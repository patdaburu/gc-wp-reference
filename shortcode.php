<?php
/**
 * Shortcode Page
 *
 * This module defines the HTML returned when the plugin's shortcode is used.
 *
 * @since 1.0.0
 */
?>
<?php

/**
 * We need the Options class.
 */
include_once 'Options.php';
use gc\wp\reference\Options as Options;

// Retrieve the options array for the plugin.
$plugin_options = get_option( Options::$options_name );

// Import the supplied attributes ($atts) into the current symbol table.
/** @noinspection PhpUndefinedVariableInspection */
extract(
	shortcode_atts( array(
		'color' => 'red' // Set defaults.
	), $atts ),
	EXTR_PREFIX_SAME, // If we find a collision with an existing variable...
	'local' // ...prefix the new variable with 'local_'.
);

?>
<!-- Import JavaScripts -->
<script type='text/javascript' src='<?= plugins_url( '/js/main.js', __FILE__ ) ?>'></script>

<!-- Now let's write some HTML -->
<h2>Define the shortcode output.</h2>
<p>Email (option) = <?= $plugin_options['email'] ?></p>
<p>Color (attribute) = <?= /** @noinspection PhpUndefinedVariableInspection */
	$color ?></p>
<p>To prove JavaScript is working, this timestamp will change periodically as the page
	reloads: <?= date( "h:i:sa" ) ?></p>

<!--
Refreshing an IFrame:  Uncomment the lines below and point the iframe at a target on the local domain (ie. same host
name, protocol, and port).  Then go to js/main.js, comment out the line that refreshes the page and uncomment the line
that refreshes the iframe.
-->
<!--<iframe id="myIFrame" src="/phpinfo.php"></iframe>-->
