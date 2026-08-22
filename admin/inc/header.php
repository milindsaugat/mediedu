<?php
/**
 * Admin Topbar Header & HTML Head Start
 */
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?? 'Admin Dashboard' ?> | MedicEdu Global</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>

<div class="admin-layout">
  <?php require_once __DIR__ . '/sidebar.php'; ?>

  <div class="admin-main">
    <header class="admin-topbar">
      <div class="topbar-left">
        <button class="sidebar-toggle-btn" id="sidebarToggleBtn" aria-label="Toggle navigation">
          <i class="ri-menu-2-line"></i>
        </button>
        <div class="breadcrumb-trail">
          <a href="dashboard.php"><i class="ri-home-4-line"></i> Admin</a>
          <span>/</span>
          <span class="current"><?= $breadcrumb ?? ($pageTitle ?? 'Dashboard') ?></span>
        </div>
      </div>

      <div class="topbar-right">
        <a class="btn-view-site" href="../index.html" target="_blank">
          <i class="ri-external-link-line"></i> View Live Website
        </a>
      </div>
    </header>

    <div class="admin-content">
      <?= get_flash() ?>
