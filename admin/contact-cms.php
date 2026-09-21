<?php
// Zuvio Global School - Admin Contact Us CMS Manager
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_login();

$msg = $_GET['msg'] ?? '';
$error = '';
$tab = $_GET['tab'] ?? 'details';

// Initialize session store
if (!isset($_SESSION['mock_contact_cms'])) {
    $_SESSION['mock_contact_cms'] = [];
}
$contact_cms = &$_SESSION['mock_contact_cms'];

// 1. Defaults for Details
if (!isset($contact_cms['details'])) {
    $contact_cms['details'] = [
        'heading' => 'Contact Us / Enquire Now',
        'badge' => 'Admissions & Academic Office',
        'subheading' => 'We are here to support your child’s onboarding. Connect directly with our academic coordinators or submit an enquiry below.',
        'address' => "B-09, Lower Ground Floor,\nITL Twin Tower,\nNetaji Subhash Place,\nPitampura,\nDelhi – 110034",
        'phone' => '7827262956',
        'whatsapp' => '7827262956',
        'email' => 'info@zuvioglobalschool.com',
        'office_hours' => 'Monday–Saturday, 10:00 AM–7:00 PM'
    ];
}

// 2. Defaults for Social Links
if (!isset($contact_cms['social_links'])) {
    $contact_cms['social_links'] = [
        [
            'id' => 1,
            'platform' => 'WhatsApp',
            'icon' => 'whatsapp',
            'url' => 'https://wa.me/917827262956',
            'sort_order' => 1,
            'is_published' => 1
        ],
        [
            'id' => 2,
            'platform' => 'Instagram',
            'icon' => 'instagram',
            'url' => 'https://www.instagram.com/thezuvio/',
            'sort_order' => 2,
            'is_published' => 1
        ],
        [
            'id' => 3,
            'platform' => 'Facebook',
            'icon' => 'facebook',
            'url' => 'https://www.facebook.com/share/1XsYWDm3rt/',
            'sort_order' => 3,
            'is_published' => 1
        ],
        [
            'id' => 4,
            'platform' => 'LinkedIn',
            'icon' => 'linkedin',
            'url' => 'https://www.linkedin.com/company/zuvio-global-school/',
            'sort_order' => 4,
            'is_published' => 1
        ],
        [
            'id' => 5,
            'platform' => 'YouTube',
            'icon' => 'youtube',
            'url' => 'https://www.youtube.com/@zuvioglobalschool',
            'sort_order' => 5,
            'is_published' => 1
        ]
    ];
} else {
    foreach ($contact_cms['social_links'] as &$s_link) {
        $plat = strtolower($s_link['platform'] ?? '');
        if ($plat === 'instagram') $s_link['url'] = 'https://www.instagram.com/thezuvio/';
        elseif ($plat === 'facebook') $s_link['url'] = 'https://www.facebook.com/share/1XsYWDm3rt/';
        elseif ($plat === 'linkedin') $s_link['url'] = 'https://www.linkedin.com/company/zuvio-global-school/';
        elseif ($plat === 'youtube') $s_link['url'] = 'https://www.youtube.com/@zuvioglobalschool';
    }
    unset($s_link);
}

// 3. Defaults for Map Settings
if (!isset($contact_cms['map'])) {
    $contact_cms['map'] = [
        'is_visible' => 1,
        'title' => 'Our Academic & Admissions Office',
        'subtitle' => 'Located at ITL Twin Tower, Netaji Subhash Place (NSP), Pitampura — easily accessible via Delhi Metro (Red & Pink Lines).',
        'embed_url' => 'https://maps.google.com/maps?q=ITL+Twin+Tower,+Netaji+Subhash+Place,+Pitampura,+Delhi+110034&t=&z=15&ie=UTF8&iwloc=&output=embed',
        'directions_url' => 'https://www.google.com/maps/search/?api=1&query=ITL+Twin+Tower,+Netaji+Subhash+Place,+Pitampura,+Delhi+110034',
        'height' => 420
    ];
}

// 4. Defaults for Form Settings
if (!isset($contact_cms['form'])) {
    $contact_cms['form'] = [
        'title' => 'Enquire Now',
        'subtitle' => 'Please fill the form and we will get back to you within one working day.',
        'success_title' => 'Enquiry Submitted Successfully',
        'success_message' => 'Thank you! Your enquiry has been received. Our academic roadmap advisor will contact you within 24 working hours.',
        'button_text' => 'Submit Enquiry Form',
        'consent_text' => 'By submitting this form, you agree to receive official academic communications from Zuvio Global School.'
    ];
}

// 5. Defaults for Conversion Banner
if (!isset($contact_cms['banner'])) {
    $contact_cms['banner'] = [
        'is_visible' => 1,
        'heading' => 'Ready to Experience Modern Virtual Schooling?',
        'subheading' => 'Join forward-thinking families who have chosen flexible, CBSE-aligned online education tailored to their child.',
        'cta_primary_label' => 'Book a Free 1-on-1 Demo',
        'cta_primary_url' => '/book-demo',
        'cta_secondary_label' => 'Admissions & Enrolment',
        'cta_secondary_url' => '/admissions'
    ];
}

// -------------------------------------------------------------
// POST HANDLERS
// -------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security validation failed. Please refresh and try again.';
    } else {
        $action = $_POST['action'] ?? '';

        // 1. Save Details
        if ($action === 'save_details') {
            $contact_cms['details']['badge'] = trim($_POST['badge'] ?? '');
            $contact_cms['details']['heading'] = trim($_POST['heading'] ?? '');
            $contact_cms['details']['subheading'] = trim($_POST['subheading'] ?? '');
            $contact_cms['details']['address'] = trim($_POST['address'] ?? '');
            $contact_cms['details']['phone'] = trim($_POST['phone'] ?? '');
            $contact_cms['details']['whatsapp'] = trim($_POST['whatsapp'] ?? '');
            $contact_cms['details']['email'] = trim($_POST['email'] ?? '');
            $contact_cms['details']['office_hours'] = trim($_POST['office_hours'] ?? 'Monday–Saturday, 10:00 AM–7:00 PM');

            // Sync with site_settings if DB online
            if ($db) {
                try {
                    $stmt = $db->prepare("UPDATE `site_settings` SET `setting_value` = ? WHERE `setting_key` = ?");
                    $stmt->execute([$contact_cms['details']['phone'], 'phone']);
                    $stmt->execute([$contact_cms['details']['email'], 'general_email']);
                    $stmt->execute([$contact_cms['details']['address'], 'address']);
                    $stmt->execute([$contact_cms['details']['office_hours'], 'office_timings']);
                } catch (Exception $e) {
                    error_log("[Settings Sync Error] " . $e->getMessage());
                }
            }

            header('Location: /admin/contact-cms.php?tab=details&msg=saved');
            exit;
        }

        // 2. Add Social Link
        if ($action === 'add_social') {
            $platform = trim($_POST['platform'] ?? '');
            $url = trim($_POST['url'] ?? '');
            $icon = strtolower(trim($_POST['icon'] ?? $platform));
            $sort_order = (int)($_POST['sort_order'] ?? count($contact_cms['social_links']) + 1);
            $is_published = isset($_POST['is_published']) ? 1 : 0;

            if ($platform && $url) {
                $new_id = 1;
                foreach ($contact_cms['social_links'] as $item) {
                    if ($item['id'] >= $new_id) $new_id = $item['id'] + 1;
                }
                $contact_cms['social_links'][] = [
                    'id' => $new_id,
                    'platform' => $platform,
                    'icon' => $icon,
                    'url' => $url,
                    'sort_order' => $sort_order,
                    'is_published' => $is_published
                ];
                header('Location: /admin/contact-cms.php?tab=social&msg=added');
                exit;
            } else {
                $error = 'Platform and URL are required.';
            }
        }

        // 3. Edit Social Link
        if ($action === 'edit_social') {
            $id = (int)$_POST['id'];
            $platform = trim($_POST['platform'] ?? '');
            $url = trim($_POST['url'] ?? '');
            $icon = strtolower(trim($_POST['icon'] ?? $platform));
            $sort_order = (int)$_POST['sort_order'];
            $is_published = isset($_POST['is_published']) ? 1 : 0;

            foreach ($contact_cms['social_links'] as &$item) {
                if ($item['id'] === $id) {
                    $item['platform'] = $platform;
                    $item['icon'] = $icon;
                    $item['url'] = $url;
                    $item['sort_order'] = $sort_order;
                    $item['is_published'] = $is_published;
                    break;
                }
            }
            header('Location: /admin/contact-cms.php?tab=social&msg=updated');
            exit;
        }

        // 4. Delete Social Link
        if ($action === 'delete_social') {
            $id = (int)$_POST['id'];
            $contact_cms['social_links'] = array_values(array_filter($contact_cms['social_links'], function($i) use ($id) {
                return $i['id'] !== $id;
            }));
            header('Location: /admin/contact-cms.php?tab=social&msg=deleted');
            exit;
        }

        // 5. Toggle Social Publish
        if ($action === 'toggle_social') {
            $id = (int)$_POST['id'];
            foreach ($contact_cms['social_links'] as &$item) {
                if ($item['id'] === $id) {
                    $item['is_published'] = $item['is_published'] ? 0 : 1;
                    break;
                }
            }
            header('Location: /admin/contact-cms.php?tab=social&msg=status_toggled');
            exit;
        }

        // 6. Save Map Settings
        if ($action === 'save_map') {
            $contact_cms['map']['is_visible'] = isset($_POST['is_visible']) ? 1 : 0;
            $contact_cms['map']['title'] = trim($_POST['title'] ?? '');
            $contact_cms['map']['subtitle'] = trim($_POST['subtitle'] ?? '');
            $contact_cms['map']['embed_url'] = trim($_POST['embed_url'] ?? '');
            $contact_cms['map']['directions_url'] = trim($_POST['directions_url'] ?? '');
            $contact_cms['map']['height'] = (int)($_POST['height'] ?? 420);

            header('Location: /admin/contact-cms.php?tab=map&msg=saved');
            exit;
        }

        // 7. Save Form Configuration
        if ($action === 'save_form') {
            $contact_cms['form']['title'] = trim($_POST['title'] ?? '');
            $contact_cms['form']['subtitle'] = trim($_POST['subtitle'] ?? '');
            $contact_cms['form']['button_text'] = trim($_POST['button_text'] ?? 'Submit Enquiry Form');
            $contact_cms['form']['consent_text'] = trim($_POST['consent_text'] ?? '');
            $contact_cms['form']['success_title'] = trim($_POST['success_title'] ?? '');
            $contact_cms['form']['success_message'] = trim($_POST['success_message'] ?? '');

            header('Location: /admin/contact-cms.php?tab=form&msg=saved');
            exit;
        }

        // 8. Save Conversion Banner
        if ($action === 'save_banner') {
            $contact_cms['banner']['is_visible'] = isset($_POST['is_visible']) ? 1 : 0;
            $contact_cms['banner']['heading'] = trim($_POST['heading'] ?? '');
            $contact_cms['banner']['subheading'] = trim($_POST['subheading'] ?? '');
            $contact_cms['banner']['cta_primary_label'] = trim($_POST['cta_primary_label'] ?? '');
            $contact_cms['banner']['cta_primary_url'] = trim($_POST['cta_primary_url'] ?? '');
            $contact_cms['banner']['cta_secondary_label'] = trim($_POST['cta_secondary_label'] ?? '');
            $contact_cms['banner']['cta_secondary_url'] = trim($_POST['cta_secondary_url'] ?? '');

            header('Location: /admin/contact-cms.php?tab=banner&msg=saved');
            exit;
        }
    }
}

// Sort social links
usort($contact_cms['social_links'], function($a, $b) {
    return ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0);
});

$current_page = 'admin-contact-cms';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.6rem; color: var(--color-navy); margin-bottom: 0.25rem;">
      Contact Us CMS Manager
    </h1>
    <p style="color: var(--color-muted); font-size: 0.85rem; margin: 0;">
      Manage official contact details, social links, location map, enquiry form, and conversion CTA.
    </p>
  </div>
  <div style="display: flex; gap: 0.75rem;">
    <a href="/contact" target="_blank" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.35rem;">
      <span>View Live Contact Page</span> &nearr;
    </a>
  </div>
</div>

<?php if ($msg === 'saved' || $msg === 'updated' || $msg === 'added' || $msg === 'deleted' || $msg === 'status_toggled'): ?>
  <div style="background-color: #ECFDF5; border-left: 4px solid var(--color-success, #10B981); padding: 0.85rem 1.15rem; border-radius: var(--radius-sm); color: #065F46; font-size: 0.85rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
    <span>Action processed and published successfully.</span>
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div style="background-color: #FEF2F2; border-left: 4px solid #EF4444; padding: 0.85rem 1.15rem; border-radius: var(--radius-sm); color: #B91C1C; font-size: 0.85rem; margin-bottom: 1.5rem;">
    <?php echo h($error); ?>
  </div>
<?php endif; ?>

<!-- Tabs Navigation -->
<div style="display: flex; gap: 0.5rem; border-bottom: 2px solid var(--color-border); margin-bottom: 2rem; overflow-x: auto; padding-bottom: 2px;">
  <a href="?tab=details" style="padding: 0.65rem 1.25rem; font-size: 0.85rem; font-weight: 600; text-decoration: none; border-bottom: 2px solid <?php echo $tab === 'details' ? 'var(--color-teal)' : 'transparent'; ?>; color: <?php echo $tab === 'details' ? 'var(--color-navy)' : 'var(--color-muted)'; ?>; margin-bottom: -4px;">
    1. Contact Details &amp; Hours
  </a>
  <a href="?tab=social" style="padding: 0.65rem 1.25rem; font-size: 0.85rem; font-weight: 600; text-decoration: none; border-bottom: 2px solid <?php echo $tab === 'social' ? 'var(--color-teal)' : 'transparent'; ?>; color: <?php echo $tab === 'social' ? 'var(--color-navy)' : 'var(--color-muted)'; ?>; margin-bottom: -4px;">
    2. Social Media Links
  </a>
  <a href="?tab=map" style="padding: 0.65rem 1.25rem; font-size: 0.85rem; font-weight: 600; text-decoration: none; border-bottom: 2px solid <?php echo $tab === 'map' ? 'var(--color-teal)' : 'transparent'; ?>; color: <?php echo $tab === 'map' ? 'var(--color-navy)' : 'var(--color-muted)'; ?>; margin-bottom: -4px;">
    3. Location Map Settings
  </a>
  <a href="?tab=form" style="padding: 0.65rem 1.25rem; font-size: 0.85rem; font-weight: 600; text-decoration: none; border-bottom: 2px solid <?php echo $tab === 'form' ? 'var(--color-teal)' : 'transparent'; ?>; color: <?php echo $tab === 'form' ? 'var(--color-navy)' : 'var(--color-muted)'; ?>; margin-bottom: -4px;">
    4. Enquiry Form &amp; Copy
  </a>
  <a href="?tab=banner" style="padding: 0.65rem 1.25rem; font-size: 0.85rem; font-weight: 600; text-decoration: none; border-bottom: 2px solid <?php echo $tab === 'banner' ? 'var(--color-teal)' : 'transparent'; ?>; color: <?php echo $tab === 'banner' ? 'var(--color-navy)' : 'var(--color-muted)'; ?>; margin-bottom: -4px;">
    5. Bottom Conversion Banner
  </a>
</div>

<!-- TAB 1: DETAILS & HOURS -->
<?php if ($tab === 'details'): ?>
  <div class="card" style="border-left: none; padding: 2rem; max-width: 800px;">
    <h3 style="font-size: 1.2rem; color: var(--color-navy); margin-bottom: 0.5rem; font-family: var(--font-primary);">
      Edit Verified Contact Details
    </h3>
    <p style="font-size: 0.85rem; color: var(--color-muted); margin-bottom: 1.5rem;">
      Updates here reflect on the Contact page, global site settings, and footer.
    </p>

    <form method="POST" action="">
      <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
      <input type="hidden" name="action" value="save_details">

      <div class="admin-form-group">
        <label class="admin-label">Top Banner Badge</label>
        <input type="text" name="badge" class="admin-input" value="<?php echo h($contact_cms['details']['badge']); ?>" required>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Hero Heading</label>
        <input type="text" name="heading" class="admin-input" value="<?php echo h($contact_cms['details']['heading']); ?>" required>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Hero Subheading</label>
        <textarea name="subheading" class="admin-input" rows="2" required><?php echo h($contact_cms['details']['subheading']); ?></textarea>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;" class="admin-grid-row">
        <div class="admin-form-group">
          <label class="admin-label">Phone Number (digits only)</label>
          <input type="text" name="phone" class="admin-input" value="<?php echo h($contact_cms['details']['phone']); ?>" required>
          <small style="color: var(--color-muted); font-size: 0.75rem;">Example: 7827262956</small>
        </div>
        <div class="admin-form-group">
          <label class="admin-label">WhatsApp Number</label>
          <input type="text" name="whatsapp" class="admin-input" value="<?php echo h($contact_cms['details']['whatsapp']); ?>" required>
        </div>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Official Email Address</label>
        <input type="email" name="email" class="admin-input" value="<?php echo h($contact_cms['details']['email']); ?>" required>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Physical Campus Office Address (multiline)</label>
        <textarea name="address" class="admin-input" rows="4" required><?php echo h($contact_cms['details']['address']); ?></textarea>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Verified Office Hours</label>
        <input type="text" name="office_hours" class="admin-input" value="<?php echo h($contact_cms['details']['office_hours']); ?>" required>
        <small style="color: var(--color-muted); font-size: 0.75rem;">Standard verified format: Monday–Saturday, 10:00 AM–7:00 PM</small>
      </div>

      <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem; font-size: 0.9rem;">
        Save Contact Information
      </button>
    </form>
  </div>
<?php endif; ?>

<!-- TAB 2: SOCIAL LINKS -->
<?php if ($tab === 'social'): ?>
  <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 2rem; align-items: start;">
    
    <!-- Table of Links -->
    <div class="card" style="border-left: none; padding: 2rem;">
      <h3 style="font-size: 1.2rem; color: var(--color-navy); margin-bottom: 0.5rem; font-family: var(--font-primary);">
        Configured Social Platforms
      </h3>
      <p style="font-size: 0.85rem; color: var(--color-muted); margin-bottom: 1.5rem;">
        Add, edit, reorder or toggle visibility of social media buttons on the contact page.
      </p>

      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
          <thead>
            <tr style="border-bottom: 2px solid var(--color-border); color: var(--color-navy); font-weight: 600;">
              <th style="padding: 0.6rem 0.85rem;">Platform</th>
              <th style="padding: 0.6rem 0.85rem;">Target URL</th>
              <th style="padding: 0.6rem 0.85rem; text-align: center;">Order</th>
              <th style="padding: 0.6rem 0.85rem; text-align: center;">Status</th>
              <th style="padding: 0.6rem 0.85rem; text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($contact_cms['social_links'] as $item): ?>
              <tr style="border-bottom: 1px solid var(--color-border);">
                <td style="padding: 0.75rem 0.85rem; font-weight: 600; color: var(--color-navy);">
                  <?php echo h($item['platform']); ?>
                </td>
                <td style="padding: 0.75rem 0.85rem; color: var(--color-muted); max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                  <a href="<?php echo h($item['url']); ?>" target="_blank" rel="noopener" style="color: var(--color-teal); text-decoration: none;">
                    <?php echo h($item['url']); ?>
                  </a>
                </td>
                <td style="padding: 0.75rem 0.85rem; text-align: center; font-weight: 700;">
                  <?php echo (int)$item['sort_order']; ?>
                </td>
                <td style="padding: 0.75rem 0.85rem; text-align: center;">
                  <form method="POST" action="" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
                    <input type="hidden" name="action" value="toggle_social">
                    <input type="hidden" name="id" value="<?php echo (int)$item['id']; ?>">
                    <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0.2rem 0.5rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; background-color: <?php echo $item['is_published'] ? '#ECFDF5' : '#FEF2F2'; ?>; color: <?php echo $item['is_published'] ? '#065F46' : '#991B1B'; ?>;">
                      <?php echo $item['is_published'] ? 'Active' : 'Hidden'; ?>
                    </button>
                  </form>
                </td>
                <td style="padding: 0.75rem 0.85rem; text-align: right;">
                  <form method="POST" action="" style="display: inline;" onsubmit="return confirm('Delete this social link?');">
                    <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
                    <input type="hidden" name="action" value="delete_social">
                    <input type="hidden" name="id" value="<?php echo (int)$item['id']; ?>">
                    <button type="submit" style="background: none; border: none; color: #EF4444; font-size: 0.8rem; cursor: pointer;">
                      Delete
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Add Link Form -->
    <div class="card" style="border-left: none; padding: 2rem;">
      <h3 style="font-size: 1.15rem; color: var(--color-navy); margin-bottom: 0.5rem; font-family: var(--font-primary);">
        Add / Register Social Link
      </h3>
      <p style="font-size: 0.8rem; color: var(--color-muted); margin-bottom: 1.25rem;">
        Add official verified profiles for the school.
      </p>

      <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
        <input type="hidden" name="action" value="add_social">

        <div class="admin-form-group">
          <label class="admin-label">Platform Name</label>
          <select name="platform" class="admin-input" required>
            <option value="WhatsApp">WhatsApp</option>
            <option value="Instagram">Instagram</option>
            <option value="Facebook">Facebook</option>
            <option value="LinkedIn">LinkedIn</option>
            <option value="YouTube">YouTube</option>
            <option value="Twitter">X / Twitter</option>
          </select>
        </div>

        <div class="admin-form-group">
          <label class="admin-label">Profile URL</label>
          <input type="url" name="url" placeholder="https://..." class="admin-input" required>
        </div>

        <div class="admin-form-group">
          <label class="admin-label">Display Sort Order</label>
          <input type="number" name="sort_order" value="<?php echo count($contact_cms['social_links']) + 1; ?>" class="admin-input" required>
        </div>

        <div class="admin-form-group" style="display: flex; align-items: center; gap: 0.5rem;">
          <input type="checkbox" name="is_published" id="is_published" value="1" checked>
          <label for="is_published" style="font-size: 0.85rem; color: var(--color-navy); cursor: pointer;">
            Publish immediately on Contact Page
          </label>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem; font-size: 0.9rem;">
          Add Social Platform
        </button>
      </form>
    </div>

  </div>
<?php endif; ?>

<!-- TAB 3: LOCATION MAP SETTINGS -->
<?php if ($tab === 'map'): ?>
  <div class="card" style="border-left: none; padding: 2rem; max-width: 800px;">
    <h3 style="font-size: 1.2rem; color: var(--color-navy); margin-bottom: 0.5rem; font-family: var(--font-primary);">
      Location Map &amp; Embed Configuration
    </h3>
    <p style="font-size: 0.85rem; color: var(--color-muted); margin-bottom: 1.5rem;">
      Controls the live Google Maps container replacing the old map placeholder.
    </p>

    <form method="POST" action="">
      <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
      <input type="hidden" name="action" value="save_map">

      <div class="admin-form-group" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
        <input type="checkbox" name="is_visible" id="map_visible" value="1" <?php echo !empty($contact_cms['map']['is_visible']) ? 'checked' : ''; ?>>
        <label for="map_visible" style="font-size: 0.9rem; font-weight: 600; color: var(--color-navy); cursor: pointer;">
          Show Interactive Google Map Section on Contact Page
        </label>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Map Section Title</label>
        <input type="text" name="title" class="admin-input" value="<?php echo h($contact_cms['map']['title']); ?>" required>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Map Section Subtitle / Transit Instructions</label>
        <textarea name="subtitle" class="admin-input" rows="2" required><?php echo h($contact_cms['map']['subtitle']); ?></textarea>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Google Maps Embed URL (Iframe Src)</label>
        <input type="text" name="embed_url" class="admin-input" value="<?php echo h($contact_cms['map']['embed_url']); ?>" required>
        <small style="color: var(--color-muted); font-size: 0.75rem;">Verified destination: ITL Twin Tower, Netaji Subhash Place, Pitampura, Delhi 110034</small>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">"Open in Google Maps" Directions URL</label>
        <input type="text" name="directions_url" class="admin-input" value="<?php echo h($contact_cms['map']['directions_url']); ?>" required>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Map Iframe Height (px)</label>
        <input type="number" name="height" class="admin-input" value="<?php echo (int)$contact_cms['map']['height']; ?>" min="250" max="800" required>
      </div>

      <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem; font-size: 0.9rem;">
        Save Map Configuration
      </button>
    </form>
  </div>
<?php endif; ?>

<!-- TAB 4: FORM CONFIGURATION -->
<?php if ($tab === 'form'): ?>
  <div class="card" style="border-left: none; padding: 2rem; max-width: 800px;">
    <h3 style="font-size: 1.2rem; color: var(--color-navy); margin-bottom: 0.5rem; font-family: var(--font-primary);">
      Enquiry Form Headings &amp; Messaging
    </h3>
    <p style="font-size: 0.85rem; color: var(--color-muted); margin-bottom: 1.5rem;">
      Customize the layout copy, CTA button text, privacy disclaimer, and submission feedback.
    </p>

    <form method="POST" action="">
      <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
      <input type="hidden" name="action" value="save_form">

      <div class="admin-form-group">
        <label class="admin-label">Left Panel Heading (Page 65 Baseline)</label>
        <input type="text" name="title" class="admin-input" value="<?php echo h($contact_cms['form']['title']); ?>" required>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Left Panel Subtitle</label>
        <textarea name="subtitle" class="admin-input" rows="2" required><?php echo h($contact_cms['form']['subtitle']); ?></textarea>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Submit Button Label</label>
        <input type="text" name="button_text" class="admin-input" value="<?php echo h($contact_cms['form']['button_text']); ?>" required>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Consent &amp; Privacy Notice Text</label>
        <textarea name="consent_text" class="admin-input" rows="2" required><?php echo h($contact_cms['form']['consent_text']); ?></textarea>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Success Screen Headline</label>
        <input type="text" name="success_title" class="admin-input" value="<?php echo h($contact_cms['form']['success_title']); ?>" required>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Success Screen Message Body</label>
        <textarea name="success_message" class="admin-input" rows="3" required><?php echo h($contact_cms['form']['success_message']); ?></textarea>
      </div>

      <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem; font-size: 0.9rem;">
        Save Form Configuration
      </button>
    </form>
  </div>
<?php endif; ?>

<!-- TAB 5: CONVERSION BANNER -->
<?php if ($tab === 'banner'): ?>
  <div class="card" style="border-left: none; padding: 2rem; max-width: 800px;">
    <h3 style="font-size: 1.2rem; color: var(--color-navy); margin-bottom: 0.5rem; font-family: var(--font-primary);">
      Bottom Conversion Banner CTA
    </h3>
    <p style="font-size: 0.85rem; color: var(--color-muted); margin-bottom: 1.5rem;">
      Invitation to Book a Demo or proceed with admissions at the foot of the page.
    </p>

    <form method="POST" action="">
      <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
      <input type="hidden" name="action" value="save_banner">

      <div class="admin-form-group" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
        <input type="checkbox" name="is_visible" id="banner_visible" value="1" <?php echo !empty($contact_cms['banner']['is_visible']) ? 'checked' : ''; ?>>
        <label for="banner_visible" style="font-size: 0.9rem; font-weight: 600; color: var(--color-navy); cursor: pointer;">
          Show Bottom Conversion Banner
        </label>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Banner Heading</label>
        <input type="text" name="heading" class="admin-input" value="<?php echo h($contact_cms['banner']['heading']); ?>" required>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Banner Subheading</label>
        <textarea name="subheading" class="admin-input" rows="2" required><?php echo h($contact_cms['banner']['subheading']); ?></textarea>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;" class="admin-grid-row">
        <div class="admin-form-group">
          <label class="admin-label">Primary CTA Label</label>
          <input type="text" name="cta_primary_label" class="admin-input" value="<?php echo h($contact_cms['banner']['cta_primary_label']); ?>" required>
        </div>
        <div class="admin-form-group">
          <label class="admin-label">Primary CTA URL</label>
          <input type="text" name="cta_primary_url" class="admin-input" value="<?php echo h($contact_cms['banner']['cta_primary_url']); ?>" required>
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;" class="admin-grid-row">
        <div class="admin-form-group">
          <label class="admin-label">Secondary CTA Label</label>
          <input type="text" name="cta_secondary_label" class="admin-input" value="<?php echo h($contact_cms['banner']['cta_secondary_label']); ?>" required>
        </div>
        <div class="admin-form-group">
          <label class="admin-label">Secondary CTA URL</label>
          <input type="text" name="cta_secondary_url" class="admin-input" value="<?php echo h($contact_cms['banner']['cta_secondary_url']); ?>" required>
        </div>
      </div>

      <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem; font-size: 0.9rem;">
        Save Conversion Banner
      </button>
    </form>
  </div>
<?php endif; ?>

<style>
  @media (max-width: 768px) {
    .admin-grid-row {
      grid-template-columns: 1fr !important;
    }
  }
</style>

<?php
include_once dirname(__FILE__) . '/footer.php';
?>
