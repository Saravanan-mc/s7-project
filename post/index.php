<?php
// Ensure the data directory exists
if (!is_dir('data')) {
    mkdir('data');
}
// Ensure the posts.json file exists and is readable/writable
$postsFile = 'data/posts.json';
if (!file_exists($postsFile)) {
    file_put_contents($postsFile, '[]');
}

// Function to read posts from JSON file
function getPosts() {
    global $postsFile;
    $postsJson = file_get_contents($postsFile);
    return json_decode($postsJson, true) ?: [];
}

// Handle Like/Unlike action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle_like') {
    $postId = $_POST['post_id'];
    $likerName = $_POST['liker_name']; // In a real app, this would come from a user session
    $posts = getPosts();
    foreach ($posts as &$post) {
        if ($post['id'] === $postId) {
            $likerIndex = array_search($likerName, $post['likes']);
            if ($likerIndex !== false) {
                // Unlike
                unset($post['likes'][$likerIndex]);
                $post['likes'] = array_values($post['likes']); // Re-index array
            } else {
                // Like
                $post['likes'][] = $likerName;
            }
            break;
        }
    }
    file_put_contents($postsFile, json_encode($posts, JSON_PRETTY_PRINT));
    header('Location: index.php'); // Redirect to prevent form resubmission
    exit;
}

$posts = getPosts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Doubt Forum</title>
    <style>
        body { font-family: sans-serif; margin: 20px; background-color: #f4f7f6; color: #333; }
        .container { max-width: 800px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #007bff; margin-bottom: 30px; }
        .post-form-section { background-color: #e9f0f8; padding: 20px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #cceeff; }
        .post-form-section h2 { margin-top: 0; color: #007bff; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input[type="text"], .form-group input[type="email"], .form-group textarea {
            width: calc(100% - 22px); padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 1rem;
        }
        .form-group textarea { resize: vertical; min-height: 80px; }
        .form-group input[type="file"] { padding: 5px 0; }
        .btn-submit { background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 1rem; }
        .btn-submit:hover { background-color: #218838; }
        .post-feed { margin-top: 30px; }
        .post { background-color: #ffffff; border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin-bottom: 20px; box-shadow: 0 1px 5px rgba(0,0,0,0.05); }
        .post-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .post-info { font-size: 0.9em; color: #666; }
        .post-info strong { color: #333; }
        .post-content p { margin: 0 0 10px 0; line-height: 1.6; }
        .post-image { max-width: 100%; height: auto; border-radius: 4px; margin-top: 10px; }
        .post-actions { margin-top: 10px; display: flex; gap: 10px; }
        .action-button { background-color: #007bff; color: white; padding: 8px 12px; border: none; border-radius: 5px; cursor: pointer; font-size: 0.9rem; }
        .action-button.like-button { background-color: #ff4500; }
        .action-button.like-button.liked { background-color: #ff6347; }
        .action-button.comment-button { background-color: #6c757d; }
        .action-button:hover { opacity: 0.9; }
        .like-count { margin-left: 5px; font-weight: bold; }
        .no-posts { text-align: center; color: #666; padding: 20px; border: 1px dashed #ccc; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Local Student Doubt Forum</h1>

        <div class="post-form-section">
            <h2>Post a New Doubt</h2>
            <form action="create_post.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="studentName">Your Name:</label>
                    <input type="text" id="studentName" name="studentName" required>
                </div>
                <div class="form-group">
                    <label for="rollNumber">Roll Number:</label>
                    <input type="text" id="rollNumber" name="rollNumber" required>
                </div>
                <div class="form-group">
                    <label for="email">Email ID:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="doubtText">Your Doubt:</label>
                    <textarea id="doubtText" name="doubtText" required></textarea>
                </div>
                <div class="form-group">
                    <label for="doubtImage">Optional Image:</label>
                    <input type="file" id="doubtImage" name="doubtImage" accept="image/*">
                </div>
                <button type="submit" class="btn-submit">Post Doubt</button>
            </form>
        </div>

        <hr>

        <div class="post-feed">
            <h2>Recent Doubts</h2>
            <?php if (empty($posts)): ?>
                <p class="no-posts">No doubts posted yet. Be the first to post!</p>
            <?php else: ?>
                <?php
                // Sort posts by ID (assuming ID is timestamp-based for recency)
                usort($posts, function($a, $b) {
                    return $b['id'] <=> $a['id'];
                });
                ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post" id="post-<?php echo htmlspecialchars($post['id']); ?>">
                        <div class="post-header">
                            <div class="post-info">
                                Posted by <strong><?php echo htmlspecialchars($post['studentName']); ?></strong> (<?php echo htmlspecialchars($post['rollNumber']); ?>)
                                <br>
                                <small><?php echo htmlspecialchars($post['email']); ?></small>
                            </div>
                        </div>
                        <div class="post-content">
                            <p><?php echo nl2br(htmlspecialchars($post['doubtText'])); ?></p>
                            <?php if (!empty($post['image'])): ?>
                                <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Doubt Image" class="post-image">
                            <?php endif; ?>
                        </div>
                        <div class="post-actions">
                            <form action="index.php" method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="toggle_like">
                                <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post['id']); ?>">
                                <input type="hidden" name="liker_name" value="Current User">
                                <?php
                                    $isLiked = false;
                                    if (in_array("Current User", $post['likes'])) { // Check if "Current User" liked
                                        $isLiked = true;
                                    }
                                ?>
                                <button type="submit" class="action-button like-button <?php echo $isLiked ? 'liked' : ''; ?>">
                                    <?php echo $isLiked ? 'Unlike' : 'Like'; ?>
                                </button>
                                <span class="like-count"><?php echo count($post['likes']); ?> Likes</span>
                            </form>
                            <a href="view_post.php?id=<?php echo htmlspecialchars($post['id']); ?>" class="action-button comment-button">
                                View Details (<?php echo count($post['comments']); ?> Comments)
                            </a>
                            </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // No specific internal JS for this page, but you could add dynamic content loading or subtle animations here.
        // Canvas is not directly used for UI elements like buttons or forms as per standard web development practices.
        // If you intended canvas for visual effects, it would be a separate implementation.
    </script>
</body>
</html>