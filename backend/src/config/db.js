const mysql = require('mysql2/promise');
const path = require('path');

// Load environment variables from parent levels if available (for local monorepo layouts)
require('dotenv').config({ path: path.join(__dirname, '../../../.env') });
require('dotenv').config({ path: path.join(__dirname, '../../.env') });
require('dotenv').config();

// Safe Startup Diagnostics
console.log('\n=============================================');
console.log('[DB] Environment variables diagnostic:');
console.log(`  DB_HOST configured: ${process.env.DB_HOST ? 'YES' : 'NO'}`);
console.log(`  DB_PORT configured: ${process.env.DB_PORT ? 'YES' : 'NO'}`);
console.log(`  DB_USER configured: ${process.env.DB_USER ? 'YES' : 'NO'}`);
console.log(`  DB_PASSWORD configured: ${process.env.DB_PASSWORD ? 'YES' : 'NO'}`);
console.log(`  DB_NAME configured: ${process.env.DB_NAME ? 'YES' : 'NO'}`);
console.log('=============================================\n');

const isProduction = process.env.NODE_ENV === 'production' || (process.env.PORT && process.env.PORT !== '5001');

// Production Validation Gate
if (isProduction) {
  const missing = [];
  if (!process.env.DB_HOST) missing.push('DB_HOST');
  if (!process.env.DB_USER) missing.push('DB_USER');
  if (!process.env.DB_PASSWORD) missing.push('DB_PASSWORD');
  if (!process.env.DB_NAME) missing.push('DB_NAME');

  if (missing.length > 0) {
    console.error(`[DB] Required production environment variables are missing: ${missing.join(', ')}`);
    console.error('[DB] Silently falling back to development defaults is disabled in production.');
    throw new Error('Database configuration missing in production');
  }
}

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
    let sanitizedError = err.message || 'Unknown database connection error';
    
    // Obfuscate sensitive credentials from error message outputs
    if (dbConfig.password) {
      sanitizedError = sanitizedError.split(dbConfig.password).join('******');
    }
    if (dbConfig.user) {
      sanitizedError = sanitizedError.split(dbConfig.user).join('******');
    }
    if (dbConfig.host) {
      sanitizedError = sanitizedError.split(dbConfig.host).join('******');
    }
    
    return { connected: false, error: sanitizedError, host: '******', database: dbConfig.database };
  }
}

module.exports = {
  getPool,
  testConnection
};
