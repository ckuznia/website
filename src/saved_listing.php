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
            $('#' + index).css('display', 'none');
            
            // Show the next image
            index++;
            $('#' + index).css('display', 'inline');
        }
    }
    
    function prevImage() {
        if(index != 0) {
            // Hide the current image
            $('#' + index).css('display', 'none');
            
            // Show the previous image
            index--;
            $('#' + index).css('display', 'inline');
        }
    }
    
</script>

<div class="page-content">
    <div class="text-large-white">
        <?php echo $savedListing['address']." ".$savedListing['city'].", ".$savedListing['state']." ".$savedListing['zip_code']?>
    </div>
    <div class="text-medium-large-white"><?php echo $savedListing['name'] ?></div>
    <div class="listing-panel">
        <div class="inner-listing-pane">
            <button type="button" class="image-button" onclick="prevImage()"><</button>
            
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
            
            <button type="button" class="image-button" onclick="nextImage()">></button>
        </div>
        <div id="description-pane" class="inner-listing-pane" style="margin-left: 20px">
            <div class="text-medium-large-white">Description</div>
            <div class="text-medium-white"><?php echo $savedListing['description'] ?></div>
            <form id="gallery-form" action="gallery.php" method="post">
                <button class="submit-button" name="tab" value="hometab" type="submit" form="gallery-form">View All Photos</button>
            </form>
        </div>
    </div>
    
    <div class="listing-panel">
        <div id="details-pane" class="inner-listing-pane">
            <div class="text-medium-large-white">Details</div>
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
            <div class="text-medium-white">Lease terms: <?php echo $savedListing['lease_terms'] ?></div>
        </div>
        <div style="margin-left: 20px" class="inner-listing-pane">
            <div class="text-medium-large-white">Contact: <?php echo $savedListing['contact_number'] ?></div>
            <div style="margin-bottom: 50px"><a class="text-medium-large-white" href="<?php echo $savedListing['link'] ?>">Visit contact site</a></div>

            <?php
            // Check if listing has been saved to the profile
            $isSavedListing = $dao->isSavedListing($savedListing['property_id']);
            $logger->LogDebug("is saved listing=".$isSavedListing);
            $text = "Want to save this listing for later?";
            $buttonText = "Save";
            $buttonClass = "submit-button";
            $value="save";
            if($isSavedListing) {
                $text = "This listing has been saved to your profile, click below to remove it.";
                $buttonText = "Remove";
                $buttonClass = "cancel-button";
                $value="delete";
            }
            ?>
            <div class="text-medium-white"><?php echo $text ?></div>
            <form id="saved-listing-form" action="handle_listing.php" method="post">
                <input name="tab" value="home" type="hidden">
                <button class="<?php echo $buttonClass ?>" type="submit" name="button" value="<?php echo $value ?>" form="saved-listing-form"><?php echo $buttonText ?></button>
            </form>
        </div>
    </div>
</div>



<?php require_once 'footer.php'; ?>