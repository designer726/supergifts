<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../login.php"); exit(); }
require_once '../includes/db.php';
$pageTitle = 'Edit Brand';
$errors = []; $success = '';

$id = intval($_GET['id'] ?? 0);
if (!$id) { header("Location: index.php"); exit(); }

$stmt = $conn->prepare("SELECT * FROM brandlogo WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$brand = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$brand) { header("Location: index.php"); exit(); }

$uploadDir = ($_SERVER['SERVER_NAME'] === 'localhost')
    ? $_SERVER['DOCUMENT_ROOT'] . '/supergifts/images/brandlogo/'
    : $_SERVER['DOCUMENT_ROOT'] . '/images/brandlogo/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brandname = trim($_POST['brandname'] ?? '');
    $links     = trim($_POST['links'] ?? '');
    $flag      = intval($_POST['flag'] ?? 1);
    $seqence   = intval($_POST['seqence'] ?? 0);
    $imageno   = $brand['imageno']; // Keep old imageno by default

    if (!$brandname) $errors[] = "Brand name is required.";

    // Handle new logo upload
    if (!$errors && !empty($_FILES['logo']['name'])) {
        $allowed = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = "Only JPG, PNG, WEBP allowed.";
        } elseif ($_FILES['logo']['size'] > 2 * 1024 * 1024) {
            $errors[] = "Logo must be under 2MB.";
        } else {
            // Delete old logo file
            $oldFile = $uploadDir . 'image' . $brand['imageno'] . '.jpg';
            if (file_exists($oldFile)) @unlink($oldFile);
            // Save new logo with same imageno
            $filename = 'image' . $imageno . '.' . $ext;
            if (!move_uploaded_file($_FILES['logo']['tmp_name'], $uploadDir . $filename)) {
                $errors[] = "Failed to upload logo. Check folder permissions.";
            }
        }
    }

    if (!$errors) {
        $stmt = $conn->prepare("UPDATE brandlogo SET brandname=?, links=?, seqence=?, flag=? WHERE id=?");
        $stmt->bind_param("ssiii", $brandname, $links, $seqence, $flag, $id);
        if ($stmt->execute()) {
            $success = "Brand updated successfully!";
            $brand = array_merge($brand, compact('brandname','links','seqence','flag'));
        } else {
            $errors[] = "DB error: " . $conn->error;
        }
        $stmt->close();
    }
}

$logoUrl = ($_SERVER['SERVER_NAME']==='localhost' ? '/supergifts/' : '/') . 'images/brandlogo/image' . $brand['imageno'] . '.jpg';

require_once '../includes/layout_top.php';
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Edit Brand: <span class="text-capitalize"><?= htmlspecialchars($brand['brandname']) ?></span></h5>
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
                           value="<?= htmlspecialchars($brand['brandname']) ?>" required>
                </div>
                <div class="mb-0">
                    <label class="form-label">Catalog URL</label>
                    <input type="text" name="links" class="form-control"
                           value="<?= htmlspecialchars($brand['links']) ?>"
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
                        <option value="1" <?= $brand['flag']==1?'selected':'' ?>>⭐ Authorised Brand Partner</option>
                        <option value="0" <?= $brand['flag']==0?'selected':'' ?>>🤝 We Also Deal</option>
                    </select>
                </div>
                <div class="mb-0">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="seqence" class="form-control"
                           value="<?= $brand['seqence'] ?>" min="0">
                    <div class="text-muted small mt-1">Lower number = shown first.</div>
                </div>
            </div>

            <div class="form-card">
                <h6 class="fw-bold mb-3">Brand Logo</h6>
                <img id="img-preview" src="<?= htmlspecialchars($logoUrl) ?>"
                     style="max-height:80px;max-width:100%;object-fit:contain;border:1px solid #eee;border-radius:6px;padding:6px;background:#fff;margin-bottom:10px;"
                     onerror="this.style.display='none'">
                <input type="file" name="logo" class="form-control" accept="image/jpeg,image/png,image/webp"
                       onchange="previewImage(this)">
                <div class="text-muted small mt-1">Leave empty to keep current logo.</div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-3">
        <button type="submit" class="btn btn-gold px-5"><i class="bi bi-save me-2"></i>Save Changes</button>
        <a href="../products/index.php?brand_id=<?= $id ?>" class="btn btn-outline-primary">
            <i class="bi bi-box-seam me-1"></i>Manage Products
        </a>
        <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('img-preview');
            img.src = e.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once '../includes/layout_bottom.php'; ?>
