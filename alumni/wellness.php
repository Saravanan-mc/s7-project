<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wellness Page</title>
</head>
<body>

    <!-- Top Navigation Bar -->
    <div style="background-color: #e0e0e0; padding: 10px; text-align: center; border-bottom: 1px solid #ccc;">
        <a href="#well1" style="margin: 0 15px; text-decoration: none; color: #333;">Page 1</a>
        <a href="#well2" style="margin: 0 15px; text-decoration: none; color: #333;">Page 2</a>
        <a href="#well3" style="margin: 0 15px; text-decoration: none; color: #333;">Page 3</a>
        <a href="#well4" style="margin: 0 15px; text-decoration: none; color: #333;">Page 4</a>
    </div>

    <!-- Main Content Area -->
    <div style="padding: 20px;">
        <h1>Wellness Page</h1>
        <p>Discover tips and resources for a healthy lifestyle on this wellness page.</p>

        <?php
            // Example PHP content
            $wellness_tips = [
                "Stay hydrated by drinking plenty of water throughout the day.",
                "Get at least 7-8 hours of quality sleep each night.",
                "Incorporate regular physical activity into your daily routine.",
                "Practice mindfulness or meditation for mental well-being."
            ];

            echo "<h2>Daily Wellness Tips:</h2>";
            echo "<ol>";
            foreach ($wellness_tips as $index => $tip) {
                echo "<li id='well" . ($index + 1) . "'>" . htmlspecialchars($tip) . "</li>";
            }
            echo "</ol>";
        ?>

        <h3 id="well4">Section: Page 4 (Additional Resources)</h3>
        <p>Links to external wellness resources and guides.</p>
        <ul>
            <li><a href="#" style="text-decoration: none; color: #007bff;">Healthy Eating Guide</a></li>
            <li><a href="#" style="text-decoration: none; color: #007bff;">Stress Management Techniques</a></li>
        </ul>
    </div>

</body>
</html>
