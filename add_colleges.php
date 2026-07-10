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
                        <h5 class="mb-0 font-medium">Add University</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="Index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0)">University Management</a></li>
                        <li class="breadcrumb-item" aria-current="page">Add University</li>
                    </ul>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0">

                        <div class="card-header">
                            <h5 class="mb-0">College Details</h5>
                        </div>

                        <div class="card-body">

                            <form id="collegeForm" class="needs-validation" novalidate>

                                <div class="row">

                                    <!-- College Name -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">College Name</label>
                                        <input type="text" class="form-control" required>
                                        <div class="invalid-feedback">College name is required</div>
                                    </div>

                                    <!-- Short Name -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Short Name</label>
                                        <input type="text" class="form-control" required>
                                        <div class="invalid-feedback">Short name is required</div>
                                    </div>

                                    <!-- University -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">University</label>
                                        <select class="form-select" required>
                                            <option value="">Select University</option>
                                            <option>GTU</option>
                                            <option>Delhi University</option>
                                            <option>Mumbai University</option>
                                        </select>
                                        <div class="invalid-feedback">Please select university</div>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" required>
                                        <div class="invalid-feedback">Valid email is required</div>
                                    </div>

                                    <!-- Phone -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control" pattern="[0-9]{7,15}" required>
                                        <div class="invalid-feedback">Enter valid phone number</div>
                                    </div>

                                    <!-- Address -->
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea class="form-control" rows="3" required></textarea>
                                        <div class="invalid-feedback">Address is required</div>
                                    </div>

                                    <!-- Logo -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Upload Logo</label>
                                        <input type="file" class="form-control" id="collegeLogo" accept="image/png, image/jpeg">
                                        <div class="invalid-feedback">Only JPG/PNG allowed</div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" required>
                                            <option value="">Choose status</option>
                                            <option>Active</option>
                                            <option>Inactive</option>
                                        </select>
                                        <div class="invalid-feedback">Select status</div>
                                    </div>

                                </div>

                                <!-- Buttons -->
                                <div class="text-end mt-3">
                                    <button type="reset" class="btn btn-light me-2">Reset</button>
                                    <button type="submit" class="btn btn-primary">
                                        Save College
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
        document.getElementById("collegeForm").addEventListener("submit", function(e) {
            e.preventDefault();

            let isValid = true;

            const form = this;
            form.classList.add("was-validated");

            const name = document.getElementById("college_name");
            const shortName = document.getElementById("college_short_name");
            const university = document.getElementById("university");
            const email = document.getElementById("college_email");
            const phone = document.getElementById("college_phone");
            const address = document.getElementById("college_address");
            const status = document.getElementById("college_status");
            const logo = document.getElementById("collegeLogo");

            // Reset validation
            document.querySelectorAll("#collegeForm .form-control, #collegeForm .form-select").forEach(el => {
                el.classList.remove("is-invalid");
            });

            // College Name
            if (name.value.trim() === "") {
                name.classList.add("is-invalid");
                isValid = false;
            }

            // Short Name
            if (shortName.value.trim() === "") {
                shortName.classList.add("is-invalid");
                isValid = false;
            }

            // University
            if (university.value === "") {
                university.classList.add("is-invalid");
                isValid = false;
            }

            // Email
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email.value)) {
                email.classList.add("is-invalid");
                isValid = false;
            }

            // Phone
            const phonePattern = /^[0-9]{7,15}$/;
            if (!phonePattern.test(phone.value)) {
                phone.classList.add("is-invalid");
                isValid = false;
            }

            // Address
            if (address.value.trim() === "") {
                address.classList.add("is-invalid");
                isValid = false;
            }

            // Status
            if (status.value === "") {
                status.classList.add("is-invalid");
                isValid = false;
            }

            // Logo validation
            if (logo.files.length > 0) {
                const file = logo.files[0];
                const allowedTypes = ["image/jpeg", "image/png"];

                if (!allowedTypes.includes(file.type)) {
                    alert("Only JPG or PNG images allowed");
                    isValid = false;
                }
            }

            // Final submit
            if (isValid) {
                alert("College added successfully ✅");
                form.reset();
                form.classList.remove("was-validated");
            }

        });
    </script>

</body>

</html>