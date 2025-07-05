<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Talent Showcase - Post Your Talent</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* --- CSS Variables (Consolidated from home.html and post_talent.php) --- */
        :root {
            /* Colors */
            --primary-light: #e3f2fd; /* Light Blue */
            --primary-medium: #bbdefb; /* Medium Blue */
            --primary-dark: #90caf9; /* Darker Blue */
            
            --accent-light: #42a5f5; /* Lighter Accent Blue */
            --accent-medium: #2196f3; /* Medium Accent Blue (Primary Accent) */
            --accent-dark: #1976d2; /* Darker Accent Blue */

            --text-dark: #1a237e; /* Very Dark Blue for main text/headings */
            --text-secondary: #283593; /* Darker Blue for secondary text/paragraphs */
            
            --form-bg-light: #fdfdfe; /* Light background for forms/cards */
            --form-border-light: #d1e3f8; /* Light border for form elements */

            /* Gradients */
            --gradient-main: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-medium) 50%, var(--primary-dark) 100%);
            --gradient-accent: linear-gradient(90deg, var(--accent-light), var(--accent-medium));
            --gradient-accent-hover: linear-gradient(90deg, var(--accent-medium), var(--accent-light));
            --gradient-button-submit: linear-gradient(135deg, #1e88e5, #42a5f5); /* Specific for submit */
            --gradient-button-submit-hover: linear-gradient(135deg, #42a5f5, #1e88e5);

            /* Shadows */
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 5px 15px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 10px 25px rgba(33, 150, 243, 0.1);
            --shadow-xl: 0 15px 45px rgba(33, 150, 243, 0.2);

            /* Borders */
            --border-navbar: 1px solid rgba(33, 150, 243, 0.3);
            --border-feature: 1px solid rgba(33, 150, 243, 0.2);
            --border-link-bottom: 1px solid rgba(33, 150, 243, 0.1);
            --border-color: #cbd5e1; /* For form inputs/upload area */

            /* Radius */
            --radius-sm: 4px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
        }

        /* --- Base Styles --- */
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--gradient-main);
            min-height: 100vh;
            color: var(--text-dark);
            position: relative;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Animated background elements (from home.html) */
        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            background: rgba(33, 150, 243, 0.08);
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

        /* --- Navbar Styles (from home.html, adapted for consistency) --- */
        .navbar {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(25px);
            border-bottom: var(--border-navbar);
            padding: 18px 40px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-md); /* Changed from --shadow-medium */
            width: 100%;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
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
        
        .navbar-links a:focus {
            outline: 2px solid var(--accent-medium);
            outline-offset: 3px;
            border-radius: 2px;
        }

        .navbar-links a.active {
            color: var(--accent-dark);
            font-weight: 700;
        }

        .navbar-links a.active::after {
            width: 100%;
        }

        /* --- Main Content Layout --- */
        .main-container {
            max-width: 900px; /* Adjusted for form content */
            margin: 60px auto;
            padding: 40px 20px;
            flex-grow: 1;
            width: 100%;
            text-align: center; /* Center the page header */
        }

        /* --- Page Header Styles --- */
        .page-header {
            margin-bottom: 40px;
        }

        .page-title {
            font-family: 'Poppins', sans-serif; /* Using Poppins for titles */
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 15px;
            text-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, var(--accent-dark), var(--accent-medium), var(--accent-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            animation: fadeInUp 0.8s ease-out both; /* Apply animation to title */
        }

        .page-title i {
            font-size: 0.9em;
            color: var(--accent-medium);
        }

        .page-subtitle {
            font-size: 1.25rem;
            color: var(--text-secondary);
            line-height: 1.6;
            max-width: 700px;
            margin: 0 auto;
            animation: fadeInUp 0.8s ease-out 0.2s both; /* Delayed animation for subtitle */
        }

        /* --- Form Section Styles --- */
        .form-section {
            background: var(--form-bg-light);
            border-radius: var(--radius-xl);
            padding: 3.5rem 3rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--form-border-light);
            text-align: left; /* Align form elements to the left */
            margin-top: 30px; /* Spacing from page header */
            animation: fadeInUp 0.8s ease-out 0.4s both; /* Animation for the form */
        }

        .section-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--accent-dark);
            margin-bottom: 2.5rem;
            text-align: center; /* Center section title within the form card */
            position: relative;
            padding-bottom: 15px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--gradient-accent);
            border-radius: 2px;
        }

        .form-group {
            margin-bottom: 2rem;
            position: relative; /* For the focused class effect */
        }

        .form-group label {
            display: block;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: color 0.3s ease;
        }

        .form-group label i {
            color: var(--accent-medium);
            font-size: 1.1em;
            transition: color 0.3s ease;
        }

        .form-input {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            color: var(--text-secondary);
            background-color: #f8faff;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        .form-input:focus {
            border-color: var(--accent-medium);
            box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.2);
            outline: none;
            background-color: #ffffff;
        }

        .form-input::placeholder {
            color: #94a3b8;
            opacity: 0.9;
        }

        textarea.form-input {
            resize: vertical;
            min-height: 120px;
        }

        /* Form Group Focus state for label/icon */
        .form-group.focused label {
            color: var(--accent-dark);
        }
        .form-group.focused label i {
            color: var(--accent-dark);
        }

        /* File Upload */
        .file-upload-area {
            border: 2px dashed var(--border-color);
            border-radius: var(--radius-lg);
            padding: 3rem 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            position: relative;
            overflow: hidden;
        }

        .file-upload-area::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(102, 126, 234, 0.05) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .file-upload-area:hover::before {
            transform: translateX(100%);
        }

        .file-upload-area:hover {
            border-color: #667eea;
            background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%);
            transform: translateY(-2px);
        }
        
        .file-upload-area:focus-within { /* Added for keyboard accessibility */
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
            outline: none;
        }

        .file-upload-area.drag-over {
            border-color: #667eea;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            transform: scale(1.02);
        }

        .upload-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 1rem;
            display: block;
        }

        .upload-text {
            font-weight: 600;
            color: var(--text-dark); /* Changed from --text-primary for consistency */
            margin-bottom: 0.5rem;
        }

        .upload-subtext {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .file-preview {
            display: none;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 1px solid #a7f3d0;
            border-radius: var(--radius-md);
            margin-top: 1rem;
        }

        .file-preview.active {
            display: flex;
        }

        .file-preview i {
            color: #059669;
            font-size: 1.25rem;
        }

        .file-name {
            flex: 1;
            color: var(--text-dark); /* Changed from --text-primary for consistency */
            font-weight: 500;
            word-break: break-all; /* Ensures long filenames wrap */
        }

        .remove-file {
            background: none;
            border: none;
            color: #dc2626;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: var(--radius-sm);
            transition: all 0.3s ease;
            display: flex; /* To center the icon better */
            align-items: center;
            justify-content: center;
        }

        .remove-file:hover {
            background: rgba(220, 38, 38, 0.1);
            transform: scale(1.1);
        }
        
        .remove-file:focus { /* Added for accessibility */
            outline: 2px solid #dc2626;
            outline-offset: 2px;
            border-radius: var(--radius-sm);
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 1.25rem 2rem;
            background: var(--gradient-button-submit); /* Using new variable */
            color: white;
            border: none;
            border-radius: var(--radius-lg);
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-md); /* Adjusted shadow level */
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            position: relative;
            overflow: hidden;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, transparent 100%);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .submit-btn:hover::before {
            transform: translateX(100%);
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-xl);
            background: var(--gradient-button-submit-hover); /* Apply hover gradient */
        }

        .submit-btn:active {
            transform: translateY(-1px);
        }
        
        .submit-btn:focus { /* Added for accessibility */
            outline: 2px solid #fff;
            outline-offset: 4px;
            border-radius: var(--radius-lg);
        }

        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
            box-shadow: var(--shadow-md);
            background: var(--gradient-button-submit); /* Revert background when disabled */
        }

        /* Messages */
        .message {
            padding: 1rem 1.5rem;
            border-radius: var(--radius-md);
            margin-bottom: 2rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-align: left; /* Ensure text aligns left in message box */
            box-shadow: var(--shadow-sm); /* Add a subtle shadow to messages */
            animation: fadeInUp 0.5s ease-out; /* Animation for messages */
        }

        .message.success {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .message.error {
            background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%);
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 2rem;
            color: rgba(255, 255, 255, 0.8);
            background: rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            margin-top: 4rem;
            width: 100%; /* Ensure footer spans full width */
        }

        /* Animations */
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Hidden class */
        .hidden {
            display: none !important;
        }

        /* Responsive */
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
            .page-title {
                font-size: 3rem;
            }
            .page-subtitle {
                font-size: 1.1rem;
                padding: 0 10px;
            }
            .form-section {
                padding: 2.5rem 2rem;
            }
            .section-title {
                font-size: 2rem;
            }
            .form-group label {
                font-size: 1em;
            }
            .form-input {
                padding: 0.9rem 1.1rem;
            }
            .submit-btn {
                padding: 1rem 1.5rem;
                font-size: 1em;
            }
        }

        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            .navbar-brand {
                margin-bottom: 0; /* Remove margin as links will stack below */
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
                border-bottom: var(--border-link-bottom);
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

            .main-container {
                padding: 30px 15px;
            }
            .page-title {
                font-size: 2.5rem;
                flex-direction: column;
                gap: 5px;
            }
            .page-title i {
                font-size: 1em;
            }
            .page-subtitle {
                font-size: 1rem;
                padding: 0 10px;
            }
            .form-section {
                padding: 2rem 1.5rem;
            }
            .section-title {
                font-size: 1.8rem;
            }
            .file-upload-area {
                padding: 2rem 1rem;
            }
            .message {
                padding: 0.8rem 1rem;
                font-size: 0.9em;
            }
        }

        @media (max-width: 480px) {
            .navbar-brand {
                font-size: 1.4em;
            }
            .navbar-links a {
                font-size: 0.9em;
            }
            .page-title {
                font-size: 2rem;
            }
            .page-subtitle {
                font-size: 0.9rem;
            }
            .form-section {
                padding: 1.5rem 1rem;
            }
            .section-title {
                font-size: 1.5rem;
            }
            .form-group label {
                font-size: 0.9em;
            }
            .form-input {
                font-size: 0.9em;
                padding: 0.8rem 1rem;
            }
            .upload-icon {
                font-size: 2.5rem;
            }
            .upload-text {
                font-size: 0.9em;
            }
            .upload-subtext {
                font-size: 0.8em;
            }
            .file-preview {
                flex-direction: column;
                gap: 0.5rem;
                padding: 0.8rem;
            }
            .remove-file {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="home.html" class="navbar-brand">
                <i class="fas fa-sparkles"></i>
                Talent Showcase
            </a>
            <div class="navbar-links">
                <a href="home.html">
                    <i class="fas fa-house"></i>
                    Home
                </a>
                <a href="post_talent.php" class="active">
                    <i class="fas fa-cloud-arrow-up"></i>
                    Post Your Talent
                </a>
                <a href="display_posts.php">
                    <i class="fas fa-magnifying-glass"></i> View All Posts
                </a>
            </div>
        </div>
    </nav>

    <div class="main-container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-cloud-arrow-up"></i>
                Share Your Talent
            </h1>
            <p class="page-subtitle">
                Showcase your unique skills and creative projects with the entire student community. 
                Fill out the form below to share your amazing talent with everyone!
            </p>
        </div>

        <section class="form-section">
            <h2 class="section-title">Submit Your Talent Details</h2>
            
            <div id="php-messages">
                <?php
                    // PHP simulation for messages - In a real scenario, this would come from server-side
                    // Example: if (isset($_SESSION['success_message'])) {
                    // echo '<div class="message success"><i class="fas fa-check-circle"></i> ' . $_SESSION['success_message'] . '</div>';
                    // unset($_SESSION['success_message']); }
                    // if (isset($_SESSION['error_message'])) {
                    // echo '<div class="message error"><i class="fas fa-exclamation-triangle"></i> ' . $_SESSION['error_message'] . '</div>';
                    // unset($_SESSION['error_message']); }
                    // For demonstration, uncomment the line below to see a simulated message
                    // echo '<div class="message success"><i class="fas fa-check-circle"></i> Your talent has been submitted successfully! (Simulated)</div>';
                    // echo '<div class="message error"><i class="fas fa-exclamation-triangle"></i> Oops! Something went wrong. Please try again. (Simulated)</div>';
                ?>
            </div>

            <form action="post_talent.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="roll_number">
                        <i class="fas fa-id-badge"></i>
                        Roll Number
                    </label>
                    <input type="text" id="roll_number" name="roll_number" class="form-input" required 
                           placeholder="Enter your roll number">
                </div>

                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-user"></i>
                        Full Name
                    </label>
                    <input type="text" id="name" name="name" class="form-input" required 
                           placeholder="Enter your full name">
                </div>

                <div class="form-group">
                    <label for="description">
                        <i class="fas fa-file-alt"></i>
                        Talent Description
                    </label>
                    <textarea id="description" name="description" class="form-input" required 
                                 placeholder="Describe your talent, skills, or creative project in detail..."></textarea>
                </div>

                <div class="form-group">
                    <label>
                        <i class="fas fa-cloud-arrow-up"></i>
                        Upload Media (Max 40MB)
                    </label>
                    <div class="file-upload-area" id="fileUploadArea">
                        <i class="fas fa-file-arrow-up upload-icon"></i>
                        <div class="upload-text">Drag and drop your file here, or click to browse</div>
                        <div class="upload-subtext">Supported: JPG, PNG, GIF, WebP, MP4, WebM, Ogg</div>
                        <input type="file" id="media_file" name="media_file" class="hidden" 
                                 accept="image/*,video/*">
                    </div>
                    <div class="file-preview" id="filePreview">
                        <i class="fas fa-file-check"></i>
                        <span class="file-name" id="fileName"></span>
                        <button type="button" class="remove-file" id="removeFile" aria-label="Remove selected file">
                            <i class="fas fa-times-circle"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="external_link">
                        <i class="fas fa-link"></i>
                        External Link (Optional)
                    </label>
                    <input type="url" id="external_link" name="external_link" class="form-input" 
                           placeholder="https://yourportfolio.com or social media link">
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i>
                    Submit Your Talent
                </button>
            </form>
        </section>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Student Talent Showcase. Empowering creativity and innovation.</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Script for applying 'active' class to navbar links
            const currentPath = window.location.pathname.split('/').pop();
            const navLinks = document.querySelectorAll('.navbar-links a');

            navLinks.forEach(link => {
                const linkPath = link.getAttribute('href').split('/').pop();
                if (linkPath === currentPath) {
                    link.classList.add('active');
                }
            });

            // File upload functionality
            const fileUploadArea = document.getElementById('fileUploadArea');
            const mediaFileInput = document.getElementById('media_file');
            const filePreview = document.getElementById('filePreview');
            const fileName = document.getElementById('fileName');
            const removeFileBtn = document.getElementById('removeFile');

            // Click to upload
            fileUploadArea.addEventListener('click', function() {
                mediaFileInput.click();
            });

            // File input change
            mediaFileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    showFilePreview(this.files[0]);
                } else {
                    hideFilePreview();
                }
            });

            // Drag and drop
            fileUploadArea.addEventListener('dragover', function(e) {
                e.preventDefault(); // Prevent default to allow drop
                this.classList.add('drag-over');
            });

            fileUploadArea.addEventListener('dragleave', function() {
                this.classList.remove('drag-over');
            });

            fileUploadArea.addEventListener('drop', function(e) {
                e.preventDefault(); // Prevent default file open behavior
                this.classList.remove('drag-over');
                
                if (e.dataTransfer.files.length > 0) {
                    // Assign files from dataTransfer to the input's files property
                    mediaFileInput.files = e.dataTransfer.files;
                    showFilePreview(e.dataTransfer.files[0]);
                }
            });

            // Remove file
            removeFileBtn.addEventListener('click', function() {
                hideFilePreview();
                mediaFileInput.value = ''; // Clear the file input
            });

            function showFilePreview(file) {
                fileName.textContent = file.name;
                filePreview.classList.add('active');
                fileUploadArea.style.display = 'none';
            }

            function hideFilePreview() {
                filePreview.classList.remove('active');
                fileUploadArea.style.display = 'block';
            }

            // Form validation and enhancement (focus state)
            const form = document.querySelector('form');
            const inputs = form.querySelectorAll('.form-input');

            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.closest('.form-group').classList.add('focused'); // Use closest for robustness
                });

                input.addEventListener('blur', function() {
                    this.closest('.form-group').classList.remove('focused');
                });
            });

            // Add loading state to submit button
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('.submit-btn');
                const originalText = submitBtn.innerHTML;
                
                // Client-side validation check before showing loading state
                if (form.checkValidity()) { 
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
                    submitBtn.disabled = true;
                }
                
                // Important: In a real PHP scenario, you'd remove the setTimeout
                // and re-enable the button either after a successful PHP redirect
                // or via an AJAX response. This setTimeout is purely for client-side simulation.
                setTimeout(() => {
                    if (submitBtn.disabled) { // Only re-enable if still disabled by this script
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                }, 3000); 
            });

            // Function to display PHP messages (call this from PHP)
            function displayMessage(type, text) {
                const messagesContainer = document.getElementById('php-messages');
                // Clear existing messages before adding new ones (optional, depends on UX)
                messagesContainer.innerHTML = ''; 

                const messageDiv = document.createElement('div');
                messageDiv.className = `message ${type}`;
                
                const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
                messageDiv.innerHTML = `<i class="fas ${icon}"></i> ${text}`;
                
                messagesContainer.appendChild(messageDiv);
                
                // Auto-remove after 5 seconds
                setTimeout(() => {
                    messageDiv.remove();
                }, 5000);
            }

            // Example usage (replace with actual PHP output based on server response)
            // To test: uncomment one of these lines in your browser's console
            // displayMessage('success', 'Your talent has been submitted successfully!');
            // displayMessage('error', 'Please ensure all required fields are filled and file size is within limits.');
        });
    </script>
</body>
</html>