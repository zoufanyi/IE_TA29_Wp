<?php
/**
 * Avadanta functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Avadanta
 */

if( ! defined( 'ABSPATH' ) )
{
	exit;
}

define('AVADANTA_THEME_DIR', get_template_directory());
define('AVADANTA_THEME_URI', get_template_directory_uri());

/**
 * Custom Header feature.
 */
require( AVADANTA_THEME_DIR . '/theme-setup.php');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function avadanta_content_width() {

	$GLOBALS['content_width'] = apply_filters( 'avadanta_content_width', 696 );
}
add_action( 'after_setup_theme', 'avadanta_content_width', 0 );	

$theme = wp_get_theme();

require( AVADANTA_THEME_DIR .'/library/customizer/customizer-alpha-color-picker/class-avadanta-customize-alpha-color-control.php');

require( AVADANTA_THEME_DIR . '/library/breadcrumbs-trail.php');

require( AVADANTA_THEME_DIR . '/script/enqueue-scripts.php');

require( AVADANTA_THEME_DIR . '/library/template-functions.php');

require(AVADANTA_THEME_DIR . '/library/template-tags.php');

require(AVADANTA_THEME_DIR . '/library/class-tgm-plugin-activation.php');

require(AVADANTA_THEME_DIR . '/library/hook-tgm.php');

require(AVADANTA_THEME_DIR . '/library/avadanta-typography.php');

require(AVADANTA_THEME_DIR . '/library/customizer/avadanta_customizer_sections.php');

require ( AVADANTA_THEME_DIR . '/library/customizer/avadanta-customizer.php' );

require ( AVADANTA_THEME_DIR . '/library/custom-header.php' );

require ( AVADANTA_THEME_DIR . '/library/getting-started/getting-started.php' );

require ( AVADANTA_THEME_DIR . '/library/upgrade/class-customize.php' );



if( ! function_exists( 'avadanta_admin_notice' ) ) :
/**
 * Addmin notice for getting started page
*/
function avadanta_admin_notice(){
    global $pagenow;
    $theme_args      = wp_get_theme();
    $meta            = get_option( 'avadanta_admin_notice' );
    $name            = $theme_args->__get( 'Name' );
    $current_screen  = get_current_screen();
    
    if( 'themes.php' == $pagenow && !$meta ){
        
        if( $current_screen->id !== 'dashboard' && $current_screen->id !== 'themes' ){
            return;
        }

        if( is_network_admin() ){
            return;
        }

        if( ! current_user_can( 'manage_options' ) ){
            return;
        } ?>

        <div class="welcome-message notice notice-info">
            <div class="notice-wrapper">
                <div class="notice-text">
                    <h3><?php esc_html_e( 'Congratulations! For Activaing Avadanta Theme', 'avadanta' ); ?></h3>
                    <p><?php printf( __( '%1$s is now installed and ready to use. Click below to see theme documentation, plugins to install and other details to get started.Also Install Avadanta Companion Plugin To Get Advantages Of Theme Homepage', 'avadanta' ), esc_html( $name ) ) ; ?></p>
                    <p><a href="<?php echo esc_url( admin_url( 'themes.php?page=avadanta-getting-started' ) ); ?>" class="button button-primary" style="text-decoration: none;"><?php esc_html_e( 'Go to the getting started.', 'avadanta' ); ?></a></p>
                    <p><a href="<?php echo esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ); ?>" class="button button-secondary" style="text-decoration: none;"><?php esc_html_e( 'Install Avadanta Companion', 'avadanta' ); ?></a></p>

                    <p class="dismiss-link"><strong><a href="?avadanta_admin_notice=1" class="dismiss-getting"><?php esc_html_e( 'Dismiss', 'avadanta' ); ?></a></strong></p>
                </div>
                 <div class="avadanta-theme-screenshot">
                        <img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/screenshot.png" />
                    </div>
            </div>
        </div>
    <?php }
}
endif;
add_action( 'admin_notices', 'avadanta_admin_notice' );

if( ! function_exists( 'avadanta_update_admin_notice' ) ) :
/**
 * Updating admin notice on dismiss
*/
function avadanta_update_admin_notice(){
    if ( isset( $_GET['avadanta_admin_notice'] ) && $_GET['avadanta_admin_notice'] = '1' ) {
        update_option( 'avadanta_admin_notice', true );
    }
}
endif;
add_action( 'admin_init', 'avadanta_update_admin_notice' );