<?php
// Zuvio Global School - Super Admin User Management Dashboard
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_permission('users.view');

// Perform dynamic schema checking for users table additions
if ($db) {
    try {
        $db->query("SELECT `is_active` FROM `users` LIMIT 1");
    } catch (Exception $e) {
        $db->exec("ALTER TABLE `users` ADD COLUMN `is_active` TINYINT(1) DEFAULT 1");
    }
    
    try {
        $db->query("SELECT `last_activity` FROM `users` LIMIT 1");
    } catch (Exception $e) {
        $db->exec("ALTER TABLE `users` ADD COLUMN `last_activity` TIMESTAMP NULL DEFAULT NULL");
    }
}

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$msg = '';
$error = '';

// Helper to count active super admins
function count_active_super_admins() {
    global $db;
    try {
        $stmt = $db->query("
            SELECT COUNT(*) 
            FROM `users` u 
            JOIN `roles` r ON r.id = u.role_id 
            WHERE r.name = 'super_admin' AND u.is_active = 1
        ");
        return (int)$stmt->fetchColumn();
    } catch (Exception $e) {
        return 0;
    }
}

// Helper to count total super admins
function count_total_super_admins() {
    global $db;
    try {
        $stmt = $db->query("
            SELECT COUNT(*) 
            FROM `users` u 
            JOIN `roles` r ON r.id = u.role_id 
            WHERE r.name = 'super_admin'
        ");
        return (int)$stmt->fetchColumn();
    } catch (Exception $e) {
        return 0;
    }
}

// Helper to get user role name by ID
function get_user_role_name($user_id) {
    global $db;
    try {
        $stmt = $db->prepare("
            SELECT r.name 
            FROM `users` u 
            JOIN `roles` r ON r.id = u.role_id 
            WHERE u.id = ? LIMIT 1
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn();
    } catch (Exception $e) {
        return '';
    }
}

// Fetch all roles for dropdowns
$roles = [];
if ($db) {
    try {
        $roles = $db->query("SELECT * FROM `roles` ORDER BY `name` ASC")->fetchAll();
    } catch (Exception $e) {
        error_log("[User Dashboard Roles Fetch] " . $e->getMessage());
    }
}

// Handle User Deletion
if ($action === 'delete' && $id > 0) {
    require_permission('users.delete');
    
    $target_role = get_user_role_name($id);
    if ($target_role === 'super_admin' && count_total_super_admins() <= 1) {
        header('Location: /admin/users?error=last_super_admin_delete');
        exit;
    }
    
    try {
        // Fetch target info for audit logging
        $stmt = $db->prepare("SELECT * FROM `users` WHERE `id` = ? LIMIT 1");
        $stmt->execute([$id]);
        $old_user_data = $stmt->fetch();
        
        $stmt = $db->prepare("DELETE FROM `users` WHERE `id` = ?");
        $stmt->execute([$id]);
        
        log_audit('USER_DELETED', 'users', 'users', $id, $old_user_data, null, "User {$old_user_data['username']} deleted successfully");
        header('Location: /admin/users?msg=deleted');
        exit;
    } catch (Exception $e) {
        $error = 'Failed to delete user: ' . $e->getMessage();
    }
}

// Handle Add / Edit User Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($action === 'add' || $action === 'edit')) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role_id = (int)$_POST['role_id'];
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if (empty($username) || empty($email) || empty($role_id)) {
            $error = 'Please fill out all required fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } else {
            try {
                // Fetch target role details
                $role_stmt = $db->prepare("SELECT `name` FROM `roles` WHERE `id` = ? LIMIT 1");
                $role_stmt->execute([$role_id]);
                $selected_role_name = $role_stmt->fetchColumn();
                
                if ($action === 'add') {
                    require_permission('users.create');
                    $password = $_POST['password'] ?? '';
                    if (empty($password)) {
                        $error = 'Password is required for new users.';
                    } else {
                        $hash = password_hash($password, PASSWORD_BCRYPT);
                        
                        $stmt = $db->prepare("
                            INSERT INTO `users` (`username`, `email`, `password_hash`, `role_id`, `is_active`)
                            VALUES (?, ?, ?, ?, ?)
                        ");
                        $stmt->execute([$username, $email, $hash, $role_id, $is_active]);
                        $new_id = $db->lastInsertId();
                        
                        $new_data = ['username' => $username, 'email' => $email, 'role_id' => $role_id, 'is_active' => $is_active];
                        log_audit('USER_CREATED', 'users', 'users', $new_id, null, $new_data, "Created user {$username}");
                        header('Location: /admin/users?msg=added');
                        exit;
                    }
                } else {
                    require_permission('users.edit');
                    
                    // Fetch existing user info
                    $orig_stmt = $db->prepare("SELECT * FROM `users` WHERE `id` = ? LIMIT 1");
                    $orig_stmt->execute([$id]);
                    $old_user_data = $orig_stmt->fetch();
                    $old_role_name = get_user_role_name($id);
                    
                    // Super Admin Safeties Lockout checks
                    if ($old_role_name === 'super_admin') {
                        // 1. Demoting last Super Admin
                        if ($selected_role_name !== 'super_admin' && count_total_super_admins() <= 1) {
                            throw new Exception("Cannot demote the last remaining Super Admin.");
                        }
                        // 2. Deactivating last active Super Admin
                        if ($is_active == 0 && count_active_super_admins() <= 1 && $old_user_data['is_active'] == 1) {
                            throw new Exception("Cannot deactivate the last active Super Admin.");
                        }
                        // 3. User demoting themselves
                        if ($id == $_SESSION['user_id'] && $selected_role_name !== 'super_admin') {
                            throw new Exception("You cannot remove your own Super Admin role.");
                        }
                    }
                    
                    // Handle password reset if provided
                    $password_update_sql = '';
                    $params = [$username, $email, $role_id, $is_active];
                    
                    $password = $_POST['password'] ?? '';
                    if (!empty($password)) {
                        $hash = password_hash($password, PASSWORD_BCRYPT);
                        $password_update_sql = ", `password_hash` = ?";
                        $params[] = $hash;
                    }
                    
                    $params[] = $id;
                    
                    $stmt = $db->prepare("
                        UPDATE `users` 
                        SET `username` = ?, `email` = ?, `role_id` = ?, `is_active` = ? {$password_update_sql}
                        WHERE `id` = ?
                    ");
                    $stmt->execute($params);
                    
                    $new_data = ['username' => $username, 'email' => $email, 'role_id' => $role_id, 'is_active' => $is_active];
                    
                    if ($old_user_data['role_id'] != $role_id) {
                        log_audit('USER_ROLE_CHANGED', 'users', 'users', $id, $old_user_data, $new_data, "Changed role of user {$username} to {$selected_role_name}");
                    }
                    if ($old_user_data['is_active'] != $is_active) {
                        $act_text = $is_active ? 'activated' : 'deactivated';
                        log_audit($is_active ? 'USER_ACTIVATED' : 'USER_DEACTIVATED', 'users', 'users', $id, $old_user_data, $new_data, "User {$username} {$act_text}");
                    }
                    
                    log_audit('USER_UPDATED', 'users', 'users', $id, $old_user_data, $new_data, "Updated details of user {$username}");
                    header('Location: /admin/users?msg=updated');
                    exit;
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
    }
}

// Fetch single user detail
$item = null;
if ($action === 'edit' && $id > 0) {
    try {
        $stmt = $db->prepare("SELECT * FROM `users` WHERE `id` = ? LIMIT 1");
        $stmt->execute([$id]);
        $item = $stmt->fetch();
        if (!$item) {
            header('Location: /admin/users?error=notfound');
            exit;
        }
    } catch (Exception $e) {
        $error = 'Failed to load user details.';
    }
}

// Fetch all users list
$users = [];
if ($action === 'list') {
    try {
        $users = $db->query("
            SELECT u.*, r.name as role_name 
            FROM `users` u 
            JOIN `roles` r ON r.id = u.role_id 
            ORDER BY u.created_at DESC
        ")->fetchAll();
    } catch (Exception $e) {
        $error = 'Database connection offline.';
    }
}

$page_slug = 'admin-users';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.5rem; color: var(--color-navy); margin-bottom: 0.25rem;">Super Admin User & Roles Manager</h1>
    <p style="color: var(--color-muted); font-size: 0.85rem;">Manage user profiles, assign roles, activate/deactivate accounts, and view user metrics.</p>
  </div>
  <?php if ($action === 'list'): ?>
    <a href="/admin/users?action=add" class="btn btn-primary" style="font-size: 0.85rem;">+ Add New User</a>
  <?php else: ?>
    <a href="/admin/users" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">&larr; Back to List</a>
  <?php endif; ?>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'added'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    User created successfully.
  </div>
<?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    User details updated successfully.
  </div>
<?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    User profile deleted successfully.
  </div>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] === 'last_super_admin_delete'): ?>
  <div class="error-alert">
    Cannot delete the last remaining Super Admin in the system.
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="error-alert">
    <?php echo h($error); ?>
  </div>
<?php endif; ?>

<!-- VIEW: LIST -->
<?php if ($action === 'list'): ?>
  <div class="card" style="border-left: none; padding: 2rem;">
    <?php if (!empty($users)): ?>
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
          <thead>
            <tr style="border-bottom: 2px solid var(--color-border); color: var(--color-navy); font-weight: 600;">
              <th style="padding: 0.75rem 1rem;">Username</th>
              <th style="padding: 0.75rem 1rem;">Email Address</th>
              <th style="padding: 0.75rem 1rem;">Role Assigned</th>
              <th style="padding: 0.75rem 1rem;">Status</th>
              <th style="padding: 0.75rem 1rem;">Created Date</th>
              <th style="padding: 0.75rem 1rem;">Last Activity</th>
              <th style="padding: 0.75rem 1rem; text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $user): ?>
              <tr style="border-bottom: 1px solid var(--color-border); color: var(--color-text);">
                <td style="padding: 0.75rem 1rem; font-weight: 600;"><?php echo h($user['username']); ?></td>
                <td style="padding: 0.75rem 1rem;"><?php echo h($user['email']); ?></td>
                <td style="padding: 0.75rem 1rem;">
                  <span style="display: inline-block; padding: 0.2rem 0.5rem; font-size: 0.7rem; border-radius: var(--radius-sm); font-weight: 700; background-color: var(--color-surface-blue); color: var(--color-navy);">
                    <?php echo strtoupper($user['role_name']); ?>
                  </span>
                </td>
                <td style="padding: 0.75rem 1rem;">
                  <span style="display: inline-block; padding: 0.2rem 0.5rem; font-size: 0.7rem; border-radius: var(--radius-sm); font-weight: 700; background-color: <?php echo $user['is_active'] ? '#D1FAE5' : '#FEE2E2'; ?>; color: <?php echo $user['is_active'] ? '#065F46' : '#991B1B'; ?>;">
                    <?php echo $user['is_active'] ? 'ACTIVE' : 'SUSPENDED'; ?>
                  </span>
                </td>
                <td style="padding: 0.75rem 1rem; color: var(--color-muted);"><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></td>
                <td style="padding: 0.75rem 1rem; color: var(--color-muted);">
                  <?php echo $user['last_activity'] ? date('Y-m-d H:i', strtotime($user['last_activity'])) : 'Never'; ?>
                </td>
                <td style="padding: 0.75rem 1rem; text-align: right;">
                  <a href="/admin/users?action=edit&id=<?php echo $user['id']; ?>" style="color: var(--color-gold); font-weight: 600; margin-right: 1rem;">Edit</a>
                  <?php if ($user['id'] != $_SESSION['user_id']): ?>
                    <a href="/admin/users?action=delete&id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user profile?');" style="color: #EF4444; font-weight: 600;">Delete</a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p style="color: var(--color-muted); text-align: center;">No user accounts found.</p>
    <?php endif; ?>
  </div>

<!-- VIEW: ADD / EDIT -->
<?php elseif ($action === 'add' || $action === 'edit'): ?>
  <div class="card" style="border-left: none; padding: 2.5rem; max-width: 600px; margin: 0 auto;">
    <form method="POST" action="">
      <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
      
      <div class="admin-form-group">
        <label class="admin-label">Username *</label>
        <input type="text" name="username" required class="admin-input" value="<?php echo h($item['username'] ?? ''); ?>" placeholder="e.g. pragya_jain">
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Email Address *</label>
        <input type="email" name="email" required class="admin-input" value="<?php echo h($item['email'] ?? ''); ?>" placeholder="e.g. pragya@zuvioglobalschool.com">
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Assign Privilege Role *</label>
        <select name="role_id" required class="admin-input" style="height: 38px;">
          <option value="">Select Role</option>
          <?php foreach ($roles as $r): ?>
            <option value="<?php echo $r['id']; ?>" <?php echo (isset($item['role_id']) && $item['role_id'] == $r['id']) ? 'selected' : ''; ?>>
              <?php echo strtoupper($r['name']); ?> (<?php echo h($r['description']); ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Password <?php echo $action === 'add' ? '*' : '(Leave empty to keep current password)'; ?></label>
        <input type="password" name="password" <?php echo $action === 'add' ? 'required' : ''; ?> class="admin-input" placeholder="Password length min 6 characters">
      </div>

      <div class="admin-form-group" style="display: flex; align-items: center; margin-top: 1.5rem;">
        <label style="font-size: 0.9rem; font-weight: 600; color: var(--color-navy); display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
          <input type="checkbox" name="is_active" value="1" <?php echo ($action === 'add' || (isset($item['is_active']) && $item['is_active'] == 1)) ? 'checked' : ''; ?>>
          Account Active (Allows dashboard login)
        </label>
      </div>

      <div style="margin-top: 2rem;">
        <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2.5rem;">Save Account</button>
        <a href="/admin/users" class="btn btn-outline" style="padding: 0.8rem 2rem; margin-left: 0.75rem;">Cancel</a>
      </div>
    </form>
  </div>
<?php endif; ?>

<?php
include_once dirname(__FILE__) . '/footer.php';
?>
