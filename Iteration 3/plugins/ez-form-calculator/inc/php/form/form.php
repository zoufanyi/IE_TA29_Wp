<?php

class Ezfc_Form {
	private $tables;
	private $wpdb;

	public $element_js_vars;
	public $form;
	public $form_elements;
	public $form_elements_css = array();
	public $form_elements_order = array();
	public $form_elements_objects = array();
	public $form_options_js = array();
	public $id;
	public $name;
	public $options;
	public $summary;

	public function __construct($id) {
		global $wpdb;

		$this->wpdb     = $wpdb;
		$this->frontend = Ezfc_frontend::instance();
		$this->id       = $id;

		// order is important
		$this->form                  = $this->frontend->form_get_db_data($id);
		$this->data                  = $this->get_form_data();
		$this->form_elements         = $this->get_form_elements();
		$this->form_elements_objects = $this->get_form_elements_objects();
		$this->options               = $this->get_form_option_values();
		$this->name                  = $this->form->name;
	}

	/**
		add additional css styles to the form
	**/
	public function add_css($css_styles) {
		$this->form_elements_css[] = $css_styles;
	}

	/**
		get additional css styles added by elements
	**/
	public function get_elements_css() {
		return implode("", $this->form_elements_css);
	}

	/**
		elements
	**/
	public function get_elements() {
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

	public function get_form_data() {
		$form_data = array();

		if (is_object($this->form) && property_exists($this->form, "data")) {
			$form_data = $this->form->data;

			if (!is_object($this->form->data) && !is_array($form_data)) {
				$this->form_data = json_decode($this->form->data);
			}
		}

		return (object) $form_data;
	}

	// get single form element
	public function get_form_element($id) {
		return $this->frontend->wpdb->get_row($this->frontend->wpdb->prepare(
			"SELECT * FROM {$this->frontend->tables["forms_elements"]} WHERE id = %d",
			$id
		));
	}

	public function get_form_elements($index = true, $key = "id", $merge = true) {
		$res = $this->frontend->wpdb->get_results($this->frontend->wpdb->prepare(
			"SELECT * FROM {$this->frontend->tables["forms_elements"]} WHERE f_id = %d ORDER BY position DESC",
			$this->id
		));

		if ($merge) {
			foreach ($res as &$element) {
				// extension elements (todo)
				if (!isset($this->frontend->elements[$element->e_id])) {
					$extension_data = json_decode($element->data);
					if (property_exists($extension_data, "extension")) {
						$element->type = $extension_data->extension;
					}
					
					continue;
				}

				$tmp_element = (array) $this->frontend->elements[$element->e_id]->data;
				$tmp_array = (array) json_decode($element->data);

				$element_type = $this->frontend->get_element_type($element);

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
		}

		if ($index) $res = Ezfc_Functions::array_index_key($res, $key);

		return $res;
	}

	public function get_form_elements_objects() {
		$form_element_objects = array();

		foreach ($this->form_elements as $element) {
			// get element class
			$element_class = $this->get_element_class($element);
			$element_class->set_element_data(json_decode($element->data));
			$element_class->set_form($this->form);
			$element_class->set_form_options($this->options);
			$element_class->set_form_wrapper($this);
			$element_class->init();

			// add to form element objects
			$form_element_objects[$element_class->get_id()] = $element_class;
			// add to form order
			$this->form_elements_order[] = (int) $element->id;
		}

		return $form_element_objects;
	}

	public function get_form_options_js_vars() {
		return $this->form_options_js;
	}

	/**
		get object class of element
	**/
	public function get_element_class($element) {
		$element_type = $this->frontend->get_element_type($element);

		$element_file = sanitize_file_name($element_type . ".php");
		$class_file   = EZFC_PATH . "inc/php/elements/{$element_file}";
		if (file_exists($class_file)) {
			require_once($class_file);

			// build class name
			$class_name = "Ezfc_Element_" . ucfirst($element_type);

			if (!class_exists($class_name)) {
				die(sprintf(__("Invalid classname: %s", "ezfc"), $class_name));
			}

			$element_class = new $class_name($this->form, $element, $element->id, $element_type);
		}
		else {
			// default class
			$element_class_default = "Ezfc_Element";

			// check extension
			$element_data = json_decode($element->data);
			if (!empty($element_data->extension)) {
				$element_class_default = apply_filters("ezfc_extension_add_element_classes", $element_class_default, $element_data);
			}

			$element_class = new $element_class_default($this->form, $element, $element->id, $element_type);
		}

		return $element_class;
	}

	/**
		get element class by form element id
	**/
	public function get_element_class_by_id($fe_id) {
		if (!isset($this->form_elements[$fe_id])) return false;

		$element = $this->form_elements[$fe_id];

		return $this->get_element_class($element);
	}

	public function get_element_js_vars() {
		return $this->element_js_vars;
	}

	/**
		get form options
	**/
	public function get_form_options($preview_options = false) {
		$settings = Ezfc_Functions::array_index_key(Ezfc_settings::get_form_options(true), "id");

		// merge values
		if ($preview_options) {
			$settings_db = json_decode(json_encode($preview_options), true);
		}
		else {
			$settings_db = Ezfc_Functions::array_index_key($this->wpdb->get_results($this->wpdb->prepare(
				"SELECT o_id, value FROM {$this->frontend->tables["forms_options"]} WHERE f_id = %d",
				$this->id
			), ARRAY_A), "o_id");
		}

		foreach ($settings as $i => &$setting) {
			if (isset($settings_db[$setting["id"]])) {
				$setting["value"] = maybe_unserialize($settings_db[$setting["id"]]["value"]);
			}
			else {
				$setting["value"] = empty($setting["default"]) ? "" : $setting["default"];
			}
		}

		return $settings;
	}

	public function get_form_option_values($preview_options = false) {
		$settings_tmp = $this->get_form_options($preview_options);

		$settings = array();

		foreach ($settings_tmp as &$setting) {
			$settings[$setting["name"]] = $setting["value"];
		}

		return $settings;
	}

	public function get_output($product_id=null, $theme=null, $preview=null, $counter = 0) {
		if ($counter == 0) {
			global $form;
			global $options;
			global $post;
			global $product;
		}

		$form          = $this->form;
		$form_elements = $this->form_elements;
		$id            = $this->id;
		$name          = $this->name;

		if (!$id && !$name && $preview === null) {
			echo __("No id or name found. Correct syntax: [ezfc id='1' /] or [ezfc name='form-name' /].", "ezfc");
			return;
		}

		// get form by id
		if ($preview !== null) {
			$preview_form = $this->frontend->form_get_preview($preview);
			if (Ezfc_Functions::is_error($preview_form)) {
				echo $preview_form["error"];
				return;
			}

			$form = $preview_form->form;

			// frontend output
			$form_elements = $preview_form->elements;
			$options       = $this->get_form_option_values($preview_form->options);
		}
		else {
			$options = $this->get_form_option_values();
		}

		$this->element_js_vars = array();
		$this->form_data = $this->get_form_data();
		$this->options = apply_filters("ezfc_output_form_options", $options, $form->id);
		$form_elements = apply_filters("ezfc_output_form_elements", $form_elements, $form->id, $this->options);

		// open in popup
		if (Ezfc_Functions::get_array_value($this->options, "popup_enabled", 0) == 1) {
			require_once(EZFC_PATH . "inc/php/form/popup.php");
			new Ezfc_Form_Popup($form->id);
		}

		// begin output
		$html  = "";
		// output begin filter
		$html .= apply_filters("ezfc_form_output_start", "", $form->id);

		// step counter
		$this->current_step = 0;
		// get amount of steps
		$this->step_count = 0;
		$step_titles = array();
		// trigger ID array
		$this->trigger_ids = array();
		// html element output array
		$html_array = array();

		foreach ($form_elements as $i => $element) {
			$element->__counter = $counter;

			$this->element_js_vars[$element->id] = array();
			// element data
			$data = json_decode($element->data);
			// element type
			$element_type = $this->frontend->get_element_type($element);

			// get element class
			$element_object = $this->form_elements_objects[$element->id];
			$element_object->set_counter($counter);
			$element_object->enqueue_scripts();
			$element_object->before_form_output();

			// trigger ids
			$this->trigger_ids[$element->id] = array();
			// html element output
			$html_array[$element->id] = array();

			// skip if extension element
			if (!empty($data->extension) || !isset($this->frontend->elements[$element->e_id])) continue;
			// step counter
			if ($element_type == "stepstart") {
				$step_titles[] = $data->title;
				$this->step_count++;
			}
		}
		
		// additional styles
		$css_label_width = get_option("ezfc_css_form_label_width");
		$css_label_width = empty($css_label_width) ? "" : "style='width: {$css_label_width}'";
		$form_class      = isset($options["form_class"]) ? $options["form_class"] : "";
		$wrapper_class   = "";

		// override theme by shortcode
		if (empty($theme)) {
			$theme = isset($options["theme"]) ? $options["theme"] : "default";
		}

		// theme css
		$theme_file = EZFC_PATH . "themes/{$theme}.css";
		$theme_def  = EZFC_PATH . "themes/slick.css";

		if (!file_exists($theme_file)) {
			$theme = $theme_def;
		}
		// register/enqueue styles
		wp_register_style("ezfc-theme-style-{$theme}", EZFC_URL . "themes/{$theme}.css", array(), EZFC_VERSION);
		wp_enqueue_style("ezfc-theme-style-{$theme}");

		// global custom styling will be added from shortcode
		// form custom styling
		if ($options["load_custom_styling"] == 1) {
			$form_custom_styling  = $options["css_custom_styling"];
			$form_custom_styling .= $options["css_custom_styling_user"];

			wp_add_inline_style("ezfc-css-frontend", $form_custom_styling);

			// font
			if (!empty($options["css_font"])) {
				$font_name = $options["css_font"];
				wp_register_style("ezfc-font-{$font_name}", "//fonts.googleapis.com/css?family={$font_name}");
				wp_enqueue_style("ezfc-font-{$font_name}");
			}
		}

		// center form
		if ($options["form_center"] == 1) $form_class .= " ezfc-form-center";
		// show loading text
		$form_show_loading = get_option("ezfc_form_show_loading", 1);
		if ($form_show_loading) $wrapper_class .= " ezfc-form-loading";
		// image selection style
		$image_selection_style = empty($options["image_selection_style"]) ? "default" : $options["image_selection_style"];
		$form_class .= " ezfc-image-selection-style-{$image_selection_style}";

		// step indicator styling
		$form_class .= " ezfc-step-indicator-" . (Ezfc_Functions::get_array_value($options, "step_indicator_layout", "full-width"));

		// check if woocommerce is used
		$cart_item = null;
		$cart_key  = null;
		$use_woocommerce = 0;
		if ($options["submission_enabled"] == 1) {
			// submission / woocommerce
			if (get_option("ezfc_woocommerce", 0) == 1 && $options["woo_disable_form"] == 0) {
				$use_woocommerce = 1;

				// edit previously added product
				if (!empty($_GET["ezfc_cart_product_key"])) {
					$cart_items = WC()->instance()->cart->get_cart();

					foreach ($cart_items as $cart_item_key => $cart_item_tmp) {
						if (!empty($cart_item_tmp["ezfc_cart_product_key"]) && $cart_item_tmp["ezfc_cart_product_key"] == $_GET["ezfc_cart_product_key"]) {
							$cart_item = $cart_item_tmp;
							$cart_key  = $cart_item_key;
						}
					}
				}
			}
		}

		// check for payment methods
		$stripe_enabled    = get_option("ezfc_stripe_enabled", 0) && $options["stripe_enabled"];
		$stripe_enabled    = apply_filters("ezfc_stripe_check", $stripe_enabled, $id);
		//$authorize_enabled = get_option("ezfc_authorize_enabled", 0) && $options["authorize_enabled"];
		$authorize_enabled = false;

		// global conditions
		$global_conditions = Ezfc_Functions::get_object_value($this->form_data, "global_conditions", null);
		// prepare output
		if ($global_conditions) {
			// add to trigger list
			if (count($global_conditions) > 0) {
				foreach ($global_conditions as $row) {
					if (empty($row->action) || empty($row->target)) continue;

					// check if target is price
					if ($row->target == "price") {
						if (isset($this->form_elements_objects[$row->compare_value_first])) {
							$this->form_elements_objects[$row->compare_value_first]->add_trigger_id();
						}
					}
					else if (isset($this->form_elements_objects[$row->compare_value_first]) && isset($this->form_elements_objects[$row->target])) {
						$this->form_elements_objects[$row->compare_value_first]->add_trigger_id($row->target);
						$this->form_elements_objects[$row->target]->add_trigger_id($row->compare_value_first);
					}
				}
			}

			$global_conditions = Ezfc_conditional::prepare_conditional_js($global_conditions);
		}

		// js output - form_vars
		$this->form_options_js = array_merge($this->form_options_js, array(
			"clear_selected_values_hidden"  => !empty($options["clear_selected_values_hidden"]) ? $options["clear_selected_values_hidden"] : 0,
			"counter_duration"              => isset($options["counter_duration"]) ? $options["counter_duration"] : 1000,
			"counter_interval"              => isset($options["counter_interval"]) ? $options["counter_interval"] : 30,
			"currency"                      => $options["currency"],
			"currency_position"             => $options["currency_position"],
			"datepicker_format"             => $options["datepicker_format"],
			"disable_error_scroll"          => !empty($options["disable_error_scroll"]) ? $options["disable_error_scroll"] : 0,
			"form_elements_order"           => $this->form_elements_order,
			"format_currency_numbers_elements" => $options["format_currency_numbers_elements"],
			"format_number_show_zero"       => $options["format_number_show_zero"],
			"global_conditions"             => $global_conditions,
			"hard_submit"                   => isset($options["hard_submit"]) ? $options["hard_submit"] : 0,
			"has_steps"                     => $this->step_count > 0,
			"hide_all_forms"                => !empty($options["hide_all_forms"]) ? $options["hide_all_forms"] : 0,
			"live_summary_enabled"          => Ezfc_Functions::get_array_value($options, "live_summary_enabled", 0),
			//"payment_force_authorize"       => $options["payment_force_authorize"],
			"payment_force_stripe"          => $options["payment_force_stripe"],
			"payment_info_shown"            => array(
				"authorize" => 0,
				"stripe"    => 0
			),
			"popup_open_auto"               => Ezfc_Functions::get_array_value($options, "popup_open_auto", 0),
			"preview_form"                  => $preview,
			"price_format"                  => !empty($options["price_format"]) ? $options["price_format"] : get_option("ezfc_price_format"),
			"price_position_scroll_top"     => !empty($options["price_position_scroll_top"]) ? $options["price_position_scroll_top"] : 0,
			"price_requested"               => 0,
			"price_show_request_before"     => !empty($options["price_show_request_before"]) ? $options["price_show_request_before"] : 0,
			"price_show_request"            => !empty($options["price_show_request"]) ? $options["price_show_request"] : 0,
			"redirect_forward_values"       => !empty($options["redirect_forward_values"]) ? $options["redirect_forward_values"] : 0,
			"redirect_text"                 => sprintf($options["redirect_text"], $options["redirect_timer"]),
			"redirect_timer"                => $options["redirect_timer"],
			"redirect_url"                  => trim($options["redirect_url"]),
			"refresh_page_after_submission" => $options["refresh_page_after_submission"],
			"reset_after_submission"        => $options["reset_after_submission"],
			"scroll_to_success_message"     => $options["scroll_to_success_message"],
			"selectable_max_error"          => get_option("ezfc_text_error_max_selectable", __("Please select at most %s options.", "ezfc")),
			"selectable_min_error"          => get_option("ezfc_text_error_min_selectable", __("Please select at least %s options.", "ezfc")),
			"show_success_text"             => $options["show_success_text"],
			"show_success_text_duration"    => Ezfc_Functions::get_array_value($options, "show_success_text_duration", 10),
			"step_auto_progress"            => $options["step_auto_progress"],
			"step_count"                    => $this->step_count,
			"step_indicator_start"          => $options["step_indicator_start"],
			"step_reset_succeeding"         => Ezfc_Functions::get_array_value($options, "step_reset_succeeding", 0),
			"step_speed"                    => $options["step_speed"],
			"submission_js_func"            => !empty($options["submission_js_func"]) ? $options["submission_js_func"] : "",
			"submit_text"                   => array(
				//"authorize"   => $options["submit_text_authorize"],
				"default"     => $options["submit_text"],
				"paypal"      => $options["pp_submittext"],
				"request"     => !empty($options["price_show_request_text"]) ? $options["price_show_request_text"] : __("Request price", "ezfc"),
				"stripe"      => $options["submit_text_stripe"],
				"summary"     => $options["summary_button_text"],
				"woocommerce" => $options["submit_text_woo"]
			),
			"summary_enabled"   => $options["summary_enabled"],
			"summary_shown"     => 0,
			"timepicker_format" => $options["timepicker_format"],
			//"use_authorize"     => $options["authorize_enabled"],
			"use_paypal"        => $options["pp_enabled"],
			"use_stripe"        => $options["stripe_enabled"],
			"use_woocommerce"   => $use_woocommerce,
			"verify_steps"      => $options["verify_steps"]
		));
		
		if ($preview !== null) {
			$form_class .= " ezfc-preview";
		}

		$grid_class = empty($options["grid_12"]) ? "ezfc-grid-6" : "ezfc-grid-12";

		$form_action = "";
		if ($options["hard_submit"] == 1) {
			$form_action = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		}

		// wrapper class filter
		$wrapper_class = apply_filters("ezfc_form_wrapper_class", $wrapper_class, $form->id);

		// main wrapper
		$html .= "<div class='ezfc-wrapper ezfc-form-{$form->id} ezfc-theme-{$theme} {$grid_class} {$wrapper_class}'>";

		// form loading text
		if ($form_show_loading) {
			$html .= "<div class='ezfc-form-loading-text'>" . get_option("ezfc_form_show_loading_text", __("Loading...", "ezfc")) . "</div>";
		}

		// adding "novalidate" is essential since required fields can be hidden due to conditional logic
		$html .= "<form class='ezfc-form {$form_class}' id='ezfc-form-{$form->id}' name='ezfc-form[{$form->id}]' action='{$form_action}' data-id='{$form->id}' data-currency='{$options["currency"]}' method='POST' novalidate>";

		// reference
		$html .= "<input type='hidden' name='id' value='{$form->id}' />";
		$html .= "<input type='hidden' name='ref_id' value='' id='ezfc-ref-id-{$form->id}' />"; // filled in with JS

		// woo product id
		// retrieve product ID via global product if it's not set
		if (empty($product_id) && !empty($product) && method_exists($product, "get_id")) {
			$product_id = $product->get_id();
		}

		if (!empty($product_id)) {
			$html .= "<input type='hidden' name='woo_product_id' value='" . esc_attr($product_id) . "' />";
		}

		// woo edit cart key
		if (!is_null($cart_key)) {
			$html .= "<input type='hidden' name='woo_cart_item_key' value='" . esc_attr($cart_key) . "' />";
		}

		// price
		if ($options["currency_position"] == 0) {
			$price_html = "<span class='ezfc-price-currency ezfc-price-currency-before'>{$options["currency"]}</span><span class='ezfc-price-value' data-id='{$form->id}'>0</span>";
		}
		else {
			$price_html = "<span class='ezfc-price-value' data-id='{$form->id}'>0</span><span class='ezfc-price-currency ezfc-price-currency-after'>{$options["currency"]}</span>";
		}

		// total price above form elements
		if ($options["show_price_position"] == 2 || $options["show_price_position"] == 3) {
			$html .= "<div class='ezfc-element ezfc-price-wrapper-element'>";
			$html .= "	<label class='ezfc-label' {$css_label_width}>" . $options["price_label"] . "</label>";
			$html .= "	<div class='ezfc-price-wrapper'>";
			$html .= "		<span class='ezfc-price-prefix'>{$options["price_label_prefix"]}</span>";
			$html .= "		<span class='ezfc-price'>{$price_html}</span>";
			$html .= "		<span class='ezfc-price-suffix'>{$options["price_label_suffix"]}</span>";
			$html .= "	</div>";
			$html .= "</div>";
		}

		// step indicators
		if ($options["step_indicator"] == 1) {
			$html .= "<div class='ezfc-step-indicator'>";

			$indicator_start = (int) $options["step_indicator_start"];
			if (is_nan($indicator_start)) {
				$indicator_start = 0;
			}
			else {
				$indicator_start -= 1;
			}
			$indicator_start = max($indicator_start, 0);

			$s_loop = 1;
			for ($s = $indicator_start; $s < $this->step_count; $s++) {
				$step_add_class = $s == 0 ? "ezfc-step-indicator-item-active" : "";

				if ($options["step_allow_free_navigation"] == 1) {
					$step_add_class .= " ezfc-step-indicator-item-force";
				}

				if ($options["step_use_titles"] == 1) {
					$step_title_text = sprintf($step_titles[$s], $s + 1);
				}
				else {
					$step_title_text = sprintf($options["step_indicator_text"], $s_loop);
				}

				$html .= sprintf("<a class='ezfc-step-indicator-item {$step_add_class}' href='#' data-step='{$s}'>%s</a>", $step_title_text);

				$s_loop++;
			}

			$html .= "</div>";
			$html .= "<div class='ezfc-clear'></div>";
		}

		// begin of form elements
		$html .= "<div class='ezfc-form-elements'>";

		foreach ($form_elements as $i => $element) {
			if (!isset($this->frontend->elements[$element->e_id]) && $element->e_id != 0) {
				$this->frontend->debug(sprintf(__("Element %s does not exist.", "ezfc"), $element->id));
				continue;
			}

			$form_element_output = $this->get_form_element_output($element, $form, $options);
			// add to html array
			$html_array[$element->id] = array(
				"element" => $element,
				"output"  => $form_element_output["html"]
			);
			// add to js output
			$this->element_js_vars[$element->id] = $form_element_output["element_js_vars"];
		}

		// set unique trigger IDs
		foreach ($form_elements as $i => $element) {
			// unique trigger ids
			$this->trigger_ids[$element->id] = array_unique($this->trigger_ids[$element->id]);

			$this->element_js_vars[$element->id]["trigger_ids"] = $this->form_elements_objects[$element->id]->get_trigger_ids($this->trigger_ids[$element->id]);
		}

		$html_element_output_final = $this->build_element_output($html_array);
		// return form elements only
		if ($counter > 0 && !$preview) return $html_element_output_final;
		
		$html .= $html_element_output_final;

		// end of form elements
		$html .= "</div>";

		// summary
		if ($options["summary_enabled"] == 1) {
			$html .= "<div class='ezfc-summary-wrapper ezfc-element ezfc-hidden'>";
			// summary text
			$html .= "  <label class='ezfc-label ezfc-summary-text'>" . $this->frontend->apply_content_filter($options["summary_text"]) . "</label>";
			// actual summary
			$html .= "  <div class='ezfc-summary'></div>";
			$html .= "</div>";
		}

		// price
		if ($options["show_price_position"] == 1 ||	$options["show_price_position"] == 3) {
			$html .= "<div class='ezfc-element ezfc-price-wrapper-element'>";
			$html .= "	<label class='ezfc-label' {$css_label_width}>" . $options["price_label"] . "</label>";
			$html .= "	<div class='ezfc-price-wrapper'>";
			$html .= "		<span class='ezfc-price-prefix'>{$options["price_label_prefix"]}</span>";
			$html .= "		<span class='ezfc-price'>{$price_html}</span>";
			$html .= "		<span class='ezfc-price-suffix'>{$options["price_label_suffix"]}</span>";
			$html .= "	</div>";
			$html .= "</div>";
		}

		// reset button
		if (!empty($options["reset_enabled"]) && $options["reset_enabled"]["enabled"] == 1) {
			$html .= "<div class='ezfc-element ezfc-reset-wrapper'>";
			$html .= "	<label {$css_label_width}></label>";
			$html .= "	<button class='ezfc-btn ezfc-element ezfc-element-reset ezfc-reset' id='ezfc-reset-{$form->id}'>{$options["reset_enabled"]["text"]}</button>";
			$html .= "</div>";
		}

		// submit
		if ($options["submission_enabled"] == 1) {
			// summary
			if ($options["summary_enabled"] == 1) $submit_text = $options["summary_button_text"];
			// submission / woocommerce
			else if ($use_woocommerce == 1) $submit_text = $options["submit_text_woo"];
			// paypal
			else if ($options["pp_enabled"] == 1) $submit_text = $options["pp_submittext"];
			// default
			else $submit_text = $options["submit_text"];

			$submit_text = $this->frontend->get_listen_placeholders(null, $submit_text);

			// button alignment
			$submit_button_wrapper_class = "";
			$submit_button_alignment = Ezfc_Functions::get_array_value($options, "submit_button_alignment", "");
			if (!empty($submit_button_alignment)) {
				$submit_button_wrapper_class .= " {$submit_button_alignment}";
			}

			$html .= "<div class='ezfc-element ezfc-submit-wrapper {$submit_button_wrapper_class}'>";
			$html .= "	<label {$css_label_width}></label>";
			$html .= "	<input class='ezfc-btn ezfc-element ezfc-element-submit ezfc-submit {$options["submit_button_class"]}' id='ezfc-submit-{$form->id}' type='submit' value='" . esc_attr($submit_text) . "' data-element='submit' />";
			// loading icon
			$html .= "	<span class='ezfc-submit-icon'><i class='" . esc_attr(get_option("ezfc_loading_icon", "fa fa-cog fa-spin")) . "'></i></span>";
			$html .= "</div>";
		}

		// required char
		$required_text = get_option("ezfc_required_text");
		if (!empty($options["required_text"])) {
			$required_text = $options["required_text"];
		}

		if ($options["show_required_char"] != 0 && !empty($required_text)) {
			$html .= "<div class='ezfc-required-notification'><span class='ezfc-required-char'>*</span> " . $required_text . "</div>";
		}

		// stripe token
		if ($stripe_enabled) {
			$html .= "<input type='hidden' name='stripeToken' id='ezfc-stripetoken-{$form->id}' value='' />";
		}
		// authorize token
		if ($authorize_enabled) {
			$html .= "<input type='hidden' name='authorizeToken' id='ezfc-authorizetoken-{$form->id}' value='' />";
		}

		$html .= "</form>";

		// fixed wrapper position
		$fixed_wrapper_add     = false;
		$fixed_wrapper_content = "";
		$fixed_price_position  = "right";

		// add fixed price
		if ($options["show_price_position"] == 4 ||	$options["show_price_position"] == 5) {
			$fixed_price_position = $options["show_price_position"]==4 ? "left" : "right";

			$fixed_wrapper_content .= "<div class='ezfc-fixed-price ezfc-fixed-price-{$fixed_price_position} ezfc-price-wrapper-element' data-id='{$form->id}'>";
			$fixed_wrapper_content .= "	<label {$css_label_width}>" . $options["price_label"] . "</label>";
			$fixed_wrapper_content .= "	<div class='ezfc-price-wrapper'>";
			$fixed_wrapper_content .= "		<span class='ezfc-price-prefix'>{$options["price_label_prefix"]}</span>";
			$fixed_wrapper_content .= "		<span class='ezfc-price'>{$price_html}</span>";
			$fixed_wrapper_content .= "		<span class='ezfc-price-suffix'>{$options["price_label_suffix"]}</span>";
			$fixed_wrapper_content .= "	</div>";
			$fixed_wrapper_content .= "</div>";

			$fixed_wrapper_add = true;
		}

		// live summary table
		if (Ezfc_Functions::get_array_value($options, "live_summary_enabled", 0) == 1) {
			$text_above = do_shortcode(Ezfc_Functions::get_array_value($options, "live_summary_text_above", ""));
			$text_below = do_shortcode(Ezfc_Functions::get_array_value($options, "live_summary_text_below", ""));

			$fixed_wrapper_content .= "<div class='ezfc-live-summary' id='ezfc-live-summary-{$form->id}'>";
			$fixed_wrapper_content .= 	"<div class='ezfc-live-summary-text-above'>{$text_above}</div>";
			$fixed_wrapper_content .= 	"<div class='ezfc-live-summary-content'></div>";
			$fixed_wrapper_content .= 	"<div class='ezfc-live-summary-text-below'>{$text_below}</div>";
			$fixed_wrapper_content .= "</div>";

			$fixed_wrapper_add = true;
		}

		if ($fixed_wrapper_add) {
			$html .= "<div class='ezfc-fixed ezfc-fixed-{$fixed_price_position}' data-id='{$form->id}'>";
			$html .= 	$fixed_wrapper_content;
			$html .= "</div>";
		}

		// error messages
		$html .= "<div class='ezfc-message' id='ezfc-message-{$form->id}'></div>";

		// success message
		if (get_option("ezfc_woocommerce") == 1 && $options["woo_disable_form"] == 0) {
			$success_text = get_option("ezfc_woocommerce_text");
		}
		else {
			$success_text = $options["success_text"];
		}
		$success_text = $this->frontend->replace_values_text($success_text);

		$html .= "<div class='ezfc-success-text' data-id='{$form->id}'></div>";

		// stripe payment
		if ($stripe_enabled) {
			wp_enqueue_script("ezfc-stripejs", "https://js.stripe.com/v2/");

			// modal
			$html .= "<div id='ezfc-stripe-form-modal-{$form->id}' class='ezfc-payment-dialog-modal' data-form_id='{$form->id}'></div>";
			// payment form
			$html .= $this->frontend->get_template("payment/stripe-popup");
		}
		// authorize payment
		if ($authorize_enabled) {
			wp_enqueue_script("ezfc-authorize-acceptjs");

			// modal
			$html .= "<div id='ezfc-authorize-form-modal-{$form->id}' class='ezfc-payment-dialog-modal' data-form_id='{$form->id}'></div>";
			// payment form
			$html .= $this->frontend->get_template("payment/authorize-popup");
		}

		// wrapper
		$html .= "</div>";

		// overview
		$html .= "<div class='ezfc-overview' data-id='{$form->id}'></div>";

		$html .= apply_filters("ezfc_form_output_end", "", $form->id);

		$html = apply_filters("ezfc_form_output", $html, $form, $options);

		// add view
		if (class_exists("Ezfc_stats") && !$preview) {
			$ezfc_stats_instance = Ezfc_stats::instance();
			$ezfc_stats_instance::view($form->id);
		}

		// add css added by elements
		wp_add_inline_style("ezfc-css-frontend", $this->get_elements_css());

		return $html;
	}

	/**
		get form element output
	**/
	public function get_form_element_output($element, $form, $options = array()) {
		if (count($options) < 1) $options = $this->options;

		$element_css = "ezfc-element ezfc-custom-element";
		$data         = json_decode($element->data);
		$el_id        = "ezfc_element-{$element->id}"; // wrapper id
		$el_name      = $options["hard_submit"] == 1 ? "ezfc_element[{$data->name}]" : "ezfc_element[{$element->id}]"; // input name
		$el_child_id  = $el_id . "-child"; // used for labels
		$element_type = $this->frontend->get_element_type($element);

		 // element js vars
		$element_js_vars = array(
			"add_to_price"               => Ezfc_Functions::get_object_value($data, "add_to_price", 1),
			"calculate_enabled"          => Ezfc_Functions::get_object_value($data, "calculate_enabled", 1),
			"calculate_when_hidden"      => Ezfc_Functions::get_object_value($data, "calculate_when_hidden", 1),
			"current_value"              => "",
			"factor"                     => Ezfc_Functions::get_object_value($data, "factor", 1),
			"group_id"                   => Ezfc_Functions::get_object_value($data, "group_id", 0),
			"form_id"                    => $form->id,
			"id"                         => $element->id,
			"is_currency"                => Ezfc_Functions::get_object_value($data, "is_currency", 1),
			"is_number"                  => Ezfc_Functions::get_object_value($data, "is_number", 1),
			"keep_value_after_reset"     => Ezfc_Functions::get_object_value($data, "keep_value_after_reset", 0),
			"label"                      => Ezfc_Functions::get_object_value($data, "label", ""),
			"name"                       => Ezfc_Functions::get_object_value($data, "name", ""),
			"overwrite_price"            => Ezfc_Functions::get_object_value($data, "overwrite_price", 0),
			"precision"                  => Ezfc_Functions::get_object_value($data, "precision", 2),
			"price_format"               => Ezfc_Functions::get_object_value($data, "price_format", ""),
			"show_in_live_summary"       => Ezfc_Functions::get_object_value($data, "show_in_live_summary", 1),
			"text_after"                 => Ezfc_Functions::get_object_value($data, "text_after", ""),
			"text_before"                => Ezfc_Functions::get_object_value($data, "text_before", ""),
			"type"                       => $element_type,
			"workdays_only"              => Ezfc_Functions::get_object_value($data, "workdays_only", "")
		);

		if (property_exists($data, "options")) {
			$element_js_vars["options"] = $this->frontend->get_options_source($data, $element->id, $options);
		}

		// check for extension
		if (!empty($data->extension)) {
			$extension_settings = apply_filters("ezfc_get_extension_settings_{$data->extension}", null);

			if (is_array($extension_settings) && !empty($extension_settings["type"])) {
				$element_css .= " ezfc-extension ezfc-extension-{$extension_settings["type"]}";
			}
		}

		$el_text       = "";
		$required      = "";
		$required_char = "";
		$step          = false;
		if (property_exists($data, "required") && $data->required == 1) {
			$required = "required";

			if ($options["show_required_char"] != 0) {
				$required_char = " <span class='ezfc-required-char'>*</span>";
			}
		}

		// element label
		$el_data_label = "";

		// trim labels
		if (property_exists($data, "label")) {
			$tmp_label = trim(htmlspecialchars_decode($data->label));

			if (get_option("ezfc_allow_label_shortcodes", 0)) {
				$tmp_label = do_shortcode($tmp_label);
			}

			// placeholders
			$tmp_label = $this->frontend->get_listen_placeholders($data, $tmp_label);

			$el_data_label .= $tmp_label;
		}

		// element description
		if (!empty($data->description)) {
			$element_description = "<span class='ezfc-element-description ezfc-element-description-{$options["description_label_position"]}' data-ezfctip='" . esc_attr($data->description) . "'></span>";

			$element_description = apply_filters("ezfc_element_description", $element_description, $data->description);

			if ($options["description_label_position"] == "before") {
				$el_data_label = $element_description . $el_data_label;
			}
			else {
				$el_data_label = $el_data_label . $element_description;
			}
		}

		// add whitespace for empty labels
		if ($el_data_label == "" && $options["add_space_to_empty_label"] == 1) {
			$el_data_label .= " &nbsp;";
		}

		// label
		$el_label      = "";
		$default_label = "<label class='ezfc-label' for='{$el_child_id}'>" . $el_data_label . "{$required_char}</label>";

		// calculate values
		$calc_enabled = 0;
		if (property_exists($data, "calculate_enabled")) {
			// add self to trigger IDs if calculation element
			$this->trigger_ids[$element->id][] = $element->id;

			$calc_enabled = $data->calculate_enabled ? 1 : 0;
		}

		$calc_before = 0;
		if (property_exists($data, "calculate_before")) {
			$calc_before  = $data->calculate_before ? 1 : 0;
		}

		// add data-id
		$data_add = "data-id='{$element->id}'";
		// add name
		if (!empty($data->name)) {
			$data_add .= " data-elementname='" . esc_attr(strtolower($data->name)) . "'";
		}

		// calculation enabled
		$data_add .= " data-calculate_enabled='{$calc_enabled}' ";

		// calculation rows
		$data_calculate_output_new = array();
		if (property_exists($data, "calculate") && Ezfc_Functions::is_countable($data->calculate) && count($data->calculate) > 0) {
			$element_js_vars["calculate"] = $data->calculate;

			// add target elements to trigger ids
			foreach ($data->calculate as $calc_row) {
				if (!empty($calc_row->target) && $calc_row->target != "__open__" && $calc_row->target != "__close__") {
					$this->trigger_ids[$element->id][] = $calc_row->target;

					if (isset($this->trigger_ids[$calc_row->target])) {
						$this->trigger_ids[$calc_row->target][] = $element->id;
					}
				}

				// replace calculation values
				$calc_row->value = $this->frontend->replace_calculation_values(trim($calc_row->value));
			}
		}
		// calculation routines
		if (property_exists($data, "calculate_routine") && Ezfc_Functions::is_countable($data->calculate_routine) && count($data->calculate_routine) > 0) {
			$element_js_vars["calculate_routine"] = $data->calculate_routine;
		}

		// overwrite price flag
		if (!empty($data->overwrite_price)) {
			$data_add .= " data-overwrite_price='{$data->overwrite_price}'";
		}
		// return value flag
		if (!empty($data->return_value)) {
			$data_add .= " data-return_value='{$data->return_value}'";
		}

		// group
		if (property_exists($data, "group_id") && $data->group_id != 0 && $data->group_id != $element->id) {
			$data_add .= " data-group='{$data->group_id}'";
		}

		// trigger IDs
		if (property_exists($data, "set")) {
			foreach ($data->set as $set_element) {
				if (!empty($set_element->target)) {
					$this->trigger_ids[$set_element->target][] = $element->id;
				}
			}
		}

		// matrix
		if (property_exists($data, "matrix")) {
			foreach ($data->matrix->conditions as $matrix_condition) {
				if (!property_exists($matrix_condition, "elements") || !is_array($matrix_condition->elements)) continue;

				foreach ($matrix_condition->elements as $matrix_condition_element) {
					$this->trigger_ids[$element->id][] = $matrix_condition_element;
				}
			}
		}

		// star rating
		if (property_exists($data, "stars")) {
			$this->trigger_ids[$element->id][] = $element->id;
		}

		// is currency
		if (property_exists($data, "is_currency")) {
			$data_add .= " data-is_currency='{$data->is_currency}'";
		}
		// is number
		if (property_exists($data, "is_number")) {
			$data_add .= " data-is_number='{$data->is_number}'";
		}
		// add to price
		$add_to_price = 1;
		if (property_exists($data, "add_to_price")) {
			$add_to_price = $data->add_to_price;
		}
		$data_add .= " data-add_to_price='{$add_to_price}'";
		
		// min selectable options
		if (!empty($data->min_selectable)) {
			$data_add .= " data-min_selectable='{$data->min_selectable}'";
		}
		// max selectable options
		if (!empty($data->max_selectable)) {
			$data_add .= " data-max_selectable='{$data->max_selectable}'";
		}

		// element price
		$show_price = "";

		// hidden?
		if (property_exists($data, "hidden")) {
			if ($data->hidden == 1) $element_css .= " ezfc-hidden";
			// conditional hidden
			elseif ($data->hidden == 2) $element_css .= " ezfc-hidden ezfc-custom-hidden";
		}

		// factor
		$data_factor = "";
		if (property_exists($data, "factor")) {
			if ($data->factor == "") $data->factor = 1;
			$factor = $this->frontend->get_factor($data->factor);
			$data_factor = "data-factor='{$factor}'";
		}

		// preselect value
		if (property_exists($data, "preselect")) {
			$data->preselect = apply_filters("ezfc_element_preselect_value", $data->preselect, $element, $data, $options, $form->id);
		}

		// modify value
		if (property_exists($data, "value")) {
			// WC attribute
			if (!empty($data->value_attribute) && !empty($product) && method_exists($product, "get_attribute")) {
				$data->value = $product->get_attribute($data->value_attribute);
			}

			// acf
			if (strpos($data->value, "acf:") !== false && function_exists("get_field")) {
				$tmp_array = explode(":", $data->value);
				$data->value = get_field($tmp_array[1]);
			}

			// postmeta
			else if (strpos($data->value, "postmeta:") !== false) {
				$tmp_array = explode(":", $data->value);
				$data->value = get_post_meta(get_the_ID(), $tmp_array[1], true);
			}

			// woocommerce product attribute via data->value
			else if (strpos($data->value, "wc:") !== false && !empty($product) && method_exists($product, "get_attribute")) {
				$tmp_array = explode(":", $data->value);
				$data->value = $product->get_attribute($tmp_array[1]);
			}

			// php function
			else if (strpos($data->value, "php:") !== false) {
				$tmp_array = explode(":", $data->value);
				if (!empty($tmp_array[1]) && function_exists($tmp_array[1])) {
					$data->value = htmlspecialchars($tmp_array[1]($element, $data, $options, $form->id), ENT_QUOTES, "UTF-8");
				}
			}

			// replace placeholder values
			$replace_values = $this->frontend->get_frontend_replace_values();
			foreach ($replace_values as $replace => $replace_value) {
				$data->value = str_ireplace("{{" . $replace . "}}", $replace_value, $data->value);
			}

			// random number
			if ($data->value == "__rand__" && property_exists($data, "min") && is_numeric($data->min) && property_exists($data, "max") && is_numeric($data->max)) {
				$data->value = function_exists("mt_rand") ? mt_rand($data->min, $data->max) : rand($data->min, $data->max);
			}

			// shortcode value
			if (get_option("ezfc_allow_value_shortcodes", 1)) {
				$data->value = do_shortcode($data->value);
			}
		}

		// external value
		$data_value_external = "";
		if (property_exists($data, "value_external")) $data_value_external = "data-value_external='{$data->value_external}'";
		// external value listen
		if (property_exists($data, "value_external_listen")) $data_value_external .= " data-value_external_listen='{$data->value_external_listen}'";

		// make radio buttons / checkboxes inline
		if (!empty($data->inline)) {
			$element_css .= " ezfc-inline-options";
		}

		// edit order (woocommerce only)
		$use_cart_values = false;
		if (!empty($_GET["ezfc_cart_product_key"])) {
			$use_cart_values = true;
			if (isset($cart_item["ezfc_edit_values"][$element->id])) {
				$data->value = $cart_item["ezfc_edit_values"][$element->id];
			}
		}
		// use custom GET-parameter value
		else if (property_exists($data, "GET")) {
			$get_tmp   = $data->GET;
			$get_value = null;

			// default
			if (property_exists($data, "value")) {
				$get_value = $data->value;
			}

			if (strpos($data->GET, "[") !== false) {
				$get_tmp = str_replace("]", "", $get_tmp);
				$get_tmp = explode("[", $get_tmp);

				if (isset($_GET[$get_tmp[0]][$get_tmp[1]])) {
					$get_value = $_GET[$get_tmp[0]][$get_tmp[1]];
				}
			}
			else if (isset($_GET[$get_tmp])) {
				$get_value = $_GET[$get_tmp];
			}
			// value over http(s) or any other protocol (via file_get_contents only)
			else if (property_exists($data, "value_http") && !empty($data->value_http) && filter_var($data->value_http, FILTER_VALIDATE_URL)) {
				$get_value = $this->frontend->get_external_file($data->value_http);

				// decode json
				if (!empty($data->value_http_json)) {
					$get_value_json = json_decode($get_value);

					if (!$get_value_json) {
						$get_value = __("Invalid JSON object.", "ezfc");
					}
					else {
						$json_separator = apply_filters("ezfc_json_key_separator", ".", $this->id);
						$json_keys = explode($json_separator, $data->value_http_json);
						$get_value = $this->frontend->get_json_value($get_value_json, $json_keys);
					}
				}
			}

			// xss protection
			if (!is_null($get_value)) {
				$get_value = htmlspecialchars($get_value, ENT_QUOTES, "UTF-8");

				$data->value     = $get_value;
				$data->preselect = $get_value;
			}
		}

		// normalize value
		if (property_exists($data, "value") && !empty($data->is_number) && $this->frontend->dec_point == ",") {
			$data->value = str_replace(".", ",", $data->value);
		}
		
		$data_settings = json_encode(array(
			"calculate_when_hidden" => property_exists($data, "calculate_when_hidden") ? $data->calculate_when_hidden : 1
		));

		// get element html output
		$element_class = $this->form_elements_objects[$element->id];
		
		// set modified element data
		$element_class->set_element_data($data);

		// prepare element output
		$element_class->prepare_output($options, array(
			"current_step"        => $this->current_step,
			"data_settings"       => $data_settings,
			"data_value_external" => $data_value_external,
			"form_elements"       => $this->form_elements,
			"step_count"          => $this->step_count,
			"use_cart_values"     => $use_cart_values
		));

		// add external element styles
		$element_class->enqueue_styles();

		// get content from extension
		if (!empty($data->extension) && !method_exists($element_class, "get_output")) {
			// add default label
			$el_label = $default_label;
			$el_text  = apply_filters("ezfc_ext_get_frontend_{$data->extension}", $el_text, $el_name, $element, $data, $options);
		}
		// inbuilt element
		else {
			$el_icon  = $element_class->get_icon();
			$el_label = $element_class->get_label();
			$el_text  = $element_class->_get_output();

			if (!empty($el_icon)) {
				$el_text = $el_icon . $el_text;
			}
		}

		// get element vars
		$element_css     = $element_class->get_element_css($element_css);
		$element_js_vars = $element_class->get_element_js_vars($element_js_vars);

		// increase step
		if ($element_type == "stepend") $this->current_step++;

		// add description below label
		if (!empty($data->description_below_label)) {
			$label_below_text = "<p class='ezfc-element-description-below-label'>{$data->description_below_label}</p>";
			$label_below_text = $this->frontend->get_listen_placeholders($data, $label_below_text);

			$el_label .= $label_below_text;
		}

		// add label
		if (!empty($el_label)) {
			//$el_text = $el_label . $el_text;
		}

		if (!empty($data->description_below_input)) {
			$label_below_input_text = "<p class='ezfc-element-description-below-input'>{$data->description_below_input}</p>";
			$label_below_input_text = $this->frontend->get_listen_placeholders($data, $label_below_input_text);

			$el_text .= $label_below_input_text;
		}

		// column class
		if (!empty($data->columns)) $element_css .= " ezfc-column ezfc-col-{$data->columns}";
		// wrapper class
		if (!empty($data->wrapper_class)) $element_css .= " {$data->wrapper_class}";
		// wrapper style
		if (!empty($data->wrapper_style)) $data_add .= " style=\"" . esc_js($data->wrapper_style) . "\"";

		// remove all line breaks (since WP may add these here)
		$data_add = $this->frontend->remove_nl($data_add);

		// html output
		$html_element_output = "";

		if (!$element_class->step) {
			// get element output from style
			$el_text = $this->get_element_style_output($el_text, $el_label, $element_class);

			$element_css .= " ezfc-element-wrapper-{$element_type}";
			
			$html_element_output .= "<div class='{$element_css}' id='{$el_id}' data-element='{$element_type}' {$data_add} {$data_value_external}>{$el_text}";

			if ($element_type != "group") {
				$html_element_output .= "</div>";
			}
		}
		else {
			$el_text = apply_filters("ezfc_element_output_{$element_type}", $el_text, $el_label, $element);

			$html_element_output .= $el_text;
		}

		// get element wrapper output
		$html_element_output = $element_class->_get_wrapper_output($html_element_output, array(
			"element_class" => $element_css,
			"element_id"    => $el_id,
			"element_text"  => $el_text,
			"element_type"  => $element_type,
			"data_add"      => $data_add,
			"data_external" => $data_value_external,
		));

		// add JS vars
		$element_js_vars = apply_filters("ezfc_element_js_vars", $element_js_vars, $element, $data);

		// extension
		if (!empty($data->extension)) {
			$element_js_vars = apply_filters("ezfc_ext_js_vars_{$data->extension}", $element_js_vars, $element, $data);
		}

		return array(
			"html"            => $html_element_output,
			"element_js_vars" => $element_js_vars
		);
	}

	public function get_element_style_output($element_content, $label, $element_class) {
		global $element;
		global $form;
		global $options;

		$element = $element_class;
		$form = $this;
		$options = $this->options;
		
		$element_style = sanitize_file_name(Ezfc_Functions::get_object_value($element->data, "element_style", "default"));
		$content = $this->frontend->get_template("elements-styles/{$element_style}", "", "elements-styles/default");

		// add label
		$label_content = "";
		if (!empty($label)) {
			$label_content = $label;
		}

		$content = str_replace("{{label}}", $label_content, $content);
		$content = str_replace("{{element_content}}", $element_content, $content);

		return $content;
	}

	/**
		build and return summary table
	**/
	public function get_summary() {
		// prepare replace values first
		$this->frontend->prepare_replace_values();
		$summary = "";

		// build summary content
		if (!empty($this->options["summary_content"])) {
			$summary_content = $this->frontend->replace_values_text($this->options["summary_content"]);
			$summary_content = apply_filters("the_content", $summary_content);

			// add to summary
			$summary .= $summary_content;
		}
		// add summary table
		if ($this->options["summary_values"] != "none") {
			$summary .= self::get_summary_form_elements();
		}

		return $summary;
	}

	public function get_summary_form_elements($options = array()) {
		$options = array_merge(array(
			"content_only"  => false,
			"form_elements" => $this->frontend->submission_data["form_elements_objects"],
			"loop_value"    => 0,
			"values"        => array(
				"raw_values"        => $this->frontend->submission_data["raw_values"],
				"calculated_values" => $this->frontend->submission_data["calculated_values"]
			),
			"options"       => $this->options,
			"price"         => $this->frontend->submission_data["generated_price"],
			"theme"         => $this->frontend->submission_data["options"]["summary_template"]
		), $options);

		$this->summary = new Ezfc_summary();
		$this->summary->set_form($this);
		$this->summary->set_form_elements_order($this->form_elements_order);
		$this->summary->set_form_elements($options["form_elements"]);
		$this->summary->set_form_values($options["values"]["raw_values"], $options["values"]["calculated_values"]);
		$this->summary->set_form_options($options["options"]);
		$this->summary->set_loop_value($options["loop_value"]);
		$this->summary->set_total($options["price"]);
		$this->summary->set_theme($options["theme"]);

		$summary_result = $this->summary->get_summary(array(
			"content_only" => $options["content_only"]
		));

		$summary_return = $summary_result;
		
		if (is_array($summary_return)) $summary_return = implode("", $summary_return);

		return $summary_return;
	}

	/**
		build form element output
	**/
	public function build_element_output($output) {
		$tree = $this->build_element_output_tree($output);
		$html_output = $this->build_element_output_from_tree($tree);

		return $html_output;
	}

	public function build_element_output_from_tree($tree) {
		$output = array();

		foreach ($tree as $i => $tree_object) {
			$output[] = $tree_object["output"];

			if (!empty($tree_object["children"])) {
				$output[] = $this->build_element_output_from_tree($tree_object["children"]);
			}

			$element_type = $this->frontend->get_element_type($tree_object["element"]);

			if ($element_type == "group") {
				$output[] = "</div></div>";
			}
		}

		return implode("", $output);
	}

	public function build_element_output_tree($source) {
		$nested = array();

		foreach ( $source as &$s ) {
			$element_data = json_decode($s["element"]->data);

			// no parent_id so we put it in the root of the array (or group id equals element id due to a previous bug)
			if ($element_data->group_id == 0 || $s["element"]->id == $element_data->group_id) {
				$nested[] = &$s;
			}
			else {
				$pid = $element_data->group_id;
				if ( isset($source[$pid]) ) {
					// If the parent ID exists in the source array
					// we add it to the 'children' array of the parent after initializing it.

					if (property_exists($source[$pid]["element"], "type") && $source[$pid]["element"]->type != "group") {
						$nested[] = &$s;
					}
					else {
						if ( !isset($source[$pid]['children']) ) {
							$source[$pid]['children'] = array();
						}

						$source[$pid]['children'][] = &$s;
					}
				}
				else {
					$nested[] = &$s;
				}
			}
		}

		return $nested;
	}

	public function __clone() {}
	public function __wakeup() {}
}