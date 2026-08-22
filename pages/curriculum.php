<?php
// Zuvio Global School - Our Curriculum Page Template (Visual Adaptation of Official PDF)
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

$page_slug = 'our-curriculum';
include_once dirname(__FILE__) . '/../includes/header.php';
?>

<!-- PAGE 1 — HERO & THE ZUVIO LEARNING JOURNEY (White Background) -->
<section class="curriculum-stage-section bg-white" style="padding-top: 100px;">
  <div class="curriculum-container">
    <div style="max-width: 1100px; margin: 0 auto; text-align: center;">
      <!-- Title & Subtitle hierarchy matching Page 1 -->
      <h1 class="t-hero-title" style="margin-bottom: 1rem; color: var(--color-navy-dark); text-transform: uppercase; font-family: var(--font-primary);">CURRICULUM INSIGHTS</h1>
      <p class="t-sub-title" style="margin-bottom: 3rem; color: var(--color-teal); font-family: var(--font-secondary); font-weight: 600;">
        A Future-Ready Learning Journey | Nursery to Grade 8
      </p>
      
      <!-- Introductory Paragraphs -->
      <div style="text-align: left; margin-bottom: 4rem;">
        <p class="t-body-large" style="margin-bottom: 1.5rem; color: var(--color-text);">
          At Zuvio Global School, learning is designed to grow with every child. From stories, sounds, numbers and discovery in the Early Years to research, innovation, technology and independent thinking in Middle School, every stage builds upon the previous one.
        </p>
        <p class="t-body-large" style="color: var(--color-text); margin-bottom: 0;">
          Our curriculum is designed in alignment with CBSE, NEP 2020 and NCF principles, combining strong academic foundations with creativity, communication, digital fluency, life skills and real-world learning.
        </p>
      </div>

      <!-- THE ZUVIO LEARNING JOURNEY PROCESS STRIP -->
      <div class="illustration-container" style="margin-top: 2rem;">
        <div class="illustration-title">THE ZUVIO LEARNING JOURNEY</div>
        <div class="curriculum-grid-4" style="gap: 20px; margin-bottom: 0;">
          
          <div class="curriculum-grid-cell" style="background-color: #ECFDF5; border-color: #A7F3D0; text-align: center; padding: 25px 15px; justify-content: center; gap: 6px;">
            <span style="font-size: 0.8rem; font-weight: 700; color: #059669; text-transform: uppercase; letter-spacing: 1px;">Nursery-KG</span>
            <h4 class="curriculum-grid-cell-title" style="margin: 0; text-transform: uppercase; font-size: 1.45rem;">Explore</h4>
          </div>

          <div class="curriculum-grid-cell" style="background-color: #FFFBEB; border-color: #FDE68A; text-align: center; padding: 25px 15px; justify-content: center; gap: 6px;">
            <span style="font-size: 0.8rem; font-weight: 700; color: #D97706; text-transform: uppercase; letter-spacing: 1px;">Grades 1-2</span>
            <h4 class="curriculum-grid-cell-title" style="margin: 0; text-transform: uppercase; font-size: 1.45rem;">Build</h4>
          </div>

          <div class="curriculum-grid-cell" style="background-color: #EFF6FF; border-color: #BFDBFE; text-align: center; padding: 25px 15px; justify-content: center; gap: 6px;">
            <span style="font-size: 0.8rem; font-weight: 700; color: #2563EB; text-transform: uppercase; letter-spacing: 1px;">Grades 3-5</span>
            <h4 class="curriculum-grid-cell-title" style="margin: 0; text-transform: uppercase; font-size: 1.45rem;">Understand</h4>
          </div>

          <div class="curriculum-grid-cell" style="background-color: #F5F3FF; border-color: #DDD6FE; text-align: center; padding: 25px 15px; justify-content: center; gap: 6px;">
            <span style="font-size: 0.8rem; font-weight: 700; color: #7C3AED; text-transform: uppercase; letter-spacing: 1px;">Grades 6-8</span>
            <h4 class="curriculum-grid-cell-title" style="margin: 0; text-transform: uppercase; font-size: 1.45rem;">Innovate</h4>
          </div>

        </div>
        <div style="margin-top: 2.5rem; text-align: center;">
          <p style="font-family: var(--font-primary); font-size: 1.35rem; font-weight: 700; color: var(--color-navy-dark); font-style: italic; margin: 0; letter-spacing: 0.5px;">
            "Strong Foundations. Future Skills. Learning Without Boundaries."
          </p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- PAGE 2 — 1. EARLY YEARS (Soft Blue Background) -->
<section class="curriculum-stage-section bg-soft-blue">
  <div class="curriculum-container">
    
    <!-- Section Title & Description -->
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Nursery - Kindergarten</span>
      <h2 class="t-section-title curriculum-stage-title">1. Early Years</h2>
      <p class="t-sub-title curriculum-stage-subtitle">Explore • Play • Discover</p>
    </div>

    <div style="max-width: 1000px; margin: 0 auto 3rem auto;">
      <p class="t-body-large text-center curriculum-desc-text">
        The Early Years are where curiosity begins. Our curriculum creates a joyful and nurturing online learning environment where children learn through stories, conversations, movement, music, exploration and play. Children are encouraged to observe, speak, listen, question, create and participate.
      </p>
    </div>

    <!-- CORE LEARNING AREAS (Structured Grid Cells) -->
    <div style="margin-bottom: 3.5rem;">
      <h3 style="font-size: 1.3rem; color: var(--color-navy-dark); font-weight: 700; margin-bottom: 1.5rem; text-transform: uppercase; font-family: var(--font-primary); border-left: 4px solid var(--color-gold); padding-left: 12px; letter-spacing: 0.5px;">Core Learning Areas</h3>
      <div class="curriculum-grid-3">
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Early Literacy & Phonics</h4>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Early Numeracy</h4>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Environmental Awareness</h4>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Communication & Vocabulary</h4>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Creative Expression</h4>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Motor & Sensory Development</h4>
        </div>
        <div class="curriculum-grid-cell" style="grid-column: span 1;">
          <h4 class="curriculum-grid-cell-title">Social & Emotional Learning</h4>
        </div>
      </div>
    </div>

    <!-- LEARNING EXPERIENCES & KEY OUTCOMES -->
    <div class="curriculum-grid-2" style="margin-bottom: 3.5rem;">
      <div class="curriculum-grid-cell" style="background-color: #FFFFFF;">
        <h4 class="curriculum-grid-cell-title" style="border-bottom: 1px solid var(--color-border); padding-bottom: 10px; margin-bottom: 10px;">Learning Experiences</h4>
        <ul class="curriculum-item-list" style="gap: 0.75rem;">
          <li><span class="curriculum-bullet-check">&#10004;</span> Storytelling</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Rhymes</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Show & Tell</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Games</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Hands-on Activities</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Art & Craft</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Music & Movement</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Interactive Digital Activities</li>
        </ul>
      </div>

      <div class="curriculum-grid-cell" style="background-color: #FFFFFF;">
        <h4 class="curriculum-grid-cell-title" style="border-bottom: 1px solid var(--color-border); padding-bottom: 10px; margin-bottom: 10px;">Key Outcomes</h4>
        <ul class="curriculum-item-list" style="gap: 0.75rem;">
          <li><span class="curriculum-bullet-check">&#10004;</span> Confidence</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Curiosity</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Communication</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Foundational Literacy</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Foundational Numeracy</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Creativity</li>
          <li><span class="curriculum-bullet-check">&#10004;</span> Independent Learning Habits</li>
        </ul>
      </div>
    </div>

    <!-- ILLUSTRATION: THE EARLY LEARNING GARDEN -->
    <div class="illustration-container">
      <div class="illustration-title">ILLUSTRATION: THE EARLY LEARNING GARDEN</div>
      <div class="visual-strip-row">
        <div class="visual-strip-block bg-green">Language</div>
        <div class="visual-strip-block bg-orange">Numeracy</div>
        <div class="visual-strip-block bg-blue">Creative</div>
        <div class="visual-strip-block bg-purple">Awareness</div>
        <div class="visual-strip-block bg-teal">Comm.</div>
      </div>
    </div>

  </div>
</section>

<!-- PAGE 3 — 2. FOUNDATION STAGE (White Background) -->
<section class="curriculum-stage-section bg-white">
  <div class="curriculum-container">
    
    <!-- Section Title & Description -->
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Grades 1-2</span>
      <h2 class="t-section-title curriculum-stage-title">2. Foundation Stage</h2>
      <p class="t-sub-title curriculum-stage-subtitle">Build • Question • Create</p>
    </div>

    <div style="max-width: 1000px; margin: 0 auto 3rem auto;">
      <p class="t-body-large text-center curriculum-desc-text">
        Students strengthen reading, writing and mathematical foundations while learning to ask questions, communicate ideas and connect classroom concepts with everyday experiences.
      </p>
    </div>

    <!-- CORE SUBJECTS -->
    <div style="margin-bottom: 3.5rem;">
      <h3 style="font-size: 1.3rem; color: var(--color-navy-dark); font-weight: 700; margin-bottom: 1.5rem; text-transform: uppercase; font-family: var(--font-primary); border-left: 4px solid var(--color-gold); padding-left: 12px; letter-spacing: 0.5px;">Core Subjects</h3>
      <div class="curriculum-grid-3">
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">English</h4>
          <p class="curriculum-grid-cell-desc">Foundational reading, writing, comprehension & vocabulary building.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Mathematics</h4>
          <p class="curriculum-grid-cell-desc">Introduction to operations, sizing, and pattern recognition.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Environmental Studies</h4>
          <p class="curriculum-grid-cell-desc">Learning about community, nature, self and environment.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Hindi / Second Language</h4>
          <p class="curriculum-grid-cell-desc">Building primary conversational and writing skills.</p>
        </div>
        <div class="curriculum-grid-cell" style="grid-column: span 2;">
          <h4 class="curriculum-grid-cell-title">Digital Literacy</h4>
          <p class="curriculum-grid-cell-desc">Navigating devices safely and typing baseline skills.</p>
        </div>
      </div>
    </div>

    <!-- BEYOND ACADEMICS -->
    <div style="margin-bottom: 3.5rem;">
      <h3 style="font-size: 1.3rem; color: var(--color-navy-dark); font-weight: 700; margin-bottom: 1.5rem; text-transform: uppercase; font-family: var(--font-primary); border-left: 4px solid var(--color-teal); padding-left: 12px; letter-spacing: 0.5px;">Beyond Academics</h3>
      <div class="curriculum-grid-3">
        <div class="curriculum-grid-cell"><h4 class="curriculum-grid-cell-title">Art & Creativity</h4></div>
        <div class="curriculum-grid-cell"><h4 class="curriculum-grid-cell-title">Communication</h4></div>
        <div class="curriculum-grid-cell"><h4 class="curriculum-grid-cell-title">Life Skills</h4></div>
        <div class="curriculum-grid-cell"><h4 class="curriculum-grid-cell-title">Physical Wellness</h4></div>
        <div class="curriculum-grid-cell"><h4 class="curriculum-grid-cell-title">Social-Emotional Learning</h4></div>
        <div class="curriculum-grid-cell"><h4 class="curriculum-grid-cell-title">General Awareness</h4></div>
      </div>
    </div>

    <!-- FROM CONCEPT TO EXPERIENCE (Editorial block) -->
    <div class="curriculum-grid-cell" style="background-color: #F5F8FA; border: 1px solid #D7DEE6; padding: 30px; margin-bottom: 3.5rem;">
      <h4 class="curriculum-grid-cell-title" style="font-size: 1.25rem; margin-bottom: 1rem; text-transform: uppercase;">From Concept to Experience</h4>
      <p class="t-body-large" style="margin: 0; color: var(--color-text);">
        A learner may study plants, observe one at home, record its growth, discuss observations and create a mini project. The aim is to show that learning exists beyond the screen and textbook.
      </p>
    </div>

    <!-- KEY OUTCOMES -->
    <div style="margin-bottom: 4rem;">
      <h3 style="font-size: 1.3rem; color: var(--color-navy-dark); font-weight: 700; margin-bottom: 1.5rem; text-transform: uppercase; font-family: var(--font-primary); border-left: 4px solid #2563EB; padding-left: 12px; letter-spacing: 0.5px;">Key Outcomes</h3>
      <div class="curriculum-grid-3">
        <div class="curriculum-grid-cell" style="text-align: center; padding: 25px 15px; justify-content: center;"><span style="font-weight: 700; font-size: 1.05rem;">Reading & Writing Fluency</span></div>
        <div class="curriculum-grid-cell" style="text-align: center; padding: 25px 15px; justify-content: center;"><span style="font-weight: 700; font-size: 1.05rem;">Foundational Numeracy</span></div>
        <div class="curriculum-grid-cell" style="text-align: center; padding: 25px 15px; justify-content: center;"><span style="font-weight: 700; font-size: 1.05rem;">Curiosity & Independent Study</span></div>
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

<!-- PAGE 4 — 3. PREPARATORY STAGE (Warm Off-White Background) -->
<section class="curriculum-stage-section bg-warm-white">
  <div class="curriculum-container">
    
    <!-- Section Title & Description -->
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Grades 3-5</span>
      <h2 class="t-section-title curriculum-stage-title">3. Preparatory Stage</h2>
      <p class="t-sub-title curriculum-stage-subtitle">Understand • Apply • Collaborate</p>
    </div>

    <div style="max-width: 1000px; margin: 0 auto 3rem auto;">
      <p class="t-body-large text-center curriculum-desc-text">
        Learning becomes increasingly interdisciplinary and application-oriented. Students move beyond knowing concepts to understanding why they matter, how they work and where they can be used.
      </p>
    </div>

    <!-- CORE SUBJECTS -->
    <div style="margin-bottom: 3.5rem;">
      <h3 style="font-size: 1.3rem; color: var(--color-navy-dark); font-weight: 700; margin-bottom: 1.5rem; text-transform: uppercase; font-family: var(--font-primary); border-left: 4px solid var(--color-gold); padding-left: 12px; letter-spacing: 0.5px;">Core Subjects</h3>
      <div class="curriculum-grid-3">
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">English</h4>
          <p class="curriculum-grid-cell-desc">Strengthening grammar, interactive speaking, and contextual writing.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Mathematics</h4>
          <p class="curriculum-grid-cell-desc">Geometry baseline, fractions, and multiplication logic.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Science</h4>
          <p class="curriculum-grid-cell-desc">Scientific method, experimentation & environmental sciences study.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Social Studies</h4>
          <p class="curriculum-grid-cell-desc">Local administration structures, mapping, and early history.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Hindi / Second Language</h4>
          <p class="curriculum-grid-cell-desc">Literature appreciation, composition, and vocabulary expansion.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Digital Literacy</h4>
          <p class="curriculum-grid-cell-desc">Spreadsheets introductory concepts, design fundamentals, and online safety.</p>
        </div>
      </div>
    </div>

    <!-- FUTURE-READY LEARNING (2-Column Grid matching visual Spec #7) -->
    <div style="margin-bottom: 3.5rem;">
      <h3 style="font-size: 1.3rem; color: var(--color-navy-dark); font-weight: 700; margin-bottom: 1.5rem; text-transform: uppercase; font-family: var(--font-primary); border-left: 4px solid var(--color-teal); padding-left: 12px; letter-spacing: 0.5px;">Future-Ready Learning</h3>
      <div class="curriculum-grid-2">
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Coding & Computational Thinking</h4>
          <p class="curriculum-grid-cell-desc">Logical thinking and an introduction to how technology works.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Communication & Public Speaking</h4>
          <p class="curriculum-grid-cell-desc">Expressing ideas clearly and confidently.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Financial Awareness</h4>
          <p class="curriculum-grid-cell-desc">Money, saving, spending and responsible choices.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Creative Arts</h4>
          <p class="curriculum-grid-cell-desc">Imagination, design and self-expression.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Life Skills</h4>
          <p class="curriculum-grid-cell-desc">Decision-making, responsibility and independence.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Health & Wellness</h4>
          <p class="curriculum-grid-cell-desc">Healthy physical and emotional habits.</p>
        </div>
        <div class="curriculum-grid-cell" style="grid-column: span 2;">
          <h4 class="curriculum-grid-cell-title">Environmental Awareness</h4>
          <p class="curriculum-grid-cell-desc">Sustainability and responsibility towards the planet.</p>
        </div>
      </div>
    </div>

    <!-- KEY OUTCOMES -->
    <div style="margin-bottom: 4rem;">
      <h3 style="font-size: 1.3rem; color: var(--color-navy-dark); font-weight: 700; margin-bottom: 1.5rem; text-transform: uppercase; font-family: var(--font-primary); border-left: 4px solid #2563EB; padding-left: 12px; letter-spacing: 0.5px;">Key Outcomes</h3>
      <div class="curriculum-grid-3">
        <div class="curriculum-grid-cell" style="text-align: center; padding: 25px 15px; justify-content: center;"><span style="font-weight: 700; font-size: 1.05rem;">Project Development Skills</span></div>
        <div class="curriculum-grid-cell" style="text-align: center; padding: 25px 15px; justify-content: center;"><span style="font-weight: 700; font-size: 1.05rem;">Logical & Creative Thinking</span></div>
        <div class="curriculum-grid-cell" style="text-align: center; padding: 25px 15px; justify-content: center;"><span style="font-weight: 700; font-size: 1.05rem;">Effective Collaborative Habits</span></div>
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

<!-- PAGE 5 — 4. MIDDLE SCHOOL (White Background) -->
<section class="curriculum-stage-section bg-white">
  <div class="curriculum-container">
    
    <!-- Section Title & Description -->
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Grades 6-8</span>
      <h2 class="t-section-title curriculum-stage-title">4. Middle School</h2>
      <p class="t-sub-title curriculum-stage-subtitle">Think • Apply • Innovate</p>
    </div>

    <div style="max-width: 1000px; margin: 0 auto 3rem auto;">
      <p class="t-body-large text-center curriculum-desc-text">
        Middle School marks the transition from guided learning towards greater academic independence and intellectual exploration. Students investigate ideas deeply, analyse information, participate in discussions, undertake research and apply knowledge to meaningful challenges.
      </p>
    </div>

    <!-- CORE SUBJECTS -->
    <div style="margin-bottom: 3.5rem;">
      <h3 style="font-size: 1.3rem; color: var(--color-navy-dark); font-weight: 700; margin-bottom: 1.5rem; text-transform: uppercase; font-family: var(--font-primary); border-left: 4px solid var(--color-gold); padding-left: 12px; letter-spacing: 0.5px;">Core Subjects</h3>
      <div class="curriculum-grid-3">
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">English</h4>
          <p class="curriculum-grid-cell-desc">Analytical essay writing, literature study, and high-impact verbal expression.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Mathematics</h4>
          <p class="curriculum-grid-cell-desc">Algebra, equations, ratios, basic statistics, and data visualization.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Science</h4>
          <p class="curriculum-grid-cell-desc">Differentiated study across Physics, Chemistry, Biology, and project works.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Social Science</h4>
          <p class="curriculum-grid-cell-desc">History, Geography, and Civics exploring global and local systems.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Hindi / Second Language</h4>
          <p class="curriculum-grid-cell-desc">Advanced comprehension, writing portfolios, and literary analysis.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-cell-title">Digital & Technology Education</h4>
          <p class="curriculum-grid-cell-desc">Basics of hardware, software systems, computer networks, and file systems.</p>
        </div>
      </div>
    </div>

    <!-- FUTURE SKILLS -->
    <div style="margin-bottom: 3.5rem;">
      <h3 style="font-size: 1.3rem; color: var(--color-navy-dark); font-weight: 700; margin-bottom: 1.5rem; text-transform: uppercase; font-family: var(--font-primary); border-left: 4px solid var(--color-teal); padding-left: 12px; letter-spacing: 0.5px;">Future Skills</h3>
      <div class="curriculum-grid-3">
        <div class="curriculum-grid-cell"><h4 class="curriculum-grid-cell-title">Coding & AI Awareness</h4></div>
        <div class="curriculum-grid-cell"><h4 class="curriculum-grid-cell-title">Entrepreneurship</h4></div>
        <div class="curriculum-grid-cell"><h4 class="curriculum-grid-cell-title">Financial Literacy</h4></div>
        <div class="curriculum-grid-cell"><h4 class="curriculum-grid-cell-title">Research & Critical Thinking</h4></div>
        <div class="curriculum-grid-cell"><h4 class="curriculum-grid-cell-title">Public Speaking & Comm.</h4></div>
        <div class="curriculum-grid-cell"><h4 class="curriculum-grid-cell-title">Design Thinking</h4></div>
        <div class="curriculum-grid-cell"><h4 class="curriculum-grid-cell-title">Leadership</h4></div>
        <div class="curriculum-grid-cell"><h4 class="curriculum-grid-cell-title">Global Citizenship</h4></div>
        <div class="curriculum-grid-cell"><h4 class="curriculum-grid-cell-title">Career & Interest Exploration</h4></div>
      </div>
    </div>

    <!-- LEARNING EXPERIENCES (Full width) -->
    <div class="curriculum-grid-cell" style="background-color: #F5F8FA; border: 1px solid #D7DEE6; padding: 30px; margin-bottom: 3.5rem;">
      <h4 class="curriculum-grid-cell-title" style="font-size: 1.25rem; margin-bottom: 1rem; text-transform: uppercase;">Learning Experiences</h4>
      <p class="t-body-large" style="margin: 0; color: var(--color-text);">
        Case Studies • Research Projects • Debates • Experiments • Collaborative Assignments • Presentations • Innovation Challenges • Real-World Problem Solving
      </p>
    </div>

    <!-- KEY OUTCOMES -->
    <div style="margin-bottom: 4rem;">
      <h3 style="font-size: 1.3rem; color: var(--color-navy-dark); font-weight: 700; margin-bottom: 1.5rem; text-transform: uppercase; font-family: var(--font-primary); border-left: 4px solid #2563EB; padding-left: 12px; letter-spacing: 0.5px;">Key Outcomes</h3>
      <div class="curriculum-grid-3">
        <div class="curriculum-grid-cell" style="text-align: center; padding: 25px 15px; justify-content: center;"><span style="font-weight: 700; font-size: 1.05rem;">Deep Critical Analysis</span></div>
        <div class="curriculum-grid-cell" style="text-align: center; padding: 25px 15px; justify-content: center;"><span style="font-weight: 700; font-size: 1.05rem;">Academic Autonomy</span></div>
        <div class="curriculum-grid-cell" style="text-align: center; padding: 25px 15px; justify-content: center;"><span style="font-weight: 700; font-size: 1.05rem;">Practical Innovation Talents</span></div>
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

<!-- PAGE 6 — 5. THE ZUVIO LEARNING FRAMEWORK (Soft Blue Background) -->
<section class="curriculum-stage-section bg-soft-blue">
  <div class="curriculum-container">
    
    <!-- Title & Subtitle -->
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Core Framework</span>
      <h2 class="t-section-title curriculum-stage-title">5. The Zuvio Learning Framework</h2>
      <p class="t-sub-title curriculum-stage-subtitle">From Knowing to Doing</p>
    </div>

    <div style="max-width: 1000px; margin: 0 auto 3rem auto;">
      <p class="t-body-large text-center curriculum-desc-text">
        Learning should not end when a child remembers an answer. Every Zuvio learning experience moves through five dimensions that progressively transform knowledge into confident application.
      </p>
    </div>

    <!-- 5 FRAMEWORK ITEMS IN A BALANCED 2-COLUMN GRID -->
    <div class="curriculum-grid-2" style="margin-bottom: 4rem;">
      <div class="curriculum-grid-cell" style="background-color: #FFFFFF;">
        <h4 style="margin: 0; padding: 0; border: none; font-size: 1.3rem; font-weight: 700; color: #059669; text-transform: uppercase;">KNOW</h4>
        <strong style="font-size: 1rem; display: block; margin-top: 4px; color: var(--color-navy-dark);">Build the Foundation</strong>
        <p class="curriculum-grid-cell-desc" style="margin-top: 8px;">Understand essential concepts, facts and ideas.</p>
      </div>

      <div class="curriculum-grid-cell" style="background-color: #FFFFFF;">
        <h4 style="margin: 0; padding: 0; border: none; font-size: 1.3rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase;">THINK</h4>
        <strong style="font-size: 1rem; display: block; margin-top: 4px; color: var(--color-navy-dark);">Develop Understanding</strong>
        <p class="curriculum-grid-cell-desc" style="margin-top: 8px;">Question, analyse, compare, reason and solve.</p>
      </div>

      <div class="curriculum-grid-cell" style="background-color: #FFFFFF;">
        <h4 style="margin: 0; padding: 0; border: none; font-size: 1.3rem; font-weight: 700; color: #2563EB; text-transform: uppercase;">CREATE</h4>
        <strong style="font-size: 1rem; display: block; margin-top: 4px; color: var(--color-navy-dark);">Turn Ideas Into Possibilities</strong>
        <p class="curriculum-grid-cell-desc" style="margin-top: 8px;">Imagine, experiment, design and innovate.</p>
      </div>

      <div class="curriculum-grid-cell" style="background-color: #FFFFFF;">
        <h4 style="margin: 0; padding: 0; border: none; font-size: 1.3rem; font-weight: 700; color: #7C3AED; text-transform: uppercase;">CONNECT</h4>
        <strong style="font-size: 1rem; display: block; margin-top: 4px; color: var(--color-navy-dark);">Learn With the World</strong>
        <p class="curriculum-grid-cell-desc" style="margin-top: 8px;">Communicate, collaborate and understand different perspectives.</p>
      </div>

      <div class="curriculum-grid-cell" style="background-color: #FFFFFF; grid-column: span 2;">
        <h4 style="margin: 0; padding: 0; border: none; font-size: 1.3rem; font-weight: 700; color: var(--color-teal); text-transform: uppercase;">APPLY</h4>
        <strong style="font-size: 1rem; display: block; margin-top: 4px; color: var(--color-navy-dark);">Make Learning Meaningful</strong>
        <p class="curriculum-grid-cell-desc" style="margin-top: 8px;">Use knowledge confidently in projects, challenges and real-life situations.</p>
      </div>
    </div>

    <!-- ILLUSTRATION: FRAMEWORK STRIP -->
    <div class="illustration-container" style="background-color: var(--color-white);">
      <div class="illustration-title">ILLUSTRATION: KNOW &rarr; THINK &rarr; CREATE &rarr; CONNECT &rarr; APPLY</div>
      <div class="visual-strip-row" style="align-items: center;">
        <div class="visual-strip-block bg-green">KNOW</div>
        <div style="color: var(--color-gold); font-weight: bold; font-size: 1.75rem;" class="arrow-indicator">&rarr;</div>
        <div class="visual-strip-block bg-orange">THINK</div>
        <div style="color: var(--color-gold); font-weight: bold; font-size: 1.75rem;" class="arrow-indicator">&rarr;</div>
        <div class="visual-strip-block bg-blue">CREATE</div>
        <div style="color: var(--color-gold); font-weight: bold; font-size: 1.75rem;" class="arrow-indicator">&rarr;</div>
        <div class="visual-strip-block bg-purple">CONNECT</div>
        <div style="color: var(--color-gold); font-weight: bold; font-size: 1.75rem;" class="arrow-indicator">&rarr;</div>
        <div class="visual-strip-block bg-teal">APPLY</div>
      </div>
      <div style="text-align: center; margin-top: 3rem;">
        <h4 style="font-size: 1.5rem; font-weight: 700; color: var(--color-navy-dark); margin: 0; font-family: var(--font-primary);">
          Knowledge &rarr; Understanding &rarr; Application &rarr; Innovation
        </h4>
      </div>
    </div>

  </div>
</section>

<!-- PAGE 7 — 6. LEARNING BEYOND & 7. ASSESSMENT (White Background) -->
<section class="curriculum-stage-section bg-white">
  <div class="curriculum-container">
    
    <!-- 6. Learning Beyond the Textbook -->
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Real-World Exposure</span>
      <h2 class="t-section-title curriculum-stage-title">6. Learning Beyond the Textbook</h2>
      <p class="t-sub-title curriculum-stage-subtitle">Because the World Is the Real Classroom</p>
    </div>

    <!-- 2-COLUMN × 3-ROW GRID (Bordered Cells matching visual Spec #10) -->
    <div class="curriculum-grid-2" style="margin-bottom: 6rem;">
      <div class="curriculum-grid-cell">
        <h4 class="curriculum-grid-cell-title">Projects & Experiments</h4>
        <p class="curriculum-grid-cell-desc">Learning by doing, observing, testing and discovering.</p>
      </div>
      <div class="curriculum-grid-cell">
        <h4 class="curriculum-grid-cell-title">Technology & Digital Learning</h4>
        <p class="curriculum-grid-cell-desc">Using technology creatively, effectively and responsibly.</p>
      </div>
      <div class="curriculum-grid-cell">
        <h4 class="curriculum-grid-cell-title">Communication & Collaboration</h4>
        <p class="curriculum-grid-cell-desc">Presentations, discussions, teamwork and global interactions.</p>
      </div>
      <div class="curriculum-grid-cell">
        <h4 class="curriculum-grid-cell-title">Life Skills</h4>
        <p class="curriculum-grid-cell-desc">Decision-making, independence, financial awareness and emotional intelligence.</p>
      </div>
      <div class="curriculum-grid-cell">
        <h4 class="curriculum-grid-cell-title">Creativity & Innovation</h4>
        <p class="curriculum-grid-cell-desc">Art, coding, design thinking and problem-solving challenges.</p>
      </div>
      <div class="curriculum-grid-cell">
        <h4 class="curriculum-grid-cell-title">Global Exposure</h4>
        <p class="curriculum-grid-cell-desc">Cultures, perspectives and ideas beyond geographical boundaries.</p>
      </div>
    </div>

    <!-- 7. Assessment at Zuvio -->
    <div class="curriculum-stage-header text-center" style="margin-top: 80px;">
      <span class="curriculum-stage-title-number">Evaluation Process</span>
      <h2 class="t-section-title curriculum-stage-title">7. Assessment at Zuvio</h2>
      <p class="t-sub-title curriculum-stage-subtitle">Measure Growth, Not Just Marks</p>
    </div>

    <div style="max-width: 1000px; margin: 0 auto 3rem auto;">
      <p class="t-body-large text-center curriculum-desc-text" style="margin-bottom: 2rem;">
        Assessment helps learners move forward rather than simply generating a score. It can include concept checks, quizzes, projects, assignments, presentations, portfolios, skill-based assessments and term assessments.
      </p>
    </div>

    <!-- PROCESS STRIP: ASSESSMENT -->
    <div class="illustration-container" style="margin-bottom: 3.5rem; margin-top: 0;">
      <div class="illustration-title">ASSESSMENT STRIP</div>
      <div class="visual-strip-row">
        <div class="visual-strip-block bg-green">Learn</div>
        <div class="visual-strip-block bg-orange">Assess</div>
        <div class="visual-strip-block bg-blue">Understand</div>
        <div class="visual-strip-block bg-purple">Support</div>
        <div class="visual-strip-block bg-teal">Progress</div>
      </div>
    </div>

    <!-- Parent Insight block -->
    <div class="curriculum-grid-cell" style="background-color: var(--color-surface-warm); padding: 30px; border-left: 4px solid var(--color-gold); border-top: 1px solid #D7DEE6; border-right: 1px solid #D7DEE6; border-bottom: 1px solid #D7DEE6;">
      <span style="font-weight: 700; color: var(--color-gold); text-transform: uppercase; font-size: 0.95rem; display: block; margin-bottom: 0.5rem; letter-spacing: 0.5px;">Parent Insight</span>
      <p style="font-size: 1.05rem; font-style: italic; line-height: 1.8; color: var(--color-text); margin: 0; font-weight: 500;">
        "Parents receive meaningful insights into academic progress, skills development, strengths and areas where additional support may be useful."
      </p>
    </div>

  </div>
</section>

<!-- PAGE 8 — 8. PERSONALISED LEARNING & 9. ZUVIO BEYOND (Warm Off-White Background) -->
<section class="curriculum-stage-section bg-warm-white">
  <div class="curriculum-container">
    
    <!-- 8. Personalised Learning -->
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Individual Growth</span>
      <h2 class="t-section-title curriculum-stage-title">8. Personalised Learning</h2>
      <p class="t-sub-title curriculum-stage-subtitle">Every Learner Has a Different Learning Journey</p>
    </div>

    <div style="max-width: 1000px; margin: 0 auto 3rem auto;">
      <p class="t-body-large text-center curriculum-desc-text">
        Children do not necessarily learn at the same speed or in exactly the same way. Zuvio brings together teacher guidance, digital learning tools, regular assessment, individual progress insights and targeted academic support to respond to each learner's progress and needs.
      </p>
    </div>

    <!-- 5 EQUAL VISUAL BLOCKS (Personalised Growth Illustration) -->
    <div class="illustration-container" style="background-color: var(--color-white); margin-bottom: 80px; margin-top: 0;">
      <div class="illustration-title">ILLUSTRATION: PERSONALISED GROWTH</div>
      <div class="visual-strip-row">
        <div class="visual-strip-block bg-green">Strengths</div>
        <div class="visual-strip-block bg-orange">Interests</div>
        <div class="visual-strip-block bg-blue">Needs</div>
        <div class="visual-strip-block bg-purple">Progress</div>
        <div class="visual-strip-block bg-teal">Growth</div>
      </div>
    </div>

    <!-- 9. Zuvio Beyond -->
    <div class="curriculum-stage-header text-center" style="margin-top: 80px;">
      <span class="curriculum-stage-title-number">Specialised Programs</span>
      <h2 class="t-section-title curriculum-stage-title">9. Zuvio Beyond</h2>
      <p class="t-sub-title curriculum-stage-subtitle">Learning That Goes Beyond Academics</p>
    </div>

    <div style="max-width: 1000px; margin: 0 auto 3rem auto;">
      <p class="t-body-large text-center curriculum-desc-text">
        Through Zuvio Beyond, learners can access co-curricular activities, specialised programmes, enrichment opportunities and additional academic support according to their interests and learning needs.
      </p>
    </div>

    <!-- 4-COLUMN CATEGORIES GRID -->
    <div class="curriculum-grid-4" style="margin-bottom: 4rem;">
      <div class="curriculum-grid-cell" style="background-color: #FFFFFF; text-align: center; justify-content: center; padding: 25px 15px;"><span style="font-weight: 700;">Creative Arts</span></div>
      <div class="curriculum-grid-cell" style="background-color: #FFFFFF; text-align: center; justify-content: center; padding: 25px 15px;"><span style="font-weight: 700;">Communication</span></div>
      <div class="curriculum-grid-cell" style="background-color: #FFFFFF; text-align: center; justify-content: center; padding: 25px 15px;"><span style="font-weight: 700;">Thinking Skills</span></div>
      <div class="curriculum-grid-cell" style="background-color: #FFFFFF; text-align: center; justify-content: center; padding: 25px 15px;"><span style="font-weight: 700;">Technology</span></div>
      <div class="curriculum-grid-cell" style="background-color: #FFFFFF; text-align: center; justify-content: center; padding: 25px 15px;"><span style="font-weight: 700;">Wellness</span></div>
      <div class="curriculum-grid-cell" style="background-color: #FFFFFF; text-align: center; justify-content: center; padding: 25px 15px;"><span style="font-weight: 700;">Global Experiences</span></div>
      <div class="curriculum-grid-cell" style="background-color: #FFFFFF; text-align: center; justify-content: center; padding: 25px 15px;"><span style="font-weight: 700;">Academic Support</span></div>
      <div class="curriculum-grid-cell" style="background-color: #FFFFFF; text-align: center; justify-content: center; padding: 25px 15px;"><span style="font-weight: 700;">Enrichment</span></div>
    </div>

    <!-- 6-COLOUR PROCESS STRIP: ZUVIO BEYOND -->
    <div class="illustration-container" style="background-color: var(--color-white); margin-top: 0;">
      <div class="illustration-title">ILLUSTRATION: ZUVIO BEYOND</div>
      <div class="curriculum-grid-6" style="gap: 15px; margin-bottom: 0;">
        <div class="visual-strip-block bg-green">Create</div>
        <div class="visual-strip-block bg-orange">Communicate</div>
        <div class="visual-strip-block bg-blue">Think</div>
        <div class="visual-strip-block bg-purple">Tech</div>
        <div class="visual-strip-block bg-teal">Wellness</div>
        <div class="visual-strip-block bg-navy">Global</div>
      </div>
    </div>

  </div>
</section>

<!-- PAGE 9 — 10. THE ZUVIO GRADUATE (White Background) -->
<section class="curriculum-stage-section bg-white" style="border-bottom: none;">
  <div class="curriculum-container">
    
    <!-- Title & Subtitle -->
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Target Profile</span>
      <h2 class="t-section-title curriculum-stage-title">10. The Zuvio Graduate</h2>
      <p class="t-sub-title curriculum-stage-subtitle">Who Are We Preparing Our Learners to Become?</p>
    </div>

    <div style="max-width: 1000px; margin: 0 auto 3rem auto;">
      <p class="t-body-large text-center curriculum-desc-text">
        By the end of Grade 8, our goal is not simply to prepare students for the next academic grade. We aim to develop capable, confident learners who can understand, communicate, create, collaborate and adapt.
      </p>
    </div>

    <!-- 9 GRADUATE ATTRIBUTES IN A STRUCTURED GRID (2-Column Grid) -->
    <div class="curriculum-grid-2" style="margin-bottom: 4.5rem;">
      <div class="curriculum-grid-cell">
        <h4 class="curriculum-grid-cell-title">Academically Strong</h4>
        <p class="curriculum-grid-cell-desc">Understands and applies core academic concepts.</p>
      </div>

      <div class="curriculum-grid-cell">
        <h4 class="curriculum-grid-cell-title">Curious</h4>
        <p class="curriculum-grid-cell-desc">Questions, explores and seeks deeper understanding.</p>
      </div>

      <div class="curriculum-grid-cell">
        <h4 class="curriculum-grid-cell-title">Confident & Articulate</h4>
        <p class="curriculum-grid-cell-desc">Communicates ideas clearly and effectively.</p>
      </div>

      <div class="curriculum-grid-cell">
        <h4 class="curriculum-grid-cell-title">Creative</h4>
        <p class="curriculum-grid-cell-desc">Imagines, designs and builds new possibilities.</p>
      </div>

      <div class="curriculum-grid-cell">
        <h4 class="curriculum-grid-cell-title">Digitally Fluent</h4>
        <p class="curriculum-grid-cell-desc">Navigates a technology-driven world responsibly.</p>
      </div>

      <div class="curriculum-grid-cell">
        <h4 class="curriculum-grid-cell-title">Collaborative</h4>
        <p class="curriculum-grid-cell-desc">Works respectfully and productively with others.</p>
      </div>

      <div class="curriculum-grid-cell">
        <h4 class="curriculum-grid-cell-title">Independent</h4>
        <p class="curriculum-grid-cell-desc">Takes increasing ownership of learning.</p>
      </div>

      <div class="curriculum-grid-cell">
        <h4 class="curriculum-grid-cell-title">Globally Aware</h4>
        <p class="curriculum-grid-cell-desc">Values cultures, perspectives and global issues.</p>
      </div>

      <div class="curriculum-grid-cell" style="grid-column: span 2;">
        <h4 class="curriculum-grid-cell-title">Future-Ready</h4>
        <p class="curriculum-grid-cell-desc">Prepared to learn, adapt and grow in a changing world.</p>
      </div>
    </div>

    <!-- 6-COLOUR GRADUATE PROCESS STRIP -->
    <div class="illustration-container" style="background-color: var(--color-surface-blue); border: 1px solid var(--color-border); margin-bottom: 5rem;">
      <div class="illustration-title">ILLUSTRATION: THE ZUVIO GRADUATE</div>
      <div class="curriculum-grid-6" style="gap: 15px; margin-bottom: 0;">
        <div class="visual-strip-block bg-green">Academic</div>
        <div class="visual-strip-block bg-orange">Curious</div>
        <div class="visual-strip-block bg-blue">Confident</div>
        <div class="visual-strip-block bg-purple">Creative</div>
        <div class="visual-strip-block bg-teal">Digital</div>
        <div class="visual-strip-block bg-navy">Global</div>
      </div>
      
      <div style="text-align: center; margin-top: 3.5rem; border-top: 1px solid var(--color-border); padding-top: 2rem;">
        <span style="font-size: 1.15rem; font-weight: 700; color: var(--color-navy-dark); letter-spacing: 2.5px; display: block; margin-bottom: 2rem; line-height: 1.6; text-transform: uppercase;">
          Curious • Confident • Creative • Articulate • Independent • Collaborative • Digitally Fluent • Globally Aware
        </span>
        <h4 style="font-family: var(--font-primary); font-size: 1.85rem; color: var(--color-navy-dark); font-weight: 700; margin: 0; line-height: 1.35; text-transform: uppercase; letter-spacing: 0.5px;">
          Strong Foundations. Future Skills. Learning Without Boundaries.
        </h4>
      </div>
    </div>

  </div>
</section>

<!-- CALL TO ACTION / REGISTRATION -->
<section class="section text-center" style="background-color: var(--color-navy-dark); color: #FFFFFF; padding: 80px 20px;">
  <div class="curriculum-container" style="max-width: 800px;">
    <h2 class="t-section-title" style="color: #FFFFFF; margin-bottom: 1rem; font-family: var(--font-primary); text-transform: uppercase;">Map Your Child's Academic Journey</h2>
    <p class="t-sub-title" style="color: #E2E8F0; margin-bottom: 2.5rem; max-width: 600px; margin-left: auto; margin-right: auto; font-size: 1.1rem; line-height: 1.6;">
      Get in touch with our admissions coordinators to verify grade availability and structure personalized study paths.
    </p>
    <button onclick="openCallbackModal()" class="btn btn-primary" style="padding: 1.15rem 3.5rem; font-size: 1.1rem; border-radius: 4px;">Request Callback</button>
  </div>
</section>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
