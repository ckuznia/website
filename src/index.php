<?php 
session_start();
require_once 'header.php';
?>

<div id="login-container" class="center-container">
    <div id="welcome-message" class="text-large-white">Welcome!</div>
    <div id="welcome-subtext1" class="text-medium-white">Please login</div>
    <div id="login-panel" class="white-center-panel">
        <div>
            <form id="login-form" action="handle_login.php" method="post">
                <div>Username</div>
                <input id="username" name="username" placeholder="Enter username" class="text-input" type="text" value="<?php echo htmlspecialchars($_SESSION['username'])?>">
                
                <div>Password</div>
                <input id="password" name="password" placeholder="Enter password" class="text-input" type="password" value="<?php echo htmlspecialchars($_SESSION['password'])?>">
                
                <div class="error-message">
                    <?php 
                    
                    // Show input-field error messages if any exist
                    if(isset($_SESSION['error_message'])) {
                        foreach($_SESSION['error_message'] as $message) {
                            echo "<div>$message</div>";
                        }
                    }
                    ?>
                </div>
                
                <div><button id="login-button" class="submit-button" type="submit" form="login-form">Login</button></div>
            </form>
        </div>
    </div>
    <div id="welcome-subtext2" class="text-medium-white">Don't have an account?</div>
    <div id="no-account-panel">
        <div>
        <form id="create-account" action="create_account.php" method="get">
            <div><button class="submit-button" type="submit" form="create-account">Create account</button></div>
        </form>
        </div>
    </div>
</div>

<?php

// Reset error messages if needed
if(isset($_SESSION['error_message'])) {
    unset($_SESSION['error_message']);
}
unset($_SESSION['username']);
unset($_SESSION['password']);

require_once 'footer.php';
?>