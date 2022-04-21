<?php
session_start();

// Ensure user is logged in before displaying page
if(!isset($_COOKIE['logged-in'])) {
    // Redirect back to login page
    header('Location: index.php');
    exit;
}

require_once 'header.php';
$currentTab = 'about';
require_once 'nav.php';
require_once 'logger.php';
require_once 'dao.php';

$logger = new KLogger("log.txt", KLogger::DEBUG);
$dao = new Dao();

?>

<div>About page</div>

<?php require_once 'footer.php'; ?>