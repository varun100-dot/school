const mysql = require('mysql2/promise');
const path = require('path');

// Load environment variables if they are not loaded by server.js
require('dotenv').config({ path: path.join(__dirname, '../../../.env') });

const dbConfig = {
  host: process.env.DB_HOST || '127.0.0.1',
  port: process.env.DB_PORT || 3306,
  user: process.env.DB_USER || 'root',
  password: process.env.DB_PASSWORD || '',
  database: process.env.DB_NAME || 'zuvio_global_school',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
};

let pool = null;

function getPool() {
  if (!pool) {
    pool = mysql.createPool(dbConfig);
  }
  return pool;
}

async function testConnection() {
  try {
    const currentPool = getPool();
    const connection = await currentPool.getConnection();
    connection.release();
    return { connected: true, host: dbConfig.host, database: dbConfig.database };
  } catch (err) {
    return { connected: false, error: err.message, host: dbConfig.host, database: dbConfig.database };
  }
}

module.exports = {
  getPool,
  testConnection
};
