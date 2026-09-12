<?php
// Zuvio Global School - Dedicated Inside the Virtual Classroom Page
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// Initialize Mock CMS session store for Beyond if not present
if (!isset($_SESSION['mock_beyond_cms'])) {
    $_SESSION['mock_beyond_cms'] = [];
}
$beyond_cms = &$_SESSION['mock_beyond_cms'];

// Virtual Classroom Videos from Source Document Page 64 reference & real local videos
$default_videos = [
    [
        'id' => 1,
        'title' => 'Interactive Online Class — Live at Zuvio Global School',
        'category' => 'Live Class',
        'category_key' => 'live',
        'badge' => '📹 Live Class',
        'duration' => '3:45 mins',
        'description' => 'Experience how our teachers engage students through real-time dialogue, digital whiteboarding, active polling, and personalized questioning in small cohorts.',
        'thumbnail' => '/assets/images/Students learning in classroom.png',
        'video_url' => '/assets/images/01_Collaborative_Project_Learning.mp4',
        'is_published' => 1
    ],
    [
        'id' => 2,
        'title' => 'Student Learning & Hands-On Activity Session',
        'category' => 'Student Activity',
        'category_key' => 'activity',
        'badge' => '💡 Student Activity',
        'duration' => '4:12 mins',
        'description' => 'Watch young learners collaborate on interdisciplinary challenges, break down complex concepts, and build creative solutions together.',
        'thumbnail' => '/assets/images/Teacher interacting with students.png',
        'video_url' => '/assets/images/04_Student_Presentation_Learning.mp4',
        'is_published' => 1
    ],
    [
        'id' => 3,
        'title' => 'Co-Curricular Activity — Live Robotics & Code Jam',
        'category' => 'Co-Curricular',
        'category_key' => 'cocurricular',
        'badge' => '🤖 Co-Curricular',
        'duration' => '5:20 mins',
        'description' => 'Step inside our live robotics club as students test their algorithms, debug virtual circuits, and share creative digital inventions.',
        'thumbnail' => '/assets/images/Zuvio_Beyond_Website_Images/03_Robotics.jpg',
        'video_url' => '/assets/images/02_Online_Robotics_Learning.mp4',
        'is_published' => 1
    ],
    [
        'id' => 4,
        'title' => 'Special School Event & Annual Day Celebration',
        'category' => 'School Event',
        'category_key' => 'events',
        'badge' => '🎉 School Event',
        'duration' => '6:30 mins',
        'description' => 'Our global student community unites virtually across 38+ countries for annual celebrations, cultural performances, and awards.',
        'thumbnail' => '/assets/images/Hero image 1.png',
        'video_url' => '/assets/images/03_Science_Experiment_Learning.mp4',
        'is_published' => 1
    ],
    [
        'id' => 5,
        'title' => 'Student Performing Arts & Cultural Showcase',
        'category' => 'Performance',
        'category_key' => 'performance',
        'badge' => '🎭 Performance',
        'duration' => '4:50 mins',
        'description' => 'Showcasing classical and contemporary dance recitals, vocal concerts, and theatrical monologues prepared in Zuvio global clubs.',
        'thumbnail' => '/assets/images/Zuvio_Beyond_Website_Images/11_Dance.jpg',
        'video_url' => '/assets/images/01_Collaborative_Project_Learning.mp4',
        'is_published' => 1
    ],
    [
        'id' => 6,
        'title' => 'Nurturing Aspirations at Zuvio Global School',
        'category' => 'About School',
        'category_key' => 'about',
        'badge' => '🏫 About School',
        'duration' => '3:15 mins',
        'description' => 'An overview of our child-centered philosophy, Oxford thematic learning, and how we adapt school around the learner.',
        'thumbnail' => '/assets/images/about_us_hero.jpg',
        'video_url' => '/assets/images/02_Online_Robotics_Learning.mp4',
        'is_published' => 1
    ],
    [
        'id' => 7,
        'title' => 'The Complete Online Learning Experience',
        'category' => 'Learning Experience',
        'category_key' => 'experience',
        'badge' => '🌟 Experience',
        'duration' => '5:05 mins',
        'description' => 'A walk-through of a typical school day: from morning mindfulness to live academic batches, screen breaks, and club sessions.',
        'thumbnail' => '/assets/images/Hero image 2.png',
        'video_url' => '/assets/images/04_Student_Presentation_Learning.mp4',
        'is_published' => 1
    ]
];

$videos_list = $beyond_cms['virtual_classroom'] ?? $default_videos;

$seo = [
    'seo_title' => 'Inside the Virtual Classroom | Zuvio Global School',
    'meta_description' => 'Watch real recorded highlights from live online classes, student activity sessions, robotics clubs, and cultural showcases at Zuvio Global School.',
    'canonical_url' => 'https://zuvioglobalschool.com/beyond/virtual-classroom',
    'og_title' => 'Inside the Virtual Classroom — Zuvio Global School',
    'og_description' => 'See how our teachers and students interact in real-time. Watch live classroom sessions, co-curricular workshops, and student performances.',
    'og_image' => '/assets/images/Students learning in classroom.png'
];

$breadcrumbs = [
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Beyond', 'url' => '/beyond'],
    ['label' => 'Inside the Virtual Classroom']
];

$page_slug = 'beyond-virtual-classroom';
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
    <div style="position: absolute; bottom: -50px; right: -50px; width: 350px; height: 350px; background: radial-gradient(circle, rgba(212, 175, 55, 0.15) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>
    <div class="container text-center" style="position: relative; z-index: 2; max-width: 850px; margin: 0 auto;">
      <span style="display: inline-block; background-color: var(--color-gold); color: var(--color-navy-dark); font-size: 0.82rem; font-weight: 800; padding: 0.35rem 1rem; border-radius: 20px; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 1.25rem;">
        Video Showcase &bull; Learning in Action
      </span>
      <h1 style="font-size: 2.85rem; font-family: var(--font-primary); color: #FFFFFF; line-height: 1.2; margin-bottom: 1.25rem;">
        Inside the Virtual Classroom
      </h1>
      <p style="font-size: 1.15rem; color: rgba(255, 255, 255, 0.9); line-height: 1.7; margin-bottom: 2rem;">
        Curious about what live online schooling looks and feels like? Watch authentic classroom recordings, student discussions, co-curricular clubs, and creative performances.
      </p>
      <div style="display: inline-flex; gap: 0.5rem; background: rgba(255, 255, 255, 0.08); padding: 0.5rem 1.25rem; border-radius: 30px; border: 1px solid rgba(255, 255, 255, 0.15); font-size: 0.9rem; color: #FFFFFF;">
        <span>🎥 100% Live Class Footage</span>
        <span>•</span>
        <span>💻 Interactive Pedagogies</span>
      </div>
    </div>
  </section>

  <!-- Video Showcase Section -->
  <section class="section" style="background-color: #FFFFFF; padding: 5rem 0;">
    <div class="container">
      
      <!-- Category Filter -->
      <div style="display: flex; justify-content: center; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 3.5rem;">
        <button class="video-filter-btn active" onclick="filterVideos('all', this)">All Videos (7)</button>
        <button class="video-filter-btn" onclick="filterVideos('live', this)">Live Classes</button>
        <button class="video-filter-btn" onclick="filterVideos('activity', this)">Student Activities</button>
        <button class="video-filter-btn" onclick="filterVideos('cocurricular', this)">Co-Curricular</button>
        <button class="video-filter-btn" onclick="filterVideos('performance', this)">Performances</button>
        <button class="video-filter-btn" onclick="filterVideos('experience', this)">School Experience</button>
      </div>

      <!-- Videos Grid -->
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 2rem;">
        <?php foreach ($videos_list as $video): ?>
          <?php if (!empty($video['is_published'])): ?>
            <div class="video-card" data-cat="<?php echo h($video['category_key'] ?? 'all'); ?>" onclick="playVideoModal('<?php echo h($video['video_url']); ?>', '<?php echo h(addslashes($video['title'])); ?>', '<?php echo h(addslashes($video['description'])); ?>')" style="background: #FFFFFF; border-radius: var(--radius-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); overflow: hidden; display: flex; flex-direction: column; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;">
              
              <!-- Video Thumbnail with Play Button Overlay -->
              <div style="position: relative; width: 100%; height: 230px; overflow: hidden; background: #000;">
                <img src="<?php echo h($video['thumbnail']); ?>" alt="<?php echo h($video['title']); ?>" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.9; transition: transform 0.3s;" loading="lazy">
                
                <!-- Play Button Center Overlay -->
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 56px; height: 56px; background: rgba(212, 175, 55, 0.92); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(0,0,0,0.3); transition: transform 0.2s, background 0.2s;">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="#031B42" style="margin-left: 3px;">
                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                  </svg>
                </div>

                <!-- Category Badge -->
                <div style="position: absolute; top: 1rem; left: 1rem; background: rgba(3, 27, 66, 0.85); color: #FFFFFF; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.7rem; border-radius: 12px; backdrop-filter: blur(4px);">
                  <?php echo h($video['badge']); ?>
                </div>

                <!-- Duration Badge -->
                <div style="position: absolute; bottom: 1rem; right: 1rem; background: rgba(0, 0, 0, 0.75); color: #FFFFFF; font-size: 0.75rem; font-weight: 600; padding: 0.2rem 0.5rem; border-radius: 4px;">
                  ⏱️ <?php echo h($video['duration']); ?>
                </div>
              </div>

              <!-- Content Body -->
              <div style="padding: 1.75rem; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                  <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); font-weight: 700; margin: 0 0 0.65rem 0; line-height: 1.4;">
                    <?php echo h($video['title']); ?>
                  </h3>
                  <p style="color: var(--color-muted); font-size: 0.92rem; line-height: 1.6; margin: 0;">
                    <?php echo h($video['description']); ?>
                  </p>
                </div>
                <div style="margin-top: 1.25rem; display: flex; align-items: center; justify-content: space-between; border-top: 1px dashed rgba(6, 43, 99, 0.12); padding-top: 0.85rem;">
                  <span style="color: var(--color-teal); font-weight: 700; font-size: 0.85rem;">Watch Video &rarr;</span>
                  <span style="font-size: 0.8rem; color: var(--color-muted);">Live Interactive</span>
                </div>
              </div>

            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Video Playback Modal -->
  <div id="videoPlayerModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(3, 27, 66, 0.94); z-index: 9999; align-items: center; justify-content: center; padding: 2rem;" onclick="closeVideoModal(event)">
    <div style="max-width: 900px; width: 100%; background: #FFFFFF; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-xl); position: relative;" onclick="event.stopPropagation()">
      <button onclick="closeVideoModal()" style="position: absolute; top: 1rem; right: 1rem; background: rgba(0,0,0,0.6); color: #FFFFFF; border: none; border-radius: 50%; width: 36px; height: 36px; font-size: 1.2rem; cursor: pointer; display: flex; align-items: center; justify-content: center; z-index: 10;">
        &times;
      </button>
      <div style="background: #000; text-align: center;">
        <video id="modalVideoElement" controls autoplay style="width: 100%; max-height: 520px; display: block;">
          <source id="modalVideoSrc" src="" type="video/mp4">
          Your browser does not support HTML5 video.
        </video>
      </div>
      <div style="padding: 1.75rem 2rem;">
        <h3 id="modalVideoTitle" style="font-size: 1.35rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0 0 0.5rem 0;"></h3>
        <p id="modalVideoDesc" style="color: var(--color-muted); font-size: 0.95rem; margin: 0; line-height: 1.6;"></p>
      </div>
    </div>
  </div>

  <!-- Book a Live Demo Strip -->
  <section class="section" style="background-color: var(--color-surface-warm); padding: 5rem 0; border-top: 1px solid var(--color-border); border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div style="background: linear-gradient(135deg, var(--color-navy-dark) 0%, var(--color-navy) 100%); border-radius: var(--radius-lg); padding: 3.5rem 2.5rem; color: #FFFFFF; text-align: center; border: 1.5px solid rgba(212, 175, 55, 0.3); box-shadow: var(--shadow-lg);">
        <h2 style="font-size: 2.2rem; color: #FFFFFF; font-family: var(--font-primary); margin-bottom: 1rem;">
          Experience a Live Class Demo with Your Child
        </h2>
        <p style="max-width: 650px; margin: 0 auto 2rem auto; color: rgba(255, 255, 255, 0.9); line-height: 1.7; font-size: 1.05rem;">
          Join a live 1-on-1 virtual class session where your child can meet our educators, experience the interactive whiteboard, and test-drive our online platform.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
          <a href="javascript:openCallbackModal()" class="btn btn-primary" style="background-color: var(--color-gold); color: var(--color-navy-dark); font-weight: 700; border: none; padding: 0.85rem 2rem; border-radius: var(--radius-sm); text-decoration: none;">
            Book Free Live Demo &rarr;
          </a>
          <a href="/admissions/enrol-now" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid #FFFFFF; font-weight: 600; padding: 0.85rem 2rem; border-radius: var(--radius-sm); text-decoration: none;">
            Enrol Now
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Cross Navigation -->
  <section class="section" style="background-color: #FFFFFF; padding: 4rem 0;">
    <div class="container text-center">
      <h3 style="font-size: 1.8rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 1rem;">
        More Beyond Destinations
      </h3>
      <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; margin-top: 1.5rem;">
        <a href="/beyond" class="btn" style="background: transparent; color: var(--color-navy); border: 1.5px solid var(--color-navy); padding: 0.75rem 1.5rem; font-weight: 600; border-radius: var(--radius-sm); text-decoration: none;">
          Main Beyond Hub &rarr;
        </a>
        <a href="/beyond/co-curricular" class="btn" style="background: transparent; color: var(--color-navy); border: 1.5px solid var(--color-navy); padding: 0.75rem 1.5rem; font-weight: 600; border-radius: var(--radius-sm); text-decoration: none;">
          Co-curricular / Clubs &rarr;
        </a>
        <a href="/beyond/student-achievers" class="btn" style="background: transparent; color: var(--color-navy); border: 1.5px solid var(--color-navy); padding: 0.75rem 1.5rem; font-weight: 600; border-radius: var(--radius-sm); text-decoration: none;">
          Student Achievers &rarr;
        </a>
        <a href="/beyond/gallery" class="btn" style="background: transparent; color: var(--color-navy); border: 1.5px solid var(--color-navy); padding: 0.75rem 1.5rem; font-weight: 600; border-radius: var(--radius-sm); text-decoration: none;">
          Photo Gallery &rarr;
        </a>
      </div>
    </div>
  </section>
</main>

<style>
.video-filter-btn {
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
.video-filter-btn:hover, .video-filter-btn.active {
  background: var(--color-navy);
  color: #FFFFFF;
  border-color: var(--color-navy);
}
.video-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-md);
}
.video-card:hover img {
  transform: scale(1.04);
}
</style>

<script>
function filterVideos(cat, btn) {
  document.querySelectorAll('.video-filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  
  const cards = document.querySelectorAll('.video-card');
  cards.forEach(card => {
    const cardCat = card.getAttribute('data-cat');
    if (cat === 'all' || cardCat === cat) {
      card.style.display = 'flex';
    } else {
      card.style.display = 'none';
    }
  });
}

function playVideoModal(videoSrc, title, desc) {
  const modal = document.getElementById('videoPlayerModal');
  const videoEl = document.getElementById('modalVideoElement');
  const sourceEl = document.getElementById('modalVideoSrc');
  sourceEl.src = videoSrc;
  videoEl.load();
  videoEl.play();
  document.getElementById('modalVideoTitle').innerText = title;
  document.getElementById('modalVideoDesc').innerText = desc;
  modal.style.display = 'flex';
  document.body.style.overflow = 'hidden';
}

function closeVideoModal(e) {
  const modal = document.getElementById('videoPlayerModal');
  const videoEl = document.getElementById('modalVideoElement');
  videoEl.pause();
  modal.style.display = 'none';
  document.body.style.overflow = 'auto';
}
</script>

<?php include_once dirname(__FILE__) . '/../includes/footer.php'; ?>
