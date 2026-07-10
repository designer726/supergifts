<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../login.php"); exit(); }
require_once '../includes/db.php';
$pageTitle = 'Gift Vouchers';

$sql = "SELECT * FROM vouchers ORDER BY seqence ASC, id ASC";
$vouchers = $conn->query($sql);

$total  = $conn->query("SELECT COUNT(*) as c FROM vouchers")->fetch_assoc()['c'];
$active = $conn->query("SELECT COUNT(*) as c FROM vouchers WHERE status=1")->fetch_assoc()['c'];

$isLocal = ($_SERVER['SERVER_NAME'] === 'localhost');
$imgBase = $isLocal ? '/supergifts/' : '/';

$deleted = isset($_GET['deleted']) ? "Voucher deleted successfully." : '';
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
                <div class="icon" style="background:#e0f2fe;color:#0369a1;"><i class="bi bi-gift"></i></div>
                <div><div class="count"><?= $total ?></div><div class="label">Total Vouchers</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="icon" style="background:#d1fae5;color:#059669;"><i class="bi bi-eye"></i></div>
                <div><div class="count"><?= $active ?></div><div class="label">Active on Homepage</div></div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <p class="text-muted small mb-0">Vouchers shown here appear in the "Available Gift Vouchers" section on the homepage (up to 8, ordered by display order).</p>
    <a href="add.php" class="btn btn-gold btn-sm px-3"><i class="bi bi-plus-lg me-1"></i>Add Voucher</a>
</div>

<!-- Table -->
<div class="data-table">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th style="width:60px;">Seq</th>
                <th style="width:100px;">Image</th>
                <th>Title</th>
                <th>Status</th>
                <th style="width:120px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($vouchers->num_rows === 0): ?>
                <tr><td colspan="5" class="text-center text-muted py-4">No vouchers found.</td></tr>
            <?php endif; ?>
            <?php while ($row = $vouchers->fetch_assoc()): ?>
            <tr>
                <td class="fw-bold text-muted"><?= $row['seqence'] ?></td>
                <td>
                    <?php if ($row['image']): ?>
                    <img src="<?= $imgBase . htmlspecialchars($row['image']) ?>"
                         style="height:40px;max-width:80px;object-fit:cover;border-radius:4px;border:1px solid #eee;background:#fff;"
                         onerror="this.src='https://via.placeholder.com/80x40?text=Voucher'">
                    <?php else: ?>
                        <span class="text-muted small">No image</span>
                    <?php endif; ?>
                </td>
                <td class="fw-semibold"><?= htmlspecialchars($row['title']) ?></td>
                <td>
                    <?php if ($row['status'] == 1): ?>
                        <span style="background:#d1fae5;color:#059669;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:600;">Active</span>
                    <?php else: ?>
                        <span style="background:#fee2e2;color:#b91c1c;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:600;">Hidden</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit"><i class="bi bi-pencil"></i></a>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" title="Delete"
                       onclick="return confirm('Delete voucher &quot;<?= htmlspecialchars($row['title']) ?>&quot;?')">
                        <i class="bi bi-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/layout_bottom.php'; ?>
