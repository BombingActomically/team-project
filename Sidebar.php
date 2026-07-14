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
              <a href="alluniversity.php" class="pc-link">
                  <span class="pc-micon">
                    <i class="ti ti-building"></i>
                  </span>
                  <span class="pc-mtext">
                      All University
                  </span>
              </a>
            </li>
            <li class="pc-item">
              <a href="adduniversity.php" class="pc-link">
                  <span class="pc-micon">
                    <i class="ti ti-plus"></i>
                  </span>
                  <span class="pc-mtext">
                      Add University
                  </span>
              </a>
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
              <a href="all_colleges.php" class="pc-link">
                  <span class="pc-micon"><i class="ti ti-school"></i></span>
                  <span class="pc-mtext">All College</span>
              </a>
            </li>

            <li class="pc-item">
              <a href="add_college.php" class="pc-link">
                  <span class="pc-micon">
                    <i class="ti ti-plus"></i>
                  </span>
                  <span class="pc-mtext">
                      Add College
                  </span>
              </a>
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
              <a href="all_students.php" class="pc-link">
                  <span class="pc-micon"><i class="ti ti-users"></i></span>
                  <span class="pc-mtext">All Students</span>
              </a>
            </li>

            <li class="pc-item">
              <a href="add_student.php" class="pc-link">
                  <span class="pc-micon"><i class="ti ti-user-plus"></i></span>
                  <span class="pc-mtext">Add Student</span>
              </a>
            </li>

            <li class="pc-item">
              <a href="pending_students.php" class="pc-link">
                  <span class="pc-micon"><i class="ti ti-clock"></i></span>
                  <span class="pc-mtext">Pending Verification</span>
              </a>
            </li>

            <li class="pc-item">
              <a href="verified_students.php" class="pc-link">
                  <span class="pc-micon"><i class="ti ti-user-check"></i></span>
                  <span class="pc-mtext">Verified Students</span>
              </a>
            </li>

            <li class="pc-item">
              <a href="rejected_students.php" class="pc-link">
                  <span class="pc-micon"><i class="ti ti-user-x"></i></span>
                  <span class="pc-mtext">Rejected Students</span>
              </a>
            </li>

            <li class="pc-item">
              <a href="blocked_students.php" class="pc-link">
                  <span class="pc-micon"><i class="ti ti-user-off"></i></span>
                  <span class="pc-mtext">Blocked Students</span>
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
                <a href="all_categories.php" class="pc-link">
                    <span class="pc-micon">
                        <i class="ti ti-grid-dots"></i>
                    </span>
                    <span class="pc-mtext">
                        Event Categories
                    </span>
                </a>
            </li>

            <li class="pc-item">
                <a href="add_category.php" class="pc-link">
                    <span class="pc-micon">
                        <i class="ti ti-circle-plus"></i>
                    </span>
                    <span class="pc-mtext">
                        Add Category
                    </span>
                </a>
            </li>

            <li class="pc-item">
                <a href="category_status.php" class="pc-link">
                    <span class="pc-micon">
                        <i class="ti ti-toggle-right"></i>
                    </span>
                    <span class="pc-mtext">
                        Active / Inactive Categories
                    </span>
                </a>
            </li>

          </ul>
        </li>
        <!-- Auth -->
        

        <!-- Event Categories -->

        <li class="pc-item pc-hasmenu">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i data-feather="calendar"></i></span>
            <span class="pc-mtext">Event Categories</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>

          <ul class="pc-submenu">
            <li class="pc-item">
              <a href="all_events.php" class="pc-link">
                  <span class="pc-micon"><i class="ti ti-calendar-event"></i></span>
                  <span class="pc-mtext">All Events</span>
              </a>
            </li>

            <li class="pc-item">
                <a href="pending_events.php" class="pc-link">
                    <span class="pc-micon"><i class="ti ti-calendar-time"></i></span>
                    <span class="pc-mtext">Pending Events</span>
                </a>
            </li>

            <li class="pc-item">
              <a href="published_events.php" class="pc-link">
                  <span class="pc-micon">
                      <i class="ti ti-check"></i>
                  </span>
                  <span class="pc-mtext">
                      Published Events
                  </span>
              </a>
            </li>

            <li class="pc-item">
                <a href="completed_events.php" class="pc-link">
                    <span class="pc-micon"><i class="ti ti-calendar-stats"></i></span>
                    <span class="pc-mtext">Completed Events</span>
                </a>
            </li>

            <li class="pc-item">
              <a href="cancelled_events.php" class="pc-link">
                  <span class="pc-micon">
                      <i class="ti ti-x"></i>
                  </span>
                  <span class="pc-mtext">
                      Cancelled Events
                  </span>
              </a>
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
              <a href="all_registrations.php" class="pc-link">
                  <span class="pc-micon"><i class="ti ti-clipboard-list"></i></span>
                  <span class="pc-mtext">All Registrations</span>
              </a>
            </li>

            <li class="pc-item">
                <a href="solo_registrations.php" class="pc-link">
                    <span class="pc-micon"><i class="ti ti-user"></i></span>
                    <span class="pc-mtext">Solo Registrations</span>
                </a>
            </li>

            <li class="pc-item">
              <a href="team_registrations.php" class="pc-link">
                  <span class="pc-micon">
                      <i class="ti ti-users"></i>
                  </span>
                  <span class="pc-mtext">
                      Team Registrations
                  </span>
              </a>
            </li>

            <li class="pc-item">
              <a href="pending_approvals.php" class="pc-link">
                  <span class="pc-micon">
                      <i class="ti ti-clock"></i>
                  </span>
                  <span class="pc-mtext">
                      Pending Approvals
                  </span>
              </a>
            </li>

            <li class="pc-item">
                <a href="cancelled_registrations.php" class="pc-link">
                    <span class="pc-micon"><i class="ti ti-ban"></i></span>
                    <span class="pc-mtext">Cancelled Registration</span>
                </a>
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
              <a href="all_teams.php" class="pc-link">
                  <span class="pc-micon">
                      <i class="ti ti-users"></i>
                  </span>
                  <span class="pc-mtext">
                      All Teams
                  </span>
              </a>
            </li>

            <li class="pc-item">
              <a href="team_members.php" class="pc-link">
                  <span class="pc-micon">
                      <i class="ti ti-user"></i>
                  </span>
                  <span class="pc-mtext">
                      Team Members
                  </span>
              </a>
            </li>

            <li class="pc-item">
              <a href="team_details.php" class="pc-link">
                  <span class="pc-micon">
                      <i class="ti ti-list"></i>
                  </span>
                  <span class="pc-mtext">
                      Team Details
                  </span>
              </a>
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
              <a href="send_notification.php" class="pc-link">
                  <span class="pc-micon"><i class="ti ti-send"></i></span>
                  <span class="pc-mtext">Send Notifications</span>
              </a>
            </li>

            <li class="pc-item">
                <a href="notification_history.php" class="pc-link">
                    <span class="pc-micon"><i class="ti ti-history"></i></span>
                    <span class="pc-mtext">Notification History</span>
                </a>
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
              <a href="profile.php" class="pc-link">
                  <span class="pc-micon">
                      <i class="ti ti-user"></i>
                  </span>
                  <span class="pc-mtext">
                      Profile
                  </span>
              </a>
            </li>

            <li class="pc-item">
              <a href="change_password.php" class="pc-link">
                  <span class="pc-micon">
                      <i class="ti ti-lock"></i>
                  </span>
                  <span class="pc-mtext">
                      Change Password
                  </span>
              </a>
            </li>

            <li class="pc-item">
                <a href="system_settings.php" class="pc-link">
                    <span class="pc-micon"><i class="ti ti-settings"></i></span>
                    <span class="pc-mtext">System Settings</span>
                </a>
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
                <a href="generated_passes.php" class="pc-link">
                    <span class="pc-micon"><i class="ti ti-ticket"></i></span>
                    <span class="pc-mtext">Generated Passes</span>
                </a>
            </li>

            <li class="pc-item">
              <a href="used_passes.php" class="pc-link">
                  <span class="pc-micon">
                      <i class="ti ti-check"></i>
                  </span>
                  <span class="pc-mtext">
                      Used Passes
                  </span>
              </a>
            </li>

            <li class="pc-item">
              <a href="used_passes.php" class="pc-link">
                  <span class="pc-micon">
                      <i class="ti ti-check"></i>
                  </span>
                  <span class="pc-mtext">
                      Used Passes
                  </span>
              </a>
            </li>

            <li class="pc-item">
                <a href="pass_verification.php" class="pc-link">
                    <span class="pc-micon"><i class="ti ti-scan"></i></span>
                    <span class="pc-mtext">Pass Verification</span>
                </a>
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
              <a href="payment_overview.php" class="pc-link">
                  <span class="pc-micon"><i class="ti ti-wallet"></i></span>
                  <span class="pc-mtext">Payment Overview</span>
              </a>
            </li>

        <li class="pc-item">
            <a href="successful_payments.php" class="pc-link">
                <span class="pc-micon"><i class="ti ti-circle-check"></i></span>
                <span class="pc-mtext">Successful Payments</span>
            </a>
        </li>

        <li class="pc-item">
          <a href="pending_payments.php" class="pc-link">
              <span class="pc-micon">
                  <i class="ti ti-clock"></i>
              </span>
              <span class="pc-mtext">
                  Pending Payments
              </span>
          </a>
        </li>

        <li class="pc-item">
            <a href="failed_payments.php" class="pc-link">
                <span class="pc-micon"><i class="ti ti-circle-x"></i></span>
                <span class="pc-mtext">Failed Payments</span>
            </a>
        </li>

        <li class="pc-item">
            <a href="transaction_reports.php" class="pc-link">
                <span class="pc-micon"><i class="ti ti-report-money"></i></span>
                <span class="pc-mtext">Transaction Reports</span>
            </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
