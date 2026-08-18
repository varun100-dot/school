const express = require('express');
const cors = require('cors');
const helmet = require('helmet');
const morgan = require('morgan');
const path = require('path');
const db = require('./config/db');
const errorHandler = require('./middleware/error');

const app = express();
const PORT = process.env.PORT || 5001;

// Basic Security and Utility Middlewares
app.use(helmet());
app.use(morgan('dev'));

// CORS setup supporting both local React dev servers
app.use(cors({
  origin: ['http://localhost:5173', 'http://127.0.0.1:5173'],
  credentials: true
}));

app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Parsing cookies manually to maintain zero-dependency layout
app.use((req, res, next) => {
  req.cookies = {};
  const cookieHeader = req.headers.cookie;
  if (cookieHeader) {
    cookieHeader.split(';').forEach(cookie => {
      const parts = cookie.split('=');
      req.cookies[parts[0].trim()] = (parts[1] || '').trim();
    });
  }
  next();
});

// Import route files
const healthRouter = require('./routes/health');
const authRouter = require('./routes/auth');
const siteRouter = require('./routes/site');
const homeRouter = require('./routes/home');
const aboutRouter = require('./routes/about');
const curriculumRouter = require('./routes/curriculum');
const beyondRouter = require('./routes/beyond');
const blogsRouter = require('./routes/blogs');
const enquiriesRouter = require('./routes/enquiries');
const mediaRouter = require('./routes/media');

// Mount routes
app.use('/api/health', healthRouter);
app.use('/api/auth', authRouter);
app.use('/api/site', siteRouter);
app.use('/api/home', homeRouter);
app.use('/api/about', aboutRouter);
app.use('/api/curriculum', curriculumRouter);
app.use('/api/beyond', beyondRouter);
app.use('/api/blogs', blogsRouter);
app.use('/api/enquiries', enquiriesRouter);
app.use('/api/media', mediaRouter);

// Root fallback diagnostic
app.get('/', (req, res) => {
  res.json({
    message: 'Welcome to Zuvio Global School API',
    endpoints: {
      health: '/api/health',
      settings: '/api/site/settings',
      navigation: '/api/site/navigation',
      homepage: '/api/home',
      about: '/api/about',
      curriculum: '/api/curriculum',
      beyond: '/api/beyond',
      blogs: '/api/blogs',
      enquiries: '/api/enquiries'
    }
  });
});

// Catch-all 404 endpoint
app.use((req, res, next) => {
  res.status(404);
  next(new Error(`Not Found - ${req.originalUrl}`));
});

// Error handling middleware
app.use(errorHandler);

// Start Server and verify DB connection status
app.listen(PORT, async () => {
  console.log(`[API] Server running on port ${PORT}`);
  const dbStatus = await db.testConnection();
  if (dbStatus.connected) {
    console.log(`[DB] Connected`);
  } else {
    console.log(`[DB] Not connected — read-only development mode`);
  }
});
