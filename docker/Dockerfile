FROM node:20.14.0-slim AS build

WORKDIR app
COPY . .
#RUN yarn install
#RUN yarn build

FROM nginx:1.27.0-alpine as nginx

WORKDIR /var/www/html
COPY --chown=nginx --from=build ./app/public ./public
COPY docker/nginx/ /
EXPOSE 80 443

CMD ["nginx", "-g", "daemon off;"]

FROM php:8.3-fpm-alpine3.18 AS base

ARG PHP_OPCACHE_VALIDATE_TIMESTAMPS_ARG=0

# Composer settings
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_CACHE_DIR /dev/null
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=$PHP_OPCACHE_VALIDATE_TIMESTAMPS_ARG

# Install composer
COPY --from=composer:2.6.6 /usr/bin/composer /usr/bin/composer

# Install OS Packages
RUN apk update \
    && apk add --no-cache \
        linux-headers \
        apache2-utils \
        autoconf \
        curl \
        openssl1.1-compat \
        g++ \
        libzip-dev \
        libpng-dev \
        imagemagick-dev \
        make \
        supervisor \
        tini \
        zlib-dev \
        libpq-dev \
    && pecl install redis \
    && docker-php-ext-install \
        pdo_mysql \
        gd \
        zip \
        sockets \
        pcntl \
    && docker-php-ext-enable redis \
    && docker-php-ext-configure gd

COPY docker/php/ /
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

WORKDIR /var/www/html

COPY --from=build ./app .

RUN chmod -R 777 /var/www/html/storage

# Install Composer packages without development package
# RUN composer install

FROM base AS development

CMD ["php-fpm"]

FROM base AS app

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["laravel-app"]

FROM base as batch

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord-worker.conf"]

FROM base as scheduler

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord-schedule.conf"]
