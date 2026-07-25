<?php

declare(strict_types=1);

namespace App\Services;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorAuthService
{
    protected Google2FA $google2fa;

    public function __construct(?Google2FA $google2fa = null)
    {
        $this->google2fa = $google2fa ?? new Google2FA;
    }

    /**
     * Generate a new TOTP secret key.
     */
    public function generateSecret(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    /**
     * Generate a QR code data URI for Google Authenticator setup.
     */
    public function getQRCodeInline(string $company, string $email, string $secret): string
    {
        $qrCodeUrl = $this->google2fa->getQRCodeUrl($company, $email, $secret);

        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new SvgImageBackEnd
        );
        $writer = new Writer($renderer);

        return 'data:image/svg+xml;base64,' . base64_encode($writer->writeString($qrCodeUrl));
    }

    /**
     * Verify a TOTP code against a secret.
     */
    public function verify(string $secret, string $code): bool
    {
        if (mb_strlen($code) !== config('2fa.code_digits', 6)) {
            return false;
        }

        return $this->google2fa->verifyKey(
            $secret,
            $code,
            config('2fa.window', 1)
        );
    }

    /**
     * Generate recovery codes (plaintext, shown once to the user).
     */
    public function generateRecoveryCodes(): array
    {
        $count = config('2fa.recovery_codes_count', 8);
        $length = config('2fa.recovery_code_length', 12);
        $codes = [];

        for ($i = 0; $i < $count; $i++) {
            $codes[] = Str::random($length);
        }

        return $codes;
    }

    /**
     * Hash recovery codes for storage.
     */
    public function hashRecoveryCodes(array $codes): array
    {
        return array_map(fn (string $code): string => Hash::make($code), $codes);
    }

    /**
     * Verify a provided recovery code against stored hashes.
     * If a match is found, the code is consumed (removed from the array).
     *
     * Returns ['valid' => bool, 'remaining_codes' => array]
     */
    public function verifyAndConsumeRecoveryCode(string $providedCode, array $storedHashes): array
    {
        foreach ($storedHashes as $index => $hash) {
            if (Hash::check($providedCode, $hash)) {
                // Remove the used code
                array_splice($storedHashes, $index, 1);

                return [
                    'valid' => true,
                    'remaining_codes' => $storedHashes,
                ];
            }
        }

        return [
            'valid' => false,
            'remaining_codes' => $storedHashes,
        ];
    }
}
