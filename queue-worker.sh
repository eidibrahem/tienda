#!/bin/bash

# Start Queue Worker
php artisan queue:work --verbose --tries=3 --timeout=90

