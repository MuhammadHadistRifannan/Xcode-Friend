#!/bin/bash
set -e

echo "==> Preparing JCow development environment..."

# Source code harus bisa dibaca Apache/PHP
find /var/www/html -type d -exec chmod 755 {} \;
find /var/www/html -type f -exec chmod 644 {} \;

# JCow writable directories
for dir in \
    /var/www/html/files \
    /var/www/html/uploads \
    /var/www/html/my
do
    if [ -d "$dir" ]; then
        chmod -R 775 "$dir"
    fi
done

echo "==> JCow permissions ready."

exec "$@"