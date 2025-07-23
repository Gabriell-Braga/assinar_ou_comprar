FROM php:7.4-fpm-alpine3.16

RUN apk update && apk add --no-cache shadow openssl \
    bash \
    nano \
    git \
    openssh-client \
    gnupg \
    libzip-dev \
    zip unzip \
    nodejs npm \
    nginx \
    python3 \
    py3-crcmod \
    py3-openssl \
    $PHPIZE_DEPS 

## Instalação de composer
COPY --from=composer:2.1.3 /usr/bin/composer /usr/bin/composer

ENV HOST=0.0.0.0
ENV WATCHPACK_POLLING=true
ENV CHOKIDAR_USEPOLLING=true

# Instalação de yarn
RUN npm install yarn -g

RUN docker-php-ext-install exif zip bcmath && \
    pecl install redis && docker-php-ext-enable redis

COPY nginx.conf /etc/nginx/http.d/default.conf

WORKDIR /var/www

RUN rm -rf /var/www && \
    usermod -u 1000 www-data && \
    adduser www-data nginx && \
    ln -s /home/www-data /var/www && \
    chmod -R 770 /var/lib/nginx && \
    chmod -R 770 /var/log/nginx && \
    chmod -R 770 /run/nginx

USER www-data

ENV SHELL /bin/bash

EXPOSE 9000 80 3000