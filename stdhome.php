<?php
session_start();


include 'index.php';

// --- Configuration ---
define('DB_HOST', 'localhost');
define('DB_NAME', 'regdata');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// --- Database Connection ---
$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
$options = [
    PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES  => false,
];

try {
    $db = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    die("Database connection failed.");
}

// --- Session and Authorization Check ---
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT email, name, roll_number, department, academic_year, role FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user || !in_array($user['role'], ['student', 'alumni'])) {
    session_unset();
    session_destroy();
    header("Location: login.php?err=unauthorized");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student/Alumni Home - BITSathy</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-blue: #3498db;
      --secondary-dark-blue: #2c3e50;
      --accent-green: #2ecc71;
      --danger-red: #e74c3c;
      --text-dark: #333;
      --text-light: #fff;
      --bg-light: #f4f7f6;
      --card-bg: rgba(255,255,255,0.98);
      --shadow: 0 8px 30px rgba(0,0,0,0.15);
      --border-radius: 12px;
      --transition-speed: 0.3s ease;
    }

    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-dark-blue) 100%);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      overflow: hidden;
      position: relative;
      color: var(--text-dark);
      margin-left: 250px;
            padding: 20px;
    }
    .content {
            margin-left: 250px;
            padding: 20px;
        }

    .background-bubbles {
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      z-index: -1;
      overflow: hidden;
      pointer-events: none;
    }

    .background-bubbles li {
      position: absolute;
      list-style: none;
      display: block;
      width: 40px;
      height: 40px;
      background: rgba(255,255,255,0.15);
      animation: animateBubbles 25s linear infinite;
      bottom: -180px;
      border-radius: 50%;
    }

    .background-bubbles li:nth-child(1) { left: 20%; width: 90px; height: 90px; animation-delay: 0s; animation-duration: 20s; }
    .background-bubbles li:nth-child(2) { left: 5%; width: 30px; height: 30px; animation-delay: 2s; animation-duration: 15s; }
    .background-bubbles li:nth-child(3) { left: 70%; width: 60px; height: 60px; animation-delay: 4s; animation-duration: 22s; }
    .background-bubbles li:nth-child(4) { left: 40%; width: 80px; height: 80px; animation-delay: 0s; animation-duration: 18s; }
    .background-bubbles li:nth-child(5) { left: 65%; width: 50px; height: 50px; animation-delay: 0s; animation-duration: 10s; }
    .background-bubbles li:nth-child(6) { left: 75%; width: 110px; height: 110px; animation-delay: 3s; animation-duration: 28s; }
    .background-bubbles li:nth-child(7) { left: 35%; width: 150px; height: 150px; animation-delay: 7s; animation-duration: 35s; }
    .background-bubbles li:nth-child(8) { left: 50%; width: 25px; height: 25px; animation-delay: 15s; animation-duration: 45s; }
    .background-bubbles li:nth-child(9) { left: 20%; width: 15px; height: 15px; animation-delay: 2s; animation-duration: 35s; }
    .background-bubbles li:nth-child(10) { left: 85%; width: 150px; height: 150px; animation-delay: 0s; animation-duration: 11s; }

    @keyframes animateBubbles {
      0% { transform: translateY(0) rotate(0deg); opacity: 1; border-radius: 50%; }
      100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; border-radius: 50%; }
    }

    .container {
      background: var(--card-bg);
      padding: 40px;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      text-align: center;
      max-width: 600px;
      z-index: 1;
      width: 90%;
      box-sizing: border-box;
      animation: fadeIn 0.8s ease-out;
      
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    h2 {
      font-size: 2.2em;
      color: var(--primary-blue);
      margin-bottom: 25px;
      font-weight: 600;
    }

    .details {
      text-align: left;
      background: var(--bg-light);
      padding: 25px;
      border-radius: 10px;
      margin-bottom: 35px;
      border: 1px solid rgba(0,0,0,0.08);
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .details p {
      margin: 12px 0;
      color: var(--text-dark);
      font-size: 1.05em;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .details p strong {
      color: var(--secondary-dark-blue);
      flex-shrink: 0;
      min-width: 120px;
    }

    .details p i {
        color: var(--primary-blue);
        font-size: 1.1em;
    }

    .logout-btn {
      background: var(--danger-red);
      color: var(--text-light);
      border: none;
      padding: 14px 30px;
      border-radius: var(--border-radius);
      cursor: pointer;
      font-weight: 600;
      text-decoration: none;
      font-size: 1.1em;
      transition: background-color var(--transition-speed), transform var(--transition-speed), box-shadow var(--transition-speed);
      display: inline-flex;
      align-items: center;
      gap: 10px;
    }

    .logout-btn:hover {
      background: #c0392b;
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
    }

    @media (max-width: 768px) {
        .container {
            padding: 30px;
        }
        h2 {
            font-size: 1.8em;
        }
        .details p {
            font-size: 0.95em;
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
        .details p strong {
            min-width: unset;
        }
    }

    @media (max-width: 480px) {
        .container {
            padding: 20px;
            border-radius: 8px;
        }
        h2 {
            font-size: 1.5em;
            margin-bottom: 20px;
        }
        .details {
            padding: 15px;
            margin-bottom: 25px;
        }
        .details p {
            font-size: 0.9em;
        }
        .logout-btn {
            padding: 10px 20px;
            font-size: 1em;
        }
    }
  </style>
</head>
<body>

  <ul class="background-bubbles">
    <li></li><li></li><li></li><li></li><li></li>
    <li></li><li></li><li></li><li></li><li></li>
  </ul>

  <div class="container">
    <h2>Welcome, <?= htmlspecialchars($user['name']) ?></h2>
    <div class="details">
      <p><i class="fas fa-id-badge"></i><strong>Roll Number:</strong> <?= htmlspecialchars($user['roll_number']) ?></p>
      <p><i class="fas fa-envelope"></i><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
      <p><i class="fas fa-building"></i><strong>Department:</strong> <?= htmlspecialchars($user['department']) ?></p>
      <p><i class="fas fa-calendar-alt"></i><strong>Academic Year:</strong> <?= htmlspecialchars($user['academic_year']) ?></p>
      <p><i class="fas fa-user-tag"></i><strong>Role:</strong> <?= htmlspecialchars(ucfirst($user['role'])) ?></p>
    </div>
    <a class="logout-btn" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>

</body>
</html>