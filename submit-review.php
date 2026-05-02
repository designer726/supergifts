<?php
require_once 'sgipl-manage/includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$client_name = trim($_POST['client_name'] ?? '');
$company_name = trim($_POST['company_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$rating = (int)($_POST['rating'] ?? 0);
$review_text = trim($_POST['review_text'] ?? '');

// Validation
$errors = [];

if (empty($client_name)) $errors[] = 'Client name is required';
if (strlen($client_name) > 100) $errors[] = 'Client name is too long';

if (empty($company_name)) $errors[] = 'Company name is required';
if (strlen($company_name) > 150) $errors[] = 'Company name is too long';

if (empty($email)) $errors[] = 'Email is required';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format';

if ($rating < 1 || $rating > 5) $errors[] = 'Rating must be between 1 and 5';

if (empty($review_text)) $errors[] = 'Review text is required';
if (strlen($review_text) < 10) $errors[] = 'Review must be at least 10 characters long';
if (strlen($review_text) > 5000) $errors[] = 'Review is too long';

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Insert into database
$stmt = $conn->prepare("
    INSERT INTO reviews (client_name, company_name, email, rating, review_text, status, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, 'pending', NOW(), NOW())
");

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit;
}

$stmt->bind_param('sssis', $client_name, $company_name, $email, $rating, $review_text);

if ($stmt->execute()) {
    // Optionally send email to admin
    // You can add email notification here
    
    echo json_encode(['success' => true, 'message' => 'Review submitted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error submitting review: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
