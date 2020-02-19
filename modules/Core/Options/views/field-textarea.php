<?php
	$field = wp_parse_args($field, [
		'placeholder' => '',
		'rows' => 4
	])
?>

<textarea
	name="<?php echo F4_EP_OPTION_NAME; ?>[<?php echo $field_name; ?>]"
	id="<?php echo F4_EP_OPTION_NAME . $field_name; ?>"
	placeholder="<?php echo $field['placeholder']; ?>"
	rows="<?php echo $field['rows']; ?>"
	class="regular-text"
><?php echo $options[$field_name]; ?></textarea>
