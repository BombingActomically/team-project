<?php
/**
 * all_events.php
 * Single-file Event management: DB connection, validation,
 * create / read / update / delete, status toggle (AJAX), and UI theme matching alluniversity.php.
 *
 * Events are linked to a College (events.college_id -> colleges.college_id),
 * the same way a College is linked to a University (colleges.university_id ->
 * universities.university_id) in colleges.php. The college_id (and, when
 * supplied, category_id) is now verified to actually exist before an event
 * is saved, and every other field gets full server-side validation in
 * addition to the existing client-side (HTML5) checks.
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

/**
 * Full server-side validation for an event payload.
 * Mirrors the depth of validate_college()/validate_university() in the
 * sibling files: required fields, format checks, cross-field rules, and
 * a foreign-key existence check for college_id (and category_id, when given).
 */
function validate_event(PDO $pdo, array $data): array
{
    $errors = [];

    $college_id_raw         = $data['college_id'] ?? null;
    $category_id_raw        = $data['category_id'] ?? '';
    $college_id             = filter_var($college_id_raw, FILTER_VALIDATE_INT);
    $category_id            = ($category_id_raw !== '' && $category_id_raw !== null)
        ? filter_var($category_id_raw, FILTER_VALIDATE_INT)
        : null;
    $title                  = trim($data['title'] ?? '');
    $description             = trim($data['description'] ?? '');
    $event_type             = trim($data['event_type'] ?? '');
    $min_team_size          = filter_var($data['min_team_size'] ?? 1, FILTER_VALIDATE_INT);
    $max_team_size          = filter_var($data['max_team_size'] ?? 1, FILTER_VALIDATE_INT);
    $fee_type               = trim($data['fee_type'] ?? '');
    $registration_fee       = filter_var($data['registration_fee'] ?? 0.00, FILTER_VALIDATE_FLOAT);
    $registration_deadline  = trim($data['registration_deadline'] ?? '');
    $event_date             = trim($data['event_date'] ?? '');
    $start_time             = trim($data['start_time'] ?? '');
    $end_time                = trim($data['end_time'] ?? '');
    $venue                   = trim($data['venue'] ?? '');
    $dress_code              = trim($data['dress_code'] ?? '');
    $status                  = trim($data['status'] ?? 'draft');

    /* ---------- College (required, FK must exist) ---------- */
    if (!$college_id) {
        $errors[] = 'Please select a college.';
    } else {
        $chk = $pdo->prepare('SELECT college_id FROM colleges WHERE college_id = :id');
        $chk->execute(['id' => $college_id]);
        if (!$chk->fetch()) {
            $errors[] = 'Selected college does not exist.';
        }
    }

    /* ---------- Category (required, FK must exist) ---------- */
    if (!$category_id) {
        $errors[] = 'Please select a category.';
    } else {
        $chk = $pdo->prepare('SELECT category_id FROM categories WHERE category_id = :id');
        $chk->execute(['id' => $category_id]);
        if (!$chk->fetch()) {
            $errors[] = 'Selected category does not exist.';
        }
    }

    /* ---------- Title ---------- */
    if ($title === '' || mb_strlen($title) < 2) {
        $errors[] = 'Event title must be at least 2 characters.';
    } elseif (mb_strlen($title) > 150) {
        $errors[] = 'Title cannot exceed 150 characters.';
    }

    /* ---------- Description ---------- */
    if ($description === '' || mb_strlen($description) < 10) {
        $errors[] = 'Description is required (at least 10 characters).';
    } elseif (mb_strlen($description) > 2000) {
        $errors[] = 'Description cannot exceed 2000 characters.';
    }

    /* ---------- Event type ---------- */
    if (!in_array($event_type, ['solo', 'team'], true)) {
        $errors[] = 'Select a valid event type.';
    }

    /* ---------- Team size (only enforced for team events) ---------- */
    if ($event_type === 'team') {
        if ($min_team_size === false || $min_team_size < 1) {
            $errors[] = 'Minimum team size must be at least 1.';
        }
        if ($max_team_size === false || $max_team_size < 1) {
            $errors[] = 'Maximum team size must be at least 1.';
        }
        if ($min_team_size !== false && $max_team_size !== false && $max_team_size < $min_team_size) {
            $errors[] = 'Maximum team size cannot be less than minimum team size.';
        }
        if ($max_team_size !== false && $max_team_size > 100) {
            $errors[] = 'Maximum team size cannot exceed 100.';
        }
    }

    /* ---------- Fee type ---------- */
    if (!in_array($fee_type, ['per_person', 'per_team'], true)) {
        $errors[] = 'Select a valid fee type.';
    }

    /* ---------- Registration fee ---------- */
    if ($registration_fee === false) {
        $errors[] = 'Enter a valid registration fee.';
    } elseif ($registration_fee < 0) {
        $errors[] = 'Registration fee cannot be less than 0.';
    } elseif ($registration_fee > 1000000) {
        $errors[] = 'Registration fee cannot exceed ₹10,00,000.';
    }

    /* ---------- Event date ---------- */
    $eventDateObj = null;
    if ($event_date === '') {
        $errors[] = 'Event date is required.';
    } else {
        $d = DateTime::createFromFormat('Y-m-d', $event_date);
        if (!$d || $d->format('Y-m-d') !== $event_date) {
            $errors[] = 'Enter a valid event date.';
        } else {
            $eventDateObj = $d;
        }
    }

    /* ---------- Start / End time (both required) ---------- */
    if ($start_time === '') {
        $errors[] = 'Start time is required.';
    }
    if ($end_time === '') {
        $errors[] = 'End time is required.';
    }
    if ($start_time !== '' && $end_time !== '') {
        $s = DateTime::createFromFormat('H:i', $start_time) ?: DateTime::createFromFormat('H:i:s', $start_time);
        $e = DateTime::createFromFormat('H:i', $end_time) ?: DateTime::createFromFormat('H:i:s', $end_time);
        if (!$s || !$e) {
            $errors[] = 'Enter valid start and end times.';
        } elseif ($e <= $s) {
            $errors[] = 'End time must be after start time.';
        }
    }

    /* ---------- Registration deadline (required) ---------- */
    if ($registration_deadline === '') {
        $errors[] = 'Registration deadline is required.';
    } else {
        $dl = DateTime::createFromFormat('Y-m-d\TH:i', $registration_deadline)
            ?: DateTime::createFromFormat('Y-m-d H:i', $registration_deadline)
            ?: DateTime::createFromFormat('Y-m-d H:i:s', $registration_deadline);

        if (!$dl) {
            $errors[] = 'Enter a valid registration deadline.';
        } elseif ($eventDateObj !== null) {
            $eventEndOfDay = clone $eventDateObj;
            $eventEndOfDay->setTime(23, 59, 59);
            if ($dl > $eventEndOfDay) {
                $errors[] = 'Registration deadline must be on or before the event date.';
            }
        }
    }

    /* ---------- Venue (required) ---------- */
    if ($venue === '' || mb_strlen($venue) < 3) {
        $errors[] = 'Venue is required (at least 3 characters).';
    } elseif (mb_strlen($venue) > 200) {
        $errors[] = 'Venue cannot exceed 200 characters.';
    }

    /* ---------- Dress code (required) ---------- */
    if ($dress_code === '' || mb_strlen($dress_code) < 2) {
        $errors[] = 'Dress code is required.';
    } elseif (mb_strlen($dress_code) > 255) {
        $errors[] = 'Dress code cannot exceed 255 characters.';
    }

    /* ---------- Status ---------- */
    if (!in_array($status, ['draft', 'published', 'completed', 'cancelled'], true)) {
        $errors[] = 'Select a valid status.';
    }

    return $errors;
}

/* =========================================================
   AJAX: STATUS TOGGLE
   ========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['action'] ?? '') === 'toggle_status') {
    header('Content-Type: application/json');

    $body   = json_decode(file_get_contents('php://input'), true);
    $id     = filter_var($body['id'] ?? null, FILTER_VALIDATE_INT);
    $status = strtolower(trim($body['status'] ?? ''));

    if (!$id || !in_array($status, ['draft', 'published', 'completed', 'cancelled'], true)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Invalid parameters.']);
        exit;
    }

    $check = $pdo->prepare('SELECT event_id FROM events WHERE event_id = :id');
    $check->execute(['id' => $id]);
    if (!$check->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Event not found.']);
        exit;
    }

    $stmt = $pdo->prepare('UPDATE events SET status = :status, updated_at = NOW() WHERE event_id = :id');
    $stmt->execute(['status' => $status, 'id' => $id]);

    echo json_encode(['success' => true]);
    exit;
}

/* =========================================================
   AJAX: UNIQUENESS / EXISTENCE CHECK (college_id)
   all_events.php?action=check_college  (POST, JSON body: { college_id })
   Lets the "Add Event" / "Edit Event" forms confirm a college is valid
   before submit, the same way colleges.php checks slug/email asynchronously.
   ========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['action'] ?? '') === 'check_college') {
    header('Content-Type: application/json');

    $body      = json_decode(file_get_contents('php://input'), true);
    $collegeId = filter_var($body['college_id'] ?? null, FILTER_VALIDATE_INT);

    if (!$collegeId) {
        echo json_encode(['valid' => false, 'message' => 'Select a college.']);
        exit;
    }

    $chk = $pdo->prepare('SELECT college_id, status FROM colleges WHERE college_id = :id');
    $chk->execute(['id' => $collegeId]);
    $row = $chk->fetch();

    if (!$row) {
        echo json_encode(['valid' => false, 'message' => 'Selected college does not exist.']);
        exit;
    }

    echo json_encode([
        'valid'   => true,
        'message' => $row['status'] === 'inactive' ? 'Note: this college is currently inactive.' : null,
    ]);
    exit;
}

/* =========================================================
   DELETE
   ========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['action'] ?? '') === 'delete') {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($id) {
        $check = $pdo->prepare('SELECT event_id FROM events WHERE event_id = :id');
        $check->execute(['id' => $id]);
        
        if ($check->fetch()) {
            $pdo->prepare('DELETE FROM events WHERE event_id = :id')->execute(['id' => $id]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Event deleted successfully.'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Event not found.'];
        }
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Invalid event identity.'];
    }

    header('Location: all_events.php');
    exit;
}

/* =========================================================
   CREATE / UPDATE HANDLERS
   ========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_action'])) {

    if ($_POST['form_action'] === 'create') {
        $errors = validate_event($pdo, $_POST);

        if (empty($errors)) {
            try {
                $eventType = trim($_POST['event_type']);
                // Solo events always store 1/1 regardless of what the (hidden) team fields carried.
                $minTeamSize = $eventType === 'team' ? (int)($_POST['min_team_size'] ?? 1) : 1;
                $maxTeamSize = $eventType === 'team' ? (int)($_POST['max_team_size'] ?? 1) : 1;

                $stmt = $pdo->prepare(
                    'INSERT INTO events (college_id, category_id, title, description, event_type, min_team_size, max_team_size, fee_type, registration_fee, registration_deadline, event_date, start_time, end_time, venue, dress_code, status)
                     VALUES (:college_id, :category_id, :title, :description, :event_type, :min_team_size, :max_team_size, :fee_type, :registration_fee, :registration_deadline, :event_date, :start_time, :end_time, :venue, :dress_code, :status)'
                );
                $stmt->execute([
                    'college_id'            => (int)$_POST['college_id'],
                    'category_id'           => filter_var($_POST['category_id'] ?? '', FILTER_VALIDATE_INT) ?: null,
                    'title'                 => trim($_POST['title']),
                    'description'           => trim($_POST['description'] ?? ''),
                    'event_type'            => $eventType,
                    'min_team_size'         => $minTeamSize,
                    'max_team_size'         => $maxTeamSize,
                    'fee_type'              => trim($_POST['fee_type']),
                    'registration_fee'      => $_POST['registration_fee'] ?? 0.00,
                    'registration_deadline' => !empty($_POST['registration_deadline']) ? $_POST['registration_deadline'] : null,
                    'event_date'            => $_POST['event_date'],
                    'start_time'            => !empty($_POST['start_time']) ? $_POST['start_time'] : null,
                    'end_time'              => !empty($_POST['end_time']) ? $_POST['end_time'] : null,
                    'venue'                 => trim($_POST['venue'] ?? ''),
                    'dress_code'            => trim($_POST['dress_code'] ?? ''),
                    'status'                => trim($_POST['status'] ?? 'draft'),
                ]);

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Event added successfully.'];
            } catch (PDOException $e) {
                // 23000 = integrity constraint violation, e.g. college_id foreign key rejected at the DB level
                $errors[] = $e->getCode() === '23000'
                    ? 'The selected college/category is no longer valid.'
                    : 'Could not save event. Please try again.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => implode(' ', $errors)];
            $_SESSION['reopen_modal'] = 'addEventModal';
        }

        header('Location: all_events.php');
        exit;
    }

    if ($_POST['form_action'] === 'update') {
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);

        if (!$id) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Event not found.'];
            header('Location: all_events.php');
            exit;
        }

        $existsCheck = $pdo->prepare('SELECT event_id FROM events WHERE event_id = :id');
        $existsCheck->execute(['id' => $id]);
        if (!$existsCheck->fetch()) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Event not found.'];
            header('Location: all_events.php');
            exit;
        }

        $errors = validate_event($pdo, $_POST);

        if (empty($errors)) {
            try {
                $eventType = trim($_POST['event_type']);
                $minTeamSize = $eventType === 'team' ? (int)($_POST['min_team_size'] ?? 1) : 1;
                $maxTeamSize = $eventType === 'team' ? (int)($_POST['max_team_size'] ?? 1) : 1;

                $stmt = $pdo->prepare(
                    'UPDATE events 
                     SET college_id = :college_id, category_id = :category_id, title = :title, description = :description, 
                         event_type = :event_type, min_team_size = :min_team_size, max_team_size = :max_team_size, 
                         fee_type = :fee_type, registration_fee = :registration_fee, registration_deadline = :registration_deadline, 
                         event_date = :event_date, start_time = :start_time, end_time = :end_time, venue = :venue, 
                         dress_code = :dress_code, status = :status, updated_at = NOW()
                     WHERE event_id = :id'
                );
                $stmt->execute([
                    'college_id'            => (int)$_POST['college_id'],
                    'category_id'           => filter_var($_POST['category_id'] ?? '', FILTER_VALIDATE_INT) ?: null,
                    'title'                 => trim($_POST['title']),
                    'description'           => trim($_POST['description'] ?? ''),
                    'event_type'            => $eventType,
                    'min_team_size'         => $minTeamSize,
                    'max_team_size'         => $maxTeamSize,
                    'fee_type'              => trim($_POST['fee_type']),
                    'registration_fee'      => $_POST['registration_fee'],
                    'registration_deadline' => !empty($_POST['registration_deadline']) ? $_POST['registration_deadline'] : null,
                    'event_date'            => $_POST['event_date'],
                    'start_time'            => !empty($_POST['start_time']) ? $_POST['start_time'] : null,
                    'end_time'              => !empty($_POST['end_time']) ? $_POST['end_time'] : null,
                    'venue'                 => trim($_POST['venue'] ?? ''),
                    'dress_code'            => trim($_POST['dress_code'] ?? ''),
                    'status'                => trim($_POST['status']),
                    'id'                     => $id,
                ]);

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Event updated successfully.'];
            } catch (PDOException $e) {
                $errors[] = $e->getCode() === '23000'
                    ? 'The selected college/category is no longer valid.'
                    : 'Could not update event.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => implode(' ', $errors)];
            $_SESSION['reopen_modal'] = 'editEventModal' . $id;
        }

        header('Location: all_events.php');
        exit;
    }
}

// Fetch lists for the simple select dropdown boxes
// Colleges are the direct parent of an event, exactly like a University is the
// direct parent of a College in colleges.php.
$colleges   = $pdo->query('SELECT college_id, name, status FROM colleges ORDER BY name ASC')->fetchAll();
$categories = $pdo->query('SELECT category_id, name FROM categories ORDER BY name ASC')->fetchAll();

$collegeLookup  = array_column($colleges, 'name', 'college_id');
$categoryLookup = array_column($categories, 'name', 'category_id');

/* =========================================================
   DATA FOR DISPLAY
   ========================================================= */
$events = $pdo->query('SELECT * FROM events ORDER BY event_date DESC')->fetchAll();
$total     = count($events);
$published = count(array_filter($events, fn($e) => $e['status'] === 'published'));
$drafts    = count(array_filter($events, fn($e) => $e['status'] === 'draft'));

$flash = $_SESSION['flash'] ?? null;
$reopenModal = $_SESSION['reopen_modal'] ?? null;
unset($_SESSION['flash'], $_SESSION['reopen_modal']);

$todayDate = date('Y-m-d');
?>
<!doctype html>
<html lang="en">

<head>
    <title>All Events | Evenza Admin</title>
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
        .stat-card { border: 0; border-radius: 14px; transition: 0.3s ease; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important; }
        .stat-icon { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 22px; }
        .icon-primary { background: #e8edff; color: #4f46e5; }
        .icon-success { background: #e7f8ef; color: #198754; }
        .icon-warning { background: #fff8e6; color: #ffc107; }
        .main-card { border: 0; border-radius: 16px; overflow: hidden; }
        .main-card-header { background: #ffffff; padding: 20px 24px; border-bottom: 1px solid #edf0f5; }
        .search-box { position: relative; }
        .search-box i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; }
        .search-box input { padding-left: 40px; border-radius: 10px; }
        .table thead th { background: #f8f9fc; color: #6b7280; font-size: 13px; font-weight: 600; white-space: nowrap; padding: 15px; }
        .table tbody td { padding: 15px; vertical-align: middle; color: #374151; }
        .table tbody tr { transition: 0.2s ease; }
        .table tbody tr:hover { background-color: #f8faff; }
        .event-name { font-weight: 600; color: #1f2937; }
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: capitalize; }
        .action-btn { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; }
        .async-feedback { min-height: 18px; }
        .async-feedback.text-success { color: #198754 !important; }
        .async-spinner { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); }
        @media (max-width: 768px) {
            .main-card-header { padding: 16px; }
            .table { min-width: 950px; }
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
                    <h4 class="page-title mb-1">All Events</h4>
                    <p class="page-subtitle mb-3">Manage all events across registered colleges</p>
                    <ul class="custom-breadcrumb">
                        <li><a href="Dashboard.php">Home</a></li>
                        <li>Event Management</li>
                        <li>All Events</li>
                    </ul>
                </div>
                <div class="mt-3 mt-md-0">
                    <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#addEventModal">
                        <i class="bi bi-plus-lg me-2"></i> Add Event
                    </button>
                </div>
            </div>

            <!-- Statistics Grid -->
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
                            <div class="stat-icon icon-success me-3"><i class="bi bi-check-circle"></i></div>
                            <div>
                                <small class="text-muted">Published Events</small>
                                <h4 class="mb-0 mt-1" id="publishedCount"><?= $published ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon icon-warning me-3"><i class="bi bi-file-earmark"></i></div>
                            <div>
                                <small class="text-muted">Drafts</small>
                                <h4 class="mb-0 mt-1" id="draftCount"><?= $drafts ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Table Card -->
            <div class="card main-card shadow-sm">
                <div class="main-card-header">
                    <div class="row align-items-center g-3">
                        <div class="col-md-4">
                            <h5 class="mb-1">Event List</h5>
                            <small class="text-muted">View and manage all active system events</small>
                        </div>
                        <div class="col-md-8">
                            <div class="row g-2 justify-content-md-end">
                                <div class="col-md-6">
                                    <div class="search-box">
                                        <i class="bi bi-search"></i>
                                        <input type="text" class="form-control" id="searchEvent" placeholder="Search title, college, venue...">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="statusFilter">
                                        <option value="">All Statuses</option>
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                        <option value="completed">Completed</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="typeFilter">
                                        <option value="">All Types</option>
                                        <option value="solo">Solo</option>
                                        <option value="team">Team</option>
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
                                    <th>Event</th>
                                    <th>College</th>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>Fee</th>
                                    <th>Date</th>
                                    <th>Venue</th>
                                    <th>Status</th>
                                    <th>Change Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($total === 0): ?>
                                    <tr>
                                        <td colspan="11" class="text-center text-muted py-4">
                                            No events found. Click "Add Event" to create one.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($events as $index => $e): ?>
                                        <?php 
                                            $statusClasses = [
                                                'published' => 'bg-success-subtle text-success',
                                                'draft'     => 'bg-warning-subtle text-warning',
                                                'completed' => 'bg-info-subtle text-info',
                                                'cancelled' => 'bg-danger-subtle text-danger'
                                            ];
                                            $badgeClass = $statusClasses[$e['status']] ?? 'bg-secondary-subtle text-secondary';
                                        ?>
                                        <tr data-type="<?= htmlspecialchars($e['event_type']) ?>">
                                            <td><?= $index + 1 ?></td>
                                            <td>
                                                <div class="event-name"><?= htmlspecialchars($e['title']) ?></div>
                                            </td>
                                            <td><?= htmlspecialchars($collegeLookup[$e['college_id']] ?? 'Unknown') ?></td>
                                            <td><?= htmlspecialchars($categoryLookup[$e['category_id']] ?? 'None') ?></td>
                                            <td>
                                                <span class="badge <?= $e['event_type'] === 'solo' ? 'bg-primary' : 'bg-secondary' ?>">
                                                    <?= htmlspecialchars($e['event_type']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <strong>₹<?= number_format($e['registration_fee'], 2) ?></strong>
                                                <small class="text-muted d-block" style="font-size: 11px;">
                                                    <?= $e['fee_type'] === 'per_person' ? 'Per Person' : 'Per Team' ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div><?= date('d M Y', strtotime($e['event_date'])) ?></div>
                                                <?php if(!empty($e['start_time'])): ?>
                                                    <small class="text-muted"><?= date('h:i A', strtotime($e['start_time'])) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($e['venue'] ?: 'N/A') ?></td>
                                            <td>
                                                <span class="badge <?= $badgeClass ?> status-badge">
                                                    <?= htmlspecialchars($e['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <select class="form-select form-select-sm status-dropdown" data-id="<?= (int)$e['event_id'] ?>" style="width: 110px; font-size: 12px;">
                                                    <option value="draft" <?= $e['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                                                    <option value="published" <?= $e['status'] === 'published' ? 'selected' : '' ?>>Publish</option>
                                                    <option value="completed" <?= $e['status'] === 'completed' ? 'selected' : '' ?>>Complete</option>
                                                    <option value="cancelled" <?= $e['status'] === 'cancelled' ? 'selected' : '' ?>>Cancel</option>
                                                </select>
                                            </td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-light action-btn me-1" title="Edit"
                                                    data-bs-toggle="modal" data-bs-target="#editEventModal<?= (int)$e['event_id'] ?>">
                                                    <i class="bi bi-pencil text-primary"></i>
                                                </button>
                                                <button type="button" class="btn btn-light action-btn" title="Delete" onclick="deleteEvent(<?= (int)$e['event_id'] ?>)">
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
                <div class="card-footer bg-white border-top">
                    <small class="text-muted">Showing <?= $total ?> events</small>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== MODAL: ADD EVENT ===================== -->
    <div class="modal fade" id="addEventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" class="needs-validation event-form" novalidate>
                    <input type="hidden" name="form_action" value="create">

                    <div class="modal-header">
                        <h5 class="modal-title">Add New Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">College *</label>
                                <div class="position-relative">
                                    <select class="form-select" name="college_id" required data-async-check="college"
                                        aria-describedby="collegeAsyncFeedbackAdd">
                                        <option value="">Choose College</option>
                                        <?php foreach ($colleges as $c): ?>
                                            <option value="<?= (int)$c['college_id'] ?>"><?= htmlspecialchars($c['name']) ?><?= $c['status'] === 'inactive' ? ' (Inactive)' : '' ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="async-spinner spinner-border spinner-border-sm text-secondary d-none" role="status" aria-hidden="true"></span>
                                </div>
                                <div class="invalid-feedback">Please select a college.</div>
                                <div class="async-feedback small mt-1" id="collegeAsyncFeedbackAdd" role="alert" aria-live="polite"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Category *</label>
                                <select class="form-select" name="category_id" required>
                                    <option value="">Choose Category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= (int)$cat['category_id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Please select a category.</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Title *</label>
                                <input type="text" class="form-control" name="title" required minlength="2" maxlength="150" placeholder="Event Name">
                                <div class="invalid-feedback">Enter a valid title (2-150 characters).</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Event Type *</label>
                                <select class="form-select type-select" name="event_type" required>
                                    <option value="solo" selected>Solo</option>
                                    <option value="team">Team</option>
                                </select>
                            </div>

                            <div class="col-md-3 team-fields d-none">
                                <label class="form-label">Min Team Size</label>
                                <input type="number" class="form-control" name="min_team_size" value="1" min="1" max="100">
                                <div class="invalid-feedback">Min team size must be at least 1.</div>
                            </div>

                            <div class="col-md-3 team-fields d-none">
                                <label class="form-label">Max Team Size</label>
                                <input type="number" class="form-control" name="max_team_size" value="1" min="1" max="100">
                                <div class="invalid-feedback">Max team size cannot be less than min.</div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Fee Type *</label>
                                <select class="form-select" name="fee_type" required>
                                    <option value="per_person" selected>Per Person</option>
                                    <option value="per_team">Per Team</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Registration Fee *</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" class="form-control" name="registration_fee" step="0.01" min="0" max="1000000" value="0.00" required>
                                </div>
                                <div class="invalid-feedback">Enter a fee between 0 and 10,00,000.</div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Status *</label>
                                <select class="form-select" name="status" required>
                                    <option value="draft" selected>Draft</option>
                                    <option value="published">Published</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Date *</label>
                                <input type="date" class="form-control event-date-input" name="event_date" required min="<?= htmlspecialchars($todayDate) ?>">
                                <div class="invalid-feedback">Please select a valid date.</div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Start Time *</label>
                                <input type="time" class="form-control start-time-input" name="start_time" required>
                                <div class="invalid-feedback">Start time is required.</div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">End Time *</label>
                                <input type="time" class="form-control end-time-input" name="end_time" required>
                                <div class="invalid-feedback">End time must be after start time.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Registration Deadline *</label>
                                <input type="datetime-local" class="form-control deadline-input" name="registration_deadline" required>
                                <div class="invalid-feedback">Deadline must be on or before the event date.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Venue *</label>
                                <input type="text" class="form-control" name="venue" required minlength="3" maxlength="200" placeholder="Room, Auditorium, Ground...">
                                <div class="invalid-feedback">Venue is required (min 3 characters).</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Dress Code *</label>
                                <input type="text" class="form-control" name="dress_code" required minlength="2" maxlength="255" placeholder="e.g., Casual, Formals, Traditional">
                                <div class="invalid-feedback">Dress code is required.</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description *</label>
                                <textarea class="form-control" name="description" required minlength="10" maxlength="2000" style="height: 90px;" placeholder="Write structural description or event details..."></textarea>
                                <div class="invalid-feedback">Description is required (min 10 characters).</div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ===================== MODAL: EDIT EVENT ===================== -->
    <?php foreach ($events as $e): ?>
        <div class="modal fade" id="editEventModal<?= (int)$e['event_id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" class="needs-validation event-form" novalidate>
                        <input type="hidden" name="form_action" value="update">
                        <input type="hidden" name="id" value="<?= (int)$e['event_id'] ?>">

                        <div class="modal-header">
                            <h5 class="modal-title">Edit Event</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">College *</label>
                                    <div class="position-relative">
                                        <select class="form-select" name="college_id" required data-async-check="college"
                                            aria-describedby="collegeAsyncFeedback<?= (int)$e['event_id'] ?>">
                                            <?php foreach ($colleges as $c): ?>
                                                <option value="<?= (int)$c['college_id'] ?>" <?= $c['college_id'] == $e['college_id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($c['name']) ?><?= $c['status'] === 'inactive' ? ' (Inactive)' : '' ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="async-spinner spinner-border spinner-border-sm text-secondary d-none" role="status" aria-hidden="true"></span>
                                    </div>
                                    <div class="invalid-feedback">Please select a college.</div>
                                    <div class="async-feedback small mt-1" id="collegeAsyncFeedback<?= (int)$e['event_id'] ?>" role="alert" aria-live="polite"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Category *</label>
                                    <select class="form-select" name="category_id" required>
                                        <option value="">Choose Category</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= (int)$cat['category_id'] ?>" <?= $cat['category_id'] == $e['category_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cat['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Please select a category.</div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Title *</label>
                                    <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($e['title']) ?>" required minlength="2" maxlength="150">
                                    <div class="invalid-feedback">Enter a valid title (2-150 characters).</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Event Type *</label>
                                    <select class="form-select edit-type-select" name="event_type" required>
                                        <option value="solo" <?= $e['event_type'] === 'solo' ? 'selected' : '' ?>>Solo</option>
                                        <option value="team" <?= $e['event_type'] === 'team' ? 'selected' : '' ?>>Team</option>
                                    </select>
                                </div>

                                <div class="col-md-3 edit-team-fields <?= $e['event_type'] === 'solo' ? 'd-none' : '' ?>">
                                    <label class="form-label">Min Team Size</label>
                                    <input type="number" class="form-control" name="min_team_size" value="<?= (int)$e['min_team_size'] ?>" min="1" max="100">
                                    <div class="invalid-feedback">Min team size must be at least 1.</div>
                                </div>

                                <div class="col-md-3 edit-team-fields <?= $e['event_type'] === 'solo' ? 'd-none' : '' ?>">
                                    <label class="form-label">Max Team Size</label>
                                    <input type="number" class="form-control" name="max_team_size" value="<?= (int)$e['max_team_size'] ?>" min="1" max="100">
                                    <div class="invalid-feedback">Max team size cannot be less than min.</div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Fee Type *</label>
                                    <select class="form-select" name="fee_type" required>
                                        <option value="per_person" <?= $e['fee_type'] === 'per_person' ? 'selected' : '' ?>>Per Person</option>
                                        <option value="per_team" <?= $e['fee_type'] === 'per_team' ? 'selected' : '' ?>>Per Team</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Registration Fee *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" class="form-control" name="registration_fee" step="0.01" min="0" max="1000000" value="<?= htmlspecialchars($e['registration_fee']) ?>" required>
                                    </div>
                                    <div class="invalid-feedback">Enter a fee between 0 and 10,00,000.</div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Status *</label>
                                    <select class="form-select" name="status" required>
                                        <option value="draft" <?= $e['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                                        <option value="published" <?= $e['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                                        <option value="completed" <?= $e['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                        <option value="cancelled" <?= $e['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Date *</label>
                                    <input type="date" class="form-control event-date-input" name="event_date" value="<?= htmlspecialchars($e['event_date']) ?>" required>
                                    <div class="invalid-feedback">Please select a valid date.</div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Start Time *</label>
                                    <input type="time" class="form-control start-time-input" name="start_time" value="<?= htmlspecialchars($e['start_time']) ?>" required>
                                    <div class="invalid-feedback">Start time is required.</div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">End Time *</label>
                                    <input type="time" class="form-control end-time-input" name="end_time" value="<?= htmlspecialchars($e['end_time']) ?>" required>
                                    <div class="invalid-feedback">End time must be after start time.</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Registration Deadline *</label>
                                    <input type="datetime-local" class="form-control deadline-input" name="registration_deadline" value="<?= !empty($e['registration_deadline']) ? date('Y-m-d\TH:i', strtotime($e['registration_deadline'])) : '' ?>" required>
                                    <div class="invalid-feedback">Deadline must be on or before the event date.</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Venue *</label>
                                    <input type="text" class="form-control" name="venue" value="<?= htmlspecialchars($e['venue']) ?>" required minlength="3" maxlength="200">
                                    <div class="invalid-feedback">Venue is required (min 3 characters).</div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Dress Code *</label>
                                    <input type="text" class="form-control" name="dress_code" value="<?= htmlspecialchars($e['dress_code']) ?>" required minlength="2" maxlength="255">
                                    <div class="invalid-feedback">Dress code is required.</div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Description *</label>
                                    <textarea class="form-control" name="description" required minlength="10" maxlength="2000" style="height: 90px;"><?= htmlspecialchars($e['description']) ?></textarea>
                                    <div class="invalid-feedback">Description is required (min 10 characters).</div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Event</button>
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
        // =====================================================================
        // FIELD-LEVEL VALIDATION ENGINE
        //
        // Bootstrap's default ".was-validated :valid" approach marks EVERY
        // field satisfying its constraints as "valid" (green) - including
        // optional fields that are simply empty. That makes an untouched form
        // look like Venue/Dress Code/Description/Start Time/etc. were filled
        // in correctly when they weren't. This engine replaces that with
        // explicit per-field classes so a field only ever shows:
        //   - red (is-invalid)  -> required-and-empty, OR has a value that
        //                          fails a rule
        //   - green (is-valid)  -> has a value AND that value passes all rules
        //   - neutral (nothing) -> optional and still empty
        // =====================================================================
        function isFieldEmpty(field) {
            return field.value === null || field.value.trim() === '';
        }

        // Bootstrap only auto-shows a .invalid-feedback block when it's an
        // IMMEDIATE sibling of the .is-invalid element. Several of our fields
        // (college select, fee input, deadline input) wrap the control in a
        // .position-relative/.input-group div for a spinner/prefix, which
        // breaks that sibling relationship. So we show/hide the message
        // ourselves instead of relying on Bootstrap's CSS alone.
        function findFeedbackEl(field) {
            const group = field.closest('[class*="col-"]');
            return group ? group.querySelector('.invalid-feedback') : null;
        }

        function validateField(field) {
            if (!field || field.type === 'hidden' || field.type === 'button' || field.type === 'submit') return true;

            const empty = isFieldEmpty(field);
            const feedbackEl = findFeedbackEl(field);

            // Optional + still empty => neutral, no red/green, no message.
            if (!field.required && empty) {
                field.classList.remove('is-valid', 'is-invalid');
                if (feedbackEl) feedbackEl.style.display = 'none';
                return true;
            }

            const valid = field.checkValidity();
            field.classList.toggle('is-valid', valid);
            field.classList.toggle('is-invalid', !valid);
            if (feedbackEl) feedbackEl.style.display = valid ? 'none' : 'block';
            return valid;
        }

        function validateForm(form) {
            const fields = form.querySelectorAll('input, select, textarea');
            let formValid = true;
            fields.forEach(field => {
                if (field.type === 'hidden') return;
                if (!validateField(field)) formValid = false;
            });
            return formValid;
        }

        document.querySelectorAll('.event-form').forEach(form => {
            const fields = form.querySelectorAll('input, select, textarea');

            // Live per-field feedback as the user actually interacts with it -
            // nothing is marked valid/invalid until it has been touched.
            fields.forEach(field => {
                if (field.type === 'hidden') return;
                ['input', 'change', 'blur'].forEach(evt => {
                    field.addEventListener(evt, () => validateField(field));
                });
            });

            form.addEventListener('submit', function(e) {
                if (!validateForm(form)) {
                    e.preventDefault();
                    e.stopPropagation();
                    // Bring the first invalid field into view for the user.
                    const firstInvalid = form.querySelector('.is-invalid');
                    if (firstInvalid) firstInvalid.focus({ preventScroll: false });
                }
            });
        });

        // ===================== Team size fields: show/require only for "team" events =====================
        function syncTeamFieldRequirement(typeSelect, fieldsSelector) {
            const isTeam = typeSelect.value === 'team';
            const container = typeSelect.closest('.row');
            const fields = container.querySelectorAll(fieldsSelector);
            fields.forEach(field => {
                field.classList.toggle('d-none', !isTeam);
                const input = field.querySelector('input');
                if (input) {
                    input.required = isTeam;
                    if (!isTeam) {
                        input.setCustomValidity('');
                        input.classList.remove('is-valid', 'is-invalid');
                    } else {
                        validateField(input);
                    }
                }
            });
        }

        // Add Modal: Toggle Min/Max Team boxes depending on selection
        const typeSelect = document.querySelector('.type-select');
        if (typeSelect) {
            syncTeamFieldRequirement(typeSelect, '.team-fields');
            typeSelect.addEventListener('change', function() {
                syncTeamFieldRequirement(this, '.team-fields');
            });
        }

        // Edit Modal: Toggle Min/Max Team boxes depending on selection
        document.querySelectorAll('.edit-type-select').forEach(sel => {
            syncTeamFieldRequirement(sel, '.edit-team-fields');
            sel.addEventListener('change', function() {
                syncTeamFieldRequirement(this, '.edit-team-fields');
            });
        });

        // ===================== Cross-field validation: min/max team size, start/end time, deadline vs event date =====================
        document.querySelectorAll('.event-form').forEach(form => {
            const minInput   = form.querySelector('input[name="min_team_size"]');
            const maxInput   = form.querySelector('input[name="max_team_size"]');
            const startInput = form.querySelector('.start-time-input');
            const endInput   = form.querySelector('.end-time-input');
            const dateInput  = form.querySelector('.event-date-input');
            const deadlineInput = form.querySelector('.deadline-input');

            function checkTeamSizes() {
                if (!minInput || !maxInput) return;
                if (minInput.value && maxInput.value && parseInt(maxInput.value, 10) < parseInt(minInput.value, 10)) {
                    maxInput.setCustomValidity('Maximum team size cannot be less than minimum team size.');
                } else {
                    maxInput.setCustomValidity('');
                }
                validateField(minInput);
                validateField(maxInput);
            }

            function checkTimes() {
                if (!startInput || !endInput) return;
                if (startInput.value && endInput.value && endInput.value <= startInput.value) {
                    endInput.setCustomValidity('End time must be after start time.');
                } else {
                    endInput.setCustomValidity('');
                }
                validateField(startInput);
                validateField(endInput);
            }

            function checkDeadline() {
                if (!deadlineInput || !dateInput) return;
                if (deadlineInput.value && dateInput.value) {
                    const deadlineDate = deadlineInput.value.split('T')[0];
                    if (deadlineDate > dateInput.value) {
                        deadlineInput.setCustomValidity('Registration deadline must be on or before the event date.');
                    } else {
                        deadlineInput.setCustomValidity('');
                    }
                } else {
                    deadlineInput.setCustomValidity('');
                }
                validateField(deadlineInput);
                validateField(dateInput);
            }

            [minInput, maxInput].forEach(el => el && el.addEventListener('input', checkTeamSizes));
            [startInput, endInput].forEach(el => el && el.addEventListener('input', checkTimes));
            [deadlineInput, dateInput].forEach(el => el && el.addEventListener('input', checkDeadline));
        });

        <?php if ($reopenModal): ?>
            document.addEventListener("DOMContentLoaded", function() {
                const modalEl = document.getElementById(<?= json_encode($reopenModal) ?>);
                if (modalEl) new bootstrap.Modal(modalEl).show();
            });
        <?php endif; ?>

        // Search & Multi-Filter Logic Engine
        const searchInput = document.getElementById("searchEvent");
        const statusFilter = document.getElementById("statusFilter");
        const typeFilter = document.getElementById("typeFilter");
        const rows = document.querySelectorAll("#eventTable tbody tr");

        function filterEvents() {
            const searchVal = searchInput.value.toLowerCase();
            const statusVal = statusFilter.value.toLowerCase();
            const typeVal = typeFilter.value.toLowerCase();

            rows.forEach(row => {
                if (!row.querySelector(".status-badge")) return;

                const fullText = row.innerText.toLowerCase();
                const currentStatus = row.querySelector(".status-badge").innerText.toLowerCase().trim();
                const currentType = row.dataset.type.toLowerCase().trim();

                const matchesSearch = fullText.includes(searchVal);
                const matchesStatus = statusVal === "" || currentStatus === statusVal;
                const matchesType = typeVal === "" || currentType === typeVal;

                row.style.display = (matchesSearch && matchesStatus && matchesType) ? "" : "none";
            });
        }

        searchInput.addEventListener("keyup", filterEvents);
        statusFilter.addEventListener("change", filterEvents);
        typeFilter.addEventListener("change", filterEvents);

        // Live AJAX Status Changes & Metrics Updates
        const publishedCountBox = document.getElementById("publishedCount");
        const draftCountBox = document.getElementById("draftCount");

        function adjustCounts(oldStatus, newStatus) {
            let pubVal = parseInt(publishedCountBox.textContent, 10) || 0;
            let drfVal = parseInt(draftCountBox.textContent, 10) || 0;

            if (oldStatus === 'published' && pubVal > 0) publishedCountBox.textContent = pubVal - 1;
            if (oldStatus === 'draft' && drfVal > 0) draftCountBox.textContent = drfVal - 1;

            if (newStatus === 'published') publishedCountBox.textContent = pubVal + 1;
            if (newStatus === 'draft') draftCountBox.textContent = drfVal + 1;
        }

        function updateBadgeColor(row, status) {
            const badge = row.querySelector(".status-badge");
            badge.textContent = status;
            badge.className = "badge status-badge";
            
            const classes = {
                'published': ["bg-success-subtle", "text-success"],
                'draft': ["bg-warning-subtle", "text-warning"],
                'completed': ["bg-info-subtle", "text-info"],
                'cancelled': ["bg-danger-subtle", "text-danger"]
            };

            const applyStyles = classes[status] || ["bg-secondary-subtle", "text-secondary"];
            badge.classList.add(...applyStyles);
        }

        document.querySelectorAll(".status-dropdown").forEach(dropdown => {
            let previousValue = dropdown.value;

            dropdown.addEventListener("change", function() {
                const eventId = this.dataset.id;
                const newValue = this.value;
                const row = this.closest("tr");

                this.disabled = true;

                fetch("all_events.php?action=toggle_status", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ id: eventId, status: newValue })
                })
                .then(res => {
                    if (!res.ok) throw new Error("Network issue.");
                    return res.json();
                })
                .then(data => {
                    if (data && data.success) {
                        updateBadgeColor(row, newValue);
                        adjustCounts(previousValue, newValue);
                        previousValue = newValue;
                        filterEvents();
                    } else {
                        throw new Error("Rejected by server.");
                    }
                })
                .catch(() => {
                    this.value = previousValue;
                    alert("Could not update status. Please try again.");
                })
                .finally(() => {
                    this.disabled = false;
                });
            });
        });

        function deleteEvent(id) {
            if (confirm("Are you sure you want to delete this event permanently?")) {
                window.location.href = "all_events.php?action=delete&id=" + id;
            }
        }

        // ===================== ASYNC COLLEGE EXISTENCE CHECK =====================
        // Mirrors the async slug/email checks in colleges.php: confirms the picked
        // college is still a real, valid record before the user submits.
        (function() {
            const DEBOUNCE_MS = 350;
            const timers = new WeakMap();

            function debounce(el, fn) {
                clearTimeout(timers.get(el));
                timers.set(el, setTimeout(fn, DEBOUNCE_MS));
            }

            function setState(select, feedbackEl, spinner, state, message) {
                spinner.classList.toggle("d-none", state !== "checking");

                if (state === "valid") {
                    select.setCustomValidity("");
                    feedbackEl.textContent = message || "College confirmed.";
                    feedbackEl.classList.remove("text-danger");
                    feedbackEl.classList.add(message ? "text-warning" : "text-success");
                } else if (state === "invalid") {
                    select.setCustomValidity(message || "Invalid college.");
                    feedbackEl.textContent = message || "Invalid college.";
                    feedbackEl.classList.remove("text-success", "text-warning");
                    feedbackEl.classList.add("text-danger");
                } else if (state === "checking") {
                    // Mid-flight: no red/green yet, just the spinner + message.
                    select.classList.remove('is-valid', 'is-invalid');
                    feedbackEl.textContent = "Checking college…";
                    feedbackEl.classList.remove("text-success", "text-danger", "text-warning");
                    return;
                } else {
                    select.setCustomValidity("");
                    feedbackEl.textContent = "";
                    feedbackEl.classList.remove("text-success", "text-danger", "text-warning");
                }

                // Keep the select's red/green border in sync with the async result
                // (the generic validateField() only knows about sync HTML5 constraints).
                if (typeof validateField === 'function') validateField(select);
            }

            async function runCheck(select) {
                const feedbackId = select.getAttribute("aria-describedby");
                const feedbackEl = feedbackId ? document.getElementById(feedbackId) : null;
                const spinner = select.parentElement.querySelector(".async-spinner");
                if (!feedbackEl || !spinner) return;

                const value = select.value;
                if (!value) {
                    setState(select, feedbackEl, spinner, "idle");
                    return;
                }

                setState(select, feedbackEl, spinner, "checking");

                try {
                    const res = await fetch("all_events.php?action=check_college", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ college_id: value })
                    });

                    if (!res.ok) throw new Error("Request failed");
                    const data = await res.json();

                    setState(select, feedbackEl, spinner, data.valid ? "valid" : "invalid", data.message);
                } catch {
                    // Network failure: don't hard-block client-side; the final
                    // server-side validate_event() check on submit is authoritative.
                    setState(select, feedbackEl, spinner, "idle");
                }
            }

            document.addEventListener("change", function(e) {
                if (!e.target.matches('[data-async-check="college"]')) return;
                debounce(e.target, () => runCheck(e.target));
            });
        })();
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
