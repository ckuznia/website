<?php
session_start();
require_once 'handle_common.php';
require_once 'dao.php';
require_once 'logger.php';
$logger = new KLogger("log.txt", KLogger::DEBUG);

// Capture data for saved listing that was clicked on
$_SESSION['saved-listing'] = $_SESSION['saved-listings'][$_POST['index']];

$logger->LogDebug("Clicked on saved listing with name ".$_SESSION['saved-listing']['name']." and id ".$_SESSION['saved-listing']['property_id']." and index ".$_POST['index']);

// Redirect to saved listing page
header('Location: saved_listing.php');
exit;

?>