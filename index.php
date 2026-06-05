<?php
$pagename = basename($_SERVER['PHP_SELF']);

$brandPartners = [];
$alsoDealWith = [];

function findBrandLogoPath($imageno)
{
    $root = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/supergifts/';
    foreach (['.jpg', '.jpeg', '.png', '.webp'] as $ext) {
        $file = $root . 'images/brandlogo/image' . intval($imageno) . $ext;
        if (file_exists($file)) {
            return 'images/brandlogo/image' . intval($imageno) . $ext;
        }
    }
    return 'images/brandlogo/image' . intval($imageno) . '.jpg';
}

$brandDb = new mysqli("localhost", "superehc_aiir", "Aiir@8097000970", "superehc_sgipl");
if (!$brandDb->connect_error) {
    $sql = "SELECT id, brandname, imageno, flag FROM brandlogo ORDER BY flag DESC, seqence ASC, brandname ASC";
    if ($result = $brandDb->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            $logo = findBrandLogoPath($row['imageno']);
            $item = ['id' => $row['id'], 'brandname' => $row['brandname'], 'logoUrl' => $logo];
            if (intval($row['flag']) === 1) {
                $brandPartners[] = $item;
            } else {
                $alsoDealWith[] = $item;
            }
        }
        $result->free();
    }
    $brandDb->close();
}
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

            <!-- Dynamic Hero Carousel Section -->
            <section class="hero-carousel-wrapper" id="home">
                <div class="hero-carousel-container">
                    <!-- Hero Slide 1 -->
                    <section class="hero-slide active" data-slide="0" style="background:none;padding:0;">
                        <svg width="100%" viewBox="0 0 1200 480" xmlns="http://www.w3.org/2000/svg" style="display:block;width:100%;height:100%;">
                            <rect width="1200" height="480" fill="#1B4B7C" />
                            <circle cx="1050" cy="240" r="340" fill="#254668" opacity="0.5" />
                            <circle cx="1100" cy="200" r="220" fill="#2D6A9F" opacity="0.35" />
                            <ellipse cx="600" cy="520" rx="700" ry="180" fill="#0F2A3E" opacity="0.4" />
                            <circle cx="80" cy="80" r="120" fill="#FFFFFF" opacity="0.08" />
                            <circle cx="80" cy="80" r="70" fill="#FFFFFF" opacity="0.06" />
                            <circle cx="420" cy="60" r="18" fill="#FFFFFF" opacity="0.15" />
                            <circle cx="580" cy="400" r="30" fill="#FFFFFF" opacity="0.08" />
                            <circle cx="200" cy="380" r="12" fill="#FFFFFF" opacity="0.12" />
                            <circle cx="700" cy="30" r="10" fill="#FFFFFF" opacity="0.14" />
                            <polygon points="860,0 1200,0 1200,130 860,130" fill="#D4AF37" opacity="0.15" />
                            <polygon points="0,380 340,480 0,480" fill="#D4AF37" opacity="0.12" />
                            <rect x="820" y="100" width="240" height="200" rx="12" fill="#FFFFFF" opacity="0.12" />
                            <rect x="810" y="88" width="260" height="32" rx="8" fill="#FFFFFF" opacity="0.15" />
                            <line x1="940" y1="88" x2="940" y2="300" stroke="#D4AF37" stroke-width="5" opacity="0.25" />
                            <line x1="810" y1="190" x2="1070" y2="190" stroke="#D4AF37" stroke-width="5" opacity="0.25" />
                            <ellipse cx="910" cy="86" rx="28" ry="16" fill="#D4AF37" opacity="0.3" />
                            <ellipse cx="970" cy="86" rx="28" ry="16" fill="#D4AF37" opacity="0.3" />
                            <circle cx="940" cy="86" r="10" fill="#D4AF37" opacity="0.35" />
                            <rect x="820" y="340" width="110" height="52" rx="10" fill="#D4AF37" opacity="0.15" />
                            <text x="875" y="360" text-anchor="middle" font-family="Georgia, serif" font-size="22" font-weight="700" fill="#D4AF37">500+</text>
                            <text x="875" y="381" text-anchor="middle" font-family="Arial, sans-serif" font-size="10" fill="#FFFFFF" opacity="0.9">Brand Partners</text>
                            <rect x="950" y="340" width="110" height="52" rx="10" fill="#D4AF37" opacity="0.15" />
                            <text x="1005" y="360" text-anchor="middle" font-family="Georgia, serif" font-size="22" font-weight="700" fill="#D4AF37">10K+</text>
                            <text x="1005" y="381" text-anchor="middle" font-family="Arial, sans-serif" font-size="10" fill="#FFFFFF" opacity="0.9">Orders Delivered</text>
                            <rect x="1080" y="340" width="100" height="52" rx="10" fill="#D4AF37" opacity="0.15" />
                            <text x="1130" y="360" text-anchor="middle" font-family="Georgia, serif" font-size="22" font-weight="700" fill="#D4AF37">98%</text>
                            <text x="1130" y="381" text-anchor="middle" font-family="Arial, sans-serif" font-size="10" fill="#FFFFFF" opacity="0.9">Satisfaction</text>
                            <rect x="56" y="68" width="234" height="26" rx="13" fill="#1A1A1A" opacity="0.25" />
                            <text x="173" y="85" text-anchor="middle" font-family="Arial, sans-serif" font-size="10.5" font-weight="700" fill="#D4AF37" letter-spacing="1.2">&#x2726; INDIA'S #1 B2B GIFTING</text>
                            <text x="56" y="168" font-family="Georgia, serif" font-size="66" font-weight="700" fill="#FFFFFF">Gifts That</text>
                            <text x="56" y="248" font-family="Georgia, serif" font-size="66" font-weight="700" fill="#D4AF37" font-style="italic">Inspire</text>
                            <text x="56" y="318" font-family="Georgia, serif" font-size="54" font-weight="700" fill="#FFFFFF">&amp; Build Bonds</text>
                            <text x="56" y="360" font-family="Arial, sans-serif" font-size="15" fill="#FFFFFF" opacity="0.85">Premium B2B gifting solutions — branding to last-mile delivery.</text>
                            <circle cx="1130" cy="100" r="52" fill="#1A1A1A" opacity="0.15" />
                            <circle cx="1130" cy="100" r="52" fill="none" stroke="#FFFFFF" stroke-width="1.2" opacity="0.3" />
                            <text x="1130" y="90" text-anchor="middle" font-family="Georgia, serif" font-size="22" font-weight="700" fill="#FFFFFF">20+</text>
                            <text x="1130" y="107" text-anchor="middle" font-family="Arial, sans-serif" font-size="9.5" fill="#FFFFFF" opacity="0.7">YRS EXP</text>
                            <text x="56" y="462" font-family="Arial, sans-serif" font-size="13" font-weight="700" fill="#FFFFFF" opacity="0.6" letter-spacing="2">SUPERGIFTS.IN</text>
                        </svg>
                    </section>

                    <!-- Hero Slide 2 -->
                    <section class="hero-slide" data-slide="1" style="background:none;padding:0;">
                        <svg width="100%" viewBox="0 0 1200 480" xmlns="http://www.w3.org/2000/svg" style="display:block;width:100%;height:100%;">
                            <rect width="1200" height="480" fill="#1B4B7C" />
                            <polygon points="620,0 1200,0 1200,480 700,480" fill="#254668" />
                            <ellipse cx="1080" cy="100" rx="260" ry="180" fill="#2D6A9F" opacity="0.4" />
                            <ellipse cx="1120" cy="80" rx="160" ry="100" fill="#3D7FAF" opacity="0.25" />
                            <ellipse cx="120" cy="430" rx="200" ry="120" fill="#D4AF37" opacity="0.08" />
                            <circle cx="640" cy="240" r="200" fill="#D4AF37" opacity="0.03" />
                            <circle cx="640" cy="240" r="150" fill="#D4AF37" opacity="0.02" />
                            <polygon points="300,0 360,52 300,104 240,52" fill="#D4AF37" opacity="0.08" />
                            <polygon points="360,52 420,0 480,52 420,104" fill="#D4AF37" opacity="0.06" />
                            <polygon points="500,10 560,62 500,114 440,62" fill="#D4AF37" opacity="0.04" />
                            <circle cx="980" cy="260" r="110" fill="none" stroke="#D4AF37" stroke-width="2" opacity="0.15" />
                            <circle cx="980" cy="260" r="88" fill="none" stroke="#D4AF37" stroke-width="1" opacity="0.08" />
                            <text x="980" y="250" text-anchor="middle" font-family="Arial, sans-serif" font-size="11" font-weight="700" fill="#D4AF37" opacity="0.4" letter-spacing="2">CO-BRANDING</text>
                            <text x="980" y="270" text-anchor="middle" font-family="Arial, sans-serif" font-size="11" fill="#D4AF37" opacity="0.25">ENGRAVING</text>
                            <text x="980" y="290" text-anchor="middle" font-family="Arial, sans-serif" font-size="11" fill="#D4AF37" opacity="0.15">EMBOSSING</text>
                            <circle cx="750" cy="420" r="6" fill="#D4AF37" opacity="0.2" />
                            <circle cx="800" cy="400" r="4" fill="#D4AF37" opacity="0.15" />
                            <circle cx="850" cy="430" r="8" fill="#D4AF37" opacity="0.1" />
                            <circle cx="1100" cy="380" r="10" fill="#D4AF37" opacity="0.1" />
                            <circle cx="1150" cy="330" r="5" fill="#D4AF37" opacity="0.15" />
                            <rect x="56" y="68" width="242" height="26" rx="13" fill="#FFFFFF" opacity="0.12" />
                            <text x="177" y="85" text-anchor="middle" font-family="Arial, sans-serif" font-size="10.5" font-weight="700" fill="#D4AF37" letter-spacing="1.2">&#x2726; CUSTOMIZATION EXCELLENCE</text>
                            <text x="56" y="170" font-family="Georgia, serif" font-size="64" font-weight="700" fill="#FFFFFF">Your Brand,</text>
                            <text x="56" y="244" font-family="Georgia, serif" font-size="64" font-weight="700" fill="#FFFFFF">Our</text>
                            <text x="56" y="318" font-family="Georgia, serif" font-size="64" font-weight="700" fill="#D4AF37" font-style="italic">Expertise</text>
                            <text x="56" y="358" font-family="Arial, sans-serif" font-size="15" fill="#FFFFFF" opacity="0.85">In-house branding, embossing &amp; engraving — concept to delivery.</text>
                            <rect x="56" y="390" width="106" height="54" rx="10" fill="#D4AF37" opacity="0.12" />
                            <text x="109" y="413" text-anchor="middle" font-family="Georgia, serif" font-size="22" font-weight="700" fill="#D4AF37">100%</text>
                            <text x="109" y="433" text-anchor="middle" font-family="Arial, sans-serif" font-size="10" fill="#FFFFFF" opacity="0.85">Customizable</text>
                            <rect x="176" y="390" width="90" height="54" rx="10" fill="#D4AF37" opacity="0.12" />
                            <text x="221" y="413" text-anchor="middle" font-family="Georgia, serif" font-size="22" font-weight="700" fill="#D4AF37">8</text>
                            <text x="221" y="433" text-anchor="middle" font-family="Arial, sans-serif" font-size="10" fill="#FFFFFF" opacity="0.85">Core Services</text>
                            <rect x="280" y="390" width="90" height="54" rx="10" fill="#D4AF37" opacity="0.12" />
                            <text x="325" y="413" text-anchor="middle" font-family="Georgia, serif" font-size="22" font-weight="700" fill="#D4AF37">24x7</text>
                            <text x="325" y="433" text-anchor="middle" font-family="Arial, sans-serif" font-size="10" fill="#FFFFFF" opacity="0.85">Support</text>
                            <text x="56" y="466" font-family="Arial, sans-serif" font-size="13" font-weight="700" fill="#FFFFFF" opacity="0.7" letter-spacing="2">SUPERGIFTS.IN</text>
                        </svg>
                    </section>

                    <!-- Hero Slide 3 -->
                    <section class="hero-slide" data-slide="2" style="background:none;padding:0;">
                        <svg width="100%" viewBox="0 0 1200 480" xmlns="http://www.w3.org/2000/svg" style="display:block;width:100%;height:100%;">
                            <rect width="1200" height="480" fill="#1B4B7C" />
                            <polygon points="550,0 1200,0 1200,480 650,480" fill="#254668" opacity="0.8" />
                            <circle cx="1050" cy="140" r="200" fill="#D4AF37" opacity="0.12" />
                            <circle cx="1050" cy="140" r="130" fill="#D4AF37" opacity="0.1" />
                            <circle cx="1050" cy="140" r="70" fill="#D4AF37" opacity="0.12" />
                            <ellipse cx="600" cy="560" rx="700" ry="200" fill="#0F2A3E" opacity="0.35" />
                            <circle cx="0" cy="0" r="180" fill="none" stroke="#D4AF37" stroke-width="1" opacity="0.1" />
                            <circle cx="0" cy="0" r="130" fill="none" stroke="#D4AF37" stroke-width="0.8" opacity="0.06" />
                            <path d="M 700 360 Q 790 310 860 260 Q 940 200 1010 150 Q 1070 110 1110 70" stroke="#D4AF37" stroke-width="2" fill="none" opacity="0.3" stroke-dasharray="8,5" />
                            <path d="M 680 380 Q 760 320 840 270 Q 920 210 990 160 Q 1060 120 1100 90" stroke="#254668" stroke-width="1.2" fill="none" opacity="0.15" stroke-dasharray="5,7" />
                            <circle cx="710" cy="360" r="8" fill="#D4AF37" opacity="0.8" />
                            <circle cx="710" cy="360" r="18" fill="#D4AF37" opacity="0.12" />
                            <text x="730" y="358" font-family="Arial, sans-serif" font-size="11" fill="#D4AF37" opacity="0.7">Mumbai</text>
                            <circle cx="870" cy="255" r="8" fill="#D4AF37" opacity="0.8" />
                            <circle cx="870" cy="255" r="18" fill="#D4AF37" opacity="0.12" />
                            <text x="892" y="253" font-family="Arial, sans-serif" font-size="11" fill="#D4AF37" opacity="0.7">Hyderabad</text>
                            <circle cx="1000" cy="160" r="8" fill="#D4AF37" opacity="0.8" />
                            <circle cx="1000" cy="160" r="18" fill="#D4AF37" opacity="0.12" />
                            <text x="1020" y="158" font-family="Arial, sans-serif" font-size="11" fill="#D4AF37" opacity="0.7">Bangalore</text>
                            <circle cx="1100" cy="80" r="8" fill="#D4AF37" opacity="0.8" />
                            <circle cx="1100" cy="80" r="18" fill="#D4AF37" opacity="0.12" />
                            <text x="1118" y="78" font-family="Arial, sans-serif" font-size="11" fill="#D4AF37" opacity="0.7">Delhi</text>
                            <circle cx="640" cy="50" r="5" fill="#254668" opacity="0.25" />
                            <circle cx="580" cy="90" r="3" fill="#D4AF37" opacity="0.2" />
                            <circle cx="400" cy="400" r="6" fill="#254668" opacity="0.15" />
                            <rect x="0" y="0" width="6" height="480" fill="#254668" opacity="0.4" />
                            <rect x="0" y="0" width="3" height="480" fill="#D4AF37" opacity="0.3" />
                            <rect x="60" y="68" width="248" height="26" rx="13" fill="#FFFFFF" opacity="0.12" />
                            <text x="184" y="85" text-anchor="middle" font-family="Arial, sans-serif" font-size="10.5" font-weight="700" fill="#D4AF37" letter-spacing="1.2">&#x2726; FAST &amp; RELIABLE DELIVERY</text>
                            <text x="60" y="170" font-family="Georgia, serif" font-size="58" font-weight="700" fill="#FFFFFF">Pan-India Logistics</text>
                            <text x="60" y="240" font-family="Georgia, serif" font-size="58" font-weight="700" fill="#D4AF37" font-style="italic">At Your Service</text>
                            <text x="60" y="280" font-family="Arial, sans-serif" font-size="15" fill="#FFFFFF" opacity="0.9">Nationwide delivery · Real-time tracking · Rush orders.</text>
                            <text x="60" y="302" font-family="Arial, sans-serif" font-size="15" fill="#FFFFFF" opacity="0.85">Trusted by 500+ brands across India.</text>
                            <rect x="60" y="330" width="106" height="54" rx="10" fill="#D4AF37" opacity="0.12" />
                            <text x="113" y="353" text-anchor="middle" font-family="Georgia, serif" font-size="22" font-weight="700" fill="#D4AF37">20+</text>
                            <text x="113" y="374" text-anchor="middle" font-family="Arial, sans-serif" font-size="10" fill="#FFFFFF" opacity="0.85">Yrs Experience</text>
                            <rect x="180" y="330" width="106" height="54" rx="10" fill="#D4AF37" opacity="0.12" />
                            <text x="233" y="353" text-anchor="middle" font-family="Georgia, serif" font-size="22" font-weight="700" fill="#D4AF37">98%</text>
                            <text x="233" y="374" text-anchor="middle" font-family="Arial, sans-serif" font-size="10" fill="#FFFFFF" opacity="0.85">On-Time Delivery</text>
                            <rect x="300" y="330" width="122" height="54" rx="10" fill="#D4AF37" opacity="0.12" />
                            <text x="361" y="353" text-anchor="middle" font-family="Georgia, serif" font-size="22" font-weight="700" fill="#D4AF37">25K SFT</text>
                            <text x="361" y="374" text-anchor="middle" font-family="Arial, sans-serif" font-size="10" fill="#FFFFFF" opacity="0.85">Warehouse</text>
                            <text x="60" y="464" font-family="Arial, sans-serif" font-size="13" font-weight="700" fill="#FFFFFF" opacity="0.7" letter-spacing="2">SUPERGIFTS.IN</text>
                        </svg>
                    </section>
                </div>

                <!-- Navigation Arrows -->
                <button class="hero-nav-btn prev" onclick="changeHeroSlide(-1)">❮</button>
                <button class="hero-nav-btn next" onclick="changeHeroSlide(1)">❯</button>

                <!-- Slider Dots -->
                <div class="slider-dots" id="heroDots">
                    <div class="dot active" onclick="goToHeroSlide(0)"></div>
                    <div class="dot" onclick="goToHeroSlide(1)"></div>
                    <div class="dot" onclick="goToHeroSlide(2)"></div>
                </div>
            </section>


            <!-- Brand Partners & Add-on Services Section -->
            <div class="two-col">
                <div class="col-panel">
                    <div class="section-title">Brand Partners <span class="pill"><?= count($brandPartners) + count($alsoDealWith) ?> Brands</span></div>
                    <div class="brand-section">
                        <div class="mb-4">
                            <div class="section-subtitle" style="font-size:14px;font-weight:700;margin-bottom:12px;color:#333;">Authorised</div>
                            <?php if (!empty($brandPartners)): ?>
                                <div class="brand-grid">
                                    <?php foreach ($brandPartners as $brand): ?>
                                        <a class="brand-chip" href="brand-products.php?brand=<?= intval($brand['id']) ?>" style="padding:12px;min-height:80px;text-decoration:none;display:flex;align-items:center;justify-content:center;">
                                            <img src="<?= htmlspecialchars($brand['logoUrl']) ?>" alt="<?= htmlspecialchars($brand['brandname']) ?>" style="max-width:100%;max-height:50px;object-fit:contain;" />
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-center" style="margin:0;color:#666;">No authorised brand partners are available.</p>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div class="section-subtitle" style="font-size:14px;font-weight:700;margin-bottom:12px;color:#333;">Also Deal With</div>
                            <?php if (!empty($alsoDealWith)): ?>
                                <div class="brand-grid">
                                    <?php foreach ($alsoDealWith as $brand): ?>
                                        <a class="brand-chip" href="brand-products.php?brand=<?= intval($brand['id']) ?>" style="padding:12px;min-height:80px;text-decoration:none;display:flex;align-items:center;justify-content:center;">
                                            <img src="<?= htmlspecialchars($brand['logoUrl']) ?>" alt="<?= htmlspecialchars($brand['brandname']) ?>" style="max-width:100%;max-height:50px;object-fit:contain;" />
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-center" style="margin:0;color:#666;">No "Also Deal With" brands are available.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-panel">
                    <div class="section-title">Add-on Services <span class="pill">8 Services</span></div>
                    <div class="service-list">
                        <a href="service-branding-customization.php" class="service-row" style="text-decoration: none; color: inherit; display: block; cursor: pointer; transition: all 0.3s ease;"><span class="service-num">1</span> Branding & Customization</a>
                        <a href="service-logistic.php" class="service-row" style="text-decoration: none; color: inherit; display: block; cursor: pointer; transition: all 0.3s ease;"><span class="service-num">2</span> Logistic Services</a>
                        <a href="service-premium-packing.php" class="service-row" style="text-decoration: none; color: inherit; display: block; cursor: pointer; transition: all 0.3s ease;"><span class="service-num">3</span> Premium Packing</a>
                        <a href="service-after-sale.php" class="service-row" style="text-decoration: none; color: inherit; display: block; cursor: pointer; transition: all 0.3s ease;"><span class="service-num">4</span> After Sale Services</a>
                        <a href="service-original-products.php" class="service-row" style="text-decoration: none; color: inherit; display: block; cursor: pointer; transition: all 0.3s ease;"><span class="service-num">5</span> 100% Original Products</a>
                        <a href="service-inventory.php" class="service-row" style="text-decoration: none; color: inherit; display: block; cursor: pointer; transition: all 0.3s ease;"><span class="service-num">6</span> Ready to go Inventory</a>
                        <a href="service-pan-india.php" class="service-row" style="text-decoration: none; color: inherit; display: block; cursor: pointer; transition: all 0.3s ease;"><span class="service-num">7</span> Pan-India Reach</a>
                        <a href="service-support.php" class="service-row" style="text-decoration: none; color: inherit; display: block; cursor: pointer; transition: all 0.3s ease;"><span class="service-num">8</span> 24x7 Support</a>
                    </div>
                </div>
            </div>

            <!-- New Brand Products Carousel Section -->
            <section class="products-carousel-section">
                <div class="carousel-header">
                    <div class="section-title">New Brand Products <span class="pill">Premium Collection</span></div>
                    <a href="products.php" class="see-all">Browse All →</a>
                </div>

                <div class="carousel-container">
                    <button class="carousel-btn prev" onclick="scrollCarousel(-1)">❮</button>

                    <div class="carousel-track" id="productCarousel">
                        <!-- Product 1 -->
                        <!-- <div class="carousel-slide product-card">
                            <div class="product-image" style="background: linear-gradient(135deg, #0D2B55, #1A4080);">
                                <span class="product-icon">🎁</span>
                            </div>
                            <div class="product-body">
                                <div class="product-tag">Premium</div>
                                <h4 class="product-name">Leather Portfolio</h4>
                                <p class="product-desc">Premium leather portfolio with custom branding</p>
                                <div class="product-price">₹2,499</div>
                            </div>
                        </div> -->

                        <!-- Product 2 -->
                        <!-- <div class="carousel-slide product-card">
                            <div class="product-image" style="background: linear-gradient(135deg, #1A4080, #0D2B55);">
                                <span class="product-icon">💼</span>
                            </div>
                            <div class="product-body">
                                <div class="product-tag">Corporate</div>
                                <h4 class="product-name">Executive Pen Set</h4>
                                <p class="product-desc">Luxury pen set with engraving options</p>
                                <div class="product-price">₹1,299</div>
                            </div>
                        </div> -->

                        <!-- Product 3 -->
                        <!-- <div class="carousel-slide product-card">
                            <div class="product-image" style="background: linear-gradient(135deg, #D4AF37, #B8962E);">
                                <span class="product-icon">🏆</span>
                            </div>
                            <div class="product-body">
                                <div class="product-tag">Best Seller</div>
                                <h4 class="product-name">Crystal Trophy</h4>
                                <p class="product-desc">Elegant crystal trophy for awards</p>
                                <div class="product-price">₹3,999</div>
                            </div>
                        </div> -->

                        <!-- Product 1 -->
                        <div class="carousel-slide product-card">
                            <div class="product-image">
                                <img src="images/products/prod-1-1777107248-69ec8130029af.png" alt="Leather Portfolio">
                            </div>
                            <div class="product-body">
                                <div class="product-tag">Premium</div>
                                <h4 class="product-name">Leather Portfolio</h4>
                                <p class="product-desc">Premium leather portfolio with custom branding</p>
                                <div class="product-price">₹2,499</div>
                            </div>
                        </div>

                        <!-- Product 2 -->
                        <div class="carousel-slide product-card">
                            <div class="product-image">
                                <img src="images/products/prod-1-1777107248-69ec8130029af.png" alt="Executive Pen Set">
                            </div>
                            <div class="product-body">
                                <div class="product-tag">Corporate</div>
                                <h4 class="product-name">Executive Pen Set</h4>
                                <p class="product-desc">Luxury pen set with engraving options</p>
                                <div class="product-price">₹1,299</div>
                            </div>
                        </div>

                        <!-- Product 3 -->
                        <div class="carousel-slide product-card">
                            <div class="product-image">
                                <img src="images/products/prod-1-1777107248-69ec8130029af.png" alt="Crystal Trophy">
                            </div>
                            <div class="product-body">
                                <div class="product-tag">Best Seller</div>
                                <h4 class="product-name">Crystal Trophy</h4>
                                <p class="product-desc">Elegant crystal trophy for awards</p>
                                <div class="product-price">₹3,999</div>
                            </div>
                        </div>

                        <!-- Product 4 -->
                        <div class="carousel-slide product-card">
                            <div class="product-image" style="background: linear-gradient(135deg, #071624, #0D2B55);">
                                <span class="product-icon">☕</span>
                            </div>
                            <div class="product-body">
                                <div class="product-tag">Popular</div>
                                <h4 class="product-name">Custom Mug & Cup</h4>
                                <p class="product-desc">Ceramic mug with company logo printing</p>
                                <div class="product-price">₹349</div>
                            </div>
                        </div>

                        <!-- Product 5 -->
                        <div class="carousel-slide product-card">
                            <div class="product-image" style="background: linear-gradient(135deg, #B8962E, #D4AF37);">
                                <span class="product-icon">🎯</span>
                            </div>
                            <div class="product-body">
                                <div class="product-tag">Trending</div>
                                <h4 class="product-name">Desk Organizer</h4>
                                <p class="product-desc">Wooden desk organizer with branding</p>
                                <div class="product-price">₹1,799</div>
                            </div>
                        </div>

                        <!-- Product 6 -->
                        <div class="carousel-slide product-card">
                            <div class="product-image" style="background: linear-gradient(135deg, #0D2B55, #071624);">
                                <span class="product-icon">📱</span>
                            </div>
                            <div class="product-body">
                                <div class="product-tag">Tech Gift</div>
                                <h4 class="product-name">Phone Stand</h4>
                                <p class="product-desc">Premium metal phone stand holder</p>
                                <div class="product-price">₹799</div>
                            </div>
                        </div>
                    </div>

                    <button class="carousel-btn next" onclick="scrollCarousel(1)">❯</button>
                </div>

                <div class="carousel-dots" id="carouselDots">
                    <span class="dot active" onclick="goToSlide(0)"></span>
                    <span class="dot" onclick="goToSlide(1)"></span>
                    <span class="dot" onclick="goToSlide(2)"></span>
                </div>
            </section>

            <!-- Modern Current Updates Section -->
            <section class="updates-section">
                <div class="updates-header">
                    <div class="section-title">Current Updates <span class="pill">Latest News</span></div>
                    <a href="events.php" class="see-all">See all articles →</a>
                </div>
                <div class="cards-row">
                    <div class="nav-arrow" onclick="document.querySelectorAll('.blog-cards')[0].scrollLeft -= 300">‹</div>
                    <div class="blog-cards">
                        <div class="blog-card">
                            <div class="blog-img">🎁</div>
                            <div class="blog-body">
                                <div class="blog-tag">Gifting Trends</div>
                                <div class="blog-heading">Top 10 Corporate Gift Ideas for 2025</div>
                                <div class="blog-excerpt">Discover what's trending in corporate gifting this season — from eco-friendly bundles to tech accessories that impress.</div>
                            </div>
                        </div>
                        <div class="blog-card">
                            <div class="blog-img">📦</div>
                            <div class="blog-body">
                                <div class="blog-tag">Logistics</div>
                                <div class="blog-heading">How We Deliver 10,000+ Orders On Time</div>
                                <div class="blog-excerpt">A behind-the-scenes look at our warehouse operations and last-mile delivery partnerships across India.</div>
                            </div>
                        </div>
                        <div class="blog-card">
                            <div class="blog-img">⭐</div>
                            <div class="blog-body">
                                <div class="blog-tag">Success Story</div>
                                <div class="blog-heading">How Tata Motors Gifted 3000 Employees</div>
                                <div class="blog-excerpt">A case study on how we helped Tata Motors execute a seamless gifting campaign across 15 cities in under 7 days.</div>
                            </div>
                        </div>
                    </div>
                    <div class="nav-arrow" onclick="document.querySelectorAll('.blog-cards')[0].scrollLeft += 300">›</div>
                </div>
            </section>

            <!-- Modern Our Clients Section -->
            <section class="clients-section">
                <div class="section-title" style="justify-content:center">Our Clients <span class="pill">Trusted by 200+</span></div>
                <div class="clients-row">
                    <div class="client-chip">🏢 Tata Group</div>
                    <div class="client-chip">💼 Infosys</div>
                    <div class="client-chip">🏦 HDFC Bank</div>
                    <div class="client-chip">📱 Reliance</div>
                    <div class="client-chip">✈️ Air India</div>
                    <div class="client-chip">🏗️ L&T</div>
                    <div class="client-chip">💊 Sun Pharma</div>
                    <div class="client-chip">🔧 Mahindra</div>
                    <div class="client-chip">+ 192 more</div>
                </div>
            </section>

            <!-- Modern Reviews Section -->
            <section class="reviews-section">
                <div class="section-title">What Clients Say <span class="pill">★ 4.9 / 5</span></div>
                <div class="reviews-grid">
                    <div class="review-card">
                        <div class="stars">★★★★★</div>
                        <div class="review-text">"Super Gifts delivered 5,000 custom gift boxes flawlessly. Every pack was perfectly branded and arrived on time. Exceptional service!"</div>
                        <div class="reviewer">
                            <div class="avatar" style="background:linear-gradient(135deg,#FF5E1A,#FFB800)">R</div>
                            <div>
                                <div class="reviewer-name">Rahul Mehta</div>
                                <div class="reviewer-role">Procurement Head, TCS</div>
                            </div>
                        </div>
                    </div>
                    <div class="review-card">
                        <div class="stars">★★★★★</div>
                        <div class="review-text">"We've been ordering quarterly for 2 years. Product quality is consistently excellent and the after-sales support is second to none."</div>
                        <div class="reviewer">
                            <div class="avatar" style="background:linear-gradient(135deg,#00C4A0,#0F1D3A)">P</div>
                            <div>
                                <div class="reviewer-name">Priya Sharma</div>
                                <div class="reviewer-role">HR Manager, Infosys</div>
                            </div>
                        </div>
                    </div>
                    <div class="review-card">
                        <div class="stars">★★★★☆</div>
                        <div class="review-text">"The bulk order facility and inventory management saved us weeks of effort. Highly recommend for large enterprise gifting needs."</div>
                        <div class="reviewer">
                            <div class="avatar" style="background:linear-gradient(135deg,#FFB800,#FF5E1A)">A</div>
                            <div>
                                <div class="reviewer-name">Arjun Nair</div>
                                <div class="reviewer-role">Operations Lead, HDFC</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Modern Trust Section -->
            <section class="trust-section">
                <div class="trust-card">
                    <div class="trust-icon" style="background:#FFF0E0">🏆</div>
                    <h4>500+</h4>
                    <p>Brand Partners across categories and price ranges</p>
                </div>
                <div class="trust-card">
                    <div class="trust-icon" style="background:#E0FFF8">🚚</div>
                    <h4>48hr</h4>
                    <p>Express delivery across 500+ cities in India</p>
                </div>
                <div class="trust-card">
                    <div class="trust-icon" style="background:#FFF8E0">⭐</div>
                    <h4>10K+</h4>
                    <p>Successful corporate gift orders delivered</p>
                </div>
                <div class="trust-card">
                    <div class="trust-icon" style="background:#E0EAFF">🔒</div>
                    <h4>100%</h4>
                    <p>Secure payments and quality assurance guarantee</p>
                </div>
            </section>

            <!-- Modern Contact Section - Professional -->
            <section class="contact-section-home" id="contact">
                <div class="container">
                    <div class="contact-header">
                        <h2 class="section-title">Get In Touch <span class="pill">Quick Contact</span></h2>
                        <p class="contact-sub">Ready for corporate gifting? Connect instantly. Multiple offices nationwide.</p>
                    </div>

                    <div class="contact-content">
                        <div class="contact-info">
                            <h4>Contact Information</h4>

                            <div class="info-item">
                                <div class="info-icon">📍</div>
                                <div>
                                    <div class="info-label">Address</div>
                                    <div class="info-value">Hyderabad, India</div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">📱</div>
                                <div>
                                    <div class="info-label">Phone</div>
                                    <div class="info-value"><a href="tel:+918097000970" style="color: var(--brand-orange); text-decoration: none; font-weight: 600;">+91 8097 000 970</a></div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">✉️</div>
                                <div>
                                    <div class="info-label">Email</div>
                                    <div class="info-value"><a href="mailto:info@supergifts.in" style="color: var(--brand-orange); text-decoration: none; font-weight: 600;">info@supergifts.in</a></div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">🕐</div>
                                <div>
                                    <div class="info-label">Business Hours</div>
                                    <div class="info-value">
                                        <strong>Monday - Friday:</strong> 9:00 AM - 6:00 PM<br>
                                        <strong>Saturday:</strong> 10:00 AM - 4:00 PM<br>
                                        <strong>Sunday:</strong> Closed
                                    </div>
                                </div>
                            </div>

                            <div class="contact-socials">
                                <a href="https://www.linkedin.com/company/super-gifts" target="_blank" class="social-link">🔗 LinkedIn</a>
                                <a href="https://www.instagram.com/supergifts/" target="_blank" class="social-link">📸 Instagram</a>
                                <a href="https://www.facebook.com/supergifts" target="_blank" class="social-link">f Facebook</a>
                            </div>
                        </div>

                        <div class="contact-form-wrapper">
                            <form class="contact-form" method="POST" action="submit-review.php">
                                <div class="form-group">
                                    <label for="name">Your Name</label>
                                    <input type="text" id="name" name="name" placeholder="John Doe" required>
                                </div>

                                <div class="form-group">
                                    <label for="email">Your Email</label>
                                    <input type="email" id="email" name="email" placeholder="john@example.com" required>
                                </div>

                                <div class="form-group">
                                    <label for="company">Company Name</label>
                                    <input type="text" id="company" name="company" placeholder="Your Company">
                                </div>

                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" placeholder="+91 XXXXX XXXXX">
                                </div>

                                <div class="form-group">
                                    <label for="message">Your Message</label>
                                    <textarea id="message" name="message" placeholder="Tell us about your gifting needs..." rows="5" required></textarea>
                                </div>

                                <button type="submit" class="btn-primary">Send Message →</button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Modern Contact Section -->

        </main>

        <?php include('common/footer.php'); ?>

        <!-- Dynamic Hero Carousel JavaScript -->
        <script>
            // Hero Carousel State
            let heroState = {
                current: 0,
                totalSlides: 3,
                autoplayInterval: null,
                autoplayDelay: 5000
            };

            // Change Hero Slide
            function changeHeroSlide(direction) {
                heroState.current += direction;
                if (heroState.current < 0) heroState.current = heroState.totalSlides - 1;
                if (heroState.current >= heroState.totalSlides) heroState.current = 0;

                updateHeroSlide();
                resetAutoplay();
            }

            // Go to specific hero slide
            function goToHeroSlide(slideIndex) {
                heroState.current = slideIndex;
                updateHeroSlide();
                resetAutoplay();
            }

            // Update hero slide display
            function updateHeroSlide() {
                // Update slide visibility
                document.querySelectorAll('.hero-slide').forEach((slide, index) => {
                    if (index === heroState.current) {
                        slide.classList.add('active');
                        slide.style.opacity = '1';
                        slide.style.transform = 'translateX(0)';
                    } else {
                        slide.classList.remove('active');
                        slide.style.opacity = '0';
                        slide.style.transform = 'translateX(100px)';
                    }
                });

                // Update dots
                document.querySelectorAll('.slider-dots .dot').forEach((dot, index) => {
                    if (index === heroState.current) {
                        dot.classList.add('active');
                    } else {
                        dot.classList.remove('active');
                    }
                });
            }

            // Start autoplay
            function startAutoplay() {
                heroState.autoplayInterval = setInterval(() => {
                    changeHeroSlide(1);
                }, heroState.autoplayDelay);
            }

            // Reset autoplay timer
            function resetAutoplay() {
                clearInterval(heroState.autoplayInterval);
                startAutoplay();
            }

            // Initialize carousel on page load
            document.addEventListener('DOMContentLoaded', function() {
                // Set initial styles
                document.querySelectorAll('.hero-slide').forEach((slide, index) => {
                    slide.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
                    if (index !== 0) {
                        slide.style.opacity = '0';
                        slide.style.transform = 'translateX(100px)';
                    }
                });

                // Start autoplay
                startAutoplay();

                // Pause autoplay on hover
                document.querySelector('.hero-carousel-container').addEventListener('mouseenter', () => {
                    clearInterval(heroState.autoplayInterval);
                });

                // Resume autoplay on mouse leave
                document.querySelector('.hero-carousel-container').addEventListener('mouseleave', () => {
                    startAutoplay();
                });
            });

            // Touch support for mobile
            let touchStartX = 0;
            let touchEndX = 0;

            document.querySelector('.hero-carousel-container').addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            }, false);

            document.querySelector('.hero-carousel-container').addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                if (touchStartX - touchEndX > 50) changeHeroSlide(1); // Swipe left
                if (touchEndX - touchStartX > 50) changeHeroSlide(-1); // Swipe right
            }, false);
        </script>

        <!-- Dynamic Hero Carousel Styles -->
        <style>
            .hero-carousel-wrapper {
                position: relative;
                width: 100%;
                background: #1B1B1B;
                min-height: 600px;
                overflow: hidden;
            }

            .hero-carousel-container {
                position: relative;
                width: 100%;
                min-height: 600px;
                display: flex;
                align-items: center;
            }

            .hero-slide {
                position: absolute;
                width: 100%;
                height: 100%;
                display: flex;
                align-items: stretch;
                justify-content: stretch;
                padding: 0;
                background: none;
                opacity: 0;
                transform: translateX(100px);
                transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 1;
                overflow: hidden;
            }

            .hero-slide svg {
                width: 100%;
                height: 100%;
                min-height: 480px;
            }

            .hero-slide.active {
                opacity: 1;
                transform: translateX(0);
                z-index: 2;
            }

            .hero-nav-btn {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                z-index: 10;
                background: rgba(255, 255, 255, 0.2);
                color: white;
                border: 2px solid rgba(255, 255, 255, 0.4);
                width: 50px;
                height: 50px;
                border-radius: 50%;
                cursor: pointer;
                font-size: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
                backdrop-filter: blur(10px);
            }

            .hero-nav-btn:hover {
                background: rgba(255, 199, 0, 0.3);
                border-color: #ffc700;
                transform: translateY(-50%) scale(1.1);
            }

            .hero-nav-btn.prev {
                left: 30px;
            }

            .hero-nav-btn.next {
                right: 30px;
            }

            .slider-dots {
                position: absolute;
                bottom: 30px;
                left: 50%;
                transform: translateX(-50%);
                display: flex;
                gap: 12px;
                z-index: 10;
            }

            .slider-dots .dot {
                width: 14px;
                height: 14px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.4);
                cursor: pointer;
                transition: all 0.4s ease;
                border: 2px solid rgba(255, 255, 255, 0.6);
            }

            .slider-dots .dot.active {
                background: #ffc700;
                border-color: #ffc700;
                width: 18px;
                height: 18px;
                box-shadow: 0 0 15px rgba(255, 199, 0, 0.5);
            }

            .slider-dots .dot:hover {
                background: rgba(255, 255, 255, 0.6);
                transform: scale(1.2);
            }

            @media (max-width: 1024px) {
                .hero-slide {
                    min-height: auto;
                }

                .hero-nav-btn {
                    width: 45px;
                    height: 45px;
                    font-size: 20px;
                }

                .hero-nav-btn.prev {
                    left: 15px;
                }

                .hero-nav-btn.next {
                    right: 15px;
                }
            }

            @media (max-width: 768px) {
                .hero-carousel-wrapper {
                    min-height: 260px;
                }

                .hero-slide {
                    min-height: 260px;
                }

                .hero-slide svg {
                    min-height: 260px;
                }

                .hero-nav-btn {
                    display: none;
                }
            }

            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateX(100px);
                }

                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            .hero-slide.active {
                animation: slideIn 0.8s ease forwards;
            }
        </style>


</body>

</html>