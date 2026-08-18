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
      ready: dbStatus.connected
    },
    timestamp: new Date().toISOString()
  });
});

module.exports = router;
