<?php
$pagename = basename($_SERVER['PHP_SELF']);
require_once('sgipl-manage/includes/db.php'); // Your existing DB connection file

// Fetch published blog posts
$result = $conn->query("SELECT * FROM blogs WHERE status = 'published' ORDER BY created_at DESC");
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
            <section class="page-section bg-gray-light-1 bg-light-alpha-90 parallax-5" style="background-image: url(images/full-width-images/section-bg-1.jpg)" id="home">
                <div class="container position-relative pt-30 pt-sm-50">
                    <div class="text-center">
                        <div class="row">
                            <div class="col-md-8 offset-md-2">
                                <h1 class="hs-title-1 mb-20">
                                    <span class="wow charsAnimIn" data-splitting="chars">Blog</span>
                                </h1>
                                <div class="row">
                                    <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2">
                                        <p class="section-descr mb-0 wow fadeIn" data-wow-delay="0.2s" data-wow-duration="1.2s">
                                            Latest Insights, Tips, and Updates from SGIPL
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Header Section -->


            <!-- Blog Section -->
            <section class="page-section">
                <div class="container relative">

                    <?php if ($result && $result->num_rows > 0): ?>
                    <div class="row g-4">

                        <?php while ($blog = $result->fetch_assoc()): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="blog-item box-shadow round p-4 h-100 d-flex flex-column">

                                <!-- Image -->
                                <div class="blog-media mb-3">
                                    <a href="blog_details?BlogDetails=<?= htmlspecialchars($blog['slug']) ?>">
                                        <img src="<?= htmlspecialchars($blog['image']) ?>"
                                             alt="<?= htmlspecialchars($blog['title']) ?>"
                                             class="img-fluid rounded"
                                             style="width:100%;height:200px;object-fit:cover;" />
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
