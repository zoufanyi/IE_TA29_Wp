<?php

class Ezfc_Element_Dropdown extends Ezfc_Element_Options {
	public function get_output() {
		$el_text  = "";
		$add_attr = "";

		// preselect value
		if (property_exists($this->data, "preselect") && $this->data->preselect != -1) $add_attr .= " data-initvalue='{$this->data->preselect}'";

		$el_text .= "<select class='{$this->data->class} ezfc-element ezfc-element-select' id='{$this->output["element_child_id"]}' name='{$this->output["element_name"]}' {$this->output["style"]} {$this->output["required"]} {$add_attr}>";

		$element_options = $this->frontend->get_options_source($this->data, $this->id, $this->options);

		foreach ($element_options as $n => $option) {
			$add_data = "";
			$selected = false;

			// check by ID or value
			$check_option_value = Ezfc_Functions::get_object_value($this->data, "GET_check_option_value", "index");
			$check_preselect_value = apply_filters("ezfc_check_option_preselect_values", $check_option_value, $this->id, $this->data, $this->type, $this->form->id);

			// preselect check
			if (property_exists($this->data, "preselect")) {
				// check by index
				if ($check_preselect_value == "index") {
					$selected = $this->data->preselect == $n && $this->data->preselect != -1;
				}
				// check by value
				else if ($check_preselect_value == "value") {
					$selected = $this->data->preselect == $option->value && $this->data->preselect != -1;
				}
				// check by ID
				else if ($check_preselect_value == "id") {
					$selected = $this->data->preselect == $option->id && $this->data->preselect != -1;
				}
			}

			// disabled
			if (property_exists($option, "disabled") && $option->disabled == 1) {
				$add_data .= " disabled='disabled'";
			}

			// option is preselected
			if ($selected) {
				$add_data .= " selected='selected'";
			}

			// option ID
			if (!empty($option->id)) {
				$add_data .= " data-optionid='" . esc_attr($option->id) . "'";
			}

			$show_price = $this->frontend->get_show_price_text($this->options, $this->data, $option->value, $this->type);

			// replace values
			$option_text = $this->frontend->replace_element_label_text($option->text, $this->data);

			$el_text .= "<option value='{$n}' data-value='{$option->value}' data-index='{$n}' data-factor='{$option->value}' data-settings='{$this->add_vars["data_settings"]}' {$add_data}>{$option_text}{$show_price}</option>";
		}

		$el_text .= "</select>";

		return $el_text;
	}
}