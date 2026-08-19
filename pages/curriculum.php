<?php
// Zuvio Global School - Our Curriculum Page Template
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// Fetch Stages
$stages = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM `curriculum_stages` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
        $stages = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("[Curriculum Stages Error] " . $e->getMessage());
    }
}

// Fetch Stage Items
$stage_items = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM `curriculum_items` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
        while ($row = $stmt->fetch()) {
            $stage_items[$row['stage_id']][] = $row;
        }
    } catch (Exception $e) {
        error_log("[Curriculum Items Error] " . $e->getMessage());
    }
}

// Fallbacks if database is offline
if (empty($stages)) {
    $stages = [
        ['id' => 1, 'name' => 'Early Years', 'description' => 'Introduction to fundamental social, cognitive, and physical development steps.'],
        ['id' => 2, 'name' => 'Primary School', 'description' => 'Core subjects foundational study (Grades 1 to 5).'],
        ['id' => 3, 'name' => 'Middle School', 'description' => 'Analytical thinking and specialized modules alignment (Grades 6 to 8).']
    ];
    $stage_items = [
        1 => [['title' => 'Curiosity and Discovery', 'description' => 'Focus on building exploratory senses and baseline language abilities.']],
        2 => [['title' => 'Core Foundations', 'description' => 'Mathematics, Science, English, and Social Studies aligned with CBSE/NIOS.']],
        3 => [
            ['title' => 'Analytical Growth', 'description' => 'Critical thinking, advanced science foundations, and initial technology exposure.'],
            ['title' => 'Extracurricular Activities', 'description' => 'Content pending - detailed grade-wise extracurricular activity lists will follow.']
        ]
    ];
}

$learning_loop = [
    ['title' => 'Discover', 'desc' => 'Sparking interest and raising questions about new concepts.'],
    ['title' => 'Understand', 'desc' => 'Building conceptual clarity through interactive guidance.'],
    ['title' => 'Apply', 'desc' => 'Putting knowledge to use in real-world contexts and exercises.'],
    ['title' => 'Collaborate', 'desc' => 'Peer learning, discussions, and joint project assignments.'],
    ['title' => 'Grow', 'desc' => 'Continuous improvement based on feedback and analytical reviews.']
];

$faqs = [
    ['q' => 'Is Zuvio Global School affiliated with CBSE?', 'a' => 'Our curriculum is fully aligned with the CBSE board guidelines, NEP 2020, and the National Curriculum Framework (NCF). We offer flexible, personalised pathways supporting online CBSE learning.'],
    ['q' => 'What online learning platform does Zuvio use?', 'a' => 'We deploy a premium, state-of-the-art US-based Learning Management System (LMS) specifically designed to bring live classes, curriculum modules, peer collaboration, resources, and progress analytics together in a single dashboard.'],
    ['q' => 'How are student assessments conducted?', 'a' => 'Assessments combine continuous formative projects, automated quizzes, and semester examinations designed to test analytical application rather than rote memorization. Detailed progress matrices are shared with parents.'],
    ['q' => 'What is the student-teacher ratio at Zuvio?', 'a' => 'To ensure personalised support and individualised attention, we maintain an institutional student-teacher ratio of 15:1 for all grade levels.']
];

$page_slug = 'our-curriculum';
include_once dirname(__FILE__) . '/../includes/header.php';
?>

<!-- Hero Banner Header -->
<section class="curriculum-hero" style="background-image: linear-gradient(rgba(0, 10, 66, 0.8), rgba(0, 10, 66, 0.85)), url('/assets/images/Students learning in classroom.png'); background-size: cover; background-position: center; color: #FFFFFF; padding: 6rem 2rem; text-align: center;">
  <div class="container" style="max-width: 800px;">
    <h1 style="font-size: 3rem; font-family: var(--font-primary); margin-bottom: 1rem; color: #FFFFFF;">Our Curriculum</h1>
    <p style="font-size: 1.15rem; font-weight: 300; line-height: 1.6; color: #E2E8F0; margin: 0;">
      A thoughtfully designed learning journey aligned with CBSE guidelines, NEP 2020, and NCF, supporting custom academic roadmaps.
    </p>
  </div>
</section>

<!-- Section 1: CBSE Aligned Overview -->
<section class="section" style="background-color: var(--color-white); border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="grid-2" style="align-items: center; gap: 4rem;">
      <div>
        <span style="font-size: 0.85rem; font-weight: 600; color: var(--color-gold); text-transform: uppercase; letter-spacing: 1.5px; display: block; margin-bottom: 0.5rem;">Academic Excellence</span>
        <h2 style="font-size: 2.25rem; color: var(--color-navy); margin-bottom: 1.5rem; font-family: var(--font-primary);">Rigorous Academic Framework</h2>
        <p style="color: var(--color-text); font-size: 1.05rem; line-height: 1.8; margin-bottom: 1.5rem;">
          Our academic framework is structured to deliver robust subject foundations while preparing students with modern competencies. We integrate the structured guidelines of CBSE with interactive, child-centered pedagogies.
        </p>
        <p style="color: var(--color-muted); font-size: 0.95rem; line-height: 1.7;">
          NEP 2020 and NCF-aligned teaching methodologies emphasize conceptual clarity over rote memorization, helping learners build critical thinking, logical analysis, and practical application skills from an early age.
        </p>
      </div>
      <div style="background-color: var(--color-navy); border-radius: var(--radius-lg); padding: 3rem 2.5rem; color: #FFFFFF; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; min-height: 350px;">
        <h3 style="font-size: 1.5rem; font-family: var(--font-primary); color: #FFFFFF; margin-bottom: 0.75rem;">Continuous Development Cycle</h3>
        <p style="color: #E2E8F0; font-size: 0.85rem; line-height: 1.6; max-width: 300px; margin-bottom: 1.5rem;">
          Our teachers perform continuous diagnostics and share monthly progress updates with parents.
        </p>
        <div style="width: 50px; height: 3px; background-color: var(--color-gold);"></div>
      </div>
    </div>
  </div>
</section>

<!-- Section 2: K-8 Academic Stages -->
<section class="section" style="background-color: var(--color-surface-blue); border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="text-center" style="margin-bottom: 4rem;">
      <span style="font-size: 0.85rem; font-weight: 600; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">School Stages</span>
      <h2 style="font-size: 2.25rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">Academic Pathways K-8</h2>
    </div>

    <div class="grid-3">
      <?php foreach ($stages as $stage): ?>
        <div class="card" style="border-top: 4px solid var(--color-gold); border-left: none; padding: 2.25rem;">
          <h3 style="font-size: 1.35rem; color: var(--color-navy); margin-bottom: 0.5rem;"><?php echo h($stage['name']); ?></h3>
          <p style="color: var(--color-muted); font-size: 0.9rem; line-height: 1.6; margin-bottom: 1.5rem;"><?php echo h($stage['description']); ?></p>
          
          <div style="border-top: 1px solid var(--color-border); padding-top: 1rem; margin-top: auto; display: flex; flex-direction: column; gap: 0.75rem;">
            <strong style="font-size: 0.8rem; text-transform: uppercase; color: var(--color-navy); letter-spacing: 0.5px;">Highlights:</strong>
            <?php 
              $items = $stage_items[$stage['id']] ?? [];
              if (!empty($items)):
                foreach ($items as $item):
            ?>
              <div style="font-size: 0.85rem; color: var(--color-text); display: flex; gap: 0.5rem;">
                <span style="color: var(--color-gold);">&#10004;</span>
                <div>
                  <strong><?php echo h($item['title']); ?>:</strong> <?php echo h($item['description']); ?>
                </div>
              </div>
            <?php 
                endforeach;
              else:
            ?>
              <span style="font-size: 0.75rem; color: var(--color-gold);">Content pending from school</span>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Section 3: Learning Model Loop -->
<section class="section" style="background-color: var(--color-white); border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="text-center" style="margin-bottom: 4rem;">
      <span style="font-size: 0.85rem; font-weight: 600; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Methodology</span>
      <h2 style="font-size: 2.25rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">Zuvio Learning Loop™</h2>
    </div>

    <div class="grid-5" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 1.5rem;">
      <?php foreach ($learning_loop as $idx => $step): ?>
        <div style="text-align: center; padding: 1rem;">
          <div style="width: 50px; height: 50px; border-radius: 50%; background-color: var(--color-navy-dark); color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 700; margin: 0 auto 1.25rem auto; font-family: var(--font-primary); border: 2px solid var(--color-gold);">
            <?php echo $idx + 1; ?>
          </div>
          <h3 style="font-size: 1.15rem; color: var(--color-navy); margin-bottom: 0.5rem;"><?php echo h($step['title']); ?></h3>
          <p style="color: var(--color-muted); font-size: 0.8rem; line-height: 1.6;"><?php echo h($step['desc']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Section 4: Accordion FAQs -->
<section class="section" style="background-color: var(--color-surface-warm); border-bottom: 1px solid var(--color-border);">
  <div class="container" style="max-width: 800px;">
    <div class="text-center" style="margin-bottom: 3.5rem;">
      <span style="font-size: 0.85rem; font-weight: 600; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Got Questions?</span>
      <h2 style="font-size: 2.25rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">Curriculum FAQs</h2>
    </div>

    <div style="display: flex; flex-direction: column; gap: 1.25rem;">
      <?php foreach ($faqs as $faq): ?>
        <details style="background-color: #FFFFFF; padding: 1.25rem 1.75rem; border-radius: var(--radius-md); border: 1px solid var(--color-border); box-shadow: var(--shadow-sm); cursor: pointer;" class="faq-details">
          <summary style="font-weight: 600; color: var(--color-navy); font-size: 1.05rem; display: flex; justify-content: space-between; align-items: center; outline: none; list-style: none;">
            <?php echo h($faq['q']); ?>
            <span class="faq-arrow" style="color: var(--color-gold); font-size: 1.25rem;">+</span>
          </summary>
          <p style="color: var(--color-muted); font-size: 0.9rem; line-height: 1.7; margin-top: 1rem; border-top: 1px solid var(--color-border); padding-top: 1rem; cursor: default;">
            <?php echo h($faq['a']); ?>
          </p>
        </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Section 5: CTA -->
<section class="section text-center" style="background-color: var(--color-navy-dark); color: #FFFFFF; padding: 6rem 0;">
  <div class="container" style="max-width: 600px;">
    <h2 style="font-size: 2.25rem; color: #FFFFFF; margin-bottom: 1.25rem; font-family: var(--font-primary);">Map Your Child's Academic Journey</h2>
    <p style="color: #E2E8F0; font-size: 1.05rem; margin-bottom: 2.5rem; line-height: 1.6;">
      Get in touch with our admissions coordinators to verify grade availability and structure personalized study paths.
    </p>
    <a href="/contact" class="btn btn-primary" style="padding: 1rem 3rem;">Enquire Now</a>
  </div>
</section>

<style>
  /* Accoridon animations */
  .faq-details[open] .faq-arrow {
    transform: rotate(45deg);
    display: inline-block;
  }
  @media (max-width: 768px) {
    .grid-5 {
      grid-template-columns: 1fr !important;
      gap: 2.5rem !important;
    }
  }
</style>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
