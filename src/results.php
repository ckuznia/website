<?php
session_start();

// Ensure user is logged in before displaying page
if(!isset($_COOKIE['logged-in'])) {
    // Redirect back to login page
    header('Location: index.php');
    exit;
}

require_once 'header.php';
$currentTab = 'search';
require_once 'nav.php';
require_once 'logger.php';
$logger = new KLogger("log.txt", KLogger::DEBUG);
?>

<div class="page-content">
    <!-- Show search results -->
    
    <?php
    
    $results = $_SESSION['search-results']; // all search results
    foreach($results as $result) {
        echo "<div class=\"result-container\">";
        echo "<form id=\"result-form".$result['property_id']."\" method=\"post\" action=\"handle_results.php\">";
            echo "<button class=\"result-button\" type=\"submit\">";
                echo "<div class=\"inner-result-container\">";
                    echo "<img class=\"result-image\" src=\"".$result['photos'][0]['href']."\">";
                    echo "<div class=\"result-right-pane\">";
                        echo "<div class=\"result-text large\">".$result['description']['name']."</div>";
                        echo "<div class=\"result-text small\">".$result['address']['line']."</div>";
                        echo "<div class=\"result-text small\">".$result['description']['beds_max']." Bed | ".$result['description']['baths_max']." Bath"."</div>";
                        echo "<div class=\"result-text large\">"."$".$result['list_price_min']." - $".$result['list_price_max']."</div>";
                    echo "</div>";
                echo "</div>";
            echo "</button>";
            // use a hidden form with the property_id value to pass through to the next page
            echo "<input name=\"property-id\" value=\"".$result['property_id']."\" type=\"hidden\">";
        echo "</form>";
        echo "</div>";
    }
    ?>
</div>

<?php
require_once 'footer.php';
?>