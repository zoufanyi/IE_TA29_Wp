<?php

if (!function_exists("get_option")) die("Access denied.");

if (get_option("ezfc_debug_mode", 0) != 0) {
	@error_reporting(E_ALL);
	@ini_set("display_errors", "On");
}
else {
	error_reporting(0);
}

require_once(EZFC_PATH . "class.ezfc_frontend.php");
require_once(EZFC_PATH . "class.ezfc_conditional.php");
$ezfc = Ezfc_frontend::instance();
$debug_mode = get_option("ezfc_debug_mode", 0);

// check for post data
if (empty($_POST) || empty($_POST["data"])) die();

parse_str($_POST["data"], $data);
$id = (int) $data["id"];

// check for form ID
if (empty($id) || empty($data)) die();

// empty form
if (!isset($data["ezfc_element"])) {
	$data["ezfc_element"] = array();
}

// post names -> ids
$use_form_element_names = apply_filters("ezfc_use_form_element_names", false, $id, $_POST["data"]); // todo
if ($use_form_element_names) {
	$form_elements = Ezfc_Functions::array_index_key($ezfc->form_elements_get($id), "id");

	// store in tmp array
	$data_elements_tmp = array();
	foreach ($data["ezfc_element"] as $name => $value) {
		// get element
		$element_find = $ezfc->get_form_element_by_name($name, $form_elements);

		if ($element_find) {
			$data_elements_tmp[$element_find->id] = $value;
		}
	}

	// use tmp array
	$data["ezfc_element"] = $data_elements_tmp;
}

// preview
$preview_id = empty($data["preview_form"]) ? null : $data["preview_form"];

// prepare submission data
$ezfc->set_current_form($id);
$ezfc->prepare_submission_data($id, $data);

// check input
$check_input_result = $ezfc->check_input($id, $data, $preview_id);

// form validation check
if (array_key_exists("error", $check_input_result)) {
	// check if validation is enabled when in debug mode
	if ($debug_mode == 0 || ($debug_mode != 0 && get_option("ezfc_debug_mode_required_elements", 1) == 1)) {
		ezfc_send_ajax($check_input_result);
		die();
	}
}
// form validation until the submitted step is valid
elseif (array_key_exists("step_valid", $check_input_result)) {
	ezfc_send_ajax(Ezfc_Functions::send_message("step_valid", 1));
	die();
}

// prepare form elements / options
$elements = $ezfc->elements_get();

if ($preview_id !== null) {
	$preview_form  = $ezfc->form_get_preview($preview_id);
	$form_elements = $preview_form->elements;
	$form_options  = $ezfc->form->get_form_option_values($preview_form->options);
}
else {
	$form_elements = $ezfc->form->get_form_elements();
	$form_options  = $ezfc->form->get_form_option_values();
}

// special checks
$has_recaptcha      = false;
$has_upload         = false;
$use_payment_choice = false;

// user can choose to pay via paypal or other types
$force_paypal    = false;
$force_stripe    = false;
$force_authorize = false;

if ($form_options["submission_enabled"] == 0) {
	return Ezfc_Functions::send_message("error", __("Submission not allowed.", "ezfc"));
}

// check special elements data
foreach ($form_elements as $k => $fe) {
	$fe_data = json_decode($fe->data);

	// skip for extensions
	if (!empty($fe_data->extension)) continue;

	$element_type = $elements[$fe->e_id]->type;

	// form has recaptcha element
	if ($element_type == "recaptcha") {
		$has_recaptcha = true;
	}

	// form uses payment element
	else if ($element_type == "payment") {
		$use_payment_choice = true;

		// if the user chose paypal payment, set it to submission data
		if (isset($data["ezfc_element"][$fe->id])) {
			$payment_choice = $ezfc->get_raw_target_value($fe->id, $data["ezfc_element"][$fe->id]);
			$ezfc->submission_data["payment_choice"] = $payment_choice;

			if ($payment_choice == "paypal") {
				$force_paypal = true;
				$ezfc->submission_data["force_paypal"] = true;
			}
			else if ($payment_choice == "stripe") {
				$force_stripe = true;
				$ezfc->submission_data["force_stripe"] = true;
			}
			else if ($payment_choice == "authorize") {
				$force_authorize = true;
				$ezfc->submission_data["authorize"] = true;
			}
		}
	}
	// repeatable form
	else if ($element_type == "repeatable_form") {
		$ezfc->add_form($fe_data->repeatable_form_id);
	}
}

// recaptcha in form?
if ($has_recaptcha) {
	$recaptcha_fail_message = __("Recaptcha failed, please try again.", "ezfc");

	if (empty($data["g-recaptcha-response"])) {
		ezfc_send_ajax(Ezfc_Functions::send_message("error", $recaptcha_fail_message));
		die();
	}

	$privatekey = get_option("ezfc_captcha_private");
	$recaptcha_user = $data["g-recaptcha-response"];

	// new
	$recaptcha_query = http_build_query(array(
		"secret"   => $privatekey,
		"response" => $recaptcha_user,
		"remoteip" => $_SERVER["REMOTE_ADDR"]
	));

	$recaptcha_result = null;
	$recaptcha_url = "https://www.google.com/recaptcha/api/siteverify?{$recaptcha_query}";
	// recaptcha result via file_get_contents
	if (ini_get('allow_url_fopen')) {
		$recaptcha_result = json_decode(file_get_contents($recaptcha_url));
	}
	else if (function_exists("curl_version")) {
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $recaptcha_url);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		$recaptcha_result_tmp = curl_exec($ch);

		if (curl_errno($ch)) {
			$ezfc->debug(__("Unable to get ReCaptchaResponse.", "ezfc"));
		}
		else {
			curl_close($ch);
			$recaptcha_result = json_decode($recaptcha_result_tmp);
		}
	}
	else {
		ezfc_send_ajax(Ezfc_Functions::send_message("error", __("Unable to use ReCaptcha since neither allow_url_fopen nor curl is installed.", "ezfc")));
		die();
	}

	if (!is_object($recaptcha_result) || !isset($recaptcha_result->success)) {
		ezfc_send_ajax(Ezfc_Functions::send_message("error", $recaptcha_fail_message));
		die();
	}
}

// calculate total
$total = $ezfc->get_total();

// price request
if (!empty($data["price_requested"]) && $form_options["price_show_request"] == 1) {
	ezfc_send_ajax(array(
		"price_requested" => $total
	));

	die();
}

// check for minimum price
if (isset($form_options["min_submit_value"]) && $form_options["min_submit_value"] != "") {
	if ((double) $total < (double) $form_options["min_submit_value"]) {
		$replaced_message = str_replace("%s", $form_options["min_submit_value"], $form_options["min_submit_value_text"]);
		ezfc_send_ajax(Ezfc_Functions::send_message("error", $replaced_message));
		die();
	}
}
// check for maximum price
if (isset($form_options["max_submit_value"]) && $form_options["max_submit_value"] != "") {
	if ($total > $form_options["max_submit_value"]) {
		$replaced_message = str_replace("%s", $form_options["max_submit_value"], $form_options["max_submit_value_text"]);
		ezfc_send_ajax(Ezfc_Functions::send_message("error", $replaced_message));
		die();
	}
}

// summary
if (!empty($data["summary"]) && $form_options["summary_enabled"] == 1) {
	$summary = $ezfc->form->get_summary();

	ezfc_send_ajax(array(
		"summary" => $summary
	));

	die();
}

// hard submit
if ($form_options["hard_submit"] == 1) {
	ezfc_send_ajax(array(
		"hard_submit" => 1
	));

	die();
}

// form email target
$ezfc->check_conditional_email_target();

// check if form uses paypal
if (($use_payment_choice && $force_paypal) || $form_options["pp_enabled"] == 1) {
	if (session_id() == '') {
	    session_start();
	}

	$_SESSION["Payment_Amount"] = round($total, 2);
	//$_SESSION["ezfc_subscription"] = $form_options["pp_subscription"];

	// get item name / description
	global $ezfc_item_options;
	$ezfc_item_options = array(
		"name" => $form_options["pp_item_name"],
		"desc" => $form_options["pp_item_desc"]
	);

	// generate payment reference ID
	$pp_ref_id = md5(microtime());

	require_once(EZFC_PATH . "lib/paypal/expresscheckout.php");
	$ret = Ezfc_paypal::checkout($ezfc, $pp_ref_id);

	// paypal error :(
	if (isset($ret["error"])) {
		ezfc_send_ajax(array($ret));
		die();
	}

	// insert submission details
	$ezfc->insert($id, $data["ezfc_element"], $data["ref_id"], false, array(
		"id" => EZFC_PAYMENT_ID_PAYPAL, "transaction_id" => "", "token" => $ret["token"]
	));

	ezfc_send_ajax(array(
		"paypal" => $ret["url"]
	));
}
// stripe
else if (($use_payment_choice && $force_stripe) || $form_options["payment_force_stripe"] == 1) {
	$stripe = array(
		"secret_key"      => trim(get_option("ezfc_stripe_secret_key")),
		"publishable_key" => trim(get_option("ezfc_stripe_publishable_key"))
	);

	if (empty($stripe["secret_key"]) || empty($stripe["publishable_key"])) {
		ezfc_send_ajax(Ezfc_Functions::send_message("error", __("Empty Stripe secret or public key. Please enter your Stripe keys in the global settings.", "ezfc")));
		die();
	}

	// load api
	if (!class_exists("Stripe\Stripe")) {
		require_once(EZFC_PATH . "lib/stripe-php-3.20.0/init.php");
	}

	// multiply total by 100 since no decimal points are allowed
	$total = round($ezfc->get_total(2) * 100);

	// check for token
	if (empty($data["stripeToken"])) {
		ezfc_send_ajax(Ezfc_Functions::send_message("error", __("The Stripe Token was not generated correctly", "ezfc")));
		die();
	}

	try {
		$user_mail = !empty($ezfc->submission_data["user_mail"]) ? $ezfc->submission_data["user_mail"] : "";

		// set secret key
		\Stripe\Stripe::setApiKey($stripe["secret_key"]);
		
		// set customer
		$customer = \Stripe\Customer::create(array(
			'email'       => $user_mail,
			'source'      => $data["stripeToken"]
		));

		// charge cc
		$charge = \Stripe\Charge::create(array(
			'customer' => $customer->id,
			'amount'   => $total,
			'currency' => get_option("ezfc_stripe_currency_code", "USD")
		));

		// insert submission details
		ezfc_send_ajax($ezfc->insert($id, $data["ezfc_element"], $data["ref_id"], true, array(
			"id" => EZFC_PAYMENT_ID_STRIPE,
			"token" => "",
			"transaction_id" => $charge->id
		)));

		die();
	}
	catch (Exception $e) {
		ezfc_send_ajax(Ezfc_Functions::send_message("error", $e->getMessage()));
		die();
	}
}
/* authorize (todo)
else if (($use_payment_choice && $force_authorize) || $form_options["payment_force_authorize"] == 1) {
	do_action("wp_ajax_ezfc_ext_authorize_frontend");
}*/
// preview success
else if ($preview_id !== null) {
	ezfc_send_ajax(Ezfc_Functions::send_message("success", 1));
}
else {
	// insert into db
	ezfc_send_ajax($ezfc->insert($id, $data["ezfc_element"], $data["ref_id"]));
}

die();

function ezfc_send_ajax($msg) {
	// check for errors in array
	if (is_array($msg)) {
		foreach ($msg as $m) {
			if (is_array($m) && $m["error"]) {
				echo json_encode($m);

				return;
			}
		}
	}

	echo json_encode($msg);
}