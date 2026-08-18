const express = require('express');
const router = express.Router();
const db = require('../config/db');

router.get('/', async (req, res) => {
  try {
    const pool = db.getPool();
    const [slides] = await pool.query('SELECT * FROM hero_slides WHERE is_active = 1 ORDER BY sort_order ASC');
    const [sections] = await pool.query('SELECT * FROM homepage_sections WHERE is_active = 1');
    const [stats] = await pool.query('SELECT * FROM homepage_stats WHERE is_active = 1 ORDER BY sort_order ASC');
    const [features] = await pool.query('SELECT * FROM homepage_features WHERE is_active = 1 ORDER BY sort_order ASC');
    
    res.json({
      slides,
      sections,
      stats,
      features
    });
  } catch (err) {
    // Fallback data
    res.json({
      slides: [
        {
          title: 'Learning Beyond Boundaries',
          subtitle: 'A future-ready online school where academic excellence meets personalised learning.',
          image: '/assets/images/Hero image 1.png'
        }
      ],
      sections: [],
      stats: [
        { label: 'Established', value: '2026' },
        { label: 'Ratio', value: '15:1' }
      ],
      features: []
    });
  }
});

module.exports = router;
