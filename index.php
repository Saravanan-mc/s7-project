<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --primary-light: rgba(67, 97, 238, 0.1);
            --secondary: #3f37c9;
            --dark: #1f2937;
            --darker: #111827;
            --light: #f9fafb;
            --lighter: #ffffff;
            --gray: #6b7280;
            --gray-light: #e5e7eb;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --transition-speed: 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            --nav-item-delay: 0.05s;

            /* Adjusted Blue Colors */
            --sidebar-blue: #1d4ed8; /* Tailwind blue-700 */
            --sidebar-blue-dark: #1e3a8a; /* Tailwind blue-900 or similar */
            --gradient-start: #2563eb; /* Tailwind blue-600 */
            --gradient-end: #1d4ed8; /* Tailwind blue-700 */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        body {
            display: flex;
            background-color: #f0f2f5; /* A light background for better visibility */
            min-height: 100vh;
            overflow-x: hidden;
        }

        body.sidebar-collapsed {
            --sidebar-width: var(--sidebar-collapsed-width);
        }

        /* Sidebar Styling */
        #sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(160deg, var(--sidebar-blue-dark), var(--sidebar-blue));
            color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            transform: translateX(-100%);
            transition: transform var(--transition-speed), width var(--transition-speed);
            z-index: 1000;
            box-shadow: 5px 0 30px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        #sidebar.loaded {
            transform: translateX(0);
        }

        .sidebar-header {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 80px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.2);
        }

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
            border-radius: 12px;
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
            transition: opacity var(--transition-speed);
        }

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

        /* Sidebar Navigation */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0.5rem;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.1) transparent;
        }

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

        .nav-section {
            margin-bottom: 1.5rem;
            overflow: hidden;
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

        /* Sidebar Links */
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
            white-space: nowrap;
            position: relative;
            transform-origin: left center;
            opacity: 0;
            transform: translateX(-20px);
            animation: slideIn 0.4s var(--transition-speed) forwards;
        }

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
            z-index: 1;
        }

        .link-text {
            transition: opacity var(--transition-speed), transform var(--transition-speed);
            position: relative;
            z-index: 1;
        }

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
            color: white;
            transform: translateX(5px) scale(1.02);
        }

        .sidebar-link:hover i {
            transform: scale(1.15);
            color: white;
        }

        .sidebar-link:hover::before {
            opacity: 0.2;
        }

        .sidebar-link.active {
            background: white;
            color: var(--sidebar-blue); /* Active link text color */
            box-shadow: 0 4px 20px rgba(255, 255, 255, 0.2);
            transform: translateX(5px);
        }

        .sidebar-link.active i {
            color: var(--sidebar-blue); /* Active icon color */
            transform: scale(1.1);
        }

        .sidebar-link.active::before {
            opacity: 1;
        }

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
            animation: pulse 2s infinite;
        }

        /* Logout button specific styles */
        #logout {
            background: rgba(239, 68, 68, 0.1);
            color: rgba(239, 68, 68, 0.9);
        }
        
        #logout:hover {
            background: rgba(239, 68, 68, 0.2);
            color: var(--danger);
        }
        
        #logout.active {
            background: var(--danger);
            color: white;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        /* Dropdown Menu */
        .dropdown-parent {
            position: relative;
        }

        .dropdown-toggle::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 0.7rem;
            margin-left: auto;
            transition: transform 0.3s;
        }

        .dropdown-parent.active .dropdown-toggle::after {
            transform: rotate(180deg);
        }

        .dropdown {
            display: none;
            padding-left: 1.5rem;
            padding-top: 0.5rem;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { 
                opacity: 0; 
                transform: translateY(-10px); 
                max-height: 0;
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
                max-height: 500px; /* Sufficiently large to reveal content */
            }
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

        /* Show dropdown when active */
        .dropdown-parent.active .dropdown {
            display: block;
        }

        /* Badge for updates */
        .badge {
            background-color: var(--warning);
            color: white;
            font-size: 0.75rem;
            font-weight: bold;
            padding: 0.2em 0.6em;
            border-radius: 9999px;
            margin-left: auto;
            position: relative;
            z-index: 1; /* Ensure badge is above link::before */
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
            background: rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.5s 0.6s both;
        }

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
            background: linear-gradient(135deg, #ffffff, #c8e6c9);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--sidebar-blue); /* Avatar text color */
            transition: all 0.3s;
        }

        .user-profile:hover .user-avatar {
            transform: rotate(15deg) scale(1.1);
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

        /* Collapsed sidebar styles */
        body.sidebar-collapsed #sidebar {
            width: var(--sidebar-collapsed-width);
        }

        body.sidebar-collapsed .logo-text,
        body.sidebar-collapsed .link-text,
        body.sidebar-collapsed .nav-section-title,
        body.sidebar-collapsed .user-name,
        body.sidebar-collapsed .user-role,
        body.sidebar-collapsed .dropdown-toggle::after {
            opacity: 0;
            pointer-events: none;
            white-space: nowrap;
        }

        body.sidebar-collapsed .sidebar-link {
            justify-content: center;
        }

        body.sidebar-collapsed .dropdown-parent.active .dropdown {
            display: none;
        }

        body.sidebar-collapsed .sidebar-header {
            justify-content: center;
        }

        body.sidebar-collapsed .toggle-btn i {
            transform: rotate(180deg);
        }

        /* Menu Toggle Button (for mobile) */
        .menu-toggle {
            position: fixed;
            top: 1rem;
            left: 1rem;
            background: var(--sidebar-blue);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: none; /* Hidden by default, shown on mobile */
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 900;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
            transition: all 0.3s;
            opacity: 0;
            transform: translateY(-20px);
            animation: fadeInDown 0.5s 0.3s forwards;
        }

        @keyframes fadeInDown {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .menu-toggle:hover {
            transform: translateY(0) scale(1.1);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.6);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            #sidebar {
                transform: translateX(-100%);
            }

            #sidebar.active {
                transform: translateX(0);
                box-shadow: 5px 0 30px rgba(0, 0, 0, 0.5);
            }

            .menu-toggle {
                display: flex;
            }

            /* When sidebar is active on mobile, push content */
            body.sidebar-active #main-content {
                margin-left: var(--sidebar-width); /* Adjust based on sidebar width */
            }
        }

        @media (max-width: 768px) {
            #sidebar {
                width: 260px;
            }
        }

        /* Animation delays for sidebar items */
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
        /* Add delays for dropdown items too */
        .dropdown-item:nth-child(1) { animation-delay: calc(11 * var(--nav-item-delay)); }
        .dropdown-item:nth-child(2) { animation-delay: calc(12 * var(--nav-item-delay)); }
        .dropdown-item:nth-child(3) { animation-delay: calc(13 * var(--nav-item-delay)); }
        .dropdown-item:nth-child(4) { animation-delay: calc(14 * var(--nav-item-delay)); }
        .dropdown-item:nth-child(5) { animation-delay: calc(15 * var(--nav-item-delay)); }
        .dropdown-item:nth-child(6) { animation-delay: calc(16 * var(--nav-item-delay)); }
        .dropdown-item:nth-child(7) { animation-delay: calc(17 * var(--nav-item-delay)); }


        /* Main Content Area adjustments */
        #main-content {
            margin-left: var(--sidebar-width); /* Space for the sidebar */
            flex-grow: 1;
            padding: 20px;
            transition: margin-left var(--transition-speed); /* Smooth transition for content shift */
        }

        body.sidebar-collapsed #main-content {
            margin-left: var(--sidebar-collapsed-width); /* Adjust for collapsed sidebar */
        }

        @media (max-width: 992px) {
            #main-content {
                margin-left: 0; /* On mobile, content doesn't push */
            }
        }
    </style>
</head>
<body>
    <button class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
    </button>

    <div id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">Student Portal</div>
            </div>
            <button class="toggle-btn" id="sidebarToggle">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Main</div>
                <a href="index.php" class="sidebar-link active" id="home-link">
                    <i class="fas fa-home"></i>
                    <span class="link-text">Home</span>
                </a>
                <a href="stdhome.php" class="sidebar-link" id="profile-link">
                    <i class="fas fa-user-circle"></i>
                    <span class="link-text">My Profile</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Academics</div>
                <a href="data.php" class="sidebar-link" id="data-analyze-link">
                    <i class="fas fa-chart-line"></i>
                    <span class="link-text">Data Analyze</span>
                </a>
                <a href="book.php" class="sidebar-link" id="book-link">
                    <i class="fas fa-book"></i>
                    <span class="link-text">Book</span>
                </a>
             
                <a href="#" class="sidebar-link" id="library-link">
                    <i class="fas fa-book-reader"></i>
                    <span class="link-text">Library</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Community & Resources</div>
                <a href="post/home.php" class="sidebar-link" id="student-about-link">
                    <i class="fas fa-users"></i>
                    <span class="link-text">Student About</span>
                </a>
                <a href="wellness/index.html" class="sidebar-link" id="wellness-link">
                    <i class="fas fa-heartbeat"></i>
                    <span class="link-text">Wellness</span>
                </a>
                <a href="lostandfound/index.php" class="sidebar-link" id="lost-and-find-link">
                    <i class="fas fa-search-dollar"></i>
                    <span class="link-text">Lost and Found</span>
                </a>
                <a href="club/home.php" class="sidebar-link" id="club-post-link">
                    <i class="fas fa-puzzle-piece"></i>
                    <span class="link-text">Club Post</span>
                </a>
                <a href="talent/home.html" class="sidebar-link" id="talent-post-link">
                    <i class="fas fa-star"></i>
                    <span class="link-text">Talent Post</span>
                </a>

                
                <a href="#" class="sidebar-link" id="notifications-link">
                    <i class="fas fa-bell"></i>
                    <span class="link-text">Notifications</span>
                    <span class="badge">5</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Support</div>
                <a href="feedback.php" class="sidebar-link" id="feedback-link">
                    <i class="fas fa-comment-alt"></i>
                    <span class="link-text">Feedback</span>
                </a>
                <a href="login.php" class="sidebar-link" id="logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="link-text">Logout</span>
                </a>
            </div>
        </nav>

        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar">SR</div>
                <div class="user-info">
                    <div class="user-name">Student Name</div>
                    <div class="user-role">UG Student</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.getElementById('menuToggle');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const body = document.body;
            const mainContent = document.getElementById('main-content'); // Get main content

            // Initialize sidebar with animation
            setTimeout(() => {
                sidebar.classList.add('loaded');
            }, 100);
            
            // Handle dropdown toggle
            const quicklinksDropdown = document.getElementById('quicklinks-dropdown');
            const quicklinksToggle = document.getElementById('quicklinks-toggle');

            if (quicklinksToggle) {
                quicklinksToggle.addEventListener('click', function(event) {
                    event.preventDefault();
                    quicklinksDropdown.classList.toggle('active');
                });
            }
            
            // Handle mobile menu toggle
            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    this.classList.toggle('active');
                    body.classList.toggle('sidebar-active'); // Add class to body for content shift
                });
            }
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (quicklinksDropdown && !quicklinksDropdown.contains(event.target) && quicklinksToggle && !quicklinksToggle.contains(event.target)) {
                    quicklinksDropdown.classList.remove('active');
                }
            });
            
            // Add active class to current page link
            const currentPage = window.location.pathname.split('/').pop() || 'index.php'; // Updated to index.php
            const navLinks = document.querySelectorAll('.sidebar-link, .dropdown-item');
            
            navLinks.forEach(link => {
                const linkHref = link.getAttribute('href');
                // Adjust for relative paths like 'update/index.html'
                const normalizedLinkHref = linkHref.includes('/') ? linkHref.split('/').pop() : linkHref;

                if (normalizedLinkHref === currentPage) {
                    link.classList.add('active');
                    
                    // If it's a dropdown item, activate its parent and expand the dropdown
                    if (link.classList.contains('dropdown-item')) {
                        const parentDropdown = link.closest('.dropdown-parent');
                        if (parentDropdown) {
                            parentDropdown.classList.add('active');
                        }
                    }
                }
            });
            
            // Toggle sidebar collapse (for desktop)
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    body.classList.toggle('sidebar-collapsed');
                });
            }
            
            // Close sidebar when clicking on main content (mobile)
            if (mainContent) {
                mainContent.addEventListener('click', function() {
                    if (window.innerWidth <= 992 && sidebar.classList.contains('active')) {
                        sidebar.classList.remove('active');
                        menuToggle.classList.remove('active');
                        body.classList.remove('sidebar-active');
                    }
                });
            }
        });
    </script>
</body>
</html>