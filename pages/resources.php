<?php
// Zuvio Global School - Dedicated Resources Page
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// CMS synchronization
if (!isset($_SESSION['mock_academics_cms'])) {
    $_SESSION['mock_academics_cms'] = [];
}
$ac_cms = &$_SESSION['mock_academics_cms'];

$resources_data = $ac_cms['resources'] ?? [
    'calendar_title' => 'Academic Calendar 2026–27',
    'calendar_desc' => 'Comprehensive term dates, assessment schedules, project submission deadlines, and school holidays.',
    'calendar_pdf' => '/assets/docs/Zuvio_Academic_Calendar_2026_27.pdf'
];

$page_slug = 'resources';
$seo = [
    'seo_title' => 'Academic Resources & Academic Calendar | Zuvio Global School',
    'meta_description' => 'Access Zuvio Global School’s learning materials: Oxford Content Books, digital libraries, printable worksheets, and official Academic Calendar 2026–27 PDF.',
    'canonical_url' => BASE_URL . '/resources',
    'og_title' => 'Academic Resources & Academic Calendar — Zuvio Global School',
    'og_description' => 'Comprehensive academic materials, Oxford books, digital simulations, and downloadable Academic Calendar 2026–27.',
    'og_image' => '/assets/images/about_us_hero.jpg',
    'index_status' => 'index, follow'
];

include_once dirname(__FILE__) . '/../includes/header.php';

render_breadcrumbs([
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Academics', 'url' => '/academics'],
    ['label' => 'Resources']
]);
?>

<main class="resources-page">
  <!-- Hero Section -->
  <section class="section-hero" style="background-color: var(--pastel-blue); padding: 5rem 1.5rem; text-align: center; border-bottom: 1px solid var(--color-border);">
    <div class="container" style="max-width: 850px;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">
        Learning Materials &amp; Repositories
      </span>
      <h1 style="font-size: 2.85rem; font-family: var(--font-primary); margin-bottom: 1rem; color: var(--color-navy-dark); font-weight: 700; line-height: 1.2;">
        Academic Resources
      </h1>
      <p style="font-size: 1.15rem; font-weight: 500; line-height: 1.6; color: var(--color-text); margin: 0 auto; max-width: 740px;">
        Everything students and parents need for academic success — curated textbooks, digital libraries, and official school schedules.
      </p>
    </div>
  </section>

  <!-- Official Academic Calendar Download Showcase -->
  <section class="section" style="background-color: #FFFFFF; padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container" style="max-width: 900px;">
      <div style="background: var(--color-surface-warm); border-radius: var(--radius-lg); padding: 3.5rem 3rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-md); display: flex; align-items: center; justify-content: space-between; gap: 2rem; flex-wrap: wrap; border-left: 6px solid var(--color-gold);">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
          <div style="width: 75px; height: 75px; border-radius: 12px; background: #FFF9E6; color: var(--color-navy-dark); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; flex-shrink: 0; border: 1px solid rgba(212,175,55,0.4);">
            🗓️
          </div>
          <div>
            <span style="font-size: 0.8rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 1.5px;">
              Annual Schedule &bull; Official PDF
            </span>
            <h2 style="font-size: 1.75rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0.25rem 0 0.5rem 0;">
              <?php echo h($resources_data['calendar_title']); ?>
            </h2>
            <p style="color: var(--color-text); font-size: 0.95rem; margin: 0; max-width: 480px; line-height: 1.6;">
              <?php echo h($resources_data['calendar_desc']); ?>
            </p>
          </div>
        </div>

        <div>
          <a href="<?php echo h($resources_data['calendar_pdf']); ?>" download target="_blank" class="btn btn-primary" style="background-color: var(--color-navy); color: #FFFFFF; font-weight: 600; padding: 0.9rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: var(--shadow-sm);">
            Download Academic Calendar &darr;
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Content Books & Publishers -->
  <section class="section" style="background-color: var(--pastel-blue); padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Curated Literature
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          Content Books &amp; Publishing Partners
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          Rigorous materials created by premier global educational institutions.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem;">
        
        <!-- Oxford -->
        <div style="background: #FFFFFF; border-radius: var(--radius-md); padding: 2.25rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); border-top: 4px solid var(--color-navy);">
          <div style="height: 50px; display: flex; align-items: center; margin-bottom: 1rem;">
            <img src="/assets/images/oxford-logo.png" alt="Oxford Quality Logo" style="max-height: 45px; object-fit: contain;">
          </div>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">
            Oxford University Press Materials
          </h3>
          <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.6; margin-bottom: 1rem;">
            Oxford English language readers, thematic inquiry workbooks, and systematic phonics programmes benchmarked against global literacy progressions.
          </p>
          <span style="display: inline-block; background: var(--pastel-blue); color: var(--color-teal); font-weight: 600; font-size: 0.75rem; padding: 0.25rem 0.6rem; border-radius: 4px;">
            Digital + Physical Available
          </span>
        </div>

        <!-- NCERT / CBSE -->
        <div style="background: #FFFFFF; border-radius: var(--radius-md); padding: 2.25rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); border-top: 4px solid var(--color-teal);">
          <div style="font-size: 2rem; margin-bottom: 1rem;">📚</div>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">
            NCERT &amp; CBSE Standard Publications
          </h3>
          <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.6; margin-bottom: 1rem;">
            Official core syllabus textbooks for Mathematics, Environmental Studies, Science, and Social Sciences enriched with custom Zuvio interactive lesson guides.
          </p>
          <span style="display: inline-block; background: var(--pastel-blue); color: var(--color-teal); font-weight: 600; font-size: 0.75rem; padding: 0.25rem 0.6rem; border-radius: 4px;">
            Fully Digital PDFs in LMS
          </span>
        </div>

        <!-- Supplementary -->
        <div style="background: #FFFFFF; border-radius: var(--radius-md); padding: 2.25rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); border-top: 4px solid var(--color-gold);">
          <div style="font-size: 2rem; margin-bottom: 1rem;">💡</div>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">
            Interactive Supplementary Worksheets
          </h3>
          <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.6; margin-bottom: 1rem;">
            Differentiated practice sheets, formative quick-quizzes, and creative writing prompts created by our master teachers for homework and revision.
          </p>
          <span style="display: inline-block; background: #FFF9E6; color: var(--color-navy-dark); font-weight: 600; font-size: 0.75rem; padding: 0.25rem 0.6rem; border-radius: 4px; border: 1px solid rgba(212,175,55,0.4);">
            Weekly Module Updates
          </span>
        </div>

      </div>
    </div>
  </section>

  <!-- Digital Resources & Simulations -->
  <section class="section" style="background-color: #FFFFFF; padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Interactive Exploration
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          Interactive Simulations &amp; Digital Tools
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          Bridging abstract concepts through hands-on virtual modeling and visual discovery.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.75rem;">
        
        <div style="background: var(--color-surface-warm); border-radius: var(--radius-md); padding: 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm);">
          <div style="font-size: 1.8rem; margin-bottom: 0.75rem;">🧪</div>
          <h4 style="font-size: 1.15rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">PhET Interactive Simulations</h4>
          <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; margin: 0;">
            Interactive simulations for circuit building, molecular structures, solar system gravity, and light refraction.
          </p>
        </div>

        <div style="background: var(--color-surface-warm); border-radius: var(--radius-md); padding: 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm);">
          <div style="font-size: 1.8rem; margin-bottom: 0.75rem;">📖</div>
          <h4 style="font-size: 1.15rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">Oxford Reading Club</h4>
          <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; margin: 0;">
            Digital library containing hundreds of graded readers with audio narration, interactive glossaries, and quizzes.
          </p>
        </div>

        <div style="background: var(--color-surface-warm); border-radius: var(--radius-md); padding: 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm);">
          <div style="font-size: 1.8rem; margin-bottom: 0.75rem;">💻</div>
          <h4 style="font-size: 1.15rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">Discovery Coding Sandbox</h4>
          <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; margin: 0;">
            In-browser interactive coding consoles where students write, test, and render Python and Scratch projects in real time.
          </p>
        </div>

        <div style="background: var(--color-surface-warm); border-radius: var(--radius-md); padding: 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm);">
          <div style="font-size: 1.8rem; margin-bottom: 0.75rem;">🎬</div>
          <h4 style="font-size: 1.15rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">Masterclass Archives</h4>
          <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; margin: 0;">
            Curated archive of guest lectures by authors, astronomers, athletes, and environmentalists accessible on-demand.
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
          Explore the Full Academic Experience
        </h2>
        <p style="max-width: 650px; margin: 0 auto 2rem auto; color: rgba(255, 255, 255, 0.9); font-size: 1.05rem; line-height: 1.7;">
          Discover our curriculum stages, digital learning technology, and admissions details.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
          <a href="/curriculum" class="btn btn-primary" style="background-color: var(--color-gold); color: var(--color-navy-dark); font-weight: 700; border: none; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Curriculum Pathways &rarr;
          </a>
          <a href="/technology" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid #FFFFFF; font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Learning Technology
          </a>
          <a href="/admissions" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid rgba(255, 255, 255, 0.6); font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Admissions Process
          </a>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include_once dirname(__FILE__) . '/../includes/footer.php'; ?>
