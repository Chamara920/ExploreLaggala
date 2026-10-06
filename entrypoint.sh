#!/bin/sh

# Optimize configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link --force

# Run Web Server
exec php artisan serve --host=0.0.0.0 --port=${PORT:-80}