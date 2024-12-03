# BlueOrange Web Application

## Features
-  **Role-Based Access**: Developer & Super Admin (Pre-Defined Roles).
-  **Real-Time Notifications**: Keeps users updated.
-  **File Management**: Simplified uploads and downloads.
-  **Chat System**: Real-time messaging between users.
---

## Installation Guide
Follow these steps to set up the project on your local environment:

### Prerequisites
Ensure you have the following installed on your system:
- PHP (8.2 or higher)
- Composer
- Node.js & npm
- MySQL or a compatible database system

### Steps
**Step 1: Clone the repository and navigate to the project directory**
```bash
git clone https://github.com/nikhil-lu210/BlueOrange.git
cd BlueOrange
```

**Step 2: Install dependencies**
```bash
composer install
npm install
```

**Step 3: Set up environment variables**
```bash
cp .env.example .env
php artisan key:generate
```

**Step 4: Update the `.env` file with your database credentials**

**Step 5: Run database migrations and seeders**
```bash
php artisan migrate  --seed
```

**Step 6: Build frontend assets**
```bash
npm run dev
```

**Step 7: Start the local development server**
```bash
php artisan serve
```

- Access the application at http://127.0.0.1:8000
---

### Default Credentials
**Developer**
```bash
UserID: 20230201
Password: 12345678
```

**Super Admin**
```bash
UserID: 20202020
Password:  12345678
```
---

### Troubleshooting
**Verify `.env` configurations**

**Clear Optimization**
```bash
php artisan optimize:clear
```

**For more help**
Visit the official Laravel documentation: [https://laravel.com/docs](https://laravel.com/docs)