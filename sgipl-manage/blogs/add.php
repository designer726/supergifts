<?php
// define('BASE_URL', '../../');
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

$pageTitle = 'Add New Blog Post';
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = trim($_POST['title'] ?? '');
    $slug     = trim($_POST['slug'] ?? '');
    $excerpt  = trim($_POST['excerpt'] ?? '');
    $content  = trim($_POST['content'] ?? '');
    $category = trim($_POST['category'] ?? 'Blog');
    $author   = trim($_POST['author'] ?? 'SGIPL Team');
    $status   = in_array($_POST['status'] ?? '', ['published', 'draft']) ? $_POST['status'] : 'published';
    $image    = '';

    // Validate
    if (!$title)   $errors[] = "Title is required.";
    if (!$slug)    $errors[] = "Slug is required.";
    if (!$excerpt) $errors[] = "Excerpt is required.";

    // Sanitize slug
    $slug = strtolower(preg_replace('/[^a-z0-9-]/', '-', $slug));
    $slug = preg_replace('/-+/', '-', trim($slug, '-'));

    // Check slug uniqueness
    if (!$errors) {
        $chk = $conn->prepare("SELECT id FROM blogs WHERE slug = ?");
        $chk->bind_param("s", $slug);
        $chk->execute();
        if ($chk->get_result()->num_rows > 0) $errors[] = "This slug already exists. Please use a different one.";
        $chk->close();
    }

    // Handle image upload
    if (!$errors && !empty($_FILES['image']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = "Only JPG, PNG, WEBP images are allowed.";
        } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
            $errors[] = "Image must be under 2MB.";
        } else {
            $filename = 'blog-' . time() . '-' . uniqid() . '.' . $ext;
            $dest = UPLOAD_DIR . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $image = 'images/blog/' . $filename;
            } else {
                $errors[] = "Failed to upload image. Check folder permissions.";
            }
        }
    }

    // Save to DB
    if (!$errors) {
        $stmt = $conn->prepare("INSERT INTO blogs (title, slug, excerpt, content, image, category, author, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $title, $slug, $excerpt, $content, $image, $category, $author, $status);
        if ($stmt->execute()) {
            $success = "Blog post added successfully!";
            $title = $slug = $excerpt = $content = $category = $author = '';
            $status = 'published';
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
        $stmt->close();
    }
}

require_once '../includes/layout_top.php';
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Add New Blog Post</h5>
</div>

<?php if ($errors): ?>
    <div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i><?= $success ?> <a href="index.php">View all posts →</a></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-lg-8">
            <div class="form-card mb-4">
                <div class="mb-3">
                    <label class="form-label">Post Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($title ?? '') ?>" placeholder="e.g. Top Gifting Trends for 2025" oninput="generateSlug(this.value)" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">URL Slug <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text text-muted small">blog_details?BlogDetails=</span>
                        <input type="text" name="slug" id="slug" class="form-control" value="<?= htmlspecialchars($slug ?? '') ?>" placeholder="top-gifting-trends-2025" required>
                    </div>
                </div>
                <div class="mb-0">
                    <label class="form-label">Short Excerpt <span class="text-danger">*</span></label>
                    <textarea name="excerpt" class="form-control" rows="2" placeholder="A brief summary shown on the blog listing page..." required><?= htmlspecialchars($excerpt ?? '') ?></textarea>
                </div>
            </div>

            <div class="form-card">
                <label class="form-label">Full Blog Content</label>
                <textarea name="content" class="form-control" rows="12" placeholder="Write the full blog post content here..."><?= htmlspecialchars($content ?? '') ?></textarea>
                <div class="text-muted small mt-1">You can use basic HTML tags for formatting.</div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <div class="form-card mb-4">
                <h6 class="fw-bold mb-3">Publish Settings</h6>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="published" <?= ($status??'published')==='published'?'selected':'' ?>>✅ Published</option>
                        <option value="draft"     <?= ($status??'')==='draft'?'selected':'' ?>>📝 Draft</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($category ?? 'Blog') ?>" placeholder="e.g. Corporate, Branding, Tips">
                </div>
                <div class="mb-0">
                    <label class="form-label">Author</label>
                    <input type="text" name="author" class="form-control" value="<?= htmlspecialchars($author ?? 'SGIPL Team') ?>">
                </div>
            </div>

            <div class="form-card">
                <h6 class="fw-bold mb-3">Featured Image</h6>
                <div id="preview-box" class="mb-2" style="display:none;">
                    <img id="img-preview" src="" style="width:100%;height:150px;object-fit:cover;border-radius:8px;border:1px solid #e9ecef;">
                </div>
                <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp" onchange="previewImage(this)">
                <div class="text-muted small mt-1">Max 2MB. JPG, PNG or WEBP.</div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-3">
        <button type="submit" class="btn btn-gold px-5"><i class="bi bi-save me-2"></i>Publish Post</button>
        <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

<script>
function generateSlug(title) {
    const slug = title.toLowerCase().replace(/[^a-z0-9\s-]/g, '').trim().replace(/\s+/g, '-');
    document.getElementById('slug').value = slug;
}
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
