<?php
// Zuvio Global School - About Us Leadership Detail Page
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

$leader = null;
if ($db && isset($profile_slug)) {
    try {
        $stmt = $db->prepare("SELECT * FROM `leadership` WHERE `slug` = ? AND `is_active` = 1 LIMIT 1");
        $stmt->execute([$profile_slug]);
        $leader = $stmt->fetch();
    } catch (Exception $e) {
        error_log("[About Detail Error] " . $e->getMessage());
    }
}

// Fallback logic if database is offline or empty
if (!$leader && (isset($_GET['debug_db']) || !$db || empty($leader))) {
    $fallbacks = [
        'sharmin-habib' => [
            'name' => 'Sharmin Habib',
            'designation' => 'Co-Founder & Director',
            'image' => '/assets/images/Profile_Images/Sharmin_Professional_Profile.webp',
            'short_description' => 'Sharmin Habib is a seasoned educationist and edtech growth expert with over 18 years of experience. She has successfully founded and scaled preschools and digital K–8 learning models in domestic and international markets.',
            'bio' => "Sharmin Habib is a seasoned education, business development, growth, and expansion professional with over 18 years of experience across early childhood education, online schooling, EdTech, and strategic business growth.\n\nShe served as the Head of Business at The Himalayan School until April 2026, where she played an instrumental role in growing and scaling the school’s online education vertical. Her responsibilities spanned business strategy, student acquisition, admissions, partnerships, market expansion, team development, and strengthening the overall positioning of the school in the online education space.\n\nOver the years, Sharmin has developed extensive expertise in:\n\n• Online School Growth & Expansion – Building strategies to expand the reach and presence of online schooling across markets.\n\n• Business Development & Revenue Growth – Identifying new opportunities, developing growth channels, and driving sustainable business performance.\n\n• Admissions & Student Acquisition – Developing outreach, counselling, conversion, and enrolment strategies to strengthen admissions.\n\n• Strategic Partnerships – Building relationships with education organisations, institutions, communities, and other strategic partners.\n\n• Team Building & Leadership – Recruiting, mentoring, and leading cross-functional teams across academics, admissions, operations, and business development.\n\n• Market Expansion & Brand Positioning – Creating strategies to enter new markets and strengthen an education brand's competitive positioning.\n\n• Franchise Development – Extensive experience in developing and expanding education franchise networks in domestic and international markets.\n\n• Education Operations – Understanding the complete learner journey and aligning academic, operational, and business functions for effective delivery.\n\n• EdTech & Digital Learning – Strong experience in technology-enabled education and developing scalable digital learning models for K–8 learners.\n\n• Entrepreneurship & Institution Building – Experience conceptualising, launching, operating, and scaling education ventures from the ground up.\n\nAs the founder of Kindercare Services Pvt. Ltd. and I3 Education Pvt. Ltd., she has successfully led ventures in preschool education and digital K–8 learning. Her extensive experience also includes international business and franchise development, including her role as Global Franchisee Head at K12 Education.\n\nSharmin brings together education expertise, entrepreneurial thinking, strategic leadership, and hands-on business execution. Her ability to understand both the academic and commercial dimensions of education has enabled her to build teams, develop markets, strengthen enrolments, establish partnerships, and contribute to the growth and scalability of education organisations.",
            'message' => ''
        ],
        'deepak-jain' => [
            'name' => 'Deepak Jain',
            'designation' => 'Co-Founder & Director',
            'image' => '/assets/images/Profile_Images/Deepak_Professional_Profile.webp',
            'short_description' => 'Deepak Jain is an entrepreneur and business professional who brings a strategic, systems-oriented perspective to Zuvio Global School. He oversees Zuvio’s strategic direction, operations, and partnerships for sustainable, long-term growth.',
            'bio' => "With a strong entrepreneurial mindset and experience in business, Deepak Jain brings a practical, strategic and growth-oriented perspective to Zuvio Global School.\n\nHis belief is that building a meaningful education platform requires more than a good academic model—it requires strong systems, responsible leadership, innovation and a clear understanding of the changing needs of families and children.\n\nAs Co-Founder, Deepak plays an important role in shaping Zuvio’s strategic direction, operations, partnerships and long-term growth, helping transform the vision of Zuvio into a sustainable and accessible education platform.\n\nHis vision is to help build an institution that combines the values of education with the possibilities of technology, creating a learning ecosystem that can grow with the needs of the next generation.",
            'message' => 'For Deepak, Zuvio is not simply about creating another school. It is about building an education platform for the future—one that can create meaningful opportunities for children, families and educators.'
        ],
        'pragya-jain' => [
            'name' => 'Pragya Jain',
            'designation' => 'Co-Founder & Director',
            'image' => '/assets/images/Profile_Images/Pragya_Professional_Profile.webp',
            'short_description' => 'Pragya Jain is an educationist dedicated to child-centric learning that prepares students for life. She conceptualized Zuvio to merge academic rigor with personalization, creativity, confidence, and future-ready skills.',
            'bio' => "I have always believed that education should do more than prepare a child for examinations—it should prepare them for life, change and the possibilities of tomorrow.\n\nMy vision for Zuvio was born from a simple question: Can we create a learning environment where every child feels understood, encouraged and inspired to discover their own potential?\n\nZuvio is my endeavour to build an education experience that brings together strong academics, personalised learning, creativity, confidence and future-ready skills—while giving children the freedom to learn beyond traditional boundaries.\n\nI envision Zuvio as a school where learning is not limited to textbooks or classrooms, but becomes a continuous journey of curiosity, exploration, application and growth.\n\nEvery child learns differently. Every mind has possibilities. And every possibility deserves the opportunity to grow.",
            'message' => 'Every child learns differently. Every mind has possibilities. And every possibility deserves the opportunity to grow.'
        ],
        'rashmi-bhasin' => [
            'name' => 'Rashmi Bhasin',
            'designation' => 'Academic Head',
            'image' => '/assets/images/Profile_Images/Rashmi_Professional_Profile.webp',
            'short_description' => 'Rashmi Bhasin is the Academic Head of Zuvio Global School. She is a visionary curriculum thinker and mentor committed to designing personalized, interactive, and child-centered online homeschooling experiences for K to Grade 8.',
            'bio' => "As an educationist, my central role as an Academic Head would be in shaping the academic vision, learning culture, and educational philosophy of an online homeschooling programme. The role is not limited to curriculum management or academic administration; it is about understanding how children learn, identifying their individual needs, empowering teachers, and creating meaningful learning experiences that prepare students for both academic success and life beyond school.\n\nAs an Academic Head I would have a clear understanding of child-centred, personalised, experiential, and competency-based education. In an online homeschooling environment in Zuvio Global School, this becomes especially important because students have different abilities, learning styles, interests, and academic backgrounds. I would therefore create a flexible academic structure that would maintain common learning standards while allowing teachers to personalise instruction according to each child's needs.\n\nAs an educationist, I would lead the development of a curriculum that balances academic rigour with creativity, critical thinking, communication, collaboration, problem-solving, technology, life skills, and character development.\n\nAs an Academic Head I would also act as a mentor and academic leader for teachers. Teachers need more than subject knowledge; they need the ability to understand children, facilitate discussions, use technology effectively, design engaging learning experiences, assess learning meaningfully, and provide individual support. Through induction, training, mentoring, classroom observations, peer learning, and constructive feedback, I as an Academic Head would build a strong professional teaching culture.\n\nAn educationist also recognises that parents are important partners in a child's education. In online homeschooling, parents need clarity about the child's learning journey, progress, strengths, challenges, and ways they can provide support at home. The Academic Head would therefore create transparent and meaningful communication systems that build trust and encourage collaboration between teachers, parents, and students.\n\nMost importantly, being the Academic Head I would be like the guardian of the institution's educational philosophy who would ensure that every academic decision reflects the school's core values and contributes to the development of the whole child. The focus would be on creating learners who are curious, confident, responsible, adaptable, creative, and capable of thinking independently.\n\nIn essence, an Academic Head as an educationist is a visionary academic leader, curriculum thinker, teacher mentor, child advocate, and learning-culture builder who ensures that education remains purposeful, personalised, engaging, and future-ready while maintaining high academic standards.\n\nAbout the Curriculum and Academics\n\nAs an Academic Head of a Zuvio Global online homeschooling programme I would be responsible for providing strong academic leadership and building a structured, personalised, and future-ready learning environment for students. The role goes beyond managing curriculum and teachers; it involves creating an educational ecosystem where every child receives meaningful learning experiences, individual attention, continuous support, and opportunities to develop both academic and life skills.\n\nI as an Academic Head would be responsible for setting the overall academic vision and ensuring that this vision is consistently translated into classroom practice. My key responsibility as an Academic Head would be to design and oversee a well-structured curriculum from K to Grade 8 for Zuvio Global School. The curriculum would maintain strong academic standards which would be flexible enough to accommodate different learning abilities, interests, learning styles, and individual needs. It would integrate core subjects with areas such as critical thinking, creativity, communication, collaboration, problem-solving, technology, coding, financial literacy, entrepreneurship, life skills, arts, wellness, and global awareness. The objective is to ensure that students are not only prepared for examinations but also equipped with the knowledge, skills, confidence, and adaptability required for the future.\n\nAs an Academic Head I would also be responsible for developing effective academic systems and processes. These include the academic calendar, annual academic planner, lesson-planning framework, assessment system, student progress tracking, teacher onboarding, academic SOPs, quality assurance mechanisms, and parent communication systems. Clear systems are particularly important in online homeschooling because students come from different academic backgrounds and require consistency, structure, and personalised support. Teacher development is another major responsibility.\n\nBeing an Academic Head I would recruit teachers for Zuvio Global School who would demonstrate strong subject knowledge, communication skills, empathy, digital competence, adaptability, and the ability to engage children online. Teachers would receive structured induction, training, mentoring, classroom observations, and regular feedback. The aim would not be to micromanage teachers but to create a culture of professional growth, accountability, collaboration, and continuous improvement.\n\nIn Zuvio Global online schooling, student engagement would be especially important. As an Academic Head I would ensure that classes are interactive rather than lecture-based. Teachers should use questioning, discussions, demonstrations, activities, projects, digital tools, real-world examples, and collaborative tasks to keep students actively involved. Class duration, screen time, breaks, asynchronous learning, and independent activities should be thoughtfully planned according to the age and developmental needs of students. Assessment should also move beyond marks and examinations. I as an Academic Head would establish a balanced assessment framework that would include formative assessments, quizzes, projects, presentations, portfolios, practical activities, competency-based assessments, self-assessment, and periodic summative assessments. Student progress would be tracked regularly so that learning gaps can be identified early and appropriate interventions can be provided.\n\nParent partnership is equally important in online homeschooling. In Zuvio Global School as an Academic Head I would establish transparent and meaningful communication with parents through regular progress updates, academic reports, feedback meetings, and clear guidance on how parents can support learning at home. Parents should understand not only what their child has achieved, but also their strengths, areas for improvement, learning habits, and next steps.\n\nUltimately, being an Academic Head I would build a culture where academic excellence and holistic development would go hand in hand. The goal is to create an online homeschooling experience where children feel supported, challenged, motivated, and confident. The success of the Academic Head should therefore be measured not only by academic results, but by how effectively the programme develops curious, independent, responsible, creative, and future-ready learners.",
            'message' => 'My vision as the Academic Head of Zuvio Global School is to build a future-ready, child-centred, and globally relevant learning ecosystem where academic excellence goes hand in hand with curiosity, creativity, character, and real-world skills. I envision Zuvio as a school where children are not simply prepared to score well in examinations, but are empowered to think independently, communicate confidently, solve problems, embrace technology, and become responsible global citizens.'
        ]
    ];
    if (isset($fallbacks[$profile_slug])) {
        $leader = $fallbacks[$profile_slug];
        $leader['slug'] = $profile_slug;
    }
}

if (!$leader) {
    header('HTTP/1.1 404 Not Found');
    include_once dirname(__FILE__) . '/404.php';
    exit;
}

// Override default SEO variables
$seo = [
    'seo_title' => $leader['name'] . ' | ' . $leader['designation'] . ' | Zuvio Global School',
    'meta_description' => $leader['short_description'] ?: $leader['name'] . ' serves as ' . $leader['designation'] . ' at Zuvio Global School.',
    'canonical_url' => 'https://zuvioglobalschool.com/about/' . $leader['slug'],
    'og_title' => $leader['name'] . ' - ' . $leader['designation'],
    'og_description' => $leader['short_description'] ?: $leader['designation'],
    'og_image' => $leader['image'] ?: '/assets/images/logo.png',
    'index_status' => 'index, follow'
];

$page_slug = 'about';
include_once dirname(__FILE__) . '/../includes/header.php';
?>

<!-- Profile Detail Layout -->
<section class="about-detail-wrapper" style="background-color: var(--color-bg); min-height: 80vh; font-family: var(--font-secondary); padding: 4rem 1.5rem;">
  <div class="container" style="max-width: 1000px; margin: 0 auto;">
    
    <!-- Breadcrumbs / Back button -->
    <div style="margin-bottom: 2.5rem;">
      <a href="/about" class="btn btn-outline" style="padding: 0.6rem 1.5rem; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; font-size: 0.9rem;">
        &larr; Back to About Us
      </a>
    </div>

    <!-- Main Content Card -->
    <div style="background-color: #FFFFFF; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-md); border: 1px solid var(--color-border); display: flex; flex-direction: column;">
      
      <!-- Split Banner / Profile Summary -->
      <div style="background-color: var(--color-navy-dark); color: #FFFFFF; padding: 3rem 2.5rem; display: flex; flex-wrap: wrap; gap: 3rem; align-items: center;">
        
        <!-- Image Column -->
        <div style="flex: 1 1 280px; display: flex; justify-content: center;">
          <div style="width: 260px; height: 260px; border-radius: 50%; overflow: hidden; border: 4px solid var(--color-gold); box-shadow: var(--shadow-sm); background-color: var(--color-navy);">
            <?php if (!empty($leader['image'])): ?>
              <img src="<?php echo h($leader['image']); ?>" alt="<?php echo h($leader['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
            <?php else: ?>
              <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: bold; color: #FFFFFF; font-family: var(--font-primary);">
                <?php echo h(substr($leader['name'], 0, 1)); ?>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Meta Text Column -->
        <div style="flex: 2 2 400px;">
          <span style="font-size: 0.85rem; font-weight: 600; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.5rem;">
            Academic Leadership
          </span>
          <h1 style="font-size: 2.5rem; font-family: var(--font-primary); color: #FFFFFF; margin-bottom: 0.5rem; line-height: 1.2;">
            <?php echo h($leader['name']); ?>
          </h1>
          <p style="font-size: 1.15rem; color: #E2E8F0; font-weight: 500; margin-bottom: 1.5rem;">
            <?php echo h($leader['designation']); ?>
          </p>
          
          <?php if (!empty($leader['short_description'])): ?>
            <div style="border-left: 3px solid var(--color-gold); padding-left: 1.25rem; font-size: 0.95rem; color: #CBD5E1; line-height: 1.6; font-style: italic;">
              "<?php echo h($leader['short_description']); ?>"
            </div>
          <?php endif; ?>
        </div>

      </div>

      <!-- Long Form Biography Details -->
      <div style="padding: 3.5rem 3rem; color: var(--color-text); font-size: 1.05rem; line-height: 1.8;">
        
        <!-- Full Biography -->
        <div>
          <h2 style="font-size: 1.75rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 1.5rem; border-bottom: 2px solid var(--color-border); padding-bottom: 0.5rem;">
            Professional Biography
          </h2>
          <div style="white-space: pre-line;">
            <?php echo nl2br(h($leader['bio'])); ?>
          </div>
        </div>

        <!-- Personal message/quote if present -->
        <?php if (!empty($leader['message'])): ?>
          <div style="margin-top: 3.5rem; padding: 2rem 2.5rem; background-color: var(--color-surface-warm); border-left: 5px solid var(--color-gold); border-radius: var(--radius-sm);">
            <h3 style="font-size: 1.2rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px;">
              Personal Message
            </h3>
            <p style="font-style: italic; color: var(--color-text); margin: 0; line-height: 1.7;">
              "<?php echo h($leader['message']); ?>"
            </p>
            <span style="display: block; margin-top: 1rem; text-align: right; font-weight: 600; color: var(--color-navy); font-size: 0.9rem;">
              — <?php echo h($leader['name']); ?>, <?php echo h($leader['designation']); ?>
            </span>
          </div>
        <?php endif; ?>

      </div>

    </div>

    <!-- Back to listings button at bottom -->
    <div style="margin-top: 3rem; text-align: center;">
      <a href="/about" class="btn btn-primary" style="padding: 0.75rem 2.5rem;">
        &larr; Back to Leadership Listings
      </a>
    </div>

  </div>
</section>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
