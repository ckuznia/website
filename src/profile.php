<?php
session_start();

// Ensure user is logged in before displaying page
if(!isset($_COOKIE['logged-in'])) {
    // Redirect back to login page
    header('Location: index.php');
    exit;
}

require_once 'header.php';
$currentTab = 'profile';
require_once 'nav.php';
require_once 'logger.php';
require_once 'dao.php';

$logger = new KLogger("log.txt", KLogger::DEBUG);
$dao = new Dao();

$userInfo = $dao->getUserInfo($_SESSION['username']);
$firstName = $userInfo['first_name'];
$lastName = $userInfo['last_name'];
$creationDate = $userInfo['account_creation_date'];
$creationTime = substr($creationDate, 11);
$creationDate = substr($creationDate, 0, 10);
$hour = substr($creationTime, 0, 2);
if($hour >= 13) {
    $newHour = ($hour - 12);
    $creationTime = $newHour.substr($creationTime, 2)." pm";
}
else $creationTime .= " am"; 
?>
<div class="page-content">
    <div id="home-name" class="text-large-white"><?php echo $_SESSION['username']."'s profile info"?></div>
    <div class='text-medium-large-white'>First name: <?php echo $firstName ?></div>
    <div class='text-medium-large-white'>Last name: <?php echo $lastName ?></div>
    <div class='text-medium-large-white'>Account created: <?php echo $creationDate." at ".$creationTime ?></div>
    
    <form id="delete-user-form" action="handle_profile.php">
        <div><button id="delete-user-button" class="submit-button" type="submit" form="delete-user-form">DELETE <?php echo $_SESSION['username']."'s account" ?></button></div>
    </form>
</div>


<?php require_once 'footer.php'; ?>