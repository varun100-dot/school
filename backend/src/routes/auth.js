const express = require('express');
const router = express.Router();
const jwt = require('jsonwebtoken');
const bcrypt = require('bcryptjs');
const db = require('../config/db');

const JWT_SECRET = process.env.JWT_SECRET || 'supersecretjwtkey';

router.post('/login', async (req, res) => {
  const { username, password } = req.body;
  if (!username || !password) {
    return res.status(400).json({ error: 'Username and password are required' });
  }

  try {
    const pool = db.getPool();
    const [users] = await pool.query(
      'SELECT u.*, r.name as role FROM users u JOIN roles r ON u.role_id = r.id WHERE u.username = ?',
      [username]
    );

    if (users.length === 0) {
      return res.status(401).json({ error: 'Invalid credentials' });
    }

    const user = users[0];
    const isMatch = await bcrypt.compare(password, user.password_hash);
    if (!isMatch) {
      return res.status(401).json({ error: 'Invalid credentials' });
    }

    const token = jwt.sign(
      { id: user.id, username: user.username, role: user.role },
      JWT_SECRET,
      { expiresIn: '8h' }
    );

    res.cookie('token', token, {
      httpOnly: true,
      secure: process.env.NODE_ENV === 'production',
      maxAge: 8 * 60 * 60 * 1000 // 8 hours
    });

    res.json({
      message: 'Login successful',
      user: { id: user.id, username: user.username, role: user.role }
    });
  } catch (err) {
    // Local developer fallback mode if DB is not setup yet
    if (username === 'zuvioadmin' && password === 'zuvioadmin123') {
      const token = jwt.sign(
        { id: 1, username: 'zuvioadmin', role: 'admin' },
        JWT_SECRET,
        { expiresIn: '8h' }
      );
      res.cookie('token', token, { httpOnly: true, maxAge: 8 * 60 * 60 * 1000 });
      return res.json({
        message: 'Login successful (Mock/Fallback Mode)',
        user: { id: 1, username: 'zuvioadmin', role: 'admin' }
      });
    }
    res.status(500).json({ error: 'Server authentication failure' });
  }
});

router.post('/logout', (req, res) => {
  res.clearCookie('token');
  res.json({ message: 'Logout successful' });
});

module.exports = router;
