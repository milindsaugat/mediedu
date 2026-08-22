<?php
/**
 * MedicEdu Global — Admin Profile & Password Change
 */
$pageTitle = 'Admin Profile & Security';
$breadcrumb = 'Profile';

require_once __DIR__ . '/inc/header.php';

$db = getDB();
$adminId = $_SESSION['admin_id'] ?? 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Update Name / Email
    if (isset($_POST['update_profile'])) {
        $name  = clean($_POST['name'] ?? '');
        $email = clean($_POST['email'] ?? '');

        if (empty($name) || empty($email)) {
            set_flash('danger', 'Name and email cannot be blank.');
        } elseif ($db) {
            try {
                $stmt = $db->prepare("UPDATE admins SET name = ?, email = ? WHERE id = ?");
                $stmt->execute([$name, $email, $adminId]);
                $_SESSION['admin_name']  = $name;
                $_SESSION['admin_email'] = $email;
                set_flash('success', 'Profile updated successfully.');
            } catch (PDOException $e) {
                set_flash('danger', 'Error updating profile: ' . $e->getMessage());
            }
        }
    }

    // 2. Change Password
    if (isset($_POST['change_password'])) {
        $currentPass = $_POST['current_password'] ?? '';
        $newPass     = $_POST['new_password'] ?? '';
        $confirmPass = $_POST['confirm_password'] ?? '';

        if (empty($currentPass) || empty($newPass) || empty($confirmPass)) {
            set_flash('danger', 'Please fill in all password fields.');
        } elseif ($newPass !== $confirmPass) {
            set_flash('danger', 'New password and confirm password do not match.');
        } elseif (strlen($newPass) < 6) {
            set_flash('danger', 'New password must be at least 6 characters long.');
        } elseif ($db) {
            $stmt = $db->prepare("SELECT password FROM admins WHERE id = ? LIMIT 1");
            $stmt->execute([$adminId]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($currentPass, $admin['password'])) {
                $newHash = password_hash($newPass, PASSWORD_DEFAULT);
                $updateStmt = $db->prepare("UPDATE admins SET password = ? WHERE id = ?");
                $updateStmt->execute([$newHash, $adminId]);
                set_flash('success', 'Password changed successfully. Please remember your new password.');
            } else {
                set_flash('danger', 'Current password entered is incorrect.');
            }
        }
    }
}

$admin = ['name' => $_SESSION['admin_name'] ?? 'Admin', 'email' => $_SESSION['admin_email'] ?? ''];
if ($db) {
    $stmt = $db->prepare("SELECT * FROM admins WHERE id = ? LIMIT 1");
    $stmt->execute([$adminId]);
    $admin = $stmt->fetch() ?: $admin;
}
?>

<div class="page-head">
  <div class="page-title">
    <h1>Admin Account & Security Settings</h1>
    <p>Manage your login credentials, name, and administrative password.</p>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;max-width:960px;">
  <!-- Profile Info -->
  <div class="card">
    <div class="card-header">
      <h3><i class="ri-user-settings-line"></i> Profile Information</h3>
    </div>
    <div class="card-body">
      <form method="POST" action="">
        <input type="hidden" name="update_profile" value="1">

        <div class="form-group">
          <label for="name">Full Name</label>
          <input class="form-control" type="text" id="name" name="name" required value="<?= htmlspecialchars($admin['name']) ?>">
        </div>

        <div class="form-group">
          <label for="email">Email Address</label>
          <input class="form-control" type="email" id="email" name="email" required value="<?= htmlspecialchars($admin['email']) ?>">
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top:10px;">
          <i class="ri-save-line"></i> Update Profile Info
        </button>
      </form>
    </div>
  </div>

  <!-- Change Password -->
  <div class="card">
    <div class="card-header">
      <h3><i class="ri-lock-password-line"></i> Change Password</h3>
    </div>
    <div class="card-body">
      <form method="POST" action="">
        <input type="hidden" name="change_password" value="1">

        <div class="form-group">
          <label for="current_password">Current Password</label>
          <input class="form-control" type="password" id="current_password" name="current_password" required placeholder="••••••••">
        </div>

        <div class="form-group">
          <label for="new_password">New Password</label>
          <input class="form-control" type="password" id="new_password" name="new_password" required placeholder="Min 6 characters">
        </div>

        <div class="form-group">
          <label for="confirm_password">Confirm New Password</label>
          <input class="form-control" type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm new password">
        </div>

        <button type="submit" class="btn btn-accent" style="margin-top:10px;">
          <i class="ri-shield-keyhole-line"></i> Update Password
        </button>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/inc/footer.php'; ?>
