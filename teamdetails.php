<?php
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
   FETCH TEAM DETAILS
   ========================================================= */
$teamId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Fallback: If no valid team ID is provided in URL, automatically load the latest team
if (!$teamId) {
    $latestStmt = $pdo->query('SELECT team_id FROM teams ORDER BY created_at DESC LIMIT 1');
    $latestTeam = $latestStmt->fetch();
    $teamId = $latestTeam ? (int)$latestTeam['team_id'] : null;
}

$team = null;
$members = [];

if ($teamId) {
    $stmt = $pdo->prepare('
        SELECT 
            t.*,
            e.title AS event_title,
            e.venue,
            e.event_date,
            e.min_team_size,
            e.max_team_size,
            s.name AS leader_name,
            s.email AS leader_email,
            s.phone AS leader_phone,
            s.enrollment_no AS leader_enrollment,
            s.profile_photo AS leader_photo,
            c.name AS college_name
        FROM teams t
        JOIN events e ON t.event_id = e.event_id
        JOIN students s ON t.leader_id = s.student_id
        LEFT JOIN colleges c ON s.college_id = c.college_id
        WHERE t.team_id = :id
    ');
    $stmt->execute(['id' => $teamId]);
    $team = $stmt->fetch();

    if ($team) {
        $mStmt = $pdo->prepare('
            SELECT 
                s.student_id,
                s.name,
                s.enrollment_no,
                s.email,
                s.profile_photo,
                c.name AS college_name
            FROM team_members tm
            JOIN students s ON tm.student_id = s.student_id
            LEFT JOIN colleges c ON s.college_id = c.college_id
            WHERE tm.team_id = :tid
            ORDER BY (s.student_id = :leader_id) DESC, s.name ASC
        ');
        $mStmt->execute(['tid' => $teamId, 'leader_id' => $team['leader_id']]);
        $members = $mStmt->fetchAll();
    }
}

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<!doctype html>
<html lang="en">

<head>
    <title>Team Details | Evenza Admin</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- [Font] Family -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
    
    <!-- [Icon Fonts Required for Sidebar] -->
    <link rel="stylesheet" href="assets/fonts/phosphor/duotone/style.css" />
    <link rel="stylesheet" href="assets/fonts/tabler-icons.min.css" />
    <link rel="stylesheet" href="assets/fonts/feather.css" />
    <link rel="stylesheet" href="assets/fonts/fontawesome.css" />
    <link rel="stylesheet" href="assets/fonts/material.css" />

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
        .table thead th { background: #f8f9fc; color: #6b7280; font-size: 13px; font-weight: 600; white-space: nowrap; padding: 15px; }
        .table tbody td { padding: 15px; vertical-align: middle; color: #374151; }
        .table tbody tr { transition: 0.2s ease; }
        .table tbody tr:hover { background-color: #f8faff; }
        .member-avatar { width: 46px; height: 46px; object-fit: cover; border-radius: 12px; border: 1px solid #e5e7eb; background: #f8f9fa; }
        .leader-avatar { width: 80px; height: 80px; object-fit: cover; border-radius: 16px; border: 1px solid #e5e7eb; }
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

            <?php if ($flash): ?>
                <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($flash['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Page Header -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="page-title mb-1">Team Details</h4>
                    <p class="page-subtitle mb-3">
                        <?= $team ? 'Viewing details for team: ' . htmlspecialchars($team['team_name']) : 'No team selected' ?>
                    </p>
                    <ul class="custom-breadcrumb">
                        <li><a href="Index.php">Home</a></li>
                        <li><a href="allteams.php">Team Management</a></li>
                        <li>Team Details</li>
                    </ul>
                </div>
                <div>
                    <a href="allteams.php" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-2"></i> Back to Teams
                    </a>
                </div>
            </div>

            <?php if (!$team): ?>
                <!-- Empty State if database has no teams at all -->
                <div class="card main-card shadow-sm text-center py-5">
                    <div class="card-body">
                        <i class="bi bi-people text-muted display-4"></i>
                        <h5 class="mt-3">No Team Information Available</h5>
                        <p class="text-muted">No teams exist in the database or an invalid team was requested.</p>
                        <a href="allteams.php" class="btn btn-primary mt-2">Go to Teams List</a>
                    </div>
                </div>
            <?php else: ?>

                <!-- Team & Event Information Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="card main-card shadow-sm h-100">
                            <div class="main-card-header">
                                <h5 class="mb-0">Team Information</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-3">
                                    <small class="text-muted d-block">Team Name</small>
                                    <span class="fw-semibold text-dark fs-6"><?= htmlspecialchars($team['team_name']) ?></span>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">Team Code</small>
                                    <span class="badge bg-primary-subtle text-primary fw-bold fs-6">
                                        <?= htmlspecialchars($team['team_code']) ?>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Created Date</small>
                                    <span class="text-dark"><?= date('d M Y', strtotime($team['created_at'])) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card main-card shadow-sm h-100">
                            <div class="main-card-header">
                                <h5 class="mb-0">Event Information</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-3">
                                    <small class="text-muted d-block">Event Name</small>
                                    <span class="fw-semibold text-dark fs-6"><?= htmlspecialchars($team['event_title']) ?></span>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">Venue</small>
                                    <span class="text-dark"><?= htmlspecialchars($team['venue'] ?? 'Not Specified') ?></span>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Event Date</small>
                                    <span class="text-dark"><?= !empty($team['event_date']) ? date('d M Y', strtotime($team['event_date'])) : 'TBA' ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Leader Details -->
                <div class="card main-card shadow-sm mb-4">
                    <div class="main-card-header">
                        <h5 class="mb-0">Team Leader</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-4 flex-wrap">
                            <?php 
                                $leaderPhoto = !empty($team['leader_photo']) 
                                    ? $PHOTO_WEB_PATH . htmlspecialchars($team['leader_photo']) 
                                    : 'assets/images/user/avatar-1.jpg'; 
                            ?>
                            <img src="<?= $leaderPhoto ?>" class="leader-avatar" alt="Leader Photo">
                            <div>
                                <h5 class="mb-1 text-dark fw-bold"><?= htmlspecialchars($team['leader_name']) ?></h5>
                                <p class="mb-1 text-muted"><strong>Enrollment:</strong> <?= htmlspecialchars($team['leader_enrollment'] ?? 'N/A') ?></p>
                                <p class="mb-1 text-muted"><strong>Email:</strong> <?= htmlspecialchars($team['leader_email']) ?></p>
                                <p class="mb-0 text-muted"><strong>College:</strong> <?= htmlspecialchars($team['college_name'] ?? 'N/A') ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Members Table -->
                <div class="card main-card shadow-sm">
                    <div class="main-card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Team Members</h5>
                            <small class="text-muted">Total members in team</small>
                        </div>
                        <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2">
                            <?= count($members) ?> / <?= $team['max_team_size'] ?> Members
                        </span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Enrollment</th>
                                        <th>Email</th>
                                        <th>College</th>
                                        <th>Role</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($members)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">No members recorded for this team yet.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($members as $index => $m): ?>
                                            <?php 
                                                $isLeader = (int)$m['student_id'] === (int)$team['leader_id']; 
                                                $photo = !empty($m['profile_photo']) 
                                                    ? $PHOTO_WEB_PATH . htmlspecialchars($m['profile_photo']) 
                                                    : 'assets/images/user/avatar-1.jpg';
                                            ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td>
                                                    <img src="<?= $photo ?>" class="member-avatar" alt="Member Photo">
                                                </td>
                                                <td class="fw-semibold text-dark"><?= htmlspecialchars($m['name']) ?></td>
                                                <td><?= htmlspecialchars($m['enrollment_no'] ?? 'N/A') ?></td>
                                                <td><?= htmlspecialchars($m['email']) ?></td>
                                                <td><?= htmlspecialchars($m['college_name'] ?? 'N/A') ?></td>
                                                <td>
                                                    <?php if ($isLeader): ?>
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-7"><i class="bi bi-star-fill me-1"></i> Leader</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-7">Member</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            <?php endif; ?>

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
