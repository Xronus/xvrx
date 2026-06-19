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
DB_PASSWORD=ascent

GAME_AUTH_DB_HOST=127.0.0.1
GAME_AUTH_DB_PORT=3306
GAME_AUTH_DB_DATABASE=auth
GAME_AUTH_DB_USERNAME=root
GAME_AUTH_DB_PASSWORD=ascent

GAME_CHAR_DB_HOST=127.0.0.1
GAME_CHAR_DB_PORT=3306
GAME_CHAR_DB_DATABASE=characters
GAME_CHAR_DB_USERNAME=root
GAME_CHAR_DB_PASSWORD=ascent

TRINITY_DB_HOST=127.0.0.1
TRINITY_DB_PORT=3306
TRINITY_DB_DATABASE=characters
TRINITY_DB_USERNAME=root
TRINITY_DB_PASSWORD=ascent

CAPTCHA_METHOD=false
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

Для production включите капчу через `CAPTCHA_METHOD=google` или `CAPTCHA_METHOD=cloudflare` и заполните соответствующие ключи.

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

4. Заполнить `.env` реальными доступами к базам сайта, `auth` и `characters`.

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

7. Выставить права на запись:

```bash
chmod -R ug+rw storage bootstrap/cache
```

8. Настроить web root на каталог `public`.

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
