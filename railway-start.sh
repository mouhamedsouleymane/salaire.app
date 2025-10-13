#!/bin/bash

# Install dependencies
composer install --no-dev --optimize-autoloader --no-interaction

# Generate key if not exists
php artisan key:generate --force

# Run migrations
php artisan migrate --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start server
php artisan serve --host=0.0.0.0 --port=$PORT
