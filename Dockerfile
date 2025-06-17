# Use the official PHP 8.2 image with FPM
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing Laravel project files into the container
COPY . .

# Install PHP dependencies via Composer
RUN composer install

# Set correct file permissions
RUN chown -R www-data:www-data /var/www

# Expose port 8000 for Artisan serve
EXPOSE 8000

# Start Laravel development server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
