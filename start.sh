#!/usr/bin/env bash
set -e

echo "===> Menyiapkan link penyimpanan publik..."
php artisan storage:link --force || true

echo "===> Menjalankan migrasi database..."
php artisan migrate --force

echo "===> Memeriksa & menjalankan seeder awal jika diperlukan..."
php artisan db:seed --force || true

echo "===> Mengoptimalkan cache konfigurasi, route, dan view..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "===> Memulai server aplikasi Laravel pada port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
