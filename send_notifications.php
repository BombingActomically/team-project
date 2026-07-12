<?php
include 'auth_check.php';

// ---------- Server-side validation (fallback safety net) ----------
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $receiver          = trim($_POST['receiver'] ?? '');
    $college           = trim($_POST['college'] ?? '');
    $event             = trim($_POST['event'] ?? '');
    $notification_type = trim($_POST['notification_type'] ?? '');
    $subject           = trim($_POST['subject'] ?? '');
    $message           = trim($_POST['message'] ?? '');
    $priority          = trim($_POST['priority'] ?? '');

    if ($receiver === '') {
        $errors['receiver'] = 'Please select a recipient.';
    }

    if ($notification_type === '') {
        $errors['notification_type'] = 'Please select a notification type.';
    }

    if ($subject === '') {
        $errors['subject'] = 'Subject is required.';
    } elseif (strlen($subject) > 150) {
        $errors['subject'] = 'Subject must be under 150 characters.';
    }

    if ($message === '') {
        $errors['message'] = 'Message is required.';
    } elseif (strlen($message) < 10) {
        $errors['message'] = 'Message should be at least 10 characters.';
    }

    if ($priority === '') {
        $errors['priority'] = 'Please select a priority.';
    }

    // Conditional requirement: if sending to event participants, event is required
    if ($receiver === 'event_participants' && $event === '') {
        $errors['event'] = 'Please select an event for event participants.';
    }

    if (empty($errors)) {
        // TODO: hook up actual sending logic (DB insert / mailer / SMS gateway) here
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
                    <h5 class="mb-0 font-medium">Send Notification</h5>
                </div>

                <ul class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="index.php">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        Notifications
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        Send Notification
                    </li>
                </ul>
            </div>
        </div>

        <!-- Notification Form -->
        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="card shadow-sm border-0">

                    <div class="card-header">
                        <h5 class="mb-0">
                            Send New Notification
                        </h5>
                    </div>

                    <div class="card-body">

                        <?php if ($success): ?>
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <i class="ti ti-circle-check me-2"></i>
                                Notification submitted successfully.
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

                        <form action="" method="post" id="notificationForm" class="needs-validation" novalidate>

                            <!-- Recipient -->
                            <div class="mb-3">
                                <label class="form-label" for="receiver">
                                    Send To <span class="text-danger">*</span>
                                </label>

                                <select
                                    class="form-select <?php echo isset($errors['receiver']) ? 'is-invalid' : ''; ?>"
                                    name="receiver"
                                    id="receiver"
                                    required>

                                    <option value="">
                                        -- Select Recipient --
                                    </option>

                                    <option value="all_students">
                                        All Students
                                    </option>

                                    <option value="all_colleges">
                                        All Colleges
                                    </option>

                                    <option value="all_universities">
                                        All Universities
                                    </option>

                                    <option value="event_participants">
                                        Event Participants
                                    </option>

                                    <option value="individual_student">
                                        Individual Student
                                    </option>

                                </select>

                                <div class="invalid-feedback">
                                    <?php echo isset($errors['receiver']) ? htmlspecialchars($errors['receiver']) : 'Please select a recipient.'; ?>
                                </div>
                            </div>

                            <!-- College -->
                            <div class="mb-3">

                                <label class="form-label" for="college">
                                    College
                                </label>

                                <select class="form-select" name="college" id="college">

                                    <option value="">
                                        Select College
                                    </option>

                                    <option value="ABC Engineering College">
                                        ABC Engineering College
                                    </option>

                                    <option value="XYZ College">
                                        XYZ College
                                    </option>

                                    <option value="PQR University College">
                                        PQR University College
                                    </option>

                                </select>

                            </div>

                            <!-- Event -->
                            <div class="mb-3">

                                <label class="form-label" for="event">
                                    Event <span class="text-danger event-required-mark d-none">*</span>
                                </label>

                                <select
                                    class="form-select <?php echo isset($errors['event']) ? 'is-invalid' : ''; ?>"
                                    name="event"
                                    id="event">

                                    <option value="">
                                        Select Event
                                    </option>

                                    <option value="Code Clash">
                                        Code Clash
                                    </option>

                                    <option value="Dance Competition">
                                        Dance Competition
                                    </option>

                                    <option value="Hackathon">
                                        Hackathon
                                    </option>

                                </select>

                                <div class="invalid-feedback">
                                    <?php echo isset($errors['event']) ? htmlspecialchars($errors['event']) : 'Please select an event.'; ?>
                                </div>
                            </div>

                            <!-- Notification Type -->
                            <div class="mb-3">

                                <label class="form-label" for="notification_type">
                                    Notification Type <span class="text-danger">*</span>
                                </label>

                                <select
                                    class="form-select <?php echo isset($errors['notification_type']) ? 'is-invalid' : ''; ?>"
                                    name="notification_type"
                                    id="notification_type"
                                    required>

                                    <option value="">-- Select Type --</option>
                                    <option value="Email">Email</option>
                                    <option value="SMS">SMS</option>
                                    <option value="In-App Notification">In-App Notification</option>

                                </select>

                                <div class="invalid-feedback">
                                    <?php echo isset($errors['notification_type']) ? htmlspecialchars($errors['notification_type']) : 'Please select a notification type.'; ?>
                                </div>
                            </div>

                            <!-- Subject -->
                            <div class="mb-3">

                                <label class="form-label" for="subject">
                                    Subject <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    class="form-control <?php echo isset($errors['subject']) ? 'is-invalid' : ''; ?>"
                                    name="subject"
                                    id="subject"
                                    maxlength="150"
                                    placeholder="Enter Subject"
                                    value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>"
                                    required>

                                <div class="invalid-feedback">
                                    <?php echo isset($errors['subject']) ? htmlspecialchars($errors['subject']) : 'Subject is required.'; ?>
                                </div>
                            </div>

                            <!-- Message -->
                            <div class="mb-3">

                                <label class="form-label" for="message">
                                    Message <span class="text-danger">*</span>
                                </label>

                                <textarea
                                    class="form-control <?php echo isset($errors['message']) ? 'is-invalid' : ''; ?>"
                                    name="message"
                                    id="message"
                                    rows="6"
                                    minlength="10"
                                    placeholder="Write your notification..."
                                    required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>

                                <div class="invalid-feedback">
                                    <?php echo isset($errors['message']) ? htmlspecialchars($errors['message']) : 'Message must be at least 10 characters.'; ?>
                                </div>

                                <div class="form-text">
                                    <span id="charCount">0</span> characters
                                </div>
                            </div>

                            <!-- Priority -->
                            <div class="mb-3">

                                <label class="form-label" for="priority">
                                    Priority <span class="text-danger">*</span>
                                </label>

                                <select
                                    class="form-select <?php echo isset($errors['priority']) ? 'is-invalid' : ''; ?>"
                                    name="priority"
                                    id="priority"
                                    required>

                                    <option value="">-- Select Priority --</option>
                                    <option value="Normal">Normal</option>
                                    <option value="Important">Important</option>
                                    <option value="Urgent">Urgent</option>

                                </select>

                                <div class="invalid-feedback">
                                    <?php echo isset($errors['priority']) ? htmlspecialchars($errors['priority']) : 'Please select a priority.'; ?>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="text-end">

                                <button
                                    type="reset"
                                    class="btn btn-light"
                                    id="resetBtn">

                                    Reset

                                </button>

                                <button
                                    type="submit"
                                    class="btn btn-primary">

                                    <i class="ti ti-send"></i>

                                    Send Notification

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

    <!-- ===================== Notification Form Validation ===================== -->
    <script>
        (function () {
            'use strict';

            const form = document.getElementById('notificationForm');
            const receiver = document.getElementById('receiver');
            const eventField = document.getElementById('event');
            const eventMark = document.querySelector('.event-required-mark');
            const messageField = document.getElementById('message');
            const charCount = document.getElementById('charCount');
            const resetBtn = document.getElementById('resetBtn');

            // Live character counter for the message field
            function updateCharCount() {
                charCount.textContent = messageField.value.length;
            }
            messageField.addEventListener('input', updateCharCount);
            updateCharCount();

            // Event becomes required only when "Event Participants" is selected
            function syncEventRequirement() {
                if (receiver.value === 'event_participants') {
                    eventField.setAttribute('required', 'required');
                    eventMark.classList.remove('d-none');
                } else {
                    eventField.removeAttribute('required');
                    eventField.classList.remove('is-invalid');
                    eventMark.classList.add('d-none');
                }
            }
            receiver.addEventListener('change', syncEventRequirement);
            syncEventRequirement();

            // Clear the red "is-invalid" state as soon as a field becomes valid again
            form.querySelectorAll('.form-control, .form-select').forEach(function (field) {
                field.addEventListener('input', function () {
                    if (field.checkValidity()) {
                        field.classList.remove('is-invalid');
                    }
                });
                field.addEventListener('change', function () {
                    if (field.checkValidity()) {
                        field.classList.remove('is-invalid');
                    }
                });
            });

            // Bootstrap-style client-side validation on submit
            form.addEventListener('submit', function (e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Focus the first invalid field for a smoother experience
                    const firstInvalid = form.querySelector(':invalid');
                    if (firstInvalid) {
                        firstInvalid.classList.add('is-invalid');
                        firstInvalid.focus();
                    }
                }
                form.classList.add('was-validated');
            }, false);

            // Reset validation state when the form is cleared
            resetBtn.addEventListener('click', function () {
                setTimeout(function () {
                    form.classList.remove('was-validated');
                    form.querySelectorAll('.is-invalid').forEach(function (el) {
                        el.classList.remove('is-invalid');
                    });
                    updateCharCount();
                    syncEventRequirement();
                }, 0);
            });
        })();
    </script>

</body>

</html>