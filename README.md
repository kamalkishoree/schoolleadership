# Future Skills Championship - Production MVP

A comprehensive gamified learning platform for schools with mobile app, admin panel, and REST API.

## 🏗️ Architecture

- **Backend**: Laravel 10+ with REST API (v1)
- **Mobile App**: React Native with Expo (iOS & Android)
- **Admin Panel**: Laravel Blade
- **Database**: MySQL
- **Authentication**: Laravel Sanctum

## 📁 Project Structure

```
MakeAIAPP/
├── backend/          # Laravel API & Admin Panel
├── mobile/           # React Native App
└── README.md
```

## 🚀 Quick Start

### Prerequisites

- PHP 8.1+
- Composer
- Node.js 18+
- MySQL 8.0+
- Expo CLI (`npm install -g expo-cli`)

### Backend Setup

1. **Navigate to backend directory:**
```bash
cd backend
```

2. **Install dependencies:**
```bash
composer install
```

3. **Configure environment:**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Update `.env` file:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fsc_db
DB_USERNAME=your_username
DB_PASSWORD=your_password

APP_URL=http://localhost:8000
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1
```

5. **Run migrations:**
```bash
php artisan migrate
```

6. **Start server:**
```bash
php artisan serve
```

The API will be available at `http://localhost:8000/api/v1`

### Mobile App Setup

1. **Navigate to mobile directory:**
```bash
cd mobile
```

2. **Install dependencies:**
```bash
npm install
```

3. **Update API base URL in `src/services/api.js`:**
```javascript
const API_BASE_URL = 'http://YOUR_IP_ADDRESS:8000/api/v1';
// For Android emulator, use: http://10.0.2.2:8000/api/v1
// For iOS simulator, use: http://localhost:8000/api/v1
```

4. **Start Expo:**
```bash
npm start
```

5. **Run on device:**
- Scan QR code with Expo Go app (iOS/Android)
- Or press `i` for iOS simulator / `a` for Android emulator

### Admin Panel

Access the admin panel at: `http://localhost:8000/admin`

## 📚 API Documentation

### Authentication Endpoints

#### Login
```http
POST /api/v1/login
Content-Type: application/json

{
  "email_or_mobile": "student@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "student": {
    "id": 1,
    "name": "John Doe",
    "email_or_mobile": "student@example.com",
    "total_xp": 100,
    "current_streak": 5
  },
  "token": "1|..."
}
```

#### Get Profile
```http
GET /api/v1/me
Authorization: Bearer {token}
```

#### Logout
```http
POST /api/v1/logout
Authorization: Bearer {token}
```

### Challenge Endpoints

#### Get Today's Challenge
```http
GET /api/v1/challenge/today
Authorization: Bearer {token}
```

#### Submit Challenge
```http
POST /api/v1/challenge/submit
Authorization: Bearer {token}
Content-Type: application/json

{
  "challenge_id": 1,
  "selected_option": "a"
}
```

### Dashboard Endpoint

```http
GET /api/v1/dashboard
Authorization: Bearer {token}
```

### Leaderboard Endpoints

#### Class Leaderboard
```http
GET /api/v1/leaderboard/class
Authorization: Bearer {token}
```

#### School Leaderboard
```http
GET /api/v1/leaderboard/school
Authorization: Bearer {token}
```

## 🎮 Core Features

### 1. Multi-School Support
- Schools can be created and managed through admin panel
- Each school has multiple classes
- Students belong to a school and class

### 2. Daily Challenge System
- One challenge per day (based on `active_date`)
- Students can submit only once per challenge
- MCQ format with 4 options
- XP reward for correct answers

### 3. XP System
- Students earn XP for correct answers
- Total XP tracked and displayed
- Used for leaderboard ranking

### 4. Streak Tracking
- Tracks consecutive days of activity
- Resets if a day is missed
- Increments on consecutive logins

### 5. Leaderboards
- Class-level leaderboard (top 20)
- School-level leaderboard (top 20)
- Ranked by total XP, then streak

## 🔒 Security Features

- **Sanctum Token Authentication**: Secure API access
- **Password Hashing**: Bcrypt encryption
- **Input Validation**: Laravel validation rules
- **CSRF Protection**: Enabled for web routes
- **Rate Limiting**: API rate limiting middleware
- **SQL Injection Protection**: Eloquent ORM
- **XSS Protection**: Blade templating
- **Duplicate Submission Prevention**: Database unique constraints

## 🧪 Testing

Run tests:
```bash
cd backend
php artisan test
```

Test coverage includes:
- Authentication flows
- Challenge submission logic
- XP and streak calculations
- Leaderboard queries
- Admin panel operations

## 📱 Mobile App Features

### Screens

1. **Login Screen**
   - Email/mobile + password authentication
   - Token storage with AsyncStorage

2. **Dashboard**
   - Welcome message with student name
   - Total XP display
   - Current streak counter
   - Class and school rank
   - Start challenge button

3. **Challenge Screen**
   - Question display
   - 4 option buttons
   - Submit functionality

4. **Result Screen**
   - Correct/incorrect indicator
   - XP earned animation
   - Updated stats
   - Rank display

5. **Leaderboard Screen**
   - Toggle between class/school
   - Top 20 students
   - Medal indicators for top 3

6. **Profile Screen**
   - Student information
   - School and class details
   - Statistics
   - Logout functionality

## 🎨 UI Guidelines

- **Primary Color**: #2563EB (Blue)
- **Background**: White (#FFFFFF)
- **Typography**: Large, readable fonts
- **Animations**: Smooth, simple transitions
- **Design**: Clean, minimal, modern

## 📊 Admin Panel Features

### School Management
- Create, edit, delete schools
- View school statistics
- Activate/deactivate schools

### Class Management
- Create classes for schools
- View class student counts
- Edit class details

### Student Management
- Create students with school/class assignment
- View student statistics (XP, streak)
- Filter by school/class
- Edit student details

### Challenge Management
- Create daily challenges
- Set active dates
- Configure options and correct answer
- Set XP rewards
- Activate/deactivate challenges

### Leaderboard View
- View top performers
- Filter by school
- Real-time rankings

### Weekly Reports
- Generate PDF reports
- Filter by school
- Student performance metrics
- Accuracy calculations

## 🗄️ Database Schema

### schools
- id, name, city, contact_person, contact_phone, is_active, timestamps

### classes
- id, school_id, name, timestamps

### students
- id, school_id, class_id, name, email_or_mobile, password, total_xp, current_streak, last_active_date, timestamps

### challenges
- id, title, description, type, option_a, option_b, option_c, option_d, correct_option, xp_reward, active_date, is_active, timestamps

### submissions
- id, student_id, challenge_id, selected_option, score, submitted_at, timestamps

## 🔧 Business Logic

### Daily Challenge Logic
1. Only 1 challenge per day (based on `active_date`)
2. Student can submit only once per challenge
3. If correct → add `xp_reward` to `total_xp`
4. If consecutive login → increase `current_streak`
5. If missed day → reset `current_streak` to 1

### Streak Calculation
- If last active date is yesterday → increment streak
- If last active date is today → keep streak
- If last active date is before yesterday → reset to 1

### Leaderboard Ranking
1. Order by `total_xp` DESC
2. Then by `current_streak` DESC
3. Then by `updated_at` DESC
4. Limit to top 20

## 🚀 Deployment

### Backend Deployment

1. Set production environment variables
2. Run migrations: `php artisan migrate --force`
3. Optimize: `php artisan config:cache && php artisan route:cache`
4. Set up web server (Nginx/Apache)
5. Configure SSL certificate

### Mobile App Deployment

1. Build for production:
```bash
expo build:android
expo build:ios
```

2. Update API base URL to production URL
3. Submit to app stores

## 📝 License

Proprietary - All rights reserved

## 👥 Support

For issues and questions, please contact the development team.

---

**Built with ❤️ for Future Skills Championship**

