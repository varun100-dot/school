<?php
// Zuvio Global School - Dedicated Electives Page
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// CMS synchronization
if (!isset($_SESSION['mock_academics_cms'])) {
    $_SESSION['mock_academics_cms'] = [];
}
$ac_cms = &$_SESSION['mock_academics_cms'];

$electives = $ac_cms['electives'] ?? [
    'regional_languages' => ['Hindi', 'Sanskrit', 'Urdu', 'Tamil', 'Telugu', 'Kannada', 'Marathi', 'Bengali'],
    'foreign_languages' => ['French', 'Spanish', 'German', 'Arabic', 'Mandarin'],
    'future_skills' => [
        ['name' => 'Coding & Robotics', 'desc' => 'Block programming, Python fundamentals, logic building, and computational thinking.'],
        ['name' => 'Abacus & Rubik\'s Cube', 'desc' => 'Mental arithmetic speed, spatial memory, 3D visualization, and focus concentration.'],
        ['name' => 'Public Speaking & Debate', 'desc' => 'Articulating ideas with poise, persuasive rhetoric, parliamentary debate, and voice modulation.'],
        ['name' => 'Creative Writing & Media', 'desc' => 'Authoring short stories, poetry, journalistic reporting, and digital publishing.'],
        ['name' => 'Financial Literacy', 'desc' => 'Foundational concepts of money, saving, budgeting, smart investing, and ethical commerce.'],
        ['name' => 'Yoga & Mindfulness', 'desc' => 'Breathing exercises, physical postures, somatic awareness, and emotional regulation techniques.']
    ]
];

$page_slug = 'electives';
$seo = [
    'seo_title' => 'Electives, Languages & Future Skills | Zuvio Global School',
    'meta_description' => 'Explore Zuvio’s rich elective offerings: 8 Indian regional languages, 5 world languages, Coding & Robotics, Public Speaking, and Financial Literacy.',
    'canonical_url' => BASE_URL . '/electives',
    'og_title' => 'Electives, Languages & Future Skills — Zuvio Global School',
    'og_description' => 'Discover regional languages, foreign languages, and 21st-century future skill electives integrated into our K–8 curriculum.',
    'og_image' => '/assets/images/about_us_hero.jpg',
    'index_status' => 'index, follow'
];

include_once dirname(__FILE__) . '/../includes/header.php';

render_breadcrumbs([
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Academics', 'url' => '/academics'],
    ['label' => 'Electives']
]);
?>

<main class="electives-page">
  <!-- Hero Section -->
  <section class="section-hero" style="background-color: var(--pastel-blue); padding: 5rem 1.5rem; text-align: center; border-bottom: 1px solid var(--color-border);">
    <div class="container" style="max-width: 850px;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">
        Holistic Development
      </span>
      <h1 style="font-size: 2.85rem; font-family: var(--font-primary); margin-bottom: 1rem; color: var(--color-navy-dark); font-weight: 700; line-height: 1.2;">
        Electives &amp; Future Skills
      </h1>
      <p style="font-size: 1.15rem; font-weight: 500; line-height: 1.6; color: var(--color-text); margin: 0 auto; max-width: 740px;">
        Expanding children's cognitive horizons through Indian languages, foreign languages, and hands-on 21st-century skill clubs.
      </p>
    </div>
  </section>

  <!-- Regional & Foreign Languages -->
  <section class="section" style="background-color: #FFFFFF; padding: 5.5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 4rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Linguistic Breadth
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          Regional &amp; Foreign Language Electives
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          Preserving cultural identity while cultivating confident multilingual global citizens.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2.5rem;">
        
        <!-- Regional Languages Card -->
        <div style="background: var(--color-surface-warm); border-radius: var(--radius-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); padding: 2.5rem; border-top: 4px solid var(--color-teal);">
          <div style="font-size: 2rem; margin-bottom: 0.75rem;">🇮🇳</div>
          <h3 style="font-size: 1.4rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">
            Indian Regional Languages
          </h3>
          <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.6; margin-bottom: 1.5rem;">
            Offered in accordance with NEP 2020 mother-tongue and three-language recommendations. Taught by native-fluent educators with cultural context.
          </p>

          <div style="display: flex; flex-wrap: wrap; gap: 0.65rem;">
            <?php foreach ($electives['regional_languages'] as $lang): ?>
              <span style="background: #FFFFFF; color: var(--color-navy); border: 1.5px solid rgba(6, 43, 99, 0.16); padding: 0.45rem 0.9rem; border-radius: 20px; font-weight: 600; font-size: 0.88rem;">
                <?php echo h($lang); ?>
              </span>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Foreign Languages Card -->
        <div style="background: var(--color-surface-warm); border-radius: var(--radius-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); padding: 2.5rem; border-top: 4px solid var(--color-gold);">
          <div style="font-size: 2rem; margin-bottom: 0.75rem;">🌍</div>
          <h3 style="font-size: 1.4rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">
            World &amp; International Languages
          </h3>
          <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.6; margin-bottom: 1.5rem;">
            CEFR-aligned conversational curriculums developing pronunciation, conversational ease, reading comprehension, and cultural curiosity.
          </p>

          <div style="display: flex; flex-wrap: wrap; gap: 0.65rem;">
            <?php foreach ($electives['foreign_languages'] as $lang): ?>
              <span style="background: #FFFFFF; color: var(--color-navy); border: 1.5px solid rgba(6, 43, 99, 0.16); padding: 0.45rem 0.9rem; border-radius: 20px; font-weight: 600; font-size: 0.88rem;">
                <?php echo h($lang); ?>
              </span>
            <?php endforeach; ?>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- Future Skill Clubs (6) -->
  <section class="section" style="background-color: var(--pastel-blue); padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          21st-Century Competencies
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          Future-Ready Enrichment Clubs
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          Hands-on experiential modules that build critical thinking, creative expression, and real-world acumen.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.75rem;">
        <?php foreach ($electives['future_skills'] as $skill): ?>
          <div style="background-color: #FFFFFF; border-radius: var(--radius-md); padding: 2.25rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <span style="color: var(--color-gold); font-size: 1.1rem;">★</span>
                <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0;">
                  <?php echo h($skill['name']); ?>
                </h3>
              </div>
              <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.6; margin: 0;">
                <?php echo h($skill['desc']); ?>
              </p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Scheduling & Workload Balance -->
  <section class="section" style="background-color: #FFFFFF; padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container" style="max-width: 850px;">
      <div style="background: var(--color-surface-warm); border-radius: var(--radius-lg); padding: 3rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); text-align: center;">
        <h3 style="font-size: 1.8rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 1rem;">
          Balanced by Design, Never Overburdening
        </h3>
        <p style="color: var(--color-text); font-size: 1.05rem; line-height: 1.7; margin-bottom: 1.5rem;">
          Electives at Zuvio are intentionally integrated into regular weekly timetables with flexible afternoon or weekend club formats. This ensures students pursue their individual passions without academic burnout or prolonged screen fatigue.
        </p>
        <div style="display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap; color: var(--color-navy); font-weight: 600; font-size: 0.95rem;">
          <div><span style="color: var(--color-teal); font-size: 1.2rem;">✓</span> Max 2–3 hours weekly</div>
          <div><span style="color: var(--color-teal); font-size: 1.2rem;">✓</span> Project &amp; Portfolio based</div>
          <div><span style="color: var(--color-teal); font-size: 1.2rem;">✓</span> Certificate of Mastery</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Cross Navigation Strip -->
  <section class="section" style="background-color: #FFFFFF; padding: 4.5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div style="background: linear-gradient(135deg, var(--color-navy-dark) 0%, var(--color-navy) 100%); border-radius: var(--radius-lg); padding: 3.5rem 2.5rem; color: #FFFFFF; text-align: center; border: 1.5px solid rgba(212, 175, 55, 0.3); box-shadow: var(--shadow-lg);">
        <h2 style="font-size: 2.2rem; color: #FFFFFF; font-family: var(--font-primary); margin-bottom: 1rem;">
          Explore Complete Academic Pathways
        </h2>
        <p style="max-width: 650px; margin: 0 auto 2rem auto; color: rgba(255, 255, 255, 0.9); font-size: 1.05rem; line-height: 1.7;">
          See how our electives pair seamlessly with our core CBSE, NEP 2020, and international stage-wise curriculum.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
          <a href="/curriculum" class="btn btn-primary" style="background-color: var(--color-gold); color: var(--color-navy-dark); font-weight: 700; border: none; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Curriculum Guide &rarr;
          </a>
          <a href="/nep-2020" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid #FFFFFF; font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            NEP 2020 Alignment
          </a>
          <a href="/resources" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid rgba(255, 255, 255, 0.6); font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Academic Calendar
          </a>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include_once dirname(__FILE__) . '/../includes/footer.php'; ?>
