# -------------------------------
# Étape 1 : Builder PHP + Composer
# -------------------------------
FROM composer:latest AS composer-builder

WORKDIR /var/www/html

# Copier tout le code Laravel pour que composer et artisan puissent fonctionner
COPY . .

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# -------------------------------
# Étape 2 : Builder Frontend (Node + Vite)
# -------------------------------
FROM node:18 AS node-builder

WORKDIR /app

# Copier les fichiers nécessaires pour npm install
COPY package*.json ./
RUN npm install

# Copier le reste du code frontend (ressources, etc.)
COPY resources/ ./resources/
COPY vite.config.js ./
COPY tailwind.config.js ./

# Builder le frontend
RUN npm run build

# -------------------------------
# Étape 3 : Image PHP finale
# -------------------------------
FROM php:8.2-fpm

# Installer les extensions nécessaires
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copier le code Laravel et les dépendances depuis composer-builder
COPY --from=composer-builder /var/www/html /var/www/html

# Copier le build frontend depuis node-builder
COPY --from=node-builder /app/dist /var/www/html/public/build

# Définir les permissions correctes
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Exposer le port PHP-FPM
EXPOSE 9000

# Commande par défaut
CMD ["php-fpm"]
