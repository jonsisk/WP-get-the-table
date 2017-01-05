# wp-get-the-table
A plugin that lets you grab an HTML table from a URL by ID, and echo it out onto a page via shortcode. Use your own stylesheets and responsiveness with on-page <table> elements rather than ugly iframe embeds of offsite data.

Upload and activate the plugin to enable the shortcode.

Shortcode usage:
----------------
	[getthetable url="" id=""] - Basic Format
	[getthetable url="http://www.itjon.com/getthetable" id="getthetable"] - This will grab the sample table off the plugin page
	[getthetable url="http://www.itjon.com/getthetable" id="getthetable" tablesorter] - The tablesorter argument adds the tablesorter js and css from cdn.ucb.org.br and applies it to your table
	
Revision history:
-----------------
1.3.1 Styled debug output

1.3 Added debug mode

1.2 Added tablesorter js and css to plugin codebase

1.1 Added verification and error handling for bad URL/ID and tablesorter option

1.0 Initial Release