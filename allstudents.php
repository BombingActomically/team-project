<?php include 'auth_check.php'; ?>
<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr">

<head>
    <title>All Students | Admin</title>
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
    <!-- Icons -->
    <link rel="stylesheet" href="assets/fonts/phosphor/duotone/style.css" />
    <link rel="stylesheet" href="assets/fonts/tabler-icons.min.css" />
    <link rel="stylesheet" href="assets/fonts/feather.css" />
    <link rel="stylesheet" href="assets/fonts/fontawesome.css" />
    <link rel="stylesheet" href="assets/fonts/material.css" />
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="assets/css/style.css" id="main-style-link" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* ===== University Ledger theme — Light Mode Only ===== */
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
            --th-bg: #F8F9FB;
            --tr-hover: #FAFAFC;
            --badge-bg: #EEF1F6;
            --btn-action-bg: #ffffff;
            --btn-edit-hover: #FBF6EA;
            --pagination-disabled-bg: #ffffff;

            background: var(--bg);
            padding-bottom: 8px;
        }

        .uni-page,
        .uni-page .table,
        .uni-page .form-control,
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
        .uni-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--brass);
            margin-bottom: 6px;
        }

        .uni-eyebrow::before {
            content: "";
            width: 18px;
            height: 1px;
            background: var(--brass);
            display: inline-block;
        }

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
            color: var(--border);
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

        /* ---- Ledger stat cards ---- */
        .uni-stat {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            border-top: 3px solid var(--stat-accent, var(--brass));
            padding: 20px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            transition: box-shadow .2s ease, transform .2s ease;
        }

        .uni-stat:hover {
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        .uni-stat--active {
            --stat-accent: var(--success);
        }

        .uni-stat--inactive {
            --stat-accent: var(--danger);
        }

        .uni-stat--total {
            --stat-accent: var(--ink-2);
        }

        .uni-stat-label {
            font-size: 11.5px;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .uni-stat-value {
            font-family: 'Newsreader', serif;
            font-size: 36px;
            font-weight: 600;
            color: var(--ink);
            line-height: 1;
        }

        .uni-stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 19px;
            color: var(--stat-accent, var(--brass));
            background: color-mix(in srgb, var(--stat-accent, var(--brass)) 12%, var(--card));
            flex-shrink: 0;
        }

        /* ---- Main card / table ---- */
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

        .uni-search {
            position: relative;
        }

        .uni-search i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 14px;
        }

        .uni-search input {
            padding-left: 34px;
            border: 1px solid var(--border);
            border-radius: 8px;
            min-width: 260px;
            font-size: 13.5px;
            background-color: var(--bg);
            color: var(--ink);
        }

        .uni-search input:focus {
            background-color: var(--card);
            color: var(--ink);
            border-color: var(--brass);
            box-shadow: 0 0 0 3px rgba(169, 130, 46, 0.15);
        }

        .uni-btn-add {
            background: var(--ink);
            border: 1px solid var(--ink);
            color: #ffffff;
            font-size: 13.5px;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: background .15s ease;
        }

        .uni-btn-add:hover {
            background: var(--ink-2);
            color: #ffffff;
        }

        /* ---- Table ---- */
        .uni-table thead th {
            background: var(--th-bg);
            color: var(--muted);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            border-bottom: 1px solid var(--border);
            padding: 13px 16px;
            white-space: nowrap;
        }

        .uni-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border) !important;
            border-top: none !important;
            font-size: 13.5px;
            color: var(--ink-2);
            vertical-align: middle;
            background: var(--card) !important;
        }

        .uni-table tbody tr {
            border-left: 3px solid transparent;
            transition: background .15s ease, border-color .15s ease;
        }

        .uni-table tbody tr:hover td {
            background: var(--tr-hover) !important;
        }

        .uni-table tbody tr:hover {
            border-left-color: var(--brass);
        }

        /* Student Specific Styles */
        .uni-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid var(--border);
            padding: 2px;
            background: #fff;
        }

        .uni-name {
            font-family: 'Newsreader', serif;
            font-weight: 600;
            font-size: 15px;
            color: var(--ink);
        }

        .uni-enrollment {
            display: inline-block;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .04em;
            color: var(--ink);
            background: var(--badge-bg);
            border-radius: 5px;
            padding: 2px 7px;
        }

        .uni-email,
        .uni-phone,
        .uni-semester {
            color: var(--muted);
            font-size: 13px;
        }

        .uni-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px 5px 9px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .uni-pill .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .uni-pill--active {
            background: var(--success-bg);
            color: var(--success);
        }

        .uni-pill--active .dot {
            background: var(--success);
        }

        .uni-pill--inactive {
            background: var(--danger-bg);
            color: var(--danger);
        }

        .uni-pill--inactive .dot {
            background: var(--danger);
        }

        .uni-pill--pending {
            background: var(--warning-bg);
            color: var(--warning);
        }

        .uni-pill--pending .dot {
            background: var(--warning);
        }

        .uni-date {
            color: var(--muted);
            font-size: 12.5px;
        }

        /* Toggle switch */
        .uni-page .form-switch .form-check-input {
            width: 40px;
            height: 21px;
            cursor: pointer;
            background-color: var(--border);
            border-color: var(--border);
        }

        .uni-page .form-switch .form-check-input:checked {
            background-color: var(--success);
            border-color: var(--success);
        }

        .uni-page .form-switch .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(31, 138, 87, 0.15);
        }

        /* Action buttons */
        .uni-action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 7px;
            border: 1px solid var(--border);
            background: var(--btn-action-bg);
            color: var(--muted);
            transition: all .15s ease;
        }

        .uni-action-btn:hover.uni-action-view {
            border-color: var(--ink-2);
            color: var(--ink-2);
            background: #F2F4F8;
        }

        .uni-action-btn:hover.uni-action-edit {
            border-color: var(--brass);
            color: var(--brass);
            background: var(--btn-edit-hover);
        }

        .uni-action-btn:hover.uni-action-delete {
            border-color: var(--danger);
            color: var(--danger);
            background: var(--danger-bg);
        }

        @media (max-width: 767px) {
            .uni-card-header {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .uni-search input {
                min-width: 100%;
            }
        }

        /* ---- Pagination ---- */
        .uni-pagination-wrap {
            border-top: 1px solid var(--border);
            flex-wrap: wrap;
            gap: 12px;
        }

        .uni-pagination-info {
            font-size: 12.5px;
            color: var(--muted);
        }

        .uni-pagination-wrap .pagination .page-link {
            color: var(--ink-2);
            border: 1px solid var(--border);
            background-color: var(--card);
            font-size: 13px;
            padding: 6px 12px;
            margin-left: 4px;
            border-radius: 7px !important;
        }

        .uni-pagination-wrap .pagination .page-item.active .page-link {
            background: var(--ink);
            border-color: var(--ink);
            color: var(--bg);
        }

        .uni-pagination-wrap .pagination .page-item.disabled .page-link {
            color: var(--muted);
            background: var(--pagination-disabled-bg);
            border-color: var(--border);
        }

        .uni-pagination-wrap .pagination .page-link:hover:not(.disabled) {
            border-color: var(--brass);
            color: var(--brass);
            background: var(--btn-edit-hover);
        }

        .uni-per-page {
            font-size: 12.5px;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .uni-per-page select {
            border: 1px solid var(--border);
            background-color: var(--bg);
            color: var(--ink);
            border-radius: 6px;
            font-size: 12.5px;
            padding: 3px 6px;
        }
    </style>
</head>

<body>

    <!-- [ Pre-loader ] start -->
    <div class="loader-bg fixed inset-0 bg-white z-[1034]">
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
            <div class="page-header uni-header">
                <div class="page-block">
                    <div class="uni-eyebrow">Student Directory</div>
                    <h1 class="uni-title">All Students</h1>
                    <ul class="uni-breadcrumb">
                        <li><a href="Index.php">Home</a></li>
                        <li><a href="javascript: void(0)">Student Management</a></li>
                        <li style="color: var(--ink);">All Students</li>
                    </ul>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Status Summary Cards ] start -->
            <div class="row g-3 mb-4 mt-1">

                <div class="col-md-4">
                    <div class="uni-stat uni-stat--active">
                        <div>
                            <div class="uni-stat-label">Active Students</div>
                            <div class="uni-stat-value" id="activeCount">0</div>
                        </div>
                        <div class="uni-stat-icon"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="uni-stat uni-stat--inactive">
                        <div>
                            <div class="uni-stat-label">Inactive Students</div>
                            <div class="uni-stat-value" id="inactiveCount">0</div>
                        </div>
                        <div class="uni-stat-icon"><i class="bi bi-x-circle-fill"></i></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="uni-stat uni-stat--total">
                        <div>
                            <div class="uni-stat-label">Total Students</div>
                            <div class="uni-stat-value" id="totalCount">0</div>
                        </div>
                        <div class="uni-stat-icon"><i class="bi bi-people-fill"></i></div>
                    </div>
                </div>

            </div>
            <!-- [ Status Summary Cards ] end -->

            <!-- [ Main Table Card ] start -->
            <div class="row">
                <div class="col-12">
                    <div class="uni-card">

                        <div class="uni-card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h5 class="uni-card-title">Student List</h5>
                                <div class="uni-card-sub">Search and manage student profiles and active status.</div>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <div class="uni-search">
                                    <i class="bi bi-search"></i>
                                    <input type="text" id="studentSearch" class="form-control form-control-sm" placeholder="Search by name, email, enrollment...">
                                </div>
                                <a href="addstudent.php" class="uni-btn-add">
                                    <i class="ti ti-plus"></i> Add Student
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">

                                <table class="table uni-table align-middle mb-0" id="studentTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Avatar</th>
                                            <th>Enrollment No</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Semester</th>
                                            <th>Status</th>
                                            <th>Toggle</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <!-- Rendered dynamically by AJAX -->
                                    </tbody>
                                </table>

                            </div>

                            <!-- Pagination Footer -->
                            <div class="uni-pagination-wrap d-flex justify-content-between align-items-center px-3 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="uni-pagination-info" id="paginationInfo"></div>
                                    <div class="uni-per-page">
                                        <label for="rowsPerPage" class="mb-0">Rows:</label>
                                        <select id="rowsPerPage">
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                        </select>
                                    </div>
                                </div>
                                <nav aria-label="Student table pagination">
                                    <ul class="pagination pagination-sm mb-0" id="paginationControls"></ul>
                                </nav>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <!-- [ Main Table Card ] end -->

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

    <div class="floting-button fixed bottom-[50px] right-[30px] z-[1030]"></div>

    <!-- Layout Initializer Scripts (Forced Light Mode) -->
    <script>
        layout_change('false'); // Force Light Mode
        layout_theme_sidebar_change('dark'); // Dark Sidebar as in original
        change_box_container('false');
        layout_caption_change('true');
        layout_rtl_change('false');
        preset_change('preset-1');
        main_layout_change('vertical');
    </script>

    <!-- AJAX Engine Script -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        let currentPage = 1;
        let rowsPerPage = 5;

        function loadStudents() {
            const searchValue = $('#studentSearch').val();
            
            $.ajax({
                url: 'fetch_students.php', 
                type: 'POST',
                data: {
                    search: searchValue,
                    page: currentPage,
                    per_page: rowsPerPage
                },
                dataType: 'json',
                success: function(response) {
                    // Update table with the dynamically formed row HTML payload
                    $('#studentTable tbody').html(response.html);
                    
                    // Populate overall metrics to the top stat boxes
                    $('#activeCount').text(response.activeCount);
                    $('#inactiveCount').text(response.inactiveCount);
                    $('#totalCount').text(response.totalCount);
                    
                    renderPaginationInfo(response.totalFiltered, response.start, response.end);
                    renderPaginationControls(response.totalPages);
                },
                error: function(xhr, status, error) {
                    console.error("Server Response:", xhr.responseText);
                    $('#studentTable tbody').html(
                        '<tr><td colspan="10" class="text-center text-danger py-3">' +
                        '<strong>Error fetching data:</strong> ' + error + ' (Status: ' + xhr.status + ').<br>' +
                        'Check your browser Console (F12 -> Console) for server error details.' +
                        '</td></tr>'
                    );
                }
            });
        }

        function renderPaginationInfo(totalRows, start, end) {
            const info = $('#paginationInfo');
            if (totalRows === 0) {
                info.text('No students found');
                return;
            }
            info.text(`Showing ${start + 1}–${Math.min(end, totalRows)} of ${totalRows}`);
        }

        function renderPaginationControls(totalPages) {
            const controls = $('#paginationControls');
            controls.empty();

            function makePageItem(label, page, opts = {}) {
                const li = $('<li>').addClass('page-item');
                if (opts.disabled) li.addClass('disabled');
                if (opts.active) li.addClass('active');

                const a = $('<a>').addClass('page-link').attr('href', 'javascript:void(0)').text(label);
                if (!opts.disabled && !opts.active) {
                    a.on('click', function() {
                        currentPage = page;
                        loadStudents();
                    });
                }
                li.append(a);
                return li;
            }

            controls.append(makePageItem('Prev', currentPage - 1, { disabled: currentPage === 1 }));

            for (let p = 1; p <= totalPages; p++) {
                controls.append(makePageItem(p, p, { active: p === currentPage }));
            }

            controls.append(makePageItem('Next', currentPage + 1, { disabled: currentPage === totalPages || totalPages === 0 }));
        }

        $(document).ready(function() {
            // Set the dropdown to match state
            $('#rowsPerPage').val(String(rowsPerPage));
            loadStudents();

            $('#studentSearch').on('keyup', function() {
                currentPage = 1;
                loadStudents();
            });

            $('#rowsPerPage').on('change', function() {
                rowsPerPage = parseInt($(this).val(), 10);
                currentPage = 1;
                loadStudents();
            });

            // Handle database updates dynamically on click of status switches
            $(document).on('change', '.status-toggle', function() {
                const toggle = $(this);
                const row = toggle.closest('tr');
                const studentId = row.data('id');
                const badge = row.find('.status-badge');
                
                const isChecked = toggle.prop('checked');
                const newStatus = isChecked ? 'active' : 'inactive';

                // Optimistic visual switch
                if (isChecked) {
                    badge.removeClass('uni-pill--inactive').addClass('uni-pill--active').html('<span class="dot"></span>Active');
                } else {
                    badge.removeClass('uni-pill--active').addClass('uni-pill--inactive').html('<span class="dot"></span>Inactive');
                }

                $.ajax({
                    url: 'update_student_status.php',
                    type: 'POST',
                    data: {
                        id: studentId,
                        status: newStatus
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Silently update dashboard statistic figures
                            $.ajax({
                                url: 'fetch_students.php', 
                                type: 'POST',
                                data: { search: $('#studentSearch').val(), page: currentPage, per_page: rowsPerPage },
                                dataType: 'json',
                                success: function(counters) {
                                    $('#activeCount').text(counters.activeCount);
                                    $('#inactiveCount').text(counters.inactiveCount);
                                    $('#totalCount').text(counters.totalCount);
                                }
                            });
                        } else {
                            alert('Update failed: ' + response.message);
                            revertToggle(toggle, badge, !isChecked);
                        }
                    },
                    error: function() {
                        alert('Server error occurred during execution. Reverting change.');
                        revertToggle(toggle, badge, !isChecked);
                    }
                });
            });

            function revertToggle(toggle, badge, shouldBeChecked) {
                toggle.prop('checked', shouldBeChecked);
                if (shouldBeChecked) {
                    badge.removeClass('uni-pill--inactive').addClass('uni-pill--active').html('<span class="dot"></span>Active');
                } else {
                    badge.removeClass('uni-pill--active').addClass('uni-pill--inactive').html('<span class="dot"></span>Inactive');
                }
            }
        });
    </script>
</body>
</html>