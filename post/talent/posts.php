<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Talent Posts</title>
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
        .posts-list-container {
            width: 70%;
            max-width: 900px;
            margin: 0 auto;
            padding-bottom: 30px; /* Add some space at the bottom */
        }
        .post-container {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .post-container h3 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #007bff;
            font-size: 1.4em;
        }
        .post-container p {
            margin-bottom: 15px;
            line-height: 1.6;
            color: #555;
        }
        .post-media {
            margin-top: 15px;
            margin-bottom: 15px;
            text-align: center;
            background-color: #eee; /* Light background for media area */
            padding: 10px;
            border-radius: 5px;
        }
        .post-media img, .post-media video {
            max-width: 100%;
            height: auto;
            display: block; /* To remove extra space below image/video */
            margin: 0 auto;
            border-radius: 4px;
        }
        .post-media iframe {
            width: 100%;
            max-width: 560px; /* Standard YouTube embed width */
            height: 315px; /* Standard YouTube embed height */
            border: none;
            border-radius: 4px;
        }
        .like-comment-section {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px dashed #eee;
        }
        .like-button {
            padding: 8px 15px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 0.95em;
            transition: background-color 0.3s ease;
        }
        .like-button:hover {
            background-color: #0056b3;
        }
        .comment-input {
            width: calc(100% - 120px); /* Adjust for button and padding */
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 8px;
            font-size: 0.9em;
            vertical-align: middle; /* Align with button */
        }
        .comment-button {
            padding: 8px 15px;
            cursor: pointer;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 0.95em;
            transition: background-color 0.3s ease;
            vertical-align: middle; /* Align with input */
        }
        .comment-button:hover {
            background-color: #218838;
        }
        .comments-list {
            margin-top: 15px;
            font-size: 0.9em;
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
        }
        .comment-item {
            background-color: #e9ecef;
            padding: 8px 12px;
            border-radius: 4px;
            margin-bottom: 8px;
            color: #444;
        }
        .comment-item:last-child {
            margin-bottom: 0;
        }
        .view-details-link {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-size: 0.95em;
        }
        .view-details-link:hover {
            background-color: #5a6268;
        }
        .no-posts-message {
            text-align: center;
            font-size: 1.2em;
            color: #666;
            margin-top: 50px;
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

    <h2>All Talent Posts</h2>

    <div class="posts-list-container">
        <?php
        $posts_json_file = 'posts.json';
        $posts = [];

        if (file_exists($posts_json_file)) {
            $json_data = file_get_contents($posts_json_file);
            $posts = json_decode($json_data, true);
            if ($posts === null) {
                $posts = [];
            }
        }

        if (empty($posts)) {
            echo "<p class='no-posts-message'>No talent posts yet. Be the first to share!</p>";
        } else {
            // Sort posts by timestamp in descending order (latest first)
            usort($posts, function($a, $b) {
                return $b['timestamp'] - $a['timestamp'];
            });

            foreach ($posts as $post) {
                $post_id = htmlspecialchars($post['id']);
                echo "<div class='post-container'>";
                echo "<h3>Roll No: " . htmlspecialchars($post['roll_number']) . " - " . htmlspecialchars($post['name']) . "</h3>";
                echo "<p>" . nl2br(htmlspecialchars($post['description'])) . "</p>";

                echo "<div class='post-media'>";
                if ($post['media_type'] === 'image') {
                    echo "<img src='" . htmlspecialchars($post['media_path']) . "' alt='Talent Image'>";
                } elseif ($post['media_type'] === 'video') {
                    echo "<video controls><source src='" . htmlspecialchars($post['media_path']) . "' type='video/mp4'>Your browser does not support the video tag.</video>";
                } elseif ($post['media_type'] === 'link') {
                    // Basic check for YouTube/Vimeo for embedding
                    $link = htmlspecialchars($post['media_path']);
                    if (strpos($link, 'youtube.com/watch?v=') !== false) {
                        $video_id = explode('v=', $link)[1];
                        $video_id = explode('&', $video_id)[0]; // Remove extra parameters
                        echo "<iframe src='https://www.youtube.com/embed/" . $video_id . "' allowfullscreen></iframe>";
                    } elseif (strpos($link, 'vimeo.com/') !== false) {
                        $video_id = explode('vimeo.com/', $link)[1];
                        echo "<iframe src='https://player.vimeo.com/video/" . $video_id . "' allowfullscreen></iframe>";
                    } else {
                        echo "<p><a href='" . $link . "' target='_blank'>" . $link . "</a></p>";
                    }
                }
                echo "</div>";

                echo "<a href='view_post.php?id=" . $post_id . "' class='view-details-link'>View Details</a>";

                echo "<div class='like-comment-section'>";
                echo "<button class='like-button' data-post-id='" . $post_id . "' onclick='toggleLike(\"" . $post_id . "\")'>Like (<span id='likes_" . $post_id . "'>0</span>)</button>";
                echo "<div style='margin-top: 10px;'>";
                echo "<input type='text' class='comment-input' id='comment_input_" . $post_id . "' placeholder='Add a comment...'>";
                echo "<button class='comment-button' onclick='addComment(\"" . $post_id . "\")'>Comment</button>";
                echo "</div>";
                echo "<div class='comments-list' id='comments_list_" . $post_id . "'></div>";
                echo "</div>"; // End like-comment-section
                echo "</div>"; // End post-container
            }
        }
        ?>
    </div>

    <script>
        // Initialize likes and comments from localStorage on page load
        document.addEventListener('DOMContentLoaded', function() {
            <?php
            // Re-encode posts for JS access, or pass individual post IDs
            if (!empty($posts)) {
                foreach ($posts as $post) {
                    $post_id = htmlspecialchars($post['id']);
                    echo "initPostInteractions('" . $post_id . "');\n";
                }
            }
            ?>
        });

        function initPostInteractions(postId) {
            // Initialize likes
            const likes = JSON.parse(localStorage.getItem('likes')) || {};
            const likeCount = likes[postId] ? likes[postId] : 0;
            document.getElementById('likes_' + postId).innerText = likeCount;

            // Initialize comments
            const comments = JSON.parse(localStorage.getItem('comments')) || {};
            renderComments(postId, comments[postId] || []);
        }

        function toggleLike(postId) {
            let likes = JSON.parse(localStorage.getItem('likes')) || {};
            if (likes[postId]) {
                likes[postId]--;
                if (likes[postId] < 0) likes[postId] = 0; // Prevent negative likes
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
                // Replaced alert with a basic div message for better UX
                const messageDiv = document.createElement('div');
                messageDiv.style.cssText = "background-color: #ffe0b2; color: #fb8c00; padding: 10px; border-radius: 5px; margin-bottom: 10px; text-align: center;";
                messageDiv.innerText = 'Comment cannot be empty!';
                commentInput.parentNode.insertBefore(messageDiv, commentInput);
                setTimeout(() => messageDiv.remove(), 3000); // Remove message after 3 seconds
                return;
            }

            let comments = JSON.parse(localStorage.getItem('comments')) || {};
            if (!comments[postId]) {
                comments[postId] = [];
            }
            comments[postId].push(commentText);
            localStorage.setItem('comments', JSON.stringify(comments));

            renderComments(postId, comments[postId]);
            commentInput.value = ''; // Clear input
        }

        function renderComments(postId, postComments) {
            const commentsListDiv = document.getElementById('comments_list_' + postId);
            commentsListDiv.innerHTML = ''; // Clear existing comments

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
