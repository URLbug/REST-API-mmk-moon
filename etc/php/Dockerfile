FROM php:8.4-fpm-alpine
LABEL authors="Timur Davydov"

COPY ./www/ .

ENTRYPOINT ["php-fpm", "start"]