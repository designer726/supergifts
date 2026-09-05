<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../login.php"); exit(); }
require_once '../includes/db.php';
$pageTitle = 'Add Packaging Video';
$errors = []; $success = '';

$videoUploadDir = ($_SERVER['SERVER_NAME'] === 'localhost')
    ? $_SERVER['DOCUMENT_ROOT'] . '/supergifts/images/packaging/'
    : $_SERVER['DOCUMENT_ROOT'] . '/images/packaging/';
if (!is_dir($videoUploadDir)) mkdir($videoUploadDir, 0755, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $caption  = trim($_POST['caption'] ?? '');
    $sequence = intval($_POST['sequence'] ?? 0);
    $status   = intval($_POST['status'] ?? 1);
    $video     = '';
    $thumbnail = '';

    if (empty($_FILES['video']['name'])) {
        $errors[] = "A video file is required.";
    } else {
        $vidExt = ['mp4','webm','mov'];
        $ext = strtolower(pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $vidExt)) {
            $errors[] = "Only MP4, WEBM, MOV video files allowed.";
        } elseif ($_FILES['video']['size'] > 30 * 1024 * 1024) {
            $errors[] = "Video must be under 30 MB.";
        } else {
            $filename = 'packaging-'.time().'-'.uniqid().'.'.$ext;
            if (move_uploaded_file($_FILES['video']['tmp_name'], $videoUploadDir.$filename)) {
                $video = 'images/packaging/'.$filename;
            } else {
                $errors[] = "Failed to upload video. Check images/packaging/ folder permissions.";
            }
        }
    }

    if (!$errors && !empty($_FILES['thumbnail']['name'])) {
        $imgExt = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $imgExt)) {
            $errors[] = "Poster image must be JPG, PNG or WEBP.";
        } elseif ($_FILES['thumbnail']['size'] > 3 * 1024 * 1024) {
            $errors[] = "Poster image must be under 3MB.";
        } else {
            $filename = 'packaging-poster-'.time().'-'.uniqid().'.'.$ext;
            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $videoUploadDir.$filename)) {
                $thumbnail = 'images/packaging/'.$filename;
            } else {
                $errors[] = "Failed to upload poster image.";
            }
        }
    }

    if (!$errors) {
        $stmt = $conn->prepare("INSERT INTO packaging_videos (caption, video, thumbnail, sequence, status) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssii", $caption, $video, $thumbnail, $sequence, $status);
        if ($stmt->execute()) {
            $success = "Packaging video added! <a href='index.php'>View all →</a>";
            $caption = ''; $sequence = 0;
        } else {
            $errors[] = "DB error: ".$conn->error;
        }
        $stmt->close();
    }
}

require_once '../includes/layout_top.php';
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Add Packaging &amp; Dispatch Video</h5>
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
                    <label class="form-label">Video File <span class="text-danger">*</span> <small class="text-muted">(MP4, WEBM, MOV — max 30 MB)</small></label>
                    <input type="file" name="video" class="form-control" accept="video/mp4,video/webm,video/quicktime"
                           onchange="previewVideo(this)" required>
                    <div id="video-preview-box" style="display:none;margin-top:10px;">
                        <video id="video-preview" style="max-height:220px;max-width:100%;border-radius:8px;border:1px solid #eee;" controls muted></video>
                    </div>
                </div>
                <div class="mb-0">
                    <label class="form-label">Caption <small class="text-muted">(optional)</small></label>
                    <input type="text" name="caption" class="form-control"
                           value="<?= htmlspecialchars($caption ?? '') ?>" placeholder="e.g. Premium Gift Wrapping">
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-card mb-4">
                <h6 class="fw-bold mb-3">Settings</h6>
                <div class="mb-3">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="sequence" class="form-control"
                           value="<?= $sequence ?? 0 ?>" min="0">
                    <div class="text-muted small mt-1">Lower = shown first.</div>
                </div>
                <div class="mb-0">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="1">✅ Active</option>
                        <option value="0">🚫 Hidden</option>
                    </select>
                </div>
            </div>

            <div class="form-card">
                <h6 class="fw-bold mb-3">Poster Image <small class="text-muted fw-normal">(optional)</small></h6>
                <div id="thumb-preview-box" style="display:none;margin-bottom:10px;text-align:center;">
                    <img id="thumb-preview" src=""
                         style="max-height:150px;max-width:100%;object-fit:contain;border:1px solid #eee;border-radius:8px;padding:6px;">
                </div>
                <input type="file" name="thumbnail" class="form-control"
                       accept="image/jpeg,image/png,image/webp" onchange="previewThumb(this)">
                <div class="text-muted small mt-1">Shown while the video loads. Max 3MB.</div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-3">
        <button type="submit" class="btn btn-gold px-5"><i class="bi bi-save me-2"></i>Save Video</button>
        <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

<script>
function previewVideo(input) {
    var box = document.getElementById('video-preview-box');
    var vid = document.getElementById('video-preview');
    if (input.files && input.files[0]) {
        vid.src = URL.createObjectURL(input.files[0]);
        box.style.display = 'block';
    } else {
        box.style.display = 'none';
    }
}
function previewThumb(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('thumb-preview').src = e.target.result;
            document.getElementById('thumb-preview-box').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once '../includes/layout_bottom.php'; ?>
