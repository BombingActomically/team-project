<?php include 'auth_check.php'; ?>
<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr" data-pc-theme="light">
<!-- [Head] start -->

<head>
  <title>University Status | Admin</title>
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
<!-- [Head] end -->
<!-- [Body] Start -->

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
            <h5 class="mb-0 font-medium">University Status</h5>
          </div>
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="Index.php">Home</a></li>
            <li class="breadcrumb-item">University</li>
            <li class="breadcrumb-item">Status</li>
          </ul>
        </div>
      </div>

      <!-- STATUS CARDS -->
      <div class="row mb-4">

        <div class="col-md-4">
          <div class="card status-card bg-success text-white">
            <div class="card-body">
              <h6>Active Universities</h6>
              <h3>12</h3>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card status-card bg-danger text-white">
            <div class="card-body">
              <h6>Inactive Universities</h6>
              <h3>3</h3>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card status-card bg-primary text-white">
            <div class="card-body">
              <h6>Total Universities</h6>
              <h3>15</h3>
            </div>
          </div>
        </div>

      </div>

      <!-- TABLE -->
      <div class="card shadow-sm border-0">

        <div class="card-header">
          <h5 class="mb-0">Manage Status</h5>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Status</th>
                  <th>Toggle</th>
                </tr>
              </thead>

              <tbody>

                <tr>
                  <td>1</td>
                  <td>Harvard University</td>
                  <td>info@harvard.edu</td>
                  <td><span class="badge bg-success">Active</span></td>
                  <td>
                    <div class="form-check form-switch">
                      <input class="form-check-input status-toggle" type="checkbox" checked>
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>2</td>
                  <td>Oxford University</td>
                  <td>contact@ox.ac.uk</td>
                  <td><span class="badge bg-success">Active</span></td>
                  <td>
                    <div class="form-check form-switch">
                      <input class="form-check-input status-toggle" type="checkbox" checked>
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>3</td>
                  <td>Stanford University</td>
                  <td>hello@stanford.edu</td>
                  <td><span class="badge bg-danger">Inactive</span></td>
                  <td>
                    <div class="form-check form-switch">
                      <input class="form-check-input status-toggle" type="checkbox">
                    </div>
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
<!-- [Body] end -->

</html>
