FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y     git     unzip     libzip-dev     libpng-dev     libonig-dev     libxml2-dev     zip     curl     && docker-php-ext-install pdo_mysql zip

WORKDIR /var/www

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy existing application code
COPY . /var/www

# Install PHP dependencies (when container builds, it will run composer install if composer.json exists)
RUN if [ -f composer.json ]; then composer install --no-interaction --prefer-dist --optimize-autoloader; fi

CMD ["php-fpm"]
