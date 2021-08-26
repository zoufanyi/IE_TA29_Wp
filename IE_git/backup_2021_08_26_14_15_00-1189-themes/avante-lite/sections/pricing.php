<?php
/**
 * Pricing Section
 */
?>

<?php if ( get_theme_mod( 'avante_pricing_section_toggle' ) == '' ) : ?>

<section id="pricing" class="pricing position-relative py-7">

    <?php if ( get_theme_mod( 'avante_pricing_pattern_toggle' ) == '' ) : ?><div class="texture"></div><?php endif; ?>

    <div class="container">

        <div class="row">

            <div class="col-md-12 text-center">

                <?php if ( get_theme_mod( 'avante_onepage_pricing_title' ) ) : ?>
                    <h2 class="section-title"><?php echo esc_html(get_theme_mod( 'avante_onepage_pricing_title', __( 'Plans & Pricing', 'avante-lite' ) ) ); ?></h2>
                <?php endif; ?>

                <?php if ( get_theme_mod( 'avante_onepage_pricing_subtitle' ) ) : ?>
                    <?php if ( get_theme_mod( 'avante_pricing_section_title_divider_toggle' ) == '' ) : ?>
                        <span class="section-title-divider mx-auto mt-3 mb-3"></span>
                    <?php endif; ?>
                    <p class="lead text-info w-75 mx-auto"><?php echo esc_html(get_theme_mod( 'avante_onepage_pricing_subtitle', __( 'Sed fermentum, felis ut cursus varius, purus velit placerat tortor, at faucibus elit purus posuere velit. Integer sit amet felis ligula.', 'avante-lite' ) ) ); ?></p>
                <?php endif; ?>

            </div>

        </div>

        <?php if ( is_active_sidebar( 'pricing-widgets' ) ) : ?>

        <div class="widgets row multi-columns-row d-flex justify-content-center">

            <?php dynamic_sidebar( 'pricing-widgets' ); ?>

        </div>

        <?php endif; ?>

    </div>

</section>

<?php endif; ?>