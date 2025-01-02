<?php
require_once '../config/config.php';
require_once '../config/access-db.php';

if (!isset($_SESSION['recommendation_data'])) {
    header('Location: ../index.php');
    exit;
}

$data = $_SESSION['recommendation_data'];
$plans = [
    'recommended' => $data['recommendations']['recommended'],
    'budget' => $data['recommendations']['budget'],
    'premium' => $data['recommendations']['premium']
];
$usage_context = $data['recommendations']['usage_context'];

// Helper function to format plan features nicely
function formatDataUsage($data_amount)
{
    if (strpos(strtolower($data_amount), 'unlimited') !== false) {
        return 'Unlimited data';
    }
    return $data_amount;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfect Phone Plan for Your Sydney Stay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-T8TD7ZWF6Q"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-T8TD7ZWF6Q');
    </script>
</head>

<body class="bg-gray-50">
    <!-- Sticky Header -->
    <header class="sticky top-0 bg-white border-b z-10">
        <div class="max-w-4xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a href="../index.php" class="flex items-center text-gray-600 hover:text-blue-600">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span class="text-sm font-medium">Back to Search</span>
                </a>
                <div class="flex items-center gap-3">
                    <button onclick="copyToClipboard()" class="text-sm text-blue-600 hover:underline">Share</button>
                    <button onclick="saveOption('email')" class="px-4 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                        Save Plan
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-6">
        <!-- Plan Summary Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
            <!-- Header Section -->
            <div class="p-6 border-b">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <div class="text-sm font-medium text-blue-600 mb-1">RECOMMENDED PLAN</div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            $<?php echo number_format($plans['recommended']['price'], 2); ?> -
                            <?php echo htmlspecialchars($plans['recommended']['carrier_name']); ?>
                        </h1>
                        <p class="text-gray-600 mt-1"><?php echo formatDataUsage($plans['recommended']['data_amount']); ?></p>
                    </div>
                    <div class="flex flex-col items-end">
                        <div class="bg-green-50 text-green-700 text-sm font-medium px-3 py-1 rounded-full">
                            Best Match
                        </div>
                        <div class="text-sm text-gray-500 mt-2">Updated Today</div>
                    </div>
                </div>

                <!-- Quick Requirements -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div class="bg-gray-50 p-3 rounded-lg text-center">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>
                        </div>
                        <span class="text-sm text-gray-600">Passport Required</span>
                    </div>
                    <!-- Add other requirements... -->
                    <div class="bg-gray-50 p-3 rounded-lg text-center">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <span class="text-sm text-gray-600">Payment (they accept international cards)</span>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg text-center">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <span class="text-sm text-gray-600">Unlocked phone (<button class="text-blue-600 hover:underline" onclick="showUnlockGuide()">check here</button>)</span>
                    </div>
                </div>
            </div>

            <!-- Coverage and Store Section -->
            <div class="p-6 grid md:grid-cols-2 gap-6">
                <!-- Store Location -->
                <?php if (!empty($plans['recommended']['stores'])): ?>
                    <?php $store = $plans['recommended']['stores'][0]; ?>
                    <div class="mb-6">
                        <h3 class="font-medium text-sm text-gray-500 mb-2">GET IT HERE</h3>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <div class="bg-blue-100 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium"><?php echo htmlspecialchars($store['name']); ?></p>
                                    <p class="text-sm text-gray-600"><?php echo htmlspecialchars($store['address']); ?></p>
                                    <?php
                                    $hours = is_string($store['hours']) ? json_decode($store['hours'], true) : $store['hours'];
                                    if (is_array($hours)):
                                    ?>
                                        <p class="text-sm text-gray-600 mt-1">
                                            Open: <?php echo isset($hours['weekday']) ? htmlspecialchars($hours['weekday']) : '9AM-6PM'; ?>
                                        </p>
                                    <?php endif; ?>
                                    <button onclick="showDirections('<?php echo htmlspecialchars($store['address']); ?>')"
                                        class="mt-2 text-blue-600 text-sm hover:underline">
                                        Show me how to get there →
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Coverage Info -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-3">NETWORK COVERAGE</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm text-gray-500">Coverage Rating</span>
                                <p class="font-medium"><?php echo $plans['recommended']['coverage_rating']; ?>/5</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Speed Range</span>
                                <p class="font-medium"><?php echo $plans['recommended']['data_speed_min']; ?>-<?php echo $plans['recommended']['data_speed_max']; ?> Mbps</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Smart Insights Grid -->
        <?php if (isset($data['recommendations']['ai_insights'])): ?>
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <!-- Usage Tips -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-4">USAGE TIPS</h3>
                    <ul class="space-y-3">
                        <?php foreach ($data['recommendations']['ai_insights']['usageAdvice'] as $tip): ?>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span><?php echo htmlspecialchars($tip); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Savings Tips -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-4">MONEY SAVING TIPS</h3>
                    <ul class="space-y-3">
                        <?php foreach ($data['recommendations']['ai_insights']['savingTips'] as $tip): ?>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span><?php echo htmlspecialchars($tip); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <!-- Important Information -->
        <!-- Important Information -->
        <?php if (!empty($plans['recommended']['honest_insights'])): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-4">IMPORTANT INFORMATION</h3>

                <div class="space-y-4">
                    <?php foreach ($plans['recommended']['honest_insights'] as $insight): ?>
                        <div class="bg-amber-50 rounded-lg p-4">
                            <?php if (!empty($insight['marketing_claim'])): ?>
                                <div class="mb-3">
                                    <span class="text-gray-600 text-sm">What they say:</span>
                                    <p class="text-gray-800"><?php echo htmlspecialchars($insight['marketing_claim']); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($insight['reality'])): ?>
                                <div class="mb-3">
                                    <span class="text-gray-600 text-sm">Reality:</span>
                                    <p class="text-gray-800"><?php echo htmlspecialchars($insight['reality']); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($insight['recommendation'])): ?>
                                <div class="flex items-start gap-2 mt-2">
                                    <svg class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-gray-700"><?php echo htmlspecialchars($insight['recommendation']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Feedback Section -->
        <div class="mt-8 text-center">
            <p class="text-gray-600 mb-2">Was this helpful?</p>
            <div class="flex justify-center gap-4">
                <button onclick="submitFeedback('positive')" class="text-2xl hover:scale-110 transition-transform">👍</button>
                <button onclick="submitFeedback('negative')" class="text-2xl hover:scale-110 transition-transform">👎</button>
            </div>
        </div>
    </main>


    <!-- Phone Unlock Guide Modal -->
    <div id="unlockGuideModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl p-6 max-w-md w-full">
            <h3 class="text-lg font-semibold mb-4">How to Check if Your Phone is Unlocked</h3>
            <div class="space-y-4">
                <ol class="space-y-3">
                    <li>
                        <p class="font-medium">1. Check in Settings</p>
                        <p class="text-sm text-gray-600">iPhone: Settings → Cellular → Cellular Data Options</p>
                        <p class="text-sm text-gray-600">Android: Settings → Connections → Networks</p>
                    </li>
                    <li>
                        <p class="font-medium">2. Try Another SIM</p>
                        <p class="text-sm text-gray-600">Insert a SIM from a different carrier. If it shows signal bars, your phone is unlocked.</p>
                    </li>
                    <li>
                        <p class="font-medium">3. Contact Your Carrier</p>
                        <p class="text-sm text-gray-600">They can tell you if your phone is unlocked and help unlock it if needed.</p>
                    </li>
                </ol>
            </div>
            <button onclick="closeUnlockGuide()"
                class="mt-6 w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Got it
            </button>
        </div>
    </div>

    <!-- Plan Details Modal -->
    <div id="planDetailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl p-6 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div id="planDetailsContent"></div>
            <button onclick="closePlanDetails()"
                class="mt-6 w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Close
            </button>
        </div>
    </div>

    <script>
        // Save options handling
        function saveOption(type) {
            switch (type) {
                case 'sms':
                    promptPhoneNumber();
                    break;
                case 'email':
                    promptEmail();
                    break;
                case 'wallet':
                    addToWallet();
                    break;
                case 'copy':
                    copyToClipboard();
                    break;
            }
        }

        function promptPhoneNumber() {
            const phone = prompt('Enter your phone number:');
            if (phone) {
                // TODO: Implement SMS sending
                alert('Details will be sent to ' + phone);
            }
        }

        function promptEmail() {
            const email = prompt('Enter your email address:');
            if (email) {
                // TODO: Implement email sending
                alert('Details will be sent to ' + email);
            }
        }

        function addToWallet() {
            // TODO: Implement wallet pass generation
            alert('Wallet pass feature coming soon!');
        }

        function copyToClipboard() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(() => {
                alert('Link copied to clipboard!');
            });
        }

        // Modal handling
        function showUnlockGuide() {
            document.getElementById('unlockGuideModal').classList.remove('hidden');
        }

        function closeUnlockGuide() {
            document.getElementById('unlockGuideModal').classList.add('hidden');
        }

        // Replace the empty showPlanDetails function with this:
        function showPlanDetails(type) {
            const modal = document.getElementById('planDetailsModal');
            const content = document.getElementById('planDetailsContent');

            // Get the plan data from PHP
            let plan;
            switch (type) {
                case 'budget':
                    plan = <?php echo json_encode($plans['budget']); ?>;
                    break;
                case 'premium':
                    plan = <?php echo json_encode($plans['premium']); ?>;
                    break;
            }

            // Create the content HTML
            let html = `
        <div class="space-y-6">
            <div class="border-b pb-4">
                <h3 class="text-xl font-semibold">
                    ${type === 'budget' ? 'Budget-Friendly Option' : 'Premium Plan'} Details
                </h3>
                <p class="text-gray-600">$${plan.price} - ${plan.carrier_name}</p>
            </div>

            <div class="space-y-4">
                <div>
                    <h4 class="font-medium mb-2">Plan Includes:</h4>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>${plan.data_amount} data</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Network: ${plan.network_type}</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Speed: ${plan.data_speed_min}-${plan.data_speed_max} Mbps</span>
                        </li>
                    </ul>
                </div>

                <div class="space-y-6">
        <!-- Coverage Details -->
        <div>
            <h4 class="font-medium mb-2">Coverage Details:</h4>
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm text-gray-500">Network Type</span>
                        <p class="font-medium">${plan.network_type}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Speed Range</span>
                        <p class="font-medium">${plan.data_speed_min}-${plan.data_speed_max} Mbps</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Coverage Rating</span>
                        <p class="font-medium">${plan.coverage_rating}/5</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Reliability Score</span>
                        <p class="font-medium">${plan.reliability_score}/10</p>
                    </div>
                </div>
                ${plan.peak_hour_impact === 'significant' ? `
                    <div class="mt-4 text-amber-600 text-sm">
                        ⚠️ Network may be slower during peak hours (6-8pm)
                    </div>
                ` : ''}
            </div>
        </div>

                ${plan.warnings && plan.warnings.length ? `
                    <div>
                        <h4 class="font-medium mb-2">Important Notes:</h4>
                        <ul class="space-y-2">
                            ${plan.warnings.map(warning => `
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-amber-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <span>${warning}</span>
                                </li>
                            `).join('')}
                        </ul>
                    </div>
                ` : ''}

                ${plan.experiences && plan.experiences.length ? `
                    <div>
                        <h4 class="font-medium mb-2">User Experiences:</h4>
                        ${plan.experiences.map(exp => `
                            <div class="border-t pt-4">
                                <div class="text-sm text-gray-600">${exp.title}</div>
                                <div class="mt-1">${exp.context}</div>
                            </div>
                        `).join('')}
                    </div>
                ` : ''}
            </div>
        </div>
    `;

            content.innerHTML = html;
            modal.classList.remove('hidden');
        }

        function closePlanDetails() {
            document.getElementById('planDetailsModal').classList.add('hidden');
        }

        // Feedback handling
        function submitFeedback(type) {
            const commentSection = document.getElementById('feedbackComment');
            if (type === 'negative') {
                commentSection.classList.remove('hidden');
            } else {
                alert('Thanks for your feedback!');
            }
        }

        function submitComment() {
            const comment = document.querySelector('#feedbackComment textarea').value;
            if (comment) {
                // TODO: Implement feedback submission
                alert('Thank you for your feedback!');
                document.getElementById('feedbackComment').classList.add('hidden');
            }
        }

        // Directions handling
        function showDirections(address) {
            const encodedAddress = encodeURIComponent(address + ', Sydney, Australia');
            window.open(`https://www.google.com/maps/search/?api=1&query=${encodedAddress}`, '_blank');
        }
    </script>
</body>

</html>