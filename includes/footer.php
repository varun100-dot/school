<?php
// Zuvio Global School - Main Footer Template
require_once dirname(__FILE__) . '/helper.php';

$phone = get_setting('phone', '7827262956');
$whatsapp = get_setting('whatsapp', '7827262956');
$email = get_setting('general_email', 'info@zuvioglobalschool.com');
$address = get_setting('address', "B-09, Lower Ground Floor,\nITL Twin Tower,\nNetaji Subhash Place,\nPitampura,\nDelhi - 110034");
$office_timings = get_setting('office_timings', 'Monday–Saturday, 10:00 AM–7:00 PM');
if ($office_timings === '10-7' || $office_timings === '10-7 AM - PM') {
    $office_timings = 'Monday–Saturday, 10:00 AM–7:00 PM';
}
$copyright = get_setting('copyright', '© 2026 Zuvio Global School. All rights reserved.');
$logo_path = get_setting('logo_url', '/assets/images/logo.png');

$social_insta = get_setting('social_instagram', 'https://www.instagram.com/thezuvio/');
$social_fb = get_setting('social_facebook', 'https://www.facebook.com/share/1XsYWDm3rt/');
$social_linkedin = get_setting('social_linkedin', 'https://www.linkedin.com/company/zuvio-global-school/');
$social_youtube = get_setting('social_youtube', 'https://www.youtube.com/@zuvioglobalschool');
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
          <strong>Office Hours:</strong> <?php echo h($office_timings); ?>
        </p>
      </div>

      <!-- Column 4: Institutional Handles -->
      <div class="footer-col social-col">
        <h4 class="footer-title">Follow Us</h4>
        <div class="social-links-row">
          <?php if ($social_fb !== '#'): ?>
            <a href="<?php echo h($social_fb); ?>" target="_blank" rel="noopener" class="social-icon-btn" aria-label="Facebook" title="Facebook">FB</a>
          <?php endif; ?>
          <?php if ($social_insta !== '#'): ?>
            <a href="<?php echo h($social_insta); ?>" target="_blank" rel="noopener" class="social-icon-btn" aria-label="Instagram" title="Instagram">IG</a>
          <?php endif; ?>
          <?php if ($social_linkedin !== '#'): ?>
            <a href="<?php echo h($social_linkedin); ?>" target="_blank" rel="noopener" class="social-icon-btn" aria-label="LinkedIn" title="LinkedIn">LN</a>
          <?php endif; ?>
          <?php if (!empty($social_youtube) && $social_youtube !== '#'): ?>
            <a href="<?php echo h($social_youtube); ?>" target="_blank" rel="noopener" class="social-icon-btn" aria-label="YouTube" title="YouTube">YT</a>
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

  <!-- Sticky Enquiry / Book a Free Demo CTA on Scroll -->
  <div class="callback-floating-widget" id="stickyCallbackWidget">
    <div class="callback-widget-pill">Free Consultation</div>
    <div class="callback-widget-card" onclick="openCallbackModal()">
      <div class="callback-widget-text">
        <span class="callback-widget-sub">Start Learning Today</span>
        <h4 class="callback-widget-title">Book a Free Demo</h4>
      </div>
      <div class="callback-widget-icon-box">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><polyline points="8 14 10 16 16 10"></polyline></svg>
      </div>
    </div>
  </div>

  <!-- Global Floating WhatsApp & Call CTA -->
  <div class="floating-contact-actions" id="floatingContactActions">
    <a href="tel:<?php echo h($phone); ?>" class="floating-action-btn floating-call-btn" title="Call Us: +91 <?php echo h($phone); ?>" aria-label="Call Zuvio Global School">
      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
      </svg>
      <span class="floating-action-tooltip">Call: +91 <?php echo h($phone); ?></span>
    </a>

    <a href="https://wa.me/91<?php echo h($whatsapp); ?>" target="_blank" rel="noopener" class="floating-action-btn floating-wa-btn" title="Chat on WhatsApp" aria-label="Chat with Zuvio Global School on WhatsApp">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
      </svg>
      <span class="floating-action-tooltip">Chat on WhatsApp</span>
    </a>
  </div>

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

  <?php
  // Fetch Active Announcements
  $announcements = [];
  if (isset($db) && $db) {
      try {
          $stmt = $db->query("SELECT * FROM `announcements` WHERE `is_active` = 1 ORDER BY `sort_order` ASC, `id` DESC");
          $announcements = $stmt->fetchAll();
      } catch (Exception $e) {
          error_log("[Announcements Footer Error] " . $e->getMessage());
      }
  }

  // Fallback if DB is offline or empty
  if (empty($announcements)) {
      $announcements = [
          [
              'id' => 1,
              'text' => 'Admissions ongoing for Mid-Session 2026–27',
              'button_text' => 'Apply Now',
              'button_url' => '/contact'
          ],
          [
              'id' => 2,
              'text' => 'Unlock Future Skills: Enroll for Coding & AI Workshops at Zuvio Global School.',
              'button_text' => 'Enquire Now',
              'button_url' => '/contact'
          ],
          [
              'id' => 3,
              'text' => 'Zuvio is now an Oxford Quality Partner, delivering internationally benchmarked educational materials.',
              'button_text' => 'Learn More',
              'button_url' => '/about'
          ]
      ];
  }
  ?>

  <!-- Bottom Floating Admissions Announcement Bar -->
  <?php if (!empty($announcements)): ?>
  <div id="admissionsAnnouncementBar" class="admissions-announcement-bar">
    <div class="announcement-slider-container" style="flex-grow: 1; position: relative;">
      <?php foreach ($announcements as $index => $ann): ?>
        <?php 
          $ann_text = $ann['text'];
          $ann_text = preg_replace('/\s*\|\s*Admissions open for Children with Learning Disabilities\.?/i', '', $ann_text);
          $ann_text = str_replace(['2026–28', '2026-28'], '2026–27', $ann_text);
        ?>
        <div class="announcement-slide <?php echo $index === 0 ? 'active' : ''; ?>" style="display: <?php echo $index === 0 ? 'flex' : 'none'; ?>; align-items: center; justify-content: center; width: 100%; transition: opacity 0.5s ease; opacity: <?php echo $index === 0 ? '1' : '0'; ?>;">
          <div class="announcement-content">
            <span class="announcement-badge">Announcements</span>
            <span class="announcement-text"><?php echo h($ann_text); ?></span>
            <?php if (!empty($ann['button_text'])): ?>
              <a href="<?php echo h($ann['button_url'] ?: '/contact'); ?>" class="announcement-btn"><?php echo h($ann['button_text']); ?></a>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <button class="announcement-close" onclick="closeAdmissionsBar()">&times;</button>
  </div>
  <?php endif; ?>

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
    .announcement-slider-container {
      display: flex;
      align-items: center;
      justify-content: center;
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
    // Global FAQ accordion toggle handler
    if (typeof window.toggleFaq !== 'function') {
      window.toggleFaq = function(btn) {
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
      };
    }

    function closeAdmissionsBar() {
      const bar = document.getElementById('admissionsAnnouncementBar');
      if (bar) {
        bar.style.transform = 'translateY(100%)';
        setTimeout(() => bar.style.display = 'none', 300);
      }
    }

    // Announcement slider fade loop
    document.addEventListener('DOMContentLoaded', () => {
      const slides = document.querySelectorAll('.announcement-slide');
      if (slides.length > 1) {
        let currentSlide = 0;
        setInterval(() => {
          const activeSlide = slides[currentSlide];
          activeSlide.style.opacity = '0';
          setTimeout(() => {
            activeSlide.style.display = 'none';
            
            currentSlide = (currentSlide + 1) % slides.length;
            const nextSlide = slides[currentSlide];
            nextSlide.style.display = 'flex';
            // Force reflow
            nextSlide.offsetHeight;
            nextSlide.style.opacity = '1';
          }, 500);
        }, 6000); // Rotates announcements every 6 seconds
      }
    });

    // Sticky "Book a Free Demo" CTA on scroll
    (function() {
      const stickyWidget = document.getElementById('stickyCallbackWidget');
      if (!stickyWidget) return;
      function handleScroll() {
        if (window.scrollY > 300) {
          stickyWidget.classList.add('is-sticky');
        } else {
          stickyWidget.classList.remove('is-sticky');
        }
      }
      window.addEventListener('scroll', handleScroll, { passive: true });
      handleScroll();
    })();
  </script>
</body>
</html>
