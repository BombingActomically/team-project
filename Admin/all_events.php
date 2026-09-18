<?php
// C:\xampp\htdocs\Project2\Admin\all_events.php

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', '0');

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
    $pdo = new PDO("mysql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME};charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    die('Database connection failed.');
}

/* =========================================================
   AJAX: STATUS TOGGLE
   ========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['action'] ?? '') === 'toggle_status') {
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');

    $body = json_decode(file_get_contents('php://input'), true);
    $id = filter_var($body['id'] ?? null, FILTER_VALIDATE_INT);
    $status = strtolower(trim($body['status'] ?? ''));

    if (!$id || !in_array($status, ['active', 'inactive', 'cancelled', 'draft', 'published', 'completed'], true)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare('UPDATE events SET status = :status WHERE event_id = :id');
        $stmt->execute(['status' => $status, 'id' => $id]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error.']);
    }
    exit;
}

/* =========================================================
   VALIDATION HELPER
   ========================================================= */
function validate_event(PDO $pdo, array $data): array {
    $errors = [];

    $title = trim($data['title'] ?? '');
    if (empty($title) || mb_strlen($title) < 3 || mb_strlen($title) > 150) {
        $errors['title'] = 'Event title must be between 3 and 150 characters.';
    }

    $college_id = filter_var($data['college_id'] ?? null, FILTER_VALIDATE_INT);
    if (!$college_id) {
        $errors['college_id'] = 'Valid College selection is required.';
    }

    $category_id = filter_var($data['category_id'] ?? null, FILTER_VALIDATE_INT);
    if (!$category_id) {
        $errors['category_id'] = 'Valid Category selection is required.';
    }

    $event_type = trim($data['event_type'] ?? '');
    if (!in_array($event_type, ['solo', 'team'])) {
        $errors['event_type'] = 'Event type must be Solo or Team.';
    }

    $min_team = filter_var($data['min_team_size'] ?? 1, FILTER_VALIDATE_INT);
    $max_team = filter_var($data['max_team_size'] ?? 1, FILTER_VALIDATE_INT);
    if ($event_type === 'team') {
        if ($min_team < 2) $errors['min_team_size'] = 'Minimum team size must be at least 2.';
        if ($max_team < $min_team) $errors['max_team_size'] = 'Max team size must be >= min size.';
    }

    $fee = filter_var($data['registration_fee'] ?? null, FILTER_VALIDATE_FLOAT);
    if ($fee === false || $fee === null || $fee < 0) {
        $errors['registration_fee'] = 'Registration fee must be a valid non-negative amount.';
    }

    $event_date = trim($data['event_date'] ?? '');
    if (empty($event_date)) {
        $errors['event_date'] = 'Event date is required.';
    }

    $start_time = trim($data['start_time'] ?? '');
    if (empty($start_time)) {
        $errors['start_time'] = 'Start time is required.';
    }

    $end_time = trim($data['end_time'] ?? '');
    if (empty($end_time)) {
        $errors['end_time'] = 'End time is required.';
    } elseif (!empty($start_time) && strtotime($end_time) <= strtotime($start_time)) {
        $errors['end_time'] = 'End time must be after start time.';
    }

    $deadline = trim($data['registration_deadline'] ?? '');
    if (empty($deadline)) {
        $errors['registration_deadline'] = 'Registration deadline is required.';
    }

    $venue = trim($data['venue'] ?? '');
    if (empty($venue) || mb_strlen($venue) < 3) {
        $errors['venue'] = 'Venue requires at least 3 characters.';
    }

    $desc = trim($data['description'] ?? '');
    if (empty($desc) || mb_strlen($desc) < 10) {
        $errors['description'] = 'Description requires at least 10 characters.';
    }

    $status = trim($data['status'] ?? '');
    if (!in_array($status, ['active', 'inactive', 'cancelled', 'draft', 'published', 'completed'])) {
        $errors['status'] = 'Invalid status selected.';
    }

    return $errors;
}

/* =========================================================
   AJAX: CREATE / UPDATE EVENT ENDPOINT
   ========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_action'])) {
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');

    $action = $_POST['form_action'];
    $errors = validate_event($pdo, $_POST);

    if (!empty($errors)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    try {
        if ($action === 'create') {
            $stmt = $pdo->prepare('
                INSERT INTO events (title, description, event_date, start_time, end_time, venue, registration_deadline, event_type, min_team_size, max_team_size, registration_fee, college_id, category_id, status)
                VALUES (:title, :description, :event_date, :start_time, :end_time, :venue, :registration_deadline, :event_type, :min_team_size, :max_team_size, :registration_fee, :college_id, :category_id, :status)
            ');
            $stmt->execute([
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'event_date' => $_POST['event_date'],
                'start_time' => $_POST['start_time'],
                'end_time' => $_POST['end_time'],
                'venue' => trim($_POST['venue']),
                'registration_deadline' => str_replace('T', ' ', $_POST['registration_deadline']),
                'event_type' => $_POST['event_type'],
                'min_team_size' => $_POST['event_type'] === 'team' ? (int)$_POST['min_team_size'] : 1,
                'max_team_size' => $_POST['event_type'] === 'team' ? (int)$_POST['max_team_size'] : 1,
                'registration_fee' => (float)$_POST['registration_fee'],
                'college_id' => (int)$_POST['college_id'],
                'category_id' => (int)$_POST['category_id'],
                'status' => $_POST['status']
            ]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Event created successfully.'];
        } elseif ($action === 'update') {
            $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
            $stmt = $pdo->prepare('
                UPDATE events SET 
                    title=:title, description=:description, event_date=:event_date, start_time=:start_time, end_time=:end_time, 
                    venue=:venue, registration_deadline=:registration_deadline, event_type=:event_type, 
                    min_team_size=:min_team_size, max_team_size=:max_team_size, registration_fee=:registration_fee, 
                    college_id=:college_id, category_id=:category_id, status=:status
                WHERE event_id = :id
            ');
            $stmt->execute([
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'event_date' => $_POST['event_date'],
                'start_time' => $_POST['start_time'],
                'end_time' => $_POST['end_time'],
                'venue' => trim($_POST['venue']),
                'registration_deadline' => str_replace('T', ' ', $_POST['registration_deadline']),
                'event_type' => $_POST['event_type'],
                'min_team_size' => $_POST['event_type'] === 'team' ? (int)$_POST['min_team_size'] : 1,
                'max_team_size' => $_POST['event_type'] === 'team' ? (int)$_POST['max_team_size'] : 1,
                'registration_fee' => (float)$_POST['registration_fee'],
                'college_id' => (int)$_POST['college_id'],
                'category_id' => (int)$_POST['category_id'],
                'status' => $_POST['status'],
                'id' => $id
            ]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Event updated successfully.'];
        }
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

/* =========================================================
   DELETE EVENT
   ========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['action'] ?? '') === 'delete') {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($id) {
        try {
            $pdo->prepare('DELETE FROM events WHERE event_id = :id')->execute(['id' => $id]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Event deleted successfully.'];
        } catch (PDOException $e) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Cannot delete event. It has active registrations linked to it.'];
        }
    }
    header('Location: all_events.php');
    exit;
}

/* =========================================================
   DATA FETCHING
   ========================================================= */
$colleges = $pdo->query("SELECT college_id, name FROM colleges WHERE status='active' ORDER BY name")->fetchAll();
$categories = $pdo->query("SELECT category_id, name FROM categories WHERE status='active' ORDER BY name")->fetchAll();

$eventsSql = "SELECT e.*, c.name as college_name, cat.name as category_name 
              FROM events e 
              LEFT JOIN colleges c ON e.college_id = c.college_id 
              LEFT JOIN categories cat ON e.category_id = cat.category_id 
              ORDER BY e.created_at DESC";
$events = $pdo->query($eventsSql)->fetchAll();

$total = count($events);
$solo_events = count(array_filter($events, fn($e) => $e['event_type'] === 'solo'));
$team_events = count(array_filter($events, fn($e) => $e['event_type'] === 'team'));

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!doctype html>
<html lang="en">
<head>
    <title>All Events | Eventura Admin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="assets/fonts/phosphor/duotone/style.css" />
    <link rel="stylesheet" href="assets/fonts/tabler-icons.min.css" />
    <link rel="stylesheet" href="assets/fonts/feather.css" />
    <link rel="stylesheet" href="assets/fonts/fontawesome.css" />
    <link rel="stylesheet" href="assets/fonts/material.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Match Theme Styling -->
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
        .event-title { font-weight: 600; color: #1f2937; }
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .action-btn { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; }
        .page-link { cursor: pointer; }
    </style>

    <!-- Dark Theme Specific Overrides -->
    <style>
        :root{ --evenza-bg:#0D1117; --evenza-card:#161B22; --evenza-border:rgba(255,255,255,0.07); --evenza-accent:#22C55E; --evenza-accent-soft:rgba(34,197,94,0.12); }
        [data-pc-theme="dark"] body { background: var(--evenza-bg) !important; }
        [data-pc-theme="dark"] .pc-container { background: var(--evenza-bg) !important; }
        [data-pc-theme="dark"] .card, [data-pc-theme="dark"] .main-card { background-color: var(--evenza-card) !important; border-color: var(--evenza-border) !important; }
        [data-pc-theme="dark"] .main-card-header, [data-pc-theme="dark"] .card-footer { background-color: transparent !important; border-bottom: 1px solid var(--evenza-border) !important; border-top: 1px solid var(--evenza-border) !important; }
        [data-pc-theme="dark"] .page-title, [data-pc-theme="dark"] .event-title, [data-pc-theme="dark"] h5, [data-pc-theme="dark"] .text-dark, [data-pc-theme="dark"] .card-body h4, [data-pc-theme="dark"] h6 { color: #E6EDF3 !important; }
        [data-pc-theme="dark"] .text-muted, [data-pc-theme="dark"] .page-subtitle, [data-pc-theme="dark"] .custom-breadcrumb li, [data-pc-theme="dark"] .custom-breadcrumb li a, [data-pc-theme="dark"] #showingCountText { color: #8B949E !important; }
        [data-pc-theme="dark"] .table { --bs-table-bg: transparent !important; color: #E6EDF3 !important; }
        [data-pc-theme="dark"] .table th, [data-pc-theme="dark"] .table td { background-color: transparent !important; border-bottom: 1px solid var(--evenza-border) !important; color: #E6EDF3 !important; }
        [data-pc-theme="dark"] .table thead th { background: var(--evenza-bg) !important; color: #8B949E !important; border-bottom: 1px solid rgba(255,255,255,0.1) !important; }
        [data-pc-theme="dark"] .table tbody tr:hover td { background-color: rgba(255, 255, 255, 0.04) !important; }
        [data-pc-theme="dark"] .form-control, [data-pc-theme="dark"] .form-select { background-color: #0D1117 !important; border-color: var(--evenza-border) !important; color: #E6EDF3 !important; color-scheme: dark !important; }
        [data-pc-theme="dark"] .form-control:focus, [data-pc-theme="dark"] .form-select:focus { background-color: #0D1117 !important; border-color: var(--evenza-accent) !important; box-shadow: 0 0 0 3px var(--evenza-accent-soft) !important; }
        [data-pc-theme="dark"] .action-btn { background-color: #232B36 !important; border-color: #232B36 !important; color: #E6EDF3 !important; }
        [data-pc-theme="dark"] .action-btn:hover { background-color: #303B4A !important; }
        [data-pc-theme="dark"] .modal-content { background-color: var(--evenza-card) !important; color: #E6EDF3 !important; border-color: var(--evenza-border) !important; }
        [data-pc-theme="dark"] .modal-header, [data-pc-theme="dark"] .modal-footer { border-color: var(--evenza-border) !important; }
        [data-pc-theme="dark"] .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
        [data-pc-theme="dark"] .page-link { background-color: #0D1117 !important; border-color: var(--evenza-border) !important; color: #E6EDF3 !important; }
        [data-pc-theme="dark"] .page-item.active .page-link { background-color: var(--evenza-accent) !important; border-color: var(--evenza-accent) !important; color: #04170C !important; }
        [data-pc-theme="dark"] .stat-icon.icon-primary { background: rgba(88,166,255,0.12); color: #58A6FF; }
        [data-pc-theme="dark"] .stat-icon.icon-success { background: rgba(34,197,94,0.12); color: #22C55E; }
        [data-pc-theme="dark"] .stat-icon.icon-warning { background: rgba(227,179,65,0.12); color: #E3B341; }
        .dropdown-menu .dropdown-item[data-value="default"], .dropdown-menu .dropdown-item[onclick*="default"] { display: none !important; }
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
                <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show mb-4" role="alert">
                    <?= $flash['message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Page Header -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="page-title mb-1">Event Master Catalog</h4>
                    <p class="page-subtitle mb-3">Create, toggle, and manage all events</p>
                    <ul class="custom-breadcrumb">
                        <li><a href="Dashboard.php">Home</a></li>
                        <li>Events</li>
                        <li>All Events</li>
                    </ul>
                </div>
                <div class="mt-3 mt-md-0">
                    <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#addEventModal">
                        <i class="bi bi-plus-lg me-2"></i> Add Event
                    </button>
                </div>
            </div>

            <!-- Statistics -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon icon-primary me-3"><i class="bi bi-calendar-event"></i></div>
                            <div>
                                <small class="text-muted">Total Events</small>
                                <h4 class="mb-0 mt-1"><?= $total ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon icon-success me-3"><i class="bi bi-person"></i></div>
                            <div>
                                <small class="text-muted">Solo Events</small>
                                <h4 class="mb-0 mt-1"><?= $solo_events ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon icon-warning me-3"><i class="bi bi-people"></i></div>
                            <div>
                                <small class="text-muted">Team Events</small>
                                <h4 class="mb-0 mt-1"><?= $team_events ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Events Table -->
            <div class="card main-card shadow-sm">
                <div class="main-card-header">
                    <div class="row align-items-center g-3">
                        <div class="col-md-5">
                            <h5 class="mb-1">All Events</h5>
                            <small class="text-muted">List of all created events and workshops</small>
                        </div>
                        <div class="col-md-7">
                            <div class="row g-2 justify-content-md-end">
                                <div class="col-md-7">
                                    <div class="search-box">
                                        <i class="bi bi-search"></i>
                                        <input type="text" class="form-control" id="searchEvent" placeholder="Search event or college...">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <select class="form-select" id="typeFilter">
                                        <option value="">All Types</option>
                                        <option value="solo">Solo Event</option>
                                        <option value="team">Team Event</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="eventTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Event Details</th>
                                    <th>College / Category</th>
                                    <th>Date & Time</th>
                                    <th>Type & Fee</th>
                                    <th>Status Toggle</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($total === 0): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">No events found. Click "Add Event".</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($events as $i => $ev): ?>
                                        <?php $isActive = $ev['status'] === 'active' || $ev['status'] === 'published'; ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td>
                                                <div class="event-title"><?= htmlspecialchars($ev['title']) ?></div>
                                                <small class="text-muted">Venue: <?= htmlspecialchars($ev['venue']) ?></small>
                                            </td>
                                            <td>
                                                <div class="text-dark fw-medium" style="font-size: 13px;"><?= htmlspecialchars($ev['college_name'] ?? 'Unknown College') ?></div>
                                                <span class="badge bg-light text-secondary border mt-1"><?= htmlspecialchars($ev['category_name'] ?? 'General') ?></span>
                                            </td>
                                            <td>
                                                <div class="text-dark" style="font-size: 13px;"><i class="bi bi-calendar me-1"></i><?= date('d M Y', strtotime($ev['event_date'])) ?></div>
                                                <div class="text-muted small"><i class="bi bi-clock me-1"></i><?= $ev['start_time'] ? date('h:i A', strtotime($ev['start_time'])) : 'TBA' ?></div>
                                            </td>
                                            <td data-type="<?= htmlspecialchars($ev['event_type']) ?>">
                                                <?php if($ev['event_type'] === 'team'): ?>
                                                    <span class="badge bg-info-subtle text-info"><i class="bi bi-people me-1"></i> Team (<?= $ev['min_team_size'] ?>-<?= $ev['max_team_size'] ?>)</span>
                                                <?php else: ?>
                                                    <span class="badge bg-primary-subtle text-primary"><i class="bi bi-person me-1"></i> Solo</span>
                                                <?php endif; ?>
                                                <div class="mt-1 fw-bold text-success small">
                                                    <?= $ev['registration_fee'] > 0 ? '₹' . number_format($ev['registration_fee'], 2) : 'Free' ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input status-toggle" type="checkbox" role="switch" data-id="<?= (int)$ev['event_id'] ?>" <?= $isActive ? 'checked' : '' ?>>
                                                    <label class="form-check-label ms-2 status-label <?= $isActive ? 'text-success' : 'text-danger' ?>" style="font-size:12px; font-weight:600;"><?= ucfirst($ev['status']) ?></label>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-light action-btn me-1" title="Edit" data-bs-toggle="modal" data-bs-target="#editEventModal<?= (int)$ev['event_id'] ?>">
                                                    <i class="bi bi-pencil text-primary"></i>
                                                </button>
                                                <button class="btn btn-light action-btn" title="Delete" onclick="deleteEvent(<?= (int)$ev['event_id'] ?>)">
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

                <!-- Footer limits and pagination -->
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
                                <small class="text-muted ms-2" id="showingCountText">Showing 0 of 0 events</small>
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

    <!-- ===================== ADD EVENT MODAL ===================== -->
    <div class="modal fade" id="addEventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="addEventForm" onsubmit="submitEventForm(event, 'addEventForm')">
                    <input type="hidden" name="form_action" value="create">
                    
                    <div class="modal-header">
                        <h5 class="modal-title">Create New Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12"><h6 class="text-primary fw-bold mb-0">Core Details</h6><hr class="mt-2 mb-0"></div>
                            
                            <div class="col-md-12">
                                <label class="form-label text-muted">Event Title *</label>
                                <input type="text" class="form-control" name="title" placeholder="e.g. CodeStorm Hackathon 2026" oninput="clearFieldError(this)">
                                <div class="invalid-feedback" data-field="title"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted">Host College *</label>
                                <select class="form-select" name="college_id" onchange="clearFieldError(this)">
                                    <option value="">Select College</option>
                                    <?php foreach($colleges as $c): ?>
                                        <option value="<?= $c['college_id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback" data-field="college_id"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted">Category *</label>
                                <select class="form-select" name="category_id" onchange="clearFieldError(this)">
                                    <option value="">Select Category</option>
                                    <?php foreach($categories as $cat): ?>
                                        <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback" data-field="category_id"></div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label text-muted">Full Description *</label>
                                <textarea class="form-control" name="description" rows="3" placeholder="Detail the event rules, prizes, and instructions..." oninput="clearFieldError(this)"></textarea>
                                <div class="invalid-feedback" data-field="description"></div>
                            </div>

                            <div class="col-12 mt-4"><h6 class="text-primary fw-bold mb-0">Configuration & Schedule</h6><hr class="mt-2 mb-0"></div>

                            <div class="col-md-4">
                                <label class="form-label text-muted">Event Type *</label>
                                <select class="form-select" name="event_type" id="addEventType" onchange="toggleTeamFields('add'); clearFieldError(this);">
                                    <option value="solo">Solo Participant</option>
                                    <option value="team">Team Competition</option>
                                </select>
                                <div class="invalid-feedback" data-field="event_type"></div>
                            </div>
                            
                            <div class="col-md-4 team-field-add" style="display: none;">
                                <label class="form-label text-muted">Min Team Size *</label>
                                <input type="number" class="form-control" name="min_team_size" id="addMinTeam" min="2" value="2" oninput="clearFieldError(this)">
                                <div class="invalid-feedback" data-field="min_team_size"></div>
                            </div>
                            <div class="col-md-4 team-field-add" style="display: none;">
                                <label class="form-label text-muted">Max Team Size *</label>
                                <input type="number" class="form-control" name="max_team_size" id="addMaxTeam" min="2" value="4" oninput="clearFieldError(this)">
                                <div class="invalid-feedback" data-field="max_team_size"></div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-muted">Registration Fee (₹) *</label>
                                <input type="number" class="form-control" name="registration_fee" min="0" step="0.01" value="0" oninput="clearFieldError(this)">
                                <div class="invalid-feedback" data-field="registration_fee"></div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-muted">Venue / Location *</label>
                                <input type="text" class="form-control" name="venue" placeholder="e.g. Main Auditorium" oninput="clearFieldError(this)">
                                <div class="invalid-feedback" data-field="venue"></div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-muted">Event Status *</label>
                                <select class="form-select" name="status" onchange="clearFieldError(this)">
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive / Draft</option>
                                </select>
                                <div class="invalid-feedback" data-field="status"></div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label text-muted">Event Date *</label>
                                <input type="date" class="form-control" name="event_date" oninput="clearFieldError(this)">
                                <div class="invalid-feedback" data-field="event_date"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted">Start Time *</label>
                                <input type="time" class="form-control" name="start_time" oninput="clearFieldError(this)">
                                <div class="invalid-feedback" data-field="start_time"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted">End Time *</label>
                                <input type="time" class="form-control" name="end_time" oninput="clearFieldError(this)">
                                <div class="invalid-feedback" data-field="end_time"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted">Registration Deadline *</label>
                                <input type="datetime-local" class="form-control" name="registration_deadline" oninput="clearFieldError(this)">
                                <div class="invalid-feedback" data-field="registration_deadline"></div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-circle me-1"></i> Save Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ===================== EDIT EVENT MODALS ===================== -->
    <?php foreach ($events as $ev): ?>
        <div class="modal fade" id="editEventModal<?= (int)$ev['event_id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form id="editEventForm<?= (int)$ev['event_id'] ?>" onsubmit="submitEventForm(event, 'editEventForm<?= (int)$ev['event_id'] ?>')">
                        <input type="hidden" name="form_action" value="update">
                        <input type="hidden" name="id" value="<?= (int)$ev['event_id'] ?>">
                        
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Event: <?= htmlspecialchars($ev['title']) ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12"><h6 class="text-primary fw-bold mb-0">Core Details</h6><hr class="mt-2 mb-0"></div>
                                
                                <div class="col-md-12">
                                    <label class="form-label text-muted">Event Title *</label>
                                    <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($ev['title']) ?>" oninput="clearFieldError(this)">
                                    <div class="invalid-feedback" data-field="title"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted">Host College *</label>
                                    <select class="form-select" name="college_id" onchange="clearFieldError(this)">
                                        <?php foreach($colleges as $c): ?>
                                            <option value="<?= $c['college_id'] ?>" <?= $ev['college_id'] == $c['college_id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback" data-field="college_id"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted">Category *</label>
                                    <select class="form-select" name="category_id" onchange="clearFieldError(this)">
                                        <?php foreach($categories as $cat): ?>
                                            <option value="<?= $cat['category_id'] ?>" <?= $ev['category_id'] == $cat['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback" data-field="category_id"></div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label text-muted">Full Description *</label>
                                    <textarea class="form-control" name="description" rows="3" oninput="clearFieldError(this)"><?= htmlspecialchars($ev['description']) ?></textarea>
                                    <div class="invalid-feedback" data-field="description"></div>
                                </div>

                                <div class="col-12 mt-4"><h6 class="text-primary fw-bold mb-0">Configuration & Schedule</h6><hr class="mt-2 mb-0"></div>

                                <div class="col-md-4">
                                    <label class="form-label text-muted">Event Type *</label>
                                    <select class="form-select" name="event_type" id="editEventType<?= $ev['event_id'] ?>" onchange="toggleTeamFields('edit<?= $ev['event_id'] ?>'); clearFieldError(this);">
                                        <option value="solo" <?= $ev['event_type'] === 'solo' ? 'selected' : '' ?>>Solo Participant</option>
                                        <option value="team" <?= $ev['event_type'] === 'team' ? 'selected' : '' ?>>Team Competition</option>
                                    </select>
                                    <div class="invalid-feedback" data-field="event_type"></div>
                                </div>
                                
                                <div class="col-md-4 team-field-edit<?= $ev['event_id'] ?>" style="display: <?= $ev['event_type'] === 'team' ? 'block' : 'none' ?>;">
                                    <label class="form-label text-muted">Min Size *</label>
                                    <input type="number" class="form-control" name="min_team_size" id="editMinTeam<?= $ev['event_id'] ?>" min="2" value="<?= $ev['min_team_size'] ?>" oninput="clearFieldError(this)">
                                    <div class="invalid-feedback" data-field="min_team_size"></div>
                                </div>
                                <div class="col-md-4 team-field-edit<?= $ev['event_id'] ?>" style="display: <?= $ev['event_type'] === 'team' ? 'block' : 'none' ?>;">
                                    <label class="form-label text-muted">Max Size *</label>
                                    <input type="number" class="form-control" name="max_team_size" id="editMaxTeam<?= $ev['event_id'] ?>" min="2" value="<?= $ev['max_team_size'] ?>" oninput="clearFieldError(this)">
                                    <div class="invalid-feedback" data-field="max_team_size"></div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-muted">Registration Fee (₹) *</label>
                                    <input type="number" class="form-control" name="registration_fee" min="0" step="0.01" value="<?= $ev['registration_fee'] ?>" oninput="clearFieldError(this)">
                                    <div class="invalid-feedback" data-field="registration_fee"></div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-muted">Venue / Location *</label>
                                    <input type="text" class="form-control" name="venue" value="<?= htmlspecialchars($ev['venue']) ?>" oninput="clearFieldError(this)">
                                    <div class="invalid-feedback" data-field="venue"></div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-muted">Event Status *</label>
                                    <select class="form-select" name="status" onchange="clearFieldError(this)">
                                        <option value="active" <?= $ev['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="inactive" <?= $ev['status'] === 'inactive' ? 'selected' : '' ?>>Inactive / Draft</option>
                                        <option value="cancelled" <?= $ev['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                    <div class="invalid-feedback" data-field="status"></div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label text-muted">Event Date *</label>
                                    <input type="date" class="form-control" name="event_date" value="<?= $ev['event_date'] ?>" oninput="clearFieldError(this)">
                                    <div class="invalid-feedback" data-field="event_date"></div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-muted">Start Time *</label>
                                    <input type="time" class="form-control" name="start_time" value="<?= $ev['start_time'] ?>" oninput="clearFieldError(this)">
                                    <div class="invalid-feedback" data-field="start_time"></div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-muted">End Time *</label>
                                    <input type="time" class="form-control" name="end_time" value="<?= $ev['end_time'] ?>" oninput="clearFieldError(this)">
                                    <div class="invalid-feedback" data-field="end_time"></div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-muted">Registration Deadline *</label>
                                    <?php 
                                        $formattedDeadline = !empty($ev['registration_deadline']) ? date('Y-m-d\TH:i', strtotime($ev['registration_deadline'])) : ''; 
                                    ?>
                                    <input type="datetime-local" class="form-control" name="registration_deadline" value="<?= $formattedDeadline ?>" oninput="clearFieldError(this)">
                                    <div class="invalid-feedback" data-field="registration_deadline"></div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-arrow-repeat me-1"></i> Update Event</button>
                        </div>
                    </form>
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
        // Professional AJAX Form Submission Handler (Inline errors, live clearing, no reloads on fail)
        function submitEventForm(event, formId) {
            event.preventDefault();
            const form = document.getElementById(formId);
            const formData = new FormData(form);

            // Clear previous errors
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

            fetch('all_events.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else if (data.errors) {
                    for (const [field, message] of Object.entries(data.errors)) {
                        const input = form.querySelector(`[name="${field}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                            const feedback = form.querySelector(`.invalid-feedback[data-field="${field}"]`);
                            if (feedback) feedback.textContent = message;
                        }
                    }
                }
            })
            .catch(err => {
                console.error('Submission error:', err);
                alert('An unexpected error occurred. Please try again.');
            });
        }

        // Live error clearing as the user fills out details
        function clearFieldError(input) {
            input.classList.remove('is-invalid');
            const feedback = input.closest('.row, .col-md-12, .col-md-6, .col-md-4, .col-md-3').querySelector('.invalid-feedback');
            if (feedback) feedback.textContent = '';
        }

        function toggleTeamFields(prefix) {
            let typeSelect = document.getElementById(prefix + 'EventType');
            let fields = document.querySelectorAll('.team-field-' + prefix);
            let minInput = document.getElementById(prefix + 'MinTeam');
            let maxInput = document.getElementById(prefix + 'MaxTeam');
            
            if (typeSelect && typeSelect.value === 'team') {
                fields.forEach(f => f.style.display = 'block');
                if (minInput) minInput.setAttribute('required', 'required');
                if (maxInput) maxInput.setAttribute('required');
            } else {
                fields.forEach(f => f.style.display = 'none');
                if (minInput) minInput.removeAttribute('required');
                if (maxInput) maxInput.removeAttribute('required');
            }
        }

        // Search, Filter, Limit & Pagination Engine
        const searchInput = document.getElementById("searchEvent");
        const typeFilter = document.getElementById("typeFilter");
        const limitSelect = document.getElementById("limitSelect");
        const rows = document.querySelectorAll("#eventTable tbody tr");
        const showingCountText = document.getElementById("showingCountText");
        const paginationContainer = document.getElementById("pagination");

        let currentPage = 1;

        function filterEvents() {
            const searchValue = searchInput.value.toLowerCase();
            const typeValue = typeFilter.value.toLowerCase();
            const limitValue = limitSelect.value;

            const matchedRows = [];
            rows.forEach(row => {
                if (row.cells.length < 7) return; 

                const rowText = row.innerText.toLowerCase();
                const typeAttr = row.cells[4].getAttribute("data-type");
                const typeRaw = typeAttr ? typeAttr.toLowerCase().trim() : "";

                const matchesSearch = rowText.includes(searchValue);
                const matchesType = typeValue === "" || typeRaw === typeValue;

                if (matchesSearch && matchesType) {
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
            showingCountText.textContent = `Showing ${visibleCount} of ${totalMatched} events`;

            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            paginationContainer.innerHTML = "";
            if (totalPages <= 1) return;

            if (currentPage > 1) {
                const prevLi = document.createElement("li");
                prevLi.className = "page-item";
                prevLi.innerHTML = `<a class="page-link"><i class="bi bi-chevron-left"></i></a>`;
                prevLi.addEventListener("click", () => { currentPage--; filterEvents(); });
                paginationContainer.appendChild(prevLi);
            }

            const currentLi = document.createElement("li");
            currentLi.className = "page-item active";
            currentLi.innerHTML = `<a class="page-link">${currentPage}</a>`;
            paginationContainer.appendChild(currentLi);

            if (currentPage < totalPages) {
                const nextLi = document.createElement("li");
                nextLi.className = "page-item";
                nextLi.innerHTML = `<a class="page-link"><i class="bi bi-chevron-right"></i></a>`;
                nextLi.addEventListener("click", () => { currentPage++; filterEvents(); });
                paginationContainer.appendChild(nextLi);
            }
        }

        searchInput.addEventListener("keyup", () => { currentPage = 1; filterEvents(); });
        typeFilter.addEventListener("change", () => { currentPage = 1; filterEvents(); });
        limitSelect.addEventListener("change", () => { currentPage = 1; filterEvents(); });
        document.addEventListener("DOMContentLoaded", filterEvents);

        // Status Toggle (AJAX)
        document.querySelectorAll(".status-toggle").forEach(function(toggle) {
            toggle.addEventListener("change", function() {
                const row = this.closest("tr");
                const eventId = this.dataset.id;
                const isActive = this.checked;
                const label = row.querySelector('.status-label');

                if (isActive) {
                    label.textContent = "Active";
                    label.classList.replace("text-danger", "text-success");
                } else {
                    label.textContent = "Inactive";
                    label.classList.replace("text-success", "text-danger");
                }

                this.disabled = true;

                fetch("all_events.php?action=toggle_status", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        id: eventId,
                        status: isActive ? "active" : "inactive"
                    })
                })
                .then(res => {
                    if (!res.ok) throw new Error("Request failed");
                    return res.json();
                })
                .then(data => {
                    if (!data || !data.success) throw new Error("Update rejected");
                })
                .catch(() => {
                    toggle.checked = !isActive;
                    if (!isActive) {
                        label.textContent = "Active";
                        label.classList.replace("text-danger", "text-success");
                    } else {
                        label.textContent = "Inactive";
                        label.classList.replace("text-success", "text-danger");
                    }
                    alert("Could not update status. Please try again.");
                })
                .finally(() => {
                    this.disabled = false;
                });
            });
        });

        function deleteEvent(id) {
            if (confirm("WARNING: Are you sure you want to delete this event? This action cannot be undone.")) {
                window.location.href = "all_events.php?action=delete&id=" + id;
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