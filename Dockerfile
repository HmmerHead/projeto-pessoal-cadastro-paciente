FROM php:8.2-fpm

ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0" \
    PHP_OPCACHE_MAX_ACCELERATED_FILES="10000" \
    PHP_OPCACHE_MEMORY_CONSUMPTION="200" \
    PHP_OPCACHE_MAX_WASTED_PERCENTAGE="10"

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    wget \
    unzip \
    libpq-dev

RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd sockets opcache

RUN pecl install xdebug-3.2.0 \
    && docker-php-ext-enable xdebug

COPY .docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY .docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

COPY .docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN wget https://github.com/infection/infection/releases/download/0.27.0/infection.phar \
        && chmod +x infection.phar \
        && mv infection.phar /usr/local/bin/infection

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

EXPOSE 9000
