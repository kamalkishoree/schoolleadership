#!/bin/bash

# Start Expo in LAN mode for mobile device access
# This makes the Expo dev server accessible from your network

echo "=========================================="
echo "Starting Expo Dev Server (LAN Mode)"
echo "=========================================="
echo ""
echo "Server IP: $(hostname -I | awk '{print $1}')"
echo "Metro will be available at: exp://$(hostname -I | awk '{print $1}'):8081"
echo ""
echo "Make sure:"
echo "  1. Port 8081 is open: sudo ufw allow 8081/tcp"
echo "  2. Mobile device is on same WiFi network"
echo "  3. Scan QR code with Expo Go app"
echo ""
echo "If connection fails, try: npm run start:tunnel"
echo "=========================================="
echo ""

cd "$(dirname "$0")"
EXPO_DEVTOOLS_LISTEN_ADDRESS=0.0.0.0 npx expo start --lan

