# Инструкция на русском языке

Полная инструкция по установке и настройке сайта сервера World of Warcraft на базе Laravel 11.

## Требования

- **PHP** >= 8.2
- **Composer** >= 2.0
- **MySQL** >= 5.7 или MariaDB >= 10.3
- **Расширения PHP**: `pdo_mysql`, `mbstring`, `openssl`, `json`, `tokenizer`, `xml`, `ctype`, `fileinfo`
- **Веб-сервер**: Apache с mod_rewrite или Nginx

## Установка

### 1. Клонирование репозитория

```bash
git clone https://github.com/PowerpuffIO/3.3.5a-website.git
cd 3.3.5a-website
```

### 2. Установка зависимостей

```bash
composer install
```

### 3. Настройка окружения

Скопируйте файл `.env.example` в `.env`:

```bash
cp .env.example .env
```

Сгенерируйте ключ приложения:

```bash
php artisan key:generate
```

### 4. Настройка базы данных

Откройте файл `.env` и настройте подключения к базам данных:

#### Основная база данных (для сайта)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wow_website
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### База данных игрового сервера (Auth)

```env
GAME_AUTH_DB_HOST=127.0.0.1
GAME_AUTH_DB_PORT=3306
GAME_AUTH_DB_DATABASE=auth
GAME_AUTH_DB_USERNAME=root
GAME_AUTH_DB_PASSWORD=your_password
```

#### База данных игрового сервера (Characters)

```env
GAME_CHAR_DB_HOST=127.0.0.1
GAME_CHAR_DB_PORT=3306
GAME_CHAR_DB_DATABASE=characters
GAME_CHAR_DB_USERNAME=root
GAME_CHAR_DB_PASSWORD=your_password
```

**Важно:** Убедитесь, что база данных для сайта создана в MySQL:

```sql
CREATE DATABASE wow_website CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Выполнение миграций

```bash
php artisan migrate
```

### 6. Настройка прав доступа

Установите права на запись для директорий `storage` и `bootstrap/cache`:

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

На Windows используйте соответствующие права доступа через свойства папок.

### 7. Создание символической ссылки для storage

```bash
php artisan storage:link
```

Это создаст ссылку из `public/storage` на `storage/app/public` для доступа к загруженным файлам.

### 8. Настройка капчи (защита форм входа и регистрации)

Защита форм регистрации и авторизации поддерживает три режима: **Google reCAPTCHA v3**, **Cloudflare Turnstile** или **отключена**. Выбор режима задаётся в `.env` переменной `CAPTCHA_METHOD`.

#### Выбор метода капчи

В файле `.env` укажите один из вариантов:

```env
# CAPTCHA_METHOD=google    — использовать Google reCAPTCHA v3
# CAPTCHA_METHOD=cloudflare — использовать Cloudflare Turnstile
# CAPTCHA_METHOD=false     — капча отключена (удобно для разработки)
CAPTCHA_METHOD=google
```

#### Вариант A: Google reCAPTCHA v3 (`CAPTCHA_METHOD=google`)

1. Получите ключи reCAPTCHA:
   - Перейдите на [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin)
   - Нажмите «+» для создания нового сайта
   - Выберите тип **reCAPTCHA v3**
   - Добавьте домен вашего сайта
   - Скопируйте **Site Key** и **Secret Key**

2. Добавьте в `.env`:

```env
CAPTCHA_METHOD=google
RECAPTCHA_SITE_KEY=your_site_key_here
RECAPTCHA_SECRET_KEY=your_secret_key_here
RECAPTCHA_MIN_SCORE=0.5
```

**Примечание:** `RECAPTCHA_MIN_SCORE` — минимальный балл (0.0–1.0) для прохождения проверки. Рекомендуется: 0.5.

#### Вариант B: Cloudflare Turnstile (`CAPTCHA_METHOD=cloudflare`)

1. Получите ключи Turnstile:
   - Войдите в [Cloudflare Dashboard](https://dash.cloudflare.com/)
   - Перейдите в **Turnstile** (в боковом меню или по ссылке [Turnstile](https://dash.cloudflare.com/?to=/:account/turnstile))
   - Создайте виджет, при необходимости укажите домен
   - Скопируйте **Site Key** и **Secret Key**

2. Добавьте в `.env`:

```env
CAPTCHA_METHOD=cloudflare
TURNSTILE_SITE_KEY=your_turnstile_site_key
TURNSTILE_SECRET_KEY=your_turnstile_secret_key
```

#### Вариант C: Капча отключена (`CAPTCHA_METHOD=false`)

Для локальной разработки можно отключить проверку:

```env
CAPTCHA_METHOD=false
```

В этом случае ключи reCAPTCHA и Turnstile не требуются.

## Настройка веб-сервера

### Apache

Убедитесь, что включен модуль `mod_rewrite`. Создайте файл `.htaccess` в корне проекта (если его нет) или используйте следующую конфигурацию:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

Настройте виртуальный хост, указывающий на директорию `public`:

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /path/to/3.3.5a-website/public

    <Directory /path/to/3.3.5a-website/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Nginx

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/3.3.5a-website/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Первоначальная настройка

### 1. Создание администратора

Создайте первого администратора через Tinker:

```bash
php artisan tinker
```

```php
$admin = new App\Models\Admin();
$admin->username = 'admin';
$admin->password = Hash::make('your_secure_password');
$admin->email = 'admin@example.com';
$admin->save();
```

### 2. Настройка сайта через админ-панель

1. Войдите в админ-панель: `http://your-domain.com/powerpuffsiteadmin`
2. Перейдите в раздел **Настройки**
3. Заполните основные параметры сайта (название, описание)
4. Настройте языки в разделе **Языки**

### 3. Загрузка логотипа

1. Перейдите в **Настройки** → **Управление логотипом**
2. Загрузите логотип (файл будет сохранен в `/public/powerpuffsite/images/logo/`)
3. Установите загруженный логотип как текущий

### 4. Импорт игровых аккаунтов (опционально)

Если у вас есть база данных игрового сервера:

1. Убедитесь, что настроены подключения `GAME_AUTH_DB_*` в `.env`
2. Перейдите в **Настройки** → **Парсер игровых аккаунтов**
3. Настройте параметры импорта:
   - **Размер пакета**: количество аккаунтов за один импорт (по умолчанию 100)
   - **Домен email по умолчанию**: для аккаунтов без email (например, `example.com`)
4. Нажмите **Парсить аккаунты**

**Важно:** Парсер использует SRP6 аутентификацию. Salt и verifier из игровой БД будут автоматически преобразованы в нужный формат.

## Структура проекта

```
├── app/                    # Основная логика приложения
│   ├── Http/Controllers/   # Контроллеры
│   ├── Models/             # Eloquent модели
│   └── Services/           # Сервисы (SRP6 и др.)
├── config/                 # Конфигурационные файлы
├── database/
│   └── migrations/         # Миграции базы данных
├── lang/                   # Файлы локализации (ru, en, de, es)
├── public/                 # Публичная директория (корень сайта)
│   └── powerpuffsite/     # Статические файлы (CSS, JS, изображения)
├── resources/
│   └── views/              # Blade шаблоны
└── routes/
    └── web.php             # Маршруты приложения
```

## Основные функции

- **Многоязычность**: Поддержка русского, английского, немецкого и испанского языков
- **Новости**: Система новостей с поиском и пагинацией
- **Личный кабинет**: Управление персонажами, бонусами, голосованием
- **Админ-панель**: Полное управление контентом сайта
- **Парсер аккаунтов**: Импорт аккаунтов из игровой БД
- **Управление логотипом**: Загрузка и смена логотипа через админ-панель
- **Таблица лидеров**: Отображение топовых персонажей

## Полезные команды

```bash
# Очистка кэша
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Оптимизация (для production)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Просмотр маршрутов
php artisan route:list
```

## Безопасность

- Никогда не коммитьте файл `.env` в репозиторий
- Используйте сильные пароли для администраторов
- Регулярно обновляйте зависимости: `composer update`
- Настройте правильные права доступа к файлам и директориям
- Используйте HTTPS в production окружении

## Поддержка

При возникновении проблем проверьте логи:

```bash
tail -f storage/logs/laravel.log
```

## Прочее и контакты

1. Просьба не удалять и не редактировать указанного на сайте разработчика.

2. Ссылки для связи:
   - **Site**: [https://powerpuff.pro/](https://powerpuff.pro/)
   - **Telegram**: [https://t.me/powerpuff_io](https://t.me/powerpuff_io)
   - **Discord**: [https://discord.gg/QwCsWtP99A](https://discord.gg/QwCsWtP99A)
