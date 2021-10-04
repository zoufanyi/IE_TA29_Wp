<?php
function ccb_widgets_load()
{
    // Check required version
    $elementor_version_required = '2.6.7';
    if ( defined('ELEMENTOR_VERSION') && ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
        add_action( 'admin_notices', 'ccb_widgets_fail_load_out_of_date' );
        return;
    }

    // Require the main plugin file
    require( CALC_DIR . '/includes/classes/plugin.php' );
}

function ccb_widgets_fail_load_out_of_date()
{
    if ( ! current_user_can( 'update_plugins' ) ) {
        return;
    }

    $file_path = 'elementor/elementor.php';

    $upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
    $message = '<p>' . __( 'Elementor CCB Widgets is not working because you are using an old version of Elementor.', 'cost-calculator' ) . '</p>';
    $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'cost-calculator' ) ) . '</p>';

    echo '<div class="error">' . $message . '</div>';
}
