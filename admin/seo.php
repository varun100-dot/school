<?php
// Zuvio Global School - Admin Page SEO Manager
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_login();

$msg = $_GET['msg'] ?? '';
$error = '';
$action = $_GET['action'] ?? 'list';
$page_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Update Single Page SEO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_seo'])) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        $p_id = (int)$_POST['page_id'];
        $seo_title = trim($_POST['seo_title'] ?? '');
        $meta_description = trim($_POST['meta_description'] ?? '');
        $primary_keyword = trim($_POST['primary_keyword'] ?? '');
        $secondary_keywords = trim($_POST['secondary_keywords'] ?? '');
        $canonical_url = trim($_POST['canonical_url'] ?? '');
        $index_status = trim($_POST['index_status'] ?? 'index, follow');
        $og_title = trim($_POST['og_title'] ?? '') ?: $seo_title;
        $og_description = trim($_POST['og_description'] ?? '') ?: $meta_description;
        $og_image = trim($_POST['og_image'] ?? '/assets/images/logo.png');

        if ($db) {
            try {
                $stmt = $db->prepare("
                    INSERT INTO `page_seo` (`page_id`, `primary_keyword`, `secondary_keywords`, `seo_title`, `meta_description`, `canonical_url`, `index_status`, `og_title`, `og_description`, `og_image`)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE
                        `primary_keyword` = VALUES(`primary_keyword`),
                        `secondary_keywords` = VALUES(`secondary_keywords`),
                        `seo_title` = VALUES(`seo_title`),
                        `meta_description` = VALUES(`meta_description`),
                        `canonical_url` = VALUES(`canonical_url`),
                        `index_status` = VALUES(`index_status`),
                        `og_title` = VALUES(`og_title`),
                        `og_description` = VALUES(`og_description`),
                        `og_image` = VALUES(`og_image`)
                ");
                $stmt->execute([
                    $p_id, $primary_keyword, $secondary_keywords, $seo_title, $meta_description,
                    $canonical_url, $index_status, $og_title, $og_description, $og_image
                ]);
                header('Location: /admin/seo.php?msg=saved');
                exit;
            } catch (Exception $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        } else {
            // Store in mock/session fallback
            $_SESSION['mock_seo'][$p_id] = [
                'primary_keyword' => $primary_keyword,
                'secondary_keywords' => $secondary_keywords,
                'seo_title' => $seo_title,
                'meta_description' => $meta_description,
                'canonical_url' => $canonical_url,
                'index_status' => $index_status,
                'og_title' => $og_title,
                'og_description' => $og_description,
                'og_image' => $og_image
            ];
            set_json_setting('custom_seo', $_SESSION['mock_seo'] ?? [], 'Custom SEO Settings');
            header('Location: /admin/seo.php?msg=saved');
            exit;
        }
    }
}

// Fetch pages with their SEO info
$pages = [];
$default_pages = [
    ['id' => 1, 'name' => 'Home', 'slug' => 'home'],
    ['id' => 2, 'name' => 'About Zuvio', 'slug' => 'about-zuvio'],
    ['id' => 3, 'name' => 'About Us (Overview)', 'slug' => 'about'],
    ['id' => 4, 'name' => 'Our Team & Leadership', 'slug' => 'our-team'],
    ['id' => 5, 'name' => 'Founder’s Message', 'slug' => 'founder-message'],
    ['id' => 6, 'name' => 'Affiliations & Accreditations', 'slug' => 'affiliations-accreditations'],
    ['id' => 7, 'name' => 'Curriculum Framework', 'slug' => 'curriculum'],
    ['id' => 8, 'name' => 'Academics Overview', 'slug' => 'academics'],
    ['id' => 9, 'name' => 'Technology & AI Labs', 'slug' => 'technology'],
    ['id' => 10, 'name' => 'Special Education', 'slug' => 'special-education'],
    ['id' => 11, 'name' => 'Electives & Languages', 'slug' => 'electives'],
    ['id' => 12, 'name' => 'NEP 2020 Guidelines', 'slug' => 'nep-2020'],
    ['id' => 13, 'name' => 'Academic Resources', 'slug' => 'resources'],
    ['id' => 14, 'name' => 'Admissions Overview', 'slug' => 'admissions'],
    ['id' => 15, 'name' => 'Enrol Now (5 Steps)', 'slug' => 'enrol-now'],
    ['id' => 16, 'name' => 'Eligibility Matrix', 'slug' => 'eligibility'],
    ['id' => 17, 'name' => 'Academic Calendar', 'slug' => 'calendar'],
    ['id' => 18, 'name' => 'Fee Structure', 'slug' => 'fees'],
    ['id' => 19, 'name' => 'Parent FAQs', 'slug' => 'faq'],
    ['id' => 20, 'name' => 'Beyond Overview', 'slug' => 'beyond'],
    ['id' => 21, 'name' => 'Co-curricular & Clubs', 'slug' => 'co-curricular'],
    ['id' => 22, 'name' => 'Student Achievers', 'slug' => 'student-achievers'],
    ['id' => 23, 'name' => 'Photo Gallery', 'slug' => 'gallery'],
    ['id' => 24, 'name' => 'Virtual Classroom', 'slug' => 'virtual-classroom'],
    ['id' => 25, 'name' => 'Blogs & Insights', 'slug' => 'blogs'],
    ['id' => 26, 'name' => 'Contact Us', 'slug' => 'contact']
];

if ($db) {
    try {
        $stmt = $db->query("
            SELECT p.id, p.name, p.slug, s.seo_title, s.meta_description, s.primary_keyword, s.canonical_url, s.index_status, s.og_image
            FROM `pages` p
            LEFT JOIN `page_seo` s ON s.page_id = p.id
            ORDER BY p.id ASC
        ");
        $pages = $stmt->fetchAll();
    } catch (Exception $e) {
        $error = "Could not fetch SEO pages: " . $e->getMessage();
    }
}

if (empty($pages)) {
    $pages = $default_pages;
    $custom_seo = get_json_setting('custom_seo', $_SESSION['mock_seo'] ?? []);
    foreach ($pages as &$p) {
        if (isset($custom_seo[$p['id']])) {
            $p = array_merge($p, $custom_seo[$p['id']]);
        }
    }
}

// Single Page Edit Data
$edit_page = null;
if ($action === 'edit' && $page_id > 0) {
    foreach ($pages as $p) {
        if ($p['id'] == $page_id) {
            $edit_page = $p;
            break;
        }
    }
    if (!$edit_page) {
        header('Location: /admin/seo.php?error=notfound');
        exit;
    }
}

$page_slug = 'admin-seo';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.5rem; color: var(--color-navy); margin-bottom: 0.25rem;">Page SEO &amp; Meta Manager</h1>
    <p style="color: var(--color-muted); font-size: 0.85rem;">Optimize title tags, meta descriptions, Open Graph data, canonical URLs, and Google SERP previews.</p>
  </div>
  <?php if ($action === 'edit'): ?>
    <a href="/admin/seo.php" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">&larr; Back to All Pages</a>
  <?php endif; ?>
</div>

<?php if ($msg === 'saved'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    SEO settings updated and synchronized across production.
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="error-alert">
    <?php echo h($error); ?>
  </div>
<?php endif; ?>

<?php if ($action === 'list'): ?>
  <!-- All Pages List Table -->
  <div class="card" style="border-left: none; padding: 2rem;">
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
        <thead>
          <tr style="border-bottom: 2px solid var(--color-border); color: var(--color-navy); font-weight: 600;">
            <th style="padding: 0.75rem 1rem; width: 40px;">#</th>
            <th style="padding: 0.75rem 1rem;">Page Name</th>
            <th style="padding: 0.75rem 1rem;">Slug</th>
            <th style="padding: 0.75rem 1rem;">SEO Title Tag</th>
            <th style="padding: 0.75rem 1rem;">Primary Keyword</th>
            <th style="padding: 0.75rem 1rem;">Robots Index</th>
            <th style="padding: 0.75rem 1rem; text-align: right;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($pages as $p): ?>
            <tr style="border-bottom: 1px solid var(--color-border);">
              <td style="padding: 0.75rem 1rem; color: var(--color-muted);"><?php echo (int)$p['id']; ?></td>
              <td style="padding: 0.75rem 1rem; font-weight: 700; color: var(--color-navy);"><?php echo h($p['name']); ?></td>
              <td style="padding: 0.75rem 1rem; color: var(--color-muted);"><code>/<?php echo h($p['slug'] === 'home' ? '' : $p['slug']); ?></code></td>
              <td style="padding: 0.75rem 1rem; max-width: 320px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                <?php echo h($p['seo_title'] ?? '—'); ?>
              </td>
              <td style="padding: 0.75rem 1rem; color: var(--color-teal); font-weight: 600;">
                <?php echo h($p['primary_keyword'] ?? '—'); ?>
              </td>
              <td style="padding: 0.75rem 1rem;">
                <span style="display: inline-block; padding: 0.2rem 0.5rem; font-size: 0.72rem; border-radius: 4px; font-weight: 700; background: <?php echo ($p['index_status'] ?? 'index') !== 'noindex' ? '#DEF7EC' : '#FDE8E8'; ?>; color: <?php echo ($p['index_status'] ?? 'index') !== 'noindex' ? '#03543F' : '#9B1C1C'; ?>;">
                  <?php echo h($p['index_status'] ?? 'index, follow'); ?>
                </span>
              </td>
              <td style="padding: 0.75rem 1rem; text-align: right;">
                <a href="/admin/seo.php?action=edit&id=<?php echo (int)$p['id']; ?>" style="color: var(--color-gold); font-weight: 700; text-decoration: none;">Edit SEO &rarr;</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

<?php elseif ($action === 'edit' && $edit_page): ?>
  <!-- Edit Page SEO & SERP Preview Form -->
  <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: flex-start;">
    <div class="card" style="border-left: none; padding: 2.5rem;">
      <h3 style="color: var(--color-navy); font-size: 1.25rem; margin-top: 0; margin-bottom: 1.5rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">
        Edit SEO for: <span style="color: var(--color-gold);"><?php echo h($edit_page['name']); ?></span>
      </h3>
      <form method="POST" action="/admin/seo.php">
        <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
        <input type="hidden" name="save_seo" value="1">
        <input type="hidden" name="page_id" value="<?php echo (int)$edit_page['id']; ?>">

        <div class="admin-form-group">
          <label class="admin-label">Page SEO Title Tag (50-60 chars recommended) *</label>
          <input type="text" id="seo_title" name="seo_title" required value="<?php echo h($edit_page['seo_title'] ?? ''); ?>" class="admin-input" oninput="updateSerpPreview()">
        </div>

        <div class="admin-form-group">
          <label class="admin-label">Meta Description (140-160 chars recommended) *</label>
          <textarea id="meta_description" name="meta_description" required rows="3" class="admin-input" oninput="updateSerpPreview()"><?php echo h($edit_page['meta_description'] ?? ''); ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div class="admin-form-group">
            <label class="admin-label">Primary Target Keyword</label>
            <input type="text" name="primary_keyword" value="<?php echo h($edit_page['primary_keyword'] ?? ''); ?>" placeholder="e.g. CBSE online school" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Secondary Keywords (comma separated)</label>
            <input type="text" name="secondary_keywords" value="<?php echo h($edit_page['secondary_keywords'] ?? ''); ?>" placeholder="e.g. online schooling, K-8 homeschooling" class="admin-input">
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
          <div class="admin-form-group">
            <label class="admin-label">Canonical URL</label>
            <input type="text" name="canonical_url" value="<?php echo h($edit_page['canonical_url'] ?? ('https://zuvioglobalschool.com/' . ($edit_page['slug'] === 'home' ? '' : $edit_page['slug']))); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Index Status</label>
            <select name="index_status" class="admin-input" style="height: 38px;">
              <option value="index, follow" <?php echo ($edit_page['index_status'] ?? '') === 'index, follow' ? 'selected' : ''; ?>>index, follow</option>
              <option value="noindex, follow" <?php echo ($edit_page['index_status'] ?? '') === 'noindex, follow' ? 'selected' : ''; ?>>noindex, follow</option>
              <option value="noindex, nofollow" <?php echo ($edit_page['index_status'] ?? '') === 'noindex, nofollow' ? 'selected' : ''; ?>>noindex, nofollow</option>
            </select>
          </div>
        </div>

        <h4 style="color: var(--color-navy); font-size: 1.1rem; margin-top: 1.5rem; margin-bottom: 1rem; border-top: 1px solid var(--color-border); padding-top: 1rem;">Social Sharing (Open Graph & Twitter)</h4>
        
        <div class="admin-form-group">
          <label class="admin-label">OG Title</label>
          <input type="text" name="og_title" value="<?php echo h($edit_page['og_title'] ?? $edit_page['seo_title'] ?? ''); ?>" class="admin-input">
        </div>

        <div class="admin-form-group">
          <label class="admin-label">OG Description</label>
          <textarea name="og_description" rows="2" class="admin-input"><?php echo h($edit_page['og_description'] ?? $edit_page['meta_description'] ?? ''); ?></textarea>
        </div>

        <div class="admin-form-group">
          <label class="admin-label">OG Image URL (1200x630px recommended)</label>
          <input type="text" name="og_image" value="<?php echo h($edit_page['og_image'] ?? '/assets/images/logo.png'); ?>" class="admin-input">
        </div>

        <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2.5rem; margin-top: 1rem;">Save SEO Changes</button>
        <a href="/admin/seo.php" class="btn btn-outline" style="padding: 0.8rem 2rem; margin-left: 0.75rem;">Cancel</a>
      </form>
    </div>

    <!-- Live Google SERP Simulation -->
    <div>
      <div class="card" style="border-left: none; padding: 1.75rem; border-top: 4px solid var(--color-teal); margin-bottom: 1.5rem;">
        <span style="font-size: 0.72rem; font-weight: 800; color: var(--color-gold); text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 0.5rem;">Live SERP Preview</span>
        <h4 style="font-size: 1rem; color: var(--color-navy); margin-top: 0; margin-bottom: 1rem;">Google Search Result Snippet</h4>
        
        <div style="background: #ffffff; border: 1px solid #dadce0; border-radius: 8px; padding: 1rem; font-family: Roboto, Arial, sans-serif;">
          <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem;">
            <div style="width: 18px; height: 18px; border-radius: 50%; background: #062b63; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 10px; font-weight: 800;">Z</div>
            <div style="font-size: 12px; color: #202124; line-height: 1.2;">
              <div>zuvioglobalschool.com</div>
              <div style="color: #5f6368; font-size: 11px;">https://zuvioglobalschool.com/<?php echo h($edit_page['slug'] === 'home' ? '' : $edit_page['slug']); ?></div>
            </div>
          </div>
          <div id="preview-title" style="color: #1a0dab; font-size: 18px; line-height: 1.3; font-weight: 400; cursor: pointer; text-decoration: none; margin-bottom: 4px;">
            <?php echo h($edit_page['seo_title'] ?? $edit_page['name'] . ' | Zuvio Global School'); ?>
          </div>
          <div id="preview-desc" style="color: #4d5156; font-size: 13px; line-height: 1.58; word-wrap: break-word;">
            <?php echo h($edit_page['meta_description'] ?? 'Explore Zuvio Global School’s accredited online schooling curriculum, holistic learning modules, and future-ready education.'); ?>
          </div>
        </div>
      </div>

      <div class="card" style="border-left: none; padding: 1.5rem; background: var(--color-surface-warm);">
        <h4 style="font-size: 0.95rem; color: var(--color-navy); margin-top: 0; margin-bottom: 0.5rem;">SEO Best Practices</h4>
        <ul style="font-size: 0.8rem; color: var(--color-muted); padding-left: 1.2rem; line-height: 1.5; margin: 0;">
          <li>Keep Title under 60 characters to avoid truncation.</li>
          <li>Write action-driven Meta Descriptions between 140–160 chars.</li>
          <li>Include the primary keyword naturally in title and description.</li>
          <li>Always provide an absolute URL in the Canonical field.</li>
        </ul>
      </div>
    </div>
  </div>

  <script>
    function updateSerpPreview() {
      const titleInput = document.getElementById('seo_title');
      const descInput = document.getElementById('meta_description');
      const titleEl = document.getElementById('preview-title');
      const descEl = document.getElementById('preview-desc');

      if (titleInput && titleEl) {
        titleEl.textContent = titleInput.value.trim() || '<?php echo h($edit_page['name']); ?> | Zuvio Global School';
      }
      if (descInput && descEl) {
        descEl.textContent = descInput.value.trim() || 'Discover child-centric online schooling at Zuvio Global School.';
      }
    }
  </script>
<?php endif; ?>

<?php include_once dirname(__FILE__) . '/footer.php'; ?>
