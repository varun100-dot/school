<?php
// Zuvio Global School - Redesigned Blog Detail Template
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

$post = null;
if ($db && isset($blog_slug)) {
    try {
        $stmt = $db->prepare("
            SELECT b.*, c.name as category_name, c.slug as category_slug 
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

// Fetch Dynamic Related Blogs from the same category (excluding current post)
$related_blogs = [];
if ($db && $post) {
    try {
        if (!empty($post['category_id'])) {
            $r_stmt = $db->prepare("
                SELECT b.*, c.name as category_name, c.slug as category_slug 
                FROM `blogs` b 
                LEFT JOIN `blog_categories` c ON c.id = b.category_id 
                WHERE b.category_id = ? AND b.status = 'published' AND b.id != ? 
                ORDER BY b.publish_date DESC, b.id DESC 
                LIMIT 3
            ");
            $r_stmt->execute([$post['category_id'], $post['id']]);
            $related_blogs = $r_stmt->fetchAll();
        }

        // If fewer than 3 in the same category, backfill with other published blogs gracefully
        if (count($related_blogs) < 3) {
            $needed = 3 - count($related_blogs);
            $excluded_ids = array_merge([(int)$post['id']], array_map('intval', array_column($related_blogs, 'id')));
            $placeholders = implode(',', array_fill(0, count($excluded_ids), '?'));
            
            $fallback_stmt = $db->prepare("
                SELECT b.*, c.name as category_name, c.slug as category_slug 
                FROM `blogs` b 
                LEFT JOIN `blog_categories` c ON c.id = b.category_id 
                WHERE b.status = 'published' AND b.id NOT IN ($placeholders)
                ORDER BY b.publish_date DESC, b.id DESC 
                LIMIT " . (int)$needed
            );
            $fallback_stmt->execute($excluded_ids);
            $backfill = $fallback_stmt->fetchAll();
            $related_blogs = array_merge($related_blogs, $backfill);
        }
    } catch (Exception $e) {
        error_log("[Related Blogs Query Error] " . $e->getMessage());
    }
}

// Calculate dynamic reading time (average 200 words/min)
$word_count = str_word_count(strip_tags($post['content'] ?? ''));
$read_time = max(1, (int)ceil($word_count / 200));

// Author monogram initials
$author_name = trim($post['author'] ?: 'Zuvio Editorial');
$author_initials = 'Z';
$parts = explode(' ', $author_name);
if (count($parts) >= 2) {
    $author_initials = strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
} else {
    $author_initials = strtoupper(substr($author_name, 0, 1));
}

// Override SEO metrics for header.php
$seo = [
    'seo_title' => $post['seo_title'] ?: ($post['title'] . ' | Zuvio Global School'),
    'meta_description' => $post['meta_description'] ?: ($post['excerpt'] ?: 'Read the latest educational insights from Zuvio Global School.'),
    'canonical_url' => $post['canonical_url'] ?: ('https://zuvioglobalschool.com/blogs/' . $post['slug']),
    'og_title' => $post['title'],
    'og_description' => $post['excerpt'] ?: 'Educational perspectives and campus stories from Zuvio Global School.',
    'og_image' => $post['featured_image'] ?: '/assets/images/logo.png',
    'index_status' => 'index, follow'
];

$page_slug = 'blogs';
include_once dirname(__FILE__) . '/../includes/header.php';

// Render standardized breadcrumbs
$crumbs = [
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Blogs & Insights', 'url' => '/blogs'],
];
if (!empty($post['category_name']) && !empty($post['category_slug'])) {
    $crumbs[] = ['label' => $post['category_name'], 'url' => '/blogs?category=' . urlencode($post['category_slug'])];
}
$crumbs[] = ['label' => $post['title'], 'url' => ''];
render_breadcrumbs($crumbs);
?>

<!-- Redesigned Article Layout -->
<article class="blog-detail-article">
  
  <!-- Article Header Banner -->
  <header class="blog-detail-hero">
    <div class="blog-detail-hero-content">
      <?php if (!empty($post['category_name'])): ?>
        <a href="/blogs?category=<?php echo h($post['category_slug'] ?? slugify($post['category_name'])); ?>" class="blog-detail-cat-pill">
          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
          <?php echo h($post['category_name']); ?>
        </a>
      <?php endif; ?>

      <h1 class="blog-detail-title">
        <?php echo h($post['title']); ?>
      </h1>
      
      <div class="blog-detail-meta-strip">
        <span class="blog-detail-meta-item">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          <span>By <strong><?php echo h($author_name); ?></strong></span>
        </span>
        <span class="blog-detail-meta-item">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
          <span><?php echo date('F j, Y', strtotime($post['publish_date'])); ?></span>
        </span>
        <span class="blog-detail-meta-item">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
          <span><?php echo $read_time; ?> min read</span>
        </span>
      </div>
    </div>
  </header>

  <!-- Article Surface Container -->
  <div class="blog-detail-container">
    <div class="blog-detail-card">
      
      <!-- Featured Image (Clean Responsive Frame with Aspect-Ratio) -->
      <?php if (!empty($post['featured_image'])): ?>
        <figure class="blog-featured-media">
          <img src="<?php echo h($post['featured_image']); ?>" alt="<?php echo h($post['title']); ?>" loading="eager">
        </figure>
      <?php endif; ?>

      <!-- Main Copy (Sanitized Typography) -->
      <div class="blog-article-content">
        <?php echo render_blog_content($post['content']); ?>
      </div>
      
      <!-- Author Signature Card -->
      <div class="blog-author-box">
        <div class="blog-author-avatar" aria-hidden="true">
          <?php echo h($author_initials); ?>
        </div>
        <div class="blog-author-info">
          <strong><?php echo h($author_name); ?></strong>
          <span><?php echo h($post['author_designation'] ?: 'Academic Content Contributor & Faculty Mentor'); ?></span>
          <p style="font-size: 0.86rem; color: var(--color-muted); margin: 0.4rem 0 0 0; line-height: 1.5;">
            Dedicated to advancing child-centric pedagogy, international benchmarks, and future-ready digital learning at Zuvio Global School.
          </p>
        </div>
      </div>

      <!-- Navigation & Action Links -->
      <div class="blog-nav-actions">
        <a href="/blogs" class="btn btn-outline" style="padding: 0.65rem 1.6rem; font-size: 0.88rem;">
          &larr; Back to All Articles
        </a>

        <?php if (!empty($post['category_slug'])): ?>
          <a href="/blogs?category=<?php echo h($post['category_slug']); ?>" class="btn btn-primary" style="padding: 0.65rem 1.6rem; font-size: 0.88rem;">
            More in <?php echo h($post['category_name']); ?> &rarr;
          </a>
        <?php endif; ?>
      </div>

    </div>
  </div>
</article>

<!-- Dynamic Related Blogs Section ("You May Also Like") -->
<?php if (!empty($related_blogs)): ?>
  <section class="related-blogs-section" aria-labelledby="related-blogs-heading">
    <div class="container">
      <div class="related-blogs-header">
        <span class="related-blogs-tag">Recommended Reading</span>
        <h2 id="related-blogs-heading" class="related-blogs-title">You May Also Like</h2>
        <p class="related-blogs-subtitle">Explore more stories and insights from our school community.</p>
      </div>

      <div class="related-blogs-grid">
        <?php foreach ($related_blogs as $related): 
          $rel_img = !empty($related['featured_image']) ? $related['featured_image'] : '/assets/images/logo.png';
          $rel_date = !empty($related['publish_date']) ? date('M d, Y', strtotime($related['publish_date'])) : '';
          $rel_excerpt = !empty($related['excerpt']) ? $related['excerpt'] : strip_tags($related['content']);
          $rel_excerpt_trimmed = mb_strimwidth($rel_excerpt, 0, 115, '...');
        ?>
          <a href="/blogs/<?php echo h($related['slug']); ?>" class="related-blog-card">
            <div class="related-card-media">
              <img src="<?php echo h($rel_img); ?>" alt="<?php echo h($related['title']); ?>" loading="lazy">
              <?php if (!empty($related['category_name'])): ?>
                <span class="related-card-badge"><?php echo h($related['category_name']); ?></span>
              <?php endif; ?>
            </div>
            <div class="related-card-body">
              <div class="related-card-meta">
                <span><?php echo h($rel_date); ?></span>
                <span><?php echo h($related['author'] ?: 'Zuvio Editorial'); ?></span>
              </div>
              <h3 class="related-card-title"><?php echo h($related['title']); ?></h3>
              <p class="related-card-excerpt"><?php echo h($rel_excerpt_trimmed); ?></p>
              <span class="related-card-link">
                Read Article &rarr;
              </span>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
<?php endif; ?>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
