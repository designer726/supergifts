<!-- Site Header -->
<header class="site-header" id="siteHeader">
    <div class="logo-wrap local-scroll">
        <a href="index.php" class="logo">
            <img class="logoimg" src="images/logo.png" alt="SGIPL" />
        </a>
    </div>

    <!-- Desktop Nav -->
    <nav class="nav-menu" id="navMenu">
        <a href="index" <?php if (isset($pagename) && $pagename == "index.php") echo "class='active'"; ?>>Home</a>
        <a href="about" <?php if (isset($pagename) && $pagename == "about.php") echo "class='active'"; ?>>About us</a>
        <a href="services" <?php if (isset($pagename) && $pagename == "services.php") echo "class='active'"; ?>>Services</a>
        <a href="clients" <?php if (isset($pagename) && $pagename == "clients.php") echo "class='active'"; ?>>Clients</a>
        <a href="events" <?php if (isset($pagename) && $pagename == "events.php") echo "class='active'"; ?>>News</a>
        <a href="Careers" <?php if (isset($pagename) && $pagename == "Careers.php") echo "class='active'"; ?>>Careers</a>
        <a href="blog" <?php if (isset($pagename) && $pagename == "blog.php") echo "class='active'"; ?>>Blog</a>
        <a href="reviews" <?php if (isset($pagename) && $pagename == "reviews.php") echo "class='active'"; ?>>Reviews</a>
        <a href="contact" class="header-cta <?php if (isset($pagename) && $pagename == "contact.php") echo "active"; ?>">Contact Us</a>
    </nav>

    <!-- Hamburger Button (mobile) -->
    <button class="nav-hamburger" id="navHamburger" aria-label="Toggle menu" onclick="toggleMobileNav()">
        <span></span><span></span><span></span>
    </button>
</header>

<!-- Mobile Nav Overlay -->
<div class="mobile-nav-overlay" id="mobileNavOverlay" onclick="closeMobileNav()"></div>

<!-- Mobile Nav Drawer -->
<nav class="mobile-nav" id="mobileNav">
    <div class="mobile-nav-header">
        <img src="images/logo.png" alt="SGIPL" style="height:36px;">
        <button onclick="closeMobileNav()" aria-label="Close" style="background:none;border:none;font-size:22px;cursor:pointer;color:#555;">✕</button>
    </div>
    <a href="index" <?php if (isset($pagename) && $pagename == "index.php") echo "class='active'"; ?>>Home</a>
    <a href="about" <?php if (isset($pagename) && $pagename == "about.php") echo "class='active'"; ?>>About us</a>
    <a href="services" <?php if (isset($pagename) && $pagename == "services.php") echo "class='active'"; ?>>Services</a>
    <a href="clients" <?php if (isset($pagename) && $pagename == "clients.php") echo "class='active'"; ?>>Clients</a>
    <a href="events" <?php if (isset($pagename) && $pagename == "events.php") echo "class='active'"; ?>>News</a>
    <a href="Careers" <?php if (isset($pagename) && $pagename == "Careers.php") echo "class='active'"; ?>>Careers</a>
    <a href="blog" <?php if (isset($pagename) && $pagename == "blog.php") echo "class='active'"; ?>>Blog</a>
    <a href="reviews" <?php if (isset($pagename) && $pagename == "reviews.php") echo "class='active'"; ?>>Reviews</a>
    <a href="contact" class="mobile-nav-cta">Contact Us</a>
</nav>

<style>
/* Hamburger */
.nav-hamburger {
    display: none;
    flex-direction: column;
    gap: 5px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 6px;
}
.nav-hamburger span {
    display: block;
    width: 24px;
    height: 2px;
    background: #0D2B55;
    border-radius: 2px;
    transition: all .25s;
}
.nav-hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
.nav-hamburger.open span:nth-child(2) { opacity: 0; }
.nav-hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

/* Mobile overlay */
.mobile-nav-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.45);
    z-index: 998;
}
.mobile-nav-overlay.show { display: block; }

/* Mobile drawer */
.mobile-nav {
    position: fixed;
    top: 0; right: -280px;
    width: 270px;
    height: 100vh;
    background: #fff;
    z-index: 999;
    display: flex;
    flex-direction: column;
    box-shadow: -4px 0 24px rgba(0,0,0,.12);
    transition: right .3s ease;
    overflow-y: auto;
}
.mobile-nav.open { right: 0; }

.mobile-nav-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid #f0f0f0;
}

.mobile-nav a {
    display: block;
    padding: 14px 20px;
    font-size: 15px;
    font-weight: 500;
    color: #333;
    text-decoration: none;
    border-bottom: 1px solid #f5f5f5;
    transition: background .15s, color .15s;
}
.mobile-nav a:hover,
.mobile-nav a.active { background: #f5f8ff; color: #0D2B55; }

.mobile-nav-cta {
    margin: 16px 20px;
    background: #D4AF37 !important;
    color: #0D2B55 !important;
    border-radius: 8px !important;
    font-weight: 700 !important;
    text-align: center;
    border-bottom: none !important;
}

@media (max-width: 860px) {
    .nav-menu { display: none; }
    .nav-hamburger { display: flex; }
}
</style>

<script>
function toggleMobileNav() {
    var nav     = document.getElementById('mobileNav');
    var overlay = document.getElementById('mobileNavOverlay');
    var btn     = document.getElementById('navHamburger');
    var open    = nav.classList.toggle('open');
    overlay.classList.toggle('show', open);
    btn.classList.toggle('open', open);
}
function closeMobileNav() {
    document.getElementById('mobileNav').classList.remove('open');
    document.getElementById('mobileNavOverlay').classList.remove('show');
    document.getElementById('navHamburger').classList.remove('open');
}
</script>
