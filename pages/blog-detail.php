<?php
// Zuvio Global School - Blog Detail Template
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

$post = null;
if ($db && isset($blog_slug)) {
    try {
        $stmt = $db->prepare("
            SELECT b.*, c.name as category_name 
            FROM `blogs` b 
            LEFT JOIN `blog_categories` c ON c.id = b.category_id 
            WHERE b.slug = ? AND b.status = 'published' LIMIT 1
        ");
        $stmt->execute([$blog_slug]);
        $post = $stmt->fetch();
    } catch (Exception $e) {
        error_log("[Blog Detail Error] " . $e->getMessage());
    }
}

// If post is missing, redirect to 404
if (!$post) {
    header('HTTP/1.1 404 Not Found');
    include_once dirname(__FILE__) . '/404.php';
    exit;
}

// Override default SEO metrics for header.php injection
$seo = [
    'seo_title' => $post['seo_title'] ?: ($post['title'] . ' | Zuvio Global School'),
    'meta_description' => $post['meta_description'] ?: $post['excerpt'],
    'canonical_url' => 'https://zuvioglobalschool.com/blogs/' . $post['slug'],
    'og_title' => $post['title'],
    'og_description' => $post['excerpt'],
    'og_image' => $post['featured_image'] ?: '/assets/images/logo.png',
    'index_status' => 'index, follow'
];

$page_slug = 'blogs';
include_once dirname(__FILE__) . '/../includes/header.php';
?>

<!-- Article Layout -->
<article style="background-color: var(--color-bg); min-height: 80vh; font-family: var(--font-secondary); padding-bottom: 5rem;">
  
  <!-- Article Header Banner -->
  <header style="background-color: var(--color-navy-dark); color: #FFFFFF; padding: 5rem 0; position: relative;">
    <div class="container" style="max-width: 800px; text-align: center;">
      <span style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; color: var(--color-gold); letter-spacing: 1.5px; display: block; margin-bottom: 1rem;">
        <?php echo h($post['category_name'] ?: 'School News'); ?>
      </span>
      <h1 style="font-size: 2.5rem; font-family: var(--font-primary); color: #FFFFFF; margin-bottom: 1.5rem; line-height: 1.2;">
        <?php echo h($post['title']); ?>
      </h1>
      
      <div style="display: flex; justify-content: center; gap: 1.5rem; font-size: 0.85rem; color: #E2E8F0; border-top: 1px solid rgba(255,255,255,0.15); padding-top: 1.5rem; margin-top: 1.5rem;">
        <span><strong>Published:</strong> <?php echo date('M d, Y', strtotime($post['publish_date'])); ?></span>
        <span><strong>Author:</strong> <?php echo h($post['author'] ?: 'Zuvio Editorial'); ?></span>
      </div>
    </div>
  </header>

  <!-- Article Contents -->
  <div class="container" style="max-width: 800px; margin-top: -3rem; position: relative; z-index: 10;">
    
    <!-- Featured Image -->
    <?php if (!empty($post['featured_image'])): ?>
      <div style="border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-lg); height: 420px; background-color: var(--color-navy); margin-bottom: 3rem;">
        <img src="<?php echo h($post['featured_image']); ?>" alt="<?php echo h($post['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
      </div>
    <?php endif; ?>

    <!-- Main Copy -->
    <div style="background-color: #FFFFFF; padding: 3rem 2.5rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); border: 1px solid var(--color-border); color: var(--color-text); font-size: 1.05rem; line-height: 1.8;">
      <?php echo nl2br($post['content']); ?>
      
      <!-- Author Signature -->
      <div style="margin-top: 3.5rem; padding-top: 2rem; border-top: 1px solid var(--color-border); display: flex; align-items: center; gap: 1rem;">
        <div style="width: 48px; height: 48px; border-radius: 50%; background-color: var(--color-navy); color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-weight: 700; font-family: var(--font-primary);">
          Z
        </div>
        <div>
          <strong style="color: var(--color-navy); display: block; font-size: 0.95rem;"><?php echo h($post['author'] ?: 'Zuvio Editorial Team'); ?></strong>
          <span style="font-size: 0.8rem; color: var(--color-muted);"><?php echo h($post['author_designation'] ?: 'Academic Content Contributor'); ?></span>
        </div>
      </div>
    </div>

    <!-- Back list button -->
    <div style="margin-top: 2.5rem; text-align: center;">
      <a href="/blogs" class="btn btn-outline" style="padding: 0.75rem 2rem;">&larr; Back to Listings</a>
    </div>

  </div>
</article>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
