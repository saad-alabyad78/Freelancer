#!/usr/bin/env bash
echo "Running composer"
#composer install --no-dev --working-dir=/var/www/html
composer update

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate:fresh --force --seed

echo "Running queue"
php artisan queue:work
