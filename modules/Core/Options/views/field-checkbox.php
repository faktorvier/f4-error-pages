<input
	type="checkbox"
	name="<?php echo F4_EP_CORE_OPTION_NAME; ?>[<?php echo $field_name; ?>]"
	id="<?php echo F4_EP_CORE_OPTION_NAME . $field_name; ?>"
	value="1"
	<?php checked($options[$field_name]); ?>
/>
