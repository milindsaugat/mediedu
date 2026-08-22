<?php
/**
 * MedicEdu Global — Single Lead Detail & Follow-up Notes
 */
$pageTitle = 'Student Inquiry Details';
$breadcrumb = 'Inquiry Details';

require_once __DIR__ . '/inc/header.php';

$db = getDB();
$leadId = (int)($_GET['id'] ?? 0);

if (!$db || $leadId <= 0) {
    header("Location: leads.php");
    exit;
}

// Handle Note / Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_lead'])) {
    $status = clean($_POST['status'] ?? 'new');
    $notes  = clean($_POST['notes'] ?? '');
    
    $stmt = $db->prepare("UPDATE leads SET status = ?, notes = ? WHERE id = ?");
    $stmt->execute([$status, $notes, $leadId]);
    set_flash('success', 'Lead details and counselor follow-up notes updated successfully.');
}

$stmt = $db->prepare("SELECT * FROM leads WHERE id = ? LIMIT 1");
$stmt->execute([$leadId]);
$lead = $stmt->fetch();

if (!$lead) {
    set_flash('danger', 'Lead #' . $leadId . ' not found.');
    header("Location: leads.php");
    exit;
}

$cleanPhone = preg_replace('/[^0-9]/', '', $lead['phone']);
if (strlen($cleanPhone) === 10) $cleanPhone = '91' . $cleanPhone;
?>

<div class="page-head">
  <div class="page-title">
    <h1>Inquiry #<?= $lead['id'] ?> — <?= htmlspecialchars($lead['name']) ?></h1>
    <p>Submitted on <?= format_date($lead['created_at']) ?> via <?= htmlspecialchars($lead['source_page'] ?: 'Website Form') ?></p>
  </div>
  <div class="page-actions">
    <a href="leads.php" class="btn btn-secondary">
      <i class="ri-arrow-left-line"></i> Back to All Leads
    </a>
    <a href="https://wa.me/<?= $cleanPhone ?>?text=Hello%20<?= urlencode($lead['name']) ?>%2C%20greetings%20from%20MedicEdu%20Global." target="_blank" class="btn btn-accent">
      <i class="ri-whatsapp-line"></i> WhatsApp Student
    </a>
    <a href="tel:<?= htmlspecialchars($lead['phone']) ?>" class="btn btn-primary">
      <i class="ri-phone-fill"></i> Call Now
    </a>
  </div>
</div>

<div style="display:grid;grid-template-columns:1.5fr 1fr;gap:24px;">
  <!-- Left Column: Student Details -->
  <div>
    <div class="card">
      <div class="card-header">
        <h3><i class="ri-user-3-line"></i> Student Profile & Admission Request</h3>
      </div>
      <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
          <div>
            <span style="font-size:12px;color:var(--admin-text-muted);text-transform:uppercase;font-weight:600;">Student Name</span>
            <div style="font-size:16px;font-weight:700;color:var(--admin-navy);margin-top:2px;"><?= htmlspecialchars($lead['name']) ?></div>
          </div>
          <div>
            <span style="font-size:12px;color:var(--admin-text-muted);text-transform:uppercase;font-weight:600;">Phone / WhatsApp</span>
            <div style="font-size:16px;font-weight:700;color:var(--info);margin-top:2px;">
              <a href="tel:<?= htmlspecialchars($lead['phone']) ?>"><?= htmlspecialchars($lead['phone']) ?></a>
            </div>
          </div>
          <div>
            <span style="font-size:12px;color:var(--admin-text-muted);text-transform:uppercase;font-weight:600;">Email Address</span>
            <div style="font-size:14px;font-weight:500;margin-top:2px;">
              <?= htmlspecialchars($lead['email'] ?: 'Not Provided') ?>
            </div>
          </div>
          <div>
            <span style="font-size:12px;color:var(--admin-text-muted);text-transform:uppercase;font-weight:600;">NEET Score / Status</span>
            <div style="font-size:15px;font-weight:700;color:var(--admin-gold);margin-top:2px;">
              <?= htmlspecialchars($lead['neet_score'] ?: 'Not Specified') ?>
            </div>
          </div>
          <div>
            <span style="font-size:12px;color:var(--admin-text-muted);text-transform:uppercase;font-weight:600;">Target Country</span>
            <div style="font-size:15px;font-weight:600;color:var(--admin-navy);margin-top:2px;">
              <?= htmlspecialchars($lead['country_interest'] ?: 'Any Recommended') ?>
            </div>
          </div>
          <div>
            <span style="font-size:12px;color:var(--admin-text-muted);text-transform:uppercase;font-weight:600;">Preferred University</span>
            <div style="font-size:14px;font-weight:500;margin-top:2px;">
              <?= htmlspecialchars($lead['university_interest'] ?: 'Open to Suggestions') ?>
            </div>
          </div>
          <div>
            <span style="font-size:12px;color:var(--admin-text-muted);text-transform:uppercase;font-weight:600;">City & State</span>
            <div style="font-size:14px;font-weight:500;margin-top:2px;">
              <?= htmlspecialchars($lead['city_state'] ?: 'Not Specified') ?>
            </div>
          </div>
          <div>
            <span style="font-size:12px;color:var(--admin-text-muted);text-transform:uppercase;font-weight:600;">Current Status</span>
            <div style="margin-top:4px;">
              <?= status_badge($lead['status']) ?>
            </div>
          </div>
        </div>

        <?php if (!empty($lead['message'])): ?>
          <div style="margin-top:24px;padding-top:20px;border-top:1px solid var(--admin-border);">
            <span style="font-size:12px;color:var(--admin-text-muted);text-transform:uppercase;font-weight:600;">Student's Message / Query</span>
            <div style="background:#F8FAFC;padding:14px;border-radius:8px;border:1px solid var(--admin-border);margin-top:6px;font-size:13.5px;">
              <?= nl2br(htmlspecialchars($lead['message'])) ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Right Column: Counselor Notes & Status Editor -->
  <div>
    <div class="card">
      <div class="card-header">
        <h3><i class="ri-edit-2-line"></i> Counselor Actions</h3>
      </div>
      <div class="card-body">
        <form method="POST" action="">
          <input type="hidden" name="save_lead" value="1">

          <div class="form-group">
            <label for="status">Admission Stage / Status</label>
            <select class="form-control" name="status" id="status">
              <option value="new" <?= $lead['status'] === 'new' ? 'selected' : '' ?>>⚡ New (Unattended)</option>
              <option value="contacted" <?= $lead['status'] === 'contacted' ? 'selected' : '' ?>>📞 Contacted (Intro Call Done)</option>
              <option value="in_progress" <?= $lead['status'] === 'in_progress' ? 'selected' : '' ?>>⏳ In Progress (Docs Collection / Apostille)</option>
              <option value="admitted" <?= $lead['status'] === 'admitted' ? 'selected' : '' ?>>🎓 Admitted (Seat Confirmed / Visa Done)</option>
              <option value="rejected" <?= $lead['status'] === 'rejected' ? 'selected' : '' ?>>❌ Rejected / Dropped</option>
            </select>
          </div>

          <div class="form-group">
            <label for="notes">Internal Counselor Remarks & Follow-up Notes</label>
            <textarea class="form-control" name="notes" id="notes" rows="6" placeholder="Write counseling updates, parent discussion notes, documents pending, or follow-up date..."><?= htmlspecialchars($lead['notes'] ?? '') ?></textarea>
          </div>

          <button type="submit" class="btn btn-primary" style="width:100%;">
            <i class="ri-save-line"></i> Save Notes & Update Status
          </button>
        </form>

        <div style="margin-top:24px;padding-top:16px;border-top:1px dashed var(--admin-border);font-size:12px;color:var(--admin-text-muted);">
          <div><strong>IP Address:</strong> <?= htmlspecialchars($lead['ip_address'] ?: '—') ?></div>
          <div><strong>Last Updated:</strong> <?= format_date($lead['updated_at']) ?></div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/inc/footer.php'; ?>
