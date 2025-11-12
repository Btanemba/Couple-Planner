#!/bin/bash

# Install PHP dependencies (production only)
composer install --optimize-autoloader --no-dev --no-interaction

# Install Node dependencies
npm ci

# Build assets
npm run build

# Create storage directories
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
