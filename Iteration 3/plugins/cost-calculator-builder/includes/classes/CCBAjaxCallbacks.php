<?php

namespace cBuilder\Classes;

class CCBAjaxCallbacks {

    public static function register_calc_hooks() {
        add_action( 'ccb_custom_importer', [ self::class, 'ccb_custom_importer' ] );
    }

    /**
     * This function needs to be called after loading the plugin
     *
     * @param $file_name
     *
     * @return boolean
     */
    public static function ccb_custom_importer( $file_name ) {

        if ( ! file_exists( $file_name ) && ! function_exists( 'is_user_logged_in' ) ) {
            return false;
        }

        $contents    = file_get_contents( $file_name );
        $calculators = json_decode( $contents, true );

        if ( is_array( $calculators ) ) {
            foreach ( $calculators as $calculator ) {
                $my_post = [
                    'post_type'   => 'cost-calc',
                    'post_status' => 'publish',
                ];
                // get id
                $id = wp_insert_post( $my_post );

                $data = [
                    'id'         => $id,
                    'title'      => isset( $calculator['stm-name'] ) ? $calculator['stm-name'] : '',
                    'formula'    => isset( $calculator['stm-formula'] ) ? $calculator['stm-formula'] : [],
                    'settings'   => isset( $calculator['stm_ccb_form_settings'] ) ? $calculator['stm_ccb_form_settings'] : [],
                    'builder'    => isset( $calculator['stm-fields'] ) ? $calculator['stm-fields'] : [],
                    'conditions' => isset( $calculator['stm-conditions'] ) ? $calculator['stm-conditions'] : [],
                    'styles'     => isset( $calculator['ccb-custom-styles'] ) ? $calculator['ccb-custom-styles'] : CCBCustomFields::custom_default_styles(),
                    'fields'     => isset( $calculator['ccb-custom-fields'] ) ? $calculator['ccb-custom-fields'] : CCBCustomFields::custom_fields(),
                ];

                ccb_update_calc_values( $data );
            }
        }
    }
}