<?php

class Ezfc_Element_Input extends Ezfc_Element {
	public function get_output() {
		$el_text = "";

		$add_attr = "data-initvalue='" . esc_attr($this->data->value) . "'";

		// readonly
		if (!empty($this->data->read_only)) $add_attr .= " readonly";
		// max length
		if (property_exists($this->data, "max_length") && $this->data->max_length != "") $add_attr .= " maxlength='{$this->data->max_length}'";
		// autocomplete
		if (property_exists($this->data, "autocomplete") && $this->data->autocomplete == 0) $add_attr .= " autocomplete='false'";

		// tel
		$input_type = empty($this->data->is_telephone_nr) ? "text" : "tel";

		$el_text .= "<input	class='{$this->data->class} ezfc-element ezfc-element-input ezfc-input-format-listener' {$this->output["factor"]} id='{$this->output["element_child_id"]}' name='{$this->output["element_name"]}' placeholder='{$this->data->placeholder}' type='{$input_type}' value='{$this->data->value}' {$add_attr} {$this->output["style"]} {$this->output["required"]} />";

		return $el_text;
	}

	public function get_test_value() {
		return __("Test", "ezfc");
	}
}