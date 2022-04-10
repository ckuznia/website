<?php
session_start();
require_once 'handle_common.php';
require_once 'dao.php';
require_once 'logger.php';
$logger = new KLogger("log.txt", KLogger::DEBUG);

// Capture data for saved listing that was clicked on
$_SESSION['saved-listing'] = $_SESSION['saved-listings'][$_POST['index']];

// Redirect to saved listing page
header('Location: saved_listing.php');
exit;

?>