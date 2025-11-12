#!/bin/bash

# Install PHP dependencies if composer.json exists
if [ -f "composer.json" ]; then
    # Download and use Composer
    curl -sS https://getcomposer.org/installer | php
    php composer.phar install --no-dev --optimize-autoloader --no-interaction
    
    # Create required Laravel directories
    mkdir -p bootstrap/cache
    mkdir -p storage/logs
    mkdir -p storage/framework/{cache,sessions,views}
    
    # Set proper permissions
    chmod -R 755 bootstrap/cache
    chmod -R 755 storage
fi

# Build frontend assets
npm ci && npm run build