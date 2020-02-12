<?php

namespace F4\EP\Core\Options;

use F4\EP\Core\Helpers as Core;
use F4\EP\Core\Options\Helpers as Helpers;

/**
 * Core\Options hooks
 *
 * Hooks for the Core\Options module
 *
 * @since 1.0.0
 * @package F4\EP\Core\Options
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
		if(!defined('F4_EP_CORE_OPTION_NAME')) {
			define('F4_EP_CORE_OPTION_NAME', F4_EP_SLUG . '-settings');
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
		add_action('admin_head', __NAMESPACE__ . '\\Hooks::add_admin_styles');
		add_action('admin_menu', __NAMESPACE__ . '\\Hooks::register_options_page', 99);
		add_action('admin_init', __NAMESPACE__ . '\\Hooks::register_options');
		add_filter('plugin_action_links_' . F4_EP_BASENAME, __NAMESPACE__ . '\\Hooks::add_settings_link_to_plugin_list');
		add_filter('pre_update_option_' . F4_EP_CORE_OPTION_NAME, __NAMESPACE__ . '\\Hooks::before_update_option', 10, 3);
		add_filter('update_option_' . F4_EP_CORE_OPTION_NAME, __NAMESPACE__ . '\\Hooks::after_update_option', 10, 3);
	}

	/**
	 * Add admin styles
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function add_admin_styles() {
		echo '<style>';
			echo '
				.' . F4_EP_SLUG . '-submenu-item {
				display: flex;
				align-items: center;
				white-space: nowrap;
			}

			.' . F4_EP_SLUG . '-submenu-item .f4-icon {
				flex: 0 0 auto;
				width: 1em;
				height: 1em;
				margin-right: 0.4em;
				fill: currentColor;
			}
			';

			if(Helpers::is_option_page()) {
				echo '
				.form-table--compact tr:not(:last-child) th,
				.form-table--compact tr:not(:last-child) td {
					padding-bottom: 0;
				}

				.f4-options-sidebar {
					display: none;
				}

				.f4-options-sidebar-link {
					display: block;
					font-size: 0;
					line-height: 0;
				}

				@media screen and (min-width: 851px) {
					.wrap {
						display: flex;
					}

					.f4-options-form {
						flex: 1 1 100%;
						padding-right: 20px;
					}

					.f4-options-sidebar {
						display: block;
						padding-top: 12px;
						flex: 0 0 300px;
					}
				}
				';
			}
		echo '</style>';
	}

	/**
	 * Register options page
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function register_options_page() {
		$submenu_label = '
		<span class="' . F4_EP_SLUG . '-submenu-item">
			<svg class="f4-icon" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"width="75px" height="100px" viewBox="0 0 75 100">
				<path d="M74.295,21.04c0,1.38-1.122,2.504-2.502,2.504H54.259c-1.384,0-2.504-1.124-2.504-2.504V3.504
					c0-1.379,1.12-2.504,2.504-2.504h17.534c1.38,0,2.502,1.125,2.502,2.504V21.04z"/>
				<path d="M74.295,46.562c0,1.384-1.122,2.506-2.502,2.506H54.259c-1.384,0-2.504-1.122-2.504-2.506V29.029
					c0-1.382,1.12-2.504,2.504-2.504h17.534c1.38,0,2.502,1.122,2.502,2.504V46.562z"/>
				<path d="M74.295,72.086c0,1.384-1.122,2.506-2.502,2.506H54.259c-1.384,0-2.504-1.122-2.504-2.506V54.557
					c0-1.387,1.12-2.506,2.504-2.506h17.534c1.38,0,2.502,1.119,2.502,2.506V72.086z"/>
				<path d="M48.769,46.562c0,1.384-1.12,2.506-2.502,2.506H28.733c-1.384,0-2.504-1.122-2.504-2.506V29.029
					c0-1.382,1.12-2.504,2.504-2.504h17.533c1.382,0,2.502,1.122,2.502,2.504V46.562z"/>
				<path d="M48.769,72.086c0,1.384-1.12,2.506-2.502,2.506H28.733c-1.384,0-2.504-1.122-2.504-2.506V54.557
					c0-1.387,1.12-2.506,2.504-2.506h17.533c1.382,0,2.502,1.119,2.502,2.506V72.086z"/>
				<path d="M23.247,72.086c0,1.384-1.124,2.506-2.503,2.506H3.21c-1.384,0-2.505-1.122-2.505-2.506V54.557
					c0-1.387,1.122-2.506,2.505-2.506h17.533c1.379,0,2.503,1.119,2.503,2.506V72.086z"/>
				<path d="M53.833,98.412c-1.086,1.085-2.078,0.581-2.078-0.799V80.077c0-1.38,1.12-2.501,2.504-2.501h17.534
					c1.38,0,1.768,1.106,0.798,2.075L53.833,98.412z"/>
				<path d="M46.691,2.708c1.084-1.087,2.077-0.583,2.077,0.796v17.534c0,1.382-1.12,2.506-2.502,2.506H28.733
					c-1.384,0-1.771-1.107-0.799-2.08L46.691,2.708z"/>
				<path d="M21.167,28.229c1.086-1.085,2.08-0.582,2.08,0.8v17.532c0,1.384-1.124,2.506-2.503,2.506H3.21
					c-1.384,0-1.773-1.107-0.801-2.078L21.167,28.229z"/>
			</svg>

			' .  __('Error Pages', 'f4-error-pages') . '
		</span>
		';

		add_options_page(
			__('Error Pages Settings', 'f4-error-pages'),
			$submenu_label,
			'manage_options',
			F4_EP_SLUG,
			function() {
				include F4_EP_PATH . 'modules/Core/Options/views/admin-options.php';
			}
		);
	}

	/**
	 * Register settings for options page
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function register_options() {
		register_setting(
			F4_EP_CORE_OPTION_NAME,
			F4_EP_CORE_OPTION_NAME
		);
	}

	/**
	 * Add settings link to plugin list
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function add_settings_link_to_plugin_list($links) {
		array_push(
			$links,
			'<a href="' . admin_url('options-general.php?page=' . F4_EP_SLUG) . '">' . __('Settings') . '</a>'
		);

		return $links;
	}

	/**
	 * Manipulate option before its saved in the db
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function before_update_option($value, $old_value, $option) {
		foreach(Helpers::$defaults as $option_name => $option_default) {
			if(!isset($value[$option_name])) {
				$value[$option_name] = '0';
			}
		}

		return $value;
	}

	/**
	 * After the option has been updated
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function after_update_option($old_value, $value, $option) {
		$changed = [];

		foreach($value as $option_name => $option_value) {
			if(!isset($old_value[$option_name]) || $option_value !== $old_value[$option_name]) {
				$changed[] = $option_name;
			}
		}

		do_action('F4/EP/after_update_option', $old_value, $value, $changed);
	}
}

?>
