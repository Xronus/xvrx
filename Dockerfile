FROM composer:2.8 AS composer

FROM php:8.5.12-fpm-bookworm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        default-mysql-client \
        git \
        libgmp-dev \
        libzip-dev \
        unzip \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        gmp \
        pcntl \
        pdo_mysql \
        zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --no-scripts

COPY . .
COPY docker/php/local.ini /usr/local/etc/php/conf.d/99-xvrx.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/zz-xvrx.conf
COPY docker/php/bootstrap.sh /usr/local/bin/bootstrap-app

RUN chmod +x /usr/local/bin/bootstrap-app \
    && composer dump-autoload --optimize --no-interaction \
    && mkdir -p \
        bootstrap/cache \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
    && chown -R www-data:www-data bootstrap/cache storage

EXPOSE 9000

CMD ["php-fpm"]
