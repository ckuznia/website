<?php
session_start();
require_once 'handle_common.php';
require_once 'dao.php';
require_once 'logger.php';
$logger = new KLogger("log.txt", KLogger::DEBUG);

// Gather input data
$raw_firstname = $_POST['first-name'];
$raw_lastname = $_POST['last-name'];
$raw_username = $_POST['username'];
$raw_password = $_POST['password'];
$raw_password2 = $_POST['password2'];

// Clean input data
$firstname = get_input($raw_firstname);
$lastname = get_input($raw_lastname);
$username = get_input($raw_username);
$password = get_password($raw_password);
$password2 = get_password($raw_password2);

// ============ VALIDATE FIELDS ============
$isValid = true;

// Ensure fields are not blank
if($raw_firstname == "") {
    $_SESSION['error_message'][] = "First name must not be blank.";
    $isValid = false;
}
if($raw_lastname == "") {
    $_SESSION['error_message'][] = "Last name must not be blank.";
    $isValid = false;
}
if($raw_username == "") {
    $_SESSION['error_message'][] = "Username must not be blank.";
    $isValid = false;
}
if($raw_password == "") {
    $_SESSION['error_message'][] = "Password must not be blank.";
    $isValid = false;
}

// Ensure both passwords match
if($raw_password != $raw_password2) {
    $_SESSION['error_message'][] = "Both password fields must match.";
    $isValid = false;
}

// Ensure password is at least 10 characters long
if(strlen(trim($raw_password)) < 10) {
    $_SESSION['error_message'][] = "Password must be at least 10 characters long.";
    $isValid = false;
}

// Ensure username is not already taken
$dao = new Dao();
if($dao->getUserInfo($username) != null) {
    $logger->LogDebug(print_r($dao->getUserInfo($username), true));
    $_SESSION['error_message'][] = "Username already taken.";
    $isValid = false;
}

$userAdded = false;
if($isValid) {
    // Data is valid, proceed to create the account in the database
    
    $userAdded = $dao->addUser($firstname, $lastname, $username, $password, $email);
    
    // Ensure user was successfully added
    if($userAdded) $logger->LogDebug("user '$username' created.");
    else $logger->LogDebug("user '$username' could not be created.");
    
}
if($userAdded) {
    // Redirect to login page
    header('Location: index.php');
    exit;
}
else {
    // Pass values back to create account screen
    $_SESSION['first-name'] = $raw_firstname;
    $_SESSION['last-name'] = $raw_lastname;
    $_SESSION['email'] = $raw_email;
    $_SESSION['username'] = $raw_username;
    $_SESSION['password'] = $raw_password;
    // Second password is not passed back to prevent auto-filling
    
    // Redirect to create account page
    header('Location: create_account.php');
    exit;
}

?>