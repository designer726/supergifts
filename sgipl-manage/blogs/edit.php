<?php
// define('BASE_URL', '../../');
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/image_helper.php';

$pageTitle = 'Edit Blog Post';
$errors = [];
$success = '';

$id = intval($_GET['id'] ?? 0);
if (!$id) { header("Location: index.php"); exit(); }

// Load existing post
$stmt = $conn->prepare("SELECT * FROM blogs WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$post) { header("Location: index.php"); exit(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = trim($_POST['title'] ?? '');
    $slug     = trim($_POST['slug'] ?? '');
    $excerpt  = trim($_POST['excerpt'] ?? '');
    $content  = trim($_POST['content'] ?? '');
    $category = trim($_POST['category'] ?? 'Blog');
    $author   = trim($_POST['author'] ?? 'SGIPL Team');
    $link             = trim($_POST['link'] ?? '');
    $status           = in_array($_POST['status'] ?? '', ['published', 'draft']) ? $_POST['status'] : 'published';
    $image            = $post['image']; // Keep old image by default
    $title_bg_image   = $post['title_bg_image'];

    if (!$title)   $errors[] = "Title is required.";
    if (!$slug)    $errors[] = "Slug is required.";
    if (!$excerpt) $errors[] = "Excerpt is required.";

    $slug = strtolower(preg_replace('/[^a-z0-9-]/', '-', $slug));
    $slug = preg_replace('/-+/', '-', trim($slug, '-'));

    if (!$errors) {
        $chk = $conn->prepare("SELECT id FROM blogs WHERE slug = ? AND id != ?");
        $chk->bind_param("si", $slug, $id);
        $chk->execute();
        if ($chk->get_result()->num_rows > 0) $errors[] = "This slug already exists on another post.";
        $chk->close();
    }

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
                compressUploadedImage($dest, 1200);
                // Delete old image if it was uploaded via admin
                if ($post['image'] && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $post['image'])) {
                    @unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $post['image']);
                }
                $image = 'images/blog/' . $filename;
            } else {
                $errors[] = "Failed to upload image. Check folder permissions.";
            }
        }
    }

    if (!$errors && !empty($_FILES['title_bg_image']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['title_bg_image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = "Only JPG, PNG, WEBP images are allowed for the title background.";
        } elseif ($_FILES['title_bg_image']['size'] > 2 * 1024 * 1024) {
            $errors[] = "Background image must be under 2MB.";
        } else {
            $filename = 'blog-title-bg-' . time() . '-' . uniqid() . '.' . $ext;
            $dest = UPLOAD_DIR . $filename;
            if (move_uploaded_file($_FILES['title_bg_image']['tmp_name'], $dest)) {
                compressUploadedImage($dest, 1920);
                if ($post['title_bg_image'] && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $post['title_bg_image'])) {
                    @unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $post['title_bg_image']);
                }
                $title_bg_image = 'images/blog/' . $filename;
            } else {
                $errors[] = "Failed to upload title background image. Check folder permissions.";
            }
        }
    }

    if (!$errors) {
        $stmt = $conn->prepare("UPDATE blogs SET title=?, slug=?, excerpt=?, content=?, image=?, title_bg_image=?, category=?, author=?, status=?, link=? WHERE id=?");
        $stmt->bind_param("ssssssssssi", $title, $slug, $excerpt, $content, $image, $title_bg_image, $category, $author, $status, $link, $id);
        if ($stmt->execute()) {
            $success = "Blog post updated successfully!";
            $post = array_merge($post, compact('title','slug','excerpt','content','image','category','author','status','link','title_bg_image'));
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
    <h5 class="mb-0 fw-bold">Edit Blog Post</h5>
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
                    <label class="form-label">Post Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($post['title']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">URL Slug <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text text-muted small">blog_details?BlogDetails=</span>
                        <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($post['slug']) ?>" required>
                    </div>
                </div>
                <div class="mb-0">
                    <label class="form-label">Short Excerpt <span class="text-danger">*</span></label>
                    <textarea name="excerpt" class="form-control" rows="2" required><?= htmlspecialchars($post['excerpt']) ?></textarea>
                </div>
            </div>
            <div class="form-card">
                <label class="form-label">Full Blog Content</label>
                <textarea name="content" class="form-control" rows="12"><?= htmlspecialchars($post['content']) ?></textarea>
                <div class="text-muted small mt-1">You can use basic HTML tags for formatting.</div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-card mb-4">
                <h6 class="fw-bold mb-3">Publish Settings</h6>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="published" <?= $post['status']==='published'?'selected':'' ?>>✅ Published</option>
                        <option value="draft"     <?= $post['status']==='draft'?'selected':'' ?>>📝 Draft</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($post['category']) ?>">
                </div>
                <div class="mb-0">
                    <label class="form-label">Author</label>
                    <input type="text" name="author" class="form-control" value="<?= htmlspecialchars($post['author']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Optional Link</label>
                    <input type="text" name="link" class="form-control" value="<?= htmlspecialchars($post['link'] ?? '') ?>" placeholder="https://example.com or /contact">
                    <div class="text-muted small mt-1">Leave empty to hide the link section on the blog page.</div>
                </div>
            </div>
            <div class="form-card">
                <h6 class="fw-bold mb-3">Images</h6>
                <div id="preview-box-featured" class="mb-2" style="display:<?= $post['image'] ? 'block' : 'none' ?>;">
                    <img id="img-preview-featured" src="<?= $post['image'] ? 'https://www.supergifts.in/' . htmlspecialchars($post['image']) : '' ?>" style="width:100%;height:150px;object-fit:cover;border-radius:8px;border:1px solid #e9ecef;">
                </div>
                <label class="form-label">Featured Image</label>
                <input type="file" name="image" class="form-control mb-3" accept="image/jpeg,image/png,image/webp" onchange="previewImage(this, 'img-preview-featured', 'preview-box-featured')">
                <div class="text-muted small mb-3">Leave empty to keep current featured image.</div>
                <div id="preview-box-bg" class="mb-2" style="display:<?= $post['title_bg_image'] ? 'block' : 'none' ?>;">
                    <img id="img-preview-bg" src="<?= $post['title_bg_image'] ? 'https://www.supergifts.in/' . htmlspecialchars($post['title_bg_image']) : '' ?>" style="width:100%;height:150px;object-fit:cover;border-radius:8px;border:1px solid #e9ecef;">
                </div>
                <label class="form-label">Title Background Image</label>
                <input type="file" name="title_bg_image" class="form-control" accept="image/jpeg,image/png,image/webp" onchange="previewImage(this, 'img-preview-bg', 'preview-box-bg')">
                <div class="text-muted small mt-1">This image appears behind the blog title. Leave empty to keep current background.</div>
            </div>
        </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-3">
        <button type="submit" class="btn btn-gold px-5"><i class="bi bi-save me-2"></i>Save Changes</button>
        <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

<script>
function previewImage(input, previewId, previewBoxId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById(previewId);
            img.src = e.target.result;
            document.getElementById(previewBoxId).style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once '../includes/layout_bottom.php'; ?>
