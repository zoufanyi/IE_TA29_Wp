<?php
/**
 * Testimonials Section
 */
  $testimonials_layout = esc_attr( get_theme_mod( 'avante_onepage_testimonials_layout', '2' ) );

  $slick_options = array(
    adaptiveHeight => true,
    dots => true,
    autoplay => false,
    autoplaySpeed => 5*1000,
    arrows => true,
    pauseOnHover => true,
    infinite => true,
    slidesToShow => $testimonials_layout,
    slidesToScroll => 1,
    speed => 1000,
    responsive=> array(
      array(
        breakpoint => 992,
        settings => array(
          slidesToShow => 1,
          slidesToScroll => 1
        )
      ),
      array(
        breakpoint => 768,
        settings => 'unslick'
      )
    )
  );
?>

<?php if ( get_theme_mod( 'avante_testimonials_section_toggle' ) == '' ) : ?>

<section id="testimonials" class="testimonials position-relative">

    <?php if ( get_theme_mod( 'avante_testimonials_pattern_toggle' ) == '' ) : ?><div class="texture"></div><?php endif; ?>

    <div class="container">

        <div class="row">

            <div class="col-md-12 text-center">

                <?php if ( get_theme_mod( 'avante_onepage_testimonials_title' ) ) : ?>
                    <h2 class="section-title"><?php echo esc_html(get_theme_mod( 'avante_onepage_testimonials_title', __( 'Testimonials', 'avante-lite' ) ) ); ?><span></span></h2>
                <?php endif; ?>

                <?php if ( get_theme_mod( 'avante_onepage_testimonials_subtitle' ) ) : ?>
                    <?php if ( get_theme_mod( 'avante_testimonials_section_title_divider_toggle' ) == '' ) : ?>
                      <span class="section-title-divider mx-auto mt-3 mb-3"></span>
                    <?php endif; ?>
                    <p class="lead text-info w-75 mx-auto"><?php echo esc_html(get_theme_mod( 'avante_onepage_testimonials_subtitle', __( 'Sed fermentum, felis ut cursus varius, purus velit placerat tortor, at faucibus elit purus posuere velit. Integer sit amet felis ligula.', 'avante-lite' ) ) ); ?></p>
                <?php endif; ?>

            </div>

        </div>

        <?php if ( is_active_sidebar( 'testimonial-widgets' ) ) : ?>

          <div class="slick-carousel" data-slick='<?php echo htmlspecialchars( json_encode( $slick_options ), ENT_QUOTES, 'UTF-8' ); ?>'>

            <?php dynamic_sidebar( 'testimonial-widgets' ); ?>

          </div>

        <?php endif; ?>

    </div>

</section>

<?php endif; ?>