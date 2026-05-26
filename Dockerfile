# Build frontend
FROM node:20 AS node-build
WORKDIR /app/frontend
#Ensure devDependencies are installed so `vite` is available for the build
ENV NODE_ENV=development
COPY frontend/package*.json ./
COPY frontend/package-lock.json ./
RUN npm ci --include=dev
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
    && docker-php-ext-install zip pdo pdo_pgsql pdo_mysql mbstring gd bcmath \
    && rm -rf /var/lib/apt/lists/*

# Copy composer (from official image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Copy built frontend into the repo so Laravel can serve it
COPY --from=node-build /app/frontend/dist /var/www/html/frontend/dist

RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite \
    && sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Create runtime directories Laravel needs when using file-based sessions/cache.
RUN mkdir -p /var/www/html/storage/framework/cache/data \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs \
    /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Install PHP dependencies
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction || true

EXPOSE 80
CMD ["apache2-foreground"]
