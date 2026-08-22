<?php
// Zuvio Global School - Our Curriculum Page Template (Master Professional Visual Alignment)
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

$page_slug = 'our-curriculum';
include_once dirname(__FILE__) . '/../includes/header.php';
?>

<!-- PAGE 1 — HERO SECTION (White Background) -->
<section class="curriculum-stage-section bg-white" style="border-bottom: none; padding-top: 120px; padding-bottom: 80px;">
  <div class="curriculum-container">
    <div style="text-align: center; max-width: 1000px; margin: 0 auto;">
      <!-- Zuvio Logo is already in header.php, so we output the Title and Subtitle -->
      <h1 class="t-hero-title" style="color: var(--color-navy-dark); font-family: var(--font-primary); text-transform: uppercase; margin-bottom: 1.5rem; letter-spacing: 2px;">CURRICULUM INSIGHTS</h1>
      <p class="t-sub-title" style="color: var(--color-teal); font-family: var(--font-secondary); margin-bottom: 3.5rem; font-weight: 600;">
        A Future-Ready Learning Journey | Nursery to Grade 8
      </p>
      
      <div style="text-align: left; margin-bottom: 5rem;">
        <p class="t-body-large" style="margin-bottom: 1.5rem; color: var(--color-text); font-weight: 500;">
          At Zuvio Global School, learning is designed to grow with every child. From stories, sounds, numbers and discovery in the Early Years to research, innovation, technology and independent thinking in Middle School, every stage builds upon the previous one.
        </p>
        <p class="t-body-large" style="color: var(--color-text); font-weight: 500;">
          Our curriculum is designed in alignment with CBSE, NEP 2020 and NCF principles, combining strong academic foundations with creativity, communication, digital fluency, life skills and real-world learning.
        </p>
      </div>

      <!-- THE ZUVIO LEARNING JOURNEY -->
      <div class="illustration-container" style="margin-top: 2rem;">
        <div class="illustration-title">THE ZUVIO LEARNING JOURNEY</div>
        <div class="curriculum-grid-4" style="gap: 20px;">
          <div style="background-color: #ECFDF5; border: 1.5px solid #A7F3D0; border-radius: 8px; padding: 2.25rem 1.5rem; text-align: center; box-shadow: var(--shadow-sm);">
            <span style="font-size: 0.85rem; font-weight: 700; color: #059669; display: block; margin-bottom: 0.5rem; letter-spacing: 1.5px; text-transform: uppercase;">Nursery-KG</span>
            <h4 style="font-size: 1.6rem; color: var(--color-navy-dark); font-family: var(--font-primary); font-weight: 700; margin: 0; text-transform: uppercase;">Explore</h4>
          </div>
          <div style="background-color: #FFFBEB; border: 1.5px solid #FDE68A; border-radius: 8px; padding: 2.25rem 1.5rem; text-align: center; box-shadow: var(--shadow-sm);">
            <span style="font-size: 0.85rem; font-weight: 700; color: #D97706; display: block; margin-bottom: 0.5rem; letter-spacing: 1.5px; text-transform: uppercase;">Grades 1-2</span>
            <h4 style="font-size: 1.6rem; color: var(--color-navy-dark); font-family: var(--font-primary); font-weight: 700; margin: 0; text-transform: uppercase;">Build</h4>
          </div>
          <div style="background-color: #EFF6FF; border: 1.5px solid #BFDBFE; border-radius: 8px; padding: 2.25rem 1.5rem; text-align: center; box-shadow: var(--shadow-sm);">
            <span style="font-size: 0.85rem; font-weight: 700; color: #2563EB; display: block; margin-bottom: 0.5rem; letter-spacing: 1.5px; text-transform: uppercase;">Grades 3-5</span>
            <h4 style="font-size: 1.6rem; color: var(--color-navy-dark); font-family: var(--font-primary); font-weight: 700; margin: 0; text-transform: uppercase;">Understand</h4>
          </div>
          <div style="background-color: #F5F3FF; border: 1.5px solid #DDD6FE; border-radius: 8px; padding: 2.25rem 1.5rem; text-align: center; box-shadow: var(--shadow-sm);">
            <span style="font-size: 0.85rem; font-weight: 700; color: #7C3AED; display: block; margin-bottom: 0.5rem; letter-spacing: 1.5px; text-transform: uppercase;">Grades 6-8</span>
            <h4 style="font-size: 1.6rem; color: var(--color-navy-dark); font-family: var(--font-primary); font-weight: 700; margin: 0; text-transform: uppercase;">Innovate</h4>
          </div>
        </div>
        <div style="margin-top: 3rem; text-align: center;">
          <p style="font-family: var(--font-primary); font-size: 1.45rem; font-weight: 700; color: var(--color-navy-dark); font-style: italic; margin: 0; letter-spacing: 0.5px;">
            Strong Foundations. Future Skills. Learning Without Boundaries.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- PAGE 2 — EARLY YEARS (Soft Blue Background) -->
<section class="curriculum-stage-section bg-soft-blue">
  <div class="curriculum-container">
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Nursery - Kindergarten</span>
      <h2 class="t-section-title curriculum-stage-title">1. Early Years</h2>
      <p class="t-sub-title curriculum-stage-subtitle">Explore • Play • Discover</p>
    </div>

    <div style="max-width: 1000px; margin: 0 auto 4rem auto;">
      <p class="t-body-large text-center curriculum-desc-text">
        The Early Years are where curiosity begins. Our curriculum creates a joyful and nurturing online learning environment where children learn through stories, conversations, movement, music, exploration and play. Children are encouraged to observe, speak, listen, question, create and participate.
      </p>
    </div>

    <!-- CORE LEARNING AREAS -->
    <div style="margin-bottom: 4.5rem;">
      <h3 style="font-size: 1.45rem; color: var(--color-navy-dark); font-weight: 700; margin-bottom: 1.75rem; text-transform: uppercase; font-family: var(--font-primary); border-left: 4px solid var(--color-gold); padding-left: 12px; letter-spacing: 0.5px;">Core Learning Areas</h3>
      <div class="curriculum-grid-3">
        <div class="curriculum-list-card" style="border-top-color: #059669; padding: 24px;">
          <h4 style="margin: 0; border: none; padding: 0; font-size: 1.15rem;">Early Literacy & Phonics</h4>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #D97706; padding: 24px;">
          <h4 style="margin: 0; border: none; padding: 0; font-size: 1.15rem;">Early Numeracy</h4>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #2563EB; padding: 24px;">
          <h4 style="margin: 0; border: none; padding: 0; font-size: 1.15rem;">Environmental Awareness</h4>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #7C3AED; padding: 24px;">
          <h4 style="margin: 0; border: none; padding: 0; font-size: 1.15rem;">Communication & Vocabulary</h4>
        </div>
        <div class="curriculum-list-card" style="border-top-color: var(--color-teal); padding: 24px;">
          <h4 style="margin: 0; border: none; padding: 0; font-size: 1.15rem;">Creative Expression</h4>
        </div>
        <div class="curriculum-list-card" style="border-top-color: var(--color-gold); padding: 24px;">
          <h4 style="margin: 0; border: none; padding: 0; font-size: 1.15rem;">Motor & Sensory Development</h4>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #059669; padding: 24px; grid-column: span 1;">
          <h4 style="margin: 0; border: none; padding: 0; font-size: 1.15rem;">Social & Emotional Learning</h4>
        </div>
      </div>
    </div>

    <!-- LEARNING EXPERIENCES & KEY OUTCOMES -->
    <div class="curriculum-grid-2" style="margin-bottom: 5rem;">
      <div class="curriculum-list-card" style="border-top-color: var(--color-teal);">
        <h4>Learning Experiences</h4>
        <ul class="curriculum-item-list">
          <li><span class="curriculum-bullet-check">&#10004;</span> Storytelling & Phonics Fun</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Nursery Rhymes & Action Songs</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Show & Tell Activities</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Sensory Games & Visual Logic</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Simple Hands-on Projects</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Creative Art, Drawing & Crafts</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Music, Auditory Games & Movement</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Interactive Educational Software & Tools</li>
        </ul>
      </div>

      <div class="curriculum-list-card" style="border-top-color: #2563EB;">
        <h4>Key Outcomes</h4>
        <ul class="curriculum-item-list">
          <li><span class="curriculum-bullet-check">&#10004;</span> Self-expression and confidence in speaking</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Curiosity and eager questioning habits</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Foundational alphabet phonics & baseline vocabulary</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Number sense (counting, sorting, sizes)</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Appreciation of environment & nature basics</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Sensory-motor skill coordination</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Healthy socialization and sharing principles</li>
        </ul>
      </div>
    </div>

    <!-- ILLUSTRATION: THE EARLY LEARNING GARDEN -->
    <div class="illustration-container">
      <div class="illustration-title">ILLUSTRATION: THE EARLY LEARNING GARDEN</div>
      <div class="visual-strip-row">
        <div class="visual-strip-block bg-green">Language</div>
        <div class="visual-strip-block bg-orange">Numeracy</div>
        <div class="visual-strip-block bg-blue">Creativity</div>
        <div class="visual-strip-block bg-purple">Awareness</div>
        <div class="visual-strip-block bg-teal">Communication</div>
      </div>
    </div>
  </div>
</section>

<!-- PAGE 3 — FOUNDATION STAGE (White Background) -->
<section class="curriculum-stage-section bg-white">
  <div class="curriculum-container">
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Grades 1-2</span>
      <h2 class="t-section-title curriculum-stage-title">2. Foundation Stage</h2>
      <p class="t-sub-title curriculum-stage-subtitle">Build • Question • Create</p>
    </div>

    <div style="max-width: 1000px; margin: 0 auto 4rem auto;">
      <p class="t-body-large text-center curriculum-desc-text">
        Students strengthen reading, writing and mathematical foundations while learning to ask questions, communicate ideas and connect classroom concepts with everyday experiences.
      </p>
    </div>

    <!-- CORE SUBJECTS -->
    <div style="margin-bottom: 4.5rem;">
      <h3 style="font-size: 1.45rem; color: var(--color-navy-dark); font-weight: 700; margin-bottom: 1.75rem; text-transform: uppercase; font-family: var(--font-primary); border-left: 4px solid var(--color-gold); padding-left: 12px; letter-spacing: 0.5px;">Core Subjects</h3>
      <div class="curriculum-grid-3">
        <div class="curriculum-list-card" style="border-top-color: #059669;">
          <h4>English</h4>
          <p class="t-card-text">Foundational reading, writing, comprehension & vocabulary building.</p>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #D97706;">
          <h4>Mathematics</h4>
          <p class="t-card-text">Introduction to operations, numbers, sizing, and pattern recognition.</p>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #2563EB;">
          <h4>Environmental Studies</h4>
          <p class="t-card-text">Learning about community, nature, self and the physical environment.</p>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #7C3AED;">
          <h4>Hindi / Second Language</h4>
          <p class="t-card-text">Building primary conversational, vocabulary and writing skills.</p>
        </div>
        <div class="curriculum-list-card" style="border-top-color: var(--color-teal); grid-column: span 2;">
          <h4>Digital Literacy</h4>
          <p class="t-card-text">Navigating devices safely, typing baseline skills, and understanding online workspace etiquette.</p>
        </div>
      </div>
    </div>

    <!-- BEYOND ACADEMICS -->
    <div style="margin-bottom: 4.5rem;">
      <h3 style="font-size: 1.45rem; color: var(--color-navy-dark); font-weight: 700; margin-bottom: 1.75rem; text-transform: uppercase; font-family: var(--font-primary); border-left: 4px solid var(--color-teal); padding-left: 12px; letter-spacing: 0.5px;">Beyond Academics</h3>
      <div class="curriculum-grid-3">
        <div class="curriculum-list-card" style="border-top-color: var(--color-gold); padding: 24px;">
          <h4 style="margin: 0; border: none; padding: 0; font-size: 1.15rem;">Art & Creativity</h4>
        </div>
        <div class="curriculum-list-card" style="border-top-color: var(--color-teal); padding: 24px;">
          <h4 style="margin: 0; border: none; padding: 0; font-size: 1.15rem;">Communication</h4>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #2563EB; padding: 24px;">
          <h4 style="margin: 0; border: none; padding: 0; font-size: 1.15rem;">Life Skills</h4>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #7C3AED; padding: 24px;">
          <h4 style="margin: 0; border: none; padding: 0; font-size: 1.15rem;">Physical Wellness</h4>
        </div>
        <div class="curriculum-list-card" style="border-top-color: var(--color-teal); padding: 24px;">
          <h4 style="margin: 0; border: none; padding: 0; font-size: 1.15rem;">Social-Emotional Learning</h4>
        </div>
        <div class="curriculum-list-card" style="border-top-color: var(--color-gold); padding: 24px;">
          <h4 style="margin: 0; border: none; padding: 0; font-size: 1.15rem;">General Awareness</h4>
        </div>
      </div>
    </div>

    <!-- FROM CONCEPT TO EXPERIENCE -->
    <div class="curriculum-list-card" style="border-top-color: #2563EB; margin-bottom: 4.5rem; padding: 40px; background-color: var(--color-surface-blue); border: 1px solid rgba(3, 27, 66, 0.08);">
      <h4 style="font-size: 1.45rem; border-bottom: none; padding-bottom: 0; margin-bottom: 1.5rem; text-transform: uppercase;">From Concept to Experience</h4>
      <p class="t-body-large" style="margin: 0; line-height: 1.8; color: var(--color-text);">
        A learner may study plants, observe one at home, record its growth, discuss observations and create a mini project. The aim is to show that learning exists beyond the screen and textbook.
      </p>
    </div>

    <!-- KEY OUTCOMES -->
    <div style="margin-bottom: 5rem;">
      <h3 style="font-size: 1.45rem; color: var(--color-navy-dark); font-weight: 700; margin-bottom: 1.75rem; text-transform: uppercase; font-family: var(--font-primary); border-left: 4px solid #2563EB; padding-left: 12px; letter-spacing: 0.5px;">Key Outcomes</h3>
      <div class="curriculum-grid-3">
        <div class="curriculum-list-card" style="border-top-color: #059669; padding: 24px; text-align: center;">
          <span style="font-weight: 700; color: var(--color-navy-dark);">Reading & Writing Fluency</span>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #D97706; padding: 24px; text-align: center;">
          <span style="font-weight: 700; color: var(--color-navy-dark);">Basic Mathematical Skills</span>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #2563EB; padding: 24px; text-align: center;">
          <span style="font-weight: 700; color: var(--color-navy-dark);">Inquiring Mindset</span>
        </div>
      </div>
    </div>

    <!-- ILLUSTRATION: QUESTION TO DISCOVERY -->
    <div class="illustration-container">
      <div class="illustration-title">ILLUSTRATION: QUESTION TO DISCOVERY</div>
      <div class="visual-strip-row">
        <div class="visual-strip-block bg-green">Learn</div>
        <div class="visual-strip-block bg-orange">Observe</div>
        <div class="visual-strip-block bg-blue">Record</div>
        <div class="visual-strip-block bg-purple">Discuss</div>
        <div class="visual-strip-block bg-teal">Create</div>
      </div>
    </div>
  </div>
</section>

<!-- PAGE 4 — PREPARATORY STAGE (Warm Off-White Background) -->
<section class="curriculum-stage-section bg-warm-white">
  <div class="curriculum-container">
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Grades 3-5</span>
      <h2 class="t-section-title curriculum-stage-title">3. Preparatory Stage</h2>
      <p class="t-sub-title curriculum-stage-subtitle">Understand • Apply • Collaborate</p>
    </div>

    <div style="max-width: 1000px; margin: 0 auto 4rem auto;">
      <p class="t-body-large text-center curriculum-desc-text">
        Learning becomes increasingly interdisciplinary and application-oriented. Students move beyond knowing concepts to understanding why they matter, how they work and where they can be used.
      </p>
    </div>

    <!-- CORE SUBJECTS -->
    <div style="margin-bottom: 4.5rem;">
      <h3 style="font-size: 1.45rem; color: var(--color-navy-dark); font-weight: 700; margin-bottom: 1.75rem; text-transform: uppercase; font-family: var(--font-primary); border-left: 4px solid var(--color-gold); padding-left: 12px; letter-spacing: 0.5px;">Core Subjects</h3>
      <div class="curriculum-grid-3">
        <div class="curriculum-list-card" style="border-top-color: #059669;">
          <h4>English</h4>
          <p class="t-card-text">Contextual reading comprehension, vocabulary, functional grammar & analytical essay writing.</p>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #D97706;">
          <h4>Mathematics</h4>
          <p class="t-card-text">Fraction calculations, decimal logic, area/perimeter parameters, and logical reasoning.</p>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #2563EB;">
          <h4>Science</h4>
          <p class="t-card-text">Scientific inquiry, plant/animal biological systems, matter states, and experimental logic.</p>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #7C3AED;">
          <h4>Social Studies</h4>
          <p class="t-card-text">History foundations, geography map tracking, civics parameters, and societal exploration.</p>
        </div>
        <div class="curriculum-list-card" style="border-top-color: var(--color-teal);">
          <h4>Hindi / Second Language</h4>
          <p class="t-card-text">Literature studies, grammar structures, paragraph composition, and vocabulary updates.</p>
        </div>
        <div class="curriculum-list-card" style="border-top-color: var(--color-gold);">
          <h4>Digital Literacy</h4>
          <p class="t-card-text">Using spreadsheets, presenting designs, editing documents, and understanding internet safety.</p>
        </div>
      </div>
    </div>

    <!-- FUTURE-READY LEARNING -->
    <div style="margin-bottom: 4.5rem;">
      <h3 style="font-size: 1.45rem; color: var(--color-navy-dark); font-weight: 700; margin-bottom: 1.75rem; text-transform: uppercase; font-family: var(--font-primary); border-left: 4px solid var(--color-teal); padding-left: 12px; letter-spacing: 0.5px;">Future-Ready Learning</h3>
      <div class="curriculum-grid-2">
        <div class="curriculum-list-card" style="border-top-color: #059669;">
          <h4>Coding & Computational Thinking</h4>
          <p class="t-card-text">Introducing logical programming sequences, algorithm block design, and computational loops.</p>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #D97706;">
          <h4>Communication & Public Speaking</h4>
          <p class="t-card-text">Coordinating verbal delivery, confidence guidelines, speech structure, and active listening.</p>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #2563EB;">
          <h4>Financial Awareness</h4>
          <p class="t-card-text">Learning budget basics, currency structures, money management systems, and smart choices.</p>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #7C3AED;">
          <h4>Creative Arts</h4>
          <p class="t-card-text">Exploring design, craft systems, graphic structures, music appreciation, and fine arts.</p>
        </div>
        <div class="curriculum-list-card" style="border-top-color: var(--color-teal);">
          <h4>Life Skills</h4>
          <p class="t-card-text">Developing personal organization, time tracking, self-management, and decision making.</p>
        </div>
        <div class="curriculum-list-card" style="border-top-color: var(--color-gold);">
          <h4>Health & Wellness</h4>
          <p class="t-card-text">Coordinating core nutrition parameters, exercise guidelines, emotional control, and sportsmanship.</p>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #2563EB; grid-column: span 2;">
          <h4>Environmental Awareness</h4>
          <p class="t-card-text">Focusing on resource saving, waste management cycles, planet protection practices, and community responsibilities.</p>
        </div>
      </div>
    </div>

    <!-- KEY OUTCOMES -->
    <div style="margin-bottom: 5rem;">
      <h3 style="font-size: 1.45rem; color: var(--color-navy-dark); font-weight: 700; margin-bottom: 1.75rem; text-transform: uppercase; font-family: var(--font-primary); border-left: 4px solid #2563EB; padding-left: 12px; letter-spacing: 0.5px;">Key Outcomes</h3>
      <div class="curriculum-grid-3">
        <div class="curriculum-list-card" style="border-top-color: #059669; padding: 24px; text-align: center;">
          <span style="font-weight: 700; color: var(--color-navy-dark);">Scientific Observation Skills</span>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #D97706; padding: 24px; text-align: center;">
          <span style="font-weight: 700; color: var(--color-navy-dark);">Collaborative Team Projects</span>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #2563EB; padding: 24px; text-align: center;">
          <span style="font-weight: 700; color: var(--color-navy-dark);">Logical Coding Principles</span>
        </div>
      </div>
    </div>

    <!-- ILLUSTRATION: THE LEARNING LAB -->
    <div class="illustration-container">
      <div class="illustration-title">ILLUSTRATION: THE LEARNING LAB</div>
      <div class="visual-strip-row">
        <div class="visual-strip-block bg-green">Experiment</div>
        <div class="visual-strip-block bg-orange">Code</div>
        <div class="visual-strip-block bg-blue">Research</div>
        <div class="visual-strip-block bg-purple">Present</div>
        <div class="visual-strip-block bg-teal">Collaborate</div>
      </div>
    </div>
  </div>
</section>

<!-- PAGE 5 — MIDDLE SCHOOL (White Background) -->
<section class="curriculum-stage-section bg-white">
  <div class="curriculum-container">
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Grades 6-8</span>
      <h2 class="t-section-title curriculum-stage-title">4. Middle School</h2>
      <p class="t-sub-title curriculum-stage-subtitle">Think • Apply • Innovate</p>
    </div>

    <div style="max-width: 1000px; margin: 0 auto 4rem auto;">
      <p class="t-body-large text-center curriculum-desc-text">
        Middle School marks the transition from guided learning towards greater academic independence and intellectual exploration. Students investigate ideas deeply, analyse information, participate in discussions, undertake research and apply knowledge to meaningful challenges.
      </p>
    </div>

    <!-- CORE SUBJECTS -->
    <div style="margin-bottom: 4.5rem;">
      <h3 style="font-size: 1.45rem; color: var(--color-navy-dark); font-weight: 700; margin-bottom: 1.75rem; text-transform: uppercase; font-family: var(--font-primary); border-left: 4px solid var(--color-gold); padding-left: 12px; letter-spacing: 0.5px;">Core Subjects</h3>
      <div class="curriculum-grid-3">
        <div class="curriculum-list-card" style="border-top-color: #059669;">
          <h4>English</h4>
          <p class="t-card-text">Analytical literary appreciation, grammar, composition models & debate projects.</p>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #D97706;">
          <h4>Mathematics</h4>
          <p class="t-card-text">Advanced equations, geometry parameters, algebra, data statistics & chart structures.</p>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #2563EB;">
          <h4>Science</h4>
          <p class="t-card-text">Detailed branches (Physics laws, Chemistry setups, Biology structures) & science fair projects.</p>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #7C3AED;">
          <h4>Social Science</h4>
          <p class="t-card-text">Detailed history profiles, geography analysis, civics functions & economic models.</p>
        </div>
        <div class="curriculum-list-card" style="border-top-color: var(--color-teal);">
          <h4>Hindi / Second Language</h4>
          <p class="t-card-text">Literature critique, essay writing portfolios, composition metrics, and vocabulary tracking.</p>
        </div>
        <div class="curriculum-list-card" style="border-top-color: var(--color-gold);">
          <h4>Digital & Technology Education</h4>
          <p class="t-card-text">Basics of computer hardware systems, files operations, software logic, and local network setups.</p>
        </div>
      </div>
    </div>

    <!-- FUTURE SKILLS -->
    <div style="margin-bottom: 4.5rem;">
      <h3 style="font-size: 1.45rem; color: var(--color-navy-dark); font-weight: 700; margin-bottom: 1.75rem; text-transform: uppercase; font-family: var(--font-primary); border-left: 4px solid var(--color-teal); padding-left: 12px; letter-spacing: 0.5px;">Future Skills</h3>
      <div class="curriculum-grid-3">
        <div class="curriculum-list-card" style="border-top-color: #059669; padding: 24px;">
          <h4 style="margin: 0; border: none; padding: 0; font-size: 1.15rem;">Coding & AI Awareness</h4>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #D97706; padding: 24px;">
          <h4 style="margin: 0; border: none; padding: 0; font-size: 1.15rem;">Entrepreneurship</h4>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #2563EB; padding: 24px;">
          <h4 style="margin: 0; border: none; padding: 0; font-size: 1.15rem;">Financial Literacy</h4>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #7C3AED; padding: 24px;">
          <h4 style="margin: 0; border: none; padding: 0; font-size: 1.15rem;">Research & Critical Thinking</h4>
        </div>
        <div class="curriculum-list-card" style="border-top-color: var(--color-teal); padding: 24px;">
          <h4 style="margin: 0; border: none; padding: 0; font-size: 1.15rem;">Public Speaking & Comm.</h4>
        </div>
        <div class="curriculum-list-card" style="border-top-color: var(--color-gold); padding: 24px;">
          <h4 style="margin: 0; border: none; padding: 0; font-size: 1.15rem;">Design Thinking</h4>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #2563EB; padding: 24px;">
          <h4 style="margin: 0; border: none; padding: 0; font-size: 1.15rem;">Leadership</h4>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #7C3AED; padding: 24px;">
          <h4 style="margin: 0; border: none; padding: 0; font-size: 1.15rem;">Global Citizenship</h4>
        </div>
        <div class="curriculum-list-card" style="border-top-color: var(--color-teal); padding: 24px;">
          <h4 style="margin: 0; border: none; padding: 0; font-size: 1.15rem;">Career & Interest Exploration</h4>
        </div>
      </div>
    </div>

    <!-- LEARNING EXPERIENCES -->
    <div class="curriculum-list-card" style="border-top-color: #2563EB; margin-bottom: 4.5rem; padding: 40px; background-color: var(--color-surface-blue); border: 1px solid rgba(3, 27, 66, 0.08);">
      <h4 style="font-size: 1.45rem; border-bottom: none; padding-bottom: 0; margin-bottom: 1.5rem; text-transform: uppercase;">Learning Experiences</h4>
      <p class="t-body-large" style="margin: 0; line-height: 1.8; color: var(--color-text);">
        Case Studies • Research Projects • Debates • Experiments • Collaborative Assignments • Presentations • Innovation Challenges • Real-World Problem Solving
      </p>
    </div>

    <!-- KEY OUTCOMES -->
    <div style="margin-bottom: 5rem;">
      <h3 style="font-size: 1.45rem; color: var(--color-navy-dark); font-weight: 700; margin-bottom: 1.75rem; text-transform: uppercase; font-family: var(--font-primary); border-left: 4px solid #2563EB; padding-left: 12px; letter-spacing: 0.5px;">Key Outcomes</h3>
      <div class="curriculum-grid-3">
        <div class="curriculum-list-card" style="border-top-color: #059669; padding: 24px; text-align: center;">
          <span style="font-weight: 700; color: var(--color-navy-dark);">Advanced Problem-Solving Capabilities</span>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #D97706; padding: 24px; text-align: center;">
          <span style="font-weight: 700; color: var(--color-navy-dark);">Independent Critical Investigation</span>
        </div>
        <div class="curriculum-list-card" style="border-top-color: #2563EB; padding: 24px; text-align: center;">
          <span style="font-weight: 700; color: var(--color-navy-dark);">Ready for Higher Secondary Paths</span>
        </div>
      </div>
    </div>

    <!-- ILLUSTRATION: FUTURE SKILLS IN ACTION -->
    <div class="illustration-container">
      <div class="illustration-title">ILLUSTRATION: FUTURE SKILLS IN ACTION</div>
      <div class="visual-strip-row">
        <div class="visual-strip-block bg-green">AI & Code</div>
        <div class="visual-strip-block bg-orange">Think</div>
        <div class="visual-strip-block bg-blue">Lead</div>
        <div class="visual-strip-block bg-purple">Communicate</div>
        <div class="visual-strip-block bg-teal">Global</div>
      </div>
    </div>
  </div>
</section>

<!-- PAGE 6 — ZUVIO LEARNING FRAMEWORK (Soft Blue Background) -->
<section class="curriculum-stage-section bg-soft-blue">
  <div class="curriculum-container">
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Core Framework</span>
      <h2 class="t-section-title curriculum-stage-title">5. The Zuvio Learning Framework</h2>
      <p class="t-sub-title curriculum-stage-subtitle">From Knowing to Doing</p>
    </div>

    <div style="max-width: 1000px; margin: 0 auto 4rem auto;">
      <p class="t-body-large text-center curriculum-desc-text">
        Learning should not end when a child remembers an answer. Every Zuvio learning experience moves through five dimensions that progressively transform knowledge into confident application.
      </p>
    </div>

    <!-- 5 DIMENSIONS IN A 2-COLUMN GRID (5th spans across both) -->
    <div class="curriculum-grid-2" style="margin-bottom: 5rem;">
      <div class="curriculum-list-card" style="border-top-color: #059669; padding: 32px;">
        <h4 style="font-size: 1.35rem; color: #059669; border: none; padding: 0; margin-bottom: 0.5rem; text-transform: uppercase;">KNOW</h4>
        <strong style="font-size: 1rem; display: block; margin-bottom: 0.75rem; color: var(--color-navy-dark);">Build the Foundation</strong>
        <p class="t-card-text" style="color: var(--color-muted); margin: 0;">Understand essential concepts, facts and ideas.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: var(--color-gold); padding: 32px;">
        <h4 style="font-size: 1.35rem; color: var(--color-gold); border: none; padding: 0; margin-bottom: 0.5rem; text-transform: uppercase;">THINK</h4>
        <strong style="font-size: 1rem; display: block; margin-bottom: 0.75rem; color: var(--color-navy-dark);">Develop Understanding</strong>
        <p class="t-card-text" style="color: var(--color-muted); margin: 0;">Question, analyse, compare, reason and solve.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: #2563EB; padding: 32px;">
        <h4 style="font-size: 1.35rem; color: #2563EB; border: none; padding: 0; margin-bottom: 0.5rem; text-transform: uppercase;">CREATE</h4>
        <strong style="font-size: 1rem; display: block; margin-bottom: 0.75rem; color: var(--color-navy-dark);">Turn Ideas Into Possibilities</strong>
        <p class="t-card-text" style="color: var(--color-muted); margin: 0;">Imagine, experiment, design and innovate.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: #7C3AED; padding: 32px;">
        <h4 style="font-size: 1.35rem; color: #7C3AED; border: none; padding: 0; margin-bottom: 0.5rem; text-transform: uppercase;">CONNECT</h4>
        <strong style="font-size: 1rem; display: block; margin-bottom: 0.75rem; color: var(--color-navy-dark);">Learn With the World</strong>
        <p class="t-card-text" style="color: var(--color-muted); margin: 0;">Communicate, collaborate and understand different perspectives.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: var(--color-teal); padding: 32px; grid-column: span 2;">
        <h4 style="font-size: 1.35rem; color: var(--color-teal); border: none; padding: 0; margin-bottom: 0.5rem; text-transform: uppercase;">APPLY</h4>
        <strong style="font-size: 1rem; display: block; margin-bottom: 0.75rem; color: var(--color-navy-dark);">Make Learning Meaningful</strong>
        <p class="t-card-text" style="color: var(--color-muted); margin: 0;">Use knowledge confidently in projects, challenges and real-life situations.</p>
      </div>
    </div>

    <!-- ILLUSTRATION: KNOW &rarr; THINK &rarr; CREATE &rarr; CONNECT &rarr; APPLY -->
    <div class="illustration-container" style="background-color: var(--color-white);">
      <div class="illustration-title">ILLUSTRATION: KNOW &rarr; THINK &rarr; CREATE &rarr; CONNECT &rarr; APPLY</div>
      <div class="visual-strip-row" style="align-items: center;">
        <div class="visual-strip-block bg-green" style="padding: 1.5rem 0.5rem;">KNOW</div>
        <div style="color: var(--color-gold); font-weight: bold; font-size: 1.75rem;" class="arrow-indicator">&rarr;</div>
        <div class="visual-strip-block bg-orange" style="padding: 1.5rem 0.5rem;">THINK</div>
        <div style="color: var(--color-gold); font-weight: bold; font-size: 1.75rem;" class="arrow-indicator">&rarr;</div>
        <div class="visual-strip-block bg-blue" style="padding: 1.5rem 0.5rem;">CREATE</div>
        <div style="color: var(--color-gold); font-weight: bold; font-size: 1.75rem;" class="arrow-indicator">&rarr;</div>
        <div class="visual-strip-block bg-purple" style="padding: 1.5rem 0.5rem;">CONNECT</div>
        <div style="color: var(--color-gold); font-weight: bold; font-size: 1.75rem;" class="arrow-indicator">&rarr;</div>
        <div class="visual-strip-block bg-teal" style="padding: 1.5rem 0.5rem;">APPLY</div>
      </div>
      <div class="text-center" style="margin-top: 3.5rem; border-top: 1px solid var(--color-border); padding-top: 2rem;">
        <span style="font-family: var(--font-primary); font-size: 1.55rem; color: var(--color-navy-dark); font-weight: 700; letter-spacing: 0.5px;">
          Knowledge &rarr; Understanding &rarr; Application &rarr; Innovation
        </span>
      </div>
    </div>
  </div>
</section>

<!-- PAGE 7 — LEARNING BEYOND + ASSESSMENT (White Background) -->
<section class="curriculum-stage-section bg-white">
  <div class="curriculum-container">
    
    <!-- 6. Learning Beyond the Textbook -->
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Holistic Growth</span>
      <h2 class="t-section-title curriculum-stage-title">6. Learning Beyond the Textbook</h2>
      <p class="t-sub-title curriculum-stage-subtitle">Because the World Is the Real Classroom</p>
    </div>

    <div style="max-width: 1000px; margin: 0 auto 4rem auto;">
      <p class="t-body-large text-center curriculum-desc-text">
        Real learning connects what we know with what we can do. Zuvio activities, challenges, projects and co-curricular programs bridge theory and experience.
      </p>
    </div>

    <!-- 2-COLUMN x 3-ROW GRID -->
    <div class="curriculum-grid-2" style="margin-bottom: 8rem;">
      <div class="curriculum-list-card" style="border-top-color: var(--color-gold);">
        <strong style="color: var(--color-navy-dark); font-size: 1.2rem; display: block; margin-bottom: 0.75rem;">Projects & Experiments</strong>
        <p class="t-card-text" style="color: var(--color-muted); margin: 0;">Hands-on inquiry-driven explorations that bridge textbook concepts with physical observations.</p>
      </div>
      <div class="curriculum-list-card" style="border-top-color: var(--color-teal);">
        <strong style="color: var(--color-navy-dark); font-size: 1.2rem; display: block; margin-bottom: 0.75rem;">Technology & Digital Learning</strong>
        <p class="t-card-text" style="color: var(--color-muted); margin: 0;">Interactive digital software setups, creative design, research, and collaborative presentations.</p>
      </div>
      <div class="curriculum-list-card" style="border-top-color: #2563EB;">
        <strong style="color: var(--color-navy-dark); font-size: 1.2rem; display: block; margin-bottom: 0.75rem;">Communication & Collaboration</strong>
        <p class="t-card-text" style="color: var(--color-muted); margin: 0;">Debates, show-and-tell, team projects, presentations, and global cultural exchanges.</p>
      </div>
      <div class="curriculum-list-card" style="border-top-color: #7C3AED;">
        <strong style="color: var(--color-navy-dark); font-size: 1.2rem; display: block; margin-bottom: 0.75rem;">Life Skills</strong>
        <p class="t-card-text" style="color: var(--color-muted); margin: 0;">Smart money habits, organization checklists, self-management, and decision processes.</p>
      </div>
      <div class="curriculum-list-card" style="border-top-color: #059669;">
        <strong style="color: var(--color-navy-dark); font-size: 1.2rem; display: block; margin-bottom: 0.75rem;">Creativity & Innovation</strong>
        <p class="t-card-text" style="color: var(--color-muted); margin: 0;">Art designs, digital coding, engineering frameworks, and problem-solving exercises.</p>
      </div>
      <div class="curriculum-list-card" style="border-top-color: var(--color-navy);">
        <strong style="color: var(--color-navy-dark); font-size: 1.2rem; display: block; margin-bottom: 0.75rem;">Global Exposure</strong>
        <p class="t-card-text" style="color: var(--color-muted); margin: 0;">Intercultural studies, mapping exercises, environmental sciences, and global issues discussions.</p>
      </div>
    </div>

    <!-- 7. Assessment at Zuvio -->
    <div class="curriculum-stage-header text-center" style="margin-top: 100px;">
      <span class="curriculum-stage-title-number">Evaluation Process</span>
      <h2 class="t-section-title curriculum-stage-title">7. Assessment at Zuvio</h2>
      <p class="t-sub-title curriculum-stage-subtitle">Measure Growth, Not Just Marks</p>
    </div>

    <div style="max-width: 1000px; margin: 0 auto 4rem auto; text-align: center;">
      <p class="t-body-large curriculum-desc-text">
        Assessment helps learners move forward rather than simply generating a score. It can include concept checks, quizzes, projects, assignments, presentations, portfolios, skill-based assessments and term assessments.
      </p>

      <!-- Parent Insight -->
      <div style="background-color: var(--color-surface-warm); border: 1.5px solid var(--color-border); border-radius: 8px; padding: 2.5rem; text-align: left; max-width: 800px; margin: 0 auto 5rem auto; box-shadow: var(--shadow-sm);">
        <span style="font-weight: 700; color: var(--color-gold); display: block; margin-bottom: 0.75rem; text-transform: uppercase; font-size: 0.95rem; letter-spacing: 1px;">Parent Insight:</span>
        <p style="font-size: 1.05rem; line-height: 1.8; color: var(--color-text); margin: 0; font-style: italic; font-weight: 500;">
          "Parents receive meaningful insights into academic progress, skills development, strengths and areas where additional support may be useful."
        </p>
      </div>
    </div>

    <!-- ILLUSTRATION: ASSESSMENT FOR GROWTH -->
    <div class="illustration-container">
      <div class="illustration-title">ILLUSTRATION: ASSESSMENT FOR GROWTH</div>
      <div class="visual-strip-row">
        <div class="visual-strip-block bg-green">Learn</div>
        <div class="visual-strip-block bg-orange">Assess</div>
        <div class="visual-strip-block bg-blue">Understand</div>
        <div class="visual-strip-block bg-purple">Support</div>
        <div class="visual-strip-block bg-teal">Progress</div>
      </div>
    </div>
  </div>
</section>

<!-- PAGE 8 — PERSONALISED LEARNING + ZUVIO BEYOND (Warm Off-White Background) -->
<section class="curriculum-stage-section bg-warm-white">
  <div class="curriculum-container">
    
    <!-- 8. Personalised Learning -->
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Individual Attention</span>
      <h2 class="t-section-title curriculum-stage-title">8. Personalised Learning</h2>
      <p class="t-sub-title curriculum-stage-subtitle">Every Learner Has a Different Learning Journey</p>
    </div>

    <div style="max-width: 1000px; margin: 0 auto 4rem auto;">
      <p class="t-body-large text-center curriculum-desc-text">
        Children do not necessarily learn at the same speed or in exactly the same way. Zuvio brings together teacher guidance, digital learning tools, regular assessment, individual progress insights and targeted academic support to respond to each learner's progress and needs.
      </p>
    </div>

    <!-- ILLUSTRATION: PERSONALISED GROWTH -->
    <div class="illustration-container" style="background-color: var(--color-white); margin-bottom: 8rem; border: 1.5px solid var(--color-border);">
      <div class="illustration-title">ILLUSTRATION: PERSONALISED GROWTH</div>
      <div class="visual-strip-row">
        <div class="visual-strip-block bg-green">Strengths</div>
        <div class="visual-strip-block bg-orange">Interests</div>
        <div class="visual-strip-block bg-blue">Learning Needs</div>
        <div class="visual-strip-block bg-purple">Progress</div>
        <div class="visual-strip-block bg-teal">Growth</div>
      </div>
    </div>

    <!-- 9. Zuvio Beyond -->
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Co-Curricular Exploration</span>
      <h2 class="t-section-title curriculum-stage-title">9. Zuvio Beyond</h2>
      <p class="t-sub-title curriculum-stage-subtitle">Learning That Goes Beyond Academics</p>
    </div>

    <div style="max-width: 1000px; margin: 0 auto 4rem auto;">
      <p class="t-body-large text-center curriculum-desc-text">
        Through Zuvio Beyond, learners can access co-curricular activities, specialised programmes, enrichment opportunities and additional academic support according to their interests and learning needs.
      </p>
    </div>

    <!-- 4-COLUMN CATEGORIES GRID -->
    <div class="curriculum-grid-4" style="margin-bottom: 5rem;">
      <div class="curriculum-list-card" style="padding: 2.25rem 1.5rem; text-align: center; border-top-color: #059669; display: flex; align-items: center; justify-content: center;">
        <h5 style="color: var(--color-navy-dark); font-size: 1.25rem; margin: 0; font-family: var(--font-primary); font-weight: 700;">Creative Arts</h5>
      </div>
      <div class="curriculum-list-card" style="padding: 2.25rem 1.5rem; text-align: center; border-top-color: #D97706; display: flex; align-items: center; justify-content: center;">
        <h5 style="color: var(--color-navy-dark); font-size: 1.25rem; margin: 0; font-family: var(--font-primary); font-weight: 700;">Communication</h5>
      </div>
      <div class="curriculum-list-card" style="padding: 2.25rem 1.5rem; text-align: center; border-top-color: #2563EB; display: flex; align-items: center; justify-content: center;">
        <h5 style="color: var(--color-navy-dark); font-size: 1.25rem; margin: 0; font-family: var(--font-primary); font-weight: 700;">Thinking Skills</h5>
      </div>
      <div class="curriculum-list-card" style="padding: 2.25rem 1.5rem; text-align: center; border-top-color: #7C3AED; display: flex; align-items: center; justify-content: center;">
        <h5 style="color: var(--color-navy-dark); font-size: 1.25rem; margin: 0; font-family: var(--font-primary); font-weight: 700;">Technology</h5>
      </div>
      <div class="curriculum-list-card" style="padding: 2.25rem 1.5rem; text-align: center; border-top-color: var(--color-teal); display: flex; align-items: center; justify-content: center;">
        <h5 style="color: var(--color-navy-dark); font-size: 1.25rem; margin: 0; font-family: var(--font-primary); font-weight: 700;">Wellness</h5>
      </div>
      <div class="curriculum-list-card" style="padding: 2.25rem 1.5rem; text-align: center; border-top-color: var(--color-navy); display: flex; align-items: center; justify-content: center;">
        <h5 style="color: var(--color-navy-dark); font-size: 1.25rem; margin: 0; font-family: var(--font-primary); font-weight: 700;">Global Experiences</h5>
      </div>
      <div class="curriculum-list-card" style="padding: 2.25rem 1.5rem; text-align: center; border-top-color: var(--color-gold); display: flex; align-items: center; justify-content: center;">
        <h5 style="color: var(--color-navy-dark); font-size: 1.25rem; margin: 0; font-family: var(--font-primary); font-weight: 700;">Academic Support</h5>
      </div>
      <div class="curriculum-list-card" style="padding: 2.25rem 1.5rem; text-align: center; border-top-color: #059669; display: flex; align-items: center; justify-content: center;">
        <h5 style="color: var(--color-navy-dark); font-size: 1.25rem; margin: 0; font-family: var(--font-primary); font-weight: 700;">Enrichment</h5>
      </div>
    </div>

    <!-- ILLUSTRATION: ZUVIO BEYOND -->
    <div class="illustration-container" style="background-color: var(--color-white); border: 1.5px solid var(--color-border);">
      <div class="illustration-title">ILLUSTRATION: ZUVIO BEYOND</div>
      <div class="curriculum-grid-6" style="gap: 15px;">
        <div class="visual-strip-block bg-green" style="padding: 1.5rem 0.5rem; font-size: 1rem;">Create</div>
        <div class="visual-strip-block bg-orange" style="padding: 1.5rem 0.5rem; font-size: 1rem;">Communicate</div>
        <div class="visual-strip-block bg-blue" style="padding: 1.5rem 0.5rem; font-size: 1rem;">Think</div>
        <div class="visual-strip-block bg-purple" style="padding: 1.5rem 0.5rem; font-size: 1rem;">Tech</div>
        <div class="visual-strip-block bg-teal" style="padding: 1.5rem 0.5rem; font-size: 1rem;">Wellness</div>
        <div class="visual-strip-block bg-navy" style="padding: 1.5rem 0.5rem; font-size: 1rem;">Global</div>
      </div>
    </div>
  </div>
</section>

<!-- PAGE 9 — THE ZUVIO GRADUATE (White Background) -->
<section class="curriculum-stage-section bg-white">
  <div class="curriculum-container">
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Target Learner Profile</span>
      <h2 class="t-section-title curriculum-stage-title">10. The Zuvio Graduate</h2>
      <p class="t-sub-title curriculum-stage-subtitle">Who Are We Preparing Our Learners to Become?</p>
    </div>

    <div style="max-width: 1000px; margin: 0 auto 4rem auto;">
      <p class="t-body-large text-center curriculum-desc-text">
        By the end of Grade 8, our goal is not simply to prepare students for the next academic grade. We aim to develop capable, confident learners who can understand, communicate, create, collaborate and adapt.
      </p>
    </div>

    <!-- 2-COLUMN ATTRIBUTE GRID -->
    <div class="curriculum-grid-2" style="margin-bottom: 6rem; align-items: stretch;">
      <div class="curriculum-list-card" style="border-top-color: var(--color-gold);">
        <h4 style="font-size: 1.2rem; margin-bottom: 0.75rem; border: none; padding: 0;">Academically Strong</h4>
        <p class="t-card-text" style="color: var(--color-muted); margin: 0;">Understands and applies core academic concepts in math, science, and languages.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: var(--color-teal);">
        <h4 style="font-size: 1.2rem; margin-bottom: 0.75rem; border: none; padding: 0;">Curious</h4>
        <p class="t-card-text" style="color: var(--color-muted); margin: 0;">Actively questions, investigates, and seeks deeper logical reasoning behind concepts.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: #2563EB;">
        <h4 style="font-size: 1.2rem; margin-bottom: 0.75rem; border: none; padding: 0;">Confident & Articulate</h4>
        <p class="t-card-text" style="color: var(--color-muted); margin: 0;">Communicates ideas confidently and expresses opinions with structure.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: #7C3AED;">
        <h4 style="font-size: 1.2rem; margin-bottom: 0.75rem; border: none; padding: 0;">Creative</h4>
        <p class="t-card-text" style="color: var(--color-muted); margin: 0;">Designs solutions, thinks outside the box, and constructs original artistic elements.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: #059669;">
        <h4 style="font-size: 1.2rem; margin-bottom: 0.75rem; border: none; padding: 0;">Digitally Fluent</h4>
        <p class="t-card-text" style="color: var(--color-muted); margin: 0;">Uses technology tools effectively, searches safely, and creates projects digitally.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: var(--color-navy);">
        <h4 style="font-size: 1.2rem; margin-bottom: 0.75rem; border: none; padding: 0;">Collaborative</h4>
        <p class="t-card-text" style="color: var(--color-muted); margin: 0;">Works respectfully in teams, coordinates tasks, and accepts group feedback.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: var(--color-gold);">
        <h4 style="font-size: 1.2rem; margin-bottom: 0.75rem; border: none; padding: 0;">Independent</h4>
        <p class="t-card-text" style="color: var(--color-muted); margin: 0;">Takes ownership of study routines, deadlines, and personal improvements.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: var(--color-teal);">
        <h4 style="font-size: 1.2rem; margin-bottom: 0.75rem; border: none; padding: 0;">Globally Aware</h4>
        <p class="t-card-text" style="color: var(--color-muted); margin: 0;">Respects diverse backgrounds, cultures, and understands global citizenship.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: #2563EB; grid-column: span 2;">
        <h4 style="font-size: 1.2rem; margin-bottom: 0.75rem; border: none; padding: 0;">Future-Ready</h4>
        <p class="t-card-text" style="color: var(--color-muted); margin: 0;">Equipped with adaptability, resilience, and curiosity to keep learning in a fluid environment.</p>
      </div>
    </div>

    <!-- ILLUSTRATION: THE ZUVIO GRADUATE -->
    <div class="illustration-container" style="background-color: var(--color-surface-blue); margin-bottom: 6rem; border: 1.5px solid var(--color-border);">
      <div class="illustration-title">ILLUSTRATION: THE ZUVIO GRADUATE</div>
      <div class="curriculum-grid-6" style="gap: 15px;">
        <div class="visual-strip-block bg-green" style="padding: 1.5rem 0.5rem; font-size: 1rem;">Academic</div>
        <div class="visual-strip-block bg-orange" style="padding: 1.5rem 0.5rem; font-size: 1rem;">Curious</div>
        <div class="visual-strip-block bg-blue" style="padding: 1.5rem 0.5rem; font-size: 1rem;">Confident</div>
        <div class="visual-strip-block bg-purple" style="padding: 1.5rem 0.5rem; font-size: 1rem;">Creative</div>
        <div class="visual-strip-block bg-teal" style="padding: 1.5rem 0.5rem; font-size: 1rem;">Digital</div>
        <div class="visual-strip-block bg-navy" style="padding: 1.5rem 0.5rem; font-size: 1rem;">Global</div>
      </div>
      
      <div class="text-center" style="margin-top: 4rem; border-top: 1px solid var(--color-border); padding-top: 2.5rem;">
        <span style="font-size: 1.15rem; font-weight: 700; color: var(--color-navy-dark); letter-spacing: 2.5px; display: block; margin-bottom: 2rem; line-height: 1.6; text-transform: uppercase;">
          Curious • Confident • Creative • Articulate • Independent • Collaborative • Digitally Fluent • Globally Aware
        </span>
        <h4 style="font-family: var(--font-primary); font-size: 2rem; color: var(--color-navy-dark); font-weight: 700; margin: 0; line-height: 1.3;">
          Strong Foundations. Future Skills. Learning Without Boundaries.
        </h4>
      </div>
    </div>
  </div>
</section>

<!-- CALL TO ACTION / REGISTRATION -->
<section class="section text-center" style="background-color: var(--color-navy-dark); color: #FFFFFF; padding: 100px 20px;">
  <div class="curriculum-container" style="max-width: 800px;">
    <h2 class="t-section-title" style="color: #FFFFFF; margin-bottom: 1.5rem; font-family: var(--font-primary);">Map Your Child's Academic Journey</h2>
    <p class="t-sub-title" style="color: #E2E8F0; margin-bottom: 2.5rem; max-width: 600px; margin-left: auto; margin-right: auto;">
      Get in touch with our admissions coordinators to verify grade availability and structure personalized study paths.
    </p>
    <button onclick="openCallbackModal()" class="btn btn-primary" style="padding: 1.15rem 3.5rem; font-size: 1.1rem; border-radius: 4px;">Request Callback</button>
  </div>
</section>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
