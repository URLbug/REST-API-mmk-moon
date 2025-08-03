FROM php:8.4-fpm-alpine
LABEL authors="Timur Davydov"

WORKDIR /usr/local/apache2/htdocs

COPY --chown=www-data:www-data ./www/ .
COPY ./etc/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./etc/php/php.ini /usr/local/etc/php/php.ini
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apk update && apk --no-cache add \
    build-base \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    bash \
    fcgi \
    libmcrypt-dev \
    oniguruma-dev \
    postgresql-dev

RUN rm -rf /var/cache/apk/*

RUN docker-php-ext-configure gd \
    --with-freetype=/usr/include/ \
    --with-jpeg=/usr/include/ \
    --with-webp=/usr/include/ \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl bcmath opcache

USER 1000
CMD ["php-fpm"]