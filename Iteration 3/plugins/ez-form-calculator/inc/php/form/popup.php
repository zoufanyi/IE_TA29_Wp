<?php

class Ezfc_Form_Popup {
	public $form_id;

	public function __construct($form_id) {
		$this->frontend = Ezfc_frontend::instance();
		$this->form_id  = $form_id;

		add_filter("ezfc_form_output_start",  array($this, "popup_output_start"), 10, 2);
		add_filter("ezfc_form_output_end",    array($this, "popup_output_end"), 10, 2);
		add_filter("ezfc_form_wrapper_class", array($this, "popup_form_wrapper_class"), 10, 2);
	}

	public function popup_output_start($html, $form_id) {
		if ($this->form_id != $form_id) return $html;

		$out = "";

		// modal div
		// todo: check if modal exists
		$out .= "<div class='ezfc-modal'></div>";

		// add button
		if (Ezfc_Functions::get_array_value($this->frontend->form->options, "popup_button_show", 1) == 1) {
			$out .= $this->frontend->get_template("popup/button");
		}

		// add popup wrapper
		$out .= $this->frontend->get_template("popup/popup-begin");

		return $out . $html;
	}

	public function popup_output_end($html, $form_id) {
		if ($this->form_id != $form_id) return $html;

		$out = "";

		$out .= $this->frontend->get_template("popup/popup-end");		

		return $out . $html;
	}

	public function popup_form_wrapper_class($wrapper_class, $form_id) {
		if ($this->form_id != $form_id) return $wrapper_class;

		// add wrapper tag
		$wrapper_class .= " ezfc-form-in-dialog";

		return $wrapper_class;
	}
}