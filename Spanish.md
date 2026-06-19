# Instrucciones en español

Guía completa de instalación y configuración del sitio web para servidor de World of Warcraft basado en Laravel 11.

## Requisitos

- **PHP** >= 8.2
- **Composer** >= 2.0
- **MySQL** >= 5.7 o MariaDB >= 10.3
- **Extensiones PHP**: `pdo_mysql`, `mbstring`, `openssl`, `json`, `tokenizer`, `xml`, `ctype`, `fileinfo`
- **Servidor web**: Apache con mod_rewrite o Nginx

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/PowerpuffIO/3.3.5a-website.git
cd 3.3.5a-website
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configuración del entorno

Copie el archivo `.env.example` a `.env`:

```bash
cp .env.example .env
```

Genere la clave de la aplicación:

```bash
php artisan key:generate
```

### 4. Configuración de la base de datos

Edite el archivo `.env` y configure las conexiones a las bases de datos:

#### Base de datos principal (para el sitio web)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wow_website
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### Base de datos del servidor de juego (Auth)

```env
GAME_AUTH_DB_HOST=127.0.0.1
GAME_AUTH_DB_PORT=3306
GAME_AUTH_DB_DATABASE=auth
GAME_AUTH_DB_USERNAME=root
GAME_AUTH_DB_PASSWORD=your_password
```

#### Base de datos del servidor de juego (Characters)

```env
GAME_CHAR_DB_HOST=127.0.0.1
GAME_CHAR_DB_PORT=3306
GAME_CHAR_DB_DATABASE=characters
GAME_CHAR_DB_USERNAME=root
GAME_CHAR_DB_PASSWORD=your_password
```

**Importante:** Asegúrese de que la base de datos del sitio web exista en MySQL:

```sql
CREATE DATABASE wow_website CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Ejecutar migraciones

```bash
php artisan migrate
```

### 6. Establecer permisos

Conceda permisos de escritura a `storage` y `bootstrap/cache`:

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

En Windows, establezca los permisos adecuados mediante las propiedades de la carpeta.

### 7. Crear enlace simbólico de storage

```bash
php artisan storage:link
```

Esto crea un enlace desde `public/storage` a `storage/app/public` para los archivos subidos.

### 8. Configuración de la captcha (protección de inicio de sesión y registro)

Los formularios de inicio de sesión y registro pueden protegerse con **Google reCAPTCHA v3**, **Cloudflare Turnstile**, o la captcha puede **desactivarse**. El modo se establece en `.env` con la variable `CAPTCHA_METHOD`.

#### Elegir el método de captcha

En `.env`, establezca una de las siguientes opciones:

```env
# CAPTCHA_METHOD=google     — usar Google reCAPTCHA v3
# CAPTCHA_METHOD=cloudflare — usar Cloudflare Turnstile
# CAPTCHA_METHOD=false      — captcha desactivada (cómodo para desarrollo)
CAPTCHA_METHOD=google
```

#### Opción A: Google reCAPTCHA v3 (`CAPTCHA_METHOD=google`)

1. Obtenga las claves de reCAPTCHA en [Consola de administración de Google reCAPTCHA](https://www.google.com/recaptcha/admin).
2. Añada en `.env`: `CAPTCHA_METHOD=google`, `RECAPTCHA_SITE_KEY`, `RECAPTCHA_SECRET_KEY`, `RECAPTCHA_MIN_SCORE=0.5`.

#### Opción B: Cloudflare Turnstile (`CAPTCHA_METHOD=cloudflare`)

1. Obtenga las claves en [Cloudflare Turnstile](https://dash.cloudflare.com/?to=/:account/turnstile).
2. Añada en `.env`: `CAPTCHA_METHOD=cloudflare`, `TURNSTILE_SITE_KEY`, `TURNSTILE_SECRET_KEY`.

#### Opción C: Captcha desactivada (`CAPTCHA_METHOD=false`)

Para desarrollo local puede desactivar la verificación; no se requieren claves.

## Configuración del servidor web

Configure Apache o Nginx con el document root en la carpeta `public` del proyecto. Consulte los ejemplos en [English.md](English.md).

## Configuración inicial

1. Cree el primer administrador con `php artisan tinker` (modelo `App\Models\Admin`).
2. Inicie sesión en `http://your-domain.com/powerpuffsiteadmin` y configure idiomas, logo y parámetros.
3. Opcional: use el parser de cuentas para importar desde la base de datos del juego.

## Estructura del proyecto

```
├── app/                    # Lógica de la aplicación
├── config/                 # Archivos de configuración
├── database/migrations/    # Migraciones
├── lang/                   # Localización (ru, en, de, es, fr)
├── public/                 # Raíz web
├── resources/views/        # Plantillas Blade
└── routes/web.php          # Rutas
```

## Funciones principales

- **Multilingüe**: Ruso, inglés, alemán, español, francés
- **Noticias**, **cabina de usuario**, **panel de administración**, **parser de cuentas**, **gestión del logotipo**, **tabla de clasificación**

## Comandos útiles

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:list
```

## Seguridad y soporte

- No suba el archivo `.env` al repositorio.
- Use HTTPS en producción.
- Logs: `storage/logs/laravel.log`

## Contactos

- **Sitio**: [https://powerpuff.pro/](https://powerpuff.pro/)
- **Telegram**: [https://t.me/powerpuff_io](https://t.me/powerpuff_io)
- **Discord**: [https://discord.gg/QwCsWtP99A](https://discord.gg/QwCsWtP99A)
