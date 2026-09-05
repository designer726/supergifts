<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../login.php"); exit(); }
require_once '../includes/db.php';
require_once '../includes/image_helper.php';
$pageTitle = 'Edit Voucher';
$errors = []; $success = '';

$id = intval($_GET['id'] ?? 0);
if (!$id) { header("Location: index.php"); exit(); }

$stmt = $conn->prepare("SELECT * FROM vouchers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$voucher = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$voucher) { header("Location: index.php"); exit(); }

$isLocal   = ($_SERVER['SERVER_NAME'] === 'localhost');
$uploadDir = $isLocal
    ? $_SERVER['DOCUMENT_ROOT'] . '/supergifts/images/vouchers/'
    : $_SERVER['DOCUMENT_ROOT'] . '/images/vouchers/';
$uploadUrl = 'images/vouchers/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['title'] ?? '');
    $seqence = intval($_POST['seqence'] ?? 0);
    $status  = intval($_POST['status'] ?? 1);
    $image   = $voucher['image'];

    if (!$title) $errors[] = "Voucher title is required.";

    if (!$errors && !empty($_FILES['image']['name'])) {
        $allowed = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = "Only JPG, PNG, WEBP allowed.";
        } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
            $errors[] = "Image must be under 2MB.";
        } else {
            $oldFile = $isLocal
                ? $_SERVER['DOCUMENT_ROOT'] . '/supergifts/' . $voucher['image']
                : $_SERVER['DOCUMENT_ROOT'] . '/' . $voucher['image'];
            if ($voucher['image'] && file_exists($oldFile)) @unlink($oldFile);

            $filename = 'voucher-' . time() . '-' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
                compressUploadedImage($uploadDir . $filename, 1200);
                $image = $uploadUrl . $filename;
            } else {
                $errors[] = "Failed to upload image. Check folder permissions.";
            }
        }
    }

    if (!$errors) {
        $stmt = $conn->prepare("UPDATE vouchers SET title=?, image=?, seqence=?, status=? WHERE id=?");
        $stmt->bind_param("ssiii", $title, $image, $seqence, $status, $id);
        if ($stmt->execute()) {
            $success = "Voucher updated successfully!";
            $voucher = array_merge($voucher, compact('title','image','seqence','status'));
        } else {
            $errors[] = "DB error: " . $conn->error;
        }
        $stmt->close();
    }
}

$imgBase = $isLocal ? '/supergifts/' : '/';

require_once '../includes/layout_top.php';
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Edit Voucher: <span><?= htmlspecialchars($voucher['title']) ?></span></h5>
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
                <div class="mb-0">
                    <label class="form-label">Voucher Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control"
                           value="<?= htmlspecialchars($voucher['title']) ?>" required>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-card mb-4">
                <h6 class="fw-bold mb-3">Voucher Settings</h6>
                <div class="mb-3">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="seqence" class="form-control"
                           value="<?= $voucher['seqence'] ?>" min="0">
                    <div class="text-muted small mt-1">Lower number = shown first.</div>
                </div>
                <div class="mb-0">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="1" <?= $voucher['status']==1?'selected':'' ?>>Active (show on site)</option>
                        <option value="0" <?= $voucher['status']==0?'selected':'' ?>>Hidden</option>
                    </select>
                </div>
            </div>

            <div class="form-card">
                <h6 class="fw-bold mb-3">Voucher Image</h6>
                <?php if ($voucher['image']): ?>
                <img id="img-preview" src="<?= htmlspecialchars($imgBase . $voucher['image']) ?>"
                     style="max-height:100px;max-width:100%;object-fit:cover;border:1px solid #eee;border-radius:6px;padding:6px;background:#fff;margin-bottom:10px;display:block;"
                     onerror="this.style.display='none'">
                <?php else: ?>
                <img id="img-preview" src="" style="display:none;max-height:100px;max-width:100%;object-fit:cover;border:1px solid #eee;border-radius:6px;padding:6px;background:#fff;margin-bottom:10px;">
                <?php endif; ?>
                <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp"
                       onchange="previewImage(this)">
                <div class="text-muted small mt-1">Leave empty to keep current image.</div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-3">
        <button type="submit" class="btn btn-gold px-5"><i class="bi bi-save me-2"></i>Save Changes</button>
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
