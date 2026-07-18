<?php
include 'auth_check.php';

/*
 * ===========================================================================
 * NOTE: Replace with real DB lookups, e.g.:
 *
 * $stmt = $pdo->prepare("SELECT password_hash FROM admins WHERE id = ?");
 * $stmt->execute([$_SESSION['admin_id']]);
 * $currentHash = $stmt->fetchColumn();
 * ===========================================================================
 */
$currentHash = password_hash('Admin@123', PASSWORD_DEFAULT); // placeholder stand-in for the stored hash

$errors  = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword     = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Current password check
    if ($currentPassword === '') {
        $errors['current_password'] = 'Please enter your current password.';
    } elseif (!password_verify($currentPassword, $currentHash)) {
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
    } elseif ($currentPassword !== '' && password_verify($newPassword, $currentHash)) {
        $errors['new_password'] = 'New password must be different from the current password.';
    }

    // Confirm password check
    if ($confirmPassword === '') {
        $errors['confirm_password'] = 'Please confirm your new password.';
    } elseif ($newPassword !== $confirmPassword) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        // TODO: persist the new hashed password
        // $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        // $stmt = $pdo->prepare("UPDATE admins SET password_hash = ? WHERE id = ?");
        // $stmt->execute([$newHash, $_SESSION['admin_id']]);
        $success = true;
    }
}
?>
<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr" data-pc-theme="light">

<head>
    <title>Evets | Admin</title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
        name="description"
        content="Datta Able is trending dashboard template made using Bootstrap 5 design framework. Datta Able is available in Bootstrap, React, CodeIgniter, Angular,  and .net Technologies." />
    <meta
        name="keywords"
        content="Bootstrap admin template, Dashboard UI Kit, Dashboard Template, Backend Panel, react dashboard, angular dashboard" />
    <meta name="author" content="CodedThemes" />

    <!-- [Favicon] icon -->
    <link rel="icon" href="assets/images/favicon.svg" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- [Font] Family -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <!-- [phosphor Icons] https://phosphoricons.com/ -->
    <link rel="stylesheet" href="assets/fonts/phosphor/duotone/style.css" />
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="assets/fonts/tabler-icons.min.css" />
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="assets/fonts/feather.css" />
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="assets/fonts/fontawesome.css" />
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="assets/fonts/material.css" />
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="assets/css/style.css" id="main-style-link" />

</head>

<body>

    <!-- [ Pre-loader ] start -->
    <div class="loader-bg fixed inset-0 bg-white dark:bg-themedark-cardbg z-[1034]">
        <div class="loader-track h-[5px] w-full inline-block absolute overflow-hidden top-0">
            <div class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0 animate-[hitZak_0.6s_ease-in-out_infinite_alternate]"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->
    <!-- [ Sidebar Menu ] start -->
    <?php include_once("Sidebar.php"); ?>
    <!-- [ Sidebar Menu ] end -->
    <!-- [ Header Topbar ] start -->
    <?php include_once("Header.php"); ?>
    <!-- [ Header ] end -->



<!-- [ Main Content ] start -->
<div class="pc-container">
    <div class="pc-content">

        <!-- Page Header -->
        <div class="page-header">
            <div class="page-block">
                <div class="page-header-title">
                    <h5 class="mb-0 font-medium">Change Password</h5>
                </div>

                <ul class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="index.php">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        Admin Settings
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        Change Password
                    </li>
                </ul>
            </div>
        </div>

        <div class="row justify-content-center">

            <div class="col-lg-7">

                <?php if ($success): ?>
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="ti ti-circle-check me-2"></i>
                        Your password has been changed successfully.
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger" role="alert">
                        <strong>Please fix the following:</strong>
                        <ul class="mb-0">
                            <?php foreach ($errors as $err): ?>
                                <li><?php echo htmlspecialchars($err); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm border-0">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-lock"></i>
                            Update Your Password
                        </h5>
                    </div>

                    <div class="card-body">

                        <form action="" method="post" id="changePasswordForm" class="needs-validation" novalidate>

                            <!-- Current Password -->
                            <div class="mb-3">
                                <label class="form-label" for="current_password">
                                    Current Password <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">
                                    <input
                                        type="password"
                                        class="form-control <?php echo isset($errors['current_password']) ? 'is-invalid' : ''; ?>"
                                        name="current_password"
                                        id="current_password"
                                        required>
                                    <button class="btn btn-light toggle-password" type="button" data-target="current_password">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                    <div class="invalid-feedback">
                                        <?php echo isset($errors['current_password']) ? htmlspecialchars($errors['current_password']) : 'Please enter your current password.'; ?>
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
                                    <input
                                        type="password"
                                        class="form-control <?php echo isset($errors['new_password']) ? 'is-invalid' : ''; ?>"
                                        name="new_password"
                                        id="new_password"
                                        minlength="8"
                                        required>
                                    <button class="btn btn-light toggle-password" type="button" data-target="new_password">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                    <div class="invalid-feedback">
                                        <?php echo isset($errors['new_password']) ? htmlspecialchars($errors['new_password']) : 'Please enter a valid new password.'; ?>
                                    </div>
                                </div>

                                <!-- Strength meter -->
                                <div class="progress mt-2" style="height:6px;">
                                    <div id="strengthBar" class="progress-bar" role="progressbar" style="width:0%;"></div>
                                </div>
                                <small id="strengthLabel" class="text-muted">Password strength</small>

                                <!-- Live requirement checklist -->
                                <ul class="list-unstyled small mt-2 mb-0" id="passwordRules">
                                    <li id="rule-length" class="text-muted"><i class="ti ti-circle"></i> At least 8 characters</li>
                                    <li id="rule-upper" class="text-muted"><i class="ti ti-circle"></i> One uppercase letter</li>
                                    <li id="rule-lower" class="text-muted"><i class="ti ti-circle"></i> One lowercase letter</li>
                                    <li id="rule-number" class="text-muted"><i class="ti ti-circle"></i> One number</li>
                                    <li id="rule-special" class="text-muted"><i class="ti ti-circle"></i> One special character</li>
                                </ul>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label class="form-label" for="confirm_password">
                                    Confirm New Password <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">
                                    <input
                                        type="password"
                                        class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>"
                                        name="confirm_password"
                                        id="confirm_password"
                                        required>
                                    <button class="btn btn-light toggle-password" type="button" data-target="confirm_password">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                    <div class="invalid-feedback" id="confirmFeedback">
                                        <?php echo isset($errors['confirm_password']) ? htmlspecialchars($errors['confirm_password']) : 'Passwords do not match.'; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-light border d-flex align-items-start gap-2 mb-4">
                                <i class="ti ti-info-circle mt-1"></i>
                                <div class="small text-muted">
                                    Use a unique password you don't use elsewhere. You'll be asked to sign in again on other devices after changing it.
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="text-end">
                                <a href="profile.php" class="btn btn-light">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy"></i>
                                    Update Password
                                </button>
                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>
<!-- [ Main Content ] end -->
    <?php include_once("Footer.php"); ?>
    <!-- Required Js -->
    <script src="assets/js/plugins/simplebar.min.js"></script>
    <script src="assets/js/plugins/popper.min.js"></script>
    <script src="assets/js/icon/custom-icon.js"></script>
    <script src="assets/js/plugins/feather.min.js"></script>
    <script src="assets/js/component.js"></script>
    <script src="assets/js/theme.js"></script>
    <script src="assets/js/script.js"></script>

    <div class="floting-button fixed bottom-[50px] right-[30px] z-[1030]">
    </div>


    <script>
        layout_change('false');
    </script>


    <script>
        layout_theme_sidebar_change('dark');
    </script>


    <script>
        change_box_container('false');
    </script>

    <script>
        layout_caption_change('true');
    </script>

    <script>
        layout_rtl_change('false');
    </script>

    <script>
        preset_change('preset-1');
    </script>

    <script>
        main_layout_change('vertical');
    </script>

    <!-- ===================== Change Password: strength meter, live rules, validation ===================== -->
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
                length:  { test: v => v.length >= 8,        el: document.getElementById('rule-length') },
                upper:   { test: v => /[A-Z]/.test(v),       el: document.getElementById('rule-upper') },
                lower:   { test: v => /[a-z]/.test(v),       el: document.getElementById('rule-lower') },
                number:  { test: v => /[0-9]/.test(v),       el: document.getElementById('rule-number') },
                special: { test: v => /[^a-zA-Z0-9]/.test(v), el: document.getElementById('rule-special') }
            };

            function updateRules(value) {
                let passedCount = 0;

                Object.values(rules).forEach(function (rule) {
                    const icon = rule.el.querySelector('i');
                    if (rule.test(value)) {
                        rule.el.classList.remove('text-muted');
                        rule.el.classList.add('text-success');
                        icon.className = 'ti ti-circle-check';
                        passedCount++;
                    } else {
                        rule.el.classList.remove('text-success');
                        rule.el.classList.add('text-muted');
                        icon.className = 'ti ti-circle';
                    }
                });

                return passedCount;
            }

            function updateStrengthBar(passedCount) {
                const percent = (passedCount / 5) * 100;
                strengthBar.style.width = percent + '%';

                strengthBar.classList.remove('bg-danger', 'bg-warning', 'bg-success');

                let label = 'Very weak';
                if (passedCount <= 1) {
                    strengthBar.classList.add('bg-danger');
                    label = 'Very weak';
                } else if (passedCount <= 3) {
                    strengthBar.classList.add('bg-warning');
                    label = 'Moderate';
                } else if (passedCount === 4) {
                    strengthBar.classList.add('bg-warning');
                    label = 'Strong';
                } else {
                    strengthBar.classList.add('bg-success');
                    label = 'Very strong';
                }

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
                        icon.className = 'ti ti-eye-off';
                    } else {
                        input.type = 'password';
                        icon.className = 'ti ti-eye';
                    }
                });
            });

            // Clear current-password invalid state as user types
            document.getElementById('current_password').addEventListener('input', function () {
                if (this.value.length > 0) {
                    this.classList.remove('is-invalid');
                }
            });

            // Final submit validation
            form.addEventListener('submit', function (e) {
                checkConfirmMatch();

                if (!form.checkValidity() || confirmPassword.value !== newPassword.value) {
                    e.preventDefault();
                    e.stopPropagation();

                    const firstInvalid = form.querySelector(':invalid, .is-invalid');
                    if (firstInvalid) {
                        firstInvalid.focus();
                    }
                }
                form.classList.add('was-validated');
            }, false);
        })();
    </script>

</body>

</html>