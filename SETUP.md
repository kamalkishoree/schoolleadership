# Setup Guide - Future Skills Championship

## Quick Setup Checklist

### Backend Setup
- [ ] Install PHP 8.1+ and Composer
- [ ] Install MySQL 8.0+
- [ ] Run `composer install` in backend directory
- [ ] Copy `.env.example` to `.env`
- [ ] Generate application key: `php artisan key:generate`
- [ ] Configure database in `.env`
- [ ] Run migrations: `php artisan migrate`
- [ ] Start server: `php artisan serve`

### Mobile App Setup
- [ ] Install Node.js 18+
- [ ] Install Expo CLI: `npm install -g expo-cli`
- [ ] Run `npm install` in mobile directory
- [ ] Update API base URL in `src/services/api.js`
- [ ] Start Expo: `npm start`

### Admin Panel
- [ ] Access at `http://localhost:8000/admin`
- [ ] Create schools, classes, and students
- [ ] Create daily challenges

## Initial Data Setup

### Create a School
1. Go to Admin Panel → Schools
2. Click "Add School"
3. Fill in school details
4. Save

### Create Classes
1. Go to Admin Panel → Classes
2. Click "Add Class"
3. Select school
4. Enter class name
5. Save

### Create Students
1. Go to Admin Panel → Students
2. Click "Add Student"
3. Select school and class
4. Enter student details
5. Set password (default: phone number as per requirements)
6. Save

### Create Challenges
1. Go to Admin Panel → Challenges
2. Click "Add Challenge"
3. Fill in challenge details
4. Set active date (must be unique per day)
5. Set correct option and XP reward
6. Save

## Testing the System

### Test Student Login
1. Use student email/mobile and password
2. Login via mobile app
3. Verify dashboard loads

### Test Challenge Flow
1. Student logs in
2. Views dashboard
3. Clicks "Start Today's Challenge"
4. Selects an option
5. Submits challenge
6. Views result screen

### Test Leaderboard
1. Create multiple students
2. Have them complete challenges
3. View leaderboard in app
4. Verify rankings

## Common Issues

### API Connection Issues
- Check API base URL in mobile app
- Verify backend server is running
- Check CORS configuration
- For Android emulator, use `10.0.2.2:8000`
- For iOS simulator, use `localhost:8000`

### Database Issues
- Verify database credentials in `.env`
- Check database exists
- Run migrations: `php artisan migrate:fresh`

### Token Issues
- Clear AsyncStorage in mobile app
- Re-login to get new token
- Check Sanctum configuration

## Production Deployment

### Backend
1. Set `APP_ENV=production`
2. Set `APP_DEBUG=false`
3. Run `php artisan config:cache`
4. Run `php artisan route:cache`
5. Set up SSL certificate
6. Configure web server (Nginx/Apache)

### Mobile App
1. Update API URL to production
2. Build: `expo build:android` or `expo build:ios`
3. Submit to app stores

