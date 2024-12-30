FROM php:7.4-fpm

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo_mysql

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar permisos de Laravel
WORKDIR /var/www/html
