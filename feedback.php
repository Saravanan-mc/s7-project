<?php
session_start(); // Start the session

// Initialize variables
$name = $student_id = $subject = $feedback = "";
$date_time = "";

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "feedback_db";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajax']) && $_POST['ajax'] == 1) {
    // Validate if all POST data is available
    if (isset($_POST['name'], $_POST['student_id'], $_POST['subject'], $_POST['feedback'])) {
        $name = htmlspecialchars(strip_tags($_POST['name']));
        $student_id = htmlspecialchars(strip_tags($_POST['student_id']));
        $subject = htmlspecialchars(strip_tags($_POST['subject']));
        $feedback = htmlspecialchars(strip_tags($_POST['feedback']));
        $date_time = date("Y-m-d H:i:s");

        // Store data in session variables
        $_SESSION['name'] = $name;
        $_SESSION['student_id'] = $student_id;
        $_SESSION['subject'] = $subject;
        $_SESSION['feedback'] = $feedback;
        $_SESSION['date_time'] = $date_time;

        // Validate inputs
        if (empty($name) || empty($student_id) || empty($subject) || empty($feedback)) {
            echo json_encode(array('status' => 'error', 'message' => 'All fields are required.'));
            exit();
        }

        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            echo json_encode(array('status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error));
            exit();
        }

        // Prepare and execute the query
        $stmt = $conn->prepare("INSERT INTO feedbacks (name, student_id, subject, feedback, submission_time) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssss", $name, $student_id, $subject, $feedback, $date_time);
            if ($stmt->execute()) {
                echo json_encode(array('status' => 'success', 'message' => 'Feedback submitted successfully.'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Failed to submit feedback.'));
            }
            $stmt->close();
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to prepare statement.'));
        }
        $conn->close();
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Invalid form submission.'));
    }
    exit();
}
include 'index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Feedback Form</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        /* --- Improved Feedback Form CSS --- */

        /* Import Google Font */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        body {
            /* Vibrant multi-color gradient background */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #89f7fe 100%);
            color: #333;
            margin: 0;
            padding: 10px; /* Add padding for smaller screens */
            display: flex;
            /* Center content vertically and horizontally */
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Use min-height for flexibility */
            box-sizing: border-box;
            overflow-x: hidden; /* Prevent horizontal scroll */

        }

        /* Container for the form and title */
        .content {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column; /* Stack title and form vertically */
             margin-left:280px;
        }

        h2#formTitle { /* Target the h2 specifically */
            text-align: center;
            color: #ffffff; /* White title for better contrast on gradient */
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2); /* Subtle shadow */
            margin-bottom: 25px;
            font-size: 2.2rem; /* Slightly larger title */
            font-weight: 600;
            animation: titleFadeInDown 0.8s ease-out forwards; /* Smoother animation */
        }

        /* Animation for title */
        @keyframes titleFadeInDown {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        form#feedbackForm { /* Target the form specifically */
            /* Semi-transparent white background */
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px 40px; /* More padding */
            border-radius: 15px; /* Slightly softer corners */
            /* More pronounced but softer shadow */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px; /* Max width for larger screens */
            box-sizing: border-box;
            margin: 0 auto; /* Center form horizontally */
            animation: formFadeInUp 1s ease-out forwards; /* Entrance animation */
            animation-delay: 0.2s; /* Start after title */
            opacity: 0; /* Start hidden for animation */
        }

        /* Animation for form */
        @keyframes formFadeInUp {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        label.label { /* Target labels specifically */
            display: block;
            font-weight: 600; /* Bolder labels */
            margin-bottom: 8px;
            font-size: 0.95rem; /* Slightly adjusted size */
            color: #555; /* Dark grey for readability */
        }

        /* Styling for text inputs and textarea */
        input[type="text"].input-field,
        textarea#feedback.textarea-field { /* Target inputs/textarea specifically */
            width: 100%;
            padding: 12px 15px; /* Comfortable padding */
            margin-bottom: 20px; /* Consistent spacing */
            border: 1px solid #ccc;
            border-radius: 8px; /* Rounded inputs */
            font-size: 1rem; /* Standard input font size */
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif; /* Ensure font consistency */
            color: #333;
            background-color: #fdfdfd; /* Slightly off-white input background */
            /* Smooth transition for focus */
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        textarea#feedback.textarea-field {
            resize: vertical; /* Allow vertical resize only */
            min-height: 120px; /* Slightly taller textarea */
        }

        /* Focus effect for inputs and textarea */
        input[type="text"].input-field:focus,
        textarea#feedback.textarea-field:focus {
            border-color: #764ba2; /* Highlight with a theme color */
            outline: none; /* Remove default outline */
            /* Subtle glow effect */
            box-shadow: 0 0 0 3px rgba(118, 75, 162, 0.2);
        }

        /* Submit button styling */
        input[type="submit"].submit-button { /* Target submit button specifically */
            width: 100%;
            padding: 12px 15px;
            font-size: 1.1rem; /* Slightly larger text */
            font-weight: 600;
            color: #fff;
            /* Gradient background matching the theme */
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            /* Smooth transitions for hover/active states */
            transition: background 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            -webkit-appearance: none; /* Fix rendering issues on some browsers */
            appearance: none;
        }

        input[type="submit"].submit-button:hover {
            /* Slightly adjust gradient or use filter for hover effect */
            background: linear-gradient(135deg, #764ba2, #667eea);
            transform: translateY(-3px); /* Lift effect */
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15); /* Enhanced shadow */
        }

        input[type="submit"].submit-button:active {
            transform: translateY(0px); /* Press down effect */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Reset shadow */
        }

        /* --- Responsive Design Adjustments --- */

        @media (max-width: 768px) {
            body { padding: 15px; }
            h2#formTitle { font-size: 1.8rem; }
            form#feedbackForm { padding: 25px 30px; max-width: 90%; }
            input[type="text"].input-field, textarea#feedback.textarea-field { padding: 10px 12px; font-size: 0.95rem; }
            input[type="submit"].submit-button { padding: 11px 15px; font-size: 1rem; }
        }

        @media (max-width: 480px) {
            body { padding: 10px; background-attachment: fixed; /* Prevent gradient scrolling issue */ }
            h2#formTitle { font-size: 1.5rem; margin-bottom: 20px; }
            form#feedbackForm { padding: 20px 20px; max-width: 95%; border-radius: 10px; }
            label.label { font-size: 0.9rem; }
            input[type="text"].input-field, textarea#feedback.textarea-field { padding: 9px 10px; font-size: 0.9rem; margin-bottom: 15px; }
            textarea#feedback.textarea-field { min-height: 100px; }
            input[type="submit"].submit-button { padding: 10px 15px; font-size: 0.95rem; }
        }
    </style>
</head>
<body>
    <div class="content">
    <br><br>
        <h2 id="formTitle">Student Feedback Form</h2>

        <form id="feedbackForm" class="feedback-form" method="POST" action="">
            <label for="name" class="label">Name:</label>
            <input type="text" id="name" name="name" class="input-field" required>

            <label for="student_id" class="label">Student ID:</label>
            <input type="text" id="student_id" name="student_id" class="input-field" required>

            <label for="subject" class="label">Subject:</label>
            <input type="text" id="subject" name="subject" class="input-field" required>

            <label for="feedback" class="label">Feedback:</label>
            <textarea id="feedback" name="feedback" class="textarea-field" rows="4" required></textarea>

            <input type="submit" value="Submit Feedback" class="submit-button">
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#feedbackForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                // Get form data
                var formData = {
                    name: $('#name').val().trim(), // Trim whitespace
                    student_id: $('#student_id').val().trim(),
                    subject: $('#subject').val().trim(),
                    feedback: $('#feedback').val().trim(),
                    ajax: 1 // Flag to indicate this is an AJAX request for the PHP backend
                };

                 // Basic client-side validation (optional but good practice)
                if (!formData.name || !formData.student_id || !formData.subject || !formData.feedback) {
                    alert('Please fill out all fields.');
                    return; // Stop the submission if fields are empty
                }

                // Disable button during submission
                var submitButton = $(this).find('.submit-button');
                submitButton.prop('disabled', true).val('Submitting...');


                // Perform the AJAX request
                $.ajax({
                    type: "POST",
                    url: "", // Post to the current page (where the PHP handler is)
                    data: formData,
                    dataType: "json", // Expect JSON response from the server
                    success: function(response) {
                        if (response.status == 'success') {
                            alert(response.message); // Show success message
                            $('#feedbackForm')[0].reset(); // Reset the form fields
                        } else {
                            // Show error message from the server
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // Handle network errors or unexpected server responses
                        console.error("AJAX Error:", textStatus, errorThrown, jqXHR.responseText); // Log detailed error
                        alert('An error occurred while submitting the form. Please check the console or try again.');
                    },
                    complete: function() {
                         // Re-enable button whether success or error
                        submitButton.prop('disabled', false).val('Submit Feedback');
                    }
                });
            });
        });
    </script>

</body>
</html>