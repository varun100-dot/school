const express = require('express');
const router = express.Router();
const db = require('../config/db');

router.get('/', async (req, res) => {
  const dbStatus = await db.testConnection();
  
  res.json({
    status: 'ok',
    service: 'zuvio-api',
    database: {
      connected: dbStatus.connected,
      ready: dbStatus.connected,
      hostConfigured: !!process.env.DB_HOST,
      userConfigured: !!process.env.DB_USER,
      passwordConfigured: !!process.env.DB_PASSWORD,
      databaseConfigured: !!process.env.DB_NAME,
      portConfigured: !!process.env.DB_PORT,
      errorCode: dbStatus.error || null
    },
    timestamp: new Date().toISOString()
  });
});

module.exports = router;
