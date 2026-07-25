<?php
/**
 * teammembers.php
 * View all students registered as team members across events and teams.
 */

include 'auth_check.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* =========================================================
   DB CONNECTION
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
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed.');
}

$PHOTO_WEB_PATH = 'assets/images/students/profiles/';

/* =========================================================
   FETCH DATA
   ========================================================= */
$sql = '
    SELECT 
        tm.team_member_id,
        tm.team_id,
        s.student_id,
        s.name AS student_name,
        s.enrollment_no,
        s.email,
        s.profile_photo,
        t.team_name,
        t.leader_id,
        e.title AS event_title,
        c.name AS college_name
    FROM team_members tm
    JOIN students s ON tm.student_id = s.student_id
    JOIN teams t ON tm.team_id = t.team_id
    JOIN events e ON t.event_id = e.event_id
    LEFT JOIN colleges c ON s.college_id = c.college_id
    ORDER BY t.created_at DESC, s.name ASC
';

$members = $pdo->query($sql)->fetchAll();
$total   = count($members);

$events = $pdo->query("SELECT DISTINCT title FROM events WHERE event_type = 'team' ORDER BY title ASC")->fetchAll(PDO::FETCH_COLUMN);
$teams  = $pdo->query("SELECT DISTINCT team_name FROM teams ORDER BY team_name ASC")->fetchAll(PDO::FETCH_COLUMN);
?>

<!doctype html>
<html lang="en">

<head>
    <title>Team Members | Evenza Admin</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        body { background-color: #f5f7fb; }
        .page-title { font-weight: 600; color: #1f2937; }
        .page-subtitle { color: #6b7280; font-size: 14px; }
        .custom-breadcrumb { display: flex; align-items: center; gap: 12px; list-style: none; padding: 0; margin: 0; font-size: 14px; }
        .custom-breadcrumb li { color: #6b7280; }
        .custom-breadcrumb li a { text-decoration: none; color: #4f46e5; }
        .custom-breadcrumb li:not(:last-child)::after { content: "/"; margin-left: 12px; color: #adb5bd; }
        .main-card { border: 0; border-radius: 16px; overflow: hidden; }
        .main-card-header { background: #ffffff; padding: 20px 24px; border-bottom: 1px solid #edf0f5; }
        .search-box { position: relative; }
        .search-box i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; }
        .search-box input { padding-left: 40px; border-radius: 10px; }
        .table thead th { background: #f8f9fc; color: #6b7280; font-size: 13px; font-weight: 600; white-space: nowrap; padding: 15px; }
        .table tbody td { padding: 15px; vertical-align: middle; color: #374151; }
        .table tbody tr { transition: 0.2s ease; }
        .table tbody tr:hover { background-color: #f8faff; }
        .member-avatar { width: 44px; height: 44px; object-fit: cover; border-radius: 12px; border: 1px solid #e5e7eb; background: #f8f9fa; }
        .action-btn { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; }
        @media (max-width: 768px) {
            .main-card-header { padding: 16px; }
            .table { min-width: 900px; }
        }
    </style>
</head>

<body>

    <div class="loader-bg fixed inset-0 bg-white dark:bg-themedark-cardbg z-[1034]">
        <div class="loader-track h-[5px] w-full inline-block absolute overflow-hidden top-0">
            <div class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0"></div>
        </div>
    </div>

    <?php include_once("Sidebar.php"); ?>
    <?php include_once("Header.php"); ?>

    <div class="pc-container">
        <div class="pc-content">

            <!-- Page Header -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="page-title mb-1">Team Members</h4>
                    <p class="page-subtitle mb-3">View all registered student members across teams</p>
                    <ul class="custom-breadcrumb">
                        <li><a href="Index.php">Home</a></li>
                        <li>Team Management</li>
                        <li>Team Members</li>
                    </ul>
                </div>
            </div>

            <!-- Team Members Table -->
            <div class="card main-card shadow-sm">

                <div class="main-card-header">
                    <div class="row align-items-center g-3">
                        <div class="col-md-4">
                            <h5 class="mb-1">Member Directory</h5>
                            <small class="text-muted">Total: <?= $total ?> members</small>
                        </div>
                        <div class="col-md-8">
                            <div class="row g-2 justify-content-md-end">
                                <div class="col-md-4">
                                    <select class="form-select" id="eventFilter">
                                        <option value="">All Events</option>
                                        <?php foreach ($events as $evTitle): ?>
                                            <option value="<?= htmlspecialchars($evTitle) ?>"><?= htmlspecialchars($evTitle) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select" id="teamFilter">
                                        <option value="">All Teams</option>
                                        <?php foreach ($teams as $tName): ?>
                                            <option value="<?= htmlspecialchars($tName) ?>"><?= htmlspecialchars($tName) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <div class="search-box">
                                        <i class="bi bi-search"></i>
                                        <input type="text" class="form-control" id="searchMember" placeholder="Search member...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="membersTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Photo</th>
                                    <th>Student Name</th>
                                    <th>Enrollment No</th>
                                    <th>Team Name</th>
                                    <th>Event</th>
                                    <th>College</th>
                                    <th>Role</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($total === 0): ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            No team members registered yet.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($members as $i => $m): ?>
                                        <?php 
                                            $isLeader = (int)$m['student_id'] === (int)$m['leader_id']; 
                                            $photoSrc = !empty($m['profile_photo']) ? $PHOTO_WEB_PATH . htmlspecialchars($m['profile_photo']) : 'assets/images/user/avatar-1.jpg';
                                        ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td>
                                                <img src="<?= $photoSrc ?>" class="member-avatar" alt="Photo">
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-dark"><?= htmlspecialchars($m['student_name']) ?></div>
                                                <small class="text-muted"><?= htmlspecialchars($m['email']) ?></small>
                                            </td>
                                            <td><?= htmlspecialchars($m['enrollment_no'] ?? 'N/A') ?></td>
                                            <td>
                                                <span class="fw-semibold text-primary"><?= htmlspecialchars($m['team_name']) ?></span>
                                            </td>
                                            <td><?= htmlspecialchars($m['event_title']) ?></td>
                                            <td><?= htmlspecialchars($m['college_name'] ?? 'N/A') ?></td>
                                            <td>
                                                <?php if ($isLeader): ?>
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-7"><i class="bi bi-star-fill me-1"></i> Leader</span>
                                                <?php else: ?>
                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-7">Member</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <a href="teamdetails.php?id=<?= (int)$m['team_id'] ?>" class="btn btn-light action-btn" title="View Team Details">
                                                    <i class="bi bi-eye text-info"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center">
                    <small class="text-muted">Showing <?= $total ?> of <?= $total ?> members</small>
                </div>

            </div>

        </div>
    </div>

    <?php include_once("Footer.php"); ?>

    <script src="assets/js/plugins/simplebar.min.js"></script>
    <script src="assets/js/plugins/popper.min.js"></script>
    <script src="assets/js/icon/custom-icon.js"></script>
    <script src="assets/js/plugins/feather.min.js"></script>
    <script src="assets/js/component.js"></script>
    <script src="assets/js/theme.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const searchInput = document.getElementById("searchMember");
        const eventFilter = document.getElementById("eventFilter");
        const teamFilter = document.getElementById("teamFilter");
        const rows = document.querySelectorAll("#membersTable tbody tr");

        function filterMembers() {
            const query = searchInput.value.toLowerCase();
            const selectedEvent = eventFilter.value.toLowerCase();
            const selectedTeam = teamFilter.value.toLowerCase();

            rows.forEach(row => {
                if (row.cells.length < 9) return;
                const text = row.innerText.toLowerCase();
                const eventText = row.cells[5].innerText.toLowerCase().trim();
                const teamText = row.cells[4].innerText.toLowerCase().trim();

                const matchesSearch = text.includes(query);
                const matchesEvent = selectedEvent === "" || eventText === selectedEvent;
                const matchesTeam = selectedTeam === "" || teamText === selectedTeam;

                row.style.display = (matchesSearch && matchesEvent && matchesTeam) ? "" : "none";
            });
        }

        searchInput.addEventListener("keyup", filterMembers);
        eventFilter.addEventListener("change", filterMembers);
        teamFilter.addEventListener("change", filterMembers);
    </script>

    <script>
        layout_change('false');
        layout_theme_sidebar_change('dark');
        change_box_container('false');
        layout_caption_change('true');
        layout_rtl_change('false');
        preset_change('preset-1');
        main_layout_change('vertical');
    </script>

</body>
</html>
