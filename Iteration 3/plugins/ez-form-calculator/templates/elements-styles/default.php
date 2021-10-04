<?php
/**
 * Template for the default element
 *
 * This template can be overridden by copying it to yourtheme/ezfc/elements/<filename>.php
 */

if ( ! defined( "ABSPATH" ) ) {
	exit;
}

global $element;
global $form;
global $options;

if ( empty( $form ) || empty( $options ) ) {
	return;
}

?>

{{label}}
{{element_content}}