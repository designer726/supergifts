<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'superehc_aiir');       // ← Change this
define('DB_PASS', 'Aiir@8097000970');   // ← Change this
define('DB_NAME', 'superehc_sgipl');       // ← Change this

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
