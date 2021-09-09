<?php
/**
 * Enqueue scripts
 */
function avante_scripts() {  
    wp_enqueue_style('bootstrap', get_template_directory_uri().'/css/bootstrap.min.css', array(), '4.1.3' );
    wp_enqueue_style('bootstrap-grid', get_template_directory_uri().'/css/bootstrap-grid.min.css', array(), '4.1.3' );
    wp_enqueue_style('avante-multicolumnsrow', get_template_directory_uri().'/css/multi-columns-row.css', array(), '1.0.0' );
    wp_enqueue_style('fontawesome', get_template_directory_uri().'/css/font-awesome.min.css', array(), '5.8.1' );
    wp_enqueue_style('magnificpopup', get_template_directory_uri() . '/css/magnific-popup.css', array(), '1.1.0');
    wp_enqueue_style('avante-googlefonts', 'https://fonts.googleapis.com/css?family=Archivo:100,300,400,500,600,700,800|Rubik:100,300,400,500,600,700,800|Montserrat:300,300i,400,400i,500,600,700,700i,800,800i' );
    wp_enqueue_style('slick', get_template_directory_uri().'/css/slick.css', array(), '1.9.0' );
    wp_enqueue_style('slick-theme', get_template_directory_uri().'/css/slick-theme.css', array('slick'), '1.9.0' );
    wp_enqueue_style('avante-base', get_stylesheet_uri(), array(), '1.0.0' );
    wp_enqueue_script('jquery-effects-core');
    wp_enqueue_script('bootstrap-bundle', get_template_directory_uri().'/js/bootstrap.bundle.min.js', array('jquery'), '4.1.3', true );
    wp_enqueue_script('slick', get_template_directory_uri() . '/js/slick.min.js', array('jquery'), '1.9.0', true);
    wp_enqueue_script('hoverintent', get_template_directory_uri() . '/js/hoverintent.min.js', array(), '4.1.3', true);
    wp_enqueue_script('avante-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery', 'hoverintent'), '4.1.3', true);
    wp_enqueue_script('magnificpopup', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array('jquery'), '1.1.0', true );
    wp_enqueue_script('avante-magnific-init', get_template_directory_uri() . '/js/jquery.magnific-popup-init.js', array('jquery'), '1.1.0', true );

    if( is_page_template( 'template-onepage.php' ) ) {
        wp_enqueue_script('avante-smoothscroll', get_template_directory_uri().'/js/smooth-scroll.js', array('jquery'), '1.0', true );
    }

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}  
add_action('wp_enqueue_scripts', 'avante_scripts');

/**
 * Registers an editor stylesheet for the theme.
 */
function avante_editor_styles() {
    add_editor_style( 'editor-style.css' );
}
add_action( 'admin_init', 'avante_editor_styles' );