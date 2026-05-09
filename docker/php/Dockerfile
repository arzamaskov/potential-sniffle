# syntax=docker/dockerfile:1.7

FROM php:8.3-fpm-alpine AS base

WORKDIR /var/www/html

RUN apk add --no-cache \
        bash \
        curl \
        icu-libs \
        libpq \
        libzip \
        oniguruma \
        tzdata \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev \
        libzip-dev \
        postgresql-dev \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        intl \
        opcache \
        pcntl \
        pdo_pgsql \
        zip \
    && apk del .build-deps

COPY docker/php/conf.d/00-app.ini /usr/local/etc/php/conf.d/00-app.ini
COPY docker/php/php-fpm.d/zz-app.conf /usr/local/etc/php-fpm.d/zz-app.conf

RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data /var/www/html

FROM base AS development

RUN apk add --no-cache \
        git \
        linux-headers \
        openssh-client \
    && apk add --no-cache --virtual .xdebug-build-deps \
        $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del .xdebug-build-deps

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY docker/php/conf.d/10-opcache-dev.ini /usr/local/etc/php/conf.d/10-opcache.ini
COPY docker/php/conf.d/20-xdebug.ini /usr/local/etc/php/conf.d/20-xdebug.ini

RUN mkdir -p /tmp/composer-cache \
    && chown -R www-data:www-data /tmp/composer-cache

ENV APP_ENV=local \
    COMPOSER_CACHE_DIR=/tmp/composer-cache \
    PHP_IDE_CONFIG=serverName=runtracker-laravel \
    XDEBUG_MODE=off

USER www-data

FROM base AS production

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY docker/php/conf.d/10-opcache-prod.ini /usr/local/etc/php/conf.d/10-opcache.ini
COPY --chown=www-data:www-data . /var/www/html

ENV APP_ENV=production \
    COMPOSER_ALLOW_SUPERUSER=1

RUN if [ -f composer.json ]; then \
        composer install \
            --no-dev \
            --prefer-dist \
            --no-interaction \
            --no-progress \
            --optimize-autoloader \
            --classmap-authoritative; \
    fi \
    && if [ -d storage ] && [ -d bootstrap/cache ]; then \
        chown -R www-data:www-data storage bootstrap/cache; \
    fi \
    && rm -rf /tmp/* /var/cache/apk/*

USER www-data

CMD ["php-fpm"]
