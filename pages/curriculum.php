<?php
// Zuvio Global School - Our Curriculum Page Template (9-Page Curriculum Realignment)
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

$page_slug = 'our-curriculum';
include_once dirname(__FILE__) . '/../includes/header.php';
?>

<!-- PAGE 1: CURRICULUM INSIGHTS HERO -->
<section class="curriculum-hero" style="background-image: linear-gradient(rgba(0, 10, 66, 0.85), rgba(0, 10, 66, 0.9)), url('/assets/images/Students learning in classroom.png');">
  <div class="container" style="max-width: 900px;">
    <span style="font-size: 0.9rem; font-weight: 600; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2.5px; display: block; margin-bottom: 0.75rem;">Zuvio Global School</span>
    <h1 style="font-size: 3.5rem; font-family: var(--font-primary); margin-bottom: 1.5rem; color: #FFFFFF; line-height: 1.2;">CURRICULUM INSIGHTS</h1>
    <p style="font-size: 1.25rem; font-weight: 300; line-height: 1.7; color: #E2E8F0; margin-bottom: 2rem;">
      A Future-Ready Learning Journey | Nursery to Grade 8
    </p>
    <div style="width: 60px; height: 3px; background-color: var(--color-gold); margin: 0 auto 2.5rem auto;"></div>
  </div>
</section>

<!-- PAGE 1 INTRO SECTION -->
<section class="section" style="background-color: var(--color-white); border-bottom: 1px solid var(--color-border);">
  <div class="container" style="max-width: 1000px;">
    <div class="text-center" style="margin-bottom: 4rem;">
      <p style="font-size: 1.2rem; line-height: 1.8; color: var(--color-text); max-width: 800px; margin: 0 auto 1.5rem auto; font-weight: 500;">
        At Zuvio Global School, learning is designed to grow with every child. From stories, sounds, numbers and discovery in the Early Years to research, innovation, technology and independent thinking in Middle School, every stage builds upon the previous one.
      </p>
      <p style="font-size: 1.05rem; line-height: 1.8; color: var(--color-muted); max-width: 800px; margin: 0 auto;">
        Our curriculum is designed in alignment with CBSE, NEP 2020 and NCF principles, combining strong academic foundations with creativity, communication, digital fluency, life skills and real-world learning.
      </p>
    </div>

    <!-- THE ZUVIO LEARNING JOURNEY ILLUSTRATION -->
    <div class="illustration-container" style="margin-top: 2rem;">
      <div class="illustration-title">THE ZUVIO LEARNING JOURNEY</div>
      <div class="grid-4">
        
        <div style="background-color: #ECFDF5; border: 1px solid #A7F3D0; border-radius: var(--radius-md); padding: 2rem 1.5rem; text-align: center; box-shadow: var(--shadow-sm);">
          <span style="font-size: 0.8rem; font-weight: 700; color: #059669; display: block; margin-bottom: 0.5rem; letter-spacing: 1px;">NURSERY - KG</span>
          <h4 style="font-size: 1.5rem; color: #062B63; font-family: var(--font-primary); font-weight: 700; margin-bottom: 0.5rem;">EXPLORE</h4>
          <div style="width: 25px; height: 2px; background-color: #059669; margin: 0.75rem auto;"></div>
        </div>

        <div style="background-color: #FFFBEB; border: 1px solid #FDE68A; border-radius: var(--radius-md); padding: 2rem 1.5rem; text-align: center; box-shadow: var(--shadow-sm);">
          <span style="font-size: 0.8rem; font-weight: 700; color: #D97706; display: block; margin-bottom: 0.5rem; letter-spacing: 1px;">GRADES 1 - 2</span>
          <h4 style="font-size: 1.5rem; color: #062B63; font-family: var(--font-primary); font-weight: 700; margin-bottom: 0.5rem;">BUILD</h4>
          <div style="width: 25px; height: 2px; background-color: #D97706; margin: 0.75rem auto;"></div>
        </div>

        <div style="background-color: #EFF6FF; border: 1px solid #BFDBFE; border-radius: var(--radius-md); padding: 2rem 1.5rem; text-align: center; box-shadow: var(--shadow-sm);">
          <span style="font-size: 0.8rem; font-weight: 700; color: #2563EB; display: block; margin-bottom: 0.5rem; letter-spacing: 1px;">GRADES 3 - 5</span>
          <h4 style="font-size: 1.5rem; color: #062B63; font-family: var(--font-primary); font-weight: 700; margin-bottom: 0.5rem;">UNDERSTAND</h4>
          <div style="width: 25px; height: 2px; background-color: #2563EB; margin: 0.75rem auto;"></div>
        </div>

        <div style="background-color: #F5F3FF; border: 1px solid #DDD6FE; border-radius: var(--radius-md); padding: 2rem 1.5rem; text-align: center; box-shadow: var(--shadow-sm);">
          <span style="font-size: 0.8rem; font-weight: 700; color: #7C3AED; display: block; margin-bottom: 0.5rem; letter-spacing: 1px;">GRADES 6 - 8</span>
          <h4 style="font-size: 1.5rem; color: #062B63; font-family: var(--font-primary); font-weight: 700; margin-bottom: 0.5rem;">INNOVATE</h4>
          <div style="width: 25px; height: 2px; background-color: #7C3AED; margin: 0.75rem auto;"></div>
        </div>

      </div>
      <div class="text-center" style="margin-top: 2.5rem;">
        <p style="font-family: var(--font-primary); font-size: 1.25rem; font-weight: 700; color: var(--color-navy); font-style: italic;">
          "Strong Foundations. Future Skills. Learning Without Boundaries."
        </p>
      </div>
    </div>

  </div>
</section>

<!-- PAGE 2: 1. EARLY YEARS -->
<section class="curriculum-stage-section" id="early-years">
  <div class="container">
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Nursery - Kindergarten</span>
      <h2 class="curriculum-stage-title">1. Early Years</h2>
      <p class="curriculum-stage-subtitle">Explore • Play • Discover</p>
    </div>

    <p class="curriculum-desc-text text-center" style="max-width: 800px; margin-left: auto; margin-right: auto;">
      The Early Years are where curiosity begins. Our curriculum creates a joyful and nurturing online learning environment where children learn through stories, conversations, movement, music, exploration and play. Children are encouraged to observe, speak, listen, question, create and participate.
    </p>

    <div class="grid-3" style="margin-top: 3rem; align-items: stretch;">
      
      <!-- Core Learning Areas -->
      <div class="curriculum-list-card">
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
      <div class="curriculum-list-card">
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
      <div class="curriculum-list-card">
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
<section class="curriculum-stage-section" id="foundation-stage">
  <div class="container">
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Grades 1-2</span>
      <h2 class="curriculum-stage-title">2. Foundation Stage</h2>
      <p class="curriculum-stage-subtitle">Build • Question • Create</p>
    </div>

    <p class="curriculum-desc-text text-center" style="max-width: 800px; margin-left: auto; margin-right: auto;">
      Students strengthen reading, writing and mathematical foundations while learning to ask questions, communicate ideas and connect classroom concepts with everyday experiences.
    </p>

    <div class="curriculum-info-grid">
      <!-- Core Subjects -->
      <div class="curriculum-list-card">
        <h4>Core Subjects</h4>
        <ul class="curriculum-item-list">
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>English:</strong> <span>Foundational reading, comprehension & vocabulary building.</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>Mathematics:</strong> <span>Introduction to operations, sizes, and pattern recognition.</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>Environmental Studies:</strong> <span>Learning about community, nature, and self.</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>Hindi / Second Language:</strong> <span>Building primary conversational and writing skills.</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>Digital Literacy:</strong> <span>Navigating devices safely and typing baseline skills.</span></li>
        </ul>
      </div>

      <!-- Beyond Academics & Concept to Experience -->
      <div class="curriculum-list-card" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
          <h4>Beyond Academics</h4>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem 1rem; margin-bottom: 2rem;">
            <div><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Art & Creativity</div>
            <div><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Communication</div>
            <div><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Life Skills</div>
            <div><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Physical Wellness</div>
            <div><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Social-Emotional Learning</div>
            <div><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> General Awareness</div>
          </div>
        </div>
        
        <div style="background-color: var(--color-surface-warm); border-left: 3px solid var(--color-teal); padding: 1.25rem; border-radius: var(--radius-sm);">
          <strong style="display: block; font-size: 0.9rem; color: var(--color-navy); margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">From Concept to Experience</strong>
          <p style="font-size: 0.85rem; line-height: 1.5; color: var(--color-muted); margin: 0;">
            A learner may study plants, observe one at home, record its growth, discuss observations and create a mini project. The aim is to show that learning exists beyond the screen and textbook.
          </p>
        </div>
      </div>
    </div>

    <div class="grid-2" style="align-items: stretch; margin-bottom: 4rem;">
      <!-- Key Outcomes -->
      <div class="curriculum-list-card" style="grid-column: span 2; border-top: 4px solid var(--color-teal);">
        <h4 style="border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem; margin-bottom: 1.25rem;">Key Outcomes</h4>
        <div style="display: flex; flex-wrap: wrap; gap: 1rem 2rem;">
          <span style="background-color: var(--color-surface-blue); padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.9rem; font-weight: 500; color: var(--color-navy);">Reading & Writing</span>
          <span style="background-color: var(--color-surface-blue); padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.9rem; font-weight: 500; color: var(--color-navy);">Numeracy</span>
          <span style="background-color: var(--color-surface-blue); padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.9rem; font-weight: 500; color: var(--color-navy);">Communication</span>
          <span style="background-color: var(--color-surface-blue); padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.9rem; font-weight: 500; color: var(--color-navy);">Problem-Solving</span>
          <span style="background-color: var(--color-surface-blue); padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.9rem; font-weight: 500; color: var(--color-navy);">Confidence</span>
          <span style="background-color: var(--color-surface-blue); padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.9rem; font-weight: 500; color: var(--color-navy);">Independent Thinking</span>
        </div>
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
<section class="curriculum-stage-section" id="preparatory-stage">
  <div class="container">
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Grades 3-5</span>
      <h2 class="curriculum-stage-title">3. Preparatory Stage</h2>
      <p class="curriculum-stage-subtitle">Understand • Apply • Collaborate</p>
    </div>

    <p class="curriculum-desc-text text-center" style="max-width: 800px; margin-left: auto; margin-right: auto;">
      Learning becomes increasingly interdisciplinary and application-oriented. Students move beyond knowing concepts to understanding why they matter, how they work and where they can be used.
    </p>

    <div class="curriculum-info-grid">
      <!-- Core Subjects -->
      <div class="curriculum-list-card">
        <h4>Core Subjects</h4>
        <ul class="curriculum-item-list">
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>English:</strong> <span>Strengthening grammar, interactive speaking, and contextual writing.</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>Mathematics:</strong> <span>Geometry baseline, fractions, and multiplication logic.</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>Science:</strong> <span>Scientific method, experimentation & environmental sciences.</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>Social Studies:</strong> <span>Local administration structures, mapping, and early history.</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>Hindi / Second Language:</strong> <span>Literature appreciation, composition, and vocabulary expansion.</span></li>
          <li><span class="curriculum-bullet-check">&#10004;</span> <strong>Digital Literacy:</strong> <span>Spreadsheets introductory concepts, design fundamentals, and online security.</span></li>
        </ul>
      </div>

      <!-- Future-Ready Learning -->
      <div class="curriculum-list-card" style="border-top: 4px solid var(--color-gold);">
        <h4>Future-Ready Learning</h4>
        <ul class="curriculum-item-list" style="gap: 1.25rem;">
          <li>
            <div style="font-size: 0.9rem;">
              <strong>Coding & Computational Thinking:</strong>
              <div style="color: var(--color-muted); font-size: 0.85rem; margin-top: 0.15rem;">Logical thinking and an introduction to how technology works.</div>
            </div>
          </li>
          <li>
            <div style="font-size: 0.9rem;">
              <strong>Communication & Public Speaking:</strong>
              <div style="color: var(--color-muted); font-size: 0.85rem; margin-top: 0.15rem;">Expressing ideas clearly and confidently.</div>
            </div>
          </li>
          <li>
            <div style="font-size: 0.9rem;">
              <strong>Financial Awareness:</strong>
              <div style="color: var(--color-muted); font-size: 0.85rem; margin-top: 0.15rem;">Money, saving, spending and responsible choices.</div>
            </div>
          </li>
          <li>
            <div style="font-size: 0.9rem;">
              <strong>Creative Arts:</strong>
              <div style="color: var(--color-muted); font-size: 0.85rem; margin-top: 0.15rem;">Imagination, design and self-expression.</div>
            </div>
          </li>
          <li>
            <div style="font-size: 0.9rem;">
              <strong>Life Skills:</strong>
              <div style="color: var(--color-muted); font-size: 0.85rem; margin-top: 0.15rem;">Decision-making, responsibility and independence.</div>
            </div>
          </li>
          <li>
            <div style="font-size: 0.9rem;">
              <strong>Health & Wellness:</strong>
              <div style="color: var(--color-muted); font-size: 0.85rem; margin-top: 0.15rem;">Healthy physical and emotional habits.</div>
            </div>
          </li>
          <li>
            <div style="font-size: 0.9rem;">
              <strong>Environmental Awareness:</strong>
              <div style="color: var(--color-muted); font-size: 0.85rem; margin-top: 0.15rem;">Sustainability and responsibility towards the planet.</div>
            </div>
          </li>
        </ul>
      </div>
    </div>

    <!-- Key Outcomes -->
    <div style="background-color: var(--color-white); border-radius: var(--radius-md); box-shadow: var(--shadow-sm); padding: 2rem 2.5rem; border: 1px solid var(--color-border); margin-bottom: 4rem;">
      <h4 style="font-size: 1.2rem; color: var(--color-navy); margin-bottom: 1.25rem; font-family: var(--font-primary); border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">Key Outcomes</h4>
      <div style="display: flex; flex-wrap: wrap; gap: 1rem 1.5rem;">
        <span style="background-color: var(--color-surface-blue); padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.85rem; font-weight: 600; color: var(--color-navy);">Conceptual Understanding</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.85rem; font-weight: 600; color: var(--color-navy);">Research Skills</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.85rem; font-weight: 600; color: var(--color-navy);">Collaboration</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.85rem; font-weight: 600; color: var(--color-navy);">Creativity</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.85rem; font-weight: 600; color: var(--color-navy);">Digital Fluency</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.85rem; font-weight: 600; color: var(--color-navy);">Problem-Solving</span>
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
<section class="curriculum-stage-section" id="middle-school">
  <div class="container">
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Grades 6-8</span>
      <h2 class="curriculum-stage-title">4. Middle School</h2>
      <p class="curriculum-stage-subtitle">Think • Apply • Innovate</p>
    </div>

    <p class="curriculum-desc-text text-center" style="max-width: 800px; margin-left: auto; margin-right: auto;">
      Middle School marks the transition from guided learning towards greater academic independence and intellectual exploration. Students investigate ideas deeply, analyse information, participate in discussions, undertake research and apply knowledge to meaningful challenges.
    </p>

    <div class="curriculum-info-grid">
      <!-- Core Subjects -->
      <div class="curriculum-list-card">
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
      <div class="curriculum-list-card" style="border-top: 4px solid var(--color-purple); display: flex; flex-direction: column; justify-content: space-between;">
        <div>
          <h4>Future Skills</h4>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem 1rem; margin-bottom: 2rem;">
            <div><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Coding & AI Awareness</div>
            <div><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Entrepreneurship</div>
            <div><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Financial Literacy</div>
            <div><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Research & Critical Thinking</div>
            <div><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Public Speaking & Comm.</div>
            <div><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Design Thinking</div>
            <div><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Leadership</div>
            <div><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Global Citizenship</div>
            <div><span style="color: var(--color-gold); font-weight: bold; margin-right: 0.5rem;">•</span> Career & Interest Exploration</div>
          </div>
        </div>

        <div style="border-top: 1px solid var(--color-border); padding-top: 1.5rem;">
          <h5 style="font-size: 0.95rem; color: var(--color-navy); margin-bottom: 0.75rem; font-family: var(--font-secondary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Learning Experiences</h5>
          <p style="font-size: 0.85rem; color: var(--color-muted); line-height: 1.6; margin: 0;">
            Case Studies • Research Projects • Debates • Experiments • Collaborative Assignments • Presentations • Innovation Challenges • Real-World Problem Solving
          </p>
        </div>
      </div>
    </div>

    <!-- Key Outcomes -->
    <div style="background-color: var(--color-white); border-radius: var(--radius-md); box-shadow: var(--shadow-sm); padding: 2rem 2.5rem; border: 1px solid var(--color-border); margin-bottom: 4rem;">
      <h4 style="font-size: 1.2rem; color: var(--color-navy); margin-bottom: 1.25rem; font-family: var(--font-primary); border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">Key Outcomes</h4>
      <div style="display: flex; flex-wrap: wrap; gap: 1rem 1.5rem;">
        <span style="background-color: var(--color-surface-blue); padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.85rem; font-weight: 600; color: var(--color-navy);">Critical Thinking</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.85rem; font-weight: 600; color: var(--color-navy);">Academic Independence</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.85rem; font-weight: 600; color: var(--color-navy);">Leadership</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.85rem; font-weight: 600; color: var(--color-navy);">Digital Fluency</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.85rem; font-weight: 600; color: var(--color-navy);">Communication</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.85rem; font-weight: 600; color: var(--color-navy);">Innovation</span>
        <span style="background-color: var(--color-surface-blue); padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.85rem; font-weight: 600; color: var(--color-navy);">Real-World Readiness</span>
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
<section class="section" id="learning-framework" style="background-color: var(--color-surface-warm); border-bottom: 1px solid var(--color-border);">
  <div class="container" style="max-width: 1100px;">
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Core Philosophy</span>
      <h2 class="curriculum-stage-title">5. The Zuvio Learning Framework</h2>
      <p class="curriculum-stage-subtitle">From Knowing to Doing</p>
    </div>

    <p class="curriculum-desc-text text-center" style="max-width: 850px; margin: 0 auto 4rem auto;">
      Learning should not end when a child remembers an answer. Every Zuvio learning experience moves through five dimensions that progressively transform knowledge into confident application.
    </p>

    <!-- The 5 Dimensions -->
    <div class="grid-5" style="margin-bottom: 4rem;">
      
      <div class="curriculum-list-card" style="border-top: 4px solid #059669; padding: 2rem 1.5rem;">
        <h4 style="font-size: 1.15rem; color: #059669; border: none; padding: 0; margin-bottom: 0.5rem; text-transform: uppercase;">KNOW</h4>
        <strong style="font-size: 0.85rem; display: block; margin-bottom: 0.5rem; color: var(--color-navy);">Build the Foundation</strong>
        <p style="font-size: 0.8rem; color: var(--color-muted); line-height: 1.5; margin: 0;">Understand essential concepts, facts and ideas.</p>
      </div>

      <div class="curriculum-list-card" style="border-top: 4px solid var(--color-gold); padding: 2rem 1.5rem;">
        <h4 style="font-size: 1.15rem; color: var(--color-gold); border: none; padding: 0; margin-bottom: 0.5rem; text-transform: uppercase;">THINK</h4>
        <strong style="font-size: 0.85rem; display: block; margin-bottom: 0.5rem; color: var(--color-navy);">Develop Understanding</strong>
        <p style="font-size: 0.8rem; color: var(--color-muted); line-height: 1.5; margin: 0;">Question, analyse, compare, reason and solve.</p>
      </div>

      <div class="curriculum-list-card" style="border-top: 4px solid #2563EB; padding: 2rem 1.5rem;">
        <h4 style="font-size: 1.15rem; color: #2563EB; border: none; padding: 0; margin-bottom: 0.5rem; text-transform: uppercase;">CREATE</h4>
        <strong style="font-size: 0.85rem; display: block; margin-bottom: 0.5rem; color: var(--color-navy);">Turn Ideas Into Possibilities</strong>
        <p style="font-size: 0.8rem; color: var(--color-muted); line-height: 1.5; margin: 0;">Imagine, experiment, design and innovate.</p>
      </div>

      <div class="curriculum-list-card" style="border-top: 4px solid #7C3AED; padding: 2rem 1.5rem;">
        <h4 style="font-size: 1.15rem; color: #7C3AED; border: none; padding: 0; margin-bottom: 0.5rem; text-transform: uppercase;">CONNECT</h4>
        <strong style="font-size: 0.85rem; display: block; margin-bottom: 0.5rem; color: var(--color-navy);">Learn With the World</strong>
        <p style="font-size: 0.8rem; color: var(--color-muted); line-height: 1.5; margin: 0;">Communicate, collaborate and understand different perspectives.</p>
      </div>

      <div class="curriculum-list-card" style="border-top: 4px solid var(--color-teal); padding: 2rem 1.5rem;">
        <h4 style="font-size: 1.15rem; color: var(--color-teal); border: none; padding: 0; margin-bottom: 0.5rem; text-transform: uppercase;">APPLY</h4>
        <strong style="font-size: 0.85rem; display: block; margin-bottom: 0.5rem; color: var(--color-navy);">Make Learning Meaningful</strong>
        <p style="font-size: 0.8rem; color: var(--color-muted); line-height: 1.5; margin: 0;">Use knowledge confidently in projects, challenges and real-life situations.</p>
      </div>

    </div>

    <!-- Illustration: Framework Strip -->
    <div class="illustration-container" style="background-color: var(--color-white);">
      <div class="illustration-title">ILLUSTRATION: KNOW → THINK → CREATE → CONNECT → APPLY</div>
      <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;" class="visual-strip-row">
        <div class="visual-strip-block bg-green" style="font-size: 0.85rem; padding: 1.25rem 0.5rem;">KNOW</div>
        <div style="color: var(--color-gold); font-weight: bold; font-size: 1.5rem;" class="arrow-indicator">&#8594;</div>
        <div class="visual-strip-block bg-orange" style="font-size: 0.85rem; padding: 1.25rem 0.5rem;">THINK</div>
        <div style="color: var(--color-gold); font-weight: bold; font-size: 1.5rem;" class="arrow-indicator">&#8594;</div>
        <div class="visual-strip-block bg-blue" style="font-size: 0.85rem; padding: 1.25rem 0.5rem;">CREATE</div>
        <div style="color: var(--color-gold); font-weight: bold; font-size: 1.5rem;" class="arrow-indicator">&#8594;</div>
        <div class="visual-strip-block bg-purple" style="font-size: 0.85rem; padding: 1.25rem 0.5rem;">CONNECT</div>
        <div style="color: var(--color-gold); font-weight: bold; font-size: 1.5rem;" class="arrow-indicator">&#8594;</div>
        <div class="visual-strip-block bg-teal" style="font-size: 0.85rem; padding: 1.25rem 0.5rem;">APPLY</div>
      </div>
      <div class="text-center" style="margin-top: 2rem;">
        <span style="font-size: 1rem; color: var(--color-navy); font-weight: 700; letter-spacing: 1px;">
          Knowledge &rarr; Understanding &rarr; Application &rarr; Innovation
        </span>
      </div>
    </div>

  </div>
</section>

<!-- PAGE 7: 6. LEARNING BEYOND THE TEXTBOOK & 7. ASSESSMENT -->
<section class="section" id="learning-beyond" style="background-color: var(--color-white); border-bottom: 1px solid var(--color-border);">
  <div class="container">
    
    <!-- 6. Learning Beyond the Textbook -->
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Extended Learning</span>
      <h2 class="curriculum-stage-title">6. Learning Beyond the Textbook</h2>
      <p class="curriculum-stage-subtitle">Because the World Is the Real Classroom</p>
    </div>

    <div class="grid-3" style="margin-bottom: 6rem;">
      <div class="card" style="padding: 2rem; border-left-color: var(--color-gold);">
        <strong style="color: var(--color-navy); font-size: 1.1rem; display: block; margin-bottom: 0.5rem;">Projects & Experiments</strong>
        <p style="font-size: 0.85rem; color: var(--color-muted); line-height: 1.6;">Learning by doing, observing, testing and discovering.</p>
      </div>
      <div class="card" style="padding: 2rem; border-left-color: var(--color-teal);">
        <strong style="color: var(--color-navy); font-size: 1.1rem; display: block; margin-bottom: 0.5rem;">Technology & Digital Learning</strong>
        <p style="font-size: 0.85rem; color: var(--color-muted); line-height: 1.6;">Using technology creatively, effectively and responsibly.</p>
      </div>
      <div class="card" style="padding: 2rem; border-left-color: #2563EB;">
        <strong style="color: var(--color-navy); font-size: 1.1rem; display: block; margin-bottom: 0.5rem;">Communication & Collaboration</strong>
        <p style="font-size: 0.85rem; color: var(--color-muted); line-height: 1.6;">Presentations, discussions, teamwork and global interactions.</p>
      </div>
      <div class="card" style="padding: 2rem; border-left-color: #7C3AED;">
        <strong style="color: var(--color-navy); font-size: 1.1rem; display: block; margin-bottom: 0.5rem;">Life Skills</strong>
        <p style="font-size: 0.85rem; color: var(--color-muted); line-height: 1.6;">Decision-making, independence, financial awareness and emotional intelligence.</p>
      </div>
      <div class="card" style="padding: 2rem; border-left-color: #059669;">
        <strong style="color: var(--color-navy); font-size: 1.1rem; display: block; margin-bottom: 0.5rem;">Creativity & Innovation</strong>
        <p style="font-size: 0.85rem; color: var(--color-muted); line-height: 1.6;">Art, coding, design thinking and problem-solving challenges.</p>
      </div>
      <div class="card" style="padding: 2rem; border-left-color: var(--color-navy);">
        <strong style="color: var(--color-navy); font-size: 1.1rem; display: block; margin-bottom: 0.5rem;">Global Exposure</strong>
        <p style="font-size: 0.85rem; color: var(--color-muted); line-height: 1.6;">Cultures, perspectives and ideas beyond geographical boundaries.</p>
      </div>
    </div>

    <!-- 7. Assessment at Zuvio -->
    <div class="curriculum-stage-header text-center" id="assessments" style="margin-top: 4rem;">
      <span class="curriculum-stage-title-number">Tracking Progress</span>
      <h2 class="curriculum-stage-title">7. Assessment at Zuvio</h2>
      <p class="curriculum-stage-subtitle">Measure Growth, Not Just Marks</p>
    </div>

    <div style="max-width: 800px; margin: 0 auto 4rem auto;" class="text-center">
      <p class="curriculum-desc-text" style="margin-bottom: 2rem;">
        Assessment helps learners move forward rather than simply generating a score. It can include concept checks, quizzes, projects, assignments, presentations, portfolios, skill-based assessments and term assessments.
      </p>
      
      <div style="background-color: var(--color-surface-warm); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 1.5rem; display: inline-block; max-width: 600px; text-align: left; box-shadow: var(--shadow-sm);">
        <span style="font-weight: bold; color: var(--color-gold); display: block; margin-bottom: 0.5rem; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Parent Insight:</span>
        <p style="font-size: 0.85rem; line-height: 1.5; color: var(--color-text); margin: 0; font-style: italic;">
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
<section class="section" id="personalised-learning" style="background-color: var(--color-surface-blue); border-bottom: 1px solid var(--color-border);">
  <div class="container">
    
    <!-- 8. Personalised Learning -->
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Customized Pathways</span>
      <h2 class="curriculum-stage-title">8. Personalised Learning</h2>
      <p class="curriculum-stage-subtitle">Every Learner Has a Different Learning Journey</p>
    </div>

    <p class="curriculum-desc-text text-center" style="max-width: 800px; margin: 0 auto 4rem auto;">
      Children do not necessarily learn at the same speed or in exactly the same way. Zuvio brings together teacher guidance, digital learning tools, regular assessment, individual progress insights and targeted academic support to respond to each learner's progress and needs.
    </p>

    <!-- Illustration: Personalised Growth -->
    <div class="illustration-container" style="background-color: var(--color-white); margin-bottom: 6rem;">
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
    <div class="curriculum-stage-header text-center" id="zuvio-beyond" style="margin-top: 4rem;">
      <span class="curriculum-stage-title-number">Co-Curricular Program</span>
      <h2 class="curriculum-stage-title">9. Zuvio Beyond</h2>
      <p class="curriculum-stage-subtitle">Learning That Goes Beyond Academics</p>
    </div>

    <p class="curriculum-desc-text text-center" style="max-width: 800px; margin: 0 auto 4rem auto;">
      Through Zuvio Beyond, learners can access co-curricular activities, specialised programmes, enrichment opportunities and additional academic support according to their interests and learning needs.
    </p>

    <!-- Categories Grid -->
    <div class="grid-4" style="margin-bottom: 4rem;">
      <div class="curriculum-list-card" style="padding: 1.5rem; text-align: center; border-top: 3px solid #059669;">
        <h5 style="color: var(--color-navy); font-size: 1.1rem; margin: 0; font-family: var(--font-primary);">Creative Arts</h5>
      </div>
      <div class="curriculum-list-card" style="padding: 1.5rem; text-align: center; border-top: 3px solid var(--color-gold);">
        <h5 style="color: var(--color-navy); font-size: 1.1rem; margin: 0; font-family: var(--font-primary);">Communication</h5>
      </div>
      <div class="curriculum-list-card" style="padding: 1.5rem; text-align: center; border-top: 3px solid #2563EB;">
        <h5 style="color: var(--color-navy); font-size: 1.1rem; margin: 0; font-family: var(--font-primary);">Thinking Skills</h5>
      </div>
      <div class="curriculum-list-card" style="padding: 1.5rem; text-align: center; border-top: 3px solid #7C3AED;">
        <h5 style="color: var(--color-navy); font-size: 1.1rem; margin: 0; font-family: var(--font-primary);">Technology</h5>
      </div>
      <div class="curriculum-list-card" style="padding: 1.5rem; text-align: center; border-top: 3px solid var(--color-teal);">
        <h5 style="color: var(--color-navy); font-size: 1.1rem; margin: 0; font-family: var(--font-primary);">Wellness</h5>
      </div>
      <div class="curriculum-list-card" style="padding: 1.5rem; text-align: center; border-top: 3px solid var(--color-navy);">
        <h5 style="color: var(--color-navy); font-size: 1.1rem; margin: 0; font-family: var(--font-primary);">Global Experiences</h5>
      </div>
      <div class="curriculum-list-card" style="padding: 1.5rem; text-align: center; border-top: 3px solid var(--color-gold);">
        <h5 style="color: var(--color-navy); font-size: 1.1rem; margin: 0; font-family: var(--font-primary);">Academic Support</h5>
      </div>
      <div class="curriculum-list-card" style="padding: 1.5rem; text-align: center; border-top: 3px solid #059669;">
        <h5 style="color: var(--color-navy); font-size: 1.1rem; margin: 0; font-family: var(--font-primary);">Enrichment</h5>
      </div>
    </div>

    <!-- Illustration: Zuvio Beyond -->
    <div class="illustration-container" style="background-color: var(--color-white);">
      <div class="illustration-title">ILLUSTRATION: ZUVIO BEYOND</div>
      <div class="grid-6">
        <div class="visual-strip-block bg-green" style="padding: 1.25rem 0.5rem; font-size: 0.85rem;">Create</div>
        <div class="visual-strip-block bg-orange" style="padding: 1.25rem 0.5rem; font-size: 0.85rem;">Communicate</div>
        <div class="visual-strip-block bg-blue" style="padding: 1.25rem 0.5rem; font-size: 0.85rem;">Think</div>
        <div class="visual-strip-block bg-purple" style="padding: 1.25rem 0.5rem; font-size: 0.85rem;">Tech</div>
        <div class="visual-strip-block bg-teal" style="padding: 1.25rem 0.5rem; font-size: 0.85rem;">Wellness</div>
        <div class="visual-strip-block bg-navy" style="padding: 1.25rem 0.5rem; font-size: 0.85rem;">Global</div>
      </div>
    </div>

  </div>
</section>

<!-- PAGE 9: 10. THE ZUVIO GRADUATE -->
<section class="section" id="zuvio-graduate" style="background-color: var(--color-white); border-bottom: 1px solid var(--color-border);">
  <div class="container" style="max-width: 1000px;">
    
    <div class="curriculum-stage-header text-center">
      <span class="curriculum-stage-title-number">Graduate Profile</span>
      <h2 class="curriculum-stage-title">10. The Zuvio Graduate</h2>
      <p class="curriculum-stage-subtitle">Who Are We Preparing Our Learners to Become?</p>
    </div>

    <p class="curriculum-desc-text text-center" style="max-width: 850px; margin: 0 auto 4rem auto;">
      By the end of Grade 8, our goal is not simply to prepare students for the next academic grade. We aim to develop capable, confident learners who can understand, communicate, create, collaborate and adapt.
    </p>

    <!-- Attributes Grid -->
    <div class="grid-3" style="margin-bottom: 4rem; align-items: stretch;">
      
      <div class="curriculum-list-card" style="padding: 2rem; border-top-color: var(--color-gold);">
        <strong style="color: var(--color-navy); font-size: 1.15rem; display: block; margin-bottom: 0.5rem;">Academically Strong</strong>
        <p style="font-size: 0.85rem; color: var(--color-muted); line-height: 1.6; margin: 0;">Understands and applies core academic concepts.</p>
      </div>

      <div class="curriculum-list-card" style="padding: 2rem; border-top-color: var(--color-teal);">
        <strong style="color: var(--color-navy); font-size: 1.15rem; display: block; margin-bottom: 0.5rem;">Curious</strong>
        <p style="font-size: 0.85rem; color: var(--color-muted); line-height: 1.6; margin: 0;">Questions, explores and seeks deeper understanding.</p>
      </div>

      <div class="curriculum-list-card" style="padding: 2rem; border-top-color: #2563EB;">
        <strong style="color: var(--color-navy); font-size: 1.15rem; display: block; margin-bottom: 0.5rem;">Confident & Articulate</strong>
        <p style="font-size: 0.85rem; color: var(--color-muted); line-height: 1.6; margin: 0;">Communicates ideas clearly and effectively.</p>
      </div>

      <div class="curriculum-list-card" style="padding: 2rem; border-top-color: #7C3AED;">
        <strong style="color: var(--color-navy); font-size: 1.15rem; display: block; margin-bottom: 0.5rem;">Creative</strong>
        <p style="font-size: 0.85rem; color: var(--color-muted); line-height: 1.6; margin: 0;">Imagines, designs and builds new possibilities.</p>
      </div>

      <div class="curriculum-list-card" style="padding: 2rem; border-top-color: #059669;">
        <strong style="color: var(--color-navy); font-size: 1.15rem; display: block; margin-bottom: 0.5rem;">Digitally Fluent</strong>
        <p style="font-size: 0.85rem; color: var(--color-muted); line-height: 1.6; margin: 0;">Navigates a technology-driven world responsibly.</p>
      </div>

      <div class="curriculum-list-card" style="padding: 2rem; border-top-color: var(--color-navy);">
        <strong style="color: var(--color-navy); font-size: 1.15rem; display: block; margin-bottom: 0.5rem;">Collaborative</strong>
        <p style="font-size: 0.85rem; color: var(--color-muted); line-height: 1.6; margin: 0;">Works respectfully and productively with others.</p>
      </div>

      <div class="curriculum-list-card" style="padding: 2rem; border-top-color: var(--color-gold);">
        <strong style="color: var(--color-navy); font-size: 1.15rem; display: block; margin-bottom: 0.5rem;">Independent</strong>
        <p style="font-size: 0.85rem; color: var(--color-muted); line-height: 1.6; margin: 0;">Takes increasing ownership of learning.</p>
      </div>

      <div class="curriculum-list-card" style="padding: 2rem; border-top-color: var(--color-teal);">
        <strong style="color: var(--color-navy); font-size: 1.15rem; display: block; margin-bottom: 0.5rem;">Globally Aware</strong>
        <p style="font-size: 0.85rem; color: var(--color-muted); line-height: 1.6; margin: 0;">Values cultures, perspectives and global issues.</p>
      </div>

      <div class="curriculum-list-card" style="padding: 2rem; border-top-color: #2563EB;">
        <strong style="color: var(--color-navy); font-size: 1.15rem; display: block; margin-bottom: 0.5rem;">Future-Ready</strong>
        <p style="font-size: 0.85rem; color: var(--color-muted); line-height: 1.6; margin: 0;">Prepared to learn, adapt and grow in a changing world.</p>
      </div>

    </div>

    <!-- Illustration: The Zuvio Graduate -->
    <div class="illustration-container" style="background-color: var(--color-surface-blue); margin-bottom: 4rem;">
      <div class="illustration-title">ILLUSTRATION: THE ZUVIO GRADUATE</div>
      <div class="grid-6">
        <div class="visual-strip-block bg-green" style="padding: 1.25rem 0.5rem; font-size: 0.85rem;">Academic</div>
        <div class="visual-strip-block bg-orange" style="padding: 1.25rem 0.5rem; font-size: 0.85rem;">Curious</div>
        <div class="visual-strip-block bg-blue" style="padding: 1.25rem 0.5rem; font-size: 0.85rem;">Confident</div>
        <div class="visual-strip-block bg-purple" style="padding: 1.25rem 0.5rem; font-size: 0.85rem;">Creative</div>
        <div class="visual-strip-block bg-teal" style="padding: 1.25rem 0.5rem; font-size: 0.85rem;">Digital</div>
        <div class="visual-strip-block bg-navy" style="padding: 1.25rem 0.5rem; font-size: 0.85rem;">Global</div>
      </div>
      <div class="text-center" style="margin-top: 2.5rem; border-top: 1px solid var(--color-border); padding-top: 1.5rem;">
        <span style="font-size: 0.9rem; font-weight: 700; color: var(--color-navy); letter-spacing: 1.5px; display: block; margin-bottom: 1rem;">
          CURIOUS • CONFIDENT • CREATIVE • ARTICULATE • INDEPENDENT • COLLABORATIVE • DIGITALLY FLUENT • GLOBALLY AWARE
        </span>
        <h4 style="font-family: var(--font-primary); font-size: 1.6rem; color: var(--color-navy); font-weight: 700; margin-top: 1rem;">
          Strong Foundations. Future Skills. Learning Without Boundaries.
        </h4>
      </div>
    </div>

  </div>
</section>

<!-- CALL TO ACTION / FAQ SECTION -->
<section class="section text-center" style="background-color: var(--color-navy-dark); color: #FFFFFF; padding: 6rem 2rem;">
  <div class="container" style="max-width: 700px;">
    <h2 style="font-size: 2.5rem; color: #FFFFFF; margin-bottom: 1.25rem; font-family: var(--font-primary);">Map Your Child's Academic Journey</h2>
    <p style="color: #E2E8F0; font-size: 1.1rem; margin-bottom: 2.5rem; line-height: 1.7;">
      Get in touch with our admissions coordinators to verify grade availability and structure personalized study paths.
    </p>
    <button onclick="openCallbackModal()" class="btn btn-primary" style="padding: 1rem 3rem; font-size: 1.05rem;">Request Callback</button>
  </div>
</section>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
