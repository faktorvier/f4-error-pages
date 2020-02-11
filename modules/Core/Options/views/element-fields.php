<table class="form-table<?php if(isset($element['compact']) && $element['compact']): ?> form-table--compact<?php endif; ?>" role="presentation">
	<tbody>
		<?php foreach($element['fields'] as $field_name => $field): ?>
			<tr>
				<th>
					<label for="<?php echo F4_EP_CORE_OPTION_NAME . $field_name; ?>">
						<?php echo $field['label']; ?>
					</label>
				</th>
				<td>
					<?php include 'field-' . $field['type'] . '.php'; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
