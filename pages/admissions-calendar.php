<?php
// Zuvio Global School - Dedicated Academic Calendar Page
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// CMS synchronization
if (!isset($_SESSION['mock_admissions_cms'])) {
    $_SESSION['mock_admissions_cms'] = [];
}
$adm_cms = &$_SESSION['mock_admissions_cms'];

$calendar_data = $adm_cms['calendar'] ?? [
    'title' => 'Academic Calendar 2026–2027',
    'subtitle' => 'Structured two-term academic year aligned with standard Indian and international schooling schedules.',
    'terms' => [
        [
            'term' => 'Term 1: April to September',
            'desc' => 'Curriculum launch, foundational concepts, Oxford themes, mid-term formative reviews, and summer enrichment modules.',
            'milestones' => [
                ['month' => 'April 2026', 'event' => 'New Academic Session Launch, Student & Parent Tech Orientation'],
                ['month' => 'May – June 2026', 'event' => 'Core Subject Inquiries, Summer Enrichment & Coding Bootcamps'],
                ['month' => 'July – August 2026', 'event' => 'Formative Assessment Checkpoint 1, ISSO Virtual Sports Challenges'],
                ['month' => 'September 2026', 'event' => 'Mid-Term Comprehensive Review & Student Progress Portfolios']
            ]
        ],
        [
            'term' => 'Term 2: October to March',
            'desc' => 'Project exhibitions, advanced coding/AI explorations, co-curricular showcases, and end-of-year comprehensive portfolios.',
            'milestones' => [
                ['month' => 'October 2026', 'event' => 'Term 2 Kickoff, Cultural Assemblies & Creative Arts Festival'],
                ['month' => 'November 2026', 'event' => 'Global Olympiads (Science/Math), NEP Experiential Project Exhibitions'],
                ['month' => 'December 2026', 'event' => 'Formative Assessment Checkpoint 2 & Winter Break'],
                ['month' => 'January 2027', 'event' => 'Classes Resume, Future Skills Showcase & Parent-Teacher Dialogue'],
                ['month' => 'February – March 2027', 'event' => 'Summative Annual Portfolios, Graduation & Next-Grade Progression']
            ]
        ]
    ],
    'pdf_title' => 'Official Academic Calendar 2026–27',
    'pdf_url' => '/assets/docs/Zuvio_Academic_Calendar_2026_27.pdf'
];

$page_slug = 'admissions-calendar';
$seo = [
    'seo_title' => 'Academic Calendar 2026–2027 — Key Dates & Terms | Zuvio Global School',
    'meta_description' => 'View the official Zuvio Global School Academic Calendar 2026–2027. Term 1 & Term 2 dates, examination windows, holidays, and downloadable PDF schedule.',
    'canonical_url' => BASE_URL . '/admissions/calendar',
    'og_title' => 'Academic Calendar 2026–2027 — Zuvio Global School',
    'og_description' => 'Download official session calendar, term schedules, holiday lists, and assessment milestones for K–8 online schooling.',
    'og_image' => '/assets/images/about_us_hero.jpg',
    'index_status' => 'index, follow'
];

include_once dirname(__FILE__) . '/../includes/header.php';

render_breadcrumbs([
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Admissions', 'url' => '/admissions'],
    ['label' => 'Calendar']
]);
?>

<main class="admissions-calendar-page">
  <!-- Hero Section -->
  <section class="section-hero" style="background-color: var(--pastel-blue); padding: 5rem 1.5rem; text-align: center; border-bottom: 1px solid var(--color-border);">
    <div class="container" style="max-width: 850px;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">
        Annual Timelines &amp; Schedules
      </span>
      <h1 style="font-size: 2.85rem; font-family: var(--font-primary); margin-bottom: 1rem; color: var(--color-navy-dark); font-weight: 700; line-height: 1.2;">
        <?php echo h($calendar_data['title']); ?>
      </h1>
      <p style="font-size: 1.15rem; font-weight: 500; line-height: 1.6; color: var(--color-text); margin: 0 auto; max-width: 740px;">
        <?php echo h($calendar_data['subtitle']); ?>
      </p>
    </div>
  </section>

  <!-- Download Official PDF Calendar Banner -->
  <section class="section" style="background-color: #FFFFFF; padding: 4.5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container" style="max-width: 900px;">
      <div style="background: var(--color-surface-warm); border-radius: var(--radius-lg); padding: 3rem 2.5rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-md); display: flex; align-items: center; justify-content: space-between; gap: 2rem; flex-wrap: wrap; border-left: 6px solid var(--color-gold);">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
          <div style="width: 70px; height: 70px; border-radius: 12px; background: #FFF9E6; color: var(--color-navy-dark); display: flex; align-items: center; justify-content: center; font-size: 2.2rem; flex-shrink: 0; border: 1px solid rgba(212,175,55,0.4);">
            🗓️
          </div>
          <div>
            <span style="font-size: 0.8rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 1.5px;">
              Official Session Schedule &bull; PDF
            </span>
            <h2 style="font-size: 1.6rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0.25rem 0 0.4rem 0;">
              <?php echo h($calendar_data['pdf_title'] ?? 'Official Academic Calendar 2026–27'); ?>
            </h2>
            <p style="color: var(--color-muted); font-size: 0.9rem; margin: 0;">
              Comprehensive day-by-day term planner, assessment dates, gazetted holidays, and PTM schedules.
            </p>
          </div>
        </div>

        <div>
          <a href="<?php echo h($calendar_data['pdf_url'] ?? '/assets/docs/Zuvio_Academic_Calendar_2026_27.pdf'); ?>" download target="_blank" class="btn btn-primary" style="background-color: var(--color-navy); color: #FFFFFF; font-weight: 600; padding: 0.85rem 1.6rem; border-radius: var(--radius-sm); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: var(--shadow-sm);">
            Download Calendar PDF &darr;
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- 2-Term Breakdown & Milestones -->
  <section class="section" style="background-color: var(--pastel-blue); padding: 5.5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Structured Progression
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          Two-Term Academic Architecture
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          Designed for consistent academic momentum with thoughtful intervals to recharge.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 2.5rem; max-width: 1080px; margin: 0 auto;">
        <?php foreach ($calendar_data['terms'] as $tIndex => $t): ?>
          <div style="background: #FFFFFF; border-radius: var(--radius-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); padding: 2.5rem; border-top: 5px solid <?php echo $tIndex === 0 ? 'var(--color-teal)' : 'var(--color-gold)'; ?>; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              <div style="display: inline-block; background: var(--pastel-blue); color: var(--color-navy); font-weight: 700; font-size: 0.8rem; padding: 0.3rem 0.75rem; border-radius: 4px; margin-bottom: 1rem; text-transform: uppercase;">
                Term <?php echo $tIndex + 1; ?>
              </div>
              <h3 style="font-size: 1.45rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">
                <?php echo h($t['term']); ?>
              </h3>
              <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.6; margin-bottom: 1.75rem;">
                <?php echo h($t['desc']); ?>
              </p>

              <?php if (!empty($t['milestones'])): ?>
                <div style="border-top: 1px solid var(--color-border); padding-top: 1.25rem;">
                  <h4 style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: var(--color-gold); font-weight: 700; margin-bottom: 1rem;">
                    Key Milestones
                  </h4>
                  <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <?php foreach ($t['milestones'] as $m): ?>
                      <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                        <span style="color: var(--color-teal); font-weight: 700; font-size: 0.85rem; min-width: 110px;">
                          <?php echo h($m['month']); ?>:
                        </span>
                        <span style="color: var(--color-text); font-size: 0.88rem; line-height: 1.5;">
                          <?php echo h($m['event']); ?>
                        </span>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Rolling Admissions & Flexibility -->
  <section class="section" style="background-color: #FFFFFF; padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container" style="max-width: 850px;">
      <div style="background: var(--color-surface-warm); border-radius: var(--radius-lg); padding: 3rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); text-align: center;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.5rem;">
          Flexible Entry
        </span>
        <h3 style="font-size: 1.8rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 1rem;">
          Rolling Admissions Throughout the Session
        </h3>
        <p style="color: var(--color-text); font-size: 1.05rem; line-height: 1.7; margin-bottom: 1.5rem;">
          We understand that expat postings, sports competitions, and family relocations don't always align with traditional school starts. Zuvio accepts rolling admissions during both Term 1 and Term 2 with individualized bridge onboarding.
        </p>
        <a href="/admissions/enrol-now" class="btn btn-primary" style="background-color: var(--color-navy); color: #FFFFFF; font-weight: 700; padding: 0.85rem 2rem; border-radius: var(--radius-sm); text-decoration: none;">
          Apply for Mid-Term Enrolment &rarr;
        </a>
      </div>
    </div>
  </section>

  <!-- Cross Navigation Strip -->
  <section class="section" style="background-color: #FFFFFF; padding: 4.5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div style="background: linear-gradient(135deg, var(--color-navy-dark) 0%, var(--color-navy) 100%); border-radius: var(--radius-lg); padding: 3.5rem 2.5rem; color: #FFFFFF; text-align: center; border: 1.5px solid rgba(212, 175, 55, 0.3); box-shadow: var(--shadow-lg);">
        <h2 style="font-size: 2.2rem; color: #FFFFFF; font-family: var(--font-primary); margin-bottom: 1rem;">
          Take the Next Step
        </h2>
        <p style="max-width: 650px; margin: 0 auto 2rem auto; color: rgba(255, 255, 255, 0.9); font-size: 1.05rem; line-height: 1.7;">
          Review our transparent fee structure, check age eligibility, or submit your online application.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
          <a href="/admissions/enrol-now" class="btn btn-primary" style="background-color: var(--color-gold); color: var(--color-navy-dark); font-weight: 700; border: none; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Enrol Now &rarr;
          </a>
          <a href="/admissions/fees" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid #FFFFFF; font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Fee Structure
          </a>
          <a href="/admissions/eligibility" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid rgba(255, 255, 255, 0.6); font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Check Eligibility
          </a>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include_once dirname(__FILE__) . '/../includes/footer.php'; ?>
