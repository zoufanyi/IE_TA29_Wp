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
	<td style="padding: 5px; vertical-align: top; background-color: <?php echo $background_color; ?>;"><?php echo $loop_data["label"]; ?></td>
	<td style="padding: 5px; vertical-align: top; background-color: <?php echo $background_color; ?>;"><?php echo $loop_data["text_column_1"]; ?></td>
</tr>