<?php

abstract class Ezfc_Templates {
	/**
		form templates
	**/
	static function get_templates($append_id = true) {
		$templates = array(
		);

		foreach ($templates as $id => &$template) {
			// append 'ez' to template ID
			if ($append_id) $template["id"] = "ez-{$id}";

			if (isset($template["options"])) continue;

			// add missing options if not set
			$template["options"] = "";
		}

		return $templates;
	}

	static function get_template($id) {
		$templates = self::get_templates();

		// remove ez- prefix
		if (substr($id, 0, 3) == "ez-") $id = substr($id, 3);

		if (!isset($templates[$id])) {
			throw new Exception(sprintf(__("Unable to find template %s", "ezfc"), $id));
		}

		// return as object
		return json_decode(json_encode($templates[$id]));
	}
}