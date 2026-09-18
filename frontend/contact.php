<?php
// C:\xampp\htdocs\Project2\frontend\contact.php
session_start();
$page_title = 'contact';

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

$success_message = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = htmlspecialchars($_POST['full_name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $college = htmlspecialchars($_POST['college'] ?? '');
    $subject = htmlspecialchars($_POST['subject'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');

    if (!empty($full_name) && !empty($email) && !empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO inquiries (full_name, email, college, subject, message) VALUES (:name, :email, :college, :subject, :msg)");
        $stmt->execute([
            'name' => $full_name,
            'email' => $email,
            'college' => $college,
            'subject' => $subject,
            'msg' => $message
        ]);
        $success_message = "Your message has been sent! Our team will get back to you shortly.";
    }
}

// 1. Load Head metadata and config
include_once 'components/header.php';

// 2. Load Navbar
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
  header, nav, .navbar, .nav-container { background-color: var(--bg) !important; border-bottom: 1px solid var(--border-soft) !important; }
  .navbar-brand, .logo { color: var(--text) !important; }
  .navbar-brand span { color: var(--accent) !important; }
  .nav-link { color: var(--accent-dim) !important; }
  .nav-link:hover, .nav-link.active { color: var(--text) !important; }

  .btn-login { border-color: var(--accent) !important; color: var(--accent) !important; background: transparent !important; }
  .btn-login:hover { border-color: var(--text) !important; color: var(--text) !important; }
  .btn-register { background-color: var(--text) !important; color: var(--bg) !important; border: none !important; }
  .btn-register:hover { background-color: #363A5E !important; }

  .eb-eyebrow {
    display: inline-flex; align-items: center; gap: .5rem;
    padding: .4rem .9rem; border-radius: 9999px;
    background: rgba(75, 79, 134, 0.12);
    border: 1px solid var(--border-accent);
    color: var(--accent);
    font-size: .7rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
  }
  .eb-eyebrow .dot { width: .4rem; height: .4rem; border-radius: 9999px; background: var(--accent); flex-shrink: 0; }

  .eb-card { background: var(--surface); border: 1px solid var(--border-soft); border-radius: 1.25rem; transition: transform .25s ease, border-color .25s ease, box-shadow .25s ease; }
  .eb-card:hover { border-color: var(--border-accent); transform: translateY(-3px); box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12); }

  .eb-btn-fill { background: var(--text); color: var(--bg); transition: background .2s ease; font-size: 0.75rem; font-weight: 700; border-radius: .85rem; padding: 0.75rem 1.25rem; display: inline-flex; align-items: center; justify-content: center; text-align: center; cursor: pointer; border: none; }
  .eb-btn-fill:hover { background: #363A5E; color: var(--bg); }

  .eb-form-input {
    background-color: var(--bg);
    border: 1px solid var(--border-soft);
    color: var(--text);
    border-radius: 0.85rem;
    padding: 0.65rem 1rem;
    font-size: 0.75rem;
    outline: none;
    transition: border-color 0.2s ease;
    width: 100%;
  }
  .eb-form-input:focus { border-color: var(--accent); }
</style>

<main class="relative z-10 overflow-hidden min-h-screen">
    
    <div class="absolute inset-0 z-[-1] pointer-events-none" 
         style="background-image: linear-gradient(to right, rgba(75,79,134,0.14) 1px, transparent 1px), linear-gradient(to bottom, rgba(75,79,134,0.14) 1px, transparent 1px); background-size: 50px 50px;">
    </div>

    <section class="relative pt-32 pb-10 overflow-hidden" style="background-color: var(--bg-alt); border-bottom: 1px solid var(--border-soft);">
        <div class="absolute top-[20%] left-[10%] w-72 h-72 rounded-full blur-[90px] pointer-events-none" style="background: rgba(75,79,134,0.08);"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
            <span class="eb-eyebrow">
                Inquiries
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold font-outfit tracking-tight leading-none" style="color: var(--text);">
                Get In Touch
            </h1>
            <p class="text-sm sm:text-base max-w-xl mx-auto font-normal leading-relaxed" style="color: var(--text-dim);">
                Have questions about registrations, hosting partnerships, or code requirements? Drop us a message below.
            </p>
        </div>
    </section>

    <div class="relative z-10 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                <div class="lg:col-span-6 eb-card p-6 sm:p-8 space-y-6 shadow-lg">
                    <div>
                        <h2 class="text-xl font-bold font-outfit" style="color: var(--text);">Send a Message</h2>
                        <p class="text-xs mt-1" style="color: var(--text-dim);">Fill out the quick inquiry details below, and our team will get back to you within 24 hours.</p>
                    </div>
                    
                    <?php if ($success_message): ?>
                        <div class="bg-emerald-100 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl text-xs font-bold flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4"></i> <?= $success_message ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] uppercase tracking-wider font-semibold mb-1" style="color: var(--text-dim);">Full Name</label>
                                <input type="text" name="full_name" required class="eb-form-input" placeholder="e.g. Karan Patel">
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase tracking-wider font-semibold mb-1" style="color: var(--text-dim);">Email Address</label>
                                <input type="email" name="email" required class="eb-form-input" placeholder="e.g. karan@student.com">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-[10px] uppercase tracking-wider font-semibold mb-1" style="color: var(--text-dim);">College/Institution</label>
                            <input type="text" name="college" required class="eb-form-input" placeholder="e.g. ABC Institute of Technology">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] uppercase tracking-wider font-semibold mb-1" style="color: var(--text-dim);">Subject</label>
                            <select name="subject" required class="eb-form-input cursor-pointer">
                                <option value="event_registration">Event Registration Query</option>
                                <option value="partnership">College Partnership/Listing</option>
                                <option value="technical_bug">Technical Bug / Issue</option>
                                <option value="other">Other Inquiry</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-[10px] uppercase tracking-wider font-semibold mb-1" style="color: var(--text-dim);">Inquiry Description</label>
                            <textarea name="message" required rows="4" class="eb-form-input resize-none" placeholder="Type your message details here..."></textarea>
                        </div>
                        
                        <button type="submit" class="eb-btn-fill w-full mt-2 shadow-md">
                            Send Message
                        </button>
                    </form>
                </div>
                
                <div class="lg:col-span-6 space-y-6">
                    <div>
                        <h2 class="text-xl font-bold font-outfit" style="color: var(--text);">Frequently Asked Questions</h2>
                        <p class="text-xs mt-1" style="color: var(--text-dim);">Find quick answers to common support questions regarding the Eventra network.</p>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="eb-card overflow-hidden">
                            <button onclick="toggleAccordion('faq-1')" class="w-full px-6 py-4 flex items-center justify-between text-left text-sm font-semibold transition-colors" style="color: var(--text);">
                                <span>How do I register for an inter-college event?</span>
                                <i data-lucide="chevron-down" id="faq-1-icon" class="w-4 h-4 transition-transform duration-300" style="color: var(--accent);"></i>
                            </button>
                            <div id="faq-1-content" class="hidden px-6 pb-5 text-xs leading-relaxed pt-3" style="color: var(--text-dim); border-top: 1px solid var(--border-soft);">
                                To register, navigate to the <strong>Events Portal</strong>, locate your preferred event card, click <strong>Register Now</strong>, fill in your name, student ID, and college email, and submit. You will receive an instant entry pass in your mailbox.
                            </div>
                        </div>
                        
                        <div class="eb-card overflow-hidden">
                            <button onclick="toggleAccordion('faq-2')" class="w-full px-6 py-4 flex items-center justify-between text-left text-sm font-semibold transition-colors" style="color: var(--text);">
                                <span>Is there a registration fee?</span>
                                <i data-lucide="chevron-down" id="faq-2-icon" class="w-4 h-4 transition-transform duration-300" style="color: var(--accent);"></i>
                            </button>
                            <div id="faq-2-content" class="hidden px-6 pb-5 text-xs leading-relaxed pt-3" style="color: var(--text-dim); border-top: 1px solid var(--border-soft);">
                                Most events and workshops listed on EVENTRA are free to register for all validated college students. Some fests may require nominal on-spot entry fees, which will be highlighted in the event details modal.
                            </div>
                        </div>
                        
                        <div class="eb-card overflow-hidden">
                            <button onclick="toggleAccordion('faq-3')" class="w-full px-6 py-4 flex items-center justify-between text-left text-sm font-semibold transition-colors" style="color: var(--text);">
                                <span>How do I register my college on the platform?</span>
                                <i data-lucide="chevron-down" id="faq-3-icon" class="w-4 h-4 transition-transform duration-300" style="color: var(--accent);"></i>
                            </button>
                            <div id="faq-3-content" class="hidden px-6 pb-5 text-xs leading-relaxed pt-3" style="color: var(--text-dim); border-top: 1px solid var(--border-soft);">
                                College authorities, fests organizers, or student club representatives can submit a partnership request by using this contact form. Select <strong>College Partnership/Listing</strong> from the subject dropdown to start.
                            </div>
                        </div>
                        
                        <div class="eb-card overflow-hidden">
                            <button onclick="toggleAccordion('faq-4')" class="w-full px-6 py-4 flex items-center justify-between text-left text-sm font-semibold transition-colors" style="color: var(--text);">
                                <span>Can I participate if my college isn't partnered?</span>
                                <i data-lucide="chevron-down" id="faq-4-icon" class="w-4 h-4 transition-transform duration-300" style="color: var(--accent);"></i>
                            </button>
                            <div id="faq-4-content" class="hidden px-6 pb-5 text-xs leading-relaxed pt-3" style="color: var(--text-dim); border-top: 1px solid var(--border-soft);">
                                Yes! Any verified college student with a valid student ID card or college roll number can participate in the events, fests, and workshops, even if their specific institution is not yet listing on EVENTRA.
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
</main>

<script>
    function toggleAccordion(id) {
        const content = document.getElementById(id + '-content');
        const icon = document.getElementById(id + '-icon');
        
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            icon.classList.add('rotate-180');
        } else {
            content.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
    }
    
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>

<?php include_once 'components/footer.php'; ?>