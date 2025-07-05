<?php
session_start();

// Define form categories with enhanced icons and colors
const FORM_CATEGORIES = [
    'academic' => [
        'title' => 'Academic Excellence',
        'icon' => 'fas fa-graduation-cap',
        'color' => '#3B82F6', // Blue
        'bgColor' => '#EFF6FF'
    ],
    'wellness' => [
        'title' => 'Health & Wellness',
        'icon' => 'fas fa-heart-pulse',
        'color' => '#10B981', // Emerald
        'bgColor' => '#ECFDF5'
    ],
    'technical' => [
        'title' => 'Technical Skills',
        'icon' => 'fas fa-code',
        'color' => '#8B5CF6', // Violet
        'bgColor' => '#F3E8FF'
    ],
    'core' => [
        'title' => 'Core Specialization',
        'icon' => 'fas fa-microchip',
        'color' => '#F59E0B', // Amber
        'bgColor' => '#FFFBEB'
    ],
    'aptitude' => [
        'title' => 'Logical Reasoning',
        'icon' => 'fas fa-brain',
        'color' => '#EF4444', // Red
        'bgColor' => '#FEF2F2'
    ],
    'soft' => [
        'title' => 'Interpersonal Skills',
        'icon' => 'fas fa-users',
        'color' => '#EC4899', // Pink
        'bgColor' => '#FDF2F8'
    ],
    'career' => [
        'title' => 'Career Development',
        'icon' => 'fas fa-rocket',
        'color' => '#06B6D4', // Cyan
        'bgColor' => '#ECFEFF'
    ]
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

// Handle POST request for clearing data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
        $_SESSION['form_message'] = ['type' => 'error', 'text' => 'Invalid form submission. Please try again.'];
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }

    if (isset($_POST['action']) && $_POST['action'] === 'clear_all_data') {
        $currentData = loadStudentData($dataFilePath);
        $updatedData = array_filter($currentData, function($entry) use ($loggedInUserId) {
            return ($entry['student_user_id'] ?? '') !== $loggedInUserId;
        });

        if (saveStudentData($dataFilePath, array_values($updatedData))) {
            $_SESSION['form_message'] = ['type' => 'success', 'text' => 'All your development data has been cleared successfully.'];
        } else {
            $_SESSION['form_message'] = ['type' => 'error', 'text' => 'Failed to clear data. Please try again.'];
        }
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}

// Load and process data for charts
$allStudentData = loadStudentData($dataFilePath);
$currentUserData = array_filter($allStudentData, function($entry) use ($loggedInUserId) {
    return ($entry['student_user_id'] ?? '') === $loggedInUserId;
});

// Initialize arrays for chart data
$categoryData = [];
foreach (array_keys(FORM_CATEGORIES) as $key) {
    $categoryData[$key] = [
        'ratingsByDate' => [],
        'focusAreaRatings' => [],
        'allRatings' => []
    ];
}

foreach ($currentUserData as $entry) {
    $date = $entry['form_date'] ?? 'unknown_date';

    foreach (FORM_CATEGORIES as $key => $details) {
        if (isset($entry[$key]) && ($entry[$key]['rating'] !== null || !empty($entry[$key]['area']))) {
            $rating = isset($entry[$key]['rating']) ? (int)$entry[$key]['rating'] : null;
            $area = $entry[$key]['area'] ?? '';
            $otherInput = $entry[$key]['area_other'] ?? '';

            // Add rating to time-series data
            if ($rating !== null) {
                if (!isset($categoryData[$key]['ratingsByDate'][$date])) {
                    $categoryData[$key]['ratingsByDate'][$date] = [];
                }
                $categoryData[$key]['ratingsByDate'][$date][] = [
                    'area' => $area,
                    'otherInput' => $otherInput,
                    'rating' => $rating,
                    'date' => $date
                ];
                $categoryData[$key]['allRatings'][] = $rating;
            }

            // Add data to focus area ratings
            $actualAreaName = (!empty($otherInput)) ? $otherInput : ($area ?: 'General ' . $details['title']);
            if (!isset($categoryData[$key]['focusAreaRatings'][$actualAreaName])) {
                $categoryData[$key]['focusAreaRatings'][$actualAreaName] = [];
            }
            $categoryData[$key]['focusAreaRatings'][$actualAreaName][] = [
                'area' => $area,
                'otherInput' => $otherInput,
                'rating' => $rating,
                'date' => $date
            ];
        }
    }
}

// Calculate overall averages
$overallAverages = [];
foreach (FORM_CATEGORIES as $key => $details) {
    $ratings = $categoryData[$key]['allRatings'];
    $overallAverages[$key] = count($ratings) > 0 ? array_sum($ratings) / count($ratings) : 0;
}

$csrfToken = generateCsrfToken();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Development Analytics Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'scale-in': 'scaleIn 0.4s ease-out',
                    },
                    backgroundImage: {
                        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                        'gradient-conic': 'conic-gradient(from 180deg at 50% 50%, var(--tw-gradient-stops))',
                    }
                }
            }
        }
    </script>
    <style>
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

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease-out;
        }

        .modal-content {
            background: white;
            margin: auto;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            max-width: 500px;
            width: 90%;
            text-align: center;
            animation: scaleIn 0.3s ease-out;
        }

        .chart-container {
            transition: all 0.3s ease;
        }

        .chart-container:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.1));
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="font-sans">
    <div class="header-gradient text-white py-6 mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center space-x-4 mb-4 lg:mb-0">
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <i class="fas fa-chart-line text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">Development Analytics</h1>
                        <p class="text-blue-100">Track your progress across all domains</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                    <div class="bg-white bg-opacity-10 rounded-lg px-4 py-2">
                        <p class="text-sm text-blue-100">Logged in as</p>
                        <p class="font-semibold"><?= $loggedInName ?></p>
                        <p class="text-sm text-blue-200"><?= $loggedInRollNumber ?></p>
                    </div>
                    <button id="logoutButton" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div id="messageBox" class="hidden mb-6 px-6 py-4 rounded-xl shadow-lg animate-fade-in">
            <?php if ($form_message): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const messageBox = document.getElementById('messageBox');
                        messageBox.innerHTML = '<div class="flex items-center"><i class="fas fa-<?= $form_message['type'] === 'success' ? 'check-circle' : 'exclamation-triangle' ?> mr-3 text-lg"></i><?= addslashes($form_message['text']) ?></div>';
                        messageBox.classList.remove('hidden');
                        if ('<?= $form_message['type'] ?>' === 'success') {
                            messageBox.classList.add('bg-green-50', 'text-green-800', 'border', 'border-green-200');
                        } else {
                            messageBox.classList.add('bg-red-50', 'text-red-800', 'border', 'border-red-200');
                        }
                    });
                </script>
            <?php endif; ?>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8 animate-slide-up">
            <?php
            $totalEntries = count($currentUserData);
            $avgOverall = count($overallAverages) > 0 ? array_sum($overallAverages) / count($overallAverages) : 0;
            $bestCategory = array_keys($overallAverages, max($overallAverages))[0] ?? 'none';
            $activeAreas = 0;
            foreach ($categoryData as $data) {
                $activeAreas += count($data['focusAreaRatings']);
            }
            ?>

            <div class="stat-card rounded-xl p-6 text-center text-white">
                <div class="text-3xl font-bold text-blue-300"><?= $totalEntries ?></div>
                <div class="text-sm text-blue-100">Total Submissions</div>
            </div>

            <div class="stat-card rounded-xl p-6 text-center text-white">
                <div class="text-3xl font-bold text-green-300"><?= number_format($avgOverall, 1) ?>/5</div>
                <div class="text-sm text-green-100">Average Rating</div>
            </div>

            <div class="stat-card rounded-xl p-6 text-center text-white">
                <div class="text-2xl font-bold text-yellow-300"><?= FORM_CATEGORIES[$bestCategory]['title'] ?? 'N/A' ?></div>
                <div class="text-sm text-yellow-100">Top Performance</div>
            </div>

            <div class="stat-card rounded-xl p-6 text-center text-white">
                <div class="text-3xl font-bold text-purple-300"><?= $activeAreas ?></div>
                <div class="text-sm text-purple-100">Focus Areas</div>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-8 mb-8 animate-slide-up">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Development Progress Timeline</h2>
                <p class="text-gray-600">Track your improvement across different domains over time</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
                <?php foreach (FORM_CATEGORIES as $key => $details): ?>
                    <div class="chart-container bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div class="p-2 rounded-lg mr-3" style="background-color: <?= $details['bgColor'] ?>;">
                                <i class="<?= $details['icon'] ?> text-lg" style="color: <?= $details['color'] ?>;"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800"><?= $details['title'] ?></h3>
                                <p class="text-sm text-gray-500">Avg: <?= number_format($overallAverages[$key], 1) ?>/5</p>
                            </div>
                        </div>
                        <div class="h-64">
                            <canvas id="<?= $key ?>DevelopmentChart"></canvas>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-8 mb-8 animate-slide-up">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Focus Area Analysis</h2>
                <p class="text-gray-600">Detailed breakdown of your performance in specific areas</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
                <?php foreach (FORM_CATEGORIES as $key => $details): ?>
                    <div class="chart-container bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div class="p-2 rounded-lg mr-3" style="background-color: <?= $details['bgColor'] ?>;">
                                <i class="<?= $details['icon'] ?> text-lg" style="color: <?= $details['color'] ?>;"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800"><?= $details['title'] ?> Areas</h3>
                        </div>
                        <div class="h-64">
                            <canvas id="<?= $key ?>FocusAreaBarChart"></canvas>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-8 mb-8 animate-slide-up">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Overall Performance Comparison</h2>
                <p class="text-gray-600">Compare your average performance across all development areas</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <div class="h-96">
                    <canvas id="overallAverageBarChart"></canvas>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6 animate-fade-in">
            <a href="datainput.php" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-semibold py-4 px-8 rounded-xl shadow-lg transform hover:scale-105 transition duration-300 ease-in-out">
                <i class="fas fa-plus mr-2"></i>Add New Entry
            </a>
            <button type="button" id="openClearDataModal" class="bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white font-semibold py-4 px-8 rounded-xl shadow-lg transform hover:scale-105 transition duration-300 ease-in-out">
                <i class="fas fa-trash-alt mr-2"></i>Clear All Data
            </button>
        </div>
    </div>

    <div id="clearDataModal" class="modal">
        <div class="modal-content">
            <div class="mb-6">
                <div class="bg-red-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Confirm Data Deletion</h2>
                <p class="text-gray-600">Are you sure you want to permanently delete all your development data? This action cannot be undone.</p>
            </div>
            <div class="flex justify-center space-x-4">
                <button id="cancelClearData" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-200">
                    Cancel
                </button>
                <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <input type="hidden" name="action" value="clear_all_data">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-200">
                        <i class="fas fa-trash-alt mr-2"></i>Delete Data
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // DOM Content Loaded Event
        document.addEventListener('DOMContentLoaded', function() {
            // Message box handling
            const messageBox = document.getElementById('messageBox');
            if (messageBox.innerHTML.trim() !== '') {
                messageBox.classList.remove('hidden');
                setTimeout(() => {
                    messageBox.style.opacity = '0';
                    setTimeout(() => messageBox.classList.add('hidden'), 300);
                }, 5000);
            }

            // Logout functionality
            document.getElementById('logoutButton').addEventListener('click', function() {
                window.location.href = 'login.php?action=logout';
            });

            // Modal functionality
            const clearDataModal = document.getElementById('clearDataModal');
            const openClearDataBtn = document.getElementById('openClearDataModal');
            const cancelClearDataBtn = document.getElementById('cancelClearData');

            openClearDataBtn.addEventListener('click', () => {
                clearDataModal.style.display = 'flex';
            });

            cancelClearDataBtn.addEventListener('click', () => {
                clearDataModal.style.display = 'none';
            });

            window.addEventListener('click', (event) => {
                if (event.target == clearDataModal) {
                    clearDataModal.style.display = 'none';
                }
            });
        });

        // PHP data to JavaScript
        const categoryData = <?= json_encode($categoryData) ?>;
        const overallAverages = <?= json_encode($overallAverages) ?>;
        const formCategories = <?= json_encode(FORM_CATEGORIES) ?>;

        // Chart utility functions
        function getAverageRatingsForChart(ratingsByDate) {
            const labels = Object.keys(ratingsByDate).sort();
            const data = labels.map(date => {
                const ratings = ratingsByDate[date]
                                        .filter(item => item.rating !== null && item.rating !== undefined)
                                        .map(item => item.rating);
                return ratings.length > 0 ? ratings.reduce((a, b) => a + b, 0) / ratings.length : 0;
            });
            return { labels, data };
        }

        function calculateAverageOfFocusAreaRatings(focusAreaRatingsObject) {
            const averages = {};
            for (const area in focusAreaRatingsObject) {
                const ratingsArray = focusAreaRatingsObject[area]
                                        .filter(item => item.rating !== null && item.rating !== undefined)
                                        .map(item => item.rating);
                if (ratingsArray.length > 0) {
                    const sum = ratingsArray.reduce((acc, curr) => acc + curr, 0);
                    averages[area] = sum / ratingsArray.length;
                }
            }
            return averages;
        }

        function prepareFocusAreaDataForChart(focusAreaRatingsObject, color) {
            const averages = calculateAverageOfFocusAreaRatings(focusAreaRatingsObject);
            const labels = Object.keys(averages);
            const data = Object.values(averages);
            const rawOtherData = {};

            for (const area of labels) {
                const entries = focusAreaRatingsObject[area];
                const lastEntryWithOther = entries.slice().reverse().find(item => item.area === 'Other' && item.otherInput !== '');
                if (lastEntryWithOther) {
                    rawOtherData[area] = lastEntryWithOther.otherInput;
                }
            }
            return { labels, data, color, rawOtherData };
        }

        // Chart creation functions
        function createLineChart(ctx, dataByDate, overallAverage, titleText, color) {
            const chartData = getAverageRatingsForChart(dataByDate);

            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, color + '40');
            gradient.addColorStop(1, color + '10');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Progress',
                            data: chartData.data,
                            backgroundColor: gradient,
                            borderColor: color,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: color,
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointHoverBackgroundColor: color,
                            pointHoverBorderColor: '#ffffff',
                            pointHoverBorderWidth: 3
                        },
                        {
                            label: 'Average',
                            data: Array(chartData.labels.length).fill(overallAverage),
                            borderColor: '#EF4444',
                            borderWidth: 2,
                            borderDash: [8, 4],
                            fill: false,
                            pointRadius: 0,
                            tension: 0
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: color,
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                title: function(context) {
                                    return `Date: ${context[0].label}`;
                                },
                                label: function(context) {
                                    if (context.datasetIndex === 0) {
                                        return `Rating: ${context.raw.toFixed(1)}/5`;
                                    }
                                    return `Average: ${context.raw.toFixed(1)}/5`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 5,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6B7280' // Gray-500
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#6B7280' // Gray-500
                            }
                        }
                    }
                }
            });
        }

        function createBarChart(ctx, chartData, titleText, color) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Average Rating',
                        data: chartData.data,
                        backgroundColor: chartData.labels.map(() => color + 'CC'),
                        borderColor: chartData.labels.map(() => color),
                        borderWidth: 1,
                        borderRadius: 5,
                        hoverBackgroundColor: chartData.labels.map(() => color)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: color,
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                title: function(context) {
                                    const label = context[0].label;
                                    return chartData.rawOtherData[label] ? chartData.rawOtherData[label] : label;
                                },
                                label: function(context) {
                                    return `Rating: ${context.raw.toFixed(1)}/5`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 5,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6B7280'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#6B7280'
                            }
                        }
                    }
                }
            });
        }

        // Initialize charts
        document.addEventListener('DOMContentLoaded', function() {
            // Development Progress Charts (Line Charts)
            for (const key in formCategories) {
                if (categoryData[key] && categoryData[key].ratingsByDate) {
                    const ctx = document.getElementById(`${key}DevelopmentChart`).getContext('2d');
                    createLineChart(
                        ctx,
                        categoryData[key].ratingsByDate,
                        overallAverages[key],
                        `${formCategories[key].title} Development`,
                        formCategories[key].color
                    );
                }
            }

            // Focus Area Analysis (Bar Charts)
            for (const key in formCategories) {
                if (categoryData[key] && categoryData[key].focusAreaRatings) {
                    const ctx = document.getElementById(`${key}FocusAreaBarChart`).getContext('2d');
                    const chartData = prepareFocusAreaDataForChart(categoryData[key].focusAreaRatings, formCategories[key].color);
                    createBarChart(
                        ctx,
                        chartData,
                        `${formCategories[key].title} Focus Areas`,
                        formCategories[key].color
                    );
                }
            }

            // Overall Performance Comparison (Bar Chart)
            const overallAvgCtx = document.getElementById('overallAverageBarChart').getContext('2d');
            const overallAvgLabels = Object.values(formCategories).map(cat => cat.title);
            const overallAvgData = Object.values(overallAverages);
            const overallAvgColors = Object.values(formCategories).map(cat => cat.color);

            new Chart(overallAvgCtx, {
                type: 'bar',
                data: {
                    labels: overallAvgLabels,
                    datasets: [{
                        label: 'Average Rating',
                        data: overallAvgData,
                        backgroundColor: overallAvgColors.map(color => color + 'CC'),
                        borderColor: overallAvgColors,
                        borderWidth: 1,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y', // Make it a horizontal bar chart
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#ffffff',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return `Rating: ${context.raw.toFixed(1)}/5`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            max: 5,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6B7280'
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#6B7280'
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>