<?php
// C:\xampp\htdocs\Project2\frontend\college-detail.php
$page_title = 'colleges';

// 1. Load mock database
require_once 'data/events.php';

// Fetch query string parameter
$collegeId = isset($_GET['id']) ? $_GET['id'] : '';

// Locate college metadata
$college = null;
foreach ($colleges as $col) {
    if ($col['id'] === $collegeId) {
        $college = $col;
        break;
    }
}

// Redirect if invalid/missing ID
if (!$college) {
    header('Location: colleges.php');
    exit;
}

// Filter events hosted ONLY by this college
$collegeEvents = array_filter($events, function($e) use ($collegeId) {
    return $e['college_id'] === $collegeId;
});

// 2. Load Head metadata and config
include_once 'components/header.php';

// 3. Load Navbar
include_once 'components/navbar.php';
?>

<!-- College Header Profile block -->
<section class="relative pt-32 pb-16 overflow-hidden bg-brandBgSec/15 border-b border-brandBorder/60">
    <div class="absolute top-[20%] left-[10%] w-72 h-72 bg-brandPrimary/10 rounded-full blur-[90px] pointer-events-none"></div>
    
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="glass-panel card-border-glow rounded-3xl p-6 sm:p-10 flex flex-col md:flex-row items-center md:items-start gap-8 relative">
            <!-- Icon logo frame -->
            <div class="w-20 h-20 rounded-2xl bg-brandBg border border-brandBorder flex items-center justify-center text-brandSecondary shadow-lg flex-shrink-0">
                <i data-lucide="<?php echo $college['logo']; ?>" class="w-10 h-10"></i>
            </div>
            
            <!-- Details description -->
            <div class="space-y-4 text-center md:text-left flex-grow">
                <div class="space-y-1">
                    <span class="px-2.5 py-0.5 rounded-full bg-brandPrimary/15 border border-brandBorder text-[10px] font-bold uppercase tracking-widest text-brandPrimary">
                        Verified Host Campus
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-bold font-outfit text-white leading-tight">
                        <?php echo htmlspecialchars($college['name']); ?>
                    </h1>
                    <p class="text-sm text-brandMuted flex items-center justify-center md:justify-start">
                        <i data-lucide="map-pin" class="w-4 h-4 mr-1 text-brandPrimary"></i>
                        <?php echo htmlspecialchars($college['city']); ?>, India
                    </p>
                </div>
                
                <p class="text-xs text-brandMuted max-w-xl leading-relaxed">
                    <?php echo htmlspecialchars($college['name']); ?> is a premier educational institution committed to hosting elite inter-collegiate competitions, seminars, and networking masterclasses. Explore their hosting directory below.
                </p>
                
                <!-- Counter widgets -->
                <div class="flex flex-wrap gap-4 justify-center md:justify-start pt-2 border-t border-brandBorder/40">
                    <div class="text-xs text-brandMuted">
                        🏛️ Campus Events: <strong class="text-brandSecondary"><?php echo count($collegeEvents); ?></strong> listed
                    </div>
                    <div class="text-xs text-brandMuted">
                        📍 Region: <strong class="text-brandSecondary"><?php echo htmlspecialchars($college['city']); ?> Zone</strong>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</section>

<!-- Hosted Events List -->
<main class="relative z-10 overflow-hidden min-h-screen py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-10 text-center md:text-left">
            <h2 class="text-2xl sm:text-3xl font-bold font-outfit text-brandSecondary">
                Hosted Competitions & Activities
            </h2>
            <p class="text-brandMuted text-xs mt-1">
                Browse through all active registrations hosted by <?php echo htmlspecialchars($college['name']); ?>.
            </p>
        </div>
        
        <!-- Grid List -->
        <?php if (count($collegeEvents) === 0): ?>
            <!-- Empty state -->
            <div class="flex flex-col items-center justify-center py-20 text-center space-y-4 glass-panel card-border-glow rounded-2xl border border-brandBorder max-w-md mx-auto">
                <div class="p-4 rounded-full bg-brandCard border border-brandBorder text-brandPrimary flex items-center justify-center">
                    <i data-lucide="calendar-x" class="w-12 h-12"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold font-outfit text-white">No Events Listed</h3>
                    <p class="text-brandMuted text-sm mt-1 max-w-xs mx-auto">
                        This college does not have any active upcoming events registered on EVENTRA currently.
                    </p>
                </div>
                <a href="colleges.php" class="px-5 py-2.5 bg-brandSecondary text-brandBgSec hover:opacity-90 rounded-xl text-xs font-bold transition-all shadow-md">
                    Return to Colleges List
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($collegeEvents as $event): 
                    // Determine category badges
                    $badgeClass = 'bg-brandPrimary/20 text-brandPrimary border-brandPrimary/30';
                    if (strtolower($event['category']) === 'technical' || strtolower($event['category']) === 'hackathon') {
                        $badgeClass = 'bg-brandHighlight/20 text-brandHighlight border-brandHighlight/30';
                    } else if (strtolower($event['category']) === 'sports') {
                        $badgeClass = 'bg-brandSecondary/15 text-brandSecondary border-brandSecondary/30';
                    } else if (strtolower($event['category']) === 'cultural') {
                        $badgeClass = 'bg-pink-500/15 text-pink-400 border-pink-500/25';
                    }
                    
                    $locationTypeHTML = $event['location_type'] === 'Online' 
                        ? '<span class="flex items-center text-[10px] font-semibold text-brandSecondary"><span class="w-1.5 h-1.5 rounded-full bg-brandSecondary mr-1 animate-pulse"></span> Online</span>' 
                        : '<span class="flex items-center text-[10px] font-semibold text-brandMuted"><i data-lucide="map-pin" class="w-3 h-3 mr-1 text-brandPrimary"></i> On-Campus</span>';
                        
                    $formattedDate = date('M d, Y', strtotime($event['date']));
                ?>
                    <div class="glass-card card-border-glow rounded-2xl overflow-hidden flex flex-col justify-between border border-brandBorder group relative">
                        <div>
                            <!-- Banner -->
                            <div class="zoom-container h-44 w-full relative border-b border-brandBorder">
                                <img src="<?php echo $event['banner']; ?>" alt="<?php echo htmlspecialchars($event['title']); ?> Banner" class="zoom-image w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-brandBgSec/90 via-transparent to-transparent"></div>
                                <div class="absolute top-4 left-4">
                                    <span class="px-2.5 py-1 rounded-md text-[9px] font-extrabold uppercase tracking-widest border <?php echo $badgeClass; ?>">
                                        <?php echo htmlspecialchars($event['category']); ?>
                                    </span>
                                </div>
                                <div class="absolute bottom-4 right-4">
                                    <?php echo $locationTypeHTML; ?>
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <div class="p-5 space-y-3">
                                <div class="space-y-1">
                                    <span class="text-[10px] text-brandPrimary font-medium tracking-wide block uppercase"><?php echo htmlspecialchars($event['college_name']); ?></span>
                                    <h3 class="text-lg font-bold font-outfit text-white group-hover:text-brandSecondary transition-colors duration-300"><?php echo htmlspecialchars($event['title']); ?></h3>
                                </div>
                                <p class="text-xs text-brandMuted leading-relaxed line-clamp-2"><?php echo htmlspecialchars($event['description']); ?></p>
                                
                                <div class="flex items-center justify-between text-[11px] text-brandMuted pt-2 border-t border-brandBorder/40">
                                    <div class="flex items-center gap-1">
                                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-brandSecondary"></i>
                                        <span><?php echo $formattedDate; ?></span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <i data-lucide="users" class="w-3.5 h-3.5 text-brandPrimary"></i>
                                        <span><?php echo $event['participants']; ?> Joined</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="p-5 pt-0 flex gap-3 mt-auto">
                            <button onclick="openDetailsModal('<?php echo $event['id']; ?>')" class="px-4 py-2 rounded-lg bg-brandBg border border-brandBorder hover:border-brandSecondary/40 text-[11px] font-semibold text-brandText transition-all">
                                Details
                            </button>
                            <?php if ($event['registration_open']): ?>
                                <button onclick="openRegistrationModal('<?php echo $event['id']; ?>')" class="flex-grow py-2 rounded-lg bg-brandSecondary text-brandBgSec hover:opacity-90 text-[11px] font-bold transition-opacity text-center shadow-md shadow-brandSecondary/20">
                                    Register Now
                                </button>
                            <?php else: ?>
                                <button class="flex-grow py-2 rounded-lg bg-brandBgSec border border-brandBorder text-[11px] font-semibold text-brandMuted cursor-not-allowed" disabled>
                                    Closed
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
    </div>
</main>

<!-- Injected JSON event list for instant modals logic -->
<script>
    window.eventsDatabase = <?php echo json_encode($events); ?>;
</script>

<!-- Link Modals -->
<?php include_once 'components/modals.php'; ?>

<!-- Link Application scripts -->
<script src="./assets/js/main.js"></script>

<?php
// 4. Load Footer
include_once 'components/footer.php';
?>
