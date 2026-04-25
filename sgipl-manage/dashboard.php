<?php
// define('BASE_URL', '../');
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

$pageTitle = 'Dashboard';

// Stats
$total     = $conn->query("SELECT COUNT(*) as c FROM blogs")->fetch_assoc()['c'];
$published = $conn->query("SELECT COUNT(*) as c FROM blogs WHERE status='published'")->fetch_assoc()['c'];
$drafts    = $conn->query("SELECT COUNT(*) as c FROM blogs WHERE status='draft'")->fetch_assoc()['c'];
$recent    = $conn->query("SELECT * FROM blogs ORDER BY created_at DESC LIMIT 5");

require_once 'includes/layout_top.php';
?>

<!-- Stats Row -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="icon" style="background:#ede9fe;color:#7c3aed;"><i class="bi bi-journals"></i></div>
                <div>
                    <div class="count"><?= $total ?></div>
                    <div class="label">Total Blog Posts</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="icon" style="background:#d1fae5;color:#059669;"><i class="bi bi-check-circle"></i></div>
                <div>
                    <div class="count"><?= $published ?></div>
                    <div class="label">Published</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="icon" style="background:#fef3c7;color:#d97706;"><i class="bi bi-pencil-square"></i></div>
                <div>
                    <div class="count"><?= $drafts ?></div>
                    <div class="label">Drafts</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="d-flex gap-3 mb-4">
    <a href="blogs/add.php" class="btn btn-gold px-4"><i class="bi bi-plus-lg me-2"></i>New Blog Post</a>
    <a href="blogs/index.php" class="btn btn-outline-secondary px-4"><i class="bi bi-list-ul me-2"></i>All Posts</a>
</div>

<!-- Recent Posts -->
<div class="data-table">
    <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-bold">Recent Blog Posts</h6>
        <a href="blogs/index.php" class="btn btn-sm btn-outline-secondary">View All</a>
    </div>
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $recent->fetch_assoc()): ?>
            <tr>
                <td class="fw-semibold" style="max-width:280px;">
                    <div class="text-truncate"><?= htmlspecialchars($row['title']) ?></div>
                </td>
                <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($row['category']) ?></span></td>
                <td>
                    <?php if ($row['status'] === 'published'): ?>
                        <span class="badge-published">Published</span>
                    <?php else: ?>
                        <span class="badge-draft">Draft</span>
                    <?php endif; ?>
                </td>
                <td class="text-muted small"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                <td>
                    <a href="blogs/edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                    <a href="blogs/delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this post?')"><i class="bi bi-trash"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/layout_bottom.php'; ?>
