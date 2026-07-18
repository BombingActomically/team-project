<?php
include 'auth_check.php';

/*
 * ===========================================================================
 * NOTE: Replace this sample dataset with a real DB query, e.g.:
 *
 * $stmt = $pdo->prepare("SELECT * FROM notifications ORDER BY sent_at DESC");
 * $stmt->execute();
 * $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
 * ===========================================================================
 */
$notifications = [
    [
        'id'       => 1,
        'receiver' => 'All Students',
        'college'  => '-',
        'event'    => '-',
        'type'     => 'Email',
        'subject'  => 'Hackathon Registration Open',
        'message'  => 'Registrations for the annual Hackathon are now open. Register before the deadline to secure your spot.',
        'priority' => 'Important',
        'status'   => 'Sent',
        'sent_by'  => 'Admin',
        'sent_at'  => '2026-07-10 10:15:00',
    ],
    [
        'id'       => 2,
        'receiver' => 'Event Participants',
        'college'  => 'ABC Engineering College',
        'event'    => 'Code Clash',
        'type'     => 'SMS',
        'subject'  => 'Venue Change Alert',
        'message'  => 'The venue for Code Clash has been changed to Seminar Hall 2. Please note the change.',
        'priority' => 'Urgent',
        'status'   => 'Sent',
        'sent_by'  => 'Admin',
        'sent_at'  => '2026-07-09 16:42:00',
    ],
    [
        'id'       => 3,
        'receiver' => 'All Colleges',
        'college'  => '-',
        'event'    => '-',
        'type'     => 'In-App Notification',
        'subject'  => 'New Guidelines Uploaded',
        'message'  => 'Updated participation guidelines have been uploaded to the resources section.',
        'priority' => 'Normal',
        'status'   => 'Sent',
        'sent_by'  => 'Admin',
        'sent_at'  => '2026-07-08 09:05:00',
    ],
    [
        'id'       => 4,
        'receiver' => 'Individual Student',
        'college'  => 'XYZ College',
        'event'    => 'Dance Competition',
        'type'     => 'Email',
        'subject'  => 'Payment Confirmation Pending',
        'message'  => 'We could not confirm your payment for Dance Competition. Please re-check your submission.',
        'priority' => 'Important',
        'status'   => 'Failed',
        'sent_by'  => 'Admin',
        'sent_at'  => '2026-07-07 14:20:00',
    ],
    [
        'id'       => 5,
        'receiver' => 'All Universities',
        'college'  => '-',
        'event'    => '-',
        'type'     => 'Email',
        'subject'  => 'Annual Fest Dates Announced',
        'message'  => 'The annual fest will be held from 15th to 17th August. Mark your calendars.',
        'priority' => 'Normal',
        'status'   => 'Sent',
        'sent_by'  => 'Admin',
        'sent_at'  => '2026-07-05 11:30:00',
    ],
    [
        'id'       => 6,
        'receiver' => 'Event Participants',
        'college'  => 'PQR University College',
        'event'    => 'Hackathon',
        'type'     => 'SMS',
        'subject'  => 'Reminder: Submission Deadline Tomorrow',
        'message'  => 'This is a reminder that your project submission deadline is tomorrow at 6 PM.',
        'priority' => 'Urgent',
        'status'   => 'Pending',
        'sent_by'  => 'Admin',
        'sent_at'  => '2026-07-04 18:00:00',
    ],
];

// ---------- Basic server-side filtering (works with $_GET so filters are shareable/bookmarkable) ----------
$searchTerm     = trim($_GET['search'] ?? '');
$filterType     = trim($_GET['type'] ?? '');
$filterPriority = trim($_GET['priority'] ?? '');
$filterStatus   = trim($_GET['status'] ?? '');

$filtered = array_filter($notifications, function ($n) use ($searchTerm, $filterType, $filterPriority, $filterStatus) {
    $matchesSearch = $searchTerm === '' ||
        stripos($n['subject'], $searchTerm) !== false ||
        stripos($n['message'], $searchTerm) !== false ||
        stripos($n['receiver'], $searchTerm) !== false;

    $matchesType     = $filterType === '' || $n['type'] === $filterType;
    $matchesPriority = $filterPriority === '' || $n['priority'] === $filterPriority;
    $matchesStatus   = $filterStatus === '' || $n['status'] === $filterStatus;

    return $matchesSearch && $matchesType && $matchesPriority && $matchesStatus;
});

// Helpers for badge colors, kept consistent with the template's utility classes
function priority_badge_class($priority) {
    switch ($priority) {
        case 'Urgent':    return 'bg-danger';
        case 'Important': return 'bg-warning text-dark';
        default:          return 'bg-secondary';
    }
}

function status_badge_class($status) {
    switch ($status) {
        case 'Sent':    return 'bg-success';
        case 'Pending': return 'bg-warning text-dark';
        case 'Failed':  return 'bg-danger';
        default:        return 'bg-secondary';
    }
}

function type_icon($type) {
    switch ($type) {
        case 'Email': return 'ti ti-mail';
        case 'SMS':   return 'ti ti-message';
        default:      return 'ti ti-bell';
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
                    <h5 class="mb-0 font-medium">Notification History</h5>
                </div>

                <ul class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="index.php">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        Notifications
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        Notification History
                    </li>
                </ul>
            </div>

            <div class="page-block">
                <a href="send-notification.php" class="btn btn-primary">
                    <i class="ti ti-send"></i>
                    Send New Notification
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <form method="get" action="" class="row g-3 align-items-end">

                            <div class="col-md-4">
                                <label class="form-label">Search</label>
                                <input
                                    type="text"
                                    name="search"
                                    class="form-control"
                                    placeholder="Search subject, message or recipient..."
                                    value="<?php echo htmlspecialchars($searchTerm); ?>">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-select">
                                    <option value="">All Types</option>
                                    <?php foreach (['Email', 'SMS', 'In-App Notification'] as $t): ?>
                                        <option value="<?php echo $t; ?>" <?php echo $filterType === $t ? 'selected' : ''; ?>>
                                            <?php echo $t; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Priority</label>
                                <select name="priority" class="form-select">
                                    <option value="">All Priorities</option>
                                    <?php foreach (['Normal', 'Important', 'Urgent'] as $p): ?>
                                        <option value="<?php echo $p; ?>" <?php echo $filterPriority === $p ? 'selected' : ''; ?>>
                                            <?php echo $p; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <?php foreach (['Sent', 'Pending', 'Failed'] as $s): ?>
                                        <option value="<?php echo $s; ?>" <?php echo $filterStatus === $s ? 'selected' : ''; ?>>
                                            <?php echo $s; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti ti-filter"></i> Filter
                                </button>
                                <a href="notification-history.php" class="btn btn-light" title="Clear filters">
                                    <i class="ti ti-x"></i>
                                </a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            All Notifications
                            <span class="badge bg-light-primary text-primary ms-2"><?php echo count($filtered); ?></span>
                        </h5>
                    </div>

                    <div class="card-body table-responsive">

                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Subject</th>
                                    <th>Recipient</th>
                                    <th>College / Event</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Sent On</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($filtered)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i class="ti ti-inbox fs-3 d-block mb-2"></i>
                                            No notifications found matching your filters.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($filtered as $n): ?>
                                        <tr>
                                            <td><?php echo $n['id']; ?></td>

                                            <td>
                                                <i class="<?php echo type_icon($n['type']); ?> me-1"></i>
                                                <?php echo htmlspecialchars($n['type']); ?>
                                            </td>

                                            <td class="fw-medium"><?php echo htmlspecialchars($n['subject']); ?></td>

                                            <td><?php echo htmlspecialchars($n['receiver']); ?></td>

                                            <td>
                                                <?php
                                                    $collegeEvent = [];
                                                    if ($n['college'] !== '-') $collegeEvent[] = $n['college'];
                                                    if ($n['event'] !== '-') $collegeEvent[] = $n['event'];
                                                    echo $collegeEvent ? htmlspecialchars(implode(' / ', $collegeEvent)) : '<span class="text-muted">-</span>';
                                                ?>
                                            </td>

                                            <td>
                                                <span class="badge <?php echo priority_badge_class($n['priority']); ?>">
                                                    <?php echo htmlspecialchars($n['priority']); ?>
                                                </span>
                                            </td>

                                            <td>
                                                <span class="badge <?php echo status_badge_class($n['status']); ?>">
                                                    <?php echo htmlspecialchars($n['status']); ?>
                                                </span>
                                            </td>

                                            <td>
                                                <?php echo date('d M Y, h:i A', strtotime($n['sent_at'])); ?>
                                            </td>

                                            <td class="text-end">
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-light-primary view-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewNotificationModal"
                                                    data-id="<?php echo $n['id']; ?>"
                                                    data-subject="<?php echo htmlspecialchars($n['subject']); ?>"
                                                    data-message="<?php echo htmlspecialchars($n['message']); ?>"
                                                    data-receiver="<?php echo htmlspecialchars($n['receiver']); ?>"
                                                    data-type="<?php echo htmlspecialchars($n['type']); ?>"
                                                    data-priority="<?php echo htmlspecialchars($n['priority']); ?>"
                                                    data-status="<?php echo htmlspecialchars($n['status']); ?>"
                                                    data-sentby="<?php echo htmlspecialchars($n['sent_by']); ?>"
                                                    data-sentat="<?php echo date('d M Y, h:i A', strtotime($n['sent_at'])); ?>"
                                                    title="View">
                                                    <i class="ti ti-eye"></i>
                                                </button>

                                                <?php if ($n['status'] === 'Failed'): ?>
                                                    <a href="resend-notification.php?id=<?php echo $n['id']; ?>"
                                                       class="btn btn-sm btn-light-warning"
                                                       title="Resend">
                                                        <i class="ti ti-refresh"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <a href="delete-notification.php?id=<?php echo $n['id']; ?>"
                                                   class="btn btn-sm btn-light-danger delete-btn"
                                                   title="Delete"
                                                   onclick="return confirm('Delete this notification record? This cannot be undone.');">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>

                    </div>

                    <!-- Pagination (static placeholder — wire up to real paging when using a DB query) -->
                    <?php if (!empty($filtered)): ?>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <span class="text-muted">Showing <?php echo count($filtered); ?> of <?php echo count($notifications); ?> notifications</span>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

    </div>
</div>
<!-- [ Main Content ] end -->

<!-- View Notification Modal -->
<div class="modal fade" id="viewNotificationModal" tabindex="-1" aria-labelledby="viewNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="viewNotificationModalLabel">Notification Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <th class="text-muted" style="width:130px;">Subject</th>
                            <td id="modalSubject" class="fw-medium"></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Message</th>
                            <td id="modalMessage"></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Recipient</th>
                            <td id="modalReceiver"></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Type</th>
                            <td id="modalType"></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Priority</th>
                            <td id="modalPriority"></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Status</th>
                            <td id="modalStatus"></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Sent By</th>
                            <td id="modalSentBy"></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Sent On</th>
                            <td id="modalSentAt"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

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

    <!-- ===================== Notification History: modal population ===================== -->
    <script>
        (function () {
            'use strict';

            const modal = document.getElementById('viewNotificationModal');

            modal.addEventListener('show.bs.modal', function (event) {
                const btn = event.relatedTarget;
                if (!btn) return;

                document.getElementById('modalSubject').textContent  = btn.getAttribute('data-subject');
                document.getElementById('modalMessage').textContent  = btn.getAttribute('data-message');
                document.getElementById('modalReceiver').textContent = btn.getAttribute('data-receiver');
                document.getElementById('modalType').textContent     = btn.getAttribute('data-type');
                document.getElementById('modalPriority').textContent = btn.getAttribute('data-priority');
                document.getElementById('modalStatus').textContent   = btn.getAttribute('data-status');
                document.getElementById('modalSentBy').textContent   = btn.getAttribute('data-sentby');
                document.getElementById('modalSentAt').textContent   = btn.getAttribute('data-sentat');
            });
        })();
    </script>

</body>

</html>