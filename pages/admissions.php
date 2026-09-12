<?php
// Zuvio Global School - Admissions Conversion Hub Page
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// CMS synchronization
if (!isset($_SESSION['mock_admissions_cms'])) {
    $_SESSION['mock_admissions_cms'] = [];
}
$adm_cms = &$_SESSION['mock_admissions_cms'];

$overview = $adm_cms['overview'] ?? [
    'hero_badge' => 'Academic Year 2026–2027',
    'hero_title' => 'Admissions & Enrolment',
    'hero_subtitle' => 'A seamless, supportive onboarding journey designed to understand your child’s learning style, baseline competencies, and personal interests.'
];

$page_slug = 'admissions';
$seo = [
    'seo_title' => 'Admissions & Enrolment — Academic Year 2026–2027 | Zuvio Global School',
    'meta_description' => 'Admissions Open 2026–2027. Explore dedicated pages for Enrol Now, Eligibility criteria, Academic Calendar, Transparent Fees, and Parent FAQs.',
    'canonical_url' => BASE_URL . '/admissions',
    'og_title' => 'Admissions & Enrolment — Zuvio Global School',
    'og_description' => 'A seamless, supportive onboarding journey for K–8 online schooling. Explore enrolment steps, eligibility matrix, term dates, and fees.',
    'og_image' => '/assets/images/about_us_hero.jpg',
    'index_status' => 'index, follow'
];

include_once dirname(__FILE__) . '/../includes/header.php';

render_breadcrumbs([
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Admissions']
]);
?>

<main class="admissions-hub-page">
  <!-- Hero Section -->
  <section class="section-hero" style="background-color: var(--pastel-blue); padding: 5rem 1.5rem; text-align: center; border-bottom: 1px solid var(--color-border);">
    <div class="container" style="max-width: 850px;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">
        <?php echo h($overview['hero_badge'] ?? 'Academic Year 2026–2027'); ?>
      </span>
      <h1 style="font-size: 3rem; color: var(--color-navy-dark); margin: 0.5rem 0 1rem 0; font-family: var(--font-primary); font-weight: 700; line-height: 1.2;">
        <?php echo h($overview['hero_title']); ?>
      </h1>
      <p style="font-size: 1.15rem; color: var(--color-text); line-height: 1.7; margin: 0 auto; max-width: 740px;">
        <?php echo h($overview['hero_subtitle']); ?>
      </p>
      <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem; flex-wrap: wrap;">
        <a href="/admissions/enrol-now" class="btn btn-primary" style="background-color: var(--color-navy); border-color: var(--color-navy); color: #FFFFFF; font-weight: 700; padding: 0.85rem 2rem;">
          Enrol Now &rarr;
        </a>
        <a href="/admissions/eligibility" class="btn btn-outline" style="border-color: var(--color-navy); color: var(--color-navy); font-weight: 600; padding: 0.85rem 1.75rem;">
          Check Eligibility
        </a>
        <a href="javascript:void(0)" onclick="openCallbackModal()" class="btn btn-primary btn-demo" style="background-color: var(--color-teal); border-color: var(--color-teal); color: #FFFFFF; font-weight: 700; padding: 0.85rem 1.75rem;">
          Book a Demo
        </a>
      </div>
    </div>
  </section>

  <!-- Gateway Cards: Dedicated Destinations -->
  <section class="section" style="background-color: #FFFFFF; padding: 5.5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Dedicated Admissions Pages
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          Everything You Need to Get Started
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          Explore detailed guides for each stage of your child's enrolment journey.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.75rem;">
        
        <!-- Card 1: Enrol Now -->
        <div style="background-color: var(--color-surface-warm); border-radius: var(--radius-md); padding: 2.25rem 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid var(--color-teal);">
          <div>
            <div style="font-size: 2.2rem; margin-bottom: 0.75rem;">📝</div>
            <h3 style="font-size: 1.35rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">
              Enrol Now
            </h3>
            <p style="color: var(--color-text); font-size: 0.92rem; line-height: 1.6; margin-bottom: 1.5rem;">
              Simple 5-step admissions process, online application form, and required documents checklist for 2026–2027.
            </p>
          </div>
          <a href="/admissions/enrol-now" style="color: var(--color-teal); font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.95rem;">
            Go to Enrol Now &rarr;
          </a>
        </div>

        <!-- Card 2: Eligibility -->
        <div style="background-color: var(--color-surface-warm); border-radius: var(--radius-md); padding: 2.25rem 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid var(--color-gold);">
          <div>
            <div style="font-size: 2.2rem; margin-bottom: 0.75rem;">🎯</div>
            <h3 style="font-size: 1.35rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">
              Eligibility
            </h3>
            <p style="color: var(--color-text); font-size: 0.92rem; line-height: 1.6; margin-bottom: 1.5rem;">
              NEP 2020 aligned age matrix as of 31st March 2026, daily live class timings, and mid-session transfer policies.
            </p>
          </div>
          <a href="/admissions/eligibility" style="color: var(--color-navy); font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.95rem;">
            Check Eligibility &rarr;
          </a>
        </div>

        <!-- Card 3: Calendar -->
        <div style="background-color: var(--color-surface-warm); border-radius: var(--radius-md); padding: 2.25rem 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid var(--color-navy);">
          <div>
            <div style="font-size: 2.2rem; margin-bottom: 0.75rem;">🗓️</div>
            <h3 style="font-size: 1.35rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">
              Academic Calendar
            </h3>
            <p style="color: var(--color-text); font-size: 0.92rem; line-height: 1.6; margin-bottom: 1.5rem;">
              Two-term breakdown, key project milestones, assessment windows, and official downloadable Calendar PDF.
            </p>
          </div>
          <a href="/admissions/calendar" style="color: var(--color-teal); font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.95rem;">
            View Calendar &rarr;
          </a>
        </div>

        <!-- Card 4: Fees -->
        <div style="background-color: var(--color-surface-warm); border-radius: var(--radius-md); padding: 2.25rem 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid var(--color-gold);">
          <div>
            <div style="font-size: 2.2rem; margin-bottom: 0.75rem;">💳</div>
            <h3 style="font-size: 1.35rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">
              Fee Structure
            </h3>
            <p style="color: var(--color-text); font-size: 0.92rem; line-height: 1.6; margin-bottom: 1.5rem;">
              Approved official tuition schedules, quarterly payment terms, and complimentary books/LMS/ERP inclusions.
            </p>
          </div>
          <a href="/admissions/fees" style="color: var(--color-navy); font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.95rem;">
            Explore Fees &rarr;
          </a>
        </div>

      </div>
    </div>
  </section>

  <!-- 4-Step Onboarding Summary -->
  <section class="section" style="background-color: var(--pastel-blue); padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          The Journey
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          How Enrolment Works
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          From your first conversation to your child's first day in the digital classroom.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem;">
        <div style="background: #FFFFFF; padding: 2rem; border-radius: var(--radius-md); border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); border-top: 4px solid var(--color-gold);">
          <span style="font-size: 0.8rem; font-weight: 800; color: var(--color-gold);">STEP 01</span>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); margin: 0.5rem 0 0.75rem 0; font-family: var(--font-primary);">Consultation</h3>
          <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.65; margin: 0;">
            Speak with our advisors to map out curriculum options, schedule flexibility, and device requirements.
          </p>
        </div>

        <div style="background: #FFFFFF; padding: 2rem; border-radius: var(--radius-md); border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); border-top: 4px solid var(--color-teal);">
          <span style="font-size: 0.8rem; font-weight: 800; color: var(--color-teal);">STEP 02</span>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); margin: 0.5rem 0 0.75rem 0; font-family: var(--font-primary);">Demo Class</h3>
          <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.65; margin: 0;">
            Experience our interactive live classroom environment, meet master educators, and see small-group learning.
          </p>
        </div>

        <div style="background: #FFFFFF; padding: 2rem; border-radius: var(--radius-md); border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); border-top: 4px solid var(--color-gold);">
          <span style="font-size: 0.8rem; font-weight: 800; color: var(--color-gold);">STEP 03</span>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); margin: 0.5rem 0 0.75rem 0; font-family: var(--font-primary);">Assessment</h3>
          <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.65; margin: 0;">
            A low-stress baseline diagnostic to identify current learning levels and design individual bridge support.
          </p>
        </div>

        <div style="background: #FFFFFF; padding: 2rem; border-radius: var(--radius-md); border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); border-top: 4px solid var(--color-teal);">
          <span style="font-size: 0.8rem; font-weight: 800; color: var(--color-teal);">STEP 04</span>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); margin: 0.5rem 0 0.75rem 0; font-family: var(--font-primary);">Onboarding</h3>
          <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.65; margin: 0;">
            Receive student LMS login credentials, Oxford digital readers, timetables, and welcome pack.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Parent FAQ Cross Link -->
  <section class="section" style="background-color: #FFFFFF; padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container text-center" style="max-width: 750px;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
        Got Admissions Questions?
      </span>
      <h2 style="font-size: 2.25rem; color: var(--color-navy); margin: 0.5rem 0 1rem 0; font-family: var(--font-primary);">
        Parent FAQ &amp; Transitions
      </h2>
      <p style="color: var(--color-muted); font-size: 1.05rem; line-height: 1.7; margin-bottom: 2rem;">
        Wondering about transitioning back to an offline school, board registration pathways, or class timings? Read our comprehensive 18-question parent FAQ guide.
      </p>
      <a href="/faq" class="btn btn-primary" style="background-color: var(--color-teal); border-color: var(--color-teal); color: #FFFFFF; font-weight: 700; padding: 0.85rem 2.25rem; border-radius: var(--radius-sm); text-decoration: none;">
        Read Complete 18-Question FAQ &rarr;
      </a>
    </div>
  </section>

  <!-- Request Callback Lead Capture -->
  <section class="section" style="background-color: var(--color-surface-warm); padding: 4.5rem 0;">
    <div class="container" style="max-width: 850px;">
      <div style="background: linear-gradient(135deg, var(--color-navy-dark) 0%, var(--color-navy) 100%); border-radius: var(--radius-lg); padding: 3.5rem 2.5rem; color: #FFFFFF; text-align: center; border: 1.5px solid rgba(212, 175, 55, 0.3); box-shadow: var(--shadow-lg);">
        <h2 style="font-size: 2.2rem; color: #FFFFFF; font-family: var(--font-primary); margin-bottom: 1rem;">
          Speak with an Admissions Counselor
        </h2>
        <p style="max-width: 650px; margin: 0 auto 2rem auto; color: rgba(255, 255, 255, 0.9); font-size: 1.05rem; line-height: 1.7;">
          Have specific questions regarding grade placement, special education, or class schedules? Our admissions team is ready to guide you.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
          <a href="javascript:void(0)" onclick="openCallbackModal()" class="btn btn-primary" style="background-color: var(--color-gold); color: var(--color-navy-dark); font-weight: 700; border: none; padding: 0.85rem 2rem; border-radius: var(--radius-sm); text-decoration: none;">
            Request a Callback &rarr;
          </a>
          <a href="/admissions/enrol-now" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid #FFFFFF; font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Online Application
          </a>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include_once dirname(__FILE__) . '/../includes/footer.php'; ?>
