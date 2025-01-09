<!-- components/events/welcome-flow/lifestyle-select.php -->
<div class="min-h-screen flex flex-col items-center justify-center p-6">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold mb-2">What's your ideal Sydney lifestyle?</h2>
            <p class="text-white/70">This helps us find your perfect neighborhood</p>
        </div>

        <div class="space-y-4">
            <!-- Beach Lifestyle -->
            <button onclick="selectLifestyle('beach')"
                class="w-full text-left glass-card rounded-xl overflow-hidden hover:bg-white/20 transition-all transform hover:scale-102">
                <img src="/api/placeholder/400/150" alt="Beach Lifestyle" class="w-full h-32 object-cover" />
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-lucide="waves" class="w-5 h-5 text-blue-400"></i>
                        <h3 class="font-medium">Beach Lifestyle</h3>
                    </div>
                    <p class="text-white/60 text-sm mb-4">Sun, surf, and coastal walks</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-white/10 rounded-full text-sm">Morning swims</span>
                        <span class="px-3 py-1 bg-white/10 rounded-full text-sm">Beach cafes</span>
                        <span class="px-3 py-1 bg-white/10 rounded-full text-sm">Outdoor activities</span>
                    </div>
                </div>
            </button>

            <!-- Urban Explorer -->
            <button onclick="selectLifestyle('urban')"
                class="w-full text-left glass-card rounded-xl overflow-hidden hover:bg-white/20 transition-all transform hover:scale-102">
                <img src="/api/placeholder/400/150" alt="Urban Explorer" class="w-full h-32 object-cover" />
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-lucide="building-2" class="w-5 h-5 text-purple-400"></i>
                        <h3 class="font-medium">Urban Explorer</h3>
                    </div>
                    <p class="text-white/60 text-sm mb-4">City vibes and cultural experiences</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-white/10 rounded-full text-sm">Art galleries</span>
                        <span class="px-3 py-1 bg-white/10 rounded-full text-sm">Hidden bars</span>
                        <span class="px-3 py-1 bg-white/10 rounded-full text-sm">Food scene</span>
                    </div>
                </div>
            </button>

            <!-- Local Living -->
            <button onclick="selectLifestyle('suburban')"
                class="w-full text-left glass-card rounded-xl overflow-hidden hover:bg-white/20 transition-all transform hover:scale-102">
                <img src="/api/placeholder/400/150" alt="Local Living" class="w-full h-32 object-cover" />
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-lucide="home" class="w-5 h-5 text-green-400"></i>
                        <h3 class="font-medium">Local Living</h3>
                    </div>
                    <p class="text-white/60 text-sm mb-4">Quiet streets and community feel</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-white/10 rounded-full text-sm">Parks & markets</span>
                        <span class="px-3 py-1 bg-white/10 rounded-full text-sm">Cafe culture</span>
                        <span class="px-3 py-1 bg-white/10 rounded-full text-sm">Local community</span>
                    </div>
                </div>
            </button>
        </div>

        <!-- Progress Indicator -->
        <div class="mt-8 flex justify-center gap-2">
            <div class="w-2 h-2 rounded-full bg-white/30"></div>
            <div class="w-2 h-2 rounded-full bg-white/30"></div>
            <div class="w-2 h-2 rounded-full bg-white/30"></div>
            <div class="w-2 h-2 rounded-full bg-white"></div>
        </div>
    </div>
</div>

<style>
    .glass-card {
        backdrop-filter: blur(12px);
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    @keyframes scaleIn {
        from {
            transform: scale(0.95);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .animate-scale-in {
        animation: scaleIn 0.3s ease-out forwards;
    }
</style>

<script>
    // Initialize Lucide icons
    lucide.createIcons();

    function selectLifestyle(lifestyle) {
        localStorage.setItem('lifestyle', lifestyle);
        window.location.href = '?page=recommendations';
    }
</script>