FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    npm nodejs supervisor

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip mbstring

# Install Node.js & npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
  && apt-get install -y nodejs

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

RUN mkdir -p /var/www/storage/logs

# Use supervisor to run multiple processes (php artisan serve + npm run dev)
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf


# Expose Laravel and Vite ports
EXPOSE 8000 5173

CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]