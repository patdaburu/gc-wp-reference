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
<h2>Define the shortcode output.</h2>
<?php
echo "Email (option) = ".$plugin_options['option_email']."<br/>";
echo "Color (attribute) = ".$color."<br/>";
?>
