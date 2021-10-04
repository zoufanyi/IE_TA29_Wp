<?php

namespace cBuilder\Classes;

class CCBUpdatesCallbacks
{
	/** update condition node coordinates
	 *  and add links target data
	 *  based on old logic
	 */
	public static function cc_update_all_calculators_conditions_coordinates() {
		$calculatorList = CCBCalculators::get_calculator_list();
		CCBExportImport::recalculate_coordinates( $calculatorList );
	}

    public static function get_calculators()
    {
        $calculators = new \WP_Query(array(
            'posts_per_page' => -1,
            'post_type' => 'cost-calc',
            'post_status' => array('publish')
        ));

        return $calculators->posts;
    }

    /**
     * Change old icons
     */
    public static function update_icons()
    {
        $calculators = self::get_calculators();
        if (!empty($calculators))
            foreach ($calculators as $calculator) {
                $clone = [];
                $fields = get_post_meta($calculator->ID, 'stm-fields', true);
                if (!empty($fields))
                    foreach ($fields as $field) {
                        foreach (CCBSettingsData::fields_data() as $data) {
                            if ($field['type'] === $data['name']) {
                                $field['icon'] = $data['icon'];
                            }
                        }
                        $clone[] = $field;
                    }

                update_post_meta( (int) $calculator->ID, 'stm-fields', apply_filters('stm_ccb_sanitize_array', $clone) );
            }
    }

    public static function add_header_title_options() {
        $calculators = self::get_calculators();
        foreach ($calculators as $calculator) {
            $customs = get_post_meta($calculator->ID, 'ccb-custom-fields', true);
            if ( !empty($customs) && is_array($customs) && empty($customs['headers']) ) {
                $customs['headers'] = [
                    'fields' => [
                        CCBCustomFields::generate_text_settings(
                            [
                                'label'     => 'Text-color',
                                'value'     => '#000000',
                            ],
                            [
                                'label'     => 'Font-size',
                                'min'       => 0,
                                'max'       => 100,
                                'step'      => 1,
                                'value'     => 22,
                                'dimension' => 'px'
                            ],
                            [
                                'label'     => 'Letter-spacing',
                                'min'       => 0,
                                'max'       => 100,
                                'step'      => 1,
                                'value'     => 0,
                                'dimension' => 'px'
                            ],
                            [
                                "blur"        => ["min" =>  0,  "max" => 20, "step" => 1,    "value" => 0, "dimension" => "px"],
                                "opacity"     => ["min" =>  0,  "max" => 1,  "step" => 0.01, "value" => 0, "dimension" => "px"],
                                "shift_right" => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                                "shift_down"  => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                                "color"       => '#ffffff',
                            ],
                            [
                                "value" => '700',
                            ],
                            [
                                "value" => 'normal'
                            ]
                        ),
                    ],
                    'name' => 'headers'


                ];
                update_post_meta( (int) $calculator->ID, 'ccb-custom-fields', apply_filters('stm_ccb_sanitize_array', $customs) );
            }
        }
    }

    public static function update_recaptcha_options(){
        $calculators = self::get_calculators();

        foreach ($calculators as $calculator) {
            $settings = get_option('stm_ccb_form_settings_' . $calculator->ID);

            if (!empty($settings) && isset($settings['recaptcha'])) {

                $captcha = $settings['recaptcha'];

                $enable     = !empty($captcha['enable'])    ? $captcha['enable']    : '';
                $site_key   = !empty($captcha['siteKey'])   ? $captcha['siteKey']   : '';
                $secret_key = !empty($captcha['secretKey']) ? $captcha['secretKey'] : '';

                if (empty($settings['recaptcha']['v3']))
                    $settings['recaptcha'] = [
                        'enable'    => $enable,
                        'type'      => 'v2',
                        'options'   => [
                            'v2'    => 'Google reCAPTCHA v2',
                            'v3'    => 'Google reCAPTCHA v3'
                        ],
                        'v2'        => [
                            'siteKey'   => $site_key,
                            'secretKey' => $secret_key,
                        ],
                        'v3'        => [
                            'siteKey'   => '',
                            'secretKey' => '',
                        ]
                    ];

                update_option('stm_ccb_form_settings_' . sanitize_text_field( $calculator->ID ), apply_filters('stm_ccb_sanitize_array', $settings));
            }
        }
    }

    public static function update_condition_data() {
        $calculators = self::get_calculators();

        foreach ($calculators as $calculator) {
            $conditions = get_post_meta($calculator->ID, 'stm-conditions', true);

            if (!empty($conditions['links'])) {
                foreach ($conditions['links'] as $index => $link) {

                    $options_from = $link['options_from'];
                    $condition    = isset($link['condition']) ? $link['condition'] : [];
                    $changed      = true;
                    $options      = !empty($options_from['options']) ? $options_from['options'] : [];

                    if (isset($condition)) {
                        foreach ($condition as $condition_key => $condition_item) {
                            foreach ($options as $option_index => $option) {
                                if ($condition_item['value'] === $option['optionValue'] && $changed) {
                                    $condition[$condition_key]['key'] = $option_index;
                                    $changed = false;
                                }
                            }
                        }
                    }
                    $conditions['links'][$index]['condition'] = $condition;
                }
            }

            update_post_meta( (int) $calculator->ID, 'stm-conditions', apply_filters('stm_ccb_sanitize_array', $conditions) );
        }
    }

    public static function condition_restructure() {
        $calculators = self::get_calculators();

        foreach ($calculators as $calculator) {
            $conditions = get_post_meta($calculator->ID, 'stm-conditions', true);

            if ( !empty($conditions['nodes']) ) {
                $conditions['nodes'] = array_map(function ($node){
                    $node['options'] = isset($node['options']['alias']) ? $node['options']['alias'] : $node['options'];
                    return $node;
                }, $conditions['nodes']);
            }

            if ( !empty($conditions['links']) ) {
                $conditions['links'] = array_map(function ($link){
                    $link = self::replace_options($link);
                    if ( isset($link['condition']) )
                        $link['condition'] = array_map(function ($condition) {
                            $condition = self::replace_options($condition, true);
                            return $condition;
                        }, $link['condition']);

                    return $link;
                }, $conditions['links']);
            }

            update_post_meta( (int) $calculator->ID, 'stm-conditions', apply_filters('stm_ccb_sanitize_array', $conditions) );
        }
    }

    public static function generate_hover_effects() {
        $calculators = self::get_calculators();

        foreach ($calculators as $calculator) {
            $customs = get_post_meta($calculator->ID, 'ccb-custom-fields', true);
            $styles  = get_post_meta($calculator->ID, 'ccb-custom-styles', true);

            if ( !empty($customs) && is_array($customs) && !empty($customs['submit-button']) ) {
                $submit_btn = $customs['submit-button'];

                if (empty($submit_btn['fields'][5]))
                    $customs['submit-button']['fields'][] = CCBCustomFields::generate_effects([
                        "name"  => "submit-hover-effects",
                        "label" => "Hover-effects",
                        "data"  => [
                            [
                                "label"     => "Background-color",
                                "name"      => "background-color",
                                "type"      => "single-color",
                                "default"   => "#047b47",
                                "value"     => "#047b47",

                            ],
                            [
                                "label"     => "Border-color",
                                "name"      => "border-color",
                                "type"      => "single-color",
                                "default"   => "#bdc9ca",
                                "value"     => "#bdc9ca",

                            ],
                            [
                                "label"     => "Font-color",
                                "name"      => "font-color",
                                "type"      => "single-color",
                                "default"   => "#fff",
                                "value"     => "#fff",
                            ],
                        ],
                        "effect_type" => "hover"
                    ]);

                update_post_meta( (int) $calculator->ID, 'ccb-custom-fields', apply_filters('stm_ccb_sanitize_array', $customs) );
            }

            if ( !empty($styles) && !empty($styles['checkbox']) ) {
                $checkbox                 = $styles['checkbox'];
                $checkbox['bg_color']     = isset($checkbox['bg_color'])     && $checkbox['bg_color'] === '#00b163' ? '#fff'    : $checkbox['bg_color'];
                $checkbox['checkedColor'] = isset($checkbox['checkedColor']) && $checkbox['bg_color'] === '#fff'    ? '#00b163' : $checkbox['checkedColor'];
                $styles['checkbox']       = $checkbox;

                update_post_meta( (int) $calculator->ID, 'ccb-custom-styles', apply_filters('stm_ccb_sanitize_array', $styles) );
            }
        }
    }

    private static function replace_options($param, $camel_case = false) {
        $option_to_key   = $camel_case ? 'optionTo'   :  'options_to';
        $option_from_key = $camel_case ? 'optionFrom' : 'options_from';

        $param[$option_to_key]   = !empty($param[$option_to_key]) && is_array($param[$option_to_key])   && !empty($param[$option_to_key]['alias'])   ? $param[$option_to_key]['alias']   : $param[$option_to_key];
        $param[$option_from_key] = !empty($param[$option_from_key]) && is_array($param[$option_from_key]) && !empty($param[$option_from_key]['alias']) ? $param[$option_from_key]['alias'] : $param[$option_from_key];

        return $param;
    }

    public static function rename_woocommerce_settings() {
        $calculators = self::get_calculators();

        foreach ($calculators as $calculator) {
            $settings = get_option('stm_ccb_form_settings_' . $calculator->ID);

            if ( !empty($settings) && isset($settings['wooCommerce']) ) {
                $settings['woo_checkout'] = $settings['wooCommerce'];
                unset($settings['wooCommerce']);

                update_option('stm_ccb_form_settings_' . sanitize_text_field( $calculator->ID ), apply_filters('stm_ccb_sanitize_array', $settings));
            }
        }
    }

    public static function generate_active_effects() {
        $calculators = self::get_calculators();

        foreach ( $calculators as $calculator ) {
            $customs = get_post_meta( $calculator->ID, 'ccb-custom-fields', true );

            if ( !empty($customs) && is_array($customs) ) {

                if ( !empty( $customs['quantity']['fields'] ) ) {

                    $active_effect_name = 'quantity-active-effects';
                    $key = array_search( $active_effect_name, array_column( $customs['quantity']['fields'], 'name' ) );

                    if ( empty($key) || empty( $customs['quantity']['fields'][ $key ] ) ) {
                        $customs['quantity']['fields'][] = self::set_generate_effects( $active_effect_name );
                    }
                }

                if ( !empty($customs['text-area']['fields']) ) {
                    $active_effect_name = 'text-area-active-effects';
                    $key = array_search( $active_effect_name, array_column($customs['text-area']['fields'], 'name'));

                    if ( empty($key)  || empty( $customs['text-area']['fields'][ $key ] ) ) {
                        $customs['text-area']['fields'][] = self::set_generate_effects( $active_effect_name );
                    }
                }

                update_post_meta( (int) $calculator->ID, 'ccb-custom-fields', apply_filters( 'stm_ccb_sanitize_array', $customs ) );

            }
        }
    }

    protected static function set_generate_effects( $active_effect_name ) {
        return CCBCustomFields::generate_effects( [
            "name"        => $active_effect_name,
            "label"       => "Active-effects",
            "data"        => [
                [
                    "label"   => "Background-color",
                    "name"    => "background-color",
                    "type"    => "effects",
                    "default" => "#fff",
                    "value"   => "#fff",

                ],
                [
                    "label"   => "Border-color",
                    "name"    => "border-color",
                    "type"    => "effects",
                    "default" => "#00b163",
                    "value"   => "#00b163",

                ],
                [
                    "label"   => "Font-color",
                    "name"    => "font-color",
                    "type"    => "effects",
                    "default" => "#000",
                    "value"   => "#000",
                ],
            ],
            "effect_type" => "active"
        ] );
    }

    public static function ccb_admin_notification_transient() {
        $data = [ 'show_time' => DAY_IN_SECONDS * 3 + time(), 'step' => 0, 'prev_action' => '' ];
        set_transient( 'stm_cost-calculator-builder_notice_setting', $data );
    }
}