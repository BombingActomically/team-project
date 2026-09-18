<?php
// C:\xampp\htdocs\Project2\frontend\events.php
session_start();
$page_title = 'events';

// 1. Database Connection and Dynamic Data Fetching
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

// Fixed status filter to display all events except cancelled ones
$query = "
    SELECT 
        e.event_id as id,
        e.college_id,
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
    WHERE e.status != 'cancelled'
    ORDER BY e.event_date ASC
";

$stmt = $pdo->prepare($query);
$stmt->execute();
$rawEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);

$allEvents = [];
$current_time = time();

// Stunning Unsplash Placeholders mapped to Categories
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
    $isOnline = (stripos($row['venue'] ?? '', 'online') !== false || stripos($row['venue'] ?? '', 'virtual') !== false);
    $categoryName = $row['category'] ?? 'General';
    
    $allEvents[] = [
        'id' => (string)$row['id'],
        'college_id' => (string)$row['college_id'],
        'title' => $row['title'],
        'description' => $row['description'],
        'date' => $row['date'],
        'college_name' => $row['college_name'] ?? 'Unknown College',
        'category' => $categoryName,
        'location_type' => $isOnline ? 'Online' : 'On-Campus',
        'event_type' => ucfirst($row['event_type']),
        'registration_fee' => (float)$row['registration_fee'] > 0 ? '₹' . number_format($row['registration_fee'], 2) : 'Free',
        'participants' => $row['participants'] ?? 0,
        'registration_open' => (empty($row['registration_deadline']) || strtotime($row['registration_deadline']) > $current_time),
        'image' => $imageMap[$categoryName] ?? $defaultImage
    ];
}

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
  .eb-eyebrow .dot { width: .4rem; height: .4rem; border-radius: 9999px; background: var(--accent); flex-shrink: 0; }

  .eb-card { background: var(--surface); border: 1px solid var(--border-soft); border-radius: 1.25rem; }
  .eb-btn-fill { background: var(--text); color: var(--bg); transition: background .2s ease; }
  .eb-btn-fill:hover { background: #363A5E; }
  .eb-btn-ghost { border: 1px solid var(--border-accent); color: var(--text); background: transparent; transition: background .2s ease, border-color .2s ease; }
  .eb-btn-ghost:hover { background: rgba(75, 79, 134, 0.08); border-color: var(--accent); }
  .eb-btn-disabled { background: var(--bg-alt); border: 1px solid var(--border-soft); color: var(--text-faint); }
  
  .event-card { transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease; }
  .event-card:hover { box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35); }
</style>

<main class="relative z-10 overflow-hidden min-h-screen">
    
    <div class="absolute inset-0 z-[-1] pointer-events-none" 
         style="background-image: linear-gradient(to right, rgba(75,79,134,0.14) 1px, transparent 1px), linear-gradient(to bottom, rgba(75,79,134,0.14) 1px, transparent 1px); background-size: 50px 50px;">
    </div>

    <section class="relative pt-32 pb-10 overflow-hidden" style="background-color: var(--bg-alt); border-bottom: 1px solid var(--border-soft);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
            <span class="eb-eyebrow">Directory</span>
            <h1 class="text-3xl sm:text-5xl font-extrabold font-outfit tracking-tight leading-none" style="color: var(--text);">Events Portal</h1>
            <p class="text-sm sm:text-base max-w-xl mx-auto font-normal leading-relaxed" style="color: var(--text-dim);">
                Search, filter, and discover coding battles, hackathons, sports championships, workshops, and cultural events happening across all partner campuses.
            </p>
        </div>
    </section>

    <div class="relative z-10">
        <?php include_once 'components/event-grid.php'; ?>
    </div>
    
</main>

<script>
    // PASSING DATA AND AUTH STATE TO JAVASCRIPT
    window.eventsDatabase = <?php echo json_encode($allEvents); ?>;
    window.isUserLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
</script>

<?php include_once 'components/modals.php'; ?>
<script src="./assets/js/main.js?v=<?php echo time(); ?>"></script>
<?php include_once 'components/footer.php'; ?>