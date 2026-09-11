<?php
// Zuvio Global School - Academics Hub Page Template
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

$page_slug = 'academics';
$seo = [
    'seo_title' => 'Academics & Technology | Zuvio Global School',
    'meta_description' => 'Explore Zuvio’s academic architecture: CBSE & NEP 2020 alignment, Oxford thematic curriculum, US-based LMS technology, Special Education support, and elective pathways.',
    'canonical_url' => BASE_URL . '/academics',
    'og_title' => 'Academics | Zuvio Global School',
    'og_description' => 'Future-ready academic architecture combining digital fluency, structured curricula, and personalized student support.',
    'og_image' => '/assets/images/logo.png',
    'index_status' => 'index, follow'
];

include_once dirname(__FILE__) . '/../includes/header.php';
?>

<!-- Hero Banner -->
<section style="background-color: var(--pastel-blue); padding: 5.5rem 1.5rem; text-align: center; border-bottom: 1px solid var(--color-border);">
  <div class="container" style="max-width: 800px;">
    <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Academic Architecture</span>
    <h1 style="font-size: 3.25rem; color: var(--color-navy-dark); margin: 0.5rem 0 1rem 0; font-family: var(--font-primary);">Academics & Technology</h1>
    <p style="font-size: 1.15rem; color: var(--color-text); line-height: 1.7;">
      Where rigorous academic standards meet progressive pedagogy, modern LMS tools, and personalized pathways designed for an evolving world.
    </p>
    <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem; flex-wrap: wrap;">
      <a href="/curriculum" class="btn btn-primary" style="background-color: var(--color-navy); border-color: var(--color-navy); color: #FFFFFF; font-weight: 700;">Full Curriculum Guide</a>
      <a href="#technology" class="btn btn-outline" style="border-color: var(--color-navy); color: var(--color-navy); font-weight: 600;">Learning Technology</a>
      <a href="#special-education" class="btn btn-outline" style="border-color: var(--color-navy); color: var(--color-navy); font-weight: 600;">Special Education</a>
    </div>
  </div>
</section>

<!-- Section 1: Technology & LMS -->
<section id="technology" class="section" style="background-color: #FFFFFF; border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="grid-2" style="align-items: center; gap: 3.5rem;">
      <div>
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">US-Based Digital Ecosystem</span>
        <h2 style="font-size: 2.25rem; color: var(--color-navy); margin: 0.5rem 0 1.25rem 0; font-family: var(--font-primary);">Technology Built for Real Learning</h2>
        <p style="color: var(--color-text); font-size: 1rem; line-height: 1.75; margin-bottom: 1.25rem;">
          Technology at Zuvio is never a passive screen; it is an active workspace for inquiry, collaboration, and creative output. Powered by an enterprise-grade US-based Learning Management System (LMS), our platform provides:
        </p>
        <ul style="list-style: none; padding: 0; display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.92rem; color: var(--color-text);">
          <li style="display: flex; gap: 0.5rem; align-items: flex-start;">
            <span style="color: var(--color-teal); font-weight: 700;">✓</span>
            <span><strong>Live Interactive Classroom:</strong> Low-latency audio-video with digital whiteboards, breakout rooms, interactive polls, and real-time screen sharing.</span>
          </li>
          <li style="display: flex; gap: 0.5rem; align-items: flex-start;">
            <span style="color: var(--color-teal); font-weight: 700;">✓</span>
            <span><strong>Student Learning Portal:</strong> Centralized daily timetable, assignment submissions, recorded session library, and Oxford digital reader access.</span>
          </li>
          <li style="display: flex; gap: 0.5rem; align-items: flex-start;">
            <span style="color: var(--color-teal); font-weight: 700;">✓</span>
            <span><strong>Parent Dashboard:</strong> Attendance monitoring, continuous formative gradebook, direct teacher messaging, and scheduled PTM bookings.</span>
          </li>
        </ul>
      </div>

      <div style="background-color: var(--pastel-blue); border-radius: var(--radius-lg); padding: 2.5rem; border: 1px solid rgba(10, 137, 152, 0.2); box-shadow: var(--shadow-sm);">
        <h3 style="font-size: 1.35rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 1rem;">Tools for Tomorrow's Learners</h3>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
          <div style="background: #FFFFFF; padding: 1rem; border-radius: var(--radius-sm); border-left: 3px solid var(--color-gold);">
            <strong style="color: var(--color-navy); font-size: 0.95rem;">IBM-Supported AI Framework</strong>
            <p style="font-size: 0.82rem; color: var(--color-muted); margin-top: 0.25rem;">Responsible AI awareness, machine learning pattern training, and prompt thinking.</p>
          </div>
          <div style="background: #FFFFFF; padding: 1rem; border-radius: var(--radius-sm); border-left: 3px solid var(--color-teal);">
            <strong style="color: var(--color-navy); font-size: 0.95rem;">Interactive Coding Playgrounds</strong>
            <p style="font-size: 0.82rem; color: var(--color-muted); margin-top: 0.25rem;">Browser-based Scratch block and Python coding environments with zero software installation required.</p>
          </div>
          <div style="background: #FFFFFF; padding: 1rem; border-radius: var(--radius-sm); border-left: 3px solid var(--color-gold);">
            <strong style="color: var(--color-navy); font-size: 0.95rem;">Oxford Digital Library Access</strong>
            <p style="font-size: 0.82rem; color: var(--color-muted); margin-top: 0.25rem;">Authentic audiobooks, graded readers, interactive science simulations, and thematic workbooks.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Section 2: Special Education Support -->
<section id="special-education" class="section" style="background-color: var(--color-surface-warm); border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="text-center" style="max-width: 800px; margin: 0 auto 3.5rem auto;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Inclusive by Design</span>
      <h2 style="font-size: 2.5rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">Special Education & Diverse Learning Support</h2>
      <p style="color: var(--color-muted); font-size: 1.05rem; line-height: 1.7; margin-top: 0.5rem;">
        Every child learns in their own distinctive way. Zuvio is committed to providing expert, empathetic support for children with diverse learning styles, ADHD, dyslexia, and developmental differences.
      </p>
    </div>

    <div class="grid-3" style="gap: 2rem;">
      <div class="card" style="padding: 2.25rem; border-left: 4px solid var(--color-teal); background-color: #FFFFFF;">
        <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.75rem;">Dedicated Special Educator</h3>
        <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.65;">
          Guided by our qualified Special Educator, we design Individualised Education Plans (IEPs) tailored to each child's cognitive strengths, pacing, and processing preferences.
        </p>
      </div>

      <div class="card" style="padding: 2.25rem; border-left: 4px solid var(--color-gold); background-color: #FFFFFF;">
        <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.75rem;">Small-Group live Attention</h3>
        <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.65;">
          With a 15:1 maximum student-teacher ratio and targeted breakout circles, every student is genuinely seen, heard, and supported without classroom overwhelm.
        </p>
      </div>

      <div class="card" style="padding: 2.25rem; border-left: 4px solid var(--color-teal); background-color: #FFFFFF;">
        <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.75rem;">Multisensory Pedagogies</h3>
        <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.65;">
          Audio, visual, kinesthetic, and narrative-based instructional techniques that bridge conceptual gaps naturally without rote testing pressure.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Section 3: Electives & NEP 2020 Alignment -->
<section id="electives" class="section" style="background-color: #FFFFFF; border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="grid-2" style="gap: 3.5rem;">
      
      <!-- Electives Column -->
      <div>
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Tailored Passions</span>
        <h2 style="font-size: 2.25rem; color: var(--color-navy); margin: 0.5rem 0 1.25rem 0; font-family: var(--font-primary);">Electives & Languages</h2>
        <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.7; margin-bottom: 1.5rem;">
          Students can enrich their core academic timetable with language electives and creative options that broaden their cognitive horizons:
        </p>

        <div style="display: flex; flex-direction: column; gap: 1rem;">
          <div style="padding: 1.25rem; background: var(--color-surface); border-radius: var(--radius-md); border-left: 4px solid var(--color-gold);">
            <strong style="color: var(--color-navy); font-size: 1.05rem;">Regional & Classical Languages</strong>
            <p style="font-size: 0.85rem; color: var(--color-muted); margin-top: 0.25rem;">Hindi, Sanskrit, French, and Spanish language options taught with cultural context and conversation focus.</p>
          </div>
          <div style="padding: 1.25rem; background: var(--color-surface); border-radius: var(--radius-md); border-left: 4px solid var(--color-teal);">
            <strong style="color: var(--color-navy); font-size: 1.05rem;">Creative Arts & Media</strong>
            <p style="font-size: 0.85rem; color: var(--color-muted); margin-top: 0.25rem;">Digital illustration, music appreciation, creative writing, drama, and public speaking clubs.</p>
          </div>
        </div>
      </div>

      <!-- NEP 2020 Column -->
      <div id="nep-2020">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Policy Alignment</span>
        <h2 style="font-size: 2.25rem; color: var(--color-navy); margin: 0.5rem 0 1.25rem 0; font-family: var(--font-primary);">NEP 2020 & NCF Compliance</h2>
        <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.7; margin-bottom: 1.5rem;">
          Our academic progression reflects the pedagogical shifts outlined in the National Education Policy (NEP 2020) and the National Curriculum Framework (NCF):
        </p>

        <ul style="list-style: none; padding: 0; display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.9rem; color: var(--color-text);">
          <li style="display: flex; gap: 0.5rem; align-items: flex-start;">
            <span style="color: var(--color-teal); font-weight: 700;">✓</span>
            <span><strong>Competency-Based Learning:</strong> Focus on understanding and conceptual mastery rather than memorising for marks.</span>
          </li>
          <li style="display: flex; gap: 0.5rem; align-items: flex-start;">
            <span style="color: var(--color-teal); font-weight: 700;">✓</span>
            <span><strong>Interdisciplinary Thematic Modules:</strong> Blending environmental science, history, arithmetic, and art into cohesive Oxford themes.</span>
          </li>
          <li style="display: flex; gap: 0.5rem; align-items: flex-start;">
            <span style="color: var(--color-teal); font-weight: 700;">✓</span>
            <span><strong>Holistic 360° Progress Cards:</strong> Assessing critical thinking, collaboration, digital literacy, and personal growth.</span>
          </li>
        </ul>
      </div>

    </div>
  </div>
</section>

<!-- Section 4: Resources & Curriculum Link -->
<section id="resources" class="section text-center" style="background-color: var(--pastel-blue); padding: 5rem 0;">
  <div class="container" style="max-width: 750px;">
    <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Comprehensive Details</span>
    <h2 style="font-size: 2.5rem; color: var(--color-navy); margin: 0.5rem 0 1rem 0; font-family: var(--font-primary);">Explore the Complete Curriculum</h2>
    <p style="color: var(--color-muted); font-size: 1.05rem; line-height: 1.7; margin-bottom: 2rem;">
      Dive into detailed stage-by-stage learning areas, weekly timetables, core subject matrices, and key learning outcomes from Kindergarten to Grade 8.
    </p>
    <a href="/curriculum" class="btn btn-primary" style="background-color: var(--color-navy); border-color: var(--color-navy); color: #FFFFFF; font-weight: 700; padding: 0.85rem 2.5rem;">
      View Complete Curriculum Insights &rarr;
    </a>
  </div>
</section>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
