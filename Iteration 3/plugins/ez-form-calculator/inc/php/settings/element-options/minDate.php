<?php

/**
 * minDate element option
 */
class Ezfc_Settings_Element_Option_minDate extends Ezfc_Settings_Element_Option {
	public $name = "minDate";

	public function get_template_actions() {
		$actions = array(
			"datepicker" => array(
				array(
					"text" => __("Default", "ezfc"),
					"args" => "",
				),
				array(
					"text" => __("1 day in the future", "ezfc"),
					"args" => "+1d",
				),
				array(
					"text" => __("2 weeks in the future", "ezfc"),
					"args" => "+2w",
				),
				array(
					"text" => __("1 month in the future", "ezfc"),
					"args" => "+1m",
				),
				array(
					"text" => __("1 year in the future", "ezfc"),
					"args" => "+1y",
				),
			),
			"daterange" => array(
				array(
					"text" => __("Default", "ezfc"),
					"args" => "",
				),
				array(
					"text" => __("1 day in the future, 1 day minimum", "ezfc"),
					"args" => "+1d;;+2d",
				),
				array(
					"text" => __("1 week in the future, 1 week minimum", "ezfc"),
					"args" => "+1w;;+2w",
				),
				array(
					"text" => __("1 month in the future, 1 day minimum", "ezfc"),
					"args" => "+1m;;+1m1d"
				),
				array(
					"text" => __("1 year in the future, 1 day minimum", "ezfc"),
					"args" => "+1y;;+1y1d"
				),
			),
		);

		return $actions;
	}
}