# Étape 1 : Build frontend assets (si tu utilises Vite, Tailwind, etc.)
FROM node:18 AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --only=production

COPY resources/ ./resources/
COPY public/ ./public/
RUN npm run build

# Étape 2 : Composer install
FROM composer:latest AS vendor

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Étape 3 : Image PHP finale
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copie du code Laravel
COPY . ./

# Copie des dépendances PHP
COPY --from=vendor /var/www/html/vendor ./vendor

# Copie des assets frontend buildés
COPY --from=frontend /app/public/build ./public/build

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 storage \
    && chmod -R 755 bootstrap/cache

EXPOSE 9000

HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD php -v || exit 1

CMD ["php-fpm"]
# Note : N'oublie pas de configurer ton serveur web (Nginx/Apache) pour pointer vers le dossier public/ de Laravel.
