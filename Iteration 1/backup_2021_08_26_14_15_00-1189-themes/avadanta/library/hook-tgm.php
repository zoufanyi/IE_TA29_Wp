<?php
/**
 * Recommended plugins
 *
 * @package avadanta
 */

if ( ! function_exists( 'avadanta_recommended_plugins' ) ) :

    /**
     * Recommend plugins.
     *
     * @since 1.0.0
     */
    function avadanta_recommended_plugins() {

        $plugins = array(              
          
             array(
                'name'     => esc_html__( 'Contact Form 7', 'avadanta' ),
                'slug'     => 'contact-form-7',
                'required' => false,
            ),

               array(
                'name'     => esc_html__( 'Avadanta Companion', 'avadanta' ),
                'slug'     => 'avadanta-companion',
                'required' => false,
            ),
        );

        tgmpa( $plugins );

    }

endif;

add_action( 'tgmpa_register', 'avadanta_recommended_plugins' );