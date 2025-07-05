<?php
include 'index.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Analyze Page</title>
    <!-- Tailwind CSS CDN for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom font for a clean look */
        body {
            font-family: 'Inter', sans-serif;
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex flex-col items-center py-10 px-4 sm:px-6 lg:px-8 text-gray-800">
    <!-- Header section for the navigation -->
    <header class="w-full max-w-7xl flex justify-end p-4 sm:p-6 lg:p-8">
        <!-- Top Navigation for Data Analyze Page -->
        <nav class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
            <a href="datainput.php" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 ease-in-out transform hover:-translate-y-0.5">
                Data Input Page
            </a>
            <a href="datareport.php" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 ease-in-out transform hover:-translate-y-0.5">
                Analysis Report Page
            </a>
        </nav>
    </header>

    <div class="max-w-4xl w-full bg-white p-8 sm:p-10 rounded-xl shadow-2xl space-y-8 mt-10">
        <!-- Main page title -->
        <h1 class="text-4xl sm:text-5xl font-extrabold text-center text-indigo-700 leading-tight">
            Data Analyze Page
        </h1>

        <hr class="border-t-2 border-indigo-200 my-8">

        <!-- Section for Data Analyze Overview -->
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Data Analyze Overview</h2>
            <p class="text-lg text-gray-600 leading-relaxed">
                Welcome to the Data Analysis section. Please select a sub-page from the navigation above to get started with your analysis.
            </p>
        </div>

        <br>

        <!-- Back to Home button -->
        <div class="text-center">
            <a href="index.php" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-indigo-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 ease-in-out transform hover:scale-105 shadow-md">
                Back to Home
            </a>
        </div>
    </div>
</body>
</html>