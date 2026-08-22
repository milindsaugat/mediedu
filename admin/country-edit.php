<?php
/**
 * MedicEdu Global — Add / Edit Study Destination Country
 */
$pageTitle = 'Edit Study Destination';
$breadcrumb = 'Edit Country';

require_once __DIR__ . '/inc/header.php';

$db = getDB();
$countryId = (int)($_GET['id'] ?? 0);
$isEditing = $countryId > 0;

$country = [
    'name'          => '',
    'slug'          => '',
    'flag_img'      => '',
    'hero_img'      => '',
    'tagline'       => '',
    'duration'      => '6 Years',
    'medium'        => '100% English',
    'tuition_range' => '',
    'living_cost'   => '',
    'key_highlight' => '',
    'overview_text' => '',
    'is_active'     => 1,
    'sort_order'    => 0
];

if ($isEditing && $db) {
    $stmt = $db->prepare("SELECT * FROM countries WHERE id = ? LIMIT 1");
    $stmt->execute([$countryId]);
    $existing = $stmt->fetch();
    if ($existing) {
        $country = $existing;
    } else {
        set_flash('danger', 'Country not found.');
        header("Location: countries.php");
        exit;
    }
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_country'])) {
    $name          = clean($_POST['name'] ?? '');
    $slug          = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', clean($_POST['slug'] ?? $name))));
    $flag_img      = clean($_POST['flag_img'] ?? '');
    $hero_img      = clean($_POST['hero_img'] ?? '');
    $tagline       = clean($_POST['tagline'] ?? '');
    $duration      = clean($_POST['duration'] ?? '6 Years');
    $medium        = clean($_POST['medium'] ?? '100% English');
    $tuition_range = clean($_POST['tuition_range'] ?? '');
    $living_cost   = clean($_POST['living_cost'] ?? '');
    $key_highlight = clean($_POST['key_highlight'] ?? '');
    $overview_text = clean($_POST['overview_text'] ?? '');
    $is_active     = isset($_POST['is_active']) ? 1 : 0;
    $sort_order    = (int)($_POST['sort_order'] ?? 0);

    if (empty($name)) {
        set_flash('danger', 'Please enter the country name.');
    } elseif ($db) {
        try {
            if ($isEditing) {
                $stmt = $db->prepare("
                    UPDATE countries SET 
                    name = ?, slug = ?, flag_img = ?, hero_img = ?, tagline = ?, 
                    duration = ?, medium = ?, tuition_range = ?, living_cost = ?, 
                    key_highlight = ?, overview_text = ?, is_active = ?, sort_order = ?
                    WHERE id = ?
                ");
                $stmt->execute([
                    $name, $slug, $flag_img, $hero_img, $tagline,
                    $duration, $medium, $tuition_range, $living_cost,
                    $key_highlight, $overview_text, $is_active, $sort_order,
                    $countryId
                ]);
                set_flash('success', 'Country "' . $name . '" updated successfully.');
            } else {
                $stmt = $db->prepare("
                    INSERT INTO countries 
                    (name, slug, flag_img, hero_img, tagline, duration, medium, tuition_range, living_cost, key_highlight, overview_text, is_active, sort_order)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $name, $slug, $flag_img, $hero_img, $tagline,
                    $duration, $medium, $tuition_range, $living_cost,
                    $key_highlight, $overview_text, $is_active, $sort_order
                ]);
                set_flash('success', 'New destination country "' . $name . '" created successfully.');
            }
            header("Location: countries.php");
            exit;
        } catch (PDOException $e) {
            set_flash('danger', 'Database error: ' . $e->getMessage());
        }
    }
}
?>

<div class="page-head">
  <div class="page-title">
    <h1><?= $isEditing ? 'Edit Country: ' . htmlspecialchars($country['name']) : 'Add New Study Destination' ?></h1>
    <p>Configure destination details, duration, tuition metrics, and highlights.</p>
  </div>
  <div class="page-actions">
    <a href="countries.php" class="btn btn-secondary">
      <i class="ri-arrow-left-line"></i> Back to Destinations
    </a>
  </div>
</div>

<div class="card" style="max-width:860px;">
  <div class="card-body">
    <form method="POST" action="">
      <input type="hidden" name="save_country" value="1">

      <div class="form-row">
        <div class="form-group">
          <label for="name">Country Name *</label>
          <input class="form-control" type="text" id="name" name="name" required placeholder="e.g. Bosnia & Herzegovina" value="<?= htmlspecialchars($country['name']) ?>">
        </div>

        <div class="form-group">
          <label for="slug">URL Slug *</label>
          <input class="form-control" type="text" id="slug" name="slug" required placeholder="e.g. bosnia" value="<?= htmlspecialchars($country['slug']) ?>">
        </div>
      </div>

      <div class="form-group">
        <label for="tagline">Header Tagline / Pill Badge</label>
        <input class="form-control" type="text" id="tagline" name="tagline" placeholder="e.g. European Standard Medical Education" value="<?= htmlspecialchars($country['tagline']) ?>">
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="duration">Course Duration</label>
          <input class="form-control" type="text" id="duration" name="duration" placeholder="e.g. 6 Years (5+1)" value="<?= htmlspecialchars($country['duration']) ?>">
        </div>

        <div class="form-group">
          <label for="medium">Medium of Instruction</label>
          <input class="form-control" type="text" id="medium" name="medium" placeholder="e.g. 100% English" value="<?= htmlspecialchars($country['medium']) ?>">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="tuition_range">Annual Tuition Range</label>
          <input class="form-control" type="text" id="tuition_range" name="tuition_range" placeholder="e.g. €3,600 – €6,000 / Yr (~₹3.9L)" value="<?= htmlspecialchars($country['tuition_range']) ?>">
        </div>

        <div class="form-group">
          <label for="living_cost">Cost of Living (Hostel & Food)</label>
          <input class="form-control" type="text" id="living_cost" name="living_cost" placeholder="e.g. €250 – €350 / Month" value="<?= htmlspecialchars($country['living_cost']) ?>">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="flag_img">Flag Image Path</label>
          <input class="form-control" type="text" id="flag_img" name="flag_img" placeholder="e.g. img/flags/bosnia.svg" value="<?= htmlspecialchars($country['flag_img']) ?>">
        </div>

        <div class="form-group">
          <label for="hero_img">Hero Graphic Image Path</label>
          <input class="form-control" type="text" id="hero_img" name="hero_img" placeholder="e.g. img/bosnia.webp" value="<?= htmlspecialchars($country['hero_img']) ?>">
        </div>
      </div>

      <div class="form-group">
        <label for="key_highlight">Key Highlights Summary</label>
        <input class="form-control" type="text" id="key_highlight" name="key_highlight" placeholder="e.g. Most Affordable in Europe · University of East Sarajevo" value="<?= htmlspecialchars($country['key_highlight']) ?>">
      </div>

      <div class="form-group">
        <label for="overview_text">Overview & Geographic Background</label>
        <textarea class="form-control" id="overview_text" name="overview_text" rows="4" placeholder="Description of medical education, teaching hospitals, and clinical exposure..."><?= htmlspecialchars($country['overview_text']) ?></textarea>
      </div>

      <div class="form-row" style="align-items:center;">
        <div class="form-group">
          <label for="sort_order">Sort Order Priority</label>
          <input class="form-control" type="number" id="sort_order" name="sort_order" style="max-width:120px;" value="<?= (int)$country['sort_order'] ?>">
        </div>

        <div class="form-group" style="padding-top:18px;">
          <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
            <input type="checkbox" name="is_active" value="1" <?= $country['is_active'] ? 'checked' : '' ?> style="width:18px;height:18px;">
            <span style="font-weight:600;">Active & Visible on Website</span>
          </label>
        </div>
      </div>

      <div style="margin-top:24px;display:flex;gap:12px;">
        <button type="submit" class="btn btn-primary">
          <i class="ri-save-line"></i> <?= $isEditing ? 'Save Country Changes' : 'Create Destination' ?>
        </button>
        <a href="countries.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/inc/footer.php'; ?>
