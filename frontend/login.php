<?php
// C:\xampp\htdocs\Project2\frontend\login.php
session_start();

$page_title = 'login'; 

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

// --- Cookie Auto-Login Check ---
if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'student') {
    header("Location: dashboard.php");
    exit();
} elseif (isset($_COOKIE['eventra_user'])) {
    // Check if user is active before auto-logging in via cookie
    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = :id LIMIT 1");
    $stmt->execute(['id' => $_COOKIE['eventra_user']]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
        if (strtolower($student['account_status']) !== 'active') {
            // Clear cookie if banned/inactive
            setcookie('eventra_user', '', time() - 3600, "/");
        } else {
            $_SESSION['user_id'] = $student['student_id'];
            $_SESSION['user_role'] = 'student';
            $_SESSION['user_name'] = $student['name'];
            header("Location: dashboard.php");
            exit();
        }
    }
}

$error_message = '';
$success_message = isset($_SESSION['register_success']) ? $_SESSION['register_success'] : '';
unset($_SESSION['register_success']); // Clear it after reading

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    if (!empty($email) && !empty($pass)) {
        
        // Fetch student regardless of status first to check if they are banned vs invalid email
        $studentStmt = $pdo->prepare("SELECT * FROM students WHERE email = :email LIMIT 1");
        $studentStmt->execute(['email' => $email]);
        $student = $studentStmt->fetch(PDO::FETCH_ASSOC);

        if ($student) {
            // Verify hashed password first
            if (password_verify($pass, $student['password'])) {
                
                // Check if account is active/banned
                if (strtolower($student['account_status']) !== 'active') {
                    $error_message = "Access Denied: This account has been deactivated or banned by the administrator.";
                } else {
                    // Set Session
                    $_SESSION['user_id'] = $student['student_id'];
                    $_SESSION['user_role'] = 'student';
                    $_SESSION['user_name'] = $student['name'];
                    
                    // Set Cookie if "Remember me" is checked (expires in 30 days)
                    if (isset($_POST['remember-me'])) {
                        setcookie('eventra_user', $student['student_id'], time() + (86400 * 30), "/"); 
                    }

                    header("Location: dashboard.php"); 
                    exit();
                }
            } else {
                $error_message = "Invalid password.";
            }
        } else {
            $error_message = "No student account found with that email address.";
        }
        
    } else {
        $error_message = "Please enter both email and password.";
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

    <div class="max-w-[520px] w-full mx-auto px-6 relative z-10">
        <div class="bg-[var(--surface)] border border-[var(--border-soft)] rounded-2xl p-8 md:px-12 md:py-10 shadow-xl">
            
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-xl bg-[var(--bg-alt)] border border-[var(--border-soft)] text-[var(--text)] mb-4">
                    <i data-lucide="user" class="w-8 h-8"></i>
                </div>
                <h1 class="text-3xl font-bold font-outfit text-[var(--text)] tracking-tight">Student Login</h1>
                <p class="text-[var(--text-dim)] text-sm mt-2">Access your events and registrations.</p>
            </div>

            <!-- Display Registration Success -->
            <?php if (!empty($success_message)): ?>
                <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-6 text-sm flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
                    <span><?php echo htmlspecialchars($success_message); ?></span>
                </div>
            <?php endif; ?>

            <!-- Display Errors / Banned Notice -->
            <?php if (!empty($error_message)): ?>
                <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-6 text-sm flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                    <span><?php echo htmlspecialchars($error_message); ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php" class="space-y-5">
                <div>
                    <label for="email" class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1.5">Email Address</label>
                    <input type="email" id="email" name="email" required 
                           class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-lg px-4 py-3.5 focus:outline-none focus:border-[var(--accent)] transition-colors placeholder:text-[var(--text-faint)]"
                           placeholder="student@college.edu">
                </div>

                <div>
                    <label for="password" class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1.5">Password</label>
                    <input type="password" id="password" name="password" required 
                           class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-lg px-4 py-3.5 focus:outline-none focus:border-[var(--accent)] transition-colors placeholder:text-[var(--text-faint)]"
                           placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between mt-2">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 rounded border-[var(--border-soft)] text-[var(--accent)] focus:ring-[var(--accent)] cursor-pointer">
                        <label for="remember-me" class="ml-2 block text-sm text-[var(--text-dim)] cursor-pointer">Remember me</label>
                    </div>
                    <div class="text-sm">
                        <a href="#" class="font-semibold text-[var(--accent)] hover:text-[var(--text)] transition-colors">Forgot password?</a>
                    </div>
                </div>

                <button type="submit" class="w-full bg-[var(--text)] text-[var(--bg)] font-bold py-4 px-4 rounded-lg hover:bg-[#363A5E] transition-colors mt-6 shadow-md uppercase tracking-wider text-sm flex items-center justify-center gap-2">
                    Sign In <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>

            <div class="mt-8 text-center text-sm text-[var(--text-dim)] border-t border-[var(--border-soft)] pt-6">
                Don't have an account? 
                <a href="register.php" class="font-bold text-[var(--accent)] hover:text-[var(--text)] transition-colors">Register Here</a>
            </div>
            
            <div class="mt-6 text-center">
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