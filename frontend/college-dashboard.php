<?php
session_start();

if (!isset($_SESSION['college_id']) OR$_SESSION['user_role'] !== 'college') {
    header("Location: college-login.php");
    exit();
}

$college_id =$_SESSION['college_id'];
$college_name =$_SESSION['college_name'];

$host = 'localhost';$dbname = 'evenza';
$username = 'root';$password = '';     

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$statStudents =$pdo->prepare("SELECT COUNT(*) FROM students WHERE college_id = :cid");
$statStudents->execute(['cid' =>$college_id]);
$totalCollegeStudents =$statStudents->fetchColumn();

$statEvents =$pdo->prepare("SELECT COUNT(*) FROM events WHERE college_id = :cid");
$statEvents->execute(['cid' =>$college_id]);
$totalCollegeEvents =$statEvents->fetchColumn();

$studentsStmt =$pdo->prepare("
    SELECT s.student_id, s.name, s.enrollment_no, s.email, 
           e.title as event_title, e.event_id, e.registration_fee, 
           MIN(r.status) as status, 
           MAX(r.registered_at) as registered_at,
           COALESCE(MAX(p.amount), e.registration_fee, 0) as total_paid
    FROM students s
    JOIN registrations r ON s.student_id = r.student_id
    JOIN events e ON r.event_id = e.event_id
    LEFT JOIN payments p ON r.registration_id = p.registration_id AND p.payment_status = 'paid'
    WHERE e.college_id = :cid
    GROUP BY s.student_id, s.name, s.enrollment_no, s.email, e.title, e.event_id, e.registration_fee
    ORDER BY registered_at DESC
");
$studentsStmt->execute(['cid' =>$college_id]);
$collegeRegistrations =$studentsStmt->fetchAll(PDO::FETCH_ASSOC);

$totalGrossRevenue = 0;
foreach ($collegeRegistrations as$reg) {
    if (strtolower($reg['status']) === 'approved') {
        $totalGrossRevenue += (float)$reg['total_paid'];
    }
}
$collegeEarnings =$totalGrossRevenue * 0.95; 

$page_title = 'college-dashboard';
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
    --border-soft: rgba(40, 43, 74, 0.14);
    --border-accent: rgba(75, 79, 134, 0.35);
  }
  body, html, main { background-color: var(--bg) !important; color: var(--text) !important; }

  .eb-input {
    background-color: var(--bg);
    border: 1px solid var(--border-soft);
    color: var(--text);
    border-radius: 0.85rem;
    padding: 0.5rem 1rem 0.5rem 2.25rem;
    font-size: 0.8rem;
    outline: none;
    transition: border-color 0.2s ease;
  }
  .eb-input:focus { border-color: var(--accent); }

  .eb-select {
    background-color: var(--bg);
    border: 1px solid var(--border-soft);
    color: var(--text);
    border-radius: 0.85rem;
    padding: 0.5rem 1.75rem 0.5rem 0.75rem;
    font-size: 0.8rem;
    outline: none;
    cursor: pointer;
  }
  .eb-select:focus { border-color: var(--accent); }
</style>

<main class="relative z-10 min-h-screen py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="absolute inset-0 z-[-1] pointer-events-none" 
         style="background-image: linear-gradient(to right, rgba(75,79,134,0.08) 1px, transparent 1px), linear-gradient(to bottom, rgba(75,79,134,0.08) 1px, transparent 1px); background-size: 50px 50px;">
    </div>

    <div class="bg-[var(--surface)] border border-[var(--border-soft)] rounded-3xl p-6 md:p-8 shadow-xl mb-8 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded bg-[var(--accent)]/10 text-[var(--accent)] text-[10px] font-bold uppercase tracking-widest border border-[var(--border-accent)] mb-3">
                <i data-lucide="graduation-cap" class="w-3.5 h-3.5"></i> Institutional Coordinator Panel
            </div>
            <h1 class="text-3xl font-bold font-outfit text-[var(--text)]"><?= htmlspecialchars($college_name) ?></h1>
        </div>
        
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 w-full lg:w-auto">
            <div class="bg-[var(--bg)] border border-[var(--border-soft)] p-3 rounded-xl text-center shadow-sm lg:min-w-[120px]">
                <span class="block text-[9px] uppercase font-bold text-[var(--text-dim)]">Platform Students</span>
                <span class="text-lg font-bold font-mono text-[var(--accent)]"><?= $totalCollegeStudents ?></span>
            </div>
            <div class="bg-[var(--bg)] border border-[var(--border-soft)] p-3 rounded-xl text-center shadow-sm lg:min-w-[120px]">
                <span class="block text-[9px] uppercase font-bold text-[var(--text-dim)]">Events</span>
                <span class="text-lg font-bold font-mono text-[var(--text)]"><?= $totalCollegeEvents ?></span>
            </div>
            <div class="bg-[var(--bg)] border border-[var(--border-soft)] p-3 rounded-xl text-center shadow-sm lg:min-w-[120px]">
                <span class="block text-[9px] uppercase font-bold text-[var(--text-dim)]">Gross Revenue</span>
                <span class="text-lg font-bold font-mono text-[var(--text)]">₹<?= number_format($totalGrossRevenue, 2) ?></span>
            </div>
            <div class="bg-[var(--bg)] border border-[var(--border-soft)] p-3 rounded-xl text-center shadow-sm lg:min-w-[120px]">
                <span class="block text-[9px] uppercase font-bold text-[var(--text-dim)]">College Share (95%)</span>
                <span class="text-lg font-bold font-mono text-emerald-700">₹<?= number_format($collegeEarnings, 2) ?></span>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <a href="college-events.php" class="bg-[var(--surface)] text-[var(--text)] border border-[var(--border-soft)] hover:border-[var(--accent)] px-5 py-2.5 rounded-xl text-xs font-bold transition-colors shadow-sm flex items-center gap-2 uppercase tracking-wider">
            <i data-lucide="calendar" class="w-4 h-4 text-[var(--accent)]"></i> Manage Events
        </a>
        <a href="college-teams.php" class="bg-[var(--text)] text-[var(--bg)] hover:bg-[#363A5E] px-5 py-2.5 rounded-xl text-xs font-bold transition-colors shadow-md flex items-center gap-2 uppercase tracking-wider">
            <i data-lucide="users" class="w-4 h-4 text-emerald-400"></i> Manage Teams
        </a>
    </div>

    <div class="bg-[var(--surface)] border border-[var(--border-soft)] rounded-3xl p-6 md:p-8 shadow-xl">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6 pb-4 border-b border-[var(--border-soft)]">
            <h2 class="text-xl font-bold font-outfit text-[var(--text)] flex items-center gap-2">
                <i data-lucide="wallet" class="w-5 h-5 text-[var(--accent)]"></i> Live Registration & Revenue Ledger
            </h2>

            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <div class="relative w-full sm:w-64">
                    <i data-lucide="search" class="w-3.5 h-3.5 absolute left-3 top-3 text-[var(--accent)]"></i>
                    <input type="text" id="ledger-search" placeholder="Search student or event..." class="eb-input w-full" onkeyup="filterLedger()">
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-[var(--text-dim)]">Show:</span>
                    <select id="ledger-limit" class="eb-select" onchange="changeLedgerLimit()">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" id="ledger-table">
                <thead>
                    <tr class="border-b border-[var(--border-soft)] text-[var(--text-faint)] uppercase font-bold tracking-wider text-xs">
                        <th class="pb-3 px-3">Student Name & ID</th>
                        <th class="pb-3 px-3">Registered Event</th>
                        <th class="pb-3 px-3">Ticket Fee (Gross)</th>
                        <th class="pb-3 px-3">College Payout (95%)</th>
                        <th class="pb-3 px-3">Status</th>
                        <th class="pb-3 px-3">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-soft)] font-medium" id="ledger-tbody">
                    <?php if (empty($collegeRegistrations)): ?>
                        <tr class="empty-row">
                            <td colspan="6" class="py-12 text-center text-[var(--text-dim)]">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i data-lucide="folder-open" class="w-8 h-8 opacity-40"></i>
                                    <p>No student registrations found for your events yet.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($collegeRegistrations as $reg):$fee = (float)$reg['total_paid'];$payout = (strtolower($reg['status']) === 'approved') ? ($fee * 0.95) : 0;
                        ?>
                            <tr class="ledger-row hover:bg-[var(--surface-hover)] transition-colors" data-search="<?= strtolower(htmlspecialchars($reg['name'] . ' ' . $reg['enrollment_no'] . ' ' .$reg['event_title'])) ?>">
                                <td class="py-4 px-3">
                                    <div class="font-bold text-[var(--text)] text-sm"><?= htmlspecialchars($reg['name']) ?></div>
                                    <div class="text-xs text-[var(--text-dim)] font-mono mt-0.5"><?= htmlspecialchars($reg['enrollment_no']) ?></div>
                                </td>
                                <td class="py-4 px-3 text-[var(--accent)] font-semibold"><?= htmlspecialchars($reg['event_title']) ?></td>
                                <td class="py-4 px-3 font-mono text-[var(--text)]">₹<?= number_format($fee, 2) ?></td>
                                <td class="py-4 px-3 font-mono font-bold text-emerald-700">₹<?= number_format($payout, 2) ?></td>
                                <td class="py-4 px-3">
                                    <span class="px-3 py-1.5 rounded text-xs font-bold uppercase tracking-wider <?= strtolower($reg['status']) === 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' ?>">
                                        <?= htmlspecialchars($reg['status']) ?>
                                    </span>
                                </td>
                                <td class="py-4 px-3 text-[var(--text-dim)] text-sm font-mono">
                                    <?= date('M d, Y', strtotime($reg['registered_at'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6 pt-4 border-t border-[var(--border-soft)] text-xs text-[var(--text-dim)]">
            <div id="ledger-counter">Showing records</div>
            <div class="flex items-center gap-1.5" id="ledger-pagination"></div>
        </div>
    </div>
</main>

<script>
    let currentPage = 1;
    let rowsPerPage = 10;

    function renderLedger() {
        const searchInput = document.getElementById('ledger-search').value.toLowerCase();
        const limitSelect = document.getElementById('ledger-limit').value;
        rowsPerPage = limitSelect === 'all' ? 100000 : parseInt(limitSelect);

        const rows = document.querySelectorAll('.ledger-row');
        let matchedRows = [];

        rows.forEach(row => {
            const searchData = row.getAttribute('data-search');
            if (searchData.includes(searchInput)) {
                matchedRows.push(row);
                row.style.display = 'none'; 
            } else {
                row.style.display = 'none';
            }
        });

        const totalMatched = matchedRows.length;
        const totalPages = Math.ceil(totalMatched / rowsPerPage) || 1;
        if (currentPage > totalPages) currentPage = totalPages;

        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const pageRows = matchedRows.slice(start, end);

        pageRows.forEach(row => {
            row.style.display = '';
        });

        const counterEl = document.getElementById('ledger-counter');
        if (totalMatched === 0) {
            counterEl.textContent = "No matching records found";
        } else {
            counterEl.textContent = `Showing ${start + 1} to ${Math.min(end, totalMatched)} of ${totalMatched} entries`;
        }

        const paginationEl = document.getElementById('ledger-pagination');
        let paginationHTML = '';

        paginationHTML += `<button onclick="changePage(${currentPage - 1})" class="px-3 py-1.5 rounded-lg border border-[var(--border-soft)] bg-[var(--bg)] font-bold hover:border-[var(--accent)] ${currentPage === 1 ? 'opacity-40 pointer-events-none' : ''}">Prev</button>`;

        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                paginationHTML += `<button onclick="changePage(${i})" class="px-3 py-1.5 rounded-lg border font-bold ${currentPage === i ? 'bg-[var(--accent)] text-[var(--bg)] border-[var(--accent)]' : 'border-[var(--border-soft)] bg-[var(--bg)] hover:border-[var(--accent)]'}">${i}</button>`;
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                paginationHTML += `<span class="px-1 text-[var(--text-faint)]">...</span>`;
            }
        }

        paginationHTML += `<button onclick="changePage(${currentPage + 1})" class="px-3 py-1.5 rounded-lg border border-[var(--border-soft)] bg-[var(--bg)] font-bold hover:border-[var(--accent)] ${currentPage === totalPages ? 'opacity-40 pointer-events-none' : ''}">Next</button>`;

        paginationEl.innerHTML = paginationHTML;
    }

    function filterLedger() {
        currentPage = 1;
        renderLedger();
    }

    function changeLedgerLimit() {
        currentPage = 1;
        renderLedger();
    }

    function changePage(page) {
        currentPage = page;
        renderLedger();
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderLedger();
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>

<?php include_once 'components/footer.php'; ?>