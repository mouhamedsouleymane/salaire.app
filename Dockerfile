# Étape 1 : Builder Laravel
FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libonig-dev \
    libpq-dev \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip sodium

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -sL https://deb.nodesource.com/setup_18.x | bash && \
    && apt-get update && apt-get install -y nodejs

WORKDIR /var/www/html
COPY . .


# Exposer le port standard de Laravel sur Railway

EXPOSE 8000


RUN composer install
RUN npm install
RUN chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache


# Lancer Laravel sur le port fourni par Railway
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000
