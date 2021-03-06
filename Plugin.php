<?php
namespace gc\wp\reference;
/**
 * Plugin class, the main class in the plugin
 *
 * This module contains the Plugin class which principally defines the plugin's behavior.
 *
 * @since 1.0.0
 */
?>
<?php
/**
 * The plugin registers the widget class.
 */
include_once 'Widget.php'; // TODO: Include only if required?
/**
 * The plugin creates the tables.
 */
include_once 'Tables.php'; // TODO: Include only if required?
/**
 * The plugin class delegates responsibilities for settings (options) to the Options class.
 */
include_once 'Options.php';
/**
 * In case the plugin has custom posts.
 */
include_once 'CustomPost.php';

/**
 * Entry point for the plugin.
 *
 * Description: In this context, the class serves as a namespace for a collection of static functions that define
 *              the plugin.
 * @since 1.0.0.0
 */
class Plugin {

	/**
	 * The plugin version.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	const VERSION = '1.0.0';

	/**
	 * Does the plugin require custom tables?
	 *
	 * If the plugin uses custom tables, the Plugin class will execute functions to create the tables on activation,
	 * remove them when the plugin is uninstalled, and so on.
	 *
	 * @see Plugin::on_activate()
	 * @see Tables
	 * @since 1.0.0
	 */
	const USES_CUSTOM_TABLES = true;

	/**
	 * Does the plugin have custom post types?
	 *
	 * If the plugin has custom post types, the Plugin class will execute functions to set them up.
	 *
	 * @see Plugin::startup()
	 * @see Post
	 * @since 1.0.0
	 */
	const HAS_CUSTOM_POST_TYPES = true;

	/**
	 * The name used to refer to the plugin in administrative menus.
	 * @since 1.0.0.0
	 * @access private
	 * @var string $admin_menu_name the name used to refer to the plugin in administrative menus
	 * @see Plugin::on_admin_menu
	 */
	public static $admin_menu_name = 'GC Reference';

	/**
	 * The name of the plugin's page.
	 *
	 * @since 1.0.0.0
	 * @access private
	 * @var string $page_title the name of the plugin's page
	 */
	private static $page_title = 'GeoComm WordPress Reference Plugin';

	/**
	 * Gets the plugin basename.
	 *
	 * Use this method to retrieve the plugin's basename in a form suitable to the current purpose.
	 *
	 * @param string $sep The separator to use between word breaks in the basename.
	 *
	 * @return string The basename string.
	 */
	static function get_base_name($sep = '_'){
		// Replace the backslashes in the namespace with the separator character ($sep).
		return str_replace('\\', $sep, __NAMESPACE__);
	}

	/**
	 * Gets the plugin's text domain.
	 *
	 * @return string
	 */
	static function get_text_domain(){
		return Plugin::get_base_name('-');
	}

	/**
	 * Gets the base title string for the plugin.
	 *
	 * @see Plugin::get_basename()
	 * @return string
	 */
	static function get_base_title(){
		// Start by getting the base name for the plugin as a string.
		$basename = Plugin::get_base_name(' ');
		// Uppercase the words and return the string.
		return ucwords($basename);
	}

	/**
	 * Starts the plugin.
	 */
	static function startup() {
		// Perform the actions that need to occur at initialization.
		add_action( 'init', array( __CLASS__, 'on_init' ) );

		// Create the Admin menu assets.
		add_action( 'admin_menu', array( __CLASS__, 'on_admin_menu' ) );
		// Register the plugin's settings.
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );

		// Add the shortcode for a plugin.  The shortcode for this plugin will be the 'base name' with dashes used as
		// word boundaries.  (If you need more shortcodes, create additional functions and calls to add_shortcode).
		add_shortcode( Plugin::get_base_name('-'), array( __CLASS__, 'shortcode' ) );

		// Register the plugin's widget(s).
		add_action('widgets_init', array(__CLASS__, 'on_widgets_init'));

		/* Registration of the hook functions has been removed to the main plugin file. */
		/* 
		// Set up the actions to perform on activation.
		register_activation_hook( __FILE__, array( __CLASS__, 'on_activate' ) );
		// Set up the actions to perform on deactivation.
		register_deactivation_hook( __FILE__, array( __CLASS__, 'on_deactivate' ) );
		// Set up the actions to perform on un-installation.
		register_uninstall_hook( __FILE__, array( __CLASS__, 'on_uninstall' ) );
		*/

		// If the plugin defines custom post types...
		if(Plugin::HAS_CUSTOM_POST_TYPES){
			// ...we need to set that up at init.
			add_action( 'init', array( __CLASS__, 'on_init_custom_post_types' ) );
		}
	}

	/**
	 * Performs WordPress plugin initialization steps.
	 *
	 * This is the callback for the WordPress 'init' event.
	 *
	 * @ignore
	 * @since 1.0.0
	 * @see add_action()
	 *
	 */
	static function on_init() {
		/**
		 * Load the internationalization files.
		 */
		load_plugin_textdomain(
			// The text domain is the plugin's "base name" with dashes substituted in for word boundaries.
			Plugin::get_text_domain(),
			false, plugin_basename( dirname( __FILE__ ) . '/localization' ) );
	}

	/**
	 * Constructs the admin menus for the plugin.
	 *
	 * This is the callback for the WordPress 'admin_menu' event.  This is where we set up the admin menus.
	 *
	 * @ignore
	 * @since 1.0.0
	 * @see add_action()
	 *
	 */
	static function on_admin_menu() {
		// Establish the slug for the menu pages (based on the plugin's base name).
		$slug = Plugin::get_base_name('-');
		/**
		 * Note:  The terms 'settings' and 'options' are used somewhat interchangeably.  Labels will typically use
		 * the term 'setting', but 'option' is more reflective of the WordPress concept.
		 */
		// Add the main menu page.
		add_menu_page(
			Plugin::$admin_menu_name . ' Settings',
			Plugin::$admin_menu_name,
			'manage_options',
			$slug . '-options', // This is the slug for the page.
			array( __CLASS__, 'main_admin_page' ),
			plugins_url( 'images/logo.png', __FILE__ )
		/** position */ ); // We're using the default position.
		// Add the 'settings' page.
		add_submenu_page(
			$slug . '-options', // Refers to the main page.
			Plugin::$admin_menu_name . ' Settings',
			'Settings',
			'manage_options',
			// We want Settings to be the top-level menu, so we use the main menu's slug as this page's slug.
			// http://wordpress.stackexchange.com/questions/66498/add-menu-page-with-different-name-for-first-submenu-item
			$slug . '-options',
			array( __CLASS__, 'settings_page' )
		);
		// Add the 'support' page.
		add_submenu_page(
			$slug . '-options', // Refers to the main page.
			Plugin::$admin_menu_name . ' Support',
			'Support',
			'manage_options',
			$slug . '-support',
			array( __CLASS__, 'support_page' )
		);
	}

	/**
	 * Registers the plugin's settings with WordPress.
	 *
	 * This is the callback for the WordPress 'admin_menu' event.  This is where we set up the admin menus.
	 *
	 * @ignore
	 * @since 1.0.0
	 * @see add_action()
	 * @see Options::register_settings()
	 *
	 */
	static function register_settings() {
		// This task is delegated to the Options class.
		Options::register_settings();
	}

	/**
	 * Performs plugin activation steps.
	 *
	 * This is the callback for the WordPress activation hook.
	 *
	 * @ignore
	 * @since 1.0.0
	 * @see register_activation_hook()
	 */
	static function on_activate() {
		/**
		 * Verify the WordPress version.
		 */
		global $wp_version;
		if ( version_compare( $wp_version, '4.5', '<' ) ) { // TODO: Move the magic string.
			wp_die( "This plugin requires WordPress version 4.5 or higher." );
		}

		/**
		 * Create (or update) the database.
		 */
		if(Plugin::USES_CUSTOM_TABLES){ // If the plugin uses custom tables at all, let's get to work...
			// If the tables haven't been installed...
			if(!Tables::areInstalled()){
				// ...create them now.
				Tables::create();
			}
			else if(!Tables::areUpToDate()){ // If they've been installed, but aren't up to date...
				// ...upgrade them.
				Tables::upgrade();
			}
		}

		/**
		 * Set up the plugin's page.
		 */
		// What's the name of the plugin's page?
		$pagename = Plugin::get_base_name('-');
		// Has the plugin already created the page?
		$page = Plugin::get_page_by_name( $pagename );
		// If not...
		if ( empty( $page ) ) {
			// ...let's do so now.
			$shortcode = Plugin::get_base_name('-'); // The content of the post is just the shortcode.
			$new = array(
				'post_name'    => $pagename,
				'post_title'   => Plugin::$page_title,
				'post_content' => '[' . $shortcode . ']',
				// The content is just an instance of the short code.
				'post_status'  => 'publish',
				'post_type'    => 'page'
			);
			wp_insert_post( $new, true );
		} else {
			// Otherwise, make sure it's "published".
			$update = array(
				'ID'          => $page->ID,
				'post_status' => 'publish'
			);
			wp_update_post( $update );
		}
	}

	/**
	 * Performs plugin deactivation steps.
	 *
	 * This is the callback for the WordPress deactivation hook.
	 *
	 * @ignore
	 * @since 1.0.0
	 * @see register_deactivation_hook()
	 */
	static function on_deactivate() {
		// Let's see if the plugin's page exists.
		$page_name = Plugin::get_base_name('-');
		$page      = Plugin::get_page_by_name( $page_name );
		// If we find it...
		if ( ! empty( $page ) ) {
			// ...set its status to 'private'.
			$update = array(
				'ID'          => $page->ID,
				'post_status' => 'private'
			);
			wp_update_post( $update );
		}
	}

	/**
	 * Performs plugin uninstall steps.
	 *
	 * This is the callback for the WordPress uninstall hook.
	 *
	 * @ignore
	 * @since 1.0.0
	 * @see register_uninstall_hook()
	 */
	static function on_uninstall() {
		// Let's see if the plugin's page exists.
		$page_name = Plugin::get_base_name('-');
		$page      = Plugin::get_page_by_name( $page_name );
		// If our page exists...
		if ( ! empty( $page ) ) {
			// ...delete it (bypassing the trash).
			wp_delete_post( $page->ID, true );
		}
		/**
		 * Remove custom database artifacts.
		 */
		if(Plugin::USES_CUSTOM_TABLES){ // If this plugin uses custom tables...
			// ...clean them up.
			Tables::uninstall();
		}
	}

	/**
	 * Registers plugin widgets.
	 *
	 * @see register_widget
	 * @link https://developer.wordpress.org/reference/functions/register_widget/
	 */
	static function on_widgets_init() {
		// We make the asumption that the widget class is in the same namespace as the plugin class.  If this isn't
		// the case, adjust the argument.
		register_widget(__NAMESPACE__.'\Widget');
	}

	/**
	 * Initializes custom post types.
	 *
	 * Callback for the 'init' action when the plugin features custom post types.
	 *
	 * @ignore
	 * @since 1.0.0
	 * @see register_uninstall_hook()
	 */
	static function on_init_custom_post_types(){
		CustomPost::on_init();
	}

	/**
	 * Write the main admin page HTML.
	 */
	static function main_admin_page() {
		// Do nothing here.  The 'settings' page is the main page.
	}

	/**
	 * Write the 'settings' page HTML.
	 */
	static function settings_page() {
		// This function is delegated to the Options class.
		Options::settings_page();
	}

	/**
	 * Write the 'support' page HTML.
	 */
	static function support_page() {
		// The support page is defined in support.php.
		include 'support.php';
	}

	/**
	 * Returns HTML for the plugin's shortcode.
	 *
	 * @param mixed $atts The shortcode attributes.
	 * @param null $content
	 *
	 * @return string The shortcode HTML.
	 */
	static function shortcode( $atts, $content = null ) {
		// Turn on output buffering.
		ob_start();
		// Bring in the shortcode page.
		include 'shortcode.php';
		// Read the buffer.
		$res = ob_get_contents();
		// Clean up.
		ob_end_clean();
		// Return the HTML response.
		return $res;
	}
	
	/**
	 * Get a page by name.
	 *
	 * This is a helper function you can use to retrieve a page by name.
	 *
	 * @ignore
	 * @since 1.0.0
	 * @see get_pages()
	 *
	 * @param mixed $page_name The name of the page to find.
	 *
	 * @return mixed the page, or null if no such page exists.
	 */
	private static function get_page_by_name( $page_name ) {
		// We are interested in pages that have been published, as well as private pages.
		$which_pages = array(
			'post_status' => "publish,private"
		);
		// Get all the pages that meet the criteria.
		$pages = get_pages( $which_pages );
		// We need to iterate over them and return the one
		foreach ( $pages as $page ) {
			if ( $page->post_name == $page_name ) {
				return $page;
			}
		}

		// If we didn't return before, we didn't find the page.
		return null;
	}

}
?>