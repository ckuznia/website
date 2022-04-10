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

// Delete account
$username = $_SESSION['username'];
$dao->deleteUser($username);

// Remove cookies and session data
unset($_COOKIE);
unset($_SESSION);
session_destroy();

// Redirect back to login page
header('Location: index.php');
exit;
    
?>