<?php
	$field = wp_parse_args($field, [
		'placeholder' => ''
	])
?>

<input
	type="text"
	name="<?php echo F4_EP_OPTION_NAME; ?>[<?php echo $field_name; ?>]"
	id="<?php echo F4_EP_OPTION_NAME . $field_name; ?>"
	value="<?php echo $options[$field_name]; ?>"
	class="regular-text"
	placeholder="<?php echo $field['placeholder']; ?>"
/>
