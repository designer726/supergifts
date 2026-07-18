<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../login.php"); exit(); }
require_once '../includes/db.php';
$pageTitle = 'Add Product';
$errors = []; $success = '';

$prefill_brand = intval($_GET['brand_id'] ?? 0);

$imgUploadDir = ($_SERVER['SERVER_NAME'] === 'localhost')
    ? $_SERVER['DOCUMENT_ROOT'] . '/supergifts/images/products/'
    : $_SERVER['DOCUMENT_ROOT'] . '/images/products/';
if (!is_dir($imgUploadDir)) mkdir($imgUploadDir, 0755, true);

$allBrands = $conn->query("SELECT id, brandname FROM brandlogo ORDER BY brandname ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand_id    = intval($_POST['brand_id'] ?? 0);
    $name        = trim($_POST['name'] ?? '');
    $mrp         = floatval($_POST['mrp'] ?? 0);
    $offer_price = floatval($_POST['offer_price'] ?? 0);
    $quantity    = intval($_POST['quantity'] ?? 0);
    $is_premium  = intval($_POST['is_premium'] ?? 0);
    $new_lunch   = intval($_POST['new_lunch'] ?? 0);
    $series      = trim($_POST['series'] ?? '');
    $sequence    = intval($_POST['sequence'] ?? 0);
    $status      = intval($_POST['status'] ?? 1);
    $image       = '';

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
                $image = 'images/products/'.$filename;
            } else {
                $errors[] = "Failed to upload. Check images/products/ folder permissions.";
            }
        }
    }

    if (!$errors) {
        $stmt = $conn->prepare("INSERT INTO products (brand_id, name, image, mrp, offer_price, quantity, is_premium, new_lunch, series, sequence, status) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("issddiiisii", $brand_id, $name, $image, $mrp, $offer_price, $quantity, $is_premium, $new_lunch, $series, $sequence, $status);
        if ($stmt->execute()) {
            $success = "Product added! <a href='index.php?brand_id={$brand_id}'>View brand products →</a>";
            $name = ''; $mrp = 0; $offer_price = 0; $quantity = 0; $is_premium = 0; $new_lunch = 0; $series = ''; $sequence = 0;
        } else {
            $errors[] = "DB error: ".$conn->error;
        }
        $stmt->close();
    }
}

require_once '../includes/layout_top.php';
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="index.php<?= $prefill_brand?'?brand_id='.$prefill_brand:'' ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="mb-0 fw-bold">Add New Product</h5>
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
                    <label class="form-label">Select Brand <span class="text-danger">*</span></label>
                    <select name="brand_id" class="form-select" required>
                        <option value="">— Choose Brand —</option>
                        <?php
                        $allBrands->data_seek(0);
                        while ($b = $allBrands->fetch_assoc()):
                            $sel = ($prefill_brand == $b['id'] || (isset($brand_id) && $brand_id == $b['id'])) ? 'selected' : '';
                        ?>
                            <option value="<?= $b['id'] ?>" <?= $sel ?>>
                                <?= htmlspecialchars(ucfirst($b['brandname'])) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Product Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control"
                           value="<?= htmlspecialchars($name ?? '') ?>"
                           placeholder="e.g. Blaupunkt Bluetooth Speaker BT-200" required>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">MRP (₹)</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" name="mrp" class="form-control"
                                   value="<?= $mrp ?? 0 ?>" min="0" step="0.01" placeholder="999.00">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Offer Price (₹)</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" name="offer_price" class="form-control"
                                   value="<?= $offer_price ?? 0 ?>" min="0" step="0.01" placeholder="799.00">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control"
                               value="<?= $quantity ?? 0 ?>" min="0" placeholder="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Premium</label>
                        <select name="is_premium" class="form-select">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">New Launch</label>
                        <select name="new_lunch" class="form-select">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Series</label>
                        <input type="text" name="series" class="form-control"
                               value="<?= htmlspecialchars($series ?? '') ?>" placeholder="e.g. Urban Jungle">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-card mb-4">
                <h6 class="fw-bold mb-3">Settings</h6>
                <div class="mb-3">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="sequence" class="form-control"
                           value="<?= $sequence ?? 0 ?>" min="0">
                    <div class="text-muted small mt-1">Lower = shown first.</div>
                </div>
                <div class="mb-0">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="1">✅ Active</option>
                        <option value="0">🚫 Hidden</option>
                    </select>
                </div>
            </div>

            <div class="form-card">
                <h6 class="fw-bold mb-3">Product Image</h6>
                <div id="preview-box" style="display:none;margin-bottom:10px;text-align:center;">
                    <img id="img-preview" src=""
                         style="max-height:150px;max-width:100%;object-fit:contain;border:1px solid #eee;border-radius:8px;padding:6px;">
                </div>
                <input type="file" name="image" class="form-control"
                       accept="image/jpeg,image/png,image/webp" onchange="previewImage(this)">
                <div class="text-muted small mt-1">Max 3MB. JPG, PNG or WEBP.</div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-3">
        <button type="submit" class="btn btn-gold px-5"><i class="bi bi-save me-2"></i>Save Product</button>
        <a href="index.php<?= $prefill_brand?'?brand_id='.$prefill_brand:'' ?>"
           class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('img-preview').src = e.target.result;
            document.getElementById('preview-box').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once '../includes/layout_bottom.php'; ?>
