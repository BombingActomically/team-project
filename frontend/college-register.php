<?php
// C:\xampp\htdocs\Project2\frontend\college-register.php
session_start();

$page_title = 'college-register'; 

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
$success_message = '';

// Fetch universities list if your database has a universities table
$universities = [];
try {
    $univStmt = $pdo->query("SELECT university_id, name FROM universities WHERE status = 'active' ORDER BY name ASC");
    $universities = $univStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Fallback if universities table structure varies
}

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $university_id = !empty($_POST['university_id']) ? (int)$_POST['university_id'] : null;
    $pass = $_POST['password'] ?? '';
    $pass_confirm = $_POST['confirm_password'] ?? '';

    // Manual Validation Checks
    if (empty($name)) {
        $error_message = "Please enter the institution or college name.";
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid institutional email address.";
    } elseif (empty($address)) {
        $error_message = "Please enter the campus address or city.";
    } elseif (empty($pass)) {
        $error_message = "Please provide a password.";
    } elseif (strlen($pass) < 6) {
        $error_message = "Password must be at least 6 characters long.";
    } elseif ($pass !== $pass_confirm) {
        $error_message = "Passwords do not match.";
    } else {
        // Check if college email already exists
        $checkStmt = $pdo->prepare("SELECT college_id FROM colleges WHERE email = :email LIMIT 1");
        $checkStmt->execute(['email' => $email]);
        
        if ($checkStmt->fetch()) {
            $error_message = "A college account with this email address already exists.";
        } else {
            // Hash password securely
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

            // Insert new college (defaulting status to 'active' or 'pending' depending on your admin workflow)
            $insertStmt = $pdo->prepare("
                INSERT INTO colleges (name, email, password, address, university_id, status) 
                VALUES (:name, :email, :pass, :address, :univ_id, 'active')
            ");
            
            $inserted = $insertStmt->execute([
                'name' => $name,
                'email' => $email,
                'pass' => $hashed_password,
                'address' => $address,
                'univ_id' => $university_id
            ]);

            if ($inserted) {
                $_SESSION['register_success'] = "College account registered successfully! You can now sign in to your coordinator panel.";
                header("Location: college-login.php");
                exit();
            } else {
                $error_message = "An error occurred while registering the college. Please try again.";
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

    <div class="max-w-[600px] w-full mx-auto px-6 relative z-10">
        <div class="bg-[var(--surface)] border border-[var(--border-soft)] rounded-3xl p-8 md:p-12 shadow-2xl">
            
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-[var(--bg-alt)] border border-[var(--border-soft)] text-[var(--text)] mb-4 shadow-sm">
                    <i data-lucide="landmark" class="w-8 h-8 text-[var(--accent)]"></i>
                </div>
                <h1 class="text-3xl font-bold font-outfit text-[var(--text)] tracking-tight">Register Host Institution</h1>
                <p class="text-sm text-[var(--text-dim)] mt-1.5">Create a coordinator account to publish events and manage team rosters.</p>
            </div>

            <?php if (!empty($error_message)): ?>
                <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3.5 rounded-xl mb-6 text-xs flex items-center gap-2.5 shadow-sm">
                    <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
                    <span><?= htmlspecialchars($error_message); ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="college-register.php" class="space-y-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-[var(--accent)] mb-2">College / Institution Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" 
                           class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-xl px-4 py-3.5 focus:outline-none focus:border-[var(--accent)] transition-colors text-sm shadow-inner"
                           placeholder="e.g. Stanford School of Engineering" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-[var(--accent)] mb-2">Institutional Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" 
                               class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-xl px-4 py-3.5 focus:outline-none focus:border-[var(--accent)] transition-colors text-sm shadow-inner"
                               placeholder="coordinator@college.edu" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-[var(--accent)] mb-2">Campus City / Location <span class="text-red-500">*</span></label>
                        <input type="text" name="address" 
                               class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-xl px-4 py-3.5 focus:outline-none focus:border-[var(--accent)] transition-colors text-sm shadow-inner"
                               placeholder="e.g. Surat, Gujarat" value="<?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '' ?>">
                    </div>
                </div>

                <?php if (!empty($universities)): ?>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-[var(--accent)] mb-2">Affiliated University (Optional)</label>
                    <select name="university_id" 
                            class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-xl px-4 py-3.5 focus:outline-none focus:border-[var(--accent)] transition-colors text-sm shadow-inner cursor-pointer">
                        <option value="">-- Select University --</option>
                        <?php foreach ($universities as $u): ?>
                            <option value="<?= $u['university_id'] ?>" <?= (isset($_POST['university_id']) && $_POST['university_id'] == $u['university_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($u['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-[var(--accent)] mb-2">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" 
                               class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-xl px-4 py-3.5 focus:outline-none focus:border-[var(--accent)] transition-colors text-sm shadow-inner"
                               placeholder="••••••••">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-[var(--accent)] mb-2">Confirm Password <span class="text-red-500">*</span></label>
                        <input type="password" name="confirm_password" 
                               class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-xl px-4 py-3.5 focus:outline-none focus:border-[var(--accent)] transition-colors text-sm shadow-inner"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-[var(--text)] text-[var(--bg)] font-bold py-4 px-6 rounded-xl hover:bg-[#363A5E] transition-colors shadow-lg uppercase tracking-wider text-xs flex items-center justify-center gap-2">
                        Register Institution <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center text-sm text-[var(--text-dim)] border-t border-[var(--border-soft)] pt-6">
                Already registered? 
                <a href="college-login.php" class="font-bold text-[var(--accent)] hover:text-[var(--text)] transition-colors">Sign in to College Panel</a>
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