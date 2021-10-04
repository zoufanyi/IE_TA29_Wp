<?php

namespace cBuilder\Classes;

class CCBExportImport {

	static $demoCalculatorsFilePath = CALC_PATH . '/demo-sample/cost_calculator_data.txt';

	/** Get total calculators count for custom file*/
	public static function custom_import_calculators_total() {
		check_ajax_referer( 'ccb_custom_import', 'nonce' );

		if ( ! current_user_can('publish_posts') ) {
			wp_send_json_error( __('You are not allowed to run this action', 'cost-calculator-builder') );
		}

		$result = [
			'message' => [],
			'success' => false,
		];

		$files = $_FILES;

		if (!empty($files['file']) && file_exists($files['file']['tmp_name'])) {
			$content = file_get_contents($files['file']['tmp_name']);
			$content = is_string($content) ? json_decode($content) : $content;

			if (is_array($content)) {
				$result['success'] = true;
				$result['message']['calculators'] = count($content);
				$content = json_encode($content);
				update_option('ccb_demo_import_content', $content);
			}
		}

		wp_send_json($result);
	}

	/** Get total calculators count for demo file*/
	public static function demo_import_calculators_total() {
		check_ajax_referer( 'ccb_demo_import_apply', 'nonce' );
		if ( ! current_user_can('publish_posts') ) {
			wp_send_json_error( __('You are not allowed to run this action', 'cost-calculator-builder') );
		}

		$totalCalculators = 0;
		if ( file_exists( self::$demoCalculatorsFilePath ) ) {
			$fileContents = file_get_contents( self::$demoCalculatorsFilePath );
			$totalCalculators = self::get_file_total_calculators($fileContents);
		}
		wp_send_json( ['calculators' => $totalCalculators ] );
	}

	/** Load custom and demo import calculators*/
	public static function import_run( ) {
		check_ajax_referer( 'ccb_demo_import_run', 'nonce' );

		if ( ! current_user_can('publish_posts') ) {
			wp_send_json_error( __('You are not allowed to run this action', 'cost-calculator-builder') );
		}

		$result = ["success" => true, "step" => null, "key" => 0 ];

		$request_data = apply_filters('stm_ccb_sanitize_array', $_POST);

		if ( isset($request_data['step']) && isset($request_data['key']) ) {

			$result['step'] = sanitize_text_field( $request_data['step'] );
			$result['key']  = sanitize_text_field( $request_data['key'] );

			$contents = null;
			$result['success'] = false;

			if ( file_exists( self::$demoCalculatorsFilePath ) && empty($request_data['is_custom_import']) ) {

				$contents = file_get_contents(self::$demoCalculatorsFilePath);
				$contents = json_decode($contents, true);

			} elseif (!empty($request_data['is_custom_import']) && !empty(get_option('ccb_demo_import_content'))) {

				$contents = get_option('ccb_demo_import_content');
				$contents = is_string($contents) ? json_decode($contents) : $contents;
			}

			$contents = json_decode(json_encode($contents), true);
			$item = $contents[$result['key']];

			if ( isset($item['stm-fields']) ) {
				self::addCalculatorData( $item, $result );
			}
		}

		wp_send_json($result);
	}

	/**
	 * add calculator data and append values to result
	 * @param $data
	 * @param $result
	 */
	public static function addCalculatorData( $data, &$result ) {

		$title   = !empty( $data['stm-name'] ) ? sanitize_text_field( $data['stm-name'] ) : 'empty';
		$calculator_post = ['post_type' => 'cost-calc', 'post_title' => $title, 'post_status' => 'publish',];

		$calculator_id = wp_insert_post($calculator_post);
		update_post_meta($calculator_id, 'stm-fields', isset( $data['stm-fields'] ) ? (array)$data['stm-fields'] : []);
		update_post_meta($calculator_id, 'stm-formula', isset($data['stm-formula']) ? (array)$data['stm-formula'] : []);
		update_post_meta($calculator_id, 'stm-conditions', isset($data['stm-conditions']) ? (array)$data['stm-conditions'] : []);
		update_post_meta($calculator_id, 'ccb-custom-fields', isset($data['ccb-custom-fields']) ? (array)$data['ccb-custom-fields'] : []);
		update_post_meta($calculator_id, 'ccb-custom-styles', isset($data['ccb-custom-styles']) ? (array)$data['ccb-custom-styles'] : []);
		update_post_meta($calculator_id, 'stm-name', isset($data['stm-name']) ? sanitize_text_field($data['stm-name']) : 'empty');


		$data['stm_ccb_form_settings'] = (array)$data['stm_ccb_form_settings'];
		update_option('stm_ccb_form_settings_' . sanitize_text_field( $calculator_id ), apply_filters('stm_ccb_sanitize_array', $data['stm_ccb_form_settings']));

		$result['key']++;
		$result['data']     = 'Create Calculator: ' . $title;
		$result['success']  = true;
		$result['existing'] = CCBCalculators::get_calculator_list();
	}

	/**
	 * @param  string $fileContents
	 * @return int
	 */
	private static function get_file_total_calculators( $fileContents ) {
		$fileContents     = is_json_string( $fileContents )? json_decode($fileContents, true): [];
		return count($fileContents);
	}

    public static function export_calculators() {
        if (wp_verify_nonce($_REQUEST['ccb_nonce'], 'ccb-export-nonce')) {

        	$calculators = CCBCalculators::getWPCalculatorsData();
            /** return if no calculators data */
	        if ( count($calculators) <= 0 ) {
		        wp_send_json([
			        'success' => true,
			        'message' => 'There is no calculators yet!'
		        ]);
		        die();
	        }

            $data     = self::parse_export_data( $calculators );
            $data     = json_encode($data);
            $filename = 'cost_calculator_data_' . date('mdYhis') . '.txt';

            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename=' . $filename);
            header('Content-Type: text/xml; charset=' . get_option('blog_charset'), true);

            echo sanitize_without_tag_clean($data);
            die();
        }
    }

    public static function parse_export_data( $calculators ){
	    $result = [];

	    if ( !is_array( $calculators ) || !reset($calculators) instanceof \WP_Post ){
	    	return $result;
	    }

	    foreach ( $calculators as $post ) {
		    if ( isset($post->ID) ) {
			    $post_store = get_post_meta($post->ID);

			    $calculator                          = [];
			    $calculator['stm-name']              = $post_store['stm-name'][0];
			    $calculator['stm-fields']            = unserialize($post_store['stm-fields'][0]);
			    $calculator['stm-formula']           = unserialize($post_store['stm-formula'][0]);
			    $calculator['stm-conditions']        = unserialize($post_store['stm-conditions'][0]);
			    $calculator['ccb-custom-styles']     = unserialize($post_store['ccb-custom-styles'][0]);
			    $calculator['ccb-custom-fields']     = unserialize($post_store['ccb-custom-fields'][0]);
			    $calculator['stm_ccb_form_settings'] = isset(get_option('stm_ccb_form_settings_' . $post->ID)[0]) ? get_option('stm_ccb_form_settings_' . $post->ID)[0] : get_option('stm_ccb_form_settings_' . $post->ID);

			    array_push($result, $calculator);
		    }
	    }
	    return $result;
    }

    public static function recalculate_coordinates( $calculatorList ){

	    foreach ($calculatorList as $calculator){
		    $isNeedRecalculateCoordinates = false;
		    $isExistTarget                = true;
		    $calculatorConditions = get_post_meta($calculator['id'], 'stm-conditions', true);

		    $oldLogicXValues = array_filter(array_column($calculatorConditions['nodes'], 'x'), function($value) {
			    return ($value < 0 || $value > 1160 ) ? true : false;
		    });
		    $oldLogicYValues = array_filter(array_column($calculatorConditions['nodes'], 'y'), function($value) {
			    return ($value < 0 || $value > 437 ) ? true : false;
		    });

		    $isNeedRecalculateCoordinates = count( array_merge($oldLogicXValues, $oldLogicYValues) ) > 0;
		    $isExistTarget                = count( array_column($calculatorConditions['links'], 'target') ) > 0;

		    if ( $isExistTarget ) {
			    continue;
		    }

		    if ( $isNeedRecalculateCoordinates ) {
			    foreach ($calculatorConditions['nodes'] as $key => $node) {
				    $x = 1024 + (float)$node['x'];
				    if($x < 7) { $x = 7; }

				    $y = 140 + (float)$node['y'];
				    if($y < 7) { $y = 7;}
				    if($y > 438) { $y = 438;}

				    $calculatorConditions['nodes'][$key]['y'] = $y;
				    $calculatorConditions['nodes'][$key]['x'] = $x;
			    }
		    }

		    foreach ($calculatorConditions['links'] as $linkKey => $nodeLink) {

			    $fromNodeKey = array_search($nodeLink['from'], array_column($calculatorConditions['nodes'], 'id'));
			    $toNodeKey   = array_search($nodeLink['to'], array_column($calculatorConditions['nodes'], 'id'));

			    $calculatorConditions['links'][$linkKey]['target'] = [
				    'class_name' => 'node-output-point right side',
				    'x' => (float)$calculatorConditions['nodes'][$fromNodeKey]['x'] + 165,
				    'y' => (float)$calculatorConditions['nodes'][$fromNodeKey]['y'] + 29,
			    ];
			    $calculatorConditions['links'][$linkKey]['input_coordinates'] = [
				    'x' => (float)$calculatorConditions['nodes'][$toNodeKey]['x'],
				    'y' => (float)$calculatorConditions['nodes'][$toNodeKey]['y'] + 29,
			    ];
		    }
		    update_post_meta($calculator['id'], 'stm-conditions', apply_filters('stm_ccb_sanitize_array', $calculatorConditions));
	    }

    }

}