# Mobile App Network Setup Guide

## Quick Setup

### 1. Update Server IP in Mobile App

Edit `mobile/src/config/api.js` and update the `SERVER_IP`:

```javascript
const SERVER_IP = '192.168.102.167'; // ⬅️ Change to your server's IP
```

**Find your server IP:**
```bash
hostname -I
# or
ip addr show
```

### 2. Start Backend Server (Network Accessible)

**Option A: Use the startup script**
```bash
cd backend
./start-server.sh
```

**Option B: Manual start**
```bash
cd backend
php artisan serve --host=0.0.0.0 --port=8000
```

The `--host=0.0.0.0` flag makes the server accessible from your network.

### 3. Configure Firewall (if needed)

If port 8000 is blocked:

```bash
# Allow port 8000
sudo ufw allow 8000/tcp

# Or allow from specific network
sudo ufw allow from 192.168.0.0/16 to any port 8000
```

### 4. Test Connection

1. **From your computer:**
   ```bash
   curl http://192.168.102.167:8000/api/v1/login
   ```

2. **From mobile device browser:**
   - Open: `http://192.168.102.167:8000/api/v1/login`
   - Should see JSON response or error (not connection refused)

### 5. Platform-Specific URLs

The app automatically uses the correct URL:

- **Android Emulator**: `http://10.0.2.2:8000/api/v1` ✅
- **iOS Simulator**: `http://localhost:8000/api/v1` ✅
- **Physical Device**: `http://YOUR_SERVER_IP:8000/api/v1` ✅

### 6. Troubleshooting

**Problem: Can't connect from mobile device**

1. **Check server is running:**
   ```bash
   netstat -tulpn | grep 8000
   ```

2. **Check firewall:**
   ```bash
   sudo ufw status
   ```

3. **Check server IP:**
   ```bash
   hostname -I
   ```

4. **Test from mobile browser:**
   - Open `http://YOUR_SERVER_IP:8000/api/v1/login`
   - If it works in browser, the app should work too

5. **Check mobile device is on same network:**
   - Server and mobile device must be on the same WiFi network
   - Mobile data won't work (use same local network)

**Problem: CORS errors**

- Already configured in `backend/config/cors.php`
- Allows all origins (`'allowed_origins' => ['*']`)

**Problem: Sanctum authentication fails**

- Check `SANCTUM_STATEFUL_DOMAINS` in `.env`
- Should include your server IP

### 7. Production Setup

For production deployment:

1. Update `mobile/src/config/api.js`:
   ```javascript
   return 'https://api.yourdomain.com/api/v1';
   ```

2. Use HTTPS (required for production)
3. Update CORS to allow your production domain
4. Configure proper firewall rules

## Current Configuration

- **Server IP**: 192.168.102.167
- **Server Port**: 8000
- **API Base URL**: `http://192.168.102.167:8000/api/v1`
- **CORS**: Enabled for all origins
- **Sanctum**: Configured for mobile access

