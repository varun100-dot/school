<?php
// Zuvio Global School - Dedicated Eligibility Page
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// CMS synchronization
if (!isset($_SESSION['mock_admissions_cms'])) {
    $_SESSION['mock_admissions_cms'] = [];
}
$adm_cms = &$_SESSION['mock_admissions_cms'];

$eligibility_data = $adm_cms['eligibility'] ?? [
    'title' => 'Eligibility & Age Criteria',
    'subtitle' => 'Age norms in alignment with NEP 2020 guidelines as of 31st March 2026.',
    'stages' => [
        ['stage' => 'Early Years', 'grades' => 'Nursery – KG', 'age' => '3 – 5+ Years', 'duration' => 'Approx. 2 Hours', 'focus' => 'Play-based, phonics, fine motor skills, sensory discovery'],
        ['stage' => 'Foundation Stage', 'grades' => 'Grades 1 – 2', 'age' => '6 – 7+ Years', 'duration' => 'Approx. 2.5 Hours', 'focus' => 'Foundational literacy, numeracy, discovery-based inquiry'],
        ['stage' => 'Preparatory Stage', 'grades' => 'Grades 3 – 5', 'age' => '8 – 10+ Years', 'duration' => 'Approx. 2.5 Hours', 'focus' => 'Conceptual mathematics, science inquiry, reading fluencies'],
        ['stage' => 'Middle School', 'grades' => 'Grades 6 – 8', 'age' => '11 – 13+ Years', 'duration' => 'Approx. 3 Hours', 'focus' => 'Subject-specialist educators, coding, experimental science, debate']
    ],
    'mid_session_note' => 'Students joining mid-session undergo an initial diagnostic assessment. Teachers identify curricular gaps and provide an individual bridge learning plan to support an effortless transition into the ongoing curriculum.'
];

$page_slug = 'admissions-eligibility';
$seo = [
    'seo_title' => 'Eligibility & Age Criteria — Grade Placement Guidelines | Zuvio Global School',
    'meta_description' => 'Grade placement & age eligibility guidelines aligned with NEP 2020 at Zuvio Global School. Early Years to Grade 8 live class timings and transfer policies.',
    'canonical_url' => BASE_URL . '/admissions/eligibility',
    'og_title' => 'Eligibility & Age Criteria — Zuvio Global School',
    'og_description' => 'Age norms in alignment with NEP 2020 guidelines. Grade-wise live class durations, mid-session admission bridge plans, and international transfer rules.',
    'og_image' => '/assets/images/about_us_hero.jpg',
    'index_status' => 'index, follow'
];

include_once dirname(__FILE__) . '/../includes/header.php';

render_breadcrumbs([
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Admissions', 'url' => '/admissions'],
    ['label' => 'Eligibility']
]);
?>

<main class="admissions-eligibility-page">
  <!-- Hero Section -->
  <section class="section-hero" style="background-color: var(--pastel-blue); padding: 5rem 1.5rem; text-align: center; border-bottom: 1px solid var(--color-border);">
    <div class="container" style="max-width: 850px;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">
        Grade Placement Guidelines
      </span>
      <h1 style="font-size: 2.85rem; font-family: var(--font-primary); margin-bottom: 1rem; color: var(--color-navy-dark); font-weight: 700; line-height: 1.2;">
        <?php echo h($eligibility_data['title']); ?>
      </h1>
      <p style="font-size: 1.15rem; font-weight: 500; line-height: 1.6; color: var(--color-text); margin: 0 auto; max-width: 740px;">
        <?php echo h($eligibility_data['subtitle']); ?>
      </p>
    </div>
  </section>

  <!-- Eligibility & Age Matrix Table -->
  <section class="section" style="background-color: #FFFFFF; padding: 5.5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          National Standards
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          Stage-Wise Placement &amp; Daily Timings
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          Calculated as of 31st March 2026 to ensure developmental readiness and screen-time balance.
        </p>
      </div>

      <div style="background-color: #FFFFFF; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); overflow: hidden; border: 1.5px solid rgba(6, 43, 99, 0.16); max-width: 1000px; margin: 0 auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem;">
          <thead>
            <tr style="background-color: var(--color-navy-dark); color: #FFFFFF; font-family: var(--font-primary);">
              <th style="padding: 1.1rem 1.5rem;">Stage</th>
              <th style="padding: 1.1rem 1.5rem;">Grade / Class</th>
              <th style="padding: 1.1rem 1.5rem;">Recommended Age</th>
              <th style="padding: 1.1rem 1.5rem;">Daily Live Classes</th>
              <th style="padding: 1.1rem 1.5rem;">Pedagogical Focus</th>
            </tr>
          </thead>
          <tbody style="color: var(--color-text);">
            <?php foreach ($eligibility_data['stages'] as $i => $row): 
              $bg = ($i % 2 === 1) ? 'background-color: var(--color-surface-warm);' : '';
            ?>
              <tr style="border-bottom: 1px solid var(--color-border); <?php echo $bg; ?>">
                <td style="padding: 1.15rem 1.5rem; font-weight: 700; color: var(--color-navy);">
                  <?php echo h($row['stage']); ?>
                </td>
                <td style="padding: 1.15rem 1.5rem; font-weight: 600; color: var(--color-teal);">
                  <?php echo h($row['grades']); ?>
                </td>
                <td style="padding: 1.15rem 1.5rem; font-weight: 600;">
                  <?php echo h($row['age']); ?>
                </td>
                <td style="padding: 1.15rem 1.5rem; color: var(--color-text);">
                  <?php echo h($row['duration']); ?>
                </td>
                <td style="padding: 1.15rem 1.5rem; font-size: 0.88rem; color: var(--color-muted);">
                  <?php echo h($row['focus'] ?? 'Core curriculum inquiry'); ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Mid-Session Policy Box -->
      <div style="max-width: 1000px; margin: 2.5rem auto 0 auto; padding: 2rem 2.5rem; background-color: var(--pastel-blue); border-radius: var(--radius-md); border-left: 5px solid var(--color-gold); font-size: 0.95rem; color: var(--color-navy); line-height: 1.7; border: 1.5px solid rgba(6, 43, 99, 0.16); border-left-width: 5px;">
        <h4 style="font-size: 1.15rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0 0 0.5rem 0;">
          Note on Mid-Session / Term 2 Enrolment:
        </h4>
        <p style="margin: 0; color: var(--color-text);">
          <?php echo h($eligibility_data['mid_session_note']); ?>
        </p>
      </div>
    </div>
  </section>

  <!-- International & Transfer Students Guide -->
  <section class="section" style="background-color: var(--pastel-blue); padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Global Mobility
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          Transferring from Other Boards or Overseas
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          Zuvio welcomes students relocating across state, national, or international curriculum boundaries.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.75rem;">
        
        <div style="background: #FFFFFF; border-radius: var(--radius-md); padding: 2.25rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm);">
          <div style="font-size: 2rem; margin-bottom: 0.75rem;">🔄</div>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">From CBSE / ICSE / State Boards</h3>
          <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; margin: 0;">
            Direct grade-for-grade equivalency. Transfer certificates and report cards are honored with seamless continuity in mathematics, languages, and sciences.
          </p>
        </div>

        <div style="background: #FFFFFF; border-radius: var(--radius-md); padding: 2.25rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm);">
          <div style="font-size: 2rem; margin-bottom: 0.75rem;">🌐</div>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">From Cambridge / IB / American Curricula</h3>
          <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; margin: 0;">
            Our academic advisors evaluate term credits and age cohorts. Students transition comfortably with Oxford Quality curriculum resources and digital inquiry tools.
          </p>
        </div>

        <div style="background: #FFFFFF; border-radius: var(--radius-md); padding: 2.25rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm);">
          <div style="font-size: 2rem; margin-bottom: 0.75rem;">🏡</div>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">From Alternative Learning / Homeschooling</h3>
          <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; margin: 0;">
            A compassionate, non-punitive baseline assessment identifies conceptual mastery and creates a personalized grade roadmap without rigid testing pressure.
          </p>
        </div>

      </div>
    </div>
  </section>

  <!-- Cross Navigation Strip -->
  <section class="section" style="background-color: #FFFFFF; padding: 4.5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div style="background: linear-gradient(135deg, var(--color-navy-dark) 0%, var(--color-navy) 100%); border-radius: var(--radius-lg); padding: 3.5rem 2.5rem; color: #FFFFFF; text-align: center; border: 1.5px solid rgba(212, 175, 55, 0.3); box-shadow: var(--shadow-lg);">
        <h2 style="font-size: 2.2rem; color: #FFFFFF; font-family: var(--font-primary); margin-bottom: 1rem;">
          Ready to Begin Your Enrolment?
        </h2>
        <p style="max-width: 650px; margin: 0 auto 2rem auto; color: rgba(255, 255, 255, 0.9); font-size: 1.05rem; line-height: 1.7;">
          Now that you've reviewed the age and placement criteria, submit your online application or explore our all-inclusive fee schedule.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
          <a href="/admissions/enrol-now" class="btn btn-primary" style="background-color: var(--color-gold); color: var(--color-navy-dark); font-weight: 700; border: none; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Enrol Now &rarr;
          </a>
          <a href="/admissions/fees" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid #FFFFFF; font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Fee Structure
          </a>
          <a href="/admissions/calendar" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid rgba(255, 255, 255, 0.6); font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Academic Calendar
          </a>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include_once dirname(__FILE__) . '/../includes/footer.php'; ?>
