<?php
namespace gc\wp\reference;

/**
 * Options class, handles plugin options (settings).
 *
 * This module contains the Tables class (a collection of static functions) which is responsible for the creation and
 * upgrading of the plugin's custom MySQL tables.
 *
 * @since 1.0.0
 */
class Options {

	/**
	 * Identifier for the plugin's option group.
	 *
	 * @since 1.0.0.0
	 * @access public
	 * @var string $option_group the string to use as the default slug (or slug prefix)
	 * @link https://developer.wordpress.org/reference/functions/register_setting/
	 */
	public static $options_group = null; // See below for static initialization of this static variable.

	/**
	 * Name of the plugin's options array.
	 *
	 * The plugin options are stored in a single array.
	 *
	 * @since 1.0.0.0
	 * @access public
	 * @var string $options_name name of the plugin's options array.
	 * @link https://developer.wordpress.org/reference/functions/register_setting/
	 */
	public static $options_name = null; // See below for static initialization of this static variable.

	/**
	 * Registers the plugin's settings with WordPress.
	 *
	 * This is the callback for the WordPress 'admin_menu' event.  This is where we set up the admin menus.
	 *
	 * @ignore
	 * @since 1.0.0
	 * @see add_action()
	 * @see Options::sanitize_options()
	 *
	 */
	static function register_settings() {
		// Register the plugin's settings with WordPress.  Note that the third argument is the callback WordPress will
		// use to sanitize incoming values.
		register_setting(
			Options::$options_group,
			Options::$options_name,
			array( __CLASS__, 'sanitize_options' ) );
	}

	/**
	 * Sanitizes option values when they're saved.
	 *
	 * This callback is passed to WordPress when the plugin registers its settings.
	 *
	 * @param $input An array of form input values.
	 * @see Options::register_settings()
	 *
	 * @return mixed
	 */
	static function sanitize_options( $input ) {
		$input['name']  = sanitize_text_field( $input['name'] ); // sanitize text input
		$input['email'] = sanitize_email( $input['email'] ); // sanitize the email
		$input['url']   = esc_url( $input['url'] ); // sanitize a URL
		return $input;
	}

	/**
	 * Write the 'settings' page HTML.
	 */
	static function settings_page() {
		// The settings page is defined in settings.php.
		include 'settings.php';
	}

}

/**
 * Perform static initialization tasks.
 */

// The options group name for the plugin is built upon the plugin's namespace.
Options::$options_group = Plugin::get_base_name('-') . '-settings-group';
// The options name is also based on the plugin's namespace.
Options::$options_name = Plugin::get_base_name('_');