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
            'designation' => 'Head of Business and Operations',
            'category' => 'Academic Leadership',
            'image' => '/assets/images/Profile_Images/Sharmin_Professional_Profile.webp',
            'short_description' => 'Sharmin Habib is the Head of Business and Operations at Zuvio Global School with over 18 years of experience across online schooling, EdTech growth, operations, and scalable digital learning models.',
            'bio' => "Sharmin Habib is a seasoned education, business development, growth, and expansion professional with over 18 years of experience across early childhood education, online schooling, EdTech, and strategic business growth.\n\nAs Head of Business and Operations at Zuvio Global School, she plays an instrumental role in driving business strategy, academic operations, student acquisition, partnerships, and market expansion.\n\nShe served as the Head of Business at The Himalayan School until April 2026, where she played an instrumental role in growing and scaling the school’s online education vertical. Her responsibilities spanned business strategy, student acquisition, admissions, partnerships, market expansion, team development, and strengthening the overall positioning of the school in the online education space.\n\nOver the years, Sharmin has developed extensive expertise in:\n\n• Online School Growth & Expansion – Building strategies to expand the reach and presence of online schooling across markets.\n\n• Business Development & Revenue Growth – Identifying new opportunities, developing growth channels, and driving sustainable business performance.\n\n• Admissions & Student Acquisition – Developing outreach, counselling, conversion, and enrolment strategies to strengthen admissions.\n\n• Strategic Partnerships – Building relationships with education organisations, institutions, communities, and other strategic partners.\n\n• Team Building & Leadership – Recruiting, mentoring, and leading cross-functional teams across academics, admissions, operations, and business development.\n\n• Market Expansion & Brand Positioning – Creating strategies to enter new markets and strengthen an education brand's competitive positioning.\n\n• Franchise Development – Extensive experience in developing and expanding education franchise networks in domestic and international markets.\n\n• Education Operations – Understanding the complete learner journey and aligning academic, operational, and business functions for effective delivery.\n\n• EdTech & Digital Learning – Strong experience in technology-enabled education and developing scalable digital learning models for K–8 learners.\n\n• Entrepreneurship & Institution Building – Experience conceptualising, launching, operating, and scaling education ventures from the ground up.\n\nAs the founder of Kindercare Services Pvt. Ltd. and I3 Education Pvt. Ltd., she has successfully led ventures in preschool education and digital K–8 learning. Her extensive experience also includes international business and franchise development, including her role as Global Franchisee Head at K12 Education.\n\nSharmin brings together education expertise, entrepreneurial thinking, strategic leadership, and hands-on business execution. Her ability to understand both the academic and commercial dimensions of education has enabled her to build teams, develop markets, strengthen enrolments, establish partnerships, and contribute to the growth and scalability of education organisations.",
            'message' => ''
        ],
        'deepak-jain' => [
            'name' => 'Deepak Jain',
            'designation' => 'Co-Founder & Director',
            'category' => 'Board of Directors',
            'image' => '/assets/images/Profile_Images/Deepak_Professional_Profile.webp',
            'short_description' => 'Deepak Jain is an entrepreneur and business professional who brings a strategic, systems-oriented perspective to Zuvio Global School. He oversees Zuvio’s strategic direction, operations, and partnerships for sustainable, long-term growth.',
            'bio' => "With a strong entrepreneurial mindset and experience in business, Deepak Jain brings a practical, strategic and growth-oriented perspective to Zuvio Global School.\n\nHis belief is that building a meaningful education platform requires more than a good academic model—it requires strong systems, responsible leadership, innovation and a clear understanding of the changing needs of families and children.\n\nAs Co-Founder, Deepak plays an important role in shaping Zuvio’s strategic direction, operations, partnerships and long-term growth, helping transform the vision of Zuvio into a sustainable and accessible education platform.\n\nHis vision is to help build an institution that combines the values of education with the possibilities of technology, creating a learning ecosystem that can grow with the needs of the next generation.",
            'message' => 'For Deepak, Zuvio is not simply about creating another school. It is about building an education platform for the future—one that can create meaningful opportunities for children, families and educators.'
        ],
        'pragya-jain' => [
            'name' => 'Pragya Jain',
            'designation' => 'Co-Founder & Director',
            'category' => 'Board of Directors',
            'image' => '/assets/images/Profile_Images/Pragya_Professional_Profile.webp',
            'short_description' => 'Pragya Jain is an educationist dedicated to child-centric learning that prepares students for life. She conceptualized Zuvio to merge academic rigor with personalization, creativity, confidence, and future-ready skills.',
            'bio' => "I have always believed that education should do more than prepare a child for examinations—it should prepare them for life, change and the possibilities of tomorrow.\n\nMy vision for Zuvio was born from a simple question: Can we create a learning environment where every child feels understood, encouraged and inspired to discover their own potential?\n\nZuvio is my endeavour to build an education experience that brings together strong academics, personalised learning, creativity, confidence and future-ready skills—while giving children the freedom to learn beyond traditional boundaries.\n\nI envision Zuvio as a school where learning is not limited to textbooks or classrooms, but becomes a continuous journey of curiosity, exploration, application and growth.\n\nEvery child learns differently. Every mind has possibilities. And every possibility deserves the opportunity to grow.",
            'message' => 'Every child learns differently. Every mind has possibilities. And every possibility deserves the opportunity to grow.'
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

// Ensure category and designation are properly formatted if loaded from db or session
if (($leader['slug'] ?? '') === 'sharmin-habib') {
    $leader['designation'] = 'Head of Business and Operations';
    $leader['category'] = 'Academic Leadership';
} elseif (empty($leader['category'])) {
    $leader['category'] = 'Board of Directors';
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

function format_profile_biography($text) {
    if (empty($text)) return '';
    $lines = preg_split("/\r\n|\n|\r/", trim($text));
    $html = "";
    $in_list = false;
    
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($trimmed === '') continue;
        
        // Detect bullet points: lines starting with •, -, or *
        if (preg_match("/^[•\-\*]\s*(.*)$/u", $trimmed, $matches)) {
            if (!$in_list) {
                $html .= "<ul class=\"bio-expertise-list\" style=\"margin: 1.25rem 0 1.75rem 0; padding-left: 0; list-style: none; display: flex; flex-direction: column; gap: 0.65rem;\">\n";
                $in_list = true;
            }
            $content = htmlspecialchars($matches[1], ENT_QUOTES, "UTF-8");
            // Bold title if structured with en-dash, em-dash, or hyphen
            if (preg_match("/^([^–—\-]+)\s*([–—\-])\s*(.*)$/u", $content, $c_matches)) {
                $content = "<strong style=\"color: var(--color-navy); font-weight: 700;\">" . trim($c_matches[1]) . "</strong> " . $c_matches[2] . " " . trim($c_matches[3]);
            }
            $html .= "  <li style=\"position: relative; padding-left: 1.6rem; line-height: 1.6; color: var(--color-text); font-size: 0.98rem;\"><span style=\"position: absolute; left: 0.2rem; top: 0.55rem; width: 6px; height: 6px; border-radius: 50%; background: var(--color-gold); display: inline-block;\"></span>" . $content . "</li>\n";
        } else {
            if ($in_list) {
                $html .= "</ul>\n";
                $in_list = false;
            }
            $html .= "<p style=\"margin-bottom: 1.25rem; line-height: 1.75; color: var(--color-text); font-size: 1.02rem;\">" . htmlspecialchars($trimmed, ENT_QUOTES, "UTF-8") . "</p>\n";
        }
    }
    if ($in_list) {
        $html .= "</ul>\n";
    }
    return $html;
}

include_once dirname(__FILE__) . '/../includes/header.php';

// Standardized full-width breadcrumbs
render_breadcrumbs([
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'About Us', 'url' => '/about-zuvio'],
    ['label' => 'Our Team', 'url' => '/our-team'],
    ['label' => $leader['name']]
]);
?>

<!-- Profile Detail Layout -->
<section class="about-detail-wrapper" style="background-color: var(--color-bg); min-height: 80vh; font-family: var(--font-secondary); padding: 4rem 1.5rem;">
  <div class="container" style="max-width: 1000px; margin: 0 auto;">
    
    <!-- Top Back button -->
    <div style="margin-bottom: 2rem;">
      <a href="/our-team" class="btn btn-outline" style="padding: 0.55rem 1.35rem; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; font-size: 0.85rem; font-weight: 600;">
        &larr; Back to Our Team
      </a>
    </div>

    <!-- Main Profile Card Container with Full 4-sided Border -->
    <div class="card" style="background: #FFFFFF; border-radius: var(--radius-lg); border: 1px solid var(--color-border); box-shadow: var(--shadow-md); overflow: hidden; padding: 0;">
      
      <!-- Profile Header Hero Banner -->
      <div style="background: linear-gradient(135deg, var(--color-navy-dark) 0%, var(--color-navy) 100%); padding: 3.5rem 3rem; display: flex; gap: 3rem; align-items: center; flex-wrap: wrap;">
        
        <!-- Avatar Photo Frame -->
        <div style="width: 180px; height: 180px; border-radius: 50%; overflow: hidden; border: 4px solid var(--color-gold); box-shadow: var(--shadow-lg); background-color: var(--pastel-blue); flex-shrink: 0;">
          <img src="<?php echo h($leader['image']); ?>" alt="<?php echo h($leader['name']); ?>" style="width: 100%; height: 100%; object-fit: cover; object-position: top center;">
        </div>

        <!-- Meta Text Column -->
        <div style="flex: 2 2 400px;">
          <span style="font-size: 0.85rem; font-weight: 600; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.5rem;">
            <?php echo h($leader['category'] ?? ($profile_slug === 'sharmin-habib' ? 'Academic Leadership' : 'Board of Directors')); ?>
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
      <div style="padding: 3.5rem 3rem; color: var(--color-text);">
        
        <!-- Full Biography -->
        <div>
          <h2 style="font-size: 1.75rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 1.5rem; border-bottom: 2px solid var(--color-border); padding-bottom: 0.5rem;">
            Professional Biography
          </h2>
          <div class="bio-content-body">
            <?php echo format_profile_biography($leader['bio']); ?>
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
      <a href="/our-team" class="btn btn-primary" style="padding: 0.75rem 2.5rem; font-weight: 600;">
        &larr; Back to Leadership Listings
      </a>
    </div>

  </div>
</section>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
