<?php

require_once __DIR__ . '/../config/access-db.php';
require_once __DIR__ . '/../config/config.php';


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Your Perfect Sydney Spot | SydneyBuddy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    <!-- Navigation - keeping your existing nav -->
    <nav class="bg-white border-b">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <a href="/" class="text-xl sm:text-2xl font-bold text-blue-600">SydneyBuddy</a>
                    <span class="text-xs sm:text-sm px-2 py-1 bg-green-100 text-green-700 rounded">Beta</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="bg-gradient-to-b from-blue-50 to-white min-h-screen">
        <div class="max-w-2xl mx-auto px-4 pt-12 pb-24">
            <!-- Welcome Section -->
            <div class="text-center mb-12">
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    Welcome to Sydney! Let's help you find the perfect place to live.
                </h1>
                <p class="text-xl text-gray-600">
                    What brings you here?
                </p>
            </div>

            <!-- Purpose Selection Cards -->
            <div class="grid gap-6">
                <!-- Study Card -->
                <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                    onclick="selectPurpose('study')">
                    <div class="flex gap-4">
                        <div class="bg-blue-100 p-3 rounded-lg h-fit">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold mb-2">Study</h3>
                            <p class="text-gray-600">
                                Whether you're starting university or doing a short course, we'll help you understand Sydney housing options and find the perfect spot near your campus.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Work Card -->
                <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                    onclick="selectPurpose('work')">
                    <div class="flex gap-4">
                        <div class="bg-green-100 p-3 rounded-lg h-fit">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold mb-2">Work</h3>
                            <p class="text-gray-600">
                                Moving to Sydney for work? We'll help you find housing that makes your commute easy and matches your lifestyle.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Visit Card -->
                <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                    onclick="selectPurpose('visit')">
                    <div class="flex gap-4">
                        <div class="bg-purple-100 p-3 rounded-lg h-fit">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold mb-2">Visit</h3>
                            <p class="text-gray-600">
                                Planning a stay in Sydney? We'll show you the best short-term options based on your plans.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectPurpose(purpose) {
            // Store the selected purpose
            const url = new URL(window.location.origin + '/travel/views/neighborhood-details.php');
            url.searchParams.append('purpose', purpose);
            window.location.href = url.toString();
        }
    </script>
</body>

</html>