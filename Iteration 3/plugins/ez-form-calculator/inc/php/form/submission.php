<?php

class Ezfc_Submission {
	public $frontend;
	public $form_wrapper;

	public function __construct() {
		$this->frontend = Ezfc_frontend::instance();
	}

	public function set_form_id($form_id) {
		$this->form_id = $form_id;

		// get form
		$form_wrapper = $this->frontend->get_form_object($form_id);

		// set form wrapper
		$this->set_form_wrapper($form_wrapper);
	}

	public function set_form_wrapper($form_wrapper) {
		$this->form_wrapper = $form_wrapper;
	}

	public function prepare_test() {
		$fill_test_values_array = $this->fill_test_values();

		$this->frontend->prepare_submission_data($this->form_id, array(
			"ezfc_element" => $fill_test_values_array["fill_data"],
			"calculated_values" => $fill_test_values_array["fill_data"],
			"elements_visible" => $fill_test_values_array["elements_visible"],
			"ref_id" => $fill_test_values_array["ref_id"]
		));

		return $fill_test_values_array;
	}

	public function add_test_submission() {
		$fill_test_values_array = $this->prepare_test();

		$result = $this->frontend->insert($this->form_id, $fill_test_values_array["fill_data"], $fill_test_values_array["ref_id"]);
	}

	public function fill_test_values() {
		$fill_data = array();
		$elements_visible = array();
		$ref_id = "test_" . rand(10000000, 99999999);

		foreach ($this->form_wrapper->form_elements_objects as $fe_id => $element) {
			$element->prepare_output($this->form_wrapper->options);
			$fill_data[$fe_id] = $this->get_test_value($element);
			$elements_visible[$fe_id] = 1;
		}

		return array(
			"fill_data" => $fill_data,
			"elements_visible" => $elements_visible,
			"ref_id" => $ref_id,
		);
	}

	public function get_test_value($element) {
		$value = $element->get_test_value();

		return $value;
	}
}