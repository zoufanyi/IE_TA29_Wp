<?php

defined( 'ABSPATH' ) OR exit;

define("EZFC_EXT_QA_VERSION", "1.0.0");

class EZFC_Extension_Quick_Add {
	public $default_element = "numbers";
	public $lines           = array();
	public $lines_raw       = array();
	public $placeholders    = array();
	public $text;

	/**
		constructor
	**/
	function __construct() {
	}

	public function parse() {
		$lines_raw = explode("\n", $this->text);

		// get element types
		$elements      = Ezfc_settings::get_elements();
		$element_types = array();

		foreach ($elements as $element) {
			$element_types[$element->id] = $element->type;
		}

		foreach ($lines_raw as $n => $line_raw) {
			// replace n
			$line_raw = str_replace("{{n}}", ($n + 1), $line_raw);

			// split colon for element type + sanitize before
			$line_array = explode("::", $line_raw);

			// sanitize
			array_map(array($this, "sanitize_text"), $line_array);

			// skip empty lines
			if (empty($line_array[0])) continue;

			// add default element
			if (empty($line_array[1])) $line_array[1] = $this->default_element;

			// check if type exists
			$e_id = array_search($line_array[1], $element_types);
			if ($e_id === false) return false;

			// check for data
			$data = array();
			if (!empty($line_array[2])) parse_str($line_array[2], $data);

			// add element to line
			$this->lines[] = array(
				"e_id" => $e_id,
				"name" => $line_array[0],
				"type" => $line_array[1],
				"data" => $data
			);
		}

		return true;
	}

	public function set_text($text = "") {
		$this->text = $text;
	}

	public function sanitize_text($text) {
		// remove nbsp
		$text = preg_replace("/\x{00a0}/", "", $text);
		// trim
		$text = trim($text);
		// sanitize text
		$text = sanitize_text_field($text);

		return $text;
	}

	public function get_result() {
		return $this->lines;
	}
}