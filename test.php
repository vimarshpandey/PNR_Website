<?php

// Replace 'YOUR_API_ENDPOINT' with the actual API endpoint URL
$api_url = 'https://travel.paytm.com/api/trains/v1/status?vertical=train&client=web&is_genuine_pnr_web_request=1&pnr_number=2409094894';

// Fetch data from the API using file_get_contents
$response = file_get_contents($api_url);

// Check if the request was successful
if ($response === false) {
    die('Failed to fetch data from the API');
}

// Decode the JSON response
$data = json_decode($response, true);

// Check if JSON decoding was successful
if ($data === null) {
    die('Error decoding JSON data');
}

// Print the data
echo '<pre>';
print_r($data);
echo '</pre>';

?>
