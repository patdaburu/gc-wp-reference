<?php
/**
 * Plugin Settings Page
 *
 * This module defines the 'settings' page for the plugin.
 *
 * @since 1.0.0.0
 */
?>
<?php

/**
 * We need the Plugin class.
 */
include_once 'Plugin.php';
use gc\wp\reference\Plugin as Plugin;

/**
 * We need the Options class.
 */
include_once 'Options.php';
use gc\wp\reference\Options as Options;

// We'll make frequent reference to the name of the plugin's options array, so let's put it into a local variable
// now.
$options_name = Options::$options_name;
?>
<div class="wrap">
	<h2><?= Plugin::$admin_menu_name; ?> Settings</h2>
	<form method="post" action="options.php">
		<?php settings_fields( Options::$options_group ); ?>
		<?php $plugin_options = get_option( $options_name ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Name</th>
				<td>
					<input type="text"
					       name="<?=$options_name;?>[name]"
					       value="<?php echo esc_attr( $plugin_options['name'] ); ?>"
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Email</th>
				<td>
					<input type="text"
					       name="<?=$options_name;?>[email]"
					       value="<?php echo esc_attr( $plugin_options['email'] ); ?>"
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">URL</th>
				<td>
					<input type="text"
					       name="<?=$options_name;?>[url]"
					       value="<?php echo esc_attr( $plugin_options['url'] ); ?>"
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="Save Changes"/>
		</p>
	</form>
</div>