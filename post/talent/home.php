<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talent Post - Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .navbar {
            background-color: #f0f0f0;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar a {
            margin: 0 15px;
            text-decoration: none;
            color: #007bff; /* A nice blue for links */
            font-weight: bold;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-top: 50px;
            font-size: 2.5em;
        }
        p {
            text-align: center;
            font-size: 1.1em;
            margin-bottom: 30px;
        }
        .button-container {
            text-align: center;
            margin-top: 30px;
        }
        .button-container button {
            padding: 12px 25px;
            font-size: 1em;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .button-container button:hover {
            opacity: 0.9;
        }
        .submit-button {
            background-color: #4CAF50; /* Green */
            color: white;
        }
        .view-button {
            background-color: #008CBA; /* Blue */
            color: white;
        }
        .qanda-button {
            background-color: #f44336; /* Red */
            color: white;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="home.php">Home</a>
        <a href="post.php">Submit Talent</a>
        <a href="posts.php">View All Posts</a>
        <a href="qanda.php">Q&A Board</a>
    </div>

    <h1>Welcome to Talent Post!</h1>
    <p>Share your talents with the world or explore what others have shared.</p>

    <div class="button-container">
        <button onclick="location.href='post.php'" class="submit-button">Submit Your Talent</button>
        <button onclick="location.href='posts.php'" class="view-button">View All Talents</button>
        <button onclick="location.href='qanda.php'" class="qanda-button">Go to Q&A Board</button>
    </div>
</body>
</html>
