# Multi-stage Dockerfile for ForkFlash Laravel Backend
# Stage 1: Base image with common dependencies
FROM php:8.2-fpm-alpine AS base

# Set working directory
WORKDIR /var/www

# Install system dependencies (common for both dev and prod)
RUN apk add --no-cache \
    curl \
    libpng-dev \
    libxml2-dev \
    postgresql-dev \
    zip \
    unzip \
    supervisor \
    nginx

# Install PHP extensions (common for both dev and prod)
RUN docker-php-ext-install \
    pdo_pgsql \
    pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    opcache

# Install Redis extension
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user
RUN addgroup -g 1000 appgroup && \
    adduser -u 1000 -G appgroup -s /bin/sh -D appuser

# Stage 2: Development image
FROM base AS development

# Install development tools
RUN apk add --no-cache \
    git \
    nodejs \
    npm \
    bash \
    vim

# Install Xdebug for development
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps

# Copy PHP development configuration
COPY docker/php/development.ini /usr/local/etc/php/conf.d/development.ini

# Copy application files
COPY --chown=appuser:appgroup . /var/www

# Install Composer dependencies (including dev dependencies)
USER appuser
RUN composer install --optimize-autoloader

# Switch back to root for final setup
USER root

# Set proper permissions
RUN chown -R appuser:appgroup /var/www && \
    chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Expose port for PHP-FPM
EXPOSE 9000

# Switch to app user
USER appuser

# Command for development
CMD ["php-fpm"]

# Stage 3: Production image (minimal and secure)
FROM base AS production

# Production PHP configuration
COPY docker/php/production.ini /usr/local/etc/php/conf.d/production.ini

# Copy application files (excluding development files)
COPY --chown=appuser:appgroup composer.json composer.lock /var/www/
COPY --chown=appuser:appgroup app /var/www/app
COPY --chown=appuser:appgroup bootstrap /var/www/bootstrap
COPY --chown=appuser:appgroup config /var/www/config
COPY --chown=appuser:appgroup database /var/www/database
COPY --chown=appuser:appgroup public /var/www/public
COPY --chown=appuser:appgroup resources /var/www/resources
COPY --chown=appuser:appgroup routes /var/www/routes
COPY --chown=appuser:appgroup storage /var/www/storage
COPY --chown=appuser:appgroup artisan /var/www/

# Switch to app user for composer install
USER appuser

# Install only production dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts && \
    composer clear-cache

# Switch back to root for final setup
USER root

# Set proper permissions
RUN chown -R appuser:appgroup /var/www && \
    chmod -R 755 /var/www/storage /var/www/bootstrap/cache && \
    chmod -R 644 /var/www && \
    chmod +x /var/www/artisan

# Remove composer to reduce attack surface
RUN rm /usr/bin/composer

# Copy supervisor configuration for production
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port for PHP-FPM
EXPOSE 9000

# Switch to app user
USER appuser

# Use supervisor to manage PHP-FPM and queue workers in production
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
