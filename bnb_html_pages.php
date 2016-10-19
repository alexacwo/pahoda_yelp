<?php

/**
* Shortcode for widget
*/
function add_breweries_and_brunches() { ?>	
	<div class="container" style="margin-bottom:100px;" ng-app="myApp" ng-controller="myCtrl">
		
		<div class="row">
			<div class="bnb_container">
				<div id="css_loader" class="animate_show" ng-show="showCssSpinner">
					<div class="cs-loader-inner">
						<label>	●</label>
						<label>	●</label>
						<label>	●</label>
						<label>	●</label>
						<label>	●</label>
						<label>	●</label>
					</div>
				</div>
			</div>
		</div> <!-- .row -->
		
		<div class="row">
			<div id="first_screen" class="animate_show bnb_container" ng-show="displayFirstScreen">
				<img class="life_at_5280_logo" src="<?php echo plugins_url('/breweries-and-brunches/img/'); ?>life_at_5280.png" alt="LifeAt5280.com" />
				<div class="title">Breweries and Brunches</div>
				 
				<button id="enter_address" class="enter_address_btn" ng-click="enterAddress()">Enter an Address</button>
					<span class="or">OR</span>
				<button id="choose_address" class="enter_address_btn" ng-click="chooseStartingPoint()">Choose a Starting Brewery</button>
					<span class="or">OR</span>			
				<button id="current_address" class="enter_address_btn" ng-click="useCurrentLocation()">Use Current Location</button>
			</div>
		</div> <!-- .row -->
		
		<div class="row">
			<div id="businesses_screen" class="animate_show bnb_container" ng-show="displayBusinessesScreen">
				<img class="life_at_5280_logo" src="<?php echo plugins_url('/breweries-and-brunches/img/'); ?>life_at_5280.png" alt="LifeAt5280.com" />
				<br><strong> Radius filter:</strong>
				<br>				
				<div class="filter_radius">
					<div>
						<input type="radio" id="first_option" class="filter_radio" ng-model="radiusFilter" value="3">
						<label for="first_option">3 Miles</label>
					</div>
					<div>
						<input type="radio" id="second_option" class="filter_radio" ng-model="radiusFilter" value="6">
						<label for="second_option">6 Miles</label>
					</div>
					<div>
						<input type="radio" id="third_option" class="filter_radio" ng-model="radiusFilter" value="10">
						<label for="third_option">10 Miles</label>
					</div>
					<div>
						<input type="radio" id="fourth_option" class="filter_radio" ng-model="radiusFilter" value="15">
						<label for="fourth_option">15 Miles</label>
					</div>
					<div>
						<input type="radio" id="fifth_option" class="filter_radio checked" ng-model="radiusFilter" value="0">
						<label for="fifth_option">All</label>
					</div>
				</div>
				
				<div class="title">Brewery and Brunch Locations</div>
				
				<div id="scroll_list">
					<div class="business_block" ng-repeat="business in bnbList | orderObjectBy: 'title'" ng-click="searchClosestToBusiness(business.id, business.latitude, business.longitude, business.address)">
						<div class="image" >
							<img ng-src="<?php echo plugins_url('/breweries-and-brunches/img/'); ?>{{business.image || 'blank_brewery_logo.jpg'}}  " alt="{{business.title}}" />
						</div>
						<div class="information"> 
							<div class="business_title">{{business.title}}</div>
							{{business.phone}}
							<br>{{business.url}}
							<br>{{business.address}}
						</div>
						<div class="clearfix">
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div> <!-- .row -->
		
		<div class="row">
			<div id="second_screen" class="animate_show bnb_container" ng-show="displaySecondScreen"> 
				<img class="life_at_5280_logo" src="<?php echo plugins_url('/breweries-and-brunches/img/'); ?>life_at_5280.png" alt="LifeAt5280.com" />
				<div id="enter_coordinates" class="animate_show" ng-show="displayEnterCoordinatesSearch">
					
					<label for="address_box">Please enter your address:</label>
					<input type="text" class="form-control" id="address_box">
					<div class="clearfix"></div>
				</div> 
				<strong> Radius filter:</strong>
				<br>				
				<div class="filter_radius">
					<div>
						<input type="radio" id="first_option_2" class="filter_radio" ng-model="radiusFilter" value="3">
						<label for="first_option_2">3 Miles</label>
					</div>
					<div>
						<input type="radio" id="second_option_2" class="filter_radio" ng-model="radiusFilter" value="6">
						<label for="second_option_2">6 Miles</label>
					</div>
					<div>
						<input type="radio" id="third_option_2" class="filter_radio" ng-model="radiusFilter" value="10">
						<label for="third_option_2">10 Miles</label>
					</div>
					<div>
						<input type="radio" id="fourth_option_2" class="filter_radio" ng-model="radiusFilter" value="15">
						<label for="fourth_option_2">15 Miles</label>
					</div>
					<div>
						<input type="radio" id="fifth_option_2" class="filter_radio checked" ng-model="radiusFilter" value="0">
						<label for="fifth_option_2">All</label>
					</div>
				</div>
				<button class="search_button btn btn-primary" ng-click="searchClosestToEntered()" ng-show="displayEnterCoordinatesSearch">Search</button>
				<div id="current_coordinates" class="animate_show" ng-show="displayCurrentLocationSearch">
					<button class="search_button btn btn-primary" ng-click="searchClosestToCurrent()">Search</button>				
				</div>	
			</div>
		</div> <!-- .row -->
		
		<div class="row">
			<div id="third_screen" class="animate_show bnb_container" ng-show="displayThirdScreen"> 
				<img class="life_at_5280_logo" src="<?php echo plugins_url('/breweries-and-brunches/img/'); ?>life_at_5280.png" alt="LifeAt5280.com" />
				<div ng-show="displayThirdScreenBreweries">
					<div class="brewery title">Breweries</div>	
					<div id="brewery_blocks_container">						 
					</div>
					<br>
				</div>
				<div ng-show="displayThirdScreenBrunches">
					<div class="brunch title">Add Brunches?</div>	
					<div id="brunch_blocks_container">						 
					</div>
				</div>
				<button id="choose_brewery" class="btn btn-primary" ng-click="displayChosenBreweries()" ng-show="displayThirdScreenBreweries || displayThirdScreenBrunches">Choose</button>
				<div class="clearfix"></div>
			</div>
		</div> <!-- .row -->
		
		<div class="row">
			<div id="fourth_screen" class="animate_show bnb_container" ng-show="displayFourthScreen">
				<img class="life_at_5280_logo" src="<?php echo plugins_url('/breweries-and-brunches/img/'); ?>life_at_5280.png" alt="LifeAt5280.com" />
				<div class="title">Your Selections</div>
				<br>Want to change the order? Drag the breweries to the desired order so the system can map them for you.
				<div id="breweries_results">		
					<ul id="sortable">
					</ul>
				</div>
				<button id="show_map" class="btn btn-success" ng-click="displayGoogleMap()">Show the map</button>
				<div class="clearfix"></div>
				<div id="reviews">
				</div>
			</div>
		</div> <!-- .row -->
		
		<div class="row" id="wrapper"> 
			<div id="fifth_screen" class="animate_show bnb_container" ng-show="displayFifthScreen">
				<img class="life_at_5280_logo" src="<?php echo plugins_url('/breweries-and-brunches/img/'); ?>life_at_5280.png" alt="LifeAt5280.com" />
				<div id="bnbMap">
				</div>
				<div class="container-scroll">
					<div class="main">
						<div class="google_maps_title">Driving direction</div>
					</div>
				</div>
				<div id="driving_directions" ng-show="displayDrivingDirections">					
					<div class="content">
					</div>
				</div>
				<div id="send_email_block">
					<form name="myForm">
						<input id="email_value" placeholder="Email Address" type="email" name="input" ng-model="text"  ng-pattern="/^[a-z]+[a-z0-9._]+@[a-z]+\.[a-z.]{2,5}$/" required/>
						<button id="send_mail" type="submit" class="btn" ng-click="sendEmail(); $event.stopPropagation();">Send</button>
						<div ng-show="myForm.input.$error.pattern" class="error">
							<span>Please enter a valid email address!</span>
						</div>
						<div class="clearfix"></div>
					</form>
				</div>				
				<span class="disclaimer">
					Disclaimer - By Entering Email address you are agreeing to receive emails from our advertisers (Once a month) and you can always unsubscribe. This is how we make money and how you get special offers. I also understand the importance of abiding by DUI laws and understand it is critical to use ride sharing services, taxi or a Designated Driver.
				</span>
				
			</div>
		</div> <!-- .row -->
		
	</div> <!-- .container -->
	
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAMqtGpk7KLlbAHLF0Ov82_--RAErNdPFU&signed_in=true"></script>
<?php }