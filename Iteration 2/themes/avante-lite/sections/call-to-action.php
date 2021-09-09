<?php
/**
 * Call to Action Section
 */
?>

<?php if ( get_theme_mod( 'avante_cta_section_toggle' ) == '' ) : ?>

<section id="calltoaction" class="calltoaction container-fluid text-light">

    <div class="row">

        <div class="col-md-6 py-7 left">

            <div class="overlay"></div>

            <div class="row">

                <div class="col py-7 px-10">

                    <?php if ( get_theme_mod( 'avante_onepage_cta_left_title' ) ) : ?>
                        <h2 class="section-title"><?php echo esc_html(get_theme_mod( 'avante_onepage_cta_left_title', __( 'Join Us', 'avante-lite' ) ) ); ?></h2>
                    <?php endif; ?>

                    <?php if ( get_theme_mod( 'avante_onepage_cta_left_subtitle' ) ) : ?>
                        <?php if ( get_theme_mod( 'avante_onepage_cta_left_title_divider_toggle' ) == '' ) : ?>
                            <span class="section-title-divider mt-3 mb-3"></span>
                        <?php endif; ?>
                        <p class="lead text-light"><?php echo esc_html(get_theme_mod( 'avante_onepage_cta_left_subtitle', __( 'Sed fermentum, felis ut cursus varius, purus velit placerat tortor, at faucibus elit purus posuere velit. Integer sit amet felis ligula.', 'avante-lite' ) ) ); ?></p>
                    <?php endif; ?>

                    <?php if ( get_theme_mod( 'avante_onepage_cta_link' ) ) : ?>
                        <a href="<?php echo esc_url( get_theme_mod( 'avante_onepage_cta_link', '#' ) ); ?>" class="btn btn-lg btn-pill btn-light mt-4"><?php echo esc_html( get_theme_mod( 'avante_onepage_cta_btn', __( 'Start Free Trial', 'avante-lite' ) ) ); ?></a>
                    <?php endif; ?>

                </div>

            </div>

        </div>

        <div class="col-md-6 py-7 right">

            <div class="overlay"></div>

            <div class="texture"></div>

            <div class="row">

                <div class="col py-7 px-10">

                    <?php if ( get_theme_mod( 'avante_onepage_cta_right_title' ) ) : ?>
                        <h2 class="section-title"><?php echo esc_html(get_theme_mod( 'avante_onepage_cta_right_title', __( 'Subscribe', 'avante-lite' ) ) ); ?></h2>
                    <?php endif; ?>

                    <?php if ( get_theme_mod( 'avante_onepage_cta_right_subtitle' ) ) : ?>
                        <?php if ( get_theme_mod( 'avante_onepage_cta_right_title_divider_toggle' ) == '' ) : ?>
                            <span class="section-title-divider mt-3 mb-3"></span>
                        <?php endif; ?>
                        <p class="lead text-light"><?php echo esc_html(get_theme_mod( 'avante_onepage_cta_right_subtitle', __( 'Sed fermentum, felis ut cursus varius, purus velit placerat tortor, at faucibus elit purus posuere velit. Integer sit amet felis ligula.', 'avante-lite' ) ) ); ?></p>
                    <?php endif; ?>
                    
                    <?php if ( is_active_sidebar( 'cta-widgets' ) ) : ?>

                        <div class="d-inline-block mt-4 w-75">

                            <?php dynamic_sidebar( 'cta-widgets' ); ?>

                        </div>

                    <?php endif; ?>

                </div>
                
            </div>

        </div>

    </div>

</section>

<?php endif; ?>