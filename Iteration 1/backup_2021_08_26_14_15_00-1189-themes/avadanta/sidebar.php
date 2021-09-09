<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package avadanta
 */

if ( ! is_active_sidebar( 'main-sidebar' ) ) {
	return;
}
?>
<div class="col-md-4 pl-lg-4">
	<div class="sidebar">
		<?php dynamic_sidebar( 'main-sidebar' ); ?>
	</div>
</div>