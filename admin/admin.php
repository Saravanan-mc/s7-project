<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
</head>
<body>
    <!-- Top Navigation Bar -->
    <div style="background-color: #f0f0f0; padding: 10px; text-align: center;">
        <a href="admin.php" style="margin: 0 10px;">Admin Panel</a>
        <a href="book.php" style="margin: 0 10px;">Book Pages</a>
        <a href="post_read.php" style="margin: 0 10px;">Post Read</a>
        <a href="wellness.php" style="margin: 0 10px;">Wellness</a>
        <a href="club_event.php" style="margin: 0 10px;">Club Events</a>
        <a href="lost_and_find.php" style="margin: 0 10px;">Lost & Found</a>
    </div>

    <!-- Main Content Area -->
    <div style="padding: 20px;">
        <h1>Welcome to Our Website!</h1>
        <p>This is the main home page. You can navigate to different sections of the site using the links above.</p>

        <h2>Current Date and Time:</h2>
        <p><?php echo date("l, F j, Y, h:i:s A"); ?></p>

        <h2>About Us</h2>
        <p>We are dedicated to providing useful information and resources across various topics, including administration, literature, well-being, community events, and helpful services like lost and found.</p>

        <h2>Explore Our Sections:</h2>
        <ul>
            <li><strong>Admin Panel:</strong> Manage users, settings, and view reports.</li>
            <li><strong>Book Pages:</strong> Read through various chapters of our online book.</li>
            <li><strong>Post Read Page:</strong> Dive deep into insightful articles and posts.</li>
            <li><strong>Wellness Page:</strong> Discover tips and resources for mental and physical health.</li>
            <li><strong>Club Events:</strong> Stay updated on upcoming club activities and gatherings.</li>
            <li><strong>Lost & Found:</strong> Report or search for lost and found items.</li>
        </ul>

        <h2>Contact Information</h2>
        <p>If you have any questions, feel free to reach out to us at info@example.com.</p>
    </div>
</body>
</html>
