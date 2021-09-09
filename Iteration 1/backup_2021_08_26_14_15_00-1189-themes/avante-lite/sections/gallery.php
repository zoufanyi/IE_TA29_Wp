<?php
/**
 * Photo Gallery Section
 */
?>

<?php if ( get_theme_mod( 'avante_gallery_section_toggle' ) == '' ) : ?>

<section id="gallery" class="gallery position-relative py-7">

	<?php if ( get_theme_mod( 'avante_gallery_pattern_toggle' ) == '' ) : ?>
        <div class="texture-left"></div>
        <div class="texture-right"></div>
    <?php endif; ?>

	<div class="container">

		<div class="row">

            <div class="col-md-12 text-center">

                <?php if ( get_theme_mod( 'avante_onepage_gallery_title' ) ) : ?>
                    <h2 class="section-title"><?php echo esc_html(get_theme_mod( 'avante_onepage_gallery_title', __( 'Photo Gallery', 'avante-lite' ) ) ); ?></h2>
                <?php endif; ?>

                <?php if ( get_theme_mod( 'avante_onepage_gallery_subtitle' ) ) : ?>
                    <?php if ( get_theme_mod( 'avante_gallery_section_title_divider_toggle' ) == '' ) : ?>
                        <span class="section-title-divider mt-3 mb-3"></span>
                    <?php endif; ?>
                    <p class="lead text-info w-75 mx-auto"><?php echo esc_html(get_theme_mod( 'avante_onepage_gallery_subtitle', __( 'Sed fermentum, felis ut cursus varius, purus velit placerat tortor, at faucibus elit purus posuere velit. Integer sit amet felis ligula.', 'avante-lite' ) ) ); ?></p>
                <?php endif; ?>
            
            </div>

		</div>

    </div>

    <div class="<?php echo esc_attr( get_theme_mod( 'avante_onepage_gallery_width', 'container' ) ); ?>">

		<?php if ( is_active_sidebar( 'gallery-widgets' ) ) : ?>

	    <div class="row mt-4">

            <div class="col-md-12">

    	        <?php dynamic_sidebar( 'gallery-widgets' ); ?>

            </div>

		</div>

	    <?php endif; ?>

	</div>

</section>

<?php endif; ?>