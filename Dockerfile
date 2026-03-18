FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git zip unzip libicu-dev libzip-dev libpng-dev \
    && docker-php-ext-install intl pdo_mysql zip gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

CMD ["php-fpm"]

