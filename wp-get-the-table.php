<?php
/*
Plugin Name: WP Get The Table
Plugin URI: http://www.itjon.com/getthetable
Description: A plugin that lets you grab an HTML table from a URL by ID, and echo it out onto a page via shortcode. [getthetable url="" id=""]
Version: 1.0
Author: Jonathan Sisk
Author URI: http://www.itjon.com
License: GPL2
*/

function jns_get_the_table_init() {

	function jns_get_the_table($atts) {

		$ch = curl_init($atts['url']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$page = curl_exec($ch);

		$match = '#<table[^>]*id="' . $atts['id'] . '"[^>]*>.+?</table>#is';

		preg_match($match, $page, $table);

		if($table != '') {
			return $table[0];
		} else {
			return 'Sorry, we could not find a table with that ID at that URL ';
		}
	}

	add_shortcode('getthetable', 'jns_get_the_table');
	
}

add_action('init','jns_get_the_table_init');