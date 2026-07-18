<?php
/**
 * universities.php
 * Single-file University management: DB connection, validation,
 * create / read / update / delete, status toggle (AJAX), and UI.
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

$LOGO_DIR = __DIR__ . '/assets/images/universities';
$LOGO_WEB_PATH = 'assets/images/universities/';

/* =========================================================
   VALIDATION HELPERS
   ========================================================= */
function validate_university(PDO $pdo, array $data, ?int $excludeId = null): array
{
    $errors = [];

    $name      = trim($data['name'] ?? '');
    $shortName = trim($data['short_name'] ?? '');
    $email     = trim($data['email'] ?? '');
    $phone     = trim($data['phone'] ?? '');
    $address   = trim($data['address'] ?? '');
    $status    = trim($data['status'] ?? '');

    if ($name === '' || mb_strlen($name) < 2) {
        $errors[] = 'University name must be at least 2 characters.';
    } elseif (mb_strlen($name) > 150) {
        $errors[] = 'University name is too long.';
    }

    if ($shortName === '') {
        $errors[] = 'Short name is required.';
    } elseif (!preg_match('/^[A-Za-z0-9\-]{1,20}$/', $shortName)) {
        $errors[] = 'Short name may only contain letters, numbers, and hyphens (max 20 chars).';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email address.';
    }

    if ($phone === '' || !preg_match('/^[0-9+\-\s]{7,20}$/', $phone)) {
        $errors[] = 'Enter a valid phone number (7-20 digits).';
    }

    if ($address === '') {
        $errors[] = 'Address is required.';
    }

    if (!in_array($status, ['active', 'inactive'], true)) {
        $errors[] = 'Select a valid status.';
    }

    if (!in_array('Short name is required.', $errors, true) && $shortName !== '') {
        $sql = 'SELECT university_id FROM universities WHERE short_name = :short_name';
        $params = ['short_name' => $shortName];
        if ($excludeId !== null) {
            $sql .= ' AND university_id != :id';
            $params['id'] = $excludeId;
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        if ($stmt->fetch()) {
            $errors[] = 'This short name is already in use.';
        }
    }

    if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $sql = 'SELECT university_id FROM universities WHERE email = :email';
        $params = ['email' => $email];
        if ($excludeId !== null) {
            $sql .= ' AND university_id != :id';
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

    $filename = 'u_' . bin2hex(random_bytes(8)) . '.' . $allowedTypes[$mime];

    if (!move_uploaded_file($file['tmp_name'], $destDir . '/' . $filename)) {
        throw new RuntimeException('Could not save uploaded logo.');
    }

    return $filename;
}

/* =========================================================
   AJAX: STATUS TOGGLE
   universities.php?action=toggle_status  (POST, JSON body)
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

    $check = $pdo->prepare('SELECT university_id FROM universities WHERE university_id = :id');
    $check->execute(['id' => $id]);
    if (!$check->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'University not found.']);
        exit;
    }

    $stmt = $pdo->prepare('UPDATE universities SET status = :status, updated_at = NOW() WHERE university_id = :id');
    $stmt->execute(['status' => $status, 'id' => $id]);

    echo json_encode(['success' => true]);
    exit;
}

/* =========================================================
   DELETE
   universities.php?action=delete&id=3  (GET)
   ========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['action'] ?? '') === 'delete') {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($id) {
        $stmt = $pdo->prepare('SELECT logo FROM universities WHERE university_id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if ($row) {
            $pdo->prepare('DELETE FROM universities WHERE university_id = :id')->execute(['id' => $id]);

            if (!empty($row['logo'])) {
                $path = $LOGO_DIR . '/' . $row['logo'];
                if (is_file($path)) {
                    unlink($path);
                }
            }

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'University deleted successfully.'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'University not found.'];
        }
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Invalid university id.'];
    }

    header('Location: universities.php');
    exit;
}

/* =========================================================
   CREATE / UPDATE
   Normal (non-AJAX) form POST, using Post/Redirect/Get.
   ========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_action'])) {

    if ($_POST['form_action'] === 'create') {

        $errors = validate_university($pdo, $_POST);

        if (empty($errors)) {
            try {
                $logo = handle_logo_upload($_FILES['logo'] ?? null, $LOGO_DIR);

                $stmt = $pdo->prepare(
                    'INSERT INTO universities (name, short_name, email, phone, address, logo, status, created_at, updated_at)
                     VALUES (:name, :short_name, :email, :phone, :address, :logo, :status, NOW(), NOW())'
                );
                $stmt->execute([
                    'name'       => trim($_POST['name']),
                    'short_name' => trim($_POST['short_name']),
                    'email'      => trim($_POST['email']),
                    'phone'      => trim($_POST['phone']),
                    'address'    => trim($_POST['address']),
                    'logo'       => $logo,
                    'status'     => trim($_POST['status']),
                ]);

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'University added successfully.'];

            } catch (RuntimeException $e) {
                $errors[] = $e->getMessage();
            } catch (PDOException $e) {
                $errors[] = 'Could not save university. Please try again.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => implode(' ', $errors)];
            $_SESSION['reopen_modal'] = 'addUniversityModal';
        }

        header('Location: alluniversity.php');
        exit;
    }

    if ($_POST['form_action'] === 'update') {

        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        $existingStmt = $pdo->prepare('SELECT * FROM universities WHERE university_id = :id');
        $existingStmt->execute(['id' => $id]);
        $existing = $existingStmt->fetch();

        if (!$id || !$existing) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'University not found.'];
            header('Location: universities.php');
            exit;
        }

        $errors = validate_university($pdo, $_POST, $id);

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
                    'UPDATE universities
                     SET name = :name, short_name = :short_name, email = :email,
                         phone = :phone, address = :address, logo = :logo,
                         status = :status, updated_at = NOW()
                     WHERE university_id = :id'
                );
                $stmt->execute([
                    'name'       => trim($_POST['name']),
                    'short_name' => trim($_POST['short_name']),
                    'email'      => trim($_POST['email']),
                    'phone'      => trim($_POST['phone']),
                    'address'    => trim($_POST['address']),
                    'logo'       => $logoToStore,
                    'status'     => trim($_POST['status']),
                    'id'         => $id,
                ]);

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'University updated successfully.'];

            } catch (RuntimeException $e) {
                $errors[] = $e->getMessage();
            } catch (PDOException $e) {
                $errors[] = 'Could not update university. Please try again.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => implode(' ', $errors)];
            $_SESSION['reopen_modal'] = 'editUniversityModal' . $id;
        }

        header('Location: alluniversity.php');
        exit;
    }
}

/* =========================================================
   DATA FOR DISPLAY
   ========================================================= */
$universities = $pdo->query('SELECT * FROM universities ORDER BY created_at DESC')->fetchAll();
$total    = count($universities);
$active   = count(array_filter($universities, fn($u) => $u['status'] === 'active'));
$inactive = $total - $active;

$flash = $_SESSION['flash'] ?? null;
$reopenModal = $_SESSION['reopen_modal'] ?? null;
unset($_SESSION['flash'], $_SESSION['reopen_modal']);
?>
<!doctype html>
<html lang="en">

<head>

    <title>All Universities | Evenza Admin</title>

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
        .university-logo { width: 46px; height: 46px; object-fit: cover; border-radius: 12px; border: 1px solid #e5e7eb; background: #f8f9fa; }
        .university-name { font-weight: 600; color: #1f2937; }
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
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

            <?php if ($flash): ?>
                <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($flash['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Page Header -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="page-title mb-1">All Universities</h4>
                    <p class="page-subtitle mb-3">Manage universities registered on the Evenza platform</p>
                    <ul class="custom-breadcrumb">
                        <li><a href="Dashboard.php">Home</a></li>
                        <li>University Management</li>
                        <li>All Universities</li>
                    </ul>
                </div>

                <div class="mt-3 mt-md-0">
                    <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#addUniversityModal">
                        <i class="bi bi-plus-lg me-2"></i>
                        Add University
                    </button>
                </div>
            </div>

            <!-- Statistics -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon icon-primary me-3"><i class="bi bi-building"></i></div>
                            <div>
                                <small class="text-muted">Total Universities</small>
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
                                <small class="text-muted">Active Universities</small>
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
                                <small class="text-muted">Inactive Universities</small>
                                <h4 class="mb-0 mt-1" id="inactiveCount"><?= $inactive ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- University Table -->
            <div class="card main-card shadow-sm">

                <div class="main-card-header">
                    <div class="row align-items-center g-3">
                        <div class="col-md-5">
                            <h5 class="mb-1">University List</h5>
                            <small class="text-muted">View and manage all universities</small>
                        </div>
                        <div class="col-md-7">
                            <div class="row g-2 justify-content-md-end">
                                <div class="col-md-7">
                                    <div class="search-box">
                                        <i class="bi bi-search"></i>
                                        <input type="text" class="form-control" id="searchUniversity" placeholder="Search university...">
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
                        <table class="table table-hover align-middle mb-0" id="universityTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>University</th>
                                    <th>Short Name</th>
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
                                        <td colspan="9" class="text-center text-muted py-4">
                                            No universities yet. Click "Add University" to create one.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($universities as $i => $u): ?>
                                        <?php
                                            $isActive = $u['status'] === 'active';
                                            $logoSrc = !empty($u['logo'])
                                                ? $LOGO_WEB_PATH . htmlspecialchars($u['logo'])
                                                : $LOGO_WEB_PATH . 'placeholder.png';
                                        ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>

                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="<?= $logoSrc ?>" class="university-logo" alt="University Logo">
                                                    <div class="university-name"><?= htmlspecialchars($u['name']) ?></div>
                                                </div>
                                            </td>

                                            <td><?= htmlspecialchars($u['short_name']) ?></td>
                                            <td><?= htmlspecialchars($u['email']) ?></td>
                                            <td><?= htmlspecialchars($u['phone']) ?></td>

                                            <td>
                                                <span class="badge <?= $isActive ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?> status-badge">
                                                    <?= $isActive ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>

                                            <td>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input status-toggle" type="checkbox" role="switch"
                                                        data-id="<?= (int) $u['university_id'] ?>" <?= $isActive ? 'checked' : '' ?>>
                                                </div>
                                            </td>

                                            <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>

                                            <td class="text-end">
                                                <button type="button" class="btn btn-light action-btn me-1" title="Edit"
                                                    data-bs-toggle="modal" data-bs-target="#editUniversityModal<?= (int) $u['university_id'] ?>">
                                                    <i class="bi bi-pencil text-primary"></i>
                                                </button>
                                                <button class="btn btn-light action-btn" title="Delete" onclick="deleteUniversity(<?= (int) $u['university_id'] ?>)">
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
                    <small class="text-muted">Showing <?= $total ?> of <?= $total ?> universities</small>
                </div>

            </div>

        </div>
    </div>

    <!-- ===================== ADD UNIVERSITY MODAL ===================== -->
    <div class="modal fade" id="addUniversityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <input type="hidden" name="form_action" value="create">

                    <div class="modal-header">
                        <h5 class="modal-title">Add University</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">University Name</label>
                                <input type="text" class="form-control" name="name" required minlength="2">
                                <div class="invalid-feedback">Enter university name (min 2 characters).</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Short Name</label>
                                <input type="text" class="form-control" name="short_name" required maxlength="20" pattern="[A-Za-z0-9\-]+">
                                <div class="invalid-feedback">Letters, numbers, hyphens only (max 20 chars).</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                                <div class="invalid-feedback">Enter a valid email.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone" required pattern="[0-9+\-\s]{7,20}">
                                <div class="invalid-feedback">Enter a valid phone number.</div>
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
                                <input type="file" class="form-control" name="logo" accept="image/png,image/jpeg">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" style="height:90px" required></textarea>
                                <div class="invalid-feedback">Enter address.</div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save University</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- ===================== EDIT UNIVERSITY MODALS (one per row) ===================== -->
    <?php foreach ($universities as $u): ?>
        <div class="modal fade" id="editUniversityModal<?= (int) $u['university_id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <input type="hidden" name="form_action" value="update">
                        <input type="hidden" name="id" value="<?= (int) $u['university_id'] ?>">

                        <div class="modal-header">
                            <h5 class="modal-title">Edit University</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">University Name</label>
                                    <input type="text" class="form-control" name="name" required minlength="2" value="<?= htmlspecialchars($u['name']) ?>">
                                    <div class="invalid-feedback">Enter university name (min 2 characters).</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Short Name</label>
                                    <input type="text" class="form-control" name="short_name" required maxlength="20" pattern="[A-Za-z0-9\-]+" value="<?= htmlspecialchars($u['short_name']) ?>">
                                    <div class="invalid-feedback">Letters, numbers, hyphens only (max 20 chars).</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" required value="<?= htmlspecialchars($u['email']) ?>">
                                    <div class="invalid-feedback">Enter a valid email.</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" name="phone" required pattern="[0-9+\-\s]{7,20}" value="<?= htmlspecialchars($u['phone']) ?>">
                                    <div class="invalid-feedback">Enter a valid phone number.</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status" required>
                                        <option value="active" <?= $u['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="inactive" <?= $u['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Replace Logo (optional)</label>
                                    <input type="file" class="form-control" name="logo" accept="image/png,image/jpeg">
                                    <?php if (!empty($u['logo'])): ?>
                                        <small class="text-muted">Current: <?= htmlspecialchars($u['logo']) ?></small>
                                    <?php endif; ?>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control" name="address" style="height:90px" required><?= htmlspecialchars($u['address']) ?></textarea>
                                    <div class="invalid-feedback">Enter address.</div>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update University</button>
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
        const searchInput = document.getElementById("searchUniversity");
        const statusFilter = document.getElementById("statusFilter");
        const rows = document.querySelectorAll("#universityTable tbody tr");

        function filterUniversities() {
            const searchValue = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();

            rows.forEach(row => {
                if (!row.querySelector(".status-badge")) return;

                const rowText = row.innerText.toLowerCase();
                const status = row.cells[5].innerText.toLowerCase().trim();

                const matchesSearch = rowText.includes(searchValue);
                const matchesStatus = statusValue === "" || status === statusValue;

                row.style.display = matchesSearch && matchesStatus ? "" : "none";
            });
        }

        searchInput.addEventListener("keyup", filterUniversities);
        statusFilter.addEventListener("change", filterUniversities);

        // Status Toggle (AJAX)
        const activeCountBox = document.getElementById("activeCount");
        const inactiveCountBox = document.getElementById("inactiveCount");

        function setBadge(row, isActive) {
            const badge = row.cells[5].querySelector(".status-badge");
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
                const universityId = this.dataset.id;
                const isActive = this.checked;

                setBadge(row, isActive);
                adjustCounts(isActive);
                filterUniversities();

                this.disabled = true;

                fetch("alluniversity.php?action=toggle_status", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ id: universityId, status: isActive ? "active" : "inactive" })
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
                        filterUniversities();
                        alert("Could not update status. Please try again.");
                    })
                    .finally(() => {
                        toggle.disabled = false;
                    });
            });
        });

        function deleteUniversity(id) {
            if (confirm("Are you sure you want to delete this university?")) {
                window.location.href = "universities.php?action=delete&id=" + id;
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
