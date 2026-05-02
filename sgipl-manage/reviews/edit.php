<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

$id = (int)($_GET['id'] ?? 0);
$message = '';
$error = '';

if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM reviews WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$review = $result->fetch_assoc();

if (!$review) {
    die("Review not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'] ?? '';
    
    if (!in_array($status, ['approved', 'pending', 'rejected'])) {
        $error = "Invalid status.";
    } else {
        $stmt = $conn->prepare("UPDATE reviews SET status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param('si', $status, $id);
        
        if ($stmt->execute()) {
            $message = "Review status updated successfully!";
            $review['status'] = $status;
        } else {
            $error = "Error updating review: " . $stmt->error;
        }
    }
}

$pageTitle = 'Edit Review - ' . htmlspecialchars($review['client_name']);

require_once '../includes/layout_top.php';
?>

<div class="row">
    <div class="col-lg-8">
        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i><?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i><?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title mb-4">Review Details</h5>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Client Name</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($review['client_name']) ?>" disabled>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold">Company Name</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($review['company_name']) ?>" disabled>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars($review['email']) ?>" disabled>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold">Rating</label>
                        <div style="font-size: 24px; color: #ffc107;">
                            <?php for($s=0; $s<$review['rating']; $s++): ?>
                                <i class="bi bi-star-fill"></i>
                            <?php endfor; ?>
                            <?php for($s=$review['rating']; $s<5; $s++): ?>
                                <i class="bi bi-star"></i>
                            <?php endfor; ?>
                        </div>
                        <small class="text-muted"><?= $review['rating'] ?> out of 5</small>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold">Submitted On</label>
                        <input type="text" class="form-control" value="<?= date('d M Y, H:i A', strtotime($review['created_at'])) ?>" disabled>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Review Text</label>
                    <textarea class="form-control" rows="6" disabled><?= htmlspecialchars($review['review_text']) ?></textarea>
                </div>

                <hr>

                <form method="POST">
                    <div class="mb-4">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending" <?= $review['status'] === 'pending' ? 'selected' : '' ?>>Pending Review</option>
                            <option value="approved" <?= $review['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                            <option value="rejected" <?= $review['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
                        <small class="text-muted">Only "Approved" reviews will be displayed on the website</small>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check me-1"></i>Update Status</button>
                        <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/layout_bottom.php'; ?>
