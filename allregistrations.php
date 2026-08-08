<?php
/**
 * allregistrations.php
 * Admin monitoring dashboard for event registrations and participant rosters.
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

/* =========================================================
   CANCEL / DISQUALIFY HANDLER
   ========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['action'] ?? '') === 'cancel') {
    $registration_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($registration_id) {
        try {
            $stmt = $pdo->prepare("UPDATE registrations SET status = 'cancelled' WHERE registration_id = :id");
            $stmt->execute(['id' => $registration_id]);

            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => "Registration REG" . str_pad((string)$registration_id, 4, '0', STR_PAD_LEFT) . " has been cancelled."
            ];
        } catch (PDOException $e) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Could not cancel registration.'];
        }
    }

    header('Location: allregistrations.php');
    exit;
}

/* =========================================================
   FETCH REGISTRATIONS WITH FULL JOIN DATA
   ========================================================= */
$query = "
    SELECT 
        r.registration_id,
        r.registration_type,
        r.status,
        r.registered_at,
        e.event_id,
        e.title AS event_title,
        e.event_date,
        e.start_time,
        e.end_time,
        e.venue,
        e.registration_fee,
        e.fee_type,
        -- Student / Leader info
        s.student_id,
        s.name AS student_name,
        s.email AS student_email,
        s.phone AS student_phone,
        -- Team info
        t.team_id,
        t.team_name,
        t.team_code,
        -- College info
        c.name AS college_name,
        -- Payment info
        p.payment_status,
        p.amount
    FROM registrations r
    JOIN events e ON r.event_id = e.event_id
    LEFT JOIN students s ON r.student_id = s.student_id
    LEFT JOIN teams t ON r.team_id = t.team_id
    LEFT JOIN students leader ON t.leader_id = leader.student_id
    LEFT JOIN colleges c ON (s.college_id = c.college_id OR leader.college_id = c.college_id)
    LEFT JOIN payments p ON r.registration_id = p.registration_id
    ORDER BY r.registered_at DESC
";

$registrations = $pdo->query($query)->fetchAll();

// Retrieve Team Members for Team Registrations
$teamMembersMap = [];
$teamIds = array_filter(array_column($registrations, 'team_id'));

if (!empty($teamIds)) {
    $inClause = implode(',', array_map('intval', array_unique($teamIds)));
    $membersQuery = "
        SELECT 
            tm.team_id,
            s.name,
            s.email,
            s.phone,
            s.enrollment_no
        FROM team_members tm
        JOIN students s ON tm.student_id = s.student_id
        WHERE tm.team_id IN ($inClause)
    ";
    $membersList = $pdo->query($membersQuery)->fetchAll();
    foreach ($membersList as $m) {
        $teamMembersMap[$m['team_id']][] = $m;
    }
}

// Quick Metrics
$total      = count($registrations);
$approved   = count(array_filter($registrations, fn($r) => $r['status'] === 'approved'));
$pending    = count(array_filter($registrations, fn($r) => $r['status'] === 'pending'));
$cancelled  = count(array_filter($registrations, fn($r) => $r['status'] === 'cancelled'));

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!doctype html>
<html lang="en">

<head>
    <title>All Registrations | Evenza Admin</title>
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
        .stat-card { border: 0; border-radius: 14px; transition: 0.3s ease; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important; }
        .stat-icon { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 22px; }
        .icon-primary { background: #e8edff; color: #4f46e5; }
        .icon-success { background: #e7f8ef; color: #198754; }
        .icon-warning { background: #fff8e6; color: #ffc107; }
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
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .action-btn { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: 1px solid #e5e7eb; background: #fff; }
        .action-btn:hover { background-color: #f3f4f6; }
        .action-option-btn { width: 100%; text-align: left; padding: 12px 16px; border-radius: 10px; border: 0; background: #f8f9fa; font-weight: 500; color: #374151; transition: 0.2s ease; margin-bottom: 8px; display: flex; align-items: center; }
        .action-option-btn:hover { background: #eef2ff; color: #4f46e5; }
        .action-option-btn.delete-option:hover { background: #fdecec; color: #dc3545; }
        .page-link { cursor: pointer; }
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
                    <h4 class="page-title mb-1">All Registrations</h4>
                    <p class="page-subtitle mb-3">Monitor and review all student and team event sign-ups</p>
                    <ul class="custom-breadcrumb">
                        <li><a href="Dashboard.php">Home</a></li>
                        <li>Registration Management</li>
                        <li>All Registrations</li>
                    </ul>
                </div>
            </div>

            <!-- Statistics Grid -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon icon-primary me-3"><i class="bi bi-people-fill"></i></div>
                            <div>
                                <small class="text-muted">Total Registrations</small>
                                <h4 class="mb-0 mt-1"><?= $total ?></h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon icon-success me-3"><i class="bi bi-check-circle-fill"></i></div>
                            <div>
                                <small class="text-muted">Confirmed / Approved</small>
                                <h4 class="mb-0 mt-1"><?= $approved ?></h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon icon-warning me-3"><i class="bi bi-clock-history"></i></div>
                            <div>
                                <small class="text-muted">Pending Payment</small>
                                <h4 class="mb-0 mt-1"><?= $pending ?></h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon icon-danger me-3"><i class="bi bi-x-circle-fill"></i></div>
                            <div>
                                <small class="text-muted">Cancelled</small>
                                <h4 class="mb-0 mt-1"><?= $cancelled ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration List Card -->
            <div class="card main-card shadow-sm">
                <div class="main-card-header">
                    <div class="row align-items-center g-3">
                        <div class="col-md-5">
                            <h5 class="mb-1">Registration List</h5>
                            <small class="text-muted">Real-time overview of participant entries</small>
                        </div>
                        <div class="col-md-7">
                            <div class="row g-2 justify-content-md-end">
                                <div class="col-md-7">
                                    <div class="search-box">
                                        <i class="bi bi-search"></i>
                                        <input type="text" class="form-control" id="searchRegistration" placeholder="Search ID, participant, event, college...">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <select class="form-select" id="statusFilter">
                                        <option value="">All Statuses</option>
                                        <option value="approved">Approved</option>
                                        <option value="pending">Pending</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="registrationTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Reg ID</th>
                                    <th>Event Name</th>
                                    <th>Type</th>
                                    <th>Participant / Team</th>
                                    <th>College</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($total === 0): ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">No registrations found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($registrations as $i => $r): ?>
                                        <?php 
                                            $isTeam = $r['registration_type'] === 'team';
                                            $participantName = $isTeam ? ($r['team_name'] ?? 'N/A') : ($r['student_name'] ?? 'N/A');
                                            $regCode = 'REG' . str_pad((string)$r['registration_id'], 4, '0', STR_PAD_LEFT);
                                            
                                            $badgeClass = 'bg-warning-subtle text-warning';
                                            if ($r['status'] === 'approved') $badgeClass = 'bg-success-subtle text-success';
                                            if ($r['status'] === 'cancelled') $badgeClass = 'bg-danger-subtle text-danger';
                                        ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td><span class="fw-semibold text-primary"><?= $regCode ?></span></td>
                                            <td><span class="fw-medium text-dark"><?= htmlspecialchars($r['event_title']) ?></span></td>
                                            <td>
                                                <span class="badge <?= $isTeam ? 'bg-info-subtle text-info' : 'bg-primary-subtle text-primary' ?>">
                                                    <?= ucfirst($r['registration_type']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="fw-semibold"><?= htmlspecialchars($participantName) ?></div>
                                                <?php if ($isTeam && !empty($r['team_code'])): ?>
                                                    <small class="text-muted">Code: <?= htmlspecialchars($r['team_code']) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($r['college_name'] ?? 'N/A') ?></td>
                                            <td>
                                                <span class="badge status-badge <?= $badgeClass ?>">
                                                    <?= ucfirst($r['status']) ?>
                                                </span>
                                            </td>
                                            <td><small class="text-muted"><?= date('d M Y', strtotime($r['registered_at'])) ?></small></td>
                                            <td class="text-end">
                                                <!-- Popup Action Menu -->
                                                <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#actionMenuModal<?= $r['registration_id'] ?>" title="Actions">
                                                    <i class="bi bi-three-dots-vertical text-muted"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer with 5, 10, 25 Limit Selector & Compact Pagination -->
                <div class="card-footer bg-white border-top py-3">
                    <div class="row align-items-center g-3">
                        <div class="col-md-6 col-12">
                            <div class="d-flex align-items-center gap-2">
                                <small class="text-muted text-nowrap">Show</small>
                                <select class="form-select form-select-sm w-auto" id="limitSelect" style="font-size: 12px; padding-top: 2px; padding-bottom: 2px;">
                                    <option value="5" selected>5</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                </select>
                                <small class="text-muted text-nowrap me-2">entries</small>
                                <span class="text-muted opacity-50">|</span>
                                <small class="text-muted ms-2" id="showingCountText">Showing 0 of 0 registrations</small>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <nav aria-label="Table pagination">
                                <ul class="pagination pagination-sm mb-0 justify-content-md-end justify-content-center" id="pagination">
                                    <!-- Dynamic compact pagination controls inject here -->
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- ===================== MODALS PER ROW ===================== -->
    <?php foreach ($registrations as $r): ?>
        <?php 
            $isTeam = $r['registration_type'] === 'team';
            $regCode = 'REG' . str_pad((string)$r['registration_id'], 4, '0', STR_PAD_LEFT);
            $members = $isTeam && isset($teamMembersMap[$r['team_id']]) ? $teamMembersMap[$r['team_id']] : [];
        ?>

        <!-- 1. POPUP ACTION MENU -->
        <div class="modal fade" id="actionMenuModal<?= $r['registration_id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-0 pb-0">
                        <h6 class="modal-title font-semibold text-muted">Registration Actions</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-3">
                        <!-- Option 1: View Event Info -->
                        <button type="button" class="action-option-btn" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#viewEventModal<?= $r['registration_id'] ?>">
                            <i class="bi bi-calendar-event text-primary me-3 fs-5"></i> View Event Info
                        </button>

                        <!-- Option 2: View Participant Details / Roster -->
                        <button type="button" class="action-option-btn" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#viewParticipantModal<?= $r['registration_id'] ?>">
                            <i class="bi bi-people text-info me-3 fs-5"></i> View Roster / Details
                        </button>

                        <!-- Option 3: Cancel Registration -->
                        <?php if ($r['status'] !== 'cancelled'): ?>
                            <button type="button" class="action-option-btn delete-option text-danger" data-bs-dismiss="modal" onclick="cancelRegistration(<?= $r['registration_id'] ?>)">
                                <i class="bi bi-x-circle text-danger me-3 fs-5"></i> Cancel Registration
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. VIEW EVENT DETAILS MODAL -->
        <div class="modal fade" id="viewEventModal<?= $r['registration_id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold text-dark">
                            <i class="bi bi-calendar-event text-primary me-2"></i>Event Information
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3 border-bottom pb-2">
                            <h5 class="mb-1 text-primary"><?= htmlspecialchars($r['event_title']) ?></h5>
                            <small class="text-muted"><i class="bi bi-building me-1"></i><?= htmlspecialchars($r['college_name'] ?? 'N/A') ?></small>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <small class="text-muted d-block">EVENT DATE</small>
                                <span class="fw-semibold text-dark"><i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($r['event_date'])) ?></span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">TIMING</small>
                                <span class="fw-semibold text-dark">
                                    <i class="bi bi-clock me-1"></i><?= !empty($r['start_time']) ? date('h:i A', strtotime($r['start_time'])) : 'N/A' ?>
                                </span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">VENUE</small>
                                <span class="fw-semibold text-dark"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($r['venue'] ?: 'N/A') ?></span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">FEE</small>
                                <span class="fw-bold text-success">₹<?= number_format($r['registration_fee'], 2) ?> (<?= $r['fee_type'] === 'per_person' ? 'Per Person' : 'Per Team' ?>)</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. VIEW PARTICIPANT / TEAM ROSTER MODAL -->
        <div class="modal fade" id="viewParticipantModal<?= $r['registration_id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header">
                        <h5 class="modal-title">Participant Details - <?= $regCode ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <?php if ($isTeam): ?>
                            <div class="mb-3">
                                <h6><i class="bi bi-people me-2 text-primary"></i>Team Name: <strong><?= htmlspecialchars($r['team_name'] ?? 'N/A') ?></strong></h6>
                                <small class="text-muted">Team Code: <?= htmlspecialchars($r['team_code'] ?? 'N/A') ?></small>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Student Name</th>
                                            <th>Enrollment No</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($members)): ?>
                                            <tr><td colspan="5" class="text-center text-muted py-3">No team members registered yet.</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($members as $mIdx => $m): ?>
                                                <tr>
                                                    <td><?= $mIdx + 1 ?></td>
                                                    <td class="fw-semibold"><?= htmlspecialchars($m['name']) ?></td>
                                                    <td><?= htmlspecialchars($m['enrollment_no'] ?? 'N/A') ?></td>
                                                    <td><?= htmlspecialchars($m['email']) ?></td>
                                                    <td><?= htmlspecialchars($m['phone'] ?? 'N/A') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <h6><i class="bi bi-person me-2 text-primary"></i>Individual Participant Information</h6>
                            <div class="card card-body bg-light border-0 mt-2">
                                <p class="mb-2"><strong>Name:</strong> <?= htmlspecialchars($r['student_name'] ?? 'N/A') ?></p>
                                <p class="mb-2"><strong>Email:</strong> <?= htmlspecialchars($r['student_email'] ?? 'N/A') ?></p>
                                <p class="mb-0"><strong>Phone:</strong> <?= htmlspecialchars($r['student_phone'] ?? 'N/A') ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    <?php endforeach; ?>

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
        // Real-time Search, Status Filter, Limit & Compact Pagination Engine
        const searchInput = document.getElementById("searchRegistration");
        const statusFilter = document.getElementById("statusFilter");
        const limitSelect = document.getElementById("limitSelect");
        const rows = document.querySelectorAll("#registrationTable tbody tr");
        const showingCountText = document.getElementById("showingCountText");
        const paginationContainer = document.getElementById("pagination");

        let currentPage = 1;

        function filterRegistrations() {
            const searchValue = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();
            const limitValue = limitSelect.value;

            // 1. Filter matching rows
            const matchedRows = [];
            rows.forEach(row => {
                const badge = row.querySelector(".status-badge");
                if (!badge) return;

                const rowText = row.innerText.toLowerCase();
                const statusText = badge.innerText.toLowerCase().trim();

                const matchesSearch = rowText.includes(searchValue);
                const matchesStatus = statusValue === "" || statusText === statusValue;

                if (matchesSearch && matchesStatus) {
                    matchedRows.push(row);
                } else {
                    row.style.display = "none";
                }
            });

            const totalMatched = matchedRows.length;
            let pageSize = parseInt(limitValue, 10);
            if (pageSize <= 0) pageSize = 1;

            const totalPages = Math.ceil(totalMatched / pageSize) || 1;

            // Clamp current page to valid range
            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;

            const startIdx = (currentPage - 1) * pageSize;
            const endIdx = startIdx + pageSize;

            // 2. Render visible page subset
            matchedRows.forEach((row, idx) => {
                if (idx >= startIdx && idx < endIdx) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });

            // 3. Update counter text
            const visibleCount = Math.min(pageSize, totalMatched - startIdx > 0 ? totalMatched - startIdx : 0);
            showingCountText.textContent = `Showing ${visibleCount} of ${totalMatched} registrations`;

            // 4. Build Compact Pagination Controls
            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            paginationContainer.innerHTML = "";

            // Hide pagination completely if there's only 1 page or no records
            if (totalPages <= 1) return;

            // 1. PREVIOUS BUTTON (Only renders/displays if NOT on Page 1)
            if (currentPage > 1) {
                const prevLi = document.createElement("li");
                prevLi.className = "page-item";
                prevLi.innerHTML = `<a class="page-link" aria-label="Previous"><i class="bi bi-chevron-left"></i> Prev</a>`;
                prevLi.addEventListener("click", () => {
                    currentPage--;
                    filterRegistrations();
                });
                paginationContainer.appendChild(prevLi);
            }

            // 2. CURRENT PAGE NUMBER ONLY
            const currentLi = document.createElement("li");
            currentLi.className = "page-item active";
            currentLi.innerHTML = `<a class="page-link">${currentPage}</a>`;
            paginationContainer.appendChild(currentLi);

            // 3. NEXT BUTTON (Only renders/displays if NOT on the last page)
            if (currentPage < totalPages) {
                const nextLi = document.createElement("li");
                nextLi.className = "page-item";
                nextLi.innerHTML = `<a class="page-link" aria-label="Next">Next <i class="bi bi-chevron-right"></i></a>`;
                nextLi.addEventListener("click", () => {
                    currentPage++;
                    filterRegistrations();
                });
                paginationContainer.appendChild(nextLi);
            }
        }

        searchInput.addEventListener("keyup", () => { currentPage = 1; filterRegistrations(); });
        statusFilter.addEventListener("change", () => { currentPage = 1; filterRegistrations(); });
        limitSelect.addEventListener("change", () => { currentPage = 1; filterRegistrations(); });

        document.addEventListener("DOMContentLoaded", filterRegistrations);

        function cancelRegistration(id) {
            if (confirm("Are you sure you want to cancel this registration?")) {
                window.location.href = "allregistrations.php?action=cancel&id=" + id;
            }
        }
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
