<?php
/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1920; /* pixels */
}

function avante_setup() {
	
   // This theme uses a custom image size for featured images, displayed on "standard" posts.
   add_theme_support( 'post-thumbnails' );
   add_image_size( 'avante-post-thumbnails', 1000, 600, true);
   add_image_size( 'avante-home-post-thumbnails', 800, 660, true);
   add_image_size( 'avante-gallery-thumbnails', 600, 400, true);

   // Add default posts and comments RSS feed links to head. 
   add_theme_support( 'automatic-feed-links' );

   // Let WordPress manage the document title. By adding theme support, we declare that this theme does not use a hard-coded <title> tag in the document head, and expect WordPress to provide it for us.
   add_theme_support( 'title-tag' );

   // This theme uses wp_nav_menu() in one location.
   register_nav_menus( array(
      'main-menu'     => esc_html__( 'Main Menu', 'avante-lite' ),
   ) );

   // Make theme available for translation. Translations can be filed in the /languages/ directory.
   load_theme_textdomain( 'avante-lite', get_template_directory() . '/languages' );

   // Set up the WordPress core custom logo feature.
   add_theme_support( 'custom-logo', array(
      'height'      => 50,
      'width'       => 300,
      'flex-width' => true,
      'flex-height' => true,
      'header-text' => array( 'site-title', 'site-description' ),
   ) );

   // Set up the WordPress core custom header feature.
   add_theme_support( 'custom-header', array(
      'default-image'    => get_template_directory_uri() . '/images/bg-hero.jpg',
      'flex-width'      => true,
      'flex-height'     => true,
   ) );

   // Add support for Selective Refresh.
   add_theme_support( 'customize-selective-refresh-widgets' );

   // Add support for WooCommerce.
   add_theme_support( 'woocommerce' );
    
}
add_action( 'after_setup_theme', 'avante_setup' );

function avante_update_user_notices() {
  //remove notice dismissal flags from all users that might have it.
  delete_metadata( 'user', null, 'avante_welcome_admin_notice', null, true );
}
add_action( 'switch_theme', 'avante_update_user_notices' );