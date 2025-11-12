#!/bin/bash

# Create required Laravel directories
mkdir -p bootstrap/cache
mkdir -p storage/logs
mkdir -p storage/framework/{cache,sessions,views}

# Build frontend assets
npm ci && npm run build
