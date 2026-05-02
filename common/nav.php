 <!-- Modern Navigation Header -->
 <header class="site-header">
    <div class="logo-wrap local-scroll">
        <div class="logo-icon">🎁</div>
        <div>
            <a href="index.php" class="logo-text">Super<span>Gifts</span></a>
        </div>
    </div>
    <nav class="nav-menu">
        <a href="index" <?php if($pagename == "index.php"){echo "class='active'";} ?>>Home</a>
        <a href="about" <?php if($pagename == "about.php"){echo "class='active'";} ?>>About us</a>
        <a href="services" <?php if($pagename == "services.php"){echo "class='active'";} ?>>Services</a>
        <a href="clients" <?php if($pagename == "clients.php"){echo "class='active'";} ?>>Clients</a>
        <a href="events" <?php if($pagename == "events.php"){echo "class='active'";} ?>>News</a>
        <a href="Careers" <?php if($pagename == "Careers.php"){echo "class='active'";} ?>>Careers</a>
        <a href="blog" <?php if($pagename == "blog.php"){echo "class='active'";} ?>>Blog</a>
        <a href="reviews" <?php if($pagename == "reviews.php"){echo "class='active'";} ?>>Reviews</a>
        <a href="contact" <?php if($pagename == "contact.php"){echo "class='active'";} ?> class="header-cta">Contact Us</a>
    </nav>
 </header>