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
        <li class="pc-item pc-caption">
          <label>University Management</label>
          <i data-feather="feather"></i>
        </li>

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
            <li class="pc-item">
              <a href="adduniversity.php" class="pc-link">Add University</a>
            </li>
            <li class="pc-item">
              <a href="universitystatus.php" class="pc-link">University Status</a>
            </li>
          </ul>
        </li>

        <!-- College -->
        <li class="pc-item pc-caption">
          <label>College Management</label>
          <i data-feather="feather"></i>
        </li>

        <li class="pc-item pc-hasmenu">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i data-feather="home"></i></span>
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
            <li class="pc-item">
              <a href="college_status.php" class="pc-link">College Status</a>
            </li>
          </ul>
        </li>
        <!-- Category Management -->
        <!-- Student Management -->
        <li class="pc-item pc-caption">
          <label>Student Management</label>
          <i data-feather="users"></i>
        </li>

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

            <li class="pc-item">
              <a href="Pending.php" class="pc-link">
                Pending Verification
              </a>
            </li>

            <li class="pc-item">
              <a href="VerifiedStudents.php" class="pc-link">
                Verified Students
              </a>
            </li>

            <li class="pc-item">
              <a href="RejectedStudents.php" class="pc-link">
                Rejected Students
              </a>
            </li>

            <li class="pc-item">
              <a href="BlockedStudents.php" class="pc-link">
                Blocked Students
              </a>
            </li>

          </ul>
        </li>
        <li class="pc-item pc-caption">
          <label>Category Management</label>
          <i data-feather="feather"></i>
        </li>

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
            <li class="pc-item">
              <a href="category_status.php" class="pc-link">Active / Inactive Categories</a>
            </li>
          </ul>
        </li>
        <!-- Auth -->
        <li class="pc-item pc-caption">
          <label>Event Management</label>
          <i data-feather="feather"></i>
        </li>

        <li class="pc-item pc-hasmenu">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i data-feather="grid"></i></span>
            <span class="pc-mtext">Event Categories</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>

          <ul class="pc-submenu">
            <li class="pc-item">
              <a href="all_events.php" class="pc-link">All Events</a>
            </li>
            <li class="pc-item">
              <a href="pending_events.php" class="pc-link">Pending Events</a>
            </li>
            <li class="pc-item">
              <a href="published_events.php" class="pc-link">Published Events</a>
            </li>
            <li class="pc-item">
              <a href="completed_events.php" class="pc-link">Completed Events</a>
            </li>
            <li class="pc-item">
              <a href="cancelled_events.php" class="pc-link">Cancelled Events</a>
            </li>
          </ul>
        </li>
        <li class="pc-item pc-caption">
          <label>Registration Management</label>
          <i data-feather="feather"></i>
        </li>

        <li class="pc-item pc-hasmenu">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i data-feather="grid"></i></span>
            <span class="pc-mtext">Registration Category</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>

          <ul class="pc-submenu">
            <li class="pc-item">
              <a href="allregistrations.php" class="pc-link">All Registrations</a>
            </li>
            <li class="pc-item">
              <a href="soloregistrations.php" class="pc-link">Solo Registrations</a>
            </li>
            <li class="pc-item">
              <a href="teamregistrations.php" class="pc-link">Team Registrations</a>
            </li>
            <li class="pc-item">
              <a href="pendingregistrations.php" class="pc-link">Pending Approvals</a>
            </li>
            <li class="pc-item">
              <a href="cancelledregistrations.php" class="pc-link">Cancelled Registration</a>
            </li>
          </ul>
        </li>
        <li class="pc-item pc-caption">
          <label>Team Management</label>
          <i data-feather="feather"></i>
        </li>
        <li class="pc-item pc-hasmenu">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i data-feather="grid"></i></span>
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
        <li class="pc-item pc-caption">
          <label>Notifications</label>
          <i data-feather="feather"></i>
        </li>
        <li class="pc-item pc-hasmenu">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i data-feather="grid"></i></span>
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
        <li class="pc-item pc-caption">
          <label>Admin Settings</label>
          <i data-feather="feather"></i>
        </li>
        <li class="pc-item pc-hasmenu">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i data-feather="grid"></i></span>
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


        <!-- Auth -->
        <li class="pc-item pc-caption">
          <label>Payment Management</label>
          <i data-feather="feather"></i>
        </li>

        <li class="pc-item pc-hasmenu">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i data-feather="grid"></i></span>
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


        <!-- Auth -->
        <li class="pc-item">
          <a href="login-v1.html" class="pc-link" target="_blank">
            <span class="pc-micon"><i data-feather="lock"></i></span>
            <span class="pc-mtext">Login</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="register-v1.html" class="pc-link" target="_blank">
            <span class="pc-micon"><i data-feather="user-plus"></i></span>
            <span class="pc-mtext">Register</span>
          </a>
        </li>
        
        <!-- Other -->
        <li class="pc-item pc-caption">
          <label>Other</label>
          <i data-feather="sidebar"></i>
        </li>

        <li class="pc-item pc-hasmenu">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i data-feather="align-right"></i></span>
            <span class="pc-mtext">Menu Levels</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>

          <ul class="pc-submenu">
            <li class="pc-item"><a class="pc-link" href="javascript:void(0)">Level 2.1</a></li>

            <li class="pc-item pc-hasmenu">
              <a href="javascript:void(0)" class="pc-link">
                Level 2.2 <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
              </a>
              <ul class="pc-submenu">
                <li class="pc-item"><a class="pc-link" href="javascript:void(0)">Level 3.1</a></li>
                <li class="pc-item"><a class="pc-link" href="javascript:void(0)">Level 3.2</a></li>
              </ul>
            </li>
          </ul>
        </li>

        <!-- Sample -->
        <li class="pc-item">
          <a href="../other/sample-page.html" class="pc-link">
            <span class="pc-micon"><i data-feather="sidebar"></i></span>
            <span class="pc-mtext">Sample Page</span>
          </a>
        </li>

      </ul>
    </div>
  </div>
</nav>
