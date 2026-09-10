<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../login.php"); exit(); }
require_once '../includes/db.php';
require_once '../includes/image_helper.php';
$pageTitle = 'Edit Brand';
$errors = []; $success = '';

$brandCategories = [
    'Electronics',
    'Electrical',
    'Home & Kitchen',
    'Travel & Luggage',
    'Apparels/Sports',
    'Lifestyle / Personal Hygiene',
    'Food & Beverages',
    'Large Home & Commercial Appliances',
];

$id = intval($_GET['id'] ?? 0);
if (!$id) { header("Location: index.php"); exit(); }

$stmt = $conn->prepare("SELECT * FROM brandlogo WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$brand = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$brand) { header("Location: index.php"); exit(); }

$uploadDir = ($_SERVER['SERVER_NAME'] === 'localhost')
    ? $_SERVER['DOCUMENT_ROOT'] . '/supergifts/images/brandlogo/'
    : $_SERVER['DOCUMENT_ROOT'] . '/images/brandlogo/';

$bannerUploadDir = ($_SERVER['SERVER_NAME'] === 'localhost')
    ? $_SERVER['DOCUMENT_ROOT'] . '/supergifts/images/brandbanner/'
    : $_SERVER['DOCUMENT_ROOT'] . '/images/brandbanner/';
if (!is_dir($bannerUploadDir)) mkdir($bannerUploadDir, 0755, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brandname      = trim($_POST['brandname'] ?? '');
    $links          = trim($_POST['links'] ?? '');
    $website        = trim($_POST['website'] ?? '');
    $flag           = intval($_POST['flag'] ?? 1);
    $category       = trim($_POST['category'] ?? '');
    $seqence        = intval($_POST['seqence'] ?? 0);
    $imageno        = $brand['imageno']; // Keep old imageno by default
    $brand_banner   = $brand['brand_banner'];   // Keep old banners by default
    $brand_banner_2 = $brand['brand_banner_2'];
    $brand_banner_3 = $brand['brand_banner_3'];

    if (!$brandname) $errors[] = "Brand name is required.";

    // Handle new logo upload
    if (!$errors && !empty($_FILES['logo']['name'])) {
        $allowed = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = "Only JPG, PNG, WEBP allowed.";
        } elseif ($_FILES['logo']['size'] > 2 * 1024 * 1024) {
            $errors[] = "Logo must be under 2MB.";
        } else {
            // Delete old logo file
            $oldFile = $uploadDir . 'image' . $brand['imageno'] . '.jpg';
            if (file_exists($oldFile)) @unlink($oldFile);
            // Save new logo with same imageno
            $filename = 'image' . $imageno . '.' . $ext;
            if (!move_uploaded_file($_FILES['logo']['tmp_name'], $uploadDir . $filename)) {
                $errors[] = "Failed to upload logo. Check folder permissions.";
            } else {
                compressUploadedImage($uploadDir . $filename, 500);
            }
        }
    }

    // Handle new banner uploads (optional, up to 3)
    function handleBannerUpload($slot, $fieldName, $dbCol, $currentVal, $brand, $bannerUploadDir, &$errors) {
        if (!empty($_FILES[$fieldName]['name'])) {
            $allowed = ['jpg','jpeg','png','webp'];
            $ext = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $errors[] = "Banner {$slot}: only JPG, PNG, WEBP allowed.";
                return $currentVal;
            }
            if ($_FILES[$fieldName]['size'] > 4 * 1024 * 1024) {
                $errors[] = "Banner {$slot} must be under 4MB.";
                return $currentVal;
            }
            $bannerFilename = 'banner-' . $brand['imageno'] . '-' . $slot . '-' . time() . '-' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $bannerUploadDir . $bannerFilename)) {
                compressUploadedImage($bannerUploadDir . $bannerFilename, 1920);
                if ($brand[$dbCol]) {
                    $oldBanner = ($_SERVER['SERVER_NAME']==='localhost')
                        ? $_SERVER['DOCUMENT_ROOT'].'/supergifts/'.$brand[$dbCol]
                        : $_SERVER['DOCUMENT_ROOT'].'/'.$brand[$dbCol];
                    if (file_exists($oldBanner)) @unlink($oldBanner);
                }
                return 'images/brandbanner/' . $bannerFilename;
            }
            $errors[] = "Failed to upload banner {$slot}. Check folder permissions.";
            return $currentVal;
        }
        if (isset($_POST['remove_banner' . $slot])) {
            if ($brand[$dbCol]) {
                $oldBanner = ($_SERVER['SERVER_NAME']==='localhost')
                    ? $_SERVER['DOCUMENT_ROOT'].'/supergifts/'.$brand[$dbCol]
                    : $_SERVER['DOCUMENT_ROOT'].'/'.$brand[$dbCol];
                if (file_exists($oldBanner)) @unlink($oldBanner);
            }
            return '';
        }
        return $currentVal;
    }

    if (!$errors) {
        $brand_banner   = handleBannerUpload(1, 'banner1', 'brand_banner',   $brand_banner,   $brand, $bannerUploadDir, $errors);
        $brand_banner_2 = handleBannerUpload(2, 'banner2', 'brand_banner_2', $brand_banner_2, $brand, $bannerUploadDir, $errors);
        $brand_banner_3 = handleBannerUpload(3, 'banner3', 'brand_banner_3', $brand_banner_3, $brand, $bannerUploadDir, $errors);
    }

    if (!$errors) {
        $stmt = $conn->prepare("UPDATE brandlogo SET brandname=?, links=?, website=?, brand_banner=?, brand_banner_2=?, brand_banner_3=?, seqence=?, flag=?, category=? WHERE id=?");
        $stmt->bind_param("ssssssiisi", $brandname, $links, $website, $brand_banner, $brand_banner_2, $brand_banner_3, $seqence, $flag, $category, $id);
        if ($stmt->execute()) {
            $success = "Brand updated successfully!";
            $brand = array_merge($brand, compact('brandname','links','website','brand_banner','brand_banner_2','brand_banner_3','seqence','flag','category'));
        } else {
            $errors[] = "DB error: " . $conn->error;
        }
        $stmt->close();
    }
}

$logoUrl = ($_SERVER['SERVER_NAME']==='localhost' ? '/supergifts/' : '/') . 'images/brandlogo/image' . $brand['imageno'] . '.jpg';
function bannerUrlFor($path) {
    if (!$path) return '';
    return ($_SERVER['SERVER_NAME']==='localhost') ? '/supergifts/'.$path : '/'.$path;
}
$bannerUrls = [1 => bannerUrlFor($brand['brand_banner']), 2 => bannerUrlFor($brand['brand_banner_2']), 3 => bannerUrlFor($brand['brand_banner_3'])];

require_once '../includes/layout_top.php';
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Edit Brand: <span class="text-capitalize"><?= htmlspecialchars($brand['brandname']) ?></span></h5>
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
                    <label class="form-label">Brand Name <span class="text-danger">*</span></label>
                    <input type="text" name="brandname" class="form-control"
                           value="<?= htmlspecialchars($brand['brandname']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Catalog URL</label>
                    <input type="text" name="links" class="form-control"
                           value="<?= htmlspecialchars($brand['links']) ?>"
                           placeholder="https://drive.google.com/...">
                </div>
                <div class="mb-0">
                    <label class="form-label">Brand Website <span class="text-muted small fw-normal">(used by "About Brand" link)</span></label>
                    <input type="text" name="website" class="form-control"
                           value="<?= htmlspecialchars($brand['website'] ?? '') ?>"
                           placeholder="https://www.brandwebsite.com">
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-card mb-4">
                <h6 class="fw-bold mb-3">Brand Settings</h6>
                <div class="mb-3">
                    <label class="form-label">Brand Type</label>
                    <select name="flag" class="form-select">
                        <option value="1" <?= $brand['flag']==1?'selected':'' ?>>⭐ Authorised Brand Partner</option>
                        <option value="0" <?= $brand['flag']==0?'selected':'' ?>>🤝 We Also Deal</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Brand Category <span class="text-muted small fw-normal">(homepage section)</span></label>
                    <select name="category" class="form-select">
                        <option value="">— Select Category —</option>
                        <?php foreach ($brandCategories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat) ?>" <?= ($brand['category'] ?? '')===$cat?'selected':'' ?>><?= htmlspecialchars($cat) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-0">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="seqence" class="form-control"
                           value="<?= $brand['seqence'] ?>" min="0">
                    <div class="text-muted small mt-1">Lower number = shown first.</div>
                </div>
            </div>

            <div class="form-card">
                <h6 class="fw-bold mb-3">Brand Logo</h6>
                <img id="img-preview" src="<?= htmlspecialchars($logoUrl) ?>"
                     style="max-height:80px;max-width:100%;object-fit:contain;border:1px solid #eee;border-radius:6px;padding:6px;background:#fff;margin-bottom:10px;"
                     onerror="this.style.display='none'">
                <input type="file" name="logo" class="form-control" accept="image/jpeg,image/png,image/webp"
                       onchange="previewImage(this)">
                <div class="text-muted small mt-1">Leave empty to keep current logo.</div>
            </div>

            <div class="form-card mt-4">
                <h6 class="fw-bold mb-3">Brand Banners <span class="text-muted small fw-normal">(carousel on brand product page)</span></h6>
                <?php for ($i = 1; $i <= 3; $i++): $bUrl = $bannerUrls[$i]; ?>
                <div class="mb-3<?= $i === 3 ? ' mb-0' : '' ?>">
                    <label class="form-label small fw-semibold">Banner <?= $i ?><?= $i === 1 ? '' : ' (optional)' ?></label>
                    <?php if ($bUrl): ?>
                        <img id="banner<?= $i ?>-img-preview" src="<?= htmlspecialchars($bUrl) ?>"
                             style="max-height:90px;max-width:100%;object-fit:contain;border:1px solid #eee;border-radius:6px;padding:4px;background:#fff;margin-bottom:8px;display:block;"
                             onerror="this.style.display='none'">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="remove_banner<?= $i ?>" id="remove_banner<?= $i ?>">
                            <label class="form-check-label small text-danger" for="remove_banner<?= $i ?>">Remove this banner</label>
                        </div>
                    <?php else: ?>
                        <img id="banner<?= $i ?>-img-preview" src="" style="max-height:90px;display:none;margin-bottom:8px;">
                    <?php endif; ?>
                    <input type="file" name="banner<?= $i ?>" class="form-control" accept="image/jpeg,image/png,image/webp"
                           onchange="previewBanner(this, <?= $i ?>)">
                </div>
                <?php endfor; ?>
                <div class="text-muted small mt-2">Max 4MB each. Leave all empty/removed to hide the banner carousel on this brand's page.</div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-3">
        <button type="submit" class="btn btn-gold px-5"><i class="bi bi-save me-2"></i>Save Changes</button>
        <a href="../products/index.php?brand_id=<?= $id ?>" class="btn btn-outline-primary">
            <i class="bi bi-box-seam me-1"></i>Manage Products
        </a>
        <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('img-preview');
            img.src = e.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewBanner(input, idx) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('banner' + idx + '-img-preview');
            img.src = e.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once '../includes/layout_bottom.php'; ?>
