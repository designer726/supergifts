<?php $pagename = basename($_SERVER['PHP_SELF']); ?>
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
                    <div class="section-title">Brand Partners <span class="pill">500+ Brands</span></div>
                    <div class="brand-grid">
                        <div class="brand-chip">🏷 Boat</div>
                        <div class="brand-chip">🏷 Sony</div>
                        <div class="brand-chip">🏷 Apple</div>
                        <div class="brand-chip">🏷 Samsung</div>
                        <div class="brand-chip">🏷 Dell</div>
                        <div class="brand-chip">🏷 HP</div>
                        <div class="brand-chip">🏷 Canon</div>
                        <div class="brand-chip">🏷 Nikon</div>
                        <div class="brand-chip">🏷 Titan</div>
                        <div class="brand-chip">🏷 Fossil</div>
                        <div class="brand-chip">🏷 Lumix</div>
                        <div class="brand-chip">🏷 Bose</div>
                        <div class="brand-chip">🏷 JBL</div>
                        <div class="brand-chip">🏷 Sennheiser</div>
                        <div class="brand-chip">🏷 Audio-Technica</div>
                        <div class="brand-chip">🏷 GoPro</div>
                        <div class="brand-chip">🏷 DJI</div>
                        <div class="brand-chip">🏷 Garmin</div>
                        <div class="brand-chip">🏷 Fitbit</div>
                        <div class="brand-chip">🏷 Xiaomi</div>
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
                            <div class="avatar" style="background:linear-gradient(135deg,#e74c3c,#f39c12)">R</div>
                            <div><div class="reviewer-name">Rahul Mehta</div><div class="reviewer-role">Procurement Head, TCS</div></div>
                        </div>
                    </div>
                    <div class="review-card">
                        <div class="stars">★★★★★</div>
                        <div class="review-text">"We've been ordering quarterly for 2 years. Product quality is consistently excellent and the after-sales support is second to none."</div>
                        <div class="reviewer">
                            <div class="avatar" style="background:linear-gradient(135deg,#1a3a52,#f39c12)">P</div>
                            <div><div class="reviewer-name">Priya Sharma</div><div class="reviewer-role">HR Manager, Infosys</div></div>
                        </div>
                    </div>
                    <div class="review-card">
                        <div class="stars">★★★★☆</div>
                        <div class="review-text">"The bulk order facility and inventory management saved us weeks of effort. Highly recommend for large enterprise gifting needs."</div>
                        <div class="reviewer">
                            <div class="avatar" style="background:linear-gradient(135deg,#f39c12,#e74c3c)">A</div>
                            <div><div class="reviewer-name">Arjun Nair</div><div class="reviewer-role">Operations Lead, HDFC</div></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Modern Trust Section -->
            <section class="trust-section">
                <div class="trust-card">
                    <div class="trust-icon" style="background:#ffe8e4">🏆</div>
                    <h4>500+</h4>
                    <p>Brand Partners across categories and price ranges</p>
                </div>
                <div class="trust-card">
                    <div class="trust-icon" style="background:#e8f2f8">🚚</div>
                    <h4>48hr</h4>
                    <p>Express delivery across 500+ cities in India</p>
                </div>
                <div class="trust-card">
                    <div class="trust-icon" style="background:#fef5e8">⭐</div>
                    <h4>10K+</h4>
                    <p>Successful corporate gift orders delivered</p>
                </div>
                <div class="trust-card">
                    <div class="trust-icon" style="background:#e8f0f8">🔒</div>
                    <h4>100%</h4>
                    <p>Secure payments and quality assurance guarantee</p>
                </div>
            </section>

            <!-- Modern Contact Section -->
            <section class="contact-section" id="contact">
                <div>
                    <div class="section-title">Contact Us <span class="pill">Get in Touch</span></div>
                    <p class="contact-sub">Looking for bulk gifting, branding, or a custom proposal? Drop us a message and our team will respond within 24 hours.</p>
                    <div class="contact-info">
                        <div class="info-row"><div class="info-icon">📍</div><span>123 Gift District, Hyderabad, Telangana 500001</span></div>
                        <div class="info-row"><div class="info-icon">📱</div><span>+91 98765 43210</span></div>
                        <div class="info-row"><div class="info-icon">✉️</div><span>hello@supergifts.in</span></div>
                        <div class="info-row"><div class="info-icon">🕐</div><span>Mon–Sat: 9am – 7pm IST</span></div>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-row">
                        <div class="field-group"><label>First Name</label><input type="text" placeholder="Rahul"></div>
                        <div class="field-group"><label>Last Name</label><input type="text" placeholder="Mehta"></div>
                    </div>
                    <div class="field-group"><label>Company</label><input type="text" placeholder="Tata Consultancy Services"></div>
                    <div class="form-row">
                        <div class="field-group"><label>Email</label><input type="email" placeholder="rahul@company.com"></div>
                        <div class="field-group"><label>Phone</label><input type="tel" placeholder="+91 98765 43210"></div>
                    </div>
                    <div class="field-group">
                        <label>Enquiry Type</label>
                        <select><option>Select enquiry type...</option><option>Bulk Order</option><option>Branding & Packaging</option><option>Corporate Proposal</option><option>Reseller Partnership</option></select>
                    </div>
                    <div class="field-group"><label>Message</label><textarea rows="3" placeholder="Tell us about your gifting requirements..."></textarea></div>
                    <button class="btn-primary" style="width:100%;padding:13px">Send Message →</button>
                </div>
            </section>
            <!-- End Modern Contact Section -->

        </main>

        <?php include('common/footer.php'); ?>



</body>

</html>