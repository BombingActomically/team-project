<?php
// C:\xampp\htdocs\Project2\frontend\college-events.php
session_start();

if (!isset($_SESSION['college_id']) || $_SESSION['user_role'] !== 'college') {
    header("Location: college-login.php");
    exit();
}

$college_id = $_SESSION['college_id'];
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

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // --- MANUAL PHP VALIDATION FOR PUBLISHING EVENT ---
    if (isset($_POST['action']) && $_POST['action'] === 'add_event') {
        
        $title = trim($_POST['title'] ?? '');
        $category_id = trim($_POST['category_id'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $event_date = trim($_POST['event_date'] ?? '');
        $deadline = trim($_POST['registration_deadline'] ?? '');
        $venue = trim($_POST['venue'] ?? '');
        $event_type = trim($_POST['event_type'] ?? '');
        $fee = trim($_POST['registration_fee'] ?? '');

        // 1. Check for empty fields
        if (empty($title) || empty($category_id) || empty($description) || empty($event_date) || empty($deadline) || empty($venue) || empty($event_type) || $fee === '') {
            $error_message = "Validation Failed: All fields must be filled out to publish an event.";
        } 
        // 2. Validate numeric fee
        elseif (!is_numeric($fee) || (float)$fee < 0) {
            $error_message = "Validation Failed: Registration fee must be a valid number (0 or greater).";
        }
        // 3. Logical date validation
        elseif (strtotime($deadline) >= strtotime($event_date)) {
            $error_message = "Validation Failed: The registration deadline must be before the actual event date.";
        } 
        // 4. Passed Validation -> Insert into DB
        else {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO events (college_id, category_id, title, description, event_date, venue, registration_deadline, event_type, registration_fee, status) 
                    VALUES (:cid, :cat, :title, :desc, :edate, :venue, :deadline, :etype, :fee, 'published')
                ");
                $stmt->execute([
                    'cid' => $college_id,
                    'cat' => $category_id,
                    'title' => $title,
                    'desc' => $description,
                    'edate' => $event_date,
                    'venue' => $venue,
                    'deadline' => $deadline,
                    'etype' => $event_type,
                    'fee' => (float)$fee
                ]);
                $success_message = "Event successfully published.";
            } catch (Exception $e) {
                $error_message = "Error publishing event: " . $e->getMessage();
            }
        }
    }

    if (isset($_POST['action']) && $_POST['action'] === 'delete_event') {
        try {
            $stmt = $pdo->prepare("DELETE FROM events WHERE event_id = :eid AND college_id = :cid");
            $stmt->execute(['eid' => $_POST['event_id'], 'cid' => $college_id]);
            $success_message = "Event permanently removed.";
        } catch (Exception $e) {
            $error_message = "Cannot delete event. It may have active student registrations.";
        }
    }
}

$eventsStmt = $pdo->prepare("SELECT e.*, c.name as category_name FROM events e LEFT JOIN categories c ON e.category_id = c.category_id WHERE e.college_id = :cid ORDER BY e.event_date ASC");
$eventsStmt->execute(['cid' => $college_id]);
$publishedEvents = $eventsStmt->fetchAll(PDO::FETCH_ASSOC);

$cats = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'college-events';
include_once 'components/header.php';
include_once 'components/navbar.php';
?>

<style>
  :root { --bg: #EEEBDA; --surface: #F7F4E9; --surface-hover: #FFFFFF; --text: #282B4A; --text-dim: rgba(40, 43, 74, 0.68); --accent: #4B4F86; --border-soft: rgba(40, 43, 74, 0.14); }
  body, html, main { background-color: var(--bg) !important; color: var(--text) !important; }
</style>

<main class="relative z-10 min-h-screen py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 border-b border-[var(--border-soft)] pb-6 gap-4">
        <h1 class="text-3xl font-bold font-outfit text-[var(--text)] flex items-center gap-3">
            <i data-lucide="calendar" class="w-8 h-8 text-[var(--accent)]"></i> Event Management
        </h1>
        <a href="college-dashboard.php" class="bg-[var(--surface)] text-[var(--text)] border border-[var(--border-soft)] hover:border-[var(--accent)] px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-sm flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4 text-[var(--accent)]"></i> Dashboard
        </a>
    </div>

    <?php if (!empty($success_message)): ?>
        <div class="bg-emerald-100 border border-emerald-300 text-emerald-800 px-5 py-4 rounded-xl mb-6 text-sm font-bold flex items-center gap-2 shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i> <?= htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="bg-red-100 border border-red-300 text-red-700 px-5 py-4 rounded-xl mb-6 text-sm font-bold flex items-center gap-2 shadow-sm">
            <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i> <?= htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <div class="bg-[var(--surface)] border border-[var(--border-soft)] rounded-3xl p-6 md:p-8 shadow-xl">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h2 class="text-xl font-bold font-outfit text-[var(--text)]">Published Events</h2>
            <button onclick="document.getElementById('modal-add-event').classList.replace('hidden', 'flex')" class="bg-[var(--text)] text-[var(--bg)] px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-[#363A5E] transition-colors flex items-center gap-2 shadow-md">
                <i data-lucide="plus" class="w-4 h-4 text-emerald-400"></i> Publish Event
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-[var(--border-soft)] text-[var(--text-dim)] uppercase font-bold tracking-wider text-xs">
                        <th class="pb-3 px-3">Event Title</th>
                        <th class="pb-3 px-3">Category & Type</th>
                        <th class="pb-3 px-3">Date & Fee</th>
                        <th class="pb-3 px-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-soft)] font-medium">
                    <?php if (empty($publishedEvents)): ?>
                        <tr><td colspan="4" class="py-12 text-center text-[var(--text-dim)]">No events published yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($publishedEvents as $ev): ?>
                            <tr class="hover:bg-[var(--surface-hover)]">
                                <td class="py-4 px-3">
                                    <div class="font-bold text-[var(--text)] text-sm"><?= htmlspecialchars($ev['title']) ?></div>
                                    <div class="text-xs text-[var(--text-dim)] mt-1 truncate max-w-xs"><?= htmlspecialchars($ev['description']) ?></div>
                                </td>
                                <td class="py-4 px-3">
                                    <div class="text-[var(--accent)] font-bold"><?= htmlspecialchars($ev['category_name'] ?? 'General') ?></div>
                                    <div class="text-xs uppercase font-mono mt-1"><?= htmlspecialchars($ev['event_type']) ?></div>
                                </td>
                                <td class="py-4 px-3 font-mono text-sm">
                                    <div><?= date('M d, Y', strtotime($ev['event_date'])) ?></div>
                                    <div class="text-[var(--accent)] font-bold mt-1"><?= $ev['registration_fee'] > 0 ? '₹' . number_format($ev['registration_fee'], 2) : 'Free' ?></div>
                                </td>
                                <td class="py-4 px-3 text-right">
                                    <form method="POST" class="inline" onsubmit="return confirm('Delete this event?');">
                                        <input type="hidden" name="action" value="delete_event">
                                        <input type="hidden" name="event_id" value="<?= $ev['event_id'] ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-800 p-2 border border-red-200 bg-red-50 rounded-lg shadow-sm">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div id="modal-add-event" class="fixed inset-0 z-[100] hidden items-center justify-center bg-[#282B4A]/60 backdrop-blur-sm p-4">
    <div class="bg-[var(--surface)] w-full max-w-2xl rounded-3xl p-8 shadow-2xl border border-[var(--border-soft)] max-h-[90vh] overflow-y-auto relative">
        <button type="button" onclick="document.getElementById('modal-add-event').classList.replace('flex', 'hidden')" class="absolute top-6 right-6 p-2 bg-[var(--bg)] rounded-full text-[var(--text)] hover:bg-[var(--border-soft)] transition-colors">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
        <h2 class="text-2xl font-bold font-outfit text-[var(--text)] mb-6 border-b border-[var(--border-soft)] pb-4">Publish New Event</h2>
        
        <form method="POST" class="space-y-4">
            <input type="hidden" name="action" value="add_event">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1">Title <span class="text-red-500">*</span></label>
                    <!-- Removed HTML 'required' -->
                    <input type="text" name="title" class="w-full bg-[var(--bg)] border border-[var(--border-soft)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--accent)]">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" class="w-full bg-[var(--bg)] border border-[var(--border-soft)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--accent)]">
                        <option value="">-- Select Category --</option>
                        <?php foreach($cats as $c): ?>
                            <option value="<?= $c['category_id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="3" class="w-full bg-[var(--bg)] border border-[var(--border-soft)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--accent)]"></textarea>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1">Event Date <span class="text-red-500">*</span></label>
                    <input type="date" name="event_date" class="w-full bg-[var(--bg)] border border-[var(--border-soft)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--accent)]">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1">Reg. Deadline <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="registration_deadline" class="w-full bg-[var(--bg)] border border-[var(--border-soft)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--accent)]">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1">Venue <span class="text-red-500">*</span></label>
                    <input type="text" name="venue" class="w-full bg-[var(--bg)] border border-[var(--border-soft)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--accent)]">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1">Type <span class="text-red-500">*</span></label>
                        <select name="event_type" class="w-full bg-[var(--bg)] border border-[var(--border-soft)] rounded-xl px-2 py-3 text-sm focus:outline-none focus:border-[var(--accent)]">
                            <option value="solo">Solo</option>
                            <option value="team">Team</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1">Fee (₹) <span class="text-red-500">*</span></label>
                        <input type="number" name="registration_fee" step="0.01" value="0.00" class="w-full bg-[var(--bg)] border border-[var(--border-soft)] rounded-xl px-2 py-3 text-sm focus:outline-none focus:border-[var(--accent)]">
                    </div>
                </div>
            </div>
            <button type="submit" class="w-full bg-[var(--text)] text-[var(--bg)] font-bold py-4 rounded-xl hover:bg-[#363A5E] uppercase tracking-wider text-xs mt-6 shadow-lg">Publish Event</button>
        </form>
    </div>
</div>

<script>if (typeof lucide !== 'undefined') { lucide.createIcons(); }</script>
<?php include_once 'components/footer.php'; ?>