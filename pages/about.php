<?php
// Zuvio Global School - About Us Page Template
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// Initialize CMS store from session if available, else defaults from DOCX source of truth
if (!isset($_SESSION['mock_about_cms'])) {
    $_SESSION['mock_about_cms'] = [];
}
$cms = &$_SESSION['mock_about_cms'];

// 1. About Zuvio / Story
$story = $cms['story'] ?? [
    'title' => 'Learning Without Boundaries, Growing With Purpose',
    'subtitle' => 'About Zuvio',
    'content' => "Zuvio began with a simple observation: too many children are asked to fit into a system, rather than the system being designed to fit the child.\n\nTraditional schooling often requires conformity over curiosity, rigid schedules over natural rhythms, and a one-size-fits-all approach that leaves many students underserved — whether they need more time to master a concept, more room to run ahead, or simply an environment where they feel safe and understood.\n\nZuvio Global School was founded to offer an alternative — not an alternative that compromises on quality, but one that raises the bar for what education can be.\n\nWe bring together a structured, curriculum-aligned programme, caring teachers, and thoughtful technology to create a flexible, personalised learning experience your child can access from anywhere in the world.",
    'image' => '/assets/images/about_us_hero.webp'
];

// 2. Vision & Mission
$vision_mission = $cms['vision_mission'] ?? [
    'vision' => 'To redefine the future of education by creating a dynamic, borderless learning environment where students from every corner of the world can thrive academically, think critically, and evolve into compassionate, future-ready global leaders.',
    'mission' => 'Our mission is to revolutionize education through a cutting-edge online learning platform that integrates futuristic teaching methods, personalized pathways, and holistic development to unlock the unique potential of every child.'
];

// 3. Values (What Matters at Zuvio) - 6 Values
$default_values = [
    [
        'id' => 1,
        'title' => 'Child at the Centre',
        'desc' => 'Every child is an individual, not a cohort. Their strengths, pace, and interests shape the journey.',
        'icon' => '🎯',
        'sort_order' => 1,
        'is_published' => 1
    ],
    [
        'id' => 2,
        'title' => 'Inclusion by Design',
        'desc' => 'An environment built from day one to welcome every kind of mind — neurotypical, neurodivergent, gifted, or simply different.',
        'icon' => '🤝',
        'sort_order' => 2,
        'is_published' => 1
    ],
    [
        'id' => 3,
        'title' => 'Personalised Learning',
        'desc' => 'Learning pathways that flex to fit the student, not rigid timetables that force students into a mould.',
        'icon' => '🌱',
        'sort_order' => 3,
        'is_published' => 1
    ],
    [
        'id' => 4,
        'title' => 'Growth Not Just Marks',
        'desc' => 'Academic achievement matters deeply, but so does confidence, critical thinking, emotional resilience, and character.',
        'icon' => '📈',
        'sort_order' => 4,
        'is_published' => 1
    ],
    [
        'id' => 5,
        'title' => 'Beyond Academics',
        'desc' => 'A complete school experience — clubs, sports, competitions, exhibitions, and real-world life skills.',
        'icon' => '🎨',
        'sort_order' => 5,
        'is_published' => 1
    ],
    [
        'id' => 6,
        'title' => 'Learning Without Boundaries',
        'desc' => 'Quality education that travels with the child. Accessible from anywhere in the world.',
        'icon' => '🌍',
        'sort_order' => 6,
        'is_published' => 1
    ]
];
$values = $cms['values'] ?? $default_values;

// 4. What Sets Us Apart - 6 Cards
$default_apart = [
    [
        'id' => 1,
        'title' => 'Online but Deeply Human',
        'desc' => 'Small interactive live classes, dedicated mentors, and real relationships — never pre-recorded video lectures.',
        'tag' => 'Human Touch',
        'sort_order' => 1,
        'is_published' => 1
    ],
    [
        'id' => 2,
        'title' => 'Personalised by Default',
        'desc' => 'Customised pace, targeted support, and pathways tailored to each child’s unique learning style and needs.',
        'tag' => 'Tailored Pace',
        'sort_order' => 2,
        'is_published' => 1
    ],
    [
        'id' => 3,
        'title' => 'Inclusive by Design',
        'desc' => 'Specialised support, SEN certified educators, and a culture where every learner belongs and flourishes.',
        'tag' => 'Neuroinclusive',
        'sort_order' => 3,
        'is_published' => 1
    ],
    [
        'id' => 4,
        'title' => 'Flexible for Real Life',
        'desc' => 'Timetables and structures that support families traveling, student athletes, artists, and homeschooling paths.',
        'tag' => 'Anytime Anywhere',
        'sort_order' => 4,
        'is_published' => 1
    ],
    [
        'id' => 5,
        'title' => 'Beyond Academics',
        'desc' => 'Holistic co-curricular programmes, leadership clubs, debate, coding, and sports integration through ISSO.',
        'tag' => '360° Growth',
        'sort_order' => 5,
        'is_published' => 1
    ],
    [
        'id' => 6,
        'title' => 'Parents as Partners',
        'desc' => 'Transparent progress tracking, regular open dialogues, and collaborative goal setting for student success.',
        'tag' => 'Collaborative',
        'sort_order' => 6,
        'is_published' => 1
    ]
];
$apart_cards = $cms['apart'] ?? $default_apart;

// 5. ZUVIO Approach (Z-U-V-I-O Acronym)
$default_approach = [
    [
        'letter' => 'Z',
        'title' => 'Zoomed-In Attention',
        'desc' => 'Small cohorts, frequent individual check-ins, and dedicated teacher focus ensuring no child is overlooked.',
        'sort_order' => 1,
        'is_published' => 1
    ],
    [
        'letter' => 'U',
        'title' => 'Understand Every Learner',
        'desc' => 'Diagnostic assessments that recognise cognitive strengths, emotional needs, and individual learning preferences.',
        'sort_order' => 2,
        'is_published' => 1
    ],
    [
        'letter' => 'V',
        'title' => 'Versatile Pathways',
        'desc' => 'Flexible curriculum choices, customizable pacing, and elective enrichment tailored to future aspirations.',
        'sort_order' => 3,
        'is_published' => 1
    ],
    [
        'letter' => 'I',
        'title' => 'Inclusive by Design',
        'desc' => 'Neurodivergent support, SEN-trained educators, and differentiated instruction welcoming all minds.',
        'sort_order' => 4,
        'is_published' => 1
    ],
    [
        'letter' => 'O',
        'title' => 'Opportunities Without Boundaries',
        'desc' => 'Global student peers, international olympiads, and borderless learning accessible anywhere on Earth.',
        'sort_order' => 5,
        'is_published' => 1
    ]
];
$approach_cards = $cms['approach'] ?? $default_approach;

// 6. Who Should Choose Zuvio (8 Audiences)
$default_audiences = [
    [
        'id' => 1,
        'title' => 'Flexible Learning Families',
        'desc' => 'Families seeking flexible learning schedules that adapt seamlessly to family lifestyle, commitments, and relocation.',
        'sort_order' => 1,
        'is_published' => 1
    ],
    [
        'id' => 2,
        'title' => 'Homeschooling Families',
        'desc' => 'Alternative-learning and homeschooling families looking for structured, recognized international curriculum accreditation.',
        'sort_order' => 2,
        'is_published' => 1
    ],
    [
        'id' => 3,
        'title' => 'Globally Mobile & Expats',
        'desc' => 'Expat, diplomatic, and traveling families requiring continuous, uninterrupted schooling with recognized global credentials.',
        'sort_order' => 3,
        'is_published' => 1
    ],
    [
        'id' => 4,
        'title' => 'Young Athletes & Performers',
        'desc' => 'Students pursuing competitive sports, fine arts, music, or performance careers needing rigorous yet adaptable academics.',
        'sort_order' => 4,
        'is_published' => 1
    ],
    [
        'id' => 5,
        'title' => 'Calm-Environment Learners',
        'desc' => 'Children who flourish better in calm, distraction-free, supportive online settings free from traditional classroom anxiety.',
        'sort_order' => 5,
        'is_published' => 1
    ],
    [
        'id' => 6,
        'title' => 'Personalised Pace Seekers',
        'desc' => 'Students who want to accelerate in areas of strength or take measured, dedicated time to master challenging concepts.',
        'sort_order' => 6,
        'is_published' => 1
    ],
    [
        'id' => 7,
        'title' => 'Future-Ready Seekers',
        'desc' => 'Parents prioritising 21st-century critical thinking, ethical digital literacy, communication, and emotional resilience.',
        'sort_order' => 7,
        'is_published' => 1
    ],
    [
        'id' => 8,
        'title' => 'Neuroinclusive Needs',
        'desc' => 'Children with ADHD, autism, or delayed learning who thrive with individualized attention, patience, and expert guidance.',
        'sort_order' => 8,
        'is_published' => 1
    ]
];
$audiences = $cms['audiences'] ?? $default_audiences;

// 7. Leadership Team - STRICT RULE: Rashmi Bhasin removed
$default_team = [
    [
        'name' => 'Pragya Jain',
        'slug' => 'pragya-jain',
        'designation' => 'Co-Founder & Director',
        'category' => 'Board of Directors',
        'image' => '/assets/images/Profile_Images/Pragya_Professional_Profile.webp',
        'short_description' => 'Pragya Jain is an educationist dedicated to child-centric learning that prepares students for life. She conceptualized Zuvio to merge academic rigor with personalization, creativity, and future-ready skills.',
        'sort_order' => 1,
        'is_published' => 1
    ],
    [
        'name' => 'Deepak Jain',
        'slug' => 'deepak-jain',
        'designation' => 'Co-Founder & Director',
        'category' => 'Board of Directors',
        'image' => '/assets/images/Profile_Images/Deepak_Professional_Profile.webp',
        'short_description' => 'Deepak Jain is an entrepreneur and business professional who brings a practical, growth-oriented perspective to Zuvio Global School. He oversees Zuvio’s strategic direction, operations, and partnerships.',
        'sort_order' => 2,
        'is_published' => 1
    ],
    [
        'name' => 'Sharmin Habib',
        'slug' => 'sharmin-habib',
        'designation' => 'Head of Business and Operations',
        'category' => 'Academic Leadership',
        'image' => '/assets/images/Profile_Images/Sharmin_Professional_Profile.webp',
        'short_description' => 'Sharmin Habib is the Head of Business and Operations at Zuvio Global School with over 18 years of experience across online schooling, EdTech growth, operations, and scalable digital learning models.',
        'sort_order' => 3,
        'is_published' => 1
    ]
];
$team = $cms['team'] ?? $default_team;
// Filter out Rashmi Bhasin if present
$team = array_values(array_filter($team, function($member) {
    return stripos($member['name'] ?? '', 'Rashmi') === false && ($member['slug'] ?? '') !== 'rashmi-bhasin';
}));

// Ensure Sharmin Habib has correct designation and category if coming from session
foreach ($team as &$m) {
    if (($m['slug'] ?? '') === 'sharmin-habib') {
        $m['designation'] = 'Head of Business and Operations';
        $m['category'] = 'Academic Leadership';
    }
}
unset($m);

// 8. Founder's Message
$founder_message = $cms['founder_message_data'] ?? [
    'title' => 'Learning Without Boundaries. Growing With Purpose.',
    'salutation' => 'Dear Parents, Students and Members of the Zuvio Community,',
    'paragraphs' => [
        "Education today must prepare children not only for examinations, but for a world that is constantly evolving.",
        "At Zuvio Global School, we believe that meaningful learning is not defined by the walls of a classroom. It is defined by curiosity, connection, opportunity and the confidence to explore beyond what is already known.",
        "Our vision is to create a 100% online, future-ready learning environment where every child has the opportunity to learn beyond geographical boundaries while receiving the guidance, structure and personal attention needed to thrive.",
        "At Zuvio, strong academics form the foundation, but learning goes much further. We encourage our students to question, think critically, communicate confidently, collaborate, create and apply their knowledge to real-world situations. Technology enables our classrooms, but teachers, relationships and meaningful human interaction remain at the heart of the learning experience.",
        "We recognise that every child is different. Their interests, abilities, pace and aspirations are unique. Our approach therefore aims to create a learning journey that gives students the flexibility to discover their strengths while developing the knowledge, skills and values required for the future.",
        "We also believe education is a partnership. Parents, educators and students must work together to create an environment in which children feel supported, inspired and empowered to take ownership of their learning.",
        "Zuvio Global School is not simply about bringing a traditional classroom online. We are reimagining how learning can happen when boundaries are removed and possibilities are expanded.",
        "Our aspiration is simple yet powerful: to nurture confident learners, independent thinkers, compassionate individuals and responsible global citizens who are prepared not just for the next grade, but for the world ahead.",
        "Welcome to Zuvio Global School — a global learning community where every child is encouraged to learn, explore, create and grow without boundaries."
    ],
    'signoff_name' => 'Founder',
    'signoff_org' => 'Zuvio Global School',
    'image' => '/assets/images/Profile_Images/Pragya_Professional_Profile.webp'
];

// 9. Founder Story & Areas of Specialisation
$founder_story = $cms['founder_story'] ?? [
    'name' => 'Dr. Alisha Madhok Walia',
    'title' => 'Visionary Educator & Founder',
    'intro' => 'With a clear vision to build a progressive and future-ready learning environment, driven by stellar experience and a passion for holistic online education, she focuses on empowering students to grow with confidence, curiosity, and a global mindset.',
    'quote' => '“Our vision has always been to provide our students with world-class education that prepares them not just for exams, but for life.”',
    'credentials' => [
        'Doctorate in Education',
        'Director of Education Program (USA — Certified by Florida & California Governments)',
        'Master of Arts (Milan, Italy)',
        'BBA — Marketing & Sales',
        'ADHD, Autism & Inclusion Specialist'
    ],
    'stats' => [
        ['number' => '100K+', 'label' => 'Students Reached'],
        ['number' => '38', 'label' => 'Countries'],
        ['number' => '54+', 'label' => 'Years Group Legacy'],
        ['number' => '15+', 'label' => 'Years Experience']
    ]
];

$default_specialisations = [
    [
        'title' => 'Global Online Education',
        'desc' => 'Pioneering AI-powered K-12 online schooling across 38 countries with multi-board curriculum including CBSE, Cambridge, NIOS, WACE and American pathways.',
        'icon' => '🌐'
    ],
    [
        'title' => 'Inclusive Education',
        'desc' => 'Expert in supporting learners with ADHD, autism, and developmental delays. Championing education access for 500+ Afghan girls and underserved communities.',
        'icon' => '🤲'
    ],
    [
        'title' => 'EdTech Leadership',
        'desc' => 'Integrating platforms like Zoom, MS Teams, Kahoot!, AI analytics, and adaptive technologies to create personalised, engaging digital classrooms.',
        'icon' => '💻'
    ],
    [
        'title' => 'Child & Parent Therapy',
        'desc' => 'Certified in play therapy, parent counselling, child therapy, and special primary education methodologies — ensuring holistic support beyond academics.',
        'icon' => '❤️'
    ],
    [
        'title' => 'Academic Excellence',
        'desc' => 'Driving consistent award recognition — from National School Awards to International Icon Awards 2025 — through outcome-focused pedagogy and teacher development.',
        'icon' => '🏆'
    ],
    [
        'title' => 'Strategic Growth',
        'desc' => 'Scaling progressive education from India to a global presence across 38 countries, targeting top-10 international educational institution status by 2027.',
        'icon' => '🚀'
    ]
];
$specialisations = $cms['specialisations'] ?? $default_specialisations;

// 10. Founder Career Timeline
$default_timeline = [
    [
        'year' => '1972',
        'tag' => 'LEGACY',
        'title' => 'Sunbeam Group Founded',
        'desc' => 'The Dalimss Sunbeam Group of Schools, Varanasi — established by Dr. Amrit Lal Madhok — lays the 54-year educational legacy carried forward.'
    ],
    [
        'year' => 'EDUCATION',
        'tag' => 'TRAINING',
        'title' => 'International Academic Training',
        'desc' => 'Completed BBA, then Master of Arts from Milan, Italy, followed by the Director of Education Program in the USA — certified by the Governments of Florida and California.'
    ],
    [
        'year' => '2011',
        'tag' => 'CAREER',
        'title' => 'Operations & Leadership',
        'desc' => 'Began career as Additional Director at Dalimss Sunbeam Group of Schools, building extensive expertise in school operations, curriculum, and student development.'
    ],
    [
        'year' => '2021',
        'tag' => 'FOUNDED',
        'title' => 'Launched Global Online Schooling',
        'desc' => 'Identified the global gap in accessible, flexible K-12 education and launched 100% online schooling with multi-board curriculum for students worldwide.'
    ],
    [
        'year' => '2023–25',
        'tag' => 'GROWTH',
        'title' => '100K+ Students, 38 Countries',
        'desc' => 'Scaled rapidly to 100,000+ students across 38 countries. Honored with Best E-School of 2023, National School Award, and International Icon Awards 2025.'
    ],
    [
        'year' => '2026–Now',
        'tag' => 'FUTURE',
        'title' => 'Shaping the Future of Global Education',
        'desc' => 'Leading the mission to deliver world-class borderless education with Cambridge, CBSE, NIOS, and international curriculum offerings.'
    ]
];
$timeline = $cms['timeline'] ?? $default_timeline;

// 11. Awards & In Media
$default_awards = [
    [
        'title' => 'Best E-School of 2023',
        'org' => 'Global Education Summit',
        'desc' => 'Recognized for pioneering digital school infrastructure and interactive cohort pedagogy.'
    ],
    [
        'title' => 'National School Award',
        'org' => 'Education Excellence Forum',
        'desc' => 'Awarded for exceptional commitment to student-centric online learning and curriculum rigor.'
    ],
    [
        'title' => 'International Icon Awards 2025',
        'org' => 'Global EdTech Leadership',
        'desc' => 'Honored for transformative leadership in inclusive and neurodivergent-friendly education.'
    ],
    [
        'title' => 'Featured in Global Media',
        'org' => 'Education World & Top Portals',
        'desc' => 'Celebrated across leading publications for breaking geographical barriers in K–12 education.'
    ]
];
$awards = $cms['awards'] ?? $default_awards;

// Page Meta & Header
$page_slug = 'about';
$seo = [
    'seo_title' => 'About Us — Zuvio Global School | Learning Without Boundaries',
    'meta_description' => 'Discover Zuvio Global School — an accredited, child-centric online school combining academic rigor with flexibility, personalised pathways, and inclusive learning.',
    'canonical_url' => 'https://zuvioglobalschool.com/about',
    'og_title' => 'About Us — Zuvio Global School',
    'og_description' => 'Reimagining education for a world without boundaries. Learn about our vision, values, approach, team, and global accreditations.',
    'og_image' => '/assets/images/about_us_hero.jpg',
    'index_status' => 'index, follow'
];

include_once dirname(__FILE__) . '/../includes/header.php';

render_breadcrumbs([
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'About Us']
]);
?>

<!-- 1. Hero Banner Header -->
<section class="about-hero" style="background-color: var(--pastel-blue); color: var(--color-navy); padding: 5rem 1.5rem; text-align: center; border-bottom: 1px solid var(--color-border);">
  <div class="container" style="max-width: 850px;">
    <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">
      <?php echo h($story['subtitle'] ?? 'About Zuvio'); ?>
    </span>
    <h1 style="font-size: 2.85rem; font-family: var(--font-primary); margin-bottom: 1rem; color: var(--color-navy-dark); font-weight: 700; line-height: 1.2;">
      <?php echo h($story['title']); ?>
    </h1>
    <p style="font-size: 1.12rem; font-weight: 500; line-height: 1.6; color: var(--color-text); margin: 0 auto; max-width: 720px;">
      Reimagining education for a world without boundaries — where every child has the freedom to learn, explore, and grow.
    </p>
  </div>
</section>

<!-- 2. Section: About Zuvio / Story -->
<section id="about-zuvio" class="section" style="background-color: var(--color-white); border-bottom: 1px solid var(--color-border); padding: 4.5rem 0;">
  <div class="container">
    <div class="grid-2" style="align-items: center; gap: 4rem;">
      <div>
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 1.5px; display: block; margin-bottom: 0.5rem;">
          Our Genesis & Vision
        </span>
        <h2 style="font-size: 2.25rem; color: var(--color-navy); margin-bottom: 1.5rem; font-family: var(--font-primary); line-height: 1.3;">
          A School Designed to Fit the Child
        </h2>
        <div style="color: var(--color-text); font-size: 1rem; line-height: 1.8; display: flex; flex-direction: column; gap: 1rem;">
          <?php 
          $story_paras = explode("\n\n", $story['content']);
          foreach ($story_paras as $sp):
            if (trim($sp)): ?>
              <p style="margin: 0;"><?php echo nl2br(h(trim($sp))); ?></p>
          <?php 
            endif;
          endforeach; ?>
        </div>
      </div>
      <div>
        <div style="border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-md); border: 1.5px solid rgba(6, 43, 99, 0.16); background-color: var(--color-navy); height: 420px;">
          <img src="<?php echo h($story['image']); ?>" alt="Zuvio Global School Students" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Vision & Mission -->
<section class="section" style="background-color: var(--color-surface-blue); border-bottom: 1px solid var(--color-border); padding: 4rem 0;">
  <div class="container">
    <div class="grid-2" style="gap: 2.5rem;">
      <!-- Vision Card -->
      <div class="card" style="background-color: #FFFFFF; padding: 2.5rem; border-radius: var(--radius-md); border-top: 4px solid var(--color-gold); box-shadow: var(--shadow-sm);">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
          <div style="width: 40px; height: 40px; border-radius: 8px; background-color: var(--pastel-blue); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            🔭
          </div>
          <h3 style="font-size: 1.5rem; color: var(--color-navy); margin: 0; font-family: var(--font-primary);">Our Vision</h3>
        </div>
        <p style="color: var(--color-text); font-size: 1rem; line-height: 1.75; margin: 0;">
          <?php echo h($vision_mission['vision']); ?>
        </p>
      </div>

      <!-- Mission Card -->
      <div class="card" style="background-color: #FFFFFF; padding: 2.5rem; border-radius: var(--radius-md); border-top: 4px solid var(--color-teal); box-shadow: var(--shadow-sm);">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
          <div style="width: 40px; height: 40px; border-radius: 8px; background-color: var(--pastel-green); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            🎯
          </div>
          <h3 style="font-size: 1.5rem; color: var(--color-navy); margin: 0; font-family: var(--font-primary);">Our Mission</h3>
        </div>
        <p style="color: var(--color-text); font-size: 1rem; line-height: 1.75; margin: 0;">
          <?php echo h($vision_mission['mission']); ?>
        </p>
      </div>
    </div>
  </div>
</section>

<!-- 3. Section: Values (What Matters at Zuvio) -->
<section id="values" class="section" style="background-color: var(--color-white); border-bottom: 1px solid var(--color-border); padding: 5rem 0;">
  <div class="container">
    <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
        Core Foundation
      </span>
      <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
        What Matters at Zuvio
      </h2>
      <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
        Principles that guide every live class, curriculum pathway, and student interaction.
      </p>
    </div>

    <div class="about-values-grid">
      <?php foreach ($values as $val): 
        if (empty($val['is_published'])) continue;
      ?>
        <div class="about-value-card">
          <div class="about-value-icon-wrap">
            <?php echo $val['icon'] ?? '✨'; ?>
          </div>
          <h3 style="font-size: 1.3rem; color: var(--color-navy); margin-bottom: 0.75rem; font-family: var(--font-primary); font-weight: 700;">
            <?php echo h($val['title']); ?>
          </h3>
          <p style="color: var(--color-text); font-size: 0.92rem; line-height: 1.65; margin: 0;">
            <?php echo h($val['desc']); ?>
          </p>
        </div>
      <?php endforeach; ?>
    </div>

    <div style="text-align: center; margin-top: 3.5rem; padding: 1.5rem; background: var(--pastel-blue); border: 1.5px solid rgba(6, 43, 99, 0.16); border-radius: var(--radius-md);">
      <p style="color: var(--color-navy-dark); font-size: 1.15rem; font-weight: 700; margin: 0; font-family: var(--font-primary); letter-spacing: 0.5px;">
        “Inclusive by design. Personalised by need. Future-ready by purpose.”
      </p>
    </div>
  </div>
</section>

<!-- 4. Section: What Sets Us Apart -->
<section id="what-sets-us-apart" class="section" style="background-color: var(--color-surface-warm); border-bottom: 1px solid var(--color-border); padding: 5rem 0;">
  <div class="container">
    <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
        Distinctive Excellence
      </span>
      <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
        What Sets Us Apart
      </h2>
      <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
        How our school experience delivers depth, rigor, and warmth in an online environment.
      </p>
    </div>

    <div class="about-apart-grid">
      <?php foreach ($apart_cards as $card): 
        if (empty($card['is_published'])) continue;
      ?>
        <div class="about-apart-card">
          <span class="about-apart-badge">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <?php echo h($card['tag'] ?? 'Differentiator'); ?>
          </span>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); margin-bottom: 0.75rem; font-family: var(--font-primary); font-weight: 700;">
            <?php echo h($card['title']); ?>
          </h3>
          <p style="color: var(--color-text); font-size: 0.92rem; line-height: 1.65; margin: 0;">
            <?php echo h($card['desc']); ?>
          </p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- 5. Section: ZUVIO Approach (Z-U-V-I-O) -->
<section id="our-approach" class="section" style="background-color: var(--color-white); border-bottom: 1px solid var(--color-border); padding: 5rem 0;">
  <div class="container">
    <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
        Educational Framework
      </span>
      <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
        The ZUVIO Approach
      </h2>
      <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
        Every letter represents our pedagogical commitment to every child who walks into our virtual school.
      </p>
    </div>

    <div class="zuvio-acronym-grid">
      <?php foreach ($approach_cards as $item): 
        if (empty($item['is_published'])) continue;
      ?>
        <div class="zuvio-acronym-card">
          <div class="zuvio-letter-avatar">
            <?php echo h($item['letter']); ?>
          </div>
          <h3 style="font-size: 1.15rem; color: var(--color-navy); margin-bottom: 0.75rem; font-family: var(--font-primary); font-weight: 700; min-height: 48px; display: flex; align-items: center; justify-content: center;">
            <?php echo h($item['title']); ?>
          </h3>
          <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.6; margin: 0;">
            <?php echo h($item['desc']); ?>
          </p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- 6. Section: Who Should Choose Zuvio -->
<section id="who-should-choose" class="section" style="background-color: var(--pastel-blue); border-bottom: 1px solid var(--color-border); padding: 5rem 0;">
  <div class="container">
    <div class="text-center" style="max-width: 780px; margin: 0 auto 3.5rem auto;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
        Is Zuvio Right for You?
      </span>
      <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
        Who Should Choose Zuvio
      </h2>
      <p style="color: var(--color-text); font-size: 1rem; margin-top: 0.5rem;">
        We welcome a wide range of students who flourish when given the right structure, flexibility, and care.
      </p>
    </div>

    <div class="audience-card-grid">
      <?php foreach ($audiences as $idx => $aud): 
        if (empty($aud['is_published'])) continue;
      ?>
        <div class="audience-card">
          <div class="audience-icon-badge">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
          </div>
          <h3 style="font-size: 1.12rem; color: var(--color-navy); margin-bottom: 0.6rem; font-family: var(--font-primary); font-weight: 700;">
            <?php echo h($aud['title']); ?>
          </h3>
          <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.6; margin: 0;">
            <?php echo h($aud['desc']); ?>
          </p>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Audience CTA Banner -->
    <div style="margin-top: 3.5rem; background: linear-gradient(135deg, var(--color-navy-dark) 0%, var(--color-navy) 100%); color: #FFFFFF; border-radius: var(--radius-lg); padding: 3rem 2rem; text-align: center; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-md);">
      <h3 style="font-size: 2rem; font-family: var(--font-primary); margin-bottom: 0.75rem; color: #FFFFFF;">
        Learn. Explore. Grow. From anywhere.
      </h3>
      <p style="font-size: 1.05rem; color: #E2E8F0; max-width: 600px; margin: 0 auto 1.75rem auto; line-height: 1.6;">
        Discover how Zuvio Global School can tailor a world-class educational journey for your child.
      </p>
      <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
        <a href="/admissions" class="btn btn-primary" style="background-color: var(--color-gold); border-color: var(--color-gold); color: var(--color-navy-dark); font-weight: 700;">
          Apply for Admission
        </a>
        <a href="/contact" class="btn btn-outline" style="color: #FFFFFF; border-color: #FFFFFF; font-weight: 600;">
          Book a Consultation
        </a>
      </div>
    </div>
  </div>
</section>

<!-- 7. Section: Our Team (Leadership Team) -->
<section id="our-team" class="section" style="background-color: var(--color-white); border-bottom: 1px solid var(--color-border); padding: 5rem 0;">
  <div class="container">
    <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
        Institutional Governance
      </span>
      <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
        Our Leadership Team
      </h2>
      <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
        Experienced educationists and leaders driving academic innovation and child-first excellence.
      </p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; max-width: 1050px; margin: 0 auto;">
      <?php foreach ($team as $leader): 
        if (empty($leader['is_published'])) continue;
      ?>
        <div class="card" style="background-color: #FFFFFF; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm); display: flex; flex-direction: column;">
          <div style="height: 360px; background-color: var(--pastel-blue); display: flex; justify-content: center; align-items: center; overflow: hidden;">
            <?php if (!empty($leader['image'])): ?>
              <img src="<?php echo h($leader['image']); ?>" alt="<?php echo h($leader['name']); ?>" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; object-position: center 12%;">
            <?php else: ?>
              <span style="font-size: 1.5rem; font-weight: 700; font-family: var(--font-primary);"><?php echo h($leader['name']); ?></span>
            <?php endif; ?>
          </div>
          <div style="padding: 1.75rem; text-align: center; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              <div style="display: inline-block; background: var(--pastel-blue); color: var(--color-teal); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; padding: 0.25rem 0.65rem; border-radius: 4px; margin-bottom: 0.65rem;">
                <?php echo h($leader['category'] ?? (($leader['slug'] ?? '') === 'sharmin-habib' ? 'Academic Leadership' : 'Board of Directors')); ?>
              </div>
              <h3 style="font-size: 1.35rem; color: var(--color-navy); margin-bottom: 0.25rem; font-family: var(--font-primary);">
                <?php echo h($leader['name']); ?>
              </h3>
              <p style="font-size: 0.85rem; color: var(--color-gold); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1.25rem;">
                <?php echo h($leader['designation']); ?>
              </p>
              <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.65; margin-bottom: 1.5rem;">
                <?php echo h($leader['short_description']); ?>
              </p>
            </div>
            <div style="margin-top: auto; border-top: 1px solid var(--color-border); padding-top: 1rem;">
              <a href="/about/<?php echo h($leader['slug'] ?? ''); ?>" style="color: var(--color-teal); font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;">
                View Profile &rarr;
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- 8. Section: Founder’s Message (Official) -->
<section id="founders-message" class="founder-message-section" style="background-color: var(--color-surface-warm); border-bottom: 1px solid var(--color-border); padding: 5rem 0;">
  <div class="container">
    <div class="founder-editorial-card">
      
      <div class="founder-portrait-col" style="text-align: center;">
        <div class="founder-portrait-frame" style="width: 220px; height: 280px; border-radius: var(--radius-md); overflow: hidden; margin: 0 auto 1.25rem auto; border: 3px solid var(--color-gold); box-shadow: var(--shadow-sm);">
          <img src="<?php echo h($founder_message['image']); ?>" alt="Founder of Zuvio Global School" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <h3 style="font-size: 1.35rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.25rem;">
          <?php echo h($founder_message['signoff_name']); ?>
        </h3>
        <p style="font-size: 0.85rem; color: var(--color-gold); font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
          <?php echo h($founder_message['signoff_org']); ?>
        </p>
      </div>

      <div class="founder-letter-col">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.5rem;">
          Founder’s Message
        </span>
        <h2 style="font-size: 2.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 1.25rem; line-height: 1.25;">
          <?php echo h($founder_message['title']); ?>
        </h2>
        
        <p style="font-weight: 600; color: var(--color-navy); font-size: 1rem; margin-bottom: 1.25rem;">
          <?php echo h($founder_message['salutation']); ?>
        </p>

        <div style="color: var(--color-text); font-size: 0.95rem; line-height: 1.8; display: flex; flex-direction: column; gap: 1rem;">
          <?php foreach ($founder_message['paragraphs'] as $p): ?>
            <p style="margin: 0;"><?php echo h($p); ?></p>
          <?php endforeach; ?>
        </div>

        <div style="margin-top: 2rem; border-top: 1px solid var(--color-border); padding-top: 1.25rem;">
          <p style="font-style: italic; color: var(--color-navy); font-size: 0.95rem; margin-bottom: 0.25rem; font-weight: 600;">
            Warm regards,
          </p>
          <p style="font-weight: 700; color: var(--color-navy); margin: 0;">
            <?php echo h($founder_message['signoff_name']); ?>, <?php echo h($founder_message['signoff_org']); ?>
          </p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- 9. Section: Founder Story & Expertise (Visionary Educator Profile) -->
<section id="founder-story" class="section" style="background-color: var(--color-white); border-bottom: 1px solid var(--color-border); padding: 5rem 0;">
  <div class="container">
    <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
        Visionary Leadership
      </span>
      <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
        Leadership & Expertise
      </h2>
      <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
        Grounding our curriculum in deep academic pedigree, clinical insight, and global standards.
      </p>
    </div>

    <!-- Founder Hero Profile Card -->
    <div class="founder-profile-banner">
      <div style="text-align: center;">
        <div style="width: 200px; height: 250px; border-radius: var(--radius-md); overflow: hidden; margin: 0 auto 1.25rem auto; border: 3px solid var(--color-gold); box-shadow: var(--shadow-md);">
          <img src="/assets/images/Profile_Images/Pragya_Professional_Profile.webp" alt="<?php echo h($founder_story['name']); ?>" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <h3 style="font-size: 1.4rem; color: #FFFFFF; font-family: var(--font-primary); margin-bottom: 0.25rem;">
          <?php echo h($founder_story['name']); ?>
        </h3>
        <p style="font-size: 0.85rem; color: var(--color-gold); font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
          <?php echo h($founder_story['title']); ?>
        </p>
      </div>

      <div>
        <p style="font-size: 1.05rem; line-height: 1.75; color: #E2E8F0; margin-bottom: 1.25rem;">
          <?php echo h($founder_story['intro']); ?>
        </p>
        <blockquote style="border-left: 3px solid var(--color-gold); padding-left: 1.25rem; font-style: italic; color: #FFFFFF; margin: 0 0 1.5rem 0; font-size: 1rem; line-height: 1.6;">
          <?php echo h($founder_story['quote']); ?>
        </blockquote>

        <h4 style="font-size: 0.9rem; color: var(--color-gold); text-transform: uppercase; letter-spacing: 1px; font-weight: 700; margin-bottom: 0.75rem;">
          Academic Credentials & Accreditations
        </h4>
        <ul style="list-style: none; padding: 0; margin: 0 0 2rem 0; display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 0.6rem;">
          <?php foreach ($founder_story['credentials'] as $cred): ?>
            <li style="display: flex; align-items: flex-start; gap: 0.5rem; font-size: 0.9rem; color: #CBD5E1;">
              <span style="color: var(--color-gold); font-weight: 700;">✓</span>
              <span><?php echo h($cred); ?></span>
            </li>
          <?php endforeach; ?>
        </ul>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; border-top: 1px solid rgba(255,255,255,0.15); padding-top: 1.25rem;">
          <?php foreach ($founder_story['stats'] as $stat): ?>
            <div>
              <div style="font-size: 1.75rem; font-weight: 700; color: var(--color-gold); font-family: var(--font-primary);">
                <?php echo h($stat['number']); ?>
              </div>
              <div style="font-size: 0.78rem; color: #CBD5E1; text-transform: uppercase; letter-spacing: 0.5px;">
                <?php echo h($stat['label']); ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Areas of Specialisation -->
    <div style="margin-bottom: 4.5rem;">
      <div class="text-center" style="margin-bottom: 2.5rem;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Core Competencies
        </span>
        <h3 style="font-size: 2rem; color: var(--color-navy); margin-top: 0.35rem; font-family: var(--font-primary);">
          Areas of Specialisation
        </h3>
      </div>

      <div class="specialisation-grid">
        <?php foreach ($specialisations as $spec): ?>
          <div class="specialisation-card">
            <div style="font-size: 1.75rem; margin-bottom: 0.75rem;">
              <?php echo $spec['icon'] ?? '🌟'; ?>
            </div>
            <h4 style="font-size: 1.2rem; color: var(--color-navy); margin-bottom: 0.5rem; font-family: var(--font-primary);">
              <?php echo h($spec['title']); ?>
            </h4>
            <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.65; margin: 0;">
              <?php echo h($spec['desc']); ?>
            </p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Career Timeline / Journey -->
    <div>
      <div class="text-center" style="margin-bottom: 3rem;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Milestones
        </span>
        <h3 style="font-size: 2rem; color: var(--color-navy); margin-top: 0.35rem; font-family: var(--font-primary);">
          Her Story & Journey
        </h3>
      </div>

      <div class="career-timeline">
        <?php foreach ($timeline as $index => $item): 
          $side = ($index % 2 === 0) ? 'left' : 'right';
        ?>
          <div class="timeline-milestone <?php echo $side; ?>">
            <div class="timeline-card">
              <span style="display: inline-block; background: var(--pastel-blue); color: var(--color-navy); font-weight: 700; font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 4px; text-transform: uppercase; margin-bottom: 0.5rem;">
                <?php echo h($item['year']); ?> • <?php echo h($item['tag']); ?>
              </span>
              <h4 style="font-size: 1.15rem; color: var(--color-navy); margin-bottom: 0.5rem; font-family: var(--font-primary);">
                <?php echo h($item['title']); ?>
              </h4>
              <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.6; margin: 0;">
                <?php echo h($item['desc']); ?>
              </p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</section>

<!-- 10. Section: Awards / In Media -->
<section id="awards" class="section" style="background-color: var(--color-surface-warm); border-bottom: 1px solid var(--color-border); padding: 5rem 0;">
  <div class="container">
    <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
        Recognition
      </span>
      <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
        Awards & In Media
      </h2>
      <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
        Recognized by global education forums for academic excellence and innovation.
      </p>
    </div>

    <div class="awards-media-grid">
      <?php foreach ($awards as $award): ?>
        <div class="award-item-card">
          <div style="font-size: 2rem; margin-bottom: 0.75rem;">🏆</div>
          <h3 style="font-size: 1.2rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.35rem;">
            <?php echo h($award['title']); ?>
          </h3>
          <h4 style="font-size: 0.85rem; color: var(--color-teal); font-weight: 700; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
            <?php echo h($award['org']); ?>
          </h4>
          <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.6; margin: 0;">
            <?php echo h($award['desc']); ?>
          </p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- 11. Section: Affiliations & Accreditations (Strictly Preserved Links & Logos) -->
<section id="accreditations" class="section" style="background-color: var(--pastel-blue); border-bottom: 1px solid var(--color-border); padding: 5.5rem 0;">
  <div class="container">
    <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
        Global Benchmarks
      </span>
      <h2 style="font-size: 2.5rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
        Affiliations & Accreditations
      </h2>
      <p style="color: var(--color-muted); font-size: 1.05rem; margin-top: 0.5rem;">
        Committed to world-class educational benchmarks and certified international standards.
      </p>
    </div>

    <div class="accreditation-card-grid">
      <!-- ISSO -->
      <div class="accreditation-feature-card">
        <div>
          <div class="accreditation-logo-wrapper">
            <img src="/assets/images/isso-logo.png" alt="ISSO Logo" loading="lazy">
          </div>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.35rem;">ISSO</h3>
          <h4 style="font-size: 0.88rem; color: var(--color-teal); font-weight: 600; margin-bottom: 1rem;">International Schools Sports Organisation</h4>
          <p style="color: var(--color-muted); font-size: 0.9rem; line-height: 1.65;">
            Connecting international-curriculum schools and student-athletes through organised multi-sport opportunities, tournaments, and competitive pathways.
          </p>
        </div>
        <a href="https://www.issosports.org/" target="_blank" rel="noopener" class="certificate-verify-btn">Learn More &rarr;</a>
      </div>

      <!-- IAO -->
      <div class="accreditation-feature-card">
        <div>
          <div class="accreditation-logo-wrapper">
            <img src="/assets/images/iao-logo.png" alt="IAO Logo" loading="lazy">
          </div>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.35rem;">IAO</h3>
          <h4 style="font-size: 0.88rem; color: var(--color-teal); font-weight: 600; margin-bottom: 1rem;">International Accreditation Organization</h4>
          <p style="color: var(--color-muted); font-size: 0.9rem; line-height: 1.65;">
            Quality-assurance and international accreditation reflecting continuously benchmarked teaching practices, faculty qualifications, and governance.
          </p>
        </div>
        <a href="https://www.iao.org/India-Delhi/Zuvio-Global-School" target="_blank" rel="noopener" class="certificate-verify-btn">
          Verify Certificate
          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
        </a>
      </div>

      <!-- Oxford Quality -->
      <div class="accreditation-feature-card">
        <div>
          <div class="accreditation-logo-wrapper">
            <img src="/assets/images/oxford-logo.png" alt="Oxford Quality Logo" loading="lazy">
          </div>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.35rem;">Oxford Quality</h3>
          <h4 style="font-size: 0.88rem; color: var(--color-teal); font-weight: 600; margin-bottom: 1rem;">Oxford University Press</h4>
          <p style="color: var(--color-muted); font-size: 0.9rem; line-height: 1.65;">
            Strengthening education through high-quality curriculum resources, thematic workbooks, and continuous teacher professional development.
          </p>
        </div>
        <a href="https://india.oup.com/" target="_blank" rel="noopener" class="certificate-verify-btn">Explore Oxford &rarr;</a>
      </div>
    </div>
  </div>
</section>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
