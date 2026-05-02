  <!-- Modern Footer -->
  <footer>
    <div class="footer-top">
      <div class="footer-brand">
        <div class="logo-wrap" style="margin-bottom:0">
          <div class="logo-icon">🎁</div>
          <div class="logo-text" style="color:#fff">Super<span>Gifts</span></div>
        </div>
        <p>India's leading corporate gifting platform. Premium quality, custom branding, and seamless logistics — all in one place.</p>
        <div class="socials">
          <a href="https://www.linkedin.com/in/supergifts/" rel="noopener nofollow" target="_blank" class="soc-btn">🔗</a>
          <a href="https://www.facebook.com/people/Super-Gifts-India-Private-Limited/100090976219801/" rel="noopener nofollow" target="_blank" class="soc-btn">📘</a>
          <a href="https://www.instagram.com/supergifts_official/" rel="noopener nofollow" target="_blank" class="soc-btn">📸</a>
          <a href="#" rel="noopener nofollow" target="_blank" class="soc-btn">🐦</a>
        </div>
      </div>
      <div class="footer-col">
        <h5>For Resellers</h5>
        <ul>
          <li><a href="services">Services</a></li>
          <li><a href="contact">Bulk Inquiry</a></li>
          <li><a href="#">Signup</a></li>
          <li><a href="#">Partner Portal</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h5>For Corporates</h5>
        <ul>
          <li><a href="services">Branding</a></li>
          <li><a href="services">Logistics</a></li>
          <li><a href="services">Packaging</a></li>
          <li><a href="services">After Sales</a></li>
          <li><a href="contact">Request Proposal</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h5>Company</h5>
        <ul>
          <li><a href="about">About Us</a></li>
          <li><a href="#">Our Team</a></li>
          <li><a href="events">News</a></li>
          <li><a href="reviews">Reviews</a></li>
          <li><a href="contact">Contact</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p>© 2025 <span>SuperGifts</span>. All rights reserved.</p>
      <p>Made with ❤️ in Hyderabad, India</p>
    </div>
  </footer>
  <!-- End Modern Footer -->
        
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