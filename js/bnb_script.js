var app = angular.module('myApp', []);

app.filter('orderObjectBy', function() {
  return function(items, field) {
    var filtered = [];
    angular.forEach(items, function(item) {
      filtered.push(item);
    });
    filtered.sort(function (a, b) {
      return (a[field] > b[field] ? 1 : -1);
    });
    return filtered;
  };
}); 

app.controller('myCtrl', function($scope, $http) {
		
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
	var chosenBreweries = [];
	var chosenBrunches = [];
	var chosenBreweryNumber;	
	var mapParams;
	var zoomLevel;
	var mapDiv;
	var mapDim;
	var lastAddress;
	
	var topOffset = jQuery('.life_at_5280_logo').offset().top;
	
	$scope.displayFirstScreen = true;
	$scope.displayBusinessesScreen = false;
	$scope.displaySecondScreen = false;
	$scope.displayThirdScreen = false;
	$scope.displayFourthScreen = false;
	$scope.displayFifthScreen = false;	
	$scope.displayThirdScreenBreweries = false;
	$scope.displayThirdScreenBrunches = false;	
	$scope.displayCurrentCoordinatesSearch = false;
	$scope.displayCurrentLocationSearch = false;
	$scope.displayBnbList = false;	
	$scope.displayDrivingDirections = false;	
	$scope.showCssSpinner = false;
	
	var getBoundsZoomLevel = function (bounds, mapDim) {
		var WORLD_DIM = { height: 256, width: 256 };
		var ZOOM_MAX = 21;

		function latRad(lat) {
			var sin = Math.sin(lat * Math.PI / 180);
			var radX2 = Math.log((1 + sin) / (1 - sin)) / 2;
			return Math.max(Math.min(radX2, Math.PI), -Math.PI) / 2;
		}

		function zoom(mapPx, worldPx, fraction) {
			return Math.floor(Math.log(mapPx / worldPx / fraction) / Math.LN2);
		}

		var ne = bounds.getNorthEast();
		var sw = bounds.getSouthWest();
		var latFraction = (latRad(ne.lat()) - latRad(sw.lat())) / Math.PI;
		var lngDiff = ne.lng() - sw.lng();
		var lngFraction = ((lngDiff < 0) ? (lngDiff + 360) : lngDiff) / 360;
		var latZoom = zoom(mapDim.height, WORLD_DIM.height, latFraction);
		var lngZoom = zoom(mapDim.width, WORLD_DIM.width, lngFraction);

		return Math.min(latZoom, lngZoom, ZOOM_MAX);
	}

	var getNearestBusinesses = function(latitude, longitude, radius, businessId) {		
	 
		jQuery.ajax({
			type: "POST",
			url: script_vars.list_businesses_by_distance,
			data:  {curr_lat: latitude, curr_lon: longitude, filter_radius: radius} ,
			success: function( data ) {			
				
				window.scrollTo(0,topOffset);
				 
				parsed_data = JSON.parse(data);			

				sorted_breweries_ids = parsed_data.sorted_breweries_ids;
				sorted_brunches_ids = parsed_data.sorted_brunches_ids;
				output_breweries_distances = parsed_data.breweries_distances;
				output_brunches_distances = parsed_data.brunches_distances;
				output_breweries = parsed_data.breweries; 
				output_brunches = parsed_data.brunches; 

				output_breweries_html = ''; 
				output_brunches_html = ''; 
				
				if (null != sorted_breweries_ids) {
					sorted_breweries_ids_length = sorted_breweries_ids.length;
					$scope.displayThirdScreenBreweries = true;
				} else {
					sorted_breweries_ids_length = 0;
				} 
	
				for( j = 0; j < sorted_breweries_ids_length; j++ ) {
					
					if ('null' != sorted_breweries_ids[j]) {
						output_brewery_id = sorted_breweries_ids[j];
						
						if (('undefined' != typeof businessId && output_brewery_id != businessId) || 'undefined' == typeof businessId) {
						
							distanceToBrewery = (output_breweries_distances[j]/1000*0.621371).toFixed(2); /* meters->km->miles*/	
 
							output_breweries_html += '<div class="business_block_container">';
							output_breweries_html += '<input data-choice="' + output_brewery_id + '" type="checkbox" />';
							output_breweries_html += '<div class="brewery_block"><div class="information">';
							output_breweries_html += '<b><a class="heading" href="http://' + output_breweries[output_brewery_id-1].url + '">' + output_breweries[output_brewery_id-1].title  + '</a></b>';
							output_breweries_html += '<br><i class="fa fa-phone" aria-hidden="true"></i>' + output_breweries[output_brewery_id-1].phone;
							output_breweries_html += '<br><i class="fa fa-globe" aria-hidden="true"></i>';
							output_breweries_html += '<a class="url" href="http://' + output_breweries[output_brewery_id-1].url + '">' + output_breweries[output_brewery_id-1].url + '</a>';
							output_breweries_html += '<br><i class="fa fa-map-marker" aria-hidden="true"></i>' + output_breweries[output_brewery_id-1].address;
							output_breweries_html += '<br><strong>Distance:</strong>';
							output_breweries_html += '<br>' + distanceToBrewery + ' miles';
							output_breweries_html += '<br><button class="yelp_reviews" data-yelp="' + output_breweries[output_brewery_id-1].yelp_id + '">REVIEWS</button>';
							output_breweries_html += '</div>';
							output_breweries_html += '<div class="image">';
							
							img_src = output_breweries[output_brewery_id-1].image ? output_breweries[output_brewery_id-1].image : 'blank_brewery_logo.jpg';
							output_breweries_html += '<img src="' + script_vars.plugins_url + '/breweries-and-brunches/img/' + img_src + '" alt="' + output_breweries[output_brewery_id-1].title + '" />';
							
							output_breweries_html += '</div>';
							output_breweries_html += '<div class="clearfix">';
							output_breweries_html += '</div>';
							output_breweries_html += '</div>';
							output_breweries_html += '</div>';
						}
					}	
				} 
				jQuery('#brewery_blocks_container').html(output_breweries_html); 				 
				
				if (null != sorted_brunches_ids) {
					sorted_brunches_ids_length = sorted_brunches_ids.length;
					$scope.displayThirdScreenBrunches = true;
				} else {
					sorted_brunches_ids_length = 0;
				}
				 
				for( j = 0; j < sorted_brunches_ids_length; j++ ) {
					
					if ('null' != sorted_brunches_ids[j]) {
						output_brunch_id = sorted_brunches_ids[j];
						    
						if (('undefined' != typeof businessId && output_brunch_id != businessId) || 'undefined' == typeof businessId) {	
						
							distanceToBrunch = (output_brunches_distances[j]/1000*0.621371).toFixed(2); /* meters->km->miles*/	
							
							output_brunches_html += '<div class="business_block_container">';
							output_brunches_html += '<input data-choice="' + output_brunch_id + '" type="checkbox" />';
							output_brunches_html += '<div class="brunch_block"><div class="information">';							
							output_brunches_html += '<b><a class="heading" href="http://' + output_brunches[output_brunch_id-1].url + '">' + output_brunches[output_brunch_id-1].title  + '</a></b>';
							output_brunches_html += '<br><i class="fa fa-phone" aria-hidden="true"></i>' + output_brunches[output_brunch_id-1].phone;  
							output_brunches_html += '<br><i class="fa fa-globe" aria-hidden="true"></i>';
							output_brunches_html += '<a class="url" href="http://' + output_brunches[output_brunch_id-1].url + '">' + output_brunches[output_brunch_id-1].url + '</a>';
							output_brunches_html += '<br><i class="fa fa-map-marker" aria-hidden="true"></i>' + output_brunches[output_brunch_id-1].address;  
							output_brunches_html += '<br><strong>Distance:</strong>';
							output_brunches_html += '<br>' + distanceToBrunch + ' miles';
							output_brunches_html += '<br><button class="yelp_reviews" data-yelp="' + output_brunches[output_brunch_id-1].yelp_id + '">REVIEWS</button>';
							output_brunches_html += '</div>';
							output_brunches_html += '<div class="image">';
							
							img_src = output_brunches[output_brunch_id-1].image ? output_brunches[output_brunch_id-1].image : 'blank_brewery_logo.jpg';
							output_brunches_html += '<img src="' + script_vars.plugins_url + '/breweries-and-brunches/img/' + img_src + '" alt="' + output_brunches[output_brunch_id-1].title + '" />';
							
							output_brunches_html += '</div>';
							output_brunches_html += '<div class="clearfix">';
							output_brunches_html += '</div>';
							output_brunches_html += '</div>';
							output_brunches_html += '</div>';
						}
					}	
				}
				jQuery('#brunch_blocks_container').html(output_brunches_html); 
				
				$scope.$apply(function(){ 
					$scope.showCssSpinner = false;
				});				
				
				jQuery('.yelp_reviews').click(function() {
					yelpId = jQuery(this).data("yelp");
					
					if (yelpId) {
						jQuery.post( script_vars.yelp_reviews, { yelp_id: yelpId },
							/* If success */
							function (data) {
								 
								parsedData = JSON.parse(data);
								newData = [];
								
								// Define data for the popup
								for(i=0;i < parsedData.length; i++) {
									newData[i] = {
										userName : parsedData[i].user.name,
										userReviewUrl_href : parsedData[i].url,
										reviewText : parsedData[i].text,
										businessRating : '<div class="stars stars' + parsedData[i].rating + '"></div>'								
									}
								}

								// initalize popup
								jQuery.magnificPopup.open({ 
									key: yelpId, 
									items: newData,
									type: 'inline',
									inline: {
										// Define markup. Class names should match key names.
										markup: '<div class="white-popup"><div class="mfp-close"></div>'+
										'<h2 class="mfp-userName"></h2>'+
										'<div class="mfp-reviewText"></div>'+
										'<a class="mfp-userReviewUrl" target="_blank">'+
										'Read more at YELP.com...' +
										'</a>'+
										'<div class="rating_title">Rating score:</div>'+
										'<div class="mfp-businessRating"></div>'+
										'<div class="clearfix"></div>'+
										'</div>'
									},
									gallery: {
										enabled: true 
									},
								});
							}
						);
					} else {
						jQuery.magnificPopup.open({
							items: {
								src: '<div class="white-popup"><strong>No reviews for this place currently available.</strong</div>',
								type: 'inline'
							}
						});
					}
				});
			} 
		});
	} 
	
	var getCoordinatesFromAddress = function(address, radius) {
		jQuery.ajax({
			url:"https://maps.googleapis.com/maps/api/geocode/json?address="+address+"&sensor=false",
			type: "POST",
			success:function(res){
				window.scrollTo(0,topOffset);
				
				latitude =  res.results[0].geometry.location.lat;					
				longitude =  res.results[0].geometry.location.lng;	
				
				getNearestBusinesses(latitude, longitude, radius);
			},
			error: function(res) {
				console.log('Error: ' + res);
			}
		});
	}
	
	var initMap = function(address) {
		if (address == '') {
			origin = new google.maps.LatLng(latitude, longitude);
			var originStaticMapLocation = origin.lat() + ',' + origin.lng();
		} else {
			origin = address;
			var originStaticMapLocation = encodeURIComponent(origin);
		}
		var map;
		var bounds = new google.maps.LatLngBounds();
		var styleArray = [
			{
				featureType: "all",
				stylers: [
					{ saturation: -80 }
				]
			},{
				featureType: "road.arterial",
				elementType: "geometry",
				stylers: [
					{ hue: "#00ffee" },
					{ saturation: 50 }
				]
			},{
				featureType: "poi.business",
				elementType: "labels",
				stylers: [
					{ visibility: "off" }
				]
			}
		];
		var mapOptions = {
			mapTypeId: 'roadmap',
			disableDefaultUI: true,
			mapTypeControl: false,
			styles: styleArray
		};
				
		// Display a map on the page
		map = new google.maps.Map(document.getElementById("bnbMap"), mapOptions);
		map.setTilt(45); 
		var directionsService = new google.maps.DirectionsService;
		var directionsDisplay = new google.maps.DirectionsRenderer({suppressMarkers: true});
		directionsDisplay.setMap(map);
		
		/* Push addresses to waypoints array */
		for( i = 0; i < nearestBusinesses.length-1; i++ ) {
			
			parsedArray = nearestBusinesses[i].split('_');
			id = parsedArray[0];
			type = parsedArray[1];
			
			switch (type) {
			  case 'brewery':
				var position = new google.maps.LatLng(output_breweries[id].latitude, output_breweries[id].longitude);
				
				waypoints.push({
					location: output_breweries[id].address,
					stopover: true
				});  
				 
				break;
			  case 'brunch':
				var position = new google.maps.LatLng(output_brunches[id].latitude, output_brunches[id].longitude);
				
				waypoints.push({
					location: output_brunches[id].address,
					stopover: true
				});
				  
				break;
			}

			bounds.extend(position);			  
			map.fitBounds(bounds);
		}
		

		$scope.displayFifthScreen = true;
		
		lastElement = nearestBusinesses.length - 1 ; 
		
		//parsedArray = nearestBusinesses[i].split('_');
		
		lastElementParsedArray = nearestBusinesses[lastElement].split('_');
		lastId = lastElementParsedArray[0];
		lastType = lastElementParsedArray[1];
		
		switch (lastType) {
		  case 'brewery':
			lastAddress = output_breweries[lastId].address;
			endPointLatLng = new google.maps.LatLng(output_breweries[lastId].latitude, output_breweries[lastId].longitude);
			createMarker(endPointLatLng,"end",output_breweries[lastId].address);
			break;
		  case 'brunch':
			lastAddress = output_brunches[lastId].address;
			endPointLatLng = new google.maps.LatLng(output_brunches[lastId].latitude, output_brunches[lastId].longitude);
			createMarker(endPointLatLng,"end",output_brunches[lastId].address);
			break;
		}
		
		directionsService.route({
			origin: origin,
			destination: lastAddress,
			waypoints: waypoints,
			travelMode: google.maps.TravelMode.DRIVING
		}, function(response, status) { 
			if (status === google.maps.DirectionsStatus.OK) {
				var route = response.routes[0];
				var legs = response.routes[0].legs;

				var driving_directions = '';
				for(a = 0; a < route.legs.length; a++) {
					var leg = route.legs[a];
					number = a + 1;
					driving_directions += '<div class="dir_change"><img class="start_img" src="' + script_vars.plugins_url + '/breweries-and-brunches/img/dir_marker.png" /></div>';
					driving_directions += '<div class="dir_instruction">' + leg.start_address + '</div><div class="clearfix"></div>';
					for(k = 0; k < leg.steps.length; k++) {
						driving_directions += '<div class="distance_block"><span>Then ' + leg.steps[k].distance.text + '</span></div>';
						driving_directions += '<div class="dir_change"><img src="' + script_vars.plugins_url + '/breweries-and-brunches/img/dir_change.png" /></div>';
						driving_directions += '<div class="dir_instruction">' + leg.steps[k].instructions + '</div><div class="clearfix"></div>';
					}
					
					if (a == 0) { 
						createMarker(legs[a].start_location,"start",legs[i].start_address);
					} else {	
						createMarker(legs[a].start_location,"waypoint " + a,legs[i].start_address);
					}

				}
				
				jQuery('#driving_directions').show('slow');
				jQuery('#driving_directions .content').html(driving_directions);
				
				directionsDisplay.setDirections(response); 
				
				$scope.$apply(function(){
					$scope.displayDrivingDirections = true;
				});   
				
				/* Remove extra Google Maps blocks */
				
				setTimeout(customizeMap, 1000);
				
				function customizeMap() {
					jQuery( "#bnbMap .gmnoprint" ).remove();
					jQuery( "#bnbMap .gm-style > div:nth-last-child(2)" ).remove();
					
					jQuery('#bnbMap .gm-style').prepend('<div class="google_maps_title">Map</div>');
				}
			} else {
				$scope.$apply(function(){
					$scope.displayDrivingDirections = true;
				});
				jQuery('#driving_directions').html('Loading driving directions failed. Please try again.');
			}
		}); 
		
		  
		mapDiv = jQuery('#map');
		mapDim = {
			height: mapDiv.height(),
			width: mapDiv.width()
		}
		
		// Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
		var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
			google.maps.event.removeListener(boundsListener);
		});
				
		function createMarker(latlng, label, html) {
			var contentString = '<b>'+label+'</b><br>'+html;
			var marker = new google.maps.Marker({
				position: latlng,
				map: map,
				icon: new google.maps.MarkerImage(
						script_vars.plugins_url + '/breweries-and-brunches/img/map_marker.png',
						new google.maps.Size(32, 32),
						new google.maps.Point(0,0),
						new google.maps.Point(16, 32)
				),
				title: label,
				zIndex: Math.round(latlng.lat()*-100000)<<5
				});
				marker.myname = label;
		}

		
		var centerCoordinates = bounds.getCenter();

		//zoomLevel = getBoundsZoomLevel(bounds, mapDim) - 1;
		zoomLevel = 8;
		
		mapParams = 'center=' + centerCoordinates.lat() + ',' + centerCoordinates.lng() + '&zoom=' + zoomLevel + '&size=600x300&maptype=roadmap';
		mapParams += '&markers=color:0xFF551A:%7Clabel:1%7C' + originStaticMapLocation; 
		for(m = 0; m  < waypoints.length; m++) {
			number = m + 2;
			mapParams += '&markers=color:0xFF551A:%7Clabel:' + number + '%7C' + encodeURIComponent(waypoints[m].location);	
		}		
		mapParams += '&markers=color:0xFF551A:%7Clabel:X%7C' + encodeURIComponent(lastAddress);	
		
		console.log(mapParams);
	}
	
	/* First Screen */
	$scope.enterAddress = function() {       
		$scope.displayFirstScreen = false;
		$scope.displaySecondScreen = true; 
		// Radius filter: select ALL by default 
		jQuery('#fifth_option_2').attr('checked', true);
		
		$scope.displayEnterCoordinatesSearch = true;
    };		
	$scope.chooseStartingPoint = function() {  
		$scope.displayFirstScreen = false;
		$scope.displayBusinessesScreen = true;
		// Radius filter: select ALL by default 
		jQuery('#fifth_option').attr('checked', true);
		
		$scope.showCssSpinner = true;
	
		$http.get(script_vars.list_businesses)
		.then(function(response) {
			$scope.bnbList = response.data;
			
			$scope.showCssSpinner = false;

			jQuery('#scroll_list').slimScroll({
				color: '#31a2e1',
				size: '4px',
				height: '400px',
				alwaysVisible: true,
				railVisible: true,
				railColor: '#c4c5c5',
				opacity: 1,
				railOpacity: 0.6,
				wheelStep: 3
			});  
		});
    };	
	$scope.useCurrentLocation = function() {  
		$scope.displayFirstScreen = false;
		$scope.displaySecondScreen = true;
		// Radius filter: select ALL by default
		jQuery('#fifth_option_2').attr('checked', true);
	
		$scope.displayCurrentLocationSearch = true;
    };
	
	/* Second Screen */	
	$scope.searchClosestToCurrent = function() {
		$scope.displaySecondScreen = false;		  
		$scope.displayThirdScreen = true;
		
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
			 	latitude =  position.coords.latitude;
				longitude =  position.coords.longitude; 
				
				// TEST
				//latitude =  40.0163007;
				//longitude =  -105.2266636;
				$scope.$apply(function(){
					$scope.showCssSpinner = true;
					
					radius = $scope.radiusFilter;
					getNearestBusinesses(latitude, longitude, radius);
				});
				
			}, function() {
				console.log('Error with Geolocating');
			});
		} else {
			console.log('Error with Geolocating');
		} 
		
	}
	$scope.searchClosestToBusiness = function(id, latitude, longitude, businessAddress) {
		$scope.displayBusinessesScreen = false;		  
		$scope.displayThirdScreen = true;
		
		$scope.currentCoordinates = 'latitude: ' + latitude + ', longitude: ' + longitude; 
		$scope.showCssSpinner = true;
		
		address = businessAddress;		
		radius = $scope.radiusFilter;
		
		getNearestBusinesses(latitude, longitude, radius, id);
		
	}
	$scope.searchClosestToEntered = function() {
		$scope.displaySecondScreen = false;		  
		$scope.displayThirdScreen = true;
		
		$scope.showCssSpinner = true;
		
		address = jQuery('#address_box').val();		
		radius = $scope.radiusFilter;		
		
		getCoordinatesFromAddress(address, radius); 
	}
	
	/* Third Screen */
	
	$scope.displayChosenBreweries = function() {
				
		jQuery('#brewery_blocks_container input[type=checkbox]:checked ').each(function() {
			chosenId = jQuery( this ).data( "choice" );
			chosenBreweries.push(chosenId-1);
		});	
			
		jQuery('#brunch_blocks_container input[type=checkbox]:checked ').each(function() {
			chosenId = jQuery( this ).data( "choice" );
			chosenBrunches.push(chosenId-1);
		});	
				
		var chosen_businesses = ''
		for( k = 0; k < chosenBreweries.length; k++ ) {
			if ('null' != chosenBreweries[k]) {
				chosen_id = chosenBreweries[k];
				
				chosenBreweryNumber = k + 1;
				chosen_businesses += '<li id="' + chosen_id + '" data-type="brewery" class="bnb_business ui-state-default">'; 	
					chosen_businesses += '<div class="pointer"><div class="number">' + chosenBreweryNumber + '.</div>';
					chosen_businesses += '<div class="business_type">brewery</div></div>'; 
					chosen_businesses += '<div class="right_block">'; 
					chosen_businesses += '<div class="information">'; 
					chosen_businesses += '<b><a class="heading" href="http://' + output_breweries[chosen_id].url + '">' + output_breweries[chosen_id].title + '</a></b>';
					chosen_businesses += '<br><i class="fa fa-phone" aria-hidden="true"></i>'	+ output_breweries[chosen_id].phone;
					chosen_businesses += '<br><i class="fa fa-globe" aria-hidden="true"></i>';
					chosen_businesses += '<a class="url" href="http://' + output_breweries[chosen_id].url + '">' + output_breweries[chosen_id].url + '</a>';
					chosen_businesses += '<br><i class="fa fa-map-marker" aria-hidden="true"></i>'	+ output_breweries[chosen_id].address;  
					chosen_businesses += '</div>';
					chosen_businesses += '<div class="image">';
					img_src = output_breweries[chosen_id].image ? output_breweries[chosen_id].image : 'blank_brewery_logo.jpg';
					chosen_businesses += '<img src="' + script_vars.plugins_url + '/breweries-and-brunches/img/' + img_src + '" alt="' + output_breweries[chosen_id].title + '" />';
					chosen_businesses += '</div>';
					chosen_businesses += '<div class="clearfix">';
					chosen_businesses += '</div>';					
				chosen_businesses += '</li>'; 
			}					
		}
		chosenBreweryNumber = isNaN(chosenBreweryNumber) ? 0 : chosenBreweryNumber;
		for( k = 0; k < chosenBrunches.length; k++ ) {
			if ('null' != chosenBrunches[k]) {
				chosen_id = chosenBrunches[k];
				
				chosenBrunchNumber = k + 1 + chosenBreweryNumber;
				chosen_businesses += '<li id="' + chosen_id + '" data-type="brunch" class="bnb_business ui-state-default">'; 
					chosen_businesses += '<div class="pointer"><div class="number">' + chosenBrunchNumber + '.</div>';					
					chosen_businesses += '<div class="business_type">brunch</div></div>'; 
					chosen_businesses += '<div class="right_block">'; 
					chosen_businesses += '<div class="information">'; 
					chosen_businesses += '<b><a class="heading" href="http://' + output_brunches[chosen_id].url + '">' + output_brunches[chosen_id].title + '</a></b>';
					chosen_businesses += '<br><i class="fa fa-phone" aria-hidden="true"></i>'	+ output_brunches[chosen_id].phone;
					chosen_businesses += '<br><i class="fa fa-globe" aria-hidden="true"></i>';
					chosen_businesses += '<a class="url" href="http://' + output_brunches[chosen_id].url + '">' + output_brunches[chosen_id].url + '</a>';
					chosen_businesses += '<br><i class="fa fa-map-marker" aria-hidden="true"></i>'	+ output_brunches[chosen_id].address;  
					chosen_businesses += '</div>';
					chosen_businesses += '<div class="image">';
					img_src = output_brunches[chosen_id].image ? output_brunches[chosen_id].image : 'blank_brewery_logo.jpg';
					chosen_businesses += '<img src="' + script_vars.plugins_url + '/breweries-and-brunches/img/' + img_src + '" alt="' + output_brunches[chosen_id].title + '" />';
					chosen_businesses += '</div>';
					chosen_businesses += '<div class="clearfix">';
					chosen_businesses += '</div>';		
				chosen_businesses += '</li>'; 
			}					
		}
			
		jQuery('#breweries_results #sortable').html(chosen_businesses);		
		 
		window.scrollTo(0,topOffset);
		
		$scope.displayThirdScreen = false;		  
		$scope.displayFourthScreen = true;
	}
	
	/* Fourth Screen */	
	
	$scope.displayGoogleMap = function() { 
	
		$scope.displayFourthScreen = false;		 
		
		jQuery('.bnb_business.ui-state-default').each(function() {			
			id = parseInt(jQuery( this ).prop( "id" ));
			type = jQuery( this ).data("type");
			nearestBusinesses.push(id + '_' + type);
		});		
		
		window.scrollTo(0,topOffset);
		
		initMap(address);
	}
	
	/* Send Email */
	$scope.sendEmail = function() {
		userEmail = jQuery('#email_value').val();
		drivingDirections = jQuery('#driving_directions').html();	
		
		jQuery.post( script_vars.send_email, { email: userEmail, directions: drivingDirections, map_params : mapParams }, function( data ) {
			if (data == 'OK') {
				jQuery('#email_value').hide('slow');
				jQuery('#send_mail').hide('slow');
				jQuery('#send_email_block').html('<strong>Thank you! Please check your email with driving instructions.</strong>');
			}
		});
	}
});
	
jQuery( document ).ready(function() { 

	jQuery('#brewery_blocks_container').slimScroll({
		color: '#31a2e1',
		size: '4px',
		height: '350px',
		alwaysVisible: true,
		railVisible: true,
		railColor: '#c4c5c5',
		opacity: 1,
		railOpacity: 0.6,
		wheelStep: 3
	});	
	jQuery('#brunch_blocks_container').slimScroll({
		color: '#31a2e1',
		size: '4px',
		height: '350px',
		alwaysVisible: true,
		railVisible: true,
		railColor: '#c4c5c5',
		opacity: 1,
		railOpacity: 0.6,
		wheelStep: 3
	});		
	jQuery('#breweries_results').slimScroll({
		color: '#31a2e1',
		size: '4px',
		height: '350px',
		alwaysVisible: true,
		railVisible: true,
		railColor: '#c4c5c5',
		opacity: 1,
		railOpacity: 0.6,
		wheelStep: 3
	});	 	
	jQuery('#driving_directions').slimScroll({
		color: '#31a2e1',
		size: '4px',
		height: '400px',
		alwaysVisible: true,
		railVisible: true,
		railColor: '#c4c5c5',
		opacity: 1,
		railOpacity: 0.6,
		wheelStep: 3
	});	 	
	/* Sort the breweries results */
    jQuery( "#sortable" ).sortable({
		axis: "y"
	});
	jQuery( "#sortable" ).disableSelection();
	
});