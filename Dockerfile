FROM php:8.2.1-fpm

RUN apt-get update && apt-get install -y libpq-dev zip unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

USER 1000