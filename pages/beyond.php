<?php
// Zuvio Global School - Zuvio Beyond Page Template
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// Fetch Beyond Sections
$beyond_sections = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM `beyond_sections` WHERE `is_active` = 1");
        while ($row = $stmt->fetch()) {
            $beyond_sections[$row['section_key']] = $row;
        }
    } catch (Exception $e) {
        error_log("[Beyond Sections Error] " . $e->getMessage());
    }
}

// Fetch Gallery
$gallery = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM `beyond_gallery` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
        $gallery = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("[Beyond Gallery Error] " . $e->getMessage());
    }
}

// Section Fallbacks if Database is Offline
$intro = $beyond_sections['intro'] ?? [
    'title' => 'Beyond Academics',
    'subtitle' => 'Holistic Development at Zuvio',
    'content' => 'Zuvio goes beyond textbooks and examinations, with a focus on curiosity, creativity, critical thinking, communication, collaboration, real-world learning, character and life skills. Technology, innovation, projects, and global opportunities are central themes.'
];

$activities = $beyond_sections['activities_placeholder'] ?? [
    'title' => 'Our Extracurricular Programs',
    'subtitle' => 'Sports, Arts & Clubs',
    'content' => 'Content pending - Specific program descriptions, grades, and schedules for Sports, Music, Dance, Theatre, Visual Arts, Clubs, and Trips will remain draft placeholders until finalized.'
];

$default_activities = [
    ['title' => 'Sports & Physical Health', 'desc' => 'Individual fitness tracking, yoga sessions, and physical coordination routines.'],
    ['title' => 'Performing Arts', 'desc' => 'Introductory modules in music, dramatic arts, dance, and expression.'],
    ['title' => 'Clubs & STEM Workshops', 'desc' => 'Coding foundations, scientific experiments, and collaborative peer clubs.']
];

$page_slug = 'zuvio-beyond';
include_once dirname(__FILE__) . '/../includes/header.php';
?>

<!-- Hero Banner Header -->
<section class="beyond-hero" style="background-image: linear-gradient(rgba(0, 10, 66, 0.8), rgba(0, 10, 66, 0.85)), url('/assets/images/Teacher interacting with students.png'); background-size: cover; background-position: center; color: #FFFFFF; padding: 6rem 2rem; text-align: center;">
  <div class="container" style="max-width: 800px;">
    <h1 style="font-size: 3rem; font-family: var(--font-primary); margin-bottom: 1rem; color: #FFFFFF;">Zuvio Beyond</h1>
    <p style="font-size: 1.15rem; font-weight: 300; line-height: 1.6; color: #E2E8F0; margin: 0;">
      Beyond academics—nurturing creativity, character, life skills, and physical well-being through dynamic programs.
    </p>
  </div>
</section>

<!-- Section 1: Intro Philosophy -->
<section class="section" style="background-color: var(--color-white); border-bottom: 1px solid var(--color-border);">
  <div class="container" style="max-width: 800px; text-align: center;">
    <span style="font-size: 0.85rem; font-weight: 600; color: var(--color-gold); text-transform: uppercase; letter-spacing: 1.5px;"><?php echo h($intro['subtitle']); ?></span>
    <h2 style="font-size: 2.25rem; color: var(--color-navy); margin: 1rem 0 1.5rem 0; font-family: var(--font-primary);"><?php echo h($intro['title']); ?></h2>
    <p style="color: var(--color-muted); font-size: 1.1rem; line-height: 1.8;"><?php echo h($intro['content']); ?></p>
  </div>
</section>

<!-- Section 2: Program Cards -->
<section class="section" style="background-color: var(--color-surface-blue); border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="text-center" style="margin-bottom: 4rem;">
      <span style="font-size: 0.85rem; font-weight: 600; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;"><?php echo h($activities['subtitle']); ?></span>
      <h2 style="font-size: 2.25rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);"><?php echo h($activities['title']); ?></h2>
    </div>

    <div class="grid-3" style="margin-bottom: 3.5rem;">
      <?php foreach ($default_activities as $act): ?>
        <div class="card" style="border-top: 4px solid var(--color-gold); border-left: none; padding: 2.25rem;">
          <h3 style="font-size: 1.3rem; color: var(--color-navy); margin-bottom: 0.75rem;"><?php echo h($act['title']); ?></h3>
          <p style="color: var(--color-muted); font-size: 0.85rem; line-height: 1.6;"><?php echo h($act['desc']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="text-center" style="max-width: 700px; margin: 0 auto; background-color: var(--color-white); border-radius: var(--radius-md); padding: 2rem; box-shadow: var(--shadow-sm); border-left: 4px solid var(--color-gold);">
      <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.6; margin: 0;">
        <strong>Advisory:</strong> <?php echo h($activities['content']); ?>
      </p>
    </div>
  </div>
</section>

<!-- Section 3: Gallery (Dynamic Photo Grid) -->
<?php if (!empty($gallery)): ?>
<section class="section" style="background-color: var(--color-white); border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="text-center" style="margin-bottom: 4rem;">
      <span style="font-size: 0.85rem; font-weight: 600; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Visuals</span>
      <h2 style="font-size: 2.25rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">Student Life Gallery</h2>
    </div>

    <div class="grid-3">
      <?php foreach ($gallery as $img): ?>
        <div style="border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm); height: 260px; position: relative;" class="gallery-wrapper">
          <img src="<?php echo h($img['image']); ?>" alt="<?php echo h($img['title'] ?: 'Student activity'); ?>" style="width: 100%; height: 100%; object-fit: cover;">
          <div style="position: absolute; bottom: 0; left: 0; width: 100%; background: linear-gradient(transparent, rgba(0,10,66,0.9)); padding: 1.5rem 1.25rem; color: #FFFFFF;" class="gallery-caption">
            <span style="font-size: 0.75rem; text-transform: uppercase; color: var(--color-gold); font-weight: 600; letter-spacing: 0.5px;"><?php echo h($img['category'] ?: 'Activity'); ?></span>
            <h3 style="font-size: 1.05rem; margin-top: 0.25rem; color: #FFFFFF; font-family: var(--font-secondary); font-weight: 600;"><?php echo h($img['title']); ?></h3>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Section 4: CTA -->
<section class="section text-center" style="background-color: var(--color-surface-warm); padding: 6rem 0;">
  <div class="container" style="max-width: 600px;">
    <h2 style="font-size: 2.25rem; color: var(--color-navy); margin-bottom: 1.25rem; font-family: var(--font-primary);">Fostering Talents Beyond Boundaries</h2>
    <p style="color: var(--color-muted); font-size: 1.05rem; margin-bottom: 2.5rem; line-height: 1.6;">
      Want to learn more about club choices and physical schedule allocations? Let's discuss during your counselling roadmap session.
    </p>
    <a href="/contact" class="btn btn-primary" style="padding: 1rem 3rem;">Enquire Now</a>
  </div>
</section>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
