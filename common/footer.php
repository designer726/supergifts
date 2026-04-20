  <!-- Footer -->
  <footer class="page-section footer bg-gray-light-1 pb-30">
                <div class="container">
                    
                    <div class="row pb-120 pb-sm-80 pb-xs-50">
                                                
                        <div class="col-md-4 col-lg-3 text-gray mb-sm-50">
                            
                            <div class="mb-30">
                                <img src="images/logo.png"  alt="SGIPL" style="width: 158px;"/>
                            </div>
                            
                            <p>
                            With a legacy of excellence, we take pride in our extensive experience, catering to over 140+ gifting companies and serving more than 100 clients & with offices across Mumbai, Hyderabad & Bengaluru.

                            </p>
                            
                           
                            
                        </div>
                        
                        <div class="col-md-7 offset-md-1 offset-lg-2">                            
                            <div class="row mt-n30">
                                
                                <!-- Footer Widget -->
                                <div class="col-sm-4 mt-30">
                                    
                                    <h3 class="fw-title">Company</h3>
                                    
                                    <ul class="fw-menu clearlist">                                        
                                        <li><a href="about">About</a></li>
                                        <li><a href="services">Services</a></li>
                                        <li><a href="index#brandb2b">Partners</a></li>
                                        <li><a href="events">News</a></li>
                                        <li><a href="contact">Contact</a></li>
                                    </ul>
                                    
                                </div>
                                <!-- End Footer Widget -->
                                
                                <!-- Footer Widget -->
                                <div class="col-sm-4 mt-30">
                                    
                                    <h3 class="fw-title">Social Media</h3>
                                    
                                    <ul class="fw-menu clearlist">
                                        <li>
                                            <a href="#" rel="noopener nofollow" target="_blank">
                                                <i class="fa-facebook"></i>
                                                Facebook
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" rel="noopener nofollow" target="_blank">
                                                <i class="fa-youtube"></i>
                                                Youtube
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" rel="noopener nofollow" target="_blank">
                                                <i class="fa-pinterest"></i>
                                                Pinterest
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" rel="noopener nofollow" target="_blank">
                                                <i class="fa-linkedin"></i>
                                                LinkedIn
                                            </a>
                                        </li>
                                    </ul>
                                    
                                </div>
                                <!-- End Footer Widget -->
                                
                                <!-- Footer Widget -->
                                <div class="col-sm-4 mt-30">
                                    
                                    <h3 class="fw-title">Legal & Press</h3>
                                    
                                    <ul class="fw-menu clearlist">                                     
                                        <li><a href="PrivacyPolicy">Privacy Policy</a></li>
                                        <li><a href="TermandCondition">Terms & Conditions</a></li>
                                        <!--<li><a href="#">Presskit</a></li>  -->
                                    </ul>
                                    <p></p>
                                    <h3 class="fw-title">Quick Contact</h3>

                                    <div class="clearlinks">                                
                                <strong>T.</strong> <a href="tel:+9197000020369">+91 93926 85811</a>
                            </div>
                            
                            <div class="clearlinks">
                                <strong>E.</strong> <a href="mailto:operations@supergifts.in">info@supergifts.in</a>
                            </div>

                                </div>
                                <!-- End Footer Widget -->                                
                                
                            </div>                            
                        </div>
                        
                    </div>
                    
                    <!-- Footer Text -->
                    <div class="row text-gray">
                        
                        <div class="col-md-4 col-lg-3">
                            <b>© SGIPL 2024.</b>
                        </div>
                        
                        <div class="col-md-7 offset-md-1 offset-lg-2 clearfix">
                            
                            <b>Based in Hyderabad, Mumbai, Bengaluru.</b>
                            
                            <!-- Back to Top Link -->
                            <div class="local-scroll float-end mt-n20 mt-sm-10">
                                <a href="#top" class="link-to-top">                                
                                    <i class="mi-arrow-up size-24"></i>
                                    <span class="visually-hidden">Scroll to top</span>
                                </a>
                            </div>
                            <!-- End Back to Top Link -->
                            
                        </div>
                        
                    </div>
                    <!-- End Footer Text --> 
                    
                 </div>                 

                 <div class="whatsapplink" > 
                    <a href="https://web.whatsapp.com/send?phone=+919967404027&text=Hello SGIPL, Need to enqurey"><img src="images/whatsapp_icon.svg" /></a> <span> Need Help?</span>
</div>
            </footer>
            <!-- End Footer -->
        
        </div>
        <!-- End Page Wrap -->      
        
        <!-- JS -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/plugins.js"></script>
        <script src="js/jquery.ajaxchimp.min.js"></script>             
        <script src="js/contact-form.js"></script>        
        <script src="js/all.js"></script> 
        <!-- End JS -->       
       
        <script>
        $(document).ready(function() {
            const options = {
                threshold: 0.5 // Trigger when 50% of the element is visible
            };

            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !$(entry.target).data('animated')) {
                        $(entry.target).data('animated', true).prop('Counter', 0).animate({
                            Counter: $(entry.target).text()
                        }, {
                            duration: 3000,
                            easing: 'swing',
                            step: function(now) {
                                $(entry.target).text(Math.ceil(now));
                            }
                        });
                    }
                });
            }, options);

            $('.Count').each(function() {
                observer.observe(this);
            });
        });
    </script>