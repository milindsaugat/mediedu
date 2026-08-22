<?php
/**
 * Admin Sidebar Navigation
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

$currentPage = basename($_SERVER['PHP_SELF']);

// Count new leads for badge
$newLeadsCount = 0;
$db = getDB();
if ($db) {
    try {
        $stmt = $db->query("SELECT COUNT(*) as count FROM leads WHERE status = 'new'");
        $row = $stmt->fetch();
        $newLeadsCount = $row ? (int)$row['count'] : 0;
    } catch (Exception $e) {
        $newLeadsCount = 0;
    }
}
?>
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>
<aside class="admin-sidebar" id="adminSidebar">
  <div class="sidebar-header">
    <a class="sidebar-logo" href="dashboard.php">
      <img src="../img/logo.png" alt="MedicEdu Global">
    </a>
    <button class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="Close sidebar">
      <i class="ri-close-line"></i>
    </button>
  </div>

  <ul class="sidebar-nav">
    <li class="nav-category">Main Menu</li>
    <li class="nav-item">
      <a class="nav-link <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">
        <i class="ri-dashboard-3-line"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <li class="nav-category">CRM & Students</li>
    <li class="nav-item">
      <a class="nav-link <?= in_array($currentPage, ['leads.php', 'lead-view.php']) ? 'active' : '' ?>" href="leads.php">
        <i class="ri-user-voice-line"></i>
        <span>Student Leads</span>
        <?php if ($newLeadsCount > 0): ?>
          <span class="nav-badge"><?= $newLeadsCount ?> New</span>
        <?php endif; ?>
      </a>
    </li>

    <li class="nav-category">Website Management</li>
    <li class="nav-item">
      <a class="nav-link <?= in_array($currentPage, ['countries.php', 'country-edit.php']) ? 'active' : '' ?>" href="countries.php">
        <i class="ri-earth-line"></i>
        <span>Destinations (8)</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= $currentPage === 'universities.php' ? 'active' : '' ?>" href="universities.php">
        <i class="ri-building-4-line"></i>
        <span>Universities & Fees</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= $currentPage === 'settings.php' ? 'active' : '' ?>" href="settings.php">
        <i class="ri-settings-4-line"></i>
        <span>Site Settings</span>
      </a>
    </li>

    <li class="nav-category">Account & Security</li>
    <li class="nav-item">
      <a class="nav-link <?= $currentPage === 'profile.php' ? 'active' : '' ?>" href="profile.php">
        <i class="ri-user-settings-line"></i>
        <span>Admin Profile</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="logout.php">
        <i class="ri-logout-box-r-line"></i>
        <span>Sign Out</span>
      </a>
    </li>
  </ul>

  <div class="sidebar-footer">
    <div class="sidebar-user">
      <div class="user-avatar">
        <?= strtoupper(substr($_SESSION['admin_name'] ?? 'Admin', 0, 1)) ?>
      </div>
      <div class="user-info">
        <div class="user-name"><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin User') ?></div>
        <div class="user-role"><?= htmlspecialchars($_SESSION['admin_email'] ?? 'tarunrockthakur@gmail.com') ?></div>
      </div>
    </div>
  </div>
</aside>
