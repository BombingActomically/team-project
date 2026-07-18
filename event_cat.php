<?php include 'auth_check.php'; ?>

<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr"
    data-pc-theme="light">

<head>

    <title>Evenza | Event Categories</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Evenza - Multi College Event Registration Portal">

    <meta name="keywords" content="Evenza, event management, college events, university events">

    <meta name="author" content="Evenza">

    <!-- Favicon -->
    <link rel="icon" href="assets/images/favicon.svg" type="image/x-icon">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Existing Template CSS -->
    <link rel="stylesheet" href="assets/css/style.css" id="main-style-link">


    <style>
        /* Remove all Bootstrap breadcrumb separators */
        .breadcrumb-item::before,
        .breadcrumb-item::after {
            content: none !important;
            display: none !important;
        }

        /* Display breadcrumb items horizontally */
        .breadcrumb {
            display: flex !important;
            align-items: center;
            gap: 25px;
        }
    </style>
</head>

<body>


    <!-- Preloader -->
    <div class="loader-bg fixed inset-0 bg-white dark:bg-themedark-cardbg z-[1034]">

        <div class="loader-track h-[5px] w-full inline-block absolute overflow-hidden top-0">

            <div class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0
                animate-[hitZak_0.6s_ease-in-out_infinite_alternate]">

            </div>

        </div>

    </div>


    <!-- Sidebar -->
    <?php include_once("Sidebar.php"); ?>


    <!-- Header -->
    <?php include_once("Header.php"); ?>


    <!-- Main Content -->
    <div class="pc-container">

        <div class="pc-content">


            <!-- Page Header -->
            <div class="page-header mb-4">

                <div class="page-block">

                    <div class="page-header-title">

                        <h5 class="mb-1">
                            Event Categories
                        </h5>

                        <p class="text-muted mb-0">
                            Manage and organize categories for Evenza events
                        </p>

                    </div>

                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" class="mt-3">

                        <ol class="breadcrumb mb-0">

                            <li>
                                <a href="Dashboard.php" class="text-decoration-none">
                                    Home
                                </a>
                            </li>

                            <li>
                                /
                            </li>

                            <li>
                                <a href="event_cat.php" class="text-decoration-none">
                                    Category Management
                                </a>
                            </li>

                            <li>
                                /
                            </li>

                            <li aria-current="page">
                                All Categories
                            </li>

                        </ol>

                    </nav>


                </div>

            </div>


            <!-- Statistics Cards -->
            <div class="row g-3 mb-4">


                <!-- Total Categories -->
                <div class="col-xl-4 col-md-6">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <p class="text-muted mb-1">
                                        Total Categories
                                    </p>

                                    <h3 class="mb-0 fw-bold">
                                        03
                                    </h3>

                                </div>

                                <div class="bg-primary-subtle text-primary rounded-3 p-3">

                                    <i class="bi bi-tags fs-3"></i>

                                </div>

                            </div>

                            <small class="text-muted">

                                <i class="bi bi-bar-chart me-1"></i>
                                All event categories

                            </small>

                        </div>

                    </div>

                </div>


                <!-- Active Categories -->
                <div class="col-xl-4 col-md-6">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <p class="text-muted mb-1">
                                        Active Categories
                                    </p>

                                    <h3 class="mb-0 fw-bold text-success">
                                        02
                                    </h3>

                                </div>

                                <div class="bg-success-subtle text-success rounded-3 p-3">

                                    <i class="bi bi-check-circle fs-3"></i>

                                </div>

                            </div>

                            <small class="text-muted">

                                <i class="bi bi-check2 me-1"></i>
                                Currently available

                            </small>

                        </div>

                    </div>

                </div>


                <!-- Inactive Categories -->
                <div class="col-xl-4 col-md-6">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <p class="text-muted mb-1">
                                        Inactive Categories
                                    </p>

                                    <h3 class="mb-0 fw-bold text-danger">
                                        01
                                    </h3>

                                </div>

                                <div class="bg-danger-subtle text-danger rounded-3 p-3">

                                    <i class="bi bi-x-circle fs-3"></i>

                                </div>

                            </div>

                            <small class="text-muted">

                                <i class="bi bi-eye-slash me-1"></i>
                                Currently disabled

                            </small>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Category Table Card -->
            <div class="card border-0 shadow-sm">


                <!-- Card Header -->
                <div class="card-header bg-white border-0 py-4">

                    <div class="row align-items-center g-3">


                        <!-- Title -->
                        <div class="col-lg-5">

                            <div class="d-flex align-items-center">

                                <div class="bg-primary text-white rounded-3 p-2 me-3">

                                    <i class="bi bi-grid-3x3-gap fs-4"></i>

                                </div>

                                <div>

                                    <h5 class="mb-1">
                                        Category List
                                    </h5>

                                    <small class="text-muted">
                                        View and manage all categories
                                    </small>

                                </div>

                            </div>

                        </div>


                        <!-- Search -->
                        <div class="col-lg-4">

                            <div class="input-group">

                                <span class="input-group-text bg-light border-end-0">

                                    <i class="bi bi-search text-muted"></i>

                                </span>

                                <input type="text" id="categorySearch" class="form-control bg-light border-start-0"
                                    placeholder="Search categories...">

                            </div>

                        </div>


                        <!-- Filter and Add -->
                        <div class="col-lg-3">

                            <div class="d-flex gap-2">

                                <select id="statusFilter" class="form-select">

                                    <option value="all">
                                        All Status
                                    </option>

                                    <option value="active">
                                        Active
                                    </option>

                                    <option value="inactive">
                                        Inactive
                                    </option>

                                </select>


                                <a href="add_category.php" class="btn btn-primary text-nowrap">

                                    <i class="bi bi-plus-lg me-1"></i>

                                    Add

                                </a>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- Table -->
                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th class="px-4">
                                        #
                                    </th>

                                    <th>
                                        Category
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Created
                                    </th>

                                    <th class="text-end px-4">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <tbody id="categoryTable">


                                <!-- Technical Events -->
                                <tr data-status="active">

                                    <td class="px-4">

                                        <span class="text-muted">
                                            01
                                        </span>

                                    </td>

                                    <td>

                                        <div class="d-flex align-items-center">

                                            <div class="bg-primary-subtle text-primary rounded-3 p-2 me-3">

                                                <i class="bi bi-code-slash fs-5"></i>

                                            </div>

                                            <div>

                                                <div class="fw-semibold">
                                                    Technical Events
                                                </div>

                                                <small class="text-muted">
                                                    Technology and coding competitions
                                                </small>

                                            </div>

                                        </div>

                                    </td>

                                    <td>

                                        <span class="badge text-bg-success">

                                            <i class="bi bi-check-circle me-1"></i>

                                            Active

                                        </span>

                                    </td>

                                    <td>

                                        <small class="text-muted">
                                            15 July 2026
                                        </small>

                                    </td>

                                    <td class="text-end px-4">

                                        <a href="edit_category.php?id=1" class="btn btn-sm btn-outline-warning me-1"
                                            title="Edit Category">

                                            <i class="bi bi-pencil"></i>

                                        </a>


                                        <button class="btn btn-sm btn-outline-danger"
                                            onclick="openDeleteModal(1, 'Technical Events')" title="Delete Category">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </td>

                                </tr>


                                <!-- Cultural Events -->
                                <tr data-status="active">

                                    <td class="px-4">

                                        <span class="text-muted">
                                            02
                                        </span>

                                    </td>

                                    <td>

                                        <div class="d-flex align-items-center">

                                            <div class="bg-danger-subtle text-danger rounded-3 p-2 me-3">

                                                <i class="bi bi-music-note-beamed fs-5"></i>

                                            </div>

                                            <div>

                                                <div class="fw-semibold">
                                                    Cultural Events
                                                </div>

                                                <small class="text-muted">
                                                    Music, dance and cultural competitions
                                                </small>

                                            </div>

                                        </div>

                                    </td>

                                    <td>

                                        <span class="badge text-bg-success">

                                            <i class="bi bi-check-circle me-1"></i>

                                            Active

                                        </span>

                                    </td>

                                    <td>

                                        <small class="text-muted">
                                            16 July 2026
                                        </small>

                                    </td>

                                    <td class="text-end px-4">

                                        <a href="edit_category.php?id=2" class="btn btn-sm btn-outline-warning me-1">

                                            <i class="bi bi-pencil"></i>

                                        </a>


                                        <button class="btn btn-sm btn-outline-danger"
                                            onclick="openDeleteModal(2, 'Cultural Events')">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </td>

                                </tr>


                                <!-- Sports Events -->
                                <tr data-status="inactive">

                                    <td class="px-4">

                                        <span class="text-muted">
                                            03
                                        </span>

                                    </td>

                                    <td>

                                        <div class="d-flex align-items-center">

                                            <div class="bg-warning-subtle text-warning rounded-3 p-2 me-3">

                                                <i class="bi bi-trophy fs-5"></i>

                                            </div>

                                            <div>

                                                <div class="fw-semibold">
                                                    Sports Events
                                                </div>

                                                <small class="text-muted">
                                                    Indoor and outdoor sports competitions
                                                </small>

                                            </div>

                                        </div>

                                    </td>

                                    <td>

                                        <span class="badge text-bg-danger">

                                            <i class="bi bi-x-circle me-1"></i>

                                            Inactive

                                        </span>

                                    </td>

                                    <td>

                                        <small class="text-muted">
                                            17 July 2026
                                        </small>

                                    </td>

                                    <td class="text-end px-4">

                                        <a href="edit_category.php?id=3" class="btn btn-sm btn-outline-warning me-1">

                                            <i class="bi bi-pencil"></i>

                                        </a>


                                        <button class="btn btn-sm btn-outline-danger"
                                            onclick="openDeleteModal(3, 'Sports Events')">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </td>

                                </tr>


                            </tbody>

                        </table>

                    </div>


                    <!-- Empty State -->
                    <div id="emptyState" class="text-center py-5 d-none">

                        <i class="bi bi-search display-4 text-muted"></i>

                        <h5 class="mt-3">
                            No Categories Found
                        </h5>

                        <p class="text-muted">
                            Try changing your search or filter.
                        </p>

                    </div>

                </div>


                <!-- Card Footer -->
                <div class="card-footer bg-white border-0 py-3">

                    <div class="row align-items-center">


                        <div class="col-md-6">

                            <small class="text-muted">

                                Showing
                                <strong id="visibleCount">
                                    3
                                </strong>
                                categories

                            </small>

                        </div>


                        <div class="col-md-6">

                            <nav class="float-md-end mt-3 mt-md-0">

                                <ul class="pagination pagination-sm mb-0">

                                    <li class="page-item disabled">

                                        <a class="page-link">

                                            <i class="bi bi-chevron-left"></i>

                                        </a>

                                    </li>


                                    <li class="page-item active">

                                        <a class="page-link">
                                            1
                                        </a>

                                    </li>


                                    <li class="page-item">

                                        <a class="page-link">

                                            <i class="bi bi-chevron-right"></i>

                                        </a>

                                    </li>

                                </ul>

                            </nav>

                        </div>

                    </div>

                </div>

            </div>


        </div>

    </div>


    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 shadow">


                <div class="modal-header">

                    <h5 class="modal-title">

                        <i class="bi bi-exclamation-triangle text-danger me-2"></i>

                        Delete Category

                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">

                    </button>

                </div>


                <div class="modal-body">

                    Are you sure you want to delete

                    <strong id="deleteCategoryName">
                    </strong>

                    ?

                    <div class="alert alert-warning mt-3 mb-0">

                        <i class="bi bi-info-circle me-2"></i>

                        This action cannot be undone.

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">

                        Cancel

                    </button>


                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">

                        <i class="bi bi-trash me-1"></i>

                        Delete Category

                    </button>

                </div>

            </div>

        </div>

    </div>


    <!-- Footer -->
    <?php include_once("Footer.php"); ?>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>


    <!-- Template JS -->
    <script src="assets/js/plugins/simplebar.min.js"></script>

    <script src="assets/js/plugins/popper.min.js"></script>

    <script src="assets/js/icon/custom-icon.js"></script>

    <script src="assets/js/plugins/feather.min.js"></script>

    <script src="assets/js/component.js"></script>

    <script src="assets/js/theme.js"></script>

    <script src="assets/js/script.js"></script>


    <!-- Search and Filter -->
    <script>

        const searchInput =
            document.getElementById("categorySearch");

        const statusFilter =
            document.getElementById("statusFilter");

        const rows =
            document.querySelectorAll(
                "#categoryTable tr"
            );

        const emptyState =
            document.getElementById("emptyState");

        const visibleCount =
            document.getElementById("visibleCount");


        function filterCategories() {

            const searchValue =
                searchInput.value.toLowerCase();

            const statusValue =
                statusFilter.value;

            let count = 0;


            rows.forEach(function (row) {

                const categoryName =
                    row
                        .querySelector(".fw-semibold")
                        .textContent
                        .toLowerCase();

                const rowStatus =
                    row.dataset.status;


                const matchesSearch =
                    categoryName.includes(
                        searchValue
                    );


                const matchesStatus =
                    statusValue === "all" ||
                    rowStatus === statusValue;


                if (
                    matchesSearch &&
                    matchesStatus
                ) {

                    row.classList.remove("d-none");

                    count++;

                } else {

                    row.classList.add("d-none");

                }

            });


            visibleCount.textContent = count;


            if (count === 0) {

                emptyState.classList.remove("d-none");

            } else {

                emptyState.classList.add("d-none");

            }

        }


        searchInput.addEventListener(
            "keyup",
            filterCategories
        );


        statusFilter.addEventListener(
            "change",
            filterCategories
        );


        // Delete Modal
        let deleteCategoryId = null;


        function openDeleteModal(
            categoryId,
            categoryName
        ) {

            deleteCategoryId =
                categoryId;


            document
                .getElementById(
                    "deleteCategoryName"
                )
                .textContent =
                categoryName;


            const modal =
                new bootstrap.Modal(
                    document.getElementById(
                        "deleteModal"
                    )
                );


            modal.show();

        }


        document
            .getElementById(
                "confirmDeleteBtn"
            )
            .addEventListener(
                "click",
                function () {


                    if (
                        deleteCategoryId
                    ) {

                        // Connect your PHP delete page here
                        // window.location.href =
                        // "delete_category.php?id=" +
                        // deleteCategoryId;


                        alert(
                            "Category deleted successfully."
                        );

                    }

                }

            );

    </script>


    <!-- Layout Settings -->

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
