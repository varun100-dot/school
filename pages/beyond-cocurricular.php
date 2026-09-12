<?php
// Zuvio Global School - Dedicated Co-curricular & Clubs Page
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// Initialize Mock CMS session store for Beyond if not present
if (!isset($_SESSION['mock_beyond_cms'])) {
    $_SESSION['mock_beyond_cms'] = [];
}
$beyond_cms = &$_SESSION['mock_beyond_cms'];

// Co-curricular Clubs Data from Reference Document Page 59 & 61
$default_clubs = [
    [
        'id' => 1,
        'title' => 'Classical Dance',
        'stage' => 'Preparatory & Middle School',
        'stage_key' => 'prep_mid',
        'desc' => 'Bharatnatyam, Kathak and classical dance training conducted by trained classical artists. Focuses on rhythm, expression, and cultural heritage.',
        'icon' => '💃',
        'schedule' => 'Twice weekly live cohort',
        'is_published' => 1
    ],
    [
        'id' => 2,
        'title' => 'Drawing & Painting',
        'stage' => 'All Stages (KG to Grade 8)',
        'stage_key' => 'all',
        'desc' => 'Sketching, watercolour, acrylic and digital art guided by certified visual artists. Encourages self-expression, color theory, and spatial creativity.',
        'icon' => '🎨',
        'schedule' => 'Weekly live studio session',
        'is_published' => 1
    ],
    [
        'id' => 3,
        'title' => 'Public Speaking & Debate',
        'stage' => 'Preparatory & Middle School',
        'stage_key' => 'prep_mid',
        'desc' => 'Debate, elocution, presentation and impromptu speech skills building confident, articulate communicators and future leaders.',
        'icon' => '🎙️',
        'schedule' => 'Weekly live forensics meet',
        'is_published' => 1
    ],
    [
        'id' => 4,
        'title' => 'Rubik’s Cube Club',
        'stage' => 'All Stages (Age 6+)',
        'stage_key' => 'all',
        'desc' => 'Speed-solving methods from beginner layer-by-layer to advanced CFOP algorithms. Develops concentration, memory, and calm problem-solving.',
        'icon' => '🧩',
        'schedule' => 'Weekly speed-solving workshop',
        'is_published' => 1
    ],
    [
        'id' => 5,
        'title' => 'Western Dance',
        'stage' => 'All Stages (KG to Grade 8)',
        'stage_key' => 'all',
        'desc' => 'Hip-hop, contemporary and freestyle dance for all age groups. Enhances stamina, coordination, musicality, and stage confidence.',
        'icon' => '🕺',
        'schedule' => 'Twice weekly active movement',
        'is_published' => 1
    ],
    [
        'id' => 6,
        'title' => 'Yoga & Mindfulness',
        'stage' => 'All Stages (KG to Grade 8)',
        'stage_key' => 'all',
        'desc' => 'Daily guided asanas, pranayama breathing exercises, and mindfulness techniques for physical flexibility, posture, and emotional wellness.',
        'icon' => '🧘',
        'schedule' => 'Morning wellness sessions',
        'is_published' => 1
    ],
    [
        'id' => 7,
        'title' => 'Strategic Chess Club',
        'stage' => 'Preparatory & Middle School',
        'stage_key' => 'prep_mid',
        'desc' => 'Tactical problem-solving, opening strategies, endgame calculation, and competitive tournament preparation with rated coaches.',
        'icon' => '♟️',
        'schedule' => 'Weekly tournaments & review',
        'is_published' => 1
    ],
    [
        'id' => 8,
        'title' => 'Vocal Music',
        'stage' => 'All Stages (KG to Grade 8)',
        'stage_key' => 'all',
        'desc' => 'Hindustani classical and Western vocal training from beginner pitch matching to advanced melodic improvisation and performance.',
        'icon' => '🎵',
        'schedule' => 'Twice weekly vocal lab',
        'is_published' => 1
    ],
    [
        'id' => 9,
        'title' => 'Coding & App Development',
        'stage' => 'Grades 1 to 8',
        'stage_key' => 'prep_mid',
        'desc' => 'Block-based Scratch programming, Python syntax, web development, and algorithmic logic. Students create games, stories, and apps.',
        'icon' => '💻',
        'schedule' => 'Weekly project-based lab',
        'is_published' => 1
    ],
    [
        'id' => 10,
        'title' => 'Robotics & AI Explorers',
        'stage' => 'Middle School (Grades 6–8)',
        'stage_key' => 'middle',
        'desc' => 'Hands-on robotics kits, sensors, AI prompt engineering, machine learning concepts, and STEM innovation challenges.',
        'icon' => '🤖',
        'schedule' => 'Weekly innovation challenge',
        'is_published' => 1
    ],
    [
        'id' => 11,
        'title' => 'Foreign & World Languages',
        'stage' => 'Preparatory & Middle School',
        'stage_key' => 'prep_mid',
        'desc' => 'Conversational French, German, Spanish, and Sanskrit taught by certified native-level educators with cultural immersion.',
        'icon' => '🌍',
        'schedule' => 'Twice weekly language circle',
        'is_published' => 1
    ],
    [
        'id' => 12,
        'title' => 'Phonics & Early Reading',
        'stage' => 'Foundational Stage (Nursery to Grade 2)',
        'stage_key' => 'foundational',
        'desc' => 'Structured synthetic phonics programme developing phonemic awareness, blending fluency, and early reading comprehension from age 3+.',
        'icon' => '📚',
        'schedule' => '3x weekly reading circles',
        'is_published' => 1
    ],
    [
        'id' => 13,
        'title' => 'Guitar & Instrumental Music',
        'stage' => 'Preparatory & Middle School',
        'stage_key' => 'prep_mid',
        'desc' => 'Acoustic and electric guitar fundamentals, chord progressions, strumming rhythms, tab reading, and ensemble performance.',
        'icon' => '🎸',
        'schedule' => 'Weekly instrumental masterclass',
        'is_published' => 1
    ],
    [
        'id' => 14,
        'title' => 'Abacus & Mental Arithmetic',
        'stage' => 'Foundational & Preparatory (Ages 4–11)',
        'stage_key' => 'foundational',
        'desc' => 'Speed calculation and mental arithmetic using traditional Japanese Soroban abacus methods to cultivate visualization and numerical agility.',
        'icon' => '🧮',
        'schedule' => 'Twice weekly practice cohorts',
        'is_published' => 1
    ]
];

$clubs_list = $beyond_cms['cocurricular_clubs'] ?? $default_clubs;

// Hybrid Experiential Campus Data from Reference Document Page 60
$hybrid_campus_facets = $beyond_cms['hybrid_campus'] ?? [
    [
        'title' => 'Practical Experiments & Interactive Projects',
        'desc' => 'Guided hands-on project kits shipped directly to families for interactive experiments that reinforce real-world scientific discovery.',
        'icon' => '🔬'
    ],
    [
        'title' => 'AR-VR Astronomy & Immersive Simulations',
        'desc' => 'Augmented reality planetarium experiences, digital cosmos exploration, and interactive simulations that bring abstract concepts to life.',
        'icon' => '🌌'
    ],
    [
        'title' => 'Robotics & Innovation Socialization',
        'desc' => 'Hands-on cohort builds and peer collaboration where young creators build automated systems and share working prototypes.',
        'icon' => '⚙️'
    ],
    [
        'title' => 'Creative Play & Discovery for Younger Learners',
        'desc' => 'Sensory exploration, motor-skill development, storytelling sessions, and tactile craft activities designed specifically for foundational years.',
        'icon' => '🧸'
    ],
    [
        'title' => 'Collaborative Meets & Regional Hubs',
        'desc' => 'Organised regional physical gatherings, local sports meets, and peer community field experiences connecting online classmates in person.',
        'icon' => '🤝'
    ]
];

$seo = [
    'seo_title' => 'Co-Curricular & Global Clubs | Zuvio Global School',
    'meta_description' => 'Explore 15+ co-curricular clubs, arts, chess, robotics, debate and the Hybrid Experiential Campus at Zuvio Global School. Stage-wise enrichment for curious minds.',
    'canonical_url' => 'https://zuvioglobalschool.com/beyond/co-curricular',
    'og_title' => 'Co-Curricular & Global Clubs — Zuvio Global School',
    'og_description' => '15+ Activities Beyond the Classroom. From classical dance to robotics and competitive chess, nurturing every child’s individual spark.',
    'og_image' => '/assets/images/Students learning in classroom.png'
];

$breadcrumbs = [
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Beyond', 'url' => '/beyond'],
    ['label' => 'Co-curricular & Clubs']
];

$page_slug = 'beyond-cocurricular';
include_once dirname(__FILE__) . '/../includes/header.php';

render_breadcrumbs($breadcrumbs);
?>

<main class="page-main">

  <!-- Hero Section -->
  <section class="section" style="background: linear-gradient(135deg, var(--color-navy-dark) 0%, var(--color-navy) 100%); color: #FFFFFF; padding: 5rem 0 4.5rem 0; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 350px; height: 350px; background: radial-gradient(circle, rgba(212, 175, 55, 0.15) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>
    <div class="container text-center" style="position: relative; z-index: 2; max-width: 850px; margin: 0 auto;">
      <span style="display: inline-block; background-color: var(--color-gold); color: var(--color-navy-dark); font-size: 0.82rem; font-weight: 800; padding: 0.35rem 1rem; border-radius: 20px; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 1.25rem;">
        Global Clubs &bull; Co-Curricular
      </span>
      <h1 style="font-size: 2.85rem; font-family: var(--font-primary); color: #FFFFFF; line-height: 1.2; margin-bottom: 1.25rem;">
        15+ Activities Beyond the Classroom
      </h1>
      <p style="font-size: 1.15rem; color: rgba(255, 255, 255, 0.9); line-height: 1.7; margin-bottom: 2rem;">
        Every child has a spark. Our comprehensive co-curricular programme is designed to discover, nurture, and elevate it across arts, strategy, technology, and physical wellness.
      </p>
      <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
        <a href="#clubs-grid" class="btn btn-primary" style="background-color: var(--color-gold); color: var(--color-navy-dark); font-weight: 700; border: none; padding: 0.85rem 2rem; border-radius: var(--radius-sm); text-decoration: none;">
          Browse All Clubs &darr;
        </a>
        <a href="#hybrid-campus" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid #FFFFFF; font-weight: 600; padding: 0.85rem 2rem; border-radius: var(--radius-sm); text-decoration: none;">
          Hybrid Experiential Campus
        </a>
      </div>
    </div>
  </section>

  <!-- Clubs Filter & Grid Section -->
  <section id="clubs-grid" class="section" style="background-color: #FFFFFF; padding: 5rem 0;">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 3rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Stage-Wise Structure</span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          Explore Co-Curricular Clubs
        </h2>
        <p style="color: var(--color-muted); font-size: 1.05rem; line-height: 1.6; margin-top: 0.75rem;">
          Conducted by specialist artists, master coaches, and industry mentors in safe, live interactive small-group cohorts.
        </p>

        <!-- Stage Filter Buttons -->
        <div style="display: flex; gap: 0.5rem; justify-content: center; flex-wrap: wrap; margin-top: 2rem;">
          <button class="stage-filter-btn active" onclick="filterClubs('all', this)">All Clubs (14)</button>
          <button class="stage-filter-btn" onclick="filterClubs('foundational', this)">Foundational Stage (KG–Gr 2)</button>
          <button class="stage-filter-btn" onclick="filterClubs('prep_mid', this)">Preparatory & Middle (Gr 3–8)</button>
          <button class="stage-filter-btn" onclick="filterClubs('middle', this)">Middle School (Gr 6–8)</button>
        </div>
      </div>

      <!-- Grid of Clubs -->
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.75rem;">
        <?php foreach ($clubs_list as $club): ?>
          <?php if (!empty($club['is_published'])): ?>
            <div class="club-card" data-stage="<?php echo h($club['stage_key'] ?? 'all'); ?>" style="background: #FFFFFF; border-radius: var(--radius-lg); padding: 2.25rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.2s, box-shadow 0.2s;">
              <div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.25rem;">
                  <span style="font-size: 2.4rem; line-height: 1;"><?php echo h($club['icon']); ?></span>
                  <span style="background-color: var(--color-surface-warm); color: var(--color-navy); border: 1px solid rgba(6, 43, 99, 0.12); font-size: 0.75rem; font-weight: 700; padding: 0.3rem 0.75rem; border-radius: 12px; text-transform: uppercase;">
                    <?php echo h($club['stage']); ?>
                  </span>
                </div>
                <h3 style="font-size: 1.35rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.75rem; font-weight: 700;">
                  <?php echo h($club['title']); ?>
                </h3>
                <p style="color: var(--color-text); font-size: 0.92rem; line-height: 1.6; margin-bottom: 1.25rem;">
                  <?php echo h($club['desc']); ?>
                </p>
              </div>
              <div style="border-top: 1px dashed rgba(6, 43, 99, 0.12); padding-top: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.82rem; color: var(--color-muted); font-weight: 500;">
                  🗓️ <?php echo h($club['schedule']); ?>
                </span>
                <a href="javascript:openCallbackModal()" style="color: var(--color-teal); font-weight: 700; font-size: 0.85rem; text-decoration: none;">
                  Enquire &rarr;
                </a>
              </div>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Hybrid Experiential Campus Section (Source Page 60) -->
  <section id="hybrid-campus" class="section" style="background-color: var(--color-surface-warm); padding: 5.5rem 0; border-top: 1px solid var(--color-border); border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 800px; margin: 0 auto 3.5rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">The Best of Both Worlds</span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          Hybrid Experiential Campus
        </h2>
        <p style="color: var(--color-muted); font-size: 1.05rem; line-height: 1.6; margin-top: 0.75rem;">
          Zuvio bridges the digital classroom with real-world, tangible experiences. Our hybrid model incorporates hands-on experiments, astronomy simulations, cohort workshops, and city-level physical meetups.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.75rem;">
        <?php foreach ($hybrid_campus_facets as $facet): ?>
          <div style="background: #FFFFFF; border-radius: var(--radius-lg); padding: 2.25rem 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              <div style="font-size: 2.5rem; margin-bottom: 1rem;"><?php echo h($facet['icon']); ?></div>
              <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); font-weight: 700; margin-bottom: 0.75rem;">
                <?php echo h($facet['title']); ?>
              </h3>
              <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; margin: 0;">
                <?php echo h($facet['desc']); ?>
              </p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div style="margin-top: 3.5rem; background: #FFFFFF; border-radius: var(--radius-lg); padding: 2.5rem; border: 1.5px solid rgba(212, 175, 55, 0.3); box-shadow: var(--shadow-sm); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem;">
        <div>
          <h4 style="font-size: 1.35rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0 0 0.4rem 0;">
            Want to Know More About Club Timings &amp; Schedules?
          </h4>
          <p style="color: var(--color-muted); font-size: 0.95rem; margin: 0;">
            Speak directly with our Co-Curricular coordinator to discover the best activities for your child's schedule.
          </p>
        </div>
        <a href="javascript:openCallbackModal()" class="btn btn-primary" style="background-color: var(--color-navy); border-color: var(--color-navy); color: #FFFFFF; font-weight: 700; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
          Request Activity Schedule &rarr;
        </a>
      </div>
    </div>
  </section>

  <!-- Cross-Navigation Strip to Other Beyond Destinations -->
  <section class="section" style="background-color: #FFFFFF; padding: 4rem 0;">
    <div class="container text-center">
      <h3 style="font-size: 1.8rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 1rem;">
        Explore More Beyond Classrooms
      </h3>
      <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; margin-top: 1.5rem;">
        <a href="/beyond" class="btn" style="background: transparent; color: var(--color-navy); border: 1.5px solid var(--color-navy); padding: 0.75rem 1.5rem; font-weight: 600; border-radius: var(--radius-sm); text-decoration: none;">
          Main Beyond Hub &rarr;
        </a>
        <a href="/beyond/student-achievers" class="btn" style="background: transparent; color: var(--color-navy); border: 1.5px solid var(--color-navy); padding: 0.75rem 1.5rem; font-weight: 600; border-radius: var(--radius-sm); text-decoration: none;">
          Student Achievers &rarr;
        </a>
        <a href="/beyond/gallery" class="btn" style="background: transparent; color: var(--color-navy); border: 1.5px solid var(--color-navy); padding: 0.75rem 1.5rem; font-weight: 600; border-radius: var(--radius-sm); text-decoration: none;">
          Photo Gallery &rarr;
        </a>
        <a href="/beyond/virtual-classroom" class="btn" style="background: transparent; color: var(--color-navy); border: 1.5px solid var(--color-navy); padding: 0.75rem 1.5rem; font-weight: 600; border-radius: var(--radius-sm); text-decoration: none;">
          Inside Virtual Classroom &rarr;
        </a>
      </div>
    </div>
  </section>
</main>

<style>
.stage-filter-btn {
  background: transparent;
  color: var(--color-navy);
  border: 1.5px solid rgba(6, 43, 99, 0.2);
  padding: 0.5rem 1.25rem;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}
.stage-filter-btn:hover, .stage-filter-btn.active {
  background: var(--color-navy);
  color: #FFFFFF;
  border-color: var(--color-navy);
}
.club-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-md);
}
</style>

<script>
function filterClubs(stage, btn) {
  document.querySelectorAll('.stage-filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  
  const cards = document.querySelectorAll('.club-card');
  cards.forEach(card => {
    const cardStage = card.getAttribute('data-stage');
    if (stage === 'all' || cardStage === 'all' || cardStage === stage) {
      card.style.display = 'flex';
    } else {
      card.style.display = 'none';
    }
  });
}
</script>

<?php include_once dirname(__FILE__) . '/../includes/footer.php'; ?>
