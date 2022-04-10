<?php
session_start();

// Ensure user is logged in before displaying page
if(!isset($_COOKIE['logged-in'])) {
    // Redirect back to login page
    header('Location: index.php');
    exit;
}

$currentTab = 'hometab';

require_once 'header.php';
require_once 'nav.php';
require_once 'logger.php';
require_once 'dao.php';

$logger = new KLogger("log.txt", KLogger::DEBUG);
$dao = new Dao();

$savedListing = $_SESSION['saved-listing'];
$photos = $dao->getPhotos($savedListing['property_id']);
?>

<script type="text/javascript">

    var photoCount = <?php echo count($photos) ?>;
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
        <?php echo $savedListing['address']." ".$savedListing['city'].", ".$savedListing['state']." ".$savedListing['zip_code']?>
    </div>
    <div class="text-medium-white"><?php echo $savedListing['name'] ?></div>
    <div id="picture-pane" class="listing-pane">
        <div>
            <button type="button" class="image-button" onclick="prevImage()">prev</button>
            
            <?php
            // Create tags for all images
            $counter = 0;
            foreach($photos as $photo) {
                // Show only first image
                if($counter == 0) {
                    echo "<img id=\"".$counter."\"src=\"".$photo['house_photo_path']."\" class=\"listing-image\">";
                }
                else {
                    echo "<img id=\"".$counter."\"src=\"".$photo['house_photo_path']."\" class=\"listing-image hide\">";
                }
                $counter++;
            }
            
            ?>
            
            <button type="button" class="image-button" onclick="nextImage()">next</button>
        </div>
    </div>
    <div id="info-pane" class="listing-pane">
        <div class="text-medium-white"><?php echo "List price: ".$savedListing['list_price'] ?></div>
        <div class="text-medium-white"><?php echo "Size: ".$savedListing['size'] ?></div>
        <div class="text-medium-white"><?php echo "Beds: ".$savedListing['beds'] ?></div>
        <div class="text-medium-white"><?php echo "Baths: ".$savedListing['baths'] ?></div>
        <div class="text-medium-white"><?php echo "Assigned parking: ".$savedListing['assigned_parking'] ?></div>
        <div class="text-medium-white"><?php echo "Year built: ".$savedListing['year_built'] ?></div>
        <div class="text-medium-white"><?php echo "Neighborhood: ".$savedListing['neighborhood'] ?></div>
        <div class="text-medium-white"><?php echo "Property type: ".$savedListing['property_type'] ?></div>
        <div class="text-medium-white"><?php echo "Cats allowed: ".$savedListing['cats_allowed'] ?></div>
        <div class="text-medium-white"><?php echo "Dogs allowed: ".$savedListing['dogs_allowed'] ?></div>
        <div class="text-medium-white">Nearby schools: <?php echo $savedListing['nearby_schools'] ?></div>
        <div class="text-medium-white">Contact number: <?php echo $savedListing['contact_number'] ?></div>
        <div class="text-medium-white">Lease terms: <?php echo $savedListing['lease_terms'] ?></div>
    </div>
    <div id="description-pane" class="listing-pane">
        <div class="text-medium-white">Description:</div>
        <div class="text-medium-white"><?php echo $savedListing['description'] ?></div>
    </div>
    <div class="listing-pane">
        <?php
        // Check if listing has been saved to the profile
        $isSavedListing = $dao->isSavedListing($savedListing['property_id']);
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