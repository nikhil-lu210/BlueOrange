const { app, BrowserWindow } = require('electron');
const path = require('path');
const { pathToFileURL } = require('url');
const fs = require('fs');

// Load environment variables from .env file
function loadEnvFile() {
  const envPath = path.join(__dirname, '..', '.env');
  if (fs.existsSync(envPath)) {
    const envContent = fs.readFileSync(envPath, 'utf8');
    const envLines = envContent.split('\n');

    envLines.forEach(line => {
      const trimmedLine = line.trim();
      if (trimmedLine && !trimmedLine.startsWith('#')) {
        const [key, ...valueParts] = trimmedLine.split('=');
        if (key && valueParts.length > 0) {
          const value = valueParts.join('=').replace(/^["']|["']$/g, ''); // Remove quotes
          process.env[key.trim()] = value.trim();
        }
      }
    });
  }
}

// Load environment variables
loadEnvFile();

// Get DEBUG_MODE from environment variable
const DEBUG_MODE = process.env.VITE_DEBUG_MODE === 'true';

function createWindow() {
  const win = new BrowserWindow({
    width: 1200,
    height: 800,
    webPreferences: {
      nodeIntegration: false,
      contextIsolation: true,
      preload: path.join(__dirname, 'preload.js'),
      icon: path.join(__dirname, '..', 'public', 'assets', 'favicon.ico'),
      devTools: DEBUG_MODE ? true : false,
    },
  });

  // Log potential issues in production
  win.webContents.on('did-fail-load', (event, errorCode, errorDescription, validatedURL) => {
    console.error('did-fail-load:', errorCode, errorDescription, validatedURL)
  });
  win.webContents.on('crashed', () => {
    console.error('Renderer process crashed')
  });
  win.webContents.on('console-message', (event, level, message, line, sourceId) => {
    console.log(`console[${level}] ${sourceId}:${line} ${message}`)
  });

  if (!app.isPackaged) {
    win.loadURL('http://localhost:5173');
    if (DEBUG_MODE) {
      win.webContents.openDevTools();
    }
  } else {
    const indexPath = path.join(__dirname, '..', 'dist', 'index.html');
    const indexUrl = pathToFileURL(indexPath).toString();
    win.loadURL(indexUrl).catch(err => {
      console.error('Error loading index.html:', indexUrl, err);
    });
  }
}

app.whenReady().then(createWindow);

app.on('window-all-closed', () => {
  if (process.platform !== 'darwin') app.quit();
});

app.on('activate', () => {
  if (BrowserWindow.getAllWindows().length === 0) createWindow();
});

