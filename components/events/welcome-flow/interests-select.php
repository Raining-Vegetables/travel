<!-- components/events/welcome-flow/interests-select.php -->
<div class="min-h-screen flex flex-col items-center justify-center p-6">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold mb-2">What would you like to experience?</h2>
            <p class="text-white/70">Select all that interest you</p>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-8">
            <!-- Markets & Food -->
            <button onclick="toggleInterest('markets')"
                class="interest-btn p-6 rounded-xl border glass-card transition-all text-center"
                data-interest="markets">
                <i data-lucide="shopping-basket" class="w-8 h-8 mb-2 text-yellow-400 mx-auto"></i>
                <span class="text-sm font-medium block mb-1">Markets & Food</span>
                <span class="text-xs text-white/60">The Rocks, Paddington</span>
            </button>

            <!-- Beach Life -->
            <button onclick="toggleInterest('beach')"
                class="interest-btn p-6 rounded-xl border glass-card transition-all text-center"
                data-interest="beach">
                <i data-lucide="waves" class="w-8 h-8 mb-2 text-blue-400 mx-auto"></i>
                <span class="text-sm font-medium block mb-1">Beach Events</span>
                <span class="text-xs text-white/60">Bondi, Manly, Coogee</span>
            </button>

            <!-- Local Festivals -->
            <button onclick="toggleInterest('festivals')"
                class="interest-btn p-6 rounded-xl border glass-card transition-all text-center"
                data-interest="festivals">
                <i data-lucide="party-popper" class="w-8 h-8 mb-2 text-purple-400 mx-auto"></i>
                <span class="text-sm font-medium block mb-1">Local Festivals</span>
                <span class="text-xs text-white/60">Cultural celebrations</span>
            </button>

            <!-- Arts & Culture -->
            <button onclick="toggleInterest('arts')"
                class="interest-btn p-6 rounded-xl border glass-card transition-all text-center"
                data-interest="arts">
                <i data-lucide="palette" class="w-8 h-8 mb-2 text-pink-400 mx-auto"></i>
                <span class="text-sm font-medium block mb-1">Arts & Culture</span>
                <span class="text-xs text-white/60">Opera House, galleries</span>
            </button>

            <!-- Outdoor Activities -->
            <button onclick="toggleInterest('outdoor')"
                class="interest-btn p-6 rounded-xl border glass-card transition-all text-center"
                data-interest="outdoor">
                <i data-lucide="mountain" class="w-8 h-8 mb-2 text-green-400 mx-auto"></i>
                <span class="text-sm font-medium block mb-1">Nature & Parks</span>
                <span class="text-xs text-white/60">Walks, harbor views</span>
            </button>

            <!-- Sports -->
            <button onclick="toggleInterest('sports')"
                class="interest-btn p-6 rounded-xl border glass-card transition-all text-center"
                data-interest="sports">
                <i data-lucide="trophy" class="w-8 h-8 mb-2 text-amber-400 mx-auto"></i>
                <span class="text-sm font-medium block mb-1">Sports Events</span>
                <span class="text-xs text-white/60">Cricket, NRL, AFL</span>
            </button>
        </div>

        <!-- Newcomer Specials -->
        <div class="glass-card rounded-xl p-6 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <i data-lucide="users" class="w-5 h-5 text-blue-400"></i>
                <h3 class="font-medium">Newcomer Events</h3>
            </div>
            <div class="space-y-3">
                <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-white/5 cursor-pointer">
                    <input type="checkbox" class="rounded border-white/20 bg-white/10" onchange="toggleNewcomerEvent('meetups')">
                    <span class="text-sm">Newcomer meetups & social events</span>
                </label>
                <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-white/5 cursor-pointer">
                    <input type="checkbox" class="rounded border-white/20 bg-white/10" onchange="toggleNewcomerEvent('tours')">
                    <span class="text-sm">Guided local tours</span>
                </label>
            </div>
        </div>

        <button onclick="saveInterests()"
            id="continue-btn"
            disabled
            class="w-full py-4 glass-card rounded-xl text-white font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed hover:bg-white/20">
            Continue
        </button>

        <!-- Progress Indicator -->
        <div class="mt-8 flex justify-center gap-2">
            <div class="w-2 h-2 rounded-full bg-white/30"></div>
            <div class="w-2 h-2 rounded-full bg-white/30"></div>
            <div class="w-2 h-2 rounded-full bg-white"></div>
        </div>
    </div>
</div>

<style>
    .interest-btn {
        border-color: rgba(255, 255, 255, 0.2);
    }

    .interest-btn:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: scale(1.02);
    }

    .interest-btn.selected {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.4);
    }
</style>

<script>
    // Initialize Lucide icons
    lucide.createIcons();

    let selectedInterests = new Set();
    let selectedNewcomerEvents = new Set();
    const continueBtn = document.getElementById('continue-btn');

    function toggleInterest(interest) {
        const btn = document.querySelector(`[data-interest="${interest}"]`);

        if (selectedInterests.has(interest)) {
            selectedInterests.delete(interest);
            btn.classList.remove('selected');
        } else {
            selectedInterests.add(interest);
            btn.classList.add('selected');
        }

        updateContinueButton();
    }

    function toggleNewcomerEvent(event) {
        if (selectedNewcomerEvents.has(event)) {
            selectedNewcomerEvents.delete(event);
        } else {
            selectedNewcomerEvents.add(event);
        }

        updateContinueButton();
    }

    function updateContinueButton() {
        continueBtn.disabled = selectedInterests.size === 0 && selectedNewcomerEvents.size === 0;
    }

    function saveInterests() {
        if (selectedInterests.size > 0 || selectedNewcomerEvents.size > 0) {
            document.cookie = `interests=${JSON.stringify([...selectedInterests])}; path=/`;
            document.cookie = `newcomer-events=${JSON.stringify([...selectedNewcomerEvents])}; path=/`;
            window.location.href = 'index.php?page=location';
        }
    }
</script>