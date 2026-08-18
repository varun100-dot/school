const readline = require('readline');
const { Writable } = require('stream');
const bcrypt = require('bcryptjs');
const { getPool, testConnection } = require('../src/config/db');

// Secure writable stream to mask password input
const mutableStdout = new Writable({
  write: function(chunk, encoding, callback) {
    if (!this.muted) {
      process.stdout.write(chunk, encoding);
    }
    callback();
  }
});

function askQuestion(query) {
  const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
  });
  return new Promise((resolve) => {
    rl.question(query, (answer) => {
      rl.close();
      resolve(answer.trim());
    });
  });
}

function askPassword(query) {
  mutableStdout.muted = false;
  const rl = readline.createInterface({
    input: process.stdin,
    output: mutableStdout,
    terminal: true
  });
  return new Promise((resolve) => {
    rl.question(query, (answer) => {
      rl.close();
      process.stdout.write('\n');
      resolve(answer);
    });
    mutableStdout.muted = true;
  });
}

async function run() {
  console.log('\n=============================================');
  console.log('  ZUVIO GLOBAL SCHOOL - FIRST ADMIN SETUP');
  console.log('=============================================\n');

  // 1. Verify DB Connectivity
  const dbStatus = await testConnection();
  if (!dbStatus.connected) {
    console.error(`[ERROR] Production Database Unavailable!`);
    console.error(`Details: ${dbStatus.error}`);
    console.error(`Please ensure environment variables (DB_HOST, DB_USER, etc.) are correctly set.\n`);
    process.exit(1);
  }
  console.log(`[OK] Connected to database: ${dbStatus.database} on ${dbStatus.host}\n`);

  // 2. Prompt Credentials
  const username = await askQuestion('Enter Admin Username (default: zuvioadmin): ') || 'zuvioadmin';
  const email = await askQuestion('Enter Admin Email (default: admin@zuvioglobalschool.com): ') || 'admin@zuvioglobalschool.com';
  
  let password = '';
  while (!password) {
    password = await askPassword('Enter Admin Password (hidden): ');
    if (!password) {
      console.log('Password cannot be empty. Please try again.');
    }
  }

  const confirmPassword = await askPassword('Confirm Admin Password (hidden): ');
  if (password !== confirmPassword) {
    console.error('[ERROR] Passwords do not match. Aborting.\n');
    process.exit(1);
  }

  console.log('\nProcessing secure credentials hashing...');
  const saltRounds = 10;
  const passwordHash = await bcrypt.hash(password, saltRounds);

  // 3. Upsert User
  try {
    const pool = getPool();
    // Get role id for admin
    const [roles] = await pool.query('SELECT id FROM roles WHERE name = ?', ['admin']);
    if (roles.length === 0) {
      console.error('[ERROR] The "admin" role does not exist in the database. Please import schema & seed.production.sql first.\n');
      process.exit(1);
    }
    const roleId = roles[0].id;

    // Check if user already exists
    const [existing] = await pool.query('SELECT id FROM users WHERE username = ? OR email = ?', [username, email]);
    
    if (existing.length > 0) {
      const userId = existing[0].id;
      await pool.query(
        'UPDATE users SET username = ?, email = ?, password_hash = ?, role_id = ? WHERE id = ?',
        [username, email, passwordHash, roleId, userId]
      );
      console.log(`[SUCCESS] Admin user "${username}" has been successfully updated!`);
    } else {
      await pool.query(
        'INSERT INTO users (username, email, password_hash, role_id) VALUES (?, ?, ?, ?)',
        [username, email, passwordHash, roleId]
      );
      console.log(`[SUCCESS] Admin user "${username}" has been successfully created!`);
    }

    console.log('\nSetup completed. You can now use these credentials to log in to the CMS dashboard.\n');
    process.exit(0);
  } catch (err) {
    console.error(`[ERROR] Failed to seed admin user: ${err.message}\n`);
    process.exit(1);
  }
}

run();
