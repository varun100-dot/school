<?php
// Zuvio Global School - Dedicated Affiliations & Accreditations Page
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

$page_slug = 'affiliations-accreditations';
$seo = [
    'seo_title' => 'Affiliations & Accreditations — ISSO, IAO, Oxford Quality | Zuvio Global School',
    'meta_description' => 'Verify Zuvio Global School’s official educational affiliations: IAO Accredited (Affiliation No: IA 4883), ISSO Sports Member, and Oxford Quality Partner.',
    'canonical_url' => BASE_URL . '/affiliations-accreditations',
    'og_title' => 'Affiliations & Accreditations — Zuvio Global School',
    'og_description' => 'Committed to world-class educational benchmarks and certified international standards. Explore our IAO, ISSO, and Oxford Quality credentials.',
    'og_image' => '/assets/images/iao-logo.png',
    'index_status' => 'index, follow'
];

include_once dirname(__FILE__) . '/../includes/header.php';

render_breadcrumbs([
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'About Us', 'url' => '/about'],
    ['label' => 'Affiliations & Accreditations']
]);
?>

<main class="affiliations-accreditations-page">
  <!-- Hero Section -->
  <section class="section-hero" style="background-color: var(--pastel-blue); padding: 5rem 1.5rem; text-align: center; border-bottom: 1px solid var(--color-border);">
    <div class="container" style="max-width: 850px;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">
        Global Trust &amp; Standards
      </span>
      <h1 style="font-size: 2.85rem; font-family: var(--font-primary); margin-bottom: 1rem; color: var(--color-navy-dark); font-weight: 700; line-height: 1.2;">
        Affiliations &amp; Accreditations
      </h1>
      <p style="font-size: 1.15rem; font-weight: 500; line-height: 1.6; color: var(--color-text); margin: 0 auto; max-width: 720px;">
        Affiliation No: IA 4883 &bull; IAO Accredited &bull; ISSO Member &bull; Oxford Quality Curriculum
      </p>
    </div>
  </section>

  <!-- Core Accreditations Showcase -->
  <section class="section" style="background-color: #FFFFFF; padding: 5.5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 4rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Certified Excellence
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          Our International Partners &amp; Affiliations
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          Every partnership is chosen to guarantee educational rigor, global sports integration, and recognized credentials.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2.5rem; max-width: 1140px; margin: 0 auto;">
        
        <!-- IAO Card -->
        <div style="background: #FFFFFF; border-radius: var(--radius-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); padding: 2.5rem; display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid var(--color-gold);">
          <div>
            <div style="height: 80px; display: flex; align-items: center; margin-bottom: 1.5rem;">
              <img src="/assets/images/iao-logo.png" alt="IAO Logo" style="max-height: 70px; max-width: 180px; object-fit: contain;">
            </div>

            <div style="display: inline-block; background: #FFF9E6; color: var(--color-navy-dark); font-weight: 700; font-size: 0.8rem; padding: 0.3rem 0.75rem; border-radius: 4px; margin-bottom: 1rem; border: 1px solid rgba(212,175,55,0.4);">
              Affiliation No: IA 4883
            </div>

            <h3 style="font-size: 1.4rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.35rem;">
              IAO Accredited
            </h3>
            <h4 style="font-size: 0.9rem; color: var(--color-teal); font-weight: 600; margin-bottom: 1rem;">
              International Accreditation Organization
            </h4>

            <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.7; margin-bottom: 1.5rem;">
              IAO accreditation is an internationally recognized seal of academic quality. It validates Zuvio’s robust curriculum frameworks, faculty qualifications, assessment integrity, student support systems, and organizational governance against leading global educational standards.
            </p>
          </div>

          <div style="border-top: 1px solid var(--color-border); padding-top: 1.25rem;">
            <a href="https://www.iao.org/India-Delhi/Zuvio-Global-School" target="_blank" rel="noopener" style="display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; background: var(--color-navy); color: #FFFFFF; font-weight: 600; padding: 0.75rem 1.25rem; border-radius: var(--radius-sm); text-decoration: none; width: 100%; text-align: center; transition: background 0.2s;">
              Verify Official IAO Certificate
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
            </a>
          </div>
        </div>

        <!-- ISSO Card -->
        <div style="background: #FFFFFF; border-radius: var(--radius-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); padding: 2.5rem; display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid var(--color-teal);">
          <div>
            <div style="height: 80px; display: flex; align-items: center; margin-bottom: 1.5rem;">
              <img src="/assets/images/isso-logo.png" alt="ISSO Logo" style="max-height: 70px; max-width: 180px; object-fit: contain;">
            </div>

            <div style="display: inline-block; background: var(--pastel-blue); color: var(--color-navy); font-weight: 700; font-size: 0.8rem; padding: 0.3rem 0.75rem; border-radius: 4px; margin-bottom: 1rem;">
              Sports Member School
            </div>

            <h3 style="font-size: 1.4rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.35rem;">
              ISSO Member
            </h3>
            <h4 style="font-size: 0.9rem; color: var(--color-teal); font-weight: 600; margin-bottom: 1rem;">
              International Schools Sports Organisation
            </h4>

            <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.7; margin-bottom: 1.5rem;">
              As an ISSO member school, Zuvio Global School ensures student-athletes have structured access to regional, national, and international tournaments. Our students compete alongside premier international schools, building teamwork, resilience, and sportsmanship.
            </p>
          </div>

          <div style="border-top: 1px solid var(--color-border); padding-top: 1.25rem;">
            <a href="https://www.issosports.org/" target="_blank" rel="noopener" style="display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; background: var(--color-teal); color: #FFFFFF; font-weight: 600; padding: 0.75rem 1.25rem; border-radius: var(--radius-sm); text-decoration: none; width: 100%; text-align: center; transition: opacity 0.2s;">
              Visit ISSO Sports Portal &rarr;
            </a>
          </div>
        </div>

        <!-- Oxford Quality Card -->
        <div style="background: #FFFFFF; border-radius: var(--radius-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); padding: 2.5rem; display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid var(--color-navy);">
          <div>
            <div style="height: 80px; display: flex; align-items: center; margin-bottom: 1.5rem;">
              <img src="/assets/images/oxford-logo.png" alt="Oxford Quality Logo" style="max-height: 70px; max-width: 180px; object-fit: contain;">
            </div>

            <div style="display: inline-block; background: var(--pastel-blue); color: var(--color-navy); font-weight: 700; font-size: 0.8rem; padding: 0.3rem 0.75rem; border-radius: 4px; margin-bottom: 1rem;">
              Curriculum &amp; Pedagogical Partner
            </div>

            <h3 style="font-size: 1.4rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.35rem;">
              Oxford Quality
            </h3>
            <h4 style="font-size: 0.9rem; color: var(--color-teal); font-weight: 600; margin-bottom: 1rem;">
              Oxford University Press
            </h4>

            <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.7; margin-bottom: 1.5rem;">
              The Oxford Quality Programme represents an agreement with Oxford University Press to use high-quality educational materials and continuous professional development for teachers. Students benefit from globally researched textbooks, graded readers, and structured phonics.
            </p>
          </div>

          <div style="border-top: 1px solid var(--color-border); padding-top: 1.25rem;">
            <a href="/curriculum" style="display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; background: var(--color-navy-dark); color: #FFFFFF; font-weight: 600; padding: 0.75rem 1.25rem; border-radius: var(--radius-sm); text-decoration: none; width: 100%; text-align: center;">
              Explore Oxford Curriculum &rarr;
            </a>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- Quality Assurance Framework Checklist -->
  <section class="section" style="background-color: var(--pastel-blue); padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div style="max-width: 900px; margin: 0 auto; background: #FFFFFF; border-radius: var(--radius-lg); padding: 3.5rem 3rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm);">
        <div class="text-center" style="margin-bottom: 2.5rem;">
          <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
            Trust &amp; Governance
          </span>
          <h2 style="font-size: 2.2rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
            Our Quality Assurance Commitment
          </h2>
          <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
            How our accredited status translates into everyday excellence for your child.
          </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem;">
          <div style="border-left: 3px solid var(--color-gold); padding-left: 1.25rem;">
            <h4 style="font-size: 1.1rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.35rem;">Transparent Assessments</h4>
            <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; margin: 0;">
              Formative and summative assessment rubrics benchmarked against CBSE, NEP 2020, and international grade requirements.
            </p>
          </div>

          <div style="border-left: 3px solid var(--color-gold); padding-left: 1.25rem;">
            <h4 style="font-size: 1.1rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.35rem;">Child Safeguarding</h4>
            <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; margin: 0;">
              100% moderated virtual classrooms, encrypted infrastructure, zero unmonitored links, and strict child protection policies.
            </p>
          </div>

          <div style="border-left: 3px solid var(--color-gold); padding-left: 1.25rem;">
            <h4 style="font-size: 1.1rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.35rem;">Seamless Transferability</h4>
            <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; margin: 0;">
              Recognized transcripts, report cards, and migration certificates facilitate smooth transition back into physical or overseas schools.
            </p>
          </div>

          <div style="border-left: 3px solid var(--color-gold); padding-left: 1.25rem;">
            <h4 style="font-size: 1.1rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.35rem;">Continuous Faculty Audits</h4>
            <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; margin: 0;">
              Peer observations, student feedback loops, and Oxford University Press pedagogical refresher seminars held every quarter.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Subpage Navigation & Admissions CTA -->
  <section class="section" style="background-color: #FFFFFF; padding: 4.5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div style="background: linear-gradient(135deg, var(--color-navy-dark) 0%, var(--color-navy) 100%); border-radius: var(--radius-lg); padding: 3.5rem 2.5rem; color: #FFFFFF; text-align: center; border: 1.5px solid rgba(212, 175, 55, 0.3); box-shadow: var(--shadow-lg);">
        <h2 style="font-size: 2.2rem; color: #FFFFFF; font-family: var(--font-primary); margin-bottom: 1rem;">
          Ready to Experience Accredited Global Schooling?
        </h2>
        <p style="max-width: 650px; margin: 0 auto 2rem auto; color: rgba(255, 255, 255, 0.9); font-size: 1.05rem; line-height: 1.7;">
          Discover how our accredited curriculum, flexible schedules, and caring educators can transform your child’s educational journey.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
          <a href="/admissions" class="btn btn-primary" style="background-color: var(--color-gold); color: var(--color-navy-dark); font-weight: 700; border: none; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Admissions Process &rarr;
          </a>
          <a href="/about-zuvio" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid #FFFFFF; font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            About Zuvio
          </a>
          <a href="/curriculum" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid rgba(255, 255, 255, 0.6); font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Curriculum Guide
          </a>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include_once dirname(__FILE__) . '/../includes/footer.php'; ?>
