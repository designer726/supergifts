<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../login.php"); exit(); }
require_once '../includes/db.php';
require_once '../includes/image_helper.php';
$pageTitle = 'Add Brand';
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

// Upload dir for logos
$uploadDir = ($_SERVER['SERVER_NAME'] === 'localhost')
    ? $_SERVER['DOCUMENT_ROOT'] . '/supergifts/images/brandlogo/'
    : $_SERVER['DOCUMENT_ROOT'] . '/images/brandlogo/';

// Upload dir for banners
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
    $imageno        = '';
    $brand_banner   = '';
    $brand_banner_2 = '';
    $brand_banner_3 = '';

    if (!$brandname) $errors[] = "Brand name is required.";

    // Get next imageno if not uploading
    if (!$errors) {
        $maxImg = $conn->query("SELECT MAX(CAST(imageno AS UNSIGNED)) as m FROM brandlogo")->fetch_assoc()['m'];
        $imageno = ($maxImg ?? 0) + 1;
    }

    // Handle logo upload
    if (!$errors && !empty($_FILES['logo']['name'])) {
        $allowed = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = "Only JPG, PNG, WEBP allowed.";
        } elseif ($_FILES['logo']['size'] > 2 * 1024 * 1024) {
            $errors[] = "Logo must be under 2MB.";
        } else {
            // Save as image{imageno}.jpg
            $filename = 'image' . $imageno . '.' . $ext;
            if (!move_uploaded_file($_FILES['logo']['tmp_name'], $uploadDir . $filename)) {
                $errors[] = "Failed to upload logo. Check images/brandlogo/ folder permissions.";
            } else {
                compressUploadedImage($uploadDir . $filename, 500);
            }
        }
    } elseif (!$errors && empty($_FILES['logo']['name'])) {
        $errors[] = "Brand logo is required.";
    }

    // Handle banner uploads (optional, up to 3)
    $bannerFields = ['banner1' => &$brand_banner, 'banner2' => &$brand_banner_2, 'banner3' => &$brand_banner_3];
    $bannerSlot = 0;
    foreach ($bannerFields as $fieldName => &$targetVar) {
        $bannerSlot++;
        if (!$errors && !empty($_FILES[$fieldName]['name'])) {
            $allowed = ['jpg','jpeg','png','webp'];
            $ext = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $errors[] = "Banner {$bannerSlot}: only JPG, PNG, WEBP allowed.";
            } elseif ($_FILES[$fieldName]['size'] > 4 * 1024 * 1024) {
                $errors[] = "Banner {$bannerSlot} must be under 4MB.";
            } else {
                $bannerFilename = 'banner-' . $imageno . '-' . $bannerSlot . '-' . time() . '-' . uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $bannerUploadDir . $bannerFilename)) {
                    compressUploadedImage($bannerUploadDir . $bannerFilename, 1920);
                    $targetVar = 'images/brandbanner/' . $bannerFilename;
                } else {
                    $errors[] = "Failed to upload banner {$bannerSlot}. Check images/brandbanner/ folder permissions.";
                }
            }
        }
    }
    unset($targetVar);

    if (!$errors) {
        $stmt = $conn->prepare("INSERT INTO brandlogo (brandname, links, website, imageno, brand_banner, brand_banner_2, brand_banner_3, seqence, flag, category) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("sssisssiis", $brandname, $links, $website, $imageno, $brand_banner, $brand_banner_2, $brand_banner_3, $seqence, $flag, $category);
        if ($stmt->execute()) {
            $newId = $conn->insert_id;
            $success = "Brand added! <a href='../products/add.php?brand_id={$newId}'>Add products →</a>";
            $brandname = $links = $website = ''; $flag = 1; $category = ''; $seqence = 0;
        } else {
            $errors[] = "DB error: " . $conn->error;
        }
        $stmt->close();
    }
}

require_once '../includes/layout_top.php';
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Add New Brand</h5>
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
                           value="<?= htmlspecialchars($brandname ?? '') ?>"
                           placeholder="e.g. Blaupunkt" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Catalog URL <span class="text-muted small fw-normal">(Google Drive / PDF link)</span></label>
                    <input type="text" name="links" class="form-control"
                           value="<?= htmlspecialchars($links ?? '') ?>"
                           placeholder="https://drive.google.com/...">
                </div>
                <div class="mb-0">
                    <label class="form-label">Brand Website <span class="text-muted small fw-normal">(used by "About Brand" link)</span></label>
                    <input type="text" name="website" class="form-control"
                           value="<?= htmlspecialchars($website ?? '') ?>"
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
                        <option value="1" <?= ($flag??1)==1?'selected':'' ?>>⭐ Authorised Brand Partner</option>
                        <option value="0" <?= ($flag??1)==0?'selected':'' ?>>🤝 We Also Deal</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Brand Category <span class="text-muted small fw-normal">(homepage section)</span></label>
                    <select name="category" class="form-select">
                        <option value="">— Select Category —</option>
                        <?php foreach ($brandCategories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat) ?>" <?= ($category ?? '')===$cat?'selected':'' ?>><?= htmlspecialchars($cat) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-0">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="seqence" class="form-control"
                           value="<?= $seqence ?? 0 ?>" min="0"
                           placeholder="0">
                    <div class="text-muted small mt-1">Lower number = shown first.</div>
                </div>
            </div>

            <div class="form-card">
                <h6 class="fw-bold mb-3">Brand Logo <span class="text-danger">*</span></h6>
                <div id="preview-box" style="display:none;margin-bottom:10px;text-align:center;">
                    <img id="img-preview" src=""
                         style="max-height:80px;max-width:100%;object-fit:contain;border:1px solid #eee;border-radius:6px;padding:6px;background:#fff;">
                </div>
                <input type="file" name="logo" class="form-control" accept="image/jpeg,image/png,image/webp"
                       onchange="previewImage(this)" required>
                <div class="text-muted small mt-1">Max 2MB. JPG, PNG or WEBP.</div>
            </div>

            <div class="form-card mt-4">
                <h6 class="fw-bold mb-3">Brand Banners <span class="text-muted small fw-normal">(carousel on brand product page)</span></h6>
                <?php for ($i = 1; $i <= 3; $i++): ?>
                <div class="mb-3<?= $i === 3 ? ' mb-0' : '' ?>">
                    <label class="form-label small fw-semibold">Banner <?= $i ?><?= $i === 1 ? '' : ' (optional)' ?></label>
                    <div id="banner<?= $i ?>-preview-box" style="display:none;margin-bottom:8px;text-align:center;">
                        <img id="banner<?= $i ?>-img-preview" src=""
                             style="max-height:90px;max-width:100%;object-fit:contain;border:1px solid #eee;border-radius:6px;padding:4px;background:#fff;">
                    </div>
                    <input type="file" name="banner<?= $i ?>" class="form-control" accept="image/jpeg,image/png,image/webp"
                           onchange="previewBanner(this, <?= $i ?>)">
                </div>
                <?php endfor; ?>
                <div class="text-muted small mt-2">Max 4MB each. Wide image recommended (e.g. 1600×400). Leave all empty to hide the banner carousel on this brand's page — upload just 1 for a static banner, or 2–3 for a carousel.</div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-3">
        <button type="submit" class="btn btn-gold px-5"><i class="bi bi-save me-2"></i>Save Brand</button>
        <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
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

function previewBanner(input, idx) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('banner' + idx + '-img-preview').src = e.target.result;
            document.getElementById('banner' + idx + '-preview-box').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once '../includes/layout_bottom.php'; ?>
