# Expo Network Connection Setup

## Problem: Mobile Device Can't Connect to Expo Dev Server

If you see "Metro waiting on exp://192.168.102.167:8081" but the app shows a loader and can't connect, follow these steps:

## Solution 1: Use LAN Mode (Recommended)

The app is already configured to use `--lan` mode. Start Expo with:

```bash
cd mobile
npm start
# or
npx expo start --lan
```

This makes Expo accessible from your local network.

## Solution 2: Allow Port 8081 in Firewall

Expo Metro bundler uses port 8081. Make sure it's accessible:

```bash
# Check if port 8081 is open
sudo netstat -tulpn | grep 8081

# Allow port 8081
sudo ufw allow 8081/tcp

# Or allow from your network
sudo ufw allow from 192.168.0.0/16 to any port 8081
```

## Solution 3: Use Tunnel Mode (If LAN Doesn't Work)

If you're on different networks or having firewall issues:

```bash
cd mobile
npm run start:tunnel
```

This uses Expo's tunnel service (slower but works across networks).

## Solution 4: Check Network Connection

1. **Ensure same WiFi network:**
   - Server and mobile device must be on the same WiFi
   - Mobile data won't work

2. **Test connection from mobile browser:**
   ```
   http://192.168.102.167:8081
   ```
   Should show Expo dev tools or Metro bundler info

3. **Verify server IP:**
   ```bash
   hostname -I
   ```

## Solution 5: Manual IP Configuration

If automatic detection fails, manually set the IP:

1. Start Expo:
   ```bash
   EXPO_DEVTOOLS_LISTEN_ADDRESS=0.0.0.0 npx expo start --lan
   ```

2. Or set in `.expo/settings.json`:
   ```json
   {
     "hostType": "lan",
     "lanType": "ip"
   }
   ```

## Solution 6: Use Development Build (Production Alternative)

For production-like testing without Expo Go:

```bash
# Build development version
npx expo run:android
# or
npx expo run:ios
```

## Troubleshooting Steps

1. **Check Expo is running:**
   ```bash
   # Should see Metro bundler running
   ps aux | grep expo
   ```

2. **Check port 8081 is listening:**
   ```bash
   sudo netstat -tulpn | grep 8081
   # Should show: 0.0.0.0:8081 or 192.168.102.167:8081
   ```

3. **Test from mobile browser:**
   - Open: `http://192.168.102.167:8081`
   - Should see Metro bundler or Expo page

4. **Clear Expo cache:**
   ```bash
   npx expo start -c
   ```

5. **Check firewall:**
   ```bash
   sudo ufw status verbose
   ```

## Current Configuration

- **Expo Mode**: LAN (configured in package.json)
- **Metro Port**: 8081
- **API Port**: 8000 (separate from Expo)
- **Server IP**: 192.168.102.167

## Quick Fix Commands

```bash
# 1. Allow ports
sudo ufw allow 8081/tcp
sudo ufw allow 8000/tcp

# 2. Start Expo in LAN mode
cd mobile
npm start

# 3. If still not working, use tunnel
npm run start:tunnel
```

## Important Notes

- **Port 8081** = Expo Metro bundler (for app code)
- **Port 8000** = Laravel API (for backend)
- Both ports need to be accessible from mobile device
- Both devices must be on same WiFi network

