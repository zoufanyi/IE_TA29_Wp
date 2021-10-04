<?php
/**
 * Template for the Repeatable Form element
 *
 * This template can be overridden by copying it to yourtheme/ezfc/elements/<filename>.php
 */

if ( ! defined( "ABSPATH" ) ) {
	exit;
}

global $element_data;
global $element_vars;
global $form;
global $options;
global $output;

if ( empty( $form ) || empty( $options ) ) {
	return;
}

?>

<div class="ezfc-repeatable-wrapper" id="ezfc-repeatable-wrapper-<?php echo $element_vars["id"]; ?>">
	<div class="ezfc-repeatable-form-elements">
		<?php echo $element_vars["content"]; ?>
	</div>

	<?php if ($element_vars["repeatable"] == 1) { ?>
		<div class="ezfc-repeatable-form-actions">
			<button class="ezfc-btn ezfc-repeatable-form-repeat-button" data-id="<?php echo $element_vars["id"]; ?>"><i class="fa fa-plus-circle"></i> <?php echo $element_vars["repeat_button_text"]; ?></button>
			<button class="ezfc-btn ezfc-repeatable-form-remove-button" data-id="<?php echo $element_vars["id"]; ?>"><i class="fa fa-minus-circle"></i> <?php echo $element_vars["remove_button_text"]; ?></button>
		</div>
	<?php } ?>
</div>