<?php
// Base URL of the admin panel (with trailing slash)
// Change this to match your server path, e.g., https://www.supergifts.in/sgipl-manage/
// define('BASE_URL', '/sgipl-manage/');

// Auto-detects localhost vs live server
if ($_SERVER['SERVER_NAME'] === 'localhost') {
    define('BASE_URL', '/supergifts/sgipl-manage/');
} else {
    define('BASE_URL', '/sgipl-manage/');
}

// Upload directory for blog images (relative to website root)
// define('UPLOAD_DIR', $_SERVER['DOCUMENT_ROOT'] . '/images/blog/');
// define('UPLOAD_URL', '/images/blog/');

// Auto-detects localhost vs live server
if ($_SERVER['SERVER_NAME'] === 'localhost') {
    define('UPLOAD_DIR', $_SERVER['DOCUMENT_ROOT'] . '/supergifts/images/blog/');
} else {
    define('UPLOAD_DIR', $_SERVER['DOCUMENT_ROOT'] . '/images/blog/');
}

// Admin credentials (change these!)
define('ADMIN_USERNAME', 'sgipl_admin');
define('ADMIN_PASSWORD', '$2y$10$YourHashedPasswordHere'); 
// To generate a new hash, run this once in PHP:
// echo password_hash('your_password_here', PASSWORD_DEFAULT);
?>
