<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'superehc_aiir');
define('DB_PASS', 'Aiir@8097000970');
define('DB_NAME', 'superehc_sgipl');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Database Setup & Sample Data Insertion</h2>";

// Create table
$create_table_sql = "CREATE TABLE IF NOT EXISTS reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_name VARCHAR(100) NOT NULL,
    company_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_text LONGTEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_created_at (created_at),
    INDEX idx_rating (rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($create_table_sql) === TRUE) {
    echo "<p style='color: green;'><strong>✓ Reviews table created successfully</strong></p>";
} else {
    echo "<p style='color: red;'><strong>✗ Error creating table:</strong> " . $conn->error . "</p>";
}

// Check if reviews already exist
$check = $conn->query("SELECT COUNT(*) as cnt FROM reviews");
$count = $check->fetch_assoc()['cnt'];

if ($count > 0) {
    echo "<p style='color: orange;'><strong>⚠ Database already has " . $count . " reviews. Skipping sample data insertion.</strong></p>";
} else {
    // Insert sample data
    $sample_data = array(
        array(
            'client_name' => 'John Doe',
            'company_name' => 'Tech Solutions Inc',
            'email' => 'john@techsolutions.com',
            'rating' => 5,
            'review_text' => 'Excellent service and quality products. SGIPL provided us with high-quality corporate gifts that impressed our clients. Their attention to detail and timely delivery was outstanding!'
        ),
        array(
            'client_name' => 'Jane Smith',
            'company_name' => 'Digital Marketing Pro',
            'email' => 'jane@dmgpro.com',
            'rating' => 4,
            'review_text' => 'Great experience working with SGIPL. They understood our requirements well and delivered customized gifts within the timeline. Highly recommend their services.'
        ),
        array(
            'client_name' => 'Mike Johnson',
            'company_name' => 'Global Trade Ltd',
            'email' => 'mike@globaltrade.com',
            'rating' => 5,
            'review_text' => 'Outstanding quality and exceptional customer service. SGIPL has been our trusted partner for corporate gifting for the past 2 years. Keep up the great work!'
        ),
        array(
            'client_name' => 'Sarah Williams',
            'company_name' => 'Innovation Enterprises',
            'email' => 'sarah@innovation.com',
            'rating' => 4,
            'review_text' => 'Very impressed with the quality of gifts and the professionalism of the team. The customization options were perfect for our corporate event. Will definitely recommend!'
        ),
        array(
            'client_name' => 'David Brown',
            'company_name' => 'Premier Consulting',
            'email' => 'david@premier.com',
            'rating' => 5,
            'review_text' => 'Best corporate gifting partner we have worked with! The products are premium, delivery was on time, and customer support was fantastic. Highly satisfied with SGIPL!'
        ),
        array(
            'client_name' => 'Emily Davis',
            'company_name' => 'Creative Agency Co',
            'email' => 'emily@creative.com',
            'rating' => 5,
            'review_text' => 'Amazing experience from start to finish. The team at SGIPL went above and beyond to make our corporate gifts special. Worth every penny!'
        ),
        array(
            'client_name' => 'Robert Wilson',
            'company_name' => 'Financial Services Group',
            'email' => 'robert@financial.com',
            'rating' => 4,
            'review_text' => 'Reliable and professional service. The quality of corporate gifts exceeded our expectations. Great communication throughout the process.'
        ),
        array(
            'client_name' => 'Lisa Anderson',
            'company_name' => 'Healthcare Solutions',
            'email' => 'lisa@healthcare.com',
            'rating' => 5,
            'review_text' => 'Outstanding! SGIPL delivered exactly what we needed. The attention to detail in the gifting process was remarkable. Highly recommended!'
        )
    );

    $inserted = 0;
    $failed = 0;

    foreach ($sample_data as $data) {
        $stmt = $conn->prepare("INSERT INTO reviews (client_name, company_name, email, rating, review_text, status) VALUES (?, ?, ?, ?, ?, 'approved')");
        
        if ($stmt) {
            $stmt->bind_param('sssds', 
                $data['client_name'],
                $data['company_name'],
                $data['email'],
                $data['rating'],
                $data['review_text']
            );
            
            if ($stmt->execute()) {
                $inserted++;
            } else {
                $failed++;
                echo "<p style='color: red;'>Error inserting: " . htmlspecialchars($data['client_name']) . "</p>";
            }
            $stmt->close();
        }
    }

    echo "<p style='color: green;'><strong>✓ Successfully inserted " . $inserted . " sample reviews</strong></p>";
    if ($failed > 0) {
        echo "<p style='color: red;'><strong>✗ Failed to insert " . $failed . " reviews</strong></p>";
    }
}

// Display summary
echo "<hr>";
echo "<h3>Database Summary</h3>";

$result = $conn->query("SELECT COUNT(*) as total, 
                               SUM(CASE WHEN status='approved' THEN 1 ELSE 0 END) as approved,
                               SUM(CASE WHEN status='pending' THEN 1 ELSE 0 END) as pending,
                               SUM(CASE WHEN status='rejected' THEN 1 ELSE 0 END) as rejected,
                               AVG(rating) as avg_rating
                        FROM reviews");

if ($result) {
    $summary = $result->fetch_assoc();
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; margin: 20px 0;'>";
    echo "<tr><td><strong>Total Reviews:</strong></td><td>" . ($summary['total'] ?? 0) . "</td></tr>";
    echo "<tr><td><strong>Approved:</strong></td><td>" . ($summary['approved'] ?? 0) . "</td></tr>";
    echo "<tr><td><strong>Pending:</strong></td><td>" . ($summary['pending'] ?? 0) . "</td></tr>";
    echo "<tr><td><strong>Rejected:</strong></td><td>" . ($summary['rejected'] ?? 0) . "</td></tr>";
    echo "<tr><td><strong>Average Rating:</strong></td><td>" . number_format($summary['avg_rating'] ?? 0, 1) . " / 5</td></tr>";
    echo "</table>";
}

echo "<hr>";
echo "<h3 style='color: green;'>✓ Setup Complete!</h3>";
echo "<p>You can now visit: <a href='reviews.php' target='_blank'><strong>http://localhost/supergifts/reviews.php</strong></a></p>";
echo "<p>Admin panel: <a href='sgipl-manage/reviews/' target='_blank'><strong>http://localhost/supergifts/sgipl-manage/reviews/</strong></a></p>";

$conn->close();
?>
