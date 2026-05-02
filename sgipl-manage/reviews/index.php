<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

$pageTitle = 'Client Reviews';

// Search/filter
$search = trim($_GET['search'] ?? '');
$filter = $_GET['status'] ?? '';

$where = [];
$params = [];
$types = '';

if ($search) {
    $where[] = "(client_name LIKE ? OR review_text LIKE ? OR email LIKE ?)";
    $like = "%$search%";
    $params[] = $like; $params[] = $like; $params[] = $like;
    $types .= 'sss';
}
if ($filter && in_array($filter, ['approved', 'pending', 'rejected'])) {
    $where[] = "status = ?";
    $params[] = $filter;
    $types .= 's';
}

$sql = "SELECT * FROM reviews" . ($where ? " WHERE " . implode(" AND ", $where) : "") . " ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$reviews = $stmt->get_result();

require_once '../includes/layout_top.php';
?>

<!-- Toolbar -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <form method="GET" class="d-flex gap-2 flex-wrap">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search reviews..." value="<?= htmlspecialchars($search) ?>" style="width:220px;">
        <select name="status" class="form-select form-select-sm" style="width:140px;">
            <option value="">All Status</option>
            <option value="approved" <?= $filter==='approved'?'selected':'' ?>>Approved</option>
            <option value="pending" <?= $filter==='pending'?'selected':'' ?>>Pending</option>
            <option value="rejected" <?= $filter==='rejected'?'selected':'' ?>>Rejected</option>
        </select>
        <button class="btn btn-sm btn-secondary" type="submit"><i class="bi bi-search"></i></button>
        <?php if ($search || $filter): ?>
            <a href="index.php" class="btn btn-sm btn-outline-secondary">Clear</a>
        <?php endif; ?>
    </form>
</div>

<!-- Stats Row -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card bg-success bg-opacity-10 border border-success p-3 rounded">
            <div class="text-success fw-bold text-end" style="font-size: 24px;">
                <?php 
                $count = $conn->query("SELECT COUNT(*) as cnt FROM reviews WHERE status='approved'")->fetch_assoc()['cnt'];
                echo $count;
                ?>
            </div>
            <div class="text-muted small">Approved Reviews</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card bg-warning bg-opacity-10 border border-warning p-3 rounded">
            <div class="text-warning fw-bold text-end" style="font-size: 24px;">
                <?php 
                $count = $conn->query("SELECT COUNT(*) as cnt FROM reviews WHERE status='pending'")->fetch_assoc()['cnt'];
                echo $count;
                ?>
            </div>
            <div class="text-muted small">Pending Reviews</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card bg-danger bg-opacity-10 border border-danger p-3 rounded">
            <div class="text-danger fw-bold text-end" style="font-size: 24px;">
                <?php 
                $count = $conn->query("SELECT COUNT(*) as cnt FROM reviews WHERE status='rejected'")->fetch_assoc()['cnt'];
                echo $count;
                ?>
            </div>
            <div class="text-muted small">Rejected Reviews</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card bg-info bg-opacity-10 border border-info p-3 rounded">
            <div class="text-info fw-bold text-end" style="font-size: 24px;">
                <?php 
                $count = $conn->query("SELECT COUNT(*) as cnt FROM reviews")->fetch_assoc()['cnt'];
                echo $count;
                ?>
            </div>
            <div class="text-muted small">Total Reviews</div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="data-table">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th style="width:50px;">#</th>
                <th>Client Name</th>
                <th>Company</th>
                <th>Rating</th>
                <th>Review</th>
                <th>Status</th>
                <th>Date</th>
                <th style="width:110px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($reviews->num_rows === 0): ?>
            <tr><td colspan="8" class="text-center text-muted py-4">No reviews found.</td></tr>
            <?php endif; ?>
            <?php $i = 1; while($row = $reviews->fetch_assoc()): ?>
            <tr>
                <td class="text-muted small"><?= $i++ ?></td>
                <td class="fw-semibold"><?= htmlspecialchars($row['client_name']) ?></td>
                <td><small class="text-muted"><?= htmlspecialchars($row['company_name']) ?></small></td>
                <td>
                    <div class="text-warning">
                        <?php for($s=0; $s<$row['rating']; $s++): ?>
                            <i class="bi bi-star-fill"></i>
                        <?php endfor; ?>
                        <?php for($s=$row['rating']; $s<5; $s++): ?>
                            <i class="bi bi-star"></i>
                        <?php endfor; ?>
                    </div>
                </td>
                <td style="max-width:200px;">
                    <div class="text-truncate" title="<?= htmlspecialchars($row['review_text']) ?>">
                        <?= htmlspecialchars(substr($row['review_text'], 0, 50)) . (strlen($row['review_text']) > 50 ? '...' : '') ?>
                    </div>
                </td>
                <td>
                    <?php 
                    $statusClass = [
                        'approved' => 'badge-success',
                        'pending' => 'badge-warning',
                        'rejected' => 'badge-danger'
                    ];
                    $statusText = ucfirst($row['status']);
                    $class = $statusClass[$row['status']] ?? 'badge-secondary';
                    ?>
                    <span class="badge <?= $class ?>"><?= $statusText ?></span>
                </td>
                <td class="text-muted small"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                <td>
                    <a href="view.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-info me-1" title="View"><i class="bi bi-eye"></i></a>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit"><i class="bi bi-pencil"></i></a>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure?')"><i class="bi bi-trash"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/layout_bottom.php'; ?>
