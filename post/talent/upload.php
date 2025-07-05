<?php
// Define the path for storing media files and JSON data
$upload_dir = 'talantfile/';
$posts_json_file = 'posts.json';

// Ensure the upload directory exists and is writable
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Function to redirect with a message (now includes basic HTML structure for alert)
function redirect_with_message($url, $message_type, $message) {
    echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>Redirecting...</title>";
    echo "<style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .message-box { background-color: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center; }
        .message-box p { font-size: 1.1em; color: #333; }
        .message-box.success { border: 1px solid #4CAF50; }
        .message-box.error { border: 1px solid #f44336; }
    </style>";
    echo "</head><body><div class='message-box " . htmlspecialchars($message_type) . "'><p>" . htmlspecialchars($message) . "</p></div>";
    echo "<script>
        // Use a slight delay to allow the message to be seen before redirecting
        setTimeout(function() {
            window.location.href='" . addslashes($url) . "';
        }, 1500); // Redirect after 1.5 seconds
    </script>";
    echo "</body></html>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roll_number = htmlspecialchars($_POST['roll_number']);
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $media_type = 'none';
    $media_path = '';

    // Handle file upload
    if (isset($_FILES['media_file']) && $_FILES['media_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['media_file'];
        $max_file_size = 40 * 1024 * 1024; // 40MB

        if ($file['size'] > $max_file_size) {
            redirect_with_message('post.php', 'error', 'File size exceeds 40MB limit.');
        }

        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_image_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $allowed_video_types = ['mp4', 'webm', 'ogg', 'mov', 'avi']; // Common video formats

        if (in_array($file_extension, $allowed_image_types)) {
            $media_type = 'image';
        } elseif (in_array($file_extension, $allowed_video_types)) {
            $media_type = 'video';
        } else {
            redirect_with_message('post.php', 'error', 'Unsupported file type. Only images and common video formats are allowed.');
        }

        $unique_filename = uniqid('media_', true) . '.' . $file_extension;
        $destination = $upload_dir . $unique_filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $media_path = $destination;
        } else {
            redirect_with_message('post.php', 'error', 'Failed to move uploaded file.');
        }
    } elseif (!empty($_POST['media_link'])) {
        $media_type = 'link';
        $media_path = htmlspecialchars($_POST['media_link']);
    } else {
        redirect_with_message('post.php', 'error', 'No media file or link provided.');
    }

    // Load existing posts
    $posts = [];
    if (file_exists($posts_json_file)) {
        $json_data = file_get_contents($posts_json_file);
        $posts = json_decode($json_data, true);
        if ($posts === null) {
            $posts = []; // Initialize if JSON is malformed
        }
    }

    // Create a new post array
    $new_post = [
        'id' => uniqid('post_', true), // Unique ID for each post
        'roll_number' => $roll_number,
        'name' => $name,
        'description' => $description,
        'media_type' => $media_type,
        'media_path' => $media_path,
        'timestamp' => time() // Add a timestamp for chronological sorting
    ];

    // Add the new post to the array
    $posts[] = $new_post;

    // Save the updated posts array to the JSON file
    if (file_put_contents($posts_json_file, json_encode($posts, JSON_PRETTY_PRINT))) {
        redirect_with_message('posts.php', 'success', 'Talent post submitted successfully!');
    } else {
        redirect_with_message('post.php', 'error', 'Failed to save post data.');
    }
} else {
    redirect_with_message('post.php', 'error', 'Invalid request method.');
}
?>
