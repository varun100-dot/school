<?php
// Zuvio Global School - Our Curriculum Page Template (Master Professional Redesign)
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

$page_slug = 'our-curriculum';
include_once dirname(__FILE__) . '/../includes/header.php';
?>

<!-- PAGE 1: CURRICULUM INSIGHTS HERO -->
<section class="curriculum-hero" style="background-image: linear-gradient(rgba(3, 27, 66, 0.9), rgba(3, 27, 66, 0.95)), url('/assets/images/Students learning in classroom.png');">
  <div class="curriculum-container">
    <span style="font-size: 0.95rem; font-weight: 600; color: var(--color-gold); text-transform: uppercase; letter-spacing: 3px; display: block; margin-bottom: 1rem;">Official Academic Curriculum</span>
    <h1 class="t-hero-title" style="color: #FFFFFF; font-family: var(--font-primary); margin-bottom: 1.5rem;">CURRICULUM INSIGHTS</h1>
    <p class="t-sub-title" style="color: #E2E8F0; font-family: var(--font-secondary); margin-bottom: 2rem; max-width: 800px; margin-left: auto; margin-right: auto;">
      A Future-Ready Learning Journey | Nursery to Grade 8
    </p>
    <div style="width: 80px; height: 3px; background-color: var(--color-gold); margin: 2rem auto;"></div>
  </div>
</section>

<!-- PAGE 1: INTRODUCTION & THE ZUVIO LEARNING JOURNEY -->
<section class="curriculum-stage-section" style="background-color: var(--color-white);">
  <div class="curriculum-container">
    <div style="max-width: 1000px; margin: 0 auto 5rem auto; text-align: center;">
      <h2 class="t-section-title" style="margin-bottom: 2rem; color: var(--color-navy);">An Editorial Introduction</h2>
      <p class="t-body-large" style="color: var(--color-text); margin-bottom: 1.5rem; font-weight: 500;">
        At Zuvio Global School, learning is designed to grow with every child. From stories, sounds, numbers and discovery in the Early Years to research, innovation, technology and independent thinking in Middle School, every stage builds upon the previous one.
      </p>
      <p class="t-body-large" style="color: var(--color-muted);">
        Our curriculum is designed in alignment with CBSE, NEP 2020 and NCF principles, combining strong academic foundations with creativity, communication, digital fluency, life skills and real-world learning.
      </p>
    </div>

    <!-- THE ZUVIO LEARNING JOURNEY ILLUSTRATION -->
    <div class="illustration-container">
      <div class="illustration-title">THE ZUVIO LEARNING JOURNEY</div>
      <div class="grid-4" style="gap: 28px;">
        
        <div style="background-color: #ECFDF5; border: 1.5px solid #A7F3D0; border-radius: var(--radius-md); padding: 2.5rem 2rem; text-align: center; box-shadow: var(--shadow-sm); transition: transform 0.2s ease;">
          <span style="font-size: 0.85rem; font-weight: 700; color: #059669; display: block; margin-bottom: 0.75rem; letter-spacing: 1.5px;">NURSERY - KG</span>
          <h4 style="font-size: 1.75rem; color: #031B42; font-family: var(--font-primary); font-weight: 700; margin-bottom: 0;">EXPLORE</h4>
        </div>

        <div style="background-color: #FFFBEB; border: 1.5px solid #FDE68A; border-radius: var(--radius-md); padding: 2.5rem 2rem; text-align: center; box-shadow: var(--shadow-sm); transition: transform 0.2s ease;">
          <span style="font-size: 0.85rem; font-weight: 700; color: #D97706; display: block; margin-bottom: 0.75rem; letter-spacing: 1.5px;">GRADES 1 - 2</span>
          <h4 style="font-size: 1.75rem; color: #031B42; font-family: var(--font-primary); font-weight: 700; margin-bottom: 0;">BUILD</h4>
        </div>

        <div style="background-color: #EFF6FF; border: 1.5px solid #BFDBFE; border-radius: var(--radius-md); padding: 2.5rem 2rem; text-align: center; box-shadow: var(--shadow-sm); transition: transform 0.2s ease;">
          <span style="font-size: 0.85rem; font-weight: 700; color: #2563EB; display: block; margin-bottom: 0.75rem; letter-spacing: 1.5px;">GRADES 3 - 5</span>
          <h4 style="font-size: 1.75rem; color: #031B42; font-family: var(--font-primary); font-weight: 700; margin-bottom: 0;">UNDERSTAND</h4>
        </div>

        <div style="background-color: #F5F3FF; border: 1.5px solid #DDD6FE; border-radius: var(--radius-md); padding: 2.5rem 2rem; text-align: center; box-shadow: var(--shadow-sm); transition: transform 0.2s ease;">
          <span style="font-size: 0.85rem; font-weight: 700; color: #7C3AED; display: block; margin-bottom: 0.75rem; letter-spacing: 1.5px;">GRADES 6 - 8</span>
          <h4 style="font-size: 1.75rem; color: #031B42; font-family: var(--font-primary); font-weight: 700; margin-bottom: 0;">INNOVATE</h4>
        </div>

      </div>
      
      <div class="text-center" style="margin-top: 3.5rem; border-top: 1px solid var(--color-border); padding-top: 2rem;">
        <p style="font-family: var(--font-primary); font-size: 1.45rem; font-weight: 700; color: var(--color-navy); font-style: italic; margin: 0; letter-spacing: 0.5px;">
          "Strong Foundations. Future Skills. Learning Without Boundaries."
        </p>
      </div>
    </div>
  </div>
</section>

<!-- PAGE 2: 1. EARLY YEARS -->
<section class="curriculum-stage-section">
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

    <div class="grid-3" style="gap: 28px;">
      
      <!-- Core Learning Areas -->
      <div class="curriculum-list-card" style="border-top-color: var(--color-gold);">
        <h4>Core Learning Areas</h4>
        <ul class="curriculum-item-list">
          <li><span class="curriculum-bullet-check">&#10004;</span> <span>Early Literacy & Phonics</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <span>Early Numeracy</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <span>Environmental Awareness</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <span>Communication & Vocabulary</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <span>Creative Expression</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <span>Motor & Sensory Development</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <span>Social & Emotional Learning</span></li>
        </ul>
      </div>

      <!-- Learning Experiences -->
      <div class="curriculum-list-card" style="border-top-color: var(--color-teal);">
        <h4>Learning Experiences</h4>
        <ul class="curriculum-item-list">
          <li><span class="curriculum-bullet-check">&#10004;</span> <span>Storytelling</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <span>Rhymes</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <span>Show & Tell</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <span>Games</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <span>Hands-on Activities</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <span>Art & Craft</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <span>Music & Movement</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <span>Interactive Digital Activities</span></li>
        </ul>
      </div>

      <!-- Key Outcomes -->
      <div class="curriculum-list-card" style="border-top-color: #2563EB;">
        <h4>Key Outcomes</h4>
        <ul class="curriculum-item-list">
          <li><span class="curriculum-bullet-check">&#10004;</span> <span>Confidence</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <span>Curiosity</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <span>Communication</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <span>Foundational Literacy</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <span>Foundational Numeracy</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <span>Creativity</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <span>Independent Learning Habits</span></li>
        </ul>
      </div>

    </div>

    <!-- Illustration: The Early Learning Garden -->
    <div class="illustration-container">
      <div class="illustration-title">ILLUSTRATION: THE EARLY LEARNING GARDEN</div>
      <div class="visual-strip-row">
        <div class="visual-strip-block bg-green">Language</div>
        <div class="visual-strip-block bg-orange">Numeracy</div>
        <div class="visual-strip-block bg-teal">Creativity</div>
        <div class="visual-strip-block bg-purple">Awareness</div>
        <div class="visual-strip-block bg-blue">Communication</div>
      </div>
    </div>

  </div>
</section>

<!-- PAGE 3: 2. FOUNDATION STAGE -->
<section class="curriculum-stage-section">
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

    <div class="curriculum-info-grid">
      <!-- Core Subjects -->
      <div class="curriculum-list-card" style="border-top-color: var(--color-gold);">
        <h4>Core Subjects</h4>
        <ul class="curriculum-item-list">
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>English:</strong> <span>Foundational reading, writing, comprehension & vocabulary building.</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>Mathematics:</strong> <span>Introduction to operations, sizing, and pattern recognition.</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>Environmental Studies:</strong> <span>Learning about community, nature, self and environment.</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>Hindi / Second Language:</strong> <span>Building primary conversational and writing skills.</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>Digital Literacy:</strong> <span>Navigating devices safely and typing baseline skills.</span></li>
        </ul>
      </div>

      <!-- Beyond Academics & Concept to Experience -->
      <div class="curriculum-list-card" style="border-top-color: var(--color-teal); display: flex; flex-direction: column; justify-content: space-between;">
        <div>
          <h4>Beyond Academics</h4>
          <div class="grid-2" style="gap: 1rem; margin-bottom: 2rem;">
            <div class="t-card-text"><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Art & Creativity</div>
            <div class="t-card-text"><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Communication</div>
            <div class="t-card-text"><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Life Skills</div>
            <div class="t-card-text"><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Physical Wellness</div>
            <div class="t-card-text"><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Social-Emotional Learning</div>
            <div class="t-card-text"><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> General Awareness</div>
          </div>
        </div>
        
        <div style="background-color: var(--color-surface-warm); border-left: 4px solid var(--color-teal); padding: 1.5rem; border-radius: var(--radius-sm); margin-top: auto;">
          <strong style="display: block; font-size: 0.95rem; color: var(--color-navy); margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.75px;">From Concept to Experience</strong>
          <p style="font-size: 0.9rem; line-height: 1.6; color: var(--color-muted); margin: 0;">
            A learner may study plants, observe one at home, record its growth, discuss observations and create a mini project. The aim is to show that learning exists beyond the screen and textbook.
          </p>
        </div>
      </div>
    </div>

    <!-- Key Outcomes -->
    <div class="curriculum-list-card" style="border-top-color: #2563EB; margin-bottom: 80px;">
      <h4 style="border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem; margin-bottom: 1.5rem;">Key Outcomes</h4>
      <div style="display: flex; flex-wrap: wrap; gap: 1rem 1.5rem;">
        <span style="background-color: var(--color-surface-blue); padding: 0.5rem 1.25rem; border-radius: 30px; font-size: 0.95rem; font-weight: 600; color: var(--color-navy);">Reading & Writing</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.5rem 1.25rem; border-radius: 30px; font-size: 0.95rem; font-weight: 600; color: var(--color-navy);">Numeracy</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.5rem 1.25rem; border-radius: 30px; font-size: 0.95rem; font-weight: 600; color: var(--color-navy);">Communication</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.5rem 1.25rem; border-radius: 30px; font-size: 0.95rem; font-weight: 600; color: var(--color-navy);">Problem-Solving</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.5rem 1.25rem; border-radius: 30px; font-size: 0.95rem; font-weight: 600; color: var(--color-navy);">Confidence</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.5rem 1.25rem; border-radius: 30px; font-size: 0.95rem; font-weight: 600; color: var(--color-navy);">Independent Thinking</span>
      </div>
    </div>

    <!-- Illustration: Question to Discovery -->
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

<!-- PAGE 4: 3. PREPARATORY STAGE -->
<section class="curriculum-stage-section">
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

    <div class="curriculum-info-grid">
      <!-- Core Subjects -->
      <div class="curriculum-list-card" style="border-top-color: var(--color-gold);">
        <h4>Core Subjects</h4>
        <ul class="curriculum-item-list">
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>English:</strong> <span>Strengthening grammar, interactive speaking, and contextual writing.</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>Mathematics:</strong> <span>Geometry baseline, fractions, and multiplication logic.</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>Science:</strong> <span>Scientific method, experimentation & environmental sciences study.</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>Social Studies:</strong> <span>Local administration structures, mapping, and early history.</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>Hindi / Second Language:</strong> <span>Literature appreciation, composition, and vocabulary expansion.</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>Digital Literacy:</strong> <span>Spreadsheets introductory concepts, design fundamentals, and online safety.</span></li>
        </ul>
      </div>

      <!-- Future-Ready Learning -->
      <div class="curriculum-list-card" style="border-top-color: var(--color-teal);">
        <h4>Future-Ready Learning</h4>
        <ul class="curriculum-item-list" style="gap: 1.5rem;">
          <li>
            <div>
              <strong>Coding & Computational Thinking:</strong>
              <div style="color: var(--color-muted); font-size: 0.95rem; margin-top: 0.25rem;">Logical thinking and an introduction to how technology works.</div>
            </div>
          </li>
          <li>
            <div>
              <strong>Communication & Public Speaking:</strong>
              <div style="color: var(--color-muted); font-size: 0.95rem; margin-top: 0.25rem;">Expressing ideas clearly and confidently.</div>
            </div>
          </li>
          <li>
            <div>
              <strong>Financial Awareness:</strong>
              <div style="color: var(--color-muted); font-size: 0.95rem; margin-top: 0.25rem;">Money, saving, spending and responsible choices.</div>
            </div>
          </li>
          <li>
            <div>
              <strong>Creative Arts:</strong>
              <div style="color: var(--color-muted); font-size: 0.95rem; margin-top: 0.25rem;">Imagination, design and self-expression.</div>
            </div>
          </li>
          <li>
            <div>
              <strong>Life Skills:</strong>
              <div style="color: var(--color-muted); font-size: 0.95rem; margin-top: 0.25rem;">Decision-making, responsibility and independence.</div>
            </div>
          </li>
          <li>
            <div>
              <strong>Health & Wellness:</strong>
              <div style="color: var(--color-muted); font-size: 0.95rem; margin-top: 0.25rem;">Healthy physical and emotional habits.</div>
            </div>
          </li>
          <li>
            <div>
              <strong>Environmental Awareness:</strong>
              <div style="color: var(--color-muted); font-size: 0.95rem; margin-top: 0.25rem;">Sustainability and responsibility towards the planet.</div>
            </div>
          </li>
        </ul>
      </div>
    </div>

    <!-- Key Outcomes -->
    <div class="curriculum-list-card" style="border-top-color: #2563EB; margin-bottom: 80px;">
      <h4 style="border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem; margin-bottom: 1.5rem;">Key Outcomes</h4>
      <div style="display: flex; flex-wrap: wrap; gap: 1rem 1.5rem;">
        <span style="background-color: var(--color-surface-blue); padding: 0.5rem 1.25rem; border-radius: 30px; font-size: 0.95rem; font-weight: 600; color: var(--color-navy);">Conceptual Understanding</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.5rem 1.25rem; border-radius: 30px; font-size: 0.95rem; font-weight: 600; color: var(--color-navy);">Research Skills</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.5rem 1.25rem; border-radius: 30px; font-size: 0.95rem; font-weight: 600; color: var(--color-navy);">Collaboration</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.5rem 1.25rem; border-radius: 30px; font-size: 0.95rem; font-weight: 600; color: var(--color-navy);">Creativity</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.5rem 1.25rem; border-radius: 30px; font-size: 0.95rem; font-weight: 600; color: var(--color-navy);">Digital Fluency</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.5rem 1.25rem; border-radius: 30px; font-size: 0.95rem; font-weight: 600; color: var(--color-navy);">Problem-Solving</span>
      </div>
    </div>

    <!-- Illustration: The Learning Lab -->
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

<!-- PAGE 5: 4. MIDDLE SCHOOL -->
<section class="curriculum-stage-section">
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

    <div class="curriculum-info-grid">
      <!-- Core Subjects -->
      <div class="curriculum-list-card" style="border-top-color: var(--color-gold);">
        <h4>Core Subjects</h4>
        <ul class="curriculum-item-list">
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>English:</strong> <span>Analytical essay writing, literature study, and high-impact verbal expression.</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>Mathematics:</strong> <span>Algebra, equations, ratios, basic statistics, and data visualization.</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>Science:</strong> <span>Differentiated study across Physics, Chemistry, Biology, and project works.</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>Social Science:</strong> <span>History, Geography, and Civics exploring global and local systems.</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>Hindi / Second Language:</strong> <span>Advanced comprehension, writing portfolios, and literary analysis.</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>Digital & Technology Education:</strong> <span>Basics of hardware, software systems, computer networks, and file systems.</span></li>
        </ul>
      </div>

      <!-- Future Skills & Learning Experiences -->
      <div class="curriculum-list-card" style="border-top-color: var(--color-purple); display: flex; flex-direction: column; justify-content: space-between;">
        <div>
          <h4>Future Skills</h4>
          <div class="grid-2" style="gap: 1rem; margin-bottom: 2rem;">
            <div class="t-card-text"><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Coding & AI Awareness</div>
            <div class="t-card-text"><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Entrepreneurship</div>
            <div class="t-card-text"><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Financial Literacy</div>
            <div class="t-card-text"><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Research & Critical Thinking</div>
            <div class="t-card-text"><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Public Speaking & Comm.</div>
            <div class="t-card-text"><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Design Thinking</div>
            <div class="t-card-text"><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Leadership</div>
            <div class="t-card-text"><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Global Citizenship</div>
            <div class="t-card-text" style="grid-column: span 2;"><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Career & Interest Exploration</div>
          </div>
        </div>

        <div style="border-top: 1px solid var(--color-border); padding-top: 1.5rem; margin-top: auto;">
          <h5 style="font-size: 0.95rem; color: var(--color-navy); margin-bottom: 0.75rem; font-family: var(--font-secondary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Learning Experiences</h5>
          <p style="font-size: 0.9rem; color: var(--color-muted); line-height: 1.6; margin: 0;">
            Case Studies • Research Projects • Debates • Experiments • Collaborative Assignments • Presentations • Innovation Challenges • Real-World Problem Solving
          </p>
        </div>
      </div>
    </div>

    <!-- Key Outcomes -->
    <div class="curriculum-list-card" style="border-top-color: #2563EB; margin-bottom: 80px;">
      <h4 style="border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem; margin-bottom: 1.5rem;">Key Outcomes</h4>
      <div style="display: flex; flex-wrap: wrap; gap: 1rem 1.5rem;">
        <span style="background-color: var(--color-surface-blue); padding: 0.5rem 1.25rem; border-radius: 30px; font-size: 0.95rem; font-weight: 600; color: var(--color-navy);">Critical Thinking</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.5rem 1.25rem; border-radius: 30px; font-size: 0.95rem; font-weight: 600; color: var(--color-navy);">Academic Independence</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.5rem 1.25rem; border-radius: 30px; font-size: 0.95rem; font-weight: 600; color: var(--color-navy);">Leadership</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.5rem 1.25rem; border-radius: 30px; font-size: 0.95rem; font-weight: 600; color: var(--color-navy);">Digital Fluency</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.5rem 1.25rem; border-radius: 30px; font-size: 0.95rem; font-weight: 600; color: var(--color-navy);">Communication</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.5rem 1.25rem; border-radius: 30px; font-size: 0.95rem; font-weight: 600; color: var(--color-navy);">Innovation</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.5rem 1.25rem; border-radius: 30px; font-size: 0.95rem; font-weight: 600; color: var(--color-navy);">Real-World Readiness</span>
      </div>
    </div>

    <!-- Illustration: Future Skills in Action -->
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

<!-- PAGE 6: 5. THE ZUVIO LEARNING FRAMEWORK -->
<section class="curriculum-stage-section" style="background-color: var(--color-surface-warm);">
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

    <!-- The 5 Dimensions -->
    <div class="grid-5" style="margin-bottom: 80px;">
      
      <div class="curriculum-list-card" style="border-top-color: #059669; padding: 2rem 1.5rem;">
        <h4 style="font-size: 1.25rem; color: #059669; border: none; padding: 0; margin-bottom: 0.5rem; text-transform: uppercase;">KNOW</h4>
        <strong style="font-size: 0.95rem; display: block; margin-bottom: 0.75rem; color: var(--color-navy);">Build the Foundation</strong>
        <p style="font-size: 0.85rem; color: var(--color-muted); line-height: 1.6; margin: 0;">Understand essential concepts, facts and ideas.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: var(--color-gold); padding: 2rem 1.5rem;">
        <h4 style="font-size: 1.25rem; color: var(--color-gold); border: none; padding: 0; margin-bottom: 0.5rem; text-transform: uppercase;">THINK</h4>
        <strong style="font-size: 0.95rem; display: block; margin-bottom: 0.75rem; color: var(--color-navy);">Develop Understanding</strong>
        <p style="font-size: 0.85rem; color: var(--color-muted); line-height: 1.6; margin: 0;">Question, analyse, compare, reason and solve.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: #2563EB; padding: 2rem 1.5rem;">
        <h4 style="font-size: 1.25rem; color: #2563EB; border: none; padding: 0; margin-bottom: 0.5rem; text-transform: uppercase;">CREATE</h4>
        <strong style="font-size: 0.95rem; display: block; margin-bottom: 0.75rem; color: var(--color-navy);">Turn Ideas Into Possibilities</strong>
        <p style="font-size: 0.85rem; color: var(--color-muted); line-height: 1.6; margin: 0;">Imagine, experiment, design and innovate.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: #7C3AED; padding: 2rem 1.5rem;">
        <h4 style="font-size: 1.25rem; color: #7C3AED; border: none; padding: 0; margin-bottom: 0.5rem; text-transform: uppercase;">CONNECT</h4>
        <strong style="font-size: 0.95rem; display: block; margin-bottom: 0.75rem; color: var(--color-navy);">Learn With the World</strong>
        <p style="font-size: 0.85rem; color: var(--color-muted); line-height: 1.6; margin: 0;">Communicate, collaborate and understand different perspectives.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: var(--color-teal); padding: 2rem 1.5rem;">
        <h4 style="font-size: 1.25rem; color: var(--color-teal); border: none; padding: 0; margin-bottom: 0.5rem; text-transform: uppercase;">APPLY</h4>
        <strong style="font-size: 0.95rem; display: block; margin-bottom: 0.75rem; color: var(--color-navy);">Make Learning Meaningful</strong>
        <p style="font-size: 0.85rem; color: var(--color-muted); line-height: 1.6; margin: 0;">Use knowledge confidently in projects, challenges and real-life situations.</p>
      </div>

    </div>

    <!-- Illustration: Framework Strip -->
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
      <div class="text-center" style="margin-top: 3rem; border-top: 1px solid var(--color-border); padding-top: 1.5rem;">
        <span style="font-family: var(--font-primary); font-size: 1.35rem; color: var(--color-navy); font-weight: 700; letter-spacing: 1px;">
          Knowledge &rarr; Understanding &rarr; Application &rarr; Innovation
        </span>
      </div>
    </div>

  </div>
</section>

<!-- PAGE 7: 6. LEARNING BEYOND THE TEXTBOOK & 7. ASSESSMENT -->
<section class="curriculum-stage-section">
  <div class="curriculum-container">
    
    <!-- 6. Learning Beyond the Textbook -->
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Holistic Growth</span>
      <h2 class="t-section-title curriculum-stage-title">6. Learning Beyond the Textbook</h2>
      <p class="t-sub-title curriculum-stage-subtitle">Because the World Is the Real Classroom</p>
    </div>

    <div class="grid-3" style="gap: 28px; margin-bottom: 100px;">
      <div class="curriculum-list-card" style="border-top-color: var(--color-gold);">
        <strong style="color: var(--color-navy); font-size: 1.2rem; display: block; margin-bottom: 0.75rem;">Projects & Experiments</strong>
        <p style="font-size: 0.95rem; color: var(--color-muted); line-height: 1.6;">Learning by doing, observing, testing and discovering.</p>
      </div>
      <div class="curriculum-list-card" style="border-top-color: var(--color-teal);">
        <strong style="color: var(--color-navy); font-size: 1.2rem; display: block; margin-bottom: 0.75rem;">Technology & Digital Learning</strong>
        <p style="font-size: 0.95rem; color: var(--color-muted); line-height: 1.6;">Using technology creatively, effectively and responsibly.</p>
      </div>
      <div class="curriculum-list-card" style="border-top-color: #2563EB;">
        <strong style="color: var(--color-navy); font-size: 1.2rem; display: block; margin-bottom: 0.75rem;">Communication & Collaboration</strong>
        <p style="font-size: 0.95rem; color: var(--color-muted); line-height: 1.6;">Presentations, discussions, teamwork and global interactions.</p>
      </div>
      <div class="curriculum-list-card" style="border-top-color: #7C3AED;">
        <strong style="color: var(--color-navy); font-size: 1.2rem; display: block; margin-bottom: 0.75rem;">Life Skills</strong>
        <p style="font-size: 0.95rem; color: var(--color-muted); line-height: 1.6;">Decision-making, independence, financial awareness and emotional intelligence.</p>
      </div>
      <div class="curriculum-list-card" style="border-top-color: #059669;">
        <strong style="color: var(--color-navy); font-size: 1.2rem; display: block; margin-bottom: 0.75rem;">Creativity & Innovation</strong>
        <p style="font-size: 0.95rem; color: var(--color-muted); line-height: 1.6;">Art, coding, design thinking and problem-solving challenges.</p>
      </div>
      <div class="curriculum-list-card" style="border-top-color: var(--color-navy);">
        <strong style="color: var(--color-navy); font-size: 1.2rem; display: block; margin-bottom: 0.75rem;">Global Exposure</strong>
        <p style="font-size: 0.95rem; color: var(--color-muted); line-height: 1.6;">Cultures, perspectives and ideas beyond geographical boundaries.</p>
      </div>
    </div>

    <!-- 7. Assessment at Zuvio -->
    <div class="curriculum-stage-header text-center" style="margin-top: 100px;">
      <span class="curriculum-stage-title-number">Evaluation Process</span>
      <h2 class="t-section-title curriculum-stage-title">7. Assessment at Zuvio</h2>
      <p class="t-sub-title curriculum-stage-subtitle">Measure Growth, Not Just Marks</p>
    </div>

    <div style="max-width: 1000px; margin: 0 auto 40px auto; text-align: center;">
      <p class="t-body-large curriculum-desc-text">
        Assessment helps learners move forward rather than simply generating a score. It can include concept checks, quizzes, projects, assignments, presentations, portfolios, skill-based assessments and term assessments.
      </p>
      
      <div style="background-color: var(--color-surface-warm); border: 1.5px solid var(--color-border); border-radius: var(--radius-md); padding: 2rem; display: inline-block; max-width: 750px; text-align: left; box-shadow: var(--shadow-sm); margin-bottom: 60px;">
        <span style="font-weight: 700; color: var(--color-gold); display: block; margin-bottom: 0.75rem; text-transform: uppercase; font-size: 0.9rem; letter-spacing: 1px;">Parent Insight:</span>
        <p style="font-size: 0.95rem; line-height: 1.6; color: var(--color-text); margin: 0; font-style: italic;">
          "Parents receive meaningful insights into academic progress, skills development, strengths and areas where additional support may be useful."
        </p>
      </div>
    </div>

    <!-- Illustration: Assessment for Growth -->
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

<!-- PAGE 8: 8. PERSONALISED LEARNING & 9. ZUVIO BEYOND -->
<section class="curriculum-stage-section" style="background-color: var(--color-surface-blue);">
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

    <!-- Illustration: Personalised Growth -->
    <div class="illustration-container" style="background-color: var(--color-white); margin-bottom: 100px;">
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
    <div class="curriculum-stage-header text-center" style="margin-top: 100px;">
      <span class="curriculum-stage-title-number">Co-Curricular Exploration</span>
      <h2 class="t-section-title curriculum-stage-title">9. Zuvio Beyond</h2>
      <p class="t-sub-title curriculum-stage-subtitle">Learning That Goes Beyond Academics</p>
    </div>

    <div style="max-width: 1000px; margin: 0 auto 4rem auto;">
      <p class="t-body-large text-center curriculum-desc-text">
        Through Zuvio Beyond, learners can access co-curricular activities, specialised programmes, enrichment opportunities and additional academic support according to their interests and learning needs.
      </p>
    </div>

    <!-- Categories Grid -->
    <div class="grid-4" style="gap: 24px; margin-bottom: 80px;">
      <div class="curriculum-list-card" style="padding: 2rem 1.5rem; text-align: center; border-top-color: #059669;">
        <h5 style="color: var(--color-navy); font-size: 1.25rem; margin: 0; font-family: var(--font-primary); font-weight: 700;">Creative Arts</h5>
      </div>
      <div class="curriculum-list-card" style="padding: 2rem 1.5rem; text-align: center; border-top-color: var(--color-gold);">
        <h5 style="color: var(--color-navy); font-size: 1.25rem; margin: 0; font-family: var(--font-primary); font-weight: 700;">Communication</h5>
      </div>
      <div class="curriculum-list-card" style="padding: 2rem 1.5rem; text-align: center; border-top-color: #2563EB;">
        <h5 style="color: var(--color-navy); font-size: 1.25rem; margin: 0; font-family: var(--font-primary); font-weight: 700;">Thinking Skills</h5>
      </div>
      <div class="curriculum-list-card" style="padding: 2rem 1.5rem; text-align: center; border-top-color: #7C3AED;">
        <h5 style="color: var(--color-navy); font-size: 1.25rem; margin: 0; font-family: var(--font-primary); font-weight: 700;">Technology</h5>
      </div>
      <div class="curriculum-list-card" style="padding: 2rem 1.5rem; text-align: center; border-top-color: var(--color-teal);">
        <h5 style="color: var(--color-navy); font-size: 1.25rem; margin: 0; font-family: var(--font-primary); font-weight: 700;">Wellness</h5>
      </div>
      <div class="curriculum-list-card" style="padding: 2rem 1.5rem; text-align: center; border-top-color: var(--color-navy);">
        <h5 style="color: var(--color-navy); font-size: 1.25rem; margin: 0; font-family: var(--font-primary); font-weight: 700;">Global Experiences</h5>
      </div>
      <div class="curriculum-list-card" style="padding: 2rem 1.5rem; text-align: center; border-top-color: var(--color-gold);">
        <h5 style="color: var(--color-navy); font-size: 1.25rem; margin: 0; font-family: var(--font-primary); font-weight: 700;">Academic Support</h5>
      </div>
      <div class="curriculum-list-card" style="padding: 2rem 1.5rem; text-align: center; border-top-color: #059669;">
        <h5 style="color: var(--color-navy); font-size: 1.25rem; margin: 0; font-family: var(--font-primary); font-weight: 700;">Enrichment</h5>
      </div>
    </div>

    <!-- Illustration: Zuvio Beyond -->
    <div class="illustration-container" style="background-color: var(--color-white);">
      <div class="illustration-title">ILLUSTRATION: ZUVIO BEYOND</div>
      <div class="grid-6" style="gap: 20px;">
        <div class="visual-strip-block bg-green" style="padding: 1.5rem 0.5rem; font-size: 0.95rem;">Create</div>
        <div class="visual-strip-block bg-orange" style="padding: 1.5rem 0.5rem; font-size: 0.95rem;">Communicate</div>
        <div class="visual-strip-block bg-blue" style="padding: 1.5rem 0.5rem; font-size: 0.95rem;">Think</div>
        <div class="visual-strip-block bg-purple" style="padding: 1.5rem 0.5rem; font-size: 0.95rem;">Tech</div>
        <div class="visual-strip-block bg-teal" style="padding: 1.5rem 0.5rem; font-size: 0.95rem;">Wellness</div>
        <div class="visual-strip-block bg-navy" style="padding: 1.5rem 0.5rem; font-size: 0.95rem;">Global</div>
      </div>
    </div>

  </div>
</section>

<!-- PAGE 9: 10. THE ZUVIO GRADUATE -->
<section class="curriculum-stage-section">
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

    <!-- Attributes Grid -->
    <div class="grid-3" style="gap: 28px; margin-bottom: 80px; align-items: stretch;">
      
      <div class="curriculum-list-card" style="border-top-color: var(--color-gold);">
        <strong style="color: var(--color-navy); font-size: 1.25rem; display: block; margin-bottom: 0.5rem;">Academically Strong</strong>
        <p style="font-size: 0.95rem; color: var(--color-muted); line-height: 1.6; margin: 0;">Understands and applies core academic concepts.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: var(--color-teal);">
        <strong style="color: var(--color-navy); font-size: 1.25rem; display: block; margin-bottom: 0.5rem;">Curious</strong>
        <p style="font-size: 0.95rem; color: var(--color-muted); line-height: 1.6; margin: 0;">Questions, explores and seeks deeper understanding.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: #2563EB;">
        <strong style="color: var(--color-navy); font-size: 1.25rem; display: block; margin-bottom: 0.5rem;">Confident & Articulate</strong>
        <p style="font-size: 0.95rem; color: var(--color-muted); line-height: 1.6; margin: 0;">Communicates ideas clearly and effectively.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: #7C3AED;">
        <strong style="color: var(--color-navy); font-size: 1.25rem; display: block; margin-bottom: 0.5rem;">Creative</strong>
        <p style="font-size: 0.95rem; color: var(--color-muted); line-height: 1.6; margin: 0;">Imagines, designs and builds new possibilities.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: #059669;">
        <strong style="color: var(--color-navy); font-size: 1.25rem; display: block; margin-bottom: 0.5rem;">Digitally Fluent</strong>
        <p style="font-size: 0.95rem; color: var(--color-muted); line-height: 1.6; margin: 0;">Navigates a technology-driven world responsibly.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: var(--color-navy);">
        <strong style="color: var(--color-navy); font-size: 1.25rem; display: block; margin-bottom: 0.5rem;">Collaborative</strong>
        <p style="font-size: 0.95rem; color: var(--color-muted); line-height: 1.6; margin: 0;">Works respectfully and productively with others.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: var(--color-gold);">
        <strong style="color: var(--color-navy); font-size: 1.25rem; display: block; margin-bottom: 0.5rem;">Independent</strong>
        <p style="font-size: 0.95rem; color: var(--color-muted); line-height: 1.6; margin: 0;">Takes increasing ownership of learning.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: var(--color-teal);">
        <strong style="color: var(--color-navy); font-size: 1.25rem; display: block; margin-bottom: 0.5rem;">Globally Aware</strong>
        <p style="font-size: 0.95rem; color: var(--color-muted); line-height: 1.6; margin: 0;">Values cultures, perspectives and global issues.</p>
      </div>

      <div class="curriculum-list-card" style="border-top-color: #2563EB;">
        <strong style="color: var(--color-navy); font-size: 1.25rem; display: block; margin-bottom: 0.5rem;">Future-Ready</strong>
        <p style="font-size: 0.95rem; color: var(--color-muted); line-height: 1.6; margin: 0;">Prepared to learn, adapt and grow in a changing world.</p>
      </div>

    </div>

    <!-- Illustration: The Zuvio Graduate -->
    <div class="illustration-container" style="background-color: var(--color-surface-blue); margin-bottom: 80px;">
      <div class="illustration-title">ILLUSTRATION: THE ZUVIO GRADUATE</div>
      <div class="grid-6" style="gap: 20px;">
        <div class="visual-strip-block bg-green" style="padding: 1.5rem 0.5rem; font-size: 0.95rem;">Academic</div>
        <div class="visual-strip-block bg-orange" style="padding: 1.5rem 0.5rem; font-size: 0.95rem;">Curious</div>
        <div class="visual-strip-block bg-blue" style="padding: 1.5rem 0.5rem; font-size: 0.95rem;">Confident</div>
        <div class="visual-strip-block bg-purple" style="padding: 1.5rem 0.5rem; font-size: 0.95rem;">Creative</div>
        <div class="visual-strip-block bg-teal" style="padding: 1.5rem 0.5rem; font-size: 0.95rem;">Digital</div>
        <div class="visual-strip-block bg-navy" style="padding: 1.5rem 0.5rem; font-size: 0.95rem;">Global</div>
      </div>
      
      <div class="text-center" style="margin-top: 3.5rem; border-top: 1px solid var(--color-border); padding-top: 2rem;">
        <span style="font-size: 1.05rem; font-weight: 700; color: var(--color-navy); letter-spacing: 2px; display: block; margin-bottom: 1.5rem; line-height: 1.6;">
          CURIOUS • CONFIDENT • CREATIVE • ARTICULATE • INDEPENDENT • COLLABORATIVE • DIGITALLY FLUENT • GLOBALLY AWARE
        </span>
        <h4 style="font-family: var(--font-primary); font-size: 1.85rem; color: var(--color-navy); font-weight: 700; margin: 0; line-height: 1.3;">
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
    <button onclick="openCallbackModal()" class="btn btn-primary" style="padding: 1.15rem 3.5rem; font-size: 1.1rem; border-radius: var(--radius-sm);">Request Callback</button>
  </div>
</section>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
