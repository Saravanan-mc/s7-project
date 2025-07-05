<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Events</title>
</head>
<body>
    <!-- Top Navigation Bar -->
    <div style="background-color: #f0f0f0; padding: 10px; text-align: center;">
        <a href="#upcoming" style="margin: 0 10px;">Upcoming Events</a>
        <a href="#past" style="margin: 0 10px;">Past Events</a>
        <a href="#host" style="margin: 0 10px;">Host an Event</a>
        <a href="#gallery" style="margin: 0 10px;">Event Gallery</a>
        <a href="#contact" style="margin: 0 10px;">Contact</a>
    </div>

    <!-- Main Content Area -->
    <div style="padding: 20px;">
        <h1>Club Events</h1>
        <p>Welcome to our Club Events page! Here you can find information about our upcoming gatherings, past events, and how to get involved.</p>

        <h2 id="upcoming">Upcoming Events</h2>
        <table border="1" style="width:100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="padding: 8px;">Event Name</th>
                    <th style="padding: 8px;">Date</th>
                    <th style="padding: 8px;">Time</th>
                    <th style="padding: 8px;">Location</th>
                    <th style="padding: 8px;">Description</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 8px;">Annual Gala Night</td>
                    <td style="padding: 8px;">2025-07-15</td>
                    <td style="padding: 8px;">7:00 PM</td>
                    <td style="padding: 8px;">Grand Ballroom</td>
                    <td style="padding: 8px;">A formal evening with dinner and entertainment.</td>
                </tr>
                <tr>
                    <td style="padding: 8px;">Summer Picnic</td>
                    <td style="padding: 8px;">2025-08-01</td>
                    <td style="padding: 8px;">1:00 PM</td>
                    <td style="padding: 8px;">City Park</td>
                    <td style="padding: 8px;">Fun for the whole family with games and food.</td>
                </tr>
                <?php
                    // Example of dynamic event listing (can be fetched from a database in a real app)
                    $events = [
                        ["Coding Workshop", "2025-09-10", "6:00 PM", "Community Hall", "Learn the basics of web development."],
                        ["Book Club Meeting", "2025-09-25", "5:30 PM", "Library Annex", "Discussion on this month's book."],
                    ];
                    foreach ($events as $event) {
                        echo "<tr>";
                        echo "<td style='padding: 8px;'>" . htmlspecialchars($event[0]) . "</td>";
                        echo "<td style='padding: 8px;'>" . htmlspecialchars($event[1]) . "</td>";
                        echo "<td style='padding: 8px;'>" . htmlspecialchars($event[2]) . "</td>";
                        echo "<td style='padding: 8px;'>" . htmlspecialchars($event[3]) . "</td>";
                        echo "<td style='padding: 8px;'>" . htmlspecialchars($event[4]) . "</td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>

        <h2 id="past">Past Events</h2>
        <p>Check out some of our successful past events:</p>
        <ul>
            <li>Winter Charity Drive (Dec 2024)</li>
            <li>Spring Festival (Apr 2025)</li>
            <li>Annual General Meeting (May 2025)</li>
        </ul>

        <h2 id="host">Host an Event</h2>
        <p>Interested in organizing an event for the club? Fill out the form below!</p>
        <form action="#" method="post">
            <label for="eventName">Event Name:</label><br>
            <input type="text" id="eventName" name="eventName" required><br><br>
            <label for="eventDate">Date:</label><br>
            <input type="date" id="eventDate" name="eventDate" required><br><br>
            <label for="eventDesc">Description:</label><br>
            <textarea id="eventDesc" name="eventDesc" rows="5" cols="50"></textarea><br><br>
            <input type="submit" value="Submit Event Proposal">
        </form>

        <h2 id="gallery">Event Gallery</h2>
        <p>A sneak peek into moments from our previous events.</p>
        <p>[Image placeholders would go here in a real application]</p>
        <p>Image 1 | Image 2 | Image 3</p>

        <h2 id="contact">Contact</h2>
        <p>For any event-related inquiries, please reach out to us at events@club.com.</p>
    </div>
</body>
</html>
