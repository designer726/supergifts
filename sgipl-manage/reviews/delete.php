<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    header("Location: index.php?msg=deleted");
} else {
    header("Location: index.php?error=delete_failed");
}
exit;
?>
