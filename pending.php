<?php include 'auth_check.php'; ?>
<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr" data-pc-theme="light">

<head>
    <title>Admin | Pending Verification</title>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="icon" href="assets/images/favicon.svg" type="image/x-icon" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="assets/fonts/phosphor/duotone/style.css">
    <link rel="stylesheet" href="assets/fonts/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/fonts/feather.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="assets/fonts/material.css">

    <!-- Theme -->
    <link rel="stylesheet" href="assets/css/style.css" id="main-style-link">

    <style>
        .doc-img {
            width: 90px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #ddd;
        }

        .profile-img {
            width: 55px;
            height: 55px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>

<body>

<div class="loader-bg fixed inset-0 bg-white dark:bg-themedark-cardbg z-[1034]">
    <div class="loader-track h-[5px] w-full inline-block absolute overflow-hidden top-0">
        <div class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0 animate-[hitZak_0.6s_ease-in-out_infinite_alternate]"></div>
    </div>
</div>

<?php include_once("Sidebar.php"); ?>
<?php include_once("Header.php"); ?>

<div class="pc-container">
<div class="pc-content">

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-block">
            <div class="page-header-title">
                <h5 class="mb-0">Pending Verification</h5>
            </div>

            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="Index.php">Home</a></li>
                <li class="breadcrumb-item">Student Management</li>
                <li class="breadcrumb-item">Pending Verification</li>
            </ul>
        </div>
    </div>

    <!-- Summary -->
    <div class="row mb-3">

        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h2 class="text-warning">15</h2>
                    <h6 class="mb-0">Pending Requests</h6>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-5">
                            <input type="text" class="form-control"
                                   placeholder="Search Student...">
                        </div>

                        <div class="col-md-3">
                            <select class="form-select">
                                <option>All Colleges</option>
                                <option>ABC College</option>
                                <option>XYZ College</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select class="form-select">
                                <option>Semester</option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">
                                Search
                            </button>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- Table -->

    <div class="card">

        <div class="card-header">
            <h5>Students Awaiting Verification</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                    <tr>

                        <th>#</th>

                        <th>ID Card</th>

                        <th>Photo</th>

                        <th>Student</th>

                        <th>College</th>

                        <th>Semester</th>

                        <th>Submitted</th>

                        <th>Status</th>

                        <th width="220">Action</th>

                    </tr>

                    </thead>

                    <tbody>

                        <!-- Dummy Row -->

                        <tr>

                            <td>1</td>

                            <td>
                                <img src="assets/images/idcard.jpg"
                                     class="doc-img">
                            </td>

                            <td>
                                <img src="assets/images/user/avatar-1.jpg"
                                     class="profile-img">
                            </td>

                            <td>
                                <strong>Rahul Patel</strong><br>
                                EN2025001<br>
                                rahul@gmail.com
                            </td>

                            <td>ABC Engineering College</td>

                            <td>Semester 5</td>

                            <td>
                                05 Jul 2026
                            </td>

                            <td>
                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>
                            </td>

                            <td>

                                <button class="btn btn-info btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewModal">
                                    <i class="ti ti-eye"></i>
                                </button>

                                <button class="btn btn-success btn-sm">
                                    <i class="ti ti-check"></i>
                                    Verify
                                </button>

                                <button class="btn btn-danger btn-sm">
                                    <i class="ti ti-x"></i>
                                    Reject
                                </button>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>
</div>

<!-- View Modal -->

<div class="modal fade" id="viewModal">

<div class="modal-dialog modal-lg">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title">
Student Verification
</h5>

<button class="btn-close"
        data-bs-dismiss="modal"></button>

</div>

<div class="modal-body">

<div class="row">

<div class="col-md-4 text-center">

<img src="assets/images/user/avatar-1.jpg"
     class="img-fluid rounded mb-3">

<h5>Rahul Patel</h5>

<p>Enrollment : EN2025001</p>

</div>

<div class="col-md-8">

<table class="table table-bordered">

<tr>
<th>Email</th>
<td>rahul@gmail.com</td>
</tr>

<tr>
<th>Phone</th>
<td>9876543210</td>
</tr>

<tr>
<th>College</th>
<td>ABC Engineering College</td>
</tr>

<tr>
<th>Semester</th>
<td>Semester 5</td>
</tr>

<tr>
<th>Gender</th>
<td>Male</td>
</tr>

</table>

<h6>ID Card</h6>

<img src="assets/images/idcard.jpg"
     class="img-fluid rounded border">

</div>

</div>

</div>

<div class="modal-footer">

<button class="btn btn-success">
<i class="ti ti-check"></i>
Verify
</button>

<button class="btn btn-danger">
<i class="ti ti-x"></i>
Reject
</button>

<button class="btn btn-secondary"
        data-bs-dismiss="modal">
Close
</button>

</div>

</div>

</div>

</div>

<?php include_once("Footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
