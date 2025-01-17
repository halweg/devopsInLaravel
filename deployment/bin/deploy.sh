#!/bin/bash

set -e

MYSQL_PASSWORD=$1

PROJECT_DIR="/var/www/html/posts"

# make dir if not exists (first deploy)
mkdir -p $PROJECT_DIR

cd $PROJECT_DIR

git config --global --add safe.directory $PROJECT_DIR

# the project has not been cloned yet (first deploy)
if [ ! -d $PROJECT_DIR"/.git" ]; then
  GIT_SSH_COMMAND='ssh -i /home/martin/.ssh/id_rsa -o IdentitiesOnly=yes' git clone git@github.com:mmartinjoo/devops-with-laravel-sample.git .
else
  GIT_SSH_COMMAND='ssh -i /home/martin/.ssh/id_rsa -o IdentitiesOnly=yes' git pull
fi

cd $PROJECT_DIR"/frontend"
npm install
npm run build

cd $PROJECT_DIR"/api"

composer install --no-interaction --optimize-autoloader --no-dev

# initialize .env if does not exist (first deploy)
if [ ! -f $PROJECT_DIR"/api/.env" ]; then
    cp .env.example .env
    sed -i "/DB_PASSWORD/c\DB_PASSWORD=$MYSQL_PASSWORD" $PROJECT_DIR"/api/.env"
    sed -i '/QUEUE_CONNECTION/c\QUEUE_CONNECTION=database' $PROJECT_DIR"/api/.env"
    php artisan key:generate
fi

sudo chown -R www-data:www-data $PROJECT_DIR

php artisan storage:link
php artisan optimize:clear

php artisan down

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan up

sudo cp $PROJECT_DIR"/deployment/config/php-fpm/www.conf" /etc/php/8.1/fpm/pool.d/www.conf
sudo cp $PROJECT_DIR"/deployment/config/php-fpm/php.ini" /etc/php/8.1/fpm/conf.d/php.ini
sudo systemctl restart php8.1-fpm.service

sudo cp $PROJECT_DIR"/deployment/config/nginx.conf" /etc/nginx/nginx.conf
# test the config so if it's not valid we don't try to reload it
sudo nginx -t
sudo systemctl reload nginx

sudo cp $PROJECT_DIR"/deployment/config/supervisor/supervisord.conf" /etc/supervisor/conf.d/supervisord.conf
# update the config
sudo supervisorctl update
# restart workers (notice the : at the end. it refers to the process group)
sudo supervisorctl restart workers:
