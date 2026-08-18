const express = require('express');
const router = express.Router();
const db = require('../config/db');

router.get('/', async (req, res) => {
  try {
    const pool = db.getPool();
    const [stages] = await pool.query('SELECT * FROM curriculum_stages WHERE is_active = 1 ORDER BY sort_order ASC');
    const [items] = await pool.query('SELECT * FROM curriculum_items WHERE is_active = 1 ORDER BY sort_order ASC');
    
    // Group items by stage id
    const groupedStages = stages.map(stage => {
      return {
        ...stage,
        items: items.filter(item => item.stage_id === stage.id)
      };
    });
    
    res.json(groupedStages);
  } catch (err) {
    res.json([
      {
        id: 1,
        name: 'Early Years',
        slug: 'early-years',
        description: 'Introduction to fundamental social, cognitive, and physical development steps.',
        items: [
          { title: 'Curiosity and Discovery', description: 'Focus on building exploratory senses and baseline language abilities.' }
        ]
      },
      {
        id: 2,
        name: 'Primary School',
        slug: 'primary-school',
        description: 'Core subjects foundational study (Grades 1 to 5).',
        items: [
          { title: 'Core Foundations', description: 'Mathematics, Science, English, and Social Studies aligned with CBSE/NIOS.' }
        ]
      },
      {
        id: 3,
        name: 'Middle School',
        slug: 'middle-school',
        description: 'Analytical thinking and specialized modules alignment (Grades 6 to 8).',
        items: [
          { title: 'Analytical Growth', description: 'Critical thinking, advanced science foundations, and initial technology exposure.' },
          { title: 'Extracurricular Activities', description: 'Content pending - detailed grade-wise extracurricular activity lists will follow.' }
        ]
      }
    ]);
  }
});

module.exports = router;
