<?php 
include 'auth_check.php'; 
// include 'db_connection.php'; // Uncomment to integrate with database

$message = '';
$messageType = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize inputs
    $enrollment_no = isset($_POST['enrollment_no']) ? trim($_POST['enrollment_no']) : '';
    $name          = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email         = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone         = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $semester      = isset($_POST['semester']) ? intval($_POST['semester']) : 1;
    $status        = isset($_POST['status']) ? 'active' : 'inactive';
    $avatar_path   = 'assets/images/user/avatar-2.jpg'; // Default avatar

    // Handle File Upload safely
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['avatar']['tmp_name'];
        $fileName      = $_FILES['avatar']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = 'avatar_' . time() . '_' . uniqid() . '.' . $fileExtension;
            $uploadFileDir = 'uploads/avatars/';
            
            // Create folder if it doesn't exist
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            
            $dest_path = $uploadFileDir . $newFileName;
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $avatar_path = $dest_path;
            }
        } else {
            $message = 'Invalid file format. Only JPG, PNG, and WebP are allowed.';
            $messageType = 'danger';
        }
    }

    // Database insertion routine (If DB config is ready)
    if (empty($message) && isset($pdo)) {
        try {
            $sql = "INSERT INTO students (enrollment_no, name, email, phone, semester, status, avatar) 
                    VALUES (:enrollment_no, :name, :email, :phone, :semester, :status, :avatar)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':enrollment_no' => $enrollment_no,
                ':name'          => $name,
                ':email'         => $email,
                ':phone'         => $phone,
                ':semester'      => $semester,
                ':status'        => $status,
                ':avatar'        => $avatar_path
            ]);
            $message = 'Student profile successfully created!';
            $messageType = 'success';
        } catch (PDOException $e) {
            $message = 'Database Error: ' . $e->getMessage();
            $messageType = 'danger';
        }
    }
}
?>
<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr" data-pc-theme="light">

<head>
    <title>Add Student | Admin</title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- [Favicon] icon -->
    <link rel="icon" href="assets/images/favicon.svg" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- [Font] Family -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,500;6..72,600;6..72,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <!-- [phosphor Icons] -->
    <link rel="stylesheet" href="assets/fonts/phosphor/duotone/style.css" />
    <!-- [Tabler Icons] -->
    <link rel="stylesheet" href="assets/fonts/tabler-icons.min.css" />
    <!-- [Feather Icons] -->
    <link rel="stylesheet" href="assets/fonts/feather.css" />
    <!-- [Font Awesome Icons] -->
    <link rel="stylesheet" href="assets/fonts/fontawesome.css" />
    <!-- [Material Icons] -->
    <link rel="stylesheet" href="assets/fonts/material.css" />
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="assets/css/style.css" id="main-style-link" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* ===== University Ledger theme — tokens ===== */
        .uni-page {
            --ink: #16233F;
            --ink-2: #2C3E63;
            --brass: #A9822E;
            --brass-light: #E7CE8C;
            --bg: #F2F4F8;
            --card: #FFFFFF;
            --success: #1F8A57;
            --success-bg: #E7F5EE;
            --danger: #C6414B;
            --danger-bg: #FBEAEC;
            --warning: #B8860B;
            --warning-bg: #FBF3E0;
            --border: #E5E8EF;
            --muted: #6B7280;

            background: var(--bg);
            padding-bottom: 30px;
        }

        .uni-page,
        .uni-page .form-control,
        .uni-page .form-select,
        .uni-page .btn {
            font-family: 'Inter', -apple-system, sans-serif;
        }

        .uni-page h1,
        .uni-page h2,
        .uni-page h5,
        .uni-page .uni-serif {
            font-family: 'Newsreader', Georgia, serif;
        }

        /* ---- Header ---- */
        .uni-title {
            font-weight: 600;
            font-size: 30px;
            color: var(--ink);
            margin-bottom: 2px;
            letter-spacing: -0.01em;
        }

        .uni-breadcrumb {
            list-style: none;
            display: flex;
            gap: 6px;
            padding: 0;
            margin: 6px 0 0;
            font-size: 13px;
            color: var(--muted);
        }

        .uni-breadcrumb li+li::before {
            content: "/";
            margin-right: 6px;
            color: #C7CCD6;
        }

        .uni-breadcrumb li {
            display: flex;
            gap: 6px;
        }

        .uni-breadcrumb a {
            color: var(--muted);
            text-decoration: none;
        }

        .uni-breadcrumb a:hover {
            color: var(--ink);
        }

        /* ---- Main Card ---- */
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
            font-weight: 600;
            font-size: 19px;
            color: var(--ink);
            margin: 0;
        }

        .uni-card-sub {
            font-size: 12.5px;
            color: var(--muted);
            margin-top: 2px;
        }

        /* ---- Forms Styling ---- */
        .uni-form-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--ink-2);
            margin-bottom: 6px;
        }

        .uni-page .form-control,
        .uni-page .form-select {
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            padding: 10px 14px;
            color: var(--ink-2);
            background-color: #FAFAFC;
            transition: all 0.15s ease;
        }

        .uni-page .form-control:focus,
        .uni-page .form-select:focus {
            background-color: #fff;
            border-color: var(--brass);
            box-shadow: 0 0 0 3px rgba(169, 130, 46, 0.12);
        }

        /* Avatar Upload Container */
        .avatar-upload-box {
            border: 2px dashed var(--border);
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            background: #FAFAFC;
            cursor: pointer;
            transition: all .15s ease;
        }

        .avatar-upload-box:hover {
            border-color: var(--brass);
            background: #FBF6EA;
        }

        .preview-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 4px 12px rgba(22, 35, 63, 0.1);
            margin-bottom: 12px;
        }

        /* Action Buttons */
        .uni-btn-primary {
            background: var(--ink);
            border: 1px solid var(--ink);
            color: #fff;
            font-size: 14px;
            font-weight: 500;
            padding: 10px 24px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background .15s ease;
        }

        .uni-btn-primary:hover {
            background: var(--ink-2);
            color: #fff;
        }

        .uni-btn-secondary {
            background: #fff;
            border: 1px solid var(--border);
            color: var(--ink-2);
            font-size: 14px;
            font-weight: 500;
            padding: 10px 24px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all .15s ease;
        }

        .uni-btn-secondary:hover {
            background: var(--bg);
            border-color: #DADFE7;
            color: var(--ink);
        }

        /* toggle switch styling */
        .uni-page .form-switch .form-check-input {
            width: 44px;
            height: 22px;
            cursor: pointer;
            background-color: #DADFE7;
            border-color: #DADFE7;
        }

        .uni-page .form-switch .form-check-input:checked {
            background-color: var(--success);
            border-color: var(--success);
        }

        .uni-page .form-switch .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(31, 138, 87, 0.15);
        }
    </style>
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
        <div class="pc-content uni-page">

            <!-- [ breadcrumb ] start -->
            <div class="page-header uni-header mb-4">
                <div class="page-block">
                    <h1 class="uni-title">Add Student Profile</h1>
                    <ul class="uni-breadcrumb">
                        <li><a href="Index.php">Home</a></li>
                        <li><a href="javascript: void(0)">Student Management</a></li>
                        <li><a href="allstudents.php">All Students</a></li>
                        <li style="color: var(--ink);">Add Student</li>
                    </ul>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Form Content Area ] start -->
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="uni-card">
                        
                        <div class="uni-card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="uni-card-title">Enroll New Student</h5>
                                <div class="uni-card-sub">Establish a new academic record in the ledger directory.</div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <?php if (!empty($message)): ?>
                                <div class="alert alert-<?= $messageType; ?> alert-dismissible fade show mb-4" role="alert">
                                    <strong><?= $messageType === 'success' ? 'Success!' : 'Error!'; ?></strong> <?= htmlspecialchars($message); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="" enctype="multipart/form-data">
                                <div class="row g-4">
                                    
                                    <!-- Avatar Upload / Side Column -->
                                    <div class="col-md-4 order-md-2">
                                        <div class="d-flex flex-column align-items-center">
                                            <label class="uni-form-label text-center d-block mb-3">Student Portrait</label>
                                            
                                            <div class="avatar-upload-box w-100" id="uploadBox">
                                                <img src="assets/images/user/avatar-2.jpg" id="avatarPreview" class="preview-avatar" alt="Default Avatar">
                                                <p class="mb-0 text-muted" style="font-size: 11.5px;">Click to upload image file</p>
                                                <span class="text-xs text-muted d-block mt-1" style="font-size: 10px;">JPG, PNG or WebP</span>
                                                <input type="file" name="avatar" id="avatarInput" class="d-none" accept="image/png, image/jpeg, image/webp">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Main Input Column -->
                                    <div class="col-md-8 order-md-1">
                                        <div class="row g-3">
                                            
                                            <!-- Enrollment ID -->
                                            <div class="col-sm-6">
                                                <label class="uni-form-label" for="enrollment_no">Enrollment Number</label>
                                                <input type="text" name="enrollment_no" id="enrollment_no" class="form-control" placeholder="e.g., EN20264023" required>
                                            </div>

                                            <!-- Full Name -->
                                            <div class="col-sm-6">
                                                <label class="uni-form-label" for="name">Full Name</label>
                                                <input type="text" name="name" id="name" class="form-control" placeholder="e.g., Evelyn Vance" required>
                                            </div>

                                            <!-- Email -->
                                            <div class="col-sm-6">
                                                <label class="uni-form-label" for="email">Email Address</label>
                                                <input type="email" name="email" id="email" class="form-control" placeholder="name@university.edu" required>
                                            </div>

                                            <!-- Phone -->
                                            <div class="col-sm-6">
                                                <label class="uni-form-label" for="phone">Phone Number</label>
                                                <input type="tel" name="phone" id="phone" class="form-control" placeholder="+1 (555) 019-2834">
                                            </div>

                                            <!-- Semester -->
                                            <div class="col-sm-6">
                                                <label class="uni-form-label" for="semester">Current Semester</label>
                                                <select name="semester" id="semester" class="form-select">
                                                    <option value="1">1st Semester</option>
                                                    <option value="2">2nd Semester</option>
                                                    <option value="3">3rd Semester</option>
                                                    <option value="4">4th Semester</option>
                                                    <option value="5">5th Semester</option>
                                                    <option value="6">6th Semester</option>
                                                    <option value="7">7th Semester</option>
                                                    <option value="8">8th Semester</option>
                                                </select>
                                            </div>

                                            <!-- Status Switcher -->
                                            <div class="col-sm-6 d-flex align-items-center">
                                                <div>
                                                    <label class="uni-form-label d-block" for="status">Initial Enrollment Status</label>
                                                    <div class="form-check form-switch mt-2">
                                                        <input class="form-check-input" type="checkbox" name="status" id="status" checked>
                                                        <label class="form-check-label ms-2 text-muted" style="font-size: 13.5px;" for="status">Mark student as Active</label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>

                                <!-- Action Footer -->
                                <hr class="my-4" style="border-color: var(--border);">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="allstudents.php" class="uni-btn-secondary">
                                        <i class="ti ti-arrow-left"></i> Back to Ledger
                                    </a>
                                    <button type="submit" class="uni-btn-primary">
                                        <i class="ti ti-check"></i> Register Student
                                    </button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <!-- [ Form Content Area ] end -->

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

    <script>
        layout_change('false');
        layout_theme_sidebar_change('dark');
        change_box_container('false');
        layout_caption_change('true');
        layout_rtl_change('false');
        preset_change('preset-1');
        main_layout_change('vertical');

        // Dynamic Profile Image Preview Script
        const uploadBox = document.getElementById('uploadBox');
        const avatarInput = document.getElementById('avatarInput');
        const avatarPreview = document.getElementById('avatarPreview');

        uploadBox.addEventListener('click', () => {
            avatarInput.click();
        });

        avatarInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>