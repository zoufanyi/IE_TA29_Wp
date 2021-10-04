<?php

/**
 * Result template
 *
 * This template can be overridden by copying it to yourtheme/ezfc/result/<themename>/<filename>.php
 */

if ( ! defined( "ABSPATH" ) ) {
	exit;
}

$theme_name = "default";

global $summary_data;

?>
<table class="ezfc-summary-table ezfc-summary-table-<?php echo $theme_name; ?>">
	<thead>
		<th align="left" style="width: 40%; padding: 5px; vertical-align: top; text-align: left;"><?php echo $summary_data["columns"][0]; ?></th>
		<th align="left" style="width: 40%; padding: 5px; vertical-align: top; text-align: left;"><?php echo $summary_data["columns"][1]; ?></th>
		<th align="right" style="width: 20%; padding: 5px; vertical-align: top; text-align: right;"><?php echo $summary_data["columns"][2]; ?></th>
	</thead>

	<tbody>