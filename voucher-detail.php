<?php
$pagename = basename($_SERVER['PHP_SELF']);

// DB Connection
$servername = "localhost";
$username   = "superehc_aiir";
$password   = "Aiir@8097000970";
$dbname     = "superehc_sgipl";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Get voucher ID from URL
$voucher_id = intval($_GET['id'] ?? 0);

if (!$voucher_id) {
    header("Location: index.php");
    exit();
}

// Fetch selected voucher
$stmt = $conn->prepare("SELECT * FROM vouchers WHERE id = ? AND status = 1");
$stmt->bind_param("i", $voucher_id);
$stmt->execute();
$voucher = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$voucher) {
    header("Location: index.php");
    exit();
}

// Fetch all other vouchers
$stmt = $conn->prepare("SELECT * FROM vouchers WHERE id != ? AND status = 1 ORDER BY seqence ASC, id ASC");
$stmt->bind_param("i", $voucher_id);
$stmt->execute();
$otherVouchers = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('common/head.php'); ?>
    <title><?= htmlspecialchars($voucher['title']) ?> | Gift Vouchers — SGIPL</title>
    <style>
        .voucher-detail-hero {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 40px 0;
        }

        .voucher-image-wrap {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 16px;
            padding: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 340px;
            overflow: hidden;
        }

        .voucher-image-wrap img {
            max-width: 100%;
            max-height: 380px;
            object-fit: contain;
            width: auto;
        }

        .voucher-title {
            font-size: 32px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 20px;
            line-height: 1.3;
        }

        .voucher-description {
            color: #555;
            font-size: 15px;
            line-height: 1.8;
            margin-bottom: 28px;
        }

        .btn-inquire {
            background: linear-gradient(135deg, #ff6b00 0%, #ff8c00 100%);
            color: #fff !important;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            font-size: 15px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-inquire:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 0, 0.3);
            color: #fff;
        }

        .other-vouchers-sec {
            padding: 60px 0;
            background: #f8f9fa;
        }

        .section-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 8px;
            text-align: center;
        }

        .section-subtitle {
            text-align: center;
            color: #999;
            margin-bottom: 40px;
            font-size: 14px;
        }

        .voucher-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
        }

        .voucher-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transform: translateY(-5px);
        }

        .voucher-card-img-wrap {
            background: #f5f5f5;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-bottom: 1px solid #e9ecef;
        }

        .voucher-card-img-wrap img {
            max-height: 180px;
            max-width: 100%;
            object-fit: contain;
            padding: 10px;
        }

        .voucher-card-info {
            padding: 16px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .voucher-card-name {
            font-size: 15px;
            font-weight: 600;
            color: #1a1a2e;
            line-height: 1.4;
        }

        .breadcrumb-nav {
            background: #fff;
            padding: 16px 0;
            border-bottom: 1px solid #e9ecef;
            font-size: 13px;
        }

        .breadcrumb-nav a {
            color: #0066cc;
            text-decoration: none;
        }

        .breadcrumb-nav a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .voucher-title {
                font-size: 22px;
            }

            .voucher-image-wrap {
                min-height: 260px;
            }

            .section-title {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>

    <!-- Navigation -->
    <?php include('common/nav.php'); ?>

    <!-- Breadcrumb -->
    <div class="breadcrumb-nav container mt-3">
        <a href="index.php">Home</a>
        <span class="mx-2">/</span>
        <span>Gift Vouchers</span>
        <span class="mx-2">/</span>
        <span><?= htmlspecialchars($voucher['title']) ?></span>
    </div>

    <!-- Voucher Detail Hero Section -->
    <section class="voucher-detail-hero">
        <div class="container">
            <div class="row g-4 align-items-center justify-content-center">
                <!-- Voucher Image -->
                <div class="col-lg-7">
                    <div class="voucher-image-wrap">
                        <?php if (!empty($voucher['image'])): ?>
                            <img src="<?= htmlspecialchars($voucher['image']) ?>"
                                 alt="<?= htmlspecialchars($voucher['title']) ?>"
                                 onerror="this.src='https://via.placeholder.com/500x300?text=Gift+Voucher'">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/500x300?text=Gift+Voucher"
                                 alt="Gift Voucher">
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Voucher Details -->
                <div class="col-lg-5">
                    <h1 class="voucher-title">
                        <?= htmlspecialchars($voucher['title']) ?>
                    </h1>
                    <p class="voucher-description">
                        The perfect gift for every occasion. This <?= htmlspecialchars($voucher['title']) ?> lets your
                        recipient choose exactly what they want — ideal for corporate gifting, employee rewards,
                        and client appreciation.
                    </p>
                    <a href="contact.php?voucher=<?= urlencode($voucher['title']) ?>" class="btn-inquire">
                        <i class="fa fa-envelope me-2"></i>Inquire Now
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Other Vouchers Section -->
    <?php if ($otherVouchers->num_rows > 0): ?>
    <section class="other-vouchers-sec">
        <div class="container">
            <h2 class="section-title">More Gift Vouchers</h2>
            <p class="section-subtitle">Explore other available gift vouchers</p>

            <div class="row g-4">
                <?php while ($ov = $otherVouchers->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4">
                    <a href="voucher-detail.php?id=<?= intval($ov['id']) ?>" class="voucher-card">
                        <div class="voucher-card-img-wrap">
                            <?php if (!empty($ov['image'])): ?>
                                <img src="<?= htmlspecialchars($ov['image']) ?>"
                                     alt="<?= htmlspecialchars($ov['title']) ?>"
                                     onerror="this.src='https://via.placeholder.com/200x150?text=Voucher'">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/200x150?text=Voucher"
                                     alt="Gift Voucher">
                            <?php endif; ?>
                        </div>
                        <div class="voucher-card-info">
                            <div class="voucher-card-name">
                                <?= htmlspecialchars($ov['title']) ?>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Footer -->
    <?php include('common/footer.php'); ?>

    <!-- JS -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/all.js"></script>
    <script src="js/plugins.js"></script>

</body>

</html>
