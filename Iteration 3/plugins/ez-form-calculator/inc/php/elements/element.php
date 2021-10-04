<?php

class Ezfc_Element {
	// frontend class
	public $frontend;
	// form options
	public $options;
	// counter
	public $counter = 0;
	// element object
	public $element;
	// element id
	public $e_id;
	// form object
	public $form;
	// form element id
	public $id;
	// parent element id
	public $parent_id = 0;
	// form element data
	public $data;
	// output
	public $output = array();
	// submission value for summary/emails
	public $submission_value = "";
	public $calculated_submission_value = 0;

	public function __construct($form, $element, $id = null, $type = "input") {
		$this->frontend = Ezfc_frontend::instance();

		// form
		$this->form = $form;
		// element
		$this->element = $element;
		// element id
		$this->e_id = $element->e_id;
		// form element id
		$this->id = $id;
		// element type
		$this->type = $type;

		// default vars
		$this->add_vars            = array();
		$this->element_css_classes = "";
		$this->element_js_vars     = array();
		$this->step                = false;
		$this->trigger_ids         = array();

		if (property_exists($element, "data")) {
			$element_data = json_decode($element->data);
			$this->set_element_data($element_data);
		}
	}

	// called after data initialization
	public function init() {}

	// called before form element output
	public function before_form_output() {}
	// called before form element output is built
	public function before_form_final_output() {}

	public function add_condition($condition_array = array()) {
		$condition_array = array_merge(array(
			"action"              => "",
			"operator"            => "",
			"compare_value_first" => 0,
			"chain"               => array(
				"compare_target" => array(""),
				"operator"       => array(""),
				"value"          => array("")
			),
			"target"              => "",
			"target_value"        => "",
			"notoggle"            => array(0),
			"option_index_value"  => array(""),
			"redirects"           => array(""),
			"row_operator"        => array(0),
			"use_factor"          => array(0),
			"values"              => array(""),
		), $condition_array);

		// use conditional count
		$conditional_index = count($this->element_js_vars["conditional"]["action"]);

		$this->element_js_vars["conditional"]["action"][$conditional_index]              = $condition_array["action"];
		$this->element_js_vars["conditional"]["operator"][$conditional_index]            = $condition_array["operator"];
		$this->element_js_vars["conditional"]["compare_value_first"][$conditional_index] = $condition_array["compare_value_first"];
		$this->element_js_vars["conditional"]["chain"][$conditional_index]               = $condition_array["chain"];
		$this->element_js_vars["conditional"]["target"][$conditional_index]              = $condition_array["target"];
		$this->element_js_vars["conditional"]["target_value"][$conditional_index]        = $condition_array["target_value"];
		$this->element_js_vars["conditional"]["notoggle"][$conditional_index]            = $condition_array["notoggle"];
		$this->element_js_vars["conditional"]["option_index_value"][$conditional_index]  = $condition_array["option_index_value"];
		$this->element_js_vars["conditional"]["redirects"][$conditional_index]           = $condition_array["redirects"];
		$this->element_js_vars["conditional"]["row_operator"][$conditional_index]        = $condition_array["row_operator"];
		$this->element_js_vars["conditional"]["use_factor"][$conditional_index]          = $condition_array["use_factor"];
		$this->element_js_vars["conditional"]["values"][$conditional_index]              = $condition_array["values"];
	}

	public function add_trigger_id($trigger_id = 0) {
		if (!$trigger_id) $trigger_id = $this->id;

		$this->trigger_ids[$this->id][] = $trigger_id;
	}

	public function get_data_value($key) {
		$default = Ezfc_settings::get_default_element_value($this->e_id, $key);

		return Ezfc_Functions::get_object_value($this->data, $key, $default);
	}

	public function get_element_data() {
		return $this->data;
	}

	// id will be changed when being added to forms more than once.
	public function get_id($get_real_id = false) {
		$id = $this->id;

		// todo
		if ($this->counter > 0 && !$get_real_id) {
			$id = "{$this->id}_{$this->counter}";
		}

		return $id;
	}

	public function get_input_type() {
		$input_type = "text";

		if (!empty($this->data->is_number)) {
			$input_type = "tel";
		}
		else if (!empty($this->data->input_type)) {
			$input_type = $this->data->input_type;
		}

		return $input_type;
	}

	// returns option value
	public function get_option_value($option, $index) {
		$value = "";

		if (property_exists($option, "value")) {
			$value = $option->value;
		}

		$value = $this->get_value_special_fields($value);

		$value = str_replace("{{n}}", ($index + 1), $value);
		$value = trim($value);

		return $value;
	}

	// returns a test value to be used for test submissions
	public function get_test_value() {
		// number
		if (Ezfc_Functions::get_object_value($this->data, "is_number", 0) == 1 || Ezfc_Functions::get_object_value($this->data, "is_currency", 0) == 1) {
			$min = rand(0, 9);
			$max = rand(10, 19);

			if (property_exists($this->data, "min") && property_exists($this->data, "max")) {
				$min_raw = Ezfc_Functions::get_object_value($this->data, "min", 0);
				$max_raw = Ezfc_Functions::get_object_value($this->data, "max", 100);

				if (Ezfc_Functions::empty_string($min_raw)) $min_raw = $min;
				if (Ezfc_Functions::empty_string($max_raw)) $max_raw = $max;
			}

			$min = (float) $min;
			$max = (float) $max;

			return rand($min, $max);
		}

		return rand(100, 999);
	}

	public function get_trigger_ids($trigger_ids = array()) {
		return array_replace($trigger_ids, $this->trigger_ids);
	}

	public function set_counter($counter) {
		$this->counter = (int) $counter;
	}

	public function set_element_data($data) {
		$this->data = $data;
	}

	public function set_form($form) {
		$this->form = $form;
	}

	public function set_form_wrapper($form_wrapper) {
		$this->form_wrapper = $form_wrapper;
	}

	public function set_form_options($options) {
		$this->options = $options;
	}

	// frontend output
	public function prepare_output($options, $add_vars = array()) {
		$this->options = $options;

		$output_id = $this->get_id();

		// wrapper id
		$this->output["element_id"] = "ezfc_element-{$output_id}";
		// input id
		$this->output["element_child_id"] = $this->output["element_id"] . "-child";
		// input name
		$this->output["element_name"] = $this->get_element_input_name();

		// additional vars
		$this->add_vars = $add_vars;

		// prepare conditional
		$this->prepare_conditional();
		// prepare discounts
		$this->prepare_discounts();
		// check if required
		$this->prepare_required();
		// prepare label
		$this->prepare_label();
		// prepare factor value
		$this->prepare_factor();
		// prepare styles
		$this->prepare_styles();
		// prepare value
		$this->prepare_value();
	}

	public function get_element_input_name() {
		$id = $this->get_id();

		$element_input_name = $this->options["hard_submit"] == 1 ? "ezfc_element[{$this->data->name}]" : "ezfc_element[{$id}]";

		if ($this->counter > 0 && $this->parent_id) {
			$counter_input = $this->counter - 1;
			// repeatable form id
			$element_input_name = "ezfc_element[{$this->parent_id}][{$this->id}][{$counter_input}]";
		}

		return $element_input_name;
	}

	public function prepare_conditional() {
		// conditional values
		if (!Ezfc_conditional::has_conditional_rows($this->data)) return;

		$data_conditional_output = Ezfc_conditional::prepare_conditional_js($this->data->conditional);
		$this->element_js_vars["conditional"] = $data_conditional_output;

		// triggers
		foreach ($this->data->conditional as $c => $conditional_row) {
			$compare_value = property_exists($conditional_row, "compare_value") ? $conditional_row->compare_value : "";

			// add target to trigger list
			if (!empty($conditional_row->target)) {
				$this->trigger_ids[$this->get_id()][] = $conditional_row->target;
			}

			// conditional chains
			if (property_exists($conditional_row, "operator_chain")) {
				// add to trigger ids
				if (is_array($compare_value)) {
					foreach ($compare_value as $target_id) {
						$this->trigger_ids[$this->get_id()][] = $target_id;

						if (isset($this->trigger_ids[$target_id])) {
							$this->trigger_ids[$this->get_id()][] = $this->get_id();
						}
					}
				}
			}
		}
	}

	public function prepare_discounts() {
		if (!property_exists($this->data, "discount") || !Ezfc_Functions::is_countable($this->data->discount) || count($this->data->discount) < 1) return;

		// discount
		$data_discount_output = array(
			"range_min" => array(),
			"range_max" => array(),
			"operator"  => array(),
			"values"    => array()
		);
		
		foreach ($this->data->discount as $discount) {
			$data_discount_output["range_min"][] = property_exists($discount, "range_min") ? $discount->range_min : "";
			$data_discount_output["range_max"][] = property_exists($discount, "range_max") ? $discount->range_max : "";
			$data_discount_output["operator"][]  = property_exists($discount, "operator") ? $discount->operator : "";
			$data_discount_output["values"][]    = property_exists($discount, "discount_value") ? $discount->discount_value : "";
		}

		$this->element_js_vars["discount"] = $data_discount_output;

		// normalize my stupid typo - oh dear
		$discount_value_type = Ezfc_Functions::get_object_value($this->data, "discount_value_type", "calculated");
		if ($discount_value_type == "calulcated") $discount_value_type = "calculated";

		$this->element_js_vars["discount_show_table"]           = Ezfc_Functions::get_object_value($this->data, "discount_show_table", 0);
		$this->element_js_vars["discount_show_table_indicator"] = Ezfc_Functions::get_object_value($this->data, "discount_show_table_indicator", 0);
		$this->element_js_vars["discount_value_type"]           = $discount_value_type;
		$this->element_js_vars["discount_value_apply"]          = Ezfc_Functions::get_object_value($this->data, "discount_value_apply", "calculated");
	}

	public function prepare_factor() {
		$factor = Ezfc_Functions::get_object_value($this->data, "factor", 1);

		if ($factor == "") {
			$factor = 1;
		}

		$factor = do_shortcode($factor);
		$factor = $this->get_value_special_fields($factor);

		// override factor
		if (property_exists($this->data, "factor")) {
			$this->element_js_vars["factor"] = $factor;
			$this->element_js_vars["factor_default"] = $factor;
		}

		$this->factor           = $factor;
		$this->output["factor"] = "data-factor='" . esc_attr($factor) . "'";
	}

	public function prepare_label($custom_label = "") {
		// data label
		$el_data_label = "";

		$tmp_label = $custom_label;

		// trim labels
		if (property_exists($this->data, "label")) {
			$tmp_label = trim(htmlspecialchars_decode($this->data->label));
		}

		if (!empty($tmp_label)) {
			// todo: cache option
			if (get_option("ezfc_allow_label_shortcodes", 0)) {
				$tmp_label = do_shortcode($tmp_label);
			}

			// placeholders
			$tmp_label = $this->frontend->get_listen_placeholders($this->data, $tmp_label);

			$el_data_label .= $tmp_label;
		}

		// element description
		if (!empty($this->data->description)) {
			$element_description = "<span class='ezfc-element-description ezfc-element-description-{$this->form_wrapper->options["description_label_position"]}' data-ezfctip='" . esc_attr($this->data->description) . "'></span>";

			$element_description = apply_filters("ezfc_element_description", $element_description, $this->data->description);

			if ($this->form_wrapper->options["description_label_position"] == "before") {
				$el_data_label = $element_description . $el_data_label;
			}
			else {
				$el_data_label = $el_data_label . $element_description;
			}
		}

		// add whitespace for empty labels
		if ($el_data_label == "" && $this->form_wrapper->options["add_space_to_empty_label"] == 1) {
			$el_data_label .= " &nbsp;";
		}

		// additional styles
		// todo: globalize / cache option
		$css_label_width = get_option("ezfc_css_form_label_width");
		$css_label_width = empty($css_label_width) ? "" : "style='width: {$css_label_width}'";

		// default label
		$this->default_label = "<label class='ezfc-label' for='{$this->output["element_child_id"]}' {$css_label_width}>" . $el_data_label . "{$this->output["required_char"]}</label>";

		if (!empty($custom_label)) return $el_data_label;

		return $this->default_label;
	}

	public function prepare_required() {
		$required_check = Ezfc_Functions::get_object_value($this->data, "required", 0);

		$required      = "";
		$required_char = "";

		if ($required_check) {
			$required = "required";

			if ($this->form_wrapper->options["show_required_char"] != 0) {
				$required_char = " <span class='ezfc-required-char'>*</span>";
			}
		}

		// is this element required?
		$this->required = $required_check;
		// text to be added in the input element
		$this->output["required"] = $required;
		// required char
		$this->output["required_char"]  = $required_char;
	}

	public function prepare_styles() {
		// inline style
		$this->output["style"] = "";
		if (!empty($this->data->style)) {
			$this->output["style"] = "style='{$this->data->style}'";
		}

		// options container class
		$this->output["options_container_class"] = "";
		// flex
		if (Ezfc_Functions::get_object_value($this->data, "flexbox", 0) == 1) {
			$this->output["options_container_class"] .= " ezfc-flexbox";
		}
	}

	public function prepare_value() {
		global $post;
		global $product; // woocommerce product (perhaps empty)

		// no value
		if (!property_exists($this->data, "value")) return;

		// WC attribute
		if (!empty($this->data->value_attribute) && !empty($product) && method_exists($product, "get_attribute")) {
			$this->data->value = $product->get_attribute($this->data->value_attribute);
		}

		$this->data->value = $this->get_value_special_fields($this->data->value);
	}

	public function get_value_special_fields($value) {
		if (is_array($value)) {
			foreach ($value as &$value_item) {
				$value_item = $this->get_value_special_fields($value_item);
			}

			return $value;
		}

		global $product;

		// acf
		if (strpos($value, "acf:") !== false && function_exists("get_field")) {
			$tmp_array = explode(":", $value);
			$value = get_field($tmp_array[1]);
		}
		// postmeta
		elseif (strpos($value, "postmeta:") !== false) {
			$tmp_array = explode(":", $value);
			$value = get_post_meta(get_the_ID(), $tmp_array[1], true);
		}
		// php function
		elseif (strpos($value, "php:") !== false) {
			$tmp_array = explode(":", $value);
			if (!empty($tmp_array[1]) && function_exists($tmp_array[1])) {
				$value = htmlspecialchars($tmp_array[1]($this, $this->data, $this->form_wrapper->options, $this->form->id), ENT_QUOTES, "UTF-8");
			}
		}
		// woocommerce
		else if (!empty($product)) {
			// get attribute
			if (strpos($value, "wc:") !== false && method_exists($product, "get_attribute")) {
				$tmp_array = explode(":", $value);
				$value = $product->get_attribute($tmp_array[1]);
			}
			// woocommerce current product price
			else if (strpos($value, "wc_price_current_product") !== false && method_exists($product, "get_price")) {
				$value = $product->get_price();
			}
			// woocommerce current product price
			/*// wc_product:<id>:<attribute>
			else if (strpos($value, "wc_product:") !== false && method_exists($product, "get_price")) {
				$tmp_array = explode(":", $value);
				$value = $product->get_price();
			}*/
		}

		// replace placeholder values
		$replace_values = $this->frontend->get_frontend_replace_values();
		foreach ($replace_values as $replace => $replace_value) {
			$value = str_ireplace("{{" . $replace . "}}", $replace_value, $value);
		}

		// random number
		if ($value == "__rand__" && property_exists($this->data, "min") && is_numeric($this->data->min) && property_exists($this->data, "max") && is_numeric($this->data->max)) {
			$value = function_exists("mt_rand") ? mt_rand($this->data->min, $this->data->max) : rand($this->data->min, $this->data->max);
		}

		// shortcode value
		if (get_option("ezfc_allow_value_shortcodes", 1)) {
			$value = do_shortcode($value);
		}

		// quotes
		$value = htmlspecialchars_decode($value, ENT_QUOTES);

		return $value;
	}

	/**
		get css classes for element wrapper
	**/
	public function get_element_css($css_classes) {
		return $css_classes . " " . $this->element_css_classes;
	}

	/**
		get element js vars
	**/
	public function get_element_js_vars($element_js_vars) {
		$element_js_vars = array_merge($element_js_vars, $this->element_js_vars);

		return $element_js_vars;
	}

	/**
		get external scripts
	**/
	public function enqueue_scripts() {}

	/**
		get external styles
	**/
	public function enqueue_styles() {}

	/**
		get icon
	**/
	public function get_icon() {
		$icon = "";

		if (!empty($this->data->icon)) {
			$icon = "<i class='ezfc-element-icon fa {$this->data->icon}'></i>";
			// add icon class
			$this->data->class .= " ezfc-has-icon";
		}

		return $icon;
	}

	/**
		get label
	**/
	public function get_label() {
		return $this->default_label;
	}

	// get output
	public function _get_output() {
		$output  = "";
		$output .= $this->get_output_before();
		$output .= $this->get_output();
		$output .= $this->get_output_after();

		return $output;
	}

	// before element output
	public function get_output_before() {
		return "";
	}

	// after element output
	public function get_output_after() {
		$output = "";

		if (property_exists($this->data, "discount_show_table") && $this->data->discount_show_table == 1) {
			$output .= $this->get_discount_table();
		}

		return $output;
	}

	/**
		get default output (input field)
	**/
	public function get_output() {
		$el_text  = "";
		$add_attr = "";

		// readonly
		if (!empty($this->data->read_only)) $add_attr .= " readonly";
		// max length
		if (property_exists($this->data, "max_length") && $this->data->max_length != "") $add_attr .= " maxlength='{$this->data->max_length}'";
		// autocomplete
		if (property_exists($this->data, "autocomplete") && $this->data->autocomplete == 0) $add_attr .= " autocomplete='false'";

		// value
		$value = "";
		if (property_exists($this->data, "value")) {
			$add_attr .= " data-initvalue='" . esc_attr($this->data->value) . "'";
			$value     = $this->data->value;
		}
		// readonly
		if (property_exists($this->data, "read_only") && $this->data->read_only == 1) {
			$add_attr .= " readonly";
		}

		// placeholder
		$placeholder = Ezfc_Functions::get_object_value($this->data, "placeholder", "");

		// tel
		$input_type = empty($this->data->is_telephone_nr) ? "text" : "tel";

		$css_classes = property_exists($this->data, "class") ? $this->data->class : "";

		$el_text .= "<input	class='{$css_classes} ezfc-element ezfc-element-input' {$this->output["factor"]} id='{$this->output["element_child_id"]}' name='{$this->output["element_name"]}' placeholder='{$placeholder}' type='{$input_type}' value='{$value}' {$add_attr} {$this->output["style"]} {$this->output["required"]} />";

		return $el_text;
	}

	/**
		get element output wrapper
	**/
	// get output
	public function _get_wrapper_output($content, $output_data) {
		$output  = "";
		$output .= $this->get_wrapper_output_before($output_data);
		$output .= $this->get_wrapper_output($content, $output_data);
		$output .= $this->get_wrapper_output_after($output_data);

		return $output;
	}

	// before element output
	public function get_wrapper_output_before($output_data) {
		return "";
	}

	// after element output
	public function get_wrapper_output_after($output_data) {
		return "";
	}

	/**
		get default output (input field)
	**/
	public function get_wrapper_output($content, $output_data) {
		return $content;
	}

	// get discount table
	public function get_discount_table() {
		global $element_data;
		global $item_row;
		global $output;

		$element_data = $this->data;

		if (!property_exists($this->data, "discount")) return;

		$output = $this->frontend->get_template("elements/discount_table-header");

		foreach ($this->data->discount as $i => $row) {
			$item_row = $row;
			$item_row->__index = $i;

			// format value
			$formatted_value_prefix = "";
			$formatted_value_suffix = "";

			if ($row->operator == "add") { $formatted_value_prefix = "+"; }
			elseif ($row->operator == "subtract") { $formatted_value_prefix = "-"; }
			elseif ($row->operator == "percent_sub") { $formatted_value_prefix = "-"; $formatted_value_suffix = "%"; }
			elseif ($row->operator == "percent_add") { $formatted_value_prefix = "+"; $formatted_value_suffix = "%"; }
			elseif ($row->operator == "equals") { $formatted_value_prefix = "="; }
			elseif ($row->operator == "factor") { $formatted_value_suffx = _x("ea.", "Discount table each abbreviation (factor)", "ezfc"); }

			$item_row->__formatted_value = $formatted_value_prefix . $row->discount_value . $formatted_value_suffix;

			$output .= $this->frontend->get_template("elements/discount_table-loop");
		}
		
		$output .= $this->frontend->get_template("elements/discount_table-footer");

		return $output;
	}

	public function set_parent_id($id) {
		$this->parent_id = $id;

		$this->element_js_vars["__parent_id"] = $id;
	}

	/**
		set submission value
	**/
	public function set_submission_value($value) {
		if (is_array($value)) {
			$value = array_map("stripslashes", $value);
		}
		else {
			$value = stripslashes($value);
		}

		$this->submission_value = $value;

		return $value;
	}

	/**
		set calculated submission value
	**/
	public function set_calculated_submission_value($value) {
		$this->calculated_submission_value = $value;
	}

	/**
		check if this element should be included in submission table
	**/
	public function submission_show_in_email() {
		// conditionally hidden
		if ($this->submission_value == "__HIDDEN__") return false;
		if (is_array($this->submission_value) && isset($this->submission_value[0]) && strpos($this->submission_value[0], "__HIDDEN__") !== false) return false;

		// check if element will be shown in email
		$show_in_email = false;
		if (property_exists($this->data, "show_in_email")) {
			// always show
			if ($this->data->show_in_email == 1) {
				$show_in_email = true;
			}
			// show if not empty
			else if ($this->data->show_in_email == 2) {
				$show_in_email = Ezfc_conditional::value_not_empty($this->submission_value);
			}
			// show if not empty and not 0
			else if ($this->data->show_in_email == 3) {
				$show_in_email = Ezfc_conditional::value_not_empty_not_zero($this->submission_value);
			}
			// conditional show
			else if ($this->data->show_in_email == 4) {
				// invalid -> show nevertheless
				if (empty($this->data->show_in_email_cond)) {
					$show_in_email = true;
				}
				// loop through conditions
				else if (!empty($this->data->show_in_email_cond) && is_array($this->data->show_in_email_cond)) {
					$show_in_email_index = true;
					foreach ($this->data->show_in_email_cond as $i => $cond_element_id) {
						$selected_id = in_array($this->data->show_in_email_operator[$i], array("selected_id", "not_selected_id", "selected_count", "not_selected_count", "selected_count_gt", "selected_count_lt", "in", "not_in"));

						// check if data was submitted
						if (isset($this->frontend->submission_data["raw_values"][$cond_element_id])) {
							// check for selected id
							if ($selected_id) {
								$compare_value = $this->frontend->get_selected_option_id($cond_element_id, $this->frontend->submission_data["raw_values"][$cond_element_id]);
							}
							else {
								$compare_value = $this->frontend->submission_data["raw_values"][$cond_element_id];
							}
						}
						// data wasn't submited -> checkbox or radio options
						else {
							if ($selected_id) $compare_value = array();
							else $compare_value = 0;
						}

						// visibility check
						if (in_array($this->data->show_in_email_operator[$i], array("hidden", "visible"))) {
							$compare_value = !empty($this->frontend->submission_data["elements_visible"][$this->get_id()]);
						}

						$show_in_email_index_compare = Ezfc_conditional::check_operator($compare_value, $this->data->show_in_email_value[$i], $this->data->show_in_email_operator[$i], $compare_value);

						// set flag to false
						if (!$show_in_email_index_compare) $show_in_email_index = false;
					}

					$show_in_email = $show_in_email_index;
				}
			}
			// show if visible
			else if ($this->data->show_in_email == 5) {
				$show_in_email = !empty($this->frontend->submission_data["elements_visible"][$this->get_id()]);
			}
			// show if visible and not empty
			else if ($this->data->show_in_email == 6) {
				$show_in_email = !empty($this->frontend->submission_data["elements_visible"][$this->get_id()]) && Ezfc_conditional::value_not_empty($this->submission_value);
			}
			// show if visible and not empty and not zero
			else if ($this->data->show_in_email == 7) {
				$show_in_email = !empty($this->frontend->submission_data["elements_visible"][$this->get_id()]) && Ezfc_conditional::value_not_empty_not_zero($this->submission_value);
			}
		}

		return $show_in_email;
	}

	/**
		get summary label
	**/
	public function get_summary_label() {
		// what label to show. default = name
		$label_choice = $this->data->name;

		if (property_exists($this->data, "show_in_email_label")) {
			if ($this->data->show_in_email_label == "label" && property_exists($this->data, "label")) {
				$label_choice = $this->data->label;
			} 
			else if ($this->data->show_in_email_label == "custom" && property_exists($this->data, "show_in_email_label_custom")) {
				$label_choice = $this->data->show_in_email_label_custom;
			}
		}

		return $label_choice;
	}

	/**
		returns the submitted value
	**/
	public function get_submission_value() {
		return $this->submission_value;
	}

	/**
		returns the formatted calculated value
	**/
	public function get_summary_value_calculated() {
		// no calculation value
		if (!property_exists($this->data, "is_number") || $this->data->is_number == 0) return "";

		return $this->format_number($this->calculated_submission_value, true);
	}

	/**
		returns the formatted submitted value
	**/
	public function get_summary_value_formatted() {
		if (property_exists($this->data, "options")) return $this->get_summary_value_options();

		// skip email double check
		if (strpos($this->id, "email_check") !== false) return "";

		// skip hidden field by conditional logic
		if ($this->is_conditionally_hidden()) return "";

		$summary_value = $this->submission_value;

		if (Ezfc_Functions::get_object_value($this->data, "is_currency") == 1 && $this->form_wrapper->options["format_currency_numbers_elements"] == 1) {
			$summary_value = $this->format_number($this->submission_value);
		}

		if (is_array($summary_value)) {
			$summary_value = array_map("trim", $summary_value);
			$summary_value = array_filter($summary_value, function($value) { return !is_null($value) && $value !== ''; });
			$summary_value = implode(",", $summary_value);
		}

		//$summary_value = $this->wrap_text($summary_value);

		return $summary_value;
	}

	public function get_summary_value_image() {
		return $this->get_summary_value_options("image");
	}

	/**
		returns the formatted selected option values
	**/
	public function get_summary_value_options($return_type = "text") {
		$return_value = "";

		// check for options source
		$element_values = (array) $this->frontend->get_options_source($this->data, $this->id, $this->form_wrapper->options);

		if (!is_array($this->submission_value) && isset($element_values[$this->submission_value])) {
			// check and return image URL
			if ($return_type == "image" && !empty($element_values[$this->submission_value]->image)) {
				$return_value = "<img src='{$element_values[$this->submission_value]->image}' alt='' />";
			}
			// default text
			else {
				$return_value = esc_html($element_values[$this->submission_value]->text);
			}
		}

		return $return_value;
	}

	/**
		format number
	**/
	public function format_number($value, $is_normalized = false) {
		// not a number
		if (Ezfc_Functions::get_object_value($this->data, "is_number") == 0) return $value;

		$value_old = $value;

		// currency
		if (Ezfc_Functions::get_object_value($this->data, "is_currency") == 1 && $this->form_wrapper->options["format_currency_numbers_elements"] == 1 && !$is_normalized) {
			$value = $this->frontend->normalize_value($value);
		}

		return $this->frontend->number_format($value, $this->data);
	}

	// ignore data settings
	public function format_number_force($value) {
		return $this->frontend->number_format($value, $this->data);
	}

	/**
		check if the element was conditionally hidden
	**/
	public function is_conditionally_hidden() {
		$value_check = $this->submission_value;

		if (is_array($value_check)) {
			if (isset($value_check[0])) {
				$value_check = $value_check[0];
			}
			else {
				$value_check = implode(" ", $value_check);
			}
		}

		return strpos($value_check, "__HIDDEN__") !== false && empty($this->data->calculate_when_hidden);
	}

	/**
		wrap text_before/text_after
	**/
	public function wrap_text($value) {
		if (!property_exists($this->data, "text_before")) return $value;


		if (Ezfc_Functions::get_object_value($this->data, "is_number", 0) == 1) return $value;

		return $this->data->text_before . $value . $this->data->text_after;
	}
}