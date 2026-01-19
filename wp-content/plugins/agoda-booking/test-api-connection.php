<?php
/**
 * Test API Connection Script
 * 
 * This is a temporary test script to verify API credentials.
 * Remove this file after testing.
 * 
 * Usage: Access via browser: http://yoursite.com/wp-content/plugins/agoda-booking/test-api-connection.php
 * 
 * SECURITY WARNING: This file should be deleted after testing!
 */

// Load WordPress
require_once( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php' );

// Security check - only allow admin users
if ( ! current_user_can( 'manage_options' ) ) {
	die( 'Access denied. Admin privileges required.' );
}

// Test credentials
$site_id = '1425703';
$api_key = '1425703:E05A6D52-1A4E-46B6-BE82-C8FBDCD43140';

// Determine auth value
$auth_value = $api_key;
if ( strpos( $api_key, ':' ) === false ) {
	// API key doesn't contain site_id, combine them
	$auth_value = $site_id . ':' . $api_key;
} elseif ( strpos( $api_key, $site_id . ':' ) === 0 ) {
	// API key already starts with site_id, use it directly
	$auth_value = $api_key;
}

echo '<h1>Agoda API Connection Test</h1>';
echo '<pre>';
echo "Site ID: $site_id\n";
echo "API Key: $api_key\n";
echo "Authorization Header: $auth_value\n\n";

// Test endpoint
$endpoint = 'http://affiliateapi7643.agoda.com/affiliateservice/lt_v1';

// Test request body (Bangkok, 7 days from now)
$check_in = date( 'Y-m-d', strtotime( '+7 days' ) );
$check_out = date( 'Y-m-d', strtotime( '+8 days' ) );

$request_body = array(
	'criteria' => array(
		'cityId' => 9395, // Bangkok
		'checkInDate' => $check_in,
		'checkOutDate' => $check_out,
		'additional' => array(
			'language' => 'en-us',
			'currency' => 'USD',
			'maxResult' => 5,
		),
	),
);

echo "Request Details:\n";
echo "Endpoint: $endpoint\n";
echo "Method: POST\n";
echo "Check-in: $check_in\n";
echo "Check-out: $check_out\n";
echo "City ID: 9395 (Bangkok)\n\n";

// Prepare headers
$headers = array(
	'Accept-Encoding' => 'gzip,deflate',
	'Content-Type'    => 'application/json',
	'Authorization'   => $auth_value,
);

// Prepare request
$args = array(
	'method'  => 'POST',
	'headers' => $headers,
	'body'    => wp_json_encode( $request_body ),
	'timeout' => 30,
);

echo "Sending request...\n\n";

// Send request
$response = wp_remote_post( $endpoint, $args );

// Check for errors
if ( is_wp_error( $response ) ) {
	echo "❌ ERROR: " . $response->get_error_message() . "\n";
	echo "Error Code: " . $response->get_error_code() . "\n";
} else {
	$status_code = wp_remote_retrieve_response_code( $response );
	$response_body = wp_remote_retrieve_body( $response );
	
	echo "Response Status Code: $status_code\n\n";
	
	if ( $status_code === 200 ) {
		echo "✅ SUCCESS! Connection successful!\n\n";
		
		$data = json_decode( $response_body, true );
		if ( $data && isset( $data['results'] ) ) {
			$count = count( $data['results'] );
			echo "Found $count hotel(s)\n\n";
			
			if ( $count > 0 ) {
				echo "First hotel result:\n";
				$first_hotel = $data['results'][0];
				echo "  Hotel ID: " . ( isset( $first_hotel['hotelId'] ) ? $first_hotel['hotelId'] : 'N/A' ) . "\n";
				echo "  Hotel Name: " . ( isset( $first_hotel['hotelName'] ) ? $first_hotel['hotelName'] : 'N/A' ) . "\n";
				echo "  Daily Rate: " . ( isset( $first_hotel['dailyRate'] ) ? $first_hotel['dailyRate'] : 'N/A' ) . "\n";
				echo "  Currency: " . ( isset( $first_hotel['currency'] ) ? $first_hotel['currency'] : 'N/A' ) . "\n";
			}
		} else {
			echo "Response body (first 500 chars):\n";
			echo substr( $response_body, 0, 500 ) . "\n";
		}
	} else {
		echo "❌ ERROR: HTTP Status $status_code\n\n";
		echo "Response body:\n";
		echo $response_body . "\n";
		
		// Try to parse error
		$error_data = json_decode( $response_body, true );
		if ( $error_data && isset( $error_data['message'] ) ) {
			echo "\nError Message: " . $error_data['message'] . "\n";
		}
	}
}

echo '</pre>';
echo '<p><strong>⚠️ SECURITY WARNING:</strong> Delete this file after testing!</p>';
