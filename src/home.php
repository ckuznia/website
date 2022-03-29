<?php
// Ensure user is logged in before displaying page
if(!isset($_COOKIE['logged-in'])) {
    // Redirect back to login page
    header('Location: index.php');
    exit;
}

require_once 'header.php';
$currentTab = 'hometab';
require_once 'nav.php';
?>

<div class="page-content">
    <div id="home-name" class="text-large-white">John Doe</div>
    <div id="no-search-history-text" class="text-medium-white">No searches have been performed.</div>
</div>



<?php require_once 'footer.php'; ?>