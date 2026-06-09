 <!-- Modern Navigation Header -->
 <header class="site-header">
     <div class="logo-wrap local-scroll">
         <!-- <a href="index.php" class="logo-link">
            <img src="images/logo.png" alt="Super Gifts logo" class="logo-img" />
        </a> -->
         <a href="index.php" class="logo">
             <!--<img src="images/logo-dark.svg" alt="Your Company Logo" width="105" height="34" />-->
             <img class="logoimg" src="images/logo.png" alt="SGIPL" />
         </a>
     </div>
     <nav class="nav-menu">
        <a href="index" <?php if (isset($pagename) && $pagename == "index.php") {
                               echo "class='active'";
                           } ?>>Home</a>
        <a href="about" <?php if (isset($pagename) && $pagename == "about.php") {
                               echo "class='active'";
                           } ?>>About us</a>
        <a href="services" <?php if (isset($pagename) && $pagename == "services.php") {
                               echo "class='active'";
                           } ?>>Services</a>
        <a href="clients" <?php if (isset($pagename) && $pagename == "clients.php") {
                               echo "class='active'";
                           } ?>>Clients</a>
        <a href="events" <?php if (isset($pagename) && $pagename == "events.php") {
                               echo "class='active'";
                           } ?>>News</a>
        <a href="Careers" <?php if (isset($pagename) && $pagename == "Careers.php") {
                               echo "class='active'";
                           } ?>>Careers</a>
        <a href="blog" <?php if (isset($pagename) && $pagename == "blog.php") {
                           echo "class='active'";
                       } ?>>Blog</a>
        <a href="reviews" <?php if (isset($pagename) && $pagename == "reviews.php") {
                               echo "class='active'";
                           } ?>>Reviews</a>
        <a href="contact" <?php if (isset($pagename) && $pagename == "contact.php") {
                               echo "class='active'";
                           } ?> class="header-cta">Contact Us</a>
     </nav>
 </header>