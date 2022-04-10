<?php
session_start();

// Ensure user is logged in before displaying page
if(!isset($_COOKIE['logged-in'])) {
    // Redirect back to login page
    header('Location: index.php');
    exit;
}

require_once 'handle_common.php';
require_once 'config/api-config.php';
require_once 'logger.php';

$logger = new KLogger("log.txt", KLogger::DEBUG);
$logger->LogDebug("Result selected with property id ".$_POST['property-id']);

// Do another API call to get more detailed information on this listing
$curl = curl_init();
$url = "https://".$API_HOST."/v2/property-detail?property_id=".$_POST['property-id'];
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

// Save API data
$_SESSION['listing'] = $data['data']['property_detail'];

// Save data (from 1st API call) for listing with that property ID
foreach($_SESSION['search-results'] as $result) {
    if(trim($result['property_id']) == trim($_POST['property-id'])) {
        $_SESSION['search-result'] = $result;
        $logger->LogDebug("clicked result with property id ".$result['property_id']." has name ".$result['description']['name']);
        break;
    }
}

// Redirect to listing page
header('Location: listing.php');
exit;

?>