<?php 
$pagename = basename($_SERVER['PHP_SELF']); 
require_once 'sgipl-manage/includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('common/head.php'); ?>
</head>
<body class="appear-animate">

    <!-- Page Loader -->
    <div class="page-loader">
        <div class="loader">Loading...</div>
    </div>
    <!-- End Page Loader -->
    <!-- Page Wrap -->
    <div class="page" id="top">
        <?php include('common/nav.php'); ?>

        <main id="main">

            <!-- Page Title Section -->
            <section class="page-section bg-gradient-light-1 overflow-hidden">
                <div class="container position-relative z-index-1 pt-60 pb-60 pt-md-100 pb-md-100">
                    <div class="text-center max-width-800 mx-auto mb-0">
                        <h1 class="hs-title-1 mb-20">
                            <span class="wow charsAnimIn">Client Reviews & Ratings</span>
                        </h1>
                        <p class="section-descr mb-0 wow fadeInUp" data-wow-delay="0.2s">
                            Hear what our valued clients have to say about our corporate gifting solutions
                        </p>
                    </div>
                </div>
            </section>

            <!-- Reviews Grid Section -->
            <section class="page-section bg-white">
                <div class="container">
                    <div class="row">
                        <!-- Reviews Listing -->
                        <div class="col-lg-8 mb-80 mb-md-60">
                            <div id="reviews-list">
                                <?php
                                if (!$conn) {
                                    echo '<p class="text-center text-danger">Database connection error. Please try again later.</p>';
                                } else {
                                    $stmt = $conn->prepare("SELECT * FROM reviews WHERE status = 'approved' ORDER BY created_at DESC LIMIT 50");
                                    if (!$stmt) {
                                        echo '<p class="text-center text-warning">Reviews table not found. Please run the setup script.</p>';
                                    } else {
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        if ($result->num_rows === 0) {
                                            echo '<p class="text-center text-muted">No reviews yet. Be the first to share your feedback!</p>';
                                        } else {
                                            while($review = $result->fetch_assoc()):
                                ?>
                                <div class="review-card mb-40 pb-40" style="border-bottom: 1px solid #e9e9e9;">
                                    <!-- Rating Stars -->
                                    <div class="mb-15" style="color: #ffc107; font-size: 18px;">
                                        <?php for($s=0; $s<$review['rating']; $s++): ?>
                                            <i class="fa fa-star"></i>
                                        <?php endfor; ?>
                                        <?php for($s=$review['rating']; $s<5; $s++): ?>
                                            <i class="fa fa-star-o"></i>
                                        <?php endfor; ?>
                                    </div>

                                    <!-- Review Text -->
                                    <p class="review-text mb-20" style="font-size: 16px; line-height: 1.6; color: #555;">
                                        "<?= htmlspecialchars($review['review_text']) ?>"
                                    </p>

                                    <!-- Client Info -->
                                    <div class="review-meta d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong class="review-name" style="color: #000; font-size: 15px;">
                                                <?= htmlspecialchars($review['client_name']) ?>
                                            </strong>
                                            <br>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($review['company_name']) ?>
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted d-block">
                                                <?= date('d M Y', strtotime($review['created_at'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                            endwhile;
                                        }
                                        $stmt->close();
                                    }
                                }
                                ?>
                            </div>
                        </div>

                        <!-- Sidebar with Form -->
                        <div class="col-lg-4">
                            <!-- Stats Box -->
                            <div class="bg-gray-light-2 p-40 mb-40 rounded-8" style="background: #f9f9f9; border: 1px solid #e9e9e9;">
                                <h5 class="mb-30">Overall Rating</h5>
                                <?php
                                $stats = null;
                                if ($conn) {
                                    $result = $conn->query("
                                        SELECT 
                                            COUNT(*) as total,
                                            AVG(rating) as avg_rating
                                        FROM reviews 
                                        WHERE status = 'approved'
                                    ");
                                    if ($result) {
                                        $stats = $result->fetch_assoc();
                                    }
                                }
                                ?>
                                <div class="mb-20">
                                    <div style="font-size: 36px; color: #ffc107; font-weight: bold;">
                                        <?= number_format($stats['avg_rating'] ?? 0, 1) ?>
                                        <span style="font-size: 24px;">/ 5</span>
                                    </div>
                                    <div style="color: #ffc107; font-size: 18px; margin-top: 5px;">
                                        <?php $avg = $stats['avg_rating'] ?? 0; 
                                        for($s=0; $s<floor($avg); $s++): ?>
                                            <i class="fa fa-star"></i>
                                        <?php endfor; ?>
                                        <?php if($avg - floor($avg) > 0): ?>
                                            <i class="fa fa-star-half-o"></i>
                                        <?php endif; ?>
                                    </div>
                                    <small class="text-muted d-block mt-10">
                                        Based on <?= $stats['total'] ?> reviews
                                    </small>
                                </div>
                            </div>

                            <!-- Review Form -->
                            <div class="bg-gray-light-2 p-40 rounded-8" style="background: #f9f9f9; border: 1px solid #e9e9e9;">
                                <h5 class="mb-30">Share Your Review</h5>
                                <form id="review-form" method="POST" action="submit-review.php">
                                    <div class="mb-20">
                                        <label class="form-label" for="client_name">Your Name *</label>
                                        <input type="text" id="client_name" name="client_name" class="form-control" placeholder="Full Name" required>
                                    </div>

                                    <div class="mb-20">
                                        <label class="form-label" for="company_name">Company Name *</label>
                                        <input type="text" id="company_name" name="company_name" class="form-control" placeholder="Your Company" required>
                                    </div>

                                    <div class="mb-20">
                                        <label class="form-label" for="email">Email Address *</label>
                                        <input type="email" id="email" name="email" class="form-control" placeholder="your@email.com" required>
                                    </div>

                                    <div class="mb-20">
                                        <label class="form-label">Your Rating *</label>
                                        <div class="rating-input" style="font-size: 24px; color: #ccc; cursor: pointer;">
                                            <input type="hidden" id="rating" name="rating" value="5" required>
                                            <?php for($i=1; $i<=5; $i++): ?>
                                                <i class="fa fa-star star-icon" data-rating="<?= $i ?>" style="cursor: pointer; margin-right: 10px;"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <small class="text-muted d-block mt-5">Click to rate</small>
                                    </div>

                                    <div class="mb-20">
                                        <label class="form-label" for="review_text">Your Review *</label>
                                        <textarea id="review_text" name="review_text" class="form-control" placeholder="Share your experience..." rows="5" required></textarea>
                                    </div>

                                    <div class="mb-20">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="agree" name="agree" required>
                                            <label class="form-check-label" for="agree">
                                                I agree to the <a href="TermandCondition.php" target="_blank">Terms & Conditions</a>
                                            </label>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-mod btn-large btn-round btn-hover-anim w-100">
                                        <span>Submit Review</span>
                                    </button>
                                </form>

                                <div id="form-message" class="mt-15"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </main>

        <?php include('common/footer.php'); ?>

    </div>
    <!-- End Page Wrap -->

    <!-- JS -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Initialize page loader
    jQuery(document).ready(function() {
        $(".page-loader div").fadeOut();
        $(".page-loader").delay(250).fadeOut("slow");
    });

    // Safe initialization of WOW animations if available
    if (typeof WOW !== 'undefined') {
        new WOW().init();
    }

    // Rating Stars Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const starIcons = document.querySelectorAll('.star-icon');
        
        if (starIcons.length > 0) {
            starIcons.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = this.getAttribute('data-rating');
                    document.getElementById('rating').value = rating;
                    updateStarColors(starIcons, rating);
                });

                star.addEventListener('mouseover', function() {
                    const rating = this.getAttribute('data-rating');
                    updateStarColors(starIcons, rating);
                });
            });

            // Set initial rating color
            const initialRating = document.getElementById('rating').value;
            updateStarColors(starIcons, initialRating);

            // Reset on mouse leave
            document.addEventListener('mouseleave', function() {
                const rating = document.getElementById('rating').value;
                updateStarColors(starIcons, rating);
            });
        }

        // Form Submission
        const reviewForm = document.getElementById('review-form');
        if (reviewForm) {
            reviewForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const messageDiv = document.getElementById('form-message');
                
                fetch('submit-review.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        messageDiv.innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert"><i class="bi bi-check-circle me-2"></i>Thank you! Your review has been submitted and is pending approval.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
                        reviewForm.reset();
                        document.getElementById('rating').value = 5;
                        updateStarColors(starIcons, 5);
                    } else {
                        messageDiv.innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="bi bi-exclamation-circle me-2"></i>' + (data.message || 'Error submitting review') + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
                    }
                })
                .catch(error => {
                    messageDiv.innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="bi bi-exclamation-circle me-2"></i>Error: ' + error.message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
                });
            });
        }
    });

    function updateStarColors(starIcons, rating) {
        starIcons.forEach((s, index) => {
            if (index < rating) {
                s.style.color = '#ffc107';
            } else {
                s.style.color = '#ccc';
            }
        });
    }
    </script>

</body>
</html>
