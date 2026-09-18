<!-- C:\xampp\htdocs\Project2\frontend\components\how-it-works.php -->
<section class="relative py-24 overflow-hidden border-t border-[var(--border-soft)]">
    
    <!-- Subtle Grid Background -->
    <div class="absolute inset-0 z-[-1] pointer-events-none" 
         style="background-image: linear-gradient(to right, rgba(75,79,134,0.06) 1px, transparent 1px), linear-gradient(to bottom, rgba(75,79,134,0.06) 1px, transparent 1px); background-size: 50px 50px;">
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Header -->
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="eb-eyebrow mb-4">Workflow</span>
            <h2 class="text-3xl sm:text-4xl font-bold font-outfit text-[var(--text)] tracking-tight">How It Works</h2>
            <p class="text-sm text-[var(--text-dim)] mt-3">Connect and excel in three simple phases. Join the EVENTRA network today.</p>
        </div>

        <!-- Cards Container -->
        <div class="relative">
            
            <!-- Connecting Dashed Line (Hidden on Mobile) -->
            <div class="hidden md:block absolute top-1/2 left-[10%] right-[10%] h-px border-t-2 border-dashed border-[var(--border-accent)] opacity-30 z-0 -translate-y-1/2"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Step 01 -->
                <div class="bg-[var(--surface)] border border-[var(--border-soft)] rounded-2xl p-10 text-center relative z-10 shadow-sm hover:-translate-y-1 transition-transform duration-300">
                    <!-- Faint Watermark Number -->
                    <div class="absolute top-6 right-6 text-5xl font-black font-outfit text-[var(--text)] opacity-10 select-none pointer-events-none">01</div>
                    
                    <!-- Centered Icon -->
                    <div class="w-16 h-16 mx-auto bg-[var(--bg-alt)] border border-[var(--border-soft)] rounded-2xl flex items-center justify-center mb-6 text-[var(--text)] shadow-inner">
                        <i data-lucide="compass" class="w-8 h-8"></i>
                    </div>
                    
                    <h3 class="text-xl font-bold font-outfit text-[var(--text)] mb-3">Discover</h3>
                    <p class="text-sm text-[var(--text-dim)] leading-relaxed">
                        Browse through hundreds of events, fests, hackathons, and seminars hosted by top-tier colleges near you or online.
                    </p>
                </div>

                <!-- Step 02 -->
                <div class="bg-[var(--surface)] border border-[var(--border-soft)] rounded-2xl p-10 text-center relative z-10 shadow-sm hover:-translate-y-1 transition-transform duration-300">
                    <!-- Faint Watermark Number -->
                    <div class="absolute top-6 right-6 text-5xl font-black font-outfit text-[var(--text)] opacity-10 select-none pointer-events-none">02</div>
                    
                    <!-- Centered Icon -->
                    <div class="w-16 h-16 mx-auto bg-[var(--bg-alt)] border border-[var(--border-soft)] rounded-2xl flex items-center justify-center mb-6 text-[var(--text)] shadow-inner">
                        <i data-lucide="pen-tool" class="w-8 h-8"></i>
                    </div>
                    
                    <h3 class="text-xl font-bold font-outfit text-[var(--text)] mb-3">Register</h3>
                    <p class="text-sm text-[var(--text-dim)] leading-relaxed">
                        Pick your preferred competition, fill out the simple online enrollment form, and claim your entry pass instantly.
                    </p>
                </div>

                <!-- Step 03 -->
                <div class="bg-[var(--surface)] border border-[var(--border-soft)] rounded-2xl p-10 text-center relative z-10 shadow-sm hover:-translate-y-1 transition-transform duration-300">
                    <!-- Faint Watermark Number -->
                    <div class="absolute top-6 right-6 text-5xl font-black font-outfit text-[var(--text)] opacity-10 select-none pointer-events-none">03</div>
                    
                    <!-- Centered Icon -->
                    <div class="w-16 h-16 mx-auto bg-[var(--bg-alt)] border border-[var(--border-soft)] rounded-2xl flex items-center justify-center mb-6 text-[var(--text)] shadow-inner">
                        <i data-lucide="award" class="w-8 h-8"></i>
                    </div>
                    
                    <h3 class="text-xl font-bold font-outfit text-[var(--text)] mb-3">Participate</h3>
                    <p class="text-sm text-[var(--text-dim)] leading-relaxed">
                        Attend on event day, compete with peers, learn from workshops, and network with students across the state.
                    </p>
                </div>

            </div>
        </div>
        
    </div>
</section>