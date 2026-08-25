<?php
// Zuvio Global School - Main Footer Template
require_once dirname(__FILE__) . '/helper.php';

$phone = get_setting('phone', '7827262956');
$email = get_setting('general_email', 'info@zuvioglobalschool.com');
$address = get_setting('address', "B-09, Lower Ground Floor,\nITL Twin Tower,\nNetaji Subhash Place,\nPitampura,\nDelhi - 110034");
$office_timings = get_setting('office_timings', '10-7');
$copyright = get_setting('copyright', '© 2026 Zuvio Global School. All rights reserved.');
$logo_path = get_setting('logo_url', '/assets/images/logo.png');

$social_insta = get_setting('social_instagram', '#');
$social_fb = get_setting('social_facebook', '#');
$social_linkedin = get_setting('social_linkedin', '#');
?>

  <!-- Footer Section -->
  <footer class="site-footer">
    <div class="footer-container">
      
      <!-- Column 1: Brand Anchor -->
      <div class="footer-col brand-col">
        <a href="/">
          <img src="<?php echo h($logo_path); ?>" alt="Zuvio Global School" class="footer-logo">
        </a>
        <p class="brand-tagline">Learning Beyond Boundaries</p>
        <p class="copyright-text"><?php echo h($copyright); ?></p>
      </div>

      <!-- Column 2: Quick Links -->
      <div class="footer-col Links-col">
        <h4 class="footer-title">Quick Navigation</h4>
        <ul class="footer-links-list">
          <li><a href="/about" class="footer-link">About Zuvio</a></li>
          <li><a href="/our-curriculum" class="footer-link">Academic Stages</a></li>
          <li><a href="/zuvio-beyond" class="footer-link">Beyond Academics</a></li>
          <li><a href="/blogs" class="footer-link">Blogs & Articles</a></li>
          <li><a href="/contact" class="footer-link">Contact & Enquiries</a></li>
        </ul>
      </div>

      <!-- Column 3: Verified Contact Info -->
      <div class="footer-col contact-col">
        <h4 class="footer-title">Contact Us</h4>
        <p class="contact-info-line">
          <strong>Address:</strong><br>
          <?php echo nl2br(h($address)); ?>
        </p>
        <p class="contact-info-line">
          <strong>Phone / WhatsApp:</strong><br>
          <a href="tel:<?php echo h($phone); ?>" class="contact-anchor">+91 <?php echo h($phone); ?></a>
        </p>
        <p class="contact-info-line">
          <strong>Email:</strong><br>
          <a href="mailto:<?php echo h($email); ?>" class="contact-anchor"><?php echo h($email); ?></a>
        </p>
        <p class="contact-info-line">
          <strong>Office Hours:</strong> <?php echo h($office_timings); ?> AM - PM
        </p>
      </div>

      <!-- Column 4: Institutional Handles -->
      <div class="footer-col social-col">
        <h4 class="footer-title">Follow Us</h4>
        <div class="social-links-row">
          <?php if ($social_fb !== '#'): ?>
            <a href="<?php echo h($social_fb); ?>" target="_blank" rel="noopener" class="social-icon-btn" aria-label="Facebook">FB</a>
          <?php endif; ?>
          <?php if ($social_insta !== '#'): ?>
            <a href="<?php echo h($social_insta); ?>" target="_blank" rel="noopener" class="social-icon-btn" aria-label="Instagram">IG</a>
          <?php endif; ?>
          <?php if ($social_linkedin !== '#'): ?>
            <a href="<?php echo h($social_linkedin); ?>" target="_blank" rel="noopener" class="social-icon-btn" aria-label="LinkedIn">LN</a>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </footer>

  <style>
    /* Footer Layout Stylings */
    .site-footer {
      background-color: var(--color-surface);
      border-top: 1px solid var(--color-border);
      padding: 5rem 0 3rem 0;
      color: var(--color-text);
      font-family: var(--font-secondary);
    }
    .footer-container {
      max-width: var(--max-width);
      margin: 0 auto;
      padding: 0 1.5rem;
      display: grid;
      grid-template-columns: 1.2fr 0.8fr 1.2fr 0.8fr;
      gap: 3.5rem;
    }
    .footer-col {
      display: flex;
      flex-direction: column;
      gap: 1.25rem;
    }
    .footer-logo {
      height: 75px;
      width: auto;
      object-fit: contain;
      display: block;
      transition: height 0.3s ease;
    }
    .brand-tagline {
      font-size: 1.05rem;
      font-weight: 700;
      color: var(--color-navy);
      margin-top: 0.5rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .copyright-text {
      font-size: 0.8rem;
      color: var(--color-muted);
      margin-top: 1rem;
    }
    .footer-title {
      font-family: var(--font-primary);
      font-size: 1.25rem;
      color: var(--color-navy);
      font-weight: 700;
      position: relative;
      padding-bottom: 0.5rem;
    }
    .footer-title::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 40px;
      height: 2px;
      background-color: var(--color-gold);
    }
    .footer-links-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 0.65rem;
    }
    .footer-link {
      font-size: 0.9rem;
      color: var(--color-text);
      transition: color var(--transition-fast);
    }
    .footer-link:hover {
      color: var(--color-gold);
      padding-left: 4px;
    }
    .contact-info-line {
      font-size: 0.9rem;
      color: var(--color-text);
      line-height: 1.5;
    }
    .contact-anchor {
      color: var(--color-navy);
      font-weight: 600;
      transition: color var(--transition-fast);
    }
    .contact-anchor:hover {
      color: var(--color-gold);
    }
    .social-links-row {
      display: flex;
      gap: 1rem;
    }
    .social-icon-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 38px;
      height: 38px;
      border-radius: 50%;
      border: 1.5px solid var(--color-navy);
      color: var(--color-navy);
      font-size: 0.8rem;
      font-weight: 700;
      transition: all var(--transition-fast);
    }
    .social-icon-btn:hover {
      background-color: var(--color-navy);
      color: var(--color-white);
      transform: translateY(-2px);
    }

    /* Footer Mobile Responsiveness */
    @media (max-width: 900px) {
      .footer-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 2.5rem;
      }
    }
    @media (max-width: 580px) {
      .footer-container {
        grid-template-columns: 1fr;
        gap: 2rem;
      }
      .copyright-text {
        margin-top: 1rem;
      }
    }
  </style>

  <!-- Floating Request Callback Widget Removed as requested -->

  <!-- Callback Modal Overlay -->
  <div class="callback-modal-overlay" id="callbackModalOverlay" onclick="closeCallbackModal(event)">
    <div class="callback-modal-content" onclick="event.stopPropagation()">
      <button class="callback-modal-close" onclick="closeCallbackModal(null)">&times;</button>
      <div class="callback-modal-header">
        <h3>Request Callback</h3>
        <p>Fill out the details below, and our advisor will call you back at your preferred time.</p>
      </div>
      <div id="callbackModalMessage" class="callback-status-message"></div>
      <form id="callbackForm" onsubmit="submitCallbackForm(event)">
        <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
        
        <div class="form-group">
          <label for="cb_parent_name">Parent Name *</label>
          <input type="text" id="cb_parent_name" name="parent_name" required>
        </div>
        
        <div class="form-group">
          <label for="cb_email">Email Address *</label>
          <input type="email" id="cb_email" name="email" required>
        </div>
        
        <div class="form-group">
          <label for="cb_phone">Phone Number *</label>
          <input type="tel" id="cb_phone" name="phone" required>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label for="cb_grade">Grade *</label>
            <select id="cb_grade" name="grade" required>
              <option value="">Select</option>
              <option value="Early Years">Early Years</option>
              <option value="Primary (1-5)">Grades 1-5</option>
              <option value="Middle School (6-8)">Grades 6-8</option>
            </select>
          </div>
          
          <div class="form-group">
            <label for="cb_preferred_time">Preferred Callback Time *</label>
            <select id="cb_preferred_time" name="preferred_time" required>
              <option value="">Select Time</option>
              <option value="Morning (9 AM - 12 PM)">Morning (9 AM - 12 PM)</option>
              <option value="Afternoon (12 PM - 3 PM)">Afternoon (12 PM - 3 PM)</option>
              <option value="Late Afternoon (3 PM - 6 PM)">Late Afternoon (3 PM - 6 PM)</option>
              <option value="Evening (6 PM - 8 PM)">Evening (6 PM - 8 PM)</option>
            </select>
          </div>
        </div>
        
        <div class="form-group">
          <label for="cb_message">Message / Question (Optional)</label>
          <textarea id="cb_message" name="message" rows="3"></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.85rem; margin-top: 0.5rem;">Request Callback</button>
      </form>
    </div>
  </div>

  <script>
    function openCallbackModal() {
      document.getElementById('callbackModalOverlay').classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function closeCallbackModal(event) {
      if (event === null || event.target === document.getElementById('callbackModalOverlay')) {
        document.getElementById('callbackModalOverlay').classList.remove('open');
        document.body.style.overflow = '';
      }
    }

    function submitCallbackForm(event) {
      event.preventDefault();
      
      const form = document.getElementById('callbackForm');
      const statusMsg = document.getElementById('callbackModalMessage');
      const submitBtn = form.querySelector('button[type="submit"]');
      
      // Clear messages
      statusMsg.className = 'callback-status-message';
      statusMsg.style.display = 'none';
      statusMsg.innerHTML = '';
      
      submitBtn.disabled = true;
      submitBtn.innerText = 'Submitting...';
      
      const formData = new FormData(form);
      
      fetch('/submit-callback.php', {
        method: 'POST',
        body: formData
      })
      .then(response => {
        return response.json().then(data => {
          if (!response.ok) {
            throw new Error(data.message || 'An error occurred during submission.');
          }
          return data;
        });
      })
      .then(data => {
        // Show success message
        statusMsg.className = 'callback-status-message success';
        statusMsg.innerHTML = 'Thank you. Our team will get in touch with you shortly.';
        statusMsg.style.display = 'block';
        
        // Reset form
        form.reset();
        
        // Hide form fields
        form.style.display = 'none';
        
        // Auto close modal after 3 seconds
        setTimeout(() => {
          closeCallbackModal(null);
          // Reset form view in case they open it again
          setTimeout(() => {
            form.style.display = 'block';
            statusMsg.style.display = 'none';
            submitBtn.disabled = false;
            submitBtn.innerText = 'Request Callback';
          }, 500);
        }, 3000);
      })
      .catch(error => {
        statusMsg.className = 'callback-status-message error';
        statusMsg.innerHTML = error.message || 'Database connection required. Form could not be persisted.';
        statusMsg.style.display = 'block';
        submitBtn.disabled = false;
        submitBtn.innerText = 'Request Callback';
      });
    }
  </script>

  <!-- Bottom Floating Admissions Announcement Bar -->
  <div id="admissionsAnnouncementBar" class="admissions-announcement-bar">
    <div class="announcement-content">
      <span class="announcement-badge">Announcements</span>
      <span class="announcement-text">Admissions ongoing for Mid-Session 2026–27 | Admissions open for Children with Learning Disabilities.</span>
      <a href="/contact" class="announcement-btn">Apply Now</a>
    </div>
    <button class="announcement-close" onclick="closeAdmissionsBar()">&times;</button>
  </div>

  <style>
    .admissions-announcement-bar {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background: linear-gradient(90deg, var(--color-navy-dark) 0%, var(--color-navy) 100%);
      color: var(--color-white);
      z-index: 9999;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.75rem 2rem;
      box-shadow: 0 -4px 20px rgba(6, 43, 99, 0.15);
      font-family: var(--font-secondary);
      transition: transform 0.3s ease;
    }
    .announcement-content {
      display: flex;
      align-items: center;
      gap: 1.25rem;
      flex-wrap: wrap;
      margin: 0 auto;
    }
    .announcement-badge {
      background-color: var(--color-gold);
      color: var(--color-navy-dark);
      padding: 0.3rem 0.85rem;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .announcement-text {
      font-size: 0.92rem;
      font-weight: 500;
      letter-spacing: 0.25px;
    }
    .announcement-btn {
      background-color: var(--color-teal);
      color: #fff;
      padding: 0.4rem 1.15rem;
      border-radius: var(--radius-sm);
      font-size: 0.8rem;
      font-weight: 600;
      text-decoration: none;
      transition: background-color 0.2s ease;
    }
    .announcement-btn:hover {
      background-color: #0b9ba9;
    }
    .announcement-close {
      background: none;
      border: none;
      color: var(--color-white);
      font-size: 1.6rem;
      cursor: pointer;
      opacity: 0.8;
      transition: opacity 0.2s;
      padding: 0 0.5rem;
      line-height: 1;
    }
    .announcement-close:hover {
      opacity: 1;
    }
    @media (max-width: 850px) {
      .admissions-announcement-bar {
        padding: 0.8rem 1rem;
      }
      .announcement-content {
        gap: 0.5rem;
        justify-content: center;
        text-align: center;
      }
      .announcement-text {
        font-size: 0.82rem;
        width: 100%;
      }
    }
  </style>

  <script>
    function closeAdmissionsBar() {
      const bar = document.getElementById('admissionsAnnouncementBar');
      if (bar) {
        bar.style.transform = 'translateY(100%)';
        setTimeout(() => bar.style.display = 'none', 300);
      }
    }
  </script>
</body>
</html>
