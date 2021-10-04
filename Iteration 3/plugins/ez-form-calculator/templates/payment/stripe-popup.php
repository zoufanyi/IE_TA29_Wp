<?php
/**
 * Pop up template for stripe payments
 *
 * This template can be overridden by copying it to yourtheme/ezfc/stripe-popup.php.
 */

if ( ! defined( "ABSPATH" ) ) {
	exit;
}

global $form;
global $options;

if ( empty( $form ) || empty( $options ) ) {
	return;
}

$submit_button_text = sprintf(esc_html($options["stripe_label_submit"]), "<span class='ezfc-payment-price'></span>");

?>

<form action="" class="ezfc-payment-form ezfc-payment-dialog" method="POST" id="ezfc-stripe-form-<?php echo $form->id; ?>" data-form_id="<?php echo $form->id; ?>">
	<div class="ezfc-payment-content">
		<h3 class="ezfc-payment-heading"><?php echo esc_html($options["stripe_heading"]); ?></h3>

		<div class="ezfc-payment-description-text"><?php echo $options["stripe_description"]; ?></div>

		<div class="ezfc-form-input-group">
			<?php if (!empty($options["stripe_show_card_holder_name"])) { ?>
				<div class="ezfc-form-input-row">
					<div class="ezfc-half">
						<label class="ezfc-label" for="ezfc-element-payment-stripe-card-holder-name-<?php echo $form->id; ?>"><?php echo esc_html($options["stripe_label_card_holder_name"]); ?></label>
					</div>
					<div class="ezfc-half">
						<input id="ezfc-element-payment-stripe-card-holder-name-<?php echo $form->id; ?>" class="ezfc-input" type="text" size="20" data-stripe="name" value="" />
					</div>
				</div>

				<div class="ezfc-clear"></div>
			<?php } ?>

			<div class="ezfc-form-input-row">
				<div class="ezfc-half">
					<label class="ezfc-label" for="ezfc-element-payment-stripe-card-number-<?php echo $form->id; ?>"><?php echo esc_html($options["stripe_label_card_number"]); ?></label>
				</div>
				<div class="ezfc-half">
					<input id="ezfc-element-payment-stripe-card-number-<?php echo $form->id; ?>" class="ezfc-cc-number-formatter" type="text" size="20" maxlength="19" data-stripe="number" value="" />
				</div>
			</div>

			<div class="ezfc-clear"></div>

			<div class="ezfc-form-input-row">
				<div class="ezfc-half">
					<label class="ezfc-label" for="ezfc-element-payment-stripe-expiry-month-<?php echo $form->id; ?>"><?php echo esc_html($options["stripe_label_expiration_date"]); ?></label>
				</div>
				<div class="ezfc-half">
					<input id="ezfc-element-payment-stripe-expiry-month-<?php echo $form->id; ?>" class="ezfc-input-small" type="text" size="2" maxlength="2" data-stripe="exp_month" value="" />
					<span class="ezfc-element-payment-expiry-separator"> / </span>
					<input id="ezfc-element-payment-stripe-expiry-year-<?php echo $form->id; ?>" class="ezfc-input-small" type="text" size="2" maxlength="2" data-stripe="exp_year" value="" />
				</div>
			</div>

			<div class="ezfc-form-input-row">
				<div class="ezfc-half">
					<label class="ezfc-label" for="ezfc-element-payment-stripe-cvc-<?php echo $form->id; ?>"><?php echo esc_html($options["stripe_label_cvc"]); ?></label>
				</div>
				<div class="ezfc-half">
					<input id="ezfc-element-payment-stripe-cvc-<?php echo $form->id; ?>" class="ezfc-input-small" type="text" size="4" maxlength="4" data-stripe="cvc" value="" />
				</div>
			</div>

			<div class="ezfc-clear"></div>
		</div>

		<div class="ezfc-payment-errors" id="ezfc-payment-message-<?php echo $form->id; ?>"></div>

		<div class="ezfc-form-button-group">
			<button class="ezfc-btn ezfc-payment-cancel"><i class="fa fa-times"></i> <?php echo esc_html($options["stripe_label_cancel"]); ?></button>
			<button class="ezfc-btn ezfc-payment-submit" data-payment="stripe"><i class="fa fa-credit-card"></i> <?php echo $submit_button_text ?> <span class="ezfc-submit-icon"><i class="<?php echo esc_attr(get_option("ezfc_loading_icon", "fa fa-cog fa-spin")); ?>"></i></span></button>
		</div>
	</div>
</form>