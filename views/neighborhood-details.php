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

            // Study-specific content
            if ($purpose === 'study'): ?>
                <div class="space-y-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">Which university will you be attending?</h1>
                        <p class="text-gray-600">We'll help you find areas with easy access to your campus.</p>
                    </div>

                    <div class="grid gap-4">
                        <div class="bg-white rounded-xl p-6 hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                            onclick="selectLocation('unsw')">
                            <h3 class="font-semibold text-lg mb-2">UNSW (Kensington)</h3>
                            <p class="text-gray-600 text-sm">Popular areas: Randwick, Kensington, Kingsford</p>
                            <p class="text-gray-600 text-sm">Transport: Bus, Light Rail</p>
                        </div>

                        <div class="bg-white rounded-xl p-6 hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                            onclick="selectLocation('usyd')">
                            <h3 class="font-semibold text-lg mb-2">University of Sydney</h3>
                            <p class="text-gray-600 text-sm">Popular areas: Newtown, Glebe, Camperdown</p>
                            <p class="text-gray-600 text-sm">Transport: Bus, Train</p>
                        </div>

                        <div class="bg-white rounded-xl p-6 hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                            onclick="selectLocation('uts')">
                            <h3 class="font-semibold text-lg mb-2">UTS (Ultimo)</h3>
                            <p class="text-gray-600 text-sm">Popular areas: Ultimo, Chippendale, Haymarket</p>
                            <p class="text-gray-600 text-sm">Transport: Train, Light Rail, Bus</p>
                        </div>

                        <div class="bg-white rounded-xl p-6 hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                            onclick="showOtherInput()">
                            <h3 class="font-semibold text-lg">Other Institution</h3>
                            <p class="text-gray-600 text-sm">Tell us where you'll be studying</p>
                        </div>
                    </div>
                </div>

            <?php elseif ($purpose === 'work'): ?>
                <div class="space-y-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">Where will your workplace be?</h1>
                        <p class="text-gray-600">This helps us suggest areas with convenient commutes.</p>
                    </div>

                    <div class="grid gap-4">
                        <div class="bg-white rounded-xl p-6 hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                            onclick="selectLocation('cbd')">
                            <h3 class="font-semibold text-lg mb-2">Sydney CBD</h3>
                            <p class="text-gray-600 text-sm">Popular areas: Surry Hills, Potts Point, North Sydney</p>
                            <p class="text-gray-600 text-sm">Transport: Train, Bus, Ferry</p>
                        </div>

                        <div class="bg-white rounded-xl p-6 hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                            onclick="selectLocation('north-sydney')">
                            <h3 class="font-semibold text-lg mb-2">North Sydney</h3>
                            <p class="text-gray-600 text-sm">Popular areas: Neutral Bay, Cremorne, St Leonards</p>
                            <p class="text-gray-600 text-sm">Transport: Train, Bus</p>
                        </div>

                        <div class="bg-white rounded-xl p-6 hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                            onclick="selectLocation('parramatta')">
                            <h3 class="font-semibold text-lg mb-2">Parramatta</h3>
                            <p class="text-gray-600 text-sm">Popular areas: Parramatta, Westmead, Harris Park</p>
                            <p class="text-gray-600 text-sm">Transport: Train, Bus, Light Rail</p>
                        </div>

                        <div class="bg-white rounded-xl p-6 hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                            onclick="showOtherInput()">
                            <h3 class="font-semibold text-lg">Other Location</h3>
                            <p class="text-gray-600 text-sm">Tell us where you'll be working</p>
                        </div>
                    </div>
                </div>

            <?php elseif ($purpose === 'visit'): ?>
                <div class="space-y-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">Which part of Sydney interests you most?</h1>
                        <p class="text-gray-600">We'll help you find the perfect spot for your stay.</p>
                    </div>

                    <div class="grid gap-4">
                        <div class="bg-white rounded-xl p-6 hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                            onclick="selectLocation('beach')">
                            <h3 class="font-semibold text-lg mb-2">Beach & Coast</h3>
                            <p class="text-gray-600 text-sm">Popular areas: Bondi, Manly, Coogee</p>
                            <p class="text-gray-600 text-sm">Perfect for: Beach lifestyle, coastal walks</p>
                        </div>

                        <div class="bg-white rounded-xl p-6 hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                            onclick="selectLocation('city')">
                            <h3 class="font-semibold text-lg mb-2">City & Entertainment</h3>
                            <p class="text-gray-600 text-sm">Popular areas: CBD, Darling Harbour, Surry Hills</p>
                            <p class="text-gray-600 text-sm">Perfect for: Nightlife, shopping, dining</p>
                        </div>

                        <div class="bg-white rounded-xl p-6 hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                            onclick="selectLocation('nature')">
                            <h3 class="font-semibold text-lg mb-2">Nature & Parks</h3>
                            <p class="text-gray-600 text-sm">Popular areas: Lane Cove, Mosman, Cremorne</p>
                            <p class="text-gray-600 text-sm">Perfect for: Bush walks, quiet surroundings</p>
                        </div>

                        <div class="bg-white rounded-xl p-6 hover:shadow-md transition-shadow cursor-pointer border border-gray-200"
                            onclick="selectLocation('flexible')">
                            <h3 class="font-semibold text-lg">Not sure yet</h3>
                            <p class="text-gray-600 text-sm">We'll show you options across Sydney</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Other Location Input (Hidden by default) -->
            <div id="otherLocationInput" class="hidden mt-6">
                <input type="text"
                    placeholder="Enter location"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    onkeypress="handleOtherLocation(event)">
            </div>
        </div>
    </div>

    <script>
        function selectLocation(location) {
            window.location.href = `neighborhood-duration.php?purpose=<?php echo $purpose; ?>&location=${location}`;
        }

        function showOtherInput() {
            document.getElementById('otherLocationInput').classList.remove('hidden');
        }

        function handleOtherLocation(event) {
            if (event.key === 'Enter') {
                const location = event.target.value;
                selectLocation(encodeURIComponent(location));
            }
        }
    </script>
</body>

</html>