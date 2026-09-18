<?php
// C:\xampp\htdocs\Project2\frontend\register.php
session_start();

$page_title = 'register'; 

// 1. Database Connection
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

// 2. Fetch Colleges for Dropdown
$collegesStmt = $pdo->query("SELECT college_id, name FROM colleges WHERE status = 'active' ORDER BY name ASC");
$colleges = $collegesStmt->fetchAll(PDO::FETCH_ASSOC);

// 3. Handle Registration Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Sanitize inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $gender = $_POST['gender'];
    $semester = $_POST['semester'];
    $college_id = (int)$_POST['college_id'];
    $enrollment_no = trim($_POST['enrollment_no']);
    $pass = $_POST['password'];
    $pass_confirm = $_POST['confirm_password'];

    // Basic Validation
    if (empty($name) || empty($email) || empty($pass) || empty($college_id) || empty($enrollment_no)) {
        $error_message = "Please fill in all required fields.";
    } elseif ($pass !== $pass_confirm) {
        $error_message = "Passwords do not match.";
    } else {
        
        // Check if email or enrollment number already exists
        $checkStmt = $pdo->prepare("SELECT student_id FROM students WHERE email = :email OR enrollment_no = :eno LIMIT 1");
        $checkStmt->execute(['email' => $email, 'eno' => $enrollment_no]);
        
        if ($checkStmt->fetch()) {
            $error_message = "An account with this Email or Enrollment Number already exists.";
        } else {
            
            // Handle Profile Photo Upload
            $profile_photo_name = 'default_avatar.png'; // Default fallback

            if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['profile_photo']['tmp_name'];
                $fileName = $_FILES['profile_photo']['name'];
                $fileSize = $_FILES['profile_photo']['size'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                $allowedExtensions = ['jpg', 'jpeg', 'png'];

                if (in_array($fileExtension, $allowedExtensions)) {
                    if ($fileSize <= 2 * 1024 * 1024) { // Max 2MB
                        $newFileName = 'student_' . uniqid() . '.' . $fileExtension;
                        $uploadFileDir = __DIR__ . '/uploads/';
                        
                        if (!is_dir($uploadFileDir)) {
                            mkdir($uploadFileDir, 0755, true);
                        }
                        
                        $dest_path = $uploadFileDir . $newFileName;

                        if (move_uploaded_file($fileTmpPath, $dest_path)) {
                            $profile_photo_name = $newFileName;
                        }
                    }
                }
            }

            // Hash the password securely
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
            
            // Insert into the students table with uploaded profile photo
            $insertStmt = $pdo->prepare("
                INSERT INTO students 
                (college_id, enrollment_no, name, email, password, phone, gender, semester, id_card_image, profile_photo, status, account_status) 
                VALUES 
                (:cid, :eno, :name, :email, :pass, :phone, :gender, :sem, 'pending_id.png', :photo, 'pending', 'active')
            ");
            
            $inserted = $insertStmt->execute([
                'cid' => $college_id,
                'eno' => $enrollment_no,
                'name' => $name,
                'email' => $email,
                'pass' => $hashed_password,
                'phone' => $phone,
                'gender' => $gender,
                'sem' => $semester,
                'photo' => $profile_photo_name
            ]);

            if ($inserted) {
                $_SESSION['register_success'] = "Account created successfully! You can now log in.";
                header("Location: login.php");
                exit();
            } else {
                $error_message = "An error occurred while creating your account. Please try again.";
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

    <div class="max-w-[700px] w-full mx-auto px-6 relative z-10">
        <div class="bg-[var(--surface)] border border-[var(--border-soft)] rounded-2xl p-8 md:px-12 md:py-10 shadow-xl">
            
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-xl bg-[var(--bg-alt)] border border-[var(--border-soft)] text-[var(--text)] mb-4">
                    <i data-lucide="user-plus" class="w-8 h-8"></i>
                </div>
                <h1 class="text-3xl font-bold font-outfit text-[var(--text)] tracking-tight">Create an Account</h1>
                <p class="text-[var(--text-dim)] text-sm mt-2">Join the network to discover and participate in events.</p>
            </div>

            <!-- Display Errors -->
            <?php if (!empty($error_message)): ?>
                <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-8 text-sm flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                    <span><?php echo htmlspecialchars($error_message); ?></span>
                </div>
            <?php endif; ?>

            <!-- Added enctype for file uploads -->
            <form method="POST" action="register.php" enctype="multipart/form-data" class="space-y-6">
                
                <!-- Personal Info Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1.5">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required 
                               class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-lg px-4 py-3 focus:outline-none focus:border-[var(--accent)] transition-colors"
                               placeholder="e.g. Karan Patel" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1.5">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required 
                               class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-lg px-4 py-3 focus:outline-none focus:border-[var(--accent)] transition-colors"
                               placeholder="student@college.edu" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                    </div>
                </div>

                <!-- Academic Info Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1.5">College / University <span class="text-red-500">*</span></label>
                        <select name="college_id" required 
                                class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-lg px-4 py-3 focus:outline-none focus:border-[var(--accent)] transition-colors appearance-none cursor-pointer">
                            <option value="" disabled selected>Select College</option>
                            <?php foreach ($colleges as $c): ?>
                                <option value="<?= $c['college_id'] ?>" <?= (isset($_POST['college_id']) && $_POST['college_id'] == $c['college_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1.5">Enrollment No / ID <span class="text-red-500">*</span></label>
                        <input type="text" name="enrollment_no" required 
                               class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-lg px-4 py-3 focus:outline-none focus:border-[var(--accent)] transition-colors"
                               placeholder="e.g. 23CS012" value="<?= isset($_POST['enrollment_no']) ? htmlspecialchars($_POST['enrollment_no']) : '' ?>">
                    </div>
                </div>

                <!-- Demographic Info Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1.5">Phone Number</label>
                        <input type="text" name="phone" 
                               class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-lg px-4 py-3 focus:outline-none focus:border-[var(--accent)] transition-colors"
                               placeholder="10-digit number" value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1.5">Gender</label>
                        <select name="gender" 
                                class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-lg px-4 py-3 focus:outline-none focus:border-[var(--accent)] transition-colors appearance-none cursor-pointer">
                            <option value="" disabled selected>Select</option>
                            <option value="male" <?= (isset($_POST['gender']) && $_POST['gender'] == 'male') ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?= (isset($_POST['gender']) && $_POST['gender'] == 'female') ? 'selected' : '' ?>>Female</option>
                            <option value="other" <?= (isset($_POST['gender']) && $_POST['gender'] == 'other') ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1.5">Semester</label>
                        <select name="semester" 
                                class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-lg px-4 py-3 focus:outline-none focus:border-[var(--accent)] transition-colors appearance-none cursor-pointer">
                            <option value="" disabled selected>Select</option>
                            <option value="Semester 1">Semester 1</option>
                            <option value="Semester 2">Semester 2</option>
                            <option value="Semester 3">Semester 3</option>
                            <option value="Semester 4">Semester 4</option>
                            <option value="Semester 5">Semester 5</option>
                            <option value="Semester 6">Semester 6</option>
                            <option value="Semester 7">Semester 7</option>
                            <option value="Semester 8">Semester 8</option>
                        </select>
                    </div>
                </div>

                <!-- Profile Photo Upload Field -->
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1.5">Profile Photo (Optional)</label>
                    <input type="file" name="profile_photo" accept="image/png, image/jpeg, image/jpg"
                           class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-lg px-4 py-2.5 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-[var(--bg-alt)] file:text-[var(--text)] hover:file:bg-[var(--border-soft)] transition-colors cursor-pointer text-xs">
                    <p class="text-[10px] text-[var(--text-faint)] mt-1">Accepted formats: JPG, JPEG, PNG (Max 2MB)</p>
                </div>

                <!-- Password Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1.5">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required minlength="6"
                               class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-lg px-4 py-3 focus:outline-none focus:border-[var(--accent)] transition-colors"
                               placeholder="Create password">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-[var(--accent)] mb-1.5">Confirm Password <span class="text-red-500">*</span></label>
                        <input type="password" name="confirm_password" required minlength="6"
                               class="w-full bg-[var(--surface-hover)] border border-[var(--border-soft)] text-[var(--text)] rounded-lg px-4 py-3 focus:outline-none focus:border-[var(--accent)] transition-colors"
                               placeholder="Repeat password">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-[var(--text)] text-[var(--bg)] font-bold py-4 px-4 rounded-lg hover:bg-[#363A5E] transition-colors shadow-md uppercase tracking-wider text-sm flex items-center justify-center gap-2">
                        Create Account <i data-lucide="check" class="w-4 h-4"></i>
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center text-sm text-[var(--text-dim)] border-t border-[var(--border-soft)] pt-6">
                Already have an account? 
                <a href="login.php" class="font-bold text-[var(--accent)] hover:text-[var(--text)] transition-colors">Sign in here</a>
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