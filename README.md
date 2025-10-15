# Salaire - Laravel Application

A Laravel-based salary management application with Docker deployment for Railway.app.

## Features

- Employee management
- Department management
- Salary calculations
- Admin dashboard
- Authentication system

## Prerequisites

- Docker
- Docker Compose

## Local Development

1. Clone the repository
2. Copy `.env.example` to `.env` and configure your environment variables
3. Run the application:

```bash
docker-compose up --build
```

The application will be available at `http://localhost:8000`

## Production Deployment (Railway.app)

1. Connect your GitHub repository to Railway.app
2. Railway will automatically detect the `Dockerfile` and `docker-compose.yml`
3. Set the following environment variables in Railway:
   - `APP_KEY` (generate with `php artisan key:generate --show`)
   - `MYSQL_ROOT_PASSWORD` (secure password for database)
   - `APP_ENV=production`
   - `APP_DEBUG=false`

## Environment Variables

Key environment variables for production:

- `APP_ENV=production`
- `APP_KEY=<your-app-key>`
- `APP_DEBUG=false`
- `DB_CONNECTION=mysql`
- `DB_HOST=db`
- `DB_DATABASE=salaire`
- `DB_USERNAME=root`
- `DB_PASSWORD=<mysql-root-password>`
- `MYSQL_ROOT_PASSWORD=<mysql-root-password>`

## Database Migration

After deployment, run database migrations:

```bash
php artisan migrate
```

## Building Assets

Assets are automatically built during the Docker build process using multi-stage builds for optimization.

## Architecture

- **Frontend**: Nginx (reverse proxy)
- **Backend**: PHP 8.2 + Laravel + PHP-FPM
- **Database**: MySQL 8.0
- **Assets**: Node.js 18 (build stage only)

## Health Checks

The application includes health checks for both PHP-FPM and Nginx services.
