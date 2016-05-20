<?php
/**
 * Shortcode Page
 *
 * This module defines the HTML returned when the plugin's shortcode is used.
 *
 * @since 1.0.0.0
 */
?>
<?php

/**
 *  The page requires the plugin's class.
 */
include_once "plugin-class.php";

// Retrieve the options array for the plugin.
$plugin_options = get_option( GC_Reference::$options_name );

// Import the supplied attributes ($atts) into the current symbol table.
extract(
	shortcode_atts( array(
		'color' => 'red' // Set defaults.
	), $atts ),
	EXTR_PREFIX_SAME, // If we find a collision with an existing variable...
	'local' // ...prefix the new variable with 'local_'.
);

?>
<!-- Import JavaScripts -->
<script type='text/javascript' src='<?=plugins_url( '/js/main.js', __FILE__ )?>'></script>

<!-- Now let's write some HTML -->
<h2>Define the shortcode output.</h2>
<p>Email (option) = <?=$plugin_options['option_email']?></p>
<p>Color (attribute) = <?=$color?></p>
<p>To prove JavaScript is working, this timestamp will change periodically as the page reloads: <?=date("h:i:sa")?></p>
