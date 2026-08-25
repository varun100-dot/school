<?php
// Zuvio Global School - About Us Page Template
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// Fetch About Sections
$sections = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM `about_sections` WHERE `is_active` = 1");
        while ($row = $stmt->fetch()) {
            $sections[$row['section_key']] = $row;
        }
    } catch (Exception $e) {
        error_log("[About Sections Error] " . $e->getMessage());
    }
}

// Fetch Timeline
$timeline = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM `about_timeline` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
        $timeline = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("[About Timeline Error] " . $e->getMessage());
    }
}

// Fetch Leadership
$leadership = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM `leadership` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
        $leadership = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("[About Leadership Error] " . $e->getMessage());
    }
}

// Fallback leadership if database is offline or empty
if (empty($leadership)) {
    $leadership = [
        [
            'name' => 'Sharmin Habib',
            'slug' => 'sharmin-habib',
            'designation' => 'Co-Founder & Director',
            'image' => '/assets/images/Profile_Images/Sharmin_Professional_Profile.webp',
            'short_description' => 'Sharmin Habib is a seasoned educationist and edtech growth expert with over 18 years of experience. She has successfully founded and scaled preschools and digital K–8 learning models in domestic and international markets.',
            'bio' => '',
            'message' => '',
            'sort_order' => 1,
            'is_active' => 1
        ],
        [
            'name' => 'Deepak Jain',
            'slug' => 'deepak-jain',
            'designation' => 'Co-Founder & Director',
            'image' => '/assets/images/Profile_Images/Deepak_Professional_Profile.webp',
            'short_description' => 'Deepak Jain is an entrepreneur and business professional who brings a practical, growth-oriented perspective to Zuvio Global School. He oversees Zuvio’s strategic direction, operations, and partnerships.',
            'bio' => '',
            'message' => '',
            'sort_order' => 2,
            'is_active' => 1
        ],
        [
            'name' => 'Pragya Jain',
            'slug' => 'pragya-jain',
            'designation' => 'Co-Founder & Director',
            'image' => '/assets/images/Profile_Images/Pragya_Professional_Profile.webp',
            'short_description' => 'Pragya Jain is an educationist dedicated to child-centric learning that prepares students for life. She conceptualized Zuvio to merge academic rigor with personalization, creativity, and future-ready skills.',
            'bio' => '',
            'message' => '',
            'sort_order' => 3,
            'is_active' => 1
        ],
        [
            'name' => 'Rashmi Bhasin',
            'slug' => 'rashmi-bhasin',
            'designation' => 'Academic Head',
            'image' => '/assets/images/Profile_Images/Rashmi_Professional_Profile.webp',
            'short_description' => 'Rashmi Bhasin is the Academic Head of Zuvio Global School. She is a visionary curriculum thinker and mentor committed to designing personalized, interactive, and child-centered online homeschooling experiences.',
            'bio' => '',
            'message' => '',
            'sort_order' => 4,
            'is_active' => 1
        ]
    ];
}

// Fallback arrays if database is offline
$story = $sections['our_story'] ?? [
    'title' => 'Our Story',
    'subtitle' => 'Learning Beyond Boundaries',
    'content' => "Zuvio Global School was born from a simple belief — every child deserves access to meaningful, world-class learning, no matter where they are.\n\nWe envisioned a school where learning adapts to the learner, not the other way around — combining strong academics with creativity, confidence, technology, life skills and global exposure.\n\nToday, Zuvio is building a learning community without geographical boundaries, where children are encouraged to explore boldly, think independently and grow into confident global citizens.\n\nDifferent Paths. One Purpose. Limitless Futures.",
    'image' => '/assets/images/about_us_hero.jpg'
];
$story['subtitle'] = 'Learning Beyond Boundaries'; // Enforce the required text revision
$story['content'] = "Zuvio Global School was born from a simple belief — every child deserves access to meaningful, world-class learning, no matter where they are.\n\nWe envisioned a school where learning adapts to the learner, not the other way around — combining strong academics with creativity, confidence, technology, life skills and global exposure.\n\nToday, Zuvio is building a learning community without geographical boundaries, where children are encouraged to explore boldly, think independently and grow into confident global citizens.\n\nDifferent Paths. One Purpose. Limitless Futures."; // Enforce the required text revision
$story['image'] = '/assets/images/about_us_hero.jpg'; // Enforce the new image

$vision_mission = $sections['vision_mission'] ?? [
    'title' => 'Vision, Mission & Beliefs',
    'subtitle' => 'Our Compass',
    'content' => "Vision: To create a world where every child can access future-ready, personalised and globally connected learning without boundaries, and to empower every child to become a confident global citizen of tomorrow.\n\nMission: To empower every child to discover their potential and thrive in a changing world by combining academic excellence with creativity, critical thinking, technology and life skills.\n\nEducational Philosophy: “Every Child. Every Mind. Every Possibility.”"
];

$philosophies = [
    ['title' => 'Curiosity', 'desc' => 'Gives learners the freedom to explore questions and interests beyond textbooks.', 'bg' => 'var(--pastel-blue)', 'border' => 'rgba(10, 137, 152, 0.2)'],
    ['title' => 'Creativity', 'desc' => 'Creates space to imagine, experiment and learn in individual ways.', 'bg' => 'var(--pastel-yellow)', 'border' => 'rgba(217, 164, 65, 0.2)'],
    ['title' => 'Critical Thinking', 'desc' => 'Encourages learners to question, analyse and find solutions independently.', 'bg' => 'var(--pastel-orange)', 'border' => 'rgba(217, 164, 65, 0.2)'],
    ['title' => 'Communication', 'desc' => 'Builds confidence to express ideas through conversations, presentations and real-world interactions.', 'bg' => 'var(--pastel-green)', 'border' => 'rgba(10, 137, 152, 0.2)'],
    ['title' => 'Collaboration', 'desc' => 'Connects learners with peers, mentors and communities beyond geographical boundaries.', 'bg' => 'var(--pastel-purple)', 'border' => 'rgba(6, 43, 99, 0.1)'],
    ['title' => 'Compassion', 'desc' => 'Nurtures empathy and kindness through meaningful family and community experiences.', 'bg' => 'var(--pastel-red)', 'border' => 'rgba(217, 70, 239, 0.2)'],
    ['title' => 'Character', 'desc' => 'Develops independence, responsibility, resilience and self-discipline.', 'bg' => 'var(--pastel-blue)', 'border' => 'rgba(10, 137, 152, 0.2)'],
    ['title' => 'Citizenship', 'desc' => 'Builds awareness of communities, cultures and the wider world through learning beyond classroom walls.', 'bg' => 'var(--pastel-yellow)', 'border' => 'rgba(217, 164, 65, 0.2)']
];

$page_slug = 'about';
include_once dirname(__FILE__) . '/../includes/header.php';
?>

<!-- Hero Banner Header -->
<section class="about-hero" style="background-color: var(--pastel-blue); color: var(--color-navy); padding: 6.5rem 2rem; text-align: center; border-bottom: 1px solid var(--color-border);">
  <div class="container" style="max-width: 800px;">
    <h1 style="font-size: 3rem; font-family: var(--font-primary); margin-bottom: 1rem; color: var(--color-navy-dark); font-weight: 700;">About Zuvio Global School</h1>
    <p style="font-size: 1.15rem; font-weight: 500; line-height: 1.6; color: var(--color-text); margin: 0;">
      Reimagining education for a world without boundaries—where every child has the freedom to learn, explore, and grow.
    </p>
  </div>
</section>

<!-- Section 1: Our Story -->
<section class="section" style="background-color: var(--color-white); border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="grid-2" style="align-items: center; gap: 4rem;">
      <div>
        <span style="font-size: 0.85rem; font-weight: 600; color: var(--color-gold); text-transform: uppercase; letter-spacing: 1.5px; display: block; margin-bottom: 0.5rem;"><?php echo h($story['subtitle']); ?></span>
        <h2 style="font-size: 2.25rem; color: var(--color-navy); margin-bottom: 1.5rem; font-family: var(--font-primary);"><?php echo h($story['title']); ?></h2>
        <p style="color: var(--color-text); font-size: 1.05rem; line-height: 1.8; white-space: pre-line;"><?php echo h($story['content']); ?></p>
      </div>
      <div style="border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-md); background-color: var(--color-navy); height: 350px;">
        <img src="/assets/images/about_us_hero.jpg" alt="Students studying online" style="width: 100%; height: 100%; object-fit: cover;">
      </div>
    </div>
  </div>
</section>

<!-- Section 2: Vision & Mission (Responsive 1fr/2fr columns) -->
<section class="section" style="background-color: var(--color-surface-blue); border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="grid-2" style="gap: 3rem;">
      
      <!-- Vision Card -->
      <div class="about-card" style="background-color: #FFFFFF; padding: 2.5rem; border-radius: var(--radius-md); border-top: 4px solid var(--color-gold); box-shadow: var(--shadow-sm);">
        <h3 style="font-size: 1.5rem; color: var(--color-navy); margin-bottom: 1rem; font-family: var(--font-primary);">Our Vision</h3>
        <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.7;">
          To create a world where every child can access future-ready, personalised and globally connected learning without boundaries, and to empower every child to become a confident global citizen of tomorrow.
        </p>
      </div>

      <!-- Mission Card -->
      <div class="about-card" style="background-color: #FFFFFF; padding: 2.5rem; border-radius: var(--radius-md); border-top: 4px solid var(--color-gold); box-shadow: var(--shadow-sm);">
        <h3 style="font-size: 1.5rem; color: var(--color-navy); margin-bottom: 1rem; font-family: var(--font-primary);">Our Mission</h3>
        <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.7;">
          To empower every child to discover their potential and thrive in a changing world by combining academic excellence with creativity, critical thinking, technology and life skills.
        </p>
      </div>

    </div>
  </div>
</section>

<!-- Section 3: 8C Philosophy Grid -->
<section class="section" style="background-color: var(--color-white); border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="text-center" style="margin-bottom: 4rem;">
      <span style="font-size: 0.85rem; font-weight: 600; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">The Zuvio Foundation</span>
      <h2 style="font-size: 2.25rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">Our 8C Philosophy™</h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem;">
      <?php foreach ($philosophies as $phil): 
        $bg = $phil['bg'] ?? 'var(--color-white)';
        $border = $phil['border'] ?? 'var(--color-border)';
      ?>
        <div class="card" style="padding: 2.25rem 1.75rem; background-color: <?php echo $bg; ?>; border-color: <?php echo $border; ?>; box-shadow: var(--shadow-sm); border-left: none; border-top: 4px solid <?php echo $border; ?>; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
          <h3 style="font-size: 1.25rem; color: var(--color-navy); margin-bottom: 0.75rem; font-family: var(--font-primary); font-weight: 700;"><?php echo h($phil['title']); ?></h3>
          <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.6;"><?php echo h($phil['desc']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Section 4: Leadership Team -->
<?php if (!empty($leadership)): ?>
<section class="section" style="background-color: var(--color-surface-warm); border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="text-center" style="margin-bottom: 4rem;">
      <span style="font-size: 0.85rem; font-weight: 600; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Academic Founders</span>
      <h2 style="font-size: 2.25rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">Our Leadership Team</h2>
    </div>

    <div class="leadership-grid">
      <?php foreach ($leadership as $leader): ?>
        <div class="leader-card">
          <div style="height: 300px; background-color: var(--color-navy); display: flex; justify-content: center; align-items: center; color: #FFFFFF; font-size: 1.25rem; font-weight: 600; font-family: var(--font-primary);">
            <?php if (!empty($leader['image'])): ?>
              <img src="<?php echo h($leader['image']); ?>" alt="<?php echo h($leader['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
            <?php else: ?>
              <span><?php echo h($leader['name']); ?></span>
            <?php endif; ?>
          </div>
          <div style="padding: 1.75rem; text-align: center; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              <h3 style="font-size: 1.35rem; color: var(--color-navy); margin-bottom: 0.25rem; font-family: var(--font-primary);"><?php echo h($leader['name']); ?></h3>
              <p style="font-size: 0.85rem; color: var(--color-gold); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1.25rem;"><?php echo h($leader['designation']); ?></p>
              <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.7; margin-bottom: 1.5rem;">
                <?php 
                  $short_desc = $leader['short_description'] ?? '';
                  if (empty($short_desc) && !empty($leader['bio'])) {
                      $words = explode(' ', $leader['bio']);
                      if (count($words) > 30) {
                          $short_desc = implode(' ', array_slice($words, 0, 30)) . '...';
                      } else {
                          $short_desc = $leader['bio'];
                      }
                  }
                  echo h($short_desc); 
                ?>
              </p>
            </div>
            <div style="margin-top: auto; border-top: 1px solid var(--color-border); padding-top: 1rem;">
              <a href="/about/<?php echo h($leader['slug'] ?? ''); ?>" style="color: var(--color-teal); font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem; transition: color var(--transition-fast);" onmouseover="this.style.color='var(--color-navy)'" onmouseout="this.style.color='var(--color-teal)'">Read More &rarr;</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Timeline / School History removed completely as requested -->

<style>
  .leadership-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
    justify-content: center;
  }
  .leader-card {
    background-color: #FFFFFF;
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    display: flex;
    flex-direction: column;
    width: 100%;
  }
  @media (max-width: 1024px) {
    .leadership-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }
  @media (max-width: 600px) {
    .leadership-grid {
      grid-template-columns: 1fr;
    }
  }
  @media (max-width: 768px) {
    .about-card {
      padding: 1.75rem !important;
    }
  }
</style>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
