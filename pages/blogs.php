<?php
// Zuvio Global School - Blogs Listing Template
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

$category_filter = isset($_GET['category']) ? trim($_GET['category']) : '';

// Fetch Categories
$categories = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM `blog_categories` ORDER BY `name` ASC");
        $categories = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("[Blog Categories Error] " . $e->getMessage());
    }
}

// Fetch Published Blogs
$posts = [];
if ($db) {
    try {
        if ($category_filter) {
            $stmt = $db->prepare("
                SELECT b.*, c.name as category_name 
                FROM `blogs` b 
                JOIN `blog_categories` c ON c.id = b.category_id 
                WHERE b.status = 'published' AND c.slug = ? 
                ORDER BY b.publish_date DESC
            ");
            $stmt->execute([$category_filter]);
        } else {
            $stmt = $db->prepare("
                SELECT b.*, c.name as category_name 
                FROM `blogs` b 
                LEFT JOIN `blog_categories` c ON c.id = b.category_id 
                WHERE b.status = 'published' 
                ORDER BY b.publish_date DESC
            ");
            $stmt->execute();
        }
        $posts = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("[Blog Posts Error] " . $e->getMessage());
    }
}

$page_slug = 'blogs';
include_once dirname(__FILE__) . '/../includes/header.php';
?>

<!-- Section Header -->
<section class="section" style="background-color: var(--color-surface-blue); border-bottom: 1px solid var(--color-border); padding: 5rem 0 3rem 0;">
  <div class="container text-center">
    <span style="font-size: 0.85rem; font-weight: 600; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">School Articles</span>
    <h1 style="font-size: 2.75rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">Blogs & Insights</h1>
  </div>
</section>

<!-- Section Content -->
<section class="section" style="background-color: var(--color-white); min-height: 50vh;">
  <div class="container">
    
    <!-- Category Filter Bar -->
    <?php if (!empty($categories)): ?>
      <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; justify-content: center; margin-bottom: 3.5rem;" class="blog-filters">
        <a href="/blogs" class="btn <?php echo empty($category_filter) ? 'btn-primary' : 'btn-outline'; ?>" style="padding: 0.5rem 1.25rem; font-size: 0.85rem;">All Articles</a>
        <?php foreach ($categories as $cat): ?>
          <a href="/blogs?category=<?php echo h($cat['slug']); ?>" class="btn <?php echo $category_filter === $cat['slug'] ? 'btn-primary' : 'btn-outline'; ?>" style="padding: 0.5rem 1.25rem; font-size: 0.85rem;">
            <?php echo h($cat['name']); ?>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- Blogs Grid / Empty State -->
    <?php if (!empty($posts)): ?>
      <div class="grid-3">
        <?php foreach ($posts as $post): ?>
          <div class="card" style="padding: 0; overflow: hidden; border-left: none; border-top: 4px solid var(--color-gold);">
            <div style="height: 220px; background-color: var(--color-navy); background-image: url('<?php echo h($post['featured_image']); ?>'); background-size: cover; background-position: center;"></div>
            <div style="padding: 2rem; display: flex; flex-direction: column; flex-grow: 1;">
              <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: var(--color-gold); font-weight: 600; text-transform: uppercase; margin-bottom: 0.55rem;">
                <span><?php echo h($post['category_name'] ?: 'General'); ?></span>
                <span style="color: var(--color-muted);"><?php echo date('M d, Y', strtotime($post['publish_date'])); ?></span>
              </div>
              <h3 style="font-size: 1.35rem; color: var(--color-navy); margin-bottom: 1rem; line-height: 1.4;"><?php echo h($post['title']); ?></h3>
              <p style="color: var(--color-muted); font-size: 0.85rem; line-height: 1.6; margin-bottom: 2rem;"><?php echo h($post['excerpt']); ?></p>
              <a href="/blogs/<?php echo h($post['slug']); ?>" class="btn btn-outline" style="margin-top: auto; padding: 0.6rem 1.25rem; font-size: 0.85rem; align-self: flex-start;">Read Article</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <!-- Clean Empty State (Prevents broken image boxes) -->
      <div class="text-center" style="max-width: 500px; margin: 4rem auto; padding: 3rem 2rem; border: 1px dashed var(--color-border); border-radius: var(--radius-md);">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 1.5rem;"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><line x1="10" y1="9" x2="8" y2="9"></line></svg>
        <h3 style="font-size: 1.25rem; color: var(--color-navy); margin-bottom: 0.75rem;">No Articles Published</h3>
        <p style="color: var(--color-muted); font-size: 0.9rem; line-height: 1.6;">
          We are currently preparing academic guides, news releases, and parenting insights. Please check back soon!
        </p>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
