<!-- C:\xampp\htdocs\Project2\frontend\components\filters.php -->
<?php
// 1. Ensure Database Connection exists for the filters
if (!isset($pdo)) {
    $host = 'localhost';
    $dbname = 'evenza';
    $username = 'root';
    $password = '';
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Database connection failed in filters: " . $e->getMessage());
    }
}

// 2. Dynamically fetch active Colleges
if (!isset($colleges)) {
    $stmtCol = $pdo->query("SELECT college_id as id, name FROM colleges WHERE status = 'active' ORDER BY name ASC");
    $colleges = $stmtCol->fetchAll(PDO::FETCH_ASSOC);
}

// 3. Dynamically fetch active Categories and assign Emojis
if (!isset($categories)) {
    $stmtCat = $pdo->query("SELECT category_id, name FROM categories WHERE status = 'active' ORDER BY name ASC");
    $dbCategories = $stmtCat->fetchAll(PDO::FETCH_ASSOC);
    
    // Fallback mapping for category emojis
    $emojiMap = [
        'Technical Events' => '💻',
        'Cultural Events' => '🎭',
        'Sports Events' => '🏆',
        'Management Events' => '📊',
        'E-Sports & Gaming' => '🎮',
        'Art & Design' => '🎨',
        'Literary Events' => '📚',
        'Workshops & Seminars' => '🛠️',
        'Social & Fun Events' => '🎉'
    ];
    
    $categories = [];
    foreach ($dbCategories as $cat) {
        $categories[] = [
            'name' => $cat['name'],
            'emoji' => $emojiMap[$cat['name']] ?? '✨'
        ];
    }
}
?>

<style>
    /* Clean CSS for dynamic tab switching to replace inline styles */
    .eb-category-tab {
        background-color: var(--surface) !important;
        color: var(--text-dim) !important;
        border-color: var(--border-soft) !important;
    }
    .eb-category-tab:hover {
        color: var(--text) !important;
        border-color: var(--border-accent) !important;
    }
    /* When JavaScript adds .tab-active, this overrides the default */
    .eb-category-tab.tab-active {
        background-color: var(--text) !important;
        color: var(--bg) !important;
        border-color: var(--text) !important;
    }
</style>

<div class="eb-glass rounded-2xl p-6 mb-8 shadow-xl relative z-20" style="background-color: var(--surface); border-color: var(--border-soft);">
    <div class="space-y-6">
        
        <!-- Search and Inputs Grid -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <!-- Search Text Input -->
            <div class="md:col-span-5 relative">
                <i data-lucide="search" class="w-5 h-5 absolute left-4 top-3.5" style="color: var(--accent-dim);"></i>
                <input 
                    type="text" 
                    id="search-input" 
                    placeholder="Search event title, description or tags..." 
                    class="w-full pl-12 pr-4 py-3 rounded-xl focus:outline-none transition-all duration-300 text-sm font-medium"
                    style="background-color: var(--bg); border: 1px solid var(--border-soft); color: var(--text);"
                    onfocus="this.style.borderColor='var(--accent)'"
                    onblur="this.style.borderColor='var(--border-soft)'"
                >
            </div>
            
            <!-- College Filter Dropdown -->
            <div class="md:col-span-3 relative">
                <i data-lucide="graduation-cap" class="w-5 h-5 absolute left-4 top-3.5" style="color: var(--accent-dim);"></i>
                <select 
                    id="college-filter" 
                    class="w-full pl-12 pr-8 py-3 rounded-xl focus:outline-none transition-all duration-300 text-sm font-medium appearance-none cursor-pointer"
                    style="background-color: var(--bg); border: 1px solid var(--border-soft); color: var(--text);"
                    onfocus="this.style.borderColor='var(--accent)'"
                    onblur="this.style.borderColor='var(--border-soft)'"
                >
                    <option value="">All Colleges</option>
                    <?php foreach ($colleges as $col): ?>
                        <option value="<?php echo htmlspecialchars($col['id']); ?>">
                            <?php echo htmlspecialchars($col['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <i data-lucide="chevron-down" class="w-4 h-4 absolute right-4 top-4 pointer-events-none" style="color: var(--accent-dim);"></i>
            </div>
            
            <!-- Date Filter Input -->
            <div class="md:col-span-2 relative">
                <i data-lucide="calendar" class="w-5 h-5 absolute left-4 top-3.5" style="color: var(--accent-dim);"></i>
                <input 
                    type="date" 
                    id="date-filter" 
                    class="w-full pl-12 pr-4 py-3 rounded-xl focus:outline-none transition-all duration-300 text-sm font-medium cursor-pointer"
                    style="background-color: var(--bg); border: 1px solid var(--border-soft); color: var(--text);"
                    onfocus="this.style.borderColor='var(--accent)'"
                    onblur="this.style.borderColor='var(--border-soft)'"
                >
            </div>

            <!-- Sort By Selector -->
            <div class="md:col-span-2 relative">
                <i data-lucide="sliders-horizontal" class="w-5 h-5 absolute left-4 top-3.5" style="color: var(--accent-dim);"></i>
                <select 
                    id="sort-filter" 
                    class="w-full pl-12 pr-8 py-3 rounded-xl focus:outline-none transition-all duration-300 text-sm font-medium appearance-none cursor-pointer"
                    style="background-color: var(--bg); border: 1px solid var(--border-soft); color: var(--text);"
                    onfocus="this.style.borderColor='var(--accent)'"
                    onblur="this.style.borderColor='var(--border-soft)'"
                >
                    <option value="upcoming">Soonest Date</option>
                    <option value="popularity">Most Popular</option>
                    <option value="title">Alphabetical</option>
                </select>
                <i data-lucide="chevron-down" class="w-4 h-4 absolute right-4 top-4 pointer-events-none" style="color: var(--accent-dim);"></i>
            </div>
        </div>
        
        <!-- Horizontal Tabs -->
        <div class="pt-5" style="border-top: 1px solid var(--border-soft);">
            
            <!-- Mobile Dropdown -->
            <div class="block lg:hidden relative">
                <i data-lucide="filter" class="w-5 h-5 absolute left-4 top-3.5" style="color: var(--accent-dim);"></i>
                <select 
                    id="category-mobile-select" 
                    class="w-full pl-12 pr-8 py-3 rounded-xl focus:outline-none transition-all duration-300 text-sm font-medium appearance-none cursor-pointer"
                    style="background-color: var(--bg); border: 1px solid var(--border-soft); color: var(--text);"
                >
                    <option value="all">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat['name']); ?>">
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <i data-lucide="chevron-down" class="w-4 h-4 absolute right-4 top-4 pointer-events-none" style="color: var(--accent-dim);"></i>
            </div>

            <!-- Horizontal Tab buttons (Desktop / Tablet) -->
            <div class="hidden lg:flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
                <button 
                    onclick="setCategoryFilter('all')" 
                    data-category="all"
                    class="category-tab eb-category-tab tab-active px-5 py-2.5 rounded-xl border text-sm font-semibold transition-all duration-300 whitespace-nowrap"
                >
                    ✨ All Events
                </button>
                <?php foreach ($categories as $cat): ?>
                    <button 
                        onclick="setCategoryFilter('<?php echo htmlspecialchars($cat['name']); ?>')" 
                        data-category="<?php echo htmlspecialchars($cat['name']); ?>"
                        class="category-tab eb-category-tab px-5 py-2.5 rounded-xl border text-sm font-semibold transition-all duration-300 whitespace-nowrap"
                    >
                        <?php echo $cat['emoji'] . ' ' . htmlspecialchars($cat['name']); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Filter helper status text -->
        <div id="filter-status" class="text-xs font-medium flex items-center justify-between" style="color: var(--text-dim);">
            <div>Showing <span id="filtered-count" class="font-bold" style="color: var(--accent);">10</span> events</div>
            <button id="clear-filters-btn" class="hidden hover:text-opacity-80 transition-colors font-semibold flex items-center gap-1" style="color: var(--text);">
                <i data-lucide="x" class="w-3.5 h-3.5"></i> Clear all filters
            </button>
        </div>
        
    </div>
</div>