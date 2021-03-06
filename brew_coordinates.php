<?php

if ( !empty($_POST) ) {
	
	$path = $_SERVER['DOCUMENT_ROOT'];
	
	include_once $path . '/step/pahoda/yelp/wp-load.php';
	
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
	
	$breweries_array = $wpdb->get_results( 
		"
		SELECT id, title, url, phone, address, latitude, longitude
		FROM " . $wpdb->prefix . "breweries_data
		"
	); 
 
	$ids_array = array (
"4bs-brewery-cedaredge",
"105-west-brewing-company-castle-rock",
"7-hermits-brewing-company-eagle",
"300-suns-brewery-longmont",
"38-state-brewing-company-littleton",
"12-degree-brewing-louisville",
"",
"alpine-dog-brewing-company-denver",
"amicas-pizza-and-microbrewery-salida",
"animas-brewing-company-durango",
"asher-brewing-company-boulder",
"aspen-brewing-company-aspen",
"avalanche-brewing-silverton",
"avery-brewing-boulder-2",
"backcountry-brewery-and-restaurant-frisco",
"baere-brewing-company-denver",
"barnett-and-son-brewing-co-parker",
"barrels-and-bottles-brewery-golden",
"beer-by-design-brewery-northglenn",
"berthoud-brewing-company-berthoud",
"beryls-beer-co-denver",
"bierwerks-brewery-woodland-park",
"big-beaver-brewing-co-loveland-3",
"black-bottle-brewery-fort-collins",
"black-shirt-brewing-denver",
"black-sky-brewery-denver",
"blue-moon-brewing-company-denver",
"blue-spruce-brewing-company-centennial",
"boggy-draw-brewery-denver",
"bonfire-brewing-eagle",
"bootstrap-brewing-company-niwot",
"boulder-beer-boulder",
"breckenridge-brewery-tasting-room-littleton",
"brewery-rickoli-wheat-ridge",
"brew-pub-and-kitchen-durango",
"",
"bristol-brewing-company-colorado-springs",
"brix-taphouse-and-brewery-greeley",
"broken-compass-brewing-breckenridge",
"broken-plow-brewery-greeley",
"bru-handbuilt-ales-and-eats-boulder",
"buckhorn-brewers-loveland",
"bull-and-bush-denver-3",
"butcherknife-brewing-company-steamboat-springs",
"call-to-arms-brewing-company-denver",
"cannonball-creek-brewing-company-golden",
"",
"carbondale-beer-works-carbondale",
"carver-brewing-co-durango",
"casey-brewing-and-blending-glenwood-springs",
"caution-brewing-co-lakewood",
"cb-and-potts-restaurant-and-taproom-fort-collins",
"",
"cerebral-brewing-denver",
"chain-reaction-brewing-company-denver",
"city-star-brewing-berthoud",
"colorado-boy-pub-and-brewery-ridgway",
"colorado-mountain-brewery-colorado-springs-6",
"colorado-plus-brew-pub-wheat-ridge",
"comrade-brewing-denver",
"coopersmiths-pub-and-brewing-fort-collins",
"",
"copper-club-brewing-company-fruita",
"copper-kettle-brewing-company-denver",
"crabtree-brewing-company-greeley",
"crazy-mountain-brewing-company-edwards",
"creede-brewing-company-denver",
"crooked-stave-denver-2",
"crow-hop-brewing-co-loveland-2",
"crystal-springs-brewing-company-louisville-2",
"dad-and-dudes-breweria-aurora-3",
"dead-hippie-brewing-sheridan",
"declaration-brewing-company-denver-2",
"deep-draft-brewing-company-denver",
"de-steeg-brewing-denver",
"denver-beer-co-denver-3",
"",
"diebolt-brewing-company-denver",
"dillon-dam-brewery-dillon",
"",
"dolores-river-brewery-dolores-2",
"dostal-alley-saloon-central-city",
"",
"durango-brewing-durango",
"echo-brewing-company-frederick",
"",
"eddyline-brewery-and-pub-buena-vista",
"eldo-crested-butte",
"elevation-beer-co-poncha-springs",
"equinox-brewing-fort-collins",
"estes-park-brewery-estes-park",
"factotum-brewhouse-denver",
"fate-brewing-company-boulder-2",
"fermaentra-denver-2",
"fiction-beer-company-denver",
"fieldhouse-brewing-company-colorado-springs-2",
"finkel-and-garf-brewing-company-boulder",
"floodstage-ale-works-brighton",
"",
"former-future-brewing-company-denver",
"fossil-craft-beer-company-colorado-springs",
"",
"front-range-brewing-company-lafayette",
"funkwerks-fort-collins",
"glenwood-canyon-brewing-glenwood-springs-2",
"golden-block-brewery-silverton",
"golden-city-brewery-golden",
"goldspot-brewing-denver",
"gold-camp-brewing-company-colorado-springs",
"gore-range-brewery-edwards",
"grandmas-house-denver-2",
"grand-lakes-yukon-st-tavern-arvada",
"gravity-brewing-louisville",
"great-divide-brewing-company-denver",
"great-storm-brewing-colorado-springs",
"grimm-brothers-brewhouse-loveland",
"grist-brewing-company-highlands-ranch",
"grossen-bart-brewery-longmont",
"",
"high-alpine-brewing-company-gunnison",
"halfpenny-brewing-company-centennial",
"",
"",
"high-hops-brewery-windsor",
"hogshead-brewery-denver",
"holidaily-brewing-golden",
"horse-and-dragon-brewery-fort-collins",
"horsefly-brewing-company-montrose-3",
"the-industrial-revolution-brewing-company-erie",
"iron-bird-brewing-co-colorado-springs-2",
"",
"j-wells-brewery-boulder",
"jaks-brewing-company-peyton",
"joyride-brewing-edgewater-3",
"",
"j-fargos-family-dining-and-micro-brewery-cortez",
"kannah-creek-brewing-company-grand-junction-3",
"kokopelli-beer-company-westminster",
"left-hand-brewing-company-longmont",
"",
"liquid-mechanics-brewing-co-lafayette-2",
"locavore-beer-works-littleton",
"lost-highway-brewing-company-denver",
"loveland-aleworks-loveland",
"lowdown-brewery-denver",
"lumpy-ridge-brewing-company-estes-park",
"mad-jacks-mountain-brewery-bailey",
"mahogany-ridge-brewery-and-grill-steamboat-springs",
"main-street-brewery-cortez",
"mancos-brewing-company-mancos",
"manitou-brewing-company-manitou-springs",
"",
"mockery-brewing-denver",
"",
"moonlight-pizza-and-brew-pub-salida",
"mountain-toad-brewing-golden",
"mountain-sun-pub-and-brewery-boulder",
"mu-brewery-aurora",
"nano-108-brewing-company-colorado-springs",
"new-belgium-brewing-company-fort-collins",
"",
"nighthawk-brewery-broomfield",
"",
"odd13-brewing-lafayette",
"odell-brewing-company-fort-collins",
"odyssey-beerwerks-arvada",
"old-colorado-brewing-wellington",
"",
"ourayle-house-brewery-ouray-2",
"ourayle-house-brewery-ouray-2",
"our-mutual-friend-brewing-company-denver-2",
"oskar-blues-brewery-longmont-2",
"pagosa-brewing-co-and-grill-pagosa-springs",
"palisade-brewing-company-palisade",
"pateros-creek-brewing-co-fort-collins",
"paradox-beer-company-divide-4",
"peaks-n-pines-brewing-company-colorado-springs-3",
"phantom-canyon-colorado-springs",
"pikes-peak-brewing-co-monument",
"",
"platt-park-brewing-denver",
"the-post-brewing-company-lafayette",
"powder-keg-brewing-company-niwot",
"prost-brewery-denver",
"pug-ryans-steakhouse-and-brewery-dillon-2",
"pumphouse-brewery-longmont",
"ratio-beerworks-denver",
"red-leg-brewing-company-colorado-springs",
"renegade-brewing-company-denver",
"revolution-brewing-paonia",
"riff-raff-brewing-company-pagosa-springs",
"river-north-brewery-denver-3",
"roaring-fork-beer-company-carbondale",
"rockslide-brewery-and-restaurant-grand-junction-2",
"rock-bottom-restaurant-and-brewery-loveland",
"rock-bottom-restaurant-and-brewery-loveland",
"rock-bottom-restaurant-and-brewery-loveland",
"rock-bottom-restaurant-and-brewery-loveland",
"rock-bottom-restaurant-and-brewery-loveland",
"rock-bottom-restaurant-and-brewery-loveland",
"rock-bottom-restaurant-and-brewery-loveland",
"rocky-mountain-brewery-colorado-springs",
"rockyard-american-grill-and-brewing-company-castle-rock",
"royal-gorge-brewing-and-restaurant-cañon-city",
"saint-patricks-brewing-company-littleton",
"san-luis-valley-brewing-alamosa",
"sanitas-brewing-co-boulder",
"seedstock-brewery-denver",
"shamrock-brewing-pueblo",
"",
"ska-brewing-durango",
"skeye-brewing-longmont",
"smiling-toad-brewing-company-colorado-springs-2",
"smugglers-brew-pub-telluride-3",
"snowbank-brewing-fort-collins",
"south-park-brewing-fairplay",
"station-26-brewing-denver",
"steamworks-brewing-company-durango",
"storm-peak-brewing-company-steamboat-springs",
"storybook-brewing-colorado-springs",
"strange-craft-beer-company-denver",
"suds-bros-brewery-fruita",
"telluride-brewing-co-telluride",
"the-bakers-brewery-silverthorne",
"the-bob-the-brew-on-broadway-englewood",
"eldo-crested-butte",
"",
"three-barrel-brewing-company-del-norte-2",
"tivoli-brewing-company-denver",
"tommyknocker-brewery-and-pub-idaho-springs-2",
"trinity-brewing-company-colorado-springs",
"",
"trve-brewing-co-denver-2",
"twisted-pine-brewing-company-boulder",
"two22-brew-centennial",
"",
"two-rascals-brewing-company-montrose",
"upslope-brewing-company-flatiron-park-tap-room-boulder-2",
"ursula-brewery-aurora",
"ute-pass-brewing-company-woodland-park",
"",
"verboten-brewing-loveland-2",
"the-very-nice-brewing-company-nederland",
"walnut-brewery-boulder",
"walter-brewing-company-pueblo",
"weldwerks-brewing-company-greeley-2",
"westbound-and-down-brewing-company-idaho-springs",
"westfax-brewing-company-denver-2",
"west-flanders-brewing-company-boulder",
"westminster-brewing-company-broomfield",
"wild-woods-brewery-boulder",
"wiley-roots-brewing-company-greeley",
"wits-end-brewing-company-denver",
"wonderland-brewing-company-broomfield",
"wynkoop-brewing-co-denver-2",
"yak-and-yeti-restaurant-and-brewpub-arvada-2",
"yampa-valley-brewing-hayden"


); 

	$R = 6371e3; // m 	
	$distances = array();
	$html = '';
	$output_ids = array();
	$output_distances = array();
	$output_breweries = array();
	$output_reviews = array();
	
	for($i = 0; $i < count($breweries_array); $i++) {				
		$lat = $breweries_array[$i]->latitude;
		$lon = $breweries_array[$i]->longitude;	

		/* Using Haversine formulae to calculate the distance between two points */
		
		$x1 = $lat - $current_latitude;
		$dLat = toRad($x1);  
		$x2 = $lon-$current_longitude;
		$dLon = toRad($x2);  
		
		$a = sin($dLat/2) * sin($dLat/2) + 
				cos(toRad($current_latitude)) * cos(toRad($lat)) * 
				sin($dLon/2) * sin($dLon/2);  
		$c = 2 * atan2(sqrt($a), sqrt(1-$a)); 
		$d = $R * $c; 
		
		$distances[] = array($i, $d);
	}
	usort($distances,"sortNumber"); 
	 
	for($j = 0; $j < 5; $j++) {
		$id = $distances[$j][0];
		$distance = $distances[$j][1];		
		
		$output_ids[] = $id;
		$output_distances[] = $distance;
		$output_breweries[] = $breweries_array[$id];		
	}
	
	/* A call to YELP */
	
//	$values = explode(",", $_POST['data']); 
	// $values = array(240,251); 

	$token =  'gMOEuLtVhlfxUVNkvZxm0JNpeiJ15GwGcM0oxc7WJYlem4XRQeb8i2fIIIDlhzvjLLF8fRJZ72gP2LWdg15GtBIoZQSqCCnoE0M-1ghZpHFmZ1pyJ1CWDPpYNr7uV3Yx';	
 
	
	foreach ($output_ids as $id) { 
		$yelp_id = $ids_array[$id]; 

		if (!empty($yelp_id)) {
			$review = searchReview($token, $yelp_id)[0];
			
			$output_reviews[] = $review;
		} 
	}  
	
	$output_json_array = array ('ids' => $output_ids, 'distances' => $output_distances, 'breweries' => $output_breweries, 'reviews' => $output_reviews);
	 echo json_encode($output_json_array); 
	
	
	
	
	
	
 }	