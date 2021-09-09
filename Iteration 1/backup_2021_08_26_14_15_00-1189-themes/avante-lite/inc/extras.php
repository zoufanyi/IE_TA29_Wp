<?php

/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 */

/**
 * Custom excerpt read more link
 */
function avante_excerpt_read_more_link($more) {
  if ( ! is_admin() ) {
  global $post;
  return ' ... <a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="more-link text-uppercase small"><strong>'.__('Read More','avante-lite').'</strong> <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>';
  }
}
add_filter( 'excerpt_more', 'avante_excerpt_read_more_link' );

/**
 * Add Bootstrap img-fluid to all content images
 */
function avante_add_image_responsive_class($content) {
   global $post;
   $pattern ="/<img(.*?)class=\"(.*?)\"(.*?)>/i";
   $replacement = '<img$1class="$2 img-fluid"$3>';
   $content = preg_replace($pattern, $replacement, $content);
   return $content;
}
add_filter('the_content', 'avante_add_image_responsive_class');

/**
 * Handles JavaScript detection.
 * Adds a js class to the root <html> element when JavaScript is detected.
 */
function avante_javascript_detection() {
  echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'avante_javascript_detection', 0 );

/**
 * Add postMessage & partials support for site title and description for the Theme Customizer.
  */
function avante_site_identity_partials_register( $wp_customize ) {

  $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
  $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

  if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'blogname', array(
      'selector' => '.site-title',
      'container_inclusive' => false,
      'render_callback' => function() {
            return bloginfo( 'name' );
        },
    ) );
    $wp_customize->selective_refresh->add_partial( 'blogdescription', array(
      'selector' => '.site-description',
      'container_inclusive' => false,
      'render_callback' => function() {
            return bloginfo( 'description' );
        },
    ) );
  }
}
add_action( 'customize_register', 'avante_site_identity_partials_register', 11 );

/**
 * Content class depending if sidebar is active or not.
  */
function avante_content_class() {
    return is_active_sidebar( 'sidebar-1' )
        ? 'col-md-12 col-lg-8' : 'col-md-12';
}

/**
 * Internal linking in the WordPress Theme Customizer
 */
function avante_customizer_internal_links() {
   ?>
   <script type="text/javascript">
    (function($) {
        $(document).ready(function() {
            var api = wp.customize;
            api.bind('ready', function() {
                $(['control', 'section', 'panel']).each(function(i, type) {
                    $('a[rel="tc-'+type+'"]').click(function(e) {
                        e.preventDefault();
                        var id = $(this).attr('href').replace('#', '');
                        if(api[type].has(id)) {
                            api[type].instance(id).focus();
                        }
                    });
                });
            });
        });
    })(jQuery);
   </script>
   <?php
}
add_action('customize_controls_print_scripts', 'avante_customizer_internal_links');


/**
 * Clean up wp_nav_menu_args
 */
//Remove the id="" on nav menu items
add_filter('nav_menu_item_id', '__return_null');

//Remove the container
add_filter('wp_nav_menu_args', function ($args = '') {
  $nav_menu_args = array();
  $nav_menu_args['container'] = false;

  if (empty($args['items_wrap'])) {
    $nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
  }

  return array_merge($args, $nav_menu_args);
});

/*
 * Fix customizer partial refresh for menus
 * @url https://wordpress.stackexchange.com/questions/295461/using-string-instead-of-object-class-instantiation-on-the-walker-argument-breaks
 */
add_filter( 'wp_nav_menu_args', function( $args ) {
    if ( isset( $args['walker'] ) && is_string( $args['walker'] ) && class_exists( $args['walker'] ) ) {
        $args['walker'] = new $args['walker'];
    }
    return $args;
}, 1001 );

// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false', 100 );

// Disables the block editor from managing widgets. renamed from wp_use_widgets_block_editor
add_filter( 'use_widgets_block_editor', '__return_false' );