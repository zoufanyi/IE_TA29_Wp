<?php

abstract class Ezfc_conditional {
	static public function value_not_empty($value = null) {
		if (is_array($value)) {
			$check_return = !Ezfc_conditional::value_empty_array($value);
		}
		else {
			$value_trimmed = trim($value);
			$check_return = $value_trimmed != "";
		}

		return $check_return;
	}

	static public function value_not_empty_not_zero($value = null) {
		if (is_array($value)) {
			$check_return = !Ezfc_conditional::value_empty_array($value, false);
		}
		else {
			$value_trimmed = trim($value);
			$check_return = $value_trimmed != "" && $value_trimmed != 0;
		}

		return $check_return;
	}

	// checks whether an array is empty
	static public function value_empty_array($value_array, $zero_is_also_empty = true) {
		// not an array
		if (!is_array($value_array)) return true;

		$empty = true;
		foreach ($value_array as $i => $value) {
			$value = trim($value);

			if ($zero_is_also_empty && $value != 0) {
				$empty = false;
				break;
			}
			else if ($value != "") {
				$empty = false;
				break;
			}
		}

		return $empty;
	}

	static public function check_conditional($element_data, $value, $single_conditional_action = "", $return_row = false, $selected_id = false) {
		// object needed | throw new Exception(__("Element data is not an object.", "ezfc"));
		if (!is_object($element_data)) return;
		// check value | throw new Exception(__("Value is not a number.", "ezfc"));
		if (!is_numeric($value)) return;

		$return_row_value = 0;
		$return_value = true;

		if (!empty($element_data->conditional) && count($element_data->conditional) > 0) {
			foreach ($element_data->conditional as $i => $condition) {
				$check_row = self::check_conditional_row($condition, $value, $single_conditional_action, $selected_id);

				// invalid row
				if ($check_row === -1) continue;

				// return conditional row index
				if ($check_row === true && $return_row) {
					return array(
						"conditional" => true,
						"row"         => $i
					);
				}

				// condition is not true
				if ($check_row === false) {
					$return_value = false;
				}
			}
		}

		return $return_value;
	}

	static public function check_conditional_row($conditional_row, $value, $single_conditional_action = "", $return_row = false, $selected_id = false) {
		// check for single conditional action only
		if (!is_object($conditional_row) ||
			!property_exists($conditional_row, "action") ||
			(!empty($single_conditional_action) && $conditional_row->action != $single_conditional_action)) {
			return -1;
		}

		$condition_is_true = true;

		$condition_merged = array(
			array(
				"operator" => $conditional_row->operator,
				"value" => $conditional_row->value
			)
		);

		// chain
		$n = 1;
		if (!empty($conditional_row->operator_chain) && !empty($conditional_row->value_chain)) {
			$condition_merged[$n] = array();

			// operator
			foreach ($conditional_row->operator_chain as $operator_chain) {
				$condition_merged[$n]["operator"] = $operator_chain;
			}

			// value
			foreach ($conditional_row->value_chain as $value_chain) {
				$condition_merged[$n]["value"] = $value_chain;
			}

			$n++;
		}

		foreach ($condition_merged as $conditional_row) {
			if (!self::check_operator($value, $conditional_row["value"], $conditional_row["operator"], $selected_id)) {
				$condition_is_true = false;
			}
		}

		return $condition_is_true;
	}

	static public function has_conditional_rows($element_data) {
		if (!property_exists($element_data, "conditional")) return false;
		if (!Ezfc_Functions::is_countable($element_data->conditional)) return false;
		if (count($element_data->conditional) < 1) return false;

		foreach ($element_data->conditional as $i => $conditional_row) {
			if (empty($conditional_row)) return false;
		}
		
		return true;
	}

	// check for global email target
	static public function get_conditional_email_target($conditions, $target_element_id = false) {
		$frontend = Ezfc_frontend::instance();

		if (empty($conditions)) return false;

		foreach ($conditions as $i => $conditional_row) {
			// global conditions
			if (property_exists($conditional_row, "compare_value_first")) {
				$target_element_id = Ezfc_Functions::get_object_value($conditional_row, "compare_value_first", false);
			}

			// target element doesn't exist
			if (!$target_element_id) continue;

			// value from target element
			$target_element_value = $frontend->get_calculated_target_value_from_input($target_element_id, $frontend->get_raw_submission_value($target_element_id));
			$target_element_selected_id = $frontend->get_selected_option_id($target_element_id, $frontend->get_raw_submission_value($target_element_id));

			// todo: check for custom compare value or element
			$conditional_row_result = self::check_conditional_row($conditional_row, $target_element_value, "email_target", true, $target_element_selected_id);
			if ($conditional_row_result === true) {
				// return email
				return $conditional_row->target_value;
			}
		}

		return false;
	}

	// $v1 = input value, $v2 = element conditional value
	static public function check_operator($v1, $v2, $operator, $selected_id = false) {
		$do_action = false;

		switch ($operator) {
			case "gr": $do_action = $v1 > $v2;
			break;
			case "gre": $do_action = $v1 >= $v2;
			break;

			case "less": $do_action = $v1 < $v2;
			break;
			case "lesse": $do_action = $v1 <= $v2;
			break;

			case "selected":
			case "equals": $do_action = $v1 == $v2;
			break;

			case "between":
				if (count($v2) < 2) {
					$do_action = false;
				}
				else {
					$do_action = ($v1 >= $v2[0] && $v1 <= $v2[1]);
				}
			break;

			case "not_between":
				if (count($v2) < 2) {
					$do_action = false;
				}
				else {
					$do_action = ($v1 < $v2[0] || $v1 > $v2[1]);
				}
			break;

			case "not_selected":
			case "not":
				if (count($v2) < 2) {
					$do_action = $v1 != $v2;
				}
				else {
					$do_action = ($v1 < $v2[0] && $v1 > $v2[1]);
				}
			break;

			case "mod0": $do_action = $v1 > 0 && ($v1 % $v2) == 0;
			break;
			case "mod1": $do_action = $v1 > 0 && ($v1 % $v2) != 0;
			break;

			case "bit_and": $do_action = $v1 & $v2;
			break;

			case "bit_or": $do_action = $v1 | $v2;
			break;

			case "empty":
				$do_action = $v1 === "";
			break;

			case "notempty":
				$do_action = $v1 !== "";
			break;

			case "in":
				$do_action = in_array($v1, $v2);
			break;

			case "not_in":
				$do_action = !in_array($v1, $v2);
			break;

			case "selected_id":
				if (is_array($selected_id)) {
					$do_action = in_array($v2, $selected_id);
				}
				else {
					$do_action = $v2 == $selected_id && $selected_id !== false;
				}
			break;
			case "not_selected_id":
				if (is_array($selected_id)) {
					$do_action = !in_array($v2, $selected_id);
				}
				else {
					$do_action = $v2 != $selected_id || $selected_id === false;
				}
			break;

			case "selected_count":
				$do_action = count($selected_id) == $v2;
			break;
			case "not_selected_count":
				$do_action = count($selected_id) != $v2;
			break;
			case "selected_count_gt":
				$do_action = count($selected_id) > $v2;
			break;
			case "selected_count_lt":
				$do_action = count($selected_id) < $v2;
			break;

			// v1 = visible flag
			case "hidden":
				$do_action = !$v1;
			break;
			case "visible":
				$do_action = $v1;
			break;

			/*
			todo
			{ value: "selected_index", text: "selected index" },
			{ value: "not_selected_index", text: "not selected index" },
			*/

			default: $do_action = false;
			break;
		}

		return $do_action;
	}

	static public function prepare_conditional_js($conditional_row) {
		$frontend = Ezfc_frontend::instance();

		$data_conditional_output = array(
			"action"              => array(),
			"compare_value_first" => array(),
			"notoggle"            => array(),
			"operator"            => array(),
			"option_index_value"  => array(),
			"redirects"           => array(),
			"row_operator"        => array(),
			"target"              => array(),
			"target_value"        => array(),
			"use_factor"          => array(),
			"values"              => array()
		);

		foreach ($conditional_row as $c => $conditional) {
			$conditional_action = Ezfc_Functions::get_object_value($conditional, "action", "");
			// do not add emails to frontend
			if ($conditional_action == "email_target") continue;

			// check if valid condition
			if (!self::is_valid_conditional_row($conditional)) continue;

			$data_conditional_output["action"][]              = property_exists($conditional, "action") ? $conditional->action : "";
			$data_conditional_output["compare_value_first"][] = property_exists($conditional, "compare_value_first") ? $conditional->compare_value_first : "";
			$data_conditional_output["notoggle"][]            = property_exists($conditional, "notoggle") ? $conditional->notoggle : "0";
			$data_conditional_output["operator"][]            = property_exists($conditional, "operator") ? $conditional->operator : "";
			$data_conditional_output["option_index_value"][]  = property_exists($conditional, "option_index_value") ? $conditional->option_index_value : "";
			$data_conditional_output["redirects"][]           = property_exists($conditional, "redirect") ? $conditional->redirect : "0";
			$data_conditional_output["row_operator"][]        = property_exists($conditional, "row_operator") ? $conditional->row_operator : 0;
			$data_conditional_output["target"][]              = property_exists($conditional, "target") ? $conditional->target : "";
			$data_conditional_output["target_value"][]        = property_exists($conditional, "target_value") ? $frontend->normalize_value($conditional->target_value, true, false) : "";
			$data_conditional_output["use_factor"][]          = property_exists($conditional, "use_factor") ? $conditional->use_factor : 0;
			$data_conditional_output["values"][]              = property_exists($conditional, "value") ? $conditional->value : "";

			// conditional chains
			if (property_exists($conditional, "operator_chain")) {
				$compare_value = property_exists($conditional, "compare_value") ? $conditional->compare_value : "";

				$data_conditional_output["chain"][$c] = array(
					"compare_target" => $compare_value,
					"operator"       => $conditional->operator_chain,
					"value"          => $conditional->value_chain
				);
			}

			// todo: check chain / filter
			/*if (!empty($conditional->operator_chain)) {
				foreach ($conditional->operator_chain as $chain) {
					$chain = (object) 
					if (empty($chain->))
				}
			}*/
		}

		return $data_conditional_output;
	}

	static public function is_valid_conditional_row($conditional) {
		$conditional = (object) $conditional;

		if (empty($conditional->action)) return false;

		return true;
	}
}