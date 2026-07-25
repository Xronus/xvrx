<?php

return [
    // How long (in minutes) does the admin 2FA session last before requiring re-verification
    'admin_session_ttl' => env('TWOFA_ADMIN_SESSION_TTL', 30),

    // Number of digits in the TOTP code (6 or 8)
    'code_digits' => 6,

    // TOTP window (how many periods backward/forward to check for clock drift)
    // 1 period = 30 seconds, so window=1 checks current, previous, and next periods
    'window' => 1,

    // Number of recovery codes to generate during setup
    'recovery_codes_count' => 8,

    // Length of each recovery code
    'recovery_code_length' => 12,
];
