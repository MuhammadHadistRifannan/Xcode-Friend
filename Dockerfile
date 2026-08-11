FROM php:5.6-apache

# ============================================================
# Debian Stretch sudah EOL.
# Gunakan Debian archive.
# ============================================================

RUN rm -f /etc/apt/sources.list.d/* \
    && echo "deb [trusted=yes] http://archive.debian.org/debian stretch main" \
        > /etc/apt/sources.list \
    && echo "deb [trusted=yes] http://archive.debian.org/debian-security stretch/updates main" \
        >> /etc/apt/sources.list \
    && echo 'Acquire::Check-Valid-Until "false";' \
        > /etc/apt/apt.conf.d/99no-check-valid-until \
    && echo 'Acquire::AllowInsecureRepositories "true";' \
        > /etc/apt/apt.conf.d/99allow-insecure

# ============================================================
# Dependencies
# ============================================================

RUN apt-get update \
    && apt-get install -y --allow-unauthenticated \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

# ============================================================
# PHP extensions required by JCow
# ============================================================

RUN docker-php-ext-configure gd \
        --with-freetype-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install \
        gd \
        mysql \
        mysqli \
        pdo \
        pdo_mysql \
        mbstring \
        xml

# ============================================================
# Apache
# ============================================================

RUN a2enmod rewrite

# ============================================================
# JCow
# ============================================================

COPY . /var/www/html/

# ============================================================
# Permissions
# ============================================================

RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;

# JCow writable directories
RUN chmod -R 775 \
    /var/www/html/files \
    /var/www/html/uploads \
    /var/www/html/my

WORKDIR /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]