# BlueOrange Web Application

## Features
- **Role-Based Access**: Developer & Super Admin (Pre-Defined Roles)
- **Real-Time Notifications**: Keeps users updated
- **File Management**: Simplified uploads and downloads
- **Chat System**: Real-time messaging between users
- **Attendance Management**: Track employee attendance and shifts
- **Leave Management**: Manage employee leaves and holidays
- **Salary Management**: Comprehensive salary and benefits tracking
- **Task Management**: Assign and track tasks
- **Expense Management**: Track income and expenses
- **Document Management**: Secure file storage and sharing

## System Requirements
- PHP 8.2 or higher
- Composer
- Node.js 16.x or higher & npm
- MySQL 5.7+ or MariaDB 10.3+
- Web Server (Apache/Nginx)
- Git

## Installation Guide

### 1. Clone the Repository
```bash
git clone https://github.com/nikhil-lu210/BlueOrange.git
cd BlueOrange
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Install Frontend Dependencies
```bash
npm install
```

### 4. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configure Environment Variables
Edit the `.env` file and update the following:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blueorange
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=your_smtp_port
MAIL_USERNAME=your_smtp_username
MAIL_PASSWORD=your_smtp_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 6. Database Setup
```bash
# Create a new database
mysql -u root -p
CREATE DATABASE blueorange;
exit;

# Run migrations and seeders
php artisan migrate --seed
```

### 7. Build Frontend Assets
```bash
# For development
npm run dev

# For production
npm run build
```

### 8. Start the Development Server
```bash
php artisan serve
```

Access the application at http://127.0.0.1:8000

## Default Login Credentials

### Developer Account
- **UserID**: 00000001
- **Email**: developer@mail.com
- **Password**: 12345678

### Super Admin Account
- **UserID**: 00000002
- **Email**: superadmin@mail.com
- **Password**: 12345678

## Database Structure
The application includes the following main tables:
- Users & Employees
- Roles & Permissions
- Attendance & Shifts
- Leave Management
- Salary & Benefits
- Tasks & Projects
- Chat & Notifications
- File Management
- Income & Expenses

## Seeding Process
The database seeder creates:
1. Weekend settings
2. System settings
3. Permissions and roles
4. Default users (Developer and Super Admin)
5. Employee records
6. Shift schedules
7. Leave allowances
8. Salary structures

## Troubleshooting

### Common Issues

1. **Permission Issues**
```bash
chmod -R 775 storage bootstrap/cache
```

2. **Cache Issues**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

3. **Database Issues**
```bash
php artisan migrate:fresh --seed
```

4. **Frontend Build Issues**
```bash
rm -rf node_modules
npm install
npm run dev
```

### Additional Help
- Check Laravel logs in `storage/logs/laravel.log`
- Enable debug mode in `.env` by setting `APP_DEBUG=true`
- Visit the official Laravel documentation: [https://laravel.com/docs](https://laravel.com/docs)

## Support
For support, email support@blueorange.com or create an issue in the GitHub repository.