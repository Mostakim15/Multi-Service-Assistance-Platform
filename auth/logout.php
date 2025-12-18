<?php
// Logout and redirect to home page
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
// Unset all session variables
$_SESSION = [];
// If there's a session cookie, remove it
if (ini_get("session.use_cookies")) {
	$params = session_get_cookie_params();
	setcookie(session_name(), '', time() - 42000,
		$params['path'], $params['domain'], $params['secure'], $params['httponly']
	);
}
// Destroy the session
session_destroy();

// Redirect to site home (not login page)
header('Location: /msap/index.php');
exit;
?>
