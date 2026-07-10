<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr" data-pc-theme="light">

<head>
    <title>University | Admin</title>
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
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="page-header-title">
                        <h5 class="mb-0 font-medium">All University</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="Index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0)">University Management</a></li>
                        <li class="breadcrumb-item" aria-current="page">All University</li>
                    </ul>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <!-- [ Main Content ] start -->
            <div class="row">

                <div class="col-12">
                    <div class="card shadow-sm border-0">

                        <!-- Header -->
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">University List</h5>
                            <a href="bc_typography.php" class="btn btn-primary btn-sm mt-3">
                                <i class="ti ti-plus"></i> Add University
                            </a>
                        </div>

                        <!-- Table -->
                        <div class="card-body p-0">
                            <div class="table-responsive">

                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Logo</th>
                                            <th>Name</th>
                                            <th>Short Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <img src="assets/images/universities/u1.png" width="40" class="rounded">
                                            </td>
                                            <td><strong>Harvard University</strong></td>
                                            <td>HU</td>
                                            <td>info@harvard.edu</td>
                                            <td>+1 123 456 7890</td>
                                            <td><span class="badge bg-success">Active</span></td>
                                            <td>01 Jan 2024</td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-warning"><i class="ti ti-edit"></i></button>
                                                <button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>2</td>
                                            <td>
                                                <img src="assets/images/universities/u2.png" width="40" class="rounded">
                                            </td>
                                            <td><strong>Oxford University</strong></td>
                                            <td>OU</td>
                                            <td>contact@ox.ac.uk</td>
                                            <td>+44 987 654 321</td>
                                            <td><span class="badge bg-success">Active</span></td>
                                            <td>15 Feb 2024</td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-warning"><i class="ti ti-edit"></i></button>
                                                <button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>3</td>
                                            <td>
                                                <img src="assets/images/universities/u3.png" width="40" class="rounded">
                                            </td>
                                            <td><strong>Stanford University</strong></td>
                                            <td>SU</td>
                                            <td>hello@stanford.edu</td>
                                            <td>+1 555 888 222</td>
                                            <td><span class="badge bg-danger">Inactive</span></td>
                                            <td>10 Mar 2024</td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-warning"><i class="ti ti-edit"></i></button>
                                                <button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>4</td>
                                            <td>
                                                <img src="assets/images/universities/u4.png" width="40" class="rounded">
                                            </td>
                                            <td><strong>MIT</strong></td>
                                            <td>MIT</td>
                                            <td>info@mit.edu</td>
                                            <td>+1 777 999 000</td>
                                            <td><span class="badge bg-success">Active</span></td>
                                            <td>20 Apr 2024</td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-warning"><i class="ti ti-edit"></i></button>
                                                <button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>5</td>
                                            <td>
                                                <img src="assets/images/universities/u5.png" width="40" class="rounded">
                                            </td>
                                            <td><strong>Cambridge University</strong></td>
                                            <td>CU</td>
                                            <td>admin@cam.ac.uk</td>
                                            <td>+44 222 333 444</td>
                                            <td><span class="badge bg-success">Active</span></td>
                                            <td>05 May 2024</td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-warning"><i class="ti ti-edit"></i></button>
                                                <button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button>
                                            </td>
                                        </tr>

                                    </tbody>

                                </table>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <!-- [ Main Content ] end -->
            <!-- [ Main Content ] end -->
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



</body>

</html>