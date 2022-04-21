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

$hasListings = $listings != null && sizeof($listings) > 0;


?>

<div class="page-content">
    <div id="home-name" class="text-large-white"><?php echo $_SESSION['username']."'s saved listings" ?></div>
    <div id="no-search-history-text" class="text-medium-white">
        <?php
        if($hasListings) {
            // Show saved listings
            $index = 0;
            foreach($listings as $listing) {
                $searchDate = $listing['search_date'];
                $searchTime = substr($searchDate, 11);
                $searchDate = substr($searchDate, 0, 10);
                $hour = substr($searchTime, 0, 2);
                if($hour >= 13) {
                    $newHour = ($hour - 12);
                    $searchTime = $newHour.substr($searchTime, 2)." pm";
                }
                else $searchTime .= " am"; 

                
                echo "<div class=\"result-container\">";
                echo "<form id=\"result-form".$listings['property_id']."\" method=\"post\" action=\"handle_home.php\">";
                    echo "<button class=\"result-button\" type=\"submit\">";
                        echo "<div class=\"inner-result-container\">";
                            echo "<img class=\"result-image\" src=\"".$listing['main_photo']."\">";
                            echo "<div class=\"result-right-pane\">";
                                echo "<div class=\"result-text large\">".$listing['name']."</div>";
                                echo "<div class=\"result-text small\">".$listing['address']."</div>";
                                echo "<div class=\"result-text small\">".$listing['beds']." Bed | ".$listing['baths']." Bath"."</div>";
                                echo "<div class=\"result-text large\">".$listing['list_price']."</div>";
                                echo "<div class=\"result-text small\">"."Searched on ".$searchDate." at ".$searchTime."</div>";
                            echo "</div>";
                        echo "</div>";
                    echo "</button>";
                    // use a hidden form with the index value to pass through to the next page
                    echo "<input name=\"index\" value=\"".$index."\" type=\"hidden\">";
                echo "</form>";
                echo "</div>";
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