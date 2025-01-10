<?php
require_once __DIR__ . '/../../helpers/ticketmaster.php';
require_once __DIR__ . '/../../helpers/meetup.php';
require_once __DIR__ . '/../../helpers/event-matcher.php';

// Initialize arrays
$tmEvents = [];
$muEvents = [];
$allEvents = [];

try {
    // Initialize APIs and EventMatcher
    $ticketmaster = new TicketmasterAPI('sU3NDmUv2qoBuD76gzrY5ZJ2S8m6GToz');
    $meetup = new MeetupAPI();
    $matcher = new EventMatcher();

    // Get base preferences for API calls
    $basePreferences = [
        'startDate' => isset($_COOKIE['arrival-date']) ? $_COOKIE['arrival-date'] : date('Y-m-d')
    ];

    // Get Ticketmaster events
    $eventsResponse = $ticketmaster->getEvents($basePreferences);
    if (isset($eventsResponse['_embedded']['events'])) {
        foreach ($eventsResponse['_embedded']['events'] as $event) {
            $formattedEvent = $ticketmaster->formatEvent($event);
            $formattedEvent['source'] = 'ticketmaster';
            $tmEvents[] = $formattedEvent;
        }
    }

    // Get Meetup events
    $muResponse = $meetup->getEvents($basePreferences);
    if (isset($muResponse['events'])) {
        foreach ($muResponse['events'] as $event) {
            $formattedEvent = $meetup->formatEvent($event);
            if ($formattedEvent !== null) {
                $formattedEvent['source'] = 'meetup';
                $muEvents[] = $formattedEvent;
            }
        }
    }

    // Merge all events
    $allEvents = array_merge($tmEvents, $muEvents);

    // Apply preference-based filtering and sorting
    $allEvents = $matcher->filterEvents($allEvents);
} catch (Exception $e) {
    error_log('Error in feed: ' . $e->getMessage());
    $allEvents = [];
}

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


    <!-- Main Content -->
    <!-- Main Content -->
    <div class="max-w-2xl mx-auto px-4 py-6">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white mb-2">Hey <?php echo htmlspecialchars($_COOKIE['user_name'] ?? 'Mate'); ?> 👋</h1>
            <p class="text-white/70">Here's what's happening in Sydney</p>
        </div>

        <!-- Events List -->
        <div class="space-y-6">
            <?php if (empty($allEvents)): ?>
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="calendar" class="w-8 h-8 text-white/40"></i>
                    </div>
                    <h3 class="text-white font-medium mb-2">No events found</h3>
                    <p class="text-white/60">Try adjusting your preferences or check back later</p>
                </div>
            <?php else: ?>
                <?php foreach ($allEvents as $event): ?>
                    <div class="bg-white/10 rounded-xl overflow-hidden backdrop-blur-lg">
                        <?php if (isset($event['image']) && $event['image']): ?>
                            <div class="aspect-w-16 aspect-h-9 relative">
                                <img
                                    src="<?php echo htmlspecialchars($event['image']); ?>"
                                    class="w-full h-48 object-cover object-center"
                                    loading="lazy"
                                    alt="<?php echo htmlspecialchars($event['name']); ?>"
                                    onerror="this.onerror=null; this.src='/api/placeholder/800/450';" />
                            </div>
                        <?php endif; ?>

                        <div class="p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="inline-block px-2 py-1 bg-blue-500/20 text-blue-400 rounded text-sm">
                                    <?php echo htmlspecialchars($event['type']); ?>
                                </span>
                                <span class="text-sm px-2 py-1 bg-white/10 rounded text-white/60">
                                    via <?php echo ucfirst(htmlspecialchars($event['source'])); ?>
                                </span>
                            </div>
                            <h3 class="text-white font-semibold mb-1"><?php echo htmlspecialchars($event['name']); ?></h3>
                            <p class="text-white/70 text-sm mb-2">
                                <?php echo htmlspecialchars($event['date']); ?> •
                                <?php echo htmlspecialchars($event['time']); ?> •
                                <?php echo htmlspecialchars($event['venue']); ?>
                            </p>
                            <?php if (isset($event['description'])): ?>
                                <p class="text-white/60 text-sm mb-3"><?php echo htmlspecialchars($event['description']); ?></p>
                            <?php endif; ?>
                            <div class="flex items-center justify-between">
                                <span class="text-white/60 text-sm"><?php echo htmlspecialchars($event['priceRange']); ?></span>
                                <a href="<?php echo htmlspecialchars($event['url']); ?>"
                                    target="_blank"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white text-sm transition-colors">
                                    Get Tickets
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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
</div>

<script>
    // Initialize Lucide icons
    lucide.createIcons();
</script>