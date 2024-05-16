#!/usr/bin/env bash
echo "Running composer"
#composer install --no-dev --working-dir=/var/www/html
composer update

echo "Clear..."
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan clear

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate:fresh --force --seed

