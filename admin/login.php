<?php
// Zuvio Global School - Admin Login Portal
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['role_name']) && $_SESSION['role_name'] === 'admin') {
    header('Location: /admin');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please try again.';
    } else {
        $username_or_email = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($username_or_email) || empty($password)) {
            $error = 'Please enter both username/email and password.';
        } else {
            try {
                if (!$db) throw new Exception("Database connection offline.");
                
                $stmt = $db->prepare("
                    SELECT u.*, r.name as role_name 
                    FROM `users` u 
                    JOIN `roles` r ON r.id = u.role_id 
                    WHERE u.username = ? OR u.email = ? LIMIT 1
                ");
                $stmt->execute([$username_or_email, $username_or_email]);
                $user = $stmt->fetch();
                
                if ($user && password_verify($password, $user['password_hash'])) {
                    // Regenerate session id to prevent fixation
                    session_regenerate_id(true);
                    
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role_name'] = $user['role_name'];
                    
                    header('Location: /admin');
                    exit;
                } else {
                    $error = 'Invalid username, email, or password.';
                }
            } catch (Exception $e) {
                $error = 'Database connection required for login authentication.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | Zuvio Global School</title>
  <link rel="stylesheet" href="/css/main.css">
  <style>
    body {
      background-color: var(--color-surface-blue);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 1.5rem;
    }
    .login-card {
      background-color: #FFFFFF;
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow-lg);
      border-top: 5px solid var(--color-gold);
      padding: 3rem 2.5rem;
      width: 100%;
      max-width: 400px;
    }
    .login-logo {
      height: 52px;
      width: auto;
      object-fit: contain;
      display: block;
      margin: 0 auto 2rem auto;
    }
    .login-input {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1px solid rgba(6, 43, 99, 0.2);
      border-radius: var(--radius-sm);
      outline: none;
      font-size: 0.9rem;
      margin-bottom: 1.25rem;
      transition: border-color var(--transition-fast);
    }
    .login-input:focus {
      border-color: var(--color-navy);
    }
    .login-label {
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--color-navy);
      display: block;
      margin-bottom: 0.4rem;
    }
    .error-alert {
      background-color: #FDF2F8;
      border: 1px solid #FBCFE8;
      padding: 0.75rem 1rem;
      border-radius: var(--radius-sm);
      color: #D946EF;
      font-size: 0.8rem;
      margin-bottom: 1.5rem;
    }
  </style>
</head>
<body>

<div class="login-card">
  <a href="/">
    <img src="/assets/images/logo.png" alt="Zuvio Global School" class="login-logo">
  </a>
  
  <h2 style="font-size: 1.5rem; color: var(--color-navy); margin-bottom: 1.5rem; text-align: center; font-family: var(--font-primary);">Admin Login Portal</h2>
  
  <?php if ($error): ?>
    <div class="error-alert">
      <?php echo h($error); ?>
    </div>
  <?php endif; ?>

  <form method="POST" action="">
    <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
    
    <div>
      <label class="login-label">Username or Email</label>
      <input type="text" name="username" required class="login-input" placeholder="e.g. admin@zuvioglobalschool.com">
    </div>

    <div>
      <label class="login-label">Password</label>
      <input type="password" name="password" required class="login-input" placeholder="Enter password">
    </div>

    <button type="submit" name="login" class="btn btn-primary" style="width: 100%; padding: 0.85rem; font-size: 0.95rem; margin-top: 0.5rem;">Access Dashboard</button>
  </form>
  
  <div style="text-align: center; margin-top: 1.5rem;">
    <a href="/" style="font-size: 0.8rem; color: var(--color-muted); hover: color: var(--color-gold);">&larr; Return to School Site</a>
  </div>
</div>

</body>
</html>
