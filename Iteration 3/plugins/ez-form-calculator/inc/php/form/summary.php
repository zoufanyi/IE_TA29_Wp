<?php

class Ezfc_summary {
	public $frontend;
	public $form;
	public $form_elements          = array();
	public $form_elements_order    = array();
	public $form_options           = array();
	public $form_values_calculated = array();
	public $form_values            = array();
	public $form_values_raw        = array();
	public $loop_value             = 0;
	public $theme                  = "default";
	public $total                  = 0;

	public function __construct() {
		$this->frontend = Ezfc_frontend::instance();
	}

	public function set_form($form) {
		$this->form = $form;
	}

	public function get_summary($options = array()) {
		global $summary_data;

		$options = array_merge(array(
			"content_only" => false
		), $options);

		$summary_data = array(
			"columns" => array(
				$this->form_options["summary_column_1"],
				$this->form_options["summary_column_2"],
				$this->form_options["summary_column_3"]
			),
			"form_options" => $this->form_options,
			"total" => $this->total,
			"total_formatted" => $this->frontend->number_format($this->total)
		);

		// result theme
		$theme_output = array(
			"header"  => $this->frontend->get_template("result/{$this->theme}/header"),
			"content" => "",
			"footer"  => $this->frontend->get_template("result/{$this->theme}/footer")
		);

		$content = array();

		foreach ($this->form_elements_order as $fe_id) {
			if (!isset($this->form_elements[$fe_id])) {
				// check for repeating form element
				$element_raw = $this->form->get_form_element($fe_id);
				// element doesn't exist
				if (!$element_raw) return;

				$element = $this->form->get_element_class($element_raw);
				$element->set_element_data(json_decode($element_raw->data));
				$element->set_form($this->form);
				$element->set_form_options($this->form->options);
				$element->set_form_wrapper($this->form);
				$element->init();
			}
			// regular form element
			else {
				$element = $this->form_elements[$fe_id];
			}

			$value = isset($this->form_values[$fe_id]) ? $this->form_values[$fe_id] : false;
			$calculated_value = isset($this->form_values_calculated[$fe_id]) ? $this->form_values_calculated[$fe_id] : 0;

			$content_loop = $this->get_loop_item($element, $value, $calculated_value, $this->loop_value);

			// add to table
			if ($content_loop !== false) {
				$content[] = $content_loop;
				$this->loop_value++;
			}
		}

		$theme_output["content"] = implode("", $content);

		// return content only
		if ($options["content_only"]) return $theme_output["content"];

		return $theme_output;
	}

	public function get_loop_item($element, $value, $calculated_value, $loop_value) {
		global $loop_data;

		$sanitize_function = "sanitize_text_field";
		if ($element->type == "textfield") $sanitize_function = "sanitize_textarea_field";

		// sanitize
		$calculated_value = Ezfc_Functions::sanitize($calculated_value, $sanitize_function);
		$value = Ezfc_Functions::sanitize($value, $sanitize_function);

		// set/retrieve modified submission value
		$calculated_value = $element->set_calculated_submission_value($calculated_value);
		$value = $element->set_submission_value($value);

		// do not show element in email
		if (!$element->submission_show_in_email()) return false;

		$loop_data = array(
			"even"             => $loop_value % 2 == 0,
			"label"            => $element->get_summary_label(),
			"text_column_1"    => $this->get_text_column_value(Ezfc_Functions::get_object_value($element->data, "email_text_column_1", "submission_value"), $element),
			"text_column_2"    => $this->get_text_column_value(Ezfc_Functions::get_object_value($element->data, "email_text_column_2", "calculated_value"), $element),
			"value_calculated" => $element->get_summary_value_calculated(), // old: total_out_string
			"value_formatted"  => $element->get_summary_value_formatted(), // old: $value_out_simple_html
			"value_raw"        => isset($this->form_values[$element->id]) ? $this->form_values[$element->id] : false,
			"values"           => $value
		);

		$loop_file = "loop";
		if (file_exists(EZFC_PATH . "templates/result/{$this->theme}/loop-{$element->type}.php")) {
			$loop_file = "loop-{$element->type}";
		}

		$loop_content = $this->frontend->get_template("result/{$this->theme}/{$loop_file}");

		return $loop_content;
	}

	public function get_text_column_value($text_type, $element) {
		$return_value = "";

		switch ($text_type) {
			case "blank": $return_value = "";
			break;

			case "calculated_value": $return_value = $element->get_summary_value_calculated();
			break;

			case "image": $return_value = $element->get_summary_value_image();
			break;

			case "submission_value": $return_value = $element->get_summary_value_formatted();
			break;
		}

		return $return_value;
	}

	public function set_form_elements($form_elements) {
		$this->form_elements = $form_elements;
	}

	public function set_form_elements_order($form_elements_order = array()) {
		$this->form_elements_order = $form_elements_order;
	}

	public function set_form_options($form_options) {
		$this->form_options = $form_options;
	}

	public function set_form_values($form_values, $form_values_calculated) {
		$this->form_values = $form_values;
		$this->form_values_calculated = $form_values_calculated;
	}

	public function set_loop_value($value = 0) {
		$this->loop_value = $value;
	}

	public function set_theme($theme = "default") {
		$this->theme = sanitize_file_name($theme);
	}

	public function set_total($total = 0) {
		$this->total = $total;
	}
}