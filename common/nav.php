 <!-- Navigation Panel -->
 <nav class="main-nav transparent stick-fixed wow-menubar">
                <div class="main-nav-sub full-wrapper">
                    
                    <!-- Logo  (* Add your text or image to the link tag. Use SVG or PNG image format. 
                    If you use a PNG logo image, the image resolution must be equal 200% of the visible logo
                    image size for support of retina screens. See details in the template documentation. *) -->
                    <div class="nav-logo-wrap local-scroll">
                        <a href="index.php" class="logo">
                            <!--<img src="images/logo-dark.svg" alt="Your Company Logo" width="105" height="34" />-->
                            <img class="logoimg" src="images/logo.png" alt="SGIPL" />
                        </a>
                    </div>
                    
                    <!-- Mobile Menu Button -->
                    <div class="mobile-nav" role="button" tabindex="0">
                        <i class="mobile-nav-icon"></i>
                        <span class="visually-hidden">Menu</span>
                    </div>
                    
                    <!-- Main Menu -->
                    <div class="inner-nav desktop-nav">
                        <ul class="clearlist local-scroll">
                            
                            <!-- Item With Sub -->
                            <li><a href="index" <?php if($pagename == "index.php"){echo "class='active'";} ?>>Home </a> </li>
                             <li>  <a href="about"  <?php if($pagename == "about.php"){echo "class='active'";} ?>>About us  </a> </li>
                             <li><a href="services"  <?php if($pagename == "services.php"){echo "class='active'";} ?>>Our services </a> </li>
                             <li><a href="clients"  <?php if($pagename == "clients.php"){echo "class='active'";} ?> >Clients</a> </li>
                             <li><a href="events"  <?php if($pagename == "events.php"){echo "class='active'";} ?>>News  </a> </li>
                             <li><a href="Careers"  <?php if($pagename == "Careers.php"){echo "class='active'";} ?>>Careers  </a> </li>
                         <!--    <li><a href="#" >Location & Loyalty </a> </li> -->
                             <li><a href="contact" <?php if($pagename == "contact.php"){echo "class='active'";} ?>>Contact Us </a></li>
                            <!-- End Item With Sub -->                            
                           
                        </ul>
                        
                        <!--<ul class="items-end clearlist">-->
                            
                    
                                                      
                          <!--  <li><a href="#" class="opacity-1 no-hover"><span class="link-hover-anim underline" data-link-animate="y">Let's work together</span></a></li> -->
                        <!--    <li><a href="index#brandb2b" class="local-scroll opacity-1 "> <img src="images/b2bbrand_logo.png" style="width:200px" alt="SGIPL"  /></a></li>-->
                        <!--</ul>                        -->
                        
                    </div>
                    <!-- End Main Menu -->
                    
                </div>
            </nav>
            <!-- End Navigation Panel -->