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
 *  The page requires the plugin's class.
 */
include_once "plugin-class.php";
// We'll make frequent reference to the name of the plugin's options array, so let's put it into a local variable
// now.
$options_name = GC_Reference::$options_name;
?>
<div class="wrap">
	<h2><?= GC_Reference::$admin_menu_name; ?> Settings</h2>
	<form method="post" action="options.php">
		<?php settings_fields( GC_Reference::$option_group ); ?>
		<?php $plugin_options = get_option( $options_name ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Name</th>
				<td>
					<input type="text"
					       name="<?= $options_name; ?>[option_name]"
					       value="<?php echo esc_attr( $plugin_options['option_name'] ); ?>"
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Email</th>
				<td>
					<input type="text"
					       name="<?= $options_name; ?>[option_email]"
					       value="<?php echo esc_attr( $plugin_options['option_email'] ); ?>"
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">URL</th>
				<td>
					<input type="text"
					       name="<?= $options_name; ?>[option_url]"
					       value="<?php echo esc_attr( $plugin_options['option_url'] ); ?>"
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="Save Changes"/>
		</p>
	</form>
</div>