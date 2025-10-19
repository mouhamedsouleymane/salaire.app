# Étape 1 : Builder Laravel
FROM php:8.3-fpm AS build

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev libonig-dev libpq-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

RUN chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache

# Étape 2 : Image finale
FROM php:8.3-fpm

WORKDIR /var/www/html
COPY --from=build /var/www/html /var/www/html

# Exposer le port standard de Laravel sur Railway
ENV PORT=8000
EXPOSE 8000

# Lancer Laravel sur le port fourni par Railway
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT}
