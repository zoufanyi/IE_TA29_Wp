<?php

class Ezfc_Settings_Element_Options_Wrapper {
	private static $_instance = null;
	public $element_options = array();

	public function __construct() {
		// require base class
		require_once(EZFC_PATH . "inc/php/settings/element-options/element-option.php");

		$element_options_load = array(
			"minDate",
			"maxDate",
			"price_format",
			"repeatable_form_id",
		);

		// register element option classes
		foreach ($element_options_load as $element_option_name) {
			$this->load_element_option($element_option_name);
		}
	}

	public function load_element_option($element_option_name) {
		if (isset($this->element_options[$element_option_name])) return;

		$class_name = "Ezfc_Settings_Element_Option_{$element_option_name}";
		$class_file = sanitize_file_name($element_option_name);
		$file       = EZFC_PATH . "inc/php/settings/element-options/{$class_file}.php";

		if (!file_exists($file)) return;

		require_once($file);
		$this->element_options[$element_option_name] = new $class_name();
	}

	public function get_js_vars() {
		$js_vars = array();

		foreach ($this->element_options as $element_option_name => $element_option) {
			$js_vars[$element_option_name] = $element_option->get_js_vars();
		}

		return $js_vars;
	}

	// instance
	public static function instance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __clone() {}
	public function __wakeup() {}
}