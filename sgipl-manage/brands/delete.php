<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../login.php"); exit(); }
require_once '../includes/db.php';

$id = intval($_GET['id'] ?? 0);
if ($id) {
    $stmt = $conn->prepare("SELECT imageno FROM brandlogo WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($row) {
        // Delete product images
        $prods = $conn->query("SELECT image FROM products WHERE brand_id = $id");
        while ($p = $prods->fetch_assoc()) {
            if ($p['image']) {
                $path = ($_SERVER['SERVER_NAME']==='localhost')
                    ? $_SERVER['DOCUMENT_ROOT'].'/supergifts/'.$p['image']
                    : $_SERVER['DOCUMENT_ROOT'].'/'.$p['image'];
                if (file_exists($path)) @unlink($path);
            }
        }
        // Delete brand (CASCADE deletes products from DB)
        $del = $conn->prepare("DELETE FROM brandlogo WHERE id = ?");
        $del->bind_param("i", $id);
        $del->execute();
        $del->close();
    }
}
header("Location: index.php?deleted=1");
exit();
?>
