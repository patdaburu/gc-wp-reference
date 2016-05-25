<?php
namespace gc\wp\reference;

/**
 * Reference properties of the main plugin class.
 */
include_once 'Plugin.php';

/**
 * Post class, manages the plugin's custom post type.
 *
 * This module contains the Post class which principally defines the plugin's custom post.  It is primarily a collection
 * of static functions that relate to the definition of a single type of WordPress post.  Consider changing the name
 * of this class to something more descriptive of the actual custom post type in your plugin.
 *
 * @since 1.0.0
 * @link http://www.wpbeginner.com/wp-tutorials/how-to-create-custom-post-types-in-wordpress/
 * @link https://developer.wordpress.org/reference/functions/register_post_type/
 * @link http://lists.automattic.com/pipermail/wp-testers/2010-May/013010.html
 */
class CustomPost {

	/**
	 * Does the post type have custom meta-data associated with it?
	 */
	const HAS_METADATA = true;

	/**
	 * @var string $post_type_key Post type key, must not exceed 20 characters!  If no value is provided, a default
	 *                            value is used.  It is strongly recommended that you provide a value.
	 * @see get_post_type_key()
	 * @see register_post_type()
	 */
	private static $post_type_key = null; // By convention, use the plural form.

	/**
	 * @var string $meta_array_name The name of the array used in the meta-box form to group the meta values.  If no
	 *                              value is provided, a default value is used.
	 * @see get_meta_array_name()
	 */
	private static $meta_array_name = null; // By convention, use the singular form.

	/**
	 * @var string $meta_shortcode A shortcode that can be used to reference metadata in custom posts.  If no value is
	 *                             provided, a default value is used.
	 */
	private static $meta_shortcode = null;
	
	/**
	 * An array of labels for this post type.  Defaults are set for values that aren't represented in the array.
	 *
	 * @var array $labels
	 * @see Post::on_install()
	 * @link https://developer.wordpress.org/reference/functions/register_post_type/
	 */
	private static $labels = array();

	// Below is an example of how label settings may be provided.
	/*	$labels = array(
			'name'          => __('Products', 'my-plugins-text-domain'),
			'singular_name' => __('Product', 'my-plugins-text-domain'),
			'add_new'       => __('Add New', 'my-plugins-text-domain'),
			'add_new_item'  => __('Add New Product', 'my-plugins-text-domain'),
			'edit_item'     => __('Edit Product', 'my-plugins-text-domain'),
			'new_item'      => __('New Product', 'my-plugins-text-domain'),
			'all_items'     => __('All Products', 'my-plugins-text-domain'),
			'view_item'     => __('View Product', 'my-plugins-text-domain'),
			'search_items'  => __('Search Products', 'my-plugins-text-domain'),
			'not_found'     => __('No Products found.', 'my-plugins-text-domain'),
			'not_found_in_trash' => __('No Products found in Trash', 'my-plugins-text-domain'),
			'menu_name'     => __('Products', 'my-plugins-text-domain')
	);*/

	/**
	 * Argument values that are passed to
	 *
	 * @var array $labels
	 * @see Post::on_install()
	 * @see register_post_type()
	 * @link https://developer.wordpress.org/reference/functions/register_post_type/
	 */
	private static $register_args = array();

	// Below is an example of how registration arguments can be defined.
	/* $register_args= array(
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => true,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array('title', 'editor','thumbnail','excerpt')
		); */

	/**
	 * Gets the string key for this type of post.
	 *
	 * @see Post::$post_type_key
	 * @return string
	 */
	public static function get_post_type_key(){
		// If a value for $post_type_key has been defined by the static variable, use it.  Otherwise, fabricate one.
		$post_type_key = !is_null(CustomPost::$post_type_key) ?
			CustomPost::$post_type_key : Plugin::get_base_name('-') . '-posts';
		// In any case, the post type key must not exceed 20 characters.
		$post_type_key = substr($post_type_key, 0, 20);
		// Return the value.
		return $post_type_key;
	}

	/**
	 * Gets the name of the array used in the meta-box form to group the meta values.
	 *
	 * @see Post::$meta_array_name
	 * @return string
	 */
	public static function get_meta_array_name(){
		// If a value for $meta_form_array has been defined by the static variable, use it.  Otherwise, fabricate one.
		$meta_array_name = !is_null(CustomPost::$meta_array_name) ?
			CustomPost::$meta_array_name : Plugin::get_base_name('_') . '_post';
		// Return the value.
		return $meta_array_name;
	}

	/**
	 * Gets the shortcode that can be used to retrieve metadata for a post.
	 *
	 * @see Post::$meta_shortcode
	 * @return string
	 */
	public static function get_meta_shortcode(){
		$meta_shortcode = !is_null(CustomPost::$meta_shortcode) ?
			CustomPost::$meta_shortcode : Plugin::get_base_name('-') . '-meta';
		return $meta_shortcode;
	}

	/**
	 * Gets the key under which this post-type's metadata is stored for any given post.
	 *
	 * @see Post::meta_box()
	 * @see Post::on_save_post()
	 * @return string
	 */
	public static function get_post_meta_key(){
		// Format the key used to identify the particular metadata set we want.
		return '_'.preg_replace('/\W|\s/', '_',CustomPost::get_post_type_key()) . '_meta_data';
	}

	/**
	 * Performs custom post registration steps at WordPress initialization.
	 *
	 * @since 1.0.0
	 */
	public static function on_init(){
		// We'll need the text domain for localization as we define the labels.
		$text_domain = Plugin::get_text_domain();

		// Set up the default labels...
		$default_post_type_name = Plugin::get_base_title().' Post'; // The default post type name.
		$default_post_type_name_pl = $default_post_type_name.'s'; // The plural form of teh default post type name.
		$label_defaults = array(
			'name'          => __($default_post_type_name_pl, $text_domain),
			'singular_name' => __($default_post_type_name, $text_domain),
			'add_new'       => __('Add New', $text_domain),
			'add_new_item'  => __('Add New '.$default_post_type_name, $text_domain),
			'edit_item'     => __('Edit '.$default_post_type_name, $text_domain),
			'new_item'      => __('New '.$default_post_type_name, $text_domain),
			'all_items'     => __('All '.$default_post_type_name_pl, $text_domain),
			'view_item'     => __('View '.$default_post_type_name, $text_domain),
			'search_items'  => __('Search '.$default_post_type_name_pl, $text_domain),
			'not_found'     => __('No '.$default_post_type_name_pl.' found.', $text_domain),
			'not_found_in_trash' => __('No '.$default_post_type_name_pl.' found in Trash', $text_domain),
			'menu_name'     => __($default_post_type_name_pl, $text_domain)
		);
		// Combine the labels provided at the top of the class with the defaults.
		$labels = wp_parse_args(CustomPost::$labels, $label_defaults);

		// Set up default arguments to pass to register_post_type.
		$default_args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			// 'rewrite' is set to 'false' by default.  Setting it to 'true' causes some problems with the post not
			// showing up correctly when called out by its permalink.  This is yet to be resolved. 5/24/2016
			'rewrite'            => false,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array('title', 'editor','thumbnail','excerpt')
		);
		// Combine the arguments provided at the top of the class with the defaults.
		$args = wp_parse_args(CustomPost::$register_args, $default_args);

		// There are a couple of ways the post type key may be defined.  The get_post_type_key() function can sort
		// through them and tell us what the final value is.
		$post_type_key = CustomPost::get_post_type_key();
		// Register the post type.
		register_post_type($post_type_key, $args);

		// If this type of post has metadata associated with it...
		if(CustomPost::HAS_METADATA)
		{
			// ...make sure the meta box for this type of post is loaded when a post is loaded.
			add_action( 'add_meta_boxes_' . CustomPost::get_post_type_key(), array( __CLASS__, 'on_add_meta_boxes' ));
			// Plus, we need to take some action when the post is saved.
			add_action( 'save_post', array( __CLASS__, 'on_save_post' ));
			// Add the shortcode.
			add_shortcode(CustomPost::get_meta_shortcode(), array( __CLASS__, 'shortcode' ));
		}

	}

	/**
	 * Adds a meta-box to the editor for this type of post.
	 *
	 * @since 1.0.0
	 * @param mixed $post The post object for which a meta-box is provided.
	 * @see add_meta_box()
	 */
	public static function on_add_meta_boxes($post){
		// Pretty simple, really.  Just make a call to add_meta_box().
		add_meta_box(
			CustomPost::get_post_type_key() . '-meta',
			__('Here is the meta data...', Plugin::get_text_domain()),
			array( __CLASS__, 'meta_box' ), // the callback that defines the HTML of the meta box
			CustomPost::get_post_type_key(), // Show the meta-box with posts of this type.
			'side',
			'default'
			); // <-- Additional parameters to supply to the callback may optionally be supplied as the final argument.
	}

	/**
	 * Gets the metadata for a post.
	 *
	 * Helper function to simplify retrieval of the metadata for a given post.
	 *
	 * @since 1.0.0
	 * @param mixed $post The post metadata.
	 *
	 * @return array
	 */
	private static function get_post_metadata($post){
		// Get the key for this particular type of metadata.
		$post_meta_key = CustomPost::get_post_meta_key();
		// Get the original meta-data for the post.
		$_post_meta = get_post_meta($post->ID, $post_meta_key, true);
		// Make a copy of the original meta-data in case we need to modify it.
		$post_metadata = (new \ArrayObject($_post_meta))->getArrayCopy();
		// Return the copy.
		return $post_metadata;
	}

	/**
	 * Creates the HTML that defines the post's meta-box.
	 *
	 * @since 1.0.0
	 * @param mixed $post The post for which the meta_box is provided.
	 * @param mixed $extras Extra arguments supplied to the callback when add_meta_box() is called.
	 */
	public static function meta_box($post, $extras){
		// Get the key for this particular type of metadata.
		$post_meta_key = CustomPost::get_post_meta_key();
		// Get the original meta-data for the post.
		$_post_meta = get_post_meta($post->ID, $post_meta_key, true); 
		// Make a copy of the original meta-data in case we need to modify it.
		/** @noinspection PhpUnusedLocalVariableInspection */
		$post_metadata =
			empty($_post_meta) ?
				array() :
				(new \ArrayObject($_post_meta))->getArrayCopy(); // $post_metadata is used in the included file.

		/** @noinspection PhpUnusedLocalVariableInspection */
		$input_array_name = CustomPost::get_meta_array_name(); // $input_array_name is used in the included file.
		/** @noinspection PhpUnusedLocalVariableInspection */
		$text_domain = Plugin::get_text_domain(); // $text_domain is used in the included file.
		/** @noinspection PhpUnusedLocalVariableInspection */
		$meta_shortcode = CustomPost::get_meta_shortcode();

		// We want a nonce field as a security measure.
		$nonce_name = Plugin::get_base_name();
		wp_nonce_field('meta-box-save', $nonce_name);

		// Load the page that defines the custom post data form.
		include_once 'custom-post-meta.php';
	}

	/**
	 * Performs steps when a custom post is saved.
	 *
	 * @param integer $post_id The ID of the post being saved.
	 */
	public static function on_save_post($post_id){
		// If the post type doesn't match the post type described by this class, or the HTML POST doesn't contain
		// the meta data array...
		if( get_post_type($post_id) != CustomPost::get_post_type_key() ||
		    !isset($_POST[CustomPost::get_meta_array_name()])){
			// ...there's nothing to do here.
			return;
		}
		// If this is an autosave, skip saving the data.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;
		// Check nonce for security.
		$nonce_name = Plugin::get_base_name();
		wp_verify_nonce('meta-box-save', $nonce_name);
		// Store the post data in a variable.
		$incoming_meta_data = $_POST[CustomPost::get_meta_array_name()];

		// Use the array_map function to sanitize the option values.
		$incoming_meta_data = array_map('sanitize_text_field', $incoming_meta_data);

		// Get the key for this particular type of metadata.
		$post_meta_key = CustomPost::get_post_meta_key();
		// Save the meta box data as post metadata.
		update_post_meta($post_id, $post_meta_key, $incoming_meta_data);
	}

	/**
	 * Generates the HTML substitution for a shortcode in a custom post.
	 *
	 * @param mixed $atts The shortcode attributes.
	 * @param mixed $content Enclosed content.
	 *
	 * @return mixed The HTML substitution
	 *
	 * @see add_shortcode()
	 * @link https://developer.wordpress.org/reference/functions/add_shortcode/
	 */
	public static function shortcode($atts, $content=null){
		// Shortcodes will be evaluated based on the post type.
		global $post;

		// Extract the shortcode attributes.  (The only one we honor is 'show'.)
		extract(shortcode_atts(array("show"=>''), $atts));

		// Load the metadata for the custom post.
		$post_metadata = CustomPost::get_post_metadata($post);
		/** @noinspection PhpUndefinedVariableInspection */
		return $post_metadata[$show]; // $show is added to the symbols table when we called extract() above.
	}

}
?>
