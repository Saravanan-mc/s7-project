<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Input</title>
</head>
<body>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Student Development Tracker - Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen p-4 font-inter">
    <div class="bg-white p-8 rounded-lg shadow-xl max-w-4xl w-full">
        <div class="header-info flex justify-between items-center mb-6 border-b pb-4">
            <p class="text-lg text-gray-700">Logged in as: <strong id="loggedInInfo">Loading...</strong></p>
            <button id="logoutButton" class="text-red-500 hover:text-red-700 font-medium">
                <i class="fas fa-sign-out-alt mr-2"></i>Logout
            </button>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Student Development Form</h1>
        <p class="optional-note text-center text-gray-500 mb-6">All fields in this form are optional, but ratings will update your graph.</p>

        <div id="messageBox" class="message-box hidden px-4 py-3 rounded-lg mb-4 text-sm font-medium"></div>

        <form id="developmentForm" class="mb-8 p-6 bg-blue-50 rounded-lg shadow-inner">
            <div class="form-header-inputs grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-gray-700 font-medium">Your User ID:</label>
                    <p id="displayUserId" class="font-normal text-gray-800 mt-1">Loading...</p>
                    <input type="hidden" name="student_user_id" id="studentUserId">
                </div>
                <div>
                    <label for="selectedDate" class="block text-gray-700 font-medium">Select Date:</label>
                    <input type="date" id="selectedDate" name="form_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 p-2" aria-label="Select submission date">
                </div>
                <input type="hidden" name="submission_timestamp" id="submissionTimestampHidden">
            </div>

            <div class="mb-6 p-4 border rounded-lg bg-white shadow-sm">
                <h2 class="text-xl font-semibold text-blue-700 mb-3"><i class="fas fa-brain mr-2"></i>Academic</h2>
                <label for="academic_area" class="block text-gray-700 font-medium">Select Focus Area:</label>
                <select name="academic_area" id="academic_area" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 p-2" onchange="toggleOtherInput(this, 'academic_other_container')">
                    <option value="">-- Select One (Optional) --</option>
                    <option value="Subject">Subject</option>
                    <option value="Note-taking">Note-taking</option>
                    <option value="Research">Research</option>
                    <option value="Exam Preparation">Exam Preparation</option>
                    <option value="Other">Other</option>
                </select>
                <div id="academic_other_container" class="mt-2 hidden">
                    <label for="academic_area_other" class="block text-gray-700 font-medium">Other (please specify):</label>
                    <input type="text" name="academic_area_other" id="academic_area_other" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 p-2" aria-label="Specify other academic focus area">
                </div>
                <label for="academic_rating" class="block text-gray-700 font-medium mt-3">Rate Your Development (1 to 5):</label>
                <input type="number" name="academic_rating" id="academic_rating" min="1" max="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 p-2" aria-label="Academic development rating (1 to 5)">
            </div>

            <div class="mb-6 p-4 border rounded-lg bg-white shadow-sm">
                <h2 class="text-xl font-semibold text-blue-700 mb-3"><i class="fas fa-dumbbell mr-2"></i>Sports & Wellness</h2>
                <label for="wellness_area" class="block text-gray-700 font-medium">Select Focus Area:</label>
                <select name="wellness_area" id="wellness_area" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 p-2" onchange="toggleOtherInput(this, 'wellness_other_container')">
                    <option value="">-- Select One (Optional) --</option>
                    <option value="Physical Fitness">Physical Fitness</option>
                    <option value="Mental Health">Mental Health</option>
                    <option value="Stress Management">Stress Management</option>
                    <option value="Team Activities">Team Activities</option>
                    <option value="Other">Other</option>
                </select>
                <div id="wellness_other_container" class="mt-2 hidden">
                    <label for="wellness_area_other" class="block text-gray-700 font-medium">Other (please specify):</label>
                    <input type="text" name="wellness_area_other" id="wellness_area_other" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 p-2" aria-label="Specify other sports and wellness focus area">
                </div>
                <label for="wellness_rating" class="block text-gray-700 font-medium mt-3">Rate Your Engagement (1 to 5):</label>
                <input type="number" name="wellness_rating" id="wellness_rating" min="1" max="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 p-2" aria-label="Sports and wellness engagement rating (1 to 5)">
            </div>

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

        <div class="text-center">
            <p class="text-gray-700 mb-4">View your development progress on the charts page:</p>
            <a href="graphs.html" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105 inline-block">
                Go to Charts
            </a>
        </div>
    </div>

    <script>
        const user = { id: 'USER123', name: 'John Doe' };
        document.getElementById('loggedInInfo').textContent = user.name;
        document.getElementById('displayUserId').textContent = user.id;
        document.getElementById('studentUserId').value = user.id;

        // Data structures for storing ratings
        let academicRatingsByDate = JSON.parse(localStorage.getItem('academicRatingsByDate')) || {};
        let wellnessRatingsByDate = JSON.parse(localStorage.getItem('wellnessRatingsByDate')) || {};
        let academicFocusAreaRatings = JSON.parse(localStorage.getItem('academicFocusAreaRatings')) || {};
        let wellnessFocusAreaRatings = JSON.parse(localStorage.getItem('wellnessFocusAreaRatings')) || {};

        function toggleOtherInput(selectElement, containerId) {
            const container = document.getElementById(containerId);
            container.classList.toggle('hidden', selectElement.value !== 'Other');
            if (selectElement.value !== 'Other') {
                document.getElementById(`${selectElement.id}_other`).value = '';
            }
        }

        const form = document.getElementById('developmentForm');
        const messageBox = document.getElementById('messageBox');
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const dateInput = document.getElementById('selectedDate').value;
            if (!dateInput) {
                showMessage('Please select a date.', 'error');
                return;
            }

            const academicArea = document.getElementById('academic_area').value;
            const academicAreaOther = document.getElementById('academic_area_other').value.trim();
            const wellnessArea = document.getElementById('wellness_area').value;
            const wellnessAreaOther = document.getElementById('wellness_area_other').value.trim();

            const academicRating = parseInt(document.getElementById('academic_rating').value) || 0;
            const wellnessRating = parseInt(document.getElementById('wellness_rating').value) || 0;

            if (academicRating === 0 && wellnessRating === 0) {
                showMessage('Please provide at least one rating between 1 and 5.', 'error');
                return;
            }

            if (academicArea === 'Other' && !academicAreaOther && academicRating > 0) {
                showMessage('Please specify the "Other" field for Academic.', 'error');
                return;
            }
            if (wellnessArea === 'Other' && !wellnessAreaOther && wellnessRating > 0) {
                showMessage('Please specify the "Other" field for Sports & Wellness.', 'error');
                return;
            }

            // Academic Data Update
            if (academicRating >= 1 && academicRating <= 5) { // Ensure rating is valid
                const currentAcademicAreaLabel = (academicArea === 'Other' && academicAreaOther) ? academicAreaOther : academicArea || 'General Academic';
                
                // Update academicRatingsByDate (for Academic Progress by Date chart)
                if (!academicRatingsByDate[dateInput]) {
                    academicRatingsByDate[dateInput] = [];
                }
                academicRatingsByDate[dateInput].push({
                    area: academicArea, // Keep original value for filtering 'Other'
                    otherInput: academicAreaOther,
                    rating: academicRating,
                    date: dateInput
                });
                localStorage.setItem('academicRatingsByDate', JSON.stringify(academicRatingsByDate));

                // Update academicFocusAreaRatings (for Academic Focus Area Performance chart)
                if (!academicFocusAreaRatings[currentAcademicAreaLabel]) {
                    academicFocusAreaRatings[currentAcademicAreaLabel] = [];
                }
                academicFocusAreaRatings[currentAcademicAreaLabel].push({
                    area: academicArea, // Keep original value for filtering 'Other'
                    otherInput: academicAreaOther,
                    rating: academicRating,
                    date: dateInput
                });
                localStorage.setItem('academicFocusAreaRatings', JSON.stringify(academicFocusAreaRatings));
            }

            // Wellness Data Update
            if (wellnessRating >= 1 && wellnessRating <= 5) { // Ensure rating is valid
                const currentWellnessAreaLabel = (wellnessArea === 'Other' && wellnessAreaOther) ? wellnessAreaOther : wellnessArea || 'General Wellness';

                // Update wellnessRatingsByDate (for Sports & Wellness Progress by Date chart)
                if (!wellnessRatingsByDate[dateInput]) {
                    wellnessRatingsByDate[dateInput] = [];
                }
                wellnessRatingsByDate[dateInput].push({
                    area: wellnessArea,
                    otherInput: wellnessAreaOther,
                    rating: wellnessRating,
                    date: dateInput
                });
                localStorage.setItem('wellnessRatingsByDate', JSON.stringify(wellnessRatingsByDate));

                // Update wellnessFocusAreaRatings (for Sports & Wellness Focus Area Performance chart)
                if (!wellnessFocusAreaRatings[currentWellnessAreaLabel]) {
                    wellnessFocusAreaRatings[currentWellnessAreaLabel] = [];
                }
                wellnessFocusAreaRatings[currentWellnessAreaLabel].push({
                    area: wellnessArea,
                    otherInput: wellnessAreaOther,
                    rating: wellnessRating,
                    date: dateInput
                });
                localStorage.setItem('wellnessFocusAreaRatings', JSON.stringify(wellnessFocusAreaRatings));
            }
            
            showMessage('Development report submitted successfully!', 'success');
            form.reset();
            // Hide 'other' input fields after form reset
            const sections = ['academic', 'wellness'];
            sections.forEach(section => toggleOtherInput(document.getElementById(`${section}_area`), `${section}_other_container`));
        });

        function showMessage(message, type) {
            messageBox.textContent = message;
            if (type === 'success') {
                messageBox.className = 'message-box px-4 py-3 rounded-lg mb-4 text-sm font-medium bg-green-100 text-green-800';
            } else if (type === 'error') {
                messageBox.className = 'message-box px-4 py-3 rounded-lg mb-4 text-sm font-medium bg-red-100 text-red-800';
            }
            messageBox.classList.remove('hidden');
        }

        document.getElementById('clearFormDataButton').addEventListener('click', function() {
            form.reset();
            messageBox.classList.add('hidden'); // Hide message box on clear
            const sections = ['academic', 'wellness'];
            sections.forEach(section => toggleOtherInput(document.getElementById(`${section}_area`), `${section}_other_container`));
        });

        document.getElementById('logoutButton').addEventListener('click', function() {
            alert('Logout functionality is not yet implemented.');
        });
    </script>
</body>
</html>

    <h1>Data Input Page</h1>
    <p>This is where you would put your forms or interface for inputting data.</p>
    <br>
    <a href="data.php">Back to Data Analyze</a> | <a href="index.php">Back to Home</a>
</body>
</html>
