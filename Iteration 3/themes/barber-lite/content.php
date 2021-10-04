<?php
/**
 * @package Barber Lite
 */
?>
 <div class="StyleforArticle2_BL">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>     
         
		  <?php if( get_theme_mod( 'barber_lite_blogpost_hide_featuredimg' ) == '') { ?> 
			 <?php if (has_post_thumbnail() ){ ?>
                <div class="blgimagebx <?php if( esc_attr( get_theme_mod( 'barber_lite_blogpost_img100' )) ) { ?>imgfull<?php } ?>">
                 <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
                </div>
             <?php } ?> 
          <?php } ?> 
            
        <header class="entry-header">
            <h3><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>                           
            <?php if ( 'post' == get_post_type() ) : ?>
                <div class="blog_postmeta">
                   <?php if( get_theme_mod( 'barber_lite_hidepostdate' ) == '') { ?> 
                      <div class="post-date"> <i class="far fa-clock"></i>  <?php echo esc_html( get_the_date() ); ?></div><!-- post-date --> 
                    <?php } ?> 
                    
                    <?php if( get_theme_mod( 'barber_lite_hidepostcategory' ) == '') { ?> 
                      <span class="blogpost_cat"> <i class="far fa-folder-open"></i> <?php the_category( __( ', ', 'barber-lite' ));?></span>
                   <?php } ?>                                             
                </div><!-- .blog_postmeta -->
            <?php endif; ?>                    
        </header><!-- .entry-header -->          
        <?php if ( is_search() || !is_single() ) : // Only display Excerpts for Search ?>
        <div class="entry-summary">           
         <p>
            <?php $barber_lite_arg = get_theme_mod( 'barber_lite_blogposts_fullcontent','Excerpt');
              if($barber_lite_arg == 'Content'){ ?>
                <?php the_content(); ?>
              <?php }
              if($barber_lite_arg == 'Excerpt'){ ?>
                <?php if(get_the_excerpt()) { ?>
                  <?php $excerpt = get_the_excerpt(); echo esc_html( barber_lite_string_limit_words( $excerpt, esc_attr(get_theme_mod('barber_lite_blogpostexcerpt','30')))); ?>
                <?php }?>
                
                 <?php
					$barber_lite_blogpostmorebutton = get_theme_mod('barber_lite_blogpostmorebutton');
					if( !empty($barber_lite_blogpostmorebutton) ){ ?>
					<a class="morebutton" href="<?php the_permalink(); ?>"><?php echo esc_html($barber_lite_blogpostmorebutton); ?></a>
                <?php } ?> 
                
              <?php }?>
           </p>
                     
           
                    
        </div><!-- .entry-summary -->
        <?php else : ?>
        <div class="entry-content">
            <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'barber-lite' ) ); ?>
            <?php
                wp_link_pages( array(
                    'before' => '<div class="page-links">' . __( 'Pages:', 'barber-lite' ),
                    'after'  => '</div>',
                ) );
            ?>
        </div><!-- .entry-content -->
        <?php endif; ?>
        <div class="clear"></div>
    </article><!-- #post-## -->
</div>