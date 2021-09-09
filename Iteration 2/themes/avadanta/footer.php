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

$avadanta_top_footer_enable = get_theme_mod('avadanta_top_footer_enable',0); 

?>	
<?php if($avadanta_top_footer_enable==0)
		{ 
			get_template_part('footer/footer-top'); 
 		} 
			$avadanta_preloader_option = get_theme_mod('avadanta_preloader_option',0);
			 if($avadanta_preloader_option==0){
			?>
		<div class="preloader preloader-light preloader-dalas no-split"><span class="spinner spinner-alt"><img class="spinner-brand" src="<?php echo esc_url(AVADANTA_THEME_URI .'/assets/images/preload.gif') ?>" alt=""></span></div>
	<?php }
			$avadanta_copyright_text = get_theme_mod( 'avadanta_copyright_text');
            $avadanta_copyright_enable = get_theme_mod( 'avadanta_copyright_enable', 0 );
            if($avadanta_copyright_enable==0) :
            ?>
<div class="footer-2 footer-bg-dark tc-light">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-md-6">
                    <div class="copyright text-left my-20">
                         	<?php if( get_theme_mod( 'avadanta_copyright_text' ) ) : ?>
                            <p><?php echo wp_kses_post(  get_theme_mod('avadanta_copyright_text') ); ?> </p>
                            <?php else : ?>
                            <?php
                            printf( __( 'Proudly powered by', 'avadanta' ) );
                            ?>
                            <a href="<?php echo esc_url( __( 'https://wordpress.org/', 'avadanta' ) ); ?>" class="imprint">
                            	
							<?php
                            printf( __( 'WordPress', 'avadanta' ) );
                            ?>
                            </a>
                            
                            
                            <?php endif ; ?> 
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="bottom-nav text-right my-20">
                        <ul><?php
            				$avadanta_bottom_social_url = get_theme_mod( 'avadanta_bottom_social_url', '#' );
            				$avadanta_bottom_twitter_social_url = get_theme_mod( 'avadanta_bottom_twitter_social_url', '#' );
            				$avadanta_bottom_insta_social_url = get_theme_mod( 'avadanta_bottom_insta_social_url', '#' );
            				$avadanta_bottom_linkedin_social_url = get_theme_mod( 'avadanta_bottom_linkedin_social_url', '#' );
            				
            				if(!empty($avadanta_bottom_social_url)){
            				?>
                            <li><a href="<?php echo esc_url($avadanta_bottom_social_url); ?>"><i class="fa fa-facebook"></i></a></li>
                        	<?php } if(!empty($avadanta_bottom_twitter_social_url)){?>
                            <li><a href="<?php echo esc_url($avadanta_bottom_twitter_social_url); ?>"><i class="fa fa-twitter"></i></a></li>
                            <?php } if(!empty($avadanta_bottom_insta_social_url)){?>
                            <li><a href="<?php echo esc_url($avadanta_bottom_insta_social_url); ?>"><i class="fa fa-instagram"></i></a></li>
                            <?php } if(!empty($avadanta_bottom_linkedin_social_url)){?>
                            <li><a href="<?php echo esc_url($avadanta_bottom_linkedin_social_url); ?>"><i class="fa fa-linkedin"></i></a></li>
                        	<?php } ?>
                        </ul>
                    </div>
                </div>
			</div> 
		</div> 
	</div><?php
			$avadanta_scroll_thumb = get_theme_mod('avadanta_scroll_thumb',0);
			 if($avadanta_scroll_thumb==0){
			?>
			<a href="#" class="back-to-top"><i class="fa fa-arrow-up"></i></a>
			<?php } endif; ?>

<?php wp_footer();?>	
</body>
</html>