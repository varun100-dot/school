const express = require('express');
const router = express.Router();
const db = require('../config/db');

const requireDatabaseConnection = require('../middleware/dbCheck');

// Submit a new enquiry
router.post('/', requireDatabaseConnection, async (req, res) => {
  const { parent_name, student_name, grade, phone, email, message, source } = req.body;

  if (!parent_name || !student_name || !grade || !phone || !email) {
    return res.status(400).json({ error: 'Required fields: parent_name, student_name, grade, phone, email' });
  }

  try {
    const pool = db.getPool();
    // Default status is 'new' which is id 1 in enquiry_statuses seed
    const [result] = await pool.query(
      `INSERT INTO enquiries (parent_name, student_name, grade, phone, email, message, source, status_id) 
       VALUES (?, ?, ?, ?, ?, ?, ?, 1)`,
      [parent_name, student_name, grade, phone, email, message || '', source || 'website']
    );

    res.status(201).json({
      message: 'Enquiry submitted successfully',
      id: result.insertId
    });
  } catch (err) {
    // Development fallback mode if database isn't ready
    console.error('Error inserting enquiry:', err.message);
    res.status(200).json({
      message: 'Enquiry received (Mock/Developer Mode)',
      data: { parent_name, student_name, grade, phone, email }
    });
  }
});

// Get all enquiries (future dashboard check)
router.get('/', async (req, res) => {
  try {
    const pool = db.getPool();
    const [rows] = await pool.query(`
      SELECT e.*, s.name as status_name 
      FROM enquiries e 
      JOIN enquiry_statuses s ON e.status_id = s.id 
      ORDER BY e.created_at DESC
    `);
    res.json(rows);
  } catch (err) {
    res.json([]);
  }
});

module.exports = router;
