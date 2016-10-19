<?php

/**
* Plugin Name: Breweries and Brunches
* Plugin URI: https://www.linkedin.com/company/pahoda-image-products
* Description: Get breweries and brunches that are nearest to you
* Version: 1.0
* Author: Pahoda Image Products
* Author URI: https://www.linkedin.com/company/pahoda-image-products
**/

wp_enqueue_script( 'jquery_ui_js', plugins_url() . '/breweries-and-brunches/js/jquery_ui.js', array('jquery'), null );
wp_enqueue_script( 'angular', plugins_url() . '/breweries-and-brunches/js/angular.min.js', array());
wp_enqueue_script( 'jquery_slimscroll', plugins_url() . '/breweries-and-brunches/js/jquery.slimscroll.min.js', array('jquery'), null );
wp_enqueue_script( 'jquery_validate', plugins_url() . '/breweries-and-brunches/js/jquery-validate.min.js', array('jquery'), null );
wp_enqueue_script( 'touch_punch', plugins_url() . '/breweries-and-brunches/js/jquery.ui.touch-punch.min.js', array('jquery'), null );
wp_enqueue_script( 'magnific_popup_js', plugins_url() . '/breweries-and-brunches/js/jquery.magnific-popup.min.js', array('jquery'), null );

wp_register_script( 'bnb_script', plugins_url() . '/breweries-and-brunches/js/bnb_script.js', array('jquery', 'angular'), null );
$script_vars = array(
	'list_businesses_by_distance' => plugins_url() . '/breweries-and-brunches/bnb_coordinates.php',
	'list_businesses' => plugins_url() . '/breweries-and-brunches/bnb_list_businesses.php',
	'yelp_reviews' => plugins_url() . '/breweries-and-brunches/bnb_yelp_reviews.php',
	'send_email' => plugins_url() . '/breweries-and-brunches/bnb_send_email.php',	
	'plugins_url' => plugins_url()
	);
wp_localize_script( 'bnb_script', 'script_vars', $script_vars);
wp_enqueue_script( 'bnb_script' );

wp_enqueue_style( 'bootstrap', plugins_url() .  '/breweries-and-brunches/css/bootstrap.css', array(), null );
wp_enqueue_style( 'jquery_ui_css', plugins_url() .  '/breweries-and-brunches/css/jquery_ui.css', array(), null );
wp_enqueue_style( 'font_awesome', plugins_url() .  '/breweries-and-brunches/css/font-awesome.min.css', array(), null );
wp_enqueue_style( 'magnific_popup_css', plugins_url() .  '/breweries-and-brunches/css/magnific-popup.css', array(), null );
wp_enqueue_style( 'bnb_style', plugins_url() .  '/breweries-and-brunches/css/bnb_style.css', array(), null );



/* Check bnb_create_tables.php for details */
require_once('bnb_create_tables.php');

	/* Insert breweries and brunches tables and data in the database */
	add_action('init', 'check_if_is_admin');	

/* Check bnb_html_pages.php for details */
require_once('bnb_html_pages.php');
	
	/* Add shortcode for pasting to the frontpage */
	add_shortcode('breweries_and_brunches', 'add_breweries_and_brunches');
	function bnb_plugin_menu() {
		add_menu_page(
			'Breweries and Brunches',
			'Breweries and Brunches',
			'manage_options',
			'breweries_and_brunches',
			'bnb_plugin_settings_page'
		);
	}
