# Stage 1: Install Composer Dependencies
FROM composer:2.7 AS composer_builder
WORKDIR /app
COPY composer.json composer.lock ./
# Install without dev dependencies and optimize autoloader (we ignore scripts for now to avoid errors without full code)
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-reqs

# Stage 2: Build Frontend Assets (Vite/Vue)
FROM node:20-alpine AS node_builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
# Copy vendor from composer to make Ziggy available for Vite
COPY --from=composer_builder /app/vendor ./vendor
RUN npm run build

# Stage 3: PHP & Application
FROM php:8.2-fpm-alpine

# Set working directory
WORKDIR /var/www

# Install system dependencies & PHP extensions
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    zip \
    unzip \
    bash \
    mysql-client

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . .

# Copy vendor from Stage 1
COPY --from=composer_builder /app/vendor /var/www/vendor

# Run composer dump-autoload / scripts since we now have the full code
RUN composer dump-autoload --optimize \
    && composer run-script post-root-package-install --no-interaction \
    && composer run-script post-create-project-cmd --no-interaction || true

# Copy frontend build from Stage 2
COPY --from=node_builder /app/public/build /var/www/public/build

# Make entrypoint executable
RUN chmod +x /var/www/docker/entrypoint.sh

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Set entrypoint
ENTRYPOINT ["/bin/sh", "/var/www/docker/entrypoint.sh"]
CMD ["php-fpm"]
