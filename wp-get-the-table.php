<?php
/*
Plugin Name: WP Get The Table
Plugin URI: http://www.itjon.com/getthetable
Description: A plugin that lets you grab a live HTML table from a URL by ID, and echo it out onto a page via shortcode. Usage: <strong>[getthetable url="page url" id="table id"]</strong>
Version: 1.4
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
		
		//style including mobile breakpoint
		?>
		<style>
			.stacktable.large-only { display: table; }
			.stacktable.small-only { display: none; }
			@media (max-width: <?php echo $atts['mobile'];?>px) {
				.stacktable.large-only { display: none; }
				.stacktable.small-only { display: table; }
			}
			.stacktable.small-only th { background: #2ba8e0; padding: 5px 10px; }
			.stacktable.small-only th a { color: white !important; }
			.stacktable.small-only td { vertical-align: top; padding: 5px 10px; }
			.stacktable.small-only .st-key { font-weight:bold; }
			#<?php echo $atts['id']; ?> th {background-color: #3e9ed9 !important; color: white !important; padding: 5px 20px;background-image: url(data:image/gif;base64,R0lGODlhFQAJAIAAAP///////yH5BAEAAAEALAAAAAAVAAkAAAIXjI+AywnaYnhUMoqt3gZXPmVg94yJVQAAOw==);	background-repeat: no-repeat; background-position: center right; cursor: pointer;}
			table#<?php echo $atts['id']; ?> {font-family:arial;background-color: #CDCDCD; margin:10px 0pt 15px; font-size: 8pt; width: 100%;	text-align: left;}
			table#<?php echo $atts['id']; ?> tbody tr:nth-child(odd) td {background-color:#FAFAFA;}
			table#<?php echo $atts['id']; ?> thead tr .headerSortUp {background-image: url(data:image/gif;base64,R0lGODlhFQAEAIAAAP///////yH5BAEAAAEALAAAAAAVAAQAAAINjI8Bya2wnINUMopZAQA7);}
			table#<?php echo $atts['id']; ?> thead tr .headerSortDown {background-image: url(data:image/gif;base64,R0lGODlhFQAEAIAAAP///////yH5BAEAAAEALAAAAAAVAAQAAAINjB+gC+jP2ptn0WskLQA7);}				
			#<?php echo $atts['id']; ?> td {border:1px solid #eee; color: #3D3D3D; padding: 4px; background-color: #FFF; vertical-align: top;}
		</style>

		<?php
		//find the table with matching ID
		$match = '#<table[^>]*id="' . $atts['id'] . '"[^>]*>.+?</table>#is';
		preg_match($match, $page, $table);

		//show error if we didn't find a table with that ID
		if($table[0] == '') {

			return 'Sorry, no table with id="<strong>' . $atts['id'] . '</strong>" could be found at the URL: <strong>"' . $atts['url'] . '</strong>".';

		} else {
			
			//add tablesorter script if the argument is present
			if($atts[0] == 'tablesorter') {
			
				wp_enqueue_script('tablesorter', plugin_dir_url(__FILE__) . 'js/jquery.tablesorter.min.js',array(),false,true);
				wp_enqueue_script('stacktable', plugin_dir_url(__FILE__) . 'js/stacktable.min.js',array(),false,true);
				
				echo $table[0];
				echo "<script>jQuery(document).ready(function(){jQuery('#" .  $atts['id'] . "').tablesorter().stacktable()})</script>";				
				
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