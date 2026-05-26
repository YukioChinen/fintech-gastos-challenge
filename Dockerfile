# =========================
# Build frontend (Vite/React)
# =========================
FROM node:20 AS node-build

WORKDIR /app/frontend

# Ensure devDependencies (e.g. vite) are available during image build.
ENV NODE_ENV=development

# Copia package files
COPY frontend/package*.json ./

# Instala dependências
RUN npm ci --include=dev

# Copia restante do frontend
COPY frontend/ ./

# Build do frontend
RUN npm run build


# =========================
# PHP + Apache (Laravel)
# =========================
FROM php:8.2-apache

# Instala dependências do sistema e extensões PHP
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libpq-dev \
    && docker-php-ext-install \
    zip \
    pdo \
    pdo_pgsql \
    pdo_mysql \
    mbstring \
    gd \
    bcmath \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Diretório da aplicação
WORKDIR /var/www/html

# Copia arquivos do Laravel
COPY . /var/www/html

# Copia build do frontend para public/build
COPY --from=node-build /app/frontend/dist /var/www/html/public/build

# Instala dependências PHP
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# Configura Apache para usar /public
RUN a2enmod rewrite \
    && sed -ri 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# Cria diretórios necessários do Laravel
RUN mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    database \
    && touch database/database.sqlite

# Permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Porta
EXPOSE 80

# Inicializa Apache
CMD ["apache2-foreground"]