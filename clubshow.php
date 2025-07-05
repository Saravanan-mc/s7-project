<?php
$posts = [];
$file = 'club_posts.json';
if (file_exists($file)) {
    $json = file_get_contents($file);
    $posts = json_decode($json, true) ?? [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Club Posts</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    body { font-family: Arial, sans-serif; background:#f9f9f9; margin:0; padding:20px; }
    nav a {
      margin-right: 15px;
      text-decoration:none;
      color:#007BFF;
      font-weight:bold;
    }
    nav a:hover {
      text-decoration:underline;
    }
    .container {
      max-width: 800px;
      margin: auto;
      background:#fff;
      padding: 20px;
      border-radius: 6px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    article.post {
      border-bottom: 1px solid #ccc;
      padding-bottom: 15px;
      margin-bottom: 15px;
    }
    article.post:last-child {
      border-bottom: none;
      margin-bottom: 0;
      padding-bottom: 0;
    }
    h2, h3 {
      margin-top: 0;
    }
    img {
      max-width: 100%;
      height: auto;
      margin-top: 10px;
      border-radius: 4px;
    }
    .timestamp {
      font-size: 0.85em;
      color: #666;
    }
  </style>
</head>
<body>
  <nav>
    <a href="club.php">Club Home</a>
    <a href="clubpost.php">Submit Post</a>
    <a href="index.php">Back to Home</a>
  </nav>

  <div class="container">
    <h2>All Club Posts</h2>

    <?php if (empty($posts)): ?>
      <p>No club posts found. Be the first to <a href="clubpost.php">submit a post</a>!</p>
    <?php else: ?>
      <?php foreach ($posts as $post): ?>
        <article class="post">
          <h3><?= htmlspecialchars($post['title']) ?></h3>
          <p><strong>Club:</strong> <?= htmlspecialchars($post['clubName']) ?></p>
          <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
          <?php if (!empty($post['image'])): ?>
            <img src="<?= htmlspecialchars($post['image']) ?>" alt="Club Post Image" />
          <?php endif; ?>
          <p class="timestamp">Posted on <?= date('F j, Y, g:i a', $post['timestamp']) ?></p>
        </article>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</body>
</html>
