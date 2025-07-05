<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Q&A Board</title>
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
            color: #007bff;
            font-weight: bold;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-top: 30px;
            margin-bottom: 25px;
        }
        .qanda-container {
            width: 70%;
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            padding-bottom: 30px;
        }
        .qanda-container h3 {
            color: #333;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 1.5em;
        }
        form {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"],
        textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
        }
        textarea {
            resize: vertical;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .questions-list {
            margin-top: 30px;
        }
        .question-item {
            background-color: #f9f9f9;
            border: 1px solid #eee;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }
        .question-item p {
            margin: 0;
            line-height: 1.5;
            color: #444;
        }
        .question-item strong {
            color: #007bff;
            font-size: 1.1em;
        }
        .question-item em {
            display: block;
            margin-top: 8px;
            font-size: 0.85em;
            color: #888;
        }
        .no-questions-message {
            text-align: center;
            font-size: 1.2em;
            color: #666;
            margin-top: 20px;
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

    <h2>Q&A Board</h2>

    <div class="qanda-container">
        <h3>Submit a Question</h3>
        <form action="" method="post">
            <label for="qa_name">Your Name:</label>
            <input type="text" id="qa_name" name="qa_name" required><br>

            <label for="question">Your Question:</label>
            <textarea id="question" name="question" rows="4" required></textarea><br>

            <input type="submit" value="Submit Question">
        </form>

        <h3>All Questions</h3>
        <div class="questions-list">
            <?php
            $qanda_json_file = 'qanda.json';
            $qandas = [];

            // Handle new question submission
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qa_name']) && isset($_POST['question'])) {
                $qa_name = htmlspecialchars($_POST['qa_name']);
                $question = htmlspecialchars($_POST['question']);

                if (file_exists($qanda_json_file)) {
                    $json_data = file_get_contents($qanda_json_file);
                    $qandas = json_decode($json_data, true);
                    if ($qandas === null) {
                        $qandas = [];
                    }
                }

                $new_qanda = [
                    'name' => $qa_name,
                    'question' => $question,
                    'timestamp' => time()
                ];

                $qandas[] = $new_qanda;
                file_put_contents($qanda_json_file, json_encode($qandas, JSON_PRETTY_PRINT));
                // Redirect to prevent form re-submission on refresh
                header('Location: qanda.php');
                exit;
            }

            // Display questions
            if (file_exists($qanda_json_file)) {
                $json_data = file_get_contents($qanda_json_file);
                $qandas = json_decode($json_data, true);
                if ($qandas === null) {
                    $qandas = [];
                }
            }

            if (empty($qandas)) {
                echo "<p class='no-questions-message'>No questions yet. Ask the first one!</p>";
            } else {
                // Sort Q&As by timestamp in descending order (latest first)
                usort($qandas, function($a, $b) {
                    return $b['timestamp'] - $a['timestamp'];
                });

                foreach ($qandas as $qa) {
                    echo "<div class='question-item'>";
                    echo "<p><strong>" . htmlspecialchars($qa['name']) . "</strong> asked:</p>";
                    echo "<p>" . nl2br(htmlspecialchars($qa['question'])) . "</p>";
                    echo "<p><em>" . date('Y-m-d H:i:s', $qa['timestamp']) . "</em></p>";
                    echo "</div>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
