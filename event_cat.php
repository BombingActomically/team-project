<?php
/**
 * event_cat.php
 * Single-file Event Category management: DB connection, validation,
 * create / read / update / delete, status toggle (AJAX), limit & pagination, and UI.
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
function validate_category(PDO $pdo, array $data, ?int $excludeId = null): array
{
    $errors = [];

    $name   = trim($data['name'] ?? '');
    $status = trim($data['status'] ?? '');

    if ($name === '' || mb_strlen($name) < 2) {
        $errors[] = 'Category name must be at least 2 characters.';
    } elseif (mb_strlen($name) > 100) {
        $errors[] = 'Category name is too long.';
    } elseif (preg_match('/^[\d\s]+$/', $name)) {
        // Rejects names that are purely numbers/spaces (e.g. "123", "45 67").
        $errors[] = 'Category name cannot be numbers only. It must contain letters.';
    } elseif (!preg_match('/[A-Za-z]/u', $name)) {
        // Extra safety net for names with no letters at all (e.g. only symbols).
        $errors[] = 'Category name must contain letters.';
    }

    if (!in_array($status, ['active', 'inactive'], true)) {
        $errors[] = 'Select a valid status.';
    }

    if ($name !== '') {
        $sql = 'SELECT category_id FROM categories WHERE name = :name';
        $params = ['name' => $name];
        if ($excludeId !== null) {
            $sql .= ' AND category_id != :id';
            $params['id'] = $excludeId;
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        if ($stmt->fetch()) {
            $errors[] = 'This category name already exists.';
        }
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

    if (!$id || !in_array($status, ['active', 'inactive'], true)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        exit;
    }

    $check = $pdo->prepare('SELECT category_id FROM categories WHERE category_id = :id');
    $check->execute(['id' => $id]);
    if (!$check->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Category not found.']);
        exit;
    }

    $stmt = $pdo->prepare('UPDATE categories SET status = :status WHERE category_id = :id');
    $stmt->execute(['status' => $status, 'id' => $id]);

    echo json_encode(['success' => true]);
    exit;
}

/* =========================================================
   DELETE
   ========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['action'] ?? '') === 'delete') {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($id) {
        $stmt = $pdo->prepare('SELECT category_id FROM categories WHERE category_id = :id');
        $stmt->execute(['id' => $id]);
        
        if ($stmt->fetch()) {
            try {
                $pdo->prepare('DELETE FROM categories WHERE category_id = :id')->execute(['id' => $id]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Category deleted successfully.'];
            } catch (PDOException $e) {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Cannot delete category as it is currently linked to events.'];
            }
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Category not found.'];
        }
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Invalid category id.'];
    }

    header('Location: event_cat.php');
    exit;
}

/* =========================================================
   CREATE / UPDATE
   ========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_action'])) {

    if ($_POST['form_action'] === 'create') {
        $errors = validate_category($pdo, $_POST);

        if (empty($errors)) {
            try {
                $stmt = $pdo->prepare(
                    'INSERT INTO categories (name, status) VALUES (:name, :status)'
                );
                $stmt->execute([
                    'name'   => trim($_POST['name']),
                    'status' => trim($_POST['status']),
                ]);

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Category added successfully.'];
            } catch (PDOException $e) {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Could not save category. Please try again.'];
            }
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => implode(' ', $errors)];
            $_SESSION['reopen_modal'] = 'addCategoryModal';
        }

        header('Location: event_cat.php');
        exit;
    }

    if ($_POST['form_action'] === 'update') {
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        $existingStmt = $pdo->prepare('SELECT * FROM categories WHERE category_id = :id');
        $existingStmt->execute(['id' => $id]);
        $existing = $existingStmt->fetch();

        if (!$id || !$existing) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Category not found.'];
            header('Location: event_cat.php');
            exit;
        }

        $errors = validate_category($pdo, $_POST, $id);

        if (empty($errors)) {
            try {
                $stmt = $pdo->prepare(
                    'UPDATE categories SET status = :status, name = :name WHERE category_id = :id'
                );
                $stmt->execute([
                    'name'   => trim($_POST['name']),
                    'status' => trim($_POST['status']),
                    'id'     => $id,
                ]);

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Category updated successfully.'];
            } catch (PDOException $e) {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Could not update category. Please try again.'];
            }
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => implode(' ', $errors)];
            $_SESSION['reopen_modal'] = 'editCategoryModal' . $id;
        }

        header('Location: event_cat.php');
        exit;
    }
}

/* =========================================================
   DATA FOR DISPLAY
   ========================================================= */
$categories = $pdo->query('SELECT * FROM categories ORDER BY category_id DESC')->fetchAll();
$total      = count($categories);
$active     = count(array_filter($categories, fn($c) => $c['status'] === 'active'));
$inactive   = $total - $active;

$flash       = $_SESSION['flash'] ?? null;
$reopenModal = $_SESSION['reopen_modal'] ?? null;
unset($_SESSION['flash'], $_SESSION['reopen_modal']);
?>
<!doctype html>
<html lang="en">

<head>

    <title>Event Categories | Evenza Admin</title>

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
        .category-icon { width: 46px; height: 46px; display: flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 20px; background: #e8edff; color: #4f46e5; }
        .category-name { font-weight: 600; color: #1f2937; }
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .action-btn { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; }
        .page-link { cursor: pointer; }
        @media (max-width: 768px) {
            .main-card-header { padding: 16px; }
            .table { min-width: 700px; }
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
                <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show mb-4" role="alert">
                    <?= htmlspecialchars($flash['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Page Header -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="page-title mb-1">Event Categories</h4>
                    <p class="page-subtitle mb-3">Manage categories registered on the Evenza platform</p>
                    <ul class="custom-breadcrumb">
                        <li><a href="Dashboard.php">Home</a></li>
                        <li>Category Management</li>
                        <li>All Categories</li>
                    </ul>
                </div>

                <div class="mt-3 mt-md-0">
                    <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="bi bi-plus-lg me-2"></i>
                        Add Category
                    </button>
                </div>
            </div>

            <!-- Statistics -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon icon-primary me-3"><i class="bi bi-tags"></i></div>
                            <div>
                                <small class="text-muted">Total Categories</small>
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
                                <small class="text-muted">Active Categories</small>
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
                                <small class="text-muted">Inactive Categories</small>
                                <h4 class="mb-0 mt-1" id="inactiveCount"><?= $inactive ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Table -->
            <div class="card main-card shadow-sm">

                <div class="main-card-header">
                    <div class="row align-items-center g-3">
                        <div class="col-md-5">
                            <h5 class="mb-1">Category List</h5>
                            <small class="text-muted">View and manage all categories</small>
                        </div>
                        <div class="col-md-7">
                            <div class="row g-2 justify-content-md-end">
                                <div class="col-md-7">
                                    <div class="search-box">
                                        <i class="bi bi-search"></i>
                                        <input type="text" class="form-control" id="searchCategory" placeholder="Search category...">
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
                        <table class="table table-hover align-middle mb-0" id="categoryTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Status Toggle</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if ($total === 0): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            No categories yet. Click "Add Category" to create one.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($categories as $i => $c): ?>
                                        <?php $isActive = $c['status'] === 'active'; ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>

                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="category-icon">
                                                        <i class="bi bi-tags"></i>
                                                    </div>
                                                    <div class="category-name"><?= htmlspecialchars($c['name']) ?></div>
                                                </div>
                                            </td>

                                            <td>
                                                <span class="badge <?= $isActive ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?> status-badge">
                                                    <?= $isActive ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>

                                            <td>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input status-toggle" type="checkbox" role="switch"
                                                        data-id="<?= (int) $c['category_id'] ?>" <?= $isActive ? 'checked' : '' ?>>
                                                </div>
                                            </td>

                                            <td class="text-end">
                                                <button type="button" class="btn btn-light action-btn me-1" title="Edit"
                                                    data-bs-toggle="modal" data-bs-target="#editCategoryModal<?= (int) $c['category_id'] ?>">
                                                    <i class="bi bi-pencil text-primary"></i>
                                                </button>
                                                <button class="btn btn-light action-btn" title="Delete" onclick="deleteCategory(<?= (int) $c['category_id'] ?>)">
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
                                <small class="text-muted ms-2" id="showingCountText">Showing 0 of 0 categories</small>
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

    <!-- ===================== ADD CATEGORY MODAL ===================== -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="form_action" value="create">

                    <div class="modal-header">
                        <h5 class="modal-title">Add Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">

                            <div class="col-12">
                                <label class="form-label">Category Name</label>
                                <input type="text" class="form-control" name="name" required minlength="2" maxlength="100"
                                    pattern="^(?!^[0-9\s]+$).+$" title="Category name cannot be numbers only.">
                                <div class="invalid-feedback">Enter a valid category name (letters required, not numbers only).</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" required>
                                    <option value="">Choose</option>
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <div class="invalid-feedback">Select a status.</div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Category</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- ===================== EDIT CATEGORY MODALS ===================== -->
    <?php foreach ($categories as $c): ?>
        <div class="modal fade" id="editCategoryModal<?= (int) $c['category_id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" class="needs-validation" novalidate>
                        <input type="hidden" name="form_action" value="update">
                        <input type="hidden" name="id" value="<?= (int) $c['category_id'] ?>">

                        <div class="modal-header">
                            <h5 class="modal-title">Edit Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row g-3">

                                <div class="col-12">
                                    <label class="form-label">Category Name</label>
                                    <input type="text" class="form-control" name="name" required minlength="2" maxlength="100"
                                        pattern="^(?!^[0-9\s]+$).+$" title="Category name cannot be numbers only."
                                        value="<?= htmlspecialchars($c['name']) ?>">
                                    <div class="invalid-feedback">Enter a valid category name (letters required, not numbers only).</div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status" required>
                                        <option value="active" <?= $c['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="inactive" <?= $c['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                    </select>
                                    <div class="invalid-feedback">Select a status.</div>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Category</button>
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
        // Bootstrap client-side validation styling
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
        const searchInput = document.getElementById("searchCategory");
        const statusFilter = document.getElementById("statusFilter");
        const limitSelect = document.getElementById("limitSelect");
        const rows = document.querySelectorAll("#categoryTable tbody tr");
        const showingCountText = document.getElementById("showingCountText");
        const paginationContainer = document.getElementById("pagination");

        let currentPage = 1;

        function filterCategories() {
            const searchValue = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();
            const limitValue = limitSelect.value;

            // 1. Filter matching rows
            const matchedRows = [];
            rows.forEach(row => {
                const badge = row.querySelector(".status-badge");
                if (!badge) return; // skip empty state row

                const rowText = row.innerText.toLowerCase();
                const status = badge.innerText.toLowerCase().trim();

                const matchesSearch = rowText.includes(searchValue);
                const matchesStatus = statusValue === "" || status === statusValue;

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
            showingCountText.textContent = `Showing ${visibleCount} of ${totalMatched} categories`;

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
                    filterCategories();
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
                    filterCategories();
                });
                paginationContainer.appendChild(nextLi);
            }
        }

        searchInput.addEventListener("keyup", () => { currentPage = 1; filterCategories(); });
        statusFilter.addEventListener("change", () => { currentPage = 1; filterCategories(); });
        limitSelect.addEventListener("change", () => { currentPage = 1; filterCategories(); });

        // Initial Filter Execution
        document.addEventListener("DOMContentLoaded", filterCategories);

        // Status Toggle (AJAX)
        const activeCountBox = document.getElementById("activeCount");
        const inactiveCountBox = document.getElementById("inactiveCount");

        function setBadge(row, isActive) {
            const badge = row.querySelector(".status-badge");
            if (badge) {
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
                const categoryId = this.dataset.id;
                const isActive = this.checked;

                setBadge(row, isActive);
                adjustCounts(isActive);
                filterCategories();

                this.disabled = true;

                fetch("event_cat.php?action=toggle_status", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ id: categoryId, status: isActive ? "active" : "inactive" })
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
                        filterCategories();
                        alert("Could not update status. Please try again.");
                    })
                    .finally(() => { toggle.disabled = false; });
            });
        });

        function deleteCategory(id) {
            if (confirm("Are you sure you want to delete this category?")) {
                window.location.href = "event_cat.php?action=delete&id=" + id;
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
