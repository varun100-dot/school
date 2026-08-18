const db = require('../config/db');

async function requireDatabaseConnection(req, res, next) {
  const dbStatus = await db.testConnection();
  if (!dbStatus.connected) {
    return res.status(503).json({
      success: false,
      error: 'Database unavailable',
      message: 'This operation requires an active database connection.'
    });
  }
  next();
}

module.exports = requireDatabaseConnection;
