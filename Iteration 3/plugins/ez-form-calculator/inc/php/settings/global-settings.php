<?php

defined( 'ABSPATH' ) OR exit;

function ezfc_get_global_settings() {
	$deprecated = " | <strong>" . __("Deprecated", "ezfc") . "</strong>";

	$settings = array(
		__("Customization", "ezfc") => array(
			"company_name" => array("description" => __("Company name", "ezfc"), "description_long" => __("Your company name will be displayed in styled email submissions and PDF files.", "ezfc"), "type" => Ezfc_settings::$type_input, "default" => ""),
			"logo" => array("description" => __("Logo", "ezfc"), "description_long" => __("This logo will be displayed in styled email submissions and PDF files.", "ezfc"), "type" => Ezfc_settings::$type_file, "default" => ""),
			"custom_css" => array("description" => __("Custom CSS", "ezfc"), "description_long" => __("Add your custom styles here.", "ezfc"), "type" => Ezfc_settings::$type_textarea),
			"custom_js_enable" => array("description" => __("Enable custom JS", "ezfc"), "description_long" => __("Add custom JS code to the website (only on pages where a form is shown).", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 0),
			"custom_js" => array("description" => __("Custom JS", "ezfc"), "description_long" => __("Add your custom JS code here. Please note that you do not need to wrap the code into a script-tag. Additionally, make sure that the syntax is correct or else you might break your website. Leave blank if you don't know what you're doing.", "ezfc"), "type" => Ezfc_settings::$type_textarea, "default" => "function ez_foo() { return 1; }"),
			"required_text" => array("description" => __("Required text", "ezfc"), "description_long" => __("This text is shown below the form.", "ezfc"), "type" => Ezfc_settings::$type_input, "default" => __("Required", "ezfc")),
			"required_text_element" => array("description" => __("Required element text", "ezfc"), "description_long" => __("This text will be shown when a required element is empty. Default: 'This field is required'", "ezfc"), "type" => Ezfc_settings::$type_input, "default" => __("This field is required.", "ezfc")),
			"required_text_position" => array("description" => __("Required text position", "ezfc"), "description_long" => __("Position of the required text tip.", "ezfc"), "type" => Ezfc_settings::$type_dropdown, "default" => "middle right", "options" => array(
				"bottom left"  => __("Top left", "ezfc"),
				"bottom"       => __("Top", "ezfc"),
				"bottom right" => __("Top right", "ezfc"),
				"middle left"  => __("Middle left", "ezfc"),
				"middle"       => __("Middle", "ezfc"),
				"middle right" => __("Middle right", "ezfc"),
				"top left"     => __("Bottom left", "ezfc"),
				"top"          => __("Bottom", "ezfc"),
				"top right"    => __("Bottom right", "ezfc")
			)),
			"required_text_auto_hide" => array("description" => __("Required text auto hide", "ezfc"), "description_long" => __("Seconds to automatically hide the required text tooltip. Leave blank or set to 0 to disable this option.", "ezfc"), "type" => Ezfc_settings::$type_input, "default" => ""),
			"text_error_min_selectable" => array("description" => __("Minimum selectable options text", "ezfc"), "description_long" => __("This error message will be shown when not enough options were selected.", "ezfc"), "type" => Ezfc_settings::$type_input, "default" => __("Please select at least %d options.", "ezfc")),
			"text_error_max_selectable" => array("description" => __("Maximum selectable options text", "ezfc"), "description_long" => __("This error message will be shown when too many options were selected.", "ezfc"), "type" => Ezfc_settings::$type_input, "default" => __("Please select %d options at max.", "ezfc")),
			"datepicker_language" => array("description" => __("Datepicker language", "ezfc"), "description_long" => __("Datepicker language. Default: 'en'", "ezfc"), "type" => Ezfc_settings::$type_input),
			"datepicker_load_languages" => array("description" => __("Load datepicker languages", "ezfc"), "description_long" => __("Load additional datepicker languages. Only set this option to 'Yes' when using a different language than English since all languages will be loaded with an additional ~40kb file. If you know what you are doing, you can remove all unneccessary data from the file /ez-form-calculator-premium/assets/js/jquery.ui.u18n.all.min.js", "ezfc"), "type" => Ezfc_settings::$type_yesno),
			"daterange_min_days_error" => array("description" => __("Daterange minimum days text", "ezfc"), "description_long" => __("This error message will be shown when not enough days were selected in the daterange element.", "ezfc"), "default" => __("Please select at least %s days.", "ezfc"), "type" => Ezfc_settings::$type_input),
			"daterange_max_days_error" => array("description" => __("Daterange maximum days text", "ezfc"), "description_long" => __("This error message will be shown when too many days were selected in the daterange element.", "ezfc"), "default" => __("Please select at most %s days.", "ezfc"), "type" => Ezfc_settings::$type_input),
			"auto_scroll_steps" => array("description" => __("Auto scroll steps", "ezfc"), "description_long" => __("Automatically scroll to top upon changing steps.", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 1),
			"loading_icon" => array("description" => __("Loading icon", "ezfc"), "description_long" => __("This icon will be shown when the form is submitted.", "ezfc"), "type" => Ezfc_settings::$type_input, "default" => "fa fa-cog fa-spin"),
			"scroll_steps" => array("description" => __("Scroll steps", "ezfc"), "description_long" => __("The browser window scrolls to the top of the form automatically.", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 1),
			"scroll_steps_offset" => array("description" => __("Scroll offset", "ezfc"), "description_long" => __("Top offset when scrolling (in px)", "ezfc"), "type" => Ezfc_settings::$type_input, "default" => ""),
			//"opentip_background" => array("description" => __("Tooltip background color", "ezfc"), "description_long" => "", "type" => "colorpicker", "default" => "yellow")
			"allow_label_shortcodes" => array("description" => __("Allow label shortcodes", "ezfc"), "description_long" => __("By default, shortcodes will not be executed in labels. Turn this option on to allow shortcodes in labels.", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 0),
			"allow_value_shortcodes" => array("description" => __("Allow value shortcodes", "ezfc"), "description_long" => __("Shortcodes can be parsed in element values. All values will be escaped before the actual output.", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 1),
			"allow_option_html"       => array("description" => __("Allow HTML in options", "ezfc"), "description_long" => __("Special characters will be encoded to HTML entities when displayed. If you wish to use HTML in checkbox or radio options, you can enable this option.", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 0),
			"form_show_loading" => array("description" => __("Show form loading text", "ezfc"), "description_long" => __("When this option is enabled, the form will be faded in as soon as the page is completely loaded. This way, no form fragments will be shown.", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 1),
			"form_show_loading_text" => array("description" => __("Form loading text", "ezfc"), "description_long" => __("Show this text while the form is loading.", "ezfc"), "type" => Ezfc_settings::$type_input, "default" => __("Loading...", "ezfc")),
			"form_submit_error" => array("description" => __("Form submission error text", "ezfc"), "description_long" => __("Whenever the form fails to submit (internal error, SSL interface error etc.), this text will be shown.", "ezfc"), "type" => Ezfc_settings::$type_input, "default" => __("An unknown error occured. Please try again later.", "ezfc"))
		),

		__("Price", "ezfc") => array(
			"price_format" => array(
				"description" => __("Price format", "ezfc"),
				"description_long" => sprintf(__("See %s for syntax documentation", "ezfc"), "<a href='http://numeraljs.com/' target='_blank'>numeraljs.com</a>"),
				"type" => Ezfc_settings::$type_input,
				"default" => "0,0[.]00"
			),
			"email_price_format_thousand"  => array(
				"description" => __("Price format thousands separator", "ezfc"),
				"description_long" => __("Thousands separator", "ezfc"),
				"type" => Ezfc_settings::$type_dropdown,
				"options" => array(
					"." => __("Dot (.)", "ezfc"),
					"," => __("Comma (,)", "ezfc"),
					"&apos;" => __("Apostrophe (')", "ezfc"),
					" " => __("Space", "ezfc"),
					""  => __("Blank", "ezfc")
				),
				"default" => ","
			),
			"email_price_format_dec_point" => array(
				"description" => __("Price format decimal point", "ezfc"),
				"description_long" => __("Decimal point separator", "ezfc"),
				"type" => Ezfc_settings::$type_dropdown,
				"options" => array(
					"." => __("Dot (.)", "ezfc"),
					"," => __("Comma (,)", "ezfc")
				),
				"default" => "."
			),
			"price_format_replace_trailing_zeros" => array(
				"description" => __("Replace trailing zeros", "ezfc"),
				"description_long" => __("If the price is a round number, replace trailing zeros with a character.", "ezfc"),
				"type" => Ezfc_settings::$type_bool_text,
				"options" => array(
					"enabled" => 0,
					"text"    => "-"
				),
				"default" => array(
					"enabled" => 0,
					"text"    => "-"
				)
			)
		),

		__("Email", "ezfc") => array(
			"email_text_footer" => array("description" => __("Email footer text", "ezfc"), "description_long" => __("This text will be displayed in the footer section of styled email submissions.", "ezfc"), "type" => Ezfc_settings::$type_editor, "default" => ""),
			"email_price_format_dec_num" => array("description" => __("Price format decimals", "ezfc"), "description_long" => __("Number of decimals in email prices", "ezfc"), "type" => Ezfc_settings::$type_input, "default" => 2),
			"email_font_family" => array("description" => __("Email font family", "ezfc"), "description_long" => __("Font to be used in email body. It is recommended to provide fallback fonts, example: Arial, Helvetica, sans-serif", "ezfc"), "type" => Ezfc_settings::$type_input, "default" => "Arial, Helvetica, sans-serif"),
			"email_plain_html" => array("description" => __("Use plain HTML", "ezfc"), "description_long" => __("If disabled, HTML elements will be shown as code in emails.", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 1),
			"email_smtp_enabled" => array("description" => __("Enable SMTP", "ezfc"), "description_long" => "", "type" => Ezfc_settings::$type_yesno, "premium" => true),
			"email_smtp_anon"    => array("description" => __("Anonymous authentication", "ezfc"), "description_long" => __("If this option is enabled, the plugin will connect to the SMTP server anonymously.", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 0, "premium" => true),
			"email_smtp_host"    => array("description" => __("SMTP Host", "ezfc"), "description_long" => "", "type" => Ezfc_settings::$type_input, "premium" => true),
			"email_smtp_user"    => array("description" => __("SMTP Username", "ezfc"), "description_long" => "", "type" => Ezfc_settings::$type_input, "premium" => true),
			"email_smtp_pass"    => array("description" => __("SMTP Password", "ezfc"), "description_long" => "", "type" => Ezfc_settings::$type_password, "premium" => true),
			"email_smtp_port"    => array("description" => __("SMTP Port", "ezfc"), "description_long" => __("Default ports: 25 (no encryption), 465 (SSL), 587 (TLS).", "ezfc"), "type" => Ezfc_settings::$type_input, "premium" => true),
			"email_smtp_secure"  => array("description" => __("SMTP Encryption", "ezfc"), "description_long" => "", "type" => Ezfc_settings::$type_dropdown, "premium" => true, "options" => array(
				"0"   => __("No encryption", "ezfc"),
				"ssl" => "SSL",
				"tls" => "TLS" . " " . __("(recommended)", "ezfc")
			)),
			"email_smtp_debug_level"  => array("description" => __("SMTP Debug Level", "ezfc"), "description_long" => sprintf(__("Debug output level. If you're having problems connecting or sending emails through your SMTP server, you can increase the value to debug the issue. %s", "ezfc"), "<a href='https://github.com/PHPMailer/PHPMailer/wiki/SMTP-Debugging' target='_blank'>" . __("Debug levels", "ezfc") . "</a>"),  "type" => Ezfc_settings::$type_input, "default" => 0, "premium" => true),
			"email_smtp_disable_verify_peer"  => array("description" => __("SMTP Disable Verify Peer", "ezfc"), "description_long" => __("Disables verify_peer and verify_peer_name and enables allow_self_signed. (Only change this if you know what you're doing)", "ezfc"), "type" => Ezfc_settings::$type_yesno, "premium" => true),
		),

		__("PayPal", "ezfc") => array(
			"pp_api_username"         => array("description" => __("PayPal API username", "ezfc"), "description_long" => __("See <a href='https://developer.paypal.com/docs/classic/api/apiCredentials/'>PayPal docs</a> to read how to get your API credentials.", "ezfc"), "type" => Ezfc_settings::$type_input, "premium" => true),
			"pp_api_password"         => array("description" => __("PayPal API password", "ezfc"), "description_long" => "", "type" => Ezfc_settings::$type_password, "premium" => true),
			"pp_api_signature"        => array("description" => __("PayPal API signature", "ezfc"), "description_long" => "", "type" => Ezfc_settings::$type_input, "premium" => true),
			"pp_return_url"           => array("description" => __("Return URL", "ezfc"), "description_long" => __("The return URL is the location where buyers return to when a payment has been succesfully authorized. <br>You need to use this shortcode on the return page/post or else it will not work:<br>[ezfc_verify]", "ezfc"), "type" => Ezfc_settings::$type_input, "premium" => true),
			"pp_cancel_url"           => array("description" => __("Cancel URL", "ezfc"), "description_long" => __("The cancelURL is the location buyers are sent to when they hit the cancel button during authorization of payment during the PayPal flow.", "ezfc"), "type" => Ezfc_settings::$type_input, "premium" => true),
			"pp_currency_code"        => array("description" => __("Currency code", "ezfc"), "description_long" => "", "type" => Ezfc_settings::$type_currencycodes, "premium" => true),
			"pp_sandbox"              => array("description" => __("Use sandbox", "ezfc"), "description_long" => __("Set to 'yes' for testing purposes.", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 0, "premium" => true),
			"pp_acount_required"      => array("description" => __("Account required", "ezfc"), "description_long" => __("Whether a PayPal account is required or not. If an account is optional, you still need to do the following step: Log in to your PayPal account, go to the Profile subtab, click on Website Payment Preferences under the Selling Preferences column, and check the yes/no box under PayPal Account Optional.", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 1, "premium" => true)
			//"pp_enable_ipn"           => array("description" => __("Enable IPN", "ezfc"), "description_long" => sprintf(__("Enable PayPal IPN. Your PayPal IPN URL is: %s", "ezfc"), trailingslashit(get_home_url()) . "ezfc-pp-ipn.php"), "type" => Ezfc_settings::$type_yesno, "default" => 0)
		),

		__("Stripe", "ezfc") => array(
			"stripe_enabled" => array("description" => __("Enable Stripe", "ezfc"), "description_long" => "", "type" => Ezfc_settings::$type_yesno, "default" => 0, "premium" => true),
			"stripe_currency_code" => array("description" => __("Currency code", "ezfc"), "description_long" => "", "type" => Ezfc_settings::$type_currencycodes, "premium" => true),
			"stripe_secret_key" => array("description" => __("Secret key", "ezfc"), "description_long" => "", "type" => Ezfc_settings::$type_input, "premium" => true),
			"stripe_publishable_key" => array("description" => __("Publishable key", "ezfc"), "description_long" => "", "type" => Ezfc_settings::$type_input, "premium" => true)
		),

		__("PDF", "ezfc") => array(
			"pdf_save_file" => array(
				"name" => "pdf_save_file",
				"description" => __("Save PDF files", "ezfc"),
				"description_long" => sprintf(__("Generated PDF files will be saved on the server if the option is set to 'Yes'. The save folder is %s.", "ezfc"), "/wp-content/uploads/ezfc-pdf/"),
				"type" => Ezfc_settings::$type_yesno,
				"default" => 0, "premium" => true
			),
			"pdf_allow_remote" => array(
				"name" => "pdf_allow_remote",
				"description" => __("Allow remote content", "ezfc"),
				"description_long" => __("If this setting is set to true, the PDF library (DOMPDF) will access remote sites for images and CSS files as required.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"default" => 1, "premium" => true
			),
			"pdf_allow_shortcodes" => array(
				"name" => "pdf_allow_shortcodes",
				"description" => __("Allow shortcodes", "ezfc"),
				"description_long" => __("If this setting is set to true, shortcodes can be executed in the PDF content. This might break PDF generation, so you should deactivate this option if no PDF can be generated.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"default" => 1, "premium" => true
			),
			"pdf_page_orientation" => array(
				"name" => "pdf_page_orientation",
				"description" => __("Page orientation", "ezfc"),
				"type" => Ezfc_settings::$type_dropdown,
				"options" => array(
					"portrait" => __("Portrait", "ezfc"),
					"landscape" => __("Landscape", "ezfc")
				),
				"default" => "portrait", "premium" => true
			),
			"pdf_page_size" => array(
				"name" => "pdf_page_size",
				"description" => __("Page size", "ezfc"),
				"description_long" => __("Size of the pdf page, e.g. letter (default), A4, A5.", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"default" => "letter", "premium" => true
			),
			"pdf_text_company" => array("description" => __("PDF company text", "ezfc"), "description_long" => __("This text will be displayed in the company section in generated PDF files.", "ezfc"), "type" => Ezfc_settings::$type_editor, "default" => "", "premium" => true),
			"pdf_text_header" => array("description" => __("PDF header text", "ezfc"), "description_long" => __("This text will be displayed in the header section in generated PDF files.", "ezfc"), "type" => Ezfc_settings::$type_editor, "default" => "", "premium" => true),
			"pdf_text_footer" => array("description" => __("PDF footer text", "ezfc"), "description_long" => __("This text will be displayed in the footer section in generated PDF files.", "ezfc"), "type" => Ezfc_settings::$type_editor, "default" => "", "premium" => true),
			"pdf_css_styles" => array(
				"name" => "pdf_css_styles",
				"description" => __("PDF CSS styles", "ezfc"),
				"description_long" => __("Add your own CSS styles here.", "ezfc"),
				"type" => Ezfc_settings::$type_textarea,
				"default" => "", "premium" => true
			)
		),

		__("ReCaptcha", "ezfc") => array(
			"captcha_public"  => array("description" => __("Recaptcha public key", "ezfc"), "description_long" => "", "type" => Ezfc_settings::$type_input, "premium" => true),
			"captcha_private" => array("description" => __("Recaptcha private key", "ezfc"), "description_long" => "", "type" => Ezfc_settings::$type_input, "premium" => true)
		),

		__("Styling", "ezfc") => array(
			"load_custom_styling" => array(
				"name" => "load_custom_styling",
				"description" => __("Load custom styling", "ezfc"),
				"description_long" => __("Only enable this option if you want to use the styling below.", "ezfc"),
				"type" => Ezfc_settings::$type_yesno,
				"default" => 0,
				"value" => ""
			),
			// will be generated automatically
			"css_custom_styling" => array(
				"name" => "css_custom_styling",
				"description" => "",
				"description_long" => "",
				"type" => "hidden",
				"default" => "",
				"value" => ""
			),
			"css_font" => array(
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
			"css_background_size" => array(
				"default" => "contain",
				"name" => "css_background_size",
				"description" => __("Background size", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"css" => array(
					"selector" => ".ezfc-form",
					"property" => "background-size"
				),
				"value" => ""
			),
			"css_background_repeat" => array(
				"name" => "css_background_repeat",
				"description" => __("Background repeat", "ezfc"),
				"type" => Ezfc_settings::$type_input,
				"css" => array(
					"selector" => ".ezfc-form",
					"property" => "background-repeat"
				),
				"value" => ""
			),
			"css_text_color" => array(
				"name" => "css_text_color",
				"description" => __("Text color", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-form",
					"property" => "color"
				),
				"value" => ""
			),
			// input
			"css_input_background_color" => array(
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
				"name" => "css_label_font_size",
				"description" => __("Label font size", "ezfc"),
				"type" => "dimensions",
				"css" => array(
					"selector" => ".ezfc-label",
					"property" => "font-size"
				),
				"value" => ""
			),
			// submit button
			"css_submit_image" => array(
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
				"name" => "css_submit_background",
				"description" => __("Submit button background", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-element-submit",
					"property" => "background-color",
					"hover_override" => true
				),
				"value" => ""
			),
			"css_submit_text_color" => array(
				"name" => "css_submit_text_color",
				"description" => __("Submit button text color", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-element-submit",
					"property" => "color",
					"hover_override" => true
				),
				"value" => ""
			),
			"css_submit_border" => array(
				"name" => "css_submit_border",
				"description" => __("Submit button border", "ezfc"),
				"description_long" => __("Color, size (px), style, border-radius (px)", "ezfc"),
				"type" => "border",
				"separator" => " ",
				"css" => array(
					"selector" => ".ezfc-element-submit",
					"property" => "border",
					"hover_override" => true
				),
				"value" => ""
			),
			// step styling
			"css_step_button_image" => array(
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
			"css_step_button_background" => array(
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
				"name" => "css_title_font_size",
				"description" => __("Step title font size", "ezfc"),
				"type" => "dimensions",
				"css" => array(
					"selector" => ".ezfc-step-title",
					"property" => "font-size"
				),
				"value" => ""
			),
			// tooltip
			"css_tooltip_background_color" => array(
				"name" => "css_tooltip_background_color",
				"description" => __("Tooltip background color", "ezfc"),
				"type" => "colorpicker",
				/*"css" => array(
					"selector"  => ".opentip",
					"property"  => "background-color",
					"disable_parent_selector" => true,
					"important" => true
				),*/
				"value" => ""
			),
			"css_tooltip_font_color" => array(
				"name" => "css_tooltip_font_color",
				"description" => __("Tooltip font color", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".opentip",
					"property" => "color",
					"disable_parent_selector" => true,
					"important" => true
				),
				"value" => ""
			),
			// fixed price
			"css_fixed_price_font_size" => array(
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
				"name" => "css_fixed_price_text_color",
				"description" => __("Fixed price color", "ezfc"),
				"type" => "colorpicker",
				"css" => array(
					"selector" => ".ezfc-fixed-price",
					"property" => "color"
				),
				"value" => ""
			),
			// other
			"css_element_spacing" => array(
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
				"description" => __("Form height", "ezfc"),
				"description_long" => "",
				"type" => "dimensions",
				"css" => array(
					"selector" => ".ezfc-form",
					"property" => "height"
				),
				"value" => ""
			),
			"css_overflow_x" => array(
				"description" => __("Overflow-x", "ezfc"),
				"description_long" => "",
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
				"description" => __("Overflow-y", "ezfc"),
				"description_long" => "",
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
				"description" => __("Vertical spacing", "ezfc"),
				"description_long" => "",
				"type" => "dimensions",
				"css" => array(
					"selector" => ".ezfc-element-wrapper-spacer",
					"property" => "height"
				),
				"value" => ""
			)
		),

		"WooCommerce" => array(
			"woocommerce"            => array("description" => __("Integrate with WooCommerce", "ezfc"), "description_long" => __("Integrate with WooCommerce. Please be aware that on a single product page, the product price and add-to-cart button will be hidden since the plugin handles this.", "ezfc"), "type" => Ezfc_settings::$type_yesno, "premium" => true),
			"woocommerce_text"       => array("description" => __("'Added to cart' text", "ezfc"), "description_long" => __("This text will be displayed after a submission was added to the cart.", "ezfc"), "type" => Ezfc_settings::$type_input, "default" => __("Added to cart", "ezfc"), "premium" => true),
			"woocommerce_add_forms"  => array("description" => __("Add forms to products", "ezfc"), "description_long" => __("When this option is enabled, forms will be added to products automatically. If you want to show individual forms for products, please make sure you add a custom field to the product:<br>
				custom field name: ezfc_form_id<br>custom field value: &lt;form_id&gt;<br>
				If you want to show one form for all products, please enter a form ID in 'Global form ID' below.<br>
				<strong>This will replace the 'Add to cart'-button from Woocommerce!</strong><br>
				More information here: <a href='http://ez-form-calculator.ezplugins.de/documentation/woocommerce-integration/' target='_blank'>ezfc Woocommerce Integration</a>", "ezfc"), "type" => Ezfc_settings::$type_yesno, "premium" => true),
			"woocommerce_global_form_id"  => array("description" => __("Global form ID", "ezfc"), "description_long" => __("Form with this ID will be added to the WooCommerce product when 'Add forms to products' is set to 'Yes'", "ezfc"), "type" => Ezfc_settings::$type_input, "premium" => true),
			"woocommerce_checkout_details" => array("description" => __("Show selected values in checkout", "ezfc"), "description_long" => __("Show the selected values in a table on the checkout page", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 1, "premium" => true),
			"woocommerce_checkout_details_text" => array("description" => __("Checkout details text", "ezfc"), "description_long" => __("If 'Show selected values in checkout' is enabled, display this text above the details table.", "ezfc"), "type" => Ezfc_settings::$type_input, "default" => "Selected values", "premium" => true),
			"woocommerce_checkout_details_values" => array("description" => __("Checkout details values", "ezfc"), "description_long" => __("Details text in checkout: 'Full Details' shows all values and calculations. 'Simple details' shows values and prices. 'Values only' shows the selected values only. <strong>Note: only applies to products added after this values was changed.</strong>", "ezfc"), "type" => Ezfc_settings::$type_dropdown, "default" => "result_default", "options" => array(
					"result_default"          => __("Result table v2.0 (result_default)", "ezfc"),
					"result"                  => __("Full details (result)", "ezfc") . " [" . __("Deprecated", "ezfc") . "]",
					"result_simple"           => __("Simple details (result_simple)", "ezfc") . " [" . __("Deprecated", "ezfc") . "]",
					"result_values"           => __("Values only (result_values)", "ezfc") . " [" . __("Deprecated", "ezfc") . "]",
					"result_values_submitted" => __("Submitted values only (result_values_submitted)", "ezfc") . " [" . __("Deprecated", "ezfc") . "]"
			), "premium" => true),
			"woocommerce_enable_edit" => array("description" => __("Editable cart items", "ezfc"), "description_long" => __("Customers can edit the submitted form in the cart. When set to 'Yes', an editable link will be added to relevant cart items.", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 1, "premium" => true),
			"woocommerce_edit_text" => array("description" => __("Edit text", "ezfc"), "description_long" => __("Text of the edit link.", "ezfc"), "type" => Ezfc_settings::$type_input, "default" => __("Edit", "ezfc"), "premium" => true),
			"woocommerce_add_hook"  => array("description" => __("Form display hook", "ezfc"), "description_long" => sprintf(__("WooCommerce hook when forms are added to products (for a full list, see %s", "ezfc"), "<a href='https://docs.woocommerce.com/wc-apidocs/hook-docs.html' target='_blank'>docs.woothemes.com</a>)"), "type" => Ezfc_settings::$type_input, "default" => "woocommerce_before_add_to_cart_form", "premium" => true),
			"woocommerce_product_id" => array("description" => __("WooCommerce product id", "ezfc"), "description_long" => __("<strong>NOTE:</strong> this value is deprecated since v2.7.3 and has no effect - set the WooCommerce product id in the form options", "ezfc"), "type" => Ezfc_settings::$type_input, "premium" => true),
			"woocommerce_update_cart" => array("description" => __("Update cart", "ezfc"), "description_long" => __("Update the mini-cart after adding products to the cart.", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 0, "premium" => true),
			"woocommerce_update_cart_selector" => array("description" => __("Cart CSS selector", "ezfc"), "description_long" => __("The mini-cart CSS selector can change depending on your theme. If you are not sure, you might want to contact the theme author.", "ezfc") . $deprecated, "type" => Ezfc_settings::$type_input, "default" => "", "premium" => true),
			"woocommerce_update_cart_items_number_selector" => array("description" => __("Cart item number CSS selector", "ezfc"), "description_long" => __("Updates the cart item number after a product has been added to the cart. The cart item number CSS selector can change depending on your theme. If you are not sure, you might want to contact the theme author.", "ezfc") . $deprecated, "type" => Ezfc_settings::$type_input, "default" => "", "premium" => true),
			"woocommerce_insert_db" => array("description" => __("Insert form submissions on checkout", "ezfc"), "description_long" => __("Insert form submissions for each product that was added from the form on checkout.", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 0, "premium" => true),
			/*"woocommerce_send_mail" => array("description" => __("Send additional email", "ezfc"), "description_long" => __("Emails won't be sent when using WooCommerce, but you might want to receive a separate email sent by this plugin with the submitted values.", "ezfc"), "type" => Ezfc_settings::$type_dropdown, "default" => 0, "options" => array(
					"0" => "No",
					"admin" => "Admin only",
					"admin_customer" => "Admin and customers"
			)),*/
		),

		__("File upload", "ezfc") => array(
			"upload_override_filetypes" => array("description" => __("Override file types", "ezfc"), "description_long" => __("Override allowed default upload files types.", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 0, "premium" => true),
			"upload_custom_filetypes" => array("description" => __("File types", "ezfc"), "description_long" => __("Allowed upload file types separated by comma.", "ezfc"), "type" => Ezfc_settings::$type_textarea, "default" => "gif,jpg,jpeg,png,pdf,doc,docx,csv,xls,xlsx,mp3,mp4,ogg,avi,zip", "premium" => true),
		),

		__("Form Editor", "ezfc") => array(
			"use_tinymce"            => array("description" => __("Use tinyMCE", "ezfc"), "description_long" => __("Use tinyMCE editor in HTML elements.", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 1),
			"use_large_data_editor"  => array("description" => __("Use large data editor", "ezfc"), "description_long" => __("Use large data editor to edit form elements. The editor will be fixed to the right side and expanded to full height.", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 1),
			"reopen_last_form"       => array("description" => __("Reopen last form", "ezfc"), "description_long" => __("The last form edited will automatically be opened on the main page.", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 1),
			"reopen_last_form_id"    => array("description" => __("Last form ID", "ezfc"), "description_long" => __("ID of the last opened form. The ID will change automatically after each form opened.", "ezfc"), "type" => "hidden", "default" => 0),
			"tinymce_use_relative_urls" => array("description" => __("Use relative URLs in tinyMCE", "ezfc"), "description_long" => __("tinyMCE converts absolute URLs to relative ones on links and images. If you want to always use absolute URLs, you need to disable this option.", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 1),
			"batch_separator" => array("description" => __("Batch edit separator", "ezfc"), "description_long" => __("Separator character which is used to split values in the batch editor.", "ezfc"), "type" => Ezfc_settings::$type_input, "default" => ","),
		),

		"Zapier" => array(
			array("description" => __("Enable Zapier integration", "ezfc"), "description_long" => __("Turn Zapier integration on or off globally.", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 1, "premium" => true)
		),

		__("Privacy", "ezfc") => array(
			"anonymize_ip" => array("description" => __("Anonymize IP", "ezfc"), "description_long" => __("The plugin will anonymize the IP of the user before the form submission.", "ezfc"), "type" => Ezfc_settings::$type_dropdown, "default" => 2, "options" => array(
					0 => __("No anonymization", "ezfc"),
					1 => sprintf(__("Partly (%s)", "ezfc"), "123.123.123.x"),
					2 => sprintf(__("Full (%s)", "ezfc"), "x.x.x.x")
				)
			),
			"store_submissions" => array("description" => __("Store submissions", "ezfc"), "description_long" => __("Form submissions will be saved in the database by default. You can disable this for privacy reasons, though some plugin functions may not work correctly. Previous submissions won't be deleted.", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 1)
		),

		__("Other", "ezfc") => array(
			"calculation_version"    => array("description" => __("Calculation version", "ezfc"), "description_long" => __("The calculation routine has changed in v2.10.2.0. If the new calculation routine shows wrong prices, you can switch to an older version here. It is recommended to update the calculations to the newest version, please see the changelog for more information.", "ezfc"), "type" => Ezfc_settings::$type_dropdown, "default" => 1, "options" => array(
				"current" => __("Current version (recommended)", "ezfc"),
			)),
			"user_roles"             => array("description" => __("User roles", "ezfc"), "description_long" => __("Check which user role has access to edit this plugin.", "ezfc"), "type" => "roles", "default" => "administrator"),
			"mailchimp_api_key"      => array("description" => __("Mailchimp API key", "ezfc"), "description_long" => "<a href='http://kb.mailchimp.com/accounts/management/about-api-keys'>" . __("How to find your API key", "ezfc") . "</a>", "type" => Ezfc_settings::$type_input, "premium" => true),
			"content_filter"         => array("description" => __("Content filter", "ezfc"), "description_long" => __("WordPress filter to apply html elements on. You might want to use 'the_content' if HTML elements look wrong (without quotes).", "ezfc"), "type" => Ezfc_settings::$type_input, "default" => ""),
			"html_convert_encoding"  => array("description" => __("Convert HTML encoding", "ezfc"), "description_long" => sprintf(__("If special characters aren't displayed correctly on the page, you might want to change the encoding. Example: from %s to %s", "ezfc"), "ISO-8859-2", "UTF-8"), "type" => Ezfc_settings::$type_input_multiple, "default" => "", "inputs" => array(
					"from" => array(
						"label" => __("From", "ezfc")
					),
					"to" => array(
						"label" => __("To", "ezfc")
					)
				)
			),
			"html_force_convert_utf8"  => array("description" => __("Force UTF-8 encoding", "ezfc"), "description_long" => __("If special characters aren't displayed correctly on the page, you might want to activate this option.", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 0
			),
			"debug_mode"             => array("description" => __("Enable debug mode", "ezfc"), "description_long" => __("This option should only be enabled if you encounter any issues with the plugin.", "ezfc"), "type" => Ezfc_settings::$type_dropdown, "default" => 0, "options" => array(
				0 => __("No", "ezfc"),
				1 => __("Yes", "ezfc"),
				2 => __("Yes + Frontend details", "ezfc")
			)),
			"debug_mode_required_elements" => array("description" => __("Required elements check", "ezfc"), "description_long" => __("Behaviour of input checks of required elements while debug mode is active.", "ezfc"), "type" => Ezfc_settings::$type_dropdown, "default" => 1, "options" => array(
				0 => __("Disable required checks", "ezfc"),
				1 => __("Enable required checks", "ezfc")
			)),
			"jquery_ui" => array("description" => __("Add default jQuery UI stylesheet", "ezfc"), "description_long" => __("If your theme looks differently after installing this plugin, set this option to 'No' and see again. It may break due to the default jQuery UI stylesheet.", "ezfc"), "type" => Ezfc_settings::$type_yesno, "default" => 1),
			"uninstall_keep_data"    => array("description" => __("Keep data after uninstall", "ezfc"), "description_long" => __("The plugin will keep all plugin-related data in the database when uninstalling. Only select 'Yes' if you want to upgrade the script.", "ezfc"), "type" => Ezfc_settings::$type_yesno),
			"invoice_counter_id" => array("description" => __("Invoice counter ID", "ezfc"), "description_long" => __("Current invoice counter ID.", "ezfc"), "type" => Ezfc_settings::$type_input, "default" => 1),
			"css_form_label_width" => array("description" => __("CSS label width", "ezfc"), "description_long" => __("Width of the labels. Default: 15em", "ezfc") . $deprecated, "type" => Ezfc_settings::$type_input),
		)
	);

	return $settings;
}