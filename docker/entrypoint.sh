#!/usr/bin/env bash
set -e

# Pastikan folder database dan SQLite file tersedia
mkdir -p /var/www/html/database
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    DB_FILE="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
    if [ ! -f "$DB_FILE" ]; then
        touch "$DB_FILE"
    fi
fi

# Berikan hak akses penuh ke www-data untuk storage, bootstrap/cache, dan database
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Buat symlink storage ke public jika belum ada
php artisan storage:link --force || true

# Jalankan migrasi database dan buat data admin awal
php artisan migrate --force --seed || true

# Optimasi cache Laravel untuk production
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

exec "$@"
