# API Configuration Guide

## Setting Up API URL for Mobile App

The mobile app needs to connect to your Laravel backend API. Update the configuration based on your setup:

### 1. Update Server IP

Edit `src/config/api.js` and update the `SERVER_IP` constant:

```javascript
const SERVER_IP = '192.168.102.167'; // Change to your server's IP
```

### 2. Platform-Specific URLs

The app automatically uses the correct URL based on platform:

- **Android Emulator**: `http://10.0.2.2:8000/api/v1`
- **iOS Simulator**: `http://localhost:8000/api/v1`
- **Physical Device**: `http://YOUR_SERVER_IP:8000/api/v1`

### 3. Backend Configuration

Make sure your Laravel backend:

1. **Runs on port 8000** (or update the port in `api.js`)
2. **Allows CORS** from mobile app (already configured)
3. **Sanctum allows your IP** (already configured)

### 4. Testing Connection

1. Start Laravel server:
   ```bash
   cd backend
   php artisan serve --host=0.0.0.0 --port=8000
   ```

2. Test API from mobile device:
   - Make sure mobile device is on the same network
   - Update `SERVER_IP` in `src/config/api.js`
   - Restart the Expo app

### 5. Firewall Configuration

If port 8000 is blocked, you may need to:

```bash
# Allow port 8000
sudo ufw allow 8000/tcp

# Or for specific IP
sudo ufw allow from 192.168.102.0/24 to any port 8000
```

### 6. Production Setup

For production, update the production URL in `src/config/api.js`:

```javascript
return 'https://api.yourdomain.com/api/v1';
```

And ensure your production server:
- Has SSL certificate (HTTPS)
- Allows CORS from your app domain
- Has proper firewall rules

