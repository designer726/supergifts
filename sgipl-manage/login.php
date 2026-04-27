<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        $stmt = $conn->prepare("SELECT id, username, password FROM admin_users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['admin_id']       = $user['id'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
        $stmt->close();
    } else {
        $error = "Please enter both username and password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGIPL Admin — Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); min-height: 100vh; display: flex; align-items: center; }
        .login-card { background: #fff; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.4); overflow: hidden; max-width: 420px; width: 100%; }
        .login-header { background: linear-gradient(135deg, #c8a96e, #a07840); padding: 36px 32px 28px; text-align: center; }
        .login-header img { height: 50px; filter: brightness(0) invert(1); margin-bottom: 10px; }
        .login-header h4 { color: #fff; margin: 0; font-weight: 700; letter-spacing: 1px; font-size: 15px; }
        .login-body { padding: 36px 32px; }
        .form-control:focus { border-color: #c8a96e; box-shadow: 0 0 0 0.2rem rgba(200,169,110,0.2); }
        .btn-login { background: linear-gradient(135deg, #c8a96e, #a07840); border: none; color: #fff; font-weight: 600; padding: 12px; letter-spacing: 0.5px; border-radius: 8px; }
        .btn-login:hover { background: linear-gradient(135deg, #a07840, #7d5e2e); color: #fff; }
        .input-group-text { background: #f8f4ef; border-color: #dee2e6; color: #c8a96e; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="login-card">
                <div class="login-header">
                    <img src="https://www.supergifts.in/images/logo.png" alt="SGIPL Logo" onerror="this.style.display='none'">
                    <h4>SUPER GIFTS ADMIN PANEL</h4>
                </div>
                <div class="login-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger py-2 small"><i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="username" class="form-control" placeholder="Enter username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autofocus>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-login w-100"><i class="bi bi-box-arrow-in-right me-2"></i>Sign In</button>
                    </form>
                    <p class="text-center text-muted small mt-4 mb-0">© <?= date('Y') ?> Super Gifts (India) Pvt. Ltd.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
