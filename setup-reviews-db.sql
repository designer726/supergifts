-- Create Reviews Table for SGIPL Website

CREATE TABLE IF NOT EXISTS reviews (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional: Insert sample data for testing
-- INSERT INTO reviews (client_name, company_name, email, rating, review_text, status) VALUES
-- ('John Doe', 'Tech Solutions Inc', 'john@techsolutions.com', 5, 'Excellent service and quality products. SGIPL provided us with high-quality corporate gifts that impressed our clients. Their attention to detail and timely delivery was outstanding!', 'approved'),
-- ('Jane Smith', 'Digital Marketing Pro', 'jane@dmgpro.com', 4, 'Great experience working with SGIPL. They understood our requirements well and delivered customized gifts within the timeline. Highly recommend their services.', 'approved'),
-- ('Mike Johnson', 'Global Trade Ltd', 'mike@globaltrade.com', 5, 'Outstanding quality and exceptional customer service. SGIPL has been our trusted partner for corporate gifting for the past 2 years. Keep up the great work!', 'approved');
