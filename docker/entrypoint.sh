#!/bin/sh

# Set correct permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Run Laravel migrations (force for production)
echo "Running Database Migrations..."
php artisan migrate --force

# Start PHP-FPM
echo "Starting PHP-FPM..."
exec "$@"
