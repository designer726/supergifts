<?php
$pagename = basename($_SERVER['PHP_SELF']);

$brandPartners = [];
$alsoDealWith = [];

function findBrandLogoPath($imageno) {
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

            <!-- Modern Hero Section -->
            <section class="hero" id="home">
                <div class="hero-content">
                    <div class="hero-badge">✦ India's #1 Corporate Gifting Platform</div>
                    <h1>Gifts That <em>Inspire</em><br>&Build Bonds</h1>
                    <p>Premium gifting solutions for corporates and resellers — from branding to last-mile delivery. Everything under one roof.</p>
                    <div class="hero-btns">
                        <button class="btn-primary" onclick="window.location.href='products.php'">Browse Products →</button>
                        <button class="btn-outline" onclick="window.location.href='contact.php'">Request Proposal</button>
                    </div>
                </div>
                <div class="hero-right">
                    <div class="stat-card"><div class="num">500+</div><div class="lbl">Brand Partners</div></div>
                    <div class="stat-card"><div class="num">10K+</div><div class="lbl">Orders Delivered</div></div>
                    <div class="stat-card"><div class="num">98%</div><div class="lbl">Satisfaction Rate</div></div>
                </div>
            </section>
            <div class="slider-dots">
                <div class="dot active"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>


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
                        <div class="service-row"><span class="service-num">1</span> Branding & Customization</div>
                        <div class="service-row"><span class="service-num">2</span> Logistic Services</div>
                        <div class="service-row"><span class="service-num">3</span> Premium Packing</div>
                        <div class="service-row"><span class="service-num">4</span> After Sale Services</div>
                        <div class="service-row"><span class="service-num">5</span> 100% Original Products</div>
                        <div class="service-row"><span class="service-num">6</span> Ready to go Inventory</div>
                        <div class="service-row"><span class="service-num">7</span> Pan-India Reach</div>
                        <div class="service-row"><span class="service-num">8</span> 24x7 Support</div>
                    </div>
                </div>
            </div>

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
                            <div><div class="reviewer-name">Rahul Mehta</div><div class="reviewer-role">Procurement Head, TCS</div></div>
                        </div>
                    </div>
                    <div class="review-card">
                        <div class="stars">★★★★★</div>
                        <div class="review-text">"We've been ordering quarterly for 2 years. Product quality is consistently excellent and the after-sales support is second to none."</div>
                        <div class="reviewer">
                            <div class="avatar" style="background:linear-gradient(135deg,#00C4A0,#0F1D3A)">P</div>
                            <div><div class="reviewer-name">Priya Sharma</div><div class="reviewer-role">HR Manager, Infosys</div></div>
                        </div>
                    </div>
                    <div class="review-card">
                        <div class="stars">★★★★☆</div>
                        <div class="review-text">"The bulk order facility and inventory management saved us weeks of effort. Highly recommend for large enterprise gifting needs."</div>
                        <div class="reviewer">
                            <div class="avatar" style="background:linear-gradient(135deg,#FFB800,#FF5E1A)">A</div>
                            <div><div class="reviewer-name">Arjun Nair</div><div class="reviewer-role">Operations Lead, HDFC</div></div>
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

            <!-- Modern Contact Section -->
            <section class="contact-section" id="contact">
                <div class="contact-container">
                    <div class="contact-header">
                        <h2 class="section-title">Get In Touch <span class="pill">Let's Connect</span></h2>
                        <p class="contact-subtitle">Have a question or ready to place an order? Reach out to us and we'll get back to you within 24 hours.</p>
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
                                    <div class="info-value">+91 8097 000 970</div>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-icon">✉️</div>
                                <div>
                                    <div class="info-label">Email</div>
                                    <div class="info-value">info@supergifts.in</div>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-icon">🕐</div>
                                <div>
                                    <div class="info-label">Business Hours</div>
                                    <div class="info-value">Monday - Friday: 9:00 AM - 6:00 PM<br>Saturday: 10:00 AM - 4:00 PM<br>Sunday: Closed</div>
                                </div>
                            </div>

                            <div class="contact-socials">
                                <a href="https://www.linkedin.com/company/super-gifts" target="_blank" class="social-link">LinkedIn</a>
                                <a href="https://www.instagram.com/supergifts/" target="_blank" class="social-link">Instagram</a>
                                <a href="https://www.facebook.com/supergifts" target="_blank" class="social-link">Facebook</a>
                            </div>
                        </div>

                        <div class="contact-form-wrapper">
                            <form class="contact-form" method="POST" action="submit-review.php">
                                <div class="form-group">
                                    <input type="text" name="name" placeholder="Your Name" required>
                                </div>
                                
                                <div class="form-group">
                                    <input type="email" name="email" placeholder="Your Email" required>
                                </div>
                                
                                <div class="form-group">
                                    <input type="text" name="company" placeholder="Company Name">
                                </div>
                                
                                <div class="form-group">
                                    <input type="tel" name="phone" placeholder="Phone Number">
                                </div>
                                
                                <div class="form-group">
                                    <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
                                </div>
                                
                                <button type="submit" class="btn-primary">Send Message</button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Modern Contact Section -->

        </main>

        <?php include('common/footer.php'); ?>



</body>

</html>