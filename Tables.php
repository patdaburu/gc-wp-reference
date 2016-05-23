<?php
namespace gc\wp\reference;
/**
 * Tables class, MySQL database handling.
 *
 * This module contains the Tables class (a collection of static functions) which is responsible for the creation and
 * upgrading of the plugin's custom MySQL tables.
 *
 * @since 1.0.0
 */
class Tables {

	/**
	 * @var The name of the WordPress option that contains the database version.
	 */
	public static $db_version_option = null;

	/**
	 * Create the plugin tables.
	 *
	 * @see Tables::areInstalled()
	 */
	public static function create(){
		// If we have already installed the tables...
		if(Tables::areInstalled()){
			// TODO: Should this cause an error?
			return;
		};
		// We'll need the WordPress database global.
		global $wpdb;
		// Define the custom table name.  (This example creates the table name by concatenating the WordPress prefix
		// and the plugin's basename.)
		$table_name = $wpdb->prefix.Plugin::get_base_name('_'); // Extend the basename to create additional tables.
		// Now we just do some straight-up SQL work.
		$sql = "CREATE TABLE ".$table_name."(
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			time bigint(11) DEFAULT '0' NOT NULL,
			name tinytext NOT NULL,
			text text NOT NULL,
			url VARCHAR(55) NOT NULL,
			UNIQUE KEY id(id)
		);";
		require_once(ABSPATH.'wp-admin/includes/upgrade.php');
		// Execute the query to create the table. ("The dbDelta() function verifies first that the table you are
		// creating doesn't exist so you don't have to wory about checking if a table exists before creating it."
		// -- Williams & Damstra, 'Professional WordPress: Design and Development')
		dbDelta($sql);
		// Set the table structure version.
		add_option(Tables::$db_version_option, Plugin::VERSION);
	}

	/**
	 * Upgrade the plugin's tables.
	 *
	 * @see Tables::areUpToDate()
	 */
	public static function upgrade(){
		// If an upgrade isn't required at this time...
		if(Tables::areUpToDate()){
			// ...that's that.
			return;
		}

		/** Take whatever steps are required to update the custom database tables. */

		// Update the WordPress option that holds the database version.
		update_option(Tables::$db_version_option, Plugin::VERSION);
	}

	/**
	 * Remove traces of the custom tables.
	 */
	public static function uninstall(){
		// If nothing is installed...
		if(!Tables::areInstalled()){
			// ...there's nothing to do.
			return;
		}
		// We'll need the WordPress database global.
		global $wpdb;
		// Delete the custom table(s) from the database.
		$table_name = $wpdb->prefix.Plugin::get_base_name('_');
		$wpdb->query("DROP TABLE IF EXISTS $table_name");
		// Delete the WordPress option that contains the database version.
		delete_option(Tables::$db_version_option);
	}

	/**
	 * Have the plugin's custom tables been installed?
	 *
	 * @return bool
	 */
	public static function areInstalled(){
		// If the "database version" option we write when we create (or upgrade) the database is present...
		if(get_option(Tables::$db_version_option)){
			// ...that's our cue that we the tables have been installed through the plugin.
			return true;
		} else { // Otherwise...
			// ...to our knowledge the tables haven't been installed.
			return false;
		}
	}

	/**
	 * Are the plugin's custom tables consistent with the current version of the plugin?
	 *
	 * @return bool
	 */
	public static function areUpToDate(){
		// Get the installed version from the WordPress options.
		$installed_version = get_option( Plugin::get_base_name('_') . '_db_version');
		// Whether or not an upgrade is required depends on whether or not the current plugin version matches the
		// database version we set in the WordPress options the last time we created (or upgraded) the database.
		return $installed_version == Plugin::VERSION;
	}
}

/**
 * Perform static initialization tasks.
 */

// What's the name of the WordPress option that holds the DB version?
Tables::$db_version_option = Plugin::get_base_name('_') . '_db_version';