FROM php:8.4-fpm-alpine
LABEL authors="Timur Davydov"

RUN mkdir -p /usr/local/apache2/htdocs
WORKDIR /usr/local/apache2/htdocs

COPY ./www/ .
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apk --no-cache add \
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

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring zip exif pcntl bcmath opcache

RUN find . -type f -exec chown www-data:www-data {} \;
RUN find . -type d -exec chown www-data:www-data {} \;
RUN find . -type f -exec chmod 644 {} \;
RUN find . -type d -exec chmod 755 {} \;

CMD ["php-fpm"]