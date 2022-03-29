<script type="text/javascript">
const base = "https://" + "/";
const endpoint = "v2/for-rent";
const queryString = 
<?php
// ========= START of query string generation =========
echo "\"?city=".$_SESSION['post']['city'];
echo "&state_code=".$_SESSION['post']['state'];
echo "&location=".$_SESSION['post']['zip-code'];
echo "&limit=".$_SESSION['post']['result-count'];
echo "&sort=".$_SESSION['post']['sort'];
echo "&price_min=".$_SESSION['post']['min-price'];
echo "&price_max=".$_SESSION['post']['max-price'];
echo "&beds_min=".$_SESSION['post']['min-beds'];
echo "&beds_max=".$_SESSION['post']['max-beds'];
echo "&baths_min=". $_SESSION['post']['min-baths'];
echo "&baths_max=".$_SESSION['post']['max-baths'];
echo "&property_type=".$_SESSION['post']['type'];
echo "&expand_search_radius=".$_SESSION['post']['search-radius'];
echo "&home_size_min=".$_SESSION['post']['min-size'];
echo "&home_size_max=".$_SESSION['post']['max-size'];

// Concatenate selected features
if(isset($_SESSION['post']['features'])) {
    echo "&in_unit_features=";
    $count = 0;
    foreach($_SESSION['post']['features'] as $feature) {
        echo $feature;
        $count++;
        if($count != sizeof($_SESSION['post']['features'])) echo ",";
    }
}

// Concatenate selected ammenities
if(isset($_SESSION['post']['ammenities'])) {
    echo "&community_ammenities=";
    $count = 0;
    foreach($_SESSION['post']['ammenities'] as $feature) {
        echo $feature;
        $count++;
        if($count != sizeof($_SESSION['post']['ammenities'])) echo ",";
    }
}

echo "&cats_ok=".$_SESSION['post']['cats'];
echo "&dogs_ok=".$_SESSION['post']['dogs'];

echo "\"";
// ========= END of query string generation =========
?>; 
const url = base + endpoint + queryString;
const settings = {
    "async": true,
    "crossDomain": true,
    "url": url,
    "method": "GET",
    "headers": {
    	"X-RapidAPI-Host": "",
    	"X-RapidAPI-Key": ""
    }
};
/*
$.ajax(settings).done(function (response) {
	console.log(response);
});
*/