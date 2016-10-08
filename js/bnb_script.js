jQuery( document ).ready(function() {
		 
	
	var R = 6371e3; // m 	
	var distances = [];
	var nearestBusinesses = [];
	var waypoints = [];		
	var html = '';
	var address = '';
	var latitude = 0;	
	var longitude = 0;	
	var origin;
	var output_ids;
	var output_distances;
	var output_breweries
	
	/* Display coordinates depending on a select choice */
	jQuery( "#select_address" ).change(function () {
		var value = jQuery( "#select_address option:selected" ).val();
		switch (value) {
			case 'current':
				jQuery('#current_coordinates').show('slow');
				jQuery('#enter_coordinates').hide('slow');
			break;
		case 'enter':
			jQuery('#current_coordinates').hide('slow');
			jQuery('#enter_coordinates').show('slow');	
			break;
		default:
			console.log('default');
		}
	}); 
	
	jQuery('#current_coordinates .search_button').click(function() {

		jQuery('#select_address').prop('disabled',true);	
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
			 	latitude =  position.coords.latitude;
				longitude =  position.coords.longitude; 
				
				// TEST
				/*latitude =  40.0163007;
				longitude =  -105.2266636;*/
				
				jQuery('#current_coordinates').html('Your coordinates: <br>-latitude: ' + latitude + '<br>-longitude: ' + longitude + '<br><br>'); 
				jQuery('#loading').show();
				
				getNearestBusinesses(latitude, longitude);
				//showMap();
			}, function() {
				console.log('Error with Geolocating');
			});
		} else {
			console.log('Error with Geolocating');
		} 
	}); 
	
	jQuery('#enter_coordinates .search_button').click(function() { 
		jQuery('#select_address').prop('disabled',true);
		jQuery('#loading').show();
		jQuery('#enter_coordinates').hide('slow');	
		
		address = jQuery('#address_box').val();
		getCoordinatesFromAddress(address);
	}); 
	
	function getCoordinatesFromAddress(address) {
		jQuery.ajax({
			url:"https://maps.googleapis.com/maps/api/geocode/json?address="+address+"&sensor=false",
			type: "POST",
			success:function(res){
				console.log('Success'); 
				latitude =  res.results[0].geometry.location.lat;					
				longitude =  res.results[0].geometry.location.lng;	


		 console.log(latitude);
		 console.log(longitude);
				getNearestBusinesses(latitude, longitude);
				//showMap(); 				
			},
			error: function(res) {
				console.log('Error: ' + res);
			}
		});
	}
	/* Sort the breweries results */
    jQuery( "#sortable" ).sortable({
		axis: "y"
	});
	jQuery( "#sortable" ).disableSelection();	
	
	jQuery('#show_map').click(function() {
		jQuery('#breweries_results').hide('slow');
		jQuery('#reviews').hide('slow');
		
		jQuery('.ui-state-default').each(function() {
			id = parseInt(jQuery( this ).prop( "id" ));
			nearestBusinesses.push(id);
		});			
		
		jQuery('#map').show();		
		initMap(address);			 
	}); 	
	
	function sortNumber(a,b) {
		return a[1] - b[1];
	} 
	
	function getNearestBusinesses(latitude, longitude) {		
		
		console.log(ajax_script);
		jQuery.ajax({
			type: "POST",
			url: ajax_script,
			data:  {curr_lat: latitude, curr_lon: longitude} ,
			success: function( data ) {
			
				console.log(data);	
				parsed_data = JSON.parse(data);			
				
				output_ids = parsed_data.ids;
				output_distances = parsed_data.distances;
				output_breweries = parsed_data.breweries;
				output_reviews = parsed_data.reviews;
				
				console.log(output_distances);	
				console.log(output_breweries);	
				console.log(output_reviews);	
				
				output_breweries_html = '';
				output_reviews_html = '';
				
				for( j = 0; j < output_ids.length; j++ ) {
					number = j + 1;
					
					if ('null' != output_ids[j]) {
						output_id = output_ids[j];
						output_breweries_html += '<li id="' + output_id + '" class="ui-state-default">';
							output_breweries_html += number + '). ID: ' + output_id + ', distance: ' + output_distances[j] + ' meters <br>';
							output_breweries_html += '<b>Title:</b> ' + output_breweries[j].title + ', <b>website:</b> ' + output_breweries[j].url + ',';
							output_breweries_html += '<b>phone:</b> ' + output_breweries[j].phone + ', address: ' + output_breweries[j].address + '<br>';
						output_breweries_html += '</li>';
					}					
					
					if (null != output_reviews[j]) {
						output_reviews_html += '<div> ID: ' + output_id + ',<br> review: ' + output_reviews[j].text + ',<br> rating: ' + output_reviews[j].rating + '<br><br>';
					}
				}
				
				jQuery('#breweries_results #sortable').html(output_breweries_html);
				jQuery ('#breweries_results').show('slow'); 
				
				
				jQuery('#reviews').html('<b>REVIEWS FROM YELP</b><br>' + output_reviews_html); 
				jQuery('#loading').hide('slow');
				jQuery('#show_map').show('slow');
				
				
			} 
		});
		
		
	} 
	
	/*function showMap() {		 
		var post_data = ''; 
		for(i = 0; i < 5; i++) { 
			//post_data += distances[i][0];
			post_data += output_distances[i][0];
			post_data += i == 4 ? '' : ','					
		} 
		console.log('POST data' + post_data);
		jQuery.ajax({
			type: "POST",
			url: '/step/pahoda/yelp/wp-content/plugins/breweries-and-brunches/brew_ajax.php',
			data:  {data: post_data} ,
			success: function( data ) {
			
				jQuery('#reviews').html('<b>REVIEWS FROM YELP</b><br>' + data); 
				jQuery('#show_map').show('slow');
		 
			} 
		});
	}
	*/
	function initMap(address) {
		console.log('Address: ' + address);
		if (address == '') {
			origin = new google.maps.LatLng(latitude, longitude);
		} else {
			origin = address;
		}
		var map;
		var bounds = new google.maps.LatLngBounds();
		var mapOptions = {
			mapTypeId: 'roadmap'
		};
				
		// Display a map on the page
		map = new google.maps.Map(document.getElementById("map"), mapOptions);
		map.setTilt(45);

		var directionsService = new google.maps.DirectionsService;
		var directionsDisplay = new google.maps.DirectionsRenderer;
		directionsDisplay.setMap(map);
				
		console.log('Nearest businesses: ' + nearestBusinesses);
		// Loop through our array of markers & place each one on the map  
		//coordinates
		for( i = 0; i < nearestBusinesses.length-1; i++ ) {
					 
			//id = nearestBusinesses[i];
			var position = new google.maps.LatLng(output_breweries[i].latitude, output_breweries[i].longitude);
			bounds.extend(position);
			  
			map.fitBounds(bounds);		
			
			waypoints.push({
				location: output_breweries[i].address,
				stopover: true
			});  
		}
		
		console.log('Waipoints: ' + waypoints);
		lastElement = output_breweries.length - 1 ; 
		
		directionsService.route({
			origin: origin,
			destination: output_breweries[lastElement].address,
			waypoints: waypoints,
			travelMode: google.maps.TravelMode.DRIVING
		}, function(response, status) { 
			if (status === google.maps.DirectionsStatus.OK) {   
				var leg = response.routes[0].legs[0];
				var driving_directions = '';
				for(k = 0; k < leg.steps.length; k++) {
					driving_directions += 'Distance: ' + leg.steps[k].distance.text + ', duration: ' + leg.steps[k].duration.text + '<br>';
					driving_directions += 'Instruction: ' + leg.steps[k].instructions + '<br>';
				}
				jQuery('#driving_directions').show('slow');
				jQuery('#driving_directions .content').html(driving_directions);
				
				directionsDisplay.setDirections(response); 
				
			} else {
				console.log('Directions request failed due to ' + status);
			}
		}); 
		// Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
		var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
			google.maps.event.removeListener(boundsListener);
		});

	}
	
});