# Utilise une image PHP + Nginx prête à l’emploi
FROM webdevops/php-nginx:8.3

# Répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers du projet
COPY . .

# Installer les dépendances PHP (sans dev)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Générer la clé d'application
RUN php artisan key:generate --force

# Mettre en cache les configurations
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# Donner les bons droits
RUN chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache

# Exposer le port HTTP standard pour Railway
EXPOSE 8080

# Indiquer à Nginx le port à utiliser
ENV WEB_DOCUMENT_ROOT=/var/www/html/public
ENV APP_ENV=production
ENV APP_DEBUG=false

# Commande de démarrage
CMD ["supervisord"]
