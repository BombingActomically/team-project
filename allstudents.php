<?php

/**
 * students.php
 * Single-file Student management: DB connection, validation,
 * create / read / update / delete, account-status toggle (AJAX),
 * async uniqueness checks (AJAX), and UI.
 *
 * Assumes a `students` table:
 *   student_id, college_id, enrollment_no, name, email, password, phone,
 *   gender, semester, id_card_image, profile_photo,
 *   verification_status, account_status, created_at, updated_at
 * with college_id as a foreign key to colleges.college_id.
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

$IDCARD_DIR      = __DIR__ . '/assets/images/students/idcards';
$IDCARD_WEB_PATH = 'assets/images/students/idcards/';
$PHOTO_DIR       = __DIR__ . '/assets/images/students/profiles';
$PHOTO_WEB_PATH  = 'assets/images/students/profiles/';

/* =========================================================
   VALIDATION HELPERS
   ========================================================= */
function validate_student(
    PDO $pdo,
    array $data,
    ?int $excludeId = null,
    ?array $idCardFile = null,
    ?string $existingIdCard = null,
    ?array $photoFile = null,
    ?string $existingPhoto = null
): array {
    $errors = [];

    $name          = trim($data['name'] ?? '');
    $collegeId     = filter_var($data['college_id'] ?? null, FILTER_VALIDATE_INT);
    $enrollmentNo  = trim($data['enrollment_no'] ?? '');
    $email         = trim($data['email'] ?? '');
    $phone         = trim($data['phone'] ?? '');
    $gender        = trim($data['gender'] ?? '');
    $semester      = trim($data['semester'] ?? '');
    $accountStatus = trim($data['account_status'] ?? '');
    $verifyStatus  = trim($data['verification_status'] ?? '');
    $password      = (string) ($data['password'] ?? '');

    if ($name === '' || mb_strlen($name) < 2) {
        $errors[] = 'Student name must be at least 2 characters.';
    } elseif (mb_strlen($name) > 100) {
        $errors[] = 'Student name is too long (max 100 characters).';
    }

    if (!$collegeId) {
        $errors[] = 'Select a valid college.';
    } else {
        $chk = $pdo->prepare('SELECT college_id FROM colleges WHERE college_id = :id');
        $chk->execute(['id' => $collegeId]);
        if (!$chk->fetch()) {
            $errors[] = 'Selected college does not exist.';
        }
    }

    if ($enrollmentNo === '') {
        $errors[] = 'Enrollment number is required.';
    } elseif (mb_strlen($enrollmentNo) > 50) {
        $errors[] = 'Enrollment number is too long (max 50 characters).';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 100) {
        $errors[] = 'Enter a valid email address (max 100 characters).';
    }

    if ($phone === '') {
        $errors[] = 'Phone number is required.';
    } elseif (!preg_match('/^[0-9+\-\s]{7,20}$/', $phone)) {
        $errors[] = 'Enter a valid phone number.';
    }

    if ($gender === '') {
        $errors[] = 'Select a gender.';
    } elseif (!in_array($gender, ['male', 'female', 'other'], true)) {
        $errors[] = 'Select a valid gender.';
    }

    if ($semester === '') {
        $errors[] = 'Semester is required.';
    } elseif (mb_strlen($semester) > 20) {
        $errors[] = 'Semester value is too long (max 20 characters).';
    }

    if (!file_provided($idCardFile) && empty($existingIdCard)) {
        $errors[] = 'Student ID card image is required.';
    }

    if (!file_provided($photoFile) && empty($existingPhoto)) {
        $errors[] = 'Profile photo is required.';
    }

    if (!in_array($accountStatus, ['active', 'inactive'], true)) {
        $errors[] = 'Select a valid account status.';
    }

    if (!in_array($verifyStatus, ['pending', 'verified', 'rejected'], true)) {
        $errors[] = 'Select a valid verification status.';
    }

    if ($excludeId === null) {
        if (mb_strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }
    } elseif ($password !== '' && mb_strlen($password) < 8) {
        $errors[] = 'New password must be at least 8 characters (leave blank to keep the current password).';
    }

    if ($enrollmentNo !== '') {
        $sql = 'SELECT student_id FROM students WHERE enrollment_no = :enrollment_no';
        $params = ['enrollment_no' => $enrollmentNo];
        if ($excludeId !== null) {
            $sql .= ' AND student_id != :id';
            $params['id'] = $excludeId;
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        if ($stmt->fetch()) {
            $errors[] = 'This enrollment number is already in use.';
        }
    }

    if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $sql = 'SELECT student_id FROM students WHERE email = :email';
        $params = ['email' => $email];
        if ($excludeId !== null) {
            $sql .= ' AND student_id != :id';
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

function file_provided(?array $file): bool
{
    return $file !== null && isset($file['error']) && $file['error'] !== UPLOAD_ERR_NO_FILE;
}

function handle_image_upload(?array $file, string $destDir, string $prefix, bool $required = false): ?string
{
    if (!$file || !isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Image upload failed. Please try again.');
    }

    $allowedTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!isset($allowedTypes[$mime])) {
        throw new RuntimeException('Only JPG or PNG images are allowed.');
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        throw new RuntimeException('Image must be smaller than 2MB.');
    }

    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    $filename = $prefix . '_' . bin2hex(random_bytes(8)) . '.' . $allowedTypes[$mime];

    if (!move_uploaded_file($file['tmp_name'], $destDir . '/' . $filename)) {
        throw new RuntimeException('Could not save uploaded image.');
    }

    return $filename;
}

/* =========================================================
   AJAX Handlers
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

    $check = $pdo->prepare('SELECT student_id FROM students WHERE student_id = :id');
    $check->execute(['id' => $id]);
    if (!$check->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Student not found.']);
        exit;
    }

    $stmt = $pdo->prepare('UPDATE students SET account_status = :status, updated_at = NOW() WHERE student_id = :id');
    $stmt->execute(['status' => $status, 'id' => $id]);

    echo json_encode(['success' => true]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['action'] ?? '') === 'check_unique') {
    header('Content-Type: application/json');

    $body      = json_decode(file_get_contents('php://input'), true);
    $field     = $body['field'] ?? '';
    $value     = trim($body['value'] ?? '');
    $excludeId = filter_var($body['id'] ?? null, FILTER_VALIDATE_INT) ?: null;

    if (!in_array($field, ['enrollment_no', 'email'], true) || $value === '') {
        http_response_code(422);
        echo json_encode(['available' => false, 'message' => 'Invalid request.']);
        exit;
    }

    $sql = "SELECT student_id FROM students WHERE {$field} = :value";
    $params = ['value' => $value];
    if ($excludeId !== null) {
        $sql .= ' AND student_id != :id';
        $params['id'] = $excludeId;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $exists = (bool) $stmt->fetch();

    echo json_encode([
        'available' => !$exists,
        'message'   => $exists
            ? ($field === 'enrollment_no' ? 'This enrollment number is already in use.' : 'This email is already registered.')
            : null,
    ]);
    exit;
}

/* =========================================================
   DELETE & POST HANDLERS
   ========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['action'] ?? '') === 'delete') {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($id) {
        $stmt = $pdo->prepare('SELECT id_card_image, profile_photo FROM students WHERE student_id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if ($row) {
            $pdo->prepare('DELETE FROM students WHERE student_id = :id')->execute(['id' => $id]);

            if (!empty($row['id_card_image'])) {
                $path = $IDCARD_DIR . '/' . $row['id_card_image'];
                if (is_file($path)) unlink($path);
            }
            if (!empty($row['profile_photo'])) {
                $path = $PHOTO_DIR . '/' . $row['profile_photo'];
                if (is_file($path)) unlink($path);
            }

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Student deleted successfully.'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Student not found.'];
        }
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Invalid student id.'];
    }

    header('Location: students.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_action'])) {
    if ($_POST['form_action'] === 'create') {
        $errors = validate_student($pdo, $_POST, null, $_FILES['id_card_image'] ?? null, null, $_FILES['profile_photo'] ?? null, null);

        if (empty($errors)) {
            try {
                $idCard  = handle_image_upload($_FILES['id_card_image'] ?? null, $IDCARD_DIR, 'idc', true);
                $photo   = handle_image_upload($_FILES['profile_photo'] ?? null, $PHOTO_DIR, 'pf', false);

                $stmt = $pdo->prepare(
                    'INSERT INTO students (college_id, enrollment_no, name, email, password, phone, gender, semester, id_card_image, profile_photo, verification_status, account_status, created_at, updated_at)
                     VALUES (:college_id, :enrollment_no, :name, :email, :password, :phone, :gender, :semester, :id_card_image, :profile_photo, :verification_status, :account_status, NOW(), NOW())'
                );
                $stmt->execute([
                    'college_id'           => (int) $_POST['college_id'],
                    'enrollment_no'        => trim($_POST['enrollment_no']),
                    'name'                 => trim($_POST['name']),
                    'email'                => trim($_POST['email']),
                    'password'             => password_hash($_POST['password'], PASSWORD_DEFAULT),
                    'phone'                => trim($_POST['phone']) !== '' ? trim($_POST['phone']) : null,
                    'gender'               => trim($_POST['gender']) !== '' ? trim($_POST['gender']) : null,
                    'semester'             => trim($_POST['semester']) !== '' ? trim($_POST['semester']) : null,
                    'id_card_image'        => $idCard,
                    'profile_photo'        => $photo,
                    'verification_status'  => trim($_POST['verification_status']),
                    'account_status'       => trim($_POST['account_status']),
                ]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Student added successfully.'];
            } catch (RuntimeException $e) {
                $errors[] = $e->getMessage();
            } catch (PDOException $e) {
                $errors[] = 'Could not save student. Please try again.';
            }
        }
        if (!empty($errors)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => implode(' ', $errors)];
            $_SESSION['reopen_modal'] = 'addStudentModal';
        }
        header('Location: students.php');
        exit;
    }

    if ($_POST['form_action'] === 'update') {
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        $existingStmt = $pdo->prepare('SELECT * FROM students WHERE student_id = :id');
        $existingStmt->execute(['id' => $id]);
        $existing = $existingStmt->fetch();

        if (!$id || !$existing) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Student not found.'];
            header('Location: students.php');
            exit;
        }

        $errors = validate_student($pdo, $_POST, $id, $_FILES['id_card_image'] ?? null, $existing['id_card_image'] ?? null, $_FILES['profile_photo'] ?? null, $existing['profile_photo'] ?? null);

        if (empty($errors)) {
            try {
                $newIdCard = handle_image_upload($_FILES['id_card_image'] ?? null, $IDCARD_DIR, 'idc', false);
                $newPhoto  = handle_image_upload($_FILES['profile_photo'] ?? null, $PHOTO_DIR, 'pf', false);

                $idCardToStore = $newIdCard !== null ? $newIdCard : $existing['id_card_image'];
                $photoToStore = $newPhoto !== null ? $newPhoto : $existing['profile_photo'];

                $stmt = $pdo->prepare(
                    'UPDATE students
                     SET college_id = :college_id, enrollment_no = :enrollment_no, name = :name, email = :email, phone = :phone, gender = :gender, semester = :semester, id_card_image = :id_card_image, profile_photo = :profile_photo, verification_status = :verification_status, account_status = :account_status, updated_at = NOW()
                     ' . (trim($_POST['password'] ?? '') !== '' ? ', password = :password' : '') . '
                     WHERE student_id = :id'
                );

                $params = [
                    'college_id'          => (int) $_POST['college_id'],
                    'enrollment_no'       => trim($_POST['enrollment_no']),
                    'name'                => trim($_POST['name']),
                    'email'               => trim($_POST['email']),
                    'phone'               => trim($_POST['phone']) !== '' ? trim($_POST['phone']) : null,
                    'gender'              => trim($_POST['gender']) !== '' ? trim($_POST['gender']) : null,
                    'semester'            => trim($_POST['semester']) !== '' ? trim($_POST['semester']) : null,
                    'id_card_image'       => $idCardToStore,
                    'profile_photo'       => $photoToStore,
                    'verification_status' => trim($_POST['verification_status']),
                    'account_status'      => trim($_POST['account_status']),
                    'id'                  => $id,
                ];

                if (trim($_POST['password'] ?? '') !== '') {
                    $params['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                }

                $stmt->execute($params);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Student updated successfully.'];
            } catch (RuntimeException $e) {
                $errors[] = $e->getMessage();
            } catch (PDOException $e) {
                $errors[] = 'Could not update student.';
            }
        }
        if (!empty($errors)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => implode(' ', $errors)];
            $_SESSION['reopen_modal'] = 'editStudentModal' . $id;
        }
        header('Location: students.php');
        exit;
    }
}

$students = $pdo->query('SELECT s.*, c.name AS college_name FROM students s LEFT JOIN colleges c ON c.college_id = s.college_id ORDER BY s.created_at DESC')->fetchAll();
$colleges = $pdo->query('SELECT college_id, name FROM colleges ORDER BY name ASC')->fetchAll();
$total    = count($students);
$active   = count(array_filter($students, fn($s) => $s['account_status'] === 'active'));
$inactive = $total - $active;

$flash = $_SESSION['flash'] ?? null;
$reopenModal = $_SESSION['reopen_modal'] ?? null;
unset($_SESSION['flash'], $_SESSION['reopen_modal']);
?>
<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr">

<head>
    <title>All Students | Admin</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="icon" href="assets/images/favicon.svg" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="assets/fonts/phosphor/duotone/style.css" />
    <link rel="stylesheet" href="assets/fonts/tabler-icons.min.css" />
    <link rel="stylesheet" href="assets/fonts/feather.css" />
    <link rel="stylesheet" href="assets/fonts/fontawesome.css" />
    <link rel="stylesheet" href="assets/fonts/material.css" />
    <link rel="stylesheet" href="assets/css/style.css" id="main-style-link" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .uni-page {
            --ink: #1B2430;
            --ink-2: #45505E;
            
            /* Primary Colors Unified to match alluniversity.php */
            --accent: #4f46e5;
            --accent-hover: #3b31d1;
            --accent-bg: #e8edff;
            
            --violet: #6C5DD3;
            --violet-bg: #EFECFB;
            --bg: #F4F6FA;
            --card: #FFFFFF;
            --success: #1AA260;
            --success-bg: #E7F8EF;
            --danger: #E24C4B;
            --danger-bg: #FCEAEA;
            --warning: #E0A63E;
            --warning-bg: #FBF3E0;
            --border: #E7EAF0;
            --muted: #8A94A6;
            --th-bg: #F8F9FC;
            --tr-hover: #FAFBFD;
            --badge-bg: #EEF1F6;
            --btn-action-bg: #ffffff;
            --btn-edit-hover: #EAF1FE;
            --pagination-disabled-bg: #ffffff;

            background: var(--bg);
            padding-bottom: 8px;
        }

        .uni-page,
        .uni-page .table,
        .uni-page .form-control,
        .uni-page .btn,
        .uni-page h1,
        .uni-page h2,
        .uni-page h5 {
            font-family: 'Inter', -apple-system, sans-serif;
        }

        /* Unified Font Weight (Changed from 700 to 600) */
        .uni-title {
            font-weight: 600;
            font-size: 26px;
            color: var(--ink);
            margin-bottom: 2px;
            letter-spacing: -0.01em;
        }

        .uni-card-sub-top {
            font-size: 13.5px;
            color: var(--muted);
            margin-top: 2px;
        }

        .uni-breadcrumb {
            list-style: none;
            display: flex;
            gap: 6px;
            padding: 0;
            margin: 10px 0 0;
            font-size: 13px;
            color: var(--muted);
        }

        .uni-breadcrumb li+li::before {
            content: "/";
            margin-right: 6px;
            color: var(--border);
        }

        .uni-breadcrumb li {
            display: flex;
            gap: 6px;
        }

        .uni-breadcrumb a {
            color: var(--accent);
            text-decoration: none;
        }

        .uni-breadcrumb a:hover {
            color: var(--accent-hover);
        }

        .uni-stat {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px 22px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: box-shadow .2s ease, transform .2s ease;
        }

        .uni-stat:hover {
            box-shadow: 0 10px 24px rgba(20, 30, 60, 0.06);
            transform: translateY(-2px);
        }

        .uni-stat--total {
            --stat-accent: var(--violet);
            --stat-accent-bg: var(--violet-bg);
        }

        .uni-stat--active {
            --stat-accent: var(--success);
            --stat-accent-bg: var(--success-bg);
        }

        .uni-stat--inactive {
            --stat-accent: var(--danger);
            --stat-accent-bg: var(--danger-bg);
        }

        .uni-stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--stat-accent, var(--accent));
            background: var(--stat-accent-bg, var(--accent-bg));
            flex-shrink: 0;
        }

        .uni-stat-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--muted);
            margin-bottom: 4px;
        }

        .uni-stat-value {
            font-size: 26px;
            font-weight: 700;
            color: var(--ink);
            line-height: 1;
        }

        .uni-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }

        .uni-card-header {
            padding: 20px 22px;
            border-bottom: 1px solid var(--border);
        }

        .uni-card-title {
            font-weight: 700;
            font-size: 18px;
            color: var(--ink);
            margin: 0;
        }

        .uni-card-sub {
            font-size: 12.5px;
            color: var(--muted);
            margin-top: 2px;
        }

        .uni-search {
            position: relative;
        }

        .uni-search i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 14px;
        }

        .uni-search input {
            padding-left: 34px;
            border: 1px solid var(--border);
            border-radius: 8px;
            min-width: 260px;
            font-size: 13.5px;
        }

        .uni-search input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(47, 111, 237, 0.15);
        }

        .uni-status-filter select {
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 13.5px;
            padding: 6px 30px 6px 12px;
        }

        .uni-status-filter select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(47, 111, 237, 0.15);
            outline: none;
        }

        .uni-btn-add {
            background: var(--accent);
            border: 1px solid var(--accent);
            color: #ffffff;
            font-size: 13.5px;
            font-weight: 600;
            padding: 9px 18px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: background .15s ease;
        }

        .uni-btn-add:hover {
            background: var(--accent-hover);
            color: #ffffff;
        }

        .uni-table thead th {
            background: var(--th-bg);
            color: var(--muted);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            border-bottom: 1px solid var(--border);
            padding: 13px 16px;
            white-space: nowrap;
        }

        .uni-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border) !important;
            border-top: none !important;
            font-size: 13.5px;
            color: var(--ink-2);
            vertical-align: middle;
            background: var(--card) !important;
        }

        .uni-table tbody tr {
            border-left: 3px solid transparent;
            transition: background .15s ease, border-color .15s ease;
        }

        .uni-table tbody tr:hover td {
            background: var(--tr-hover) !important;
        }

        .uni-table tbody tr:hover {
            border-left-color: var(--accent);
        }

        .uni-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid var(--border);
            padding: 2px;
            background: #fff;
        }

        .uni-name {
            font-weight: 600;
            font-size: 14px;
            color: var(--ink);
        }

        .uni-enrollment {
            display: inline-block;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .04em;
            color: var(--ink);
            background: var(--badge-bg);
            border-radius: 5px;
            padding: 2px 7px;
            margin-top: 2px;
        }

        .uni-email,
        .uni-phone,
        .uni-semester {
            color: var(--muted);
            font-size: 13px;
        }

        .uni-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px 5px 9px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .uni-pill .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .uni-pill--active {
            background: var(--success-bg);
            color: var(--success);
        }

        .uni-pill--active .dot {
            background: var(--success);
        }

        .uni-pill--inactive {
            background: var(--danger-bg);
            color: var(--danger);
        }

        .uni-pill--inactive .dot {
            background: var(--danger);
        }

        .uni-pill--pending {
            background: var(--warning-bg);
            color: var(--warning);
        }

        .uni-pill--pending .dot {
            background: var(--warning);
        }

        .uni-page .form-switch .form-check-input {
            width: 40px;
            height: 21px;
            cursor: pointer;
            background-color: var(--border);
            border-color: var(--border);
        }

        .uni-page .form-switch .form-check-input:checked {
            background-color: var(--success);
            border-color: var(--success);
        }

        .uni-page .form-switch .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(26, 162, 96, 0.15);
        }

        .uni-action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 7px;
            border: 1px solid var(--border);
            background: var(--btn-action-bg);
            color: var(--muted);
            transition: all .15s ease;
        }

        .uni-action-btn:hover.uni-action-edit {
            border-color: var(--warning);
            color: var(--warning);
            background: var(--warning-bg);
        }

        .uni-action-btn:hover.uni-action-delete {
            border-color: var(--danger);
            color: var(--danger);
            background: var(--danger-bg);
        }

        .async-feedback {
            min-height: 18px;
            font-size: 12px;
        }

        @media (max-width: 767px) {
            .uni-card-header {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .uni-search input {
                min-width: 100%;
            }

            .uni-table {
                min-width: 900px;
            }
        }
    </style>
</head>

<body>

    <div class="loader-bg fixed inset-0 bg-white z-[1034]">
        <div class="loader-track h-[5px] w-full inline-block absolute overflow-hidden top-0">
            <div class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0"></div>
        </div>
    </div>

    <?php include_once("Sidebar.php"); ?>
    <?php include_once("Header.php"); ?>

    <div class="pc-container">
        <div class="pc-content uni-page">

            <?php if ($flash): ?>
                <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($flash['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="page-header uni-header d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div class="page-block">
                    <h1 class="uni-title">All Students</h1>
                    <div class="uni-card-sub-top">Manage students registered on the Evenza platform</div>
                    <ul class="uni-breadcrumb">
                        <li><a href="Index.php">Home</a></li>
                        <li><a href="javascript: void(0)">Student Management</a></li>
                        <li style="color: var(--ink);">All Students</li>
                    </ul>
                </div>
                <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                    <i class="ti ti-plus me-2"></i> Add Student
                </button>
            </div>

            <div class="row g-3 mb-4 mt-1">
                <div class="col-md-4">
                    <div class="uni-stat uni-stat--total">
                        <div class="uni-stat-icon"><i class="bi bi-people-fill"></i></div>
                        <div>
                            <div class="uni-stat-label">Total Students</div>
                            <div class="uni-stat-value" id="totalCount"><?= $total ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="uni-stat uni-stat--active">
                        <div class="uni-stat-icon"><i class="bi bi-check-circle-fill"></i></div>
                        <div>
                            <div class="uni-stat-label">Active Students</div>
                            <div class="uni-stat-value" id="activeCount"><?= $active ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="uni-stat uni-stat--inactive">
                        <div class="uni-stat-icon"><i class="bi bi-x-circle-fill"></i></div>
                        <div>
                            <div class="uni-stat-label">Inactive Students</div>
                            <div class="uni-stat-value" id="inactiveCount"><?= $inactive ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="uni-card">
                        <div class="uni-card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h5 class="uni-card-title">Student List</h5>
                                <div class="uni-card-sub">Search and manage student profiles and active status.</div>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <div class="uni-search">
                                    <i class="bi bi-search"></i>
                                    <input type="text" id="studentSearch" class="form-control form-control-sm" placeholder="Search by name, email, enrollment...">
                                </div>
                                <div class="uni-status-filter">
                                    <select id="statusFilter" class="form-select form-select-sm">
                                        <option value="">All Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table uni-table align-middle mb-0" id="studentTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student</th>
                                            <th>College</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Semester</th>
                                            <th>Verification</th>
                                            <th>Status</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($total === 0): ?>
                                            <tr>
                                                <td colspan="9" class="text-center text-muted py-4">
                                                    No students yet. Click "Add Student" to create one.
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($students as $i => $s): ?>
                                                <?php
                                                $isActive = $s['account_status'] === 'active';
                                                $verify   = $s['verification_status'];
                                                $verifyPillClass = $verify === 'verified' ? 'uni-pill--active' : ($verify === 'rejected' ? 'uni-pill--inactive' : 'uni-pill--pending');
                                                $photoSrc = !empty($s['profile_photo']) ? $PHOTO_WEB_PATH . htmlspecialchars($s['profile_photo']) : $PHOTO_WEB_PATH . 'placeholder.png';
                                                ?>
                                                <tr data-id="<?= (int) $s['student_id'] ?>">
                                                    <td><?= $i + 1 ?></td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <img src="<?= $photoSrc ?>" class="uni-avatar" alt="Avatar">
                                                            <div>
                                                                <div class="uni-name"><?= htmlspecialchars($s['name']) ?></div>
                                                                <?php if (!empty($s['enrollment_no'])): ?>
                                                                    <span class="uni-enrollment"><?= htmlspecialchars($s['enrollment_no']) ?></span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><?= htmlspecialchars($s['college_name'] ?? 'Unknown') ?></td>
                                                    <td class="uni-email"><?= htmlspecialchars($s['email']) ?></td>
                                                    <td class="uni-phone"><?= htmlspecialchars($s['phone'] ?? '—') ?></td>
                                                    <td class="uni-semester"><?= htmlspecialchars($s['semester'] ?? '—') ?></td>
                                                    <td>
                                                        <span class="uni-pill <?= $verifyPillClass ?> verification-badge">
                                                            <span class="dot"></span><?= ucfirst($verify) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="uni-pill <?= $isActive ? 'uni-pill--active' : 'uni-pill--inactive' ?> status-badge">
                                                                <span class="dot"></span><?= $isActive ? 'Active' : 'Inactive' ?>
                                                            </span>
                                                            <div class="form-check form-switch mb-0">
                                                                <input class="form-check-input status-toggle" type="checkbox" role="switch" data-id="<?= (int) $s['student_id'] ?>" <?= $isActive ? 'checked' : '' ?>>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end">
                                                        <button type="button" class="uni-action-btn uni-action-edit me-1" title="Edit" data-bs-toggle="modal" data-bs-target="#editStudentModal<?= (int) $s['student_id'] ?>">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button type="button" class="uni-action-btn uni-action-delete" title="Delete" onclick="deleteStudent(<?= (int) $s['student_id'] ?>)">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
                                <small class="text-muted">Showing <?= $total ?> of <?= $total ?> students</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== ADD STUDENT MODAL ===================== -->
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <input type="hidden" name="form_action" value="create">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Student Name</label>
                                <input type="text" class="form-control" name="name" required minlength="2" maxlength="100">
                                <div class="invalid-feedback">Student name is required.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">College</label>
                                <select class="form-select" name="college_id" required>
                                    <option value="">Choose</option>
                                    <?php foreach ($colleges as $c): ?>
                                        <option value="<?= (int) $c['college_id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Select a college.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Enrollment No</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" name="enrollment_no" required maxlength="50" data-async-check="enrollment_no" aria-describedby="enrollAsyncFeedbackAdd">
                                    <span class="async-spinner spinner-border spinner-border-sm text-secondary d-none" role="status" aria-hidden="true"></span>
                                </div>
                                <div class="invalid-feedback">Enrollment number is required.</div>
                                <div class="async-feedback mt-1" id="enrollAsyncFeedbackAdd" role="alert" aria-live="polite"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <div class="position-relative">
                                    <input type="email" class="form-control" name="email" required maxlength="100" data-async-check="email" aria-describedby="emailAsyncFeedbackAdd">
                                    <span class="async-spinner spinner-border spinner-border-sm text-secondary d-none" role="status" aria-hidden="true"></span>
                                </div>
                                <div class="invalid-feedback">Enter a valid email.</div>
                                <div class="async-feedback mt-1" id="emailAsyncFeedbackAdd" role="alert" aria-live="polite"></div>
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
                            <div class="col-md-4">
                                <label class="form-label">Gender</label>
                                <select class="form-select" name="gender" required>
                                    <option value="">Choose</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                                <div class="invalid-feedback">Select a gender.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Semester</label>
                                <input type="text" class="form-control" name="semester" required maxlength="20" placeholder="e.g. 4">
                                <div class="invalid-feedback">Semester is required.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Account Status</label>
                                <select class="form-select" name="account_status" required>
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Verification Status</label>
                                <select class="form-select" name="verification_status" required>
                                    <option value="pending" selected>Pending</option>
                                    <option value="verified">Verified</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Profile Photo (JPG/PNG, max 2MB)</label>
                                <input type="file" class="form-control" name="profile_photo" accept="image/png,image/jpeg" required>
                                <div class="invalid-feedback">Profile photo is required.</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Student ID Card Image (JPG/PNG, max 2MB)</label>
                                <input type="file" class="form-control" name="id_card_image" accept="image/png,image/jpeg" required>
                                <div class="invalid-feedback">Student ID card image is required.</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ===================== EDIT STUDENT MODALS ===================== -->
    <?php foreach ($students as $s): ?>
        <div class="modal fade" id="editStudentModal<?= (int) $s['student_id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <input type="hidden" name="form_action" value="update">
                        <input type="hidden" name="id" value="<?= (int) $s['student_id'] ?>">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Student</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Student Name</label>
                                    <input type="text" class="form-control" name="name" required minlength="2" maxlength="100" value="<?= htmlspecialchars($s['name']) ?>">
                                    <div class="invalid-feedback">Student name is required.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">College</label>
                                    <select class="form-select" name="college_id" required>
                                        <?php foreach ($colleges as $c): ?>
                                            <option value="<?= (int) $c['college_id'] ?>" <?= (int) $c['college_id'] === (int) $s['college_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($c['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Enrollment No</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control" name="enrollment_no" required maxlength="50" value="<?= htmlspecialchars($s['enrollment_no'] ?? '') ?>" data-async-check="enrollment_no" aria-describedby="enrollAsyncFeedback<?= (int) $s['student_id'] ?>">
                                        <span class="async-spinner spinner-border spinner-border-sm text-secondary d-none" role="status" aria-hidden="true"></span>
                                    </div>
                                    <div class="invalid-feedback">Enrollment number is required.</div>
                                    <div class="async-feedback mt-1" id="enrollAsyncFeedback<?= (int) $s['student_id'] ?>" role="alert" aria-live="polite"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <div class="position-relative">
                                        <input type="email" class="form-control" name="email" required maxlength="100" value="<?= htmlspecialchars($s['email']) ?>" data-async-check="email" aria-describedby="emailAsyncFeedback<?= (int) $s['student_id'] ?>">
                                        <span class="async-spinner spinner-border spinner-border-sm text-secondary d-none" role="status" aria-hidden="true"></span>
                                    </div>
                                    <div class="invalid-feedback">Enter a valid email.</div>
                                    <div class="async-feedback mt-1" id="emailAsyncFeedback<?= (int) $s['student_id'] ?>" role="alert" aria-live="polite"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Reset Password <span class="text-muted">(optional)</span></label>
                                    <input type="password" class="form-control" name="password" minlength="8" placeholder="Leave blank to keep current password">
                                    <div class="invalid-feedback">Minimum 8 characters.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" name="phone" required pattern="[0-9+\-\s]{7,20}" value="<?= htmlspecialchars($s['phone'] ?? '') ?>">
                                    <div class="invalid-feedback">Phone number is required (valid format).</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Gender</label>
                                    <select class="form-select" name="gender" required>
                                        <option value="">Choose</option>
                                        <option value="male" <?= $s['gender'] === 'male' ? 'selected' : '' ?>>Male</option>
                                        <option value="female" <?= $s['gender'] === 'female' ? 'selected' : '' ?>>Female</option>
                                        <option value="other" <?= $s['gender'] === 'other' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                    <div class="invalid-feedback">Select a gender.</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Semester</label>
                                    <input type="text" class="form-control" name="semester" required maxlength="20" value="<?= htmlspecialchars($s['semester'] ?? '') ?>">
                                    <div class="invalid-feedback">Semester is required.</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Account Status</label>
                                    <select class="form-select" name="account_status" required>
                                        <option value="active" <?= $s['account_status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="inactive" <?= $s['account_status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Verification Status</label>
                                    <select class="form-select" name="verification_status" required>
                                        <option value="pending" <?= $s['verification_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="verified" <?= $s['verification_status'] === 'verified' ? 'selected' : '' ?>>Verified</option>
                                        <option value="rejected" <?= $s['verification_status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><?= empty($s['profile_photo']) ? 'Profile Photo' : 'Replace Profile Photo (optional)' ?></label>
                                    <input type="file" class="form-control" name="profile_photo" accept="image/png,image/jpeg">
                                    <?php if (!empty($s['profile_photo'])): ?><small class="text-muted">Current: <?= htmlspecialchars($s['profile_photo']) ?></small><?php endif; ?>
                                </div>
                                <div class="col-12">
                                    <label class="form-label"><?= empty($s['id_card_image']) ? 'Student ID Card Image' : 'Replace ID Card Image (optional)' ?></label>
                                    <input type="file" class="form-control" name="id_card_image" accept="image/png,image/jpeg">
                                    <?php if (!empty($s['id_card_image'])): ?><small class="text-muted">Current: <?= htmlspecialchars($s['id_card_image']) ?></small><?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Student</button>
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

        const searchInput = document.getElementById("studentSearch");
        const statusFilter = document.getElementById("statusFilter");
        const rows = document.querySelectorAll("#studentTable tbody tr");

        function filterStudents() {
            const searchValue = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();

            rows.forEach(row => {
                if (!row.querySelector(".status-badge")) return;
                const rowText = row.innerText.toLowerCase();
                const statusBadge = row.querySelector(".status-badge");
                const status = statusBadge ? statusBadge.innerText.toLowerCase().trim() : '';

                const matchesSearch = rowText.includes(searchValue);
                const matchesStatus = statusValue === "" || status === statusValue;
                row.style.display = matchesSearch && matchesStatus ? "" : "none";
            });
        }
        searchInput.addEventListener("keyup", filterStudents);
        statusFilter.addEventListener("change", filterStudents);

        const activeCountBox = document.getElementById("activeCount");
        const inactiveCountBox = document.getElementById("inactiveCount");

        function setBadge(row, isActive) {
            const badge = row.querySelector(".status-badge");
            badge.innerHTML = '<span class="dot"></span>' + (isActive ? 'Active' : 'Inactive');
            badge.classList.remove(isActive ? 'uni-pill--inactive' : 'uni-pill--active');
            badge.classList.add(isActive ? 'uni-pill--active' : 'uni-pill--inactive');
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
                const studentId = this.dataset.id;
                const isActive = this.checked;

                setBadge(row, isActive);
                adjustCounts(isActive);
                filterStudents();
                this.disabled = true;

                fetch("students.php?action=toggle_status", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ id: studentId, status: isActive ? "active" : "inactive" })
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
                        filterStudents();
                        alert("Could not update status. Please try again.");
                    })
                    .finally(() => { toggle.disabled = false; });
            });
        });

        function deleteStudent(id) {
            if (confirm("Are you sure you want to delete this student?")) {
                window.location.href = "students.php?action=delete&id=" + id;
            }
        }

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
                    feedbackEl.className = "async-feedback mt-1 text-success";
                } else if (state === "invalid") {
                    input.setAttribute("aria-invalid", "true");
                    input.setCustomValidity(message || "Already in use.");
                    feedbackEl.textContent = message || "Already in use.";
                    feedbackEl.className = "async-feedback mt-1 text-danger";
                } else if (state === "checking") {
                    input.setCustomValidity("Checking availability…");
                    feedbackEl.textContent = "Checking availability…";
                    feedbackEl.className = "async-feedback mt-1 text-secondary";
                } else {
                    input.setCustomValidity("");
                    feedbackEl.textContent = "";
                    feedbackEl.className = "async-feedback mt-1";
                }
                input.dataset.asyncState = state;
            }

            async function runCheck(input) {
                const form = input.closest("form");
                if (!form) return;
                const field = input.dataset.asyncCheck;
                const value = input.value.trim();
                const feedbackEl = document.getElementById(input.getAttribute("aria-describedby"));
                const spinner = input.parentElement.querySelector(".async-spinner");
                const idField = form.querySelector('input[name="id"]');

                if (!feedbackEl || !spinner) return;
                if (!value || !input.checkValidity()) {
                    setState(input, feedbackEl, spinner, "idle");
                    return;
                }

                setState(input, feedbackEl, spinner, "checking");

                try {
                    const res = await fetch("students.php?action=check_unique", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ field, value, id: idField ? idField.value : null })
                    });
                    if (!res.ok) throw new Error("Request failed");
                    const data = await res.json();
                    setState(input, feedbackEl, spinner, data.available ? "valid" : "invalid", data.message);
                } catch {
                    setState(input, feedbackEl, spinner, "idle");
                }
            }

            document.addEventListener("input", function(e) {
                if (!e.target.matches("[data-async-check]")) return;
                debounce(e.target, () => runCheck(e.target));
            });

            document.addEventListener("submit", function(e) {
                const form = e.target;
                const asyncFields = form.querySelectorAll("[data-async-check]");
                if (!asyncFields.length) return;

                const blocking = [...asyncFields].some(el => el.dataset.asyncState === "checking" || el.dataset.asyncState === "invalid");
                if (blocking) {
                    e.preventDefault();
                    e.stopPropagation();
                    form.classList.add("was-validated");
                    asyncFields.forEach(el => { if (el.dataset.asyncState === "checking") runCheck(el); });
                }
            });
        })();
    </script>
</body>
</html>
