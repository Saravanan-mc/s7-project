
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Student Portal</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f9f9f9;
    }
    .navbar {
      background-color: #2c3e50;
      padding: 10px 20px;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
    }
    .navbar a {
      color: #ecf0f1;
      text-decoration: none;
      margin: 8px 12px;
      display: inline-flex;
      align-items: center;
      font-size: 15px;
    }
    .navbar a i {
      margin-right: 6px;
    }
    .navbar a.active,
    .navbar a:hover {
      background-color: #34495e;
      padding: 8px 12px;
      border-radius: 6px;
    }
    .content {
      padding: 20px;
    }
    @media (max-width: 768px) {
      .navbar {
        flex-direction: column;
        align-items: flex-start;
      }
      .navbar a {
        margin: 6px 0;
      }
    }
  </style>
</head>
<body>

<!-- Top Navbar -->
<nav class="navbar">
  <a href="stdhome2.php"><i class="fas fa-home"></i> Home</a>
  <a href="youtube.php"><i class="fas fa-video"></i> Video</a>
  <a href="stdgbook.php"><i class="fas fa-book"></i> Book</a>
  <a href="stdbook.php"><i class="fas fa-file-pdf"></i> PDF Book</a>
  <a href="stdvideo.php"><i class="fas fa-play-circle"></i> Video Book</a>
  <a href="stdsocial.php"><i class="fas fa-globe"></i> Social</a>
  <a href="stdupdate2.php"><i class="fas fa-bell"></i> Update</a>
  <a href="stdsee2.php"><i class="fas fa-envelope"></i> Notification</a>
  <a href="ztool.php"><i class="fas fa-tools"></i> Tools</a>
  <a href="stdwiki.php"><i class="fas fa-wikipedia-w"></i> Wiki</a>
  <a href="stdpdfdrive.php"><i class="fas fa-folder-open"></i> PDF Drive</a>
</nav>

<!-- Page Content -->
<div class="content">
  <!-- Your content here -->
</div>

<!-- JS to highlight active link -->
<script>
  const currentPage = window.location.pathname.split('/').pop();
  document.querySelectorAll('.navbar a').forEach(link => {
    if (link.getAttribute('href') === currentPage) {
      link.classList.add('active');
    }
  });
</script>

</body>
</html>
