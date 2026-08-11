FROM php:5.6-apache

# Debian Stretch sudah EOL
RUN rm -f /etc/apt/sources.list.d/* \
    && echo "deb [trusted=yes] http://archive.debian.org/debian stretch main" \
        > /etc/apt/sources.list \
    && echo "deb [trusted=yes] http://archive.debian.org/debian-security stretch/updates main" \
        >> /etc/apt/sources.list \
    && echo 'Acquire::Check-Valid-Until "false";' \
        > /etc/apt/apt.conf.d/99no-check-valid-until \
    && echo 'Acquire::AllowInsecureRepositories "true";' \
        > /etc/apt/apt.conf.d/99allow-insecure

RUN apt-get update \
    && apt-get install -y --allow-unauthenticated \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

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

RUN a2enmod rewrite

# Development entrypoint
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh

RUN chmod +x /usr/local/bin/docker-entrypoint.sh

WORKDIR /var/www/html

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]

CMD ["apache2-foreground"]