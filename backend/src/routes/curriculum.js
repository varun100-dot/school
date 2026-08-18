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
    res.json([]);
  }
});

module.exports = router;
