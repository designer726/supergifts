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

require_once 'sgipl-manage/includes/db.php';
$serviceBanners = [];
$serviceRes = $conn->query("SELECT slot, file_path, file_type FROM banners WHERE slot BETWEEN 12 AND 15 AND status=1 ORDER BY slot ASC");
if ($serviceRes) {
    while ($row = $serviceRes->fetch_assoc()) {
        if (!empty($row['file_path'])) {
            $serviceBanners[] = $row;
        }
    }
}
if (empty($serviceBanners)) {
    $serviceBanners[] = ['slot' => 0, 'file_path' => '', 'file_type' => 'image'];
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
            <section class="services-banner-hero">
                <div class="services-banner-slides" id="servicesBannerSlider">
                    <?php foreach ($serviceBanners as $idx => $banner): ?>
                        <?php $isVideo = !empty($banner['file_path']) && $banner['file_type'] === 'video'; ?>
                        <div class="banner-slide<?= $idx === 0 ? ' active' : '' ?>" data-slide="<?= $idx ?>">
                            <?php if (!empty($banner['file_path'])): ?>
                                <?php if ($isVideo): ?>
                                    <video class="services-banner-media" src="<?= htmlspecialchars($banner['file_path']) ?>" muted playsinline autoplay loop></video>
                                <?php else: ?>
                                    <img class="services-banner-media" src="<?= htmlspecialchars($banner['file_path']) ?>" alt="Services Banner <?= $banner['slot'] ?>">
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="banner-fallback"></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- <div class="services-banner-overlay">
                    <div class="hero-content">
                        <div class="hero-badge">✦ Our Services</div>
                        <h1>Transforming Imaginations into <em>Personalized Gifting</em> Realities</h1>
                        <p>Welcome to Super Gifting India Private Ltd (SGIPL), where we redefine the gifting space through close collaboration, embodying the motto "You Imagine, We Create."</p>
                        <div class="hero-btns">
                            <button class="btn-primary" onclick="window.location.href='contact.php'">Request Proposal →</button>
                            <button class="btn-outline" onclick="window.location.href='products.php'">Browse Products</button>
                        </div>
                    </div>
                    <div class="hero-right">
                        <div class="stat-card"><div class="num">8</div><div class="lbl">Core Services</div></div>
                        <div class="stat-card"><div class="num">100%</div><div class="lbl">Customizable</div></div>
                        <div class="stat-card"><div class="num">24x7</div><div class="lbl">Support</div></div>
                    </div>
                </div> -->

                <?php if (count($serviceBanners) > 1): ?>
                <div class="banner-controls">
                    <button type="button" class="services-banner-arrow prev" onclick="navigateServicesBannerSlider('servicesBannerSlider', -1)">❮</button>
                    <button type="button" class="services-banner-arrow next" onclick="navigateServicesBannerSlider('servicesBannerSlider', 1)">❯</button>
                    <div class="services-banner-dots" id="servicesBannerDots"></div>
                </div>
                <?php endif; ?>
            </section>
            <!-- End Modern Hero Section -->

            <style>
            .services-banner-hero { position: relative; overflow: hidden; height: 600px; min-height: 600px; background: #0d2b55; }
            .services-banner-hero .services-banner-slides { position: absolute; inset: 0; z-index: 1; height: 100%; }
            .services-banner-hero .banner-slide { position: absolute; inset: 0; opacity: 0; transition: opacity 0.6s ease; height: 100%; }
            .services-banner-hero .banner-slide.active { opacity: 1; z-index: 1; }
            .services-banner-hero .services-banner-media { width: 100%; height: 100%; object-fit: cover; display: block; }
            .services-banner-hero .banner-fallback { width: 100%; height: 100%; background: linear-gradient(135deg, #0d2b55 0%, #1f3b85 45%, #071327 100%); }
            .services-banner-hero .services-banner-overlay { position: relative; z-index: 2; display: grid; grid-template-columns: 1.7fr 1fr; gap: 32px; align-items: center; max-width: 1240px; margin: 0 auto; padding: 90px 30px; height: 100%; }
            .services-banner-hero .services-banner-overlay::before { content: ''; position: absolute; inset: 0; background: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.08), transparent 35%), linear-gradient(180deg, rgba(8,16,40,0.18), rgba(8,16,40,0.55)); pointer-events: none; }
            .services-banner-hero .services-banner-overlay > * { position: relative; z-index: 2; }
            .services-banner-hero .hero-content h1 { color: #fff; line-height: 1.05; }
            .services-banner-hero .hero-content p { color: rgba(255,255,255,0.88); max-width: 640px; margin-top: 22px; }
            .services-banner-hero .hero-right { display: grid; gap: 20px; justify-self: start; }
            .services-banner-hero .stat-card { padding: 28px 26px; border-radius: 20px; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(12px); }
            .services-banner-hero .stat-card .num { font-size: 3rem; font-weight: 700; color: #ffc107; }
            .services-banner-hero .stat-card .lbl { margin-top: 10px; color: #fff; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.04em; }
            .services-banner-hero .banner-controls { position: absolute; inset: 0; z-index: 3; pointer-events: none; }
            .services-banner-hero .services-banner-arrow {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                background: rgba(255, 255, 255, 0.15);
                color: #fff;
                border: 1.5px solid rgba(255, 255, 255, 0.3);
                width: 44px;
                height: 44px;
                border-radius: 50%;
                cursor: pointer;
                font-size: 22px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s ease;
                backdrop-filter: blur(6px);
                line-height: 1;
                pointer-events: auto;
            }
            .services-banner-hero .services-banner-arrow:hover {
                background: rgba(208, 2, 27, 0.35);
                border-color: #D0021B;
            }
            .services-banner-hero .services-banner-arrow.prev { left: 24px; }
            .services-banner-hero .services-banner-arrow.next { right: 24px; }
            .services-banner-hero .services-banner-dots {
                position: absolute;
                left: 48px;
                bottom: 20px;
                display: flex;
                gap: 8px;
                z-index: 10;
                pointer-events: auto;
            }
            .services-banner-hero .services-banner-dot { width: 8px; height: 8px; border-radius: 50%; background: rgba(255,255,255,0.35); cursor: pointer; transition: all 0.3s ease; }
            .services-banner-hero .services-banner-dot.active { background: #D0021B; width: 20px; border-radius: 4px; }
            .metrics-track .metric-card,
            .process-track .process-card,
            .features-track .feature-card { min-width: 280px; }
            #services .container {
                max-width: 100% !important;
                width: 100% !important;
                padding-left: 20px !important;
                padding-right: 20px !important;
            }
            #services .row.mb-n30 {
                display: grid !important;
                grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
                gap: 22px !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                align-items: stretch !important;
            }
            #services .row.mb-n30 > [class*="col-"] {
                width: 100% !important;
                max-width: none !important;
                flex: 0 0 100% !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
                margin-bottom: 0 !important;
                display: flex !important;
                align-items: stretch !important;
            }
            #services .row.mb-n30 > .col-lg-4,
            #services .row.mb-n30 > .col-md-6 {
                flex-basis: 100% !important;
                max-width: 100% !important;
            }
            #services .services-3-item {
                width: 100% !important;
                height: 100% !important;
                min-height: 380px !important;
                display: flex !important;
                align-items: stretch !important;
                justify-content: flex-start !important;
                border-radius: 18px !important;
                background: #081b4a !important;
                box-shadow: 0 12px 28px rgba(6, 26, 72, 0.12) !important;
                padding: 30px 22px 26px !important;
                position: relative;
                overflow: hidden;
            }
            #services .services-3-item .wow {
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: flex-start;
                text-align: center;
            }
            #services .services-3-icon {
                width: 90px;
                height: 90px;
                min-width: 90px;
                min-height: 90px;
                margin: 0 auto 18px;
                background: #f4f5f7;
                color: #0d2b55;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: inset 0 0 0 2px rgba(13, 43, 85, 0.08);
            }
            #services .services-3-title {
                font-size: clamp(1.3rem, 1vw + 0.8rem, 2rem);
                line-height: 1.3;
                margin-bottom: 18px;
                color: #f6d222;
                font-weight: 700;
            }
            #services .services-3-text {
                font-size: 1rem;
                line-height: 1.6;
                color: rgba(255,255,255,0.84);
                margin: 0;
                max-width: 100%;
            }
            @media (max-width: 991px) {
                #services .row.mb-n30 {
                    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                }
                #services .services-3-item {
                    min-height: 320px !important;
                }
            }
            @media (max-width: 767px) {
                #services .row.mb-n30 {
                    grid-template-columns: 1fr !important;
                    row-gap: 30px !important;
                }
                #services .services-3-item {
                    min-height: 260px !important;
                    padding: 22px 18px 20px !important;
                }
                #services .services-3-title {
                    font-size: 1.6rem;
                }
                #services .services-3-text {
                    font-size: 0.95rem;
                }
            }
            @media(max-width: 1200px) {
                .services-banner-hero { height: 520px; }
                .services-banner-hero .services-banner-overlay { padding: 60px 24px; }
                .services-banner-hero .hero-content h1 { font-size: 3.2rem; }
                .services-banner-hero .hero-right { gap: 16px; }
            }
            @media(max-width: 991px) {
                .services-banner-hero { height: auto; min-height: 520px; }
                .services-banner-hero .services-banner-overlay { grid-template-columns: 1fr; padding: 60px 20px; min-height: 520px; }
                .services-banner-hero .hero-right { grid-template-columns: repeat(3, minmax(0, 1fr)); }
                .slider-nav-btn { width: 42px; height: 42px; font-size: 18px; }
                .metrics-slider-container .slider-nav-btn.prev,
                .process-slider-container .slider-nav-btn.prev,
                .features-slider-container .slider-nav-btn.prev { left: 10px !important; }
                .metrics-slider-container .slider-nav-btn.next,
                .process-slider-container .slider-nav-btn.next,
                .features-slider-container .slider-nav-btn.next { right: 10px !important; }
            }
            @media(max-width: 767px) {
                .services-banner-hero {
                    height: auto;
                    min-height: 0;
                    background: #0d2b55;
                }
                .services-banner-hero .services-banner-slides {
                    position: relative;
                    height: auto;
                }
                .services-banner-hero .banner-slide {
                    position: relative;
                    inset: auto;
                    display: none;
                    height: auto;
                }
                .services-banner-hero .banner-slide.active {
                    display: block;
                }
                .services-banner-hero .services-banner-media {
                    width: 100%;
                    height: auto;
                    max-height: none;
                    object-fit: contain;
                    object-position: center;
                }
                .services-banner-hero .banner-fallback {
                    aspect-ratio: 16 / 9;
                    height: auto;
                }
                .services-banner-hero .banner-controls { inset: 0; }
                .services-banner-hero .services-banner-arrow.prev { left: 10px; }
                .services-banner-hero .services-banner-arrow.next { right: 10px; }
                .services-banner-hero .services-banner-dots {
                    left: 50%;
                    bottom: 10px;
                    transform: translateX(-50%);
                }
                .services-banner-hero .hero-right { grid-template-columns: 1fr; }
                .services-banner-hero .hero-content h1 { font-size: 2.4rem; }
                .services-banner-hero .hero-content p { font-size: 0.96rem; }
                .metrics-track,
                .process-track,
                .features-track { gap: 18px; }
                .metrics-track .metric-card,
                .process-track .process-card,
                .features-track .feature-card { flex: 0 0 calc(100% - 20px) !important; }
                .slider-nav-btn { left: 10px !important; right: 10px !important; }
            }
            @media(max-width: 575px) {
                .services-banner-hero .services-banner-overlay { padding: 50px 16px; }
                .services-banner-hero .banner-controls { gap: 10px; }
                .services-banner-hero .services-banner-arrow { width: 38px; height: 38px; }
                .services-banner-hero .services-banner-dots { gap: 8px; }
                .services-banner-hero .services-banner-dot { width: 10px; height: 10px; }
            }
            </style>

            <script>
            const servicesBannerSliderState = {};
            const servicesBannerAutoSlideTimers = {};
            document.addEventListener('DOMContentLoaded', function() {
                initializeServicesBannerSlider('servicesBannerSlider', 'servicesBannerDots');
                var slider = document.getElementById('servicesBannerSlider');
                if (slider && slider.querySelectorAll('.banner-slide').length > 1) {
                    startServicesBannerAutoSlide('servicesBannerSlider', 'servicesBannerDots', 5000);
                }
            });

            function initializeServicesBannerSlider(sliderId, dotsId, initialSlide = 0) {
                const slider = document.getElementById(sliderId);
                if (!slider) return;
                const slides = slider.querySelectorAll('.banner-slide');
                if (!slides.length) return;
                servicesBannerSliderState[sliderId] = { current: initialSlide, total: slides.length };
                const dots = document.getElementById(dotsId);
                if (dots) {
                    dots.innerHTML = '';
                    slides.forEach((slide, index) => {
                        const dot = document.createElement('span');
                        dot.className = 'services-banner-dot' + (index === initialSlide ? ' active' : '');
                        dot.addEventListener('click', function() {
                            goToServicesBannerSlide(sliderId, dotsId, index);
                            startServicesBannerAutoSlide(sliderId, dotsId, 5000);
                        });
                        dots.appendChild(dot);
                    });
                }
                updateServicesBannerSlider(sliderId, dotsId);
            }

            function startServicesBannerAutoSlide(sliderId, dotsId, interval = 5000) {
                if (servicesBannerAutoSlideTimers[sliderId]) {
                    clearInterval(servicesBannerAutoSlideTimers[sliderId]);
                }
                servicesBannerAutoSlideTimers[sliderId] = setInterval(function() {
                    navigateServicesBannerSlider(sliderId, 1);
                }, interval);
            }

            function navigateServicesBannerSlider(sliderId, delta) {
                const state = servicesBannerSliderState[sliderId];
                if (!state) return;
                state.current += delta;
                if (state.current < 0) state.current = state.total - 1;
                if (state.current >= state.total) state.current = 0;
                updateServicesBannerSlider(sliderId, 'servicesBannerDots');
                startServicesBannerAutoSlide(sliderId, 'servicesBannerDots', 5000);
            }

            function goToServicesBannerSlide(sliderId, dotsId, slideIndex) {
                const state = servicesBannerSliderState[sliderId];
                if (!state) return;
                state.current = slideIndex;
                updateServicesBannerSlider(sliderId, dotsId);
                startServicesBannerAutoSlide(sliderId, dotsId, 5000);
            }

            function updateServicesBannerSlider(sliderId, dotsId) {
                const slider = document.getElementById(sliderId);
                if (!slider) return;
                const slides = slider.querySelectorAll('.banner-slide');
                slides.forEach((slide, index) => {
                    slide.classList.toggle('active', index === servicesBannerSliderState[sliderId].current);
                });
                const dots = document.getElementById(dotsId);
                if (dots) {
                    dots.querySelectorAll('.services-banner-dot').forEach((dot, index) => {
                        dot.classList.toggle('active', index === servicesBannerSliderState[sliderId].current);
                    });
                }
            }
            </script>

            <!-- Informatic Dynamic Sliders Section -->
            <section class="informatic-sliders-section" style="padding: 80px 0; background: linear-gradient(135deg, #f5f7fa 0%, #e9f0f8 100%);">
                <div class="container">
                    <!-- Slider 1: Key Statistics & Performance Metrics -->
                    <div class="informatic-slider-wrapper mb-80">
                        <div class="slider-header" style="text-align: center; margin-bottom: 50px;">
                            <h2 style="font-size: 2.5rem; color: #0d2b55; margin-bottom: 15px; font-weight: 700;">
                                Why Choose <span style="color: #ffc107;">Super Gifting?</span>
                            </h2>
                            <p style="font-size: 1.1rem; color: #555; max-width: 600px; margin: 0 auto;">
                                Exceptional metrics that prove our excellence in corporate gifting solutions
                            </p>
                        </div>

                        <div class="metrics-slider-container" style="position: relative;">
                            <!-- Slider Navigation -->
                            <button class="slider-nav-btn prev" onclick="moveSlider('metrics', -1)" style="position: absolute; left: -50px; top: 50%; transform: translateY(-50%); z-index: 10; background: #0d2b55; color: white; border: none; width: 45px; height: 45px; border-radius: 50%; cursor: pointer; font-size: 20px; transition: all 0.3s;">❮</button>
                            <button class="slider-nav-btn next" onclick="moveSlider('metrics', 1)" style="position: absolute; right: -50px; top: 50%; transform: translateY(-50%); z-index: 10; background: #0d2b55; color: white; border: none; width: 45px; height: 45px; border-radius: 50%; cursor: pointer; font-size: 20px; transition: all 0.3s;">❯</button>

                            <!-- Slider Track -->
                            <div class="metrics-track" style="display: flex; gap: 30px; overflow: hidden; padding: 20px 0;">
                                <!-- Metric 1 -->
                                <div class="metric-card" style="flex: 0 0 calc(33.333% - 20px); background: white; padding: 40px 30px; border-radius: 15px; text-align: center; box-shadow: 0 10px 30px rgba(13, 43, 85, 0.1); transition: all 0.4s ease; cursor: pointer;">
                                    <div style="font-size: 3.5rem; font-weight: 700; color: #ffc107; margin-bottom: 15px;">500+</div>
                                    <h4 style="color: #0d2b55; font-size: 1.3rem; margin: 15px 0;">Brand Partners</h4>
                                    <p style="color: #666; font-size: 0.95rem;">Authorized and trusted by India's leading brands</p>
                                </div>

                                <!-- Metric 2 -->
                                <div class="metric-card" style="flex: 0 0 calc(33.333% - 20px); background: white; padding: 40px 30px; border-radius: 15px; text-align: center; box-shadow: 0 10px 30px rgba(13, 43, 85, 0.1); transition: all 0.4s ease; cursor: pointer;">
                                    <div style="font-size: 3.5rem; font-weight: 700; color: #28a745; margin-bottom: 15px;">10K+</div>
                                    <h4 style="color: #0d2b55; font-size: 1.3rem; margin: 15px 0;">Orders Delivered</h4>
                                    <p style="color: #666; font-size: 0.95rem;">Successful deliveries across India with 24x7 tracking</p>
                                </div>

                                <!-- Metric 3 -->
                                <div class="metric-card" style="flex: 0 0 calc(33.333% - 20px); background: white; padding: 40px 30px; border-radius: 15px; text-align: center; box-shadow: 0 10px 30px rgba(13, 43, 85, 0.1); transition: all 0.4s ease; cursor: pointer;">
                                    <div style="font-size: 3.5rem; font-weight: 700; color: #ff6b6b; margin-bottom: 15px;">98%</div>
                                    <h4 style="color: #0d2b55; font-size: 1.3rem; margin: 15px 0;">Satisfaction Rate</h4>
                                    <p style="color: #666; font-size: 0.95rem;">Industry-leading customer satisfaction and repeat business</p>
                                </div>

                                <!-- Metric 4 -->
                                <div class="metric-card" style="flex: 0 0 calc(33.333% - 20px); background: white; padding: 40px 30px; border-radius: 15px; text-align: center; box-shadow: 0 10px 30px rgba(13, 43, 85, 0.1); transition: all 0.4s ease; cursor: pointer;">
                                    <div style="font-size: 3.5rem; font-weight: 700; color: #007bff; margin-bottom: 15px;">8</div>
                                    <h4 style="color: #0d2b55; font-size: 1.3rem; margin: 15px 0;">Core Services</h4>
                                    <p style="color: #666; font-size: 0.95rem;">Comprehensive solutions from branding to delivery</p>
                                </div>

                                <!-- Metric 5 -->
                                <div class="metric-card" style="flex: 0 0 calc(33.333% - 20px); background: white; padding: 40px 30px; border-radius: 15px; text-align: center; box-shadow: 0 10px 30px rgba(13, 43, 85, 0.1); transition: all 0.4s ease; cursor: pointer;">
                                    <div style="font-size: 3.5rem; font-weight: 700; color: #17a2b8; margin-bottom: 15px;">15+</div>
                                    <h4 style="color: #0d2b55; font-size: 1.3rem; margin: 15px 0;">Years Experience</h4>
                                    <p style="color: #666; font-size: 0.95rem;">Decades of expertise in corporate gifting industry</p>
                                </div>
                            </div>

                            <!-- Slider Indicators -->
                            <div style="display: flex; gap: 8px; margin-top: 30px; justify-content: center;">
                                <div class="indicator" style="width: 12px; height: 12px; border-radius: 50%; background: #0d2b55; cursor: pointer; transition: all 0.3s;" onclick="goToSlide('metrics', 0)"></div>
                                <div class="indicator" style="width: 12px; height: 12px; border-radius: 50%; background: #ccc; cursor: pointer; transition: all 0.3s;" onclick="goToSlide('metrics', 1)"></div>
                                <div class="indicator" style="width: 12px; height: 12px; border-radius: 50%; background: #ccc; cursor: pointer; transition: all 0.3s;" onclick="goToSlide('metrics', 2)"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Slider 2: Process & Workflow -->
                    <div class="informatic-slider-wrapper mb-80">
                        <div class="slider-header" style="text-align: center; margin-bottom: 50px;">
                            <h2 style="font-size: 2.5rem; color: #0d2b55; margin-bottom: 15px; font-weight: 700;">
                                Our <span style="color: #ffc107;">Simple Process</span>
                            </h2>
                            <p style="font-size: 1.1rem; color: #555; max-width: 600px; margin: 0 auto;">
                                From imagination to delivery - we make corporate gifting effortless
                            </p>
                        </div>

                        <div class="process-slider-container" style="position: relative;">
                            <!-- Slider Navigation -->
                            <button class="slider-nav-btn prev" onclick="moveSlider('process', -1)" style="position: absolute; left: -50px; top: 50%; transform: translateY(-50%); z-index: 10; background: #ffc107; color: white; border: none; width: 45px; height: 45px; border-radius: 50%; cursor: pointer; font-size: 20px; transition: all 0.3s;">❮</button>
                            <button class="slider-nav-btn next" onclick="moveSlider('process', 1)" style="position: absolute; right: -50px; top: 50%; transform: translateY(-50%); z-index: 10; background: #ffc107; color: white; border: none; width: 45px; height: 45px; border-radius: 50%; cursor: pointer; font-size: 20px; transition: all 0.3s;">❯</button>

                            <!-- Slider Track -->
                            <div class="process-track" style="display: flex; gap: 25px; overflow: hidden; padding: 20px 0;">
                                <!-- Process 1 -->
                                <div class="process-card" style="flex: 0 0 calc(50% - 12.5px); background: linear-gradient(135deg, #0d2b55 0%, #1a4080 100%); padding: 50px 35px; border-radius: 20px; color: white; text-align: center; transition: all 0.4s ease; cursor: pointer; position: relative; overflow: hidden;">
                                    <div style="font-size: 4rem; font-weight: 700; margin-bottom: 20px; opacity: 0.2; position: absolute; top: -10px; right: -10px;">1</div>
                                    <div style="font-size: 3rem; margin-bottom: 20px; z-index: 2; position: relative;">📋</div>
                                    <h4 style="font-size: 1.5rem; margin: 20px 0; font-weight: 700;">Consultation</h4>
                                    <p style="font-size: 0.95rem; line-height: 1.6; opacity: 0.9;">Share your requirements, budget, and preferences. Our expert team will guide you through every option.</p>
                                </div>

                                <!-- Process 2 -->
                                <div class="process-card" style="flex: 0 0 calc(50% - 12.5px); background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%); padding: 50px 35px; border-radius: 20px; color: white; text-align: center; transition: all 0.4s ease; cursor: pointer; position: relative; overflow: hidden;">
                                    <div style="font-size: 4rem; font-weight: 700; margin-bottom: 20px; opacity: 0.2; position: absolute; top: -10px; right: -10px;">2</div>
                                    <div style="font-size: 3rem; margin-bottom: 20px; z-index: 2; position: relative;">🎨</div>
                                    <h4 style="font-size: 1.5rem; margin: 20px 0; font-weight: 700;">Customization</h4>
                                    <p style="font-size: 0.95rem; line-height: 1.6; opacity: 0.9;">Personalize products with your branding, logos, and messaging. We handle all customization in-house.</p>
                                </div>

                                <!-- Process 3 -->
                                <div class="process-card" style="flex: 0 0 calc(50% - 12.5px); background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 50px 35px; border-radius: 20px; color: white; text-align: center; transition: all 0.4s ease; cursor: pointer; position: relative; overflow: hidden;">
                                    <div style="font-size: 4rem; font-weight: 700; margin-bottom: 20px; opacity: 0.2; position: absolute; top: -10px; right: -10px;">3</div>
                                    <div style="font-size: 3rem; margin-bottom: 20px; z-index: 2; position: relative;">📦</div>
                                    <h4 style="font-size: 1.5rem; margin: 20px 0; font-weight: 700;">Packaging</h4>
                                    <p style="font-size: 0.95rem; line-height: 1.6; opacity: 0.9;">Premium packaging and quality assurance ensure your gifts arrive in perfect condition.</p>
                                </div>

                                <!-- Process 4 -->
                                <div class="process-card" style="flex: 0 0 calc(50% - 12.5px); background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); padding: 50px 35px; border-radius: 20px; color: white; text-align: center; transition: all 0.4s ease; cursor: pointer; position: relative; overflow: hidden;">
                                    <div style="font-size: 4rem; font-weight: 700; margin-bottom: 20px; opacity: 0.2; position: absolute; top: -10px; right: -10px;">4</div>
                                    <div style="font-size: 3rem; margin-bottom: 20px; z-index: 2; position: relative;">🚚</div>
                                    <h4 style="font-size: 1.5rem; margin: 20px 0; font-weight: 700;">Fast Delivery</h4>
                                    <p style="font-size: 0.95rem; line-height: 1.6; opacity: 0.9;">Pan-India logistics network with 24x7 tracking. Delivered on time, every time.</p>
                                </div>
                            </div>

                            <!-- Slider Indicators -->
                            <div style="display: flex; gap: 8px; margin-top: 30px; justify-content: center;">
                                <div class="indicator" style="width: 12px; height: 12px; border-radius: 50%; background: #0d2b55; cursor: pointer; transition: all 0.3s;" onclick="goToSlide('process', 0)"></div>
                                <div class="indicator" style="width: 12px; height: 12px; border-radius: 50%; background: #ccc; cursor: pointer; transition: all 0.3s;" onclick="goToSlide('process', 1)"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Slider 3: Features & Benefits -->
                    <div class="informatic-slider-wrapper">
                        <div class="slider-header" style="text-align: center; margin-bottom: 50px;">
                            <h2 style="font-size: 2.5rem; color: #0d2b55; margin-bottom: 15px; font-weight: 700;">
                                Premium <span style="color: #ffc107;">Features</span>
                            </h2>
                            <p style="font-size: 1.1rem; color: #555; max-width: 600px; margin: 0 auto;">
                                What makes us India's #1 corporate gifting platform
                            </p>
                        </div>

                        <div class="features-slider-container" style="position: relative;">
                            <button type="button" class="features-mobile-nav prev" aria-label="Previous feature">&#10094;</button>
                            <div class="features-track" style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 24px; align-items: stretch; padding: 20px 0;">
                                <div class="feature-card" style="background: white; padding: 30px 22px; border-radius: 16px; box-shadow: 0 10px 25px rgba(13, 43, 85, 0.08); transition: all 0.4s ease; cursor: pointer; border-top: 4px solid #ffc107; min-height: 220px; display: flex; flex-direction: column; justify-content: flex-start;">
                                    <div style="font-size: 2.2rem; margin-bottom: 18px;">✅</div>
                                    <h4 style="color: #0d2b55; font-size: 1.2rem; margin: 0 0 12px; font-weight: 700;">100% Authentic</h4>
                                    <p style="color: #666; font-size: 0.95rem; line-height: 1.6; margin: 0;">Genuine products from authorized distributors. No counterfeits, guaranteed.</p>
                                </div>

                                <div class="feature-card" style="background: white; padding: 30px 22px; border-radius: 16px; box-shadow: 0 10px 25px rgba(13, 43, 85, 0.08); transition: all 0.4s ease; cursor: pointer; border-top: 4px solid #28a745; min-height: 220px; display: flex; flex-direction: column; justify-content: flex-start;">
                                    <div style="font-size: 2.2rem; margin-bottom: 18px;">⚡</div>
                                    <h4 style="color: #0d2b55; font-size: 1.2rem; margin: 0 0 12px; font-weight: 700;">Quick Turnaround</h4>
                                    <p style="color: #666; font-size: 0.95rem; line-height: 1.6; margin: 0;">Rush deliveries available. Get your orders within 24-48 hours.</p>
                                </div>

                                <div class="feature-card" style="background: white; padding: 30px 22px; border-radius: 16px; box-shadow: 0 10px 25px rgba(13, 43, 85, 0.08); transition: all 0.4s ease; cursor: pointer; border-top: 4px solid #007bff; min-height: 220px; display: flex; flex-direction: column; justify-content: flex-start;">
                                    <div style="font-size: 2.2rem; margin-bottom: 18px;">💰</div>
                                    <h4 style="color: #0d2b55; font-size: 1.2rem; margin: 0 0 12px; font-weight: 700;">Best Pricing</h4>
                                    <p style="color: #666; font-size: 0.95rem; line-height: 1.6; margin: 0;">Competitive rates with bulk discounts. Transparent pricing with no hidden charges.</p>
                                </div>

                                <div class="feature-card" style="background: white; padding: 30px 22px; border-radius: 16px; box-shadow: 0 10px 25px rgba(13, 43, 85, 0.08); transition: all 0.4s ease; cursor: pointer; border-top: 4px solid #ff6b6b; min-height: 220px; display: flex; flex-direction: column; justify-content: flex-start;">
                                    <div style="font-size: 2.2rem; margin-bottom: 18px;">🎯</div>
                                    <h4 style="color: #0d2b55; font-size: 1.2rem; margin: 0 0 12px; font-weight: 700;">Full Customization</h4>
                                    <p style="color: #666; font-size: 0.95rem; line-height: 1.6; margin: 0;">Print, engrave, or embroider your branding. Unlimited customization options.</p>
                                </div>

                                <div class="feature-card" style="background: white; padding: 30px 22px; border-radius: 16px; box-shadow: 0 10px 25px rgba(13, 43, 85, 0.08); transition: all 0.4s ease; cursor: pointer; border-top: 4px solid #17a2b8; min-height: 220px; display: flex; flex-direction: column; justify-content: flex-start;">
                                    <div style="font-size: 2.2rem; margin-bottom: 18px;">🏆</div>
                                    <h4 style="color: #0d2b55; font-size: 1.2rem; margin: 0 0 12px; font-weight: 700;">Expert Support</h4>
                                    <p style="color: #666; font-size: 0.95rem; line-height: 1.6; margin: 0;">24x7 customer support. Dedicated account managers for bulk orders.</p>
                                </div>

                                <div class="feature-card" style="background: white; padding: 30px 22px; border-radius: 16px; box-shadow: 0 10px 25px rgba(13, 43, 85, 0.08); transition: all 0.4s ease; cursor: pointer; border-top: 4px solid #6f42c1; min-height: 220px; display: flex; flex-direction: column; justify-content: flex-start;">
                                    <div style="font-size: 2.2rem; margin-bottom: 18px;">🌐</div>
                                    <h4 style="color: #0d2b55; font-size: 1.2rem; margin: 0 0 12px; font-weight: 700;">Pan-India Reach</h4>
                                    <p style="color: #666; font-size: 0.95rem; line-height: 1.6; margin: 0;">Deliver anywhere in India. With our logistics network covering all major cities.</p>
                                </div>
                            </div>
                            <button type="button" class="features-mobile-nav next" aria-label="Next feature">&#10095;</button>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Informatic Dynamic Sliders Section -->

             <!-- Services Section -->
             <section class="page-section pt-50" id="services">                    
                    <div class="container position-relative">
                        
                        <div class="row mb-n30">
                            
                            <!-- Services Item (Exclusive Corporate Gifting) -->
                            <div class="col-md-6 col-lg-4 d-flex align-items-stretch mb-30">
                                <div class="services-3-item round text-center">
                                    <div class="wow fadeInUpShort" data-wow-offset="50">
                                        
                                        <div class="services-3-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="64" viewBox="0 0 48 64" aria-hidden="true">
                                                <path d="M16 18.75l32 32-4.25 4.25-32-32zM18.625 27l25.125 25.125 1.375-1.375-25.125-25.25zM15 13v-5h2v5h-2zM15 38.5v-5h2v5h-2zM27 24v-2h5v2h-5zM0 24v-2h5v2h-5zM5.875 11.75l3.625 3.625-1.5 1.375-3.5-3.5zM9.5 31.25l-3.625 3.5-1.375-1.375 3.5-3.5zM27.5 13.25l-3.5 3.5-1.5-1.375 3.625-3.625z"></path>
                                            </svg>
                                        </div>
                                        
                                        <h3 class="services-3-title">
                                        Exclusive Corporate Gifting
                                        </h3>
                                        
                                        <div class="services-3-text">
                                        Elevate your corporate relationships with our exclusive gifting options, tailored to leave a lasting impression.

                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <!-- End Services Item-->
                            
                            <!-- Services Item (Customizable Packages) -->
                            <div class="col-md-6 col-lg-4 d-flex align-items-stretch mb-30">
                                <div class="services-3-item round text-center">
                                    <div class="wow fadeInUpShort" data-wow-offset="50">
                                        
                                        <div class="services-3-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="64" viewBox="0 0 60 64" aria-hidden="true">
                                                <path d="M60 48h-22v2h8v2h-32.125v-2h8v-2h-21.875v-36h60v36zM2 14v32h56v-32h-56z"></path>
                                            </svg>
                                        </div>
                                        
                                        <h3 class="services-3-title">
                                        Customizable Packages
                                        </h3>
                                        
                                        <div class="services-3-text">
                                        We understand that every occasion is unique. Our customizable solutions allow you to create personalized gift packages for any event or celebration.

                                        </div>
                                    
                                    </div>
                                </div>
                            </div>
                            <!-- End Services Item-->
                            
                            <!-- Services (Gift Vouchers) -->
                            <div class="col-md-6 col-lg-4 d-flex align-items-stretch mb-30">
                                <div class="services-3-item round text-center">
                                    <div class="wow fadeInUpShort" data-wow-offset="50">
                                        
                                        <div class="services-3-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="64" viewBox="0 0 50 64" aria-hidden="true">
                                                <path d="M0 11h50v42h-50v-42zM9 51v-6h-7v6h7zM9 43v-6h-7v6h7zM9 35v-6h-7v6h7zM9 27v-6h-7v6h7zM9 19v-6h-7v6h7zM39 51v-18h-28v18h28zM39 31v-18h-28v18h28zM48 51v-6h-7v6h7zM48 43v-6h-7v6h7zM48 35v-6h-7v6h7zM48 27v-6h-7v6h7zM48 19v-6h-7v6h7z"></path>
                                            </svg>
                                        </div>
                                        
                                        <h3 class="services-3-title">
                                        Gift Vouchers
                                        </h3>
                                        
                                        <div class="services-3-text">
                                        Give the gift of choice with our versatile gift vouchers, allowing recipients to select their preferred products or experiences.

                                        </div>
                                    
                                    </div>
                                </div>
                            </div>
                            <!-- End Services Item-->
                            
                            <!-- Services Item (Brand Embossed Gifts) -->
                            <div class="col-md-6 col-lg-4 d-flex align-items-stretch mb-30">
                                <div class="services-3-item round text-center">
                                    <div class="wow fadeInUpShort" data-wow-offset="50">
                                        
                                        <div class="services-3-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="64" viewBox="0 0 48 64" aria-hidden="true">
                                                <path d="M24.125 20c3.25 0 6.25 1.25 8.5 3.5s3.5 5.25 3.5 8.5-1.25 6.25-3.5 8.5-5.25 3.5-8.5 3.5-6.25-1.25-8.5-3.5-3.5-5.25-3.5-8.5 1.25-6.25 3.5-8.5 5.25-3.5 8.5-3.5zM24.125 42c5.5 0 10-4.5 10-10s-4.5-10-10-10-10 4.5-10 10 4.5 10 10 10zM17.25 10.375c-1.25 0.375-2.5 0.875-3.75 1.5 0.25 1 0.125 2 0 3-0.25 1.625-1 3.125-2.25 4.375-1.5 1.5-3.625 2.375-5.75 2.375-0.5 0-1.125 0-1.625-0.125-0.625 1.25-1.125 2.5-1.5 3.75 0.875 0.5 1.5 1.25 2.125 2.125 1 1.375 1.5 3 1.5 4.625s-0.5 3.25-1.5 4.625c-0.625 0.875-1.25 1.625-2.125 2.125 0.375 1.25 0.875 2.5 1.5 3.75 0.5-0.125 1.125-0.125 1.625-0.125 2.125 0 4.25 0.875 5.75 2.375 1.25 1.25 2 2.75 2.25 4.375 0.125 1 0.25 2 0 3 1.25 0.625 2.5 1.125 3.75 1.5 0.5-0.875 1.25-1.5 2.125-2.125 1.375-1 3-1.5 4.625-1.5s3.25 0.5 4.625 1.5c0.875 0.625 1.625 1.25 2.125 2.125 1.25-0.375 2.5-0.875 3.75-1.5-0.25-1-0.125-2 0-3 0.25-1.625 1-3.125 2.25-4.375 1.5-1.5 3.625-2.375 5.75-2.375 0.5 0 1.125 0 1.625 0.125 0.625-1.25 1.125-2.5 1.5-3.75-0.875-0.5-1.5-1.25-2.125-2.125-1-1.375-1.5-3-1.5-4.625s0.5-3.25 1.5-4.625c0.625-0.875 1.25-1.625 2.125-2.125-0.375-1.25-0.875-2.5-1.5-3.75-0.5 0.125-1.125 0.125-1.625 0.125-2.125 0-4.25-0.875-5.75-2.375-1.25-1.25-2-2.75-2.25-4.375-0.125-1-0.25-2 0-3-1.25-0.625-2.5-1.125-3.75-1.5-0.5 0.875-1.25 1.5-2.125 2.125-1.375 1-3 1.5-4.625 1.5s-3.25-0.5-4.625-1.5c-0.875-0.625-1.625-1.25-2.125-2.125zM29.75 8v0c2.5 0.625 5 1.625 7.125 3-1 2.25-0.625 5 1.25 6.875 1.25 1.25 2.75 1.75 4.375 1.75 0.875 0 1.75-0.125 2.5-0.5 1.375 2.125 2.375 4.625 3 7.125-2.375 0.875-4 3.125-4 5.75s1.75 4.875 4 5.75c-0.625 2.5-1.625 5-3 7.125-0.75-0.375-1.625-0.5-2.5-0.5-1.625 0-3.125 0.5-4.375 1.75-1.875 1.875-2.25 4.625-1.25 6.875-2.125 1.375-4.625 2.375-7.125 3-0.875-2.25-3.125-4-5.75-4s-4.875 1.75-5.75 4c-2.5-0.625-5-1.625-7.125-3 1-2.25 0.625-5-1.25-6.875-1.25-1.25-2.75-1.75-4.375-1.75-0.875 0-1.75 0.125-2.5 0.5-1.375-2.125-2.375-4.625-3-7.125 2.25-0.875 4-3.125 4-5.75s-1.625-4.875-4-5.75c0.625-2.5 1.625-5 3-7.125 0.75 0.375 1.625 0.5 2.5 0.5 1.625 0 3.125-0.5 4.375-1.75 1.875-1.875 2.25-4.625 1.25-6.875 2.125-1.375 4.625-2.375 7.125-3 0.875 2.375 3.125 4 5.75 4s4.875-1.625 5.75-4z"></path>
                                            </svg>
                                        </div>
                                        
                                        <h3 class="services-3-title">
                                        Brand Embossed Gifts
                                        </h3>
                                        
                                        <div class="services-3-text">
                                        Make a statement with brand-embossed gifts, leaving a lasting impression and reinforcing brand recognition.
                                        </div>
                                    
                                    </div>
                                    
                                </div>
                            </div>
                            <!-- End Services Item-->
                            
                            <!-- Services Item (Employee Gifting Programs) -->
                            <div class="col-md-6 col-lg-4 d-flex align-items-stretch mb-30">
                                <div class="services-3-item round text-center">
                                    <div class="wow fadeInUpShort" data-wow-offset="50">
                                        
                                        <div class="services-3-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="64" viewBox="0 0 48 64" aria-hidden="true">
                                                <path d="M44.25 20c2.25 0 3.75 1.625 3.75 3.875v22c0 2.25-1.5 4.125-3.75 4.125h-40c-2.25 0-4.25-1.875-4.25-4.125v-22c0-2.25 2-3.875 4.25-3.875h1.625v-2h4.25v2h1.625c4-4.5 5.375-6 6.875-6h11c1.5 0 2.875 1.5 6.875 6h7.75zM46 45.875v-22c0-1.125-0.625-1.875-1.75-1.875h-8.625l-0.625-0.375c-0.375-0.5-0.875-1-1.25-1.375-1.5-1.625-2.5-3-3.25-3.75-0.625-0.625-0.875-0.5-0.875-0.5h-11s-0.25 0-0.875 0.5c-0.75 0.625-1.75 1.75-3.125 3.375-0.375 0.5-0.875 1.25-1.375 1.75l-0.625 0.375h-8.375c-1.125 0-2.25 0.875-2.25 1.875v22c0 1.125 1.125 2.125 2.25 2.125h40c1 0 1.75-1 1.75-2.125zM24 23.75c5.875 0 10.75 4.75 10.75 10.625s-4.875 10.625-10.75 10.625-10.75-4.75-10.75-10.625 4.875-10.625 10.75-10.625zM24 43c4.75 0 8.75-3.875 8.75-8.625s-4-8.625-8.75-8.625-8.75 3.875-8.75 8.625 4 8.625 8.75 8.625zM36 26.125v-2.125h2.125v2.125h-2.125zM20 34.375c0-2.625 1.375-4 4-4s4 1.375 4 4-1.375 4-4 4-4-1.375-4-4z"></path>
                                            </svg>
                                        </div>
                                        
                                        <h3 class="services-3-title">
                                        Employee Gifting Programs
                                        </h3>
                                        
                                        
                                        <div class="services-3-text">
                                        Foster a positive work culture with our employee gifting programs, recognizing and appreciating the valuable contributions of your team.

                                        </div>
                                    
                                    </div>
                                </div>
                            </div>
                            <!-- End Services Item-->
                            
                            <!-- Services Item-->
                            <div class="col-md-6 col-lg-4 d-flex align-items-stretch mb-30">
                                <div class="services-3-item round text-center">
                                    <div class="wow fadeInUpShort" data-wow-offset="50">
                                        
                                        <div class="services-3-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="64" viewBox="0 0 36 64" aria-hidden="true">
                                                <path d="M4 20v-8h28v8h-28zM6 14v4h24v-4h-24zM32 8c2.25 0 4 1.75 4 4v40c0 2.25-1.75 4-4 4h-28c-2.25 0-4-1.75-4-4v-40c0-2.25 1.75-4 4-4h28zM34 52v-40c0-1.125-0.875-2-2-2h-28c-1.125 0-2 0.875-2 2v40c0 1.125 0.875 2 2 2h28c1.125 0 2-0.875 2-2zM6 30h6v2h-8v-8h2v6zM6 40h6v2h-8v-8h2v6zM6 50h6v2h-8v-8h2v6zM16 30h6v2h-8v-8h2v6zM26 30h6v2h-8v-8h2v6zM16 40h6v2h-8v-8h2v6zM16 50h6v2h-8v-8h2v6zM26 50h6v2h-8v-18h2v16z"></path>
                                            </svg>
                                        </div>
                                        
                                        <h3 class="services-3-title">
                                        Channel & Trade Loyalty Programs                                        </h3>
                                        
                                        <div class="services-3-text">
                                        Strengthen relationships with partners through channel and trade loyalty programs, designed to incentivize and reward loyalty.

                                        </div>
                                    
                                    </div>
                                </div>
                            </div>
                            <!-- End Services Item-->
                            

         <!-- Services Item-->
         <div class="col-md-6 col-lg-4 d-flex align-items-stretch mb-30">
                                <div class="services-3-item round text-center">
                                    <div class="wow fadeInUpShort" data-wow-offset="50">
                                        
                                        <div class="services-3-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="64" viewBox="0 0 36 64" aria-hidden="true">
                                                <path d="M4 20v-8h28v8h-28zM6 14v4h24v-4h-24zM32 8c2.25 0 4 1.75 4 4v40c0 2.25-1.75 4-4 4h-28c-2.25 0-4-1.75-4-4v-40c0-2.25 1.75-4 4-4h28zM34 52v-40c0-1.125-0.875-2-2-2h-28c-1.125 0-2 0.875-2 2v40c0 1.125 0.875 2 2 2h28c1.125 0 2-0.875 2-2zM6 30h6v2h-8v-8h2v6zM6 40h6v2h-8v-8h2v6zM6 50h6v2h-8v-8h2v6zM16 30h6v2h-8v-8h2v6zM26 30h6v2h-8v-8h2v6zM16 40h6v2h-8v-8h2v6zM16 50h6v2h-8v-8h2v6zM26 50h6v2h-8v-18h2v16z"></path>
                                            </svg>
                                        </div>
                                        
                                        <h3 class="services-3-title">
                                        Internal Team & Stakeholders Programs                          </h3>
                                        
                                        <div class="services-3-text">
                                        Build strong internal relationships and engage stakeholders with our thoughtfully curated gifting programs.

                                        </div>
                                    
                                    </div>
                                </div>
                            </div>
                            <!-- End Services Item-->


                                     <!-- Services Item-->
                                     <div class="col-md-6 col-lg-4 d-flex align-items-stretch mb-30">
                                <div class="services-3-item round text-center">
                                    <div class="wow fadeInUpShort" data-wow-offset="50">
                                        
                                        <div class="services-3-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="64" viewBox="0 0 36 64" aria-hidden="true">
                                                <path d="M4 20v-8h28v8h-28zM6 14v4h24v-4h-24zM32 8c2.25 0 4 1.75 4 4v40c0 2.25-1.75 4-4 4h-28c-2.25 0-4-1.75-4-4v-40c0-2.25 1.75-4 4-4h28zM34 52v-40c0-1.125-0.875-2-2-2h-28c-1.125 0-2 0.875-2 2v40c0 1.125 0.875 2 2 2h28c1.125 0 2-0.875 2-2zM6 30h6v2h-8v-8h2v6zM6 40h6v2h-8v-8h2v6zM6 50h6v2h-8v-8h2v6zM16 30h6v2h-8v-8h2v6zM26 30h6v2h-8v-8h2v6zM16 40h6v2h-8v-8h2v6zM16 50h6v2h-8v-8h2v6zM26 50h6v2h-8v-18h2v16z"></path>
                                            </svg>
                                        </div>
                                        
                                        <h3 class="services-3-title">
                                        Influencer Loyalty                                       </h3>
                                        
                                        <div class="services-3-text">
                                        Acknowledge and appreciate the contributions of influencers with our specialized loyalty programs, fostering long-term partnerships.

                                        </div>
                                    
                                    </div>
                                </div>
                            </div>
                            <!-- End Services Item-->


                                     <!-- Services Item-->
                                     <div class="col-md-6 col-lg-4 d-flex align-items-stretch mb-30">
                                <div class="services-3-item round text-center">
                                    <div class="wow fadeInUpShort" data-wow-offset="50">
                                        
                                        <div class="services-3-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="64" viewBox="0 0 36 64" aria-hidden="true">
                                                <path d="M4 20v-8h28v8h-28zM6 14v4h24v-4h-24zM32 8c2.25 0 4 1.75 4 4v40c0 2.25-1.75 4-4 4h-28c-2.25 0-4-1.75-4-4v-40c0-2.25 1.75-4 4-4h28zM34 52v-40c0-1.125-0.875-2-2-2h-28c-1.125 0-2 0.875-2 2v40c0 1.125 0.875 2 2 2h28c1.125 0 2-0.875 2-2zM6 30h6v2h-8v-8h2v6zM6 40h6v2h-8v-8h2v6zM6 50h6v2h-8v-8h2v6zM16 30h6v2h-8v-8h2v6zM26 30h6v2h-8v-8h2v6zM16 40h6v2h-8v-8h2v6zM16 50h6v2h-8v-8h2v6zM26 50h6v2h-8v-18h2v16z"></path>
                                            </svg>
                                        </div>
                                        
                                        <h3 class="services-3-title">
                                        Seasonal Gifting                                       </h3>
                                        
                                        <div class="services-3-text">
                                        Celebrate every season with our seasonal gifting solutions, tailored to capture the essence of festive occasions.

                                        </div>
                                    
                                    </div>
                                </div>
                            </div>
                            <!-- End Services Item-->


                        </div>
                    
                    </div>
                </section>
                <!-- End Services Section -->
    
                <!-- Call to Action Section -->
                <section class="full-wrapper">
                    <div class="page-section bg-border-gradient pt-0 pb-0 scroll-nav-invisible z-index-1">
                        <div class="page-section container position-relative scroll-nav-invisible">
                            
                            <!-- Decoration Image -->
                            <div class="decoration-image-1" data-rellax-y data-rellax-speed="0.5" data-rellax-percentage="0.5">
                                <img src="images/demo-gradient/section-image-6.jpg" alt="Image Description" />
                            </div>
                            <!-- End Decoration Image -->
                            
                            <!-- Decoration Image -->
                            <div class="decoration-image-2" data-rellax-y data-rellax-speed="-0.5" data-rellax-percentage="0.4">
                                <img src="images/demo-gradient/section-image-7.jpg" alt="Image Description" />
                            </div>
                            <!-- End Decoration Image -->
                            
                            <div class="row text-center">
                                <div class="col-md-8 offset-md-2">
                                    <p class="section-descr mb-50 mb-sm-40" style="color: #231f75;">
                                        Looking for exclusive products? Contact us and get free consultation for your brand or your client's brand.
                                    </p>
                                    <div class="local-scroll">
                                        <a href="contact#contact_form" class="btn btn-mod btn-large btn-round btn-hover-anim"><span>Contact us</span></a>
                                    </div>
                                </div>                             
                            </div>
                            
                        </div>
                    </div>
                </section>
                   <!-- Logotypes Section -->
                   <!-- <section class="small-section pt-20 pb-20 services-brand-partners">
                    <div class="container relative">
                        
                        <div class="row wow fadeInUpShort">
                            <div class="col-md-12">
                                <h2 class="section-title-tiny mb-30 text-center">Authorised Brand Partners</h2>
                                <?php if (!empty($brandPartners)): ?>
                                    <div class="small-item-carousel black owl-carousel mb-0">
                                        <?php foreach ($brandPartners as $brand): ?>
                                            <div class="logo-item">
                                                <a href="brand-products.php?brand=<?= intval($brand['id']) ?>">
                                                    <img class="aiir_brand_img" src="<?= htmlspecialchars($brand['logoUrl']) ?>" width="150" height="90" alt="<?= htmlspecialchars($brand['brandname']) ?>" />
                                                </a>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-center">No authorised brand partners are available right now.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row wow fadeInUpShort mt-4">
                            <div class="col-md-12">
                                <h2 class="section-title-tiny mb-30 text-center">Also Deal With</h2>
                                <?php if (!empty($alsoDealWith)): ?>
                                    <div class="small-item-carousel black owl-carousel mb-0">
                                        <?php foreach ($alsoDealWith as $brand): ?>
                                            <div class="logo-item">
                                                <a href="brand-products.php?brand=<?= intval($brand['id']) ?>">
                                                    <img class="aiir_brand_img" src="<?= htmlspecialchars($brand['logoUrl']) ?>" width="150" height="90" alt="<?= htmlspecialchars($brand['brandname']) ?>" />
                                                </a>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-center">No "Also Deal With" brands are available right now.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                     </div>
                </section> -->
                <!-- End Logotypes -->
                
               
                <!-- End Call to Action Section -->
                    <!-- Parallax Image Section -->
                    <section class="page-section bg-gray-light-1 bg-light-alpha-70 bg-scroll pb-0 mb-100 mb-md-70 mb-sm-50 z-index-1" style="background-image: url(images/full-width-images/section-bg-9.jpg)">
                    <div class="container position-relative">                    
                        
                        <div class="row">
                            <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2 text-center">
                                
                                <h2 class="section-caption mb-xs-10 wow fadeInUp">Our Values</h2>
                                
                                <h3 class="section-title mb-60 mb-sm-40"><span class="wow charsAnimIn" data-splitting="chars">We prioritize quality, trust, and collaboration as essential pillars of our approach.</span></h3>
                                
                             
                            
                            </div>
                        </div>
                    
                    </div>
                </section>
                <!-- End Parallax Image Section -->
        
            
       </main>
            
          <?php include('common/footer.php'); ?>
        
        <!-- Slider JavaScript -->
        <script>
            // Slider State Management
            const sliderState = {
                metrics: { current: 0, itemsPerView: 3, totalItems: 5 },
                process: { current: 0, itemsPerView: 2, totalItems: 4 },
                features: { current: 0, itemsPerView: 3, totalItems: 6 }
            };

            // Get the track element for a slider
            function getTrack(sliderType) {
                if (sliderType === 'metrics') return document.querySelector('.metrics-track');
                if (sliderType === 'process') return document.querySelector('.process-track');
                if (sliderType === 'features') return document.querySelector('.features-track');
            }

            // Move slider by a specified amount
            function moveSlider(sliderType, direction) {
                const state = sliderState[sliderType];
                const track = getTrack(sliderType);
                
                // Calculate max slides
                const maxSlides = Math.ceil(state.totalItems / state.itemsPerView) - 1;
                
                // Update current position
                state.current += direction;
                if (state.current < 0) state.current = 0;
                if (state.current > maxSlides) state.current = maxSlides;
                
                // Calculate scroll amount (each item has gap of 30px)
                const itemWidth = state.itemsPerView === 3 ? 'calc(33.333% - 20px)' : 
                                 state.itemsPerView === 2 ? 'calc(50% - 12.5px)' : 'calc(25% - 7.5px)';
                const scrollAmount = state.current * (window.innerWidth > 1200 ? 
                    (1200 / state.itemsPerView) : 
                    (track.offsetWidth / state.itemsPerView));
                
                track.style.transform = `translateX(-${scrollAmount * state.current / (state.itemsPerView === 3 ? 0.33 : state.itemsPerView === 2 ? 0.5 : 0.25)}px)`;
                
                // Update indicators
                updateIndicators(sliderType);
                
                // Add hover effect to cards
                addCardHoverEffects(track);
            }

            // Go to specific slide
            function goToSlide(sliderType, slideIndex) {
                const state = sliderState[sliderType];
                const maxSlides = Math.ceil(state.totalItems / state.itemsPerView) - 1;
                
                state.current = Math.min(slideIndex, maxSlides);
                const track = getTrack(sliderType);
                
                const scrollAmount = state.current * (window.innerWidth > 1200 ? 
                    (1200 / state.itemsPerView) : 
                    (track.offsetWidth / state.itemsPerView));
                
                track.style.transform = `translateX(-${scrollAmount * state.current / (state.itemsPerView === 3 ? 0.33 : state.itemsPerView === 2 ? 0.5 : 0.25)}px)`;
                
                updateIndicators(sliderType);
                addCardHoverEffects(track);
            }

            // Update indicator dots
            function updateIndicators(sliderType) {
                const state = sliderState[sliderType];
                const indicators = document.querySelectorAll(`[onclick*="'${sliderType}'"]`);
                
                indicators.forEach((indicator, index) => {
                    if (indicator.classList.contains('indicator')) {
                        indicator.style.background = index === state.current ? '#0d2b55' : '#ccc';
                    }
                });
            }

            // Add hover effects to cards
            function addCardHoverEffects(track) {
                const cards = track.querySelectorAll('[class*="-card"]');
                cards.forEach(card => {
                    card.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-10px)';
                        this.style.boxShadow = '0 20px 40px rgba(13, 43, 85, 0.2)';
                    });
                    card.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0)';
                        this.style.boxShadow = '0 10px 30px rgba(13, 43, 85, 0.1)';
                    });
                });
            }

            // Responsive adjustments
            function adjustSlidersForResponsive() {
                const width = window.innerWidth;
                
                if (width < 768) {
                    sliderState.metrics.itemsPerView = 1;
                    sliderState.process.itemsPerView = 1;
                    sliderState.features.itemsPerView = 1;
                } else if (width < 1024) {
                    sliderState.metrics.itemsPerView = 2;
                    sliderState.process.itemsPerView = 2;
                    sliderState.features.itemsPerView = 2;
                } else {
                    sliderState.metrics.itemsPerView = 3;
                    sliderState.process.itemsPerView = 2;
                    sliderState.features.itemsPerView = 3;
                }
                
                // Reset current position
                sliderState.metrics.current = 0;
                sliderState.process.current = 0;
                sliderState.features.current = 0;
            }

            // Auto-slide functionality (optional)
            function startAutoSlide(sliderType, interval = 5000) {
                setInterval(() => {
                    const state = sliderState[sliderType];
                    const maxSlides = Math.ceil(state.totalItems / state.itemsPerView) - 1;
                    
                    state.current = (state.current + 1) > maxSlides ? 0 : state.current + 1;
                    
                    const track = getTrack(sliderType);
                    const scrollAmount = state.current * (window.innerWidth > 1200 ? 
                        (1200 / state.itemsPerView) : 
                        (track.offsetWidth / state.itemsPerView));
                    
                    track.style.transform = `translateX(-${scrollAmount * state.current / (state.itemsPerView === 3 ? 0.33 : state.itemsPerView === 2 ? 0.5 : 0.25)}px)`;
                    track.style.transition = 'transform 0.6s ease-in-out';
                    
                    updateIndicators(sliderType);
                }, interval);
            }

            // Initialize sliders on page load
            document.addEventListener('DOMContentLoaded', function() {
                adjustSlidersForResponsive();
                const featuresTrack = document.querySelector('.features-track');
                document.querySelectorAll('.features-mobile-nav').forEach(function(button) {
                    button.addEventListener('click', function() {
                        if (!featuresTrack || window.innerWidth > 768) return;
                        featuresTrack.scrollBy({
                            left: button.classList.contains('next') ? featuresTrack.clientWidth : -featuresTrack.clientWidth,
                            behavior: 'smooth'
                        });
                    });
                });
                
                // Add card hover effects on all sliders
                document.querySelectorAll('.metrics-track, .process-track, .features-track').forEach(track => {
                    addCardHoverEffects(track);
                });

                // Start auto-slide for each slider (uncomment to enable)
                // startAutoSlide('metrics', 6000);
                // startAutoSlide('process', 6000);
                // startAutoSlide('features', 6000);
                
                // Handle window resize
                window.addEventListener('resize', adjustSlidersForResponsive);
            });

            // Smooth scroll on track
            document.querySelectorAll('.metrics-track, .process-track, .features-track').forEach(track => {
                track.style.transition = 'transform 0.6s ease-in-out';
            });
        </script>
        
        <!-- Slider Styles -->
        <style>
            .informatic-sliders-section {
                overflow: hidden;
            }

            .slider-header {
                animation: fadeInDown 0.8s ease-out;
            }

            .metric-card, .process-card, .feature-card {
                animation: fadeInUp 0.8s ease-out;
                transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            }

            .features-track {
                display: grid !important;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 24px;
                align-items: stretch;
            }

            .features-mobile-nav {
                display: none;
            }

            .feature-card {
                flex: unset !important;
                width: 100% !important;
                min-height: 220px;
                border: 1px solid #edf1f7;
                box-shadow: 0 10px 25px rgba(13, 43, 85, 0.08);
                background: #ffffff;
                align-items: center;
                justify-content: center !important;
                text-align: center;
            }

            .feature-card:hover {
                transform: translateY(-6px);
                box-shadow: 0 16px 30px rgba(13, 43, 85, 0.12);
            }

            .metric-card:hover, .process-card:hover {
                transform: translateY(-10px);
            }

            .slider-nav-btn:hover {
                transform: translateY(-50%) scale(1.1);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            }

            .indicator {
                animation: pulse 0.3s ease-out;
            }

            @keyframes fadeInDown {
                from {
                    opacity: 0;
                    transform: translateY(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes pulse {
                0% {
                    transform: scale(1);
                }
                50% {
                    transform: scale(1.2);
                }
                100% {
                    transform: scale(1);
                }
            }

            @media (max-width: 1024px) {
                .informatic-sliders-section {
                    padding: 60px 0;
                }

                .slider-header h2 {
                    font-size: 2rem;
                }

                .features-track {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }

                .slider-nav-btn {
                    left: 10px !important;
                    right: auto !important;
                    width: 40px !important;
                    height: 40px !important;
                }

                .slider-nav-btn.next {
                    left: auto !important;
                    right: 10px !important;
                }
            }

            @media (max-width: 768px) {
                .informatic-sliders-section {
                    padding: 40px 0;
                }

                .informatic-sliders-section .container,
                .services-brand-partners .container {
                    padding-left: 16px;
                    padding-right: 16px;
                }

                .informatic-slider-wrapper {
                    margin-bottom: 42px !important;
                }

                .informatic-slider-wrapper:last-child {
                    margin-bottom: 0 !important;
                }

                .slider-header {
                    margin-bottom: 24px !important;
                }

                .slider-header h2 {
                    font-size: 1.5rem;
                    line-height: 1.25;
                }

                .slider-header p {
                    font-size: 0.95rem !important;
                    line-height: 1.55;
                }

                .features-track {
                    display: flex !important;
                    grid-template-columns: none;
                    gap: 12px !important;
                    padding: 0 4px 10px !important;
                    overflow-x: auto;
                    overflow-y: hidden;
                    scroll-snap-type: x mandatory;
                    scroll-behavior: smooth;
                    scrollbar-width: none;
                    -webkit-overflow-scrolling: touch;
                }

                .features-track::-webkit-scrollbar {
                    display: none;
                }

                .slider-nav-btn {
                    display: none;
                }

                .metric-card, .process-card, .feature-card {
                    padding: 30px 20px !important;
                }

                .feature-card {
                    flex: 0 0 calc(100% - 8px) !important;
                    width: calc(100% - 8px) !important;
                    min-height: 0 !important;
                    padding: 22px 20px !important;
                    border-radius: 14px !important;
                    scroll-snap-align: center;
                }

                .feature-card > div:first-child {
                    margin-bottom: 12px !important;
                }

                .features-mobile-nav {
                    display: flex;
                    position: absolute;
                    top: 50%;
                    z-index: 2;
                    align-items: center;
                    justify-content: center;
                    width: 34px;
                    height: 34px;
                    border: 0;
                    border-radius: 50%;
                    background: #0d2b55;
                    color: #fff;
                    box-shadow: 0 3px 10px rgba(13, 43, 85, 0.25);
                    transform: translateY(-50%);
                }

                .features-mobile-nav.prev { left: -3px; }
                .features-mobile-nav.next { right: -3px; }

                .metric-card h4, .process-card h4, .feature-card h4 {
                    font-size: 1.1rem !important;
                }

                .metric-card p, .process-card p, .feature-card p {
                    font-size: 0.9rem !important;
                }

                .services-brand-partners {
                    padding-top: 28px !important;
                    padding-bottom: 28px !important;
                }

                .services-brand-partners .section-title-tiny {
                    margin-bottom: 18px !important;
                    font-size: 1.35rem;
                    line-height: 1.3;
                }

                .services-brand-partners .small-item-carousel {
                    padding: 0 4px;
                }

                .services-brand-partners .logo-item {
                    height: 96px;
                    margin: 0 6px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    opacity: 1;
                    background: #fff;
                    border: 1px solid #e7ebf1;
                    border-radius: 12px;
                    box-shadow: 0 4px 14px rgba(13, 43, 85, 0.08);
                }

                .services-brand-partners .logo-item a {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    width: 100%;
                    height: 100%;
                    padding: 10px;
                }

                .services-brand-partners .logo-item img.aiir_brand_img {
                    position: static;
                    transform: none;
                    width: 100% !important;
                    max-width: 130px;
                    height: 68px !important;
                    object-fit: contain;
                }

                .services-brand-partners .owl-item {
                    padding: 0 !important;
                }
            }

            @media (max-width: 420px) {
                .slider-header h2 {
                    font-size: 1.35rem;
                }

                .services-brand-partners .logo-item {
                    height: 88px;
                    margin: 0 4px;
                }

                .services-brand-partners .logo-item img.aiir_brand_img {
                    max-width: 112px;
                    height: 60px !important;
                }
            }
        </style>
    </body>
</html>
