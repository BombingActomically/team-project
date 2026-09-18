<?php
// C:\xampp\htdocs\Project2\frontend\logout.php
session_start();

// 1. Unset all session variables
$_SESSION = array();

// 2. Destroy the session cookie to fully clear state
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Destroy the session itself
session_destroy();

// 4. Delete the "Remember Me" cookie if you end up using one
if (isset($_COOKIE['eventra_user'])) {
    setcookie('eventra_user', '', time() - 3600, '/'); 
}

// 5. Redirect back to the login page
header("Location: login.php");
exit();
?>