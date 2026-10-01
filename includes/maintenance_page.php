<?php
// Zuvio Global School - Scheduled Maintenance / Coming Soon Public Display
http_response_code(503);
header('Retry-After: 3600');

$m_title = get_setting('maintenance_title', 'We’re Upgrading Our Learning Experience');
$m_message = get_setting('maintenance_message', 'Zuvio Global School website is currently undergoing scheduled platform upgrades. We will be back online shortly. For admissions assistance or immediate inquiries, our counseling desk is available via phone and WhatsApp.');
$m_logo = get_setting('logo_url', '/assets/images/logo.png');
$m_favicon = get_setting('favicon_url', '/assets/images/logo.png');
$m_phone = get_setting('phone', '7827262956');
$m_email = get_setting('general_email', 'info@zuvioglobalschool.com');
$m_wa = get_setting('whatsapp', '7827262956');
$m_wa_enabled = (string)get_setting('whatsapp_enabled', '1') === '1';
$social_fb = get_setting('social_facebook', 'https://www.facebook.com/share/1XsYWDm3rt/');
$social_insta = get_setting('social_instagram', 'https://www.instagram.com/thezuvio/');
$social_linkedin = get_setting('social_linkedin', 'https://www.linkedin.com/company/zuvio-global-school/');
$social_youtube = get_setting('social_youtube', 'https://www.youtube.com/@zuvioglobalschool');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Under Maintenance — Zuvio Global School</title>
  <link rel="icon" type="image/png" href="<?php echo h($m_favicon); ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">
  <style>
    :root {
      --color-navy: #062B63;
      --color-deep-navy: #031B42;
      --color-gold: #D9A441;
      --color-teal: #0D9488;
      --color-white: #FFFFFF;
      --color-muted: #64748B;
      --color-light-bg: #F8FAFC;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      background: radial-gradient(circle at 15% 20%, rgba(13, 148, 136, 0.15) 0%, transparent 40%),
                  radial-gradient(circle at 85% 80%, rgba(217, 164, 65, 0.12) 0%, transparent 45%),
                  linear-gradient(135deg, #031B42 0%, #062B63 60%, #083882 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 2rem 1.5rem;
      color: #FFFFFF;
      text-align: center;
    }
    .maintenance-card {
      background: rgba(255, 255, 255, 0.96);
      backdrop-filter: blur(12px);
      border-radius: 24px;
      max-width: 680px;
      width: 100%;
      padding: 3.5rem 2.5rem;
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.35);
      border: 1px solid rgba(255, 255, 255, 0.4);
      color: #1E293B;
      position: relative;
    }
    .logo-wrap {
      margin-bottom: 2rem;
    }
    .logo-wrap img {
      max-height: 72px;
      width: auto;
      object-fit: contain;
    }
    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      background: #FEF3C7;
      color: #92400E;
      border: 1px solid #FCD34D;
      padding: 0.35rem 1rem;
      border-radius: 999px;
      font-size: 0.82rem;
      font-weight: 700;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      margin-bottom: 1.5rem;
    }
    .pulse-dot {
      width: 8px;
      height: 8px;
      background: #D97706;
      border-radius: 50%;
      box-shadow: 0 0 0 0 rgba(217, 119, 6, 0.7);
      animation: pulse 1.8s infinite;
    }
    @keyframes pulse {
      0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(217, 119, 6, 0.7); }
      70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(217, 119, 6, 0); }
      100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(217, 119, 6, 0); }
    }
    h1 {
      font-family: 'Playfair Display', Georgia, serif;
      font-size: 2.1rem;
      font-weight: 700;
      color: var(--color-navy);
      line-height: 1.25;
      margin-bottom: 1.25rem;
    }
    p.lead-desc {
      font-size: 1rem;
      color: var(--color-muted);
      line-height: 1.65;
      margin-bottom: 2.25rem;
    }
    .contact-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
      text-align: left;
    }
    .contact-box {
      background: #F8FAFC;
      border: 1px solid #E2E8F0;
      padding: 1.2rem;
      border-radius: 12px;
      text-decoration: none;
      color: inherit;
      display: block;
      transition: all 0.2s ease;
    }
    .contact-box:hover {
      border-color: var(--color-teal);
      transform: translateY(-2px);
      box-shadow: 0 8px 18px rgba(0, 0, 0, 0.05);
    }
    .contact-label {
      font-size: 0.75rem;
      text-transform: uppercase;
      font-weight: 700;
      color: var(--color-muted);
      letter-spacing: 0.05em;
      margin-bottom: 0.35rem;
    }
    .contact-val {
      font-size: 0.95rem;
      font-weight: 600;
      color: var(--color-navy);
      word-break: break-all;
    }
    .social-links {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-top: 1.5rem;
      padding-top: 1.5rem;
      border-top: 1px solid #E2E8F0;
    }
    .social-btn {
      color: var(--color-navy);
      text-decoration: none;
      font-size: 0.85rem;
      font-weight: 600;
      transition: color 0.2s;
    }
    .social-btn:hover {
      color: var(--color-gold);
    }
    .admin-link {
      margin-top: 2rem;
      font-size: 0.82rem;
      color: rgba(255, 255, 255, 0.65);
    }
    .admin-link a {
      color: var(--color-gold);
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="maintenance-card">
    <div class="logo-wrap">
      <img src="<?php echo h($m_logo); ?>" alt="Zuvio Global School">
    </div>

    <div class="status-badge">
      <span class="pulse-dot"></span>
      Scheduled Maintenance & Upgrades
    </div>

    <h1><?php echo h($m_title); ?></h1>

    <p class="lead-desc">
      <?php echo nl2br(h($m_message)); ?>
    </p>

    <div class="contact-grid">
      <a href="tel:+91<?php echo preg_replace('/[^0-9]/', '', $m_phone); ?>" class="contact-box">
        <div class="contact-label">📞 Admissions Desk</div>
        <div class="contact-val">+91 <?php echo h($m_phone); ?></div>
      </a>
      <a href="mailto:<?php echo h($m_email); ?>" class="contact-box">
        <div class="contact-label">✉️ General Queries</div>
        <div class="contact-val"><?php echo h($m_email); ?></div>
      </a>
      <?php if ($m_wa_enabled && !empty($m_wa)): ?>
      <a href="https://wa.me/91<?php echo preg_replace('/[^0-9]/', '', $m_wa); ?>" target="_blank" class="contact-box" style="border-color: #86EFAC; background: #F0FDF4;">
        <div class="contact-label" style="color: #166534;">💬 Instant WhatsApp</div>
        <div class="contact-val" style="color: #15803D;">Chat with Counselor</div>
      </a>
      <?php endif; ?>
    </div>

    <div class="social-links">
      <?php if (!empty($social_fb)): ?>
        <a href="<?php echo h($social_fb); ?>" target="_blank" rel="noopener" class="social-btn">Facebook</a>
      <?php endif; ?>
      <?php if (!empty($social_insta)): ?>
        <a href="<?php echo h($social_insta); ?>" target="_blank" rel="noopener" class="social-btn">Instagram</a>
      <?php endif; ?>
      <?php if (!empty($social_linkedin)): ?>
        <a href="<?php echo h($social_linkedin); ?>" target="_blank" rel="noopener" class="social-btn">LinkedIn</a>
      <?php endif; ?>
      <?php if (!empty($social_youtube)): ?>
        <a href="<?php echo h($social_youtube); ?>" target="_blank" rel="noopener" class="social-btn">YouTube</a>
      <?php endif; ?>
    </div>
  </div>

  <div class="admin-link">
    Are you a faculty member or administrator? <a href="/admin/login.php">Sign in to Admin Panel</a>
  </div>

</body>
</html>
