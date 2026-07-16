<?php
// Database connection
require_once 'sgipl-manage/includes/db.php';

// Sanitize and validate brand parameter
$brand = isset($_GET['brand']) ? trim($_GET['brand']) : null;

// Security: Validate brand parameter (alphanumeric and common special characters only)
if (!$brand || !preg_match('/^[a-zA-Z0-9\s\-._]*$/', $brand)) {
    die("Error: Invalid brand name.");
}

// Use prepared statement to prevent SQL injection
$stmt = $conn->prepare("SELECT id, name, image, price, brand FROM products WHERE brand = ? ORDER BY id DESC");
$stmt->bind_param("s", $brand);
$stmt->execute();
$result = $stmt->get_result();

// Check if any products found
$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

function resolveBrandLogoPath($imageno) {
    $imageNo = intval($imageno);
    if ($imageNo <= 0) {
        return 'images/brandlogo/image1.jpg';
    }

    $baseDir = __DIR__ . '/images/brandlogo/';
    $extensions = ['.png', '.jpg', '.jpeg', '.gif', '.webp'];

    foreach ($extensions as $ext) {
        $filePath = $baseDir . 'image' . $imageNo . $ext;
        if (file_exists($filePath)) {
            return 'images/brandlogo/image' . $imageNo . $ext;
        }
    }

    return 'images/brandlogo/image' . $imageNo . '.jpg';
}

// Get brand logo path (assuming logos are stored in images/brandlogo/)
$brand_logo = resolveBrandLogoPath($brand ?? null);
$brand_logo_default = 'images/brandlogo/default.png';

// Check if brand logo exists, otherwise use default
if (!file_exists(__DIR__ . '/' . $brand_logo)) {
    $brand_logo = $brand_logo_default;
}

// PDF catalog path (you can modify this logic)
$pdf_catalog = 'catalogs/' . strtolower(str_replace(' ', '-', $brand)) . '-catalog.pdf';

$pagename = "brand.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'common/head.php'; ?>
    <style>
        .brand-hero-section {
            background: radial-gradient(circle at top left, rgba(15, 23, 42, 0.35), transparent 28%),
                        linear-gradient(180deg, #08111e 0%, #0d203f 55%, #081523 100%);
            color: #fff;
            padding: 70px 0 56px;
            position: relative;
            overflow: hidden;
        }

        .brand-hero-section::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 20% 30%, rgba(59, 130, 246, 0.18), transparent 26%),
                        radial-gradient(circle at 85% 10%, rgba(34, 197, 94, 0.12), transparent 20%);
            pointer-events: none;
        }

        .brand-hero-inner {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: minmax(320px, 1.2fr) minmax(320px, 0.8fr);
            gap: 30px;
            align-items: center;
        }

        .hero-copy {
            max-width: 720px;
        }

        .hero-copy h1 {
            font-size: clamp(40px, 5vw, 68px);
            line-height: 1.02;
            margin: 0 0 18px;
            letter-spacing: -0.04em;
            max-width: 780px;
        }

        .hero-copy p {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.86);
            margin: 0 0 28px;
            max-width: 680px;
        }

        .hero-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 14px;
        }

        .hero-badge,
        .catalog-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
        }

        .hero-badge {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 999px;
            padding: 12px 20px;
            color: #e2e8f0;
            font-size: 14px;
        }

        .catalog-btn {
            background: linear-gradient(135deg, #34d399 0%, #0ea5e9 100%);
            color: #0f172a;
            padding: 14px 26px;
            border-radius: 999px;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .catalog-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(56, 189, 248, 0.2);
        }

        .hero-panel {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 30px;
            padding: 24px;
            display: grid;
            gap: 20px;
            box-shadow: 0 28px 70px rgba(0, 0, 0, 0.18);
        }

        .hero-logo-block {
            background: #fff;
            border-radius: 20px;
            padding: 16px 20px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            min-width: 180px;
        }

        .hero-logo-block img {
            max-height: 68px;
            max-width: 220px;
            object-fit: contain;
        }

        .hero-feature {
            background: #071127;
            min-height: 280px;
            border-radius: 28px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .hero-feature img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .hero-price-box {
            background: rgba(15, 23, 42, 0.96);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 26px;
            padding: 24px;
            display: grid;
            gap: 12px;
        }

        .hero-price-label {
            font-size: 13px;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: #93c5fd;
            font-weight: 700;
        }

        .hero-price-value {
            font-size: clamp(34px, 4vw, 46px);
            font-weight: 800;
            color: #fff;
            margin: 0;
        }

        .hero-price-subtitle {
            font-size: 15px;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.78);
            margin: 0;
        }

        .hero-price-features {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .hero-price-feature {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 999px;
            color: #e2e8f0;
            padding: 10px 14px;
            font-size: 13px;
            line-height: 1.4;
        }
    </style>

        .products-section {
            padding: 50px 0 70px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .back-link {
            color: #1e3a8a;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
        }

        .product-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e6e8f0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100%;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        }

        .product-img-wrap {
            background: #f8fafc;
            min-height: 240px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-bottom: 1px solid #eef2ff;
        }

        .product-img-wrap img {
            width: auto;
            height: 100%;
            max-width: 100%;
            object-fit: contain;
        }

        .product-info {
            padding: 22px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            flex: 1;
        }

        .product-name {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            line-height: 1.35;
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-size: 18px;
            font-weight: 700;
            color: #16a34a;
        }

        .product-price em {
            font-style: normal;
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
        }

        .view-details-btn {
            margin-top: auto;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            padding: 12px 18px;
            border-radius: 12px;
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            transition: background 0.2s ease;
        }

        .view-details-btn:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
        }

        .no-products {
            text-align: center;
            padding: 70px 20px;
            color: #4b5563;
        }

        .no-products h3 {
            margin-bottom: 14px;
            font-size: 28px;
            color: #111827;
        }

        .no-products p {
            margin: 0 0 14px;
            color: #6b7280;
        }

        @media (max-width: 768px) {
            .brand-hero-section {
                padding: 40px 0 30px;
            }

            .brand-hero-card {
                padding: 24px;
            }

            .brand-hero-copy h1 {
                font-size: 32px;
            }

            .products-section {
                padding: 40px 0 50px;
            }
        }

        @media (max-width: 520px) {
            .brand-hero-card {
                flex-direction: column;
                align-items: stretch;
            }

            .brand-hero-meta {
                flex-direction: column;
                align-items: stretch;
            }

            .brand-logo-hero {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php include 'common/nav.php'; ?>

    <!-- Page Title Section -->
    <section class="page-title-wrap">
        <div class="page-title-inner">
            <div class="page-title-content">
                <h1 class="page-title"><?php echo htmlspecialchars($brand); ?> Products</h1>
            </div>
        </div>
    </section>

    <!-- Brand Hero Section -->
    <section class="brand-hero-section">
        <div class="container">
            <div class="brand-hero-inner">
                <div class="hero-copy">
                    <div class="hero-meta">
                        <div class="hero-badge">Recommended Online Shop</div>
                    </div>
                    <h1>Bring Home The Luxury Of The Ultimate Audio Technology</h1>
                    <p>Shop the latest premium sound systems from <?php echo htmlspecialchars($brand); ?> with exclusive deals, catalog downloads, and brand-certified support.</p>
                    <div class="hero-meta">
                        <div class="hero-badge"><?php echo count($products); ?> Products Available</div>
                        <?php if (file_exists($pdf_catalog)): ?>
                            <a href="<?php echo htmlspecialchars($pdf_catalog); ?>" class="catalog-btn" download>
                                <i class="fa fa-download"></i> Download Catalog
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="hero-panel">
                    <div class="hero-logo-block">
                        <img src="<?php echo htmlspecialchars($brand_logo); ?>" alt="<?php echo htmlspecialchars($brand); ?> Logo">
                    </div>
                    <div class="hero-feature">
                        <img src="images/banners/hero-product.png" alt="Featured Product">
                    </div>
                    <div class="hero-price-box">
                        <div class="hero-price-label">Launch Price</div>
                        <p class="hero-price-value">Rs 69,990</p>
                        <p class="hero-price-subtitle">Complimentary 3 Year Extended Warranty • Free Installation</p>
                        <div class="hero-price-features">
                            <span class="hero-price-feature">Dolby Atmos</span>
                            <span class="hero-price-feature">SBW600 Emperor</span>
                            <span class="hero-price-feature">12.1.4 Home Theatre</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section">
        <div class="container">
            <div class="top-bar">
                <a href="index.php" class="back-link">← Back to Home</a>
                <?php if (file_exists($pdf_catalog)): ?>
                    <a href="<?php echo htmlspecialchars($pdf_catalog); ?>" class="catalog-btn" download>
                        <i class="fa fa-download"></i> Download Catalog
                    </a>
                <?php endif; ?>
            </div>

            <?php if (!empty($products)): ?>
                <div class="products-grid">
                    <?php foreach ($products as $product): ?>
                        <a href="product-details.php?id=<?php echo urlencode($product['id']); ?>" class="product-card">
                            <div class="product-img-wrap">
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            </div>
                            <div class="product-info">
                                <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                                <div class="product-price">₹<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></div>
                                <span class="view-details-btn">View Details</span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-products">
                    <h3>No Products Found</h3>
                    <p>There are currently no products available for the <strong><?php echo htmlspecialchars($brand); ?></strong> brand.</p>
                    <p><a href="index.php" style="color: #1e3a8a; text-decoration: none; font-weight: 600;">← Back to Home</a></p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'common/footer.php'; ?>

    <!-- Scripts -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/all.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
