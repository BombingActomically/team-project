<!-- C:\xampp\htdocs\Project2\frontend\components\hero.php -->
<section id="home" class="relative pt-32 pb-20 md:pt-40 md:pb-28 overflow-hidden">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            <!-- Left Column: Content -->
            <div class="lg:col-span-7 text-center lg:text-left space-y-8">
                <!-- Mini Tagline -->
                <div class="eb-eyebrow mx-auto lg:mx-0">
                    <span class="dot"></span>
                    <span>Inter-College Networking Hub</span>
                </div>

                <!-- Main Headings -->
                <div class="space-y-4">
                    <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight font-outfit leading-[1.1]" style="color: var(--text);">
                        Discover. <br class="hidden sm:inline">
                        <span style="color: var(--accent);">Connect. Compete.</span>
                    </h1>
                    <p class="text-lg max-w-xl mx-auto lg:mx-0 font-normal leading-relaxed" style="color: var(--text-dim);">
                        Explore college events, competitions, workshops, fests, and activities happening across multiple colleges — all in one place.
                    </p>
                </div>

                <!-- CTA Actions -->
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                    <a href="events.php" class="eb-btn-primary w-full sm:w-auto">
                        Explore Events
                    </a>
                    <a href="colleges.php" class="eb-btn-secondary w-full sm:w-auto">
                        View Colleges
                    </a>
                </div>

                <!-- Quick Stats Grid (Dynamic from Database) -->
                <div class="pt-8" style="border-top: 1px solid var(--border-soft);">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
                        <div>
                            <div class="eb-stat-value font-outfit"><?php echo number_format($totalColleges ?? 0); ?>+</div>
                            <div class="eb-stat-label">Partner Colleges</div>
                        </div>
                        <div>
                            <div class="eb-stat-value font-outfit"><?php echo number_format($totalEvents ?? 0); ?>+</div>
                            <div class="eb-stat-label">Active Events</div>
                        </div>
                        <div>
                            <div class="eb-stat-value font-outfit"><?php echo number_format($totalStudents ?? 0); ?>+</div>
                            <div class="eb-stat-label">Registered Students</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Visual -->
            <div class="lg:col-span-5 flex justify-center items-center relative">

                <!-- Floating Glass Container -->
                <div class="eb-glass eb-float relative p-4 rounded-3xl max-w-sm sm:max-w-md w-full shadow-2xl">
                    <div class="relative overflow-hidden rounded-2xl aspect-square flex items-center justify-center group" style="background-color: var(--text); border: 1px solid var(--border-soft);">
                        
                        <?php 
                            // Determine image based on the hero event's category
                            $heroImage = 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=800&q=80'; // Default
                            
                            $heroCategory = null;
                            if (isset($heroEvent['category_id'])) {
                                $stmt = $pdo->prepare("SELECT name FROM categories WHERE category_id = ?");
                                $stmt->execute([$heroEvent['category_id']]);
                                $heroCategory = $stmt->fetchColumn();
                            } elseif (isset($megaCategory)) {
                                $heroCategory = $megaCategory;
                            }

                            if ($heroCategory) {
                                 $imgMap = [
                                    'Technical Events'     => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=800&q=80',
                                    'Cultural Events'      => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=800&q=80',
                                    'Sports Events'        => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&w=800&q=80',
                                    'Management Events'    => 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=800&q=80',
                                    'E-Sports & Gaming'    => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=800&q=80',
                                    'Art & Design'         => 'https://images.unsplash.com/photo-1460661419201-fd4cecdf8a8b?auto=format&fit=crop&w=800&q=80',
                                    'Literary Events'      => 'https://images.unsplash.com/photo-1474366521946-c3d4b507abf2?auto=format&fit=crop&w=800&q=80',
                                    'Workshops & Seminars' => 'https://images.unsplash.com/photo-1544928147-79a2dbc1f389?auto=format&fit=crop&w=800&q=80',
                                    'Social & Fun Events'  => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=800&q=80'
                                ];
                                $heroImage = $imgMap[$heroCategory] ?? $heroImage;
                            }
                        ?>

                        <!-- Dynamic Hero Image -->
                        <img src="<?php echo $heroImage; ?>" alt="Hero Featured Event" class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-1000 ease-in-out">

                        <!-- Sleek Dark Gradient Overlay for Legibility -->
                        <div class="absolute inset-0 bg-gradient-to-t from-[var(--text)] via-[#282B4A]/40 to-transparent pointer-events-none"></div>

                        <!-- Subtle Polka-Dot Pattern Overlay -->
                        <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(var(--surface) 2px, transparent 2px); background-size: 20px 20px;"></div>

                        <!-- Dynamic Event Countdown -->
                        <div class="absolute bottom-4 left-4 right-4 p-5 rounded-2xl backdrop-blur-md shadow-xl" style="background-color: rgba(40, 43, 74, 0.85); border: 1px solid rgba(255,255,255,0.1);">
                            <div class="mb-3">
                                <!-- EXPLICITLY SET TO WHITE -->
                                <h4 class="text-sm font-bold font-outfit truncate" style="color: #FFFFFF;">
                                    <?php echo htmlspecialchars($megaTitle ?? $heroEventTitle ?? 'Next Major Event'); ?>
                                </h4>
                                <!-- EXPLICITLY SET TO CREAM -->
                                <p class="text-[10px] font-bold uppercase tracking-wider mt-1" style="color: #E4DFC8;">Starts In</p>
                            </div>
                            
                            <!-- Timer Flexbox -->
                            <div id="hero-countdown" class="flex items-center justify-between text-center gap-2">
                                <div class="flex flex-col bg-white px-2 py-1.5 rounded-lg shadow-sm flex-1">
                                    <span id="h-days" class="font-outfit font-black text-xl leading-none" style="color: var(--text);">--</span>
                                    <span class="text-[9px] uppercase font-bold tracking-wider mt-0.5" style="color: var(--accent);">Days</span>
                                </div>
                                <!-- EXPLICITLY SET TO WHITE -->
                                <span class="font-bold pb-3" style="color: #FFFFFF;">:</span>
                                <div class="flex flex-col bg-white px-2 py-1.5 rounded-lg shadow-sm flex-1">
                                    <span id="h-hours" class="font-outfit font-black text-xl leading-none" style="color: var(--text);">--</span>
                                    <span class="text-[9px] uppercase font-bold tracking-wider mt-0.5" style="color: var(--accent);">Hrs</span>
                                </div>
                                <!-- EXPLICITLY SET TO WHITE -->
                                <span class="font-bold pb-3" style="color: #FFFFFF;">:</span>
                                <div class="flex flex-col bg-white px-2 py-1.5 rounded-lg shadow-sm flex-1">
                                    <span id="h-mins" class="font-outfit font-black text-xl leading-none" style="color: var(--text);">--</span>
                                    <span class="text-[9px] uppercase font-bold tracking-wider mt-0.5" style="color: var(--accent);">Min</span>
                                </div>
                                <!-- EXPLICITLY SET TO WHITE -->
                                <span class="font-bold pb-3" style="color: #FFFFFF;">:</span>
                                <div class="flex flex-col bg-white px-2 py-1.5 rounded-lg shadow-sm flex-1">
                                    <span id="h-secs" class="font-outfit font-black text-xl leading-none" style="color: var(--text);">--</span>
                                    <span class="text-[9px] uppercase font-bold tracking-wider mt-0.5" style="color: var(--accent);">Sec</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // Look up the target date generated by index.php
    let targetDateString = window.nextHeroEvent ? window.nextHeroEvent.datetime : "<?php echo $megaCountdownTarget ?? $countdownDateTime ?? date('Y-m-d\T10:00:00', strtotime('+10 days')); ?>";
    
    // Convert to JS Date object
    const targetDate = new Date(targetDateString).getTime();

    // DOM Elements
    const elDays = document.getElementById("h-days");
    const elHours = document.getElementById("h-hours");
    const elMins = document.getElementById("h-mins");
    const elSecs = document.getElementById("h-secs");

    if (!elDays || isNaN(targetDate)) return;

    function updateHeroCountdown() {
        const now = new Date().getTime();
        const difference = targetDate - now;

        if (difference <= 0) {
            elDays.innerText = "00";
            elHours.innerText = "00";
            elMins.innerText = "00";
            elSecs.innerText = "00";
            return;
        }

        const days = Math.floor(difference / (1000 * 60 * 60 * 24));
        const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((difference % (1000 * 60)) / 1000);

        elDays.innerText = days.toString().padStart(2, '0');
        elHours.innerText = hours.toString().padStart(2, '0');
        elMins.innerText = minutes.toString().padStart(2, '0');
        elSecs.innerText = seconds.toString().padStart(2, '0');
    }

    // Run immediately, then every second
    updateHeroCountdown();
    setInterval(updateHeroCountdown, 1000);
});
</script>   