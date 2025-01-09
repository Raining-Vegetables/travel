<!-- components/events/welcome-flow/location-select.php -->
<div class="min-h-screen flex flex-col items-center justify-center p-6">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold mb-2">Where in Sydney are you?</h2>
            <p class="text-white/70">Help us find events near you</p>
        </div>

        <!-- Location Selection -->
        <div class="glass-card rounded-xl p-6 mb-6">
            <div class="space-y-4">
                <h3 class="font-medium mb-4">Select your area</h3>

                <button onclick="selectLocation('cbd')"
                    class="location-btn w-full p-4 text-left rounded-xl hover:bg-white/10 transition-all flex items-center gap-4 group"
                    data-location="cbd">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i data-lucide="building" class="w-6 h-6 text-blue-400"></i>
                    </div>
                    <div>
                        <span class="font-medium block">CBD & Inner City</span>
                        <span class="text-sm text-white/60">City, Surry Hills, Darlinghurst</span>
                    </div>
                    <i data-lucide="check" class="w-5 h-5 ml-auto text-green-400 opacity-0 group-data-[selected=true]:opacity-100 transition-opacity"></i>
                </button>

                <button onclick="selectLocation('eastern')"
                    class="location-btn w-full p-4 text-left rounded-xl hover:bg-white/10 transition-all flex items-center gap-4 group"
                    data-location="eastern">
                    <div class="w-12 h-12 bg-cyan-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i data-lucide="waves" class="w-6 h-6 text-cyan-400"></i>
                    </div>
                    <div>
                        <span class="font-medium block">Eastern Beaches</span>
                        <span class="text-sm text-white/60">Bondi, Coogee, Bronte</span>
                    </div>
                    <i data-lucide="check" class="w-5 h-5 ml-auto text-green-400 opacity-0 group-data-[selected=true]:opacity-100 transition-opacity"></i>
                </button>

                <button onclick="selectLocation('inner-west')"
                    class="location-btn w-full p-4 text-left rounded-xl hover:bg-white/10 transition-all flex items-center gap-4 group"
                    data-location="inner-west">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i data-lucide="coffee" class="w-6 h-6 text-purple-400"></i>
                    </div>
                    <div>
                        <span class="font-medium block">Inner West</span>
                        <span class="text-sm text-white/60">Newtown, Marrickville, Glebe</span>
                    </div>
                    <i data-lucide="check" class="w-5 h-5 ml-auto text-green-400 opacity-0 group-data-[selected=true]:opacity-100 transition-opacity"></i>
                </button>

                <button onclick="selectLocation('north-shore')"
                    class="location-btn w-full p-4 text-left rounded-xl hover:bg-white/10 transition-all flex items-center gap-4 group"
                    data-location="north-shore">
                    <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i data-lucide="trees" class="w-6 h-6 text-green-400"></i>
                    </div>
                    <div>
                        <span class="font-medium block">North Shore</span>
                        <span class="text-sm text-white/60">Chatswood, North Sydney</span>
                    </div>
                    <i data-lucide="check" class="w-5 h-5 ml-auto text-green-400 opacity-0 group-data-[selected=true]:opacity-100 transition-opacity"></i>
                </button>

                <button onclick="selectLocation('northern-beaches')"
                    class="location-btn w-full p-4 text-left rounded-xl hover:bg-white/10 transition-all flex items-center gap-4 group"
                    data-location="northern-beaches">
                    <div class="w-12 h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i data-lucide="sun" class="w-6 h-6 text-yellow-400"></i>
                    </div>
                    <div>
                        <span class="font-medium block">Northern Beaches</span>
                        <span class="text-sm text-white/60">Manly, Dee Why, Mona Vale</span>
                    </div>
                    <i data-lucide="check" class="w-5 h-5 ml-auto text-green-400 opacity-0 group-data-[selected=true]:opacity-100 transition-opacity"></i>
                </button>
            </div>
        </div>

        <!-- Transport Preferences -->
        <div class="glass-card rounded-xl p-6 mb-6">
            <h3 class="font-medium mb-4">How will you get around?</h3>
            <div class="space-y-3">
                <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-white/5 cursor-pointer">
                    <input type="checkbox" class="rounded border-white/20 bg-white/10" onchange="toggleTransport('public')" checked>
                    <span class="text-sm">Public transport</span>
                </label>
                <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-white/5 cursor-pointer">
                    <input type="checkbox" class="rounded border-white/20 bg-white/10" onchange="toggleTransport('walking')">
                    <span class="text-sm">Walking distance only</span>
                </label>
                <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-white/5 cursor-pointer">
                    <input type="checkbox" class="rounded border-white/20 bg-white/10" onchange="toggleTransport('car')">
                    <span class="text-sm">I have a car</span>
                </label>
            </div>
        </div>

        <!-- Event Range -->
        <div class="glass-card rounded-xl p-6 mb-6">
            <h3 class="font-medium mb-4">How far would you travel for events?</h3>
            <select id="range" class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2 text-white">
                <option value="nearby">Nearby only (up to 2km)</option>
                <option value="medium" selected>Medium distance (up to 5km)</option>
                <option value="anywhere">Anywhere in Sydney</option>
            </select>
        </div>

        <button onclick="savePreferences()"
            id="continue-btn"
            disabled
            class="w-full py-4 glass-card rounded-xl text-white font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed hover:bg-white/20">
            Show me events
        </button>

        <!-- Progress Indicator -->
        <div class="mt-8 flex justify-center gap-2">
            <div class="w-2 h-2 rounded-full bg-white/30"></div>
            <div class="w-2 h-2 rounded-full bg-white/30"></div>
            <div class="w-2 h-2 rounded-full bg-white/30"></div>
            <div class="w-2 h-2 rounded-full bg-white"></div>
        </div>
    </div>
</div>

<script>
    // Initialize Lucide icons
    lucide.createIcons();

    let selectedLocation = null;
    let transportModes = new Set(['public']); // Public transport checked by default
    const continueBtn = document.getElementById('continue-btn');

    function selectLocation(location) {
        // Remove previous selection
        document.querySelectorAll('.location-btn').forEach(btn => {
            btn.dataset.selected = 'false';
        });

        // Set new selection
        const btn = document.querySelector(`[data-location="${location}"]`);
        btn.dataset.selected = 'true';
        selectedLocation = location;

        updateContinueButton();
    }

    function toggleTransport(mode) {
        if (transportModes.has(mode)) {
            transportModes.delete(mode);
        } else {
            transportModes.add(mode);
        }
    }

    function updateContinueButton() {
        continueBtn.disabled = !selectedLocation;
    }

    function savePreferences() {
        if (selectedLocation) {
            localStorage.setItem('location', selectedLocation);
            localStorage.setItem('transport', JSON.stringify([...transportModes]));
            localStorage.setItem('range', document.getElementById('range').value);
            window.location.href = 'index.php?page=events';
        }
    }
</script>