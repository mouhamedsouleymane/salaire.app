# Étape 1 : Build du frontend avec Vite
FROM node:18 AS frontend

WORKDIR /app

# Copie des fichiers nécessaires à l'installation
COPY package*.json vite.config.* postcss.config.* tailwind.config.* ./

# Installation complète (inclut les devDependencies pour Vite)
RUN npm ci

# Copie du reste du frontend
COPY resources/ ./resources/
COPY public/ ./public/

# Build des assets frontend
RUN npm run build

# Étape 2 : Installation de Composer
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

# Copie de Composer depuis l'image précédente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copie du code source Laravel
COPY . .

# Copie des dépendances PHP
COPY --from=vendor /var/www/html/vendor ./vendor

# Copie des assets buildés par Vite
COPY --from=frontend /app/public/build ./public/build

# Permissions Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 storage \
    && chmod -R 755 bootstrap/cache

EXPOSE 9000

HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD php -v || exit 1

CMD ["php-fpm"]
