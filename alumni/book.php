<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Page</title>
</head>
<body>

    <!-- Top Navigation Bar -->
    <div style="background-color: #e0e0e0; padding: 10px; text-align: center; border-bottom: 1px solid #ccc;">
        <a href="#page1" style="margin: 0 15px; text-decoration: none; color: #333;">Page 1</a>
        <a href="#page2" style="margin: 0 15px; text-decoration: none; color: #333;">Page 2</a>
        <a href="#page3" style="margin: 0 15px; text-decoration: none; color: #333;">Page 3</a>
        <a href="#page4" style="margin: 0 15px; text-decoration: none; color: #333;">Page 4</a>
        <a href="#page5" style="margin: 0 15px; text-decoration: none; color: #333;">Page 5</a>
        <a href="#page6" style="margin: 0 15px; text-decoration: none; color: #333;">Page 6</a>
        <a href="#page7" style="margin: 0 15px; text-decoration: none; color: #333;">Page 7</a>
        <a href="#page8" style="margin: 0 15px; text-decoration: none; color: #333;">Page 8</a>
    </div>

    <!-- Main Content Area -->
    <div style="padding: 20px;">
        <h1>Welcome to the Book Page</h1>
        <p>This page is dedicated to book-related content. You can navigate through different sections using the links above.</p>

        <?php
            // Example PHP content
            $books = ["The Great Gatsby", "1984", "To Kill a Mockingbird", "Pride and Prejudice"];
            echo "<h2>Featured Books:</h2>";
            echo "<ul>";
            foreach ($books as $book) {
                echo "<li>" . htmlspecialchars($book) . "</li>";
            }
            echo "</ul>";
        ?>

        <h3 id="page1">Section: Page 1</h3>
        <p>Content for Page 1...</p>
        <h3 id="page2">Section: Page 2</h3>
        <p>Content for Page 2...</p>
        <h3 id="page3">Section: Page 3</h3>
        <p>Content for Page 3...</p>
        <h3 id="page4">Section: Page 4</h3>
        <p>Content for Page 4...</p>
        <h3 id="page5">Section: Page 5</h3>
        <p>Content for Page 5...</p>
        <h3 id="page6">Section: Page 6</h3>
        <p>Content for Page 6...</p>
        <h3 id="page7">Section: Page 7</h3>
        <p>Content for Page 7...</p>
        <h3 id="page8">Section: Page 8</h3>
        <p>Content for Page 8...</p>
    </div>

</body>
</html>
