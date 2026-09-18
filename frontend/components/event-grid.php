<!-- C:\xampp\htdocs\Project2\frontend\components\event-grid.php -->
<section id="events-section" class="relative py-20 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Filters Component Placement -->
        <?php include 'filters.php'; ?>
        
        <!-- Grid Container -->
        <div id="events-grid-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 transition-all duration-300">
            <!-- Skeleton cards shown briefly before rendering -->
            <div class="skeleton-loader hidden col-span-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 w-full">
                <?php for ($i = 0; $i < 6; $i++): ?>
                    <div class="eb-card rounded-xl p-0 overflow-hidden flex flex-col justify-between shadow-sm">
                        <div>
                            <!-- Updated Skeleton Image Box -->
                            <div class="w-full h-48 bg-[#E4DFC8] animate-pulse border-b border-[var(--border-soft)]"></div>
                            <div class="p-6 space-y-4">
                                <div class="w-1/3 h-3 bg-[#E4DFC8] rounded animate-pulse"></div>
                                <div class="w-3/4 h-6 bg-[#E4DFC8] rounded animate-pulse"></div>
                                <div class="space-y-2 pt-2">
                                    <div class="w-full h-3 bg-[#E4DFC8] rounded animate-pulse"></div>
                                    <div class="w-5/6 h-3 bg-[#E4DFC8] rounded animate-pulse"></div>
                                </div>
                                <div class="flex justify-between items-center pt-4 border-t border-[var(--border-soft)] mt-4">
                                    <div class="w-20 h-4 bg-[#E4DFC8] rounded animate-pulse"></div>
                                    <div class="w-20 h-4 bg-[#E4DFC8] rounded animate-pulse"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
            
            <!-- Cards will be dynamically injected by assets/js/main.js -->
        </div>

        <!-- Empty State UI -->
        <div id="empty-state" class="hidden flex-col items-center justify-center py-20 text-center space-y-4 max-w-md mx-auto eb-card rounded-2xl p-8 shadow-sm" style="border-color: var(--border-soft);">
            <div class="p-5 rounded-full mb-2 bg-[var(--bg-alt)] text-[var(--accent)] border border-[var(--border-soft)]">
                <i data-lucide="calendar-x" class="w-12 h-12"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold font-outfit text-[var(--text)]">No Events Found</h3>
                <p class="text-sm mt-2 max-w-xs mx-auto leading-relaxed text-[var(--text-dim)]">
                    We couldn't find any events matching your active filters. Try clearing some selections!
                </p>
            </div>
            <button onclick="clearAllFilters()" class="eb-btn-secondary mt-2 w-full">
                <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i> Reset All Filters
            </button>
        </div>

    </div>
</section>