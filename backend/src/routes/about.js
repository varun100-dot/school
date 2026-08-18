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
      sections: [
        {
          section_key: 'our_story',
          title: 'Our Story',
          subtitle: 'How Zuvio Began',
          content: 'Zuvio Global School began from a question: “What if education was designed for the child instead of expecting the child to fit the system?” As technology, AI and global connectivity changed the world, education also needed to evolve. Zuvio was conceived to provide flexible, personalised, globally connected learning that nurtures creativity, critical thinking, communication and adaptability.'
        },
        {
          section_key: 'vision_mission',
          title: 'Vision, Mission & Beliefs',
          subtitle: 'Our Compass',
          content: 'Vision: To create a world where every child can access future-ready, personalised and globally connected learning without boundaries, and to empower every child to become a confident global citizen of tomorrow.\n\nMission: To empower every child to discover their potential and thrive in a changing world by combining academic excellence with creativity, critical thinking, technology and life skills.\n\nEducational Philosophy: “Every Child. Every Mind. Every Possibility.”'
        }
      ],
      timeline: [
        { year: '2026', title: 'Foundation', description: 'Zuvio Global School is officially established, introducing the 8C Philosophy™, Zuvio Compass™, and Learning Model™.' }
      ],
      leadership: [
        { name: 'Pragya Jain', designation: 'Co-Founder & Director', bio: 'Content pending', message: 'Content pending' },
        { name: 'Deepak Jain', designation: 'Co-Founder & Director', bio: 'Content pending', message: 'Content pending' }
      ]
    });
  }
});

module.exports = router;
