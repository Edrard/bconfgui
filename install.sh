#!/bin/bash

chmod -R 775 storage bootstrap/cache

composer install --no-dev --optimize-autoloader
npm install --production
npm run build

php artisan app:init-config

php artisan migrate
php artisan db:seed

php artisan moonshine:user

php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan optimize:clear

