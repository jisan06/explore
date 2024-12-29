FROM php:8.2-fpm

# Install system dependencies for PHP
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy the application code
COPY . /var/www

# Set permissions for the application
RUN chown -R www-data:www-data /var/www

# Remove node_modules directory if it exists
RUN rm -rf /var/www/node_modules

# Expose port 9000 for php-fpm
EXPOSE 9000
CMD ["php-fpm"]
