#!/bin/bash

# Start Laravel server accessible from network
# This allows mobile devices to connect via IP address

echo "Starting Laravel server on 0.0.0.0:8000"
echo "Server will be accessible from:"
echo "  - Local: http://localhost:8000"
echo "  - Network: http://$(hostname -I | awk '{print $1}'):8000"
echo ""
echo "Make sure your mobile device is on the same network!"
echo ""

php artisan serve --host=0.0.0.0 --port=8000

