<?php

class Ezfc_frontend {
	private static $_instance = null;

	public $options = array();
	public $replace_values;
	public $shortcode_atts = array();
	public $smtp;

	public $math_constants = array(
		"pi"      => M_PI,
		"pi2"     => M_PI_2,
		"pi4"     => M_PI_4,
		"1pi"     => M_1_PI,
		"2pi"     => M_2_PI,
		"sqrtpi"  => M_SQRTPI,
		"2sqrtpi" => M_2_SQRTPI,
		"e"       => M_E,
		"euler"   => M_EULER,
		"lnpi"    => M_LNPI,
		"ln2"     => M_LN2,
		"ln10"    => M_LN10,
		"log2e"   => M_LOG2E,
		"log10e"  => M_LOG10E
	);
	public $form;
	public $tables;
	public $wpdb;
	private $debug_enabled = false;

	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;

		$this->tables = array(
			"debug"          => "{$this->wpdb->prefix}ezfc_debug",
			"elements"       => "{$this->wpdb->prefix}ezfc_elements",
			"files"          => "{$this->wpdb->prefix}ezfc_files",
			"forms"			 => "{$this->wpdb->prefix}ezfc_forms",
			"forms_elements" => "{$this->wpdb->prefix}ezfc_forms_elements",
			"forms_options"  => "{$this->wpdb->prefix}ezfc_forms_options",
			"forms_views"    => "{$this->wpdb->prefix}ezfc_forms_views",
			"options"        => "{$this->wpdb->prefix}ezfc_options",
			"preview"        => "{$this->wpdb->prefix}ezfc_preview",
			"submissions"    => "{$this->wpdb->prefix}ezfc_submissions"
		);

		if (get_option("ezfc_debug_mode", 0) != 0) {
			$this->debug_enabled = true;
			error_reporting(E_ALL);
		}

		$this->calculated_values          = array();
		$this->calculated_values_subtotal = array();
		$this->elements                   = $this->elements_get();
		$this->element_js_vars            = array();
		$this->forms                      = array();
		$this->form_elements              = array();
		$this->mail_output                = array();
		$this->replace_values             = array();
		$this->submission_data            = array();
		$this->pdf                        = null;

		// misc vars
		$this->dec_point = get_option("ezfc_email_price_format_dec_point", ".");

		// global options cache
		$this->global_options = array(
			"html_convert_encoding"   => maybe_unserialize(get_option("ezfc_html_convert_encoding")),
			"html_force_convert_utf8" => get_option("ezfc_html_force_convert_utf8", 0)
		);

		// filters
		add_filter("ezfc_element_output_text_only", array($this, "filter_text_only"), 0, 3);
		add_filter("ezfc_label_sanitize", array($this, "filter_label_sanitize"));
		add_filter("ezfc_option_label", array($this, "filter_option_label"), 0, 4);
		// email value filter
		add_filter("ezfc_email_value_submitted", array($this, "filter_email_value"), 10, 4);
		add_filter("ezfc_output_form_elements", array($this, "replace_shortcode_element_data"), 10, 3);

		// actions
		add_action("ezfc_after_submission", array($this, "after_submission"), 10, 6);

		// load required files
		require_once(EZFC_PATH . "class.ezfc_conditional.php");
		require_once(EZFC_PATH . "inc/php/form/form.php");
		require_once(EZFC_PATH . "inc/php/form/submission.php");
		require_once(EZFC_PATH . "inc/php/form/summary.php");

		// load pdf extension
		if (!class_exists("EZFC_Extension_PDF") && file_exists(EZFC_PATH . "ext/class.ezfc_pdf.php") && version_compare(PHP_VERSION, "5.3.0") >= 0) {
			require_once(EZFC_PATH . "ext/class.ezfc_pdf.php");
			$this->pdf = new EZFC_Extension_PDF($this);
		}
		// load zapier extension
		if (!class_exists("EZFC_Zapier") && file_exists(EZFC_PATH . "ext/class.zapier.php") && version_compare(PHP_VERSION, "5.3.0") >= 0) {
			require_once(EZFC_PATH . "ext/class.zapier.php");
		}

		// main element classes
		require_once(EZFC_PATH . "inc/php/elements/element.php");
		require_once(EZFC_PATH . "inc/php/elements/element_decor.php");
		require_once(EZFC_PATH . "inc/php/elements/element_options.php");
	}

	// set shortcode adds
	public function set_shortcode_atts($atts) {
		$this->shortcode_atts = $atts;
	}
	// set single shortcode att
	public function set_shortcode_att($att, $value) {
		$this->shortcode_atts[$att] = $value;
	}

	public function debug($msg) {
		if (!$this->debug_enabled) return;

		if (is_array($msg)) $msg = var_export($msg, true);

		$this->wpdb->insert(
			$this->tables["debug"],
			array("msg" => $msg),
			array("%s")
		);
	}

	public function is_error($result = false) {
		if (!$result) return true;
		if (is_array($result) && isset($result["error"])) return true;
		if (is_object($result) && property_exists($result, "error")) return true;

		return false;
	}

	public function add_form($form_id) {
		// check if form exists at all
		if (!$this->form_exists($form_id)) return false;

		// check if form was already added
		if (isset($this->forms[$form_id])) return $this->forms[$form_id];

		// add form with class
		$this->forms[$form_id] = new Ezfc_Form($form_id);

		return $this->forms[$form_id];
	}

	public function find_form_element_class_by_id($fe_id) {
		$return_value = false;

		foreach ($this->forms as $form_id => $form) {
			if (isset($form->form_elements_objects[$fe_id])) {
				$return_value = $form->form_elements_objects[$fe_id];
				break;
			}
		}

		return $return_value;
	}

	public function get_form_object($form_id) {
		return $this->add_form($form_id);
	}

	public function get_form_options_js_vars() {
		$form_vars = array();

		foreach ($this->forms as $form_id => $form) {
			$form_vars[$form_id] = $form->get_form_options_js_vars();
		}

		return $form_vars;
	}

	public function set_current_form($form_id) {
		$this->form = $this->get_form_object($form_id);
	}

	// check if form exists
	public function form_exists($id = 0, $name = null) {
		if ($name) {
			$form = $this->wpdb->get_var($this->wpdb->prepare(
				"SELECT id FROM {$this->tables["forms"]} WHERE name=%s",
				$name
			));
		}
		else {
			$form = $this->wpdb->get_var($this->wpdb->prepare(
				"SELECT id FROM {$this->tables["forms"]} WHERE id=%d",
				$id
			));
		}

		return $form;
	}

	// get form from db
	public function form_get($id, $name = null, $preview = null) {
		if (!$id && !$name && !$preview) return Ezfc_Functions::send_message("error", __("No id or name found.", "ezfc"));

		if ($name) {
			$id = $this->wpdb->get_var($this->wpdb->prepare(
				"SELECT id FROM {$this->tables["forms"]} WHERE name=%s",
				$name
			));

			// no form found
			if (!$id) return Ezfc_Functions::send_message("error", sprintf(__("No form found with name: %s", "ezfc"), $name));
		}

		return $this->add_form($id);
	}

	/**
	 * get form ID by name
	 * @param $name
	 * @return string|null
	 */
	public function form_get_id_by_name($name) {
		return $this->wpdb->get_var($this->wpdb->prepare(
			"SELECT id FROM {$this->tables["forms"]} WHERE name=%s",
			$name
		));
	}

	public function form_get_db_data($id) {
		// get form from array
		if (isset($this->forms[$id])) return $this->forms[$id];

		$res = $this->wpdb->get_row($this->wpdb->prepare(
			"SELECT * FROM {$this->tables["forms"]} WHERE id=%d",
			$id
		));

		if (!$res) return false;

		// form data
		if (property_exists($res, "data")) {
			$res->data = json_decode($res->data);
		}
		else {
			$res->data = json_decode(json_encode(array()));
		}

		return $res;
	}

	public function form_get_preview($id) {
		if ($id === null) return Ezfc_Functions::send_message("error", __("No preview id found.", "ezfc"));

		$res = $this->wpdb->get_row($this->wpdb->prepare(
			"SELECT * FROM {$this->tables["preview"]} WHERE f_id=%d",
			$id
		));

		if (!$res) return Ezfc_Functions::send_message("error", __("No preview form with f_id={$id} found.", "ezfc"));

		// convert form to object
		$form = json_decode($res->data);

		/*if (Ezfc_Functions::is_countable($form->elements) && count($form->elements) > 0) {
			// replace calculate positions with target ids
			// $form_elements = Ezfc_settings::form_elements_prepare_export($form->elements);
			// merge defaults
			// todo
			//$form_elements = $this->form_elements_merge_default($form->elements);
		}*/

		return $form;
	}

	/**
	 * Returns a list of uploaded files by referrer ID
	 * @param $ref_id
	 * @return array|object|null
	 */
	public function form_get_submission_files($ref_id) {
		if (!$ref_id) return array();

		return $this->wpdb->get_results($this->wpdb->prepare(
			"SELECT * FROM {$this->tables["files"]} WHERE ref_id=%s",
			$ref_id
		));
	}

	/**
		elements
	**/
	public function elements_get() {
		$elements = Ezfc_Functions::array_index_key(Ezfc_settings::get_elements(), "id");

		$elements_ext = apply_filters("ezfc_show_backend_elements", array());
		foreach ($elements_ext as $element_name => $element_options) {
			// convert array data to object
			$element_data_json = json_decode(json_encode($element_options));
			$element_data_json->extension = true;

			$elements[$element_name] = $element_data_json;
		}

		return $elements;
	}

	public function element_get($id) {
		if (!$id) return Ezfc_Functions::send_message("error", __("No ID.", "ezfc"));

		$elements = Ezfc_Functions::array_index_key(Ezfc_settings::get_elements(), "id");

		return $elements[$id];
	}

	/**
		form elements
	**/
	public function form_elements_get($id, $index = false, $key = "id", $merge_default = true) {
		if (!$id) return Ezfc_Functions::send_message("error", __("No ID given.", "ezfc"));

		$res = $this->wpdb->get_results($this->wpdb->prepare(
			"SELECT * FROM {$this->tables["forms_elements"]} WHERE f_id=%d ORDER BY position DESC",
			$id
		));

		// merge default
		if ($merge_default) $res = $this->form_elements_merge_default($res);
		// index by key
		if ($index) $res = Ezfc_Functions::array_index_key($res, $key);

		return $res;
	}

	public function form_elements_merge_default($elements) {
		foreach ($elements as &$element) {
			// extension elements (todo)
			if (!isset($this->elements[$element->e_id])) {
				$extension_data = json_decode($element->data);
				if (property_exists($extension_data, "extension")) {
					$element->type = $extension_data->extension;
				}
				
				continue;
			}

			$tmp_element = (array) $this->elements[$element->e_id]->data;
			$tmp_array = (array) json_decode($element->data);

			$element_type = $this->get_element_type($element);

			// do not add default element options for payment element
			if ($element_type == "payment") {
				$data = json_encode(Ezfc_Functions::array_merge_recursive_ignore_options($tmp_element, $tmp_array));
			}
			// add default element options
			else {
				$data = json_encode(Ezfc_Functions::array_merge_recursive_distinct($tmp_element, $tmp_array));
			}

			$element->data = $data;
			$element->data_array = $tmp_array;
			$element->type = $element_type; // needed?
		}

		return $elements;
	}

	public function form_element_get($fe_id) {
		if (!$fe_id) return Ezfc_Functions::send_message("error", __("No ID given.", "ezfc"));

		$res = $this->wpdb->get_row($this->wpdb->prepare(
			"SELECT * FROM {$this->tables["forms_elements"]} WHERE id=%d",
			$fe_id
		));

		return $res;
	}

	/**
		element type
	**/
	public function get_element_type($element) {
		// type in element
		if (property_exists($element, "type")) return $element->type;
		// type from general elements
		if (isset($this->elements[$element->e_id])) return $this->elements[$element->e_id]->type;

		// other
		return "input";
	}

	public function get_form_element_by_id($fe_id, $form_elements = null) {
		// search elements from submission data
		if (!$form_elements && !empty($this->submission_data["form_elements"])) {
			$form_elements = $this->submission_data["form_elements"];
		}

		// no form elements
		if (empty($form_elements)) return false;
		// element not found
		if (!isset($form_elements[$fe_id])) return false;

		return $form_elements[$fe_id];
	}

	/**
	 * @param  String $name - Element name to look for
	 * @param  array $form_elements (optional) - Array of form elements to search in. If no array was provided, submission data elements will be used instead
	 * @return mixed $element - Element with $name. Return false if no element with the given name was found
	 */
	public function get_form_element_by_name($name, $form_elements = array()) {
		// search elements from submission data
		if (!$form_elements && !empty($this->submission_data["form_elements"])) {
			$form_elements = $this->submission_data["form_elements"];
		}

		// no form elements
		if (empty($form_elements)) return false;

		// case insensitive
		$name = strtolower($name);

		foreach ($form_elements as $e_id => $element) {
			$element_data = json_decode($element->data);
			// element was found
			if (property_exists($element_data, "name") && strtolower($element_data->name) == $name) return $element;
		}

		return false;
	}

	/**
		get element js vars
	**/
	public function get_element_js_vars() {
		// todo: concat vars for each form
		return $this->element_js_vars;
	}

	/**
		get submission entry
	**/
	public function submission_get($id) {
		if (!$id) return Ezfc_Functions::send_message("error", __("No ID.", "ezfc"));

		$res = $this->wpdb->get_row($this->wpdb->prepare(
			"SELECT * FROM {$this->tables["submissions"]} WHERE id=%d",
			$id
		));

		return $res;
	}

	/**
		misc functions
	**/
	public function check_input($id, $data_raw, $preview_id=null) {
		if ((!$id || !$data_raw) && $preview_id === null) return Ezfc_Functions::send_message("error", __("No ID or no data.", "ezfc"));

		$data   = $data_raw["ezfc_element"];
		$ref_id = $data_raw["ref_id"];

		// preview
		if ($preview_id !== null) {
			$tmp_form      = $this->form_get_preview($preview_id);
			$form_elements = $tmp_form->elements;
			$options       = Ezfc_Functions::prepare_options_for_preview($tmp_form->options);
		}
		else {
			$form_elements = Ezfc_Functions::array_index_key($this->form_elements_get($id), "id");
			$options       = $this->forms[$id]->get_form_option_values();
		}

		if (!is_array($options) && is_object($options)) {
			$options = (array) $options;
		}

		$step_counter = 0;

		foreach ($form_elements as $fe_id => $form_element) {
			// special field - email double check
			if (strpos($fe_id, "email_check") !== false) continue;

			$element_data = json_decode($form_element->data);
			$verify_value = Ezfc_Functions::get_object_value($element_data, "verify_value", 1);

			// do not verify value
			if ($verify_value == 0) continue;

			$is_extension = false;
			// check for extension
			if (!empty($element_data->extension)) {
				$is_extension = true;
			}

			$element_type = $this->get_element_type($form_element);

			// get required text (from global settings or form options)
			$required_text_element = get_option("ezfc_required_text_element", __("This field is required.", "ezfc"));
			if (!empty($options["required_text_element"])) {
				$required_text_element = $options["required_text_element"];
			}

			// skip non-input fields (and recaptcha since it is verified in ajax.php)
			$skip_check_elements = array("image", "hr", "html", "recaptcha");
			if (in_array($element_type, $skip_check_elements)) continue;

			// check for steps
			if ($element_type == "stepend") {
				if (isset($data_raw["step"]) && $step_counter == (int) $data_raw["step"]) {
					return Ezfc_Functions::send_message("step_valid", "");
				}

				$step_counter++;
			}

			// skip if the field was hidden by conditional logic
			if (isset($data[$fe_id]) && !is_array($data[$fe_id]) && strpos($data[$fe_id], "__HIDDEN__") !== false) continue;

			// checkbox
			if (isset($data[$fe_id]) && is_array($data[$fe_id]) && $element_type == "checkbox") {
				// required check
				if (Ezfc_Functions::array_empty($data[$fe_id])) {
					return Ezfc_Functions::send_message("error", $required_text_element, $fe_id);
				}
			}
			// special check for file uploads
			else if ($element_type == "fileupload" && $element_data->required == 1) {
				$checkfile = $this->wpdb->get_row($this->wpdb->prepare(
					"SELECT id FROM {$this->tables["files"]} WHERE ref_id=%s",
					$ref_id
				));

				if (!$checkfile) return Ezfc_Functions::send_message("error", __("No file was uploaded yet.", "ezfc"), $fe_id);
			}
			else {
				$input_value = null;

				if (isset($data[$fe_id])) {
					$input_value = $data[$fe_id];

					if (is_array($data[$fe_id])) {
						$empty = Ezfc_Functions::array_empty($data[$fe_id]);
					}
					else {
						$input_value = trim($data[$fe_id]);

						// check if submitted data string is empty
						$empty = (!is_string($input_value) || $input_value == "") && $element_type != "fileupload";
					}
				}
				// no submit data for this element exists -> empty
				else {
					$empty = true;
				}

				// filter
				do_action("ezfc_check_input_before", $element_data, $input_value, $fe_id, $form_element, $id);

				// check custom regex first if selected
				if (!empty($element_data->custom_regex) && !empty($element_data->custom_regex_check_first)) {
					if (!empty($element_data->custom_regex) && !preg_match($element_data->custom_regex, $input_value)) {
						return Ezfc_Functions::send_message("error", $element_data->custom_error_message, $fe_id);
					}
				}

				// check if element is required and no value was submitted
				if (!empty($element_data->required) && $empty) {
					return Ezfc_Functions::send_message("error", $required_text_element, $fe_id);
				}

				// check for max length
				if (property_exists($element_data, "max_length") && $element_data->max_length != "" && strlen($input_value) > $element_data->max_length) {
					return Ezfc_Functions::send_message("error", __("Max character length exceeded", "ezfc"), $fe_id);
				}
				// check for min length
				if (property_exists($element_data, "min_length") && $element_data->min_length != "" && strlen($input_value) < $element_data->min_length) {
					return Ezfc_Functions::send_message("error", sprintf(__("This field must be at least %s characters.", "ezfc"), $element_data->min_length), $fe_id);
				}

				// check empty only
				if ($verify_value == 2) continue;

				// run filters
				if (!$empty) {
					switch ($element_type) {
						case "input":
							if (!empty($element_data->custom_regex) && !preg_match($element_data->custom_regex, $input_value)) {
								return Ezfc_Functions::send_message("error", $element_data->custom_error_message, $fe_id);
							}
						break;

						case "datepicker":
							if (!empty($input_value)) {
								$date_format = empty($options["datepicker_format"]) ? "dd/mm/yy" : $options["datepicker_format"];

								$check_date = Ezfc_Functions::get_datepicker_date_from_format($date_format, $input_value, true);

								$date_valid = $check_date && $check_date->format(Ezfc_Functions::date_jqueryui_to_php($date_format)) == $input_value;

								if (!$date_valid) return Ezfc_Functions::send_message("error", __("Please enter a valid date.", "ezfc"));
							}
						break;

						case "daterange":
							if (!is_array($input_value)	||
								!isset($input_value[0]) || !isset($input_value[1]) ||
								!Ezfc_Functions::check_valid_date($options["datepicker_format"], $input_value[0], true) ||
								!Ezfc_Functions::check_valid_date($options["datepicker_format"], $input_value[1], true)) {
								// invalid date
								return Ezfc_Functions::send_message("error", __("Please enter a valid date range.", "ezfc"), $fe_id);
							}

							// check for min/max days
							$date_format = empty($options["datepicker_format"]) ? "dd/mm/yy" : $options["datepicker_format"];
							$days = Ezfc_Functions::count_days_format($date_format, $input_value[0], $input_value[1], $element_data->workdays_only);

							if ($days < $element_data->minDays) {
								return Ezfc_Functions::send_message("error", sprintf(get_option("ezfc_daterange_min_days_error", __("Please select at least %s days.", "ezfc")), $element_data->minDays), $fe_id);
							}
							else if (!empty($element_data->minDays) && !empty($element_data->maxDays) && $days > $element_data->maxDays) {
								return Ezfc_Functions::send_message("error", sprintf(get_option("ezfc_daterange_max_days_error", __("Please select at most %s days.", "ezfc")), $element_data->maxDays), $fe_id);
							}
						break;

						case "email":
							$emails_array = array($input_value);

							if (!empty($element_data->allow_multiple)) {
								$emails_array = explode(",", $input_value);
							}

							foreach ($emails_array as $email_value) {
								$email_value = trim($email_value);

								if (!filter_var($email_value, FILTER_VALIDATE_EMAIL)) {
									return Ezfc_Functions::send_message("error", __("Please enter a valid email address.", "ezfc"), $fe_id);
								}

								// double check email address
								if (property_exists($element_data, "double_check") && $element_data->double_check == 1) {
									$email_check_name = "{$fe_id}_email_check";

									if (!$data[$email_check_name] || $data[$email_check_name] !== $email_value) {
										return Ezfc_Functions::send_message("error", __("Please check your email address.", "ezfc"), $fe_id);
									}
								}

								// block submissions for blacklisted emails
								$blacklist = apply_filters("ezfc_email_blacklist", array());
								$blacklist_message = apply_filters("ezfc_email_blacklist_message", __("This email address is blocked.", "ezfc"));

								if (is_array($blacklist)) {
									foreach ($blacklist as $bm) {
										// check for domain only
										if (strpos($bm, "@") === false) {
											$input_email_domain = explode("@", $email_value);

											// domain wildcard
											if (isset($input_email_domain[1]) && stripos($input_email_domain[1], $bm) !== false) {
												return Ezfc_Functions::send_message("error", $blacklist_message, $fe_id);
											}
										}
										// single email
										else if (stripos($email_value, $bm) !== false) {
											return Ezfc_Functions::send_message("error", $blacklist_message, $fe_id);
										}
									}
								}
							}

							// user email address
							if (property_exists($element_data, "use_address") && $element_data->use_address == 1) {
								$this->submission_data["user_mail"] = $input_value;
							}

						break;

						case "numbers":
							// normalize
							$input_value = $this->normalize_value($input_value);

							if ($element_data->is_number == 1) {
								if (!is_numeric($input_value)) {
									return Ezfc_Functions::send_message("error", __("Please enter a valid number.", "ezfc"), $fe_id);
								}

								if (!property_exists($element_data, "min") || !property_exists($element_data, "max")) continue 2;

								$check_min = $element_data->min;
								$check_max = $element_data->max;

								// override min
								if (isset($data_raw["dynamic_min"]) && isset($data_raw["dynamic_min"][$fe_id])) $check_min = $data_raw["dynamic_min"][$fe_id];
								// override max
								if (isset($data_raw["dynamic_max"]) && isset($data_raw["dynamic_max"][$fe_id])) $check_max = $data_raw["dynamic_max"][$fe_id];

								// min / max values
								if (!empty($check_min) && $input_value < $check_min) return Ezfc_Functions::send_message("error", __("Minimum value:", "ezfc") . $check_min, $fe_id);

								if (!empty($check_max) && $input_value > $check_max) return Ezfc_Functions::send_message("error", __("Maximum value:", "ezfc") . $check_max, $fe_id);
							}
						break;

						case "password":
							$double_check_value = trim($input_value);

							// double check
							if (property_exists($element_data, "double_check") && $element_data->double_check == 1) {
								$double_check_name = "{$fe_id}_double_check";

								if (!$data[$double_check_name] || $data[$double_check_name] !== $double_check_value) {
									return Ezfc_Functions::send_message("error", __("Your confirmation password doesn't match the password.", "ezfc"), $fe_id);
								}
							}
						break;

						case "repeatable_form":
							$repeatable_form = $this->find_form_element_class_by_id($fe_id);
							$repeatable_form_id = $repeatable_form->get_data_value("repeatable_form_id");

							if ($repeatable_form_id != 0) {
								/*
								array (
								  24123 => 
								  array (
								    0 => '0',
								  ),
								  24124 => 
								  array (
								    0 => '26',
								  ),
								  24125 => 
								  array (
								    0 => '10',
								  ),
								  24126 => 
								  array (
								    0 => '520',
								  ),
								)
								
								$result = $this->check_input($repeatable_form_id, array(
									"ezfc_element" => $input_value,
									"ref_id" => "",
								), $preview_id);
								*/
							}
						break;
					}
				}

				// custom filter
				if (!empty($element_data->custom_filter)) {
					$filter_result = apply_filters("ezfc_custom_filter_{$element_data->custom_filter}", $element_data, $input_value, $fe_id, $form_element, $id);

					if (is_array($filter_result) && !empty($filter_result["error"])) {
						return Ezfc_Functions::send_message("error", $filter_result["error"]);
					}
				}
			}

			// also check for extension input
			if ($is_extension) {
				$extension_result = apply_filters("ezfc_ext_check_input_{$element_data->extension}", $input_value, $element_data, $fe_id);

				if (!empty($extension_result["error"])) {
					$error_id = isset($extension_result["id"]) ? $extension_result["id"] : 0;
					return Ezfc_Functions::send_message("error", $extension_result["error"], $error_id);
				}
			}
		}

		// no errors found
		return Ezfc_Functions::send_message("success", "");
	}

	/**
		prepare submission data
	**/
	public function prepare_submission_data($form_id, $data, $force_paypal = false, $ref_id = false, $submission_id = null) {
		$this->form                  = $this->form_get($form_id);
		$this->form_elements         = $this->form->form_elements;
		$this->form_elements_objects = $this->form->form_elements_objects;
		$this->form_options          = $this->form->options;

		// paypal
		if (is_object($data)) {
			$element_data = (array) $data;
		}
		else {
			$element_data = $data["ezfc_element"];

			if (isset($data["ref_id"])) {
				$ref_id = $data["ref_id"];
			}
		}

		$woo_product_id = isset($data["woo_product_id"]) ? $data["woo_product_id"] : 0;
		$elements_visible = array();

		$form_elements_objects_add = array();
		$raw_values = array();

		foreach ($element_data as $fe_id => $raw_value) {
			$raw_values[$fe_id] = $raw_value;

			// visible element
			$elements_visible[$fe_id] = isset($data["elements_visible"][$fe_id]);
		}

		// add to array
		$this->form_elements_objects = $this->form_elements_objects + $form_elements_objects_add;

		$this->submission_data = array_merge($this->submission_data, array(
			"calculated_values"              => isset($data["calculated_values"]) ? $this->normalize_calculated_values($data["calculated_values"]) : array(),
			"elements"                       => Ezfc_Functions::array_index_key($this->elements_get(), "id"),
			"elements_visible"               => $elements_visible,
			"force_authorize"                => false,
			"force_email_target"             => "",
			"force_paypal"                   => $force_paypal,
			"force_stripe"                   => false,
			"force_submit"                   => false,
			"form"                           => $this->form,
			"form_elements"                  => $this->form_elements,
			"form_elements_objects"          => $this->form_elements_objects,
			"form_id"                        => $form_id,
			"generated_price"                => isset($data["generated_price"]) ? $data["generated_price"] : 0,
			"invoice_id"                     => "",
			"newsletter_confirm"             => false,
			"options"                        => $this->form_options,
			"raw_values"                     => $raw_values,
			"ref_id"                         => $ref_id,
			"submission_elements_formatted"  => array(),
			"submission_elements_values"     => array(),
			"submission_elements_values_raw" => array(),
			"submission_id"                  => $submission_id,
			"submission_url"                 => empty($data["url"]) ? "" : $data["url"],
			"woo_cart_item_key"              => empty($data["woo_cart_item_key"]) ? 0 : $data["woo_cart_item_key"],
			"woo_product_id"                 => $woo_product_id
		));

		if (!empty($submission_id)) {
			// replace after submission placeholders
			$this->replace_values["id"]            = $submission_id;
			$this->replace_values["submission_id"] = $submission_id;
			$this->replace_values["invoice_id"]    = $this->generate_invoice_id($this->submission_data, $submission_id);
		}

		$this->submission_data = apply_filters("ezfc_prepare_submission_data", $this->submission_data);

		return $this->submission_data;
	}

	/**
		prepare replace values
	**/
	public function prepare_replace_values($submission_data = null, $replace_values = array()) {
		if (!$submission_data) $submission_data = $this->submission_data;

		// get uploaded files
		$files        = $this->form_get_submission_files($submission_data["ref_id"]);
		$files_output = "";

		if (count($files) > 0) {
			$files_output = "<p>" . __("Files", "ezfc") . "</p>";

			foreach ($files as $file) {
				$filename      = basename($file->file);
				$url           = site_url() . "/ezfc-file.php?file_id={$file->id}";
				$files_output .= "<p><a href='{$url}' target='_blank'>{$filename}</a></p>";
			}
		}

		// form name
		$form = $this->form_get($submission_data["form_id"]);
		$form_name = empty($form->name) ? "" : $form->name;

		// build replace values array
		$this->replace_values = array_merge($this->replace_values, array(
			"files"                   => $files_output,
			"form_id"                 => $submission_data["form_id"],
			"form_name"               => $form_name,
			"ip"                      => $_SERVER["REMOTE_ADDR"],
			"page_break"              => "<div style='page-break-before: always;'></div>",
			"result"                  => isset($replace_values["result"]) ? $replace_values["result"] : "",
			"result_default"          => isset($replace_values["result_default"]) ? $replace_values["result_default"] : "",
			"result_simple"           => isset($replace_values["result_simple"]) ? $replace_values["result_simple"] : "",
			"result_values"           => isset($replace_values["result_values"]) ? $replace_values["result_values"] : "",
			"result_values_submitted" => isset($replace_values["result_values_submitted"]) ? $replace_values["result_values_submitted"] : "",
			"submission_url"          => $submission_data["submission_url"],
			"total"                   => $this->number_format(isset($replace_values["total"]) ? $replace_values["total"] : 0)
			// random placeholders will be generated in replace_values_text
		));

		// replace placeholders with form values
		foreach ($submission_data["form_elements"] as $fe_id => $fe) {
			$fe_data = json_decode($submission_data["form_elements"][$fe_id]->data);

			// raw value
			$value_to_replace          = "";
			$value_to_replace_calc     = "";
			$value_to_replace_image    = "";
			$value_to_replace_unescape = "";

			if (isset($submission_data["raw_values"][$fe_id])) {
				$value_to_replace          = $this->get_text_from_input($fe_data, $submission_data["raw_values"][$fe_id], $fe_id);
				$value_to_replace_unescape = wp_specialchars_decode($value_to_replace);
				$value_to_replace_calc     = $this->get_calculated_target_value_from_input($fe_id, $submission_data["raw_values"][$fe_id]);
				$value_to_replace_image    = $this->get_text_from_input($fe_data, $submission_data["raw_values"][$fe_id], $fe_id, "image");
			}

			// default
			$this->replace_values[$fe_data->name] = $value_to_replace;
			// calculated value
			$this->replace_values[$fe_data->name . "*"] = $value_to_replace_calc;
			// unescaped value
			$this->replace_values[$fe_data->name . "!"] = $value_to_replace_unescape;
			// image
			$this->replace_values[$fe_data->name . "_image"] = $value_to_replace_image;
		}

		$this->replace_values = array_merge($this->replace_values, $this->get_frontend_replace_values());

		return $this->replace_values;
	}

	/**
		insert submission
	**/
	public function insert($id, $data, $ref_id, $send_mail = true, $payment = array(), $force_add = false) {
		if (!$id || !$data || !$this->submission_data) return Ezfc_Functions::send_message("error", __("No ID or no data.", "ezfc"));

		if (count($payment) < 1) {
			$payment = array(
				"id"             => EZFC_PAYMENT_ID_DEFAULT,
				"token"          => "",
				"transaction_id" => 0
			);
		}

		// spam protection
		$anonymize_ip = get_option("ezfc_anonymize_ip", 1);
		$spam_time = $this->submission_data["options"]["spam_time"];
		if (!is_numeric($spam_time)) $spam_time = 1;

		// do not check for spam when IP anonymization is on
		if ($anonymize_ip != 0) {
			$spam = $this->wpdb->get_row($this->wpdb->prepare(
				"SELECT 1 FROM {$this->tables["submissions"]} WHERE ip='%s' AND date>=DATE_ADD(NOW(), INTERVAL %d SECOND)",
				$_SERVER["REMOTE_ADDR"], -$spam_time
			));

			// spamming
			if ($spam) {
				return Ezfc_Functions::send_message("error", sprintf(__("Spam protection: you need to wait %s seconds before you can submit anything.", "ezfc"), $spam_time));
			}
		}

		$this->debug("Add submission to database start: id={$id}, send_mail={$send_mail}");

		// check for user mail address + create extension list
		$extension_list = array();
		$user_mail = "";
		foreach ($data as $fe_id => $value) {
			// double check
			if (strpos($fe_id, "email_check") !== false || strpos($fe_id, "double_check") !== false) continue;

			// element could not be found
			if (!isset($this->submission_data["form_elements_objects"][$fe_id])) {
				return Ezfc_Functions::send_message("error", sprintf(__("Element #%s could not be found.", "ezfc"), $fe_id));
			}

			$element      = $this->submission_data["form_elements_objects"][$fe_id];
			$element_data = $element->data;

			// user email address
			if (property_exists($element_data, "use_address") && $element_data->use_address == 1) {
				$user_mail = $this->submission_data["raw_values"][$fe_id];
				$this->debug("User email address found: {$user_mail}");
			}

			// newsletter signup
			if (property_exists($element_data, "options") && is_array($element_data->options) && count($element_data->options) > 0) {
				// check if element data was submitted
				if (isset($this->submission_data["raw_values"][$fe_id])) {
					$selected_options = (array) $this->submission_data["raw_values"][$fe_id];

					foreach ($selected_options as $selected_option) {
						// invalid option
						if (!isset($element_data->options[$selected_option])) continue;

						if ($element_data->options[$selected_option]->value == "__newsletter_signup__") {
							$this->submission_data["newsletter_confirm"] = true;
						}
						else if ($element_data->options[$selected_option]->value == "__force_submit__") {
							$this->submission_data["force_submit"] = true;
							$force_add = true;
						}
					}
				}
			}

			// store type
			if (property_exists($element_data, "store_type")) {
				switch ($element_data->store_type->type) {
					case "none": $data[$fe_id] = ""; break;
					case "md5": $data[$fe_id] = md5($value); break;
					case "sha1": $data[$fe_id] = hash("sha1", $value); break;
					case "sha256": $data[$fe_id] = hash("sha256", $value); break;
					case "sha512": $data[$fe_id] = hash("sha512", $value); break;
					case "php":
						if (property_exists($element_data->store_type, "func") && function_exists($element_data->store_type->func)) {
							$data[$fe_id] = $element_data->store_type->func($value);
						}
					break;
				}

				if (isset($data[$fe_id . "_double_check"])) {
					$data[$fe_id . "_double_check"] = $data[$fe_id];
				}
			}

			if (!empty($element_data->extension)) {
				$extension_list[] = array(
					"form_element"      => $element,
					"form_element_data" => $element_data,
					"raw_value"         => $this->submission_data["raw_values"][$fe_id]
				);
			}
		}

		// mail output
		$output_data = $this->get_mail_output($this->submission_data);

		// check minimum value
		if (isset($this->submission_data["options"]["min_submit_value"]) && (float) $output_data["total"] < (float) $this->submission_data["options"]["min_submit_value"]) {
			$min_submit_value_text = sprintf($this->submission_data["options"]["min_submit_value_text"], $this->submission_data["options"]["min_submit_value"]);

			return Ezfc_Functions::send_message("error", $min_submit_value_text);
		}

		// woo add to cart
		$add_to_cart = get_option("ezfc_woocommerce", 0) == 1 && $this->submission_data["options"]["woo_disable_form"] == 0;
		$insert_id   = 0;

		/**
			* hook: before submission
			* @param int $form_id ID of this form
		**/
		do_action("ezfc_before_submission", $id, $this->submission_data);

		if (!$add_to_cart || $force_add) {
			$ip = $_SERVER["REMOTE_ADDR"];
			$anonymize_ip = get_option("ezfc_anonymize_ip", 2);
			// anonymize IP
			if ($anonymize_ip != 0) {
				require_once(EZFC_PATH . "ezplugins/class.ez_ip_anonymizer.php");
				$ip = EZ_IP_Anonymizer::anonymize($ip, $anonymize_ip);
			}

			$data_json = json_encode($data, JSON_HEX_APOS | JSON_HEX_QUOT);
			$output_content = $output_data["result_default"];

			// insert into db
			$res = $this->wpdb->insert(
				$this->tables["submissions"],
				array(
					"f_id"           => $id,
					"data"           => $data_json,
					"content"        => $output_content,
					"ip"             => $ip,
					"ref_id"         => $ref_id,
					"total"          => $output_data["total"],
					"payment_id"     => $payment["id"],
					"transaction_id" => $payment["transaction_id"],
					"token"          => $payment["token"],
					"user_mail"      => $user_mail
				),
				array(
					"%d",
					"%s",
					"%s",
					"%s",
					"%s",
					"%f",
					"%d",
					"%s",
					"%s",
					"%s"
				)
			);

			if (!$res) {
				$this->debug(sprintf(__("Unable to add submission: %s", "ezfc"), $this->wpdb->last_error));
				return Ezfc_Functions::send_message("error", __("Submission failed.", "ezfc"));
			}
			
			$insert_id = $this->wpdb->insert_id;
			$this->debug("Successfully added submission to db: id={$insert_id}");
		}

		// put submission id into submission data
		$this->submission_data["submission_id"] = $insert_id;

		// replace after submission placeholders
		$this->replace_values["id"]            = $insert_id;
		$this->replace_values["submission_id"] = $insert_id;
		$this->replace_values["__token"]       = $payment["token"];
		$this->replace_values["invoice_id"]    = $this->generate_invoice_id($this->submission_data, $insert_id);
		$this->submission_data["invoice_id"]   = $this->replace_values["invoice_id"];

		$output_data["user"]  = stripslashes($this->replace_values_text($output_data["user"]));
		$output_data["admin"] = stripslashes($this->replace_values_text($output_data["admin"]));
		$output_data["pdf"]   = stripslashes($this->replace_values_text($output_data["pdf"]));

		// mailchimp integration
		if ($this->submission_data["newsletter_confirm"] && !empty($user_mail)) {
			if ($this->submission_data["options"]["mailchimp_add"] == 1 && version_compare(PHP_VERSION, "5.3.0") >= 0) {
				// load mailchimp api wrapper
				require_once(EZFC_PATH . "lib/mailchimp/wrapper.php");
				$mailchimp_api_key = get_option("ezfc_mailchimp_api_key");

				if (!empty($mailchimp_api_key)) {
					$mailchimp = Ezfc_Mailchimp_Wrapper::get_instance($mailchimp_api_key);
					$mres = $mailchimp->post("lists/{$this->submission_data["options"]["mailchimp_list"]}/members", array(
						"email_address" => $user_mail,
						"status" => "pending"
					));

					if (!$mres) {
						$this->debug(__("Unable to add email address to MailChimp list.", "ezfc"));
					}
					else {
						$this->debug(sprintf(__("Email address added to MailChimp list id=%s", "ezfc"), $this->submission_data["options"]["mailchimp_list"]));
					}
				}
			}
			// mailpoet integration
			if ($this->submission_data["options"]["mailpoet_add"] == 1) {
				if (!class_exists("WYSIJA")) {
					$this->debug("Mailpoet class does not exist.");
				}
				else {
					$mailpoet_userdata   = array("email" => $user_mail);
					$mailpoet_subscriber = array(
						"user" => $mailpoet_userdata,
						"user_list" => array(
							"list_ids" => array($this->submission_data["options"]["mailpoet_list"])
						)
					);

					$mailpoet_helper = WYSIJA::get("user", "helper");
					$mres = $mailpoet_helper->addSubscriber($mailpoet_subscriber);

					if (!$mres) {
						$this->debug("Unable to add email address to mailpoet list.");
					}
					else {
						$this->debug("Email address added to mailpoet list id={$this->submission_data["options"]["mailpoet_list"]}");
					}
				}
			}
		}

		// add to cart
		if ($add_to_cart && !$force_add && function_exists("WC")) {
			$this->debug("Adding submission to WooCommerce cart...");

			// this is required as anonymous users cannot add an ezfc product to the cart
			if (!WC()->session->has_session()) {
				WC()->session->set_customer_session_cookie(true);
				$this->debug("WC session could not be found -> set customer session cookie");
			}

			// get product ID from form post data (automatically added)
			if (empty($this->submission_data["options"]["woo_product_id"])) {
				$product_id = $this->submission_data["woo_product_id"];
			}
			// get product ID from form options
			else {
				$product_id = $this->submission_data["options"]["woo_product_id"];
			}

			// show full details, simple or values only in checkout page
			$output_details = get_option("ezfc_woocommerce_checkout_details_values", "result");
			
			// write file links into output
			if (!empty($output_data["files_output"])) {
				$output_data[$output_details] .= $output_data["files_output"];
			}

			// check if product was already added with the generated ID
			if (!empty($this->submission_data["woo_cart_item_key"])) {
				WC()->instance()->cart->remove_cart_item($this->submission_data["woo_cart_item_key"]);
			}

			$quantity = 1;
			// use quantity from element
			if (!empty($this->submission_data["options"]["woo_quantity_element"]) && !empty($this->submission_data["raw_values"][$this->submission_data["options"]["woo_quantity_element"]])) {
				$quantity = (int) $this->submission_data["raw_values"][$this->submission_data["options"]["woo_quantity_element"]];
				$output_data["total"] = ((float) $output_data["total"]) / $quantity;
			}

			$cart_item_key = WC()->instance()->cart->add_to_cart($product_id, $quantity, 0, array(), array(
				"ezfc_cart_product_key" => md5(microtime(true)),
				"ezfc_edit_values"      => $this->submission_data["raw_values"],
				"ezfc_form_id"          => $id,
				"ezfc_ref_id"           => $ref_id,
				"ezfc_total"            => $output_data["total"],
				"ezfc_values"           => $output_data[$output_details]
			));

			if (!$cart_item_key) {
				return Ezfc_Functions::send_message("error", sprintf(__("Unable to add product #%s to the cart.", "ezfc"), $product_id));
			}
			
			$this->debug("Submission added to the cart successfully: cart_item_key={$cart_item_key}");

			// update mini cart
			$cart_success_text = get_option("ezfc_woocommerce_text");

			if (get_option("ezfc_woocommerce_update_cart_selector", 0)) {
				ob_start();

				if (defined("WC_VERSION") && version_compare(WC_VERSION, "3.2.4") >= 0) {
					woocommerce_mini_cart();
				}
				else {
					echo wc_get_template( 'cart/mini-cart.php' );
				}

				$cart_content = ob_get_contents();
				ob_end_clean();

				return Ezfc_Functions::send_message(array(
					"success" => $cart_success_text,
					"woo_update_cart" => 1,
					"woocommerce_update_cart_items_number" => WC()->instance()->cart->get_cart_contents_count(),
					"woo_cart_html" => $cart_content
				));
			}

			return Ezfc_Functions::send_message("success", $cart_success_text);
		}

		$this->submission_data["submission_id"] = $insert_id;

		/**
			* @hook before send mails
			* @param int $submission_id The ID of this submission
			* @param float $total The total
			* @param string $user_email User email address (if any)
			* @param int $form_id The ID of this form
			* @param array $output_data Generated output content as array
			* @param array $submission_data Various submission data
		**/
		do_action("ezfc_after_submission_before_send_mails", $insert_id, $output_data["total"], $user_mail, $id, $output_data, $this->submission_data, $this->replace_values);

		if ($send_mail) {
			$this->send_mails(false, $output_data, $user_mail);
		}

		/**
			* @hook submission successful
			* @param int $submission_id The ID of this submission
			* @param float $total The total
			* @param string $user_email User email address (if any)
			* @param int $form_id The ID of this form
			* @param array $output_data Generated output content as array
			* @param array $submission_data Various submission data
		**/
		do_action("ezfc_after_submission", $insert_id, $output_data["total"], $user_mail, $id, $output_data, $this->submission_data, $this->replace_values);

		// extension actions after submission
		foreach ($extension_list as $extension) {
			do_action("ezfc_after_submission_ext_{$extension["form_element_data"]->extension}", $insert_id, $extension["form_element"], $extension["form_element_data"], $extension["raw_value"]);
		}

		$success_text = apply_filters("ezfc_success_text", $this->replace_values_text($this->submission_data["options"]["success_text"]), $this->submission_data);
		//$success_text = do_shortcode($success_text);
		$success_text = apply_filters("the_content", $success_text);

		$insert_key = Ezfc_Functions::get_unique_id();
		// store values for an hour so these can be retrieved on a separate page
		set_transient("ezfc_values_{$insert_key}", array(
			"element_values" => $this->submission_data["submission_elements_values"],
			"total"          => $output_data["total"]
		), 3600);

		$return_array = array(
			"success"    => $success_text,
			"insert_id"  => $insert_id,
			"insert_key" => $insert_key,
		);

		return $return_array;
	}

	/**
		get email output
	**/
	public function get_mail_output($submission_data = null, $summary = false, $mail_content_replace_override = null) {
		if ($submission_data === null) {
			$submission_data = $this->submission_data;
		}

		$out                  = array();
		$out_simple           = array();
		$out_values           = array();
		$out_values_submitted = array();

		// email body font
		$email_font_family = addslashes(get_option("ezfc_email_font_family", "Arial, Helvetica, sans-serif"));

		// output header
		$out_pre = "<html><head><meta charset='utf-8' /></head><body style=\"font-family: {$email_font_family};\">";
		$out_pre = apply_filters("ezfc_email_header", $out_pre, $submission_data);
		// email header after
		$out_pre_after = apply_filters("ezfc_email_header_after", "", $submission_data);

		// email footer before
		$out_suf_before = apply_filters("ezfc_email_footer_before", "", $submission_data);
		// email footer
		$out_suf = "</body></html>";
		$out_suf = apply_filters("ezfc_email_footer", $out_suf, $submission_data);

		$out_pre = $out_pre . $out_pre_after;
		$out_suf = $out_suf_before . $out_suf;

		// result output
		$table_start = "<table class='ezfc-summary-table'>";
		$table_start = apply_filters("ezfc_summary_before", $table_start, $submission_data, $summary);

		$out[]                  = $table_start;
		$out_simple[]           = $table_start;
		$out_values[]           = $table_start;
		$out_values_submitted[] = $table_start;

		// get summary
		$summary_result = (array) $this->form->get_summary();

		// todo: replace all from below

		$i = 0;
		// calculated total
		$total = 0;

		foreach ($submission_data["form_elements"] as $fe_id => $element) {
			$element_data = json_decode($element->data);
			$element_type = $this->get_element_type($element);

			// skip email double check
			if (property_exists($element_data, "email_check") && $element_data->email_check == 1) continue;

			// only continue if submitted value exists
			if (isset($submission_data["raw_values"][$fe_id])) {
				$value = $submission_data["raw_values"][$fe_id];
			}
			else {
				$value = "";
			}

			// skip hidden values
			// refactor: OK
			if ($value == "__HIDDEN__") continue;
			if (is_array($value) && isset($value[0]) && strpos($value[0], "__HIDDEN__") !== false) continue;

			// hack for older extension versions
			if (!empty($element_data->extension)) {
				$element->e_id      = $element_data->extension;
				$element_data->e_id = $element_data->extension;
			}

			// Show HTML elements if needed
			if ($element_type == "html") {
				// skip html elements since it is disabled in the form options
				if ($submission_data["options"]["email_show_html_elements"] == 0) continue;
			
				$value = $element_data->html;
			}
			// post content
			else if ($element_type == "post") {
				if ($element_data->post_id == 0) continue;

				$post = get_post($element_data->post_id);
				$value = $this->apply_content_filter($post->post_content);
			}
			// table order
			else if ($element_type == "table_order") {
				$table_order_is_empty = true;

				if (is_array($value)) {
					foreach ($value as $table_order_value) {
						// table order is not empty
						if ($table_order_value != 0) $table_order_is_empty = false;
					}
				}

				// if table order doesn't have any value, set it to 0 so show_in_email works correctly
				if ($table_order_is_empty) $value = 0;
			}

			$tmp_out = $this->get_element_output($fe_id, $value, $i, $total);

			// check if element will be shown in email
			$show_in_email = false;
			if (property_exists($element_data, "show_in_email")) {
				// always show
				if ($element_data->show_in_email == 1) {
					$show_in_email = true;
				}
				// show if not empty
				else if ($element_data->show_in_email == 2) {
					$show_in_email = Ezfc_conditional::value_not_empty($value);
				}
				// show if not empty and not 0
				else if ($element_data->show_in_email == 3) {
					$show_in_email = Ezfc_conditional::value_not_empty_not_zero($value);
				}
				// conditional show
				else if ($element_data->show_in_email == 4) {
					// invalid -> show nevertheless
					if (empty($element_data->show_in_email_cond)) {
						$show_in_email = true;
					}
					// loop through conditions
					else if (!empty($element_data->show_in_email_cond) && is_array($element_data->show_in_email_cond)) {
						$show_in_email_index = true;
						foreach ($element_data->show_in_email_cond as $i => $cond_element_id) {
							$selected_id = in_array($element_data->show_in_email_operator[$i], array("selected_id", "not_selected_id", "selected_count", "not_selected_count", "selected_count_gt", "selected_count_lt", "in", "not_in"));

							// check if data was submitted
							if (isset($this->submission_data["raw_values"][$cond_element_id])) {
								// check for selected id
								if ($selected_id) {
									$compare_value = $this->get_selected_option_id($cond_element_id, $submission_data["raw_values"][$cond_element_id]);
								}
								else {
									$compare_value = $this->submission_data["raw_values"][$cond_element_id];
								}
							}
							// data wasn't submited -> checkbox or radio options
							else {
								if ($selected_id) $compare_value = array();
								else $compare_value = 0;
							}

							// visibility check
							if (in_array($element_data->show_in_email_operator[$i], array("hidden", "visible"))) {
								$compare_value = !empty($this->submission_data["elements_visible"][$fe_id]);
							}

							$show_in_email_index_compare = Ezfc_conditional::check_operator($compare_value, $element_data->show_in_email_value[$i], $element_data->show_in_email_operator[$i], $compare_value);

							// set flag to false
							if (!$show_in_email_index_compare) $show_in_email_index = false;
						}

						$show_in_email = $show_in_email_index;
					}
				}
				// show if visible
				else if ($element_data->show_in_email == 5) {
					$show_in_email = !empty($this->submission_data["elements_visible"][$fe_id]);
				}
				// show if visible and not empty
				else if ($element_data->show_in_email == 6) {
					$show_in_email = !empty($this->submission_data["elements_visible"][$fe_id]) && Ezfc_conditional::value_not_empty($value);
				}
				// show if visible and not empty and not zero
				else if ($element_data->show_in_email == 7) {
					$show_in_email = !empty($this->submission_data["elements_visible"][$fe_id]) && Ezfc_conditional::value_not_empty_not_zero($value);
				}
			}

			if ($show_in_email) {
				$out[] = $tmp_out["output"];

				if (isset($tmp_out["output_simple"])) {
					$out_simple[] = $tmp_out["output_simple"];
				}
				if (isset($tmp_out["output_values"])) {
					$out_values[] = $tmp_out["output_values"];
				}
				if (isset($tmp_out["output_values_submitted"])) {
					$out_values_submitted[] = $tmp_out["output_values_submitted"];
				}

				$this->submission_data["submission_elements_formatted"][$element_data->name] = $tmp_out["raw_simple"];

				$i++;
			}

			$this->submission_data["submission_elements_values"][$element_data->name]     = $tmp_out["raw_simple"];
			$this->submission_data["submission_elements_values_raw"][$element_data->name] = wp_strip_all_tags(stripslashes($tmp_out["raw_value"]));
			
			if ($tmp_out["override"]) $total  = $tmp_out["total"];
			else                      $total += $tmp_out["total"];
		}

		// use generated total from frontend
		$total = $this->get_total();

		// show total price in email or not
		if ((!$summary && $submission_data["options"]["email_show_total_price"] == 1) || ($summary && $submission_data["options"]["hide_summary_price"] == 0)) {
			$total_text         = isset($submission_data["options"]["email_total_price_text"]) ? $submission_data["options"]["email_total_price_text"] : __("Total", "ezfc");
			$summary_bg_color   = empty($submission_data["options"]["css_summary_total_background"]["color"]) ? "#eee" : $submission_data["options"]["css_summary_total_background"]["color"];
			$summary_text_color = empty($submission_data["options"]["css_summary_total_color"]["color"]) ? "#000" : $submission_data["options"]["css_summary_total_color"]["color"];

			$summary_padding = "5px";
			if (is_array($submission_data["options"]["css_summary_table_padding"]) && $submission_data["options"]["css_summary_table_padding"]["value"] != "") {
				$summary_padding = $submission_data["options"]["css_summary_table_padding"]["value"] . $submission_data["options"]["css_summary_table_padding"]["unit"];
			}

			$style_total_tr    = apply_filters("ezfc_email_style_total_tr", "background-color: {$summary_bg_color}; color: {$summary_text_color}; font-weight: bold;", $this->submission_data);
			$style_total_text  = apply_filters("ezfc_email_style_total_text", "padding: {$summary_padding}; text-align: left;", $this->submission_data);
			$style_total_price = apply_filters("ezfc_email_style_total_price", "padding: {$summary_padding}; text-align: right;", $this->submission_data);

			$price_output  = "<tr class='ezfc-summary-table-total' style='" . esc_attr($style_total_tr) . "'>";
			$price_output .= "	<td style='" . esc_attr($style_total_text) . "' colspan='2'>" . $total_text . "</td>";
			$price_output .= "	<td style='" . esc_attr($style_total_price) . "'>" .  $this->number_format($total) . "</td>";
			$price_output .= "</tr>";

			$price_output_values  = "<tr class='ezfc-summary-table-total' style='" . esc_attr($style_total_tr) . "'>";
			$price_output_values .= "	<td style='" . esc_attr($style_total_text) . "'>" . $total_text . "</td>";
			$price_output_values .= "	<td style='" . esc_attr($style_total_price) . "'>" .  $this->number_format($total) . "</td>";
			$price_output_values .= "</tr>";

			// 3col
			$out[]                  = $price_output;
			$out_simple[]           = $price_output;
			// 2col
			$out_values[]           = $price_output_values;
			$out_values_submitted[] = $price_output_values;
		}

		$table_end = "</table>";
		$table_end = apply_filters("ezfc_summary_after", $table_end, $submission_data, $summary);

		$out[]                  = $table_end;
		$out_simple[]           = $table_end;
		$out_values[]           = $table_end;
		$out_values_submitted[] = $table_end;

		// summary
		if ($summary) {
			switch ($submission_data["options"]["summary_values"]) {
				/*case "result":
					$summary_return = $out;
				break;*/

				// new
				case "result_default":
					$summary_return = $summary_result;
				break;

				case "result":
				case "result_simple":
					$summary_return = $out_simple;
				break;

				case "result_values":
					$summary_return = $out_values;
				break;

				case "result_values_submitted":
					$summary_return = $out_values_submitted;
				break;

				default:
					$summary_return = $summary_result;
				break;
			}

			return implode("", $summary_return);
		}

		// implode content
		$result_content                  = implode("", $out);
		$result_default_content          = implode("", $summary_result);
		$result_simple_content           = implode("", $out_simple);
		$result_values_content           = implode("", $out_values);
		$result_values_submitted_content = implode("", $out_values_submitted);

		// put email text into vars
		$mail_content_replace = $submission_data["options"]["email_text"];
		if ($submission_data["options"]["pp_enabled"] == 1 || $submission_data["force_paypal"]) {
			$mail_content_replace = $submission_data["options"]["email_text_pp"];
		}
		elseif ($submission_data["options"]["payment_force_stripe"] == 1 || $submission_data["force_stripe"]) {
			$mail_content_replace = $submission_data["options"]["email_text_stripe"];
		}

		// use different string for customer email
		if ($mail_content_replace_override) {
			$mail_content_replace = $mail_content_replace_override;
		}

		$mail_admin_content_replace = $submission_data["options"]["email_admin_text"];
		$mail_pdf_content_replace   = $submission_data["options"]["pdf_text"];

		// get uploaded files
		$files        = $this->form_get_submission_files($submission_data["ref_id"]);
		$files_output = "";
		$files_raw    = array();

		if (count($files) > 0) {
			$files_output = "<p>" . __("Files", "ezfc") . "</p>";

			foreach ($files as $file) {
				$filename      = basename($file->file);
				$files_output .= "<p><a href='{$file->url}' target='_blank'>{$filename}</a></p>";
				$files_raw[]   = $file->file;
			}
		}

		// todo
		$this->prepare_replace_values($this->submission_data, array(
			"result"                  => $result_content,
			"result_default"          => $result_default_content,
			"result_simple"           => $result_simple_content,
			"result_values"           => $result_values_content,
			"result_values_submitted" => $result_values_submitted_content,
			"total"                   => $total
		));

		foreach ($this->replace_values as $replace => $replace_value) {
			if (strpos($replace_value, "__HIDDEN__") !== false) $replace_value = "";

			$mail_content_replace       = str_ireplace("{{" . $replace . "}}", $replace_value, $mail_content_replace);
			$mail_admin_content_replace = str_ireplace("{{" . $replace . "}}", $replace_value, $mail_admin_content_replace);
			$mail_pdf_content_replace   = str_ireplace("{{" . $replace . "}}", $replace_value, $mail_pdf_content_replace);
		}

		if ($submission_data["options"]["email_do_shortcode"] == 1) {
			$mail_content_replace       = do_shortcode($mail_content_replace);
			$mail_admin_content_replace = do_shortcode($mail_admin_content_replace);
			$mail_pdf_content_replace   = do_shortcode($mail_pdf_content_replace);
		}

		// put together email contents for user
		$mail_content  = $out_pre;
		$mail_content .= $this->format_mail_content($mail_content_replace);
		$mail_content .= $out_suf;
		$mail_content  = $this->replace_email_theme_content($mail_content);

		// put together email contents for admin
		$mail_admin_content  = $out_pre;
		$mail_admin_content .= $this->format_mail_content($mail_admin_content_replace);
		$mail_admin_content .= $out_suf;
		$mail_admin_content  = $this->replace_email_theme_content($mail_admin_content);

		// put together email contents for pdf
		$mail_pdf_content  = $out_pre;
		$mail_pdf_content .= $mail_pdf_content_replace;
		$mail_pdf_content .= $out_suf;

		return array(
			"user"                    => $mail_content,
			"admin"                   => $mail_admin_content,
			"files"                   => $files_raw,
			"files_output"            => $files_output,
			"pdf"                     => $mail_pdf_content,
			"result"                  => $result_content,
			"result_default"          => $result_default_content,
			"result_simple"           => $result_simple_content,
			"result_values"           => $result_values_content,
			"result_values_submitted" => $result_values_submitted_content,
			"total"                   => $total
		);
	}

	/**
		get mail subject from submission
	**/
	public function get_mail_subject() {
		$mail_subject = $this->submission_data["options"]["email_subject"];

		if ($this->submission_data["force_paypal"]) {
			$mail_subject = $this->submission_data["options"]["email_subject_pp"];
		}
		else if ($this->submission_data["force_stripe"]) {
			$mail_subject = $this->submission_data["options"]["email_subject_stripe"];
		}

		return $mail_subject;
	}


	/**
	 * get formatted output text from submitted data
	 * @param $element_data
	 * @param $value
	 * @param $fe_id
	 * @param string $return_type
	 * @return array|mixed|string|void
	 */
	public function get_text_from_input($element_data, $value, $fe_id, $return_type = "text") {
		$element = $this->submission_data["form_elements"][$fe_id];
		$element_type = $this->get_element_type($element);

		// extension
		if (!empty($element_data->extension)) {
			$extension_settings = apply_filters("ezfc_get_extension_settings_{$element_data->extension}", null);

			if (!empty($extension_settings)) {
				$element_type = $extension_settings["type"];
			}
		}

		$raw_value = isset($this->submission_data["raw_values"][$fe_id]) ? $this->submission_data["raw_values"][$fe_id] : "";
		$raw_value_unsafe = $raw_value;

		if (is_array($raw_value)) {
			$raw_value = array_map("sanitize_text_field", $raw_value);
		}
		else {
			$raw_value = sanitize_text_field($raw_value);
		}
		
		// return value
		$return = "";

		switch ($element_type) {
			case "custom_calculation":
			case "hidden":
			case "numbers":
			case "subtotal":
			case "set":
				if (!empty($element_data->is_currency) && $this->submission_data["options"]["format_currency_numbers_elements"] == 1) {
					$return = $this->number_format($this->normalize_value($raw_value), $element_data);
				}
				else {
					$return = $raw_value;
				}
			break;

			case "checkbox":
				$element_values = (array) $element_data->options;
				$return         = array();

				if (!is_array($value)) $value = (array) $value;

				foreach ($value as $chk_i => $chk_value) {
					// skip hidden field by conditional logic
					if (strpos($chk_value, "__HIDDEN__") !== false) continue;

					if (isset($element_values[$chk_value])) {
						// check and return image URL
						if ($return_type == "image" && !empty($element_values[$chk_value]->image)) {
							$return[] = "<img src='{$element_values[$chk_value]->image}' alt='' />";
						}
						else {
							$return[] = esc_html($element_values[$chk_value]->text);
						}
					}
				}
			break;

			case "dropdown":
			case "radio":
			case "payment":
				// check for options source
				$element_data->options = $this->get_options_source($element_data, $fe_id, $this->submission_data["options"]);

				$element_values = (array) $element_data->options;

				if (isset($element_values[$value])) {
					// check and return image URL
					if ($return_type == "image" && !empty($element_values[$value]->image)) {
						$return = "<img src='{$element_values[$value]->image}' alt='' />";
					}
					// default text
					else {
						$return = esc_html($element_values[$value]->text);
					}
				}
			break;

			case "daterange":
				if (!is_array($raw_value) || count($raw_value) < 2) $raw_value = array("", "");

				$placeholder = explode(";;", $element_data->placeholder);
				$placeholder_values = array(
					isset($placeholder[0]) ? $placeholder[0] : __("From", "ezfc"),
					isset($placeholder[1]) ? $placeholder[1] : __("To", "ezfc")
				);

				$return  = $placeholder_values[0] . ": " . $raw_value[0] . "<br>";
				$return .= $placeholder_values[1] . ": " . $raw_value[1];
			break;

			case "email":
				//$return = "<a href='mailto:{$value}'>{$value}</a>";
				$return = $value;
			break;

			case "textfield":
				$return = $raw_value;

				if (function_exists("sanitize_textarea_field")) {
					$return = sanitize_textarea_field($raw_value_unsafe);
				}

				$return = nl2br($return);
			break;

			case "html":
				$return = $element_data->html;

				// decode HTML entities
				if (get_option("ezfc_email_plain_html", 1)) {
					$return = wp_specialchars_decode($return);
				}

				// shortcode
				if (!empty($element_data->do_shortcode)) {
					$return = do_shortcode($return);
				}
				// content filter
				else {
					$return = $this->apply_content_filter($return);
				}

				// linebreaks / content filter
				if (!empty($element_data->add_linebreaks)) {
					$return = wpautop($return);
				}
			break;

			case "image":
				$return = "";

				if (!empty($element_data->image)) {
					$image_email_style = esc_attr(apply_filters("ezfc_email_table_image_style", "max-width: 30%;"));
					$return = "<img src='{$element_data->image}' alt='{$element_data->alt}' style='{$image_email_style}' />";
				}
			break;

			case "colorpicker":
				$color = $raw_value;
				$return = "{$color}<br><br><div style='width: 100%; height: 50px; background-color: {$color}'></div>";
			break;

			case "post":
				if ($element_data->post_id == 0) return "";

				$post = get_post($element_data->post_id);
				$return = $this->apply_content_filter($post->post_content);
			break;

			case "starrating":
				$return = "{$value}/{$element_data->stars}";
			break;

			case "table_order":
				$element_values = (array) $element_data->options;
				$tmp_return     = array();

				if (!is_array($value)) $value = (array) $value;

				foreach ($value as $chk_i => $chk_value) {
					// empty
					if ($chk_value == 0 || strpos($chk_value, "__HIDDEN__") !== false || !isset($element_values[$chk_i])) {
						$chk_value = 0;
					}

					if (($chk_value == 0 && $element_data->show_empty_values_in_email == 1) || ($chk_value != 0 && $element_data->show_empty_values_in_email == 0) || $element_data->show_empty_values_in_email == 1) {
						$tmp_return[] = "{$chk_value}x " . esc_html($element_values[$chk_i]->text);
					}
				}

				$return = implode("<br>", $tmp_return);
			break;

			// no action
			case "hr":
			case "recaptcha":
			case "stepstart":
			case "stepend":
			break;

			default:
				$return = $raw_value;
			break;
		}

		// checkbox
		if (is_array($return)) {
			return implode(", ", $return);
		}

		return $return;
	}

	/**
		get email output from form elements
	**/
	public function get_element_output($fe_id, $value, $even=1, $total_loop=0) {
		if (!isset($this->submission_data["form_elements"][$fe_id])) {
			return array("output" => "", "output_simple" => "", "total" => 0, "override" => false);
		}
		
		$element      = $this->submission_data["form_elements"][$fe_id];
		$element_data = json_decode($element->data);

		if (!is_array($value)) {
			// skip email double check
			if (strpos($fe_id, "email_check") !== false) return array("output" => "", "output_simple" => "", "total" => 0, "override" => false);

			// skip hidden field by conditional logic
			if (strpos($value, "__HIDDEN__") !== false && empty($element_data->calculate_when_hidden)) return array("output" => "", "output_simple" => "", "total" => 0, "override" => false);
		}

		$disable_normalize       = false;
		$discount_total          = 0;
		$el_out                  = array();
		$el_out_simple           = array();
		$el_out_values           = array();
		$el_out_values_submitted = array();
		$is_extension            = false;
		$price_override          = false;
		$simple_value            = "";
		$total                   = 0;
		$total_out               = array();
		$value_out               = array(); // needed?
		$value_out_simple        = array(); // needed?

		// check for extension
		if (!empty($element_data->extension)) {
			$extension_settings = apply_filters("ezfc_get_extension_settings_{$element_data->extension}", null);
			$element_type = $extension_settings["type"];
			$is_extension = true;
		}
		// inbuilt element
		else {
			$element_type = $this->get_element_type($element);
		}

		// these operators do not need any target or value
		$operator_no_check = array("ceil", "floor", "round", "abs", "subtotal");

		// get output text
		$tmp_out = $this->get_text_from_input($element_data, $value, $fe_id);
		if ($tmp_out !== false) {
			// needed?
			$value_out[] = $tmp_out;
		}
		// simple
		$tmp_out_simple = $this->get_text_from_input($element_data, $value, $fe_id);
		if ($tmp_out_simple !== false) {
			// needed?
			$value_out_simple[] = $tmp_out_simple;
			$simple_value = $tmp_out_simple;
		}

		// calculation output begin
		if (property_exists($element_data, "calculate_enabled") && $element_data->calculate_enabled == 1) {
			// support for older versions
			if (!is_array($value)) $value = array($value);

			// counter value for dateranges so the calculating info will not be displayed twice
			$daterange_counter = 0;
			// checkboxes need their own total price counter in simple result table
			$total_simple = 0;
			// precision
			$precision = empty($element_data->precision) ? null : $element_data->precision;

			foreach ($value as $n => $input_value) {
				// skip second daterange input value
				if ($element_type == "daterange" && $daterange_counter%2==1) continue;

				// table order
				if ($element_type == "table_order") {
					if ($input_value == 0) {
						$tmp_total = 0;
					}
					else {
						$table_order_row_array = array(
							"index" => $n,
							"value" => $input_value
						);

						$tmp_total = $this->get_calculated_target_value_from_input($fe_id, $table_order_row_array, $total_loop);
					}
				}
				else {
					$tmp_total = $this->get_calculated_target_value_from_input($fe_id, $input_value, $total_loop);
				}

				// increase element price counter
				if ($element_type == "checkbox" || $element_type == "table_order") {
					$total_simple += $tmp_total;
				}

				// price details output
				$tmp_total_out = array();
				// show price for current element
				$show_price = true;

				// calculate value * factor
				if (property_exists($element_data, "factor") && $value) {
					if (empty($element_data->factor) || !is_numeric($element_data->factor)) $element_data->factor = 1;

					// special calculation for daterange element
					if ($element_type == "daterange") {
						// check for correct format
						if (!is_array($value) || count($value) < 2) $value = array(0, 0);

						$datepicker_format = $this->submission_data["options"]["datepicker_format"];
						$days = Ezfc_Functions::count_days_format($datepicker_format, $value[0], $value[1], $element_data->workdays_only);

						$tmp_total = (double) $days * $element_data->factor;
						$tmp_total_out[] = "= {$days} " . __("day(s)", "ezfc");

						$show_price = false;
						$daterange_counter++;
					}
					else {
						$tmp_total = $this->normalize_value($input_value) * $element_data->factor;
						$tmp_total_out[] = "{$input_value} * {$element_data->factor}";
					}
				}

				// custom calculations
				if (!empty($element_data->calculate)) {
					// transfer "open" bracket data to "close" bracket
					foreach ($element_data->calculate as $calc_row_open_index => $calc_row_open) {
						if (!property_exists($calc_row_open, "target")) continue 2;

						if ($calc_row_open->target == "__open__" && !property_exists($calc_row_open, "reference_index")) {
							// check for valid prio
							if (!property_exists($calc_row_open, "prio")) {
								$calc_row_open->prio = 0;
							}
							else {
								$calc_row_open->prio = (int) $calc_row_open->prio;
							}

							foreach ($element_data->calculate as $calc_row_close_index => $calc_row_close) {
								if ($calc_row_open_index == $calc_row_close_index) continue 2;

								// check for valid prio
								if (!property_exists($calc_row_close, "prio")) {
									$calc_row_close->prio = 0;
								}
								else {
									$calc_row_close->prio = (int) $calc_row_close->prio;
								}

								// find next close bracket with the same priority as the open bracket
								if ($calc_row_close->target == "__close__" && $calc_row_open->prio == $calc_row_close->prio && !property_exists($calc_row_open, "reference_index") && !property_exists($calc_row_close, "reference_index")) {
									$calc_row_close->operator        = $calc_row_open->operator;
									$calc_row_close->reference_index = $calc_row_open_index;
									$calc_row_open->reference_index  = $calc_row_close_index;
								}
							}
						}
					}

					foreach ($element_data->calculate as $calc_index => $calc_array) {
						if (empty($calc_array->operator)) continue 2;
						
						$is_single_operator = in_array($calc_array->operator, $operator_no_check);

						// skip in case of invalid calculation data
						if (!property_exists($calc_array, "target") || ($calc_array->operator == "0" || (!$is_single_operator && ($calc_array->target == "0" && !is_numeric($calc_array->value)))) && ($calc_array->target != "__open__" && $calc_array->target != "__close__")) continue 2;

						// skip open bracket
						if ($calc_array->target == "__open__") {
							$calc_array->value = $tmp_total;
							$tmp_total = 0;
							continue 2;
						}

						// prepare target values
						$target_exists       = isset($this->submission_data["form_elements"][$calc_array->target]);
						$target_element      = false;
						$target_element_data = false;

						// get target values
						if ($target_exists) {
							$target_element = $this->submission_data["form_elements"][$calc_array->target];
							$target_element_data = json_decode($target_element->data);
						}

						$use_target_value = $calc_array->target != "0";
						$use_custom_value = (!$use_target_value && is_numeric($calc_array->value));

						// check if target element exists (only when a target was selected)
						if ($use_target_value && !$use_custom_value && !$target_exists && $calc_array->target != "__open__" && $calc_array->target != "__close__") {
							$tmp_total = 0;
							$tmp_total_out[] = __("No target found:", "ezfc") . $calc_array->target;
						}

						if ($use_target_value || $use_custom_value || $is_single_operator) {
							$target_value = null;
							
							// target value is value of next close bracket
							if ($calc_array->target == "__close__") {
								if (!isset($element_data->calculate[$calc_array->reference_index])) continue 2;

								$target_value = $tmp_total;
								$tmp_total = $element_data->calculate[$calc_array->reference_index]->value;
							}
							else {
								// use value from target element
								if ($use_target_value) {
									// check if calculation target exists
									if (isset($this->submission_data["raw_values"][$calc_array->target])) {
										if (property_exists($calc_array, "use_calculated_target_value")) {
											// use raw target value
											if ($calc_array->use_calculated_target_value == 0) {
												$target_value = $this->get_raw_target_value($calc_array->target, $this->submission_data["raw_values"][$calc_array->target]);
											}
											// use raw target value without factor
											else if ($calc_array->use_calculated_target_value == 3) {
												$target_value = $this->get_raw_target_value($calc_array->target, $this->submission_data["raw_values"][$calc_array->target], false);
											}
											// use calculated target value with subtotal value
											else if ($calc_array->use_calculated_target_value == 1) {
												// if target was already calculated, use the calculated value
												if (isset($this->calculated_values_subtotal[$calc_array->target])) {
													$target_value = $this->calculated_values_subtotal[$calc_array->target];
												}
												// target was not calculated yet
												else {
													$target_value = $this->get_calculated_target_value_from_input($calc_array->target, $this->submission_data["raw_values"][$calc_array->target], $total_loop);
												}

												// if target was already calculated, use the calculated value
												if (isset($this->calculated_values[$calc_array->target])) {
													$target_value += $this->calculated_values[$calc_array->target];
												}
												// target was not calculated yet
												else {
													$target_value += $this->get_calculated_target_value_from_input($calc_array->target, $this->submission_data["raw_values"][$calc_array->target], 0);
												}
											}
											// use calculated target value without subtotal value
											else if ($calc_array->use_calculated_target_value == 2) {
												// if target was already calculated, use the calculated value
												if (isset($this->calculated_values[$calc_array->target])) {
													$target_value = $this->calculated_values[$calc_array->target];
												}
												// target was not calculated yet
												else {
													$target_value = $this->get_calculated_target_value_from_input($calc_array->target, $this->submission_data["raw_values"][$calc_array->target], 0);
												}
											}
										}
										// use raw target value
										else {
											$target_value = $this->get_raw_target_value($calc_array->target, $this->submission_data["raw_values"][$calc_array->target]);
										}
									}
									// calculation target does not exist
									else {
										$target_value = 0;
										$this->debug(sprintf(__("Unable to find calculation target #%d in element #%d in form #%d", "ezfc"), $calc_array->target, $fe_id, $this->submission_data["form_id"]));
									}
								}
								// use custom value
								else {
									$target_value = $calc_array->value;
								}
							}

							// conditionally hidden
							if ($target_value === false) {
								continue 2;
							}
							
							if (!is_null($precision)) {
								$target_value = round($target_value, $precision);
							}

							switch ($calc_array->operator) {
								case "add":
									$tmp_total_out[] = "{$tmp_total} + {$target_value}";
									//$tmp_total       = (double) $tmp_total + $target_value;
								break;

								case "subtract":
									$tmp_total_out[] = "{$tmp_total} - {$target_value}";
									//$tmp_total       = (double) $tmp_total - $target_value;
								break;

								case "multiply":
									$tmp_total_out[] = "{$tmp_total} * {$target_value}";
									//$tmp_total       = (double) $tmp_total * $target_value;
								break;

								case "divide":
									if ($target_value == 0) {
										$tmp_total = 0;
										$tmp_total_out[] = __("Cannot divide by target factor 0", "ezfc");
									}
									else {
										if (property_exists($element_data, "calculate_before") && $element_data->calculate_before == "1") {
											$tmp_total_out[] = "{$target_value} / {$tmp_total}";
											//$tmp_total       = $target_value / (double) $tmp_total;
										}
										else {
											$tmp_total_out[] = "{$tmp_total} / {$target_value}";
											//$tmp_total       = (double) $tmp_total / $target_value;
										}
									}
								break;

								case "equals":
									if (!isset($this->submission_data["form_elements"][$calc_array->target]) && $calc_array->value == "") continue 2;

									// use target element value
									if ($use_target_value) {
										if (property_exists($calc_array, "use_calculated_target_value")) {
											/*if ($calc_array->use_calculated_target_value == 0 && !empty($target_element_data->factor)) {
												$target_factor   = $target_element_data->factor;
												$tmp_total_out[] = "= {$target_factor} * {$currency} {$target_value}";
												$tmp_total       = (double) $target_factor * $target_value;
											}
											else {
												$tmp_total_out[] = "= {$target_value}";	
												$tmp_total       = (double) $target_value;
											}*/

											$tmp_total_out[] = "= {$target_value}";	
											//$tmp_total       = (double) $target_value;
										}
										else {
											$tmp_total_out[] = "= {$target_value}";	
											//$tmp_total       = (double) $target_value;
										}
									}
									// use custom entered value
									else if ($use_custom_value) {
										$tmp_total_out[] = "= {$target_value}";	
										//$tmp_total       = (double) $target_value;
									}
								break;

								case "power":
									$tmp_total_out[] = "{$tmp_total} ^ {$target_value}";
									//$tmp_total       = pow((double) $tmp_total, $target_value);
								break;

								case "ceil":
									$tmp_total_out[] = "ceil({$tmp_total})";
									//$tmp_total       = ceil($tmp_total);
								break;

								case "floor":
									$tmp_total_out[] = "floor({$tmp_total})";
									//$tmp_total       = floor($tmp_total);
								break;

								case "round":
									$tmp_total_out[] = "round({$tmp_total})";
									//$tmp_total       = round($tmp_total);
								break;

								case "abs":
									$tmp_total_out[] = "abs({$tmp_total})";
									//$tmp_total       = abs($tmp_total);
								break;

								case "subtotal":
									$tmp_total_out[] = "subtotal = {$total_loop}";
									//$tmp_total       = $total_loop;
								break;

								case "log":
									$tmp_total_out[] = "log({$target_value})";
									//$tmp_total       = log($target_value);
								break;
								case "log2":
									$tmp_total_out[] = "log2({$target_value})";
									//$tmp_total       = (log10($target_value) / log10(2));
								break;
								case "log10":
									$tmp_total_out[] = "log10({$target_value})";
									//$tmp_total       = log10($target_value);
								break;

								case "sqrt":
									$tmp_total_out[] = "sqrt({$target_value})";
									//$tmp_total       = sqrt($target_value);
								break;
							}
						}
					}
				}

				// overwrite price
				if (property_exists($element_data, "overwrite_price") && $element_data->overwrite_price == 1 && !empty($element_data->add_to_price)) {
					$price_override = true;
					//$total          = $tmp_total;

					$tmp_total_out[] = "<strong>" . __("Price override", "ezfc") . "</strong>";
				}

				// discount
				$discount_total_compare_value = $tmp_total;
				// calculate discounts only if element type needs to be calculated separately (subtotal, custom_calculation etc. already have the discount value retrieved from the frontend)
				if (property_exists($element_data, "discount") && count($element_data->discount) > 0 && !in_array($element_type, array("subtotal", "custom_calculation", "set"))) {
					foreach ($element_data->discount as $discount) {
						// check if operator is empty
						if (empty($discount->operator)) continue 2;

						$discount->range_min = trim($discount->range_min);
						$discount->range_max = trim($discount->range_max);

						// if fields are left blank, set min/max to infinity
						if ($discount->range_min === "") $discount->range_min = -INF;
						if ($discount->range_max === "") $discount->range_max = INF;

						if ($discount_total_compare_value >= $discount->range_min && $discount_total_compare_value <= $discount->range_max) {
							$discount->value = (float) $discount->discount_value;
							$add_to_total = true;

							switch ($discount->operator) {
								case "add":
									$tmp_total_out[] = __("Discount:", "ezfc") . " + " . $this->number_format($discount->discount_value, $element_data, true);
									$discount_total  = $discount->discount_value;
								break;

								case "subtract":
									$tmp_total_out[] = __("Discount:", "ezfc") . " - " . $this->number_format($discount->discount_value, $element_data, true);
									$discount_total  = -$discount->discount_value;
								break;

								case "percent_add":
									$tmp_total_out[] = __("Discount:", "ezfc") . " +{$discount->discount_value}%";
									$discount_total  = $tmp_total * ($discount->discount_value / 100);

									//$add_to_total = false;
								break;

								case "percent_sub":
									$tmp_total_out[] = __("Discount:", "ezfc") . " -{$discount->discount_value}%";
									$discount_total  = -($tmp_total * ($discount->discount_value / 100));

									//$add_to_total = false;
								break;

								case "equals":
									$tmp_total_out[] = __("Discount:", "ezfc") . " = " . $this->number_format($discount->discount_value, $element_data, true);

									// overwrite temporary price here
									$add_to_total   = false;
									$discount_total = 0;

									$total     = $total - $tmp_total + $discount->discount_value;
									//$tmp_total = $discount->discount_value;
								break;

								case "factor":
									$tmp_total_out[] = __("Factor:", "ezfc") . " " . $discount->discount_value;

									$add_to_total   = false;
									$discount_total = 0;

									$total = $total - $tmp_total + $tmp_total * $discount->discount_value;
									//$tmp_total = $tmp_total * $discount->discount_value;
								break;
							}

							if ($add_to_total) {
								$tmp_total += $discount_total;
								$total     += $discount_total;
							}
						}
					}
				}

				// build string for output
				if ($show_price) {
					$value_out_str = !$tmp_total ? "-" : $this->number_format($tmp_total, $element_data);

					if ($tmp_total_out) {
						$value_out_str = implode("<br>", $tmp_total_out) . "<br>= {$value_out_str}";
					}
				}
				else {
					$value_out_str = implode("<br>", $tmp_total_out);
				}

				if ($element_type == "checkbox") {
					$simple_value = !$tmp_total ? "-" : $this->number_format($total_simple, $element_data);
				}
				else if ($element_type == "table_order") {
					$simple_value = "";

					// include non-zero values only if desired
					if (($tmp_total != 0 && $element_data->show_empty_values_in_email == 0) || $element_data->show_empty_values_in_email == 1) {
						$simple_value = !$tmp_total ? "-" : $this->number_format($tmp_total, $element_data);
					}

					$total += $tmp_total;
					$value_out_str = $simple_value;
				}
				// simply use the last element of array output
				else {
					//$last_value   = end((array_values($tmp_total_out)));
					$simple_value = $this->number_format($tmp_total, $element_data);
				}

				// add to total column
				$add_to_total_column = true;

				if ($element_type == "table_order") {
					$disable_normalize = true;
					$add_to_total_column = false;

					if ($value_out_str != "") {
						$add_to_total_column = true;
					}
				}
				
				if ($add_to_total_column) {
					$total_out[] = $value_out_str;
				}

				if (!is_numeric($tmp_total)) $tmp_total = 0;

				$this->calculated_values[$fe_id] = $tmp_total;
				$this->calculated_values_subtotal[$fe_id] = $tmp_total + $total_loop;
			} // calculate element loop end
		} // calculation output end

		// normalize total
		if (!$disable_normalize) {
			$total = $this->normalize_value($total);
		}

		if ($is_extension) {
			$ext_total_out = apply_filters("ezfc_ext_frontend_submission_output_{$extension_settings["id"]}", $total_out, $element_data, $value, $total_loop);
			$simple_value  = apply_filters("ezfc_ext_frontend_submission_output_simple_{$extension_settings["id"]}", $simple_value, $element_data, $value, $total_loop);
			$tmp_out_simple = $simple_value;
			$ext_total_out = $this->number_format($ext_total_out);
			$total_out     = array($ext_total_out);

			$total = apply_filters("ezfc_ext_frontend_submission_value_{$extension_settings["id"]}", $total, $element_data, $value, $total_loop);
		}

		// background colors
		$color_even = empty($this->submission_data["options"]["css_summary_bgcolor_even"]["color"]) ? "#fff" : $this->submission_data["options"]["css_summary_bgcolor_even"]["color"];
		$color_odd  = empty($this->submission_data["options"]["css_summary_bgcolor_odd"]["color"]) ? "#efefef" : $this->submission_data["options"]["css_summary_bgcolor_odd"]["color"];

		$summary_padding = "5px";
		if (is_array($this->submission_data["options"]["css_summary_table_padding"]) && $this->submission_data["options"]["css_summary_table_padding"]["value"] != "") {
			$summary_padding = $this->submission_data["options"]["css_summary_table_padding"]["value"] . $this->submission_data["options"]["css_summary_table_padding"]["unit"];
		}
		
		$tr_bg = $even%2==1 ? $color_even : $color_odd;

		// simple
		$value_out_simple_html = $tmp_out_simple;
		if (is_array($tmp_out_simple)) {
			$value_out_simple_html = implode("<hr style='border: 0; border-bottom: #ccc 1px solid;' />", $tmp_out_simple);
		}

		// what label to show. default = name
		$label_choice = $element_data->name;
		if (property_exists($element_data, "show_in_email_label")) {
			if ($element_data->show_in_email_label == "label" && property_exists($element_data, "label")) {
				$label_choice = $element_data->label;
			} 
			else if ($element_data->show_in_email_label == "custom" && property_exists($element_data, "show_in_email_label_custom")) {
				$label_choice = $element_data->show_in_email_label_custom;
			}
		}

		// filters
		// element name / label
		$label_name         = apply_filters("ezfc_email_label_name", $label_choice, $element_data, $this->submission_data, $element);
		// styles
		$style_tr           = apply_filters("ezfc_email_style_tr", "background-color: {$tr_bg};", $element_data, $this->submission_data, $element);
		$style_label        = apply_filters("ezfc_email_style_label", "padding: {$summary_padding}; vertical-align: top; text-align: left;", $element_data, $this->submission_data, $element);
		$style_value        = apply_filters("ezfc_email_style_value", "padding: {$summary_padding}; vertical-align: top; text-align: left;", $element_data, $this->submission_data, $element);
		$style_value_simple = apply_filters("ezfc_email_style_value_simple", "padding: {$summary_padding}; vertical-align: top; text-align: right;", $element_data, $this->submission_data, $element);
		$style_calc         = apply_filters("ezfc_email_style_calc", "padding: {$summary_padding}; vertical-align: top; text-align: right; width: 150px;", $element_data, $this->submission_data, $element);

		// values
		$simple_value          = apply_filters("ezfc_email_value_formatted", $simple_value, $element_data, $this->submission_data, $element);
		$value_out_simple_html = apply_filters("ezfc_email_value_submitted", $value_out_simple_html, $element_data, $this->submission_data, $element);

		// don't show duplicate value in third row
		if (!property_exists($element_data, "calculate")) $simple_value = "";

		$implode_string = "<hr style='border: 0; border-bottom: #ccc 1px solid;' />";
		if ($element_type == "table_order") {
			$implode_string = "<br>";
			$total_out[] = "= " . $this->number_format($total, $element_data);
		}

		$total_out_string = $total_out;
		if (is_array($total_out)) $total_out_string = Ezfc_Functions::multi_implode($implode_string, $total_out);

		if ($element_type == "table_order") {
			$simple_value = $total_out_string;
		}

		// detailed
		$el_out[] = "<tr style='" . esc_attr($style_tr) . "'>";
		$el_out[] = "	<td style='" . esc_attr($style_label) . "'>{$label_name}</td>";
		$el_out[] = "	<td style='" . esc_attr($style_value) . "'>" . $value_out_simple_html . "</td>";
		$el_out[] = "	<td style='" . esc_attr($style_calc) . "'>" . $total_out_string . "</td>";
		$el_out[] = "</tr>";

		// simple
		$el_out_simple[] = "<tr style='" . esc_attr($style_tr) . "'>";
		$el_out_simple[] = "	<td style='" . esc_attr($style_label) . "'>{$label_name}</td>";
		$el_out_simple[] = "	<td style='" . esc_attr($style_value) . "'>" . $value_out_simple_html . "</td>";
		$el_out_simple[] = "	<td style='" . esc_attr($style_calc) . "'>" . $simple_value . "</td>";
		$el_out_simple[] = "</tr>";

		// values only
		$el_out_values[] = "<tr style='" . esc_attr($style_tr) . "'>";
		$el_out_values[] = "	<td style='" . esc_attr($style_label) . "'>{$label_name}</td>";
		$el_out_values[] = "	<td style='" . esc_attr($style_value_simple) . "'>" . $simple_value . "</td>";
		$el_out_values[] = "</tr>";

		// submitted values only
		$el_out_values_submitted[] = "<tr style='" . esc_attr($style_tr) . "'>";
		$el_out_values_submitted[] = "	<td style='" . esc_attr($style_label) . "'>{$label_name}</td>";
		$el_out_values_submitted[] = "	<td style='" . esc_attr($style_value_simple) . "'>" . $value_out_simple_html . "</td>";
		$el_out_values_submitted[] = "</tr>";

		return array(
			"output"                  => implode("", $el_out),
			"output_simple"           => implode("", $el_out_simple),
			"output_values"           => implode("", $el_out_values),
			"output_values_submitted" => implode("", $el_out_values_submitted),
			"raw"                     => $value,
			"raw_simple"              => $simple_value,
			"raw_value"               => $value_out_simple_html,
			"total"                   => $total,
			"override"                => $price_override
		);
	}

	public function number_format($number, $element_data = null, $force_format = false) {
		if ((empty($number) && $number != 0) || !is_numeric($number)) return $number;

		$decimal_numbers = get_option("ezfc_email_price_format_dec_num", 2);
		// check for numeric number
		$decimal_numbers = !is_numeric($decimal_numbers) ? 2 : $decimal_numbers;
		
		// check if element value should be returned plain
		if ($element_data && !$force_format) {
			if (!property_exists($element_data, "is_currency") ||
				(property_exists($element_data, "is_currency") && $element_data->is_currency == 0)) {

				if (property_exists($element_data, "precision") && $element_data->precision != "") {
					$decimal_numbers = $element_data->precision;
				}

				$number_formatted = number_format(
					$number,
					$decimal_numbers,
					$this->dec_point,
					get_option("ezfc_email_price_format_thousand", ",")
				);

				return apply_filters("ezfc_number_format_nocurrency", $number_formatted, $number, $element_data);
			}
		}

		$currency = $this->form->options["currency"];
		$currency_position = $this->form->options["currency_position"];

		// todo
		$number_formatted = @number_format(
			$number,
			$decimal_numbers,
			get_option("ezfc_email_price_format_dec_point", "."),
			get_option("ezfc_email_price_format_thousand", ",")
		);

		if ($currency_position == 0) {
			$number_return = "{$currency} {$number_formatted}";
		}
		else {
			$number_return = "{$number_formatted} {$currency}";
		}

		return apply_filters("ezfc_number_format", $number_return, $number, $element_data, $force_format);
	}

	public function get_calculated_target_value_from_input($target_id, $input_value, $total_loop = 0, $use_factor = true, $precision = null) {
		if (!isset($this->submission_data["form_elements"][$target_id])) return false;

		$normalize_value = true;
		$target = $this->submission_data["form_elements"][$target_id];

		if (isset($this->submission_data["raw_values"][$target_id])) {
			$raw_value = $this->submission_data["raw_values"][$target_id];
		}
		else {
			$raw_value = "";
		}
		$data = json_decode($target->data);

		if (!empty($data->extension)) {
			$element = $data->extension;
		}
		else {
			$element = $this->submission_data["elements"][$target->e_id]->type;
		}

		// checkboxes, radio element, dropdown
		if (property_exists($data, "options") && is_array($data->options) && $element != "table_order") {
			if (is_array($input_value)) {
				if (count($input_value) < 1) return 0;

				// iterate through all checkboxes
				$checkbox_total = 0;
				foreach ($input_value as $i => $checkbox_value) {
					// checkbox index was not found
					if (!array_key_exists($i, $data->options)) return 0;

					$checkbox_total += (float) $data->options[$i]->value;
				}

				return $checkbox_total;
			}
			
			if (!array_key_exists($input_value, $data->options)) return false;
			if (!property_exists($data->options[$input_value], "value")) return false;

			$value = $data->options[$input_value]->value;

			// check for numeric value
			if (!is_numeric($value)) {
				$value = 0;
			}

			// normalize value for numbers
			if (!empty($data->is_number)) {
				// do not normalize value for elements with options due to float numbers (gracious normalize here)
				//$value = $this->normalize_value($value, true);
				$value = (float) $value;
			}
		}
		// table order
		else if ($element == "table_order" && is_array($input_value)) {
			// calculate total array
			if (isset($input_value["index"])) {
				// index wasn't found
				if (!isset($data->options[$input_value["index"]])) return 0;

				$input_value_normalized = (float) $this->normalize_value($input_value["value"]);
				
				$value = (float) $data->options[$input_value["index"]]->value * $input_value_normalized;
			}
			// calculate single row
			else {
				$tmp_value = 0;

				foreach ($input_value as $index => $input_value_row) {
					if (!isset($data->options[$index])) continue;

					$input_value_normalized = (float) $this->normalize_value($input_value_row);

					$tmp_value += (float) $data->options[$index]->value * (float) $input_value_normalized;
				}

				$value = $tmp_value;
			}

			// don't normalize value
			$normalize_value = false;
		}
		else if ($element == "daterange") {
			$datepicker_format = $this->submission_data["options"]["datepicker_format"];

			if (isset($input_value[0]) && isset($input_value[1])) {
				$days = Ezfc_Functions::count_days_format($datepicker_format, $input_value[0], $input_value[1], $data->workdays_only);
				
				$value = $days;
				if (property_exists($data, "factor") && $data->factor && $data->factor !== 0 && $use_factor) {
					$factor = $this->get_factor($data->factor);
					$value *= $factor;
				}
			}
			else {
				$value = 0;
			}
		}
		else if ($element == "custom_calculation") {
			$value = $this->normalize_value($raw_value);
		}
		else if ($element == "repeatable_form") {
			// todo
			$value = "";
		}
		else if (is_array($raw_value)) {
			$value = implode(",", $raw_value);
		}
		else {
			if (!isset($this->submission_data["elements"][$this->submission_data["form_elements"][$target_id]->e_id])) return 0;

			$value = $raw_value;

			// todo: check if target is subtotal / custom calculation value (due to normalize value action)
			//$element_type = $this->get_element_type($this->submission_data["elements"][$this->submission_data["form_elements"][$target_id]->e_id]);

			// if calculation is enabled, normalize value
			/*if (!empty($data->is_number) && !in_array($element_type, array("subtotal", "custom_calculation", "set"))) {
				$value = $this->normalize_value($value);
			}*/

			if (is_numeric($value)) {
				// check for percent calculation
				if (strpos($value, "%") !== false) {
					$value_pct = $value / 100;
					$value     = $total_loop * $value_pct;
				}
				else if (property_exists($data, "factor") && $data->factor && $data->factor !== 0 && $use_factor) {
					$factor = $this->get_factor($data->factor);
					$value *= $factor;
				}
			}
		}

		if (!empty($data->is_number) && $normalize_value) {
			$value = $this->normalize_value($value);
		}

		// conditionally hidden
		if (!is_array($raw_value) && strpos($raw_value, "__HIDDEN__") !== false && empty($data->calculate_when_hidden)) return false;

		// use precision if provided
		if (!is_null($precision) && is_numeric($value)) {
			$value = round($value, $precision);
		}

		return $value;
	}

	public function get_raw_target_value($target_id, $input_value, $use_factor = true, $precision = null) {
		if (!isset($this->submission_data["form_elements"][$target_id])) return false;

		$target_element = $this->submission_data["form_elements"][$target_id];
		$value  = 0;

		if (isset($this->submission_data["raw_values"][$target_id])) {
			$value = $this->submission_data["raw_values"][$target_id];
		}

		$data = json_decode($target_element->data);
		$element_type = $this->get_element_type($target_element);

		if (property_exists($data, "options") && is_array($data->options)) {
			// checkboxes
			if (is_array($input_value)) {
				if (count($input_value) < 1) return 0;

				// iterate through all checkboxes
				$checkbox_total = 0;
				foreach ($input_value as $i => $checkbox_value) {
					// checkbox index was not found
					if (!array_key_exists($i, $data->options)) return 0;

					$checkbox_total += (float) $data->options[$i]->value;
				}

				return $checkbox_total;
			}
			
			if (!array_key_exists($input_value, $data->options)) return false;
			if (!property_exists($data->options[$input_value], "value")) return false;

			$value = $data->options[$input_value]->value;
			// normalize value for numbers
			if (!empty($data->is_number)) {
				// do not normalize value for elements with options due to float numbers (gracious normalize here)
				$value = $this->normalize_value($value);
			}
		}
		else if ($element_type == "daterange") {
			$datepicker_format = $this->submission_data["options"]["datepicker_format"];

			if (isset($input_value[0]) && isset($input_value[1])) {
				$value = Ezfc_Functions::count_days_format($datepicker_format, $input_value[0], $input_value[1], $data->workdays_only);
			}
		}

		if (property_exists($data, "factor") && $use_factor && is_numeric($data->factor) && is_numeric($value)) {
			$factor = $this->get_factor($data->factor);
			$value *= $factor;
		}

		// conditionally hidden
		if (!is_array($value) && strpos($value, "__HIDDEN__") !== false && empty($data->calculate_when_hidden)) return false;

		// use precision if provided
		if (!is_null($precision) && is_numeric($value)) {
			$value = round($value, $precision);
		}

		return $value;
	}

	public function get_raw_submission_value($fe_id) {
		if (!isset($this->submission_data["raw_values"][$fe_id])) return false;

		return $this->submission_data["raw_values"][$fe_id];
	}

	public function get_selected_option_id($target_id, $input_value) {
		if (!isset($this->submission_data["form_elements"][$target_id])) return false;

		$target_element = $this->submission_data["form_elements"][$target_id];
		$value = false;

		$data = json_decode($target_element->data);

		if (property_exists($data, "options") && is_array($data->options)) {
			// checkboxes
			if (is_array($input_value)) {
				if (count($input_value) < 1) return false;

				$value = array();
				foreach ($input_value as $i => $checkbox_value) {
					// checkbox index was not found
					if (!array_key_exists($i, $data->options)) return false;

					$value[] = empty($data->options[$i]->id) ? "" : $data->options[$i]->id;
				}

				return $value;
			}
			else {			
				if (!array_key_exists($input_value, $data->options)) return false;
				if (!property_exists($data->options[$input_value], "id")) return false;

				$value = $data->options[$input_value]->id;
			}
		}

		// conditionally hidden
		if (!is_array($value) && strpos($value, "__HIDDEN__") !== false && empty($data->calculate_when_hidden)) return false;

		return $value;
	}


	/**
	 * calculates total value from submitted data
	 * @param null $precision
	 * @return float|mixed
	 */
	public function get_total($precision = null) {
		$total = $this->submission_data["generated_price"];

		if (!is_null($precision)) {
			$total = round($total, $precision);
		}

		return $total;
	}

	/**
		send mails
	**/
	public function send_mails($submission_id, $custom_mail_output=false, $user_mail=false, $format_mail_output=false, $submission_data=null, $mail_options = array()) {
		$this->debug("Preparing to send mail(s)...");

		// generate email content from submission
		// use $this->prepare_submission_data() first!
		if ($submission_id != false) {
			$submission = $this->submission_get($submission_id);
			$user_mail  = $submission->user_mail;

			$this->mail_output = $custom_mail_output ? $custom_mail_output : $this->get_mail_output($this->submission_data);
		}
		// send emails later
		else if ($custom_mail_output != false) {
			// if $format_mail_output is true (when emails will be sent later using WooCommerce), we need to format the mail output before.
			$this->mail_output = $format_mail_output ? $this->get_mail_output($custom_mail_output) : $custom_mail_output;
		}
		// generate email content from submission data
		else {
			$this->mail_output = $this->get_mail_output($submission_data);
		}

		// raw mail output given, convert to array
		if (!is_array($this->mail_output)) {
			$this->mail_output = array(
				"user"                    => $custom_mail_output,
				"admin"                   => $custom_mail_output,
				"files"                   => array(),
				"files_output"            => "",
				"pdf"                     => "",
				"result"                  => "",
				"result_default"          => "",
				"result_simple"           => "",
				"result_values"           => "",
				"result_values_submitted" => "",
				"total"                   => 0
			);
		}

		// make it a bit more difficult for you to find this watermark. :)
		$info_add = "<br><br><p><small>P"."o"."w"."e"."r"."e"."d"." b"."y"." <a href='http://www.e"."z"."p"."l"."u"."g"."i"."n"."s.de/link/ezfc-free-mail/'>"."e"."z"." F"."o"."r"."m"." C"."a"."l"."c"."u"."l"."a"."t"."o"."r</a></small></p>";
		$this->mail_output["admin"] .= $info_add;
		$this->mail_output["user"]  .= $info_add;

		// use smtp
		$use_smtp = get_option("ezfc_email_smtp_enabled")==1 ? true : false;
		if ($use_smtp) {
			$this->smtp_setup();

			if ($this->submission_data["options"]["email_subject_utf8"]) {
				$this->smtp->CharSet = "UTF-8";
			}
		}

		$attachment_admin    = array();
		$attachment_customer = array();

		$email_target = $this->submission_data["options"]["email_recipient"];
		if (!empty($this->submission_data["force_email_target"])) {
			$email_target = $this->submission_data["force_email_target"];
		}

		// do not send emails to admin by option
		if (isset($mail_options["send_to_admin"]) && !$mail_options["send_to_admin"]) {
			$email_target = "";
		}
		// do not send emails to admin by option
		if (isset($mail_options["send_to_customer"]) && !$mail_options["send_to_customer"]) {
			$user_mail = "";
		}

		$this->debug("Target email: $user_mail");

		// admin mail
		if (!empty($email_target)) {
			$mail_admin_headers   = array();

			$mail_admin_headers_charset = "Content-type: text/html;";
			if ($this->submission_data["options"]["email_subject_utf8"]) {
				$mail_admin_headers_charset .= " charset=UTF-8";
			}
			$mail_admin_headers[] = $mail_admin_headers_charset;

			// sendername recipient
			if (!empty($this->submission_data["options"]["email_admin_sender_recipient"])) {
				$sender_name_user = $this->replace_values_text($this->submission_data["options"]["email_admin_sender_recipient"]);
				$sender_name_user = apply_filters("ezfc_send_admin_email_from", stripslashes($sender_name_user), $this->submission_data);
				$mail_admin_headers[] = "From: {$sender_name_user}";

				if ($use_smtp) {
					// Convert syntax: Name <hello@ezplugins.de>
					$mail_sendername_recipient_array = explode("<", $sender_name_user);
					if (count($mail_sendername_recipient_array) > 1) {
						$mail_from_address = str_replace(">", "", $mail_sendername_recipient_array[1]);

						$this->smtp->setFrom($mail_from_address, $mail_sendername_recipient_array[0]);
					}
				}
			}

			// add reply-to
			if (!empty($user_mail) && $this->submission_data["options"]["email_add_reply_to"] == 1) {
				$mail_admin_headers[] = "Reply-to: {$user_mail}";

				if ($use_smtp) {
					$this->smtp->addReplyTo($user_mail);
				}
			}

			// file upload attachment
			if (!empty($this->submission_data["options"]["email_send_files_attachment"])) {
				$attachment_admin = $this->mail_output["files"];
			}
			$attachment_admin = apply_filters("ezfc_submission_attachments_admin", $attachment_admin, $this->submission_data["submission_id"], $this->submission_data["options"]);

			// admin subject
			$admin_subject = $this->submission_data["options"]["email_admin_subject"];
			$admin_subject = $this->replace_values_text($admin_subject);
			$admin_subject = stripslashes($admin_subject);
			$admin_subject = $this->submission_data["options"]["email_subject_utf8"] ? '=?utf-8?B?' . base64_encode($admin_subject) . '?=' : $admin_subject;

			$res = false;

			// smtp
			if ($use_smtp) {
				$email_target_array = explode(",", $email_target);
				foreach ($email_target_array as $email_target_tmp) {
					$this->smtp->addAddress($email_target_tmp);
				}

				$this->smtp->Subject = $admin_subject;
				//$this->smtp->Body    = $this->format_mail_content($this->mail_output["admin"]);
				$this->smtp->Body    = $this->mail_output["admin"];

				if ($attachment_admin) {
					foreach ($attachment_admin as $file) {
						$this->smtp->AddAttachment($file);
					}
				}

				try {
					$res = $this->smtp->send() ? 1 : 0;
				}
				catch (Exception $e) {
					$this->debug(sprintf(__("Unable to send SMTP mails to ADMIN: %s", "ezfc"), $e->getMessage()));
				}
			}
			// wp mail
			else {
				try {
					$res = wp_mail(
						$email_target,
						$admin_subject,
						$this->mail_output["admin"],
						$mail_admin_headers,
						$attachment_admin
					);
				}
				catch (Exception $e) {
					$this->debug(sprintf(__("Unable to send WP mails to ADMIN: %s", "ezfc"), $e->getMessage()));
				}
			}

			$this->debug("Email delivery to admin: $res ({$email_target})");
			$this->debug(var_export($mail_admin_headers, true));
		}
		else {
			$this->debug("No admin email recipient found.");
		}

		// user mail
		if (!empty($user_mail)) {
			// headers
			$mail_headers = array();

			$mail_headers_charset = "Content-type: text/html;";
			// utf8 encoding
			if ($this->submission_data["options"]["email_subject_utf8"]) {
				$mail_headers_charset .= " charset=UTF-8";
			}
			$mail_headers[] = $mail_headers_charset;

			// subject
			$mail_subject = $this->get_mail_subject();
			// custom mail subject
			if (isset($mail_options["subject"])) {
				$mail_subject = $mail_options["subject"];
			}
			$mail_subject = $this->replace_values_text($mail_subject);
			$mail_subject = stripslashes($mail_subject);
			$mail_subject = $this->submission_data["options"]["email_subject_utf8"] ? '=?utf-8?B?' . base64_encode($mail_subject) . '?=' : $mail_subject;

			// text
			$mail_text = $this->mail_output["user"];
			if (isset($mail_options["text"])) {
				$mail_text = $mail_options["text"];
			}

			// "from" name
			$mail_from = "";
			if (!empty($this->submission_data["options"]["email_admin_sender"])) {
				$mail_from = $this->submission_data["options"]["email_admin_sender"];
				$mail_from = stripslashes($mail_from);
				$mail_headers[] = "From: {$mail_from}";
			}

			// add custom reply-to email address
			if (!empty($this->submission_data["options"]["email_reply_to_address"])) {
				$mail_headers[] = "Reply-To: {$this->submission_data["options"]["email_reply_to_address"]}";
			}

			// add extra attachments from form options
			$add_attachments = $this->submission_data["options"]["email_attachments"];
			if (!empty($add_attachments)) {
				$add_attachments_ids = explode(",", $add_attachments);

				foreach ($add_attachments_ids as $attachment) {
					$attachment_customer[] = get_attached_file($attachment);
				}
			}

			// attachments sent to the customer
			$attachment_customer = apply_filters("ezfc_submission_attachments_customer", $attachment_customer, $this->submission_data["submission_id"], $this->submission_data["options"]);

			$res = false;

			// smtp
			if ($use_smtp) {
				// remove admin first
				$this->smtp->ClearAddresses();
				$this->smtp->ClearCCs();
				$this->smtp->ClearBCCs();
				$this->smtp->ClearAttachments();
				$this->smtp->ClearReplyTos();

				// Convert syntax: Name <hello@ezplugins.de>
				$mail_from_array = explode("<", $mail_from);
				if (count($mail_from_array) > 1) {
					$mail_from_address = str_replace(">", "", $mail_from_array[1]);

					$this->smtp->setFrom($mail_from_address, $mail_from_array[0]);
				}

				$this->smtp->addAddress($user_mail);
				$this->smtp->Subject = $mail_subject;
				//$this->smtp->Body    = $this->format_mail_content($this->mail_output["user"]);
				$this->smtp->Body    = $mail_text;

				// add custom reply-to email address
				if (!empty($this->submission_data["options"]["email_reply_to_address"])) {
					$this->smtp->addReplyTo($this->submission_data["options"]["email_reply_to_address"]);
				}

				if ($attachment_customer) {
					foreach ($attachment_customer as $file) {
						$this->smtp->AddAttachment($file);
					}
				}

				try {
					$res = $this->smtp->send() ? 1 : 0;
				}
				catch (Exception $e) {
					$this->debug(sprintf(__("Unable to send SMTP mails to CUSTOMER: %s", "ezfc"), $e->getMessage()));
				}
			}
			else {
				try {
					$res = wp_mail(
						$user_mail,
						$mail_subject,
						$mail_text,
						$mail_headers,
						$attachment_customer
					);
				}
				catch (Exception $e) {
					$this->debug(sprintf(__("Unable to send WP mails to CUSTOMER: %s", "ezfc"), $e->getMessage()));
				}
			}

			$this->debug("Email delivery to user: $res");
			$this->debug(var_export($mail_headers, true));
		}
		else {
			$this->debug("No user email found.");
		}
	}

	/**
		output
	**/
	public function get_output($id=null, $name=null, $product_id=null, $theme=null, $preview=null, $counter = 0) {
		$form = $this->form_get($id, $name);
		if (!$form) return sprintf(__("Invalid form: %s", "ezfc"), "ID = {$id} | Name = {$name}");
		if ($this->is_error($form)) return $form;

		$output = $form->get_output($product_id, $theme, $preview, $counter);

		$this->element_js_vars[$id] = $form->get_element_js_vars();

		return $output;
	}


	/**
		insert file upload info to db
	**/
	public function insert_file($f_id, $ref_id, $file) {
		// insert into db
		$res = $this->wpdb->insert(
			$this->tables["files"],
			array(
				"f_id"   => $f_id,
				"ref_id" => $ref_id,
				"url"    => $file["url"],
				"file"   => $file["file"]
			),
			array(
				"%d",
				"%s",
				"%s",
				"%s"
			)
		);

		if (!$res) return Ezfc_Functions::send_message("error", __("File entry failed.", "ezfc"));

		return Ezfc_Functions::send_message("success", $this->wpdb->insert_id);
	}

	/**
		remove uploaded file
	**/
	public function remove_file($file_id, $ref_id) {
		$res = $this->wpdb->delete(
			$this->tables["files"],
			array(
				"id"     => $file_id,
				"ref_id" => $ref_id
			),
			array(
				"%d",
				"%s"
			)
		);

		// error or unknown file
		if (!$res) return Ezfc_Functions::send_message("error", __("Unable to delete file.", "ezfc"));

		// file successfully deleted
		return Ezfc_Functions::send_message("success", 1);
	}

	public function submission_paypal_get($token) {
		$submission = $this->wpdb->get_row($this->wpdb->prepare(
			"SELECT id, f_id, data, ref_id, total, payment_id, transaction_id, token FROM {$this->tables["submissions"]} WHERE token=%s",
			$token
		));

		return $submission;
	}

	public function update_submission_paypal($token, $transaction_id) {
		if (!$token) return Ezfc_Functions::send_message("error", __("No token.", "ezfc"));

		$submission = $this->submission_paypal_get($token);

		// no submission with $token found
		if (!$submission || count($submission) < 1) return Ezfc_Functions::send_message("error", __("Unable to find submission.", "ezfc"));
		// check if transaction ID is present (i.e. payment has already been processed)
		if (!empty($submission->transaction_id)) return Ezfc_Functions::send_message("error", __("Payment has already been processed.", "ezfc"));

		$this->wpdb->update(
			$this->tables["submissions"],
			array("transaction_id" => $transaction_id),
			array("id" => $submission->id),
			array("%s"),
			array("%d")
		);

		// reset some session data
		$_SESSION["Payment_Amount"] = null;

		return array("submission" => $submission);
	}

	/**
		when using ob_get() and ob_clean() in a special way, the applied content filter will cause the page to be blank.
	**/
	public function apply_content_filter($content) {
		return apply_filters(get_option("ezfc_content_filter", "the_content"), $content);
	}

	/**
		generate invoice id
	**/
	public function generate_invoice_id($submission_data, $insert_id) {
		$prefix = $submission_data["options"]["invoice_prefix"];
		$suffix = $submission_data["options"]["invoice_suffix"];
		$method = $submission_data["options"]["invoice_method"];

		$counter_id = $insert_id;

		// use form submission counter
		if ($method == "form") {
			$form_counter_id = $this->wpdb->get_var($this->wpdb->prepare("
				SELECT COUNT(id)
				FROM {$this->tables["submissions"]}
				WHERE f_id=%d
			", $submission_data["form_id"]));

			$counter_id = $form_counter_id;
		}
		// global counter
		else if ($method == "global") {
			$counter_id = $insert_id;
		}
		// via option
		else if ($method == "option") {
			$counter_id = get_option("ezfc_invoice_counter_id", 1);
		}

		$insert_id = $counter_id;
		$insert_id = apply_filters("ezfc_invoice_counter_id", $insert_id, $counter_id, $submission_data);

		// build invoice with prefix / suffix
		$invoice_id = $prefix . $insert_id . $suffix;

		// replace text values with predefined values
		$invoice_id = $this->replace_values_text($invoice_id);

		// filter
		$invoice_id = apply_filters("ezfc_invoice_id", $invoice_id, $counter_id, $submission_data);

		return $invoice_id;
	}

	/**
	 * filters
	 * @param $value_out_simple_html
	 * @return string
	 */
	public function filter_email_value($value_out_simple_html) {
		$value_out_simple_html = wp_specialchars_decode($value_out_simple_html);
		$value_out_simple_html = wp_kses($value_out_simple_html, wp_kses_allowed_html( 'post' ));

		return $value_out_simple_html;
	}

	public function filter_label_sanitize($text) {
		//$text = htmlentities
		return $text;
	}

	public function filter_option_label($text = "", $checkbox_id = "", $option_index = 1, $return_text_only = false) {
		// convert to HTML chars if enabled
		if (get_option("ezfc_allow_option_html", 0) == 1) {
			$text = wp_specialchars_decode($text);
		}

		$text = str_replace("{{n}}", ($option_index + 1), $text);

		if ($return_text_only) return $text;

		return "<label for='{$checkbox_id}'>{$text}</label>";
	}

	public function filter_text_only($el_text, $data, $options) {
		$value = property_exists($data, "value") ? $data->value : "";

		if (!empty($data->text_only)) {
			// text before/after
			$text_before = property_exists($data, "text_before") ? $data->text_before : "";
			$text_after  = property_exists($data, "text_after")  ? $data->text_after : "";

			$data->class .= " ezfc-hidden";
			$el_value = isset($value) ? $value : "";

			$el_text .= "<span class='ezfc-text-wrapper'>";

			// before
			$el_text .= "<span class='ezfc-text-before'>{$text_before}</span>";

			// prepare price text
			$price_text = "<span class='ezfc-text'>{$el_value}</span>";
			// currency
			if (Ezfc_Functions::get_object_value($data, "is_currency", 0) == 1 && !empty($options["format_currency_numbers_elements"])) {
				$currency_text = "<span class='ezfc-text-currency'>{$options["currency"]}</span>";

				if ($options["currency_position"] == 0) $price_text = $currency_text . $price_text;
				else $price_text = $price_text . $currency_text;
			}

			// price text
			$el_text .= $price_text;
			// after
			$el_text .= "<span class='ezfc-text-after'>{$text_after}</span>";

			$el_text .= "</span>";
		}

		return $el_text;
	}

	/**
		get external file
	**/
	public function get_external_file($url) {
		$response  = wp_remote_get(esc_url_raw($url));
		$get_value = wp_remote_retrieve_body($response);

		if (is_wp_error($response)) {
			$get_value = sprintf(__("Unable to retrieve external value: %s", "ezfc"), $response->get_error_message());
		}
		else if (is_wp_error($get_value)) {
			$get_value = sprintf(__("Unable to retrieve external value: %s", "ezfc"), $response->get_error_message());
		}

		return $get_value;
	}

	/**
		format mail content
	**/
	public function format_mail_content($content) {
		//if ($this->submission_data["options"]["email_nl2br"] == 1 && Ezfc_Functions::get_array_value($this->submission_data["options"], "email_theme", "default") == "default") {
		if ($this->submission_data["options"]["email_nl2br"] == 1) {
			$content = wpautop($content);
		}

		return apply_filters("ezfc_email_content", $content, $this->submission_data);
	}

	/**
		replace email theme content
	**/
	public function replace_email_theme_content($content = "") {
		global $style;

		// default styles
		$style = array(
			"company"     => get_option("ezfc_company_name", ""),
			"font_family" => addslashes(get_option("ezfc_email_font_family", "Arial, Helvetica, sans-serif")),
			"logo"        => get_option("ezfc_logo", ""),
			"text_footer" => get_option("ezfc_email_text_footer", "")
		);

		// email theme
		$theme = sanitize_file_name($this->submission_data["options"]["email_theme"]);
		// get template content
		$template = $this->get_template("email/{$theme}", "email");

		if ($template) {
			// replace custom text with content
			$content = str_replace("{{custom_text}}", $content, $template);

			// font family
			$content = str_replace("{{font_family}}", $style["font_family"], $content);
			// company text
			$content = str_replace("{{company}}", $style["company"], $content);
			// logo
			$content = str_replace("{{logo}}", $style["logo"], $content);
			// text footer
			$content = str_replace("{{text_footer}}", wpautop($style["text_footer"]), $content);
		}

		return $content;
	}

	/**
	 * replace shortcode element data
	 * [ezfc id="123" replace="numbers:value=123,label=New Label; custom_calculation:value=1" /]
	 * @param $form_elements
	 * @return mixed
	 */
	public function replace_shortcode_element_data($form_elements) {
		if (!isset($this->shortcode_atts["replace"])) return $form_elements;

		$replace_elements_array = explode(";", $this->shortcode_atts["replace"]);
		if (count($replace_elements_array) < 1) return $form_elements;

		$replace_elements = array();
		foreach ($replace_elements_array as $replace_element) {
			$element_array = explode(":", $replace_element);
			// check if valid element array statement
			if (count($element_array) < 1) continue;

			$replace_element_name = strtolower(trim($element_array[0]));
			$replace_element_key_value_array = explode(",", $element_array[1]);

			// check if valid assignment
			if (count($replace_element_key_value_array) < 1) continue;

			$replace_elements[$replace_element_name] = array();

			foreach ($replace_element_key_value_array as $key_value_array) {
				$replace_element_assignment = explode("=", $key_value_array);

				if (count($replace_element_assignment) < 1) continue;

				$element_key = trim($replace_element_assignment[0]);
				$element_value = trim($replace_element_assignment[1]);

				$replace_elements[$replace_element_name][$element_key] = $element_value;
			}
		}

		foreach ($form_elements as &$form_element) {
			$element_name = strtolower(Ezfc_Functions::get_array_value($form_element->data_array, "name", ""));
			// no replace element found
			if (!isset($replace_elements[$element_name])) continue;

			$element_has_changed = false;

			foreach ($replace_elements[$element_name] as $replace_element_key => $replace_element_value) {
				if (!isset($form_element->data_array[$replace_element_key])) continue;

				$form_element->data_array[$replace_element_key] = $replace_element_value;
				// changed flag
				$element_has_changed = true;
			}

			if ($element_has_changed) {
				// reconvert back
				$form_element->data = json_encode($form_element->data_array);
			}
		}

		return $form_elements;
	}

	/**
		replace special calculation values / constants
	**/
	public function replace_calculation_values($value) {
		return isset($this->math_constants[$value]) ? $this->math_constants[$value] : $value;
	}

	/**
		replace elementnames with ids
	**/
	public function replace_elementnames_with_ids($text, $form_elements) {
		foreach ($form_elements as $form_element) {
			$fe_id   = $form_element->id;
			$fe_data = json_decode($form_element->data);

			if (empty($fe_data->name)) continue;

			$text = str_ireplace("{{" . strtolower($fe_data->name) . "}}", "ezfc_functions.get_value_from({$fe_id})", $text);
		}

		// check if (invalid) placeholders remain
		$text_check_invalid = preg_match("/{{.+?}}/i", $text);

		// return false for error check
		if ($text_check_invalid) {
			$text_error = "ez Form Calculator: " . __("Invalid custom calculation: replace element does not exist. Please check all placeholders for their correct names.", "ezfc");
			$text = "if (console) console.warn('" . esc_attr($text_error) . "'); return 0;";
		}

		return $text;
	}

	public function replace_element_label_text($text, $element_data) {
		// replace values
		$text = $this->replace_values_text($text, $this->get_frontend_replace_values());
		// placeholders
		$text = $this->get_listen_placeholders($element_data, $text);

		if (get_option("ezfc_allow_label_shortcodes", 1)) {
			$text = do_shortcode($text);
		}

		return $text;
	}

	/**
		replace predefined values
	**/
	public function replace_values_text($text, $replace_values_override = array()) {
		// extensions may not contain replace values set by this class, so check for override values
		$replace_values = count($replace_values_override) > 0 ? $replace_values_override : $this->replace_values;

		foreach ($replace_values as $replace => $replace_value) {
			if (strpos($replace_value, "__HIDDEN__") !== false) $replace_value = "";

			$text = str_ireplace("{{" . $replace . "}}", $replace_value, $text);
			$text = str_ireplace("{{" . $replace . ":striptags}}", wp_strip_all_tags($replace_value), $text);
		}

		// placeholders with callback
		try {
			// random num
			$text = preg_replace_callback("/{{rand_num}}/", function() {
				return rand(0, 9);
			}, $text);

			// random char
			$text = preg_replace_callback("/{{rand_char}}/", function() {
				return substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 1);
			}, $text);
		}
		catch (Exception $e) {}

		return $text;
	}

	/**
		check for options source
	**/
	public function get_options_source($element_data, $element_id, $form_options) {
		$options = $element_data->options;
		$use_options_source = Ezfc_Functions::get_object_value($element_data, "options_source", 0);

		// no options source
		if (!is_object($use_options_source)) return $options;

		// php function
		if ($use_options_source->source == "php" || $use_options_source->source == "php_merge") {
			$options_func = $use_options_source->value;

			if (function_exists($options_func)) {
				$source_options = $options_func((array) $element_data->options, $element_data, $element_id, $form_options);

				if (is_array($source_options)) {
					$source_options = json_decode(json_encode($source_options));
				}

				if (!is_array($source_options)) {
					$this->debug(sprintf(__("Invalid options format: %s", "ezfc"), "ID: {$element_id}, Function: {$options_func}"));
					return $options;
				}

				$options = $source_options;

				// add to list
				if ($use_options_source->source == "php_merge") {
					$options = array_merge($element_data->options, $source_options);
				}
			}
		}
		// json
		else if ($use_options_source->source == "json" || $use_options_source->source == "json_merge" && filter_var($use_options_source->value, FILTER_VALIDATE_URL)) {
			$json_raw = $this->get_external_file($use_options_source->value);

			$json_options = json_decode($json_raw);

			if (!is_object($json_options) || !property_exists($json_options, "options")) {
				$this->debug(sprintf(__("Invalid JSON file or format: %s", "ezfc"), "ID: {$element_id}, URL: {$use_options_source->value}"));
				return $options;
			}

			$options = $json_options->options;

			if ($use_options_source->source == "json_merge") {
				$options = array_merge($element_data->options, $json_options->options);
			}
		}

		return $options;
	}

	/**
		check for element price and return it with proper formatting
	**/
	public function get_show_price_text($options, $data, $value, $el_type) {
		$show_price = "";

		if ($options["show_element_price"] == 1 && !empty($data->is_number)) {
			if (property_exists($data, "factor") && empty($data->factor)) return $show_price;

			if ($el_type == "numbers" && !empty($data->factor)) {
				$factor = $this->get_factor($data->factor);
				$value = $factor;
			}

			$show_price_text = "({$value})";

			if (!empty($data->is_currency) && is_numeric($value)) {
				$price_text = $this->number_format($value, $data);
				$show_price_text = "({$price_text})";
			}

			$show_price = apply_filters("ezfc_element_show_price", " " . $show_price_text, $el_type);
		}

		return $show_price;
	}

	/**
		bind placeholders
	**/
	public function get_listen_placeholders($data = array(), $text = "") {
		$allowed_retrieve_values = array("value", "name", "index");
		$tmp_text_placeholder    = explode("{{", $text);

		if (is_array($tmp_text_placeholder) && count($tmp_text_placeholder) > 0) {
			foreach ($tmp_text_placeholder as $tmp_placeholder) {
				$placeholder_array = explode("}}", $tmp_placeholder);
				$placeholder_array = array_map("trim", $placeholder_array);

				if (count($placeholder_array) > 0) {
					$placeholder = strtolower($placeholder_array[0]);
					// retrieve value by default
					$value_retrieve = "value";

					// check for defined result
					$defined_result = explode(":", $placeholder);
					if (count($defined_result) > 1) {
						$placeholder    = trim($defined_result[0]);
						$value_retrieve = trim($defined_result[1]);

						if (!in_array($value_retrieve, $allowed_retrieve_values)) $value_retrieve = "value";
					}

					$placeholder_empty = "";
					if (is_object($data) && property_exists($data, "placeholder_empty_text")) {
						$placeholder_empty = $data->placeholder_empty_text;
					}

					$placeholder_html = "<span class='ezfc-html-placeholder' data-listen_target='" . esc_js($placeholder) . "' data-listen_retrieve='{$value_retrieve}' data-empty='" . esc_js($placeholder_empty) . "'></span>";

					$text = str_replace("{{" . $placeholder_array[0] . "}}", $placeholder_html, $text);
				}
			}
		}

		return $text;
	}

	/**
		misc replace values for frontend elements
	**/
	public function get_frontend_replace_values() {
		$values = array(
			"current_url" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://{$_SERVER["HTTP_HOST"]}{$_SERVER["REQUEST_URI"]}",
			"date"      => date_i18n(get_option( 'date_format' ), current_time("timestamp")),
			"date_dd"   => date_i18n(date("d"), current_time("timestamp")),
			"date_d"    => date_i18n(date("j"), current_time("timestamp")),
			"date_mm"   => date_i18n(date("m"), current_time("timestamp")),
			"date_m"    => date_i18n(date("n"), current_time("timestamp")),
			"date_yy"   => date_i18n(date("y"), current_time("timestamp")),
			"date_yyyy" => date_i18n(date("Y"), current_time("timestamp")),
			"date_u"    => date_i18n(date("U"), current_time("timestamp")),
			"time"      => date_i18n(get_option( 'time_format' ), current_time("timestamp"))
		);

		// wp user values
		if (is_user_logged_in()) {
			$user = wp_get_current_user();

			$user_values = array(
				"user"           => $user->display_name,
				"user_email"     => $user->user_email,
				"user_firstname" => $user->user_firstname,
				"user_lastname"  => $user->user_lastname,
				"user_id"        => $user->ID,
				"user_login"     => $user->user_login,
				"user_url"       => $user->user_url
			);
		}
		else {
			$user_values = array(
				"user"           => "",
				"user_email"     => "",
				"user_firstname" => "",
				"user_lastname"  => "",
				"user_id"        => "",
				"user_login"     => ""
			);
		}

		$values = array_merge($values, $user_values);
		$values = apply_filters("ezfc_frontend_replace_values", $values);

		return $values;
	}

	/**
	 * normalize value
	 * @param $value
	 * @param bool $reverse
	 * @param bool $numbers_only
	 * @return float|string|string[]|null
	 */
	public function normalize_value($value, $reverse = false, $numbers_only = true) {
		$return_value = $value;
		if ($numbers_only) $return_value = preg_replace("/[^0-9.,]+/i", "", $value);

		if ($reverse) {
			if ($this->dec_point == ",") {
				$return_value = str_replace(",", "", $return_value);
				$return_value = str_replace(".", ",", $return_value);
			}
		}
		else {
			if ($this->dec_point == ",") {
				$return_value = str_replace(".", "", $return_value);
				$return_value = str_replace(",", ".", $return_value);
			}

			$return_value = str_replace(array(",", "%"), "", $return_value);
			$return_value = (double) $return_value;
		}

		return $return_value;
	}

	/**
		normalize calculated values
	**/
	public function normalize_calculated_values($values) {
		foreach ($values as $value) {
			if (is_array($value)) {
				$value = $this->normalize_calculated_values_array($value);
			}
			else {
				$value = $this->normalize_value($value);
			}
		}

		return $values;
	}

	public function normalize_calculated_values_array($values, $loop_value = 0) {
		if (!is_array($values)) return $loop_value + (float) $values;

		foreach ($values as $key => $item) {
			$loop_value += $this->normalize_calculated_values_array($item, $loop_value);
		}

		return $loop_value;
	}

	/**
		helper functions
	**/
	public function remove_nl($content) {
		return trim(preg_replace('/\s\s+/', ' ', $content));
	}

	/**
		form output template (e.g. payment dialog)
	**/
	public function get_template($template, $default_dir = "", $default_file = "") {
		$check_dirs = array(
			trailingslashit( get_stylesheet_directory() ) . "ezfc/", // (child-) theme
			trailingslashit( get_template_directory() ) . "ezfc/", // (child-) theme
			trailingslashit( EZFC_PATH ) . "templates/" // ezfc default dir
		);

		$file = false;

		foreach ( $check_dirs as $dir ) {
			if ( file_exists( trailingslashit( $dir ) . $template . '.php' ) ) {
				$file = trailingslashit( $dir ) . $template . '.php';
				break;
			}
		}

		// template not found, use default
		if (!$file) {
			$default_dir = sanitize_file_name($default_dir);
			$file = trailingslashit( EZFC_PATH ) . "templates/{$default_dir}/default.php";
		}

		// no template found
		if (!file_exists($file)) {
			if (empty($default_file)) return null;

			// return default file
			return $this->get_template($default_file, $default_dir);
		}

		ob_start();
		include $file;
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	/**
		form element output template (TODO)
	**/
	/*public function get_element_template($element, $element_data) {
		$elements_load_order = array($element, "input");

		foreach ($elements_load_order as $element_load_name) {
			$template_content = $this->get_template("elements/" . $element_load_name, $element_data);

			if (!is_null($template_content)) return $template_content;
		}

		return null;
	}*/

	// smtp setup wrapper
	public function smtp_setup() {
		if ($this->smtp) return;

		$this->smtp = Ezfc_Functions::smtp_setup();
	}

	public function get_json_value($object, $keys) {
		if (!is_object($object)) return __("Returned JSON is not an object.", "ezfc");
		if (!is_array($keys)) return __("No array found.", "ezfc");

		$value = $object;
		foreach ($keys as $key) {
			if (is_object($value)) {
				if (!property_exists($value, $key)) {
					return sprintf(__("Unable to find key: %s", "ezfc"), $key);
				}

				$value = $value->{$key};
			}
			else if (is_array($value)) {
				if (!isset($value[$key])) {
					return sprintf(__("Unable to find array key: %s", "ezfc"), $key);
				}

				$value = $value[$key];
			}
		}

		return $value;
	}

	public function check_conditional_email_target($submission_data = null) {
		if (!$submission_data) $submission_data = $this->submission_data;

		// global conditions
		if (is_object($this->form) && property_exists($this->form, "data") && is_array($this->form->data) && isset($this->form->data["global_conditions"])) {
			$email_target = Ezfc_conditional::get_conditional_email_target($this->form->data->global_conditions);

			if ($email_target !== false) {
				$this->submission_data["force_email_target"] = $email_target;
			}
		}

		$form_elements = $this->form_elements_get($submission_data["form_id"]);
		foreach ($form_elements as $k => $fe) {
			$fe_data = json_decode($fe->data);

			// skip this element if no value was submitted (e.g. step elements)
			if (!isset($submission_data["raw_values"][$fe->id]) || !property_exists($fe_data, "conditional")) continue;

			$email_target = Ezfc_conditional::get_conditional_email_target($fe_data->conditional, $fe->id);

			if ($email_target !== false) {
				$this->submission_data["force_email_target"] = $email_target;
			}
		}
	}

	public function after_submission($insert_id, $total, $user_mail, $id, $output_data, $submission_data) {
		// remove submission
		if (get_option("ezfc_store_submissions", 1) == 0) {
			$this->wpdb->delete(
				$this->tables["submissions"],
				array("id" => $insert_id),
				array("%d")
			);
		}

		// inc counter ID
		$counter_id = (int) get_option("ezfc_invoice_counter_id", 1);
		update_option("ezfc_invoice_counter_id", $counter_id + 1);

		// save submitted values to session
		if (session_id() == "") @session_start();
		// deprecated
		$_SESSION["ezfc_submission_values"] = $submission_data["submission_elements_values"];
	}

	public function get_factor($factor = 1) {
		return do_shortcode($factor);
	}

	// instance
	public static function instance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __clone() {}
	public function __wakeup() {}

	// wrapper for deprecated extensions / customizations
	public function array_index_key($array, $key) {
		return Ezfc_Functions::array_index_key($array, $key);
	}

	public function send_message($type, $msg = "", $id = 0) {
		Ezfc_Functions::send_message($type, $msg, $id);
	}
}