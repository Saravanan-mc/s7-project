<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Talent Post</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .navbar {
            background-color: #f0f0f0;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar a {
            margin: 0 15px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-top: 30px;
            margin-bottom: 25px;
        }
        form {
            width: 60%;
            max-width: 700px;
            margin: 0 auto;
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"],
        input[type="url"],
        textarea {
            width: calc(100% - 20px); /* Adjust for padding */
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
        }
        textarea {
            resize: vertical;
        }
        input[type="file"] {
            margin-bottom: 15px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="home.php">Home</a>
        <a href="post.php">Submit Talent</a>
        <a href="posts.php">View All Posts</a>
        <a href="qanda.php">Q&A Board</a>
    </div>

    <h2>Submit Your Talent</h2>

    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="roll_number">Roll Number:</label>
        <input type="text" id="roll_number" name="roll_number" required><br>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="5" required></textarea><br>

        <p style="margin-bottom: 10px;">Media (Upload image/video or provide an external link):</p>
        <label for="media_file">Upload File (Max 40MB):</label>
        <input type="file" id="media_file" name="media_file" accept="image/*,video/*" capture="camera"><br>

        <label for="media_link">Or provide an External Link (YouTube, Vimeo, etc.):</label>
        <input type="url" id="media_link" name="media_link" placeholder="e.g., https://www.youtube.com/watch?v=dQw4w9WgXcQ"><br>

        <input type="submit" value="Submit Talent">
    </form>
</body>
</html>
