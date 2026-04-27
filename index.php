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

            <!-- Home Section -->
            <section class="home-section bg-gray-light-1 bg-light-alpha-90 parallax-5 parallax-mousemove-scene" style="background-image: url(images/full-width-images/section-bg-1.jpg)" id="home">
                <div class="container min-height-100vh d-flex align-items-center pt-100 pb-100 pt-sm-120 pb-sm-120">

                    <!-- Home Section Content -->
                    <div class="home-content text-start">
                        <div class="row">

                            <!-- Home Section Text -->
                            <div class="col-md-6 d-flex align-items-center mb-sm-60">
                                <div>

                                    <h2 class="section-caption mb-30 mb-xs-10 wow fadeInUp" data-wow-duration="1.2s">
                                        RESONATE EMOTIONS WITH GIFT
                                    </h2>

                                    <h1 class="hs-title-1 mb-30">
                                        <span class="wow charsAnimIn" data-splitting="chars">Elevate connections with thoughtful corporate gifts.</span>
                                    </h1>

                                    <p class="section-descr mb-50 wow fadeInUp" data-wow-delay="0.6s" data-wow-duration="1.2s">
                                        Unleash the potential of your Employees, Customers, and Dealers as influential Brand evangelists with our diverse range of products and services.
                                    </p>

                                    <!-- <div class="local-scroll mt-n10 wow fadeInUp wch-unset" data-wow-delay="0.7s" data-wow-duration="1.2s" data-wow-offset="0">
                                            <a href="#about" class="btn btn-mod btn-large btn-round btn-hover-anim align-middle me-2 me-sm-5 mt-10"><span>Discover now</span></a>
                                         
                                        </div>-->

                                    <p> <a href="#about" class="scroll-down-1">
                                            <div class="scroll-down-1-icon">
                                                <i class="mi-arrow-down"></i>
                                            </div>
                                            <div class="scroll-down-1-text">Scroll Down</div>
                                        </a> </p>
                                </div>
                            </div>
                            <!-- End Home Section Text -->

                            <!-- Stack Images -->
                            <div class="col-md-5 offset-md-1 d-flex align-items-center">
                                <div class="stack-images">

                                    <div class="stack-images-1 parallax-mousemove" data-offset="30">
                                        <div class="wow clipRightIn" data-wow-delay="1.2s" data-wow-duration="1.75s">
                                            <img src="images/stack-image-1.jpg" alt="Image Description" />
                                        </div>
                                    </div>

                                    <div class="stack-images-2 parallax-mousemove" data-offset="60">
                                        <div class="wow clipRightIn" data-wow-delay="1.7s" data-wow-duration="1.75s">
                                            <img src="images/stack-image-2.jpg" alt="Image Description" />
                                        </div>
                                    </div>

                                    <div class="stack-images-3 parallax-mousemove" data-offset="90">
                                        <div class="wow clipRightIn" data-wow-delay="2.2s" data-wow-duration="1.75s">
                                            <img src="images/stack-image-3.jpg" alt="Image Description" width="600" />
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- End Stack Images -->

                        </div>
                    </div>
                    <!-- End Home Section Content -->

                    <!-- Scroll Down 
                        <div class="local-scroll scroll-down-wrap-type-1 wow fadeInUp" data-wow-offset="0">
                            <div class="container">
                                <a href="#about" class="scroll-down-1">
                                    <div class="scroll-down-1-icon">
                                        <i class="mi-arrow-down"></i>
                                    </div>
                                    <div class="scroll-down-1-text">Scroll Down</div>
                                </a>
                            </div>
                        </div>-->
                    <!-- End Scroll Down -->

                </div>
            </section>
            <!-- End Home Section -->


            <!-- About Section -->
            <section class="page-section" id="about">
                <div class="container position-relative">

                    <div class="row mb-60 mb-xs-30">

                        <div class="col-md-6">
                            <h2 class="section-caption mb-xs-10">We are</h2>
                            <h3 class="section-title mb-0"><span class="wow charsAnimIn" data-splitting="chars">Working for you… always</span></h3>
                        </div>

                        <div class="col-md-5 offset-md-1 relative text-start text-md-end pt-40 pt-sm-20 local-scroll">

                            <!-- Decorative Dots -->
                            <div class="decoration-2 d-none d-md-block" data-rellax-y data-rellax-speed="0.7" data-rellax-percentage="-0.2">
                                <img src="images/decoration-2.svg" alt="" />
                            </div>
                            <!-- End Decorative Dots -->



                        </div>

                    </div>

                    <div class="row wow fadeInUp" data-wow-delay="0.5s">

                        <div class="col-lg-6 mb-md-60">
                            <div class="position-relative">

                                <!-- Image -->
                                <div class="position-relative overflow-hidden">
                                    <img src="images/about-image.jpg" class="image-fullwidth relative" alt="Image Description" />
                                </div>
                                <!-- End Image -->

                                <!-- Decorative Waves -->
                                <div class="decoration-1 d-none d-sm-block" data-rellax-y data-rellax-speed="1" data-rellax-percentage="0.1">
                                    <img src="images/decoration-1.svg" alt="" />
                                </div>
                                <!-- End Decorative Waves -->

                            </div>
                        </div>

                        <div class="col-lg-6 col-xl-5 offset-xl-1">



                            <p class="text-gray">
                                At SGIPL, we stand at the intersection of tradition and technology, incorporating dynamic tech integration into our operations to deliver a cutting-edge gifting experience. Explore our extensive online catalogue, offering customization options for both established and upcoming brands, providing a diverse array of choices tailored to individual preferences. </p>
                            <p class="text-gray">
                                Our commitment to precision shines through our customized order processing, facilitated by real-time inventory and order management tools, ensuring efficiency and accuracy. </p>


                            <a href="about" class="link-hover-anim underline align-middle" data-link-animate="y">Learn more about us <i class="mi-arrow-right size-18"></i></a>


                        </div>

                    </div>

                </div>
            </section>
            <!-- End About Section -->





            <!-- Call Action Section -->
            <section class="page-section" style="    background: #fbe6cb;" id="brandb2b">
                <div class="container position-relative">
                    <div class="row">

                        <div class="col-lg-12 align-items-center"> <!--<h2 class="section-title mb-50 mb-sm-20"><img src="images/b2bbrand_logo.png" style="width:350px"/></h2>-->
                            <p> Elevate your sourcing experience with our comprehensive services.</p>
                            <p>
                                BrandB2B acts as a multiple brand reseller, sourcing diverse high-quality products from renowned brands. Through strategic partnerships, it provides retailers with a one-stop solution for accessing top-tier brands, facilitating their growth and success.</p>

                        </div>
                        <!-- Text -->
                        <div class="col-lg-6 d-flex align-items-center">
                            <div class="wow fadeInUp" data-wow-duration="1.2s" data-wow-offset="255">



                                <div class="call-action-2-text mb-50 mb-sm-40">

                                    <!-- Accordion -->
                                    <dl class="accordion">
                                        <dt>
                                            <a href="#">01. Extensive Network</a>
                                        </dt>
                                        <dd class="text-gray">
                                            Gain access to a diverse array of products from over 140+ gifting companies and 100+ esteemed brands, ensuring a wide-ranging selection to meet your needs.
                                        </dd>
                                        <dt>
                                            <a href="#">02. Authenticity Assured</a>
                                        </dt>
                                        <dd class="text-gray">
                                            Enjoy the assurance of 100% genuine products, featuring sought-after offerings from both Indian and International brands, adding credibility to your sourcing choices.
                                        </dd>
                                        <dt>
                                            <a href="#">03. Cost-Effective Solutions</a>
                                        </dt>
                                        <dd class="text-gray">
                                            Benefit from the best price offerings on every order, optimizing your procurement process for maximum cost efficiency.
                                        </dd>
                                        <dt>
                                            <a href="#">04. Time and Cost Savings</a>
                                        </dt>
                                        <dd class="text-gray">
                                            Experience substantial savings in both time and money, as our streamlined processes and extensive network simplify your sourcing journey,both ensuring prompt response to all your queries.
                                        </dd>
                                        <dt>
                                            <a href="#">05. Nationwide Reach</a>
                                        </dt>
                                        <dd class="text-gray">
                                            With 100% order updates and a commitment to timely delivery at a PAN India level, you can trust us for seamless and reliable procurement services.
                                        </dd>
                                    </dl>
                                    <!-- End Accordion -->

                                </div>


                                <div class="row">
                                    <div class="col-lg-6 d-flex align-items-center">
                                        <div class="local-scroll">
                                            <a href="contact#contact" class="btn btn-mod btn-large btn-round btn-hover-anim"><span>Let's Start a Discussion</span></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 d-flex align-items-center">
                                        <a href="https://drive.google.com/drive/folders/1O4B_XOtkbFGHFJ3Q30_zq5g_eeNifPUB" target="_blank" class=" "><img src="images/brochure-download-icon.png" style="width:210px" /></a>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <!-- End Text -->
                        <!-- Images -->
                        <div class="col-lg-6 d-flex align-items-center mb-md-60 mb-xs-30">
                            <div class="call-action-2-images">
                                <img src="images/reseller_small.jpg" style="width:100%" />
                                <!--   <div class="call-action-2-image-1" data-rellax-y data-rellax-speed="0.5" data-rellax-percentage="0.7">
                                        <img src="images/promo-3.jpg" alt="Image Description" class="wow scaleOutIn" data-wow-duration="1.2s" data-wow-offset="255" />
                                    </div>
                                    
                                    <div class="call-action-2-image-2">
                                        <img src="images/promo-4.jpg" alt="Image Description" class="wow scaleOutIn" data-wow-duration="1.2s" data-wow-offset="134" />
                                    </div>
                                    
                                    <div class="call-action-2-image-3" data-rellax-y data-rellax-speed="-0.5" data-rellax-percentage="0.5">
                                        <img src="images/promo-5.jpg" alt="Image Description" class="wow scaleOutIn" data-wow-duration="1.2s" data-wow-offset="0" />
                                    </div> -->

                            </div>
                        </div>
                        <!-- End Images -->

                    </div>



                </div>
            </section>
            <!-- End Call Action Section -->

            <!-- Portfolio Section -->
            <!-- <section class="page-section" id="portfolio">
                <div class="container">

                    <h2 class="section-title mb-30 text-center">Trusted Brand Partners</h2>
                    <p class="mb-50 text-center"> click on logos to download respective catalog </p>
                    # Works Grid
                    <ul class="works-grid work-grid-6 work-grid-gut-lg masonry" id="work-grid">

                        <?php
                        $servername = "localhost";
                        $username = "superehc_aiir";
                        $password = "Aiir@8097000970";
                        $dbname = "superehc_sgipl";

                        # Create connection
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        # Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SELECT * FROM `brandlogo` WHERE flag <> 1 order by seqence ASC;";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            # output data of each row
                            while ($row = $result->fetch_assoc()) { ?>
                                <li class="work-item mix development">
                                    <div class="work-img">
                                        <div class="work-img-bg wow-p scalexIn"></div>
                                        <a href="<?php echo $row["links"]; ?>" target="_blank"><img class="aiir_brand_img" src="images/brandlogo/<?php echo "image" . $row["imageno"] . ".jpg"; ?>" alt="SGIPL" /></a>
                                    </div>
                                </li>
                        <?php  }
                        } else {
                            echo "0 results";
                        }
                        $conn->close();
                        ?>
                    </ul>
                    # End Works Grid
                </div>
            </section> -->

            <!-- New code for authorised and other brand partners sections, replacing the old trusted brand partners section. -->

            <!-- Authorised Brand Partners Section -->
            <!-- <section class="page-section" id="authorised-brands">
                <div class="container">
                    <h2 class="section-title mb-30 text-center">Authorised Brand Partners</h2>
                    <p class="mb-50 text-center">Click on logos to download respective catalog</p>
                    <ul class="works-grid work-grid-6 work-grid-gut-lg masonry" id="authorised-work-grid">
                        <?php
                        $servername = "localhost";
                        $username = "superehc_aiir";
                        $password = "Aiir@8097000970";
                        $dbname = "superehc_sgipl";
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        $sql = "SELECT * FROM `brandlogo` WHERE flag = 1 ORDER BY seqence ASC;";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) { ?>
                                <li class="work-item mix development">
                                    <div class="work-img">
                                        <div class="work-img-bg wow-p scalexIn"></div>
                                        <a href="<?php echo $row["links"]; ?>" target="_blank">
                                            <img class="aiir_brand_img" src="images/brandlogo/<?php echo "image" . $row["imageno"] . ".jpg"; ?>" alt="SGIPL" />
                                        </a>
                                    </div>
                                </li>
                        <?php }
                        } else {
                            echo "0 results";
                        }
                        $conn->close();
                        ?>
                    </ul>
                </div>
            </section> -->

            <!-- We Also Deal with This Brands Section -->
            <!-- <section class="page-section" id="other-brands">
                <div class="container">
                    <h2 class="section-title mb-30 text-center">We Also Deal with This Brands</h2>
                    <p class="mb-50 text-center">Click on logos to download respective catalog</p>
                    <ul class="works-grid work-grid-6 work-grid-gut-lg masonry" id="other-work-grid">
                        <?php
                        $servername = "localhost";
                        $username = "superehc_aiir";
                        $password = "Aiir@8097000970";
                        $dbname = "superehc_sgipl";
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        $sql = "SELECT * FROM `brandlogo` WHERE flag = 0 ORDER BY seqence ASC;";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) { ?>
                                <li class="work-item mix development">
                                    <div class="work-img">
                                        <div class="work-img-bg wow-p scalexIn"></div>
                                        <a href="<?php echo $row["links"]; ?>" target="_blank">
                                            <img class="aiir_brand_img" src="images/brandlogo/<?php echo "image" . $row["imageno"] . ".jpg"; ?>" alt="SGIPL" />
                                        </a>
                                    </div>
                                </li>
                        <?php }
                        } else {
                            echo "0 results";
                        }
                        $conn->close();
                        ?>
                    </ul>
                </div>
            </section> -->

            <!-- Authorised Brand Partners Section -->
            <section class="page-section" id="authorised-brands">
                <div class="container">
                    <h2 class="section-title mb-30 text-center">Authorised Brand Partners</h2>
                    <p class="mb-50 text-center">Click on logos to view products</p>
                    <ul class="works-grid work-grid-6 work-grid-gut-lg masonry" id="authorised-work-grid">
                        <?php
                        $conn_auth = new mysqli("localhost", "superehc_aiir", "Aiir@8097000970", "superehc_sgipl");
                        if ($conn_auth->connect_error) die("Connection failed: " . $conn_auth->connect_error);
                        $result_auth = $conn_auth->query("SELECT * FROM brandlogo WHERE flag = 1 ORDER BY seqence ASC");
                        if ($result_auth->num_rows > 0) {
                            while ($row = $result_auth->fetch_assoc()) { ?>
                                <li class="work-item mix development">
                                    <div class="work-img">
                                        <div class="work-img-bg wow-p scalexIn"></div>
                                        <a href="brand-products?brand=<?= $row['id'] ?>">
                                            <img class="aiir_brand_img"
                                                src="images/brandlogo/<?= 'image' . $row['imageno'] . '.jpg' ?>"
                                                alt="<?= htmlspecialchars($row['brandname']) ?>" />
                                        </a>
                                    </div>
                                </li>
                        <?php }
                        } else {
                            echo "0 results";
                        }
                        $conn_auth->close();
                        ?>
                    </ul>
                </div>
            </section>

            <!-- We Also Deal with This Brands Section -->
            <section class="page-section" id="other-brands">
                <div class="container">
                    <h2 class="section-title mb-30 text-center">We Also Deal with This Brands</h2>
                    <p class="mb-50 text-center">Click on logos to view products</p>
                    <ul class="works-grid work-grid-6 work-grid-gut-lg masonry" id="other-work-grid">
                        <?php
                        $conn_other = new mysqli("localhost", "superehc_aiir", "Aiir@8097000970", "superehc_sgipl");
                        if ($conn_other->connect_error) die("Connection failed: " . $conn_other->connect_error);
                        $result_other = $conn_other->query("SELECT * FROM brandlogo WHERE flag = 0 ORDER BY seqence ASC");
                        if ($result_other->num_rows > 0) {
                            while ($row = $result_other->fetch_assoc()) { ?>
                                <li class="work-item mix development">
                                    <div class="work-img">
                                        <div class="work-img-bg wow-p scalexIn"></div>
                                        <a href="brand-products?brand=<?= $row['id'] ?>">
                                            <img class="aiir_brand_img"
                                                src="images/brandlogo/<?= 'image' . $row['imageno'] . '.jpg' ?>"
                                                alt="<?= htmlspecialchars($row['brandname']) ?>" />
                                        </a>
                                    </div>
                                </li>
                        <?php }
                        } else {
                            echo "0 results";
                        }
                        $conn_other->close();
                        ?>
                    </ul>
                </div>
            </section>




            <!-- End Portfolio Section -->


            <!-- Logotypes Section
                <section class="small-section pt-20 pb-20">
                    <div class="container relative">
                        
                        <div class="row wow fadeInUpShort">
                            <div class="col-md-12">
                            <h2 class="section-title-tiny mb-30 text-center">Trusted Reseller Partners</h2>
                                <div class="small-item-carousel black owl-carousel mb-0">

                                <div class="logo-item">
                                        <img src="images/clients-logos/satya.png" width="150" height="90" alt="SGIPL" />
                                    </div>
                                    <div class="logo-item">
                                        <img src="images/clients-logos/steve-madden.png" width="150" height="90" alt="SGIPL" />
                                    </div>
                                    <div class="logo-item">
                                        <img src="images/clients-logos/martina.png" width="150" height="90" alt="SGIPL" />
                                    </div>
                                    <div class="logo-item">
                                        <img src="images/clients-logos/gas.png" width="150" height="90" alt="SGIPL" />
                                    </div>


                                    <?php for ($i = 1; $i < 20; $i++) {
                                        if ($i != 14) { ?>
                               

                                    <div class="logo-item">
                                        <img src="images/clients-logos/<?php echo $i . ".jpg"; ?>" width="150" height="90" alt="SGIPL" />
                                    </div>
                               
                                    <?php }
                                    } ?>
                                    
                                </div>
                                    
                             </div>
                         </div>
                        
                     </div>
                </section> -->
            <!-- End Logotypes -->




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
                                    <div class="number-title mb-10 counter">
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


            <!-- Services Section -->
            <section class="page-section" id="services">
                <div class="container position-relative">

                    <div class="row">

                        <div class="col-lg-6 mb-md-60 mb-sm-30">

                            <h2 class="section-caption mb-xs-10">Our Services</h2>
                            <h3 class="section-title mb-30"><span class="wow charsAnimIn" data-splitting="chars">We serve the best gifting solutions.</span></h3>

                            <div class="row">
                                <div class="col-lg-10">
                                    <p class="section-descr mb-50 mb-sm-30 wow fadeInUp" data-wow-delay="0.4s">
                                        The power of design help us to solve complex problems and cultivate business solutions.
                                    </p>
                                </div>
                            </div>

                            <ul class="nav nav-tabs services-tabs wow fadeInUp" data-wow-delay="0.55s" role="tablist">
                                <li role="presentation">
                                    <a href="#services-item-1" class="active" aria-controls="services-item-1" role="tab" aria-selected="true" data-bs-toggle="tab">Corporate Gifting <span class="number">01</span></a>
                                </li>
                                <li role="presentation">
                                    <a href="#services-item-2" aria-controls="services-item-2" role="tab" aria-selected="false" data-bs-toggle="tab">New Hire Joinee Kit <span class="number">02</span></a>
                                </li>
                                <li role="presentation">
                                    <a href="#services-item-3" aria-controls="services-item-3" role="tab" aria-selected="false" data-bs-toggle="tab">Official Brandkits <span class="number">03</span></a>
                                </li>
                                <li role="presentation">
                                    <a href="#services-item-4" aria-controls="services-item-4" role="tab" aria-selected="false" data-bs-toggle="tab">Gift Voucher <span class="number">04</span></a>
                                </li>
                                <li role="presentation">
                                    <a href="#services-item-5" aria-controls="services-item-5" role="tab" aria-selected="false" data-bs-toggle="tab"> Sales Incentives <span class="number">05</span></a>
                                </li>
                                <li role="presentation">
                                    <a href="#services-item-6" aria-controls="services-item-6" role="tab" aria-selected="false" data-bs-toggle="tab">Channel Partner Engagement <span class="number">06</span></a>
                                </li>
                            </ul>

                        </div>

                        <div class="col-lg-6 d-flex wow fadeInLeft" data-wow-delay="0.55s" data-wow-offset="275">

                            <div class="tab-content services-content">

                                <!-- Tab Content -->
                                <div class="tab-pane services-content-item show fade active" id="services-item-1" role="tabpanel">

                                    <div class="services-text">
                                        <div class="services-text-container">
                                            <h4 class="services-title">Corporate Gifting </h4>
                                            <p class="text-gray mb-0">
                                                Our customized approach to our clients needs ensures that they not just showcase their brand in each of their gifts but also have a packaging done to suit their expectations.
                                            </p>
                                        </div>
                                    </div>

                                    <img class="services-image" src="images/services/service-1.jpg" alt="Image Description" />

                                </div>
                                <!-- End Tab Content -->

                                <!-- Tab Content -->
                                <div class="tab-pane services-content-item fade" id="services-item-2" role="tabpanel">

                                    <div class="services-text">
                                        <div class="services-text-container">
                                            <h4 class="services-title">New Hire Joinee Kit </h4>
                                            <p class="text-gray mb-0">
                                                Tailored to welcome new team members, our kits showcase your brand while meeting individual expectations, blending excitement and professionalism seamlessly.
                                            </p>
                                        </div>
                                    </div>

                                    <img class="services-image" src="images/services/service-2.jpg" alt="Image Description" />

                                </div>
                                <!-- End Tab Content -->

                                <!-- Tab Content -->
                                <div class="tab-pane services-content-item fade" id="services-item-3" role="tabpanel">

                                    <div class="services-text">
                                        <div class="services-text-container">
                                            <h4 class="services-title">Official Brandkits</h4>
                                            <p class="text-gray mb-0">
                                                Crafted to embody your brand's essence, our Brandkits ensure a cohesive representation across all touchpoints, leaving a lasting impression on clients and stakeholders.
                                            </p>
                                        </div>
                                    </div>

                                    <img class="services-image" src="images/services/service-3.jpg" alt="Image Description" />

                                </div>
                                <!-- End Tab Content -->

                                <!-- Tab Content -->
                                <div class="tab-pane services-content-item fade" id="services-item-4" role="tabpanel">

                                    <div class="services-text">
                                        <div class="services-text-container">
                                            <h4 class="services-title">Gift Voucher</h4>
                                            <p class="text-gray mb-0">
                                                Elevate gifting with our personalized vouchers, reflecting your brand's sophistication and thoughtfulness for unforgettable recipient experiences.
                                            </p>
                                        </div>
                                    </div>

                                    <img class="services-image" src="images/services/service-4.jpg" alt="Image Description" />

                                </div>
                                <!-- End Tab Content -->

                                <!-- Tab Content -->
                                <div class="tab-pane services-content-item fade" id="services-item-5" role="tabpanel">

                                    <div class="services-text">
                                        <div class="services-text-container">
                                            <h4 class="services-title">Sales Incentives</h4>
                                            <p class="text-gray mb-0">
                                                Inspire sales excellence with our incentives program, offering rewards that align with your goals and drive team performance to new heights.
                                            </p>
                                        </div>
                                    </div>

                                    <img class="services-image" src="images/services/service-5.jpg" alt="Image Description" />

                                </div>
                                <!-- End Tab Content -->

                                <!-- Tab Content -->
                                <div class="tab-pane services-content-item fade" id="services-item-6" role="tabpanel">

                                    <div class="services-text">
                                        <div class="services-text-container">
                                            <h4 class="services-title">Channel Partner Engagement</h4>
                                            <p class="text-gray mb-0">
                                                Strengthen partnerships through tailored engagement strategies, fostering loyalty and collaboration for mutual success in the market.
                                            </p>
                                        </div>
                                    </div>

                                    <img class="services-image" src="images/services/service-6.jpg" alt="Image Description" />

                                </div>
                                <!-- End Tab Content -->

                            </div>

                        </div>

                    </div>

                </div>
            </section>
            <!-- End Services Section -->


            <!-- Divider -->
            <hr class="mt-0 mb-0" />
            <!-- End Divider -->




            <!-- Divider -->
            <hr class="mt-0 mb-0" />
            <!-- End Divider -->



            <!-- Logotypes Section
                <section class="page-section">
                    <div class="container position-relative">
                        
                        <div class="row">                            
                            <div class="col-md-8 offset-md-2 text-center">
                                
                                <h2 class="section-title-tiny mb-30">Trusted Partners</h2>
                                
                                <div class="logo-grid">                                    
                                    <img class="logo-grid-img" src="images/clients-logos/logo-grid/logo-1.png" width="110" height="33" alt="Image description is here" />
                                    <img class="logo-grid-img" src="images/clients-logos/logo-grid/logo-2.png" width="119" height="33" alt="Image description is here" />
                                    <img class="logo-grid-img" src="images/clients-logos/logo-grid/logo-3.png" width="79" height="33" alt="Image description is here" />
                                    <img class="logo-grid-img" src="images/clients-logos/logo-grid/logo-4.png" width="122" height="33" alt="Image description is here" />
                                    <img class="logo-grid-img" src="images/clients-logos/logo-grid/logo-5.png" width="100" height="33" alt="Image description is here" />
                                </div>
                                
                            </div>                            
                        </div>
                        
                    </div>
                </section>-->
            <!-- End Logotypes Section-->

            <!-- Features Slider Section -->
            <section class="page-section bg-dark bg-dark-alpha-70 light-content" style="background-image: url(images/full-width-images/section-bg-4.jpg)">
                <div class="container position-relative">

                    <div class="wow fadeInUp">
                        <div class="item-carousel owl-carousel">

                            <!-- Features Item -->
                            <div class="features-item">
                                <div class="features-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                                        <path d="M17.517 10.012c.151.23.087.541-.144.692l-2.2 1.444c-.085.056-.179.082-.274.082-.162 0-.322-.079-.418-.225-.152-.231-.087-.541.143-.692l2.201-1.445c.23-.151.541-.089.692.144m-6.242-2.595l1.111-2.385c.116-.252.414-.36.664-.243.25.117.36.413.242.664l-1.111 2.386c-.085.181-.265.288-.453.288l-.211-.047c-.25-.115-.359-.413-.242-.663m-2.624.613c1.377-2.652 1.484-5.104.318-7.286-.178-.333.066-.734.441-.734.177 0 .351.095.442.264 1.33 2.49 1.225 5.254-.314 8.217-.089.171-.263.269-.444.269-.078 0-.156-.018-.23-.056-.245-.127-.341-.429-.213-.674m15.349 5.526c0 .352-.351.588-.671.47-2.808-1.028-5.254-.821-7.271.611-.088.063-.189.093-.29.093-.155 0-.309-.073-.406-.21-.16-.224-.108-.537.117-.696 2.301-1.637 5.059-1.884 8.193-.737.203.074.328.266.328.469m-16.484-2.608l2.865 7.517-2.248.964-2.753-7.512.778-2.176 1.358 1.207zm3.785 7.124l-2.168-5.687 5.025 4.463-2.857 1.224zm-8.27.419l.989 2.699-2.307.989 1.318-3.688zm1.823-5.103l2.358 6.435-2.271.973-1.384-3.777 1.297-3.631zm-4.853 10.612l15.997-6.853-10.283-9.137-5.714 15.99zm20.46-15.629l.552-.694.281.841.831.309-.713.528-.038.886-.723-.516-.854.238.268-.846-.491-.739.887-.007zm-1.384.885l-.639 2.019 2.041-.568 1.724 1.23.089-2.115 1.704-1.258-1.985-.739-.672-2.008-1.315 1.658-2.118.017 1.171 1.764zm-2.167-4.194c.593-.044.924-.141 1.074-.315.176-.204.226-.647.165-1.433-.023-.276.183-.517.459-.539.277-.016.515.18.537.456.063.806.059 1.62-.402 2.156-.429.499-1.13.602-1.76.647-.702.052-.72.243-.774.878-.056.67-.152 1.744-1.84 1.933-1.017.115-1.433.33-1.377 1.956.008.275-.207.325-.484.325h-.016c-.269 0-.491-.022-.5-.291-.049-1.461.191-2.655 2.265-2.887.874-.099.9-.404.956-1.072.054-.635.145-1.7 1.697-1.814m5.264-3.048c.454 0 .823.37.823.824 0 .454-.369.823-.823.823-.454 0-.824-.369-.824-.823 0-.454.37-.824.824-.824m0 2.647c1.006 0 1.823-.817 1.823-1.823s-.817-1.823-1.823-1.823c-1.007 0-1.824.817-1.824 1.823s.817 1.823 1.824 1.823m-8.446-3.662c.552 0 1 .449 1 .999 0 .551-.448.999-1 .999s-1-.448-1-.999c0-.55.448-.999 1-.999m0 2.998c1.103 0 1.999-.896 1.999-1.999 0-1.103-.896-1.998-1.999-1.998-1.104 0-2 .895-2 1.998s.896 1.999 2 1.999" />
                                    </svg>
                                </div>
                                <div class="features-title">
                                    Simplicity
                                </div>
                                <div class="features-descr">
                                    Simplify your gifting journey with Special Gifting India Private Ltd (SGIPL), where simplicity meets sophistication. Our streamlined processes ensure an effortless and enjoyable experience, making gifting a straightforward and delightful affair.
                                </div>
                            </div>
                            <!-- End Features Item -->

                            <!-- Features Item -->
                            <div class="features-item">
                                <div class="features-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                                        <path d="M22 9.74l-2 1.02v7.24c-1.007 2.041-5.606 3-8.5 3-3.175 0-7.389-.994-8.5-3v-7.796l-3-1.896 12-5.308 11 6.231v8.769l1 3h-3l1-3v-8.26zm-18 1.095v6.873c.958 1.28 4.217 2.292 7.5 2.292 2.894 0 6.589-.959 7.5-2.269v-6.462l-7.923 4.039-7.077-4.473zm-1.881-2.371l9.011 5.694 9.759-4.974-8.944-5.066-9.826 4.346z" />
                                    </svg>
                                </div>
                                <div class="features-title">
                                    Accountability
                                </div>
                                <div class="features-descr">
                                    Accountability is at the heart of our service at Special Gifting India Private Ltd (SGIPL). With a dedicated focus on accountability, we ensure every aspect of your gifting experience is managed with precision and reliability. Trust us to deliver not just gifts but a promise of accountability in every thoughtful gesture.
                                </div>
                            </div>
                            <!-- End Features Item -->

                            <!-- Features Item -->
                            <div class="features-item">
                                <div class="features-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                                        <path d="M3.278 3.956c-.459 0-.883.211-1.103.552-.363.561-.218 1.315.352 1.832.383.346.888.546 1.385.546.581 0 1.093-.268 1.444-.755l3.208-4.373-.502-.366-2.928 3.966-.4-.557c-.39-.546-.908-.845-1.456-.845m.634 3.928c-.743 0-1.492-.293-2.056-.804-.578-.525-.883-1.211-.883-1.891 0-1.62 1.426-2.232 2.305-2.232.675 0 1.308.265 1.829.756l2.742-3.713 2.112 1.541-3.797 5.177c-.542.751-1.342 1.166-2.252 1.166m15.386-7.839l-1.2 2.215-2.476.455 1.735 1.825-.332 2.496 2.273-1.086 2.271 1.086-.331-2.496 1.735-1.825-2.476-.455-1.199-2.215zm0 2.098l.548 1.013 1.132.208-.793.834.152 1.142-1.039-.496-1.039.496.152-1.142-.794-.834 1.132-.208.549-1.013m-7.312 3.894c-2.48 0-4.494 2.014-4.494 4.494 0 2.482 2.014 4.494 4.494 4.494 2.481 0 4.495-2.012 4.495-4.494 0-2.48-2.014-4.494-4.495-4.494m0 .999c1.928 0 3.496 1.569 3.496 3.495 0 1.927-1.568 3.495-3.496 3.495-1.927 0-3.495-1.568-3.495-3.495 0-1.926 1.568-3.495 3.495-3.495m-4.983 15.965h9.974v-2.778c0-1.256.204-1.786.661-2.494l1.024-1.58c1.148-1.764 2.233-3.43 2.792-4.491.078-.148.03-.328-.112-.418-.168-.109-.403-.076-.536.07-.671.734-2.03 2.164-4.041 4.251l-.369.396c-.951 1.04-1.53 1.54-4.287 1.54h-.123c-2.859-.014-3.442-.515-4.391-1.554l-.356-.382c-1.999-2.074-3.359-3.504-4.042-4.251-.133-.146-.368-.177-.535-.07-.142.091-.189.271-.112.418.585 1.112 1.828 3.18 3.796 6.323.479.766.657 1.44.657 2.489v2.531zm10.973.999h-11.972v-3.53c0-.851-.132-1.363-.504-1.958-2.01-3.208-3.228-5.239-3.833-6.388-.321-.611-.126-1.352.455-1.725.565-.361 1.361-.258 1.812.236.668.731 2.059 2.195 4.024 4.233l.374.402c.786.86 1.111 1.216 3.659 1.228h.118c2.439 0 2.764-.355 3.55-1.215l.387-.415c2.005-2.08 3.358-3.504 4.024-4.232.452-.495 1.249-.598 1.811-.237.582.373.777 1.114.457 1.725-.582 1.101-1.677 2.786-2.839 4.57l-1.022 1.576c-.348.541-.501.889-.501 1.953v3.777z" />
                                    </svg>
                                </div>
                                <div class="features-title">
                                    High Loyalty
                                </div>
                                <div class="features-descr">
                                    Experience high loyalty with Special Gifting India Private Ltd (SGIPL), where our unwavering commitment to customer satisfaction fosters enduring relationships. Elevate your gifting experience with us, and discover the true meaning of loyalty in every thoughtful detail.
                                </div>
                            </div>
                            <!-- End Features Item -->

                            <!-- Features Item -->
                            <div class="features-item">
                                <div class="features-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                                        <path d="M17.517 10.012c.151.23.087.541-.144.692l-2.2 1.444c-.085.056-.179.082-.274.082-.162 0-.322-.079-.418-.225-.152-.231-.087-.541.143-.692l2.201-1.445c.23-.151.541-.089.692.144m-6.242-2.595l1.111-2.385c.116-.252.414-.36.664-.243.25.117.36.413.242.664l-1.111 2.386c-.085.181-.265.288-.453.288l-.211-.047c-.25-.115-.359-.413-.242-.663m-2.624.613c1.377-2.652 1.484-5.104.318-7.286-.178-.333.066-.734.441-.734.177 0 .351.095.442.264 1.33 2.49 1.225 5.254-.314 8.217-.089.171-.263.269-.444.269-.078 0-.156-.018-.23-.056-.245-.127-.341-.429-.213-.674m15.349 5.526c0 .352-.351.588-.671.47-2.808-1.028-5.254-.821-7.271.611-.088.063-.189.093-.29.093-.155 0-.309-.073-.406-.21-.16-.224-.108-.537.117-.696 2.301-1.637 5.059-1.884 8.193-.737.203.074.328.266.328.469m-16.484-2.608l2.865 7.517-2.248.964-2.753-7.512.778-2.176 1.358 1.207zm3.785 7.124l-2.168-5.687 5.025 4.463-2.857 1.224zm-8.27.419l.989 2.699-2.307.989 1.318-3.688zm1.823-5.103l2.358 6.435-2.271.973-1.384-3.777 1.297-3.631zm-4.853 10.612l15.997-6.853-10.283-9.137-5.714 15.99zm20.46-15.629l.552-.694.281.841.831.309-.713.528-.038.886-.723-.516-.854.238.268-.846-.491-.739.887-.007zm-1.384.885l-.639 2.019 2.041-.568 1.724 1.23.089-2.115 1.704-1.258-1.985-.739-.672-2.008-1.315 1.658-2.118.017 1.171 1.764zm-2.167-4.194c.593-.044.924-.141 1.074-.315.176-.204.226-.647.165-1.433-.023-.276.183-.517.459-.539.277-.016.515.18.537.456.063.806.059 1.62-.402 2.156-.429.499-1.13.602-1.76.647-.702.052-.72.243-.774.878-.056.67-.152 1.744-1.84 1.933-1.017.115-1.433.33-1.377 1.956.008.275-.207.325-.484.325h-.016c-.269 0-.491-.022-.5-.291-.049-1.461.191-2.655 2.265-2.887.874-.099.9-.404.956-1.072.054-.635.145-1.7 1.697-1.814m5.264-3.048c.454 0 .823.37.823.824 0 .454-.369.823-.823.823-.454 0-.824-.369-.824-.823 0-.454.37-.824.824-.824m0 2.647c1.006 0 1.823-.817 1.823-1.823s-.817-1.823-1.823-1.823c-1.007 0-1.824.817-1.824 1.823s.817 1.823 1.824 1.823m-8.446-3.662c.552 0 1 .449 1 .999 0 .551-.448.999-1 .999s-1-.448-1-.999c0-.55.448-.999 1-.999m0 2.998c1.103 0 1.999-.896 1.999-1.999 0-1.103-.896-1.998-1.999-1.998-1.104 0-2 .895-2 1.998s.896 1.999 2 1.999" />
                                    </svg>
                                </div>
                                <div class="features-title">
                                    Simplicity
                                </div>
                                <div class="features-descr">
                                    Simplify your gifting journey with Special Gifting India Private Ltd (SGIPL), where simplicity meets sophistication. Our streamlined processes ensure an effortless and enjoyable experience, making gifting a straightforward and delightful affair.
                                </div>
                            </div>
                            <!-- End Features Item -->

                            <!-- Features Item -->
                            <div class="features-item">
                                <div class="features-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                                        <path d="M22 9.74l-2 1.02v7.24c-1.007 2.041-5.606 3-8.5 3-3.175 0-7.389-.994-8.5-3v-7.796l-3-1.896 12-5.308 11 6.231v8.769l1 3h-3l1-3v-8.26zm-18 1.095v6.873c.958 1.28 4.217 2.292 7.5 2.292 2.894 0 6.589-.959 7.5-2.269v-6.462l-7.923 4.039-7.077-4.473zm-1.881-2.371l9.011 5.694 9.759-4.974-8.944-5.066-9.826 4.346z" />
                                    </svg>
                                </div>
                                <div class="features-title">
                                    Accountability
                                </div>
                                <div class="features-descr">
                                    Accountability is at the heart of our service at Special Gifting India Private Ltd (SGIPL). With a dedicated focus on accountability, we ensure every aspect of your gifting experience is managed with precision and reliability. Trust us to deliver not just gifts but a promise of accountability in every thoughtful gesture.
                                </div>
                            </div>
                            <!-- End Features Item -->

                            <!-- Features Item -->
                            <div class="features-item">
                                <div class="features-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                                        <path d="M3.278 3.956c-.459 0-.883.211-1.103.552-.363.561-.218 1.315.352 1.832.383.346.888.546 1.385.546.581 0 1.093-.268 1.444-.755l3.208-4.373-.502-.366-2.928 3.966-.4-.557c-.39-.546-.908-.845-1.456-.845m.634 3.928c-.743 0-1.492-.293-2.056-.804-.578-.525-.883-1.211-.883-1.891 0-1.62 1.426-2.232 2.305-2.232.675 0 1.308.265 1.829.756l2.742-3.713 2.112 1.541-3.797 5.177c-.542.751-1.342 1.166-2.252 1.166m15.386-7.839l-1.2 2.215-2.476.455 1.735 1.825-.332 2.496 2.273-1.086 2.271 1.086-.331-2.496 1.735-1.825-2.476-.455-1.199-2.215zm0 2.098l.548 1.013 1.132.208-.793.834.152 1.142-1.039-.496-1.039.496.152-1.142-.794-.834 1.132-.208.549-1.013m-7.312 3.894c-2.48 0-4.494 2.014-4.494 4.494 0 2.482 2.014 4.494 4.494 4.494 2.481 0 4.495-2.012 4.495-4.494 0-2.48-2.014-4.494-4.495-4.494m0 .999c1.928 0 3.496 1.569 3.496 3.495 0 1.927-1.568 3.495-3.496 3.495-1.927 0-3.495-1.568-3.495-3.495 0-1.926 1.568-3.495 3.495-3.495m-4.983 15.965h9.974v-2.778c0-1.256.204-1.786.661-2.494l1.024-1.58c1.148-1.764 2.233-3.43 2.792-4.491.078-.148.03-.328-.112-.418-.168-.109-.403-.076-.536.07-.671.734-2.03 2.164-4.041 4.251l-.369.396c-.951 1.04-1.53 1.54-4.287 1.54h-.123c-2.859-.014-3.442-.515-4.391-1.554l-.356-.382c-1.999-2.074-3.359-3.504-4.042-4.251-.133-.146-.368-.177-.535-.07-.142.091-.189.271-.112.418.585 1.112 1.828 3.18 3.796 6.323.479.766.657 1.44.657 2.489v2.531zm10.973.999h-11.972v-3.53c0-.851-.132-1.363-.504-1.958-2.01-3.208-3.228-5.239-3.833-6.388-.321-.611-.126-1.352.455-1.725.565-.361 1.361-.258 1.812.236.668.731 2.059 2.195 4.024 4.233l.374.402c.786.86 1.111 1.216 3.659 1.228h.118c2.439 0 2.764-.355 3.55-1.215l.387-.415c2.005-2.08 3.358-3.504 4.024-4.232.452-.495 1.249-.598 1.811-.237.582.373.777 1.114.457 1.725-.582 1.101-1.677 2.786-2.839 4.57l-1.022 1.576c-.348.541-.501.889-.501 1.953v3.777z" />
                                    </svg>
                                </div>
                                <div class="features-title">
                                    High Loyalty
                                </div>
                                <div class="features-descr">
                                    Experience high loyalty with Special Gifting India Private Ltd (SGIPL), where our unwavering commitment to customer satisfaction fosters enduring relationships. Elevate your gifting experience with us, and discover the true meaning of loyalty in every thoughtful detail.
                                </div>
                            </div>
                            <!-- End Features Item -->

                            <!-- Features Item -->
                            <div class="features-item">
                                <div class="features-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                                        <path d="M17.517 10.012c.151.23.087.541-.144.692l-2.2 1.444c-.085.056-.179.082-.274.082-.162 0-.322-.079-.418-.225-.152-.231-.087-.541.143-.692l2.201-1.445c.23-.151.541-.089.692.144m-6.242-2.595l1.111-2.385c.116-.252.414-.36.664-.243.25.117.36.413.242.664l-1.111 2.386c-.085.181-.265.288-.453.288l-.211-.047c-.25-.115-.359-.413-.242-.663m-2.624.613c1.377-2.652 1.484-5.104.318-7.286-.178-.333.066-.734.441-.734.177 0 .351.095.442.264 1.33 2.49 1.225 5.254-.314 8.217-.089.171-.263.269-.444.269-.078 0-.156-.018-.23-.056-.245-.127-.341-.429-.213-.674m15.349 5.526c0 .352-.351.588-.671.47-2.808-1.028-5.254-.821-7.271.611-.088.063-.189.093-.29.093-.155 0-.309-.073-.406-.21-.16-.224-.108-.537.117-.696 2.301-1.637 5.059-1.884 8.193-.737.203.074.328.266.328.469m-16.484-2.608l2.865 7.517-2.248.964-2.753-7.512.778-2.176 1.358 1.207zm3.785 7.124l-2.168-5.687 5.025 4.463-2.857 1.224zm-8.27.419l.989 2.699-2.307.989 1.318-3.688zm1.823-5.103l2.358 6.435-2.271.973-1.384-3.777 1.297-3.631zm-4.853 10.612l15.997-6.853-10.283-9.137-5.714 15.99zm20.46-15.629l.552-.694.281.841.831.309-.713.528-.038.886-.723-.516-.854.238.268-.846-.491-.739.887-.007zm-1.384.885l-.639 2.019 2.041-.568 1.724 1.23.089-2.115 1.704-1.258-1.985-.739-.672-2.008-1.315 1.658-2.118.017 1.171 1.764zm-2.167-4.194c.593-.044.924-.141 1.074-.315.176-.204.226-.647.165-1.433-.023-.276.183-.517.459-.539.277-.016.515.18.537.456.063.806.059 1.62-.402 2.156-.429.499-1.13.602-1.76.647-.702.052-.72.243-.774.878-.056.67-.152 1.744-1.84 1.933-1.017.115-1.433.33-1.377 1.956.008.275-.207.325-.484.325h-.016c-.269 0-.491-.022-.5-.291-.049-1.461.191-2.655 2.265-2.887.874-.099.9-.404.956-1.072.054-.635.145-1.7 1.697-1.814m5.264-3.048c.454 0 .823.37.823.824 0 .454-.369.823-.823.823-.454 0-.824-.369-.824-.823 0-.454.37-.824.824-.824m0 2.647c1.006 0 1.823-.817 1.823-1.823s-.817-1.823-1.823-1.823c-1.007 0-1.824.817-1.824 1.823s.817 1.823 1.824 1.823m-8.446-3.662c.552 0 1 .449 1 .999 0 .551-.448.999-1 .999s-1-.448-1-.999c0-.55.448-.999 1-.999m0 2.998c1.103 0 1.999-.896 1.999-1.999 0-1.103-.896-1.998-1.999-1.998-1.104 0-2 .895-2 1.998s.896 1.999 2 1.999" />
                                    </svg>
                                </div>
                                <div class="features-title">
                                    Simplicity
                                </div>
                                <div class="features-descr">
                                    Simplify your gifting journey with Special Gifting India Private Ltd (SGIPL), where simplicity meets sophistication. Our streamlined processes ensure an effortless and enjoyable experience, making gifting a straightforward and delightful affair.
                                </div>
                            </div>
                            <!-- End Features Item -->

                            <!-- Features Item -->
                            <div class="features-item">
                                <div class="features-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                                        <path d="M22 9.74l-2 1.02v7.24c-1.007 2.041-5.606 3-8.5 3-3.175 0-7.389-.994-8.5-3v-7.796l-3-1.896 12-5.308 11 6.231v8.769l1 3h-3l1-3v-8.26zm-18 1.095v6.873c.958 1.28 4.217 2.292 7.5 2.292 2.894 0 6.589-.959 7.5-2.269v-6.462l-7.923 4.039-7.077-4.473zm-1.881-2.371l9.011 5.694 9.759-4.974-8.944-5.066-9.826 4.346z" />
                                    </svg>
                                </div>
                                <div class="features-title">
                                    Accountability
                                </div>
                                <div class="features-descr">
                                    Accountability is at the heart of our service at Special Gifting India Private Ltd (SGIPL). With a dedicated focus on accountability, we ensure every aspect of your gifting experience is managed with precision and reliability. Trust us to deliver not just gifts but a promise of accountability in every thoughtful gesture.
                                </div>
                            </div>
                            <!-- End Features Item -->

                            <!-- Features Item -->
                            <div class="features-item">
                                <div class="features-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                                        <path d="M3.278 3.956c-.459 0-.883.211-1.103.552-.363.561-.218 1.315.352 1.832.383.346.888.546 1.385.546.581 0 1.093-.268 1.444-.755l3.208-4.373-.502-.366-2.928 3.966-.4-.557c-.39-.546-.908-.845-1.456-.845m.634 3.928c-.743 0-1.492-.293-2.056-.804-.578-.525-.883-1.211-.883-1.891 0-1.62 1.426-2.232 2.305-2.232.675 0 1.308.265 1.829.756l2.742-3.713 2.112 1.541-3.797 5.177c-.542.751-1.342 1.166-2.252 1.166m15.386-7.839l-1.2 2.215-2.476.455 1.735 1.825-.332 2.496 2.273-1.086 2.271 1.086-.331-2.496 1.735-1.825-2.476-.455-1.199-2.215zm0 2.098l.548 1.013 1.132.208-.793.834.152 1.142-1.039-.496-1.039.496.152-1.142-.794-.834 1.132-.208.549-1.013m-7.312 3.894c-2.48 0-4.494 2.014-4.494 4.494 0 2.482 2.014 4.494 4.494 4.494 2.481 0 4.495-2.012 4.495-4.494 0-2.48-2.014-4.494-4.495-4.494m0 .999c1.928 0 3.496 1.569 3.496 3.495 0 1.927-1.568 3.495-3.496 3.495-1.927 0-3.495-1.568-3.495-3.495 0-1.926 1.568-3.495 3.495-3.495m-4.983 15.965h9.974v-2.778c0-1.256.204-1.786.661-2.494l1.024-1.58c1.148-1.764 2.233-3.43 2.792-4.491.078-.148.03-.328-.112-.418-.168-.109-.403-.076-.536.07-.671.734-2.03 2.164-4.041 4.251l-.369.396c-.951 1.04-1.53 1.54-4.287 1.54h-.123c-2.859-.014-3.442-.515-4.391-1.554l-.356-.382c-1.999-2.074-3.359-3.504-4.042-4.251-.133-.146-.368-.177-.535-.07-.142.091-.189.271-.112.418.585 1.112 1.828 3.18 3.796 6.323.479.766.657 1.44.657 2.489v2.531zm10.973.999h-11.972v-3.53c0-.851-.132-1.363-.504-1.958-2.01-3.208-3.228-5.239-3.833-6.388-.321-.611-.126-1.352.455-1.725.565-.361 1.361-.258 1.812.236.668.731 2.059 2.195 4.024 4.233l.374.402c.786.86 1.111 1.216 3.659 1.228h.118c2.439 0 2.764-.355 3.55-1.215l.387-.415c2.005-2.08 3.358-3.504 4.024-4.232.452-.495 1.249-.598 1.811-.237.582.373.777 1.114.457 1.725-.582 1.101-1.677 2.786-2.839 4.57l-1.022 1.576c-.348.541-.501.889-.501 1.953v3.777z" />
                                    </svg>
                                </div>
                                <div class="features-title">
                                    High Loyalty
                                </div>
                                <div class="features-descr">
                                    Experience high loyalty with Special Gifting India Private Ltd (SGIPL), where our unwavering commitment to customer satisfaction fosters enduring relationships. Elevate your gifting experience with us, and discover the true meaning of loyalty in every thoughtful detail.
                                </div>
                            </div>
                            <!-- End Features Item -->

                        </div>
                    </div>

                </div>
            </section>
            <!-- End Features Slider Section -->


            <!-- Divider -->
            <hr class="mt-0 mb-0" />
            <!-- End Divider -->


            <!-- Promo Section -->

            <!-- End Promo Section -->

            <!-- Divider -->
            <hr class="mt-0 mb-0" />
            <!-- End Divider -->




        </main>

        <?php include('common/footer.php'); ?>



</body>

</html>