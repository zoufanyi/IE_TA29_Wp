<?php
/**
 * The template for displaying the footer
 *
 * Contains the opening of the #site-footer div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Avadanta
 */
$footer_background_image = get_theme_mod('footer_background_image','');

?>

	<footer class="section footer bg-dark-alt tc-light footer-s1">
		<div class="container">
			<div class="row gutter-vr-30px">
					<?php
			           $avadanta_footer_widgets_column = get_theme_mod( 'avadanta_footer_widgets_column', 'mt-column-3' );
			            if( is_active_sidebar( 'avadanta-footer-area' ) )
			          {
			           
			            echo '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 margin-b-30 '.esc_attr( $avadanta_footer_widgets_column ).'">';
			            dynamic_sidebar( 'avadanta-footer-area' );
			            echo '</div>';
			            }
			        ?>

			        <?php

			            if( is_active_sidebar( 'avadanta-footer-area-2' ) || $avadanta_footer_widgets_column == 'mt-column-1'){
			           
			          
			            echo '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 margin-b-30 '.esc_attr( $avadanta_footer_widgets_column ).'">';
			            dynamic_sidebar( 'avadanta-footer-area-2' );
			            echo '</div>';
			             }
			        ?>
			 
			        <?php
			        if( is_active_sidebar( 'avadanta-footer-area-3' ) || $avadanta_footer_widgets_column == 'mt-column-1' || $avadanta_footer_widgets_column == 'mt-column-2' ){
			          
			       
			            echo '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 margin-b-30 '.esc_attr( $avadanta_footer_widgets_column ).'">';
			            dynamic_sidebar( 'avadanta-footer-area-3' );
			            echo '</div>';
			             }
			    	?>

			        <?php
			        if( is_active_sidebar( 'avadanta-footer-area-4' ) || $avadanta_footer_widgets_column != 'mt-column-4'){
			            						      
			            echo '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 margin-b-30 '.esc_attr( $avadanta_footer_widgets_column ).'">';
			            dynamic_sidebar( 'avadanta-footer-area-4' );
			            echo '</div>';
			            }
			    	?>
			</div> 
		</div> 
		<div class="bg-image bg-fixed">
			<img src="<?php echo esc_url($footer_background_image);?>" alt="">
		</div>
	</footer>