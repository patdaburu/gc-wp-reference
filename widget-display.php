<?php
/**
 * Widget Display HTML Page
 *
 * This module defines the HTML written when the widget appears on a page.
 *
 * @see GC_ReferenceWidget::widget()
 * @since 1.0.0
 */

// Retrieve the defined widget properties.
/** @noinspection PhpUndefinedVariableInspection */
$title =apply_filters('widget_title', $instance['title']);
$name  = (empty($instance['name']) ? '' : $instance['name']);
$bio   = (empty($instance['bio']) ? '' : $instance['bio']);

// If we have a title to work with...
if(!empty($title)){
	// ...wrap it up in the HTML the theme provides and write it out.
	/** @noinspection PhpUndefinedVariableInspection */
	echo $before_title . esc_html($title) . $after_title;
}
?>
<p>Name:<?=esc_html($name)?></p>
<p>Bio:<?=esc_html($bio)?></p>
