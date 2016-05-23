<?php
/**
 * Plugin Support Page
 *
 * This module defines the 'support' page for the plugin.
 *
 * @since 1.0.0.0
 */
?>
<?php
/**
 * Import the plugin namespace.  (We'll reference static members of the plugin class.)
 */
use gc\wp\reference\Plugin as Plugin;
/**
 *  The page requires the plugin's class.
 */
include_once "plugin-class.php";
?>
<div class="wrap">
	<h2><?= Plugin::$admin_menu_name; ?> Support</h2>
	<p>Support information goes here.</p>
</div>