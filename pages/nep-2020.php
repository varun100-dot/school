<?php
// Zuvio Global School - Dedicated NEP 2020 Page
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// CMS synchronization
if (!isset($_SESSION['mock_academics_cms'])) {
    $_SESSION['mock_academics_cms'] = [];
}
$ac_cms = &$_SESSION['mock_academics_cms'];

$nep = $ac_cms['nep_2020'] ?? [
    'title' => 'NEP 2020 & NCF Compliance',
    'subtitle' => 'National Education Policy 2020 Alignment',
    'desc' => 'In full alignment with the National Education Policy (NEP 2020) and National Curriculum Framework (NCF), Zuvio replaces rote memorization with experiential, discovery-based, and interdisciplinary learning.',
    'pdf_title' => 'National Education Policy 2020 — Ministry of Education, Govt. of India',
    'pdf_url' => '/assets/docs/NEP_2020_Policy_Document.pdf'
];

$page_slug = 'nep-2020';
$seo = [
    'seo_title' => 'NEP 2020 & NCF Compliance | Zuvio Global School',
    'meta_description' => 'Discover how Zuvio Global School implements India’s National Education Policy 2020 (NEP 2020) & NCF: 5+3+3+4 stage design, experiential pedagogy, and holistic 360° assessments.',
    'canonical_url' => BASE_URL . '/nep-2020',
    'og_title' => 'NEP 2020 & NCF Compliance — Zuvio Global School',
    'og_description' => 'Experiential, discovery-based, and interdisciplinary learning mapped to the NEP 2020 5+3+3+4 framework.',
    'og_image' => '/assets/images/about_us_hero.jpg',
    'index_status' => 'index, follow'
];

include_once dirname(__FILE__) . '/../includes/header.php';

render_breadcrumbs([
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Academics', 'url' => '/academics'],
    ['label' => 'NEP 2020']
]);
?>

<main class="nep-2020-page">
  <!-- Hero Section -->
  <section class="section-hero" style="background-color: var(--pastel-blue); padding: 5rem 1.5rem; text-align: center; border-bottom: 1px solid var(--color-border);">
    <div class="container" style="max-width: 850px;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">
        <?php echo h($nep['subtitle'] ?? 'National Education Policy 2020 Alignment'); ?>
      </span>
      <h1 style="font-size: 2.85rem; font-family: var(--font-primary); margin-bottom: 1rem; color: var(--color-navy-dark); font-weight: 700; line-height: 1.2;">
        <?php echo h($nep['title']); ?>
      </h1>
      <p style="font-size: 1.15rem; font-weight: 500; line-height: 1.6; color: var(--color-text); margin: 0 auto; max-width: 740px;">
        <?php echo h($nep['desc']); ?>
      </p>
    </div>
  </section>

  <!-- 5+3+3+4 Stage Architecture -->
  <section class="section" style="background-color: #FFFFFF; padding: 5.5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 4rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Pedagogical Reorganization
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          The 5+3+3+4 Structure at Zuvio
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          How our online curriculum aligns with developmental psychology and national standards.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 2rem;">
        
        <!-- Foundational -->
        <div style="background: var(--color-surface-warm); border-radius: var(--radius-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); padding: 2rem; border-top: 4px solid #10b981;">
          <span style="display: inline-block; background: #e6f9f0; color: #047857; font-weight: 700; font-size: 0.8rem; padding: 0.25rem 0.65rem; border-radius: 4px; margin-bottom: 0.75rem;">
            5 Years &bull; Ages 3–8
          </span>
          <h3 style="font-size: 1.3rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">
            Foundational Stage
          </h3>
          <div style="font-size: 0.85rem; color: var(--color-gold); font-weight: 700; margin-bottom: 0.75rem;">K-KG to Grade 2</div>
          <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; margin: 0;">
            Play-based and activity-driven pedagogy focusing on phonics, early numeracy, socio-emotional interaction, and curiosity without exams.
          </p>
        </div>

        <!-- Preparatory -->
        <div style="background: var(--color-surface-warm); border-radius: var(--radius-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); padding: 2rem; border-top: 4px solid #f59e0b;">
          <span style="display: inline-block; background: #fef3c7; color: #b45309; font-weight: 700; font-size: 0.8rem; padding: 0.25rem 0.65rem; border-radius: 4px; margin-bottom: 0.75rem;">
            3 Years &bull; Ages 8–11
          </span>
          <h3 style="font-size: 1.3rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">
            Preparatory Stage
          </h3>
          <div style="font-size: 0.85rem; color: var(--color-gold); font-weight: 700; margin-bottom: 0.75rem;">Grades 3 to 5</div>
          <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; margin: 0;">
            Transition to structured subject learning with discovery-based mathematics, science inquiry, reading fluencies, and digital literacy.
          </p>
        </div>

        <!-- Middle -->
        <div style="background: var(--color-surface-warm); border-radius: var(--radius-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); padding: 2rem; border-top: 4px solid #3b82f6;">
          <span style="display: inline-block; background: #eff6ff; color: #1d4ed8; font-weight: 700; font-size: 0.8rem; padding: 0.25rem 0.65rem; border-radius: 4px; margin-bottom: 0.75rem;">
            3 Years &bull; Ages 11–14
          </span>
          <h3 style="font-size: 1.3rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">
            Middle Stage
          </h3>
          <div style="font-size: 0.85rem; color: var(--color-gold); font-weight: 700; margin-bottom: 0.75rem;">Grades 6 to 8</div>
          <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; margin: 0;">
            Subject-specialist educators, experiential science, coding, vocational electives, and critical thinking debates.
          </p>
        </div>

        <!-- Secondary -->
        <div style="background: var(--color-surface-warm); border-radius: var(--radius-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); padding: 2rem; border-top: 4px solid #8b5cf6;">
          <span style="display: inline-block; background: #f5f3ff; color: #6d28d9; font-weight: 700; font-size: 0.8rem; padding: 0.25rem 0.65rem; border-radius: 4px; margin-bottom: 0.75rem;">
            4 Years &bull; Ages 14–18
          </span>
          <h3 style="font-size: 1.3rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">
            Secondary Pathway
          </h3>
          <div style="font-size: 0.85rem; color: var(--color-gold); font-weight: 700; margin-bottom: 0.75rem;">High School Preparation</div>
          <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; margin: 0;">
            Multidisciplinary study with no rigid stream boundaries, deep analytical depth, career preparation, and international board readiness.
          </p>
        </div>

      </div>
    </div>
  </section>

  <!-- Key NEP 2020 Tenets in Action -->
  <section class="section" style="background-color: var(--pastel-blue); padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Pedagogical Transformation
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          How Zuvio Implements NEP 2020
        </h2>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.75rem;">
        
        <div style="background: #FFFFFF; border-radius: var(--radius-md); padding: 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm);">
          <div style="font-size: 2rem; margin-bottom: 0.75rem;">🗣️</div>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">Multilingualism</h3>
          <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.6; margin: 0;">
            Three-language formula supporting regional mother tongues alongside English and international modern languages.
          </p>
        </div>

        <div style="background: #FFFFFF; border-radius: var(--radius-md); padding: 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm);">
          <div style="font-size: 2rem; margin-bottom: 0.75rem;">🔬</div>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">Experiential Learning</h3>
          <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.6; margin: 0;">
            Hands-on science experiments, digital simulations, and community project investigations rather than memorizing textbooks.
          </p>
        </div>

        <div style="background: #FFFFFF; border-radius: var(--radius-md); padding: 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm);">
          <div style="font-size: 2rem; margin-bottom: 0.75rem;">📊</div>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">Holistic 360° Assessment</h3>
          <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.6; margin: 0;">
            Formative feedback, self-evaluations, and peer collaboration rubrics tracking cognitive, affective, and psychomotor growth.
          </p>
        </div>

      </div>
    </div>
  </section>

  <!-- Official Policy PDF Download Card -->
  <section class="section" style="background-color: #FFFFFF; padding: 5.5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container" style="max-width: 850px;">
      <div style="background: var(--color-surface-warm); border-radius: var(--radius-lg); padding: 3rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-md); display: flex; align-items: center; justify-content: space-between; gap: 2rem; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
          <div style="width: 70px; height: 70px; border-radius: 12px; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; flex-shrink: 0;">
            📄
          </div>
          <div>
            <span style="font-size: 0.75rem; font-weight: 700; color: #dc2626; text-transform: uppercase; letter-spacing: 1px;">
              Official Document &bull; PDF
            </span>
            <h3 style="font-size: 1.3rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0.25rem 0 0.5rem 0;">
              <?php echo h($nep['pdf_title']); ?>
            </h3>
            <p style="color: var(--color-muted); font-size: 0.88rem; margin: 0;">
              Complete policy framework published by the Government of India.
            </p>
          </div>
        </div>

        <div>
          <a href="<?php echo h($nep['pdf_url']); ?>" download target="_blank" class="btn btn-primary" style="background-color: var(--color-navy); color: #FFFFFF; font-weight: 600; padding: 0.85rem 1.5rem; border-radius: var(--radius-sm); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
            Download Policy PDF &darr;
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Cross Navigation Strip -->
  <section class="section" style="background-color: #FFFFFF; padding: 4.5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div style="background: linear-gradient(135deg, var(--color-navy-dark) 0%, var(--color-navy) 100%); border-radius: var(--radius-lg); padding: 3.5rem 2.5rem; color: #FFFFFF; text-align: center; border: 1.5px solid rgba(212, 175, 55, 0.3); box-shadow: var(--shadow-lg);">
        <h2 style="font-size: 2.2rem; color: #FFFFFF; font-family: var(--font-primary); margin-bottom: 1rem;">
          Discover Our Full Curriculum Insights
        </h2>
        <p style="max-width: 650px; margin: 0 auto 2rem auto; color: rgba(255, 255, 255, 0.9); font-size: 1.05rem; line-height: 1.7;">
          Review our stage-by-stage learning journey, daily routines, screen-time guidelines, and interactive learning tools.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
          <a href="/curriculum" class="btn btn-primary" style="background-color: var(--color-gold); color: var(--color-navy-dark); font-weight: 700; border: none; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Curriculum Guide &rarr;
          </a>
          <a href="/resources" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid #FFFFFF; font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Academic Calendar
          </a>
          <a href="/technology" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid rgba(255, 255, 255, 0.6); font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Learning Technology
          </a>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include_once dirname(__FILE__) . '/../includes/footer.php'; ?>
