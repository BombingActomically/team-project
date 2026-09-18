<?php
// C:\xampp\htdocs\Project2\frontend\college-detail.php
session_start();
$page_title = 'colleges';

$host = 'localhost';
$dbname = 'evenza';
$username = 'root'; 
$password = '';     

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch and sanitize college ID
$collegeId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch college metadata from database
$stmt = $pdo->prepare("SELECT * FROM colleges WHERE college_id = :id AND status = 'active' LIMIT 1");
$stmt->execute(['id' => $collegeId]);
$college = $stmt->fetch(PDO::FETCH_ASSOC);

// Redirect if invalid/missing ID
if (!$college) {
    header('Location: colleges.php');
    exit;
}

// Filter events hosted ONLY by this college from DB
$eventsStmt = $pdo->prepare("
    SELECT e.*, c.name as category_name, cl.name as college_name,
           (SELECT COUNT(*) FROM registrations r WHERE r.event_id = e.event_id AND r.status = 'approved') as participants
    FROM events e
    LEFT JOIN categories c ON e.category_id = c.category_id
    LEFT JOIN colleges cl ON e.college_id = cl.college_id
    WHERE e.college_id = :cid AND e.status != 'cancelled'
    ORDER BY e.event_date ASC
");
$eventsStmt->execute(['cid' => $collegeId]);
$collegeEvents = $eventsStmt->fetchAll(PDO::FETCH_ASSOC);

include_once 'components/header.php';
include_once 'components/navbar.php';

// Category Image Mapping
$imageMap = [
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
$defaultImage = 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=800&q=80';
?>

<!-- College Header Profile block -->
<section class="relative pt-32 pb-16 overflow-hidden bg-[var(--bg-alt)] border-b border-[var(--border-soft)]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="bg-[var(--surface)] border border-[var(--border-soft)] rounded-3xl p-6 sm:p-10 flex flex-col md:flex-row items-center md:items-start gap-8 shadow-xl">
            <div class="w-20 h-20 rounded-2xl bg-[var(--bg)] border border-[var(--border-soft)] flex items-center justify-center text-[var(--accent)] shadow-md flex-shrink-0">
                <i data-lucide="building-2" class="w-10 h-10"></i>
            </div>
            
            <div class="space-y-4 text-center md:text-left flex-grow">
                <div class="space-y-1">
                    <span class="px-3 py-1 rounded-full bg-[var(--accent)]/10 border border-[var(--border-accent)] text-[10px] font-bold uppercase tracking-widest text-[var(--accent)]">
                        Verified Host Campus
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-bold font-outfit text-[var(--text)] leading-tight">
                        <?php echo htmlspecialchars($college['name']); ?>
                    </h1>
                    <p class="text-sm text-[var(--text-dim)] flex items-center justify-center md:justify-start">
                        <i data-lucide="map-pin" class="w-4 h-4 mr-1 text-[var(--accent)]"></i>
                        <?php echo htmlspecialchars($college['address'] ?: 'Campus Location'); ?>
                    </p>
                </div>
                
                <p class="text-xs text-[var(--text-dim)] max-w-xl leading-relaxed">
                    <?php echo htmlspecialchars($college['name']); ?> is a premier educational institution committed to hosting elite inter-collegiate competitions and technical fests.
                </p>
                
                <div class="flex flex-wrap gap-4 justify-center md:justify-start pt-2 border-t border-[var(--border-soft)]">
                    <div class="text-xs text-[var(--text-dim)]">
                        🏛️ Campus Events: <strong class="text-[var(--text)]"><?php echo count($collegeEvents); ?></strong> listed
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Hosted Events List -->
<main class="relative z-10 overflow-hidden min-h-screen py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10 text-center md:text-left">
            <h2 class="text-2xl sm:text-3xl font-bold font-outfit text-[var(--text)]">
                Hosted Competitions & Activities
            </h2>
            <p class="text-[var(--text-dim)] text-xs mt-1">
                Browse through all active registrations hosted by <?php echo htmlspecialchars($college['name']); ?>.
            </p>
        </div>
        
        <?php if (count($collegeEvents) === 0): ?>
            <div class="flex flex-col items-center justify-center py-20 text-center space-y-4 bg-[var(--surface)] rounded-2xl border border-[var(--border-soft)] max-w-md mx-auto shadow-sm">
                <div class="p-4 rounded-full bg-[var(--bg-alt)] border border-[var(--border-soft)] text-[var(--accent)] flex items-center justify-center">
                    <i data-lucide="calendar-x" class="w-12 h-12"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold font-outfit text-[var(--text)]">No Events Listed</h3>
                    <p class="text-[var(--text-dim)] text-sm mt-1 max-w-xs mx-auto">
                        This college does not have any active upcoming events registered currently.
                    </p>
                </div>
                <a href="colleges.php" class="px-5 py-2.5 bg-[var(--text)] text-[var(--bg)] rounded-xl text-xs font-bold transition-all shadow-md">
                    Return to Colleges List
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($collegeEvents as $event): 
                    $categoryName = $event['category_name'] ?? 'General';
                    $eventBanner = $imageMap[$categoryName] ?? $defaultImage;
                    $formattedDate = !empty($event['event_date']) ? date('M d, Y', strtotime($event['event_date'])) : 'TBA';
                    $isRegOpen = (empty($event['registration_deadline']) || strtotime($event['registration_deadline']) > time());
                ?>
                    <div class="bg-[var(--surface)] rounded-2xl overflow-hidden flex flex-col justify-between border border-[var(--border-soft)] group shadow-sm hover:shadow-md transition-shadow">
                        <div>
                            <div class="h-44 w-full relative border-b border-[var(--border-soft)] overflow-hidden">
                                <img src="<?= $eventBanner ?>" alt="<?= htmlspecialchars($event['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute top-4 left-4">
                                    <span class="px-2.5 py-1 rounded-md text-[9px] font-extrabold uppercase tracking-widest bg-[var(--bg)] text-[var(--text)] border border-[var(--border-soft)]">
                                        <?= htmlspecialchars($categoryName) ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="p-5 space-y-3">
                                <div class="space-y-1">
                                    <span class="text-[10px] text-[var(--accent)] font-bold tracking-wide block uppercase"><?= htmlspecialchars($college['name']) ?></span>
                                    <h3 class="text-lg font-bold font-outfit text-[var(--text)] group-hover:text-[var(--accent)] transition-colors"><?= htmlspecialchars($event['title']) ?></h3>
                                </div>
                                <p class="text-xs text-[var(--text-dim)] leading-relaxed line-clamp-2"><?= htmlspecialchars($event['description']) ?></p>
                                
                                <div class="flex items-center justify-between text-[11px] text-[var(--text-dim)] pt-2 border-t border-[var(--border-soft)]">
                                    <div class="flex items-center gap-1">
                                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-[var(--accent)]"></i>
                                        <span><?= $formattedDate ?></span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <i data-lucide="users" class="w-3.5 h-3.5 text-[var(--accent)]"></i>
                                        <span><?= $event['participants'] ?> Joined</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-5 pt-0 flex gap-3 mt-auto">
                            <a href="event.php?id=<?= $event['event_id'] ?>" class="flex-grow py-2 rounded-lg bg-[var(--text)] text-[var(--bg)] hover:bg-[#363A5E] text-[11px] font-bold transition-colors text-center shadow-sm">
                                View Details & Register
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>

<?php include_once 'components/footer.php'; ?>