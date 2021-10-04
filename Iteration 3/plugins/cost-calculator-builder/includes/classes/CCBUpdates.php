<?php

namespace cBuilder\Classes;

class CCBUpdates {

    private static $updates = [
        '2.0.6' => [
            'add_header_title_options',
            'update_recaptcha_options'
        ],

        '2.1.0' => [
            'update_condition_data'
        ],

        '2.1.1' => [
            'condition_restructure'
        ],
        '2.1.2' => [
            'generate_hover_effects'
        ],
        '2.1.6' => [
            'rename_woocommerce_settings'
        ],
        '2.2.1' => [
            'generate_active_effects'
        ],
        '2.2.3' => [
            'ccb_admin_notification_transient',
        ],
	    '2.2.4' => [
	        'cc_update_all_calculators_conditions_coordinates',
        ]
    ];

    public static function init() {
        if (version_compare( get_option( 'ccb_version' ), CALC_VERSION, '<' ) )
            self::update_version();
    }

    public static function get_updates() {
        return self::$updates;
    }

    public static function needs_to_update() {
        $update_versions    = array_keys( self::get_updates() );
        $current_db_version = get_option( 'calc_db_updates', 1 );
        usort( $update_versions, 'version_compare' );
        return ! is_null( $current_db_version ) && version_compare( $current_db_version, end( $update_versions ), '<' );
    }

    private static function maybe_update_db_version() {
        if ( self::needs_to_update() ) {
            $updates = self::get_updates();
            $calc_db_version = get_option('calc_db_updates');

            foreach ( $updates as $version => $callback_arr)
                if ( version_compare( $calc_db_version, $version, '<' ) )
                    foreach ($callback_arr as $callback)
                        call_user_func( ["\\cBuilder\\Classes\\CCBUpdatesCallbacks", $callback] );
        }
        update_option('calc_db_updates', sanitize_text_field( CALC_DB_VERSION ), true);
    }

    public static function update_version() {
        update_option('ccb_version', sanitize_text_field( CALC_VERSION ), true);
        self::maybe_update_db_version();
    }

    /**
     * Run calc updates after import old calculators
     * @return void
     */
    public static function run_calc_updates() {
        check_ajax_referer( 'ccb_run_calc_updates', 'nonce' );

        $updates = self::get_updates();

        if (current_user_can('publish_posts') && $_POST['action'] === 'calc-run-calc-updates' && !empty($_POST['access']))
            foreach ( $updates as $version => $callback_arr) {
                foreach ($callback_arr as $callback) {
                    call_user_func( ["\\cBuilder\\Classes\\CCBUpdatesCallbacks", $callback] );
                }
            }
    }
}