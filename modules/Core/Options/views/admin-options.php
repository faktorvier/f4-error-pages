
<?php

use F4\EP\Core\Helpers as Core;
use F4\EP\Core\Options\Helpers as Options;

if(!defined('ABSPATH')) exit;

// Get tabs
$tabs = [];

foreach(Options::get_tabs() as $tab_slug => $tab) {
	$elements = Options::get_elements($tab_slug);

	if(!empty($elements)) {
		$tabs[$tab_slug] = [
			'label' => $tab['label'],
			'elements' => $elements
		];
	}
}

// Get first tab
$tab_first = isset(array_keys($tabs)[0]) ? array_keys($tabs)[0] : '';

// Get all options
$options = Options::get();

?>

<!-- Tab script, only if more than one tab -->
<?php if(count($tabs) > 1): ?>
	<script>
		jQuery(function() {
			jQuery('.nav-tab').on('click', function(e) {
				e.preventDefault();

				let $tab = jQuery(this);
				let $tabs = jQuery('.nav-tab');
				let $contents = jQuery('[data-tab-content]');
				let tabName = $tab.attr('data-tab');

				$tabs.removeClass('nav-tab-active');
				$tab.addClass('nav-tab-active');

				$contents.hide();
				$contents.filter('[data-tab-content="' + tabName + '"]').show();
			});
		});
	</script>
<?php endif; ?>

<div class="wrap">
	<div class="f4-options-form">
		<!-- Headline -->
		<h1>
			<?php _e('Error Pages Settings', 'f4-error-pages'); ?>
		</h1>

		<!-- Tabs -->
		<?php if(count($tabs) > 1): ?>
			<nav class="nav-tab-wrapper">
				<?php foreach($tabs as $tab_slug => $tab): ?>
					<a
						href="<?php echo admin_url('options-general.php?page=' . F4_EP_SLUG); ?>"
						data-tab="<?php echo $tab_slug; ?>"
						class="nav-tab<?php if($tab_slug === $tab_first): ?> nav-tab-active<?php endif; ?>"
						>
						<?php echo $tab['label']; ?>
					</a>
				<?php endforeach; ?>
			</nav>
		<?php endif; ?>

		<!-- Options form -->
		<form method="POST" action="options.php" novalidate="novalidate">
			<?php settings_fields(F4_EP_OPTION_NAME); ?>

			<?php foreach($tabs as $tab_slug => $tab): ?>
				<div
					<?php if($tab_slug !== $tab_first): ?>style="display:none;"<?php endif; ?>
					data-tab-content="<?php echo $tab_slug; ?>"
					>

					<?php foreach($tab['elements'] as $element): ?>
						<?php include 'element-' . $element['type'] . '.php'; ?>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>

			<?php submit_button(); ?>
		</form>
	</div>

	<div class="f4-options-sidebar">
		<a class="f4-options-sidebar-link" href="https://www.faktorvier.ch" target="_blank">
			<img src="<?php echo F4_EP_URL . 'assets/img/made-with-love-by-f4.png'; ?>" alt="F4" />
		</a>
	</div>
</div>
