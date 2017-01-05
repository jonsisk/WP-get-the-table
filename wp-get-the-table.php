<?php
/*
Plugin Name: WP Get The Table
Plugin URI: http://www.itjon.com/getthetable
Description: A plugin that lets you grab a live HTML table from a URL by ID, and echo it out onto a page via shortcode. Usage: <strong>[getthetable url="page url" id="table id"]</strong>
Version: 1.3.1
Author: Jonathan Sisk
Author URI: http://www.itjon.com
License: GPL2
*/

//everything is wrapped in this function so we can initalize at the proper time
function jns_get_the_table_init() {

	//the function for the shortcode
	function jns_get_the_table($atts) {

		//get the contents from the URL
		$ch = curl_init($atts['url']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		//show error message if URL is invalid, otherwise store as $page
		if(curl_exec($ch) === false) {

			return "Sorry, the URL: <strong>" . $atts['url'] . "</strong> could not be opened.";

		} else {

			$page = curl_exec($ch);

		}

		//debug mode echos out page that was cURLed
		if($atts['debug'] == '1') {
			echo '<br>Debug:<hr><pre>' . $page . '</pre><hr><br>';
		}

		//find the table with matching ID
		$match = '#<table[^>]*id="' . $atts['id'] . '"[^>]*>.+?</table>#is';
		preg_match($match, $page, $table);

		//show error if we didn't find a table with that ID
		if($table[0] == '') {

			return 'Sorry, no table with id="<strong>' . $atts['id'] . '</strong>" could be found at the URL: <strong>"' . $atts['url'] . '</strong>".';

		} else {
			
			//add tablesorter script if the argument is present
			if($atts[0] == 'tablesorter') {
			
				wp_enqueue_style('tablesorter_style', plugin_dir_url(__FILE__) . 'tablesorter/style.css');
				wp_enqueue_script('tablesorter', plugin_dir_url(__FILE__) . 'tablesorter/jquery.tablesorter.min.js',array(),false,true);

				function tablesort_the_table() {
					echo "<script>jQuery(document).ready(function(){jQuery('#" .  $atts['id'] . "').tablesorter()})</script>";
				}

				add_action('wp_footer','tablesort_the_table');

				echo $table[0];

			} else {

				//return the table
				echo $table[0];

			}

		}

	}
	
	//add the shortcode
	add_shortcode('getthetable', 'jns_get_the_table');

}

//run the works at WP init since we are a plugin
add_action('init','jns_get_the_table_init');