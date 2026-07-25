<nav class="pc-sidebar">
  <div class="navbar-wrapper">

    <!-- Logo -->
    <div class="m-header flex items-center py-4 px-6 h-header-height">
      <a href="Index.php" class="b-brand flex items-center gap-3">
        <img src="assets/images/logo-white.svg" alt="logo" />
        <img src="assets/images/favicon.svg" class="img-fluid logo logo-sm" alt="logo" />
      </a>
    </div>

    <!-- Sidebar Content -->
    <div class="navbar-content h-[calc(100vh_-_74px)] py-2.5">
      <ul class="pc-navbar">

        <!-- Navigation Header -->
        <li class="pc-item pc-caption">
          <label>Navigation</label>
        </li>

        <li class="pc-item">
          <a href="Dashboard.php" class="pc-link">
            <span class="pc-micon"><i data-feather="home"></i></span>
            <span class="pc-mtext">Dashboard</span>
          </a>
        </li>

        <!-- University Management -->
        <li class="pc-item pc-hasmenu">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i data-feather="globe"></i></span>
            <span class="pc-mtext">University</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>

          <ul class="pc-submenu">
            <li class="pc-item">
              <a href="alluniversity.php" class="pc-link">All University</a>
            </li>
          </ul>
        </li>

        <!-- College Management -->
        <li class="pc-item pc-hasmenu">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i data-feather="monitor"></i></span>
            <span class="pc-mtext">College</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>

          <ul class="pc-submenu">
            <li class="pc-item">
              <a href="Colleges.php" class="pc-link">All College</a>
            </li>
          </ul>
        </li>
        
        <!-- Student Management -->
        <li class="pc-item pc-hasmenu">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i data-feather="users"></i></span>
            <span class="pc-mtext">Student</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>

          <ul class="pc-submenu">
            <li class="pc-item">
              <a href="AllStudents.php" class="pc-link">All Students</a>
            </li>
          </ul>
        </li>
        
        <!-- Category Management (Cleaned) -->
        <li class="pc-item pc-hasmenu">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i data-feather="grid"></i></span>
            <span class="pc-mtext">Categories</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>

          <ul class="pc-submenu">
            <li class="pc-item">
              <a href="event_cat.php" class="pc-link">Event Categories</a>
            </li>
          </ul>
        </li>

        <!-- Event Management -->
        <li class="pc-item pc-hasmenu">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i data-feather="calendar"></i></span>
            <span class="pc-mtext">Events</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>

          <ul class="pc-submenu">
            <li class="pc-item">
              <a href="all_events.php" class="pc-link">All Events</a>
            </li>
          </ul>
        </li>
        
        <!-- Registration Management -->
        <li class="pc-item pc-hasmenu">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i data-feather="user-plus"></i></span>
            <span class="pc-mtext">Registration Category</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>

          <ul class="pc-submenu">
            <li class="pc-item">
              <a href="allregistrations.php" class="pc-link">All Registrations</a>
            </li>
          </ul>
        </li>

        <!-- Team Category -->
        <li class="pc-item pc-hasmenu">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i data-feather="layers"></i></span>
            <span class="pc-mtext">Team Category</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>

          <ul class="pc-submenu">
            <li class="pc-item">
              <a href="allteams.php" class="pc-link">All Teams</a>
            </li>
            <li class="pc-item">
              <a href="teammembers.php" class="pc-link">Team Members</a>
            </li>
            <li class="pc-item">
              <a href="teamdetails.php" class="pc-link">Team Details</a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
