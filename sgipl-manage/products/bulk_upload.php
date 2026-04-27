<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../login.php"); exit(); }
require_once '../includes/db.php';
$pageTitle = 'Bulk Upload Products';
$errors = []; $success = ''; $imported = 0;

$prefill_brand = intval($_GET['brand_id'] ?? 0);
$allBrands = $conn->query("SELECT id, brandname FROM brandlogo ORDER BY brandname ASC");

// Download sample CSV
if (isset($_GET['download_sample'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="products_template.csv"');
    echo "Product Name,MRP,Display Order\n";
    echo "Sample Product Name,999.00,1\n";
    echo "Another Product,1499.00,2\n";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand_id = intval($_POST['brand_id'] ?? 0);
    if (!$brand_id) {
        $errors[] = "Please select a brand.";
    } elseif (empty($_FILES['csv_file']['name'])) {
        $errors[] = "Please upload a CSV file.";
    } else {
        $ext = strtolower(pathinfo($_FILES['csv_file']['name'], PATHINFO_EXTENSION));
        if ($ext !== 'csv') {
            $errors[] = "Only .csv files allowed.";
        } else {
            $handle = fopen($_FILES['csv_file']['tmp_name'], 'r');
            fgetcsv($handle); // skip header
            $seq = 1;
            while (($row = fgetcsv($handle)) !== false) {
                if (empty($row[0])) continue;
                $name     = trim($row[0]);
                $mrp      = floatval($row[1] ?? 0);
                $sequence = intval($row[2] ?? $seq);
                if (!$name) continue;
                $stmt = $conn->prepare("INSERT INTO products (brand_id, name, mrp, sequence, status) VALUES (?,?,?,?,1)");
                $stmt->bind_param("isdi", $brand_id, $name, $mrp, $sequence);
                if ($stmt->execute()) $imported++;
                $stmt->close();
                $seq++;
            }
            fclose($handle);
            if ($imported > 0) {
                $success = "$imported products imported! <a href='index.php?brand_id=$brand_id'>View products →</a> (Add images by editing each product)";
            } else {
                $errors[] = "No valid rows found in CSV.";
            }
        }
    }
}

require_once '../includes/layout_top.php';
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="index.php<?= $prefill_brand?'?brand_id='.$prefill_brand:'' ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="mb-0 fw-bold">Bulk Upload Products via CSV</h5>
</div>

<div class="alert alert-info d-flex gap-3 align-items-start mb-4">
    <i class="bi bi-info-circle-fill fs-5 mt-1"></i>
    <div>
        <strong>How to bulk upload:</strong>
        <ol class="mb-1 mt-1">
            <li>Download the sample CSV template</li>
            <li>Open in Excel or Google Sheets</li>
            <li>Fill: <strong>Product Name</strong>, <strong>MRP</strong>, <strong>Display Order</strong></li>
            <li>Save as CSV and upload here</li>
            <li>After upload, edit each product to add images</li>
        </ol>
        <a href="?download_sample=1<?= $prefill_brand?'&brand_id='.$prefill_brand:'' ?>"
           class="btn btn-sm btn-outline-primary mt-1">
            <i class="bi bi-download me-1"></i>Download Sample CSV
        </a>
    </div>
</div>

<?php if ($errors): ?>
    <div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i><?= $success ?></div>
<?php endif; ?>

<div class="form-card" style="max-width:600px;">
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label fw-semibold">Select Brand <span class="text-danger">*</span></label>
            <select name="brand_id" class="form-select" required>
                <option value="">— Choose Brand —</option>
                <?php while ($b = $allBrands->fetch_assoc()): ?>
                    <option value="<?= $b['id'] ?>" <?= $prefill_brand==$b['id']?'selected':'' ?>>
                        <?= htmlspecialchars(ucfirst($b['brandname'])) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold">Upload CSV File <span class="text-danger">*</span></label>
            <input type="file" name="csv_file" class="form-control" accept=".csv" required>
            <div class="text-muted small mt-1">Only .csv files.</div>
        </div>
        <button type="submit" class="btn btn-gold px-5"><i class="bi bi-upload me-2"></i>Upload & Import</button>
    </form>
</div>

<!-- Format reference -->
<div class="form-card mt-4" style="max-width:600px;">
    <h6 class="fw-bold mb-3">📋 CSV Format</h6>
    <table class="table table-sm table-bordered mb-0" style="font-size:13px;">
        <thead class="table-light">
            <tr><th>Column A — Product Name</th><th>Column B — MRP</th><th>Column C — Order</th></tr>
        </thead>
        <tbody>
            <tr><td>Blaupunkt BT Speaker</td><td>999.00</td><td>1</td></tr>
            <tr><td>Blaupunkt Headphones</td><td>1499.00</td><td>2</td></tr>
        </tbody>
    </table>
    <div class="text-muted small mt-2">⚠️ Do not change column headers. Images added manually after upload.</div>
</div>

<?php require_once '../includes/layout_bottom.php'; ?>
