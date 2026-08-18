# Zuvio Global School — Phase 1 CMS Foundation

A future-ready online school where academic excellence meets personalised learning.

## Monorepo Architecture

- **frontend/**: React + Vite web application containing visual system elements, custom routes, layouts, and components.
- **backend/**: Node.js + Express REST API handling user sessions, blogging, media entries, SEO details, contact forms, and config records.
- **database/**: Local schema and seed resources for a standardized MySQL setup.
- **assets/**: Raw assets including images and content files.

## Running the Application

### 1. Prerequisites
- Node.js (v22.x recommended)
- MySQL Server

### 2. Environment Setup
Create a `.env` file at the root of the project:
```bash
cp .env.example .env
```
And populate it with your local MySQL credentials.

### 3. Database Import
Create a database named `zuvio_global_school` and import the schema and seeds:
```bash
mysql -u root -p zuvio_global_school < database/schema.sql
mysql -u root -p zuvio_global_school < database/seed.sql
```

### 4. Install Dependencies
Run the installation script to configure all packages:
```bash
npm run install:all
```

### 5. Start Development Servers
Run the following script to spin up the React frontend and Express backend concurrently:
```bash
npm run dev
```
- Frontend: `http://localhost:5173`
- Backend API: `http://localhost:5001`
- Diagnostics Dashboard: Navigate to `/health` or check the health page on startup.
