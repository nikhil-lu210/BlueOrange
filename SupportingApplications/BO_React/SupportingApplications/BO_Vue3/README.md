# BlueOrange Offline Attendance PWA

A Vue.js 3 Progressive Web Application for offline-first attendance management that integrates with the BlueOrange Laravel backend.

## 🚀 Features

- **Offline-First Design**: Works completely offline after initial sync
- **SQLite Database**: Uses `sql.js` for in-memory SQLite database
- **Barcode Scanning**: Scan user barcodes for clock in/out
- **Smart Attendance Logic**: Automatically detects if user is already clocked in
- **BlueOrange Integration**: Syncs users and attendances with BlueOrange backend
- **PWA Support**: Installable as a Progressive Web App
- **Auto-Import**: Configured with `unplugin-auto-import` and `unplugin-vue-components`
- **TypeScript**: Full TypeScript support with type safety

## 📋 Prerequisites

- Node.js 20.19.0 or higher
- pnpm (recommended) or npm
- BlueOrange Laravel backend running on `http://blueorange.test`

## 🛠️ Installation

1. **Clone or navigate to the project directory:**
   ```bash
   cd BO_Vue3
   ```

2. **Install dependencies:**
   ```bash
   pnpm install
   # or
   npm install
   ```

3. **Configure environment variables:**
   ```bash
   cp .env.example .env
   ```
   
   Edit `.env` file with your BlueOrange API configuration:
   ```env
   VITE_API_BASE_URL=http://blueorange.test/api
   VITE_API_TIMEOUT=10000
   ```

4. **Start development server:**
   ```bash
   pnpm dev
   # or
   npm run dev
   ```

## 🔧 Configuration

### Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `VITE_API_BASE_URL` | BlueOrange API base URL | `http://blueorange.test/api` |
| `VITE_API_TIMEOUT` | API request timeout (ms) | `10000` |
| `VITE_APP_NAME` | Application name | `BlueOrange Offline Attendance` |
| `VITE_APP_VERSION` | Application version | `1.0.0` |
| `VITE_DB_NAME` | SQLite database name | `blueorange_offline` |
| `VITE_DEBUG_SQL` | Enable SQL debugging | `false` |

### BlueOrange API Endpoints

The app expects these endpoints in your BlueOrange Laravel backend:

- `GET /api/offline-attendance/users` - Get all users
- `GET /api/offline-attendance/user/{userid}` - Get user by userid
- `POST /api/offline-attendance/sync` - Sync attendance data

## 📱 Usage

### Initial Setup

1. **Start the app** - It will automatically try to sync users from BlueOrange
2. **If offline** - Click "Sync from BlueOrange" when online to download users
3. **Ready to use** - Start scanning barcodes for attendance

### Daily Usage

1. **Scan Barcode** - Scan a user's barcode
2. **Clock In/Out** - App shows appropriate button based on user's status
3. **Offline Recording** - All data is stored locally in SQLite
4. **Sync When Ready** - Click "Sync to BlueOrange" to upload offline data

### Data Flow

```
BlueOrange DB → Initial Sync → Local SQLite → Offline Usage → Sync Back → BlueOrange DB
```

## 🏗️ Architecture

### Database Schema

The local SQLite database mirrors the BlueOrange Laravel schema:

- **users** - User information from BlueOrange
- **employees** - Employee aliases and additional info
- **employee_shifts** - User shift schedules
- **attendances** - Clock in/out records
- **daily_breaks** - Break time tracking

### Key Services

- **`dbService`** - SQLite database operations
- **`syncService`** - BlueOrange API integration
- **`API`** - HTTP client with timeout handling

### Auto-Import Configuration

The project uses `unplugin-auto-import` for:
- Vue 3 Composition API functions (`ref`, `computed`, `onMounted`, etc.)
- Vue Router functions (`useRouter`, `useRoute`)
- Pinia functions (`defineStore`, `storeToRefs`)

## 🔄 Complete Offline-First Workflow

### **Phase 1: Initial Setup (Online Required)**

1. **App Startup**: 
   - Initializes persistent SQLite database (stored in IndexedDB)
   - Checks if users exist in local database
   - If no users found, automatically triggers initial sync

2. **Initial Sync from BlueOrange**:
   - Fetches ALL users from BlueOrange API (`/api/offline-attendance/users`)
   - Downloads open attendances (where `clock_out` is null) - *Future feature*
   - Stores everything in persistent SQLite database
   - Data survives page refreshes and browser restarts

### **Phase 2: Offline Operation (No Internet Required)**

3. **Daily Usage**:
   - Scan barcode → App checks local SQLite database
   - Smart detection: Shows "Clock In" or "Clock Out" based on user's status
   - All attendance records stored locally with full validation
   - Works completely offline after initial sync

4. **Data Persistence**:
   - SQLite database stored in IndexedDB for persistence
   - Data survives page refreshes, browser restarts, and device reboots
   - No data loss even if app is closed unexpectedly

### **Phase 3: Sync Back to BlueOrange (When Online)**

5. **Manual Sync**:
   - Collects all unsynced attendance records
   - Validates data integrity and prevents duplicates
   - Sends to BlueOrange API (`/api/offline-attendance/sync`)
   - Marks records as synced on success
   - Handles conflicts and errors gracefully

### **Data Flow Diagram**

```
BlueOrange DB → Initial Sync → Persistent SQLite (IndexedDB) → Offline Usage → Sync Back → BlueOrange DB
     ↑                                                                                              ↓
     └─────────────────────── Conflict Resolution & Validation ←─────────────────────────────────┘
```

### **Key Features**

- **🔄 Persistent Storage**: Data survives page refreshes and browser restarts
- **📱 True Offline**: Works without internet after initial sync
- **🔍 Smart Detection**: Automatically detects clock in/out status
- **✅ Data Validation**: Prevents duplicates and validates all data
- **🔄 Conflict Resolution**: Handles sync conflicts gracefully
- **📊 Real-time Stats**: Shows users, attendances, and sync status

## 🚀 Building for Production

```bash
# Build the application
pnpm run build

# Preview production build
pnpm run preview
```

The build output will be in the `dist/` directory, ready for deployment.

## 📦 PWA Features

- **Installable** - Can be installed on mobile devices
- **Offline Support** - Works without internet connection
- **Service Worker** - Caches resources for offline use
- **App Manifest** - Defines app appearance and behavior

## 🐛 Troubleshooting

### Common Issues

1. **Import Errors** - Make sure all imports use correct relative paths
2. **API Connection** - Check BlueOrange backend is running and accessible
3. **SQLite Issues** - Check browser console for WebAssembly errors
4. **Build Errors** - Ensure all dependencies are installed

### Debug Mode

Enable SQL debugging in `.env`:
```env
VITE_DEBUG_SQL=true
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This project is part of the BlueOrange office management system.

## 🔗 Related Projects

- **BlueOrange Laravel Backend** - Main office management system
- **BlueOrangeOffline** - Previous vanilla JS implementation

---

**Note**: This is an offline-first application designed to work with the BlueOrange Laravel backend. Make sure your BlueOrange API endpoints are properly configured before using this application.