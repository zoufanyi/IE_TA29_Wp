<?php

/**
 * Email template
 *
 * This template can be overridden by copying it to yourtheme/ezfc/email/<filename>.php
 */

if ( ! defined( "ABSPATH" ) ) {
	exit;
}

global $style;

// email colors
$accent_color = "#033ba0";
$email_style = array(
	"background_main"    => "#eeeeee",
	"background_content" => "#ffffff",
	"background_header"  => "url(" . EZFC_URL . "assets/img/templates/shape-blue.jpg)",
	"background_footer"  => "url(" . EZFC_URL . "assets/img/templates/shape-blue.jpg)",
	"font_color_content" => "#222222",
	"font_color_header"  => "#ffffff",
	"font_color_info"    => "#ffffff",
	"font_color_footer"  => "#ffffff",
	"heading_border"     => "$accent_color 2px solid",
	"link_color"         => $accent_color
);

?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="x-apple-disable-message-reformatting">
	<meta name="format-detection" content="telephone=no,address=no,email=no,date=no,url=no">
	<title></title>

	<!--[if mso]>
		<style>
			* {
				font-family: sans-serif !important;
			}
		</style>
	<![endif]-->

	<style>
		html,
		body {
			margin: 0 !important;
			padding: 0 !important;
			height: 100% !important;
			width: 100% !important;
		}

		* {
			-ms-text-size-adjust: 100%;
			-webkit-text-size-adjust: 100%;
		}

		table,
		td {
			mso-table-lspace: 0pt !important;
			mso-table-rspace: 0pt !important;
		}

		table {
			border-spacing: 0 !important;
			border-collapse: collapse !important;
			table-layout: fixed !important;
			margin: 0 auto !important;
		}

		img {
			-ms-interpolation-mode:bicubic;
		}

		a {
			text-decoration: none;
		}

		a[x-apple-data-detectors],
		.unstyle-auto-detected-links a,
		.aBn {
			border-bottom: 0 !important;
			cursor: default !important;
			color: inherit !important;
			text-decoration: none !important;
			font-size: inherit !important;
			font-family: inherit !important;
			font-weight: inherit !important;
			line-height: inherit !important;
		}

		.a6S {
			display: none !important;
			opacity: 0.01 !important;
		}

		.im {
			color: inherit !important;
		}

		img.g-img + div {
			display: none !important;
		}

		@media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
			u ~ div .email-container {
				min-width: 320px !important;
			}
		}
		@media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
			u ~ div .email-container {
				min-width: 375px !important;
			}
		}
		@media only screen and (min-device-width: 414px) {
			u ~ div .email-container {
				min-width: 414px !important;
			}
		}

		.button-td,
		.button-a {
			transition: all 100ms ease-in;
		}
		.button-td-primary:hover,
		.button-a-primary:hover {
			background: #555555 !important;
			border-color: #555555 !important;
		}

		@media screen and (max-width: 600px) {
			.email-container p {
				font-size: 17px !important;
			}

		}

		hr {
			border: 0;
			border-bottom: #ccc 1px solid;
			margin: 1em 0;
		}

		table.footer-padding {
			opacity: 0;
			height: 20px;
		}

		table.content {
			box-shadow: rgba(90, 90, 90, 0.18823529411764706) 2px 2px 10px;
		}

		.ezfc-summary-table {
			margin-top: 1em !important;
			width: 100%;
		}

		.footer, .footer a {
			color: #fff !important;
		}
	</style>

</head>

<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background: <?php echo $email_style["background_main"]; ?>; font-family: {{font_family}}">
	<center style="width: 100%; background: <?php echo $email_style["background_main"]; ?>;">
		<div style="max-width: 600px; margin: 0 auto;" class="email-container">
			<!-- header padding -->
			<table class="header-padding" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
				<tr>
					<td style="padding: 10px; font-family: sans-serif; font-size: 10px; line-height: 10px; color: #555555;">
					</td>
				</tr>
			</table>

			<!-- content -->
			<table class="content" align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
				<tr>
					<td style="background-color: <?php echo $email_style["background_content"]; ?>;">
						<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
							<tr>
								<td style="padding: 20px 0; text-align: center; background: <?php echo $email_style["background_header"]; ?>; background-size: cover; background-position: 50% 0%; color: <?php echo $email_style["font_color_header"]; ?>;">
									<?php if (!empty($style["logo"])) { ?>
										<img src="{{logo}}" width="300" height="" alt="" border="0" style="width: 100%; max-width: 300px; height: auto; margin: auto; display: block;" class="g-img" />
									<?php } else { ?>
										<h1 class="header-company">{{company}}</h1>
									<?php } ?>
								</td>
							</tr>
						</table>

						<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
							<tr>
								<td style="padding: 20px; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
									{{custom_text}}
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<!-- footer content -->
				<tr>
					<td class="footer" style="background: <?php echo $email_style["background_footer"]; ?>; background-size: cover; background-position: 50% 100%; color: <?php echo $email_style["font_color_footer"]; ?>; padding: 20px; font-family: sans-serif; font-size: 12px; line-height: 20px; text-align: center;">
						{{text_footer}}
					</td>
				</tr>
			</table>

			<!-- bottom padding -->
			<table class="footer-padding" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
				<tr>
					<td style="padding: 10px;">
					</td>
				</tr>
			</table>
		</div>
	</center>
</body>
</html>