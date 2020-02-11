<?php

namespace F4\EP\Core\Options;

/**
 * Core\Options Helpers
 *
 * Helpers for the Core\Options module
 *
 * @since 1.0.0
 * @package F4\EP\Core
 */
class Helpers {
	public static $tabs = null;
	public static $defaults = null;
	public static $elements = null;
	public static $options = null;

	/**
	 * Check if current page is options page
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function is_option_page() {
		return isset(get_current_screen()->base) && get_current_screen()->base === 'settings_page_' . F4_EP_SLUG;
	}

	/**
	 * Get admin tabs
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function get_tabs() {
		if(self::$tabs === null) {
			self::$tabs = apply_filters('F4/EP/register_options_tabs', []);
		}

		return self::$tabs;
	}

	/**
	 * Get defaults
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function get_defaults() {
		if(self::$defaults === null) {
			self::$defaults = apply_filters('F4/EP/register_options_defaults', []);
		}

		return self::$defaults;
	}

	/**
	 * Get elements
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function get_elements($tab) {
		if(self::$elements === null) {
			self::$elements = apply_filters('F4/EP/register_options_elements', []);
		}

		$elements = self::$elements;

		if(isset(self::$elements[$tab])) {
			$elements = self::$elements[$tab];
		}

		return $elements;
	}

	/**
	 * Get options
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function get($name = null) {
		if(self::$options === null) {
			// Get default values
			$defaults = self::get_defaults();

			// Get saved options
			$options = get_option(F4_EP_CORE_OPTION_NAME, $defaults);

			if(!is_array($options)) {
				$options = array();
			}

			$options = wp_parse_args(
				$options,
				$defaults
			);

			self::$options = apply_filters('F4/EP/get_options', $options, $name);
		}

		if($name) {
			$setting = isset(self::$options[$name]) ? self::$options[$name] : null;
			$setting = apply_filters('F4/EP/get_option', $setting, self::$options, $name);
			return $setting;
		}

		return self::$options;
	}
}

?>
