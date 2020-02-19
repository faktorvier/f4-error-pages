<?php

namespace F4\EP\Page;

use F4\EP\Core\Helpers as Core;
use F4\EP\Core\Options\Helpers as Options;

/**
 * Page hooks
 *
 * Hooks for the Page module
 *
 * @since 1.0.0
 * @package F4\EP\Page
 */
class Hooks {
	/**
	 * Initialize the hooks
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function init() {
		add_action('F4/EP/set_constants', __NAMESPACE__ . '\\Hooks::set_default_constants', 99);
		add_action('F4/EP/loaded', __NAMESPACE__ . '\\Hooks::loaded');
		add_filter('F4/EP/register_options_tabs', __NAMESPACE__ . '\\Hooks::register_options_tab', 5);
		add_filter('F4/EP/register_options_defaults', __NAMESPACE__ . '\\Hooks::register_options_defaults');
		add_filter('F4/EP/register_options_elements', __NAMESPACE__ . '\\Hooks::register_options_elements');

		register_activation_hook(F4_EP_MAIN_FILE, __NAMESPACE__ . '\\Hooks::plugin_activation');
		register_deactivation_hook(F4_EP_MAIN_FILE, __NAMESPACE__ . '\\Hooks::plugin_deactivation');
	}

	/**
	 * Sets the module default constants
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function set_default_constants() {
		if(!defined('F4_EP_HTACCESS_COMMENT')) {
			define('F4_EP_HTACCESS_COMMENT', '# F4 Error Pages');
		}

		if(!defined('F4_EP_HTACCESS_RULE')) {
			define('F4_EP_HTACCESS_RULE', 'ErrorDocument 403 /index.php?status=403');
		}
	}

	/**
	 * Fires once the module is loaded
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function loaded() {
		add_action('template_redirect', __NAMESPACE__ . '\\Hooks::show_error_page');
		add_filter('display_post_states', __NAMESPACE__ . '\\Hooks::add_overview_page_post_state', 10, 2);
	}

	/**
	 * Register admin options defaults
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function register_options_defaults($defaults) {
		$defaults['page-403'] = '0';
		$defaults['page-404'] = '0';

		return $defaults;
	}

	/**
	 * Register admin options tab
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function register_options_tab($tabs) {
		$tabs['general'] = [
			'label' => ''
		];

		return $tabs;
	}

	/**
	 * Register options elements
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function register_options_elements($elements) {
		$elements['general'] = [
			[
				'type' => 'description',
				'description' =>  __('Here you can assign pages that should be displayed in case of an error.', 'f4-error-pages')
			],
			[
				'type' => 'fields',
				'fields' => [
					'page-403' => [
						'type' => 'page',
						'label' => __('Forbidden (403)', 'f4-error-pages')
					],
					'page-404' => [
						'type' => 'page',
						'label' => __('Page not found (404)', 'f4-error-pages')
					],
				],
			],
		];

		return $elements;
	}

	/**
	 * Show assigned error pages
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function show_error_page($template) {
		global $wp_query, $post;

		if($wp_query->is_404()) {
			$error_page_id = null;

			if(isset($_GET['status']) && $_GET['status'] == 403) {
				header('HTTP/1.0 403 Forbidden');
				$error_page_id = (int)Options::get('page-403');
			} else {
				$error_page_id = (int)Options::get('page-404');
			}

			if($error_page_id) {
				$wp_query = null;
				$wp_query = new \WP_Query();
				$wp_query->query('page_id=' . $error_page_id);
				$wp_query->the_post();
				rewind_posts();
			}
		}
	}

	/**
	 * Add the overview page hints to the page list
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 * @param array $post_states An array with all the available post states
	 * @param object $post The current post object
	 * @return array A modified array with all the available post states
	 */
	public static function add_overview_page_post_state($post_states, $post) {
		if($post->post_type == 'page') {
			// Error pages
			$page_403 = (int)Options::get('page-403');
			$page_404 = (int)Options::get('page-404');

			if($post->ID === $page_403) {
				$post_states[] = __('Error 403 Page', 'f4-error-pages');
			} elseif($post->ID === $page_404) {
				$post_states[] = __('Error 404 Page', 'f4-error-pages');
			}
		}

		return $post_states;
	}

	/**
	 * Plugin activation
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function plugin_activation() {
		self::set_default_constants();

		// Add line to htaccess if not already exists
		$htaccess_path = ABSPATH . '.htaccess';

		if(!file_exists($htaccess_path)) {
			return;
		}

		$htaccess_content = file_get_contents($htaccess_path);

		if(strpos($htaccess_content, F4_EP_HTACCESS_RULE) === false) {
			$htaccess_content = F4_EP_HTACCESS_COMMENT . PHP_EOL . F4_EP_HTACCESS_RULE . PHP_EOL . PHP_EOL . $htaccess_content;

			file_put_contents($htaccess_path, $htaccess_content);
		}
	}

	/**
	 * Plugin deactivation
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function plugin_deactivation() {
		self::set_default_constants();

		// Remove line from htaccess if set by this plugin
		$htaccess_path = ABSPATH . '.htaccess';

		if(!file_exists($htaccess_path)) {
			return;
		}

		$htaccess_content = file_get_contents($htaccess_path);

		if(strpos($htaccess_content, F4_EP_HTACCESS_COMMENT . PHP_EOL . F4_EP_HTACCESS_RULE) !== false) {
			$htaccess_content = str_replace(F4_EP_HTACCESS_COMMENT . PHP_EOL . F4_EP_HTACCESS_RULE . PHP_EOL . PHP_EOL, '', $htaccess_content);

			file_put_contents($htaccess_path, $htaccess_content);
		}
	}
}

?>
