<!-- C:\xampp\htdocs\Project2\frontend\components\footer.php -->
<footer id="contact-section" class="relative bg-brandBg/95 pt-20 pb-10 border-t border-brandBorder overflow-hidden">
    <!-- Accent background glows -->
    <div class="absolute bottom-0 left-[10%] w-80 h-80 bg-brandBgSec/15 rounded-full blur-[90px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-[10%] w-80 h-80 bg-brandPrimary/5 rounded-full blur-[90px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-12 pb-16 border-b border-brandBorder/60">
            
            <!-- Column 1: Info -->
            <div class="md:col-span-4 space-y-6">
                <a href="index.php" class="flex items-center space-x-2">
                    <span class="p-2 rounded-lg bg-gradient-to-tr from-brandBgSec to-brandPrimary flex items-center justify-center border border-brandBorder">
                        <i data-lucide="sparkles" class="w-5 h-5 text-brandSecondary"></i>
                    </span>
                    <span class="text-2xl font-bold tracking-wider font-outfit text-brandSecondary">
                        EVENTURA
                    </span>
                </a>
                <p class="text-sm text-brandMuted leading-relaxed max-w-sm">
                    Connecting campuses through interactive events, hackathons, and technical symposia. Discover fests, register in seconds, and compete nationally.
                </p>
                <div class="flex items-center space-x-3">
                    <a href="#" class="p-2 rounded-lg bg-brandBg border border-brandBorder text-brandMuted hover:text-brandSecondary hover:border-brandSecondary transition-all" aria-label="Twitter">
                        <i data-lucide="twitter" class="w-4 h-4"></i>
                    </a>
                    <a href="#" class="p-2 rounded-lg bg-brandBg border border-brandBorder text-brandMuted hover:text-brandSecondary hover:border-brandSecondary transition-all" aria-label="GitHub">
                        <i data-lucide="github" class="w-4 h-4"></i>
                    </a>
                    <a href="#" class="p-2 rounded-lg bg-brandBg border border-brandBorder text-brandMuted hover:text-brandSecondary hover:border-brandSecondary transition-all" aria-label="LinkedIn">
                        <i data-lucide="linkedin" class="w-4 h-4"></i>
                    </a>
                    <a href="#" class="p-2 rounded-lg bg-brandBg border border-brandBorder text-brandMuted hover:text-brandSecondary hover:border-brandSecondary transition-all" aria-label="Discord">
                        <i data-lucide="message-square" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
            
            <!-- Column 2: Quick Links -->
            <div class="md:col-span-2 space-y-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-brandSecondary font-outfit">Platform</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="index.php" class="text-brandMuted hover:text-brandSecondary transition-colors">Home</a></li>
                    <li><a href="events.php" class="text-brandMuted hover:text-brandSecondary transition-colors">Events Portal</a></li>
                    <li><a href="colleges.php" class="text-brandMuted hover:text-brandSecondary transition-colors">Host Colleges</a></li>
                    <li><a href="about.php" class="text-brandMuted hover:text-brandSecondary transition-colors">About Us</a></li>
                    <li><a href="contact.php" class="text-brandMuted hover:text-brandSecondary transition-colors">Contact Support</a></li>
                </ul>
            </div>
            
            <!-- Column 3: Contact Info -->
            <div class="md:col-span-3 space-y-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-brandSecondary font-outfit">Contact Info</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start space-x-2.5 text-brandMuted">
                        <i data-lucide="map-pin" class="w-4 h-4 text-brandPrimary mt-0.5 flex-shrink-0"></i>
                        <span>EVENTURA Headquarters, Ring Road, Surat 395007</span>
                    </li>
                    <li class="flex items-center space-x-2.5 text-brandMuted">
                        <i data-lucide="mail" class="w-4 h-4 text-brandPrimary flex-shrink-0"></i>
                        <span>support@eventura.edu</span>
                    </li>
                    <li class="flex items-center space-x-2.5 text-brandMuted">
                        <i data-lucide="phone" class="w-4 h-4 text-brandPrimary flex-shrink-0"></i>
                        <span>+91 261 5558 9201</span>
                    </li>
                </ul>
            </div>
            
            <!-- Column 4: Newsletter -->
            <div class="md:col-span-3 space-y-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-brandSecondary font-outfit">Stay Updated</h4>
                <p class="text-xs text-brandMuted leading-relaxed">
                    Subscribe to our weekly newsletter to get instant alerts on new college hackathons and fests.
                </p>
                <form onsubmit="handleNewsletterSubmit(event)" class="space-y-2">
                    <div class="relative">
                        <input 
                            type="email" 
                            required 
                            placeholder="Enter your email" 
                            class="w-full bg-brandBg border border-brandBorder rounded-lg pl-3 pr-10 py-2.5 text-xs text-brandText placeholder-brandMuted focus:outline-none focus:border-brandPrimary"
                        >
                        <button type="submit" class="absolute right-1.5 top-1.5 p-1.5 rounded-md bg-brandSecondary text-brandBgSec hover:opacity-90 transition-opacity">
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
        
        <!-- Copyright Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between pt-8 text-xs text-brandMuted">
            <p>&copy; <?php echo date('Y'); ?> EVENTURA. All rights reserved. Designed for Multi-College Event Discovery.</p>
            <div class="flex items-center space-x-6 mt-4 sm:mt-0">
                <a href="#" class="hover:text-brandSecondary transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-brandSecondary transition-colors">Terms of Service</a>
                <a href="#" class="hover:text-brandSecondary transition-colors">Code of Conduct</a>
            </div>
        </div>
    </div>
</footer>

<!-- Unified toast notification for interactions -->
<div id="toast-notification" class="fixed bottom-6 right-6 z-[110] hidden items-center space-x-3 bg-brandBgSec border border-brandBorder p-4 rounded-2xl shadow-[0_10px_35px_rgba(91,136,178,0.2)] backdrop-blur-md max-w-sm animate-float">
    <div class="p-2 rounded-lg bg-brandPrimary/15 text-brandPrimary" id="toast-icon-wrapper">
        <i data-lucide="check-circle" class="w-5 h-5"></i>
    </div>
    <div>
        <h4 class="text-xs font-bold text-white font-outfit" id="toast-title">Notification</h4>
        <p class="text-[11px] text-brandMuted mt-0.5" id="toast-message"></p>
    </div>
</div>

<script>
    // Initialize Lucide Icons globally
    lucide.createIcons();
</script>
</body>
</html>