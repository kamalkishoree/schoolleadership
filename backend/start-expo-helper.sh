#!/bin/bash

# Helper script to start Laravel API server for mobile app
# This ensures the server is accessible from the network

echo "=========================================="
echo "Starting Laravel API Server"
echo "=========================================="
echo ""
echo "Server will be accessible at:"
echo "  - http://$(hostname -I | awk '{print $1}'):8000"
echo "  - http://localhost:8000"
echo ""
echo "Make sure port 8000 is open in firewall:"
echo "  sudo ufw allow 8000/tcp"
echo ""
echo "=========================================="
echo ""

cd "$(dirname "$0")"
php artisan serve --host=0.0.0.0 --port=8000

