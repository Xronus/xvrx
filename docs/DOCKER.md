# Локальный запуск в Docker

Стек включает PHP-FPM 8.4.27, Nginx, MySQL 8.4, очередь Laravel, scheduler и локальный SOAP mock для магазина.

## Запуск

```bash
docker compose up -d --build
docker compose ps
```

Сайт будет доступен по адресу <http://localhost:8010>.

При первом запуске Docker автоматически:

1. создаёт базы `wow_website`, `auth` и `characters`;
2. применяет Laravel-миграции;
3. создаёт локальные тестовые аккаунты;
4. запускает queue worker и scheduler;
5. подключает SOAP mock, возвращающий успешный ответ для тестовых покупок.

## Тестовые аккаунты

| Роль | Логин | Пароль | Email |
|---|---|---|---|
| Администратор | `admin` | `Admin123!` | `admin@example.test` |
| Пользователь | `test` | `User123!` | `test@example.test` |

Учётные записи предназначены только для локального окружения `APP_ENV=local`.

## Полезные команды

```bash
# Статус контейнеров
docker compose ps

# Логи всего стека
docker compose logs -f

# Логи приложения и очереди
docker compose logs -f app queue

# Тесты
docker compose run --rm test

# Состояние миграций
docker compose exec app php artisan migrate:status

# Сброс 2FA локального администратора
docker compose exec app php artisan 2fa:reset admin@example.test

# Остановить контейнеры без удаления данных
docker compose down
```

Тесты принудительно используют SQLite in-memory из `phpunit.xml` и не изменяют
локальную MySQL-базу контейнера.

Полный сброс локальных данных выполняется командой `docker compose down -v`. Она удаляет Docker volumes и все локальные данные MySQL.

## Переопределение портов и паролей

Значения можно передать через shell или локальный `.env`, например:

```bash
APP_FORWARD_PORT=8080 MYSQL_FORWARD_PORT=3308 docker compose up -d
```

Основные переменные:

| Переменная | Значение по умолчанию |
|---|---|
| `APP_FORWARD_PORT` | `8010` |
| `MYSQL_FORWARD_PORT` | `3307` |
| `MYSQL_ROOT_PASSWORD` | `local-dev-root-password` |
| `APP_URL` | `http://localhost:8010` |
| `DOCKER_APP_KEY` | фиксированный локальный ключ из compose-файла |

Локальные значения нельзя переносить в production.
