<?php
// Include your header/navigation/layout
include '../index.php';
exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student Doubt Forum - Welcome</title>
  <style>
    body {
      font-family: sans-serif;
      margin: 20px;
      background-color: #f4f7f6;
      color: #333;
      margin-left: 250px;
      padding: 20px;
    }
    .container {
      max-width: 600px;
      margin: 100px auto;
      background-color: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      text-align: center;
    }
    h1 {
      color: #007bff;
      margin-bottom: 20px;
    }
    p {
      font-size: 1.1em;
      margin-bottom: 30px;
    }
    .navigation {
      position: absolute;
      top: 20px;
      right: 20px;
      display: flex;
      gap: 10px;
    }
    .navigation a {
      text-decoration: none;
      color: white;
      background-color: #28a745;
      padding: 8px 15px;
      border-radius: 5px;
      font-size: 0.9em;
      transition: background-color 0.3s ease;
      white-space: nowrap;
    }
    .navigation a:hover {
      background-color: #218838;
    }
    .main-content-area {
      margin-top: 80px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="navigation">
    <a href="home.php">Welcome</a>
    <a href="create_post.php">Post Doubt</a>
    <a href="view_feed.php">Doubt Feed</a>
  </div>

  <div class="container main-content-area">
    <h1>Welcome to the Local Student Doubt Forum!</h1>
    <p>Your platform to post questions, get answers, and help your peers.</p>
    <p>Use the navigation above to get started.</p>
  </div>
</body>
</html>
