<?php
// Zuvio Global School - Our Curriculum Page Template (Accurate Visual adaptation of PDF)
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

$page_slug = 'our-curriculum';
include_once dirname(__FILE__) . '/../includes/header.php';
?>

<div class="curriculum-page">

  <!-- PAGE 1 — HERO & THE ZUVIO LEARNING JOURNEY (White Background) -->
  <section class="curriculum-section bg-white" style="padding-top: 110px;">
    <div class="curriculum-container">
      <div class="curriculum-section-header" style="text-align: center;">
        <h1 class="curriculum-hero-title">CURRICULUM INSIGHTS</h1>
        <p class="curriculum-section-subtitle">A Future-Ready Learning Journey | Nursery to Grade 8</p>
      </div>

      <div class="curriculum-body">
        <p style="margin-bottom: 16px;">
          At <strong>Zuvio Global School</strong>, learning is designed to grow with every child. From stories, sounds, numbers and discovery in the Early Years to research, innovation, technology and independent thinking in Middle School, every stage builds upon the previous one.
        </p>
        <p style="margin: 0;">
          Our curriculum is <strong>designed in alignment with CBSE, NEP 2020 and NCF principles</strong>, combining strong academic foundations with creativity, communication, digital fluency, life skills and real-world learning.
        </p>
      </div>

      <!-- THE ZUVIO LEARNING JOURNEY illustration strip -->
      <div class="curriculum-process-box">
        <div class="curriculum-process-title">THE ZUVIO LEARNING JOURNEY</div>
        <div class="curriculum-process-strip curriculum-grid-4">
          <div class="curriculum-process-block bg-green" style="flex-direction: column; gap: 4px;">
            <span style="font-size: 11px; font-weight: normal; opacity: 0.95;">Nursery-KG</span>
            <span>Explore</span>
          </div>
          <div class="curriculum-process-block bg-orange" style="flex-direction: column; gap: 4px;">
            <span style="font-size: 11px; font-weight: normal; opacity: 0.95;">Grades 1-2</span>
            <span>Build</span>
          </div>
          <div class="curriculum-process-block bg-blue" style="flex-direction: column; gap: 4px;">
            <span style="font-size: 11px; font-weight: normal; opacity: 0.95;">Grades 3-5</span>
            <span>Understand</span>
          </div>
          <div class="curriculum-process-block bg-purple" style="flex-direction: column; gap: 4px;">
            <span style="font-size: 11px; font-weight: normal; opacity: 0.95;">Grades 6-8</span>
            <span>Innovate</span>
          </div>
        </div>
      </div>

      <div style="text-align: center; margin-top: 32px;">
        <p style="font-size: 19px; font-weight: 700; color: var(--color-navy-dark); font-family: var(--font-primary); margin: 0;">
          Strong Foundations. Future Skills. Learning Without Boundaries.
        </p>
      </div>
    </div>
  </section>

  <!-- PAGE 2 — 1. EARLY YEARS (Soft Blue Background) -->
  <section class="curriculum-section bg-soft-blue">
    <div class="curriculum-container">
      <div class="curriculum-section-header">
        <h2 class="curriculum-section-title">1. Early Years</h2>
        <p class="curriculum-section-subtitle">Nursery - Kindergarten | Explore • Play • Discover</p>
      </div>

      <div class="curriculum-body">
        <p style="margin: 0;">
          The Early Years are where curiosity begins. Our curriculum creates a joyful and nurturing online learning environment where children learn through stories, conversations, movement, music, exploration and play. Children are encouraged to observe, speak, listen, question, create and participate.
        </p>
      </div>

      <!-- Core Learning Areas Grid -->
      <div style="margin-bottom: 28px;">
        <h3 class="curriculum-grid-title" style="margin-bottom: 16px; text-transform: uppercase;">Core Learning Areas</h3>
        <div class="curriculum-grid curriculum-grid-3">
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Early Literacy & Phonics</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Early Numeracy</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Environmental Awareness</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Communication & Vocabulary</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Creative Expression</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Motor & Sensory Development</span></div>
          <div class="curriculum-grid-cell" style="grid-column: span 1;"><span class="curriculum-grid-title" style="margin:0;">Social & Emotional Learning</span></div>
        </div>
      </div>

      <!-- Learning Experiences & Key Outcomes -->
      <div class="curriculum-grid curriculum-grid-2">
        <div class="curriculum-grid-cell" style="background: #FFFFFF;">
          <h4 class="curriculum-grid-title" style="border-bottom: 1px solid #D8E0E6; padding-bottom: 10px; margin-bottom: 12px; text-transform: uppercase;">Learning Experiences</h4>
          <p class="curriculum-list-inline">
            Storytelling • Rhymes • Show & Tell • Games • Hands-on Activities • Art & Craft • Music & Movement • Interactive Digital Activities
          </p>
        </div>
        <div class="curriculum-grid-cell" style="background: #FFFFFF;">
          <h4 class="curriculum-grid-title" style="border-bottom: 1px solid #D8E0E6; padding-bottom: 10px; margin-bottom: 12px; text-transform: uppercase;">Key Outcomes</h4>
          <p class="curriculum-list-inline">
            Confidence • Curiosity • Communication • Foundational Literacy • Foundational Numeracy • Creativity • Independent Learning Habits
          </p>
        </div>
      </div>

      <!-- ILLUSTRATION: THE EARLY LEARNING GARDEN -->
      <div class="curriculum-process-box">
        <div class="curriculum-process-title">ILLUSTRATION: THE EARLY LEARNING GARDEN</div>
        <div class="curriculum-process-strip curriculum-grid-5">
          <div class="curriculum-process-block bg-green">Language</div>
          <div class="curriculum-process-block bg-orange">Numeracy</div>
          <div class="curriculum-process-block bg-blue">Creativity</div>
          <div class="curriculum-process-block bg-purple">Awareness</div>
          <div class="curriculum-process-block bg-teal">Communication</div>
        </div>
      </div>
    </div>
  </section>

  <!-- PAGE 3 — 2. FOUNDATION STAGE (White Background) -->
  <section class="curriculum-section bg-white">
    <div class="curriculum-container">
      <div class="curriculum-section-header">
        <h2 class="curriculum-section-title">2. Foundation Stage</h2>
        <p class="curriculum-section-subtitle">Grades 1-2 | Build • Question • Create</p>
      </div>

      <div class="curriculum-body">
        <p style="margin: 0;">
          Students strengthen reading, writing and mathematical foundations while learning to ask questions, communicate ideas and connect classroom concepts with everyday experiences.
        </p>
      </div>

      <!-- Core Subjects Grid -->
      <div style="margin-bottom: 28px;">
        <h3 class="curriculum-grid-title" style="margin-bottom: 16px; text-transform: uppercase;">Core Subjects</h3>
        <div class="curriculum-grid curriculum-grid-3">
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">English</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Mathematics</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Environmental Studies</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Hindi / Second Language</span></div>
          <div class="curriculum-grid-cell" style="grid-column: span 2;"><span class="curriculum-grid-title" style="margin:0;">Digital Literacy</span></div>
        </div>
      </div>

      <!-- Beyond Academics Grid -->
      <div style="margin-bottom: 28px;">
        <h3 class="curriculum-grid-title" style="margin-bottom: 16px; text-transform: uppercase;">Beyond Academics</h3>
        <div class="curriculum-grid curriculum-grid-3">
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Art & Creativity</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Communication</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Life Skills</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Physical Wellness</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Social-Emotional Learning</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">General Awareness</span></div>
        </div>
      </div>

      <!-- From Concept to Experience -->
      <div class="curriculum-editorial-block">
        <h4>From Concept to Experience</h4>
        <p>
          A learner may study plants, observe one at home, record its growth, discuss observations and create a mini project. The aim is to show that learning exists beyond the screen and textbook.
        </p>
      </div>

      <!-- Key Outcomes -->
      <div class="curriculum-body" style="margin-bottom: 0;">
        <strong style="color: var(--color-navy-dark);">Key Outcomes:</strong> Reading & Writing • Numeracy • Communication • Problem-Solving • Confidence • Independent Thinking
      </div>

      <!-- ILLUSTRATION: QUESTION TO DISCOVERY -->
      <div class="curriculum-process-box">
        <div class="curriculum-process-title">ILLUSTRATION: QUESTION TO DISCOVERY</div>
        <div class="curriculum-process-strip curriculum-grid-5">
          <div class="curriculum-process-block bg-green">Learn</div>
          <div class="curriculum-process-block bg-orange">Observe</div>
          <div class="curriculum-process-block bg-blue">Record</div>
          <div class="curriculum-process-block bg-purple">Discuss</div>
          <div class="curriculum-process-block bg-teal">Create</div>
        </div>
      </div>
    </div>
  </section>

  <!-- PAGE 4 — 3. PREPARATORY STAGE (Warm Off-White Background) -->
  <section class="curriculum-section bg-warm-white">
    <div class="curriculum-container">
      <div class="curriculum-section-header">
        <h2 class="curriculum-section-title">3. Preparatory Stage</h2>
        <p class="curriculum-section-subtitle">Grades 3-5 | Understand • Apply • Collaborate</p>
      </div>

      <div class="curriculum-body">
        <p style="margin: 0;">
          Learning becomes increasingly interdisciplinary and application-oriented. Students move beyond knowing concepts to understanding why they matter, how they work and where they can be used.
        </p>
      </div>

      <!-- Core Subjects Grid -->
      <div style="margin-bottom: 28px;">
        <h3 class="curriculum-grid-title" style="margin-bottom: 16px; text-transform: uppercase;">Core Subjects</h3>
        <div class="curriculum-grid curriculum-grid-3">
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">English</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Mathematics</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Science</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Social Studies</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Hindi / Second Language</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Digital Literacy</span></div>
        </div>
      </div>

      <!-- Future-Ready Learning Grid (Spec #9 - Title and Official Description) -->
      <div style="margin-bottom: 28px;">
        <h3 class="curriculum-grid-title" style="margin-bottom: 16px; text-transform: uppercase;">Future-Ready Learning</h3>
        <div class="curriculum-grid curriculum-grid-2">
          <div class="curriculum-grid-cell">
            <h4 class="curriculum-grid-title">Coding & Computational Thinking</h4>
            <p class="curriculum-grid-desc">Logical thinking and an introduction to how technology works.</p>
          </div>
          <div class="curriculum-grid-cell">
            <h4 class="curriculum-grid-title">Communication & Public Speaking</h4>
            <p class="curriculum-grid-desc">Expressing ideas clearly and confidently.</p>
          </div>
          <div class="curriculum-grid-cell">
            <h4 class="curriculum-grid-title">Financial Awareness</h4>
            <p class="curriculum-grid-desc">Money, saving, spending and responsible choices.</p>
          </div>
          <div class="curriculum-grid-cell">
            <h4 class="curriculum-grid-title">Creative Arts</h4>
            <p class="curriculum-grid-desc">Imagination, design and self-expression.</p>
          </div>
          <div class="curriculum-grid-cell">
            <h4 class="curriculum-grid-title">Life Skills</h4>
            <p class="curriculum-grid-desc">Decision-making, responsibility and independence.</p>
          </div>
          <div class="curriculum-grid-cell">
            <h4 class="curriculum-grid-title">Health & Wellness</h4>
            <p class="curriculum-grid-desc">Healthy physical and emotional habits.</p>
          </div>
          <div class="curriculum-grid-cell" style="grid-column: span 2;">
            <h4 class="curriculum-grid-title">Environmental Awareness</h4>
            <p class="curriculum-grid-desc">Sustainability and responsibility towards the planet.</p>
          </div>
        </div>
      </div>

      <!-- Key Outcomes -->
      <div class="curriculum-body" style="margin-bottom: 0;">
        <strong style="color: var(--color-navy-dark);">Key Outcomes:</strong> Conceptual Understanding • Research Skills • Collaboration • Creativity • Digital Fluency • Problem-Solving
      </div>

      <!-- ILLUSTRATION: THE LEARNING LAB -->
      <div class="curriculum-process-box">
        <div class="curriculum-process-title">ILLUSTRATION: THE LEARNING LAB</div>
        <div class="curriculum-process-strip curriculum-grid-5">
          <div class="curriculum-process-block bg-green">Experiment</div>
          <div class="curriculum-process-block bg-orange">Code</div>
          <div class="curriculum-process-block bg-blue">Research</div>
          <div class="curriculum-process-block bg-purple">Present</div>
          <div class="curriculum-process-block bg-teal">Collaborate</div>
        </div>
      </div>
    </div>
  </section>

  <!-- PAGE 5 — 4. MIDDLE SCHOOL (White Background) -->
  <section class="curriculum-section bg-white">
    <div class="curriculum-container">
      <div class="curriculum-section-header">
        <h2 class="curriculum-section-title">4. Middle School</h2>
        <p class="curriculum-section-subtitle">Grades 6-8 | Think • Apply • Innovate</p>
      </div>

      <div class="curriculum-body">
        <p style="margin: 0;">
          Middle School marks the transition from guided learning towards greater academic independence and intellectual exploration. Students investigate ideas deeply, analyse information, participate in discussions, undertake research and apply knowledge to meaningful challenges.
        </p>
      </div>

      <!-- Core Subjects Grid -->
      <div style="margin-bottom: 28px;">
        <h3 class="curriculum-grid-title" style="margin-bottom: 16px; text-transform: uppercase;">Core Subjects</h3>
        <div class="curriculum-grid curriculum-grid-3">
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">English</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Mathematics</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Science</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Social Science</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Hindi / Second Language</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Digital & Technology Education</span></div>
        </div>
      </div>

      <!-- Future Skills Grid -->
      <div style="margin-bottom: 28px;">
        <h3 class="curriculum-grid-title" style="margin-bottom: 16px; text-transform: uppercase;">Future Skills</h3>
        <div class="curriculum-grid curriculum-grid-3">
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Coding & AI Awareness</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Entrepreneurship</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Financial Literacy</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Research & Critical Thinking</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Public Speaking & Communication</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Design Thinking</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Leadership</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Global Citizenship</span></div>
          <div class="curriculum-grid-cell"><span class="curriculum-grid-title" style="margin:0;">Career & Interest Exploration</span></div>
        </div>
      </div>

      <!-- Learning Experiences -->
      <div class="curriculum-editorial-block">
        <h4>Learning Experiences</h4>
        <p>
          Case Studies • Research Projects • Debates • Experiments • Collaborative Assignments • Presentations • Innovation Challenges • Real-World Problem Solving
        </p>
      </div>

      <!-- Key Outcomes -->
      <div class="curriculum-body" style="margin-bottom: 0;">
        <strong style="color: var(--color-navy-dark);">Key Outcomes:</strong> Critical Thinking • Academic Independence • Leadership • Digital Fluency • Communication • Innovation • Real-World Readiness
      </div>

      <!-- ILLUSTRATION: FUTURE SKILLS IN ACTION -->
      <div class="curriculum-process-box">
        <div class="curriculum-process-title">ILLUSTRATION: FUTURE SKILLS IN ACTION</div>
        <div class="curriculum-process-strip curriculum-grid-5">
          <div class="curriculum-process-block bg-green">AI & Code</div>
          <div class="curriculum-process-block bg-orange">Think</div>
          <div class="curriculum-process-block bg-blue">Lead</div>
          <div class="curriculum-process-block bg-purple">Communicate</div>
          <div class="curriculum-process-block bg-teal">Global</div>
        </div>
      </div>
    </div>
  </section>

  <!-- PAGE 6 — 5. THE ZUVIO LEARNING FRAMEWORK (Soft Blue Background) -->
  <section class="curriculum-section bg-soft-blue">
    <div class="curriculum-container">
      <div class="curriculum-section-header">
        <h2 class="curriculum-section-title">5. The Zuvio Learning Framework</h2>
        <p class="curriculum-section-subtitle">From Knowing to Doing</p>
      </div>

      <div class="curriculum-body">
        <p style="margin: 0;">
          Learning should not end when a child remembers an answer. Every Zuvio learning experience moves through five dimensions that progressively transform knowledge into confident application.
        </p>
      </div>

      <!-- 5 Framework Dimensions in a balanced 2-column grid -->
      <div class="curriculum-grid curriculum-grid-2" style="margin-bottom: 28px;">
        <div class="curriculum-grid-cell" style="background: #FFFFFF;">
          <h4 class="curriculum-grid-title" style="color: #059669; text-transform: uppercase;">KNOW - Build the Foundation</h4>
          <p class="curriculum-grid-desc">Understand essential concepts, facts and ideas.</p>
        </div>
        <div class="curriculum-grid-cell" style="background: #FFFFFF;">
          <h4 class="curriculum-grid-title" style="color: #D97706; text-transform: uppercase;">THINK - Develop Understanding</h4>
          <p class="curriculum-grid-desc">Question, analyse, compare, reason and solve.</p>
        </div>
        <div class="curriculum-grid-cell" style="background: #FFFFFF;">
          <h4 class="curriculum-grid-title" style="color: #2563EB; text-transform: uppercase;">CREATE - Turn Ideas Into Possibilities</h4>
          <p class="curriculum-grid-desc">Imagine, experiment, design and innovate.</p>
        </div>
        <div class="curriculum-grid-cell" style="background: #FFFFFF;">
          <h4 class="curriculum-grid-title" style="color: #7C3AED; text-transform: uppercase;">CONNECT - Learn With the World</h4>
          <p class="curriculum-grid-desc">Communicate, collaborate and understand different perspectives.</p>
        </div>
        <div class="curriculum-grid-cell" style="background: #FFFFFF; grid-column: span 2;">
          <h4 class="curriculum-grid-title" style="color: #0A8998; text-transform: uppercase;">APPLY - Make Learning Meaningful</h4>
          <p class="curriculum-grid-desc">Use knowledge confidently in projects, challenges and real-life situations.</p>
        </div>
      </div>

      <!-- ILLUSTRATION: KNOW -> THINK -> CREATE -> CONNECT -> APPLY -->
      <div class="curriculum-process-box" style="background: #FFFFFF;">
        <div class="curriculum-process-title">ILLUSTRATION: KNOW &rarr; THINK &rarr; CREATE &rarr; CONNECT &rarr; APPLY</div>
        <div class="curriculum-process-strip curriculum-grid-5">
          <div class="curriculum-process-block bg-green">Know</div>
          <div class="curriculum-process-block bg-orange">Think</div>
          <div class="curriculum-process-block bg-blue">Create</div>
          <div class="curriculum-process-block bg-purple">Connect</div>
          <div class="curriculum-process-block bg-teal">Apply</div>
        </div>
      </div>

      <div style="text-align: center; margin-top: 32px;">
        <h4 style="font-size: 19px; font-weight: 700; color: var(--color-navy-dark); font-family: var(--font-primary); margin: 0;">
          Knowledge &rarr; Understanding &rarr; Application &rarr; Innovation
        </h4>
      </div>
    </div>
  </section>

  <!-- PAGE 7 — 6. LEARNING BEYOND THE TEXTBOOK & 7. ASSESSMENT (White Background) -->
  <section class="curriculum-section bg-white">
    <div class="curriculum-container">
      
      <!-- 6. Learning Beyond the Textbook -->
      <div class="curriculum-section-header">
        <h2 class="curriculum-section-title">6. Learning Beyond the Textbook</h2>
        <p class="curriculum-section-subtitle">Because the World Is the Real Classroom</p>
      </div>

      <!-- Flat 2-column x 3-row grid (Spec #12) -->
      <div class="curriculum-grid curriculum-grid-2" style="margin-bottom: 80px;">
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-title">Projects & Experiments</h4>
          <p class="curriculum-grid-desc">Learning by doing, observing, testing and discovering.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-title">Technology & Digital Learning</h4>
          <p class="curriculum-grid-desc">Using technology creatively, effectively and responsibly.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-title">Communication & Collaboration</h4>
          <p class="curriculum-grid-desc">Presentations, discussions, teamwork and global interactions.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-title">Life Skills</h4>
          <p class="curriculum-grid-desc">Decision-making, independence, financial awareness and emotional intelligence.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-title">Creativity & Innovation</h4>
          <p class="curriculum-grid-desc">Art, coding, design thinking and problem-solving challenges.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-title">Global Exposure</h4>
          <p class="curriculum-grid-desc">Cultures, perspectives and ideas beyond geographical boundaries.</p>
        </div>
      </div>

      <!-- 7. Assessment at Zuvio -->
      <div class="curriculum-section-header">
        <h2 class="curriculum-section-title">7. Assessment at Zuvio</h2>
        <p class="curriculum-section-subtitle">Measure Growth, Not Just Marks</p>
      </div>

      <div class="curriculum-body">
        <p style="margin: 0;">
          Assessment helps learners move forward rather than simply generating a score. It can include concept checks, quizzes, projects, assignments, presentations, portfolios, skill-based assessments and term assessments.
        </p>
      </div>

      <!-- ILLUSTRATION: ASSESSMENT FOR GROWTH -->
      <div class="curriculum-process-box" style="margin-bottom: 28px;">
        <div class="curriculum-process-title">ILLUSTRATION: ASSESSMENT FOR GROWTH</div>
        <div class="curriculum-process-strip curriculum-grid-5">
          <div class="curriculum-process-block bg-green">Learn</div>
          <div class="curriculum-process-block bg-orange">Assess</div>
          <div class="curriculum-process-block bg-blue">Understand</div>
          <div class="curriculum-process-block bg-purple">Support</div>
          <div class="curriculum-process-block bg-teal">Progress</div>
        </div>
      </div>

      <div class="curriculum-body" style="margin: 0;">
        Parents receive meaningful insights into academic progress, skills development, strengths and areas where additional support may be useful.
      </div>
    </div>
  </section>

  <!-- PAGE 8 — 8. PERSONALISED LEARNING & 9. ZUVIO BEYOND (Warm Off-White Background) -->
  <section class="curriculum-section bg-warm-white">
    <div class="curriculum-container">
      
      <!-- 8. Personalised Learning -->
      <div class="curriculum-section-header">
        <h2 class="curriculum-section-title">8. Personalised Learning</h2>
        <p class="curriculum-section-subtitle">Every Learner Has a Different Learning Journey</p>
      </div>

      <div class="curriculum-body">
        <p style="margin: 0;">
          Children do not necessarily learn at the same speed or in exactly the same way. Zuvio brings together teacher guidance, digital learning tools, regular assessment, individual progress insights and targeted academic support to respond to each learner's progress and needs.
        </p>
      </div>

      <!-- ILLUSTRATION: PERSONALISED GROWTH -->
      <div class="curriculum-process-box" style="background: #FFFFFF; margin-bottom: 80px;">
        <div class="curriculum-process-title">ILLUSTRATION: PERSONALISED GROWTH</div>
        <div class="curriculum-process-strip curriculum-grid-5">
          <div class="curriculum-process-block bg-green">Strengths</div>
          <div class="curriculum-process-block bg-orange">Interests</div>
          <div class="curriculum-process-block bg-blue">Learning Needs</div>
          <div class="curriculum-process-block bg-purple">Progress</div>
          <div class="curriculum-process-block bg-teal">Growth</div>
        </div>
      </div>

      <!-- 9. Zuvio Beyond -->
      <div class="curriculum-section-header">
        <h2 class="curriculum-section-title">9. Zuvio Beyond</h2>
        <p class="curriculum-section-subtitle">Learning That Goes Beyond Academics</p>
      </div>

      <div class="curriculum-body">
        <p style="margin: 0;">
          Through Zuvio Beyond, learners can access co-curricular activities, specialised programmes, enrichment opportunities and additional academic support according to their interests and learning needs.
        </p>
      </div>

      <!-- Category Grid -->
      <div class="curriculum-grid curriculum-grid-4">
        <div class="curriculum-grid-cell" style="background: #FFFFFF;"><span class="curriculum-grid-title" style="margin:0; text-align:center;">Creative Arts</span></div>
        <div class="curriculum-grid-cell" style="background: #FFFFFF;"><span class="curriculum-grid-title" style="margin:0; text-align:center;">Communication</span></div>
        <div class="curriculum-grid-cell" style="background: #FFFFFF;"><span class="curriculum-grid-title" style="margin:0; text-align:center;">Thinking Skills</span></div>
        <div class="curriculum-grid-cell" style="background: #FFFFFF;"><span class="curriculum-grid-title" style="margin:0; text-align:center;">Technology</span></div>
        <div class="curriculum-grid-cell" style="background: #FFFFFF;"><span class="curriculum-grid-title" style="margin:0; text-align:center;">Wellness</span></div>
        <div class="curriculum-grid-cell" style="background: #FFFFFF;"><span class="curriculum-grid-title" style="margin:0; text-align:center;">Global Experiences</span></div>
        <div class="curriculum-grid-cell" style="background: #FFFFFF;"><span class="curriculum-grid-title" style="margin:0; text-align:center;">Academic Support</span></div>
        <div class="curriculum-grid-cell" style="background: #FFFFFF;"><span class="curriculum-grid-title" style="margin:0; text-align:center;">Enrichment</span></div>
      </div>

      <!-- ILLUSTRATION: ZUVIO BEYOND -->
      <div class="curriculum-process-box" style="background: #FFFFFF;">
        <div class="curriculum-process-title">ILLUSTRATION: ZUVIO BEYOND</div>
        <div class="curriculum-process-strip curriculum-grid-6" style="gap: 15px;">
          <div class="curriculum-process-block bg-green">Create</div>
          <div class="curriculum-process-block bg-orange">Communicate</div>
          <div class="curriculum-process-block bg-blue">Think</div>
          <div class="curriculum-process-block bg-purple">Tech</div>
          <div class="curriculum-process-block bg-teal">Wellness</div>
          <div class="curriculum-process-block bg-gold">Global</div>
        </div>
      </div>
    </div>
  </section>

  <!-- PAGE 9 — 10. THE ZUVIO GRADUATE (White Background) -->
  <section class="curriculum-section bg-white" style="border-bottom: none;">
    <div class="curriculum-container">
      <div class="curriculum-section-header">
        <h2 class="curriculum-section-title">10. The Zuvio Graduate</h2>
        <p class="curriculum-section-subtitle">Who Are We Preparing Our Learners to Become?</p>
      </div>

      <div class="curriculum-body">
        <p style="margin: 0;">
          By the end of Grade 8, our goal is not simply to prepare students for the next academic grade. We aim to develop capable, confident learners who can understand, communicate, create, collaborate and adapt.
        </p>
      </div>

      <!-- 9 attributes in structured grid -->
      <div class="curriculum-grid curriculum-grid-2" style="margin-bottom: 40px;">
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-title">Academically Strong</h4>
          <p class="curriculum-grid-desc">Understands and applies core academic concepts.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-title">Curious</h4>
          <p class="curriculum-grid-desc">Questions, explores and seeks deeper understanding.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-title">Confident & Articulate</h4>
          <p class="curriculum-grid-desc">Communicates ideas clearly and effectively.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-title">Creative</h4>
          <p class="curriculum-grid-desc">Imagines, designs and builds new possibilities.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-title">Digitally Fluent</h4>
          <p class="curriculum-grid-desc">Navigates a technology-driven world responsibly.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-title">Collaborative</h4>
          <p class="curriculum-grid-desc">Works respectfully and productively with others.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-title">Independent</h4>
          <p class="curriculum-grid-desc">Takes increasing ownership of learning.</p>
        </div>
        <div class="curriculum-grid-cell">
          <h4 class="curriculum-grid-title">Globally Aware</h4>
          <p class="curriculum-grid-desc">Values cultures, perspectives and global issues.</p>
        </div>
        <div class="curriculum-grid-cell" style="grid-column: span 2;">
          <h4 class="curriculum-grid-title">Future-Ready</h4>
          <p class="curriculum-grid-desc">Prepared to learn, adapt and grow in a changing world.</p>
        </div>
      </div>

      <!-- ILLUSTRATION: THE ZUVIO GRADUATE -->
      <div class="curriculum-process-box">
        <div class="curriculum-process-title">ILLUSTRATION: THE ZUVIO GRADUATE</div>
        <div class="curriculum-process-strip curriculum-grid-6" style="gap: 15px;">
          <div class="curriculum-process-block bg-green">Academic</div>
          <div class="curriculum-process-block bg-orange">Curious</div>
          <div class="curriculum-process-block bg-blue">Confident</div>
          <div class="curriculum-process-block bg-purple">Creative</div>
          <div class="curriculum-process-block bg-teal">Digital</div>
          <div class="curriculum-process-block bg-gold">Global</div>
        </div>
      </div>

      <div style="text-align: center; margin-top: 40px;">
        <span style="font-size: 19px; font-weight: 700; color: var(--color-navy-dark); letter-spacing: 1px; display: block; margin-bottom: 24px; text-transform: uppercase;">
          CURIOUS • CONFIDENT • CREATIVE • ARTICULATE • INDEPENDENT • COLLABORATIVE • DIGITALLY FLUENT • GLOBALLY AWARE
        </span>
        <p style="font-size: 24px; font-weight: 700; color: var(--color-navy-dark); line-height: 1.4; margin: 0;">
          Strong Foundations.<br>
          Future Skills.<br>
          Learning Without Boundaries.
        </p>
      </div>
    </div>
  </section>

  <!-- CALL TO ACTION / REGISTRATION -->
  <section class="section text-center" style="background-color: var(--color-navy-dark); color: #FFFFFF; padding: 80px 20px;">
    <div class="curriculum-container" style="max-width: 800px;">
      <h2 class="curriculum-section-title" style="color: #FFFFFF; margin-bottom: 16px; text-transform: uppercase;">Map Your Child's Academic Journey</h2>
      <p style="color: #E2E8F0; margin-bottom: 32px; max-width: 600px; margin-left: auto; margin-right: auto; font-size: 18px; line-height: 1.6;">
        Get in touch with our admissions coordinators to verify grade availability and structure personalized study paths.
      </p>
      <button onclick="openCallbackModal()" class="btn btn-primary" style="padding: 1.15rem 3.5rem; font-size: 1.1rem; border-radius: 4px;">Request Callback</button>
    </div>
  </section>

</div>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
