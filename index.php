<?php
$pagename = basename($_SERVER['PHP_SELF']);

/* ── helpers ── */
// function findBrandLogoPath($imageno) {
//     $root = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/supergifts/';
//     foreach (['.jpg','.jpeg','.png','.webp'] as $ext) {
//         $file = $root . 'images/brandlogo/image' . intval($imageno) . $ext;
//         if (file_exists($file)) return 'images/brandlogo/image' . intval($imageno) . $ext;
//     }
//     return 'images/brandlogo/image' . intval($imageno) . '.jpg';
// }

function findBrandLogoPath($imageno) {
    $root = __DIR__ . '/';

    foreach (['.jpg', '.jpeg', '.png', '.webp', '.gif'] as $ext) {
        $file = $root . 'images/brandlogo/image' . intval($imageno) . $ext;

        if (is_file($file)) {
            return 'images/brandlogo/image' . intval($imageno) . $ext;
        }
    }

    return 'images/brandlogo/image' . intval($imageno) . '.jpg';
}

function starRating($rating) {
    $full  = intval($rating);
    $empty = 5 - $full;
    return str_repeat('★', $full) . str_repeat('☆', $empty);
}

/* ── DB queries ── */
$brandPartners   = [];
$premiumProducts = [];
$selProducts     = [];
$blogPosts       = [];
$testimonials    = [];
$budgetLow = $budgetMid = $budgetHigh = $budgetPremium = [];
$applianceBrands = [];
$dbBanners       = [];   // banners from DB (up to 4)
$dbVouchers      = [];

/* Static fallback banners shown when no file is uploaded */
$staticBanners = [
    ['title' => 'Gifts That <span>Inspire</span> &amp; Build Bonds',
     'subtitle' => 'Premium branded products · Custom branding · Pan-India delivery.<br>Trusted by 500+ corporates across India.',
     'btn_text' => 'Get a Quote', 'btn_link' => 'contact',
     'badge' => "India's #1 B2B Corporate Gifting"],
    ['title' => 'Your Brand, <span>Our Expertise</span>',
     'subtitle' => 'In-house branding, embossing &amp; engraving — concept to delivery.<br>8 core services. 24×7 support.',
     'btn_text' => 'Our Services', 'btn_link' => 'services',
     'badge' => 'Customization Excellence'],
    ['title' => 'Bulk Orders <span>Made Easy</span>',
     'subtitle' => '5000+ products in stock. Quick co-branding.<br>Express 48-hr delivery across India.',
     'btn_text' => 'View Products', 'btn_link' => 'brand-products',
     'badge' => 'Bulk Promotional Swag'],
    ['title' => 'Pan-India <span>Logistics</span> At Your Service',
     'subtitle' => 'Nationwide delivery · Real-time tracking · Rush orders.<br>Trusted by 500+ brands.',
     'btn_text' => 'Contact Us', 'btn_link' => 'contact',
     'badge' => 'Fast & Reliable Delivery'],
];

$db = new mysqli("localhost","superehc_aiir","Aiir@8097000970","superehc_sgipl");

if (!$db->connect_error) {
    $db->set_charset("utf8mb4");

    $categoryCol = $db->query("SHOW COLUMNS FROM products LIKE 'category'");
    if ($categoryCol && $categoryCol->num_rows === 0) {
        $db->query("ALTER TABLE products ADD COLUMN category VARCHAR(20) NOT NULL DEFAULT 'NA' AFTER quantity");
        $db->query("UPDATE products SET category = CASE WHEN is_premium = 1 THEN 'Premium' ELSE 'NA' END");
    }

    $brandCategoryCol = $db->query("SHOW COLUMNS FROM brandlogo LIKE 'category'");
    if ($brandCategoryCol && $brandCategoryCol->num_rows === 0) {
        $db->query("ALTER TABLE brandlogo ADD COLUMN category VARCHAR(50) NOT NULL DEFAULT '' AFTER flag");
    }

    /* Homepage banners */
    $r = $db->query("SELECT * FROM banners WHERE status=1 ORDER BY slot ASC LIMIT 4");
    if ($r) while ($row = $r->fetch_assoc()) $dbBanners[$row['slot']] = $row;

    /* Authorised brand partners, grouped by category for the sectioned/auto-rotating display */
    $brandCategoryOrder = [
        'Electronics',
        'Electrical',
        'Home & Kitchen',
        'Travel & Luggage',
        'Apparels/Sports',
        'Lifestyle / Personal Hygiene',
        'Food & Beverages',
        'Large Home & Commercial Appliances',
    ];
    $brandPartnersByCategory = [];
    $r = $db->query("SELECT id, brandname, imageno, category FROM brandlogo WHERE flag=1 ORDER BY seqence ASC, brandname ASC");
    if ($r) while ($row = $r->fetch_assoc()) {
        $brand = array_merge($row, ['logoUrl' => findBrandLogoPath($row['imageno'])]);
        $brandPartners[] = $brand;
        $cat = $row['category'] ?: '';
        if ($cat !== '' && in_array($cat, $brandCategoryOrder, true)) {
            $brandPartnersByCategory[$cat][] = $brand;
        }
    }
    // Keep only categories that actually have brands assigned, in the fixed display order
    $brandPartnersByCategory = array_filter($brandPartnersByCategory, fn($list) => !empty($list));
    $brandCategorySections = [];
    foreach ($brandCategoryOrder as $cat) {
        if (!empty($brandPartnersByCategory[$cat])) $brandCategorySections[$cat] = $brandPartnersByCategory[$cat];
    }

    /* Category products for homepage carousels */
    $r = $db->query("SELECT p.id, p.name, p.image, p.mrp, p.offer_price, p.category, b.brandname, b.imageno
                     FROM products p JOIN brandlogo b ON p.brand_id=b.id
                     WHERE p.status=1 AND p.category='Premium' ORDER BY p.sequence ASC, p.id DESC LIMIT 20");
    if ($r) while ($row = $r->fetch_assoc())
        $premiumProducts[] = array_merge($row, ['brandLogoUrl' => findBrandLogoPath($row['imageno'])]);

    $categoryProducts = ['Executive' => [], 'Economy' => [], 'NA' => []];
    foreach (array_keys($categoryProducts) as $categoryName) {
        $categoryQuery = $db->prepare("SELECT p.id, p.name, p.image, p.mrp, p.offer_price, p.category, b.brandname, b.imageno
                                       FROM products p JOIN brandlogo b ON p.brand_id=b.id
                                       WHERE p.status=1 AND p.category=? ORDER BY p.sequence ASC, p.id DESC LIMIT 20");
        $categoryQuery->bind_param('s', $categoryName);
        $categoryQuery->execute();
        $categoryResult = $categoryQuery->get_result();
        while ($row = $categoryResult->fetch_assoc())
            $categoryProducts[$categoryName][] = array_merge($row, ['brandLogoUrl' => findBrandLogoPath($row['imageno'])]);
        $categoryQuery->close();
    }
    $executiveProducts = $categoryProducts['Executive'];
    $economyProducts = $categoryProducts['Economy'];

    /* Product selection — round-robin across brands: one product per brand per pass
       (brand A, brand B, brand C, ... then back to brand A's 2nd product, etc.)
       so a single brand never dominates consecutive cards. Includes qty=1 items. */
    $r = $db->query("SELECT p.id, p.name, p.image, p.mrp, p.offer_price, p.quantity, p.brand_id, p.category, b.brandname, b.imageno
                     FROM products p JOIN brandlogo b ON p.brand_id=b.id
                     WHERE p.status=1 AND p.quantity>=1 ORDER BY p.brand_id ASC, p.id DESC");
    $selByBrand = [];
    if ($r) while ($row = $r->fetch_assoc())
        $selByBrand[$row['brand_id']][] = array_merge($row, ['brandLogoUrl' => findBrandLogoPath($row['imageno'])]);
    while (!empty($selByBrand)) {
        foreach ($selByBrand as $bId => &$queue) {
            $selProducts[] = array_shift($queue);
            if (empty($queue)) unset($selByBrand[$bId]);
        }
        unset($queue);
    }

    
    /* Blog posts */
    $r = $db->query("SELECT id, title, slug, excerpt, image, category, created_at
                     FROM blogs WHERE status='published' ORDER BY created_at DESC LIMIT 6");
    if ($r) while ($row = $r->fetch_assoc()) $blogPosts[] = $row;

    /* Testimonials */
    $r = $db->query("SELECT client_name, company_name, rating, review_text
                     FROM reviews WHERE status='approved' AND is_hidden = 0 ORDER BY created_at DESC LIMIT 3");
    if ($r) while ($row = $r->fetch_assoc()) $testimonials[] = $row;

    /* Budget products by price range */
    $r = $db->query("SELECT p.id,p.name,p.image,p.mrp,p.offer_price FROM products p WHERE p.status=1 AND p.offer_price>=10 AND p.offer_price<=100 ORDER BY p.sequence ASC LIMIT 6");
    if ($r) while ($row=$r->fetch_assoc()) $budgetLow[]=$row;
    $r = $db->query("SELECT p.id,p.name,p.image,p.mrp,p.offer_price FROM products p WHERE p.status=1 AND p.offer_price>100 AND p.offer_price<=500 ORDER BY p.sequence ASC LIMIT 6");
    if ($r) while ($row=$r->fetch_assoc()) $budgetMid[]=$row;
    $r = $db->query("SELECT p.id,p.name,p.image,p.mrp,p.offer_price FROM products p WHERE p.status=1 AND p.offer_price>500 AND p.offer_price<=1000 ORDER BY p.sequence ASC LIMIT 6");
    if ($r) while ($row=$r->fetch_assoc()) $budgetHigh[]=$row;
    $r = $db->query("SELECT p.id,p.name,p.image,p.mrp,p.offer_price FROM products p WHERE p.status=1 AND p.offer_price>1000 ORDER BY p.sequence ASC LIMIT 6");
    if ($r) while ($row=$r->fetch_assoc()) $budgetPremium[]=$row;

    /* Brands for appliances section */
    $r = $db->query("SELECT id, brandname, imageno FROM brandlogo WHERE flag=0 ORDER BY seqence ASC, id ASC LIMIT 8");
    if ($r) while ($row = $r->fetch_assoc())
        $applianceBrands[] = array_merge($row, ['logoUrl' => findBrandLogoPath($row['imageno'])]);

    /* Gift vouchers */
    $r = $db->query("SELECT id, title, image FROM vouchers WHERE status=1 ORDER BY seqence ASC, id ASC LIMIT 8");
    if ($r) while ($row = $r->fetch_assoc()) $dbVouchers[] = $row;

    $db->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('common/head.php'); ?>
    <link rel="stylesheet" href="css/homepage.css?v=<?= @filemtime(__DIR__.'/css/homepage.css') ?: time() ?>">
</head>
<body class="hp-body appear-animate">

    <!-- Page Loader -->
    <div class="page-loader"><div class="loader">Loading...</div></div>

    <div class="page" id="top">

        <?php include('common/nav.php'); ?>

        <main id="main">

            <!-- ═══════════ HERO BANNER CAROUSEL (Dynamic) ═══════════ -->
            <?php
            /* Build the 4 slides — use DB banner if file uploaded, else static fallback */
            $slides = [];
            for ($s = 1; $s <= 4; $s++) {
                $db_b = $dbBanners[$s] ?? null;
                $st   = $staticBanners[$s - 1];
                $slides[] = [
                    'slot'      => $s,
                    'title'     => !empty($db_b['title'])    ? htmlspecialchars($db_b['title'])    : $st['title'],
                    'subtitle'  => !empty($db_b['subtitle']) ? htmlspecialchars($db_b['subtitle']) : $st['subtitle'],
                    'btn_text'  => !empty($db_b['btn_text']) ? htmlspecialchars($db_b['btn_text']) : $st['btn_text'],
                    'btn_link'  => !empty($db_b['btn_link']) ? htmlspecialchars($db_b['btn_link']) : $st['btn_link'],
                    'badge'     => $st['badge'],
                    'file_path' => $db_b['file_path'] ?? '',
                    'file_type' => $db_b['file_type'] ?? 'image',
                ];
            }
            $totalSlides = count($slides);
            ?>
            <div class="hp-banner-wrap" id="hpBannerWrap">
                <!-- Slides -->
                <div class="hp-banner-slides" id="hpBannerSlides">
                <?php foreach ($slides as $idx => $sl): $active = ($idx === 0); $isVideo = !empty($sl['file_path']) && $sl['file_type'] === 'video'; ?>
                <div class="hp-banner-slide <?= $active ? 'active' : '' ?>" data-slide="<?= $idx ?>" data-type="<?= $isVideo ? 'video' : 'image' ?>">
                    <!-- Background: uploaded image / video / gradient fallback -->
                    <?php if (!empty($sl['file_path'])): ?>
                        <?php if ($isVideo): ?>
                        <video class="hp-banner-bg-video" muted playsinline preload="metadata">
                            <source src="<?= htmlspecialchars($sl['file_path']) ?>">
                        </video>
                        <?php else: ?>
                        <div class="hp-banner-bg-img" style="background-image:url('<?= htmlspecialchars($sl['file_path']) ?>')"></div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="hp-banner-bg-gradient"></div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
                </div>

                <!-- Nav arrows -->
                <button class="hp-banner-arrow prev" onclick="hpBannerNav(-1)" aria-label="Previous">&#8249;</button>
                <button class="hp-banner-arrow next" onclick="hpBannerNav(1)"  aria-label="Next">&#8250;</button>

                <!-- Dots -->
                <div class="hp-hero-dots" id="hpBannerDots">
                    <?php for ($d = 0; $d < $totalSlides; $d++): ?>
                    <div class="hp-hero-dot <?= $d === 0 ? 'active' : '' ?>" onclick="hpBannerGo(<?= $d ?>)"></div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- ═══════════ AUTHORISED BRAND PARTNER ═══════════ -->
            <section class="brand-partner-sec">
                <div class="hp-sec-title">Authorised Brand Partner</div>
                <?php if (!empty($brandCategorySections)): ?>
                <div class="brand-cat-tabs" id="brandCatTabs">
                    <?php $catIdx = 0; foreach ($brandCategorySections as $catName => $catBrands): ?>
                    <button type="button" class="brand-cat-tab <?= $catIdx === 0 ? 'active' : '' ?>" data-cat-index="<?= $catIdx ?>">
                        <span class="brand-cat-tab-label"><?= htmlspecialchars($catName) ?></span>
                        <span class="brand-cat-tab-progress"></span>
                    </button>
                    <?php $catIdx++; endforeach; ?>
                </div>
                <div class="brand-cat-panels" id="brandCatPanels">
                    <?php $catIdx = 0; foreach ($brandCategorySections as $catName => $catBrands): ?>
                    <div class="brand-cat-panel <?= $catIdx === 0 ? 'active' : '' ?>" data-cat-index="<?= $catIdx ?>">
                        <div class="brand-logo-grid">
                            <?php foreach ($catBrands as $brand): ?>
                            <a class="brand-logo-box" href="brand-products.php?brand=<?= intval($brand['id']) ?>" title="<?= htmlspecialchars($brand['brandname']) ?>">
                                <img src="<?= htmlspecialchars($brand['logoUrl']) ?>" alt="<?= htmlspecialchars($brand['brandname']) ?>" loading="lazy">
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php $catIdx++; endforeach; ?>
                </div>
                <!-- <p class="brand-partner-more">&amp; many more...</p> -->
                <?php elseif (!empty($brandPartners)): ?>
                <div class="brand-logo-grid">
                    <?php foreach ($brandPartners as $brand): ?>
                    <a class="brand-logo-box" href="brand-products.php?brand=<?= intval($brand['id']) ?>" title="<?= htmlspecialchars($brand['brandname']) ?>">
                        <img src="<?= htmlspecialchars($brand['logoUrl']) ?>" alt="<?= htmlspecialchars($brand['brandname']) ?>">
                    </a>
                    <?php endforeach; ?>
                </div>
                <!-- <p class="brand-partner-more">&amp; many more...</p> -->
                <?php else: ?>
                <p style="color:#9CA3AF;font-size:14px;">Brand partners coming soon.</p>
                <?php endif; ?>
            </section>

             <!-- ═══════════ PAN INDIA INDIVIDUAL DELIVERY ═══════════ -->
            <div class="hp-pan-india">
                <h3>PAN India Individual Delivery Available</h3>
                <p>Delivering to 500+ cities across India — fast, reliable, and trackable</p>
            </div>

             <!-- ═══════════ AVAILABLE GIFT VOUCHERS ═══════════ -->
            <section class="hp-vouchers-sec">
                <div class="hp-sec-title">Available Gift Vouchers</div>
                <div class="hp-vouchers-track-wrapper">
                    <div class="hp-vouchers-track">
                        <?php if (!empty($dbVouchers)): ?>
                            <?php foreach (array_merge($dbVouchers, $dbVouchers) as $v): ?>
                            <a href="voucher-detail.php?id=<?= intval($v['id']) ?>" class="hp-voucher-card">
                                <img src="<?= htmlspecialchars($v['image']) ?>" alt="<?= htmlspecialchars($v['title']) ?>" loading="lazy">
                            </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php
                            $staticVouchers = [
                                ['icon' => '🛍️', 'label' => 'Shopping Voucher'],
                                ['icon' => '🍽️', 'label' => 'Dining Voucher'],
                                ['icon' => '✈️', 'label' => 'Travel Voucher'],
                                ['icon' => '💆', 'label' => 'Wellness Voucher'],
                                ['icon' => '🎬', 'label' => 'Entertainment'],
                                ['icon' => '📱', 'label' => 'Tech Voucher'],
                                ['icon' => '⚽', 'label' => 'Sports Voucher'],
                                ['icon' => '🎓', 'label' => 'Education Voucher'],
                            ];
                            foreach (array_merge($staticVouchers, $staticVouchers) as $v): ?>
                            <div class="hp-voucher-card">
                                <div class="hp-voucher-placeholder">
                                    <span class="icon"><?= $v['icon'] ?></span>
                                    <span><?= $v['label'] ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <!-- ═══════════ PREMIUM BRANDED PRODUCT ═══════════ -->
            <section class="hp-carousel-sec">
                <div class="hp-carousel-header">
                    <h2>Premium Branded Product</h2>
                    <div class="hp-carousel-nav">
                        <button class="hp-c-btn" onclick="scrollProd(this, -1)" aria-label="Previous">&#8249;</button>
                        <button class="hp-c-btn" onclick="scrollProd(this, 1)" aria-label="Next">&#8250;</button>
                    </div>
                </div>
                <div class="hp-carousel-outer">
                    <div class="hp-carousel-track">
                        <?php if (!empty($premiumProducts)): ?>
                            <?php foreach ($premiumProducts as $p): ?>
                            <a href="product-detail.php?id=<?= intval($p['id']) ?>" class="hp-prod-card" style="text-decoration:none;color:inherit;">
                                <div class="hp-prod-card-image">
                                    <?php if (!empty($p['image'])): ?>
                                    <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
                                    <?php else: ?>
                                    <div style="display:flex;align-items:center;justify-content:center;height:100%;color:#9CA3AF;font-size:36px;">🎁</div>
                                    <?php endif; ?>
                                </div>
                                <div class="hp-prod-card-body">
                                    <div class="hp-prod-brand-logo">
                                        <img src="<?= htmlspecialchars($p['brandLogoUrl']) ?>" alt="<?= htmlspecialchars($p['brandname']) ?>" loading="lazy" onerror="this.parentElement.style.display='none'">
                                    </div>
                                    <div class="hp-prod-name"><?= htmlspecialchars($p['name']) ?></div>
                                    <?php if (!empty($p['offer_price']) && $p['offer_price'] > 0 && $p['offer_price'] < $p['mrp']): ?>
                                    <div class="hp-prod-price-row">
                                        <div class="hp-prod-price">₹<?= number_format($p['offer_price'], 0) ?>/-</div>
                                        <!-- <div class="hp-prod-mrp">₹<?= number_format($p['mrp'], 0) ?>/-</div> -->
                                    </div>
                                    <?php elseif ($p['offer_price'] > 0): ?>
                                    <div class="hp-prod-price">₹<?= number_format($p['offer_price'], 0) ?>/-</div>
                                    <?php else: ?>
                                    <div class="hp-prod-price">Price on Request</div>
                                    <?php endif; ?>
                                    <div class="hp-prod-mrp">₹<?= number_format($p['mrp'], 0) ?>/-</div>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="padding:40px;color:#9CA3AF;font-size:14px;">Products coming soon.</div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php $pages = max(1, ceil(count($premiumProducts) / 4)); ?>
                <div class="hp-carousel-dots">
                    <?php for ($i = 0; $i < $pages; $i++): ?>
                    <div class="hp-carousel-dot <?= $i === 0 ? 'active' : '' ?>" onclick="goToProdPage(this, <?= $i ?>)"></div>
                    <?php endfor; ?>
                </div>
            </section>

            <!-- ═══════════ EXECUTIVE BRANDED PRODUCT ═══════════ -->
            <section class="hp-carousel-sec">
                <div class="hp-carousel-header">
                    <h2>Executive Product</h2>
                    <div class="hp-carousel-nav">
                        <button class="hp-c-btn" onclick="scrollProd(this, -1)" aria-label="Previous">&#8249;</button>
                        <button class="hp-c-btn" onclick="scrollProd(this, 1)" aria-label="Next">&#8250;</button>
                    </div>
                </div>
                <div class="hp-carousel-outer">
                    <div class="hp-carousel-track">
                        <?php if (!empty($executiveProducts)): ?>
                            <?php foreach ($executiveProducts as $p): ?>
                            <a href="product-detail.php?id=<?= intval($p['id']) ?>" class="hp-prod-card" style="text-decoration:none;color:inherit;">
                                <div class="hp-prod-card-image">
                                    <?php if (!empty($p['image'])): ?>
                                    <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
                                    <?php else: ?>
                                    <div style="display:flex;align-items:center;justify-content:center;height:100%;color:#9CA3AF;font-size:36px;">🎁</div>
                                    <?php endif; ?>
                                </div>
                                <div class="hp-prod-card-body">
                                    <div class="hp-prod-brand-logo">
                                        <img src="<?= htmlspecialchars($p['brandLogoUrl']) ?>" alt="<?= htmlspecialchars($p['brandname']) ?>" loading="lazy" onerror="this.parentElement.style.display='none'">
                                    </div>
                                    <div class="hp-prod-name"><?= htmlspecialchars($p['name']) ?></div>
                                    <?php if (!empty($p['offer_price']) && $p['offer_price'] > 0 && $p['offer_price'] < $p['mrp']): ?>
                                    <div class="hp-prod-price-row">
                                        <div class="hp-prod-price">₹<?= number_format($p['offer_price'], 0) ?>/-</div>
                                        <!-- <div class="hp-prod-mrp">₹<?= number_format($p['mrp'], 0) ?>/-</div> -->
                                    </div>
                                    <?php elseif ($p['offer_price'] > 0): ?>
                                    <div class="hp-prod-price">₹<?= number_format($p['offer_price'], 0) ?>/-</div>
                                    <?php else: ?>
                                    <div class="hp-prod-price">Price on Request</div>
                                    <?php endif; ?>
                                    <div class="hp-prod-mrp">₹<?= number_format($p['mrp'], 0) ?>/-</div>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="padding:40px;color:#9CA3AF;font-size:14px;">Products coming soon.</div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php $pages = max(1, ceil(count($executiveProducts) / 4)); ?>
                <div class="hp-carousel-dots">
                    <?php for ($i = 0; $i < $pages; $i++): ?>
                    <div class="hp-carousel-dot <?= $i === 0 ? 'active' : '' ?>" onclick="goToProdPage(this, <?= $i ?>)"></div>
                    <?php endfor; ?>
                </div>
            </section>

            <!-- ═══════════ ECONOMY BRANDED PRODUCT ═══════════ -->
            <section class="hp-carousel-sec">
                <div class="hp-carousel-header">
                    <h2>Economy Product</h2>
                    <div class="hp-carousel-nav">
                        <button class="hp-c-btn" onclick="scrollProd(this, -1)" aria-label="Previous">&#8249;</button>
                        <button class="hp-c-btn" onclick="scrollProd(this, 1)" aria-label="Next">&#8250;</button>
                    </div>
                </div>
                <div class="hp-carousel-outer">
                    <div class="hp-carousel-track">
                        <?php if (!empty($economyProducts)): ?>
                            <?php foreach ($economyProducts as $p): ?>
                            <a href="product-detail.php?id=<?= intval($p['id']) ?>" class="hp-prod-card" style="text-decoration:none;color:inherit;">
                                <div class="hp-prod-card-image">
                                    <?php if (!empty($p['image'])): ?>
                                    <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
                                    <?php else: ?>
                                    <div style="display:flex;align-items:center;justify-content:center;height:100%;color:#9CA3AF;font-size:36px;">🎁</div>
                                    <?php endif; ?>
                                </div>
                                <div class="hp-prod-card-body">
                                    <div class="hp-prod-brand-logo">
                                        <img src="<?= htmlspecialchars($p['brandLogoUrl']) ?>" alt="<?= htmlspecialchars($p['brandname']) ?>" loading="lazy" onerror="this.parentElement.style.display='none'">
                                    </div>
                                    <div class="hp-prod-name"><?= htmlspecialchars($p['name']) ?></div>
                                    <?php if (!empty($p['offer_price']) && $p['offer_price'] > 0 && $p['offer_price'] < $p['mrp']): ?>
                                    <div class="hp-prod-price-row">
                                        <div class="hp-prod-price">₹<?= number_format($p['offer_price'], 0) ?>/-</div>
                                        <!-- <div class="hp-prod-mrp">₹<?= number_format($p['mrp'], 0) ?>/-</div> -->
                                    </div>
                                    <?php elseif ($p['offer_price'] > 0): ?>
                                    <div class="hp-prod-price">₹<?= number_format($p['offer_price'], 0) ?>/-</div>
                                    <?php else: ?>
                                    <div class="hp-prod-price">Price on Request</div>
                                    <?php endif; ?>
                                    <div class="hp-prod-mrp">₹<?= number_format($p['mrp'], 0) ?>/-</div>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="padding:40px;color:#9CA3AF;font-size:14px;">Products coming soon.</div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php $pages = max(1, ceil(count($economyProducts) / 4)); ?>
                <div class="hp-carousel-dots">
                    <?php for ($i = 0; $i < $pages; $i++): ?>
                    <div class="hp-carousel-dot <?= $i === 0 ? 'active' : '' ?>" onclick="goToProdPage(this, <?= $i ?>)"></div>
                    <?php endfor; ?>
                </div>
            </section>

            <!-- ═══════════ LARGE HOME & COMMERCIAL APPLIANCES ═══════════ -->
            <!-- <section class="hp-appliances-sec">
                <div class="hp-sec-title">Large Home &amp; Commercial Appliances</div>
                <?php if (!empty($applianceBrands)): ?>
                <div class="hp-appliances-grid">
                    <?php foreach ($applianceBrands as $brand): ?>
                    <a href="brand-products.php?brand=<?= intval($brand['id']) ?>" class="hp-appliance-box" title="<?= htmlspecialchars($brand['brandname']) ?>">
                        <img src="<?= htmlspecialchars($brand['logoUrl']) ?>" alt="<?= htmlspecialchars($brand['brandname']) ?>">
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div style="color:#9CA3AF;font-size:14px;padding:20px 0;">Brands coming soon.</div>
                <?php endif; ?>
            </section>    -->

            <!-- ═══════════ PRODUCT SELECTION ═══════════ -->
            <section class="hp-sel-sec">
                <div class="hp-sel-header">
                    <h3>Product Selection</h3>
                    <a href="all-products.php" class="hp-see-all">View All →</a>
                </div>
                <?php if (!empty($selProducts)): ?>
                <div class="hp-sel-track-wrapper">
                    <div class="hp-sel-track">
                        <?php
                        /* Duplicate the set so the auto-scroll loops seamlessly */
                        foreach (array_merge($selProducts, $selProducts) as $p): ?>
                        <a href="product-detail.php?id=<?= intval($p['id']) ?>" class="hp-sel-card">
                            <div class="hp-sel-img-wrap">
                                <?php if (!empty($p['image'])): ?>
                                <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
                                <?php else: ?>
                                <div style="display:flex;align-items:center;justify-content:center;height:100%;background:#F4F3F8;font-size:32px;color:#9CA3AF;">🎁</div>
                                <?php endif; ?>
                                <!--<div class="hp-qty-badge">-->
                                    <!-- <strong>500+</strong> -->
                                <!--     <div><?= htmlspecialchars($p['quantity']) ?></div>-->
                                <!--    Available Qty.-->
                                <!--</div>-->
                            </div>
                            <div class="hp-sel-info">
                                <div class="hp-prod-brand-logo">
                                    <img src="<?= htmlspecialchars($p['brandLogoUrl']) ?>" alt="<?= htmlspecialchars($p['brandname']) ?>" loading="lazy" onerror="this.parentElement.style.display='none'">
                                </div>
                                <div class="hp-sel-name"><?= htmlspecialchars($p['name']) ?></div>
                                <div class="hp-sel-cat"><?= htmlspecialchars($p['category'] ?? 'NA') ?></div>
                                <?php if (!empty($p['offer_price']) && $p['offer_price'] > 0 && $p['offer_price'] < $p['mrp']): ?>
                                <div class="hp-sel-price-row">
                                    <div class="hp-sel-price">₹<?= number_format($p['offer_price'], 0) ?>/-</div>
                                    <!-- <div class="hp-sel-mrp">₹<?= number_format($p['mrp'], 0) ?>/-</div> -->
                                </div>
                                <?php elseif ($p['offer_price'] > 0): ?>
                                <div class="hp-sel-price">₹<?= number_format($p['offer_price'], 0) ?>/-</div>
                                <?php else: ?>
                                <div class="hp-sel-price">Price on Request</div>
                                <?php endif; ?>
                                <div class="hp-sel-mrp">₹<?= number_format($p['mrp'], 0) ?>/-</div>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php else: ?>
                <p style="color:#9CA3AF;font-size:14px;padding:20px 0;">Products coming soon.</p>
                <?php endif; ?>
            </section>

            <!-- ═══════════ MADE TO ORDER – BUDGET FRIENDLY ═══════════ -->
            <section class="hp-budget-sec">
                <div class="hp-budget-title">Made to Order <span>Budget Friendly</span> Gift Options</div>

                <?php
                $budgetRanges = [
                    ['label' => '₹10 – ₹100',    'data' => $budgetLow],
                    ['label' => '₹100 – ₹500',   'data' => $budgetMid],
                    ['label' => '₹500 – ₹1000',  'data' => $budgetHigh],
                    ['label' => '₹1000 & Above', 'data' => $budgetPremium],
                ];
                ?>

                <!-- Tabs -->
                <div class="hp-budget-tabs">
                    <?php foreach ($budgetRanges as $idx => $range): ?>
                    <button type="button" class="hp-budget-tab<?= $idx === 0 ? ' active' : '' ?>" onclick="hpBudgetSwitch(this, <?= $idx ?>)">
                        <?= $range['label'] ?>
                    </button>
                    <?php endforeach; ?>
                </div>

                <!-- Panels -->
                <?php foreach ($budgetRanges as $idx => $range): ?>
                <div class="hp-budget-panel<?= $idx === 0 ? ' active' : '' ?>" data-budget-panel="<?= $idx ?>">
                    <?php $items = $range['data']; if (!empty($items)): ?>
                    <div class="hp-budget-track-wrapper">
                        <div class="hp-budget-track">
                            <?php
                            /* Duplicate the set so the auto-scroll loops seamlessly */
                            foreach (array_merge($items, $items) as $item):
                            ?>
                            <a href="product-detail.php?id=<?= intval($item['id']) ?>" class="hp-budget-card" title="<?= htmlspecialchars($item['name']) ?>">
                                <div class="hp-budget-card-img">
                                    <?php if (!empty($item['image'])): ?>
                                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" loading="lazy">
                                    <?php else: ?>
                                    <span class="hp-budget-card-ph">GIFT</span>
                                    <?php endif; ?>
                                </div>
                                <div class="hp-budget-card-body">
                                    <div class="hp-budget-card-name"><?= htmlspecialchars($item['name']) ?></div>
                                    <?php if (!empty($item['offer_price']) && $item['offer_price'] > 0 && $item['offer_price'] < $item['mrp']): ?>
                                    <div class="hp-budget-card-price-row">
                                        <div class="hp-budget-card-price">₹<?= number_format($item['offer_price'], 0) ?></div>
                                        <div class="hp-budget-card-mrp">₹<?= number_format($item['mrp'], 0) ?></div>
                                    </div>
                                    <?php elseif ($item['offer_price'] > 0): ?>
                                    <div class="hp-budget-card-price">₹<?= number_format($item['offer_price'], 0) ?></div>
                                    <?php endif; ?>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php else: ?>
                    <p style="text-align:center;color:#9CA3AF;font-size:14px;padding:20px 0;">No products in this range yet.</p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </section>

            <!-- ═══════════ BULK PROMOTIONAL SWAG ═══════════ -->
            <section class="hp-bulk-sec">
                <div class="hp-bulk-title">Bulk <span>Promotional Swag</span></div>

                <div class="hp-bulk-steps">
                    <div class="hp-bulk-step-wrap">
                        <div class="hp-bulk-step" style="background-image:linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 100%), url('images/warehouse.jpg');">
                            <div class="hp-bulk-step-val">5000+</div>
                        </div>
                        <div class="hp-bulk-step-label">Ready to Go Inventory</div>
                        <div class="hp-bulk-step-sub">Units available in stock, ready for immediate co-branding</div>
                    </div>
                    <div class="hp-bulk-arrow">→</div>
                    <div class="hp-bulk-step-wrap">
                        <div class="hp-bulk-step" style="background-image:linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 100%), url('images/printing.jpg');">
                            <div class="hp-bulk-step-val">Quick</div>
                        </div>
                        <div class="hp-bulk-step-label">Co-Branding Option</div>
                        <div class="hp-bulk-step-sub">Your logo printed or embossed on any product within hours</div>
                    </div>
                    <div class="hp-bulk-arrow">→</div>
                    <div class="hp-bulk-step-wrap">
                        <div class="hp-bulk-step" style="background-image:linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 100%), url('images/logestic.jpg');">
                            <div class="hp-bulk-step-val">Express</div>
                        </div>
                        <div class="hp-bulk-step-label">Pan India Delivery</div>
                        <div class="hp-bulk-step-sub">Through India Post, Blue Dart, Delhivery, & Express Bees</div>
                    </div>
                </div>
            </section>

            <!-- ═══════════ LOOKING FOR LONGTERM GIFTING PARTNERS ═══════════ -->
            <section class="hp-partners-sec">
                <div class="hp-partners-title">Looking for <span>Long-term Collaboration</span></div>
                <div class="hp-partners-boxes">
                    <div class="hp-partner-box hp-box-1">
                        <div class="hp-partner-content">
                            New Hiring / On-Boarding Kits
                        </div>
                    </div>

                    <div class="hp-partner-box hp-box-2">
                        <div class="hp-partner-content">
                            Consumer, Dealer, Distributor &amp; Trade Scheme
                        </div>
                    </div>

                    <div class="hp-partner-box hp-box-3">
                        <div class="hp-partner-content">
                            Branded Logo Store for Corporate Clients
                        </div>
                    </div>
                </div>
                <!-- <a href="contact" class="hp-tieup-btn">TIE UP NOW</a> -->
                <p class="hp-tieup-tagline">We store the selected product and ship globally on demand!</p>
            </section>

            <!-- ═══════════ CURRENT UPDATES ═══════════ -->
            <section class="hp-updates-sec">
                <div class="hp-updates-header">
                    <h2>Current Updates</h2>
                    <a href="blog" class="hp-see-all">See all →</a>
                </div>
                <div class="hp-updates-slider">
                    <div class="hp-updates-arrow" onclick="var s=document.getElementById('updatesScroll');s.scrollLeft -= s.clientWidth">&#8249;</div>
                    <div style="overflow:hidden;flex:1;">
                        <div id="updatesScroll" style="display:flex;gap:16px;overflow-x:auto;scroll-behavior:smooth;scrollbar-width:none;">
                            <?php if (!empty($blogPosts)): ?>
                                <?php foreach ($blogPosts as $blog): ?>
                                <!--<a href="blog_details?BlogDetails=<?= htmlspecialchars($blog['slug']) ?>" class="hp-blog-card" style="min-width:calc(33.333% - 11px);max-width:calc(33.333% - 11px);flex-shrink:0;">-->
                                <a href="blog_details?BlogDetails=<?= htmlspecialchars($blog['slug']) ?>" class="hp-blog-card">
                                    <div class="hp-blog-img">
                                        <?php if (!empty($blog['image'])): ?>
                                        <img src="<?= htmlspecialchars($blog['image']) ?>" alt="<?= htmlspecialchars($blog['title']) ?>" loading="lazy">
                                        <?php else: ?>
                                        <div style="display:flex;align-items:center;justify-content:center;height:100%;background:#F4F3F8;color:#9CA3AF;font-size:32px;">📰</div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="hp-blog-body">
                                        <div class="hp-blog-cat"><?= htmlspecialchars($blog['category']) ?></div>
                                        <div class="hp-blog-title"><?= htmlspecialchars($blog['title']) ?></div>
                                        <!-- <div class="hp-blog-excerpt"><?= htmlspecialchars($blog['excerpt']) ?></div> -->
                                    </div>
                                </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div style="padding:40px;color:#9CA3AF;font-size:14px;">No updates available yet.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="hp-updates-arrow" onclick="var s=document.getElementById('updatesScroll');s.scrollLeft += s.clientWidth">&#8250;</div>
                </div>
            </section>

            <!-- ═══════════ WHAT CLIENTS SAY ═══════════ -->
            <section class="hp-reviews-sec">
                <div class="hp-sec-title">What Clients & Brand Say</div>
                <div class="hp-reviews-grid">
                <?php if (!empty($testimonials)):
                    foreach ($testimonials as $rev): ?>
                    <div class="hp-review-card">
                        <div class="hp-review-stars"><?= starRating($rev['rating']) ?></div>
                        <div class="hp-review-text">"<?= htmlspecialchars($rev['review_text']) ?>"</div>
                        <div class="hp-reviewer">
                            <div class="hp-reviewer-avatar"><?= strtoupper(mb_substr($rev['client_name'],0,1)) ?></div>
                            <div>
                                <div class="hp-reviewer-name"><?= htmlspecialchars($rev['client_name']) ?></div>
                                <div class="hp-reviewer-role"><?= htmlspecialchars($rev['company_name']) ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach;
                else: ?>
                    <div class="hp-review-card">
                        <div class="hp-review-stars">★★★★★</div>
                        <div class="hp-review-text">"Super Gifts delivered 5,000 custom gift boxes flawlessly. Every pack was perfectly branded and arrived on time. Exceptional service!"</div>
                        <div class="hp-reviewer">
                            <div class="hp-reviewer-avatar">R</div>
                            <div><div class="hp-reviewer-name">Rahul Mehta</div><div class="hp-reviewer-role">Procurement Head, TCS</div></div>
                        </div>
                    </div>
                    <div class="hp-review-card">
                        <div class="hp-review-stars">★★★★★</div>
                        <div class="hp-review-text">"We've been ordering quarterly for 2 years. Product quality is consistently excellent and the after-sales support is second to none."</div>
                        <div class="hp-reviewer">
                            <div class="hp-reviewer-avatar" style="background:#0B7A43;">P</div>
                            <div><div class="hp-reviewer-name">Priya Sharma</div><div class="hp-reviewer-role">HR Manager, Infosys</div></div>
                        </div>
                    </div>
                    <div class="hp-review-card">
                        <div class="hp-review-stars">★★★★☆</div>
                        <div class="hp-review-text">"The bulk order facility and inventory management saved us weeks of effort. Highly recommend for large enterprise gifting needs."</div>
                        <div class="hp-reviewer">
                            <div class="hp-reviewer-avatar" style="background:#FFD400;color:#241C6B;">A</div>
                            <div><div class="hp-reviewer-name">Arjun Nair</div><div class="hp-reviewer-role">Operations Lead, HDFC</div></div>
                        </div>
                    </div>
                <?php endif; ?>
                </div>
            </section>
        </main>

        <?php include('common/footer.php'); ?>
        <!-- footer.php closes .page div and loads jQuery + all.js -->

    <script>
    /* ── Hero Banner Carousel ──
       Images stay for 3s. Videos play fully (once) then advance. */
    (function() {
        var total       = <?= $totalSlides ?>;
        var current     = 0;
        var slides      = document.querySelectorAll('.hp-banner-slide');
        var dots        = document.querySelectorAll('#hpBannerDots .hp-hero-dot');
        var advanceTimer = null;
        var IMAGE_DURATION = 3000;
        var VIDEO_FALLBACK = 20000; // safety net if a video fails to load/play

        function stopVideo(slide) {
            var v = slide.querySelector('.hp-banner-bg-video');
            if (v) { v.onended = null; v.pause(); v.currentTime = 0; }
        }

        function playVideo(slide) {
            var v = slide.querySelector('.hp-banner-bg-video');
            if (!v) return;
            v.onended = function() { hpBannerNav(1); };
            v.currentTime = 0;
            v.play().catch(function() { /* autoplay blocked — fallback timer still advances */ });
        }

        function show(idx) {
            clearTimeout(advanceTimer);
            slides.forEach(function(s, i) {
                var isActive = i === idx;
                s.classList.toggle('active', isActive);
                if (!isActive) stopVideo(s);
            });
            dots.forEach(function(d, i) { d.classList.toggle('active', i === idx); });
            current = idx;

            var activeSlide = slides[idx];
            if (activeSlide && activeSlide.dataset.type === 'video') {
                playVideo(activeSlide);
                advanceTimer = setTimeout(function() { hpBannerNav(1); }, VIDEO_FALLBACK);
            } else {
                advanceTimer = setTimeout(function() { hpBannerNav(1); }, IMAGE_DURATION);
            }
        }

        window.hpBannerNav = function(dir) {
            show((current + dir + total) % total);
        };

        window.hpBannerGo = function(idx) {
            show(idx);
        };

        show(0);

        /* Swipe support */
        var touchX = 0;
        var wrap = document.getElementById('hpBannerWrap');
        if (wrap) {
            wrap.addEventListener('touchstart', function(e) { touchX = e.changedTouches[0].screenX; }, {passive:true});
            wrap.addEventListener('touchend',   function(e) {
                var dx = e.changedTouches[0].screenX - touchX;
                if (Math.abs(dx) > 40) hpBannerNav(dx < 0 ? 1 : -1);
            }, {passive:true});
        }
    })();

    /* ── Product Carousels (Premium / Executive / Economy — each scrolls independently) ── */
    (function() {
        function perPage() {
            if (window.innerWidth < 540)  return 1;
            if (window.innerWidth < 820)  return 2;
            if (window.innerWidth < 1100) return 3;
            return 4;
        }

        document.querySelectorAll('.hp-carousel-sec').forEach(function(section) {
            var track = section.querySelector('.hp-carousel-track');
            var cards = track ? Array.from(track.querySelectorAll('.hp-prod-card')) : [];
            if (!track || !cards.length) return;

            var dots = Array.from(section.querySelectorAll('.hp-carousel-dot'));
            var page = 0;
            var timer;

            function totalPages() {
                return Math.max(1, Math.ceil(cards.length / perPage()));
            }

            function update() {
                var pp    = perPage();
                var cardW = cards[0].getBoundingClientRect().width + 16;
                track.style.transform = 'translateX(-' + (page * pp * cardW) + 'px)';
                dots.forEach(function(d, i) { d.classList.toggle('active', i === page); });
            }

            function goTo(p) {
                var tp = totalPages();
                page = ((p % tp) + tp) % tp;
                update();
            }

            function restartAutoplay() {
                clearInterval(timer);
                timer = setInterval(function() { goTo(page + 1); }, 5000);
            }

            section.__carousel = { scroll: function(dir) { goTo(page + dir); }, goTo: goTo, restartAutoplay: restartAutoplay };

            update();
            window.addEventListener('resize', function() {
                page = Math.min(page, totalPages() - 1);
                update();
            });

            restartAutoplay();
        });

        window.scrollProd = function(btn, dir) {
            var section = btn.closest('.hp-carousel-sec');
            if (section && section.__carousel) {
                section.__carousel.scroll(dir);
                section.__carousel.restartAutoplay();
            }
        };

        window.goToProdPage = function(dot, p) {
            var section = dot.closest('.hp-carousel-sec');
            if (section && section.__carousel) {
                section.__carousel.goTo(p);
                section.__carousel.restartAutoplay();
            }
        };
    })();

    /* ── Authorised Brand Partner category rotation ── */
    (function() {
        var tabs   = Array.from(document.querySelectorAll('#brandCatTabs .brand-cat-tab'));
        var panels = Array.from(document.querySelectorAll('#brandCatPanels .brand-cat-panel'));
        if (!tabs.length || !panels.length) return;

        var current = 0;
        var timer;

        function show(idx) {
            current = idx;
            tabs.forEach(function(t, i) {
                var active = i === idx;
                t.classList.toggle('active', active);
                var bar = t.querySelector('.brand-cat-tab-progress');
                if (bar) {
                    bar.style.animation = 'none';
                    if (active) {
                        void bar.offsetWidth; // force reflow so the animation restarts from 0
                        bar.style.animation = '';
                    }
                }
            });
            panels.forEach(function(p, i) { p.classList.toggle('active', i === idx); });
        }

        function startAutoplay() {
            clearInterval(timer);
            timer = setInterval(function() { show((current + 1) % tabs.length); }, 5000);
        }

        tabs.forEach(function(tab, i) {
            tab.addEventListener('click', function() {
                show(i);
                startAutoplay();
            });
        });

        show(0);
        startAutoplay();
    })();

    /* ── Budget Friendly Tabs ── */
    window.hpBudgetSwitch = function(btn, idx) {
        document.querySelectorAll('.hp-budget-tab').forEach(function(t) { t.classList.remove('active'); });
        document.querySelectorAll('.hp-budget-panel').forEach(function(p) {
            p.classList.toggle('active', p.getAttribute('data-budget-panel') == idx);
        });
        btn.classList.add('active');
    };
    </script>

</body>
</html>
