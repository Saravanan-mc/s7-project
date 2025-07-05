<?php
// Ensure the data and images directories exist
if (!is_dir('data')) {
    mkdir('data');
}
if (!is_dir('images')) {
    mkdir('images', 0777, true);
}

// Function to read posts from JSON file
function getPosts($postsFile) {
    if (!file_exists($postsFile)) {
        return [];
    }
    $postsJson = file_get_contents($postsFile);
    return json_decode($postsJson, true) ?: [];
}

// Function to save posts to JSON file
function savePosts($postsFile, $posts) {
    file_put_contents($postsFile, json_encode($posts, JSON_PRETTY_PRINT));
}

$postsFile = 'data/posts.json';

// Handle Post Creation (form submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentName = $_POST['studentName'] ?? '';
    $rollNumber = $_POST['rollNumber'] ?? '';
    $email = $_POST['email'] ?? '';
    $doubtText = $_POST['doubtText'] ?? '';
    $imagePath = null;

    // Handle image upload
    if (isset($_FILES['doubtImage']) && $_FILES['doubtImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'images/';
        $fileName = uniqid('img_') . '_' . basename($_FILES['doubtImage']['name']);
        $targetFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['doubtImage']['tmp_name'], $targetFilePath)) {
            $imagePath = $targetFilePath;
        } else {
            header('Location: create_post.php?upload_error=1');
            exit;
        }
    }

    $posts = getPosts($postsFile);

    $newPost = [
        'id' => 'post_' . time(),
        'studentName' => htmlspecialchars($studentName),
        'rollNumber' => htmlspecialchars($rollNumber),
        'email' => htmlspecialchars($email),
        'doubtText' => htmlspecialchars($doubtText),
        'image' => $imagePath,
        'likes' => [],
        'comments' => []
    ];

    $posts[] = $newPost;
    savePosts($postsFile, $posts);

    header('Location: view_feed.php?success=1');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Doubt Forum - Post New Doubt</title>
    <style>
        body { font-family: sans-serif; margin: 20px; background-color: #f4f7f6; color: #333; }
        .container { max-width: 700px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #007bff; margin-bottom: 30px; }

        .navigation {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
        }
        .navigation a {
            text-decoration: none;
            color: #007bff;
            padding: 8px 15px;
            border: 1px solid #007bff;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-size: 0.9em;
        }
        .navigation a:hover {
            background-color: #007bff;
            color: white;
        }

        .main-content-area {
            margin-top: 80px;
        }

        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input[type="text"], .form-group input[type="email"], .form-group textarea {
            width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 1rem;
        }
        .form-group textarea { resize: vertical; min-height: 100px; }
        .form-group input[type="file"] { padding: 5px 0; }
        .btn-submit {
            background-color: #28a745; color: white;
            padding: 10px 20px; border: none; border-radius: 5px;
            cursor: pointer; font-size: 1rem; width: 100%;
        }
        .btn-submit:hover { background-color: #218838; }
        .success-message { color: green; text-align: center; margin-bottom: 15px; font-weight: bold; }
        .error-message { color: red; text-align: center; margin-bottom: 15px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="navigation">
        <a href="home.php">Welcome</a>
        <a href="create_post.php">Post Doubt</a>
        <a href="view_feed.php">Doubt Feed</a>
    </div>

    <div class="container main-content-area">
        <h1>Post a New Doubt</h1>

        <?php if (isset($_GET['upload_error'])): ?>
            <p class="error-message">There was an error uploading the image. Please try again.</p>
        <?php endif; ?>

        <form action="create_post.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="studentName">Your Name</label>
                <input type="text" id="studentName" name="studentName" required>
            </div>
            <div class="form-group">
                <label for="rollNumber">Roll Number</label>
                <input type="text" id="rollNumber" name="rollNumber" required>
            </div>
            <div class="form-group">
                <label for="email">College Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="doubtText">Doubt / Query</label>
                <textarea id="doubtText" name="doubtText" required></textarea>
            </div>
            <div class="form-group">
                <label for="doubtImage">Optional Image (Screenshot, etc.)</label>
                <input type="file" id="doubtImage" name="doubtImage" accept="image/*">
            </div>
            <button type="submit" class="btn-submit">Submit Doubt</button>
        </form>
    </div>
</body>
</html>
