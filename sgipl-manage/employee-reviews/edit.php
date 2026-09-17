<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/image_helper.php';

$pageTitle = 'Edit Employee Review';
$errors = [];

$id = intval($_GET['id'] ?? 0);
if (!$id) { header("Location: index.php"); exit(); }

$stmt = $conn->prepare("SELECT * FROM employee_testimonials WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$row) { header("Location: index.php"); exit(); }

$isLocal   = ($_SERVER['SERVER_NAME'] === 'localhost');
$uploadDir = $isLocal
    ? $_SERVER['DOCUMENT_ROOT'] . '/supergifts/images/employee-reviews/'
    : $_SERVER['DOCUMENT_ROOT'] . '/images/employee-reviews/';
$uploadUrl = 'images/employee-reviews/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

$quote         = $row['quote'];
$employee_name = $row['employee_name'];
$role          = $row['role'];
$sequence      = intval($row['sequence']);
$status        = intval($row['status']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quote         = trim($_POST['quote'] ?? '');
    $employee_name = trim($_POST['employee_name'] ?? '');
    $role          = trim($_POST['role'] ?? '');
    $sequence      = max(0, intval($_POST['sequence'] ?? 0));
    $status        = intval($_POST['status'] ?? 1);
    $photo         = $row['photo'];

    if (!$quote)         $errors[] = "Quote is required.";
    if (!$employee_name) $errors[] = "Employee name is required.";

    if (!$errors && !empty($_FILES['photo']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = "Photo must be JPG, PNG or WEBP.";
        } elseif ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
            $errors[] = "Photo must be under 2MB.";
        } else {
            $old = $photo;
            $filename = 'employee-review-' . time() . '-' . uniqid() . '.' . $ext;
            $dest = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $dest)) {
                compressUploadedImage($dest, 500);
                $photo = $uploadUrl . $filename;
                if (!empty($old)) {
                    $oldPath = $isLocal
                        ? $_SERVER['DOCUMENT_ROOT'] . '/supergifts/' . $old
                        : $_SERVER['DOCUMENT_ROOT'] . '/' . $old;
                    if (file_exists($oldPath)) @unlink($oldPath);
                }
            } else {
                $errors[] = "Failed to upload photo. Check folder permissions on images/employee-reviews/.";
            }
        }
    }

    if (!$errors) {
        $stmt = $conn->prepare("UPDATE employee_testimonials SET photo=?, quote=?, employee_name=?, role=?, sequence=?, status=? WHERE id=?");
        $stmt->bind_param("ssssiii", $photo, $quote, $employee_name, $role, $sequence, $status, $id);
        if ($stmt->execute()) {
            header("Location: index.php?saved=1");
            exit();
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
        $stmt->close();
    }
}

require_once '../includes/layout_top.php';
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Edit Employee Review</h5>
</div>

<?php if ($errors): ?>
    <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="form-card mb-4">
                <label class="form-label">Quote <span class="text-danger">*</span></label>
                <textarea name="quote" class="form-control" rows="6"><?= htmlspecialchars($quote) ?></textarea>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-card mb-4">
                <h6 class="fw-bold mb-3">Employee Details</h6>
                <div class="mb-3">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="employee_name" class="form-control" value="<?= htmlspecialchars($employee_name) ?>">
                </div>
                <div class="mb-0">
                    <label class="form-label">Role / Designation</label>
                    <input type="text" name="role" class="form-control" value="<?= htmlspecialchars($role) ?>">
                </div>
            </div>

            <div class="form-card mb-4">
                <h6 class="fw-bold mb-3">Photo</h6>
                <?php if (!empty($row['photo'])): ?>
                <div class="mb-2"><img src="../../<?= htmlspecialchars($row['photo']) ?>" style="width:100px;height:100px;object-fit:cover;border-radius:50%;border:1px solid #e9ecef;"></div>
                <?php endif; ?>
                <input type="file" name="photo" class="form-control" accept="image/jpeg,image/png,image/webp" onchange="previewImage(this, 'img-preview-photo', 'preview-box-photo')">
                <div id="preview-box-photo" class="mt-2" style="display:none;">
                    <img id="img-preview-photo" src="" style="width:100px;height:100px;object-fit:cover;border-radius:50%;border:1px solid #e9ecef;">
                </div>
                <div class="text-muted small mt-2">Upload a new file to replace it. Leave empty to keep the current one.</div>
            </div>

            <div class="form-card">
                <h6 class="fw-bold mb-3">Publish Settings</h6>
                <div class="mb-3">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="sequence" class="form-control" value="<?= intval($sequence) ?>" min="0">
                    <div class="text-muted small mt-1">Lower numbers show first in the carousel.</div>
                </div>
                <div class="mb-0">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="1" <?= $status ? 'selected' : '' ?>>Active (show on site)</option>
                        <option value="0" <?= !$status ? 'selected' : '' ?>>Hidden</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-3">
        <button type="submit" class="btn btn-gold px-5"><i class="bi bi-save me-2"></i>Save Changes</button>
        <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

<script>
function previewImage(input, previewId, previewBoxId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById(previewId).src = e.target.result;
            document.getElementById(previewBoxId).style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once '../includes/layout_bottom.php'; ?>
