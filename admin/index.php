<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Admin Dashboard</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS Variables for consistent theming. These define colors, sizes, and animation speeds. */
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --primary-light: rgba(67, 97, 238, 0.1);
            --secondary: #3f37c9;
            --dark: #1f2937;
            --darker: #111827;
            --light: #f9fafb; /* Light background for the content area */
            --lighter: #ffffff;
            --gray: #6b7280;
            --gray-light: #e5e7eb;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --sidebar-width: 280px; /* Default sidebar width */
            --sidebar-collapsed-width: 80px; /* Width when collapsed */
            --transition-speed: 0.4s cubic-bezier(0.16, 1, 0.3, 1); /* Smooth animation speed */
            --nav-item-delay: 0.05s; /* Delay for staggered link animations */
            --sidebar-green: #1a5632; /* The "normal green" for the sidebar */
            --sidebar-green-dark: #0d3b1e; /* Darker green for gradient */
        }

        /* Basic CSS Reset and Font Styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        /* Body Layout: Uses flexbox to arrange sidebar and main content */
        body {
            display: flex;
            min-height: 100vh; /* Full viewport height */
            background-color: var(--light);
            transition: margin-left var(--transition-speed); /* Smooth transition for content shift */
            overflow-x: hidden; /* Prevent horizontal scrollbar */
            position: relative; /* Needed for absolute positioning of the mobile menu toggle */
        }

        /* Adjust body margin when sidebar is collapsed (desktop) */
        body.sidebar-collapsed {
            --sidebar-width: var(--sidebar-collapsed-width); /* Override sidebar width variable */
        }

        /* Sidebar Styling */
        #sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(160deg, var(--sidebar-green-dark), var(--sidebar-green)); /* Green gradient background */
            color: white;
            height: 100vh;
            position: fixed; /* Fixed position on the left */
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            transform: translateX(-100%); /* Initially hidden off-screen for animation */
            transition: transform var(--transition-speed), width var(--transition-speed);
            z-index: 1000; /* Ensure sidebar is above content */
            box-shadow: 5px 0 30px rgba(0, 0, 0, 0.3); /* Subtle shadow */
            overflow: hidden; /* Hide overflowing content during collapse */
        }

        /* Animation for sidebar appearing on load */
        #sidebar.loaded {
            transform: translateX(0);
        }

        /* Sidebar Header: Contains logo and toggle button */
        .sidebar-header {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 80px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08); /* Separator line */
            background: rgba(0, 0, 0, 0.2); /* Semi-transparent overlay */
        }

        /* Logo: Icon and text */
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            overflow: hidden;
        }

        .logo-icon {
            font-size: 1.8rem;
            color: white;
            background: rgba(255, 255, 255, 0.15);
            width: 42px;
            height: 42px;
            border-radius: 12px; /* Rounded corners */
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all var(--transition-speed);
            flex-shrink: 0;
        }

        .logo-text {
            font-weight: 700;
            font-size: 1.25rem;
            color: white;
            transition: opacity var(--transition-speed); /* Fade out when collapsed */
        }

        /* Sidebar Toggle Button (desktop) */
        .toggle-btn {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            flex-shrink: 0;
        }

        .toggle-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(15deg);
        }

        /* Sidebar Navigation Area */
        .sidebar-nav {
            flex: 1; /* Takes remaining vertical space */
            overflow-y: auto; /* Enable scrolling for many links */
            padding: 1rem 0.5rem;
            scrollbar-width: thin; /* Firefox scrollbar styling */
            scrollbar-color: rgba(255,255,255,0.1) transparent;
        }

        /* Custom scrollbar for Webkit browsers */
        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.25);
        }

        /* Navigation Section Titles */
        .nav-section {
            margin-bottom: 1.5rem;
            overflow: hidden; /* Helps with text fading during collapse */
        }

        .nav-section-title {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 1rem;
            margin-bottom: 0.75rem;
            transition: opacity var(--transition-speed);
            white-space: nowrap;
        }

        /* Sidebar Links (common styles for all navigation items) */
        .sidebar-link {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.85);
            padding: 0.85rem 1rem;
            margin: 0.25rem 0;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.95rem;
            gap: 12px;
            white-space: nowrap; /* Prevent text wrapping */
            position: relative;
            transform-origin: left center;
            opacity: 0; /* Hidden by default for slide-in animation */
            transform: translateX(-20px);
            animation: slideIn 0.4s var(--transition-speed) forwards; /* Slide in on load */
        }

        /* Slide-in animation for sidebar links */
        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .sidebar-link i {
            font-size: 1.1rem;
            min-width: 24px;
            text-align: center;
            transition: all 0.3s;
            position: relative;
            z-index: 1; /* Ensure icon is above pseudo-element */
        }

        .link-text {
            transition: opacity var(--transition-speed), transform var(--transition-speed);
            position: relative;
            z-index: 1;
        }

        /* Background hover effect for links */
        .sidebar-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: white;
            border-radius: 8px;
            z-index: 0;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-link:hover {
            color: white; /* Text color on hover */
            transform: translateX(5px) scale(1.02); /* Slight movement and scale */
        }

        .sidebar-link:hover i {
            transform: scale(1.15); /* Icon subtle scale */
            color: white;
        }

        .sidebar-link:hover::before {
            opacity: 0.2; /* Show semi-transparent background */
        }

        /* Active link styling */
        .sidebar-link.active {
            background: white;
            color: var(--sidebar-green); /* Text color for active link */
            box-shadow: 0 4px 20px rgba(255, 255, 255, 0.2);
            transform: translateX(5px);
        }

        .sidebar-link.active i {
            color: var(--sidebar-green);
            transform: scale(1.1);
        }

        .sidebar-link.active::before {
            opacity: 1;
        }

        /* Active link left border with pulse animation */
        .sidebar-link.active::after {
            content: '';
            position: absolute;
            left: -8px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: white;
            border-radius: 0 4px 4px 0;
            animation: pulse 2s infinite; /* Pulsing effect */
        }

        /* Pulse animation for the active link indicator */
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        /* Logout button specific styles */
        #logout {
            background: rgba(239, 68, 68, 0.1); /* Light red background */
            color: rgba(239, 68, 68, 0.9); /* Red text */
        }
        
        #logout:hover {
            background: rgba(239, 68, 68, 0.2);
            color: var(--danger); /* Darker red on hover */
        }
        
        #logout.active {
            background: var(--danger); /* Solid red when active */
            color: white;
        }

        /* Dropdown Menu (for News Sources) */
        .dropdown-parent {
            position: relative;
        }

        /* Arrow icon for dropdown toggle */
        .dropdown-toggle::after {
            content: '\f078'; /* Font Awesome chevron-down icon */
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 0.7rem;
            margin-left: auto; /* Pushes arrow to the right */
            transition: transform 0.3s;
        }

        /* Rotate arrow when dropdown is active */
        .dropdown-parent.active .dropdown-toggle::after {
            transform: rotate(180deg);
        }

        .dropdown {
            display: none; /* Hidden by default */
            padding-left: 1.5rem; /* Indent dropdown items */
            padding-top: 0.5rem;
            overflow: hidden; /* For smooth max-height transition */
            max-height: 0; /* Starts collapsed */
            transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
            opacity: 0; /* Starts invisible */
        }

        /* Show dropdown when parent is active */
        .dropdown-parent.active .dropdown {
            display: block; /* Change display for transition to work */
            max-height: 500px; /* Arbitrarily large value to allow content to show */
            opacity: 1;
        }

        .dropdown-item {
            padding: 0.65rem 1rem;
            display: block;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.9rem;
            border-radius: 6px;
            margin: 0.15rem 0;
            position: relative;
            transform-origin: left center;
        }

        .dropdown-item:hover {
            color: white;
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(5px);
        }

        /* Small dot indicator for dropdown items on hover */
        .dropdown-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 6px;
            height: 6px;
            background: white;
            border-radius: 50%;
            opacity: 0;
            transition: all 0.3s;
        }

        .dropdown-item:hover::before {
            opacity: 1;
            left: 5px;
        }

        .dropdown-item.active {
            color: white;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Sidebar Footer: User profile section */
        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto; /* Pushes footer to the bottom */
            background: rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.5s 0.6s both; /* Delayed fade in for footer */
        }

        /* User Profile: Avatar and info */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s;
        }

        .user-profile:hover {
            transform: translateX(5px);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ffffff, #c8e6c9); /* Light gradient for avatar background */
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--sidebar-green);
            transition: all 0.3s;
            flex-shrink: 0;
        }

        .user-profile:hover .user-avatar {
            transform: rotate(15deg) scale(1.1); /* Avatar rotation on hover */
        }

        .user-info {
            flex: 1;
            overflow: hidden;
        }

        .user-name {
            font-weight: 500;
            font-size: 0.95rem;
            margin-bottom: 0.15rem;
            white-space: nowrap;
            transition: all var(--transition-speed);
        }

        .user-role {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.6);
            white-space: nowrap;
            transition: all var(--transition-speed);
        }

        /* Main Content Area Styling */
        .content {
            flex: 1; /* Takes remaining horizontal space */
            padding: 2rem;
            margin-left: var(--sidebar-width); /* Push content right by sidebar width */
            transition: margin-left var(--transition-speed), filter var(--transition-speed);
            background-color: var(--light); /* Ensure content background is light */
            min-height: 100vh; /* Make content fill height */
            border-radius: 12px; /* Slightly rounded corners for content area */
            box-shadow: 0 0 20px rgba(0,0,0,0.05); /* Subtle shadow */
            margin-top: 1rem;
            margin-bottom: 1rem;
            margin-right: 1rem;
        }

        .content h1, .content h2 {
            color: var(--dark);
            margin-bottom: 1rem;
        }

        .content p {
            color: var(--gray);
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .content ul {
            list-style: none; /* Remove default bullet points */
            padding-left: 0;
            margin-bottom: 1.5rem;
        }

        .content ul li {
            position: relative;
            padding-left: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        .content ul li::before {
            content: '\2022'; /* Custom bullet point */
            color: var(--primary); /* Use primary color for bullets */
            font-size: 1.2em;
            position: absolute;
            left: 0;
            top: 0;
        }

        .content strong {
            color: var(--secondary); /* Highlight strong text */
        }

        /* Collapsed sidebar styles (desktop) */
        body.sidebar-collapsed #sidebar {
            width: var(--sidebar-collapsed-width); /* Collapse sidebar */
        }

        body.sidebar-collapsed .logo-text,
        body.sidebar-collapsed .link-text,
        body.sidebar-collapsed .nav-section-title,
        body.sidebar-collapsed .user-name,
        body.sidebar-collapsed .user-role,
        body.sidebar-collapsed .dropdown-toggle::after {
            opacity: 0; /* Fade out text */
            pointer-events: none; /* Disable interaction */
            white-space: nowrap;
        }

        body.sidebar-collapsed .sidebar-link {
            justify-content: center; /* Center icons when text is hidden */
        }

        /* Hide dropdowns when sidebar is collapsed */
        body.sidebar-collapsed .dropdown-parent.active .dropdown {
            display: none;
        }

        body.sidebar-collapsed .sidebar-header {
            justify-content: center; /* Center logo icon */
        }

        body.sidebar-collapsed .toggle-btn i {
            transform: rotate(180deg); /* Rotate collapse icon */
        }

        /* Mobile Menu Toggle Button */
        .menu-toggle {
            position: fixed;
            top: 1rem;
            left: 1rem;
            background: var(--sidebar-green);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: none; /* Hidden by default, shown only on mobile */
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1001; /* Higher than sidebar */
            box-shadow: 0 4px 15px rgba(26, 86, 50, 0.4);
            transition: all 0.3s;
            opacity: 0;
            transform: translateY(-20px);
            animation: fadeInDown 0.5s 0.3s forwards; /* Fade in and slide down */
        }

        @keyframes fadeInDown {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .menu-toggle:hover {
            transform: translateY(0) scale(1.1);
            box-shadow: 0 6px 20px rgba(26, 86, 50, 0.6);
        }

        /* Responsive Design Media Queries */
        @media (max-width: 992px) {
            #sidebar {
                transform: translateX(-100%); /* Hide sidebar on mobile by default */
            }

            #sidebar.active {
                transform: translateX(0); /* Show sidebar when active (mobile) */
                box-shadow: 5px 0 30px rgba(0, 0, 0, 0.5);
            }

            .content {
                margin-left: 0; /* No left margin on mobile */
                filter: blur(0); /* Initially no blur */
                pointer-events: auto; /* Allow interaction */
                border-radius: 0; /* Remove rounded corners on content for full width */
                margin: 0; /* Remove side margins */
                padding: 1rem; /* Adjust padding for smaller screens */
            }

            /* Apply blur to content when sidebar is active on mobile */
            #sidebar.active ~ .content {
                filter: blur(2px);
                pointer-events: none; /* Disable interaction with content when sidebar is open */
            }

            .menu-toggle {
                display: flex; /* Show mobile menu toggle */
            }
        }

        @media (max-width: 768px) {
            #sidebar {
                width: 260px; /* Slightly narrower sidebar on very small mobiles */
            }
        }

        /* Staggered animation delays for sidebar items */
        .sidebar-link:nth-child(1) { animation-delay: calc(1 * var(--nav-item-delay)); }
        .sidebar-link:nth-child(2) { animation-delay: calc(2 * var(--nav-item-delay)); }
        .sidebar-link:nth-child(3) { animation-delay: calc(3 * var(--nav-item-delay)); }
        .sidebar-link:nth-child(4) { animation-delay: calc(4 * var(--nav-item-delay)); }
        .sidebar-link:nth-child(5) { animation-delay: calc(5 * var(--nav-item-delay)); }
        .sidebar-link:nth-child(6) { animation-delay: calc(6 * var(--nav-item-delay)); }
        .sidebar-link:nth-child(7) { animation-delay: calc(7 * var(--nav-item-delay)); }
        .sidebar-link:nth-child(8) { animation-delay: calc(8 * var(--nav-item-delay)); }
        .sidebar-link:nth-child(9) { animation-delay: calc(9 * var(--nav-item-delay)); }
        .sidebar-link:nth-child(10) { animation-delay: calc(10 * var(--nav-item-delay)); }
        .sidebar-link:nth-child(11) { animation-delay: calc(11 * var(--nav-item-delay)); }
        .sidebar-link:nth-child(12) { animation-delay: calc(12 * var(--nav-item-delay)); }
        .sidebar-link:nth-child(13) { animation-delay: calc(13 * var(--nav-item-delay)); }
        .sidebar-link:nth-child(14) { animation-delay: calc(14 * var(--nav-item-delay)); }
        .sidebar-link:nth-child(15) { animation-delay: calc(15 * var(--nav-item-delay)); }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle Button (appears only on smaller screens) -->
    <button class="menu-toggle" id="menuToggle" aria-label="Toggle navigation menu">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Navigation -->
    <div id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-cube"></i> <!-- Cube icon for logo -->
                </div>
                <div class="logo-text">Admin</div>
            </div>
            <button class="toggle-btn" id="sidebarToggle" aria-label="Collapse sidebar">
                <i class="fas fa-chevron-left"></i> <!-- Left chevron icon for collapsing -->
            </button>
        </div>

        <!-- Navigation Links Section -->
        <nav class="sidebar-nav" aria-label="Main navigation">
            <div class="nav-section">
                <div class="nav-section-title">Main</div>
                <a href="adhome.php" class="sidebar-link" id="home-link">
                    <i class="fas fa-home"></i>
                    <span class="link-text">Home</span>
                </a>
                <a href="admin.php" class="sidebar-link" id="admin-dashboard-link">
                    <i class="fas fa-tachometer-alt"></i> <!-- Dashboard icon -->
                    <span class="link-text">Admin Dashboard</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Content Management</div>
                <a href="advideo.php" class="sidebar-link" id="upload-video">
                    <i class="fas fa-video"></i>
                    <span class="link-text">Upload Video</span>
                </a>
                <a href="adbook.php" class="sidebar-link" id="upload-book">
                    <i class="fas fa-book-medical"></i> <!-- Medical book icon for upload -->
                    <span class="link-text">Upload Book</span>
                </a>
                <a href="adbooks.php" class="sidebar-link" id="borrow-books">
                    <i class="fas fa-exchange-alt"></i> <!-- Exchange icon for borrow -->
                    <span class="link-text">Books Borrow</span>
                </a>
                <a href="book.php" class="sidebar-link" id="book-reading">
                    <i class="fas fa-book-open"></i> <!-- Open book icon for reading -->
                    <span class="link-text">Book Reading</span>
                </a>
                <a href="post_read.php" class="sidebar-link" id="post-read">
                    <i class="fas fa-file-alt"></i> <!-- File alt icon for posts -->
                    <span class="link-text">Post Read</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Community & Features</div>
                <a href="adai.php" class="sidebar-link" id="ai-section">
                    <i class="fas fa-robot"></i> <!-- Robot icon for AI -->
                    <span class="link-text">AI Section</span>
                </a>
                <a href="adsocial.php" class="sidebar-link" id="social-section">
                    <i class="fas fa-users"></i> <!-- Users icon for social -->
                    <span class="link-text">Social</span>
                </a>
                <a href="wellness.php" class="sidebar-link" id="wellness-section">
                    <i class="fas fa-heartbeat"></i> <!-- Heartbeat icon for wellness -->
                    <span class="link-text">Wellness</span>
                </a>
                <a href="club_event.php" class="sidebar-link" id="club-events-section">
                    <i class="fas fa-calendar-alt"></i> <!-- Calendar icon for events -->
                    <span class="link-text">Club Events</span>
                </a>
                <a href="lost_and_find.php" class="sidebar-link" id="lost-found-section">
                    <i class="fas fa-search-dollar"></i> <!-- Search dollar icon for lost & found -->
                    <span class="link-text">Lost & Found</span>
                </a>
                
                <!-- Dropdown Menu for News Sources -->
                <div class="dropdown-parent" id="news-dropdown">
                    <a href="#" class="sidebar-link dropdown-toggle" id="news-toggle" aria-expanded="false" aria-controls="news-menu">
                        <i class="fas fa-newspaper"></i> <!-- Newspaper icon -->
                        <span class="link-text">News Sources</span>
                    </a>
                    <div class="dropdown" id="news-menu">
                        <a href="addailythanthi.php" class="dropdown-item">Dailythanthi</a>
                        <a href="addinamalar.php" class="dropdown-item">Dinamalar</a>
                        <a href="adhindutamil.php" class="dropdown-item">Hindutamil</a>
                        <a href="adjionews.php" class="dropdown-item">Jio News</a>
                        <a href="adnews18.php" class="dropdown-item">News18</a>
                        <a href="adoneindia.php" class="dropdown-item">OneIndia</a>
                    </div>
                </div>
                
                <a href="adupdate.php" class="sidebar-link" id="update-section">
                    <i class="fas fa-bell"></i> <!-- Bell icon for updates -->
                    <span class="link-text">Updates</span>
                    <span class="badge">3</span> <!-- You can add CSS for .badge to style this -->
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">System</div>
                <a href="adfeedback.php" class="sidebar-link" id="feedback-section">
                    <i class="fas fa-comment-alt"></i> <!-- Comment icon for feedback -->
                    <span class="link-text">Feedback</span>
                </a>
                <a href="login.php" class="sidebar-link" id="logout">
                    <i class="fas fa-sign-out-alt"></i> <!-- Sign out icon for logout -->
                    <span class="link-text">Logout</span>
                </a>
            </div>
        </nav>

        <!-- Sidebar Footer: User Profile -->
        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar">AD</div>
                <div class="user-info">
                    <div class="user-name">Admin User</div>
                    <div class="user-role">Super Admin</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area: This is where your home page content will reside -->
    <div class="content" id="mainContent">
        <h1>Welcome to Our Website!</h1>
        <p>This is the main home page. You can navigate to different sections of the site using the sidebar on the left.</p>

        <h2>Current Date and Time:</h2>
        <p id="currentDateTime">Loading...</p> <!-- JavaScript will update this -->

        <h2>About Us</h2>
        <p>We are dedicated to providing useful information and resources across various topics, including administration, literature, well-being, community events, and helpful services like lost and found.</p>

        <h2>Explore Our Sections:</h2>
        <ul>
            <li><strong>Home:</strong> Your central hub for all things on our site.</li>
            <li><strong>Admin Dashboard:</strong> Manage users, settings, and view reports.</li>
            <li><strong>Upload Video:</strong> Share video content with your community.</li>
            <li><strong>Upload Book:</strong> Add new books to the digital library.</li>
            <li><strong>Books Borrow:</strong> Track and manage book borrowing requests.</li>
            <li><strong>Book Reading:</strong> Dive into various online books.</li>
            <li><strong>Post Read:</strong> Explore insightful articles and posts.</li>
            <li><strong>AI Section:</strong> Discover intelligent features and tools.</li>
            <li><strong>Social:</strong> Connect and interact with other community members.</li>
            <li><strong>Wellness:</strong> Find resources and tips for mental and physical health.</li>
            <li><strong>Club Events:</strong> Stay updated and participate in upcoming activities.</li>
            <li><strong>Lost & Found:</strong> Report or search for lost and found items.</li>
            <li><strong>News Sources:</strong> Access various news outlets directly.</li>
            <li><strong>Updates:</strong> Get the latest notifications and announcements.</li>
            <li><strong>Feedback:</strong> Share your thoughts and suggestions to help us improve.</li>
            <li><strong>Logout:</strong> Securely exit your session.</li>
        </ul>

        <h2>Contact Information</h2>
        <p>If you have any questions, feel free to reach out to us at <a href="mailto:info@example.com">info@example.com</a>.</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get references to key DOM elements
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.getElementById('menuToggle'); // Mobile menu toggle button
            const sidebarToggle = document.getElementById('sidebarToggle'); // Desktop sidebar collapse button
            const body = document.body;
            const mainContent = document.getElementById('mainContent');
            const newsDropdownParent = document.getElementById('news-dropdown');
            const newsToggle = document.getElementById('news-toggle'); // News dropdown toggle link
            
            // --- Sidebar Initialization & Animations ---
            // Add 'loaded' class after a short delay to trigger the initial slide-in animation
            setTimeout(() => {
                sidebar.classList.add('loaded');
            }, 100);
            
            // --- Dropdown Functionality for 'News Sources' ---
            if (newsToggle) {
                newsToggle.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent default link behavior (page navigation)
                    newsDropdownParent.classList.toggle('active'); // Toggle 'active' class on parent
                    // Update ARIA expanded attribute for accessibility
                    const isExpanded = newsDropdownParent.classList.contains('active');
                    this.setAttribute('aria-expanded', isExpanded);
                });
            }
            
            // Close the dropdown when clicking anywhere outside of it
            document.addEventListener('click', function(event) {
                // Check if the click occurred outside the dropdown and its toggle
                if (newsDropdownParent && !newsDropdownParent.contains(event.target) && event.target !== newsToggle) {
                    newsDropdownParent.classList.remove('active'); // Remove 'active' class
                    if (newsToggle) newsToggle.setAttribute('aria-expanded', 'false'); // Reset ARIA attribute
                }
            });
            
            // --- Mobile Sidebar Toggle ---
            // Event listener for the mobile menu button (bars icon)
            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active'); // Toggle 'active' class on sidebar for mobile view
                    this.classList.toggle('active'); // Toggle 'active' class on the menu button itself
                });
            }
            
            // Close mobile sidebar when clicking on the main content area (to dismiss it)
            if (mainContent) {
                mainContent.addEventListener('click', function() {
                    // Only close if on a smaller screen and sidebar is currently open
                    if (window.innerWidth <= 992 && sidebar.classList.contains('active')) {
                        sidebar.classList.remove('active');
                        if (menuToggle) menuToggle.classList.remove('active');
                    }
                });
            }

            // --- Desktop Sidebar Collapse Toggle ---
            // Event listener for the sidebar collapse button (chevron icon)
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    body.classList.toggle('sidebar-collapsed'); // Toggle 'sidebar-collapsed' class on the body
                });
            }
            
            // --- Active Link Highlighting Logic ---
            // This function determines the current page and applies the 'active' class to the corresponding sidebar link
            function setActiveLink() {
                // Get the current page filename (e.g., 'adhome.php' or 'index.html')
                const currentPath = window.location.pathname.split('/').pop(); 
                const navLinks = document.querySelectorAll('.sidebar-link, .dropdown-item');
                
                navLinks.forEach(link => {
                    link.classList.remove('active'); // First, remove 'active' from all links to ensure only one is active
                    
                    let linkHref = link.getAttribute('href');

                    // Handle cases for the home page (adhome.php, empty path, or index.html)
                    if ((linkHref === 'adhome.php' && (currentPath === '' || currentPath === 'index.html' || currentPath === 'adhome.php'))) {
                        link.classList.add('active');
                    } else if (linkHref && currentPath && linkHref.includes(currentPath)) {
                        link.classList.add('active');
                        
                        // If the active link is inside a dropdown, also activate its parent dropdown
                        const parentDropdown = link.closest('.dropdown-parent');
                        if (parentDropdown) {
                            parentDropdown.classList.add('active');
                            const parentToggle = parentDropdown.querySelector('.dropdown-toggle');
                            if (parentToggle) parentToggle.setAttribute('aria-expanded', 'true'); // Update ARIA for accessibility
                        }
                    }
                });
            }

            setActiveLink(); // Call this function on initial page load

            // --- Display Current Date and Time in Content Area ---
            function updateDateTime() {
                const dateTimeElement = document.getElementById('currentDateTime');
                if (dateTimeElement) {
                    const now = new Date();
                    const options = { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric', 
                        hour: 'numeric', 
                        minute: 'numeric', 
                        second: 'numeric', 
                        hour12: true // Use 12-hour format with AM/PM
                    };
                    dateTimeElement.textContent = now.toLocaleString('en-US', options); // Format date and time
                }
            }

            updateDateTime(); // Call once immediately to show current time
            setInterval(updateDateTime, 1000); // Update every second for a live clock effect
        });
    </script>
</body>
</html>
