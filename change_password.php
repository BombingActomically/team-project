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

$errors  = [];
$success = false;

$admin_id = $_SESSION['admin_id'] ?? 0;

/* Fetch current admin password */
$stmt = $pdo->prepare('SELECT password FROM admins WHERE admin_id = :id');
$stmt->execute(['id' => $admin_id]);
$adminRow = $stmt->fetch();
$storedPassword = $adminRow['password'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword     = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Current password check
    if ($storedPassword === null) {
        $errors['current_password'] = 'Admin account not found.';
    } elseif ($currentPassword === '') {
        $errors['current_password'] = 'Please enter your current password.';
    } elseif ($currentPassword !== $storedPassword) {
        // NOTE: plain-text comparison (DB me plain password store hai)
        $errors['current_password'] = 'Current password is incorrect.';
    }

    // New password checks
    if ($newPassword === '') {
        $errors['new_password'] = 'Please enter a new password.';
    } elseif (strlen($newPassword) < 8) {
        $errors['new_password'] = 'Password must be at least 8 characters long.';
    } elseif (!preg_match('/[A-Z]/', $newPassword)) {
        $errors['new_password'] = 'Password must include at least one uppercase letter.';
    } elseif (!preg_match('/[a-z]/', $newPassword)) {
        $errors['new_password'] = 'Password must include at least one lowercase letter.';
    } elseif (!preg_match('/[0-9]/', $newPassword)) {
        $errors['new_password'] = 'Password must include at least one number.';
    } elseif (!preg_match('/[^a-zA-Z0-9]/', $newPassword)) {
        $errors['new_password'] = 'Password must include at least one special character.';
    } elseif ($storedPassword !== null && $newPassword === $storedPassword) {
        $errors['new_password'] = 'New password must be different from the current password.';
    }

    // Confirm password check
    if ($confirmPassword === '') {
        $errors['confirm_password'] = 'Please confirm your new password.';
    } elseif ($newPassword !== $confirmPassword) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        try {
            $upd = $pdo->prepare('UPDATE admins SET password = :pass WHERE admin_id = :id');
            $upd->execute(['pass' => $newPassword, 'id' => $admin_id]);
            $success = true;
            $storedPassword = $newPassword;
        } catch (PDOException $e) {
            $errors['current_password'] = 'Something went wrong. Please try again.';
        }
    }
}
?>
<!doctype html>
<html lang="en">

<head>

    <title>Change Password | Evenza Admin</title>

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
        .header-icon { width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 20px; background: #e8edff; color: #4f46e5; }
        .form-label { font-weight: 500; color: #374151; }
        .input-group .btn-eye { border: 1px solid #dee2e6; background: #f8f9fa; color: #6b7280; }
        .input-group .btn-eye:hover { background: #eef1f6; }
        .rule-item { transition: 0.2s ease; }
        .strength-wrap { height: 6px; border-radius: 20px; background: #eceff4; overflow: hidden; }
        .strength-wrap .bar { height: 100%; width: 0; transition: 0.3s ease; border-radius: 20px; }
        .info-note { background: #f8faff; border: 1px solid #e6ecff; border-radius: 12px; }
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
                    <h4 class="page-title mb-1">Change Password</h4>
                    <p class="page-subtitle mb-3">Update your account password to keep it secure</p>
                    <ul class="custom-breadcrumb">
                        <li><a href="Dashboard.php">Home</a></li>
                        <li>Admin Settings</li>
                        <li>Change Password</li>
                    </ul>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-7">

                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Your password has been changed successfully.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Please fix the following:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach ($errors as $err): ?>
                                    <li><?= htmlspecialchars($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="card main-card shadow-sm">

                        <div class="main-card-header d-flex align-items-center gap-3">
                            <div class="header-icon"><i class="bi bi-shield-lock"></i></div>
                            <div>
                                <h5 class="mb-0">Update Your Password</h5>
                                <small class="text-muted">Choose a strong, unique password</small>
                            </div>
                        </div>

                        <div class="card-body p-4">

                            <form action="" method="post" id="changePasswordForm" class="needs-validation" novalidate>

                                <!-- Current Password -->
                                <div class="mb-3">
                                    <label class="form-label" for="current_password">
                                        Current Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                            class="form-control <?= isset($errors['current_password']) ? 'is-invalid' : '' ?>"
                                            name="current_password" id="current_password" required>
                                        <button class="btn btn-eye toggle-password" type="button" data-target="current_password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <div class="invalid-feedback">
                                            <?= isset($errors['current_password']) ? htmlspecialchars($errors['current_password']) : 'Please enter your current password.' ?>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- New Password -->
                                <div class="mb-3">
                                    <label class="form-label" for="new_password">
                                        New Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                            class="form-control <?= isset($errors['new_password']) ? 'is-invalid' : '' ?>"
                                            name="new_password" id="new_password" minlength="8" required>
                                        <button class="btn btn-eye toggle-password" type="button" data-target="new_password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <div class="invalid-feedback">
                                            <?= isset($errors['new_password']) ? htmlspecialchars($errors['new_password']) : 'Please enter a valid new password.' ?>
                                        </div>
                                    </div>

                                    <!-- Strength meter -->
                                    <div class="strength-wrap mt-2">
                                        <div id="strengthBar" class="bar"></div>
                                    </div>
                                    <small id="strengthLabel" class="text-muted">Password strength</small>

                                    <!-- Live requirement checklist -->
                                    <ul class="list-unstyled small mt-3 mb-0" id="passwordRules">
                                        <li id="rule-length" class="rule-item text-muted mb-1"><i class="bi bi-circle me-1"></i> At least 8 characters</li>
                                        <li id="rule-upper" class="rule-item text-muted mb-1"><i class="bi bi-circle me-1"></i> One uppercase letter</li>
                                        <li id="rule-lower" class="rule-item text-muted mb-1"><i class="bi bi-circle me-1"></i> One lowercase letter</li>
                                        <li id="rule-number" class="rule-item text-muted mb-1"><i class="bi bi-circle me-1"></i> One number</li>
                                        <li id="rule-special" class="rule-item text-muted mb-1"><i class="bi bi-circle me-1"></i> One special character</li>
                                    </ul>
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-3">
                                    <label class="form-label" for="confirm_password">
                                        Confirm New Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                            class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>"
                                            name="confirm_password" id="confirm_password" required>
                                        <button class="btn btn-eye toggle-password" type="button" data-target="confirm_password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <div class="invalid-feedback" id="confirmFeedback">
                                            <?= isset($errors['confirm_password']) ? htmlspecialchars($errors['confirm_password']) : 'Passwords do not match.' ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-note d-flex align-items-start gap-2 p-3 mb-4">
                                    <i class="bi bi-info-circle text-primary mt-1"></i>
                                    <div class="small text-muted">
                                        Use a unique password you don't use elsewhere. You'll be asked to sign in again on other devices after changing it.
                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="text-end">
                                    <a href="Dashboard.php" class="btn btn-light px-4">Cancel</a>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-save me-1"></i> Update Password
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>

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

    <!-- Change Password: strength meter, live rules, validation -->
    <script>
        (function () {
            'use strict';

            const form            = document.getElementById('changePasswordForm');
            const newPassword     = document.getElementById('new_password');
            const confirmPassword = document.getElementById('confirm_password');
            const strengthBar     = document.getElementById('strengthBar');
            const strengthLabel   = document.getElementById('strengthLabel');
            const confirmFeedback = document.getElementById('confirmFeedback');

            const rules = {
                length:  { test: v => v.length >= 8,          el: document.getElementById('rule-length') },
                upper:   { test: v => /[A-Z]/.test(v),        el: document.getElementById('rule-upper') },
                lower:   { test: v => /[a-z]/.test(v),        el: document.getElementById('rule-lower') },
                number:  { test: v => /[0-9]/.test(v),        el: document.getElementById('rule-number') },
                special: { test: v => /[^a-zA-Z0-9]/.test(v), el: document.getElementById('rule-special') }
            };

            function updateRules(value) {
                let passedCount = 0;
                Object.values(rules).forEach(function (rule) {
                    const icon = rule.el.querySelector('i');
                    if (rule.test(value)) {
                        rule.el.classList.remove('text-muted');
                        rule.el.classList.add('text-success');
                        icon.className = 'bi bi-check-circle-fill me-1';
                        passedCount++;
                    } else {
                        rule.el.classList.remove('text-success');
                        rule.el.classList.add('text-muted');
                        icon.className = 'bi bi-circle me-1';
                    }
                });
                return passedCount;
            }

            function updateStrengthBar(passedCount) {
                const percent = (passedCount / 5) * 100;
                strengthBar.style.width = percent + '%';

                let color = '#dc3545';
                let label = 'Very weak';
                if (passedCount <= 1)      { color = '#dc3545'; label = 'Very weak'; }
                else if (passedCount <= 3) { color = '#ffc107'; label = 'Moderate'; }
                else if (passedCount === 4){ color = '#fd7e14'; label = 'Strong'; }
                else                       { color = '#198754'; label = 'Very strong'; }

                strengthBar.style.background = color;
                strengthLabel.textContent = newPassword.value ? 'Password strength: ' + label : 'Password strength';
            }

            newPassword.addEventListener('input', function () {
                const passedCount = updateRules(newPassword.value);
                updateStrengthBar(passedCount);
                if (newPassword.checkValidity() && passedCount === 5) {
                    newPassword.classList.remove('is-invalid');
                }
                checkConfirmMatch();
            });

            function checkConfirmMatch() {
                if (confirmPassword.value === '') return;
                if (confirmPassword.value !== newPassword.value) {
                    confirmPassword.setCustomValidity('mismatch');
                    confirmPassword.classList.add('is-invalid');
                    confirmFeedback.textContent = 'Passwords do not match.';
                } else {
                    confirmPassword.setCustomValidity('');
                    confirmPassword.classList.remove('is-invalid');
                }
            }

            confirmPassword.addEventListener('input', checkConfirmMatch);

            // Show/hide password toggles
            document.querySelectorAll('.toggle-password').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const targetId = btn.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = btn.querySelector('i');
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.className = 'bi bi-eye-slash';
                    } else {
                        input.type = 'password';
                        icon.className = 'bi bi-eye';
                    }
                });
            });

            // Clear current-password invalid state as user types
            document.getElementById('current_password').addEventListener('input', function () {
                if (this.value.length > 0) this.classList.remove('is-invalid');
            });

            // Final submit validation
            form.addEventListener('submit', function (e) {
                checkConfirmMatch();
                if (!form.checkValidity() || confirmPassword.value !== newPassword.value) {
                    e.preventDefault();
                    e.stopPropagation();
                    const firstInvalid = form.querySelector(':invalid, .is-invalid');
                    if (firstInvalid) firstInvalid.focus();
                }
                form.classList.add('was-validated');
            }, false);
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
