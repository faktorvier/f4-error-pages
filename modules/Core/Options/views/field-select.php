<select
	name="<?php echo F4_EP_OPTION_NAME; ?>[<?php echo $field_name; ?>]"
	id="<?php echo F4_EP_OPTION_NAME . $field_name; ?>"
>
	<?php foreach($field['options'] as $option_value => $option_name): ?>
		<option
			value="<?php echo $option_value; ?>"
			<?php selected($option_value === $options[$field_name]); ?>
			>
			<?php echo $option_name; ?>
		</option>
	<?php endforeach; ?>
</select>
