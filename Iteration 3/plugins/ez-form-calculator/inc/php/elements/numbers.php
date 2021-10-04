<?php

class Ezfc_Element_Numbers extends Ezfc_Element {
	public function get_output() {
		$el_text = "";

		// number settings
		$this->add_vars["data_settings"] = json_encode(array(
			"calculate_when_hidden" => property_exists($this->data, "calculate_when_hidden") ? $this->data->calculate_when_hidden : 1,
			"precision"             => property_exists($this->data, "precision")             ? $this->data->precision : 2,
			"price_format"          => property_exists($this->data, "price_format")          ? $this->data->price_format : ""
		));

		// min/max
		$this->element_js_vars["min"] = $this->data->min;
		$this->element_js_vars["max"] = $this->data->max;

		$show_price = $this->frontend->get_show_price_text($this->options, $this->data, $this->data->value, $this->type);

		$el_text_add = "";

		$add_attr = "data-min='{$this->data->min}' data-max='{$this->data->max}' data-initvalue='{$this->data->value}'";
		// use slider
		if (property_exists($this->data, "slider") && $this->data->slider == 1) {
			wp_enqueue_script("jquery-ui-slider");

			$steps_slider = 1;
			if (property_exists($this->data, "steps_slider")) $steps_slider = $this->data->steps_slider;

			$add_attr    .= " data-stepsslider='{$steps_slider}'";
			$this->data->class .= " ezfc-slider";
			$this->element_css_classes .= " ezfc-element-has-slider";

			// vertical
			if (!empty($this->data->slider_vertical)) $this->data->class .= " ezfc-slider-vertical";

			// slider values
			if (!empty($this->data->slider_values)) $add_attr .= " data-values='{$this->data->slider_values}'";

			$el_text_add .= "<div class='ezfc-slider-element' data-target='{$this->output["element_id"]}'></div>";
		}
		// use spinner
		if (property_exists($this->data, "spinner") && $this->data->spinner == 1) {
			wp_enqueue_script("jquery-ui-spinner");

			$steps_spinner = 1;
			if (property_exists($this->data, "steps_spinner")) $steps_spinner = $this->data->steps_spinner;

			$add_attr    .= " data-stepsspinner='{$steps_spinner}'";
			$this->data->class .= " ezfc-spinner";
		}
		// pips
		if (property_exists($this->data, "pips") && $this->data->pips > 0) {
			wp_enqueue_style("jquery-ui-slider-pips", EZFC_URL . "assets/jquery-ui-slider-pips/css/jquery-ui-slider-pips.css");
			wp_enqueue_script("jquery-ui-slider-pips", EZFC_URL . "assets/jquery-ui-slider-pips/js/jquery-ui-slider-pips.js", array( 'jquery-ui-slider' ), false, false);

			$steps_pips = 1;
			if (property_exists($this->data, "steps_pips")) $steps_pips = $this->data->steps_pips;
			if (property_exists($this->data, "pips_float") && $this->data->pips_float == 1) $add_attr .= " data-pipsfloat='1'"; 

			$add_attr    .= " data-stepspips='{$steps_pips}'";
			$this->data->class .= " ezfc-pips";
		}
		// readonly
		if (property_exists($this->data, "read_only") && $this->data->read_only == 1) {
			$add_attr .= " readonly";
		}

		if (!empty($this->data->image) && filter_var($this->data->image, FILTER_VALIDATE_URL)) {
			$el_text .= "<img class='ezfc-img ezfc-img-numbers' src='" . esc_attr($this->data->image) . "' alt='" . esc_attr($this->data->alt) . "' />";
		}

		// readonly
		if (!empty($this->data->read_only)) $add_attr .= " readonly";
		// max length
		if (isset($this->data->max_length) && $this->data->max_length != "") $add_attr .= " maxlength='{$this->data->max_length}'";
		// autocomplete
		if (property_exists($this->data, "autocomplete") && $this->data->autocomplete == 0) $add_attr .= " autocomplete='false'";

		// text_only
		$el_text = apply_filters("ezfc_element_output_text_only", $el_text, $this->data, $this->options);

		$input_type = $this->get_input_type();

		$el_text .= "<input	class='{$this->data->class} ezfc-element ezfc-element-numbers ezfc-input-format-listener' {$this->output["factor"]}  id='{$this->output["element_child_id"]}' name='{$this->output["element_name"]}' placeholder='{$this->data->placeholder}' type='{$input_type}' value='{$this->data->value}' data-id='{$this->id}' data-settings='{$this->add_vars["data_settings"]}' {$this->output["style"]} {$this->output["required"]} {$add_attr} />{$show_price}";
		$el_text .= $el_text_add;

		return $el_text;
	}
}