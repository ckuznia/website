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
    <form id="search-form" action="handle_search.php" method="post">
        <div class="text-medium-white">State*</div>
        <input id="state" name="state" class="text-input" type="text" value="<?php echo $_SESSION['post']['state'] ?>">
        
        <div class="text-medium-white">City*</div>
        <input id="city" name="city" class="text-input" type="text" value="<?php echo $_SESSION['post']['city'] ?>">
        
        <div class="text-medium-white">Zip code</div>
        <input id="zip-code" name="zip-code" class="text-input" type="text" value="<?php echo $_SESSION['post']['zip-code'] ?>">
        
        <div class="text-medium-white">Max number of results</div>
        <input id="result-count" name="result-count" class="text-input" type="text" value="<?php echo $_SESSION['post']['result-count'] ?>">
        
       <div class="text-medium-white">Sort by:</div>
        <select name="sort" id="sort">
            <option value="frehsnest" <?php if($_SESSION['post']['sort'] == "frehsnest") echo "selected"?>>Default</option>
            <option value="recently_added_update" <?php if($_SESSION['post']['sort'] == "recently_added_update") echo "selected"?>>Recent</option>
            <option value="lowest_price" <?php if($_SESSION['post']['sort'] == "lowest_price") echo "selected"?>>Lowest price</option>
            <option value="highest_price" <?php if($_SESSION['post']['sort'] == "highest_price") echo "selected"?>>Hightest price</option>
        </select>
        
        <div class="text-medium-white">Min price</div>
        <input id="min-price" name="min-price" class="text-input" type="text" value="<?php echo $_SESSION['post']['min-price'] ?>">
        
        <div class="text-medium-white">Max price</div>
        <input id="max-price" name="max-price" class="text-input" type="text" value="<?php echo $_SESSION['post']['max-price'] ?>">
        
        <div class="text-medium-white">Min # of bedrooms</div>
        <input id="min-beds" name="min-beds" class="text-input" type="text" value="<?php echo $_SESSION['post']['min-beds'] ?>">
        
        <div class="text-medium-white">Max # of bedrooms</div>
        <input id="max-beds" name="max-beds" class="text-input" type="text" value="<?php echo $_SESSION['post']['max-beds'] ?>">
        
        <div class="text-medium-white">Min # of baths</div>
        <input id="min-baths" name="min-baths" class="text-input" type="text" value="<?php echo $_SESSION['post']['min-baths'] ?>">
        
        <div class="text-medium-white">Max # of baths</div>
        <input id="max-baths" name="max-baths" class="text-input" type="text" value="<?php echo $_SESSION['post']['max-baths'] ?>">
        
        <div class="text-medium-white">Property type</div>
        <select name="type" id="type">
            <option value="" <?php if($_SESSION['post']['type'] == "") echo "selected"?>>Any</option>
            <option value="townhome" <?php if($_SESSION['post']['type'] == "townhome") echo "selected"?>>Townhome</option>
            <option value="coop" <?php if($_SESSION['post']['type'] == "coop") echo "selected"?>>Coop</option>
            <option value="single_family" <?php if($_SESSION['post']['type'] == "single_family") echo "selected"?>>Single family</option>
            <option value="apartment" <?php if($_SESSION['post']['type'] == "apartment") echo "selected"?>>Apartment</option>
            <option value="condo" <?php if($_SESSION['post']['type'] == "condo") echo "selected"?>>Condo</option>
            <option value="condop" <?php if($_SESSION['post']['type'] == "condop") echo "selected"?>>Condop</option>
        </select>
        
        <div class="text-medium-white">Expand search radius</div>
        <input id="search-radius" name="search-radius" class="text-input" type="text" value="<?php echo $_SESSION['post']['search-radius'] ?>">
        
        <div class="text-medium-white">Min size</div>
        <input id="min-size" name="min-size" class="text-input" type="text" value="<?php echo $_SESSION['post']['min-size'] ?>">
        
        <div class="text-medium-white">Max size</div>
        <input id="max-size" name="max-size" class="text-input" type="text" value="<?php echo $_SESSION['post']['max-size'] ?>">
        
        <div id="features" class="text-medium-large-white">Features</div>
        <input id="central-air" name="central-air" type="checkbox" <?php if($_SESSION['post']['central-air'] == "on") echo "checked"?>>
        <label for="central-air" class="text-medium-white">Central air</label>
        <input id="dishwasher" name="dishwasher" type="checkbox" <?php if($_SESSION['post']['dishwasher'] == "on") echo "checked"?>>
        <label for="dishwasher" class="text-medium-white">Dishwasher</label>
        <input id="washer-dryer" name="washer-dryer" type="checkbox" <?php if($_SESSION['post']['washer-dryer'] == "on") echo "checked"?>>
        <label for="washer-dryer" class="text-medium-white">Washer/dryer</label>
        <input id="furnished" name="furnished" type="checkbox" <?php if($_SESSION['post']['furnished'] == "on") echo "checked"?>>
        <label for="furnished" class="text-medium-white">Furnished</label>
       
        <div id="ammenities" class="text-medium-large-white">Community ammenities</div>
        <input id="garage" name="garage" type="checkbox" <?php if($_SESSION['post']['garage'] == "on") echo "checked"?>>
        <label for="garage" class="text-medium-white">Garage</label>
        <input id="pool" name="pool" type="checkbox" <?php if($_SESSION['post']['pool'] == "on") echo "checked"?>>
        <label for="pool" class="text-medium-white">Pool</label>
        <input id="laundry" name="laundry" type="checkbox" <?php if($_SESSION['post']['laundry'] == "on") echo "checked"?>>
        <label for="laundry" class="text-medium-white">Community laundry room</label>
        <input id="outdoor" name="outdoor" type="checkbox" <?php if($_SESSION['post']['outdoor'] == "on") echo "checked"?>>
        <label for="outdoor" class="text-medium-white">Community outdoor space</label>
        <input id="gym" name="gym" type="checkbox" <?php if($_SESSION['post']['gym'] == "on") echo "checked"?>>
        <label for="gym" class="text-medium-white">Gym</label>
        
        <div id="pets" class="text-medium-large-white">Pets</div>
        <input id="cats" name="cats" type="checkbox" value="true" <?php if($_SESSION['post']['cats'] == "true") echo "checked"?>>
        <label for="cats" class="text-medium-white">Must allow cats</label>
        <input id="dogs" name="dogs" type="checkbox" value="true" <?php if($_SESSION['post']['dogs'] == "true") echo "checked"?>>
        <label for="dogs" class="text-medium-white">Must allow dogs</label>
        
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
        
        <div><button id="search-submit-button" class="submit-button" type="submit" form="search-form">Search</button></div>
    </form>
</div>

<?php
// Reset error messages if needed
if(isset($_SESSION['error_message'])) {
    unset($_SESSION['error_message']);
}

require_once 'footer.php';
?>