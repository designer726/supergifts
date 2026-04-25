<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'SGIPL Admin' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --gold: #c8a96e;
            --gold-dark: #a07840;
            --sidebar-bg: #1a1a2e;
            --sidebar-text: #ccc;
        }

        body {
            background: #f4f6fb;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
        }

        .sidebar-logo {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-logo img {
            height: 36px;
            filter: brightness(0) invert(1);
        }

        .sidebar-logo span {
            display: block;
            color: var(--gold);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-top: 6px;
            text-transform: uppercase;
        }

        .sidebar nav {
            flex: 1;
            padding: 16px 0;
        }

        .nav-section {
            padding: 12px 20px 4px;
            color: rgba(255, 255, 255, 0.35);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .sidebar a.nav-link {
            color: var(--sidebar-text);
            padding: 10px 20px;
            border-radius: 0;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .sidebar a.nav-link:hover,
        .sidebar a.nav-link.active {
            background: rgba(200, 169, 110, 0.15);
            color: var(--gold);
            border-right: 3px solid var(--gold);
        }

        .sidebar a.nav-link i {
            font-size: 16px;
            width: 20px;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-footer a {
            color: rgba(255, 255, 255, 0.5);
            font-size: 13px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sidebar-footer a:hover {
            color: #ff6b6b;
        }

        /* Main */
        .main-wrapper {
            margin-left: 240px;
            min-height: 100vh;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .topbar-title {
            font-weight: 700;
            color: #1a1a2e;
            font-size: 18px;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #555;
        }

        .avatar {
            width: 34px;
            height: 34px;
            background: var(--gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 13px;
        }

        .content-area {
            padding: 28px;
        }

        /* Cards */
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #e9ecef;
            transition: box-shadow 0.2s;
        }

        .stat-card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .stat-card .icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .stat-card .count {
            font-size: 32px;
            font-weight: 800;
            color: #1a1a2e;
        }

        .stat-card .label {
            color: #888;
            font-size: 13px;
        }

        /* Table */
        .data-table {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            overflow: hidden;
        }

        .data-table .table {
            margin: 0;
        }

        .data-table .table thead th {
            background: #f8f9fa;
            color: #555;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e9ecef;
        }

        .badge-published {
            background: #d1fae5;
            color: #065f46;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
        }

        .badge-draft {
            background: #fef3c7;
            color: #92400e;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
        }

        .btn-gold {
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: #fff;
            border: none;
            font-weight: 600;
        }

        .btn-gold:hover {
            background: linear-gradient(135deg, var(--gold-dark), #7d5e2e);
            color: #fff;
        }

        /* Form */
        .form-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            padding: 28px;
        }

        .form-label {
            font-weight: 600;
            font-size: 13px;
            color: #444;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 0.2rem rgba(200, 169, 110, 0.2);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-wrapper {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="https://www.supergifts.in/images/logo.png" alt="SGIPL" onerror="this.style.display='none'">
            <span>Admin Panel</span>
        </div>
        <nav>
            <div class="nav-section">Main</div>
            <a href="<?= BASE_URL ?>dashboard.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <div class="nav-section">Content</div>
            <a href="<?= BASE_URL ?>blogs/index.php" class="nav-link <?= (strpos($_SERVER['PHP_SELF'], '/blogs/') !== false) ? 'active' : '' ?>">
                <i class="bi bi-journal-richtext"></i> Blog Posts
            </a>
            <a href="<?= BASE_URL ?>blogs/add.php" class="nav-link">
                <i class="bi bi-plus-circle"></i> New Blog Post
            </a>
            <div class="nav-section">Website</div>
            <a href="https://www.supergifts.in/blog" target="_blank" class="nav-link">
                <i class="bi bi-globe"></i> View Live Blog
            </a>
            <a href="https://www.supergifts.in" target="_blank" class="nav-link">
                <i class="bi bi-house"></i> View Website
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="<?= BASE_URL ?>logout.php"><i class="bi bi-box-arrow-left"></i> Logout (<?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?>)</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-wrapper">
        <div class="topbar">
            <div class="topbar-title"><?= $pageTitle ?? 'Dashboard' ?></div>
            <div class="topbar-user">
                <div class="avatar"><?= strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)) ?></div>
                <span><?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?></span>
            </div>
        </div>
        <div class="content-area">