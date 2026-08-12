<?php
$pagename = basename($_SERVER['PHP_SELF']);

// DB Connection
$conn = new mysqli("localhost", "superehc_aiir", "Aiir@8097000970", "superehc_sgipl");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get slug from URL: blog_details?BlogDetails=first-post
$slug = trim($_GET['BlogDetails'] ?? '');
if (!$slug) {
    header("Location: blog");
    exit();
}

// Fetch blog post by slug
$stmt = $conn->prepare("SELECT * FROM blogs WHERE slug = ? AND status = 'published' LIMIT 1");
$stmt->bind_param("s", $slug);
$stmt->execute();
$blog = $stmt->get_result()->fetch_assoc();
$stmt->close();

// If blog not found, redirect to blog page
if (!$blog) {
    header("Location: blog");
    exit();
}

// Fetch previous and next posts for navigation
$prev = $conn->query("SELECT title, slug FROM blogs WHERE status='published' AND created_at < '{$blog['created_at']}' ORDER BY created_at DESC LIMIT 1")->fetch_assoc();
$next = $conn->query("SELECT title, slug FROM blogs WHERE status='published' AND created_at > '{$blog['created_at']}' ORDER BY created_at ASC LIMIT 1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($blog['excerpt']) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($blog['category']) ?>, SGIPL, blog">
    <meta name="author" content="<?= htmlspecialchars($blog['author']) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($blog['title']) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($blog['excerpt']) ?>">
    <meta property="og:type" content="article">
    <?php if ($blog['image']): ?>
    <meta property="og:image" content="<?= htmlspecialchars($blog['image']) ?>">
    <?php endif; ?>

    <?php include('common/head.php'); ?>
    <title><?= htmlspecialchars($blog['title']) ?> | SGIPL Blog</title>
</head>
<body class="appear-animate" style="background:#ffffff;color:#111;">

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
            <?php
            $titleHeaderBgImage = '';
            if (!empty($blog['title_bg_image'])) {
                $titleHeaderBgImage = htmlspecialchars($blog['title_bg_image']);
            } elseif (!empty($blog['image'])) {
                $titleHeaderBgImage = htmlspecialchars($blog['image']);
            }
            ?>
            <section class="page-section parallax-5" style="<?php if ($titleHeaderBgImage): ?>background-image:url('<?= $titleHeaderBgImage ?>');background-size:cover;background-position:center;background-repeat:no-repeat;background-color:#ffffff;<?php else: ?>background:#ffffff;<?php endif; ?> color:#111;">
                <div class="container pt-50 pb-50">
                    <div class="text-center">
                        <div class="row justify-content-center">
                            <div class="col-xl-7 col-lg-8 col-md-10">
                                <div style="background: rgba(255,255,255,0.92); padding: 42px 32px; border-radius: 24px;">

                                <div class="mb-16">
                                    <span style="background:#111;color:#fff;font-size:11px;padding:6px 12px;border-radius:999px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;">
                                        <?= htmlspecialchars($blog['category']) ?>
                                    </span>
                                </div>

                                <h1 class="hs-title-1 mb-18" style="font-size:3rem; line-height:1.05; color:#111; font-weight:800;">
                                    <?= htmlspecialchars($blog['title']) ?>
                                </h1>

                                <div class="row">
                                    <div class="col-md-10 offset-md-1 col-lg-10 offset-lg-1">
                                        <p class="section-descr mb-0" style="color:#5f5f5f; letter-spacing:0.03em; font-size:0.95rem;">
                                            <?= date('F d, Y', strtotime($blog['created_at'])) ?>
                                            &nbsp;|&nbsp; <?= htmlspecialchars($blog['author']) ?>
                                        </p>
                                    </div>
                                </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Header Section -->


            <!-- Blog Post Content Section -->
            <section class="page-section" style="background:#ffffff;">
                <div class="container relative" style="color:#2a2a2a;">
                    <div class="row">

                        <!-- Main Content -->
                        <div class="col-lg-8 offset-lg-2">

                            <!-- Featured Image -->
                            <?php if ($blog['image']): ?>
                            <div class="blog-media mb-50">
                                <img src="<?= htmlspecialchars($blog['image']) ?>"
                                     alt="<?= htmlspecialchars($blog['title']) ?>"
                                     style="width:100%;height:auto;border-radius:8px;" />
                            </div>
                            <?php endif; ?>
                            <!-- End Featured Image -->

                            <!-- Post Content -->
                            <article>

                                <!-- Excerpt (intro) -->
                                <p class="text-gray mb-30" style="font-size:18px;font-weight:500;line-height:1.7;border-left:4px solid #e1e1e1;padding-left:20px;color:#5c5c5c;">
                                    <?= htmlspecialchars($blog['excerpt']) ?>
                                </p>

                                <!-- Full Content -->
                                <?php if (!empty($blog['content'])): ?>
                                <div class="blog-content text-gray" style="line-height:1.95;font-size:17px;color:#333;">
                                    <?= nl2br(htmlspecialchars($blog['content'])) ?>
                                </div>
                                <?php endif; ?>

                                <?php if (!empty($blog['link'] ?? '')): ?>
                                <div class="p-40 round mb-50 mt-50" style="text-align:center;background:transparent;">
                                    <h4 class="mb-20" style="color:#111; font-weight:700;">Click to Know more</h4>
                                    <a href="<?= htmlspecialchars($blog['link']) ?>" target="_blank" rel="noopener noreferrer" class="header-cta" style="display:inline-block;padding:14px 28px;border:1px solid #000;border-radius:999px;color:#fff;background:#000;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;">Know more</a>
                                </div>
                                <?php endif; ?>

                                <!-- Call to Action -->
                                <div class="p-40 round mb-50 mt-50" style="background:#f6f6f6;">
                                    <h3 class="mb-20" style="color:#111; font-weight:700;">Ready to Transform Your Business?</h3>
                                    <p class="mb-30" style="color:#555; font-size:15px;">
                                        Discover how SGIPL can help you access premium products, optimize operations, and drive sustainable growth.
                                    </p>
                                    <div class="local-scroll" style="text-align:center;">
                                        <a href="contact" class="btn btn-mod btn-large btn-round btn-hover-anim"><span>Contact Us</span></a>
                                    </div>
                                </div>

                                <!-- Post Meta -->
                                <div class="blog-item-data pt-30 border-top">
                                    <div class="mb-10">
                                        <strong>Category:</strong>
                                        <?= htmlspecialchars($blog['category']) ?>
                                    </div>
                                    <div class="mb-10">
                                        <strong>Published by:</strong> <?= htmlspecialchars($blog['author']) ?>
                                        &nbsp;|&nbsp;
                                        <strong>Date:</strong> <?= date('F d, Y', strtotime($blog['created_at'])) ?>
                                    </div>
                                </div>

                            </article>

                            <!-- Prev / Next Navigation -->
                            <?php if ($prev || $next): ?>
                            <div class="mt-50 pt-30 border-top">
                                <div class="row">
                                    <div class="col-6">
                                        <?php if ($prev): ?>
                                        <a href="blog_details?BlogDetails=<?= htmlspecialchars($prev['slug']) ?>" class="link-hover-anim underline">
                                            <i class="mi-arrow-left size-18 align-middle me-10"></i>
                                            <span style="font-size:13px;color:#888;">Previous</span><br>
                                            <span style="font-size:14px;font-weight:600;"><?= htmlspecialchars(substr($prev['title'], 0, 50)) ?>...</span>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-6 text-end">
                                        <?php if ($next): ?>
                                        <a href="blog_details?BlogDetails=<?= htmlspecialchars($next['slug']) ?>" class="link-hover-anim underline">
                                            <span style="font-size:13px;color:#888;">Next</span><br>
                                            <span style="font-size:14px;font-weight:600;"><?= htmlspecialchars(substr($next['title'], 0, 50)) ?>...</span>
                                            <i class="mi-arrow-right size-18 align-middle ms-10"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Back to Blog -->
                            <div class="mt-30 pt-30 border-top">
                                <a href="blog" class="link-hover-anim underline align-middle">
                                    <i class="mi-arrow-left size-18 align-middle me-10"></i> Back to Blog
                                </a>
                            </div>

                        </div>
                        <!-- End Main Content -->

                    </div>
                </div>
            </section>
            <!-- End Blog Post Content Section -->

        </main>

        <?php include('common/footer.php'); ?>
    </div>
    <!-- End Page Wrap -->

</body>
</html>
