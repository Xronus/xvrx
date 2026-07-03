<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Captcha Method
    |--------------------------------------------------------------------------
    |
    | google    - Google reCAPTCHA v3
    | cloudflare - Cloudflare Turnstile
    | false      - Captcha disabled
    |
    */
    'method' => env('CAPTCHA_METHOD', 'false'),
];
