<?php
// define('BASE_URL', '../../');
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

$id = intval($_GET['id'] ?? 0);
if ($id) {
    // Get image path before deleting
    $stmt = $conn->prepare("SELECT image FROM blogs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($row) {
        // Delete from DB
        $del = $conn->prepare("DELETE FROM blogs WHERE id = ?");
        $del->bind_param("i", $id);
        $del->execute();
        $del->close();

        // Delete image file if it was uploaded via admin
        if (!empty($row['image']) && strpos($row['image'], 'blog-') !== false) {
            $path = $_SERVER['DOCUMENT_ROOT'] . '/' . $row['image'];
            if (file_exists($path)) @unlink($path);
        }
    }
}

header("Location: index.php?deleted=1");
exit();
?>
