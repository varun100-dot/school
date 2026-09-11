<?php
// Zuvio Global School - Zuvio Beyond (Co-curricular, Achievers, Gallery)
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

$page_slug = 'beyond';
$seo = get_page_seo('beyond');

include dirname(__FILE__) . '/../includes/header.php';
?>

<!-- ========================================================================
     BEYOND HERO BANNER
     ======================================================================== -->
<section style="background: linear-gradient(135deg, var(--color-navy-dark) 0%, var(--color-navy) 100%); color: #FFFFFF; padding: 4.5rem 0 3.5rem 0; position: relative; overflow: hidden;">
  <div class="container text-center" style="position: relative; z-index: 2; max-width: 850px;">
    <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2.5px; display: block; margin-bottom: 0.75rem;">
      Holistic Development & Enrichment
    </span>
    <h1 style="font-size: clamp(2.2rem, 4vw, 3.5rem); font-family: var(--font-primary); margin: 0 0 1rem 0; line-height: 1.2;">
      Zuvio Beyond
    </h1>
    <p style="font-size: 1.15rem; color: #E2E8F0; line-height: 1.7; margin-bottom: 2rem;">
      Education doesn’t stop at the textbook. Through teacher-led virtual clubs, sports partnerships, and global challenges, Zuvio students discover their talents, build lifelong passions, and grow with purpose.
    </p>
    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
      <a href="#co-curricular" class="btn btn-primary" style="padding: 0.75rem 1.75rem;">Explore Friday Clubs</a>
      <a href="#achievers" class="btn btn-outline" style="border-color: rgba(255,255,255,0.4); color: #FFFFFF; padding: 0.75rem 1.75rem;">Student Achievers</a>
      <a href="#gallery" class="btn btn-outline" style="border-color: rgba(255,255,255,0.4); color: #FFFFFF; padding: 0.75rem 1.75rem;">Events Gallery</a>
    </div>
  </div>
</section>

<!-- ========================================================================
     1. CO-CURRICULAR ACTIVITIES & VIRTUAL CLUBS (#co-curricular)
     ======================================================================== -->
<section id="co-curricular" class="section" style="background-color: #FFFFFF; border-bottom: 1px solid var(--color-border); padding: 5rem 0;">
  <div class="container">
    <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Friday Enrichment</span>
      <h2 style="font-size: 2.5rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">Co-Curricular Learning & Virtual Clubs</h2>
      <p style="color: var(--color-muted); font-size: 1.05rem; margin-top: 0.5rem; line-height: 1.6;">
        Every Friday, textbooks make way for hands-on exploration. Our curated club ecosystem allows learners to build future skills across four key domains:
      </p>
    </div>

    <!-- 4 Domains Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 2rem;">
      
      <!-- Domain 1 -->
      <div class="card" style="padding: 2.25rem; border-top: 4px solid var(--color-teal); background-color: var(--color-surface);">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
          <span style="font-size: 1.75rem;">🚀</span>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0;">Tech & Innovation</h3>
        </div>
        <p style="font-size: 0.88rem; color: var(--color-muted); line-height: 1.6; margin-bottom: 1.25rem;">
          Cultivating computational thinking, design logic, and responsible technology usage:
        </p>
        <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.9rem; color: var(--color-text); line-height: 2;">
          <li>✔ <strong>AI Explorers:</strong> Age-appropriate AI concepts & applications</li>
          <li>✔ <strong>Coding & App Building:</strong> Scratch, Python, and web fundamentals</li>
          <li>✔ <strong>Robotics Garage:</strong> Virtual simulations & maker circuits</li>
          <li>✔ <strong>Digital Media & Animation:</strong> 2D illustration & storytelling</li>
        </ul>
      </div>

      <!-- Domain 2 -->
      <div class="card" style="padding: 2.25rem; border-top: 4px solid var(--color-gold); background-color: var(--color-surface);">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
          <span style="font-size: 1.75rem;">🧠</span>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0;">Mind & Numeracy</h3>
        </div>
        <p style="font-size: 0.88rem; color: var(--color-muted); line-height: 1.6; margin-bottom: 1.25rem;">
          Accelerating cognitive agility, pattern recognition, and mental calculation:
        </p>
        <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.9rem; color: var(--color-text); line-height: 2;">
          <li>✔ <strong>Chess & Strategic Thinking:</strong> Tactics, focus, and endgame planning</li>
          <li>✔ <strong>Rubik's Cube Club:</strong> Algorithmic solving & spatial awareness</li>
          <li>✔ <strong>Vedic Maths:</strong> Rapid mental math shortcuts and speed</li>
          <li>✔ <strong>Abacus Mastery:</strong> Concentration and foundational numeracy</li>
        </ul>
      </div>

      <!-- Domain 3 -->
      <div class="card" style="padding: 2.25rem; border-top: 4px solid var(--color-teal); background-color: var(--color-surface);">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
          <span style="font-size: 1.75rem;">🌱</span>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0;">Life & Leadership</h3>
        </div>
        <p style="font-size: 0.88rem; color: var(--color-muted); line-height: 1.6; margin-bottom: 1.25rem;">
          Empowering learners with personal agency, financial acumen, and empathy:
        </p>
        <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.9rem; color: var(--color-text); line-height: 2;">
          <li>✔ <strong>Financial Literacy:</strong> Budgeting, saving, and value exchange</li>
          <li>✔ <strong>Junior Entrepreneurship:</strong> Problem identification & pitching</li>
          <li>✔ <strong>Public Speaking & Debating:</strong> Eloquence and reasoned discourse</li>
          <li>✔ <strong>Mindfulness & Yoga:</strong> Emotional regulation & screen posture</li>
        </ul>
      </div>

      <!-- Domain 4 -->
      <div class="card" style="padding: 2.25rem; border-top: 4px solid var(--color-gold); background-color: var(--color-surface);">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
          <span style="font-size: 1.75rem;">🎨</span>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0;">Creative Expression</h3>
        </div>
        <p style="font-size: 0.88rem; color: var(--color-muted); line-height: 1.6; margin-bottom: 1.25rem;">
          Unlocking individual creative voices through multi-sensory artistry:
        </p>
        <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.9rem; color: var(--color-text); line-height: 2;">
          <li>✔ <strong>Visual Arts & Craft:</strong> Mixed-media painting and sculpting</li>
          <li>✔ <strong>Creative Writing & Journalism:</strong> Poetry, blogging, and school gazette</li>
          <li>✔ <strong>Dance & Movement:</strong> Rhythm, coordination, and physical energy</li>
          <li>✔ <strong>Drama & Theatrics:</strong> Character portrayal and vocal modulation</li>
        </ul>
      </div>

    </div>
  </div>
</section>

<!-- ========================================================================
     2. STUDENT ACHIEVERS & SPOTLIGHTS (#achievers)
     ======================================================================== -->
<section id="achievers" class="section" style="background-color: var(--color-surface-blue); border-bottom: 1px solid var(--color-border); padding: 5rem 0;">
  <div class="container">
    <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Student Spotlight</span>
      <h2 style="font-size: 2.5rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">Celebrating Our Achievers</h2>
      <p style="color: var(--color-muted); font-size: 1.05rem; margin-top: 0.5rem;">
        Flexible schedules empower our students to excel in national competitions, sports championships, and creative arts alongside their academic pursuits.
      </p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem;">
      
      <!-- Achiever 1 -->
      <div class="card" style="padding: 2rem; background-color: #FFFFFF; border-left: 4px solid var(--color-gold);">
        <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1rem;">
          <div style="width: 56px; height: 56px; border-radius: 50%; background: var(--color-surface-blue); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            🏆
          </div>
          <div>
            <h3 style="font-size: 1.2rem; color: var(--color-navy); margin: 0; font-family: var(--font-primary);">National Cyber Olympiad</h3>
            <span style="font-size: 0.8rem; color: var(--color-gold); font-weight: 700;">Gold Medalist · Grade 6</span>
          </div>
        </div>
        <p style="font-size: 0.9rem; color: var(--color-text); line-height: 1.65;">
          Demonstrated outstanding mastery in algorithmic reasoning and logical problem-solving, achieving Rank 1 in the regional STEM Olympiad after training in our Coding & Logic club.
        </p>
      </div>

      <!-- Achiever 2 -->
      <div class="card" style="padding: 2rem; background-color: #FFFFFF; border-left: 4px solid var(--color-teal);">
        <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1rem;">
          <div style="width: 56px; height: 56px; border-radius: 50%; background: var(--color-surface-blue); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            ⛸️
          </div>
          <div>
            <h3 style="font-size: 1.2rem; color: var(--color-navy); margin: 0; font-family: var(--font-primary);">Competitive Figure Skating</h3>
            <span style="font-size: 0.8rem; color: var(--color-teal); font-weight: 700;">National Podium Athlete · Grade 5</span>
          </div>
        </div>
        <p style="font-size: 0.9rem; color: var(--color-text); line-height: 1.65;">
          Balancing 4 hours of daily rink training with live classes at Zuvio. Flexible lesson recordings and one-on-one mentor check-ins ensure uninterrupted academic distinction.
        </p>
      </div>

      <!-- Achiever 3 -->
      <div class="card" style="padding: 2rem; background-color: #FFFFFF; border-left: 4px solid var(--color-gold);">
        <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1rem;">
          <div style="width: 56px; height: 56px; border-radius: 50%; background: var(--color-surface-blue); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            ♟️
          </div>
          <div>
            <h3 style="font-size: 1.2rem; color: var(--color-navy); margin: 0; font-family: var(--font-primary);">FIDE Rated Junior Chess</h3>
            <span style="font-size: 0.8rem; color: var(--color-gold); font-weight: 700;">Under-12 State Champion · Grade 7</span>
          </div>
        </div>
        <p style="font-size: 0.9rem; color: var(--color-text); line-height: 1.65;">
          Travels across India and the GCC for classical tournaments. Zuvio's online ecosystem allows asynchronous assignment submissions and continuous teacher communication.
        </p>
      </div>

    </div>
  </div>
</section>

<!-- ========================================================================
     3. ACTIVITY & EVENTS GALLERY (#gallery)
     ======================================================================== -->
<section id="gallery" class="section" style="background-color: #FFFFFF; padding: 5rem 0;">
  <div class="container">
    <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Campus Life</span>
      <h2 style="font-size: 2.5rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">Activity & Events Showcase</h2>
      <p style="color: var(--color-muted); font-size: 1.05rem; margin-top: 0.5rem;">
        From virtual science fairs to collaborative digital art exhibitions, explore moments from the Zuvio global learning community.
      </p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
      
      <div style="background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-sm); overflow: hidden;">
        <div style="height: 180px; background-color: var(--color-navy); background-image: url('/assets/images/homepage_hero_1.jpg'); background-size: cover; background-position: center;"></div>
        <div style="padding: 1.25rem;">
          <h4 style="font-size: 1.05rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0 0 0.35rem 0;">Virtual Science Innovation Expo</h4>
          <p style="font-size: 0.82rem; color: var(--color-muted); line-height: 1.5; margin: 0;">Students demonstrated working prototypes on renewable energy and water conservation.</p>
        </div>
      </div>

      <div style="background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-sm); overflow: hidden;">
        <div style="height: 180px; background-color: var(--color-navy); background-image: url('/assets/images/Hero image 2.png'); background-size: cover; background-position: center;"></div>
        <div style="padding: 1.25rem;">
          <h4 style="font-size: 1.05rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0 0 0.35rem 0;">Model United Nations (MUN)</h4>
          <p style="font-size: 0.82rem; color: var(--color-muted); line-height: 1.5; margin: 0;">Middle school delegates debated climate policy and global economic cooperation.</p>
        </div>
      </div>

      <div style="background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-sm); overflow: hidden;">
        <div style="height: 180px; background-color: var(--color-navy); background-image: url('/assets/images/Students learning in classroom.png'); background-size: cover; background-position: center;"></div>
        <div style="padding: 1.25rem;">
          <h4 style="font-size: 1.05rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0 0 0.35rem 0;">Digital Art & Illustration Gallery</h4>
          <p style="font-size: 0.82rem; color: var(--color-muted); line-height: 1.5; margin: 0;">Showcasing student artwork exploring cultural diversity and future-city architecture.</p>
        </div>
      </div>

    </div>

    <div class="text-center" style="margin-top: 3.5rem;">
      <a href="/admissions" class="btn btn-primary" style="padding: 0.85rem 2.25rem; font-size: 1rem;">
        Join the Zuvio Community &rarr;
      </a>
    </div>
  </div>
</section>

<?php include dirname(__FILE__) . '/../includes/footer.php'; ?>
