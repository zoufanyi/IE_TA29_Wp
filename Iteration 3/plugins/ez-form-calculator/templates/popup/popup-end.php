<?php
/**
 * Pop up template for popup form end
 *
 * This template can be overridden by copying it to yourtheme/ezfc/popup-end.php
 */

if ( ! defined( "ABSPATH" ) ) {
	exit;
}

global $form;
global $options;

if ( empty( $form ) || empty( $options ) ) {
	return;
}

?>
	</div>
</div>