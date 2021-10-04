<?php

defined( "ABSPATH" ) OR exit;

class EZFC_VC_Widget extends WPBakeryShortCode {
	public function __construct() {
		add_action( "init", array( $this, "add_widget" ) );
		add_shortcode( "ezfc_vc_form", array( $this, "shortcode" ) );
	}

	// Element Mapping
	public function add_widget() {
		 
		// Stop all if VC is not enabled
		if ( !defined( "WPB_VC_VERSION" ) ) {
			return;
		}

		if (!class_exists("Ezfc_backend")) {
			require_once(EZFC_PATH . "class.ezfc_backend.php");
		}

		$ezfc = Ezfc_backend::instance();
		$forms = $ezfc->forms_get();

		$vc_forms = array("0" => __("No form selected.", "ezfc"));
		foreach ($forms as $form) {
			$label = "#{$form->id} {$form->name}";
			$vc_forms[$label] = $form->id;
		}
		 
		// Map the block with vc_map()
		vc_map( 
			array(
				"name" => __("ez Form Calculator", "ezfc"),
				"base" => "ezfc_vc_form",
				"description" => __("Show an ez Form Calculator form", "ezfc"), 
				"category" => "ezPlugins",
				"icon" => EZFC_URL . "/assets/img/ez-icon.png",            
				"params" => array(   
					array(
						"type" => "dropdown",
						"holder" => "div",
						"class" => "",
						"heading" => __( "Form", "ezfc" ),
						"param_name" => "form_id",
						"value" => $vc_forms,
						"description" => ""
					)
				)
			)
		);
	}
	 
	 
	// Element HTML
	public function shortcode( $atts ) {
		// Params extraction
		extract(
			shortcode_atts(
				array(
					"form_id" => ""
				),
				$atts
			)
		);
		 
		return do_shortcode("[ezfc id='{$form_id}' /]");
		 
	}
}

new EZFC_VC_Widget();