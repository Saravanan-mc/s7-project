<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Read Page</title>
</head>
<body>

    <!-- Top Navigation Bar -->
    <div style="background-color: #e0e0e0; padding: 10px; text-align: center; border-bottom: 1px solid #ccc;">
        <a href="#post1" style="margin: 0 15px; text-decoration: none; color: #333;">Page 1</a>
        <a href="#post2" style="margin: 0 15px; text-decoration: none; color: #333;">Page 2</a>
        <a href="#post3" style="margin: 0 15px; text-decoration: none; color: #333;">Page 3</a>
        <a href="#post4" style="margin: 0 15px; text-decoration: none; color: #333;">Page 4</a>
    </div>

    <!-- Main Content Area -->
    <div style="padding: 20px;">
        <h1>Post Read Page</h1>
        <p>This page is for reading various posts or articles.</p>

        <?php
            // Example PHP content
            $posts = [
                ["title" => "First Post", "content" => "This is the content of the first post. It discusses basic concepts."],
                ["title" => "Second Post", "content" => "Here's the second post, diving deeper into the topic."],
                ["title" => "Third Post", "content" => "The third post offers new perspectives and insights."],
            ];

            foreach ($posts as $index => $post) {
                echo "<div style='border: 1px solid #eee; padding: 10px; margin-bottom: 15px;'>";
                echo "<h2 id='post" . ($index + 1) . "'>" . htmlspecialchars($post['title']) . "</h2>";
                echo "<p>" . htmlspecialchars($post['content']) . "</p>";
                echo "</div>";
            }
        ?>

        <h3 id="post4">Section: Page 4</h3>
        <p>Additional content for Page 4.</p>
    </div>

</body>
</html>
