# Client Reviews & Ratings System - Setup Guide

## Overview
This is a complete client review and rating system for the SGIPL website. It allows clients to submit reviews with star ratings, and administrators to manage and approve them from the backend.

## Features

### Frontend Features:
✅ **Review Display Page** (`reviews.php`)
   - Shows all approved reviews
   - Displays overall rating statistics
   - Beautiful, responsive design
   - Review submission form with validation

✅ **Review Submission Form**
   - Client name, company name, email
   - 5-star rating system with hover effects
   - Review text area
   - Form validation (client-side and server-side)
   - Success/error notifications

✅ **Review Processing** (`submit-review.php`)
   - Server-side validation
   - Prevents spam and malformed submissions
   - Stores reviews in database with "pending" status

### Backend Features:
✅ **Reviews Management Dashboard** (`sgipl-manage/reviews/index.php`)
   - View all reviews (approved, pending, rejected)
   - Search and filter functionality
   - Display review statistics

✅ **Review Management** (`sgipl-manage/reviews/`)
   - **index.php** - List all reviews with stats
   - **view.php** - View full review details
   - **edit.php** - Approve, reject, or change status
   - **delete.php** - Delete reviews

## Installation Steps

### Step 1: Create Database Table
1. Open phpMyAdmin or your database management tool
2. Select your database: `superehc_sgipl`
3. Copy and paste the contents of `setup-reviews-db.sql`
4. Execute the SQL query

**Alternative:** Run this SQL directly:
```sql
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
```

### Step 2: Files Created
All files are already created. Here's the complete structure:

**Frontend:**
- `/reviews.php` - Main reviews page with display and form
- `/submit-review.php` - Handles review submission (AJAX)

**Backend (Admin Panel):**
- `/sgipl-manage/reviews/index.php` - Reviews dashboard
- `/sgipl-manage/reviews/view.php` - View review details
- `/sgipl-manage/reviews/edit.php` - Edit/approve reviews
- `/sgipl-manage/reviews/delete.php` - Delete reviews

**Database Setup:**
- `/setup-reviews-db.sql` - Database table creation script

## How to Use

### For Clients:

1. **Submit a Review:**
   - Navigate to `https://yoursite.com/reviews.php`
   - Fill in your details (name, company, email)
   - Select your rating (1-5 stars)
   - Write your review
   - Click "Submit Review"
   - Review will show: "Your review has been submitted and is pending approval"

2. **View Approved Reviews:**
   - Browse the reviews page
   - See overall rating based on all approved reviews
   - Read what other clients have said

### For Administrators:

1. **Access Reviews Dashboard:**
   - Log in to `/sgipl-manage/`
   - Click on **Reviews** in the navigation

2. **Review Management:**
   - View all pending reviews (need approval)
   - Approve reviews to display them on the website
   - Reject inappropriate reviews
   - Delete reviews as needed

3. **Monitor Statistics:**
   - See count of Approved, Pending, and Rejected reviews
   - Total reviews count
   - Average rating displayed on public page

## Customization

### Change Review Form Fields:
Edit `reviews.php` - modify the form fields in the "Sidebar with Form" section

### Change Star Colors:
Search for `#ffc107` in `reviews.php` and replace with your desired color

### Change Approval Workflow:
By default, reviews need admin approval. To auto-approve:
- In `submit-review.php`, change `'pending'` to `'approved'` in the INSERT statement

### Email Notifications:
Add email notification to admins when new reviews are submitted:
- In `submit-review.php`, after successful insert, add:
```php
// Send email to admin
// Include your PHPMailer code here
```

## Navigation Update

To add a "Reviews" link to your website navigation, edit `common/nav.php`:
```html
<li><a href="reviews">Reviews</a></li>
```

Or in the footer, edit `common/footer.php`:
```html
<li><a href="reviews">Reviews & Ratings</a></li>
```

## Important Security Notes

✅ All inputs are:
   - Trimmed and validated
   - Escaped to prevent XSS attacks
   - Protected with prepared statements (prevents SQL injection)
   - Limited in length

✅ Only "Approved" reviews show on the public page
✅ Requires authentication for admin panel

## Troubleshooting

**Problem:** Reviews not submitting
- Check browser console for JavaScript errors
- Verify database connection in `sgipl-manage/includes/db.php`
- Check file permissions on `submit-review.php`

**Problem:** Reviews table not found
- Run the SQL setup script again
- Verify you're using the correct database

**Problem:** Admin panel access denied
- Verify you're logged in (check `sgipl-manage/includes/auth.php`)
- Check login credentials

## Support

For questions or issues, check:
1. Database connection settings in `sgipl-manage/includes/db.php`
2. File permissions on all PHP files
3. Browser console for JavaScript errors
4. PHP error logs

---

**System Created:** 2026
**Version:** 1.0
**Database:** MySQL/MariaDB
**Platform:** PHP 7.0+
