<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'superehc_aiir');       // ← Change this
define('DB_PASS', 'Aiir@8097000970');   // ← Change this
define('DB_NAME', 'superehc_sgipl');       // ← Change this

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$categoryCol = $conn->query("SHOW COLUMNS FROM products LIKE 'category'");
if ($categoryCol && $categoryCol->num_rows === 0) {
    $conn->query("ALTER TABLE products ADD COLUMN category VARCHAR(20) NOT NULL DEFAULT 'NA' AFTER quantity");
    $conn->query("UPDATE products SET category = CASE WHEN is_premium = 1 THEN 'Premium' ELSE 'NA' END");
}

$brandCategoryCol = $conn->query("SHOW COLUMNS FROM brandlogo LIKE 'category'");
if ($brandCategoryCol && $brandCategoryCol->num_rows === 0) {
    $conn->query("ALTER TABLE brandlogo ADD COLUMN category VARCHAR(50) NOT NULL DEFAULT '' AFTER flag");
}

// $reviewColumns = $conn->query("SHOW COLUMNS FROM reviews LIKE 'is_hidden'");
// if ($reviewColumns && $reviewColumns->num_rows === 0) {
//     $conn->query("ALTER TABLE reviews ADD COLUMN is_hidden TINYINT(1) NOT NULL DEFAULT 0 AFTER status");
// }

// $offerPriceCol = $conn->query("SHOW COLUMNS FROM products LIKE 'offer_price'");
// if ($offerPriceCol && $offerPriceCol->num_rows === 0) {
//     $conn->query("ALTER TABLE products ADD COLUMN offer_price DECIMAL(10,2) DEFAULT 0.00 AFTER mrp");
// }
// $quantityCol = $conn->query("SHOW COLUMNS FROM products LIKE 'quantity'");
// if ($quantityCol && $quantityCol->num_rows === 0) {
//     $conn->query("ALTER TABLE products ADD COLUMN quantity INT(11) DEFAULT 0 AFTER offer_price");
// }
// $premiumCol = $conn->query("SHOW COLUMNS FROM products LIKE 'is_premium'");
// if ($premiumCol && $premiumCol->num_rows === 0) {
//     $conn->query("ALTER TABLE products ADD COLUMN is_premium TINYINT(1) DEFAULT 0 AFTER quantity");
// }
// $newLunchCol = $conn->query("SHOW COLUMNS FROM products LIKE 'new_lunch'");
// if ($newLunchCol && $newLunchCol->num_rows === 0) {
//     $conn->query("ALTER TABLE products ADD COLUMN new_lunch TINYINT(1) DEFAULT 0 AFTER is_premium");
// }
// $seriesCol = $conn->query("SHOW COLUMNS FROM products LIKE 'series'");
// if ($seriesCol && $seriesCol->num_rows === 0) {
//     $conn->query("ALTER TABLE products ADD COLUMN series VARCHAR(255) DEFAULT '' AFTER new_lunch");
// }

// $brandBannerCol = $conn->query("SHOW COLUMNS FROM brandlogo LIKE 'brand_banner'");
// if ($brandBannerCol && $brandBannerCol->num_rows === 0) {
//     $conn->query("ALTER TABLE brandlogo ADD COLUMN brand_banner VARCHAR(255) DEFAULT '' AFTER imageno");
// }
// $brandBanner2Col = $conn->query("SHOW COLUMNS FROM brandlogo LIKE 'brand_banner_2'");
// if ($brandBanner2Col && $brandBanner2Col->num_rows === 0) {
//     $conn->query("ALTER TABLE brandlogo ADD COLUMN brand_banner_2 VARCHAR(255) DEFAULT '' AFTER brand_banner");
// }
// $brandBanner3Col = $conn->query("SHOW COLUMNS FROM brandlogo LIKE 'brand_banner_3'");
// if ($brandBanner3Col && $brandBanner3Col->num_rows === 0) {
//     $conn->query("ALTER TABLE brandlogo ADD COLUMN brand_banner_3 VARCHAR(255) DEFAULT '' AFTER brand_banner_2");
// }
// $brandWebsiteCol = $conn->query("SHOW COLUMNS FROM brandlogo LIKE 'website'");
// if ($brandWebsiteCol && $brandWebsiteCol->num_rows === 0) {
//     $conn->query("ALTER TABLE brandlogo ADD COLUMN website VARCHAR(255) DEFAULT '' AFTER links");
// }

// $blogLinkCol = $conn->query("SHOW COLUMNS FROM blogs LIKE 'link'");
// if ($blogLinkCol && $blogLinkCol->num_rows === 0) {
//     $conn->query("ALTER TABLE blogs ADD COLUMN link VARCHAR(255) DEFAULT '' AFTER image");
// }

// $blogTitleBgCol = $conn->query("SHOW COLUMNS FROM blogs LIKE 'title_bg_image'");
// if ($blogTitleBgCol && $blogTitleBgCol->num_rows === 0) {
//     $conn->query("ALTER TABLE blogs ADD COLUMN title_bg_image VARCHAR(255) DEFAULT '' AFTER image");
// }
?>
