<?php

if ( !empty($_POST) ) {
	
	$path = $_SERVER['DOCUMENT_ROOT'];
	
	include_once $path . '/wp-load.php';
	
	function toRad($value) {
	   return $value * pi() / 180;
	}
	
	function sortNumber($a,$b) {
		return $a[1] - $b[1];
	} 
	
	function searchReview($client_token, $request_id) {

		$header = array(); 
		$header[] = 'Authorization: Bearer ' . $client_token;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.yelp.com/v3/businesses/" . $request_id . "/reviews");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);	
		$server_output = curl_exec ($ch);
		curl_close ($ch);  

		$reviews = json_decode($server_output)->reviews;

		return $reviews; 
	}  
	
	global $wpdb;
		
	$current_latitude = $_POST['curr_lat'];
	$current_longitude = $_POST['curr_lon'];
	if ($_POST['filter_radius']) {
		$filter_radius = $_POST['filter_radius'] * 1609.34; // Converting miles to meters
	} else {
		$filter_radius = 0;
	}
	
	$breweries_array = $wpdb->get_results( 
		"
		SELECT id, title, url, phone, address, latitude, longitude, business_type, image, yelp_id
		FROM " . $wpdb->prefix . "bnb_data
		WHERE business_type = 'brewery'
		"
	); 
	
	$brunches_array = $wpdb->get_results( 
		"
		SELECT id, title, url, phone, address, latitude, longitude, business_type, image, yelp_id
		FROM " . $wpdb->prefix . "bnb_data
		WHERE business_type = 'brunch'
		"
	); 

	$R = 6371e3; // m 	
	$breweries_distances = array();
	$brunches_distances = array();
	$html = '';	
	$output_breweries_ids = array();
	$output_brunches_ids = array();
	$output_breweries_distances = array();
	$output_brunches_distances = array();
	
	/* Sort breweries by distances */
		 
	$x = count($breweries_array);
	for($i = 0; $i < $x; $i++) {				
		$lat = $breweries_array[$i]->latitude;
		$lon = $breweries_array[$i]->longitude;	

		/* Using Haversine formulae to calculate the distance between two points */
		
		$x1 = $lat - $current_latitude;
		$dLat = toRad($x1);  
		$x2 = $lon - $current_longitude;
		$dLon = toRad($x2);  
		
		$a = sin($dLat/2) * sin($dLat/2) + 
				cos(toRad($current_latitude)) * cos(toRad($lat)) * 
				sin($dLon/2) * sin($dLon/2);  
		$c = 2 * atan2(sqrt($a), sqrt(1-$a)); 
		$distance = $R * $c; 
		
		if (($filter_radius > 0 && $distance < $filter_radius) || $filter_radius == 0) {
			$breweries_distances[] = array($i+1, $distance);
		}
	}
	usort($breweries_distances,"sortNumber"); 
	for($j = 0; $j < count($breweries_distances); $j++) {
		$sorted_brewery_id = $breweries_distances[$j][0];
		$brewery_distance = $breweries_distances[$j][1];		
		
		$sorted_breweries_ids[] = $sorted_brewery_id;
		$output_breweries_distances[] = $brewery_distance;
	}
	
	/* Sort brunches by distances */ 
	$y = count($brunches_array);
	for($i = 0; $i < $y; $i++) {				
		$lat = $brunches_array[$i]->latitude;
		$lon = $brunches_array[$i]->longitude;	
 
		$x1 = $lat - $current_latitude;
		$dLat = toRad($x1);  
		$x2 = $lon - $current_longitude;
		$dLon = toRad($x2);  
		
		$a = sin($dLat/2) * sin($dLat/2) + 
				cos(toRad($current_latitude)) * cos(toRad($lat)) * 
				sin($dLon/2) * sin($dLon/2);  
		$c = 2 * atan2(sqrt($a), sqrt(1-$a)); 
		$distance = $R * $c; 
		
		if (($filter_radius > 0 && $distance < $filter_radius) || $filter_radius == 0) {
			$brunches_distances[] = array($i+1, $distance);
		}
	}
	usort($brunches_distances,"sortNumber"); 	
	for($j = 0; $j < count($brunches_distances); $j++) {
		$sorted_brunch_id = $brunches_distances[$j][0];
		$brunch_distance = $brunches_distances[$j][1];		
		
		$sorted_brunches_ids[] = $sorted_brunch_id;
		$output_brunches_distances[] = $brunch_distance;	
	}	
	
	$output_json_array = array (
		'sorted_breweries_ids' => $sorted_breweries_ids,
		'breweries_distances' => $output_breweries_distances,
		'breweries' => $breweries_array,
		'sorted_brunches_ids' => $sorted_brunches_ids,
		'brunches_distances' => $output_brunches_distances,
		'brunches' => $brunches_array
	);
	 echo json_encode($output_json_array); 
 }	