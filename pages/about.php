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

// Fallback arrays if database is offline
$story = $sections['our_story'] ?? [
    'title' => 'Our Story',
    'subtitle' => 'How Zuvio Began',
    'content' => 'Zuvio Global School began from a question: “What if education was designed for the child instead of expecting the child to fit the system?” As technology, AI and global connectivity changed the world, education also needed to evolve. Zuvio was conceived to provide flexible, personalised, globally connected learning that nurtures creativity, critical thinking, communication and adaptability.',
    'image' => '/assets/images/Hero image 2.png'
];

$vision_mission = $sections['vision_mission'] ?? [
    'title' => 'Vision, Mission & Beliefs',
    'subtitle' => 'Our Compass',
    'content' => "Vision: To create a world where every child can access future-ready, personalised and globally connected learning without boundaries, and to empower every child to become a confident global citizen of tomorrow.\n\nMission: To empower every child to discover their potential and thrive in a changing world by combining academic excellence with creativity, critical thinking, technology and life skills.\n\nEducational Philosophy: “Every Child. Every Mind. Every Possibility.”"
];

$philosophies = [
    ['title' => 'Curiosity', 'desc' => 'Nurturing the natural desire to learn and explore.'],
    ['title' => 'Creativity', 'desc' => 'Encouraging original thinking and problem solving.'],
    ['title' => 'Critical Thinking', 'desc' => 'Evaluating information objectively to make decisions.'],
    ['title' => 'Communication', 'desc' => 'Expressing ideas clearly and listening to others.'],
    ['title' => 'Collaboration', 'desc' => 'Working effectively with diverse teams.'],
    ['title' => 'Compassion', 'desc' => 'Understanding and sharing the feelings of others.'],
    ['title' => 'Character', 'desc' => 'Building integrity, resilience, and ethical values.'],
    ['title' => 'Citizenship', 'desc' => 'Fostering a sense of global responsibility and community.']
];

$page_slug = 'about';
include_once dirname(__FILE__) . '/../includes/header.php';
?>

<!-- Hero Banner Header -->
<section class="about-hero" style="background-image: linear-gradient(rgba(0, 10, 66, 0.82), rgba(0, 10, 66, 0.88)), url('/assets/images/Hero image 2.png'); background-size: cover; background-position: center; color: #FFFFFF; padding: 6rem 2rem; text-align: center;">
  <div class="container" style="max-width: 800px;">
    <h1 style="font-size: 3rem; font-family: var(--font-primary); margin-bottom: 1rem; color: #FFFFFF;">About Zuvio Global School</h1>
    <p style="font-size: 1.15rem; font-weight: 300; line-height: 1.6; color: #E2E8F0; margin: 0;">
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
        <img src="<?php echo h($story['image']); ?>" alt="Students studying online" style="width: 100%; height: 100%; object-fit: cover;">
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

    <div class="grid-4">
      <?php foreach ($philosophies as $phil): ?>
        <div class="card" style="padding: 1.75rem;">
          <h3 style="font-size: 1.15rem; color: var(--color-navy); margin-bottom: 0.75rem;"><?php echo h($phil['title']); ?></h3>
          <p style="color: var(--color-muted); font-size: 0.8rem; line-height: 1.6;"><?php echo h($phil['desc']); ?></p>
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

    <div style="display: flex; flex-wrap: wrap; gap: 3rem; justify-content: center;">
      <?php foreach ($leadership as $leader): ?>
        <div style="background-color: #FFFFFF; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm); width: 340px; display: flex; flex-direction: column;">
          <div style="height: 240px; background-color: var(--color-navy); display: flex; justify-content: center; align-items: center; color: #FFFFFF; font-size: 1.25rem; font-weight: 600; font-family: var(--font-primary);">
            <?php if (!empty($leader['image'])): ?>
              <img src="<?php echo h($leader['image']); ?>" alt="<?php echo h($leader['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
            <?php else: ?>
              <span><?php echo h($leader['name']); ?></span>
            <?php endif; ?>
          </div>
          <div style="padding: 1.75rem; text-align: center; flex-grow: 1;">
            <h3 style="font-size: 1.35rem; color: var(--color-navy); margin-bottom: 0.25rem;"><?php echo h($leader['name']); ?></h3>
            <p style="font-size: 0.85rem; color: var(--color-gold); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1.25rem;"><?php echo h($leader['designation']); ?></p>
            <p style="color: var(--color-muted); font-size: 0.85rem; line-height: 1.6; font-style: italic; margin-bottom: 1.5rem;"><?php echo h($leader['bio']); ?></p>
            <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.7; border-top: 1px solid var(--color-border); paddingTop: 1rem;"><?php echo h($leader['message']); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Section 5: Timeline -->
<?php if (!empty($timeline)): ?>
<section class="section" style="background-color: var(--color-white);">
  <div class="container">
    <div class="text-center" style="margin-bottom: 4rem;">
      <span style="font-size: 0.85rem; font-weight: 600; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">School History</span>
      <h2 style="font-size: 2.25rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">Our Growth Journey</h2>
    </div>

    <div style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: 2rem;">
      <?php foreach ($timeline as $item): ?>
        <div style="display: flex; gap: 2.5rem; border-left: 3px solid var(--color-gold); padding-left: 2rem; position: relative;">
          <div style="position: absolute; left: -10px; top: 0; width: 17px; height: 17px; border-radius: 50%; background-color: var(--color-gold);"></div>
          <div>
            <span style="font-size: 1.5rem; font-weight: 700; color: var(--color-navy); font-family: var(--font-primary); display: block; margin-bottom: 0.25rem;"><?php echo h($item['year']); ?></span>
            <h3 style="font-size: 1.15rem; color: var(--color-navy); margin-bottom: 0.5rem;"><?php echo h($item['title']); ?></h3>
            <p style="color: var(--color-muted); font-size: 0.9rem; line-height: 1.6;"><?php echo h($item['description']); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<style>
  @media (max-width: 768px) {
    .about-card {
      padding: 1.75rem !important;
    }
  }
</style>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
