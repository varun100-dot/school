<?php
// Zuvio Global School - Dedicated Our Team Page
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// CMS synchronization
if (!isset($_SESSION['mock_about_cms'])) {
    $_SESSION['mock_about_cms'] = [];
}
$cms = &$_SESSION['mock_about_cms'];

// STRICT RULE: Rashmi Bhasin is 100% excluded
$default_team = [
    [
        'name' => 'Sharmin Habib',
        'slug' => 'sharmin-habib',
        'designation' => 'Co-Founder & Director',
        'image' => '/assets/images/Profile_Images/Sharmin_Professional_Profile.webp',
        'short_description' => 'Sharmin Habib is a seasoned educationist and edtech growth expert with over 18 years of experience. She has successfully founded and scaled preschools and digital K–8 learning models in domestic and international markets.',
        'badges' => ['18+ Years EdTech', 'K–8 Curriculum Specialist', 'Global Operations']
    ],
    [
        'name' => 'Deepak Jain',
        'slug' => 'deepak-jain',
        'designation' => 'Co-Founder & Director',
        'image' => '/assets/images/Profile_Images/Deepak_Professional_Profile.webp',
        'short_description' => 'Deepak Jain is an entrepreneur and business professional who brings a practical, growth-oriented perspective to Zuvio Global School. He oversees Zuvio’s strategic direction, operations, and partnerships.',
        'badges' => ['Strategic Governance', 'FinTech & Scale', 'Institutional Partnerships']
    ],
    [
        'name' => 'Pragya Jain',
        'slug' => 'pragya-jain',
        'designation' => 'Co-Founder & Director',
        'image' => '/assets/images/Profile_Images/Pragya_Professional_Profile.webp',
        'short_description' => 'Pragya Jain is an educationist dedicated to child-centric learning that prepares students for life. She conceptualized Zuvio to merge academic rigor with personalization, creativity, and future-ready skills.',
        'badges' => ['Child-Centric Pedagogy', 'Holistic Frameworks', 'Creative Mentorship']
    ]
];

$team = $cms['team'] ?? $default_team;
// Enforce exclusion of Rashmi Bhasin
$team = array_values(array_filter($team, function($member) {
    return stripos($member['name'] ?? '', 'Rashmi') === false && ($member['slug'] ?? '') !== 'rashmi-bhasin';
}));

$page_slug = 'our-team';
$seo = [
    'seo_title' => 'Our Team — Leadership & Faculty | Zuvio Global School',
    'meta_description' => 'Meet the passionate educational leaders, directors, and expert mentors shaping borderless, child-centric learning at Zuvio Global School.',
    'canonical_url' => BASE_URL . '/our-team',
    'og_title' => 'Our Team — Zuvio Global School',
    'og_description' => 'Meet the visionaries, educators, and mentors guiding Zuvio Global School students worldwide.',
    'og_image' => '/assets/images/Profile_Images/Pragya_Professional_Profile.webp',
    'index_status' => 'index, follow'
];

include_once dirname(__FILE__) . '/../includes/header.php';

render_breadcrumbs([
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'About Us', 'url' => '/about'],
    ['label' => 'Our Team']
]);
?>

<main class="our-team-page">
  <!-- Hero Section -->
  <section class="section-hero" style="background-color: var(--pastel-blue); padding: 5rem 1.5rem; text-align: center; border-bottom: 1px solid var(--color-border);">
    <div class="container" style="max-width: 850px;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">
        People Behind Zuvio
      </span>
      <h1 style="font-size: 2.85rem; font-family: var(--font-primary); margin-bottom: 1rem; color: var(--color-navy-dark); font-weight: 700; line-height: 1.2;">
        Our Leadership Team
      </h1>
      <p style="font-size: 1.15rem; font-weight: 500; line-height: 1.6; color: var(--color-text); margin: 0 auto; max-width: 720px;">
        Experienced educators, passionate visionaries, and strategic pioneers committed to child-centric, borderless learning.
      </p>
    </div>
  </section>

  <!-- Leadership Grid -->
  <section class="section" style="background-color: #FFFFFF; padding: 5.5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 700px; margin: 0 auto 4rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Guiding Leadership
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          Board of Directors
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          Driving our mission to make world-class, flexible K–8 education accessible everywhere.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2.5rem; max-width: 1140px; margin: 0 auto;">
        <?php foreach ($team as $member): ?>
          <div style="background: #FFFFFF; border-radius: var(--radius-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); overflow: hidden; display: flex; flex-direction: column; transition: transform 0.25s ease, box-shadow 0.25s ease;">
            
            <div style="height: 320px; background-color: var(--pastel-blue); position: relative; overflow: hidden;">
              <img src="<?php echo h($member['image']); ?>" alt="<?php echo h($member['name']); ?>" style="width: 100%; height: 100%; object-fit: cover; object-position: top center;">
              <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(10,37,64,0.7) 0%, transparent 100%); height: 80px;"></div>
            </div>

            <div style="padding: 2rem; display: flex; flex-direction: column; flex-grow: 1; justify-content: space-between;">
              <div>
                <h3 style="font-size: 1.5rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.25rem;">
                  <?php echo h($member['name']); ?>
                </h3>
                <div style="color: var(--color-gold); font-weight: 700; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1.25rem;">
                  <?php echo h($member['designation']); ?>
                </div>

                <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.7; margin-bottom: 1.5rem;">
                  <?php echo h($member['short_description']); ?>
                </p>

                <?php if (!empty($member['badges'])): ?>
                  <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.5rem;">
                    <?php foreach ($member['badges'] as $b): ?>
                      <span style="background: var(--pastel-blue); color: var(--color-teal); font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.65rem; border-radius: 4px;">
                        <?php echo h($b); ?>
                      </span>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>

              <div style="border-top: 1px solid var(--color-border); padding-top: 1.25rem;">
                <a href="/about/<?php echo h($member['slug']); ?>" style="color: var(--color-teal); font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.95rem;">
                  Read Full Bio &amp; Journey &rarr;
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Faculty & Mentorship Philosophy -->
  <section class="section" style="background-color: var(--pastel-blue); padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div style="max-width: 900px; margin: 0 auto; background: #FFFFFF; border-radius: var(--radius-lg); padding: 3.5rem 3rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm);">
        <div class="text-center" style="margin-bottom: 2.5rem;">
          <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
            The Educator Difference
          </span>
          <h2 style="font-size: 2.2rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
            Our Teaching &amp; Mentorship Standards
          </h2>
          <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
            Online schooling at Zuvio is powered by empathetic, certified educators who build genuine relationships.
          </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 2rem;">
          <div style="text-align: center;">
            <div style="font-size: 2.2rem; font-weight: 800; color: var(--color-teal); margin-bottom: 0.5rem;">1:15–1:20</div>
            <h4 style="font-size: 1.1rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">Low Cohort Ratio</h4>
            <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.6; margin: 0;">
              Guaranteed individual attention in every live classroom session so no learner gets left behind.
            </p>
          </div>

          <div style="text-align: center;">
            <div style="font-size: 2.2rem; font-weight: 800; color: var(--color-teal); margin-bottom: 0.5rem;">100%</div>
            <h4 style="font-size: 1.1rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">Certified Educators</h4>
            <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.6; margin: 0;">
              Rigorous background screening, subject qualifications, and child safety compliance protocols.
            </p>
          </div>

          <div style="text-align: center;">
            <div style="font-size: 2.2rem; font-weight: 800; color: var(--color-teal); margin-bottom: 0.5rem;">Ongoing</div>
            <h4 style="font-size: 1.1rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">Oxford &amp; SEN Training</h4>
            <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.6; margin: 0;">
              Continuous professional learning in neuroinclusive teaching and modern digital pedagogy.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Cross Navigation Strip -->
  <section class="section" style="background-color: #FFFFFF; padding: 4.5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div style="background: linear-gradient(135deg, var(--color-navy-dark) 0%, var(--color-navy) 100%); border-radius: var(--radius-lg); padding: 3.5rem 2.5rem; color: #FFFFFF; text-align: center; border: 1.5px solid rgba(212, 175, 55, 0.3); box-shadow: var(--shadow-lg);">
        <h2 style="font-size: 2.2rem; color: #FFFFFF; font-family: var(--font-primary); margin-bottom: 1rem;">
          Connect With Our Leadership
        </h2>
        <p style="max-width: 650px; margin: 0 auto 2rem auto; color: rgba(255, 255, 255, 0.9); font-size: 1.05rem; line-height: 1.7;">
          Have questions about our academic philosophy or wish to speak with an admissions counselor? We welcome your dialogue.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
          <a href="/founders-message" class="btn btn-primary" style="background-color: var(--color-gold); color: var(--color-navy-dark); font-weight: 700; border: none; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Read Founder’s Message &rarr;
          </a>
          <a href="/about-zuvio" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid #FFFFFF; font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            About Zuvio Philosophy
          </a>
          <a href="/contact" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid rgba(255, 255, 255, 0.6); font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Contact Leadership
          </a>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include_once dirname(__FILE__) . '/../includes/footer.php'; ?>
