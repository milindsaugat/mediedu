<?php
/**
 * MedicEdu Global — Global Site Settings Manager
 */
$pageTitle = 'Site Settings & Contact Info';
$breadcrumb = 'Settings';

require_once __DIR__ . '/inc/header.php';

$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    if ($db) {
        try {
            $settingsToSave = [
                'site_title'       => clean($_POST['site_title'] ?? ''),
                'phone_primary'    => clean($_POST['phone_primary'] ?? ''),
                'phone_display'    => clean($_POST['phone_display'] ?? ''),
                'whatsapp_number'  => clean($_POST['whatsapp_number'] ?? ''),
                'whatsapp_link'    => clean($_POST['whatsapp_link'] ?? ''),
                'email_primary'    => clean($_POST['email_primary'] ?? ''),
                'office_address'   => clean($_POST['office_address'] ?? ''),
                'working_hours'    => clean($_POST['working_hours'] ?? ''),
                'session_year'     => clean($_POST['session_year'] ?? '2026–2027'),
                'cta_headline'     => clean($_POST['cta_headline'] ?? '')
            ];

            $stmt = $db->prepare("
                INSERT INTO settings (setting_key, setting_value) 
                VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
            ");

            foreach ($settingsToSave as $k => $v) {
                $stmt->execute([$k, $v]);
            }

            set_flash('success', 'Site settings updated successfully.');
            header("Location: settings.php");
            exit;
        } catch (PDOException $e) {
            set_flash('danger', 'Error updating settings: ' . $e->getMessage());
        }
    }
}

// Fetch current settings
$settings = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT setting_key, setting_value FROM settings");
        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    } catch (PDOException $e) {
        // Fallback
    }
}
?>

<div class="page-head">
  <div class="page-title">
    <h1>Global Website Settings & Contact Information</h1>
    <p>Manage helpline numbers, support email, WhatsApp contact, session year, and office details.</p>
  </div>
</div>

<div class="card" style="max-width:860px;">
  <div class="card-body">
    <form method="POST" action="">
      <input type="hidden" name="save_settings" value="1">

      <h3 style="font-size:16px;margin-bottom:18px;color:var(--admin-navy);border-bottom:1px solid var(--admin-border);padding-bottom:8px;">
        <i class="ri-phone-line"></i> Contact & Helpline Numbers
      </h3>

      <div class="form-row">
        <div class="form-group">
          <label for="phone_primary">Primary Contact Number (Dialer)</label>
          <input class="form-control" type="text" id="phone_primary" name="phone_primary" value="<?= htmlspecialchars($settings['phone_primary'] ?? '+91 94106 24320') ?>" placeholder="+91 94106 24320">
        </div>

        <div class="form-group">
          <label for="phone_display">Display Phone Number (Formatted)</label>
          <input class="form-control" type="text" id="phone_display" name="phone_display" value="<?= htmlspecialchars($settings['phone_display'] ?? '94 10 62 43 20') ?>" placeholder="94 10 62 43 20">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="whatsapp_number">WhatsApp Number</label>
          <input class="form-control" type="text" id="whatsapp_number" name="whatsapp_number" value="<?= htmlspecialchars($settings['whatsapp_number'] ?? '+91 94106 24320') ?>" placeholder="+91 94106 24320">
        </div>

        <div class="form-group">
          <label for="whatsapp_link">WhatsApp Direct Click Link</label>
          <input class="form-control" type="text" id="whatsapp_link" name="whatsapp_link" value="<?= htmlspecialchars($settings['whatsapp_link'] ?? 'https://wa.me/919410624320') ?>" placeholder="https://wa.me/919410624320">
        </div>
      </div>

      <div class="form-group">
        <label for="email_primary">Primary Support Email Address</label>
        <input class="form-control" type="email" id="email_primary" name="email_primary" value="<?= htmlspecialchars($settings['email_primary'] ?? 'tarunrockthakur@gmail.com') ?>" placeholder="tarunrockthakur@gmail.com">
      </div>

      <h3 style="font-size:16px;margin:28px 0 18px;color:var(--admin-navy);border-bottom:1px solid var(--admin-border);padding-bottom:8px;">
        <i class="ri-calendar-check-line"></i> Academic Session & Headquarters
      </h3>

      <div class="form-row">
        <div class="form-group">
          <label for="session_year">Active Admission Session</label>
          <input class="form-control" type="text" id="session_year" name="session_year" value="<?= htmlspecialchars($settings['session_year'] ?? '2026–2027') ?>" placeholder="2026–2027">
        </div>

        <div class="form-group">
          <label for="working_hours">Counseling Hours</label>
          <input class="form-control" type="text" id="working_hours" name="working_hours" value="<?= htmlspecialchars($settings['working_hours'] ?? 'Mon – Sat: 9:30 AM – 6:30 PM') ?>" placeholder="Mon – Sat: 9:30 AM – 6:30 PM">
        </div>
      </div>

      <div class="form-group">
        <label for="office_address">Head Office Location</label>
        <input class="form-control" type="text" id="office_address" name="office_address" value="<?= htmlspecialchars($settings['office_address'] ?? 'Head Office: India') ?>" placeholder="Head Office: India">
      </div>

      <div class="form-group">
        <label for="cta_headline">Consultation Banner Headline</label>
        <input class="form-control" type="text" id="cta_headline" name="cta_headline" value="<?= htmlspecialchars($settings['cta_headline'] ?? 'Book 1:1 Free Medical Counselling for 2026–2027 Session') ?>" placeholder="Book 1:1 Free Medical Counselling for 2026–2027 Session">
      </div>

      <div style="margin-top:28px;">
        <button type="submit" class="btn btn-primary btn-lg">
          <i class="ri-save-line"></i> Save Website Settings
        </button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/inc/footer.php'; ?>
