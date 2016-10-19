<?php
  
if (!empty($_POST)) {
	
	function searchReviews($client_token, $request_id) {

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

	$yelp_id = $_POST['yelp_id'];	
	$token =  'gMOEuLtVhlfxUVNkvZxm0JNpeiJ15GwGcM0oxc7WJYlem4XRQeb8i2fIIIDlhzvjLLF8fRJZ72gP2LWdg15GtBIoZQSqCCnoE0M-1ghZpHFmZ1pyJ1CWDPpYNr7uV3Yx';	

	$reviews_response = searchReviews($token, $yelp_id) ;
	
	echo json_encode($reviews_response);

} else {
	echo 'Error';
}