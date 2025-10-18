# Étape 1 : Builder l'app Laravel
FROM php:8.3-fpm AS php-builder

# Installer dépendances système et extensions PHP
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copier le code source
COPY . .

# Installer les dépendances Laravel
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Optimiser le cache Laravel
RUN php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

# Donner les droits nécessaires
RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Étape finale
FROM php:8.3-fpm

WORKDIR /var/www/html

COPY --from=php-builder /var/www/html /var/www/html

# Exposer le port Railway
EXPOSE 8000

# Commande de lancement Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000
