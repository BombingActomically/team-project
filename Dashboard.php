<?php include 'auth_check.php'; ?>
<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr" data-pc-theme="dark">
  <!-- [Head] start -->

  <head>
    <title>Admin | Reports &amp; Analytics</title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Evenza Admin — Reports & Analytics dashboard." />
    <meta name="author" content="Evenza" />

    <!-- [Favicon] icon -->
    <link rel="icon" href="assets/images/favicon.svg" type="image/x-icon" />

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

    <!-- [Evenza Reports & Analytics] page-scoped overrides -->
    <!--
      This block only layers Evenza's charcoal + neon-green identity on top of
      the existing Datta Able theme tokens. It does not replace style.css.
      If your compiled theme uses different CSS variable names for
      --pc-* / --bs-* tokens, adjust the selectors below to match.
    -->
    <style>
      :root{
        --evenza-bg:#0D1117;
        --evenza-card:#161B22;
        --evenza-border:rgba(255,255,255,0.07);
        --evenza-accent:#22C55E;
        --evenza-accent-soft:rgba(34,197,94,0.12);
        --evenza-accent-glow:rgba(34,197,94,0.32);
        --evenza-danger:#F85149;
        --evenza-danger-soft:rgba(248,81,73,0.12);
        --evenza-warning:#E3B341;
        --evenza-warning-soft:rgba(227,179,65,0.12);
        --evenza-info:#58A6FF;
        --evenza-info-soft:rgba(88,166,255,0.12);
        --evenza-violet:#A371F7;
        --evenza-violet-soft:rgba(163,113,247,0.12);
      }

      [data-pc-theme="dark"] body{ background:var(--evenza-bg); }
      [data-pc-theme="dark"] .pc-container{ background:var(--evenza-bg); }
      [data-pc-theme="dark"] .card{
        background:var(--evenza-card);
        border:1px solid var(--evenza-border);
        border-radius:14px;
        box-shadow:none;
      }
      [data-pc-theme="dark"] .card-header{ border-bottom:1px solid var(--evenza-border); }
      [data-pc-theme="dark"] .table-responsive table thead th{
        color:#8B949E; font-size:11px; text-transform:uppercase; letter-spacing:.06em; font-weight:500;
        border-bottom:1px solid var(--evenza-border);
      }
      [data-pc-theme="dark"] .table-responsive table td{ border-color:rgba(255,255,255,0.04); }

      .evenza-eyebrow{
        font-size:11px; letter-spacing:.12em; text-transform:uppercase;
        color:var(--evenza-accent); display:flex; align-items:center; gap:8px; margin-bottom:4px;
      }
      .evenza-eyebrow .dot{
        width:6px; height:6px; border-radius:50%; background:var(--evenza-accent);
        box-shadow:0 0 8px var(--evenza-accent-glow); animation:evenzaPulse 2.2s infinite;
      }
      @keyframes evenzaPulse{
        0%,100%{opacity:1; transform:scale(1);}
        50%{opacity:.4; transform:scale(1.5);}
      }

      .evenza-kpi-card{ transition:transform .2s ease, box-shadow .2s ease, border-color .2s ease; height:100%; }
      .evenza-kpi-card:hover{
        transform:translateY(-3px);
        border-color:rgba(34,197,94,0.35) !important;
        box-shadow:0 10px 26px -14px rgba(0,0,0,0.6);
      }
      .evenza-icon-box{
        width:44px; height:44px; border-radius:11px;
        display:flex; align-items:center; justify-content:center;
      }
      .evenza-ic-green{ background:var(--evenza-accent-soft); color:var(--evenza-accent); }
      .evenza-ic-blue{ background:var(--evenza-info-soft); color:var(--evenza-info); }
      .evenza-ic-amber{ background:var(--evenza-warning-soft); color:var(--evenza-warning); }
      .evenza-ic-violet{ background:var(--evenza-violet-soft); color:var(--evenza-violet); }
      .evenza-ic-red{ background:var(--evenza-danger-soft); color:var(--evenza-danger); }

      .evenza-trend-up{ color:var(--evenza-accent); font-size:13px; }
      .evenza-trend-down{ color:var(--evenza-danger); font-size:13px; }

      .evenza-bar-track{ background:#0D1117; }
      .evenza-bar-accent{ background:var(--evenza-accent); box-shadow:0 6px 14px -6px var(--evenza-accent-glow); }
      .evenza-bar-danger{ background:var(--evenza-danger); }
      .evenza-bar-info{ background:var(--evenza-info); }
      .evenza-bar-violet{ background:var(--evenza-violet); }
      .evenza-bar-amber{ background:var(--evenza-warning); }

      .evenza-insight-card{ transition:transform .2s ease, border-color .2s ease; height:100%; }
      .evenza-insight-card:hover{ transform:translateY(-2px); border-color:rgba(34,197,94,0.3) !important; }
      .evenza-insight-k{ font-size:11px; text-transform:uppercase; letter-spacing:.06em; color:#8B949E; }

      .evenza-badge{
        font-size:11px; font-weight:600; padding:4px 10px; border-radius:20px;
        display:inline-flex; align-items:center; gap:5px; color:#fff;
      }
      .evenza-badge.confirmed, .evenza-badge.success{ background:rgba(34,197,94,0.16); color:var(--evenza-accent); }
      .evenza-badge.pending{ background:rgba(227,179,65,0.16); color:var(--evenza-warning); }
      .evenza-badge.failed{ background:rgba(248,81,73,0.16); color:var(--evenza-danger); }

      .evenza-chart-box{ position:relative; width:100%; }
      .evenza-filter-label{
        font-size:10.5px; text-transform:uppercase; letter-spacing:.06em; color:#8B949E; margin-bottom:6px; display:block;
      }
      [data-pc-theme="dark"] .form-control,
      [data-pc-theme="dark"] .form-select{
        background:#0D1117; border-color:var(--evenza-border); color:#E6EDF3;
      }
      [data-pc-theme="dark"] .form-control:focus,
      [data-pc-theme="dark"] .form-select:focus{
        border-color:var(--evenza-accent); box-shadow:0 0 0 3px var(--evenza-accent-soft);
      }
      .btn-evenza-accent{
        background:var(--evenza-accent); border:1px solid var(--evenza-accent); color:#04170C; font-weight:600;
      }
      .btn-evenza-accent:hover{ background:#2FDC70; color:#04170C; box-shadow:0 0 18px var(--evenza-accent-glow); }
    </style>
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
    <div class="pc-container">
      <div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
          <div class="page-block">
            <div class="flex items-center justify-between gap-3 flex-wrap">
              <div>
                <div class="page-header-title">
                  <h5 class="mb-0 font-medium">Reports &amp; Analytics</h5>
                </div>
                <p class="text-muted mb-0 text-[13px]">Overview of platform performance</p>
              </div>
              <div class="flex items-center gap-2 flex-wrap">
                <input type="text" class="form-control" style="max-width:210px" value="Jun 12 – Jul 11, 2026" readonly />
                <button type="button" class="btn btn-light-secondary d-flex align-items-center gap-2">
                  <i class="ti ti-download"></i> Export
                </button>
                <button type="button" class="btn btn-evenza-accent d-flex align-items-center gap-2">
                  <i class="ti ti-adjustments"></i> Filter
                </button>
              </div>
            </div>
            <ul class="breadcrumb mt-3">
              <li class="breadcrumb-item"><a href="Dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="javascript: void(0)">Dashboard</a></li>
              <li class="breadcrumb-item" aria-current="page">Reports &amp; Analytics</li>
            </ul>
          </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <!-- [ KPI cards ] start -->
        <div class="grid grid-cols-12 gap-x-6 gap-y-6">

          <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="card evenza-kpi-card">
              <div class="card-body">
                <div class="flex items-center justify-between gap-3 mb-4">
                  <div class="evenza-icon-box evenza-ic-green"><i class="ti ti-users text-[20px]"></i></div>
                  <h6 class="mb-0 flex items-center gap-1 evenza-trend-up"><i class="ti ti-arrow-up-right"></i>12.4%</h6>
                </div>
                <h3 class="font-light mb-1">18,204</h3>
                <p class="text-muted mb-3">Total Students</p>
                <div class="w-full evenza-bar-track rounded-lg h-1.5">
                  <div class="evenza-bar-accent h-full rounded-lg" role="progressbar" style="width: 72%"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="card evenza-kpi-card">
              <div class="card-body">
                <div class="flex items-center justify-between gap-3 mb-4">
                  <div class="evenza-icon-box evenza-ic-blue"><i class="ti ti-calendar-event text-[20px]"></i></div>
                  <h6 class="mb-0 flex items-center gap-1 evenza-trend-up"><i class="ti ti-arrow-up-right"></i>4.1%</h6>
                </div>
                <h3 class="font-light mb-1">312</h3>
                <p class="text-muted mb-3">Total Events</p>
                <div class="w-full evenza-bar-track rounded-lg h-1.5">
                  <div class="evenza-bar-info h-full rounded-lg" role="progressbar" style="width: 55%"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="card evenza-kpi-card">
              <div class="card-body">
                <div class="flex items-center justify-between gap-3 mb-4">
                  <div class="evenza-icon-box evenza-ic-violet"><i class="ti ti-ticket text-[20px]"></i></div>
                  <h6 class="mb-0 flex items-center gap-1 evenza-trend-up"><i class="ti ti-arrow-up-right"></i>9.8%</h6>
                </div>
                <h3 class="font-light mb-1">42,981</h3>
                <p class="text-muted mb-3">Total Registrations</p>
                <div class="w-full evenza-bar-track rounded-lg h-1.5">
                  <div class="evenza-bar-violet h-full rounded-lg" role="progressbar" style="width: 80%"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="card evenza-kpi-card">
              <div class="card-body">
                <div class="flex items-center justify-between gap-3 mb-4">
                  <div class="evenza-icon-box evenza-ic-green"><i class="ti ti-currency-rupee text-[20px]"></i></div>
                  <h6 class="mb-0 flex items-center gap-1 evenza-trend-up"><i class="ti ti-arrow-up-right"></i>18.2%</h6>
                </div>
                <h3 class="font-light mb-1">₹86.4L</h3>
                <p class="text-muted mb-3">Total Revenue</p>
                <div class="w-full evenza-bar-track rounded-lg h-1.5">
                  <div class="evenza-bar-accent h-full rounded-lg" role="progressbar" style="width: 88%"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="card evenza-kpi-card">
              <div class="card-body">
                <div class="flex items-center justify-between gap-3 mb-4">
                  <div class="evenza-icon-box evenza-ic-amber"><i class="ti ti-qrcode text-[20px]"></i></div>
                  <h6 class="mb-0 flex items-center gap-1 evenza-trend-up"><i class="ti ti-arrow-up-right"></i>6.7%</h6>
                </div>
                <h3 class="font-light mb-1">39,660</h3>
                <p class="text-muted mb-3">Passes Issued</p>
                <div class="w-full evenza-bar-track rounded-lg h-1.5">
                  <div class="evenza-bar-amber h-full rounded-lg" role="progressbar" style="width: 65%"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="card evenza-kpi-card">
              <div class="card-body">
                <div class="flex items-center justify-between gap-3 mb-4">
                  <div class="evenza-icon-box evenza-ic-green"><i class="ti ti-checks text-[20px]"></i></div>
                  <h6 class="mb-0 flex items-center gap-1 evenza-trend-down"><i class="ti ti-arrow-down-right"></i>2.3%</h6>
                </div>
                <h3 class="font-light mb-1">31,204</h3>
                <p class="text-muted mb-3">Passes Used</p>
                <div class="w-full evenza-bar-track rounded-lg h-1.5">
                  <div class="evenza-bar-danger h-full rounded-lg" role="progressbar" style="width: 47%"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="card evenza-kpi-card">
              <div class="card-body">
                <div class="flex items-center justify-between gap-3 mb-4">
                  <div class="evenza-icon-box evenza-ic-blue"><i class="ti ti-building text-[20px]"></i></div>
                  <h6 class="mb-0 flex items-center gap-1 evenza-trend-up"><i class="ti ti-arrow-up-right"></i>3.5%</h6>
                </div>
                <h3 class="font-light mb-1">148</h3>
                <p class="text-muted mb-3">Active Colleges</p>
                <div class="w-full evenza-bar-track rounded-lg h-1.5">
                  <div class="evenza-bar-info h-full rounded-lg" role="progressbar" style="width: 60%"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="card evenza-kpi-card">
              <div class="card-body">
                <div class="flex items-center justify-between gap-3 mb-4">
                  <div class="evenza-icon-box evenza-ic-red"><i class="ti ti-x text-[20px]"></i></div>
                  <h6 class="mb-0 flex items-center gap-1 evenza-trend-down"><i class="ti ti-arrow-down-right"></i>1.1%</h6>
                </div>
                <h3 class="font-light mb-1">1,842</h3>
                <p class="text-muted mb-3">Cancelled Registrations</p>
                <div class="w-full evenza-bar-track rounded-lg h-1.5">
                  <div class="evenza-bar-danger h-full rounded-lg" role="progressbar" style="width: 22%"></div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <!-- [ KPI cards ] end -->

        <!-- [ Charts row 1 ] start -->
        <div class="grid grid-cols-12 gap-x-6 gap-y-6 mt-6">
          <div class="col-span-12 xl:col-span-7">
            <div class="card">
              <div class="card-header !pb-0 !border-b-0">
                <h5>Registrations Over Time</h5>
                <p class="text-muted mb-0 text-[13px]">Daily registrations, last 30 days</p>
              </div>
              <div class="card-body">
                <div class="evenza-chart-box" style="height:300px;">
                  <canvas id="lineChart"></canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="col-span-12 xl:col-span-5">
            <div class="card">
              <div class="card-header !pb-0 !border-b-0">
                <h5>Pass Usage</h5>
                <p class="text-muted mb-0 text-[13px]">Issued vs. checked-in</p>
              </div>
              <div class="card-body flex flex-col items-center">
                <div class="evenza-chart-box flex items-center justify-center" style="height:230px; width:230px;">
                  <canvas id="donutChart"></canvas>
                </div>
                <div class="flex items-center gap-4 mt-3">
                  <span class="flex items-center gap-2 text-[12px] text-muted"><span style="width:8px;height:8px;border-radius:50%;background:var(--evenza-accent);display:inline-block;"></span>Used · 78.7%</span>
                  <span class="flex items-center gap-2 text-[12px] text-muted"><span style="width:8px;height:8px;border-radius:50%;background:#232B36;display:inline-block;"></span>Unused · 21.3%</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- [ Charts row 1 ] end -->

        <!-- [ Charts row 2 ] start -->
        <div class="grid grid-cols-12 gap-x-6 gap-y-6 mt-6">
          <div class="col-span-12 xl:col-span-6">
            <div class="card">
              <div class="card-header !pb-0 !border-b-0">
                <h5>Revenue Trend</h5>
                <p class="text-muted mb-0 text-[13px]">Monthly revenue, ₹ in lakhs</p>
              </div>
              <div class="card-body">
                <div class="evenza-chart-box" style="height:270px;">
                  <canvas id="barChart"></canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="col-span-12 xl:col-span-6">
            <div class="card">
              <div class="card-header !pb-0 !border-b-0">
                <h5>Top Events</h5>
                <p class="text-muted mb-0 text-[13px]">By total registrations</p>
              </div>
              <div class="card-body">
                <div class="evenza-chart-box" style="height:270px;">
                  <canvas id="hBarChart"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- [ Charts row 2 ] end -->

        <!-- [ Pie + Insights ] start -->
        <div class="grid grid-cols-12 gap-x-6 gap-y-6 mt-6">
          <div class="col-span-12 xl:col-span-4">
            <div class="card">
              <div class="card-header !pb-0 !border-b-0">
                <h5>Team vs Solo</h5>
                <p class="text-muted mb-0 text-[13px]">Participation format split</p>
              </div>
              <div class="card-body flex flex-col items-center">
                <div class="evenza-chart-box flex items-center justify-center" style="height:220px; width:220px;">
                  <canvas id="pieChart"></canvas>
                </div>
                <div class="flex items-center gap-4 mt-3">
                  <span class="flex items-center gap-2 text-[12px] text-muted"><span style="width:8px;height:8px;border-radius:50%;background:var(--evenza-accent);display:inline-block;"></span>Team · 64%</span>
                  <span class="flex items-center gap-2 text-[12px] text-muted"><span style="width:8px;height:8px;border-radius:50%;background:var(--evenza-info);display:inline-block;"></span>Solo · 36%</span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-span-12 xl:col-span-8">
            <h6 class="text-muted mb-3 uppercase text-[11px] tracking-wide">Insights</h6>
            <div class="grid grid-cols-12 gap-x-6 gap-y-6">
              <div class="col-span-12 sm:col-span-6">
                <div class="card evenza-insight-card">
                  <div class="card-body flex items-center gap-3">
                    <div class="evenza-icon-box evenza-ic-green shrink-0"><i class="ti ti-trophy text-[18px]"></i></div>
                    <div>
                      <p class="evenza-insight-k mb-1">Top Performing Event</p>
                      <h6 class="mb-1">TechnoVerse 2026</h6>
                      <p class="text-muted mb-0 text-[12px]">3,842 registrations · ₹9.2L revenue</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-span-12 sm:col-span-6">
                <div class="card evenza-insight-card">
                  <div class="card-body flex items-center gap-3">
                    <div class="evenza-icon-box evenza-ic-blue shrink-0"><i class="ti ti-building text-[18px]"></i></div>
                    <div>
                      <p class="evenza-insight-k mb-1">Most Active College</p>
                      <h6 class="mb-1">SRM Institute of Tech</h6>
                      <p class="text-muted mb-0 text-[12px]">1,204 students engaged</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-span-12 sm:col-span-6">
                <div class="card evenza-insight-card">
                  <div class="card-body flex items-center gap-3">
                    <div class="evenza-icon-box evenza-ic-amber shrink-0"><i class="ti ti-clock text-[18px]"></i></div>
                    <div>
                      <p class="evenza-insight-k mb-1">Peak Registration Time</p>
                      <h6 class="mb-1">7:00 PM – 9:00 PM</h6>
                      <p class="text-muted mb-0 text-[12px]">31% of daily volume</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-span-12 sm:col-span-6">
                <div class="card evenza-insight-card">
                  <div class="card-body flex items-center gap-3">
                    <div class="evenza-icon-box evenza-ic-violet shrink-0"><i class="ti ti-chart-line text-[18px]"></i></div>
                    <div>
                      <p class="evenza-insight-k mb-1">Conversion Rate</p>
                      <h6 class="mb-1">67.8%</h6>
                      <p class="text-muted mb-0 text-[12px]">Visits → confirmed registration</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- [ Pie + Insights ] end -->

        <!-- [ Filters ] start -->
        <div class="grid grid-cols-12 mt-6">
          <div class="col-span-12">
            <h6 class="text-muted mb-3 uppercase text-[11px] tracking-wide">Filters</h6>
            <div class="card">
              <div class="card-body">
                <div class="grid grid-cols-12 gap-x-4 gap-y-3 items-end">
                  <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                    <label class="evenza-filter-label">Date</label>
                    <input type="text" class="form-control" value="Jul 11, 2026" placeholder="Select date" />
                  </div>
                  <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                    <label class="evenza-filter-label">Event</label>
                    <select class="form-select">
                      <option>All Events</option>
                      <option>TechnoVerse 2026</option>
                      <option>CodeSprint Finals</option>
                      <option>Design Circuit</option>
                      <option>Startup Meet</option>
                    </select>
                  </div>
                  <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                    <label class="evenza-filter-label">College</label>
                    <select class="form-select">
                      <option>All Colleges</option>
                      <option>SRM Institute of Tech</option>
                      <option>VIT Chennai</option>
                      <option>NIT Surat</option>
                      <option>PDPU</option>
                    </select>
                  </div>
                  <div class="col-span-12 sm:col-span-4 xl:col-span-2">
                    <label class="evenza-filter-label">Status</label>
                    <select class="form-select">
                      <option>All Status</option>
                      <option>Confirmed</option>
                      <option>Pending</option>
                      <option>Failed</option>
                    </select>
                  </div>
                  <div class="col-span-12 sm:col-span-2 xl:col-span-1">
                    <button type="button" class="btn btn-evenza-accent w-full"><i class="ti ti-filter"></i></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- [ Filters ] end -->

        <!-- [ Tables ] start -->
        <div class="grid grid-cols-12 gap-x-6 gap-y-6 mt-6">
          <div class="col-span-12 xl:col-span-7">
            <div class="card table-card">
              <div class="card-header">
                <h5>Recent Registrations</h5>
                <p class="text-muted mb-0 text-[13px]">Latest sign-ups across events</p>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Event</th>
                        <th>Status</th>
                        <th>Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          <div class="flex items-center gap-2">
                            <img class="rounded-full max-w-10" style="width: 32px" src="assets/images/user/avatar-1.jpg" alt="avatar" />
                            <h6 class="mb-0">Aarav Rao</h6>
                          </div>
                        </td>
                        <td>TechnoVerse 2026</td>
                        <td><span class="evenza-badge confirmed">Confirmed</span></td>
                        <td class="text-muted">Jul 11, 2026</td>
                      </tr>
                      <tr>
                        <td>
                          <div class="flex items-center gap-2">
                            <img class="rounded-full max-w-10" style="width: 32px" src="assets/images/user/avatar-2.jpg" alt="avatar" />
                            <h6 class="mb-0">Sneha Patel</h6>
                          </div>
                        </td>
                        <td>Design Circuit</td>
                        <td><span class="evenza-badge pending">Pending</span></td>
                        <td class="text-muted">Jul 11, 2026</td>
                      </tr>
                      <tr>
                        <td>
                          <div class="flex items-center gap-2">
                            <img class="rounded-full max-w-10" style="width: 32px" src="assets/images/user/avatar-3.jpg" alt="avatar" />
                            <h6 class="mb-0">Kabir Joshi</h6>
                          </div>
                        </td>
                        <td>CodeSprint Finals</td>
                        <td><span class="evenza-badge confirmed">Confirmed</span></td>
                        <td class="text-muted">Jul 10, 2026</td>
                      </tr>
                      <tr>
                        <td>
                          <div class="flex items-center gap-2">
                            <img class="rounded-full max-w-10" style="width: 32px" src="assets/images/user/avatar-1.jpg" alt="avatar" />
                            <h6 class="mb-0">Meera Iyer</h6>
                          </div>
                        </td>
                        <td>Startup Meet</td>
                        <td><span class="evenza-badge failed">Failed</span></td>
                        <td class="text-muted">Jul 10, 2026</td>
                      </tr>
                      <tr>
                        <td>
                          <div class="flex items-center gap-2">
                            <img class="rounded-full max-w-10" style="width: 32px" src="assets/images/user/avatar-2.jpg" alt="avatar" />
                            <h6 class="mb-0">Rohan Verma</h6>
                          </div>
                        </td>
                        <td>TechnoVerse 2026</td>
                        <td><span class="evenza-badge confirmed">Confirmed</span></td>
                        <td class="text-muted">Jul 09, 2026</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <div class="col-span-12 xl:col-span-5">
            <div class="card table-card">
              <div class="card-header">
                <h5>Recent Payments</h5>
                <p class="text-muted mb-0 text-[13px]">Latest transactions</p>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Txn ID</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Event</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="text-muted">TXN-88421</td>
                        <td>₹1,200</td>
                        <td><span class="evenza-badge success">Success</span></td>
                        <td>TechnoVerse</td>
                      </tr>
                      <tr>
                        <td class="text-muted">TXN-88420</td>
                        <td>₹800</td>
                        <td><span class="evenza-badge pending">Pending</span></td>
                        <td>Design Circuit</td>
                      </tr>
                      <tr>
                        <td class="text-muted">TXN-88419</td>
                        <td>₹1,500</td>
                        <td><span class="evenza-badge success">Success</span></td>
                        <td>CodeSprint</td>
                      </tr>
                      <tr>
                        <td class="text-muted">TXN-88418</td>
                        <td>₹600</td>
                        <td><span class="evenza-badge failed">Failed</span></td>
                        <td>Startup Meet</td>
                      </tr>
                      <tr>
                        <td class="text-muted">TXN-88417</td>
                        <td>₹1,200</td>
                        <td><span class="evenza-badge success">Success</span></td>
                        <td>TechnoVerse</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- [ Tables ] end -->

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

    <!-- Chart.js (page-scoped) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
      (function () {
        Chart.defaults.font.family = getComputedStyle(document.body).fontFamily;
        Chart.defaults.color = '#8B949E';

        var accent = '#22C55E';
        var accentSoft = 'rgba(34,197,94,0.18)';
        var info = '#58A6FF';
        var violet = '#A371F7';
        var warning = '#E3B341';
        var grid = 'rgba(255,255,255,0.05)';
        var track = '#232B36';

        function fade(ctx, area, c1, c2) {
          var g = ctx.createLinearGradient(0, area.top, 0, area.bottom);
          g.addColorStop(0, c1);
          g.addColorStop(1, c2);
          return g;
        }

        // Registrations over time
        new Chart(document.getElementById('lineChart'), {
          type: 'line',
          data: {
            labels: ['Jun 12', 'Jun 15', 'Jun 18', 'Jun 21', 'Jun 24', 'Jun 27', 'Jun 30', 'Jul 3', 'Jul 6', 'Jul 9', 'Jul 11'],
            datasets: [{
              label: 'Registrations',
              data: [420, 510, 480, 610, 590, 720, 680, 810, 760, 890, 940],
              borderColor: accent,
              borderWidth: 2.4,
              pointRadius: 3,
              pointBackgroundColor: '#161B22',
              pointBorderColor: accent,
              pointBorderWidth: 2,
              tension: .4,
              fill: true,
              backgroundColor: function (ctx) {
                var chart = ctx.chart, area = chart.chartArea;
                if (!area) return 'transparent';
                return fade(chart.ctx, area, accentSoft, 'rgba(34,197,94,0)');
              }
            }]
          },
          options: {
            responsive: true, maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
              legend: { display: false },
              tooltip: { backgroundColor: '#1B222B', borderColor: '#232B36', borderWidth: 1, titleColor: '#E6EDF3', bodyColor: '#8B949E', padding: 10, cornerRadius: 8, displayColors: false }
            },
            scales: {
              x: { grid: { color: grid }, border: { display: false } },
              y: { grid: { color: grid }, border: { display: false } }
            }
          }
        });

        // Pass usage donut
        new Chart(document.getElementById('donutChart'), {
          type: 'doughnut',
          data: {
            labels: ['Used', 'Unused'],
            datasets: [{ data: [78.7, 21.3], backgroundColor: [accent, track], borderColor: '#161B22', borderWidth: 4 }]
          },
          options: {
            responsive: true, maintainAspectRatio: false, cutout: '72%',
            plugins: {
              legend: { display: false },
              tooltip: { backgroundColor: '#1B222B', borderColor: '#232B36', borderWidth: 1, titleColor: '#E6EDF3', bodyColor: '#8B949E', padding: 10, cornerRadius: 8 }
            }
          },
          plugins: [{
            id: 'centerText',
            afterDraw: function (chart) {
              var ctx = chart.ctx, area = chart.chartArea;
              if (!area) return;
              var cx = area.left + area.width / 2, cy = area.top + area.height / 2;
              ctx.save();
              ctx.font = "700 20px " + getComputedStyle(document.body).fontFamily;
              ctx.fillStyle = '#E6EDF3';
              ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
              ctx.fillText('78.7%', cx, cy - 8);
              ctx.font = "500 11px " + getComputedStyle(document.body).fontFamily;
              ctx.fillStyle = '#8B949E';
              ctx.fillText('Checked-in', cx, cy + 14);
              ctx.restore();
            }
          }]
        });

        // Revenue trend bar
        new Chart(document.getElementById('barChart'), {
          type: 'bar',
          data: {
            labels: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
              label: 'Revenue (₹L)',
              data: [9.2, 11.4, 10.1, 14.8, 16.3, 18.9],
              backgroundColor: function (ctx) {
                var chart = ctx.chart, area = chart.chartArea;
                if (!area) return accent;
                return fade(chart.ctx, area, accent, '#16803C');
              },
              borderRadius: 6, maxBarThickness: 34
            }]
          },
          options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
              legend: { display: false },
              tooltip: { backgroundColor: '#1B222B', borderColor: '#232B36', borderWidth: 1, titleColor: '#E6EDF3', bodyColor: '#8B949E', padding: 10, cornerRadius: 8, displayColors: false }
            },
            scales: {
              x: { grid: { display: false }, border: { display: false } },
              y: { grid: { color: grid }, border: { display: false } }
            }
          }
        });

        // Top events horizontal bar
        new Chart(document.getElementById('hBarChart'), {
          type: 'bar',
          data: {
            labels: ['TechnoVerse 2026', 'CodeSprint Finals', 'Design Circuit', 'Startup Meet', 'Robo Rumble'],
            datasets: [{
              label: 'Registrations',
              data: [3842, 3110, 2540, 2065, 1720],
              backgroundColor: [accent, '#2FDC70', info, violet, warning],
              borderRadius: 6, maxBarThickness: 20
            }]
          },
          options: {
            indexAxis: 'y',
            responsive: true, maintainAspectRatio: false,
            plugins: {
              legend: { display: false },
              tooltip: { backgroundColor: '#1B222B', borderColor: '#232B36', borderWidth: 1, titleColor: '#E6EDF3', bodyColor: '#8B949E', padding: 10, cornerRadius: 8, displayColors: false }
            },
            scales: {
              x: { grid: { color: grid }, border: { display: false } },
              y: { grid: { display: false }, border: { display: false } }
            }
          }
        });

        // Team vs solo pie
        new Chart(document.getElementById('pieChart'), {
          type: 'pie',
          data: {
            labels: ['Team', 'Solo'],
            datasets: [{ data: [64, 36], backgroundColor: [accent, info], borderColor: '#161B22', borderWidth: 3 }]
          },
          options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
              legend: { display: false },
              tooltip: { backgroundColor: '#1B222B', borderColor: '#232B36', borderWidth: 1, titleColor: '#E6EDF3', bodyColor: '#8B949E', padding: 10, cornerRadius: 8 }
            }
          }
        });
      })();
    </script>

    <div class="floting-button fixed bottom-[50px] right-[30px] z-[1030]"></div>

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