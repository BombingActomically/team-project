<?php
// C:\xampp\htdocs\Project2\frontend\event.php
session_start();

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

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("
    SELECT e.*, c.name as college_name, cat.name as category_name,
           (SELECT COUNT(*) FROM registrations r WHERE r.event_id = e.event_id AND r.status = 'approved') as registered_count
    FROM events e
    LEFT JOIN colleges c ON e.college_id = c.college_id
    LEFT JOIN categories cat ON e.category_id = cat.category_id
    WHERE e.event_id = :id AND e.status != 'cancelled'
");
$stmt->execute(['id' => $event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    $page_title = 'Event Not Found';
    include_once 'components/header.php';
    include_once 'components/navbar.php';
    echo '<main class="min-h-screen py-32 text-center"><h1 class="text-3xl font-bold font-outfit text-[#282B4A]">Event Not Found</h1><p class="text-sm text-[rgba(40,43,74,0.68)] mt-2">The event you are looking for does not exist or has been removed.</p><a href="index.php" class="inline-block mt-6 px-6 py-2.5 rounded-xl bg-[#282B4A] text-[#EEEBDA] text-xs font-bold uppercase tracking-wider">Return Home</a></main>';
    include_once 'components/footer.php';
    exit();
}

$page_title = $event['title'];
include_once 'components/header.php';
include_once 'components/navbar.php';

$isOnline = (stripos($event['venue'], 'online') !== false || stripos($event['venue'], 'virtual') !== false);
$location = $isOnline ? 'Online' : 'On-Campus / ' . ($event['venue'] ?: 'TBA');
$eventDate = date('M d, Y', strtotime($event['event_date']));
$fee = (float)$event['registration_fee'] > 0 ? '₹' . number_format($event['registration_fee'], 2) : 'Free';
$isRegistrationOpen = (strtotime($event['registration_deadline']) > time());

// Category Image Mapping
$imageMap = [
    'Technical Events'     => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1200&q=80',
    'Cultural Events'      => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=1200&q=80',
    'Sports Events'        => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&w=1200&q=80',
    'Management Events'    => 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1200&q=80',
    'E-Sports & Gaming'    => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1200&q=80',
    'Art & Design'         => 'https://images.unsplash.com/photo-1460661419201-fd4cecdf8a8b?auto=format&fit=crop&w=1200&q=80',
    'Literary Events'      => 'https://images.unsplash.com/photo-1474366521946-c3d4b507abf2?auto=format&fit=crop&w=1200&q=80',
    'Workshops & Seminars' => 'https://images.unsplash.com/photo-1544928147-79a2dbc1f389?auto=format&fit=crop&w=1200&q=80',
    'Social & Fun Events'  => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=1200&q=80'
];
$categoryName = $event['category_name'] ?? 'General';
$eventImage = $imageMap[$categoryName] ?? 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1200&q=80';
?>

<style>
  :root {
    --bg: #EEEBDA;             
    --surface: #F7F4E9;        
    --text: #282B4A;           
    --text-dim: rgba(40, 43, 74, 0.68);   
    --accent: #4B4F86;         
    --border-soft: rgba(40, 43, 74, 0.14);
  }
  body, html, main { background-color: var(--bg) !important; color: var(--text) !important; }
</style>

<main class="relative z-10 min-h-screen py-24 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
    <div class="absolute inset-0 z-[-1] pointer-events-none" 
         style="background-image: linear-gradient(to right, rgba(75,79,134,0.08) 1px, transparent 1px), linear-gradient(to bottom, rgba(75,79,134,0.08) 1px, transparent 1px); background-size: 50px 50px;">
    </div>

    <div class="mb-6">
        <a href="index.php" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-[var(--accent)] hover:underline">
            &larr; Back to Home
        </a>
    </div>

    <div class="bg-[var(--surface)] border border-[var(--border-soft)] rounded-3xl overflow-hidden shadow-xl">
        <!-- Event Banner Image -->
        <div class="h-64 sm:h-80 w-full relative overflow-hidden bg-[var(--text)]">
            <img src="<?= $eventImage ?>" alt="<?= htmlspecialchars($event['title']) ?>" class="absolute inset-0 w-full h-full object-cover opacity-85">
            <div class="absolute inset-0 bg-gradient-to-t from-[var(--text)]/80 via-transparent to-transparent"></div>
            
            <div class="absolute bottom-6 left-6 right-6 flex flex-wrap items-center justify-between gap-4">
                <span class="bg-[#EEEBDA]/95 text-[#282B4A] px-3.5 py-1 rounded-md font-bold text-xs uppercase tracking-widest backdrop-blur-sm shadow-sm">
                    <?= htmlspecialchars($categoryName) ?>
                </span>
                <span class="bg-[#EEEBDA]/95 text-[#282B4A] px-3.5 py-1 rounded-md font-bold text-xs uppercase tracking-widest backdrop-blur-sm shadow-sm flex items-center gap-1.5">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> <?= htmlspecialchars($location) ?>
                </span>
            </div>
        </div>

        <div class="p-8 md:p-12">
            <h1 class="text-3xl md:text-4xl font-bold font-outfit text-[var(--text)] mb-2"><?= htmlspecialchars($event['title']) ?></h1>
            <p class="text-sm font-semibold text-[var(--accent)] mb-8">Organized by <?= htmlspecialchars($event['college_name'] ?? 'Unknown College') ?></p>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-6 rounded-2xl bg-[var(--bg)] border border-[var(--border-soft)] mb-8">
                <div>
                    <span class="block text-[9px] uppercase font-bold text-[var(--text-dim)] mb-1">Event Date</span>
                    <span class="text-sm font-bold font-mono text-[var(--text)]"><?= $eventDate ?></span>
                </div>
                <div>
                    <span class="block text-[9px] uppercase font-bold text-[var(--text-dim)] mb-1">Registration Fee</span>
                    <span class="text-sm font-bold font-mono text-[var(--text)]"><?= $fee ?></span>
                </div>
                <div>
                    <span class="block text-[9px] uppercase font-bold text-[var(--text-dim)] mb-1">Participants</span>
                    <span class="text-sm font-bold font-mono text-[var(--accent)]"><?= $event['registered_count'] ?> Registered</span>
                </div>
                <div>
                    <span class="block text-[9px] uppercase font-bold text-[var(--text-dim)] mb-1">Deadline</span>
                    <span class="text-sm font-bold font-mono text-[var(--text)]"><?= date('M d, Y', strtotime($event['registration_deadline'])) ?></span>
                </div>
            </div>

            <div class="mb-10">
                <h2 class="text-lg font-bold font-outfit mb-3 text-[var(--text)]">About This Event</h2>
                <p class="text-[var(--text-dim)] text-sm leading-relaxed whitespace-pre-line"><?= htmlspecialchars($event['description']) ?></p>
            </div>

            <div class="flex flex-wrap items-center gap-4 pt-6 border-t border-[var(--border-soft)]">
                <?php if ($isRegistrationOpen): ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="event-register.php?id=<?= $event['event_id'] ?>" class="bg-[var(--text)] text-[var(--bg)] px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider shadow-md hover:bg-[#363A5E] transition-colors">
                            Register Now
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="bg-[var(--text)] text-[var(--bg)] px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider shadow-md hover:bg-[#363A5E] transition-colors">
                            Login to Register
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <button class="bg-[var(--bg)] text-[var(--text-dim)] border border-[var(--border-soft)] px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider cursor-not-allowed" disabled>
                        Registration Closed
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>

<?php include_once 'components/footer.php'; ?>