<?php
include 'auth_check.php';

/*
 * ===========================================================================
 * NOTE: Replace this with a real DB fetch for the logged-in admin, e.g.:
 *
 * $stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
 * $stmt->execute([$_SESSION['admin_id']]);
 * $admin = $stmt->fetch(PDO::FETCH_ASSOC);
 * ===========================================================================
 */
$admin = [
    'id'          => 1,
    'name'        => 'Rahul Sharma',
    'email'       => 'rahul.sharma@evets.com',
    'phone'       => '+91 98765 43210',
    'role'        => 'Super Admin',
    'designation' => 'Event Coordinator',
    'department'  => 'Student Activities Cell',
    'bio'         => 'Managing college fest operations, registrations and event logistics for Evets.',
    'avatar'      => 'assets/images/user/avatar-1.jpg',
    'joined_on'   => '2024-03-12',
    'last_login'  => '2026-07-12 09:20:00',
];

$errors  = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {

    $name        = trim($_POST['name'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $phone       = trim($_POST['phone'] ?? '');
    $designation = trim($_POST['designation'] ?? '');
    $department  = trim($_POST['department'] ?? '');
    $bio         = trim($_POST['bio'] ?? '');

    if ($name === '') {
        $errors['name'] = 'Name is required.';
    }

    if ($email === '') {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if ($phone === '') {
        $errors['phone'] = 'Phone number is required.';
    } elseif (!preg_match('/^[0-9+\-\s()]{7,20}$/', $phone)) {
        $errors['phone'] = 'Please enter a valid phone number.';
    }

    if (strlen($bio) > 300) {
        $errors['bio'] = 'Bio must be under 300 characters.';
    }

    // Optional avatar upload handling
    if (!empty($_FILES['avatar']['name'])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize      = 2 * 1024 * 1024; // 2MB

        if (!in_array($_FILES['avatar']['type'], $allowedTypes, true)) {
            $errors['avatar'] = 'Only JPG, PNG or WEBP images are allowed.';
        } elseif ($_FILES['avatar']['size'] > $maxSize) {
            $errors['avatar'] = 'Image must be under 2MB.';
        }
        // TODO: move the uploaded file to a permanent path and save it against the admin record
    }

    if (empty($errors)) {
        // TODO: persist $name, $email, $phone, $designation, $department, $bio to DB
        $admin['name']        = $name;
        $admin['email']       = $email;
        $admin['phone']       = $phone;
        $admin['designation'] = $designation;
        $admin['department']  = $department;
        $admin['bio']         = $bio;
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
                    <h5 class="mb-0 font-medium">Profile</h5>
                </div>

                <ul class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="index.php">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        Admin Settings
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        Profile
                    </li>
                </ul>
            </div>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success d-flex align-items-center" role="alert">
                <i class="ti ti-circle-check me-2"></i>
                Profile updated successfully.
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

        <div class="row">

            <!-- Profile Summary Card -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0 text-center">
                    <div class="card-body">

                        <img
                            src="<?php echo htmlspecialchars($admin['avatar']); ?>"
                            alt="Admin Avatar"
                            class="rounded-circle mb-3"
                            style="width:120px; height:120px; object-fit:cover; border:3px solid var(--bs-primary);">

                        <h5 class="mb-0"><?php echo htmlspecialchars($admin['name']); ?></h5>
                        <p class="text-muted mb-2"><?php echo htmlspecialchars($admin['designation']); ?></p>

                        <span class="badge bg-light-primary text-primary mb-3">
                            <i class="ti ti-shield-check"></i>
                            <?php echo htmlspecialchars($admin['role']); ?>
                        </span>

                        <hr>

                        <ul class="list-unstyled text-start mb-0">
                            <li class="mb-2">
                                <i class="ti ti-mail text-muted me-2"></i>
                                <?php echo htmlspecialchars($admin['email']); ?>
                            </li>
                            <li class="mb-2">
                                <i class="ti ti-phone text-muted me-2"></i>
                                <?php echo htmlspecialchars($admin['phone']); ?>
                            </li>
                            <li class="mb-2">
                                <i class="ti ti-building text-muted me-2"></i>
                                <?php echo htmlspecialchars($admin['department']); ?>
                            </li>
                            <li class="mb-2">
                                <i class="ti ti-calendar text-muted me-2"></i>
                                Joined <?php echo date('d M Y', strtotime($admin['joined_on'])); ?>
                            </li>
                            <li>
                                <i class="ti ti-clock text-muted me-2"></i>
                                Last login <?php echo date('d M Y, h:i A', strtotime($admin['last_login'])); ?>
                            </li>
                        </ul>

                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <a href="change-password.php" class="btn btn-light w-100 mb-2 text-start">
                            <i class="ti ti-lock"></i>
                            Change Password
                        </a>
                        <a href="system-settings.php" class="btn btn-light w-100 text-start">
                            <i class="ti ti-settings"></i>
                            System Settings
                        </a>
                    </div>
                </div>
            </div>

            <!-- Edit Profile Form -->
            <div class="col-md-8">
                <div class="card shadow-sm border-0">

                    <div class="card-header">
                        <h5 class="mb-0">Edit Profile</h5>
                    </div>

                    <div class="card-body">

                        <form action="" method="post" id="profileForm" class="needs-validation" enctype="multipart/form-data" novalidate>

                            <!-- Avatar Upload -->
                            <div class="mb-4">
                                <label class="form-label" for="avatar">Profile Photo</label>

                                <div class="d-flex align-items-center gap-3">
                                    <img
                                        id="avatarPreview"
                                        src="<?php echo htmlspecialchars($admin['avatar']); ?>"
                                        alt="Preview"
                                        class="rounded-circle"
                                        style="width:64px; height:64px; object-fit:cover;">

                                    <input
                                        type="file"
                                        class="form-control <?php echo isset($errors['avatar']) ? 'is-invalid' : ''; ?>"
                                        name="avatar"
                                        id="avatar"
                                        accept="image/png, image/jpeg, image/webp">
                                </div>

                                <div class="form-text">JPG, PNG or WEBP. Max size 2MB.</div>

                                <div class="invalid-feedback d-block" style="<?php echo isset($errors['avatar']) ? '' : 'display:none;'; ?>">
                                    <?php echo isset($errors['avatar']) ? htmlspecialchars($errors['avatar']) : ''; ?>
                                </div>
                            </div>

                            <div class="row">

                                <!-- Name -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="name">
                                        Full Name <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>"
                                        name="name"
                                        id="name"
                                        value="<?php echo htmlspecialchars($admin['name']); ?>"
                                        required>
                                    <div class="invalid-feedback">
                                        <?php echo isset($errors['name']) ? htmlspecialchars($errors['name']) : 'Name is required.'; ?>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="email">
                                        Email Address <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="email"
                                        class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>"
                                        name="email"
                                        id="email"
                                        value="<?php echo htmlspecialchars($admin['email']); ?>"
                                        required>
                                    <div class="invalid-feedback">
                                        <?php echo isset($errors['email']) ? htmlspecialchars($errors['email']) : 'Please enter a valid email address.'; ?>
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="phone">
                                        Phone Number <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="tel"
                                        class="form-control <?php echo isset($errors['phone']) ? 'is-invalid' : ''; ?>"
                                        name="phone"
                                        id="phone"
                                        value="<?php echo htmlspecialchars($admin['phone']); ?>"
                                        placeholder="+91 98765 43210"
                                        required>
                                    <div class="invalid-feedback">
                                        <?php echo isset($errors['phone']) ? htmlspecialchars($errors['phone']) : 'Please enter a valid phone number.'; ?>
                                    </div>
                                </div>

                                <!-- Designation -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="designation">
                                        Designation
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="designation"
                                        id="designation"
                                        value="<?php echo htmlspecialchars($admin['designation']); ?>">
                                </div>

                                <!-- Department -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="department">
                                        Department
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="department"
                                        id="department"
                                        value="<?php echo htmlspecialchars($admin['department']); ?>">
                                </div>

                                <!-- Role (read-only) -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="role">
                                        Role
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="role"
                                        value="<?php echo htmlspecialchars($admin['role']); ?>"
                                        disabled
                                        readonly>
                                    <div class="form-text">Role is managed by System Settings.</div>
                                </div>

                                <!-- Bio -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="bio">
                                        Bio
                                    </label>
                                    <textarea
                                        class="form-control <?php echo isset($errors['bio']) ? 'is-invalid' : ''; ?>"
                                        name="bio"
                                        id="bio"
                                        rows="4"
                                        maxlength="300"
                                        placeholder="A short description about yourself..."><?php echo htmlspecialchars($admin['bio']); ?></textarea>
                                    <div class="invalid-feedback">
                                        <?php echo isset($errors['bio']) ? htmlspecialchars($errors['bio']) : 'Bio must be under 300 characters.'; ?>
                                    </div>
                                    <div class="form-text">
                                        <span id="bioCharCount">0</span>/300 characters
                                    </div>
                                </div>

                            </div>

                            <!-- Buttons -->
                            <div class="text-end">
                                <button type="reset" class="btn btn-light" id="resetProfileBtn">
                                    Cancel
                                </button>
                                <button type="submit" name="update_profile" class="btn btn-primary">
                                    <i class="ti ti-device-floppy"></i>
                                    Save Changes
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

    <!-- ===================== Profile Form: validation, avatar preview, bio counter ===================== -->
    <script>
        (function () {
            'use strict';

            const form           = document.getElementById('profileForm');
            const avatarInput    = document.getElementById('avatar');
            const avatarPreview  = document.getElementById('avatarPreview');
            const bio            = document.getElementById('bio');
            const bioCharCount   = document.getElementById('bioCharCount');
            const resetBtn       = document.getElementById('resetProfileBtn');
            const originalAvatar = avatarPreview.src;

            // Live bio character counter
            function updateBioCount() {
                bioCharCount.textContent = bio.value.length;
            }
            bio.addEventListener('input', updateBioCount);
            updateBioCount();

            // Live avatar preview when a new photo is chosen
            avatarInput.addEventListener('change', function () {
                const file = this.files && this.files[0];
                if (!file) return;

                const allowed = ['image/jpeg', 'image/png', 'image/webp'];
                if (!allowed.includes(file.type)) {
                    avatarInput.classList.add('is-invalid');
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    avatarInput.classList.add('is-invalid');
                    return;
                }

                avatarInput.classList.remove('is-invalid');
                const reader = new FileReader();
                reader.onload = function (e) {
                    avatarPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            });

            // Clear invalid state as fields become valid
            form.querySelectorAll('.form-control').forEach(function (field) {
                field.addEventListener('input', function () {
                    if (field.checkValidity()) {
                        field.classList.remove('is-invalid');
                    }
                });
            });

            // Bootstrap-style validation on submit
            form.addEventListener('submit', function (e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();

                    const firstInvalid = form.querySelector(':invalid');
                    if (firstInvalid) {
                        firstInvalid.classList.add('is-invalid');
                        firstInvalid.focus();
                    }
                }
                form.classList.add('was-validated');
            }, false);

            // Reset avatar preview and validation state on cancel
            resetBtn.addEventListener('click', function () {
                setTimeout(function () {
                    form.classList.remove('was-validated');
                    form.querySelectorAll('.is-invalid').forEach(function (el) {
                        el.classList.remove('is-invalid');
                    });
                    avatarPreview.src = originalAvatar;
                    updateBioCount();
                }, 0);
            });
        })();
    </script>

</body>

</html>