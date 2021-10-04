<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Barber Lite
 */
 
$barber_lite_show_social_hdrftr_section  				= esc_attr( get_theme_mod('barber_lite_show_social_hdrftr_section', false) ); 
 
?>

<div class="SiteFooter-BL">         
    <div class="container FtrHold-BL">     
          <?php if ( is_active_sidebar( 'fw-column-1' ) ) : ?>
                <div class="BL-ColStyle-1">  
                    <?php dynamic_sidebar( 'fw-column-1' ); ?>
                </div>
           <?php endif; ?>
          
          <?php if ( is_active_sidebar( 'fw-column-2' ) ) : ?>
                <div class="BL-ColStyle-2">  
                    <?php dynamic_sidebar( 'fw-column-2' ); ?>
                </div>
           <?php endif; ?>
           
           <?php if ( is_active_sidebar( 'fw-column-3' ) ) : ?>
                <div class="BL-ColStyle-3">  
                    <?php dynamic_sidebar( 'fw-column-3' ); ?>
                </div>
           <?php endif; ?> 
           
           <?php if ( is_active_sidebar( 'fw-column-4' ) ) : ?>
                <div class="BL-ColStyle-4">  
                    <?php dynamic_sidebar( 'fw-column-4' ); ?>
                </div>
           <?php endif; ?>            
          	
           <div class="clear"></div>    
      </div><!--.FtrHold-BL-->

        <div class="copyrigh-wrapper"> 
             <div class="container">             
                <div class="BLCopy_Left">
				   <?php bloginfo('name'); ?> - <?php esc_html_e('Theme by Grace Themes','barber-lite'); ?>  
                </div>
                <div class="BLCopy_Right">
				
                  <?php if( $barber_lite_show_social_hdrftr_section != ''){ ?>                
                    <div class="footericons">                                                
					   <?php $barber_lite_hffb_link = get_theme_mod('barber_lite_hffb_link');
                        if( !empty($barber_lite_hffb_link) ){ ?>
                        <a class="fab fa-facebook-f" target="_blank" href="<?php echo esc_url($barber_lite_hffb_link); ?>"></a>
                       <?php } ?>
                    
                       <?php $barber_lite_hftw_link = get_theme_mod('barber_lite_hftw_link');
                        if( !empty($barber_lite_hftw_link) ){ ?>
                        <a class="fab fa-twitter" target="_blank" href="<?php echo esc_url($barber_lite_hftw_link); ?>"></a>
                       <?php } ?>
                
                      <?php $barber_lite_hfin_link = get_theme_mod('barber_lite_hfin_link');
                        if( !empty($barber_lite_hfin_link) ){ ?>
                        <a class="fab fa-linkedin" target="_blank" href="<?php echo esc_url($barber_lite_hfin_link); ?>"></a>
                      <?php } ?> 
                      
                      <?php $barber_lite_hfigram_link = get_theme_mod('barber_lite_hfigram_link');
                        if( !empty($barber_lite_hfigram_link) ){ ?>
                        <a class="fab fa-instagram" target="_blank" href="<?php echo esc_url($barber_lite_hfigram_link); ?>"></a>
                      <?php } ?> 
                 </div><!--end .topright-30--> 
               <?php } ?>  
                
                </div>
                <div class="clear"></div>                                
             </div><!--end .container-->             
        </div><!--end .copyrigh-wrapper-->  
                             
     </div><!--end #SiteFooter-BL-->
</div><!--#end mainwrap-->
<?php wp_footer(); ?>
</body>
</html>