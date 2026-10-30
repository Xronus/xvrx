<?php

namespace App\Http\Controllers;

use App\Models\BalanceTopUp;
use App\Services\BalanceTopUpService;
use DomainException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class BalanceTopUpController extends Controller
{
    public function index(BalanceTopUpService $service): View
    {
        $user = auth()->user();

        return view('cabinet.balance-top-up', [
            'user' => $user,
            'settings' => site_settings(),
            'configured' => $service->configured(),
            'bonusPerRuble' => (int) config('yookassa.bonus_per_ruble'),
            'minAmount' => (int) config('yookassa.min_amount'),
            'maxAmount' => (int) config('yookassa.max_amount'),
            'topUps' => BalanceTopUp::query()
                ->where('user_id', $user->id)
                ->latest()
                ->limit(10)
                ->get(),
        ]);
    }

    public function store(Request $request, BalanceTopUpService $service): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => [
                'required',
                'integer',
                'min:'.config('yookassa.min_amount'),
                'max:'.config('yookassa.max_amount'),
            ],
        ]);

        try {
            $topUp = $service->create($request->user(), (int) $validated['amount']);

            if ($topUp->confirmation_url) {
                return redirect()->away($topUp->confirmation_url);
            }

            return redirect()->route('balance.topup')
                ->with('success', __('main.top_up_succeeded'));
        } catch (ConnectionException $exception) {
            Log::warning('YooKassa is unreachable while starting balance top-up', [
                'user_id' => $request->user()->id,
            ]);

            return back()->withInput()->with('error', __('main.top_up_provider_unavailable'));
        } catch (Throwable $exception) {
            Log::error('Unable to start balance top-up', [
                'user_id' => $request->user()->id,
                'exception' => $exception::class,
            ]);

            return back()->withInput()->with('error', __('main.top_up_start_error'));
        }
    }

    public function complete(Request $request, BalanceTopUp $topUp, BalanceTopUpService $service): RedirectResponse
    {
        abort_unless($topUp->user_id === $request->user()->id, 404);

        try {
            $topUp = $service->synchronize($topUp);
        } catch (Throwable $exception) {
            Log::warning('Unable to synchronize returned YooKassa payment', [
                'top_up_id' => $topUp->public_id,
                'exception' => $exception::class,
            ]);

            return redirect()->route('balance.topup')
                ->with('status', __('main.top_up_pending'));
        }

        return redirect()->route('balance.topup')->with(
            $topUp->credited_at ? 'success' : ($topUp->status === BalanceTopUp::STATUS_CANCELED ? 'error' : 'status'),
            $topUp->credited_at
                ? __('main.top_up_succeeded')
                : ($topUp->status === BalanceTopUp::STATUS_CANCELED ? __('main.top_up_canceled') : __('main.top_up_pending'))
        );
    }

    public function webhook(Request $request, BalanceTopUpService $service): JsonResponse
    {
        $notification = $request->json()->all();

        if (! is_array($notification) || $notification === []) {
            return response()->json(['ok' => false], 400);
        }

        try {
            $service->handleWebhook($notification);
        } catch (DomainException $exception) {
            Log::warning('Rejected YooKassa notification', [
                'reason' => $exception->getMessage(),
            ]);

            return response()->json(['ok' => false], 422);
        } catch (Throwable $exception) {
            Log::error('YooKassa notification processing failed', [
                'exception' => $exception::class,
            ]);

            return response()->json(['ok' => false], 500);
        }

        return response()->json(['ok' => true]);
    }
}
