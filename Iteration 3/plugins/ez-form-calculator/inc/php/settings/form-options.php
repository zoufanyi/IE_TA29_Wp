<?php

defined( 'ABSPATH' ) OR exit;

function ezfc_get_form_options($global = false) {
	$settings = array(
		__("Email", "ezfc") => array(
			"email_recipient" => array(
				"id" => 1,
				"name" => "email_recipient",
				"default" => "",
				"description" => __("Email recipient", "ezfc"),
				"description_long" => __("Notifications will be sent to this email. Leave blank for no notifications. Multiple addresses need to be separated by commas.", "ezfc"),
				"type" => Ezfc_settings::$type_email,
				"value" => ""
			),
			"email_admin_sender" => array(
				"id" => 10,
				"name" => "email_admin_sender",
				"default" => "",
				"description" => __("Sender name", "ezfc"),
				"description_long" => __("Sender name in emails. Use this syntax: Sendername &lt;sender@mail.com&gt;", "ezfc"),
				"type" => "email_sender_name",
				"value" => ""
			),
			"email_admin_sender_recipient" => array(
				"id" => 91,
				"name" => "email_admin_sender_recipient",
				"default" => "",
				"description" => __("Sender name (admin)", "ezfc"),
				"description_long" => __("Sender name in emails sent to the admin (recipient). Use this syntax: Sendername &lt;sender@mail.com&gt;", "ezfc"),
				"type" => "email_sender_name",
				"value" => ""
			),
			"email_reply_to_address" => array(
				"id" => 215,
				"name" => "email_reply_to_address",
				"default" => "",
				"description" => __("Reply-to email address", "ezfc"),
				"description_long" => __("This address will be set as the reply-to email address which will be sent to the user. Leave blank for the default value.", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => ""
			),
			"email_subject" => array(
				"id" => 11,
				"name" => "email_subject",
				"default" => __("Your submission", "ezfc"),
				"description" => __("Email subject", "ezfc"),
				"description_long" => __("The email subject. You can also use element placeholders to create dynamic email subjects.", "ezfc"),
				"type" => "",
				"value" => ""
			),
			"email_text" => array(
				"id" => 12,
				"name" => "email_text",
				"default" => sprintf(__("<h2>Thank you for your order!</h2>
Your order was submitted successfully and our staff is working on it as soon as possible.

In the meantime, lean back, have a cup of coffee and enjoy your time. And remember:
<blockquote>'Coffee is a way of stealing time that should by rights belong to your older self.'
— Terry Pratchett</blockquote>

<h4>Your order details</h4>
%s", "ezfc"), "{{result_default}}"),
				"description" => __("Email text", "ezfc"),
				"description_long" => __("Email text sent to the user. Use {{result_default}} for a generated submission table. Use {{Elementname}} for single element values (where Elementname is the internal name of the element). Use {{files}} for attached files (you should not send these to the customer for security reasons).", "ezfc") . "<br><a href='https://ez-form-calculator.ezplugins.de/email-placeholder-list/' target='_blank'>" . __("View all placeholders", "ezfc") . "</a>",
				"type" => Ezfc_settings::$type_editor,
				"value" => ""
			),
			"email_admin_subject" => array(
				"id" => 13,
				"name" => "email_admin_subject",
				"default" => __("New submission", "ezfc"),
				"description" => __("Admin email subject", "ezfc"),
				"description_long" => "",
				"type" => "",
				"value" => ""
			),
			"email_admin_text" => array(
				"id" => 14,
				"name" => "email_admin_text",
				"default" => sprintf(__("You have received a new submission:\n\n%s", "ezfc"), "{{result_default}}"),
				"description" => __("Admin email text", "ezfc"),
				"description_long" => __("Email text sent to the admin. Use {{result_default}} for a generated submission table. Use {{Elementname}} for single element values (where Elementname is the internal name of the element). Use {{files}} for attached files (you should not send these to the customer for security reasons)", "ezfc") . "<br><a href='http://ez-form-calculator.ezplugins.de/email-placeholder-list/' target='_blank'>" . __("View all placeholders", "ezfc") . "</a>",
				"type" => Ezfc_settings::$type_editor,
				"value" => ""
			),
			"email_subject_pp" => array(
				"id" => 24,
				"name" => "email_subject_pp",
				"default" => __("Your submission", "ezfc"),
				"description" => __("Email Paypal subject", "ezfc"),
				"description_long" => "",
				"type" => "",
				"value" => "", "premium" => true
			),
			"email_text_pp" => array(
				"id" => 25,
				"name" => "email_text_pp",
				"default" => __("Thank you for your submission,\n\nwe have received your payment.", "ezfc"),
				"description" => __("Email Paypal text", "ezfc"),
				"description_long" => __("Email text sent to the user when paid with PayPal.", "ezfc") . "<br><a href='http://ez-form-calculator.ezplugins.de/email-placeholder-list/' target='_blank'>" . __("View all placeholders", "ezfc") . "</a>",
				"type" => Ezfc_settings::$type_editor,
				"value" => "", "premium" => true
			),
			"email_subject_stripe" => array(
				"id" => 133,
				"name" => "email_subject_stripe",
				"default" => __("Your submission", "ezfc"),
				"description" => __("Email Stripe subject", "ezfc"),
				"description_long" => "",
				"type" => "",
				"value" => "", "premium" => true
			),
			"email_text_stripe" => array(
				"id" => 134,
				"name" => "email_text_stripe",
				"default" => __("Thank you for your submission,\n\nwe have received your payment.", "ezfc"),
				"description" => __("Email Stripe text", "ezfc"),
				"description_long" => __("Email text sent to the user when paid with Stripe.", "ezfc") . "<br><a href='http://ez-form-calculator.ezplugins.de/email-placeholder-list/' target='_blank'>" . __("View all placeholders", "ezfc") . "</a>",
				"type" => Ezfc_settings::$type_editor,
				"value" => "", "premium" => true
			),
			"email_theme" => array(
				"id" => 208,
				"name" => "email_theme",
				"default" => "default",
				"description" => __("Email theme", "ezfc"),
				"description_long" => __("Choose your email design for attractive response emails.", "ezfc"),
				"type" => Ezfc_settings::$type_template_themes,
				"value" => ""
			),
			"email_send_files_attachment" => array(
				"id" => 41,
				"name" => "email_send_files_attachment",
				"default" => "0",
				"description" => __("Send files as attachment", "ezfc"),
				"description_long" => __("Uploaded files will be sent to the admin email recipient as attachments", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"email_show_html_elements" => array(
				"id" => 75,
				"name" => "email_show_html_elements",
				"default" => "0",
				"description" => __("Show HTML elements", "ezfc"),
				"description_long" => __("HTML elements can be shown in emails. You need to make sure that HTML elements have the option 'Show_in_email' enabled as well.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => ""
			),
			"email_nl2br" => array(
				"id" => 98,
				"name" => "email_nl2br",
				"default" => 1,
				"description" => __("Add linebreaks", "ezfc"),
				"description_long" => __("Automatically add linebreaks to emails. If emails contain a lot of blank space, you may want to disable this option.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => ""
			),
			"email_add_reply_to" => array(
				"id" => 189,
				"name" => "email_add_reply_to",
				"default" => 1,
				"description" => __("Add reply-to header", "ezfc"),
				"description_long" => __("Add the reply-to header to incoming admin emails.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => ""
			),
			"email_do_shortcode" => array(
				"id" => 190,
				"name" => "email_do_shortcode",
				"default" => 1,
				"description" => __("Do shortcode in email texts", "ezfc"),
				"description_long" => __("Shortcodes will be parsed in email texts. If you encounter any issues with form submissions or the text doesn't look good in emails, you should disable this option.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => ""
			),
			"email_subject_utf8" => array(
				"id" => 99,
				"name" => "email_subject_utf8",
				"default" => 1,
				"description" => __("Use UTF8 encoding", "ezfc"),
				"description_long" => __("When special characters aren't shown properly in emails, set this option to 'Yes'.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => ""
			),
			"mailchimp_add" => array(
				"id" => 30,
				"name" => "mailchimp_add",
				"default" => "0",
				"description" => __("Enable MailChimp", "ezfc"),
				"description_long" => __("Enable MailChimp integration", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"mailchimp_list" => array(
				"id" => 31,
				"name" => "mailchimp_list",
				"default" => "",
				"description" => __("Mailchimp list", "ezfc"),
				"description_long" => __("Email addresses will be added to this list upon form submission.", "ezfc"),
				"type" => "mailchimp_list",
				"value" => "", "premium" => true
			),
			"mailpoet_add" => array(
				"id" => 48,
				"name" => "mailpoet_add",
				"default" => "0",
				"description" => __("Enable Mailpoet", "ezfc"),
				"description_long" => __("Enable Mailpoet integration", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"mailpoet_list" => array(
				"id" => 49,
				"name" => "mailpoet_list",
				"default" => "",
				"description" => __("Mailpoet list", "ezfc"),
				"description_long" => __("Email addresses will be added to this list upon form submission.", "ezfc"),
				"type" => "mailpoet_list",
				"value" => "", "premium" => true
			),
			"email_attachments" => array(
				"id" => 155,
				"name" => "email_attachments",
				"default" => "",
				"description" => __("Email attachments", "ezfc"),
				"description_long" => __("These files will be sent as attachments. Multiple files are possible.", "ezfc"),
				"type" => "file_multiple",
				"value" => "", "premium" => true
			)
		),

		__("Form", "ezfc") => array(
			"submission_enabled" => array(
				"id" => 8,
				"name" => "submission_enabled",
				"default" => "1",
				"description" => __("Submission enabled", "ezfc"),
				"description_long" => __("If this option is disabled, the submit button will not be shown. This can be useful if you only want to show a calculation form without receiving any submissions.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => ""
			),
			"success_text" => array(
				"id" => 2,
				"name" => "success_text",
				"default" => __("Thank you for your submission!", "ezfc"),
				"description" => __("Submission message", "ezfc"),
				"description_long" => __("Frontend message after successful submission.", "ezfc"),
				"type" => Ezfc_settings::$type_editor,
				"value" => ""
			),
			"show_success_text" => array(
				"id" => 160,
				"name" => "show_success_text",
				"default" => 1,
				"description" => __("Show success text", "ezfc"),
				"description_long" => __("Show the success text when the form was submitted successfully. If disabled, the form will remain on the site.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => ""
			),
			"show_success_text_duration" => array(
				"id" => 200,
				"name" => "show_success_text_duration",
				"default" => 10,
				"description" => __("Success text duration", "ezfc"),
				"description_long" => __("Duration of how long the success text will be displayed (in seconds).", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => ""
			),
			"submission_js_func" => array(
				"id" => 138,
				"name" => "submission_js_func",
				"default" => "",
				"description" => __("JS function after submission", "ezfc"),
				"description_long" => __("This JavaScript function will be called after a successful submission, e.g: my_function. Provides an argument with relevant form / submission variables.", "ezfc"),
				"type" => "",
				"value" => ""
			),
			"spam_time" => array(
				"id" => 3,
				"name" => "spam_time",
				"default" => "5",
				"description" => __("Spam protection in seconds", "ezfc"),
				"description_long" => __("Every x seconds, a user (identified by IP address) can add an entry. Default: 5.", "ezfc"),
				"type" => "",
				"value" => "", "premium" => true
			),
			"min_submit_value" => array(
				"id" => 28,
				"name" => "min_submit_value",
				"default" => "0",
				"description" => __("Minimum submission value", "ezfc"),
				"description_long" => "",
				"type" => "",
				"value" => "", "premium" => true
			),
			"min_submit_value_text" => array(
				"id" => 29,
				"name" => "min_submit_value_text",
				"default" => __("Minimum submission value is %s", "ezfc"),
				"description" => __("Minimum submission value text", "ezfc"),
				"description_long" => __("This text will be displayed when the user's total value is less than the minimum value.", "ezfc"),
				"type" => "",
				"value" => "", "premium" => true
			),
			"max_submit_value" => array(
				"id" => 119,
				"name" => "max_submit_value",
				"default" => "",
				"description" => __("Maximum submission value", "ezfc"),
				"description_long" => "",
				"type" => "",
				"value" => "", "premium" => true
			),
			"max_submit_value_text" => array(
				"id" => 120,
				"name" => "max_submit_value_text",
				"default" => __("Maximum submission value is %s", "ezfc"),
				"description" => __("Maximum submission value text", "ezfc"),
				"description_long" => __("This text will be displayed when the user's total value is greater than the maximum value.", "ezfc"),
				"type" => "",
				"value" => "", "premium" => true
			),
			"hide_all_forms" => array(
				"id" => 32,
				"name" => "hide_all_forms",
				"default" => "0",
				"description" => __("Hide all forms on submission", "ezfc"),
				"description_long" => __("If this option is set to 'yes', all forms on the relevant page will be hidden upon submission (useful for product comparisons).", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"clear_selected_values_hidden" => array(
				"id" => 36,
				"name" => "clear_selected_values_hidden",
				"default" => "0",
				"description" => __("Clear selected values when hiding", "ezfc"),
				"description_long" => __("When elements are hidden, clear the selected values (numbers, dropdowns, radio buttons etc.). Please note that preselected values will be cleared as well!", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"reset_enabled" => array(
				"id" => 76,
				"name" => "reset_enabled",
				"default" => array(
					"enabled" => 0,
					"text" => __("Reset", "ezfc")
				),
				"description" => __("Enable reset button", "ezfc"),
				"description_long" => __("The reset button is used to reset the form elements to their initial values.", "ezfc"),
				"type" => Ezfc_settings::$type_bool_text,
				"value" => "", "premium" => true
			),
			"reset_after_submission" => array(
				"id" => 89,
				"name" => "reset_after_submission",
				"default" => 0,
				"description" => __("Reset form after submission", "ezfc"),
				"description_long" => __("When the form was submitted successfully, reset the form to its initial values. Please note that this will cancel the redirection URL if you have entered any.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"redirect_url" => array(
				"id" => 27,
				"name" => "redirect_url",
				"default" => "",
				"description" => __("Redirect URL", "ezfc"),
				"description_long" => __("Redirect users to this URL upon form submission. Note: URL must start with http:// or https://", "ezfc"),
				"type" => "",
				"value" => "", "premium" => true
			),
			"refresh_page_after_submission" => array(
				"id" => 94,
				"name" => "refresh_page_after_submission",
				"default" => "",
				"description" => __("Refresh page after submission", "ezfc"),
				"description_long" => __("The current page will be refreshed after the user has submitted the form. This will not work if you have entered a URL in the 'Redirect URL' option.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"redirect_timer" => array(
				"id" => 77,
				"name" => "redirect_timer",
				"default" => 3,
				"description" => __("Redirect timer", "ezfc"),
				"description_long" => __("Duration after which the user will be redirected when the conditional action 'redirect' is executed (in seconds).", "ezfc"),
				"type" => "",
				"value" => "", "premium" => true
			),
			"redirect_text" => array(
				"id" => 78,
				"name" => "redirect_text",
				"default" => __("You will be redirected in %s seconds...", "ezfc"),
				"description" => __("Redirect text", "ezfc"),
				"description_long" => __("This text will be shown when the user will be redirected (conditional action only). Use %s as the placeholder for the redirect timer.", "ezfc"),
				"type" => "",
				"value" => "", "premium" => true
			),
			"redirect_forward_values" => array(
				"id" => 95,
				"name" => "redirect_forward_values",
				"default" => 0,
				"description" => __("Redirect forward values", "ezfc"),
				"description_long" => __("When redirecting to another page, the submitted values can be forwarded by GET-parameters. The elements' IDs will be used as keys. Please note that you have to enter a redirection URL.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => 0, "premium" => true
			),
			"hard_submit" => array(
				"id" => 103,
				"name" => "hard_submit",
				"default" => 0,
				"description" => __("Hard submit", "ezfc"),
				"description_long" => __("Form submissions will not be processed by the plugin. Useful when you want to process the values through your own functon.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"invoice_method" => array(
				"id" => 121,
				"name" => "invoice_method",
				"default" => "form",
				"description" => __("Invoice ID method", "ezfc"),
				"description_long" => __("Form submission counter will use the 'counter' ID from the form submissions. Global submission counter will use the last submission ID of all forms.", "ezfc"),
				"type" => Ezfc_settings::$type_dropdown,
				"options" => array(
					"form"   => __("Use form submission counter", "ezfc"),
					"global" => __("Use global submission counter", "ezfc"),
					"option" => __("Use ID from global option", "ezfc") . " (ezfc_invoice_counter_id)"
				),
				"value" => "", "premium" => true
			),
			"invoice_prefix" => array(
				"id" => 122,
				"name" => "invoice_prefix",
				"default" => "",
				"description" => __("Invoice ID prefix", "ezfc"),
				"description_long" => __("This text will be added in front of the generated invoice ID.", "ezfc"),
				"type" => "",
				"value" => "", "premium" => true
			),
			"invoice_suffix" => array(
				"id" => 123,
				"name" => "invoice_suffix",
				"default" => "",
				"description" => __("Invoice ID suffix", "ezfc"),
				"description_long" => __("This text will be added behind the generated invoice ID.", "ezfc"),
				"type" => "",
				"value" => "", "premium" => true
			)
		),

		__("Layout", "ezfc") => array(
			"show_required_char" => array(
				"id" => 4,
				"name" => "show_required_char",
				"default" => "1",
				"description" => __("Show required char", "ezfc"),
				"description_long" => __("Show required char below the form", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => ""
			),
			"add_space_to_empty_label" => array(
				"id" => 188,
				"name" => "add_space_to_empty_label",
				"default" => 0,
				"description" => __("Add space to empty labels", "ezfc"),
				"description_long" => __("Add a space character to empty labels so they line up with other elements.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => ""
			),
			"required_text" => array(
				"id" => 158,
				"name" => "required_text",
				"default" => "",
				"description" => __("Required text", "ezfc"),
				"description_long" => __("This text is shown below the form. If this form option is not empty, this text will override the global option.", "ezfc"),
				"type" => "",
				"value" => ""
			),
			"required_text_element" => array(
				"id" => 159,
				"name" => "required_text_element",
				"default" => "",
				"description" => __("Required element text", "ezfc"),
				"description_long" => __("This text will be shown when a required element is empty. If this form option is not empty, this text will override the global option.", "ezfc"),
				"type" => "",
				"value" => ""
			),
			"submit_text" => array(
				"id" => 15,
				"name" => "submit_text",
				"default" => __("Submit", "ezfc"),
				"description" => __("Submit text", "ezfc"),
				"description_long" => __("Text in submit buttons", "ezfc"),
				"type" => "",
				"value" => ""
			),
			"submit_text_woo" => array(
				"id" => 16,
				"name" => "submit_text_woo",
				"default" => __("Add to cart", "ezfc"),
				"description" => __("Submit text WooCommerce", "ezfc"),
				"description_long" => __("Text used for WooCommerce submissions", "ezfc"),
				"type" => "",
				"value" => ""
			),
			"submit_button_alignment" => array(
				"id" => 216,
				"name" => "submit_button_alignment",
				"default" => "",
				"description" => __("Submit button alignment", "ezfc"),
				"description_long" => "",
				"type" => Ezfc_settings::$type_dropdown,
				"options" => array(
					"" => __("Default", "ezfc"),
					"ezfc-text-left" => __("Left", "ezfc"),
					"ezfc-text-center" => __("Center", "ezfc"),
					"ezfc-text-right" => __("Right", "ezfc"),
					"ezfc-full-width" => __("Full width", "ezfc"),
				),
				"value" => ""
			),
			"submit_button_class" => array(
				"id" => 17,
				"name" => "submit_button_class",
				"default" => "",
				"description" => __("Submit button CSS class", "ezfc"),
				"description_long" => "",
				"type" => "",
				"value" => ""
			),
			"theme" => array(
				"id" => 19,
				"name" => "theme",
				"default" => "aero-v2",
				"description" => __("Form theme", "ezfc"),
				"description_long" => sprintf("<a href='https://ez-form-calculator.ezplugins.de/themes/' target='_blank'>%s</a> | <a href='#' data-action='form_preview_all_themes'>%s</a>", __("View all themes", "ezfc"), __("Preview this form in all themes", "ezfc")),
				"type" => "themes",
				"value" => ""
			),
			"popup_enabled" => array(
				"id" => 193,
				"name" => "popup_enabled",
				"default" => 0,
				"description" => __("Open form in popup", "ezfc"),
				"description_long" => __("The form will be opened in a popup. The plugin adds a button to open the form automatically.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"popup_open_auto" => array(
				"id" => 196,
				"name" => "popup_open_auto",
				"default" => 0,
				"description" => __("Open popup automatically", "ezfc"),
				"description_long" => __("The form will be opened automatically as soon as the page finished loading.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"popup_button_show" => array(
				"id" => 194,
				"name" => "popup_button_show",
				"default" => 1,
				"description" => __("Show popup button", "ezfc"),
				"description_long" => __("Show the popup button. Disable this if you want to open the form manually.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"popup_button_text" => array(
				"id" => 195,
				"name" => "popup_button_text",
				"default" => __("Open form", "ezfc"),
				"description" => __("Popup button text", "ezfc"),
				"description_long" => __("This text will be shown on the button to open the popup form.", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
			"image_selection_style" => array(
				"id" => 179,
				"name" => "image_selection_style",
				"default" => "default",
				"description" => __("Image selection style", "ezfc"),
				"description_long" => __("Styling of option image selection", "ezfc"),
				"type" => Ezfc_settings::$type_dropdown,
				"options" => array(
					"default"        => __("Default (opacity)", "ezfc"),
					"icon-check"     => __("Check icon", "ezfc"),
					"triangle-check" => __("Check icon in triangle", "ezfc"),
					"pacman"         => __("Pacman", "ezfc")
				),
				"value" => "", "premium" => true
			),
			"datepicker_format" => array(
				"id" => 21,
				"name" => "datepicker_format",
				"default" => __("dd/mm/yy", "ezfc"),
				"description" => __("Datepicker format", "ezfc"),
				"description_long" => sprintf(__("See jqueryui.com for date formats. Default: %s", "ezfc"), "dd/mm/yy"),
				"type" => "",
				"value" => ""
			),
			"timepicker_format" => array(
				"id" => 33,
				"name" => "timepicker_format",
				"default" => "H:i",
				"description" => __("Timepicker format", "ezfc"),
				"description_long" => __("See php.net for time formats", "ezfc"),
				"type" => "",
				"value" => ""
			),
			"form_class" => array(
				"id" => 42,
				"name" => "form_class",
				"default" => "",
				"description" => __("Form class", "ezfc"),
				"description_long" => __("Additional css classes for the form", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
			"price_position_scroll_top" => array(
				"id" => 43,
				"name" => "price_position_scroll_top",
				"default" => 150,
				"description" => __("Price scroll top position", "ezfc"),
				"description_long" => __("Top position of the price with fixed position. Some designs may overlay a navigation (or something else) on the top of the page. Enter a number without any dimension here.", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
			"counter_duration" => array(
				"id" => 46,
				"name" => "counter_duration",
				"default" => "1000",
				"description" => __("Number counter duration", "ezfc"),
				"description_long" => __("Duration of the number counter to count after each change (in ms). Set to 0 to disable the counter.", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
			"counter_interval" => array(
				"id" => 47,
				"name" => "counter_interval",
				"default" => "30",
				"description" => __("Number counter interval", "ezfc"),
				"description_long" => __("Interval rate at which the counter updates the numbers (in ms).", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
			"disable_error_scroll" => array(
				"id" => 96,
				"name" => "disable_error_scroll",
				"default" => 0,
				"description" => __("Disable scroll to error element", "ezfc"),
				"description_long" => __("Depending on your theme, scrolling to the element which caused an error message may not work correctly. If this option is enabled, scrolling will be disabled.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"scroll_to_success_message" => array(
				"id" => 116,
				"name" => "scroll_to_success_message",
				"default" => 1,
				"description" => __("Scroll to success message", "ezfc"),
				"description_long" => __("Scroll to success message after the form was submitted.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"grid_12" => array(
				"id" => 97,
				"name" => "grid_12",
				"default" => 0,
				"description" => __("Use 12 columns grid", "ezfc"),
				"description_long" => __("Forms are divided into 6 columns by default. Enable this option to use a 12 column grid.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"form_center" => array(
				"id" => 105,
				"name" => "form_center",
				"default" => 0,
				"description" => __("Center form", "ezfc"),
				"description_long" => __("All elements will be centered.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => ""
			),
			"description_label_position" => array(
				"id" => 197,
				"name" => "description_label_position",
				"default" => "before",
				"description" => __("Tooltip description position", "ezfc"),
				"description_long" => __("Position of the tooltip description icon.", "ezfc"),
				"type" => Ezfc_settings::$type_dropdown,
				"options" => array(
					"before" => __("Before", "ezfc"),
					"after"  => __("After", "ezfc")
				),
				"value" => "", "premium" => true
			)
		),

		__("Price", "ezfc") => array(
			"currency" => array(
				"id" => 5,
				"name" => "currency",
				"default" => "$",
				"description" => __("Currency", "ezfc"),
				"description_long" => "",
				"type" => "",
				"value" => ""
			),
			"currency_position" => array(
				"id" => 20,
				"name" => "currency_position",
				"default" => "0",
				"description" => __("Currency position", "ezfc"),
				"description_long" => "",
				"type" => "select,Before|After",
				"value" => ""
			),
			"price_format" => array(
				"id" => 34,
				"name" => "price_format",
				"default" => "",
				"description" => __("Price format", "ezfc"),
				"description_long" => __("If left blank, the global price format will be used. See numeraljs.com for syntax documentation", "ezfc"),
				"type" => "",
				"value" => ""
			),
			"price_label" => array(
				"id" => 6,
				"name" => "price_label",
				"default" => __("Price", "ezfc"),
				"description" => __("Price label", "ezfc"),
				"description_long" => __("Calculated field label (default: Price)", "ezfc"),
				"type" => "",
				"value" => ""
			),
			"price_label_prefix" => array(
				"id" => 175,
				"name" => "price_label_prefix",
				"default" => "",
				"description" => __("Price prefix", "ezfc"),
				"description_long" => __("This text is displayed before the formatted total price.", "ezfc"),
				"type" => "",
				"value" => ""
			),
			"price_label_suffix" => array(
				"id" => 176,
				"name" => "price_label_suffix",
				"default" => "",
				"description" => __("Price suffix", "ezfc"),
				"description_long" => __("This text is displayed behind the formatted total price.", "ezfc"),
				"type" => "",
				"value" => ""
			),
			"show_element_price" => array(
				"id" => 7,
				"name" => "show_element_price",
				"default" => "0",
				"description" => __("Show element prices", "ezfc"),
				"description_long" => __("Display element factor or option value as currency behind an input field or option.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => ""
			),
			"format_currency_numbers_elements" => array(
				"id" => 180,
				"name" => "format_currency_numbers_elements",
				"default" => 1,
				"description" => __("Format currency elements", "ezfc"),
				"description_long" => __("If this option is enabled, all numbers elements with element option is_currency set to 'Yes' will be formatted as currency automatically. The input field will display the currency sign accordingly to the currency position.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => ""
			),
			"format_number_show_zero" => array(
				"id" => 201,
				"name" => "format_number_show_zero",
				"default" => 1,
				"description" => __("Show zero value", "ezfc"),
				"description_long" => __("If this option is enabled, all elements with element option is_number set to 'Yes' will show the number '0' for zero values.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => ""
			),
			"show_price_position" => array(
				"id" => 9,
				"name" => "show_price_position",
				"default" => "1",
				"description" => __("Total price position", "ezfc"),
				"description_long" => __("Price can be displayed above or below the form (or both) as well as fixed (scrolls with window)", "ezfc"),
				"type" => Ezfc_settings::$type_dropdown,
				"options" => array(
					0 => __("Hidden", "ezfc"),
					1 => __("Below", "ezfc"),
					2 => __("Above", "ezfc"), 
					3 => __("Below and above", "ezfc"),
					4 => __("Fixed left", "ezfc"),
					5 => __("Fixed right", "ezfc")
				),
				"value" => ""
			),
			"email_total_price_text" => array(
				"id" => 92,
				"name" => "email_total_price_text",
				"default" => "",
				"description" => __("Total price text", "ezfc"),
				"description_long" => __("This text will be shown before the total price in emails.", "ezfc"),
				"type" => "",
				"value" => __("Total", "ezfc")
			),
			"email_show_total_price" => array(
				"id" => 18,
				"name" => "email_show_total_price",
				"default" => "1",
				"description" => __("Show total price in email", "ezfc"),
				"description_long" => __("Whether the total price of a submission should be shown or not. (Disable this option when you don't have a calculation form)", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => ""
			),
			"price_show_request" => array(
				"id" => 38,
				"name" => "price_show_request",
				"default" => "0",
				"description" => __("Request price", "ezfc"),
				"description_long" => __("Enable this option if you do not want to show the price immediately.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"price_show_request_text" => array(
				"id" => 39,
				"name" => "price_show_request_text",
				"default" => __("Request price", "ezfc"),
				"description" => __("Request price text", "ezfc"),
				"description_long" => __("Text in request price button", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
			"price_show_request_before" => array(
				"id" => 40,
				"name" => "price_show_request_before",
				"default" => "-",
				"description" => __("Price text before request", "ezfc"),
				"description_long" => "",
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
			/*"deposit_price_element" => array(
				"id" => 214,
				"name" => "deposit_price_element",
				"default" => "",
				"description" => __("Deposit price element", "ezfc"),
				"description_long" => __("If an element is selected, the deposit price will be this elements' value.", "ezfc"),
				"type" => "form_element",
				"value" => ""
			)*/
		),

		__("PayPal", "ezfc") => array(
			"pp_enabled" => array(
				"id" => 22,
				"name" => "pp_enabled",
				"default" => "0",
				"description" => __("Force PayPal payment", "ezfc"),
				"description_long" => __("Enabling this option will force the user to use PayPal. If you want to let the user choose how to pay, disable this option and add the Payment element (do not change the paypal value).", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"pp_submittext" => array(
				"id" => 23,
				"name" => "pp_submittext",
				"default" => __("Check out with PayPal", "ezfc"),
				"description" => __("Submit text PayPal", "ezfc"),
				"description_long" => __("Text used for PayPal checkouts", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
			"pp_paid_text" => array(
				"id" => 26,
				"name" => "pp_paid_text",
				"default" => __("We have received your payment. Thank you for your order!", "ezfc"),
				"description" => __("PayPal payment success text", "ezfc"),
				"description_long" => __("This text will be displayed when the user has successfully paid and returns to the site.", "ezfc"),
				"type" => Ezfc_settings::$type_editor,
				"value" => "", "premium" => true
			),
			"pp_item_name" => array(
				"id" => 44,
				"name" => "pp_item_name",
				"default" => "",
				"description" => __("Item name", "ezfc"),
				"description_long" => __("This text will be displayed as item name on the PayPal checkout page.", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
			"pp_item_desc" => array(
				"id" => 45,
				"name" => "pp_item_desc",
				"default" => "",
				"description" => __("Item description", "ezfc"),
				"description_long" => __("This text will be displayed as description below the item name on the PayPal checkout page.", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
		),

		__("Stripe", "ezfc") => array(
			"stripe_enabled" => array(
				"id" => 126,
				"name" => "stripe_enabled",
				"default" => 0,
				"description" => __("Enable Stripe payment", "ezfc"),
				"description_long" => __("Enable stripe payment for this form.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"payment_force_stripe" => array(
				"id" => 127,
				"name" => "payment_force_stripe",
				"default" => 0,
				"description" => __("Force Stripe payment", "ezfc"),
				"description_long" => __("Enabling this option will force the user to use Stripe. If you want to let the user choose how to pay, disable this option, add the Payment element and add an option value named 'stripe' (without the quotes).", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"submit_text_stripe" => array(
				"id" => 132,
				"name" => "submit_text_stripe",
				"default" => __("Check out with Stripe", "ezfc"),
				"description" => __("Submit text Stripe", "ezfc"),
				"description_long" => __("This text will be shown on the form submit button which will lead the user to enter their credit card info.", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
			"stripe_label_card_number" => array(
				"id" => 128,
				"name" => "stripe_label_card_number",
				"default" => __("Card number", "ezfc"),
				"description" => __("Label for card number", "ezfc"),
				"description_long" => "",
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
			"stripe_label_expiration_date" => array(
				"id" => 129,
				"name" => "stripe_label_expiration_date",
				"default" => __("Expiration (MM/YY)", "ezfc"),
				"description" => __("Label for expiration date", "ezfc"),
				"description_long" => "",
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
			"stripe_label_cvc" => array(
				"id" => 130,
				"name" => "stripe_label_cvc",
				"default" => __("CVC", "ezfc"),
				"description" => __("Label for CVC", "ezfc"),
				"description_long" => "",
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
			"stripe_show_card_holder_name" => array(
				"id" => 140,
				"name" => "stripe_show_card_holder_name",
				"default" => 0,
				"description" => __("Show card holder name input", "ezfc"),
				"description_long" => __("An additional input field will be shown which acts as the card holder name.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"stripe_label_card_holder_name" => array(
				"id" => 141,
				"name" => "stripe_label_card_holder_name",
				"default" => __("Card holder name", "ezfc"),
				"description" => __("Label for card holder name", "ezfc"),
				"description_long" => "",
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
			"stripe_label_submit" => array(
				"id" => 131,
				"name" => "stripe_label_submit",
				"default" => __("Pay %s", "ezfc"),
				"description" => __("Label for submit button", "ezfc"),
				"description_long" => __("Text on payment button. Use %s as a placeholder for the total price.", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
			"stripe_label_cancel" => array(
				"id" => 135,
				"name" => "stripe_label_cancel",
				"default" => __("Cancel", "ezfc"),
				"description" => __("Label for cancel button", "ezfc"),
				"description_long" => "",
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
			"stripe_heading" => array(
				"id" => 136,
				"name" => "stripe_heading",
				"default" => __("Credit Card Payment", "ezfc"),
				"description" => __("Payment form heading", "ezfc"),
				"description_long" => __("This text will be shown at the top of the payment form.", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
			"stripe_description" => array(
				"id" => 137,
				"name" => "stripe_description",
				"default" => __("Please enter your credit card details.", "ezfc"),
				"description" => __("Payment form description", "ezfc"),
				"description_long" => __("This text will be shown below the payment form heading.", "ezfc"),
				"type" => Ezfc_settings::$type_editor,
				"value" => "", "premium" => true
			),
		),

		__("Summary", "ezfc") => array(
			"summary_enabled" => array(
				"id" => 79,
				"name" => "summary_enabled",
				"default" => 0,
				"description" => __("Show summary", "ezfc"),
				"description_long" => __("Show a summary of the selected values at the end of the form.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"summary_values" => array(
				"id" => 117,
				"name" => "summary_values",
				"default" => "result_values",
				"description" => __("Summary values", "ezfc"),
				"description_long" => __("If summary is enabled, the plugin will show a table of submitted values. In this option, you can select which summary table should be shown (default: values only).", "ezfc"),
				"type" => Ezfc_settings::$type_dropdown,
				"options" => array(
					"result_default"          => __("Result table v2.0 (result_default)", "ezfc"),
					"none"                    => __("Don't show summary table", "ezfc"),
					// deprecated
					"result"                  => __("Full details (result)", "ezfc") . " [" . __("Deprecated", "ezfc") . "]",
					"result_simple"           => __("Simple details (result_simple)", "ezfc") . " [" . __("Deprecated", "ezfc") . "]",
					"result_values"           => __("Values only (result_values)", "ezfc") . " [" . __("Deprecated", "ezfc") . "]",
					"result_values_submitted" => __("Submitted values only (result_values_submitted)", "ezfc") . " [" . __("Deprecated", "ezfc") . "]"
				),
				"value" => "", "premium" => true
			),
			"summary_text" => array(
				"id" => 80,
				"name" => "summary_text",
				"default" => __("Summary", "ezfc"),
				"description" => __("Summary title", "ezfc"),
				"description_long" => __("This text will be shown above the summary.", "ezfc"),
				"type" => "",
				"value" => "", "premium" => true
			),
			"summary_content" => array(
				"id" => 178,
				"name" => "summary_content",
				"default" => "",
				"description" => __("Summary content", "ezfc"),
				"description_long" => __("This text will be shown above the summary table and below the summary title.", "ezfc"),
				"type" => Ezfc_settings::$type_editor,
				"value" => "", "premium" => true
			),
			"summary_template" => array(
				"id" => 210,
				"name" => "summary_template",
				"default" => "default",
				"description" => __("Summary template", "ezfc"),
				"description_long" => __("Choose a summary template for an optimal submission layout.", "ezfc"),
				"type" => Ezfc_settings::$type_dropdown,
				"options" => array(
					"default" => __("Default (3 columns)", "ezfc"),
					"default-2-columns" => __("Default (2 columns)", "ezfc"),
				),
				"value" => "", "premium" => true
			),
			"summary_column_1" => array(
				"id" => 211,
				"name" => "summary_column_1",
				"default" => __("Description", "ezfc"),
				"description" => __("First column title", "ezfc"),
				"description_long" => __("This title will be shown in the first column of the summary table.", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
			"summary_column_2" => array(
				"id" => 212,
				"name" => "summary_column_2",
				"default" => __("Value", "ezfc"),
				"description" => __("Second column title", "ezfc"),
				"description_long" => __("This title will be shown in the second column of the summary table.", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
			"summary_column_3" => array(
				"id" => 213,
				"name" => "summary_column_3",
				"default" => __("Amount", "ezfc"),
				"description" => __("Third column title", "ezfc"),
				"description_long" => __("This title will be shown in the third column of the summary table.", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
			"hide_summary_price" => array(
				"id" => 186,
				"name" => "hide_summary_price",
				"default" => 0,
				"description" => __("Hide summary price", "ezfc"),
				"description_long" => __("Whether the price should be shown in the summary or not.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"summary_button_text" => array(
				"id" => 81,
				"name" => "summary_button_text",
				"default" => __("Check your order", "ezfc"),
				"description" => __("Summary button text", "ezfc"),
				"description_long" => __("Text on the summary submit button.", "ezfc"),
				"type" => "",
				"value" => __("Check your order", "ezfc"), "premium" => true
			),
			"live_summary_enabled" => array(
				"id" => 191,
				"name" => "live_summary_enabled",
				"default" => 0,
				"description" => __("Live summary", "ezfc"),
				"description_long" => __("Show selected values in a fixed sidebar.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"live_summary_text_above" => array(
				"id" => 198,
				"name" => "live_summary_text_above",
				"default" => "",
				"description" => __("Live summary text above", "ezfc"),
				"description_long" => __("Text that is shown above the live summary.", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
			"live_summary_text_below" => array(
				"id" => 199,
				"name" => "live_summary_text_below",
				"default" => "",
				"description" => __("Live summary text below", "ezfc"),
				"description_long" => __("Text that is shown below the live summary.", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			)
		),

		__("PDF", "ezfc") => array(
			"pdf_enable" => array(
				"id" => 112,
				"name" => "pdf_enable",
				"description" => __("Enable PDF integration", "ezfc"),
				"description_long" => __("When the form is submitted, a PDF file will be created with the content from 'PDF Text'.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"default" => 0,
				"value" => "", "premium" => true
			),
			"pdf_send_to_admin" => array(
				"id" => 113,
				"name" => "pdf_send_to_admin",
				"description" => __("Send PDF to admin", "ezfc"),
				"description_long" => __("The created PDF file will be sent to all recipients as email attachment.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"default" => 1,
				"value" => "", "premium" => true
			),
			"pdf_send_to_customer" => array(
				"id" => 114,
				"name" => "pdf_send_to_customer",
				"description" => __("Send PDF to customer", "ezfc"),
				"description_long" => __("The created PDF file will be sent to the customer as email attachment.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"default" => 1,
				"value" => "", "premium" => true
			),
			"pdf_text" => array(
				"id" => 115,
				"name" => "pdf_text",
				"default" => "{{result_values}}",
				"description" => __("PDF text", "ezfc"),
				"description_long" => __("PDF text sent to the user. Use {{result_default}} for a generated submission table. Use {{Elementname}} for single element values (where Elementname is the internal name of the element). Use {{files}} for attached files (you should not send these to the customer for security reasons).", "ezfc") . "<br><a href='https://ez-form-calculator.ezplugins.de/email-placeholder-list/' target='_blank'>" . __("View all placeholders", "ezfc") . "</a>",
				"type" => Ezfc_settings::$type_editor,
				"value" => "", "premium" => true
			),
			"pdf_theme" => array(
				"id" => 209,
				"name" => "pdf_theme",
				"default" => "default",
				"description" => __("PDF theme", "ezfc"),
				"description_long" => __("Choose your pdf design for attractive response pdf files.", "ezfc"),
				"type" => Ezfc_settings::$type_template_themes,
				"value" => "", "premium" => true
			),
			"pdf_filename" => array(
				"id" => 139,
				"name" => "pdf_filename",
				"default" => "form",
				"description" => __("PDF filename", "ezfc"),
				"description_long" => __("Filename of the attached PDF file in emails.", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			)
		),

		__("Steps", "ezfc") => array(
			"step_indicator" => array(
				"id" => 100,
				"name" => "step_indicator",
				"default" => 0,
				"description" => __("Show step indicator", "ezfc"),
				"description_long" => "",
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"step_indicator_text" => array(
				"id" => 101,
				"name" => "step_indicator_text",
				"default" => __("Step %d", "ezfc"),
				"description" => __("Step indicator text", "ezfc"),
				"description_long" => __("Text on step indicator. The placeholder %d will be replaced with the step numbers.", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
			"step_use_titles" => array(
				"id" => 102,
				"name" => "step_use_titles",
				"default" => 0,
				"description" => __("Use step titles", "ezfc"),
				"description_long" => __("Use titles in step-start elements instead of step indicator text.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"step_indicator_start" => array(
				"id" => 118,
				"name" => "step_indicator_start",
				"default" => 0,
				"description" => __("Step indicator start", "ezfc"),
				"description_long" => __("Step indicator will be shown with the step value of this option. Example: if the step indicator should be shown after the second step, the value of this option must be 3.", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => 0, "premium" => true
			),
			"step_indicator_layout" => array(
				"id" => 214,
				"name" => "step_indicator_layout",
				"default" => "full-width",
				"description" => __("Step indicator layout", "ezfc"),
				"description_long" => "",
				"type" => Ezfc_settings::$type_dropdown,
				"options" => array(
					"full-width" => __("Full width", "ezfc"),
					"auto-width" => __("Auto width", "ezfc")
				),
				"value" => 0, "premium" => true
			),
			"verify_steps" => array(
				"id" => 104,
				"name" => "verify_steps",
				"default" => 1,
				"description" => __("Verify steps", "ezfc"),
				"description_long" => __("Verify required element values after each step.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"step_allow_free_navigation" => array(
				"id" => 204,
				"name" => "step_allow_free_navigation",
				"default" => 0,
				"description" => __("Free navigation", "ezfc"),
				"description_long" => __("Allow the user to click on all steps when the step indicator is active.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"step_auto_progress" => array(
				"id" => 185,
				"name" => "step_auto_progress",
				"default" => 0,
				"description" => __("Auto progress", "ezfc"),
				"description_long" => __("Once the user selects a radio option, the form will progress to the next step automatically.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"step_reset_succeeding" => array(
				"id" => 192,
				"name" => "step_reset_succeeding",
				"default" => 0,
				"description" => __("Reset succeeding elements", "ezfc"),
				"description_long" => __("Reset elements in succeeding steps when going back a step.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"step_speed" => array(
				"id" => 187,
				"name" => "step_speed",
				"default" => 200,
				"description" => __("Step speed", "ezfc"),
				"description_long" => __("Fade in/out speed when progressing steps. Set to 0 for instant progress, default is 200.", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			)
		),

		__("Styling", "ezfc") => array(
			"load_custom_styling" => array(
				"id" => 50,
				"name" => "load_custom_styling",
				"description" => __("Load custom styling", "ezfc"),
				"description_long" => __("Only enable this option if you want to use the styling below.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"default" => 0,
				"value" => ""
			),
			// user-input CSS
			"css_custom_styling_user" => array(
				"id" => 177,
				"name" => "css_custom_styling_user",
				"description" => __("Custom CSS", "ezfc"),
				"description_long" => __("Add your own CSS styles here.", "ezfc"),
				"type" => Ezfc_settings::$type_textarea,
				"default" => "",
				"value" => ""
			),
			// will be generated automatically
			"css_custom_styling" => array(
				"id" => 51,
				"name" => "css_custom_styling",
				"description" => "",
				"description_long" => "",
				"type" => "hidden",
				"default" => "",
				"value" => ""
			),
			"css_font" => array(
				"id" => 58,
				"name" => "css_font",
				"description" => __("Font", "ezfc"),
				"type" => "font",
				"css" => array(
					"selector" => ".ezfc-form",
					"property" => "font-family"
				),
				"value" => ""
			),
			"css_font_size" => array(
				"id" => 59,
				"name" => "css_font_size",
				"description" => __("Font size", "ezfc"),
				"type" => "dimensions",
				"css" => array(
					"selector" => ".ezfc-form",
					"property" => "font-size"
				),
				"value" => ""
			),
			"css_background_color" => array(
				"id" => 53,
				"name" => "css_background_color",
				"description" => __("Background color", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-form",
					"property" => "background-color"
				),
				"value" => ""
			),
			"css_background_image" => array(
				"id" => 52,
				"name" => "css_background_image",
				"description" => __("Background image", "ezfc"),
				"type" => "file",
				"css" => array(
					"selector" => ".ezfc-form",
					"property" => "background-image",
					"is_url"   => true
				),
				"value" => ""
			),
			"css_background_attachment" => array(
				"id" => 107,
				"name" => "css_background_attachment",
				"description" => __("Background attachment", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"css" => array(
					"selector" => ".ezfc-form",
					"property" => "background-attachment"
				),
				"value" => ""
			),
			"css_background_repeat" => array(
				"id" => 108,
				"name" => "css_background_repeat",
				"description" => __("Background repeat", "ezfc"),
				"type" => Ezfc_settings::$type_dropdown,
				"default" => "no-repeat",
				"options" => array(
					"no-repeat" => "no-repeat",
					"repeat" => "repeat",
					"repeat-x" => "repeat-x",
					"repeat-y" => "repeat-y"
				),
				"css" => array(
					"selector" => ".ezfc-form",
					"property" => "background-repeat"
				),
				"value" => ""
			),
			"css_background_size" => array(
				"id" => 106,
				"name" => "css_background_size",
				"description" => __("Background size", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"css" => array(
					"selector" => ".ezfc-form",
					"property" => "background-size"
				),
				"default" => "contain",
				"value" => ""
			),
			"css_text_color" => array(
				"id" => 54,
				"name" => "css_text_color",
				"description" => __("Text color", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-form,.ezfc-element-wrapper-subtotal span",
					"property" => "color"
				),
				"value" => ""
			),
			"css_input_background_color" => array(
				"id" => 55,
				"name" => "css_input_background_color",
				"description" => __("Input background color", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-element-input,.ezfc-element-numbers,.ezfc-element-textarea,.ezfc-element-select",
					"property" => "background-color"
				),
				"value" => ""
			),
			"css_input_text_color" => array(
				"id" => 56,
				"name" => "css_input_text_color",
				"description" => __("Input text color", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-element-input,.ezfc-element-numbers,.ezfc-element-textarea,.ezfc-element-select",
					"property" => "color"
				),
				"value" => ""
			),
			"css_input_border" => array(
				"id" => 57,
				"name" => "css_input_border",
				"description" => __("Input border", "ezfc"),
				"description_long" => __("Color, size (px), style, border-radius (px)", "ezfc"),
				"type" => "border",
				"separator" => " ",
				"css" => array(
					"selector" => ".ezfc-element-input,.ezfc-element-numbers,.ezfc-element-textarea,.ezfc-element-select",
					"property" => "border"
				),
				"value" => ""
			),
			"css_input_padding" => array(
				"id" => 71,
				"name" => "css_input_padding",
				"description" => __("Input padding", "ezfc"),
				"type" => "dimensions",
				"css" => array(
					"selector" => ".ezfc-element-input,.ezfc-element-numbers,.ezfc-element-textarea,.ezfc-element-select",
					"property" => "padding"
				),
				"value" => ""
			),
			"css_label_font_size" => array(
				"id" => 142,
				"name" => "css_label_font_size",
				"description" => __("Label font size", "ezfc"),
				"type" => "dimensions",
				"css" => array(
					"selector" => ".ezfc-label",
					"property" => "font-size"
				),
				"value" => ""
			),
			"css_label_color" => array(
				"id" => 161,
				"name" => "css_label_color",
				"description" => __("Label color", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-label",
					"property" => "color"
				),
				"value" => ""
			),
			"css_label_width" => array(
				"id" => 162,
				"name" => "css_label_width",
				"description" => __("Label width", "ezfc"),
				"type" => "dimensions",
				"css" => array(
					"selector" => ".ezfc-label",
					"property" => "width"
				),
				"value" => ""
			),
			"css_label_description_color" => array(
				"id" => 181,
				"name" => "css_label_description_color",
				"description" => __("Label below description color", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-element-description-below-label",
					"property" => "color"
				),
				"value" => ""
			),
			"css_label_description_size" => array(
				"id" => 182,
				"name" => "css_label_description_size",
				"description" => __("Label below description font size", "ezfc"),
				"type" => "dimensions",
				"css" => array(
					"selector" => ".ezfc-element-description-below-label",
					"property" => "font-size"
				),
				"value" => ""
			),
			"css_label_description_below_element_color" => array(
				"id" => 183,
				"name" => "css_label_description_below_element_color",
				"description" => __("Label below element color", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-element-description-below-element",
					"property" => "color"
				),
				"value" => ""
			),
			"css_label_description_below_element_size" => array(
				"id" => 184,
				"name" => "css_label_description_below_element_size",
				"description" => __("Label below element font size", "ezfc"),
				"type" => "dimensions",
				"css" => array(
					"selector" => ".ezfc-element-description-below-element",
					"property" => "font-size"
				),
				"value" => ""
			),
			// submit button
			"css_submit_image" => array(
				"id" => 60,
				"name" => "css_submit_image",
				"description" => __("Submit button image", "ezfc"),
				"type" => "file",
				"css" => array(
					"selector" => ".ezfc-element-submit",
					"property" => "background-image",
					"is_url"   => true,
					"add"      => array(
						"background-repeat" => "no-repeat",
						"background-size" => "contain"
					),
					"hover_override" => true
				),
				"value" => ""
			),
			"css_submit_background" => array(
				"id" => 61,
				"name" => "css_submit_background",
				"description" => __("Submit button background", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-element-submit",
					"property" => "background-color"
				),
				"value" => ""
			),
			"css_submit_background_hover" => array(
				"id" => 124,
				"name" => "css_submit_background_hover",
				"description" => __("Submit button background hover", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-element-submit:hover",
					"property" => "background-color"
				),
				"value" => ""
			),
			"css_submit_text_color" => array(
				"id" => 62,
				"name" => "css_submit_text_color",
				"description" => __("Submit button text color", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-element-submit",
					"property" => "color"
				),
				"value" => ""
			),
			"css_submit_text_color_hover" => array(
				"id" => 125,
				"name" => "css_submit_text_color_hover",
				"description" => __("Submit button text color hover", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-element-submit:hover",
					"property" => "color"
				),
				"value" => ""
			),
			"css_submit_border" => array(
				"id" => 63,
				"name" => "css_submit_border",
				"description" => __("Submit button border", "ezfc"),
				"description_long" => __("Color, size (px), style, border-radius (px)", "ezfc"),
				"type" => "border",
				"separator" => " ",
				"css" => array(
					"selector" => ".ezfc-element-submit",
					"property" => "border"
				),
				"value" => ""
			),
			// step styling
			"css_step_button_image" => array(
				"id" => 64,
				"name" => "css_step_button_image",
				"description" => __("Step button image", "ezfc"),
				"type" => "file",
				"css" => array(
					"selector" => ".ezfc-step-button",
					"property" => "background-image",
					"is_url"   => true,
					"add"      => array(
						"background-repeat" => "no-repeat",
						"background-size" => "contain"
					),
					"hover_override" => true
				),
				"value" => ""
			),
			"css_step_buttons_position" => array(
				"id" => 205,
				"name" => "css_step_buttons_position",
				"description" => __("Step buttons position", "ezfc"),
				"type" => Ezfc_settings::$type_dropdown,
				"options" => array(
					"left"   => __("Left", "ezfc"),
					"center" => __("Center", "ezfc"),
					"right"  => __("Right", "ezfc")
				),
				"css" => array(
					"selector" => ".ezfc-step-button-wrapper",
					"property" => "text-align"
				),
				"default" => "center",
				"value" => ""
			),
			"css_step_button_background" => array(
				"id" => 65,
				"name" => "css_step_button_background",
				"description" => __("Step button background", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-step-button",
					"property" => "background-color"
				),
				"value" => ""
			),
			"css_step_button_text_color" => array(
				"id" => 66,
				"name" => "css_step_button_text_color",
				"description" => __("Step button text color", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-step-button",
					"property" => "color"
				),
				"value" => ""
			),
			"css_step_button_border" => array(
				"id" => 67,
				"name" => "css_step_button_border",
				"description" => __("Step button border", "ezfc"),
				"description_long" => __("Color, size (px), style, border-radius (px)", "ezfc"),
				"type" => "border",
				"separator" => " ",
				"css" => array(
					"selector" => ".ezfc-step-button",
					"property" => "border"
				),
				"value" => ""
			),
			"css_title_font_size" => array(
				"id" => 68,
				"name" => "css_title_font_size",
				"description" => __("Step title font size", "ezfc"),
				"type" => "dimensions",
				"css" => array(
					"selector" => ".ezfc-step-title",
					"property" => "font-size"
				),
				"value" => ""
			),
			// fixed price
			"css_fixed_price_font_size" => array(
				"id" => 72,
				"name" => "css_fixed_price_font_size",
				"description" => __("Fixed price font size", "ezfc"),
				"type" => "dimensions",
				"css" => array(
					"selector" => ".ezfc-fixed-price",
					"property" => "font-size"
				),
				"value" => ""
			),
			"css_fixed_price_background_color" => array(
				"id" => 73,
				"name" => "css_fixed_price_background_color",
				"description" => __("Fixed price background color", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-fixed-price",
					"property" => "background-color"
				),
				"value" => ""
			),
			"css_fixed_price_text_color" => array(
				"id" => 74,
				"name" => "css_fixed_price_text_color",
				"description" => __("Fixed price color", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-fixed-price",
					"property" => "color"
				),
				"value" => ""
			),
			// summary table
			"css_summary_width" => array(
				"id" => 82,
				"name" => "css_summary_width",
				"description" => __("Summary table width", "ezfc"),
				"type" => "dimensions",
				"css" => array(
					"selector" => ".ezfc-summary-table",
					"property" => "width"
				),
				"value" => ""
			),
			"css_summary_bgcolor_even" => array(
				"id" => 83,
				"name" => "css_summary_bgcolor_even",
				"description" => __("Summary table even row", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-summary-table tr:nth-child(even)",
					"property" => "background-color"
				),
				"value" => ""
			),
			"css_summary_bgcolor_odd" => array(
				"id" => 84,
				"name" => "css_summary_bgcolor_odd",
				"description" => __("Summary table odd row", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-summary-table tr:nth-child(odd)",
					"property" => "background-color"
				),
				"value" => ""
			),
			"css_summary_text_color" => array(
				"id" => 85,
				"name" => "css_summary_text_color",
				"description" => __("Summary table text color", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-summary-table",
					"property" => "color"
				),
				"value" => ""
			),
			"css_summary_total_background" => array(
				"id" => 86,
				"name" => "css_summary_total_background",
				"description" => __("Summary total row color", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-summary-table .ezfc-summary-table-total",
					"property" => "background-color"
				),
				"value" => ""
			),
			"css_summary_total_color" => array(
				"id" => 87,
				"name" => "css_summary_total_color",
				"description" => __("Summary total text color", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-summary-table .ezfc-summary-table-total",
					"property" => "color"
				),
				"value" => ""
			),
			"css_summary_table_padding" => array(
				"id" => 88,
				"name" => "css_summary_table_padding",
				"description" => __("Summary table padding", "ezfc"),
				"type" => "dimensions",
				"css" => array(
					"selector" => ".ezfc-summary-table td",
					"property" => "padding"
				),
				"value" => ""
			),
			// other
			"css_element_spacing" => array(
				"id" => 143,
				"name" => "css_element_vertical_spacing",
				"description" => __("Element vertical spacing", "ezfc"),
				"type" => "dimensions",
				"css" => array(
					"selector" => ".ezfc-element",
					"property" => "padding-bottom"
				),
				"value" => ""
			),
			"css_form_padding" => array(
				"id" => 69,
				"name" => "css_form_padding",
				"description" => __("Form padding", "ezfc"),
				"type" => "dimensions",
				"css" => array(
					"selector" => ".ezfc-form",
					"property" => "padding"
				),
				"value" => ""
			),
			"css_form_width" => array(
				"id" => 70,
				"name" => "css_form_width",
				"description" => __("Form width", "ezfc"),
				"type" => "dimensions",
				"css" => array(
					"selector" => ".ezfc-form",
					"property" => "width"
				),
				"value" => ""
			),
			"css_form_height" => array(
				"id" => 109,
				"name" => "css_form_height",
				"description" => __("Form height", "ezfc"),
				"type" => "dimensions",
				"css" => array(
					"selector" => ".ezfc-form",
					"property" => "height"
				),
				"value" => ""
			),
			"css_overflow_x" => array(
				"id" => 110,
				"name" => "css_overflow_x",
				"description" => __("Overflow-x", "ezfc"),
				"type" => Ezfc_settings::$type_dropdown,
				"options" => array(
					"initial" => "initial",
					"auto" => "auto",
					"visible" => "visible",
					"hidden" => "hidden",
					"scroll" => "scroll",
					"inherit" => "inherit",
					"unset" => "unset",
				),
				"css" => array(
					"selector" => ".ezfc-form",
					"property" => "overflow-x"
				),
				"default" => "initial",
				"value" => ""
			),
			"css_overflow_y" => array(
				"id" => 111,
				"name" => "css_overflow_y",
				"description" => __("Overflow-y", "ezfc"),
				"type" => Ezfc_settings::$type_dropdown,
				"options" => array(
					"initial" => "initial",
					"auto" => "auto",
					"visible" => "visible",
					"hidden" => "hidden",
					"scroll" => "scroll",
					"inherit" => "inherit",
					"unset" => "unset",
				),
				"css" => array(
					"selector" => ".ezfc-form",
					"property" => "overflow-y"
				),
				"default" => "initial",
				"value" => ""
			),
			"css_vertical_spacing" => array(
				"id" => 93,
				"name" => "css_vertical_spacing",
				"description" => __("Vertical spacing", "ezfc"),
				"type" => "dimensions",
				"css" => array(
					"selector" => ".ezfc-element-wrapper-spacer",
					"property" => "height"
				),
				"value" => ""
			),
			"css_form_direction" => array(
				"id" => 144,
				"name" => "css_form_direction",
				"description" => __("Direction", "ezfc"),
				"description_long" => __("Direction of content flow."),
				"type" => Ezfc_settings::$type_dropdown,
				"options" => array(
					"ltr"     => __("Left to right", "ezfc"),
					"rtl"     => __("Right to left", "ezfc"),
					"inherit" => __("Inherit", "ezfc")
				),
				"css" => array(
					"selector" => ".ezfc-form *",
					"property" => "direction",
					"add"      => array(
						"unicode-bidi" => "bidi-override"
					),
				),
				"default" => "ltr",
				"value" => ""
			)
		),
		
		__("WooCommerce", "ezfc") => array(
			"woo_product_id" => array(
				"id" => 35,
				"name" => "woo_product_id",
				"default" => "",
				"description" => __("WooCommerce Product ID", "ezfc"),
				"description_long" => __("WooCommerce product ID for this form", "ezfc"),
				"type" => "",
				"value" => "", "premium" => true
			),
			"woo_disable_form" => array(
				"id" => 37,
				"name" => "woo_disable_form",
				"default" => 0,
				"description" => __("Disable WooCommerce for this form", "ezfc"),
				"description_long" => __("In case you don't want to add products from this form (e.g. use it as a contact form), you can set this to 'Yes'.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"woo_categories" => array(
				"id" => 90,
				"name" => "woo_categories",
				"default" => "",
				"description" => __("Categories", "ezfc"),
				"description_long" => __("Add form to these product categories only. Separate categories by comma. <strong>Use category slug, not name!</strong>. Leave blank to add the form to all categories.", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			),
			"woo_quantity_element" => array(
				"id" => 145,
				"name" => "woo_quantity_element",
				"default" => "",
				"description" => __("Quantity element", "ezfc"),
				"description_long" => __("This element will act as the product quantity in WooCommerce.", "ezfc"),
				"type" => "form_element",
				"value" => "", "premium" => true
			),
			"woo_send_order_mails" => array(
				"id" => 156,
				"name" => "woo_send_order_mails",
				"default" => 0,
				"description" => __("Send additional submission emails", "ezfc") . " [BETA]",
				"description_long" => __("By default, submission emails will not be sent to the customer or admin. However, you can enable this option and the plugin will send a mail for each order item added by the plugin.", "ezfc"),
				"type" => Ezfc_settings::$type_dropdown,
				"options" => array(
					0 => __("Disabled", "ezfc"),
					"admin"    => __("Send to admin", "ezfc"),
					"both"     => __("Send to admin and customer", "ezfc")
				),
				"value" => "", "premium" => true
			)
		),

		"Zapier" => array(
			"zapier_enabled" => array(
				"id" => 202,
				"name" => "zapier_enabled",
				"default" => 0,
				"description" => __("Enable Zapier integration", "ezfc"),
				"description_long" => "",
				"type" => Ezfc_settings::$type_yesno,
				"value" => "", "premium" => true
			),
			"zapier_webhook_url" => array(
				"id" => 203,
				"name" => "zapier_webhook_url",
				"default" => "",
				"description" => __("Webhook URL", "ezfc"),
				"description_long" => __("Webhook URL generated by Zapier.", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"value" => "", "premium" => true
			)
		)
	);

	return $settings;
}