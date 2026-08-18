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
      sections: [
        {
          section_key: 'intro',
          title: 'Beyond Academics',
          subtitle: 'Holistic Development at Zuvio',
          content: 'Zuvio goes beyond textbooks and examinations, with a focus on curiosity, creativity, critical thinking, communication, collaboration, real-world learning, character and life skills. Technology, innovation, projects, and global opportunities are central themes.'
        },
        {
          section_key: 'activities_placeholder',
          title: 'Our Extracurricular Programs',
          subtitle: 'Sports, Arts & Clubs',
          content: 'Content pending - Specific program descriptions, grades, and schedules for Sports, Music, Dance, Theatre, Visual Arts, Clubs, and Trips will remain draft placeholders until finalized.'
        }
      ],
      gallery: []
    });
  }
});

module.exports = router;
