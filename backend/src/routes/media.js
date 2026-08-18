const express = require('express');
const router = express.Router();
const db = require('../config/db');

// List registered media files
router.get('/', async (req, res) => {
  try {
    const pool = db.getPool();
    const [rows] = await pool.query('SELECT * FROM media ORDER BY created_at DESC');
    res.json(rows);
  } catch (err) {
    res.json([]);
  }
});

// Register uploaded media entry (Phase 1 Stub)
router.post('/register', async (req, res) => {
  const { file_name, storage_path, public_url, alt_text, mime_type, file_size } = req.body;
  if (!file_name || !storage_path || !public_url) {
    return res.status(400).json({ error: 'Missing file_name, storage_path or public_url' });
  }

  try {
    const pool = db.getPool();
    const [result] = await pool.query(
      'INSERT INTO media (file_name, storage_path, public_url, alt_text, mime_type, file_size) VALUES (?, ?, ?, ?, ?, ?)',
      [file_name, storage_path, public_url, alt_text || '', mime_type || '', file_size || 0]
    );
    res.status(201).json({ message: 'Media metadata registered', id: result.insertId });
  } catch (err) {
    res.status(500).json({ error: 'Database registry failure' });
  }
});

module.exports = router;
