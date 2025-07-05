<?php
session_start();

// --- PHP Configuration and Logic ---
// DATABASE CONNECTION
define('DB_HOST', 'localhost');
define('DB_NAME', 'regdata'); // ✅ Ensure this is the same database name used in register.php
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
$options = [
    PDO::ATTR_ERRMODE             => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES    => false,
];

$db = null; // Initialize $db outside try for proper scope
try {
    $db = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // In a real application, log this error securely and show a user-friendly message
    // error_log("DB connection failed: " . $e->getMessage()); // Uncomment for logging
    die("A database error occurred. Please try again later."); // Generic message for user
}

$err = ""; // Initialize error message
$msg = ""; // Initialize success message

// Generate CSRF token for security
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Token Validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $err = "Security token mismatch. Please try again.";
        // Optionally, regenerate token and log suspicious activity
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } else {
        // Regenerate CSRF token after successful validation to prevent replay attacks
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $email = strtolower(trim($_POST['email']));
        $password_raw = $_POST['password']; // Raw password for confirmation check
        $confirm_password_raw = $_POST['confirm_password']; // Raw password for confirmation check
        $roll = trim($_POST['roll_number'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $dept = trim($_POST['department'] ?? '');
        $year = $_POST['academic_year'] ?? ''; // Selected academic year from dropdown

        // --- Input Validation ---
        // 1. Password Confirmation
        if ($password_raw !== $confirm_password_raw) {
            $err = "Passwords do not match.";
        }
        // 2. Password Length (arbitrary, adjust as needed)
        elseif (strlen($password_raw) < 8) {
            $err = "Password must be at least 8 characters long.";
        }
        // 3. Email Format & Domain
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/^[a-z0-9\.]+@bitsathy\.ac\.in$/", $email)) {
            $err = "Please enter a valid @bitsathy.ac.in email address.";
        }
        // 4. Basic Name validation
        elseif (empty($name)) {
            $err = "Full Name is required.";
        }

        // Determine role from email (pre-validation to guide form validation)
        $potential_role = "admin"; // Default assumption, can be overridden
        preg_match('/^([a-z0-9\.]+?)([0-9]{2})@bitsathy\.ac\.in$/', $email, $matches);

        if (isset($matches[2])) { // If year digits are found in the email
            $email_year_prefix = intval($matches[2]); // e.g., 22 from 22ecb001

            $current_year_full = date("Y");
            $current_year_suffix = $current_year_full % 100;

            $joined_year = 2000 + $email_year_prefix;
            // Handle cases where email prefix might be for a year in the previous century (e.g., '99' for 1999)
            if ($email_year_prefix > $current_year_suffix && $email_year_prefix <= 99) { // Check if it's a "high" suffix like 90-99
                $joined_year = 1900 + $email_year_prefix;
            }


            $academic_years_passed = $current_year_full - $joined_year;

            // Simplified rule for role detection based on academic years passed
            if ($academic_years_passed >= 4) { // 4 or more years since joining implies alumni for 4-year degree
                $potential_role = 'alumni';
            } else {
                $potential_role = 'student';
            }
        }

        // Apply additional validation based on the determined role
        if (empty($err) && ($potential_role === 'student' || $potential_role === 'alumni')) {
            if (empty($roll)) {
                $err = "Roll Number is required for students and alumni.";
            } elseif (empty($dept)) {
                $err = "Department is required for students and alumni.";
            } elseif (empty($year)) {
                $err = "Academic Year is required for students and alumni.";
            }
        }

        if (empty($err)) { // Proceed only if no validation errors so far
            // Hash the password securely
            $password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);

            // Check for duplicate email
            $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $err = "Email already registered. Please login or use a different email.";
            } else {
                // Adjust null values for admin based on the determined role
                $final_roll = ($potential_role === 'admin') ? null : $roll;
                $final_dept = ($potential_role === 'admin') ? null : $dept;
                $final_year = ($potential_role === 'admin') ? null : $year;

                // Insert new user
                try {
                    $stmt = $db->prepare("INSERT INTO users
                        (email, password, roll_number, name, department, academic_year, role)
                        VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$email, $password_hashed, $final_roll, $name, $final_dept, $final_year, $potential_role]);
                    $msg = "Registration successful! You are registered as an **" . ucfirst($potential_role) . "**.";
                    // Clear form fields on success by resetting $_POST
                    $_POST = [];
                } catch (PDOException $e) {
                    // Log this error securely and show a generic message to the user
                    // error_log("Registration error: " . $e->getMessage()); // Uncomment for logging
                    $err = "An error occurred during registration. Please try again.";
                }
            }
        }
    }
}

// Generate Academic Years for dropdown (e.g., current year to 4 years back, assuming 4-year program)
$current_year_for_dropdown = date("Y");
$academic_years = [];
// List recent start years for current/recent students (e.g., last 5 years)
for ($i = 0; $i < 5; $i++) {
    $start_year_option = $current_year_for_dropdown - $i;
    $end_year_option = $start_year_option + 4; // Assuming a 4-year program
    $academic_years[] = "$start_year_option to $end_year_option";
}
// Add older options for alumni (e.g., back to 2000)
for ($start_year_option = 2019; $start_year_option >= 2000; $start_year_option--) { // Go back to 2000 explicitly
    $end_year_option = $start_year_option + 4;
    $full_period = "$start_year_option to $end_year_option";
    if (!in_array($full_period, $academic_years)) {
        $academic_years[] = $full_period;
    }
}

// Sort in descending order (newest first) and remove duplicates
rsort($academic_years);
$academic_years = array_unique($academic_years);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BITSathy Register - Ultimate Experience</title>
    <!-- Fonts: Space Grotesk for headings, Inter for body text -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons (latest version) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* --- CSS Variables: Futuristic Dark Theme --- */
        :root {
            --bg-dark: #07071c; /* Very deep blue-purple */
            --bg-medium: #111133; /* Slightly lighter for elements */
            --primary-accent: #00e0ff; /* Electric Cyan */
            --secondary-accent: #d400ff; /* Vibrant Magenta */
            --text-light: #e0e0e0; /* Light gray for main text */
            --text-medium: #a0a0b0; /* Medium gray for secondary text/placeholders */
            --text-dark: #606070; /* Dark gray for subtle hints */

            --glass-bg: rgba(255, 255, 255, 0.04); /* Very subtle transparent white */
            --glass-border: rgba(255, 255, 255, 0.08); /* Slightly more visible border */
            --shadow-deep: 0 20px 50px rgba(0, 0, 0, 0.6); /* For card shadow */
            --glow-primary: 0 0 15px rgba(0, 224, 255, 0.4); /* For subtle glows */
            --glow-secondary: 0 0 15px rgba(212, 0, 255, 0.4);

            --error-color: #ef4444; /* Red 500 */
            --success-color: #22c55e; /* Green 500 */
            --warning-color: #f59e0b; /* Amber 500 */

            --border-radius-lg: 20px;
            --border-radius-md: 12px;
            --transition-fast: all 0.2s ease-out;
            --transition-medium: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: all 0.6s ease-in-out;
        }

        /* --- Global Styles --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            color: var(--text-light);
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        a {
            color: var(--primary-accent);
            text-decoration: none;
            font-weight: 600;
            transition: color var(--transition-fast), text-shadow var(--transition-fast);
        }

        a:hover {
            color: var(--secondary-accent);
            text-shadow: 0 0 10px var(--secondary-accent);
        }

        /* --- Dynamic Background: Aura Blobs & Particles --- */
        .background-gradient {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 10% 90%, var(--bg-medium) 0%, transparent 50%),
                        radial-gradient(circle at 90% 10%, var(--bg-medium) 0%, transparent 50%);
            z-index: -3;
        }

        .aura-blob {
            position: absolute;
            background: radial-gradient(circle, var(--primary-accent) 0%, transparent 70%);
            opacity: 0.1;
            filter: blur(80px);
            animation: blobAnimate 25s infinite alternate ease-in-out;
            border-radius: 50%;
            pointer-events: none;
            transform: translateZ(0); /* Hardware acceleration */
        }

        .aura-blob:nth-child(1) { width: 400px; height: 400px; top: 10%; left: 5%; animation-delay: 0s; }
        .aura-blob:nth-child(2) { width: 350px; height: 350px; bottom: 15%; right: 10%; background: radial-gradient(circle, var(--secondary-accent) 0%, transparent 70%); opacity: 0.12; animation-delay: 5s; animation-duration: 28s; }
        .aura-blob:nth-child(3) { width: 500px; height: 500px; top: 20%; right: 20%; animation-delay: 10s; animation-duration: 20s; }
        .aura-blob:nth-child(4) { width: 250px; height: 250px; bottom: 5%; left: 25%; background: radial-gradient(circle, var(--secondary-accent) 0%, transparent 70%); opacity: 0.08; animation-delay: 15s; animation-duration: 32s; }
        .aura-blob:nth-child(5) { width: 300px; height: 300px; top: 60%; left: 40%; animation-delay: 20s; animation-duration: 23s; }

        @keyframes blobAnimate {
            0% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(50px, -30px) scale(1.1); }
            66% { transform: translate(-20px, 40px) scale(0.9); }
            100% { transform: translate(0, 0) scale(1); }
        }

        .particles-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            overflow: hidden;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            opacity: 0;
            animation: particleFloat 15s infinite linear;
            box-shadow: 0 0 8px rgba(255, 255, 255, 0.3);
        }

        @keyframes particleFloat {
            0% { transform: translateY(100vh) scale(0.5); opacity: 0; }
            10% { opacity: 0.8; }
            90% { opacity: 0.8; }
            100% { transform: translateY(-10vh) scale(1.5); opacity: 0; }
        }
        /* Particle sizes and delays for variation */
        .particle:nth-child(even) { width: 2px; height: 2px; animation-duration: 18s; animation-delay: calc(var(--i) * 1s); }
        .particle:nth-child(odd) { width: 3px; height: 3px; animation-duration: 12s; animation-delay: calc(var(--i) * 1.5s); }
        .particle:nth-child(3n) { background: var(--primary-accent); box-shadow: 0 0 10px var(--primary-accent); }
        .particle:nth-child(5n) { background: var(--secondary-accent); box-shadow: 0 0 10px var(--secondary-accent); }


        /* --- Registration Card --- */
        .register-card {
            background: var(--glass-bg);
            backdrop-filter: blur(25px) brightness(1.1); /* Stronger blur and slight brightness */
            -webkit-backdrop-filter: blur(25px) brightness(1.1);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius-lg);
            padding: 3rem;
            box-shadow: var(--shadow-deep);
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 950px; /* Wider for better layout */
            animation: fadeScaleIn 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            overflow: hidden; /* For inner animations */
        }

        @keyframes fadeScaleIn {
            0% { opacity: 0; transform: scale(0.9) translateY(20px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }

        /* --- Profile Section --- */
        .profile-section {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
            animation: slideFadeIn 0.7s ease-out forwards;
            animation-delay: 0.2s; /* Staggered animation */
            opacity: 0;
            transform: translateY(20px);
        }

        .profile-image-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--primary-accent), var(--secondary-accent));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            box-shadow: var(--glow-primary), var(--glow-secondary);
            margin: 0 auto 1.5rem;
            animation: profilePulse 2s ease-in-out infinite alternate;
        }

        @keyframes profilePulse {
            0% { transform: scale(1); box-shadow: var(--glow-primary); }
            100% { transform: scale(1.05); box-shadow: var(--glow-primary), var(--glow-secondary), 0 0 30px var(--primary-accent); }
        }

        .profile-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(90deg, var(--primary-accent), var(--secondary-accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 15px rgba(0, 224, 255, 0.3), 0 0 15px rgba(212, 0, 255, 0.3);
        }

        .profile-subtitle {
            color: var(--text-medium);
            font-size: 1rem;
            font-weight: 400;
        }

        /* --- Message Boxes --- */
        #messageContainer {
            margin-bottom: 1.5rem;
            position: relative; /* For z-index over form elements on animation */
        }

        .message {
            padding: 1rem;
            border-radius: var(--border-radius-md);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.95rem;
            font-weight: 500;
            margin-bottom: 0.75rem;
            animation: slideInDown 0.5s ease-out forwards;
            opacity: 0;
            transform: translateY(-20px);
        }

        .message.error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #fca5a5;
        }

        .message.error.shake {
            animation: slideInDown 0.5s ease-out forwards, errorShake 0.5s ease-in-out;
        }

        .message.success {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.4);
            color: #86efac;
        }

        @keyframes slideInDown {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        @keyframes errorShake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* --- Form Layout --- */
        .form-content {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2.5rem 3.5rem; /* Row gap, Column gap */
        }

        /* Staggered animation for form sections */
        .form-section {
            animation: slideFadeIn 0.7s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        .form-section:nth-child(1) { animation-delay: 0.3s; }
        .form-section:nth-child(2) { animation-delay: 0.4s; }

        .submit-section {
            grid-column: 1 / -1; /* Spans full width */
            margin-top: 1rem;
            animation: slideFadeIn 0.7s ease-out forwards;
            animation-delay: 0.5s;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes slideFadeIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .form-section h3 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.4rem;
            color: var(--primary-accent);
            margin-bottom: 1.8rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-shadow: 0 0 10px rgba(0, 224, 255, 0.3);
        }
        
        .form-section h3 i {
            font-size: 1.1rem;
            color: var(--secondary-accent);
        }

        /* --- Input Group Styling --- */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 0.9rem;
            color: var(--text-medium);
            margin-bottom: 0.6rem;
            font-weight: 500;
        }

        .input-wrapper {
            position: relative;
            border-radius: var(--border-radius-md);
            overflow: hidden; /* For the inner glow effect */
            transition: var(--transition-fast);
        }

        .input-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: var(--border-radius-md);
            border: 2px solid var(--glass-border);
            pointer-events: none;
            z-index: 1;
            transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
        }

        .input-wrapper.focused::before {
            border-color: var(--primary-accent);
            box-shadow: 0 0 15px rgba(0, 224, 255, 0.3), inset 0 0 8px rgba(0, 224, 255, 0.2);
        }
        .input-wrapper.valid::before {
            border-color: var(--success-color);
            box-shadow: 0 0 10px rgba(34, 197, 94, 0.2), inset 0 0 5px rgba(34, 197, 94, 0.1);
        }
        .input-wrapper.invalid::before {
            border-color: var(--error-color);
            box-shadow: 0 0 10px rgba(239, 68, 68, 0.2), inset 0 0 5px rgba(239, 68, 68, 0.1);
        }

        .form-input, .form-select {
            width: 100%;
            padding: 1.1rem 1.1rem 1.1rem 3.5rem; /* Space for icon */
            background: transparent; /* Makes glass effect visible */
            border: none; /* Border handled by ::before */
            color: var(--text-light);
            font-size: 1rem;
            font-weight: 500;
            outline: none;
            position: relative;
            z-index: 2; /* Keep input above ::before */
            -webkit-appearance: none; /* Remove default select styling */
            -moz-appearance: none;
            appearance: none;
            cursor: text; /* Or pointer for select */
            transition: var(--transition-fast);
        }

        .form-select {
            cursor: pointer;
            padding-right: 2.5rem; /* Space for arrow icon */
        }

        .form-input::placeholder {
            color: var(--text-dark);
            opacity: 0.8;
        }

        .input-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-dark);
            font-size: 1.1rem;
            transition: var(--transition-fast);
            z-index: 3; /* Keep icon above input */
            pointer-events: none;
        }

        .input-wrapper.focused .input-icon {
            color: var(--primary-accent);
            transform: translateY(-50%) scale(1.1);
            text-shadow: 0 0 8px rgba(0, 224, 255, 0.5);
        }

        /* --- Submit Button --- */
        .submit-btn {
            width: 100%;
            padding: 1.25rem;
            background: linear-gradient(45deg, var(--primary-accent), var(--secondary-accent));
            border: none;
            border-radius: var(--border-radius-md);
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition-medium);
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.4), 0 0 10px var(--primary-accent);
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -150%; /* Start far left */
            width: 200%; /* Wider for wave effect */
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transform: skewX(-20deg); /* Angled shimmer */
            transition: left 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94); /* Smooth wave */
            z-index: 0;
        }

        .submit-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6), 0 0 20px var(--primary-accent);
        }

        .submit-btn:hover::before {
            left: 150%; /* End far right */
        }

        .submit-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3), 0 0 5px var(--primary-accent);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .submit-btn .fas {
            transition: transform 0.3s ease;
        }

        #registrationForm.loading .submit-btn {
            opacity: 0.7;
            cursor: wait;
            transform: scale(0.98);
            box-shadow: none;
        }

        /* --- Info Text and Login Link --- */
        .info-text {
            text-align: center;
            color: var(--text-medium);
            font-size: 0.85rem;
            margin-top: 1.5rem;
            line-height: 1.5;
        }

        .login-link {
            text-align: center;
            color: var(--text-medium);
            font-size: 0.95rem;
            margin-top: 1.5rem;
        }

        /* --- Responsive Design --- */
        @media (max-width: 992px) {
            .register-card {
                max-width: 700px;
                padding: 2.5rem;
            }
            .form-content {
                grid-template-columns: 1fr; /* Stack columns on medium screens */
                gap: 2rem;
            }
            .profile-title {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 768px) {
            .register-card {
                padding: 2rem;
                border-radius: var(--border-radius-md);
            }
            .profile-image-icon {
                width: 80px;
                height: 80px;
                font-size: 2.5rem;
            }
            .profile-title {
                font-size: 1.8rem;
            }
            .profile-subtitle {
                font-size: 0.9rem;
            }
            .form-section h3 {
                font-size: 1.2rem;
                margin-bottom: 1.25rem;
            }
            .form-input, .form-select {
                padding: 1rem 1rem 1rem 3rem;
                font-size: 0.95rem;
            }
            .input-icon {
                left: 1rem;
                font-size: 1rem;
            }
            .submit-btn {
                padding: 1rem;
                font-size: 1rem;
            }
            .info-text, .login-link {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .register-card {
                padding: 1.5rem;
                margin: 1rem;
                width: calc(100% - 2rem);
            }
            .profile-title {
                font-size: 1.5rem;
            }
            .profile-subtitle {
                font-size: 0.85rem;
            }
            .aura-blob { filter: blur(50px); } /* Less blur on small screens */
        }
    </style>
</head>
<body>
    <!-- Dynamic Background: Aura Blobs -->
    <div class="background-gradient">
        <div class="aura-blob"></div>
        <div class="aura-blob"></div>
        <div class="aura-blob"></div>
        <div class="aura-blob"></div>
        <div class="aura-blob"></div>
    </div>
    
    <!-- Particles (Stars) Background -->
    <div class="particles-container">
        <?php for($i=0; $i<50; $i++): ?>
            <div class="particle" style="left: <?php echo rand(0, 100); ?>%; top: <?php echo rand(0, 100); ?>%; animation-delay: <?php echo rand(0, 15000) / 1000; ?>s; animation-duration: <?php echo rand(100, 200) / 10; ?>s; --i:<?php echo $i; ?>;"></div>
        <?php endfor; ?>
    </div>

    <div class="register-card">
        <!-- Profile Section -->
        <div class="profile-section">
            <div class="profile-image-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h1 class="profile-title">Join BITSathy</h1>
            <p class="profile-subtitle">Alumni & Student Network</p>
        </div>

        <!-- Messages -->
        <div id="messageContainer">
            <?php if ($err): ?>
                <div class="message error shake">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($err); ?></span>
                </div>
            <?php endif; ?>
            <?php if ($msg): ?>
                <div class="message success">
                    <i class="fas fa-check-circle"></i>
                    <span><?php echo $msg; ?></span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Registration Form -->
        <form id="registrationForm" method="POST">
            <input type="hidden" name="csrf_token" id="csrfToken" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            
            <div class="form-content">
                <!-- Left Section: Authentication -->
                <div class="form-section">
                    <h3><i class="fas fa-key"></i> Authentication</h3>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email" class="form-input" 
                                   placeholder="your.name@bitsathy.ac.in" required
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" class="form-input" 
                                   placeholder="Enter secure password" required minlength="8">
                            <i class="fas fa-lock input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <div class="input-wrapper">
                            <input type="password" id="confirm_password" name="confirm_password" class="form-input" 
                                   placeholder="Confirm your password" required minlength="8">
                            <i class="fas fa-shield-alt input-icon"></i>
                        </div>
                    </div>
                </div>

                <!-- Right Section: Personal Details -->
                <div class="form-section">
                    <h3><i class="fas fa-user"></i> Personal Details</h3>
                    
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <div class="input-wrapper">
                            <input type="text" id="name" name="name" class="form-input" 
                                   placeholder="Enter your full name" required
                                   value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                            <i class="fas fa-user-circle input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="roll_number">Roll Number</label>
                        <div class="input-wrapper">
                            <input type="text" id="roll_number" name="roll_number" class="form-input" 
                                   placeholder="e.g., 22ECB001 (Optional for Admin)"
                                   value="<?php echo htmlspecialchars($_POST['roll_number'] ?? ''); ?>">
                            <i class="fas fa-id-badge input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="department">Department</label>
                        <div class="input-wrapper">
                            <input type="text" id="department" name="department" class="form-input" 
                                   placeholder="e.g., ECE, CSE (Optional for Admin)"
                                   value="<?php echo htmlspecialchars($_POST['department'] ?? ''); ?>">
                            <i class="fas fa-building input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="academic_year">Academic Year</label>
                        <div class="input-wrapper">
                            <select id="academic_year" name="academic_year" class="form-select">
                                <option value="">Select Academic Year (Optional for Admin)</option>
                                <?php foreach ($academic_years as $year_option): ?>
                                    <option value="<?php echo htmlspecialchars($year_option); ?>"
                                        <?php echo (isset($_POST['academic_year']) && $_POST['academic_year'] === $year_option) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($year_option); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <i class="fas fa-calendar-alt input-icon"></i>
                        </div>
                    </div>
                </div>

                <!-- Submit Section -->
                <div class="submit-section">
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-user-plus"></i> <span>Create Account</span>
                    </button>
                    
                    <p class="info-text">
                        Your role will be automatically determined based on your email. 
                        Students and alumni must fill all fields, while admin fields are optional.
                    </p>
                    
                    <div class="login-link">
                        Already have an account? <a href="login.php">Sign in here</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        // --- JavaScript for Enhanced Interactions ---

        // Helper function to show messages
        function showMessage(text, type) {
            const messageContainer = document.getElementById('messageContainer');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${type}`;
            messageDiv.innerHTML = `
                <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'}"></i>
                <span>${text}</span>
            `;
            messageContainer.appendChild(messageDiv);
            
            // Auto-remove messages after 5 seconds with fade-out
            setTimeout(() => {
                messageDiv.style.opacity = '0';
                messageDiv.style.transform = 'translateY(-20px)';
                messageDiv.addEventListener('transitionend', () => messageDiv.remove());
            }, 5000);
        }

        // --- Form Validation and Submission ---
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const submitBtn = form.querySelector('.submit-btn');
            const messageContainer = document.getElementById('messageContainer');
            
            // Clear previous messages and validation states
            messageContainer.innerHTML = '';
            document.querySelectorAll('.input-wrapper').forEach(wrapper => {
                wrapper.classList.remove('focused', 'valid', 'invalid');
            });
            
            const formData = new FormData(form);
            const password = formData.get('password');
            const confirmPassword = formData.get('confirm_password');
            const email = formData.get('email');
            
            let errors = [];
            let isValidForm = true;

            // Validate all inputs for visual feedback
            document.querySelectorAll('.form-input, .form-select').forEach(input => {
                const wrapper = input.closest('.input-wrapper');
                if (input.checkValidity()) {
                    wrapper.classList.add('valid');
                    wrapper.classList.remove('invalid');
                } else {
                    wrapper.classList.add('invalid');
                    wrapper.classList.remove('valid');
                    isValidForm = false;
                    // Add specific error messages for invalid fields, avoiding duplicates
                    const labelText = input.previousElementSibling ? input.previousElementSibling.textContent.replace(':', '').trim() : input.placeholder.replace('(Optional for Admin)', '').trim();
                    if (input.validity.valueMissing) {
                        errors.push(`${labelText} is required.`);
                    } else if (input.validity.typeMismatch && input.type === 'email') {
                        errors.push(`Please enter a valid email address for "${labelText}".`);
                    } else if (input.validity.tooShort) {
                        errors.push(`${labelText} must be at least ${input.minLength} characters.`);
                    }
                }
            });

            if (password !== confirmPassword) {
                errors.push('Passwords do not match.');
                document.getElementById('password').closest('.input-wrapper').classList.add('invalid');
                document.getElementById('confirm_password').closest('.input-wrapper').classList.add('invalid');
                isValidForm = false;
            }
            
            if (!email.endsWith('@bitsathy.ac.in')) {
                errors.push('Please enter a valid @bitsathy.ac.in email address.');
                document.getElementById('email').closest('.input-wrapper').classList.add('invalid');
                isValidForm = false;
            }
            
            // Filter unique errors and ensure order for presentation
            errors = [...new Set(errors)];

            if (!isValidForm) {
                if (errors.length > 0) {
                    showMessage(errors.join('. '), 'error');
                }
                return;
            }
            
            // Show loading state
            form.classList.add('loading');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            
            // Simulate form submission (replace with actual PHP processing)
            setTimeout(() => {
                form.classList.remove('loading');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-user-plus"></i> <span>Create Account</span>'; // Revert button text

                // Use the PHP generated messages
                const phpError = "<?php echo $err; ?>";
                const phpMsg = "<?php echo $msg; ?>";

                if (phpError) {
                    showMessage(phpError, 'error');
                } else if (phpMsg) {
                    showMessage(phpMsg, 'success');
                    // Reset form fields on success
                    form.reset();
                    // Clear all input validation styles on successful submission
                    document.querySelectorAll('.input-wrapper').forEach(wrapper => {
                        wrapper.classList.remove('focused', 'valid', 'invalid');
                    });
                } else {
                    // Fallback if no PHP message is set (shouldn't happen with current PHP logic)
                    showMessage('Form submitted successfully!', 'success');
                    form.reset();
                }

            }, 2500); // Increased delay for better loading visual
        });
        
        // --- Real-time Input Visual Feedback & Focus Effects ---
        document.querySelectorAll('.form-input, .form-select').forEach(input => {
            const wrapper = input.closest('.input-wrapper');

            input.addEventListener('focus', () => {
                wrapper.classList.add('focused');
            });
            
            input.addEventListener('blur', () => {
                wrapper.classList.remove('focused');
                // Re-validate on blur for immediate feedback
                if (input.checkValidity()) {
                    wrapper.classList.add('valid');
                    wrapper.classList.remove('invalid');
                } else {
                    wrapper.classList.add('invalid');
                    wrapper.classList.remove('valid');
                }
            });

            input.addEventListener('input', () => {
                if (input.value.length === 0) { // Clear validation state if input is empty
                    wrapper.classList.remove('valid', 'invalid');
                } else if (input.checkValidity()) {
                    wrapper.classList.add('valid');
                    wrapper.classList.remove('invalid');
                } else {
                    wrapper.classList.add('invalid');
                    wrapper.classList.remove('valid');
                }
            });
        });

        // --- Parallax effect for particles on mouse movement ---
        document.addEventListener('mousemove', (e) => {
            const particles = document.querySelectorAll('.particle');
            const mouseX = (e.clientX / window.innerWidth - 0.5) * 100; // -50 to 50
            const mouseY = (e.clientY / window.innerHeight - 0.5) * 100; // -50 to 50
            
            particles.forEach((particle, index) => {
                const speed = (index % 5 + 1) * 0.05; // Vary speed slightly
                const x = mouseX * speed;
                const y = mouseY * speed;
                
                // Get existing transform values (from CSS animation)
                const currentTransform = window.getComputedStyle(particle).transform;
                // Add parallax translation on top of existing transform
                particle.style.transform = `${currentTransform.includes('matrix') ? currentTransform : 'translate(0,0)'} translate(${x}px, ${y}px)`;
            });
        });

        // Initial setup for existing PHP messages on page load
        document.addEventListener('DOMContentLoaded', () => {
            const phpError = "<?php echo $err; ?>";
            const phpMsg = "<?php echo $msg; ?>";

            if (phpError) {
                showMessage(phpError, 'error');
            } else if (phpMsg) {
                showMessage(phpMsg, 'success');
            }
        });
    </script>
</body>
</html>