<?php
// C:\xampp\htdocs\Project2\frontend\college-register.php
session_start();

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

// Fetch active universities for the parent university dropdown
$universities = $pdo->query("SELECT university_id, name FROM universities WHERE status = 'active' ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

$error_message = '';
$success_message = '';
$old_input = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_input = $_POST;
    $university_id = trim($_POST['university_id'] ?? '');
    $name          = trim($_POST['name'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $pass          = $_POST['password'] ?? '';
    $phone         = trim($_POST['phone'] ?? '');
    $address       = trim($_POST['address'] ?? '');

    // Validation checks
    if (empty($university_id) || empty($name) || empty($email) || empty($pass)) {
        $error_message = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } else {
        // Check if college email already exists in database
        $checkStmt = $pdo->prepare("SELECT college_id FROM colleges WHERE email = :email LIMIT 1");
        $checkStmt->execute(['email' => $email]);
        
        if ($checkStmt->fetch()) {
            $error_message = "This college email address is already registered.";
        } else {
            // Generate clean slug from college name
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
            
            // Handle optional logo upload
            $logo_filename = null;
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                    $allowed_types = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, $_FILES['logo']['tmp_name']);
                    finfo_close($finfo);

                    if (isset($allowed_types[$mime]) && $_FILES['logo']['size'] <= 2 * 1024 * 1024) {
                        $upload_dir = __DIR__ . '/assets/images/colleges/';
                        if (!is_dir($upload_dir)) {
                            mkdir($upload_dir, 0755, true);
                        }
                        $logo_filename = 'c_' . bin2hex(random_bytes(8)) . '.' . $allowed_types[$mime];
                        move_uploaded_file($_FILES['logo']['tmp_name'], $upload_dir . $logo_filename);
                    } else {
                        $error_message = "Logo must be a valid JPG/PNG image under 2MB.";
                    }
                } else {
                    $error_message = "Logo upload failed. Please try again.";
                }
            }

            if (empty($error_message)) {
                // Securely hash password
                $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("
                    INSERT INTO colleges (university_id, name, slug, email, password, phone, address, logo, status, created_at, updated_at) 
                    VALUES (:university_id, :name, :slug, :email, :password, :phone, :address, :logo, 'active', NOW(), NOW())
                ");
                
                $stmt->execute([
                    'university_id' => $university_id,
                    'name'          => $name,
                    'slug'          => $slug,
                    'email'         => $email,
                    'password'      => $hashed_password,
                    'phone'         => $phone,
                    'address'       => $address,
                    'logo'          => $logo_filename
                ]);

                $success_message = "College registered successfully! You can now log into your coordinator portal.";
                $old_input = []; // Clear form fields on success
            }
        }
    }
}

$page_title = 'college-register';
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
    --accent: #4B4F86;         
    --border-soft: rgba(40, 43, 74, 0.14);
  }
  body, html, main { background-color: var(--bg) !important; color: var(--text) !important; }
</style>

<main class="relative z-10 min-h-screen flex items-center justify-center py-20 px-4 pt-32">
    <div class="absolute inset-0 z-[-1] pointer-events-none" 
         style="background-image: linear-gradient(to right, rgba(75,79,134,0.08) 1px, transparent 1px), linear-gradient(to bottom, rgba(75,79,134,0.08) 1px, transparent 1px); background-size: 50px 50px;">
    </div>

    <div class="max-w-[620px] w-full mx-auto relative z-10">
        <div class="bg-[var(--surface)] border border-[var(--border-soft)] rounded-3xl p-10 md:p-12 shadow-2xl relative overflow-hidden">
            
            <div class="absolute top-0 right-0 w-32 h-32 bg-[var(--accent)]/5 rounded-bl-full pointer-events-none"></div>

            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-[var(--bg-alt)] border border-[var(--border-soft)] text-[var(--text)] mb-4 shadow-sm">
                    <i data-lucide="building-2" class="w-8 h-8 text-[var(--accent)]"></i>
                </div>
                <h1 class="text-3xl font-bold font-outfit text-[var(--text)] tracking-tight">College Registration Portal</h1>
                <p class="text-sm text-[var(--text-dim)] mt-1.5">Register your institution under its parent university to join Evenza.</p>
            </div>

            <?php if (!empty($error_message)): ?>
                <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3.5 rounded-xl mb-6 text-xs flex items-center gap-2.5 shadow-sm">
                    <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
                    <span><?= htmlspecialchars($error_message); ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($success_message)): ?>
                <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3.5 rounded-xl mb-6 text-xs flex items-center gap-2.5 shadow-sm">
                    <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
                    <span><?= htmlspecialchars($success_message); ?> <a href="college-login.php" class="underline font-bold ml-1">Sign In Now</a></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="college-register.php" enctype="multipart/form-data" class="space-y-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-[var(--accent)] mb-2">Parent University <span class="text-red-500">*</span></label>
                    <select name="university_id" required class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-xl px-4 py-3.5 text-sm shadow-inner focus:outline-none focus:border-[var(--accent)]">
                        <option value="">Select Parent University</option>
                        <?php foreach ($universities as $uni): ?>
                            <option value="<?= $uni['university_id']; ?>" <?= (($old_input['university_id'] ?? '') == $uni['university_id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($uni['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-[var(--accent)] mb-2">College Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. Stanford College of Engineering" value="<?= htmlspecialchars($old_input['name'] ?? ''); ?>"
                           class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-xl px-4 py-3.5 text-sm shadow-inner focus:outline-none focus:border-[var(--accent)]">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-[var(--accent)] mb-2">Official Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required placeholder="engineering@stanford.edu" value="<?= htmlspecialchars($old_input['email'] ?? ''); ?>"
                               class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-xl px-4 py-3.5 text-sm shadow-inner focus:outline-none focus:border-[var(--accent)]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-[var(--accent)] mb-2">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required placeholder="••••••••"
                               class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-xl px-4 py-3.5 text-sm shadow-inner focus:outline-none focus:border-[var(--accent)]">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-[var(--accent)] mb-2">Phone Number</label>
                        <input type="text" name="phone" placeholder="+1 555 111 001" value="<?= htmlspecialchars($old_input['phone'] ?? ''); ?>"
                               class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-xl px-4 py-3.5 text-sm shadow-inner focus:outline-none focus:border-[var(--accent)]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-[var(--accent)] mb-2">College Logo (JPG/PNG)</label>
                        <input type="file" name="logo" accept="image/png,image/jpeg"
                               class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-xl px-3 py-2.5 text-xs shadow-inner focus:outline-none focus:border-[var(--accent)] file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[var(--bg-alt)] file:text-[var(--text)]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-[var(--accent)] mb-2">Campus Address</label>
                    <textarea name="address" rows="2" placeholder="Campus location, City, State..."
                              class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-xl px-4 py-3 text-sm shadow-inner focus:outline-none focus:border-[var(--accent)]"><?= htmlspecialchars($old_input['address'] ?? ''); ?></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-[var(--text)] text-[var(--bg)] font-bold py-4 px-6 rounded-xl hover:bg-[#363A5E] transition-colors shadow-lg uppercase tracking-wider text-xs flex items-center justify-center gap-2">
                        Complete College Registration <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </div>

                <div class="text-center pt-2">
                    <p class="text-xs text-[var(--text-dim)]">Already registered? <a href="college-login.php" class="font-bold text-[var(--text)] underline">Sign in here</a></p>
                </div>
            </form>

        </div>
    </div>
</main>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>

<?php include_once 'components/footer.php'; ?>