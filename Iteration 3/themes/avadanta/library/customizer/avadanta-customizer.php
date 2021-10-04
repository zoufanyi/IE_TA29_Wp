<?php
/************************* Footer Callback function *********************************/

 	function avadanta_footer_callback ( $control ) 
 	{
		if( true == $control->manager->get_setting ('footer_enable')->value()){
 			return true;
 		}
 		else {
 			return false;
 		} 		
 	}

/************************* Related Post Callback function *********************************/

 	function avadanta_rt_post_callback ( $control ) 
 	{
		if( true == $control->manager->get_setting ('avadanta_enable_related_post')->value()){
 			return true;
 		}
 		else {
 			return false;
 		} 		
 	}

/************************* Theme Customizer with Sanitize function *********************************/
function avadanta_theme_option( $wp_customize )
{
	function avadanta_sanitize_text( $input ) {
		return wp_kses_post( force_balance_tags( $input ) );
	}

	function avadanta_sanitize_checkbox( $checked ) {
		// Boolean check.
		return ( ( isset( $checked ) && true == $checked ) ? true : false );
	}



	function avadanta_sanitize_select( $input, $setting ){
	         
	    //input must be a slug: lowercase alphanumeric characters, dashes and underscores are allowed only
	    $input = sanitize_key($input);
	 
	    //get the list of possible radio box options 
	    $choices = $setting->manager->get_control( $setting->id )->choices;
	                             
	    //return input if valid or return default option
	    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );                
	             
	}

	$wp_customize->add_panel('avadanta_theme_panel',
		array(
			'priority' => 2,
			'capability' => 'edit_theme_options',
			'title' => esc_html__('Avadanta theme options','avadanta')
		)
	);
}
add_action('customize_register','avadanta_theme_option');