FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    zip \
    && apt-get clean \
&& curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo pdo_mysql

COPY nginx/php-custom.ini /usr/local/etc/php/conf.d/

WORKDIR /var/www
