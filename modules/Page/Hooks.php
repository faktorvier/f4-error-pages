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
		add_action('F4/EP/Core/set_constants', __NAMESPACE__ . '\\Hooks::set_default_constants', 99);
		add_filter('F4/EP/register_options_tabs', __NAMESPACE__ . '\\Hooks::register_options_tab', 5);
		add_filter('F4/EP/register_options_defaults', __NAMESPACE__ . '\\Hooks::register_options_defaults');
		add_filter('F4/EP/register_options_elements', __NAMESPACE__ . '\\Hooks::register_options_elements');
		add_action('F4/EP/Core/loaded', __NAMESPACE__ . '\\Hooks::loaded');
	}

	/**
	 * Sets the module default constants
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function set_default_constants() {

	}

	/**
	 * Register admin options defaults
	 *
	 * @since 3.0.0
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
	 * @since 3.0.0
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
	 * @since 3.0.0
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
	 * @since 3.0.0
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
}

?>
