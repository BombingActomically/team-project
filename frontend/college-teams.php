<?php
// C:\xampp\htdocs\Project2\frontend\college-teams.php
session_start();

if (!isset($_SESSION['college_id']) ||$_SESSION['user_role'] !== 'college') {
    header("Location: college-login.php");
    exit();
}

$college_id =$_SESSION['college_id'];
$host = 'localhost';$dbname = 'evenza';
$username = 'root';$password = '';     

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$success_message = '';$error_message = '';

/* =========================================================
   HELPERS
   ========================================================= */

// Fetch a team event's size limits, scoped to this college (prevents
// touching events that belong to another college).
function getEventTeamLimits(PDO $pdo, int $event_id, int $college_id) {
    $stmt = $pdo->prepare("SELECT event_id, title, min_team_size, max_team_size FROM events WHERE event_id = :eid AND college_id = :cid AND event_type = 'team'");
    $stmt->execute(['eid' => $event_id, 'cid' => $college_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}

// Confirms every given student is an approved, registered participant of
// the event, and is either unassigned or already in $allowedTeamId
// (used when editing an existing team so its current members still pass).
function studentsAreEligible(PDO $pdo, array $studentIds, int $event_id, ?int $allowedTeamId = null) {
    $studentIds = array_values(array_unique(array_map('intval', $studentIds)));
    if (empty($studentIds)) return true;

    $placeholders = implode(',', array_fill(0, count($studentIds), '?'));
    $sql = "SELECT COUNT(*) FROM registrations
            WHERE event_id = ? AND status = 'approved' AND student_id IN ($placeholders)
              AND (team_id IS NULL" . ($allowedTeamId ? " OR team_id = ?" : "") . ")";
    $params = array_merge([$event_id], $studentIds);
    if ($allowedTeamId) { $params[] = $allowedTeamId; }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return (int)$stmt->fetchColumn() === count($studentIds);
}

// Confirms a team belongs to an event hosted by this college, and returns
// team_id, event_id, leader_id, current member count and event size limits.
function getOwnedTeamInfo(PDO $pdo, int $team_id, int $college_id) {
    $stmt = $pdo->prepare("
        SELECT t.team_id, t.event_id, t.leader_id, e.title AS event_title,
               e.max_team_size, e.min_team_size,
               (SELECT COUNT(*) FROM team_members tm WHERE tm.team_id = t.team_id) AS current_size
        FROM teams t
        JOIN events e ON t.event_id = e.event_id
        WHERE t.team_id = :tid AND e.college_id = :cid
    ");
    $stmt->execute(['tid' => $team_id, 'cid' => $college_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // -- CREATE TEAM --
    if (isset($_POST['action']) && $_POST['action'] === 'create_team') {$event_id = (int)$_POST['event_id'];$team_name = trim($_POST['team_name']);$leader_id = (int)$_POST['leader_id'];$members = isset($_POST['members']) ?$_POST['members'] : [];

        if (empty($team_name) || !$event_id || !$leader_id) {$error_message = "Please fill in all required team details.";
        } else {
            $members = array_values(array_unique(array_map('intval', $members)));
            if (!in_array($leader_id, $members, true)) { $members[] = $leader_id; }

            $eventInfo = getEventTeamLimits($pdo, $event_id, $college_id);

            if (!$eventInfo) {
                $error_message = "Selected event is invalid or is not a team event hosted by your college.";
            } elseif (count($members) > (int)$eventInfo['max_team_size']) {
                $error_message = "\"" . htmlspecialchars($eventInfo['title']) . "\" allows a maximum of " . $eventInfo['max_team_size'] . " member(s) per team — you selected " . count($members) . ".";
            } elseif (count($members) < (int)$eventInfo['min_team_size']) {
                $error_message = "\"" . htmlspecialchars($eventInfo['title']) . "\" requires at least " . $eventInfo['min_team_size'] . " member(s) per team — you selected " . count($members) . ".";
            } elseif (!studentsAreEligible($pdo, $members, $event_id)) {
                $error_message = "One or more selected students are not approved participants of this event, or are already assigned to another team.";
            } else {
                try {
                    $pdo->beginTransaction();$team_code = 'TEAM-' . strtoupper(substr(md5(uniqid()), 0, 6));

                    $teamStmt =$pdo->prepare("INSERT INTO teams (event_id, leader_id, team_name, team_code, created_at) VALUES (:eid, :lid, :tname, :tcode, NOW())");
                    $teamStmt->execute(['eid' => $event_id, 'lid' =>$leader_id, 'tname' => $team_name, 'tcode' =>$team_code]);
                    $team_id =$pdo->lastInsertId();

                    $memStmt =$pdo->prepare("INSERT INTO team_members (team_id, student_id) VALUES (:tid, :sid)");
                    $regUpdateStmt =$pdo->prepare("UPDATE registrations SET team_id = :tid, registration_type = 'team' WHERE student_id = :sid AND event_id = :eid");

                    foreach ($members as $std_id) {$memStmt->execute(['tid' => $team_id, 'sid' => (int)$std_id]);
                        $regUpdateStmt->execute(['tid' =>$team_id, 'sid' => (int)$std_id, 'eid' =>$event_id]);
                    }

                    $pdo->commit();$success_message = "Team '$team_name' generated successfully with code$team_code.";
                } catch (Exception $e) {
                    $pdo->rollBack();$error_message = "Failed to create team: " . $e->getMessage();
                }
            }
        }
    }

    // -- EDIT TEAM --
    if (isset($_POST['action']) && $_POST['action'] === 'edit_team') {$team_id = (int)$_POST['team_id'];$event_id = (int)$_POST['event_id'];$team_name = trim($_POST['team_name']);$leader_id = (int)$_POST['leader_id'];$members = isset($_POST['members']) ?$_POST['members'] : [];

        if (empty($team_name) || !$team_id || !$leader_id) {$error_message = "Please fill in all required details.";
        } else {
            $owned = getOwnedTeamInfo($pdo, $team_id, $college_id);
            $members = array_values(array_unique(array_map('intval', $members)));
            if (!in_array($leader_id, $members, true)) { $members[] = $leader_id; }

            if (!$owned) {
                $error_message = "Team not found or you do not have access to it.";
            } elseif ((int)$owned['event_id'] !== $event_id) {
                $error_message = "Event mismatch for this team.";
            } elseif (count($members) > (int)$owned['max_team_size']) {
                $error_message = "\"" . htmlspecialchars($owned['event_title']) . "\" allows a maximum of " . $owned['max_team_size'] . " member(s) per team — you selected " . count($members) . ".";
            } elseif (count($members) < (int)$owned['min_team_size']) {
                $error_message = "\"" . htmlspecialchars($owned['event_title']) . "\" requires at least " . $owned['min_team_size'] . " member(s) per team — you selected " . count($members) . ".";
            } elseif (!studentsAreEligible($pdo, $members, $event_id, $team_id)) {
                $error_message = "One or more selected students are not approved participants of this event, or are already assigned to another team.";
            } else {
                try {
                    $pdo->beginTransaction();

                    $stmt =$pdo->prepare("UPDATE teams SET team_name = :tname, leader_id = :lid WHERE team_id = :tid");
                    $stmt->execute(['tname' =>$team_name, 'lid' => $leader_id, 'tid' =>$team_id]);

                    $pdo->prepare("UPDATE registrations SET team_id = NULL, registration_type = 'solo' WHERE team_id = :tid")->execute(['tid' => $team_id]);
                    $pdo->prepare("DELETE FROM team_members WHERE team_id = :tid")->execute(['tid' => $team_id]);

                    $memStmt =$pdo->prepare("INSERT INTO team_members (team_id, student_id) VALUES (:tid, :sid)");
                    $regUpdateStmt =$pdo->prepare("UPDATE registrations SET team_id = :tid, registration_type = 'team' WHERE student_id = :sid AND event_id = :eid");

                    foreach ($members as $std_id) {$memStmt->execute(['tid' => $team_id, 'sid' => (int)$std_id]);
                        $regUpdateStmt->execute(['tid' =>$team_id, 'sid' => (int)$std_id, 'eid' =>$event_id]);
                    }

                    $pdo->commit();
                    $success_message = "Team '$team_name' has been updated successfully.";
                } catch (Exception $e) {
                    $pdo->rollBack();$error_message = "Failed to update team: " . $e->getMessage();
                }
            }
        }
    }

    // -- ASSIGN SINGLE STUDENT TO AN EXISTING TEAM --
    if (isset($_POST['action']) && $_POST['action'] === 'assign_student') {
        $team_id = (int)$_POST['team_id'];
        $student_id = (int)$_POST['student_id'];

        if (!$team_id || !$student_id) {
            $error_message = "Please select a team to assign this student to.";
        } else {
            $owned = getOwnedTeamInfo($pdo, $team_id, $college_id);

            if (!$owned) {
                $error_message = "Team not found or you do not have access to it.";
            } elseif ((int)$owned['current_size'] >= (int)$owned['max_team_size']) {
                $error_message = "Team is already at its maximum capacity (" . $owned['max_team_size'] . " members) for \"" . htmlspecialchars($owned['event_title']) . "\".";
            } elseif (!studentsAreEligible($pdo, [$student_id], (int)$owned['event_id'])) {
                $error_message = "This student is not an approved, unassigned participant for this event.";
            } else {
                try {
                    $pdo->beginTransaction();
                    $pdo->prepare("INSERT INTO team_members (team_id, student_id) VALUES (:tid, :sid)")
                        ->execute(['tid' => $team_id, 'sid' => $student_id]);
                    $pdo->prepare("UPDATE registrations SET team_id = :tid, registration_type = 'team' WHERE student_id = :sid AND event_id = :eid")
                        ->execute(['tid' => $team_id, 'sid' => $student_id, 'eid' => $owned['event_id']]);
                    $pdo->commit();
                    $success_message = "Student assigned to team successfully.";
                } catch (Exception $e) {
                    $pdo->rollBack();$error_message = "Failed to assign student: " . $e->getMessage();
                }
            }
        }
    }

    // -- DELETE TEAM --
    if (isset($_POST['action']) &&$_POST['action'] === 'delete_team') {
        $team_id = (int)$_POST['team_id'];
        $owned = getOwnedTeamInfo($pdo, $team_id, $college_id);

        if (!$owned) {
            $error_message = "Team not found or you do not have access to it.";
        } else {
            try {
                $pdo->beginTransaction();

                $upd =$pdo->prepare("UPDATE registrations SET team_id = NULL, registration_type = 'solo' WHERE team_id = :tid");
                $upd->execute(['tid' =>$team_id]);

                $pdo->prepare("DELETE FROM team_members WHERE team_id = :tid")->execute(['tid' => $team_id]);
                $pdo->prepare("DELETE FROM teams WHERE team_id = :tid")->execute(['tid' => $team_id]);

                $pdo->commit();$success_message = "Team disbanded successfully. Students returned to the eligible pool.";
            } catch (Exception $e) {
                $pdo->rollBack();$error_message = "Failed to remove team: " . $e->getMessage();
            }
        }
    }
}

// Fetch formed teams for events hosted by this college (with size limits so
// we know remaining capacity for the "assign existing student" panel)
$teamsStmt =$pdo->prepare("
    SELECT t.team_id, t.team_name, t.team_code, t.event_id, t.leader_id, e.title as event_title, st.name as leader_name,
           e.max_team_size, e.min_team_size,
           (SELECT COUNT(*) FROM team_members tm WHERE tm.team_id = t.team_id) as member_count,
           (SELECT GROUP_CONCAT(student_id) FROM team_members tm WHERE tm.team_id = t.team_id) as member_ids
    FROM teams t
    JOIN events e ON t.event_id = e.event_id
    JOIN students st ON t.leader_id = st.student_id
    WHERE e.college_id = :cid
    ORDER BY t.created_at DESC
");
$teamsStmt->execute(['cid' =>$college_id]);
$formedTeams =$teamsStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch students registered (and approved) for team events hosted by this college
$eligStmt =$pdo->prepare("
    SELECT s.student_id, s.name, s.enrollment_no, r.event_id, r.team_id, e.title AS event_title,
           e.max_team_size, e.min_team_size
    FROM students s
    JOIN registrations r ON s.student_id = r.student_id
    JOIN events e ON r.event_id = e.event_id
    WHERE e.college_id = :cid 
      AND e.event_type = 'team'
      AND r.status = 'approved'
    ORDER BY e.title ASC, s.name ASC
");
$eligStmt->execute(['cid' =>$college_id]);
$allStudentData =$eligStmt->fetchAll(PDO::FETCH_ASSOC);

$studentsByEvent = [];$activeTeamEvents = [];$eventLimits = [];$unassignedStudents = [];

foreach ($allStudentData as$row) {
    $eid =$row['event_id'];
    if (!isset($studentsByEvent[$eid])) {
        $studentsByEvent[$eid] = [];
    }
    $studentsByEvent[$eid][] =$row;

    $eventLimits[$eid] = ['min' => (int)$row['min_team_size'], 'max' => (int)$row['max_team_size']];

    if (empty($row['team_id'])) {
        $activeTeamEvents[$eid] = true;
        $unassignedStudents[] = $row;
    }
}

$createEventOptions = [];
if (!empty($activeTeamEvents)) {
    $eids = implode(',', array_keys($activeTeamEvents));
    $evStmt =$pdo->query("SELECT event_id, title, min_team_size, max_team_size FROM events WHERE event_id IN ($eids) ORDER BY title ASC");
    $createEventOptions =$evStmt->fetchAll(PDO::FETCH_ASSOC);
}

// Group existing teams by event, exposing remaining capacity, for the
// "Assign to Existing Team" dropdowns in the pending-students panel.
$teamsByEvent = [];
foreach ($formedTeams as $tm) {
    $teamsByEvent[$tm['event_id']][] = [
        'team_id'       => (int)$tm['team_id'],
        'team_name'     => $tm['team_name'],
        'current_size'  => (int)$tm['member_count'],
        'max_team_size' => (int)$tm['max_team_size'],
    ];
}

$page_title = 'college-teams';
include_once 'components/header.php';
include_once 'components/navbar.php';
?>

<style>
  :root { 
    --bg: #EEEBDA; 
    --surface: #F7F4E9; 
    --surface-hover: #FFFFFF; 
    --text: #282B4A; 
    --text-dim: rgba(40, 43, 74, 0.68); 
    --accent: #4B4F86; 
    --border-soft: rgba(40, 43, 74, 0.14); 
  }
  body, html, main { background-color: var(--bg) !important; color: var(--text) !important; }
</style>

<main class="relative z-10 min-h-screen py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 border-b border-[var(--border-soft)] pb-6 gap-4">
        <h1 class="text-3xl font-bold font-outfit text-[var(--text)] flex items-center gap-3">
            <i data-lucide="users" class="w-8 h-8 text-[var(--accent)]"></i> Team Management
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

    <!-- STUDENTS AWAITING TEAM ASSIGNMENT -->
    <div class="bg-[var(--surface)] border border-[var(--border-soft)] rounded-3xl p-6 md:p-8 shadow-xl mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h2 class="text-xl font-bold font-outfit text-[var(--text)]">Students Awaiting Team Assignment</h2>
                <p class="text-xs text-[var(--text-dim)] mt-1">Approved participants in your team events who aren't part of a team yet — assign each to an existing team below.</p>
            </div>
            <span class="bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text-dim)] px-4 py-2 rounded-xl text-xs font-bold shadow-inner">
                <?= count($unassignedStudents) ?> Pending
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-[var(--border-soft)] text-[var(--text-dim)] uppercase font-bold tracking-wider text-xs">
                        <th class="pb-3 px-3">Student</th>
                        <th class="pb-3 px-3">Event</th>
                        <th class="pb-3 px-3 text-right">Assign To Team</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-soft)] font-medium">
                    <?php if (empty($unassignedStudents)): ?>
                        <tr><td colspan="3" class="py-12 text-center text-[var(--text-dim)]">No students currently awaiting assignment.</td></tr>
                    <?php else: ?>
                        <?php foreach ($unassignedStudents as $us): ?>
                            <tr class="hover:bg-[var(--surface-hover)] assign-row" data-event-id="<?= $us['event_id'] ?>">
                                <td class="py-4 px-3">
                                    <div class="font-bold text-[var(--text)]"><?= htmlspecialchars($us['name']) ?></div>
                                    <div class="text-xs text-[var(--text-dim)]"><?= htmlspecialchars($us['enrollment_no']) ?></div>
                                </td>
                                <td class="py-4 px-3 text-[var(--text)] font-semibold"><?= htmlspecialchars($us['event_title']) ?></td>
                                <td class="py-4 px-3">
                                    <form method="POST" class="flex items-center justify-end gap-2 flex-wrap">
                                        <input type="hidden" name="action" value="assign_student">
                                        <input type="hidden" name="student_id" value="<?= (int)$us['student_id'] ?>">
                                        <select name="team_id" class="assign-team-select bg-[var(--bg)] border border-[var(--border-soft)] rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-[var(--accent)]" required>
                                            <option value="">-- Select Team --</option>
                                        </select>
                                        <button type="submit" class="assign-submit-btn bg-[var(--text)] text-[var(--bg)] px-3 py-2 rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-[#363A5E] transition-colors whitespace-nowrap">
                                            Assign
                                        </button>
                                        <button type="button" onclick="openCreateModalForEvent(<?= (int)$us['event_id'] ?>)" class="text-[9px] font-bold text-[var(--accent)] underline whitespace-nowrap">
                                            New Team
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

    <div class="bg-[var(--surface)] border border-[var(--border-soft)] rounded-3xl p-6 md:p-8 shadow-xl">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h2 class="text-xl font-bold font-outfit text-[var(--text)]">Active Team Rosters</h2>
                <p class="text-xs text-[var(--text-dim)] mt-1">Manage formed teams and assign eligible registered students.</p>
            </div>
            
            <?php if (empty($createEventOptions)): ?>
                <span class="bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text-dim)] px-4 py-2 rounded-xl text-xs font-bold shadow-inner">
                    No Pending Students
                </span>
            <?php else: ?>
                <button onclick="document.getElementById('modal-add-team').classList.replace('hidden', 'flex')" class="bg-[var(--text)] text-[var(--bg)] px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-[#363A5E] transition-colors flex items-center gap-2 shadow-md">
                    <i data-lucide="shield" class="w-4 h-4 text-emerald-400"></i> Form Team
                </button>
            <?php endif; ?>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-[var(--border-soft)] text-[var(--text-dim)] uppercase font-bold tracking-wider text-xs">
                        <th class="pb-3 px-3">Team Name & Code</th>
                        <th class="pb-3 px-3">Event</th>
                        <th class="pb-3 px-3">Leader & Count</th>
                        <th class="pb-3 px-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-soft)] font-medium">
                    <?php if (empty($formedTeams)): ?>
                        <tr><td colspan="4" class="py-12 text-center text-[var(--text-dim)]">No teams managed currently.</td></tr>
                    <?php else: ?>
                        <?php foreach ($formedTeams as$tm): 
                            $escapedName = htmlspecialchars($tm['team_name'], ENT_QUOTES);
                        ?>
                            <tr class="hover:bg-[var(--surface-hover)]">
                                <td class="py-4 px-3">
                                    <div class="font-bold text-[var(--text)] text-sm"><?= $escapedName ?></div>
                                    <div class="text-xs font-mono font-bold text-[var(--accent)] mt-1"><?= htmlspecialchars($tm['team_code']) ?></div>
                                </td>
                                <td class="py-4 px-3 text-[var(--text)] font-semibold"><?= htmlspecialchars($tm['event_title']) ?></td>
                                <td class="py-4 px-3">
                                    <div class="font-bold"><?= htmlspecialchars($tm['leader_name']) ?></div>
                                    <div class="text-xs text-[var(--text-dim)] mt-1 flex items-center gap-2">
                                        <span><?= $tm['member_count'] ?> / <?= (int)$tm['max_team_size'] ?> Members</span>
                                        <?php if ((int)$tm['member_count'] >= (int)$tm['max_team_size']): ?>
                                            <span class="text-[9px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">Full</span>
                                        <?php elseif ((int)$tm['member_count'] < (int)$tm['min_team_size']): ?>
                                            <span class="text-[9px] font-bold uppercase tracking-wider bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">Below Min</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="py-4 px-3 text-right space-x-1">
                                    <button onclick="openEditModal(<?= $tm['team_id'] ?>, <?= $tm['event_id'] ?>, '<?=$escapedName ?>', <?= $tm['leader_id'] ?>, '<?=$tm['member_ids'] ?>')" 
                                            class="text-blue-600 hover:text-blue-800 p-2 border border-blue-200 bg-blue-50 rounded-lg shadow-sm transition-colors" title="Edit Team">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </button>
                                    
                                    <form method="POST" class="inline" onsubmit="return confirm('Disband this team? Students will be returned to the eligible pool.');">
                                        <input type="hidden" name="action" value="delete_team">
                                        <input type="hidden" name="team_id" value="<?= $tm['team_id'] ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-800 p-2 border border-red-200 bg-red-50 rounded-lg shadow-sm transition-colors" title="Disband Team">
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

<!-- CREATE TEAM MODAL -->
<?php if (!empty($createEventOptions)): ?>
<div id="modal-add-team" class="fixed inset-0 z-[100] hidden items-center justify-center bg-[#282B4A]/60 backdrop-blur-sm p-4">
    <div class="bg-[var(--surface)] w-full max-w-lg rounded-3xl p-8 shadow-2xl border border-[var(--border-soft)] max-h-[90vh] overflow-y-auto relative">
        <button type="button" onclick="document.getElementById('modal-add-team').classList.replace('flex', 'hidden')" class="absolute top-6 right-6 p-2 bg-[var(--bg)] rounded-full text-[var(--text)] hover:bg-[var(--border-soft)] transition-colors">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
        <h2 class="text-2xl font-bold font-outfit text-[var(--text)] mb-6 border-b border-[var(--border-soft)] pb-4">Form Team</h2>
        
        <form method="POST" class="space-y-5" id="createTeamForm" onsubmit="return validateTeamForm(this, false);">
            <input type="hidden" name="action" value="create_team">
            
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1">Select Event <span class="text-red-500">*</span></label>
                <select name="event_id" id="create_event_select" required class="w-full bg-[var(--bg)] border border-[var(--border-soft)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--accent)]" onchange="updateCreateLists()">
                    <option value="">-- Choose Event with Pending Students --</option>
                    <?php foreach ($createEventOptions as$ev): ?>
                        <option value="<?= $ev['event_id'] ?>"><?= htmlspecialchars($ev['title']) ?> (Team size: <?= (int)$ev['min_team_size'] ?>–<?= (int)$ev['max_team_size'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1">Team Name <span class="text-red-500">*</span></label>
                <input type="text" name="team_name" required class="w-full bg-[var(--bg)] border border-[var(--border-soft)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--accent)]">
            </div>
            
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1">Designate Leader <span class="text-red-500">*</span></label>
                <select name="leader_id" id="create_leader_select" required disabled class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--accent)] disabled:opacity-50">
                    <option value="">-- Select Event First --</option>
                </select>
            </div>
            
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1">Select Members (Ctrl/Cmd Click)</label>
                <select name="members[]" id="create_members_select" multiple size="4" disabled class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] rounded-xl p-3 text-sm focus:outline-none focus:border-[var(--accent)] shadow-inner disabled:opacity-50">
                </select>
                <span id="create_size_hint" class="text-[9px] text-[var(--text-faint)] mt-1 block"></span>
            </div>
            
            <button type="submit" class="w-full bg-[var(--text)] text-[var(--bg)] font-bold py-4 rounded-xl hover:bg-[#363A5E] uppercase tracking-wider text-xs mt-6 shadow-lg">Generate Team & Roster</button>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- EDIT TEAM MODAL -->
<div id="modal-edit-team" class="fixed inset-0 z-[100] hidden items-center justify-center bg-[#282B4A]/60 backdrop-blur-sm p-4">
    <div class="bg-[var(--surface)] w-full max-w-lg rounded-3xl p-8 shadow-2xl border border-[var(--border-soft)] max-h-[90vh] overflow-y-auto relative">
        <button type="button" onclick="document.getElementById('modal-edit-team').classList.replace('flex', 'hidden')" class="absolute top-6 right-6 p-2 bg-[var(--bg)] rounded-full text-[var(--text)] hover:bg-[var(--border-soft)] transition-colors">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
        <h2 class="text-2xl font-bold font-outfit text-[var(--text)] mb-6 border-b border-[var(--border-soft)] pb-4">Edit Team Roster</h2>
        
        <form method="POST" class="space-y-5" id="editTeamForm" onsubmit="return validateTeamForm(this, true);">
            <input type="hidden" name="action" value="edit_team">
            <input type="hidden" name="team_id" id="edit_team_id">
            <input type="hidden" name="event_id" id="edit_event_id">
            
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1">Team Name <span class="text-red-500">*</span></label>
                <input type="text" name="team_name" id="edit_team_name" required class="w-full bg-[var(--bg)] border border-[var(--border-soft)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--accent)]">
            </div>
            
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1">Designate Leader <span class="text-red-500">*</span></label>
                <select name="leader_id" id="edit_leader_select" required class="w-full bg-[var(--bg)] border border-[var(--border-soft)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--accent)]">
                </select>
            </div>
            
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1">Select Members (Ctrl/Cmd Click)</label>
                <select name="members[]" id="edit_members_select" multiple size="5" class="w-full bg-[var(--bg)] border border-[var(--border-soft)] rounded-xl p-3 text-sm focus:outline-none focus:border-[var(--accent)] shadow-inner">
                </select>
                <span class="text-[9px] text-[var(--text-faint)] mt-1 block">Includes current members + any unassigned students for this event.</span>
                <span id="edit_size_hint" class="text-[9px] text-[var(--text-faint)] mt-1 block"></span>
            </div>
            
            <button type="submit" class="w-full bg-[var(--text)] text-[var(--bg)] font-bold py-4 rounded-xl hover:bg-[#363A5E] uppercase tracking-wider text-xs mt-6 shadow-lg">Save Changes</button>
        </form>
    </div>
</div>

<script>
    const studentsByEvent = <?= json_encode($studentsByEvent) ?>;
    const teamsByEvent = <?= json_encode($teamsByEvent) ?>;
    const eventLimits = <?= json_encode($eventLimits) ?>;

    function updateCreateLists() {
        const eventId = document.getElementById('create_event_select').value;
        const leaderSelect = document.getElementById('create_leader_select');
        const membersSelect = document.getElementById('create_members_select');
        const hint = document.getElementById('create_size_hint');
        
        leaderSelect.innerHTML = '<option value="">-- Select Student --</option>';
        membersSelect.innerHTML = '';
        if (hint) hint.textContent = '';
        
        if (!eventId || !studentsByEvent[eventId]) {
            leaderSelect.disabled = true;
            membersSelect.disabled = true;
            return;
        }
        
        leaderSelect.disabled = false;
        membersSelect.disabled = false;

        const limits = eventLimits[eventId];
        if (limits && hint) {
            hint.textContent = `Team size allowed: ${limits.min}–${limits.max} member(s), including the leader.`;
        }
        
        studentsByEvent[eventId].forEach(student => {
            if (!student.team_id) {
                const optionText = `${student.name} (${student.enrollment_no})`;
                
                const opt1 = document.createElement('option');
                opt1.value = student.student_id;
                opt1.textContent = optionText;
                leaderSelect.appendChild(opt1);
                
                const opt2 = document.createElement('option');
                opt2.value = student.student_id;
                opt2.textContent = optionText;
                membersSelect.appendChild(opt2);
            }
        });
    }

    function openEditModal(teamId, eventId, teamName, leaderId, memberIdsStr) {
        document.getElementById('edit_team_id').value = teamId;
        document.getElementById('edit_event_id').value = eventId;
        document.getElementById('edit_team_name').value = teamName;
        
        const leaderSelect = document.getElementById('edit_leader_select');
        const membersSelect = document.getElementById('edit_members_select');
        const hint = document.getElementById('edit_size_hint');
        
        leaderSelect.innerHTML = '';
        membersSelect.innerHTML = '';

        const limits = eventLimits[eventId];
        if (limits && hint) {
            hint.textContent = `Team size allowed: ${limits.min}–${limits.max} member(s), including the leader.`;
        }
        
        const currentMemberIds = memberIdsStr.split(',').map(id => parseInt(id.trim()));

        if (studentsByEvent[eventId]) {
            studentsByEvent[eventId].forEach(student => {
                const sId = parseInt(student.student_id);
                if (!student.team_id || student.team_id == teamId) {
                    const optionText = `${student.name} (${student.enrollment_no})`;
                    
                    const opt1 = document.createElement('option');
                    opt1.value = sId;
                    opt1.textContent = optionText;
                    if (sId === leaderId) opt1.selected = true;
                    leaderSelect.appendChild(opt1);
                    
                    const opt2 = document.createElement('option');
                    opt2.value = sId;
                    opt2.textContent = optionText;
                    if (currentMemberIds.includes(sId)) opt2.selected = true;
                    membersSelect.appendChild(opt2);
                }
            });
        }
        
        document.getElementById('modal-edit-team').classList.replace('hidden', 'flex');
    }

    // Client-side guard for team size (server re-validates authoritatively)
    function validateTeamForm(form, isEdit) {
        const eventId = isEdit ? document.getElementById('edit_event_id').value : document.getElementById('create_event_select').value;
        const leaderVal = form.querySelector('[name="leader_id"]').value;
        const leaderId = leaderVal ? parseInt(leaderVal) : null;
        const memberSelect = form.querySelector('[name="members[]"]');
        const selectedIds = Array.from(memberSelect.selectedOptions).map(o => parseInt(o.value));
        if (leaderId && !selectedIds.includes(leaderId)) selectedIds.push(leaderId);
        const uniqueCount = new Set(selectedIds).size;

        const limits = eventLimits[eventId];
        if (limits) {
            if (uniqueCount > limits.max) {
                alert(`This event allows a maximum of ${limits.max} member(s) per team. You have selected ${uniqueCount}.`);
                return false;
            }
            if (uniqueCount < limits.min) {
                alert(`This event requires at least ${limits.min} member(s) per team. You have selected ${uniqueCount} — add more before saving.`);
                return false;
            }
        }
        return true;
    }

    // Populate the "Assign to Existing Team" dropdown for every pending student,
    // scoped to that student's event and skipping teams already at capacity.
    function populateAssignSelects() {
        document.querySelectorAll('.assign-row').forEach(function (row) {
            const eventId = row.getAttribute('data-event-id');
            const sel = row.querySelector('.assign-team-select');
            const btn = row.querySelector('.assign-submit-btn');
            const teams = teamsByEvent[eventId] || [];
            const openTeams = teams.filter(t => t.current_size < t.max_team_size);

            if (openTeams.length === 0) {
                sel.innerHTML = '<option value="">No open team — create one</option>';
                sel.disabled = true;
                sel.required = false;
                btn.disabled = true;
                btn.classList.add('opacity-40', 'cursor-not-allowed');
            } else {
                sel.disabled = false;
                btn.disabled = false;
                btn.classList.remove('opacity-40', 'cursor-not-allowed');
                sel.innerHTML = '<option value="">-- Select Team --</option>' + openTeams.map(t =>
                    `<option value="${t.team_id}">${t.team_name} (${t.current_size}/${t.max_team_size})</option>`
                ).join('');
            }
        });
    }

    // Opens the "Form Team" modal pre-scoped to a given event (used by the
    // "New Team" shortcut next to each pending student).
    function openCreateModalForEvent(eventId) {
        const modal = document.getElementById('modal-add-team');
        const sel = document.getElementById('create_event_select');
        if (!modal || !sel) return;
        modal.classList.replace('hidden', 'flex');
        sel.value = eventId;
        updateCreateLists();
    }

    document.addEventListener('DOMContentLoaded', populateAssignSelects);
</script>

<script>if (typeof lucide !== 'undefined') { lucide.createIcons(); }</script>
<?php include_once 'components/footer.php'; ?>