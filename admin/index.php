<?php
/**
 * MedicEdu Global — Admin Login
 */
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = clean($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $token    = $_POST['csrf_token'] ?? '';

    if (!csrf_verify($token)) {
        $error = 'Security session expired. Please refresh the page and try again.';
    } elseif (empty($email) || empty($password)) {
        $error = 'Please enter both your email address and password.';
    } else {
        $db = getDB();
        if (!$db) {
            $error = 'Database connection failed. Please run the installer at /database/install.php first.';
        } else {
            try {
                $stmt = $db->prepare("SELECT * FROM admins WHERE email = ? LIMIT 1");
                $stmt->execute([$email]);
                $admin = $stmt->fetch();

                if ($admin && password_verify($password, $admin['password'])) {
                    // Password is correct, start secure session
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id']        = $admin['id'];
                    $_SESSION['admin_name']      = $admin['name'];
                    $_SESSION['admin_email']     = $admin['email'];
                    $_SESSION['admin_role']      = $admin['role'];

                    header("Location: dashboard.php");
                    exit;
                } else {
                    $error = 'Invalid email address or password. Please check your credentials.';
                }
            } catch (PDOException $e) {
                $error = 'System error occurred: ' . $e->getMessage();
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
<title>Admin Portal Login | MedicEdu Global</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">
<link rel="stylesheet" href="css/admin.css">
</head>
<body class="login-body">

<div class="login-card">
  <div class="login-logo">
    <img src="../img/logo.png" alt="MedicEdu Global">
  </div>

  <h2>Admin Portal</h2>
  <p>Sign in to manage student inquiries, university fees, and website content.</p>

  <?php if ($error): ?>
    <div class="alert alert-danger">
      <i class="ri-error-warning-fill"></i>
      <div><?= htmlspecialchars($error) ?></div>
    </div>
  <?php endif; ?>

  <form method="POST" action="">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

    <div class="form-group">
      <label for="email"><i class="ri-mail-line"></i> Email Address</label>
      <input class="form-control" type="email" id="email" name="email" required placeholder="tarunrockthakur@gmail.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" autocomplete="email">
    </div>

    <div class="form-group">
      <label for="password"><i class="ri-lock-2-line"></i> Password</label>
      <input class="form-control" type="password" id="password" name="password" required placeholder="••••••••" autocomplete="current-password">
    </div>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;font-size:12.5px;">
      <span style="color:var(--admin-text-muted);">Default: <code>Admin@2026!</code></span>
      <a href="../database/install.php" style="color:var(--admin-navy);font-weight:600;">DB Installer</a>
    </div>

    <button class="btn btn-primary" style="width:100%;padding:12px;" type="submit">
      Sign In to Dashboard <i class="ri-arrow-right-line"></i>
    </button>
  </form>

  <div style="margin-top:24px;text-align:center;font-size:12px;color:var(--admin-text-muted);border-top:1px solid var(--admin-border);padding-top:16px;">
    &copy; <?= date('Y') ?> MedicEdu Global Private Limited · Secure Admin Control
  </div>
</div>

</body>
</html>
