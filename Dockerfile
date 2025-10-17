# -------------------------------
# Étape 1 : Builder frontend avec Node
# -------------------------------
FROM node:18 AS frontend-builder
WORKDIR /app

# Copier uniquement les fichiers nécessaires à npm ci pour utiliser le cache
COPY package*.json vite.config.* postcss.config.* tailwind.config.* ./
RUN npm ci

# Copier le reste des assets frontend et builder
COPY resources/ ./resources/
COPY public/ ./public/
RUN npm run build

# -------------------------------
# Étape 2 : Installer les dépendances PHP avec Composer
# -------------------------------
FROM composer:latest AS composer-builder
WORKDIR /var/www/html

# Copier juste composer.json et composer.lock pour cache
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# -------------------------------
# Étape 3 : Image PHP finale
# -------------------------------
FROM php:8.2-fpm

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copier le code Laravel complet
COPY . .

# Copier les vendor depuis l'étape Composer
COPY --from=composer-builder /var/www/html/vendor ./vendor

# Copier les assets buildés depuis l'étape frontend
COPY --from=frontend-builder /app/public/build ./public/build

# Permissions Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 storage \
    && chmod -R 755 bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
