<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Club Events</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* --- Variables --- */
        :root {
            --primary-light: #e0f7fa;
            --primary-medium: #b2ebf2;
            --primary-dark: #80deea;
            --accent-light: #00bcd4;
            --accent-medium: #00acc1;
            --accent-dark: #00838f;
            --text-dark: #0d4650;
            --text-green: #00695c;
            --gradient-1: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-medium) 50%, var(--primary-dark) 100%);
            --gradient-accent: linear-gradient(90deg, #00bcd4, #26c6da);
            --shadow-light: 0 4px 15px rgba(0, 0, 0, 0.1);
            --shadow-medium: 0 8px 30px rgba(0, 188, 212, 0.15);
            --shadow-heavy: 0 15px 45px rgba(0, 0, 0, 0.25);
            --border-color: rgba(0, 188, 212, 0.3);
            --bg-blue: rgba(0, 188, 212, 0.05);
            --primary-pale: rgba(0, 188, 212, 0.1);
            --success-color: #10b981;
            --warning-color: #fbbf24;
            --error-color: #ef4444;
            --event-card-bg: rgba(255, 255, 255, 0.8); /* New variable for event card background */
            --event-title-color: #00838f; /* Darker accent for titles */
        }

        /* --- Base Styles --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--gradient-1);
            min-height: 100vh;
            color: var(--text-dark);
            position: relative;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Animated background elements */
        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            background: rgba(0, 188, 212, 0.08);
            z-index: -1;
            filter: blur(10px);
        }

        body::before {
            top: -10%;
            left: -10%;
            width: 50vw;
            height: 50vw;
            animation: floatShape 25s ease-in-out infinite;
        }

        body::after {
            bottom: -10%;
            right: -10%;
            width: 60vw;
            height: 60vw;
            animation: floatShape 20s ease-in-out infinite reverse;
        }

        @keyframes floatShape {
            0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.7; }
            50% { transform: translate(20px, 30px) scale(1.05); opacity: 0.4; }
        }

        /* --- Navbar Styles --- */
        .navbar {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(25px);
            border-bottom: 1px solid var(--border-color);
            padding: 18px 40px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-medium);
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 1.8em;
            font-weight: 800;
            color: var(--accent-dark);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .navbar-brand i {
            color: var(--accent-medium);
            font-size: 1.1em;
        }

        .navbar-links {
            display: flex;
            gap: 22px;
        }

        .navbar-links a {
            text-decoration: none;
            color: var(--accent-medium);
            font-weight: 600;
            font-size: 1.05em;
            position: relative;
            transition: all 0.3s ease;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .navbar-links a::after {
            content: '';
            position: absolute;
            bottom: -7px;
            left: 50%;
            width: 0;
            height: 3px;
            background: var(--gradient-accent);
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .navbar-links a:hover {
            color: var(--accent-dark);
            transform: translateY(-3px);
        }

        .navbar-links a:hover::after {
            width: 100%;
        }

        /* --- Main Content Styles --- */
        .main-container {
            max-width: 900px;
            margin: 60px auto;
            padding: 40px 20px;
            text-align: center;
            flex-grow: 1;
            width: 100%;
        }

        h2 {
            font-size: 3.2rem;
            font-weight: 800;
            margin-bottom: 25px;
            text-shadow: 0 5px 12px rgba(0, 0, 0, 0.12);
            background: linear-gradient(135deg, #00838f, #00acc1, #26c6da);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            animation: slideDown 1s ease-out forwards;
        }

        h2 i {
            font-size: 0.9em;
            color: #00acc1;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-60px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- Events List Styles --- */
        .events-list-container {
            width: 100%;
            margin-top: 30px;
        }

        .event-container {
            background: var(--event-card-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 15px;
            box-shadow: var(--shadow-medium);
            text-align: left;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .event-container:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-heavy);
        }

        .event-container h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: var(--event-title-color);
            font-size: 1.8em;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .event-container h3 i {
            color: var(--accent-medium);
            font-size: 0.9em;
        }

        .event-container p {
            margin-bottom: 10px;
            line-height: 1.6;
            color: var(--text-dark);
            font-size: 1.05em;
        }

        .event-container p strong {
            color: var(--accent-dark);
            font-weight: 600;
        }

        .event-media {
            margin-top: 20px;
            margin-bottom: 10px;
            text-align: center;
            background-color: var(--primary-pale);
            padding: 15px;
            border-radius: 10px;
            border: 1px solid rgba(0, 188, 212, 0.2);
            overflow: hidden; /* Ensures content doesn't spill */
        }

        .event-media img, .event-media video {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .event-media iframe {
            width: 100%;
            max-width: 600px; /* Adjusted max-width for better display */
            height: 340px; /* Adjusted height for standard aspect ratio */
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .event-media p a {
            color: var(--accent-medium);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .event-media p a:hover {
            color: var(--accent-dark);
            text-decoration: underline;
        }

        .no-events-message {
            text-align: center;
            font-size: 1.4em;
            color: var(--text-dark);
            margin-top: 50px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 10px;
            box-shadow: var(--shadow-light);
        }

        /* --- Footer --- */
        .footer {
            margin-top: auto;
            padding: 30px 20px;
            text-align: center;
            color: var(--text-dark);
            font-size: 0.9em;
            background: rgba(255, 255, 255, 0.1);
            width: 100%;
            border-top: 1px solid rgba(0, 188, 212, 0.1);
        }

        /* --- Responsive Design --- */
        @media (max-width: 992px) {
            .navbar {
                padding: 18px 20px;
            }
            .navbar-brand {
                font-size: 1.6em;
            }
            .navbar-links a {
                font-size: 1em;
            }
            h2 {
                font-size: 2.8rem;
            }
            .main-container {
                padding: 30px 15px;
            }
            .event-container {
                padding: 20px;
            }
            .event-container h3 {
                font-size: 1.6em;
            }
            .event-container p {
                font-size: 1em;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
                padding: 15px 20px;
            }
            .navbar-brand {
                margin-bottom: 15px;
            }
            .navbar-links {
                flex-direction: column;
                width: 100%;
                gap: 10px;
            }
            .navbar-links a {
                padding: 8px 0;
                text-align: left;
                width: 100%;
                border-bottom: 1px solid rgba(0, 188, 212, 0.1);
            }
            .navbar-links a:last-child {
                border-bottom: none;
            }
            .navbar-links a::after {
                left: 0;
                transform: translateX(0);
                width: 0;
            }
            .navbar-links a:hover::after {
                width: 30%;
            }

            h2 {
                font-size: 2.2rem;
                flex-direction: column;
                gap: 5px;
            }
            h2 i {
                font-size: 1em;
            }
            .main-container {
                margin: 40px auto;
                padding: 20px 10px;
            }
            .event-container {
                padding: 18px;
            }
            .event-container h3 {
                font-size: 1.4em;
            }
            .event-media iframe {
                height: 250px; /* Adjust height for smaller screens */
            }
        }

        @media (max-width: 480px) {
            .navbar-brand {
                font-size: 1.4em;
            }
            .navbar-links a {
                font-size: 0.9em;
            }
            h2 {
                font-size: 1.8rem;
            }
            .main-container {
                padding: 15px;
            }
            .event-container {
                padding: 15px;
            }
            .event-container h3 {
                font-size: 1.2em;
            }
            .event-container p {
                font-size: 0.95em;
            }
            .event-media iframe {
                height: 200px; /* Further adjust height for very small screens */
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="club_home.php" class="navbar-brand">
            <i class="fas fa-users-crown"></i> Talent Post
        </a>
        <div class="navbar-links">
            <a href="club_home.php"><i class="fas fa-home"></i> Home</a>
            <a href="club_post.php"><i class="fas fa-plus-circle"></i> Submit Club Post</a>
            <a href="club_posts.php"><i class="fas fa-th-list"></i> View Club Posts</a>
            <a href="club_event_post.php"><i class="fas fa-calendar-plus"></i> Submit Event</a>
            <a href="club_events.php"><i class="fas fa-calendar-alt"></i> View Events</a>
        </div>
    </nav>

    <div class="main-container">
        <h2>
            <i class="fas fa-calendar-alt"></i> Upcoming Club Events
        </h2>

        <div class="events-list-container">
            <?php
            $club_events_json_file = 'club_events.json';
            $club_events = [];

            if (file_exists($club_events_json_file)) {
                $json_data = file_get_contents($club_events_json_file);
                $club_events = json_decode($json_data, true);
                if ($club_events === null) {
                    $club_events = [];
                }
            }

            if (empty($club_events)) {
                echo "<p class='no-events-message'><i class='fas fa-exclamation-circle'></i> No upcoming club events yet. Be the first to add one!</p>";
            } else {
                // Filter out past events and sort upcoming events by date
                $current_timestamp = time();
                $upcoming_events = array_filter($club_events, function($event) use ($current_timestamp) {
                    // Combine date and time into a single timestamp for comparison
                    $event_datetime_str = $event['event_date'] . ' ' . $event['event_time'];
                    $event_timestamp = strtotime($event_datetime_str);
                    return $event_timestamp >= $current_timestamp;
                });

                // Sort upcoming events chronologically by date and time
                usort($upcoming_events, function($a, $b) {
                    $datetime_a = strtotime($a['event_date'] . ' ' . $a['event_time']);
                    $datetime_b = strtotime($b['event_date'] . ' ' . $b['event_time']);
                    return $datetime_a - $datetime_b;
                });


                if (empty($upcoming_events)) {
                    echo "<p class='no-events-message'><i class='fas fa-calendar-times'></i> No upcoming club events. All events have passed.</p>";
                } else {
                    foreach ($upcoming_events as $event) {
                        echo "<div class='event-container'>";
                        echo "<h3><i class='fas fa-calendar-day'></i> " . htmlspecialchars($event['event_title']) . " - " . htmlspecialchars($event['club_name']) . "</h3>";
                        echo "<p><strong><i class='fas fa-calendar-alt'></i> Date:</strong> " . htmlspecialchars($event['event_date']) . "</p>";
                        echo "<p><strong><i class='fas fa-clock'></i> Time:</strong> " . htmlspecialchars($event['event_time']) . "</p>";
                        echo "<p><strong><i class='fas fa-map-marker-alt'></i> Location:</strong> " . nl2br(htmlspecialchars($event['location'])) . "</p>";
                        echo "<p><strong><i class='fas fa-info-circle'></i> Description:</strong> " . nl2br(htmlspecialchars($event['description'])) . "</p>";

                        echo "<div class='event-media'>";
                        if ($event['media_type'] === 'image') {
                            echo "<img src='" . htmlspecialchars($event['media_path']) . "' alt='Event Image'>";
                        } elseif ($event['media_type'] === 'video') {
                            echo "<video controls><source src='" . htmlspecialchars($event['media_path']) . "' type='video/mp4'>Your browser does not support the video tag.</video>";
                        } elseif ($event['media_type'] === 'link') {
                            $link = htmlspecialchars($event['media_path']);
                            // Original code had a hardcoded '1' and '2' in YouTube URLs, which is incorrect.
                            // Assuming the intent was to correctly embed YouTube videos.
                            // The following logic correctly extracts video ID and embeds.
                            if (strpos($link, 'youtube.com/watch?v=') !== false) {
                                $video_id = explode('v=', $link)[1];
                                $video_id = explode('&', $video_id)[0];
                                echo "<iframe src='https://www.youtube.com/embed/" . $video_id . "' allowfullscreen></iframe>";
                            } elseif (strpos($link, 'youtu.be/') !== false) {
                                $video_id = explode('youtu.be/', $link)[1];
                                $video_id = explode('?', $video_id)[0]; // Remove any query parameters
                                echo "<iframe src='https://www.youtube.com/embed/" . $video_id . "' allowfullscreen></iframe>";
                            } elseif (strpos($link, 'vimeo.com/') !== false) {
                                $video_id = explode('vimeo.com/', $link)[1];
                                echo "<iframe src='https://player.vimeo.com/video/" . $video_id . "' allowfullscreen></iframe>";
                            } else {
                                echo "<p><i class='fas fa-link'></i> <a href='" . $link . "' target='_blank'>" . $link . "</a></p>";
                            }
                        }
                        echo "</div>";
                        echo "</div>"; // End event-container
                    }
                }
            }
            ?>
        </div>
    </div>

    <footer class="footer">
        <p><i class="fas fa-copyright"></i> 2025 Talent Post. All rights reserved.</p>
    </footer>
</body>
</html>