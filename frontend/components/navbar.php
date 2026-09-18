<!-- C:\xampp\htdocs\Project2\frontend\components\navbar.php -->
<?php
// Determine active link highlighting helper
$activeClass = "font-bold border-b-2";
$inactiveClass = "font-medium border-b-2 border-transparent transition-all duration-200";

function is_active($page, $current) {
    global $activeClass, $inactiveClass;
    return $page === $current ? $activeClass : $inactiveClass;
}

$currPage = isset($page_title) ? $page_title : 'home';
?>
<nav id="main-navbar" class="fixed top-0 left-0 w-full z-50 transition-all duration-300 border-b py-4" style="background-color: var(--bg); border-color: var(--border-soft);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Brand Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="index.php" class="flex items-center space-x-2">
                    <span class="p-2 rounded-lg flex items-center justify-center" style="background-color: var(--bg-alt); border: 1px solid var(--border-soft);">
                        <i data-lucide="sparkles" class="w-5 h-5" style="color: var(--accent);"></i>
                    </span>
                    <span class="text-2xl font-bold tracking-wider font-outfit" style="color: var(--text);">
                        EVENTRA
                    </span>
                </a>
            </div>
            
            <!-- Desktop Navigation Links -->
            <div class="hidden md:flex items-center space-x-8 h-full pt-1">
                <a href="index.php" class="<?php echo is_active('home', $currPage); ?> py-1.5 text-sm" style="<?php echo $currPage === 'home' ? 'color: var(--text); border-color: var(--accent);' : 'color: var(--text-dim);'; ?>">Home</a>
                <a href="events.php" class="<?php echo is_active('events', $currPage); ?> py-1.5 text-sm" style="<?php echo $currPage === 'events' ? 'color: var(--text); border-color: var(--accent);' : 'color: var(--text-dim);'; ?>">Events</a>
                <a href="colleges.php" class="<?php echo is_active('colleges', $currPage); ?> py-1.5 text-sm" style="<?php echo $currPage === 'colleges' ? 'color: var(--text); border-color: var(--accent);' : 'color: var(--text-dim);'; ?>">Colleges</a>
                <a href="about.php" class="<?php echo is_active('about', $currPage); ?> py-1.5 text-sm" style="<?php echo $currPage === 'about' ? 'color: var(--text); border-color: var(--accent);' : 'color: var(--text-dim);'; ?>">About</a>
                <a href="contact.php" class="<?php echo is_active('contact', $currPage); ?> py-1.5 text-sm" style="<?php echo $currPage === 'contact' ? 'color: var(--text); border-color: var(--accent);' : 'color: var(--text-dim);'; ?>">Contact</a>
            </div>
            
            <!-- Actions (Search, Auth Portals) -->
            <div class="hidden md:flex items-center space-x-4">    
                <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'student'): ?>
                    <!-- Student Logged In State -->
                    <a href="dashboard.php" class="px-4 py-2 text-sm font-bold text-[var(--accent)] hover:text-[var(--text)] transition-colors duration-200">
                        Dashboard
                    </a>
                    <a href="logout.php" class="px-4 py-2 text-sm font-bold rounded shadow-md border border-[var(--border-soft)] text-[var(--text)] hover:bg-[var(--surface-hover)] transition-colors duration-200">
                        Sign Out
                    </a>
                <?php elseif(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'college'): ?>
                    <!-- College Coordinator Logged In State -->
                    <a href="college-dashboard.php" class="px-4 py-2 text-sm font-bold text-[var(--accent)] hover:text-[var(--text)] transition-colors duration-200 flex items-center gap-1.5">
                        <i data-lucide="graduation-cap" class="w-4 h-4"></i> College Portal
                    </a>
                    <a href="logout.php" class="px-4 py-2 text-sm font-bold rounded shadow-md border border-[var(--border-soft)] text-[var(--text)] hover:bg-[var(--surface-hover)] transition-colors duration-200">
                        Sign Out
                    </a>
                <?php else: ?>
                    <!-- Logged Out State -->
                    <a href="college-login.php" class="text-xs font-bold uppercase tracking-wider text-[var(--accent)] hover:text-[var(--text)] transition-colors mr-2 flex items-center gap-1" title="Coordinator Login">
                        <i data-lucide="graduation-cap" class="w-3.5 h-3.5"></i> College Panel
                    </a>
                    <a href="login.php" class="px-4 py-2 text-sm font-semibold transition-colors duration-200" style="color: var(--text-dim);" onmouseover="this.style.color='var(--text)'" onmouseout="this.style.color='var(--text-dim)'">
                        Login
                    </a>
                    <a href="register.php" class="px-5 py-2 text-sm font-bold rounded-lg transition-all duration-200 shadow-md inline-block text-center" style="background-color: var(--text); color: var(--bg);">
                        Register
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Mobile Menu Toggle Button -->
            <div class="md:hidden flex items-center space-x-3">
                <button id="mobile-search-btn" class="p-2 transition-colors duration-200 rounded-lg" style="color: var(--text-dim);">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </button>
                <button id="mobile-menu-toggle" class="p-2 transition-colors duration-200 rounded-lg" style="color: var(--text-dim);" aria-label="Toggle Menu">
                    <i data-lucide="menu" id="menu-icon" class="w-6 h-6"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu (Collapsible) -->
    <div id="mobile-menu" class="hidden md:hidden border-b w-full absolute top-[100%] left-0 py-4 px-6 space-y-4" style="background-color: var(--surface); border-color: var(--border-soft);">
        <div class="flex flex-col space-y-3">
            <a href="index.php" class="mobile-nav-link block py-2 text-base font-medium" style="color: <?php echo $currPage === 'home' ? 'var(--text)' : 'var(--text-dim)'; ?>;">Home</a>
            <a href="events.php" class="mobile-nav-link block py-2 text-base font-medium" style="color: <?php echo $currPage === 'events' ? 'var(--text)' : 'var(--text-dim)'; ?>;">Events</a>
            <a href="colleges.php" class="mobile-nav-link block py-2 text-base font-medium" style="color: <?php echo $currPage === 'colleges' ? 'var(--text)' : 'var(--text-dim)'; ?>;">Colleges</a>
            <a href="about.php" class="mobile-nav-link block py-2 text-base font-medium" style="color: <?php echo $currPage === 'about' ? 'var(--text)' : 'var(--text-dim)'; ?>;">About</a>
            <a href="contact.php" class="mobile-nav-link block py-2 text-base font-medium" style="color: <?php echo $currPage === 'contact' ? 'var(--text)' : 'var(--text-dim)'; ?>;">Contact</a>
        </div>
        <hr style="border-color: var(--border-soft);">
        <div class="flex flex-col space-y-3 pt-2">
            <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'student'): ?>
                <a href="dashboard.php" class="w-full text-center py-2.5 text-sm font-bold border rounded-lg transition-colors" style="color: var(--text); border-color: var(--border-soft);">
                    Dashboard
                </a>
                <a href="logout.php" class="w-full text-center py-2.5 text-sm font-bold rounded-lg shadow-md" style="background-color: var(--text); color: var(--bg);">
                    Sign Out
                </a>
            <?php elseif(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'college'): ?>
                <a href="college-dashboard.php" class="w-full text-center py-2.5 text-sm font-bold border rounded-lg transition-colors" style="color: var(--text); border-color: var(--border-soft);">
                    College Portal
                </a>
                <a href="logout.php" class="w-full text-center py-2.5 text-sm font-bold rounded-lg shadow-md" style="background-color: var(--text); color: var(--bg);">
                    Sign Out
                </a>
            <?php else: ?>
                <a href="college-login.php" class="w-full text-center py-2 text-xs font-bold uppercase tracking-wider text-[var(--accent)] border border-[var(--border-soft)] rounded-lg">
                    College Panel Login
                </a>
                <a href="login.php" class="w-full text-center py-2.5 text-sm font-bold border rounded-lg transition-colors" style="color: var(--text); border-color: var(--border-soft);">
                    Login
                </a>
                <a href="register.php" class="w-full text-center py-2.5 text-sm font-bold rounded-lg shadow-md" style="background-color: var(--text); color: var(--bg);">
                    Register
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>