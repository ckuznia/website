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
$logger->LogDebug(print_r($userInfo, true));
$firstName = $userInfo[0]['first_name'];
$lastName = $userInfo[0]['last_name'];
$creationDate = $userInfo[0]['account_creation_date'];
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
    <div id="home-name" class="text-large-white"><?php echo $_SESSION['username']."'s Profile Info"?></div>
    <div class='text-large-white'>First name: <?php echo $firstName ?></div>
    <div class='text-large-white'>Last name: <?php echo $lastName ?></div>
    <div class='text-large-white'>Account created: <?php echo $creationDate." at ".$creationTime ?></div>
</div>


<?php require_once 'footer.php'; ?>