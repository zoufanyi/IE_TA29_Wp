<?php

class Ezfc_Element_Options extends Ezfc_Element {
	public $element_options;
	
	public function __construct($form, $element, $id = null, $type = "input") {
		parent::__construct($form, $element, $id, $type);
		
		$this->element_options = $this->frontend->get_options_source($this->data, $this->id, $this->options);
	}

	/*public function get_element_input_name() {
		$id = $this->get_id();

		$element_input_name = parent::get_element_input_name();

		if ($this->counter > 0 && $this->parent_id) {
			// repeatable form id
			$element_input_name = "ezfc_element[{$this->parent_id}][{$this->id}][0]";
		}

		return $element_input_name;
	}*/

	public function get_option_columns_style() {
		$item_style = "";

		$element_options_count = 0;
		if (Ezfc_Functions::is_countable($this->element_options)) {
			$element_options_count = count($this->element_options);
		}

		$options_columns_raw = Ezfc_Functions::get_object_value($this->data, "options_columns", "");

		// deprecated
		$image_auto_width = Ezfc_Functions::get_object_value($this->data, "image_auto_width", 0);
		if ($image_auto_width == 1) {
			$options_columns_raw = "custom";
			$this->data->__options_columns_custom = -1;
		}

		if (!empty($options_columns_raw)) {
			$options_columns = (int) $options_columns_raw;

			// custom columns
			if ($options_columns_raw == "custom") $options_columns = Ezfc_Functions::get_object_value($this->data, "__options_columns_custom", "");

			$options_columns = (int) $options_columns;
			if (!empty($options_columns)) {
				$column_width = 100 / $options_columns;

				// auto width
				if ($options_columns == -1 && $element_options_count > 0) {
					$column_width = 100 / $element_options_count;
				}

				$item_style .= ";width: {$column_width}%; display: inline-block;";
			}
		}

		// image sizes
		if (!empty($this->data->max_width)) {
			if (is_numeric($this->data->max_width)) $this->data->max_width .= "px";

			$item_style .= ";max-width: {$this->data->max_width};";
		}

		// max height
		if (!empty($this->data->max_height)) {
			if (is_numeric($this->data->max_height)) $this->data->max_height .= "px";

			$item_style .= ";max-height: {$this->data->max_height};";
		}

		return $item_style;
	}

	public function get_test_value() {
		return array_rand($this->element_options);
	}
}