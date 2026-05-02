<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

$id = (int)($_GET['id'] ?? 0);

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

$pageTitle = 'View Review - ' . htmlspecialchars($review['client_name']);

require_once '../includes/layout_top.php';
?>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h4 class="mb-2"><?= htmlspecialchars($review['client_name']) ?></h4>
                        <p class="text-muted mb-0"><?= htmlspecialchars($review['company_name']) ?></p>
                        <?php if($review['email']): ?>
                            <p class="text-muted small mb-0"><?= htmlspecialchars($review['email']) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="text-end">
                        <div class="text-warning mb-2" style="font-size: 24px;">
                            <?php for($s=0; $s<$review['rating']; $s++): ?>
                                <i class="bi bi-star-fill"></i>
                            <?php endfor; ?>
                            <?php for($s=$review['rating']; $s<5; $s++): ?>
                                <i class="bi bi-star"></i>
                            <?php endfor; ?>
                        </div>
                        <div class="text-muted small"><?= $review['rating'] ?> out of 5</div>
                    </div>
                </div>

                <hr>

                <div class="mb-4">
                    <h6 class="fw-bold mb-3">Review</h6>
                    <p class="text-muted" style="line-height: 1.6;">
                        <?= nl2br(htmlspecialchars($review['review_text'])) ?>
                    </p>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Submitted on</small>
                        <strong><?= date('d M Y, H:i A', strtotime($review['created_at'])) ?></strong>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <small class="text-muted d-block">Status</small>
                        <?php 
                        $statusClass = [
                            'approved' => 'badge-success',
                            'pending' => 'badge-warning',
                            'rejected' => 'badge-danger'
                        ];
                        $class = $statusClass[$review['status']] ?? 'badge-secondary';
                        ?>
                        <span class="badge <?= $class ?>"><?= ucfirst($review['status']) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <a href="edit.php?id=<?= $review['id'] ?>" class="btn btn-primary"><i class="bi bi-pencil me-1"></i>Edit</a>
            <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
        </div>
    </div>
</div>

<?php require_once '../includes/layout_bottom.php'; ?>
