<?php
// C:\xampp\htdocs\Project2\frontend\pay.php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;

if (!$event_id) {
    header("Location: events.php");
    exit();
}

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

// Fetch Event Details including fee
$eventStmt = $pdo->prepare("SELECT title, registration_fee FROM events WHERE event_id = :eid");
$eventStmt->execute(['eid' => $event_id]);
$event = $eventStmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    header("Location: events.php");
    exit();
}

$fee = (float)$event['registration_fee'];
$admin_commission = $fee * 0.05; // 5% Admin Cut

// Handle Form Submission / Database Insertion
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $fee <= 0) {
    
    // Check if already registered
    $checkStmt = $pdo->prepare("SELECT registration_id FROM registrations WHERE student_id = :uid AND event_id = :eid");
    $checkStmt->execute(['uid' => $user_id, 'eid' => $event_id]);
    $reg = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if (!$reg) {
        $ins = $pdo->prepare("
            INSERT INTO registrations (student_id, event_id, status) 
            VALUES (:uid, :eid, 'approved')
        ");
        $ins->execute(['uid' => $user_id, 'eid' => $event_id]);
        $registration_id = $pdo->lastInsertId();
    } else {
        $registration_id = $reg['registration_id'];
    }

    // Record transaction in payments table if paid event
    if ($fee > 0) {
        $tx_id = 'TXN_EVN_' . strtoupper(uniqid());
        $payStmt = $pdo->prepare("
            INSERT INTO payments (registration_id, amount, payment_method, transaction_id, payment_status, payment_date) 
            VALUES (:rid, :amt, 'upi', :txid, 'paid', NOW())
        ");
        $payStmt->execute([
            'rid' => $registration_id,
            'amt' => $fee,
            'txid' => $tx_id
        ]);
    }
    
    // Return success response for AJAX or normal redirect fallback
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo json_encode(['status' => 'success']);
        exit();
    }

    header("Location: dashboard.php?payment=success");
    exit();
}

$page_title = 'checkout';
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
  
  @keyframes scaleUp {
      0% { transform: scale(0.8); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
  }
  .animate-scale-up { animation: scaleUp 0.3s ease-out forwards; }
</style>

<main class="relative z-10 min-h-screen flex items-center justify-center py-20 pt-32">
    <div class="absolute inset-0 z-[-1] pointer-events-none" 
         style="background-image: linear-gradient(to right, rgba(75,79,134,0.08) 1px, transparent 1px), linear-gradient(to bottom, rgba(75,79,134,0.08) 1px, transparent 1px); background-size: 50px 50px;">
    </div>

    <div class="max-w-[500px] w-full mx-auto px-4 relative z-10">
        <div class="bg-[var(--surface)] border border-[var(--border-soft)] rounded-2xl p-8 md:p-10 shadow-xl relative overflow-hidden">
            
            <div class="absolute top-0 right-0 w-24 h-24 bg-[var(--accent)]/5 rounded-bl-full pointer-events-none"></div>

            <div class="text-center mb-6">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-emerald-100 border border-emerald-300 text-emerald-800 text-[10px] font-bold uppercase tracking-widest mb-3">
                    <i data-lucide="lock" class="w-3 h-3"></i> 256-Bit SSL Secure Checkout
                </div>
                <h1 class="text-2xl font-bold font-outfit text-[var(--text)] tracking-tight">Eventra Secure Pay</h1>
                <p class="text-xs text-[var(--text-dim)] mt-1">Complete your registration payment securely.</p>
            </div>

            <!-- Order Summary Box -->
            <div class="bg-[var(--bg)] border border-[var(--border-soft)] rounded-xl p-5 mb-6 space-y-3">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-[var(--text-dim)] font-semibold uppercase tracking-wider">Event</span>
                    <span class="font-bold text-[var(--text)] truncate max-w-[220px]"><?= htmlspecialchars($event['title']) ?></span>
                </div>
                <div class="flex justify-between items-center text-xs border-t border-[var(--border-soft)] pt-3">
                    <span class="text-[var(--text-dim)] font-semibold uppercase tracking-wider">Registration Ticket</span>
                    <span class="font-mono font-bold text-[var(--accent)]">₹<?= number_format($fee, 2) ?></span>
                </div>
                <div class="flex justify-between items-center text-sm border-t border-[var(--border-soft)] pt-3 font-bold">
                    <span class="text-[var(--text)] uppercase tracking-wider text-xs">Total Amount</span>
                    <span class="font-mono text-lg text-[var(--text)]">₹<?= number_format($fee, 2) ?></span>
                </div>
            </div>

            <!-- Unified Payment Action Form -->
            <form id="payment-form" method="POST" onsubmit="processPayment(event)" class="space-y-4">
                <div class="bg-[var(--surface-hover)] p-4 rounded-xl border border-[var(--border-soft)] flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-[var(--bg-alt)] flex items-center justify-center text-[var(--accent)]">
                            <i data-lucide="credit-card" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-[var(--text)]">Instant Online Payment</p>
                            <p class="text-[10px] text-[var(--text-dim)]">UPI, Credit/Debit Card, NetBanking</p>
                        </div>
                    </div>
                    <span class="w-4 h-4 rounded-full border-2 border-[var(--accent)] flex items-center justify-center">
                        <span class="w-2 h-2 rounded-full bg-[var(--accent)]"></span>
                    </span>
                </div>

                <button type="submit" id="pay-btn" class="w-full bg-[var(--text)] text-[var(--bg)] font-bold py-4 rounded-xl hover:bg-[#363A5E] transition-colors shadow-lg uppercase tracking-wider text-xs flex items-center justify-center gap-2">
                    <i data-lucide="shield-check" class="w-4 h-4 text-emerald-400"></i> Pay ₹<?= number_format($fee, 2) ?> Securely
                </button>
            </form>

        </div>
    </div>
</main>

<!-- Payment Success Overlay Animation Modal -->
<div id="success-modal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-[#282B4A]/70 backdrop-blur-sm transition-opacity">
    <div class="bg-[var(--surface)] w-full max-w-sm rounded-2xl p-8 shadow-2xl border border-[var(--border-soft)] text-center animate-scale-up space-y-4">
        <div class="w-20 h-20 bg-emerald-100 border border-emerald-300 text-emerald-700 rounded-full flex items-center justify-center mx-auto shadow-inner">
            <i data-lucide="check" class="w-10 h-10 stroke-[3]"></i>
        </div>
        <h3 class="text-xl font-bold font-outfit text-[var(--text)]">Payment Successful!</h3>
        <p class="text-xs text-[var(--text-dim5)]">Transaction verified. Generating your ticket and updating records...</p>
        <div class="w-6 h-6 border-2 border-[var(--accent)] border-t-transparent rounded-full animate-spin mx-auto mt-4"></div>
    </div>
</div>

<script>
    function processPayment(event) {
        event.preventDefault();
        
        const btn = document.getElementById('pay-btn');
        btn.disabled = true;
        btn.innerHTML = `<span class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin inline-block"></span> Processing Securely...`;

        // Show success animation modal after short simulated verification delay
        setTimeout(() => {
            const modal = document.getElementById('success-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            if (typeof lucide !== 'undefined') lucide.createIcons();

            // Submit form via fetch or direct submit after showing animation for 1.5 seconds
            setTimeout(() => {
                document.getElementById('payment-form').submit();
            }, 1500);
        }, 1000);
    }

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>

<?php include_once 'components/footer.php'; ?>