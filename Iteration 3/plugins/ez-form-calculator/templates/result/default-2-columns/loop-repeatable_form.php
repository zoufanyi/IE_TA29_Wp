<?php

/**
 * Result template
 *
 * This template can be overridden by copying it to yourtheme/ezfc/result/<themename>/<filename>.php
 */

if ( ! defined( "ABSPATH" ) ) {
	exit;
}

global $loop_data;

$background_color = $loop_data["even"] ? "#fff" : "#eee";

?>

<?php echo $loop_data["text_column_1"]; ?>