<?php
$pagename = basename($_SERVER['PHP_SELF']);

// DB Connection
$servername = "localhost";
$username   = "superehc_aiir";      // ← Change for live server
$password   = "Aiir@8097000970";          // ← Change for live server
$dbname     = "superehc_sgipl"; // ← Change to your DB name
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// ── Filters from query string ──
$selectedBrands = array_filter(array_map('intval', $_GET['brand'] ?? []));
$offerMin       = ($_GET['offer_min'] ?? '') !== '' ? floatval($_GET['offer_min']) : null;
$offerMax       = ($_GET['offer_max'] ?? '') !== '' ? floatval($_GET['offer_max']) : null;
$qtyMin         = ($_GET['qty_min'] ?? '') !== '' ? max(10, intval($_GET['qty_min'])) : 10;

// ── Build WHERE clause ──
$where  = ["p.status = 1", "p.quantity >= ?"];
$params = [$qtyMin];
$types  = 'i';

if ($selectedBrands) {
    $placeholders = implode(',', array_fill(0, count($selectedBrands), '?'));
    $where[] = "p.brand_id IN ($placeholders)";
    foreach ($selectedBrands as $bid) { $params[] = $bid; $types .= 'i'; }
}
if ($offerMin !== null) { $where[] = "p.offer_price >= ?"; $params[] = $offerMin; $types .= 'd'; }
if ($offerMax !== null) { $where[] = "p.offer_price <= ?"; $params[] = $offerMax; $types .= 'd'; }

$sql = "SELECT p.*, b.brandname FROM products p JOIN brandlogo b ON p.brand_id = b.id
        WHERE " . implode(' AND ', $where) . "
        ORDER BY b.brandname ASC, p.sequence ASC, p.id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$products = $stmt->get_result();
$productCount = $products->num_rows;
$stmt->close();

// ── Brands available for filtering (only brands with qualifying products) ──
$brandOptions = $conn->query("SELECT DISTINCT b.id, b.brandname FROM products p
                               JOIN brandlogo b ON p.brand_id = b.id
                               WHERE p.status = 1 AND p.quantity >= 10
                               ORDER BY b.brandname ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('common/head.php'); ?>
    <title>All Products — SGIPL</title>
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
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
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
            font-size: 13px;
            color: #999;
            text-decoration: line-through;
        }

        .product-offer {
            font-size: 15px;
            font-weight: 700;
            color: #059669;
        }

        .product-qty {
            font-size: 12px;
            color: #888;
            margin-top: 4px;
        }

        .filter-sidebar {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 20px;
        }

        .filter-sidebar h6 {
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 12px;
            color: #1a1a2e;
        }

        .filter-group {
            margin-bottom: 24px;
        }

        .filter-brand-list {
            max-height: 220px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .filter-brand-list .form-check {
            margin-bottom: 8px;
        }

        .filter-range-inputs {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-range-inputs input {
            width: 100%;
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

            <!-- Header -->
            <section class="page-section bg-gray-light-1 bg-light-alpha-90 parallax-5" style="background-image: url(images/full-width-images/section-bg-1.jpg)">
                <div class="container position-relative pt-30 pt-sm-50 pb-10">
                    <div class="text-center">
                        <h1 class="hs-title-1 mb-10">
                            <span class="wow charsAnimIn" data-splitting="chars">All Products</span>
                        </h1>
                        <p class="section-descr mb-0 wow fadeIn" data-wow-delay="0.2s">
                            <?= $productCount ?> Product<?= $productCount != 1 ? 's' : '' ?> Available
                        </p>
                    </div>
                </div>
            </section>

            <!-- Filters + Products -->
            <section class="page-section">
                <div class="container relative">
                    <div class="row g-4">

                        <!-- Left Filter Sidebar -->
                        <div class="col-lg-3">
                            <form method="GET" class="filter-sidebar">

                                <div class="filter-group">
                                    <h6>Brand</h6>
                                    <div class="filter-brand-list">
                                        <?php while ($b = $brandOptions->fetch_assoc()): ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="brand[]"
                                                       value="<?= $b['id'] ?>" id="brand-<?= $b['id'] ?>"
                                                       <?= in_array($b['id'], $selectedBrands) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="brand-<?= $b['id'] ?>">
                                                    <?= htmlspecialchars(ucfirst($b['brandname'])) ?>
                                                </label>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                </div>

                                <div class="filter-group">
                                    <h6>Offer Price (₹)</h6>
                                    <div class="filter-range-inputs">
                                        <input type="number" name="offer_min" class="form-control form-control-sm"
                                               placeholder="Min" min="0" value="<?= htmlspecialchars($_GET['offer_min'] ?? '') ?>">
                                        <span>—</span>
                                        <input type="number" name="offer_max" class="form-control form-control-sm"
                                               placeholder="Max" min="0" value="<?= htmlspecialchars($_GET['offer_max'] ?? '') ?>">
                                    </div>
                                </div>

                                <div class="filter-group mb-3">
                                    <h6>Minimum Quantity</h6>
                                    <input type="number" name="qty_min" class="form-control form-control-sm"
                                           min="10" placeholder="10" value="<?= $qtyMin ?>">
                                    <div class="text-muted small mt-1">Only products with 10+ quantity are shown.</div>
                                </div>

                                <button type="submit" class="btn btn-sm w-100 mb-2" style="background:linear-gradient(135deg,#c8a96e,#a07840);color:#fff;">
                                    Apply Filters
                                </button>
                                <a href="all-products.php" class="btn btn-sm btn-outline-secondary w-100">Clear Filters</a>
                            </form>
                        </div>

                        <!-- Products Grid -->
                        <div class="col-lg-9">
                            <?php if ($productCount > 0): ?>
                                <div class="row g-4">
                                    <?php while ($product = $products->fetch_assoc()): ?>
                                        <div class="col-lg-4 col-md-6 col-sm-6">
                                            <a href="product-detail.php?id=<?= $product['id'] ?>" style="text-decoration:none;color:inherit;display:block;">
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
                                                        <?php if ($product['offer_price'] > 0): ?>
                                                            <div class="product-offer">₹<?= number_format($product['offer_price'], 2) ?></div>
                                                            <?php if ($product['mrp'] > $product['offer_price']): ?>
                                                                <div class="product-mrp">₹<?= number_format($product['mrp'], 2) ?></div>
                                                            <?php endif; ?>
                                                        <?php elseif ($product['mrp'] > 0): ?>
                                                            <div class="product-offer">₹<?= number_format($product['mrp'], 2) ?></div>
                                                        <?php endif; ?>
                                                        <div class="product-qty">Qty available: <?= intval($product['quantity']) ?></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <div class="no-products">
                                    <svg width="64" height="64" fill="#ddd" viewBox="0 0 16 16">
                                        <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4z" />
                                    </svg>
                                    <h5 class="mt-3" style="color:#aaa;">No products match your filters</h5>
                                    <p style="color:#bbb;font-size:14px;">Try adjusting or clearing your filters.</p>
                                    <a href="all-products.php" class="btn btn-sm btn-outline-secondary mt-2">Clear Filters</a>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </section>

            <hr class="mt-0 mb-0">
        </main>

        <?php include('common/footer.php'); ?>
    </div>
</body>

</html>
