<?php
// controllers/RecommendationController.php
require_once 'config/access-db.php';
require_once 'config/config.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sydney Tourist Phone Plans - Get Connected with Confidence</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="description"
        content="Find the perfect Sydney phone plan for tourists. Compare prices, get step-by-step setup guides, and find the nearest store. Save time and money on your Australian phone plan.">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://sydneybuddy.com/">
    <meta property="og:title" content="Sydney Tourist Phone Plans - Get Connected with Confidence">
    <meta property="og:description"
        content="Find the perfect Sydney phone plan for tourists. Compare prices across all carriers and get step-by-step setup guides.">
    <meta property="og:image" content="https://sydneybuddy.com/images/og-image.jpg">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://sydneybuddy.com/">
    <meta property="twitter:title" content="Sydney Tourist Phone Plans - Get Connected with Confidence">
    <meta property="twitter:description"
        content="Find the perfect Sydney phone plan for tourists. Compare prices across all carriers and get step-by-step setup guides.">
    <meta property="twitter:image" content="https://sydneybuddy.com/images/og-image.jpg">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-T8TD7ZWF6Q"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-T8TD7ZWF6Q', {
            'page_title': 'Home',
            'page_location': window.location.href,
            'page_path': window.location.pathname
        });
    </script>

    <link rel="canonical" href="https://sydneybuddy.com/" />
    <meta name="robots" content="index, follow">
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white border-b">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-blue-600">SydneyBuddy</span>
                </div>
                <!-- <div class="flex space-x-4 text-sm">
                    <a href="#how-it-works" class="text-gray-600 hover:text-gray-900">How It Works</a>
                    <a href="#contact" class="text-gray-600 hover:text-gray-900">Contact</a>
                </div> -->
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-b from-blue-50 to-white">
        <div class="max-w-6xl mx-auto px-4 pt-16 pb-24">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="flex-1 space-y-6">
                    <div class="inline-block bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-medium">
                        New: Sydney's Local Phone Plan Finder
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight">
                        Get Connected in Sydney Without The Hassle
                    </h1>
                    <p class="text-xl text-gray-600">
                        We research and compare Sydney phone plans from all major carriers, providing clear prices and
                        step-by-step pickup instructions for tourists.
                    </p>
                </div>
                <div class="flex-1">
                    <div class="bg-white rounded-2xl shadow-xl p-8 max-w-md mx-auto">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold">Find Your Perfect Plan</h2>
                            <div class="flex items-center text-sm text-green-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Updated Today
                            </div>
                        </div>

                        <!-- Pre-requirements Notice -->
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                            <h3 class="text-sm font-semibold text-blue-800 mb-2">Before you start:</h3>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Make sure your phone is unlocked
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Have your passport ready for purchase
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Plans can be picked up same-day
                                </li>
                            </ul>
                        </div>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                                <?php
                                echo htmlspecialchars($_SESSION['error']);
                                unset($_SESSION['error']);
                                ?>
                            </div>
                        <?php endif; ?>


                        <form action="/controllers/RecommendationController.php" method="GET" class="space-y-8" id="planFinderForm">


                            <!-- Usage Pattern -->
                            <div class="space-y-2">
                                <label for="usage" class="text-sm font-semibold text-gray-800">
                                    What will you mainly use your phone for in Sydney?
                                </label>
                                <select id="usage" name="usage_type"
                                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white transition-all duration-200">
                                    <option value="">Select usage type...</option>
                                    <option value="basic">Basic (Maps & messaging) - About 1GB/week</option>
                                    <option value="regular">Regular (Social media & video calls) - About 3GB/week
                                    </option>
                                    <option value="heavy">Heavy use (Streaming & working) - 5GB+/week</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">All prices shown in AUD</p>
                            </div>

                            <!-- Location -->
                            <div class="space-y-2">
                                <label for="location"
                                    class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                                    Where in Sydney will you be staying?
                                    <button type="button"
                                        onclick="alert('Popular areas:\n• Eastern: Bondi Beach, Coogee\n• City: CBD, Darling Harbour\n• Northern: Manly, Mosman\n• Western: Parramatta\n• South: Cronulla')"
                                        class="text-blue-600 text-xs hover:underline">
                                        Not sure?
                                    </button>
                                </label>
                                <select id="location" name="area"
                                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white transition-all duration-200">
                                    <option value="">Select location...</option>
                                    <option value="eastern">Eastern Suburbs (Bondi, Coogee, etc.)</option>
                                    <option value="city">City & Inner West</option>
                                    <option value="northern">Northern Beaches</option>
                                    <option value="western">Western Sydney</option>
                                    <option value="southern">South Sydney</option>
                                </select>
                            </div>

                            <!-- Duration -->
                            <div class="space-y-2">
                                <label for="duration" class="text-sm font-semibold text-gray-800">
                                    How long are you staying in Sydney?
                                </label>
                                <select id="duration_days" name="duration_days"
                                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white transition-all duration-200">
                                    <option value="">Select duration...</option>
                                    <option value="short">Less than 2 weeks</option>
                                    <option value="medium">2-4 weeks</option>
                                    <option value="long">More than a month</option>
                                </select>
                            </div>

                            <button type="submit"
                                class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 focus:ring-4 focus:ring-blue-200">
                                Show My Recommendations
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Trust Sections -->
    <div class="max-w-6xl mx-auto px-4 py-16">
        <!-- Trust Badges -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold mb-2">Clear Step-by-Step Guide</h3>
                <p class="text-gray-600">We provide detailed instructions for buying and setting up your SIM card.</p>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold mb-2">Store Finder</h3>
                <p class="text-gray-600">Find the closest carrier store to your accommodation with exact directions.</p>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 9a2 2 0 11-4 0 2 2 0 014 0zm6 8a2 2 0 11-4 0 2 2 0 014 0zM7 17a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold mb-2">Compare All Options</h3>
                <p class="text-gray-600">See current prices and plans from every major carrier in Sydney.</p>
            </div>
        </div>
    </div>

    <!-- Modern Centered Footer -->
    <footer class="bg-white border-t">
        <div class="max-w-6xl mx-auto px-4 py-12">
            <!-- Main Footer Content -->
            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">SydneyBuddy</h3>
                <p class="text-gray-600 max-w-md mx-auto">
                    Helping tourists find the best phone plans in Sydney with clear, unbiased recommendations.
                </p>
            </div>

            <!-- Quick Links -->
            <div class="flex justify-center space-x-6 mb-8">
                <a href="about.php" class="text-gray-600 hover:text-blue-600 transition-colors">About</a>
                <span class="text-gray-300">|</span>
                <!-- <a href="#" class="text-gray-600 hover:text-blue-600 transition-colors">Contact</a>
                <span class="text-gray-300">|</span> -->
                <a href="privacy.php" class="text-gray-600 hover:text-blue-600 transition-colors">Privacy</a>
                <span class="text-gray-300">|</span>
                <a href="terms.php" class="text-gray-600 hover:text-blue-600 transition-colors">Terms</a>
            </div>

            <!-- Contact -->
            <div class="text-center text-sm text-gray-500">
                <!-- <p>Questions? Email us at help@sydneybuddy.com</p> -->
                <p class="mt-2">© 2024 SydneyBuddy. Launched December 2024.</p>
            </div>
        </div>
    </footer>

    <script>
        // Enhanced form submission tracking
        document.getElementById('planFinderForm').addEventListener('submit', function(e) {
            const usage = document.getElementById('usage').value;
            const location = document.getElementById('location').value;
            const duration = document.getElementById('duration').value;

            gtag('event', 'form_submission', {
                'event_category': 'engagement',
                'event_label': `${usage}_${location}_${duration}`,
                'usage_type': usage,
                'location_selected': location,
                'duration_selected': duration
            });

            // Track as a conversion
            gtag('event', 'conversion', {
                'send_to': 'G-T8TD7ZWF6Q',
                'event_category': 'form',
                'event_label': 'plan_finder_submission'
            });
        });

        // Track location help clicks
        document.querySelector('button[onclick]').addEventListener('click', function() {
            gtag('event', 'location_help_click', {
                'event_category': 'engagement',
                'event_label': 'location_guide',
                'non_interaction': false
            });
        });

        // Track dropdown selections
        ['usage', 'location', 'duration'].forEach(fieldId => {
            document.getElementById(fieldId).addEventListener('change', function(e) {
                gtag('event', 'field_selection', {
                    'event_category': 'form_interaction',
                    'event_label': fieldId,
                    'field_value': e.target.value
                });
            });
        });


        // Track time spent on page
        let startTime = new Date();
        window.addEventListener('beforeunload', function() {
            const endTime = new Date();
            const timeSpent = Math.round((endTime - startTime) / 1000);

            gtag('event', 'time_spent', {
                'event_category': 'engagement',
                'event_label': 'page_time',
                'value': timeSpent
            });
        });
    </script>

</body>

</html>
