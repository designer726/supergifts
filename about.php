<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$pagename = basename($_SERVER['PHP_SELF']);
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
            <?php
            require_once 'sgipl-manage/includes/db.php';
            $aboutBanners = [];
            $res = $conn->query("SELECT slot, title, subtitle, btn_text, btn_link, file_path, file_type FROM banners WHERE slot BETWEEN 6 AND 11 AND status=1 ORDER BY slot ASC");
            while ($row = $res->fetch_assoc()) {
                if (!empty($row['file_path'])) {
                    $aboutBanners[] = $row;
                }
            }
            ?>

            <?php if (!empty($aboutBanners)): ?>
            <section class="about-banner-hero">
                <div class="about-banner-container">
                    <div class="about-banner-slider fit-cover" id="aboutBannerSlider">
                        <?php foreach (array_values($aboutBanners) as $slideIndex => $banner): ?>
                            <div class="banner-slide<?= $slideIndex === 0 ? ' active' : '' ?>" data-slide="<?= $slideIndex ?>">
                                <?php if (!empty($banner['file_path']) && $banner['file_type'] === 'video'): ?>
                                    <video src="<?= htmlspecialchars($banner['file_path']) ?>" muted playsinline autoplay loop class="edia"></video>
                                <?php elseif (!empty($banner['file_path'])): ?>
                                    <img src="<?= htmlspecialchars($banner['file_path']) ?>" alt="About Banner Slot <?= $banner['slot'] ?>" class="edia" />
                                <?php else: ?>
                                    <div class="banner-fallback"></div>
                                <?php endif; ?>
                                <!-- <div class="banner-overlay">
                                    <div>
                                        <h3><?= htmlspecialchars($banner['title'] ?: 'Corporate Gifting') ?></h3>
                                        <p><?= htmlspecialchars($banner['subtitle'] ?: 'Tailored corporate gifting solutions with custom branding.') ?></p>
                                    </div>
                                    <?php if (!empty($banner['btn_link'])): ?>
                                        <a href="<?= htmlspecialchars($banner['btn_link']) ?>" class="banner-button"><?= htmlspecialchars($banner['btn_text'] ?: 'Learn More') ?></a>
                                    <?php endif; ?>
                                </div> -->
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="banner-controls" style="margin-top:12px; text-align:center;">
                        <button type="button" class="banner-nav prev" onclick="navigateBannerSlider('aboutBannerSlider', -1)">❮</button>
                        <div class="slider-dots" id="aboutBannerDots"></div>
                        <button type="button" class="banner-nav next" onclick="navigateBannerSlider('aboutBannerSlider', 1)">❯</button>
                    </div>

                    <!-- <?php if (!empty($_SESSION['admin_logged_in'])): ?>
                        <div style="margin-top:10px; text-align:center;">
                            <a href="sgipl-manage/banners/index.php" class="btn-primary" style="padding:6px 10px;font-size:13px;">Edit About Banners</a>
                            <div style="display:inline-block;margin-left:8px;">
                                <button id="fitCover" class="btn-outline" style="padding:6px 8px;font-size:13px;">Cover</button>
                                <button id="fitContain" class="btn-outline" style="padding:6px 8px;font-size:13px;">Contain</button>
                            </div>
                        </div>
                    <?php endif; ?> -->
                </div>
            </section>
            <?php endif; ?>
            <!-- End Modern Hero Section -->

            <style>
            .about-banner-hero {
                padding: 0;
                position: relative;
                overflow: hidden;
            }
            .about-banner-container {
                width: 100%;
            }
            .about-banner-slider {
                position: relative;
                width: 100%;
                height: 600px;
                min-height: 600px;
                border-radius: 0;
                overflow: hidden;
                background: #121826;
                margin-bottom: 0;
            }
            .page-banner-slider {
                position: relative;
                width: 100%;
                min-height: 280px;
                border-radius: 0;
                overflow: hidden;
                background: #121826;
                margin-bottom: 0;
            }
            .banner-slide {
                position: absolute;
                inset: 0;
                opacity: 0;
                transition: opacity .45s ease;
                display: none;
                width: 100%;
                height: 100%;
            }
            .banner-slide.active {
                opacity: 1;
                display: block;
                z-index: 1;
            }
            .edia {
                width: 100%;
                height: 100%;
                object-fit: contain;
                object-position: center;
                display: block;
            }
            .banner-slide video.edia {
                display: block;
            }
            .banner-placeholder {
                color: #e2e8f0;
                font-weight: 700;
                padding: 16px;
                text-align: center;
                width: 100%;
            }
            .banner-overlay {
                position: absolute;
                bottom: 24px;
                left: 24px;
                right: 24px;
                background: rgba(12, 17, 36, 0.75);
                border-radius: 14px;
                padding: 18px 20px;
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                flex-wrap: wrap;
                max-width: calc(100% - 48px);
            }
            .banner-overlay h3 {
                margin: 0 0 6px;
                font-size: 24px;
            }
            .banner-overlay p {
                margin: 0;
                font-size: 15px;
                color: #d1d5db;
            }
            .banner-button {
                display: inline-block;
                background: #d4af37;
                color: #111;
                padding: 10px 18px;
                border-radius: 999px;
                text-decoration: none;
                font-weight: 700;
                transition: transform .2s ease;
            }
            .banner-button:hover {
                transform: translateY(-1px);
            }
            .banner-controls {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 12px;
                margin-top: 16px;
            }
            .banner-nav {
                border: 0;
                background: rgba(0,0,0,0.55);
                color: #fff;
                width: 38px;
                height: 38px;
                border-radius: 50%;
                cursor: pointer;
                font-size: 18px;
            }
            .slider-dots {
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .slider-dots .dot {
                width: 10px;
                height: 10px;
                border-radius: 50%;
                background: rgba(255,255,255,0.35);
                cursor: pointer;
            }
            .slider-dots .dot.active {
                background: #d4af37;
            }
            .page-section#about {
                padding-top: 40px;
                padding-bottom: 40px;
            }
            #about .row {
                display: flex;
                flex-wrap: wrap;
                align-items: flex-start;
            }
            #about .col-sm-4,
            #about .col-sm-8 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            #about .call-action-4-images img {
                width: 100%;
                height: auto;
                display: block;
                border-radius: 12px;
            }
            @media (min-width: 768px) {
                #about .col-sm-4 {
                    flex: 0 0 33.3333%;
                    max-width: 33.3333%;
                }
                #about .col-sm-8 {
                    flex: 0 0 66.6667%;
                    max-width: 66.6667%;
                    margin-top: 0 !important;
                }
            }
            @media (max-width: 1200px) {
                .about-banner-slider {
                    height: 520px;
                    min-height: 520px;
                }
            }
            @media (max-width: 991px) {
                .about-banner-slider {
                    height: auto;
                    min-height: 520px;
                }
                .banner-nav {
                    width: 34px;
                    height: 34px;
                    font-size: 16px;
                }
                .slider-dots .dot {
                    width: 9px;
                    height: 9px;
                }
                .banner-overlay {
                    padding: 14px 16px;
                    bottom: 16px;
                    left: 16px;
                    right: 16px;
                }
                .banner-overlay h3 {
                    font-size: 20px;
                }
                .banner-overlay p {
                    font-size: 14px;
                }
            }
            @media (max-width: 767px) {
                .about-banner-slider {
                    width: 100%;
                    aspect-ratio: auto;
                    height: auto;
                    min-height: 0;
                    max-height: none;
                    background: transparent;
                }
                .about-banner-hero,
                .about-banner-container,
                .about-banner-slider .banner-slide {
                    width: 100%;
                    max-width: 100%;
                }
                .about-banner-slider .banner-slide.active {
                    position: relative;
                    inset: auto;
                    display: block;
                    height: auto;
                }
                .about-banner-slider .edia {
                    width: 100%;
                    height: auto;
                    max-width: 100%;
                    max-height: none;
                    object-fit: contain;
                    object-position: center;
                    vertical-align: middle;
                }
                .banner-controls {
                    flex-wrap: wrap;
                    gap: 10px;
                    margin-top: 12px;
                }
                .banner-nav {
                    width: 34px;
                    height: 34px;
                }
                .banner-overlay {
                    position: static;
                    background: rgba(12, 17, 36, 0.85);
                    border-radius: 12px;
                    padding: 14px;
                    margin: 16px;
                }
                .banner-overlay h3 {
                    font-size: 18px;
                }
                .banner-overlay p {
                    font-size: 13px;
                }
            }
            </style>

            <script>
            document.addEventListener('DOMContentLoaded', function(){
                var coverBtn = document.getElementById('fitCover');
                var containBtn = document.getElementById('fitContain');
                var container = document.querySelector('.about-banner-full');
                if(!container) return;
                var mode = localStorage.getItem('aboutBannerFit') || 'cover';
                container.classList.toggle('fit-contain', mode === 'contain');
                container.classList.toggle('fit-cover', mode !== 'contain');
                if(coverBtn) coverBtn.addEventListener('click', function(){ container.classList.remove('fit-contain'); container.classList.add('fit-cover'); localStorage.setItem('aboutBannerFit','cover'); });
                if(containBtn) containBtn.addEventListener('click', function(){ container.classList.remove('fit-cover'); container.classList.add('fit-contain'); localStorage.setItem('aboutBannerFit','contain'); });
            });

            const bannerSliderState = {};
            const bannerAutoSlideTimers = {};
            document.addEventListener('DOMContentLoaded', function() {
                initializeBannerSlider('aboutBannerSlider', 'aboutBannerDots');
                startBannerAutoSlide('aboutBannerSlider', 'aboutBannerDots');
            });

            function initializeBannerSlider(sliderId, dotsId, initialSlide = 0) {
                const slider = document.getElementById(sliderId);
                if (!slider) return;
                const slides = slider.querySelectorAll('.banner-slide');
                if (!slides.length) return;
                bannerSliderState[sliderId] = { current: initialSlide, total: slides.length };
                const dots = document.getElementById(dotsId);
                if (dots) {
                    dots.innerHTML = '';
                    slides.forEach((slide, index) => {
                        const dot = document.createElement('span');
                        dot.className = 'dot' + (index === initialSlide ? ' active' : '');
                        dot.addEventListener('click', () => {
                            goToBannerSlide(sliderId, dotsId, index);
                            startBannerAutoSlide(sliderId, dotsId);
                        });
                        dots.appendChild(dot);
                    });
                }
                updateBannerSlider(sliderId, dotsId);
            }

            function startBannerAutoSlide(sliderId, dotsId, interval = 5000) {
                if (bannerAutoSlideTimers[sliderId]) {
                    clearTimeout(bannerAutoSlideTimers[sliderId]);
                }

                const state = bannerSliderState[sliderId];
                const slider = document.getElementById(sliderId);
                if (!state || !slider || state.total < 2) return;

                const activeVideo = slider.querySelector('.banner-slide.active video');
                if (activeVideo) {
                    const scheduleVideoSlide = function() {
                        if (!activeVideo.closest('.banner-slide').classList.contains('active')) return;
                        const videoDuration = Number.isFinite(activeVideo.duration) ? activeVideo.duration * 1000 : interval;
                        bannerAutoSlideTimers[sliderId] = setTimeout(function() {
                            navigateBannerSlider(sliderId, 1);
                        }, videoDuration);
                    };

                    if (activeVideo.readyState >= 1) {
                        scheduleVideoSlide();
                    } else {
                        activeVideo.addEventListener('loadedmetadata', scheduleVideoSlide, { once: true });
                    }
                    return;
                }

                bannerAutoSlideTimers[sliderId] = setTimeout(function() {
                    navigateBannerSlider(sliderId, 1);
                }, interval);
            }

            function navigateBannerSlider(sliderId, delta) {
                const state = bannerSliderState[sliderId];
                if (!state) return;
                state.current += delta;
                if (state.current < 0) state.current = state.total - 1;
                if (state.current >= state.total) state.current = 0;
                updateBannerSlider(sliderId, sliderId === 'aboutBannerSlider' ? 'aboutBannerDots' : 'homeBannerDots');
                startBannerAutoSlide(sliderId, sliderId === 'aboutBannerSlider' ? 'aboutBannerDots' : 'homeBannerDots');
            }

            function goToBannerSlide(sliderId, dotsId, slideIndex) {
                const state = bannerSliderState[sliderId];
                if (!state) return;
                state.current = slideIndex;
                updateBannerSlider(sliderId, dotsId);
                startBannerAutoSlide(sliderId, dotsId);
            }

            function updateBannerSlider(sliderId, dotsId) {
                const slider = document.getElementById(sliderId);
                if (!slider) return;
                const slides = slider.querySelectorAll('.banner-slide');
                slides.forEach((slide, index) => {
                    const isActive = index === bannerSliderState[sliderId].current;
                    slide.classList.toggle('active', isActive);
                    const video = slide.querySelector('video');
                    if (video) {
                        if (isActive) {
                            video.play().catch(function() {});
                        } else {
                            video.pause();
                        }
                    }
                });
                const dots = document.getElementById(dotsId);
                if (dots) {
                    dots.querySelectorAll('.dot').forEach((dot, index) => {
                        dot.classList.toggle('active', index === bannerSliderState[sliderId].current);
                    });
                }
            }
            </script>
            
            
            <!-- Marquee Section 
            <div class="page-section overflow-hidden pt-0 pb-0">
                
                <div class="marquee marquee-style-2 no-rotate" style="background: #007e48;">
                    <div class="marquee-track marquee-animation-1">
                        <div>Your Values - Our Vision</div>
                        <div>Our vision is to provide a personalized approach to enhance your gifting experience, the same way as you would do it for your employees, teams and partners.
</div>
                        <div aria-hidden="true">Your Values - Our Vision</div>
                        <div aria-hidden="true">Our vision is to provide a personalized approach to enhance your gifting experience, the same way as you would do it for your employees, teams and partners.</div>
                        <div aria-hidden="true">Your Values - Our Vision</div>
                        <div aria-hidden="true">Our vision is to provide a personalized approach to enhance your gifting experience, the same way as you would do it for your employees, teams and partners.</div>                        
                    </div>
                </div>                
                    
            </div>-->
            <!-- End Marquee Section -->
            
            
            <!-- About Section -->
            <section class="page-section" id="about">                    
                <div class="container">                        
                    
                    <div class="row">
                        
                        <div class="col-sm-4 mb-xs-50">
                            <div class="call-action-4-images">
                            <img src="images/about.jpg" alt="Image description" class="wow scaleOutIn" data-wow-duration="1.2s" data-wow-offset="0" />                              
                            </div>
                        </div>
                        
                        <div class="col-sm-8 col-lg-8 col-xl-8  mt-n10">
                            
                            <div class="wow linesAnimIn" data-splitting="lines">
                            <p class="text-gray mb-0">
                            At SGIPL, we stand at the intersection of tradition and technology, incorporating dynamic tech integration into our operations to deliver a cutting-edge gifting experience. We have built a trusted reputation in the industry by providing a diverse array of choices tailored to individual preferences, with a focus on precision and efficiency in order processing. Our 100% online delivery tracking ensures transparency and reliability, giving you peace of mind. With a Pan India presence, we are committed to exceeding your expectations and building trust with every interaction. Choose SGIPL as your trusted gifting partner for special occasions.</p>
                                <h3 class="h5 mt-30">Our Mission</h3>
                                
                                <p class="text-gray">
                                Our mission is to redefine and make easy the art of gifting and creating unforgettable moments. We are committed to enhancing the joy of giving and receiving, we envision a world where our gifts become cherished symbols of love, appreciation, and celebration.
                                </p>
                                
                             
                                
                              

                            </div>
                            
                        </div>
                        
                    <!--  <div class="col-lg-2 offset-xl-1 d-none d-lg-block">
                            <div class="overflow-hidden">
                                <img src="images/demo-strong/section-image-3.jpg" alt="Image description" class="wow scaleOutIn" data-wow-duration="1.2s" />
                            </div>
                        </div>-->
                        
                    </div>
                
                </div>
            </section>
            <!-- End About Section -->
            
            

               
                <!-- Team Section -->
                <!-- <section class="page-section pt-0 bg-gray-light-1" id="team">
                    <div class="container">
                        
                        <div class="row position-relative mt-n40 mb-80 mb-sm-40 wow fadeInUp" data-wow-delay="0.2s"> -->
                        <!-- <h2 class="section-title mt-30 mb-30 text-center">Our Team</h2> -->
                            <!-- Decorative Waves -->
                            <!-- <div class="decoration-8 d-none d-sm-block"  data-rellax-y data-rellax-speed="-0.6" data-rellax-percentage="-0.17">
                                <img src="images/decoration-1.svg" alt="" />
                            </div> -->
                            <!-- End Decorative Waves -->
                            
                            <!-- Team item -->
                            <!-- <div class="col-sm-6 col-lg-3 mt-40">                                
                                <div class="team-item">
                                    
                                    <div class="team-item-image">
                                        
                                        <img src="images/team/qaiser.jpg" alt="Image Description" />
                                        
                                      
                                        
                                    </div>
                                    
                                    <div class="team-item-descr">
                                        <div class="team-item-name">
                                        Qaiser Azad
                                        </div>
                                        <div class="team-item-role">
                                           Chairman & MD
                                        </div>
                                    </div>
                                    
                                </div>                      
                            </div> -->
                            <!-- End Team item -->
                            
                            <!-- Team item -->
                            <!-- <div class="col-sm-6 col-lg-3 mt-40">
                                <div class="team-item">
                                    
                                    <div class="team-item-image">
                                        
                                        <img src="images/team/rehan.jpg" alt="Image Description" />
                                     
                                        
                                    </div>
                                    
                                    <div class="team-item-descr">
                                        <div class="team-item-name">
                                        Rehan Azad
                                        </div>
                                        <div class="team-item-role">
                                        Director - Strategy and Brand Alliance  
                                        </div>
                                    </div>
                                    
                                </div>         
                            </div> -->
                            <!-- End Team item -->
                            
                            <!-- Team item -->
                            <!-- <div class="col-sm-6 col-lg-3 mt-40">                                
                                <div class="team-item">
                                    
                                    <div class="team-item-image">
                                        
                                        <img src="images/team/bhanu.jpg" alt="Image Description" />
                                        
                                       
                                    
                                        
                                    </div>
                                    
                                    <div class="team-item-descr">
                                        <div class="team-item-name">
                                            P Bhanu Chander
                                        </div>
                                        <div class="team-item-role">
                                           Director - Sales 
                                        </div>
                                    </div>
                                    
                                </div>                     
                            </div> -->
                            <!-- End Team item -->
                            
                            <!-- Team item -->
                            <!-- <div class="col-sm-6 col-lg-3 mt-40">                                
                                <div class="team-item">
                                    
                                    <div class="team-item-image">
                                        
                                        <img src="images/team/anil.jpg" alt="Image Description" />
                                        
                                       
                                        
                                    </div>
                                    
                                    <div class="team-item-descr">
                                        <div class="team-item-name">
                                        Anil Mathai
                                        </div>
                                        <div class="team-item-role">
                                          Director - Business Development
                                        </div>
                                    </div>
                                    
                                </div>
                            </div> -->
                            <!-- End Team item -->
                                         <!-- Team item -->
                                         <!-- <div class="col-sm-6 col-lg-3 mt-40">                                 -->
                                <!-- <div class="team-item">
                                    
                                    <div class="team-item-image">
                                        
                                        <img src="images/team/Tabrez.png" alt="Image Description" />
                                        
                                       
                                        
                                    </div>
                                    
                                    <div class="team-item-descr">
                                        <div class="team-item-name">
                                       Tabrez Khan
                                        </div>
                                        <div class="team-item-role">
                                         Virtual CFO
                                        </div>
                                    </div>
                                    
                                </div> -->
                            <!-- </div> -->
                            <!-- End Team item -->

                            <!--

      <div class="col-sm-6 col-lg-3 mt-40">                                
                                <div class="team-item">
                                    
                                    <div class="team-item-image">
                                        
                                        <img src="images/team/team-3.jpg" alt="Image Description" />
                                        
                                        <div class="team-item-detail">
                                            <div class="team-social-links">
                                                
                                                <a href="#" target="_blank" rel="noopener nofollow">
                                                    <div class="visually-hidden">
                                                        Facebook
                                                    </div>
                                                    <i class="fa-facebook-f"></i>
                                                </a>
                                                
                                                <a href="#" target="_blank" rel="noopener nofollow">
                                                    <div class="visually-hidden">
                                                        Twitter
                                                    </div>
                                                    <i class="fa-twitter"></i>
                                                </a>
                                                
                                                <a href="#" target="_blank" rel="noopener nofollow">
                                                    <div class="visually-hidden">
                                                        Pinterest
                                                    </div>
                                                    <i class="fa-pinterest-p"></i>
                                                </a>
                                                
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="team-item-descr">
                                        <div class="team-item-name">
                                     Irfan
                                        </div>
                                        <div class="team-item-role">
                                          [ Designation ] 
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
             

   
      <div class="col-sm-6 col-lg-3 mt-40">                                
                                <div class="team-item">
                                    
                                    <div class="team-item-image">
                                        
                                        <img src="images/team/team-3.jpg" alt="Image Description" />
                                        
                                        <div class="team-item-detail">
                                            <div class="team-social-links">
                                                
                                                <a href="#" target="_blank" rel="noopener nofollow">
                                                    <div class="visually-hidden">
                                                        Facebook
                                                    </div>
                                                    <i class="fa-facebook-f"></i>
                                                </a>
                                                
                                                <a href="#" target="_blank" rel="noopener nofollow">
                                                    <div class="visually-hidden">
                                                        Twitter
                                                    </div>
                                                    <i class="fa-twitter"></i>
                                                </a>
                                                
                                                <a href="#" target="_blank" rel="noopener nofollow">
                                                    <div class="visually-hidden">
                                                        Pinterest
                                                    </div>
                                                    <i class="fa-pinterest-p"></i>
                                                </a>
                                                
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="team-item-descr">
                                        <div class="team-item-name">
                                        Mustafa                                        </div>
                                        <div class="team-item-role">
                                          [ Designation ] 
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
            
 <div class="col-sm-6 col-lg-3 mt-40">                                
                                <div class="team-item">
                                    
                                    <div class="team-item-image">
                                        
                                        <img src="images/team/team-3.jpg" alt="Image Description" />
                                        
                                        <div class="team-item-detail">
                                            <div class="team-social-links">
                                                
                                                <a href="#" target="_blank" rel="noopener nofollow">
                                                    <div class="visually-hidden">
                                                        Facebook
                                                    </div>
                                                    <i class="fa-facebook-f"></i>
                                                </a>
                                                
                                                <a href="#" target="_blank" rel="noopener nofollow">
                                                    <div class="visually-hidden">
                                                        Twitter
                                                    </div>
                                                    <i class="fa-twitter"></i>
                                                </a>
                                                
                                                <a href="#" target="_blank" rel="noopener nofollow">
                                                    <div class="visually-hidden">
                                                        Pinterest
                                                    </div>
                                                    <i class="fa-pinterest-p"></i>
                                                </a>
                                                
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="team-item-descr">
                                        <div class="team-item-name">
                                        Saad                                        </div>
                                        <div class="team-item-role">
                                          [ Designation ] 
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
            
 <div class="col-sm-6 col-lg-3 mt-40">                                
                                <div class="team-item">
                                    
                                    <div class="team-item-image">
                                        
                                        <img src="images/team/team-3.jpg" alt="Image Description" />
                                        
                                        <div class="team-item-detail">
                                            <div class="team-social-links">
                                                
                                                <a href="#" target="_blank" rel="noopener nofollow">
                                                    <div class="visually-hidden">
                                                        Facebook
                                                    </div>
                                                    <i class="fa-facebook-f"></i>
                                                </a>
                                                
                                                <a href="#" target="_blank" rel="noopener nofollow">
                                                    <div class="visually-hidden">
                                                        Twitter
                                                    </div>
                                                    <i class="fa-twitter"></i>
                                                </a>
                                                
                                                <a href="#" target="_blank" rel="noopener nofollow">
                                                    <div class="visually-hidden">
                                                        Pinterest
                                                    </div>
                                                    <i class="fa-pinterest-p"></i>
                                                </a>
                                                
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="team-item-descr">
                                        <div class="team-item-name">
                                        Ahmed                                        </div>
                                        <div class="team-item-role">
                                          [ Designation ] 
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                           
                        -->











                        <!-- </div>
                     
                        
                    </div>
                </section> -->
                <!-- End Team Section -->

               
            
                
                <!-- Achievements Section -->
                <section class="page-section bg-dark-1 bg-dark-alpha-90 parallax-5 light-content" style="background-image: url(images/full-width-images/section-bg-2.jpg)">
                    <div class="container position-relative">
                        
                        <div class="row">
                            
                            <div class="col-lg-4 mb-md-60 mb-xs-50">
                                
                                <h2 class="section-title mb-20 wow fadeInUp">Check recent achievements.</h2>
                                
                                <p class="section-descr mb-40 wow fadeInUp" data-wow-delay="0.1s">
                                    We provide the effective ideas that grow productivity.
                                </p>
                                
                               
                                
                            </div>
                            
                            <div class="col-lg-7 offset-lg-1">
                                
                                <!-- Numbers Grid -->
                                <div class="row mt-n50 mt-xs-n30">
                                    
                                    <!-- Number Item -->
                                    <div class="col-sm-6 col-lg-5 mt-50 mt-xs-30 wow fadeScaleIn" data-wow-delay="0.4s">
                                        <div class="number-title mb-10 counter"  >
                                           <span class="Count">30</span>Yrs
                                        </div>
                                        <div class="number-descr">
                                       Over 30 years of industry expertise
                                        </div>
                                    </div>
                                    <!-- End Number Item -->
                                    
                                    <!-- Number Item -->
                                    <div class="col-sm-6 col-lg-5 offset-lg-2 mt-50 mt-xs-30 wow fadeScaleIn" data-wow-delay="0.6s">
                                        <div class="number-title mb-10">
                                        <span class="Count">1000</span>+
                                        </div>
                                        <div class="number-descr">
                                       Catering to over 1000+ gifting companies
                                        </div>
                                    </div>
                                    <!-- End Number Item -->
                                    
                                    <!-- Number Item -->
                                    <div class="col-sm-6 col-lg-5 mt-50 mt-xs-30 wow fadeScaleIn" data-wow-delay="0.8s">
                                        <div class="number-title mb-10">
                                        <span class="Count">100</span>%
                                        </div>
                                        <div class="number-descr">
                                        100% genuine products
                                        </div>
                                    </div>
                                    <!-- End Number Item -->
                                    
                                    <!-- Number Item -->
                                    <div class="col-sm-6 col-lg-5 offset-lg-2 mt-50 mt-xs-30 wow fadeScaleIn" data-wow-delay="1s">
                                        <div class="number-title mb-10">
                                        <span class="Count">110</span>+
                                        </div>
                                        <div class="number-descr">
                                        Serving more than 110+ clients
                                        </div>
                                    </div>
                                    <!-- End Number Item -->
                                
                                </div>
                                <!-- End Numbers Grid -->
                                
                            </div>
                            
                        </div>
                        
                    </div>
                </section>
                <!-- End Achievements Section -->
                
            
            
                <!-- Benefits Section -->
                <section class="page-section">
                    <div class="container position-relative">
                        
                        <!-- Grid -->
                        <div class="row">
                            
                            <!-- Text -->
                            <div class="col-md-12 col-lg-3 mb-md-50">
                                
                                <h2 class="section-caption mb-xs-10">Primary Benefits</h2>
                                    
                                <h3 class="section-title-small mb-40">Why choose Supergifts?</h3>
                                
                                <div class="section-line"></div>
                                
                            </div>
                            <!-- End Text -->
                            
                            <!-- Feature Item -->
                            <div class="col-md-4 col-lg-3 d-flex align-items-stretch mb-sm-30">
                                <div class="alt-features-item border-left mt-0">
                                    <div class="alt-features-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                                            <path d="M21.62 20.196c1.055-.922 1.737-2.262 1.737-3.772 0-1.321-.521-2.515-1.357-3.412v-6.946l-11.001-6.066-11 6v12.131l11 5.869 5.468-2.917c.578.231 1.205.367 1.865.367.903 0 1.739-.258 2.471-.676l2.394 3.226.803-.596-2.38-3.208zm-11.121 2.404l-9.5-5.069v-10.447l9.5 4.946v10.57zm1-.001v-10.567l5.067-2.608.029.015.021-.04 4.384-2.256v5.039c-.774-.488-1.686-.782-2.668-.782-2.773 0-5.024 2.252-5.024 5.024 0 1.686.838 3.171 2.113 4.083l-3.922 2.092zm6.833-2.149c-2.219 0-4.024-1.808-4.024-4.026s1.805-4.025 4.024-4.025c2.22 0 4.025 1.807 4.025 4.025 0 2.218-1.805 4.026-4.025 4.026zm-.364-3.333l-1.306-1.147-.66.751 2.029 1.782 2.966-3.12-.725-.689-2.304 2.423zm-16.371-10.85l4.349-2.372 9.534 4.964-4.479 2.305-9.404-4.897zm9.4-5.127l9.404 5.186-3.832 1.972-9.565-4.98 3.993-2.178z"/>
                                        </svg>
                                    </div>
                                    <h4 class="alt-features-title">Unique Products</h4>
                                    <div class="alt-features-descr">
                                        
                                    Find unique and customizable gifts that are sure to impress. We offer exclusive corporate gifts, brand-embossed items, and a variety of packages to choose from.

                                    </div>
                                </div>
                            </div>
                            <!-- End Feature Item -->
                            
                            <!-- Feature Item -->
                            <div class="col-md-4 col-lg-3 d-flex align-items-stretch mb-sm-30">
                                <div class="alt-features-item border-left mt-0">
                                    <div class="alt-features-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                                            <path d="M12 0c-3.371 2.866-5.484 3-9 3v11.535c0 4.603 3.203 5.804 9 9.465 5.797-3.661 9-4.862 9-9.465v-11.535c-3.516 0-5.629-.134-9-3zm0 1.292c2.942 2.31 5.12 2.655 8 2.701v10.542c0 3.891-2.638 4.943-8 8.284-5.375-3.35-8-4.414-8-8.284v-10.542c2.88-.046 5.058-.391 8-2.701zm5 7.739l-5.992 6.623-3.672-3.931.701-.683 3.008 3.184 5.227-5.878.728.685z"/>
                                        </svg>
                                    </div>
                                    <h4 class="alt-features-title">Quality Delivery</h4>
                                    <div class="alt-features-descr">
                                        
                                    We ensure superior quality in our wide range of services, from exclusive corporate gifts to customizable packages and brand-embossed items. 
                                    </div>
                                </div>
                            </div>
                            <!-- End Feature Item -->
                            
                            <!-- Feature Item -->
                            <div class="col-md-4 col-lg-3 d-flex align-items-stretch">
                                <div class="alt-features-item border-left mt-0">
                                    <div class="alt-features-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                                            <path d="M6.514 24.015h-3v-3.39c-2.08-.638-3.5-2.652-3.5-5.04 0-1.19.202-1.693 1.774-5.603.521-1.294 1.195-2.97 2.068-5.179.204-.518.67-.806 1.17-.802.482.004.941.284 1.146.802.718 1.817 1.302 3.274 1.777 4.454.26-.596.567-1.288.928-2.103.694-1.565 1.591-3.592 2.754-6.265.258-.592.881-.906 1.397-.888.572.015 1.126.329 1.369.888 1.163 2.673 2.06 4.7 2.754 6.265 2.094 4.727 2.363 5.334 2.363 6.764 0 2.927-2.078 5.422-5 6.082v4.015h-3v-4.015c-.943-.213-1.797-.617-2.523-1.165-.612.845-1.466 1.48-2.477 1.79v3.39zm14.493-6c1.652 0 2.993 1.341 2.993 2.993s-1.341 2.993-2.993 2.993-2.993-1.341-2.993-2.993 1.341-2.993 2.993-2.993zm.007.993c1.104 0 2 .896 2 2s-.896 2-2 2-2-.896-2-2 .896-2 2-2zm-7.5 3.993v-3.839c4.906-.786 5-4.751 5-5.244 0-1.218-.216-1.705-2.277-6.359-2.134-4.82-2.721-6.198-2.755-6.261-.079-.145-.193-.292-.455-.297-.238 0-.37.092-.481.297-.034.063-.621 1.441-2.755 6.261-2.061 4.654-2.277 5.141-2.277 6.359 0 .493.094 4.458 5 5.244v3.839h1zm-6.123-12.448l-.08-.198c-1.589-3.957-2.04-5.116-2.067-5.171-.072-.151-.15-.226-.226-.228-.109 0-.188.13-.235.228-.028.05-.316.818-2.066 5.171-1.542 3.833-1.703 4.233-1.703 5.23 0 1.988 1.076 3.728 3.5 4.25v3.166h1v-3.166c1.266-.273 2.159-.876 2.725-1.666-1.078-1.12-1.725-2.619-1.725-4.251 0-.979.126-1.572.877-3.365z"/>
                                        </svg>
                                    </div>
                                    <h4 class="alt-features-title">Transaprent Process</h4>
                                    <div class="alt-features-descr">
                                    Our transparent process ensures clarity in curating exclusive gifts and packages. With gift vouchers, employee gifting programs, and influencer initiatives, trust us for a transparent and accountable gifting journey.

                                    </div>
                                </div>
                            </div>
                            <!-- End Feature Item -->
                            
                       </div>
                       <!-- End Grid -->
                        
                    </div>
                </section>
                <!-- End Benefits Section -->
                
                
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
                                    Rich way to make clients, vendors and employees happy and cultivate business solutions.
                                    </p>
                                    <div class="local-scroll">
                                        <a href="main-pages-contact-1.html" class="btn btn-mod btn-large btn-round btn-hover-anim"><span>Contact us</span></a>
                                    </div>
                                </div>                             
                            </div>
                            
                        </div>
                    </div>
                </section>
                
        
          
                <!-- Call Action Section -->
                <section class="page-section">
                    <div class="container position-relative">
                        
                     
                        
                    </div>
                </section>
                <!-- End Call Action Section -->
       </main>
            
          <?php include('common/footer.php'); ?>
    </body>
</html>
