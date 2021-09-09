<?php
/**
 * Blog Section
 */
?>

<?php if ( get_theme_mod( 'avante_blog_section_toggle' ) == '' ) : ?>

<section id="blog" class="blog position-relative py-7">

    <?php if ( get_theme_mod( 'avante_blog_pattern_toggle' ) == '' ) : ?>
        <div class="texture-left"></div>
        <div class="texture-right"></div>
    <?php endif; ?>

	<div class="container">

        <div class="row">

			<div class="col-md-12 text-center">

                <?php if ( get_theme_mod( 'avante_onepage_blog_title' ) ) : ?>
                    <h2 class="section-title"><?php echo esc_html(get_theme_mod( 'avante_onepage_blog_title', __( 'Our Blog', 'avante-lite' ) ) ); ?></h2>
                <?php endif; ?>

                <?php if ( get_theme_mod( 'avante_onepage_blog_subtitle' ) ) : ?>
                    <?php if ( get_theme_mod( 'avante_blog_section_title_divider_toggle' ) == '' ) : ?>
                      <span class="section-title-divider mx-auto mt-3 mb-3"></span>
                    <?php endif; ?>
                    <p class="lead text-info w-75 mx-auto"><?php echo esc_html(get_theme_mod( 'avante_onepage_blog_subtitle', __( 'Sed fermentum, felis ut cursus varius, purus velit placerat tortor, at faucibus elit purus posuere velit. Integer sit amet felis ligula.', 'avante-lite' ) ) ); ?></p>
                <?php endif; ?>
            
            </div>

        </div>

        <div class="content row multi-columns-row mt-5">

            <?php

                $args = array( 'numberposts' => esc_attr( get_theme_mod( 'avante_onepage_blog_posts', '3' ) ), 'date'=> 'DSC', 'post_status' => 'publish' );

                $postslist = get_posts( $args );

                foreach ($postslist as $post) :  setup_postdata($post); ?> 

                    <div class="<?php echo esc_attr(get_theme_mod( 'avante_onepage_blog_layout', 'col-sm-12 col-md-6 col-lg-4' ) ); ?> post mb-5">

                        <div class="card rounded shadow">

                            <article id="post-<?php the_ID(); ?>">
                
                                <?php if(get_the_post_thumbnail()) : ?>

                                    <figure class="post-image position-relative mb-0">

                                        <a href="<?php the_permalink() ?>"><?php the_post_thumbnail('avante-home-post-thumbnails', array('class'=>'img-fluid rounded')); ?></a>

                                        <span class="d-inline-block position-absolute px-3 py-2 text-light text-uppercase mx-3 mb-3 badge badge-pill badge-primary"><?php the_category(', '); ?></span>

                                    </figure>

                                <?php endif; ?>
                                
                                <div class="clearfix"></div>

                                <div class="card-body">
                                    
                                    <h5 class="entry-title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h5>

                                    <p class="excerpt text-info text-ellipsis"><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
                                    
                                    <p class="meta text-info mb-0">
                                        
                                        <?php echo get_avatar( get_the_author_meta( 'ID' ), 28, null, null, array('class'=>'rounded-circle mr-1') ); ?>

                                        <?php printf(
                                            esc_html__( '%1$s on %2$s', 'avante-lite' ),
                                            '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>',
                                            get_the_time( get_option( 'date_format' ) )
                                        ); ?>

                                        <span class="float-right">
                                            
                                            <i class="far fa-comment"></i>
                                            
                                            <?php printf(
                                                _n( '%1$s', '%1$s', get_comments_number(), 'avante-lite' ),
                                                number_format_i18n( get_comments_number() )
                                            ); ?>

                                        </span>
                                    
                                    </p>
                                    
                                    <div class="clearfix"></div>

                                </div>
                             
                            </article>

                        </div>

                    </div>

            <?php endforeach; ?>

            <?php wp_reset_postdata(); ?>

		</div>

        <div class="row margin-top-30">

			<div class="col-md-12 text-center">			

                <a href="<?php echo get_permalink( esc_attr( get_theme_mod( 'avante_onepage_blog_link' ) ) ); ?>" class="btn btn-md btn-pill btn-outline-primary"><?php echo esc_html(get_theme_mod( 'avante_onepage_blog_btn', __( 'Read the blog', 'avante-lite' ) ) ); ?></a>

			</div>

        </div>

	</div>

</section>

<?php endif; ?>