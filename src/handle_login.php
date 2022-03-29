<?php
session_start();
require_once 'handle_common.php';
require_once 'dao.php';
require_once 'logger.php';
$logger = new KLogger("log.txt", KLogger::DEBUG);

// Gather input data
$raw_username = $_POST['username'];
$raw_password = $_POST['password'];
$username = get_input($raw_username);
$password = get_password($raw_password);

// Validate fields
$isValid = true;
if($raw_username == "") {
    $_SESSION['error_message'][] = "Username field must not be blank.";
    $isValid = false;
}
if($raw_password == "") {
    $_SESSION['error_message'][] = "Password field must not be blank.";
    $isValid = false;
}

// Authenticate fields
$dao = new Dao();
$result = $dao->verifyAccount($username, $password);

$authenticated = false;
foreach($result as $row) {
    if($row['username'] == $username && $row['password'] == $password) {
        $authenticated = true;
        $logger->LogDebug("User '$username' authenticated");
        break;
    }
}

if(!$authenticated && $raw_username != "" && $raw_password != "") {
    $_SESSION['error_message'][] = "Invalid username or password.";
    $isValid = false;
}

if($isValid) {
    // Set cookie to preserve login
    setcookie("logged-in", $username, time() + (86400)); // Good for 1 day
    
    // Save username
    $_SESSION['username'] = $username;
    
    // Get user data for the session
    $_SESSION['user-info'] = $dao->getUserInfo($username);
    $logger->LogDebug(print_r($_SESSION['user-info'], true));
    
    // Redirect to home page
    header('Location: home.php');
    exit;
} else {
    // Pass values back to login screen
    $_SESSION['username'] = $raw_username;
    $_SESSION['password'] = $raw_password;
    
    // Redirect to login page
    header('Location: index.php');
    exit;
}

?>