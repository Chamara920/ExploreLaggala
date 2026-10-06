#!/bin/sh
set -e

echo "==> Starting Explore Laggala application..."

# 1. Fallback APP_KEY if missing in environment
if [ -z "$APP_KEY" ]; then
    echo "Warning: APP_KEY is not set. Generating a temporary application key..."
    php artisan key:generate --force
fi

# 2. SQLite database fallback setup
if [ "$DB_CONNECTION" = "sqlite" ] || [ -z "$DB_CONNECTION" ]; then
    mkdir -p /var/www/html/database
    touch /var/www/html/database/database.sqlite
    chown -R www-data:www-data /var/www/html/database
fi

# 3. Create public storage link
php artisan storage:link --force || true

# 4. Run database migrations safely
echo "==> Running database migrations..."
php artisan migrate --force || true

# 5. Fix permissions for storage and bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 6. Optimize configurations & routes
echo "==> Caching configurations and routes..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# 7. Start PHP-FPM daemon
echo "==> Starting PHP-FPM..."
php-fpm -D

# 8. Start Caddy Web Server (binding to PORT)
echo "==> Starting Caddy server on port ${PORT:-8080}..."
exec caddy run --config /var/www/html/Caddyfile --adapter caddyfile