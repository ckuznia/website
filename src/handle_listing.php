<?php
session_start();

// Ensure user is logged in before displaying page
if(!isset($_COOKIE['logged-in'])) {
    // Redirect back to login page
    header('Location: index.php');
    exit;
}

require_once 'handle_common.php';
require_once 'logger.php';
require_once 'dao.php';

$dao = new Dao();
$logger = new KLogger("log.txt", KLogger::DEBUG);
$logger->LogDebug("handle_listing.php called");

$searchResult = $_SESSION['search-result']; // A single result from 1st API call's results
$listing = $_SESSION['listing']; // Result from 2nd API call

// Check whether saving or deleting listing
if($_POST['button'] == "save") {
    $dao->saveListing(
        get_input($_SESSION['username']),
        $searchResult['property_id'],
        $listing['address']['postal_code'],
        $searchResult['location']['address']['city'],
        $searchResult['location']['address']['state_code'],
        $listing['address']['line'],
        $searchResult['description']['name'],
        "$".$searchResult['list_price_min']." - $".$searchResult['list_price_max'],
        $searchResult['description']['sqft_min']." - ".$searchResult['description']['sqft_max'],
        $searchResult['description']['beds_max'],
        $searchResult['description']['baths_max'],
        ($listing['parking']['assigned_spaces_available'] == 1 ? "yes" : "no"),
        $listing['year_built'],
        $listing['neighborhood'],
        $listing['prop_type'],
        ($listing['permissions']['cats'] == 1 ? "yes" : "no"),
        ($listing['permissions']['dogs'] == 1 ? "yes" : "no"),
        $_SESSION['nearby-schools'],
        $listing['community']['contact_number'],
        $listing['lease_terms'],
        $listing['description'],
        $listing['photos'],
        $searchResult['photos'][0]['href'],
        $searchResult['href']
    );
}
else {
    
    
    /*
    // Remove listing from session storage
    $newResultList;
    $index = 0;
    foreach($_SESSION['saved-listings'] as $result) {
        // Add all the results except for the one that was deleted
        if($result['property_id'] != $searchResult['property_id']) {
            $newResultList[$index] = $result;
            $index++;
        }
    }
    */
    // Update session storage
    //$_SESSION['saved-listings'] = $newResultList;
    
    if($_POST['tab'] == "home") {
        // Redirect back to saved home page (since listing is now deleted from home)
        $dao->deleteListing(get_input($_SESSION['username']), $_SESSION['saved-listing']['property_id']);
        header('Location: home.php');
        exit;
    }
    else {
        $dao->deleteListing(get_input($_SESSION['username']), $searchResult['property_id']);
    }
}

if($_POST['tab'] == "home") {
    // Redirect back to saved listing page
    header('Location: saved_listing.php');
    exit;
}
else if($_POST['tab'] == "search") {
    // Redirect back to listing page
    header('Location: listing.php');
    exit;
}


?>