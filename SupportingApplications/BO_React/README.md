# BO_React - BlueOrange Offline Attendance Management System

A modern, offline-first Progressive Web Application (PWA) for employee attendance management built with React 19, TypeScript, and Vite. This application provides real-time attendance tracking, barcode scanning, and seamless synchronization with the BlueOrange Laravel backend. The app works as both a web application and a desktop application using Electron.

## 🚀 Key Features

### 📱 **Modern PWA Architecture**
- **Offline-First Design** - Works without internet connection using localStorage
- **Electron Desktop App** - Cross-platform desktop application support
- **Real-time Sync** - Automatic synchronization when online
- **Responsive Design** - Works on desktop, tablet, and mobile devices
- **Progressive Web App** - Installable on mobile devices

### 🎯 **Attendance Management**
- **Barcode Scanning** - Quick employee ID scanning for attendance entry
- **Dual Entry Types** - Support for Regular and Overtime attendance
- **Auto-Submission** - Automatic form submission after barcode scan
- **Real-time Updates** - Live UI updates without page refresh
- **Smart Type Detection** - Automatically suggests Regular/Overtime based on day of week

### 🔐 **Security & Authorization**
- **Role-Based Access** - Laravel Spatie Permission integration
- **Authorization Modal** - Secure authentication for sensitive operations
- **Email/Password Validation** - Server-side credential verification
- **Active User Validation** - Ensures only active users can access
- **Permission-Based Operations** - Requires "Attendance Create" permission

### 📊 **Data Management**
- **DataTables Integration** - Professional table with search, pagination, sorting
- **Bordered & Striped Tables** - Clean, modern table design
- **Local Database** - Smart localStorage implementation with indexed data
- **Batch Synchronization** - Efficient bulk data sync with partial success handling
- **Data Persistence** - All data stored locally and survives app restarts

### 🎨 **UI/UX Features**
- **Blade Theme Integration** - Matches Laravel blade theme colors exactly
- **Custom Button Variants** - btn-label-*, btn-outline-* classes
- **Toast Notifications** - User-friendly success/error messages
- **Loading States** - Visual feedback during operations
- **Card-Based Design** - Modern card layouts with shadows and gradients
- **Status Indicators** - Real-time connection and sync status

### 🔄 **Synchronization**
- **Active Users Sync** - Download all active users from server
- **Attendance Sync** - Upload offline attendance records
- **Partial Success Handling** - Graceful error handling for failed records
- **User-Friendly Error Messages** - Clear, actionable error descriptions
- **Conflict Resolution** - Handles duplicate entries and business rule violations

## 🛠️ Technology Stack

- **Frontend**: React 19 + TypeScript
- **Build Tool**: Vite
- **State Management**: Zustand
- **Styling**: Bootstrap 5 + Custom CSS
- **Data Tables**: DataTables.net
- **Icons**: Bootstrap Icons
- **Backend Integration**: Laravel API
- **Storage**: localStorage (offline-first)

## 📋 Prerequisites

## Prerequisites
- Node.js: 18.x or newer (LTS recommended)
- npm: 9.x or newer (comes with Node)
- Git: to clone the repository
- OS: macOS, Windows, or Linux

Optional (recommended)
- nvm (Node Version Manager) to match the project’s Node version if .nvmrc present.

## 🚀 Quick Start

### 1) Clone and Setup
```bash
git clone <your-repo-url>
cd SupportingApplications/BO_React
npm install
```

### 2) Environment Configuration
Create a `.env` file in the root directory:
```env
VITE_API_BASE_URL=http://blueorange.test/api
VITE_API_TIMEOUT=30000
```

### 3) Available Commands

```bash
# Development Commands
npm run dev              # Start Vite dev server + Electron app concurrently
npm run build            # Build React app for production
npm run preview          # Preview the production build

# Electron Commands  
npm run electron:dev     # Start Electron in development mode
npm start                # Start Electron app (production)
npm run dist             # Build React app + package Electron app

# The dev command runs both Vite and Electron concurrently
# Application will be available at http://localhost:5173
```

### 4) Development Workflow

**For Web Development Only:**
```bash
npm run dev
# This starts both Vite (React) and Electron concurrently
# Vite serves at http://localhost:5173
# Electron opens the app automatically
```

**For Production Build:**
```bash
npm run build            # Build React app
npm run dist             # Build + package Electron app
```

## 📱 Application Usage

### **Initial Setup**
1. **First Launch**: Application starts with empty local database
2. **Sync Users**: Click the download button to sync active users from server
3. **Authorization Required**: Enter email/password for user sync operation
4. **Ready to Use**: Once users are synced, you can start recording attendance

### **Attendance Entry**
1. **Select Type**: Choose "Regular" or "Overtime" attendance type
2. **Scan Barcode**: Use barcode scanner or manually enter employee ID
3. **Auto-Submission**: Form automatically submits after barcode scan
4. **Real-time Update**: UI updates immediately without refresh
5. **Smart Detection**: App suggests Regular for weekdays, Overtime for weekends

### **Data Synchronization**
1. **Sync Users**: Download active users from server (requires authorization)
2. **Sync Attendance**: Upload offline records to server (requires authorization)
3. **Clear Data**: Remove all local records (requires authorization)
4. **Partial Success**: Handles cases where some records sync successfully

### **Authorization Process**
- Sensitive operations require email/password authentication
- Credentials validated against Laravel backend
- User must have "Attendance Create" permission
- User account must be active
- Authorization modal appears for:
  - Syncing users from server
  - Syncing attendance records to server
  - Clearing all local data
  - Deleting individual attendance records

### **Offline Operation**
- Works completely offline once users are synced
- All attendance records stored locally
- Automatic sync when connection is restored
- Visual indicators show online/offline status

## 🔧 Development Features

### **Debug Mode**
- Set `VITE_DEBUG_MODE=true` in environment variables
- Enables manual input in barcode scanner
- Allows right-click context menu
- Shows debug indicators in UI

### **Error Handling**
- Comprehensive error parsing for sync operations
- User-friendly error messages for business rule violations
- Handles weekend/holiday attendance restrictions
- Duplicate entry detection and reporting

### **Performance Optimizations**
- Efficient localStorage operations
- Minimal re-renders with Zustand state management
- Lazy loading of DataTables
- Optimized API calls with timeout handling

## Step-by-Step: Local Installation and Running

### 1) Clone the repository
- git clone <your-repo-url>
- cd <your-project-folder>

### 2) Ensure correct Node and npm versions
- node -v
- npm -v

If needed, install Node from nodejs.org or use nvm:
- nvm install
- nvm use

### 3) Install all dependencies (React, Electron, and others)
- npm install

This will install:
- React and ReactDOM (frontend)
- Vite and related tooling (dev server and build)
- Electron and related tooling (desktop runtime), as specified in package.json

You do not need to install React or Electron globally.

### 4) Run in development

Option A: Start the web frontend only (useful for UI work)
- npm run dev
This runs Vite and serves the React app at http://localhost:5173 (default).

Option B: Start the Electron desktop app
- Look in package.json for one of these scripts (names may vary):
  - dev:electron
  - electron:dev
  - desktop:dev

Run the available one, for example:
- npm run dev:electron

This typically:
- Starts the Vite dev server for React
- Waits for the dev server to be ready
- Launches Electron pointing to the dev URL (Fast Refresh applies to UI)

### 5) Build for production

Frontend (web) build:
- npm run build
- Output usually goes to dist/ (or as configured in vite.config.ts)

Electron desktop build/package:
- Look in package.json for one of these scripts:
  - build:electron
  - electron:build
  - package

Run the available one, for example:
- npm run build:electron

This typically bundles the app (e.g., with electron-builder or similar) and outputs installers or packaged apps in a dist or dist_electron folder.

## 📁 Project Structure

```
BO_React/
├── src/
│   ├── components/           # React components
│   │   ├── NavBar.tsx       # Navigation bar with sync controls
│   │   ├── BarcodeScanner.tsx # Attendance entry form with barcode scanning
│   │   ├── AttendanceRecords.tsx # DataTables attendance list
│   │   ├── DashboardStats.tsx # Statistics cards
│   │   ├── AuthorizationModal.tsx # Authentication modal
│   │   ├── StatusBar.tsx    # Connection status indicator
│   │   └── Toast/           # Toast notification system
│   │       ├── index.ts     # Toast exports
│   │       ├── ToastContainer.tsx # Toast container component
│   │       └── types.ts     # Toast type definitions
│   ├── services/            # Business logic services
│   │   ├── attendanceService.ts # Attendance CRUD operations
│   │   ├── userService.ts   # User management operations
│   │   └── workflowService.ts # Attendance workflow logic
│   ├── stores/              # Zustand state management
│   │   ├── attendance.ts    # Attendance store with CRUD operations
│   │   └── users.ts         # Users store with sync operations
│   ├── hooks/               # Custom React hooks
│   │   ├── useAttendanceWorkflow.ts # Attendance operations hook
│   │   ├── useAuthorization.ts # Authorization logic hook
│   │   └── useStoreRefresh.ts # Store refresh utilities
│   ├── utils/               # Utility functions
│   │   ├── api.ts           # API communication with Laravel backend
│   │   └── constants.ts     # Application constants and environment variables
│   ├── assets/css/          # Styling files
│   │   ├── app.css          # Base styles
│   │   └── custom.css       # Custom theme styles matching Laravel blade theme
│   ├── types/               # TypeScript type definitions
│   │   └── index.ts         # Shared type definitions
│   ├── App.tsx              # Main application component
│   ├── main.tsx             # Application entry point
│   └── vite-env.d.ts        # Vite environment type definitions
├── electron/                # Electron desktop app files
│   ├── main.js              # Electron main process
│   └── preload.js           # Electron preload script
├── public/                  # Static assets
│   └── assets/
│       └── favicon.ico      # Application icon
├── dist/                    # Build output
├── package.json             # Dependencies and scripts
├── vite.config.js           # Vite configuration
├── tsconfig.json            # TypeScript configuration
├── tsconfig.app.json        # App-specific TypeScript config
├── tsconfig.node.json       # Node-specific TypeScript config
└── eslint.config.js         # ESLint configuration
```

## 🔌 API Integration

The application integrates with the BlueOrange Laravel backend through RESTful APIs defined in `routes/api/attendance/attendance.php`:

### **Available Endpoints:**

#### **User Management**
- `GET /api/offline-attendance/users` - Get all active users for offline sync
- `GET /api/offline-attendance/user/{userid}` - Get specific user by userid
- `GET /api/offline-attendance/user/{userid}/status` - Check user attendance status on server

#### **Attendance Operations**
- `POST /api/offline-attendance/sync` - Sync offline attendance records to server
- `POST /api/offline-attendance/authorize` - User authorization for sensitive operations

### **API Request/Response Format:**

#### **Get All Users**
```http
GET /api/offline-attendance/users
Response: {
  "success": true,
  "data": [
    {
      "id": 1,
      "userid": "20010101",
      "name": "John Doe",
      "alias_name": "John",
      "email": "john@example.com"
    }
  ]
}
```

#### **Get User by UserID**
```http
GET /api/offline-attendance/user/20010101
Response: {
  "success": true,
  "data": {
    "id": 1,
    "userid": "20010101",
    "name": "John Doe",
    "alias_name": "John",
    "email": "john@example.com"
  }
}
```

#### **Check User Status**
```http
GET /api/offline-attendance/user/20010101/status
Response: {
  "success": true,
  "data": {
    "user_id": 1,
    "has_attendance_today": false,
    "last_attendance": null
  }
}
```

#### **Sync Attendance Records**
```http
POST /api/offline-attendance/sync
Content-Type: application/json
{
  "attendances": [
    {
      "user_id": 1,
      "entry_date_time": "2024-01-15T08:30:00.000Z",
      "type": "Regular"
    }
  ]
}
Response: {
  "success": true,
  "data": {
    "synced_count": 1,
    "total_count": 1,
    "synced_record_ids": [0],
    "errors": []
  }
}
```

#### **User Authorization**
```http
POST /api/offline-attendance/authorize
Content-Type: application/json
{
  "email": "admin@example.com",
  "password": "password"
}
Response: {
  "success": true,
  "data": {
    "user_id": 1,
    "name": "Admin User",
    "email": "admin@example.com",
    "permissions": ["attendance.create", "attendance.sync"]
  }
}
```

### **Authentication & Security:**
- Email/password validation against Laravel backend
- Role-based permissions using Spatie/Permission package
- Active user status verification
- Requires "Attendance Create" permission for sensitive operations
- JWT token-based authentication (handled by Laravel backend)

## 🎨 UI/UX Features

### **Theme Integration:**
- Matches Laravel blade theme colors exactly
- Custom CSS variables for consistent branding
- Bootstrap 5 integration with custom overrides

### **Responsive Design:**
- Mobile-first approach
- Adaptive layouts for different screen sizes
- Touch-friendly interface elements

### **User Experience:**
- Real-time feedback and notifications
- Loading states for all async operations
- Error handling with user-friendly messages
- Keyboard shortcuts and accessibility features

## 🐛 Troubleshooting

### **Common Issues**

#### **Port Already in Use (5173)**
- Stop other dev servers or set a different port in `vite.config.ts`
- Ensure the Electron dev script uses the same URL

#### **Electron Doesn't Start**
- Ensure the Electron dev script exists in `package.json`
- Confirm the main process entry (`electron/main.js`) matches the "main" field
- Delete node_modules and reinstall: `rm -rf node_modules package-lock.json && npm install`

#### **Permission Issues on macOS**
- If Gatekeeper blocks the packaged app, allow it via System Settings > Privacy & Security
- Or run: `xattr -dr com.apple.quarantine <app-path>` on development builds

#### **Node Version Mismatch**
- Use `nvm use` to match the project's Node version if `.nvmrc` exists

#### **API Connection Issues**
- Verify `VITE_API_BASE_URL` in `.env` file points to correct Laravel backend
- Check if Laravel backend is running and accessible
- Ensure CORS is properly configured in Laravel backend

#### **Sync Failures**
- Check user permissions in Laravel backend
- Verify user has "Attendance Create" permission
- Check network connectivity
- Review browser console for detailed error messages

#### **Data Not Persisting**
- Check browser localStorage quota
- Clear browser cache and reload
- Verify localStorage is not disabled in browser settings

## Optional: ESLint and Type-Aware Rules
If you are developing a production application, consider enabling type-aware lint rules:

```js
// eslint.config.js
export default defineConfig([
  globalIgnores(['dist']),
  {
    files: ['**/*.{ts,tsx}'],
    extends: [
      // Other configs...

      // Remove tseslint.configs.recommended and replace with this
      tseslint.configs.recommendedTypeChecked,
      // Alternatively, use this for stricter rules
      tseslint.configs.strictTypeChecked,
      // Optionally, add this for stylistic rules
      tseslint.configs.stylisticTypeChecked,

      // Other configs...
    ],
    languageOptions: {
      parserOptions: {
        project: ['./tsconfig.node.json', './tsconfig.app.json'],
        tsconfigRootDir: import.meta.dirname,
      },
      // other options...
    },
  },
])
```

You can also install eslint-plugin-react-x and eslint-plugin-react-dom for React-specific lint rules:

```js
// eslint.config.js
import reactX from 'eslint-plugin-react-x'
import reactDom from 'eslint-plugin-react-dom'

export default defineConfig([
  globalIgnores(['dist']),
  {
    files: ['**/*.{ts,tsx}'],
    extends: [
      // Other configs...
      // Enable lint rules for React
      reactX.configs['recommended-typescript'],
      // Enable lint rules for React DOM
      reactDom.configs.recommended,
    ],
    languageOptions: {
      parserOptions: {
        project: ['./tsconfig.node.json', './tsconfig.app.json'],
        tsconfigRootDir: import.meta.dirname,
      },
      // other options...
    },
  },
])
```

## ❓ FAQ

### **General Questions**
- **Do I need a SupportingApplications folder?**
  - No. This project does not use it.
- **Do I need to install React or Electron globally?**
  - No. `npm install` in the project root installs everything locally.
- **What package manager should I use?**
  - npm is supported. If you prefer yarn or pnpm, ensure the lockfile is consistent for your team.

### **Application Questions**
- **How does offline mode work?**
  - The app stores all data locally using localStorage. Once users are synced, you can work completely offline.
- **What happens if sync fails?**
  - The app handles partial sync failures gracefully, showing which records succeeded and which failed.
- **Can I use this without a barcode scanner?**
  - Yes, in debug mode you can manually enter employee IDs. Set `VITE_DEBUG_MODE=true` in your environment.
- **How do I clear all data?**
  - Use the trash button in the navigation bar. This requires authorization for security.

### **Technical Questions**
- **Why does the app require authorization for some operations?**
  - Sensitive operations like syncing data and clearing records require authorization to prevent accidental data loss.
- **How does the barcode scanning work?**
  - The app detects rapid keystrokes (typical of barcode scanners) and automatically submits the form.
- **What happens to data if I close the app?**
  - All data is stored in localStorage and persists between app sessions.

## 📄 License
Add your project's license here (MIT, Apache-2.0, etc.).

## 🤝 Contributing
- Create a new branch
- Make your changes
- Open a pull request with a clear description
- Ensure all tests pass
- Follow the existing code style and patterns
```

