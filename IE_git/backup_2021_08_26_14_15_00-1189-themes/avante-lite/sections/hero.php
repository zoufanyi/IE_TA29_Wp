<?php
/**
 * Hero Section
 */
?>

<?php if ( get_theme_mod( 'avante_hero_section_toggle' ) == '' ) : ?>

<section id="hero" class="hero position-relative">

    <div class="overlay"></div>
    
    <?php if ( get_theme_mod( 'avante_hero_pattern_toggle' ) == '' ) : ?><div class="texture"></div><?php endif; ?>

    <div class="container">
        
        <div class="row">
            
            <div class="col-md-12">
                
                <div class="text-center text-light mb-5">

                    <?php if ( get_theme_mod( 'avante_hero_section_title1' ) ) : ?>
                        <h1 class="title"><?php echo esc_html( get_theme_mod( 'avante_hero_section_title1', __( 'Avante.', 'avante-lite' ) ) ); ?></h1>
                    <?php endif; ?>

                    <?php if ( get_theme_mod( 'avante_hero_section_title2' ) ) : ?>
                        <h2 class="sub-title mb-0"><?php echo esc_html( get_theme_mod( 'avante_hero_section_title2', __( 'What You Need to Build Your Dream', 'avante-lite' ) ) ); ?></h2>
                    <?php endif; ?>

                </div>
                                
                <?php if ( is_active_sidebar( 'hero-widgets' ) ) : ?>

                <div class="lead text-center text-light font-weight-normal mb-5">

                    <?php dynamic_sidebar( 'hero-widgets' ); ?>
                
                </div>

                <?php endif; ?>

                <div class="row">           
                
                    <?php if ( get_theme_mod( 'avante_hero_section_btn1_toggle' ) == '' && get_theme_mod( 'avante_hero_section_btn2_toggle' ) == '1' ) { ?>
                    <div class="col-md-12 text-center">
                        <a href="<?php echo esc_url(get_theme_mod( 'avante_hero_section_btn1url', '#' )); ?>" class="btn btn-lg btn-pill btn-primary"><?php echo esc_html(get_theme_mod( 'avante_hero_section_btn1', __( 'Learn More', 'avante-lite' ) ) ); ?></a>
                    </div>
                    <?php } else if ( get_theme_mod( 'avante_hero_section_btn1_toggle' ) == '') : ?>
                    <div class="col-md-6 text-center text-md-right mb-4 mb-md-0">
                        <a href="<?php echo esc_url(get_theme_mod( 'avante_hero_section_btn1url', '#' )); ?>" class="btn btn-lg btn-pill btn-primary"><?php echo esc_html(get_theme_mod( 'avante_hero_section_btn1', __( 'Learn More', 'avante-lite' ) ) ); ?></a>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ( get_theme_mod( 'avante_hero_section_btn2_toggle' ) == '' && get_theme_mod( 'avante_hero_section_btn1_toggle' ) == '1' ) { ?>
                    <div class="col-md-12 text-center">
                        <a href="<?php echo esc_url(get_theme_mod( 'avante_hero_section_btn2url', '#' )); ?>" class="btn btn-lg btn-pill btn-light"><?php echo esc_html(get_theme_mod( 'avante_hero_section_btn2', __( 'Get Started', 'avante-lite' ) ) ); ?></a>
                    </div>
                    <?php } else if ( get_theme_mod( 'avante_hero_section_btn2_toggle' ) == '') : ?>
                    <div class="col-md-6 text-center text-md-left">
                        <a href="<?php echo esc_url(get_theme_mod( 'avante_hero_section_btn2url', '#' )); ?>" class="btn btn-lg btn-pill btn-light"><?php echo esc_html(get_theme_mod( 'avante_hero_section_btn2', __( 'Get Started', 'avante-lite' ) ) ); ?></a>
                    </div>
                    <?php endif; ?>

                </div>
            
            </div>
        
        </div>
    
    </div>

</section>

<?php endif; ?>