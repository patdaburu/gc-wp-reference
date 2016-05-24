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
 * of this class to something more descriptive to the actual custom post type in your plugin.
 *
 * @since 1.0.0
 * @link http://www.wpbeginner.com/wp-tutorials/how-to-create-custom-post-types-in-wordpress/
 * @link https://developer.wordpress.org/reference/functions/register_post_type/
 */
class Post {

	/**
	 * Post type key, must not exceed 20 characters!  If no value is provided, a default value is used.  It is strongly
	 * recommended that you provide a value.
	 *
	 * @var string $post_type_key
	 * @see register_post_type()
	 */
	private static $post_type_key = null;

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
	 * Performs custom post registration steps at WordPress initialization.
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
		$labels = wp_parse_args(Post::$labels, $label_defaults);

		// Set up default arguments to pass to register_post_type.
		$default_args = array(
			'labels'             => $labels,
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
		);
		// Combine the arguments provided at the top of the class with the defaults.
		$args = wp_parse_args(Post::$register_args, $default_args);

		// If a value for $post_type_key has been defined by the static variable, use it.  Otherwise, fabricate one.
		$post_type_key = !is_null(Post::$post_type_key) ? Post::$post_type_key : Plugin::get_base_name('-').'-posts';
		// In any case, the post type key must not exceed 20 characters.
		$post_type_key = substr($post_type_key, 0, 20);
		// Register the post type.
		register_post_type($post_type_key, $args);
	}
}
?>
