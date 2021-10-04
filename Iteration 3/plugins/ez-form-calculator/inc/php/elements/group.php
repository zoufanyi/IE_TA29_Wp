<?php

class Ezfc_Element_Group extends Ezfc_Element_Decor {
	public function enqueue_styles() {
		// flexbox
		if (Ezfc_Functions::get_object_value($this->data, "group_flexbox", 0) == 1) {
			$flex_columns = (int) Ezfc_Functions::get_object_value($this->data, "group_flexbox_columns", 0);
			if ($flex_columns != 0) {
				$flex_columns_calc = 100 / $flex_columns;

				$this->form_wrapper->add_css("#{$this->output["element_id"]} .ezfc-group-elements > .ezfc-element { flex-basis: {$flex_columns_calc}%; }");
			}
		}
	}

	public function get_output() {
		$el_text  = "";

		$group_wrapper_classes = array();
		$group_wrapper_style   = "";

		//$element_js_vars["repeatable"] = $this->data->repeatable;

		// center group elements
		if (Ezfc_Functions::get_object_value($this->data, "group_center", 0) == 1) $group_wrapper_classes[] = "ezfc-text-center";

		// flexbox
		if (Ezfc_Functions::get_object_value($this->data, "group_flexbox", 0) == 1) {
			$group_wrapper_classes[] = "ezfc-flexbox";

			$flex_columns = (int) Ezfc_Functions::get_object_value($this->data, "group_flexbox_columns", 0);
			if (!empty($flex_columns)) {
				$group_wrapper_classes[] = "ezfc-flex-wrap";
			}

			$group_flexbox_align_items = Ezfc_Functions::get_object_value($this->data, "group_flexbox_align_items", "center");
			$group_wrapper_classes[] = "ezfc-flexbox-align-{$group_flexbox_align_items}";
		}

		if (!empty($this->data->collapsible)) {
			$group_wrapper_style       = empty($this->data->expanded) ? "display: none;" : "";
			$collapse_icon             = empty($this->data->expanded) ? "fa-chevron-circle-right" : "fa-chevron-circle-down";
			$this->element_css_classes .= " ezfc-group-collapsible";
			$this->element_css_classes .= !empty($this->data->expanded) ? " ezfc-group-active" : "";

			// collapse title + toggle handler
			$el_text .= "<div class='ezfc-collapse-title-wrapper'>";
			$el_text .= "	<span class='ezfc-collapse-icon'><i class='fa {$collapse_icon}'></i></span>";
			$el_text .= "	<span class='ezfc-collapse-title'>{$this->data->title}</span>";
			$el_text .= "</div>";
		}
			
		// group elements wrapper
		//$el_text .= "<div class='ezfc-group-elements' style='{$collapse_group_elements_style}'></div>";
		$el_text .= "<div class='ezfc-group-elements " . implode(" ", $group_wrapper_classes) . "' style='{$group_wrapper_style}'>"; // closing div will be added in build function

		/*if ($this->data->repeatable == 1) {
			$el_text .= "</div>";
			$el_text .= "<div class='ezfc-group-repeatable-wrapper'><button class='ezfc-group-repeat' data-group_repeat_id='{$element->id}'>Repeat</button></div>";
			$el_text .= "<div>";
		}*/

		return $el_text;
	}

	public function get_label() {
		return "";
	}
}