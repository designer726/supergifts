<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../login.php"); exit(); }
require_once '../includes/db.php';
$pageTitle = 'Edit Product';
$errors = []; $success = '';

$id = intval($_GET['id'] ?? 0);
if (!$id) { header("Location: index.php"); exit(); }

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$product) { header("Location: index.php"); exit(); }

$imgUploadDir = ($_SERVER['SERVER_NAME'] === 'localhost')
    ? $_SERVER['DOCUMENT_ROOT'] . '/supergifts/images/products/'
    : $_SERVER['DOCUMENT_ROOT'] . '/images/products/';
if (!is_dir($imgUploadDir)) mkdir($imgUploadDir, 0755, true);

$allBrands = $conn->query("SELECT id, brandname FROM brandlogo ORDER BY brandname ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand_id = intval($_POST['brand_id'] ?? 0);
    $name     = trim($_POST['name'] ?? '');
    $mrp      = floatval($_POST['mrp'] ?? 0);
    $sequence = intval($_POST['sequence'] ?? 0);
    $status   = intval($_POST['status'] ?? 1);
    $image    = $product['image'];

    if (!$brand_id) $errors[] = "Please select a brand.";
    if (!$name)     $errors[] = "Product name is required.";

    if (!$errors && !empty($_FILES['image']['name'])) {
        $allowed = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = "Only JPG, PNG, WEBP allowed.";
        } elseif ($_FILES['image']['size'] > 3 * 1024 * 1024) {
            $errors[] = "Image must be under 3MB.";
        } else {
            $filename = 'prod-'.$brand_id.'-'.time().'-'.uniqid().'.'.$ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $imgUploadDir.$filename)) {
                // Delete old image
                if ($product['image']) {
                    $oldPath = ($_SERVER['SERVER_NAME']==='localhost')
                        ? $_SERVER['DOCUMENT_ROOT'].'/supergifts/'.$product['image']
                        : $_SERVER['DOCUMENT_ROOT'].'/'.$product['image'];
                    if (file_exists($oldPath)) @unlink($oldPath);
                }
                $image = 'images/products/'.$filename;
            } else {
                $errors[] = "Failed to upload. Check images/products/ permissions.";
            }
        }
    }

    if (!$errors) {
        $stmt = $conn->prepare("UPDATE products SET brand_id=?, name=?, image=?, mrp=?, sequence=?, status=? WHERE id=?");
        $stmt->bind_param("issdiii", $brand_id, $name, $image, $mrp, $sequence, $status, $id);
        if ($stmt->execute()) {
            $success = "Product updated!";
            $product = array_merge($product, compact('brand_id','name','image','mrp','sequence','status'));
        } else {
            $errors[] = "DB error: ".$conn->error;
        }
        $stmt->close();
    }
}

$imgUrl = $product['image']
    ? (($_SERVER['SERVER_NAME']==='localhost') ? '/supergifts/'.$product['image'] : '/'.$product['image'])
    : '';

require_once '../includes/layout_top.php';
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="index.php?brand_id=<?= $product['brand_id'] ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="mb-0 fw-bold">Edit Product</h5>
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
                    <label class="form-label">Brand <span class="text-danger">*</span></label>
                    <select name="brand_id" class="form-select" required>
                        <?php while ($b = $allBrands->fetch_assoc()): ?>
                            <option value="<?= $b['id'] ?>" <?= $product['brand_id']==$b['id']?'selected':'' ?>>
                                <?= htmlspecialchars(ucfirst($b['brandname'])) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Product Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control"
                           value="<?= htmlspecialchars($product['name']) ?>" required>
                </div>
                <div class="mb-0">
                    <label class="form-label">MRP (₹)</label>
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="number" name="mrp" class="form-control"
                               value="<?= $product['mrp'] ?>" min="0" step="0.01">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-card mb-4">
                <h6 class="fw-bold mb-3">Settings</h6>
                <div class="mb-3">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="sequence" class="form-control" value="<?= $product['sequence'] ?>" min="0">
                </div>
                <div class="mb-0">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="1" <?= $product['status']==1?'selected':'' ?>>✅ Active</option>
                        <option value="0" <?= $product['status']==0?'selected':'' ?>>🚫 Hidden</option>
                    </select>
                </div>
            </div>
            <div class="form-card">
                <h6 class="fw-bold mb-3">Product Image</h6>
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
        <a href="index.php?brand_id=<?= $product['brand_id'] ?>" class="btn btn-outline-secondary">Cancel</a>
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
