<?php
// Zuvio Global School - Dedicated About Zuvio Page
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// CMS data synchronization
if (!isset($_SESSION['mock_about_cms'])) {
    $_SESSION['mock_about_cms'] = [];
}
$cms = &$_SESSION['mock_about_cms'];

$story = $cms['story'] ?? [
    'title' => 'Learning Without Boundaries, Growing With Purpose',
    'subtitle' => 'About Zuvio',
    'content' => "Zuvio began with a simple observation: too many children are asked to fit into a system, rather than the system being designed to fit the child.\n\nTraditional schooling often requires conformity over curiosity, rigid schedules over natural rhythms, and a one-size-fits-all approach that leaves many students underserved — whether they need more time to master a concept, more room to run ahead, or simply an environment where they feel safe and understood.\n\nZuvio Global School was founded to offer an alternative — not an alternative that compromises on quality, but one that raises the bar for what education can be.\n\nWe bring together a structured, curriculum-aligned programme, caring teachers, and thoughtful technology to create a flexible, personalised learning experience your child can access from anywhere in the world.",
    'image' => '/assets/images/about_us_hero.jpg'
];

$vision_mission = $cms['vision_mission'] ?? [
    'vision' => 'To redefine the future of education by creating a dynamic, borderless learning environment where students from every corner of the world can thrive academically, think critically, and evolve into compassionate, future-ready global leaders.',
    'mission' => 'Our mission is to revolutionize education through a cutting-edge online learning platform that integrates futuristic teaching methods, personalized pathways, and holistic development to unlock the unique potential of every child.'
];

$default_values = [
    ['title' => 'Child at the Centre', 'desc' => 'Every child is an individual, not a cohort. Their strengths, pace, and interests shape the journey.', 'icon' => '🎯'],
    ['title' => 'Inclusion by Design', 'desc' => 'An environment built from day one to welcome every kind of mind — neurotypical, neurodivergent, gifted, or simply different.', 'icon' => '🤝'],
    ['title' => 'Personalised Learning', 'desc' => 'Learning pathways that flex to fit the student, not rigid timetables that force students into a mould.', 'icon' => '🌱'],
    ['title' => 'Growth Not Just Marks', 'desc' => 'Academic achievement matters deeply, but so does confidence, critical thinking, emotional resilience, and character.', 'icon' => '📈'],
    ['title' => 'Beyond Academics', 'desc' => 'A complete school experience — clubs, sports, competitions, exhibitions, and real-world life skills.', 'icon' => '🎨'],
    ['title' => 'Learning Without Boundaries', 'desc' => 'Quality education that travels with the child. Accessible from anywhere in the world.', 'icon' => '🌍']
];
$values = $cms['values'] ?? $default_values;

$default_apart = [
    ['title' => 'Online but Deeply Human', 'desc' => 'Small interactive live classes, dedicated mentors, and real relationships — never pre-recorded video lectures.', 'tag' => 'Human Touch'],
    ['title' => 'Personalised by Default', 'desc' => 'Customised pace, targeted support, and pathways tailored to each child’s unique learning style and needs.', 'tag' => 'Tailored Pace'],
    ['title' => 'Inclusive by Design', 'desc' => 'Specialised support, SEN certified educators, and a culture where every learner belongs and flourishes.', 'tag' => 'Neuroinclusive'],
    ['title' => 'Flexible for Real Life', 'desc' => 'Timetables and structures that support families traveling, student athletes, artists, and homeschooling paths.', 'tag' => 'Anytime Anywhere'],
    ['title' => 'Beyond Academics', 'desc' => 'Holistic co-curricular programmes, leadership clubs, debate, coding, and sports integration through ISSO.', 'tag' => '360° Growth'],
    ['title' => 'Parents as Partners', 'desc' => 'Transparent progress tracking, regular open dialogues, and collaborative goal setting for student success.', 'tag' => 'Collaborative']
];
$apart_cards = $cms['apart'] ?? $default_apart;

$default_approach = [
    ['letter' => 'Z', 'title' => 'Zoomed-In Attention', 'desc' => 'Small cohorts, frequent individual check-ins, and dedicated teacher focus ensuring no child is overlooked.'],
    ['letter' => 'U', 'title' => 'Understand Every Learner', 'desc' => 'Diagnostic assessments that recognise cognitive strengths, emotional needs, and individual learning preferences.'],
    ['letter' => 'V', 'title' => 'Versatile Pathways', 'desc' => 'Flexible curriculum choices, customizable pacing, and elective enrichment tailored to future aspirations.'],
    ['letter' => 'I', 'title' => 'Inclusive by Design', 'desc' => 'Neurodivergent support, SEN-trained educators, and differentiated instruction welcoming all minds.'],
    ['letter' => 'O', 'title' => 'Opportunities Without Boundaries', 'desc' => 'Global student peers, international olympiads, and borderless learning accessible anywhere on Earth.']
];
$approach_cards = $cms['approach'] ?? $default_approach;

$default_audiences = [
    ['title' => 'Flexible Learning Families', 'desc' => 'Families seeking flexible learning schedules that adapt seamlessly to family lifestyle, commitments, and relocation.'],
    ['title' => 'Homeschooling Families', 'desc' => 'Alternative-learning and homeschooling families looking for structured, recognized international curriculum accreditation.'],
    ['title' => 'Globally Mobile & Expats', 'desc' => 'Expat, diplomatic, and traveling families requiring continuous, uninterrupted schooling with recognized global credentials.'],
    ['title' => 'Young Athletes & Performers', 'desc' => 'Students pursuing competitive sports, fine arts, music, or performance careers needing rigorous yet adaptable academics.'],
    ['title' => 'Calm-Environment Learners', 'desc' => 'Children who flourish better in calm, distraction-free, supportive online settings free from traditional classroom anxiety.'],
    ['title' => 'Personalised Pace Seekers', 'desc' => 'Students who want to accelerate in areas of strength or take measured, dedicated time to master challenging concepts.'],
    ['title' => 'Future-Ready Seekers', 'desc' => 'Parents prioritising 21st-century critical thinking, ethical digital literacy, communication, and emotional resilience.'],
    ['title' => 'Neuroinclusive Needs', 'desc' => 'Children with ADHD, autism, or delayed learning who thrive with individualized attention, patience, and expert guidance.']
];
$audiences = $cms['audiences'] ?? $default_audiences;

$page_slug = 'about-zuvio';
$seo = [
    'seo_title' => 'About Zuvio — Vision, Mission & Philosophy | Zuvio Global School',
    'meta_description' => 'Discover the story, vision, values, and pedagogical philosophy behind Zuvio Global School — an accredited leader in child-centric online schooling.',
    'canonical_url' => BASE_URL . '/about-zuvio',
    'og_title' => 'About Zuvio — Zuvio Global School',
    'og_description' => 'Reimagining education for a world without boundaries. Learn about our vision, values, approach, and learner profiles.',
    'og_image' => '/assets/images/about_us_hero.jpg',
    'index_status' => 'index, follow'
];

include_once dirname(__FILE__) . '/../includes/header.php';

render_breadcrumbs([
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'About Us', 'url' => '/about'],
    ['label' => 'About Zuvio']
]);
?>

<main class="about-zuvio-page">
  <!-- Hero Section -->
  <section class="section-hero" style="background-color: var(--pastel-blue); padding: 5rem 1.5rem; text-align: center; border-bottom: 1px solid var(--color-border);">
    <div class="container" style="max-width: 850px;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">
        <?php echo h($story['subtitle'] ?? 'About Zuvio'); ?>
      </span>
      <h1 style="font-size: 2.85rem; font-family: var(--font-primary); margin-bottom: 1rem; color: var(--color-navy-dark); font-weight: 700; line-height: 1.2;">
        <?php echo h($story['title']); ?>
      </h1>
      <p style="font-size: 1.15rem; font-weight: 500; line-height: 1.6; color: var(--color-text); margin: 0 auto; max-width: 720px;">
        Reimagining education for a world without boundaries — where every child has the freedom to learn, explore, and grow with confidence.
      </p>
    </div>
  </section>

  <!-- Story & Genesis Section -->
  <section class="section" style="background-color: #FFFFFF; padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 3.5rem; align-items: center;">
        <div>
          <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.5rem;">
            Our Genesis
          </span>
          <h2 style="font-size: 2.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 1.5rem; line-height: 1.3;">
            A School Designed to Fit the Child
          </h2>
          <div style="color: var(--color-text); font-size: 1.05rem; line-height: 1.8; display: flex; flex-direction: column; gap: 1.25rem;">
            <?php 
              $paragraphs = explode("\n\n", $story['content']);
              foreach ($paragraphs as $p):
                if (trim($p)):
            ?>
              <p><?php echo nl2br(h(trim($p))); ?></p>
            <?php 
                endif;
              endforeach; 
            ?>
          </div>
        </div>

        <div style="position: relative;">
          <div style="border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-md); border: 1.5px solid rgba(6, 43, 99, 0.16);">
            <img src="<?php echo h($story['image'] ?? '/assets/images/about_us_hero.jpg'); ?>" alt="About Zuvio Global School" style="width: 100%; height: auto; display: block; object-fit: cover;">
          </div>
          <div style="position: absolute; bottom: -20px; right: 20px; background: var(--color-navy-dark); color: #FFFFFF; padding: 1.25rem 1.75rem; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border-left: 4px solid var(--color-gold);">
            <div style="font-size: 1.6rem; font-weight: 700; color: var(--color-gold); font-family: var(--font-primary);">100% Online</div>
            <div style="font-size: 0.85rem; opacity: 0.9;">Accredited K–8 Global School</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Vision & Mission -->
  <section class="section" style="background-color: var(--pastel-blue); padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 700px; margin: 0 auto 3.5rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Guiding Compass
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          Vision & Mission
        </h2>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem;">
        <div style="background-color: #FFFFFF; border-radius: var(--radius-md); padding: 2.5rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); border-top: 4px solid var(--color-teal); display: flex; flex-direction: column; gap: 1rem;">
          <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 50px; height: 50px; border-radius: 12px; background: var(--pastel-blue); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
              🔭
            </div>
            <h3 style="font-size: 1.5rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0;">Our Vision</h3>
          </div>
          <p style="color: var(--color-text); font-size: 1.05rem; line-height: 1.7; margin: 0;">
            <?php echo h($vision_mission['vision']); ?>
          </p>
        </div>

        <div style="background-color: #FFFFFF; border-radius: var(--radius-md); padding: 2.5rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); border-top: 4px solid var(--color-gold); display: flex; flex-direction: column; gap: 1rem;">
          <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 50px; height: 50px; border-radius: 12px; background: #FFF9E6; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
              🚀
            </div>
            <h3 style="font-size: 1.5rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0;">Our Mission</h3>
          </div>
          <p style="color: var(--color-text); font-size: 1.05rem; line-height: 1.7; margin: 0;">
            <?php echo h($vision_mission['mission']); ?>
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Core Values (6) -->
  <section class="section" style="background-color: #FFFFFF; padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 700px; margin: 0 auto 3.5rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          What Matters at Zuvio
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          Our Core Values
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          Principles that guide every lesson, interaction, and educational breakthrough.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.75rem;">
        <?php foreach ($values as $val): ?>
          <div style="background-color: var(--color-surface-warm); border-radius: var(--radius-md); padding: 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); transition: transform 0.2s ease, box-shadow 0.2s ease;">
            <div style="font-size: 2rem; margin-bottom: 1rem;"><?php echo h($val['icon'] ?? '✨'); ?></div>
            <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">
              <?php echo h($val['title']); ?>
            </h3>
            <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.6; margin: 0;">
              <?php echo h($val['desc']); ?>
            </p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- What Sets Us Apart (6 Cards) -->
  <section class="section" style="background-color: var(--pastel-blue); padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 700px; margin: 0 auto 3.5rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Distinctive Advantage
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          What Sets Us Apart
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          Bridging the warmth of physical schooling with the limitless agility of the digital world.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.75rem;">
        <?php foreach ($apart_cards as $card): ?>
          <div style="background-color: #FFFFFF; border-radius: var(--radius-md); padding: 2.25rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              <span style="display: inline-block; background-color: var(--pastel-blue); color: var(--color-teal); font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; padding: 0.3rem 0.75rem; border-radius: 20px; margin-bottom: 1rem;">
                <?php echo h($card['tag']); ?>
              </span>
              <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.75rem;">
                <?php echo h($card['title']); ?>
              </h3>
              <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.6; margin: 0;">
                <?php echo h($card['desc']); ?>
              </p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- The ZUVIO Approach (Z-U-V-I-O) -->
  <section class="section" style="background-color: #FFFFFF; padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Pedagogical Framework
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          The ZUVIO Approach
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          Five interconnected pillars engineered to unlock the best version of every young scholar.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
        <?php foreach ($approach_cards as $app): ?>
          <div style="background-color: var(--color-surface-warm); border-radius: var(--radius-md); padding: 2rem 1.5rem; text-align: center; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm);">
            <div style="width: 54px; height: 54px; border-radius: 50%; background: var(--color-navy); color: var(--color-gold); font-weight: 800; font-size: 1.5rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem auto; font-family: var(--font-primary); box-shadow: var(--shadow-sm);">
              <?php echo h($app['letter']); ?>
            </div>
            <h3 style="font-size: 1.15rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">
              <?php echo h($app['title']); ?>
            </h3>
            <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.6; margin: 0;">
              <?php echo h($app['desc']); ?>
            </p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Who Should Choose Zuvio (8 Audiences) -->
  <section class="section" style="background-color: var(--pastel-blue); padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Tailored For Diverse Paths
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          Who Should Choose Zuvio?
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          Designed for families seeking purposeful flexibility, academic depth, and individual respect.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem;">
        <?php foreach ($audiences as $aud): ?>
          <div style="background-color: #FFFFFF; border-radius: var(--radius-md); padding: 1.75rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm);">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
              <span style="color: var(--color-teal); font-size: 1.25rem;">✓</span>
              <h3 style="font-size: 1.1rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0;">
                <?php echo h($aud['title']); ?>
              </h3>
            </div>
            <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; margin: 0;">
              <?php echo h($aud['desc']); ?>
            </p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Dedicated Subpage Navigation Strip / CTA -->
  <section class="section" style="background-color: #FFFFFF; padding: 4.5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div style="background: linear-gradient(135deg, var(--color-navy-dark) 0%, var(--color-navy) 100%); border-radius: var(--radius-lg); padding: 3.5rem 2.5rem; color: #FFFFFF; text-align: center; border: 1.5px solid rgba(212, 175, 55, 0.3); box-shadow: var(--shadow-lg);">
        <h2 style="font-size: 2.2rem; color: #FFFFFF; font-family: var(--font-primary); margin-bottom: 1rem;">
          Continue Exploring About Us
        </h2>
        <p style="max-width: 650px; margin: 0 auto 2rem auto; color: rgba(255, 255, 255, 0.9); font-size: 1.05rem; line-height: 1.7;">
          Meet our experienced leadership team, read the Founder's personal letter, or verify our global accreditation credentials.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
          <a href="/our-team" class="btn btn-primary" style="background-color: var(--color-gold); color: var(--color-navy-dark); font-weight: 700; border: none; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Meet Our Team &rarr;
          </a>
          <a href="/founders-message" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid #FFFFFF; font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Founder’s Message
          </a>
          <a href="/affiliations-accreditations" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid rgba(255, 255, 255, 0.6); font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Affiliations & Accreditations
          </a>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include_once dirname(__FILE__) . '/../includes/footer.php'; ?>
