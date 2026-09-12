<?php
// Zuvio Global School - Dedicated Enrol Now Page
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// CMS synchronization
if (!isset($_SESSION['mock_admissions_cms'])) {
    $_SESSION['mock_admissions_cms'] = [];
}
$adm_cms = &$_SESSION['mock_admissions_cms'];

$enrol_data = $adm_cms['enrol'] ?? [
    'title' => 'Simple 5-Step Admissions Journey',
    'subtitle' => 'From initial enquiry to your child’s very first live classroom session.',
    'steps' => [
        ['step' => 1, 'title' => 'Connect with Counsellor', 'desc' => 'Speak with an expert academic advisor to understand curriculum mapping, live class timings, and technological setup.'],
        ['step' => 2, 'title' => 'Fill Out Admission Form', 'desc' => 'Complete the online application with student details, previous academic background, and preferred curriculum track.'],
        ['step' => 3, 'title' => 'Pay the Fees', 'desc' => 'Secure your seat through transparent quarterly tuition payment via encrypted online payment gateway or bank transfer.'],
        ['step' => 4, 'title' => 'Receive Login Credentials', 'desc' => 'Get dedicated student LMS access, parent portal onboarding credentials, digital timetables, and orientation pack.'],
        ['step' => 5, 'title' => 'Attend Orientation Session', 'desc' => 'Meet class mentors, test interactive tools, meet global peers, and begin live interactive schooling with confidence.']
    ],
    'required_docs' => [
        'Valid birth certificate of the child',
        'Valid photo identity card of parent/guardian and child (Aadhaar card or passport)',
        'Recent passport-sized color photographs of parent/guardian and child',
        'Previous year school progress report / marks card (for admission in Grade 1 and above)',
        'Transfer Certificate (if applicable from previous recognized school)'
    ]
];

$page_slug = 'admissions-enrol';
$seo = [
    'seo_title' => 'Enrol Now — Online School Admissions 2026–2027 | Zuvio Global School',
    'meta_description' => 'Apply for admissions 2026–2027 at Zuvio Global School. 5-step enrolment process, required documents checklist, and online application form for K–8.',
    'canonical_url' => BASE_URL . '/admissions/enrol-now',
    'og_title' => 'Enrol Now — Zuvio Global School',
    'og_description' => 'Admissions open for Academic Year 2026–2027. Simple 5-step online enrolment process and required documents checklist.',
    'og_image' => '/assets/images/about_us_hero.jpg',
    'index_status' => 'index, follow'
];

include_once dirname(__FILE__) . '/../includes/header.php';

render_breadcrumbs([
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Admissions', 'url' => '/admissions'],
    ['label' => 'Enrol Now']
]);
?>

<main class="admissions-enrol-page">
  <!-- Hero Section -->
  <section class="section-hero" style="background-color: var(--pastel-blue); padding: 5rem 1.5rem; text-align: center; border-bottom: 1px solid var(--color-border);">
    <div class="container" style="max-width: 850px;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">
        Admissions Open &bull; Session 2026–2027
      </span>
      <h1 style="font-size: 2.85rem; font-family: var(--font-primary); margin-bottom: 1rem; color: var(--color-navy-dark); font-weight: 700; line-height: 1.2;">
        Enrol Your Child at Zuvio
      </h1>
      <p style="font-size: 1.15rem; font-weight: 500; line-height: 1.6; color: var(--color-text); margin: 0 auto; max-width: 740px;">
        Join a borderless, accredited online learning community. Complete the simple enrolment process below to secure your child’s seat.
      </p>
    </div>
  </section>

  <!-- 5-Step Admissions Process Visual -->
  <section class="section" style="background-color: #FFFFFF; padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Seamless Progression
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          <?php echo h($enrol_data['title']); ?>
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          <?php echo h($enrol_data['subtitle']); ?>
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
        <?php foreach ($enrol_data['steps'] as $st): ?>
          <div style="background-color: var(--color-surface-warm); border-radius: var(--radius-md); padding: 2rem 1.5rem; text-align: center; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--color-navy); color: var(--color-gold); font-weight: 800; font-size: 1.25rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem auto; font-family: var(--font-primary); box-shadow: var(--shadow-sm);">
                <?php echo h($st['step']); ?>
              </div>
              <h3 style="font-size: 1.15rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">
                <?php echo h($st['title']); ?>
              </h3>
              <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.6; margin: 0;">
                <?php echo h($st['desc']); ?>
              </p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Enrolment Application Form & Required Documents -->
  <section class="section" style="background-color: var(--pastel-blue); padding: 5.5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 3.5rem; align-items: start;">
        
        <!-- Application Form Card -->
        <div style="background: #FFFFFF; border-radius: var(--radius-lg); padding: 3rem 2.5rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-md);">
          <div style="margin-bottom: 2rem;">
            <span style="font-size: 0.8rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 1.5px;">
              Step 01 of 05
            </span>
            <h2 style="font-size: 1.85rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0.25rem 0 0.5rem 0;">
              Online Application Form
            </h2>
            <p style="color: var(--color-muted); font-size: 0.92rem; margin: 0;">
              Fill in the details below. Our admissions director will review your submission and contact you within 24 hours.
            </p>
          </div>

          <div id="enrolFormStatus" style="display: none; padding: 1rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-size: 0.95rem;"></div>

          <form id="admissionsEnrolForm" onsubmit="submitEnrolForm(event)">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
              <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.4rem;">Parent / Guardian Name *</label>
                <input type="text" name="parent_name" required placeholder="e.g. Rahul Sharma" style="width: 100%; padding: 0.75rem; border: 1.5px solid rgba(6, 43, 99, 0.2); border-radius: var(--radius-sm); font-size: 0.95rem; outline: none;">
              </div>
              <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.4rem;">Parent Email *</label>
                <input type="email" name="email" required placeholder="name@example.com" style="width: 100%; padding: 0.75rem; border: 1.5px solid rgba(6, 43, 99, 0.2); border-radius: var(--radius-sm); font-size: 0.95rem; outline: none;">
              </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
              <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.4rem;">Phone / WhatsApp *</label>
                <input type="tel" name="phone" required placeholder="e.g. +91 98765 43210" style="width: 100%; padding: 0.75rem; border: 1.5px solid rgba(6, 43, 99, 0.2); border-radius: var(--radius-sm); font-size: 0.95rem; outline: none;">
              </div>
              <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.4rem;">Preferred Callback Time *</label>
                <select name="preferred_time" required style="width: 100%; padding: 0.75rem; border: 1.5px solid rgba(6, 43, 99, 0.2); border-radius: var(--radius-sm); font-size: 0.95rem; outline: none; background: #fff;">
                  <option value="Morning (10:00 AM - 1:00 PM)">Morning (10:00 AM - 1:00 PM)</option>
                  <option value="Afternoon (1:00 PM - 4:00 PM)">Afternoon (1:00 PM - 4:00 PM)</option>
                  <option value="Evening (4:00 PM - 7:00 PM)">Evening (4:00 PM - 7:00 PM)</option>
                </select>
              </div>
            </div>

            <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
              <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.4rem;">Student Full Name *</label>
                <input type="text" name="student_name" required placeholder="e.g. Aarav Sharma" style="width: 100%; padding: 0.75rem; border: 1.5px solid rgba(6, 43, 99, 0.2); border-radius: var(--radius-sm); font-size: 0.95rem; outline: none;">
              </div>
              <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.4rem;">Grade Applying For *</label>
                <select name="grade" required style="width: 100%; padding: 0.75rem; border: 1.5px solid rgba(6, 43, 99, 0.2); border-radius: var(--radius-sm); font-size: 0.95rem; outline: none; background: #fff;">
                  <option value="">Select Grade</option>
                  <option value="Nursery">Nursery</option>
                  <option value="LKG">LKG</option>
                  <option value="UKG">UKG</option>
                  <option value="Grade 1">Grade 1</option>
                  <option value="Grade 2">Grade 2</option>
                  <option value="Grade 3">Grade 3</option>
                  <option value="Grade 4">Grade 4</option>
                  <option value="Grade 5">Grade 5</option>
                  <option value="Grade 6">Grade 6</option>
                  <option value="Grade 7">Grade 7</option>
                  <option value="Grade 8">Grade 8</option>
                </select>
              </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
              <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.4rem;">Questions or Specific Learning Needs</label>
              <textarea name="message" rows="3" placeholder="Tell us about previous schooling, learning preferences, sports/arts schedule, or special education needs..." style="width: 100%; padding: 0.75rem; border: 1.5px solid rgba(6, 43, 99, 0.2); border-radius: var(--radius-sm); font-size: 0.95rem; outline: none; font-family: inherit; line-height: 1.5;"></textarea>
            </div>

            <button type="submit" id="enrolSubmitBtn" class="btn btn-primary" style="width: 100%; background-color: var(--color-navy); border-color: var(--color-navy); color: #FFFFFF; font-weight: 700; padding: 0.95rem; font-size: 1rem; border-radius: var(--radius-sm); cursor: pointer; transition: background 0.2s;">
              Submit Application &rarr;
            </button>
          </form>
        </div>

        <!-- Required Documents & Assistance Card -->
        <div>
          <div style="background: #FFFFFF; border-radius: var(--radius-lg); padding: 2.5rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); margin-bottom: 2rem; border-top: 4px solid var(--color-gold);">
            <span style="font-size: 0.8rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 1.5px;">
              Document Checklist
            </span>
            <h3 style="font-size: 1.5rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0.25rem 0 1rem 0;">
              Required Documents
            </h3>
            <p style="color: var(--color-muted); font-size: 0.9rem; line-height: 1.6; margin-bottom: 1.25rem;">
              To complete the enrolment process smoothly, please keep scanned or digital copies of the following documents ready:
            </p>

            <ul style="display: flex; flex-direction: column; gap: 0.85rem; list-style: none; padding: 0; margin: 0;">
              <?php foreach ($enrol_data['required_docs'] as $doc): ?>
                <li style="display: flex; align-items: flex-start; gap: 0.75rem; font-size: 0.92rem; color: var(--color-text); line-height: 1.5;">
                  <span style="color: var(--color-teal); font-weight: 800; font-size: 1.1rem; flex-shrink: 0;">✓</span>
                  <span><?php echo h($doc); ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>

          <div style="background: var(--color-surface-warm); border-radius: var(--radius-lg); padding: 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm);">
            <h4 style="font-size: 1.15rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0 0 0.5rem 0;">
              Need Immediate Guidance?
            </h4>
            <p style="font-size: 0.88rem; color: var(--color-text); line-height: 1.6; margin-bottom: 1.25rem;">
              Our Admissions Office is available Monday to Saturday (10:00 AM – 7:00 PM IST) to assist you with eligibility questions or curriculum selection.
            </p>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
              <a href="tel:+919876543210" style="color: var(--color-teal); font-weight: 700; text-decoration: none; font-size: 0.9rem;">📞 Call +91 98765 43210</a>
              <a href="/faq" style="color: var(--color-navy); font-weight: 600; text-decoration: none; font-size: 0.9rem;">Read Parent FAQ &rarr;</a>
            </div>
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
          Explore Admissions Details
        </h2>
        <p style="max-width: 650px; margin: 0 auto 2rem auto; color: rgba(255, 255, 255, 0.9); font-size: 1.05rem; line-height: 1.7;">
          Review our age-wise eligibility guidelines, official fee schedule, or download the academic calendar.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
          <a href="/admissions/eligibility" class="btn btn-primary" style="background-color: var(--color-gold); color: var(--color-navy-dark); font-weight: 700; border: none; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Check Eligibility &rarr;
          </a>
          <a href="/admissions/fees" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid #FFFFFF; font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Fee Structure
          </a>
          <a href="/admissions/calendar" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid rgba(255, 255, 255, 0.6); font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Academic Calendar
          </a>
        </div>
      </div>
    </div>
  </section>
</main>

<script>
function submitEnrolForm(e) {
  e.preventDefault();
  const form = document.getElementById('admissionsEnrolForm');
  const statusBox = document.getElementById('enrolFormStatus');
  const submitBtn = document.getElementById('enrolSubmitBtn');
  
  statusBox.style.display = 'none';
  submitBtn.disabled = true;
  submitBtn.innerText = 'Submitting Application...';
  
  const formData = new FormData(form);
  
  fetch('/submit-callback.php', {
    method: 'POST',
    body: formData
  })
  .then(async res => {
    const data = await res.json().catch(() => ({}));
    if (res.ok && data.status === 'success') {
      statusBox.style.display = 'block';
      statusBox.style.background = '#e6f9f0';
      statusBox.style.borderLeft = '4px solid #047857';
      statusBox.style.color = '#065f46';
      statusBox.innerHTML = '<strong>Application Received!</strong> Thank you for applying to Zuvio Global School. Our admissions coordinator will get in touch with you shortly to schedule your orientation.';
      form.reset();
    } else {
      throw new Error(data.message || 'Submission encountered an issue. Please try again or contact our admissions office directly.');
    }
  })
  .catch(err => {
    statusBox.style.display = 'block';
    statusBox.style.background = '#fef2f2';
    statusBox.style.borderLeft = '4px solid #dc2626';
    statusBox.style.color = '#991b1b';
    statusBox.innerHTML = '<strong>Submission Notice:</strong> ' + (err.message || 'Please check all required fields.');
  })
  .finally(() => {
    submitBtn.disabled = false;
    submitBtn.innerText = 'Submit Application →';
  });
}
</script>

<?php include_once dirname(__FILE__) . '/../includes/footer.php'; ?>
