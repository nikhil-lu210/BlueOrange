

```
# BO_React - BlueOrange Attendance Management System

A modern, offline-first Progressive Web Application (PWA) for employee attendance management built with React, TypeScript, and Vite. This application provides real-time attendance tracking, barcode scanning, and seamless synchronization with the BlueOrange Laravel backend.

## 🚀 Key Features

### 📱 **Modern PWA Architecture**
- **Offline-First Design** - Works without internet connection
- **Local Storage** - All data stored locally using localStorage
- **Real-time Sync** - Automatic synchronization when online
- **Responsive Design** - Works on desktop, tablet, and mobile devices

### 🎯 **Attendance Management**
- **Barcode Scanning** - Quick employee ID scanning for attendance entry
- **Dual Entry Types** - Support for Regular and Overtime attendance
- **Auto-Submission** - Automatic form submission after barcode scan
- **Real-time Updates** - Live UI updates without page refresh

### 🔐 **Security & Authorization**
- **Role-Based Access** - Laravel Spatie Permission integration
- **Authorization Modal** - Secure authentication for sensitive operations
- **Email/Password Validation** - Server-side credential verification
- **Active User Validation** - Ensures only active users can access

### 📊 **Data Management**
- **DataTables Integration** - Professional table with search, pagination, sorting
- **Bordered & Striped Tables** - Clean, modern table design
- **Local Database** - Smart localStorage implementation
- **Batch Synchronization** - Efficient bulk data sync

### 🎨 **UI/UX Features**
- **Blade Theme Integration** - Matches Laravel blade theme colors
- **Custom Button Variants** - btn-label-*, btn-outline-* classes
- **Toast Notifications** - User-friendly success/error messages
- **Loading States** - Visual feedback during operations
- **Card-Based Design** - Modern card layouts with shadows

### 🔄 **Synchronization**
- **Active Users Sync** - Download all active users from server
- **Attendance Sync** - Upload offline attendance records
- **Partial Success Handling** - Graceful error handling for failed records
- **User-Friendly Error Messages** - Clear, actionable error descriptions

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

### 3) Start Development
```bash
# Start the React development server
npm run dev

# Application will be available at http://localhost:5173
```

### 4) Build for Production
```bash
# Build the application
npm run build

# Output will be in the dist/ folder
```

## 📱 Application Usage

### **Attendance Entry**
1. **Select Type**: Choose "Regular" or "Overtime" attendance
2. **Scan Barcode**: Use barcode scanner or manually enter employee ID
3. **Auto-Submission**: Form automatically submits after scan
4. **Real-time Update**: UI updates immediately without refresh

### **Data Synchronization**
1. **Sync Users**: Download active users from server (requires authorization)
2. **Sync Attendance**: Upload offline records to server (requires authorization)
3. **Clear Data**: Remove all local records (requires authorization)

### **Authorization Process**
- Sensitive operations require email/password authentication
- Credentials validated against Laravel backend
- User must have "Attendance Create" permission
- User account must be active

## 🔧 Development Features

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
│   │   ├── BarcodeScanner.tsx # Attendance entry form
│   │   ├── AttendanceRecords.tsx # DataTables attendance list
│   │   ├── DashboardStats.tsx # Statistics cards
│   │   ├── AuthorizationModal.tsx # Authentication modal
│   │   └── Toast/           # Toast notification system
│   ├── services/            # Business logic services
│   │   ├── smartDb.ts       # Local storage database
│   │   └── workflowService.ts # Attendance workflow logic
│   ├── stores/              # Zustand state management
│   │   ├── attendance.ts    # Attendance store
│   │   └── users.ts         # Users store
│   ├── hooks/               # Custom React hooks
│   │   ├── useAttendanceWorkflow.ts # Attendance operations
│   │   └── useAuthorization.ts # Authorization logic
│   ├── utils/               # Utility functions
│   │   ├── api.ts           # API communication
│   │   └── constants.ts     # Application constants
│   ├── assets/css/          # Styling files
│   │   ├── app.css          # Base styles
│   │   └── custom.css       # Custom theme styles
│   └── types/               # TypeScript type definitions
├── public/                  # Static assets
├── dist/                    # Build output
└── package.json             # Dependencies and scripts
```

## 🔌 API Integration

The application integrates with the BlueOrange Laravel backend through RESTful APIs:

### **Available Endpoints:**
- `GET /offline-attendance/users` - Get all active users
- `GET /offline-attendance/user/{userid}` - Get specific user
- `GET /offline-attendance/user/{userid}/status` - Check user attendance status
- `POST /offline-attendance/sync` - Sync offline attendance records
- `POST /offline-attendance/authorize` - User authorization

### **Authentication:**
- Email/password validation against Laravel backend
- Role-based permissions using Spatie/Permission
- Active user status verification

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

## Troubleshooting
- Port already in use (5173): Stop other dev servers or set a different port in vite.config.ts (server.port) and ensure the Electron dev script uses the same URL.
- Electron doesn’t start:
  - Ensure the Electron dev script exists in package.json.
  - Confirm the main process entry (e.g., electron/main.ts or main.js) matches the "main" field or the script logic.
  - Delete node_modules and reinstall: rm -rf node_modules package-lock.json && npm install
- Permission issues on macOS:
  - If Gatekeeper blocks the packaged app, allow it via System Settings > Privacy & Security, or run xattr -dr com.apple.quarantine <app-path> on development builds.
- Node version mismatch:
  - Use nvm use to match the project’s Node version if .nvmrc exists.

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

## FAQ
- Do I need a SupportingApplications folder?
  - No. This project does not use it.
- Do I need to install React or Electron globally?
  - No. npm install in the project root installs everything locally.
- What package manager should I use?
  - npm is supported. If you prefer yarn or pnpm, ensure the lockfile is consistent for your team.

## License
Add your project’s license here (MIT, Apache-2.0, etc.).

## Contributing
- Create a new branch
- Make your changes
- Open a pull request with a clear description
```

