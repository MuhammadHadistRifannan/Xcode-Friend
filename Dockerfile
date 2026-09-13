# syntax=docker/dockerfile:1

# ============================================================
# Stage 1 — PHP-FPM + Composer dependencies
# Builds the Laravel app, exposes php-fpm on port 9000
# (and 8080 for Laravel Reverb WebSocket)
# ============================================================
FROM php:8.4-fpm AS app

# --- System packages & PHP extensions ---
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        curl \
        nodejs \
        npm \
        default-mysql-client \
        libpng-dev \
        libjpeg-dev \
        libwebp-dev \
        libfreetype6-dev \
        libonig-dev \
        libzip-dev \
        libxml2-dev \
        libcurl4-openssl-dev \
        libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        mbstring \
        bcmath \
        exif \
        pcntl \
        zip \
        gd \
        intl \
    && docker-php-ext-enable opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# --- Composer ---
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# --- Install PHP deps first (layer cache) ---
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --no-interaction --prefer-dist

# --- Install npm deps first (layer cache) ---
COPY package.json package-lock.json ./
RUN npm ci --ignore-scripts \
        --fetch-retries=6 \
        --fetch-retry-mintimeout=20000 \
        --fetch-retry-maxtimeout=120000 \
        --fetch-timeout=600000

# --- Copy the full application ---
COPY . .

# --- Rebuild autoload with package discovery ---
RUN composer install --no-dev --no-interaction --prefer-dist \
    && composer dump-autoload --optimize --classmap-authoritative

# --- Build frontend assets (Vite) ---
RUN VITE_REMOTE_FONTS=0 npm run build \
    && rm -rf node_modules

# --- Permissions ---
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache


EXPOSE 9000
EXPOSE 8080

# Entrypoint: starts php-fpm + Reverb + queue worker
COPY docker/app/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]

# ============================================================
# Stage 2 — Nginx reverse proxy to php-fpm via FastCGI
# Serves static assets and proxies PHP to the `app` service
# ============================================================
FROM nginx:1.27-alpine AS webserver

COPY --from=app /var/www/html /var/www/html
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www/html

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]