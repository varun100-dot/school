<?php
// Zuvio Global School - Dedicated Special Education Page
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// CMS synchronization
if (!isset($_SESSION['mock_academics_cms'])) {
    $_SESSION['mock_academics_cms'] = [];
}
$ac_cms = &$_SESSION['mock_academics_cms'];

$special_ed = $ac_cms['special_ed'] ?? [
    'title' => 'Inclusive Learning & Special Education',
    'kicker' => 'Every Child Learns. Every Child Belongs.',
    'intro' => 'At Zuvio Global School, we believe education must adapt to the learner — never the child to the system. Our inclusive learning programme creates a supportive, flexible, and learner-centred environment where children with diverse needs can take part meaningfully, grow in confidence, and discover their unique strengths.',
    'pillars' => [
        ['title' => 'Personalised Learning', 'desc' => 'Flexible learning pace, individualised goals, and customized worksheets tailored to cognitive strengths.'],
        ['title' => 'Individual Attention', 'desc' => 'Small cohorts (1:15–1:20) and dedicated one-on-one check-ins ensuring no child feels overwhelmed.'],
        ['title' => 'Flexible Learning Rhythm', 'desc' => 'Learn from the comfort of home, free from sensory overload, peer pressure, or rigid traditional timetables.'],
        ['title' => 'Strength-Based Pedagogy', 'desc' => 'Focusing on what children love and do best, cultivating genuine self-esteem and independent agency.'],
        ['title' => 'Social & Emotional Growth', 'desc' => 'Gentle encouragement, empathy-driven teacher relationships, and a safe, inclusive peer atmosphere.'],
        ['title' => 'Close Family Partnership', 'desc' => 'Regular collaborative reviews with parents to calibrate IEP milestones and celebrate every win.']
    ]
];

$page_slug = 'special-education';
$seo = [
    'seo_title' => 'Special Education & Inclusive Learning | Zuvio Global School',
    'meta_description' => 'Dedicated Special Education (SEN) and neuroinclusive learning at Zuvio Global School. IEP programs for ADHD, autism, dyslexia, and diverse cognitive strengths.',
    'canonical_url' => BASE_URL . '/special-education',
    'og_title' => 'Special Education & Inclusive Learning — Zuvio Global School',
    'og_description' => 'Every Child Learns. Every Child Belongs. Discover our sensory-friendly online schooling, IEP framework, and specialized educator team.',
    'og_image' => '/assets/images/about_us_hero.jpg',
    'index_status' => 'index, follow'
];

include_once dirname(__FILE__) . '/../includes/header.php';

render_breadcrumbs([
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Academics', 'url' => '/academics'],
    ['label' => 'Special Education']
]);
?>

<main class="special-education-page">
  <!-- Hero Section -->
  <section class="section-hero" style="background-color: var(--pastel-blue); padding: 5rem 1.5rem; text-align: center; border-bottom: 1px solid var(--color-border);">
    <div class="container" style="max-width: 850px;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">
        <?php echo h($special_ed['kicker'] ?? 'Every Child Learns. Every Child Belongs.'); ?>
      </span>
      <h1 style="font-size: 2.85rem; font-family: var(--font-primary); margin-bottom: 1rem; color: var(--color-navy-dark); font-weight: 700; line-height: 1.2;">
        <?php echo h($special_ed['title']); ?>
      </h1>
      <p style="font-size: 1.15rem; font-weight: 500; line-height: 1.6; color: var(--color-text); margin: 0 auto; max-width: 740px;">
        Providing a sensory-safe, patient, and empowering academic home for neurodivergent minds and children with diverse learning needs.
      </p>
    </div>
  </section>

  <!-- Philosophy & Mission -->
  <section class="section" style="background-color: #FFFFFF; padding: 5.5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 3.5rem; align-items: center;">
        <div>
          <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.5rem;">
            Inclusive Philosophy
          </span>
          <h2 style="font-size: 2.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 1.25rem; line-height: 1.3;">
            Adapting the School to the Child
          </h2>
          <p style="color: var(--color-text); font-size: 1.05rem; line-height: 1.8; margin-bottom: 1.5rem;">
            <?php echo h($special_ed['intro']); ?>
          </p>
          <div style="background-color: var(--pastel-blue); border-left: 4px solid var(--color-teal); padding: 1.25rem; border-radius: 0 var(--radius-sm) var(--radius-sm) 0;">
            <p style="color: var(--color-navy); font-weight: 600; font-size: 0.95rem; margin: 0; line-height: 1.6;">
              We actively support children with ADHD, Autism Spectrum conditions, Dyslexia, Dyscalculia, Sensory Processing challenges, and gifted learners who require differentiated pacing.
            </p>
          </div>
        </div>

        <div>
          <div style="background: var(--color-surface-warm); border-radius: var(--radius-lg); padding: 2.5rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-md);">
            <h3 style="font-size: 1.35rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 1.25rem;">
              Why Online Schooling Excels for SEN
            </h3>
            <ul style="display: flex; flex-direction: column; gap: 1rem; color: var(--color-text); font-size: 0.95rem; line-height: 1.6; padding-left: 1.25rem; margin: 0;">
              <li><strong>Sensory-Friendly Space:</strong> Control ambient light, noise, and seating at home, eliminating playground anxiety and sensory fatigue.</li>
              <li><strong>Camera Flexibility:</strong> Freedom to move, use fidget aids, or take brief micro-breaks without feeling self-conscious.</li>
              <li><strong>Zero Bullying Risk:</strong> Fully moderated virtual classrooms overseen by empathetic teachers who protect child dignity.</li>
              <li><strong>Recorded Lectures:</strong> Re-watch explanations at 1x, 0.75x or pause to write notes without fear of missing out.</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 6 Pillars of Special Education -->
  <section class="section" style="background-color: var(--pastel-blue); padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Foundation of Care
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          6 Pillars of Inclusive Support
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          How we structure each school day to foster emotional security, cognitive achievement, and joyful learning.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.75rem;">
        <?php foreach ($special_ed['pillars'] as $i => $pillar): ?>
          <div style="background-color: #FFFFFF; border-radius: var(--radius-md); padding: 2.25rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              <div style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: var(--pastel-blue); color: var(--color-teal); font-weight: 800; font-size: 0.95rem; margin-bottom: 1rem;">
                0<?php echo $i + 1; ?>
              </div>
              <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.75rem;">
                <?php echo h($pillar['title']); ?>
              </h3>
              <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.6; margin: 0;">
                <?php echo h($pillar['desc']); ?>
              </p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- The IEP Journey (Individualized Education Plan) -->
  <section class="section" style="background-color: #FFFFFF; padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container" style="max-width: 960px;">
      <div class="text-center" style="margin-bottom: 3.5rem;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Structured Progression
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          The IEP (Individualized Education Plan) Roadmap
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          A collaborative framework created with parents, counselors, and educators.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem;">
        
        <div style="background: var(--color-surface-warm); padding: 2rem 1.5rem; border-radius: var(--radius-md); border: 1.5px solid rgba(6, 43, 99, 0.16); text-align: center;">
          <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--color-navy); color: var(--color-gold); font-weight: 700; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto;">1</div>
          <h4 style="font-size: 1.1rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">Intake Assessment</h4>
          <p style="font-size: 0.85rem; color: var(--color-text); line-height: 1.5; margin: 0;">
            Understanding current skills, sensory preferences, and learning triggers without examination stress.
          </p>
        </div>

        <div style="background: var(--color-surface-warm); padding: 2rem 1.5rem; border-radius: var(--radius-md); border: 1.5px solid rgba(6, 43, 99, 0.16); text-align: center;">
          <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--color-navy); color: var(--color-gold); font-weight: 700; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto;">2</div>
          <h4 style="font-size: 1.1rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">IEP Formulation</h4>
          <p style="font-size: 0.85rem; color: var(--color-text); line-height: 1.5; margin: 0;">
            Drafting measurable term goals, accommodation requirements, and customized learning milestones.
          </p>
        </div>

        <div style="background: var(--color-surface-warm); padding: 2rem 1.5rem; border-radius: var(--radius-md); border: 1.5px solid rgba(6, 43, 99, 0.16); text-align: center;">
          <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--color-navy); color: var(--color-gold); font-weight: 700; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto;">3</div>
          <h4 style="font-size: 1.1rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">Adaptive Delivery</h4>
          <p style="font-size: 0.85rem; color: var(--color-text); line-height: 1.5; margin: 0;">
            Classroom visual timers, structured breaks, assistive tech, and low student-to-teacher cohorts.
          </p>
        </div>

        <div style="background: var(--color-surface-warm); padding: 2rem 1.5rem; border-radius: var(--radius-md); border: 1.5px solid rgba(6, 43, 99, 0.16); text-align: center;">
          <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--color-navy); color: var(--color-gold); font-weight: 700; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto;">4</div>
          <h4 style="font-size: 1.1rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">Regular Reviews</h4>
          <p style="font-size: 0.85rem; color: var(--color-text); line-height: 1.5; margin: 0;">
            Monthly collaborative meetings with parents to adapt targets, celebrate growth, and recalibrate support.
          </p>
        </div>

      </div>
    </div>
  </section>

  <!-- Confidential Consultation CTA -->
  <section class="section" style="background-color: #FFFFFF; padding: 4.5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div style="background: linear-gradient(135deg, var(--color-navy-dark) 0%, var(--color-navy) 100%); border-radius: var(--radius-lg); padding: 3.5rem 2.5rem; color: #FFFFFF; text-align: center; border: 1.5px solid rgba(212, 175, 55, 0.3); box-shadow: var(--shadow-lg);">
        <h2 style="font-size: 2.2rem; color: #FFFFFF; font-family: var(--font-primary); margin-bottom: 1rem;">
          Schedule a Confidential SEN Consultation
        </h2>
        <p style="max-width: 650px; margin: 0 auto 2rem auto; color: rgba(255, 255, 255, 0.9); font-size: 1.05rem; line-height: 1.7;">
          Speak directly with our Special Education coordinator to understand how our environment can be customized for your child's specific needs.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
          <a href="/contact" class="btn btn-primary" style="background-color: var(--color-gold); color: var(--color-navy-dark); font-weight: 700; border: none; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Book SEN Consultation &rarr;
          </a>
          <a href="/curriculum" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid #FFFFFF; font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Curriculum Pathways
          </a>
          <a href="/electives" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid rgba(255, 255, 255, 0.6); font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Electives &amp; Skills
          </a>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include_once dirname(__FILE__) . '/../includes/footer.php'; ?>
