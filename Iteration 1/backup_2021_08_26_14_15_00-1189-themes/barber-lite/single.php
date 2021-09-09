<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Barber Lite
 */

get_header(); ?>

<div class="container">
     <div id="TabNav-BL">
        <div class="SiteContent-BL <?php if( esc_attr( get_theme_mod( 'barber_lite_hidesidebar_singlepost' )) ) { ?>fullwidth<?php } ?>">           
				<?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'content', 'single' ); ?>
                    <?php the_post_navigation(); ?>
                    <div class="clear"></div>
                    <?php
                    // If comments are open or we have at least one comment, load up the comment template
                    if ( comments_open() || '0' != get_comments_number() )
                    	comments_template();
                    ?>
                <?php endwhile; // end of the loop. ?>                  
          </div><!-- .SiteContent-BL-->        
          <?php if( esc_attr(get_theme_mod( 'barber_lite_hidesidebar_singlepost' )) == '') { ?> 
          	  <?php get_sidebar();?>
          <?php } ?>       
        <div class="clear"></div>
    </div><!-- #TabNav-BL -->
</div><!-- container -->	
<?php get_footer(); ?>