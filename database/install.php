<?php
/**
 * MedicEdu Global — 1-Click Database Setup & Installer
 */
require_once __DIR__ . '/../config/db.php';

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $host = getenv('DB_HOST') ?: 'localhost';
        $port = getenv('DB_PORT') ?: '3306';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
        $dbname = getenv('DB_NAME') ?: 'medicedu_db';

        // Connect to MySQL server without database first
        $pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        // Create Database if not exists
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `$dbname`");

        // Read and execute schema.sql
        $sql = file_get_contents(__DIR__ . '/schema.sql');
        $pdo->exec($sql);

        // Ensure default admin password hash is properly set
        $adminPassword = 'Admin@2026!';
        $hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);
        $adminEmail = 'tarunrockthakur@gmail.com';

        $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE email = ?");
        $stmt->execute([$hashedPassword, $adminEmail]);

        $success = true;
        $message = "Database & Tables installed successfully! You can now log into the Admin Panel.";
    } catch (PDOException $e) {
        $success = false;
        $message = "Installation failed: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MedicEdu Global — 1-Click Database Installer</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">
<link rel="icon" type="image/png" href="../img/favicon.png">
<style>
  :root {
    --primary: #0A294D;
    --accent: #E5A823;
    --bg: #F4F7FB;
    --text: #1E293B;
  }
  body {
    font-family: 'Inter', sans-serif;
    background: var(--bg);
    color: var(--text);
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    margin: 0;
    padding: 20px;
  }
  .card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(10, 41, 77, 0.08);
    max-width: 540px;
    width: 100%;
    padding: 36px;
    border: 1px solid #E2E8F0;
  }
  .logo {
    text-align: center;
    margin-bottom: 24px;
  }
  .logo img {
    height: 48px;
  }
  h1 {
    font-family: 'Manrope', sans-serif;
    font-size: 22px;
    color: var(--primary);
    margin: 0 0 8px;
    text-align: center;
  }
  p {
    font-size: 14px;
    color: #64748B;
    text-align: center;
    margin: 0 0 24px;
    line-height: 1.5;
  }
  .alert {
    padding: 14px 16px;
    border-radius: 10px;
    font-size: 14px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .alert-success {
    background: #ECFDF5;
    color: #065F46;
    border: 1px solid #A7F3D0;
  }
  .alert-error {
    background: #FEF2F2;
    color: #991B1B;
    border: 1px solid #FECACA;
  }
  .info-box {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 24px;
    font-size: 13px;
  }
  .info-box ul {
    margin: 8px 0 0;
    padding-left: 20px;
    color: #475569;
  }
  .info-box li {
    margin-bottom: 6px;
  }
  .btn {
    display: block;
    width: 100%;
    padding: 14px;
    background: var(--primary);
    color: #ffffff;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    transition: background 0.2s;
  }
  .btn:hover {
    background: #08203d;
  }
  .btn-success {
    background: #059669;
  }
  .btn-success:hover {
    background: #047857;
  }
</style>
</head>
<body>

<div class="card">
  <div class="logo">
    <img src="../img/logo.png" alt="MedicEdu Global">
  </div>
  
  <h1>Database Setup Wizard</h1>
  <p>1-Click Installer to setup MySQL tables, 8 MBBS study destinations, and default Admin user.</p>

  <?php if ($message): ?>
    <div class="alert <?= $success ? 'alert-success' : 'alert-error' ?>">
      <i class="<?= $success ? 'ri-checkbox-circle-fill' : 'ri-error-warning-fill' ?>" style="font-size:20px;"></i>
      <div><?= htmlspecialchars($message) ?></div>
    </div>
  <?php endif; ?>

  <div class="info-box">
    <strong>What this installer will create:</strong>
    <ul>
      <li><code>admins</code> table with default superadmin</li>
      <li><code>leads</code> CRM table for student inquiries</li>
      <li><code>countries</code> table with 8 target study destinations</li>
      <li><code>universities</code> table with 40+ medical university fee charts</li>
      <li><code>settings</code> table for site phone, email, and session</li>
    </ul>
    <div style="margin-top:12px;padding-top:10px;border-top:1px dashed #CBD5E1;font-size:12px;color:#64748B;">
      <strong>Default Admin Login:</strong><br>
      Email: <code>tarunrockthakur@gmail.com</code><br>
      Password: <code>Admin@2026!</code>
    </div>
  </div>

  <?php if ($success): ?>
    <a href="../admin/" class="btn btn-success">
      <i class="ri-login-box-line"></i> Go to Admin Panel Login
    </a>
  <?php else: ?>
    <form method="POST">
      <button type="submit" class="btn">
        <i class="ri-database-2-line"></i> Initialize & Install Database Now
      </button>
    </form>
  <?php endif; ?>
</div>

</body>
</html>
