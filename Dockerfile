# ======================================================
# Étape 1 : Build du frontend (Vite, Tailwind, etc.)
# ======================================================
FROM node:18 AS frontend

WORKDIR /app

# Copie des dépendances front
COPY package*.json ./
RUN npm ci --include=dev

# Copie du code frontend
COPY resources/ ./resources/
COPY public/ ./public/

# Build de la version de production
RUN npm run build


# ======================================================
# Étape 2 : Build de l'application PHP/Laravel
# ======================================================
FROM php:8.2-fpm

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copie de Composer depuis l’image officielle
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définition du dossier de travail
WORKDIR /var/www/html

# Copie de tout le projet Laravel
COPY . ./

# Copie des assets frontend buildés depuis le stage précédent
COPY --from=frontend /app/public/build ./public/build

# Installation des dépendances PHP
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# Optimisation de Laravel (cache config, routes, vues)
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Fix des permissions
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# ======================================================
# Étape 3 : Nginx pour exposer Laravel sur le port 8080
# ======================================================
FROM nginx:alpine

# Copie de la config Nginx personnalisée
COPY ./nginx.conf /etc/nginx/conf.d/default.conf

# Copie du code Laravel depuis le conteneur PHP
COPY --from=0 /var/www/html /var/www/html

# Exposition du port attendu par Railway
EXPOSE 8080

CMD ["nginx", "-g", "daemon off;"]
