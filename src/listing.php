<?php
session_start();

// Ensure user is logged in before displaying page
if(!isset($_COOKIE['logged-in'])) {
    // Redirect back to login page
    header('Location: index.php');
    exit;
}

$currentTab = 'search';

require_once 'header.php';
require_once 'nav.php';
require_once 'logger.php';
require_once 'dao.php';

$logger = new KLogger("log.txt", KLogger::DEBUG);
$dao = new Dao();

$searchResult = $_SESSION['search-result']; // A single result from 1st API call's results
$listing = $_SESSION['listing']; // Result from 2nd API call

?>

<script type="text/javascript">

    var photoCount = <?php echo count($listing['photos']) ?>;
    var index = 0;
    
    function nextImage() {
        if(index != photoCount - 1) {
             // Hide the current image
            document.getElementById(index).style.display = "none";
            
            // Show the next image
            index++;
            document.getElementById(index).style.display = "inline";
        }
    }
    
    function prevImage() {
        if(index != 0) {
             // Hide the current image
            document.getElementById(index).style.display = "none";
            
            // Show the previous image
            index--;
            document.getElementById(index).style.display = "inline";
        }
    }
    
</script>

<div class="page-content">
    <div class="text-large-white">
        <?php echo $listing['address']['line']." ".$searchResult['location']['address']['city'].", ".$searchResult['location']['address']['state_code']." ".$listing['address']['postal_code']?>
    </div>
    <div class="text-medium-white"><?php echo $searchResult['description']['name'] ?></div>
    <div id="picture-pane" class="listing-pane">
        <div>
            <button type="button" class="image-button" onclick="prevImage()">prev</button>
            
            <?php
            // Create tags for all images
            $counter = 0;
            foreach($listing['photos'] as $photo) {
                // Show only first image
                if($counter == 0) {
                    echo "<img id=\"".$counter."\"src=\"".$photo['href']."\" class=\"listing-image\">";
                }
                else {
                    echo "<img id=\"".$counter."\"src=\"".$photo['href']."\" class=\"listing-image hide\">";
                }
                $counter++;
            }
            
            ?>
            
            <button type="button" class="image-button" onclick="nextImage()">next</button>
        </div>
    </div>
    <div id="info-pane" class="listing-pane">
        <div class="text-medium-white"><?php echo "List price: $".$searchResult['list_price_min']." - $".$searchResult['list_price_max'] ?></div>
        <div class="text-medium-white"><?php echo "Size: ".$searchResult['description']['sqft_min']." - ".$searchResult['description']['sqft_max']." sqft" ?></div>
        <div class="text-medium-white"><?php echo "Beds: ".$searchResult['description']['beds_max'] ?></div>
        <div class="text-medium-white"><?php echo "Baths: ".$searchResult['description']['baths_max'] ?></div>
        <div class="text-medium-white"><?php echo "Assigned parking: ".($listing['parking']['assigned_spaces_available'] == 1 ? "yes" : "no");  ?></div>
        <div class="text-medium-white"><?php echo "Year built: ".$listing['year_built'] ?></div>
        <div class="text-medium-white"><?php echo "Neighborhood: ".$listing['neighborhood'] ?></div>
        <div class="text-medium-white"><?php echo "Property type: ".$listing['prop_type'] ?></div>
        <div class="text-medium-white"><?php echo "Cats allowed: ".($listing['permissions']['cats'] == 1 ? "yes" : "no") ?></div>
        <div class="text-medium-white"><?php echo "Dogs allowed: ".($listing['permissions']['dogs'] == 1 ? "yes" : "no") ?></div>
        <div class="text-medium-white">Nearby schools:
        <?php
            $schoolCount = count($listing['school_catchments']);
            $index = 0;
            $nearbySchools = "";
            while($index < $schoolCount) {
                $nearbySchools .= $listing['school_catchments'][$index]['name'];
                if($index + 1 < $schoolCount) $nearbySchools .= ", ";
                $index++;
            }
            $_SESSION['nearby-schools'] = $nearbySchools;
            echo $nearbySchools
        ?>
        </div>
        <div class="text-medium-white">Contact number: <?php echo $listing['community']['contact_number'] ?></div>
        <div class="text-medium-white">Lease terms: <?php echo $listing['lease_terms'] ?></div>
    </div>
    <div id="description-pane" class="listing-pane">
        <div class="text-medium-white">Description:</div>
        <div class="text-medium-white"><?php echo $listing['description'] ?></div>
    </div>
    <div class="listing-pane">
        <?php
        // Check if listing has been saved to the profile
        $isSavedListing = $dao->isSavedListing($searchResult['property_id']);
        $logger->LogDebug("is saved listing=".$isSavedListing);
        $buttonText = "Save listing";
        $value="save";
        if($isSavedListing) {
            $buttonText = "Remove from saved listings";
            $value="delete";
        }
        ?>
        <form id="saved-listing-form" action="handle_listing.php" method="post">
            <button type="submit" name="button" value="<?php echo $value ?>" form="saved-listing-form"><?php echo $buttonText ?></button>
        </form>
    </div>
</div>



<?php
require_once 'footer.php';
?>