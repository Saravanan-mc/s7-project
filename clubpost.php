<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $clubName = trim(htmlspecialchars($_POST['clubName']));
    $title = trim(htmlspecialchars($_POST['title']));
    $content = trim(htmlspecialchars($_POST['content']));

    // Handle file upload if exists
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'club_uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $tmpName = $_FILES['image']['tmp_name'];
        $fileName = basename($_FILES['image']['name']);
        $filePath = $uploadDir . time() . '_' . preg_replace("/[^a-zA-Z0-9._-]/", "_", $fileName);

        if (move_uploaded_file($tmpName, $filePath)) {
            $imagePath = $filePath;
        }
    }

    // Prepare post data
    $post = [
        'clubName' => $clubName,
        'title' => $title,
        'content' => $content,
        'image' => $imagePath,
        'timestamp' => time()
    ];

    // Save post in JSON file
    $file = 'club_posts.json';
    $posts = [];
    if (file_exists($file)) {
        $json = file_get_contents($file);
        $posts = json_decode($json, true) ?? [];
    }
    array_unshift($posts, $post); // add new post at beginning

    file_put_contents($file, json_encode($posts, JSON_PRETTY_PRINT));

    // Redirect to show page
    header('Location: clubshow.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Submit Club Post</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    body { font-family: Arial, sans-serif; background: #f9f9f9; margin:0; padding:20px; }
    .container { max-width: 600px; background:#fff; padding:20px; margin:auto; border-radius:6px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
    label { display: block; margin-top: 10px; font-weight: bold; }
    input[type="text"], textarea, input[type="file"] {
      width: 100%; padding: 8px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc;
    }
    button {
      margin-top: 15px; padding: 10px 20px; background-color: #4CAF50; border:none; border-radius: 4px;
      color: white; font-size: 16px; cursor: pointer;
    }
    button:hover {
      background-color: #45a049;
    }
    nav a {
      margin-right: 15px;
      text-decoration: none;
      color: #007BFF;
      font-weight: bold;
    }
    nav a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <nav>
    <a href="club.php">Club Home</a>
    <a href="clubshow.php">View Posts</a>
  </nav>
  <div class="container">
    <h2>Submit a Club Post</h2>
    <form action="clubpost.php" method="POST" enctype="multipart/form-data">
      <label for="clubName">Club Name:</label>
      <input type="text" id="clubName" name="clubName" required />

      <label for="title">Post Title:</label>
      <input type="text" id="title" name="title" required />

      <label for="content">Content:</label>
      <textarea id="content" name="content" rows="5" required></textarea>

      <label for="image">Optional Image:</label>
      <input type="file" id="image" name="image" accept="image/*" />

      <button type="submit">Submit Post</button>
    </form>
  </div>
</body>
</html>
