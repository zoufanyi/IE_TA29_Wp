<?php
/**
 * Pop up template for popup button
 *
 * This template can be overridden by copying it to yourtheme/ezfc/button.php
 */

if ( ! defined( "ABSPATH" ) ) {
	exit;
}

global $form;
global $options;

if ( empty( $form ) || empty( $options ) ) {
	return;
}

$button_text = Ezfc_Functions::get_array_value($options, "popup_button_text", __("Open form", "ezfc"));

?>
<div class="ezfc-form-open-popup-wrapper">
	<button class="ezfc-form-open-popup-button" data-target="<?php echo $form->id; ?>" title="<?php echo __("Close dialog", "ezfc"); ?>"><?php echo $button_text; ?></button>
</div>