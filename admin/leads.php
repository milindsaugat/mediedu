<?php
/**
 * MedicEdu Global — Student Leads CRM Manager
 */
$pageTitle = 'Student Leads CRM';
$breadcrumb = 'Leads & Inquiries';

require_once __DIR__ . '/inc/header.php';

$db = getDB();

// Handle CSV Export
if (isset($_GET['action']) && $_GET['action'] === 'export') {
    if ($db) {
        $stmt = $db->query("SELECT id, name, phone, email, country_interest, university_interest, neet_score, city_state, status, notes, created_at FROM leads ORDER BY created_at DESC");
        $rows = $stmt->fetchAll();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=medicedu_leads_' . date('Y-m-d_His') . '.csv');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Student Name', 'Phone', 'Email', 'Target Country', 'Preferred University', 'NEET Score', 'City / State', 'Status', 'Counselor Notes', 'Submitted On']);
        
        foreach ($rows as $row) {
            fputcsv($output, [
                $row['id'],
                $row['name'],
                $row['phone'],
                $row['email'] ?: '—',
                $row['country_interest'] ?: '—',
                $row['university_interest'] ?: '—',
                $row['neet_score'] ?: '—',
                $row['city_state'] ?: '—',
                ucfirst($row['status']),
                $row['notes'] ?: '',
                $row['created_at']
            ]);
        }
        fclose($output);
        exit;
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($db && $id > 0) {
        $stmt = $db->prepare("DELETE FROM leads WHERE id = ?");
        $stmt->execute([$id]);
        set_flash('success', 'Student inquiry #' . $id . ' has been deleted.');
        header("Location: leads.php");
        exit;
    }
}

// Handle Status Quick Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id = (int)$_POST['lead_id'];
    $newStatus = clean($_POST['status'] ?? '');
    if ($db && $id > 0 && in_array($newStatus, ['new', 'contacted', 'in_progress', 'admitted', 'rejected'])) {
        $stmt = $db->prepare("UPDATE leads SET status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $id]);
        set_flash('success', 'Lead #' . $id . ' status updated to ' . ucfirst($newStatus) . '.');
        header("Location: leads.php");
        exit;
    }
}

// Search & Filter Parameters
$search = clean($_GET['q'] ?? '');
$statusFilter = clean($_GET['status'] ?? '');
$countryFilter = clean($_GET['country'] ?? '');

$leads = [];
if ($db) {
    try {
        $query = "SELECT * FROM leads WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $query .= " AND (name LIKE ? OR phone LIKE ? OR email LIKE ? OR city_state LIKE ? OR neet_score LIKE ?)";
            $searchTerm = "%$search%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }

        if (!empty($statusFilter)) {
            $query .= " AND status = ?";
            $params[] = $statusFilter;
        }

        if (!empty($countryFilter)) {
            $query .= " AND country_interest LIKE ?";
            $params[] = "%$countryFilter%";
        }

        $query .= " ORDER BY created_at DESC";

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $leads = $stmt->fetchAll();
    } catch (PDOException $e) {
        // Fallback
    }
}
?>

<div class="page-head">
  <div class="page-title">
    <h1>Student Inquiries & Leads CRM</h1>
    <p>Manage, track, and follow up with medical aspirants applying from website lead forms.</p>
  </div>
  <div class="page-actions">
    <a href="leads.php?action=export" class="btn btn-secondary">
      <i class="ri-download-2-line"></i> Download CSV
    </a>
  </div>
</div>

<!-- Search & Filter Card -->
<div class="card" style="margin-bottom:20px;">
  <div class="card-body" style="padding:16px 20px;">
    <form method="GET" action="leads.php" style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
      <div style="flex:2;min-width:200px;">
        <input class="form-control" type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Search student name, phone, city, or NEET score...">
      </div>

      <div style="flex:1;min-width:140px;">
        <select class="form-control" name="status">
          <option value="">All Statuses</option>
          <option value="new" <?= $statusFilter === 'new' ? 'selected' : '' ?>>New</option>
          <option value="contacted" <?= $statusFilter === 'contacted' ? 'selected' : '' ?>>Contacted</option>
          <option value="in_progress" <?= $statusFilter === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
          <option value="admitted" <?= $statusFilter === 'admitted' ? 'selected' : '' ?>>Admitted</option>
          <option value="rejected" <?= $statusFilter === 'rejected' ? 'selected' : '' ?>>Rejected</option>
        </select>
      </div>

      <div style="flex:1;min-width:140px;">
        <select class="form-control" name="country">
          <option value="">All Destinations</option>
          <option value="Bosnia" <?= $countryFilter === 'Bosnia' ? 'selected' : '' ?>>Bosnia</option>
          <option value="Serbia" <?= $countryFilter === 'Serbia' ? 'selected' : '' ?>>Serbia</option>
          <option value="Romania" <?= $countryFilter === 'Romania' ? 'selected' : '' ?>>Romania</option>
          <option value="Russia" <?= $countryFilter === 'Russia' ? 'selected' : '' ?>>Russia</option>
          <option value="Armenia" <?= $countryFilter === 'Armenia' ? 'selected' : '' ?>>Armenia</option>
          <option value="Kyrgyzstan" <?= $countryFilter === 'Kyrgyzstan' ? 'selected' : '' ?>>Kyrgyzstan</option>
          <option value="Kazakhstan" <?= $countryFilter === 'Kazakhstan' ? 'selected' : '' ?>>Kazakhstan</option>
          <option value="Uzbekistan" <?= $countryFilter === 'Uzbekistan' ? 'selected' : '' ?>>Uzbekistan</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">
        <i class="ri-filter-3-line"></i> Filter
      </button>
      <?php if (!empty($search) || !empty($statusFilter) || !empty($countryFilter)): ?>
        <a href="leads.php" class="btn btn-secondary" title="Clear Filters">
          <i class="ri-close-line"></i> Reset
        </a>
      <?php endif; ?>
    </form>
  </div>
</div>

<!-- Leads Data Table -->
<div class="card">
  <div class="card-header">
    <h3><i class="ri-list-check-2"></i> Total Inquiries (<?= count($leads) ?>)</h3>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Student Details</th>
          <th>Contact / Phone</th>
          <th>Destination & Univ</th>
          <th>NEET Score</th>
          <th>Status</th>
          <th>Submitted On</th>
          <th style="text-align:right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($leads)): ?>
          <tr>
            <td colspan="8" style="text-align:center;padding:40px;color:var(--admin-text-muted);">
              <i class="ri-user-search-line" style="font-size:36px;display:block;margin-bottom:8px;"></i>
              No leads match your search criteria.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($leads as $lead): ?>
            <tr>
              <td><span style="font-weight:700;color:var(--admin-text-muted);">#<?= $lead['id'] ?></span></td>
              <td>
                <a href="lead-view.php?id=<?= $lead['id'] ?>" style="font-weight:700;color:var(--admin-navy);font-size:14px;">
                  <?= htmlspecialchars($lead['name']) ?>
                </a>
                <?php if (!empty($lead['city_state'])): ?>
                  <div style="font-size:12px;color:var(--admin-text-muted);"><i class="ri-map-pin-line"></i> <?= htmlspecialchars($lead['city_state']) ?></div>
                <?php endif; ?>
              </td>
              <td>
                <a href="tel:<?= htmlspecialchars($lead['phone']) ?>" style="font-weight:600;color:var(--info);">
                  <i class="ri-phone-fill"></i> <?= htmlspecialchars($lead['phone']) ?>
                </a>
                <?php if (!empty($lead['email'])): ?>
                  <div style="font-size:11.5px;color:var(--admin-text-muted);"><i class="ri-mail-line"></i> <?= htmlspecialchars($lead['email']) ?></div>
                <?php endif; ?>
              </td>
              <td>
                <strong><?= htmlspecialchars($lead['country_interest'] ?: 'Any') ?></strong>
                <?php if (!empty($lead['university_interest'])): ?>
                  <div style="font-size:11.5px;color:var(--admin-text-muted);"><?= htmlspecialchars($lead['university_interest']) ?></div>
                <?php endif; ?>
              </td>
              <td>
                <strong><?= htmlspecialchars($lead['neet_score'] ?: '—') ?></strong>
              </td>
              <td>
                <form method="POST" action="leads.php" style="display:inline-block;">
                  <input type="hidden" name="update_status" value="1">
                  <input type="hidden" name="lead_id" value="<?= $lead['id'] ?>">
                  <select name="status" onchange="this.form.submit()" style="font-size:12px;padding:4px 8px;border-radius:6px;border:1px solid var(--admin-border);background:#F8FAFC;font-weight:600;cursor:pointer;">
                    <option value="new" <?= $lead['status'] === 'new' ? 'selected' : '' ?>>⚡ New</option>
                    <option value="contacted" <?= $lead['status'] === 'contacted' ? 'selected' : '' ?>>📞 Contacted</option>
                    <option value="in_progress" <?= $lead['status'] === 'in_progress' ? 'selected' : '' ?>>⏳ In Progress</option>
                    <option value="admitted" <?= $lead['status'] === 'admitted' ? 'selected' : '' ?>>🎓 Admitted</option>
                    <option value="rejected" <?= $lead['status'] === 'rejected' ? 'selected' : '' ?>>❌ Rejected</option>
                  </select>
                </form>
              </td>
              <td style="font-size:12px;color:var(--admin-text-muted);white-space:nowrap;">
                <?= format_date($lead['created_at']) ?>
              </td>
              <td style="text-align:right;">
                <div style="display:inline-flex;gap:6px;">
                  <a href="lead-view.php?id=<?= $lead['id'] ?>" class="btn btn-sm btn-secondary btn-icon" title="View & Add Counselor Notes">
                    <i class="ri-eye-line"></i>
                  </a>
                  <?php 
                    $cleanPhone = preg_replace('/[^0-9]/', '', $lead['phone']);
                    if (strlen($cleanPhone) === 10) $cleanPhone = '91' . $cleanPhone;
                  ?>
                  <a href="https://wa.me/<?= $cleanPhone ?>?text=Hello%20<?= urlencode($lead['name']) ?>%2C%20greetings%20from%20MedicEdu%20Global." target="_blank" class="btn btn-sm btn-accent btn-icon" title="Chat on WhatsApp">
                    <i class="ri-whatsapp-line"></i>
                  </a>
                  <a href="leads.php?delete=<?= $lead['id'] ?>" onclick="return confirm('Are you sure you want to permanently delete lead #<?= $lead['id'] ?> (<?= htmlspecialchars(addslashes($lead['name'])) ?>)?');" class="btn btn-sm btn-danger btn-icon" title="Delete Inquiry">
                    <i class="ri-delete-bin-line"></i>
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

<?php require_once __DIR__ . '/inc/footer.php'; ?>
