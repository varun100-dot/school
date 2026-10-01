<?php
// Zuvio Global School - Complete Admin Homepage CMS Manager
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_login();

$tab = $_GET['tab'] ?? 'hero_form';
$msg = $_GET['msg'] ?? '';
$error = '';

// Load persistent homepage CMS JSON
$db_home = get_json_setting('cms_homepage', []);
if (!isset($_SESSION['mock_cms_homepage']) || !empty($db_home)) {
    $_SESSION['mock_cms_homepage'] = !empty($db_home) ? $db_home : [];
}
$home_cms = &$_SESSION['mock_cms_homepage'];

// =============================================================================
// DEFAULT INITIALIZATION FOR ALL 16 HOMEPAGE SECTIONS
// =============================================================================

// 1. Hero Side Enquiry Form
if (!isset($home_cms['hero_form'])) {
    $home_cms['hero_form'] = [
        'is_active' => 1,
        'badge' => 'Admissions 2026–27',
        'title' => 'Talk to an Admission Counselor',
        'subtitle' => 'Get immediate guidance on curriculum, timings, and personalized learning pathways.',
        'btn_text' => 'Request Academic Roadmap',
        'whatsapp_number' => '917827262956',
        'consent_note' => 'By submitting this form, you authorize Zuvio Global School to contact you via Email, SMS, Call & WhatsApp.'
    ];
}

// 2. About Zuvio
if (!isset($home_cms['about_zuvio'])) {
    $home_cms['about_zuvio'] = [
        'is_active' => 1,
        'badge' => 'Who Are We & Why Zuvio',
        'title' => 'About Zuvio',
        'lead_text' => 'Zuvio Global School is an online school built on one belief: education should adapt to the child, not the child to the system.',
        'body_text' => 'We bring together a structured, curriculum-aligned programme, caring teachers and thoughtful technology to create a flexible, personalised learning experience your child can access from anywhere. Different ways of learning. One community. Equal opportunities.',
        'highlight_text' => "That's what learning beyond boundaries means.",
        'cta_text' => 'Read Our Story →',
        'cta_url' => '/about',
        'image' => '/assets/images/Teacher interacting with students.webp',
        'image_title' => 'Learning Beyond Boundaries',
        'image_subtitle' => 'Personalised, 100% Live Online Schooling • Kindergarten to Grade 8th'
    ];
}

// 3. Who Should Choose Zuvio
if (!isset($home_cms['who_should_choose'])) {
    $home_cms['who_should_choose'] = [
        'is_active' => 1,
        'badge' => 'Tailored for Modern Learners',
        'title' => 'Who Should Choose Zuvio',
        'subtitle' => 'Zuvio is for families who want learning to fit their life — not their life to revolve around a timetable.',
        'cards' => [
            ['title' => 'Globally Mobile Families', 'desc' => 'Globally mobile families who move between cities or countries and want learning continuity without disruptions.'],
            ['title' => 'Homeschooling & Alternative Learners', 'desc' => 'Homeschooling & alternative-learning families who want structure with freedom and teacher guidance.'],
            ['title' => 'Young Athletes, Artists & Performers', 'desc' => 'Young athletes, artists & performers balancing demanding training and rehearsal schedules.'],
            ['title' => 'Children Who Thrive Online', 'desc' => 'Children who thrive online and learn best in a digital environment with modern interactive tools.'],
            ['title' => 'Personalised Approach Seekers', 'desc' => 'Learners who need a more personalised approach, pace or attention to reach their full potential.'],
            ['title' => 'Alternative Schooling Environment', 'desc' => "Children who need a different schooling environment when traditional school isn't the right fit."]
        ],
        'footer_note' => 'If you believe education should adapt to the child, Zuvio may be the right choice.',
        'cta_text' => 'Check Age & Grade Eligibility →',
        'cta_url' => '/admissions#eligibility'
    ];
}

// 4. Curriculum Pathways
if (!isset($home_cms['curriculum_pathways'])) {
    $home_cms['curriculum_pathways'] = [
        'is_active' => 1,
        'badge' => 'Curriculum Pathways',
        'title' => 'A Future-Ready Learning Journey — Kindergarten to Grade 8th',
        'subtitle' => 'Mapped to CBSE, NEP 2020 and NCF — strong academic foundations blended with creativity, communication, digital fluency and real-world learning.',
        'stages' => [
            [
                'badge' => 'K–KG',
                'title' => 'Early Years · K–KG',
                'keywords' => 'Explore • Play • Discover',
                'bullets' => "Learning through stories, play, music and hands-on activities\n• Early literacy, phonics and numeracy\n• Communication, creativity and social-emotional growth",
                'outcome' => 'Confidence, curiosity and strong foundations'
            ],
            [
                'badge' => 'Grades 1–2',
                'title' => 'Foundation Stage · Grades 1–2',
                'keywords' => 'Build • Question • Create',
                'bullets' => "Strengthening reading, writing and maths\n• Connecting classroom concepts to everyday life\n• Art, life skills and digital literacy",
                'outcome' => 'Numeracy, communication and independent thinking'
            ],
            [
                'badge' => 'Grades 3–5',
                'title' => 'Preparatory Stage · Grades 3–5',
                'keywords' => 'Understand • Apply • Collaborate',
                'bullets' => "Interdisciplinary, application-led learning\n• Science, coding and computational thinking\n• Communication, financial awareness and creativity",
                'outcome' => 'Conceptual understanding, research and digital fluency'
            ],
            [
                'badge' => 'Grades 6–8',
                'title' => 'Middle School · Grades 6–8',
                'keywords' => 'Think • Apply • Innovate',
                'bullets' => "Deeper analysis, research and discussion\n• Coding & AI awareness, entrepreneurship, design thinking\n• Leadership, global citizenship and career exploration",
                'outcome' => 'Critical thinking, independence and real-world readiness'
            ]
        ],
        'footer_tagline' => 'Strong Foundations. Future Skills. Learning Without Boundaries.',
        'cta_text' => 'Explore the Full Curriculum →',
        'cta_url' => '/curriculum'
    ];
}

// 5. The Zuvio Learning Framework
if (!isset($home_cms['learning_framework'])) {
    $home_cms['learning_framework'] = [
        'is_active' => 1,
        'badge' => 'Core Methodology',
        'title' => 'The Zuvio Learning Framework',
        'subtitle' => 'From Knowing to Doing',
        'steps' => [
            ['badge' => 'Step 01', 'title' => 'Know', 'subtitle' => 'Foundation', 'desc' => 'Build the foundation of essential concepts and ideas'],
            ['badge' => 'Step 02', 'title' => 'Think', 'subtitle' => 'Analysis & Reason', 'desc' => 'Question, analyse, reason and solve'],
            ['badge' => 'Step 03', 'title' => 'Create', 'subtitle' => 'Design & Innovation', 'desc' => 'Imagine, experiment, design and innovate'],
            ['badge' => 'Step 04', 'title' => 'Connect', 'subtitle' => 'Collaboration', 'desc' => 'Communicate, collaborate and understand other perspectives'],
            ['badge' => 'Step 05', 'title' => 'Apply', 'subtitle' => 'Real-World Doing', 'desc' => 'Use knowledge confidently in projects and real life']
        ],
        'ribbon_text' => 'Knowledge → Understanding → Application → Innovation'
    ];
}

// 6. Learning Beyond the Textbook
if (!isset($home_cms['beyond_textbook'])) {
    $home_cms['beyond_textbook'] = [
        'is_active' => 1,
        'badge' => 'Real-World Classrooms',
        'title' => 'Learning Beyond the Textbook',
        'subtitle' => 'Because the world is the real classroom.',
        'items' => [
            ['title' => 'Projects & Experiments', 'subtitle' => 'Hands-On Discovery', 'desc' => 'learning by doing, testing and discovering'],
            ['title' => 'Technology & Digital Learning', 'subtitle' => 'Future-Ready', 'desc' => 'used creatively and responsibly'],
            ['title' => 'Communication & Collaboration', 'subtitle' => 'Global Teamwork', 'desc' => 'presentations, teamwork, global interaction'],
            ['title' => 'Life Skills', 'subtitle' => 'Real-World Readiness', 'desc' => 'decision-making, independence and financial awareness'],
            ['title' => 'Creativity & Innovation', 'subtitle' => 'Art & Coding', 'desc' => 'art, coding and design thinking'],
            ['title' => 'Global Exposure', 'subtitle' => 'Beyond Boundaries', 'desc' => 'cultures and ideas beyond boundaries']
        ]
    ];
}

// 7. What Makes Zuvio Different & Graduate Profile
if (!isset($home_cms['why_different'])) {
    $home_cms['why_different'] = [
        'is_active' => 1,
        'badge' => 'The Zuvio Edge',
        'title' => 'What Makes Zuvio Different',
        'pillars' => [
            ['title' => 'Assessment for Growth', 'desc' => 'We measure progress and skills, not just marks — providing regular, meaningful qualitative insights and developmental analytics for parents.'],
            ['title' => 'Personalised Learning', 'desc' => 'Live teacher guidance, adaptive digital tools and targeted academic support that continuously adapt to each child’s pace and individual learning needs.'],
            ['title' => 'Zuvio Beyond', 'desc' => 'Rich co-curricular clubs, AI/coding modules, sports association, enrichment electives, and extra academic support matched to your child’s passions.']
        ],
        'graduate_badge' => 'Graduate Profile',
        'graduate_title' => 'The Zuvio Graduate — by the end of Grade 8',
        'graduate_pills' => 'Academically Strong, Curious, Confident & Articulate, Creative, Digitally Fluent, Collaborative, Independent, Globally Aware, Future-Ready',
        'graduate_quote' => 'Not just ready for the next grade — ready to learn, adapt and grow in a changing world.'
    ];
}

// 8. Inclusivity & Beyond (Co-curricular)
if (!isset($home_cms['inclusivity'])) {
    $home_cms['inclusivity'] = [
        'is_active' => 1,
        'badge' => 'Equal Opportunities For Every Child',
        'title' => 'Inclusivity & Beyond',
        'subtitle' => 'Every Child. Every Mind. Every Possibility.',
        'items' => [
            ['title' => 'Inclusive by Design', 'desc' => 'Learning built around how your child learns — not the other way round.'],
            ['title' => 'Learn From Anywhere', 'desc' => 'A complete school experience that moves with your family, across cities or countries.'],
            ['title' => 'Personalised Attention', 'desc' => 'Teacher-led, small-group classes where every child is known, seen and supported.'],
            ['title' => 'Flexible Pacing', 'desc' => 'Space to move ahead, slow down or revisit — without the pressure to keep up.'],
            ['title' => 'Beyond Academics', 'desc' => 'Confidence, communication, creativity and life skills — not just examination marks.'],
            ['title' => 'Special Educator Support', 'desc' => 'A qualified Special Educator and personalised plans for diverse learning needs.']
        ],
        'cocurricular_title' => 'Zuvio Beyond Co-Curricular Programmes',
        'cocurricular_subtitle' => 'Empowering skills in tech, innovation, logic, performing arts, and financial literacy.',
        'cocurricular_cta' => 'View All Beyond Programmes →',
        'cocurricular_url' => '/beyond'
    ];
}

// 9. Statistics & Benchmarks
if (!isset($home_cms['statistics'])) {
    $home_cms['statistics'] = [
        'is_active' => 1,
        'stats' => [
            ['val' => 'KG to 8th', 'label' => 'Grade Spectrum'],
            ['val' => '15:1', 'label' => 'Max Cohort Ratio'],
            ['val' => '100%', 'label' => 'Live Online Schooling'],
            ['val' => 'CBSE', 'label' => '& NEP 2020 Aligned'],
            ['val' => '6', 'label' => 'Beyond Textbook Domains']
        ]
    ];
}

// 10. Affiliations & Accreditations (Homepage Preview)
if (!isset($home_cms['accreditations_preview'])) {
    $home_cms['accreditations_preview'] = [
        'is_active' => 1,
        'badge' => 'Global Quality Partnerships',
        'title' => 'Affiliations & Accreditations',
        'subtitle' => 'Recognised and benchmarked globally for quality assurance and sports excellence.'
    ];
}

// 11. Parent Testimonials (Homepage Preview)
if (!isset($home_cms['testimonials_preview'])) {
    $home_cms['testimonials_preview'] = [
        'is_active' => 1,
        'badge' => 'Parent Perspectives',
        'title' => 'Parent Testimonials & Reviews',
        'subtitle' => 'Hear directly from families flourishing in our online learning community.'
    ];
}

// 12. News & Recognition Blogs (Homepage Preview)
if (!isset($home_cms['news_blogs_preview'])) {
    $home_cms['news_blogs_preview'] = [
        'is_active' => 1,
        'badge' => 'Stay Informed',
        'title' => 'News, Updates & Recognition'
    ];
}

// 13. Featured In Publications
if (!isset($home_cms['featured_in'])) {
    $home_cms['featured_in'] = [
        'is_active' => 1,
        'badge' => 'Media Recognition',
        'title' => 'Featured In',
        'subtitle' => 'Zuvio Global School highlighted in leading educational publications for pioneering future-skills homeschooling.',
        'publications' => 'Education World, EdTech Review, The Hindu Education, Brainfeed Magazine, Indian Express, Hindustan Times'
    ];
}

// 14. Founder's Message
if (!isset($home_cms['founder_message'])) {
    $home_cms['founder_message'] = [
        'is_active' => 1,
        'portrait' => '/assets/images/Profile_Images/Pragya_Professional_Profile.webp',
        'author_name' => 'Founder',
        'author_title' => 'Zuvio Global School',
        'badge' => "Founder's Message",
        'heading' => 'Learning Without Boundaries. Growing With Purpose.',
        'salutation' => 'Dear Parents, Students and Members of the Zuvio Community,',
        'p1' => 'Education today must prepare children not only for examinations, but for a world that is constantly evolving.',
        'p2' => 'At Zuvio Global School, we believe that meaningful learning is not defined by the walls of a classroom. It is defined by curiosity, connection, opportunity and the confidence to explore beyond what is already known.',
        'p3' => 'Our vision is to create a 100% online, future-ready learning environment where every child has the opportunity to learn beyond geographical boundaries while receiving the guidance, structure and personal attention needed to thrive.',
        'p4' => 'Our aspiration is simple yet powerful: to nurture confident learners, independent thinkers, compassionate individuals and responsible global citizens who are prepared not just for the next grade, but for the world ahead.',
        'cta_text' => 'Read Full Message →',
        'cta_url' => '/founder-message'
    ];
}

// 15. Parent FAQs (Homepage Preview)
if (!isset($home_cms['faq_preview'])) {
    $home_cms['faq_preview'] = [
        'is_active' => 1,
        'badge' => 'Got Questions?',
        'title' => 'Parent FAQ — Online Schooling',
        'subtitle' => 'A quick guide for parents to understand Zuvio’s online schooling model, curriculum, assessments, communication and student support.'
    ];
}

// 16. Final Conversion CTA
if (!isset($home_cms['final_cta'])) {
    $home_cms['final_cta'] = [
        'is_active' => 1,
        'badge' => 'Start Your Journey',
        'title' => 'Ready to Experience Zuvio?',
        'subtitle' => 'Connect with our academic team today to discuss an age-appropriate learning timeline and personalized curriculum roadmap for your child.',
        'primary_btn_text' => 'Begin Your Journey',
        'primary_btn_url' => '/admissions#enrol',
        'secondary_btn_text' => 'Book a Free Demo',
        'secondary_btn_action' => 'javascript:openCallbackModal()'
    ];
}

// =============================================================================
// POST HANDLER: SAVE SECTION
// =============================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_section'])) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security validation failed. Please refresh and try again.';
    } else {
        $s_key = trim($_POST['section_key'] ?? '');
        $pending_action = trim($_POST['pending_action'] ?? 'save');
        if (isset($home_cms[$s_key])) {
            if ($pending_action === 'remove') {
                $home_cms[$s_key]['is_removed'] = 1;
                $home_cms[$s_key]['is_active'] = 0;
            } elseif ($pending_action === 'restore') {
                $home_cms[$s_key]['is_removed'] = 0;
                $home_cms[$s_key]['is_active'] = 1;
            } else {
                $is_active = isset($_POST['is_active']) ? 1 : 0;
                $home_cms[$s_key]['is_active'] = $is_active;
                $home_cms[$s_key]['is_removed'] = 0;
            }

            // Process based on section key
            if ($s_key === 'hero_form') {
                $home_cms['hero_form']['badge'] = trim($_POST['badge'] ?? '');
                $home_cms['hero_form']['title'] = trim($_POST['title'] ?? '');
                $home_cms['hero_form']['subtitle'] = trim($_POST['subtitle'] ?? '');
                $home_cms['hero_form']['btn_text'] = trim($_POST['btn_text'] ?? '');
                $home_cms['hero_form']['whatsapp_number'] = trim($_POST['whatsapp_number'] ?? '');
                $home_cms['hero_form']['consent_note'] = trim($_POST['consent_note'] ?? '');
            } elseif ($s_key === 'about_zuvio') {
                $home_cms['about_zuvio']['badge'] = trim($_POST['badge'] ?? '');
                $home_cms['about_zuvio']['title'] = trim($_POST['title'] ?? '');
                $home_cms['about_zuvio']['lead_text'] = trim($_POST['lead_text'] ?? '');
                $home_cms['about_zuvio']['body_text'] = trim($_POST['body_text'] ?? '');
                $home_cms['about_zuvio']['highlight_text'] = trim($_POST['highlight_text'] ?? '');
                $home_cms['about_zuvio']['cta_text'] = trim($_POST['cta_text'] ?? '');
                $home_cms['about_zuvio']['cta_url'] = trim($_POST['cta_url'] ?? '');
                $home_cms['about_zuvio']['image'] = trim($_POST['image'] ?? '');
                $home_cms['about_zuvio']['image_title'] = trim($_POST['image_title'] ?? '');
                $home_cms['about_zuvio']['image_subtitle'] = trim($_POST['image_subtitle'] ?? '');
            } elseif ($s_key === 'who_should_choose') {
                $home_cms['who_should_choose']['badge'] = trim($_POST['badge'] ?? '');
                $home_cms['who_should_choose']['title'] = trim($_POST['title'] ?? '');
                $home_cms['who_should_choose']['subtitle'] = trim($_POST['subtitle'] ?? '');
                $home_cms['who_should_choose']['footer_note'] = trim($_POST['footer_note'] ?? '');
                $home_cms['who_should_choose']['cta_text'] = trim($_POST['cta_text'] ?? '');
                $home_cms['who_should_choose']['cta_url'] = trim($_POST['cta_url'] ?? '');
                if (isset($_POST['cards']) && is_array($_POST['cards'])) {
                    $new_cards = [];
                    foreach ($_POST['cards'] as $c) {
                        if (!empty($c['title'])) {
                            $new_cards[] = [
                                'title' => trim($c['title']),
                                'desc' => trim($c['desc'] ?? '')
                            ];
                        }
                    }
                    if (!empty($new_cards)) {
                        $home_cms['who_should_choose']['cards'] = $new_cards;
                    }
                }
            } elseif ($s_key === 'curriculum_pathways') {
                $home_cms['curriculum_pathways']['badge'] = trim($_POST['badge'] ?? '');
                $home_cms['curriculum_pathways']['title'] = trim($_POST['title'] ?? '');
                $home_cms['curriculum_pathways']['subtitle'] = trim($_POST['subtitle'] ?? '');
                $home_cms['curriculum_pathways']['footer_tagline'] = trim($_POST['footer_tagline'] ?? '');
                $home_cms['curriculum_pathways']['cta_text'] = trim($_POST['cta_text'] ?? '');
                $home_cms['curriculum_pathways']['cta_url'] = trim($_POST['cta_url'] ?? '');
                if (isset($_POST['stages']) && is_array($_POST['stages'])) {
                    $new_stages = [];
                    foreach ($_POST['stages'] as $stg) {
                        if (!empty($stg['title'])) {
                            $new_stages[] = [
                                'badge' => trim($stg['badge'] ?? ''),
                                'title' => trim($stg['title']),
                                'keywords' => trim($stg['keywords'] ?? ''),
                                'bullets' => trim($stg['bullets'] ?? ''),
                                'outcome' => trim($stg['outcome'] ?? '')
                            ];
                        }
                    }
                    if (!empty($new_stages)) {
                        $home_cms['curriculum_pathways']['stages'] = $new_stages;
                    }
                }
            } elseif ($s_key === 'learning_framework') {
                $home_cms['learning_framework']['badge'] = trim($_POST['badge'] ?? '');
                $home_cms['learning_framework']['title'] = trim($_POST['title'] ?? '');
                $home_cms['learning_framework']['subtitle'] = trim($_POST['subtitle'] ?? '');
                $home_cms['learning_framework']['ribbon_text'] = trim($_POST['ribbon_text'] ?? '');
                if (isset($_POST['steps']) && is_array($_POST['steps'])) {
                    $new_steps = [];
                    foreach ($_POST['steps'] as $sp) {
                        if (!empty($sp['title'])) {
                            $new_steps[] = [
                                'badge' => trim($sp['badge'] ?? ''),
                                'title' => trim($sp['title']),
                                'subtitle' => trim($sp['subtitle'] ?? ''),
                                'desc' => trim($sp['desc'] ?? '')
                            ];
                        }
                    }
                    if (!empty($new_steps)) {
                        $home_cms['learning_framework']['steps'] = $new_steps;
                    }
                }
            } elseif ($s_key === 'beyond_textbook') {
                $home_cms['beyond_textbook']['badge'] = trim($_POST['badge'] ?? '');
                $home_cms['beyond_textbook']['title'] = trim($_POST['title'] ?? '');
                $home_cms['beyond_textbook']['subtitle'] = trim($_POST['subtitle'] ?? '');
                if (isset($_POST['items']) && is_array($_POST['items'])) {
                    $new_items = [];
                    foreach ($_POST['items'] as $it) {
                        if (!empty($it['title'])) {
                            $new_items[] = [
                                'title' => trim($it['title']),
                                'subtitle' => trim($it['subtitle'] ?? ''),
                                'desc' => trim($it['desc'] ?? '')
                            ];
                        }
                    }
                    if (!empty($new_items)) {
                        $home_cms['beyond_textbook']['items'] = $new_items;
                    }
                }
            } elseif ($s_key === 'why_different') {
                $home_cms['why_different']['badge'] = trim($_POST['badge'] ?? '');
                $home_cms['why_different']['title'] = trim($_POST['title'] ?? '');
                $home_cms['why_different']['graduate_badge'] = trim($_POST['graduate_badge'] ?? '');
                $home_cms['why_different']['graduate_title'] = trim($_POST['graduate_title'] ?? '');
                $home_cms['why_different']['graduate_pills'] = trim($_POST['graduate_pills'] ?? '');
                $home_cms['why_different']['graduate_quote'] = trim($_POST['graduate_quote'] ?? '');
                if (isset($_POST['pillars']) && is_array($_POST['pillars'])) {
                    $new_pillars = [];
                    foreach ($_POST['pillars'] as $p) {
                        if (!empty($p['title'])) {
                            $new_pillars[] = [
                                'title' => trim($p['title']),
                                'desc' => trim($p['desc'] ?? '')
                            ];
                        }
                    }
                    if (!empty($new_pillars)) {
                        $home_cms['why_different']['pillars'] = $new_pillars;
                    }
                }
            } elseif ($s_key === 'inclusivity') {
                $home_cms['inclusivity']['badge'] = trim($_POST['badge'] ?? '');
                $home_cms['inclusivity']['title'] = trim($_POST['title'] ?? '');
                $home_cms['inclusivity']['subtitle'] = trim($_POST['subtitle'] ?? '');
                $home_cms['inclusivity']['cocurricular_title'] = trim($_POST['cocurricular_title'] ?? '');
                $home_cms['inclusivity']['cocurricular_subtitle'] = trim($_POST['cocurricular_subtitle'] ?? '');
                $home_cms['inclusivity']['cocurricular_cta'] = trim($_POST['cocurricular_cta'] ?? '');
                $home_cms['inclusivity']['cocurricular_url'] = trim($_POST['cocurricular_url'] ?? '');
                if (isset($_POST['items']) && is_array($_POST['items'])) {
                    $new_inc = [];
                    foreach ($_POST['items'] as $ii) {
                        if (!empty($ii['title'])) {
                            $new_inc[] = [
                                'title' => trim($ii['title']),
                                'desc' => trim($ii['desc'] ?? '')
                            ];
                        }
                    }
                    if (!empty($new_inc)) {
                        $home_cms['inclusivity']['items'] = $new_inc;
                    }
                }
            } elseif ($s_key === 'statistics') {
                if (isset($_POST['stats']) && is_array($_POST['stats'])) {
                    $new_stats = [];
                    foreach ($_POST['stats'] as $st) {
                        if (!empty($st['val'])) {
                            $new_stats[] = [
                                'val' => trim($st['val']),
                                'label' => trim($st['label'] ?? '')
                            ];
                        }
                    }
                    if (!empty($new_stats)) {
                        $home_cms['statistics']['stats'] = $new_stats;
                    }
                }
            } elseif ($s_key === 'accreditations_preview' || $s_key === 'testimonials_preview' || $s_key === 'news_blogs_preview' || $s_key === 'faq_preview') {
                $home_cms[$s_key]['badge'] = trim($_POST['badge'] ?? '');
                $home_cms[$s_key]['title'] = trim($_POST['title'] ?? '');
                if (isset($_POST['subtitle'])) {
                    $home_cms[$s_key]['subtitle'] = trim($_POST['subtitle'] ?? '');
                }
            } elseif ($s_key === 'featured_in') {
                $home_cms['featured_in']['badge'] = trim($_POST['badge'] ?? '');
                $home_cms['featured_in']['title'] = trim($_POST['title'] ?? '');
                $home_cms['featured_in']['subtitle'] = trim($_POST['subtitle'] ?? '');
                $home_cms['featured_in']['publications'] = trim($_POST['publications'] ?? '');
            } elseif ($s_key === 'founder_message') {
                $home_cms['founder_message']['badge'] = trim($_POST['badge'] ?? '');
                $home_cms['founder_message']['heading'] = trim($_POST['heading'] ?? '');
                $home_cms['founder_message']['author_name'] = trim($_POST['author_name'] ?? 'Founder');
                $home_cms['founder_message']['author_title'] = trim($_POST['author_title'] ?? 'Zuvio Global School');
                $home_cms['founder_message']['portrait'] = trim($_POST['portrait'] ?? '');
                $home_cms['founder_message']['salutation'] = trim($_POST['salutation'] ?? '');
                $home_cms['founder_message']['p1'] = trim($_POST['p1'] ?? '');
                $home_cms['founder_message']['p2'] = trim($_POST['p2'] ?? '');
                $home_cms['founder_message']['p3'] = trim($_POST['p3'] ?? '');
                $home_cms['founder_message']['p4'] = trim($_POST['p4'] ?? '');
                $home_cms['founder_message']['cta_text'] = trim($_POST['cta_text'] ?? '');
                $home_cms['founder_message']['cta_url'] = trim($_POST['cta_url'] ?? '');
            } elseif ($s_key === 'final_cta') {
                $home_cms['final_cta']['badge'] = trim($_POST['badge'] ?? '');
                $home_cms['final_cta']['title'] = trim($_POST['title'] ?? '');
                $home_cms['final_cta']['subtitle'] = trim($_POST['subtitle'] ?? '');
                $home_cms['final_cta']['primary_btn_text'] = trim($_POST['primary_btn_text'] ?? '');
                $home_cms['final_cta']['primary_btn_url'] = trim($_POST['primary_btn_url'] ?? '');
                $home_cms['final_cta']['secondary_btn_text'] = trim($_POST['secondary_btn_text'] ?? '');
                $home_cms['final_cta']['secondary_btn_action'] = trim($_POST['secondary_btn_action'] ?? '');
            }

            // Persist JSON setting into database and session
            set_json_setting('cms_homepage', $home_cms, 'Master Homepage Sections CMS');
            
            // Also update MySQL table homepage_sections if active
            if ($db) {
                try {
                    $sec_title = $home_cms[$s_key]['title'] ?? ($home_cms[$s_key]['heading'] ?? '');
                    $sec_sub = $home_cms[$s_key]['subtitle'] ?? ($home_cms[$s_key]['badge'] ?? '');
                    $up_st = $db->prepare("
                        INSERT INTO `homepage_sections` (`section_key`, `title`, `subtitle`, `is_active`)
                        VALUES (?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `subtitle` = VALUES(`subtitle`), `is_active` = VALUES(`is_active`)
                    ");
                    $up_st->execute([$s_key, $sec_title, $sec_sub, $is_active]);
                } catch (Exception $e) {}
            }

            $redirect_msg = ($pending_action === 'remove') ? 'removed' : (($pending_action === 'restore') ? 'restored' : 'saved');
            header("Location: /admin/homepage.php?tab=" . urlencode($s_key) . "&msg=" . $redirect_msg);
            exit;
        }
    }
}

// All 16 Sections Ordered Exactly as on Homepage
$sections_nav = [
    'hero_form' => ['num' => 1, 'name' => 'Hero Counselor Form', 'icon' => '📝'],
    'about_zuvio' => ['num' => 2, 'name' => 'About Zuvio Story', 'icon' => '📖'],
    'who_should_choose' => ['num' => 3, 'name' => 'Who Should Choose Zuvio', 'icon' => '🎯'],
    'curriculum_pathways' => ['num' => 4, 'name' => 'Curriculum Pathways (4 Stages)', 'icon' => '🎓'],
    'learning_framework' => ['num' => 5, 'name' => 'Learning Framework (5 Steps)', 'icon' => '⚙️'],
    'beyond_textbook' => ['num' => 6, 'name' => 'Beyond Textbook (6 Domains)', 'icon' => '🌍'],
    'why_different' => ['num' => 7, 'name' => 'What Makes Us Different', 'icon' => '✨'],
    'inclusivity' => ['num' => 8, 'name' => 'Inclusivity & Co-Curricular', 'icon' => '🤝'],
    'statistics' => ['num' => 9, 'name' => 'Statistics & Benchmarks', 'icon' => '📊'],
    'accreditations_preview' => ['num' => 10, 'name' => 'Affiliations & Accreditations', 'icon' => '🏆'],
    'testimonials_preview' => ['num' => 11, 'name' => 'Parent Testimonials', 'icon' => '💬'],
    'news_blogs_preview' => ['num' => 12, 'name' => 'News, Updates & Blogs', 'icon' => '📰'],
    'featured_in' => ['num' => 13, 'name' => 'Featured In Media', 'icon' => '📺'],
    'founder_message' => ['num' => 14, 'name' => "Founder's Message", 'icon' => '🖋️'],
    'faq_preview' => ['num' => 15, 'name' => 'Parent FAQs Preview', 'icon' => '❓'],
    'final_cta' => ['num' => 16, 'name' => 'Final Conversion CTA', 'icon' => '🚀'],
];

if (!isset($sections_nav[$tab])) {
    $tab = 'hero_form';
}
$current_sec = $home_cms[$tab] ?? [];

$page_slug = 'admin-homepage';
include dirname(__FILE__) . '/header.php';
?>

<div style="max-width: 1200px; margin: 0 auto;">

  <!-- Page Header -->
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
      <h1 style="font-family: var(--font-secondary); font-size: 1.6rem; color: var(--color-navy); margin: 0 0 0.25rem 0;">
        Homepage Sections CMS
      </h1>
      <p style="color: var(--color-muted); font-size: 0.88rem; margin: 0;">
        Manage all 16 sections of the live homepage in the exact visual sequence they appear. Enable, disable, edit, and save each section independently.
      </p>
    </div>

    <div style="display: flex; gap: 0.75rem;">
      <a href="/admin/hero.php" class="btn btn-outline" style="font-size: 0.82rem; padding: 0.5rem 1rem; border-color: var(--color-navy); color: var(--color-navy);">
        🖼️ Manage Hero Banners
      </a>
      <a href="/" target="_blank" class="btn btn-outline" style="font-size: 0.82rem; padding: 0.5rem 1rem;">
        View Live Homepage ↗
      </a>
    </div>
  </div>

  <!-- Success / Error / Remove Notices -->
  <?php if ($msg === 'saved'): ?>
    <div style="background-color: #ECFDF5; border-left: 4px solid #10B981; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #065F46; font-size: 0.88rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
      <span><strong>Saved!</strong> Section <strong>"<?php echo h($sections_nav[$tab]['name']); ?>"</strong> has been successfully updated and synced to the website.</span>
      <span style="font-size: 0.75rem; color: #047857;"><?php echo date('h:i:s A'); ?></span>
    </div>
  <?php elseif ($msg === 'removed'): ?>
    <div style="background-color: #FEF2F2; border-left: 4px solid #EF4444; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #991B1B; font-size: 0.88rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
      <span><strong>Section Removed:</strong> <strong>"<?php echo h($sections_nav[$tab]['name']); ?>"</strong> has been removed from the live website. Click "Restore Section" anytime to bring it back.</span>
      <span style="font-size: 0.75rem; color: #DC2626;"><?php echo date('h:i:s A'); ?></span>
    </div>
  <?php elseif ($msg === 'restored'): ?>
    <div style="background-color: #ECFDF5; border-left: 4px solid #10B981; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #065F46; font-size: 0.88rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
      <span><strong>Section Restored:</strong> <strong>"<?php echo h($sections_nav[$tab]['name']); ?>"</strong> has been restored and made available on the live website.</span>
      <span style="font-size: 0.75rem; color: #047857;"><?php echo date('h:i:s A'); ?></span>
    </div>
  <?php endif; ?>

  <?php if ($error): ?>
    <div style="background-color: #FEF2F2; border-left: 4px solid #EF4444; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #991B1B; font-size: 0.88rem; margin-bottom: 1.5rem;">
      <strong>Error:</strong> <?php echo h($error); ?>
    </div>
  <?php endif; ?>

  <!-- 2-Column Layout: Sections Navigation on Left + Active Section Editor on Right -->
  <div style="display: grid; grid-template-columns: 300px 1fr; gap: 2rem; align-items: flex-start;">
    
    <!-- LEFT SIDEBAR: 16 ORDERED SECTIONS -->
    <div class="card" style="padding: 1rem; border: 1.5px solid rgba(6, 43, 99, 0.12); background: #FFFFFF; border-radius: var(--radius-md); position: sticky; top: 1.5rem;">
      <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--color-gold); letter-spacing: 1px; padding: 0.5rem 0.75rem 0.75rem 0.75rem; border-bottom: 1px solid var(--color-border); margin-bottom: 0.5rem;">
        Homepage Sequence (1–16)
      </div>

      <div style="display: flex; flex-direction: column; gap: 0.25rem; max-height: calc(100vh - 180px); overflow-y: auto;">
        <?php foreach ($sections_nav as $s_k => $s_meta): 
          $is_current = ($tab === $s_k);
          $s_removed = !empty($home_cms[$s_k]['is_removed']);
          $s_active = (!isset($home_cms[$s_k]['is_active']) || !empty($home_cms[$s_k]['is_active'])) && !$s_removed;
        ?>
          <a href="/admin/homepage.php?tab=<?php echo urlencode($s_k); ?>" style="display: flex; align-items: center; justify-content: space-between; padding: 0.65rem 0.85rem; border-radius: 6px; text-decoration: none; font-size: 0.82rem; transition: all 0.15s ease; <?php echo $is_current ? 'background: var(--color-navy); color: #FFFFFF; font-weight: 600;' : 'color: var(--color-text); background: transparent;'; ?>">
            <div style="display: flex; align-items: center; gap: 0.5rem; min-width: 0;">
              <span style="font-size: 0.75rem; opacity: 0.8;"><?php echo $s_meta['num']; ?>.</span>
              <span style="font-size: 0.95rem;"><?php echo $s_meta['icon']; ?></span>
              <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo h($s_meta['name']); ?></span>
            </div>
            <?php if ($s_removed): ?>
              <span style="font-size: 0.65rem; border-radius: 8px; padding: 1px 6px; background: #FEE2E2; color: #DC2626; font-weight: 700;">REMOVED</span>
            <?php elseif ($s_active): ?>
              <span style="font-size: 0.65rem; border-radius: 8px; padding: 1px 6px; <?php echo $is_current ? 'background: #10B981; color:#fff;' : 'background: #DEF7EC; color: #03543F;'; ?>">ON</span>
            <?php else: ?>
              <span style="font-size: 0.65rem; border-radius: 8px; padding: 1px 6px; background: #F1F5F9; color: #94A3B8;">OFF</span>
            <?php endif; ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- RIGHT MAIN: SECTION EDITOR WITH INDIVIDUAL SAVE BUTTON -->
    <div class="card" style="padding: 2.25rem; border: 1.5px solid rgba(6, 43, 99, 0.12); background: #FFFFFF; border-radius: var(--radius-md);">
      
      <form method="POST" action="/admin/homepage.php" enctype="multipart/form-data" id="homepageSectionForm">
        <input type="hidden" name="section_key" value="<?php echo h($tab); ?>">
        <input type="hidden" name="save_section" value="1">
        <input type="hidden" name="pending_action" id="pendingActionInput" value="save">
        <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">

        <!-- Section Header with Active Toggle & Remove Action -->
        <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 1.25rem; border-bottom: 2px solid var(--color-border); margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
          <div>
            <span style="font-size: 0.75rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 1px;">
              Section <?php echo $sections_nav[$tab]['num']; ?> of 16
            </span>
            <h2 style="font-size: 1.4rem; color: var(--color-navy); margin: 0.25rem 0 0 0; font-family: var(--font-secondary);">
              <?php echo $sections_nav[$tab]['icon'] . ' ' . h($sections_nav[$tab]['name']); ?>
            </h2>
          </div>

          <!-- Controls: Visibility Toggle + Remove/Restore Button -->
          <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 0.5rem; background: #F8FAFC; border: 1px solid #E2E8F0; padding: 0.5rem 0.85rem; border-radius: var(--radius-sm);">
              <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); cursor: pointer; margin: 0;">
                <input type="checkbox" name="is_active" value="1" <?php echo ((!isset($current_sec['is_active']) || !empty($current_sec['is_active'])) && empty($current_sec['is_removed'])) ? 'checked' : ''; ?>>
                <span>Visible on Homepage</span>
              </label>
            </div>

            <?php if (!empty($current_sec['is_removed'])): ?>
              <button type="button" class="btn" style="background: #10B981; color: #FFFFFF; font-size: 0.82rem; padding: 0.5rem 0.95rem; font-weight: 600;" onclick="setSectionAction('restore')">
                ↩️ Restore Section
              </button>
            <?php else: ?>
              <button type="button" class="btn btn-outline" style="border-color: #EF4444; color: #EF4444; font-size: 0.82rem; padding: 0.5rem 0.95rem; font-weight: 600;" onclick="setSectionAction('remove')">
                🗑️ Remove Section
              </button>
            <?php endif; ?>
          </div>
        </div>

        <!-- Pending Removal Visual Notification Alert -->
        <div id="pendingRemovalAlert" style="display: none; background: #FEF2F2; border: 1.5px solid #EF4444; border-radius: var(--radius-sm); padding: 1rem 1.25rem; color: #991B1B; font-size: 0.88rem; margin-bottom: 1.75rem;">
          <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
            <span>
              <strong>⚠️ PENDING REMOVAL:</strong> This section is marked for removal from the live homepage. It is <strong>NOT yet removed</strong> until you click <strong>"Confirm Removal & Save"</strong> below.
            </span>
            <button type="button" class="btn btn-outline" style="font-size: 0.75rem; padding: 0.35rem 0.85rem; background: #FFFFFF; color: #991B1B; border-color: #EF4444;" onclick="cancelSectionAction()">
              Cancel Removal
            </button>
          </div>
        </div>

        <?php if (!empty($current_sec['is_removed'])): ?>
          <div style="background: #FFFBEB; border-left: 4px solid #F59E0B; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #92400E; font-size: 0.88rem; margin-bottom: 1.75rem;">
            <strong>Section Status:</strong> This section is currently <strong>REMOVED</strong> from the website. To display it again, click <strong>"Restore Section"</strong> above and then save.
          </div>
        <?php endif; ?>

        <!-- ===================================================================
             SPECIFIC SECTION FORM FIELDS
             =================================================================== -->

        <?php if ($tab === 'hero_form'): ?>
          <p style="color: var(--color-muted); font-size: 0.85rem; margin-bottom: 1.5rem;">
            Controls the enquiry form displayed next to the hero banner carousel on the homepage ("Talk to an Admission Counselor").
          </p>
          <div class="admin-form-group">
            <label class="admin-label">Form Tagline Badge</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? ''); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Form Heading</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? ''); ?>" class="admin-input" required>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Form Subtitle / Intro</label>
            <textarea name="subtitle" rows="2" class="admin-input"><?php echo h($current_sec['subtitle'] ?? ''); ?></textarea>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="admin-form-group">
              <label class="admin-label">Submit Button Label</label>
              <input type="text" name="btn_text" value="<?php echo h($current_sec['btn_text'] ?? ''); ?>" class="admin-input">
            </div>
            <div class="admin-form-group">
              <label class="admin-label">Counselor WhatsApp / Phone</label>
              <input type="text" name="whatsapp_number" value="<?php echo h($current_sec['whatsapp_number'] ?? ''); ?>" class="admin-input">
            </div>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Consent &amp; Privacy Disclaimer</label>
            <textarea name="consent_note" rows="2" class="admin-input"><?php echo h($current_sec['consent_note'] ?? ''); ?></textarea>
          </div>

        <?php elseif ($tab === 'about_zuvio'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Section Tagline Badge</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? ''); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Title</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? ''); ?>" class="admin-input" required>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Lead Philosophy Paragraph (Highlighted)</label>
            <textarea name="lead_text" rows="2" class="admin-input"><?php echo h($current_sec['lead_text'] ?? ''); ?></textarea>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Body Content</label>
            <textarea name="body_text" rows="4" class="admin-input"><?php echo h($current_sec['body_text'] ?? ''); ?></textarea>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Highlight Callout</label>
            <input type="text" name="highlight_text" value="<?php echo h($current_sec['highlight_text'] ?? ''); ?>" class="admin-input">
          </div>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="admin-form-group">
              <label class="admin-label">CTA Button Text</label>
              <input type="text" name="cta_text" value="<?php echo h($current_sec['cta_text'] ?? ''); ?>" class="admin-input">
            </div>
            <div class="admin-form-group">
              <label class="admin-label">CTA Button URL</label>
              <input type="text" name="cta_url" value="<?php echo h($current_sec['cta_url'] ?? ''); ?>" class="admin-input">
            </div>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; border-top: 1px solid #E2E8F0; padding-top: 1rem;">
            <div class="admin-form-group">
              <label class="admin-label">Graphic Image URL</label>
              <input type="text" name="image" value="<?php echo h($current_sec['image'] ?? ''); ?>" class="admin-input">
            </div>
            <div class="admin-form-group">
              <label class="admin-label">Graphic Overlay Heading</label>
              <input type="text" name="image_title" value="<?php echo h($current_sec['image_title'] ?? ''); ?>" class="admin-input">
            </div>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Graphic Overlay Subtitle</label>
            <input type="text" name="image_subtitle" value="<?php echo h($current_sec['image_subtitle'] ?? ''); ?>" class="admin-input">
          </div>

        <?php elseif ($tab === 'who_should_choose'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Section Tagline</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? ''); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Title</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? ''); ?>" class="admin-input" required>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Subtitle Description</label>
            <textarea name="subtitle" rows="2" class="admin-input"><?php echo h($current_sec['subtitle'] ?? ''); ?></textarea>
          </div>
          
          <h4 style="color: var(--color-navy); font-size: 1rem; margin: 1.5rem 0 1rem 0;">Audience Target Cards (6 Cards)</h4>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem;">
            <?php foreach (($current_sec['cards'] ?? []) as $c_idx => $card): ?>
              <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 1rem; border-radius: var(--radius-sm);">
                <label class="admin-label" style="font-size: 0.78rem;">Card #<?php echo $c_idx + 1; ?> Title</label>
                <input type="text" name="cards[<?php echo $c_idx; ?>][title]" value="<?php echo h($card['title']); ?>" class="admin-input" style="margin-bottom: 0.5rem;">
                <label class="admin-label" style="font-size: 0.78rem;">Description</label>
                <textarea name="cards[<?php echo $c_idx; ?>][desc]" rows="2" class="admin-input"><?php echo h($card['desc']); ?></textarea>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="admin-form-group">
            <label class="admin-label">Footer Note</label>
            <input type="text" name="footer_note" value="<?php echo h($current_sec['footer_note'] ?? ''); ?>" class="admin-input">
          </div>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="admin-form-group">
              <label class="admin-label">CTA Button Label</label>
              <input type="text" name="cta_text" value="<?php echo h($current_sec['cta_text'] ?? ''); ?>" class="admin-input">
            </div>
            <div class="admin-form-group">
              <label class="admin-label">CTA Button URL</label>
              <input type="text" name="cta_url" value="<?php echo h($current_sec['cta_url'] ?? ''); ?>" class="admin-input">
            </div>
          </div>

        <?php elseif ($tab === 'curriculum_pathways'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Section Tagline</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? ''); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Title</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? ''); ?>" class="admin-input" required>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Subtitle Description</label>
            <textarea name="subtitle" rows="2" class="admin-input"><?php echo h($current_sec['subtitle'] ?? ''); ?></textarea>
          </div>

          <h4 style="color: var(--color-navy); font-size: 1rem; margin: 1.5rem 0 1rem 0;">Curriculum Stages (4 Stages)</h4>
          <div style="display: flex; flex-direction: column; gap: 1.25rem; margin-bottom: 1.5rem;">
            <?php foreach (($current_sec['stages'] ?? []) as $st_idx => $stg): ?>
              <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 1.25rem; border-radius: var(--radius-sm);">
                <div style="display: grid; grid-template-columns: 120px 1fr 1fr; gap: 1rem; margin-bottom: 0.5rem;">
                  <div>
                    <label class="admin-label" style="font-size: 0.75rem;">Stage Badge</label>
                    <input type="text" name="stages[<?php echo $st_idx; ?>][badge]" value="<?php echo h($stg['badge']); ?>" class="admin-input">
                  </div>
                  <div>
                    <label class="admin-label" style="font-size: 0.75rem;">Stage Title</label>
                    <input type="text" name="stages[<?php echo $st_idx; ?>][title]" value="<?php echo h($stg['title']); ?>" class="admin-input">
                  </div>
                  <div>
                    <label class="admin-label" style="font-size: 0.75rem;">Keywords</label>
                    <input type="text" name="stages[<?php echo $st_idx; ?>][keywords]" value="<?php echo h($stg['keywords']); ?>" class="admin-input">
                  </div>
                </div>
                <div style="margin-bottom: 0.5rem;">
                  <label class="admin-label" style="font-size: 0.75rem;">Bullet Points (One per line)</label>
                  <textarea name="stages[<?php echo $st_idx; ?>][bullets]" rows="2" class="admin-input"><?php echo h($stg['bullets']); ?></textarea>
                </div>
                <div>
                  <label class="admin-label" style="font-size: 0.75rem;">Expected Outcome</label>
                  <input type="text" name="stages[<?php echo $st_idx; ?>][outcome]" value="<?php echo h($stg['outcome'] ?? ''); ?>" class="admin-input">
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="admin-form-group">
            <label class="admin-label">Bottom Tagline Callout</label>
            <input type="text" name="footer_tagline" value="<?php echo h($current_sec['footer_tagline'] ?? ''); ?>" class="admin-input">
          </div>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="admin-form-group">
              <label class="admin-label">CTA Button Label</label>
              <input type="text" name="cta_text" value="<?php echo h($current_sec['cta_text'] ?? ''); ?>" class="admin-input">
            </div>
            <div class="admin-form-group">
              <label class="admin-label">CTA Button URL</label>
              <input type="text" name="cta_url" value="<?php echo h($current_sec['cta_url'] ?? ''); ?>" class="admin-input">
            </div>
          </div>

        <?php elseif ($tab === 'learning_framework'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Section Tagline</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? ''); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Title</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? ''); ?>" class="admin-input" required>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Subtitle</label>
            <input type="text" name="subtitle" value="<?php echo h($current_sec['subtitle'] ?? ''); ?>" class="admin-input">
          </div>

          <h4 style="color: var(--color-navy); font-size: 1rem; margin: 1.5rem 0 1rem 0;">Framework Steps (5 Sequential Steps)</h4>
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
            <?php foreach (($current_sec['steps'] ?? []) as $sp_idx => $step): ?>
              <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 1rem; border-radius: var(--radius-sm);">
                <label class="admin-label" style="font-size: 0.75rem;">Step Badge</label>
                <input type="text" name="steps[<?php echo $sp_idx; ?>][badge]" value="<?php echo h($step['badge']); ?>" class="admin-input" style="margin-bottom: 0.4rem;">
                <label class="admin-label" style="font-size: 0.75rem;">Title</label>
                <input type="text" name="steps[<?php echo $sp_idx; ?>][title]" value="<?php echo h($step['title']); ?>" class="admin-input" style="margin-bottom: 0.4rem;">
                <label class="admin-label" style="font-size: 0.75rem;">Focus Subtitle</label>
                <input type="text" name="steps[<?php echo $sp_idx; ?>][subtitle]" value="<?php echo h($step['subtitle']); ?>" class="admin-input" style="margin-bottom: 0.4rem;">
                <label class="admin-label" style="font-size: 0.75rem;">Description</label>
                <textarea name="steps[<?php echo $sp_idx; ?>][desc]" rows="2" class="admin-input"><?php echo h($step['desc']); ?></textarea>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="admin-form-group">
            <label class="admin-label">Bottom Ribbon Summary Text</label>
            <input type="text" name="ribbon_text" value="<?php echo h($current_sec['ribbon_text'] ?? ''); ?>" class="admin-input">
          </div>

        <?php elseif ($tab === 'beyond_textbook'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Section Tagline</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? ''); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Title</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? ''); ?>" class="admin-input" required>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Subtitle Description</label>
            <input type="text" name="subtitle" value="<?php echo h($current_sec['subtitle'] ?? ''); ?>" class="admin-input">
          </div>

          <h4 style="color: var(--color-navy); font-size: 1rem; margin: 1.5rem 0 1rem 0;">Beyond Textbook Domains (6 Cards)</h4>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
            <?php foreach (($current_sec['items'] ?? []) as $it_idx => $item): ?>
              <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 1rem; border-radius: var(--radius-sm);">
                <label class="admin-label" style="font-size: 0.75rem;">Card Title</label>
                <input type="text" name="items[<?php echo $it_idx; ?>][title]" value="<?php echo h($item['title']); ?>" class="admin-input" style="margin-bottom: 0.4rem;">
                <label class="admin-label" style="font-size: 0.75rem;">Subtitle Tag</label>
                <input type="text" name="items[<?php echo $it_idx; ?>][subtitle]" value="<?php echo h($item['subtitle']); ?>" class="admin-input" style="margin-bottom: 0.4rem;">
                <label class="admin-label" style="font-size: 0.75rem;">Description</label>
                <textarea name="items[<?php echo $it_idx; ?>][desc]" rows="2" class="admin-input"><?php echo h($item['desc']); ?></textarea>
              </div>
            <?php endforeach; ?>
          </div>

        <?php elseif ($tab === 'why_different'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Section Tagline</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? ''); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Title</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? ''); ?>" class="admin-input" required>
          </div>

          <h4 style="color: var(--color-navy); font-size: 1rem; margin: 1.5rem 0 1rem 0;">The 3 Pillars of Difference</h4>
          <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem;">
            <?php foreach (($current_sec['pillars'] ?? []) as $p_idx => $pillar): ?>
              <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 1rem; border-radius: var(--radius-sm);">
                <label class="admin-label" style="font-size: 0.75rem;">Pillar #<?php echo $p_idx + 1; ?> Title</label>
                <input type="text" name="pillars[<?php echo $p_idx; ?>][title]" value="<?php echo h($pillar['title']); ?>" class="admin-input" style="margin-bottom: 0.4rem;">
                <label class="admin-label" style="font-size: 0.75rem;">Description</label>
                <textarea name="pillars[<?php echo $p_idx; ?>][desc]" rows="2" class="admin-input"><?php echo h($pillar['desc']); ?></textarea>
              </div>
            <?php endforeach; ?>
          </div>

          <h4 style="color: var(--color-navy); font-size: 1rem; margin: 1.5rem 0 1rem 0;">The Zuvio Graduate Profile Box</h4>
          <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 1.25rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 0.75rem;">
              <div>
                <label class="admin-label" style="font-size: 0.75rem;">Box Eyebrow Badge</label>
                <input type="text" name="graduate_badge" value="<?php echo h($current_sec['graduate_badge'] ?? ''); ?>" class="admin-input">
              </div>
              <div>
                <label class="admin-label" style="font-size: 0.75rem;">Box Title</label>
                <input type="text" name="graduate_title" value="<?php echo h($current_sec['graduate_title'] ?? ''); ?>" class="admin-input">
              </div>
            </div>
            <div class="admin-form-group">
              <label class="admin-label" style="font-size: 0.75rem;">Outcome Pills (Comma-separated)</label>
              <input type="text" name="graduate_pills" value="<?php echo h($current_sec['graduate_pills'] ?? ''); ?>" class="admin-input">
            </div>
            <div class="admin-form-group" style="margin: 0;">
              <label class="admin-label" style="font-size: 0.75rem;">Closing Quote</label>
              <input type="text" name="graduate_quote" value="<?php echo h($current_sec['graduate_quote'] ?? ''); ?>" class="admin-input">
            </div>
          </div>

        <?php elseif ($tab === 'inclusivity'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Section Tagline</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? ''); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Title</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? ''); ?>" class="admin-input" required>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Subtitle</label>
            <input type="text" name="subtitle" value="<?php echo h($current_sec['subtitle'] ?? ''); ?>" class="admin-input">
          </div>

          <h4 style="color: var(--color-navy); font-size: 1rem; margin: 1.5rem 0 1rem 0;">Inclusivity Feature Cards (6 Cards)</h4>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
            <?php foreach (($current_sec['items'] ?? []) as $in_idx => $in_item): ?>
              <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 1rem; border-radius: var(--radius-sm);">
                <label class="admin-label" style="font-size: 0.75rem;">Title</label>
                <input type="text" name="items[<?php echo $in_idx; ?>][title]" value="<?php echo h($in_item['title']); ?>" class="admin-input" style="margin-bottom: 0.4rem;">
                <label class="admin-label" style="font-size: 0.75rem;">Description</label>
                <textarea name="items[<?php echo $in_idx; ?>][desc]" rows="2" class="admin-input"><?php echo h($in_item['desc']); ?></textarea>
              </div>
            <?php endforeach; ?>
          </div>

          <h4 style="color: var(--color-navy); font-size: 1rem; margin: 1.5rem 0 1rem 0;">Co-Curricular Beyond Callout Banner</h4>
          <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 1.25rem; border-radius: var(--radius-sm);">
            <div class="admin-form-group">
              <label class="admin-label" style="font-size: 0.75rem;">Banner Title</label>
              <input type="text" name="cocurricular_title" value="<?php echo h($current_sec['cocurricular_title'] ?? ''); ?>" class="admin-input">
            </div>
            <div class="admin-form-group">
              <label class="admin-label" style="font-size: 0.75rem;">Banner Subtitle</label>
              <textarea name="cocurricular_subtitle" rows="2" class="admin-input"><?php echo h($current_sec['cocurricular_subtitle'] ?? ''); ?></textarea>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
              <div class="admin-form-group" style="margin: 0;">
                <label class="admin-label" style="font-size: 0.75rem;">CTA Button Label</label>
                <input type="text" name="cocurricular_cta" value="<?php echo h($current_sec['cocurricular_cta'] ?? ''); ?>" class="admin-input">
              </div>
              <div class="admin-form-group" style="margin: 0;">
                <label class="admin-label" style="font-size: 0.75rem;">CTA Button URL</label>
                <input type="text" name="cocurricular_url" value="<?php echo h($current_sec['cocurricular_url'] ?? ''); ?>" class="admin-input">
              </div>
            </div>
          </div>

        <?php elseif ($tab === 'statistics'): ?>
          <p style="color: var(--color-muted); font-size: 0.85rem; margin-bottom: 1.5rem;">
            Controls the 5 high-impact institutional metric stat boxes displayed on the navy gradient strip.
          </p>
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
            <?php foreach (($current_sec['stats'] ?? []) as $st_idx => $stat): ?>
              <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 1rem; border-radius: var(--radius-sm);">
                <label class="admin-label" style="font-size: 0.75rem;">Stat #<?php echo $st_idx + 1; ?> Value</label>
                <input type="text" name="stats[<?php echo $st_idx; ?>][val]" value="<?php echo h($stat['val']); ?>" class="admin-input" style="font-size: 1.1rem; font-weight: 700; color: var(--color-navy); margin-bottom: 0.5rem;">
                <label class="admin-label" style="font-size: 0.75rem;">Label / Description</label>
                <input type="text" name="stats[<?php echo $st_idx; ?>][label]" value="<?php echo h($stat['label']); ?>" class="admin-input">
              </div>
            <?php endforeach; ?>
          </div>

        <?php elseif ($tab === 'accreditations_preview'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Section Tagline</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? ''); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Title</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? ''); ?>" class="admin-input" required>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Subtitle Description</label>
            <textarea name="subtitle" rows="2" class="admin-input"><?php echo h($current_sec['subtitle'] ?? ''); ?></textarea>
          </div>
          <div style="background: #EFF6FF; border: 1px solid #BFDBFE; padding: 1.25rem; border-radius: var(--radius-sm); margin-top: 1.5rem;">
            <strong>Underlying Records Management:</strong>
            <p style="font-size: 0.85rem; color: #1E40AF; margin: 0.25rem 0 1rem 0;">
              The accreditation items (ISSO, IAO, Oxford Quality, certificates, etc.) are managed in the dedicated Accreditations Manager.
            </p>
            <a href="/admin/accreditations.php" class="btn btn-outline" style="border-color: #3B82F6; color: #1E40AF; font-size: 0.85rem;">
              Manage All Accreditation Records &rarr;
            </a>
          </div>

        <?php elseif ($tab === 'testimonials_preview'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Section Tagline</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? ''); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Title</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? ''); ?>" class="admin-input" required>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Subtitle Description</label>
            <textarea name="subtitle" rows="2" class="admin-input"><?php echo h($current_sec['subtitle'] ?? ''); ?></textarea>
          </div>
          <div style="background: #EFF6FF; border: 1px solid #BFDBFE; padding: 1.25rem; border-radius: var(--radius-sm); margin-top: 1.5rem;">
            <strong>Testimonials Management:</strong>
            <p style="font-size: 0.85rem; color: #1E40AF; margin: 0.25rem 0 1rem 0;">
              Add, edit, or approve parent reviews, ratings, and student details in the Testimonials Manager.
            </p>
            <a href="/admin/testimonials.php" class="btn btn-outline" style="border-color: #3B82F6; color: #1E40AF; font-size: 0.85rem;">
              Manage Parent Testimonials &rarr;
            </a>
          </div>

        <?php elseif ($tab === 'news_blogs_preview'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Section Tagline</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? ''); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Title</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? ''); ?>" class="admin-input" required>
          </div>
          <div style="background: #EFF6FF; border: 1px solid #BFDBFE; padding: 1.25rem; border-radius: var(--radius-sm); margin-top: 1.5rem;">
            <strong>Blog Articles &amp; Updates:</strong>
            <p style="font-size: 0.85rem; color: #1E40AF; margin: 0.25rem 0 1rem 0;">
              The homepage automatically shows the 3 most recently published blog posts. Write and publish new articles in the Blogs Manager.
            </p>
            <a href="/admin/blogs.php" class="btn btn-outline" style="border-color: #3B82F6; color: #1E40AF; font-size: 0.85rem;">
              Manage Blog Posts &amp; Articles &rarr;
            </a>
          </div>

        <?php elseif ($tab === 'featured_in'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Section Tagline</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? ''); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Title</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? ''); ?>" class="admin-input" required>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Subtitle Description</label>
            <textarea name="subtitle" rows="2" class="admin-input"><?php echo h($current_sec['subtitle'] ?? ''); ?></textarea>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Media Publications List (Comma-separated)</label>
            <input type="text" name="publications" value="<?php echo h($current_sec['publications'] ?? ''); ?>" class="admin-input">
          </div>

        <?php elseif ($tab === 'founder_message'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Section Tagline</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? ''); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Editorial Letter Headline</label>
            <input type="text" name="heading" value="<?php echo h($current_sec['heading'] ?? ''); ?>" class="admin-input" required>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
            <div class="admin-form-group">
              <label class="admin-label">Author Name</label>
              <input type="text" name="author_name" value="<?php echo h($current_sec['author_name'] ?? 'Founder'); ?>" class="admin-input">
            </div>
            <div class="admin-form-group">
              <label class="admin-label">Author Title</label>
              <input type="text" name="author_title" value="<?php echo h($current_sec['author_title'] ?? 'Zuvio Global School'); ?>" class="admin-input">
            </div>
            <div class="admin-form-group">
              <label class="admin-label">Portrait Image URL</label>
              <input type="text" name="portrait" value="<?php echo h($current_sec['portrait'] ?? ''); ?>" class="admin-input">
            </div>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Salutation</label>
            <input type="text" name="salutation" value="<?php echo h($current_sec['salutation'] ?? ''); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Paragraph 1</label>
            <textarea name="p1" rows="2" class="admin-input"><?php echo h($current_sec['p1'] ?? ''); ?></textarea>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Paragraph 2</label>
            <textarea name="p2" rows="2" class="admin-input"><?php echo h($current_sec['p2'] ?? ''); ?></textarea>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Paragraph 3</label>
            <textarea name="p3" rows="2" class="admin-input"><?php echo h($current_sec['p3'] ?? ''); ?></textarea>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Paragraph 4</label>
            <textarea name="p4" rows="2" class="admin-input"><?php echo h($current_sec['p4'] ?? ''); ?></textarea>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="admin-form-group">
              <label class="admin-label">CTA Link Label</label>
              <input type="text" name="cta_text" value="<?php echo h($current_sec['cta_text'] ?? ''); ?>" class="admin-input">
            </div>
            <div class="admin-form-group">
              <label class="admin-label">CTA Link URL</label>
              <input type="text" name="cta_url" value="<?php echo h($current_sec['cta_url'] ?? ''); ?>" class="admin-input">
            </div>
          </div>

        <?php elseif ($tab === 'faq_preview'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Section Tagline</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? ''); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Title</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? ''); ?>" class="admin-input" required>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Subtitle Description</label>
            <textarea name="subtitle" rows="2" class="admin-input"><?php echo h($current_sec['subtitle'] ?? ''); ?></textarea>
          </div>
          <div style="background: #EFF6FF; border: 1px solid #BFDBFE; padding: 1.25rem; border-radius: var(--radius-sm); margin-top: 1.5rem;">
            <strong>FAQ Database Management:</strong>
            <p style="font-size: 0.85rem; color: #1E40AF; margin: 0.25rem 0 1rem 0;">
              All 18 comprehensive questions and answers are managed in the Parent FAQs Manager.
            </p>
            <a href="/admin/faqs.php" class="btn btn-outline" style="border-color: #3B82F6; color: #1E40AF; font-size: 0.85rem;">
              Manage All 18 Parent FAQs &rarr;
            </a>
          </div>

        <?php elseif ($tab === 'final_cta'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Section Tagline</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? ''); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Heading</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? ''); ?>" class="admin-input" required>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Subtitle Text</label>
            <textarea name="subtitle" rows="3" class="admin-input"><?php echo h($current_sec['subtitle'] ?? ''); ?></textarea>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="admin-form-group">
              <label class="admin-label">Primary Button Text</label>
              <input type="text" name="primary_btn_text" value="<?php echo h($current_sec['primary_btn_text'] ?? ''); ?>" class="admin-input">
            </div>
            <div class="admin-form-group">
              <label class="admin-label">Primary Button URL</label>
              <input type="text" name="primary_btn_url" value="<?php echo h($current_sec['primary_btn_url'] ?? ''); ?>" class="admin-input">
            </div>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="admin-form-group">
              <label class="admin-label">Secondary Button Text</label>
              <input type="text" name="secondary_btn_text" value="<?php echo h($current_sec['secondary_btn_text'] ?? ''); ?>" class="admin-input">
            </div>
            <div class="admin-form-group">
              <label class="admin-label">Secondary Button Action / URL</label>
              <input type="text" name="secondary_btn_action" value="<?php echo h($current_sec['secondary_btn_action'] ?? ''); ?>" class="admin-input">
            </div>
          </div>

        <?php endif; ?>

        <!-- INDIVIDUAL SECTION SAVE BUTTON -->
        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 2px solid var(--color-border); padding-top: 1.5rem; margin-top: 2rem; flex-wrap: wrap; gap: 1rem;">
          <a href="/admin/homepage.php?tab=<?php echo urlencode($tab); ?>" class="btn btn-outline" style="font-size: 0.85rem; padding: 0.5rem 1.25rem;">
            Reset / Reload
          </a>
          <button type="submit" id="sectionSubmitBtn" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 2.25rem; font-size: 0.95rem; font-weight: 600;">
            💾 Save <?php echo h($sections_nav[$tab]['name']); ?>
          </button>
        </div>

      </form>

      <script>
      function setSectionAction(action) {
        const input = document.getElementById('pendingActionInput');
        const alertBox = document.getElementById('pendingRemovalAlert');
        const submitBtn = document.getElementById('sectionSubmitBtn');
        if (action === 'remove') {
          if (confirm('Are you sure you want to mark this section for removal? (Note: Section will NOT be removed from the live website until you click "Confirm Removal & Save")')) {
            if (input) input.value = 'remove';
            if (alertBox) alertBox.style.display = 'block';
            if (submitBtn) {
              submitBtn.innerText = '⚠️ Confirm Removal & Save';
              submitBtn.style.background = '#EF4444';
              submitBtn.style.borderColor = '#EF4444';
            }
            alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
          }
        } else if (action === 'restore') {
          if (input) input.value = 'restore';
          if (alertBox) alertBox.style.display = 'none';
          if (submitBtn) {
            submitBtn.innerText = '↩️ Confirm Restore & Save';
            submitBtn.style.background = '#10B981';
            submitBtn.style.borderColor = '#10B981';
          }
          submitBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      }

      function cancelSectionAction() {
        const input = document.getElementById('pendingActionInput');
        const alertBox = document.getElementById('pendingRemovalAlert');
        const submitBtn = document.getElementById('sectionSubmitBtn');
        if (input) input.value = 'save';
        if (alertBox) alertBox.style.display = 'none';
        if (submitBtn) {
          submitBtn.innerText = '💾 Save <?php echo h(addslashes($sections_nav[$tab]['name'])); ?>';
          submitBtn.style.background = 'var(--color-navy)';
          submitBtn.style.borderColor = 'var(--color-navy)';
        }
      }
      </script>

    </div>

  </div>

</div>

<?php
include dirname(__FILE__) . '/footer.php';
?>
