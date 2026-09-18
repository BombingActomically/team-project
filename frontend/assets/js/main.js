// C:\xampp\htdocs\Project2\frontend\assets\js\main.js

let activeCategory = 'all';
let searchPattern = '';
let selectedCollege = '';
let selectedDate = '';
let selectedSort = 'upcoming';

let calendarYear = 2026;
let calendarMonth = 9;

function initEventsGrid() {
    const urlParams = new URLSearchParams(window.location.search);
    const urlCollege = urlParams.get('college');
    const urlCategory = urlParams.get('category');
    
    if (urlCollege) {
        selectedCollege = urlCollege;
        const collegeFilter = document.getElementById('college-filter');
        if (collegeFilter) collegeFilter.value = urlCollege;
    }
    
    if (urlCategory) {
        activeCategory = urlCategory;
        syncCategoryTabs(urlCategory);
        const mobileSelect = document.getElementById('category-mobile-select');
        if (mobileSelect) mobileSelect.value = urlCategory;
    }

    renderEvents();
    
    const searchInput = document.getElementById('search-input');
    const collegeFilter = document.getElementById('college-filter');
    const dateFilter = document.getElementById('date-filter');
    const sortFilter = document.getElementById('sort-filter');
    const categoryMobileSelect = document.getElementById('category-mobile-select');
    
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            searchPattern = e.target.value.toLowerCase().trim();
            renderEvents();
        });
    }
    
    if (collegeFilter) {
        collegeFilter.addEventListener('change', (e) => {
            selectedCollege = e.target.value;
            renderEvents();
        });
    }
    
    if (dateFilter) {
        dateFilter.addEventListener('change', (e) => {
            selectedDate = e.target.value;
            renderEvents();
        });
    }
    
    if (sortFilter) {
        sortFilter.addEventListener('change', (e) => {
            selectedSort = e.target.value;
            renderEvents();
        });
    }
    
    if (categoryMobileSelect) {
        categoryMobileSelect.addEventListener('change', (e) => {
            activeCategory = e.target.value;
            syncCategoryTabs(activeCategory);
            renderEvents();
        });
    }
}

function setCategoryFilter(categoryName) {
    if (!document.getElementById('events-section')) {
        window.location.href = `events.php?category=${encodeURIComponent(categoryName)}`;
        return;
    }

    activeCategory = categoryName;
    syncCategoryTabs(categoryName);
    
    const mobileSelect = document.getElementById('category-mobile-select');
    if (mobileSelect) {
        mobileSelect.value = categoryName === 'all' ? 'all' : categoryName;
    }
    
    renderEvents();
}

function syncCategoryTabs(categoryName) {
    const tabs = document.querySelectorAll('.category-tab');
    tabs.forEach(tab => {
        const cat = tab.getAttribute('data-category');
        if (cat.toLowerCase() === categoryName.toLowerCase() || (categoryName === 'all' && cat === 'all')) {
            tab.classList.add('tab-active');
        } else {
            tab.classList.remove('tab-active');
        }
    });
}

function filterByCollege(collegeId) {
    if (!document.getElementById('events-section')) {
        window.location.href = `events.php?college=${encodeURIComponent(collegeId)}`;
        return;
    }

    selectedCollege = collegeId;
    const collegeFilter = document.getElementById('college-filter');
    if (collegeFilter) {
        collegeFilter.value = collegeId;
    }
    
    renderEvents();
    document.getElementById('events-section').scrollIntoView({ behavior: 'smooth' });
}

function filterByCategory(categoryName) {
    setCategoryFilter(categoryName);
    if (document.getElementById('events-section')) {
        document.getElementById('events-section').scrollIntoView({ behavior: 'smooth' });
    }
}

function clearAllFilters() {
    activeCategory = 'all';
    searchPattern = '';
    selectedCollege = '';
    selectedDate = '';
    selectedSort = 'upcoming';
    
    const searchInput = document.getElementById('search-input');
    const collegeFilter = document.getElementById('college-filter');
    const dateFilter = document.getElementById('date-filter');
    const sortFilter = document.getElementById('sort-filter');
    const categoryMobileSelect = document.getElementById('category-mobile-select');
    
    if (searchInput) searchInput.value = '';
    if (collegeFilter) collegeFilter.value = '';
    if (dateFilter) dateFilter.value = '';
    if (sortFilter) sortFilter.value = 'upcoming';
    if (categoryMobileSelect) categoryMobileSelect.value = 'all';
    
    setCategoryFilter('all');
    renderEvents();
}

function renderEvents() {
    const gridContainer = document.getElementById('events-grid-container');
    const emptyState = document.getElementById('empty-state');
    const countElement = document.getElementById('filtered-count');
    const clearBtn = document.getElementById('clear-filters-btn');
    
    if (!gridContainer || !window.eventsDatabase) return;
    
    let filtered = window.eventsDatabase.filter(event => {
        if (activeCategory !== 'all' && event.category.toLowerCase() !== activeCategory.toLowerCase()) {
            return false;
        }
        if (selectedCollege && String(event.college_id) !== String(selectedCollege)) {
            return false;
        }
        if (selectedDate && event.date !== selectedDate) {
            return false;
        }
        if (searchPattern) {
            const matchesTitle = (event.title || '').toLowerCase().includes(searchPattern);
            const matchesDesc = (event.description || '').toLowerCase().includes(searchPattern);
            if (!matchesTitle && !matchesDesc) {
                return false;
            }
        }
        return true;
    });
    
    if (selectedSort === 'upcoming') {
        filtered.sort((a, b) => new Date(a.date) - new Date(b.date));
    } else if (selectedSort === 'popularity') {
        filtered.sort((a, b) => b.participants - a.participants);
    } else if (selectedSort === 'title') {
        filtered.sort((a, b) => a.title.localeCompare(b.title));
    }
    
    const hasActiveFilters = activeCategory !== 'all' || searchPattern || selectedCollege || selectedDate || selectedSort !== 'upcoming';
    if (clearBtn) {
        if (hasActiveFilters) {
            clearBtn.classList.remove('hidden');
            clearBtn.classList.add('flex');
        } else {
            clearBtn.classList.add('hidden');
            clearBtn.classList.remove('flex');
        }
    }
    
    if (countElement) {
        countElement.innerText = filtered.length;
    }
    
    if (filtered.length === 0) {
        gridContainer.innerHTML = '';
        emptyState.classList.remove('hidden');
        emptyState.classList.add('flex');
        return;
    } else {
        emptyState.classList.add('hidden');
        emptyState.classList.remove('flex');
    }
    
    let cardHTML = '';
    filtered.forEach(event => {
        let badgeClass = 'bg-[#EEEBDA] text-[#282B4A] border-transparent';
        
        const locationTypeHTML = event.location_type === 'Online' 
            ? `<span class="flex items-center text-[10px] font-bold text-[#282B4A] uppercase tracking-wider"><span class="w-2 h-2 rounded-full bg-red-500 mr-1.5 animate-pulse"></span> Online</span>` 
            : `<span class="flex items-center text-[10px] font-bold text-[#282B4A] uppercase tracking-wider"><i data-lucide="map-pin" class="w-3 h-3 mr-1.5 text-[#282B4A]"></i> On-Campus</span>`;
            
        const rawDate = new Date(event.date);
        const formattedDate = !isNaN(rawDate) ? rawDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'TBA';
        
        // Smart Button Logic using window.isUserLoggedIn
        let registerButtonHtml = '';
        if (event.registration_open) {
            if (window.isUserLoggedIn) {
                registerButtonHtml = `<a href="event-register.php?id=${event.id}" class="eb-btn-fill flex justify-center items-center flex-grow py-2.5 rounded text-xs font-bold uppercase tracking-wider shadow-lg">Register Now</a>`;
            } else {
                registerButtonHtml = `<a href="login.php" class="eb-btn-fill flex justify-center items-center flex-grow py-2.5 rounded text-xs font-bold uppercase tracking-wider shadow-lg">Login to Register</a>`;
            }
        } else {
            registerButtonHtml = `<button class="eb-btn-disabled flex-grow py-2.5 rounded text-xs font-bold cursor-not-allowed uppercase tracking-wider" disabled>Closed</button>`;
        }

        cardHTML += `
            <div class="event-card eb-card rounded-xl overflow-hidden flex flex-col justify-between group relative shadow-lg hover:-translate-y-1" style="border-color: var(--border-soft);" onmouseover="this.style.borderColor='var(--border-accent)'" onmouseout="this.style.borderColor='var(--border-soft)'">
                <div>
                    <!-- IMAGE RENDERER -->
                    <div class="h-48 w-full relative flex items-center justify-center overflow-hidden group border-b border-[var(--border-soft)] bg-[var(--text)]">
                        <img src="${event.image}" alt="${event.title}" class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-700 ease-in-out">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-[var(--text)]/80 to-transparent pointer-events-none"></div>

                        <div class="absolute top-4 left-4 z-10">
                            <span class="px-3 py-1 rounded text-[10px] font-bold uppercase tracking-widest border border-transparent bg-[#EEEBDA]/95 text-[#282B4A] shadow-sm backdrop-blur">
                                ${event.category}
                            </span>
                        </div>
                        <div class="absolute bottom-4 right-4 bg-[#EEEBDA]/95 px-2 py-1 rounded backdrop-blur-sm shadow-lg border border-[var(--border-soft)] z-10">
                            ${locationTypeHTML}
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="space-y-1.5">
                            <span class="text-[10px] font-bold tracking-widest block uppercase" style="color: var(--accent);">${event.college_name}</span>
                            <h3 class="text-xl font-bold font-outfit leading-tight" style="color: var(--text);">${event.title}</h3>
                        </div>
                        <p class="text-sm leading-relaxed line-clamp-2" style="color: var(--text-dim);">${event.description}</p>
                        
                        <div class="flex items-center justify-between text-xs font-semibold pt-4" style="color: var(--text); border-top: 1px solid var(--border-soft);">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="calendar" class="w-4 h-4" style="color: var(--accent);"></i>
                                <span>${formattedDate}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="users" class="w-4 h-4" style="color: var(--accent);"></i>
                                <span>${event.participants || 0} Joined</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 pt-0 flex gap-3 mt-auto">
                    <button onclick="openDetailsModal('${event.id}')" class="eb-btn-ghost flex justify-center items-center flex-grow py-2.5 rounded text-xs font-bold uppercase tracking-wider">
                        Details
                    </button>
                    ${registerButtonHtml}
                </div>
            </div>
        `;
    });
    
    gridContainer.innerHTML = cardHTML;
    if(window.lucide) {
        lucide.createIcons();
    }
}

const MONTH_NAMES = [
    "January", "February", "March", "April", "May", "June", 
    "July", "August", "September", "October", "November", "December"
];

function initCalendar() {
    renderCalendar();
    const initialDefaultDate = "2026-10-15";
    loadCalendarEventsForDate(initialDefaultDate);
}

function prevMonth() {
    calendarMonth--;
    if (calendarMonth < 0) {
        calendarMonth = 11;
        calendarYear--;
    }
    renderCalendar();
}

function nextMonth() {
    calendarMonth++;
    if (calendarMonth > 11) {
        calendarMonth = 0;
        calendarYear++;
    }
    renderCalendar();
}

function renderCalendar() {
    const monthYearLabel = document.getElementById('calendar-month-year');
    const daysGrid = document.getElementById('calendar-days-grid');
    if (!monthYearLabel || !daysGrid || !window.eventsDatabase) return;
    
    monthYearLabel.innerText = `${MONTH_NAMES[calendarMonth]} ${calendarYear}`;
    
    const firstDayIndex = new Date(calendarYear, calendarMonth, 1).getDay(); 
    const totalDays = new Date(calendarYear, calendarMonth + 1, 0).getDate(); 
    
    daysGrid.innerHTML = '';
    
    for (let i = 0; i < firstDayIndex; i++) {
        const blankCell = document.createElement('div');
        blankCell.className = 'py-3 text-brandMuted/20 cursor-default';
        blankCell.innerText = '';
        daysGrid.appendChild(blankCell);
    }
    
    for (let day = 1; day <= totalDays; day++) {
        const dayCell = document.createElement('button');
        const mStr = (calendarMonth + 1) < 10 ? `0${calendarMonth + 1}` : `${calendarMonth + 1}`;
        const dStr = day < 10 ? `0${day}` : `${day}`;
        const dateStr = `${calendarYear}-${mStr}-${dStr}`;
        
        const matches = window.eventsDatabase.filter(e => e.date === dateStr);
        
        dayCell.className = 'py-3 rounded-xl border border-transparent font-semibold relative transition-all duration-200 cursor-pointer flex flex-col items-center justify-center ';
        
        if (matches.length > 0) {
            dayCell.classList.add('bg-[#4B4F86]/10', 'text-[#4B4F86]', 'border-[#4B4F86]/20', 'hover:bg-[#4B4F86]/20');
            dayCell.classList.add('calendar-event-day'); 
        } else {
            dayCell.classList.add('hover:bg-[#E4DFC8]/40', 'text-[#282B4A]/50', 'hover:text-[#282B4A]');
        }
        
        dayCell.innerText = day;
        dayCell.onclick = () => selectCalendarDate(dateStr, dayCell);
        
        daysGrid.appendChild(dayCell);
    }
}

function selectCalendarDate(dateStr, element) {
    const cells = document.querySelectorAll('#calendar-days-grid button');
    cells.forEach(c => c.classList.remove('border-[#4B4F86]', 'scale-105', 'bg-[#E4DFC8]'));
    
    if (element) {
        element.classList.add('border-[#4B4F86]', 'scale-105', 'bg-[#E4DFC8]');
    }
    
    const rawDate = new Date(dateStr);
    const options = { month: 'short', day: 'numeric', year: 'numeric' };
    const dateLabel = document.getElementById('calendar-selected-date');
    if (dateLabel) {
        dateLabel.innerText = !isNaN(rawDate) ? rawDate.toLocaleDateString('en-US', options) : dateStr;
    }
    
    loadCalendarEventsForDate(dateStr);
}

function loadCalendarEventsForDate(dateStr) {
    const container = document.getElementById('calendar-events-container');
    if (!container || !window.eventsDatabase) return;
    
    const events = window.eventsDatabase.filter(e => e.date === dateStr);
    
    if (events.length === 0) {
        container.innerHTML = `
            <div class="py-12 text-center space-y-2">
                <i data-lucide="calendar-x" class="w-8 h-8 text-[#282B4A]/50 mx-auto opacity-50"></i>
                <p class="text-xs text-[#282B4A]/50">No events scheduled for this date.</p>
            </div>
        `;
        if(window.lucide) lucide.createIcons();
        return;
    }
    
    let html = '';
    events.forEach(event => {
        html += `
            <div class="p-4 rounded-2xl bg-[#F7F4E9] border border-[#282B4A]/10 space-y-3 group hover:border-[#4B4F86]/30 transition-all duration-300">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[9px] uppercase tracking-wider font-bold text-[#4B4F86] block">${event.category}</span>
                        <h4 class="text-sm font-bold text-[#282B4A] font-outfit mt-0.5">${event.title}</h4>
                        <p class="text-[10px] text-[#282B4A]/60 mt-0.5">${event.college_name}</p>
                    </div>
                    <span class="text-[9px] font-bold px-2 py-0.5 bg-[#EEEBDA] border border-[#282B4A]/10 text-[#4B4F86] rounded-md uppercase">
                        TBA
                    </span>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-[#282B4A]/10 text-[10px] text-[#282B4A]/60">
                    <span>${event.location_type} Venue</span>
                    <button onclick="openDetailsModal('${event.id}')" class="text-[#4B4F86] hover:underline hover:text-[#282B4A] transition-colors font-bold">
                        View Details
                    </button>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
    if(window.lucide) lucide.createIcons();
}

function toggleBodyScroll(lock) {
    if (lock) {
        document.body.classList.add('overflow-hidden');
    } else {
        document.body.classList.remove('overflow-hidden');
    }
}

// DETAILS MODAL
function openDetailsModal(eventId) {
    const event = window.eventsDatabase.find(e => e.id == eventId);
    if (!event) return;
    
    const modal = document.getElementById('details-modal');
    const modalBox = document.getElementById('details-modal-box');
    if (!modal || !modalBox) return;
    
    const titleEl = document.getElementById('detail-title');
    if(titleEl) titleEl.innerText = event.title;
    
    const subEl = document.getElementById('detail-subtitle');
    if(subEl) subEl.innerText = `Organized by ${event.college_name}`;
    
    const descEl = document.getElementById('detail-desc');
    if(descEl) descEl.innerText = event.description;
    
    const dateEl = document.getElementById('detail-date');
    if(dateEl) {
        const d = new Date(event.date);
        dateEl.innerText = !isNaN(d) ? d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'TBA';
    }
    
    const timeEl = document.getElementById('detail-time');
    if(timeEl) timeEl.innerText = event.time || 'TBA';
    
    const venueEl = document.getElementById('detail-venue');
    if(venueEl) venueEl.innerText = event.location_type;
    
    const prizeEl = document.getElementById('detail-prize');
    if(prizeEl) {
        prizeEl.innerText = event.registration_fee === 'Free' ? 'Free Entry' : String(event.registration_fee).replace('$', '₹');
    }
    
    const partEl = document.getElementById('detail-participants');
    if(partEl) partEl.innerText = event.participants || 0;
    
    const badge = document.getElementById('detail-badge');
    if(badge) badge.innerText = event.category;

    // RENDER IMAGE IN DETAILS MODAL INSTEAD OF SYMBOL
    const iconEl = document.getElementById('detail-icon');
    if (iconEl) {
        const parent = iconEl.parentElement;
        if (parent) {
            parent.innerHTML = `<img src="${event.image}" alt="${event.title}" class="w-full h-full object-cover rounded-xl shadow-md border border-[var(--border-soft)]">`;
        } else {
            iconEl.outerHTML = `<img src="${event.image}" alt="${event.title}" class="w-full h-full object-cover rounded-xl shadow-md border border-[var(--border-soft)]">`;
        }
    }
    
    const tagsContainer = document.getElementById('detail-tags');
    if (tagsContainer) {
        tagsContainer.innerHTML = '';
        let generatedTags = [event.event_type || 'Event', event.location_type || 'Venue'];
        generatedTags.forEach(tag => {
            const span = document.createElement('span');
            span.className = 'px-2.5 py-1 rounded-md text-[10px] font-bold uppercase';
            span.style.backgroundColor = 'var(--bg)';
            span.style.border = '1px solid var(--border-soft)';
            span.style.color = 'var(--text)';
            span.innerText = `#${tag}`;
            tagsContainer.appendChild(span);
        });
    }
    
    const registerBtn = document.getElementById('detail-register-btn');
    if (registerBtn) {
        if (event.registration_open) {
            registerBtn.removeAttribute('disabled');
            registerBtn.className = 'eb-btn-fill px-6 py-2.5 rounded-lg text-xs font-bold shadow-md transition-all cursor-pointer';
            
            // SMART LINK IN MODAL TOO
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
    }
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    toggleBodyScroll(true);
    
    setTimeout(() => {
        modalBox.classList.remove('scale-95', 'opacity-0');
        modalBox.classList.add('scale-100', 'opacity-100');
        if (window.lucide) {
            lucide.createIcons();
        }
    }, 50);
}

function closeDetailsModal() {
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
}

function openRegistrationModal(eventId) {
    const event = window.eventsDatabase.find(e => e.id == eventId);
    if (!event) return;
    
    const modal = document.getElementById('register-modal');
    const modalBox = document.getElementById('register-modal-box');
    if (!modal || !modalBox) return;
    
    document.getElementById('register-event-id').value = event.id;
    document.getElementById('register-event-title').innerText = event.title;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    toggleBodyScroll(true);
    
    setTimeout(() => {
        modalBox.classList.remove('scale-95', 'opacity-0');
        modalBox.classList.add('scale-100', 'opacity-100');
    }, 50);
}

function closeRegistrationModal() {
    const modal = document.getElementById('register-modal');
    const modalBox = document.getElementById('register-modal-box');
    if (!modal || !modalBox) return;
    
    modalBox.classList.add('scale-95', 'opacity-0');
    modalBox.classList.remove('scale-100', 'opacity-100');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        toggleBodyScroll(false);
        const form = document.getElementById('registration-form');
        if (form) form.reset();
    }, 200);
}

function handleRegistrationSubmit(e) {
    e.preventDefault();
    const eventId = document.getElementById('register-event-id').value;
    const competitorName = e.target.querySelector('input[type="text"]').value;
    
    closeRegistrationModal();
    window.location.href = `register-success.php?event_id=${encodeURIComponent(eventId)}&name=${encodeURIComponent(competitorName)}`;
}

function handleNewsletterSubmit(e) {
    e.preventDefault();
    const input = e.target.querySelector('input');
    const email = input.value;
    input.value = '';
    showToast("Subscribed!", `Weekly event digests will now be sent to ${email}.`);
}

function showToast(title, message) {
    const toast = document.getElementById('toast-notification');
    const tTitle = document.getElementById('toast-title');
    const tMsg = document.getElementById('toast-message');
    
    if (!toast) return;
    
    if(tTitle) tTitle.innerText = title;
    if(tMsg) tMsg.innerText = message;
    
    toast.classList.remove('hidden');
    toast.classList.add('flex');
    
    setTimeout(() => {
        toast.classList.add('hidden');
        toast.classList.remove('flex');
    }, 4500);
}

function toggleAccordion(faqId) {
    const panel = document.getElementById(`${faqId}-content`);
    const icon = document.getElementById(`${faqId}-icon`);
    if (!panel || !icon) return;
    
    const isHidden = panel.classList.contains('hidden');
    
    const allPanels = document.querySelectorAll('[id$="-content"]');
    const allIcons = document.querySelectorAll('[id$="-icon"]');
    
    allPanels.forEach(p => {
        if (p.tagName === 'DIV' && p.id.startsWith('faq-')) {
            p.classList.add('hidden');
        }
    });
    
    allIcons.forEach(i => {
        if (i.id.startsWith('faq-')) {
            i.classList.remove('rotate-180');
        }
    });
    
    if (isHidden) {
        panel.classList.remove('hidden');
        icon.classList.add('rotate-180');
    }
}

window.addEventListener('scroll', () => {
    const navbar = document.getElementById('main-navbar');
    if (!navbar) return;
    
    if (window.scrollY > 20) {
        navbar.classList.add('shadow-lg');
        navbar.classList.remove('bg-transparent', 'border-transparent', 'py-4');
        navbar.classList.add('py-2');
        navbar.style.backgroundColor = 'rgba(238, 235, 218, 0.9)';
        navbar.style.backdropFilter = 'blur(12px)';
        navbar.style.borderBottom = '1px solid var(--border-soft)';
    } else {
        navbar.classList.add('bg-transparent', 'border-transparent');
        navbar.classList.remove('shadow-lg', 'py-2');
        navbar.classList.add('py-4');
        navbar.style.backgroundColor = 'transparent';
        navbar.style.backdropFilter = 'none';
        navbar.style.borderBottom = '1px solid transparent';
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('menu-icon');
    
    if (toggleBtn && mobileMenu) {
        toggleBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            
            if (mobileMenu.classList.contains('hidden')) {
                menuIcon.setAttribute('data-lucide', 'menu');
            } else {
                menuIcon.setAttribute('data-lucide', 'x');
            }
            if(window.lucide) lucide.createIcons();
        });
        
        const links = document.querySelectorAll('.mobile-nav-link');
        links.forEach(l => {
            l.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                menuIcon.setAttribute('data-lucide', 'menu');
                if(window.lucide) lucide.createIcons();
            });
        });
    }
    
    const navSearchBtn = document.getElementById('nav-search-btn');
    const mobileSearchBtn = document.getElementById('mobile-search-btn');
    const filterSearchInput = document.getElementById('search-input');
    
    const handleSearchClick = () => {
        if (!document.getElementById('events-section')) {
            window.location.href = 'events.php';
            return;
        }
        
        document.getElementById('events-section').scrollIntoView({ behavior: 'smooth' });
        setTimeout(() => {
            if (filterSearchInput) filterSearchInput.focus();
        }, 300);
    };
    
    if (navSearchBtn) navSearchBtn.addEventListener('click', handleSearchClick);
    if (mobileSearchBtn) mobileSearchBtn.addEventListener('click', handleSearchClick);
    
    if (document.getElementById('events-section')) {
        initEventsGrid();
    }
    if (document.getElementById('calendar-section')) {
        initCalendar();
    }
});