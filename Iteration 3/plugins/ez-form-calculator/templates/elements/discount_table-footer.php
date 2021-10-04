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
global $element_vars;
global $form;
global $options;
global $output;

if ( empty( $form ) || empty( $options ) ) {
	return;
}

$frontend = Ezfc_frontend::instance();
?>

	</tbody>
</table>