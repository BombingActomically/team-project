<!-- C:\xampp\htdocs\Project2\frontend\components\testimonials.php -->
<section id="testimonials-section" class="relative py-20 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Section Header -->
        <div class="text-center mb-16">
            <span class="px-3 py-1 rounded-full bg-brandPrimary/15 border border-brandPrimary/30 text-xs font-semibold text-brandPrimary uppercase tracking-wider">
                Reviews
            </span>
            <h2 class="text-3xl sm:text-4xl font-bold font-outfit mt-3 text-white tracking-tight">
                What Students Say
            </h2>
            <p class="text-brandMuted mt-2 text-sm max-w-xl mx-auto">
                Read feedback from active members who discovered hackathons, won prizes, and expanded networks.
            </p>
        </div>
        
        <!-- Testimonials Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Testimonial 1 -->
            <div class="glass-card rounded-2xl p-6 border border-brandBorder relative flex flex-col justify-between group">
                <!-- Quote mark absolute icon -->
                <i data-lucide="quote" class="w-8 h-8 text-brandPrimary/10 absolute right-6 top-6 group-hover:text-brandPrimary/20 transition-colors"></i>
                
                <div class="space-y-4">
                    <div class="flex items-center space-x-1 text-brandSecondary">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <?php endfor; ?>
                    </div>
                    <p class="text-sm text-brandMuted leading-relaxed italic">
                        "Finding inter-college events used to be difficult. EVENTRA makes everything available in one place. I registered for CodeStorm and our team won the second runner-up prize!"
                    </p>
                </div>
                
                <div class="flex items-center space-x-4 pt-6 mt-6 border-t border-brandBorder/60">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-brandPrimary to-brandHighlight flex items-center justify-center text-white font-bold font-outfit text-sm">
                        AR
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white">Aravind Rao</h4>
                        <p class="text-[11px] text-brandSecondary font-medium">ABC Institute of Technology</p>
                    </div>
                </div>
            </div>
            
            <!-- Testimonial 2 -->
            <div class="glass-card rounded-2xl p-6 border border-brandBorder relative flex flex-col justify-between group">
                <i data-lucide="quote" class="w-8 h-8 text-brandPrimary/10 absolute right-6 top-6 group-hover:text-brandPrimary/20 transition-colors"></i>
                
                <div class="space-y-4">
                    <div class="flex items-center space-x-1 text-brandSecondary">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <?php endfor; ?>
                    </div>
                    <p class="text-sm text-brandMuted leading-relaxed italic">
                        "The interface is gorgeous and extremely quick. We filtered by 'Workshops' and attended the AI Seminar online last weekend. Absolutely flawless registration process!"
                    </p>
                </div>
                
                <div class="flex items-center space-x-4 pt-6 mt-6 border-t border-brandBorder/60">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-brandSecondary to-brandHighlight flex items-center justify-center text-white font-bold font-outfit text-sm">
                        SP
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white">Sneha Patel</h4>
                        <p class="text-[11px] text-brandSecondary font-medium">XYZ University</p>
                    </div>
                </div>
            </div>
            
            <!-- Testimonial 3 -->
            <div class="glass-card rounded-2xl p-6 border border-brandBorder relative flex flex-col justify-between group">
                <i data-lucide="quote" class="w-8 h-8 text-brandPrimary/10 absolute right-6 top-6 group-hover:text-brandPrimary/20 transition-colors"></i>
                
                <div class="space-y-4">
                    <div class="flex items-center space-x-1 text-brandSecondary">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <?php endfor; ?>
                    </div>
                    <p class="text-sm text-brandMuted leading-relaxed italic">
                        "For sports and fests, EVENTRA is a game-changer. Our college cricket captain was able to list our participation in the Cricket Cup in less than five minutes. Fantastic platform."
                    </p>
                </div>
                
                <div class="flex items-center space-x-4 pt-6 mt-6 border-t border-brandBorder/60">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-brandPrimary to-brandSecondary flex items-center justify-center text-white font-bold font-outfit text-sm">
                        DK
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white">Dev Kumar</h4>
                        <p class="text-[11px] text-brandSecondary font-medium">National College</p>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
</section>
