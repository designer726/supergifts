<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../login.php"); exit(); }
require_once '../includes/db.php';
$pageTitle = 'Edit Budget Item';
$errors = []; $success = '';

$budgetTiers = [
    'tier1' => '₹10 – ₹200',
    'tier2' => '₹200 – ₹500',
    'tier3' => '₹500 – ₹1000',
    'tier4' => '₹1000 & Above',
];

$id = intval($_GET['id'] ?? 0);
if (!$id) { header("Location: index.php"); exit(); }

$stmt = $conn->prepare("SELECT * FROM budget_products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$item) { header("Location: index.php"); exit(); }

$imgUploadDir = ($_SERVER['SERVER_NAME'] === 'localhost')
    ? $_SERVER['DOCUMENT_ROOT'] . '/supergifts/images/budget/'
    : $_SERVER['DOCUMENT_ROOT'] . '/images/budget/';
if (!is_dir($imgUploadDir)) mkdir($imgUploadDir, 0755, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $mrp         = floatval($_POST['mrp'] ?? 0);
    $offer_price = floatval($_POST['offer_price'] ?? 0);
    $quantity    = intval($_POST['quantity'] ?? 0);
    $budget_tier = $_POST['budget_tier'] ?? $item['budget_tier'];
    if (!array_key_exists($budget_tier, $budgetTiers)) $budget_tier = $item['budget_tier'];
    $sequence    = intval($_POST['sequence'] ?? 0);
    $status      = intval($_POST['status'] ?? 1);
    $image       = $item['image'];

    if (!$name) $errors[] = "Item name is required.";

    if (!$errors && !empty($_FILES['image']['name'])) {
        $allowed = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = "Only JPG, PNG, WEBP allowed.";
        } elseif ($_FILES['image']['size'] > 3 * 1024 * 1024) {
            $errors[] = "Image must be under 3MB.";
        } else {
            $filename = 'budget-'.time().'-'.uniqid().'.'.$ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $imgUploadDir.$filename)) {
                if ($item['image']) {
                    $oldPath = ($_SERVER['SERVER_NAME']==='localhost')
                        ? $_SERVER['DOCUMENT_ROOT'].'/supergifts/'.$item['image']
                        : $_SERVER['DOCUMENT_ROOT'].'/'.$item['image'];
                    if (file_exists($oldPath)) @unlink($oldPath);
                }
                $image = 'images/budget/'.$filename;
            } else {
                $errors[] = "Failed to upload. Check images/budget/ permissions.";
            }
        }
    }

    if (!$errors) {
        $stmt = $conn->prepare("UPDATE budget_products SET name=?, image=?, mrp=?, offer_price=?, quantity=?, budget_tier=?, sequence=?, status=? WHERE id=?");
        $stmt->bind_param("ssddisiii", $name, $image, $mrp, $offer_price, $quantity, $budget_tier, $sequence, $status, $id);
        if ($stmt->execute()) {
            $success = "Budget item updated!";
            $item = array_merge($item, compact('name','image','mrp','offer_price','quantity','budget_tier','sequence','status'));
        } else {
            $errors[] = "DB error: ".$conn->error;
        }
        $stmt->close();
    }
}

$imgUrl = $item['image']
    ? (($_SERVER['SERVER_NAME']==='localhost') ? '/supergifts/'.$item['image'] : '/'.$item['image'])
    : '';

require_once '../includes/layout_top.php';
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Edit Budget Item</h5>
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
                    <label class="form-label">Item Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control"
                           value="<?= htmlspecialchars($item['name']) ?>" required>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">MRP (₹)</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" name="mrp" class="form-control"
                                   value="<?= $item['mrp'] ?>" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Offer Price (₹)</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" name="offer_price" class="form-control"
                                   value="<?= $item['offer_price'] ?>" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control"
                               value="<?= $item['quantity'] ?>" min="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Budget Tier <span class="text-danger">*</span></label>
                        <select name="budget_tier" class="form-select">
                            <?php foreach ($budgetTiers as $tierKey => $tierLabel): ?>
                            <option value="<?= $tierKey ?>" <?= $item['budget_tier']===$tierKey?'selected':'' ?>><?= $tierLabel ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
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
                <h6 class="fw-bold mb-3">Item Image</h6>
                <?php if ($imgUrl): ?>
                    <img id="img-preview" src="<?= htmlspecialchars($imgUrl) ?>"
                         style="max-height:150px;max-width:100%;object-fit:contain;border:1px solid #eee;border-radius:8px;padding:6px;margin-bottom:10px;"
                         onerror="this.style.display='none'">
                <?php else: ?>
                    <img id="img-preview" src="" style="max-height:150px;display:none;margin-bottom:10px;">
                <?php endif; ?>
                <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp"
                       onchange="previewImage(this)">
                <div class="text-muted small mt-1">Leave empty to keep current image.</div>
            </div>
        </div>
    </div>
    <div class="mt-4 d-flex gap-3">
        <button type="submit" class="btn btn-gold px-5"><i class="bi bi-save me-2"></i>Save Changes</button>
        <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('img-preview');
            img.src = e.target.result; img.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?php require_once '../includes/layout_bottom.php'; ?>
