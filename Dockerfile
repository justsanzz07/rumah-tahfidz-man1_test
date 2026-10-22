# Stage 1: Build Frontend Assets via Node.js
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: Runtime PHP 8.4 Apache
FROM php:8.4-apache

# Install sistem dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install ekstensi PHP yang dibutuhkan Laravel
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring zip exif pcntl bcmath gd

# Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# Setup Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Atur Document Root ke public/
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# Salin source code
COPY . .

# Salin build frontend dari Stage 1
COPY --from=frontend /app/public/build ./public/build

# Install dependensi PHP untuk production (tanpa dev packages)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Berikan hak akses www-data
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Copy dan beri izin eksekusi entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Konfigurasi default environment
ENV PORT=80
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=/var/www/html/database/database.sqlite

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]
