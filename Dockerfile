FROM composer:latest AS base

WORKDIR /app

COPY . .

RUN composer install \
    --no-dev \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

FROM php:8.3-fpm-alpine

WORKDIR /var/www

RUN docker-php-ext-install pdo_mysql

COPY . .
COPY --from=base /app/vendor ./vendor

RUN echo "upload_max_filesize = 10M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 10M" >> /usr/local/etc/php/conf.d/uploads.ini

CMD [ "/usr/local/bin/php", "artisan", "serve", "--host=0.0.0.0", "--port=80" ]