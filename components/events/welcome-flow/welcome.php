<?php
// components/events/welcome-flow/welcome.php
?>
<div class="relative min-h-screen flex flex-col items-center justify-center p-6 space-y-8">
    <!-- Header Section -->
    <div class="text-center space-y-4 max-w-xl">
        <div class="flex justify-center mb-6">
            <i data-lucide="compass" class="w-16 h-16 text-blue-400 animate-float"></i>
        </div>
        <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
            Discover Sydney Like a Local
        </h1>
        <p class="text-xl text-white/80">Curated events and experiences for newcomers to Sydney</p>
    </div>

    <!-- Quick Start Options -->
    <div class="w-full max-w-md space-y-4">
        <div class="glass-card rounded-2xl p-6">
            <h3 class="text-lg font-medium mb-6">Tell us about your visit</h3>
            <div class="space-y-4">
                <button onclick="setVisitorType('planning')"
                    class="w-full p-4 bg-white/5 rounded-xl hover:bg-white/10 transition-all group flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i data-lucide="plane" class="w-6 h-6 text-blue-400"></i>
                        <div class="text-left">
                            <span class="block font-medium">Planning my trip</span>
                            <span class="text-sm text-white/60">I haven't arrived in Sydney yet</span>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-5 h-5 text-white/60 group-hover:translate-x-1 transition-transform"></i>
                </button>

                <button onclick="setVisitorType('here')"
                    class="w-full p-4 bg-white/5 rounded-xl hover:bg-white/10 transition-all group flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i data-lucide="map-pin" class="w-6 h-6 text-pink-400"></i>
                        <div class="text-left">
                            <span class="block font-medium">I'm already here</span>
                            <span class="text-sm text-white/60">Show me what's happening now</span>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-5 h-5 text-white/60 group-hover:translate-x-1 transition-transform"></i>
                </button>

                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/10"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-[#0F172A] text-white/60">or</span>
                    </div>
                </div>

                <button onclick="quickEvents()"
                    class="w-full p-4 bg-white/5 rounded-xl hover:bg-white/10 transition-all group flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i data-lucide="zap" class="w-6 h-6 text-yellow-400"></i>
                        <div class="text-left">
                            <span class="block font-medium">Quick Events</span>
                            <span class="text-sm text-white/60">Skip personalization, show all events</span>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-5 h-5 text-white/60 group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>
        </div>

        <!-- How it works -->
        <div class="glass-card rounded-xl p-4">
            <div class="flex items-center gap-2 text-sm text-white/60">
                <i data-lucide="info" class="w-4 h-4"></i>
                <span>Personalized recommendations in under 2 minutes</span>
            </div>
        </div>
    </div>
</div>

<script>
    function setVisitorType(type) {
        localStorage.setItem('visitor-type', type);
        window.location.href = 'index.php?page=duration';
    }

    function quickEvents() {
        window.location.href = 'index.php?page=events';
    }
</script>