<?php

// Used for cleaning up input data and protecting against XSS
function get_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Used for cleaning up password input
// in addition to salting and hashing
function get_password($password) {
    // Cleanup input data
    $password = get_input($password);
    // Salt and hash data
    $password = hash('sha512', $password . "ocean7");
    return $password;
}

?>