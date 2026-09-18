<?php
// C:\xampp\htdocs\Project2\frontend\dashboard.php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$page_title = 'dashboard';
$user_id = $_SESSION['user_id'];

$host = 'localhost';
$dbname = 'evenza';
$username = 'root'; 
$password = '';     

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$success_msg = '';
$error_msg = '';

// Handle Profile Update Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $new_name = trim($_POST['name']);
    $new_phone = trim($_POST['phone']);
    $new_semester = trim($_POST['semester']);
    
    if (empty($new_name)) {
        $error_msg = "Name cannot be empty.";
    } else {
        // Handle Profile Photo Upload if provided
        $photo_sql_snippet = "";
        $params = [
            'name' => $new_name,
            'phone' => $new_phone,
            'semester' => $new_semester,
            'uid' => $user_id
        ];

        if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['profile_photo']['tmp_name'];
            $fileName = $_FILES['profile_photo']['name'];
            $fileSize = $_FILES['profile_photo']['size'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowedExtensions = ['jpg', 'jpeg', 'png'];

            if (in_array($fileExtension, $allowedExtensions) && $fileSize <= 2 * 1024 * 1024) {
                $newFileName = 'student_' . uniqid() . '.' . $fileExtension;
                $uploadFileDir = __DIR__ . '/uploads/';
                
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }
                
                if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                    $photo_sql_snippet = ", profile_photo = :photo";
                    $params['photo'] = $newFileName;
                }
            }
        }

        $updateStmt = $pdo->prepare("
            UPDATE students 
            SET name = :name, phone = :phone, semester = :semester $photo_sql_snippet 
            WHERE student_id = :uid
        ");
        
        if ($updateStmt->execute($params)) {
            $success_msg = "Profile updated successfully!";
        } else {
            $error_msg = "Failed to update profile. Please try again.";
        }
    }
}

// 1. Fetch Student Profile & College Name
$profileStmt = $pdo->prepare("
    SELECT s.name, s.email, s.enrollment_no, s.semester, s.phone, s.profile_photo, c.name as college_name 
    FROM students s 
    LEFT JOIN colleges c ON s.college_id = c.college_id 
    WHERE s.student_id = :uid
");
$profileStmt->execute(['uid' => $user_id]);
$student = $profileStmt->fetch(PDO::FETCH_ASSOC);

// 2. Fetch User's Registered Events 
$regStmt = $pdo->prepare("
    SELECT 
        r.registration_id, 
        r.status as reg_status, 
        e.event_id,
        e.title, 
        e.event_date, 
        e.start_time, 
        e.venue,
        e.event_type,
        c.name as event_college,
        cat.name as category
    FROM registrations r
    JOIN events e ON r.event_id = e.event_id
    LEFT JOIN colleges c ON e.college_id = c.college_id
    LEFT JOIN categories cat ON e.category_id = cat.category_id
    WHERE r.student_id = :uid
    ORDER BY e.event_date ASC
");
$regStmt->execute(['uid' => $user_id]);
$myEvents = $regStmt->fetchAll(PDO::FETCH_ASSOC);

// 3. Fetch Real Team Data from Database for Team Events
$teamsData = [];
try {
    $teamStmt = $pdo->prepare("
        SELECT 
            t.event_id, t.team_name, t.team_code, t.created_at,
            tm.is_leader,
            s.name, s.enrollment_no, s.email, s.profile_photo,
            c.name as college_name
        FROM teams t
        JOIN team_members tm ON t.team_id = tm.team_id
        JOIN students s ON tm.student_id = s.student_id
        LEFT JOIN colleges c ON s.college_id = c.college_id
        WHERE t.team_id IN (SELECT team_id FROM team_members WHERE student_id = :uid)
    ");
    $teamStmt->execute(['uid' => $user_id]);
    $rawMembers = $teamStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach($rawMembers as $row) {
        $eid = $row['event_id'];
        if (!isset($teamsData[$eid])) {
            $teamsData[$eid] = [
                'team_name' => $row['team_name'],
                'team_code' => $row['team_code'],
                'created_at' => $row['created_at'] ? date('d M Y', strtotime($row['created_at'])) : 'TBA',
                'leader' => null,
                'members' => []
            ];
        }
        $memberData = [
            'name' => $row['name'],
            'enrollment_no' => $row['enrollment_no'],
            'email' => $row['email'],
            'college_name' => $row['college_name'],
            'photo' => $row['profile_photo']
        ];

        if ($row['is_leader']) {
            $teamsData[$eid]['leader'] = $memberData;
        } else {
            $teamsData[$eid]['members'][] = $memberData;
        }
    }
} catch (PDOException $e) {}

// 4. Calculate Quick Stats
$approvedCount = 0;
$pendingCount = 0;
foreach ($myEvents as $ev) {
    if ($ev['reg_status'] === 'approved') $approvedCount++;
    if ($ev['reg_status'] === 'pending') $pendingCount++;
}

include_once 'components/header.php';
include_once 'components/navbar.php';
?>

<style>
  :root {
    --bg: #EEEBDA;             
    --bg-alt: #E4DFC8;         
    --surface: #F7F4E9;        
    --surface-hover: #FFFFFF;  
    --text: #282B4A;           
    --text-dim: rgba(40, 43, 74, 0.68);   
    --text-faint: rgba(40, 43, 74, 0.45); 
    --accent: #4B4F86;         
    --accent-dim: rgba(75, 79, 134, 0.8);
    --border-soft: rgba(40, 43, 74, 0.14);
    --border-accent: rgba(75, 79, 134, 0.35);
  }
  body, html, main { background-color: var(--bg) !important; color: var(--text) !important; }
</style>

<main class="relative z-10 min-h-screen pt-28 pb-20">
    <div class="absolute inset-0 z-[-1] pointer-events-none opacity-40" 
         style="background-image: linear-gradient(to right, rgba(75,79,134,0.08) 1px, transparent 1px), linear-gradient(to bottom, rgba(75,79,134,0.08) 1px, transparent 1px); background-size: 50px 50px;">
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Notifications -->
        <?php if (!empty($success_msg)): ?>
            <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
                <span><?= htmlspecialchars($success_msg) ?></span>
            </div>
        <?php endif; ?>
        <?php if (!empty($error_msg)): ?>
            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                <span><?= htmlspecialchars($error_msg) ?></span>
            </div>
        <?php endif; ?>

        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-[var(--border-soft)] pb-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold font-outfit text-[var(--text)] tracking-tight">
                    Welcome back, <?= htmlspecialchars(explode(' ', trim($student['name']))[0]) ?>!
                </h1>
                <p class="text-[var(--text-dim)] mt-2 font-medium">Manage your event registrations and profile.</p>
            </div>
            <a href="logout.php" class="inline-flex items-center gap-2 px-5 py-2.5 rounded border border-[var(--border-soft)] text-[var(--text-dim)] hover:text-[var(--text)] hover:border-[var(--accent)] hover:bg-[var(--surface-hover)] transition-colors text-xs font-bold uppercase tracking-widest">
                <i data-lucide="log-out" class="w-4 h-4"></i> Sign Out
            </a>
        </div>

        <div class="grid lg:grid-cols-12 gap-8">
            <div class="lg:col-span-4 space-y-6">
                <!-- Profile Card -->
                <div class="bg-[var(--surface)] border border-[var(--border-soft)] rounded-xl p-6 shadow-md relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-[var(--accent)]/5 rounded-bl-full pointer-events-none"></div>
                    
                    <div class="flex items-center justify-between mb-6 relative z-10">
                        <div class="flex items-center gap-4">
                            <?php if (!empty($student['profile_photo']) && $student['profile_photo'] !== 'default_avatar.png' && $student['profile_photo'] !== 'pending_id.png'): ?>
                                <img src="uploads/<?= htmlspecialchars($student['profile_photo']) ?>" alt="Profile" class="w-16 h-16 rounded-full object-cover shadow-inner border border-[var(--border-soft)]">
                            <?php else: ?>
                                <div class="w-16 h-16 rounded-full bg-[var(--text)] text-[var(--bg)] flex items-center justify-center font-bold text-2xl shadow-inner">
                                    <?= strtoupper(substr($student['name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h2 class="text-lg font-bold text-[var(--text)]"><?= htmlspecialchars($student['name']) ?></h2>
                                <p class="text-[var(--accent)] text-xs font-bold uppercase tracking-wider mt-0.5"><?= htmlspecialchars($student['enrollment_no']) ?></p>
                            </div>
                        </div>
                    </div>

                    <button onclick="openEditProfileModal()" class="w-full mb-6 bg-[var(--bg-alt)] hover:bg-[var(--border-soft)] text-[var(--text)] py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors border border-[var(--border-soft)] flex items-center justify-center gap-1.5 shadow-sm">
                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit Profile
                    </button>
                    
                    <div class="space-y-4 border-t border-[var(--border-soft)] pt-5 relative z-10">
                        <div>
                            <p class="text-[10px] font-bold text-[var(--text-faint)] uppercase tracking-widest">College</p>
                            <p class="text-sm text-[var(--text-dim)] font-semibold mt-0.5"><?= htmlspecialchars($student['college_name']) ?></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-[var(--text-faint)] uppercase tracking-widest">Email Address</p>
                            <p class="text-sm text-[var(--text-dim)] font-semibold mt-0.5"><?= htmlspecialchars($student['email']) ?></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-[var(--text-faint)] uppercase tracking-widest">Phone Number</p>
                            <p class="text-sm text-[var(--text-dim)] font-semibold mt-0.5"><?= htmlspecialchars($student['phone'] ?: 'Not Provided') ?></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-[var(--text-faint)] uppercase tracking-widest">Semester</p>
                            <p class="text-sm text-[var(--text-dim)] font-semibold mt-0.5"><?= htmlspecialchars($student['semester']) ?></p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-[var(--surface)] border border-[var(--border-soft)] rounded-xl p-5 shadow-sm flex flex-col justify-center items-center text-center">
                        <div class="text-3xl font-black text-[var(--text)]"><?= $approvedCount ?></div>
                        <div class="text-[9px] font-bold text-[var(--accent)] uppercase tracking-widest mt-2">Approved Tickets</div>
                    </div>
                    <div class="bg-[var(--surface)] border border-[var(--border-soft)] rounded-xl p-5 shadow-sm flex flex-col justify-center items-center text-center">
                        <div class="text-3xl font-black text-[var(--text-dim)]"><?= $pendingCount ?></div>
                        <div class="text-[9px] font-bold text-[var(--accent)] uppercase tracking-widest mt-2">Pending Review</div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold font-outfit text-[var(--text)]">My Tickets & Registrations</h3>
                    <a href="events.php" class="text-xs font-bold text-[var(--accent)] hover:text-[var(--text)] uppercase tracking-widest transition-colors flex items-center gap-1">
                        Find More Events <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>

                <?php if (empty($myEvents)): ?>
                    <div class="bg-[var(--surface)] border border-[var(--border-soft)] border-dashed rounded-xl p-12 text-center shadow-sm">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[var(--bg-alt)] border border-[var(--border-soft)] text-[var(--text-dim)] mb-4">
                            <i data-lucide="ticket" class="w-8 h-8"></i>
                        </div>
                        <h4 class="text-lg font-bold text-[var(--text)] mb-2">No Registrations Yet</h4>
                        <p class="text-sm text-[var(--text-dim)] mb-6 max-w-sm mx-auto">You haven't registered for any upcoming events or hackathons. Start exploring the network!</p>
                        <a href="events.php" class="bg-[var(--text)] text-[var(--bg)] px-6 py-3 rounded-lg font-bold text-sm uppercase tracking-wider hover:bg-[#363A5E] transition-colors shadow-md inline-block">Browse Events</a>
                    </div>
                <?php else: ?>
                    <div class="space-y-5">
                        <?php foreach ($myEvents as $event): 
                            $dateStr = !empty($event['event_date']) ? date('M d, Y', strtotime($event['event_date'])) : 'TBA';
                            $timeStr = !empty($event['start_time']) ? date('g:i A', strtotime($event['start_time'])) : 'TBA';
                            $isApproved = ($event['reg_status'] === 'approved');
                            $isTeamEvent = (strtolower($event['event_type']) === 'team');
                            $statusColor = $isApproved ? 'text-green-700 border-green-300 bg-green-100' : 'text-amber-700 border-amber-300 bg-amber-100';
                            $locationType = (stripos($event['venue'] ?: 'TBA', 'online') !== false) ? 'Online' : 'On-Campus';
                        ?>
                        <div class="bg-[var(--surface)] border border-[var(--border-soft)] rounded-xl overflow-hidden shadow-sm flex flex-col sm:flex-row transition-transform hover:-translate-y-1 hover:shadow-md hover:border-[var(--border-accent)]">
                            <div class="p-6 flex-grow border-b sm:border-b-0 sm:border-r border-[var(--border-soft)] border-dashed">
                                <div class="flex justify-between items-start mb-3">
                                    <span class="text-[10px] font-bold text-[var(--accent)] uppercase tracking-widest"><?= htmlspecialchars($event['category']) ?></span>
                                    <span class="px-2.5 py-1 rounded text-[9px] font-bold uppercase tracking-widest border <?= $statusColor ?>">
                                        <?= htmlspecialchars($event['reg_status']) ?>
                                    </span>
                                </div>
                                <h4 class="text-xl font-bold font-outfit text-[var(--text)] leading-tight mb-1">
                                    <a href="event.php?id=<?= $event['event_id'] ?>" class="hover:underline"><?= htmlspecialchars($event['title']) ?></a>
                                </h4>
                                <p class="text-xs font-semibold text-[var(--text-dim)] uppercase tracking-wide mb-5"><?= htmlspecialchars($event['event_college']) ?></p>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex items-start gap-2">
                                        <i data-lucide="calendar" class="w-4 h-4 text-[var(--accent)] mt-0.5"></i>
                                        <div>
                                            <p class="text-[10px] text-[var(--text-faint)] uppercase font-bold tracking-widest">Date & Time</p>
                                            <p class="text-xs text-[var(--text-dim)] font-semibold mt-0.5"><?= $dateStr ?><br><?= $timeStr ?></p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <i data-lucide="map-pin" class="w-4 h-4 text-[var(--accent)] mt-0.5"></i>
                                        <div>
                                            <p class="text-[10px] text-[var(--text-faint)] uppercase font-bold tracking-widest">Location</p>
                                            <p class="text-xs text-[var(--text-dim)] font-semibold mt-0.5"><?= $locationType ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="w-full sm:w-48 bg-[var(--bg-alt)] p-6 flex flex-col justify-center items-center text-center">
                                <?php if ($isApproved): ?>
                                    <div class="p-2 bg-white rounded-lg border border-[var(--border-soft)] shadow-sm mb-3">
                                        <i data-lucide="qr-code" class="w-10 h-10 text-[var(--text)] opacity-80"></i>
                                    </div>
                                    <p class="text-[9px] text-[var(--text-faint)] font-bold uppercase tracking-widest mb-1">Pass ID</p>
                                    <p class="text-sm font-mono text-[var(--text)] font-bold tracking-widest mb-2">EV-<?= str_pad($event['registration_id'], 4, '0', STR_PAD_LEFT) ?></p>
                                <?php else: ?>
                                    <i data-lucide="hourglass" class="w-8 h-8 text-[var(--accent)] opacity-50 mb-3"></i>
                                    <p class="text-[10px] text-[var(--text-dim)] font-semibold leading-relaxed px-2 mb-2">Waiting for college coordinator approval.</p>
                                <?php endif; ?>

                                <?php if ($isTeamEvent): ?>
                                    <button onclick="openTeamModal(<?= $event['event_id'] ?>)" class="w-full mt-2 <?= $isApproved ? 'bg-[var(--text)] text-[var(--bg)] hover:bg-[#363A5E]' : 'border border-[var(--border-accent)] text-[var(--text)] hover:bg-[var(--surface)]' ?> px-4 py-2 rounded text-[10px] font-bold uppercase tracking-widest transition-colors flex justify-center items-center gap-1.5 shadow-sm">
                                        <i data-lucide="users" class="w-3.5 h-3.5"></i> View Team
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
</main>

<!-- Edit Profile Modal -->
<div id="edit-profile-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6 bg-[#282B4A]/60 backdrop-blur-sm transition-opacity duration-300">
    <div id="edit-profile-modal-box" class="bg-[var(--surface)] w-full max-w-lg rounded-2xl shadow-2xl border border-[var(--border-soft)] transform scale-95 opacity-0 transition-all duration-300 flex flex-col">
         
         <div class="p-6 border-b border-[var(--border-soft)] flex justify-between items-center bg-[var(--bg)] rounded-t-2xl">
              <h3 class="text-xl font-bold font-outfit text-[var(--text)]">Edit Profile</h3>
              <button onclick="closeEditProfileModal()" class="p-2 bg-[var(--bg-alt)] hover:bg-[var(--border-soft)] rounded-lg transition-colors text-[var(--text)]">
                  <i data-lucide="x" class="w-5 h-5"></i>
              </button>
         </div>
         
         <form method="POST" action="dashboard.php" enctype="multipart/form-data" class="p-6 space-y-5">
              <input type="hidden" name="update_profile" value="1">
              
              <div>
                  <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1.5">Full Name</label>
                  <input type="text" name="name" required value="<?= htmlspecialchars($student['name']) ?>"
                         class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-lg px-4 py-2.5 focus:outline-none focus:border-[var(--accent)] transition-colors text-sm">
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                      <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1.5">Phone Number</label>
                      <input type="text" name="phone" value="<?= htmlspecialchars($student['phone']) ?>"
                             class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-lg px-4 py-2.5 focus:outline-none focus:border-[var(--accent)] transition-colors text-sm" placeholder="10-digit number">
                  </div>
                  <div>
                      <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1.5">Semester</label>
                      <select name="semester" class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-lg px-4 py-2.5 focus:outline-none focus:border-[var(--accent)] transition-colors text-sm cursor-pointer">
                          <?php for($i=1; $i<=8; $i++): ?>
                              <option value="Semester <?= $i ?>" <?= $student['semester'] === "Semester $i" ? 'selected' : '' ?>>Semester <?= $i ?></option>
                          <?php endfor; ?>
                      </select>
                  </div>
              </div>

              <div>
                  <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1.5">Update Profile Photo</label>
                  <input type="file" name="profile_photo" accept="image/png, image/jpeg, image/jpg"
                         class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-lg px-4 py-2 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-[var(--bg-alt)] file:text-[var(--text)] hover:file:bg-[var(--border-soft)] transition-colors cursor-pointer text-xs">
              </div>

              <div class="pt-4 border-t border-[var(--border-soft)] flex justify-end gap-3">
                  <button type="button" onclick="closeEditProfileModal()" class="px-5 py-2.5 rounded-lg border border-[var(--border-soft)] text-[var(--text)] text-xs font-bold uppercase tracking-wider hover:bg-[var(--bg-alt)] transition-colors">
                      Cancel
                  </button>
                  <button type="submit" class="px-6 py-2.5 rounded-lg bg-[var(--text)] text-[var(--bg)] text-xs font-bold uppercase tracking-wider hover:bg-[#363A5E] transition-colors shadow-md">
                      Save Changes
                  </button>
              </div>
         </form>
    </div>
</div>

<!-- Team Details Modal -->
<div id="team-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6 bg-[#282B4A]/60 backdrop-blur-sm transition-opacity duration-300">
    <div id="team-modal-box" class="bg-[var(--surface)] w-full max-w-4xl rounded-2xl shadow-2xl border border-[var(--border-soft)] transform scale-95 opacity-0 transition-all duration-300 flex flex-col max-h-[90vh]">
         
         <div class="p-6 border-b border-[var(--border-soft)] flex justify-between items-center bg-[var(--bg)] rounded-t-2xl">
              <div>
                  <h3 class="text-xl font-bold font-outfit text-[var(--text)]">Team Details</h3>
                  <p id="tm-event-name" class="text-xs text-[var(--accent)] mt-1 font-bold uppercase tracking-widest"></p>
              </div>
              <button onclick="closeTeamModal()" class="p-2 bg-[var(--bg-alt)] hover:bg-[var(--border-soft)] rounded-lg transition-colors text-[var(--text)]">
                  <i data-lucide="x" class="w-5 h-5"></i>
              </button>
         </div>
         
         <div class="p-6 overflow-y-auto space-y-6 bg-[var(--surface)] rounded-b-2xl">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="bg-[var(--bg)] border border-[var(--border-soft)] rounded-xl p-5 shadow-sm">
                      <h4 class="text-sm font-bold text-[var(--text)] mb-4 flex items-center gap-2 border-b border-[var(--border-soft)] pb-3"><i data-lucide="users" class="w-4 h-4 text-[var(--accent)]"></i> Team Information</h4>
                      <div class="space-y-4">
                          <div>
                              <p class="text-[10px] uppercase font-bold text-[var(--text-faint)] tracking-widest">Team Name</p>
                              <p id="tm-name" class="text-sm font-bold text-[var(--text)] mt-0.5"></p>
                          </div>
                          <div class="flex gap-6">
                              <div>
                                  <p class="text-[10px] uppercase font-bold text-[var(--text-faint)] tracking-widest mb-1">Team Code</p>
                                  <span id="tm-code" class="bg-[var(--accent)]/10 text-[var(--accent)] border border-[var(--border-accent)] px-2 py-1 rounded text-xs font-bold tracking-wider"></span>
                              </div>
                              <div>
                                  <p class="text-[10px] uppercase font-bold text-[var(--text-faint)] tracking-widest mb-1">Created Date</p>
                                  <p id="tm-date" class="text-xs font-semibold text-[var(--text-dim)] mt-1.5"></p>
                              </div>
                          </div>
                      </div>
                  </div>
                  
                  <div class="bg-[var(--bg)] border border-[var(--border-soft)] rounded-xl p-5 shadow-sm">
                      <h4 class="text-sm font-bold text-[var(--text)] mb-4 flex items-center gap-2 border-b border-[var(--border-soft)] pb-3"><i data-lucide="calendar" class="w-4 h-4 text-[var(--accent)]"></i> Event Information</h4>
                      <div class="space-y-4">
                          <div>
                              <p class="text-[10px] uppercase font-bold text-[var(--text-faint)] tracking-widest">Event Title</p>
                              <p id="tm-event-title" class="text-sm font-bold text-[var(--text)] mt-0.5"></p>
                          </div>
                          <div class="flex gap-6">
                              <div>
                                  <p class="text-[10px] uppercase font-bold text-[var(--text-faint)] tracking-widest">Location / Venue</p>
                                  <p id="tm-event-venue" class="text-xs font-semibold text-[var(--text-dim)] mt-0.5"></p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="bg-[var(--bg)] border border-[var(--border-soft)] rounded-xl p-5 shadow-sm">
                  <h4 class="text-sm font-bold text-[var(--text)] mb-4 flex items-center gap-2"><i data-lucide="star" class="w-4 h-4 text-[#FF7E67]"></i> Team Leader</h4>
                  <div id="tm-leader-container" class="flex flex-col sm:flex-row items-start sm:items-center gap-4 bg-[var(--surface)] p-4 rounded-lg border border-[var(--border-soft)]"></div>
              </div>

              <div class="bg-[var(--bg)] border border-[var(--border-soft)] rounded-xl p-5 shadow-sm">
                  <div class="flex justify-between items-center mb-4 border-b border-[var(--border-soft)] pb-3">
                      <h4 class="text-sm font-bold text-[var(--text)] flex items-center gap-2"><i data-lucide="user" class="w-4 h-4 text-[var(--accent)]"></i> Team Members</h4>
                      <span id="tm-count" class="bg-[var(--bg-alt)] border border-[var(--border-soft)] text-[var(--accent)] px-2 py-1 rounded text-[10px] font-bold tracking-widest">0 Members</span>
                  </div>
                  <div id="tm-members-container" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
              </div>
         </div>
    </div>
</div>

<script>
    window.myEventsData = <?= json_encode($myEvents) ?>;
    window.myTeamsData = <?= json_encode($teamsData) ?>;

    function openEditProfileModal() {
        const modal = document.getElementById('edit-profile-modal');
        const modalBox = document.getElementById('edit-profile-modal-box');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        setTimeout(() => {
            modalBox.classList.remove('scale-95', 'opacity-0');
            modalBox.classList.add('scale-100', 'opacity-100');
        }, 50);
    }

    function closeEditProfileModal() {
        const modal = document.getElementById('edit-profile-modal');
        const modalBox = document.getElementById('edit-profile-modal-box');
        modalBox.classList.add('scale-95', 'opacity-0');
        modalBox.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }, 200);
    }

    function createAvatarHtml(name, photoUrl) {
        if (photoUrl && photoUrl !== '' && photoUrl !== 'pending_id.png' && photoUrl !== 'default_avatar.png') {
            return `<img src="uploads/${photoUrl}" alt="${name}" onerror="this.outerHTML='<div class=\\'w-12 h-12 shrink-0 rounded-full bg-[var(--text)] text-[var(--bg)] flex items-center justify-center font-bold text-lg shadow-inner\\'>${name.charAt(0).toUpperCase()}</div>'" class="w-12 h-12 shrink-0 rounded-full object-cover shadow-sm border border-[var(--border-soft)]">`;
        }
        return `<div class="w-12 h-12 shrink-0 rounded-full bg-[var(--text)] text-[var(--bg)] flex items-center justify-center font-bold text-lg shadow-inner">${name.charAt(0).toUpperCase()}</div>`;
    }

    function createMemberCard(member) {
        return `
            <div class="flex items-center gap-3 bg-[var(--surface)] p-3 rounded-lg border border-[var(--border-soft)]">
                ${createAvatarHtml(member.name, member.photo)}
                <div class="overflow-hidden">
                    <h5 class="text-sm font-bold text-[var(--text)] truncate">${member.name}</h5>
                    <p class="text-[10px] text-[var(--accent)] font-bold uppercase tracking-widest truncate mt-0.5">${member.enrollment_no}</p>
                    <p class="text-[10px] text-[var(--text-dim)] truncate">${member.email}</p>
                </div>
            </div>
        `;
    }

    function openTeamModal(eventId) {
        const event = window.myEventsData.find(e => e.event_id == eventId);
        const team = window.myTeamsData[eventId];

        document.getElementById('tm-event-name').innerText = event.title;
        document.getElementById('tm-event-title').innerText = event.title;
        document.getElementById('tm-event-venue').innerText = (event.venue && event.venue.toLowerCase().includes('online')) ? 'Online' : 'On-Campus';
        
        const leaderContainer = document.getElementById('tm-leader-container');
        const membersContainer = document.getElementById('tm-members-container');

        if (!team) {
            document.getElementById('tm-name').innerText = "Not Assigned Yet";
            document.getElementById('tm-code').innerText = "PENDING";
            document.getElementById('tm-date').innerText = "—";
            
            leaderContainer.innerHTML = `<div class="w-full py-6 text-center text-sm text-[var(--text-dim)] italic">No team leader has been assigned to you yet. Your college coordinator is reviewing your registration.</div>`;
            membersContainer.innerHTML = `<div class="col-span-2 py-6 text-center text-sm text-[var(--text-dim)] italic">No team members have been assigned in this team yet.</div>`;
            document.getElementById('tm-count').innerText = "0 Members";
        } else {
            document.getElementById('tm-name').innerText = team.team_name;
            document.getElementById('tm-code').innerText = team.team_code;
            document.getElementById('tm-date').innerText = team.created_at;

            if (team.leader) {
                leaderContainer.innerHTML = `
                    ${createAvatarHtml(team.leader.name, team.leader.photo)}
                    <div class="overflow-hidden">
                        <h5 class="text-base font-bold text-[var(--text)] truncate">${team.leader.name}</h5>
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1">
                            <p class="text-xs text-[var(--accent)] font-bold uppercase tracking-widest">${team.leader.enrollment_no}</p>
                            <p class="text-xs text-[var(--text-dim)]"><i data-lucide="mail" class="w-3 h-3 inline pb-0.5"></i> ${team.leader.email}</p>
                            <p class="text-xs text-[var(--text-dim)]"><i data-lucide="graduation-cap" class="w-3 h-3 inline pb-0.5"></i> ${team.leader.college_name}</p>
                        </div>
                    </div>
                `;
            } else {
                leaderContainer.innerHTML = `<p class="text-sm text-[var(--text-dim)] italic">Leader not assigned yet.</p>`;
            }

            const totalCount = (team.members ? team.members.length : 0) + (team.leader ? 1 : 0);
            document.getElementById('tm-count').innerText = `${totalCount} Members`;
            
            if (team.members && team.members.length > 0) {
                membersContainer.innerHTML = team.members.map(m => createMemberCard(m)).join('');
            } else {
                membersContainer.innerHTML = `<p class="text-sm text-[var(--text-dim)] italic col-span-2 py-4 text-center">No other members assigned in this team yet.</p>`;
            }
        }

        const modal = document.getElementById('team-modal');
        const modalBox = document.getElementById('team-modal-box');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        
        if (typeof lucide !== 'undefined') lucide.createIcons();

        setTimeout(() => {
            modalBox.classList.remove('scale-95', 'opacity-0');
            modalBox.classList.add('scale-100', 'opacity-100');
        }, 50);
    }

    function closeTeamModal() {
        const modal = document.getElementById('team-modal');
        const modalBox = document.getElementById('team-modal-box');
        modalBox.classList.add('scale-95', 'opacity-0');
        modalBox.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }, 200);
    }

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>

<?php include_once 'components/footer.php'; ?>