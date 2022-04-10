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
        $searchResult['photos'][0]['href']
    );
}
else {
    $dao->deleteListing(get_input($_SESSION['username']), $searchResult['property_id']);
}

// Redirect back to listing page
header('Location: listing.php');
exit;

?>