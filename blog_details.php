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
            <section class="page-section bg-gray-light-1 bg-light-alpha-90 parallax-5" style="background-image: url(images/full-width-images/section-bg-1.jpg)">
                <div class="container position-relative pt-30 pt-sm-50">
                    <div class="text-center">
                        <div class="row">
                            <div class="col-md-8 offset-md-2">

                                <!-- Category Badge -->
                                <div class="mb-20">
                                    <span style="background:#c8a96e;color:#fff;font-size:12px;padding:4px 14px;border-radius:20px;font-weight:600;letter-spacing:1px;text-transform:uppercase;">
                                        <?= htmlspecialchars($blog['category']) ?>
                                    </span>
                                </div>

                                <!-- Title -->
                                <h1 class="hs-title-1 mb-20">
                                    <span class="wow charsAnimIn" data-splitting="chars">
                                        <?= htmlspecialchars($blog['title']) ?>
                                    </span>
                                </h1>

                                <!-- Meta -->
                                <div class="row">
                                    <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2">
                                        <p class="section-descr mb-0 wow fadeIn" data-wow-delay="0.2s" data-wow-duration="1.2s">
                                            <?= date('F d, Y', strtotime($blog['created_at'])) ?>
                                            &nbsp;|&nbsp; By <?= htmlspecialchars($blog['author']) ?>
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Header Section -->


            <!-- Blog Post Content Section -->
            <section class="page-section">
                <div class="container relative">
                    <div class="row">

                        <!-- Main Content -->
                        <div class="col-lg-8 offset-lg-2">

                            <!-- Featured Image -->
                            <?php if ($blog['image']): ?>
                            <div class="blog-media mb-50">
                                <img src="<?= htmlspecialchars($blog['image']) ?>"
                                     alt="<?= htmlspecialchars($blog['title']) ?>"
                                     style="width:100%;border-radius:8px;object-fit:cover;max-height:450px;" />
                            </div>
                            <?php endif; ?>
                            <!-- End Featured Image -->

                            <!-- Post Content -->
                            <article>

                                <!-- Excerpt (intro) -->
                                <p class="text-gray mb-30" style="font-size:18px;font-weight:500;line-height:1.7;border-left:4px solid #c8a96e;padding-left:20px;">
                                    <?= htmlspecialchars($blog['excerpt']) ?>
                                </p>

                                <!-- Full Content -->
                                <?php if (!empty($blog['content'])): ?>
                                <div class="blog-content text-gray" style="line-height:1.9;font-size:16px;">
                                    <?= nl2br(htmlspecialchars($blog['content'])) ?>
                                </div>
                                <?php endif; ?>

                                <!-- Call to Action -->
                                <div class="bg-gray-light-1 p-40 round mb-50 mt-50">
                                    <h3 class="mb-20">Ready to Transform Your Business?</h3>
                                    <p class="mb-30">
                                        Discover how SGIPL can help you access premium products, optimize operations, and drive sustainable growth.
                                    </p>
                                    <div class="local-scroll">
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
