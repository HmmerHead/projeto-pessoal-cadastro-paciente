FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev

RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd sockets

RUN pecl install xdebug-3.2.0 \
    && docker-php-ext-enable xdebug

COPY .docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

WORKDIR /var/www

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Cache Composer dependencies
WORKDIR /tmp
ADD composer.json composer.lock /tmp/
RUN mkdir -p database/seeds \
    mkdir -p database/factories \
    && composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    && rm -rf composer.json composer.lock \
    database/ vendor/

RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

COPY . /var/www

WORKDIR /var/www

RUN composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

RUN ls

EXPOSE 9000
