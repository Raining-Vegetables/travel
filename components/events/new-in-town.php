<?php
// components/events/new-in-town.php

// Add this to your validPages array in index.php:
// 'new-in-town' => 'components/events/new-in-town.php',
?>

<div class="min-h-screen bg-gradient-to-b from-gray-900 to-black">
    <!-- Top Navigation -->
    <div class="sticky top-0 bg-black/50 backdrop-blur-lg border-b border-white/10 z-50">
        <div class="max-w-2xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-2">
                    <span class="text-xl font-bold text-white">sydney</span>
                    <span class="text-sm px-2 py-1 bg-blue-500/20 text-blue-400 rounded">buddy</span>
                </div>
                <div class="flex items-center gap-4">
                    <button class="p-2 text-white/70 hover:text-white">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                    </button>
                    <button class="w-8 h-8 rounded-full bg-white/10">
                        <img src="path/to/avatar.jpg" class="w-full h-full rounded-full" alt="Profile">
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-6">
        <!-- <h2 class="text-xl font-bold">New in Town Guide</h2> -->

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white mb-2">New in Town Guide</h1>
            <p class="text-white/70">Find your way around</p>
        </div>

        <!-- Quick Setup Cards -->
        <div class="grid grid-cols-2 gap-4">
            <?php
            $setupCards = [
                ['title' => 'Phone Plans', 'icon' => 'phone', 'color' => 'blue', 'content' => [
                    'Telstra' => 'Starting from $30/month',
                    'Optus' => 'Starting from $25/month',
                    'Vodafone' => 'Starting from $28/month'
                ]],
                ['title' => 'Find Housing', 'icon' => 'building', 'color' => 'purple', 'content' => [
                    'Popular Areas' => 'CBD, Surry Hills, Newtown',
                    'Avg. Rent' => 'Studios from $400/week',
                    'Tips' => 'Check Domain.com.au'
                ]],
                ['title' => 'Transport Guide', 'icon' => 'train', 'color' => 'green', 'content' => [
                    'Opal Card' => 'Get at any station',
                    'Weekly Cap' => '$50 max per week',
                    'Airport' => 'Train direct to CBD'
                ]],
                ['title' => 'Safety Tips', 'icon' => 'shield', 'color' => 'red', 'content' => [
                    'Emergency' => 'Call 000',
                    'Beach Safety' => 'Swim between flags',
                    'Police' => 'Call 131 444 (non-emergency)'
                ]]
            ];

            foreach ($setupCards as $card): ?>
                <button
                    onclick="showGuideModal('<?php echo $card['title']; ?>')"
                    class="p-4 bg-<?php echo $card['color']; ?>-500/20 rounded-xl hover:bg-<?php echo $card['color']; ?>-500/30 transition-colors text-left">
                    <i data-lucide="<?php echo $card['icon']; ?>" class="w-6 h-6 mb-2"></i>
                    <h3 class="font-medium"><?php echo $card['title']; ?></h3>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Checklist -->
        <div class="bg-white/10 rounded-xl p-4 mt-5 mb-5">
            <h3 class="font-medium mb-4">Getting Started Checklist</h3>
            <div class="space-y-2">
                <?php
                $checklistItems = [
                    'Set up phone plan',
                    'Get an Opal card',
                    'Open bank account',
                    'Find accommodation',
                    'Register for Medicare'
                ];

                foreach ($checklistItems as $item): ?>
                    <label class="flex items-center gap-3 p-2 hover:bg-white/5 rounded-lg cursor-pointer">
                        <input type="checkbox" class="rounded border-white/20 bg-white/10">
                        <span><?php echo $item; ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Local Tips -->
        <div class="bg-white/10 rounded-xl p-4">
            <h3 class="font-medium mb-4">Local Tips</h3>
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <i data-lucide="sun" class="w-5 h-5 text-yellow-400 flex-shrink-0"></i>
                    <p class="text-sm text-white/80">UV is strong - always wear sunscreen, even on cloudy days</p>
                </div>
                <div class="flex items-start gap-3">
                    <i data-lucide="waves" class="w-5 h-5 text-blue-400 flex-shrink-0"></i>
                    <p class="text-sm text-white/80">Always swim between the flags at beaches</p>
                </div>
                <div class="flex items-start gap-3">
                    <i data-lucide="coffee" class="w-5 h-5 text-amber-400 flex-shrink-0"></i>
                    <p class="text-sm text-white/80">Coffee culture is big - try flat white, a local favorite</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Navigation -->
<div class="fixed bottom-0 left-0 right-0 bg-black/50 backdrop-blur-lg border-t border-white/10">
    <div class="max-w-2xl mx-auto px-4">
        <div class="flex justify-around py-4">
            <?php
            $navItems = [
                ['icon' => 'home', 'label' => 'Home', 'page' => 'events', 'active' => true],
                ['icon' => 'compass', 'label' => 'Explore', 'active' => false],
                ['icon' => 'info', 'label' => 'New in Town', 'page' => 'new-in-town', 'active' => true],
                ['icon' => 'user', 'label' => 'Profile', 'active' => false]
            ];

            $currentPage = $_GET['page'] ?? 'events';

            foreach ($navItems as $item):
                if ($item['active']): ?>
                    <a
                        href="index.php?page=<?php echo $item['page']; ?>"
                        class="flex flex-col items-center gap-1 <?php echo $currentPage === $item['page'] ? 'text-white' : 'text-white/40'; ?>">
                        <i data-lucide="<?php echo $item['icon']; ?>" class="w-6 h-6"></i>
                        <span class="text-xs"><?php echo $item['label']; ?></span>
                    </a>
                <?php else: ?>
                    <button class="flex flex-col items-center gap-1 text-white/40 cursor-not-allowed">
                        <i data-lucide="<?php echo $item['icon']; ?>" class="w-6 h-6"></i>
                        <span class="text-xs"><?php echo $item['label']; ?></span>
                    </button>
            <?php endif;
            endforeach; ?>
        </div>
    </div>
</div>

<!-- Modal Template -->
<div id="guideModal" class="fixed inset-0 bg-black/75 backdrop-blur-sm hidden z-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-gray-900/95 rounded-xl border border-white/20 max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modalTitle" class="text-lg font-medium"></h3>
                <button onclick="hideGuideModal()" class="p-1 hover:bg-white/10 rounded">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div id="modalContent" class="space-y-4">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
    const guideContent = <?php echo json_encode($setupCards); ?>;

    function showGuideModal(title) {
        const modal = document.getElementById('guideModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalContent = document.getElementById('modalContent');

        const card = guideContent.find(c => c.title === title);
        if (!card) return;

        modalTitle.textContent = card.title;

        // Build content HTML
        let contentHTML = '';
        for (const [key, value] of Object.entries(card.content)) {
            contentHTML += `
            <div class="p-3 bg-white/10 rounded-lg">
                <h5 class="font-medium">${key}</h5>
                <p class="text-sm text-white/60">${value}</p>
            </div>
        `;
        }
        modalContent.innerHTML = contentHTML;

        modal.classList.remove('hidden');
    }

    function hideGuideModal() {
        document.getElementById('guideModal').classList.add('hidden');
    }

    // Initialize Lucide icons after any modal content changes
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>