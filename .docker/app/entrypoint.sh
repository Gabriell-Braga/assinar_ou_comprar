#!/bin/bash

if [ ! -f ".env" ]; then
    touch /home/www-data/.bashrc | echo "PS1='\w\$ '" >> /home/www-data/.bashrc
    cp .env.example .env
    npm install
    composer install
    php artisan key:generate
fi

php-fpm -D

nginx -g 'daemon off;'