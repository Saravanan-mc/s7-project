<?php
session_start();

// Define form categories as a constant
const FORM_CATEGORIES = [
    'academic' => ['title' => 'Academic', 'icon' => 'fas fa-brain'],
    'wellness' => ['title' => 'Sports & Wellness', 'icon' => 'fas fa-dumbbell'],
    'technical' => ['title' => 'Technical', 'icon' => 'fas fa-laptop-code'],
    'core' => ['title' => 'Core Branch Specific', 'icon' => 'fas fa-microchip'],
    'aptitude' => ['title' => 'Aptitude & Reasoning', 'icon' => 'fas fa-calculator'],
    'soft' => ['title' => 'Soft Skills', 'icon' => 'fas fa-comments'],
    'career' => ['title' => 'Career & Placement', 'icon' => 'fas fa-briefcase']
];

// Redirect if user is not logged in as a student
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_roll_number'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    $_SESSION['form_message'] = ['type' => 'error', 'text' => 'Please log in as a student to access this page.'];
    header("Location: login.php");
    exit();
}

$loggedInUserId = htmlspecialchars($_SESSION['user_id'] ?? '1');
$loggedInRollNumber = htmlspecialchars($_SESSION['user_roll_number'] ?? 'R12345');
$loggedInName = htmlspecialchars($_SESSION['user_name'] ?? 'SARAVANAN M (7376221EC294)');

// Retrieve and unset form message from session
$form_message = $_SESSION['form_message'] ?? null;
unset($_SESSION['form_message']);

// CSRF Token generation and validation
function generateCsrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCsrfToken(string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Path to the data file
$dataFilePath = __DIR__ . '/student_data.json';

// Function to load data from JSON file
function loadStudentData(string $filePath): array {
    if (!file_exists($filePath)) {
        return [];
    }
    $fileContent = file_get_contents($filePath);
    if ($fileContent === false) {
        return [];
    }
    $data = json_decode($fileContent, true);
    return is_array($data) ? $data : [];
}

// Function to save data to JSON file
function saveStudentData(string $filePath, array $data): bool {
    $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($jsonData === false) {
        error_log("Failed to encode JSON data for saving.");
        return false;
    }
    return file_put_contents($filePath, $jsonData) !== false;
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
        $_SESSION['form_message'] = ['type' => 'error', 'text' => 'Invalid form submission. Please try again.'];
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }

    $currentData = loadStudentData($dataFilePath);
    $errors = [];

    // Handle clearing all data
    if (isset($_POST['action']) && $_POST['action'] === 'clear_all_data') {
        $updatedData = array_filter($currentData, function($entry) use ($loggedInUserId) {
            return ($entry['student_user_id'] ?? '') !== $loggedInUserId;
        });

        if (saveStudentData($dataFilePath, array_values($updatedData))) {
            $_SESSION['form_message'] = ['type' => 'success', 'text' => 'All your development data has been cleared.'];
        } else {
            $_SESSION['form_message'] = ['type' => 'error', 'text' => 'Failed to clear data. Please try again.'];
        }
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    } else { // Handle form submission
        $formData = [];
        $formDate = filter_input(INPUT_POST, 'form_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $studentUserIdFromForm = filter_input(INPUT_POST, 'student_user_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $submissionTimestamp = filter_input(INPUT_POST, 'submission_timestamp', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Security check for user ID
        if ($studentUserIdFromForm !== $loggedInUserId) {
             $errors[] = 'Security alert: Mismatched user ID. Submission denied.';
        }

        if (empty($formDate)) {
            $errors[] = 'Form date is required.';
        }

        // Process each category
        foreach (FORM_CATEGORIES as $key => $details) {
            $area = filter_input(INPUT_POST, "{$key}_area", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $areaOther = filter_input(INPUT_POST, "{$key}_area_other", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $rating = filter_input(INPUT_POST, "{$key}_rating", FILTER_VALIDATE_INT);

            // Only include category data if something was provided
            if (!empty($area) || !empty($areaOther) || ($rating !== null && $rating !== false)) {
                $categoryData = [
                    'area' => $area,
                    'rating' => ($rating !== false) ? $rating : null
                ];
                if ($area === 'Other' && !empty($areaOther)) {
                    $categoryData['area_other'] = $areaOther;
                }
                $formData[$key] = $categoryData;

                // Validate rating if provided
                if ($rating !== null && $rating !== false) {
                    if ($rating < 1 || $rating > 5) {
                        $errors[] = "Rating for {$details['title']} must be between 1 and 5.";
                    }
                }
            }
        }

        // Check if at least one rating was provided
        $hasRating = false;
        foreach ($formData as $categoryKey => $categoryValue) {
            if (isset($categoryValue['rating']) && $categoryValue['rating'] !== null) {
                $hasRating = true;
                break;
            }
        }
        if (!$hasRating) {
            $errors[] = 'Please provide at least one rating between 1 and 5 in any category.';
        }

        // Handle errors or save data
        if (!empty($errors)) {
            $_SESSION['form_message'] = ['type' => 'error', 'text' => implode('<br>', $errors)];
        } else {
            $formData['form_date'] = $formDate;
            $formData['student_user_id'] = $loggedInUserId;
            $formData['submission_timestamp'] = $submissionTimestamp;

            $currentData[] = $formData; // Add new submission

            if (saveStudentData($dataFilePath, $currentData)) {
                $_SESSION['form_message'] = ['type' => 'success', 'text' => 'Development report submitted successfully!'];
            } else {
                $_SESSION['form_message'] = ['type' => 'error', 'text' => 'Failed to save data. Please try again later.'];
            }
        }
    }

    header("Location: " . $_SERVER['REQUEST_URI']); // Redirect to clear POST data
    exit();
}

// Initial values for the form
$initialSelectedDate = date('Y-m-d');
$csrfToken = generateCsrfToken();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Development Tracker - Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Keyframe Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes scaleIn {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        /* Glass Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Modal Overrides and Animations */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background: rgba(0, 0, 0, 0.6); /* Darker overlay */
            backdrop-filter: blur(5px);
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease-out; /* Apply fadeIn animation */
        }

        .modal-content {
            background: white; /* Changed from #fefefe */
            margin: auto;
            padding: 2rem; /* Increased padding */
            border-radius: 1rem; /* More rounded corners */
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); /* Stronger shadow */
            max-width: 500px;
            width: 90%;
            text-align: center;
            animation: scaleIn 0.3s ease-out; /* Apply scaleIn animation */
        }

        .modal-header {
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            margin-bottom: 15px;
        }
        .modal-header h2 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }
        .modal-footer {
            padding-top: 15px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        /* Body Gradient Background */
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        /* Header Gradient (for potential use, though not directly applied to current header) */
        .header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Specific element enhancements */
        .bg-white { /* Apply glass effect to main container */
            background: rgba(255, 255, 255, 0.85); /* Slightly less opaque for main content */
            backdrop-filter: blur(15px); /* Slightly less blur than general glass */
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); /* Retain original shadow */
        }

        .bg-blue-50 { /* Apply glass effect to form background */
            background: rgba(255, 255, 255, 0.7); /* Even less opaque */
            backdrop-filter: blur(10px); /* Less blur */
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
        }

        .category-card { /* New class for individual category sections */
            background: rgba(255, 255, 255, 0.9); /* Slightly more opaque for inner cards */
            backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            transition: all 0.2s ease-in-out;
        }
        .category-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        /* Button Enhancements */
        button {
            transition: all 0.3s ease-in-out;
            transform: scale(1); /* Ensure initial state for transform */
        }
        button:hover {
            transform: scale(1.05);
            filter: brightness(1.1); /* Slightly brighten on hover */
        }

        /* Input field focus glow */
        select:focus,
        input[type="date"]:focus,
        input[type="text"]:focus,
        input[type="number"]:focus {
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.4); /* Deeper blue glow */
            border-color: #6366f1; /* Tailwind's indigo-500 equivalent */
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen p-4 font-inter">
    <div class="bg-white p-8 rounded-lg shadow-xl max-w-4xl w-full">
        <div class="header-info flex justify-between items-center mb-6 border-b pb-4">
            <p class="text-lg text-gray-700">Logged in as: <strong id="loggedInInfo"><?= $loggedInName ?> (<?= $loggedInRollNumber ?>)</strong></p>
            <button id="logoutButton" class="text-red-500 hover:text-red-700 font-medium">
                <i class="fas fa-sign-out-alt mr-2"></i>Logout
            </button>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Student Development Form</h1>
        <p class="optional-note text-center text-gray-500 mb-6">All fields in this form are optional, but ratings will update your graph.</p>

        <div id="messageBox" class="message-box hidden px-4 py-3 rounded-lg mb-4 text-sm font-medium">
            <?php if ($form_message): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const messageBox = document.getElementById('messageBox');
                        messageBox.innerHTML = '<?= addslashes($form_message['text']) ?>';
                        messageBox.classList.remove('hidden');
                        if ('<?= $form_message['type'] ?>' === 'success') {
                            messageBox.classList.add('bg-green-100', 'text-green-800');
                        } else if ('<?= $form_message['type'] ?>' === 'error') {
                            messageBox.classList.add('bg-red-100', 'text-red-800');
                        }
                    });
                </script>
            <?php endif; ?>
        </div>

        <form id="developmentForm" class="mb-8 p-6 bg-blue-50 rounded-lg shadow-inner" method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
            <div class="form-header-inputs grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-gray-700 font-medium">Your User ID:</label>
                    <p id="displayUserId" class="font-normal text-gray-800 mt-1"><?= $loggedInUserId ?></p>
                    <input type="hidden" name="student_user_id" id="studentUserId" value="<?= $loggedInUserId ?>">
                </div>
                <div>
                    <label for="selectedDate" class="block text-gray-700 font-medium">Select Date:</label>
                    <input type="date" id="selectedDate" name="form_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 p-2" aria-label="Select submission date" value="<?= $initialSelectedDate ?>">
                </div>
                <input type="hidden" name="submission_timestamp" id="submissionTimestampHidden">
            </div>

            <?php foreach (FORM_CATEGORIES as $key => $details): ?>
                <div class="mb-6 p-4 border rounded-lg bg-white shadow-sm category-card"> <h2 class="text-xl font-semibold text-blue-700 mb-3"><i class="<?= $details['icon'] ?> mr-2"></i><?= $details['title'] ?></h2>
                    <label for="<?= $key ?>_area" class="block text-gray-700 font-medium">Select Focus Area:</label>
                    <select name="<?= $key ?>_area" id="<?= $key ?>_area" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 p-2" onchange="toggleOtherInput(this, '<?= $key ?>_other_container')">
                        <option value="">-- Select One (Optional) --</option>
                        <?php
                        // Dynamically generate options based on category (you might have fixed options or fetch from DB)
                        // For demonstration, using simple examples. You can expand this.
                        switch ($key) {
                            case 'academic':
                                echo '<option value="Subject">Subject</option>';
                                echo '<option value="Note-taking">Note-taking</option>';
                                echo '<option value="Research">Research</option>';
                                echo '<option value="Exam Preparation">Exam Preparation</option>';
                                break;
                            case 'wellness':
                                echo '<option value="Physical Fitness">Physical Fitness</option>';
                                echo '<option value="Mental Health">Mental Health</option>';
                                echo '<option value="Stress Management">Stress Management</option>';
                                echo '<option value="Team Activities">Team Activities</option>';
                                break;
                            case 'technical':
                                echo '<option value="Programming (C, C++, Python, Java)">Programming (C, C++, Python, Java)</option>';
                                echo '<option value="Web Development">Web Development</option>';
                                echo '<option value="App Development">App Development</option>';
                                echo '<option value="Git & GitHub">Git & GitHub</option>';
                                echo '<option value="APIs / Firebase">APIs / Firebase</option>';
                                echo '<option value="Portfolio / Projects">Portfolio / Projects</option>';
                                break;
                            case 'core':
                                echo '<option value="Branch-Specific Knowledge">Branch-Specific Knowledge</option>';
                                echo '<option value="Circuit Design">Circuit Design</option>';
                                echo '<option value="Simulations">Simulations</option>';
                                echo '<option value="CAD Modeling">CAD Modeling</option>';
                                echo '<option value="Hardware (Arduino, IoT)">Hardware (Arduino, IoT)</option>';
                                echo '<option value="AutoCAD / MATLAB / Proteus">AutoCAD / MATLAB / Proteus</option>';
                                break;
                            case 'aptitude':
                                echo '<option value="Quantitative">Quantitative</option>';
                                echo '<option value="Logical Reasoning">Logical Reasoning</option>';
                                echo '<option value="Verbal">Verbal</option>';
                                echo '<option value="Puzzle Solving">Puzzle Solving</option>';
                                echo '<option value="Placement / GATE Prep">Placement / GATE Prep</option>';
                                break;
                            case 'soft':
                                echo '<option value="Communication">Communication</option>';
                                echo '<option value="Presentation / Public Speaking">Presentation / Public Speaking</option>';
                                echo '<option value="Leadership / Teamwork">Leadership / Teamwork</option>';
                                echo '<option value="Email Writing / Networking">Email Writing / Networking</option>';
                                break;
                            case 'career':
                                echo '<option value="Resume Building">Resume Building</option>';
                                echo '<option value="LinkedIn Optimization">LinkedIn Optimization</option>';
                                echo '<option value="Interview Preparation">Interview Preparation</option>';
                                echo '<option value="Job / Internship Search">Job / Internship Search</option>';
                                echo '<option value="Freelancing / Certifications">Freelancing / Certifications</option>';
                                break;
                        }
                        ?>
                        <option value="Other">Other</option>
                    </select>
                    <div id="<?= $key ?>_other_container" class="mt-2 hidden">
                        <label for="<?= $key ?>_area_other" class="block text-gray-700 font-medium">Other (please specify):</label>
                        <input type="text" name="<?= $key ?>_area_other" id="<?= $key ?>_area_other" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 p-2" aria-label="Specify other <?= $details['title'] ?> focus area">
                    </div>
                    <label for="<?= $key ?>_rating" class="block text-gray-700 font-medium mt-3">Rate Your Development (1 to 5):</label>
                    <input type="number" name="<?= $key ?>_rating" id="<?= $key ?>_rating" min="1" max="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 p-2" aria-label="<?= $details['title'] ?> development rating (1 to 5)">
                </div>
            <?php endforeach; ?>

            <div class="flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4 mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    Submit Development Report
                </button>
                <button type="button" id="clearFormDataButton" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-3 px-8 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    Clear Form
                </button>
            </div>
        </form>

        <hr class="my-8 border-t-2 border-gray-200">

        <div class="flex justify-center mt-6">
            <a href="datareport.php" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-8 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                View My Development Report
            </a>
        </div>

    </div>

    <div id="clearDataModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirm Data Deletion</h2>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to clear ALL your development data? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button id="cancelClearData" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-md">Cancel</button>
                <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <input type="hidden" name="action" value="clear_all_data">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md">
                        Clear Data
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            document.getElementById('selectedDate').value = `${year}-${month}-${day}`;
            document.getElementById('submissionTimestampHidden').value = Date.now();

            const messageBox = document.getElementById('messageBox');
            if (messageBox.innerHTML.trim() !== '') {
                messageBox.classList.remove('hidden');
            }

            document.getElementById('logoutButton').addEventListener('click', function() {
                window.location.href = 'login.php?action=logout';
            });

            document.getElementById('clearFormDataButton').addEventListener('click', function() {
                document.getElementById('developmentForm').reset();
                // Hide all "other" input containers
                <?php foreach (FORM_CATEGORIES as $key => $details): ?>
                    document.getElementById('<?= $key ?>_other_container').classList.add('hidden');
                <?php endforeach; ?>
                document.getElementById('selectedDate').value = `${year}-${month}-${day}`;
            });

            const clearDataModal = document.getElementById('clearDataModal');
            const openClearDataBtn = document.getElementById('openClearDataModal'); // This button is not present on this page
            const cancelClearDataBtn = document.getElementById('cancelClearData');

            // The 'openClearDataModal' button is typically on a report page, not the input page.
            // If you intend to add a "Clear All My Data" button on this input page,
            // you'd uncomment the following block and add a button with id="openClearDataModal"
            /*
            if (openClearDataBtn) {
                openClearDataBtn.addEventListener('click', () => {
                    clearDataModal.style.display = 'flex';
                });
            }
            */

            cancelClearDataBtn.addEventListener('click', () => {
                clearDataModal.style.display = 'none';
            });

            window.addEventListener('click', (event) => {
                if (event.target == clearDataModal) {
                    clearDataModal.style.display = 'none';
                }
            });
        });

        function toggleOtherInput(selectElement, containerId) {
            const container = document.getElementById(containerId);
            container.classList.toggle('hidden', selectElement.value !== 'Other');
            if (selectElement.value !== 'Other') {
                const otherInput = document.getElementById(`${selectElement.id}_other`);
                if (otherInput) { // Check if the other input element exists
                    otherInput.value = '';
                }
            }
        }
    </script>
</body>
</html>