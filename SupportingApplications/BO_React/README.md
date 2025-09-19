

```
# React + Electron + TypeScript + Vite
A simple, fast development setup for building a desktop app with Electron and a React frontend powered by Vite and TypeScript.

Important
- This app does not require a SupportingApplications folder. Ignore any instructions referring to it.
- You do not need to install React or Electron globally. Everything is managed via this repository’s package.json.

## Prerequisites
- Node.js: 18.x or newer (LTS recommended)
- npm: 9.x or newer (comes with Node)
- Git: to clone the repository
- OS: macOS, Windows, or Linux

Optional (recommended)
- nvm (Node Version Manager) to match the project’s Node version if .nvmrc present.

## Quick Start (TL;DR)
1) Clone and enter the project:
   - git clone <your-repo-url>
   - cd <your-project-folder>

2) Install dependencies:
   - npm install

3) Start development:
   - Web (Vite + React only): npm run dev
   - Desktop (Electron app): check package.json scripts and run either:
     - npm run dev:electron
     - or npm run electron:dev

4) Build:
   - Web build: npm run build
   - Desktop build/package: check package.json for one of:
     - npm run build:electron
     - npm run electron:build
     - npm run package

Notes
- If a script name differs, use the one defined in your package.json. The repository doesn’t need a SupportingApplications folder.

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

## Project Structure (high level)
Note: Your exact structure may differ, but a common layout is:
- src/ — React + TypeScript source (renderer)
- electron/ or app/ — Electron main process files (e.g., main.ts)
- public/ — Static assets
- dist/ — Build output (generated)
- package.json — Scripts and dependencies

No SupportingApplications folder is required.

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

