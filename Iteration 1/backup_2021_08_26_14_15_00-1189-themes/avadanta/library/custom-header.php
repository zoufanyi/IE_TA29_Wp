<?php
/**
 * Sample implementation of the Custom Header feature.
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 */
/**
 * Set up the WordPress core custom header feature.
 *
 */
function avadanta_custom_header_setup() {
    add_theme_support( 'custom-header', apply_filters( 'avadanta_custom_header_args', array(
        'default-image'          => '%s/assets/images/header-bg.jpg',
        'default-text-color'     => 'fff',
        'width'                  => 1200,
        'height'                 => 528,
        'flex-height'            => true,
        'wp-head-callback'       => 'avadanta_header_style',
    ) ) );

}
add_action( 'after_setup_theme', 'avadanta_custom_header_setup' );

if ( ! function_exists( 'avadanta_header_style' ) ) :
    /**
     * Styles the header image and text displayed on the blog.
     *
     * @see avadanta_custom_header_setup().
     */
    function avadanta_header_style() {
        $css = '';

        $header_title_color = get_theme_mod('header_title_color','#000');
        $header_tagline_color = get_theme_mod('header_tagline_color','#000');

            // If we get this far, we have custom styles. Let's do this.
            // Has the text been hidden?
            if ( display_header_text() ) :
           
           
            $css .='
            .avadanta-title a {
                color: '.esc_attr( $header_title_color ).';
            }
            .avadanta-desc {
                color: '.esc_attr( $header_tagline_color ).';
            }';
            endif;
        
        wp_add_inline_style( 'avadanta-style', $css );
    }
endif;
add_action( 'wp_enqueue_scripts', 'avadanta_header_style', 10 );