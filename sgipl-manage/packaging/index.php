<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../login.php"); exit(); }
require_once '../includes/db.php';
$pageTitle = 'Packaging & Dispatch Showcase';

$items = $conn->query("SELECT * FROM packaging_videos ORDER BY sequence ASC, id ASC");

$total  = $conn->query("SELECT COUNT(*) as c FROM packaging_videos")->fetch_assoc()['c'];
$active = $conn->query("SELECT COUNT(*) as c FROM packaging_videos WHERE status=1")->fetch_assoc()['c'];

$isLocal = ($_SERVER['SERVER_NAME'] === 'localhost');
$mediaBase = $isLocal ? '/supergifts/' : '/';

$deleted = isset($_GET['deleted']) ? "Video deleted successfully." : '';
require_once '../includes/layout_top.php';
?>

<?php if ($deleted): ?>
<div class="alert alert-success py-2 small"><i class="bi bi-check-circle me-1"></i><?= $deleted ?></div>
<?php endif; ?>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="icon" style="background:#e0f2fe;color:#0369a1;"><i class="bi bi-camera-reels"></i></div>
                <div><div class="count"><?= $total ?></div><div class="label">Total Videos</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="icon" style="background:#d1fae5;color:#059669;"><i class="bi bi-eye"></i></div>
                <div><div class="count"><?= $active ?></div><div class="label">Active on Services Page</div></div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <p class="text-muted small mb-0">Videos shown here appear in the "Packaging &amp; Dispatch Showcase" section on the Services page, between "Why Choose Super Gifting?" and "Our Simple Process". They autoplay on loop.</p>
    <a href="add.php" class="btn btn-gold btn-sm px-3"><i class="bi bi-plus-lg me-1"></i>Add Video</a>
</div>

<!-- Table -->
<div class="data-table">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th style="width:60px;">Seq</th>
                <th style="width:100px;">Preview</th>
                <th>Caption</th>
                <th>Status</th>
                <th style="width:120px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($items->num_rows === 0): ?>
                <tr><td colspan="5" class="text-center text-muted py-4">
                    No packaging videos yet. <a href="add.php">Add first video →</a>
                </td></tr>
            <?php endif; ?>
            <?php while ($row = $items->fetch_assoc()):
                $videoUrl = $row['video'] ? $mediaBase . htmlspecialchars($row['video']) : '';
                $posterUrl = $row['thumbnail'] ? $mediaBase . htmlspecialchars($row['thumbnail']) : '';
            ?>
            <tr>
                <td class="fw-bold text-muted"><?= $row['sequence'] ?></td>
                <td>
                    <?php if ($videoUrl): ?>
                        <video src="<?= $videoUrl ?>" <?= $posterUrl ? 'poster="'.$posterUrl.'"' : '' ?>
                               style="width:72px;height:56px;object-fit:cover;border-radius:6px;border:1px solid #eee;" muted></video>
                    <?php else: ?>
                        <div style="width:72px;height:56px;background:#f5f5f5;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-camera-video text-muted"></i>
                        </div>
                    <?php endif; ?>
                </td>
                <td class="fw-semibold"><?= htmlspecialchars($row['caption'] ?: '—') ?></td>
                <td>
                    <?= $row['status']==1
                        ? '<span class="badge-published">Active</span>'
                        : '<span class="badge-draft">Hidden</span>' ?>
                </td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Delete this video?')"><i class="bi bi-trash"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/layout_bottom.php'; ?>
