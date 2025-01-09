<?php
// components/events/feed.php
?>

<div class="min-h-screen bg-gradient-to-b from-gray-900 to-black">
    <!-- Top Navigation -->
    <div class="sticky top-0 bg-black/50 backdrop-blur-lg border-b border-white/10 z-50">
        <div class="max-w-2xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-2">
                    <span class="text-xl font-bold text-white">sydney</span>
                    <span class="text-sm px-2 py-1 bg-blue-500/20 text-blue-400 rounded">mate</span>
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

    <!-- Main Content -->
    <div class="max-w-2xl mx-auto px-4 py-6">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white mb-2">Hey <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Mate'); ?> 👋</h1>
            <p class="text-white/70">Ready to explore Sydney today?</p>
        </div>

        <!-- Search Bar -->
        <div class="relative mb-8">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-white/40"></i>
            <input
                type="text"
                placeholder="Search events, activities, places..."
                class="w-full bg-white/10 border border-white/10 rounded-xl py-3 pl-10 pr-4 text-white placeholder:text-white/40 focus:outline-none focus:border-blue-500">
        </div>

        <!-- For You Section -->
        <div class="mb-12">
            <h2 class="text-lg font-semibold text-white mb-4">For You</h2>
            <div class="flex gap-4 overflow-x-auto pb-4">
                <?php
                $quickAccess = [
                    ['icon' => 'coffee', 'text' => 'Coffee Club', 'gradient' => 'from-blue-500/20 to-purple-500/20'],
                    ['icon' => 'compass', 'text' => 'Adventures', 'gradient' => 'from-orange-500/20 to-red-500/20'],
                    ['icon' => 'book', 'text' => 'Learning', 'gradient' => 'from-green-500/20 to-emerald-500/20'],
                    ['icon' => 'music', 'text' => 'Concerts', 'gradient' => 'from-pink-500/20 to-rose-500/20'],
                ];

                foreach ($quickAccess as $item): ?>
                    <div class="flex-shrink-0 w-32 bg-gradient-to-br <?php echo $item['gradient']; ?> rounded-xl p-4 border border-white/10">
                        <i data-lucide="<?php echo $item['icon']; ?>" class="w-6 h-6 text-white mb-2"></i>
                        <span class="text-white text-sm"><?php echo $item['text']; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- This Weekend Section -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">This Weekend</h2>
                <button class="text-blue-400 text-sm">View all</button>
            </div>

            <div class="bg-white/10 rounded-xl overflow-hidden backdrop-blur-lg">
                <img src="path/to/event-image.jpg" class="w-full h-36 object-cover" alt="Event">
                <div class="p-4">
                    <span class="inline-block px-2 py-1 bg-blue-500/20 text-blue-400 rounded text-sm mb-2">
                        Featured Event
                    </span>
                    <h3 class="text-white font-semibold mb-1">Korean Street Food Festival</h3>
                    <p class="text-white/70 text-sm mb-3">This Saturday • Darling Square</p>
                    <div class="flex items-center gap-4">
                        <button class="flex-1 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white text-sm transition-colors">
                            Join Event
                        </button>
                        <button class="p-2 rounded-lg border border-white/10 hover:bg-white/5">
                            <i data-lucide="heart" class="w-5 h-5 text-white"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Your Calendar -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">Your Calendar</h2>
                <button class="text-blue-400 text-sm">View all</button>
            </div>

            <?php
            $calendarEvents = [
                [
                    'day' => 'SAT',
                    'date' => '15',
                    'title' => 'Sunset Beach Yoga',
                    'time' => '5:30 PM',
                    'location' => 'Bondi Beach',
                    'color' => 'purple'
                ],
                [
                    'day' => 'SUN',
                    'date' => '16',
                    'title' => 'Coffee Club Meetup',
                    'time' => '10:00 AM',
                    'location' => 'The Grounds',
                    'color' => 'blue'
                ]
            ];

            foreach ($calendarEvents as $event): ?>
                <div class="bg-white/10 rounded-xl p-4 backdrop-blur-lg mb-3">
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-<?php echo $event['color']; ?>-500/20 rounded-lg flex flex-col items-center justify-center">
                            <span class="text-<?php echo $event['color']; ?>-400 text-sm"><?php echo $event['day']; ?></span>
                            <span class="text-white font-bold"><?php echo $event['date']; ?></span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-white font-medium mb-1"><?php echo $event['title']; ?></h3>
                            <p class="text-white/60 text-sm"><?php echo $event['time']; ?> • <?php echo $event['location']; ?></p>
                        </div>
                        <div class="flex items-center">
                            <span class="w-2 h-2 bg-<?php echo $event['color']; ?>-400 rounded-full"></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="fixed bottom-0 left-0 right-0 bg-black/50 backdrop-blur-lg border-t border-white/10">
        <div class="max-w-2xl mx-auto px-4">
            <div class="flex justify-around py-4">
                <?php
                $navItems = [
                    ['icon' => 'home', 'label' => 'Home', 'active' => true],
                    ['icon' => 'compass', 'label' => 'Explore', 'active' => false],
                    ['icon' => 'calendar', 'label' => 'Calendar', 'active' => false],
                    ['icon' => 'user', 'label' => 'Profile', 'active' => false],
                ];

                foreach ($navItems as $item): ?>
                    <button class="flex flex-col items-center gap-1 <?php echo $item['active'] ? 'text-white' : 'text-white/40'; ?>">
                        <i data-lucide="<?php echo $item['icon']; ?>" class="w-6 h-6"></i>
                        <span class="text-xs"><?php echo $item['label']; ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize Lucide icons
    lucide.createIcons();
</script>