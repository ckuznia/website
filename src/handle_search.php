<?php
session_start();

// Ensure user is logged in
if(!isset($_COOKIE['logged-in'])) {
    // Redirect back to login page
    header('Location: index.php');
    exit;
}

require_once 'handle_common.php';
require_once 'logger.php';
require_once 'config/api-config.php';
$logger = new KLogger("log.txt", KLogger::DEBUG);

// Gather input data
$state = get_input($_POST['state']);
$city = get_input($_POST['city']);
$zipCode = get_input($_POST['zip-code']);
$resultCount = get_input($_POST['result-count']);
$sort = $_POST['sort'];
$minPrice = get_input($_POST['min-price']);
$maxPrice = get_input($_POST['max-price']);
$minBeds = get_input($_POST['min-beds']);
$maxBeds = get_input($_POST['max-beds']);
$minBaths = get_input($_POST['min-baths']);
$maxBaths = get_input($_POST['max-baths']);
$type = $_POST['type'];
$searchRadius = get_input($_POST['search-radius']);
$minSize = get_input($_POST['min-size']);
$maxSize = get_input($_POST['max-size']);
$centralAir = $_POST['central-air'];
$dishwasher = $_POST['dishwasher'];
$washerDryer = $_POST['washer-dryer'];
$furnished = $_POST['furnished'];
$garage = $_POST['garage'];
$pool = $_POST['pool'];
$laundry = $_POST['laundry'];
$outdoor = $_POST['outdoor'];
$gym = $_POST['gym'];
$cats = $_POST['cats'];
$dogs = $_POST['dogs'];

// ============ VALIDATE FIELDS ============
$isValid = true;

// Ensure fields state and city fields are not blank
if($state == "") {
    $_SESSION['error_message'][] = "State field is required.";
    $isValid = false;
}
if($city == "") {
    $_SESSION['error_message'][] = "City field is required.";
    $isValid = false;
}
// Ensure optional fields have valid values
if($zipCode != "" && (!is_numeric($zipCode) || strlen($zipCode) != 6 || $zipCode < 0)) {
    $_SESSION['error_message'][] = "Zip-code must be a 6-digit number.";
    $isValid = false;
}
if($resultCount != "" && (!is_numeric($resultCount) || $resultCount <= 0)) {
    $_SESSION['error_message'][] = "Max number of results must be a number greater than 0.";
    $isValid = false;
}
if($minPrice != "" && (!is_numeric($minPrice) || $minPrice < 0)) {
    $_SESSION['error_message'][] = "Minimum price must be a number greater than 0.";
    $isValid = false;
}
if($maxPrice != "" && (!is_numeric($maxPrice) || $maxPrice < 0)) {
    $_SESSION['error_message'][] = "Maximum price must be a number greater than 0.";
    $isValid = false;
}
if($minBeds != "" && (!is_numeric($minBeds) || $minBeds < 1)) {
    $_SESSION['error_message'][] = "Minimum beds must be a number greater than 0.";
    $isValid = false;
}
if($maxBeds != "" && (!is_numeric($maxBeds) || $maxBeds < 1)) {
    $_SESSION['error_message'][] = "Maximum beds must be a number greater than 0.";
    $isValid = false;
}
if($minBaths != "" && (!is_numeric($minBaths) || $minBaths < 1)) {
    $_SESSION['error_message'][] = "Minimum baths must be a number greater than 0.";
    $isValid = false;
}
if($maxBaths != "" && (!is_numeric($maxBaths) || $maxBaths < 1)) {
    $_SESSION['error_message'][] = "Maximum baths must be a number greater than 0.";
    $isValid = false;
}
if($searchRadius != "" && (!is_numeric($searchRadius) || $searchRadius < 1)) {
    $_SESSION['error_message'][] = "Search radius must be a number greater than 0.";
    $isValid = false;
}
if($minSize != "" && (!is_numeric($minSize) || $minSize < 1)) {
    $_SESSION['error_message'][] = "Minimum size must be a number greater than 0.";
    $isValid = false;
}
if($maxSize != "" && (!is_numeric($maxSize) || $maxSize < 1)) {
    $_SESSION['error_message'][] = "Maximum size must be a number greater than 0.";
    $isValid = false;
}

// Check state value with REGEX
if($state != "" && !preg_match("/[A-Za-z][A-Za-z]/", $state)) {
    $_SESSION['error_message'][] = "State is invalid. Use two-letter state code.";
    $isValid = false;
}

// Preserve search fields input data
$_SESSION['post'] = $_POST;

if($isValid) {
    // Data is valid, proceed to perform api call...
    
    // ============ UPDATE VALUES TO MATCH EXPECTED API VALUES ============
    // Create checked features list
    $features = [];
    if($centralAir == "on") $features[] = "central_air";
    if($dishwasher == "on") $features[] = "dishwasher";
    if($washerDryer == "on") $features[] = "washer_dryer";
    if($furnished == "on") $features[] = "furnished";
    if(sizeof($features) != 0) $_SESSION['features'] = $features;
    // Concatenate selected features
    $featuresString = "&in_unit_features=";
    $count = 0;
    foreach($features as $feature) {
        $featuresString .= $feature;
        $count++;
        if($count != sizeof($features)) $featuresString .= ",";
    }
    
    // Create checked ammenities list
    $ammenities = [];
    if($garage == "on") $ammenities[] = "garage_1_or_more";
    if($pool == "on") $ammenties[] = "swimming_pool";
    if($outdoor == "on") $ammenties[] = "swimming_pool";
    if($laundry == "on") $ammenties[] = "laundry_room";
    if($gym == "on") $ammenties[] = "community_gym";
    if(sizeof($ammenities) != 0) $_SESSION['ammenities'] = $features;
    // Concatenate selected ammenities
    $ammenitiesString = "&community_ammenities=";
    $count = 0;
    foreach($ammenities as $ammentie) {
        $ammenitiesString .= $ammentie;
        $count++;
        if($count != sizeof($ammenities)) $ammenitiesString .= ",";
    }
    
    // url generation for API call
    $base = "https://".$API_HOST."/";
    $endpoint = "v2/for-rent";
    $queryString = "?city=".$city;
    $queryString .= "&state_code=".$state;
    $queryString .= "&location=".$zipcode;
    $queryString .= "&limit=".$resultCount;
    $queryString .= "&sort=".$sort;
    $queryString .= "&price_min=".$minPrice;
    $queryString .= "&price_max=".$maxPrice;
    $queryString .= "&beds_min=".$minBeds;
    $queryString .= "&beds_max=".$maxBeds;
    $queryString .= "&baths_min=".$minBaths;
    $queryString .= "&baths_max=".$maxBaths;
    $queryString .= "&property_type=".$type;
    $queryString .= "&expand_search_radius=".$searchRadius;
    $queryString .= "&home_size_min=".$minSize;
    $queryString .= "&home_size_max=".$maxSize;
    $queryString .= $featuresString;
    $queryString .= $ammenitiesString;
    $queryString .= "&cats_ok=".$cats;
    $queryString .= "&dogs_ok=".$dogs;
    $url = $base.$endpoint.$queryString;
    
    $curl = curl_init();
    
    curl_setopt_array($curl, [
    	CURLOPT_URL => $url,
    	CURLOPT_RETURNTRANSFER => true,
    	CURLOPT_FOLLOWLOCATION => true,
    	CURLOPT_ENCODING => "",
    	CURLOPT_MAXREDIRS => 10,
    	CURLOPT_TIMEOUT => 30,
    	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    	CURLOPT_CUSTOMREQUEST => "GET",
    	CURLOPT_HTTPHEADER => [
    		"X-RapidAPI-Host: ".$API_HOST,
    		"X-RapidAPI-Key: ".$API_KEY,
    		"Content-Type: application/json"
    	],
    ]);
    
    $response = curl_exec($curl);
    $data = json_decode($response, true);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
    	$logger->LogError("cURL Error #:" . $err);
    }
    
    if(sizeof($data['data']['home_search']['results']) > 0) {
        // Pass on result set
        $_SESSION['search-results'] = $data['data']['home_search']['results'];
        
        // Redirect to results page
        header('Location: results.php');
    }
    else {
        // Search returned empty results set
        $_SESSION['error_message'][] = "No results found. Try altering the search criteria.";
        
        // Redirect back to search page
        header('Location: search.php');
    }
    exit;
}
else {
    // Search values were not valid
    
    // Redirect back to search page
    header('Location: search.php');
    exit;
}


?>