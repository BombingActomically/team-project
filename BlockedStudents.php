<?php include 'auth_check.php'; ?>
<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr" data-pc-theme="light">

<head>
    <title>Admin | All Students</title>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- Favicon -->
    <link rel="icon" href="assets/images/favicon.svg" type="image/x-icon" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="assets/fonts/phosphor/duotone/style.css" />
    <link rel="stylesheet" href="assets/fonts/tabler-icons.min.css" />
    <link rel="stylesheet" href="assets/fonts/feather.css" />
    <link rel="stylesheet" href="assets/fonts/fontawesome.css" />
    <link rel="stylesheet" href="assets/fonts/material.css" />

    <!-- Template CSS -->
    <link rel="stylesheet" href="assets/css/style.css" id="main-style-link" />
</head>

<body>

    <!-- Loader -->
    <div class="loader-bg fixed inset-0 bg-white dark:bg-themedark-cardbg z-[1034]">
        <div class="loader-track h-[5px] w-full inline-block absolute overflow-hidden top-0">
            <div class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0 animate-[hitZak_0.6s_ease-in-out_infinite_alternate]"></div>
        </div>
    </div>

    <!-- Sidebar -->
    <?php include_once("Sidebar.php"); ?>

    <!-- Header -->
    <?php include_once("Header.php"); ?>

    <div class="pc-container">
        <div class="pc-content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="page-block">
                    <div class="page-header-title">
                        <h5 class="mb-0 font-medium">All Students</h5>
                    </div>

                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="Index.php">Home</a></li>
                        <li class="breadcrumb-item">Student Management</li>
                        <li class="breadcrumb-item" aria-current="page">All Students</li>
                    </ul>
                </div>
            </div>

            <!-- Card -->
            <div class="card">

                <div class="card-header">
                    <div class="row align-items-center">

                        <div class="col-md-4">
                            <h5 class="mb-0">Student List</h5>
                        </div>

                        <div class="col-md-4">
                            <input type="text"
                                class="form-control"
                                id="searchStudent"
                                placeholder="Search by Name, Enrollment or Email">
                        </div>

                        <div class="col-md-2">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option>Pending</option>
                                <option>Verified</option>
                                <option>Rejected</option>
                                <option>Active</option>
                                <option>Inactive</option>
                                <option>Blocked</option>
                            </select>
                        </div>

                        <div class="col-md-2 text-end">
                            <a href="AddStudent.php" class="btn btn-primary">
                                <i class="ti ti-plus"></i> Add Student
                            </a>
                        </div>

                    </div>
                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-hover table-bordered align-middle">

                            <thead class="table-light">
                                <tr>

                                    <th>#</th>

                                    <th>Photo</th>

                                    <th>Enrollment No</th>

                                    <th>Name</th>

                                    <th>Email</th>

                                    <th>Phone</th>

                                    <th>Semester</th>

                                    <th>Status</th>

                                    <th width="180">Action</th>

                                </tr>
                            </thead>

                            <tbody>

                                <!-- BLOCKED STUDENT 1 -->
                                <tr class="table-danger">
                                    <td>1</td>
                                    <td>
                                        <img src="assets/images/user/avatar-1.jpg" width="45" class="rounded-circle">
                                    </td>
                                    <td>EN2025007</td>
                                    <td>Karan Singh</td>
                                    <td>karan@gmail.com</td>
                                    <td>9876501234</td>
                                    <td>Semester 5</td>
                                    <td>
                                        <span class="badge bg-danger">Blocked</span>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-info">
                                            <i class="ti ti-eye"></i>
                                        </a>

                                        <!-- Disabled Edit -->
                                        <a href="#" class="btn btn-sm btn-secondary disabled">
                                            <i class="ti ti-edit"></i>
                                        </a>

                                        <!-- Unblock -->
                                        <a href="#" class="btn btn-sm btn-success">
                                            <i class="ti ti-lock-open"></i>
                                        </a>
                                    </td>
                                </tr>

                                <!-- BLOCKED STUDENT 2 -->
                                <tr class="table-danger">
                                    <td>2</td>
                                    <td>
                                        <img src="assets/images/user/avatar-2.jpg" width="45" class="rounded-circle">
                                    </td>
                                    <td>EN2025008</td>
                                    <td>Anjali Desai</td>
                                    <td>anjali@gmail.com</td>
                                    <td>9988771122</td>
                                    <td>Semester 4</td>
                                    <td>
                                        <span class="badge bg-danger">Blocked</span>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-info">
                                            <i class="ti ti-eye"></i>
                                        </a>

                                        <a href="#" class="btn btn-sm btn-secondary disabled">
                                            <i class="ti ti-edit"></i>
                                        </a>

                                        <a href="#" class="btn btn-sm btn-success">
                                            <i class="ti ti-lock-open"></i>
                                        </a>
                                    </td>
                                </tr>

                                <!-- BLOCKED STUDENT 3 -->
                                <tr class="table-danger">
                                    <td>3</td>
                                    <td>
                                        <img src="assets/images/user/avatar-3.jpg" width="45" class="rounded-circle">
                                    </td>
                                    <td>EN2025009</td>
                                    <td>Ritesh Yadav</td>
                                    <td>ritesh@gmail.com</td>
                                    <td>9123458899</td>
                                    <td>Semester 6</td>
                                    <td>
                                        <span class="badge bg-danger">Blocked</span>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-info">
                                            <i class="ti ti-eye"></i>
                                        </a>

                                        <a href="#" class="btn btn-sm btn-secondary disabled">
                                            <i class="ti ti-edit"></i>
                                        </a>

                                        <a href="#" class="btn btn-sm btn-success">
                                            <i class="ti ti-lock-open"></i>
                                        </a>
                                    </td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>
    </div>

    <!-- Footer -->
    <?php include_once("Footer.php"); ?>

    <!-- Required JS -->
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
    </script>

</body>

</html>
