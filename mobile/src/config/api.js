/**
 * API Configuration
 * 
 * IMPORTANT: Update SERVER_IP with your actual server IP address
 * Find your server IP: hostname -I (on Linux) or ipconfig (on Windows)
 * 
 * The app automatically detects:
 * - Android Emulator → uses 10.0.2.2 (special emulator IP)
 * - iOS Simulator → uses localhost
 * - Physical Device → uses SERVER_IP (your server's IP)
 */

import { Platform } from 'react-native';

// ============================================
// CONFIGURATION - UPDATE THIS!
// ============================================
// Your server's IP address (find with: hostname -I)
const SERVER_IP = '192.168.102.167'; // ⬅️ UPDATE THIS!
const SERVER_PORT = '8000';
// ============================================

// Determine API URL based on platform and environment
const getApiUrl = () => {
  if (__DEV__) {
    // Development mode
    if (Platform.OS === 'android') {
      // Android emulator uses special IP to access host machine
      return `http://10.0.2.2:${SERVER_PORT}/api/v1`;
    } else if (Platform.OS === 'ios') {
      // iOS simulator can use localhost
      return `http://localhost:${SERVER_PORT}/api/v1`;
    } else {
      // Physical device - must use server's IP address
      return `http://${SERVER_IP}:${SERVER_PORT}/api/v1`;
    }
  } else {
    // Production mode - update with your production domain
    return 'https://api.yourdomain.com/api/v1';
  }
};

export const API_BASE_URL = getApiUrl();

// Log the API URL for debugging (only in development)
if (__DEV__) {
  console.log('API Base URL:', API_BASE_URL);
  console.log('Platform:', Platform.OS);
  console.log('Server IP:', SERVER_IP);
}

