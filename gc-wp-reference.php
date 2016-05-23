<?php
/*
 * Plugin Name: GeoComm Reference Plugin
 * Plugin URI: https://github.com/patdaburu
 * Description: A reference template for plugin development.
 * Version: 1.0.0.0
 * Author: Pat Blair
 * Author URI: https://github.com/patdaburu
 * Text Domain: gc-reference
 * License: GPLv2
 */
?>
<?php

// Import the plugin namespace.
use gc\wp\reference\Plugin as Plugin;

// Include the class that defines the plugin's core behavior.
include_once "Plugin.php";

/*
Note:  It has been possible (intermittently) to register hooks in the plugin class.  However, registering in the main
file has proven to be more reliable.
*/

/**
 * Performs plugin activation steps.
 *
 * This is the callback for the WordPress activation hook.
 *
 * @ignore
 * @since 1.0.0.0
 * @see register_activation_hook()
 * @see GC_Reference::on_activate()
 */
function on_activate(){
	Plugin::on_activate();
}
// Set up the actions to perform on activation.
register_activation_hook( __FILE__, 'on_activate');

/**
 * Performs plugin deactivation steps.
 *
 * This is the callback for the WordPress deactivation hook.
 *
 * @ignore
 * @since 1.0.0.0
 * @see register_deactivation_hook()
 * @see GC_Reference::on_deactivate()
 */
function on_deactivate(){
	Plugin::on_deactivate();
}
// Set up the actions to perform on deactivation.
register_deactivation_hook( __FILE__, 'on_deactivate');

/**
 * Performs plugin uninstall steps.
 *
 * This is the callback for the WordPress uninstall hook.
 *
 * @ignore
 * @since 1.0.0.0
 * @see register_uninstall_hook()
 * @see GC_Reference::on_uninstall()
 */
function on_uninstall(){
    Plugin::on_uninstall();
}
// Set up the actions to perform on uninstallation.
register_uninstall_hook( __FILE__, 'on_uninstall');

// Start up the plugin.
Plugin::startup();
?>