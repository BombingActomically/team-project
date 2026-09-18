<?php
// C:\xampp\htdocs\Project2\frontend\register-success.php
$page_title = 'events';

// 1. Load mock data array
require_once 'data/events.php';

// Generate some dummy registration parameters for visual realism
$regName = isset($_GET['name']) ? $_GET['name'] : 'Student Competitor';
$regId = strtoupper(substr(md5(time() . $regName), 0, 8));
$eventId = isset($_GET['event_id']) ? $_GET['event_id'] : 'codestorm_2026';

// Locate event details
$event = null;
foreach ($events as $ev) {
    if ($ev['id'] === $eventId) {
        $event = $ev;
        break;
    }
}
if (!$event) {
    $event = $events[0]; // fallback
}

$formattedDate = date('M d, Y', strtotime($event['date']));

// 2. Load Head metadata and config
include_once 'components/header.php';

// 3. Load Navbar
include_once 'components/navbar.php';
?>

<!-- Pass Details / Ticket block -->
<main class="relative z-10 overflow-hidden min-h-screen pt-32 pb-20">
    <div class="max-w-md mx-auto px-4 sm:px-6 relative z-10 space-y-6">
        
        <!-- Header status -->
        <div class="text-center space-y-2">
            <div class="w-16 h-16 rounded-full bg-brandSecondary/15 border border-brandSecondary text-brandSecondary flex items-center justify-center mx-auto glow-ocean animate-float">
                <i data-lucide="check-circle-2" class="w-8 h-8"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold font-outfit text-brandSecondary tracking-tight mt-4">
                Registration Confirmed!
            </h1>
            <p class="text-brandMuted text-xs max-w-xs mx-auto">
                Your student registration has been validated. Check your college inbox for coordinate maps and instructions.
            </p>
        </div>
        
        <!-- The Ticket Card layout -->
        <div class="glass-panel card-border-glow rounded-3xl p-6 border border-brandBorder shadow-2xl relative overflow-hidden space-y-6">
            
            <!-- Branding bar -->
            <div class="flex items-center justify-between border-b border-brandBorder/60 pb-4">
                <div class="flex items-center space-x-1.5">
                    <span class="p-1 rounded bg-brandSecondary/20 border border-brandSecondary/30 text-brandSecondary">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                    </span>
                    <span class="text-sm font-bold font-outfit text-white tracking-widest uppercase">Eventra Pass</span>
                </div>
                <span class="text-[10px] font-bold text-brandPrimary uppercase tracking-widest">
                    <?php echo htmlspecialchars($event['category']); ?>
                </span>
            </div>
            
            <!-- Details info -->
            <div class="space-y-4">
                <div>
                    <span class="text-[9px] text-brandMuted uppercase tracking-wider font-semibold">Event Name</span>
                    <h3 class="text-lg font-bold font-outfit text-white"><?php echo htmlspecialchars($event['title']); ?></h3>
                    <p class="text-[10px] text-brandPrimary font-medium mt-0.5"><?php echo htmlspecialchars($event['college_name']); ?></p>
                </div>
                
                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div>
                        <span class="text-[9px] text-brandMuted uppercase tracking-wider font-semibold">Competitor Name</span>
                        <div class="text-xs font-bold text-white"><?php echo htmlspecialchars($regName); ?></div>
                    </div>
                    <div>
                        <span class="text-[9px] text-brandMuted uppercase tracking-wider font-semibold">Ticket ID</span>
                        <div class="text-xs font-bold text-brandSecondary tracking-wider font-outfit"><?php echo $regId; ?></div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-[9px] text-brandMuted uppercase tracking-wider font-semibold">Date & Time</span>
                        <div class="text-[11px] font-bold text-white"><?php echo $formattedDate; ?></div>
                        <div class="text-[9px] text-brandMuted mt-0.5"><?php echo $event['time']; ?></div>
                    </div>
                    <div>
                        <span class="text-[9px] text-brandMuted uppercase tracking-wider font-semibold">Venue</span>
                        <div class="text-[11px] font-bold text-white leading-tight"><?php echo $event['location_type']; ?></div>
                        <div class="text-[9px] text-brandMuted mt-0.5 truncate"><?php echo $event['location']; ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Dynamic Ticket Divider Line -->
            <div class="relative flex items-center justify-between py-2">
                <div class="absolute -left-9 w-6 h-6 rounded-full bg-brandBg border-r border-brandBorder/60 z-10"></div>
                <div class="w-full border-t-2 border-dashed border-brandBorder/60"></div>
                <div class="absolute -right-9 w-6 h-6 rounded-full bg-brandBg border-l border-brandBorder/60 z-10"></div>
            </div>
            
            <!-- QR Code graphics -->
            <div class="flex flex-col items-center justify-center space-y-3 pt-2">
                <!-- Glowing glass qr code holder -->
                <div class="p-3 bg-brandBg border border-brandBorder rounded-2xl glow-ocean">
                    <!-- SVG generating generic QR shape -->
                    <svg class="w-24 h-24 text-brandSecondary" viewBox="0 0 100 100" fill="currentColor">
                        <rect x="5" y="5" width="20" height="20"/>
                        <rect x="10" y="10" width="10" height="10" fill="#122C4F"/>
                        <rect x="75" y="5" width="20" height="20"/>
                        <rect x="80" y="10" width="10" height="10" fill="#122C4F"/>
                        <rect x="5" y="75" width="20" height="20"/>
                        <rect x="10" y="80" width="10" height="10" fill="#122C4F"/>
                        <!-- Random blocks -->
                        <rect x="35" y="5" width="10" height="10"/>
                        <rect x="50" y="15" width="15" height="10"/>
                        <rect x="35" y="30" width="20" height="10"/>
                        <rect x="5" y="45" width="15" height="15"/>
                        <rect x="65" y="35" width="10" height="20"/>
                        <rect x="80" y="55" width="15" height="10"/>
                        <rect x="30" y="65" width="10" height="25"/>
                        <rect x="50" y="75" width="15" height="15"/>
                        <rect x="70" y="75" width="15" height="10"/>
                        <rect x="85" y="85" width="10" height="10"/>
                    </svg>
                </div>
                <div class="text-[9px] uppercase tracking-widest text-brandMuted font-bold">Scan at Entrance Pavilion</div>
            </div>
        </div>
        
        <!-- Actions buttons -->
        <div class="flex gap-4">
            <button onclick="window.print()" class="w-1/2 py-3 rounded-xl font-bold bg-brandBgSec border border-brandBorder text-brandSecondary text-xs hover:border-brandPrimary/35 transition-all text-center">
                Print Pass
            </button>
            <a href="events.php" class="w-1/2 py-3 rounded-xl font-bold bg-brandSecondary text-brandBgSec text-xs hover:opacity-90 transition-opacity text-center shadow-lg shadow-brandSecondary/25">
                Discover More
            </a>
        </div>
        
    </div>
</main>

<!-- Load Application scripts -->
<script src="./assets/js/main.js"></script>

<?php
// 3. Load Footer
include_once 'components/footer.php';
?>
