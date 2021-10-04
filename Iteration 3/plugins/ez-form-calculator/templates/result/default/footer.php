<?php

/**
 * Result template
 *
 * This template can be overridden by copying it to yourtheme/ezfc/result/<themename>/<filename>.php
 */

if ( ! defined( "ABSPATH" ) ) {
	exit;
}

global $summary_data;

?>
	</tbody>
	<tfoot>
		<?php

		if (Ezfc_Functions::get_array_value($summary_data["form_options"], "email_show_total_price", 1) == 1 && Ezfc_Functions::get_array_value($summary_data["form_options"], "hide_summary_price", 0) == 0) {

			$total_text         = Ezfc_Functions::get_array_value($summary_data["form_options"], "email_total_price_text", __("Total", "ezfc"));
			$summary_bg_color   = Ezfc_Functions::get_array_value($summary_data["form_options"]["css_summary_total_background"], "color", "#eee");
			$summary_text_color = Ezfc_Functions::get_array_value($summary_data["form_options"]["css_summary_total_color"], "color", "#000");

			?>
			<!-- total -->
			<tr style="font-weight: bold;">
				<td align="left" style="width: 40%; background-color: <?php echo $summary_bg_color; ?>; font-weight: bold; padding: 5px; vertical-align: top;"><?php echo __("Total", "ezfc"); ?></td>
				<td align="left" style="width: 40%;"></td>
				<td align="right" style="width: 20%; background-color: <?php echo $summary_text_color; ?>; font-weight: bold; padding: 5px; vertical-align: top; text-align: right;"><?php echo $summary_data["total_formatted"]; ?></td>
			</tr>
		<?php
		}
		?>
	</tfoot>
</table>