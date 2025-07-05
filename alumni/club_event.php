<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-M">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Event Page</title>
</head>
<body>

    <!-- Top Navigation Bar -->
    <div style="background-color: #e0e0e0; padding: 10px; text-align: center; border-bottom: 1px solid #ccc;">
        <a href="#event1" style="margin: 0 15px; text-decoration: none; color: #333;">Page 1</a>
        <a href="#event2" style="margin: 0 15px; text-decoration: none; color: #333;">Page 2</a>
        <a href="#event3" style="margin: 0 15px; text-decoration: none; color: #333;">Page 3</a>
        <a href="#event4" style="margin: 0 15px; text-decoration: none; color: #333;">Page 4</a>
        <a href="#event5" style="margin: 0 15px; text-decoration: none; color: #333;">Page 5</a>
    </div>

    <!-- Main Content Area -->
    <div style="padding: 20px;">
        <h1>Club and Event Page</h1>
        <p>Find information about upcoming club activities and events here.</p>

        <?php
            // Example PHP content for events
            $events = [
                ["name" => "Annual Gala Dinner", "date" => "2025-07-15", "location" => "Grand Ballroom"],
                ["name" => "Tech Workshop", "date" => "2025-08-01", "location" => "Innovation Hub"],
                ["name" => "Sports Day", "date" => "2025-09-10", "location" => "University Stadium"],
                ["name" => "Alumni Networking Mixer", "date" => "2025-10-20", "location" => "City Club"],
            ];

            echo "<h2>Upcoming Events:</h2>";
            echo "<table border='1' cellpadding='10' cellspacing='0'>";
            echo "<thead><tr><th>Event Name</th><th>Date</th><th>Location</th></tr></thead>";
            echo "<tbody>";
            foreach ($events as $index => $event) {
                echo "<tr id='event" . ($index + 1) . "'>";
                echo "<td>" . htmlspecialchars($event['name']) . "</td>";
                echo "<td>" . htmlspecialchars($event['date']) . "</td>";
                echo "<td>" . htmlspecialchars($event['location']) . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        ?>

        <h3 id="event5">Section: Page 5 (Club Directory)</h3>
        <p>List of active clubs and their contact information.</p>
        <ul>
            <li>Programming Club: contact@programmingclub.org</li>
            <li>Debate Society: info@debatesociety.org</li>
        </ul>
    </div>

</body>
</html>
