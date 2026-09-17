<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

$pageTitle = 'Employee Reviews';

$deleted = isset($_GET['deleted']);
$saved   = isset($_GET['saved']);

$rows = $conn->query("SELECT * FROM employee_testimonials ORDER BY sequence ASC, id DESC");

require_once '../includes/layout_top.php';
?>

<style>
.tm-thumb { width:56px; height:56px; border-radius:50%; object-fit:cover; border:1px solid #e9ecef; background:#f8f9fa; }
</style>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-bold mb-1">Employee Reviews</h5>
        <p class="text-muted small mb-0">Manage the "What Our People Say" carousel on the Careers page.</p>
    </div>
    <a href="add.php" class="btn btn-gold btn-sm"><i class="bi bi-plus-circle me-1"></i>Add Review</a>
</div>

<?php if ($deleted): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>Review deleted.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if ($saved): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>Review saved.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="data-table">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th style="width:70px;">Photo</th>
                <th>Employee</th>
                <th>Quote</th>
                <th style="width:80px;">Order</th>
                <th style="width:100px;">Status</th>
                <th style="width:110px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($rows->num_rows === 0): ?>
            <tr><td colspan="6" class="text-center text-muted py-4">No employee reviews yet. Click "Add Review" to create the first one — until then the Careers page shows its built-in default review.</td></tr>
            <?php endif; ?>
            <?php while ($row = $rows->fetch_assoc()): ?>
            <tr>
                <td>
                    <?php if (!empty($row['photo'])): ?>
                    <img src="../../<?= htmlspecialchars($row['photo']) ?>" class="tm-thumb" alt="">
                    <?php else: ?>
                    <div class="tm-thumb d-flex align-items-center justify-content-center text-muted"><i class="bi bi-person"></i></div>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="fw-semibold"><?= htmlspecialchars($row['employee_name']) ?></div>
                    <div class="text-muted small"><?= htmlspecialchars($row['role']) ?></div>
                </td>
                <td style="max-width:320px;">
                    <div class="text-truncate" title="<?= htmlspecialchars($row['quote']) ?>"><?= htmlspecialchars($row['quote']) ?></div>
                </td>
                <td class="text-muted small"><?= intval($row['sequence']) ?></td>
                <td>
                    <?php if (intval($row['status']) === 1): ?>
                    <span class="badge bg-success">Active</span>
                    <?php else: ?>
                    <span class="badge bg-secondary">Hidden</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit"><i class="bi bi-pencil"></i></a>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete this review?')"><i class="bi bi-trash"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/layout_bottom.php'; ?>
