<?php

abstract class Ezfc_settings {
	static $type_bool_text          = "bool_text";
	static $type_checkbox           = "checkbox";
	static $type_currencycodes      = "currencycodes";
	static $type_custom_calculation = "custom_calculation";
	static $type_dropdown           = "dropdown";
	static $type_editor             = "editor";
	static $type_email              = "email";
	static $type_file               = "file";
	static $type_input              = "input";
	static $type_input_multiple     = "input_multiple";
	static $type_numbers            = "numbers";
	static $type_password           = "password";
	static $type_radio              = "radio";
	static $type_repeatable_form    = "repeatable_form";
	static $type_table_order        = "table_order";
	static $type_textarea           = "textarea";
	static $type_template_themes    = "template_themes";
	static $type_yesno              = "yesno";

	static $calculate_array      = array(array("operator" => "", "target" => 0, "use_calculated_target_value" => 0, "value" => "", "prio" => 0));
	static $calculate_routine_array = array(array("name" => "", "index" => 0, "calculation_rows" => array(array("operator" => "", "target" => 0, "use_calculated_target_value" => 0, "value" => "", "prio" => 0))));
	static $conditional_array    = array(array("action" => "", "target" => 0, "operator" => "", "value" => ""));
	static $discount_array       = array(array("range_min" => "", "range_max" => "", "operator" => "", "discount_value" => ""));
	static $options_array        = array(array("id" => "", "value" => 0, "text" => "Option"));
	static $options_source_array = array("source" => "options",	"value"  => "");

	public static $elements;
	public static $elements_array = array();

	/**
		form elements
	**/
	static function get_elements() {
		self::$elements = array(
			array(
				"id" => 1,
				"name" => __("Input", "ezfc"),
				"description" => __("Basic input field with no restrictions", "ezfc"),
				"type" => self::$type_input,
				"data" => array(
					"name" => __("Input", "ezfc"),
					"label" => __("Text", "ezfc"),
					"required" => 0,
					"is_number" => 0,
					"is_currency" => 0,
					"conditional" => self::$conditional_array,
					"verify_value" => 1,
					"value" => "",
					"value_attribute" => "",
					"value_external" => "",
					"value_external_listen" => 1,
					"value_http" => "",
					"value_http_json" => "",
					"text_before" => "",
					"text_after" => "",
					"read_only" => 0,
					"placeholder" => "",
					"icon" => "",
					"is_telephone_nr" => 0,
					"custom_regex" => "",
					"custom_error_message" => "",
					"custom_regex_check_first" => 0,
					"custom_filter" => "",
					"max_length" => "",
					"min_length" => "",
					"show_in_email" => 1,
					"show_in_email_label" => "name",
					"show_in_email_cond" => 0,
					"show_in_email_operator" => 0,
					"show_in_email_value" => "",
					"email_text_column_1" => "submission_value",
					"email_text_column_2" => "calculated_value",
					"show_in_live_summary" => 1,
					"description" => "",
					"description_below_label" => "",
					"description_below_input" => "",
					"class" => "",
					"wrapper_class" => "",
					"style" => "",
					"wrapper_style" => "",
					"element_style" => "default",
					"GET" => "",
					"hidden" => 0,
					"keep_value_after_reset" => 0,
					"autocomplete" => 1,
					"read_only" => 0,
					"columns" => 6,
					"group_id" => 0
				),
				"icon" => "fa-pencil-square-o",
				"category" => "basic"
			),
			array(
				"id" => 2,
				"name" => __("Email", "ezfc"),
				"description" => __("Email input field", "ezfc"),
				"type" => self::$type_email,
				"data" => array(
					"name" => __("Email", "ezfc"),
					"label" => __("Email", "ezfc"),
					"required" => 0,
					"verify_value" => 1,
					"use_address" => 1,
					"double_check" => 0,
					"allow_multiple" => 0,
					"value" => "",
					"value_attribute" => "",
					"value_external" => "",
					"value_external_listen" => 1,
					"value_http" => "",
					"value_http_json" => "",
					"read_only" => 0,
					"placeholder" => "",
					"icon" => "",
					"custom_regex" => "",
					"custom_error_message" => "",
					"custom_regex_check_first" => 0,
					"custom_filter" => "",
					"show_in_email" => 1,
					"show_in_email_label" => "name",
					"show_in_email_cond" => 0,
					"show_in_email_operator" => 0,
					"show_in_email_value" => "",
					"email_text_column_1" => "submission_value",
					"email_text_column_2" => "calculated_value",
					"show_in_live_summary" => 0,
					"description" => "",
					"description_below_label" => "",
					"description_below_input" => "",
					"class" => "",
					"wrapper_class" => "",
					"style" => "",
					"wrapper_style" => "",
					"element_style" => "default",
					"GET" => "",
					"hidden" => 0,
					"keep_value_after_reset" => 0,
					"autocomplete" => 1,
					"read_only" => 0,
					"columns" => 6,
					"group_id" => 0
				),
				"icon" => "fa-envelope-o",
				"category" => "basic"
			),
			array(
				"id" => 3,
				"name" => __("Textfield", "ezfc"),
				"description" => __("Large text field", "ezfc"),
				"type" => "textfield",
				"data" => array(
					"name" => __("Textfield", "ezfc"),
					"label" => __("Textfield", "ezfc"),
					"required" => 0,
					"verify_value" => 1,
					"value" => "",
					"value_attribute" => "",
					"value_external" => "",
					"value_external_listen" => 1,
					"value_http" => "",
					"value_http_json" => "",
					"read_only" => 0,
					"placeholder" => "",
					"icon" => "",
					"custom_regex" => "",
					"custom_error_message" => "",
					"custom_regex_check_first" => 0,
					"custom_filter" => "",
					"max_length" => "",
					"min_length" => "",
					"show_in_email" => 1,
					"show_in_email_label" => "name",
					"show_in_email_cond" => 0,
					"show_in_email_operator" => 0,
					"show_in_email_value" => "",
					"email_text_column_1" => "submission_value",
					"email_text_column_2" => "calculated_value",
					"show_in_live_summary" => 0,
					"description" => "",
					"description_below_label" => "",
					"description_below_input" => "",
					"class" => "",
					"wrapper_class" => "",
					"style" => "",
					"wrapper_style" => "",
					"element_style" => "default",
					"GET" => "",
					"hidden" => 0,
					"keep_value_after_reset" => 0,
					"columns" => 6,
					"group_id" => 0
				),
				"icon" => "fa-align-justify",
				"category" => "basic"
			),
			array(
				"id" => 4,
				"name" => __("Dropdown", "ezfc"),
				"description" => __("Dropdown list", "ezfc"),
				"type" => self::$type_dropdown,
				"data" => array(
					"name" => __("Dropdown", "ezfc"),
					"label" => __("Dropdown", "ezfc"),
					"required" => 0,
					"verify_value" => 1,
					"calculate_enabled" => 1,
					"add_to_price" => 1,
					"is_currency" => 1,
					"is_number" => 1,
					"options" => self::$options_array,
					"options_source" => self::$options_source_array,
					"calculate" => self::$calculate_array,
					"calculate_routine" => self::$calculate_routine_array,
					"overwrite_price" => 0,
					"calculate_when_hidden" => 0,
					"precision" => 2,
					"calculate_before" => 0,
					"conditional" => self::$conditional_array,
					"discount_value_type" => "raw",
					"discount_value_apply" => "calculated",
					"discount" => self::$discount_array,
					"discount_show_table" => 0,
					"discount_show_table_indicator" => 1,
					"custom_regex" => "",
					"custom_error_message" => "",
					"custom_regex_check_first" => 0,
					"custom_filter" => "",
					"show_in_email" => 1,
					"show_in_email_label" => "name",
					"show_in_email_cond" => 0,
					"show_in_email_operator" => 0,
					"show_in_email_value" => "",
					"email_text_column_1" => "submission_value",
					"email_text_column_2" => "calculated_value",
					"show_in_live_summary" => 1,
					"description" => "",
					"description_below_label" => "",
					"description_below_input" => "",
					"class" => "",
					"wrapper_class" => "",
					"style" => "",
					"wrapper_style" => "",
					"element_style" => "default",
					"GET" => "",
					"GET_check_option_value" => "index",
					"hidden" => 0,
					"keep_value_after_reset" => 0,
					"columns" => 6,
					"group_id" => 0
				),
				"icon" => "fa-list-ul",
				"category" => "calc"
			),
			array(
				"id" => 5,
				"name" => __("Radio Button", "ezfc"),
				"description" => __("Used for single-choice elements.", "ezfc"),
				"type" => self::$type_radio,
				"data" => array(
					"name" => __("Radio", "ezfc"),
					"label" => __("Radio", "ezfc"),
					"required" => 0,
					"verify_value" => 1,
					"calculate_enabled" => 1,
					"add_to_price" => 1,
					"is_currency" => 1,
					"is_number" => 1,
					"options" => self::$options_array,
					"options_columns" => "",
					//"options_connected" => array("target" => ""),
					"options_source" => self::$options_source_array,
					"options_text_only" => 0,
					"option_add_text_icon" => 0,
					"calculate" => self::$calculate_array,
					"calculate_routine" => self::$calculate_routine_array,
					"overwrite_price" => 0,
					"calculate_when_hidden" => 0,
					"precision" => 2,
					"calculate_before" => 0,
					"conditional" => self::$conditional_array,
					"discount_value_type" => "raw",
					"discount_value_apply" => "calculated",
					"discount" => self::$discount_array,
					"discount_show_table" => 0,
					"discount_show_table_indicator" => 1,
					"custom_regex" => "",
					"custom_error_message" => "",
					"custom_regex_check_first" => 0,
					"custom_filter" => "",
					"show_in_email" => 1,
					"show_in_email_label" => "name",
					"show_in_email_cond" => 0,
					"show_in_email_operator" => 0,
					"show_in_email_value" => "",
					"email_text_column_1" => "submission_value",
					"email_text_column_2" => "calculated_value",
					"show_in_live_summary" => 1,
					"description" => "",
					"description_below_label" => "",
					"description_below_input" => "",
					"max_width" => "",
					"max_height" => "",
					"image_auto_width" => 0,
					"inline" => 0,
					"flexbox" => 0,
					"class" => "",
					"wrapper_class" => "",
					"style" => "",
					"wrapper_style" => "",
					"element_style" => "default",
					"GET" => "",
					"GET_check_option_value" => "index",
					"hidden" => 0,
					"keep_value_after_reset" => 0,
					"columns" => 6,
					"group_id" => 0
				),
				"icon" => "fa-dot-circle-o",
				"category" => "calc"
			),
			array(
				"id" => 6,
				"name" => __("Checkbox", "ezfc"),
				"description" => __("Checky check!", "ezfc"),
				"type" => self::$type_checkbox,
				"data" => array(
					"name" => __("Checkbox", "ezfc"),
					"label" => __("Checkbox", "ezfc"),
					"required" => 0,
					"verify_value" => 1,
					"calculate_enabled" => 1,
					"add_to_price" => 1,
					"is_currency" => 1,
					"is_number" => 1,
					"min_selectable" => 0,
					"max_selectable" => 0,
					"options" => self::$options_array,
					"options_columns" => "",
					"options_source" => self::$options_source_array,
					"options_text_only" => 0,
					"option_add_text_icon" => 0,
					"calculate" => self::$calculate_array,
					"calculate_routine" => self::$calculate_routine_array,
					"overwrite_price" => 0,
					"calculate_when_hidden" => 0,
					"precision" => 2,
					"calculate_before" => 0,
					"conditional" => self::$conditional_array,
					"discount_value_type" => "raw",
					"discount_value_apply" => "calculated",
					"discount" => self::$discount_array,
					"discount_show_table" => 0,
					"discount_show_table_indicator" => 1,
					"custom_regex" => "",
					"custom_error_message" => "",
					"custom_regex_check_first" => 0,
					"custom_filter" => "",
					"show_in_email" => 1,
					"show_in_email_label" => "name",
					"show_in_email_cond" => 0,
					"show_in_email_operator" => 0,
					"show_in_email_value" => "",
					"email_text_column_1" => "submission_value",
					"email_text_column_2" => "calculated_value",
					"show_in_live_summary" => 1,
					"description" => "",
					"description_below_label" => "",
					"description_below_input" => "",
					"max_width" => "",
					"max_height" => "",
					"image_auto_width" => 0,
					"inline" => 0,
					"flexbox" => 0,
					"class" => "",
					"wrapper_class" => "",
					"style" => "",
					"wrapper_style" => "",
					"element_style" => "default",
					"GET" => "",
					"GET_check_option_value" => "index",
					"hidden" => 0,
					"keep_value_after_reset" => 0,
					"columns" => 6,
					"group_id" => 0
				),
				"icon" => "fa-check-circle-o",
				"category" => "calc"
			),
			array(
				"id" => 7,
				"name" => __("Numbers", "ezfc"),
				"description" => __("Numbers only", "ezfc"),
				"type" => self::$type_numbers,
				"data" => array(
					"name" => __("Numbers", "ezfc"),
					"label" => __("Numbers", "ezfc"),
					"required" => 0,
					"verify_value" => 1,
					"calculate_enabled" => 1,
					"add_to_price" => 1,
					"is_currency" => 1,
					"is_number" => 1,
					"factor" => "",
					"value" => "",
					"value_attribute" => "",
					"value_external" => "",
					"value_external_listen" => 1,
					"value_http" => "",
					"value_http_json" => "",
					"min" => "",
					"max" => "",
					"slider" => 0,
					"slider_values" => "",
					"steps_slider" => 1,
					"slider_vertical" => 0,
					"spinner" => 0,
					"steps_spinner" => 1,
					"pips" => 0,
					"steps_pips" => 1,
					"pips_float" => 0,
					"calculate" => self::$calculate_array,
					"calculate_routine" => self::$calculate_routine_array,
					"overwrite_price" => 0,
					"calculate_when_hidden" => 0,
					"price_format" => "",
					"precision" => 2,
					"text_only" => 0,
					"text_before" => "",
					"text_after" => "",
					"calculate_before" => 0,
					"conditional" => self::$conditional_array,
					"discount_value_type" => "raw",
					"discount_value_apply" => "calculated",
					"discount" => self::$discount_array,
					"discount_show_table" => 0,
					"discount_show_table_indicator" => 1,
					"custom_regex" => "",
					"custom_error_message" => "",
					"custom_regex_check_first" => 0,
					"custom_filter" => "",
					"read_only" => 0,
					"placeholder" => "",
					"icon" => "",
					"max_length" => "",
					"min_length" => "",
					"custom_regex" => "",
					"custom_error_message" => "",
					"custom_regex_check_first" => 0,
					"custom_filter" => "",
					"show_in_email" => 1,
					"show_in_email_label" => "name",
					"show_in_email_cond" => 0,
					"show_in_email_operator" => 0,
					"show_in_email_value" => "",
					"email_text_column_1" => "submission_value",
					"email_text_column_2" => "calculated_value",
					"show_in_live_summary" => 1,
					"image" => "",
					"alt" => "",
					"description" => "",
					"description_below_label" => "",
					"description_below_input" => "",
					"class" => "",
					"wrapper_class" => "",
					"style" => "",
					"wrapper_style" => "",
					"element_style" => "default",
					"GET" => "",
					"hidden" => 0,
					"keep_value_after_reset" => 0,
					"autocomplete" => 1,
					"read_only" => 0,
					"columns" => 6,
					"group_id" => 0
				),
				"icon" => "fa-html5",
				"category" => "calc"
			),
			array(
				"id" => 25,
				"name" => __("Group", "ezfc"),
				"description" => __("Group", "ezfc"),
				"type" => "group",
				"data" => array(
					"name" => __("Group", "ezfc"),
					"collapsible" => 0,
					"expanded" => 1,
					"title" => __("Collapse title", "ezfc"),
					"group_center" => 0,
					"group_flexbox" => 0,
					"group_flexbox_align_items" => "center",
					"group_flexbox_columns" => "",
					"wrapper_class" => "",
					"wrapper_style" => "",
					"element_style" => "default",
					"hidden" => 0,
					"columns" => 6,
					"group_id" => 0
				),
				"icon" => "fa-folder",
				"category" => "other"
			),
			array(
				"id" => 37,
				"name" => __("Yes/No", "ezfc"),
				"description" => __("Simple yes/no single choice radio button.", "ezfc"),
				"type" => self::$type_radio,
				"data" => array(
					"name" => __("Yes/No", "ezfc"),
					"label" => __("Yes/No", "ezfc"),
					"required" => 0,
					"verify_value" => 1,
					"calculate_enabled" => 1,
					"add_to_price" => 1,
					"is_currency" => 1,
					"is_number" => 1,
					"options" => array(
						array("id" => "yes", "value" => "", "text" => __("Yes", "ezfc"), "icon" => "fa-check"),
						array("id" => "no", "value" => "", "text" => __("No", "ezfc"), "icon" => "fa-times"),
					),
					"options_source" => self::$options_source_array,
					"options_text_only" => 1,
					"option_add_text_icon" => 1,
					"calculate" => self::$calculate_array,
					"calculate_routine" => self::$calculate_routine_array,
					"overwrite_price" => 0,
					"calculate_when_hidden" => 0,
					"precision" => 2,
					"calculate_before" => 0,
					"conditional" => self::$conditional_array,
					"discount_value_type" => "raw",
					"discount_value_apply" => "calculated",
					"discount" => self::$discount_array,
					"discount_show_table" => 0,
					"discount_show_table_indicator" => 1,
					"custom_regex" => "",
					"custom_error_message" => "",
					"custom_regex_check_first" => 0,
					"custom_filter" => "",
					"show_in_email" => 1,
					"show_in_email_label" => "name",
					"show_in_email_cond" => 0,
					"show_in_email_operator" => 0,
					"show_in_email_value" => "",
					"email_text_column_1" => "submission_value",
					"email_text_column_2" => "calculated_value",
					"show_in_live_summary" => 1,
					"description" => "",
					"description_below_label" => "",
					"description_below_input" => "",
					"max_width" => "",
					"max_height" => "",
					"image_auto_width" => 1,
					"inline" => 1,
					"flexbox" => 0,
					"class" => "",
					"wrapper_class" => "",
					"style" => "",
					"wrapper_style" => "",
					"element_style" => "default",
					"GET" => "",
					"GET_check_option_value" => "index",
					"hidden" => 0,
					"keep_value_after_reset" => 0,
					"columns" => 6,
					"group_id" => 0
				),
				"icon" => "fa-dot-circle-o",
				"category" => "predefined"
			),
		);

		// map to ID array
		self::$elements_array = array();
		foreach (self::$elements as $element) {
			self::$elements_array[$element["id"]] = $element;
		}

		return json_decode(json_encode(self::$elements));
	}

	/**
		global settings
	**/
	static function get_global_settings($flat = false) {
		require_once(EZFC_PATH . "inc/php/settings/global-settings.php");

		$settings = ezfc_get_global_settings();
		$settings = apply_filters("ezfc_global_settings", $settings);

		// get values
		foreach ($settings as $cat => &$settings_cat) {
			foreach ($settings_cat as $name => &$setting) {
				$default = isset($setting["default"]) ? $setting["default"] : "";

				$setting["value"] = get_option("ezfc_{$name}", $default);
			}
		}

		if ($flat) {
			$settings = self::flatten($settings);
		}

		return $settings;
	}

	/**
		update global settings
	**/
	public static function update_global_settings($submitted_values) {
		$settings = self::get_global_settings(true);

		// css array builder
		$css_builder = new EZ_CSS_Builder(".ezfc-wrapper");

		$return_message = array();

		foreach ($settings as $setting_key => $setting) {
			if (!isset($submitted_values[$setting_key])) {
				// set value for empty bool settings
				if ($setting["type"] == "yesno") {
					$submitted_values[$setting_key] = 0;
				}
				// skip if not set
				else continue;
			}

			// get post value
			$value = $submitted_values[$setting_key];

			if (is_array($value)) {
				$value = serialize($value);
			}
			else {
				$value = self::validate_option($setting, $value, $setting_key);

				if (is_array($value) && !empty($value["error"])) {
					$return_message[] = array("error" => $value["error"], "id" => $setting_key);
					continue;
				}
			}

			// update wp option
			update_option("ezfc_{$setting_key}", $value);

			// check for css
			if (!empty($setting["css"]) && !empty($value)) {
				$css_builder->add_css($setting["css"], $value);
			}
		}

		// build css output
		$css_output = $css_builder->get_output();
		update_option("ezfc_css_custom_styling", $css_output);

		do_action("ezfc_global_settings_after_update", $submitted_values);

		return $return_message;
	}

	/**
		form options
	**/
	static function get_form_options($flat = false, $global = false) {
		require_once(EZFC_PATH . "inc/php/settings/form-options.php");

		$settings = ezfc_get_form_options($global);
		$settings = apply_filters("ezfc_form_options", $settings, $global);

		if ($flat) {
			$settings = self::flatten($settings);
		}

		return $settings;
	}

	/**
		prepare form elements for export, e.g. replace target IDs with positions
	**/
	static function form_elements_prepare_export($form_elements = array()) {
		// replace calculate positions with target ids
		$template_elements_indexed = self::array_index_key($form_elements, "id");

		foreach ($template_elements_indexed as $id => &$element) {
			$element->id = $element->position;

			if (!property_exists($element, "data")) continue;

			$element_data = json_decode($element->data);

			// calculate elements
			if (property_exists($element_data, "calculate") &&
				!empty($element_data->calculate) &&
				Ezfc_Functions::is_countable($element_data->calculate) &&
				count($element_data->calculate) > 0) {

				// convert object to array
				if (!is_array($element_data->calculate)) {
					$element_data->calculate = (array) $element_data->calculate;
				}

				foreach ($element_data->calculate as &$calc_value) {
					if (empty($calc_value->target)) continue;

					if (!isset($template_elements_indexed[$calc_value->target])) continue;

					$target_element = $template_elements_indexed[$calc_value->target];
					$calc_id = $target_element->position;

					$calc_value->target = $calc_id;
				}
			}

			// conditional elements
			if (property_exists($element_data, "conditional") &&
				!empty($element_data->conditional) &&
				Ezfc_Functions::is_countable($element_data->conditional) &&
				count($element_data->conditional) > 0) {

				$element_data->conditional = self::prepare_export_conditional($element_data->conditional, $template_elements_indexed);
			}

			// set elements
			if (property_exists($element_data, "set") &&
				!empty($element_data->set) &&
				Ezfc_Functions::is_countable($element_data->set) &&
				count($element_data->set) > 0) {
				// convert object to array
				if (!is_array($element_data->set)) {
					$element_data->set = (array) $element_data->set;
				}

				foreach ($element_data->set as &$set_element) {
					if (empty($set_element->target)) continue;

					if (!isset($template_elements_indexed[$set_element->target])) continue;

					$target_element = $template_elements_indexed[$set_element->target];
					$cond_id = $target_element->position;

					$set_element->target = $cond_id;
				}
			}

			// groups
			if (!empty($element_data->group_id)) {
				if (isset($template_elements_indexed[$element_data->group_id])) {
					$target_element = $template_elements_indexed[$element_data->group_id];
					$target_id      = $target_element->position;

					$element_data->group_id = $target_id;
				}
			}

			// show in email target
			if (!empty($element_data->show_in_email_cond) && is_array($element_data->show_in_email_cond)) {
				foreach ($element_data->show_in_email_cond as $i => $cond) {
					if (!isset($template_elements_indexed[$cond])) continue;
				
					$target_element = $template_elements_indexed[$cond];
					$target_id      = $target_element->position;
					$element_data->show_in_email_cond[$i] = $target_id;
				}
			}

			// matrix
			if (!empty($element_data->matrix)) {
				// target elements
				if (!empty($element_data->matrix->target_elements)) {
					foreach ($element_data->matrix->target_elements as $i => $target_element_matrix) {
						if (!isset($template_elements_indexed[$target_element_matrix])) continue;

						$target_element = $template_elements_indexed[$target_element_matrix];
						$target_id      = $target_element->position;
						$element_data->matrix->target_elements[$i] = $target_id;
					}
				}

				// matrix conditions
				if (!empty($element_data->matrix->conditions)) {
					foreach ($element_data->matrix->conditions as $i => $matrix_condition) {
						if (empty($matrix_condition->elements) || !is_array($matrix_condition->elements)) continue;
						
						foreach ($matrix_condition->elements as $mi => $matrix_condition_element) {
							if (!isset($template_elements_indexed[$matrix_condition_element])) continue;
							
							$target_element = $template_elements_indexed[$matrix_condition_element];
							$target_id      = $target_element->position;
							$element_data->matrix->conditions[$i]->elements[$mi] = $target_id;
						}
					}
				}
			}

			$element->data = json_encode($element_data);
		}

		return $template_elements_indexed;
	}

	static function prepare_export_conditional($conditional_array, $template_elements_indexed) {
		// convert object to array
		if (!is_array($conditional_array)) {
			$conditional_array = (array) $conditional_array;
		}

		foreach ($conditional_array as &$cond_value) {
			if (empty($cond_value->target)) continue;

			if (!isset($template_elements_indexed[$cond_value->target])) continue;

			$target_element = $template_elements_indexed[$cond_value->target];
			$cond_id = $target_element->position;
			$cond_value->target = $cond_id;

			// compare value first
			if (property_exists($cond_value, "compare_value_first") && isset($template_elements_indexed[$cond_value->compare_value_first])) {
				$cond_value->compare_value_first = $template_elements_indexed[$cond_value->compare_value_first]->position;
			}

			// chain target
			if (property_exists($cond_value, "compare_value") && is_array($cond_value->compare_value) && Ezfc_Functions::is_countable($cond_value->compare_value) && count($cond_value->compare_value) > 0) {
				foreach ($cond_value->compare_value as &$chain_target_id) {
					if (isset($template_elements_indexed[$chain_target_id])) {
						$chain_target_id = $template_elements_indexed[$chain_target_id]->position;
					}
				}
			}
		}

		return $conditional_array;
	}

	static function prepare_import_conditional($conditional_array, $template_elements_indexed, $is_global = false) {
		// convert object to array
		if (!is_array($conditional_array)) {
			$conditional_array = (array) $conditional_array;
		}

		foreach ($conditional_array as &$cond_value) {
			if (empty($cond_value->target)) continue;

			if (!isset($template_elements_indexed[$cond_value->target])) continue;

			$target_element = $template_elements_indexed[$cond_value->target];
			$cond_id = $target_element->id;
			$cond_value->target = $cond_id;

			// compare value first
			if (property_exists($cond_value, "compare_value_first") && isset($template_elements_indexed[$cond_value->compare_value_first])) {
				$cond_value->compare_value_first = $template_elements_indexed[$cond_value->compare_value_first]->id;
			}

			// chain target
			if (property_exists($cond_value, "compare_value") && is_array($cond_value->compare_value) && count($cond_value->compare_value) > 0) {
				foreach ($cond_value->compare_value as &$chain_target_id) {
					if (isset($template_elements_indexed[$chain_target_id])) {
						$chain_target_id = $template_elements_indexed[$chain_target_id]->id;
					}
				}
			}
		}

		return $conditional_array;
	}

	static function flatten($settings) {
		$settings_flat = array();

		foreach ($settings as $cat => $settings_cat) {
			foreach ($settings_cat as $name => $setting) {
				$tmp_id = "";
				
				if (is_array($setting)) {
					if (!empty($setting["id"])) $tmp_id = $setting["id"];
					else if (!empty($setting["name"])) $tmp_id = $setting["name"];
					else $tmp_id = $name;
				}
				else if (is_object($setting)) {
					if (!empty($setting->id)) $tmp_id = $setting->id;
					else if (!empty($setting->name)) $tmp_id = $setting->name;
					else $tmp_id = $name;
				}

				$settings_flat[$tmp_id] = $setting;
			}
		}

		return $settings_flat;
	}

	// wrapper for deprecated extensions / customizations
	public static function array_index_key($array, $key) {
		return Ezfc_Functions::array_index_key($array, $key);
	}

	public static function get_conditional_operators() {
		return array(
			"0" => " ",
			"gr" => ">",
			"gre" => ">=",
			"less" => "<",
			"lesse" => "<=",
			"equals" => "=",
			"not" => __("not", "ezfc"),
			"between" => __("between", "ezfc"),
			"not_between" => __("not between", "ezfc"),
			"hidden" => __("is hidden", "ezfc"),
			"visible" => __("is visible", "ezfc"),
			"mod0" => __("%x = 0", "ezfc"),
			"mod1" => __("%x != 0", "ezfc"),
			"bit_and" => __("bitwise AND", "ezfc"),
			"bit_or" => __("bitwise OR", "ezfc"),
			"empty" => __("empty", "ezfc"),
			"notempty" => __("not empty", "ezfc"),
			"in" => __("in", "ezfc"),
			"not_in" => __("not in", "ezfc"),
			"once" => __("once", "ezfc")
		);
	}

	public static function get_default_element_value($element_id, $key) {
		if (empty(self::$elements)) self::get_elements();

		if (!isset(self::$elements_array[$element_id]) || !isset(self::$elements_array[$element_id]["data"][$key])) return false;

		return self::$elements_array[$element_id]["data"][$key];
	}

	/**
		validate options
	**/
	public static function validate_option($setting = array(), $value = "", $id = 0) {
		// invalid function call
		if (!is_array($setting)) wp_die(__("Function validate_option was called incorrectly.", "ezfc"));
		// do not mess with arrays
		if (is_array($value)) return $value;

		// set to input by default
		$setting["type"] = empty($setting["type"]) ? "input" : $setting["type"];

		switch ($setting["type"]) {
			case "yesno":
				$value = empty($value) ? 0 : 1;
			break;

			case "email":
				// normalize
				$emails = array($value);

				// multiple
				if (strpos($value, ",") !== false) {
					$emails = explode(",", $value);
				}

				foreach ($emails as $email) {
					$email = trim($email);
					
					if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
						return self::return_option_error(__("Please enter a valid email address.", "ezfc"), $id);
					}
				}
			break;

			case "email_sender_name":
				$invalid = false;
				$sendername = trim($value);

				// disable check for dynamic values
				if (!empty($sendername) && strpos($sendername, "{{") === false) {
					$email_split_open = explode("<", $value);

					if (count($email_split_open) < 2) {
						// check for email only
						if (!filter_var($email_split_open[0], FILTER_VALIDATE_EMAIL)) {
							$invalid = true;
						}
					}
					else {
						$email_split_close = explode(">", $email_split_open[1]);

						if (count($email_split_close) < 2) {
							$invalid = true;
						}
						else {
							$email_check = $email_split_close[0];

							if (empty($email_check) || !filter_var($email_check, FILTER_VALIDATE_EMAIL)) {
								$invalid = true;
							}
						}
					}
				}

				if ($invalid) {
					return self::return_option_error(sprintf(__("Invalid syntax. Please use the following syntax: %s", "ezfc"), "Sendername &lt;sender@mail.com&gt;"), $id);
				}
			break;

			default:
				// no action
				$value = stripslashes($value);
			break;
		}

		return $value;
	}

	public static function return_option_error($msg, $id = 0) {
		return Ezfc_Functions::send_message("error", $msg, $id);
	}
}