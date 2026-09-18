<!-- C:\xampp\htdocs\Project2\frontend\components\featured-event.php -->
<section id="featured-section" class="relative py-20 overflow-hidden"
         style="background: var(--bg); border-top: 1px solid var(--border-soft); border-bottom: 1px solid var(--border-soft);">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <!-- Section Header -->
        <div class="text-center md:text-left mb-12">
            <span class="eb-eyebrow">🔥 Mega Event</span>
            <h2 class="text-3xl sm:text-4xl font-bold font-outfit mt-3 tracking-tight" style="color: var(--text);">
                Featured Highlights
            </h2>
            <p class="mt-2 text-sm max-w-xl" style="color: var(--text-dim);">
                Don't miss the biggest upcoming inter-college championship of the year. Register before slots fill up!
            </p>
        </div>

        <!-- Large Featured Card -->
        <div class="eb-glass rounded-3xl p-6 sm:p-10 overflow-hidden relative" style="box-shadow: 0 20px 50px rgba(0,0,0,0.5);">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">

                <!-- Left: Info Content -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="flex flex-wrap gap-3 items-center">
                        <span class="px-3 py-1 rounded-md text-xs font-bold uppercase tracking-wider"
                              style="background: rgba(75,79,134,0.12); color: var(--accent); border: 1px solid var(--border-accent);">
                            Hackathon
                        </span>
                        <div class="flex items-center text-xs font-medium" style="color: var(--text-dim);">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 mr-1"></i>
                            On-Campus / Mumbai
                        </div>
                    </div>

                    <div class="space-y-3">
                        <h3 class="text-3xl sm:text-5xl font-bold font-outfit leading-tight tracking-tight" style="color: var(--text);">
                            CodeStorm 2026
                        </h3>
                        <p class="font-medium text-sm sm:text-base" style="color: var(--accent);">
                            Organized by ABC Institute of Technology
                        </p>
                    </div>

                    <p class="text-sm sm:text-base font-normal leading-relaxed max-w-2xl" style="color: var(--text-dim);">
                        CodeStorm is the nation's premier 36-hour hackathon, bringing together the most talented student developers, designers, and innovators. Collaborate under one roof, receive mentorship from tech leaders, and build cutting-edge solutions using generative AI, blockchain, and cloud computing.
                    </p>

                    <!-- Stats / Details Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 pt-4" style="border-top: 1px solid var(--border-soft);">
                        <div>
                            <div class="text-[11px] uppercase tracking-wider font-semibold" style="color: var(--text-dim);">Prize Pool</div>
                            <div class="text-xl sm:text-2xl font-bold font-outfit mt-1 flex items-center gap-1.5" style="color: var(--text);">
                                <i data-lucide="trophy" class="w-5 h-5" style="color: var(--accent);"></i>
                                $10,000
                            </div>
                        </div>
                        <div>
                            <div class="text-[11px] uppercase tracking-wider font-semibold" style="color: var(--text-dim);">Registered</div>
                            <div class="text-xl sm:text-2xl font-bold font-outfit mt-1 flex items-center gap-1.5" style="color: var(--text);">
                                <i data-lucide="users" class="w-5 h-5" style="color: var(--accent);"></i>
                                850 / 1000
                            </div>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <div class="text-[11px] uppercase tracking-wider font-semibold" style="color: var(--text-dim);">Event Date</div>
                            <div class="text-sm sm:text-base font-bold mt-2 flex items-center gap-1.5" style="color: var(--text);">
                                <i data-lucide="calendar" class="w-4 h-4" style="color: var(--accent);"></i>
                                Oct 15, 2026
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-wrap items-center gap-4 pt-4">
                        <button onclick="openRegistrationModal('codestorm_2026')" class="eb-btn-primary">
                            <i data-lucide="edit-3" class="w-4 h-4"></i> Register Now
                        </button>
                        <button onclick="openDetailsModal('codestorm_2026')" class="eb-btn-secondary">
                            View Details <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <!-- Right: Countdown Timer Component -->
                <div class="lg:col-span-5 flex flex-col justify-center items-center">
                    <div class="eb-glass w-full rounded-2xl p-6 relative max-w-sm sm:max-w-md mx-auto">
                        <!-- Header banner -->
                        <div class="text-center mb-6">
                            <p class="text-xs font-semibold uppercase tracking-wider" style="color: var(--accent);">Registration Closes In</p>
                        </div>

                        <!-- Grid Numbers -->
                        <div class="grid grid-cols-4 gap-3 text-center" id="featured-countdown" data-target-date="2026-10-14T23:59:59">
                            <!-- Days Card -->
                            <div class="eb-countdown-box">
                                <span id="cd-days" class="text-2xl sm:text-3xl font-extrabold font-outfit leading-none" style="color: var(--text);">00</span>
                                <span class="text-[9px] uppercase tracking-wider font-semibold mt-2" style="color: var(--text-dim);">Days</span>
                            </div>
                            <!-- Hours Card -->
                            <div class="eb-countdown-box">
                                <span id="cd-hours" class="text-2xl sm:text-3xl font-extrabold font-outfit leading-none" style="color: var(--text);">00</span>
                                <span class="text-[9px] uppercase tracking-wider font-semibold mt-2" style="color: var(--text-dim);">Hours</span>
                            </div>
                            <!-- Minutes Card -->
                            <div class="eb-countdown-box">
                                <span id="cd-mins" class="text-2xl sm:text-3xl font-extrabold font-outfit leading-none" style="color: var(--text);">00</span>
                                <span class="text-[9px] uppercase tracking-wider font-semibold mt-2" style="color: var(--text-dim);">Mins</span>
                            </div>
                            <!-- Seconds Card -->
                            <div class="eb-countdown-box">
                                <span id="cd-secs" class="text-2xl sm:text-3xl font-extrabold font-outfit leading-none" style="color: var(--accent);">00</span>
                                <span class="text-[9px] uppercase tracking-wider font-semibold mt-2" style="color: var(--text-dim);">Secs</span>
                            </div>
                        </div>

                        <!-- Tiny banner info -->
                        <div class="mt-6 flex items-center justify-center space-x-2 text-[11px] pt-4"
                             style="color: var(--text-dim); border-top: 1px solid var(--border-soft);">
                            <span class="w-1.5 h-1.5 rounded-full" style="background: var(--accent);"></span>
                            <span>Limited to first 250 team registrations</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>