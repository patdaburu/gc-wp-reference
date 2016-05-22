<?php
/**
 * Widget Configuration Form Page
 *
 * This module defines the contents of the HTML form used to configure the plugin.
 *
 * @see GC_ReferenceWidget::widget()
 * @since 1.0.0
 */
?>
<p>Title:
	<input class="widefat"
	       name="<?= $this->get_field_name('title')?>"
	       type="text"
	       value="<?=esc_attr($title)?>"/>
</p>
<p>Name:
	<input class="widefat"
	       name="<?= $this->get_field_name('name')?>"
	       type="text"
	       value="<?=esc_attr($name)?>"/>
</p>
<p>Bio:
	<input class="widefat"
	       name="<?= $this->get_field_name('bio')?>"
	       type="text"
	       value="<?=esc_textarea($bio)?>"></textarea>
</p>
