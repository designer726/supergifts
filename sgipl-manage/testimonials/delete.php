<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

$isLocal = ($_SERVER['SERVER_NAME'] === 'localhost');

$id = intval($_GET['id'] ?? 0);
if ($id) {
    $stmt = $conn->prepare("SELECT avatar, image FROM client_testimonials WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($row) {
        $del = $conn->prepare("DELETE FROM client_testimonials WHERE id = ?");
        $del->bind_param("i", $id);
        $del->execute();
        $del->close();

        foreach ([$row['avatar'] ?? '', $row['image'] ?? ''] as $file) {
            if (!empty($file) && strpos($file, 'testimonial-') !== false) {
                $path = $isLocal
                    ? $_SERVER['DOCUMENT_ROOT'] . '/supergifts/' . $file
                    : $_SERVER['DOCUMENT_ROOT'] . '/' . $file;
                if (file_exists($path)) @unlink($path);
            }
        }
    }
}

header("Location: index.php?deleted=1");
exit();
?>
