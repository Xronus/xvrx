#!/bin/sh
set -eu

php artisan migrate --force
php artisan db:seed --force
php artisan optimize:clear

echo "Application database is ready."
