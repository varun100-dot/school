<?php
// Zuvio Global School - Dedicated Gallery Page
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// Initialize Mock CMS session store for Beyond if not present
if (!isset($_SESSION['mock_beyond_cms'])) {
    $_SESSION['mock_beyond_cms'] = [];
}
$beyond_cms = &$_SESSION['mock_beyond_cms'];

// Gallery items from source document reference & verified school images
$default_gallery = [
    [
        'id' => 1,
        'title' => 'Live Interactive Classroom Session',
        'category' => 'Live Classes',
        'category_key' => 'live',
        'caption' => 'Students engaged in active peer dialogue, interactive whiteboard problem-solving, and live questioning with mentors.',
        'image' => '/assets/images/Students learning in classroom.png',
        'is_published' => 1
    ],
    [
        'id' => 2,
        'title' => 'Personalized Teacher Mentorship',
        'category' => 'Live Classes',
        'category_key' => 'live',
        'caption' => 'Dedicated educator providing 1-on-1 pacing support, ensuring every student is seen, heard, and guided with care.',
        'image' => '/assets/images/Teacher interacting with students.png',
        'is_published' => 1
    ],
    [
        'id' => 3,
        'title' => 'Hands-on Robotics & Tech Exploration',
        'category' => 'Projects & STEM',
        'category_key' => 'stem',
        'caption' => 'Young innovators assembling sensors, coding circuits, and testing automated prototypes in live STEM cohort labs.',
        'image' => '/assets/images/Zuvio_Beyond_Website_Images/03_Robotics.jpg',
        'is_published' => 1
    ],
    [
        'id' => 4,
        'title' => 'AI Explorers & Creative Prompting',
        'category' => 'Projects & STEM',
        'category_key' => 'stem',
        'caption' => 'Early introduction to machine learning principles, pattern recognition, and creative digital storytelling.',
        'image' => '/assets/images/Zuvio_Beyond_Website_Images/01_AI_Explorers.jpg',
        'is_published' => 1
    ],
    [
        'id' => 5,
        'title' => 'Creative Arts & Craft Showcase',
        'category' => 'Creative Arts',
        'category_key' => 'arts',
        'caption' => 'Watercolour painting, origami, tactile crafts, and mixed media art crafted during weekly global creative circles.',
        'image' => '/assets/images/Zuvio_Beyond_Website_Images/12_Art_and_Craft.jpg',
        'is_published' => 1
    ],
    [
        'id' => 6,
        'title' => 'Speed Rubik\'s Cube Competition',
        'category' => 'Mind Sports',
        'category_key' => 'mind',
        'caption' => 'Learners demonstrating concentration, 3D spatial agility, and speed-solving algorithm execution.',
        'image' => '/assets/images/Zuvio_Beyond_Website_Images/04_Rubiks_Cube.jpg',
        'is_published' => 1
    ],
    [
        'id' => 7,
        'title' => 'Strategic Chess Mentorship',
        'category' => 'Mind Sports',
        'category_key' => 'mind',
        'caption' => 'Tactical opening reviews and competitive tournament simulation with rated master chess instructors.',
        'image' => '/assets/images/Zuvio_Beyond_Website_Images/09_Chess.jpg',
        'is_published' => 1
    ],
    [
        'id' => 8,
        'title' => 'Digital Media, Graphic & Web Design',
        'category' => 'Creative Arts',
        'category_key' => 'arts',
        'caption' => 'Middle school students mastering digital illustration, UI layouts, and multimedia animation tools.',
        'image' => '/assets/images/Zuvio_Beyond_Website_Images/10_Digital_Media_and_Arts.jpg',
        'is_published' => 1
    ],
    [
        'id' => 9,
        'title' => 'Vocal & Performing Arts Showcase',
        'category' => 'Creative Arts',
        'category_key' => 'arts',
        'caption' => 'Expressive dance rhythms, classical vocal scales, and musical ensembles performed live during virtual cultural galas.',
        'image' => '/assets/images/Zuvio_Beyond_Website_Images/11_Dance.jpg',
        'is_published' => 1
    ],
    [
        'id' => 10,
        'title' => 'Youth Entrepreneurship Pitch Meet',
        'category' => 'Projects & STEM',
        'category_key' => 'stem',
        'caption' => 'Students presenting real-world product ideas, business models, and collaborative social innovation projects.',
        'image' => '/assets/images/Zuvio_Beyond_Website_Images/08_Entrepreneurship.jpg',
        'is_published' => 1
    ],
    [
        'id' => 11,
        'title' => 'Abacus Numerical Agility Workshop',
        'category' => 'Mind Sports',
        'category_key' => 'mind',
        'caption' => 'Speed calculation drills building lightning-fast mental math habits, focus, and numerical confidence.',
        'image' => '/assets/images/Zuvio_Beyond_Website_Images/05_Abacus.jpg',
        'is_published' => 1
    ],
    [
        'id' => 12,
        'title' => 'Financial Literacy Masterclass',
        'category' => 'Life Skills',
        'category_key' => 'stem',
        'caption' => 'Practical exploration of budgeting, smart saving, currency exchange, and real-world trade concepts.',
        'image' => '/assets/images/Zuvio_Beyond_Website_Images/07_Financial_Literacy.jpg',
        'is_published' => 1
    ]
];

$gallery_items = $beyond_cms['gallery'] ?? $default_gallery;

$seo = [
    'seo_title' => 'Photo Gallery | School Life & Activities | Zuvio Global School',
    'meta_description' => 'Browse photos of live online classrooms, robotics projects, chess championships, arts showcases, and student events at Zuvio Global School.',
    'canonical_url' => 'https://zuvioglobalschool.com/beyond/gallery',
    'og_title' => 'Photo Gallery — Zuvio Global School',
    'og_description' => 'A visual window into vibrant online schooling: live classes, project builds, and creative celebrations.',
    'og_image' => '/assets/images/Students learning in classroom.png'
];

$breadcrumbs = [
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Beyond', 'url' => '/beyond'],
    ['label' => 'Gallery']
];

$page_slug = 'beyond-gallery';
include_once dirname(__FILE__) . '/../includes/header.php';
?>

<main class="page-main">
  <!-- Breadcrumbs -->
  <div class="breadcrumb-container" style="background-color: var(--color-surface); border-bottom: 1px solid var(--color-border); padding: 0.85rem 0;">
    <div class="container">
      <?php render_breadcrumbs($breadcrumbs); ?>
    </div>
  </div>

  <!-- Hero Section -->
  <section class="section" style="background: linear-gradient(135deg, var(--color-navy-dark) 0%, var(--color-navy) 100%); color: #FFFFFF; padding: 5rem 0 4.5rem 0; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -60px; right: -60px; width: 320px; height: 320px; background: radial-gradient(circle, rgba(14, 159, 110, 0.15) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>
    <div class="container text-center" style="position: relative; z-index: 2; max-width: 850px; margin: 0 auto;">
      <span style="display: inline-block; background-color: var(--color-gold); color: var(--color-navy-dark); font-size: 0.82rem; font-weight: 800; padding: 0.35rem 1rem; border-radius: 20px; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 1.25rem;">
        Moments &bull; Activities &bull; Showcase
      </span>
      <h1 style="font-size: 2.85rem; font-family: var(--font-primary); color: #FFFFFF; line-height: 1.2; margin-bottom: 1.25rem;">
        Life at Zuvio Global School
      </h1>
      <p style="font-size: 1.15rem; color: rgba(255, 255, 255, 0.9); line-height: 1.7; margin-bottom: 2rem;">
        A visual journey into our daily online schooling: interactive learning, collaborative projects, creative showcases, and vibrant student community milestones.
      </p>
      <div style="display: inline-flex; gap: 0.5rem; background: rgba(255, 255, 255, 0.08); padding: 0.5rem 1.25rem; border-radius: 30px; border: 1px solid rgba(255, 255, 255, 0.15); font-size: 0.9rem; color: #FFFFFF;">
        <span>📸 Real School Moments</span>
        <span>•</span>
        <span>🎨 Creative Demonstrations</span>
      </div>
    </div>
  </section>

  <!-- Gallery Section -->
  <section class="section" style="background-color: #FFFFFF; padding: 5rem 0;">
    <div class="container">
      
      <!-- Filter Bar -->
      <div style="display: flex; justify-content: center; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 3.5rem;">
        <button class="gallery-filter-btn active" onclick="filterGallery('all', this)">All Moments (12)</button>
        <button class="gallery-filter-btn" onclick="filterGallery('live', this)">Live Classes</button>
        <button class="gallery-filter-btn" onclick="filterGallery('stem', this)">Projects &amp; STEM</button>
        <button class="gallery-filter-btn" onclick="filterGallery('arts', this)">Creative Arts</button>
        <button class="gallery-filter-btn" onclick="filterGallery('mind', this)">Mind Sports &amp; Chess</button>
      </div>

      <!-- Gallery Grid -->
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem;">
        <?php foreach ($gallery_items as $item): ?>
          <?php if (!empty($item['is_published'])): ?>
            <div class="gallery-card" data-cat="<?php echo h($item['category_key'] ?? 'all'); ?>" onclick="openLightbox('<?php echo h($item['image']); ?>', '<?php echo h(addslashes($item['title'])); ?>', '<?php echo h(addslashes($item['caption'])); ?>')" style="background: #FFFFFF; border-radius: var(--radius-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); overflow: hidden; display: flex; flex-direction: column; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;">
              
              <!-- Image Container -->
              <div style="position: relative; width: 100%; height: 220px; overflow: hidden; background: #F1F5F9;">
                <img src="<?php echo h($item['image']); ?>" alt="<?php echo h($item['title']); ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;" loading="lazy">
                <div style="position: absolute; top: 1rem; right: 1rem; background: rgba(3, 27, 66, 0.85); color: #FFFFFF; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.65rem; border-radius: 12px; backdrop-filter: blur(4px); text-transform: uppercase;">
                  <?php echo h($item['category']); ?>
                </div>
              </div>

              <!-- Content Body -->
              <div style="padding: 1.5rem 1.75rem; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                  <h3 style="font-size: 1.2rem; color: var(--color-navy); font-family: var(--font-primary); font-weight: 700; margin: 0 0 0.5rem 0;">
                    <?php echo h($item['title']); ?>
                  </h3>
                  <p style="color: var(--color-muted); font-size: 0.9rem; line-height: 1.55; margin: 0;">
                    <?php echo h($item['caption']); ?>
                  </p>
                </div>
                <div style="margin-top: 1rem; display: flex; align-items: center; gap: 0.4rem; color: var(--color-teal); font-size: 0.85rem; font-weight: 600;">
                  <span>🔍 Click to Expand</span>
                </div>
              </div>

            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Lightbox Modal -->
  <div id="galleryLightbox" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(3, 27, 66, 0.94); z-index: 9999; align-items: center; justify-content: center; padding: 2rem;" onclick="closeLightbox(event)">
    <div style="max-width: 900px; width: 100%; background: #FFFFFF; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-xl); position: relative;" onclick="event.stopPropagation()">
      <button onclick="closeLightbox()" style="position: absolute; top: 1rem; right: 1rem; background: rgba(0,0,0,0.6); color: #FFFFFF; border: none; border-radius: 50%; width: 36px; height: 36px; font-size: 1.2rem; cursor: pointer; display: flex; align-items: center; justify-content: center; z-index: 10;">
        &times;
      </button>
      <div style="max-height: 550px; overflow: hidden; background: #000; text-align: center;">
        <img id="lightboxImg" src="" alt="" style="max-height: 550px; max-width: 100%; object-fit: contain;">
      </div>
      <div style="padding: 1.75rem 2rem;">
        <h3 id="lightboxTitle" style="font-size: 1.35rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0 0 0.5rem 0;"></h3>
        <p id="lightboxCaption" style="color: var(--color-muted); font-size: 0.95rem; margin: 0; line-height: 1.6;"></p>
      </div>
    </div>
  </div>

  <!-- Cross-Navigation Strip -->
  <section class="section" style="background-color: var(--color-surface-warm); padding: 4rem 0; border-top: 1px solid var(--color-border);">
    <div class="container text-center">
      <h3 style="font-size: 1.8rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 1rem;">
        Continue Discovering Beyond
      </h3>
      <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; margin-top: 1.5rem;">
        <a href="/beyond" class="btn" style="background: transparent; color: var(--color-navy); border: 1.5px solid var(--color-navy); padding: 0.75rem 1.5rem; font-weight: 600; border-radius: var(--radius-sm); text-decoration: none;">
          Main Beyond Hub &rarr;
        </a>
        <a href="/beyond/co-curricular" class="btn" style="background: transparent; color: var(--color-navy); border: 1.5px solid var(--color-navy); padding: 0.75rem 1.5rem; font-weight: 600; border-radius: var(--radius-sm); text-decoration: none;">
          Co-curricular &amp; Clubs &rarr;
        </a>
        <a href="/beyond/student-achievers" class="btn" style="background: transparent; color: var(--color-navy); border: 1.5px solid var(--color-navy); padding: 0.75rem 1.5rem; font-weight: 600; border-radius: var(--radius-sm); text-decoration: none;">
          Student Achievers &rarr;
        </a>
        <a href="/beyond/virtual-classroom" class="btn" style="background: transparent; color: var(--color-navy); border: 1.5px solid var(--color-navy); padding: 0.75rem 1.5rem; font-weight: 600; border-radius: var(--radius-sm); text-decoration: none;">
          Inside Virtual Classroom &rarr;
        </a>
      </div>
    </div>
  </section>
</main>

<style>
.gallery-filter-btn {
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
.gallery-filter-btn:hover, .gallery-filter-btn.active {
  background: var(--color-navy);
  color: #FFFFFF;
  border-color: var(--color-navy);
}
.gallery-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-md);
}
.gallery-card:hover img {
  transform: scale(1.04);
}
</style>

<script>
function filterGallery(cat, btn) {
  document.querySelectorAll('.gallery-filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  
  const cards = document.querySelectorAll('.gallery-card');
  cards.forEach(card => {
    const cardCat = card.getAttribute('data-cat');
    if (cat === 'all' || cardCat === cat) {
      card.style.display = 'flex';
    } else {
      card.style.display = 'none';
    }
  });
}

function openLightbox(img, title, caption) {
  const modal = document.getElementById('galleryLightbox');
  document.getElementById('lightboxImg').src = img;
  document.getElementById('lightboxTitle').innerText = title;
  document.getElementById('lightboxCaption').innerText = caption;
  modal.style.display = 'flex';
  document.body.style.overflow = 'hidden';
}

function closeLightbox(e) {
  const modal = document.getElementById('galleryLightbox');
  modal.style.display = 'none';
  document.body.style.overflow = 'auto';
}
</script>

<?php include_once dirname(__FILE__) . '/../includes/footer.php'; ?>
