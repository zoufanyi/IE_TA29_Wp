<?php

class Ezfc_Element_Checkbox extends Ezfc_Element_Options {
	public function get_output() {
		$el_text  = "";
					
		$preselect_values = array();
		if (property_exists($this->data, "preselect")) {
			$preselect_values = explode(",", $this->data->preselect);
		}

		// preselect from WC cart (compare with $n) or from GET-parameter
		if ($this->add_vars["use_cart_values"] && property_exists($this->data, "value")) {
			if (is_array($this->data->value)) {
				$preselect_values = $this->data->value;
			}
			else {
				$preselect_values = array($this->data->value);
			}
		}

		// option container
		$el_text .= "<div class='ezfc-element-option-container'>";

		$i = 0;
		foreach ($this->element_options as $n => $option) {
			$el_icon               = "";
			$el_image              = "";
			$element_name_option   = "{$this->output["element_name"]}[$i]";
			$el_wrapper_class      = "";
			$el_wrapper_item_style = "";
			$img_class             = "";
			$checkbox_id           = "{$this->output["element_id"]}-{$i}";
			$selectable_text       = Ezfc_Functions::get_object_value($this->data, "options_text_only", 0) == 1;

			$checkbox_text = apply_filters("ezfc_option_label", $option->text, $checkbox_id, $n);
			$checkbox_text = $this->frontend->replace_element_label_text($checkbox_text, $this->data);
			$checkbox_text_without_label = apply_filters("ezfc_option_label", $option->text, $checkbox_id, $n, true);
			$checkbox_text_without_label = $this->frontend->replace_element_label_text($checkbox_text_without_label, $this->data);

			// columns
			$el_wrapper_item_style .= $this->get_option_columns_style();

			if (!empty($option->image) || !empty($option->icon) || $selectable_text) {
				// option image
				if (!empty($option->image)) {
					$el_image = $option->image;
				}
				// option icon
				if (!empty($option->icon)) {
					$el_icon = "<i class='ezfc-element-checkbox-icon fa fa-fw {$option->icon}'></i>";
					$el_wrapper_class .= " ezfc-element-option-has-icon";
				}
				// selectable text
				if ($selectable_text) {
					$el_wrapper_class .= " ezfc-element-option-has-selectable-text";
				}

				$el_wrapper_class .= " ezfc-element-option-has-image";
			}

			$add_data = "";

			// check by ID or value
			$check_option_value = Ezfc_Functions::get_object_value($this->data, "GET_check_option_value", "index");
			$check_preselect_value = apply_filters("ezfc_check_option_preselect_values", $check_option_value, $this->id, $this->data, $this->type, $this->form->id);
			// single preselect check
			$selected = false;

			// check by index
			if ($check_preselect_value == "index") {
				$selected = in_array((string) $i, $preselect_values);
			}
			// check by value
			else if ($check_preselect_value == "value") {
				$selected = in_array($option->value, $preselect_values);
			}
			// check by ID
			else if ($check_preselect_value == "id") {
				$selected = in_array($option->id, $preselect_values);
			}

			if ($selected) {
				$add_data .= " checked='checked'";
				$add_data .= " data-initvalue='1'";

				if (!empty($option->image)) {
					$img_class .= " ezfc-selected";
				}
			}

			$add_class = "";
			if (property_exists($option, "disabled")) {
				$add_data  .= " disabled='disabled'";
				$add_class .= "force-disabled";
			}

			// option ID
			if (!empty($option->id)) {
				$add_data .= " data-optionid='" . esc_attr($option->id) . "'";
			}

			$show_price = $this->frontend->get_show_price_text($this->options, $this->data, $option->value, $this->type);

			$el_text .= "<div class='ezfc-element-checkbox-container ezfc-element-single-option-container {$el_wrapper_class}' style='{$el_wrapper_item_style}'>";
			$el_text .= "	<div class='ezfc-element-checkbox'>";
			$el_text .= "		<input class='{$this->data->class} {$add_class} ezfc-element-checkbox-input' id='{$checkbox_id}' type='checkbox' name='{$element_name_option}' value='{$i}' data-value='{$option->value}' data-index='{$i}' data-factor='{$option->value}' data-settings='{$this->add_vars["data_settings"]}' {$this->output["style"]} {$add_data} />";

			// selectable items
			if (!empty($el_image) || !empty($el_icon) || $selectable_text) {
				$img_class = "";

				// preselect
				if (!empty($selected)) {
					$img_class .= " ezfc-selected";
				}

				// selectable text
				if ($selectable_text) {
					$checkbox_text = "";
					$el_text .= "<div class='ezfc-element-option-image ezfc-element-option-selectable-text ezfc-element-checkbox-image {$img_class}' rel='{$this->output["element_id"]}'>{$el_icon}{$checkbox_text_without_label}</div>";
				}
				// selectable image
				else if (!empty($el_image)) {
					$el_text .= "<img class='ezfc-element-option-image ezfc-element-checkbox-image {$img_class}' src='{$el_image}' rel='{$this->output["element_id"]}' alt='' />";
				}
				// selectable icon
				else if (!empty($el_icon)) {
					$el_text .= "<div class='ezfc-element-option-image ezfc-element-checkbox-image ezfc-element-icon-wrapper {$img_class}' rel='{$this->output["element_id"]}'>{$el_icon}</div>";

					if (empty($this->data->option_add_text_icon)) {
						$checkbox_text = "";
					}
				}
			}		
			
			// addon label
			$el_text .= "		<label class='ezfc-addon-label' for='{$checkbox_id}'></label>";
			$el_text .= "		<div class='ezfc-addon-option'></div>";
			$el_text .= "	</div>";

			// text + price
			$el_text .= "	<div class='ezfc-element-checkbox-text'>{$checkbox_text}<span class='ezfc-element-checkbox-price'>{$show_price}</span></div>";
			$el_text .= "	<div class='ezfc-element-checkbox-clear'></div>";
			$el_text .= "</div>";

			$i++;
		}

		// option container
		$el_text .= "</div>";

		return $el_text;
	}

	public function get_summary_value_options($return_type = "text") {
		$return_value = array();;

		// check for options source
		$options = $this->frontend->get_options_source($this->data, $this->id, $this->options);
		$element_values = (array) $options;

		if (!is_array($this->submission_value)) $this->submission_value = (array) $this->submission_value;

		foreach ($this->submission_value as $chk_i => $chk_value) {
			// skip hidden field by conditional logic
			if (strpos($chk_value, "__HIDDEN__") !== false) continue;

			if (isset($element_values[$chk_value])) {
				// check and return image URL
				if ($return_type == "image" && !empty($element_values[$chk_value]->image)) {
					$return_value[] = "<img src='{$element_values[$chk_value]->image}' alt='' />";
				}
				else {
					$return_value[] = esc_html($element_values[$chk_value]->text);
				}
			}
		}

		return implode(", ", $return_value);
	}
}