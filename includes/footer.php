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
if (empty($social_linkedin) || strpos($social_linkedin, 'admin/dashboard') !== false || strpos($social_linkedin, '142914253') !== false) {
    $social_linkedin = 'https://www.linkedin.com/company/zuvio-global-school/';
}
$social_youtube = get_setting('social_youtube', 'https://www.youtube.com/@zuvioglobalschool');
?>

  <!-- Footer Section -->
  <footer class="site-footer">
    <div class="footer-container">
      
      <!-- Column 1: Brand Anchor & Accreditation -->
      <div class="footer-col brand-col">
        <a href="/" title="Zuvio Global School">
          <picture>
            <source srcset="/assets/images/logo.webp" type="image/webp">
            <img src="<?php echo h($logo_path); ?>" alt="Zuvio Global School" class="footer-logo" loading="lazy" width="160" height="58">
          </picture>
        </a>
        <p class="brand-tagline">Learning Beyond Boundaries</p>
        <p class="footer-accreditation-text">
          <strong>Affiliation No: IA 4883 &bull; IAO Accredited &bull; ISSO Member</strong><br>
          Empowering learners worldwide through interactive, personalized, and globally accredited online education from Kindergarten to Grade 8th.
        </p>
        
        <div class="footer-social-wrapper">
          <span class="footer-social-title">Connect With Us</span>
          <div class="footer-social-links">
            <?php if ($social_fb !== '#'): ?>
              <a href="<?php echo h($social_fb); ?>" target="_blank" rel="noopener" class="footer-social-icon social-facebook" aria-label="Facebook" title="Follow us on Facebook">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
              </a>
            <?php endif; ?>
            <?php if ($social_insta !== '#'): ?>
              <a href="<?php echo h($social_insta); ?>" target="_blank" rel="noopener" class="footer-social-icon social-instagram" aria-label="Instagram" title="Follow us on Instagram">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
              </a>
            <?php endif; ?>
            <?php if ($social_linkedin !== '#'): ?>
              <a href="<?php echo h($social_linkedin); ?>" target="_blank" rel="noopener" class="footer-social-icon social-linkedin" aria-label="LinkedIn" title="Connect on LinkedIn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
              </a>
            <?php endif; ?>
            <?php if (!empty($social_youtube) && $social_youtube !== '#'): ?>
              <a href="<?php echo h($social_youtube); ?>" target="_blank" rel="noopener" class="footer-social-icon social-youtube" aria-label="YouTube" title="Subscribe on YouTube">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Column 2: About Us -->
      <div class="footer-col links-col">
        <h4 class="footer-title">About Zuvio</h4>
        <ul class="footer-links-list">
          <li><a href="/about-zuvio" class="footer-link">About Zuvio</a></li>
          <li><a href="/our-team" class="footer-link">Leadership & Team</a></li>
          <li><a href="/founders-message" class="footer-link">Founder’s Message</a></li>
          <li><a href="/affiliations-accreditations" class="footer-link">Affiliations & Accreditations</a></li>
          <li><a href="/contact" class="footer-link">Contact & Enquiries</a></li>
        </ul>
      </div>

      <!-- Column 3: Academics -->
      <div class="footer-col links-col">
        <h4 class="footer-title">Academics</h4>
        <ul class="footer-links-list">
          <li><a href="/curriculum" class="footer-link">Curriculum Framework</a></li>
          <li><a href="/technology" class="footer-link">Technology & AI Labs</a></li>
          <li><a href="/special-education" class="footer-link">Special Education</a></li>
          <li><a href="/electives" class="footer-link">Electives & Languages</a></li>
          <li><a href="/nep-2020" class="footer-link">NEP 2020 Guidelines</a></li>
          <li><a href="/resources" class="footer-link">Academic Resources</a></li>
        </ul>
      </div>

      <!-- Column 4: Admissions & Beyond -->
      <div class="footer-col links-col">
        <h4 class="footer-title">Admissions & Beyond</h4>
        <ul class="footer-links-list">
          <li><a href="/admissions/enrol-now" class="footer-link">Enrol Now</a></li>
          <li><a href="/admissions/eligibility" class="footer-link">Eligibility Criteria</a></li>
          <li><a href="/admissions/calendar" class="footer-link">Academic Calendar</a></li>
          <li><a href="/admissions/fees" class="footer-link">Fee Structure</a></li>
          <li><a href="/faq" class="footer-link">Frequently Asked Questions</a></li>
          <li><a href="/beyond/co-curricular" class="footer-link">Co-Curricular & Clubs</a></li>
          <li><a href="/beyond/student-achievers" class="footer-link">Student Achievers</a></li>
          <li><a href="/beyond/virtual-classroom" class="footer-link">Virtual Classroom</a></li>
        </ul>
      </div>

      <!-- Column 5: Contact Info -->
      <div class="footer-col contact-col">
        <h4 class="footer-title">Contact Us</h4>
        <div class="footer-contact-item">
          <div class="contact-info-content">
            <strong>Campus / Office:</strong>
            <span>B-09, Lower Ground Floor, ITL Twin Tower, Netaji Subhash Place, Pitampura, Delhi - 110034</span>
          </div>
        </div>

        <div class="footer-contact-item">
          <div class="contact-info-content">
            <strong>Phone / Call:</strong>
            <a href="tel:+91<?php echo preg_replace('/[^0-9]/', '', $phone); ?>" class="contact-anchor">+91 <?php echo h($phone); ?></a>
          </div>
        </div>

        <div class="footer-contact-item">
          <div class="contact-info-content">
            <strong>WhatsApp:</strong>
            <a href="https://wa.me/91<?php echo h($whatsapp); ?>" target="_blank" rel="noopener" class="contact-anchor" style="color: #16A34A; font-weight: 600;">+91 <?php echo h($whatsapp); ?></a>
          </div>
        </div>

        <div class="footer-contact-item">
          <div class="contact-info-content">
            <strong>Email:</strong>
            <a href="mailto:<?php echo h($email); ?>" class="contact-anchor"><?php echo h($email); ?></a>
          </div>
        </div>

        <div class="footer-contact-item">
          <div class="contact-info-content">
            <strong>Office Hours:</strong>
            <span><?php echo h($office_timings); ?></span>
          </div>
        </div>

        <button type="button" onclick="openCallbackModal()" class="btn btn-outline footer-callback-btn">Book a Demo</button>
      </div>

    </div>

    <!-- Sub-Footer Bottom Bar -->
    <div class="footer-bottom-wrap">
      <div class="footer-bottom-container">
        <p class="copyright-text"><?php echo h($copyright); ?></p>
        <div class="footer-bottom-links">
          <a href="/contact">Admissions Enquiry</a>
          <span class="sep">&bull;</span>
          <a href="/faq">FAQs</a>
          <span class="sep">&bull;</span>
          <a href="/affiliations-accreditations">Accreditations</a>
          <span class="sep">&bull;</span>
          <a href="javascript:void(0)" onclick="openCallbackModal()">Request Callback</a>
        </div>
      </div>
    </div>
  </footer>

  <style>
    /* Footer Layout Stylings */
    .site-footer {
      background-color: var(--color-surface);
      border-top: 1px solid var(--color-border);
      padding: 4.5rem 0 0 0;
      color: var(--color-text);
      font-family: var(--font-secondary);
    }
    .footer-container {
      max-width: var(--max-width);
      margin: 0 auto;
      padding: 0 1.5rem;
      display: grid;
      grid-template-columns: 1.35fr 0.95fr 1fr 1.1fr 1.25fr;
      gap: 2.25rem;
    }
    .footer-col {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }
    .footer-logo {
      height: 70px;
      width: auto;
      object-fit: contain;
      display: block;
      transition: height 0.3s ease;
    }
    .brand-tagline {
      font-size: 1rem;
      font-weight: 700;
      color: var(--color-navy);
      margin-top: 0.25rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .footer-accreditation-text {
      font-size: 0.85rem;
      color: var(--color-muted);
      line-height: 1.55;
    }
    .footer-social-wrapper {
      margin-top: 0.5rem;
    }
    .footer-social-title {
      display: block;
      font-size: 0.85rem;
      font-weight: 700;
      color: var(--color-navy);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 0.5rem;
    }
    .footer-social-links {
      display: flex;
      gap: 0.65rem;
      align-items: center;
    }
    .footer-social-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 44px;
      height: 44px;
      min-width: 44px;
      min-height: 44px;
      border-radius: 50%;
      background-color: #FFFFFF;
      border: 1.5px solid var(--color-border);
      color: var(--color-navy);
      box-shadow: 0 2px 6px rgba(6, 43, 99, 0.06);
      transition: all 0.25s ease;
      text-decoration: none;
    }
    .footer-social-icon:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 14px rgba(6, 43, 99, 0.16);
    }
    .footer-social-icon.social-facebook:hover {
      background-color: #1877F2;
      border-color: #1877F2;
      color: #FFFFFF;
    }
    .footer-social-icon.social-instagram:hover {
      background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
      border-color: transparent;
      color: #FFFFFF;
    }
    .footer-social-icon.social-linkedin:hover {
      background-color: #0A66C2;
      border-color: #0A66C2;
      color: #FFFFFF;
    }
    .footer-social-icon.social-youtube:hover {
      background-color: #FF0000;
      border-color: #FF0000;
      color: #FFFFFF;
    }

    .footer-title {
      font-family: var(--font-primary);
      font-size: 1.15rem;
      color: var(--color-navy);
      font-weight: 700;
      position: relative;
      padding-bottom: 0.5rem;
      margin-bottom: 0.25rem;
    }
    .footer-title::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 32px;
      height: 2px;
      background-color: var(--color-gold);
    }
    .footer-links-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 0.25rem;
    }
    .footer-link {
      font-size: 0.9rem;
      color: var(--color-text);
      transition: color var(--transition-fast), padding-left var(--transition-fast);
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      min-height: 38px;
      padding: 0.2rem 0;
      line-height: 1.4;
    }
    .footer-link:hover {
      color: var(--color-gold);
      padding-left: 4px;
    }

    .footer-contact-item {
      display: flex;
      gap: 0.5rem;
      font-size: 0.88rem;
      line-height: 1.5;
    }
    .contact-info-content {
      display: flex;
      flex-direction: column;
      gap: 0.15rem;
    }
    .contact-info-content strong {
      color: var(--color-navy);
      font-size: 0.82rem;
      text-transform: uppercase;
      letter-spacing: 0.4px;
    }
    .contact-info-content span,
    .contact-info-content a {
      color: var(--color-text);
      font-size: 0.88rem;
    }
    .contact-anchor {
      color: var(--color-navy);
      font-weight: 600;
      transition: color var(--transition-fast);
      text-decoration: none;
    }
    .contact-anchor:hover {
      color: var(--color-gold);
    }
    .footer-callback-btn {
      margin-top: 0.5rem;
      font-size: 0.85rem;
      padding: 0.5rem 1.25rem;
      border-color: var(--color-navy);
      color: var(--color-navy);
      border-radius: 4px;
      font-weight: 700;
      cursor: pointer;
      width: fit-content;
      transition: all 0.2s ease;
    }
    .footer-callback-btn:hover {
      background-color: var(--color-navy);
      color: #FFFFFF;
    }

    /* Sub-Footer Bottom Bar */
    .footer-bottom-wrap {
      margin-top: 3.5rem;
      border-top: 1px solid var(--color-border);
      background-color: rgba(6, 43, 99, 0.02);
      padding: 1.25rem 0;
    }
    .footer-bottom-container {
      max-width: var(--max-width);
      margin: 0 auto;
      padding: 0 1.5rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1rem;
    }
    .copyright-text {
      font-size: 0.82rem;
      color: var(--color-muted);
      margin: 0;
    }
    .footer-bottom-links {
      display: flex;
      gap: 0.65rem;
      align-items: center;
      font-size: 0.82rem;
    }
    .footer-bottom-links a {
      color: var(--color-muted);
      text-decoration: none;
      transition: color 0.2s ease;
    }
    .footer-bottom-links a:hover {
      color: var(--color-navy);
    }
    .footer-bottom-links .sep {
      color: var(--color-border);
    }

    /* Responsive Grid */
    @media (max-width: 1100px) {
      .footer-container {
        grid-template-columns: repeat(3, 1fr);
        gap: 2.25rem;
      }
      .brand-col {
        grid-column: span 3;
      }
    }
    @media (max-width: 768px) {
      .footer-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
      }
      .brand-col {
        grid-column: span 2;
      }
      .contact-col {
        grid-column: span 2;
      }
      .footer-bottom-container {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
      }
    }
    @media (max-width: 520px) {
      .footer-container {
        grid-template-columns: 1fr;
        gap: 2rem;
      }
      .brand-col, .contact-col {
        grid-column: span 1;
      }
    }
  </style>

  <!-- Sticky Enquiry / Book a Free Demo CTA on Scroll -->
  <div class="callback-floating-widget" id="stickyCallbackWidget" onclick="openCallbackModal()" role="button" aria-label="Book a Free Demo">
    <div class="sticky-demo-badge">FREE</div>
    <div class="sticky-demo-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><polyline points="8 14 10 16 16 10"></polyline></svg>
    </div>
    <div class="sticky-demo-text">
      <span class="sticky-demo-label">Book a Free Demo</span>
      <span class="sticky-demo-sub">Talk to Advisor &rarr;</span>
    </div>
  </div>

  <!-- Global Floating WhatsApp & Call CTA -->
  <div class="floating-contact-actions" id="floatingContactActions">
    <a href="tel:+91<?php echo preg_replace('/[^0-9]/', '', $phone); ?>" class="floating-action-btn floating-call-btn" title="Call Us: +91 <?php echo h($phone); ?>" aria-label="Call Zuvio Global School">
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
    <button class="announcement-close" onclick="closeAdmissionsBar()" aria-label="Close Announcements" title="Close Announcements">&times;</button>
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
      padding: 0.65rem 1.5rem;
      box-shadow: 0 -4px 20px rgba(6, 43, 99, 0.15);
      font-family: var(--font-secondary);
      transition: transform 0.3s ease;
      box-sizing: border-box;
    }
    .announcement-slider-container {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-grow: 1;
      min-width: 0;
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
      flex-shrink: 0;
    }
    .announcement-text {
      font-size: 0.92rem;
      font-weight: 500;
      letter-spacing: 0.25px;
    }
    .announcement-btn {
      background-color: var(--color-teal);
      color: #fff;
      padding: 0.45rem 1.15rem;
      border-radius: var(--radius-sm);
      font-size: 0.82rem;
      font-weight: 600;
      text-decoration: none;
      transition: background-color 0.2s ease;
      white-space: nowrap;
      min-height: 36px;
      display: inline-flex;
      align-items: center;
    }
    .announcement-btn:hover {
      background-color: #0b9ba9;
    }
    .announcement-close {
      background: none;
      border: none;
      color: var(--color-white);
      font-size: 1.8rem;
      cursor: pointer;
      opacity: 0.85;
      transition: opacity 0.2s;
      padding: 0;
      width: 44px;
      height: 44px;
      min-width: 44px;
      min-height: 44px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      line-height: 1;
      border-radius: 50%;
      flex-shrink: 0;
    }
    .announcement-close:hover {
      opacity: 1;
      background: rgba(255, 255, 255, 0.1);
    }
    body:not(.announcement-bar-closed) {
      padding-bottom: 60px;
    }
    @media (max-width: 850px) {
      .admissions-announcement-bar {
        padding: 0.45rem 0.75rem;
      }
      .announcement-content {
        gap: 0.4rem;
        justify-content: center;
        text-align: center;
      }
      .announcement-text {
        font-size: 0.8rem;
        width: 100%;
        line-height: 1.35;
      }
      .announcement-badge {
        font-size: 0.68rem;
        padding: 0.2rem 0.6rem;
      }
      .announcement-btn {
        padding: 0.35rem 0.85rem;
        font-size: 0.75rem;
        min-height: 32px;
      }
    }
    @media (max-width: 768px) {
      body:not(.announcement-bar-closed) {
        padding-bottom: 75px;
      }
    }
  </style>

  <script>
    // Check if announcement was previously dismissed
    (function() {
      try {
        if (localStorage.getItem('zuvio_announcement_dismissed') === '1') {
          const bar = document.getElementById('admissionsAnnouncementBar');
          if (bar) bar.style.display = 'none';
          document.body.classList.add('announcement-bar-closed');
        }
      } catch(e) {}
    })();

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
        setTimeout(() => {
          bar.style.display = 'none';
          document.body.classList.add('announcement-bar-closed');
          try {
            localStorage.setItem('zuvio_announcement_dismissed', '1');
          } catch(e) {}
        }, 300);
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
