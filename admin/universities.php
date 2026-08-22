<?php
/**
 * MedicEdu Global — Universities & Fee Structure Matrix Manager
 */
$pageTitle = 'Universities & Fee Structures';
$breadcrumb = 'Universities';

require_once __DIR__ . '/inc/header.php';

$db = getDB();

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($db && $id > 0) {
        $stmt = $db->prepare("DELETE FROM universities WHERE id = ?");
        $stmt->execute([$id]);
        set_flash('success', 'University deleted successfully.');
        header("Location: universities.php" . (isset($_GET['country_id']) ? '?country_id=' . (int)$_GET['country_id'] : ''));
        exit;
    }
}

// Handle Add / Edit Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_university'])) {
    $univId       = (int)($_POST['univ_id'] ?? 0);
    $countryId    = (int)($_POST['country_id'] ?? 0);
    $name         = clean($_POST['name'] ?? '');
    $tuition_fee  = clean($_POST['tuition_fee'] ?? '');
    $hostel_food  = clean($_POST['hostel_food_cost'] ?? '');
    $medium       = clean($_POST['medium'] ?? '100% English');
    $nmc_status   = clean($_POST['nmc_status'] ?? 'Approved');
    $sort_order   = (int)($_POST['sort_order'] ?? 0);

    if (empty($name) || $countryId <= 0) {
        set_flash('danger', 'Please select a country and provide the university name.');
    } elseif ($db) {
        try {
            if ($univId > 0) {
                $stmt = $db->prepare("
                    UPDATE universities SET 
                    country_id = ?, name = ?, tuition_fee = ?, hostel_food_cost = ?, 
                    medium = ?, nmc_status = ?, sort_order = ? 
                    WHERE id = ?
                ");
                $stmt->execute([$countryId, $name, $tuition_fee, $hostel_food, $medium, $nmc_status, $sort_order, $univId]);
                set_flash('success', 'University "' . $name . '" updated successfully.');
            } else {
                $stmt = $db->prepare("
                    INSERT INTO universities 
                    (country_id, name, tuition_fee, hostel_food_cost, medium, nmc_status, sort_order)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$countryId, $name, $tuition_fee, $hostel_food, $medium, $nmc_status, $sort_order]);
                set_flash('success', 'New university "' . $name . '" added successfully.');
            }
            header("Location: universities.php?country_id=" . $countryId);
            exit;
        } catch (PDOException $e) {
            set_flash('danger', 'Error: ' . $e->getMessage());
        }
    }
}

// Country Filter
$filterCountryId = (int)($_GET['country_id'] ?? 0);

$countries = [];
$universities = [];

if ($db) {
    try {
        $stmt = $db->query("SELECT id, name FROM countries ORDER BY sort_order ASC, name ASC");
        $countries = $stmt->fetchAll();

        $query = "
            SELECT u.*, c.name as country_name, c.flag_img 
            FROM universities u 
            JOIN countries c ON u.country_id = c.id 
            WHERE 1=1
        ";
        $params = [];

        if ($filterCountryId > 0) {
            $query .= " AND u.country_id = ?";
            $params[] = $filterCountryId;
        }

        $query .= " ORDER BY c.sort_order ASC, u.sort_order ASC, u.name ASC";

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $universities = $stmt->fetchAll();
    } catch (PDOException $e) {
        // Fallback
    }
}
?>

<div class="page-head">
  <div class="page-title">
    <h1>Medical Universities & Fee Structure Matrix</h1>
    <p>Manage list of medical universities, annual tuition fees, hostel expenses, and NMC status badges.</p>
  </div>
  <div class="page-actions">
    <button type="button" class="btn btn-primary" onclick="openAddUnivModal()">
      <i class="ri-add-line"></i> Add New University
    </button>
  </div>
</div>

<!-- Country Filter Tabs -->
<div class="card" style="margin-bottom:20px;">
  <div class="card-body" style="padding:14px 20px;">
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
      <span style="font-size:13px;font-weight:700;color:var(--admin-navy);margin-right:6px;"><i class="ri-filter-line"></i> Filter by Country:</span>
      <a href="universities.php" class="btn btn-sm <?= $filterCountryId === 0 ? 'btn-primary' : 'btn-secondary' ?>">
        All Countries (<?= count($universities) ?>)
      </a>
      <?php foreach ($countries as $c): ?>
        <a href="universities.php?country_id=<?= $c['id'] ?>" class="btn btn-sm <?= $filterCountryId === (int)$c['id'] ? 'btn-primary' : 'btn-secondary' ?>">
          <?= htmlspecialchars($c['name']) ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Universities Table -->
<div class="card">
  <div class="card-header">
    <h3><i class="ri-building-4-line"></i> Universities List (<?= count($universities) ?>)</h3>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>#</th>
          <th>University Name</th>
          <th>Country</th>
          <th>Annual Tuition Fee</th>
          <th>Hostel & Food / Mo</th>
          <th>Medium</th>
          <th>NMC Status</th>
          <th style="text-align:right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($universities)): ?>
          <tr>
            <td colspan="8" style="text-align:center;padding:36px;color:var(--admin-text-muted);">
              No universities found for the selected filter. Click "Add New University" to create one.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($universities as $u): ?>
            <tr>
              <td><strong><?= $u['sort_order'] ?></strong></td>
              <td>
                <strong style="color:var(--admin-navy);font-size:14px;"><?= htmlspecialchars($u['name']) ?></strong>
              </td>
              <td>
                <div style="display:flex;align-items:center;gap:6px;">
                  <?php if (!empty($u['flag_img'])): ?>
                    <img src="../<?= htmlspecialchars($u['flag_img']) ?>" alt="" style="width:20px;height:14px;border-radius:2px;">
                  <?php endif; ?>
                  <span style="font-weight:600;"><?= htmlspecialchars($u['country_name']) ?></span>
                </div>
              </td>
              <td>
                <strong style="color:var(--admin-navy);"><?= htmlspecialchars($u['tuition_fee']) ?></strong>
              </td>
              <td>
                <?= htmlspecialchars($u['hostel_food_cost'] ?: '—') ?>
              </td>
              <td>
                <span class="badge badge-secondary"><?= htmlspecialchars($u['medium']) ?></span>
              </td>
              <td>
                <span class="badge badge-success"><i class="ri-checkbox-circle-fill"></i> <?= htmlspecialchars($u['nmc_status']) ?></span>
              </td>
              <td style="text-align:right;">
                <div style="display:inline-flex;gap:6px;">
                  <button type="button" class="btn btn-sm btn-secondary btn-icon" title="Edit University" onclick='editUniv(<?= json_encode($u) ?>)'>
                    <i class="ri-edit-line"></i>
                  </button>
                  <a href="universities.php?delete=<?= $u['id'] ?><?= $filterCountryId > 0 ? '&country_id=' . $filterCountryId : '' ?>" onclick="return confirm('Are you sure you want to delete <?= htmlspecialchars(addslashes($u['name'])) ?>?');" class="btn btn-sm btn-danger btn-icon" title="Delete University">
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

<!-- Add / Edit Modal -->
<div id="univModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(10,41,77,0.6);z-index:1100;align-items:center;justify-content:center;padding:20px;">
  <div style="background:#FFFFFF;max-width:600px;width:100%;border-radius:16px;box-shadow:var(--shadow-lg);padding:28px;border:1px solid var(--admin-border);max-height:90vh;overflow-y:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;border-bottom:1px solid var(--admin-border);padding-bottom:14px;">
      <h3 id="modalTitle" style="margin:0;"><i class="ri-building-line"></i> Add Medical University</h3>
      <button type="button" onclick="closeUnivModal()" style="background:none;border:none;font-size:22px;cursor:pointer;color:var(--admin-text-muted);">&times;</button>
    </div>

    <form method="POST" action="">
      <input type="hidden" name="save_university" value="1">
      <input type="hidden" name="univ_id" id="modal_univ_id" value="0">

      <div class="form-group">
        <label for="modal_country_id">Country / Destination *</label>
        <select class="form-control" name="country_id" id="modal_country_id" required>
          <option value="">Select Country</option>
          <?php foreach ($countries as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $filterCountryId === (int)$c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="modal_name">University Name *</label>
        <input class="form-control" type="text" name="name" id="modal_name" required placeholder="e.g. University of East Sarajevo – Faculty of Medicine, Foča">
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="modal_tuition_fee">Annual Tuition Fee *</label>
          <input class="form-control" type="text" name="tuition_fee" id="modal_tuition_fee" required placeholder="e.g. €3,600 / Year (~₹3.9 Lakhs)">
        </div>

        <div class="form-group">
          <label for="modal_hostel_food_cost">Hostel & Food / Month</label>
          <input class="form-control" type="text" name="hostel_food_cost" id="modal_hostel_food_cost" placeholder="e.g. €250 / Month (~₹23,000)">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="modal_medium">Medium of Instruction</label>
          <input class="form-control" type="text" name="medium" id="modal_medium" value="100% English" placeholder="100% English">
        </div>

        <div class="form-group">
          <label for="modal_nmc_status">NMC Status Tag</label>
          <input class="form-control" type="text" name="nmc_status" id="modal_nmc_status" value="Approved" placeholder="e.g. Approved (Most Affordable EU)">
        </div>
      </div>

      <div class="form-group">
        <label for="modal_sort_order">Sort Order Priority</label>
        <input class="form-control" type="number" name="sort_order" id="modal_sort_order" value="1" style="max-width:120px;">
      </div>

      <div style="display:flex;gap:12px;margin-top:24px;justify-content:flex-end;">
        <button type="button" class="btn btn-secondary" onclick="closeUnivModal()">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="ri-save-line"></i> Save University</button>
      </div>
    </form>
  </div>
</div>

<script>
const univModal = document.getElementById('univModal');

function openAddUnivModal() {
  document.getElementById('modalTitle').innerHTML = '<i class="ri-building-line"></i> Add Medical University';
  document.getElementById('modal_univ_id').value = '0';
  document.getElementById('modal_name').value = '';
  document.getElementById('modal_tuition_fee').value = '';
  document.getElementById('modal_hostel_food_cost').value = '';
  document.getElementById('modal_medium').value = '100% English';
  document.getElementById('modal_nmc_status').value = 'Approved';
  document.getElementById('modal_sort_order').value = '1';
  univModal.style.display = 'flex';
}

function editUniv(data) {
  document.getElementById('modalTitle').innerHTML = '<i class="ri-edit-line"></i> Edit Medical University';
  document.getElementById('modal_univ_id').value = data.id;
  document.getElementById('modal_country_id').value = data.country_id;
  document.getElementById('modal_name').value = data.name;
  document.getElementById('modal_tuition_fee').value = data.tuition_fee;
  document.getElementById('modal_hostel_food_cost').value = data.hostel_food_cost || '';
  document.getElementById('modal_medium').value = data.medium || '100% English';
  document.getElementById('modal_nmc_status').value = data.nmc_status || 'Approved';
  document.getElementById('modal_sort_order').value = data.sort_order || '1';
  univModal.style.display = 'flex';
}

function closeUnivModal() {
  univModal.style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/inc/footer.php'; ?>
