<?php

namespace cBuilder\Classes;

class CCBCalculators{

    const DESC_POSITION_BEFORE  = 'before';
    const DESC_POSITION_AFTER   = 'after';
    const CALCULATOR_POST_TYPE  = 'cost-calc';

	public static function getWPCalculatorsData(){
	    $calculatorPosts = new \WP_Query( [
		    'posts_per_page' => -1,
		    'post_type' => self::CALCULATOR_POST_TYPE,
		    'post_status' => ['publish']
	    ] );

	    return $calculatorPosts->posts;
    }

    /**
     * Get Default Data
     */
    public static function edit_calc(){

        check_ajax_referer( 'ccb_edit_calc', 'nonce' );

        if ( ! current_user_can('publish_posts') ) {
            wp_send_json_error( __('You are not allowed to run this action', 'cost-calculator-builder') );
        }

        $result = [
            'id' => '',
            'title' => '',
            'forms' => [],
            'fields' => [],
            'builder' => [],
            'formula' => [],
            'settings' => [],
            'products' => [],
            'categories' => [],
            'conditions' => [],
            'success' => false,
            'custom_styles' => [],
            'custom_fields' => [],
            'desc_options' => [
                self::DESC_POSITION_BEFORE => __( 'Show before field', 'cost-calculator-builder' ),
                self::DESC_POSITION_AFTER  => __( 'Show after field', 'cost-calculator-builder' ),
            ],
            'message' => __('There is no calculator with this id', 'cost-calculator-builder')
        ];

        if (isset($_GET['calc_id'])) {
            $calc_id = (int) sanitize_text_field( $_GET['calc_id'] );

            $result['id'] = $calc_id;
            $result['title'] = get_post_meta($calc_id, 'stm-name', true);
            $result['fields'] = CCBSettingsData::fields_data();
            $result['formula'] = get_post_meta($calc_id, 'stm-formula', true);
            $result['conditions'] = get_post_meta($calc_id, 'stm-conditions', true);
            $result['existing'] = self::get_calculator_list();

            $result['builder'] = !empty(get_post_meta($calc_id, 'stm-fields', true))
                ? get_post_meta($calc_id, 'stm-fields', true)
                : [];

            $result['custom_styles'] = empty(get_post_meta($calc_id, 'ccb-custom-styles', true))
                ? CCBCustomFields::custom_default_styles() : get_post_meta($calc_id, 'ccb-custom-styles', true);

            $result['custom_fields'] = empty(get_post_meta($calc_id, 'ccb-custom-fields', true))
                ? CCBCustomFields::custom_fields() : get_post_meta($calc_id, 'ccb-custom-fields', true);

            /* pro-features */
            $result['forms'] = ccb_contact_forms();
            $result['products'] = ccb_woo_products();
            $result['categories'] = ccb_woo_categories();

            $settings = get_option('stm_ccb_form_settings_' . $calc_id);
            if(!empty($settings) && isset($settings[0]) && isset($settings[0]['general']))
                $settings = $settings[0];

            if ( !empty($settings) )
                $result['settings'] = $settings;

           if(!is_array($result['settings']) || empty($result['settings']['general']))
               $result['settings'] = CCBSettingsData::settings_data();

            if(!empty($result['settings']['formFields']['body']))
                $result['settings']['formFields']['body'] = str_replace('<br>', PHP_EOL, $result['settings']['formFields']['body']);

            $result['success'] = true;
            $result['message'] = '';
        }

        // send data
        wp_send_json($result);
    }

    /**
     * Get Default
     */
    public static function duplicate_calc() {

        check_ajax_referer( 'ccb_duplicate_calc', 'nonce' );

        if ( ! current_user_can('publish_posts') ) {
            wp_send_json_error( __('You are not allowed to run this action', 'cost-calculator-builder') );
        }

        $result = [
            'existing' => [],
            'success' => false,
            'message' => __("Couldn't duplicate calculator, please try again!', 'cost-calculator-builder"),
        ];


	    if ( isset($_GET['calculator_ids']) ) {

		    $ids = array_map(function($item) {
			    return (int)sanitize_text_field($item);
		    }, explode(',', $_GET['calculator_ids']));

			$resultIds = [];
		    for ( $i = 0; $i < count($ids); $i++ ) {
			    $newCalculator = [ 'post_type' => 'cost-calc', 'post_status' => 'publish',];
			    $duplicatedPostId = wp_insert_post($newCalculator);

			    $data = [
				    'id' => $duplicatedPostId,
				    'title' => get_post_meta($ids[$i], 'stm-name', true),
				    'formula' => get_post_meta($ids[$i], 'stm-formula', true),
				    'settings' => get_option('stm_ccb_form_settings_' . $ids[$i], true),
				    'builder' => get_post_meta($ids[$i], 'stm-fields', true),
				    'conditions' => get_post_meta($ids[$i], 'stm-conditions', true),
				    'styles' => get_post_meta($ids[$i], 'ccb-custom-styles', true),
				    'fields' => get_post_meta($ids[$i], 'ccb-custom-fields', true),
			    ];

			    if ( ccb_update_calc_values($data) ) {
				    array_push($resultIds, $duplicatedPostId );
			    }
		    }

		    $result['success'] = true;
		    $result['existing'] = self::get_calculator_list();
		    $result['duplicated_ids'] = $resultIds;
            $result['message'] = __('Calculators duplicated successfully', 'cost-calculator-builder');
	    }


        if (!empty($_GET['calc_id'])) {
            $calc_id = (int) sanitize_text_field( $_GET['calc_id'] );

            $my_post = [
                'post_type' => 'cost-calc',
                'post_status' => 'publish',
            ];

            // get id
            $id = wp_insert_post($my_post);

            $data = [
                'id' => $id,
                'title' => get_post_meta($calc_id, 'stm-name', true),
                'formula' => get_post_meta($calc_id, 'stm-formula', true),
                'settings' => get_option('stm_ccb_form_settings_' . $calc_id, true),
                'builder' => get_post_meta($calc_id, 'stm-fields', true),
                'conditions' => get_post_meta($calc_id, 'stm-conditions', true),
                'styles' => get_post_meta($calc_id, 'ccb-custom-styles', true),
                'fields' => get_post_meta($calc_id, 'ccb-custom-fields', true),
            ];

            if (ccb_update_calc_values($data)) {
                $result['success'] = true;
                $result['existing'] = self::get_calculator_list();
                $result['message'] = __('Calculator duplicated successfully', 'cost-calculator-builder');
                $result['duplicated_id'] = $id;
            }

        }

        wp_send_json($result);
    }

    /**
     *  Generate calc id(create cost-calc post) and send
     */
    public static function create_calc_id() {

        check_ajax_referer( 'ccb_create_id', 'nonce' );

        if ( ! current_user_can('publish_posts') ) {
            wp_send_json_error( __('You are not allowed to run this action', 'cost-calculator-builder') );
        }

        // create cost-calc post and get id
        $id = wp_insert_post([
            'post_type' => 'cost-calc',
            'post_status' => 'publish',
        ]);
        // send data
        wp_send_json([
            'id'            => $id,
            'success'       => true,
            'forms'         => ccb_contact_forms(),
            'products'      => ccb_woo_products(),
            'categories'    => ccb_woo_categories(),
            'fields'        => CCBSettingsData::fields_data(),
            'custom_fields' => CCBCustomFields::custom_fields(),
            'custom_styles' => CCBCustomFields::custom_default_styles(),
            'desc_options'  => [
                self::DESC_POSITION_BEFORE => __( 'Show before field', 'cost-calculator-builder' ),
                self::DESC_POSITION_AFTER  => __( 'Show after field', 'cost-calculator-builder' ),
            ],
        ]);
    }

    /**
     * Delete calc by id
     */
    public static function delete_calc() {

        check_ajax_referer( 'ccb_delete_calc', 'nonce' );

        if ( ! current_user_can('publish_posts') ) {
            wp_send_json_error( __('You are not allowed to run this action', 'cost-calculator-builder') );
        }

        $result = [
            'success' => false,
            'existing' => [],
            'message' => __("Couldn't delete calculator, please try again!", "cost-calculator-builder"),
        ];

		if ( isset($_GET['calculator_ids']) ) {

			$ids = array_map(function($item) {
				return (int)sanitize_text_field($item);
			}, explode(',', $_GET['calculator_ids']));

			for ( $i = 0; $i < count($ids); $i++ ) {
				wp_delete_post( $ids[$i] );
				delete_post_meta( $ids[$i], 'cost-calc' );
				ccb_update_woocommerce_calcs( $ids[$i], true );
			}

			$result['success']  = true;
			$result['existing'] = self::get_calculator_list();
			$result['message']  = __('Calculators deleted successfully', 'cost-calculator-builder');
		}

        if (isset($_GET['calc_id'])) {

            $calc_id = (int) sanitize_text_field( $_GET['calc_id'] );
            wp_delete_post($calc_id);
            delete_post_meta($calc_id, 'cost-calc');
            ccb_update_woocommerce_calcs($calc_id, true);

            $result['success']  = true;
            $result['existing'] = self::get_calculator_list();
            $result['message']  = __('Calculator deleted successfully', 'cost-calculator-builder');
        }

        wp_send_json($result);
    }

    /**
     * Save Custom Styles
     */
    public static function save_custom() {

        check_ajax_referer( 'ccb_save_custom', 'nonce' );

        if ( ! current_user_can('publish_posts') ) {
            wp_send_json_error( __('You are not allowed to run this action', 'cost-calculator-builder') );
        }

        $result = [
            'success' => false,
            'message' => 'Something went wrong',
        ];

        if (isset($_POST['action']) && $_POST['action'] === 'calc_save_custom') {
            $data = apply_filters('stm_ccb_sanitize_array', $_POST);

            $content = json_decode(str_replace('\"', '"', $data['content']), true);
            $fields  = isset($content['fields']) ? $content['fields'] : CCBCustomFields::custom_fields();;
            $styles  = isset($content['styles']) ? $content['styles'] : CCBCustomFields::custom_default_styles();

            update_post_meta($data['id'], 'ccb-custom-fields', apply_filters('stm_ccb_sanitize_array', $fields));
            update_post_meta($data['id'], 'ccb-custom-styles',  apply_filters('stm_ccb_sanitize_array', $styles));

            $result['success'] = true;
            $result['message'] = 'Custom Changes Saved successfully';
        }

        wp_send_json($result);
    }

    /**
     * Get All existing calculator
     */
    public static function get_existing() {

        check_ajax_referer( 'ccb_get_existing', 'nonce' );

        if ( ! current_user_can('publish_posts') ) {
            wp_send_json_error( __('You are not allowed to run this action', 'cost-calculator-builder') );
        }

        $result = [
            'forms' => [],
            'existing' => [],
        ];

        if (is_array(self::get_calculator_list())) {
            $result['success'] = true;
            $result['existing'] = self::get_calculator_list();

            /* pro-features */
            $result['forms'] = ccb_contact_forms();
            $result['products'] = ccb_woo_products();
        }

        wp_send_json($result);
    }

    /**
     * Save all calculator settings via calc id
     */
    public static function save_settings() {

        if ( ! current_user_can('publish_posts') ) {
            wp_send_json_error( __('You are not allowed to run this action', 'cost-calculator-builder') );
        }

        $result = [
            'existing'  => [],
            'success'   => false,
            'message'   => 'Something went wrong',
        ];

        $request_body = file_get_contents('php://input');
        $request_data = json_decode($request_body, true);


        $data = apply_filters('stm_ccb_sanitize_array', $request_data);

        if ( isset($data['settings']['formFields']['body']) ) {
            $content = $data['settings']['formFields']['body'];
            $content = str_replace('\\n',  '<br>', $content);
            $data['settings']['formFields']['body'] = str_replace('\\', '', $content);
        }

        if ( !empty($data) && ccb_update_calc_values($data) ) {
            $result['success']  = true;
            $result['message']  = 'Calculator updated successfully';
            $result['existing'] = self::get_calculator_list();
        }

        wp_send_json($result);
    }

    /**
     * Return ready array for response
     * @return array
     */
    public static function get_calculator_list() {

        $result = [];
        $existing = ccb_calc_get_all_posts('cost-calc');

        if (is_array($existing)) {
            foreach (ccb_calc_get_all_posts('cost-calc') as $key => $value) {

                $temp = [];
                $temp['id'] = $key;
                $temp['project_name'] = !empty($value) ? $value : 'name is empty';

                $result[] = $temp;
            }
        }
        return $result;
    }

}