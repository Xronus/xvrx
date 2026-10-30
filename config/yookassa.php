<?php

return [
    'api_url' => env('YOOKASSA_API_URL', 'https://api.yookassa.ru/v3'),
    'shop_id' => env('YOOKASSA_SHOP_ID'),
    'secret_key' => env('YOOKASSA_SECRET_KEY'),
    'bonus_per_ruble' => max(1, (int) env('YOOKASSA_BONUS_PER_RUBLE', 1)),
    'min_amount' => max(1, (int) env('YOOKASSA_MIN_AMOUNT', 10)),
    'max_amount' => max(1, (int) env('YOOKASSA_MAX_AMOUNT', 100000)),
    'allow_test_payments' => filter_var(env('YOOKASSA_ALLOW_TEST_PAYMENTS', false), FILTER_VALIDATE_BOOL),
    'receipt' => [
        'enabled' => filter_var(env('YOOKASSA_RECEIPT_ENABLED', false), FILTER_VALIDATE_BOOL),
        'vat_code' => (int) env('YOOKASSA_RECEIPT_VAT_CODE', 1),
    ],
];
