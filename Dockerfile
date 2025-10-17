# ============================
# 🧩 Étape 1 : Build frontend
# ============================
FROM node:18 AS frontend

WORKDIR /app

# Installation des dépendances frontend
COPY frontend/package.json frontend/package-lock.json ./
RUN npm ci --only=production

# Copie des sources frontend et build
COPY frontend/resources/ ./resources/
COPY frontend/public/ ./public/
RUN npm run build

# ============================
# 🧩 Étape 2 : Composer install
# ============================
FROM composer:latest AS vendor

WORKDIR /var/www/html

# Installation des dépendances PHP
COPY backend/composer.json backend/composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ============================
# 🧩 Étape 3 : Image finale PHP
# ============================
FROM php:8.2-fpm

# 📦 Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 🔌 Extensions PHP nécessaires
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 📥 Composer depuis l'image vendor
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 📁 Répertoire de travail
WORKDIR /var/www/html

# 📦 Copie du code backend
COPY backend/ ./

# 📦 Copie des dépendances PHP
COPY --from=vendor /var/www/html/vendor ./vendor

# 📦 Copie des assets frontend buildés
COPY --from=frontend /app/public/build ./public/build

# 🔐 Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 storage \
    && chmod -R 755 bootstrap/cache

# 🌐 Exposition du port PHP-FPM
EXPOSE 9000

# 🩺 Healthcheck
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD php -v || exit 1

# 🚀 Démarrage
CMD ["php-fpm"]
