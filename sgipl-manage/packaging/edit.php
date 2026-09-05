<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../login.php"); exit(); }
require_once '../includes/db.php';
$pageTitle = 'Edit Packaging Video';
$errors = []; $success = '';

$id = intval($_GET['id'] ?? 0);
if (!$id) { header("Location: index.php"); exit(); }

$stmt = $conn->prepare("SELECT * FROM packaging_videos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$item) { header("Location: index.php"); exit(); }

$videoUploadDir = ($_SERVER['SERVER_NAME'] === 'localhost')
    ? $_SERVER['DOCUMENT_ROOT'] . '/supergifts/images/packaging/'
    : $_SERVER['DOCUMENT_ROOT'] . '/images/packaging/';
if (!is_dir($videoUploadDir)) mkdir($videoUploadDir, 0755, true);

function packagingDeleteOld($relPath) {
    if (!$relPath) return;
    $old = ($_SERVER['SERVER_NAME']==='localhost')
        ? $_SERVER['DOCUMENT_ROOT'].'/supergifts/'.$relPath
        : $_SERVER['DOCUMENT_ROOT'].'/'.$relPath;
    if (file_exists($old)) @unlink($old);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $caption  = trim($_POST['caption'] ?? '');
    $sequence = intval($_POST['sequence'] ?? 0);
    $status   = intval($_POST['status'] ?? 1);
    $video     = $item['video'];
    $thumbnail = $item['thumbnail'];

    if (!empty($_FILES['video']['name'])) {
        $vidExt = ['mp4','webm','mov'];
        $ext = strtolower(pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $vidExt)) {
            $errors[] = "Only MP4, WEBM, MOV video files allowed.";
        } elseif ($_FILES['video']['size'] > 30 * 1024 * 1024) {
            $errors[] = "Video must be under 30 MB.";
        } else {
            $filename = 'packaging-'.time().'-'.uniqid().'.'.$ext;
            if (move_uploaded_file($_FILES['video']['tmp_name'], $videoUploadDir.$filename)) {
                packagingDeleteOld($item['video']);
                $video = 'images/packaging/'.$filename;
            } else {
                $errors[] = "Failed to upload video. Check images/packaging/ permissions.";
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
                packagingDeleteOld($item['thumbnail']);
                $thumbnail = 'images/packaging/'.$filename;
            } else {
                $errors[] = "Failed to upload poster image.";
            }
        }
    }

    if (!$errors) {
        $stmt = $conn->prepare("UPDATE packaging_videos SET caption=?, video=?, thumbnail=?, sequence=?, status=? WHERE id=?");
        $stmt->bind_param("sssiii", $caption, $video, $thumbnail, $sequence, $status, $id);
        if ($stmt->execute()) {
            $success = "Packaging video updated!";
            $item = array_merge($item, compact('caption','video','thumbnail','sequence','status'));
        } else {
            $errors[] = "DB error: ".$conn->error;
        }
        $stmt->close();
    }
}

$isLocal = ($_SERVER['SERVER_NAME'] === 'localhost');
$videoUrl = $item['video'] ? (($isLocal ? '/supergifts/' : '/') . $item['video']) : '';
$thumbUrl = $item['thumbnail'] ? (($isLocal ? '/supergifts/' : '/') . $item['thumbnail']) : '';

require_once '../includes/layout_top.php';
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Edit Packaging &amp; Dispatch Video</h5>
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
                    <label class="form-label">Video File <small class="text-muted">(MP4, WEBM, MOV — max 30 MB)</small></label>
                    <?php if ($videoUrl): ?>
                        <video id="video-preview" src="<?= htmlspecialchars($videoUrl) ?>"
                               style="max-height:220px;max-width:100%;border-radius:8px;border:1px solid #eee;display:block;margin-bottom:10px;" controls muted></video>
                    <?php else: ?>
                        <video id="video-preview" style="max-height:220px;display:none;margin-bottom:10px;" controls muted></video>
                    <?php endif; ?>
                    <input type="file" name="video" class="form-control" accept="video/mp4,video/webm,video/quicktime"
                           onchange="previewVideo(this)">
                    <div class="text-muted small mt-1">Leave empty to keep the current video.</div>
                </div>
                <div class="mb-0">
                    <label class="form-label">Caption <small class="text-muted">(optional)</small></label>
                    <input type="text" name="caption" class="form-control"
                           value="<?= htmlspecialchars($item['caption']) ?>" placeholder="e.g. Premium Gift Wrapping">
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-card mb-4">
                <h6 class="fw-bold mb-3">Settings</h6>
                <div class="mb-3">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="sequence" class="form-control" value="<?= $item['sequence'] ?>" min="0">
                </div>
                <div class="mb-0">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="1" <?= $item['status']==1?'selected':'' ?>>✅ Active</option>
                        <option value="0" <?= $item['status']==0?'selected':'' ?>>🚫 Hidden</option>
                    </select>
                </div>
            </div>
            <div class="form-card">
                <h6 class="fw-bold mb-3">Poster Image <small class="text-muted fw-normal">(optional)</small></h6>
                <?php if ($thumbUrl): ?>
                    <img id="thumb-preview" src="<?= htmlspecialchars($thumbUrl) ?>"
                         style="max-height:150px;max-width:100%;object-fit:contain;border:1px solid #eee;border-radius:8px;padding:6px;margin-bottom:10px;"
                         onerror="this.style.display='none'">
                <?php else: ?>
                    <img id="thumb-preview" src="" style="max-height:150px;display:none;margin-bottom:10px;">
                <?php endif; ?>
                <input type="file" name="thumbnail" class="form-control" accept="image/jpeg,image/png,image/webp"
                       onchange="previewThumb(this)">
                <div class="text-muted small mt-1">Leave empty to keep the current poster.</div>
            </div>
        </div>
    </div>
    <div class="mt-4 d-flex gap-3">
        <button type="submit" class="btn btn-gold px-5"><i class="bi bi-save me-2"></i>Save Changes</button>
        <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
<script>
function previewVideo(input) {
    var vid = document.getElementById('video-preview');
    if (input.files && input.files[0]) {
        vid.src = URL.createObjectURL(input.files[0]);
        vid.style.display = 'block';
    }
}
function previewThumb(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('thumb-preview');
            img.src = e.target.result; img.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?php require_once '../includes/layout_bottom.php'; ?>
