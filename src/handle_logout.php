<?php
session_start();

// Ensure user is logged in before displaying page
if(!isset($_COOKIE['logged-in'])) {
    // Redirect back to login page
    header('Location: index.php');
    exit;
}

require_once 'handle_common.php';
require_once 'logger.php';
require_once 'dao.php';

$dao = new Dao();
$logger = new KLogger("log.txt", KLogger::DEBUG);

// Expire the logged-in cookie
$username = $_SESSION['username'];
setcookie("logged-in", $username, time() - (86400)); // Expired a day ago

// Remove session data
unset($_SESSION);
session_destroy();

// Redirect back to login page
header('Location: index.php');
exit;
    
?>