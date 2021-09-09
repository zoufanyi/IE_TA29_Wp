<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div class="container">
 *
 * @package Barber Lite
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>">
<?php endif; ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	} else {
		do_action( 'wp_body_open' );
	}
?>
<a class="skip-link screen-reader-text" href="#TabNav-BL">
<?php esc_html_e( 'Skip to content', 'barber-lite' ); ?>
</a>
<?php
$barber_lite_show_hdrinfobar 	   	= esc_attr( get_theme_mod('barber_lite_show_hdrinfobar', false) ); 
$barber_lite_show_social_hdrftr_section  				= esc_attr( get_theme_mod('barber_lite_show_social_hdrftr_section', false) ); 
$barber_lite_show_slidersettings_sections 	      			= esc_attr( get_theme_mod('barber_lite_show_slidersettings_sections', false) );
$barber_lite_show_fourpagebox_settings       = esc_attr( get_theme_mod('barber_lite_show_fourpagebox_settings', false) );
?>
<div id="mainwrap" <?php if( get_theme_mod( 'barber_lite_layouttype' ) ) { echo 'class="boxlayout"'; } ?>>
<?php
if ( is_front_page() && !is_home() ) {
	if( !empty($barber_lite_show_slidersettings_sections)) {
	 	$innerpage_cls = '';
	}
	else {
		$innerpage_cls = 'innerpage_header';
	}
}
else {
$innerpage_cls = 'innerpage_header';
}
?>

<div class="site-header <?php echo esc_attr($innerpage_cls); ?> "> 
   <div class="container">  
      <div class="topstrip">
        <?php if( $barber_lite_show_hdrinfobar != ''){ ?>         
          <div class="topleft-70">            
          <?php $barber_lite_hdrphone = get_theme_mod('barber_lite_hdrphone');
               if( !empty($barber_lite_hdrphone) ){ ?>              
                 <div class="infobox">
                     <i class="fas fa-phone-volume"></i>               
                     <span><?php echo esc_html($barber_lite_hdrphone); ?></span>   
                 </div>       
         <?php } ?>          
         
         <?php 
            $email = get_theme_mod('barber_lite_hdrmail');
               if( !empty($email) ){ ?>                
                 <div class="infobox">
                     <i class="fas fa-envelope-open-text"></i>
                     <span>
                        <a href="<?php echo esc_url('mailto:'.sanitize_email($email)); ?>"><?php echo sanitize_email($email); ?></a>
                    </span> 
                </div>            
         <?php } ?> 
         </div><!--end .topleft-70-->
      <?php } ?>    
           
            
         <?php if( $barber_lite_show_social_hdrftr_section != ''){ ?>                
              <div class="topright-30">                                                
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
               <div class="clear"></div>
         </div><!-- .container -->
    </div><!-- .topstrip -->  

          
  <div class="logo_and_navibar">    
       <div class="container">      
        <div class="logo">
           <?php barber_lite_the_custom_logo(); ?>
            <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
            <?php $description = get_bloginfo( 'description', 'display' );
            if ( $description || is_customize_preview() ) : ?>
                <p><?php echo esc_html($description); ?></p>
            <?php endif; ?>
        </div><!-- logo --> 
        <div class="menuarea_hdr">      
         <div id="navigationpanel">       
		     <button class="menu-toggle" aria-controls="main-navigation" aria-expanded="false" type="button">
			    <span aria-hidden="true"><?php esc_html_e( 'Menu', 'barber-lite' ); ?></span>
			    <span class="dashicons" aria-hidden="true"></span>
		     </button>
		    <nav id="main-navigation" class="SiteMenu-BL primary-navigation" role="navigation">
				<?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'container' => 'ul',
                    'menu_id' => 'primary',
                    'menu_class' => 'primary-menu menu',
                ) );
                ?>
		     </nav><!-- .SiteMenu-BL -->
	       </div><!-- #navigationpanel -->     
         </div><!-- .menuarea_hdr -->     
        <div class="clear"></div>
     </div><!-- .container -->   
 </div><!-- .logo_and_navibar -->  
</div><!--.site-header --> 
 
<?php 
if ( is_front_page() && !is_home() ) {
if($barber_lite_show_slidersettings_sections != '') {
	for($i=1; $i<=3; $i++) {
	  if( get_theme_mod('barber_lite_pageforslider'.$i,false)) {
		$slider_Arr[] = absint( get_theme_mod('barber_lite_pageforslider'.$i,true));
	  }
	}
?> 
<div class="FrontPageSlider">              
<?php if(!empty($slider_Arr)){ ?>
<div id="slider" class="nivoSlider">
<?php 
$i=1;
$slidequery = new WP_Query( array( 'post_type' => 'page', 'post__in' => $slider_Arr, 'orderby' => 'post__in' ) );
while( $slidequery->have_posts() ) : $slidequery->the_post();
$image = wp_get_attachment_url( get_post_thumbnail_id($post->ID)); 
$thumbnail_id = get_post_thumbnail_id( $post->ID );
$alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true); 
?>
<?php if(!empty($image)){ ?>
<img src="<?php echo esc_url( $image ); ?>" title="#slidecaption<?php echo esc_attr( $i ); ?>" alt="<?php echo esc_attr($alt); ?>" />
<?php }else{ ?>
<img src="<?php echo esc_url( get_template_directory_uri() ) ; ?>/images/slides/slider-default.jpg" title="#slidecaption<?php echo esc_attr( $i ); ?>" alt="<?php echo esc_attr($alt); ?>" />
<?php } ?>
<?php $i++; endwhile; ?>
</div>   

<?php 
$j=1;
$slidequery->rewind_posts();
while( $slidequery->have_posts() ) : $slidequery->the_post(); ?>                 
    <div id="slidecaption<?php echo esc_attr( $j ); ?>" class="nivo-html-caption">         
    	<h2><?php the_title(); ?></h2>
    	<p><?php $excerpt = get_the_excerpt(); echo esc_html( barber_lite_string_limit_words( $excerpt, esc_attr(get_theme_mod('barber_lite_slider_excerptlength','15')))); ?></p>
		<?php
        $barber_lite_pageforsliderbutton = get_theme_mod('barber_lite_pageforsliderbutton');
        if( !empty($barber_lite_pageforsliderbutton) ){ ?>
            <a class="buttonforslider" href="<?php the_permalink(); ?>"><?php echo esc_html($barber_lite_pageforsliderbutton); ?></a>
        <?php } ?>                  
    </div>   
<?php $j++; 
endwhile;
wp_reset_postdata(); ?>   
<?php } ?>
 </div><!-- .FrontPageSlider -->    
<?php } } ?>   
        
<?php if ( is_front_page() && ! is_home() ) { ?>
   <?php if( $barber_lite_show_fourpagebox_settings != ''){ ?> 
   <section id="BL-Sectionfirst">
     <div class="container">       
			<?php
            $barber_lite_sectiontitle = get_theme_mod('barber_lite_sectiontitle');
            if( !empty($barber_lite_sectiontitle) ){ ?>
                <h2 class="section_title"><?php echo esc_html($barber_lite_sectiontitle); ?></h2>
             <?php } ?>            
          <?php 
                for($n=1; $n<=4; $n++) {    
                if( get_theme_mod('barber_lite_4boxpageno'.$n,false)) {      
                    $queryvar = new WP_Query('page_id='.absint(get_theme_mod('barber_lite_4boxpageno'.$n,true)) );		
                    while( $queryvar->have_posts() ) : $queryvar->the_post(); ?>     
                     <div class="FourCol-BL <?php if($n % 4 == 0) { echo "last_column"; } ?>">                                                                   
							 <?php if(has_post_thumbnail() ) { ?>
                                <div class="ImgBX_BL">
                                  <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>                                
                                </div>        
                             <?php } ?>
                             <div class="DescBX-BL">              	
                                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4> 
                                <p><?php $excerpt = get_the_excerpt(); echo esc_html( barber_lite_string_limit_words( $excerpt, esc_attr(get_theme_mod('barber_lite_4box_excerptlength','15')))); ?></p> 
								<?php
                                    $barber_lite_4boxmorebtn = get_theme_mod('barber_lite_4boxmorebtn');
                                    if( !empty($barber_lite_4boxmorebtn) ){ ?>
                                    <a class="pagemore" href="<?php the_permalink(); ?>"><?php echo esc_html($barber_lite_4boxmorebtn); ?></a>
                                <?php } ?>  
                             </div>                                                      
                     </div>
                    <?php endwhile;
                    wp_reset_postdata();                                  
                } } ?>                                 
               <div class="clear"></div> 
       
      </div><!-- .container -->
    </section><!-- #BL-Sectionfirst -->
  <?php } ?>
<?php } ?>