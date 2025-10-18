# =============================
# Étape 1 : PHP & Composer
# =============================
FROM php:8.3-fpm AS php-builder

# Installer dépendances système
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copier composer depuis image officielle
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail
WORKDIR /var/www/html

# Copier les fichiers du projet Laravel
COPY . .

# Installer les dépendances PHP (prod only)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Optimiser l'application
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Droits d'accès
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# =============================
# Étape finale : image légère
# =============================
FROM php:8.3-fpm

WORKDIR /var/www/html

COPY --from=php-builder /var/www/html /var/www/html

EXPOSE 9000

CMD ["php-fpm"]
