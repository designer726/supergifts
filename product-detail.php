<?php
$pagename = basename($_SERVER['PHP_SELF']);

// DB Connection
$servername = "localhost";
$username   = "superehc_aiir";
$password   = "Aiir@8097000970";
$dbname     = "superehc_sgipl";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Get product ID from URL
$product_id = intval($_GET['id'] ?? 0);

if (!$product_id) {
    header("Location: index.php");
    exit();
}

// Fetch product details
$stmt = $conn->prepare("SELECT p.*, b.brandname FROM products p 
                       JOIN brandlogo b ON p.brand_id = b.id 
                       WHERE p.id = ? AND p.status = 1");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    header("Location: index.php");
    exit();
}

// Fetch related products (same brand)
$related_stmt = $conn->prepare("SELECT * FROM products 
                               WHERE brand_id = ? AND id != ? AND status = 1 
                               ORDER BY sequence ASC LIMIT 6");
$related_stmt->bind_param("ii", $product['brand_id'], $product_id);
$related_stmt->execute();
$related_products = $related_stmt->get_result();
$related_stmt->close();

// Image path handling
$imgUrl = $product['image']
    ? (($_SERVER['SERVER_NAME']==='localhost') ? '/supergifts/'.$product['image'] : '/'.$product['image'])
    : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('common/head.php'); ?>
    <title><?= htmlspecialchars($product['name']) ?> | <?= htmlspecialchars($product['brandname']) ?> — SGIPL</title>
    <style>
        .product-detail-hero {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 40px 0;
        }

        .product-image-wrap {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 16px;
            padding: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 400px;
            overflow: hidden;
        }

        .product-image-wrap img {
            max-width: 100%;
            max-height: 380px;
            object-fit: contain;
            width: auto;
        }

        .product-details-info {
            padding: 0 20px;
        }

        .product-title {
            font-size: 32px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .product-brand {
            display: inline-block;
            background: #f0f4f8;
            color: #0066cc;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 24px;
        }

        .product-price {
            font-size: 28px;
            font-weight: 700;
            color: #059669;
            margin-bottom: 20px;
        }

        .product-description {
            color: #555;
            font-size: 15px;
            line-height: 1.8;
            margin-bottom: 28px;
        }

        .product-cta {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 32px;
        }

        .btn-inquire {
            background: linear-gradient(135deg, #ff6b00 0%, #ff8c00 100%);
            color: #fff !important;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            font-size: 15px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-inquire:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 0, 0.3);
            color: #fff;
        }

        .btn-details {
            background: transparent;
            color: #0066cc !important;
            padding: 14px 32px;
            border: 2px solid #0066cc;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-details:hover {
            background: #f0f4f8;
            color: #0066cc;
        }

        .product-info-grid {
            background: #f8f9fa;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 32px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
        }

        .info-item {
            text-align: center;
        }

        .info-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a2e;
        }

        .related-products {
            padding: 60px 0;
            background: #f8f9fa;
        }

        .section-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 8px;
            text-align: center;
        }

        .section-subtitle {
            text-align: center;
            color: #999;
            margin-bottom: 40px;
            font-size: 14px;
        }

        .related-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
        }

        .related-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transform: translateY(-5px);
        }

        .related-img-wrap {
            background: #f5f5f5;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-bottom: 1px solid #e9ecef;
        }

        .related-img-wrap img {
            max-height: 180px;
            max-width: 100%;
            object-fit: contain;
            padding: 10px;
        }

        .related-info {
            padding: 16px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .related-name {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 8px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .related-price {
            font-size: 15px;
            font-weight: 700;
            color: #059669;
            margin-top: auto;
        }

        .breadcrumb-nav {
            background: #fff;
            padding: 16px 0;
            border-bottom: 1px solid #e9ecef;
            font-size: 13px;
        }

        .breadcrumb-nav a {
            color: #0066cc;
            text-decoration: none;
        }

        .breadcrumb-nav a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .product-title {
                font-size: 22px;
            }

            .product-price {
                font-size: 24px;
            }

            .product-cta {
                flex-direction: column;
            }

            .btn-inquire,
            .btn-details {
                width: 100%;
                text-align: center;
            }

            .product-image-wrap {
                min-height: 300px;
            }

            .section-title {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>

    <!-- Navigation -->
    <?php include('common/nav.php'); ?>

    <!-- Breadcrumb -->
    <div class="breadcrumb-nav container mt-3">
        <a href="index.php">Home</a> 
        <span class="mx-2">/</span>
        <a href="brand-products.php?brand=<?= $product['brand_id'] ?>">
            <?= htmlspecialchars($product['brandname']) ?>
        </a>
        <span class="mx-2">/</span>
        <span><?= htmlspecialchars($product['name']) ?></span>
    </div>

    <!-- Product Detail Hero Section -->
    <section class="product-detail-hero">
        <div class="container">
            <div class="row g-4 align-items-center">
                <!-- Product Image -->
                <div class="col-lg-6">
                    <div class="product-image-wrap">
                        <?php if ($imgUrl): ?>
                            <img src="<?= htmlspecialchars($imgUrl) ?>" 
                                 alt="<?= htmlspecialchars($product['name']) ?>"
                                 onerror="this.src='https://via.placeholder.com/400x300?text=Product+Image'">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/400x300?text=Product+Image" 
                                 alt="Product Image">
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="col-lg-6 product-details-info">
                    <!-- Brand Tag -->
                    <div class="product-brand">
                        <?= htmlspecialchars($product['brandname']) ?>
                    </div>

                    <!-- Product Title -->
                    <h1 class="product-title">
                        <?= htmlspecialchars($product['name']) ?>
                    </h1>

                    <!-- Price -->
                    <?php if ($product['mrp'] > 0): ?>
                        <div class="product-price">
                            ₹<?= number_format($product['mrp'], 2) ?>
                        </div>
                    <?php else: ?>
                        <div class="product-price text-muted">
                            Price on request
                        </div>
                    <?php endif; ?>

                    <!-- Description -->
                    <p class="product-description">
                        This premium <?= htmlspecialchars($product['brandname']) ?> product is crafted with quality and precision. 
                        It's perfect for corporate gifting, personal use, or as a premium gift for your valued clients and employees. 
                        Each product is carefully selected and tested to ensure it meets our high standards of quality.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="product-cta">
                        <a href="contact.php?product=<?= urlencode($product['name']) ?>&brand=<?= urlencode($product['brandname']) ?>" 
                           class="btn-inquire">
                            <i class="fa fa-envelope me-2"></i>Inquire Now
                        </a>
                        <a href="contact.php" class="btn-details">
                            <i class="fa fa-phone me-2"></i>Contact Us
                        </a>
                    </div>

                    <!-- Info Grid -->
                    <div class="product-info-grid">
                        <div class="info-item">
                            <div class="info-label">Brand</div>
                            <div class="info-value"><?= htmlspecialchars($product['brandname']) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Product ID</div>
                            <div class="info-value">#<?= $product['id'] ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Availability</div>
                            <div class="info-value" style="color: #059669;">In Stock</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products Section -->
    <?php if ($related_products->num_rows > 0): ?>
    <section class="related-products">
        <div class="container">
            <h2 class="section-title">More from <?= htmlspecialchars($product['brandname']) ?></h2>
            <p class="section-subtitle">Explore other premium products from this brand</p>

            <div class="row g-4">
                <?php while ($related = $related_products->fetch_assoc()):
                    $relatedImg = $related['image']
                        ? (($_SERVER['SERVER_NAME']==='localhost') ? '/supergifts/'.$related['image'] : '/'.$related['image'])
                        : '';
                ?>
                <div class="col-md-6 col-lg-4">
                    <a href="product-detail.php?id=<?= $related['id'] ?>" class="related-card">
                        <div class="related-img-wrap">
                            <?php if ($relatedImg): ?>
                                <img src="<?= htmlspecialchars($relatedImg) ?>" 
                                     alt="<?= htmlspecialchars($related['name']) ?>"
                                     onerror="this.src='https://via.placeholder.com/200x150?text=Product'">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/200x150?text=Product" 
                                     alt="Product Image">
                            <?php endif; ?>
                        </div>
                        <div class="related-info">
                            <div class="related-name">
                                <?= htmlspecialchars($related['name']) ?>
                            </div>
                            <?php if ($related['mrp'] > 0): ?>
                                <div class="related-price">
                                    ₹<?= number_format($related['mrp'], 2) ?>
                                </div>
                            <?php else: ?>
                                <div class="related-price text-muted">
                                    Price on request
                                </div>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Footer -->
    <?php include('common/footer.php'); ?>

    <!-- JS -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/all.js"></script>
    <script src="js/plugins.js"></script>

</body>

</html>
