FROM php:8.2-fpm-alpine

# Install library pendukung & driver postgres
RUN apk add --no-cache nginx wget postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql

WORKDIR /var/www
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# Perintah sakti: Migrate lalu jalankan server
CMD php artisan migrate --force; php -S 0.0.0.0:10000 -t public