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

        <!-- Navigation -->
        <li class="pc-item pc-caption">
          <label>Navigation</label>
        </li>

        <li class="pc-item">
          <a href="Dashboard.php" class="pc-link">
            <span class="pc-micon"><i data-feather="home"></i></span>
            <span class="pc-mtext">Dashboard</span>
          </a>
        </li>

        <!-- University -->

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

        <!-- College -->

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
            <li class="pc-item">
              <a href="add_colleges.php" class="pc-link">Add College</a>
            </li>
          </ul>
        </li>
        
        <!-- Student Management -->
        

        <li class="pc-item pc-hasmenu">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i data-feather="users"></i></span>
            <span class="pc-mtext">Student</span>
            <span class="pc-arrow">
              <i class="ti ti-chevron-right"></i>
            </span>
          </a>

          <ul class="pc-submenu">

            <li class="pc-item">
              <a href="AllStudents.php" class="pc-link">
                All Students
              </a>
            </li>

            <li class="pc-item">
              <a href="AddStudent.php" class="pc-link">
                Add Student
              </a>
            </li>
          </ul>
        </li>
        
        <!-- Category Management -->

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
            <li class="pc-item">
              <a href="add_category.php" class="pc-link">Add Category</a>
            </li>
          </ul>
        </li>
        <!-- Event Categories -->
        <li class="pc-item pc-hasmenu">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i data-feather="calendar"></i></span>
            <span class="pc-mtext">Event Categories</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>

          <ul class="pc-submenu">
            <li class="pc-item">
              <a href="all_events.php" class="pc-link">All Events</a>
            </li>
          </ul>
        </li>
        
        <!-- Registration Category -->

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


        <!-- Notifications -->
        

        <li class="pc-item pc-hasmenu">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i data-feather="bell"></i></span>
            <span class="pc-mtext">Notification Settings</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>

          <ul class="pc-submenu">
            <li class="pc-item">
              <a href="send_notifications.php" class="pc-link">Send Notifications</a>
            </li>
            <li class="pc-item">
              <a href="notification_history.php" class="pc-link">Notification History</a>
            </li>
          </ul>
        </li>

        <!-- Admin Settings -->
        
        
        <li class="pc-item pc-hasmenu">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i data-feather="settings"></i></span>
            <span class="pc-mtext">Admin Settings</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>

          <ul class="pc-submenu">
            <li class="pc-item">
              <a href="profile.php" class="pc-link">Profile</a>
            </li>
            <li class="pc-item">
              <a href="change_password.php" class="pc-link">Change Password</a>
            </li>
            <li class="pc-item">
              <a href="system_settings.php" class="pc-link">System Settings</a>
            </li>
          </ul>
        </li>

        <!--Auth-->

        <!-- Entry Pass Management -->
        

        <li class="pc-item pc-hasmenu">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i data-feather="smartphone"></i></span>
            <span class="pc-mtext">Entry-Passes</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>

          <ul class="pc-submenu">
            <li class="pc-item">
              <a href="generated_passes.php" class="pc-link">Generated Passes</a>
            </li>
            <li class="pc-item">
              <a href="used_passes.php" class="pc-link">Used Passes</a>
            </li>
            <li class="pc-item">
              <a href="unused_passes.php" class="pc-link">Unused Passes</a>
            </li>
            <li class="pc-item">
              <a href="pass_verification.php" class="pc-link">Pass Verification</a>
            </li>
          </ul>          
        </li>

        <!-- Payment Management -->
        

        <li class="pc-item pc-hasmenu">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i data-feather="trending-up"></i></span>
            <span class="pc-mtext">Payments</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>

          <ul class="pc-submenu">
            <li class="pc-item">
              <a href="payment_overview.php" class="pc-link">Payment Overview</a>
            </li>
            <li class="pc-item">
              <a href="successful_payments.php" class="pc-link">Successful Payments</a>
            </li>
            <li class="pc-item">
              <a href="pending_payments.php" class="pc-link">Pending Payments</a>
            </li>
            <li class="pc-item">
              <a href="failed_payments.php" class="pc-link">Failed Payments</a>
            </li>
            <li class="pc-item">
              <a href="transaction_reports.php" class="pc-link">Transaction Reports</a>
            </li>
          </ul>          
        </li>

      </ul>
    </div>
  </div>
</nav>
