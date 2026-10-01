<?php
// Zuvio Global School - Admin Contact Us CMS Manager (Unified 2-Column Section Manager)
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_login();

$msg = $_GET['msg'] ?? '';
$error = '';
$selected_sec = $_GET['sec'] ?? 'details';

// Persistent CMS Storage (MySQL database with session fallback)
$db_saved = get_json_setting('cms_contact', []);
if (!isset($_SESSION['mock_contact_cms']) || !empty($db_saved)) {
    $_SESSION['mock_contact_cms'] = !empty($db_saved) ? $db_saved : [];
}
$contact_cms = &$_SESSION['mock_contact_cms'];

// 1. Defaults for Details
if (!isset($contact_cms['details'])) {
    $contact_cms['details'] = [
        'is_active' => 1,
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
if (!isset($contact_cms['social'])) {
    $contact_cms['social'] = [
        'is_active' => 1,
        'title' => 'Social Media Channels',
        'subtitle' => 'Follow and interact with Zuvio Global School across official digital channels.'
    ];
}
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
}

// 3. Defaults for Map Settings
if (!isset($contact_cms['map'])) {
    $contact_cms['map'] = [
        'is_active' => 1,
        'title' => 'Our Headquarter Office',
        'subtitle' => 'Located at ITL Twin Tower, Netaji Subhash Place (NSP), Pitampura — easily accessible via Delhi Metro (Red & Pink Lines).',
        'embed_url' => 'https://maps.google.com/maps?q=ITL+Twin+Tower,+Netaji+Subhash+Place,+Pitampura,+Delhi+110034&t=&z=15&ie=UTF8&iwloc=&output=embed',
        'directions_url' => 'https://www.google.com/maps/search/?api=1&query=ITL+Twin+Tower,+Netaji+Subhash+Place,+Pitampura,+Delhi+110034',
        'height' => 420
    ];
}

// 4. Defaults for Form Settings
if (!isset($contact_cms['form'])) {
    $contact_cms['form'] = [
        'is_active' => 1,
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
        'is_active' => 1,
        'heading' => 'Ready to Experience Modern Virtual Schooling?',
        'subheading' => 'Join forward-thinking families who have chosen flexible, CBSE-aligned online education tailored to their child.',
        'cta_primary_label' => 'Book a Free 1-on-1 Demo',
        'cta_primary_url' => '/book-demo',
        'cta_secondary_label' => 'Admissions & Enrolment',
        'cta_secondary_url' => '/admissions'
    ];
}

// 5 Contact Sections
$sections_nav = [
    'details' => ['num' => 1, 'name' => 'Contact Details & Hours', 'icon' => '📞'],
    'form'    => ['num' => 2, 'name' => 'Enquiry Form Settings', 'icon' => '📝'],
    'map'     => ['num' => 3, 'name' => 'Google Maps Location', 'icon' => '📍'],
    'social'  => ['num' => 4, 'name' => 'Social Media Links', 'icon' => '🌐'],
    'banner'  => ['num' => 5, 'name' => 'Conversion CTA Banner', 'icon' => '🚀']
];

if (!array_key_exists($selected_sec, $sections_nav)) {
    $selected_sec = 'details';
}

function redirect_and_save_contact($sec, $msg) {
    global $contact_cms;
    set_json_setting('cms_contact', $contact_cms, 'Contact CMS Content');
    header("Location: /admin/contact-cms.php?sec=" . urlencode($sec) . "&msg=" . urlencode($msg));
    exit;
}

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please refresh and try again.';
    } else {
        $action = $_POST['action'] ?? '';
        $target_sec = $_POST['section_key'] ?? $selected_sec;

        // Apply Section Removal / Restoration / Visibility
        if (!isset($contact_cms[$target_sec])) {
            $contact_cms[$target_sec] = [];
        }

        $sec_action = $_POST['sec_action'] ?? '';
        if ($sec_action === 'remove') {
            $contact_cms[$target_sec]['is_removed'] = 1;
            $contact_cms[$target_sec]['is_active'] = 0;
        } elseif ($sec_action === 'restore') {
            $contact_cms[$target_sec]['is_removed'] = 0;
            $contact_cms[$target_sec]['is_active'] = 1;
        } else {
            $contact_cms[$target_sec]['is_active'] = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 0;
            if ($contact_cms[$target_sec]['is_active'] == 1) {
                $contact_cms[$target_sec]['is_removed'] = 0;
            }
        }

        // Section 1: Details
        if ($target_sec === 'details') {
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
            redirect_and_save_contact('details', 'saved');
        }

        // Section 2: Form Settings
        if ($target_sec === 'form') {
            $contact_cms['form']['title'] = trim($_POST['title'] ?? '');
            $contact_cms['form']['subtitle'] = trim($_POST['subtitle'] ?? '');
            $contact_cms['form']['success_title'] = trim($_POST['success_title'] ?? '');
            $contact_cms['form']['success_message'] = trim($_POST['success_message'] ?? '');
            $contact_cms['form']['button_text'] = trim($_POST['button_text'] ?? '');
            $contact_cms['form']['consent_text'] = trim($_POST['consent_text'] ?? '');
            redirect_and_save_contact('form', 'saved');
        }

        // Section 3: Map
        if ($target_sec === 'map') {
            $contact_cms['map']['title'] = trim($_POST['title'] ?? '');
            $contact_cms['map']['subtitle'] = trim($_POST['subtitle'] ?? '');
            $contact_cms['map']['embed_url'] = trim($_POST['embed_url'] ?? '');
            $contact_cms['map']['directions_url'] = trim($_POST['directions_url'] ?? '');
            $contact_cms['map']['height'] = (int)($_POST['height'] ?? 420);
            redirect_and_save_contact('map', 'saved');
        }

        // Section 4: Social
        if ($target_sec === 'social') {
            $contact_cms['social']['title'] = trim($_POST['title'] ?? 'Social Media Channels');
            $contact_cms['social']['subtitle'] = trim($_POST['subtitle'] ?? '');

            if ($action === 'add_social') {
                $platform = trim($_POST['new_platform'] ?? '');
                $url = trim($_POST['new_url'] ?? '');
                if ($platform && $url) {
                    $contact_cms['social_links'][] = [
                        'id' => time(),
                        'platform' => $platform,
                        'icon' => strtolower($platform),
                        'url' => $url,
                        'sort_order' => count($contact_cms['social_links']) + 1,
                        'is_published' => 1
                    ];
                }
            } elseif ($action === 'delete_social') {
                $id = (int)($_POST['item_id'] ?? 0);
                $contact_cms['social_links'] = array_values(array_filter($contact_cms['social_links'], fn($s) => ($s['id'] ?? 0) != $id));
            } elseif (isset($_POST['social_urls']) && is_array($_POST['social_urls'])) {
                foreach ($_POST['social_urls'] as $sid => $surl) {
                    foreach ($contact_cms['social_links'] as &$link) {
                        if (($link['id'] ?? 0) == $sid) {
                            $link['url'] = trim($surl);
                        }
                    }
                }
            }
            redirect_and_save_contact('social', 'saved');
        }

        // Section 5: Banner
        if ($target_sec === 'banner') {
            $contact_cms['banner']['heading'] = trim($_POST['heading'] ?? '');
            $contact_cms['banner']['subheading'] = trim($_POST['subheading'] ?? '');
            $contact_cms['banner']['cta_primary_label'] = trim($_POST['cta_primary_label'] ?? '');
            $contact_cms['banner']['cta_primary_url'] = trim($_POST['cta_primary_url'] ?? '');
            $contact_cms['banner']['cta_secondary_label'] = trim($_POST['cta_secondary_label'] ?? '');
            $contact_cms['banner']['cta_secondary_url'] = trim($_POST['cta_secondary_url'] ?? '');
            redirect_and_save_contact('banner', 'saved');
        }
    }
}

$page_slug = 'admin-contact-cms';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.75rem; flex-wrap: wrap; gap: 1rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.6rem; color: var(--color-navy); margin-bottom: 0.25rem;">
      Contact Us Section Manager
    </h1>
    <p style="color: var(--color-muted); font-size: 0.85rem;">
      Unified 2-column management for Contact page sections. Enable/disable, remove, or customize content.
    </p>
  </div>
  <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
    <a href="/contact" target="_blank" class="btn btn-outline" style="padding: 0.45rem 0.85rem; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.4rem;">
      <span>👁️</span> Preview Live Page &nearr;
    </a>
    <a href="/admin/contact-cms.php?sec=<?php echo urlencode($selected_sec); ?>" class="btn btn-outline" style="padding: 0.45rem 0.85rem; font-size: 0.8rem; background: #FFFFFF;">
      ↻ Reload
    </a>
  </div>
</div>

<?php if ($msg === 'saved'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.9rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
    <div><strong>✓ Saved & Synchronized!</strong> Section updates are live on the website.</div>
    <span style="font-size: 0.8rem; color: var(--color-muted);"><?php echo date('H:i:s'); ?></span>
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div style="background:#fde8e8; border-left:4px solid #c81e1e; padding:0.85rem 1.25rem; margin-bottom:1.5rem; color:#9b1c1c; font-size:0.9rem;">
    <strong>Error:</strong> <?php echo h($error); ?>
  </div>
<?php endif; ?>

<!-- 2-Column Section Manager Layout -->
<div style="display: grid; grid-template-columns: 310px 1fr; gap: 1.75rem; align-items: start;">

  <!-- Left Sidebar: Sticky Section Sequence -->
  <div style="position: sticky; top: 1.5rem;">
    <div class="card" style="padding: 0; overflow: hidden; border: 1.5px solid rgba(6, 43, 99, 0.12); box-shadow: var(--shadow-sm);">
      <div style="background: var(--color-navy); color: #FFFFFF; padding: 1rem 1.25rem; font-weight: 700; font-size: 0.9rem; display: flex; justify-content: space-between; align-items: center;">
        <span>Contact Sections</span>
        <span style="font-size: 0.75rem; background: rgba(255,255,255,0.2); padding: 0.2rem 0.55rem; border-radius: 12px; font-weight: 600;">
          <?php echo count($sections_nav); ?> Total
        </span>
      </div>

      <div style="divide-y: 1px solid var(--color-border); max-height: calc(100vh - 180px); overflow-y: auto;">
        <?php foreach ($sections_nav as $skey => $sdata): 
          $s_active = !isset($contact_cms[$skey]['is_active']) || !empty($contact_cms[$skey]['is_active']);
          $s_removed = !empty($contact_cms[$skey]['is_removed']);
          $is_current = ($selected_sec === $skey);
        ?>
          <a href="/admin/contact-cms.php?sec=<?php echo urlencode($skey); ?>" 
             style="display: flex; align-items: center; justify-content: space-between; padding: 0.85rem 1.1rem; text-decoration: none; border-bottom: 1px solid var(--color-border); background: <?php echo $is_current ? 'var(--pastel-blue)' : '#FFFFFF'; ?>; border-left: 4px solid <?php echo $is_current ? 'var(--color-navy)' : 'transparent'; ?>; transition: all 0.15s ease;">
            <div style="display: flex; align-items: center; gap: 0.75rem; min-width: 0;">
              <span style="font-size: 0.75rem; font-weight: 800; color: var(--color-muted); width: 18px;">
                <?php echo str_pad($sdata['num'], 2, '0', STR_PAD_LEFT); ?>
              </span>
              <span style="font-size: 1.1rem;"><?php echo $sdata['icon']; ?></span>
              <span style="font-size: 0.85rem; font-weight: <?php echo $is_current ? '700' : '500'; ?>; color: var(--color-navy); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                <?php echo h($sdata['name']); ?>
              </span>
            </div>
            <div>
              <?php if ($s_removed): ?>
                <span style="font-size: 0.7rem; font-weight: 700; color: #dc2626; background: #fee2e2; padding: 0.15rem 0.45rem; border-radius: 4px;">
                  REMOVED
                </span>
              <?php elseif (!$s_active): ?>
                <span style="font-size: 0.7rem; font-weight: 700; color: #6b7280; background: #f3f4f6; padding: 0.15rem 0.45rem; border-radius: 4px;">
                  OFF
                </span>
              <?php else: ?>
                <span style="font-size: 0.7rem; font-weight: 700; color: #047857; background: #d1fae5; padding: 0.15rem 0.45rem; border-radius: 4px;">
                  ON
                </span>
              <?php endif; ?>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Right Main Section Editor -->
  <div>
    <?php 
      $current_sec_data = $contact_cms[$selected_sec] ?? [];
      $is_sec_removed = !empty($current_sec_data['is_removed']);
      $is_sec_active = !isset($current_sec_data['is_active']) || !empty($current_sec_data['is_active']);
      $sec_meta = $sections_nav[$selected_sec];
    ?>

    <form method="POST" id="sectionForm">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="section_key" value="<?php echo h($selected_sec); ?>">
      <input type="hidden" name="sec_action" id="sec_action_input" value="">
      <input type="hidden" name="action" id="form_action_input" value="save_section">
      <input type="hidden" name="item_id" id="item_id_input" value="">

      <div class="card" style="padding: 2rem; border: 1.5px solid rgba(6, 43, 99, 0.15); box-shadow: var(--shadow-sm); margin-bottom: 2rem;">

        <!-- Section Editor Header with Live Controls -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 1.25rem; margin-bottom: 1.5rem; border-bottom: 2px solid var(--color-border); flex-wrap: wrap; gap: 1rem;">
          <div>
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 0.25rem;">
              Section <?php echo $sec_meta['num']; ?> of <?php echo count($sections_nav); ?>
            </div>
            <h2 style="font-size: 1.4rem; color: var(--color-navy); font-family: var(--font-secondary); margin: 0; display: flex; align-items: center; gap: 0.5rem;">
              <span><?php echo $sec_meta['icon']; ?></span>
              <span><?php echo h($sec_meta['name']); ?></span>
            </h2>
          </div>

          <div style="display: flex; align-items: center; gap: 1rem;">
            <!-- Visible on Page Toggle -->
            <label style="display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); background: var(--color-surface-warm); padding: 0.4rem 0.75rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
              <input type="checkbox" name="is_active" value="1" <?php echo ($is_sec_active && !$is_sec_removed) ? 'checked' : ''; ?> <?php echo $is_sec_removed ? 'disabled' : ''; ?> style="width: 16px; height: 16px; cursor: pointer;">
              <span>Visible on Page</span>
            </label>

            <!-- Remove / Restore Button -->
            <?php if ($is_sec_removed): ?>
              <button type="button" onclick="setSectionAction('restore')" class="btn btn-outline" style="border-color: #047857; color: #047857; padding: 0.4rem 0.85rem; font-size: 0.85rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.4rem;">
                <span>↩️</span> Restore Section
              </button>
            <?php else: ?>
              <button type="button" onclick="setSectionAction('remove')" class="btn btn-outline" style="border-color: #dc2626; color: #dc2626; padding: 0.4rem 0.85rem; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.4rem;">
                <span>🗑️</span> Remove Section
              </button>
            <?php endif; ?>
          </div>
        </div>

        <!-- Pending Removal Alert Box -->
        <div id="pendingRemovalBox" style="display: none; background: #fee2e2; border-left: 4px solid #dc2626; padding: 1rem 1.25rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; color: #991b1b; font-size: 0.9rem;">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
              <strong>⚠️ PENDING REMOVAL:</strong> This section will be removed from the Contact page when you click "Save Changes" below.
            </div>
            <button type="button" onclick="cancelSectionAction()" style="background: transparent; border: 1px solid #dc2626; color: #dc2626; padding: 0.25rem 0.65rem; border-radius: 4px; font-weight: 600; cursor: pointer; font-size: 0.8rem;">
              Cancel Removal
            </button>
          </div>
        </div>

        <!-- Section 1: Contact Details & Hours -->
        <?php if ($selected_sec === 'details'): 
          $dt = $contact_cms['details'] ?? [];
        ?>
          <div style="display: grid; gap: 1.25rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
              <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                  Eyebrow / Badge
                </label>
                <input type="text" name="badge" value="<?php echo h($dt['badge'] ?? 'Admissions & Academic Office'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
              </div>
              <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                  Page Heading
                </label>
                <input type="text" name="heading" value="<?php echo h($dt['heading'] ?? 'Contact Us / Enquire Now'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
              </div>
            </div>

            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Subheading / Description
              </label>
              <textarea name="subheading" rows="2" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);"><?php echo h($dt['subheading'] ?? ''); ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
              <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                  Phone Number
                </label>
                <input type="text" name="phone" value="<?php echo h($dt['phone'] ?? '7827262956'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
              </div>
              <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                  WhatsApp Number
                </label>
                <input type="text" name="whatsapp" value="<?php echo h($dt['whatsapp'] ?? '7827262956'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
              </div>
              <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                  Email Address
                </label>
                <input type="email" name="email" value="<?php echo h($dt['email'] ?? 'info@zuvioglobalschool.com'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
              </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
              <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                  Office Address
                </label>
                <textarea name="address" rows="4" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.88rem;"><?php echo h($dt['address'] ?? ''); ?></textarea>
              </div>
              <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                  Office Timings & Days
                </label>
                <input type="text" name="office_hours" value="<?php echo h($dt['office_hours'] ?? 'Monday–Saturday, 10:00 AM–7:00 PM'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
                <div style="font-size: 0.8rem; color: var(--color-muted); margin-top: 0.5rem;">
                  ℹ️ Changes to Phone, Email, Address, and Timings automatically synchronize with the site-wide footer and header.
                </div>
              </div>
            </div>
          </div>

        <!-- Section 2: Enquiry Form Settings -->
        <?php elseif ($selected_sec === 'form'): 
          $fm = $contact_cms['form'] ?? [];
        ?>
          <div style="display: grid; gap: 1.25rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
              <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                  Form Title
                </label>
                <input type="text" name="title" value="<?php echo h($fm['title'] ?? 'Enquire Now'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
              </div>
              <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                  Submit Button Label
                </label>
                <input type="text" name="button_text" value="<?php echo h($fm['button_text'] ?? 'Submit Enquiry Form'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
              </div>
            </div>

            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Form Subtitle / Instruction
              </label>
              <input type="text" name="subtitle" value="<?php echo h($fm['subtitle'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
              <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                  Success Notification Title
                </label>
                <input type="text" name="success_title" value="<?php echo h($fm['success_title'] ?? 'Enquiry Submitted Successfully'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
              </div>
              <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                  Consent / Privacy Disclaimer
                </label>
                <input type="text" name="consent_text" value="<?php echo h($fm['consent_text'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
              </div>
            </div>

            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Success Message Text
              </label>
              <textarea name="success_message" rows="2" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);"><?php echo h($fm['success_message'] ?? ''); ?></textarea>
            </div>
          </div>

        <!-- Section 3: Google Maps Location -->
        <?php elseif ($selected_sec === 'map'): 
          $mp = $contact_cms['map'] ?? [];
        ?>
          <div style="display: grid; gap: 1.25rem;">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
              <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                  Map Section Title
                </label>
                <input type="text" name="title" value="<?php echo h($mp['title'] ?? 'Our Headquarter Office'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
              </div>
              <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                  Embed Frame Height (px)
                </label>
                <input type="number" name="height" value="<?php echo (int)($mp['height'] ?? 420); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
              </div>
            </div>

            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Location Subtitle / Transit Landmark
              </label>
              <input type="text" name="subtitle" value="<?php echo h($mp['subtitle'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>

            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Google Maps Embed URL
              </label>
              <input type="text" name="embed_url" value="<?php echo h($mp['embed_url'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>

            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Google Maps Directions URL
              </label>
              <input type="text" name="directions_url" value="<?php echo h($mp['directions_url'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>
          </div>

        <!-- Section 4: Social Media Links -->
        <?php elseif ($selected_sec === 'social'): 
          $sc = $contact_cms['social'] ?? [];
          $socials = $contact_cms['social_links'] ?? [];
        ?>
          <div style="display: grid; gap: 1.5rem;">
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Section Title
              </label>
              <input type="text" name="title" value="<?php echo h($sc['title'] ?? 'Social Media Channels'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Section Subtitle
              </label>
              <input type="text" name="subtitle" value="<?php echo h($sc['subtitle'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>

            <!-- Existing Social Media Links -->
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.95rem; color: var(--color-navy); margin-bottom: 0.75rem; display: block;">
                Official Social Profiles
              </label>
              <div style="display: grid; gap: 0.75rem;">
                <?php foreach ($socials as $s): ?>
                  <div style="display: grid; grid-template-columns: 140px 1fr auto; gap: 0.75rem; align-items: center; background: var(--color-surface-warm); padding: 0.75rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
                    <div>
                      <strong><?php echo h($s['platform'] ?? ''); ?></strong>
                    </div>
                    <div>
                      <input type="text" name="social_urls[<?php echo (int)($s['id'] ?? 0); ?>]" value="<?php echo h($s['url'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.45rem; border: 1px solid var(--color-border); border-radius: 4px; font-size: 0.85rem;">
                    </div>
                    <div>
                      <button type="button" onclick="deleteItem('delete_social', <?php echo (int)($s['id'] ?? 0); ?>)" class="btn btn-outline" style="border-color: #dc2626; color: #dc2626; padding: 0.25rem 0.6rem; font-size: 0.75rem;">
                        Delete
                      </button>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Add Social Channel -->
            <div style="background: var(--pastel-blue); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
              <h4 style="font-size: 1rem; color: var(--color-navy); margin-bottom: 0.75rem;">Add New Channel</h4>
              <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 0.75rem; margin-bottom: 0.75rem;">
                <input type="text" name="new_platform" placeholder="Platform (e.g. Twitter / X)" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
                <input type="text" name="new_url" placeholder="Profile URL (e.g. https://...)" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
              </div>
              <button type="button" onclick="submitItemAction('add_social')" class="btn btn-outline" style="border-color: var(--color-navy); color: var(--color-navy); font-weight: 700; padding: 0.4rem 1rem; font-size: 0.85rem;">
                + Add Social Channel
              </button>
            </div>
          </div>

        <!-- Section 5: Conversion CTA Banner -->
        <?php elseif ($selected_sec === 'banner'): 
          $bn = $contact_cms['banner'] ?? [];
        ?>
          <div style="display: grid; gap: 1.25rem;">
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Banner Main Heading
              </label>
              <input type="text" name="heading" value="<?php echo h($bn['heading'] ?? 'Ready to Experience Modern Virtual Schooling?'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>

            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Banner Subheading / Pitch
              </label>
              <textarea name="subheading" rows="2" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);"><?php echo h($bn['subheading'] ?? ''); ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
              <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                  Primary CTA Button Label
                </label>
                <input type="text" name="cta_primary_label" value="<?php echo h($bn['cta_primary_label'] ?? 'Book a Free 1-on-1 Demo'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
              </div>
              <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                  Primary CTA Button Link
                </label>
                <input type="text" name="cta_primary_url" value="<?php echo h($bn['cta_primary_url'] ?? '/book-demo'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
              </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
              <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                  Secondary CTA Button Label
                </label>
                <input type="text" name="cta_secondary_label" value="<?php echo h($bn['cta_secondary_label'] ?? 'Admissions & Enrolment'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
              </div>
              <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                  Secondary CTA Button Link
                </label>
                <input type="text" name="cta_secondary_url" value="<?php echo h($bn['cta_secondary_url'] ?? '/admissions'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
              </div>
            </div>
          </div>
        <?php endif; ?>

        <!-- Bottom Actions Bar -->
        <div style="margin-top: 2rem; padding-top: 1.25rem; border-top: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
          <a href="/admin/contact-cms.php?sec=<?php echo urlencode($selected_sec); ?>" class="btn btn-outline" style="padding: 0.65rem 1.25rem; font-size: 0.9rem;">
            ↻ Reset / Reload
          </a>

          <button type="submit" id="saveSubmitBtn" class="btn btn-primary" style="background-color: var(--color-navy); border-color: var(--color-navy); color: #FFFFFF; font-weight: 700; padding: 0.65rem 1.75rem; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 0.5rem;">
            <span>💾</span> Save <?php echo h($sec_meta['name']); ?>
          </button>
        </div>

      </div>
    </form>
  </div>

</div>

<script>
function setSectionAction(action) {
  const actionInput = document.getElementById('sec_action_input');
  const pendingBox = document.getElementById('pendingRemovalBox');
  const saveBtn = document.getElementById('saveSubmitBtn');

  if (action === 'remove') {
    actionInput.value = 'remove';
    if (pendingBox) pendingBox.style.display = 'block';
    if (saveBtn) {
      saveBtn.style.backgroundColor = '#dc2626';
      saveBtn.style.borderColor = '#dc2626';
      saveBtn.innerHTML = '<span>⚠️</span> Confirm Removal &amp; Save';
    }
  } else if (action === 'restore') {
    actionInput.value = 'restore';
    document.getElementById('sectionForm').submit();
  }
}

function cancelSectionAction() {
  const actionInput = document.getElementById('sec_action_input');
  const pendingBox = document.getElementById('pendingRemovalBox');
  const saveBtn = document.getElementById('saveSubmitBtn');

  actionInput.value = '';
  if (pendingBox) pendingBox.style.display = 'none';
  if (saveBtn) {
    saveBtn.style.backgroundColor = 'var(--color-navy)';
    saveBtn.style.borderColor = 'var(--color-navy)';
    saveBtn.innerHTML = '<span>💾</span> Save <?php echo addslashes(h($sec_meta['name'])); ?>';
  }
}

function submitItemAction(actionName) {
  document.getElementById('form_action_input').value = actionName;
  document.getElementById('sectionForm').submit();
}

function deleteItem(actionName, id) {
  if (confirm('Are you sure you want to delete this channel?')) {
    document.getElementById('form_action_input').value = actionName;
    document.getElementById('item_id_input').value = id;
    document.getElementById('sectionForm').submit();
  }
}
</script>

<?php
include_once dirname(__FILE__) . '/footer.php';
