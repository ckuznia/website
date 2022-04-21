<?php 
session_start();
require_once 'header.php';
?>

<div id="create-account-container" class="center-container">
    <div id="create-account-message" class="text-large-white">Create Account</div>
    <div id="create-account-subtext1" class="text-medium-white">Please enter your information</div>
    <div id="create-account-panel" class="white-center-panel">
        <div>
            <form id="create-account-form" action="handle_create_account.php" method="post">
                <div>Name</div>
                <input id="first-name" name="first-name" placeholder="Enter first name" class="text-input" type="text" value="<?php echo htmlspecialchars($_SESSION['first-name'])?>">
                
                <input id="last-name" name="last-name" placeholder="Enter last name" class="text-input" type="text" value="<?php echo htmlspecialchars($_SESSION['last-name'])?>">
                
                <div>Username</div>
                <input id="username" name="username" placeholder="Choose username" class="text-input" type="text" value="<?php echo htmlspecialchars($_SESSION['username'])?>">
                
                <div>Password</div>
                <input id="password" name="password" placeholder="Choose password" class="text-input" type="password" value="<?php echo htmlspecialchars($_SESSION['password'])?>">
                
                <input id="password2" name="password2" placeholder="Re-enter password" class="text-input" type="password">
                
                <div class="error-message">
                    <?php 
                    
                    // Show input-field error messages if any exist
                    if(isset($_SESSION['error_message'])) {
                        foreach($_SESSION['error_message'] as $message) {
                            echo $message;
                        }
                    }
                    ?>
                </div>
                
                <div><button class="submit-button" type="submit" form="create-account-form">Create Account</button></div>
                
                
            </form>
            <form action="index.php">
                <div><button class="cancel-button" type="submit">Cancel</button></div>
            </form>
        </div>
    </div>
</div>

<?php

// Reset error messages if needed
if(isset($_SESSION['error_message'])) {
    unset($_SESSION['error_message']);
}
unset($_SESSION['first-name']);
unset($_SESSION['last-name']);
unset($_SESSION['email']);
unset($_SESSION['username']);
unset($_SESSION['password']);

require_once 'footer.php';
?>