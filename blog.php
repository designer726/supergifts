<?php
$pagename = basename($_SERVER['PHP_SELF']);
require_once('sgipl-manage/includes/db.php'); // Your existing DB connection file

// Fetch published blog posts
$result = $conn->query("SELECT * FROM blogs WHERE status = 'published' ORDER BY (sequence = 0) ASC, sequence ASC, created_at DESC");

/* Blog hero banner — admin-managed via Banner Management (slot 18).
   Same "photo" (page draws its own heading/stats on top, tinted with the
   navy gradient) vs "full" (ready-made graphic, shown as-is) modes used
   for the Clients page banner. Falls back to the default navy gradient
   hero when nothing is uploaded. */
$blogHeroImg    = '';
$blogHeroCustom = false;
$blogHeroFull   = false;
$blr = $conn->query("SELECT file_path, display_mode FROM banners WHERE slot=18 AND status=1 AND file_path<>'' LIMIT 1");
if ($blr && ($blrow = $blr->fetch_assoc()) && !empty($blrow['file_path'])) {
    $blogHeroImg    = $blrow['file_path'];
    $blogHeroCustom = true;
    $blogHeroFull   = (($blrow['display_mode'] ?? 'photo') === 'full');
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

            <style>
            /* ============ BLOG PAGE HERO — scoped overrides ============ */
            /* "Ready-made banner" mode (Slot 18, display_mode=full): plain,
               uncropped, fully responsive image — no cropping at any width. */
            .blog-hero-full { width:100%; line-height:0; font-size:0; background:#140f3d; }
            .blog-hero-full img { width:100%; height:auto; display:block; }

            <?php if ($blogHeroCustom && !$blogHeroFull): ?>
            /* "Plain photo" mode: swap the default navy gradient for the
               uploaded photo, keeping the same tint so text stays legible. */
            #blog.hero {
                background:
                    linear-gradient(130deg, rgba(36,28,107,.88) 0%, rgba(75,63,158,.82) 55%, rgba(20,15,61,.9) 100%),
                    url('<?= htmlspecialchars($blogHeroImg) ?>') center right / cover no-repeat;
            }
            <?php endif; ?>

            /* The shared .hero-right stat column is absolute-positioned for
               the desktop layout and only resets to static between 769-1200px
               in the shared stylesheet — below that it was overlapping the
               heading text. Scoped to #blog so Careers/Events aren't touched. */
            @media (max-width: 768px) {
                #blog.hero { flex-wrap: wrap; }
                #blog .hero-right {
                    position: static;
                    transform: none;
                    flex-direction: row;
                    flex-wrap: wrap;
                    margin-top: 24px;
                    width: 100%;
                }
                #blog .hero-right .stat-card { flex: 1 1 120px; min-width: 0; }
            }
            @media (max-width: 480px) {
                #blog .hero-right { gap: 8px; }
                #blog .hero-right .stat-card { padding: 10px 12px; }
                #blog .hero-right .stat-card .num { font-size: 20px; }
            }
            </style>

            <!-- Modern Hero Section -->
            <?php if ($blogHeroFull): ?>
            <section class="blog-hero-full" id="blog">
                <img src="<?= htmlspecialchars($blogHeroImg) ?>" alt="Latest Insights, Tips, and Updates">
            </section>
            <?php else: ?>
            <section class="hero" id="blog">
                <div class="hero-content">
                    <div class="hero-badge">✦ Our Blog</div>
                    <h1>Latest Insights, Tips, and <em>Updates</em></h1>
                    <p>Latest Insights, Tips, and Updates from SGIPL</p>
                    <div class="hero-btns">
                        <button class="btn-primary" onclick="window.location.href='contact.php'">Subscribe →</button>
                        <button class="btn-outline" onclick="window.location.href='events.php'">View News</button>
                    </div>
                </div>
                <div class="hero-right">
                    <div class="stat-card"><div class="num">100+</div><div class="lbl">Articles</div></div>
                    <div class="stat-card"><div class="num">50K+</div><div class="lbl">Monthly Reads</div></div>
                    <div class="stat-card"><div class="num">10</div><div class="lbl">Categories</div></div>
                </div>
            </section>
            <?php endif; ?>
            <!-- End Modern Hero Section -->


            <!-- Blog Section -->
            <section class="page-section">
                <div class="container relative">

                    <?php if ($result && $result->num_rows > 0): ?>
                    <div class="row g-4">

                        <?php while ($blog = $result->fetch_assoc()): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="blog-item box-shadow round p-4 h-100 d-flex flex-column">

                                <!-- Image -->
                                <div class="blog-media mb-3" style="position:relative;">
                                    <a href="blog_details?BlogDetails=<?= htmlspecialchars($blog['slug']) ?>">
                                        <img src="<?= htmlspecialchars($blog['image']) ?>"
                                             alt="<?= htmlspecialchars($blog['title']) ?>"
                                             class="img-fluid rounded"
                                             style="width:100%;height:200px;object-fit:cover;" />
                                        <?php if (!empty($blog['video'] ?? '')): ?>
                                        <span style="position:absolute;top:10px;right:10px;background:rgba(0,0,0,0.7);color:#fff;font-size:11px;font-weight:700;padding:4px 10px;border-radius:999px;letter-spacing:.04em;">▶ VIDEO</span>
                                        <?php endif; ?>
                                    </a>
                                </div>

                                <!-- Title -->
                                <h2 class="blog-item-title h5">
                                    <a href="blog_details?BlogDetails=<?= htmlspecialchars($blog['slug']) ?>">
                                        <?= htmlspecialchars($blog['title']) ?>
                                    </a>
                                </h2>

                                <!-- Meta -->
                                <div class="blog-item-data mb-2" style="font-size:13px;">
                                    <i class="mi-clock size-16"></i>
                                    <?= date('F d, Y', strtotime($blog['created_at'])) ?>
                                    &nbsp;|&nbsp;
                                    <i class="mi-user size-16"></i>
                                    <?= htmlspecialchars($blog['author']) ?>
                                    &nbsp;|&nbsp;
                                    <i class="mi-folder size-16"></i>
                                    <?= htmlspecialchars($blog['category']) ?>
                                </div>

                                <!-- Excerpt -->
                                <p class="mb-3 flex-grow-1"><?= htmlspecialchars($blog['excerpt']) ?></p>

                                <!-- Read More -->
                                <div class="blog-item-foot mt-auto">
                                    <a href="blog_details?BlogDetails=<?= htmlspecialchars($blog['slug']) ?>"
                                       class="btn btn-mod btn-round btn-medium btn-gray">Read More</a>
                                </div>

                            </div>
                        </div>
                        <?php endwhile; ?>

                    </div>
                    <?php else: ?>
                    <div class="text-center py-5">
                        <p class="text-muted">No blog posts published yet. Check back soon!</p>
                    </div>
                    <?php endif; ?>

                </div>
            </section>
            <!-- End Blog Section -->

            <hr class="mt-0 mb-0" />

        </main>

        <?php include('common/footer.php'); ?>
    </div>
    <!-- End Page Wrap -->

</body>
</html>
