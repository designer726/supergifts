<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../login.php"); exit(); }
require_once '../includes/db.php';
$pageTitle = 'Add Product Selection';
$errors = []; $success = ''; $created = 0; $updated = 0; $skipped = [];

// Download sample CSV
if (isset($_GET['download_sample'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="product_selection_template.csv"');
    echo "Brand,Product Name,MRP,Offer Price,Quantity,Category,Display Order\n";
    echo "Samsung,Samsung Bluetooth Speaker,1999.00,1499.00,10,Premium,1\n";
    echo "Prestige,Prestige Induction Cooktop,2499.00,1999.00,15,Executive,2\n";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_FILES['csv_file']['name'])) {
        $errors[] = "Please upload a CSV file.";
    } else {
        $ext = strtolower(pathinfo($_FILES['csv_file']['name'], PATHINFO_EXTENSION));
        if ($ext !== 'csv') {
            $errors[] = "Only .csv files allowed.";
        } else {
            $handle = fopen($_FILES['csv_file']['tmp_name'], 'r');
            fgetcsv($handle); // skip header
            $rowNum = 1;
            while (($row = fgetcsv($handle)) !== false) {
                $rowNum++;
                if (empty($row[0]) && empty($row[1])) continue;

                $brandName   = trim($row[0] ?? '');
                $name        = trim($row[1] ?? '');
                $mrp         = floatval($row[2] ?? 0);
                $offer_price = floatval($row[3] ?? 0);
                $quantity    = intval($row[4] ?? 0);
                $category    = trim($row[5] ?? 'NA');
                if (!in_array($category, ['Premium', 'Executive', 'Economy', 'NA'], true)) $category = 'NA';
                $is_premium  = $category === 'Premium' ? 1 : 0;
                $sequence    = intval($row[6] ?? 0);

                if (!$brandName) { $skipped[] = "Row $rowNum: Brand is required."; continue; }
                if (!$name)      { $skipped[] = "Row $rowNum: Product Name is required."; continue; }

                $bstmt = $conn->prepare("SELECT id FROM brandlogo WHERE brandname LIKE ? LIMIT 1");
                $bstmt->bind_param("s", $brandName);
                $bstmt->execute();
                $brandRow = $bstmt->get_result()->fetch_assoc();
                $bstmt->close();
                if (!$brandRow) { $skipped[] = "Row $rowNum: Brand \"$brandName\" not found — add it under Brand Partners first."; continue; }
                $brand_id = $brandRow['id'];

                $pstmt = $conn->prepare("SELECT id FROM products WHERE brand_id = ? AND name LIKE ? LIMIT 1");
                $pstmt->bind_param("is", $brand_id, $name);
                $pstmt->execute();
                $existing = $pstmt->get_result()->fetch_assoc();
                $pstmt->close();

                if ($existing) {
                    $ustmt = $conn->prepare("UPDATE products SET mrp=?, offer_price=?, quantity=?, category=?, is_premium=?, sequence=?, is_selection=1, status=1 WHERE id=?");
                    $ustmt->bind_param("ddisiii", $mrp, $offer_price, $quantity, $category, $is_premium, $sequence, $existing['id']);
                    if ($ustmt->execute()) $updated++;
                    $ustmt->close();
                } else {
                    $istmt = $conn->prepare("INSERT INTO products (brand_id, name, mrp, offer_price, quantity, category, is_premium, sequence, is_selection, status) VALUES (?,?,?,?,?,?,?,?,1,1)");
                    $istmt->bind_param("isddisii", $brand_id, $name, $mrp, $offer_price, $quantity, $category, $is_premium, $sequence);
                    if ($istmt->execute()) $created++;
                    $istmt->close();
                }
            }
            fclose($handle);

            if ($created || $updated) {
                $success = "$created product(s) added and $updated existing product(s) updated — all flagged ⭐ Show in Product Selection.";
            } elseif (!$errors) {
                $errors[] = "No valid rows found in CSV.";
            }
        }
    }
}

require_once '../includes/layout_top.php';
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="index.php" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="mb-0 fw-bold">Add Product Selection (Bulk Excel/CSV)</h5>
</div>

<div class="alert alert-info d-flex gap-3 align-items-start mb-4">
    <i class="bi bi-info-circle-fill fs-5 mt-1"></i>
    <div>
        <strong>What this does:</strong> uploads products spanning any brand and marks each one ⭐ <strong>Show in Product Selection</strong>, so they appear in the homepage "Product Selection" carousel. If a product with the same name already exists under that brand, it's updated instead of duplicated.
        <ol class="mb-1 mt-2">
            <li>Download the sample CSV template</li>
            <li>Open in Excel or Google Sheets</li>
            <li>Fill: <strong>Brand</strong> (must already exist under Brand Partners), <strong>Product Name</strong>, <strong>MRP</strong>, <strong>Offer Price</strong>, <strong>Quantity</strong>, <strong>Category</strong> (Premium, Executive, Economy or NA), <strong>Display Order</strong></li>
            <li>Save as CSV and upload here</li>
            <li>After upload, edit each product to add its image</li>
        </ol>
        <a href="?download_sample=1" class="btn btn-sm btn-outline-primary mt-1">
            <i class="bi bi-download me-1"></i>Download Sample CSV
        </a>
    </div>
</div>

<?php if ($errors): ?>
    <div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i><?= $success ?> <a href="index.php?selection=1">View Product Selection →</a></div>
<?php endif; ?>
<?php if ($skipped): ?>
    <div class="alert alert-warning"><strong>Skipped rows:</strong><ul class="mb-0"><?php foreach($skipped as $s): ?><li><?= htmlspecialchars($s) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="form-card" style="max-width:600px;">
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-4">
            <label class="form-label fw-semibold">Upload CSV File <span class="text-danger">*</span></label>
            <input type="file" name="csv_file" class="form-control" accept=".csv" required>
            <div class="text-muted small mt-1">Only .csv files.</div>
        </div>
        <button type="submit" class="btn btn-gold px-5"><i class="bi bi-upload me-2"></i>Upload & Add to Selection</button>
    </form>
</div>

<!-- Format reference -->
<div class="form-card mt-4" style="max-width:700px;">
    <h6 class="fw-bold mb-3">📋 CSV Format</h6>
    <table class="table table-sm table-bordered mb-0" style="font-size:13px;">
        <thead class="table-light">
            <tr>
                <th>A — Brand</th>
                <th>B — Product Name</th>
                <th>C — MRP</th>
                <th>D — Offer Price</th>
                <th>E — Quantity</th>
                <th>F — Category</th>
                <th>G — Display Order</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Samsung</td><td>Samsung Bluetooth Speaker</td><td>1999.00</td><td>1499.00</td><td>10</td><td>Premium</td><td>1</td></tr>
            <tr><td>Prestige</td><td>Prestige Induction Cooktop</td><td>2499.00</td><td>1999.00</td><td>15</td><td>Executive</td><td>2</td></tr>
        </tbody>
    </table>
    <div class="text-muted small mt-2">⚠️ Do not change column headers. Brand must exactly match an existing Brand Partner name. Images are added manually after upload.</div>
</div>

<?php require_once '../includes/layout_bottom.php'; ?>
