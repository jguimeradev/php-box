FROM php:8.4-cli-alpine

# Set working directory
WORKDIR /app

# Install system dependencies
RUN apk add --no-cache \
    sqlite \
    sqlite-libs \
    curl \
    git \
    nano \
    bash

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_sqlite \
    sqlite3 \
    curl \
    mbstring \
    xml \
    json \
    intl

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy project files
COPY . .

# Install dependencies
RUN composer install

# Create necessary directories
RUN mkdir -p auth/src/database

# Expose port
EXPOSE 6969

# Set environment variables
ENV PHP_MEMORY_LIMIT=512M \
    PHP_POST_MAX_SIZE=100M \
    PHP_UPLOAD_MAX_FILESIZE=100M

# Start development server
CMD ["php", "-S", "0.0.0.0:6969", "-t", "auth/public/"]
