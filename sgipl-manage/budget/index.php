<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../login.php"); exit(); }
require_once '../includes/db.php';
$pageTitle = 'Budget Friendly';

$budgetTiers = [
    'tier1' => '₹10 – ₹200',
    'tier2' => '₹200 – ₹500',
    'tier3' => '₹500 – ₹1000',
    'tier4' => '₹1000 & Above',
];

$search = trim($_GET['search'] ?? '');
$tier   = $_GET['tier'] ?? '';

$where = []; $params = []; $types = '';
if ($search) { $where[] = "name LIKE ?"; $params[] = "%$search%"; $types .= 's'; }
if ($tier !== '' && array_key_exists($tier, $budgetTiers)) { $where[] = "budget_tier = ?"; $params[] = $tier; $types .= 's'; }

$sql = "SELECT * FROM budget_products" . ($where ? " WHERE ".implode(" AND ",$where) : "") . " ORDER BY budget_tier ASC, sequence ASC, id ASC";
$stmt = $conn->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$items = $stmt->get_result();

$total  = $conn->query("SELECT COUNT(*) as c FROM budget_products")->fetch_assoc()['c'];
$active = $conn->query("SELECT COUNT(*) as c FROM budget_products WHERE status=1")->fetch_assoc()['c'];

$isLocal = ($_SERVER['SERVER_NAME'] === 'localhost');
$imgBase = $isLocal ? '/supergifts/' : '/';

$deleted = isset($_GET['deleted']) ? "Item deleted successfully." : '';
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
                <div class="icon" style="background:#e0f2fe;color:#0369a1;"><i class="bi bi-tags"></i></div>
                <div><div class="count"><?= $total ?></div><div class="label">Total Budget Items</div></div>
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
    <p class="text-muted small mb-0">Items shown here appear in the "Made to Order – Budget Friendly" section on the homepage, grouped into their tier's tab (up to 6 per tier). Independent of Brand Partners and the main product catalog.</p>
    <div class="d-flex gap-2">
        <a href="add.php" class="btn btn-gold btn-sm px-3"><i class="bi bi-plus-lg me-1"></i>Add Item</a>
        <a href="bulk_upload.php" class="btn btn-outline-secondary btn-sm px-3"><i class="bi bi-file-earmark-excel me-1"></i>Bulk Upload CSV</a>
    </div>
</div>

<!-- Search -->
<form method="GET" class="d-flex gap-2 mb-4 flex-wrap">
    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search items..."
           value="<?= htmlspecialchars($search) ?>" style="width:220px;">
    <select name="tier" class="form-select form-select-sm" style="width:180px;">
        <option value="">All Tiers</option>
        <?php foreach ($budgetTiers as $tierKey => $tierLabel): ?>
        <option value="<?= $tierKey ?>" <?= $tier===$tierKey?'selected':'' ?>><?= $tierLabel ?></option>
        <?php endforeach; ?>
    </select>
    <button class="btn btn-sm btn-secondary" type="submit"><i class="bi bi-search"></i></button>
    <?php if ($search || $tier !== ''): ?>
        <a href="index.php" class="btn btn-sm btn-outline-secondary">Clear</a>
    <?php endif; ?>
</form>

<!-- Table -->
<div class="data-table">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th style="width:60px;">Seq</th>
                <th style="width:90px;">Image</th>
                <th>Item Name</th>
                <th>Tier</th>
                <th>MRP</th>
                <th>Offer Price</th>
                <th>Qty</th>
                <th>Status</th>
                <th style="width:120px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($items->num_rows === 0): ?>
                <tr><td colspan="9" class="text-center text-muted py-4">
                    No budget items found. <a href="add.php">Add first item →</a>
                </td></tr>
            <?php endif; ?>
            <?php while ($row = $items->fetch_assoc()):
                $imgUrl = $row['image'] ? $imgBase . htmlspecialchars($row['image']) : '';
            ?>
            <tr>
                <td class="fw-bold text-muted"><?= $row['sequence'] ?></td>
                <td>
                    <?php if ($imgUrl): ?>
                        <img src="<?= $imgUrl ?>"
                             style="width:56px;height:48px;object-fit:cover;border-radius:6px;border:1px solid #eee;"
                             onerror="this.src='https://via.placeholder.com/56x48?text=IMG'">
                    <?php else: ?>
                        <div style="width:56px;height:48px;background:#f5f5f5;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-image text-muted"></i>
                        </div>
                    <?php endif; ?>
                </td>
                <td class="fw-semibold"><?= htmlspecialchars($row['name']) ?></td>
                <td><span class="badge bg-light text-dark border small"><?= htmlspecialchars($budgetTiers[$row['budget_tier']] ?? $row['budget_tier']) ?></span></td>
                <td class="fw-semibold" style="color:#059669;">
                    <?= $row['mrp'] > 0 ? '₹'.number_format($row['mrp'],2) : '<span class="text-muted">—</span>' ?>
                </td>
                <td class="fw-semibold" style="color:#059669;">
                    <?= $row['offer_price'] > 0 ? '₹'.number_format($row['offer_price'],2) : '<span class="text-muted">—</span>' ?>
                </td>
                <td class="text-muted small"><?= intval($row['quantity']) ?></td>
                <td>
                    <?= $row['status']==1
                        ? '<span class="badge-published">Active</span>'
                        : '<span class="badge-draft">Hidden</span>' ?>
                </td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Delete &quot;<?= htmlspecialchars($row['name']) ?>&quot;?')"><i class="bi bi-trash"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/layout_bottom.php'; ?>
