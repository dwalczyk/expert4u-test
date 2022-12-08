FROM php:8.1-fpm

RUN apt update \
    && apt install -y git libzip-dev zip \
    && docker-php-ext-install pdo pdo_mysql zip \
    && docker-php-ext-configure zip

WORKDIR /var/www/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/bin --filename=composer && chmod +x /usr/bin/composer