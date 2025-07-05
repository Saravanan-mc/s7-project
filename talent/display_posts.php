<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Talent Showcase - View All Posts</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* --- Variables (from Talent Showcase Home page) --- */
        :root {
            --primary-light: #e3f2fd; /* Light Blue */
            --primary-medium: #bbdefb; /* Medium Blue */
            --primary-dark: #90caf9; /* Darker Blue */
            --accent-light: #42a5f5; /* Vibrant Blue */
            --accent-medium: #2196f3; /* Strong Blue */
            --accent-dark: #1976d2; /* Deep Blue */
            --text-dark: #1a237e; /* Dark Indigo */
            --text-green: #283593; /* Medium Indigo for contrast */
            --gradient-1: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-medium) 50%, var(--primary-dark) 100%);
            --gradient-accent: linear-gradient(90deg, var(--accent-light), var(--accent-medium));
            --shadow-light: 0 4px 15px rgba(0, 0, 0, 0.1);
            --shadow-medium: 0 8px 30px rgba(33, 150, 243, 0.15); /* Adjusted shadow color */
            --shadow-heavy: 0 15px 45px rgba(0, 0, 0, 0.25);
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
            background: rgba(33, 150, 243, 0.08); /* Adjusted color */
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
            border-bottom: 1px solid rgba(33, 150, 243, 0.3); /* Adjusted color */
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
            display: flex; /* Added for icon alignment */
            align-items: center; /* Added for icon alignment */
            gap: 6px; /* Space between icon and text */
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

        /* Active link styling (applied by dynamically adding 'active' class) */
        .navbar-links a.active {
            color: var(--accent-dark);
            font-weight: 700;
        }
        .navbar-links a.active::after {
            width: 100%;
        }


        /* --- Main Content Styles --- */
        .main-container {
            max-width: 1200px;
            margin: 60px auto;
            padding: 40px 20px;
            text-align: center; /* Center the overall content */
            flex-grow: 1;
            width: 100%; /* Ensure it takes full width within its max-width */
        }

        h1 {
            font-size: 4.2rem;
            font-weight: 800;
            margin-bottom: 25px;
            text-shadow: 0 5px 12px rgba(0, 0, 0, 0.12);
            background: linear-gradient(135deg, var(--accent-dark), var(--accent-medium), var(--accent-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            animation: slideDown 1s ease-out forwards;
        }

        h1 i {
            font-size: 0.9em;
            color: var(--accent-medium);
        }
        
        .page-subtitle {
            font-size: 1.5rem;
            color: var(--text-green);
            margin-bottom: 50px;
            max-width: 850px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.7;
            animation: fadeIn 1.2s ease-out 0.4s both;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-60px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- Post Card Styles --- */
        .posts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
            text-align: left; /* Align text within cards */
        }

        .post-card {
            background: #fff;
            border: 1px solid var(--primary-medium);
            border-radius: 12px;
            padding: 25px;
            box-shadow: var(--shadow-medium);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Ensures footer sticks to bottom */
        }

        .post-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-heavy);
        }

        .post-card h3 {
            font-size: 1.8em;
            color: var(--accent-dark);
            margin-bottom: 10px;
            word-wrap: break-word; /* Prevent long titles from overflowing */
        }

        .post-card p {
            font-size: 1.1em;
            color: var(--text-dark);
            margin-bottom: 15px;
            line-height: 1.5;
            word-wrap: break-word;
        }

        .post-card .uploaded-content {
            margin-top: 15px;
            margin-bottom: 15px;
            text-align: center; /* Center media */
        }

        .post-card .uploaded-content img,
        .post-card .uploaded-content video,
        .post-card .uploaded-content audio {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto; /* Center media */
            border-radius: 8px;
            box-shadow: var(--shadow-light);
        }

        .post-card .uploaded-content audio {
            width: 100%; /* Make audio player fill width */
        }

        .post-card .uploaded-content p {
            font-size: 0.9em;
            color: #666;
            margin-top: 10px;
        }

        .post-card .uploaded-content a {
            color: var(--accent-medium);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .post-card .uploaded-content a:hover {
            color: var(--accent-dark);
            text-decoration: underline;
        }

        .post-card .external-link {
            font-size: 0.95em;
            margin-top: 10px;
            word-wrap: break-word;
        }

        .post-card .external-link a {
            color: var(--accent-medium);
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .post-card .external-link a:hover {
            color: var(--accent-dark);
            text-decoration: underline;
        }

        .post-card .post-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px dashed var(--primary-medium);
            font-size: 0.9em;
            color: #666;
        }

        .post-card .post-author {
            font-weight: 600;
            color: var(--text-dark);
        }

        .post-card .post-date {
            font-style: italic;
            color: #888;
        }

        /* --- Message for no posts --- */
        .no-posts-message {
            font-size: 1.4em;
            color: var(--text-green);
            padding: 50px;
            border: 2px dashed var(--primary-dark);
            border-radius: 10px;
            max-width: 600px;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.5);
            box-shadow: var(--shadow-light);
        }

        /* --- Footer (Optional but good practice) --- */
        .footer {
            margin-top: auto; /* Pushes footer to the bottom */
            padding: 30px 20px;
            text-align: center;
            color: var(--text-dark);
            font-size: 0.9em;
            background: rgba(255, 255, 255, 0.1);
            width: 100%;
            border-top: 1px solid rgba(33, 150, 243, 0.1); /* Adjusted color */
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
            h1 {
                font-size: 3.5rem;
            }
            .page-subtitle {
                font-size: 1.3rem;
            }
            .post-card h3 {
                font-size: 1.6em;
            }
            .post-card p {
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
                border-bottom: 1px solid rgba(33, 150, 243, 0.1);
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

            h1 {
                font-size: 2.8rem;
                flex-direction: column;
                gap: 5px;
            }
            h1 i {
                font-size: 1em;
            }
            .page-subtitle {
                font-size: 1.1rem;
                padding: 0 15px;
                margin-bottom: 40px;
            }
            .posts-grid {
                grid-template-columns: 1fr;
                padding: 0 15px;
                gap: 20px;
            }
            .post-card {
                padding: 20px;
            }
            .no-posts-message {
                font-size: 1.2em;
                padding: 30px;
            }
        }

        @media (max-width: 480px) {
            .navbar-brand {
                font-size: 1.4em;
            }
            .navbar-links a {
                font-size: 0.9em;
            }
            h1 {
                font-size: 2.2rem;
            }
            .page-subtitle {
                font-size: 1rem;
            }
            .main-container {
                padding: 40px 10px;
            }
            .post-card h3 {
                font-size: 1.4em;
            }
            .post-card p {
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="home.html" class="navbar-brand">
            <i class="fas fa-sparkles"></i> Talent Showcase
        </a>
        <div class="navbar-links">
            <a href="home.html"><i class="fas fa-house"></i> Home</a>
            <a href="post_talent.php"><i class="fas fa-cloud-arrow-up"></i> Post Your Talent</a>
            <a href="display_posts.php" class="active"><i class="fas fa-magnifying-glass"></i> View All Posts</a>
        </div>
    </nav>

    <div class="main-container">
        <h1>
            <i class="fas fa-magnifying-glass"></i> All Student Posts
        </h1>
        <p class="page-subtitle">
            Explore the incredible talents shared by students across our community. From art and music to coding and design, dive into a world of creativity and innovation.
        </p>

        <section id="student-posts-list" class="posts-grid">
            <?php
            $logFile = 'data.log';
            $uploadDir = 'uploads/';

            if (file_exists($logFile) && is_readable($logFile)) {
                $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                if (empty($lines)) {
                    echo '<p class="no-posts-message">No talent posts yet. Be the first to share your amazing work!</p>';
                } else {
                    foreach (array_reverse($lines) as $line) { // Display newest first
                        $data = explode('|||', $line); // Split by the delimiter

                        if (count($data) >= 6) { // Ensure all fields are present
                            list($timestamp, $rollNumber, $name, $description, $uploadedFile, $externalLink) = $data;
                            
                            echo '<div class="post-card">';
                            echo '<h3>' . htmlspecialchars($name) . '</h3>';
                            echo '<p>' . nl2br(htmlspecialchars($description)) . '</p>'; // nl2br to preserve line breaks
                            
                            if (!empty($uploadedFile) && file_exists($uploadDir . $uploadedFile)) {
                                $filePath = $uploadDir . $uploadedFile;
                                $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                                echo '<div class="uploaded-content">';
                                if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                    echo '<img src="' . htmlspecialchars($filePath) . '" alt="Uploaded Image">';
                                } elseif (in_array($fileExtension, ['mp4', 'webm', 'ogg'])) {
                                    echo '<video controls><source src="' . htmlspecialchars($filePath) . '" type="video/' . $fileExtension . '">Your browser does not support the video tag.</video>';
                                } elseif (in_array($fileExtension, ['mp3', 'wav', 'ogg'])) {
                                    echo '<audio controls><source src="' . htmlspecialchars($filePath) . '" type="audio/' . $fileExtension . '">Your browser does not support the audio tag.</audio>';
                                } else {
                                    echo '<p>Download: <a href="' . htmlspecialchars($filePath) . '" download>' . htmlspecialchars($uploadedFile) . '</a></p>';
                                }
                                echo '</div>';
                            }

                            if (!empty($externalLink)) {
                                echo '<p class="external-link">External Link: <a href="' . htmlspecialchars($externalLink) . '" target="_blank"><i class="fas fa-external-link-alt"></i> ' . htmlspecialchars($externalLink) . '</a></p>';
                            }
                            echo '<div class="post-meta">';
                            echo '<span class="post-author">By: ' . htmlspecialchars($rollNumber) . '</span>';
                            echo '<span class="post-date">' . date('M d, Y H:i', $timestamp) . '</span>';
                            echo '</div>'; // End post-meta
                            echo '</div>'; // End post-card
                        }
                    }
                }
            } else {
                echo '<p class="no-posts-message">No talent posts yet. The data log file does not exist or is not readable. Please ensure the "data.log" file and "uploads" directory are correctly configured.</p>';
            }
            ?>
        </section>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Student Talent Showcase. All rights reserved.</p>
    </footer>

    <script>
        // Simple client-side script to apply 'active' class based on current URL
        document.addEventListener('DOMContentLoaded', () => {
            const currentPath = window.location.pathname.split('/').pop();
            const navLinks = document.querySelectorAll('.navbar-links a');
            navLinks.forEach(link => {
                const linkPath = link.getAttribute('href').split('/').pop();
                if (linkPath === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
