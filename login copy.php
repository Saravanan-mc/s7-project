<?php
session_start();

// Error reporting for development (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'regdata');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

$db = null; // Initialize $db to null

// Database connection using PDO
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions for errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch results as associative arrays
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Disable emulation for stronger type checking and security
    ];
    $db = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Log the actual error for debugging, but show a user-friendly message
    error_log("Database connection failed: " . $e->getMessage());
    die("We are currently experiencing technical difficulties. Please try again later.");
}

$error = "";

// CSRF token generation and validation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token using hash_equals for timing attack resistance
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = "Security token mismatch. Please try again.";
        // Regenerate token on mismatch to prevent potential CSRF attacks
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } else {
        // Regenerate token on successful submission to prevent double submission and token reuse
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        // Sanitize and validate user inputs
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? ''; // No direct sanitization for password, it's hashed

        // Input validation
        if (empty($email) || empty($password)) {
            $error = "Both email and password are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email address.";
        } else {
            try {
                // Prepare SQL statement to prevent SQL injection
                $stmt = $db->prepare("SELECT id, password, role, name, roll_number FROM users WHERE email = :email");
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->execute();
                $user = $stmt->fetch();

                // Verify password using password_verify()
                if ($user && password_verify($password, $user['password'])) {
                    // Set session variables for successful login
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_roll_number'] = $user['roll_number'] ?? null; // Null coalesce for optional field

                    // Redirect based on user role
                    switch ($user['role']) {
                        case 'admin':
                            header("Location: admin.php");
                            break;
                        case 'student':
                            header("Location: stdhome.php");
                            break;
                        case 'alumni':
                            header("Location: alumni.php");
                            break;
                        default:
                            header("Location: student_dashboard.php"); // Default redirect
                            break;
                    }
                    exit(); // Crucial: Stop script execution after redirection
                } else {
                    $error = "Invalid email or password.";
                }
            } catch (PDOException $e) {
                // Log the specific database error
                error_log("Login query error: " . $e->getMessage());
                $error = "An unexpected error occurred during login. Please try again.";
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
    <title>BITSathy | Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon"> <style>
        /* CSS Variables for easy theme changes */
        :root {
            --primary-color: #3f51b5; /* A vibrant blue */
            --primary-dark: #303f9f;
            --primary-light: #7986cb;
            --secondary-color: #00bcd4; /* A teal/cyan */
            --secondary-dark: #0097a7;
            --background-overlay: rgba(11, 0, 112, 0.7); /* Slightly darker overlay */
            --card-background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(248, 248, 248, 0.96));
            --text-dark: #2c3e50; /* Darker text for better contrast */
            --text-medium: #7f8c8d;
            --text-light: #bdc3c7;
            --input-border: #dfe6e9;
            --input-focus-border: var(--primary-color);
            --error-color: #e74c3c;
            --success-color: #27ae60;
            --shadow-sm: 0 4px 10px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 8px 25px rgba(0, 0, 0, 0.15);
            --shadow-lg: 0 15px 40px rgba(0, 0, 0, 0.25);
            --border-radius-sm: 8px;
            --border-radius-md: 15px;
            --transition-fast: 0.2s ease-in-out;
            --transition-medium: 0.4s ease-in-out;
            --transition-slow: 0.6s ease-out;
        }

        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        html, body {
            height: 100%;
            width: 100%;
            overflow: hidden; /* Hide scrollbars for background effects */
        }

        body {
            background: url('img/bglo.jpg') center/cover no-repeat fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fadeIn var(--transition-slow);
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: var(--background-overlay);
            z-index: -1;
        }

        /* Background Bubbles */
        .background-bubbles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -2;
        }

        .background-bubbles li {
            position: absolute;
            display: block;
            list-style: none;
            background: rgba(255, 255, 255, 0.12); /* Subtler bubbles */
            animation: animateBubbles 25s linear infinite;
            bottom: -180px;
            border-radius: 50%;
        }

        .background-bubbles li:nth-child(1) { left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
        .background-bubbles li:nth-child(2) { left: 10%; width: 30px; height: 30px; animation-delay: 2s; animation-duration: 12s; }
        .background-bubbles li:nth-child(3) { left: 70%; width: 50px; height: 50px; animation-delay: 4s; animation-duration: 18s; }
        .background-bubbles li:nth-child(4) { left: 40%; width: 70px; height: 70px; animation-delay: 0s; animation-duration: 20s; }
        .background-bubbles li:nth-child(5) { left: 65%; width: 25px; height: 25px; animation-delay: 0s; animation-duration: 10s; }
        .background-bubbles li:nth-child(6) { left: 75%; width: 100px; height: 100px; animation-delay: 3s; animation-duration: 22s; }
        .background-bubbles li:nth-child(7) { left: 35%; width: 130px; height: 130px; animation-delay: 7s; animation-duration: 28s; }
        .background-bubbles li:nth-child(8) { left: 50%; width: 40px; height: 40px; animation-delay: 15s; animation-duration: 35s; }
        .background-bubbles li:nth-child(9) { left: 20%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 30s; }
        .background-bubbles li:nth-child(10) { left: 85%; width: 110px; height: 110px; animation-delay: 0s; animation-duration: 15s; }

        @keyframes animateBubbles {
            0% { transform: translateY(0) rotate(0deg); opacity: 1; border-radius: 50%; }
            100% { transform: translateY(-1200px) rotate(720deg); opacity: 0; border-radius: 50%; }
        }

        /* Container */
        .container {
            display: flex;
            width: 950px; /* Wider for more space */
            max-width: 95%; /* Responsive max-width */
            background: var(--card-background);
            border-radius: var(--border-radius-md);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            animation: scaleUp var(--transition-slow);
            transition: transform var(--transition-medium), box-shadow var(--transition-medium);
            z-index: 10; /* Ensure it's above the background */
        }

        .container:hover {
            transform: translateY(-10px) scale(1.005); /* Enhanced hover effect */
            box-shadow: var(--shadow-lg);
        }

        /* Left Section (Form) */
        .left {
            flex: 1;
            padding: 50px 60px; /* More generous padding */
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            background: rgba(255, 255, 255, 0.95); /* Slightly brighter background for the form side */
        }

        .left h2 {
            margin-bottom: 30px;
            color: var(--text-dark);
            font-size: 34px; /* Larger, bolder heading */
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            position: relative;
            animation: fadeIn 1s ease-out;
        }

        .left h2::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 70px; /* Slightly longer underline */
            height: 5px; /* Thicker underline */
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            border-radius: 3px;
        }

        /* Error Message */
        .error {
            color: var(--error-color);
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
            animation: shake 0.4s ease-in-out;
            border: 1px solid var(--error-color);
            padding: 10px;
            border-radius: var(--border-radius-sm);
            background-color: rgba(231, 76, 60, 0.1);
        }

        /* Input Fields */
        .input-group {
            display: flex;
            flex-direction: column;
            gap: 20px; /* More space between inputs */
            margin-bottom: 30px; /* More space before button */
            animation: fadeInUp 1.5s ease-out forwards;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper input {
            width: 100%;
            padding: 14px 15px 14px 45px; /* Added left padding for icon */
            border: 2px solid var(--input-border);
            border-radius: var(--border-radius-sm);
            font-size: 16px;
            color: var(--text-dark);
            transition: all var(--transition-fast);
            outline: none;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .input-wrapper input::placeholder {
            color: var(--text-medium);
        }

        .input-wrapper input:focus {
            border-color: var(--input-focus-border);
            box-shadow: 0 0 0 3px rgba(var(--primary-color), 0.2), inset 0 1px 3px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px) scale(1.01); /* Subtle lift on focus */
        }

        .input-wrapper input:hover {
            border-color: var(--primary-light);
            background: rgba(250, 250, 250, 0.95);
        }

        .input-wrapper .icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-medium);
            font-size: 18px;
            transition: color var(--transition-fast);
        }

        .input-wrapper input:focus + .icon {
            color: var(--primary-color);
        }


        /* Buttons */
        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: var(--border-radius-sm);
            cursor: pointer;
            font-size: 19px;
            font-weight: 600;
            transition: all var(--transition-fast);
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            animation: fadeIn 1s ease-out 0.5s;
            letter-spacing: 1.2px;
            box-shadow: var(--shadow-sm);
            text-transform: uppercase;
        }

        .btn:hover {
            background: linear-gradient(45deg, var(--primary-dark), var(--secondary-dark));
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 20px rgba(var(--primary-color), 0.3);
            animation: none; /* Remove pulse on hover to avoid conflict */
        }

        .btn:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(var(--primary-color), 0.3);
        }

        .btn:active {
            transform: translateY(0);
            opacity: 0.95;
            box-shadow: var(--shadow-sm);
        }

        /* Register Link Styling */
        .register-link {
            text-align: center;
            margin-top: 30px; /* More space */
            font-size: 15px;
            color: var(--text-medium);
            animation: fadeInUp 1.5s ease-out 1s forwards;
        }

        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 700;
            transition: color var(--transition-fast), text-decoration var(--transition-fast);
        }

        .register-link a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Right Section (Image/Visual) */
        .right {
            flex: 1;
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-light)); /* More subtle gradient */
            display: flex;
            justify-content: center;
            align-items: center;
            animation: slideInRight 1s ease-out 0.5s forwards;
            position: relative;
            overflow: hidden; /* Hide anything overflowing */
        }

        .right::before {
            content: "";
            position: absolute;
            width: 90%; /* Larger pulsating circle */
            height: 90%;
            background: rgba(255, 255, 255, 0.15); /* Subtler white overlay */
            border-radius: 50%;
            z-index: 0;
            animation: pulse 2.5s infinite alternate ease-in-out;
        }

        .right:hover::before {
            animation: rotateScale 8s linear infinite; /* Slower rotation */
        }

        .right img {
            width: 350px; /* Larger image */
            height: auto; /* Maintain aspect ratio */
            max-width: 90%; /* Ensure responsiveness */
            border-radius: var(--border-radius-md);
            animation: bounceIn 1.5s ease-in-out;
            position: relative;
            z-index: 1;
            box-shadow: var(--shadow-md); /* Shadow for the image */
            transition: transform var(--transition-medium);
        }

        .right:hover img {
            transform: scale(1.05) rotate(2deg); /* Slightly rotate and scale on hover */
            animation: glow 1.8s ease-in-out infinite alternate, bounce 1.5s infinite alternate;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes scaleUp {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes bounceIn {
            0% { opacity: 0; transform: scale(0.7) translateY(20px); }
            60% { opacity: 1; transform: scale(1.1) translateY(-10px); }
            100% { transform: scale(1) translateY(0); }
        }

        @keyframes pulse {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        @keyframes rotateScale {
            0% { transform: rotate(0deg) scale(1); }
            50% { transform: rotate(180deg) scale(1.05); }
            100% { transform: rotate(360deg) scale(1); }
        }

        @keyframes glow {
            0% { box-shadow: 0 0 10px rgba(var(--secondary-color), 0.3); }
            50% { box-shadow: 0 0 30px rgba(var(--secondary-color), 0.8); }
            100% { box-shadow: 0 0 10px rgba(var(--secondary-color), 0.3); }
        }

        @keyframes bounce {
            0% { transform: translateY(0); }
            100% { transform: translateY(-15px); }
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .container {
                width: 95%;
            }
            .left {
                padding: 40px;
            }
            .left h2 {
                font-size: 30px;
            }
            .right img {
                width: 300px;
            }
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                max-width: 90%;
            }
            .right {
                display: none; /* Hide image section on smaller screens */
            }
            .left {
                padding: 30px;
            }
            .left h2 {
                font-size: 28px;
                margin-bottom: 20px;
            }
            .input-group {
                gap: 15px;
                margin-bottom: 25px;
            }
            .btn {
                font-size: 17px;
                padding: 13px;
            }
            .register-link {
                margin-top: 25px;
            }
        }

        @media (max-width: 480px) {
            .container {
                max-width: 95%;
                padding: 0; /* Remove container padding as left will handle it */
                border-radius: var(--border-radius-md);
            }
            .left {
                padding: 25px 20px; /* Reduced padding for very small screens */
            }
            .left h2 {
                font-size: 24px;
                letter-spacing: 1.5px;
            }
            .input-wrapper input {
                font-size: 15px;
                padding: 12px 12px 12px 40px;
            }
            .input-wrapper .icon {
                font-size: 16px;
                left: 12px;
            }
            .btn {
                font-size: 15px;
                padding: 12px;
            }
            .register-link {
                font-size: 14px;
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>

<ul class="background-bubbles">
    <li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
</ul>

<div class="container">
    <div class="left">
        <h2>Login to BITSathy</h2>
        <?php if (!empty($error)): ?>
            <p class='error'><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        
        <form method="post" action="">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <div class="input-group">
                <div class="input-wrapper">
                    <input type="email" name="email" placeholder="Your Email Address" required 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" 
                           autocomplete="email" aria-label="Email Address">
                    <i class="fas fa-envelope icon"></i>
                </div>
                <div class="input-wrapper">
                    <input type="password" name="password" placeholder="Your Password" required 
                           autocomplete="current-password" aria-label="Password">
                    <i class="fas fa-lock icon"></i>
                </div>
            </div>
            <button type="submit" class="btn">
                <i class="fas fa-sign-in-alt"></i> Login Now
            </button>
        </form>
        
        <div class="register-link">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </div>
    <div class="right">
        <img src="img/login.gif" alt="BITSathy Login Illustration">
    </div>
</div>

</body>
</html>