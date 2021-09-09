<?php
$avadanta_single_post_thumb =  get_theme_mod( 'avadanta_single_post_thumb', 1 );
$avadanta_single_post_meta =  get_theme_mod( 'avadanta_single_post_meta', 1 );
$avadanta_single_post_title = get_theme_mod( 'avadanta_single_post_title', 1 ); 
?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<div class="post post-full post-details">

						<?php if( $avadanta_single_post_thumb == 1 ) { 
						 if(has_post_thumbnail()){ ?>
							<div class="post-thumb">
								<?php the_post_thumbnail(); ?>
							</div>
						<?php } } ?>
							<div class="post-entry d-sm-flex d-block align-items-start">
								<div class="content-left d-flex d-sm-block"></div>
								<div class="post-content post-content-wd">
									<?php  if( $avadanta_single_post_meta == 1 ) { ?>
									<div class="post-meta d-block d-lg-flex align-items-center">
										<div class="post-author d-flex align-items-center flex-shrink-0 align-self-start">
											<div class="author-thumb">
												<?php echo get_avatar( get_the_author_meta('user_email'), $size = '50'); ?>
											</div>
											<div class="author-name">
												<?php avadanta_posted_by(); ?>
											</div>
										</div>
										<div class="post-tag d-flex comment">
											<?php avadanta_get_comments_numbers(); ?>
										</div>
										<div class="post-tag d-flex date">
											<?php avadanta_posted_on(); ?>
										</div>
									</div>
									<?php } if( $avadanta_single_post_title == 1 ) {?>
									<h3><?php the_title(); ?></h3>
									<?php } ?>
									<div class="content">
										<?php 
							                the_content( sprintf(
							                  wp_kses(
							                    /* translators: %s: Name of current post. Only visible to screen readers */
							                    __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'avadanta' ),
							                    array(
							                          'span' => array(
							                            'class' => array(),
							                          ),
							                        )
							                      ),
							                          get_the_title()
							                        ) );

							                        wp_link_pages( array(
							                          'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'avadanta' ),
							                          'after'  => '</ div>',
							                        ) );
							                    ?>
									</div>
								</div>
							</div>
						</div><!-- .post -->
					</div>
<?php 

	if ( comments_open() || get_comments_number() ) :
	    comments_template();
	endif; 
?> 