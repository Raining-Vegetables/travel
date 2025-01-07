<?php
require_once __DIR__ . '/../config/access-db.php';
require_once __DIR__ . '/../config/config.php';

$purpose = $_GET['purpose'] ?? '';
$location = $_GET['location'] ?? '';
$duration = $_GET['duration'] ?? '';

// Function to humanize location names
function formatLocation($location)
{
    $locations = [
        'unsw' => 'UNSW Kensington',
        'usyd' => 'University of Sydney',
        'uts' => 'UTS (Ultimo)',
        'cbd' => 'Sydney CBD',
        'north-sydney' => 'North Sydney',
        'parramatta' => 'Parramatta',
        'beach' => 'Beach & Coastal Areas',
        'city' => 'City & Entertainment District',
        'nature' => 'Nature & Parks Areas'
    ];
    return $locations[$location] ?? $location;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Understanding Sydney Housing | SydneyBuddy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    <nav class="bg-white border-b">
        <div class="max-w-4xl mx-auto px-4">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <a href="/" class="text-xl sm:text-2xl font-bold text-blue-600">SydneyBuddy</a>
                    <span class="text-xs sm:text-sm px-2 py-1 bg-green-100 text-green-700 rounded">Beta</span>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 py-8">
        <!-- Summary Header -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 mb-8">
            <div class="space-y-2">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                    <span>You're starting at: <?php echo formatLocation($location); ?></span>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Length of stay: <?php echo htmlspecialchars($duration); ?></span>
                </div>
            </div>
        </div>

        <!-- Housing Options Section -->
        <div class="space-y-8">
            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Understanding Your Housing Options in Sydney</h2>

                <!-- Renting Your Own Place -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 mb-6">
                    <h3 class="text-xl font-semibold mb-4">Renting Your Own Place</h3>
                    <p class="text-gray-600 mb-4">
                        In Sydney, finding your own apartment typically happens through two major websites:
                        Domain.com.au and Realestate.com.au. These are the go-to platforms where real estate
                        agents list properties for rent. You'll find everything from studios to houses here.
                    </p>

                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <h4 class="font-medium mb-3">The Rental Process</h4>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>You'll need to inspect properties in person - this is mandatory and cannot be skipped</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Properties often have group inspections (like open houses) where multiple people view at once</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>After viewing, you submit an application if interested</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Landlords/agents review multiple applications before choosing a tenant</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>The whole process usually takes 2-3 weeks</span>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-amber-50 rounded-lg p-4">
                        <h4 class="font-medium mb-3">What You'll Need for Applications</h4>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>4 weeks rent as bond (security deposit)</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>2 weeks rent in advance</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Proof of income (job contract/bank statements)</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Identification (passport, visa documentation)</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Rental references (if you have any)</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Australian bank account</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Sharing a Place Section -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 mb-6">
                    <h3 class="text-xl font-semibold mb-4">Sharing a Place (Flatmates)</h3>
                    <p class="text-gray-600 mb-4">
                        If you prefer to rent a room in an existing household, Flatmates.com.au is Sydney's
                        main platform for this. This option is particularly popular because:
                    </p>

                    <div class="grid sm:grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium mb-3">Advantages</h4>
                            <ul class="space-y-2 text-gray-600">
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Rooms often come furnished</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Bills are usually shared or included</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Meet potential housemates before deciding</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>More flexible lease terms</span>
                                </li>
                            </ul>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium mb-3">Process</h4>
                            <ul class="space-y-2 text-gray-600">
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Message potential housemates through the platform</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Arrange to view the room and meet residents</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Can often move in within a week if everyone's happy</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Usually need 2-4 weeks rent upfront</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Recommended Area Section -->
                <?php
                // Example of how to show different areas based on their location selection
                $recommendedArea = '';
                $areaDetails = [];

                if ($location === 'unsw') {
                    $recommendedArea = 'Marrickville';
                    $areaDetails = [
                        'description' => 'Living in Marrickville means being surrounded by Sydneys best coffee roasters, independent breweries, and a thriving creative scene. The neighborhood has a relaxed, village-like atmosphere despite being just 20 minutes from your campus.',
                        'transport' => [
                            '20-minute bus ride (direct routes available)',
                            'Cycling takes about 25 minutes through local backstreets',
                            'Multiple bus routes mean you\'re not dependent on just one option'
                        ],
                        'features' => [
                            'Amazing food scene - especially Vietnamese and Greek',
                            'Factory Theatres for live shows and comedy',
                            'Multiple supermarkets and fresh food markets',
                            'Great gyms and yoga studios',
                            'Parks and swimming pools nearby',
                            'Active community feel'
                        ],
                        'prices' => [
                            'apartment' => '450-550',
                            'share' => '280-350'
                        ]
                    ];
                }
                // Add more location conditions here
                ?>

                <?php if ($recommendedArea): ?>
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                        <h3 class="text-xl font-semibold mb-4"><?php echo htmlspecialchars($recommendedArea); ?></h3>
                        <p class="text-gray-600 mb-6"><?php echo htmlspecialchars($areaDetails['description']); ?></p>

                        <!-- Transport -->
                        <div class="mb-6">
                            <h4 class="font-medium mb-3">Getting to <?php echo formatLocation($location); ?>:</h4>
                            <ul class="space-y-2 text-gray-600">
                                <?php foreach ($areaDetails['transport'] as $transport): ?>
                                    <li class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span><?php echo htmlspecialchars($transport); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- Local Life -->
                        <div class="mb-6">
                            <h4 class="font-medium mb-3">Local Life:</h4>
                            <ul class="space-y-2 text-gray-600">
                                <?php foreach ($areaDetails['features'] as $feature): ?>
                                    <li class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span><?php echo htmlspecialchars($feature); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- Price Guide -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium mb-3">Price Guide:</h4>
                            <ul class="space-y-2 text-gray-600">
                                <li>Your own 1-bedroom apartment: $<?php echo $areaDetails['prices']['apartment']; ?>/week</li>
                                <li>Room in a shared house: $<?php echo $areaDetails['prices']['share']; ?>/week</li>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Action Steps -->
                <div class="mt-8 space-y-6">
                    <h3 class="text-xl font-semibold">Ready to start looking?</h3>

                    <!-- Property Links -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                        <h4 class="font-medium mb-4">1. Start with these shortlisted properties:</h4>
                        <div class="space-y-3">
                            <a href="#" class="block text-blue-600 hover:underline">View Recommended Properties →</a>
                        </div>
                    </div>

                    <!-- Facebook Groups -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                        <h4 class="font-medium mb-4">2. Join these local Facebook groups:</h4>
                        <ul class="space-y-2 text-gray-600">
                            <li>• "<?php echo formatLocation($location); ?> Housing"</li>
                            <li>• "Sydney Student Housing"</li>
                        </ul>
                    </div>

                    <!-- Inspection Tips -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                        <h4 class="font-medium mb-4">3. Best inspection times:</h4>
                        <ul class="space-y-2 text-gray-600">
                            <li>• Saturdays are the main inspection days</li>
                            <li>• Try to arrive 10 minutes early</li>
                            <li>• Bring ID to inspections</li>
                        </ul>
                    </div>
                </div>
            </section>
        </div>

        <!-- Save or Share Section -->
        <div class="mt-12 text-center">
            <button onclick="saveToEmail()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Save This Guide
            </button>
        </div>
    </main>

    <script>
        function saveToEmail() {
            const email = prompt('Enter your email to save this guide:');
            if (email && validateEmail(email)) {
                alert('Guide will be sent to ' + email);
                // Implement email sending functionality
            }
        }

        function validateEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }
    </script>
</body>

</html>