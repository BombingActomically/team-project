<?php
// C:\xampp\htdocs\Project2\frontend\about.php
$page_title = 'about';

// 1. Database Connection for Dynamic Stats
$host = 'localhost';
$dbname = 'evenza';
$username = 'root'; 
$password = '';     

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Fetch real-time metrics
    $collegesCount = $pdo->query("SELECT COUNT(*) FROM colleges WHERE status = 'active'")->fetchColumn();
    $registrationsCount = $pdo->query("SELECT COUNT(*) FROM registrations WHERE status = 'approved'")->fetchColumn();
} catch (PDOException $e) {
    $collegesCount = 50;
    $registrationsCount = 10000;
}

// 2. Load Head metadata and config
include_once 'components/header.php';

// 3. Load Navbar
include_once 'components/navbar.php';
?>

<!-- ============ EVENTURA DESIGN SYSTEM — Midnight Indigo / Vanilla Cream ============ -->
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

  body, html, main {
    background-color: var(--bg) !important;
    color: var(--text) !important;
  }
  header, nav, .navbar, .nav-container {
    background-color: var(--bg) !important;
    border-bottom: 1px solid var(--border-soft) !important;
  }
  .navbar-brand, .logo { color: var(--text) !important; }
  .navbar-brand span { color: var(--accent) !important; }
  .nav-link { color: var(--accent-dim) !important; }
  .nav-link:hover, .nav-link.active { color: var(--text) !important; }

  .btn-login {
    border-color: var(--accent) !important;
    color: var(--accent) !important;
    background: transparent !important;
  }
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

  .eb-btn-fill { background: var(--text); color: var(--bg); transition: background .2s ease; font-size: 0.75rem; font-weight: 700; border-radius: .75rem; padding: .65rem 1.25rem; display: inline-flex; align-items: center; justify-content: center; text-align: center; }
  .eb-btn-fill:hover { background: #363A5E; color: var(--bg); }
</style>

<!-- Main Layout Structure with Grid Background -->
<main class="relative z-10 overflow-hidden min-h-screen">
    
    <!-- SUBTLE GRID BACKGROUND -->
    <div class="absolute inset-0 z-[-1] pointer-events-none" 
         style="background-image: linear-gradient(to right, rgba(75,79,134,0.14) 1px, transparent 1px), linear-gradient(to bottom, rgba(75,79,134,0.14) 1px, transparent 1px); background-size: 50px 50px;">
    </div>

    <!-- About Hero Section -->
    <section class="relative pt-32 pb-16 overflow-hidden" style="background-color: var(--bg-alt); border-bottom: 1px solid var(--border-soft);">
        <div class="absolute top-[20%] right-[10%] w-72 h-72 rounded-full blur-[90px] pointer-events-none" style="background: rgba(75,79,134,0.08);"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
            <span class="eb-eyebrow">
                The Platform
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold font-outfit tracking-tight leading-none" style="color: var(--text);">
                What is EVENTURA?
            </h1>
            <p class="text-sm sm:text-base max-w-2xl mx-auto font-normal leading-relaxed" style="color: var(--text-dim);">
                EVENTURA is a multi-tenant SaaS platform built to unify campus life. We connect university event organizers with a nationwide network of students, streamlining registrations, financial routing, and live roster management into one highly secure ecosystem.
            </p>
        </div>
    </section>

    <!-- Values and Core Assets Grid -->
    <div class="relative z-10 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-20">
            
            <!-- Three Column Pillars Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Pillar 1 -->
                <div class="eb-card p-8 space-y-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: var(--bg-alt); border: 1px solid var(--border-soft); color: var(--accent);">
                        <i data-lucide="shield-check" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-bold font-outfit" style="color: var(--text);">Isolated Architecture</h3>
                    <p class="text-xs leading-relaxed" style="color: var(--text-dim);">
                        Built on a strict multi-tenant database model. University coordinators have full administrative control and absolute data privacy over their own local events and financial ledgers.
                    </p>
                </div>
                
                <!-- Pillar 2 -->
                <div class="eb-card p-8 space-y-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: var(--bg-alt); border: 1px solid var(--border-soft); color: var(--accent);">
                        <i data-lucide="zap" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-bold font-outfit" style="color: var(--text);">Frictionless Transactions</h3>
                    <p class="text-xs leading-relaxed" style="color: var(--text-dim);">
                        Cross-campus participation made simple. Students can browse global events, securely process payments, and receive instant digital tickets while revenue scales dynamically for the host college.
                    </p>
                </div>
                
                <!-- Pillar 3 -->
                <div class="eb-card p-8 space-y-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: var(--bg-alt); border: 1px solid var(--border-soft); color: var(--accent);">
                        <i data-lucide="network" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-bold font-outfit" style="color: var(--text);">Global Discovery</h3>
                    <p class="text-xs leading-relaxed" style="color: var(--text-dim);">
                        A central directory connecting engineers, designers, and athletes from diverse universities. EVENTURA bridges the gap between campus talent and national competition.
                    </p>
                </div>
            </div>
            
            <!-- Large Text/Banner Mission Block -->
            <div class="eb-card p-6 sm:p-10 relative overflow-hidden shadow-lg">
                <div class="absolute -right-32 -bottom-32 w-80 h-80 rounded-full blur-[90px] pointer-events-none" style="background: rgba(75,79,134,0.06);"></div>
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
                    <div class="lg:col-span-7 space-y-4">
                        <span class="text-xs uppercase font-bold tracking-widest block" style="color: var(--accent);">The Engineering</span>
                        <h2 class="text-2xl sm:text-4xl font-bold font-outfit" style="color: var(--text);">Built for Scale. Designed for Students.</h2>
                        <p class="text-xs sm:text-sm leading-relaxed" style="color: var(--text-dim);">
                            We believe that participating in technical symposia, hackathons, and sports fests shapes career trajectories just as much as classroom theory. However, the administrative burden of cross-college registration historically stifles participation. By automating data flow, payment routing, and roster verification, EVENTURA lets students focus on the competition, and colleges focus on the execution.
                        </p>
                        <div class="pt-4 flex items-center gap-6 text-center lg:text-left">
                            <div>
                                <div class="text-2xl font-bold font-outfit" style="color: var(--text);"><?php echo number_format($collegesCount); ?>+</div>
                                <div class="text-[9px] uppercase tracking-wider font-semibold mt-0.5" style="color: var(--text-dim);">Colleges Partnered</div>
                            </div>
                            <div class="h-8" style="border-left: 1px solid var(--border-soft);"></div>
                            <div>
                                <div class="text-2xl font-bold font-outfit" style="color: var(--text);"><?php echo number_format($registrationsCount); ?>+</div>
                                <div class="text-[9px] uppercase tracking-wider font-semibold mt-0.5" style="color: var(--text-dim);">Active Registrations</div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-5 relative flex items-center justify-center">
                        <div class="p-6 rounded-2xl text-center w-full max-w-sm" style="background-color: var(--bg); border: 1px solid var(--border-soft);">
                            <i data-lucide="sparkles" class="w-12 h-12 mx-auto mb-4 animate-pulse" style="color: var(--accent);"></i>
                            <h4 class="text-base font-bold font-outfit" style="color: var(--text);">Deploy on Your Campus</h4>
                            <p class="text-[11px] mt-1 mb-4 leading-relaxed" style="color: var(--text-dim);">Want to migrate your college's flagship fest management to the EVENTURA ecosystem?</p>
                            <a href="contact.php" class="eb-btn-fill w-full">
                                Contact Administration
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</main>

<!-- Load Application scripts -->
<script src="./assets/js/main.js?v=<?php echo time(); ?>"></script>

<?php
// 4. Load Footer
include_once 'components/footer.php';
?>