<?php
// Zuvio Global School - Parent FAQ Dedicated Page Template
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// Fetch FAQs from Database or Fallback to all 18 PDF questions
$faqs = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM `faqs` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
        $faqs = $stmt->fetchAll();
    } catch (Exception $e) {}
}

if (empty($faqs)) {
    $faqs = [
        ['id' => 1, 'category' => 'Schedules & Structure', 'question' => 'What are the timings of online classes?', 'answer' => "Zuvio follows age-appropriate class durations. Classes are scheduled within the 9:00 AM-1:00 PM window, depending on the grade.\n\nPre-Primary: approximately 2 hours\nPrimary: approximately 2.5 hours\nMiddle School: approximately 3 hours\n\nThe timetable includes suitable breaks, interactive activities and a balanced mix of teacher-led and student-led learning."],
        ['id' => 2, 'category' => 'Online Schooling Model', 'question' => 'How does online schooling work at Zuvio?', 'answer' => "Zuvio Global School is a 100% online school where students attend structured live classes with teachers from home. Students follow a planned timetable and academic calendar and receive learning resources, assignments, projects, assessments and teacher support through the school's digital learning ecosystem."],
        ['id' => 3, 'category' => 'Curriculum & Pedagogy', 'question' => 'Is the Zuvio curriculum CBSE-mapped?', 'answer' => "Yes. Zuvio's academic curriculum is mapped to CBSE learning outcomes and designed with reference to NEP 2020 and NCF guidelines. This supports grade-appropriate concepts, competencies and structured academic progression."],
        ['id' => 4, 'category' => 'Curriculum & Pedagogy', 'question' => 'What is the Oxford theme-based curriculum approach?', 'answer' => "Zuvio incorporates an Oxford-based thematic learning approach, where concepts are connected through meaningful themes instead of always being taught as isolated topics. A theme can bring together language, environmental studies, creativity, research, communication and real-world applications, helping children understand connections across subjects."],
        ['id' => 5, 'category' => 'Curriculum & Pedagogy', 'question' => 'What is Project-Based Learning?', 'answer' => "Project-Based Learning allows students to learn by doing. Students explore a question, challenge or real-life topic and create a project, presentation, model, report or solution. This helps develop critical thinking, creativity, communication, research, collaboration and problem-solving skills."],
        ['id' => 6, 'category' => 'Technology & AI', 'question' => 'How is Artificial Intelligence integrated into the curriculum?', 'answer' => "AI learning is introduced in an age-appropriate manner as part of Zuvio's future-ready learning approach. Students gradually learn AI concepts, responsible use of technology, problem-solving and practical applications. Zuvio's AI learning approach uses IBM-supported/certified learning resources and frameworks, subject to the applicable programme and certification requirements."],
        ['id' => 7, 'category' => 'Assessments & Examinations', 'question' => 'How are examinations and assessments conducted?', 'answer' => "Assessment includes formative assessments, projects, assignments, class participation, portfolios and summative assessments. Formal assessments are conducted online according to school examination guidelines. For Nursery-UKG, assessment is primarily activity- and observation-based rather than dependent on formal written examinations. Parents receive regular feedback and progress reports."],
        ['id' => 8, 'category' => 'Parent-Teacher Communication', 'question' => 'Can parents speak directly with teachers?', 'answer' => "Yes. Parents can communicate with teachers and the academic team through WhatsApp groups, email, scheduled virtual meetings and Parent-Teacher Meetings (PTMs), depending on the requirement."],
        ['id' => 9, 'category' => 'Boards & Pathways', 'question' => 'What is NIOS, and how does it relate to online schooling?', 'answer' => "The National Institute of Open Schooling (NIOS) provides recognised open-schooling pathways in India. Where applicable, families seeking a formal open-schooling certification pathway can explore NIOS according to its prevailing eligibility, registration and examination requirements.\n\nImportant: NIOS and a CBSE-mapped curriculum are not the same. Curriculum mapping describes what and how a student learns, while NIOS is an examination/certification pathway."],
        ['id' => 10, 'category' => 'Transition & Portability', 'question' => 'Can my child move back to an offline school later?', 'answer' => "Yes, students can transition from online learning to an offline school. Admission is governed by the receiving school's admission policy, documentation, grade-level requirements and applicable board/regulatory rules. Zuvio can support parents with relevant academic records and progress documentation."],
        ['id' => 11, 'category' => 'Beyond Academics', 'question' => 'Are co-curricular activities included in online schooling?', 'answer' => "Yes. Co-curricular learning is planned in sync with the academic curriculum. Activities may include art, communication, storytelling, STEM, innovation, digital skills, presentations, competitions, projects and other age-appropriate enrichment experiences. The aim is to support academic, creative, social and emotional development."],
        ['id' => 12, 'category' => 'Student Life & Interaction', 'question' => 'Will children get enough interaction in an online school?', 'answer' => "Yes. Live classes can include discussions, quizzes, presentations, show-and-tell, collaborative activities, projects, peer interaction and teacher questioning. Students are encouraged to actively participate rather than simply watch a screen."],
        ['id' => 13, 'category' => 'Student Support & Monitoring', 'question' => 'How is student progress monitored?', 'answer' => "Teachers monitor progress through classroom participation, assignments, projects, quizzes, assessments and portfolios. Regular reviews help identify learning gaps and determine where additional academic support may be required."],
        ['id' => 14, 'category' => 'Student Support & Monitoring', 'question' => 'What happens if my child is struggling with a concept?', 'answer' => "Teachers can identify learning gaps through continuous assessment and classroom interaction. Students may receive additional clarification, practice material and academic support according to their learning needs."],
        ['id' => 15, 'category' => 'Well-being & Balance', 'question' => 'How much screen time will my child have?', 'answer' => "Zuvio follows grade-appropriate live-class durations. Online lessons are complemented by offline activities such as reading, writing, worksheets, projects, art, research, experiments and hands-on learning. The objective is not to keep children continuously in front of a screen."],
        ['id' => 16, 'category' => 'International & NRI Families', 'question' => 'Is online schooling suitable for children living outside India?', 'answer' => "Online schooling can be particularly useful for NRI and internationally mobile families seeking continuity in learning while living abroad or moving between countries. Families should separately check compulsory-schooling and recognition requirements applicable in their country of residence."],
        ['id' => 17, 'category' => 'Admissions & Transitions', 'question' => 'What support is provided to students joining in Term 2?', 'answer' => "Students joining mid-session can undergo a baseline/diagnostic assessment to understand their current learning level. Teachers can then identify gaps and provide a bridge learning plan to support a smooth transition into the ongoing curriculum."],
        ['id' => 18, 'category' => 'Distinctive Approach', 'question' => 'What makes Zuvio\'s online learning approach different?', 'answer' => "Zuvio combines structured academics, CBSE-mapped learning, thematic learning, project-based education, AI and digital skills, experiential learning, co-curricular activities and regular parent-teacher interaction in a flexible online environment. The focus is on developing confident, independent and future-ready learners.\n\nNote: Programme, curriculum, certification, examination and progression details are subject to applicable school policies and external provider/regulatory requirements. Partnership and certification references should be read according to the specific programme applicable to the learner."]
    ];
}

// Extract distinct categories
$categories = ['All'];
foreach ($faqs as $f) {
    $cat = $f['category'] ?? 'General';
    if (!in_array($cat, $categories)) {
        $categories[] = $cat;
    }
}

$page_slug = 'faq';
$seo = [
    'seo_title' => 'Parent FAQ — Online Schooling | Zuvio Global School',
    'meta_description' => 'Comprehensive answers to parent questions regarding live class timings, CBSE curriculum mapping, Oxford thematic learning, AI integration, assessments, and screen time balance.',
    'canonical_url' => BASE_URL . '/faq',
    'og_title' => 'Parent FAQ — Online Schooling | Zuvio Global School',
    'og_description' => 'A complete guide for parents to understand Zuvio’s 100% online schooling model.',
    'og_image' => '/assets/images/logo.png',
    'index_status' => 'index, follow'
];

include_once dirname(__FILE__) . '/../includes/header.php';
?>

<!-- Hero Banner -->
<section style="background-color: var(--pastel-blue); padding: 5rem 1.5rem; text-align: center; border-bottom: 1px solid var(--color-border);">
  <div class="container" style="max-width: 800px;">
    <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Parent Guide</span>
    <h1 style="font-size: 3rem; color: var(--color-navy-dark); margin: 0.5rem 0 1rem 0; font-family: var(--font-primary);">Parent FAQ — Online Schooling</h1>
    <p style="font-size: 1.12rem; color: var(--color-text); line-height: 1.7;">
      A quick guide for parents to understand Zuvio's online schooling model, curriculum, assessments, communication and student support.
    </p>
  </div>
</section>

<section class="section" style="background-color: #FFFFFF; min-height: 600px;">
  <div class="container" style="max-width: 960px;">
    
    <!-- Search Bar -->
    <div style="margin-bottom: 2rem;">
      <input type="text" id="faqSearchInputFull" placeholder="Search any topic (e.g., class duration, CBSE, NIOS, screen time, Term 2)..." class="faq-search-input" onkeyup="filterFaqsPage()">
    </div>

    <!-- Category Filter Pills -->
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 2.5rem;" id="categoryFilterContainer">
      <?php foreach ($categories as $idx => $cat): ?>
        <button type="button" class="btn btn-outline category-pill-btn <?php echo $idx === 0 ? 'active' : ''; ?>" data-category="<?php echo h($cat); ?>" onclick="filterByCategory('<?php echo h($cat); ?>', this)" style="padding: 0.4rem 1rem; font-size: 0.82rem; border-radius: 20px; font-weight: 600;">
          <?php echo h($cat); ?>
        </button>
      <?php endforeach; ?>
    </div>

    <!-- Accordion List -->
    <div id="faqFullList">
      <?php foreach ($faqs as $idx => $faq): 
        $cat = $faq['category'] ?? 'General';
      ?>
        <div class="faq-accordion-item" data-category="<?php echo h($cat); ?>">
          <button class="faq-accordion-btn" onclick="toggleFaq(this)">
            <span>
              <span style="color: var(--color-gold); font-weight: 700; margin-right: 0.5rem;">Q<?php echo ($idx + 1); ?>.</span>
              <?php echo h($faq['question']); ?>
            </span>
            <span class="faq-accordion-icon">+</span>
          </button>
          <div class="faq-accordion-body">
            <div class="faq-accordion-body-inner">
              <span style="display: inline-block; background-color: var(--pastel-blue); color: var(--color-teal); font-size: 0.72rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 4px; text-transform: uppercase; margin-bottom: 0.75rem;">
                <?php echo h($cat); ?>
              </span>
              <div style="color: var(--color-text); font-size: 0.95rem; line-height: 1.75;">
                <?php echo nl2br(h($faq['answer'])); ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Still Have Questions Card -->
    <div style="margin-top: 4rem; background: var(--color-surface-warm); border-radius: var(--radius-lg); border: 1px solid var(--color-border); padding: 2.5rem; text-align: center;">
      <h3 style="font-size: 1.5rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">Still have questions about online schooling?</h3>
      <p style="color: var(--color-muted); font-size: 0.95rem; max-width: 550px; margin: 0 auto 1.5rem auto;">
        Our academic counselors are available to walk you through live class demos, timetables, and personalized learning plans.
      </p>
      <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
        <a href="/contact" class="btn btn-primary" style="background-color: var(--color-navy); border-color: var(--color-navy); color: #FFFFFF; font-weight: 600;">Contact Academic Counselor</a>
        <a href="javascript:void(0)" onclick="openCallbackModal()" class="btn btn-primary btn-demo" style="background-color: var(--color-teal); border-color: var(--color-teal); color: #FFFFFF; font-weight: 600;">Book a Demo</a>
      </div>
    </div>

  </div>
</section>

<style>
  .category-pill-btn.active {
    background-color: var(--color-navy) !important;
    color: #FFFFFF !important;
    border-color: var(--color-navy) !important;
  }
</style>

<script>
  function toggleFaq(btn) {
    const item = btn.closest('.faq-accordion-item');
    if (!item) return;
    const body = item.querySelector('.faq-accordion-body');
    if (!body) return;
    const isOpen = item.classList.contains('open');

    if (isOpen) {
      item.classList.remove('open');
      body.style.maxHeight = '0px';
      setTimeout(() => {
        if (!item.classList.contains('open')) {
          body.style.removeProperty('max-height');
        }
      }, 350);
    } else {
      item.classList.add('open');
      body.style.maxHeight = body.scrollHeight + 'px';
    }
  }

  let activeCategory = 'All';

  function filterByCategory(cat, btn) {
    activeCategory = cat;
    document.querySelectorAll('.category-pill-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    applyFilters();
  }

  function filterFaqsPage() {
    applyFilters();
  }

  function applyFilters() {
    const searchVal = document.getElementById('faqSearchInputFull').value.toLowerCase();
    const items = document.querySelectorAll('#faqFullList .faq-accordion-item');

    items.forEach(item => {
      const itemCategory = item.getAttribute('data-category');
      const text = item.textContent.toLowerCase();

      const matchesCat = (activeCategory === 'All' || itemCategory === activeCategory);
      const matchesSearch = text.includes(searchVal);

      if (matchesCat && matchesSearch) {
        item.style.display = '';
      } else {
        item.style.display = 'none';
      }
    });
  }
</script>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
