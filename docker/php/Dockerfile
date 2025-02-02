FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libssl-dev \
    pkg-config \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Install MongoDB PHP Extension
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Install Node.js & NPM
RUN apt-get install -y nodejs npm

# Start PHP-FPM
CMD ["php-fpm"]
