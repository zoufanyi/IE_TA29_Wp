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
		<th style="padding: 5px; vertical-align: top;"><?php echo $summary_data["columns"][0]; ?></th>
		<th style="padding: 5px; vertical-align: top;"><?php echo $summary_data["columns"][1]; ?></th>
	</thead>

	<tbody>