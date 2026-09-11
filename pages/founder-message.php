<?php
// Zuvio Global School - Founder's Message Dedicated Page
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

$page_slug = 'founder-message';
$seo = [
    'seo_title' => 'Founder’s Message | Zuvio Global School',
    'meta_description' => 'Learning Without Boundaries. Growing With Purpose. Read the official Founder’s Message on our educational philosophy, vision, and child-centered online schooling.',
    'canonical_url' => BASE_URL . '/founder-message',
    'og_title' => 'Founder’s Message | Zuvio Global School',
    'og_description' => 'Learning Without Boundaries. Growing With Purpose. A message from the Founder of Zuvio Global School.',
    'og_image' => '/assets/images/Profile_Images/Pragya_Professional_Profile.webp',
    'index_status' => 'index, follow'
];

include_once dirname(__FILE__) . '/../includes/header.php';

render_breadcrumbs([
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'About Us', 'url' => '/about'],
    ['label' => "Founder's Message"]
]);
?>

<!-- Hero Banner -->
<section style="background-color: var(--pastel-blue); padding: 5rem 1.5rem; text-align: center; border-bottom: 1px solid var(--color-border);">
  <div class="container" style="max-width: 800px;">
    <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Leadership & Vision</span>
    <h1 style="font-size: 3rem; color: var(--color-navy-dark); margin: 0.5rem 0 1rem 0; font-family: var(--font-primary);">Founder’s Message</h1>
    <p style="font-size: 1.25rem; font-family: var(--font-primary); font-style: italic; color: var(--color-teal); margin: 0;">
      “Learning Without Boundaries. Growing With Purpose.”
    </p>
  </div>
</section>

<!-- Founder Editorial Letter -->
<section class="section" style="background-color: #FFFFFF; min-height: 600px;">
  <div class="container" style="max-width: 900px;">
    <div style="background-color: #FFFFFF; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid var(--color-border); border-top: 5px solid var(--color-gold); padding: 3.5rem;">
      
      <div style="display: flex; gap: 2rem; align-items: center; margin-bottom: 2.5rem; flex-wrap: wrap; border-bottom: 1px solid var(--color-border); padding-bottom: 2rem;">
        <div style="width: 110px; height: 110px; border-radius: 50%; overflow: hidden; border: 3px solid var(--color-gold); box-shadow: var(--shadow-sm); flex-shrink: 0;">
          <img src="/assets/images/Profile_Images/Pragya_Professional_Profile.webp" alt="Founder of Zuvio Global School" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div>
          <h2 style="font-size: 1.8rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0 0 0.25rem 0;">Founder</h2>
          <p style="color: var(--color-gold); font-weight: 700; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 0.5rem 0;">Zuvio Global School</p>
          <span style="font-size: 0.85rem; color: var(--color-muted);">Online K–8 Homeschooling & Global Education Pioneer</span>
        </div>
      </div>

      <div style="color: var(--color-text); font-size: 1.05rem; line-height: 1.85; display: flex; flex-direction: column; gap: 1.25rem;">
        <p style="font-weight: 700; color: var(--color-navy); font-size: 1.15rem;">
          Dear Parents, Students and Members of the Zuvio Community,
        </p>

        <p>
          Education today must prepare children not only for examinations, but for a world that is constantly evolving.
        </p>

        <p>
          At Zuvio Global School, we believe that meaningful learning is not defined by the walls of a classroom. It is defined by curiosity, connection, opportunity and the confidence to explore beyond what is already known.
        </p>

        <p>
          Our vision is to create a 100% online, future-ready learning environment where every child has the opportunity to learn beyond geographical boundaries while receiving the guidance, structure and personal attention needed to thrive.
        </p>

        <p>
          At Zuvio, strong academics form the foundation, but learning goes much further. We encourage our students to question, think critically, communicate confidently, collaborate, create and apply their knowledge to real-world situations. Technology enables our classrooms, but teachers, relationships and meaningful human interaction remain at the heart of the learning experience.
        </p>

        <p>
          We recognise that every child is different. Their interests, abilities, pace and aspirations are unique. Our approach therefore aims to create a learning journey that gives students the flexibility to discover their strengths while developing the knowledge, skills and values required for the future.
        </p>

        <p>
          We also believe education is a partnership. Parents, educators and students must work together to create an environment in which children feel supported, inspired and empowered to take ownership of their learning.
        </p>

        <p>
          Zuvio Global School is not simply about bringing a traditional classroom online. We are reimagining how learning can happen when boundaries are removed and possibilities are expanded.
        </p>

        <p>
          Our aspiration is simple yet powerful: to nurture confident learners, independent thinkers, compassionate individuals and responsible global citizens who are prepared not just for the next grade, but for the world ahead.
        </p>

        <p>
          Welcome to Zuvio Global School — a global learning community where every child is encouraged to learn, explore, create and grow without boundaries.
        </p>

        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--color-border);">
          <p style="margin: 0; font-style: italic;">Warm regards,</p>
          <p style="margin: 0.35rem 0 0 0; font-size: 1.2rem; font-weight: 700; color: var(--color-navy-dark); font-family: var(--font-primary);">Founder</p>
          <p style="margin: 0; font-size: 0.9rem; color: var(--color-gold); font-weight: 700;">Zuvio Global School</p>
        </div>
      </div>

    </div>

    <!-- Related Navigation Links -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 3rem; flex-wrap: wrap; gap: 1rem;">
      <a href="/about" class="btn btn-outline" style="border-color: var(--color-navy); color: var(--color-navy); font-weight: 600;">
        &larr; About Zuvio & Our Team
      </a>
      <a href="/contact" class="btn btn-primary" style="background-color: var(--color-teal); border-color: var(--color-teal); color: #FFFFFF; font-weight: 600;">
        Connect with Us &rarr;
      </a>
    </div>

  </div>
</section>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
