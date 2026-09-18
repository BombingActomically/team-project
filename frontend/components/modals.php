<!-- C:\xampp\htdocs\Project2\frontend\components\modals.php -->

<!-- Unified Event Detail Modal -->
<div id="details-modal" class="fixed inset-0 z-[100] hidden items-center justify-center px-4 overflow-y-auto">
    <div class="absolute inset-0 bg-[#282B4A]/60 backdrop-blur-sm transition-opacity duration-300" onclick="closeDetailsModal()"></div>
    <div class="relative w-full max-w-3xl eb-card rounded-3xl p-6 sm:p-8 shadow-[0_20px_60px_rgba(0,0,0,0.5)] overflow-hidden scale-95 transition-all duration-300 opacity-0 transform" id="details-modal-box" style="background-color: var(--surface); border: 1px solid var(--border-soft);">
        <button onclick="closeDetailsModal()" class="absolute top-5 right-5 p-2 rounded-full transition-colors duration-200 z-10" style="background-color: var(--bg-alt); color: var(--text);">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
        
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 relative items-center">
            <!-- Dynamic Image Banner Section -->
            <div id="detail-banner-container" class="md:col-span-5 h-64 md:h-full min-h-[240px] rounded-2xl overflow-hidden relative flex items-center justify-center bg-[var(--text)]">
                <img id="detail-modal-img" src="" alt="Event Image" class="absolute inset-0 w-full h-full object-cover opacity-90">
                <div class="absolute inset-0 bg-gradient-to-t from-[var(--text)]/80 to-transparent pointer-events-none"></div>
                <div class="absolute bottom-4 left-4 z-10">
                    <span id="detail-badge" class="px-2.5 py-1 rounded text-[10px] font-bold uppercase tracking-wider bg-[#EEEBDA] text-[#282B4A]"></span>
                </div>
            </div>
            
            <!-- Details Info -->
            <div class="md:col-span-7 space-y-5">
                <div>
                    <h3 id="detail-title" class="text-2xl sm:text-3xl font-bold font-outfit leading-tight" style="color: var(--text);"></h3>
                    <p id="detail-subtitle" class="text-xs sm:text-sm font-medium mt-1" style="color: var(--accent);"></p>
                </div>
                
                <p id="detail-desc" class="text-xs sm:text-sm leading-relaxed" style="color: var(--text-dim);"></p>
                
                <div class="grid grid-cols-2 gap-4 py-4" style="border-top: 1px solid var(--border-soft); border-bottom: 1px solid var(--border-soft);">
                    <div class="flex items-center space-x-2.5">
                        <div class="p-2 rounded-lg" style="background-color: var(--bg); border: 1px solid var(--border-soft); color: var(--accent);">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase tracking-wider" style="color: var(--text-dim);">Date</div>
                            <div id="detail-date" class="text-xs font-bold" style="color: var(--text);"></div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <div class="p-2 rounded-lg" style="background-color: var(--bg); border: 1px solid var(--border-soft); color: var(--accent);">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase tracking-wider" style="color: var(--text-dim);">Time</div>
                            <div id="detail-time" class="text-xs font-bold" style="color: var(--text);"></div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <div class="p-2 rounded-lg" style="background-color: var(--bg); border: 1px solid var(--border-soft); color: var(--accent);">
                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase tracking-wider" style="color: var(--text-dim);">Venue</div>
                            <div id="detail-venue" class="text-xs font-bold truncate max-w-[120px]" style="color: var(--text);"></div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <div class="p-2 rounded-lg" style="background-color: var(--bg); border: 1px solid var(--border-soft); color: var(--accent);">
                            <i data-lucide="ticket" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase tracking-wider" style="color: var(--text-dim);">Entry Fee</div>
                            <div id="detail-prize" class="text-xs font-bold" style="color: var(--text);"></div>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-2" id="detail-tags">
                    <!-- Dynamic tags -->
                </div>
                
                <div class="flex items-center justify-between pt-2">
                    <span class="text-xs" style="color: var(--text-dim);">
                        👤 Joined: <span id="detail-participants" class="font-bold" style="color: var(--text);"></span> students
                    </span>
                    <button id="detail-register-btn" class="eb-btn-fill px-6 py-2.5 rounded-lg text-xs font-bold shadow-md transition-all cursor-pointer">
                        Register Now
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Unified Event Registration / Form Modal -->
<div id="register-modal" class="fixed inset-0 z-[100] hidden items-center justify-center px-4 overflow-y-auto">
    <div class="absolute inset-0 bg-[#282B4A]/60 backdrop-blur-sm transition-opacity duration-300" onclick="closeRegistrationModal()"></div>
    <div class="relative w-full max-w-md eb-card rounded-3xl p-6 sm:p-8 shadow-[0_20px_60px_rgba(0,0,0,0.5)] overflow-hidden scale-95 transition-all duration-300 opacity-0 transform" id="register-modal-box" style="background-color: var(--surface); border: 1px solid var(--border-soft);">
        <button onclick="closeRegistrationModal()" class="absolute top-5 right-5 p-2 rounded-full transition-colors duration-200 z-10" style="background-color: var(--bg-alt); color: var(--text);">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
        
        <div class="space-y-6">
            <div class="text-center">
                <span class="p-2 rounded-xl inline-flex items-center justify-center mb-3" style="background-color: var(--bg); border: 1px solid var(--border-soft); color: var(--accent);">
                    <i data-lucide="clipboard-list" class="w-6 h-6"></i>
                </span>
                <h3 class="text-xl sm:text-2xl font-bold font-outfit" style="color: var(--text);">Registration Portal</h3>
                <p id="register-event-title" class="text-xs sm:text-sm font-semibold mt-1" style="color: var(--accent);"></p>
            </div>
            
            <form id="registration-form" class="space-y-4" onsubmit="handleRegistrationSubmit(event)">
                <input type="hidden" id="register-event-id" name="event_id">
                
                <div>
                    <label class="block text-[11px] uppercase tracking-wider font-semibold mb-1" style="color: var(--text-dim);">Full Name</label>
                    <input type="text" required class="w-full rounded-lg px-4 py-2.5 text-xs outline-none" style="background-color: var(--bg); border: 1px solid var(--border-soft); color: var(--text);" placeholder="e.g. Karan Patel">
                </div>
                
                <div>
                    <label class="block text-[11px] uppercase tracking-wider font-semibold mb-1" style="color: var(--text-dim);">College Email Address</label>
                    <input type="email" required class="w-full rounded-lg px-4 py-2.5 text-xs outline-none" style="background-color: var(--bg); border: 1px solid var(--border-soft); color: var(--text);" placeholder="e.g. karan@college.edu">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] uppercase tracking-wider font-semibold mb-1" style="color: var(--text-dim);">College/University</label>
                        <select required class="w-full rounded-lg px-3 py-2.5 text-xs outline-none cursor-pointer" style="background-color: var(--bg); border: 1px solid var(--border-soft); color: var(--text);">
                            <option value="">Select College</option>
                            <option value="abc_tech">ABC Institute of Technology</option>
                            <option value="xyz_uni">XYZ University</option>
                            <option value="national_col">National College</option>
                            <option value="gtu">Gujarat Technical University</option>
                            <option value="sunrise_inst">Sunrise Institute</option>
                            <option value="modern_arts">Modern Arts & Science College</option>
                            <option value="other">Other College</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase tracking-wider font-semibold mb-1" style="color: var(--text-dim);">Student ID / Roll No</label>
                        <input type="text" required class="w-full rounded-lg px-4 py-2.5 text-xs outline-none" style="background-color: var(--bg); border: 1px solid var(--border-soft); color: var(--text);" placeholder="e.g. 23CS012">
                    </div>
                </div>
                
                <div class="flex items-center space-x-2 pt-2">
                    <input type="checkbox" id="terms" required class="w-4 h-4 rounded cursor-pointer" style="accent-color: var(--accent);">
                    <label for="terms" class="text-[10px] leading-tight cursor-pointer" style="color: var(--text-dim);">
                        I agree to follow the code of conduct and confirm my student eligibility.
                    </label>
                </div>
                
                <button type="submit" class="eb-btn-fill w-full py-3 mt-4 rounded-xl font-bold shadow-lg">
                    Confirm Free Registration
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Generic User Auth Modal (Login / Register) -->
<div id="auth-modal" class="fixed inset-0 z-[100] hidden items-center justify-center px-4 overflow-y-auto">
    <div class="absolute inset-0 bg-[#282B4A]/60 backdrop-blur-sm transition-opacity duration-300" onclick="closeAuthModal()"></div>
    <div class="relative w-full max-w-sm eb-card rounded-3xl p-6 sm:p-8 shadow-[0_20px_60px_rgba(0,0,0,0.5)] overflow-hidden scale-95 transition-all duration-300 opacity-0 transform" id="auth-modal-box" style="background-color: var(--surface); border: 1px solid var(--border-soft);">
        <button onclick="closeAuthModal()" class="absolute top-5 right-5 p-2 rounded-full transition-colors duration-200 z-10" style="background-color: var(--bg-alt); color: var(--text);">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
        
        <div class="space-y-6">
            <div class="text-center">
                <span class="p-2 rounded-xl inline-flex items-center justify-center mb-3" style="background-color: var(--bg); border: 1px solid var(--border-soft); color: var(--accent);">
                    <i data-lucide="user" class="w-6 h-6"></i>
                </span>
                <h3 id="auth-title" class="text-xl sm:text-2xl font-bold font-outfit" style="color: var(--text);">Join EVENTRA</h3>
                <p id="auth-subtitle" class="text-xs mt-1" style="color: var(--text-dim);">Unlock discovery & rapid registration.</p>
            </div>
            
            <form id="auth-form" class="space-y-4" onsubmit="handleAuthSubmit(event)">
                <div id="auth-name-container">
                    <label class="block text-[11px] uppercase tracking-wider font-semibold mb-1" style="color: var(--text-dim);">Full Name</label>
                    <input type="text" id="auth-name" class="w-full rounded-lg px-4 py-2.5 text-xs outline-none" style="background-color: var(--bg); border: 1px solid var(--border-soft); color: var(--text);" placeholder="e.g. Karan Patel">
                </div>
                
                <div>
                    <label class="block text-[11px] uppercase tracking-wider font-semibold mb-1" style="color: var(--text-dim);">Email Address</label>
                    <input type="email" required id="auth-email" class="w-full rounded-lg px-4 py-2.5 text-xs outline-none" style="background-color: var(--bg); border: 1px solid var(--border-soft); color: var(--text);" placeholder="e.g. karan@student.com">
                </div>
                
                <div>
                    <label class="block text-[11px] uppercase tracking-wider font-semibold mb-1" style="color: var(--text-dim);">Password</label>
                    <input type="password" required id="auth-password" class="w-full rounded-lg px-4 py-2.5 text-xs outline-none" style="background-color: var(--bg); border: 1px solid var(--border-soft); color: var(--text);" placeholder="••••••••">
                </div>
                
                <button type="submit" id="auth-submit-btn" class="eb-btn-fill w-full py-3 mt-4 rounded-xl font-bold shadow-lg">
                    Get Started
                </button>
                
                <div class="text-center text-[10px] pt-2" style="color: var(--text-dim);">
                    <span id="auth-switch-text">Already have an account?</span>
                    <button type="button" onclick="toggleAuthMode()" id="auth-switch-btn" class="font-bold hover:underline ml-1" style="color: var(--accent);">Log In</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Supplemental script for handling dynamic modal image loading safely
document.addEventListener("DOMContentLoaded", () => {
    window.openDetailsModal = function(eventId) {
        const event = window.eventsDatabase.find(e => e.id == eventId);
        if (!event) return;
        
        const modal = document.getElementById('details-modal');
        const modalBox = document.getElementById('details-modal-box');
        if (!modal || !modalBox) return;
        
        document.getElementById('detail-title').innerText = event.title;
        document.getElementById('detail-subtitle').innerText = `Organized by ${event.college_name}`;
        document.getElementById('detail-desc').innerText = event.description;
        
        const d = new Date(event.date);
        document.getElementById('detail-date').innerText = !isNaN(d) ? d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'TBA';
        document.getElementById('detail-time').innerText = event.time || 'TBA';
        document.getElementById('detail-venue').innerText = event.location_type;
        
        // Rupee currency symbol handling
        document.getElementById('detail-prize').innerText = event.registration_fee === 'Free' ? 'Free Entry' : String(event.registration_fee).replace('$', '₹');
        document.getElementById('detail-participants').innerText = event.participants || 0;
        
        // Category Badge
        const badge = document.getElementById('detail-badge');
        badge.innerText = event.category;

        // Inject Event Image dynamically
        const modalImg = document.getElementById('detail-modal-img');
        if (modalImg && event.image) {
            modalImg.src = event.image;
            modalImg.alt = event.title;
        }

        // Tags
        const tagsContainer = document.getElementById('detail-tags');
        tagsContainer.innerHTML = '';
        [event.event_type || 'Event', event.location_type || 'Venue'].forEach(tag => {
            const span = document.createElement('span');
            span.className = 'px-2.5 py-1 rounded-md text-[10px] font-bold uppercase';
            span.style.backgroundColor = 'var(--bg)';
            span.style.border = '1px solid var(--border-soft)';
            span.style.color = 'var(--text)';
            span.innerText = `#${tag}`;
            tagsContainer.appendChild(span);
        });
        
        // Register button logic
        const registerBtn = document.getElementById('detail-register-btn');
        if (event.registration_open) {
            registerBtn.removeAttribute('disabled');
            registerBtn.className = 'eb-btn-fill px-6 py-2.5 rounded-lg text-xs font-bold shadow-md cursor-pointer';
            
            if (window.isUserLoggedIn) {
                registerBtn.onclick = () => {
                    window.location.href = `event-register.php?id=${event.id}`;
                };
            } else {
                registerBtn.innerText = "Login to Register";
                registerBtn.onclick = () => {
                    window.location.href = `login.php`;
                };
            }
        } else {
            registerBtn.setAttribute('disabled', 'true');
            registerBtn.innerText = "Closed";
            registerBtn.className = 'eb-btn-disabled px-6 py-2.5 rounded-lg text-xs cursor-not-allowed';
            registerBtn.onclick = null;
        }
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        toggleBodyScroll(true);
        
        setTimeout(() => {
            modalBox.classList.remove('scale-95', 'opacity-0');
            modalBox.classList.add('scale-100', 'opacity-100');
            if (window.lucide) lucide.createIcons();
        }, 50);
    };

    window.closeDetailsModal = function() {
        const modal = document.getElementById('details-modal');
        const modalBox = document.getElementById('details-modal-box');
        if (!modal || !modalBox) return;
        
        modalBox.classList.add('scale-95', 'opacity-0');
        modalBox.classList.remove('scale-100', 'opacity-100');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            toggleBodyScroll(false);
        }, 200);
    };
});
</script>