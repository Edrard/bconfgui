#!/bin/bash

user=$(stat -c '%U' update.sh)
group=$(stat -c '%G' update.sh)

git_version=$(git ls-remote https://github.com/Edrard/bconfgui.git HEAD | cut -f1)
local_version=$(git rev-parse HEAD)
if [[ ${local_version} != ${git_version} ]]; then
    git fetch --all
    git reset --hard origin/main
    git pull
fi

export COMPOSER_ALLOW_SUPERUSER=1
composer self-update --2
yes | composer update --no-dev
export COMPOSER_ALLOW_SUPERUSER=0

chmod -R 775 storage bootstrap/cache

composer update --no-dev --optimize-autoloader
npm update --production
npm run build --omit=dev

php artisan migrate

php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan optimize:clear

chown ${user}:${group} * -R
chmod 777 update.sh