<?php
// Ensure data directory exists
if (!is_dir('data')) {
    mkdir('data');
}
$postsFile = 'data/posts.json';
if (!file_exists($postsFile)) {
    file_put_contents($postsFile, '[]');
}

// Read posts from JSON
function getPosts($postsFile) {
    $postsJson = file_get_contents($postsFile);
    return json_decode($postsJson, true) ?: [];
}

// Save posts to JSON
function savePosts($postsFile, $posts) {
    file_put_contents($postsFile, json_encode($posts, JSON_PRETTY_PRINT));
}

// Handle Like/Unlike action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'toggle_like') {
    $postId = $_POST['post_id'];
    $likerName = $_POST['liker_name'];
    $posts = getPosts($postsFile);

    foreach ($posts as &$post) {
        if ($post['id'] === $postId) {
            if (!isset($post['likes'])) $post['likes'] = [];
            $index = array_search($likerName, $post['likes']);
            if ($index !== false) {
                unset($post['likes'][$index]);
                $post['likes'] = array_values($post['likes']);
            } else {
                $post['likes'][] = $likerName;
            }
            break;
        }
    }
    savePosts($postsFile, $posts);
    header('Location: view_feed.php');
    exit;
}

$posts = getPosts($postsFile);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student Doubt Forum - Doubt Feed</title>
  <style>
    body { font-family: sans-serif; margin: 20px; background-color: #f4f7f6; color: #333; }
    .container { max-width: 800px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h1 { text-align: center; color: #007bff; margin-bottom: 30px; }

    /* Top-right navigation */
    .navigation {
        position: absolute;
        top: 20px;
        right: 20px;
        display: flex;
        gap: 10px;
    }
    .navigation a {
        text-decoration: none;
        color: white;
        background-color: #007bff;
        padding: 8px 15px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
        font-size: 0.9em;
    }
    .navigation a:hover {
        background-color: #0056b3;
    }

    .main-content-area {
        margin-top: 80px;
    }

    .post { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin-bottom: 20px; box-shadow: 0 1px 5px rgba(0,0,0,0.05); }
    .post-info { font-size: 0.9em; color: #555; }
    .post-content { margin-top: 10px; }
    .post-content p { margin: 0 0 10px; line-height: 1.5; }
    .post-image { max-width: 100%; margin-top: 10px; border-radius: 5px; }

    .post-actions { margin-top: 10px; display: flex; gap: 10px; }
    .action-button {
        padding: 6px 12px;
        border: none;
        border-radius: 5px;
        color: white;
        cursor: pointer;
        font-size: 0.85rem;
    }
    .like-button { background-color: #ff4500; }
    .like-button.liked { background-color: #e25822; }
    .comment-button { background-color: #6c757d; }
    .like-count { margin-left: 6px; color: #444; font-weight: bold; }
    .no-posts { text-align: center; padding: 20px; color: #888; font-style: italic; }
  </style>
</head>
<body>
  <div class="navigation">
    <a href="home.php">Welcome</a>
    <a href="create_post.php">Post Doubt</a>
    <a href="view_feed.php">Doubt Feed</a>
  </div>

  <div class="container main-content-area">
    <h1>Recent Doubts</h1>

    <?php if (count($posts) === 0): ?>
      <div class="no-posts">No posts yet. Be the first to ask a question!</div>
    <?php else: ?>
      <?php foreach (array_reverse($posts) as $post): ?>
        <div class="post">
          <div class="post-info">
            <strong><?php echo htmlspecialchars($post['name']); ?></strong> (<?php echo htmlspecialchars($post['roll']); ?>)<br>
            <small><?php echo htmlspecialchars($post['email']); ?></small>
          </div>
          <div class="post-content">
            <p><?php echo nl2br(htmlspecialchars($post['text'])); ?></p>
            <?php if (!empty($post['image']) && file_exists($post['image'])): ?>
              <img class="post-image" src="<?php echo $post['image']; ?>" alt="Doubt Image" />
            <?php endif; ?>
          </div>
          <div class="post-actions">
            <form method="POST" style="display:inline;">
              <input type="hidden" name="action" value="toggle_like" />
              <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>" />
              <input type="hidden" name="liker_name" value="AnonymousUser" />
              <button type="submit" class="action-button like-button <?php echo (isset($post['likes']) && in_array('AnonymousUser', $post['likes'])) ? 'liked' : ''; ?>">
                ❤️ Like <span class="like-count"><?php echo isset($post['likes']) ? count($post['likes']) : 0; ?></span>
              </button>
            </form>
            <a href="view_post.php?id=<?php echo $post['id']; ?>">
              <button class="action-button comment-button">💬 Comment</button>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</body>
</html>
