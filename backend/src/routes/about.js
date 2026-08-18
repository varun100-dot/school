const express = require('express');
const router = express.Router();
const db = require('../config/db');

router.get('/', async (req, res) => {
  try {
    const pool = db.getPool();
    const [sections] = await pool.query('SELECT * FROM about_sections WHERE is_active = 1');
    const [timeline] = await pool.query('SELECT * FROM about_timeline WHERE is_active = 1 ORDER BY sort_order ASC');
    const [leadership] = await pool.query('SELECT * FROM leadership WHERE is_active = 1 ORDER BY sort_order ASC');
    
    res.json({
      sections,
      timeline,
      leadership
    });
  } catch (err) {
    res.json({
      sections: [],
      timeline: [],
      leadership: [
        { name: 'Pragya Jain', designation: 'Co-Founder & Director' },
        { name: 'Deepak Jain', designation: 'Co-Founder & Director' }
      ]
    });
  }
});

module.exports = router;
