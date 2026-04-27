<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../login.php"); exit(); }
require_once '../includes/db.php';
$pageTitle = 'Products';

$brand_id = intval($_GET['brand_id'] ?? 0);
$search   = trim($_GET['search'] ?? '');

// Load brand if filtered
$brand = null;
if ($brand_id) {
    $bs = $conn->prepare("SELECT * FROM brandlogo WHERE id = ?");
    $bs->bind_param("i", $brand_id);
    $bs->execute();
    $brand = $bs->get_result()->fetch_assoc();
    $bs->close();
}

$where = []; $params = []; $types = '';
if ($brand_id) { $where[] = "p.brand_id = ?"; $params[] = $brand_id; $types .= 'i'; }
if ($search)   { $where[] = "p.name LIKE ?";  $params[] = "%$search%"; $types .= 's'; }

$sql = "SELECT p.*, b.brandname FROM products p
        JOIN brandlogo b ON p.brand_id = b.id"
     . ($where ? " WHERE ".implode(" AND ",$where) : "")
     . " ORDER BY b.brandname ASC, p.sequence ASC";
$stmt = $conn->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$products = $stmt->get_result();

$deleted = isset($_GET['deleted']) ? "Product deleted." : '';
require_once '../includes/layout_top.php';
?>

<?php if ($deleted): ?>
<div class="alert alert-success py-2 small"><?= $deleted ?></div>
<?php endif; ?>

<!-- Header -->
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h5 class="fw-bold mb-0">
            <?= $brand ? 'Products: <span class="text-capitalize">'.htmlspecialchars($brand['brandname']).'</span>' : 'All Products' ?>
        </h5>
        <?php if ($brand): ?>
            <a href="index.php" class="text-muted small"><i class="bi bi-arrow-left me-1"></i>Back to All Products</a>
        <?php endif; ?>
    </div>
    <div class="d-flex gap-2">
        <a href="add.php<?= $brand_id?'?brand_id='.$brand_id:'' ?>" class="btn btn-gold btn-sm px-3">
            <i class="bi bi-plus-lg me-1"></i>Add Product
        </a>
        <a href="bulk_upload.php<?= $brand_id?'?brand_id='.$brand_id:'' ?>" class="btn btn-outline-secondary btn-sm px-3">
            <i class="bi bi-file-earmark-excel me-1"></i>Bulk Upload
        </a>
        <?php if ($brand): ?>
            <a href="../brands/edit.php?id=<?= $brand_id ?>" class="btn btn-outline-primary btn-sm px-3">
                <i class="bi bi-pencil me-1"></i>Edit Brand
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Search -->
<form method="GET" class="d-flex gap-2 mb-4 flex-wrap">
    <?php if ($brand_id): ?><input type="hidden" name="brand_id" value="<?= $brand_id ?>"><?php endif; ?>
    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search products..."
           value="<?= htmlspecialchars($search) ?>" style="width:220px;">
    <button class="btn btn-sm btn-secondary" type="submit"><i class="bi bi-search"></i></button>
    <?php if ($search): ?>
        <a href="index.php<?= $brand_id?'?brand_id='.$brand_id:'' ?>" class="btn btn-sm btn-outline-secondary">Clear</a>
    <?php endif; ?>
</form>

<!-- Table -->
<div class="data-table">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th style="width:50px;">Seq</th>
                <th style="width:70px;">Image</th>
                <th>Product Name</th>
                <?php if (!$brand_id): ?><th>Brand</th><?php endif; ?>
                <th>MRP</th>
                <th>Status</th>
                <th style="width:100px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($products->num_rows === 0): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">
                    No products found.
                    <?php if ($brand): ?>
                        <a href="add.php?brand_id=<?= $brand_id ?>">Add first product →</a>
                    <?php endif; ?>
                </td></tr>
            <?php endif; ?>
            <?php while ($row = $products->fetch_assoc()):
                $imgUrl = $row['image']
                    ? (($_SERVER['SERVER_NAME']==='localhost') ? '/supergifts/'.$row['image'] : '/'.$row['image'])
                    : '';
            ?>
            <tr>
                <td class="text-muted small fw-bold"><?= $row['sequence'] ?></td>
                <td>
                    <?php if ($imgUrl): ?>
                        <img src="<?= htmlspecialchars($imgUrl) ?>"
                             style="width:56px;height:48px;object-fit:cover;border-radius:6px;border:1px solid #eee;"
                             onerror="this.src='https://via.placeholder.com/56x48?text=IMG'">
                    <?php else: ?>
                        <div style="width:56px;height:48px;background:#f5f5f5;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-image text-muted"></i>
                        </div>
                    <?php endif; ?>
                </td>
                <td class="fw-semibold"><?= htmlspecialchars($row['name']) ?></td>
                <?php if (!$brand_id): ?>
                    <td><span class="badge bg-light text-dark border small text-capitalize"><?= htmlspecialchars($row['brandname']) ?></span></td>
                <?php endif; ?>
                <td class="fw-semibold" style="color:#059669;">
                    <?= $row['mrp'] > 0 ? '₹'.number_format($row['mrp'],2) : '<span class="text-muted">—</span>' ?>
                </td>
                <td>
                    <?= $row['status']==1
                        ? '<span class="badge-published">Active</span>'
                        : '<span class="badge-draft">Hidden</span>' ?>
                </td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                    <a href="delete.php?id=<?= $row['id'] ?>&brand_id=<?= $row['brand_id'] ?>"
                       class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Delete this product?')"><i class="bi bi-trash"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/layout_bottom.php'; ?>
