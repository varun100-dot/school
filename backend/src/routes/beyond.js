const express = require('express');
const router = express.Router();
const db = require('../config/db');

router.get('/', async (req, res) => {
  try {
    const pool = db.getPool();
    const [sections] = await pool.query('SELECT * FROM beyond_sections WHERE is_active = 1');
    const [gallery] = await pool.query('SELECT * FROM beyond_gallery WHERE is_active = 1 ORDER BY sort_order ASC');
    
    res.json({
      sections,
      gallery
    });
  } catch (err) {
    res.json({
      sections: [],
      gallery: []
    });
  }
});

module.exports = router;
