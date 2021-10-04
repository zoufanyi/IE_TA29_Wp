<?php
/**
 * Template for the Subtotal element
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

<input class='<?php echo $element_data->class; ?> ezfc-element ezfc-element-input ezfc-element-subtotal ezfc-input-format-listener' id='<?php echo $output["element_child_id"]; ?>' type='text' name='<?php echo $output["element_name"]; ?>' value='' data-settings='<?php echo $element_vars["data_settings"]; ?>' <?php echo $element_vars["add_attr"]; echo $output["style"]; ?> />