<?php

/**
 * repeatable_form_id element option
 */
class Ezfc_Settings_Element_Option_repeatable_form_id extends Ezfc_Settings_Element_Option {
	public $name = "repeatable_form_id";

	public function __construct() {
		parent::__construct();

		$this->help_text = __("You can only use the same sub form once per form.", "ezfc");
	}
}