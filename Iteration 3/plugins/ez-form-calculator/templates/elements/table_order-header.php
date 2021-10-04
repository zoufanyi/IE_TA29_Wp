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

$column_headers = explode("|", Ezfc_Functions::get_object_value($element_data, "table_order_header_text", __("Product|Price per Unit|Quantity|Subtotal", "ezfc")));
$column_headers = array_map("trim", $column_headers);

?>

<thead>
	<tr>

	<?php
	if (is_array($column_headers) && !empty($column_headers)) {
		for ($i = 0; $i < $element_data->__table_columns; $i++) {
			$text = isset($column_headers[$i]) ? $column_headers[$i] : "";

			?>
			<th><?php echo do_shortcode($text); ?></th>
			<?php
		}
	}
	?>

	</tr>
</thead>