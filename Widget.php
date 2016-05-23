<?php
namespace gc\wp\reference;

/**
 * We need access to members of the Plugin class.
 */
include_once 'Plugin.php';
/**
 * GC_ReferenceWidget
 *
 * This is a reference widget class that demonstrates the widget lifecycle.
 *
 * @see widget-display.php
 * @see widget-form.php
 * @link https://developer.wordpress.org/reference/functions/register_widget/
 */
class Widget extends \WP_Widget {

	/**
	 * Widget properties.
	 *
	 * Defines widget properties to override default values.
	 *
	 * @var array Properties that define this widget.
	 * @type string html_id_prefix The prefix applied to the HTML element ID when the widget is rendered on the page.
	 * @type string html_classname The class name added to the HTML tag wrapping the widget when it's displayed.
	 *                             (It may show up in a <div>, <aside>, <li>, or other HTML tag.)
	 * @type string widget_name The name of the widget as it appears in the Widgets dashboard menu.
	 * @type string widget_description The descripiton of the widget that appears in the Widgets dashboard menu.
	 */
	private $definition = array();

	/**
	 * Widget constructor.
	 */
	function __construct() {
		// Mix the defined widget properties with the defaults.
		$my = wp_parse_args($this->definition, array(
			'html_id_prefix'     => Plugin::get_base_name('-') . '-widget',
			'html_classname'     => Plugin::get_base_name('_') . '_widget',
			'widget_name'        => Plugin::get_base_title().' Widget',
			'widget_description' => 'This widget needs a description!'
		));

		// Create the options we'll provide to the parent class' constructor.
		$widget_opts = array(
			/**
			 * the class name added to the HTML tag wrapping the widget when it's displayed (It may show up in a
			 * <div>, <aside>, <li>, or other HTML tag.)
			 */
			'classname' => $my['html_classname'],
			/**
			 * displayed on the widget dashboard below the widget name
			 */
			'description' => $my['widget_description']
		);

		// Call the parent's constructor.
		parent::__construct(
			$my['html_id_prefix'], // the widget's HTML ID prefix
			$my['widget_name'],
			$widget_opts);
	}

	/**
	 * The widget's HTML output.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Display arguments including before_title, after_title,
	 *                        before_widget, and after_widget.
	 * @param array $instance The settings for the particular instance of the widget.
	 */
	function widget( $args, $instance ) {
		// Extract the $args parameters to store some global theme values like $before_widget and $after_widget.
		// (These variables can be used by theme developers to customize what code will wrap the widget.)
		extract($args);
		echo $args['before_widget'];
		// Include the HTML generated by the widget's display page.
		include "widget-display.php";
		// Tack on whatever the current theme believes should come after the widget.
		echo $args['after_widget'];
	}

	/**
	 * The widget update handler.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance The new instance of the widget.
	 * @param array $old_instance The old instance of the widget.
	 * @return array The updated instance of the widget.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']=sanitize_text_field($new_instance['title']);
		$instance['name']=sanitize_text_field($new_instance['name']);
		$instance['bio']=sanitize_text_field($new_instance['bio']);
		return $instance;
	}

	/**
	 * Output the admin widget options form HTML.
	 *
	 * @param array $instance The current widget settings.
	 * @return string The HTML markup for the form.
	 */
	function form( $instance ) {
		// Provide default widget properties.
		$defaults = array(
			'title' => 'My Biography',
			'name'  => 'Dudley Do-Right',
			'bio'   => 'I am a Royal Canadian Mountie.'
		);
		// Combine the defaults with the properties in the current widget settings.
		$instance = wp_parse_args((array)$instance, $defaults);
		$title = $instance['title'];
		$name = $instance['name'];
		$bio = $instance['bio'];
		// Provide HTML to the caller.
		include "widget-form.php";
	}
}