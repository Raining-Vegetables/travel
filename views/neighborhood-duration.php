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

    <div class="bg-gradient-to-b from-blue-50 to-white min-h-screen">
        <div class="max-w-2xl mx-auto px-4 pt-12 pb-24">
            <!-- Back Button -->
            <a href="javascript:history.back()" class="inline-flex items-center text-gray-600 hover:text-blue-600 mb-8">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>

            <?php
            $purpose = $_GET['purpose'] ?? '';
            $location = $_GET['location'] ?? '';

            if ($purpose === 'study'): ?>
                <div class="space-y-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">How long will you be studying?</h1>
                        <p class="text-gray-600">This helps us show you the most suitable housing options.</p>
                    </div>

                    <div class="grid gap-4">
                        <div class="bg-white rounded-xl p-6 hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                            onclick="selectDuration('semester')">
                            <h3 class="font-semibold text-lg mb-2">One Semester</h3>
                            <p class="text-gray-600 text-sm mb-3">Best for exchange students and short courses</p>
                            <p class="text-gray-600 text-sm">We'll focus on: Student housing, furnished options</p>
                        </div>

                        <div class="bg-white rounded-xl p-6 hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                            onclick="selectDuration('year')">
                            <h3 class="font-semibold text-lg mb-2">One Year</h3>
                            <p class="text-gray-600 text-sm mb-3">Perfect for most undergraduate years</p>
                            <p class="text-gray-600 text-sm">We'll focus on: Long-term rentals, all housing types</p>
                        </div>

                        <div class="bg-white rounded-xl p-6 hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                            onclick="selectDuration('degree')">
                            <h3 class="font-semibold text-lg mb-2">Full Degree (2+ years)</h3>
                            <p class="text-gray-600 text-sm mb-3">For your entire study program</p>
                            <p class="text-gray-600 text-sm">We'll focus on: Best value areas, buying furniture</p>
                        </div>
                    </div>
                </div>

            <?php elseif ($purpose === 'work'): ?>
                <div class="space-y-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">How long is your work contract?</h1>
                        <p class="text-gray-600">This helps us match you with suitable rental options.</p>
                    </div>

                    <div class="grid gap-4">
                        <div class="bg-white rounded-xl p-6 hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                            onclick="selectDuration('short')">
                            <h3 class="font-semibold text-lg mb-2">Less than 6 months</h3>
                            <p class="text-gray-600 text-sm mb-3">Best for contract work and trials</p>
                            <p class="text-gray-600 text-sm">We'll focus on: Furnished rentals, subletting options</p>
                        </div>

                        <div class="bg-white rounded-xl p-6 hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                            onclick="selectDuration('medium')">
                            <h3 class="font-semibold text-lg mb-2">6-12 months</h3>
                            <p class="text-gray-600 text-sm mb-3">Standard lease length in Sydney</p>
                            <p class="text-gray-600 text-sm">We'll focus on: Regular rentals, best value areas</p>
                        </div>

                        <div class="bg-white rounded-xl p-6 hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                            onclick="selectDuration('long')">
                            <h3 class="font-semibold text-lg mb-2">More than a year</h3>
                            <p class="text-gray-600 text-sm mb-3">For permanent positions</p>
                            <p class="text-gray-600 text-sm">We'll focus on: Long-term housing strategies</p>
                        </div>
                    </div>
                </div>

            <?php elseif ($purpose === 'visit'): ?>
                <div class="space-y-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">How long are you planning to stay?</h1>
                        <p class="text-gray-600">We'll show you the best accommodation options for your visit.</p>
                    </div>

                    <div class="grid gap-4">
                        <div class="bg-white rounded-xl p-6 hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                            onclick="selectDuration('short')">
                            <h3 class="font-semibold text-lg mb-2">Less than 2 weeks</h3>
                            <p class="text-gray-600 text-sm mb-3">Best for holiday visits</p>
                            <p class="text-gray-600 text-sm">We'll focus on: Hotels, serviced apartments</p>
                        </div>

                        <div class="bg-white rounded-xl p-6 hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                            onclick="selectDuration('medium')">
                            <h3 class="font-semibold text-lg mb-2">2-4 weeks</h3>
                            <p class="text-gray-600 text-sm mb-3">Perfect for extended stays</p>
                            <p class="text-gray-600 text-sm">We'll focus on: Airbnb, short-term rentals</p>
                        </div>

                        <div class="bg-white rounded-xl p-6 hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                            onclick="selectDuration('long')">
                            <h3 class="font-semibold text-lg mb-2">1-6 months</h3>
                            <p class="text-gray-600 text-sm mb-3">For longer visits</p>
                            <p class="text-gray-600 text-sm">We'll focus on: Furnished apartments, house shares</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function selectDuration(duration) {
            // Get existing URL parameters and add duration
            const params = new URLSearchParams(window.location.search);
            params.append('duration', duration);

            // Redirect to the context page with all parameters
            window.location.href = `neighborhood-context.php?${params.toString()}`;
        }
    </script>
</body>

</html>