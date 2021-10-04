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
<tr>
	<td style="width: 40%; padding: 5px; vertical-align: top; background-color: <?php echo $background_color; ?>;"><?php echo $loop_data["label"]; ?></td>
	<td style="width: 40%; padding: 5px; vertical-align: top; background-color: <?php echo $background_color; ?>;"><?php echo $loop_data["text_column_1"]; ?></td>
	<td style="width: 20%; padding: 5px; vertical-align: top; background-color: <?php echo $background_color; ?>; text-align: right;"><?php echo $loop_data["text_column_2"]; ?></td>
</tr>