<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../login.php"); exit(); }
require_once '../includes/db.php';

$id = intval($_GET['id'] ?? 0);
if ($id) {
    $stmt = $conn->prepare("SELECT video, thumbnail FROM packaging_videos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($row) {
        foreach (['video', 'thumbnail'] as $field) {
            if ($row[$field]) {
                $path = ($_SERVER['SERVER_NAME']==='localhost')
                    ? $_SERVER['DOCUMENT_ROOT'].'/supergifts/'.$row[$field]
                    : $_SERVER['DOCUMENT_ROOT'].'/'.$row[$field];
                if (file_exists($path)) @unlink($path);
            }
        }
        $del = $conn->prepare("DELETE FROM packaging_videos WHERE id = ?");
        $del->bind_param("i", $id);
        $del->execute();
        $del->close();
    }
}
header("Location: index.php?deleted=1");
exit();
?>
