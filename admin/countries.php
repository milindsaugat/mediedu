<?php
/**
 * MedicEdu Global — Study Destinations Manager
 */
$pageTitle = 'Study Destinations (Countries)';
$breadcrumb = 'Destinations';

require_once __DIR__ . '/inc/header.php';

$db = getDB();

// Handle Status Toggle
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    if ($db && $id > 0) {
        $stmt = $db->prepare("UPDATE countries SET is_active = 1 - is_active WHERE id = ?");
        $stmt->execute([$id]);
        set_flash('success', 'Country status toggled successfully.');
        header("Location: countries.php");
        exit;
    }
}

$countries = [];
if ($db) {
    try {
        $stmt = $db->query("
            SELECT c.*, COUNT(u.id) as university_count 
            FROM countries c 
            LEFT JOIN universities u ON c.id = u.country_id 
            GROUP BY c.id 
            ORDER BY c.sort_order ASC, c.name ASC
        ");
        $countries = $stmt->fetchAll();
    } catch (PDOException $e) {
        // Fallback
    }
}
?>

<div class="page-head">
  <div class="page-title">
    <h1>Study Destinations & MBBS Programs (8)</h1>
    <p>Manage medical education destinations, annual tuition fee ranges, and active status.</p>
  </div>
  <div class="page-actions">
    <a href="country-edit.php" class="btn btn-primary">
      <i class="ri-add-line"></i> Add New Country
    </a>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h3><i class="ri-earth-line"></i> Target Medical Study Countries</h3>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Country Name</th>
          <th>Duration & Medium</th>
          <th>Annual Tuition Fee</th>
          <th>Monthly Living</th>
          <th>Universities</th>
          <th>Status</th>
          <th style="text-align:right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($countries)): ?>
          <tr>
            <td colspan="8" style="text-align:center;padding:30px;color:var(--admin-text-muted);">
              No countries configured in the database. Please run the <a href="../database/install.php">database installer</a>.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($countries as $c): ?>
            <tr>
              <td><strong><?= $c['sort_order'] ?></strong></td>
              <td>
                <div style="display:flex;align-items:center;gap:10px;">
                  <?php if (!empty($c['flag_img'])): ?>
                    <img src="../<?= htmlspecialchars($c['flag_img']) ?>" alt="<?= htmlspecialchars($c['name']) ?>" style="width:24px;height:16px;object-fit:cover;border-radius:2px;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                  <?php endif; ?>
                  <div>
                    <a href="country-edit.php?id=<?= $c['id'] ?>" style="font-weight:700;color:var(--admin-navy);">
                      <?= htmlspecialchars($c['name']) ?>
                    </a>
                    <div style="font-size:11.5px;color:var(--admin-text-muted);"><?= htmlspecialchars($c['tagline'] ?? '') ?></div>
                  </div>
                </div>
              </td>
              <td>
                <div><strong><?= htmlspecialchars($c['duration']) ?></strong></div>
                <div style="font-size:11.5px;color:var(--admin-text-muted);"><?= htmlspecialchars($c['medium']) ?></div>
              </td>
              <td>
                <strong style="color:var(--admin-navy);"><?= htmlspecialchars($c['tuition_range']) ?></strong>
              </td>
              <td>
                <?= htmlspecialchars($c['living_cost'] ?: '—') ?>
              </td>
              <td>
                <a href="universities.php?country_id=<?= $c['id'] ?>" class="badge badge-primary">
                  <i class="ri-building-line"></i> <?= $c['university_count'] ?> Universities
                </a>
              </td>
              <td>
                <?php if ($c['is_active']): ?>
                  <span class="badge badge-success"><i class="ri-checkbox-circle-fill"></i> Active</span>
                <?php else: ?>
                  <span class="badge badge-danger"><i class="ri-close-circle-fill"></i> Inactive</span>
                <?php endif; ?>
              </td>
              <td style="text-align:right;">
                <div style="display:inline-flex;gap:6px;">
                  <a href="../countries/<?= htmlspecialchars($c['slug']) ?>.html" target="_blank" class="btn btn-sm btn-secondary btn-icon" title="Preview Public Page">
                    <i class="ri-external-link-line"></i>
                  </a>
                  <a href="country-edit.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-secondary btn-icon" title="Edit Destination">
                    <i class="ri-edit-line"></i>
                  </a>
                  <a href="countries.php?toggle=<?= $c['id'] ?>" class="btn btn-sm <?= $c['is_active'] ? 'btn-secondary' : 'btn-accent' ?> btn-icon" title="Toggle Active / Inactive">
                    <i class="<?= $c['is_active'] ? 'ri-eye-off-line' : 'ri-eye-line' ?>"></i>
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
