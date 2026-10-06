FROM php:8.3-fpm-alpine

# Required packages & extensions
RUN apk add --no-cache \
    caddy \
    supervisor \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    nodejs \
    npm \
    icu-dev \
    oniguruma-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd zip bcmath intl opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Start script
CMD ["sh", "-c", "php artisan config:cache && php artisan route:cache && php artisan view:cache && php-fpm -D && caddy run --config Caddyfile --adapter caddyfile"]