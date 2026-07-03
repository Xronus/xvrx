<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google reCAPTCHA v3 Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for Google reCAPTCHA v3.
    | You can get your site key and secret key from:
    | https://www.google.com/recaptcha/admin
    |
    */

    'site_key' => env('RECAPTCHA_SITE_KEY', ''),
    'secret_key' => env('RECAPTCHA_SECRET_KEY', ''),
    'verify_url' => 'https://www.google.com/recaptcha/api/siteverify',
    'min_score' => env('RECAPTCHA_MIN_SCORE', 0.5),
];
