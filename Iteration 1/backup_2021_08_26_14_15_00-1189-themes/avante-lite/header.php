<?php
/**
 * Header section
 */
?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?> class="no-js">
<!--<![endif]-->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php if ( is_singular() && pings_open() ) : ?>
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php endif; ?>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <?php wp_body_open(); ?>

    <div class="body-wrap" id="page">

        <a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'avante-lite' ); ?></a>

        <div class="page-header topnav bg-white" id="navbar">

            <div class="container">

                <div class="row">

                    <div class="col-md-12">

                        <nav class="navbar navbar-light navbar-expand-lg justify-content-between py-0 px-0">

                            <?php if ( has_custom_logo()) : ?>

                                <div class="navbar-brand">

                                    <?php the_custom_logo() ?>                                    

                                </div>

                            <?php else : ?>

                                <div class="navbar-brand">

                                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" rel="home" class="site-title"><?php bloginfo( 'name' ); ?></a>                                    

                                </div>

                                <span class="navbar-text site-description small d-none d-md-block py-0"><?php esc_html(bloginfo( 'description' )); ?></span>

                            <?php endif; ?>

                            <?php if ( has_nav_menu( 'main-menu' ) ) : ?>

                                <button id="mobileNavToggler" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mobileNav" aria-controls="mobileNav" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle Navigation', 'avante-lite' ); ?>"><span class="navbar-toggler-icon"></span></button>

                                <?php if ( has_nav_menu('main-menu') ) 
                                    wp_nav_menu(array(
                                        'theme_location' => 'main-menu',
                                        'menu_id'        => 'primaryMenu',
                                        'menu_class'     => 'navbar-nav ml-auto',
                                        'depth'          => 3,
                                        'walker'         => 'Avante_Bootstrap_NavWalker'
                                    )
                                ); ?>

                            <?php endif; ?>

                        </nav>

                    </div>

                </div>

            </div>

        </div>

        <div id="mobileNav" class="mobile-nav">
                
            <div class="container">

              <?php if ( has_nav_menu('main-menu') ) wp_nav_menu(array(
                  'theme_location' => 'main-menu',
                  'menu_id'        => 'mobileMenu',
                  'menu_class'     => 'mobile-menu',
                  'depth'          => 2,
                  'walker'         => 'Avante_NavWalker_Mobile'
                )); ?>

            </div>

        </div>