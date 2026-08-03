<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../login.php"); exit(); }
require_once '../includes/db.php';
require_once '../includes/config.php';
$pageTitle = 'Banner Management';

/* ── Upload dir ── */
$isLocal    = ($_SERVER['SERVER_NAME'] === 'localhost');
$uploadDir  = $isLocal
    ? $_SERVER['DOCUMENT_ROOT'] . '/supergifts/images/banners/'
    : $_SERVER['DOCUMENT_ROOT'] . '/images/banners/';
$uploadUrl  = 'images/banners/';

if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

$success = $error = '';

/* ═══════ HANDLE SAVE ═══════ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $slot     = intval($_POST['slot'] ?? 1);
    $title    = trim($_POST['title'] ?? '');
    $subtitle = trim($_POST['subtitle'] ?? '');
    $btn_text = trim($_POST['btn_text'] ?? 'Get a Quote');
    $btn_link = trim($_POST['btn_link'] ?? 'contact');
    $status   = intval($_POST['status'] ?? 1);
    $sort     = $slot - 1;

    /* Check if row for this slot already exists */
    $existing = $conn->query("SELECT id, file_path FROM banners WHERE slot=$slot LIMIT 1")->fetch_assoc();

    $file_path = $existing['file_path'] ?? '';
    $file_type = 'image';

    /* Handle file upload */
    if (!empty($_FILES['banner_file']['name'])) {
        $orig   = $_FILES['banner_file']['name'];
        $tmp    = $_FILES['banner_file']['tmp_name'];
        $size   = $_FILES['banner_file']['size'];
        $ext    = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
        $imgExt = ['jpg','jpeg','png','webp','gif'];
        $vidExt = ['mp4','webm','mov'];

        if (!in_array($ext, array_merge($imgExt, $vidExt))) {
            $error = 'Only JPG, PNG, WEBP, GIF, MP4, WEBM, MOV files allowed.';
        } elseif ($size > 30 * 1024 * 1024) {
            $error = 'File must be under 30 MB.';
        } else {
            /* Delete old file if exists */
            if (!empty($existing['file_path'])) {
                $old = $isLocal
                    ? $_SERVER['DOCUMENT_ROOT'] . '/supergifts/' . $existing['file_path']
                    : $_SERVER['DOCUMENT_ROOT'] . '/' . $existing['file_path'];
                if (file_exists($old)) @unlink($old);
            }
            $filename  = 'banner-' . $slot . '-' . time() . '.' . $ext;
            $dest      = $uploadDir . $filename;
            if (move_uploaded_file($tmp, $dest)) {
                $file_path = $uploadUrl . $filename;
                $file_type = in_array($ext, $vidExt) ? 'video' : 'image';
            } else {
                $error = 'Upload failed. Check folder permissions on images/banners/.';
            }
        }
    } elseif (!empty($existing['file_path'])) {
        /* Preserve existing file type */
        $ext = strtolower(pathinfo($existing['file_path'], PATHINFO_EXTENSION));
        $file_type = in_array($ext, ['mp4','webm','mov']) ? 'video' : 'image';
    }

    /* Handle remove file */
    if (isset($_POST['remove_file']) && !empty($existing['file_path'])) {
        $old = $isLocal
            ? $_SERVER['DOCUMENT_ROOT'] . '/supergifts/' . $existing['file_path']
            : $_SERVER['DOCUMENT_ROOT'] . '/' . $existing['file_path'];
        if (file_exists($old)) @unlink($old);
        $file_path = '';
        $file_type = 'image';
    }

    if (!$error) {
        if ($existing) {
            $stmt = $conn->prepare("UPDATE banners SET title=?,subtitle=?,btn_text=?,btn_link=?,file_path=?,file_type=?,status=?,sort_order=? WHERE slot=?");
            $stmt->bind_param("ssssssiis", $title,$subtitle,$btn_text,$btn_link,$file_path,$file_type,$status,$sort,$slot);
        } else {
            $stmt = $conn->prepare("INSERT INTO banners (slot,title,subtitle,btn_text,btn_link,file_path,file_type,status,sort_order) VALUES (?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("issssssii", $slot,$title,$subtitle,$btn_text,$btn_link,$file_path,$file_type,$status,$sort);
        }
        $stmt->execute();
        $stmt->close();
        $success = 'Banner ' . $slot . ' saved successfully.';
    }
}

/* ═══════ LOAD ALL BANNER SLOTS ═══════ */
$banners = [];
$res = $conn->query("SELECT * FROM banners ORDER BY slot ASC");
while ($row = $res->fetch_assoc()) $banners[$row['slot']] = $row;

/* Default values for empty slots */
$defaults = [
    1 => ['title' => 'Gifts That Inspire & Build Bonds',   'subtitle' => 'Premium branded products · Custom branding · Pan-India delivery.',   'btn_text' => 'Get a Quote', 'btn_link' => 'contact'],
    2 => ['title' => 'Your Brand, Our Expertise',          'subtitle' => 'In-house branding, embossing & engraving — concept to delivery.',       'btn_text' => 'Our Services','btn_link' => 'services'],
    3 => ['title' => 'Bulk Orders Made Easy',              'subtitle' => '5000+ products in stock. Quick co-branding. 48-hr delivery.',            'btn_text' => 'View Products','btn_link' => 'brand-products'],
    4 => ['title' => 'Pan-India Logistics At Your Service','subtitle' => 'Nationwide delivery · Real-time tracking · Rush orders.',               'btn_text' => 'Contact Us',  'btn_link' => 'contact'],
    6 => ['title' => 'Corporate Gifting',                 'subtitle' => 'Tailored corporate gifting solutions with custom branding.',               'btn_text' => 'Contact Sales','btn_link' => 'contact'],
    7 => ['title' => 'Premium Packaging',                 'subtitle' => 'Delightful unboxing with premium packing options and customization.',      'btn_text' => 'Packaging','btn_link' => 'contact'],
    8 => ['title' => 'Business Gifting',                 'subtitle' => 'Flexible gifting solutions for teams, partners, and events.',              'btn_text' => 'View Services','btn_link' => 'services'],
    9 => ['title' => 'Trusted Operations',               'subtitle' => 'Fast order processing, quality checks, and reliable delivery.',          'btn_text' => 'Learn More','btn_link' => 'about'],
    10 => ['title' => 'Design & Personalization',         'subtitle' => 'Custom artwork, logos, and finishing tailored to your brand.',           'btn_text' => 'Our Work','btn_link' => 'services'],
    11 => ['title' => 'Customer Success',                 'subtitle' => 'Dedicated support for every corporate gifting campaign.',               'btn_text' => 'Contact Us','btn_link' => 'contact'],
];
for ($i = 1; $i <= 4; $i++) {
    if (!isset($banners[$i])) {
        $banners[$i] = array_merge($defaults[$i], [
            'slot'=>$i,'file_path'=>'','file_type'=>'image','status'=>1,'sort_order'=>$i-1,'id'=>null
        ]);
    }
}
if (!isset($banners[5])) {
    $banners[5] = ['slot'=>5,'title'=>'','subtitle'=>'','btn_text'=>'','btn_link'=>'','file_path'=>'','file_type'=>'image','status'=>1,'sort_order'=>4,'id'=>null];
}
for ($i = 6; $i <= 11; $i++) {
    if (!isset($banners[$i])) {
        $banners[$i] = array_merge($defaults[$i], [
            'slot'=>$i,'file_path'=>'','file_type'=>'image','status'=>1,'sort_order'=>$i-1,'id'=>null
        ]);
    }
}

require_once '../includes/layout_top.php';
?>

<style>
.banner-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 24px; }
.banner-card { background:#fff; border-radius:12px; border:1px solid #e9ecef; overflow:hidden; }
.banner-card-header { padding:14px 20px; background:#f8f9fa; border-bottom:1px solid #e9ecef; display:flex; align-items:center; justify-content:space-between; }
.banner-card-header h6 { margin:0; font-weight:700; color:#1a1a2e; }
.banner-preview { height:160px; background:linear-gradient(135deg,#0D2B55,#1B4B7C); position:relative; overflow:hidden; display:flex; align-items:center; justify-content:center; }
.banner-preview img,
.banner-preview video { width:100%; height:100%; object-fit:cover; }
.banner-preview-empty { color:rgba(255,255,255,.4); text-align:center; font-size:13px; }
.banner-preview-empty i { font-size:36px; display:block; margin-bottom:6px; }
.banner-status-badge { position:absolute; top:8px; right:8px; font-size:11px; font-weight:700; padding:3px 10px; border-radius:20px; }
.slot-num { position:absolute; top:8px; left:8px; background:rgba(0,0,0,.55); color:#D4AF37; font-size:12px; font-weight:800; padding:3px 10px; border-radius:20px; }
.banner-form { padding:20px; }
.static-note { background:#fff8e6; border:1px solid #fcd34d; border-radius:8px; padding:10px 14px; font-size:12px; color:#92400e; margin-bottom:12px; }
@media(max-width:768px){ .banner-grid{grid-template-columns:1fr;} }
</style>

<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($success) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if ($error): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-bold mb-1">Banner Management</h5>
        <p class="text-muted small mb-0">Manage separate homepage and about page banner groups.</p>
        <p class="text-muted small mb-0">Slots 1-4 = homepage banners; slot 5 = All Products banner; slots 6-11 = About page banners.</p>
    </div>
    <a href="<?= $isLocal ? 'http://localhost/supergifts/' : 'https://www.supergifts.in/' ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-eye me-1"></i>Preview Site
    </a>
</div>

<div class="d-flex flex-column gap-4">
    <div>
        <h6 class="fw-bold mb-3">Homepage Banners (Slots 1–4)</h6>
        <div class="banner-grid">
        <?php for ($slot = 1; $slot <= 4; $slot++):
            $b = $banners[$slot];
            $hasFile = !empty($b['file_path']);
            $isVideo = ($b['file_type'] === 'video');
            $active  = intval($b['status'] ?? 1);
        ?>
        <div class="banner-card">
    <div class="banner-card-header">
        <h6><i class="bi bi-image me-2"></i>Banner Slot <?= $slot ?></h6>
        <span class="badge <?= $hasFile ? 'bg-success' : 'bg-secondary' ?>">
            <?= $hasFile ? ($isVideo ? 'Video' : 'Image') : 'Static' ?>
        </span>
    </div>

    <!-- Preview -->
    <div class="banner-preview">
        <span class="slot-num"><?= $slot ?></span>
        <?php if ($hasFile): ?>
            <?php if ($isVideo): ?>
            <video src="../../<?= htmlspecialchars($b['file_path']) ?>" muted playsinline></video>
            <?php else: ?>
            <img src="../../<?= htmlspecialchars($b['file_path']) ?>" alt="Banner <?= $slot ?>">
            <?php endif; ?>
        <?php else: ?>
            <div class="banner-preview-empty">
                <i class="bi bi-image-alt"></i>
                Static banner active
            </div>
        <?php endif; ?>
        <span class="banner-status-badge <?= $active ? 'bg-success' : 'bg-danger' ?>">
            <?= $active ? 'Active' : 'Hidden' ?>
        </span>
    </div>

    <!-- Form -->
    <div class="banner-form">
        <?php if (!$hasFile): ?>
        <div class="static-note">
            <i class="bi bi-info-circle me-1"></i>
            No file uploaded — homepage shows the <strong>static default banner</strong> for this slot.
        </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="slot" value="<?= $slot ?>">

            <div class="mb-3">
                <label class="form-label">Upload Image / Video <small class="text-muted">(JPG, PNG, WEBP, GIF, MP4, WEBM — max 30 MB)</small></label>
                <input type="file" name="banner_file" class="form-control form-control-sm"
                    accept="image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/quicktime"
                    onchange="previewFile(this, <?= $slot ?>)">
                <div id="preview_<?= $slot ?>" class="mt-2"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control form-control-sm"
                    value="<?= htmlspecialchars($b['title']) ?>" placeholder="Banner headline...">
            </div>

            <div class="mb-3">
                <label class="form-label">Subtitle</label>
                <input type="text" name="subtitle" class="form-control form-control-sm"
                    value="<?= htmlspecialchars($b['subtitle']) ?>" placeholder="Short description...">
            </div>

            <div class="row g-2 mb-3">
                <div class="col-6">
                    <label class="form-label">Button Text</label>
                    <input type="text" name="btn_text" class="form-control form-control-sm"
                        value="<?= htmlspecialchars($b['btn_text'] ?? 'Get a Quote') ?>">
                </div>
                <div class="col-6">
                    <label class="form-label">Button Link</label>
                    <input type="text" name="btn_link" class="form-control form-control-sm"
                        value="<?= htmlspecialchars($b['btn_link'] ?? 'contact') ?>" placeholder="contact">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="1" <?= $active ? 'selected' : '' ?>>Active (show on site)</option>
                    <option value="0" <?= !$active ? 'selected' : '' ?>>Hidden</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-gold btn-sm flex-grow-1">
                    <i class="bi bi-save me-1"></i>Save Banner <?= $slot ?>
                </button>
                <?php if ($hasFile): ?>
                <button type="submit" name="remove_file" value="1"
                    class="btn btn-outline-danger btn-sm"
                    onclick="return confirm('Remove the uploaded file and revert to static banner?')">
                    <i class="bi bi-trash"></i>
                </button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>
<?php endfor; ?>
        </div>
    </div>

    <div>
        <h6 class="fw-bold mb-3">About Page Banners (Slots 6–11)</h6>
        <div class="banner-grid">
        <?php for ($slot = 6; $slot <= 11; $slot++):
            $b = $banners[$slot];
            $hasFile = !empty($b['file_path']);
            $isVideo = ($b['file_type'] === 'video');
            $active  = intval($b['status'] ?? 1);
        ?>
        <div class="banner-card">
            <div class="banner-card-header">
                <h6><i class="bi bi-image me-2"></i>About Banner Slot <?= $slot ?></h6>
                <span class="badge <?= $hasFile ? 'bg-success' : 'bg-secondary' ?>">
                    <?= $hasFile ? ($isVideo ? 'Video' : 'Image') : 'Static' ?>
                </span>
            </div>

            <div class="banner-preview">
                <span class="slot-num"><?= $slot ?></span>
                <?php if ($hasFile): ?>
                    <?php if ($isVideo): ?>
                    <video src="../../<?= htmlspecialchars($b['file_path']) ?>" muted playsinline></video>
                    <?php else: ?>
                    <img src="../../<?= htmlspecialchars($b['file_path']) ?>" alt="Banner <?= $slot ?>">
                    <?php endif; ?>
                <?php else: ?>
                    <div class="banner-preview-empty">
                        <i class="bi bi-image-alt"></i>
                        Static banner active
                    </div>
                <?php endif; ?>
                <span class="banner-status-badge <?= $active ? 'bg-success' : 'bg-danger' ?>">
                    <?= $active ? 'Active' : 'Hidden' ?>
                </span>
            </div>

            <div class="banner-form">
                <?php if (!$hasFile): ?>
                <div class="static-note">
                    <i class="bi bi-info-circle me-1"></i>
                    No file uploaded — about page shows the <strong>static default banner</strong> for this slot.
                </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="slot" value="<?= $slot ?>">

                    <div class="mb-3">
                        <label class="form-label">Upload Image / Video <small class="text-muted">(JPG, PNG, WEBP, GIF, MP4, WEBM — max 30 MB)</small></label>
                        <input type="file" name="banner_file" class="form-control form-control-sm"
                            accept="image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/quicktime"
                            onchange="previewFile(this, <?= $slot ?>)">
                        <div id="preview_<?= $slot ?>" class="mt-2"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control form-control-sm"
                            value="<?= htmlspecialchars($b['title']) ?>" placeholder="Banner headline...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Subtitle</label>
                        <input type="text" name="subtitle" class="form-control form-control-sm"
                            value="<?= htmlspecialchars($b['subtitle']) ?>" placeholder="Short description...">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Button Text</label>
                            <input type="text" name="btn_text" class="form-control form-control-sm"
                                value="<?= htmlspecialchars($b['btn_text'] ?? 'Get a Quote') ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Button Link</label>
                            <input type="text" name="btn_link" class="form-control form-control-sm"
                                value="<?= htmlspecialchars($b['btn_link'] ?? 'contact') ?>" placeholder="contact">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="1" <?= $active ? 'selected' : '' ?>>Active (show on site)</option>
                            <option value="0" <?= !$active ? 'selected' : '' ?>>Hidden</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-gold btn-sm flex-grow-1">
                            <i class="bi bi-save me-1"></i>Save Banner <?= $slot ?>
                        </button>
                        <?php if ($hasFile): ?>
                        <button type="submit" name="remove_file" value="1"
                            class="btn btn-outline-danger btn-sm"
                            onclick="return confirm('Remove the uploaded file and revert to static banner?')">
                            <i class="bi bi-trash"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
        <?php endfor; ?>
        </div>
    </div>

    <div>
        <h6 class="fw-bold mb-3">All Products Page Banner (Slot 5)</h6>
        <div class="banner-grid" style="grid-template-columns:1fr;max-width:600px;">
        <?php
            $b       = $banners[5];
            $hasFile = !empty($b['file_path']);
            $isVideo = ($b['file_type'] === 'video');
            $active  = intval($b['status'] ?? 1);
            $slot    = 5;
        ?>
        <div class="banner-card">
            <div class="banner-card-header">
                <h6><i class="bi bi-image me-2"></i>All Products Banner</h6>
                <span class="badge <?= $hasFile ? 'bg-success' : 'bg-secondary' ?>">
                    <?= $hasFile ? ($isVideo ? 'Video' : 'Image') : 'Not set' ?>
                </span>
            </div>

            <div class="banner-preview">
                <?php if ($hasFile): ?>
                    <?php if ($isVideo): ?>
                    <video src="../../<?= htmlspecialchars($b['file_path']) ?>" muted playsinline></video>
                    <?php else: ?>
                    <img src="../../<?= htmlspecialchars($b['file_path']) ?>" alt="All Products Banner">
                    <?php endif; ?>
                <?php else: ?>
                    <div class="banner-preview-empty">
                        <i class="bi bi-image-alt"></i>
                        No banner uploaded
                    </div>
                <?php endif; ?>
                <span class="banner-status-badge <?= $active ? 'bg-success' : 'bg-danger' ?>">
                    <?= $active ? 'Active' : 'Hidden' ?>
                </span>
            </div>

            <div class="banner-form">
                <?php if (!$hasFile): ?>
                <div class="static-note">
                    <i class="bi bi-info-circle me-1"></i>
                    No file uploaded — the All Products page will not show a banner.
                </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="slot" value="5">

                    <div class="mb-3">
                        <label class="form-label">Upload Image / Video <small class="text-muted">(JPG, PNG, WEBP, GIF, MP4, WEBM — max 30 MB)</small></label>
                        <input type="file" name="banner_file" class="form-control form-control-sm"
                            accept="image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/quicktime"
                            onchange="previewFile(this, 5)">
                        <div id="preview_5" class="mt-2"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Title <small class="text-muted">(optional)</small></label>
                        <input type="text" name="title" class="form-control form-control-sm"
                            value="<?= htmlspecialchars($b['title']) ?>" placeholder="Banner headline...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Subtitle <small class="text-muted">(optional)</small></label>
                        <input type="text" name="subtitle" class="form-control form-control-sm"
                            value="<?= htmlspecialchars($b['subtitle']) ?>" placeholder="Short description...">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Button Text <small class="text-muted">(optional)</small></label>
                            <input type="text" name="btn_text" class="form-control form-control-sm"
                                value="<?= htmlspecialchars($b['btn_text']) ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Button Link <small class="text-muted">(optional)</small></label>
                            <input type="text" name="btn_link" class="form-control form-control-sm"
                                value="<?= htmlspecialchars($b['btn_link']) ?>" placeholder="contact">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="1" <?= $active ? 'selected' : '' ?>>Active (show on site)</option>
                            <option value="0" <?= !$active ? 'selected' : '' ?>>Hidden</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-gold btn-sm flex-grow-1">
                            <i class="bi bi-save me-1"></i>Save Banner
                        </button>
                        <?php if ($hasFile): ?>
                        <button type="submit" name="remove_file" value="1"
                            class="btn btn-outline-danger btn-sm"
                            onclick="return confirm('Remove the uploaded file?')">
                            <i class="bi bi-trash"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
</div>

<script>
function previewFile(input, slot) {
    var box = document.getElementById('preview_' + slot);
    if (!input.files || !input.files[0]) { box.innerHTML = ''; return; }
    var file = input.files[0];
    var url  = URL.createObjectURL(file);
    var ext  = file.name.split('.').pop().toLowerCase();
    if (['mp4','webm','mov'].includes(ext)) {
        box.innerHTML = '<video src="'+url+'" style="width:100%;max-height:100px;border-radius:6px;" controls muted></video>';
    } else {
        box.innerHTML = '<img src="'+url+'" style="width:100%;max-height:100px;object-fit:cover;border-radius:6px;">';
    }
}
</script>

<?php require_once '../includes/layout_bottom.php'; ?>
