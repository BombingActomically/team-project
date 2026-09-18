<?php
// C:\xampp\htdocs\Project2\frontend\index.php
session_start(); 
$page_title = 'home';

// 1. Database Connection
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

// 2. DYNAMIC STATS 
$statsQuery = $pdo->query("SELECT 
    (SELECT COUNT(*) FROM students) as students_count,
    (SELECT COUNT(*) FROM colleges) as colleges_count,
    (SELECT COUNT(*) FROM events) as events_count
");
$stats = $statsQuery->fetch(PDO::FETCH_ASSOC);

$totalStudents = $stats['students_count'] ?? 0;
$totalColleges = $stats['colleges_count'] ?? 0;
$totalEvents   = $stats['events_count'] ?? 0;

// 3. MEGA EVENT FETCH
$megaEventQuery = $pdo->query("
    SELECT 
        e.*, 
        c.name as college_name, 
        cat.name as category_name,
        (SELECT COUNT(*) FROM registrations r WHERE r.event_id = e.event_id AND r.status = 'approved') as registered_count
    FROM events e
    LEFT JOIN colleges c ON e.college_id = c.college_id
    LEFT JOIN categories cat ON e.category_id = cat.category_id
    WHERE e.status != 'cancelled' 
    ORDER BY e.event_date DESC, e.start_time DESC 
    LIMIT 1
");
$megaEvent = $megaEventQuery->fetch(PDO::FETCH_ASSOC);

if ($megaEvent) {
    $megaTitle = $megaEvent['title'];
    $megaCollege = $megaEvent['college_name'];
    $megaDesc = $megaEvent['description'];
    $megaDate = date('M d, Y', strtotime($megaEvent['event_date']));
    $megaCategory = $megaEvent['category_name'];
    $megaVenue = $megaEvent['venue'] ? $megaEvent['venue'] : 'TBA';
    $isOnline = (stripos($megaVenue, 'online') !== false || stripos($megaVenue, 'virtual') !== false);
    $megaLocation = $isOnline ? 'Online' : 'On-Campus / ' . $megaVenue;
    $megaRegistered = $megaEvent['registered_count'];
    
    $megaTime = !empty($megaEvent['start_time']) ? $megaEvent['start_time'] : '00:00:00';
    $megaCountdownTarget = $megaEvent['event_date'] . 'T' . $megaTime; 
}

// 4. EVENTS DIRECTORY FETCH 
$query = "
    SELECT 
        e.event_id as id,
        e.title,
        e.description,
        e.event_date as date,
        e.venue,
        e.registration_deadline,
        e.event_type,
        e.registration_fee,
        c.name as college_name,
        cat.name as category,
        (SELECT COUNT(*) FROM registrations r WHERE r.event_id = e.event_id AND r.status = 'approved') as participants
    FROM events e
    LEFT JOIN colleges c ON e.college_id = c.college_id
    LEFT JOIN categories cat ON e.category_id = cat.category_id
    ORDER BY e.event_date ASC
";

$stmt = $pdo->prepare($query);
$stmt->execute();
$rawEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);

$upcomingEvents = [];
$current_time = time();

// Dynamic Category Images
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

foreach ($rawEvents as $row) {
    $isOnline = (stripos($row['venue'], 'online') !== false || stripos($row['venue'], 'virtual') !== false);
    $categoryName = $row['category'] ?? 'General';
    
    $upcomingEvents[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'description' => $row['description'],
        'date' => $row['date'],
        'college_name' => $row['college_name'] ?? 'Unknown College',
        'category' => $categoryName,
        'location_type' => $isOnline ? 'Online' : 'On-Campus',
        'event_type' => ucfirst($row['event_type']),
        'registration_fee' => (float)$row['registration_fee'] > 0 ? '₹' . number_format($row['registration_fee'], 2) : 'Free',
        'participants' => $row['participants'] ?? 0,
        'registration_open' => (strtotime($row['registration_deadline']) > $current_time),
        'image' => $imageMap[$categoryName] ?? $defaultImage 
    ];
}

$highlightEvents = array_slice($upcomingEvents, 0, 3);

include_once 'components/header.php';
include_once 'components/navbar.php';
?>

<style>
  :root {
    --bg: #EEEBDA;             
    --bg-alt: #E4DFC8;         
    --surface: #F7F4E9;        
    --surface-hover: #FFFFFF;  
    --text: #282B4A;           
    --text-dim: rgba(40, 43, 74, 0.68);   
    --text-faint: rgba(40, 43, 74, 0.45); 
    --accent: #4B4F86;         
    --accent-dim: rgba(75, 79, 134, 0.8);
    --border-soft: rgba(40, 43, 74, 0.14);
    --border-accent: rgba(75, 79, 134, 0.35);
  }
  
  body, html, main { background-color: var(--bg) !important; color: var(--text) !important; }
  header, nav, .navbar, .nav-container { background-color: var(--bg) !important; border-bottom: 1px solid var(--border-soft) !important; }
  .navbar-brand, .logo { color: var(--text) !important; }
  .navbar-brand span { color: var(--accent) !important; }
  .nav-link { color: var(--accent-dim) !important; }
  .nav-link:hover, .nav-link.active { color: var(--text) !important; }

  .eb-eyebrow {
    display: inline-flex; align-items: center; gap: .5rem;
    padding: .4rem .9rem; border-radius: 9999px;
    background: rgba(75, 79, 134, 0.12);
    border: 1px solid var(--border-accent);
    color: var(--accent);
    font-size: .7rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
  }
  .eb-eyebrow .dot { width: .4rem; height: .4rem; border-radius: 9999px; background: #FF7E67; flex-shrink: 0; }

  .eb-btn-primary {
    display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
    padding: 0.85rem 1.75rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.875rem;
    background: var(--text); color: var(--bg);
    box-shadow: 0 4px 14px rgba(40, 43, 74, 0.2);
    transition: transform .25s ease, background .25s ease;
  }
  .eb-btn-primary:hover { transform: translateY(-2px); background: #363A5E; }

  .eb-btn-secondary {
    display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
    padding: 0.85rem 1.75rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.875rem;
    background: var(--surface); color: var(--text);
    border: 1px solid var(--border-soft);
    transition: transform .25s ease, border-color .25s ease, background .25s ease;
  }
  .eb-btn-secondary:hover { transform: translateY(-2px); border-color: var(--border-accent); background: var(--surface-hover); }

  .eb-card { background: var(--surface); border: 1px solid var(--border-soft); border-radius: 1.25rem; }
  .eb-stat-value { font-size: 1.5rem; font-weight: 800; color: var(--text); }
  .eb-stat-label { font-size: .68rem; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: var(--accent-dim); margin-top: .25rem; }

  .eb-btn-fill { background: var(--text); color: var(--bg); transition: background .2s ease; }
  .eb-btn-fill:hover { background: #363A5E; }
  .eb-btn-ghost { border: 1px solid var(--border-accent); color: var(--text); background: transparent; transition: background .2s ease, border-color .2s ease; }
  .eb-btn-ghost:hover { background: rgba(75, 79, 134, 0.08); border-color: var(--accent); }
  .eb-btn-disabled { background: var(--bg-alt); border: 1px solid var(--border-soft); color: var(--text-faint); }
  
  .event-card { transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease; }
  .event-card:hover { box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1); transform: translateY(-4px); }
</style>

<main class="relative z-10 overflow-hidden min-h-screen">
    
    <div class="absolute inset-0 z-[-1] pointer-events-none" 
         style="background-image: linear-gradient(to right, rgba(75,79,134,0.14) 1px, transparent 1px), linear-gradient(to bottom, rgba(75,79,134,0.14) 1px, transparent 1px); background-size: 50px 50px;">
    </div>
    
    <?php include_once 'components/hero.php'; ?>
    
    <?php if ($megaEvent): ?>
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="mb-8">
            <span class="eb-eyebrow mb-4"><span class="dot"></span> Mega Event</span>
            <h2 class="text-3xl sm:text-4xl font-bold font-outfit tracking-tight text-[var(--text)]">Featured Highlights</h2>
            <p class="mt-2 text-sm text-[var(--text-dim)] max-w-2xl">Don't miss the biggest upcoming inter-college championship of the year. Register before slots fill up!</p>
        </div>

        <div class="eb-card shadow-xl overflow-hidden p-8 md:p-12 relative">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <span class="bg-[var(--bg-alt)] text-[var(--text)] px-3 py-1 rounded font-bold text-xs uppercase tracking-widest border border-[var(--border-soft)]">
                            <?= $megaCategory ?>
                        </span>
                        <span class="text-[var(--text-dim)] text-xs font-semibold flex items-center gap-1.5">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> <?= $megaLocation ?>
                        </span>
                    </div>

                    <h3 class="text-4xl md:text-5xl font-extrabold font-outfit text-[var(--text)] mb-3 leading-tight"><?= $megaTitle ?></h3>
                    <p class="text-sm font-semibold text-[var(--accent)] mb-6">Organized by <?= $megaCollege ?></p>
                    <p class="text-[var(--text-dim)] text-sm leading-relaxed mb-10 max-w-lg">
                        <?= $megaDesc ?>
                    </p>

                    <div class="grid grid-cols-3 gap-6 mb-10 border-t border-[var(--border-soft)] pt-6">
                        <div>
                            <div class="eb-stat-label flex items-center gap-1.5 mb-1"><i data-lucide="award" class="w-3.5 h-3.5"></i> Prize Pool</div>
                            <div class="eb-stat-value">₹50,000</div>
                        </div>
                        <div>
                            <div class="eb-stat-label flex items-center gap-1.5 mb-1"><i data-lucide="users" class="w-3.5 h-3.5"></i> Registered</div>
                            <div class="eb-stat-value"><?= $megaRegistered ?> <span class="text-lg text-[var(--text-faint)]">/ 1000</span></div>
                        </div>
                        <div>
                            <div class="eb-stat-label flex items-center gap-1.5 mb-1"><i data-lucide="calendar" class="w-3.5 h-3.5"></i> Event Date</div>
                            <div class="eb-stat-value text-xl mt-1"><?= $megaDate ?></div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-4">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <a href="event-register.php?id=<?= $megaEvent['event_id'] ?>" class="eb-btn-primary">
                                <i data-lucide="edit-3" class="w-4 h-4"></i> Register Now
                            </a>
                        <?php else: ?>
                            <a href="login.php" class="eb-btn-primary">
                                <i data-lucide="log-in" class="w-4 h-4"></i> Login to Register
                            </a>
                        <?php endif; ?>
                        
                        <a href="event.php?id=<?= $megaEvent['event_id'] ?>" class="eb-btn-secondary">
                            View Details &rarr;
                        </a>
                    </div>
                </div>

                <div class="lg:pl-10">
                    <div class="bg-[var(--surface-hover)] border border-[var(--border-soft)] rounded-2xl p-8 md:p-10 shadow-sm text-center">
                        <p class="text-[var(--accent)] text-xs font-bold uppercase tracking-widest mb-6">Registration Closes In</p>
                        
                        <div id="mega-countdown" data-target="<?= $megaCountdownTarget ?>" class="grid grid-cols-4 gap-3 md:gap-4 mb-8">
                            <div class="bg-[var(--bg-alt)] border border-[var(--border-soft)] rounded-xl py-5 px-2 flex flex-col items-center shadow-inner">
                                <span id="cd-mega-days" class="text-3xl font-extrabold text-[var(--text)]">00</span>
                                <span class="text-[9px] font-bold text-[var(--text-dim)] uppercase tracking-widest mt-2">Days</span>
                            </div>
                            <div class="bg-[var(--bg-alt)] border border-[var(--border-soft)] rounded-xl py-5 px-2 flex flex-col items-center shadow-inner">
                                <span id="cd-mega-hours" class="text-3xl font-extrabold text-[var(--text)]">00</span>
                                <span class="text-[9px] font-bold text-[var(--text-dim)] uppercase tracking-widest mt-2">Hours</span>
                            </div>
                            <div class="bg-[var(--bg-alt)] border border-[var(--border-soft)] rounded-xl py-5 px-2 flex flex-col items-center shadow-inner">
                                <span id="cd-mega-mins" class="text-3xl font-extrabold text-[var(--text)]">00</span>
                                <span class="text-[9px] font-bold text-[var(--text-dim)] uppercase tracking-widest mt-2">Mins</span>
                            </div>
                            <div class="bg-[var(--bg-alt)] border border-[var(--border-soft)] rounded-xl py-5 px-2 flex flex-col items-center shadow-inner">
                                <span id="cd-mega-secs" class="text-3xl font-extrabold text-[var(--text)]">00</span>
                                <span class="text-[9px] font-bold text-[var(--text-dim)] uppercase tracking-widest mt-2">Secs</span>
                            </div>
                        </div>

                        <div class="border-t border-[var(--border-soft)] pt-5">
                            <p class="text-[10px] font-semibold text-[var(--text-dim)] flex items-center justify-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-[var(--accent)]"></span> Limited to first 1000 registrations
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Upcoming Events Showcase Section -->
    <section class="relative py-12 overflow-hidden border-t border-[var(--border-soft)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="flex flex-col md:flex-row items-start md:items-end justify-between mb-12">
                <div>
                    <span class="eb-eyebrow">Quick Discovery</span>
                    <h2 class="text-3xl sm:text-4xl font-bold font-outfit mt-4 tracking-tight" style="color: var(--text);">
                        Upcoming Highlights
                    </h2>
                    <p class="mt-3 text-sm max-w-xl leading-relaxed" style="color: var(--text-dim);">
                        A quick preview of the nearest competitions and fests scheduling. Expand your search on the events directory.
                    </p>
                </div>
                <a href="events.php" class="eb-btn-secondary mt-6 md:mt-0">
                    Explore Directory <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($highlightEvents as $event): 
                    
                    $locationTypeHTML = $event['location_type'] === 'Online' 
                        ? '<span class="flex items-center text-[10px] font-bold text-[#282B4A] uppercase tracking-wider"><span class="w-2 h-2 rounded-full bg-red-500 mr-1.5 animate-pulse"></span> Online</span>' 
                        : '<span class="flex items-center text-[10px] font-bold text-[#282B4A] uppercase tracking-wider"><i data-lucide="map-pin" class="w-3 h-3 mr-1.5 text-[#282B4A]"></i> On-Campus</span>';
                        
                    $formattedDate = !empty($event['date']) ? date('M d, Y', strtotime($event['date'])) : 'TBA';
                ?>
                    <div class="event-card eb-card overflow-hidden flex flex-col justify-between group relative" style="border-color: var(--border-soft);" onmouseover="this.style.borderColor='var(--border-accent)'" onmouseout="this.style.borderColor='var(--border-soft)'">
                        <div>
                            <div class="h-48 w-full relative flex items-center justify-center overflow-hidden group border-b border-[var(--border-soft)] bg-[var(--text)]">
                                <img src="<?php echo $event['image']; ?>" alt="Event Image" class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-700 ease-in-out">
                                
                                <div class="absolute inset-0 bg-gradient-to-t from-[var(--text)]/80 to-transparent pointer-events-none"></div>

                                <div class="absolute top-4 left-4 z-10">
                                    <span class="px-3 py-1 rounded text-[10px] font-bold uppercase tracking-widest border border-transparent bg-[#EEEBDA]/95 text-[#282B4A] shadow-sm backdrop-blur">
                                        <?php echo htmlspecialchars($event['category']); ?>
                                    </span>
                                </div>
                                <div class="absolute bottom-4 right-4 bg-[#EEEBDA]/95 px-2 py-1 rounded backdrop-blur-sm border border-[#282B4A]/10 shadow-lg z-10">
                                    <?php echo $locationTypeHTML; ?>
                                </div>
                            </div>
                            
                            <div class="p-6 space-y-4">
                                <div class="space-y-1.5">
                                    <span class="text-[10px] font-bold tracking-widest block uppercase" style="color: var(--accent);"><?php echo htmlspecialchars($event['college_name']); ?></span>
                                    <h3 class="text-xl font-bold font-outfit leading-tight" style="color: var(--text);"><?php echo htmlspecialchars($event['title']); ?></h3>
                                </div>
                                <p class="text-sm leading-relaxed line-clamp-2" style="color: var(--text-dim);"><?php echo htmlspecialchars($event['description']); ?></p>
                                
                                <div class="flex items-center justify-between text-xs font-semibold pt-4" style="color: var(--text); border-top: 1px solid var(--border-soft);">
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="calendar" class="w-4 h-4" style="color: var(--accent);"></i>
                                        <span><?php echo $formattedDate; ?></span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="users" class="w-4 h-4" style="color: var(--accent);"></i>
                                        <span><?php echo $event['participants']; ?> Joined</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6 pt-0 flex gap-3 mt-auto">
                            <a href="event.php?id=<?php echo $event['id']; ?>" class="eb-btn-ghost flex justify-center items-center flex-grow py-2.5 rounded text-xs font-bold uppercase tracking-wider">
                                Details
                            </a>
                            
                            <?php if ($event['registration_open']): ?>
                                <?php if(isset($_SESSION['user_id'])): ?>
                                    <a href="event-register.php?id=<?php echo $event['id']; ?>" class="eb-btn-fill flex justify-center items-center flex-grow py-2.5 rounded text-xs font-bold uppercase tracking-wider shadow-lg">
                                        Register Now
                                    </a>
                                <?php else: ?>
                                    <a href="login.php" class="eb-btn-fill flex justify-center items-center flex-grow py-2.5 rounded text-xs font-bold uppercase tracking-wider shadow-lg">
                                        Login to Register
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <button class="eb-btn-disabled flex-grow py-2.5 rounded text-xs font-bold cursor-not-allowed uppercase tracking-wider" disabled>
                                    Closed
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php include_once 'components/how-it-works.php'; ?>
    <?php include_once 'components/statistics.php'; ?>
    
</main>

<script>
    window.eventsDatabase = <?php echo json_encode($upcomingEvents); ?>;
    window.isUserLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
    window.platformStats = {
        students: <?php echo $totalStudents; ?>,
        colleges: <?php echo $totalColleges; ?>,
        events: <?php echo $totalEvents; ?>
    };

    document.addEventListener("DOMContentLoaded", function() {
        const megaCd = document.getElementById("mega-countdown");
        if (megaCd) {
            const targetDate = new Date(megaCd.getAttribute("data-target")).getTime();
            setInterval(function() {
                const now = new Date().getTime();
                const distance = targetDate - now;
                if (distance < 0) return;
                
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("cd-mega-days").innerText = days.toString().padStart(2, '0');
                document.getElementById("cd-mega-hours").innerText = hours.toString().padStart(2, '0');
                document.getElementById("cd-mega-mins").innerText = minutes.toString().padStart(2, '0');
                document.getElementById("cd-mega-secs").innerText = seconds.toString().padStart(2, '0');
            }, 1000);
        }
    });
</script>

<?php include_once 'components/modals.php'; ?>
<script src="./assets/js/main.js?v=<?php echo time(); ?>"></script>
<?php include_once 'components/footer.php'; ?>