<?php
// C:\xampp\htdocs\Project2\Admin\admin-inquiries.php

// Hide harmless PHP notices that break the HTML layout
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

include 'auth_check.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* =========================================================
   DATABASE CONNECTION
   ========================================================= */
$DB_HOST = '127.0.0.1';
$DB_PORT = '3306';
$DB_NAME = 'evenza';
$DB_USER = 'root';
$DB_PASS = '';

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed.');
}

/* =========================================================
   DATA FETCHING & STATS
   ========================================================= */
$stmt = $pdo->query("SELECT * FROM inquiries ORDER BY submitted_at DESC");
$inquiries = $stmt->fetchAll();

$total = count($inquiries);
$partnerships = count(array_filter($inquiries, fn($i) => $i['subject'] === 'partnership'));
$bugs = count(array_filter($inquiries, fn($i) => $i['subject'] === 'technical_bug'));

// Subject formatting map [Label, Badge Class]
$subjectMap = [
    'event_registration' => ['Event Registration', 'primary'],
    'partnership'        => ['College Partnership', 'success'],
    'technical_bug'      => ['Technical Bug', 'danger'],
    'other'              => ['Other Inquiry', 'secondary']
];
?>

<!doctype html>
<html lang="en">

<head>
    <title>Inquiries Management | Evenza Admin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons (Matching alluniversity.php exactly) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        body { background-color: #f5f7fb; }
        .page-title { font-weight: 600; color: #1f2937; }
        .page-subtitle { color: #6b7280; font-size: 14px; }
        .custom-breadcrumb { display: flex; align-items: center; gap: 12px; list-style: none; padding: 0; margin: 0; font-size: 14px; }
        .custom-breadcrumb li { color: #6b7280; }
        .custom-breadcrumb li a { text-decoration: none; color: #4f46e5; }
        .custom-breadcrumb li:not(:last-child)::after { content: "/"; margin-left: 12px; color: #adb5bd; }
        .stat-card { border: 0; border-radius: 14px; transition: 0.3s ease; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important; }
        .stat-icon { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 22px; }
        .icon-primary { background: #e8edff; color: #4f46e5; }
        .icon-success { background: #e7f8ef; color: #198754; }
        .icon-danger { background: #fdecec; color: #dc3545; }
        .main-card { border: 0; border-radius: 16px; overflow: hidden; }
        .main-card-header { background: #ffffff; padding: 20px 24px; border-bottom: 1px solid #edf0f5; }
        .search-box { position: relative; }
        .search-box i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; }
        .search-box input { padding-left: 40px; border-radius: 10px; }
        .table thead th { background: #f8f9fc; color: #6b7280; font-size: 13px; font-weight: 600; white-space: nowrap; padding: 15px; }
        .table tbody td { padding: 15px; vertical-align: middle; color: #374151; }
        .table tbody tr { transition: 0.2s ease; }
        .table tbody tr:hover { background-color: #f8faff; }
        .sender-name { font-weight: 600; color: #1f2937; }
        .action-btn { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; }
        .page-link { cursor: pointer; }
    </style>

    <!-- Dark Theme Overrides (Matching alluniversity.php exactly) -->
    <style>
        :root{ --evenza-bg:#0D1117; --evenza-card:#161B22; --evenza-border:rgba(255,255,255,0.07); --evenza-accent:#22C55E; --evenza-accent-soft:rgba(34,197,94,0.12); }
        [data-pc-theme="dark"] body { background: var(--evenza-bg) !important; }
        [data-pc-theme="dark"] .pc-container { background: var(--evenza-bg) !important; }
        [data-pc-theme="dark"] .card, [data-pc-theme="dark"] .main-card { background-color: var(--evenza-card) !important; border-color: var(--evenza-border) !important; }
        [data-pc-theme="dark"] .main-card-header, [data-pc-theme="dark"] .card-footer, [data-pc-theme="dark"] .bg-light { background-color: transparent !important; border-bottom: 1px solid var(--evenza-border) !important; border-top: 1px solid var(--evenza-border) !important; }
        [data-pc-theme="dark"] .page-title, [data-pc-theme="dark"] .sender-name, [data-pc-theme="dark"] h5, [data-pc-theme="dark"] h6, [data-pc-theme="dark"] .text-dark, [data-pc-theme="dark"] .fw-semibold, [data-pc-theme="dark"] strong { color: #E6EDF3 !important; }
        [data-pc-theme="dark"] .text-muted, [data-pc-theme="dark"] .page-subtitle, [data-pc-theme="dark"] .custom-breadcrumb li, [data-pc-theme="dark"] .custom-breadcrumb li a, [data-pc-theme="dark"] #showingCountText { color: #8B949E !important; }
        [data-pc-theme="dark"] .table { --bs-table-bg: transparent !important; color: #E6EDF3 !important; }
        [data-pc-theme="dark"] .table th, [data-pc-theme="dark"] .table td { background-color: transparent !important; border-bottom: 1px solid var(--evenza-border) !important; color: #E6EDF3 !important; }
        [data-pc-theme="dark"] .table thead th { background-color: var(--evenza-bg) !important; color: #8B949E !important; border-bottom: 1px solid rgba(255,255,255,0.1) !important; }
        [data-pc-theme="dark"] .table tbody tr:hover td { background-color: rgba(255, 255, 255, 0.04) !important; }
        [data-pc-theme="dark"] .form-control, [data-pc-theme="dark"] .form-select { background-color: #0D1117 !important; border-color: var(--evenza-border) !important; color: #E6EDF3 !important; color-scheme: dark !important; }
        [data-pc-theme="dark"] .action-btn { background-color: #232B36 !important; border-color: #232B36 !important; color: #E6EDF3 !important; }
        [data-pc-theme="dark"] .action-btn:hover { background-color: #303B4A !important; }
        [data-pc-theme="dark"] .modal-content { background-color: var(--evenza-card) !important; color: #E6EDF3 !important; border-color: var(--evenza-border) !important; }
        [data-pc-theme="dark"] .modal-header, [data-pc-theme="dark"] .modal-footer { border-color: var(--evenza-border) !important; }
        [data-pc-theme="dark"] .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
        [data-pc-theme="dark"] .page-link { background-color: #0D1117 !important; border-color: var(--evenza-border) !important; color: #E6EDF3 !important; }
        [data-pc-theme="dark"] .page-item.active .page-link { background-color: var(--evenza-accent) !important; border-color: var(--evenza-accent) !important; color: #04170C !important; }
        [data-pc-theme="dark"] .stat-icon.icon-primary { background: rgba(88,166,255,0.12); color: #58A6FF; }
        [data-pc-theme="dark"] .stat-icon.icon-success { background: rgba(34,197,94,0.12); color: #22C55E; }
        [data-pc-theme="dark"] .stat-icon.icon-danger { background: rgba(248,81,73,0.12); color: #F85149; }
        .dropdown-menu .dropdown-item[data-value="default"], .dropdown-menu .dropdown-item[onclick*="default"] { display: none !important; }
    </style>
</head>

<body>
    <!-- Preloader -->
    <div class="loader-bg fixed inset-0 bg-white dark:bg-themedark-cardbg z-[1034]">
        <div class="loader-track h-[5px] w-full inline-block absolute overflow-hidden top-0">
            <div class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0"></div>
        </div>
    </div>

    <!-- Sidebar & Header -->
    <?php include_once("Sidebar.php"); ?>
    <?php include_once("Header.php"); ?>

    <!-- Main Content -->
    <div class="pc-container">
        <div class="pc-content">

            <!-- Page Header -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="page-title mb-1">Platform Inquiries</h4>
                    <p class="page-subtitle mb-3">Manage messages submitted via the frontend contact form</p>
                    <ul class="custom-breadcrumb">
                        <li><a href="Dashboard.php">Home</a></li>
                        <li>Platform Management</li>
                        <li>Inquiries</li>
                    </ul>
                </div>
            </div>

            <!-- Statistics -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon icon-primary me-3"><i class="bi bi-inbox-fill"></i></div>
                            <div>
                                <small class="text-muted">Total Inquiries</small>
                                <h4 class="mb-0 mt-1"><?= $total ?></h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon icon-success me-3"><i class="bi bi-building-check"></i></div>
                            <div>
                                <small class="text-muted">Partnership Requests</small>
                                <h4 class="mb-0 mt-1"><?= $partnerships ?></h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon icon-danger me-3"><i class="bi bi-bug-fill"></i></div>
                            <div>
                                <small class="text-muted">Bug Reports</small>
                                <h4 class="mb-0 mt-1"><?= $bugs ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inquiries Table -->
            <div class="card main-card shadow-sm">
                <div class="main-card-header">
                    <div class="row align-items-center g-3">
                        <div class="col-md-5">
                            <h5 class="mb-1">Contact Submissions</h5>
                            <small class="text-muted">View and reply to incoming messages</small>
                        </div>
                        <div class="col-md-7">
                            <div class="row g-2 justify-content-md-end">
                                <div class="col-md-7">
                                    <div class="search-box">
                                        <i class="bi bi-search"></i>
                                        <input type="text" class="form-control" id="searchInquiry" placeholder="Search name, email, or institution...">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <select class="form-select" id="subjectFilter">
                                        <option value="">All Subjects</option>
                                        <option value="event_registration">Event Registration Query</option>
                                        <option value="partnership">College Partnership</option>
                                        <option value="technical_bug">Technical Bug / Issue</option>
                                        <option value="other">Other Inquiry</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="inquiryTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sender Details</th>
                                    <th>Institution</th>
                                    <th>Subject</th>
                                    <th>Preview</th>
                                    <th>Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($total === 0): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="bi bi-envelope-open text-muted" style="font-size: 2rem;"></i><br>
                                            No inquiries received yet.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($inquiries as$i => $row):$subData = $subjectMap[$row['subject']] ?? ['Unknown', 'secondary'];
                                        $subLabel =$subData[0];
                                        $subClass =$subData[1];
                                        $snippet = mb_strimwidth($row['message'], 0, 45, '...');
                                    ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td>
                                                <div class="sender-name"><?= htmlspecialchars($row['full_name']) ?></div>
                                                <a href="mailto:<?= htmlspecialchars($row['email']) ?>" class="text-muted" style="font-size: 12px; text-decoration: none;">
                                                    <?= htmlspecialchars($row['email']) ?>
                                                </a>
                                            </td>
                                            <td><span class="text-muted small"><?= htmlspecialchars($row['college']) ?></span></td>
                                            <td data-subject="<?= htmlspecialchars($row['subject']) ?>">
                                                <span class="badge bg-<?= $subClass ?>-subtle text-<?= $subClass ?> border border-<?= $subClass ?>-subtle px-2 py-1">
                                                    <?= $subLabel ?>
                                                </span>
                                            </td>
                                            <td><span class="text-muted small fst-italic">"<?= htmlspecialchars($snippet) ?>"</span></td>
                                            <td>
                                                <?= date('M d, Y', strtotime($row['submitted_at'])) ?><br>
                                                <small class="text-muted"><?= date('h:i A', strtotime($row['submitted_at'])) ?></small>
                                            </td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-light action-btn me-1" title="View Message" data-bs-toggle="modal" data-bs-target="#viewModal<?= (int)$row['inquiry_id'] ?>">
                                                    <i class="bi bi-eye text-primary"></i>
                                                </button>
                                                <a href="mailto:<?= htmlspecialchars($row['email']) ?>?subject=Re: <?=$subLabel ?>" class="btn btn-light action-btn" title="Reply via Email">
                                                    <i class="bi bi-reply text-success"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination Footer -->
                <div class="card-footer bg-white border-top py-3">
                    <div class="row align-items-center g-3">
                        <div class="col-md-6 col-12">
                            <div class="d-flex align-items-center gap-2">
                                <small class="text-muted text-nowrap">Show</small>
                                <select class="form-select form-select-sm w-auto" id="limitSelect" style="font-size: 12px; padding-top: 2px; padding-bottom: 2px;">
                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                </select>
                                <small class="text-muted text-nowrap me-2">entries</small>
                                <span class="text-muted opacity-50">|</span>
                                <small class="text-muted ms-2" id="showingCountText">Showing 0 of 0 messages</small>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <nav aria-label="Table pagination">
                                <ul class="pagination pagination-sm mb-0 justify-content-md-end justify-content-center" id="pagination"></ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- VIEW INQUIRY MODALS -->
    <?php foreach ($inquiries as $row):$subData = $subjectMap[$row['subject']] ?? ['Unknown', 'secondary'];
    ?>
        <div class="modal fade" id="viewModal<?= (int)$row['inquiry_id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title d-flex align-items-center gap-2">
                            <i class="bi bi-envelope-open text-primary"></i> Message Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body pt-3">
                        <div class="bg-light p-3 rounded mb-3 border">
                            <div class="row g-2 text-sm">
                                <div class="col-4 text-muted fw-semibold text-xs text-uppercase tracking-wider">From:</div>
                                <div class="col-8 fw-semibold text-dark"><?= htmlspecialchars($row['full_name']) ?> <br><a href="mailto:<?= htmlspecialchars($row['email']) ?>" class="text-primary small fw-normal"><?= htmlspecialchars($row['email']) ?></a></div>
                                
                                <div class="col-4 text-muted fw-semibold text-xs text-uppercase tracking-wider mt-2">Institution:</div>
                                <div class="col-8 mt-2 text-dark"><?= htmlspecialchars($row['college']) ?></div>
                                
                                <div class="col-4 text-muted fw-semibold text-xs text-uppercase tracking-wider mt-2">Subject:</div>
                                <div class="col-8 mt-2"><span class="badge bg-<?= $subData[1] ?>-subtle text-<?=$subData[1] ?> border border-<?= $subData[1] ?>-subtle px-2 py-1"><?= $subData[0] ?></span></div>
                                
                                <div class="col-4 text-muted fw-semibold text-xs text-uppercase tracking-wider mt-2">Received:</div>
                                <div class="col-8 mt-2 text-dark small"><?= date('F d, Y h:i A', strtotime($row['submitted_at'])) ?></div>
                            </div>
                        </div>
                        
                        <div class="text-muted fw-semibold text-xs text-uppercase tracking-wider mb-2">Message Body:</div>
                        <div class="p-3 border rounded" style="background-color: var(--evenza-bg); color: var(--evenza-text); min-height: 100px; white-space: pre-wrap; font-size: 14px;">
                            <?= htmlspecialchars($row['message']) ?>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <a href="mailto:<?= htmlspecialchars($row['email']) ?>?subject=Re: <?=$subData[0] ?>" class="btn btn-primary d-flex align-items-center gap-2">
                            <i class="bi bi-reply"></i> Reply via Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Footer & Scripts (Matching alluniversity.php exactly) -->
    <?php include_once("Footer.php"); ?>

    <script src="assets/js/plugins/simplebar.min.js"></script>
    <script src="assets/js/plugins/popper.min.js"></script>
    <script src="assets/js/icon/custom-icon.js"></script>
    <script src="assets/js/plugins/feather.min.js"></script>
    <script src="assets/js/component.js"></script>
    <script src="assets/js/theme.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Search, Filter & Pagination Logic -->
    <script>
        const searchInput = document.getElementById("searchInquiry");
        const subjectFilter = document.getElementById("subjectFilter");
        const limitSelect = document.getElementById("limitSelect");
        const rows = document.querySelectorAll("#inquiryTable tbody tr");
        const showingCountText = document.getElementById("showingCountText");
        const paginationContainer = document.getElementById("pagination");

        let currentPage = 1;

        function filterInquiries() {
            const searchValue = searchInput.value.toLowerCase();
            const subjectValue = subjectFilter.value.toLowerCase();
            const limitValue = limitSelect.value;

            const matchedRows = [];
            rows.forEach(row => {
                if (row.cells.length < 7) return; 

                const rowText = row.innerText.toLowerCase();
                const subjectAttr = row.cells[3].getAttribute("data-subject");
                const subjectRaw = subjectAttr ? subjectAttr.toLowerCase().trim() : "";

                const matchesSearch = rowText.includes(searchValue);
                const matchesSubject = subjectValue === "" || subjectRaw === subjectValue;

                if (matchesSearch && matchesSubject) {
                    matchedRows.push(row);
                } else {
                    row.style.display = "none";
                }
            });

            const totalMatched = matchedRows.length;
            let pageSize = parseInt(limitValue, 10);
            if (pageSize <= 0) pageSize = 1;

            const totalPages = Math.ceil(totalMatched / pageSize) || 1;

            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;

            const startIdx = (currentPage - 1) * pageSize;
            const endIdx = startIdx + pageSize;

            matchedRows.forEach((row, idx) => {
                if (idx >= startIdx && idx < endIdx) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });

            const visibleCount = Math.min(pageSize, totalMatched - startIdx > 0 ? totalMatched - startIdx : 0);
            showingCountText.textContent = `Showing ${visibleCount} of ${totalMatched} messages`;

            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            paginationContainer.innerHTML = "";
            if (totalPages <= 1) return;

            if (currentPage > 1) {
                const prevLi = document.createElement("li");
                prevLi.className = "page-item";
                prevLi.innerHTML = `<a class="page-link"><i class="bi bi-chevron-left"></i> Prev</a>`;
                prevLi.addEventListener("click", () => { currentPage--; filterInquiries(); });
                paginationContainer.appendChild(prevLi);
            }

            const currentLi = document.createElement("li");
            currentLi.className = "page-item active";
            currentLi.innerHTML = `<a class="page-link">${currentPage}</a>`;
            paginationContainer.appendChild(currentLi);

            if (currentPage < totalPages) {
                const nextLi = document.createElement("li");
                nextLi.className = "page-item";
                nextLi.innerHTML = `<a class="page-link">Next <i class="bi bi-chevron-right"></i></a>`;
                nextLi.addEventListener("click", () => { currentPage++; filterInquiries(); });
                paginationContainer.appendChild(nextLi);
            }
        }

        searchInput.addEventListener("keyup", () => { currentPage = 1; filterInquiries(); });
        subjectFilter.addEventListener("change", () => { currentPage = 1; filterInquiries(); });
        limitSelect.addEventListener("change", () => { currentPage = 1; filterInquiries(); });

        document.addEventListener("DOMContentLoaded", filterInquiries);
    </script>

    <!-- Theme Control Setup -->
    <script>
        layout_change('false');
        layout_theme_sidebar_change('dark');
        change_box_container('false');
        layout_caption_change('true');
        layout_rtl_change('false');
        preset_change('preset-1');
        main_layout_change('vertical');
        
        document.addEventListener('DOMContentLoaded', function() {
            function removeDefaultOption() {
                document.querySelectorAll('.dropdown-item').forEach(item => {
                    if (item.textContent.trim().toLowerCase() === 'default' || item.textContent.trim().toLowerCase().includes('default')) {
                        item.style.display = 'none';
                    }
                });
            }
            removeDefaultOption();
            const observer = new MutationObserver(removeDefaultOption);
            observer.observe(document.body, { childList: true, subtree: true });
        });
    </script>

</body>
</html>