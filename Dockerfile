# Étape 1 : Builder l'app Laravel
FROM php:8.3-fpm AS php-builder

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN php artisan config:cache && php artisan route:cache

RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Étape finale : image légère de prod
FROM php:8.3-fpm

WORKDIR /var/www/html
COPY --from=php-builder /var/www/html /var/www/html

EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
