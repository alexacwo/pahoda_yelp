<?php

/**
* Plugin Name: Breweries and Brunches
* Plugin URI: https://www.linkedin.com/company/pahoda-image-products
* Description: Get breweries and brunches that are nearest to you
* Version: 1.0
* Author: Pahoda Image Products
* Author URI: https://www.linkedin.com/company/pahoda-image-products
**/

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////DATABASE////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////// 


wp_enqueue_script( 'jquery_ui_js', plugins_url() . '/breweries-and-brunches/js/jquery_ui.js', array('jquery'), null );

wp_register_script( 'bnb_script', plugins_url() . '/breweries-and-brunches/js/bnb_script.js', array('jquery'), null );
$ajax_script = plugins_url() . '/breweries-and-brunches/brew_coordinates.php';
wp_localize_script( 'bnb_script', 'ajax_script', $ajax_script);
wp_enqueue_script( 'bnb_script' );

wp_enqueue_style( 'bootstrap', plugins_url() .  '/breweries-and-brunches/css/bootstrap.css', array(), null );
wp_enqueue_style( 'jquery_ui_css', plugins_url() .  '/breweries-and-brunches/css/jquery_ui.css', array(), null );
wp_enqueue_style( 'bnb_style', plugins_url() .  '/breweries-and-brunches/css/bnb_style.css', array(), null );

add_action('init', 'check_if_is_admin');	

function check_if_is_admin() {
	if (is_super_admin() ) {
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		
		$table_name_1 = $wpdb->prefix . "breweries_data";
		if($wpdb->get_var("show tables like '$table_name_1'") != $table_name_1) {

			$sql_1 = "CREATE TABLE " . $table_name_1 . " (
				`id` smallint(5) NOT NULL AUTO_INCREMENT,
				`title` varchar(50) NOT NULL,
				`url` varchar(70) NOT NULL,
				`phone` varchar(20) NOT NULL,
				`address` varchar(70) NOT NULL,
				`latitude` float(20,10) NOT NULL,
				`longitude` float(20,10) NOT NULL,
				UNIQUE KEY id (id)
			);";
			dbDelta($sql_1);
			
			$breweries_array = array(								
				0 => array (" 4B's Brewery  "," http://4bsbrewery.com/ "," (970) 856-7762 "," 215 W. Main St. Cedaredge Colorado ", 38.9005 , -107.92599999999999 ),
				1 => array (" 105 West Brewing Company "," http://105westbrewing.com/ "," (303) 325-7321 "," 1043 Park St, Castle Rock Colorado ", 39.3807478 , -104.86602370000003 ),
				2 => array (" 7 Hermits Brewing Company "," http://7hermitsbrewing.com/ "," 970-328-6220 "," 1020 Capitol St  Eagle Colorado ", 39.64137300000001 , -106.831032 ),
				3 => array (" 300 Suns Brewing "," http://www.300sunsbrewing.com/ "," 720-442-8292 "," 335 1st Ave., Unit C Longmont Colorado ", 40.1599031 , -105.10122890000002 ),
				4 => array (" 38 State Brewing  "," http://www.38statebrew.com/ "," 720-638-3678 "," 8071 S. Broadway, Unit A Littleton Colorado ", 39.5689623 , -104.9886209 ),
				5 => array (" 12 Degree Brewing "," http://www.12degree.com/ "," 720-638-1623  "," 820 Main Street Louisville Colorado ", 39.9782702 , -105.13174149999998 ),
				6 => array (" AC Golden Brewing Company "," http://www.acgolden.com/ "," 303.292.3926 "," P.O. Box 4030-BC530 Golden Colorado ", 39.755543 , -105.22109969999997 ),
				7 => array (" Alpine Dog Brewery "," http://www.alpinedogbrewery.com/index.html "," 720-214-5170 "," 1505 Odgen St. Denver Colorado ", 39.740326 , -104.97492899999997 ),
				8 => array (" Amicas Pizza & Microbrewery "," http://amicassalida.com/ "," (719) 539-5219 "," 136 E. 2nd St. Salida Colorado ", 38.5351268 , -105.99173330000002 ),
				9 => array (" Animas Brewing Company "," http://www.animasbrewing.com/ "," 970-403-8850 "," 1560 East 2nd Ave Durango Colorado ", 37.28001 , -107.87676099999999 ),
				10 => array (" Asher Brewing Company "," http://asherbrewing.com/ "," 303-530-1381 "," 4699 Nautilus Court, Suite 104 Boulder Colorado ", 40.0586722 , -105.20596929999999 ),
				11 => array (" Aspen Brewing Company "," http://aspenbrewingcompany.com/ "," 970.920.(2739) "," 304 E Hopkins Ave Aspen Colorado ", 39.1903225 , -106.8203009 ),
				12 => array (" Avalanche Brewing Company "," http://www.avalanchebrewing.com/ "," 970-387-5282 "," 1067 Blair Street Silverton Colorado ", 37.810249 , -107.664514 ),
				13 => array (" Avery Brewing Company "," https://www.averybrewing.com/ "," 303-440-4324 "," 4910 Nautilus Ct N Boulder Colorado ", 40.0624921 , -105.20497399999999 ),
				14 => array (" Backcountry Brewery "," http://backcountrybrewery.com/beer-shop/ "," (970) 668-2337 "," 720 Main St. Frisco Colorado ", 39.57664339999999 , -106.0929319 ),
				15 => array (" Baere Brewing Compan "," http://www.baerebrewing.com/ "," 303-733-3354 "," 320 Broadway, Unit E Denver Colorado ", 39.7214312 , -104.98699690000001 ),
				16 => array (" Barnett & Son Brewing "," http://www.barnettandsonbrewing.com/about "," (720) 420-0462 "," 18425 Pony Express Dr. Parker Colorado ", 39.5224078 , -104.77524740000001 ),
				17 => array (" Barrels & Bottles Brewery "," http://www.barrelsbottles.com/ "," (720) 328-3643 "," 600 12th St #160 Golden Colorado ", 39.7565096 , -105.21995149999998 ),
				18 => array (" Beer By Design Brewery "," http://www.beerbydesign.com/ "," 303-517-2202 "," 2100 E 112th Ave #1 Northglenn Colorado ", 39.8988225 , -104.96338909999997 ),
				19 => array (" Berthoud Brewing "," http://www.berthoudbrewing.com/ "," 970-532-9850 "," 450 8th St. Suite B Berthoud Colorado ", 40.304413 , -105.084858 ),
				20 => array (" Beryl's Beer Co "," http://www.berylsbeerco.com/ "," 720-420-0826 "," 3120 BLAKE ST. DENVER Colorado ", 39.7647759 , -104.9802181 ),
				21 => array (" Bierwerks Brewery "," http://www.bierwerks.com/ "," 719-686-8100 "," 121E Midland Ave, Woodland Park Colorado ", 38.9941465 , -105.0519486 ),
				22 => array (" Big Beaver Brewing Company "," http://bigbeaverbrew.com/ "," 970-818-6064 "," 2707 W. Eisenhower Blvd., Unit 9 Loveland Colorado ", 40.4079683 , -105.11448239999999 ),
				23 => array (" Black Bottle Brewery "," https://blackbottlebrewery.com/ "," 970.493.2337 "," 1611 S College Ave Suite 1609 Fort Collins Colorado ", 40.5661965 , -105.07861530000002 ),
				24 => array (" Black Shirt Brewing "," http://www.blackshirtbrewingco.com/home "," (303) 993-2799 "," 3719 Walnut St. Denver Colorado ", 39.7698213 , -104.97293300000001 ),
				25 => array (" Black Sky Brewery "," http://www.blackskybrewing.com/ "," 720-708-5816 "," 490 Santa Fe Dr. Denver Colorado ", 39.7239414 , -104.99828980000001 ),
				26 => array (" Blue Moon Brewing Company "," https://www.bluemoonbrewingcompany.com/ "," 303-728-2337 "," 3650 Chestnut Pl. Denver Colorado ", 39.7731078 , -104.9782032 ),
				27 => array (" Blue Spruce Brewing Company "," http://www.bluesprucebrewing.com/ "," 303 771-0590 "," 4151 E County Line Rd., Unit G Centennial Colorado ", 39.5670743 , -104.94008129999997 ),
				28 => array (" Boggy Draw Brewery "," http://www.boggydrawbrewing.com/ "," 720.507.6940 "," 3535 S. Platte River Drive, Unit L Sheridan Colorado ", 39.6527895 , -105.01077600000002 ),
				29 => array (" Bonfire Brewing "," http://bonfirebrewing.com/ "," 970.306.7113 "," 127 W. Second St. Eagle Colorado ", 39.655396 , -106.82863199999997 ),
				30 => array (" Bootstrap Brewing "," https://bootstrapbrewing.com/ "," 303-652-4186 "," 6778 N. 79th Street Niwot Colorado ", 40.0998676 , -105.16839540000001 ),
				31 => array (" Boulder Beer "," http://boulderbeer.com/ "," (303) 444 - 8448 "," 2880 Wilderness Place Boulder Colorado ", 40.0265719 , -105.24805650000002 ),
				32 => array (" Breckenridge Brewery & Pub "," http://www.breckbrew.com/ "," (800) 328-6723 "," 2920 Brewery Lane Littleton Colorado ", 39.594287 , -105.02463890000001 ),
				33 => array (" Brewery Rickoli "," http://www.breweryrickoli.com/ "," (303) 344-8988 "," 4335 Wadsworth Blvd. Wheat Ridge Colorado ", 39.7758135 , -105.08195439999997 ),
				34 => array (" BREW Pub & Kitchen "," http://brewpubkitchen.com/ "," 970-259-5959 "," 117 West College Drive Durango Colorado ", 37.2706098 , -107.88232800000003 ),
				35 => array (" Briar Common Brewery & Eatery "," http://www.briarcommon.com/ "," 720-470-3731 "," 2298 Clay St. Denver Colorado ", 39.7510677 , -105.02035940000002 ),
				36 => array (" Bristol Brewing Company "," http://www.bristolbrewing.com/ "," 719-633-2555 "," 1604 S Cascade Ave. Colorado Springs Colorado ", 38.8110074 , -104.82741820000001 ),
				37 => array (" Brix Taphouse & Brewery "," http://www.brixtaphouseandbrewery.com/ "," (970) 397-6146 "," 813 8TH ST. GREELEY Colorado ", 40.4251802 , -104.69158950000002 ),
				38 => array (" Broken Compass Brewing "," http://www.brokencompassbrewing.com/#bcb "," (970) 368-2772 "," 68 Continental Court, Unit B12 Breckenridge Colorado ", 39.514358 , -106.05351200000001 ),
				39 => array (" Broken Plow Brewery "," http://www.brokenplowbrewery.com/ "," 970-301-4575 "," 4731 West 10th Street Greeley Colorado ", 40.4215401 , -104.75721299999998 ),
				40 => array (" BRU Handbuilt Ales "," http://bruboulder.com/ "," 720.638.5193 "," 5290 Arapahoe Boulder Colorado ", 40.0141248 , -105.22964000000002 ),
				41 => array (" Buckhorn Brewers "," http://www.buckhornbrewers.com/ "," 970-980-8688 "," 4229 W Eisenhower Blvd. Loveland Colorado ", 40.40788209999999 , -105.13812009999998 ),
				42 => array (" Bull & Bush Pub & Brewery "," http://bullandbush.com/ "," 303-759-0333 "," 4700 Cherry Creek South Dr. Denver Colorado ", 39.7030876 , -104.93244300000003 ),
				43 => array (" Butcherknife Brewing Company "," http://butcherknifebrewing.com/?ao_confirm "," 970-879-(2337) "," 2875 Elk River Road Steamboat Springs Colorado ", 40.5107724 , -106.85826500000002 ),
				44 => array (" Call to Arms Brewing "," http://calltoarmsbrewing.com/ "," 720.328.8258 "," 4526 Tennyson Street Denver Colorado ", 39.77887 , -105.04362900000001 ),
				45 => array (" Cannonball Creek Brewing Company "," http://www.cannonballcreekbrewing.com/ "," (303) 278-0111 "," 393 N. Washington Ave. Golden Colorado ", 39.7687093 , -105.23489330000001 ),
				46 => array (" Can't Stop Brewing "," https://www.facebook.com/CantStopBrewing/about/?entry_point=page_nav_about_item&tab=overview "," 303-523-3101 "," 35 E Hampden Ave. Englewood Colorado ", 39.65343499999999 , -104.98656199999999 ),
				47 => array (" Carbondale Beer Works "," http://www.carbondalebeerworks.com/carbondalebeerworks/Home.html "," 970-704-1216 "," 647 Main St. Carbondale Colorado ", 39.4007166 , -107.21308909999999 ),
				48 => array (" Carver Brewing Company "," http://carverbrewing.com/ "," 970-259-2545 "," 1022 Main Ave Durango Colorado ", 37.2748003 , -107.87984590000002 ),
				49 => array (" Casey Brewing & Blending "," https://caseybrewing.com/ "," 970.230.9691  "," 3421 Grand Ave Glenwood Springs Colorado ", 39.5157423 , -107.31950649999999 ),
				50 => array (" Caution Brewing Company "," http://www.cautionbrewingco.com/ "," 970-315-2739 "," 1057 S. Wadsworth Blvd., Unit 60  Lakewood Colorado ", 39.6979509 , -105.0821709 ),
				51 => array (" CB & Pott's Restaurant & Brewery "," http://www.cbpotts.com/ "," (970) 221 – 1139 "," 195 East Foothills Parkway Ft. Collins Colorado ", 40.54327749999999 , -105.0757218 ),
				52 => array (" Centennial Beer Company "," http://centennialbeercompany.com/ "," 970-402-3747 ","195 East Foothills Parkway Ft. Collins Colorado ", 1 , 2 ),
				53 => array (" Cerebral Brewing "," http://cerebralbrewing.com/ "," (303) 927-7365 "," 1477 Monroe St Denver Colorado ", 39.7397521 , -104.9450991 ),
				54 => array (" Chain Reaction Brewing "," http://www.chainreactionbrewingco.com/main.html "," (303) 922-0960 "," 902 S Lipan Street Denver Colorado ", 39.69954329999999 , -105.0012001 ),
				55 => array (" City Star Brewing Company "," https://citystarbrewing.com/ "," (970) 532-7827 "," 321 Mountain Avenue PO Box 1064 Berthoud Colorado ", 40.3083174 , -105.08109239999999 ),
				56 => array (" Colorado Boy Pub & Brewery "," http://www.coloradoboy.com/ "," 970-626-5333 "," 602 Clinton Street, PO Box 877 Ridgway Colorado ", 38.1525873 , -107.75561620000002 ),
				57 => array (" Colorado Mountain Brewery "," http://www.cmbrew.com/ "," (719) 466-8240 "," 600 S 21st St, Unit 180 Colorado Springs Colorado ", 38.8400167 , -104.8597997 ),
				58 => array (" Colorado Plus "," http://www.coloradoplus.net/ "," (720) 353-4853 "," 6995 WEST 38TH AVENUE WHEAT RIDGE Colorado ", 39.7696601 , -105.07417750000002 ),
				59 => array (" Comrade Brewing Company "," http://comradebrewing.com/ "," (720) 748-0700 "," 7667 East Iliff Ave. #F Denver Colorado ", 39.67572880000001 , -104.89849630000003 ),
				60 => array (" Coopersmith's Pub & Brewing "," http://coopersmithspub.com/info/bars-fort-collins/# "," 970.498.0483 "," # 5 Old Town Square Fort Collins Colorado ", 40.5873521 , -105.07565970000002 ),
				61 => array (" Coors Archive Brewing Company "," http://www.millercoors.com/ "," 800-645-5376 ","  Golden Colorado ", 39.755543 , -105.22109969999997 ),
				62 => array (" Copper Club Brewing "," http://www.copperclubbrew.com/ "," 970-858-8318   "," 233 E. Aspen St. Fruita Colorado ", 39.1591604 , -108.73148950000001 ),
				63 => array (" Copper Kettle Brewing Company "," http://www.copperkettledenver.com/about.html "," 720-443-2522 "," 1338 S. Valentia Street, #100 Denver Colorado ", 39.6924489 , -104.8904852 ),
				64 => array (" Crabtree Brewing Company "," http://crabtreebrewing.com/ "," (970) 356-0516 "," Address: 2961 W 29th Street Greeley Colorado ", 40.3908288 , -104.72637930000002 ),
				65 => array (" Crazy Mountain Brewery "," http://www.crazymountainbrewery.com/ "," 720.379.4520 "," 471 Kalamath St. Denver Colorado ", 39.7236763 , -105.00059349999998 ),
				66 => array (" Creede Brewing Company "," http://creedebeer.com/ "," (303) 287-4824 "," 7314 Washington Street  Denver Colorado ", 39.8293573 , -104.97711249999998 ),
				67 => array (" Crooked Stave "," http://www.crookedstave.com/ "," 720-550-8860 "," 3350 Brighton Blvd. Denver Colorado ", 39.7686109 , -104.9797577 ),
				68 => array (" Crow Hop Brewing "," http://www.crowhopbrewing.com/ "," 970.633.0643 "," 217 E. 3rd St. Loveland Colorado ", 40.3945768 , -105.07406930000002 ),
				69 => array (" Crystal Springs Brewing Company "," http://crystalspringsbrewing.com/ "," 303-665-8888 "," 657 S. Taylor Ave. Unit E Louisville Colorado ", 39.9605672 , -105.12024180000003 ),
				70 => array (" Dad & Dudes Breweria "," http://www.breweria.com/ "," 303.400.5699 "," 6730 S Cornerstar Way Aurora Colorado ", 39.5940378 , -104.8063467 ),
				71 => array (" Dead Hippie Brewing "," http://www.deadhippiebrewing.com/ "," 720.446.7961 "," 3701 S. Santa Fe Dr, Unit 7 Sheridan Colorado ", 39.6491165 , -105.0040257 ),
				72 => array (" Declaration Brewing "," http://www.declarationbrewing.com/ "," (303) 955 . 7410 "," 2030 S CHEROKEE ST Denver Colorado ", 39.6797757 , -104.99076919999999 ),
				73 => array (" Deep Draft Brewing "," https://www.facebook.com/DeepDraftBrewing ","  "," 1604 E 17th Ave Denver Colorado ", 39.743155 , -104.9681142 ),
				74 => array (" De Steeg Brewing "," http://www.desteegbrewing.com/ "," (303) 484-9698 "," 4342 Tennyson St Denver Colorado ", 39.7761052 , -105.04367460000003 ),
				75 => array (" Denver Beer Company "," http://denverbeerco.com/ "," 303-433-2739  "," 1695 Platte Street Denver Colorado ", 39.758265 , -105.007315 ),
				76 => array (" Denver ChopHouse & Brewery "," http://www.denverchophouse.com/ "," 303-296-0800 "," 1735 19th St #100 Denver Colorado ", 39.7553521 , -104.99692959999999 ),
				77 => array (" Diebolt Brewing Company "," http://dieboltbrewing.com/ "," (720) 643-5940 "," 3855 Mariposa Street Denver Colorado ", 39.7703563 , -105.0033014 ),
				78 => array (" Dillon Dam Brewing Company "," https://www.dambrewery.com/ "," (970) 262-7777 "," 100 Little Dam Street Dillon Colorado ", 39.62757089999999 , -106.06031589999998 ),
				79 => array (" Dodgeton Creek Brewing "," http://www.dodgetoncreek.com/ "," 719-846-2339 "," 36730 Democracy Dr. Trinidad Colorado ", 37.2179725 , -104.49040000000002 ),
				80 => array (" Dolores River Brewery "," http://doloresriverbrewery.com/ "," (970) 882-4677 "," 100 S. 4TH ST. Dolores Colorado ", 37.473538 , -108.50489500000003 ),
				81 => array (" Dostal Alley Brewpub "," http://www.dostalalley.net/Site/Dostal_Alley.html "," 303-582-1610 "," 116 Main St Central City Colorado ", 39.800139 , -105.51259040000002 ),
				82 => array (" Dry Dock Brewing Company "," http://drydockbrewing.com/?ao_confirm "," (303) 400-5606 "," 15120 E Hampden Ave Aurora Colorado ", 39.65265249999999 , -104.81243660000001 ),
				83 => array (" Durango Brewing Company "," http://www.durangobrewing.com/ "," 970-247-3396 "," 3000 Main Ave Durango Colorado ", 37.2976817 , -107.87191840000003 ),
				84 => array (" Echo Brewing Company "," http://echobrewing.com/ "," (720)445-5969 "," 5969 Iris Parkway Frederick Colorado ", 40.11175009999999 , -104.94540080000002 ),
				85 => array (" Elk Mountain Brewing Company "," http://www.elkmountainbrewing.com/ "," 03-805-(2739) "," 18921 Plaza Drive, Unit 104 Parker Colorado ", 39.52699640000001 , -104.77005730000002 ),
				86 => array (" Eddyline Brewing "," http://eddylinebrewing.com/ "," 719-966-6018 "," 102 Linderman Ave Buena Vista Colorado ", 38.840558 , -106.13264379999998 ),
				87 => array (" Eldo Brewery & Taproom "," http://eldobrewpub.com/ "," 970 - 349 - 6125 "," 215 Elk Avenue Crested Butte Colorado ", 38.8697772 , -106.98706349999998 ),
				88 => array (" Elevation Beer Company "," http://elevationbeerco.com/ "," (719) 539-5258 "," 115 Pahlone Pkwy Poncha Springs Colorado ", 38.5181954 , -106.06546960000003 ),
				89 => array (" Equinox Brewing "," http://www.equinoxbrewing.com/ "," 970-484-1368 "," 133 Remington Street Fort Collins Colorado ", 40.586352 , -105.07586570000001 ),
				90 => array (" Estes Park Brewery "," http://www.epbrewery.com/ "," 970-586-5421 "," 470 Prospect Village Drive Estes Park Colorado ", 40.3713462 , -105.5260778 ),
				91 => array (" Factotum BrewHouse "," http://factotumbrewhouse.com/ "," 720-441-4735 "," 3845 Lipan St. Denver Colorado ", 39.7702189 , -105.00206789999999 ),
				92 => array (" Fate Brewing Company "," http://fatebrewingcompany.com/ "," 303-449-3283 "," 1600 38th St, # 100 Boulder Colorado ", 40.0150396 , -105.24560309999998 ),
				93 => array (" Fermaentra Brewing "," http://www.fermaentra.com/home/ "," 303-955-0736 "," 1715 East Evans Avenue Denver Colorado ", 39.6787523 , -104.96704649999998 ),
				94 => array (" Fiction Beer Company "," http://www.fictionbeer.com/ "," 720-456-7163 "," 7101 East Colfax Avenue Denver Colorado ", 39.7403815 , -104.90573139999998 ),
				95 => array (" Fieldhouse Brewing Company "," http://www.fieldhousebrew.com/ "," (719) 354-4143 "," 521 S Tejon St. Colorado Springs Colorado ", 38.8259689 , -104.8236301 ),
				96 => array (" Finkel & Garf Brewery "," http://finkelandgarf.com/ "," 720-379-6042 "," 5455 Spine Rd, Unit A Boulder Colorado ", 40.0740121 , -105.20287350000001 ),
				97 => array (" Flood Stage Aleworks "," https://www.facebook.com/floodstagealeworks2013/about/?entry_point=page_nav_about_item&ref=page_internal "," 303-654-7972 "," 170 S Main St. Brighton Colorado ", 39.9840025 , -104.8218488 ),
				98 => array (" Flying Dog Brewery "," http://flyingdogbrewery.com/ "," 301-694-7899 ","  Denver Colorado ", 39.7392358 , -104.990251 ),
				99 => array (" Former Future Brewing "," http://www.embracegoodtaste.com/about/ "," 720-441-4253 "," 1290 South Broadway Denver Colorado ", 39.6933944 , -104.98705339999998 ),
				100 => array (" Fossil Brewing "," http://www.fossilbrewing.com/ "," (719) 375-8298 "," 2845 Ore Mill Road Suite 1  Colorado Springs Colorado ", 38.847991 , -104.870091 ),
				101 => array (" Fort Collins Brewery "," http://fortcollinsbrewery.com/ "," 970-472-1499  "," 1020 E. Lincoln Ave. Fort Collins Colorado ", 40.5891747 , -105.0585226 ),
				102 => array (" Front Range Brewing "," http://www.frontrangebrewingcompany.com/ "," 303-339-0767 "," 400 W South Boulder Rd Suite 1650 Lafayette Colorado ", 39.9856334 , -105.09490289999997 ),
				103 => array (" Funkwerks "," http://funkwerks.com/ "," (970)482-3865 "," 1900 E Lincoln Ave, Unit B Fort Collins Colorado ", 40.5830931 , -105.04205780000001 ),
				104 => array (" Glenwood Canyon Brewing Company "," http://glenwoodcanyonbrewpub.com/ "," 970.945.1276 "," 402 7th Street Glenwood Springs Colorado ", 39.5475675 , -107.32318329999998 ),
				105 => array (" Golden Block Brewery "," http://goldenblockbrewery.com/ "," 970-387-5962 "," 1227 Greene St. Silverton Colorado ", 37.8119545 , -107.6641184 ),
				106 => array (" Golden City Brewery "," http://gcbrewery.com/ "," 303-279-8092 "," 920 1/2 Twelfth Street Golden Colorado ", 39.75468559999999 , -105.22365430000002 ),
				107 => array (" Goldspot Brewing "," http://goldspotbrewing.com/ "," 303-955-5657 "," 4970 Lowell Boulevard Denver Colorado ", 39.7868956 , -105.0343115 ),
				108 => array (" Gold Camp Brewing "," https://www.facebook.com/Gold-Camp-Brewing-Company-914106935272322/about/?entry_point=page_nav_about_item "," 719-695-0344 "," 1007 S Tejon St. Colorado Springs Colorado ", 38.8195711 , -104.82360990000001 ),
				109 => array (" Gore Range Brewery "," http://www.gorerangebrewery.com/ "," 970-926-2739 "," 05 Edwards Village Blvd. Edwards Colorado ", 39.6440901 , -106.59407920000001 ),
				110 => array (" Grandma's House "," http://www.grandmasbeer.co/ "," (303) 578-6754 "," 1710 South Broadway Denver Colorado ", 39.6854469 , -104.98714789999997 ),
				111 => array (" Grand Lake Brewing Company "," http://www.grandlakebrewing.com/ "," 720-723-2179 "," 5610 YUKON ST ARVADA Colorado ", 39.7995091 , -105.08247319999998 ),
				112 => array (" Gravity Brewing "," http://www.thegravitybrewing.com/ "," 303-544-0746 "," 1150 Pine Street Unit B Louisville Colorado ", 39.9760839 , -105.12849649999998 ),
				113 => array (" Great Divide Brewing Company "," http://greatdivide.com/ "," 303-296-9460 "," 2201 Arapahoe St. Denver Colorado ", 39.753793 , -104.9884662 ),
				114 => array (" Great Storm Brewing Company "," http://www.greatstormbrewing.com/GSBFrameset.htm "," 719.266.4200 "," 204 Mount View Lane #3 Colorado Springs Colorado ", 38.8900401 , -104.81799239999998 ),
				115 => array (" Grimm Brothers Brewhouse "," https://www.facebook.com/GrimmBrothersBrewhouse/about/?entry_point=page_nav_about_item "," 970-624-6045 "," 623 Denver Ave. Loveland Colorado ", 40.3967058 , -105.0469349 ),
				116 => array (" Grist Brewing Company "," https://www.gristbrewingcompany.com/ "," (720) 360 - 4782  "," 9150 COMMERCE CENTER CIR,SUITE 300 Highlands Ranch Colorado ", 39.5491319 , -105.03384460000001 ),
				117 => array (" Grossen Bart Brewery "," http://grossenbart.com/ "," 720.438.2060 "," 1025 Delaware Ave. Longmont Colorado ", 40.1544294 , -105.10924979999999 ),
				118 => array (" Gunbarrel Brewing Company "," http://www.gunbarrelbrewing.com/index.php "," 800-803-5732 ","  Boulder Colorado ", 40.0149856 , -105.27054559999999 ),
				119 => array (" High Alpine Brewing "," http://highalpinebrewing.com/ "," 970-642-4500 "," 111 N Main St. Gunnison Colorado ", 38.544747 , -106.92731100000003 ),
				120 => array (" Halfpenny Brewing Company "," http://www.halfpennybrewing.com/ "," 720-583-0580 "," 5150 E Arapahoe Rd, Unit D1-B Centennial Colorado ", 39.5935248 , -104.92740600000002 ),
				121 => array (" Hall Brewing "," http://www.hallbrewingco.com/ "," 720-638-3034 "," 10970 South Parker Rd. Parker Colorado ", 39.5173255 , -104.76324039999997 ),
				122 => array (" Hideaway Park Brewery "," http://hideawayparkbrewery.com/ "," 970 363-7312 "," 78927 US Highway 40 Winter Park Colorado ", 39.918024 , -105.7840122 ),
				123 => array (" High Hops Brewery "," http://www.highhopsbrewery.com/ "," 970-674-2841 "," 6461 State Highway 392 Windsor Colorado ", 40.4806871 , -104.9356128 ),
				124 => array (" Hogshead Brewery "," http://www.hogsheadbrewery.com/ "," 303-495-3105 "," 4460 W. 29th Ave. Denver Colorado ", 39.7582039 , -105.04504309999999 ),
				125 => array (" Holidaily Brewing Company "," http://www.holidailybrewing.com/ "," (303) 278-(2337) "," 801 Brickyard Cir. Golden Colorado ", 39.7799911 , -105.23313050000002 ),
				126 => array (" Horse & Dragon Brewing Company "," http://www.horseanddragonbrewing.com/ "," 970-631-8038 "," 124 Racquette Drive Fort Collins Colorado ", 40.5896804 , -105.04559699999999 ),
				127 => array (" Horsefly Brewing Company "," http://horseflybrewing.com/wp/ "," 970-249-6889 "," 846 East Main Street Montrose Colorado ", 38.4827516 , -107.87136659999999 ),
				128 => array (" Industrial Revolution Brewing "," http://www.industrialrevolutionbrewingcompany.com/ "," 303-828-1200 "," 285 Cheeseman Street Erie Colorado ", 40.0518743 , -105.04860530000002 ),
				129 => array (" Iron Bird Brewing  "," http://www.ironbirdbrewing.com/ "," 719-424-7002 "," 402 S Nevada Ave Colorado Springs Colorado ", 38.8280157 , -104.82262809999997 ),
				130 => array (" Ironworks Brewery & Pub "," http://www.ironworkspub.com/ "," (303) 985-5818 "," 12354 W Alameda Pkwy Lakewood Colorado ", 39.7036071 , -105.1367851 ),
				131 => array (" J Wells Brewery "," http://jwellsbrewery.com/ "," 303-396-0384 "," 2516 49TH ST #5 BOULDER  Colorado ", 40.0237869 , -105.23779619999999 ),
				132 => array (" Jak's Brewing Company "," http://www.jaksbrewing.com/ ","  719-375-1116 "," 7654 McLaughlin Rd Peyton Colorado ", 38.9421001 , -104.60435849999999 ),
				133 => array (" Joyride Brewing Company "," http://joyridebrewing.com/ "," (720) 432-7560 "," 2501 Sheridan Blvd Edgewater Colorado ", 39.7532077 , -105.053674 ),
				134 => array (" Judge Baldwin's Brewery "," https://www.facebook.com/judgebaldwins/about/?entry_point=page_nav_about_item "," 719-955-6291 "," 4 S Cascade Ave Colorado Springs Colorado ", 38.833327 , -104.82667300000003 ),
				135 => array (" J. Fargo's Restaurant & Microbrewery "," http://jfargos.com/ "," 970-564-0242 "," 1209 East Main Street Cortez Colorado ", 37.3484639 , -108.57029 ),
				136 => array (" Kannah Creek Brewing Company "," http://www.kannahcreekbrewingco.com/ "," 970.263.0111 ","  1960 North 12th Street Grand Junction Colorado ", 39.0853392 , -108.55190540000001 ),
				137 => array (" Kokopelli Brewing Company "," http://kokopellibeerco.com/ "," 303-284-0135 "," 8931 Harlan St Westminster Colorado ", 39.858327 , -105.0639607 ),
				138 => array (" Left Hand Brewing Company - Longmont, CO "," http://lefthandbrewing.com/ "," 303-772-0258 "," 1265 Boston Ave Longmont Colorado ", 40.1584072 , -105.11538109999998 ),
				139 => array (" Living The Dream Brewing "," http://livingthedreambrewing.com/ "," 303-284-9585 "," 12305 Dumont Way Unit A  Littleton Colorado ", 39.5403122 , -105.03995250000003 ),
				140 => array (" Liquid Mechanics Brewing "," http://www.liquidmechanicsbrewing.com/ "," 720-550-7813 "," 297 U.S. 287  Lafayette Colorado ", 39.99934 , -105.10355730000003 ),
				141 => array (" Locavore Beer Works "," http://www.locavorebeerworks.com/ "," 720-476-4419 "," 5950 S Platte Canyon Rd Littleton Colorado ", 39.60865709999999 , -105.0370537 ),
				142 => array (" Lost Highway Brewery  "," http://www.losthighwaybrewing.com/ "," (720) 440-9447 "," 520 E. Colfax Ave. Denver Colorado ", 39.7398299 , -104.98040040000001 ),
				143 => array (" Loveland Aleworks  "," http://www.lovelandaleworks.com/home "," 970-619-8726 "," 18 W 4th St, Loveland Colorado ", 40.3954769 , -105.07662749999997 ),
				144 => array (" Lowdown Brewery  "," http://www.lowdownbrewery.com/ "," (720) 524-8065 "," 800 Lincoln Street Denver Colorado ", 39.7293107 , -104.98575289999997 ),
				145 => array (" Lumpy Ridge Brewing "," http://www.lumpyridgebrewing.com/#lumpy-ridge-brewery "," 812.201.3836 "," 531 S St Vrain Ave Estes Park Colorado ", 40.3690055 , -105.50468820000003 ),
				146 => array (" Mad Jack's Mountain Brewery "," http://www.madjacksmountainbrewery.com/ "," (303)816-2337    "," 23 Main St Bailey Colorado ", 39.406618 , -105.477033 ),
				147 => array (" Mahogany Ridge Brewery "," http://www.mahoganyridgesteamboat.com/ "," 970-879-3773 "," 435 Lincoln Ave Steamboat Springs Colorado ", 40.4837578 , -106.83133550000002 ),
				148 => array (" Main Street Brewery & Restaurant - Cortez, CO "," http://www.mainstreetbrewerycortez.com/ ","  970-564-9112   "," 21 E Main St Cortez Colorado ", 37.348312 , -108.5845731 ),
				149 => array (" Mancos Brewing Company "," http://www.mancosbrewingcompany.com/ "," 970-533-9761 "," 550 W. Railroad Ave. Mancos Colorado ", 37.3470248 , -108.29529969999999 ),
				150 => array (" Manitou Brewing Company "," http://www.manitou-brewing.com/ "," (719) 282-7709 "," 725 Manitou Ave Manitou Springs Colorado ", 38.8571353 , -104.9156724 ),
				151 => array (" Meadary of the Rockies "," https://www.talonwinebrands.com/About-Us/Our-Wineries/Meadery-of-the-Rockies "," (970) 464-9288 "," 785 Elberta Avenue Palisade Colorado ", 39.1165663 , -108.3606949 ),
				152 => array (" Mockery Brewing "," http://www.mockerybrewing.com/ "," (303) 953-2058 "," 3501 Delgany Street Denver Colorado ", 39.7712346 , -104.97972579999998 ),
				153 => array (" Moffat Station Brewery "," https://www.facebook.com/pages/Moffat-Station-Brewery/124449017640900 "," 970-726-6331 "," 81699 US-40 Winter Park Colorado ", 39.885617 , -105.75913780000002 ),
				154 => array (" Moonlight Pizza & Brewery "," http://www.moonlightpizza.biz/press/ "," (719)539-4277 "," 242 F St. Salida Colorado ", 38.5349421 , -105.99359079999999 ),
				155 => array (" Mountain Toad Brewing "," http://mountaintoadbrewing.com/ "," 720-638-3244 "," 900 WASHINGTON AVENUE Golden Colorado ", 39.7580659 , -105.2242402 ),
				156 => array (" Mountain Sun Pub & Brewery "," http://www.mountainsunpub.com/ "," 303.546.0886 "," 1535 Pearl Street Boulder Colorado ", 40.0191506 , -105.27529149999998 ),
				157 => array (" Mu Brewery "," http://www.brewbymu.com/home.html "," 720-446-8273 "," 9735 E. Colfax Ave. Aurora Colorado ", 39.7404262 , -104.8746428 ),
				158 => array (" Nano 108 Brewing "," http://nano108brewing.com/ "," 719-596-2337 "," 2402 Waynoka Road Colorado Springs Colorado ", 38.8666517 , -104.7178298 ),
				159 => array (" New Belgium Brewing Company "," http://www.newbelgium.com/home "," 970-221-0524 "," 500 Linden Street Fort Collins Colorado ", 40.593415 , -105.06687399999998 ),
				160 => array (" New Planet Beer "," http://www.newplanetbeer.com/ "," 303-499-4978 "," 3980 Broadway Ste. 103 – PMB 116 Boulder Colorado ", 40.0149856 , -105.27054559999999 ),
				161 => array (" Nighthawk Brewery "," http://www.nighthawkbrewery.com/ "," 720 262 3900  "," 2780 Industrial Lane Broomfield Colorado ", 39.9207914 , -105.1012776 ),
				162 => array (" Oasis Brewing Company "," http://www.oasisbeer.com/ "," 303-912-7317 "," 620 E 6TH AVE Denver Colorado ", 39.725535 , -104.9792486 ),
				163 => array (" Odd 13 Brewing "," http://www.odd13brewing.com/content/main.html "," 303-997-4164 "," 301 E Simpson St, Lafayette Colorado ", 39.9983252 , -105.0878535 ),
				164 => array (" Odell Brewing Company "," https://www.odellbrewing.com/ "," (970) 498-9070 "," 800 East Lincoln Ave Fort Collins Colorado ", 40.5894674 , -105.06318190000002 ),
				165 => array (" Odyssey Beerwerks "," http://www.odysseybeerwerks.com/ "," 303-421-0772 "," 5535 West 56th Avenue #107  Arvada Colorado ", 39.8008448 , -105.05898250000001 ),
				166 => array (" Old Colorado Brewing Company "," http://oldcoloradobrewing.com/ "," 970-217-2129 "," 8121 First Street PO Box 132 Wellington Colorado ", 40.70387119999999 , -105.0085856 ),
				167 => array (" Old Mine Cidery & Brewpub "," http://www.theoldmine.com/ "," 303-905-0620 "," 500 Briggs St Erie Colorado ", 40.0491019 , -105.04768209999997 ),
				168 => array (" Ouray Brewery "," http://ouraybrewery.com/ "," 970-325-7388 "," 607 Main Street Ouray Colorado ", 38.0229401 , -107.67112420000001 ),
				169 => array (" Ourayle House Brewery "," https://www.facebook.com/Ourayle-House-Brewery-Mr-Grumpy-Pants-349885024771/about/?entry_point=page_nav_about_item "," 970-903-1824 "," 215 7th Ave Ouray Colorado ", 38.0235916 , -107.6724322 ),
				170 => array (" Our Mutual Friend Malt & Brew "," http://www.omfbeer.com/ ","  "," 2810 Larimer St Denver Colorado ", 39.76048189999999 , -104.98241589999998 ),
				171 => array (" Oskar Blues Brewery "," https://www.oskarblues.com/ "," 303-776-1914 "," 1800 Pike Rd Longmont Colorado ", 40.1389968 , -105.1227417 ),
				172 => array (" Pagosa Brewing Company "," http://pagosabrewing.com/ "," 970-731-2739 "," 118 N. Pagosa Blvd. Pagosa Springs Colorado ", 37.2552227 , -107.08031940000001 ),
				173 => array (" Palisade Brewing Company "," http://www.palisadebrewingcompany.com/ "," (970) 464-1462 "," 200 Peach Ave. Palisade Colorado ", 39.111116 , -108.35429899999997 ),
				174 => array (" Pateros Creek Brewing Company "," http://www.pateroscreekbrewing.com/ "," 970-484-7222 "," 242 N. College Ave. Fort Collins Colorado ", 40.5899876 , -105.07641839999997 ),
				175 => array (" Paradox Beer Company "," http://paradoxbeercompany.com/main.htm "," (719) 686-8081 "," 10 Buffalo Court Woodland Park Colorado ", 38.943996 , -105.15519899999998 ),
				176 => array (" Peaks N Pines Brewery "," http://www.peaksnpinesbrewery.com/ "," 719-358-6758 "," 4005 Tutt Blvd Colorado Springs Colorado ", 38.88987400000001 , -104.71388999999999 ),
				177 => array (" Phantom Canyon Brewery "," http://www.phantomcanyon.com/ "," (719) 635-2800  "," 2 East Pikes Peak Ave. Colorado Springs Colorado ", 38.834232 , -104.82494759999997 ),
				178 => array (" Pikes Peak Brewing Company "," http://www.pikespeakbrewing.com/ "," 719.208.4098 "," 1756 Lake Woodmoor Dr Monument Colorado ", 39.0950765 , -104.858993 ),
				179 => array (" Pints Pub "," http://www.pintspub.com/ "," 303-534-7543 "," 221 West 13th Avenue Denver Colorado ", 39.7369494 , -104.99079649999999 ),
				180 => array (" Platt Park Brewing "," http://www.plattparkbrewing.com/ "," 303.993.4002 "," 1875 S Pearl St Denver Colorado ", 39.6825659 , -104.9807821 ),
				181 => array (" Post Brewing Company "," http://www.postbrewing.com/ "," 303-593-2066 "," 105 West Emma St. Lafayette Colorado ", 39.9945627 , -105.0913428 ),
				182 => array (" Powder Keg Brewing Company & Niwot, CO "," http://www.powderkegbrewingcompany.com/ "," (720) 600-5442 "," 101 2nd Avenue Niwot Colorado ", 40.1032097 , -105.17289019999998 ),
				183 => array (" Prost Brewing Company "," https://prostbrewing.com/ "," 303.729.1175 "," 2540 19th Street Denver Colorado ", 39.7602704 , -105.00398259999997 ),
				184 => array (" Pug Ryans Brewery "," http://pugryans.com/start.php "," (970) 468-2145 "," 104 Village Place PO Box 2515 Dillon Colorado ", 39.6302643 , -106.04335179999998 ),
				185 => array (" Pumphouse Brewery "," http://pumphousebrewery.com/ "," (303) 702-0881 "," 540 Main Street Longmont Colorado ", 40.1686074 , -105.1021073 ),
				186 => array (" Ratio Beerworks "," http://ratiobeerworks.com/ "," 303-997-8288 "," 2920 Larimer St Denver Colorado ", 39.7615072 , -104.98107329999999 ),
				187 => array (" Red Leg Brewing Company "," http://www.redlegbrewing.com/ "," 719-598-3776 "," 4630 Forge Rd, Ste B Colorado Springs Colorado ", 38.898059 , -104.84173129999999 ),
				188 => array (" Renegade Brewing Company "," http://renegadebrewing.com/ "," 720-401-4089 "," 925 W 9TH AVE Denver Colorado ", 39.730734 , -104.99917429999999 ),
				189 => array (" Revolution Brewing "," http://www.revolution-brewing.com/ "," 970-260-4869 "," 325 Grand Ave Paonia Colorado ", 38.8695152 , -107.59750739999998 ),
				190 => array (" Riff Raff Brewing Company "," http://riffraffbrewing.com/ "," 970-264-4677 "," 274 Pagosa St Pagosa Springs Colorado ", 37.2689076 , -107.00734829999999 ),
				191 => array (" River North Brewery "," http://www.rivernorthbrewery.com/home "," (303) 296-2617 "," 6021 Washington St Unit A Denver Colorado ", 39.8065586 , -104.9790984 ),
				192 => array (" Roaring Fork Beer Company "," http://roaringforkbeerco.com/ "," 970-963-5870 "," 1941 Dolores Way Carbondale Colorado ", 39.41061699999999 , -107.22364500000003 ),
				193 => array (" Rockslide Restaurant & Brewery "," http://www.rockslidebrewpub.com/ "," 970.245.2111 "," 401 Main Street Grand Junction Colorado ", 39.06700250000001 , -108.56593379999998 ),
				194 => array (" Rock Bottom Brewery "," http://www.rockbottom.com/ "," 719-550-3586 "," 3316 Cinema Point Dr. Colorado Springs Colorado ", 38.88116240000001 , -104.71817440000001 ),
				195 => array (" Rock Bottom Brewery "," http://www.rockbottom.com/ "," 303-534-7616 "," 1001 16th St.Unit A-100 Denver Colorado ", 39.7480208 , -104.99465939999999 ),
				196 => array (" Rock Bottom Brewery "," http://www.rockbottom.com/ "," 303-342-6667 "," 9100 N. Pena Blvd.Concourse C Denver Colorado ", 39.8590731 , -104.67353159999999 ),
				197 => array (" Rock Bottom Brewery "," http://www.rockbottom.com/ "," 970-622-2077 "," 6025 Sky Pond Dr. Loveland Colorado ", 40.4181465 , -104.99039419999997 ),
				198 => array (" Rock Bottom Brewery "," http://www.rockbottom.com/ "," 303-792-9090 "," 9627 E. County Line Rd. Centennial Colorado ", 39.5662584 , -104.87368349999997 ),
				199 => array (" Rock Bottom Brewery "," http://www.rockbottom.com/ "," 303-255-1625 "," 14694 Orchard Pkwy. Suite 400 Westminster Colorado ", 39.9626423 , -104.9933193 ),
				200 => array (" Rock Bottom Brewery "," http://www.rockbottom.com/ "," 720-566-0198 "," 10633 Westminster Blvd.Suite 900 Westminster Colorado ", 39.889337 , -105.0693412 ),
				201 => array (" Rocky Mountain Brewery "," http://www.rockymountainbrews.com/ "," (719)528-1651 "," 625 Paonia Street Colorado Springs Colorado ", 38.8412557 , -104.71643770000003 ),
				202 => array (" Rockyard Brewing Company "," http://www.rockyard.com/ "," 303-814-9273 "," 880 Castleton Rd Castle Rock Colorado ", 39.4091075 , -104.8698478 ),
				203 => array (" Royal Gorge Brewing "," http://www.royalgorgebrewpub.com/ "," (719) 345-4141 "," 413 Main St, Canon City Colorado ", 38.4404908 , -105.24161349999997 ),
				204 => array (" Saint Patrick's Brewing Company "," http://saintpatricksbrewing.com/ "," 720-420-9112 "," 2842 W Bowles Ave Littleton Colorado ", 39.6130462 , -105.0212067 ),
				205 => array (" San Luis Valley Brewing "," http://www.slvbrewco.com/ "," (719) 587-2337 "," 631 Main Street Alamosa Colorado ", 37.4683105 , -105.86685139999997 ),
				206 => array (" Sanitas Brewing Company "," http://www.sanitasbrewing.com/ "," 303.442.4130 "," 3550 Frontier Ave. Unit A Boulder Colorado ", 40.02208 , -105.2480486 ),
				207 => array (" Seedstock Brewing Company "," http://www.seedstockbrewery.com/ "," 720-476-7831 "," 3610 W. Colfax Ave. Denver Colorado ", 39.740083 , -105.03511100000003 ),
				208 => array (" Shamrock Brewing "," https://shamrockbrewing.com/ "," (719) 542-9974 "," 108 West 3rd Street Pueblo Colorado ", 38.2695003 , -104.60775920000003 ),
				209 => array (" Shoes and Brews "," http://www.shoesbrews.com/ "," (720) 340-4290 "," 63 S Pratt Pkwy  Longmont Colorado ", 40.1580601 , -105.10741189999999 ),
				210 => array (" SKA Brewing Company "," http://skabrewing.com/main.html "," 970-247-5792 "," 225 Girard St Durango Colorado ", 37.2387785 , -107.87617469999998 ),
				211 => array (" Skeye Brewing "," http://www.skeyebrewing.com/ "," 303-774-7698 "," 900 S Hover St, Ste D Longmont Colorado ", 40.1517883 , -105.1301894 ),
				212 => array (" Smiling Toad Brewery "," http://smilingtoadbrewery.com/ "," (719) 418-2936 "," 1757 South 8th Street Colorado Springs Colorado ", 38.80656159999999 , -104.83853540000001 ),
				213 => array (" Smuggler's Brewpub "," http://www.smugglersbrewpub.com/ "," 970-728-5620 "," 225 S Pine St Telluride Colorado ", 37.935736 , -107.81165900000002 ),
				214 => array (" Snowbank Brewing "," http://www.snowbank.beer/ "," 970-999-5658 "," 225 N Lemay Ave, Ste 1 Fort Collins Colorado ", 40.58993359999999 , -105.05840949999998 ),
				215 => array (" Southpark Brewing Company "," http://www.southparkbrewingcolorado.com/ "," 719-836-1932 "," 297 1/2 US HIGHWAY 285 Fairplay Colorado ", 39.2203091 , -105.9926097 ),
				216 => array (" Station 26 Brewing "," http://www.station26brewing.co/#about "," 303 333 1825 "," 7045 East 38th Ave Denver Colorado ", 39.7695837 , -104.90597969999999 ),
				217 => array (" Steamworks Brewing Company "," http://steamworksbrewing.com/ "," 970-259-9200 "," 801 East Second Avenu Durango Colorado ", 37.2723125 , -107.87997719999998 ),
				218 => array (" Storm Peak Brewing Company "," http://www.stormpeakbrewing.com/ "," 970-879-1999 "," 1744 Lincoln Ave, Unit 3 Steamboat Springs Colorado ", 40.4971608 , -106.85302590000003 ),
				219 => array (" Storybook Brewing "," http://www.storybookbrewing.com/ "," 719.633.6266 "," 3121 A North El Paso Street Colorado Springs Colorado ", 38.877989 , -104.81268869999997 ),
				220 => array (" Strange Brewing Company "," http://strangecraft.com/ "," (720) 985-2337 "," 1330 Zuni Street, Unit M Denver Colorado ", 39.7373529 , -105.01523859999998 ),
				221 => array (" Suds Brothers Brewing "," http://www.sudsbrothers2fruita.com/ "," 970-858-9400 "," 127 E Aspen Ave Fruita Colorado ", 39.1591642 , -108.73267809999999 ),
				222 => array (" Telluride Brewing Company "," http://www.telluridebrewingco.com/ "," (970) 728-5094 "," 156 Society Drive Telluride Colorado ", 37.946497 , -107.877252 ),
				223 => array (" The Baker's Brewery "," http://thebakersbrewery.com/ "," 970-468-0170 "," 531 Silverthorne Ln Silverthorne Colorado ", 39.6299416 , -106.06732399999999 ),
				224 => array (" The Brew On Broadway "," http://thebrewonbroadway.com/ "," 303-781-5665 "," 3445 S. Broadway Englewood Colorado ", 39.6541145 , -104.98794729999997 ),
				225 => array (" The Eldo Brewery "," http://www.eldobrewpub.com/ "," 970 - 349 - 6125 "," 215 Elk Avenue Crested Butte Colorado ", 38.8697772 , -106.98706349999998 ),
				226 => array (" The Library Sports Grill & Brewery "," http://laramie.librarysportsgrille.com/?src=wp "," 719-552-3469 "," 78491 US-40 Winter Park Colorado ", 39.9239215 , -105.78632579999999 ),
				227 => array (" Three Barrel Brewing Company "," http://www.threebarrelbrew.com/ "," (​719) 657-0681 "," 475 Grand Ave Del Norte Colorado ", 37.678688 , -106.35647 ),
				228 => array (" Tivoli Beer  "," http://tivolibrewingco.com/ "," 720-458-5885 "," 900 Auraria Parkway Suite 240  Denver Colorado ", 39.7452383 , -105.0057946 ),
				229 => array (" Tommyknocker Brewery & Pub  "," http://www.tommyknocker.com/ "," (303) 567-4419 "," P.O. Box 3188 1401 Miner Street  Idaho Springs Colorado ", 39.7416177 , -105.51766459999999 ),
				230 => array (" Trinity Brewing Company  "," http://www.trinitybrew.com/ "," 719-634-0029 "," 1466 Garden of the Gods Road Colorado Springs Colorado ", 38.89745 , -104.85473350000001 ),
				231 => array (" Triple S Brewing Company  "," http://triplesbrewing.com/ "," 719-344-5477 "," 318 E Colorado Ave Colorado Springs Colorado ", 38.8326059 , -104.8189276 ),
				232 => array (" TRVE Brewing Company  "," http://trvebrewing.com/main "," 303-351-1021 "," 227 Broadway #101 Denver Colorado ", 39.719947 , -104.9879957 ),
				233 => array (" Twisted Pine Brewing Company - Boulder, CO "," http://twistedpinebrewing.com/ "," 303-786-9270 "," 3201 Walnut St Boulder Colorado ", 40.0207662 , -105.2510729 ),
				234 => array (" Two22 Brew "," http://www.two22brew.com/ "," 720-328-9038 "," 4550 S Reservoir Rd,  Aurora Colorado ", 39.6352322 , -104.75913660000003 ),
				235 => array (" Two Mile High Brewing Company  "," http://twomilebrewing.com/ "," 620 2739489 "," PO Box 1509 Leadville Colorado ", 39.2508229 , -106.29252380000003 ),
				236 => array (" Two Rascals Brewing  "," http://www.tworascalsbrewing.com/ "," 970-249-8689 "," 147 N 1st St Montrose Colorado ", 38.4788228 , -107.88001079999998 ),
				237 => array (" Upslope Brewing Company  "," http://upslopebrewing.com/ "," 303-396-1898 "," 1898 S Flatiron Ct Boulder Colorado ", 40.0201617 , -105.2181711 ),
				238 => array (" Ursula Brewery  "," http://ursulabrewery.com/ "," 720-324-8529 "," 2101 North Ursula Street Aurora Colorado ", 39.74868900000001 , -104.83838800000001 ),
				239 => array (" Ute Pass Brewing "," https://www.utepassbrewingcompany.com/ "," 719-686-8722 "," 209 E Midland Ave Woodland Park Colorado ", 38.9944722 , -105.05135530000001 ),
				240 => array (" Vail Brewing Company "," http://www.vailbrewingco.com/ "," 970-470-4351 ","Vail, Colorado 81657", 32.5465631 , -91.9888517 ),
				241 => array (" Verboten Brewing "," http://www.verbotenbrewing.com/verboten_new/templates/main.html "," 970-775-7371 "," 127 E 5th St Loveland Colorado ", 40.396818 , -105.07511499999998 ),
				242 => array (" Very Nice Brewing Company "," http://www.verynicebrewing.com/ "," 303-258-3770 "," 20 Lakeview Dr Suite 112 Nederland Colorado ", 39.9598444 , -105.50945790000003 ),
				243 => array (" Walnut Brewery "," http://www.walnutbrewery.com/ "," 303-447-1345 "," 1123 Walnut St, Boulder Colorado ", 40.0169565 , -105.2805007 ),
				244 => array (" Walter Brewing Company "," http://www.waltersbeer.com/ "," (719) 542-0766 "," 126 Oneida St,  Pueblo Colorado ", 38.2625102 , -104.61003069999998 ),
				245 => array (" WeldWerks Brewing  "," http://www.weldwerksbrewing.com/ "," 970-460-6345 "," 508 8th Ave Greeley Colorado ", 40.427821 , -104.68996900000002 ),
				246 => array (" Westbound & Down Brewing Company "," http://www.westboundanddown.com/ "," 720-502-3121 "," 1617 Miner St Idaho Springs Colorado ", 39.74177299999999 , -105.51549 ),
				247 => array (" WestFax Brewing Company  "," http://www.westfaxbrewingcompany.com/ "," 570-972-0920 "," 6733 W Colfax Ave Lakewood Colorado ", 39.7404587 , -105.07096869999998 ),
				248 => array (" West Flanders Brewing Company "," http://wfbrews.com/ "," 303-447-2739 "," 1125 Pearl St Boulder Colorado ", 40.0180463 , -105.28071060000002 ),
				249 => array (" Westminster Brewing Company  "," http://westminsterbrewingco.com/ "," 303-284-1864 "," 7655 W 108th Ave #600 Broomfield Colorado ", 39.8926103 , -105.08201250000002 ),
				250 => array (" Wild Woods Brewery "," http://www.wildwoodsbrewery.com/ "," 303-484-1465 "," 5460 Conestoga Ct Boulder Colorado ", 40.0163007 , -105.2266636 ),
				251 => array (" Wiley Roots Brewing  "," http://www.wileyrootsbrewing.com/Wiley_Roots_Brewing/Home.html "," 970-515-7315 "," 625 3rd St D Greeley Colorado ", 40.4315726 , -104.6883196 ),
				252 => array (" Wit's End Brewing Company "," http://www.witsendbrewing.com/Home.html "," 303-459-4379 "," 2505 W 2nd Ave #13 Denver Colorado ", 39.7207422 , -105.01744229999997 ),
				253 => array (" Wonderland Brewing "," http://wonderlandbrewing.com/ "," 303-953-0400 "," 5450 W 120th Ave Broomfield Colorado ", 39.91332240000001 , -105.05585659999997 ),
				254 => array (" Wynkoop Brewing Company "," http://www.wynkoop.com/ "," 303-297-2700 "," 1634 18th St Denver Colorado ", 39.7533834 , -104.99853389999998 ),
				255 => array (" Yak & Yeti Restaurant & Brewpub "," http://www.theyakandyeti.com/ "," 303-431-9000 "," 7803 Ralston Rd Arvada Colorado ", 39.8022809 , -105.0841393 ),
				256 => array (" Yampa Valley Brewing Company  "," http://www.yampavalleybrew.com/yampavalleybrew "," 970-276-8014 "," 106 E Jefferson Ave Unit B Hayden Colorado ", 40.495734 , -107.25748900000002 )
			);
			foreach ($breweries_array as $brewery) {
				$wpdb->query( $wpdb->prepare( 
					"
						INSERT INTO " . $wpdb->prefix . "breweries_data
						( id, title, url, phone, address, latitude, longitude )
						VALUES ( %d, %s, %s, %s, %s, %f, %f )
					", 
					'', 
					$brewery[0], 
					$brewery[1],
					$brewery[2], 
					$brewery[3], 
					$brewery[4], 
					$brewery[5]
				) );
			}
		
		} 
	}
}


/** Step 2 (from text above). */
add_action( 'admin_menu', 'bnb_plugin_menu' );

/** Step 1. */
function bnb_plugin_menu() {
	add_menu_page(
		'Breweries and Brunches',
		'Breweries and Brunches',
		'manage_options',
		'breweries_and_brunches',
		'bnb_plugin_settings_page'
	);
}

/**
* Plugin settings page
*/
function bnb_plugin_settings_page() { ?>
	<div class="wrap">
		<h2>Breweries Data</h2>

		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">
					<div class="meta-box-sortables ui-sortable"> 
					</div>
				</div>
			</div>
			<br class="clear">
		</div>
	</div>
<?php }	

/* Add shortcode for pasting to the shortpage */
add_shortcode('breweries_and_brunches', 'add_breweries_and_brunches');

function add_breweries_and_brunches() { ?>
	

	<div class="container">
		<div class="row">
			<div class="form-group col-md-6" style="margin-top: 30px;">
				<label for="select_address">Please choose the way you want to enter the address:</label>
				<select class="form-control" id="select_address">
					<option disabled selected></option>
					<option value="current">My current coordinates</option>
					<option value="enter">Enter the address manually</option>
				</select>
			</div>
			<div class="col-md-6">
			</div>
		</div> <!-- .row -->
		
		<div class="row">
			<div class="form-group col-md-6">
				<div id="current_coordinates"> 
					<br>
					<button class="search_button btn btn-primary">Search</button>
				
				</div>	
				<div id="enter_coordinates">			
					<label for="address_box">Please enter your address:</label>
					<input type="text" class="form-control" id="address_box">
					<br>
					<button class="search_button btn btn-primary">Search</button>
				</div>
				<div id="loading">
					<img src="<?php echo plugins_url() . '/breweries-and-brunches/img/loading.gif'; ?>" />
				</div>
			</div>
			<div class="col-md-6">
			</div>
		</div> <!-- .row -->
		
		<div class="row" style="display:block;">
			<div class="col-md-6">
				<div id="breweries_results">
					<h4 style="margin:0px;">Closest breweries are:</h4>
					(drag and drop blocks to sort them and get map route)
					<br><br>		
					<ul id="sortable" style="width: 100%;">
					</ul>
					<button id="show_map" class="btn btn-success">Show the map</button>
				</div>
			</div>
			<div id="reviews" class="col-md-6">
			</div>
		</div> <!-- .row -->
		
		<div class="row" style="display:block;">
			<div class="col-md-6">
				<div id="map">
				</div>
			</div>
			<div class="col-md-6">
				<div id="driving_directions">
					<h5>Driving directions:</h5>
					<div class="content">
					</div>
				</div>
			</div>
		</div> <!-- .row -->
		
	</div> <!-- .container -->
	
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAMqtGpk7KLlbAHLF0Ov82_--RAErNdPFU&signed_in=true"></script>
<?php }
