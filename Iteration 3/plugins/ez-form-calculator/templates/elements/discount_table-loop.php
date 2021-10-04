<?php
/**
 * Template for table order loop item
 *
 * This template can be overridden by copying it to yourtheme/ezfc/elements/<filename>.php
 */

if ( ! defined( "ABSPATH" ) ) {
	exit;
}

global $element_data;
global $form;
global $item_row;
global $options;
global $output;

if ( empty( $form ) || empty( $options ) ) {
	return;
}

$frontend = Ezfc_frontend::instance();
?>

	<tr>
		<td><?php echo $item_row->range_min; ?></td>
		<td><?php echo $item_row->range_max; ?></td>
		<td><?php echo $item_row->__formatted_value; ?></td>
	</tr>