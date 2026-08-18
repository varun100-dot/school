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
      host: dbStatus.host,
      database: dbStatus.database,
      details: dbStatus.connected ? 'Operational' : dbStatus.error
    },
    timestamp: new Date().toISOString()
  });
});

module.exports = router;
