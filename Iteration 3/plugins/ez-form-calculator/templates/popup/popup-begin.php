<?php
/**
 * Pop up template for popup form begin
 *
 * This template can be overridden by copying it to yourtheme/ezfc/popup-begin.php
 */

if ( ! defined( "ABSPATH" ) ) {
	exit;
}

global $form;
global $options;

if ( empty( $form ) || empty( $options ) ) {
	return;
}

?>
<div class="ezfc-form-popup" id="ezfc-form-popup-<?php echo $form->id; ?>">
	<div class="ezfc-form-close-wrapper">
		<button class="ezfc-form-popup-close" data-target="<?php echo $form->id; ?>"><i class="fa fa-times"></i></button>
	</div>

	<div class="ezfc-form-popup-content">