# Instructions in English

Full installation and configuration guide for the World of Warcraft server website based on Laravel 11.

## Requirements

- **PHP** >= 8.2
- **Composer** >= 2.0
- **MySQL** >= 5.7 or MariaDB >= 10.3
- **PHP extensions**: `pdo_mysql`, `mbstring`, `openssl`, `json`, `tokenizer`, `xml`, `ctype`, `fileinfo`
- **Web server**: Apache with mod_rewrite or Nginx

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/PowerpuffIO/3.3.5a-website.git
cd 3.3.5a-website
```

### 2. Install dependencies

```bash
composer install
```

### 3. Environment setup

Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

### 4. Database configuration

Edit `.env` and configure database connections:

#### Main database (for the website)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wow_website
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### Game server database (Auth)

```env
GAME_AUTH_DB_HOST=127.0.0.1
GAME_AUTH_DB_PORT=3306
GAME_AUTH_DB_DATABASE=auth
GAME_AUTH_DB_USERNAME=root
GAME_AUTH_DB_PASSWORD=your_password
```

#### Game server database (Characters)

```env
GAME_CHAR_DB_HOST=127.0.0.1
GAME_CHAR_DB_PORT=3306
GAME_CHAR_DB_DATABASE=characters
GAME_CHAR_DB_USERNAME=root
GAME_CHAR_DB_PASSWORD=your_password
```

**Important:** Ensure the website database exists in MySQL:

```sql
CREATE DATABASE wow_website CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Run migrations

```bash
php artisan migrate
```

### 6. Set permissions

Grant write permissions to `storage` and `bootstrap/cache`:

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

On Windows, set the appropriate permissions via folder properties.

### 7. Create storage symbolic link

```bash
php artisan storage:link
```

This creates a link from `public/storage` to `storage/app/public` for uploaded files.

### 8. Captcha configuration (login and registration protection)

Login and registration forms can be protected with **Google reCAPTCHA v3**, **Cloudflare Turnstile**, or captcha can be **disabled**. The mode is set in `.env` with the `CAPTCHA_METHOD` variable.

#### Choosing the captcha method

In `.env`, set one of the following:

```env
# CAPTCHA_METHOD=google     — use Google reCAPTCHA v3
# CAPTCHA_METHOD=cloudflare — use Cloudflare Turnstile
# CAPTCHA_METHOD=false      — captcha disabled (convenient for development)
CAPTCHA_METHOD=google
```

#### Option A: Google reCAPTCHA v3 (`CAPTCHA_METHOD=google`)

1. Get reCAPTCHA keys:
   - Go to [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin)
   - Click «+» to create a new site
   - Select **reCAPTCHA v3**
   - Add your site domain
   - Copy the **Site Key** and **Secret Key**

2. Add to `.env`:

```env
CAPTCHA_METHOD=google
RECAPTCHA_SITE_KEY=your_site_key_here
RECAPTCHA_SECRET_KEY=your_secret_key_here
RECAPTCHA_MIN_SCORE=0.5
```

**Note:** `RECAPTCHA_MIN_SCORE` is the minimum score (0.0–1.0) to pass verification. Recommended: 0.5.

#### Option B: Cloudflare Turnstile (`CAPTCHA_METHOD=cloudflare`)

1. Get Turnstile keys:
   - Log in to [Cloudflare Dashboard](https://dash.cloudflare.com/)
   - Open **Turnstile** (in the sidebar or via [Turnstile](https://dash.cloudflare.com/?to=/:account/turnstile))
   - Create a widget and add your domain if needed
   - Copy the **Site Key** and **Secret Key**

2. Add to `.env`:

```env
CAPTCHA_METHOD=cloudflare
TURNSTILE_SITE_KEY=your_turnstile_site_key
TURNSTILE_SECRET_KEY=your_turnstile_secret_key
```

#### Option C: Captcha disabled (`CAPTCHA_METHOD=false`)

For local development you can disable verification:

```env
CAPTCHA_METHOD=false
```

In this case, reCAPTCHA and Turnstile keys are not required.

## Web server configuration

### Apache

Ensure `mod_rewrite` is enabled. Create an `.htaccess` file in the project root if missing, or use:

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

Configure a virtual host with document root pointing to `public`:

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

## Initial setup

### 1. Create an administrator

Create the first admin user via Tinker:

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

### 2. Configure the site via admin panel

1. Log in to the admin panel: `http://your-domain.com/powerpuffsiteadmin`
2. Go to **Settings**
3. Fill in the main site parameters (name, description)
4. Configure languages under **Languages**

### 3. Upload logo

1. Go to **Settings** → **Logo management**
2. Upload a logo (file is stored in `/public/powerpuffsite/images/logo/`)
3. Set the uploaded logo as the current one

### 4. Import game accounts (optional)

If you have the game server database:

1. Ensure `GAME_AUTH_DB_*` connections are set in `.env`
2. Go to **Settings** → **Game accounts parser**
3. Set import options:
   - **Batch size**: number of accounts per import (default 100)
   - **Default email domain**: for accounts without email (e.g. `example.com`)
4. Click **Parse accounts**

**Important:** The parser uses SRP6 authentication. Salt and verifier from the game DB are converted to the required format automatically.

## Project structure

```
├── app/                    # Application logic
│   ├── Http/Controllers/   # Controllers
│   ├── Models/             # Eloquent models
│   └── Services/           # Services (SRP6, captcha, etc.)
├── config/                 # Configuration files
├── database/
│   └── migrations/         # Database migrations
├── lang/                   # Localization (ru, en, de, es)
├── public/                 # Web root
│   └── powerpuffsite/     # Static assets (CSS, JS, images)
├── resources/
│   └── views/              # Blade templates
└── routes/
    └── web.php             # Application routes
```

## Main features

- **Multilingual**: Russian, English, German, Spanish
- **News**: News system with search and pagination
- **User cabinet**: Character management, bonuses, voting
- **Admin panel**: Full content management
- **Account parser**: Import accounts from the game database
- **Logo management**: Upload and change logo via admin panel
- **Leaderboard**: Top characters

## Useful commands

```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Production optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache

# List routes
php artisan route:list
```

## Security

- Never commit `.env` to the repository
- Use strong passwords for administrators
- Keep dependencies updated: `composer update`
- Set correct file and directory permissions
- Use HTTPS in production

## Support

To troubleshoot, check the logs:

```bash
tail -f storage/logs/laravel.log
```

## Other and contacts

1. Please do not remove or edit the developer credit on the site.

2. Contact links:
   - **Site**: [https://powerpuff.pro/](https://powerpuff.pro/)
   - **Telegram**: [https://t.me/powerpuff_io](https://t.me/powerpuff_io)
   - **Discord**: [https://discord.gg/QwCsWtP99A](https://discord.gg/QwCsWtP99A)
