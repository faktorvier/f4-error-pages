<fieldset>
	<?php foreach($field['options'] as $option_value => $option_name): ?>
		<label for="<?php echo F4_EP_OPTION_NAME . $field_name . '-' . $option_value; ?>">
			<input
				type="checkbox"
				name="<?php echo F4_EP_OPTION_NAME; ?>[<?php echo $field_name; ?>][]"
				id="<?php echo F4_EP_OPTION_NAME . $field_name . '-' . $option_value; ?>"
				value="<?php echo $option_value; ?>"
				<?php checked(in_array($option_value, (array)$options[$field_name])); ?>
			/>

			<span style="margin-right:1em;">
				<?php echo $option_name; ?>
			</span>
		</label>
	<?php endforeach; ?>
</fieldset>
