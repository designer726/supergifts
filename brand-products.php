<?php
$pagename = basename($_SERVER['PHP_SELF']);

// DB Connection
$servername = "localhost";
$username   = "superehc_aiir";      // ← Change for live server
$password   = "Aiir@8097000970";          // ← Change for live server
$dbname     = "superehc_sgipl"; // ← Change to your DB name
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Get brand slug from URL: brand-products.php?brand=blaupunkt
$brand = trim($_GET['brand'] ?? '');
if (!$brand) {
    header("Location: index");
    exit();
}

// Load brand
// $stmt = $conn->prepare("SELECT * FROM brandlogo WHERE slug = ? AND status = 1 LIMIT 1");
$stmt = $conn->prepare("SELECT * FROM brandlogo WHERE id = ? ");
$stmt->bind_param("s", $brand);
$stmt->execute();
$brand = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$brand) {
    header("Location: index");
    exit();
}

// Load products for this brand
$products = $conn->query("SELECT * FROM products WHERE brand_id = {$brand['id']} AND status = 1 ORDER BY sequence ASC, id ASC");
$productCount = $products->num_rows;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('common/head.php'); ?>
    <title><?= htmlspecialchars($brand['brandname']) ?> Products — SGIPL</title>
    <style>
        .product-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            overflow: hidden;
            transition: box-shadow 0.2s, transform 0.2s;
            height: 100%;
        }

        .product-card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.10);
            transform: translateY(-3px);
        }

        .product-img-wrap {
            background: #f8f9fa;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-bottom: 1px solid #f0f0f0;
        }

        .product-img-wrap img {
            max-height: 180px;
            max-width: 100%;
            object-fit: contain;
            padding: 10px;
        }

        .product-info {
            padding: 14px 16px 16px;
        }

        .product-name {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 6px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-mrp {
            font-size: 15px;
            font-weight: 700;
            color: #059669;
        }

        .brand-logo-hero {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 16px 28px;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.07);
        }

        .brand-logo-hero img {
            max-height: 60px;
            max-width: 200px;
            object-fit: contain;
        }

        .catalog-btn {
            background: linear-gradient(135deg, #c8a96e, #a07840);
            color: #fff !important;
            border: none;
            border-radius: 30px;
            padding: 10px 28px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: opacity 0.2s;
        }

        .catalog-btn:hover {
            opacity: 0.88;
            color: #fff;
        }

        .no-products {
            text-align: center;
            padding: 60px 20px;
            color: #888;
        }
    </style>
</head>

<body class="appear-animate">

    <div class="page-loader">
        <div class="loader">Loading...</div>
    </div>

    <div class="page" id="top">
        <?php include('common/nav.php'); ?>

        <main id="main">

            <!-- Brand Header -->
            <section class="page-section bg-gray-light-1 bg-light-alpha-90 parallax-5" style="background-image: url(images/full-width-images/section-bg-1.jpg)">
                <div class="container position-relative pt-30 pt-sm-50 pb-10">
                    <div class="text-center">

                        <!-- Brand Logo -->
                        <?php if ($brand['imageno']): ?>
                            <div class="mb-20">
                                <div class="brand-logo-hero d-inline-flex">
                                    <img src="<?= htmlspecialchars($brand['imageno']) ?>" alt="<?= htmlspecialchars($brand['brandname']) ?>">
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Brand Name -->
                        <h1 class="hs-title-1 mb-10">
                            <span class="wow charsAnimIn" data-splitting="chars"><?= htmlspecialchars($brand['brandname']) ?></span>
                        </h1>

                        <!-- Brand Type Badge -->
                        <div class="mb-20">
                            <?php if ($brand['flag'] == 1): ?>
                                <span style="background:#dbeafe;color:#1d4ed8;font-size:13px;padding:5px 16px;border-radius:20px;font-weight:600;">⭐ Authorised Brand Partner</span>
                            <?php else: ?>
                                <span style="background:#fef9c3;color:#854d0e;font-size:13px;padding:5px 16px;border-radius:20px;font-weight:600;">🤝 We Also Deal</span>
                            <?php endif; ?>
                        </div>

                        <p class="section-descr mb-0 wow fadeIn" data-wow-delay="0.2s">
                            <?= $productCount ?> Product<?= $productCount != 1 ? 's' : '' ?> Available
                        </p>
                    </div>
                </div>
            </section>

            <!-- Products + Catalog Button -->
            <section class="page-section">
                <div class="container relative">

                    <!-- Top Bar: Back + Catalog Download -->
                    <div class="d-flex align-items-center justify-content-between mb-40 flex-wrap gap-3">
                        <a href="javascript:history.back()" style="color:#666;text-decoration:none;font-size:14px;">
                            ← Back to Brands
                        </a>
                        <?php if ($brand['links']): ?>
                            <a href="<?= htmlspecialchars($brand['links']) ?>" target="_blank" class="catalog-btn">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                                </svg>
                                Download Catalog
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Products Grid -->
                    <?php if ($productCount > 0): ?>
                        <div class="row g-4">
                            <?php while ($product = $products->fetch_assoc()): ?>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="product-card">
                                        <div class="product-img-wrap">
                                            <?php if ($product['image']): ?>
                                                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                            <?php else: ?>
                                                <div style="text-align:center;color:#ccc;">
                                                    <svg width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                                                        <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z" />
                                                    </svg>
                                                    <div style="font-size:11px;margin-top:4px;">No Image</div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="product-info">
                                            <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>
                                            <?php if ($product['mrp'] > 0): ?>
                                                <div class="product-mrp">MRP: ₹<?= number_format($product['mrp'], 2) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>

                    <?php else: ?>
                        <div class="no-products">
                            <svg width="64" height="64" fill="#ddd" viewBox="0 0 16 16">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4z" />
                            </svg>
                            <h5 class="mt-3" style="color:#aaa;">No products added yet</h5>
                            <p style="color:#bbb;font-size:14px;">Products for this brand will appear here once added.</p>
                            <?php if ($brand['links']): ?>
                                <a href="<?= htmlspecialchars($brand['links']) ?>" target="_blank" class="catalog-btn mt-2">
                                    Download Catalog Instead
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                </div>
            </section>

            <hr class="mt-0 mb-0">
        </main>

        <?php include('common/footer.php'); ?>
    </div>
</body>

</html>