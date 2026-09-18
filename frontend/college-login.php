<?php
// C:\xampp\htdocs\Project2\frontend\college-login.php
session_start();

// Database Connection
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
$success_message = isset($_SESSION['register_success']) ? $_SESSION['register_success'] : '';
unset($_SESSION['register_success']); // Clear message after reading

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $pass = trim($_POST['password'] ?? '');

    // --- MANUAL VALIDATION ---
    if (empty($email)) {
        $error_message = "Please enter your college email address.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address format.";
    } elseif (empty($pass)) {
        $error_message = "Please enter your password.";
    } else {
        // Fetch college data based on email first
        $stmt = $pdo->prepare("SELECT * FROM colleges WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $college = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($college) {
            // Verify password
            if ($pass === $college['password'] || password_verify($pass, $college['password'])) {
                
                // --- Ban / Inactive Check ---
                if (strtolower($college['status']) !== 'active') {
                    $error_message = "Access Denied: Your institutional account has been deactivated or suspended by the administrator.";
                } else {
                    // Valid and active: Create Session
                    $_SESSION['college_id'] = $college['college_id'];
                    $_SESSION['college_name'] = $college['name'];
                    $_SESSION['college_email'] = $college['email'];
                    $_SESSION['user_role'] = 'college';

                    header("Location: college-dashboard.php");
                    exit();
                }
            } else {
                $error_message = "Invalid college email or password.";
            }
        } else {
            $error_message = "Invalid college email or password.";
        }
    }
}

$page_title = 'college-login';
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

<main class="relative z-10 min-h-screen flex items-center justify-center py-20 px-4 pt-32">
    <div class="absolute inset-0 z-[-1] pointer-events-none" 
         style="background-image: linear-gradient(to right, rgba(75,79,134,0.08) 1px, transparent 1px), linear-gradient(to bottom, rgba(75,79,134,0.08) 1px, transparent 1px); background-size: 50px 50px;">
    </div>

    <div class="max-w-[520px] w-full mx-auto relative z-10">
        <div class="bg-[var(--surface)] border border-[var(--border-soft)] rounded-3xl p-10 md:p-12 shadow-2xl relative overflow-hidden">
            
            <div class="absolute top-0 right-0 w-32 h-32 bg-[var(--accent)]/5 rounded-bl-full pointer-events-none"></div>

            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-[var(--bg-alt)] border border-[var(--border-soft)] text-[var(--text)] mb-4 shadow-sm">
                    <i data-lucide="graduation-cap" class="w-8 h-8 text-[var(--accent)]"></i>
                </div>
                <h1 class="text-3xl font-bold font-outfit text-[var(--text)] tracking-tight">College Coordinator Portal</h1>
                <p class="text-sm text-[var(--text-dim)] mt-1.5">Sign in to manage institutional teams and students.</p>
            </div>

            <!-- Success Notification Banner -->
            <?php if (!empty($success_message)): ?>
                <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3.5 rounded-xl mb-6 text-xs flex items-center gap-2.5 shadow-sm">
                    <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
                    <span><?= htmlspecialchars($success_message); ?></span>
                </div>
            <?php endif; ?>

            <!-- Error and Ban Notification Banner -->
            <?php if (!empty($error_message)): ?>
                <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3.5 rounded-xl mb-6 text-xs flex items-center gap-2.5 shadow-sm">
                    <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
                    <span><?= htmlspecialchars($error_message); ?></span>
                </div>
            <?php endif; ?>

            <!-- Form with manual validation and preserved inputs (no 'required' attributes) -->
            <form method="POST" action="college-login.php" class="space-y-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-[var(--accent)] mb-2">College Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" 
                           class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-xl px-4 py-3.5 focus:outline-none focus:border-[var(--accent)] transition-colors text-sm shadow-inner"
                           placeholder="engineering@college.edu" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-[var(--accent)] mb-2">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" 
                           class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-xl px-4 py-3.5 focus:outline-none focus:border-[var(--accent)] transition-colors text-sm shadow-inner"
                           placeholder="••••••••">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-[var(--text)] text-[var(--bg)] font-bold py-4 px-6 rounded-xl hover:bg-[#363A5E] transition-colors shadow-lg uppercase tracking-wider text-xs flex items-center justify-center gap-2">
                        Sign In to College Panel <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </div>
            </form>

            <!-- Registration Link for New Colleges -->
            <div class="mt-6 text-center text-xs text-[var(--text-dim)] border-t border-[var(--border-soft)] pt-6">
                Want to register your institution? 
                <a href="college-register.php" class="font-bold text-[var(--accent)] hover:text-[var(--text)] transition-colors">Register College</a>
            </div>

            <div class="mt-4 text-center">
                <a href="../Admin/" class="inline-flex items-center justify-center gap-2 text-[10px] font-bold uppercase tracking-widest text-[var(--accent-dim)] hover:text-[var(--text)] transition-colors px-4 py-3 border border-[var(--border-soft)] hover:border-[var(--border-accent)] rounded-lg w-full bg-[var(--bg-alt)]">
                    <i data-lucide="shield" class="w-3.5 h-3.5"></i> Admin Portal
                </a>
            </div>

        </div>
    </div>
</main>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>

<?php include_once 'components/footer.php'; ?>