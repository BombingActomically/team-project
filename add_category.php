<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr" data-pc-theme="light">

<head>
    <title>Admin | Add University</title>
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
    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="page-block">
                    <div class="page-header-title">
                        <h5 class="mb-0 font-medium">Add Category</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="Index.php">Home</a></li>
                        <li class="breadcrumb-item">Category Management</li>
                        <li class="breadcrumb-item" aria-current="page">Add Category</li>
                    </ul>
                </div>
            </div>

            <!-- Form -->
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0">

                        <div class="card-header">
                            <h5 class="mb-0">Category Details</h5>
                        </div>

                        <div class="card-body">

                            <form id="categoryForm" class="needs-validation" novalidate>

                                <div class="row g-4">

                                    <!-- Category Name -->
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="text" name="name" class="form-control" id="categoryName" placeholder="Category Name" required>
                                            <label>
                                                <i class="ti ti-category me-2"></i>Category Name
                                            </label>
                                            <div class="invalid-feedback">Category name is required</div>
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <select name="status" class="form-select" id="categoryStatus" required>
                                                <option value="">Choose</option>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                            <label>
                                                <i class="ti ti-toggle-left me-2"></i>Status
                                            </label>
                                            <div class="invalid-feedback">Select status</div>
                                        </div>
                                    </div>

                                </div>

                                <!-- Buttons -->
                                <div class="d-flex justify-content-end mt-4">

                                    <button type="reset" class="btn btn-light px-4 me-2">
                                        <i class="ti ti-refresh"></i> Reset
                                    </button>

                                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                        <i class="ti ti-device-floppy"></i> Save Category
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
    <script>
        document.getElementById("categoryForm").addEventListener("submit", function(e) {
            e.preventDefault();

            let isValid = true;
            const form = this;
            form.classList.add("was-validated");

            const name = document.querySelector("input[name='name']");
            const status = document.querySelector("select[name='status']");

            // Reset validation
            document.querySelectorAll("#categoryForm .form-control, #categoryForm .form-select").forEach(el => {
                el.classList.remove("is-invalid");
            });

            // Category Name validation
            if (name.value.trim() === "") {
                name.classList.add("is-invalid");
                isValid = false;
            }

            // Status validation
            if (status.value === "") {
                status.classList.add("is-invalid");
                isValid = false;
            }

            // Final submit
            if (isValid) {
                alert("Category added successfully ✅");
                form.reset();
                form.classList.remove("was-validated");
            }
        });
    </script>

</body>

</html>
