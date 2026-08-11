# xvrx

Laravel-сайт для WoW WotLK 3.3.5a сервера: главная страница, новости, личный кабинет, регистрация игровых аккаунтов, ladder, админ-панель и интеграция с базами TrinityCore/AzerothCore-совместимого сервера.

## Стек

- PHP 8.2+
- Laravel 11
- MySQL 8 / MariaDB 10.6+
- Composer
- Docker Compose для локальной базы

## Быстрый запуск локально

```bash
git clone git@github.com:Xronus/xvrx.git
cd xvrx
composer install
cp .env.example .env
php artisan key:generate
docker compose up -d mysql
php artisan migrate
php artisan serve --host=127.0.0.1 --port=8010
```

Сайт будет доступен по адресу:

```text
http://127.0.0.1:8010
```

## Локальная база в Docker

`docker-compose.yml` поднимает MySQL-контейнер `wow-335a-mysql` и создает базы:

- `wow_website` - база сайта
- `auth` - игровая auth-база
- `characters` - игровая characters-база

Параметры по умолчанию:

```text
host: 127.0.0.1
port: 3306
user: root
password: ascent
```

Инициализационный SQL лежит в `docker/mysql/init/001-create-local-databases.sql`.

Если нужен демо-контент для сайта, после миграций можно импортировать:

```bash
mysql -h127.0.0.1 -uroot -pascent wow_website < database/local_demo_seed.sql
```

## Настройка `.env`

Минимальный локальный набор:

```env
APP_NAME=xvrx
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8010
APP_LOCALE=ru
APP_FALLBACK_LOCALE=ru

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wow_website
DB_USERNAME=root
DB_PASSWORD=change-me

GAME_AUTH_DB_HOST=127.0.0.1
GAME_AUTH_DB_PORT=3306
GAME_AUTH_DB_DATABASE=auth
GAME_AUTH_DB_USERNAME=root
GAME_AUTH_DB_PASSWORD=change-me

GAME_CHAR_DB_HOST=127.0.0.1
GAME_CHAR_DB_PORT=3306
GAME_CHAR_DB_DATABASE=characters
GAME_CHAR_DB_USERNAME=root
GAME_CHAR_DB_PASSWORD=change-me

TRINITY_DB_HOST=127.0.0.1
TRINITY_DB_PORT=3306
TRINITY_DB_DATABASE=characters
TRINITY_DB_USERNAME=root
TRINITY_DB_PASSWORD=change-me

CAPTCHA_METHOD=false
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=log
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=noreply@example.com
MAIL_PASSWORD=your-smtp-password
MAIL_ENCRYPTION=tls
MAIL_TIMEOUT=10
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

Для локального Docker MySQL замените `change-me` на пароль контейнера из `docker-compose.yml`. По умолчанию это `ascent`.

Для production включите капчу через `CAPTCHA_METHOD=google` или `CAPTCHA_METHOD=cloudflare` и заполните соответствующие ключи.

## Настройка почты

Почтовые доступы не хранятся в админке и не должны попадать в репозиторий. SMTP-сервер, логин, пароль, порт, шифрование и адрес отправителя задаются только в `.env`.

Для локальной разработки безопаснее оставить письма в логах:

```env
MAIL_MAILER=log
```

В production укажите реальные SMTP-параметры:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=noreply@example.com
MAIL_PASSWORD=your-smtp-password
MAIL_ENCRYPTION=tls
MAIL_TIMEOUT=10
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

После изменения почтовых переменных на сервере очистите кеш конфигурации:

```bash
php artisan optimize:clear
php artisan config:cache
```

В админке откройте `Настройки -> Почта`. Там можно:

- проверить, видит ли приложение SMTP-настройки;
- включить или отключить отправку писем сброса пароля;
- изменить имя отправителя, тему и текст письма сброса пароля.

Пароль SMTP в админке не показывается: отображается только признак, что он задан.

## Очередь и асинхронная отправка писем

Все письма (сброс пароля, верификация email) отправляются **асинхронно** через очередь. Mailable и Notification реализуют `ShouldQueue` — письмо ставится в очередь моментально, а отправка происходит в фоне, не блокируя HTTP-запрос.

Для работы очереди на production-сервере нужно запустить воркер:

```bash
php artisan queue:work --daemon
```

Рекомендуется настроить systemd-сервис или supervisor, чтобы воркер работал постоянно и автоматически перезапускался при падении.

### Supervisor (рекомендуемый способ)

Пример конфига `/etc/supervisor/conf.d/xvrx-worker.conf`:

```ini
[program:xvrx-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/xvrx/artisan queue:work --daemon --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/xvrx/storage/logs/worker.log
stopwaitsecs=3600
```

После создания конфига:

```bash
supervisorctl reread
supervisorctl update
supervisorctl start xvrx-worker:*
```

### Проверка очереди

```bash
# Посмотреть количество задач в очереди
php artisan queue:monitor

# Посмотреть упавшие задачи
php artisan queue:failed
```

В `.env` должно быть:

```env
QUEUE_CONNECTION=database
```

Таблицы `jobs` и `failed_jobs` создаются миграциями и уже есть в проекте.

## Холодный старт: почему сайт тормозит после простоя

После долгого неиспользования первый запрос к сайту может быть заметно медленнее. Причины и решения:

### Почему это происходит

| Причина | Механизм |
|---------|----------|
| **PHP OPcache** | PHP-FPM воркеры умирают при простое. Новый запрос форкает воркер и парсит все файлы заново |
| **MySQL Buffer Pool** | Данные вытесняются из памяти. Первый SELECT читает с диска |
| **Кеш Laravel** | TTL кешей истекает — первый хит пересчитывает всё заново |
| **Сессии в БД** | `SESSION_DRIVER=database` — сборка мусора (gc) может попасть на первый же запрос |

### Что сделано для смягчения

- **TTL кешей увеличены:** 60с → 300с, 300с → 600с. Кеш живёт дольше и реже протухает при простое
- **Крон-прогрев каждые 5 минут:** фоном обновляет все кеши главной и админ-дашборда. Даже после часов простоя первый запрос попадает в горячий кеш
- **Чистка сессий кроном:** `session:gc` выполняется по расписанию, а не лотерейно на первом хите

### Что настроить на сервере

**Crontab** (обязательно):

```bash
* * * * * cd /var/www/xvrx && php artisan schedule:run >> /dev/null 2>&1
```

**PHP-FPM** (`/etc/php/8.2/fpm/pool.d/www.conf`):

```ini
pm = dynamic
pm.min_spare_servers = 2
pm.start_servers = 2
```

Это держит минимум 2 воркера горячими, даже при нулевом трафике.

**OPcache** (`/etc/php/8.2/fpm/conf.d/10-opcache.ini`):

```ini
opcache.revalidate_freq=60
opcache.validate_timestamps=0
```

На production `validate_timestamps=0` отключает проверку изменений файлов — PHP не перечитывать исходники на каждом запросе. После деплоя перезапускать PHP-FPM.

### Проверка

После настройки проверь, что крон работает:

```bash
crontab -l
php artisan schedule:list
```

Первый запрос после ночного простоя больше не должен тормозить.

## Production-разворачивание

1. Клонировать проект:

```bash
git clone git@github.com:Xronus/xvrx.git
cd xvrx
```

2. Установить зависимости:

```bash
composer install --no-dev --optimize-autoloader
```

3. Создать окружение:

```bash
cp .env.example .env
php artisan key:generate
```

4. Заполнить `.env` реальными доступами к базам сайта, `auth`, `characters` и SMTP.

5. Выполнить миграции:

```bash
php artisan migrate --force
```

6. Подготовить кеши:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

7. Запустить воркер очереди (см. раздел «Очередь и асинхронная отправка писем»).

8. Настроить cron для фоновых задач (кеш-прогрев, чистка сессий):

```bash
* * * * * cd /var/www/xvrx && php artisan schedule:run >> /dev/null 2>&1
```

9. Выставить права на запись:

```bash
chmod -R ug+rw storage bootstrap/cache
```

10. Настроить web root на каталог `public`.

Пример Nginx:

```nginx
server {
    listen 80;
    server_name example.com;
    root /var/www/xvrx/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Полезные команды

```bash
php artisan migrate
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan serve --host=127.0.0.1 --port=8010
```

## Структура

- `resources/views` - Blade-шаблоны страниц
- `public/xvrx-assets` - визуальный стиль xvrx, изображения, шрифты, видео
- `database/migrations` - таблицы сайта
- `docker/mysql/init` - локальная демо-схема игровых баз
- `app/Services/SRP6Service.php` - SRP6-регистрация и проверка пароля WotLK 3.3.5a

## Важно

- Не коммитьте `.env`, дампы production-баз, логи и файлы из `storage`.
- Для реального сервера используйте отдельного MySQL-пользователя с минимальными правами.
- После публикации на сервере всегда выполняйте `php artisan optimize:clear`, если меняли `.env`.
