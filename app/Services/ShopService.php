<?php

namespace App\Services;

use App\Models\ShopItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShopService
{
    private SoapService $soap;

    public function __construct(SoapService $soap)
    {
        $this->soap = $soap;
    }

    public function buy(User $user, ShopItem $item, string $characterName): array
    {
        if (! $item->enabled) {
            return ['ok' => false, 'message' => __('main.shop_no_items')];
        }

        // Verify character belongs to user
        $gameAccountId = DB::connection('game_auth')
            ->table('account')
            ->where('username', strtoupper($user->username))
            ->value('id');

        if (! $gameAccountId) {
            return ['ok' => false, 'message' => __('main.shop_char_invalid')];
        }

        $charExists = DB::connection('game_char')
            ->table('characters')
            ->where('account', $gameAccountId)
            ->where('name', $characterName)
            ->exists();

        if (! $charExists) {
            return ['ok' => false, 'message' => __('main.shop_char_invalid')];
        }

        DB::beginTransaction();

        try {
            // Pessimistic lock to prevent race condition
            $locked = DB::table('users')->where('id', $user->id)->lockForUpdate()->value('bonuses');

            if ($locked < $item->price) {
                DB::rollBack();
                return ['ok' => false, 'message' => __('main.shop_purchase_funds')];
            }

            $user->decrement('bonuses', $item->price);

            // Sanitize character name to prevent SOAP command injection
            $safeCharName = preg_replace('/[^a-zA-Z0-9]/', '', $characterName);
            if (empty($safeCharName)) {
                DB::rollBack();
                return ['ok' => false, 'message' => __('main.shop_char_invalid')];
            }

            // Determine SOAP command action from item type
            $typeName = $item->type?->name_ru ?? 'items';
            $action = strtolower($typeName); // "Items" → "items", "Money" → "money"

            // Build SOAP command — escape quotes to prevent command injection
            $subject = str_replace('"', '\"', config('shop.mail_subject', 'Shop'));
            $body = str_replace('"', '\"', config('shop.mail_body', 'Thank you for your purchase!'));
            $command = '.send ' . $action . ' ' . $safeCharName . ' "' . $subject . '" "' . $body . '" ' . $item->item_entry . ':' . $item->quantity;

            // Вызов execute() — он возвращает строку (XML)
            $xmlResponse = $this->soap->execute($command);

            // Парсим XML, чтобы вытащить текст результата
            $doc = new \DOMDocument();
            @$doc->loadXML($xmlResponse); // @ подавляет предупреждения, если XML битый
            $resultNode = $doc->getElementsByTagName('result')->item(0);
            $soapResultText = $resultNode ? trim($resultNode->nodeValue) : 'Unknown response';

            // Простая эвристика успеха: ищем ключевые фразы от TrinityCore
            $soapSuccess = strpos($soapResultText, 'Command executed') !== false
                || strpos($soapResultText, 'OK') !== false
                || strpos($soapResultText, 'success') !== false
                || strpos($soapResultText, 'Mail sent') !== false;

            if (! $soapSuccess) {
                // Refund
                $user->increment('bonuses', $item->price);
                DB::rollBack();

                Log::error('Shop purchase SOAP failed', [
                    'user' => $user->username,
                    'item' => $item->item_entry,
                    'character' => $characterName,
                    'command' => $command,
                    'soap_response' => $soapResultText,
                ]);

                return [
                    'ok' => false,
                    'message' => __('main.shop_purchase_error'),
                    'debug' => $soapResultText,
                ];
            }

            // Log purchase
            DB::table('shop_purchases')->insert([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'character_name' => $characterName,
                'price' => $item->price,
                'item_entry' => $item->item_entry,
                'quantity' => $item->quantity,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            Log::info('Shop purchase completed', [
                'user' => $user->username,
                'item' => $item->item_entry,
                'character' => $characterName,
                'price' => $item->price,
            ]);

            return [
                'ok' => true,
                'balance' => $user->fresh()->bonuses,
                'message' => __('main.shop_purchase_ok'),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Shop purchase error: ' . $e->getMessage(), [
                'user' => $user->username,
                'item' => $item->item_entry,
                'trace' => $e->getTraceAsString(),
            ]);

            return ['ok' => false, 'message' => __('main.shop_purchase_error')];
        }
    }
}
