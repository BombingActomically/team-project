<?php

/**
 * colleges.php
 * Single-file College management: DB connection, validation,
 * create / read / update / delete, status toggle (AJAX),
 * async uniqueness checks (AJAX), and UI.
 *
 * Assumes a `colleges` table:
 *   college_id, university_id, name, slug, email, phone, address,
 *   logo, status, created_at, updated_at
 * with university_id as a foreign key to universities.university_id.
 * Adjust column names below if yours differ.
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

$LOGO_DIR = __DIR__ . '/assets/images/colleges';
$LOGO_WEB_PATH = 'assets/images/colleges/';

/* =========================================================
   VALIDATION HELPERS
   ========================================================= */
function validate_college(
    PDO $pdo,
    array $data,
    ?int $excludeId = null,
    ?array $logoFile = null,
    ?string $existingLogo = null
): array {
    $errors = [];

    $name         = trim($data['name'] ?? '');
    $slug         = trim($data['slug'] ?? '');
    $universityId = filter_var($data['university_id'] ?? null, FILTER_VALIDATE_INT);
    $email        = trim($data['email'] ?? '');
    $phone        = trim($data['phone'] ?? '');
    $address      = trim($data['address'] ?? '');
    $status       = trim($data['status'] ?? '');
    $password     = (string) ($data['password'] ?? '');

    if ($name === '' || mb_strlen($name) < 2) {
        $errors[] = 'College name must be at least 2 characters.';
    } elseif (mb_strlen($name) > 150) {
        $errors[] = 'College name is too long (max 150 characters).';
    }

    if ($slug === '') {
        $errors[] = 'Slug / short name is required.';
    } elseif (!preg_match('/^[A-Za-z0-9\-]{1,150}$/', $slug)) {
        $errors[] = 'Slug may only contain letters, numbers, and hyphens (max 150 chars).';
    }

    if (!$universityId) {
        $errors[] = 'Select a valid university.';
    } else {
        $chk = $pdo->prepare('SELECT university_id FROM universities WHERE university_id = :id');
        $chk->execute(['id' => $universityId]);
        if (!$chk->fetch()) {
            $errors[] = 'Selected university does not exist.';
        }
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 100) {
        $errors[] = 'Enter a valid email address (max 100 characters).';
    }

    if ($phone === '') {
        $errors[] = 'Phone number is required.';
    } elseif (!preg_match('/^[0-9+\-\s]{7,20}$/', $phone)) {
        $errors[] = 'Enter a valid phone number.';
    }

    if ($address === '' || mb_strlen($address) < 5) {
        $errors[] = 'Address is required (min 5 characters).';
    }

    // Logo is mandatory: either a new file must be uploaded, or (on edit) one must already exist
    if (!logo_file_provided($logoFile) && empty($existingLogo)) {
        $errors[] = 'College logo is required.';
    }

    if (!in_array($status, ['active', 'inactive'], true)) {
        $errors[] = 'Select a valid status.';
    }

    // Password is required when creating a new college login; optional on edit (blank = keep current)
    if ($excludeId === null) {
        if (mb_strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }
    } elseif ($password !== '' && mb_strlen($password) < 8) {
        $errors[] = 'New password must be at least 8 characters (leave blank to keep the current password).';
    }

    if ($slug !== '' && preg_match('/^[A-Za-z0-9\-]{1,150}$/', $slug)) {
        $sql = 'SELECT college_id FROM colleges WHERE slug = :slug';
        $params = ['slug' => $slug];
        if ($excludeId !== null) {
            $sql .= ' AND college_id != :id';
            $params['id'] = $excludeId;
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        if ($stmt->fetch()) {
            $errors[] = 'This slug is already in use.';
        }
    }

    if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $sql = 'SELECT college_id FROM colleges WHERE email = :email';
        $params = ['email' => $email];
        if ($excludeId !== null) {
            $sql .= ' AND college_id != :id';
            $params['id'] = $excludeId;
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        if ($stmt->fetch()) {
            $errors[] = 'This email is already registered.';
        }
    }

    return $errors;
}

function logo_file_provided(?array $file): bool
{
    return $file !== null && isset($file['error']) && $file['error'] !== UPLOAD_ERR_NO_FILE;
}

function handle_logo_upload(?array $file, string $destDir): ?string
{
    if (!$file || !isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Logo upload failed. Please try again.');
    }

    $allowedTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!isset($allowedTypes[$mime])) {
        throw new RuntimeException('Only JPG or PNG images are allowed.');
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        throw new RuntimeException('Logo must be smaller than 2MB.');
    }

    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    $filename = 'c_' . bin2hex(random_bytes(8)) . '.' . $allowedTypes[$mime];

    if (!move_uploaded_file($file['tmp_name'], $destDir . '/' . $filename)) {
        throw new RuntimeException('Could not save uploaded logo.');
    }

    return $filename;
}

/* =========================================================
   AJAX: STATUS TOGGLE
   colleges.php?action=toggle_status  (POST, JSON body)
   ========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['action'] ?? '') === 'toggle_status') {
    header('Content-Type: application/json');

    $body   = json_decode(file_get_contents('php://input'), true);
    $id     = filter_var($body['id'] ?? null, FILTER_VALIDATE_INT);
    $status = strtolower(trim($body['status'] ?? ''));

    if (!$id || !in_array($status, ['active', 'inactive'], true)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        exit;
    }

    $check = $pdo->prepare('SELECT college_id FROM colleges WHERE college_id = :id');
    $check->execute(['id' => $id]);
    if (!$check->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'College not found.']);
        exit;
    }

    $stmt = $pdo->prepare('UPDATE colleges SET status = :status, updated_at = NOW() WHERE college_id = :id');
    $stmt->execute(['status' => $status, 'id' => $id]);

    echo json_encode(['success' => true]);
    exit;
}

/* =========================================================
   AJAX: UNIQUENESS CHECK (slug / email)
   colleges.php?action=check_unique  (POST, JSON body)
   Body: { field: "slug"|"email", value: "...", id: <int|null> }
   `id` should be the current college_id when editing, so a
   college doesn't get flagged as a duplicate of itself.
   ========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['action'] ?? '') === 'check_unique') {
    header('Content-Type: application/json');

    $body      = json_decode(file_get_contents('php://input'), true);
    $field     = $body['field'] ?? '';
    $value     = trim($body['value'] ?? '');
    $excludeId = filter_var($body['id'] ?? null, FILTER_VALIDATE_INT) ?: null;

    // Only these two columns are ever checked this way — never trust $field for raw SQL beyond this whitelist.
    if (!in_array($field, ['slug', 'email'], true) || $value === '') {
        http_response_code(422);
        echo json_encode(['available' => false, 'message' => 'Invalid request.']);
        exit;
    }

    if ($field === 'slug' && !preg_match('/^[A-Za-z0-9\-]{1,150}$/', $value)) {
        echo json_encode(['available' => false, 'message' => 'Invalid slug format.']);
        exit;
    }

    if ($field === 'email' && (!filter_var($value, FILTER_VALIDATE_EMAIL) || mb_strlen($value) > 100)) {
        echo json_encode(['available' => false, 'message' => 'Invalid email format.']);
        exit;
    }

    $sql = "SELECT college_id FROM colleges WHERE {$field} = :value";
    $params = ['value' => $value];
    if ($excludeId !== null) {
        $sql .= ' AND college_id != :id';
        $params['id'] = $excludeId;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $exists = (bool) $stmt->fetch();

    echo json_encode([
        'available' => !$exists,
        'message'   => $exists
            ? ($field === 'slug' ? 'This slug is already in use.' : 'This email is already registered.')
            : null,
    ]);
    exit;
}

/* =========================================================
   DELETE
   colleges.php?action=delete&id=3  (GET)
   ========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['action'] ?? '') === 'delete') {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($id) {
        $stmt = $pdo->prepare('SELECT logo FROM colleges WHERE college_id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if ($row) {
            $pdo->prepare('DELETE FROM colleges WHERE college_id = :id')->execute(['id' => $id]);

            if (!empty($row['logo'])) {
                $path = $LOGO_DIR . '/' . $row['logo'];
                if (is_file($path)) {
                    unlink($path);
                }
            }

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'College deleted successfully.'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'College not found.'];
        }
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Invalid college id.'];
    }

    header('Location: colleges.php');
    exit;
}

/* =========================================================
   CREATE / UPDATE
   Normal (non-AJAX) form POST, using Post/Redirect/Get.
   ========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_action'])) {

    if ($_POST['form_action'] === 'create') {

        $errors = validate_college($pdo, $_POST, null, $_FILES['logo'] ?? null, null);

        if (empty($errors)) {
            try {
                $logo = handle_logo_upload($_FILES['logo'] ?? null, $LOGO_DIR);

                $stmt = $pdo->prepare(
                    'INSERT INTO colleges (university_id, name, slug, email, password, phone, address, logo, status, created_at, updated_at)
                     VALUES (:university_id, :name, :slug, :email, :password, :phone, :address, :logo, :status, NOW(), NOW())'
                );
                $stmt->execute([
                    'university_id' => (int) $_POST['university_id'],
                    'name'          => trim($_POST['name']),
                    'slug'          => trim($_POST['slug']),
                    'email'         => trim($_POST['email']),
                    'password'      => password_hash($_POST['password'], PASSWORD_DEFAULT),
                    'phone'         => trim($_POST['phone']),
                    'address'       => trim($_POST['address']),
                    'logo'          => $logo,
                    'status'        => trim($_POST['status']),
                ]);

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'College added successfully.'];
            } catch (RuntimeException $e) {
                $errors[] = $e->getMessage();
            } catch (PDOException $e) {
                // 23000 = integrity constraint violation (e.g. duplicate email), which the
                // DB itself enforces via a UNIQUE index even if our app-level check missed it
                $errors[] = $e->getCode() === '23000'
                    ? 'This email is already registered.'
                    : 'Could not save college. Please try again.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => implode(' ', $errors)];
            $_SESSION['reopen_modal'] = 'addCollegeModal';
        }

        header('Location: colleges.php');
        exit;
    }

    if ($_POST['form_action'] === 'update') {

        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        $existingStmt = $pdo->prepare('SELECT * FROM colleges WHERE college_id = :id');
        $existingStmt->execute(['id' => $id]);
        $existing = $existingStmt->fetch();

        if (!$id || !$existing) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'College not found.'];
            header('Location: colleges.php');
            exit;
        }

        $errors = validate_college($pdo, $_POST, $id, $_FILES['logo'] ?? null, $existing['logo'] ?? null);

        if (empty($errors)) {
            try {
                $newLogo = handle_logo_upload($_FILES['logo'] ?? null, $LOGO_DIR);
                $logoToStore = $existing['logo'];

                if ($newLogo !== null) {
                    if (!empty($existing['logo'])) {
                        $oldPath = $LOGO_DIR . '/' . $existing['logo'];
                        if (is_file($oldPath)) {
                            unlink($oldPath);
                        }
                    }
                    $logoToStore = $newLogo;
                }

                $stmt = $pdo->prepare(
                    'UPDATE colleges
                     SET university_id = :university_id, name = :name, slug = :slug, email = :email,
                         phone = :phone, address = :address, logo = :logo,
                         status = :status, updated_at = NOW()
                         ' . (trim($_POST['password'] ?? '') !== '' ? ', password = :password' : '') . '
                     WHERE college_id = :id'
                );

                $params = [
                    'university_id' => (int) $_POST['university_id'],
                    'name'          => trim($_POST['name']),
                    'slug'          => trim($_POST['slug']),
                    'email'         => trim($_POST['email']),
                    'phone'         => trim($_POST['phone']),
                    'address'       => trim($_POST['address']),
                    'logo'          => $logoToStore,
                    'status'        => trim($_POST['status']),
                    'id'            => $id,
                ];

                if (trim($_POST['password'] ?? '') !== '') {
                    $params['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                }

                $stmt->execute($params);

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'College updated successfully.'];
            } catch (RuntimeException $e) {
                $errors[] = $e->getMessage();
            } catch (PDOException $e) {
                $errors[] = $e->getCode() === '23000'
                    ? 'This email is already registered.'
                    : 'Could not update college. Please try again.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => implode(' ', $errors)];
            $_SESSION['reopen_modal'] = 'editCollegeModal' . $id;
        }

        header('Location: colleges.php');
        exit;
    }
}

/* =========================================================
   DATA FOR DISPLAY
   ========================================================= */
$colleges = $pdo->query(
    'SELECT c.*, u.name AS university_name
     FROM colleges c
     LEFT JOIN universities u ON u.university_id = c.university_id
     ORDER BY c.created_at DESC'
)->fetchAll();

$universities = $pdo->query('SELECT university_id, name FROM universities ORDER BY name ASC')->fetchAll();

$total    = count($colleges);
$active   = count(array_filter($colleges, fn($c) => $c['status'] === 'active'));
$inactive = $total - $active;

$flash = $_SESSION['flash'] ?? null;
$reopenModal = $_SESSION['reopen_modal'] ?? null;
unset($_SESSION['flash'], $_SESSION['reopen_modal']);
?>
<!doctype html>
<html lang="en">

<head>

    <title>All Colleges | Evenza Admin</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        body {
            background-color: #f5f7fb;
        }

        .page-title {
            font-weight: 600;
            color: #1f2937;
        }

        .page-subtitle {
            color: #6b7280;
            font-size: 14px;
        }

        .custom-breadcrumb {
            display: flex;
            align-items: center;
            gap: 12px;
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 14px;
        }

        .custom-breadcrumb li {
            color: #6b7280;
        }

        .custom-breadcrumb li a {
            text-decoration: none;
            color: #4f46e5;
        }

        .custom-breadcrumb li:not(:last-child)::after {
            content: "/";
            margin-left: 12px;
            color: #adb5bd;
        }

        .stat-card {
            border: 0;
            border-radius: 14px;
            transition: 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 22px;
        }

        .icon-primary {
            background: #e8edff;
            color: #4f46e5;
        }

        .icon-success {
            background: #e7f8ef;
            color: #198754;
        }

        .icon-danger {
            background: #fdecec;
            color: #dc3545;
        }

        .main-card {
            border: 0;
            border-radius: 16px;
            overflow: hidden;
        }

        .main-card-header {
            background: #ffffff;
            padding: 20px 24px;
            border-bottom: 1px solid #edf0f5;
        }

        .search-box {
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .search-box input {
            padding-left: 40px;
            border-radius: 10px;
        }

        .table thead th {
            background: #f8f9fc;
            color: #6b7280;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            padding: 15px;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            color: #374151;
        }

        .table tbody tr {
            transition: 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f8faff;
        }

        .college-logo {
            width: 46px;
            height: 46px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            background: #f8f9fa;
        }

        .college-name {
            font-weight: 600;
            color: #1f2937;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .action-btn {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }

        .async-feedback {
            min-height: 18px;
        }

        .async-feedback.text-success {
            color: #198754 !important;
        }

        .async-spinner {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
        }

        @media (max-width: 768px) {
            .main-card-header {
                padding: 16px;
            }

            .table {
                min-width: 900px;
            }
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
                    <h4 class="page-title mb-1">All Colleges</h4>
                    <p class="page-subtitle mb-3">Manage colleges registered on the Evenza platform</p>
                    <ul class="custom-breadcrumb">
                        <li><a href="Dashboard.php">Home</a></li>
                        <li>College Management</li>
                        <li>All Colleges</li>
                    </ul>
                </div>

                <div class="mt-3 mt-md-0">
                    <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#addCollegeModal">
                        <i class="bi bi-plus-lg me-2"></i>
                        Add College
                    </button>
                </div>
            </div>

            <!-- Statistics -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon icon-primary me-3"><i class="bi bi-bank2"></i></div>
                            <div>
                                <small class="text-muted">Total Colleges</small>
                                <h4 class="mb-0 mt-1" id="totalCount"><?= $total ?></h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon icon-success me-3"><i class="bi bi-check-circle"></i></div>
                            <div>
                                <small class="text-muted">Active Colleges</small>
                                <h4 class="mb-0 mt-1" id="activeCount"><?= $active ?></h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon icon-danger me-3"><i class="bi bi-x-circle"></i></div>
                            <div>
                                <small class="text-muted">Inactive Colleges</small>
                                <h4 class="mb-0 mt-1" id="inactiveCount"><?= $inactive ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- College Table -->
            <div class="card main-card shadow-sm">

                <div class="main-card-header">
                    <div class="row align-items-center g-3">
                        <div class="col-md-5">
                            <h5 class="mb-1">College List</h5>
                            <small class="text-muted">View and manage all colleges</small>
                        </div>
                        <div class="col-md-7">
                            <div class="row g-2 justify-content-md-end">
                                <div class="col-md-7">
                                    <div class="search-box">
                                        <i class="bi bi-search"></i>
                                        <input type="text" class="form-control" id="collegeSearch" placeholder="Search college...">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <select class="form-select" id="statusFilter">
                                        <option value="">All Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="collegeTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>College</th>
                                    <th>University</th>
                                    <th>Slug</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Status Toggle</th>
                                    <th>Created</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if ($total === 0): ?>
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">
                                            No colleges yet. Click "Add College" to create one.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($colleges as $i => $c): ?>
                                        <?php
                                        $isActive = $c['status'] === 'active';
                                        $logoSrc = !empty($c['logo'])
                                            ? $LOGO_WEB_PATH . htmlspecialchars($c['logo'])
                                            : $LOGO_WEB_PATH . 'placeholder.png';
                                        ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>

                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="<?= $logoSrc ?>" class="college-logo" alt="College Logo">
                                                    <div class="college-name"><?= htmlspecialchars($c['name']) ?></div>
                                                </div>
                                            </td>

                                            <td><?= htmlspecialchars($c['university_name'] ?? 'Unknown') ?></td>
                                            <td><?= htmlspecialchars($c['slug']) ?></td>
                                            <td><?= htmlspecialchars($c['email']) ?></td>
                                            <td><?= htmlspecialchars($c['phone'] ?? '—') ?></td>

                                            <td>
                                                <span class="badge <?= $isActive ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?> status-badge">
                                                    <?= $isActive ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>

                                            <td>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input status-toggle" type="checkbox" role="switch"
                                                        data-id="<?= (int) $c['college_id'] ?>" <?= $isActive ? 'checked' : '' ?>>
                                                </div>
                                            </td>

                                            <td><?= date('d M Y', strtotime($c['created_at'])) ?></td>

                                            <td class="text-end">
                                                <button type="button" class="btn btn-light action-btn me-1" title="Edit"
                                                    data-bs-toggle="modal" data-bs-target="#editCollegeModal<?= (int) $c['college_id'] ?>">
                                                    <i class="bi bi-pencil text-primary"></i>
                                                </button>
                                                <button type="button" class="btn btn-light action-btn" title="Delete"
                                                    onclick="deleteCollege(<?= (int) $c['college_id'] ?>)">
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

                <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center">
                    <small class="text-muted">Showing <?= $total ?> of <?= $total ?> colleges</small>
                </div>

            </div>

        </div>
    </div>

    <!-- ===================== ADD COLLEGE MODAL ===================== -->
    <div class="modal fade" id="addCollegeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <input type="hidden" name="form_action" value="create">

                    <div class="modal-header">
                        <h5 class="modal-title">Add College</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">College Name</label>
                                <input type="text" class="form-control" name="name" required minlength="2">
                                <div class="invalid-feedback">College name is required.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Slug / Short Name</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" name="slug" required maxlength="150"
                                        pattern="[A-Za-z0-9\-]+" data-async-check="slug"
                                        aria-describedby="slugAsyncFeedbackAdd">
                                    <span class="async-spinner spinner-border spinner-border-sm text-secondary d-none"
                                        role="status" aria-hidden="true"></span>
                                </div>
                                <div class="invalid-feedback">Letters, numbers, hyphens only.</div>
                                <div class="async-feedback small mt-1" id="slugAsyncFeedbackAdd" role="alert" aria-live="polite"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">University</label>
                                <select class="form-select" name="university_id" required>
                                    <option value="">Choose</option>
                                    <?php foreach ($universities as $u): ?>
                                        <option value="<?= (int) $u['university_id'] ?>"><?= htmlspecialchars($u['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Select a university.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <div class="position-relative">
                                    <input type="email" class="form-control" name="email" required maxlength="100"
                                        data-async-check="email" aria-describedby="emailAsyncFeedbackAdd">
                                    <span class="async-spinner spinner-border spinner-border-sm text-secondary d-none"
                                        role="status" aria-hidden="true"></span>
                                </div>
                                <div class="invalid-feedback">Enter a valid email.</div>
                                <div class="async-feedback small mt-1" id="emailAsyncFeedbackAdd" role="alert" aria-live="polite"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Login Password</label>
                                <input type="password" class="form-control" name="password" required minlength="8">
                                <div class="invalid-feedback">Minimum 8 characters.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone" required pattern="[0-9+\-\s]{7,20}">
                                <div class="invalid-feedback">Phone number is required (valid format).</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" required>
                                    <option value="">Choose</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <div class="invalid-feedback">Select a status.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Logo (JPG/PNG, max 2MB)</label>
                                <input type="file" class="form-control" name="logo" accept="image/png,image/jpeg" required>
                                <div class="invalid-feedback">College logo is required.</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" style="height:90px" required minlength="5"></textarea>
                                <div class="invalid-feedback">Address is required (min 5 characters).</div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save College</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- ===================== EDIT COLLEGE MODALS (one per row) ===================== -->
    <?php foreach ($colleges as $c): ?>
        <div class="modal fade" id="editCollegeModal<?= (int) $c['college_id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <input type="hidden" name="form_action" value="update">
                        <input type="hidden" name="id" value="<?= (int) $c['college_id'] ?>">

                        <div class="modal-header">
                            <h5 class="modal-title">Edit College</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">College Name</label>
                                    <input type="text" class="form-control" name="name" required minlength="2" value="<?= htmlspecialchars($c['name']) ?>">
                                    <div class="invalid-feedback">College name is required.</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Slug / Short Name</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control" name="slug" required maxlength="150"
                                            pattern="[A-Za-z0-9\-]+" value="<?= htmlspecialchars($c['slug']) ?>"
                                            data-async-check="slug" aria-describedby="slugAsyncFeedback<?= (int) $c['college_id'] ?>">
                                        <span class="async-spinner spinner-border spinner-border-sm text-secondary d-none"
                                            role="status" aria-hidden="true"></span>
                                    </div>
                                    <div class="invalid-feedback">Letters, numbers, hyphens only.</div>
                                    <div class="async-feedback small mt-1" id="slugAsyncFeedback<?= (int) $c['college_id'] ?>" role="alert" aria-live="polite"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">University</label>
                                    <select class="form-select" name="university_id" required>
                                        <?php foreach ($universities as $u): ?>
                                            <option value="<?= (int) $u['university_id'] ?>" <?= (int) $u['university_id'] === (int) $c['university_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($u['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <div class="position-relative">
                                        <input type="email" class="form-control" name="email" required maxlength="100"
                                            value="<?= htmlspecialchars($c['email']) ?>"
                                            data-async-check="email" aria-describedby="emailAsyncFeedback<?= (int) $c['college_id'] ?>">
                                        <span class="async-spinner spinner-border spinner-border-sm text-secondary d-none"
                                            role="status" aria-hidden="true"></span>
                                    </div>
                                    <div class="invalid-feedback">Enter a valid email.</div>
                                    <div class="async-feedback small mt-1" id="emailAsyncFeedback<?= (int) $c['college_id'] ?>" role="alert" aria-live="polite"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Reset Password <span class="text-muted">(optional)</span></label>
                                    <input type="password" class="form-control" name="password" minlength="8" placeholder="Leave blank to keep current password">
                                    <div class="invalid-feedback">Minimum 8 characters.</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" name="phone" required pattern="[0-9+\-\s]{7,20}" value="<?= htmlspecialchars($c['phone'] ?? '') ?>">
                                    <div class="invalid-feedback">Phone number is required (valid format).</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status" required>
                                        <option value="active" <?= $c['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="inactive" <?= $c['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        <?= empty($c['logo']) ? 'Logo' : 'Replace Logo (optional)' ?>
                                    </label>
                                    <input type="file" class="form-control" name="logo" accept="image/png,image/jpeg"
                                        <?= empty($c['logo']) ? 'required' : '' ?>>
                                    <?php if (!empty($c['logo'])): ?>
                                        <small class="text-muted">Current: <?= htmlspecialchars($c['logo']) ?></small>
                                    <?php else: ?>
                                        <div class="invalid-feedback">College logo is required.</div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control" name="address" style="height:90px" required minlength="5"><?= htmlspecialchars($c['address'] ?? '') ?></textarea>
                                    <div class="invalid-feedback">Address is required (min 5 characters).</div>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update College</button>
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
        layout_change('false');
        layout_theme_sidebar_change('dark');
        change_box_container('false');
        layout_caption_change('true');
        layout_rtl_change('false');
        preset_change('preset-1');
        main_layout_change('vertical');
    </script>

    <script>
        // Bootstrap client-side validation styling for both modal forms
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

        // Search & Filter
        const searchInput = document.getElementById("collegeSearch");
        const statusFilter = document.getElementById("statusFilter");
        const rows = document.querySelectorAll("#collegeTable tbody tr");

        function filterColleges() {
            const searchValue = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();

            rows.forEach(row => {
                if (!row.querySelector(".status-badge")) return; // skip the "no data" row

                const rowText = row.innerText.toLowerCase();
                const status = row.cells[6].innerText.toLowerCase().trim();

                const matchesSearch = rowText.includes(searchValue);
                const matchesStatus = statusValue === "" || status === statusValue;

                row.style.display = matchesSearch && matchesStatus ? "" : "none";
            });
        }

        searchInput.addEventListener("keyup", filterColleges);
        statusFilter.addEventListener("change", filterColleges);

        // Status Toggle (AJAX, persisted to DB)
        const activeCountBox = document.getElementById("activeCount");
        const inactiveCountBox = document.getElementById("inactiveCount");

        function setBadge(row, isActive) {
            const badge = row.cells[6].querySelector(".status-badge");
            if (isActive) {
                badge.textContent = "Active";
                badge.classList.remove("bg-danger-subtle", "text-danger");
                badge.classList.add("bg-success-subtle", "text-success");
            } else {
                badge.textContent = "Inactive";
                badge.classList.remove("bg-success-subtle", "text-success");
                badge.classList.add("bg-danger-subtle", "text-danger");
            }
        }

        function adjustCounts(isActive) {
            let activeVal = parseInt(activeCountBox.textContent, 10) || 0;
            let inactiveVal = parseInt(inactiveCountBox.textContent, 10) || 0;

            if (isActive) {
                activeCountBox.textContent = activeVal + 1;
                if (inactiveVal > 0) inactiveCountBox.textContent = inactiveVal - 1;
            } else {
                if (activeVal > 0) activeCountBox.textContent = activeVal - 1;
                inactiveCountBox.textContent = inactiveVal + 1;
            }
        }

        document.querySelectorAll(".status-toggle").forEach(function(toggle) {
            toggle.addEventListener("change", function() {
                const row = this.closest("tr");
                const collegeId = this.dataset.id;
                const isActive = this.checked;

                setBadge(row, isActive);
                adjustCounts(isActive);
                filterColleges();

                this.disabled = true;

                fetch("colleges.php?action=toggle_status", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            id: collegeId,
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
                        setBadge(row, !isActive);
                        adjustCounts(!isActive);
                        filterColleges();
                        alert("Could not update status. Please try again.");
                    })
                    .finally(() => {
                        toggle.disabled = false;
                    });
            });
        });

        function deleteCollege(id) {
            if (confirm("Are you sure you want to delete this college?")) {
                window.location.href = "colleges.php?action=delete&id=" + id;
            }
        }

        // ===================== ASYNC UNIQUENESS VALIDATION (slug / email) =====================
        (function() {
            const DEBOUNCE_MS = 450;
            const timers = new WeakMap();

            function debounce(el, fn) {
                clearTimeout(timers.get(el));
                timers.set(el, setTimeout(fn, DEBOUNCE_MS));
            }

            function setState(input, feedbackEl, spinner, state, message) {
                spinner.classList.toggle("d-none", state !== "checking");

                if (state === "valid") {
                    input.setAttribute("aria-invalid", "false");
                    input.setCustomValidity("");
                    feedbackEl.textContent = "Available";
                    feedbackEl.classList.remove("text-danger");
                    feedbackEl.classList.add("text-success");
                } else if (state === "invalid") {
                    input.setAttribute("aria-invalid", "true");
                    input.setCustomValidity(message || "Already in use.");
                    feedbackEl.textContent = message || "Already in use.";
                    feedbackEl.classList.remove("text-success");
                    feedbackEl.classList.add("text-danger");
                } else if (state === "checking") {
                    input.setCustomValidity("Checking availability…");
                    feedbackEl.textContent = "Checking availability…";
                    feedbackEl.classList.remove("text-success", "text-danger");
                } else {
                    // idle
                    input.setCustomValidity("");
                    feedbackEl.textContent = "";
                    feedbackEl.classList.remove("text-success", "text-danger");
                }
                input.dataset.asyncState = state;
            }

            async function runCheck(input) {
                const form = input.closest("form");
                if (!form) return;

                const field = input.dataset.asyncCheck;
                const value = input.value.trim();
                const feedbackId = input.getAttribute("aria-describedby");
                const feedbackEl = feedbackId ? document.getElementById(feedbackId) : null;
                const spinner = input.parentElement.querySelector(".async-spinner");
                const idField = form.querySelector('input[name="id"]');

                if (!feedbackEl || !spinner) return;

                if (!value) {
                    setState(input, feedbackEl, spinner, "idle");
                    return;
                }

                // Skip the network round-trip if native constraints already fail
                if (!input.checkValidity()) {
                    setState(input, feedbackEl, spinner, "idle");
                    return;
                }

                setState(input, feedbackEl, spinner, "checking");

                try {
                    const res = await fetch("colleges.php?action=check_unique", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            field,
                            value,
                            id: idField ? idField.value : null
                        })
                    });

                    if (!res.ok) throw new Error("Request failed");
                    const data = await res.json();

                    setState(
                        input,
                        feedbackEl,
                        spinner,
                        data.available ? "valid" : "invalid",
                        data.message
                    );
                } catch {
                    // Network failure: don't hard-block the user client-side.
                    // Final server-side validate_college() check on submit is still authoritative.
                    setState(input, feedbackEl, spinner, "idle");
                }
            }

            document.addEventListener("input", function(e) {
                if (!e.target.matches("[data-async-check]")) return;
                debounce(e.target, () => runCheck(e.target));
            });

            // Gate submission: block if a field is still checking or already known-invalid
            document.addEventListener("submit", function(e) {
                const form = e.target;
                const asyncFields = form.querySelectorAll("[data-async-check]");
                if (!asyncFields.length) return;

                const blocking = [...asyncFields].some(
                    el => el.dataset.asyncState === "checking" || el.dataset.asyncState === "invalid"
                );

                if (blocking) {
                    e.preventDefault();
                    e.stopPropagation();
                    form.classList.add("was-validated");
                    asyncFields.forEach(el => {
                        if (el.dataset.asyncState === "checking") runCheck(el);
                    });
                }
            });
        })();
    </script>

</body>

</html>
