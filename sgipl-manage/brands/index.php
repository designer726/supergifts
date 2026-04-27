<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../login.php"); exit(); }
require_once '../includes/db.php';
$pageTitle = 'Brand Partners';

$search = trim($_GET['search'] ?? '');
$filter = $_GET['flag'] ?? '';

$where = []; $params = []; $types = '';
if ($search) { $where[] = "brandname LIKE ?"; $params[] = "%$search%"; $types .= 's'; }
if ($filter !== '' && in_array($filter, ['0','1'])) { $where[] = "flag = ?"; $params[] = intval($filter); $types .= 'i'; }

$sql = "SELECT * FROM brandlogo" . ($where ? " WHERE ".implode(" AND ",$where) : "") . " ORDER BY flag DESC, seqence ASC";
$stmt = $conn->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$brands = $stmt->get_result();

$total      = $conn->query("SELECT COUNT(*) as c FROM brandlogo")->fetch_assoc()['c'];
$authorised = $conn->query("SELECT COUNT(*) as c FROM brandlogo WHERE flag=1")->fetch_assoc()['c'];
$alsodeal   = $conn->query("SELECT COUNT(*) as c FROM brandlogo WHERE flag=0")->fetch_assoc()['c'];

$deleted = isset($_GET['deleted']) ? "Brand deleted successfully." : '';
require_once '../includes/layout_top.php';
?>

<?php if ($deleted): ?>
<div class="alert alert-success py-2 small"><i class="bi bi-check-circle me-1"></i><?= $deleted ?></div>
<?php endif; ?>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="icon" style="background:#e0f2fe;color:#0369a1;"><i class="bi bi-building"></i></div>
                <div><div class="count"><?= $total ?></div><div class="label">Total Brands</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="icon" style="background:#d1fae5;color:#059669;"><i class="bi bi-patch-check"></i></div>
                <div><div class="count"><?= $authorised ?></div><div class="label">Authorised Partners</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="icon" style="background:#fef3c7;color:#d97706;"><i class="bi bi-shop"></i></div>
                <div><div class="count"><?= $alsodeal ?></div><div class="label">Also Deal Brands</div></div>
            </div>
        </div>
    </div>
</div>

<!-- Toolbar -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <form method="GET" class="d-flex gap-2 flex-wrap">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search brands..." value="<?= htmlspecialchars($search) ?>" style="width:200px;">
        <select name="flag" class="form-select form-select-sm" style="width:170px;">
            <option value="">All Types</option>
            <option value="1" <?= $filter==='1'?'selected':'' ?>>Authorised Partners</option>
            <option value="0" <?= $filter==='0'?'selected':'' ?>>Also Deal Brands</option>
        </select>
        <button class="btn btn-sm btn-secondary" type="submit"><i class="bi bi-search"></i></button>
        <?php if ($search || $filter !== ''): ?>
            <a href="index.php" class="btn btn-sm btn-outline-secondary">Clear</a>
        <?php endif; ?>
    </form>
    <a href="add.php" class="btn btn-gold btn-sm px-3"><i class="bi bi-plus-lg me-1"></i>Add Brand</a>
</div>

<!-- Table -->
<div class="data-table">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th style="width:60px;">Seq</th>
                <th style="width:90px;">Logo</th>
                <th>Brand Name</th>
                <th>Type</th>
                <th>Catalog Link</th>
                <th>Products</th>
                <th style="width:120px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($brands->num_rows === 0): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">No brands found.</td></tr>
            <?php endif; ?>
            <?php while ($row = $brands->fetch_assoc()):
                $prodCount = $conn->query("SELECT COUNT(*) as c FROM products WHERE brand_id={$row['id']}")->fetch_assoc()['c'];
                $logoPath = "images/brandlogo/image" . $row['imageno'] . ".jpg";
                // Build correct preview URL
                $logoUrl = "/supergifts/" . $logoPath;
            ?>
            <tr>
                <td class="fw-bold text-muted"><?= $row['seqence'] ?></td>
                <td>
                    <img src="<?= $logoUrl ?>"
                         style="height:40px;max-width:80px;object-fit:contain;border-radius:4px;border:1px solid #eee;padding:2px;background:#fff;"
                         onerror="this.src='https://via.placeholder.com/80x40?text=Logo'">
                </td>
                <td class="fw-semibold text-capitalize"><?= htmlspecialchars($row['brandname']) ?></td>
                <td>
                    <?php if ($row['flag'] == 1): ?>
                        <span style="background:#dbeafe;color:#1d4ed8;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:600;">⭐ Authorised</span>
                    <?php else: ?>
                        <span style="background:#fef9c3;color:#854d0e;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:600;">🤝 Also Deal</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($row['links']): ?>
                        <a href="<?= htmlspecialchars($row['links']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary py-0 px-2">
                            <i class="bi bi-link-45deg me-1"></i>View
                        </a>
                    <?php else: ?>
                        <span class="text-muted small">—</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="../products/index.php?brand_id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary py-0">
                        <i class="bi bi-box-seam me-1"></i><?= $prodCount ?>
                    </a>
                </td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit"><i class="bi bi-pencil"></i></a>
                    <a href="../products/add.php?brand_id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-success me-1" title="Add Product"><i class="bi bi-plus-circle"></i></a>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" title="Delete"
                       onclick="return confirm('Delete <?= htmlspecialchars($row['brandname']) ?>? All its products will also be deleted!')">
                        <i class="bi bi-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/layout_bottom.php'; ?>
