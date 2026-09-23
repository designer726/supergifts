<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/image_helper.php';

$pageTitle = 'Edit Testimonial';
$errors = [];

$id = intval($_GET['id'] ?? 0);
if (!$id) { header("Location: index.php"); exit(); }

$stmt = $conn->prepare("SELECT * FROM client_testimonials WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$row) { header("Location: index.php"); exit(); }

$isLocal   = ($_SERVER['SERVER_NAME'] === 'localhost');
$uploadDir = $isLocal
    ? $_SERVER['DOCUMENT_ROOT'] . '/supergifts/images/testimonials/'
    : $_SERVER['DOCUMENT_ROOT'] . '/images/testimonials/';
$uploadUrl = 'images/testimonials/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

$headline        = $row['headline'];
$content         = $row['content'];
$client_name     = $row['client_name'];
$client_location = $row['client_location'];
$sequence        = intval($row['sequence']);
$status          = intval($row['status']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $headline        = trim($_POST['headline'] ?? '');
    $content         = trim($_POST['content'] ?? '');
    $client_name     = trim($_POST['client_name'] ?? '');
    $client_location = trim($_POST['client_location'] ?? '');
    $sequence        = max(0, intval($_POST['sequence'] ?? 0));
    $status          = intval($_POST['status'] ?? 1);
    $avatar          = $row['avatar'];
    $image           = $row['image'];

    if (!$headline)    $errors[] = "Headline is required.";
    if (!$content)     $errors[] = "Testimonial content is required.";
    if (!$client_name) $errors[] = "Client name is required.";

    foreach (['avatar' => ['maxMB' => 2, 'width' => 300], 'image' => ['maxMB' => 5, 'width' => 1200]] as $field => $opt) {
        if (!$errors && !empty($_FILES[$field]['name'])) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $errors[] = ucfirst($field) . " must be JPG, PNG or WEBP.";
            } elseif ($_FILES[$field]['size'] > $opt['maxMB'] * 1024 * 1024) {
                $errors[] = ucfirst($field) . " must be under {$opt['maxMB']}MB.";
            } else {
                $old = $$field;
                $filename = 'testimonial-' . $field . '-' . time() . '-' . uniqid() . '.' . $ext;
                $dest = $uploadDir . $filename;
                if (move_uploaded_file($_FILES[$field]['tmp_name'], $dest)) {
                    compressUploadedImage($dest, $opt['width']);
                    $$field = $uploadUrl . $filename;
                    if (!empty($old)) {
                        $oldPath = $isLocal
                            ? $_SERVER['DOCUMENT_ROOT'] . '/supergifts/' . $old
                            : $_SERVER['DOCUMENT_ROOT'] . '/' . $old;
                        if (file_exists($oldPath)) @unlink($oldPath);
                    }
                } else {
                    $errors[] = "Failed to upload $field. Check folder permissions on images/testimonials/.";
                }
            }
        }
    }

    if (!$errors) {
        $stmt = $conn->prepare("UPDATE client_testimonials SET headline=?, content=?, client_name=?, client_location=?, avatar=?, image=?, sequence=?, status=? WHERE id=?");
        $stmt->bind_param("ssssssiii", $headline, $content, $client_name, $client_location, $avatar, $image, $sequence, $status, $id);
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
    <h5 class="mb-0 fw-bold">Edit Testimonial</h5>
</div>

<?php if ($errors): ?>
    <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="form-card mb-4">
                <div class="mb-3">
                    <label class="form-label">Headline <span class="text-danger">*</span></label>
                    <input type="text" name="headline" class="form-control" value="<?= htmlspecialchars($headline) ?>" placeholder="SGIPL is trusted by **1,000+** customers.">
                    <div class="text-muted small mt-1">Wrap the part you want bold + underlined in double asterisks, e.g. <code>**1,000+**</code>.</div>
                </div>
                <div class="mb-0">
                    <label class="form-label">Full Testimonial Content <span class="text-danger">*</span></label>
                    <textarea name="content" class="form-control" rows="10" placeholder="Write each paragraph on its own line..."><?= htmlspecialchars($content) ?></textarea>
                    <div class="text-muted small mt-1">Start a new line for each paragraph — every line becomes its own paragraph on the page.</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-card mb-4">
                <h6 class="fw-bold mb-3">Client Details</h6>
                <div class="mb-3">
                    <label class="form-label">Client Name <span class="text-danger">*</span></label>
                    <input type="text" name="client_name" class="form-control" value="<?= htmlspecialchars($client_name) ?>" placeholder="Dr Reshma Jhaveri">
                </div>
                <div class="mb-3">
                    <label class="form-label">Location</label>
                    <input type="text" name="client_location" class="form-control" value="<?= htmlspecialchars($client_location) ?>" placeholder="Mumbai">
                </div>
                <div class="mb-0">
                    <label class="form-label">Avatar Photo <small class="text-muted">(optional)</small></label>
                    <?php if (!empty($row['avatar'])): ?>
                    <div class="mb-2"><img src="../../<?= htmlspecialchars($row['avatar']) ?>" style="width:56px;height:56px;object-fit:cover;border-radius:50%;border:1px solid #e9ecef;"></div>
                    <?php endif; ?>
                    <input type="file" name="avatar" class="form-control" accept="image/jpeg,image/png,image/webp" onchange="previewImage(this, 'img-preview-avatar', 'preview-box-avatar')">
                    <div id="preview-box-avatar" class="mt-2" style="display:none;">
                        <img id="img-preview-avatar" src="" style="width:56px;height:56px;object-fit:cover;border-radius:50%;border:1px solid #e9ecef;">
                    </div>
                    <div class="text-muted small mt-1">Upload a new file to replace it. Leave empty to keep the current one.</div>
                </div>
            </div>

            <div class="form-card mb-4">
                <h6 class="fw-bold mb-3">Gift / Product Image</h6>
                <?php if (!empty($row['image'])): ?>
                <div class="mb-2"><img src="../../<?= htmlspecialchars($row['image']) ?>" style="width:100%;max-height:180px;object-fit:cover;border-radius:8px;border:1px solid #e9ecef;"></div>
                <?php endif; ?>
                <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp" onchange="previewImage(this, 'img-preview-main', 'preview-box-main')">
                <div id="preview-box-main" class="mt-2" style="display:none;">
                    <img id="img-preview-main" src="" style="width:100%;max-height:180px;object-fit:cover;border-radius:8px;border:1px solid #e9ecef;">
                </div>
                <div class="text-muted small mt-2">Upload a new file to replace it. Leave empty to keep the current one.</div>
            </div>

            <div class="form-card">
                <h6 class="fw-bold mb-3">Publish Settings</h6>
                <div class="mb-3">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="sequence" class="form-control" value="<?= intval($sequence) ?>" min="0">
                    <div class="text-muted small mt-1">Lower numbers show first when there's more than one testimonial.</div>
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
