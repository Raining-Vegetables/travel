<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/access-db.php';

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
        <!-- User Requirements Summary -->
        <div class="bg-blue-50 rounded-xl p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Your Requirements</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-start space-x-3">
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Location</p>
                        <p class="font-medium"><?php echo htmlspecialchars($usage_context['location']); ?></p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Length of Stay</p>
                        <p class="font-medium"><?php echo htmlspecialchars($usage_context['duration']); ?></p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.14 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Data Usage</p>
                        <p class="font-medium"><?php echo htmlspecialchars($usage_context['usage_type']); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Plan Navigation -->
        <div class="flex space-x-4 mb-6">
            <?php foreach ($plans as $key => $plan): ?>
                <button
                    onclick="switchTab('<?php echo $key; ?>')"
                    class="tab-button px-4 py-2 rounded-lg font-medium <?php echo $key === 'recommended' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'; ?>"
                    data-tab="<?php echo $key; ?>">
                    <?php echo ucfirst($key); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Plan Details (One for each plan type) -->
        <?php foreach ($plans as $key => $plan): ?>
            <div class="plan-content <?php echo $key !== 'recommended' ? 'hidden' : ''; ?>" data-tab-content="<?php echo $key; ?>">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
                    <!-- Plan Header -->
                    <div class="p-6 border-b">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <div class="text-sm font-medium text-blue-600 mb-1">
                                    <?php echo strtoupper($key); ?> PLAN
                                </div>
                                <h1 class="text-2xl font-bold text-gray-900">
                                    $<?php echo number_format($plan['price'], 2); ?> -
                                    <?php echo htmlspecialchars($plan['carrier_name']); ?>
                                </h1>
                                <p class="text-gray-600 mt-1"><?php echo formatDataUsage($plan['data_amount']); ?></p>
                            </div>
                            <?php if ($key === 'recommended'): ?>
                                <div class="bg-green-50 text-green-700 text-sm font-medium px-3 py-1 rounded-full">
                                    Best Match
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Plan Explanation -->
                        <div class="mb-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="font-medium text-gray-900 mb-2">
                                    <?php
                                    switch ($key) {
                                        case 'recommended':
                                            echo "Why We Recommend This Plan";
                                            break;
                                        case 'budget':
                                            echo "About This Budget Option";
                                            break;
                                        case 'premium':
                                            echo "About This Premium Option";
                                            break;
                                    }
                                    ?>
                                </h3>
                                <p class="text-gray-600">
                                    <?php echo htmlspecialchars($plan['plan_explanation']); ?>
                                </p>

                                <?php if (!empty($plan['reasoning'])): ?>
                                    <div class="mt-4 space-y-2">
                                        <?php foreach ($plan['reasoning'] as $reason): ?>
                                            <div class="flex items-start gap-2">
                                                <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span class="text-sm text-gray-600"><?php echo htmlspecialchars($reason); ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Plan Features -->
                        <div class="mb-6">
                            <h3 class="font-medium mb-3">Plan Features</h3>
                            <ul class="space-y-2">
                                <?php foreach ($plan['features'] as $feature): ?>
                                    <li class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span><?php echo htmlspecialchars($feature); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Coverage Details -->
                    <div class="p-6 border-b">
                        <h3 class="font-medium mb-4">Coverage Details</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <span class="text-sm text-gray-500">Network Speed</span>
                                <p class="font-medium"><?php echo $plan['data_speed_min']; ?>-<?php echo $plan['data_speed_max']; ?> Mbps</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <span class="text-sm text-gray-500">Coverage Rating</span>
                                <p class="font-medium"><?php echo $plan['coverage_rating']; ?>/5.0</p>
                            </div>
                        </div>
                    </div>

                    <!-- How to Get This Plan Section -->
                    <?php if (!empty($plan['stores'])): ?>
                        <button
                            onclick="toggleInstructions('<?php echo $key; ?>')"
                            class="w-full px-6 py-3 flex items-center justify-between border-b hover:bg-gray-50"
                            data-instructions="<?php echo $key; ?>">
                            <span class="font-medium">How to Get This Plan</span>
                            <svg class="w-5 h-5 text-gray-400 transform transition-transform instructions-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Instructions Content -->
                        <div class="hidden instructions-content" data-instructions-content="<?php echo $key; ?>">
                            <div class="space-y-6 bg-gray-50 p-6">
                                <!-- Store Details -->
                                <?php $store = $plan['stores'][0]; ?>
                                <div class="bg-white rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <div class="bg-blue-100 p-2 rounded-lg">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-medium"><?php echo htmlspecialchars($store['name']); ?></h4>
                                            <?php
                                            $hours = is_string($store['hours']) ? json_decode($store['hours'], true) : $store['hours'];
                                            if (is_array($hours)):
                                            ?>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    Store hours: <?php echo isset($hours['weekday']) ? htmlspecialchars($hours['weekday']) : '9AM-6PM'; ?>
                                                </p>
                                            <?php endif; ?>
                                            <button onclick="showDirections('<?php echo htmlspecialchars($store['address']); ?>')"
                                                class="text-blue-600 text-sm hover:underline mt-2 flex items-center gap-1">
                                                Get directions →
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Phone Unlock Check -->
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <div class="bg-blue-100 p-2 rounded-lg">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-medium">Ensure Your Phone is Unlocked</h4>
                                            <p class="text-sm text-gray-600 mt-1">Your phone must be unlocked to use this plan. Click below to learn how to check.</p>
                                            <button onclick="showUnlockGuide()"
                                                class="mt-2 text-blue-600 text-sm hover:underline flex items-center gap-1">
                                                How to check if your phone is unlocked →
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <!-- What to Bring -->
                                    <div class="bg-white rounded-lg p-4">
                                        <h4 class="font-medium mb-2">What to Bring:</h4>
                                        <ul class="space-y-2">
                                            <li class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span>Cash or card (they accept international cards)</span>
                                            </li>
                                            <li class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span>Photo ID (passport works)</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Setup Info -->
                                    <div class="bg-white rounded-lg p-4">
                                        <h4 class="font-medium mb-2">Setup Information:</h4>
                                        <ul class="space-y-2">
                                            <li class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>Takes about 15-20 minutes</span>
                                            </li>
                                            <li class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span>Staff will help you activate</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Help & Support -->
                                    <div class="bg-white rounded-lg p-4">
                                        <h4 class="font-medium mb-2">If You Need Help Later:</h4>
                                        <ul class="space-y-2 text-gray-600">
                                            <li>• Check balance: Dial <?php echo htmlspecialchars($plan['support_info']['balance_check'] ?? '*100#'); ?></li>
                                            <li>• Customer service: <?php echo htmlspecialchars($plan['support_info']['customer_service'] ?? '125 111'); ?> (free call)</li>
                                        </ul>
                                    </div>

                                    <!-- Backup Option -->
                                    <?php if (!empty($store['backup_store'])): ?>
                                        <div class="bg-amber-50 rounded-lg p-4">
                                            <h4 class="font-medium mb-2">Backup Option:</h4>
                                            <p class="text-gray-700">If store is closed, visit:</p>
                                            <p class="font-medium mt-1"><?php echo htmlspecialchars($store['backup_store']['name']); ?></p>
                                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($store['backup_store']['address']); ?></p>
                                            <p class="text-sm text-gray-600">Open <?php echo htmlspecialchars($store['backup_store']['hours']); ?></p>
                                            <button
                                                onclick="showDirections('<?php echo htmlspecialchars($store['backup_store']['address']); ?>')"
                                                class="text-blue-600 text-sm hover:underline mt-2 flex items-center gap-1">
                                                Get directions →
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Honest Insights Section -->
                    <?php if (!empty($plan['honest_insights'])): ?>
                        <div class="p-6">
                            <h3 class="font-medium mb-4">Honest Insights</h3>
                            <div class="space-y-4">
                                <?php foreach ($plan['honest_insights'] as $insight): ?>
                                    <div class="bg-amber-50 rounded-lg p-4">
                                        <?php if (!empty($insight['marketing_claim'])): ?>
                                            <div class="mb-3">
                                                <span class="text-sm text-gray-600 font-medium">What they say:</span>
                                                <p class="text-gray-800"><?php echo htmlspecialchars($insight['marketing_claim']); ?></p>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!empty($insight['reality'])): ?>
                                            <div class="mb-3">
                                                <span class="text-sm text-gray-600 font-medium">Reality:</span>
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

                    <!-- Known Coverage Issues -->
                    <div class="p-6 border-b">
                        <h4 class="text-sm font-medium text-gray-500 mb-3">Known Coverage Issues</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <p class="font-medium mb-1">
                                        <?php
                                        switch ($key) {
                                            case 'recommended':
                                                echo "Signal drops in underground shopping centers";
                                                break;
                                            case 'budget':
                                                echo "Weak signal in building basements";
                                                break;
                                            case 'premium':
                                                echo "Some coverage gaps in new train tunnels";
                                                break;
                                        }
                                        ?>
                                    </p>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <?php
                                        switch ($key) {
                                            case 'recommended':
                                                echo "Users report consistent signal loss in underground areas.";
                                                break;
                                            case 'budget':
                                                echo "Coverage can be limited in underground areas and thick-walled buildings.";
                                                break;
                                            case 'premium':
                                                echo "Brief connection drops possible in some tunnel sections.";
                                                break;
                                        }
                                        ?>
                                    </p>
                                    <p class="text-sm text-blue-600">
                                        💡 Tip: Connect to free WiFi networks in
                                        <?php echo $key === 'premium' ? 'stations' : 'shopping centers'; ?>
                                        when available.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Compare Plans Button -->
        <button
            onclick="toggleComparison()"
            class="w-full py-3 bg-gray-100 rounded-lg text-gray-600 font-medium hover:bg-gray-200 mb-6 flex items-center justify-center">
            <span>Compare All Plans</span>
            <svg class="ml-2 w-5 h-5 transform transition-transform comparison-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Comparison Table -->
        <div id="comparisonTable" class="hidden bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Feature</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Budget</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Recommended</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Premium</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Price</td>
                        <td class="px-6 py-4 text-sm text-gray-500">$<?php echo number_format($plans['budget']['price'], 2); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500">$<?php echo number_format($plans['recommended']['price'], 2); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500">$<?php echo number_format($plans['premium']['price'], 2); ?></td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Data</td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo formatDataUsage($plans['budget']['data_amount']); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo formatDataUsage($plans['recommended']['data_amount']); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo formatDataUsage($plans['premium']['data_amount']); ?></td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Speed</td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo $plans['budget']['data_speed_min']; ?>-<?php echo $plans['budget']['data_speed_max']; ?> Mbps</td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo $plans['recommended']['data_speed_min']; ?>-<?php echo $plans['recommended']['data_speed_max']; ?> Mbps</td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo $plans['premium']['data_speed_min']; ?>-<?php echo $plans['premium']['data_speed_max']; ?> Mbps</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Coverage</td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo $plans['budget']['coverage_rating']; ?>/5</td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo $plans['recommended']['coverage_rating']; ?>/5</td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo $plans['premium']['coverage_rating']; ?>/5</td>
                    </tr>
                </tbody>
            </table>
        </div>

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
            <button onclick="closeUnlockGuide()" class="mt-6 w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Got it
            </button>
        </div>
    </div>

    <script>
        // Instructions toggle functionality
        function toggleInstructions(planId) {
            const content = document.querySelector(`[data-instructions-content="${planId}"]`);
            const button = document.querySelector(`[data-instructions="${planId}"]`);
            const arrow = button.querySelector('.instructions-arrow');

            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                arrow.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }
        }

        // Tab switching functionality
        function switchTab(tabId) {
            // Hide all plan content
            document.querySelectorAll('.plan-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Show selected plan content
            document.querySelector(`[data-tab-content="${tabId}"]`).classList.remove('hidden');

            // Update tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                if (button.dataset.tab === tabId) {
                    button.classList.remove('bg-gray-100', 'text-gray-600');
                    button.classList.add('bg-blue-600', 'text-white');
                } else {
                    button.classList.remove('bg-blue-600', 'text-white');
                    button.classList.add('bg-gray-100', 'text-gray-600');
                }
            });
        }

        // Comparison table toggle
        function toggleComparison() {
            const table = document.getElementById('comparisonTable');
            const arrow = document.querySelector('.comparison-arrow');
            const isHidden = table.classList.contains('hidden');

            if (isHidden) {
                table.classList.remove('hidden');
                arrow.classList.add('rotate-180');
            } else {
                table.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }
        }

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

        // Feedback handling
        function submitFeedback(type) {
            alert(`Thank you for your ${type} feedback!`);
        }

        function showDirections(address) {
            const encodedAddress = encodeURIComponent(address + ', Sydney, Australia');
            window.open(`https://www.google.com/maps/search/?api=1&query=${encodedAddress}`, '_blank');
        }
    </script>
</body>

</html>