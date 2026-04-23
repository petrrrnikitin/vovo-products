FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    bash \
    curl \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    zip \
    unzip \
    autoconf \
    g++ \
    make \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        zip \
        gd \
        pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del autoconf g++ make

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts

COPY . .
RUN composer dump-autoload --optimize

COPY docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]