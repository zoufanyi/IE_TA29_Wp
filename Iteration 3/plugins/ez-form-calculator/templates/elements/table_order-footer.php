<?php
/**
 * Template for table order loop item
 *
 * This template can be overridden by copying it to yourtheme/ezfc/elements/<filename>.php
 */

if ( ! defined( "ABSPATH" ) ) {
	exit;
}

global $element;
global $element_data;
global $form;
global $options;

if ( empty( $form ) || empty( $options ) ) {
	return;
}

// calculate colspan for first column
$footer_price_id = "ezfc_element-{$element->id}-footer-price";

?>

<tfoot>
	<tr class="ezfc-element-table_order-footer">
		<th colspan="<?php echo $element_data->__table_columns - 1; ?>"><?php echo do_shortcode(Ezfc_Functions::get_object_value($element_data, "table_order_footer_text", __("Price", "ezfc"))); ?></th>
		<th class="ezfc-element-table_order-footer-price" id="<?php echo $footer_price_id; ?>">€123</th>
	</tr>
</tfoot>