<?php
// Zuvio Global School - Admin Admissions Enquiries
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_permission('enquiries.view');

// Explicitly enforce PHP timezone to Asia/Kolkata
if (date_default_timezone_get() !== 'Asia/Kolkata') {
    date_default_timezone_set('Asia/Kolkata');
}

// Detect database session timezone and ensure MySQL connection is in IST (+05:30)
$db_is_ist = false;
if (isset($db) && $db) {
    try {
        if ($db->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql') {
            $db->exec("SET time_zone = '+05:30'");
            $sess_tz = $db->query("SELECT @@session.time_zone")->fetchColumn();
            
            // In MySQL, only TIMESTAMP columns auto-convert based on @@session.time_zone.
            // DATETIME columns are literal and do not convert.
            $col_type = 'timestamp';
            try {
                $col_type_stmt = $db->query("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'enquiries' AND COLUMN_NAME = 'created_at'");
                $col_type = strtolower($col_type_stmt->fetchColumn() ?: 'timestamp');
            } catch (Exception $e) {}
            
            $db_is_ist = ($sess_tz === '+05:30' && $col_type === 'timestamp');
        }
    } catch (Exception $tz_ex) {
        $db_is_ist = false;
    }
}

if (!function_exists('format_enquiry_datetime')) {
    /**
     * Consistently format enquiry timestamps in Asia/Kolkata (IST, UTC+05:30).
     * Handles:
     * 1. MySQL TIMESTAMP when @@session.time_zone = '+05:30' (already converted by MySQL driver)
     * 2. MySQL TIMESTAMP / DATETIME when returned in UTC (converts from UTC to Asia/Kolkata)
     * 3. Session mock leads (already created with PHP date() in Asia/Kolkata)
     * Prevents double conversion under all scenarios.
     */
    function format_enquiry_datetime($date_str, $already_ist = false) {
        if (empty($date_str)) return '—';
        if ($already_ist) {
            $ts = strtotime($date_str);
            return $ts ? date('Y-m-d H:i', $ts) : $date_str;
        }
        try {
            // Driver returned UTC (e.g. SQLite or unconverted DATETIME) -> convert to Asia/Kolkata
            $dt = new DateTime($date_str, new DateTimeZone('UTC'));
            $dt->setTimezone(new DateTimeZone('Asia/Kolkata'));
            return $dt->format('Y-m-d H:i');
        } catch (Exception $e) {
            $ts = strtotime($date_str);
            return $ts ? date('Y-m-d H:i', $ts) : $date_str;
        }
    }
}

if (!function_exists('render_marketing_source_badge')) {
    function render_marketing_source_badge($source) {
        $src = trim((string)$source) ?: 'Direct';
        $bg = '#F3F4F6';
        $color = '#374151';
        $border = '#E5E7EB';

        switch ($src) {
            case 'Google Ads':
                $bg = '#E8F0FE'; $color = '#1967D2'; $border = '#D2E3FC'; break;
            case 'Facebook Ads':
                $bg = '#E7F3FF'; $color = '#1877F2'; $border = '#C8E1FF'; break;
            case 'Instagram Ads':
                $bg = '#FCE7F3'; $color = '#BE185D'; $border = '#FBCFE8'; break;
            case 'Organic Search':
                $bg = '#DCFCE7'; $color = '#15803D'; $border = '#BBF7D0'; break;
            case 'Referral':
                $bg = '#FEF3C7'; $color = '#B45309'; $border = '#FDE68A'; break;
            case 'Other / Unknown':
                $bg = '#EDE9FE'; $color = '#6D28D9'; $border = '#DDD6FE'; break;
            case 'Direct':
            default:
                $bg = '#F3F4F6'; $color = '#4B5563'; $border = '#E5E7EB'; break;
        }

        return '<span style="display: inline-flex; align-items: center; padding: 0.2rem 0.55rem; font-size: 0.72rem; border-radius: 9999px; font-weight: 700; background:' . $bg . '; color:' . $color . '; border: 1px solid ' . $border . '; white-space: nowrap;">' . htmlspecialchars($src) . '</span>';
    }
}

// Purge any stale homeschooling mock leads from session
if (!empty($_SESSION['mock_enquiries'])) {
    $_SESSION['mock_enquiries'] = array_values(array_filter($_SESSION['mock_enquiries'], function($m) {
        $src = $m['source'] ?? '';
        return stripos($src, 'Homeschool') === false;
    }));
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = $_GET['action'] ?? 'list';
$msg = '';
$error = '';

// Update status handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status']) && $id > 0) {
    require_permission('enquiries.update');
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        $status_id = (int)$_POST['status_id'];
        $src_redirect = trim($_POST['source'] ?? '');
        try {
            $old_stmt = $db->prepare("SELECT * FROM `enquiries` WHERE `id` = ? LIMIT 1");
            $old_stmt->execute([$id]);
            $old_enquiry = $old_stmt->fetch();
            
            $stmt = $db->prepare("UPDATE `enquiries` SET `status_id` = ? WHERE `id` = ?");
            $stmt->execute([$status_id, $id]);
            
            log_audit('ENQUIRY_UPDATED', 'enquiries', 'enquiries', $id, $old_enquiry, ['status_id' => $status_id], "Updated enquiry ID {$id} status");
            $redir = '/admin/enquiries?msg=status_updated' . ($src_redirect ? '&source=' . urlencode($src_redirect) : '');
            header('Location: ' . $redir);
            exit;
        } catch (Exception $e) {
            $error = 'Failed to update status: ' . $e->getMessage();
        }
    }
}

// Fetch all status definitions
$statuses = [];
if ($db) {
    try {
        $statuses = $db->query("SELECT * FROM `enquiry_statuses` ORDER BY `id` ASC")->fetchAll();
    } catch (Exception $e) {
        error_log("[Enquiry Status Fetch Error] " . $e->getMessage());
    }
}

// Fetch single enquiry details
$enq = null;
if ($action === 'view' && $id > 0) {
    try {
        $stmt = $db->prepare("
            SELECT e.*, s.name as status_name 
            FROM `enquiries` e
            LEFT JOIN `enquiry_statuses` s ON s.id = e.status_id
            WHERE e.id = ? LIMIT 1
        ");
        $stmt->execute([$id]);
        $enq = $stmt->fetch();
        if (!$enq && !empty($_SESSION['mock_enquiries'])) {
            foreach ($_SESSION['mock_enquiries'] as $m) {
                if ((int)($m['id'] ?? 0) === $id && stripos($m['source'] ?? '', 'Homeschool') === false) {
                    $enq = [
                        'id'          => $m['id'],
                        'parent_name' => $m['parent_name'] ?? '',
                        'student_name'=> $m['student_name'] ?? '',
                        'grade'       => $m['grade'] ?? '',
                        'phone'       => $m['phone'] ?? '',
                        'email'       => $m['email'] ?? '',
                        'message'     => $m['message'] ?? '',
                        'source'      => $m['source'] ?? '',
                        'status_id'   => $m['status_id'] ?? 1,
                        'status_name' => 'New',
                        'created_at'  => $m['created_at'] ?? date('Y-m-d H:i:s'),
                        'is_mock'     => true
                    ];
                    break;
                }
            }
        }
        if (!$enq) {
            header('Location: /admin/enquiries?error=notfound');
            exit;
        }
    } catch (Exception $e) {
        $error = "Error loading enquiry details.";
    }
}

$source_tab = trim($_GET['source'] ?? ''); // 'homepage', 'contact', 'landing_page', or ''

// Filter parameters
$search_q          = trim($_GET['q'] ?? '');
$status_filter     = (int)($_GET['status_id'] ?? 0);
$filter_mktg       = trim($_GET['mktg_source'] ?? '');
$filter_campaign   = trim($_GET['campaign'] ?? '');
$filter_medium     = trim($_GET['medium'] ?? '');
$filter_landing    = trim($_GET['landing_page'] ?? '');
$filter_date_from  = trim($_GET['date_from'] ?? '');
$filter_date_to    = trim($_GET['date_to'] ?? '');

// Calculate lead category counts
$count_all = 0;
$count_homepage = 0;
$count_contact = 0;
$count_landing = 0;

// Calculate 6 Channel Summary Metrics
$metric_total = 0;
$metric_google_ads = 0;
$metric_fb_ads = 0;
$metric_ig_ads = 0;
$metric_organic = 0;
$metric_direct_other = 0;

if ($db) {
    try {
        $count_all = (int)$db->query("SELECT COUNT(*) FROM `enquiries`")->fetchColumn();
        $count_homepage = (int)$db->query("SELECT COUNT(*) FROM `enquiries` WHERE `source` NOT LIKE '%Landing%' AND (`source` LIKE '%Home%' OR `source` LIKE '%Counselor%' OR `source` LIKE '%Banner%')")->fetchColumn();
        $count_contact = (int)$db->query("SELECT COUNT(*) FROM `enquiries` WHERE `source` LIKE '%Contact%'")->fetchColumn();
        $count_landing = (int)$db->query("SELECT COUNT(*) FROM `enquiries` WHERE `source` LIKE '%Landing%'")->fetchColumn();

        $metric_total = $count_all;
        $metric_google_ads = (int)$db->query("SELECT COUNT(*) FROM `enquiries` WHERE `marketing_source` = 'Google Ads'")->fetchColumn();
        $metric_fb_ads = (int)$db->query("SELECT COUNT(*) FROM `enquiries` WHERE `marketing_source` = 'Facebook Ads'")->fetchColumn();
        $metric_ig_ads = (int)$db->query("SELECT COUNT(*) FROM `enquiries` WHERE `marketing_source` = 'Instagram Ads'")->fetchColumn();
        $metric_organic = (int)$db->query("SELECT COUNT(*) FROM `enquiries` WHERE `marketing_source` = 'Organic Search'")->fetchColumn();
        $metric_direct_other = (int)$db->query("SELECT COUNT(*) FROM `enquiries` WHERE `marketing_source` IN ('Direct', 'Referral', 'Other / Unknown') OR `marketing_source` IS NULL OR `marketing_source` = ''")->fetchColumn();
    } catch (Exception $e) {}
}

if (!empty($_SESSION['mock_enquiries'])) {
    foreach ($_SESSION['mock_enquiries'] as $m) {
        $m_src = $m['source'] ?? '';
        if (stripos($m_src, 'Homeschool') !== false) {
            continue;
        }
        $count_all++;
        $metric_total++;
        if (stripos($m_src, 'Landing') === false && (stripos($m_src, 'Home') !== false || stripos($m_src, 'Counselor') !== false || stripos($m_src, 'Banner') !== false)) {
            $count_homepage++;
        } elseif (stripos($m_src, 'Contact') !== false) {
            $count_contact++;
        } elseif (stripos($m_src, 'Landing') !== false) {
            $count_landing++;
        }

        $m_mktg = $m['marketing_source'] ?? 'Direct';
        if ($m_mktg === 'Google Ads') $metric_google_ads++;
        elseif ($m_mktg === 'Facebook Ads') $metric_fb_ads++;
        elseif ($m_mktg === 'Instagram Ads') $metric_ig_ads++;
        elseif ($m_mktg === 'Organic Search') $metric_organic++;
        else $metric_direct_other++;
    }
}

// Build standard query conditions for both listing and export
$where_clauses = ['1=1'];
$query_params = [];

if ($source_tab === 'homepage') {
    $where_clauses[] = "e.source NOT LIKE '%Landing%' AND (e.source LIKE '%Home%' OR e.source LIKE '%Counselor%' OR e.source LIKE '%Banner%')";
} elseif ($source_tab === 'contact') {
    $where_clauses[] = "e.source LIKE '%Contact%'";
} elseif ($source_tab === 'landing_page' || $source_tab === 'landing') {
    $where_clauses[] = "e.source LIKE '%Landing%'";
}

if ($status_filter > 0) {
    $where_clauses[] = "e.status_id = ?";
    $query_params[] = $status_filter;
}

if ($filter_mktg !== '') {
    if ($filter_mktg === 'Direct / Other') {
        $where_clauses[] = "(e.marketing_source IN ('Direct', 'Referral', 'Other / Unknown') OR e.marketing_source IS NULL OR e.marketing_source = '')";
    } else {
        $where_clauses[] = "e.marketing_source = ?";
        $query_params[] = $filter_mktg;
    }
}

if ($filter_campaign !== '') {
    $where_clauses[] = "e.utm_campaign LIKE ?";
    $query_params[] = "%$filter_campaign%";
}

if ($filter_medium !== '') {
    $where_clauses[] = "e.utm_medium LIKE ?";
    $query_params[] = "%$filter_medium%";
}

if ($filter_landing !== '') {
    $where_clauses[] = "e.landing_page LIKE ?";
    $query_params[] = "%$filter_landing%";
}

if ($filter_date_from !== '') {
    $where_clauses[] = "e.created_at >= ?";
    $query_params[] = $filter_date_from . ' 00:00:00';
}

if ($filter_date_to !== '') {
    $where_clauses[] = "e.created_at <= ?";
    $query_params[] = $filter_date_to . ' 23:59:59';
}

if ($search_q !== '') {
    $where_clauses[] = "(e.parent_name LIKE ? OR e.student_name LIKE ? OR e.email LIKE ? OR e.phone LIKE ? OR e.utm_campaign LIKE ? OR e.gclid LIKE ? OR e.fbclid LIKE ?)";
    $wild = "%$search_q%";
    $query_params = array_merge($query_params, [$wild, $wild, $wild, $wild, $wild, $wild, $wild]);
}

$where_sql = implode(' AND ', $where_clauses);

// Handle Enriched CSV Export
if ($action === 'export_csv') {
    require_permission('enquiries.view');
    $export_rows = [];
    if ($db) {
        try {
            $export_sql = "
                SELECT e.*, s.name as status_name
                FROM `enquiries` e
                LEFT JOIN `enquiry_statuses` s ON s.id = e.status_id
                WHERE {$where_sql}
                ORDER BY e.created_at DESC
            ";
            $exp_stmt = $db->prepare($export_sql);
            $exp_stmt->execute($query_params);
            $export_rows = $exp_stmt->fetchAll();
        } catch (Exception $e) {
            error_log("[CSV Export Error] " . $e->getMessage());
        }
    }

    $filename_prefix = 'zuvio_enquiries_';
    if ($source_tab === 'homepage') $filename_prefix = 'zuvio_homepage_leads_';
    if ($source_tab === 'contact') $filename_prefix = 'zuvio_contact_leads_';
    if ($source_tab === 'landing_page' || $source_tab === 'landing') $filename_prefix = 'zuvio_landing_page_leads_';

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename_prefix . date('Y-m-d_His') . '.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, [
        'ID',
        'Parent Name',
        'Student Name',
        'Email',
        'Phone',
        'Grade',
        'Form Source',
        'Marketing Source',
        'UTM Source',
        'UTM Medium',
        'UTM Campaign',
        'UTM Content',
        'UTM Term',
        'Google Click ID (gclid)',
        'Facebook Click ID (fbclid)',
        'Landing Page',
        'Referrer',
        'Status',
        'Message',
        'Created At (IST)'
    ]);
    foreach ($export_rows as $r) {
        fputcsv($output, [
            $r['id'] ?? '',
            $r['parent_name'] ?? '',
            $r['student_name'] ?: (($r['parent_name'] ?? '') . ' (Student)'),
            $r['email'] ?? '',
            $r['phone'] ?? '',
            $r['grade'] ?? '',
            $r['source'] ?? '',
            $r['marketing_source'] ?: 'Direct',
            $r['utm_source'] ?? '',
            $r['utm_medium'] ?? '',
            $r['utm_campaign'] ?? '',
            $r['utm_content'] ?? '',
            $r['utm_term'] ?? '',
            $r['gclid'] ?? '',
            $r['fbclid'] ?? '',
            $r['landing_page'] ?? '',
            $r['referrer'] ?? '',
            $r['status_name'] ?? 'NEW',
            $r['message'] ?? '',
            format_enquiry_datetime($r['created_at'] ?? '', !empty($r['is_mock']) || $db_is_ist)
        ]);
    }
    fclose($output);
    exit;
}

$enquiries = [];
if ($action === 'list') {
    if ($db) {
        try {
            $sql = "
                SELECT e.*, s.name as status_name 
                FROM `enquiries` e
                LEFT JOIN `enquiry_statuses` s ON s.id = e.status_id
                WHERE {$where_sql}
                ORDER BY e.created_at DESC
            ";
            $stmt = $db->prepare($sql);
            $stmt->execute($query_params);
            $enquiries = $stmt->fetchAll();
        } catch (Exception $e) {
            $error = "Database queries failed: " . $e->getMessage();
        }
    }
}

$page_slug = 'admin-enquiries';
include_once dirname(__FILE__) . '/header.php';

// Dynamic Titles
$page_title = 'Lead Management CRM';
$page_sub = 'Manage prospective student admissions enquiries and leads.';
if ($source_tab === 'homepage') {
    $page_title = 'Homepage Leads CRM';
    $page_sub = 'Prospective student leads received directly from the Homepage Counselor enquiry form.';
} elseif ($source_tab === 'contact') {
    $page_title = 'Contact Us Leads CRM';
    $page_sub = 'Inquiries and parent messages received from the Contact Us form.';
} elseif ($source_tab === 'landing_page' || $source_tab === 'landing') {
    $page_title = 'Landing Page Leads CRM';
    $page_sub = 'Prospective student leads received directly from the Landing Page enquiry form.';
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.5rem; color: var(--color-navy); margin-bottom: 0.25rem;"><?php echo h($page_title); ?></h1>
    <p style="color: var(--color-muted); font-size: 0.85rem;"><?php echo h($page_sub); ?></p>
  </div>
  <div style="display: flex; gap: 0.75rem; align-items: center;">
    <?php if ($action === 'list'): ?>
      <?php
        $export_qs = http_build_query(array_filter([
          'action'      => 'export_csv',
          'source'      => $source_tab,
          'q'           => $search_q,
          'status_id'   => $status_filter,
          'mktg_source' => $filter_mktg,
          'campaign'    => $filter_campaign,
          'medium'      => $filter_medium,
          'landing_page'=> $filter_landing,
          'date_from'   => $filter_date_from,
          'date_to'     => $filter_date_to
        ]));
      ?>
      <a href="/admin/enquiries?<?php echo $export_qs; ?>" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem; background: #047857; border-color: #047857;">
        📥 Export CSV
      </a>
    <?php else: ?>
      <a href="/admin/enquiries<?php echo $source_tab ? '?source=' . urlencode($source_tab) : ''; ?>" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">&larr; Back to Leads</a>
    <?php endif; ?>
  </div>
</div>

<?php if ($action === 'list'): ?>
  <!-- 6 Channel Summary Metrics -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
    <!-- 1. Total Leads -->
    <a href="/admin/enquiries<?php echo $source_tab ? '?source=' . urlencode($source_tab) : ''; ?>" style="text-decoration: none; color: inherit;">
      <div class="card" style="padding: 1.25rem; border-left: 4px solid var(--color-navy); border-top: none; background: #fff; transition: transform 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
        <div style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700; color: var(--color-muted); margin-bottom: 0.35rem; display: flex; justify-content: space-between;">
          <span>Total Leads</span>
          <span>📊</span>
        </div>
        <div style="font-size: 1.75rem; font-weight: 800; color: var(--color-navy); line-height: 1.2;"><?php echo $metric_total; ?></div>
        <div style="font-size: 0.72rem; color: var(--color-muted); margin-top: 0.25rem;">All acquisitions</div>
      </div>
    </a>

    <!-- 2. Google Ads Leads -->
    <a href="/admin/enquiries?mktg_source=Google+Ads<?php echo $source_tab ? '&source=' . urlencode($source_tab) : ''; ?>" style="text-decoration: none; color: inherit;">
      <div class="card" style="padding: 1.25rem; border-left: 4px solid #1A73E8; border-top: none; background: <?php echo $filter_mktg === 'Google Ads' ? '#EFF6FF' : '#fff'; ?>; transition: transform 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
        <div style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700; color: #1967D2; margin-bottom: 0.35rem; display: flex; justify-content: space-between;">
          <span>Google Ads</span>
          <span>🎯</span>
        </div>
        <div style="font-size: 1.75rem; font-weight: 800; color: #1A73E8; line-height: 1.2;"><?php echo $metric_google_ads; ?></div>
        <div style="font-size: 0.72rem; color: var(--color-muted); margin-top: 0.25rem;">Paid search & gclid</div>
      </div>
    </a>

    <!-- 3. Facebook Ads Leads -->
    <a href="/admin/enquiries?mktg_source=Facebook+Ads<?php echo $source_tab ? '&source=' . urlencode($source_tab) : ''; ?>" style="text-decoration: none; color: inherit;">
      <div class="card" style="padding: 1.25rem; border-left: 4px solid #1877F2; border-top: none; background: <?php echo $filter_mktg === 'Facebook Ads' ? '#EFF6FF' : '#fff'; ?>; transition: transform 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
        <div style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700; color: #1877F2; margin-bottom: 0.35rem; display: flex; justify-content: space-between;">
          <span>Facebook Ads</span>
          <span>👥</span>
        </div>
        <div style="font-size: 1.75rem; font-weight: 800; color: #1877F2; line-height: 1.2;"><?php echo $metric_fb_ads; ?></div>
        <div style="font-size: 0.72rem; color: var(--color-muted); margin-top: 0.25rem;">Meta feed & campaigns</div>
      </div>
    </a>

    <!-- 4. Instagram Ads Leads -->
    <a href="/admin/enquiries?mktg_source=Instagram+Ads<?php echo $source_tab ? '&source=' . urlencode($source_tab) : ''; ?>" style="text-decoration: none; color: inherit;">
      <div class="card" style="padding: 1.25rem; border-left: 4px solid #C13584; border-top: none; background: <?php echo $filter_mktg === 'Instagram Ads' ? '#FDF2F8' : '#fff'; ?>; transition: transform 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
        <div style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700; color: #C13584; margin-bottom: 0.35rem; display: flex; justify-content: space-between;">
          <span>Instagram Ads</span>
          <span>📷</span>
        </div>
        <div style="font-size: 1.75rem; font-weight: 800; color: #C13584; line-height: 1.2;"><?php echo $metric_ig_ads; ?></div>
        <div style="font-size: 0.72rem; color: var(--color-muted); margin-top: 0.25rem;">Reels & sponsored posts</div>
      </div>
    </a>

    <!-- 5. Organic Search Leads -->
    <a href="/admin/enquiries?mktg_source=Organic+Search<?php echo $source_tab ? '&source=' . urlencode($source_tab) : ''; ?>" style="text-decoration: none; color: inherit;">
      <div class="card" style="padding: 1.25rem; border-left: 4px solid #16A34A; border-top: none; background: <?php echo $filter_mktg === 'Organic Search' ? '#F0FDF4' : '#fff'; ?>; transition: transform 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
        <div style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700; color: #15803D; margin-bottom: 0.35rem; display: flex; justify-content: space-between;">
          <span>Organic Search</span>
          <span>🔍</span>
        </div>
        <div style="font-size: 1.75rem; font-weight: 800; color: #16A34A; line-height: 1.2;"><?php echo $metric_organic; ?></div>
        <div style="font-size: 0.72rem; color: var(--color-muted); margin-top: 0.25rem;">Google/Bing SEO</div>
      </div>
    </a>

    <!-- 6. Direct / Other Leads -->
    <a href="/admin/enquiries?mktg_source=Direct<?php echo $source_tab ? '&source=' . urlencode($source_tab) : ''; ?>" style="text-decoration: none; color: inherit;">
      <div class="card" style="padding: 1.25rem; border-left: 4px solid #6B7280; border-top: none; background: <?php echo $filter_mktg === 'Direct' ? '#F9FAFB' : '#fff'; ?>; transition: transform 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
        <div style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700; color: #4B5563; margin-bottom: 0.35rem; display: flex; justify-content: space-between;">
          <span>Direct / Other</span>
          <span>🌐</span>
        </div>
        <div style="font-size: 1.75rem; font-weight: 800; color: #4B5563; line-height: 1.2;"><?php echo $metric_direct_other; ?></div>
        <div style="font-size: 0.72rem; color: var(--color-muted); margin-top: 0.25rem;">Direct, referral & other</div>
      </div>
    </a>
  </div>

  <!-- Lead Category Form Source Tabs -->
  <div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; border-bottom: 2px solid var(--color-border); padding-bottom: 0.5rem; flex-wrap: wrap;">
    <a href="/admin/enquiries" style="padding: 0.5rem 1rem; border-radius: var(--radius-sm); font-size: 0.85rem; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; <?php echo empty($source_tab) ? 'background: var(--color-navy); color: #fff;' : 'background: #fff; color: var(--color-navy); border: 1px solid var(--color-border);'; ?>">
      <span>All Form Sources</span>
      <span style="background: <?php echo empty($source_tab) ? 'var(--color-gold)' : 'var(--color-surface-blue)'; ?>; color: <?php echo empty($source_tab) ? 'var(--color-navy-dark)' : 'var(--color-navy)'; ?>; padding: 0.15rem 0.45rem; border-radius: 12px; font-size: 0.75rem; font-weight: 700;"><?php echo $count_all; ?></span>
    </a>
    <a href="/admin/enquiries?source=homepage" style="padding: 0.5rem 1rem; border-radius: var(--radius-sm); font-size: 0.85rem; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; <?php echo $source_tab === 'homepage' ? 'background: var(--color-navy); color: #fff;' : 'background: #fff; color: var(--color-navy); border: 1px solid var(--color-border);'; ?>">
      <span>🏠 Homepage Leads</span>
      <span style="background: <?php echo $source_tab === 'homepage' ? 'var(--color-gold)' : 'var(--color-surface-blue)'; ?>; color: <?php echo $source_tab === 'homepage' ? 'var(--color-navy-dark)' : 'var(--color-navy)'; ?>; padding: 0.15rem 0.45rem; border-radius: 12px; font-size: 0.75rem; font-weight: 700;"><?php echo $count_homepage; ?></span>
    </a>
    <a href="/admin/enquiries?source=contact" style="padding: 0.5rem 1rem; border-radius: var(--radius-sm); font-size: 0.85rem; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; <?php echo $source_tab === 'contact' ? 'background: var(--color-navy); color: #fff;' : 'background: #fff; color: var(--color-navy); border: 1px solid var(--color-border);'; ?>">
      <span>✉️ Contact Us Leads</span>
      <span style="background: <?php echo $source_tab === 'contact' ? 'var(--color-gold)' : 'var(--color-surface-blue)'; ?>; color: <?php echo $source_tab === 'contact' ? 'var(--color-navy-dark)' : 'var(--color-navy)'; ?>; padding: 0.15rem 0.45rem; border-radius: 12px; font-size: 0.75rem; font-weight: 700;"><?php echo $count_contact; ?></span>
    </a>
    <a href="/admin/enquiries?source=landing_page" style="padding: 0.5rem 1rem; border-radius: var(--radius-sm); font-size: 0.85rem; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; <?php echo ($source_tab === 'landing_page' || $source_tab === 'landing') ? 'background: var(--color-navy); color: #fff;' : 'background: #fff; color: var(--color-navy); border: 1px solid var(--color-border);'; ?>">
      <span>📄 Landing Page Leads</span>
      <span style="background: <?php echo ($source_tab === 'landing_page' || $source_tab === 'landing') ? 'var(--color-gold)' : 'var(--color-surface-blue)'; ?>; color: <?php echo ($source_tab === 'landing_page' || $source_tab === 'landing') ? 'var(--color-navy-dark)' : 'var(--color-navy)'; ?>; padding: 0.15rem 0.45rem; border-radius: 12px; font-size: 0.75rem; font-weight: 700;"><?php echo $count_landing; ?></span>
    </a>
  </div>

  <!-- Filters Bar with Marketing Attribution Controls -->
  <form method="GET" action="/admin/enquiries" style="background: #fff; padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border); margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
    <?php if ($source_tab): ?>
      <input type="hidden" name="source" value="<?php echo h($source_tab); ?>">
    <?php endif; ?>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 0.75rem; margin-bottom: 0.85rem;">
      <div>
        <label style="font-size: 0.72rem; font-weight: 700; color: var(--color-muted); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">Search</label>
        <input type="text" name="q" value="<?php echo h($search_q); ?>" placeholder="Name, email, phone, click ID..." style="width: 100%; padding: 0.45rem 0.65rem; border: 1px solid var(--color-border); border-radius: 4px; font-size: 0.85rem;">
      </div>
      <div>
        <label style="font-size: 0.72rem; font-weight: 700; color: var(--color-muted); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">Marketing Channel</label>
        <select name="mktg_source" style="width: 100%; padding: 0.45rem 0.65rem; border: 1px solid var(--color-border); border-radius: 4px; font-size: 0.85rem; background: #fff;">
          <option value="">All Marketing Channels</option>
          <option value="Google Ads" <?php echo $filter_mktg === 'Google Ads' ? 'selected' : ''; ?>>Google Ads</option>
          <option value="Facebook Ads" <?php echo $filter_mktg === 'Facebook Ads' ? 'selected' : ''; ?>>Facebook Ads</option>
          <option value="Instagram Ads" <?php echo $filter_mktg === 'Instagram Ads' ? 'selected' : ''; ?>>Instagram Ads</option>
          <option value="Organic Search" <?php echo $filter_mktg === 'Organic Search' ? 'selected' : ''; ?>>Organic Search</option>
          <option value="Direct" <?php echo $filter_mktg === 'Direct' ? 'selected' : ''; ?>>Direct</option>
          <option value="Referral" <?php echo $filter_mktg === 'Referral' ? 'selected' : ''; ?>>Referral</option>
          <option value="Other / Unknown" <?php echo $filter_mktg === 'Other / Unknown' ? 'selected' : ''; ?>>Other / Unknown</option>
        </select>
      </div>
      <div>
        <label style="font-size: 0.72rem; font-weight: 700; color: var(--color-muted); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">Campaign</label>
        <input type="text" name="campaign" value="<?php echo h($filter_campaign); ?>" placeholder="utm_campaign..." style="width: 100%; padding: 0.45rem 0.65rem; border: 1px solid var(--color-border); border-radius: 4px; font-size: 0.85rem;">
      </div>
      <div>
        <label style="font-size: 0.72rem; font-weight: 700; color: var(--color-muted); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">Medium</label>
        <input type="text" name="medium" value="<?php echo h($filter_medium); ?>" placeholder="cpc, organic, reel..." style="width: 100%; padding: 0.45rem 0.65rem; border: 1px solid var(--color-border); border-radius: 4px; font-size: 0.85rem;">
      </div>
      <div>
        <label style="font-size: 0.72rem; font-weight: 700; color: var(--color-muted); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">Landing Page</label>
        <input type="text" name="landing_page" value="<?php echo h($filter_landing); ?>" placeholder="URL or slug..." style="width: 100%; padding: 0.45rem 0.65rem; border: 1px solid var(--color-border); border-radius: 4px; font-size: 0.85rem;">
      </div>
      <div>
        <label style="font-size: 0.72rem; font-weight: 700; color: var(--color-muted); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">Lead Status</label>
        <select name="status_id" style="width: 100%; padding: 0.45rem 0.65rem; border: 1px solid var(--color-border); border-radius: 4px; font-size: 0.85rem; background: #fff;">
          <option value="0">All Statuses</option>
          <?php foreach ($statuses as $st): ?>
            <option value="<?php echo $st['id']; ?>" <?php echo $status_filter == $st['id'] ? 'selected' : ''; ?>>
              <?php echo h($st['name']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label style="font-size: 0.72rem; font-weight: 700; color: var(--color-muted); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">Date From</label>
        <input type="date" name="date_from" value="<?php echo h($filter_date_from); ?>" style="width: 100%; padding: 0.45rem 0.65rem; border: 1px solid var(--color-border); border-radius: 4px; font-size: 0.85rem;">
      </div>
      <div>
        <label style="font-size: 0.72rem; font-weight: 700; color: var(--color-muted); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">Date To</label>
        <input type="date" name="date_to" value="<?php echo h($filter_date_to); ?>" style="width: 100%; padding: 0.45rem 0.65rem; border: 1px solid var(--color-border); border-radius: 4px; font-size: 0.85rem;">
      </div>
    </div>
    
    <div style="display: flex; gap: 0.75rem; align-items: center; justify-content: flex-end; padding-top: 0.5rem; border-top: 1px solid #f1f5f9;">
      <?php if ($search_q !== '' || $status_filter > 0 || $filter_mktg !== '' || $filter_campaign !== '' || $filter_medium !== '' || $filter_landing !== '' || $filter_date_from !== '' || $filter_date_to !== ''): ?>
        <a href="/admin/enquiries<?php echo $source_tab ? '?source=' . urlencode($source_tab) : ''; ?>" style="font-size: 0.85rem; color: #EF4444; text-decoration: none; margin-right: 0.5rem; font-weight: 600;">✕ Reset Filters</a>
      <?php endif; ?>
      <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.85rem;">Apply Filters</button>
    </div>
  </form>
<?php endif; ?>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'status_updated'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Enquiry status updated successfully.
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="error-alert">
    <?php echo h($error); ?>
  </div>
<?php endif; ?>

<!-- LIST VIEW -->
<?php if ($action === 'list'): ?>
  <div class="card" style="border-left: none; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
    <?php if (!empty($enquiries)): ?>
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.82rem; text-align: left;">
          <thead>
            <tr style="border-bottom: 2px solid var(--color-border); color: var(--color-navy); font-weight: 700; background: #fafafa;">
              <th style="padding: 0.75rem 0.85rem;">ID</th>
              <th style="padding: 0.75rem 0.85rem;">Lead Contact</th>
              <th style="padding: 0.75rem 0.85rem;">Grade</th>
              <th style="padding: 0.75rem 0.85rem;">Form Source</th>
              <th style="padding: 0.75rem 0.85rem;">Marketing Source</th>
              <th style="padding: 0.75rem 0.85rem;">Campaign / Medium</th>
              <th style="padding: 0.75rem 0.85rem;">Landing Page</th>
              <th style="padding: 0.75rem 0.85rem;">Date (IST)</th>
              <th style="padding: 0.75rem 0.85rem;">Status</th>
              <th style="padding: 0.75rem 0.85rem; text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($enquiries as $row): ?>
              <?php
                $clean_lp = '';
                if (!empty($row['landing_page'])) {
                    $parsed_lp = parse_url($row['landing_page'], PHP_URL_PATH) ?: $row['landing_page'];
                    $clean_lp = (strlen($parsed_lp) > 28) ? substr($parsed_lp, 0, 26) . '…' : $parsed_lp;
                }
              ?>
              <tr style="border-bottom: 1px solid var(--color-border); color: var(--color-text);">
                <td style="padding: 0.75rem 0.85rem; font-weight: 700; color: var(--color-muted);">#<?php echo h($row['id']); ?></td>
                <td style="padding: 0.75rem 0.85rem;">
                  <div style="font-weight: 700; color: var(--color-navy);"><?php echo h($row['parent_name']); ?></div>
                  <div style="font-size: 0.75rem; color: var(--color-muted);"><?php echo h($row['student_name'] ?: ($row['parent_name'] . ' (Student)')); ?></div>
                  <div style="font-size: 0.75rem; color: var(--color-text); margin-top: 0.2rem;">
                    <a href="tel:<?php echo h($row['phone']); ?>" style="color: inherit; text-decoration: none;">📞 <?php echo h($row['phone']); ?></a>
                    <span style="color: #cbd5e1; margin: 0 0.25rem;">•</span>
                    <a href="mailto:<?php echo h($row['email']); ?>" style="color: inherit; text-decoration: none;">✉️ <?php echo h($row['email']); ?></a>
                  </div>
                </td>
                <td style="padding: 0.75rem 0.85rem; font-weight: 600;"><?php echo h($row['grade']); ?></td>
                <td style="padding: 0.75rem 0.85rem;">
                  <span style="display: inline-block; padding: 0.2rem 0.5rem; font-size: 0.7rem; border-radius: var(--radius-sm); font-weight: 600; background: rgba(6, 43, 99, 0.08); color: var(--color-navy); white-space: nowrap;">
                    <?php echo h($row['source']); ?>
                  </span>
                </td>
                <td style="padding: 0.75rem 0.85rem;">
                  <?php echo render_marketing_source_badge($row['marketing_source'] ?? 'Direct'); ?>
                </td>
                <td style="padding: 0.75rem 0.85rem;">
                  <?php if (!empty($row['utm_campaign']) || !empty($row['utm_medium'])): ?>
                    <div style="font-weight: 600; color: var(--color-navy);"><?php echo h($row['utm_campaign'] ?: '—'); ?></div>
                    <div style="font-size: 0.72rem; color: var(--color-muted);"><?php echo h($row['utm_medium'] ?: '—'); ?></div>
                  <?php else: ?>
                    <span style="color: var(--color-muted);">—</span>
                  <?php endif; ?>
                </td>
                <td style="padding: 0.75rem 0.85rem;">
                  <?php if (!empty($row['landing_page'])): ?>
                    <span title="<?php echo h($row['landing_page']); ?>" style="font-family: monospace; font-size: 0.75rem; color: #475569;">
                      <?php echo h($clean_lp); ?>
                    </span>
                  <?php else: ?>
                    <span style="color: var(--color-muted);">—</span>
                  <?php endif; ?>
                </td>
                <td style="padding: 0.75rem 0.85rem; color: var(--color-muted); white-space: nowrap;">
                  <?php echo format_enquiry_datetime($row['created_at'], !empty($row['is_mock']) || $db_is_ist); ?>
                </td>
                <td style="padding: 0.75rem 0.85rem;">
                  <span style="display: inline-block; padding: 0.2rem 0.5rem; font-size: 0.7rem; border-radius: var(--radius-sm); font-weight: 700; background-color: var(--color-surface-blue); color: var(--color-navy); white-space: nowrap;">
                    <?php echo h($row['status_name'] ?: 'NEW'); ?>
                  </span>
                </td>
                <td style="padding: 0.75rem 0.85rem; text-align: right; white-space: nowrap;">
                  <a href="/admin/enquiries?action=view&id=<?php echo $row['id']; ?><?php echo $source_tab ? '&source=' . urlencode($source_tab) : ''; ?>" class="btn btn-outline" style="padding: 0.35rem 0.75rem; font-size: 0.75rem; font-weight: 600;">View &rarr;</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p style="color: var(--color-muted); text-align: center; padding: 2.5rem 0;">No admissions enquiries match the selected filters.</p>
    <?php endif; ?>
  </div>

<!-- VIEW DETAILS -->
<?php elseif ($action === 'view' && $enq): ?>
  <div class="grid-3" style="align-items: flex-start; gap: 2rem;">
    
    <!-- Left Column: Details + Attribution -->
    <div style="grid-column: span 2; display: flex; flex-direction: column; gap: 1.5rem;">
      
      <!-- Panel 1: Enquiry Submission Details -->
      <div class="card" style="border-left: none; padding: 2rem;">
        <h3 style="font-size: 1.25rem; color: var(--color-navy); margin-bottom: 1.5rem; font-family: var(--font-secondary); border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">
          Enquiry Submission Details
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; font-size: 0.9rem;">
          <div>
            <strong style="color: var(--color-navy); display: block; margin-bottom: 0.25rem;">Parent Name:</strong>
            <span><?php echo h($enq['parent_name']); ?></span>
          </div>
          <div>
            <strong style="color: var(--color-navy); display: block; margin-bottom: 0.25rem;">Student Name:</strong>
            <span><?php echo h($enq['student_name'] ?: ($enq['parent_name'] . ' (Student)')); ?></span>
          </div>
          <div>
            <strong style="color: var(--color-navy); display: block; margin-bottom: 0.25rem;">Email Address:</strong>
            <a href="mailto:<?php echo h($enq['email']); ?>" style="color: var(--color-gold); font-weight: 600;"><?php echo h($enq['email']); ?></a>
          </div>
          <div>
            <strong style="color: var(--color-navy); display: block; margin-bottom: 0.25rem;">Phone Number:</strong>
            <a href="tel:<?php echo h($enq['phone']); ?>" style="color: var(--color-gold); font-weight: 600;">+91 <?php echo h($enq['phone']); ?></a>
          </div>
          <div>
            <strong style="color: var(--color-navy); display: block; margin-bottom: 0.25rem;">Grade Level Request:</strong>
            <span><?php echo h($enq['grade']); ?></span>
          </div>
          <div>
            <strong style="color: var(--color-navy); display: block; margin-bottom: 0.25rem;">Form Source Location:</strong>
            <span><?php echo h($enq['source']); ?></span>
          </div>
          <div>
            <strong style="color: var(--color-navy); display: block; margin-bottom: 0.25rem;">Date Submitted (IST):</strong>
            <span><?php echo !empty($enq['created_at']) ? format_enquiry_datetime($enq['created_at'], !empty($enq['is_mock']) || $db_is_ist) : '—'; ?></span>
          </div>
          <div>
            <strong style="color: var(--color-navy); display: block; margin-bottom: 0.25rem;">Current Pipeline Status:</strong>
            <span><?php echo h($enq['status_name'] ?: 'New'); ?></span>
          </div>
        </div>

        <div style="background-color: var(--color-surface-blue); padding: 1.25rem; border-radius: var(--radius-sm); border-left: 3px solid var(--color-gold); margin-bottom: 1.5rem;">
          <strong style="color: var(--color-navy); display: block; font-size: 0.85rem; margin-bottom: 0.5rem;">Message Body:</strong>
          <p style="font-size: 0.9rem; line-height: 1.6; color: var(--color-text); margin: 0; white-space: pre-wrap;"><?php echo h($enq['message']); ?></p>
        </div>
      </div>

      <!-- Panel 2: Dedicated Marketing Attribution & Tracking Data -->
      <div class="card" style="border-left: none; padding: 2rem; border-top: 4px solid var(--color-teal, #0d9488);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem; margin-bottom: 1.5rem;">
          <h3 style="font-size: 1.25rem; color: var(--color-navy); margin: 0; font-family: var(--font-secondary);">
            🎯 Marketing & Traffic Attribution
          </h3>
          <div>
            <?php echo render_marketing_source_badge($enq['marketing_source'] ?? 'Direct'); ?>
          </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem; font-size: 0.85rem;">
          <div>
            <strong style="color: var(--color-navy); display: block; margin-bottom: 0.25rem;">UTM Source:</strong>
            <code style="background: #f1f5f9; padding: 0.2rem 0.4rem; border-radius: 4px;"><?php echo h($enq['utm_source'] ?: '—'); ?></code>
          </div>
          <div>
            <strong style="color: var(--color-navy); display: block; margin-bottom: 0.25rem;">UTM Medium:</strong>
            <code style="background: #f1f5f9; padding: 0.2rem 0.4rem; border-radius: 4px;"><?php echo h($enq['utm_medium'] ?: '—'); ?></code>
          </div>
          <div>
            <strong style="color: var(--color-navy); display: block; margin-bottom: 0.25rem;">UTM Campaign:</strong>
            <code style="background: #f1f5f9; padding: 0.2rem 0.4rem; border-radius: 4px;"><?php echo h($enq['utm_campaign'] ?: '—'); ?></code>
          </div>
          <div>
            <strong style="color: var(--color-navy); display: block; margin-bottom: 0.25rem;">UTM Content:</strong>
            <code style="background: #f1f5f9; padding: 0.2rem 0.4rem; border-radius: 4px;"><?php echo h($enq['utm_content'] ?: '—'); ?></code>
          </div>
          <div>
            <strong style="color: var(--color-navy); display: block; margin-bottom: 0.25rem;">UTM Term / Keywords:</strong>
            <code style="background: #f1f5f9; padding: 0.2rem 0.4rem; border-radius: 4px;"><?php echo h($enq['utm_term'] ?: '—'); ?></code>
          </div>
          <div>
            <strong style="color: var(--color-navy); display: block; margin-bottom: 0.25rem;">Google Click ID (gclid):</strong>
            <span style="font-family: monospace; font-size: 0.8rem; word-break: break-all;"><?php echo h($enq['gclid'] ?: '—'); ?></span>
          </div>
          <div>
            <strong style="color: var(--color-navy); display: block; margin-bottom: 0.25rem;">Facebook Click ID (fbclid):</strong>
            <span style="font-family: monospace; font-size: 0.8rem; word-break: break-all;"><?php echo h($enq['fbclid'] ?: '—'); ?></span>
          </div>
        </div>

        <div style="border-top: 1px dashed var(--color-border); padding-top: 1.25rem; display: flex; flex-direction: column; gap: 0.85rem; font-size: 0.85rem;">
          <div>
            <strong style="color: var(--color-navy); display: block; margin-bottom: 0.25rem;">First Landing Page:</strong>
            <?php if (!empty($enq['landing_page'])): ?>
              <a href="<?php echo h($enq['landing_page']); ?>" target="_blank" rel="noopener noreferrer" style="color: #0284c7; word-break: break-all; text-decoration: underline;">
                <?php echo h($enq['landing_page']); ?> ↗
              </a>
            <?php else: ?>
              <span style="color: var(--color-muted);">—</span>
            <?php endif; ?>
          </div>

          <div>
            <strong style="color: var(--color-navy); display: block; margin-bottom: 0.25rem;">Referrer URL:</strong>
            <?php if (!empty($enq['referrer'])): ?>
              <a href="<?php echo h($enq['referrer']); ?>" target="_blank" rel="noopener noreferrer" style="color: #0284c7; word-break: break-all; text-decoration: underline;">
                <?php echo h($enq['referrer']); ?> ↗
              </a>
            <?php else: ?>
              <span style="color: var(--color-muted);">Direct Traffic (No HTTP Referrer)</span>
            <?php endif; ?>
          </div>
        </div>

        <!-- First-Touch vs Last-Touch Data Details -->
        <?php if (!empty($enq['first_touch']) || !empty($enq['last_touch'])): ?>
          <div style="margin-top: 1.5rem; border-top: 1px dashed var(--color-border); padding-top: 1.25rem;">
            <strong style="color: var(--color-navy); display: block; margin-bottom: 0.75rem;">30-Day Touchpoint History:</strong>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
              <div style="background: #f8fafc; padding: 0.85rem; border-radius: 4px; border: 1px solid #e2e8f0; font-size: 0.78rem;">
                <strong style="color: #0f172a; display: block; margin-bottom: 0.35rem;">First Touch Point:</strong>
                <?php
                  $ft = json_decode($enq['first_touch'] ?? '{}', true);
                  if (is_array($ft) && !empty($ft)):
                ?>
                  <div><strong>Source:</strong> <?php echo h($ft['marketing_source'] ?? 'Direct'); ?></div>
                  <div><strong>Campaign:</strong> <?php echo h($ft['utm_campaign'] ?? '—'); ?></div>
                  <div><strong>Medium:</strong> <?php echo h($ft['utm_medium'] ?? '—'); ?></div>
                  <div><strong>Time:</strong> <?php echo h($ft['timestamp'] ?? '—'); ?></div>
                <?php else: ?>
                  <span style="color: var(--color-muted);"><?php echo h($enq['first_touch'] ?: '—'); ?></span>
                <?php endif; ?>
              </div>

              <div style="background: #f8fafc; padding: 0.85rem; border-radius: 4px; border: 1px solid #e2e8f0; font-size: 0.78rem;">
                <strong style="color: #0f172a; display: block; margin-bottom: 0.35rem;">Last Touch Point:</strong>
                <?php
                  $lt = json_decode($enq['last_touch'] ?? '{}', true);
                  if (is_array($lt) && !empty($lt)):
                ?>
                  <div><strong>Source:</strong> <?php echo h($lt['marketing_source'] ?? 'Direct'); ?></div>
                  <div><strong>Campaign:</strong> <?php echo h($lt['utm_campaign'] ?? '—'); ?></div>
                  <div><strong>Medium:</strong> <?php echo h($lt['utm_medium'] ?? '—'); ?></div>
                  <div><strong>Time:</strong> <?php echo h($lt['timestamp'] ?? '—'); ?></div>
                <?php else: ?>
                  <span style="color: var(--color-muted);"><?php echo h($enq['last_touch'] ?: '—'); ?></span>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endif; ?>

      </div>

      <div>
        <a href="/admin/enquiries<?php echo $source_tab ? '?source=' . urlencode($source_tab) : ''; ?>" class="btn btn-outline" style="padding: 0.6rem 1.5rem;">&larr; Back to Leads</a>
      </div>

    </div>

    <!-- Right Column: Status Pipeline Controls -->
    <div class="card" style="border-left: none; padding: 2rem; border-top: 4px solid var(--color-gold);">
      <h3 style="font-size: 1.1rem; color: var(--color-navy); margin-bottom: 1.25rem; font-family: var(--font-secondary);">Status Pipeline</h3>
      
      <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
        <?php if ($source_tab): ?>
          <input type="hidden" name="source" value="<?php echo h($source_tab); ?>">
        <?php endif; ?>
        
        <div class="admin-form-group">
          <label class="admin-label">Assign New Status</label>
          <select name="status_id" class="admin-input" style="height: 38px; margin-bottom: 1rem;">
            <?php foreach ($statuses as $st): ?>
              <option value="<?php echo $st['id']; ?>" <?php echo $enq['status_id'] == $st['id'] ? 'selected' : ''; ?>>
                <?php echo h($st['name']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <button type="submit" name="update_status" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">Update Status</button>
      </form>
    </div>

  </div>
<?php endif; ?>

<?php
include_once dirname(__FILE__) . '/footer.php';
?>
