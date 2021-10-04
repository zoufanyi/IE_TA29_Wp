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
$row_class = $element_vars["disabled"] ? "ezfc-table_order-row-disabled" : "";

?>

<tr class="ezfc-element-table_order-row <?php echo $row_class; ?>" data-row="<?php echo $element_vars["index"]; ?>">
	<?php if ($element_vars["has_image"]) { ?>
		<td class="ezfc-element-table_order-image-column">

		<?php if (!empty($element_vars["option"]->image)) { ?>
			<img class="ezfc-element-table_order-image" src="<?php echo esc_attr($element_vars["option"]->image); ?>" alt="<?php echo esc_attr($element_vars["option"]->text); ?>" />
		<?php } ?>

		</td>
	<?php } ?>

	<td class="ezfc-element-table_order-name">
		<?php
		$text = $element_vars["option"]->text;

		if ($element_vars["do_shortcode"] == 1) $text = do_shortcode(htmlspecialchars_decode($text));

		echo $text;
		?>
	</td>

	<?php if (Ezfc_Functions::get_object_value($element_data, "show_item_price", 1) == 1) { ?>
		<td class="ezfc-element-table_order-price" id="<?php echo $element_vars["price_column_id"]; ?>"><?php echo $element_vars["item_price"]; ?></td>
	<?php } ?>

	<td class="ezfc-element-table_order-quantity">

	<button class="ezfc-table_order-btn ezfc-table_order-dec-btn" data-target="<?php echo $element_vars["input_id"]; ?>" data-value="-<?php echo $element_data->steps_spinner; ?>"><i class="fa fa-minus"></i></button>

	<input id="<?php echo $element_vars["input_id"]; ?>" class="ezfc-element-table_order-quantity-input" type="text" value="<?php echo $element_vars["input_value"]; ?>" name="<?php echo $output["element_name"] . "[" . $element_vars["index"] . "]"; ?>" <?php echo $element_vars["add_data"]; ?> data-min="<?php echo $element_vars["min"]; ?>" data-max="<?php echo $element_vars["max"]; ?>" />

	<button class="ezfc-table_order-btn ezfc-table_order-add-btn" data-target="<?php echo $element_vars["input_id"]; ?>" data-value="<?php echo $element_data->steps_spinner; ?>"><i class="fa fa-plus"></i></button>

	</td>

	<?php if (Ezfc_Functions::get_object_value($element_data, "show_subtotal_column", 1) == 1) { ?>
		<td class="ezfc-element-table_order-subtotal" id="<?php echo $element_vars["subtotal_id"]; ?>">0</td>
	<?php } ?>
</tr>