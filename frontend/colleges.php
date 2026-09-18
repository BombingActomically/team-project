<?php
// C:\xampp\htdocs\Project2\frontend\colleges.php
$page_title = 'colleges';

// 1. Database Connection
$host = 'localhost';
$dbname = 'evenza';
$username = 'root'; 
$password = '';     

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("<div style='padding: 20px; font-family: sans-serif; background: #fee2e2; color: #991b1b; border: 1px solid #f87171; border-radius: 8px; margin: 20px;'>
        <strong>Database Connection Error:</strong> " . htmlspecialchars($e->getMessage()) . "
    </div>");
}

// 2. Capture Parameters
$search = trim($_GET['search'] ?? '');
$sort = $_GET['sort'] ?? 'name_asc';
$limit = (int)($_GET['limit'] ?? 6); 
if (!in_array($limit, [6, 9, 12, 15])) { $limit = 6; }
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// 3. Build Filter & Sorting SQL
$whereSql = "WHERE c.status = 'active'";
$params = [];

if (!empty($search)) {
    $whereSql .= " AND (c.name LIKE ? OR c.address LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$orderBy = "c.name ASC";
if ($sort === 'name_desc') {
    $orderBy = "c.name DESC";
} elseif ($sort === 'events_desc') {
    $orderBy = "events_count DESC";
} elseif ($sort === 'events_asc') {
    $orderBy = "events_count ASC";
}

// 4. Fetch Total Records for Pagination
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM colleges c $whereSql");
$countStmt->execute($params);
$totalRecords = $countStmt->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

// 5. Fetch Paginated Colleges (Bulletproof LIMIT/OFFSET Syntax)
$query = "
    SELECT 
        c.college_id as id,
        c.name,
        c.address as city,
        (SELECT COUNT(*) FROM events e WHERE e.college_id = c.college_id AND e.status = 'published') as events_count
    FROM colleges c
    $whereSql
    ORDER BY $orderBy
    LIMIT " . (int)$offset . ", " . (int)$limit . "
";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$colleges = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 6. Handle AJAX Request (Return only the grid container content and exit)
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    ob_start();
    ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if (count($colleges) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($colleges as $col): ?>
                    <div class="eb-card p-6 flex flex-col justify-between relative group overflow-hidden">
                        <div class="absolute -right-20 -bottom-20 w-44 h-44 rounded-full blur-2xl pointer-events-none transition-all duration-300" style="background: rgba(75,79,134,0.06);"></div>
                        
                        <div class="space-y-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-colors duration-300" style="background-color: var(--bg-alt); border: 1px solid var(--border-soft); color: var(--accent);">
                                <i data-lucide="landmark" class="w-6 h-6"></i>
                            </div>
                            
                            <div class="space-y-1">
                                <h3 class="text-lg font-bold font-outfit transition-colors duration-300" style="color: var(--text);">
                                    <?php echo htmlspecialchars($col['name']); ?>
                                </h3>
                                <p class="text-xs flex items-center" style="color: var(--text-dim);">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 mr-1" style="color: var(--accent);"></i>
                                    <?php echo htmlspecialchars($col['city']); ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between pt-6 mt-6" style="border-top: 1px solid var(--border-soft);">
                            <span class="text-xs font-medium" style="color: var(--text-dim);">
                                <strong class="font-bold" style="color: var(--text);"><?php echo htmlspecialchars($col['events_count']); ?></strong> Hosted Events
                            </span>
                            
                            <div class="flex items-center gap-2">
                                <button onclick="filterByCollege('<?php echo htmlspecialchars($col['id']); ?>')" class="eb-btn-fill">
                                    Events
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($totalPages > 1): ?>
                <div class="flex items-center justify-center gap-2 mt-12 pb-6">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <button 
                            type="button"
                            onclick="fetchColleges(<?php echo $i; ?>)"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all border <?php echo ($page === $i) ? 'bg-[#282B4A] text-[#EEEBDA] border-[#282B4A]' : 'bg-[#F7F4E9] text-[#282B4A] border-[rgba(40,43,74,0.14)] hover:border-[#4B4F86]'; ?>"
                        >
                            <?php echo $i; ?>
                        </button>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="eb-glass rounded-2xl p-12 text-center max-w-md mx-auto space-y-4 my-10" style="border-color: var(--border-soft);">
                <div class="w-16 h-16 rounded-full mx-auto flex items-center justify-center" style="background-color: var(--bg-alt); color: var(--accent);">
                    <i data-lucide="search-x" class="w-8 h-8"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold font-outfit" style="color: var(--text);">No Institutions Found</h3>
                    <p class="text-xs mt-1" style="color: var(--text-dim);">We couldn't find any partner colleges matching your search criteria.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php
    exit;
}

// 7. Fetch events list for modals on initial load (Only published events)
$eventQuery = "
    SELECT 
        e.event_id as id,
        e.title,
        e.description,
        e.event_date as date,
        e.venue,
        e.registration_deadline,
        e.event_type,
        e.registration_fee,
        e.college_id, 
        c.name as college_name,
        cat.name as category,
        (SELECT COUNT(*) FROM registrations r WHERE r.event_id = e.event_id AND r.status = 'approved') as participants
    FROM events e
    LEFT JOIN colleges c ON e.college_id = c.college_id
    LEFT JOIN categories cat ON e.category_id = cat.category_id
    WHERE e.status = 'published'
    ORDER BY e.event_date ASC
";
$eventStmt = $pdo->prepare($eventQuery);
$eventStmt->execute();
$rawEvents = $eventStmt->fetchAll(PDO::FETCH_ASSOC);

$allEvents = [];
$current_time = time();
$iconMap = [
    'Technical Events'     => 'laptop',
    'Cultural Events'      => 'music',
    'Sports Events'        => 'trophy',
    'Management Events'    => 'briefcase',
    'E-Sports & Gaming'    => 'gamepad-2',
    'Art & Design'         => 'palette',
    'Literary Events'      => 'book-open',
    'Workshops & Seminars' => 'lightbulb',
    'Social & Fun Events'  => 'users'
];

foreach ($rawEvents as $row) {
    $isOnline = (stripos($row['venue'] ?? '', 'online') !== false || stripos($row['venue'] ?? '', 'virtual') !== false);
    $categoryName = $row['category'] ?? 'General';
    
    $allEvents[] = [
        'id' => (string)$row['id'],
        'title' => $row['title'],
        'description' => $row['description'],
        'date' => $row['date'],
        'college_id' => (string)$row['college_id'],
        'college_name' => $row['college_name'] ?? 'Unknown College',
        'category' => $categoryName,
        'location_type' => $isOnline ? 'Online' : 'On-Campus',
        'event_type' => ucfirst($row['event_type']),
        'registration_fee' => (float)$row['registration_fee'] > 0 ? '₹' . number_format($row['registration_fee'], 2) : 'Free',
        'participants' => $row['participants'] ?? 0,
        'registration_open' => (strtotime($row['registration_deadline']) > $current_time),
        'icon' => $iconMap[$categoryName] ?? 'star'
    ];
}

// 8. Load Layout Components
include_once 'components/header.php';
include_once 'components/navbar.php';
?>

<!-- ============ EVENTRA DESIGN SYSTEM — Midnight Indigo / Vanilla Cream ============ -->
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

  body, html, main {
    background-color: var(--bg) !important;
    color: var(--text) !important;
  }
  header, nav, .navbar, .nav-container {
    background-color: var(--bg) !important;
    border-bottom: 1px solid var(--border-soft) !important;
  }
  .navbar-brand, .logo { color: var(--text) !important; }
  .navbar-brand span { color: var(--accent) !important; }
  .nav-link { color: var(--accent-dim) !important; }
  .nav-link:hover, .nav-link.active { color: var(--text) !important; }

  .btn-login {
    border-color: var(--accent) !important;
    color: var(--accent) !important;
    background: transparent !important;
  }
  .btn-login:hover { border-color: var(--text) !important; color: var(--text) !important; }
  .btn-register { background-color: var(--text) !important; color: var(--bg) !important; border: none !important; }
  .btn-register:hover { background-color: #363A5E !important; }

  .eb-eyebrow {
    display: inline-flex; align-items: center; gap: .5rem;
    padding: .4rem .9rem; border-radius: 9999px;
    background: rgba(75, 79, 134, 0.12);
    border: 1px solid var(--border-accent);
    color: var(--accent);
    font-size: .7rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
  }
  .eb-eyebrow .dot { width: .4rem; height: .4rem; border-radius: 9999px; background: var(--accent); flex-shrink: 0; }

  .eb-card { background: var(--surface); border: 1px solid var(--border-soft); border-radius: 1.25rem; transition: transform .25s ease, border-color .25s ease, box-shadow .25s ease; }
  .eb-card:hover { border-color: var(--border-accent); transform: translateY(-3px); box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12); }

  .eb-btn-fill { background: var(--text); color: var(--bg); transition: background .2s ease; font-size: 0.75rem; font-weight: 700; border-radius: .75rem; padding: .5rem 1rem; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; }
  .eb-btn-fill:hover { background: #363A5E; color: var(--bg); }
  
  .eb-input {
    background-color: var(--surface);
    border: 1px solid var(--border-soft);
    color: var(--text);
    border-radius: 0.85rem;
    padding: 0.65rem 1rem 0.65rem 2.5rem;
    font-size: 0.875rem;
    outline: none;
    transition: border-color 0.2s ease;
    width: 100%;
  }
  .eb-input:focus { border-color: var(--accent); }
  
  .eb-select {
    background-color: var(--surface);
    border: 1px solid var(--border-soft);
    color: var(--text);
    border-radius: 0.85rem;
    padding: 0.5rem 2rem 0.5rem 1rem;
    font-size: 0.875rem;
    outline: none;
    transition: border-color 0.2s ease;
  }
  .eb-select:focus { border-color: var(--accent); }
</style>

<!-- Main Layout Structure with Grid Background -->
<main class="relative z-10 overflow-hidden min-h-screen">
    
    <!-- SUBTLE GRID BACKGROUND -->
    <div class="absolute inset-0 z-[-1] pointer-events-none" 
         style="background-image: linear-gradient(to right, rgba(75,79,134,0.14) 1px, transparent 1px), linear-gradient(to bottom, rgba(75,79,134,0.14) 1px, transparent 1px); background-size: 50px 50px;">
    </div>

    <!-- Title Banner Section -->
    <section class="relative pt-32 pb-10 overflow-hidden" style="background-color: var(--bg-alt); border-bottom: 1px solid var(--border-soft);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
            <span class="eb-eyebrow">Partners</span>
            <h1 class="text-3xl sm:text-5xl font-extrabold font-outfit tracking-tight leading-none" style="color: var(--text);">
                Participating Institutions
            </h1>
            <p class="text-sm sm:text-base max-w-xl mx-auto font-normal leading-relaxed" style="color: var(--text-dim);">
                Discover leading universities and technical institutes hosting and competing in fests across our unified network.
            </p>
        </div>
    </section>

    <!-- Filters, Search, Limit, and Sorting Bar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10">
        <div id="colleges-filter-form" class="eb-card p-4 sm:p-6 flex flex-col md:flex-row items-center justify-between gap-4">
            
            <!-- Search Bar -->
            <div class="relative w-full md:w-1/3">
                <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-3.5" style="color: var(--accent);"></i>
                <input 
                    type="text" 
                    id="search-query" 
                    value="<?php echo htmlspecialchars($search); ?>" 
                    placeholder="Search institution name or city..." 
                    class="eb-input"
                >
            </div>

            <!-- Controls Group (Limit & Sorting) -->
            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto justify-end">
                
                <!-- Records Per Page Limit -->
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold" style="color: var(--text-dim);">Show:</span>
                    <select id="limit-query" class="eb-select cursor-pointer">
                        <option value="6" <?php if($limit == 6) echo 'selected'; ?>>6</option>
                        <option value="9" <?php if($limit == 9) echo 'selected'; ?>>9</option>
                        <option value="12" <?php if($limit == 12) echo 'selected'; ?>>12</option>
                        <option value="15" <?php if($limit == 15) echo 'selected'; ?>>15</option>
                    </select>
                </div>

                <!-- Sorting Option -->
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold" style="color: var(--text-dim);">Sort:</span>
                    <select id="sort-query" class="eb-select cursor-pointer">
                        <option value="name_asc" <?php if($sort === 'name_asc') echo 'selected'; ?>>Name (A-Z)</option>
                        <option value="name_desc" <?php if($sort === 'name_desc') echo 'selected'; ?>>Name (Z-A)</option>
                        <option value="events_desc" <?php if($sort === 'events_desc') echo 'selected'; ?>>Most Events</option>
                        <option value="events_asc" <?php if($sort === 'events_asc') echo 'selected'; ?>>Fewest Events</option>
                    </select>
                </div>

            </div>
        </div>
    </div>

    <!-- Colleges Grid Layout Wrapper (Updated via AJAX) -->
    <div id="colleges-results-wrapper" class="relative z-10 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <?php if (count($colleges) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($colleges as $col): ?>
                        <div class="eb-card p-6 flex flex-col justify-between relative group overflow-hidden">
                            <div class="absolute -right-20 -bottom-20 w-44 h-44 rounded-full blur-2xl pointer-events-none transition-all duration-300" style="background: rgba(75,79,134,0.06);"></div>
                            
                            <div class="space-y-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-colors duration-300" style="background-color: var(--bg-alt); border: 1px solid var(--border-soft); color: var(--accent);">
                                    <i data-lucide="landmark" class="w-6 h-6"></i>
                                </div>
                                
                                <div class="space-y-1">
                                    <h3 class="text-lg font-bold font-outfit transition-colors duration-300" style="color: var(--text);">
                                        <?php echo htmlspecialchars($col['name']); ?>
                                    </h3>
                                    <p class="text-xs flex items-center" style="color: var(--text-dim);">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 mr-1" style="color: var(--accent);"></i>
                                        <?php echo htmlspecialchars($col['city']); ?>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between pt-6 mt-6" style="border-top: 1px solid var(--border-soft);">
                                <span class="text-xs font-medium" style="color: var(--text-dim);">
                                    <strong class="font-bold" style="color: var(--text);"><?php echo htmlspecialchars($col['events_count']); ?></strong> Hosted Events
                                </span>
                                
                                <div class="flex items-center gap-2">
                                    <button onclick="filterByCollege('<?php echo htmlspecialchars($col['id']); ?>')" class="eb-btn-fill">
                                        Events
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination Links -->
                <?php if ($totalPages > 1): ?>
                    <div class="flex items-center justify-center gap-2 mt-12 pb-6">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <button 
                                type="button"
                                onclick="fetchColleges(<?php echo $i; ?>)"
                                class="px-4 py-2 rounded-xl text-xs font-bold transition-all border <?php echo ($page === $i) ? 'bg-[#282B4A] text-[#EEEBDA] border-[#282B4A]' : 'bg-[#F7F4E9] text-[#282B4A] border-[rgba(40,43,74,0.14)] hover:border-[#4B4F86]'; ?>"
                            >
                                <?php echo $i; ?>
                            </button>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <!-- Empty State -->
                <div class="eb-glass rounded-2xl p-12 text-center max-w-md mx-auto space-y-4 my-10" style="border-color: var(--border-soft);">
                    <div class="w-16 h-16 rounded-full mx-auto flex items-center justify-center" style="background-color: var(--bg-alt); color: var(--accent);">
                        <i data-lucide="search-x" class="w-8 h-8"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold font-outfit" style="color: var(--text);">No Institutions Found</h3>
                        <p class="text-xs mt-1" style="color: var(--text-dim);">We couldn't find any partner colleges matching your search criteria.</p>
                    </div>
                </div>
            <?php endif; ?>
            
        </div>
    </div>
</main>

<!-- Injected JSON event list for modals -->
<script>
    window.eventsDatabase = <?php echo json_encode($allEvents); ?>;

    let searchTimeout = null;

    function fetchColleges(page = 1) {
        const search = document.getElementById('search-query').value;
        const limit = document.getElementById('limit-query').value;
        const sort = document.getElementById('sort-query').value;

        const params = new URLSearchParams({
            search: search,
            limit: limit,
            sort: sort,
            page: page
        });

        // Update URL query parameters without reloading the page
        window.history.replaceState({}, '', `${window.location.pathname}?${params.toString()}`);

        // Perform AJAX Fetch
        fetch(`colleges.php?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('colleges-results-wrapper').innerHTML = html;
            if (window.lucide) {
                lucide.createIcons();
            }
        })
        .catch(error => console.error('AJAX error:', error));
    }

    // Attach Event Listeners on DOM load
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('search-query');
        const limitSelect = document.getElementById('limit-query');
        const sortSelect = document.getElementById('sort-query');

        // Real-time search with typing delay (debounce)
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                fetchColleges(1);
            }, 300);
        });

        limitSelect.addEventListener('change', () => fetchColleges(1));
        sortSelect.addEventListener('change', () => fetchColleges(1));
    });
</script>

<!-- Modals & Scripts -->
<?php include_once 'components/modals.php'; ?>
<script src="./assets/js/main.js?v=<?php echo time(); ?>"></script>

<?php
include_once 'components/footer.php';
?>