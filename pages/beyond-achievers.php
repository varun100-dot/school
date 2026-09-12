<?php
// Zuvio Global School - Dedicated Student Achievers Page
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// Initialize Mock CMS session store for Beyond if not present
if (!isset($_SESSION['mock_beyond_cms'])) {
    $_SESSION['mock_beyond_cms'] = [];
}
$beyond_cms = &$_SESSION['mock_beyond_cms'];

// Student Achievers Data directly from Source Document Pages 61–62
$default_achievers = [
    [
        'id' => 1,
        'name' => 'Fatima Ismath',
        'category' => 'Extracurricular Achievements',
        'category_key' => 'extracurricular',
        'badge' => '🌟 Star Kid',
        'description' => 'Recognized for distinguished multi-disciplinary excellence across creative writing, school leadership, and extracurricular achievements.',
        'image' => '/assets/images/Profile_Images/Student_1.png',
        'is_published' => 1
    ],
    [
        'id' => 2,
        'name' => 'Tahura Riffath',
        'category' => 'Creative Arts & Design',
        'category_key' => 'arts',
        'badge' => '🎨 Arts Winner',
        'description' => 'Secured top honors in the Card-Making Competition & Creative Arts Showcase, demonstrating meticulous aesthetic creativity and visual design.',
        'image' => '/assets/images/Profile_Images/Student_2.png',
        'is_published' => 1
    ],
    [
        'id' => 3,
        'name' => 'Mohammed Owais Shaikh',
        'category' => 'Martial Arts & Sports',
        'category_key' => 'sports',
        'badge' => '🥋 Taekwondo Champion',
        'description' => 'Demonstrating physical excellence, mental discipline, and competitive triumph in Japanese Taekwondo tournaments.',
        'image' => '/assets/images/Profile_Images/Student_3.png',
        'is_published' => 1
    ],
    [
        'id' => 4,
        'name' => 'Venkatesh JSN',
        'category' => 'Mind Sports & Chess',
        'category_key' => 'chess',
        'badge' => '♟️ Chess Tournaments',
        'description' => 'Remarkable strategic performance and competitive success in junior chess tournaments, exhibiting deep analytical foresight and tactical patience.',
        'image' => '/assets/images/Profile_Images/Student_4.png',
        'is_published' => 1
    ],
    [
        'id' => 5,
        'name' => 'Haripriya Banerjee',
        'category' => 'Modeling & Performing Arts',
        'category_key' => 'arts',
        'badge' => '📸 Modeling & Grand Shoots',
        'description' => 'Recognized in the World of Modeling and Grand Shoots, showcasing exceptional confidence, poise, and expressive presentation skills.',
        'image' => '/assets/images/Profile_Images/Student_5.png',
        'is_published' => 1
    ],
    [
        'id' => 6,
        'name' => 'Inaya Shaikh',
        'category' => 'Olympiad & Mathematics',
        'category_key' => 'academics',
        'badge' => '📐 Math Olympiad (IFMO)',
        'description' => 'Achieved top percentiles in the International Finance & Mathematics Olympiad (IFMO), showcasing advanced arithmetic agility and logical problem-solving.',
        'image' => '/assets/images/Profile_Images/Student_6.png',
        'is_published' => 1
    ]
];

$achievers_list = $beyond_cms['student_achievers'] ?? $default_achievers;

$seo = [
    'seo_title' => 'Student Achievers | Star Kids of Zuvio Global School',
    'meta_description' => 'Celebrating the remarkable achievements of Zuvio Global School students across sports, taekwondo, chess, creative arts, and mathematics olympiads.',
    'canonical_url' => 'https://zuvioglobalschool.com/beyond/student-achievers',
    'og_title' => 'Student Achievers — Zuvio Global School',
    'og_description' => 'Our Star Kids who excel in diverse areas: from international olympiads to martial arts championships and creative arts.',
    'og_image' => '/assets/images/Students learning in classroom.png'
];

$breadcrumbs = [
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Beyond', 'url' => '/beyond'],
    ['label' => 'Student Achievers']
];

$page_slug = 'beyond-achievers';
include_once dirname(__FILE__) . '/../includes/header.php';

render_breadcrumbs($breadcrumbs);
?>

<main class="page-main">

  <!-- Hero Section -->
  <section class="section" style="background: linear-gradient(135deg, var(--color-navy-dark) 0%, var(--color-navy) 100%); color: #FFFFFF; padding: 5rem 0 4.5rem 0; position: relative; overflow: hidden;">
    <div style="position: absolute; bottom: -80px; left: -80px; width: 350px; height: 350px; background: radial-gradient(circle, rgba(14, 159, 110, 0.15) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>
    <div class="container text-center" style="position: relative; z-index: 2; max-width: 850px; margin: 0 auto;">
      <span style="display: inline-block; background-color: var(--color-gold); color: var(--color-navy-dark); font-size: 0.82rem; font-weight: 800; padding: 0.35rem 1rem; border-radius: 20px; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 1.25rem;">
        Hall of Fame &bull; Beyond Academics
      </span>
      <h1 style="font-size: 2.85rem; font-family: var(--font-primary); color: #FFFFFF; line-height: 1.2; margin-bottom: 1.25rem;">
        Student Achievements &amp; Star Kids
      </h1>
      <p style="font-size: 1.15rem; color: rgba(255, 255, 255, 0.9); line-height: 1.7; margin-bottom: 2rem;">
        Meet our Star Kids who excel in diverse arenas. From Taekwondo tournaments to competitive chess, creative design, and mathematics olympiads, we celebrate each learner's passion and perseverance.
      </p>
      <div style="display: inline-flex; gap: 0.5rem; background: rgba(255, 255, 255, 0.08); padding: 0.5rem 1.25rem; border-radius: 30px; border: 1px solid rgba(255, 255, 255, 0.15); font-size: 0.9rem; color: #FFFFFF;">
        <span>🏆 Verified Student Accomplishments</span>
        <span>•</span>
        <span>🌍 Multi-Disciplinary Talents</span>
      </div>
    </div>
  </section>

  <!-- Achievers Showcase Section -->
  <section class="section" style="background-color: #FFFFFF; padding: 5rem 0;">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 3rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Star Kids Showcase</span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          Inspiring Peer Milestones
        </h2>
        <p style="color: var(--color-muted); font-size: 1.05rem; line-height: 1.6; margin-top: 0.75rem;">
          At Zuvio Global School, learning is personalized to allow children the time, flexibility, and mentor support to excel in their distinct areas of passion.
        </p>

        <!-- Category Filters -->
        <div style="display: flex; gap: 0.5rem; justify-content: center; flex-wrap: wrap; margin-top: 2rem;">
          <button class="achiever-filter-btn active" onclick="filterAchievers('all', this)">All Achievers</button>
          <button class="achiever-filter-btn" onclick="filterAchievers('sports', this)">Martial Arts &amp; Sports</button>
          <button class="achiever-filter-btn" onclick="filterAchievers('chess', this)">Chess &amp; Mind Sports</button>
          <button class="achiever-filter-btn" onclick="filterAchievers('arts', this)">Arts &amp; Creative</button>
          <button class="achiever-filter-btn" onclick="filterAchievers('academics', this)">Olympiads &amp; STEM</button>
        </div>
      </div>

      <!-- Achievers Grid -->
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem;">
        <?php foreach ($achievers_list as $achiever): ?>
          <?php if (!empty($achiever['is_published'])): ?>
            <div class="achiever-card" data-category="<?php echo h($achiever['category_key'] ?? 'all'); ?>" style="background: #FFFFFF; border-radius: var(--radius-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.2s, box-shadow 0.2s;">
              
              <!-- Card Top Header with Avatar / Badge -->
              <div style="background: linear-gradient(135deg, var(--color-surface-warm) 0%, #FFFFFF 100%); padding: 2rem 2rem 1.5rem 2rem; border-bottom: 1px solid var(--color-border); text-align: center;">
                <div style="width: 100px; height: 100px; border-radius: 50%; background: #FFFFFF; border: 3px solid var(--color-gold); margin: 0 auto 1.25rem auto; overflow: hidden; display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm);">
                  <?php if (!empty($achiever['image']) && file_exists(dirname(__FILE__) . '/..' . $achiever['image'])): ?>
                    <img src="<?php echo h($achiever['image']); ?>" alt="<?php echo h($achiever['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                  <?php else: ?>
                    <span style="font-size: 2.2rem;">⭐</span>
                  <?php endif; ?>
                </div>

                <span style="display: inline-block; background-color: rgba(6, 43, 99, 0.08); color: var(--color-navy); font-size: 0.75rem; font-weight: 800; padding: 0.35rem 0.85rem; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">
                  <?php echo h($achiever['badge']); ?>
                </span>

                <h3 style="font-size: 1.4rem; color: var(--color-navy); font-family: var(--font-primary); font-weight: 700; margin: 0.25rem 0;">
                  <?php echo h($achiever['name']); ?>
                </h3>
                <span style="font-size: 0.85rem; color: var(--color-teal); font-weight: 600;">
                  <?php echo h($achiever['category']); ?>
                </span>
              </div>

              <!-- Card Body -->
              <div style="padding: 1.75rem 2rem; flex-grow: 1;">
                <p style="color: var(--color-text); font-size: 0.92rem; line-height: 1.65; margin: 0;">
                  <?php echo h($achiever['description']); ?>
                </p>
              </div>

              <!-- Card Footer -->
              <div style="background: var(--color-surface); padding: 1rem 2rem; border-top: 1px dashed rgba(6, 43, 99, 0.12); display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.8rem; color: var(--color-muted); font-weight: 600;">
                  Zuvio Global School
                </span>
                <span style="color: var(--color-gold); font-size: 0.9rem;">
                  ★★★★★
                </span>
              </div>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Nurturing Spark Callout -->
  <section class="section" style="background-color: var(--color-surface-warm); padding: 5rem 0; border-top: 1px solid var(--color-border); border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div style="background: linear-gradient(135deg, var(--color-navy-dark) 0%, var(--color-navy) 100%); border-radius: var(--radius-lg); padding: 3.5rem 2.5rem; color: #FFFFFF; text-align: center; border: 1.5px solid rgba(212, 175, 55, 0.3); box-shadow: var(--shadow-lg);">
        <h2 style="font-size: 2.2rem; color: #FFFFFF; font-family: var(--font-primary); margin-bottom: 1rem;">
          Every Child Has a Spark. We Help It Shine.
        </h2>
        <p style="max-width: 650px; margin: 0 auto 2rem auto; color: rgba(255, 255, 255, 0.9); font-size: 1.05rem; line-height: 1.7;">
          Does your child have a specialized pursuit in sports, music, competitive coding, or creative arts? Our flexible timetable and dedicated faculty support high achievers seamlessly.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
          <a href="javascript:openCallbackModal()" class="btn btn-primary" style="background-color: var(--color-gold); color: var(--color-navy-dark); font-weight: 700; border: none; padding: 0.85rem 2rem; border-radius: var(--radius-sm); text-decoration: none;">
            Speak with an Admission Counselor &rarr;
          </a>
          <a href="/beyond/co-curricular" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid #FFFFFF; font-weight: 600; padding: 0.85rem 2rem; border-radius: var(--radius-sm); text-decoration: none;">
            View Global Clubs
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Cross-Navigation Strip -->
  <section class="section" style="background-color: #FFFFFF; padding: 4rem 0;">
    <div class="container text-center">
      <h3 style="font-size: 1.8rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 1rem;">
        Explore Other Beyond Experiences
      </h3>
      <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; margin-top: 1.5rem;">
        <a href="/beyond" class="btn" style="background: transparent; color: var(--color-navy); border: 1.5px solid var(--color-navy); padding: 0.75rem 1.5rem; font-weight: 600; border-radius: var(--radius-sm); text-decoration: none;">
          Main Beyond Hub &rarr;
        </a>
        <a href="/beyond/co-curricular" class="btn" style="background: transparent; color: var(--color-navy); border: 1.5px solid var(--color-navy); padding: 0.75rem 1.5rem; font-weight: 600; border-radius: var(--radius-sm); text-decoration: none;">
          Co-curricular / Clubs &rarr;
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
.achiever-filter-btn {
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
.achiever-filter-btn:hover, .achiever-filter-btn.active {
  background: var(--color-navy);
  color: #FFFFFF;
  border-color: var(--color-navy);
}
.achiever-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-md);
}
</style>

<script>
function filterAchievers(cat, btn) {
  document.querySelectorAll('.achiever-filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  
  const cards = document.querySelectorAll('.achiever-card');
  cards.forEach(card => {
    const cardCat = card.getAttribute('data-category');
    if (cat === 'all' || cardCat === cat) {
      card.style.display = 'flex';
    } else {
      card.style.display = 'none';
    }
  });
}
</script>

<?php include_once dirname(__FILE__) . '/../includes/footer.php'; ?>
