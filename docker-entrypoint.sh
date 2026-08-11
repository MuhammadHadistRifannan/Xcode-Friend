#!/bin/bash
set -e

echo "==> Preparing JCow development environment..."

# Source code readable
find /var/www/html -type d -exec chmod 755 {} \;
find /var/www/html -type f -exec chmod 644 {} \;

# JCow writable directories
for dir in \
    /var/www/html/files \
    /var/www/html/uploads \
    /var/www/html/my
do
    if [ -d "$dir" ]; then
        chmod -R 777 "$dir"
    fi
done

# JCow installer needs to write config.php
if [ -f /var/www/html/my/config.php ]; then
    chmod 666 /var/www/html/my/config.php
else
    touch /var/www/html/my/config.php
    chmod 666 /var/www/html/my/config.php
fi

chmod 777 /var/www/html/my/config.php
chown -R www-data:www-data /var/www/html/my


echo "==> config.php permissions:"
ls -l /var/www/html/my/config.php


echo "==> JCow permissions ready."

exec "$@"