<?php
session_start();

// Ensure user is logged in before displaying page
if(!isset($_COOKIE['logged-in'])) {
    // Redirect back to login page
    header('Location: index.php');
    exit;
}

require_once 'header.php';
$currentTab = 'hometab';
require_once 'nav.php';
require_once 'logger.php';
require_once 'dao.php';

$logger = new KLogger("log.txt", KLogger::DEBUG);
$dao = new Dao();

$userInfo = $dao->getUserInfo($_SESSION['username']);

$listings = $dao->getSavedListings($_SESSION['username']);
$_SESSION['saved-listings'] = $listings;
//$logger->LogDebug(print_r($listings, true));

$hasListings = $listings != null && sizeof($listings) > 0;


?>

<div class="page-content">
    <div id="home-name" class="text-large-white"><?php echo $_SESSION['username'] ?></div>
    <div id="no-search-history-text" class="text-medium-white">
        <?php
        if($hasListings) {
            // Show saved listings
            $index = 0;
            foreach($listings as $listing) {
                echo "<form id=\"result-form".$listings['property_id']."\" method=\"post\" action=\"handle_home.php\">";
                    echo "<button class=\"result-button\" type=\"submit\">";
                        echo "<div class=\"result-container\">";
                            echo "<img class=\"result-image\" src=\"".$listing['main_photo']."\">";
                            echo "<div class=\"result-right-pane\">".$listing['name']."\n</div>";
                        echo "</div>";
                    echo "</button>";
                    // use a hidden form with the index value to pass through to the next page
                    echo "<input name=\"index\" value=\"".$index."\" type=\"hidden\">";
                echo "</form>";
                $index++;
            }
        }
        else {
            echo "No searches have been saved.";
        }
        
        ?>
            
    </div>
</div>



<?php require_once 'footer.php'; ?>