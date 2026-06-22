<?php
// logout.php - Secure Session Termination
session_start();

// 1. Unset all session variables
$_SESSION = array();

// 2. If a session cookie is used, destroy it completely
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Destroy the session registration on the server side
session_destroy();

// 4. Force bounce back to the login boundary
header("Location: login.php");
exit();
?>
