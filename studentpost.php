<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Post</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: Arial, sans-serif; margin: 0; background-color: #f9f9f9;">

<!-- Navbar -->
<div style="background-color: #e0e0e0; padding: 10px 20px; border-bottom: 1px solid #ccc;">
    <a href="studentpost.php" style="margin-right: 15px;">Post</a>
    <a href="studentshow.php">Show</a>
</div>

<!-- Form Section -->
<div style="max-width: 600px; margin: 40px auto; background-color: #fff; padding: 20px; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
    <h2>Submit a Student Post</h2>

    <form action="#" method="POST" enctype="multipart/form-data">
        <label for="name">Your Name:</label><br>
        <input type="text" id="name" name="name" required style="width: 100%; padding: 8px; margin-bottom: 10px;"><br>

        <label for="roll">Roll Number:</label><br>
        <input type="text" id="roll" name="roll" required style="width: 100%; padding: 8px; margin-bottom: 10px;"><br>

        <label for="title">Post Title:</label><br>
        <input type="text" id="title" name="title" required style="width: 100%; padding: 8px; margin-bottom: 10px;"><br>

        <label for="content">Content:</label><br>
        <textarea id="content" name="content" rows="4" required style="width: 100%; padding: 8px; margin-bottom: 10px;"></textarea><br>

        <label for="image">Optional Image:</label><br>
        <input type="file" id="image" name="image" accept="image/*" style="margin-bottom: 15px;"><br>

        <button type="submit" style="padding: 10px 20px; background-color: #4CAF50; color: #fff; border: none; border-radius: 4px;">Post</button>
    </form>

    <br>
    <a href="studentshow.php">View All Posts</a> |
    <a href="index.php">Back to Home</a>
</div>

</body>
</html>
