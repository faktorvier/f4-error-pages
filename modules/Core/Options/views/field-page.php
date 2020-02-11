<?php
	$field = wp_parse_args($field, [
		'show_option_none' => __('&mdash; Select &mdash;'),
		'option_none_value' => '0',
		'post_status' => array('publish')
	])
?>

<?php wp_dropdown_pages(
		array(
			'name' => F4_EP_CORE_OPTION_NAME . '[' . $field_name . ']',
			'show_option_none' => $field['show_option_none'],
			'option_none_value' => $field['option_none_value'],
			'selected' => $options[$field_name],
			'post_status' => $field['post_status']
		)
	);
?>
