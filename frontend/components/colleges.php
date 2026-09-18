<!-- C:\xampp\htdocs\Project2\frontend\components\colleges.php -->
<?php
// C:\xampp\htdocs\Project2\frontend\components\colleges.php
// Requires $colleges array from data/events.php
?>
<section id="colleges-section" class="relative py-20 overflow-hidden bg-brandBgSec/25">
    
    <!-- Ambient glow behind grid -->
    <div class="absolute bottom-[10%] left-[5%] w-80 h-80 bg-brandSecondary/5 rounded-full blur-[100px] pointer-events-none"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Section Header -->
        <div class="text-center mb-16">
            <span class="px-3 py-1 rounded-full bg-brandSecondary/15 border border-brandSecondary/30 text-xs font-semibold text-brandSecondary uppercase tracking-wider">
                Network
            </span>
            <h2 class="text-3xl sm:text-4xl font-bold font-outfit mt-3 text-white tracking-tight">
                Participating Colleges
            </h2>
            <p class="text-brandMuted mt-2 text-sm max-w-xl mx-auto">
                Discover leading educational institutions publishing and hosting competitions on our network.
            </p>
        </div>
        
        <!-- Grid list -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($colleges as $col): ?>
                <div class="glass-card card-border-glow rounded-2xl p-6 flex flex-col justify-between border border-brandBorder relative group overflow-hidden">
                    <!-- Subtle background glow on card hover -->
                    <div class="absolute -right-20 -bottom-20 w-44 h-44 bg-brandSecondary/5 rounded-full blur-2xl group-hover:bg-brandSecondary/10 transition-all duration-300 pointer-events-none"></div>
                    
                    <div class="space-y-4">
                        <!-- Logo Placeholder -->
                        <div class="w-12 h-12 rounded-xl bg-brandBgSec/80 border border-brandBorder/80 flex items-center justify-center text-brandSecondary group-hover:text-brandPrimary group-hover:border-brandPrimary/30 transition-all duration-300 shadow-inner">
                            <i data-lucide="<?php echo $col['logo']; ?>" class="w-6 h-6"></i>
                        </div>
                        
                        <!-- Name & Location -->
                        <div class="space-y-1">
                            <h3 class="text-lg font-bold font-outfit text-white group-hover:text-brandSecondary transition-colors duration-300">
                                <?php echo htmlspecialchars($col['name']); ?>
                            </h3>
                            <p class="text-xs text-brandMuted flex items-center">
                                <i data-lucide="map-pin" class="w-3 h-3 mr-1 text-brandSecondary/80"></i>
                                <?php echo htmlspecialchars($col['city']); ?>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Footer: Event stats & action -->
                    <div class="flex items-center justify-between pt-6 mt-6 border-t border-brandBorder/60">
                        <span class="text-xs text-brandMuted font-medium">
                            <strong class="text-white font-bold"><?php echo htmlspecialchars($col['events_count']); ?></strong> Active Events
                        </span>
                        
                        <button 
                            onclick="filterByCollege('<?php echo htmlspecialchars($col['id']); ?>')"
                            class="px-4 py-2 rounded-lg bg-brandBg/80 border border-brandBorder text-xs font-semibold text-brandMuted group-hover:text-white group-hover:border-brandSecondary/40 group-hover:bg-brandSecondary/10 transition-all duration-300 flex items-center gap-1"
                        >
                            View Events <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
    </div>
</section>
