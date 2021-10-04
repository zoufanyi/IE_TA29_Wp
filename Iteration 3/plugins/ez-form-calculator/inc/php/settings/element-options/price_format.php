<?php

/**
 * price_format element option
 */
class Ezfc_Settings_Element_Option_price_format extends Ezfc_Settings_Element_Option {
	public $name = "price_format";

	public function get_template_actions() {
		$actions = array(
			"all" => array(
				array(
					"text" => __("Default", "ezfc"),
					"args" => "",
				),
				array(
					"text" => __("Show decimal numbers if not zero", "ezfc"),
					"args" => "0,0[.]00",
					"args_raw" => true,
				),
				array(
					"text" => __("Always show decimal numbers", "ezfc"),
					"args" => "0,0.00",
					"args_raw" => true,
				),
				array(
					"text" => __("Never show decimal numbers", "ezfc"),
					"args" => "0,0",
					"args_raw" => true,
				),
			),
		);

		return $actions;
	}
}