<?php
// Zuvio Global School - 500 Page Template
$page_slug = '500';
// Avoid circular header injection if loaded from catch block where DB is offline
if (empty($seo)) {
    $seo = [
        'seo_title' => '500 Server Error | Zuvio Global School',
        'meta_description' => 'Database connection offline.',
        'canonical_url' => 'https://zuvioglobalschool.com/',
        'og_title' => '500 Server Error',
        'og_description' => 'Server Error',
        'og_image' => '/assets/images/logo.png',
        'index_status' => 'noindex, nofollow'
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($seo['seo_title'], ENT_QUOTES, 'UTF-8'); ?></title>
  <link rel="stylesheet" href="/css/main.css">
</head>
<body>

<section class="section text-center" style="padding: 8rem 0; background-color: var(--color-surface-blue); min-height: 100vh; display: flex; align-items: center;">
  <div class="container" style="max-width: 600px; margin: auto;">
    <h1 style="font-size: 5rem; color: var(--color-gold); margin-bottom: 1rem;">500</h1>
    <h2 style="font-size: 2rem; color: var(--color-navy); margin-bottom: 1.5rem;">Connection Interrupted</h2>
    <p style="color: var(--color-muted); font-size: 1.1rem; margin-bottom: 2.5rem; line-height: 1.8;">
      <?php echo isset($error_detail) ? htmlspecialchars($error_detail, ENT_QUOTES, 'UTF-8') : "Our database service is temporarily offline."; ?><br>
      Please refresh the page or try again in a few moments.
    </p>
    <a href="/" class="btn btn-primary" style="padding: 1rem 2.5rem;">Retry Connection</a>
  </div>
</section>

</body>
</html>
