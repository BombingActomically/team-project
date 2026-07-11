<?php include 'auth_check.php'; ?>
<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr" data-pc-theme="light">

<head>

    <title>Admin | Add Student</title>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="icon" href="assets/images/favicon.svg" type="image/x-icon" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/fonts/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/fonts/feather.css">
    <link rel="stylesheet" href="assets/css/style.css">

</head>


<body>


    <div class="loader-bg fixed inset-0 bg-white z-[1034]">
        <div class="loader-track h-[5px] w-full">
            <div class="loader-fill w-[300px] h-[5px] bg-primary-500"></div>
        </div>
    </div>



    <?php include_once("Sidebar.php"); ?>

    <?php include_once("Header.php"); ?>



    <div class="pc-container">

        <div class="pc-content">


            <div class="page-header">

                <div class="page-block">

                    <div class="page-header-title">
                        <h5 class="mb-0 font-medium">
                            Add Student
                        </h5>
                    </div>


                    <ul class="breadcrumb">

                        <li class="breadcrumb-item">
                            <a href="Index.php">Home</a>
                        </li>

                        <li class="breadcrumb-item">
                            Student Management
                        </li>

                        <li class="breadcrumb-item">
                            Add Student
                        </li>

                    </ul>

                </div>

            </div>



            <div class="card shadow-sm border-0">


                <div class="card-header">
                    <h5 class="mb-0">
                        Add Student
                    </h5>
                </div>


                <div class="card-body">


                    <form id="studentForm" class="needs-validation" novalidate enctype="multipart/form-data">

                        <div class="row g-4">

                            <!-- Academic Info -->
                            <div class="col-12">
                                <h6 class="text-primary fw-bold border-bottom pb-2">Academic Details</h6>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="college_id" required>
                                        <option value="">Choose</option>
                                        <option value="1">ABC College</option>
                                        <option value="2">XYZ College</option>
                                    </select>
                                    <label><i class="ti ti-school me-2"></i>College</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="enrollment_no" placeholder="Enrollment" required>
                                    <label><i class="ti ti-id me-2"></i>Enrollment No</label>
                                </div>
                            </div>

                            <!-- Personal Info -->
                            <div class="col-12">
                                <h6 class="text-primary fw-bold border-bottom pb-2">Personal Details</h6>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" placeholder="Name" required>
                                    <label><i class="ti ti-user me-2"></i>Student Name</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" placeholder="Email" required>
                                    <label><i class="ti ti-mail me-2"></i>Email</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating position-relative">
                                    <input type="password" class="form-control" id="password" placeholder="Password" required>
                                    <label><i class="ti ti-lock me-2"></i>Password</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="phone" placeholder="Phone">
                                    <label><i class="ti ti-phone me-2"></i>Phone</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="gender" required>
                                        <option value="">Choose</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <label><i class="ti ti-user-circle me-2"></i>Gender</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="semester" required>
                                        <option value="">Choose</option>
                                        <option>Semester 1</option>
                                        <option>Semester 2</option>
                                        <option>Semester 3</option>
                                        <option>Semester 4</option>
                                        <option>Semester 5</option>
                                        <option>Semester 6</option>
                                        <option>Semester 7</option>
                                        <option>Semester 8</option>
                                    </select>
                                    <label><i class="ti ti-book me-2"></i>Semester</label>
                                </div>
                            </div>

                            <!-- Upload Section -->
                            <div class="col-12">
                                <h6 class="text-primary fw-bold border-bottom pb-2">Documents</h6>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="ti ti-id-badge me-2"></i>ID Card Image
                                </label>
                                <input type="file" class="form-control" id="id_card_image" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="ti ti-camera me-2"></i>Profile Photo
                                </label>
                                <input type="file" class="form-control" id="profile_photo">
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="status" required>
                                        <option value="">Choose</option>
                                        <option value="pending">Pending</option>
                                        <option value="verified">Verified</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="blocked">Blocked</option>
                                    </select>
                                    <label><i class="ti ti-toggle-left me-2"></i>Status</label>
                                </div>
                            </div>

                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-end mt-4">

                            <button type="reset" class="btn btn-light px-4 me-2">
                                <i class="ti ti-refresh"></i> Reset
                            </button>

                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="ti ti-device-floppy"></i> Save Student
                            </button>

                        </div>

                    </form>


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




    <script>
        layout_change('false');

        layout_theme_sidebar_change('dark');

        change_box_container('false');

        layout_caption_change('true');

        layout_rtl_change('false');

        preset_change('preset-1');

        main_layout_change('vertical');



        document.getElementById("studentForm").addEventListener("submit", function(e) {

            e.preventDefault();


            let isValid = true;

            let form = this;


            form.classList.add("was-validated");



            document.querySelectorAll(".form-control,.form-select")
                .forEach(function(el) {

                    el.classList.remove("is-invalid");

                });



            let fields = [
                "college_id",
                "enrollment_no",
                "name",
                "email",
                "password",
                "gender",
                "semester",
                "status"
            ];


            fields.forEach(function(id) {

                let field = document.getElementById(id);

                if (field.value.trim() == "") {

                    field.classList.add("is-invalid");

                    isValid = false;

                }

            });



            let email = document.getElementById("email");

            let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


            if (!emailPattern.test(email.value)) {

                email.classList.add("is-invalid");

                isValid = false;

            }



            let phone = document.getElementById("phone");


            if (phone.value != "" && !/^[0-9]{7,15}$/.test(phone.value)) {

                phone.classList.add("is-invalid");

                isValid = false;

            }




            let idCard = document.getElementById("id_card_image");


            if (idCard.files.length == 0) {

                idCard.classList.add("is-invalid");

                isValid = false;

            } else if (!["image/jpeg", "image/png"].includes(idCard.files[0].type)) {

                idCard.classList.add("is-invalid");

                isValid = false;

            }



            let profile = document.getElementById("profile_photo");


            if (profile.files.length > 0) {

                if (!["image/jpeg", "image/png"].includes(profile.files[0].type)) {

                    profile.classList.add("is-invalid");

                    isValid = false;

                }

            }




            if (isValid) {

                alert("Student added successfully ✅");

                form.reset();

                form.classList.remove("was-validated");

            }


        });
    </script>


</body>

</html>
