const express = require('express');
const router = express.Router();
const db = require('../config/db');

// Get all site settings
router.get('/settings', async (req, res) => {
  try {
    const pool = db.getPool();
    const [rows] = await pool.query('SELECT setting_key, setting_value FROM site_settings');
    const settings = {};
    rows.forEach(r => {
      settings[r.setting_key] = r.setting_value;
    });
    res.json(settings);
  } catch (err) {
    res.json({
      school_name: 'Zuvio Global School',
      phone: '7827262956',
      general_email: 'info@zuvioglobalschool.com',
      address: 'B-09, Lower Ground Floor, ITL Twin Tower, Netaji Subhash Place, Pitampura, Delhi - 110034',
      office_timings: '10-7',
      copyright: '© 2026 Zuvio Global School. All rights reserved.'
    });
  }
});

// Get navigation menu items
router.get('/navigation', async (req, res) => {
  try {
    const pool = db.getPool();
    const [rows] = await pool.query('SELECT * FROM navigation_items WHERE is_active = 1 ORDER BY sort_order ASC');
    res.json(rows);
  } catch (err) {
    res.json([
      { label: 'Home', url: '/' },
      { label: 'About Us', url: '/about-us' },
      { label: 'Our Curriculum', url: '/our-curriculum' },
      { label: 'Zuvio Beyond', url: '/zuvio-beyond' },
      { label: 'Blogs', url: '/blogs' },
      { label: 'Contact Us', url: '/contact-us' }
    ]);
  }
});

// Get SEO config for all pages
router.get('/seo', async (req, res) => {
  try {
    const pool = db.getPool();
    const [rows] = await pool.query(`
      SELECT p.slug, s.* 
      FROM page_seo s 
      JOIN pages p ON s.page_id = p.id
    `);
    res.json(rows);
  } catch (err) {
    res.json([]);
  }
});

module.exports = router;
