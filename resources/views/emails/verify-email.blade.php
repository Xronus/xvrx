<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: Arial, sans-serif; background: #0a0f19; color: #e0e0e0; padding: 40px;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr><td align="center">
            <table width="560" cellpadding="0" cellspacing="0" style="background: #121826; border-radius: 12px; padding: 32px;">
                <tr><td align="center" style="padding-bottom: 24px;">
                    <h1 style="color: #d4af37; margin: 0;">{{ config('app.name', 'XVRX') }}</h1>
                </td></tr>
                <tr><td style="padding-bottom: 16px;">
                    <p style="font-size: 16px; line-height: 1.5; margin: 0;">{{ __('main.verify_email_line1') }}</p>
                </td></tr>
                <tr><td align="center" style="padding: 24px 0;">
                    <a href="{{ $url }}" style="display: inline-block; background: #d4af37; color: #0a0f19; padding: 14px 32px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;">
                        {{ __('main.verify_email_action') }}
                    </a>
                </td></tr>
                <tr><td style="padding-top: 16px;">
                    <p style="font-size: 13px; color: #888; margin: 0;">{{ __('main.verify_email_line2') }}</p>
                </td></tr>
            </table>
        </td></tr>
    </table>
</body>
</html>
