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
    // Phase 2.1 Rich Fallback Data
    res.json({
      slides: [
        {
          title: 'Learning Beyond Boundaries',
          subtitle: 'Zuvio Global School',
          description: 'A future-ready online school where academic excellence meets personalised learning. We empower children to grow with confidence.',
          image: '/assets/images/Hero image 1.png',
          primary_cta_text: 'Explore Zuvio',
          primary_cta_url: '/curriculum',
          secondary_cta_text: 'Enquire Now',
          secondary_cta_url: '/contact'
        },
        {
          title: 'Academic & Global Partnerships',
          subtitle: 'Oxford & IAO Collaborations',
          description: 'Zuvio Global School collaborates with Oxford and IAO, strengthening our commitment to globally benchmarked learning, academic excellence, and international standards.',
          image: '/assets/images/Hero image 2.png',
          primary_cta_text: 'About Us',
          primary_cta_url: '/about',
          secondary_cta_text: 'Enquire Now',
          secondary_cta_url: '/contact'
        },
        {
          title: 'CBSE & NIOS Aligned Curriculum',
          subtitle: 'Future-Ready Education',
          description: 'A well-designed curriculum aligned with CBSE, NEP 2020, and NCF, combining academic rigour with future-ready skills.',
          image: '/assets/images/Students learning in classroom.png',
          primary_cta_text: 'Our Curriculum',
          primary_cta_url: '/curriculum',
          secondary_cta_text: 'Enquire Now',
          secondary_cta_url: '/contact'
        },
        {
          title: 'World-Class Teachers',
          subtitle: 'Personalised Learning Paths',
          description: 'Experienced educators who bring expertise, diverse perspectives, and engaging teaching practices.',
          image: '/assets/images/Teacher interacting with students.png',
          primary_cta_text: 'Read Blog',
          primary_cta_url: '/blogs',
          secondary_cta_text: 'Enquire Now',
          secondary_cta_url: '/contact'
        }
      ],
      sections: [
        {
          section_key: 'brand_promise',
          title: 'Every child deserves an education that prepares them for life, not just examinations.',
          subtitle: 'Brand Promise & Beliefs',
          content: 'We are not building another school. We are building a future where every child has the opportunity to learn beyond boundaries.'
        }
      ],
      stats: [
        { label: 'Established', value: '2026' },
        { label: 'Student-Teacher Ratio', value: '15:1' },
        { label: 'Students Enrolled', value: 'Content pending' },
        { label: 'World-Class Educators', value: 'Content pending' }
      ],
      features: [
        { title: 'Global Presence', description: 'A globally connected learning community with an international outlook.' },
        { title: 'International Credibility', description: 'Global standards, perspectives, and learning practices designed for a changing world.' },
        { title: 'World-Class Teachers', description: 'Experienced educators who bring expertise, diverse perspectives, and engaging teaching practices.' },
        { title: 'US-Based Learning Platform', description: 'A powerful, thoughtfully designed US-based LMS that brings learning, collaboration, resources, and progress tracking together.' },
        { title: 'CBSE, NEP & NCF Aligned', description: 'A well-designed curriculum aligned with CBSE, NEP 2020, and NCF, combining academic rigour with future-ready skills.' },
        { title: 'Personalised Learning', description: 'Learning experiences that recognise every child\'s unique pace, strengths, interests, and potential.' },
        { title: 'Inclusive Learning', description: 'A supportive environment with a special focus on special learners, ensuring every child feels included, valued, and empowered.' },
        { title: 'World-Class Learning Experiences', description: 'Beyond academics—with technology, creativity, collaboration, projects, and real-world experiences.' }
      ]
    });
  }
});

module.exports = router;
