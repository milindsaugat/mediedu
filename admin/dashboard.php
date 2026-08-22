<?php
/**
 * MedicEdu Global — Admin Analytics & Dashboard
 */
$pageTitle = 'Dashboard Overview';
$breadcrumb = 'Dashboard';

require_once __DIR__ . '/inc/header.php';

$db = getDB();

$totalLeads = 0;
$todayLeads = 0;
$inProgressLeads = 0;
$admittedLeads = 0;
$recentLeads = [];
$countryStats = [];

if ($db) {
    try {
        // 1. Total Leads
        $stmt = $db->query("SELECT COUNT(*) as total FROM leads");
        $totalLeads = (int)$stmt->fetch()['total'];

        // 2. Today's Leads
        $stmt = $db->query("SELECT COUNT(*) as total FROM leads WHERE DATE(created_at) = CURDATE()");
        $todayLeads = (int)$stmt->fetch()['total'];

        // 3. In Progress
        $stmt = $db->query("SELECT COUNT(*) as total FROM leads WHERE status IN ('new', 'in_progress', 'contacted')");
        $inProgressLeads = (int)$stmt->fetch()['total'];

        // 4. Admitted
        $stmt = $db->query("SELECT COUNT(*) as total FROM leads WHERE status = 'admitted'");
        $admittedLeads = (int)$stmt->fetch()['total'];

        // 5. Recent 8 Leads
        $stmt = $db->query("SELECT * FROM leads ORDER BY created_at DESC LIMIT 8");
        $recentLeads = $stmt->fetchAll();

        // 6. Country Breakdown
        $stmt = $db->query("
            SELECT country_interest, COUNT(*) as count 
            FROM leads 
            WHERE country_interest IS NOT NULL AND country_interest != '' 
            GROUP BY country_interest 
            ORDER BY count DESC 
            LIMIT 5
        ");
        $countryStats = $stmt->fetchAll();
    } catch (PDOException $e) {
        // Fallback gracefully
    }
}
?>

<div class="page-head">
  <div class="page-title">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?> 👋</h1>
    <p>Here is your real-time medical admission inquiry overview and student tracking analytics.</p>
  </div>
  <div class="page-actions">
    <a href="leads.php?action=export" class="btn btn-secondary">
      <i class="ri-file-excel-2-line"></i> Export All Leads (CSV)
    </a>
    <a href="leads.php" class="btn btn-primary">
      <i class="ri-user-voice-line"></i> View All Inquiries
    </a>
  </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-info">
      <span>Total Inquiries</span>
      <h3><?= number_format($totalLeads) ?></h3>
    </div>
    <div class="stat-icon blue">
      <i class="ri-user-shared-line"></i>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-info">
      <span>New Leads Today</span>
      <h3><?= number_format($todayLeads) ?></h3>
    </div>
    <div class="stat-icon green">
      <i class="ri-flashlight-line"></i>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-info">
      <span>Active Follow-ups</span>
      <h3><?= number_format($inProgressLeads) ?></h3>
    </div>
    <div class="stat-icon yellow">
      <i class="ri-time-line"></i>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-info">
      <span>Admitted Students</span>
      <h3><?= number_format($admittedLeads) ?></h3>
    </div>
    <div class="stat-icon gold">
      <i class="ri-award-line"></i>
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
  <!-- Recent Leads Table -->
  <div class="card">
    <div class="card-header">
      <h2><i class="ri-user-star-line"></i> Recent Student Inquiries</h2>
      <a href="leads.php" class="btn btn-sm btn-secondary">View All Leads <i class="ri-arrow-right-line"></i></a>
    </div>
    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Student Name</th>
            <th>Contact & City</th>
            <th>Country / Univ</th>
            <th>NEET</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($recentLeads)): ?>
            <tr>
              <td colspan="6" style="text-align:center;padding:32px;color:var(--admin-text-muted);">
                <i class="ri-inbox-line" style="font-size:36px;display:block;margin-bottom:8px;"></i>
                No inquiries registered yet. Lead form submissions will automatically appear here.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($recentLeads as $lead): ?>
              <tr>
                <td>
                  <strong><?= htmlspecialchars($lead['name']) ?></strong>
                  <div style="font-size:11.5px;color:var(--admin-text-muted);"><?= format_date($lead['created_at']) ?></div>
                </td>
                <td>
                  <a href="tel:<?= htmlspecialchars($lead['phone']) ?>" style="font-weight:600;">
                    <i class="ri-phone-fill" style="color:var(--info);"></i> <?= htmlspecialchars($lead['phone']) ?>
                  </a>
                  <?php if (!empty($lead['city_state'])): ?>
                    <div style="font-size:12px;color:var(--admin-text-muted);"><?= htmlspecialchars($lead['city_state']) ?></div>
                  <?php endif; ?>
                </td>
                <td>
                  <span style="font-weight:500;"><?= htmlspecialchars($lead['country_interest'] ?: 'Any Destination') ?></span>
                  <?php if (!empty($lead['university_interest'])): ?>
                    <div style="font-size:11.5px;color:var(--admin-text-muted);"><?= htmlspecialchars($lead['university_interest']) ?></div>
                  <?php endif; ?>
                </td>
                <td>
                  <strong><?= htmlspecialchars($lead['neet_score'] ?: '—') ?></strong>
                </td>
                <td>
                  <?= status_badge($lead['status']) ?>
                </td>
                <td>
                  <div style="display:flex;gap:6px;">
                    <a href="lead-view.php?id=<?= $lead['id'] ?>" class="btn btn-sm btn-secondary btn-icon" title="View & Edit Lead">
                      <i class="ri-eye-line"></i>
                    </a>
                    <?php 
                      $cleanPhone = preg_replace('/[^0-9]/', '', $lead['phone']);
                      if (strlen($cleanPhone) === 10) $cleanPhone = '91' . $cleanPhone;
                    ?>
                    <a href="https://wa.me/<?= $cleanPhone ?>?text=Hello%20<?= urlencode($lead['name']) ?>%2C%20greetings%20from%20MedicEdu%20Global." target="_blank" class="btn btn-sm btn-accent btn-icon" title="WhatsApp Student">
                      <i class="ri-whatsapp-line"></i>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Quick Shortcuts & Country Stats -->
  <div>
    <div class="card">
      <div class="card-header">
        <h3><i class="ri-pie-chart-line"></i> Popular Destinations</h3>
      </div>
      <div class="card-body">
        <?php if (empty($countryStats)): ?>
          <p style="color:var(--admin-text-muted);font-size:13px;">No country inquiry data available yet.</p>
        <?php else: ?>
          <div style="display:flex;flex-direction:column;gap:14px;">
            <?php foreach ($countryStats as $cs): ?>
              <div>
                <div style="display:flex;justify-content:space-between;margin-bottom:4px;font-size:13px;">
                  <strong><?= htmlspecialchars($cs['country_interest']) ?></strong>
                  <span style="color:var(--admin-text-muted);"><?= $cs['count'] ?> leads</span>
                </div>
                <?php 
                  $pct = $totalLeads > 0 ? round(($cs['count'] / $totalLeads) * 100) : 0;
                ?>
                <div style="height:6px;background:#E2E8F0;border-radius:99px;overflow:hidden;">
                  <div style="width:<?= $pct ?>%;height:100%;background:var(--admin-navy);border-radius:99px;"></div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Quick Help Box -->
    <div class="card" style="background:linear-gradient(135deg, #0A294D 0%, #153C6C 100%);color:#FFFFFF;">
      <div class="card-body">
        <h3 style="color:#FFFFFF;margin-bottom:8px;"><i class="ri-customer-service-2-line"></i> Admission Helpline</h3>
        <p style="color:#E2E8F0;font-size:13px;margin-bottom:16px;">Direct contact credentials configured for website visitors & student inquiries:</p>
        
        <div style="font-size:13px;line-height:1.8;color:#FEF3C7;">
          <div><i class="ri-phone-fill"></i> +91 94106 24320</div>
          <div><i class="ri-mail-fill"></i> tarunrockthakur@gmail.com</div>
          <div><i class="ri-calendar-line"></i> Session 2026–2027</div>
        </div>
        
        <a href="settings.php" class="btn btn-accent btn-sm" style="margin-top:16px;width:100%;">
          <i class="ri-settings-line"></i> Edit Site Settings
        </a>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/inc/footer.php'; ?>
