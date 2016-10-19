<?php
	
$path = $_SERVER['DOCUMENT_ROOT'];

include_once $path . '/wp-load.php';
 
global $wpdb; 

$breweries_array = $wpdb->get_results( 
	"
	SELECT id, title, url, phone, address, latitude, longitude, business_type, image
	FROM " . $wpdb->prefix . "bnb_data
	"
);

echo json_encode($breweries_array); 
