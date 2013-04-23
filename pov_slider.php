<?php
/*
Plugin Name: POV Sliders
Plugin URI: https://github.com/theREDspace/pov_slider
Description: A collection of sliders for featuring content on WordPress pages
Version: 1.0.1
Author: Luke DeWitt
Author URI: http://www.whatadewitt.ca
Author Email: luke.dewitt@theredspace.com
License:

  The MIT License (MIT)

Copyright (c) <year> <copyright holders>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

// TODO: rename this class to a proper name for your plugin
class POVSlider {

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'plugin_textdomain' ) );

		// Register admin styles and scripts
		add_action( 'admin_print_styles-appearance_page_pov_slider_featured_homepage', array( $this, 'register_admin_styles' ) );
		add_action( 'admin_print_scripts-appearance_page_pov_slider_featured_homepage', array( $this, 'register_admin_scripts' ) );	

	    add_action('admin_menu', array( $this, 'pov_slider_register_homepage_slider_page' ) );
	    add_action('wp_ajax_pov_slider_homepage_slider_search', array( $this, 'pov_slider_homepage_slider_search' ) );
	} // end constructor

	/**
	 * Loads the plugin text domain for translation
	 */
	public function plugin_textdomain() {
		$domain = 'pov-slider';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
        load_textdomain( $domain, WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo' );
        load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

	} // end plugin_textdomain

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {
		wp_enqueue_style( 'pov-slider-admin-styles', plugins_url( 'pov_slider/css/admin.css' ) );
	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	public function register_admin_scripts() {
		wp_enqueue_script( 'pov-slider-admin-script', plugins_url( 'pov_slider/js/admin.js' ), array('jquery', 'jquery-ui-sortable') );
	} // end register_admin_scripts


	/*--------------------------------------------*
	 * Core Functions
	 *---------------------------------------------*/

	function pov_slider_register_homepage_slider_page() {
		require_once('views/homepage_slider.php');
		add_submenu_page( 'themes.php', 'Homepage Slider', 'Homepage Slider', 'manage_options', 'pov_slider_featured_homepage', 'pov_slider_homepage_slider_page' ); 
	}

	function pov_slider_homepage_slider_search() {

		$args = array ( 
			'posts_per_page' => -1,
			'post_type' => 'any',
			'post_status' => 'publish',
			's' => $_POST['s'] 
		);
		
		$posts_query = new WP_Query($args);
		$return_data = array();	
		
		while ( $posts_query->have_posts() ) : $posts_query->the_post();
			$p = get_post_type_object(get_post_type())->labels->singular_name;
			array_push($return_data, array( "id" => get_the_ID(), "title" => get_the_title(), "type" => $p ));
		endwhile;
		
		wp_reset_postdata();
		wp_reset_query();
		
		echo json_encode($return_data);
		die();
	}

} // end class

$pov_slider = new POVSlider();

function pov_slider_get_featured_posts() {
	return get_option('pov_slider_featured_posts');
}
