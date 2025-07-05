<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost and Found</title>
</head>
<body>
    <!-- Top Navigation Bar -->
    <div style="background-color: #f0f0f0; padding: 10px; text-align: center;">
        <a href="#lost" style="margin: 0 10px;">Lost Items</a>
        <a href="#found" style="margin: 0 10px;">Found Items</a>
        <a href="#report-lost" style="margin: 0 10px;">Report Lost</a>
        <a href="#report-found" style="margin: 0 10px;">Report Found</a>
        <a href="#faq" style="margin: 0 10px;">FAQ</a>
    </div>

    <!-- Main Content Area -->
    <div style="padding: 20px;">
        <h1>Lost and Found</h1>
        <p>Welcome to the Lost and Found portal. Please browse reported items or report a lost/found item.</p>

        <h2 id="lost">Lost Items</h2>
        <table border="1" style="width:100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="padding: 8px;">Item</th>
                    <th style="padding: 8px;">Description</th>
                    <th style="padding: 8px;">Last Seen Location</th>
                    <th style="padding: 8px;">Date Lost</th>
                    <th style="padding: 8px;">Contact Info</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 8px;">Red Wallet</td>
                    <td style="padding: 8px;">Contains ID and cards.</td>
                    <td style="padding: 8px;">Cafeteria</td>
                    <td style="padding: 8px;">2025-05-30</td>
                    <td style="padding: 8px;">user1@email.com</td>
                </tr>
                <tr>
                    <td style="padding: 8px;">Keys</td>
                    <td style="padding: 8px;">Silver house keys with a blue keyring.</td>
                    <td style="padding: 8px;">Main Entrance</td>
                    <td style="padding: 8px;">2025-06-01</td>
                    <td style="padding: 8px;">user2@email.com</td>
                </tr>
                <?php
                    // Example of dynamic lost item listing
                    $lostItems = [
                        ["Laptop", "Black Dell laptop, missing charger.", "Lecture Hall 3", "2025-06-03", "user3@email.com"],
                    ];
                    foreach ($lostItems as $item) {
                        echo "<tr>";
                        echo "<td style='padding: 8px;'>" . htmlspecialchars($item[0]) . "</td>";
                        echo "<td style='padding: 8px;'>" . htmlspecialchars($item[1]) . "</td>";
                        echo "<td style='padding: 8px;'>" . htmlspecialchars($item[2]) . "</td>";
                        echo "<td style='padding: 8px;'>" . htmlspecialchars($item[3]) . "</td>";
                        echo "<td style='padding: 8px;'>" . htmlspecialchars($item[4]) . "</td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>

        <h2 id="found">Found Items</h2>
        <table border="1" style="width:100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="padding: 8px;">Item</th>
                    <th style="padding: 8px;">Description</th>
                    <th style="padding: 8px;">Found Location</th>
                    <th style="padding: 8px;">Date Found</th>
                    <th style="padding: 8px;">Contact Info</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 8px;">Eyeglasses</td>
                    <td style="padding: 8px;">Black frames, prescription glasses.</td>
                    <td style="padding: 8px;">Library</td>
                    <td style="padding: 8px;">2025-05-29</td>
                    <td style="padding: 8px;">finder1@email.com</td>
                </tr>
                <tr>
                    <td style="padding: 8px;">USB Drive</td>
                    <td style="padding: 8px;">Small silver USB drive.</td>
                    <td style="padding: 8px;">Study Room 5</td>
                    <td style="padding: 8px;">2025-06-02</td>
                    <td style="padding: 8px;">finder2@email.com</td>
                </tr>
                <?php
                    // Example of dynamic found item listing
                    $foundItems = [
                        ["Umbrella", "Black folding umbrella.", "Outside Auditorium", "2025-06-04", "finder3@email.com"],
                    ];
                    foreach ($foundItems as $item) {
                        echo "<tr>";
                        echo "<td style='padding: 8px;'>" . htmlspecialchars($item[0]) . "</td>";
                        echo "<td style='padding: 8px;'>" . htmlspecialchars($item[1]) . "</td>";
                        echo "<td style='padding: 8px;'>" . htmlspecialchars($item[2]) . "</td>";
                        echo "<td style='padding: 8px;'>" . htmlspecialchars($item[3]) . "</td>";
                        echo "<td style='padding: 8px;'>" . htmlspecialchars($item[4]) . "</td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>

        <h2 id="report-lost">Report a Lost Item</h2>
        <form action="#" method="post">
            <label for="lostItem">Item Name:</label><br>
            <input type="text" id="lostItem" name="lostItem" required><br><br>
            <label for="lostDesc">Description:</label><br>
            <textarea id="lostDesc" name="lostDesc" rows="3" cols="50"></textarea><br><br>
            <label for="lostLocation">Last Seen Location:</label><br>
            <input type="text" id="lostLocation" name="lostLocation"><br><br>
            <label for="lostDate">Date Lost:</label><br>
            <input type="date" id="lostDate" name="lostDate" required><br><br>
            <label for="lostContact">Your Email/Phone:</label><br>
            <input type="text" id="lostContact" name="lostContact" required><br><br>
            <input type="submit" value="Report Lost Item">
        </form>

        <h2 id="report-found">Report a Found Item</h2>
        <form action="#" method="post">
            <label for="foundItem">Item Name:</label><br>
            <input type="text" id="foundItem" name="foundItem" required><br><br>
            <label for="foundDesc">Description:</label><br>
            <textarea id="foundDesc" name="foundDesc" rows="3" cols="50"></textarea><br><br>
            <label for="foundLocation">Found Location:</label><br>
            <input type="text" id="foundLocation" name="foundLocation"><br><br>
            <label for="foundDate">Date Found:</label><br>
            <input type="date" id="foundDate" name="foundDate" required><br><br>
            <label for="foundContact">Your Email/Phone:</label><br>
            <input type="text" id="foundContact" name="foundContact" required><br><br>
            <input type="submit" value="Report Found Item">
        </form>

        <h2 id="faq">Frequently Asked Questions</h2>
        <h3>Q: How long will found items be kept?</h3>
        <p>A: Found items are typically kept for 30 days. After this period, they may be donated or disposed of.</p>
        <h3>Q: What if I found an item but can't report it immediately?</h3>
        <p>A: Please report it as soon as possible. The sooner an item is reported, the higher the chance it will be returned to its owner.</p>
    </div>
</body>
</html>
