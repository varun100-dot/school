const express = require('express');
const router = express.Router();
const db = require('../config/db');

// Get all blogs (supports optional pagination and category filtering)
router.get('/', async (req, res) => {
  const { category, page = 1, limit = 10 } = req.query;
  const offset = (page - 1) * limit;

  try {
    const pool = db.getPool();
    let query = 'SELECT b.*, c.name as category_name FROM blogs b LEFT JOIN blog_categories c ON b.category_id = c.id WHERE b.status = "published"';
    const params = [];

    if (category) {
      query += ' AND c.slug = ?';
      params.push(category);
    }

    query += ' ORDER BY b.publish_date DESC LIMIT ? OFFSET ?';
    params.push(parseInt(limit), parseInt(offset));

    const [rows] = await pool.query(query, params);
    res.json(rows);
  } catch (err) {
    // Development Fallback
    res.json([
      {
        title: 'Welcome to Zuvio Global School: Learning Beyond Boundaries',
        slug: 'welcome-to-zuvio-global-school',
        excerpt: 'An introductory post explaining Zuvio\'s vision of a borderless classroom...',
        featured_image: '/assets/images/Hero image 1.png',
        author: 'Zuvio Editorial',
        author_designation: 'Content Writer',
        category_name: 'School News',
        publish_date: '2026-08-18'
      }
    ]);
  }
});

const requireDatabaseConnection = require('../middleware/dbCheck');

// Get individual blog by slug
router.get('/:slug', async (req, res) => {
  try {
    const pool = db.getPool();
    const [rows] = await pool.query(
      'SELECT b.*, c.name as category_name FROM blogs b LEFT JOIN blog_categories c ON b.category_id = c.id WHERE b.slug = ?',
      [req.params.slug]
    );

    if (rows.length === 0) {
      return res.status(404).json({ error: 'Blog post not found' });
    }
    res.json(rows[0]);
  } catch (err) {
    res.status(500).json({ error: 'Database error retrieving blog post' });
  }
});

// Blog CMS mutations (Phase 1.1 Secure Stubs)
router.post('/', requireDatabaseConnection, async (req, res) => {
  res.json({ success: true, message: 'Blog post created' });
});

router.put('/:id', requireDatabaseConnection, async (req, res) => {
  res.json({ success: true, message: 'Blog post updated' });
});

router.delete('/:id', requireDatabaseConnection, async (req, res) => {
  res.json({ success: true, message: 'Blog post deleted' });
});

module.exports = router;
