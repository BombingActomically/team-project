<?php
/**
 * allteams.php
 * Single-file Team Management: DB Connection, CRUD with Multi-Member Creation,
 * member management modal, leader update fix, event filtering, search, limit & compact pagination, and matching UI.
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
   VALIDATION HELPERS
   ========================================================= */
function validate_team(PDO $pdo, array $data, ?int $excludeId = null): array
{
    $errors = [];

    $teamName  = trim($data['team_name'] ?? '');
    $eventId   = filter_var($data['event_id'] ?? null, FILTER_VALIDATE_INT);
    $leaderId  = filter_var($data['leader_id'] ?? null, FILTER_VALIDATE_INT);
    $teamCode  = trim($data['team_code'] ?? '');
    $memberIds = array_filter(array_map('intval', $data['member_ids'] ?? []));

    if ($teamName === '' || mb_strlen($teamName) < 2) {
        $errors[] = 'Team name must be at least 2 characters.';
    } elseif (mb_strlen($teamName) > 100) {
        $errors[] = 'Team name is too long (max 100 chars).';
    }

    if (!$eventId) {
        $errors[] = 'Select a valid event.';
    } else {
        $stmt = $pdo->prepare('SELECT event_id, event_type, min_team_size, max_team_size FROM events WHERE event_id = :id');
        $stmt->execute(['id' => $eventId]);
        $evt = $stmt->fetch();

        if (!$evt) {
            $errors[] = 'Selected event does not exist.';
        } elseif ($evt['event_type'] !== 'team') {
            $errors[] = 'Selected event is a solo event. Teams can only be created for team events.';
        } else {
            if ($excludeId !== null) {
                $countStmt = $pdo->prepare('SELECT COUNT(*) FROM team_members WHERE team_id = :tid');
                $countStmt->execute(['tid' => $excludeId]);
                $existingMemberCount = (int)$countStmt->fetchColumn();

                $chkLeader = $pdo->prepare('SELECT 1 FROM team_members WHERE team_id = :tid AND student_id = :sid');
                $chkLeader->execute(['tid' => $excludeId, 'sid' => $leaderId]);
                if (!$chkLeader->fetch()) {
                    $existingMemberCount++;
                }

                $totalMembers = $existingMemberCount;
            } else {
                $totalMembers = count(array_unique(array_merge([$leaderId], $memberIds)));
            }

            if ($totalMembers < $evt['min_team_size']) {
                $errors[] = "This event requires a minimum of {$evt['min_team_size']} members (including leader).";
            }
            if ($totalMembers > $evt['max_team_size']) {
                $errors[] = "This event allows a maximum of {$evt['max_team_size']} members.";
            }
        }
    }

    if (!$leaderId) {
        $errors[] = 'Select a valid team leader.';
    } else {
        $stmt = $pdo->prepare('SELECT student_id FROM students WHERE student_id = :id');
        $stmt->execute(['id' => $leaderId]);
        if (!$stmt->fetch()) {
            $errors[] = 'Selected team leader does not exist.';
        }
    }

    if ($teamCode !== '') {
        $sql = 'SELECT team_id FROM teams WHERE team_code = :team_code';
        $params = ['team_code' => $teamCode];
        if ($excludeId !== null) {
            $sql .= ' AND team_id != :id';
            $params['id'] = $excludeId;
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        if ($stmt->fetch()) {
            $errors[] = 'This team code is already in use.';
        }
    }

    return $errors;
}

function generate_team_code(PDO $pdo, string $prefix = 'TM'): string
{
    do {
        $code = strtoupper($prefix . rand(100, 999) . chr(rand(65, 90)));
        $stmt = $pdo->prepare('SELECT team_id FROM teams WHERE team_code = :code');
        $stmt->execute(['code' => $code]);
    } while ($stmt->fetch());
    return $code;
}

/* =========================================================
   MEMBER ACTIONS (ADD / REMOVE MEMBER)
   ========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['member_action'])) {
    $teamId    = filter_var($_POST['team_id'] ?? null, FILTER_VALIDATE_INT);
    $studentId = filter_var($_POST['student_id'] ?? null, FILTER_VALIDATE_INT);

    if ($teamId && $studentId) {
        if ($_POST['member_action'] === 'add_member') {
            try {
                $stmt = $pdo->prepare('SELECT e.max_team_size, (SELECT COUNT(*) FROM team_members WHERE team_id = t.team_id) AS current_count FROM teams t JOIN events e ON t.event_id = e.event_id WHERE t.team_id = :tid');
                $stmt->execute(['tid' => $teamId]);
                $limitInfo = $stmt->fetch();

                if ($limitInfo && $limitInfo['current_count'] >= $limitInfo['max_team_size']) {
                    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Cannot add member. Team limit reached (' . $limitInfo['max_team_size'] . ').'];
                } else {
                    $ins = $pdo->prepare('INSERT IGNORE INTO team_members (team_id, student_id) VALUES (:tid, :sid)');
                    $ins->execute(['tid' => $teamId, 'sid' => $studentId]);
                    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Team member added successfully.'];
                }
            } catch (PDOException $e) {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Could not add member. Student may already be in the team.'];
            }
        } elseif ($_POST['member_action'] === 'remove_member') {
            $stmt = $pdo->prepare('SELECT leader_id FROM teams WHERE team_id = :tid');
            $stmt->execute(['tid' => $teamId]);
            $teamRow = $stmt->fetch();

            if ($teamRow && (int)$teamRow['leader_id'] === $studentId) {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Cannot remove team leader. Change the team leader first before removing.'];
            } else {
                $del = $pdo->prepare('DELETE FROM team_members WHERE team_id = :tid AND student_id = :sid');
                $del->execute(['tid' => $teamId, 'sid' => $studentId]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Team member removed successfully.'];
            }
        }
        $_SESSION['reopen_modal'] = 'membersModal' . $teamId;
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Invalid team or student selected.'];
    }

    header('Location: allteams.php');
    exit;
}

/* =========================================================
   DELETE TEAM
   ========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['action'] ?? '') === 'delete') {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($id) {
        $stmt = $pdo->prepare('SELECT team_id FROM teams WHERE team_id = :id');
        $stmt->execute(['id' => $id]);
        if ($stmt->fetch()) {
            $pdo->prepare('DELETE FROM teams WHERE team_id = :id')->execute(['id' => $id]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Team deleted successfully.'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Team not found.'];
        }
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Invalid team ID.'];
    }

    header('Location: allteams.php');
    exit;
}

/* =========================================================
   CREATE / UPDATE TEAM
   ========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_action'])) {

    if ($_POST['form_action'] === 'create') {
        $errors = validate_team($pdo, $_POST);

        if (empty($errors)) {
            try {
                $teamCode = trim($_POST['team_code'] ?? '');
                if ($teamCode === '') {
                    $teamCode = generate_team_code($pdo);
                }

                $leaderId  = (int)$_POST['leader_id'];
                $memberIds = array_filter(array_map('intval', $_POST['member_ids'] ?? []));
                $allMembers = array_unique(array_merge([$leaderId], $memberIds));

                $pdo->beginTransaction();

                $stmt = $pdo->prepare(
                    'INSERT INTO teams (event_id, leader_id, team_name, team_code, created_at)
                     VALUES (:event_id, :leader_id, :team_name, :team_code, NOW())'
                );
                $stmt->execute([
                    'event_id'  => (int)$_POST['event_id'],
                    'leader_id' => $leaderId,
                    'team_name' => trim($_POST['team_name']),
                    'team_code' => $teamCode,
                ]);

                $newTeamId = (int)$pdo->lastInsertId();

                $stmtMember = $pdo->prepare('INSERT IGNORE INTO team_members (team_id, student_id) VALUES (:tid, :sid)');
                foreach ($allMembers as $sid) {
                    $stmtMember->execute(['tid' => $newTeamId, 'sid' => $sid]);
                }

                $stmtReg = $pdo->prepare(
                    'INSERT INTO registrations (event_id, student_id, team_id, registration_type, status, registered_at)
                     VALUES (:event_id, :student_id, :team_id, "team", "approved", NOW())'
                );
                $stmtReg->execute([
                    'event_id'   => (int)$_POST['event_id'],
                    'student_id' => $leaderId,
                    'team_id'    => $newTeamId,
                ]);

                $pdo->commit();

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Team created successfully with ' . count($allMembers) . ' members.'];
            } catch (PDOException $e) {
                if ($pdo->inTransaction()) $pdo->rollBack();
                $errors[] = 'Could not save team. Database error.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => implode(' ', $errors)];
            $_SESSION['reopen_modal'] = 'addTeamModal';
        }

        header('Location: allteams.php');
        exit;
    }

    if ($_POST['form_action'] === 'update') {
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        $existingStmt = $pdo->prepare('SELECT * FROM teams WHERE team_id = :id');
        $existingStmt->execute(['id' => $id]);
        $existing = $existingStmt->fetch();

        if (!$id || !$existing) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Team not found.'];
            header('Location: allteams.php');
            exit;
        }

        $errors = validate_team($pdo, $_POST, $id);

        if (empty($errors)) {
            try {
                $pdo->beginTransaction();

                $teamCode = trim($_POST['team_code'] ?? '');
                if ($teamCode === '') {
                    $teamCode = $existing['team_code'];
                }

                $newLeaderId = (int)$_POST['leader_id'];

                $stmt = $pdo->prepare(
                    'UPDATE teams
                     SET event_id = :event_id, leader_id = :leader_id, team_name = :team_name, team_code = :team_code
                     WHERE team_id = :id'
                );
                $stmt->execute([
                    'event_id'  => (int)$_POST['event_id'],
                    'leader_id' => $newLeaderId,
                    'team_name' => trim($_POST['team_name']),
                    'team_code' => $teamCode,
                    'id'        => $id,
                ]);

                $stmtMember = $pdo->prepare('INSERT IGNORE INTO team_members (team_id, student_id) VALUES (:tid, :sid)');
                $stmtMember->execute(['tid' => $id, 'sid' => $newLeaderId]);

                $pdo->commit();

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Team updated successfully.'];
            } catch (PDOException $e) {
                if ($pdo->inTransaction()) $pdo->rollBack();
                $errors[] = 'Could not update team.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => implode(' ', $errors)];
            $_SESSION['reopen_modal'] = 'editTeamModal' . $id;
        }

        header('Location: allteams.php');
        exit;
    }
}

/* =========================================================
   DATA FETCHING
   ========================================================= */
$teamsSql = '
    SELECT 
        t.*,
        e.title AS event_title,
        e.min_team_size,
        e.max_team_size,
        s.name AS leader_name,
        s.email AS leader_email,
        c.name AS college_name,
        (SELECT COUNT(*) FROM team_members tm WHERE tm.team_id = t.team_id) AS total_members
    FROM teams t
    JOIN events e ON t.event_id = e.event_id
    JOIN students s ON t.leader_id = s.student_id
    LEFT JOIN colleges c ON s.college_id = c.college_id
    ORDER BY t.created_at DESC';

$teams = $pdo->query($teamsSql)->fetchAll();

$events   = $pdo->query("SELECT event_id, title, event_type, min_team_size, max_team_size FROM events ORDER BY title ASC")->fetchAll();
$students = $pdo->query("SELECT s.student_id, s.name, s.enrollment_no, c.name as college_name FROM students s LEFT JOIN colleges c ON s.college_id = c.college_id ORDER BY s.name ASC")->fetchAll();

$total = count($teams);

$flash = $_SESSION['flash'] ?? null;
$reopenModal = $_SESSION['reopen_modal'] ?? null;
unset($_SESSION['flash'], $_SESSION['reopen_modal']);
?>
<!doctype html>
<html lang="en">

<head>
    <title>All Teams | Evenza Admin</title>

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
        .icon-warning { background: #fef3c7; color: #d97706; }
        .main-card { border: 0; border-radius: 16px; overflow: hidden; }
        .main-card-header { background: #ffffff; padding: 20px 24px; border-bottom: 1px solid #edf0f5; }
        .search-box { position: relative; }
        .search-box i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; }
        .search-box input { padding-left: 40px; border-radius: 10px; }
        .table thead th { background: #f8f9fc; color: #6b7280; font-size: 13px; font-weight: 600; white-space: nowrap; padding: 15px; }
        .table tbody td { padding: 15px; vertical-align: middle; color: #374151; }
        .table tbody tr { transition: 0.2s ease; }
        .table tbody tr:hover { background-color: #f8faff; }
        .team-name { font-weight: 600; color: #1f2937; }
        .action-btn { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; }
        .page-link { cursor: pointer; }
        @media (max-width: 768px) {
            .main-card-header { padding: 16px; }
            .table { min-width: 900px; }
        }
    </style>

    <!-- Dark Theme Specific Overrides (Robust Fix) -->
    <style>
        :root{
            --evenza-bg:#0D1117;
            --evenza-card:#161B22;
            --evenza-border:rgba(255,255,255,0.07);
            --evenza-accent:#22C55E;
            --evenza-accent-soft:rgba(34,197,94,0.12);
        }

        [data-pc-theme="dark"] body { background: var(--evenza-bg) !important; }
        [data-pc-theme="dark"] .pc-container { background: var(--evenza-bg) !important; }
        
        /* Card Fixes */
        [data-pc-theme="dark"] .card, 
        [data-pc-theme="dark"] .main-card {
            background-color: var(--evenza-card) !important;
            border-color: var(--evenza-border) !important;
        }
        [data-pc-theme="dark"] .main-card-header,
        [data-pc-theme="dark"] .card-footer,
        [data-pc-theme="dark"] .bg-light {
            background-color: transparent !important;
            border-bottom: 1px solid var(--evenza-border) !important;
            border-top: 1px solid var(--evenza-border) !important;
        }
        
        /* Text Colors & Breadcrumb */
        [data-pc-theme="dark"] .page-title,
        [data-pc-theme="dark"] .team-name,
        [data-pc-theme="dark"] h5,
        [data-pc-theme="dark"] h6,
        [data-pc-theme="dark"] .text-dark,
        [data-pc-theme="dark"] .card-body h4,
        [data-pc-theme="dark"] .fw-bold,
        [data-pc-theme="dark"] .fw-semibold,
        [data-pc-theme="dark"] .fw-medium,
        [data-pc-theme="dark"] strong {
            color: #E6EDF3 !important;
        }
        [data-pc-theme="dark"] .text-muted,
        [data-pc-theme="dark"] .page-subtitle,
        [data-pc-theme="dark"] .custom-breadcrumb li,
        [data-pc-theme="dark"] .custom-breadcrumb li a,
        [data-pc-theme="dark"] #showingCountText {
            color: #8B949E !important;
        }
        [data-pc-theme="dark"] .custom-breadcrumb li a:hover {
            color: #E6EDF3 !important;
        }

        /* Dropdown Menu Fix */
        [data-pc-theme="dark"] .dropdown-menu {
            background-color: var(--evenza-card) !important;
            border-color: var(--evenza-border) !important;
        }
        [data-pc-theme="dark"] .dropdown-item {
            color: #E6EDF3 !important;
        }
        [data-pc-theme="dark"] .dropdown-item:hover,
        [data-pc-theme="dark"] .dropdown-item:focus {
            background-color: rgba(255, 255, 255, 0.05) !important;
            color: #E6EDF3 !important;
        }

        /* Table Fixes */
        [data-pc-theme="dark"] .table {
            --bs-table-bg: transparent !important; 
            color: #E6EDF3 !important; 
        }
        [data-pc-theme="dark"] .table th,
        [data-pc-theme="dark"] .table td {
            background-color: transparent !important;
            border-bottom: 1px solid var(--evenza-border) !important;
            color: #E6EDF3 !important;
        }
        [data-pc-theme="dark"] .table thead th,
        [data-pc-theme="dark"] .table-light th {
            background: var(--evenza-bg) !important;
            color: #8B949E !important;
            border-bottom: 1px solid rgba(255,255,255,0.1) !important;
        }
        [data-pc-theme="dark"] .table tbody tr:hover td {
            background-color: rgba(255, 255, 255, 0.04) !important;
        }

        /* Badges Fix */
        [data-pc-theme="dark"] .bg-primary-subtle {
            background-color: rgba(88, 166, 255, 0.15) !important;
            color: #58A6FF !important;
        }
        [data-pc-theme="dark"] .btn-outline-info {
            border-color: #58A6FF !important;
            color: #58A6FF !important;
        }
        [data-pc-theme="dark"] .btn-outline-info:hover {
            background-color: #58A6FF !important;
            color: #0D1117 !important;
        }
        
        /* Forms */
        [data-pc-theme="dark"] .form-control,
        [data-pc-theme="dark"] .form-select,
        [data-pc-theme="dark"] .input-group-text {
            background-color: #0D1117 !important;
            border-color: var(--evenza-border) !important;
            color: #E6EDF3 !important;
            color-scheme: dark !important;
        }
        [data-pc-theme="dark"] .form-control:focus,
        [data-pc-theme="dark"] .form-select:focus {
            background-color: #0D1117 !important;
            border-color: var(--evenza-accent) !important;
            color: #E6EDF3 !important;
            box-shadow: 0 0 0 3px var(--evenza-accent-soft) !important;
        }

        /* Multi-Select Fix */
        [data-pc-theme="dark"] select[multiple] option {
            background-color: #0D1117;
            color: #E6EDF3;
            padding: 8px 12px;
            margin-bottom: 2px;
            border-radius: 4px;
        }
        [data-pc-theme="dark"] select[multiple] option:checked {
            background-color: rgba(88, 166, 255, 0.25) !important;
            color: #58A6FF !important;
        }

        /* Misc elements */
        [data-pc-theme="dark"] .action-btn {
            background-color: #232B36 !important;
            border-color: #232B36 !important;
            color: #E6EDF3 !important;
        }
        [data-pc-theme="dark"] .action-btn:hover {
            background-color: #303B4A !important;
        }

        /* Action Option Button in popup */
        [data-pc-theme="dark"] .action-option-btn {
            background-color: #0D1117 !important;
            color: #E6EDF3 !important;
            border: 1px solid var(--evenza-border);
        }
        [data-pc-theme="dark"] .action-option-btn:hover {
            background-color: #232B36 !important;
            color: var(--evenza-info) !important;
        }
        [data-pc-theme="dark"] .action-option-btn.delete-option:hover {
            background-color: rgba(248, 81, 73, 0.1) !important;
            color: var(--evenza-danger) !important;
        }
        
        /* Modals */
        [data-pc-theme="dark"] .modal-content {
            background-color: var(--evenza-card) !important;
            color: #E6EDF3 !important;
            border-color: var(--evenza-border) !important;
        }
        [data-pc-theme="dark"] .modal-header,
        [data-pc-theme="dark"] .modal-footer {
            border-color: var(--evenza-border) !important;
            background-color: transparent !important;
        }

        /* RED CANCEL/CROSS BUTTON IN MODALS */
        [data-pc-theme="dark"] .btn-close {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23F85149'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/%3e%3c/svg%3e") !important;
            opacity: 0.8;
        }
        [data-pc-theme="dark"] .btn-close:hover {
            opacity: 1;
        }
        
        /* Pagination Fixes */
        [data-pc-theme="dark"] .page-link {
            background-color: #0D1117 !important;
            border-color: var(--evenza-border) !important;
            color: #E6EDF3 !important;
        }
        [data-pc-theme="dark"] .page-item.active .page-link {
            background-color: var(--evenza-accent) !important;
            border-color: var(--evenza-accent) !important;
            color: #04170C !important;
        }
        
        /* Stat Icons */
        [data-pc-theme="dark"] .stat-icon.icon-primary { background: rgba(88,166,255,0.12); color: #58A6FF; }
        [data-pc-theme="dark"] .stat-icon.icon-success { background: rgba(34,197,94,0.12); color: #22C55E; }
        [data-pc-theme="dark"] .stat-icon.icon-warning { background: rgba(227,179,65,0.12); color: #E3B341; }
        [data-pc-theme="dark"] .stat-icon.icon-danger { background: rgba(248,81,73,0.12); color: #F85149; }

        /* HIDE DEFAULT THEME OPTION GLOBALLY */
        .dropdown-menu .dropdown-item[data-value="default"],
        .dropdown-menu .dropdown-item[onclick*="default"] {
            display: none !important;
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

            <?php if ($flash): ?>
                <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($flash['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Page Header -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="page-title mb-1">All Teams</h4>
                    <p class="page-subtitle mb-3">Manage teams registered on the Evenza platform</p>
                    <ul class="custom-breadcrumb">
                        <li><a href="Dashboard.php">Home</a></li>
                        <li>Team Management</li>
                        <li>All Teams</li>
                    </ul>
                </div>

                <div class="mt-3 mt-md-0">
                    <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#addTeamModal">
                        <i class="bi bi-plus-lg me-2"></i> Add Team
                    </button>
                </div>
            </div>

            <!-- Statistics -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon icon-primary me-3"><i class="bi bi-people-fill"></i></div>
                            <div>
                                <small class="text-muted">Total Teams</small>
                                <h4 class="mb-0 mt-1"><?= $total ?></h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon icon-success me-3"><i class="bi bi-trophy-fill"></i></div>
                            <div>
                                <small class="text-muted">Total Events</small>
                                <h4 class="mb-0 mt-1"><?= count($events) ?></h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon icon-warning me-3"><i class="bi bi-person-fill-check"></i></div>
                            <div>
                                <small class="text-muted">Available Students</small>
                                <h4 class="mb-0 mt-1"><?= count($students) ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Table -->
            <div class="card main-card shadow-sm">

                <div class="main-card-header">
                    <div class="row align-items-center g-3">
                        <div class="col-md-5">
                            <h5 class="mb-1">Team List</h5>
                            <small class="text-muted">View and manage all teams and members</small>
                        </div>
                        <div class="col-md-7">
                            <div class="row g-2 justify-content-md-end">
                                <div class="col-md-7">
                                    <div class="search-box">
                                        <i class="bi bi-search"></i>
                                        <input type="text" class="form-control" id="searchTeam" placeholder="Search team...">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <select class="form-select" id="eventFilter">
                                        <option value="">All Events</option>
                                        <?php foreach ($events as $ev): ?>
                                            <option value="<?= htmlspecialchars($ev['title']) ?>"><?= htmlspecialchars($ev['title']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="teamTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Team Details</th>
                                    <th>Team Code</th>
                                    <th>Event</th>
                                    <th>Leader</th>
                                    <th>Members</th>
                                    <th>Created Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if ($total === 0): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            No teams yet. Click "Add Team" to create one.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($teams as $i => $t): ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>

                                            <td>
                                                <div class="team-name"><?= htmlspecialchars($t['team_name']) ?></div>
                                                <small class="text-muted">ID: #<?= (int)$t['team_id'] ?></small>
                                            </td>

                                            <td>
                                                <span class="badge bg-primary-subtle text-primary fw-bold">
                                                    <?= htmlspecialchars($t['team_code']) ?>
                                                </span>
                                            </td>

                                            <td><?= htmlspecialchars($t['event_title']) ?></td>

                                            <td>
                                                <div class="fw-semibold"><?= htmlspecialchars($t['leader_name']) ?></div>
                                                <small class="text-muted"><?= htmlspecialchars($t['leader_email']) ?></small>
                                            </td>

                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#membersModal<?= (int)$t['team_id'] ?>">
                                                    <i class="bi bi-people me-1"></i> <?= $t['total_members'] ?> / <?= $t['max_team_size'] ?>
                                                </button>
                                            </td>

                                            <td><?= date('d M Y', strtotime($t['created_at'])) ?></td>

                                            <td class="text-end">
                                                <a href="teamdetails.php?id=<?= (int)$t['team_id'] ?>" class="btn btn-light action-btn me-1" title="View Details">
                                                    <i class="bi bi-eye text-info"></i>
                                                </a>
                                                <button type="button" class="btn btn-light action-btn me-1" title="Edit" data-bs-toggle="modal" data-bs-target="#editTeamModal<?= (int)$t['team_id'] ?>">
                                                    <i class="bi bi-pencil text-primary"></i>
                                                </button>
                                                <button class="btn btn-light action-btn" title="Delete" onclick="deleteTeam(<?= (int)$t['team_id'] ?>)">
                                                    <i class="bi bi-trash text-danger"></i>
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
                                <small class="text-muted ms-2" id="showingCountText">Showing 0 of 0 teams</small>
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

    <!-- ADD TEAM MODAL -->
    <div class="modal fade" id="addTeamModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="form_action" value="create">

                    <div class="modal-header">
                        <h5 class="modal-title">Add Team</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Team Name</label>
                                <input type="text" class="form-control" name="team_name" required minlength="2" maxlength="100">
                                <div class="invalid-feedback">Enter team name.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Team Code <span class="text-muted">(Optional)</span></label>
                                <input type="text" class="form-control" name="team_code" maxlength="30">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Event</label>
                                <select class="form-select" name="event_id" required>
                                    <option value="">Choose Event</option>
                                    <?php if (empty($events)): ?>
                                        <option value="" disabled>No events found in database</option>
                                    <?php else: ?>
                                        <?php foreach ($events as $ev): ?>
                                            <?php $isTeamEvent = $ev['event_type'] === 'team'; ?>
                                            <option value="<?= (int)$ev['event_id'] ?>" <?= !$isTeamEvent ? 'disabled' : '' ?>>
                                                <?= htmlspecialchars($ev['title']) ?> 
                                                <?= $isTeamEvent 
                                                    ? "(Team Event: {$ev['min_team_size']}-{$ev['max_team_size']} members)" 
                                                    : "(Solo Event - Cannot attach team)" ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <div class="invalid-feedback">Select a team event.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Team Leader</label>
                                <select class="form-select" name="leader_id" required>
                                    <option value="">Choose Student Leader</option>
                                    <?php foreach ($students as $st): ?>
                                        <option value="<?= (int)$st['student_id'] ?>"><?= htmlspecialchars($st['name']) ?> (<?= htmlspecialchars($st['enrollment_no'] ?? 'No Enr') ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Select a team leader.</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">
                                    Additional Team Members <span class="text-muted">(Hold Ctrl/Cmd to select multiple)</span>
                                </label>
                                <select class="form-select" name="member_ids[]" multiple style="height: 120px;">
                                    <?php foreach ($students as $st): ?>
                                        <option value="<?= (int)$st['student_id'] ?>">
                                            <?= htmlspecialchars($st['name']) ?> (<?= htmlspecialchars($st['enrollment_no'] ?? 'No Enr') ?> - <?= htmlspecialchars($st['college_name'] ?? 'No College') ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted mt-1 d-block">The team leader selected above will automatically be added as a member.</small>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Team</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- EDIT & POST-CREATION MEMBER MODALS -->
    <?php foreach ($teams as $t): ?>
        <div class="modal fade" id="editTeamModal<?= (int)$t['team_id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" class="needs-validation" novalidate>
                        <input type="hidden" name="form_action" value="update">
                        <input type="hidden" name="id" value="<?= (int)$t['team_id'] ?>">

                        <div class="modal-header">
                            <h5 class="modal-title">Edit Team Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">Team Name</label>
                                    <input type="text" class="form-control" name="team_name" required minlength="2" value="<?= htmlspecialchars($t['team_name']) ?>">
                                    <div class="invalid-feedback">Enter team name.</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Team Code</label>
                                    <input type="text" class="form-control" name="team_code" value="<?= htmlspecialchars($t['team_code']) ?>">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Event</label>
                                    <select class="form-select" name="event_id" required>
                                        <?php foreach ($events as $ev): ?>
                                            <?php if ($ev['event_type'] === 'team' || (int)$ev['event_id'] === (int)$t['event_id']): ?>
                                                <option value="<?= (int)$ev['event_id'] ?>" <?= (int)$ev['event_id'] === (int)$t['event_id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($ev['title']) ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Team Leader</label>
                                    <select class="form-select" name="leader_id" required>
                                        <?php foreach ($students as $st): ?>
                                            <option value="<?= (int)$st['student_id'] ?>" <?= (int)$st['student_id'] === (int)$t['leader_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($st['name']) ?> (<?= htmlspecialchars($st['enrollment_no'] ?? 'No Enr') ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Team</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <?php
            $mStmt = $pdo->prepare('
                SELECT tm.student_id, s.name, s.email, s.enrollment_no, c.name as college_name 
                FROM team_members tm
                JOIN students s ON tm.student_id = s.student_id
                LEFT JOIN colleges c ON s.college_id = c.college_id
                WHERE tm.team_id = :tid');
            $mStmt->execute(['tid' => $t['team_id']]);
            $currentMembers = $mStmt->fetchAll();
            $currentMemberIds = array_column($currentMembers, 'student_id');
        ?>
        <div class="modal fade" id="membersModal<?= (int)$t['team_id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title">Manage Members - <?= htmlspecialchars($t['team_name']) ?></h5>
                            <small class="text-muted">Event Limit: <?= $t['min_team_size'] ?> to <?= $t['max_team_size'] ?> members</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" class="row g-2 mb-4 bg-light p-3 rounded">
                            <input type="hidden" name="member_action" value="add_member">
                            <input type="hidden" name="team_id" value="<?= (int)$t['team_id'] ?>">
                            <div class="col-md-9">
                                <select class="form-select" name="student_id" required>
                                    <option value="">Select Student to Add...</option>
                                    <?php foreach ($students as $st): ?>
                                        <?php if (!in_array($st['student_id'], $currentMemberIds)): ?>
                                            <option value="<?= (int)$st['student_id'] ?>">
                                                <?= htmlspecialchars($st['name']) ?> (<?= htmlspecialchars($st['enrollment_no'] ?? 'No Enr') ?> - <?= htmlspecialchars($st['college_name'] ?? 'No College') ?>)
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-success w-100" <?= count($currentMembers) >= $t['max_team_size'] ? 'disabled' : '' ?>>
                                    <i class="bi bi-person-plus me-1"></i> Add
                                </button>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Student</th>
                                        <th>Enrollment</th>
                                        <th>Role</th>
                                        <th class="text-center" style="width: 80px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($currentMembers)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">No members added yet.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($currentMembers as $m): ?>
                                            <?php $isLeader = (int)$m['student_id'] === (int)$t['leader_id']; ?>
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold text-dark"><?= htmlspecialchars($m['name']) ?></div>
                                                    <small class="text-muted"><?= htmlspecialchars($m['email']) ?></small>
                                                </td>
                                                <td><?= htmlspecialchars($m['enrollment_no'] ?? 'N/A') ?></td>
                                                <td>
                                                    <?= $isLeader 
                                                        ? '<span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i> Leader</span>' 
                                                        : '<span class="badge bg-secondary">Member</span>' ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if (!$isLeader): ?>
                                                        <form method="POST" style="display:inline;" onsubmit="return confirm('Remove this member from team?');">
                                                            <input type="hidden" name="member_action" value="remove_member">
                                                            <input type="hidden" name="team_id" value="<?= (int)$t['team_id'] ?>">
                                                            <input type="hidden" name="student_id" value="<?= (int)$m['student_id'] ?>">
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="bi bi-x-lg"></i>
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <button class="btn btn-sm btn-light text-muted" disabled title="Leader cannot be removed"><i class="bi bi-lock"></i></button>
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
        document.querySelectorAll(".needs-validation").forEach(form => {
            form.addEventListener("submit", function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add("was-validated");
            });
        });

        <?php if ($reopenModal): ?>
            document.addEventListener("DOMContentLoaded", function() {
                const modalEl = document.getElementById(<?= json_encode($reopenModal) ?>);
                if (modalEl) new bootstrap.Modal(modalEl).show();
            });
        <?php endif; ?>

        // Search, Filter, Limit & Compact Pagination Engine
        const searchInput = document.getElementById("searchTeam");
        const eventFilter = document.getElementById("eventFilter");
        const limitSelect = document.getElementById("limitSelect");
        const rows = document.querySelectorAll("#teamTable tbody tr");
        const showingCountText = document.getElementById("showingCountText");
        const paginationContainer = document.getElementById("pagination");

        let currentPage = 1;

        function filterTeams() {
            const searchValue = searchInput.value.toLowerCase();
            const eventValue = eventFilter.value.toLowerCase();
            const limitValue = limitSelect.value;

            // 1. Filter matching rows
            const matchedRows = [];
            rows.forEach(row => {
                if (row.cells.length < 8) return;

                const rowText = row.innerText.toLowerCase();
                const eventText = row.cells[3].innerText.toLowerCase().trim();

                const matchesSearch = rowText.includes(searchValue);
                const matchesEvent = eventValue === "" || eventText === eventValue;

                if (matchesSearch && matchesEvent) {
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
            showingCountText.textContent = `Showing ${visibleCount} of ${totalMatched} teams`;

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
                    filterTeams();
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
                    filterTeams();
                });
                paginationContainer.appendChild(nextLi);
            }
        }

        searchInput.addEventListener("keyup", () => { currentPage = 1; filterTeams(); });
        eventFilter.addEventListener("change", () => { currentPage = 1; filterTeams(); });
        limitSelect.addEventListener("change", () => { currentPage = 1; filterTeams(); });

        document.addEventListener("DOMContentLoaded", filterTeams);

        function deleteTeam(id) {
            if (confirm("Are you sure you want to delete this team?")) {
                window.location.href = "allteams.php?action=delete&id=" + id;
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

    <!-- Script to Hide 'Default' Theme Option -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        function removeDefaultOption() {
            document.querySelectorAll('.dropdown-item').forEach(item => {
                if (item.textContent.trim().toLowerCase() === 'default' || item.textContent.trim().toLowerCase().includes('default')) {
                    item.style.display = 'none';
                }
            });
        }
        removeDefaultOption();
        
        // Use MutationObserver in case the dropdown is injected dynamically by theme.js
        const observer = new MutationObserver(removeDefaultOption);
        observer.observe(document.body, { childList: true, subtree: true });
      });
    </script>

</body>
</html>
