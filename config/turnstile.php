<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cloudflare Turnstile Configuration
    |--------------------------------------------------------------------------
    |
    | Get your keys from Cloudflare Dashboard:
    | https://dash.cloudflare.com/?to=/:account/turnstile
    |
    */
    'site_key' => env('TURNSTILE_SITE_KEY', ''),
    'secret_key' => env('TURNSTILE_SECRET_KEY', ''),
    'verify_url' => 'https://challenges.cloudflare.com/turnstile/v0/siteverify',
];
