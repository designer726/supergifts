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
    <link rel="stylesheet" href="css/brand-products.css">
    <style>
        .brand-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 30px 0;
            margin-bottom: 40px;
            border-bottom: 2px solid #f0f0f0;
        }

        .brand-logo-container {
            text-align: center;
            flex: 1;
        }

        .brand-logo-container img {
            max-width: 150px;
            height: auto;
            object-fit: contain;
        }

        .brand-title {
            font-size: 32px;
            font-weight: 700;
            color: #222;
            margin: 15px 0 0 0;
        }

        .brand-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            align-items: center;
        }

        .download-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            text-decoration: none;
            color: white;
        }

        .download-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            margin-bottom: 60px;
        }

        .product-card {
            background: white;
            border: 1px solid #e8e8e8;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            background: #f5f5f5;
        }

        .product-info {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-name {
            font-size: 16px;
            font-weight: 600;
            color: #222;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .product-price {
            font-size: 18px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 15px;
        }

        .view-details-btn {
            background: #667eea;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 5px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: inline-block;
            margin-top: auto;
        }

        .view-details-btn:hover {
            background: #5568d3;
            text-decoration: none;
            color: white;
        }

        .no-products {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .no-products h3 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #222;
        }

        /* Responsive Grid */
        @media (max-width: 1200px) {
            .products-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }

            .brand-header {
                flex-direction: column;
                padding: 20px 0;
            }

            .brand-actions {
                width: 100%;
                margin-top: 20px;
                justify-content: center;
            }

            .download-btn {
                width: 100%;
            }

            .product-image {
                height: 200px;
            }

            .brand-title {
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: 1fr;
            }

            .brand-title {
                font-size: 20px;
            }

            .download-btn {
                font-size: 12px;
                padding: 10px 18px;
            }
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-title {
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 50px;
            color: #222;
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

    <!-- Brand Products Section -->
    <section class="section">
        <div class="container">
            
            <!-- Brand Header with Logo and Download Button -->
            <div class="brand-header">
                <div class="brand-logo-container">
                    <img src="<?php echo htmlspecialchars($brand_logo); ?>" alt="<?php echo htmlspecialchars($brand); ?> Logo">
                    <h2 class="brand-title"><?php echo htmlspecialchars($brand); ?></h2>
                </div>
                <div class="brand-actions">
                    <?php if (file_exists($pdf_catalog)): ?>
                        <a href="<?php echo htmlspecialchars($pdf_catalog); ?>" class="download-btn" download>
                            <i class="fa fa-download"></i> Download Catalog
                        </a>
                    <?php else: ?>
                        <button class="download-btn" disabled title="Catalog not available">
                            <i class="fa fa-download"></i> Download Catalog
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Products Grid -->
            <?php if (!empty($products)): ?>
                <div class="products-grid">
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <img 
                                src="<?php echo htmlspecialchars($product['image']); ?>" 
                                alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                class="product-image"
                            >
                            <div class="product-info">
                                <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                                <div class="product-price">₹<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></div>
                                <a href="product-details.php?id=<?php echo urlencode($product['id']); ?>" class="view-details-btn">
                                    View Details
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-products">
                    <h3>No Products Found</h3>
                    <p>There are currently no products available for the <strong><?php echo htmlspecialchars($brand); ?></strong> brand.</p>
                    <p><a href="index.php" style="color: #667eea; text-decoration: none; font-weight: 600;">← Back to Home</a></p>
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
