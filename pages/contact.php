<?php
// Zuvio Global School - Contact Page Template
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

$form_status = 'idle';
$error_message = '';

// Handle Enquiry Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_enquiry'])) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $form_status = 'error';
        $error_message = 'Security validation failed. Please refresh and try again.';
    } else {
        $parent_name = trim($_POST['parent_name'] ?? '');
        $student_name = trim($_POST['student_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $grade = trim($_POST['grade'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        if (empty($parent_name) || empty($email) || empty($phone) || empty($grade)) {
            $form_status = 'error';
            $error_message = 'All fields except message are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $form_status = 'error';
            $error_message = 'Please enter a valid email address.';
        } else {
            try {
                if (!$db) throw new Exception("Database connection required.");
                
                $stmt = $db->prepare("
                    INSERT INTO `enquiries` (`parent_name`, `student_name`, `grade`, `phone`, `email`, `message`, `source`, `status_id`)
                    VALUES (?, ?, ?, ?, ?, ?, 'Contact Page', 1)
                ");
                $stmt->execute([
                    $parent_name,
                    $student_name ?: ($parent_name . ' (Student)'),
                    $grade,
                    $phone,
                    $email,
                    $message ?: 'Submitted via Contact page'
                ]);
                $form_status = 'success';
            } catch (Exception $e) {
                $form_status = 'error';
                $error_message = 'Database connection required. Form could not be persisted.';
            }
        }
    }
}

$phone = get_setting('phone', '7827262956');
$email = get_setting('general_email', 'info@zuvioglobalschool.com');
$address = get_setting('address', "B-09, Lower Ground Floor,\nITL Twin Tower,\nNetaji Subhash Place,\nPitampura,\nDelhi - 110034");
$office_timings = get_setting('office_timings', '10-7');

$page_slug = 'contact';
include_once dirname(__FILE__) . '/../includes/header.php';
?>

<!-- Hero Banner Header -->
<section class="contact-hero" style="background-image: linear-gradient(rgba(0, 10, 66, 0.8), rgba(0, 10, 66, 0.85)), url('/assets/images/Hero image 1.png'); background-size: cover; background-position: center; color: #FFFFFF; padding: 6rem 2rem; text-align: center;">
  <div class="container" style="max-width: 800px;">
    <h1 style="font-size: 3rem; font-family: var(--font-primary); margin-bottom: 1rem; color: #FFFFFF;">Contact Us</h1>
    <p style="font-size: 1.15rem; font-weight: 300; line-height: 1.6; color: #E2E8F0; margin: 0;">
      We are here to support your child's onboarding. Connect with our academic office or submit your enquiry below.
    </p>
  </div>
</section>

<!-- Section Content -->
<section class="section" style="background-color: var(--color-white); border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="grid-2" style="gap: 4rem; align-items: flex-start;">
      
      <!-- Left Column: Contact Cards -->
      <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        
        <!-- Address Card -->
        <div class="card" style="border-top: 4px solid var(--color-gold); border-left: none; padding: 2rem;">
          <h3 style="font-size: 1.25rem; color: var(--color-navy); margin-bottom: 0.75rem; font-family: var(--font-primary);">Our Office</h3>
          <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6;">
            <?php echo nl2br(h($address)); ?>
          </p>
        </div>

        <!-- Phone & Email Card -->
        <div class="card" style="border-top: 4px solid var(--color-gold); border-left: none; padding: 2rem;">
          <h3 style="font-size: 1.25rem; color: var(--color-navy); margin-bottom: 0.75rem; font-family: var(--font-primary);">Direct Connections</h3>
          <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; margin-bottom: 0.5rem;">
            <strong>Phone / WhatsApp:</strong> <a href="tel:<?php echo h($phone); ?>" style="color: var(--color-navy); font-weight: 600;">+91 <?php echo h($phone); ?></a>
          </p>
          <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6;">
            <strong>Email:</strong> <a href="mailto:<?php echo h($email); ?>" style="color: var(--color-navy); font-weight: 600;"><?php echo h($email); ?></a>
          </p>
        </div>

        <!-- Timings Card -->
        <div class="card" style="border-top: 4px solid var(--color-gold); border-left: none; padding: 2rem;">
          <h3 style="font-size: 1.25rem; color: var(--color-navy); margin-bottom: 0.75rem; font-family: var(--font-primary);">Working Hours</h3>
          <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6;">
            Our academic office is open from Monday to Saturday, <strong><?php echo h($office_timings); ?> AM - PM</strong>.
          </p>
        </div>

      </div>

      <!-- Right Column: Interactive Form -->
      <div style="background-color: var(--color-surface-blue); border-radius: var(--radius-lg); padding: 3rem 2.5rem; box-shadow: var(--shadow-sm); border: 1px solid var(--color-border);">
        <?php if ($form_status === 'success'): ?>
          <div style="text-align: center; padding: 2rem 0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--color-success)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 1rem;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <h3 style="font-size: 1.5rem; color: var(--color-navy); margin-bottom: 0.75rem; font-family: var(--font-primary);">Enquiry Submitted</h3>
            <p style="color: var(--color-muted); font-size: 0.95rem; line-height: 1.6;">
              Thank you. Your enquiry has been registered. Our roadmap advisor will contact you within 24 working hours.
            </p>
          </div>
        <?php else: ?>
          <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
            <h3 style="font-size: 1.5rem; color: var(--color-navy); margin-bottom: 0.5rem; font-family: var(--font-primary);">Submit an Enquiry</h3>
            <p style="font-size: 0.8rem; color: var(--color-muted); margin-bottom: 1.5rem;">
              Fill in the form to register your child's diagnostic roadmap request.
            </p>

            <?php if ($form_status === 'error'): ?>
              <div style="background-color: #FDF2F8; border: 1px solid #FBCFE8; padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: #D946EF; font-size: 0.8rem; margin-bottom: 1.25rem;">
                <?php echo h($error_message); ?>
              </div>
            <?php endif; ?>

            <div style="margin-bottom: 1rem;">
              <label style="font-size: 0.8rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.4rem; display: block;">Parent Name *</label>
              <input type="text" name="parent_name" required class="contact-input" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid rgba(6, 43, 99, 0.2); border-radius: var(--radius-sm); outline: none;">
            </div>

            <div style="margin-bottom: 1rem;">
              <label style="font-size: 0.8rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.4rem; display: block;">Student Name (Optional)</label>
              <input type="text" name="student_name" class="contact-input" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid rgba(6, 43, 99, 0.2); border-radius: var(--radius-sm); outline: none;">
            </div>

            <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 1rem; margin-bottom: 1rem;" class="contact-form-row">
              <div>
                <label style="font-size: 0.8rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.4rem; display: block;">Email Address *</label>
                <input type="email" name="email" required class="contact-input" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid rgba(6, 43, 99, 0.2); border-radius: var(--radius-sm); outline: none;">
              </div>
              <div>
                <label style="font-size: 0.8rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.4rem; display: block;">Grade Level *</label>
                <select name="grade" required class="contact-input" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid rgba(6, 43, 99, 0.2); border-radius: var(--radius-sm); outline: none; background-color: #FFFFFF; height: 38px;">
                  <option value="">Select</option>
                  <option value="Early Years">Early Years</option>
                  <option value="Primary (1-5)">Grades 1-5</option>
                  <option value="Middle School (6-8)">Grades 6-8</option>
                </select>
              </div>
            </div>

            <div style="margin-bottom: 1.25rem;">
              <label style="font-size: 0.8rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.4rem; display: block;">Phone Number *</label>
              <input type="tel" name="phone" required class="contact-input" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid rgba(6, 43, 99, 0.2); border-radius: var(--radius-sm); outline: none;">
            </div>

            <div style="margin-bottom: 1.5rem;">
              <label style="font-size: 0.8rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.4rem; display: block;">Message / Details</label>
              <textarea name="message" rows="3" class="contact-input" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid rgba(6, 43, 99, 0.2); border-radius: var(--radius-sm); outline: none; resize: none;"></textarea>
            </div>

            <button type="submit" name="submit_enquiry" class="btn btn-primary" style="width: 100%; padding: 0.9rem;">Submit Enquiry Form</button>
          </form>
        <?php endif; ?>
      </div>

    </div>
  </div>
</section>

<!-- Maps Section Placeholder -->
<section class="section" style="padding: 0; background-color: var(--color-white);">
  <div style="width: 100%; height: 350px; background-color: var(--color-surface-blue); display: flex; align-items: center; justify-content: center; color: var(--color-muted); font-size: 1rem;" class="map-placeholder">
    <span>ITL Twin Tower NSP Pitampura Delhi - Location Map Placeholder</span>
  </div>
</section>

<style>
  .contact-input:focus {
    border-color: var(--color-navy) !important;
    box-shadow: 0 0 0 2px rgba(6, 43, 99, 0.08);
  }
  @media (max-width: 768px) {
    .contact-form-row {
      grid-template-columns: 1fr !important;
    }
  }
</style>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
