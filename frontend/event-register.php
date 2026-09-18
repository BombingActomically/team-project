<?php
// C:\xampp\htdocs\Project2\frontend\event-register.php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    $_SESSION['login_error'] = "You must be logged in to register for an event.";
    header("Location: login.php");
    exit();
}

$page_title = 'event-register';
$user_id = $_SESSION['user_id'];
$selected_event_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

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

$error_message = '';
$already_registered = false;
$registered_event_title = '';

// 1. Check if user is already registered for this specific event via URL parameter
if ($selected_event_id) {
    $dupCheck = $pdo->prepare("
        SELECT e.title 
        FROM registrations r 
        JOIN events e ON r.event_id = e.event_id 
        WHERE r.student_id = :uid AND r.event_id = :eid
    ");
    $dupCheck->execute(['uid' => $user_id, 'eid' => $selected_event_id]);
    $existing = $dupCheck->fetch(PDO::FETCH_ASSOC);
    if ($existing) {
        $already_registered = true;
        $registered_event_title = $existing['title'];
    }
}

// 2. Fetch Logged-in Student Details
$studentStmt = $pdo->prepare("
    SELECT s.name, s.email, s.enrollment_no, c.name as college_name 
    FROM students s 
    LEFT JOIN colleges c ON s.college_id = c.college_id 
    WHERE s.student_id = :uid
");
$studentStmt->execute(['uid' => $user_id]);
$student = $studentStmt->fetch(PDO::FETCH_ASSOC);

// 3. Fetch Available Events for Dropdown
$current_time = date('Y-m-d H:i:s');
$eventsStmt = $pdo->prepare("
    SELECT event_id, title, registration_fee, event_type 
    FROM events 
    WHERE status != 'cancelled' AND registration_deadline > :now
    ORDER BY event_date ASC
");
$eventsStmt->execute(['now' => $current_time]);
$available_events = $eventsStmt->fetchAll(PDO::FETCH_ASSOC);

// 4. Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = isset($_POST['event_id']) ? (int)$_POST['event_id'] : 0;
    
    if (empty($event_id)) {
        $error_message = "Please select an event to register for.";
    } else {
        $checkStmt = $pdo->prepare("SELECT registration_id FROM registrations WHERE student_id = :uid AND event_id = :eid");
        $checkStmt->execute(['uid' => $user_id, 'eid' => $event_id]);
        
        if ($checkStmt->fetch()) {
            $error_message = "You have already registered for this event. Check your Dashboard!";
        } else {
            $feeStmt = $pdo->prepare("SELECT registration_fee FROM events WHERE event_id = :eid");
            $feeStmt->execute(['eid' => $event_id]);
            $eventFee = (float)$feeStmt->fetchColumn();

            if ($eventFee > 0) {
                header("Location: pay.php?event_id=" . $event_id);
                exit();
            } else {
                $insertStmt = $pdo->prepare("
                    INSERT INTO registrations (student_id, event_id, status) 
                    VALUES (:uid, :eid, 'approved')
                ");
                $insertStmt->execute(['uid' => $user_id, 'eid' => $event_id]);
                
                header("Location: dashboard.php?registered=success");
                exit();
            }
        }
    }
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

<main class="relative z-10 min-h-screen flex items-center justify-center py-20 pt-32">
    <div class="absolute inset-0 z-[-1] pointer-events-none" 
         style="background-image: linear-gradient(to right, rgba(75,79,134,0.08) 1px, transparent 1px), linear-gradient(to bottom, rgba(75,79,134,0.08) 1px, transparent 1px); background-size: 50px 50px;">
    </div>

    <div class="max-w-[700px] w-full mx-auto px-4 sm:px-6 relative z-10">
        
        <?php if ($already_registered): ?>
            <!-- ALREADY REGISTERED STATE -->
            <div class="bg-[var(--surface)] border border-[var(--border-soft)] rounded-2xl p-10 md:p-14 shadow-xl text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-amber-100 border border-amber-300 text-amber-700 mb-6 shadow-inner">
                    <i data-lucide="alert-circle" class="w-10 h-10"></i>
                </div>
                <h1 class="text-3xl font-bold font-outfit text-[var(--text)] tracking-tight mb-3">Already Registered!</h1>
                <p class="text-[var(--text-dim)] text-sm mb-8 leading-relaxed max-w-md mx-auto">
                    You have already secured your ticket for <strong class="text-[var(--text)]"><?= htmlspecialchars($registered_event_title) ?></strong>. Check your dashboard to view your pass details.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="dashboard.php" class="bg-[var(--text)] text-[var(--bg)] font-bold py-3 px-8 rounded-lg hover:bg-[#363A5E] transition-colors shadow-md uppercase tracking-wider text-xs">
                        Go to Dashboard
                    </a>
                    <a href="events.php" class="bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] font-bold py-3 px-8 rounded-lg hover:border-[var(--border-accent)] transition-colors uppercase tracking-wider text-xs">
                        Browse Other Events
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- STANDARD REGISTRATION FORM -->
            <div class="bg-[var(--surface)] border border-[var(--border-soft)] rounded-2xl p-8 md:px-12 md:py-10 shadow-xl relative overflow-hidden">
                
                <div class="absolute top-0 right-0 w-32 h-32 bg-[var(--accent)]/5 rounded-bl-full pointer-events-none"></div>

                <div class="text-center mb-8 relative z-10">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-xl bg-[var(--bg-alt)] border border-[var(--border-soft)] text-[var(--accent)] mb-4">
                        <i data-lucide="ticket" class="w-8 h-8"></i>
                    </div>
                    <h1 class="text-3xl font-bold font-outfit text-[var(--text)] tracking-tight">Event Registration</h1>
                    <p class="text-[var(--text-dim)] text-sm mt-2">Secure your spot for upcoming hackathons and fests.</p>
                </div>

                <?php if (!empty($error_message)): ?>
                    <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-8 text-sm flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                        <span><?php echo htmlspecialchars($error_message); ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="event-register.php" class="space-y-6 relative z-10">
                    
                    <div class="bg-[var(--surface-hover)] p-5 rounded-xl border border-[var(--border-soft)]">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-2">Select Event <span class="text-red-500">*</span></label>
                        <select name="event_id" required class="w-full bg-transparent border-b border-[var(--border-soft)] text-[var(--text)] font-bold text-lg py-2 focus:outline-none focus:border-[var(--accent)] transition-colors appearance-none cursor-pointer">
                            <option value="" disabled <?php echo is_null($selected_event_id) ? 'selected' : ''; ?>>-- Choose an Event --</option>
                            <?php foreach ($available_events as $ev): ?>
                                <option value="<?= $ev['event_id'] ?>" 
                                    <?= ($selected_event_id == $ev['event_id'] || (isset($_POST['event_id']) && $_POST['event_id'] == $ev['event_id'])) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ev['title']) ?> 
                                    <?= ((float)$ev['registration_fee'] > 0) ? '(₹' . number_format($ev['registration_fee'], 2) . ')' : '(Free)' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="text-[10px] text-[var(--text-faint)] mt-2 italic"><i data-lucide="info" class="w-3 h-3 inline pb-0.5"></i> Paid events will open a sandbox checkout page. Free events confirm instantly.</p>
                    </div>

                    <div class="space-y-4 pt-2">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-[var(--text-dim)] border-b border-[var(--border-soft)] pb-2 mb-4">Participant Details</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[9px] font-bold uppercase tracking-widest text-[var(--text-faint)] mb-1">Full Name</label>
                                <div class="w-full bg-[var(--bg-alt)] border border-[var(--border-soft)] text-[var(--text-dim)] font-semibold rounded-lg px-4 py-3 cursor-not-allowed">
                                    <?= htmlspecialchars($student['name']) ?>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold uppercase tracking-widest text-[var(--text-faint)] mb-1">Enrollment ID</label>
                                <div class="w-full bg-[var(--bg-alt)] border border-[var(--border-soft)] text-[var(--text-dim)] font-semibold rounded-lg px-4 py-3 cursor-not-allowed">
                                    <?= htmlspecialchars($student['enrollment_no']) ?>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[9px] font-bold uppercase tracking-widest text-[var(--text-faint)] mb-1">College Email</label>
                                <div class="w-full bg-[var(--bg-alt)] border border-[var(--border-soft)] text-[var(--text-dim)] font-semibold rounded-lg px-4 py-3 cursor-not-allowed">
                                    <?= htmlspecialchars($student['email']) ?>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold uppercase tracking-widest text-[var(--text-faint)] mb-1">University / College</label>
                                <div class="w-full bg-[var(--bg-alt)] border border-[var(--border-soft)] text-[var(--text-dim)] font-semibold rounded-lg px-4 py-3 cursor-not-allowed">
                                    <?= htmlspecialchars($student['college_name']) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 mt-2 border-t border-[var(--border-soft)]">
                        <button type="submit" class="w-full bg-[var(--text)] text-[var(--bg)] font-bold py-4 px-4 rounded-lg hover:bg-[#363A5E] transition-colors shadow-lg uppercase tracking-wider text-sm flex items-center justify-center gap-2">
                            Proceed to Confirmation <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>

<?php include_once 'components/footer.php'; ?>