<?php
// Zuvio Global School - Admissions Hub Page Template
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

$page_slug = 'admissions';
$seo = [
    'seo_title' => 'Admissions & Enrolment | Zuvio Global School',
    'meta_description' => 'Admissions Open 2026-2027. Discover eligibility criteria, age matrix, enrolment steps, fee structure, and academic calendar for Nursery to Grade 8 online schooling.',
    'canonical_url' => BASE_URL . '/admissions',
    'og_title' => 'Admissions | Zuvio Global School',
    'og_description' => 'Join our global online learning community. Step-by-step admission process, eligibility and calendar.',
    'og_image' => '/assets/images/logo.png',
    'index_status' => 'index, follow'
];

include_once dirname(__FILE__) . '/../includes/header.php';
?>

<!-- Hero Section -->
<section style="background-color: var(--pastel-blue); padding: 5.5rem 1.5rem; text-align: center; border-bottom: 1px solid var(--color-border);">
  <div class="container" style="max-width: 800px;">
    <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Academic Year 2026–2027</span>
    <h1 style="font-size: 3.25rem; color: var(--color-navy-dark); margin: 0.5rem 0 1rem 0; font-family: var(--font-primary);">Admissions & Enrolment</h1>
    <p style="font-size: 1.15rem; color: var(--color-text); line-height: 1.7;">
      A seamless, supportive onboarding journey designed to understand your child’s learning style, baseline competencies, and personal interests.
    </p>
    <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem; flex-wrap: wrap;">
      <a href="#enrol" class="btn btn-primary" style="background-color: var(--color-navy); border-color: var(--color-navy); color: #FFFFFF; font-weight: 700;">Enrol Now</a>
      <a href="#eligibility" class="btn btn-outline" style="border-color: var(--color-navy); color: var(--color-navy); font-weight: 600;">Check Eligibility</a>
      <a href="javascript:void(0)" onclick="openCallbackModal()" class="btn btn-primary btn-demo" style="background-color: var(--color-teal); border-color: var(--color-teal); color: #FFFFFF; font-weight: 700;">Book a Demo</a>
    </div>
  </div>
</section>

<!-- Section 1: Enrol Now Process Steps -->
<section id="enrol" class="section" style="background-color: #FFFFFF; border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="text-center" style="margin-bottom: 3.5rem;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Simple 4-Step Journey</span>
      <h2 style="font-size: 2.5rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">The Enrolment Process</h2>
      <p style="color: var(--color-muted); font-size: 1.05rem; margin-top: 0.5rem;">From enquiry to the first live classroom session.</p>
    </div>

    <div class="grid-4" style="gap: 1.75rem;">
      <div class="card" style="padding: 2rem; border-left: none; border-top: 4px solid var(--color-gold); background: var(--color-surface);">
        <span style="font-size: 0.8rem; font-weight: 800; color: var(--color-gold);">STEP 01</span>
        <h3 style="font-size: 1.25rem; color: var(--color-navy); margin: 0.5rem 0 0.75rem 0; font-family: var(--font-primary);">Enquiry & Consultation</h3>
        <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.65;">
          Submit your enquiry or request a callback. Speak with an academic advisor to understand curriculum mapping, live timings, and technological setup.
        </p>
      </div>

      <div class="card" style="padding: 2rem; border-left: none; border-top: 4px solid var(--color-teal); background: var(--color-surface);">
        <span style="font-size: 0.8rem; font-weight: 800; color: var(--color-teal);">STEP 02</span>
        <h3 style="font-size: 1.25rem; color: var(--color-navy); margin: 0.5rem 0 0.75rem 0; font-family: var(--font-primary);">Interactive Demo Class</h3>
        <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.65;">
          Experience a real-time live virtual classroom demo. Observe our small-group interaction, digital tools, and child-centered teaching methodology.
        </p>
      </div>

      <div class="card" style="padding: 2rem; border-left: none; border-top: 4px solid var(--color-gold); background: var(--color-surface);">
        <span style="font-size: 0.8rem; font-weight: 800; color: var(--color-gold);">STEP 03</span>
        <h3 style="font-size: 1.25rem; color: var(--color-navy); margin: 0.5rem 0 0.75rem 0; font-family: var(--font-primary);">Baseline Assessment</h3>
        <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.65;">
          A gentle, friendly baseline diagnostic to understand current grade competencies, conceptual strengths, and areas where bridge support is beneficial.
        </p>
      </div>

      <div class="card" style="padding: 2rem; border-left: none; border-top: 4px solid var(--color-teal); background: var(--color-surface);">
        <span style="font-size: 0.8rem; font-weight: 800; color: var(--color-teal);">STEP 04</span>
        <h3 style="font-size: 1.25rem; color: var(--color-navy); margin: 0.5rem 0 0.75rem 0; font-family: var(--font-primary);">Onboarding & Live Schooling</h3>
        <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.65;">
          Receive student LMS login credentials, timetable schedule, Oxford digital resources, and parent communication channel setup.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Section 2: Eligibility Criteria -->
<section id="eligibility" class="section" style="background-color: var(--color-surface-blue); border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="text-center" style="margin-bottom: 3.5rem;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Grade Placement Guidelines</span>
      <h2 style="font-size: 2.5rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">Eligibility & Age Criteria</h2>
      <p style="color: var(--color-muted); font-size: 1.05rem; margin-top: 0.5rem;">Age norms in alignment with NEP 2020 guidelines as of 31st March 2026.</p>
    </div>

    <div style="background-color: #FFFFFF; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); overflow: hidden; border: 1px solid var(--color-border); max-width: 900px; margin: 0 auto;">
      <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
          <tr style="background-color: var(--color-navy-dark); color: #FFFFFF; font-family: var(--font-secondary);">
            <th style="padding: 1.1rem 1.5rem; font-size: 0.9rem;">Stage</th>
            <th style="padding: 1.1rem 1.5rem; font-size: 0.9rem;">Grade</th>
            <th style="padding: 1.1rem 1.5rem; font-size: 0.9rem;">Recommended Age</th>
            <th style="padding: 1.1rem 1.5rem; font-size: 0.9rem;">Daily Live Class Duration</th>
          </tr>
        </thead>
        <tbody style="font-size: 0.92rem; color: var(--color-text);">
          <tr style="border-bottom: 1px solid var(--color-border);">
            <td style="padding: 1rem 1.5rem; font-weight: 700; color: var(--color-teal);">Early Years</td>
            <td style="padding: 1rem 1.5rem;">Nursery – KG</td>
            <td style="padding: 1rem 1.5rem;">3 – 5+ Years</td>
            <td style="padding: 1rem 1.5rem;">Approx. 2 Hours</td>
          </tr>
          <tr style="border-bottom: 1px solid var(--color-border); background-color: var(--pastel-blue);">
            <td style="padding: 1rem 1.5rem; font-weight: 700; color: var(--color-navy);">Foundation Stage</td>
            <td style="padding: 1rem 1.5rem;">Grades 1 – 2</td>
            <td style="padding: 1rem 1.5rem;">6 – 7+ Years</td>
            <td style="padding: 1rem 1.5rem;">Approx. 2.5 Hours</td>
          </tr>
          <tr style="border-bottom: 1px solid var(--color-border);">
            <td style="padding: 1rem 1.5rem; font-weight: 700; color: var(--color-navy);">Preparatory Stage</td>
            <td style="padding: 1rem 1.5rem;">Grades 3 – 5</td>
            <td style="padding: 1rem 1.5rem;">8 – 10+ Years</td>
            <td style="padding: 1rem 1.5rem;">Approx. 2.5 Hours</td>
          </tr>
          <tr>
            <td style="padding: 1rem 1.5rem; font-weight: 700; color: var(--color-gold);">Middle School</td>
            <td style="padding: 1rem 1.5rem;">Grades 6 – 8</td>
            <td style="padding: 1rem 1.5rem;">11 – 13+ Years</td>
            <td style="padding: 1rem 1.5rem;">Approx. 3 Hours</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div style="max-width: 900px; margin: 2rem auto 0 auto; padding: 1.5rem; background-color: var(--pastel-yellow); border-radius: var(--radius-md); border-left: 4px solid var(--color-gold); font-size: 0.88rem; color: var(--color-navy); line-height: 1.6;">
      <strong>Note on Mid-Session / Term 2 Enrolment:</strong> Students joining mid-session undergo an initial diagnostic assessment. Teachers identify curricular gaps and provide an individual bridge learning plan to support an effortless transition into the ongoing curriculum.
    </div>
  </div>
</section>

<!-- Section 3: Academic Calendar & Fees -->
<section id="calendar" class="section" style="background-color: #FFFFFF; border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="grid-2" style="gap: 3.5rem;">
      
      <!-- Calendar Column -->
      <div>
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Schedules</span>
        <h2 style="font-size: 2.25rem; color: var(--color-navy); margin: 0.5rem 0 1.25rem 0; font-family: var(--font-primary);">Academic Calendar</h2>
        <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.7; margin-bottom: 1.5rem;">
          Zuvio operates on a structured two-term academic year, aligned with standard Indian and international schooling timelines, ensuring seamless portability and holiday balance.
        </p>

        <div style="display: flex; flex-direction: column; gap: 1rem;">
          <div style="padding: 1.25rem; background-color: var(--color-surface); border-radius: var(--radius-md); border-left: 4px solid var(--color-teal);">
            <strong style="color: var(--color-navy); font-size: 1.05rem;">Term 1: April to September</strong>
            <p style="font-size: 0.85rem; color: var(--color-muted); margin-top: 0.25rem;">Curriculum launch, foundational concepts, Oxford themes, mid-term formative reviews, and summer enrichment modules.</p>
          </div>
          <div style="padding: 1.25rem; background-color: var(--color-surface); border-radius: var(--radius-md); border-left: 4px solid var(--color-gold);">
            <strong style="color: var(--color-navy); font-size: 1.05rem;">Term 2: October to March</strong>
            <p style="font-size: 0.85rem; color: var(--color-muted); margin-top: 0.25rem;">Project exhibitions, advanced coding/AI explorations, co-curricular showcases, and end-of-year comprehensive portfolios.</p>
          </div>
        </div>
      </div>

      <!-- Fees Column -->
      <div id="fees">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Transparent Value</span>
        <h2 style="font-size: 2.25rem; color: var(--color-navy); margin: 0.5rem 0 1.25rem 0; font-family: var(--font-primary);">Fee Structure Philosophy</h2>
        <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.7; margin-bottom: 1.5rem;">
          Our tuition is transparent, all-inclusive, and structured without hidden infrastructural overheads. It includes live small-group teaching, digital Oxford learning materials, LMS software licensing, regular assessment feedback, and co-curricular enrichment.
        </p>

        <div style="padding: 2rem; background-color: var(--pastel-blue); border-radius: var(--radius-md); border: 1px solid rgba(10, 137, 152, 0.2);">
          <h4 style="color: var(--color-navy); font-size: 1.15rem; margin-bottom: 0.5rem;">Request Detailed Fee Schedule</h4>
          <p style="font-size: 0.88rem; color: var(--color-muted); line-height: 1.6; margin-bottom: 1.25rem;">
            Fee schedules vary by stage (Early Years, Primary, Middle School) and payment frequency (Annual / Semi-Annual). Contact our admissions team for the complete grade-specific breakdown.
          </p>
          <a href="/contact" class="btn btn-primary" style="background-color: var(--color-navy); border-color: var(--color-navy); color: #FFFFFF; font-weight: 600;">
            Request Fee Breakdown &rarr;
          </a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Section 4: Admissions FAQ Teaser -->
<section class="section" style="background-color: var(--color-surface-warm); padding: 5rem 0;">
  <div class="container text-center" style="max-width: 750px;">
    <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Got Admissions Questions?</span>
    <h2 style="font-size: 2.25rem; color: var(--color-navy); margin: 0.5rem 0 1rem 0; font-family: var(--font-primary);">Parent FAQ & Transitions</h2>
    <p style="color: var(--color-muted); font-size: 1.05rem; line-height: 1.7; margin-bottom: 2rem;">
      Wondering about transitioning back to an offline school, board registration pathways, or class timings? Read our comprehensive 18-question parent FAQ guide.
    </p>
    <a href="/faq" class="btn btn-primary" style="background-color: var(--color-teal); border-color: var(--color-teal); color: #FFFFFF; font-weight: 700; padding: 0.85rem 2.25rem;">
      Read Complete 18-Question FAQ &rarr;
    </a>
  </div>
</section>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
