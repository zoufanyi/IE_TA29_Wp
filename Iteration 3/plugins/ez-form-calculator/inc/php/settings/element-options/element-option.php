<?php

class Ezfc_Settings_Element_Option {
	private static $_instance = null;

	public $help_text = "";
	public $js_vars = array();
	public $name    = "";
	public $params  = array();

	public function __construct() {
	}

	public function get_js_vars() {
		// help text below input
		if (!empty($this->help_text)) $this->js_vars["help_text"] = $this->help_text;

		// template actions
		$template_actions = $this->get_template_actions();

		if ($template_actions) {
			$template_actions_output = array();

			foreach ($template_actions as $template_action_type => $template_action_array) {
				foreach ($template_action_array as $template_action) {
					$template_actions_output[$template_action_type][] = $this->get_template_action_item($template_action);
				}

				$this->js_vars["template_actions"][$template_action_type] = implode("", $template_actions_output[$template_action_type]);
			}
		}

		return $this->js_vars;
	}

	public function get_template_actions() {
		return false;
	}

	public function get_template_action_item($params) {
		$add_data = "";
		if (!empty($params["args_raw"])) $add_data .= " data-argsraw='1'";

		return "<div class='ezfc-form-element-option-template-action' data-func='insert_predefined_template' data-args='{$params["args"]}' {$add_data}>{$params["text"]}</div>";
	}

	public function set_options($name, $params = array()) {
		$this->name = $name;
		$this->params = $params;
	}
}