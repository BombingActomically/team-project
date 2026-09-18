<!-- C:\xampp\htdocs\Project2\frontend\components\categories.php -->
<?php
// C:\xampp\htdocs\Project2\frontend\components\categories.php
// Requires $categories array from data/events.php
?>
<section id="categories-section" class="relative py-20 overflow-hidden">
    
    <!-- Glow effects -->
    <div class="absolute top-[30%] right-[5%] w-72 h-72 bg-brandPrimary/5 rounded-full blur-[90px] pointer-events-none"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Section Header -->
        <div class="text-center mb-16">
            <span class="px-3 py-1 rounded-full bg-brandPrimary/15 border border-brandPrimary/30 text-xs font-semibold text-brandPrimary uppercase tracking-wider">
                Explore
            </span>
            <h2 class="text-3xl sm:text-4xl font-bold font-outfit mt-3 text-white tracking-tight">
                Browse by Category
            </h2>
            <p class="text-brandMuted mt-2 text-sm max-w-xl mx-auto">
                Find fests, matches, sprints, and masterclasses across diverse fields of interest.
            </p>
        </div>
        
        <!-- Grid of Category Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($categories as $cat): ?>
                <div 
                    onclick="filterByCategory('<?php echo htmlspecialchars($cat['name']); ?>')"
                    class="glass-card card-border-glow rounded-2xl p-6 border border-brandBorder relative group overflow-hidden cursor-pointer hover:border-brandSecondary/40"
                >
                    <!-- Hover gradient overlay -->
                    <div class="absolute inset-0 bg-gradient-to-br from-transparent via-transparent to-brandPrimary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    
                    <div class="space-y-4 relative z-10">
                        <!-- Emoji & Icon Banner -->
                        <div class="flex items-center justify-between">
                            <span class="text-3xl filter drop-shadow-[0_4px_8px_rgba(16,185,129,0.3)]">
                                <?php echo $cat['emoji']; ?>
                            </span>
                            <div class="p-2 rounded-lg bg-brandBg/80 border border-brandBorder text-brandMuted group-hover:text-brandSecondary group-hover:border-brandSecondary/20 transition-all duration-300">
                                <i data-lucide="<?php echo $cat['icon']; ?>" class="w-4 h-4"></i>
                            </div>
                        </div>
                        
                        <!-- Details -->
                        <div class="space-y-2">
                            <h3 class="text-lg font-bold font-outfit text-white group-hover:text-brandSecondary transition-colors duration-300">
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </h3>
                            <p class="text-xs text-brandMuted leading-relaxed line-clamp-2">
                                <?php echo htmlspecialchars($cat['description']); ?>
                            </p>
                        </div>
                        
                        <!-- Info Footer -->
                        <div class="flex items-center justify-between pt-4 mt-2 border-t border-brandBorder/40">
                            <span class="text-[10px] uppercase font-bold tracking-widest text-brandSecondary">
                                View Events
                            </span>
                            <span class="text-xs bg-brandBgSec/85 border border-brandBorder px-2.5 py-1 rounded-full text-brandText font-semibold font-outfit shadow-sm">
                                <?php echo htmlspecialchars($cat['count']); ?> Events
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
    </div>
</section>
