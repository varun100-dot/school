<?php
// Zuvio Global School - Zuvio Beyond Page Template (Official Brochure Redesign)
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

$page_slug = 'zuvio-beyond';
include_once dirname(__FILE__) . '/../includes/header.php';

// Verbatim copy structured from the official brochure PDF
$programmes = [
    [
        'id' => 'ai-explorers',
        'title' => 'AI EXPLORERS',
        'category' => 'TECH & INNOVATION',
        'tagline' => 'Think Smart, Create Smartly',
        'grades' => 'Nursery - Grade 12',
        'format' => 'Live Classes',
        'level' => 'Progressive by age',
        'purpose' => 'A hands-on introduction to artificial intelligence through stories, patterns, creative tools and age-appropriate projects.',
        'image' => '/assets/images/Zuvio_Beyond_Website_Images/01_AI_Explorers.jpg',
        'pathways' => [
            '01' => 'AI Foundations',
            '02' => 'Creative AI',
            '03' => 'Everyday Applications',
            '04' => 'Safe & Responsible AI'
        ],
        'gains' => [
            'Critical thinking',
            'Problem-solving',
            'AI awareness',
            'Responsible digital citizenship'
        ],
        'offers' => [
            'How AI recognises patterns and makes predictions',
            'Prompting, creative AI and simple model-based activities',
            'AI in everyday life, society and future careers',
            'Digital responsibility, bias, privacy and safe use'
        ],
        'closing' => 'Build skills. Create confidently.'
    ],
    [
        'id' => 'coding',
        'title' => 'CODING',
        'category' => 'TECH & INNOVATION',
        'tagline' => 'From ideas to working creations.',
        'grades' => 'Nursery - Grade 12',
        'format' => 'Live Classes',
        'level' => 'Progressive by age',
        'purpose' => 'Learners build computational thinking by sequencing, testing and improving their own interactive digital projects.',
        'image' => '/assets/images/Zuvio_Beyond_Website_Images/02_Coding.jpg',
        'pathways' => [
            '01' => 'Coding Logic',
            '02' => 'Games & Stories',
            '03' => 'Debugging',
            '04' => 'Project Creation'
        ],
        'gains' => [
            'Logical thinking',
            'Creativity',
            'Persistence',
            'Project-building confidence'
        ],
        'offers' => [
            'Block-based coding for beginners',
            'Games, animations and interactive stories',
            'Logic, loops, conditions and debugging',
            'Progression towards text-based coding for older learners'
        ],
        'closing' => 'Build skills. Create confidently.'
    ],
    [
        'id' => 'robotics',
        'title' => 'ROBOTICS',
        'category' => 'TECH & INNOVATION',
        'tagline' => 'Build. Code. Test. Innovate.',
        'grades' => 'Nursery - Grade 12',
        'format' => 'Live Classes',
        'level' => 'Progressive by age',
        'purpose' => 'A practical robotics journey where learners combine mechanics, electronics and coding to design solutions and bring ideas to life.',
        'image' => '/assets/images/Zuvio_Beyond_Website_Images/03_Robotics.jpg',
        'pathways' => [
            '01' => 'Robot Mechanics',
            '02' => 'Sensors & Circuits',
            '03' => 'Coding & Control',
            '04' => 'Design Challenges'
        ],
        'gains' => [
            'Systems thinking',
            'Coding confidence',
            'Creative engineering',
            'Teamwork and persistence'
        ],
        'offers' => [
            'Building simple robots and mechanisms',
            'Working with sensors, motors and circuits',
            'Coding robot movement and behaviour',
            'Testing, troubleshooting and design challenges'
        ],
        'closing' => 'Build skills. Create confidently.'
    ],
    [
        'id' => 'rubiks-cube',
        'title' => 'RUBIK\'S CUBE',
        'category' => 'MIND & NUMERACY',
        'tagline' => 'Focus, patterns and the joy of solving.',
        'grades' => 'Nursery - Grade 12',
        'format' => 'Live Classes',
        'level' => 'Progressive by age',
        'purpose' => 'Step-by-step cube-solving develops concentration, spatial intelligence and a calm approach to complex challenges.',
        'image' => '/assets/images/Zuvio_Beyond_Website_Images/04_Rubiks_Cube.jpg',
        'pathways' => [
            '01' => 'Cube Basics',
            '02' => 'Solving Strategies',
            '03' => 'Pattern Recognition',
            '04' => 'Speed & Practice'
        ],
        'gains' => [
            'Concentration',
            'Spatial reasoning',
            'Memory',
            'Resilience'
        ],
        'offers' => [
            'Cube notation and movement fundamentals',
            'Layer-by-layer solving strategies',
            'Pattern recognition and memory techniques',
            'Timed practice and personal speed goals'
        ],
        'closing' => 'Build skills. Create confidently.'
    ],
    [
        'id' => 'abacus',
        'title' => 'ABACUS',
        'category' => 'MIND & NUMERACY',
        'tagline' => 'See numbers. Feel numbers. Love numbers.',
        'grades' => 'Nursery - Grade 12',
        'format' => 'Live Classes',
        'level' => 'Progressive by age',
        'purpose' => 'A visual and tactile maths programme that strengthens number sense before progressing towards mental calculation.',
        'image' => '/assets/images/Zuvio_Beyond_Website_Images/05_Abacus.jpg',
        'pathways' => [
            '01' => 'Number Sense',
            '02' => 'Core Operations',
            '03' => 'Visualisation',
            '04' => 'Mental Calculation'
        ],
        'gains' => [
            'Number sense',
            'Accuracy',
            'Mental agility',
            'Maths confidence'
        ],
        'offers' => [
            'Number recognition and place value',
            'Addition, subtraction and number bonds',
            'Visualisation and mental arithmetic',
            'Speed and accuracy through structured practice'
        ],
        'closing' => 'Build skills. Create confidently.'
    ],
    [
        'id' => 'vedic-maths',
        'title' => 'VEDIC MATHS',
        'category' => 'MIND & NUMERACY',
        'tagline' => 'Faster strategies, deeper number sense.',
        'grades' => 'Nursery - Grade 12',
        'format' => 'Live Classes',
        'level' => 'Progressive by age',
        'purpose' => 'Learners discover flexible mental-maths techniques that make calculations quicker, clearer and more enjoyable.',
        'image' => '/assets/images/Zuvio_Beyond_Website_Images/06_Vedic_Maths.jpg',
        'pathways' => [
            '01' => 'Mental Strategies',
            '02' => 'Faster Calculation',
            '03' => 'Estimation',
            '04' => 'Maths Application'
        ],
        'gains' => [
            'Calculation speed',
            'Accuracy',
            'Flexible thinking',
            'Exam readiness'
        ],
        'offers' => [
            'Mental addition, subtraction and multiplication',
            'Short methods for squares and division',
            'Estimation and calculation checks',
            'Applying strategies to school mathematics'
        ],
        'closing' => 'Build skills. Create confidently.'
    ],
    [
        'id' => 'financial-literacy',
        'title' => 'FINANCIAL LITERACY',
        'category' => 'LIFE, LEADERSHIP & SUPPORT',
        'tagline' => 'Smart money habits start early.',
        'grades' => 'Nursery - Grade 12',
        'format' => 'Live Classes',
        'level' => 'Progressive by age',
        'purpose' => 'Real-life, age-appropriate activities help learners understand money, choices, planning and responsible decision-making.',
        'image' => '/assets/images/Zuvio_Beyond_Website_Images/07_Financial_Literacy.jpg',
        'pathways' => [
            '01' => 'Saving & Spending',
            '02' => 'Budgeting',
            '03' => 'Digital Payments',
            '04' => 'Smart Choices'
        ],
        'gains' => [
            'Money awareness',
            'Planning',
            'Decision-making',
            'Responsible habits'
        ],
        'offers' => [
            'Needs, wants, earning, saving and spending',
            'Simple budgets and goal setting',
            'Banks, digital payments, interest and safety',
            'Value, comparison, consumer choices and giving'
        ],
        'closing' => 'Build skills. Create confidently.'
    ],
    [
        'id' => 'entrepreneurship',
        'title' => 'ENTREPRENEURSHIP',
        'category' => 'LIFE, LEADERSHIP & SUPPORT',
        'tagline' => 'Turn curiosity into meaningful ideas.',
        'grades' => 'Nursery - Grade 12',
        'format' => 'Live Classes',
        'level' => 'Progressive by age',
        'purpose' => 'Learners identify problems, develop solutions and present ideas while understanding the basics of building value.',
        'image' => '/assets/images/Zuvio_Beyond_Website_Images/08_Entrepreneurship.jpg',
        'pathways' => [
            '01' => 'Idea Generation',
            '02' => 'Customer & Value',
            '03' => 'Simple Business Models',
            '04' => 'Pitching'
        ],
        'gains' => [
            'Initiative',
            'Communication',
            'Collaboration',
            'Creative problem-solving'
        ],
        'offers' => [
            'Problem spotting and idea generation',
            'Understanding customers and value',
            'Simple business models, costs and pricing',
            'Branding, teamwork, pitching and feedback'
        ],
        'closing' => 'Build skills. Create confidently.'
    ],
    [
        'id' => 'chess',
        'title' => 'CHESS',
        'category' => 'MIND & NUMERACY',
        'tagline' => 'Plan ahead. Decide with confidence.',
        'grades' => 'Nursery - Grade 12',
        'format' => 'Live Classes',
        'level' => 'Progressive by age',
        'purpose' => 'A progressive chess journey from piece movement to tactics, strategy and thoughtful competitive play.',
        'image' => '/assets/images/Zuvio_Beyond_Website_Images/09_Chess.jpg',
        'pathways' => [
            '01' => 'Rules & Movement',
            '02' => 'Tactics',
            '03' => 'Strategy',
            '04' => 'Practice & Analysis'
        ],
        'gains' => [
            'Strategic thinking',
            'Focus',
            'Patience',
            'Decision-making'
        ],
        'offers' => [
            'Board setup, piece movement and rules',
            'Checks, mates and tactical patterns',
            'Opening principles and endgame basics',
            'Practice games, analysis and sportsmanship'
        ],
        'closing' => 'Build skills. Create confidently.'
    ],
    [
        'id' => 'digital-media-arts',
        'title' => 'DIGITAL MEDIA & ARTS',
        'category' => 'TECH & INNOVATION',
        'tagline' => 'Create, communicate and publish with purpose.',
        'grades' => 'Nursery - Grade 12',
        'format' => 'Live Classes',
        'level' => 'Progressive by age',
        'purpose' => 'Learners combine art and technology to design visual stories and build confident, responsible creator skills.',
        'image' => '/assets/images/Zuvio_Beyond_Website_Images/10_Digital_Media_and_Arts.jpg',
        'pathways' => [
            '01' => 'Digital Drawing',
            '02' => 'Visual Storytelling',
            '03' => 'Animation & Video',
            '04' => 'Responsible Publishing'
        ],
        'gains' => [
            'Visual literacy',
            'Storytelling',
            'Digital creativity',
            'Media responsibility'
        ],
        'offers' => [
            'Digital drawing and visual design basics',
            'Photography, composition and storytelling',
            'Animation, video and simple editing',
            'Copyright, online safety and responsible publishing'
        ],
        'closing' => 'Build skills. Create confidently.'
    ],
    [
        'id' => 'dance',
        'title' => 'DANCE',
        'category' => 'CREATIVE EXPRESSION',
        'tagline' => 'Move with joy, rhythm and confidence.',
        'grades' => 'Nursery - Grade 12',
        'format' => 'Live Classes',
        'level' => 'Progressive by age',
        'purpose' => 'Age-appropriate movement experiences develop expression, coordination and appreciation of varied dance forms.',
        'image' => '/assets/images/Zuvio_Beyond_Website_Images/11_Dance.jpg',
        'pathways' => [
            '01' => 'Rhythm & Movement',
            '02' => 'Technique',
            '03' => 'Choreography',
            '04' => 'Performance'
        ],
        'gains' => [
            'HTML structure',
            'Coordination',
            'Expression',
            'Fitness',
            'Confidence'
        ],
        'offers' => [
            'Rhythm, posture, balance and body awareness',
            'Creative movement and musical expression',
            'Technique and choreography by age level',
            'Performance confidence and cultural appreciation'
        ],
        'closing' => 'Build skills. Create confidently.'
    ],
    [
        'id' => 'art-craft',
        'title' => 'ART & CRAFT',
        'category' => 'CREATIVE EXPRESSION',
        'tagline' => 'Imagine freely. Make confidently.',
        'grades' => 'Nursery - Grade 12',
        'format' => 'Live Classes',
        'level' => 'Progressive by age',
        'purpose' => 'Open-ended and guided projects encourage experimentation with materials, techniques, ideas and personal expression.',
        'image' => '/assets/images/Zuvio_Beyond_Website_Images/12_Art_and_Craft.jpg',
        'pathways' => [
            '01' => 'Drawing & Painting',
            '02' => 'Paper Craft',
            '03' => 'Mixed Media',
            '04' => 'Creative Projects'
        ],
        'gains' => [
            'Imagination',
            'Fine motor skills',
            'Observation',
            'Creative confidence'
        ],
        'offers' => [
            'Drawing, colouring, painting and collage',
            'Paper craft, sculpture and mixed media',
            'Design thinking and purposeful making',
            'Artist-inspired projects and portfolio building'
        ],
        'closing' => 'Build skills. Create confidently.'
    ],
    [
        'id' => 'academic-support-enrichment',
        'title' => 'ACADEMIC SUPPORT & ENRICHMENT',
        'category' => 'LIFE, LEADERSHIP & SUPPORT',
        'tagline' => 'Stronger concepts. Greater confidence.',
        'grades' => 'Nursery - Grade 12',
        'format' => 'Live Classes',
        'level' => 'Progressive by age',
        'purpose' => 'Personalised academic guidance helps learners strengthen core concepts, close learning gaps and progress confidently at their own pace.',
        'image' => '/assets/images/Zuvio_Beyond_Website_Images/13_Academic_Support_and_Enrichment.jpg',
        'pathways' => [
            '01' => 'Concept Clarity',
            '02' => 'Homework Guidance',
            '03' => 'Practice & Revision',
            '04' => 'Assessment Readiness'
        ],
        'gains' => [
            'Concept clarity',
            'Academic confidence',
            'Independent study habits',
            'Improved performance'
        ],
        'offers' => [
            'Subject support in key academic areas',
            'Concept reinforcement and doubt-clearing',
            'Guided homework, practice and revision',
            'Preparation for school assessments'
        ],
        'closing' => 'Build skills. Create confidently.'
    ]
];

// Sidebar categories mapping
$categories = [
    'TECH & INNOVATION' => [
        ['name' => 'AI Explorers', 'link' => '#ai-explorers'],
        ['name' => 'Coding', 'link' => '#coding'],
        ['name' => 'Robotics', 'link' => '#robotics'],
        ['name' => 'Digital Media & Arts', 'link' => '#digital-media-arts']
    ],
    'MIND & NUMERACY' => [
        ['name' => 'Rubik\'s Cube', 'link' => '#rubiks-cube'],
        ['name' => 'Abacus', 'link' => '#abacus'],
        ['name' => 'Vedic Maths', 'link' => '#vedic-maths'],
        ['name' => 'Chess', 'link' => '#chess']
    ],
    'LIFE, LEADERSHIP & SUPPORT' => [
        ['name' => 'Financial Literacy', 'link' => '#financial-literacy'],
        ['name' => 'Entrepreneurship', 'link' => '#entrepreneurship'],
        ['name' => 'Academic Support & Enrichment', 'link' => '#academic-support-enrichment']
    ],
    'CREATIVE EXPRESSION' => [
        ['name' => 'Dance', 'link' => '#dance'],
        ['name' => 'Art & Craft', 'link' => '#art-craft']
    ]
];
?>

<style>
/* Zuvio Beyond Special Redesign Stylesheet */
html {
  scroll-behavior: smooth;
}

.beyond-hero {
  background: linear-gradient(135deg, var(--zuvio-deep-navy) 0%, var(--zuvio-navy) 100%);
  color: #FFFFFF;
  padding: 8rem 2rem;
  text-align: center;
  position: relative;
  overflow: hidden;
}
.beyond-hero::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  background-image: radial-gradient(circle at 80% 20%, rgba(10, 137, 152, 0.15) 0%, transparent 50%),
                    radial-gradient(circle at 20% 80%, rgba(217, 164, 65, 0.1) 0%, transparent 50%);
  pointer-events: none;
}
.beyond-hero-inner {
  max-width: 900px;
  margin: 0 auto;
  position: relative;
  z-index: 2;
}
.beyond-hero-tag {
  font-family: var(--font-secondary);
  font-size: 0.85rem;
  font-weight: 700;
  color: var(--color-gold);
  text-transform: uppercase;
  letter-spacing: 3px;
  margin-bottom: 0.5rem;
  display: block;
}
.beyond-hero-subtitle {
  font-family: var(--font-secondary);
  font-size: 1.1rem;
  font-weight: 600;
  color: var(--color-teal);
  text-transform: uppercase;
  letter-spacing: 2px;
  margin-bottom: 1.5rem;
  display: block;
}
.beyond-hero-title {
  font-size: 4rem;
  font-family: var(--font-primary);
  color: #FFFFFF;
  margin-bottom: 1.5rem;
  line-height: 1.15;
}
.beyond-hero-desc {
  font-size: 1.25rem;
  font-weight: 300;
  line-height: 1.7;
  color: #E2E8F0;
  margin-bottom: 2rem;
}
.beyond-hero-grades {
  display: inline-block;
  background-color: rgba(255,255,255,0.08);
  border: 1px solid rgba(255,255,255,0.15);
  border-radius: 50px;
  padding: 0.5rem 1.75rem;
  font-size: 0.95rem;
  font-weight: 600;
  letter-spacing: 1px;
  color: var(--color-gold);
}

/* Page Layout & Outer Container Overrides */
.beyond-layout-container {
  max-width: 1440px !important;
  padding: 0 2rem;
}

.beyond-container {
  max-width: 1400px;
  width: min(92vw, 1400px);
  margin: 0 auto;
  padding: 2rem 0 5rem 0;
}

/* Category Overview Section */
.beyond-category-section {
  padding: 5rem 0 2rem 0;
  max-width: 1400px;
  width: min(92vw, 1400px);
  margin: 0 auto;
}
.beyond-category-card-outer {
  background: var(--color-white);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-lg);
  padding: 3.5rem;
  box-shadow: var(--shadow-md);
  text-align: center;
}
.beyond-category-section-title {
  font-size: 2.25rem;
  color: var(--color-navy);
  margin-bottom: 0.75rem;
  font-family: var(--font-primary);
}
.beyond-category-section-desc {
  font-size: 1.1rem;
  color: var(--color-muted);
  margin-bottom: 3.5rem;
}
.beyond-category-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 2rem;
  margin-bottom: 3rem;
  text-align: left;
}
.beyond-category-block {
  background: var(--color-surface);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-md);
  padding: 1.75rem;
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}
.beyond-category-block-title {
  font-size: 0.85rem;
  font-weight: 700;
  color: var(--color-navy);
  text-transform: uppercase;
  letter-spacing: 1px;
  border-bottom: 1px solid var(--color-border);
  padding-bottom: 0.5rem;
  margin: 0;
}
.beyond-category-links {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}
.beyond-category-btn {
  font-size: 0.95rem;
  font-weight: 600;
  color: var(--color-muted);
  text-decoration: none;
  transition: all var(--transition-fast);
  display: inline-block;
}
.beyond-category-btn:hover {
  color: var(--color-gold);
  transform: translateX(3px);
}
.beyond-category-note {
  font-size: 0.9rem;
  font-style: italic;
  color: var(--color-muted);
  border-top: 1px dashed var(--color-border);
  padding-top: 1.5rem;
  margin: 0;
  line-height: 1.5;
}

/* Program Content Card */
.beyond-program-section {
  scroll-margin-top: 100px;
  margin-bottom: 6rem;
}
.beyond-program-section:last-child {
  margin-bottom: 0;
}
.beyond-program-card {
  background: var(--color-white);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-md);
  padding: 4rem;
  border: 1px solid var(--color-border);
  position: relative;
  overflow: hidden;
}
.beyond-program-card::after {
  content: '';
  position: absolute;
  top: 0; left: 0; width: 6px; height: 100%;
  background-color: var(--color-teal);
}
.beyond-program-grid {
  display: grid;
  grid-template-columns: 1.15fr 1fr;
  gap: 4.5rem;
  margin-bottom: 3rem;
}

/* Left Column elements */
.beyond-program-info-col {
  display: flex;
  flex-direction: column;
}
.beyond-program-category {
  font-size: 0.8rem;
  font-weight: 700;
  color: var(--color-teal);
  text-transform: uppercase;
  letter-spacing: 2px;
  margin-bottom: 0.5rem;
  display: block;
}
.beyond-program-title {
  font-size: 2.75rem;
  color: var(--color-navy);
  font-family: var(--font-primary);
  margin-bottom: 0.5rem;
  line-height: 1.15;
}
.beyond-program-tagline {
  font-size: 1.25rem;
  font-style: italic;
  color: var(--color-gold);
  font-weight: 500;
  display: block;
  margin-bottom: 2rem;
}
.beyond-program-purpose-title {
  font-size: 0.9rem;
  font-weight: 700;
  color: var(--color-navy);
  text-transform: uppercase;
  letter-spacing: 1px;
  margin-bottom: 0.75rem;
}
.beyond-program-purpose {
  font-size: 1.05rem;
  color: var(--color-text);
  line-height: 1.7;
  margin-bottom: 0;
}

/* Metadata Grid inside Info Column */
.beyond-metadata-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
  margin-bottom: 2.5rem;
}
.beyond-metadata-item {
  background: var(--color-surface-blue);
  border-radius: var(--radius-sm);
  padding: 0.75rem 0.5rem;
  text-align: center;
  border: 1px solid rgba(6,43,99,0.05);
}
.beyond-metadata-label {
  font-size: 0.65rem;
  font-weight: 700;
  color: var(--color-muted);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  display: block;
  margin-bottom: 0.25rem;
}
.beyond-metadata-val {
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--color-navy);
  line-height: 1.2;
}

/* Right Column elements */
.beyond-program-media-col {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
}
.beyond-program-image-wrapper {
  width: 320px;
  max-width: 100%;
  aspect-ratio: 1 / 1;
  height: auto;
  background: var(--color-white);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
  padding: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  margin-bottom: 2.5rem;
}
.beyond-program-image {
  width: 100%;
  height: 100%;
  object-fit: contain;
  display: block;
}
.beyond-pathways-title {
  font-size: 0.9rem;
  font-weight: 700;
  color: var(--color-navy);
  text-transform: uppercase;
  letter-spacing: 1px;
  margin-bottom: 1rem;
}
.beyond-pathways-container {
  margin-top: 2.5rem;
  margin-bottom: 2.5rem;
}
.beyond-pathways-grid {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 20px;
  width: 100%;
}
.beyond-pathway-card {
  background: var(--color-surface);
  border-radius: var(--radius-md);
  padding: 1.25rem;
  border: 1px solid var(--color-border);
  border-top-width: 4px;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  transition: transform var(--transition-fast), box-shadow var(--transition-fast);
  width: 100%;
  min-width: 0;
}
.beyond-pathway-card:hover {
  transform: translateY(-3px);
  box-shadow: var(--shadow-sm);
}
.beyond-pathway-num {
  font-size: 1.5rem;
  font-weight: 700;
  font-family: var(--font-primary);
  line-height: 1;
}
.beyond-pathway-name {
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--color-navy);
  line-height: 1.4;
}

/* Navy Panel */
.beyond-navy-panel {
  background-color: var(--zuvio-deep-navy);
  border-radius: 20px;
  padding: 3rem;
  color: #FFFFFF;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 4.5rem;
  box-shadow: var(--shadow-md);
  margin-top: 1rem;
}
.beyond-navy-panel-col {
  display: flex;
  flex-direction: column;
}
.beyond-navy-col-left {
  border-right: 2px solid var(--color-teal);
  padding-right: 4.5rem;
}
.beyond-panel-sec-title {
  font-family: var(--font-secondary);
  font-size: 1.25rem;
  font-weight: 800;
  color: #FFFFFF;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  margin-bottom: 2rem;
  padding: 0;
}
.beyond-panel-list {
  list-style: none;
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
  margin: 0;
  padding: 0;
}
.beyond-panel-item {
  font-size: 0.95rem;
  line-height: 1.5;
  color: #E2E8F0;
  position: relative;
  padding-left: 2rem;
  font-weight: 500;
}
.beyond-navy-col-left .beyond-panel-item::before {
  content: '●';
  position: absolute;
  left: 0;
  top: 0;
  color: var(--color-gold);
  font-size: 0.85rem;
}
.beyond-navy-col-right .beyond-panel-item::before {
  content: '●';
  position: absolute;
  left: 0;
  top: 0;
  color: var(--color-error);
  font-size: 0.85rem;
}
.beyond-capsule-badge {
  display: inline-block;
  align-self: flex-start;
  background-color: var(--color-teal);
  color: #FFFFFF;
  padding: 0.6rem 1.75rem;
  border-radius: 50px;
  font-size: 0.95rem;
  font-weight: 600;
  letter-spacing: 0.5px;
  margin-top: 2rem;
}

/* Responsive Overrides */
@media (max-width: 1024px) {
  .beyond-category-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
  }
  .beyond-program-grid {
    grid-template-columns: 1fr;
    gap: 3rem;
  }
  .beyond-pathways-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}
@media (max-width: 768px) {
  .beyond-hero {
    padding: 6rem 1.5rem;
  }
  .beyond-hero-title {
    font-size: 2.75rem;
  }
  .beyond-category-section {
    padding: 3rem 0 1.5rem 0;
  }
  .beyond-category-card-outer {
    padding: 2.5rem 1.75rem;
  }
  .beyond-category-section-title {
    font-size: 1.85rem;
  }
  .beyond-category-section-desc {
    margin-bottom: 2.5rem;
  }
  .beyond-program-card {
    padding: 2.5rem 2rem;
  }
  .beyond-navy-panel {
    grid-template-columns: 1fr;
    gap: 3rem;
    padding: 2.5rem 2rem;
  }
  .beyond-navy-col-left {
    border-right: none;
    border-bottom: 2px solid var(--color-teal);
    padding-right: 0;
    padding-bottom: 3rem;
  }
}
@media (max-width: 640px) {
  .beyond-category-grid {
    grid-template-columns: 1fr;
    gap: 1.25rem;
  }
  .beyond-metadata-grid {
    grid-template-columns: 1fr;
    gap: 0.75rem;
  }
  .beyond-pathways-grid {
    grid-template-columns: 1fr;
  }
  .beyond-hero-title {
    font-size: 2.25rem;
  }
}
</style>

<!-- Hero Section (Page 1 Content) -->
<section class="beyond-hero">
  <div class="beyond-hero-inner">
    <span class="beyond-hero-tag">ZUVIO BEYOND</span>
    <span class="beyond-hero-subtitle">LEARNING BEYOND CLASSROOMS</span>
    <h1 class="beyond-hero-title">Discover. Create. Grow<br>Beyond.</h1>
    <p class="beyond-hero-desc">
      A vibrant enrichment space for future-ready skills, creative expression and meaningful interests.
    </p>
    <div class="beyond-hero-grades">NURSERY - GRADE 12</div>
  </div>
</section>

<!-- Category Overview Section (Page 2 Content) -->
<section class="beyond-category-section">
  <div class="beyond-category-card-outer">
    <h3 class="beyond-category-section-title">Explore every possibility</h3>
    <p class="beyond-category-section-desc">Enrichment pathways designed for curious, creative and future-ready learners.</p>
    
    <div class="beyond-category-grid">
      <?php foreach ($categories as $cat_title => $items): ?>
        <div class="beyond-category-block">
          <h4 class="beyond-category-block-title"><?php echo h($cat_title); ?></h4>
          <div class="beyond-category-links">
            <?php foreach ($items as $item): ?>
              <a href="<?php echo h($item['link']); ?>" class="beyond-category-btn">
                <?php echo h($item['name']); ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    
    <p class="beyond-category-note">
      Recommended grade bands can be adapted to each learner's readiness, prior exposure and interests.
    </p>
  </div>
</section>

<!-- Page Body Container -->
<div class="container beyond-layout-container">
  <div class="beyond-container">

    <!-- Content: 13 programmes (Page 3 to 15 Content) -->
    <main class="beyond-content">
      <?php foreach ($programmes as $p): 
        // Resolve cache-busting timestamp for the image
        $image_full_path = dirname(__FILE__) . '/..' . $p['image'];
        $v_stamp = file_exists($image_full_path) ? filemtime($image_full_path) : time();
        $image_src = $p['image'] . '?v=' . $v_stamp;
        
        // Define color scheme for pathway cards
        $pathway_colors = [
            '01' => 'var(--color-error)', // Coral
            '02' => 'var(--color-teal)',  // Teal
            '03' => 'var(--color-gold)',  // Gold
            '04' => 'var(--color-navy)'   // Navy
        ];


      ?>
        <div class="beyond-program-section" id="<?php echo h($p['id']); ?>">
          <div class="beyond-program-card">
            
            <!-- Card Grid -->
            <div class="beyond-program-grid">
              
              <!-- Left Column: Title, Tagline, Metadata, Purpose -->
              <div class="beyond-program-info-col">
                <span class="beyond-program-category"><?php echo h($p['category']); ?></span>
                <h2 class="beyond-program-title"><?php echo h($p['title']); ?></h2>
                <span class="beyond-program-tagline"><?php echo h($p['tagline']); ?></span>
                
                <!-- Metadata pills -->
                <div class="beyond-metadata-grid">
                  <div class="beyond-metadata-item">
                    <span class="beyond-metadata-label">Grades</span>
                    <span class="beyond-metadata-val"><?php echo h($p['grades']); ?></span>
                  </div>
                  <div class="beyond-metadata-item">
                    <span class="beyond-metadata-label">Format</span>
                    <span class="beyond-metadata-val"><?php echo h($p['format']); ?></span>
                  </div>
                  <div class="beyond-metadata-item">
                    <span class="beyond-metadata-label">Level</span>
                    <span class="beyond-metadata-val"><?php echo h($p['level']); ?></span>
                  </div>
                </div>

                <h4 class="beyond-program-purpose-title">Course Purpose</h4>
                <p class="beyond-program-purpose"><?php echo h($p['purpose']); ?></p>
              </div>

              <!-- Right Column: Image -->
              <div class="beyond-program-media-col">
                <div class="beyond-program-image-wrapper">
                  <img src="<?php echo h($image_src); ?>" alt="<?php echo h($p['title']); ?>" class="beyond-program-image" loading="lazy">
                </div>
              </div>

            </div>

            <!-- Middle block: Pathways Title & Full-Width Pathway Grid -->
            <div class="beyond-pathways-container">
              <h4 class="beyond-pathways-title">Pathways</h4>
              <div class="beyond-pathways-grid">
                <?php foreach ($p['pathways'] as $num => $path_name): 
                  $border_color = $pathway_colors[$num] ?? 'var(--color-teal)';
                ?>
                  <div class="beyond-pathway-card" style="border-top-color: <?php echo $border_color; ?>;">
                    <span class="beyond-pathway-num" style="color: <?php echo $border_color; ?>;"><?php echo h($num); ?></span>
                    <span class="beyond-pathway-name"><?php echo h($path_name); ?></span>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Below: Full-Width Navy Panel (WHAT LEARNERS GAIN & THE COURSE OFFERS) -->
            <div class="beyond-navy-panel">
              <!-- Left Column: What Learners Gain -->
              <div class="beyond-navy-panel-col beyond-navy-col-left">
                <h5 class="beyond-panel-sec-title">WHAT LEARNERS GAIN</h5>
                <ul class="beyond-panel-list">
                  <?php foreach ($p['gains'] as $gain): ?>
                    <li class="beyond-panel-item"><?php echo h($gain); ?></li>
                  <?php endforeach; ?>
                </ul>
                
                <!-- Capsule Badge at the bottom left -->
                <div class="beyond-capsule-badge">
                  <?php echo h($p['closing']); ?>
                </div>
              </div>
              
              <!-- Right Column: The Course Offers -->
              <div class="beyond-navy-panel-col beyond-navy-col-right">
                <h5 class="beyond-panel-sec-title">THE COURSE OFFERS</h5>
                <ul class="beyond-panel-list">
                  <?php foreach ($p['offers'] as $offer): ?>
                    <li class="beyond-panel-item"><?php echo h($offer); ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>

          </div>
        </div>
      <?php endforeach; ?>
    </main>

  </div>
</div>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
