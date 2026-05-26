# Build frontend
FROM node:20 AS node-build
WORKDIR /app/frontend
COPY frontend/package*.json ./
COPY frontend/package-lock.json ./
RUN npm ci
COPY frontend/ ./
RUN npm run build

# Build PHP app with Apache
FROM php:8.2-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libpq-dev \
    && docker-php-ext-install zip pdo pdo_pgsql mbstring gd bcmath \
    && rm -rf /var/lib/apt/lists/*

# Copy composer (from official image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Copy built frontend into the repo so Laravel can serve it
COPY --from=node-build /app/frontend/dist /var/www/html/frontend/dist

RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

# Install PHP dependencies
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction || true

EXPOSE 80
CMD ["apache2-foreground"]
