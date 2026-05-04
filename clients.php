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
         <!-- Header Section -->
         <section class="page-section pb-100 pb-sm-60 bg-gray-light-1 bg-light-alpha-90 bg-scroll" style="background-image: url(images/full-width-images/section-bg-1.jpg)">
                    <div class="position-absolute top-0 bottom-0 start-0 end-0 bg-gradient-white"></div>
                    <div class="container position-relative pt-50">
                            
                        <!-- Section Content -->
                        <div class="text-center">
                            <div class="row">
                                
                                <!-- Page Title -->
                                <div class="col-md-8 offset-md-2">
                                    
                                    <h2 class="section-caption-border mb-30 mb-xs-20 wow fadeInUp" data-wow-duration="1.2s">
                                        Our Clients
                                    </h2>
                                        
                                    <h1 class="hs-title-1 mb-0">
                                        <span class="wow charsAnimIn" data-splitting="chars">Our Core Principles: We place paramount importance on quality, trust, and teamwork as the fundamental cornerstones of our methodology.</span>
                                    </h1>
                               
                               </div>
                               <!-- End Page Title -->
                                
                            </div>                            
                        </div>
                        <!-- End Section Content -->
                        
                    </div>
                </section>
                <!-- End Header Section -->
                
                
                <!-- Section -->
                <section class="page-section pt-0">
                    <div class="container relative">
                        
                        <!-- Logo Grid -->
                        <div class="row alt-features-grid mt-n50">
                            
                            <!-- Logo Item -->
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="alt-features-item mt-50 wow fadeIn" data-wow-delay="0" data-wow-duration="1.5s">
                                    <div class="mb-20">
                                        <img src="images/clients-logos/with-bg/client-1.jpg" alt="Add Image Description" />
                                    </div>
                                    
                                </div>
                            </div>
                            <!-- End Logo Item -->
                            
                            <!-- Logo Item -->
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="alt-features-item mt-50 wow fadeIn" data-wow-delay=".1s" data-wow-duration="1.5s">
                                    <div class="mb-20">
                                        <img src="images/clients-logos/with-bg/client-2.jpg" alt="Add Image Description" />
                                    </div>
                                   
                                </div>
                            </div>
                            <!-- End Logo Item -->
                            
                            <!-- Logo Item -->
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="alt-features-item mt-50 wow fadeIn" data-wow-delay=".2" data-wow-duration="1.5s">
                                    <div class="mb-20">
                                        <img src="images/clients-logos/with-bg/client-3.jpg" alt="Add Image Description" />
                                    </div>
                                  
                                </div>
                            </div>
                            <!-- End Logo Item -->
                            
                            <!-- Logo Item -->
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="alt-features-item mt-50 wow fadeIn" data-wow-delay="0" data-wow-duration="1.5s">
                                    <div class="mb-20">
                                        <img src="images/clients-logos/with-bg/client-4.jpg" alt="Add Image Description" />
                                    </div>
                                   
                                </div>
                            </div>
                            <!-- End Logo Item -->
                            
                            <!-- Logo Item -->
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="alt-features-item mt-50 wow fadeIn" data-wow-delay=".1s" data-wow-duration="1.5s">
                                    <div class="mb-20">
                                        <img src="images/clients-logos/with-bg/client-5.jpg" alt="Add Image Description" />
                                    </div>
                                   
                                </div>
                            </div>
                            <!-- End Logo Item -->
                            
                            <!-- Logo Item -->
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="alt-features-item mt-50 wow fadeIn" data-wow-delay=".2s" data-wow-duration="1.5s">
                                    <div class="mb-20">
                                        <img src="images/clients-logos/with-bg/client-6.jpg" alt="Add Image Description" />
                                    </div>
                                   
                                </div>
                            </div>
                            <!-- End Logo Item -->
                                                        
                            <!-- Logo Item -->
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="alt-features-item mt-50 wow fadeIn" data-wow-delay=".2s" data-wow-duration="1.5s">
                                    <div class="mb-20">
                                        <img src="images/clients-logos/with-bg/client-7.jpg" alt="Add Image Description" />
                                    </div>
                                   
                                </div>
                            </div>
                            <!-- End Logo Item -->

                                 <!-- Logo Item -->
                                 <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="alt-features-item mt-50 wow fadeIn" data-wow-delay=".2s" data-wow-duration="1.5s">
                                    <div class="mb-20">
                                        <img src="images/clients-logos/with-bg/client-8.jpg" alt="Add Image Description" />
                                    </div>
                                   
                                </div>
                            </div>
                            <!-- End Logo Item -->

                                 <!-- Logo Item -->
                                 <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="alt-features-item mt-50 wow fadeIn" data-wow-delay=".2s" data-wow-duration="1.5s">
                                    <div class="mb-20">
                                        <img src="images/clients-logos/with-bg/client-9.jpg" alt="Add Image Description" />
                                    </div>
                                   
                                </div>
                            </div>
                            <!-- End Logo Item -->

                                 <!-- Logo Item -->
                                 <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="alt-features-item mt-50 wow fadeIn" data-wow-delay=".2s" data-wow-duration="1.5s">
                                    <div class="mb-20">
                                        <img src="images/clients-logos/with-bg/client-10.jpg" alt="Add Image Description" />
                                    </div>
                                   
                                </div>
                            </div>
                            <!-- End Logo Item -->


                        </div>
                        <!-- End Logo Grid -->
                        
                    </div>
                </section>
                <!-- End Section -->  
         <!-- Testimonials Section -->
         <section class="page-section pt-0 pb-0">
                    <div class="container position-relative">
                        
                        <div class="pt-80 pb-80 pt-sm-70 pb-sm-70 px-4 bg-gray-light-1" style="    background-color: #fadf01;">
                            <div class="row">
                                <div class="col-lg-8 offset-lg-2 wow fadeInUp">
                                    
                                    <div class="row">
                                        <div class="col-md-10 offset-md-1 text-center">                                            
                                            <h2 class="section-title mb-70 mb-sm-40">SGIPL is trusted by <span class="mark-decoration-1">1,000+</span> customers.</h2>
                                        </div>                                        
                                    </div>
                                    
                                    <div class="testimonials-slider-1 pb-xs-80">
                                        
                                        <!-- Slide Item -->
                                        <div>
                                            <blockquote class="mb-0">
                                                
                                                <div class="blockquote-icon" aria-hidden="true">”</div>
                                                
                                                <p>
                                                My name is Dr Reshma Jhaveri and I was in charge of selecting an item for the delegates at Bombay Ophthalmologists’ Association Focus 2024
                                                </p>
                                                <p>
I have worked with Rehan and his company earlier as well, and this time too when I got this opportunity the first person who came to my mind was Rehan. 
</p>
                                                <p>
As always he suggested this laptop bag from Nautica with the most amazing colours that I totally fell in love with and when I showed it to my managing committee there was a positive consensus immediately.
</p>
                                                <p>
All the members at the conference too really appreciated the bags and gave a very good feedback.
</p>
                                                <p>
Rehan helped in transporting all the bags from warehouse to the conference lova and did it with ease !
</p>
                                                <p>
Would like to complement the entire team for great service and product

                                                </p>
                                                
                                                <div class="section-line mt-40"></div>
                                                
                                                <footer class="ts1-author mt-20 clearfix">
                                                    <div class="ts1-author-img float-start">
                                                        <img class="rounded-circle" width="44" height="44" src="images/ts1-user.jpg" alt="Image description is here" />
                                                    </div>                                                    
                                                    <div>
                                                    Dr Reshma Jhaveri
                                                        <div class="small">
                                                      Mumbai
                                                        </div>
                                                    </div>                                                    
                                                </footer>
                                                
                                            </blockquote>
                                        </div>
                                        <!-- End Slide Item -->
                                        
                                        <!-- Slide Item -->
                                        <div>
                                            <blockquote class="mb-0">
                                                
                                                <div class="blockquote-icon" aria-hidden="true">”</div>
                                                
                                                <p>
                                                My name is Dr Reshma Jhaveri and I was in charge of selecting an item for the delegates at Bombay Ophthalmologists’ Association Focus 2024
                                                </p>
                                                <p>
I have worked with Rehan and his company earlier as well, and this time too when I got this opportunity the first person who came to my mind was Rehan. 
</p>
                                                <p>
As always he suggested this laptop bag from Nautica with the most amazing colours that I totally fell in love with and when I showed it to my managing committee there was a positive consensus immediately.
</p>
                                                <p>
All the members at the conference too really appreciated the bags and gave a very good feedback.
</p>
                                                <p>
Rehan helped in transporting all the bags from warehouse to the conference lova and did it with ease !
</p>
                                                <p>
Would like to complement the entire team for great service and product

                                                </p>
                                                
                                                
                                                <div class="section-line mt-40"></div>
                                                
                                                <footer class="ts1-author mt-20 clearfix">                                                    
                                                    <div class="ts1-author-img float-start">
                                                        <img class="rounded-circle" width="44" height="44" src="images/ts1-user.jpg" alt="Image description is here" />
                                                    </div>                                                    
                                                    <div>
                                                    Dr Reshma Jhaveri
                                                        <div class="small">
                                                      Mumbai
                                                        </div>
                                                    </div>                                                    
                                                </footer>
                                                
                                            </blockquote>
                                        </div>
                                        <!-- End Slide Item -->
                                        
                                        <!-- Slide Item -->
                                        <div>
                                            <blockquote class="mb-0">
                                                
                                                <div class="blockquote-icon" aria-hidden="true">”</div>
                                                
                                                <p>
                                                My name is Dr Reshma Jhaveri and I was in charge of selecting an item for the delegates at Bombay Ophthalmologists’ Association Focus 2024
                                                </p>
                                                <p>
I have worked with Rehan and his company earlier as well, and this time too when I got this opportunity the first person who came to my mind was Rehan. 
</p>
                                                <p>
As always he suggested this laptop bag from Nautica with the most amazing colours that I totally fell in love with and when I showed it to my managing committee there was a positive consensus immediately.
</p>
                                                <p>
All the members at the conference too really appreciated the bags and gave a very good feedback.
</p>
                                                <p>
Rehan helped in transporting all the bags from warehouse to the conference lova and did it with ease !
</p>
                                                <p>
Would like to complement the entire team for great service and product

                                                </p>
                                                
                                                
                                                <div class="section-line mt-40"></div>
                                                
                                                <footer class="ts1-author mt-20 clearfix">                                                    
                                                    <div class="ts1-author-img float-start">
                                                        <img class="rounded-circle" width="44" height="44" src="images/ts1-user.jpg" alt="Image description is here" />
                                                    </div>                                                    
                                                    <div>
                                                    Dr Reshma Jhaveri
                                                        <div class="small">
                                                      Mumbai
                                                        </div>
                                                    </div>                                                    
                                                </footer>
                                                
                                            </blockquote>
                                        </div>
                                        <!-- End Slide Item -->
                                        
                                    </div>                            
                                                              
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </section>
                <!-- End Testimonials Section -->
               
                    <!-- Logotypes Section -->
                    <section class="small-section pt-20 pb-20">
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
                </section>
                <!-- End Logotypes -->
                
               
                <!-- End Call to Action Section -->
                    <!-- Parallax Image Section -->
                    <section class="page-section bg-gray-light-1 bg-light-alpha-70 bg-scroll pb-0 mb-100 mb-md-70 mb-sm-50 z-index-1" style="background-image: url(images/full-width-images/section-bg-9.jpg)">
                    <div class="container position-relative">                    
                        
                        <div class="row">
                            <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2 text-center">
                                
                                <h2 class="section-caption mb-xs-10 wow fadeInUp">Our Values</h2>
                                
                                <h3 class="section-title mb-60 mb-sm-40"><span class="wow charsAnimIn" data-splitting="chars"> We prioritize quality, trust, and collaboration as essential pillars of our approach.</span></h3>
                            
                            </div>
                        </div>
                    
                    </div>
                </section>
                <!-- End Parallax Image Section -->
        
            
<section>
    <div class="row">
                                    </div>
                                    </section>
                
       </main>
            
          <?php include('common/footer.php'); ?>
    </body>
</html>