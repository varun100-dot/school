<?php
// Zuvio Global School - Dedicated Fee Structure Page
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// CMS synchronization
if (!isset($_SESSION['mock_admissions_cms'])) {
    $_SESSION['mock_admissions_cms'] = [];
}
$adm_cms = &$_SESSION['mock_admissions_cms'];

$fees_data = $adm_cms['fees'] ?? [
    'title' => 'Fee Structure 2026–2027',
    'subtitle' => 'Transparent, predictable tuition without hidden infrastructural overheads or unexpected surcharges.',
    'tiers' => [
        ['grade' => 'Pre Primary', 'reg_fee' => '500', 'adm_fee' => '2,500', 'tuition_q' => '15,000', 'total_annual' => '66,550'],
        ['grade' => 'Kindergarten', 'reg_fee' => '500', 'adm_fee' => '2,500', 'tuition_q' => '16,000', 'total_annual' => '70,950'],
        ['grade' => 'Grade 1–2', 'reg_fee' => '500', 'adm_fee' => '2,500', 'tuition_q' => '19,000', 'total_annual' => '85,250'],
        ['grade' => 'Grade 3–5', 'reg_fee' => '500', 'adm_fee' => '2,500', 'tuition_q' => '21,000', 'total_annual' => '95,150'],
        ['grade' => 'Grade 6–8', 'reg_fee' => '500', 'adm_fee' => '2,500', 'tuition_q' => '21,000', 'total_annual' => '95,150']
    ],
    'payment_notes' => 'Fees to be paid on a quarterly basis between 1st to 10th of the quarter. First Quarter has to be paid at the time of admission. Q1: April–June | Q2: July–September | Q3: October–December | Q4: January–March.',
    'complimentary_note' => 'BOOKS / LMS / ERP — COMPLIMENTARY | High-quality physical books, advanced LMS access, and ERP support are included in the annual fees.',
    'pdf_url' => '/assets/docs/Zuvio_Academic_Calendar_2026_27.pdf'
];

$page_slug = 'admissions-fees';
$seo = [
    'seo_title' => 'Fee Structure 2026–2027 — Transparent Tuition | Zuvio Global School',
    'meta_description' => 'Official fee structure for Academic Year 2026–2027 at Zuvio Global School. Pre-Primary to Grade 8 quarterly tuition, registration, and complimentary books & LMS.',
    'canonical_url' => BASE_URL . '/admissions/fees',
    'og_title' => 'Fee Structure 2026–2027 — Zuvio Global School',
    'og_description' => 'Transparent, all-inclusive tuition without hidden overheads. Official fee matrix for Pre-Primary to Grade 8.',
    'og_image' => '/assets/images/about_us_hero.jpg',
    'index_status' => 'index, follow'
];

include_once dirname(__FILE__) . '/../includes/header.php';

render_breadcrumbs([
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Admissions', 'url' => '/admissions'],
    ['label' => 'Fees']
]);
?>

<main class="admissions-fees-page">
  <!-- Hero Section -->
  <section class="section-hero" style="background-color: var(--pastel-blue); padding: 5rem 1.5rem; text-align: center; border-bottom: 1px solid var(--color-border);">
    <div class="container" style="max-width: 850px;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">
        Transparent Investment
      </span>
      <h1 style="font-size: 2.85rem; font-family: var(--font-primary); margin-bottom: 1rem; color: var(--color-navy-dark); font-weight: 700; line-height: 1.2;">
        <?php echo h($fees_data['title']); ?>
      </h1>
      <p style="font-size: 1.15rem; font-weight: 500; line-height: 1.6; color: var(--color-text); margin: 0 auto; max-width: 740px;">
        <?php echo h($fees_data['subtitle']); ?>
      </p>
    </div>
  </section>

  <!-- Official Fee Structure Table -->
  <section class="section" style="background-color: #FFFFFF; padding: 5.5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Approved Tuition Schedule
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          Pre-Primary to Grade 8 Fee Matrix
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          In partnership with Oxford University Press • Learning Beyond Boundaries
        </p>
      </div>

      <div style="background-color: #FFFFFF; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); overflow-x: auto; border: 1.5px solid rgba(6, 43, 99, 0.16); max-width: 1060px; margin: 0 auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem; min-width: 700px;">
          <thead>
            <tr style="background-color: var(--color-navy-dark); color: #FFFFFF; font-family: var(--font-primary);">
              <th style="padding: 1.15rem 1.5rem;">Grade / Level</th>
              <th style="padding: 1.15rem 1.5rem;">Registration Fee<br><span style="font-size: 0.75rem; opacity: 0.85; font-weight: normal;">(One Time)</span></th>
              <th style="padding: 1.15rem 1.5rem;">Admission Fee<br><span style="font-size: 0.75rem; opacity: 0.85; font-weight: normal;">(One Time)</span></th>
              <th style="padding: 1.15rem 1.5rem;">Tuition Fees<br><span style="font-size: 0.75rem; opacity: 0.85; font-weight: normal;">(Per Quarter Q1–Q4)</span></th>
              <th style="padding: 1.15rem 1.5rem;">Total Fees<br><span style="font-size: 0.75rem; opacity: 0.85; font-weight: normal;">(Annual Total)</span></th>
            </tr>
          </thead>
          <tbody style="color: var(--color-text);">
            <?php foreach ($fees_data['tiers'] as $i => $row): 
              $bg = ($i % 2 === 1) ? 'background-color: var(--color-surface-warm);' : '';
            ?>
              <tr style="border-bottom: 1px solid var(--color-border); <?php echo $bg; ?>">
                <td style="padding: 1.15rem 1.5rem; font-weight: 700; color: var(--color-navy);">
                  <?php echo h($row['grade']); ?>
                </td>
                <td style="padding: 1.15rem 1.5rem; font-weight: 600;">
                  ₹<?php echo h($row['reg_fee']); ?>
                </td>
                <td style="padding: 1.15rem 1.5rem; font-weight: 600;">
                  ₹<?php echo h($row['adm_fee']); ?>
                </td>
                <td style="padding: 1.15rem 1.5rem; font-weight: 700; color: var(--color-teal);">
                  ₹<?php echo h($row['tuition_q']); ?>
                </td>
                <td style="padding: 1.15rem 1.5rem; font-weight: 800; color: var(--color-navy-dark); font-size: 1.05rem;">
                  ₹<?php echo h($row['total_annual']); ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Payment Schedule Note -->
      <div style="max-width: 1060px; margin: 2rem auto 0 auto; padding: 1.5rem 2rem; background-color: var(--color-surface-warm); border-radius: var(--radius-md); border-left: 5px solid var(--color-gold); font-size: 0.92rem; color: var(--color-navy); line-height: 1.6; border: 1.5px solid rgba(6, 43, 99, 0.16); border-left-width: 5px;">
        <strong style="color: var(--color-navy); font-size: 0.95rem;">Payment Terms:</strong>
        <p style="margin: 0.25rem 0 0 0; color: var(--color-text);">
          <?php echo h($fees_data['payment_notes']); ?>
        </p>
      </div>

      <!-- Complimentary Books / LMS / ERP Callout Banner -->
      <div style="max-width: 1060px; margin: 1.5rem auto 0 auto; padding: 1.5rem 2rem; background-color: #e6f9f0; border-radius: var(--radius-md); border: 1.5px solid #a7f3d0; border-left: 5px solid #059669; font-size: 0.95rem; color: #065f46; display: flex; align-items: center; gap: 1rem;">
        <span style="font-size: 1.75rem; flex-shrink: 0;">🎁</span>
        <div>
          <strong style="font-size: 1.05rem; display: block; margin-bottom: 0.2rem;">BOOKS / LMS / ERP — COMPLIMENTARY</strong>
          <span style="color: #047857; font-size: 0.92rem; line-height: 1.5;">
            High-quality physical/digital books, Oxford learning materials, advanced LMS access, and ERP support are included in the annual fees without separate technology surcharges.
          </span>
        </div>
      </div>
    </div>
  </section>

  <!-- Value & Transparency Section -->
  <section class="section" style="background-color: var(--pastel-blue); padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          What's Included
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          The Value of a Zuvio Education
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          Investing in your child's global future with complete financial clarity.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.75rem;">
        
        <div style="background: #FFFFFF; border-radius: var(--radius-md); padding: 2.25rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm);">
          <div style="font-size: 2rem; margin-bottom: 0.75rem;">💻</div>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">Small Live Cohorts</h3>
          <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; margin: 0;">
            100% live teacher interaction in intimate 1:15–1:20 classrooms. Never pre-recorded video lectures or passive automated screens.
          </p>
        </div>

        <div style="background: #FFFFFF; border-radius: var(--radius-md); padding: 2.25rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm);">
          <div style="font-size: 2rem; margin-bottom: 0.75rem;">📚</div>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">Oxford Learning Materials</h3>
          <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; margin: 0;">
            World-class curriculum resources, thematic workbooks, and Oxford Reading Club digital library access included at no extra cost.
          </p>
        </div>

        <div style="background: #FFFFFF; border-radius: var(--radius-md); padding: 2.25rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm);">
          <div style="font-size: 2rem; margin-bottom: 0.75rem;">🚫</div>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">Zero Hidden Surcharges</h3>
          <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; margin: 0;">
            No campus maintenance funds, transport charges, uniform premiums, or building construction levies found in conventional brick-and-mortar schools.
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
          Ready to Apply for 2026–2027?
        </h2>
        <p style="max-width: 650px; margin: 0 auto 2rem auto; color: rgba(255, 255, 255, 0.9); font-size: 1.05rem; line-height: 1.7;">
          Submit your application today to lock in your child’s preferred live cohort time slot.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
          <a href="/admissions/enrol-now" class="btn btn-primary" style="background-color: var(--color-gold); color: var(--color-navy-dark); font-weight: 700; border: none; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Enrol Now &rarr;
          </a>
          <a href="/admissions/eligibility" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid #FFFFFF; font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Check Eligibility
          </a>
          <a href="/faq" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid rgba(255, 255, 255, 0.6); font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Parent FAQs
          </a>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include_once dirname(__FILE__) . '/../includes/footer.php'; ?>
