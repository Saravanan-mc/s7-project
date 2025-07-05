<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Student Posts</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: Arial, sans-serif; margin: 0; background-color: #f4f4f4;">

<!-- Navbar -->
<div style="background-color: #e0e0e0; padding: 10px 20px; border-bottom: 1px solid #ccc;">
    <a href="studentpost.php" style="margin-right: 15px;">Post</a>
    <a href="studentshow.php">Show</a>
</div>

<!-- Posts Section -->
<div style="max-width: 800px; margin: 40px auto; padding: 20px; background-color: #fff; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    <h2>All Student Posts</h2>

    <!-- Example Post -->
    <div style="border-bottom: 1px solid #ccc; margin-bottom: 20px; padding-bottom: 10px;">
        <h3>Clean Campus Drive</h3>
        <p><strong>Name:</strong> John Doe | <strong>Roll:</strong> 21CS123</p>
        <p>We are organizing a campus cleaning drive this weekend. Join us at 9 AM near the auditorium.</p>
        <img src="sample.jpg" alt="Post Image" style="max-width:100%; height:auto; margin-top:10px;">
    </div>

    <!-- Another Example Post -->
    <div style="border-bottom: 1px solid #ccc; margin-bottom: 20px; padding-bottom: 10px;">
        <h3>Python Workshop</h3>
        <p><strong>Name:</strong> Jane Smith | <strong>Roll:</strong> 21CS456</p>
        <p>A free Python workshop will be conducted on Friday. Certificates will be provided!</p>
    </div>

    <a href="studentpost.php">Submit a Post</a> |
    <a href="index.php">Back to Home</a>
</div>

</body>
</html>
