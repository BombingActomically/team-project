<!-- C:\xampp\htdocs\Project2\frontend\components\calendar.php -->
<section id="calendar-section" class="relative py-20 overflow-hidden bg-brandBgSec/10 border-t border-brandBorder/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Section Header -->
        <div class="text-center mb-16">
            <span class="px-3 py-1 rounded-full bg-brandSecondary/15 border border-brandSecondary/30 text-xs font-semibold text-brandSecondary uppercase tracking-wider">
                Schedule
            </span>
            <h2 class="text-3xl sm:text-4xl font-bold font-outfit mt-3 text-white tracking-tight">
                Event Calendar
            </h2>
            <p class="text-brandMuted mt-2 text-sm max-w-xl mx-auto">
                Select highlighted dates to explore schedule plans and coordinate team attendance.
            </p>
        </div>
        
        <!-- Calendar Container Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left: The Interactive Calendar Grid -->
            <div class="lg:col-span-7 glass-panel card-border-glow rounded-3xl p-6 border border-brandBorder relative overflow-hidden">
                <!-- Glowing borders -->
                <div class="absolute -left-20 -top-20 w-48 h-48 bg-brandPrimary/10 rounded-full blur-3xl pointer-events-none"></div>
                
                <!-- Calendar Controls Header -->
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-brandBorder/60">
                    <h3 class="text-xl font-bold font-outfit text-white flex items-center gap-2">
                        <i data-lucide="calendar" class="text-brandSecondary"></i>
                        <span id="calendar-month-year">October 2026</span>
                    </h3>
                    <div class="flex items-center space-x-2">
                        <button onclick="prevMonth()" class="p-2 rounded-xl bg-brandCard border border-brandBorder text-brandMuted hover:text-white hover:border-brandSecondary/30 transition-all">
                            <i data-lucide="chevron-left" class="w-5 h-5"></i>
                        </button>
                        <button onclick="nextMonth()" class="p-2 rounded-xl bg-brandCard border border-brandBorder text-brandMuted hover:text-white hover:border-brandSecondary/30 transition-all">
                            <i data-lucide="chevron-right" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Weekdays Header -->
                <div class="grid grid-cols-7 gap-2 text-center text-xs font-bold text-brandMuted uppercase tracking-wider mb-2">
                    <div>Sun</div>
                    <div>Mon</div>
                    <div>Tue</div>
                    <div>Wed</div>
                    <div>Thu</div>
                    <div>Fri</div>
                    <div>Sat</div>
                </div>
                
                <!-- Day Cells Grid -->
                <div id="calendar-days-grid" class="grid grid-cols-7 gap-2 text-center text-sm font-medium">
                    <!-- Days will be dynamically loaded by assets/js/main.js -->
                </div>
            </div>
            
            <!-- Right: Event List for Selected Date -->
            <div class="lg:col-span-5 h-full flex flex-col">
                <div class="glass-panel card-border-glow rounded-3xl p-6 border border-brandBorder flex-grow space-y-4">
                    <h3 class="text-lg font-bold font-outfit text-white flex items-center justify-between pb-3 border-b border-brandBorder/60">
                        <span>Scheduled Events</span>
                        <span id="calendar-selected-date" class="text-xs px-3 py-1 bg-brandSecondary/10 text-brandSecondary rounded-full font-semibold">Oct 15, 2026</span>
                    </h3>
                    
                    <div id="calendar-events-container" class="space-y-4 max-h-[350px] overflow-y-auto pr-1">
                        <!-- Events happening on the selected day will load here -->
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
</section>
