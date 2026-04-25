<?php
// define('BASE_URL', '../../');
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

$pageTitle = 'Blog Posts';

// Search/filter
$search = trim($_GET['search'] ?? '');
$filter = $_GET['status'] ?? '';

$where = [];
$params = [];
$types = '';

if ($search) {
    $where[] = "(title LIKE ? OR category LIKE ?)";
    $like = "%$search%";
    $params[] = $like; $params[] = $like;
    $types .= 'ss';
}
if ($filter && in_array($filter, ['published', 'draft'])) {
    $where[] = "status = ?";
    $params[] = $filter;
    $types .= 's';
}

$sql = "SELECT * FROM blogs" . ($where ? " WHERE " . implode(" AND ", $where) : "") . " ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$blogs = $stmt->get_result();

require_once '../includes/layout_top.php';
?>

<!-- Toolbar -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <form method="GET" class="d-flex gap-2 flex-wrap">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search posts..." value="<?= htmlspecialchars($search) ?>" style="width:220px;">
        <select name="status" class="form-select form-select-sm" style="width:140px;">
            <option value="">All Status</option>
            <option value="published" <?= $filter==='published'?'selected':'' ?>>Published</option>
            <option value="draft"     <?= $filter==='draft'?'selected':'' ?>>Draft</option>
        </select>
        <button class="btn btn-sm btn-secondary" type="submit"><i class="bi bi-search"></i></button>
        <?php if ($search || $filter): ?>
            <a href="index.php" class="btn btn-sm btn-outline-secondary">Clear</a>
        <?php endif; ?>
    </form>
    <a href="add.php" class="btn btn-gold btn-sm px-3"><i class="bi bi-plus-lg me-1"></i>New Post</a>
</div>

<!-- Table -->
<div class="data-table">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th style="width:50px;">#</th>
                <th style="width:70px;">Image</th>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Date</th>
                <th style="width:110px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($blogs->num_rows === 0): ?>
            <tr><td colspan="7" class="text-center text-muted py-4">No blog posts found.</td></tr>
            <?php endif; ?>
            <?php $i = 1; while($row = $blogs->fetch_assoc()): ?>
            <tr>
                <td class="text-muted small"><?= $i++ ?></td>
                <td>
                    <?php if($row['image']): ?>
                        <img src="https://www.supergifts.in/<?= htmlspecialchars($row['image']) ?>" style="width:56px;height:40px;object-fit:cover;border-radius:6px;" onerror="this.src='https://via.placeholder.com/56x40?text=IMG'">
                    <?php else: ?>
                        <div style="width:56px;height:40px;background:#f0f0f0;border-radius:6px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-image text-muted"></i></div>
                    <?php endif; ?>
                </td>
                <td style="max-width:280px;">
                    <div class="fw-semibold text-truncate"><?= htmlspecialchars($row['title']) ?></div>
                    <div class="text-muted small">/blog_details?BlogDetails=<?= htmlspecialchars($row['slug']) ?></div>
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
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit"><i class="bi bi-pencil"></i></a>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this post?')"><i class="bi bi-trash"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/layout_bottom.php'; ?>
