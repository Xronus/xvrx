# Интеграция ЮKassa

Сайт использует API ЮKassa с HTTP Basic Auth и сценарием подтверждения `redirect`.
Бонусы начисляются только после статуса `succeeded`, подтверждённого отдельным
GET-запросом сайта к API ЮKassa.

## Настройка окружения

```dotenv
YOOKASSA_SHOP_ID=идентификатор_магазина
YOOKASSA_SECRET_KEY=секретный_ключ
YOOKASSA_BONUS_PER_RUBLE=1
YOOKASSA_MIN_AMOUNT=10
YOOKASSA_MAX_AMOUNT=100000
YOOKASSA_ALLOW_TEST_PAYMENTS=false
```

Для локального тестового магазина установите
`YOOKASSA_ALLOW_TEST_PAYMENTS=true`. На production оставьте `false` до перехода
на боевые реквизиты.

Если ЮKassa формирует чеки для магазина, дополнительно включите:

```dotenv
YOOKASSA_RECEIPT_ENABLED=true
YOOKASSA_RECEIPT_VAT_CODE=1
```

Код НДС необходимо выбрать в соответствии с настройками магазина и системой
налогообложения.

## Webhook

В кабинете ЮKassa в разделе «Интеграция → HTTP-уведомления» укажите:

```text
https://ваш-домен/payments/yookassa/webhook
```

Подключите события:

- `payment.succeeded`
- `payment.canceled`

ЮKassa принимает webhook URL только по HTTPS на порту 443 или 8443 с TLS 1.2+
и ожидает ответ HTTP 200. Локальный `localhost` для webhook не подходит.

## Развёртывание и проверка

```bash
php artisan migrate --force
php artisan optimize:clear
docker compose --profile test run --rm test
```

Проверьте тестовый платёж, повторную доставку webhook и начисление бонусов ровно
один раз. Секретный ключ нельзя добавлять в Git, логи или клиентский JavaScript.

Официальная документация:

- https://yookassa.ru/developers/payment-acceptance/getting-started/payment-process
- https://yookassa.ru/developers/using-api/interaction-format
- https://yookassa.ru/developers/using-api/webhooks
- https://yookassa.ru/developers/payment-acceptance/testing-and-going-live/testing
