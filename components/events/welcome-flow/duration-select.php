<!-- components/events/welcome-flow/duration-select.php -->
<div class="min-h-screen flex flex-col items-center justify-center p-6">
    <div class="w-full max-w-md">
        <!-- Dynamic header based on visitor type -->
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold mb-2 duration-title">How long are you staying?</h2>
            <p class="text-white/70 duration-subtitle"></p>
        </div>

        <div class="space-y-4">
            <!-- Calendar Input for Planning Visitors -->
            <div id="planningDates" class="hidden space-y-4">
                <div class="glass-card rounded-xl p-6">
                    <label class="block text-sm font-medium mb-2">When are you arriving?</label>
                    <input type="date"
                        class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        id="arrivalDate">
                </div>
            </div>

            <!-- Duration Selection -->
            <div class="space-y-4">
                <button onclick="selectDuration('few_days')"
                    class="w-full glass-card rounded-xl p-6 hover:bg-white/20 transition-all transform hover:scale-105 text-left group">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-medium mb-1">Short stay</h3>
                            <p class="text-white/60 text-sm">2-3 days</p>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <span class="text-xs px-2 py-1 bg-white/10 rounded-full">Must-see highlights</span>
                                <span class="text-xs px-2 py-1 bg-white/10 rounded-full">Quick experiences</span>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-5 h-5 text-white/60 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </button>

                <button onclick="selectDuration('week')"
                    class="w-full glass-card rounded-xl p-6 hover:bg-white/20 transition-all transform hover:scale-105 text-left group">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-medium mb-1">Extended visit</h3>
                            <p class="text-white/60 text-sm">4-7 days</p>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <span class="text-xs px-2 py-1 bg-white/10 rounded-full">Local favorites</span>
                                <span class="text-xs px-2 py-1 bg-white/10 rounded-full">Hidden gems</span>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-5 h-5 text-white/60 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </button>

                <button onclick="selectDuration('long')"
                    class="w-full glass-card rounded-xl p-6 hover:bg-white/20 transition-all transform hover:scale-105 text-left group">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-medium mb-1">Extended stay</h3>
                            <p class="text-white/60 text-sm">More than a week</p>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <span class="text-xs px-2 py-1 bg-white/10 rounded-full">Local lifestyle</span>
                                <span class="text-xs px-2 py-1 bg-white/10 rounded-full">Community events</span>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-5 h-5 text-white/60 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </button>
            </div>
        </div>

        <!-- Budget Indication (Optional) -->
        <div class="mt-6">
            <div class="glass-card rounded-xl p-6">
                <label class="block text-sm font-medium mb-4">What's your event budget?</label>
                <div class="flex gap-2">
                    <button onclick="setBudget('low')" class="flex-1 py-2 px-4 rounded-lg bg-white/10 hover:bg-white/20 transition-all">$</button>
                    <button onclick="setBudget('medium')" class="flex-1 py-2 px-4 rounded-lg bg-white/10 hover:bg-white/20 transition-all">$$</button>
                    <button onclick="setBudget('high')" class="flex-1 py-2 px-4 rounded-lg bg-white/10 hover:bg-white/20 transition-all">$$$</button>
                </div>
            </div>
        </div>

        <!-- Progress Indicator -->
        <div class="mt-8 flex justify-center gap-2">
            <div class="w-2 h-2 rounded-full bg-white/30"></div>
            <div class="w-2 h-2 rounded-full bg-white"></div>
            <div class="w-2 h-2 rounded-full bg-white/30"></div>
        </div>
    </div>
</div>

<script>
    let selectedBudget = null;

    // Initialize page based on visitor type
    document.addEventListener('DOMContentLoaded', function() {
        const visitorType = localStorage.getItem('visitor-type');
        const titleElement = document.querySelector('.duration-title');
        const subtitleElement = document.querySelector('.duration-subtitle');
        const planningDates = document.getElementById('planningDates');

        if (visitorType === 'planning') {
            titleElement.textContent = 'When are you visiting Sydney?';
            subtitleElement.textContent = 'We\'ll show you events happening during your stay ';
            planningDates.classList.remove('hidden');
        } else {
            titleElement.textContent = 'How long are you in Sydney?';
            subtitleElement.textContent = 'We\'ll customize your event recommendations ';
        }
    });

    function setBudget(budget) {
        selectedBudget = budget;
        // Remove active state from all budget buttons
        document.querySelectorAll('[onclick^="setBudget"]').forEach(btn => {
            btn.classList.remove('bg-white/20');
        });
        // Add active state to selected button
        event.target.classList.add('bg-white/20');
    }

    function selectDuration(duration) {
        const visitorType = localStorage.getItem('visitor-type');
        const arrivalDate = document.getElementById('arrivalDate')?.value;

        // Store all relevant information
        localStorage.setItem('stay-duration', duration);
        localStorage.setItem('budget', selectedBudget || 'medium');

        if (visitorType === 'planning' && arrivalDate) {
            localStorage.setItem('arrival-date', arrivalDate);
        }

        window.location.href = 'index.php?page=interests';
    }
</script>