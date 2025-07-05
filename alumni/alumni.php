<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Define root color variables for the updated theme */
        :root {
            --alumni-gold: #FFD700; /* Standard Gold */
            --alumni-dark-gold: #B8860B; /* Darker Gold for accents */
            --alumni-silver: #C0C0C0; /* Keep some silver for contrast or subtle elements */
            --alumni-dark-silver: #778899; /* Light Slate Gray for a darker shade */
            --alumni-light-bg: #f8f8f8; /* Very light background for content area */
            --alumni-text-dark: #333; /* Dark text for readability */
            --alumni-text-medium: #666; /* Medium gray for paragraphs */
            --alumni-link-hover-bg: rgba(255, 255, 255, 0.4); /* Light transparent white for link hover */
            --alumni-link-active-bg: white; /* Solid white for active link background */
            --alumni-logout-red: #DC3545; /* Bootstrap's danger red */
            --alumni-logout-red-dark: #C82333; /* Darker red for hover */
            --transition-speed: 0.3s ease; /* Smooth transition for hover effects */
        }

        /* Universal box-sizing for consistent layout and Inter font */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        /* Body layout using flexbox to arrange sidebar and main content */
        body {
            display: flex;
            min-height: 100vh; /* Full viewport height to ensure footer/content extends */
            background-color: var(--alumni-light-bg); /* Light background for the page */
            overflow-x: hidden; /* Prevent horizontal scrolling */
        }

        /* Left Sidebar Styling for Alumni Page - Gold/Silver Theme */
        .alumni-sidebar {
            width: 250px; /* Slightly wider for better text display */
            background: linear-gradient(160deg, var(--alumni-dark-gold), var(--alumni-gold)); /* Gold gradient for a richer look */
            padding: 20px 15px;
            border-right: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 2px 0 15px rgba(0,0,0,0.15); /* More pronounced shadow */
            display: flex;
            flex-direction: column;
            flex-shrink: 0; /* Prevent sidebar from shrinking when content grows */
            color: var(--alumni-text-dark); /* Ensure dark text on gold background */
        }

        .alumni-sidebar h3 {
            color: white; /* White text for the title on gold */
            margin-top: 0;
            margin-bottom: 25px; /* More space below title */
            font-size: 1.3rem;
            text-align: center;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2); /* Subtle text shadow for pop */
        }

        .alumni-sidebar h3 i {
            font-size: 1.5rem; /* Larger icon for the title */
            color: var(--alumni-gold); /* Gold icon inside a darker circle */
            background-color: var(--alumni-dark-gold); /* Darker gold circle */
            padding: 8px;
            border-radius: 50%;
            box-shadow: inset 0 0 5px rgba(0,0,0,0.3); /* Inner shadow for depth */
        }

        .alumni-sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .alumni-sidebar ul li {
            margin-bottom: 8px; /* Slightly less margin for denser list */
        }

        .alumni-sidebar ul li a {
            display: flex; /* Use flex for icon and text alignment */
            align-items: center;
            background-color: rgba(255, 255, 255, 0.15); /* Light transparent white for links */
            color: white; /* White text for contrast on gold background */
            text-decoration: none;
            padding: 12px 15px; /* More padding for larger clickable area */
            border-radius: 8px; /* Rounded corners */
            transition: var(--transition-speed); /* Smooth transitions */
            font-weight: 500;
            gap: 12px; /* Space between icon and text */
            position: relative; /* For active indicator */
            overflow: hidden; /* For smooth hover effect */
        }

        /* Hover effect for alumni sidebar links */
        .alumni-sidebar ul li a::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.25); /* Slightly more opaque on hover */
            z-index: 0;
            opacity: 0;
            transition: opacity var(--transition-speed);
        }

        .alumni-sidebar ul li a:hover {
            color: white;
            transform: translateX(5px); /* Slight slide on hover */
            background-color: rgba(255, 255, 255, 0.3); /* Slightly more opaque on hover */
        }

        .alumni-sidebar ul li a:hover::before {
            opacity: 1;
        }

        .alumni-sidebar ul li a i {
            font-size: 1.1rem;
            min-width: 20px; /* Fixed width for icons */
            text-align: center;
            transition: var(--transition-speed);
            z-index: 1; /* Keep icon above pseudo-element */
        }

        .alumni-sidebar ul li a:hover i {
            transform: scale(1.1); /* Slight scale on icon hover */
        }

        /* Active link styling for alumni sidebar */
        .alumni-sidebar ul li a.active {
            background-color: var(--alumni-link-active-bg); /* Solid white background */
            color: var(--alumni-dark-gold); /* Darker gold text for active link */
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transform: translateX(5px);
            font-weight: 600; /* Bolder for active link */
        }

        .alumni-sidebar ul li a.active i {
            color: var(--alumni-dark-gold); /* Icon color matches text */
        }

        /* Active link indicator (left border) for alumni sidebar */
        .alumni-sidebar ul li a.active::after {
            content: '';
            position: absolute;
            left: -8px; /* Position slightly outside the link */
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 70%; /* Taller indicator */
            background: var(--alumni-dark-gold); /* Color of the active indicator */
            border-radius: 0 4px 4px 0;
            box-shadow: 0 0 10px var(--alumni-dark-gold); /* Glow effect */
        }

        /* Logout button specific styling */
        .alumni-sidebar ul li #alumni-logout-link {
            background-color: var(--alumni-logout-red);
            color: white;
            margin-top: 20px; /* Add some space above logout */
        }

        .alumni-sidebar ul li #alumni-logout-link:hover {
            background-color: var(--alumni-logout-red-dark);
            transform: translateX(0); /* No slide for logout to make it feel more distinct */
        }

        .alumni-sidebar ul li #alumni-logout-link.active {
            background-color: var(--alumni-logout-red); /* Keep red when active if it were a current page */
            color: white;
        }

        .alumni-sidebar ul li #alumni-logout-link::after {
            background: transparent; /* No active indicator for logout */
            box-shadow: none;
        }


        /* Main Content Area Styling */
        .alumni-content {
            flex-grow: 1; /* Takes remaining space */
            padding: 30px; /* More padding for content */
            background-color: #ffffff; /* White background for content block */
            border-radius: 12px; /* Rounded corners for the content block */
            box-shadow: 0 0 20px rgba(0,0,0,0.08); /* Soft shadow */
            margin: 20px; /* Margin around the content block */
            min-height: calc(100vh - 40px); /* Ensure it takes up vertical space, accounting for margins */
            display: flex;
            flex-direction: column;
        }

        .alumni-content h1 {
            color: var(--alumni-dark-gold); /* Gold heading */
            margin-bottom: 20px;
            font-size: 2.2rem;
            font-weight: 700;
        }

        .alumni-content h2 {
            color: var(--alumni-dark-gold); /* Use a shade of gold for subheadings */
            margin-top: 30px;
            margin-bottom: 15px;
            font-size: 1.6rem;
            font-weight: 600;
            border-bottom: 2px solid rgba(184, 134, 11, 0.3); /* Subtle underline with gold */
            padding-bottom: 5px;
        }

        .alumni-content h3 {
            color: var(--alumni-text-dark);
            margin-top: 25px;
            margin-bottom: 10px;
            font-size: 1.4rem;
            font-weight: 600;
        }

        .alumni-content p {
            color: var(--alumni-text-medium);
            line-height: 1.7;
            margin-bottom: 15px;
        }

        .alumni-content ul {
            list-style: none; /* Remove default bullet points */
            padding-left: 0;
            margin-bottom: 20px;
        }

        .alumni-content ul li {
            position: relative;
            padding-left: 25px; /* Space for custom bullet */
            margin-bottom: 8px;
            color: var(--alumni-text-dark);
            line-height: 1.5;
        }

        .alumni-content ul li::before {
            content: '\2022'; /* Unicode bullet point */
            color: var(--alumni-gold); /* Gold color for bullets */
            font-size: 1.5em; /* Larger bullet */
            position: absolute;
            left: 0;
            top: -2px; /* Adjust vertical alignment */
        }

        .alumni-content strong {
            color: var(--alumni-dark-gold); /* Highlight strong text in darker gold */
        }

        .alumni-content a {
            color: var(--alumni-dark-gold);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .alumni-content a:hover {
            text-decoration: underline;
            color: var(--alumni-gold);
        }

        /* Community Section Specific Styling (Placeholder for database content) */
        .community-member-card {
            background-color: var(--alumni-light-bg);
            border: 1px solid var(--alumni-silver);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .community-member-card .member-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: var(--alumni-gold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            flex-shrink: 0;
        }

        .community-member-card .member-info h4 {
            margin: 0 0 5px 0;
            color: var(--alumni-dark-gold);
            font-size: 1.2rem;
        }

        .community-member-card .member-info p {
            margin: 0;
            font-size: 0.95rem;
            color: var(--alumni-text-medium);
        }

        /* Responsive adjustments for smaller screens */
        @media (max-width: 768px) {
            body {
                flex-direction: column; /* Stack sidebar and content vertically */
            }
            .alumni-sidebar {
                width: 100%; /* Full width sidebar on mobile */
                height: auto; /* Height adjusts to content */
                border-right: none;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                padding: 15px;
            }
            .alumni-sidebar h3 {
                margin-bottom: 15px;
            }
            .alumni-sidebar ul {
                display: flex; /* Arrange links horizontally */
                flex-wrap: wrap; /* Allow links to wrap to next line */
                justify-content: center;
            }
            .alumni-sidebar ul li {
                margin: 5px; /* Adjust spacing for horizontal layout */
            }
            .alumni-sidebar ul li a {
                padding: 10px 12px;
                gap: 8px;
                font-size: 0.9rem;
            }
            .alumni-sidebar ul li a i {
                font-size: 1rem;
                min-width: 18px;
            }
            .alumni-sidebar ul li a.active::after {
                height: 40%; /* Smaller indicator for smaller links */
                left: -4px; /* Adjust position */
            }
            .alumni-content {
                margin: 10px; /* Smaller margin on mobile */
                padding: 20px; /* Adjust content padding */
            }
            .alumni-content h1 {
                font-size: 1.8rem;
            }
            .alumni-content h2 {
                font-size: 1.4rem;
            }
            .alumni-content h3 {
                font-size: 1.2rem;
            }

            .community-member-card {
                flex-direction: column; /* Stack elements vertically on small screens */
                text-align: center;
            }
            .community-member-card .member-avatar {
                margin-bottom: 10px;
            }
        }

        @media (max-width: 480px) {
            .alumni-sidebar ul {
                flex-direction: column; /* Stack links vertically again on very small screens */
                align-items: stretch; /* Stretch links to full width */
            }
            .alumni-sidebar ul li {
                margin: 5px 0; /* Vertical margin only */
            }
        }
    </style>
</head>
<body>

    <div class="alumni-sidebar">
        <h3><i class="fas fa-graduation-cap"></i> Alumni Portal</h3>
        <ul>
            <li><a href="alumni.php" class="active" id="alumni-home-link"><i class="fas fa-home"></i> Alumni Home</a></li>
            <li><a href="book.php" id="alumni-book-link"><i class="fas fa-book-open"></i> Book Page</a></li>
            <li><a href="post_read.php" id="alumni-post-read-link"><i class="fas fa-file-alt"></i> Post Read Page</a></li>
            <li><a href="wellness.php" id="alumni-wellness-link"><i class="fas fa-heartbeat"></i> Wellness Page</a></li>
            <li><a href="../login.php" id="alumni-logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="alumni-content">
        <h1>Welcome to the Alumni Page</h1>
        <p>This is the main content area for the Alumni section of the website. It serves as a central hub for updates, news, and valuable resources tailored specifically for our esteemed alumni community.</p>

        <h2>Current Date:</h2>
        <p id="currentAlumniDate">Loading date...</p> 

        <p>Feel free to explore the various sections using the navigation links on the left. We encourage you to engage with the content and reconnect with fellow alumni!</p>
        
        <hr>

        <h2>Alumni Community Board</h2>
        <p>Connect with your fellow alumni! Here are some recent posts and members from our vibrant community.</p>

        <div id="alumni-community-posts">
            <div class="community-member-card">
                <div class="member-avatar"><i class="fas fa-user-circle"></i></div>
                <div class="member-info">
                    <h4>Sarah Lee ('10)</h4>
                    <p>Looking for collaborators on a new renewable energy startup. Anyone interested in sustainable tech?</p>
                </div>
            </div>
            <div class="community-member-card">
                <div class="member-avatar"><i class="fas fa-user-circle"></i></div>
                <div class="member-info">
                    <h4>Dr. Alex Kim ('95)</h4>
                    <p>Seeking interns for a summer research project in AI and machine learning. Computer Science majors, let's connect!</p>
                </div>
            </div>
            <div class="community-member-card">
                <div class="member-avatar"><i class="fas fa-user-circle"></i></div>
                <div class="member-info">
                    <h4>Maria Garcia ('18)</h4>
                    <p>Just launched my online art gallery! Check it out at <a href="#">mariagarciaart.com</a>. Would love alumni support!</p>
                </div>
            </div>
        </div>
        <p><a href="community_full_board.php">View All Community Posts</a> | <a href="post_new_community.php">Create New Community Post</a></p>

        <hr>

        <h3>Upcoming Alumni Events</h3>
        <ul>
            <li><strong>Annual Reunion Gala:</strong> Join us for our grand annual reunion! Enjoy dinner, entertainment, and networking opportunities. <br>Date: September 15, 2025 | Venue: Grand Ball Room</li>
            <li><strong>Career Networking Mixer:</strong> Expand your professional network and find mentorship opportunities. Connect with alumni in diverse industries. <br>Date: October 20, 2025 | Venue: University Conference Center</li>
            <li><strong>Webinar Series: "Alumni Insights":</strong> A monthly series featuring successful alumni sharing their expertise and career journeys. <br>Starts: November 5, 2025 | Platform: Online (Registration required)</li>
        </ul>

        <h3>Alumni News & Updates</h3>
        <p>Stay informed about the latest achievements, milestones, and initiatives within our alumni network. We regularly share inspiring success stories, new projects, and various opportunities to get involved. From community service drives to guest lectures, there's always something happening!</p>
        <p>Check back often for the freshest news and exciting developments. If you have personal news, an achievement, or an upcoming event you'd like to share with the community, please don't hesitate to <a href="mailto:alumni-office@example.com">contact the alumni office</a>. We love hearing from you!</p>
        
        <h3>Alumni Spotlight</h3>
        <p>Each month, we feature an outstanding alumnus/alumna who has made significant contributions in their field or community. This month, we're proud to highlight <strong>Dr. Priya Sharma ('08)</strong>, a leading environmental scientist, for her groundbreaking work in sustainable urban development. Read more about her journey and impact on our <a href="alumni_spotlight.php">Alumni Spotlight page</a>.</p>

        <h3>Resources for Alumni</h3>
        <ul>
            <li><strong>Career Services:</strong> Access exclusive job boards, career counseling, and professional development workshops.</li>
            <li><strong>Mentorship Program:</strong> Become a mentor or find one to guide your career path.</li>
            <li><strong>Alumni Directory:</strong> Search for and connect with classmates and fellow graduates.</li>
            <li><strong>Volunteer Opportunities:</strong> Give back to your alma mater and current students.</li>
            <li><strong>Continuing Education:</strong> Explore discounted courses and programs for lifelong learning.</li>
        </ul>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to set the active link in the alumni sidebar
            function setActiveAlumniLink() {
                // Get the current page filename (e.g., 'alumni.php', 'book.php')
                const currentPath = window.location.pathname.split('/').pop();
                const alumniNavLinks = document.querySelectorAll('.alumni-sidebar ul li a');

                alumniNavLinks.forEach(link => {
                    link.classList.remove('active'); // First, remove 'active' from all links to ensure only one is active

                    let linkHref = link.getAttribute('href');
                    
                    // Logic to determine if the link is active based on the current page
                    // Special handling for 'alumni.php' as the main page, also covers empty path or root index.
                    if ((linkHref === 'alumni.php' && (currentPath === 'alumni.php' || currentPath === '' || currentPath === 'index.html'))) {
                        link.classList.add('active');
                    } else if (linkHref && currentPath && linkHref.includes(currentPath)) {
                        link.classList.add('active');
                    }
                });
            }

            setActiveAlumniLink(); // Call this function when the page loads

            // Function to dynamically display the current date in the content area
            function updateAlumniDate() {
                const dateElement = document.getElementById('currentAlumniDate');
                if (dateElement) {
                    const now = new Date();
                    // Format the date for a clear display
                    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                    dateElement.textContent = now.toLocaleDateString('en-US', options);
                }
            }
            updateAlumniDate(); // Call once immediately to show the date
            // No need for setInterval for just date, as it doesn't change frequently.
            // If you needed to display time, then setInterval would be appropriate.
        });
    </script>
</body>
</html>