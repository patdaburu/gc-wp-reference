<?php
/**
 * Custom Post Meta-data Form
 *
 * This module defines the custom post type's meta-data form.
 *
 * @see Post::meta_box()
 * @since 1.0.0
 */
?>
<!--suppress HtmlFormInputWithoutLabel -->
<table>
	<tr>
		<td>
			Pet Name
		</td>
		<td>
			<input type="text"
			       name="<?= /** @noinspection PhpUndefinedVariableInspection */
			       $input_array_name?>[pet_name]"
			       value="<?= /** @noinspection PhpUndefinedVariableInspection */
			       esc_attr($post_metadata['pet_name'])?>"/>
		</td>
	</tr>
	<tr>
		<td>
			Secret Wish
		</td>
		<td>
			<input type="text"
			       name="<?=$input_array_name?>[secret_wish]"
			       value="<?=esc_attr($post_metadata['secret_wish'])?>"/>
		</td>
	</tr>
	<?php /** Display the meta box shortcode legend section. */ ?>
	<tr><td colspan="2"><hr></td></tr>
	<tr><td colspan="2"><strong><?=__('Shortcode Legend', $text_domain)?></strong></td></tr>
	<tr><td><?=__('Pet Name', $text_domain)?></td><td>[<?= /** @noinspection PhpUndefinedVariableInspection */
			$meta_shortcode?> show=pet_name]</td></tr>
	<tr><td><?=__('Secret Wish', $text_domain)?></td><td>[<?=$meta_shortcode?> show=secret_wish]</td></tr>
</table>
