<?php

if ( ! defined( "ABSPATH" ) ) {
	exit;
}

$message = isset($message) ? $message : "";
$error   = isset($error)   ? $error : "";

?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="inner ezfc-bg">
			<div class="col-xs-8">
				<?php echo "<h2 class='heading-title'>{$title} - ez Form Calculator v" . EZFC_VERSION . "</h2>"; ?>
			</div>

			<div class="col-xs-4 text-right ezfc-button-bar-top ezfc-header-nav">
				<a href="https://ez-form-calculator.ezplugins.de/documentation/" target="_blank"><?php echo __("Documentation", "ezfc"); ?></a> |
				<a href="https://ez-form-calculator.ezplugins.de/faq/" target="_blank"><?php echo __("FAQ", "ezfc"); ?></a> |
				<a href="https://www.youtube.com/playlist?list=PLNr8RA28O1QRj4VU7YwTrLbyRmzJ7uin9" target="_blank"><?php echo __("Videos", "ezfc"); ?></a> |
				<a href="https://ez-form-calculator.ezplugins.de/custom-form-order/" target="_blank"><?php echo __("Custom forms", "ezfc"); ?></a> |
				<a href="https://ez-form-calculator.ezplugins.de/wordpress-extensions-order/" target="_blank"><?php echo __("Extensions", "ezfc"); ?></a>
			</div>

			<div class="col-xs-12 ezfc-message-wrapper">
				<div id="message" class="ezfc-message updated"><?php echo $message; ?></div>
				<div id="ezfc-error" class="ezfc-error notice notice-error"><?php echo $error; ?></div>
			</div>

			<div class="clearfix"></div>
		</div>
	</div>
</div>