<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../login.php"); exit(); }
require_once '../includes/db.php';
$pageTitle = 'Add Brand';
$errors = []; $success = '';

// Upload dir for logos
$uploadDir = ($_SERVER['SERVER_NAME'] === 'localhost')
    ? $_SERVER['DOCUMENT_ROOT'] . '/supergifts/images/brandlogo/'
    : $_SERVER['DOCUMENT_ROOT'] . '/images/brandlogo/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brandname = trim($_POST['brandname'] ?? '');
    $links     = trim($_POST['links'] ?? '');
    $flag      = intval($_POST['flag'] ?? 1);
    $seqence   = intval($_POST['seqence'] ?? 0);
    $imageno   = '';

    if (!$brandname) $errors[] = "Brand name is required.";

    // Get next imageno if not uploading
    if (!$errors) {
        $maxImg = $conn->query("SELECT MAX(imageno) as m FROM brandlogo")->fetch_assoc()['m'];
        $imageno = ($maxImg ?? 0) + 1;
    }

    // Handle logo upload
    if (!$errors && !empty($_FILES['logo']['name'])) {
        $allowed = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = "Only JPG, PNG, WEBP allowed.";
        } elseif ($_FILES['logo']['size'] > 2 * 1024 * 1024) {
            $errors[] = "Logo must be under 2MB.";
        } else {
            // Save as image{imageno}.jpg
            $filename = 'image' . $imageno . '.' . $ext;
            if (!move_uploaded_file($_FILES['logo']['tmp_name'], $uploadDir . $filename)) {
                $errors[] = "Failed to upload logo. Check images/brandlogo/ folder permissions.";
            }
        }
    } elseif (!$errors && empty($_FILES['logo']['name'])) {
        $errors[] = "Brand logo is required.";
    }

    if (!$errors) {
        $stmt = $conn->prepare("INSERT INTO brandlogo (brandname, links, imageno, seqence, flag) VALUES (?,?,?,?,?)");
        $stmt->bind_param("ssiii", $brandname, $links, $imageno, $seqence, $flag);
        if ($stmt->execute()) {
            $newId = $conn->insert_id;
            $success = "Brand added! <a href='../products/add.php?brand_id={$newId}'>Add products →</a>";
            $brandname = $links = ''; $flag = 1; $seqence = 0;
        } else {
            $errors[] = "DB error: " . $conn->error;
        }
        $stmt->close();
    }
}

require_once '../includes/layout_top.php';
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Add New Brand</h5>
</div>

<?php if ($errors): ?>
    <div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i><?= $success ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="form-card mb-4">
                <div class="mb-3">
                    <label class="form-label">Brand Name <span class="text-danger">*</span></label>
                    <input type="text" name="brandname" class="form-control"
                           value="<?= htmlspecialchars($brandname ?? '') ?>"
                           placeholder="e.g. Blaupunkt" required>
                </div>
                <div class="mb-0">
                    <label class="form-label">Catalog URL <span class="text-muted small fw-normal">(Google Drive / PDF link)</span></label>
                    <input type="text" name="links" class="form-control"
                           value="<?= htmlspecialchars($links ?? '') ?>"
                           placeholder="https://drive.google.com/...">
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-card mb-4">
                <h6 class="fw-bold mb-3">Brand Settings</h6>
                <div class="mb-3">
                    <label class="form-label">Brand Type</label>
                    <select name="flag" class="form-select">
                        <option value="1" <?= ($flag??1)==1?'selected':'' ?>>⭐ Authorised Brand Partner</option>
                        <option value="0" <?= ($flag??1)==0?'selected':'' ?>>🤝 We Also Deal</option>
                    </select>
                </div>
                <div class="mb-0">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="seqence" class="form-control"
                           value="<?= $seqence ?? 0 ?>" min="0"
                           placeholder="0">
                    <div class="text-muted small mt-1">Lower number = shown first.</div>
                </div>
            </div>

            <div class="form-card">
                <h6 class="fw-bold mb-3">Brand Logo <span class="text-danger">*</span></h6>
                <div id="preview-box" style="display:none;margin-bottom:10px;text-align:center;">
                    <img id="img-preview" src=""
                         style="max-height:80px;max-width:100%;object-fit:contain;border:1px solid #eee;border-radius:6px;padding:6px;background:#fff;">
                </div>
                <input type="file" name="logo" class="form-control" accept="image/jpeg,image/png,image/webp"
                       onchange="previewImage(this)" required>
                <div class="text-muted small mt-1">Max 2MB. JPG, PNG or WEBP.</div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-3">
        <button type="submit" class="btn btn-gold px-5"><i class="bi bi-save me-2"></i>Save Brand</button>
        <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('img-preview').src = e.target.result;
            document.getElementById('preview-box').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once '../includes/layout_bottom.php'; ?>
