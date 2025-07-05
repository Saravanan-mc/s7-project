<?php
session_start();

// --- PHP Configuration and Logic ---
// DATABASE CONNECTION (Copy from register.php)
define('DB_HOST', 'localhost');
define('DB_NAME', 'regdata');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$db = null;
try {
    $db = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Log the error for debugging, but show a generic message to the user
    // In a production environment, uncomment the error_log line.
    error_log("Database connection error: " . $e->getMessage()); 
    die("A database error occurred. Please try again later.");
}

$err = ""; // Initialize error message for display

// Generate CSRF token for security
// Ensure a token exists in the session for form submission
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Token Validation
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $err = "Security token mismatch. Please try again.";
        // Regenerate token even on mismatch to prevent fixation attacks
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } else {
        // Regenerate CSRF token after successful validation to prevent "double submission" and enhance security
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Always regenerate after processing POST

        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $err = "Both email and password are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $err = "Please enter a valid email address.";
        } else {
            try {
                $stmt = $db->prepare("SELECT id, password, role, name, roll_number FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    // Login successful
                    session_regenerate_id(true); // Prevent Session Fixation
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_roll_number'] = $user['roll_number'] ?? null; // Store roll number, default to null if not present

                    // Redirect based on role
                    switch ($user['role']) {
                        case 'admin':
                            header("Location: admin/admin.php");
                            break;
                        case 'student':
                            header("Location: index.php");
                            break;
                        case 'alumni':
                            header("Location: alumni.php");
                            break;
                        default:
                            // Fallback for unexpected roles or if a role is not explicitly handled
                            header("Location: student_dashboard.php"); // A more generic default
                            break;
                    }
                    exit(); // Always exit after a header redirect
                } else {
                    $err = "Invalid email or password.";
                }
            } catch (PDOException $e) {
                $err = "An error occurred during login. Please try again.";
                // In a production environment, uncomment the error_log line.
                error_log("Login error: " . $e->getMessage()); 
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BITSathy | Ultimate Login Experience</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1; /* Indigo 500 */
            --primary-dark: #4f46e5; /* Indigo 600 */
            --primary-light: #8b5cf6; /* Violet 500 */
            --secondary: #06b6d4; /* Cyan 500 */
            --accent: #f59e0b; /* Amber 500 */
            --success: #10b981; /* Emerald 500 */
            --error: #ef4444; /* Red 500 */
            --warning: #f59e0b; /* Amber 500 */
            --bg-primary: #0f0f23; /* Dark Blue-Purple */
            --bg-secondary: #1a1a2e; /* Slightly Lighter Dark Blue */
            --bg-tertiary: #16213e; /* Another Shade of Dark Blue */
            --text-primary: #f8fafc; /* Cool Gray 50 */
            --text-secondary: #cbd5e1; /* Cool Gray 300 */
            --text-tertiary: #64748b; /* Cool Gray 500 */
            --glass-bg: rgba(255, 255, 255, 0.08);
            --glass-border: rgba(255, 255, 255, 0.12);
            --shadow-glow: 0 0 50px rgba(99, 102, 241, 0.15);
            --shadow-intense: 0 25px 50px -12px rgba(0, 0, 0, 0.8);
            --border-radius: 24px;
            --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            --animation-bounce: cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Animated Gradient Background */
        .gradient-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(-45deg, 
                var(--bg-primary), var(--bg-secondary), var(--bg-tertiary), var(--primary), 
                var(--primary-light), var(--secondary), var(--bg-primary), var(--bg-secondary));
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            z-index: -3;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Floating Geometric Shapes */
        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -2;
            transition: transform 0.2s ease-out; /* For JS parallax */
        }

        .shape {
            position: absolute;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            opacity: 0.1;
            border-radius: 50%;
            animation: float 20s infinite linear;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            left: 10%;
            animation-delay: 0s;
            animation-duration: 25s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            left: 80%;
            animation-delay: 3s;
            animation-duration: 30s;
            clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
            border-radius: 0;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            left: 60%;
            animation-delay: 7s;
            animation-duration: 20s;
            clip-path: polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%);
            border-radius: 0;
        }

        .shape:nth-child(4) {
            width: 100px;
            height: 100px;
            left: 30%;
            animation-delay: 12s;
            animation-duration: 35s;
        }

        .shape:nth-child(5) {
            width: 150px;
            height: 150px;
            left: 70%;
            animation-delay: 18s;
            animation-duration: 28s;
            clip-path: polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%);
            border-radius: 0;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg) scale(0);
                opacity: 0;
            }
            10% {
                opacity: 0.1;
                transform: translateY(90vh) rotate(36deg) scale(1);
            }
            90% {
                opacity: 0.1;
                transform: translateY(-10vh) rotate(324deg) scale(1);
            }
            100% {
                transform: translateY(-20vh) rotate(360deg) scale(0);
                opacity: 0;
            }
        }

        /* Particle System */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            transition: transform 0.2s ease-out; /* For JS parallax */
        }

        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: var(--primary-light);
            border-radius: 50%;
            opacity: 0;
            animation: particleFloat 8s infinite linear;
        }

        /* CSS for particles positioning (spread them out horizontally) */
        .particle:nth-child(1) { left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { left: 20%; animation-delay: 1s; }
        .particle:nth-child(3) { left: 30%; animation-delay: 2s; }
        .particle:nth-child(4) { left: 40%; animation-delay: 3s; }
        .particle:nth-child(5) { left: 50%; animation-delay: 4s; }
        .particle:nth-child(6) { left: 60%; animation-delay: 5s; }
        .particle:nth-child(7) { left: 70%; animation-delay: 6s; }
        .particle:nth-child(8) { left: 80%; animation-delay: 7s; }
        .particle:nth-child(9) { left: 90%; animation-delay: 8s; }
        .particle:nth-child(10) { left: 5%; animation-delay: 0.5s; } /* Slightly offset to add variety */
        .particle:nth-child(11) { left: 95%; animation-delay: 1.5s; }
        .particle:nth-child(12) { left: 25%; animation-delay: 2.5s; }
        .particle:nth-child(13) { left: 75%; animation-delay: 3.5s; }
        .particle:nth-child(14) { left: 45%; animation-delay: 4.5s; }
        .particle:nth-child(15) { left: 55%; animation-delay: 5.5s; }


        @keyframes particleFloat {
            0% {
                transform: translateY(100vh) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-10vh) translateX(100px); /* Add a slight horizontal drift */
                opacity: 0;
            }
        }

        /* Main Container */
        .login-container {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            padding: 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            position: relative;
            z-index: 10;
        }

        /* Left Side - Form */
        .form-section {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius);
            padding: 3rem;
            box-shadow: var(--shadow-intense);
            position: relative;
            overflow: hidden;
            animation: slideInLeft 0.8s var(--animation-bounce);
        }

        .form-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .form-header {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
        }

        .form-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
            background: linear-gradient(135deg, var(--primary-light), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: titleGlow 2s ease-in-out infinite alternate;
        }

        @keyframes titleGlow {
            0% { filter: brightness(1); }
            100% { filter: brightness(1.2) drop-shadow(0 0 20px rgba(99, 102, 241, 0.5)); }
        }

        .form-subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
            font-weight: 400;
        }

        /* Error Message */
        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: errorShake 0.5s ease-in-out;
            font-size: 0.95rem;
        }

        @keyframes errorShake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* Form Inputs */
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .input-container {
            position: relative;
            overflow: hidden;
            border-radius: 16px;
        }

        .form-input {
            width: 100%;
            padding: 1.25rem 1.25rem 1.25rem 3.5rem;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            color: var(--text-primary);
            font-size: 1rem;
            font-weight: 500;
            transition: var(--transition);
            outline: none;
        }

        .form-input::placeholder {
            color: var(--text-tertiary);
        }

        .form-input:focus {
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1), var(--shadow-glow);
            transform: translateY(-2px);
        }

        .input-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-tertiary);
            font-size: 1.1rem;
            transition: var(--transition);
            z-index: 2;
        }

        .form-input:focus + .input-icon {
            color: var(--primary-light);
            transform: translateY(-50%) scale(1.1);
        }

        /* Animated Input Background */
        .input-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.1), transparent);
            transition: left 0.5s ease;
            z-index: 1;
        }

        .form-input:focus ~ .input-container::before {
            left: 100%;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 1.25rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border: none;
            border-radius: 16px;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(99, 102, 241, 0.4);
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:active {
            transform: translateY(-1px);
        }

        /* Register Link */
        .register-link {
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .register-link a {
            color: var(--primary-light);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .register-link a:hover {
            color: var(--secondary);
            text-shadow: 0 0 10px rgba(6, 182, 212, 0.5);
        }

        /* Right Side - Visual */
        .visual-section {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            animation: slideInRight 0.8s var(--animation-bounce);
        }

        .visual-container {
            position: relative;
            width: 400px;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Rotating Rings */
        .rotating-ring {
            position: absolute;
            border: 2px solid;
            border-radius: 50%;
            border-color: var(--primary) transparent var(--secondary) transparent;
            animation: rotate 10s linear infinite;
        }

        .ring-1 {
            width: 300px;
            height: 300px;
            animation-duration: 15s;
        }

        .ring-2 {
            width: 350px;
            height: 350px;
            animation-duration: 20s;
            animation-direction: reverse;
            border-color: var(--secondary) transparent var(--accent) transparent;
        }

        .ring-3 {
            width: 400px;
            height: 400px;
            animation-duration: 25s;
            border-color: var(--accent) transparent var(--primary-light) transparent;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Central Logo/Icon */
        .central-icon {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: white;
            box-shadow: var(--shadow-intense);
            animation: pulse 2s ease-in-out infinite alternate;
            position: relative;
            z-index: 5;
        }

        @keyframes pulse {
            0% { 
                transform: scale(1);
                box-shadow: var(--shadow-intense);
            }
            100% { 
                transform: scale(1.05);
                box-shadow: var(--shadow-intense), 0 0 50px rgba(99, 102, 241, 0.5);
            }
        }

        /* Floating Orbs around Visual */
        .floating-orb {
            position: absolute;
            width: 20px;
            height: 20px;
            background: linear-gradient(45deg, var(--primary-light), var(--secondary));
            border-radius: 50%;
            animation: orbitFloat 8s ease-in-out infinite;
        }

        .orb-1 {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .orb-2 {
            top: 20%;
            right: 10%;
            animation-delay: 2s;
        }

        .orb-3 {
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        .orb-4 {
            bottom: 10%;
            right: 20%;
            animation-delay: 6s;
        }

        @keyframes orbitFloat {
            0%, 100% { 
                transform: translateY(0) scale(1);
                opacity: 0.7;
            }
            50% { 
                transform: translateY(-20px) scale(1.2);
                opacity: 1;
            }
        }

        /* Slide In Animations */
        @keyframes slideInLeft {
            0% {
                opacity: 0;
                transform: translateX(-100px);
            }
            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            0% {
                opacity: 0;
                transform: translateX(100px);
            }
            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
                gap: 2rem;
                padding: 1rem;
            }

            .visual-section {
                order: -1; /* Puts visual on top for mobile */
            }

            .visual-container {
                width: 300px;
                height: 300px;
            }

            .ring-1 { width: 200px; height: 200px; }
            .ring-2 { width: 250px; height: 250px; }
            .ring-3 { width: 300px; height: 300px; }

            .central-icon {
                width: 150px;
                height: 150px;
                font-size: 3rem;
            }

            .form-section {
                padding: 2rem;
            }

            .form-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            .form-section {
                padding: 1.5rem;
            }

            .form-title {
                font-size: 1.75rem;
            }

            .visual-container {
                width: 250px;
                height: 250px;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="gradient-bg"></div>
    
    <!-- Floating Geometric Shapes -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <!-- Particle System -->
    <div class="particles">
        <!-- More particles for a richer effect -->
        <div class="particle"></div><div class="particle"></div><div class="particle"></div>
        <div class="particle"></div><div class="particle"></div><div class="particle"></div>
        <div class="particle"></div><div class="particle"></div><div class="particle"></div>
        <div class="particle"></div><div class="particle"></div><div class="particle"></div>
        <div class="particle"></div><div class="particle"></div><div class="particle"></div>
    </div>

    <!-- Main Login Container -->
    <div class="login-container">
        <!-- Form Section -->
        <div class="form-section">
            <div class="form-header">
                <h1 class="form-title">Welcome Back</h1>
                <p class="form-subtitle">Sign in to your BITSathy account</p>
            </div>

            <?php if ($err): // Display error message if $err is not empty ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span><?php echo htmlspecialchars($err); ?></span>
                </div>
            <?php endif; ?>

            <form method="post" action="">
                <!-- CSRF Token for security -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                
                <div class="form-group">
                    <div class="input-container">
                        <input type="email" name="email" class="form-input" placeholder="Enter your email address" required
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"> <!-- Retain email on error -->
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-container">
                        <input type="password" name="password" class="form-input" placeholder="Enter your password" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>

            <div class="register-link">
                Don't have an account? <a href="register.php">Create one here</a>
            </div>
        </div>

        <!-- Visual Section -->
        <div class="visual-section">
            <div class="visual-container">
                <!-- Rotating Rings -->
                <div class="rotating-ring ring-1"></div>
                <div class="rotating-ring ring-2"></div>
                <div class="rotating-ring ring-3"></div>
                
                <!-- Central Icon -->
                <div class="central-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>

                <!-- Floating Orbs -->
                <div class="floating-orb orb-1"></div>
                <div class="floating-orb orb-2"></div>
                <div class="floating-orb orb-3"></div>
                <div class="floating-orb orb-4"></div>
            </div>
        </div>
    </div>

    <script>
        // Enhanced parallax for background elements
        document.addEventListener('mousemove', (e) => {
            const shapesContainer = document.querySelector('.floating-shapes');
            const particlesContainer = document.querySelector('.particles');
            const mouseX = (e.clientX / window.innerWidth) - 0.5; // -0.5 to 0.5
            const mouseY = (e.clientY / window.innerHeight) - 0.5; // -0.5 to 0.5
            
            // Subtle parallax for shapes (closer layer)
            const shapesParallaxX = mouseX * 60; // Max 60px movement
            const shapesParallaxY = mouseY * 60; // Max 60px movement
            shapesContainer.style.transform = `translate(${shapesParallaxX}px, ${shapesParallaxY}px)`;

            // Even subtler parallax for particles (further layer)
            const particlesParallaxX = mouseX * 30; // Max 30px movement
            const particlesParallaxY = mouseY * 30; // Max 30px movement
            particlesContainer.style.transform = `translate(${particlesParallaxX}px, ${particlesParallaxY}px)`;
        });

        // Add form validation with enhanced visual feedback
        const form = document.querySelector('form');
        const inputs = document.querySelectorAll('.form-input');

        inputs.forEach(input => {
            input.addEventListener('invalid', (e) => {
                e.preventDefault(); // Prevent default browser validation popup
                input.style.borderColor = 'var(--error)';
                input.style.boxShadow = '0 0 0 4px rgba(239, 68, 68, 0.1)';
                
                // Clear the error style after a delay if the user doesn't interact further
                setTimeout(() => {
                    if (!input.matches(':focus')) { // Only clear if not focused
                        input.style.borderColor = ''; // Revert to CSS default/initial
                        input.style.boxShadow = '';
                    }
                }, 3000); // 3 seconds
            });

            input.addEventListener('input', () => {
                if (input.checkValidity()) {
                    input.style.borderColor = 'var(--success)';
                    input.style.boxShadow = '0 0 0 4px rgba(16, 185, 129, 0.1)';
                } else {
                    // If input becomes invalid again, revert to error state (or default)
                    input.style.borderColor = 'var(--text-tertiary)'; // Or 'var(--error)' if you want it to persist immediately
                    input.style.boxShadow = '';
                }
            });

            input.addEventListener('blur', () => {
                if (input.checkValidity()) {
                    input.style.borderColor = ''; // Clear green border on blur if valid
                    input.style.boxShadow = '';
                }
            });
        });

        // Enhanced button animation on click
        const submitBtn = document.querySelector('.submit-btn');
        submitBtn.addEventListener('click', (e) => {
            // Only trigger visual feedback if form is valid, otherwise browser will show native error
            if (form.checkValidity()) {
                submitBtn.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    submitBtn.style.transform = '';
                }, 150); // Small delay for the press effect
            }
        });

        // Intersection Observer for enhanced animations
        // This ensures animations only play when elements are in view
        const observerOptions = {
            threshold: 0.1, // Trigger when 10% of the element is visible
            rootMargin: '0px 0px -50px 0px' // Adjust the viewport margin
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                } else {
                    // Optionally pause animations when out of view to save resources
                    // entry.target.style.animationPlayState = 'paused'; 
                }
            });
        }, observerOptions);

        // Observe the form and visual sections
        document.querySelectorAll('.form-section, .visual-section').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>