<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Club Talent Post</title>
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
        .post-detail-container {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 30px;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            max-width: 900px;
        }
        .post-detail-container h2 {
            margin-top: 0;
            color: #9c27b0; /* Purple for club posts */
            font-size: 1.8em;
            text-align: center;
            margin-bottom: 20px;
        }
        .post-detail-container p {
            line-height: 1.6;
            color: #555;
            margin-bottom: 15px;
        }
        .post-detail-container p strong {
            color: #333;
        }
        .post-detail-media {
            margin-top: 25px;
            margin-bottom: 25px;
            text-align: center;
            background-color: #eee;
            padding: 15px;
            border-radius: 8px;
        }
        .post-detail-media img, .post-detail-media video {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
            border-radius: 6px;
        }
        .post-detail-media iframe {
            width: 100%;
            max-width: 640px;
            height: 360px;
            border: none;
            border-radius: 6px;
        }
        .like-comment-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px dashed #eee;
        }
        .like-button {
            padding: 10px 20px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }
        .like-button:hover {
            background-color: #0056b3;
        }
        .comment-input {
            width: calc(100% - 120px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
            font-size: 0.95em;
            vertical-align: middle;
        }
        .comment-button {
            padding: 10px 20px;
            cursor: pointer;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            transition: background-color 0.3s ease;
            vertical-align: middle;
        }
        .comment-button:hover {
            background-color: #218838;
        }
        .comments-list {
            margin-top: 20px;
            font-size: 0.9em;
            background-color: #f0f0f0;
            padding: 15px;
            border-radius: 8px;
        }
        .comment-item {
            background-color: #e9ecef;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 10px;
            color: #444;
        }
        .comment-item:last-child {
            margin-bottom: 0;
        }
        .post-not-found-message {
            text-align: center;
            font-size: 1.2em;
            color: #666;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="club_home.php">Home</a>
        <a href="club_post.php">Submit Club Post</a>
        <a href="club_posts.php">View Club Posts</a>
        <a href="club_event_post.php">Submit Event</a>
        <a href="club_events.php">View Events</a>
    </div>

    <?php
    $club_posts_json_file = 'club_posts.json'; // Club-specific JSON
    $post = null;
    $post_id_to_view = '';

    if (isset($_GET['id'])) {
        $post_id_to_view = htmlspecialchars($_GET['id']);
        if (file_exists($club_posts_json_file)) {
            $json_data = file_get_contents($club_posts_json_file);
            $posts = json_decode($json_data, true);
            if ($posts !== null) {
                foreach ($posts as $p) {
                    if ($p['id'] === $post_id_to_view) {
                        $post = $p;
                        break;
                    }
                }
            }
        }
    }

    if ($post) {
        echo "<div class='post-detail-container'>";
        echo "<h2>ID: " . htmlspecialchars($post['roll_number']) . " - " . htmlspecialchars($post['name']) . "</h2>";
        echo "<p><strong>Description:</strong></p>";
        echo "<p>" . nl2br(htmlspecialchars($post['description'])) . "</p>";

        echo "<div class='post-detail-media'>";
        if ($post['media_type'] === 'image') {
            echo "<img src='" . htmlspecialchars($post['media_path']) . "' alt='Club Image'>";
        } elseif ($post['media_type'] === 'video') {
            echo "<video controls><source src='" . htmlspecialchars($post['media_path']) . "' type='video/mp4'>Your browser does not support the video tag.</video>";
        } elseif ($post['media_type'] === 'link') {
            $link = htmlspecialchars($post['media_path']);
            if (strpos($link, 'youtube.com/watch?v=') !== false) {
                $video_id = explode('v=', $link)[1];
                $video_id = explode('&', $video_id)[0];
                echo "<iframe src='https://www.youtube.com/embed/" . $video_id . "' allowfullscreen></iframe>";
            } elseif (strpos($link, 'vimeo.com/') !== false) {
                $video_id = explode('vimeo.com/', $link)[1];
                echo "<iframe src='https://player.vimeo.com/video/" . $video_id . "' allowfullscreen></iframe>";
            } else {
                echo "<p><a href='" . $link . "' target='_blank'>" . $link . "</a></p>";
            }
        }
        echo "</div>";

        echo "<div class='like-comment-section'>";
        echo "<button class='like-button' data-post-id='" . $post_id_to_view . "' onclick='toggleLike(\"" . $post_id_to_view . "\")'>Like (<span id='likes_" . $post_id_to_view . "'>0</span>)</button>";
        echo "<div style='margin-top: 15px;'>";
        echo "<input type='text' class='comment-input' id='comment_input_" . $post_id_to_view . "' placeholder='Add a comment...'>";
        echo "<button class='comment-button' onclick='addComment(\"" . $post_id_to_view . "\")'>Comment</button>";
        echo "</div>";
        echo "<div class='comments-list' id='comments_list_" . $post_id_to_view . "'></div>";
        echo "</div>"; // End like-comment-section
        echo "</div>"; // End post-detail-container
    } else {
        echo "<p class='post-not-found-message'>Post not found. Please check the URL or view all club posts.</p>";
    }
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const postId = "<?php echo $post_id_to_view; ?>";
            if (postId) {
                initPostInteractions(postId);
            }
        });

        function initPostInteractions(postId) {
            const likes = JSON.parse(localStorage.getItem('likes')) || {};
            const likeCount = likes[postId] ? likes[postId] : 0;
            document.getElementById('likes_' + postId).innerText = likeCount;

            const comments = JSON.parse(localStorage.getItem('comments')) || {};
            renderComments(postId, comments[postId] || []);
        }

        function toggleLike(postId) {
            let likes = JSON.parse(localStorage.getItem('likes')) || {};
            if (likes[postId]) {
                likes[postId]--;
                if (likes[postId] < 0) likes[postId] = 0;
            } else {
                likes[postId] = 1;
            }
            localStorage.setItem('likes', JSON.stringify(likes));
            document.getElementById('likes_' + postId).innerText = likes[postId];
        }

        function addComment(postId) {
            const commentInput = document.getElementById('comment_input_' + postId);
            const commentText = commentInput.value.trim();

            if (commentText === '') {
                const messageDiv = document.createElement('div');
                messageDiv.style.cssText = "background-color: #ffe0b2; color: #fb8c00; padding: 10px; border-radius: 5px; margin-bottom: 10px; text-align: center;";
                messageDiv.innerText = 'Comment cannot be empty!';
                commentInput.parentNode.insertBefore(messageDiv, commentInput);
                setTimeout(() => messageDiv.remove(), 3000);
                return;
            }

            let comments = JSON.parse(localStorage.getItem('comments')) || {};
            if (!comments[postId]) {
                comments[postId] = [];
            }
            comments[postId].push(commentText);
            localStorage.setItem('comments', JSON.stringify(comments));

            renderComments(postId, comments[postId]);
            commentInput.value = '';
        }

        function renderComments(postId, postComments) {
            const commentsListDiv = document.getElementById('comments_list_' + postId);
            commentsListDiv.innerHTML = '';

            if (postComments.length === 0) {
                commentsListDiv.innerHTML = '<p style="font-style: italic; color: #666; text-align: center;">No comments yet.</p>';
                return;
            }

            postComments.forEach(comment => {
                const commentDiv = document.createElement('div');
                commentDiv.className = 'comment-item';
                commentDiv.innerText = comment;
                commentsListDiv.appendChild(commentDiv);
            });
        }
    </script>
</body>
</html>
