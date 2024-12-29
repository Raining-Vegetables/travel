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
    <!-- Navigation -->
    <?php include 'partials/nav.php'; ?>

    <!-- Navigation -->
    <?php include 'partials/nav.php'; ?>

    <!-- Header Section -->
    <div class="bg-gradient-to-b from-blue-50/50 to-white border-b">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Back Button -->
            <div class="py-4">
                <a href="../index.php" class="inline-flex items-center text-gray-600 hover:text-blue-600 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Search
                </a>
            </div>

            <!-- Title and Summary -->
            <div class="py-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Your Perfect Sydney Phone Plan</h1>
                <p class="mt-2 text-gray-600">Customized recommendations based on your needs</p>
            </div>

            <!-- Stay Details Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pb-8">
                <!-- Location Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-blue-50 rounded-lg">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Location</p>
                            <h3 class="font-semibold text-gray-900"><?php echo ucfirst($data['search_params']['area']); ?> Sydney</h3>
                        </div>
                    </div>
                </div>

                <!-- Usage Type Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-blue-50 rounded-lg">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Usage Type</p>
                            <h3 class="font-semibold text-gray-900"><?php echo $usage_context['description']; ?></h3>
                        </div>
                    </div>
                </div>

                <!-- Duration Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-blue-50 rounded-lg">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Duration</p>
                            <h3 class="font-semibold text-gray-900"><?php echo $data['search_params']['duration_days']; ?> days</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommendations -->
    <div class="max-w-4xl mx-auto px-4 py-8">

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <?php
                echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>
        <!-- Best Match Plan -->
        <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-2 border-blue-500">
                <div class="bg-blue-500 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-white/90 text-sm font-medium">RECOMMENDED FOR YOU</div>
                            <h2 class="text-2xl font-bold text-white">
                                $<?php echo number_format($plans['recommended']['price'], 2); ?> - <?php echo htmlspecialchars($plans['recommended']['carrier_name']); ?>
                            </h2>
                            <p class="text-white/80 text-sm mt-1"><?php echo formatDataUsage($plans['recommended']['data_amount']); ?></p>
                        </div>
                        <div class="bg-white text-blue-500 px-4 py-1 rounded-full font-medium">Best Match</div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Perfect For Section -->
                    <div class="mb-6">
                        <h3 class="font-medium text-sm text-gray-500 mb-2">PERFECT FOR</h3>
                        <p class="text-lg">Your <?php echo $data['search_params']['duration_days']; ?>-day stay in <?php echo ucfirst($data['search_params']['area']); ?> with <?php echo $usage_context['description']; ?></p>
                    </div>

                    <!-- Why This Plan Section -->
                    <div class="mb-6">
                        <h3 class="font-medium text-sm text-gray-500 mb-2">WHY THIS PLAN</h3>
                        <ul class="space-y-3">
                            <?php foreach ($plans['recommended']['reasons'] as $reason): ?>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 mt-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span><?php echo htmlspecialchars($reason); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>



                    <!-- What You Need Section -->
                    <div class="mb-6">
                        <h3 class="font-medium text-sm text-gray-500 mb-2">WHAT YOU NEED</h3>
                        <ul class="space-y-3">
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                </svg>
                                <span>Your passport</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                <span>Payment method (they accept international cards)</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <span>Unlocked phone (<button class="text-blue-600 hover:underline" onclick="showUnlockGuide()">check here</button>)</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Get It Here Section -->
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

                    <!-- Important Notes -->
                    <?php if (!empty($plans['recommended']['warnings'])): ?>
                        <div class="mb-6">
                            <h3 class="font-medium text-sm text-gray-500 mb-2">GOOD TO KNOW</h3>
                            <div class="bg-amber-50 rounded-lg p-4">
                                <ul class="space-y-2">
                                    <?php foreach ($plans['recommended']['warnings'] as $warning): ?>
                                        <li class="flex items-start gap-2">
                                            <svg class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                            <span class="text-gray-700"><?php echo htmlspecialchars($warning); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Usage Context -->
                    <div class="mb-6">
                        <h3 class="font-medium text-sm text-gray-500 mb-2">TYPICAL USAGE</h3>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <ul class="space-y-2">
                                <li class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Daily Usage: <?php echo $plans['recommended']['data_per_day']; ?></span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Perfect for: <?php echo $plans['recommended']['typical_activities']; ?></span>
                                </li>
                            </ul>
                        </div>
                    </div>


                    <!-- Real World Experiences Section -->
                    <?php if (!empty($plans['recommended']['experiences'])): ?>
                        <div class="mb-6">
                            <h3 class="font-medium text-sm text-gray-500 mb-2">REAL EXPERIENCES</h3>
                            <div class="space-y-3">
                                <?php foreach ($plans['recommended']['experiences'] as $experience): ?>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center gap-2 mb-1">
                                            <!-- Rating Stars -->
                                            <div class="flex">
                                                <?php for ($i = 0; $i < $experience['rating']; $i++): ?>
                                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                <?php endfor; ?>
                                            </div>
                                            <span class="text-sm text-gray-500"><?php echo $experience['usage_period']; ?></span>
                                        </div>
                                        <p class="text-sm"><?php echo htmlspecialchars($experience['context']); ?></p>
                                        <?php if (!empty($experience['pros'])): ?>
                                            <p class="text-sm text-green-600 mt-2">✓ <?php echo htmlspecialchars($experience['pros']); ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($experience['cons'])): ?>
                                            <p class="text-sm text-red-600 mt-1">✗ <?php echo htmlspecialchars($experience['cons']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Alternative Options -->
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Budget Option -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="text-sm font-medium text-gray-600 mb-1">SAVE MONEY WITH</div>
                    <h3 class="text-xl font-semibold mb-2">
                        $<?php echo number_format($plans['budget']['price'], 2); ?> - <?php echo htmlspecialchars($plans['budget']['carrier_name']); ?>
                    </h3>
                    <p class="text-gray-600 text-sm mb-4"><?php echo formatDataUsage($plans['budget']['data_amount']); ?></p>

                    <div class="space-y-4">
                        <div class="text-sm text-gray-600">
                            Still works for your needs but:
                            <ul class="mt-2 space-y-2">
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Might need to top up if you stream a lot</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Need to visit city store to activate</span>
                                </li>
                            </ul>
                        </div>

                        <button onclick="showPlanDetails('budget')" class="w-full py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">
                            See details →
                        </button>
                    </div>
                </div>
            </div>

            <!-- Premium Option -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="text-sm font-medium text-purple-600 mb-1">PREMIUM EXPERIENCE</div>
                    <h3 class="text-xl font-semibold mb-2">
                        $<?php echo number_format($plans['premium']['price'], 2); ?> - <?php echo htmlspecialchars($plans['premium']['carrier_name']); ?>
                    </h3>
                    <p class="text-gray-600 text-sm mb-4"><?php echo formatDataUsage($plans['premium']['data_amount']); ?></p>

                    <div class="space-y-4">
                        <div class="text-sm text-gray-600">
                            Extra benefits include:
                            <ul class="mt-2 space-y-2">
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Unlimited data</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Priority network access</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>International calls included</span>
                                </li>
                            </ul>
                        </div>

                        <button onclick="showPlanDetails('premium')" class="w-full py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">
                            See details →
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Options -->
        <div class="mt-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Save these details for later:</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <button onclick="saveOption('sms')" class="flex items-center justify-center gap-2 p-3 rounded-lg border hover:bg-gray-50 transition-colors">
                        📱 Send to my phone
                    </button>
                    <button onclick="saveOption('email')" class="flex items-center justify-center gap-2 p-3 rounded-lg border hover:bg-gray-50 transition-colors">
                        📧 Email to myself
                    </button>
                    <button onclick="saveOption('wallet')" class="flex items-center justify-center gap-2 p-3 rounded-lg border hover:bg-gray-50 transition-colors">
                        📲 Add to wallet
                    </button>
                    <button onclick="saveOption('copy')" class="flex items-center justify-center gap-2 p-3 rounded-lg border hover:bg-gray-50 transition-colors">
                        🔗 Copy link
                    </button>
                </div>
            </div>
        </div>

        <!-- Feedback -->
        <div class="mt-8 text-center">
            <p class="text-gray-600 mb-2">Was this helpful?</p>
            <div class="flex justify-center gap-4">
                <button onclick="submitFeedback('positive')" class="text-2xl hover:scale-110 transition-transform">👍</button>
                <button onclick="submitFeedback('negative')" class="text-2xl hover:scale-110 transition-transform">👎</button>
            </div>
            <div id="feedbackComment" class="hidden mt-4">
                <textarea class="w-full max-w-md mx-auto p-2 border rounded-lg"
                    placeholder="How can we improve?"></textarea>
                <button onclick="submitComment()"
                    class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Submit
                </button>
            </div>
        </div>
    </div>

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