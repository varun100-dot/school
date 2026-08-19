<?php
// Zuvio Global School - 404 Page Template
$page_slug = '404';
include_once dirname(__FILE__) . '/../includes/header.php';
?>

<section class="section text-center" style="padding: 8rem 0; background-color: var(--color-surface-blue);">
  <div class="container" style="max-width: 600px;">
    <h1 style="font-size: 5rem; color: var(--color-gold); margin-bottom: 1rem;">404</h1>
    <h2 style="font-size: 2rem; color: var(--color-navy); margin-bottom: 1.5rem;">Page Not Found</h2>
    <p style="color: var(--color-muted); font-size: 1.1rem; margin-bottom: 2.5rem; line-height: 1.8;">
      The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
    </p>
    <a href="/" class="btn btn-primary" style="padding: 1rem 2.5rem;">Go Back Home</a>
  </div>
</section>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
