# BlueOrange - Comprehensive Employee Management System

A modern, feature-rich Laravel-based employee management system designed for efficient workforce management, attendance tracking, and organizational operations.

## 🚀 Key Features

### 👥 User Management & Authentication
- **Role-Based Access Control**: Developer & Super Admin roles with granular permissions
- **Secure Authentication**: Multi-factor authentication with device and IP restrictions
- **User Profiles**: Comprehensive employee profiles with personal and professional information
- **Permission System**: 42 different permission modules with Everything/Create/Read/Update/Delete access levels

### ⏰ Attendance & Time Management
- **Real-time Clock In/Out**: Manual and QR code-based attendance tracking
- **Shift Management**: Flexible shift scheduling and management
- **Daily Break Tracking**: Monitor and manage employee break times
- **Attendance Reports**: Comprehensive attendance analytics and reporting
- **Weekend Configuration**: Customizable weekend settings

### 🏖️ Leave Management System
- **Leave Types**: Earned, Sick, and Casual leave management
- **Leave Applications**: Digital leave request and approval workflow
- **Leave Balance**: Real-time leave balance tracking
- **Holiday Management**: System-wide holiday calendar management
- **Leave History**: Complete leave history with approval status

### 💰 Salary & Financial Management
- **Salary Structure**: Comprehensive salary management with benefits
- **Monthly Salary**: Automated monthly salary calculations
- **Income & Expense Tracking**: Financial transaction management
- **Salary Reports**: Detailed salary reports and analytics

### 📋 Task & Project Management
- **Task Assignment**: Create and assign tasks to team members
- **Task Tracking**: Monitor task progress and completion
- **Daily Work Updates**: Daily work reporting with manager ratings
- **Project Management**: Organize tasks under projects
- **Task Calendar**: Visual task deadline management

### 💬 Communication & Collaboration
- **Real-time Chat System**: Group and individual messaging
- **Announcements**: System-wide announcements and notifications
- **Comment System**: Threaded comments on various modules
- **Notification System**: Real-time notifications for all activities

### 🎯 Additional Modules
- **Penalty Management**: Employee penalty tracking with email notifications
- **IT Ticket System**: Internal IT support ticket management
- **Vault System**: Secure credential storage and sharing
- **Certificate Generation**: Professional certificate creation (Appointment, Experience, NOC, etc.)
- **Dining Room Booking**: Cafeteria/dining room reservation system
- **Quiz System**: Public quiz system for candidate evaluation
- **File Management**: Secure file upload, storage, and sharing
- **Shortcut Management**: Quick access to frequently used features

### 🔧 System Features
- **Dashboard Analytics**: Comprehensive dashboard with key metrics
- **Calendar Integration**: Task deadlines, holidays, leaves, and weekends
- **Advanced Filtering**: 20+ filter criteria for user management
- **Export Functionality**: Excel export for various reports
- **Responsive Design**: Mobile-friendly interface
- **Multi-language Support**: Localization support
- **Backup System**: Automated backup functionality
- **Audit Logs**: Complete system activity logging

## 📋 System Requirements

### Server Requirements
- **PHP**: 8.2 or higher
- **Composer**: Latest version
- **Node.js**: 16.x or higher
- **NPM**: Latest version
- **Database**: MySQL 5.7+ or MariaDB 10.3+
- **Web Server**: Apache/Nginx
- **Git**: For version control

### PHP Extensions Required
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- BCMath PHP Extension
- Fileinfo PHP Extension
- GD PHP Extension

### Optional Requirements
- **Redis**: For caching and session management
- **Supervisor**: For queue management
- **SSL Certificate**: For production deployment

## 🛠️ Installation Guide

### Prerequisites
Before installing BlueOrange, ensure you have the following installed on your system:
- PHP 8.2 or higher with required extensions
- Composer (PHP dependency manager)
- Node.js 16.x or higher with NPM
- MySQL 5.7+ or MariaDB 10.3+
- Git for version control

### Step 1: Clone the Repository
```bash
git clone https://github.com/nikhil-lu210/BlueOrange.git
cd BlueOrange
```

### Step 2: Install PHP Dependencies
```bash
composer install
```

### Step 3: Install Frontend Dependencies
```bash
npm install
```

### Step 4: Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 5: Configure Environment Variables
Edit the `.env` file and update the following configurations:

#### Database Configuration
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blueorange
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password
```

#### Mail Configuration
```env
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=your_smtp_port
MAIL_USERNAME=your_smtp_username
MAIL_PASSWORD=your_smtp_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

#### Application Configuration
```env
APP_NAME="BlueOrange"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
```

#### Optional: Queue Configuration (for background jobs)
```env
QUEUE_CONNECTION=database
```

#### Optional: Cache Configuration
```env
CACHE_DRIVER=file
SESSION_DRIVER=file
```

### Step 6: Database Setup
```bash
# Create a new database (MySQL/MariaDB)
mysql -u root -p
CREATE DATABASE blueorange CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;

# Run migrations and seeders
php artisan migrate --seed
```

### Step 7: Set Directory Permissions
```bash
# For Linux/macOS
chmod -R 775 storage bootstrap/cache

# For Windows (run as administrator)
icacls storage /grant Users:F /T
icacls bootstrap/cache /grant Users:F /T
```

### Step 8: Build Frontend Assets
```bash
# For development (with hot reload)
npm run dev

# For production (optimized build)
npm run build
```

### Step 9: Start the Development Server
```bash
php artisan serve
```

The application will be available at: **http://127.0.0.1:8000**

### Step 10: Queue Worker (Optional)
For background job processing, run the queue worker:
```bash
php artisan queue:work
```

## 🔐 Default Login Credentials

After running the seeders, you can log in with the following pre-configured accounts:

### 👨‍💻 Developer Account (Full Access)
- **UserID**: `00000001`
- **Email**: `developer@mail.com`
- **Password**: `12345678`
- **Role**: Developer (All permissions)
- **Shift**: 2:00 PM - 10:00 PM
- **Leave Balance**: 120 hours each (Earned, Casual, Sick)

### 👑 Super Admin Account (Administrative Access)
- **UserID**: `00000002`
- **Email**: `superadmin@mail.com`
- **Password**: `12345678`
- **Role**: Super Admin (Limited permissions)
- **Shift**: 2:00 PM - 10:00 PM
- **Leave Balance**: 120 hours each (Earned, Casual, Sick)

### 🚀 First-Time Login Instructions

1. **Access the Application**: Navigate to `http://127.0.0.1:8000` in your web browser
2. **Login**: Use either the Developer or Super Admin credentials above
3. **Complete Profile**: After first login, you may be prompted to complete your profile information
4. **Set Blood Group**: A modal will appear asking you to set your blood group (required for all users)
5. **Explore Dashboard**: You'll be redirected to the dashboard with access to all available modules
6. **Change Password**: It's recommended to change the default password after first login

### 🔑 Permission Levels

#### Developer Role Permissions:
- **Full System Access**: All 42 permission modules with Everything access
- **User Management**: Create, update, delete users and manage roles
- **System Settings**: Configure weekends, holidays, and system preferences
- **Advanced Features**: Access to logs, vault, and administrative functions

#### Super Admin Role Permissions:
- **Limited Access**: Read-only access to most modules
- **Basic Operations**: Can create leave requests, daily work updates, and view reports
- **No Administrative Access**: Cannot modify system settings or manage users

## 📊 Database Structure

The application uses a comprehensive database schema with the following main components:

### Core Tables
- **users**: User authentication and basic information
- **employees**: Extended employee information and profiles
- **roles & permissions**: Role-based access control system
- **permission_modules**: Organized permission groupings

### Attendance & Time Management
- **attendances**: Daily attendance records with clock in/out times
- **employee_shifts**: Employee shift schedules and timing
- **daily_breaks**: Break time tracking and management
- **weekends**: Configurable weekend settings

### Leave Management
- **leave_alloweds**: Annual leave allocations per employee
- **leave_histories**: Leave applications and approval workflow
- **leave_availables**: Real-time leave balance calculations
- **holidays**: System-wide holiday calendar

### Task & Project Management
- **tasks**: Task assignments and tracking
- **daily_work_updates**: Daily work reports with ratings
- **comments**: Threaded comment system

### Communication & Collaboration
- **chatting_groups**: Group chat functionality
- **announcements**: System-wide announcements
- **notifications**: Real-time notification system

### Financial Management
- **salaries**: Employee salary structures
- **monthly_salaries**: Monthly salary calculations
- **incomes & expenses**: Financial transaction tracking

### Additional Modules
- **penalties**: Employee penalty tracking
- **it_tickets**: IT support ticket system
- **vaults**: Secure credential storage
- **certificates**: Professional certificate generation
- **bookings**: Dining room reservation system
- **quiz_questions & quiz_tests**: Public quiz system
- **file_media**: File upload and management

## 🌱 Seeding Process

The database seeder automatically creates essential data in the following order:

### 1. Weekend Configuration
- **Default Weekends**: Saturday and Sunday
- **Configurable**: Can be modified through admin panel
- **Calendar Integration**: Used in attendance and leave calculations

### 2. System Settings
- **Device Restrictions**: Mobile and computer access controls
- **IP Restrictions**: Allowed IP ranges for enhanced security
- **Unrestricted Users**: Users exempt from device/IP restrictions

### 3. Permission System
- **42 Permission Modules**: Comprehensive permission structure
- **5 Permission Types**: Everything, Create, Read, Update, Delete
- **210 Total Permissions**: Granular access control

### 4. Role Creation
- **Developer Role**: Full system access with all permissions
- **Super Admin Role**: Limited access with basic operational permissions
- **Extensible**: Additional roles can be created through admin panel

### 5. Default Users
- **Developer User**: Complete profile with employee record
- **Super Admin User**: Complete profile with employee record
- **Team Leader Assignment**: Both users assigned as team leaders
- **Shift Assignment**: Default 8-hour shifts (2 PM - 10 PM)
- **Leave Allocation**: 120 hours each for all leave types

### 6. Employee Records
- **Complete Profiles**: Personal and professional information
- **Contact Information**: Personal and official contact details
- **Academic Information**: Education and certification details
- **Joining Date**: Random dates within the last 10 years

## 🔧 Configuration & Customization

### Environment Configuration
The application can be customized through various environment variables:

#### Security Settings
```env
# Device and IP restrictions
MOBILE_RESTRICTION=false
COMPUTER_RESTRICTION=false
ALLOWED_IP_RANGES=[]
UNRESTRICTED_USERS=[]
```

#### File Upload Settings
```env
# Maximum file upload size
UPLOAD_MAX_FILESIZE=10M
POST_MAX_SIZE=10M
```

#### Queue Configuration
```env
# For background job processing
QUEUE_CONNECTION=database
QUEUE_FAILED_DRIVER=database
```

### Weekend Configuration
- Navigate to **Settings > System > Weekend** to configure weekend days
- Default: Saturday and Sunday
- Affects attendance calculations and calendar displays

### Holiday Management
- Add holidays through **Settings > System > Holiday**
- Holidays appear in calendar and affect leave calculations
- Support for recurring annual holidays

### Permission Customization
- All permissions are modular and can be assigned to roles
- Create custom roles with specific permission combinations
- 42 different modules with granular access control

## 🚨 Troubleshooting

### Common Installation Issues

#### 1. Permission Errors
```bash
# Linux/macOS
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache

# Windows (run as administrator)
icacls storage /grant Users:F /T
icacls bootstrap/cache /grant Users:F /T
```

#### 2. Database Connection Issues
```bash
# Check database credentials in .env
# Ensure database exists and user has proper permissions
mysql -u your_username -p
SHOW DATABASES;
```

#### 3. Composer Issues
```bash
# Clear composer cache
composer clear-cache

# Update dependencies
composer update

# Reinstall dependencies
rm -rf vendor
composer install
```

#### 4. NPM/Node Issues
```bash
# Clear npm cache
npm cache clean --force

# Remove node_modules and reinstall
rm -rf node_modules package-lock.json
npm install

# Build assets
npm run dev
```

#### 5. Laravel Cache Issues
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan queue:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 6. Database Migration Issues
```bash
# Reset database (WARNING: This will delete all data)
php artisan migrate:fresh --seed

# Check migration status
php artisan migrate:status

# Rollback specific migration
php artisan migrate:rollback --step=1
```

#### 7. File Upload Issues
```bash
# Check PHP configuration
php -i | grep upload_max_filesize
php -i | grep post_max_size

# Update php.ini if needed
upload_max_filesize = 10M
post_max_size = 10M
```

### Performance Optimization

#### 1. Production Deployment
```bash
# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Enable OPcache in php.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=4000
```

#### 2. Database Optimization
```bash
# Add database indexes for better performance
# Optimize MySQL configuration
# Use connection pooling for high traffic
```

#### 3. Queue Workers
```bash
# Start queue worker for background jobs
php artisan queue:work

# Use Supervisor for production
# Configure queue workers in supervisor.conf
```

### Debugging & Logging

#### Enable Debug Mode
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

#### Check Logs
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Web server logs
tail -f /var/log/apache2/error.log  # Apache
tail -f /var/log/nginx/error.log    # Nginx
```

#### Database Query Debugging
```php
// Add to AppServiceProvider boot method
DB::listen(function ($query) {
    Log::info($query->sql, $query->bindings);
});
```

## 📚 Additional Resources

### Documentation
- **Laravel Documentation**: [https://laravel.com/docs](https://laravel.com/docs)
- **Spatie Permission**: [https://spatie.be/docs/laravel-permission](https://spatie.be/docs/laravel-permission)
- **Laravel Livewire**: [https://laravel-livewire.com](https://laravel-livewire.com)
- **Bootstrap 5**: [https://getbootstrap.com/docs/5.0](https://getbootstrap.com/docs/5.0)

### Development Tools
- **Laravel Debugbar**: Enabled in development for debugging
- **Laravel IDE Helper**: Provides IDE autocompletion
- **Laravel Pint**: Code style fixer
- **PHPUnit**: Testing framework

### Browser Compatibility
- **Chrome**: 90+
- **Firefox**: 88+
- **Safari**: 14+
- **Edge**: 90+

## 🤝 Contributing

### Development Workflow
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests: `php artisan test`
5. Submit a pull request

### Code Standards
- Follow PSR-12 coding standards
- Use Laravel best practices
- Write comprehensive tests
- Document new features

### Reporting Issues
- Use GitHub Issues for bug reports
- Provide detailed reproduction steps
- Include system information and logs

## 📄 License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## 🆘 Support

### Getting Help
- **Documentation**: Check this README and inline documentation
- **GitHub Issues**: Report bugs and request features
- **Email Support**: Contact the development team

### Professional Support
For enterprise support, custom development, or consulting services, please contact the development team.

---

**BlueOrange Employee Management System** - Streamlining workforce management with modern technology.
